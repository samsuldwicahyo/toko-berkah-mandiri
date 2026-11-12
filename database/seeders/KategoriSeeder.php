<?php

namespace Database\Seeders;

use App\Models\Kategori;
use Illuminate\Database\Seeder;

class KategoriSeeder extends Seeder
{
    /**
     * Seed the default inventory categories.
     */
    public function run(): void
    {
        foreach ([
            'Sembako',
            'Jajanan',
            'Minuman',
            'Lainnya',
        ] as $namaKategori) {
            Kategori::updateOrCreate(
                ['nama_kategori' => $namaKategori],
                ['nama_kategori' => $namaKategori],
            );
        }
    }
}
