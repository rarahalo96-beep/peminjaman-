<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Transaction;
use App\Models\QrCode;
use App\Models\Review;
use App\Models\Fine;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();
        $borrowedBooks = Transaction::where('user_id', $user->id)->where('status', 'borrowed')->with('book')->get();
        return view('user.dashboard', compact('borrowedBooks'));
    }

    public function statistics()
    {
        $user = Auth::user();
        $transactions = Transaction::where('user_id', $user->id)->with('book')->get();
        $reviews = Review::where('user_id', $user->id)->get();

        // Statistik Peminjaman
        $totalBorrowed = $transactions->count();
        $currentlyBorrowing = $transactions->where('status', 'borrowed')->count();
        $totalReturned = $transactions->where('status', 'returned')->count();
        $avgBorrowDuration = $transactions->where('status', 'returned')->avg(fn($t) => $t->return_date->diffInDays($t->borrow_date)) ?? 0;
        
        // Statistik Rating & Review
        $totalReviews = $reviews->count();
        $avgRating = $reviews->avg('rating') ?? 0;
        $reviewStats = [
            5 => $reviews->where('rating', 5)->count(),
            4 => $reviews->where('rating', 4)->count(),
            3 => $reviews->where('rating', 3)->count(),
            2 => $reviews->where('rating', 2)->count(),
            1 => $reviews->where('rating', 1)->count(),
        ];

        // Top 3 Buku Terbaru Dipinjam
        $recentBorrows = $transactions->sortByDesc('created_at')->take(3);

        // Top 3 Buku dengan Rating Tertinggi
        $topRatedBooks = Book::with(['reviews' => function($query) use ($user) {
            $query->where('user_id', $user->id);
        }])
        ->get()
        ->filter(fn($b) => $b->reviews->count() > 0)
        ->sortByDesc(fn($b) => $b->reviews->first()->rating)
        ->take(3);

        return view('user.statistics', compact(
            'totalBorrowed',
            'currentlyBorrowing',
            'totalReturned',
            'avgBorrowDuration',
            'totalReviews',
            'avgRating',
            'reviewStats',
            'recentBorrows',
            'topRatedBooks'
        ));
    }

    public function finesTracker()
    {
        $user = Auth::user();
        $fines = Fine::where('user_id', $user->id)->with('transaction.book')->orderBy('created_at', 'desc')->get();
        
        // Hitung statistik denda
        $totalFines = $fines->count();
        $totalAmount = $fines->sum('amount');
        $unpaidAmount = $fines->where('status', 'unpaid')->sum('amount');
        $unpaidCount = $fines->where('status', 'unpaid')->count();
        $paidCount = $fines->where('status', 'paid')->count();

        return view('user.fines', compact(
            'fines',
            'totalFines',
            'totalAmount',
            'unpaidAmount',
            'unpaidCount',
            'paidCount'
        ));
    }

    public function borrowPage()
    {
        $books = Book::where('stock', '>', 0)->get();
        return view('user.borrow', compact('books'));
    }

    public function returnPage()
    {
        $user = Auth::user();
        $borrowedBooks = Transaction::where('user_id', $user->id)->where('status', 'borrowed')->with('book')->orderBy('created_at', 'desc')->get();
        return view('user.return', compact('borrowedBooks'));
    }

    public function borrowHistory()
    {
        $user = Auth::user();
        $transactions = Transaction::where('user_id', $user->id)->with('book')->orderBy('created_at', 'desc')->get();
        return view('user.history', compact('transactions'));
    }

    public function searchBooks(Request $request)
    {
        $query = $request->get('q');
        if ($query) {
            $books = Book::where('title', 'like', '%' . $query . '%')
                         ->orWhere('author', 'like', '%' . $query . '%')
                         ->orWhere('publisher', 'like', '%' . $query . '%')
                         ->get();
        } else {
            $books = Book::all();
        }
        return view('user.search', compact('books', 'query'));
    }

    public function borrowBook(Book $book)
    {
        $user = Auth::user();

        // Check if user has any overdue books (simplified, assume no denda for now)
        $overdue = Transaction::where('user_id', $user->id)
                              ->where('status', 'borrowed')
                              ->where('return_date', '<', now())
                              ->exists();

        if ($overdue) {
            return redirect()->back()->with('error', 'You have overdue books. Please return them first.');
        }

        // Check stock
        if ($book->stock <= 0) {
            return redirect()->back()->with('error', 'Book is out of stock.');
        }

        // Borrow
        Transaction::create([
            'user_id' => $user->id,
            'book_id' => $book->id,
            'borrow_date' => now(),
            'status' => 'borrowed',
        ]);

        $book->decrement('stock');

        return redirect()->route('user.borrow')->with('success', 'Buku berhasil dipinjam!');
    }

    /**
     * Fallback endpoint: accept POST to /user/borrow with book id provided in body or query.
     * This is defensive for cases where a form submits to /user/borrow without the {book} path param.
     */
    public function borrowBookShortcut(Request $request)
    {
        // Prefer explicit 'book_id' field
        $id = $request->input('book_id') ?? $request->query('book_id');

        // If not provided, try to detect a numeric key or value in query/body
        if (!$id) {
            // check query string keys like ?14=
            foreach ($request->query() as $k => $v) {
                if (is_numeric($k)) {
                    $id = $k;
                    break;
                }
                if (is_numeric($v)) {
                    $id = $v;
                    break;
                }
            }
        }

        if (!$id) {
            // also inspect posted form data
            foreach ($request->post() as $k => $v) {
                if (is_numeric($k)) {
                    $id = $k;
                    break;
                }
                if (is_numeric($v)) {
                    $id = $v;
                    break;
                }
            }
        }

        if (!$id) {
            return redirect()->back()->with('error', 'Book id tidak ditemukan.');
        }

        $book = Book::find($id);
        if (!$book) {
            return redirect()->back()->with('error', 'Buku tidak ditemukan.');
        }

        return $this->borrowBook($book);
    }

    public function returnBook(Transaction $transaction)
    {
        if ($transaction->user_id !== Auth::id() || $transaction->status !== 'borrowed') {
            return redirect()->back()->with('error', 'Invalid transaction.');
        }

        $transaction->update([
            'return_date' => now(),
            'status' => 'returned',
        ]);

        $transaction->book->increment('stock');

        return redirect()->route('user.return')->with('success', 'Buku berhasil dikembalikan!');
    }

    public function scanQrPage()
    {
        return view('user.scan-qr');
    }

    public function processQrScan(Request $request)
    {
        $qrCode = $request->get('qr_code');
        
        $qrCodeRecord = QrCode::where('code', $qrCode)->first();

        if (!$qrCodeRecord) {
            return response()->json([
                'success' => false,
                'message' => 'QR Code tidak ditemukan'
            ], 404);
        }

        $book = $qrCodeRecord->book;
        
        if (!$book) {
            return response()->json([
                'success' => false,
                'message' => 'Buku tidak ditemukan'
            ], 404);
        }

        $user = Auth::user();

        // Check if user has any overdue books
        $overdue = Transaction::where('user_id', $user->id)
                              ->where('status', 'borrowed')
                              ->where('return_date', '<', now())
                              ->exists();

        if ($overdue) {
            return response()->json([
                'success' => false,
                'message' => 'Anda memiliki buku yang belum dikembalikan. Silakan kembalikan terlebih dahulu.'
            ], 400);
        }

        // Check if already borrowed
        $alreadyBorrowed = Transaction::where('user_id', $user->id)
                                     ->where('book_id', $book->id)
                                     ->where('status', 'borrowed')
                                     ->exists();
        
        if ($alreadyBorrowed) {
            return response()->json([
                'success' => false,
                'message' => 'Anda sudah meminjam buku ini'
            ], 400);
        }

        // Check stock
        if ($book->stock <= 0) {
            return response()->json([
                'success' => false,
                'message' => 'Stok buku habis'
            ], 400);
        }

        // Borrow
        Transaction::create([
            'user_id' => $user->id,
            'book_id' => $book->id,
            'borrow_date' => now(),
            'status' => 'borrowed',
        ]);

        $book->decrement('stock');

        return response()->json([
            'success' => true,
            'message' => 'Buku berhasil dipinjam',
            'book' => [
                'title' => $book->title,
                'author' => $book->author,
            ]
        ]);
    }

    public function reviewPage()
    {
        $books = Book::with(['reviews' => function($query) {
            $query->where('user_id', Auth::id());
        }])->get();
        
        return view('user.review', compact('books'));
    }

    public function storeReview(Request $request, Book $book)
    {
        $user = Auth::user();

        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:500',
        ]);

        // Check if user already reviewed this book
        $existingReview = Review::where('user_id', $user->id)
                                ->where('book_id', $book->id)
                                ->first();

        if ($existingReview) {
            // Update existing review
            $existingReview->update([
                'rating' => $request->rating,
                'comment' => $request->comment,
            ]);
            $message = 'Review berhasil diperbarui!';
        } else {
            // Create new review
            Review::create([
                'user_id' => $user->id,
                'book_id' => $book->id,
                'rating' => $request->rating,
                'comment' => $request->comment,
            ]);
            $message = 'Review berhasil ditambahkan!';
        }

        return redirect()->route('user.review')->with('success', $message);
    }
}

