<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\BorrowController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SettingController;
use Illuminate\Support\Facades\Route;

// Rute Autentikasi
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Rute Terproteksi (Hanya Petugas / Admin yang telah Login)
Route::middleware('auth')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('home');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Manajemen Kategori
    Route::resource('categories', CategoryController::class)->except(['create', 'show', 'edit']);

    // Manajemen Buku
    Route::resource('books', BookController::class);

    // Manajemen Anggota
    Route::resource('members', MemberController::class);
    Route::get('/members/{member}/card', [MemberController::class, 'card'])->name('members.card');

    // Sirkulasi Peminjaman & Pengembalian
    Route::get('/borrows', [BorrowController::class, 'index'])->name('borrows.index');
    Route::get('/borrows/create', [BorrowController::class, 'create'])->name('borrows.create');
    Route::post('/borrows', [BorrowController::class, 'store'])->name('borrows.store');
    Route::get('/borrows/{borrow}', [BorrowController::class, 'show'])->name('borrows.show');
    Route::get('/borrows/{borrow}/return', [BorrowController::class, 'returnForm'])->name('borrows.return');
    Route::post('/borrows/{borrow}/return', [BorrowController::class, 'processReturn'])->name('borrows.process-return');
    Route::get('/borrows/{borrow}/receipt', [BorrowController::class, 'receipt'])->name('borrows.receipt');

    // Laporan Koleksi dan Transaksi
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/transactions', [ReportController::class, 'transactions'])->name('reports.transactions');
    Route::get('/reports/transactions/print', [ReportController::class, 'printTransactions'])->name('reports.print-transactions');
    Route::get('/reports/books', [ReportController::class, 'books'])->name('reports.books');
    Route::get('/reports/books/print', [ReportController::class, 'printBooks'])->name('reports.print-books');

    // Pengaturan
    Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
    Route::put('/settings', [SettingController::class, 'update'])->name('settings.update');
});
