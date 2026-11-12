<?php

namespace Tests\Feature;

use App\Models\Kategori;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class KategoriCrudTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_view_category_list_and_search(): void
    {
        $staff = User::factory()->create([
            'role' => 'staff',
            'aktif' => true,
        ]);

        Kategori::create(['nama_kategori' => 'Sembako']);
        Kategori::create(['nama_kategori' => 'Minuman']);

        $response = $this
            ->actingAs($staff)
            ->get(route('kategori.index', ['search' => 'Minu']));

        $response->assertOk();
        $response->assertSee('Daftar Kategori');
        $response->assertSee('Minuman');
        $response->assertDontSee('Sembako');
    }

    public function test_staff_can_create_category(): void
    {
        $staff = User::factory()->create([
            'role' => 'staff',
            'aktif' => true,
        ]);

        $response = $this->actingAs($staff)->post(route('kategori.store'), [
            'nama_kategori' => 'Jajanan',
        ]);

        $response->assertRedirect(route('kategori.index'));
        $response->assertSessionHas('success', 'Kategori berhasil ditambahkan.');
        $this->assertDatabaseHas('kategoris', [
            'nama_kategori' => 'Jajanan',
        ]);
    }

    public function test_admin_can_edit_and_delete_category(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
            'aktif' => true,
        ]);

        $kategori = Kategori::create([
            'nama_kategori' => 'Makanan Ringan',
        ]);

        $editPage = $this->actingAs($admin)->get(route('kategori.edit', $kategori));
        $editPage->assertOk();
        $editPage->assertSee('Edit Kategori');
        $editPage->assertSee('Makanan Ringan');

        $updateResponse = $this->actingAs($admin)->patch(route('kategori.update', $kategori), [
            'nama_kategori' => 'Makanan Kering',
        ]);

        $updateResponse->assertRedirect(route('kategori.index'));
        $updateResponse->assertSessionHas('success', 'Kategori berhasil diperbarui.');
        $this->assertDatabaseHas('kategoris', [
            'id' => $kategori->id,
            'nama_kategori' => 'Makanan Kering',
        ]);

        $deleteResponse = $this->actingAs($admin)->delete(route('kategori.destroy', $kategori));

        $deleteResponse->assertRedirect(route('kategori.index'));
        $deleteResponse->assertSessionHas('success', 'Kategori berhasil dihapus.');
        $this->assertDatabaseMissing('kategoris', [
            'id' => $kategori->id,
        ]);
    }

    public function test_staff_cannot_access_admin_only_category_actions(): void
    {
        $staff = User::factory()->create([
            'role' => 'staff',
            'aktif' => true,
        ]);

        $kategori = Kategori::create([
            'nama_kategori' => 'Sembako',
        ]);

        $this->actingAs($staff)
            ->get(route('kategori.edit', $kategori))
            ->assertForbidden();

        $this->actingAs($staff)
            ->patch(route('kategori.update', $kategori), [
                'nama_kategori' => 'Sembako Baru',
            ])
            ->assertForbidden();

        $this->actingAs($staff)
            ->delete(route('kategori.destroy', $kategori))
            ->assertForbidden();
    }

    public function test_category_name_is_required_and_unique(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
            'aktif' => true,
        ]);

        $kategori = Kategori::create([
            'nama_kategori' => 'Sembako',
        ]);

        $this->actingAs($admin)
            ->from(route('kategori.create'))
            ->post(route('kategori.store'), [
                'nama_kategori' => '',
            ])
            ->assertRedirect(route('kategori.create'))
            ->assertSessionHasErrors('nama_kategori');

        $this->actingAs($admin)
            ->from(route('kategori.create'))
            ->post(route('kategori.store'), [
                'nama_kategori' => 'Sembako',
            ])
            ->assertRedirect(route('kategori.create'))
            ->assertSessionHasErrors('nama_kategori');

        $this->actingAs($admin)
            ->patch(route('kategori.update', $kategori), [
                'nama_kategori' => 'Sembako',
            ])
            ->assertRedirect(route('kategori.index'));
    }
}
