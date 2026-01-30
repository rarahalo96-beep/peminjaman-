<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('/login');
});

Route::get('/dashboard', function () {
    if (auth()->user()->isAdmin()) {
        return redirect()->route('admin.dashboard');
    } else {
        return redirect()->route('user.dashboard');
    }
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Admin routes
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

    // Books
    Route::get('/books', [AdminController::class, 'books'])->name('books');
    Route::get('/books/create', [AdminController::class, 'createBook'])->name('books.create');
    Route::post('/books', [AdminController::class, 'storeBook'])->name('books.store');
    Route::get('/books/{book}/edit', [AdminController::class, 'editBook'])->name('books.edit');
    Route::put('/books/{book}', [AdminController::class, 'updateBook'])->name('books.update');
    Route::delete('/books/{book}', [AdminController::class, 'destroyBook'])->name('books.destroy');

    // Members
    Route::get('/members', [AdminController::class, 'members'])->name('members');
    Route::get('/members/create', [AdminController::class, 'createMember'])->name('members.create');
    Route::post('/members', [AdminController::class, 'storeMember'])->name('members.store');
    Route::get('/members/{member}/edit', [AdminController::class, 'editMember'])->name('members.edit');
    Route::put('/members/{member}', [AdminController::class, 'updateMember'])->name('members.update');
    Route::delete('/members/{member}', [AdminController::class, 'destroyMember'])->name('members.destroy');

    // Transactions
    Route::get('/transactions', [AdminController::class, 'transactions'])->name('transactions');

    // Fines/Denda
    Route::get('/fines', [AdminController::class, 'fines'])->name('fines');
    Route::get('/fines/create', [AdminController::class, 'createFine'])->name('fines.create');
    Route::post('/fines', [AdminController::class, 'storeFine'])->name('fines.store');
    Route::get('/fines/{fine}/edit', [AdminController::class, 'editFine'])->name('fines.edit');
    Route::put('/fines/{fine}', [AdminController::class, 'updateFine'])->name('fines.update');
    Route::delete('/fines/{fine}', [AdminController::class, 'destroyFine'])->name('fines.destroy');

    // QR Codes
    Route::get('/qrcodes', [AdminController::class, 'qrCodes'])->name('qrcodes');
    Route::get('/qrcodes/create', [AdminController::class, 'createQrCode'])->name('qrcodes.create');
    Route::post('/qrcodes', [AdminController::class, 'storeQrCode'])->name('qrcodes.store');
    Route::get('/qrcodes/{qrCode}/edit', [AdminController::class, 'editQrCode'])->name('qrcodes.edit');
    Route::put('/qrcodes/{qrCode}', [AdminController::class, 'updateQrCode'])->name('qrcodes.update');
    Route::delete('/qrcodes/{qrCode}', [AdminController::class, 'destroyQrCode'])->name('qrcodes.destroy');
    Route::get('/qrcodes/{qrCode}/generate', [AdminController::class, 'generateQrCode'])->name('qrcodes.generate');

    // Borrow History
    Route::get('/borrowhistory', [AdminController::class, 'borrowHistory'])->name('borrowhistory');
    Route::get('/borrowhistory/{transaction}', [AdminController::class, 'borrowHistoryDetail'])->name('borrowhistory.detail');
});

// User routes
Route::middleware(['auth', 'siswa'])->prefix('user')->name('user.')->group(function () {
    Route::get('/dashboard', [UserController::class, 'dashboard'])->name('dashboard');
    Route::get('/statistics', [UserController::class, 'statistics'])->name('statistics');
    Route::get('/fines', [UserController::class, 'finesTracker'])->name('fines');
    Route::get('/borrow', [UserController::class, 'borrowPage'])->name('borrow');
    // Accept POST to /user/borrow with book id in body/query (fallback for forms that send id differently)
    Route::post('/borrow', [UserController::class, 'borrowBookShortcut'])->name('borrow.shortcut');
    Route::get('/return', [UserController::class, 'returnPage'])->name('return');
    Route::get('/history', [UserController::class, 'borrowHistory'])->name('history');
    Route::get('/review', [UserController::class, 'reviewPage'])->name('review');
    Route::post('/review/{book}', [UserController::class, 'storeReview'])->name('review.store');
    Route::get('/search', [UserController::class, 'searchBooks'])->name('search');
    Route::post('/borrow/{book}', [UserController::class, 'borrowBook'])->name('borrow.store');
    Route::post('/return/{transaction}', [UserController::class, 'returnBook'])->name('return.store');
    Route::get('/scan-qr', [UserController::class, 'scanQrPage'])->name('scan-qr');
    Route::post('/scan-qr/process', [UserController::class, 'processQrScan'])->name('scan-qr.process');
});

require __DIR__.'/auth.php';
