<?php

namespace Tests\Feature;

use App\Models\Barang;
use App\Models\Kategori;
use App\Models\MutasiStok;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StockReportingTest extends TestCase
{
    use RefreshDatabase;

    public function test_dashboard_uses_real_stats_and_recent_mutations(): void
    {
        $user = User::factory()->create([
            'role' => 'admin',
            'aktif' => true,
            'name' => 'Admin Dashboard',
        ]);

        $kategoriA = Kategori::create(['nama_kategori' => 'Sembako']);
        $kategoriB = Kategori::create(['nama_kategori' => 'Minuman']);

        $barangA = Barang::create([
            'kode_barang' => 'BRG-0001',
            'nama_barang' => 'Gula Pasir',
            'kategori_id' => $kategoriA->id,
            'satuan' => 'kg',
            'harga_beli' => 12000,
            'harga_jual' => 14000,
            'stok' => 0,
            'stok_minimum' => 5,
            'gambar' => null,
        ]);

        $barangB = Barang::create([
            'kode_barang' => 'BRG-0002',
            'nama_barang' => 'Teh Botol',
            'kategori_id' => $kategoriB->id,
            'satuan' => 'pcs',
            'harga_beli' => 4000,
            'harga_jual' => 5000,
            'stok' => 3,
            'stok_minimum' => 5,
            'gambar' => null,
        ]);

        $barangC = Barang::create([
            'kode_barang' => 'BRG-0003',
            'nama_barang' => 'Air Mineral',
            'kategori_id' => $kategoriB->id,
            'satuan' => 'pcs',
            'harga_beli' => 2500,
            'harga_jual' => 3000,
            'stok' => 12,
            'stok_minimum' => 5,
            'gambar' => null,
        ]);

        MutasiStok::create([
            'barang_id' => $barangA->id,
            'user_id' => $user->id,
            'tipe' => 'masuk',
            'jumlah' => 4,
            'stok_sebelum' => 0,
            'stok_sesudah' => 4,
            'keterangan' => 'Mutasi lama',
            'tanggal' => '2026-07-25',
        ]);

        $recentOne = MutasiStok::create([
            'barang_id' => $barangB->id,
            'user_id' => $user->id,
            'tipe' => 'keluar',
            'jumlah' => 2,
            'stok_sebelum' => 5,
            'stok_sesudah' => 3,
            'keterangan' => 'Mutasi terbaru 1',
            'tanggal' => '2026-07-31',
        ]);

        $recentTwo = MutasiStok::create([
            'barang_id' => $barangC->id,
            'user_id' => $user->id,
            'tipe' => 'masuk',
            'jumlah' => 6,
            'stok_sebelum' => 6,
            'stok_sesudah' => 12,
            'keterangan' => 'Mutasi terbaru 2',
            'tanggal' => '2026-07-30',
        ]);

        $response = $this->actingAs($user)->get(route('dashboard'));

        $response->assertOk();
        $response->assertViewHas('stats', function (array $stats): bool {
            return $stats['total_barang'] === 3
                && $stats['total_kategori'] === 2
                && $stats['stok_menipis'] === 1
                && $stats['stok_habis'] === 1;
        });
        $response->assertViewHas('recentMutations', function ($mutations) use ($recentOne, $recentTwo): bool {
            return $mutations->count() === 3
                && $mutations->first()->is($recentOne)
                && $mutations->get(1)->is($recentTwo);
        });
        $response->assertSee('Gula Pasir');
        $response->assertSee('Teh Botol');
        $response->assertSee('Air Mineral');
    }

    public function test_riwayat_filter_by_date_and_item_works(): void
    {
        $user = User::factory()->create([
            'role' => 'staff',
            'aktif' => true,
        ]);

        $kategori = Kategori::create(['nama_kategori' => 'Sembako']);

        $barangA = Barang::create([
            'kode_barang' => 'BRG-0001',
            'nama_barang' => 'Beras',
            'kategori_id' => $kategori->id,
            'satuan' => 'sak',
            'harga_beli' => 250000,
            'harga_jual' => 270000,
            'stok' => 10,
            'stok_minimum' => 2,
            'gambar' => null,
        ]);

        $barangB = Barang::create([
            'kode_barang' => 'BRG-0002',
            'nama_barang' => 'Minyak',
            'kategori_id' => $kategori->id,
            'satuan' => 'liter',
            'harga_beli' => 17000,
            'harga_jual' => 19000,
            'stok' => 8,
            'stok_minimum' => 3,
            'gambar' => null,
        ]);

        MutasiStok::create([
            'barang_id' => $barangA->id,
            'user_id' => $user->id,
            'tipe' => 'masuk',
            'jumlah' => 4,
            'stok_sebelum' => 6,
            'stok_sesudah' => 10,
            'keterangan' => 'Beras masuk',
            'tanggal' => '2026-07-31',
        ]);

        MutasiStok::create([
            'barang_id' => $barangB->id,
            'user_id' => $user->id,
            'tipe' => 'keluar',
            'jumlah' => 2,
            'stok_sebelum' => 10,
            'stok_sesudah' => 8,
            'keterangan' => 'Minyak keluar',
            'tanggal' => '2026-07-31',
        ]);

        MutasiStok::create([
            'barang_id' => $barangA->id,
            'user_id' => $user->id,
            'tipe' => 'keluar',
            'jumlah' => 1,
            'stok_sebelum' => 11,
            'stok_sesudah' => 10,
            'keterangan' => 'Mutasi hari lain',
            'tanggal' => '2026-07-30',
        ]);

        $response = $this->actingAs($user)->get(route('stok.riwayat', [
            'tanggal' => '2026-07-31',
            'barang_id' => $barangB->id,
        ]));

        $response->assertOk();
        $response->assertViewHas('mutations', function ($mutations) use ($barangB): bool {
            return $mutations->total() === 1
                && $mutations->first()->barang_id === $barangB->id
                && $mutations->first()->tanggal->toDateString() === '2026-07-31';
        });
        $response->assertSee('Minyak');
        $response->assertDontSee('Beras masuk');
        $response->assertDontSee('Mutasi hari lain');
    }

    public function test_low_stock_report_shows_only_critical_items_in_ascending_stock_order(): void
    {
        $user = User::factory()->create([
            'role' => 'staff',
            'aktif' => true,
        ]);

        $kategori = Kategori::create(['nama_kategori' => 'Minuman']);

        $habis = Barang::create([
            'kode_barang' => 'BRG-0001',
            'nama_barang' => 'Air Mineral',
            'kategori_id' => $kategori->id,
            'satuan' => 'pcs',
            'harga_beli' => 2500,
            'harga_jual' => 3000,
            'stok' => 0,
            'stok_minimum' => 5,
            'gambar' => null,
        ]);

        $menipis = Barang::create([
            'kode_barang' => 'BRG-0002',
            'nama_barang' => 'Teh Botol',
            'kategori_id' => $kategori->id,
            'satuan' => 'pcs',
            'harga_beli' => 4000,
            'harga_jual' => 5000,
            'stok' => 2,
            'stok_minimum' => 5,
            'gambar' => null,
        ]);

        Barang::create([
            'kode_barang' => 'BRG-0003',
            'nama_barang' => 'Kopi Kaleng',
            'kategori_id' => $kategori->id,
            'satuan' => 'pcs',
            'harga_beli' => 6000,
            'harga_jual' => 8000,
            'stok' => 12,
            'stok_minimum' => 5,
            'gambar' => null,
        ]);

        $response = $this->actingAs($user)->get(route('laporan.stok-menipis'));

        $response->assertOk();
        $response->assertViewHas('items', function ($items) use ($habis, $menipis): bool {
            $collection = $items->getCollection();

            return $items->total() === 2
                && $collection->first()->is($habis)
                && $collection->get(1)->is($menipis);
        });
        $response->assertSeeInOrder(['Air Mineral', 'Teh Botol']);
        $response->assertDontSee('Kopi Kaleng');
        $response->assertSee('Estimasi Modal Restock');
    }

    public function test_low_stock_report_can_be_filtered_and_opened_in_print_mode(): void
    {
        $user = User::factory()->create([
            'role' => 'staff',
            'aktif' => true,
        ]);

        $minuman = Kategori::create(['nama_kategori' => 'Minuman']);
        $jajanan = Kategori::create(['nama_kategori' => 'Jajanan']);

        Barang::create([
            'kode_barang' => 'BRG-0001',
            'nama_barang' => 'Air Mineral',
            'kategori_id' => $minuman->id,
            'satuan' => 'pcs',
            'harga_beli' => 2500,
            'harga_jual' => 3000,
            'stok' => 0,
            'stok_minimum' => 5,
            'gambar' => null,
        ]);

        $targetItem = Barang::create([
            'kode_barang' => 'BRG-0002',
            'nama_barang' => 'Teh Botol',
            'kategori_id' => $minuman->id,
            'satuan' => 'pcs',
            'harga_beli' => 4000,
            'harga_jual' => 5000,
            'stok' => 2,
            'stok_minimum' => 5,
            'gambar' => null,
        ]);

        Barang::create([
            'kode_barang' => 'BRG-0003',
            'nama_barang' => 'Wafer Cokelat',
            'kategori_id' => $jajanan->id,
            'satuan' => 'pcs',
            'harga_beli' => 6000,
            'harga_jual' => 8000,
            'stok' => 1,
            'stok_minimum' => 5,
            'gambar' => null,
        ]);

        $response = $this->actingAs($user)->get(route('laporan.stok-menipis', [
            'kategori_id' => $minuman->id,
            'status' => 'menipis',
            'search' => 'Teh',
            'print' => 1,
        ]));

        $response->assertOk();
        $response->assertViewHas('isPrint', true);
        $response->assertViewHas('displayTotal', 1);
        $response->assertViewHas('summary', function (array $summary): bool {
            return $summary['total_kritis'] === 1
                && $summary['habis'] === 0
                && $summary['menipis'] === 1
                && $summary['qty_restock'] === 3
                && $summary['estimasi_modal'] === 12000;
        });
        $response->assertSee('Dicetak pada');
        $response->assertSee('Teh Botol');
        $response->assertDontSee('Air Mineral');
        $response->assertDontSee('Wafer Cokelat');
        $response->assertSee($targetItem->kode_barang);
    }

    public function test_stock_mutation_report_can_filter_and_print_incoming_and_outgoing_items(): void
    {
        $user = User::factory()->create([
            'role' => 'staff',
            'aktif' => true,
        ]);

        $kategori = Kategori::create(['nama_kategori' => 'Sembako']);
        $kategoriLain = Kategori::create(['nama_kategori' => 'Minuman']);

        $barangTarget = Barang::create([
            'kode_barang' => 'BRG-0001',
            'nama_barang' => 'Beras Premium',
            'kategori_id' => $kategori->id,
            'satuan' => 'sak',
            'harga_beli' => 72000,
            'harga_jual' => 78000,
            'stok' => 14,
            'stok_minimum' => 5,
            'gambar' => null,
        ]);

        $barangLain = Barang::create([
            'kode_barang' => 'BRG-0002',
            'nama_barang' => 'Air Mineral',
            'kategori_id' => $kategoriLain->id,
            'satuan' => 'pcs',
            'harga_beli' => 2500,
            'harga_jual' => 3000,
            'stok' => 20,
            'stok_minimum' => 5,
            'gambar' => null,
        ]);

        MutasiStok::create([
            'barang_id' => $barangTarget->id,
            'user_id' => $user->id,
            'tipe' => 'masuk',
            'jumlah' => 7,
            'stok_sebelum' => 7,
            'stok_sesudah' => 14,
            'keterangan' => 'Kulakan beras',
            'tanggal' => '2026-07-30',
        ]);

        MutasiStok::create([
            'barang_id' => $barangTarget->id,
            'user_id' => $user->id,
            'tipe' => 'keluar',
            'jumlah' => 3,
            'stok_sebelum' => 17,
            'stok_sesudah' => 14,
            'keterangan' => 'Penjualan harian',
            'tanggal' => '2026-07-31',
        ]);

        MutasiStok::create([
            'barang_id' => $barangLain->id,
            'user_id' => $user->id,
            'tipe' => 'masuk',
            'jumlah' => 10,
            'stok_sebelum' => 10,
            'stok_sesudah' => 20,
            'keterangan' => 'Stok minuman',
            'tanggal' => '2026-07-31',
        ]);

        $response = $this->actingAs($user)->get(route('laporan.mutasi-stok', [
            'tanggal_awal' => '2026-07-30',
            'tanggal_akhir' => '2026-07-31',
            'kategori_id' => $kategori->id,
            'search' => 'Beras',
            'print' => 1,
        ]));

        $response->assertOk();
        $response->assertViewHas('isPrint', true);
        $response->assertViewHas('displayTotal', 2);
        $response->assertViewHas('summary', function (array $summary): bool {
            return $summary['total_transaksi'] === 2
                && $summary['total_masuk'] === 7
                && $summary['total_keluar'] === 3
                && $summary['selisih_bersih'] === 4;
        });
        $response->assertSee('Laporan Mutasi Stok');
        $response->assertSee('Beras Premium');
        $response->assertSee('Kulakan beras');
        $response->assertSee('Penjualan harian');
        $response->assertSee('Masuk');
        $response->assertSee('Keluar');
        $response->assertDontSee('Air Mineral');
    }
}
