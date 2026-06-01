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
                'gambar' => $this->generateImage('Laptop ASUS ROG Strix G16', 'rog_strix_g16.png'),
            ],
            [
                'nama_produk' => 'Keyboard Mechanical SteelSeries Apex Pro',
                'harga_satuan' => 3200000.00,
                'stok' => 25,
                'deskripsi' => 'Keyboard mechanical premium tercepat di dunia dengan OmniPoint 2.0 Adjustable HyperMagnetic switches. Dilengkapi layar OLED pintar dan RGB per-key.',
                'gambar' => $this->generateImage('Keyboard Mechanical SteelSeries Apex Pro', 'apex_pro.png'),
            ],
            [
                'nama_produk' => 'Mouse Gaming Logitech G Pro X Superlight',
                'harga_satuan' => 1950000.00,
                'stok' => 30,
                'deskripsi' => 'Mouse gaming wireless ultra-ringan dengan berat kurang dari 63 gram. Menggunakan sensor HERO 25K untuk akurasi presisi tinggi kelas pro-athlete.',
                'gambar' => $this->generateImage('Mouse Gaming Logitech G Pro X Superlight', 'g_pro_superlight.png'),
            ],
            [
                'nama_produk' => 'Monitor Gaming MSI Optix G241V E2',
                'harga_satuan' => 1850000.00,
                'stok' => 15,
                'deskripsi' => 'Monitor gaming IPS 23.8 inci dengan refresh rate 75Hz dan response time 1ms. Akurasi warna memukau dan sudut pandang luas untuk kenyamanan visual maksimal.',
                'gambar' => $this->generateImage('Monitor Gaming MSI Optix G241V E2', 'optix_g241v.png'),
            ],
            [
                'nama_produk' => 'RAM DDR5 Corsair Vengeance RGB 32GB',
                'harga_satuan' => 2100000.00,
                'stok' => 40,
                'deskripsi' => 'Memori kit DDR5 performa tinggi 32GB (2x16GB) dengan kecepatan 6000MHz. Dilengkapi pencahayaan RGB dinamis sepuluh zona yang dapat diprogram.',
                'gambar' => $this->generateImage('RAM DDR5 Corsair Vengeance RGB 32GB', 'corsair_ddr5.png'),
            ],
            [
                'nama_produk' => 'SSD NVMe Samsung 990 PRO 1TB',
                'harga_satuan' => 1750000.00,
                'stok' => 50,
                'deskripsi' => 'Solid State Drive PCIe 4.0 NVMe premium tercepat dengan kecepatan baca hingga 7450 MB/s. Sangat ideal untuk loading game instan dan transfer file raksasa.',
                'gambar' => $this->generateImage('SSD NVMe Samsung 990 PRO 1TB', 'samsung_990_pro.png'),
            ],
            [
                'nama_produk' => 'Headset Wireless Razer BlackShark V2 Pro',
                'harga_satuan' => 2390000.00,
                'stok' => 20,
                'deskripsi' => 'Headset gaming wireless esports premium dengan driver TriForce Titanium 50mm dan mikrofon super wideband. Menawarkan audio spatial super jernih dan kenyamanan tingkat profesional.',
                'gambar' => $this->generateImage('Headset Wireless Razer BlackShark V2 Pro', 'blackshark_v2_pro.png'),
            ],
            [
                'nama_produk' => 'Liquid Cooler NZXT Kraken Elite 360 RGB',
                'harga_satuan' => 4250000.00,
                'stok' => 8,
                'deskripsi' => 'Pendingin cairan CPU AIO premium dengan radiator 360mm, kipas RGB dinamis, dan layar LCD lingkaran beresolusi tinggi di atas pump head untuk menampilkan GIF atau data hardware real-time.',
                'gambar' => $this->generateImage('Liquid Cooler NZXT Kraken Elite 360 RGB', 'nzxt_kraken_360.png'),
            ],
            [
                'nama_produk' => 'Power Supply Corsair RM850x Shift',
                'harga_satuan' => 2250000.00,
                'stok' => 15,
                'deskripsi' => 'Catu daya modular penuh 850 Watt dengan sertifikasi 80 Plus Gold. Memiliki inovasi antarmuka konektor kabel di bagian samping untuk manajemen kabel sasis yang jauh lebih bersih.',
                'gambar' => $this->generateImage('Power Supply Corsair RM850x Shift', 'corsair_rm850x.png'),
            ],
            [
                'nama_produk' => 'Casing PC Lian Li O11 Dynamic EVO',
                'harga_satuan' => 2650000.00,
                'stok' => 10,
                'deskripsi' => 'Casing komputer mid-tower modular legendaris dengan desain dual-chamber dan panel kaca tempered panoramic. Menyediakan fleksibilitas luar biasa untuk kustomisasi watercooling tingkat lanjut.',
                'gambar' => $this->generateImage('Casing PC Lian Li O11 Dynamic EVO', 'lian_li_o11d.png'),
            ],
        ];

        foreach ($products as $product) {
            Produk::updateOrCreate(
                ['nama_produk' => $product['nama_produk']],
                $product
            );
        }
    }

    private function generateImage(string $name, string $fileName): string
    {
        $dir = storage_path('app/public/produk');
        if (!file_exists($dir)) {
            mkdir($dir, 0755, true);
        }

        $filePath = $dir . '/' . $fileName;

        // Create an 800x600 canvas
        $width = 800;
        $height = 600;
        $img = imagecreatetruecolor($width, $height);

        // Enable alphablending
        imagealphablending($img, true);

        // Generate dynamic high-contrast premium tech gradients
        $colors = [
            ['r1' => 15, 'g1' => 23, 'b1' => 42, 'r2' => 30, 'g2' => 41, 'b2' => 59], // Slate tech
            ['r1' => 10, 'g1' => 10, 'b1' => 10, 'r2' => 45, 'g2' => 0, 'b2' => 45],  // Midnight Violet
            ['r1' => 11, 'g1' => 19, 'b1' => 43, 'r2' => 28, 'g2' => 37, 'b2' => 65],  // Deep Navy
            ['r1' => 15, 'g1' => 23, 'b1' => 42, 'r2' => 88, 'g2' => 28, 'b2' => 135], // Tech Amethyst
            ['r1' => 15, 'g1' => 23, 'b1' => 42, 'r2' => 16, 'g2' => 185, 'b2' => 129], // Emerald cyber
        ];
        $grad = $colors[abs(crc32($name)) % count($colors)];

        // Draw the gradient line by line
        for ($y = 0; $y < $height; $y++) {
            $ratio = $y / $height;
            $r = (int)($grad['r1'] * (1 - $ratio) + $grad['r2'] * $ratio);
            $g = (int)($grad['g1'] * (1 - $ratio) + $grad['g2'] * $ratio);
            $b = (int)($grad['b1'] * (1 - $ratio) + $grad['b2'] * $ratio);
            $color = imagecolorallocate($img, $r, $g, $b);
            imageline($img, 0, $y, $width, $y, $color);
        }

        // Add abstract geometric tech patterns (pulsing translucent circles)
        for ($i = 0; $i < 5; $i++) {
            $cx = ($i * 150) + 100;
            $cy = 300 + (int)(sin($i) * 100);
            $r = 100 + ($i * 50);
            $circleColor = imagecolorallocatealpha($img, 56, 189, 248, 115); // Translucent Sky Blue
            imagefilledellipse($img, $cx, $cy, $r, $r, $circleColor);
        }

        // Add a premium thin border
        $borderColor = imagecolorallocatealpha($img, 255, 255, 255, 100);
        imagerectangle($img, 20, 20, $width - 20, $height - 20, $borderColor);

        // Stamp headers & labels
        $storeColor = imagecolorallocate($img, 14, 165, 233); // Sky-500
        imagestring($img, 5, 40, 40, "TKJ STORE PREMIUM", $storeColor);

        $watermarkColor = imagecolorallocatealpha($img, 255, 255, 255, 100);
        imagestring($img, 5, 40, 530, "100% ORIGINAL * OFFICIAL WARRANTY", $watermarkColor);

        // Center typography settings (using GD default font 5: 8x15px)
        $charWidth = 8;
        $textColor = imagecolorallocate($img, 255, 255, 255);
        $shadowColor = imagecolorallocate($img, 0, 0, 0);

        // Wrap text to fit beautifully
        $words = explode(' ', $name);
        $line1 = "";
        $line2 = "";
        if (count($words) > 3) {
            $line1 = implode(' ', array_slice($words, 0, 3));
            $line2 = implode(' ', array_slice($words, 3));
        } else {
            $line1 = $name;
        }

        // Draw line 1
        $x1 = (int)(($width - (strlen($line1) * $charWidth)) / 2);
        $y1 = 260;
        imagestring($img, 5, $x1 + 2, $y1 + 2, $line1, $shadowColor);
        imagestring($img, 5, $x1, $y1, $line1, $textColor);

        // Draw line 2 if exists
        if ($line2) {
            $x2 = (int)(($width - (strlen($line2) * $charWidth)) / 2);
            $y2 = 290;
            imagestring($img, 5, $x2 + 2, $y2 + 2, $line2, $shadowColor);
            imagestring($img, 5, $x2, $y2, $line2, $textColor);
        }

        // Output and save image
        imagepng($img, $filePath);
        imagedestroy($img);

        return 'produk/' . $fileName;
    }
}
