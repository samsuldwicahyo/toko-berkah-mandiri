<?php

namespace Database\Seeders;

use App\Models\Barang;
use App\Models\Kategori;
use Illuminate\Database\Seeder;

class BarangSeeder extends Seeder
{
    /**
     * Seed realistic sample items for Indonesian UMKM stores.
     */
    public function run(): void
    {
        $kategoriIds = Kategori::query()
            ->pluck('id', 'nama_kategori');

        $items = [
            [
                'kode_barang' => 'BRG-0001',
                'nama_barang' => 'Beras Premium 5 Kg',
                'kategori' => 'Sembako',
                'satuan' => 'sak',
                'harga_beli' => 72000,
                'harga_jual' => 78000,
                'stok' => 18,
                'stok_minimum' => 5,
            ],
            [
                'kode_barang' => 'BRG-0002',
                'nama_barang' => 'Minyak Goreng 1 Liter',
                'kategori' => 'Sembako',
                'satuan' => 'pcs',
                'harga_beli' => 16500,
                'harga_jual' => 18500,
                'stok' => 24,
                'stok_minimum' => 8,
            ],
            [
                'kode_barang' => 'BRG-0003',
                'nama_barang' => 'Gula Pasir 1 Kg',
                'kategori' => 'Sembako',
                'satuan' => 'kg',
                'harga_beli' => 15500,
                'harga_jual' => 17500,
                'stok' => 20,
                'stok_minimum' => 6,
            ],
            [
                'kode_barang' => 'BRG-0004',
                'nama_barang' => 'Tepung Terigu 1 Kg',
                'kategori' => 'Sembako',
                'satuan' => 'kg',
                'harga_beli' => 11000,
                'harga_jual' => 13000,
                'stok' => 16,
                'stok_minimum' => 5,
            ],
            [
                'kode_barang' => 'BRG-0005',
                'nama_barang' => 'Telur Ayam Ras',
                'kategori' => 'Sembako',
                'satuan' => 'kg',
                'harga_beli' => 26000,
                'harga_jual' => 29000,
                'stok' => 12,
                'stok_minimum' => 4,
            ],
            [
                'kode_barang' => 'BRG-0006',
                'nama_barang' => 'Garam Halus 500 gr',
                'kategori' => 'Sembako',
                'satuan' => 'pcs',
                'harga_beli' => 2500,
                'harga_jual' => 3500,
                'stok' => 30,
                'stok_minimum' => 10,
            ],
            [
                'kode_barang' => 'BRG-0007',
                'nama_barang' => 'Mie Instan Goreng',
                'kategori' => 'Sembako',
                'satuan' => 'pcs',
                'harga_beli' => 2800,
                'harga_jual' => 3500,
                'stok' => 60,
                'stok_minimum' => 15,
            ],
            [
                'kode_barang' => 'BRG-0008',
                'nama_barang' => 'Kecap Manis 600 ml',
                'kategori' => 'Sembako',
                'satuan' => 'botol',
                'harga_beli' => 13500,
                'harga_jual' => 15500,
                'stok' => 10,
                'stok_minimum' => 3,
            ],
            [
                'kode_barang' => 'BRG-0009',
                'nama_barang' => 'Saus Sambal 300 ml',
                'kategori' => 'Sembako',
                'satuan' => 'botol',
                'harga_beli' => 7000,
                'harga_jual' => 8500,
                'stok' => 14,
                'stok_minimum' => 4,
            ],
            [
                'kode_barang' => 'BRG-0010',
                'nama_barang' => 'Kopi Sachet 3 in 1',
                'kategori' => 'Minuman',
                'satuan' => 'pcs',
                'harga_beli' => 1800,
                'harga_jual' => 2500,
                'stok' => 48,
                'stok_minimum' => 12,
            ],
            [
                'kode_barang' => 'BRG-0011',
                'nama_barang' => 'Teh Celup Isi 25',
                'kategori' => 'Minuman',
                'satuan' => 'box',
                'harga_beli' => 8500,
                'harga_jual' => 10500,
                'stok' => 12,
                'stok_minimum' => 4,
            ],
            [
                'kode_barang' => 'BRG-0012',
                'nama_barang' => 'Air Mineral 600 ml',
                'kategori' => 'Minuman',
                'satuan' => 'pcs',
                'harga_beli' => 2500,
                'harga_jual' => 3500,
                'stok' => 72,
                'stok_minimum' => 18,
            ],
            [
                'kode_barang' => 'BRG-0013',
                'nama_barang' => 'Teh Botol 450 ml',
                'kategori' => 'Minuman',
                'satuan' => 'pcs',
                'harga_beli' => 4200,
                'harga_jual' => 5500,
                'stok' => 35,
                'stok_minimum' => 10,
            ],
            [
                'kode_barang' => 'BRG-0014',
                'nama_barang' => 'Susu UHT Cokelat 200 ml',
                'kategori' => 'Minuman',
                'satuan' => 'pcs',
                'harga_beli' => 4200,
                'harga_jual' => 5500,
                'stok' => 18,
                'stok_minimum' => 6,
            ],
            [
                'kode_barang' => 'BRG-0015',
                'nama_barang' => 'Sirup Jeruk 460 ml',
                'kategori' => 'Minuman',
                'satuan' => 'botol',
                'harga_beli' => 13500,
                'harga_jual' => 16500,
                'stok' => 8,
                'stok_minimum' => 3,
            ],
            [
                'kode_barang' => 'BRG-0016',
                'nama_barang' => 'Biskuit Sandwich',
                'kategori' => 'Jajanan',
                'satuan' => 'pcs',
                'harga_beli' => 6500,
                'harga_jual' => 8000,
                'stok' => 20,
                'stok_minimum' => 6,
            ],
            [
                'kode_barang' => 'BRG-0017',
                'nama_barang' => 'Wafer Cokelat',
                'kategori' => 'Jajanan',
                'satuan' => 'pcs',
                'harga_beli' => 7000,
                'harga_jual' => 8500,
                'stok' => 18,
                'stok_minimum' => 6,
            ],
            [
                'kode_barang' => 'BRG-0018',
                'nama_barang' => 'Keripik Singkong Balado',
                'kategori' => 'Jajanan',
                'satuan' => 'pack',
                'harga_beli' => 6500,
                'harga_jual' => 8500,
                'stok' => 15,
                'stok_minimum' => 5,
            ],
            [
                'kode_barang' => 'BRG-0019',
                'nama_barang' => 'Permen Kopi',
                'kategori' => 'Jajanan',
                'satuan' => 'pack',
                'harga_beli' => 4500,
                'harga_jual' => 6000,
                'stok' => 22,
                'stok_minimum' => 8,
            ],
            [
                'kode_barang' => 'BRG-0020',
                'nama_barang' => 'Roti Manis Cokelat',
                'kategori' => 'Jajanan',
                'satuan' => 'pcs',
                'harga_beli' => 2500,
                'harga_jual' => 4000,
                'stok' => 14,
                'stok_minimum' => 5,
            ],
            [
                'kode_barang' => 'BRG-0021',
                'nama_barang' => 'Chiki Jagung Bakar',
                'kategori' => 'Jajanan',
                'satuan' => 'pcs',
                'harga_beli' => 2200,
                'harga_jual' => 3500,
                'stok' => 28,
                'stok_minimum' => 10,
            ],
            [
                'kode_barang' => 'BRG-0022',
                'nama_barang' => 'Sabun Cuci Piring 800 ml',
                'kategori' => 'Lainnya',
                'satuan' => 'botol',
                'harga_beli' => 11500,
                'harga_jual' => 14000,
                'stok' => 9,
                'stok_minimum' => 3,
            ],
            [
                'kode_barang' => 'BRG-0023',
                'nama_barang' => 'Deterjen Sachet',
                'kategori' => 'Lainnya',
                'satuan' => 'pcs',
                'harga_beli' => 1800,
                'harga_jual' => 2500,
                'stok' => 25,
                'stok_minimum' => 8,
            ],
            [
                'kode_barang' => 'BRG-0024',
                'nama_barang' => 'Tisu Wajah 200 Sheet',
                'kategori' => 'Lainnya',
                'satuan' => 'pcs',
                'harga_beli' => 7500,
                'harga_jual' => 9500,
                'stok' => 13,
                'stok_minimum' => 4,
            ],
        ];

        foreach ($items as $item) {
            $kategoriId = $kategoriIds->get($item['kategori']);

            if (! $kategoriId) {
                continue;
            }

            Barang::updateOrCreate(
                ['kode_barang' => $item['kode_barang']],
                [
                    'nama_barang' => $item['nama_barang'],
                    'kategori_id' => $kategoriId,
                    'satuan' => $item['satuan'],
                    'harga_beli' => $item['harga_beli'],
                    'harga_jual' => $item['harga_jual'],
                    'stok' => $item['stok'],
                    'stok_minimum' => $item['stok_minimum'],
                    'gambar' => null,
                ],
            );
        }
    }
}
