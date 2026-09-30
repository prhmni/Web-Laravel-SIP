<?php

namespace App\Providers;

use App\Models\Book;
use App\Models\Borrow;
use App\Models\Category;
use App\Models\Member;
use App\Models\Setting;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Bagikan data statistik dinamis ke layouts.app untuk badge sidebar & notifikasi navbar
        View::composer('layouts.app', function ($view) {
            $sidebarData = [
                'totalBooks' => 0,
                'totalCategories' => 0,
                'totalMembers' => 0,
                'activeBorrows' => 0,
                'overdueBorrows' => 0,
                'overdueList' => collect(),
                'libraryName' => 'SIP Perpustakaan',
                'lateFeePerDay' => 1000,
                'loanDurationDays' => 7,
            ];

            try {
                if (Schema::hasTable('books')) {
                    $sidebarData['totalBooks'] = Book::count();
                }
                if (Schema::hasTable('categories')) {
                    $sidebarData['totalCategories'] = Category::count();
                }
                if (Schema::hasTable('members')) {
                    $sidebarData['totalMembers'] = Member::where('status', 'aktif')->count();
                }
                if (Schema::hasTable('borrows')) {
                    $sidebarData['activeBorrows'] = Borrow::whereIn('status', ['dipinjam', 'terlambat'])->count();
                    $sidebarData['overdueBorrows'] = Borrow::where('status', 'terlambat')->count();
                    $sidebarData['overdueList'] = Borrow::with(['member', 'details.book'])
                        ->where('status', 'terlambat')
                        ->orderBy('due_date', 'asc')
                        ->take(5)
                        ->get();
                }
                if (Schema::hasTable('settings')) {
                    $sidebarData['libraryName'] = Setting::get('library_name', 'SIP Perpustakaan');
                    $sidebarData['lateFeePerDay'] = (int) Setting::get('late_fee_per_day', 1000);
                    $sidebarData['loanDurationDays'] = (int) Setting::get('loan_duration_days', 7);
                }
            } catch (\Throwable $e) {
                // Graceful fallback jika belum migrasi atau terjadi galat DB
            }

            $view->with('sidebarData', $sidebarData);
        });
    }
}
