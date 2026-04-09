<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Seed admin user default.
     * Gunakan kredensial ini untuk login pertama kali,
     * lalu ganti password di production.
     */
    public function run(): void
    {
        User::create([
            'name'     => 'Admin',
            'email'    => 'admin@blog.com',
            'password' => Hash::make('password'),
        ]);
    }
}
