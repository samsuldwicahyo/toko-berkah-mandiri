<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\AdminUserSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminDashboardFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_seed_user_can_login_and_see_dashboard_layout(): void
    {
        $this->seed(AdminUserSeeder::class);

        $response = $this->post('/login', [
            'email' => 'admin@tokoberkahmandiri.test',
            'password' => config('inventory.admin_password'),
        ]);

        $response->assertRedirect('/dashboard');
        $this->assertAuthenticated();

        $dashboard = $this->get('/dashboard');

        $dashboard->assertOk();
        $dashboard->assertSee('Dashboard');
        $dashboard->assertSee('Toko Berkah Mandiri');
        $dashboard->assertSee('Admin Toko Berkah Mandiri');
        $dashboard->assertSee('Total Jenis Barang');
        $dashboard->assertSee('Total Kategori');
        $dashboard->assertSee('Stok Menipis');
        $dashboard->assertSee('Stok Habis');
        $dashboard->assertSee('5 Mutasi Stok Terakhir');
        $dashboard->assertSee('href="'.route('kategori.index').'"', false);
        $dashboard->assertSee('href="'.route('barang.index').'"', false);
        $dashboard->assertSee('href="'.route('stok.masuk').'"', false);
        $dashboard->assertSee('href="'.route('stok.keluar').'"', false);
        $dashboard->assertSee('href="'.route('stok.riwayat').'"', false);
        $dashboard->assertSee('href="'.route('laporan.stok-menipis').'"', false);
        $dashboard->assertSee('href="'.route('user.index').'"', false);
    }

    public function test_logout_redirects_back_to_login(): void
    {
        $user = User::factory()->create([
            'role' => 'admin',
            'aktif' => true,
        ]);

        $response = $this->actingAs($user)->post('/logout');

        $response->assertRedirect('/login');
        $this->assertGuest();
    }

    public function test_staff_cannot_access_admin_only_route(): void
    {
        $staff = User::factory()->create([
            'role' => 'staff',
            'aktif' => true,
        ]);

        $response = $this->actingAs($staff)->get(route('user.index'));

        $response->assertForbidden();
    }

    public function test_admin_can_pass_role_middleware_on_admin_only_route(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
            'aktif' => true,
        ]);

        $response = $this->actingAs($admin)->get(route('user.index'));

        $response->assertOk();
        $response->assertSee('Daftar Pengguna');
    }
}
