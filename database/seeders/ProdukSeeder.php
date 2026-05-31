<?php

namespace Database\Seeders;

use App\Models\Produk;
use Illuminate\Database\Seeder;

class ProdukSeeder extends Seeder
{
    public function run(): void
    {
        $products = [
            [
                'nama_produk' => 'Laptop ASUS ROG Strix G16',
                'harga_satuan' => 24500000.00,
                'stok' => 12,
                'deskripsi' => 'Laptop gaming monster dengan prosesor Intel Core i9 generasi terbaru, RAM 16GB DDR5, SSD 1TB NVMe, dan kartu grafis NVIDIA RTX 4060 8GB. Performa ekstrem untuk gaming kompetitif dan produktivitas kreatif tinggi.',
                'gambar' => null,
            ],
            [
                'nama_produk' => 'Keyboard Mechanical SteelSeries Apex Pro',
                'harga_satuan' => 3200000.00,
                'stok' => 25,
                'deskripsi' => 'Keyboard mechanical premium tercepat di dunia dengan OmniPoint 2.0 Adjustable HyperMagnetic switches. Dilengkapi layar OLED pintar dan RGB per-key.',
                'gambar' => null,
            ],
            [
                'nama_produk' => 'Mouse Gaming Logitech G Pro X Superlight',
                'harga_satuan' => 1950000.00,
                'stok' => 30,
                'deskripsi' => 'Mouse gaming wireless ultra-ringan dengan berat kurang dari 63 gram. Menggunakan sensor HERO 25K untuk akurasi presisi tinggi kelas pro-athlete.',
                'gambar' => null,
            ],
            [
                'nama_produk' => 'Monitor Gaming MSI Optix G241V E2',
                'harga_satuan' => 1850000.00,
                'stok' => 15,
                'deskripsi' => 'Monitor gaming IPS 23.8 inci dengan refresh rate 75Hz dan response time 1ms. Akurasi warna memukau dan sudut pandang luas untuk kenyamanan visual maksimal.',
                'gambar' => null,
            ],
            [
                'nama_produk' => 'RAM DDR5 Corsair Vengeance RGB 32GB',
                'harga_satuan' => 2100000.00,
                'stok' => 40,
                'deskripsi' => 'Memori kit DDR5 performa tinggi 32GB (2x16GB) dengan kecepatan 6000MHz. Dilengkapi pencahayaan RGB dinamis sepuluh zona yang dapat diprogram.',
                'gambar' => null,
            ],
            [
                'nama_produk' => 'SSD NVMe Samsung 990 PRO 1TB',
                'harga_satuan' => 1750000.00,
                'stok' => 50,
                'deskripsi' => 'Solid State Drive PCIe 4.0 NVMe premium tercepat dengan kecepatan baca hingga 7450 MB/s. Sangat ideal untuk loading game instan dan transfer file raksasa.',
                'gambar' => null,
            ],
        ];

        foreach ($products as $product) {
            Produk::updateOrCreate(
                ['nama_produk' => $product['nama_produk']],
                $product
            );
        }
    }
}
