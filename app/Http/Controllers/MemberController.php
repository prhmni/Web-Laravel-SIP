<?php

namespace App\Http\Controllers;

use App\Models\Member;
use Illuminate\Http\Request;

class MemberController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search');
        $status = $request->query('status');

        $members = Member::withCount(['borrows as active_borrows_count' => function ($q) {
                $q->whereIn('status', ['dipinjam', 'terlambat']);
            }])
            ->when($search, function ($query, $search) {
                return $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('member_code', 'like', "%{$search}%")
                        ->orWhere('phone', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->when($status, function ($query, $status) {
                return $query->where('status', $status);
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('members.index', compact('members', 'search', 'status'));
    }

    public function create()
    {
        $suggestedCode = Member::generateCode();
        return view('members.create', compact('suggestedCode'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'member_code' => 'required|string|max:50|unique:members,member_code',
            'name' => 'required|string|max:255',
            'gender' => 'required|in:Laki-laki,Perempuan',
            'email' => 'nullable|email|max:255|unique:members,email',
            'phone' => 'required|string|max:20',
            'address' => 'nullable|string|max:500',
            'joined_date' => 'required|date',
            'status' => 'required|in:aktif,nonaktif',
        ], [
            'member_code.required' => 'Nomor anggota wajib diisi.',
            'member_code.unique' => 'Nomor anggota sudah terdaftar.',
            'name.required' => 'Nama lengkap wajib diisi.',
            'phone.required' => 'Nomor telepon/WhatsApp wajib diisi.',
            'joined_date.required' => 'Tanggal bergabung wajib diisi.',
        ]);

        Member::create($validated);

        return redirect()->route('members.index')->with('success', 'Data anggota berhasil ditambahkan.');
    }

    public function show(Member $member)
    {
        $member->load(['borrows' => function ($q) {
            $q->with(['details.book', 'user'])->latest();
        }]);

        return view('members.show', compact('member'));
    }

    public function card(Member $member)
    {
        return view('members.card', compact('member'));
    }

    public function edit(Member $member)
    {
        return view('members.edit', compact('member'));
    }

    public function update(Request $request, Member $member)
    {
        $validated = $request->validate([
            'member_code' => 'required|string|max:50|unique:members,member_code,' . $member->id,
            'name' => 'required|string|max:255',
            'gender' => 'required|in:Laki-laki,Perempuan',
            'email' => 'nullable|email|max:255|unique:members,email,' . $member->id,
            'phone' => 'required|string|max:20',
            'address' => 'nullable|string|max:500',
            'joined_date' => 'required|date',
            'status' => 'required|in:aktif,nonaktif',
        ]);

        $member->update($validated);

        return redirect()->route('members.index')->with('success', 'Data anggota berhasil diperbarui.');
    }

    public function destroy(Member $member)
    {
        // Cek apakah anggota sedang meminjam buku yang belum dikembalikan
        $hasActiveBorrows = $member->borrows()->whereIn('status', ['dipinjam', 'terlambat'])->exists();
        if ($hasActiveBorrows) {
            return redirect()->route('members.index')->with('error', 'Anggota masih memiliki pinjaman buku yang belum dikembalikan.');
        }

        $member->delete();

        return redirect()->route('members.index')->with('success', 'Data anggota berhasil dihapus.');
    }
}
