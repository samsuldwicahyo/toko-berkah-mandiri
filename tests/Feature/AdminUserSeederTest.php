<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\AdminUserSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AdminUserSeederTest extends TestCase
{
    use RefreshDatabase;

    public function test_seeding_preserves_an_existing_admin_password(): void
    {
        config(['inventory.admin_password' => 'first-test-password']);
        $this->seed(AdminUserSeeder::class);

        config(['inventory.admin_password' => 'second-test-password']);
        $this->seed(AdminUserSeeder::class);

        $admin = User::where('email', config('inventory.admin_email'))->firstOrFail();
        $this->assertTrue(Hash::check('first-test-password', $admin->password));
        $this->assertDatabaseCount('users', 1);
    }

    public function test_seeding_rejects_a_missing_admin_password(): void
    {
        config(['inventory.admin_password' => null]);
        $this->expectException(\RuntimeException::class);
        $this->seed(AdminUserSeeder::class);
    }
}
