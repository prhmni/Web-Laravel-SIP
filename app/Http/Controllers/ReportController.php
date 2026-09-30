<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Borrow;
use App\Models\Category;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index()
    {
        return redirect()->route('reports.transactions');
    }

    /**
     * Laporan Transaksi Peminjaman & Pengembalian
     */
    public function transactions(Request $request)
    {
        $startDate = $request->query('start_date', Carbon::today()->startOfMonth()->toDateString());
        $endDate = $request->query('end_date', Carbon::today()->toDateString());
        $status = $request->query('status');

        $query = Borrow::with(['member', 'user', 'details.book'])
            ->whereDate('borrow_date', '>=', $startDate)
            ->whereDate('borrow_date', '<=', $endDate)
            ->when($status, function ($q, $status) {
                if ($status === 'aktif') {
                    return $q->whereIn('status', ['dipinjam', 'terlambat']);
                }
                return $q->where('status', $status);
            })
            ->latest('borrow_date');

        $borrows = $query->paginate(15)->withQueryString();

        // Statistik ringkasan filter ini
        $statsQuery = Borrow::whereDate('borrow_date', '>=', $startDate)
            ->whereDate('borrow_date', '<=', $endDate)
            ->when($status, function ($q, $status) {
                if ($status === 'aktif') {
                    return $q->whereIn('status', ['dipinjam', 'terlambat']);
                }
                return $q->where('status', $status);
            });

        $totalTransactions = (clone $statsQuery)->count();
        $totalReturned = (clone $statsQuery)->where('status', 'dikembalikan')->count();
        $totalActive = (clone $statsQuery)->whereIn('status', ['dipinjam', 'terlambat'])->count();
        $totalFines = (clone $statsQuery)->sum('late_fee');

        return view('reports.transactions', compact(
            'borrows',
            'startDate',
            'endDate',
            'status',
            'totalTransactions',
            'totalReturned',
            'totalActive',
            'totalFines'
        ));
    }

    /**
     * Cetak Laporan Transaksi (Printable View)
     */
    public function printTransactions(Request $request)
    {
        $startDate = $request->query('start_date', Carbon::today()->startOfMonth()->toDateString());
        $endDate = $request->query('end_date', Carbon::today()->toDateString());
        $status = $request->query('status');

        $borrows = Borrow::with(['member', 'user', 'details.book'])
            ->whereDate('borrow_date', '>=', $startDate)
            ->whereDate('borrow_date', '<=', $endDate)
            ->when($status, function ($q, $status) {
                if ($status === 'aktif') {
                    return $q->whereIn('status', ['dipinjam', 'terlambat']);
                }
                return $q->where('status', $status);
            })
            ->latest('borrow_date')
            ->get();

        $totalFines = $borrows->sum('late_fee');

        return view('reports.print-transactions', compact('borrows', 'startDate', 'endDate', 'status', 'totalFines'));
    }

    /**
     * Laporan Koleksi Buku
     */
    public function books(Request $request)
    {
        $categoryId = $request->query('category_id');
        $stockFilter = $request->query('stock'); // all, available, empty

        $books = Book::with('category')
            ->when($categoryId, function ($q, $categoryId) {
                return $q->where('category_id', $categoryId);
            })
            ->when($stockFilter === 'available', function ($q) {
                return $q->where('available_stock', '>', 0);
            })
            ->when($stockFilter === 'empty', function ($q) {
                return $q->where('available_stock', 0);
            })
            ->orderBy('title')
            ->paginate(15)
            ->withQueryString();

        $categories = Category::orderBy('name')->get();

        $totalTitles = Book::count();
        $totalTotalStock = Book::sum('stock');
        $totalAvailable = Book::sum('available_stock');
        $totalBorrowed = $totalTotalStock - $totalAvailable;

        return view('reports.books', compact(
            'books',
            'categories',
            'categoryId',
            'stockFilter',
            'totalTitles',
            'totalTotalStock',
            'totalAvailable',
            'totalBorrowed'
        ));
    }

    /**
     * Cetak Laporan Koleksi Buku (Printable View)
     */
    public function printBooks(Request $request)
    {
        $categoryId = $request->query('category_id');
        $stockFilter = $request->query('stock');

        $books = Book::with('category')
            ->when($categoryId, function ($q, $categoryId) {
                return $q->where('category_id', $categoryId);
            })
            ->when($stockFilter === 'available', function ($q) {
                return $q->where('available_stock', '>', 0);
            })
            ->when($stockFilter === 'empty', function ($q) {
                return $q->where('available_stock', 0);
            })
            ->orderBy('title')
            ->get();

        $categoryName = $categoryId ? Category::find($categoryId)?->name : 'Semua Kategori';

        return view('reports.print-books', compact('books', 'categoryName', 'stockFilter'));
    }
}
