<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BookController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search');
        $categoryId = $request->query('category_id');

        $books = Book::with('category')
            ->when($search, function ($query, $search) {
                return $query->where(function ($q) use ($search) {
                    $q->where('title', 'like', "%{$search}%")
                        ->orWhere('book_code', 'like', "%{$search}%")
                        ->orWhere('author', 'like', "%{$search}%")
                        ->orWhere('isbn', 'like', "%{$search}%");
                });
            })
            ->when($categoryId, function ($query, $categoryId) {
                return $query->where('category_id', $categoryId);
            })
            ->latest()
            ->paginate(12)
            ->withQueryString();

        $categories = Category::orderBy('name')->get();

        return view('books.index', compact('books', 'categories', 'search', 'categoryId'));
    }

    public function create()
    {
        $categories = Category::orderBy('name')->get();

        // Rekomendasi Kode Buku Otomatis
        $lastBook = Book::latest('id')->first();
        $nextId = $lastBook ? $lastBook->id + 1 : 1;
        $suggestedCode = 'BK-' . str_pad($nextId, 4, '0', STR_PAD_LEFT);

        return view('books.create', compact('categories', 'suggestedCode'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'book_code' => 'required|string|max:50|unique:books,book_code',
            'title' => 'required|string|max:255',
            'isbn' => 'nullable|string|max:50',
            'author' => 'required|string|max:255',
            'publisher' => 'required|string|max:255',
            'publish_year' => 'required|numeric|digits:4|min:1900|max:' . (date('Y') + 1),
            'stock' => 'required|integer|min:0',
            'shelf_location' => 'nullable|string|max:100',
            'description' => 'nullable|string',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ], [
            'category_id.required' => 'Kategori buku wajib dipilih.',
            'book_code.required' => 'Kode buku wajib diisi.',
            'book_code.unique' => 'Kode buku sudah digunakan.',
            'title.required' => 'Judul buku wajib diisi.',
            'author.required' => 'Penulis buku wajib diisi.',
            'publisher.required' => 'Penerbit buku wajib diisi.',
            'publish_year.required' => 'Tahun terbit wajib diisi.',
            'stock.required' => 'Jumlah stok wajib diisi.',
        ]);

        if ($request->hasFile('cover_image')) {
            $path = $request->file('cover_image')->store('covers', 'public');
            $validated['cover_image'] = $path;
        }

        $validated['available_stock'] = $validated['stock'];

        Book::create($validated);

        return redirect()->route('books.index')->with('success', 'Buku baru berhasil ditambahkan.');
    }

    public function show(Book $book)
    {
        $book->load(['category', 'borrowDetails.borrow.member']);
        return view('books.show', compact('book'));
    }

    public function edit(Book $book)
    {
        $categories = Category::orderBy('name')->get();
        return view('books.edit', compact('book', 'categories'));
    }

    public function update(Request $request, Book $book)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'book_code' => 'required|string|max:50|unique:books,book_code,' . $book->id,
            'title' => 'required|string|max:255',
            'isbn' => 'nullable|string|max:50',
            'author' => 'required|string|max:255',
            'publisher' => 'required|string|max:255',
            'publish_year' => 'required|numeric|digits:4|min:1900|max:' . (date('Y') + 1),
            'stock' => 'required|integer|min:0',
            'shelf_location' => 'nullable|string|max:100',
            'description' => 'nullable|string',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        if ($request->hasFile('cover_image')) {
            if ($book->cover_image && Storage::disk('public')->exists($book->cover_image)) {
                Storage::disk('public')->delete($book->cover_image);
            }
            $path = $request->file('cover_image')->store('covers', 'public');
            $validated['cover_image'] = $path;
        }

        // Hitung selisih stok yang sedang dipinjam
        $borrowedCount = $book->stock - $book->available_stock;
        if ($validated['stock'] < $borrowedCount) {
            return back()->withErrors([
                'stock' => "Stok tidak boleh kurang dari jumlah buku yang sedang dipinjam ({$borrowedCount} buku)."
            ])->withInput();
        }

        $validated['available_stock'] = $validated['stock'] - $borrowedCount;

        $book->update($validated);

        return redirect()->route('books.index')->with('success', 'Data buku berhasil diperbarui.');
    }

    public function destroy(Book $book)
    {
        // Cek jika ada buku yang masih dipinjam
        $hasActiveBorrow = $book->borrowDetails()->where('status', 'dipinjam')->exists();
        if ($hasActiveBorrow) {
            return redirect()->route('books.index')->with('error', 'Buku ini sedang dipinjam oleh anggota dan tidak dapat dihapus.');
        }

        if ($book->cover_image && Storage::disk('public')->exists($book->cover_image)) {
            Storage::disk('public')->delete($book->cover_image);
        }

        $book->delete();

        return redirect()->route('books.index')->with('success', 'Buku berhasil dihapus dari sistem.');
    }
}
