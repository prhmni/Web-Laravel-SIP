<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Borrow;
use App\Models\Member;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $today = Carbon::today();

        // Otomatis tandai transaksi yang lewat jatuh tempo menjadi 'terlambat' jika status masih 'dipinjam'
        Borrow::where('status', 'dipinjam')
            ->whereDate('due_date', '<', $today)
            ->update(['status' => 'terlambat']);

        $totalBooks = Book::sum('stock');
        $totalTitles = Book::count();
        $totalMembers = Member::where('status', 'aktif')->count();
        $activeBorrows = Borrow::whereIn('status', ['dipinjam', 'terlambat'])->count();
        $overdueBorrows = Borrow::where('status', 'terlambat')->count();
        $totalFines = Borrow::sum('late_fee');

        $recentBorrows = Borrow::with(['member', 'user', 'details.book'])
            ->latest()
            ->take(6)
            ->get();

        $popularBooks = Book::with('category')
            ->withCount('borrowDetails')
            ->orderByDesc('borrow_details_count')
            ->take(5)
            ->get();

        // Data grafik 6 bulan terakhir
        $chartMonths = [];
        $chartBorrows = [];
        $chartReturns = [];

        for ($i = 5; $i >= 0; $i--) {
            $monthDate = Carbon::today()->subMonths($i);
            $monthLabel = $monthDate->translatedFormat('M Y');
            $chartMonths[] = $monthLabel;

            $borrowCount = Borrow::whereYear('borrow_date', $monthDate->year)
                ->whereMonth('borrow_date', $monthDate->month)
                ->count();
            $chartBorrows[] = $borrowCount;

            $returnCount = Borrow::whereNotNull('return_date')
                ->whereYear('return_date', $monthDate->year)
                ->whereMonth('return_date', $monthDate->month)
                ->count();
            $chartReturns[] = $returnCount;
        }

        return view('dashboard.index', compact(
            'totalBooks',
            'totalTitles',
            'totalMembers',
            'activeBorrows',
            'overdueBorrows',
            'totalFines',
            'recentBorrows',
            'popularBooks',
            'chartMonths',
            'chartBorrows',
            'chartReturns'
        ));
    }
}
