<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index()
    {
        $settings = [
            'library_name' => Setting::get('library_name', 'Perpustakaan Digital Mandiri'),
            'library_address' => Setting::get('library_address', 'Jl. Pendidikan No. 45, Jakarta'),
            'library_phone' => Setting::get('library_phone', '(021) 555-0199'),
            'library_email' => Setting::get('library_email', 'kontak@perpustakaan.sch.id'),
            'loan_duration_days' => Setting::get('loan_duration_days', 7),
            'late_fee_per_day' => Setting::get('late_fee_per_day', 1000),
            'max_books_per_loan' => Setting::get('max_books_per_loan', 3),
        ];

        return view('settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'library_name' => 'required|string|max:255',
            'library_address' => 'required|string|max:500',
            'library_phone' => 'nullable|string|max:50',
            'library_email' => 'nullable|email|max:255',
            'loan_duration_days' => 'required|integer|min:1|max:60',
            'late_fee_per_day' => 'required|numeric|min:0',
            'max_books_per_loan' => 'required|integer|min:1|max:10',
        ]);

        foreach ($validated as $key => $value) {
            Setting::set($key, $value);
        }

        return redirect()->route('settings.index')->with('success', 'Pengaturan sistem perpustakaan berhasil disimpan.');
    }
}
