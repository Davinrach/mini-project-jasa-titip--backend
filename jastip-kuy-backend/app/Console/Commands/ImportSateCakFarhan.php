<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Product;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ImportSateCakFarhan extends Command
{
    protected $signature = 'import:sate-menu';
    protected $description = 'Import menu Sate Pak Farhan dari file CSV ke tabel products';

    public function handle()
    {
        $filename = 'menu_Sate_Pak_Farhan-Gunung_Anyar_1.csv';
        $path = storage_path('app/' . $filename);

        if (!file_exists($path)) {
            $this->error("File tidak ditemukan: $path");
            return;
        }

        $file = fopen($path, 'r');
        $header = fgetcsv($file, 0, ';'); // Lewati header

        while (($row = fgetcsv($file, 0, ';')) !== false) {
            // Skip jika kolom kurang dari 2
            if (count($row) < 2) {
                continue;
            }

            Product::create([
                'id' => Str::uuid(),
                'name' => $row[0],
                'price' => str_replace(['Rp', '.', ','], ['', '', '.'], $row[1]),
                'description' => $row[2] ?? null,
                'image' => $row[3] ?? null,
            ]);
        }

        fclose($file);

        $this->info("Menu dari $filename berhasil diimpor ke tabel products.");
    }
}
