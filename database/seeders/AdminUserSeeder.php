<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class AdminUserSeeder extends Seeder
{
    /**
     * Seed the default admin user.
     */
    public function run(): void
    {
        $email = config('inventory.admin_email');

        if (User::where('email', $email)->exists()) {
            return;
        }

        $password = config('inventory.admin_password');

        if (! is_string($password) || strlen($password) < 12) {
            throw new \RuntimeException('Set SEED_ADMIN_PASSWORD to at least 12 characters before seeding.');
        }

        User::create(
            [
                'email' => $email,
                'name' => 'Admin Toko Berkah Mandiri',
                'password' => $password,
                'role' => 'admin',
                'aktif' => true,
            ]
        );
    }
}
