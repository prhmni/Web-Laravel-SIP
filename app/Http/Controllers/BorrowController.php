<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Borrow;
use App\Models\BorrowDetail;
use App\Models\Member;
use App\Models\Setting;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BorrowController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->query('status');
        $search = $request->query('search');

        $today = Carbon::today();

        // Update status pinjaman aktif yang sudah melewati jatuh tempo menjadi 'terlambat'
        Borrow::where('status', 'dipinjam')
            ->whereDate('due_date', '<', $today)
            ->update(['status' => 'terlambat']);

        $borrows = Borrow::with(['member', 'user', 'details.book'])
            ->when($status, function ($query, $status) {
                if ($status === 'aktif') {
                    return $query->whereIn('status', ['dipinjam', 'terlambat']);
                }
                return $query->where('status', $status);
            })
            ->when($search, function ($query, $search) {
                return $query->where(function ($q) use ($search) {
                    $q->where('transaction_code', 'like', "%{$search}%")
                        ->orWhereHas('member', function ($mq) use ($search) {
                            $mq->where('name', 'like', "%{$search}%")
                                ->orWhere('member_code', 'like', "%{$search}%");
                        })
                        ->orWhereHas('details.book', function ($bq) use ($search) {
                            $bq->where('title', 'like', "%{$search}%");
                        });
                });
            })
            ->latest('borrow_date')
            ->latest('id')
            ->paginate(10)
            ->withQueryString();

        $activeCount = Borrow::whereIn('status', ['dipinjam', 'terlambat'])->count();
        $overdueCount = Borrow::where('status', 'terlambat')->count();
        $returnedCount = Borrow::where('status', 'dikembalikan')->count();

        return view('borrows.index', compact('borrows', 'status', 'search', 'activeCount', 'overdueCount', 'returnedCount'));
    }

    public function create()
    {
        $members = Member::where('status', 'aktif')->orderBy('name')->get();
        $books = Book::where('available_stock', '>', 0)->orderBy('title')->get();

        $defaultLoanDays = (int) Setting::get('loan_duration_days', 7);
        $today = Carbon::today()->format('Y-m-d');
        $suggestedDueDate = Carbon::today()->addDays($defaultLoanDays)->format('Y-m-d');
        $transactionCode = Borrow::generateCode();

        return view('borrows.create', compact('members', 'books', 'today', 'suggestedDueDate', 'transactionCode'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'member_id' => 'required|exists:members,id',
            'book_ids' => 'required|array|min:1|max:5',
            'book_ids.*' => 'required|exists:books,id',
            'borrow_date' => 'required|date',
            'due_date' => 'required|date|after_or_equal:borrow_date',
            'notes' => 'nullable|string|max:500',
        ], [
            'member_id.required' => 'Anggota peminjam wajib dipilih.',
            'book_ids.required' => 'Pilih minimal satu buku untuk dipinjam.',
            'book_ids.max' => 'Maksimal peminjaman adalah 5 buku per transaksi.',
            'due_date.after_or_equal' => 'Tanggal jatuh tempo harus sama atau setelah tanggal pinjam.',
        ]);

        $member = Member::findOrFail($request->member_id);
        if ($member->status !== 'aktif') {
            return back()->with('error', 'Anggota berstatus nonaktif tidak dapat meminjam buku.')->withInput();
        }

        // Cek stok buku yang dipilih
        $bookIds = array_unique($request->book_ids);
        $books = Book::whereIn('id', $bookIds)->get();

        foreach ($books as $book) {
            if ($book->available_stock < 1) {
                return back()->with('error', "Stok buku '{$book->title}' sedang kosong / tidak tersedia.")->withInput();
            }
        }

        DB::transaction(function () use ($request, $books) {
            $borrow = Borrow::create([
                'transaction_code' => Borrow::generateCode(),
                'member_id' => $request->member_id,
                'user_id' => Auth::id() ?? 1,
                'borrow_date' => $request->borrow_date,
                'due_date' => $request->due_date,
                'status' => 'dipinjam',
                'notes' => $request->notes,
            ]);

            foreach ($books as $book) {
                BorrowDetail::create([
                    'borrow_id' => $borrow->id,
                    'book_id' => $book->id,
                    'status' => 'dipinjam',
                ]);

                // Kurangi stok tersedia
                $book->decrement('available_stock');
            }
        });

        return redirect()->route('borrows.index')->with('success', 'Transaksi peminjaman buku berhasil dicatat.');
    }

    public function show(Borrow $borrow)
    {
        $borrow->load(['member', 'user', 'details.book.category']);

        $feePerDay = (float) Setting::get('late_fee_per_day', 1000);
        $today = Carbon::today();
        $dueDate = Carbon::parse($borrow->due_date);

        $currentLateDays = 0;
        $estimatedFine = 0;

        if ($borrow->status !== 'dikembalikan') {
            if ($today->gt($dueDate)) {
                $currentLateDays = $today->diffInDays($dueDate);
                $bookCount = $borrow->details->count();
                $estimatedFine = $currentLateDays * $feePerDay * $bookCount;
            }
        }

        return view('borrows.show', compact('borrow', 'feePerDay', 'currentLateDays', 'estimatedFine'));
    }

    public function returnForm(Borrow $borrow)
    {
        if ($borrow->status === 'dikembalikan') {
            return redirect()->route('borrows.show', $borrow)->with('info', 'Transaksi ini sudah selesai dikembalikan.');
        }

        $borrow->load(['member', 'details.book']);

        $feePerDay = (float) Setting::get('late_fee_per_day', 1000);
        $today = Carbon::today();
        $dueDate = Carbon::parse($borrow->due_date);

        $lateDays = 0;
        $totalLateFee = 0;

        if ($today->gt($dueDate)) {
            $lateDays = $today->diffInDays($dueDate);
            $totalLateFee = $lateDays * $feePerDay * $borrow->details->count();
        }

        return view('borrows.return', compact('borrow', 'feePerDay', 'lateDays', 'totalLateFee'));
    }

    public function processReturn(Request $request, Borrow $borrow)
    {
        if ($borrow->status === 'dikembalikan') {
            return redirect()->route('borrows.show', $borrow)->with('info', 'Transaksi ini sudah selesai dikembalikan.');
        }

        $request->validate([
            'return_date' => 'required|date',
            'late_fee' => 'required|numeric|min:0',
            'notes' => 'nullable|string|max:500',
        ]);

        $returnDate = Carbon::parse($request->return_date);
        $dueDate = Carbon::parse($borrow->due_date);

        $lateDays = 0;
        if ($returnDate->gt($dueDate)) {
            $lateDays = $returnDate->diffInDays($dueDate);
        }

        DB::transaction(function () use ($borrow, $request, $returnDate, $lateDays) {
            $borrow->update([
                'return_date' => $returnDate->toDateString(),
                'status' => 'dikembalikan',
                'late_days' => $lateDays,
                'late_fee' => $request->late_fee,
                'notes' => $request->notes ?? $borrow->notes,
            ]);

            // Kembalikan semua stok buku
            foreach ($borrow->details as $detail) {
                $detail->update([
                    'return_date' => $returnDate->toDateString(),
                    'status' => 'dikembalikan',
                ]);

                $detail->book->increment('available_stock');
            }
        });

        return redirect()->route('borrows.show', $borrow)
            ->with('success', 'Buku berhasil dikembalikan dan stok telah diperbarui.');
    }

    public function receipt(Borrow $borrow)
    {
        $borrow->load(['member', 'user', 'details.book']);
        return view('borrows.receipt', compact('borrow'));
    }
}
