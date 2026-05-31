<?php

namespace Database\Seeders;

use App\Models\ActivityLog;
use App\Models\BugReport;
use App\Models\SystemVersion;
use Illuminate\Database\Seeder;

class DeveloperDataSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Seed System Versions
        $versions = [
            [
                'versi' => 'v1.0.0',
                'deskripsi' => "Rilis Awal Sistem (MVP):\n- Setup Environment Laravel 13 & Bootstrap 5\n- Migrasi Database untuk tabel Users & Produk\n- Otentikasi Multi-Role & Middleware Keamanan\n- Kerangka Halaman Dashboard Dasar",
                'tanggal_rilis' => '2026-05-24',
            ],
            [
                'versi' => 'v1.1.0',
                'deskripsi' => "Pembaruan Belanja & Transaksi:\n- Integrasi Keranjang Belanja berbasis Laravel Session\n- Formulir Pemesanan & Alamat Pengiriman\n- Pengamanan Transaksi Konkuren menggunakan Row Locking (lockForUpdate)\n- Relasi Database Transaksi & Detail Transaksi",
                'tanggal_rilis' => '2026-05-28',
            ],
            [
                'versi' => 'v1.2.0',
                'deskripsi' => "Fitur Administrasi & Laporan Keuangan:\n- Pemrosesan status pesanan pelanggan (pending -> diproses -> selesai)\n- Modul Laporan Penjualan & Statistik Pendapatan Toko\n- Integrasi DataTables untuk rekap data ringkas\n- Perbaikan visual antarmuka agar responsif seluler",
                'tanggal_rilis' => '2026-05-30',
            ],
            [
                'versi' => 'v1.3.0',
                'deskripsi' => "Rilis Peningkatan Keamanan & Fitur Registrasi (Saat ini):\n- Menu registrasi akun mandiri khusus pelanggan (role user)\n- Sistem Audit Trail (Log Aktivitas) menyeluruh untuk tindakan krusial\n- Penghapusan link menu Profil & Kelola User yang belum digunakan\n- Pengujian otomatis (Feature Testing) menyeluruh pada 10 test case",
                'tanggal_rilis' => '2026-05-31',
            ],
        ];

        foreach ($versions as $v) {
            SystemVersion::updateOrCreate(
                ['versi' => $v['versi']],
                $v
            );
        }

        // 2. Seed Bug Reports
        $bugs = [
            [
                'judul' => 'Kesalahan upload file gambar berukuran 3.2MB di CRUD Produk',
                'deskripsi' => 'Admin mencoba mengunggah gambar katalog produk dengan ukuran file 3.2MB tetapi memicu error Exception bukan validasi yang rapi. Diselesaikan dengan memperketat limit upload maksimal 2MB di controller.',
                'status' => 'resolved',
                'tanggal_dilaporkan' => '2026-05-25 14:20:00',
            ],
            [
                'judul' => 'Stok barang tidak terpotong jika terjadi kegagalan di tengah checkout',
                'deskripsi' => 'Ditemukan kemungkinan integritas stok pecah jika koneksi terputus saat transaksi database. Diselesaikan dengan membungkus checkout dalam DB::transaction dengan Row Lock lockForUpdate().',
                'status' => 'resolved',
                'tanggal_dilaporkan' => '2026-05-29 09:15:00',
            ],
            [
                'judul' => 'Tampilan tombol navigasi agak terpotong pada peramban web Safari iOS',
                'deskripsi' => 'Pengguna perangkat iPhone melaporkan bahwa tombol logout pada navbar seluler terpotong 5px di bagian bawah karena perbedaan rendering flexbox Safari.',
                'status' => 'open',
                'tanggal_dilaporkan' => '2026-05-31 08:05:00',
            ],
        ];

        foreach ($bugs as $b) {
            BugReport::updateOrCreate(
                ['judul' => $b['judul']],
                $b
            );
        }

        // 3. Seed Activity Logs
        $logs = [
            [
                'id_user' => 1, // Admin
                'aktivitas' => 'Melakukan login ke dalam sistem.',
                'tanggal_log' => '2026-05-31 08:01:00',
            ],
            [
                'id_user' => 1,
                'aktivitas' => 'Menambahkan produk baru: Laptop ASUS ROG Strix G16',
                'tanggal_log' => '2026-05-31 08:02:10',
            ],
            [
                'id_user' => 3, // User
                'aktivitas' => 'Melakukan registrasi akun baru.',
                'tanggal_log' => '2026-05-31 08:03:00',
            ],
            [
                'id_user' => 3,
                'aktivitas' => 'Melakukan checkout pesanan #1 dengan total Rp 24.500.000',
                'tanggal_log' => '2026-05-31 08:04:15',
            ],
            [
                'id_user' => 1,
                'aktivitas' => 'Mengubah status pesanan #1 menjadi: selesai',
                'tanggal_log' => '2026-05-31 08:05:30',
            ],
            [
                'id_user' => 2, // Developer
                'aktivitas' => 'Melakukan login ke dalam sistem.',
                'tanggal_log' => '2026-05-31 08:06:00',
            ],
            [
                'id_user' => 2,
                'aktivitas' => 'Membuka dashboard pemantauan sistem pengembang.',
                'tanggal_log' => '2026-05-31 08:06:45',
            ],
        ];

        foreach ($logs as $l) {
            ActivityLog::create($l);
        }
    }
}
