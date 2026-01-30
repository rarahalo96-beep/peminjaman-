<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Fine;
use App\Models\QrCode;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function dashboard()
    {
        $totalBooks = Book::sum('stock');
        $totalUsers = User::where('role', 'siswa')->count();
        $totalTransactions = Transaction::count();
        $borrowedBooks = Transaction::where('status', 'borrowed')->count();

        return view('admin.dashboard', compact('totalBooks', 'totalUsers', 'totalTransactions', 'borrowedBooks'));
    }

    // Books CRUD
    public function books(Request $request)
    {
        $query = $request->get('q');
        $books = Book::query();

        if ($query) {
            $books->where('title', 'like', '%' . $query . '%')
                  ->orWhere('author', 'like', '%' . $query . '%')
                  ->orWhere('publisher', 'like', '%' . $query . '%');
        }

        $books = $books->get();
        return view('admin.books.index', compact('books', 'query'));
    }

    public function createBook()
    {
        return view('admin.books.form');
    }

    public function storeBook(Request $request)
    {
        try {
            $request->validate([
                'title' => 'required',
                'author' => 'required',
                'publisher' => 'required',
                'year' => 'required|integer',
                'stock' => 'required|integer|min:0',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120', // Increased to 5MB
            ]);

            $data = $request->all();

            if ($request->hasFile('image')) {
                $imageName = time() . '.' . $request->image->extension();
                $request->image->move(public_path('images/books'), $imageName);
                $data['image'] = $imageName;
            }

            Book::create($data);
            return redirect()->route('admin.books')->with('success', 'Book added successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to add book: ' . $e->getMessage())->withInput();
        }
    }

    public function editBook(Book $book)
    {
        return view('admin.books.form', compact('book'));
    }

    public function updateBook(Request $request, Book $book)
    {
        try {
            $request->validate([
                'title' => 'required',
                'author' => 'required',
                'publisher' => 'required',
                'year' => 'required|integer',
                'stock' => 'required|integer|min:0',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120', // Increased to 5MB
            ]);

            $data = $request->all();

            if ($request->hasFile('image')) {
                // Delete old image if exists
                if ($book->image && file_exists(public_path('images/books/' . $book->image))) {
                    unlink(public_path('images/books/' . $book->image));
                }

                $imageName = time() . '.' . $request->image->extension();
                $request->image->move(public_path('images/books'), $imageName);
                $data['image'] = $imageName;
            }

            $book->update($data);
            return redirect()->route('admin.books')->with('success', 'Book updated successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to update book: ' . $e->getMessage())->withInput();
        }
    }

    public function destroyBook(Book $book)
    {
        // Delete image if exists
        if ($book->image && file_exists(public_path('images/books/' . $book->image))) {
            unlink(public_path('images/books/' . $book->image));
        }

        $book->delete();
        return redirect()->route('admin.books')->with('success', 'Book deleted successfully.');
    }

    // Members CRUD
    public function members(Request $request)
    {
        $query = $request->get('q');
        $members = User::where('role', 'siswa');

        if ($query) {
            $members->where('name', 'like', '%' . $query . '%')
                    ->orWhere('email', 'like', '%' . $query . '%');
        }

        $members = $members->get();
        return view('admin.members.index', compact('members', 'query'));
    }

    public function createMember()
    {
        return view('admin.members.form');
    }

    public function storeMember(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'role' => 'siswa',
        ]);

        return redirect()->route('admin.members')->with('success', 'Member added successfully.');
    }

    public function editMember(User $member)
    {
        return view('admin.members.form', compact('member'));
    }

    public function updateMember(Request $request, User $member)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email,' . $member->id,
        ]);

        $member->update($request->only(['name', 'email']));
        return redirect()->route('admin.members')->with('success', 'Member updated successfully.');
    }

    public function destroyMember(User $member)
    {
        $member->delete();
        return redirect()->route('admin.members')->with('success', 'Member deleted successfully.');
    }

    // Transactions
    public function transactions()
    {
        $transactions = Transaction::with('user', 'book')->get();
        return view('admin.transactions.index', compact('transactions'));
    }

    // Fines CRUD
    public function fines(Request $request)
    {
        $query = $request->get('q');
        $fines = Fine::with('user', 'transaction');

        if ($query) {
            $fines->whereHas('user', function ($q) use ($query) {
                $q->where('name', 'like', '%' . $query . '%');
            });
        }

        $fines = $fines->get();
        return view('admin.fines.index', compact('fines', 'query'));
    }

    public function createFine()
    {
        $users = User::where('role', 'siswa')->get();
        $transactions = Transaction::where('status', 'returned')->get();
        return view('admin.fines.form', compact('users', 'transactions'));
    }

    public function storeFine(Request $request)
    {
        try {
            $request->validate([
                'user_id' => 'required|exists:users,id',
                'transaction_id' => 'nullable|exists:transactions,id',
                'amount' => 'required|numeric|min:0.01',
                'reason' => 'required|string',
            ]);

            Fine::create($request->all());
            return redirect()->route('admin.fines')->with('success', 'Denda created successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to create denda: ' . $e->getMessage())->withInput();
        }
    }

    public function editFine(Fine $fine)
    {
        $users = User::where('role', 'siswa')->get();
        $transactions = Transaction::where('status', 'returned')->get();
        return view('admin.fines.form', compact('fine', 'users', 'transactions'));
    }

    public function updateFine(Request $request, Fine $fine)
    {
        try {
            $request->validate([
                'user_id' => 'required|exists:users,id',
                'transaction_id' => 'nullable|exists:transactions,id',
                'amount' => 'required|numeric|min:0.01',
                'reason' => 'required|string',
                'status' => 'required|in:unpaid,paid',
                'paid_date' => 'nullable|date|required_if:status,paid',
            ]);

            $data = $request->all();
            if ($request->status === 'unpaid') {
                $data['paid_date'] = null;
            }

            $fine->update($data);
            return redirect()->route('admin.fines')->with('success', 'Denda updated successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to update denda: ' . $e->getMessage())->withInput();
        }
    }

    public function destroyFine(Fine $fine)
    {
        $fine->delete();
        return redirect()->route('admin.fines')->with('success', 'Denda deleted successfully.');
    }

    // QR Codes CRUD
    public function qrCodes(Request $request)
    {
        $query = $request->get('q');
        $qrCodes = QrCode::with('book');

        if ($query) {
            $qrCodes->where('title', 'like', '%' . $query . '%')
                    ->orWhereHas('book', function ($q) use ($query) {
                        $q->where('title', 'like', '%' . $query . '%');
                    });
        }

        $qrCodes = $qrCodes->get();
        return view('admin.qrcodes.index', compact('qrCodes', 'query'));
    }

    public function createQrCode()
    {
        $books = Book::all();
        return view('admin.qrcodes.form', compact('books'));
    }

    public function storeQrCode(Request $request)
    {
        try {
            $request->validate([
                'book_id' => 'nullable|exists:books,id',
                'code' => 'required|unique:qr_codes',
                'title' => 'required|string',
                'description' => 'nullable|string',
                'type' => 'required|in:book,transaction',
            ]);

            QrCode::create($request->all());
            return redirect()->route('admin.qrcodes')->with('success', 'QR Code created successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to create QR Code: ' . $e->getMessage())->withInput();
        }
    }

    public function editQrCode(QrCode $qrCode)
    {
        $books = Book::all();
        return view('admin.qrcodes.form', compact('qrCode', 'books'));
    }

    public function updateQrCode(Request $request, QrCode $qrCode)
    {
        try {
            $request->validate([
                'book_id' => 'nullable|exists:books,id',
                'code' => 'required|unique:qr_codes,code,' . $qrCode->id,
                'title' => 'required|string',
                'description' => 'nullable|string',
                'type' => 'required|in:book,transaction',
            ]);

            $qrCode->update($request->all());
            return redirect()->route('admin.qrcodes')->with('success', 'QR Code updated successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to update QR Code: ' . $e->getMessage())->withInput();
        }
    }

    public function destroyQrCode(QrCode $qrCode)
    {
        $qrCode->delete();
        return redirect()->route('admin.qrcodes')->with('success', 'QR Code deleted successfully.');
    }

    public function generateQrCode(QrCode $qrCode)
    {
        return view('admin.qrcodes.generate', compact('qrCode'));
    }

    // Borrow History
    public function borrowHistory(Request $request)
    {
        $query = $request->get('q');
        $status = $request->get('status');
        $borrowHistories = Transaction::with('user', 'book');

        if ($query) {
            $borrowHistories->whereHas('user', function ($q) use ($query) {
                $q->where('name', 'like', '%' . $query . '%');
            })->orWhereHas('book', function ($q) use ($query) {
                $q->where('title', 'like', '%' . $query . '%');
            });
        }

        if ($status) {
            $borrowHistories->where('status', $status);
        }

        $borrowHistories = $borrowHistories->orderBy('created_at', 'desc')->get();
        return view('admin.borrowhistory.index', compact('borrowHistories', 'query', 'status'));
    }

    public function borrowHistoryDetail(Transaction $transaction)
    {
        $transaction->load('user', 'book');
        return view('admin.borrowhistory.detail', compact('transaction'));
    }
}
