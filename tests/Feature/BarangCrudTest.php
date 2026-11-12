<?php

namespace Tests\Feature;

use App\Models\Barang;
use App\Models\Kategori;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class BarangCrudTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_view_barang_list_filter_search_and_badge_status(): void
    {
        $staff = User::factory()->create([
            'role' => 'staff',
            'aktif' => true,
        ]);

        $sembako = Kategori::create(['nama_kategori' => 'Sembako']);
        $minuman = Kategori::create(['nama_kategori' => 'Minuman']);

        Barang::create([
            'kode_barang' => 'BRG-0001',
            'nama_barang' => 'Gula Pasir',
            'kategori_id' => $sembako->id,
            'satuan' => 'kg',
            'harga_beli' => 12000,
            'harga_jual' => 14000,
            'stok' => 0,
            'stok_minimum' => 5,
            'gambar' => null,
        ]);

        Barang::create([
            'kode_barang' => 'BRG-0002',
            'nama_barang' => 'Teh Botol',
            'kategori_id' => $minuman->id,
            'satuan' => 'pcs',
            'harga_beli' => 4000,
            'harga_jual' => 5000,
            'stok' => 3,
            'stok_minimum' => 5,
            'gambar' => null,
        ]);

        Barang::create([
            'kode_barang' => 'BRG-0003',
            'nama_barang' => 'Air Mineral',
            'kategori_id' => $minuman->id,
            'satuan' => 'pcs',
            'harga_beli' => 2500,
            'harga_jual' => 3000,
            'stok' => 10,
            'stok_minimum' => 5,
            'gambar' => null,
        ]);

        $response = $this
            ->actingAs($staff)
            ->get(route('barang.index', [
                'search' => 'BRG-0002',
                'kategori_id' => $minuman->id,
            ]));

        $response->assertOk();
        $response->assertSee('Daftar Barang');
        $response->assertSee('Teh Botol');
        $response->assertDontSee('Gula Pasir');
        $response->assertDontSee('Air Mineral');

        $allItems = $this->actingAs($staff)->get(route('barang.index'));
        $allItems->assertOk();
        $allItems->assertSee('Habis');
        $allItems->assertSee('Menipis');
        $allItems->assertSee('Aman');
        $allItems->assertSee('text-danger', false);
        $allItems->assertSee('text-warning', false);
        $allItems->assertSee('text-success', false);
    }

    public function test_staff_can_create_barang_with_auto_generated_code_and_image(): void
    {
        Storage::fake('public');

        $staff = User::factory()->create([
            'role' => 'staff',
            'aktif' => true,
        ]);

        $kategori = Kategori::create(['nama_kategori' => 'Sembako']);

        $response = $this->actingAs($staff)->post(route('barang.store'), [
            'nama_barang' => 'Minyak Goreng',
            'kategori_id' => $kategori->id,
            'satuan' => 'liter',
            'harga_beli' => 17000,
            'harga_jual' => 19000,
            'stok' => 8,
            'stok_minimum' => 4,
            'gambar' => UploadedFile::fake()->image('minyak.jpg'),
        ]);

        $response->assertRedirect(route('barang.index'));
        $response->assertSessionHas('success', 'Barang berhasil ditambahkan.');

        $barang = Barang::query()->firstOrFail();

        $this->assertSame('BRG-0001', $barang->kode_barang);
        $this->assertNotNull($barang->gambar);
        Storage::disk('public')->assertExists($barang->gambar);

        $secondResponse = $this->actingAs($staff)->post(route('barang.store'), [
            'nama_barang' => 'Tepung Terigu',
            'kategori_id' => $kategori->id,
            'satuan' => 'kg',
            'harga_beli' => 12000,
            'harga_jual' => 14000,
            'stok' => 6,
            'stok_minimum' => 3,
        ]);

        $secondResponse->assertRedirect(route('barang.index'));
        $this->assertDatabaseHas('barangs', [
            'kode_barang' => 'BRG-0002',
            'nama_barang' => 'Tepung Terigu',
        ]);
    }

    public function test_staff_can_edit_barang_and_replace_image_without_changing_code(): void
    {
        Storage::fake('public');

        $staff = User::factory()->create([
            'role' => 'staff',
            'aktif' => true,
        ]);

        $kategoriAwal = Kategori::create(['nama_kategori' => 'Minuman']);
        $kategoriBaru = Kategori::create(['nama_kategori' => 'Jajanan']);

        $oldImage = UploadedFile::fake()->image('teh-awal.jpg')->store('barangs', 'public');

        $barang = Barang::create([
            'kode_barang' => 'BRG-0001',
            'nama_barang' => 'Teh Celup',
            'kategori_id' => $kategoriAwal->id,
            'satuan' => 'box',
            'harga_beli' => 8000,
            'harga_jual' => 10000,
            'stok' => 4,
            'stok_minimum' => 5,
            'gambar' => $oldImage,
        ]);

        $editPage = $this->actingAs($staff)->get(route('barang.edit', $barang));
        $editPage->assertOk();
        $editPage->assertSee('Edit Barang');
        $editPage->assertSee('BRG-0001');

        $response = $this->actingAs($staff)->patch(route('barang.update', $barang), [
            'nama_barang' => 'Keripik Singkong',
            'kategori_id' => $kategoriBaru->id,
            'satuan' => 'pack',
            'harga_beli' => 6000,
            'harga_jual' => 8500,
            'stok' => 12,
            'stok_minimum' => 5,
            'gambar' => UploadedFile::fake()->image('keripik-baru.jpg'),
        ]);

        $response->assertRedirect(route('barang.index'));
        $response->assertSessionHas('success', 'Barang berhasil diperbarui.');

        $barang->refresh();

        $this->assertSame('BRG-0001', $barang->kode_barang);
        $this->assertSame('Keripik Singkong', $barang->nama_barang);
        $this->assertSame($kategoriBaru->id, $barang->kategori_id);
        Storage::disk('public')->assertMissing($oldImage);
        Storage::disk('public')->assertExists($barang->gambar);
    }

    public function test_admin_can_delete_barang_and_its_image(): void
    {
        Storage::fake('public');

        $admin = User::factory()->create([
            'role' => 'admin',
            'aktif' => true,
        ]);

        $kategori = Kategori::create(['nama_kategori' => 'Sembako']);
        $image = UploadedFile::fake()->image('beras.jpg')->store('barangs', 'public');

        $barang = Barang::create([
            'kode_barang' => 'BRG-0001',
            'nama_barang' => 'Beras Premium',
            'kategori_id' => $kategori->id,
            'satuan' => 'sak',
            'harga_beli' => 300000,
            'harga_jual' => 320000,
            'stok' => 2,
            'stok_minimum' => 4,
            'gambar' => $image,
        ]);

        $response = $this->actingAs($admin)->delete(route('barang.destroy', $barang));

        $response->assertRedirect(route('barang.index'));
        $response->assertSessionHas('success', 'Barang berhasil dihapus.');
        $this->assertDatabaseMissing('barangs', [
            'id' => $barang->id,
        ]);
        Storage::disk('public')->assertMissing($image);
    }

    public function test_staff_cannot_delete_barang(): void
    {
        $staff = User::factory()->create([
            'role' => 'staff',
            'aktif' => true,
        ]);

        $kategori = Kategori::create(['nama_kategori' => 'Minuman']);
        $barang = Barang::create([
            'kode_barang' => 'BRG-0001',
            'nama_barang' => 'Kopi Susu',
            'kategori_id' => $kategori->id,
            'satuan' => 'pcs',
            'harga_beli' => 6000,
            'harga_jual' => 8000,
            'stok' => 7,
            'stok_minimum' => 3,
            'gambar' => null,
        ]);

        $response = $this->actingAs($staff)->delete(route('barang.destroy', $barang));

        $response->assertForbidden();
        $this->assertDatabaseHas('barangs', [
            'id' => $barang->id,
        ]);
    }
}
