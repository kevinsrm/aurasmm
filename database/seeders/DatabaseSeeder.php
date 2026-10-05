<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'kevinsthephan9@gmail.com'],
            [
                'name' => 'Kevin Administrador',
                'password' => Hash::make('12345678'),
                'is_admin' => true,
                'balance' => 500.00,
            ]
        );
    }
}
