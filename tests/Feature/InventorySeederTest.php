<?php

namespace Tests\Feature;

use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class InventorySeederTest extends TestCase
{
    use RefreshDatabase;

    public function test_database_seeder_populates_default_categories_and_sample_items(): void
    {
        $this->seed(DatabaseSeeder::class);

        $this->assertDatabaseHas('kategoris', [
            'nama_kategori' => 'Sembako',
        ]);

        $this->assertDatabaseHas('kategoris', [
            'nama_kategori' => 'Jajanan',
        ]);

        $this->assertDatabaseHas('kategoris', [
            'nama_kategori' => 'Minuman',
        ]);

        $this->assertDatabaseHas('kategoris', [
            'nama_kategori' => 'Lainnya',
        ]);

        $this->assertDatabaseHas('barangs', [
            'kode_barang' => 'BRG-0001',
            'nama_barang' => 'Beras Premium 5 Kg',
        ]);

        $this->assertDatabaseHas('barangs', [
            'kode_barang' => 'BRG-0012',
            'nama_barang' => 'Air Mineral 600 ml',
        ]);

        $this->assertDatabaseHas('barangs', [
            'kode_barang' => 'BRG-0018',
            'nama_barang' => 'Keripik Singkong Balado',
        ]);

        $this->assertDatabaseHas('barangs', [
            'kode_barang' => 'BRG-0024',
            'nama_barang' => 'Tisu Wajah 200 Sheet',
        ]);

        $this->assertSame(24, DB::table('barangs')->count());
    }
}
