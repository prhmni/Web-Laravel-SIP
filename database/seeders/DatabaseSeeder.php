<?php

namespace Database\Seeders;

use App\Models\Book;
use App\Models\Borrow;
use App\Models\BorrowDetail;
use App\Models\Category;
use App\Models\Member;
use App\Models\Setting;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Pengaturan Default
        $settings = [
            'library_name' => 'Perpustakaan Digital Mandiri',
            'library_address' => 'Jl. Pemuda No. 128, Rawamangun, Jakarta Timur',
            'library_phone' => '(021) 4786-9021 / 0812-3456-7890',
            'library_email' => 'layanan@sip-perpustakaan.sch.id',
            'loan_duration_days' => 7,
            'late_fee_per_day' => 1000,
            'max_books_per_loan' => 3,
        ];

        foreach ($settings as $key => $val) {
            Setting::updateOrCreate(['key' => $key], ['value' => $val]);
        }

        // 2. Akun Pengguna (Admin & Petugas)
        $admin = User::updateOrCreate(
            ['email' => 'admin@sip.test'],
            [
                'name' => 'Administrator Perpustakaan',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'phone' => '081234567890',
            ]
        );

        $petugas = User::updateOrCreate(
            ['email' => 'petugas@sip.test'],
            [
                'name' => 'Siti Rahmawati, S.I.Pust',
                'password' => Hash::make('password'),
                'role' => 'petugas',
                'phone' => '089876543210',
            ]
        );

        // 3. Kategori Buku
        $categories = [
            [
                'name' => 'Teknologi Informasi & Komputer',
                'slug' => 'teknologi-informasi-komputer',
                'description' => 'Buku-buku seputar pemrograman web, rekayasa perangkat lunak, jaringan, dan kecerdasan buatan.'
            ],
            [
                'name' => 'Sastra & Fiksi',
                'slug' => 'sastra-fiksi',
                'description' => 'Novel populer, kumpulan cerpen, puisi, dan sastra klasik Indonesia maupun dunia.'
            ],
            [
                'name' => 'Pengembangan Diri & Psikologi',
                'slug' => 'pengembangan-diri-psikologi',
                'description' => 'Buku motivasi, pola pikir produktif, kepemimpinan, dan kesehatan mental.'
            ],
            [
                'name' => 'Sains & Matematika',
                'slug' => 'sains-matematika',
                'description' => 'Buku referensi sains murni, biologi, fisika, kimia, dan matematika terapan.'
            ],
            [
                'name' => 'Sejarah & Sosial Budaya',
                'slug' => 'sejarah-sosial-budaya',
                'description' => 'Rekam jejak sejarah peradaban nusantara, antropologi, dan dinamika sosial kemasyarakatan.'
            ],
            [
                'name' => 'Bisnis & Ekonomi',
                'slug' => 'bisnis-ekonomi',
                'description' => 'Panduan wirausaha, investasi, manajemen keuangan, dan pemasaran digital.'
            ],
        ];

        $categoryMap = [];
        foreach ($categories as $cat) {
            $createdCat = Category::updateOrCreate(['slug' => $cat['slug']], $cat);
            $categoryMap[$cat['slug']] = $createdCat->id;
        }

        // 4. Data Koleksi Buku
        $booksData = [
            [
                'category_id' => $categoryMap['teknologi-informasi-komputer'],
                'book_code' => 'BK-0001',
                'title' => 'Belajar Pemrograman Web Modern dengan Laravel & MySQL',
                'isbn' => '978-623-111-001-2',
                'author' => 'Budi Raharjo, M.Kom',
                'publisher' => 'Informatika Bandung',
                'publish_year' => 2024,
                'stock' => 5,
                'available_stock' => 4, // 1 dipinjam
                'shelf_location' => 'Rak TI-01',
                'description' => 'Panduan komprehensif membangun aplikasi web skala menengah hingga enterprise dengan framework Laravel.'
            ],
            [
                'category_id' => $categoryMap['teknologi-informasi-komputer'],
                'book_code' => 'BK-0002',
                'title' => 'Clean Code: A Handbook of Agile Software Craftsmanship',
                'isbn' => '978-013-235-088-4',
                'author' => 'Robert C. Martin (Uncle Bob)',
                'publisher' => 'Prentice Hall',
                'publish_year' => 2018,
                'stock' => 4,
                'available_stock' => 3, // 1 dipinjam
                'shelf_location' => 'Rak TI-02',
                'description' => 'Standar baku dan prinsip rekayasa perangkat lunak agar kode bersih, modular, dan mudah dipelihara.'
            ],
            [
                'category_id' => $categoryMap['sastra-fiksi'],
                'book_code' => 'BK-0003',
                'title' => 'Laskar Pelangi',
                'isbn' => '978-979-306-279-2',
                'author' => 'Andrea Hirata',
                'publisher' => 'Bentang Pustaka',
                'publish_year' => 2005,
                'stock' => 8,
                'available_stock' => 7, // 1 dipinjam
                'shelf_location' => 'Rak SF-01',
                'description' => 'Kisah inspiratif tentang perjuangan 10 anak di Belitong dalam menuntut ilmu di tengah keterbatasan.'
            ],
            [
                'category_id' => $categoryMap['sastra-fiksi'],
                'book_code' => 'BK-0004',
                'title' => 'Bumi Manusia',
                'isbn' => '978-979-973-123-5',
                'author' => 'Pramoedya Ananta Toer',
                'publisher' => 'Hasta Mitra',
                'publish_year' => 2015,
                'stock' => 6,
                'available_stock' => 6,
                'shelf_location' => 'Rak SF-02',
                'description' => 'Karya sastra agung yang memotret pergulatan identitas dan nasionalisme di masa kolonial Hindia Belanda.'
            ],
            [
                'category_id' => $categoryMap['sastra-fiksi'],
                'book_code' => 'BK-0005',
                'title' => 'Laut Bercerita',
                'isbn' => '978-602-424-694-5',
                'author' => 'Leila S. Chudori',
                'publisher' => 'Kepustakaan Populer Gramedia (KPG)',
                'publish_year' => 2017,
                'stock' => 7,
                'available_stock' => 7,
                'shelf_location' => 'Rak SF-03',
                'description' => 'Novel bertema sejarah penghilangan paksa aktivis mahasiswa pada era reformasi 1998.'
            ],
            [
                'category_id' => $categoryMap['sastra-fiksi'],
                'book_code' => 'BK-0006',
                'title' => 'Hujan',
                'isbn' => '978-602-032-478-4',
                'author' => 'Tere Liye',
                'publisher' => 'Gramedia Pustaka Utama',
                'publish_year' => 2016,
                'stock' => 5,
                'available_stock' => 5,
                'shelf_location' => 'Rak SF-04',
                'description' => 'Cerita fiksi ilmiah tentang masa depan bumi, persahabatan, cinta, dan melupakan kenangan pahit.'
            ],
            [
                'category_id' => $categoryMap['pengembangan-diri-psikologi'],
                'book_code' => 'BK-0007',
                'title' => 'Filosofi Teras: Panduan Stoikisme Hidup Tenang',
                'isbn' => '978-602-412-518-9',
                'author' => 'Henry Manampiring',
                'publisher' => 'Penerbit Buku Kompas',
                'publish_year' => 2019,
                'stock' => 6,
                'available_stock' => 6,
                'shelf_location' => 'Rak PD-01',
                'description' => 'Penerapan filsafat Yunani kuno Stoisisme untuk mengatasi kecemasan dan emosi negatif di era modern.'
            ],
            [
                'category_id' => $categoryMap['pengembangan-diri-psikologi'],
                'book_code' => 'BK-0008',
                'title' => 'Atomic Habits: Perubahan Kecil yang Memberikan Hasil Luar Biasa',
                'isbn' => '978-602-063-317-6',
                'author' => 'James Clear',
                'publisher' => 'Gramedia Pustaka Utama',
                'publish_year' => 2019,
                'stock' => 8,
                'available_stock' => 8,
                'shelf_location' => 'Rak PD-02',
                'description' => 'Kerangka kerja teruji untuk membentuk kebiasaan baik dan mengikis kebiasaan buruk secara konsisten.'
            ],
            [
                'category_id' => $categoryMap['sejarah-sosial-budaya'],
                'book_code' => 'BK-0009',
                'title' => 'Sapiens: Riwayat Singkat Umat Manusia',
                'isbn' => '978-602-424-416-3',
                'author' => 'Yuval Noah Harari',
                'publisher' => 'Kepustakaan Populer Gramedia',
                'publish_year' => 2017,
                'stock' => 4,
                'available_stock' => 4,
                'shelf_location' => 'Rak SJ-01',
                'description' => 'Eksplorasi mendalam evolusi Homo sapiens dari kera tak signifikan menjadi penguasa planet bumi.'
            ],
            [
                'category_id' => $categoryMap['bisnis-ekonomi'],
                'book_code' => 'BK-0010',
                'title' => 'The Psychology of Money: Pelajaran Abadi Mengenai Kekayaan dan Kebahagiaan',
                'isbn' => '978-602-064-904-7',
                'author' => 'Morgan Housel',
                'publisher' => 'Baca',
                'publish_year' => 2021,
                'stock' => 5,
                'available_stock' => 5,
                'shelf_location' => 'Rak BE-01',
                'description' => 'Memahami bahwa kesuksesan finansial bukan hanya soal angka, melainkan perilaku dan psikologi manusia.'
            ],
            [
                'category_id' => $categoryMap['sains-matematika'],
                'book_code' => 'BK-0011',
                'title' => 'Kosmos: Petualangan Menjelajahi Alam Semesta',
                'isbn' => '978-602-424-227-5',
                'author' => 'Carl Sagan',
                'publisher' => 'Kepustakaan Populer Gramedia',
                'publish_year' => 2016,
                'stock' => 3,
                'available_stock' => 3,
                'shelf_location' => 'Rak SM-01',
                'description' => 'Karya sains legendaris yang membedah keagungan galaksi, fisika bintang, dan tempat manusia di kosmos.'
            ],
        ];

        $bookEntities = [];
        foreach ($booksData as $b) {
            $bookEntities[$b['book_code']] = Book::updateOrCreate(['book_code' => $b['book_code']], $b);
        }

        // 5. Data Anggota Perpustakaan
        $membersData = [
            [
                'member_code' => 'AGT-2026-0001',
                'name' => 'Muhammad Fadhil Pratama',
                'gender' => 'Laki-laki',
                'email' => 'fadhil.pratama@gmail.com',
                'phone' => '0812-9876-5432',
                'address' => 'Jl. Kebon Jeruk No. 14, Jakarta Barat',
                'joined_date' => Carbon::now()->subMonths(6)->toDateString(),
                'status' => 'aktif',
            ],
            [
                'member_code' => 'AGT-2026-0002',
                'name' => 'Anisa Kusuma Wardani',
                'gender' => 'Perempuan',
                'email' => 'anisa.wardani@yahoo.com',
                'phone' => '0857-1234-5678',
                'address' => 'Jl. Tebet Barat Dalam VII No. 20, Jakarta Selatan',
                'joined_date' => Carbon::now()->subMonths(4)->toDateString(),
                'status' => 'aktif',
            ],
            [
                'member_code' => 'AGT-2026-0003',
                'name' => 'Rian Hidayat Saputra',
                'gender' => 'Laki-laki',
                'email' => 'rian.hidayat@gmail.com',
                'phone' => '0878-2233-4455',
                'address' => 'Jl. Cempaka Putih Tengah No. 5, Jakarta Pusat',
                'joined_date' => Carbon::now()->subMonths(2)->toDateString(),
                'status' => 'aktif',
            ],
            [
                'member_code' => 'AGT-2026-0004',
                'name' => 'Dewi Lestari Anggraini',
                'gender' => 'Perempuan',
                'email' => 'dewi.lestari@gmail.com',
                'phone' => '0813-8899-0011',
                'address' => 'Jl. Matraman Raya No. 88, Jakarta Timur',
                'joined_date' => Carbon::now()->subMonth()->toDateString(),
                'status' => 'aktif',
            ],
            [
                'member_code' => 'AGT-2026-0005',
                'name' => 'Dimas Surya Wicaksana',
                'gender' => 'Laki-laki',
                'email' => 'dimas.surya@outlook.com',
                'phone' => '0821-4455-6677',
                'address' => 'Jl. Boulevard Kelapa Gading Blok M-2, Jakarta Utara',
                'joined_date' => Carbon::now()->subMonths(5)->toDateString(),
                'status' => 'aktif',
            ],
        ];

        $memberEntities = [];
        foreach ($membersData as $m) {
            $memberEntities[$m['member_code']] = Member::updateOrCreate(['member_code' => $m['member_code']], $m);
        }

        // 6. Data Transaksi Peminjaman & Pengembalian Contoh

        // Transaksi 1: Sedang Aktif Dipinjam (Jatuh tempo 3 hari lagi)
        $tx1 = Borrow::updateOrCreate(
            ['transaction_code' => 'PJ-202609-0001'],
            [
                'member_id' => $memberEntities['AGT-2026-0001']->id,
                'user_id' => $petugas->id,
                'borrow_date' => Carbon::today()->subDays(4)->toDateString(),
                'due_date' => Carbon::today()->addDays(3)->toDateString(),
                'return_date' => null,
                'status' => 'dipinjam',
                'late_days' => 0,
                'late_fee' => 0,
                'notes' => 'Peminjaman untuk keperluan tugas akhir.',
            ]
        );
        BorrowDetail::firstOrCreate([
            'borrow_id' => $tx1->id,
            'book_id' => $bookEntities['BK-0001']->id,
        ], [
            'status' => 'dipinjam',
            'return_date' => null,
        ]);

        // Transaksi 2: Terlambat (Jatuh tempo kemarin / 4 hari lalu)
        $tx2 = Borrow::updateOrCreate(
            ['transaction_code' => 'PJ-202609-0002'],
            [
                'member_id' => $memberEntities['AGT-2026-0002']->id,
                'user_id' => $petugas->id,
                'borrow_date' => Carbon::today()->subDays(11)->toDateString(),
                'due_date' => Carbon::today()->subDays(4)->toDateString(),
                'return_date' => null,
                'status' => 'terlambat',
                'late_days' => 4,
                'late_fee' => 4000,
                'notes' => 'Peminjam telah dihubungi via WhatsApp reminder.',
            ]
        );
        BorrowDetail::firstOrCreate([
            'borrow_id' => $tx2->id,
            'book_id' => $bookEntities['BK-0002']->id,
        ], [
            'status' => 'dipinjam',
            'return_date' => null,
        ]);
        BorrowDetail::firstOrCreate([
            'borrow_id' => $tx2->id,
            'book_id' => $bookEntities['BK-0003']->id,
        ], [
            'status' => 'dipinjam',
            'return_date' => null,
        ]);

        // Transaksi 3: Selesai Dikembalikan Tepat Waktu
        $tx3 = Borrow::updateOrCreate(
            ['transaction_code' => 'PJ-202609-0003'],
            [
                'member_id' => $memberEntities['AGT-2026-0003']->id,
                'user_id' => $admin->id,
                'borrow_date' => Carbon::today()->subDays(20)->toDateString(),
                'due_date' => Carbon::today()->subDays(13)->toDateString(),
                'return_date' => Carbon::today()->subDays(14)->toDateString(),
                'status' => 'dikembalikan',
                'late_days' => 0,
                'late_fee' => 0,
                'notes' => 'Buku dikembalikan dalam kondisi sangat baik.',
            ]
        );
        BorrowDetail::firstOrCreate([
            'borrow_id' => $tx3->id,
            'book_id' => $bookEntities['BK-0004']->id,
        ], [
            'status' => 'dikembalikan',
            'return_date' => Carbon::today()->subDays(14)->toDateString(),
        ]);

        // Transaksi 4: Selesai Dikembalikan dengan Denda Keterlambatan
        $tx4 = Borrow::updateOrCreate(
            ['transaction_code' => 'PJ-202609-0004'],
            [
                'member_id' => $memberEntities['AGT-2026-0004']->id,
                'user_id' => $petugas->id,
                'borrow_date' => Carbon::today()->subDays(25)->toDateString(),
                'due_date' => Carbon::today()->subDays(18)->toDateString(),
                'return_date' => Carbon::today()->subDays(15)->toDateString(),
                'status' => 'dikembalikan',
                'late_days' => 3,
                'late_fee' => 3000,
                'notes' => 'Terlambat 3 hari, denda Rp 3.000 telah dibayar lunas.',
            ]
        );
        BorrowDetail::firstOrCreate([
            'borrow_id' => $tx4->id,
            'book_id' => $bookEntities['BK-0007']->id,
        ], [
            'status' => 'dikembalikan',
            'return_date' => Carbon::today()->subDays(15)->toDateString(),
        ]);
    }
}
