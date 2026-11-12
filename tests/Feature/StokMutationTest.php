<?php

namespace Tests\Feature;

use App\Models\Barang;
use App\Models\Kategori;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StokMutationTest extends TestCase
{
    use RefreshDatabase;

    public function test_staff_can_view_stock_mutation_forms(): void
    {
        $staff = User::factory()->create([
            'role' => 'staff',
            'aktif' => true,
        ]);

        $kategori = Kategori::create(['nama_kategori' => 'Sembako']);
        Barang::create([
            'kode_barang' => 'BRG-0001',
            'nama_barang' => 'Beras Medium',
            'kategori_id' => $kategori->id,
            'satuan' => 'sak',
            'harga_beli' => 250000,
            'harga_jual' => 270000,
            'stok' => 5,
            'stok_minimum' => 2,
            'gambar' => null,
        ]);

        $this->actingAs($staff)
            ->get(route('stok.masuk'))
            ->assertOk()
            ->assertSee('Catat Stok Masuk')
            ->assertSee('Beras Medium');

        $this->actingAs($staff)
            ->get(route('stok.keluar'))
            ->assertOk()
            ->assertSee('Catat Stok Keluar')
            ->assertSee('Beras Medium');
    }

    public function test_stok_masuk_updates_barang_stock_and_creates_mutation_record(): void
    {
        $staff = User::factory()->create([
            'role' => 'staff',
            'aktif' => true,
        ]);

        $kategori = Kategori::create(['nama_kategori' => 'Minuman']);
        $barang = Barang::create([
            'kode_barang' => 'BRG-0001',
            'nama_barang' => 'Sirup Jeruk',
            'kategori_id' => $kategori->id,
            'satuan' => 'botol',
            'harga_beli' => 14000,
            'harga_jual' => 17000,
            'stok' => 4,
            'stok_minimum' => 2,
            'gambar' => null,
        ]);

        $response = $this->actingAs($staff)->post(route('stok.masuk.store'), [
            'barang_id' => $barang->id,
            'jumlah' => 6,
            'keterangan' => 'Kulakan dari distributor',
            'tanggal' => '2026-07-31',
        ]);

        $response->assertRedirect(route('stok.masuk'));
        $response->assertSessionHas('success', 'Stok masuk berhasil disimpan.');

        $barang->refresh();

        $this->assertSame(10, $barang->stok);
        $this->assertDatabaseHas('mutasi_stoks', [
            'barang_id' => $barang->id,
            'user_id' => $staff->id,
            'tipe' => 'masuk',
            'jumlah' => 6,
            'stok_sebelum' => 4,
            'stok_sesudah' => 10,
            'keterangan' => 'Kulakan dari distributor',
        ]);
        $this->assertSame(
            '2026-07-31',
            $barang->mutasiStoks()->latest('id')->firstOrFail()->tanggal->toDateString()
        );
    }

    public function test_stok_keluar_updates_barang_stock_and_creates_mutation_record(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
            'aktif' => true,
        ]);

        $kategori = Kategori::create(['nama_kategori' => 'Jajanan']);
        $barang = Barang::create([
            'kode_barang' => 'BRG-0001',
            'nama_barang' => 'Biskuit Kaleng',
            'kategori_id' => $kategori->id,
            'satuan' => 'pcs',
            'harga_beli' => 18000,
            'harga_jual' => 22000,
            'stok' => 9,
            'stok_minimum' => 3,
            'gambar' => null,
        ]);

        $response = $this->actingAs($admin)->post(route('stok.keluar.store'), [
            'barang_id' => $barang->id,
            'jumlah' => 4,
            'keterangan' => 'Pesanan pelanggan grosir',
            'tanggal' => '2026-07-31',
        ]);

        $response->assertRedirect(route('stok.keluar'));
        $response->assertSessionHas('success', 'Stok keluar berhasil disimpan.');

        $barang->refresh();

        $this->assertSame(5, $barang->stok);
        $this->assertDatabaseHas('mutasi_stoks', [
            'barang_id' => $barang->id,
            'user_id' => $admin->id,
            'tipe' => 'keluar',
            'jumlah' => 4,
            'stok_sebelum' => 9,
            'stok_sesudah' => 5,
            'keterangan' => 'Pesanan pelanggan grosir',
        ]);
        $this->assertSame(
            '2026-07-31',
            $barang->mutasiStoks()->latest('id')->firstOrFail()->tanggal->toDateString()
        );
    }

    public function test_stok_keluar_is_rejected_when_amount_exceeds_available_stock(): void
    {
        $staff = User::factory()->create([
            'role' => 'staff',
            'aktif' => true,
        ]);

        $kategori = Kategori::create(['nama_kategori' => 'Sembako']);
        $barang = Barang::create([
            'kode_barang' => 'BRG-0001',
            'nama_barang' => 'Tepung Beras',
            'kategori_id' => $kategori->id,
            'satuan' => 'kg',
            'harga_beli' => 11000,
            'harga_jual' => 13000,
            'stok' => 2,
            'stok_minimum' => 1,
            'gambar' => null,
        ]);

        $response = $this->actingAs($staff)
            ->from(route('stok.keluar'))
            ->post(route('stok.keluar.store'), [
                'barang_id' => $barang->id,
                'jumlah' => 5,
                'keterangan' => 'Permintaan toko cabang',
                'tanggal' => '2026-07-31',
            ]);

        $response->assertRedirect(route('stok.keluar'));
        $response->assertSessionHasErrors([
            'jumlah' => 'Jumlah stok keluar tidak boleh melebihi stok yang tersedia. Stok saat ini: 2.',
        ]);

        $barang->refresh();

        $this->assertSame(2, $barang->stok);
        $this->assertDatabaseCount('mutasi_stoks', 0);
    }
}
