<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Product;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ImportAllData extends Command
{
    protected $signature = 'import:all-menu';
    protected $description = 'Import menu All dari file CSV ke tabel products';

    public function handle()
    {
        $filename = 'All_Scraping.csv';
        $path = storage_path('app/' . $filename);

        if (!file_exists($path)) {
            $this->error("File tidak ditemukan: $path");
            return;
        }

        $file = fopen($path, 'r');
        $header = fgetcsv($file, 0, ';'); // Ubah ke ',' jika delimiter CSV kamu koma

        while (($row = fgetcsv($file, 0, ';')) !== false) {
            // Skip jika kolom kurang
            if (count($row) < 5) {
                continue;
            }

            Product::create([
                'id' => Str::uuid(),
                'name' => $row[1], // kolom: nama
                'price' => $this->parsePrice($row[2]), // kolom: harga
                'description' => $row[3], // kolom: kategori
                'image' => $row[4], // kolom: gambar
            ]);
        }

        fclose($file);

        $this->info("Menu dari $filename berhasil diimpor ke tabel products.");
    }

    private function parsePrice($price)
    {
        // Hapus semua karakter selain angka
        $clean = preg_replace('/[^\d]/', '', $price);
        return (float) $clean;
    }
}
