<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_view_create_and_update_users(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
            'aktif' => true,
        ]);

        $index = $this->actingAs($admin)->get(route('user.index'));
        $index->assertOk()->assertSee('Daftar Pengguna');

        $store = $this->actingAs($admin)->post(route('user.store'), [
            'name' => 'Staff Gudang',
            'email' => 'staff.gudang@example.com',
            'role' => 'staff',
            'aktif' => '1',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $store->assertRedirect(route('user.index'));
        $store->assertSessionHas('success', 'Pengguna berhasil ditambahkan.');

        $user = User::where('email', 'staff.gudang@example.com')->firstOrFail();

        $update = $this->actingAs($admin)->patch(route('user.update', $user), [
            'name' => 'Staff Toko',
            'email' => 'staff.toko@example.com',
            'role' => 'staff',
            'password' => '',
            'password_confirmation' => '',
        ]);

        $update->assertRedirect(route('user.index'));
        $update->assertSessionHas('success', 'Pengguna berhasil diperbarui.');

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'Staff Toko',
            'email' => 'staff.toko@example.com',
            'role' => 'staff',
            'aktif' => false,
        ]);
    }

    public function test_staff_cannot_access_user_management_routes(): void
    {
        $staff = User::factory()->create([
            'role' => 'staff',
            'aktif' => true,
        ]);

        $managedUser = User::factory()->create([
            'role' => 'staff',
            'aktif' => true,
        ]);

        $this->actingAs($staff)->get(route('user.index'))->assertForbidden();
        $this->actingAs($staff)->get(route('user.create'))->assertForbidden();
        $this->actingAs($staff)->get(route('user.edit', $managedUser))->assertForbidden();
    }

    public function test_admin_cannot_deactivate_their_own_account(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
            'aktif' => true,
        ]);

        $response = $this->actingAs($admin)
            ->from(route('user.edit', $admin))
            ->patch(route('user.update', $admin), [
                'name' => $admin->name,
                'email' => $admin->email,
                'role' => 'staff',
            ]);

        $response->assertRedirect(route('user.edit', $admin));
        $response->assertSessionHas('error');

        $this->assertDatabaseHas('users', [
            'id' => $admin->id,
            'role' => 'admin',
            'aktif' => true,
        ]);
    }
}
