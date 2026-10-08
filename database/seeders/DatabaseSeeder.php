<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Mengecek apakah email sudah ada, jika belum baru dibuat
        User::firstOrCreate(
            ['email' => 'e44253363@student.polije.ac.id'],
            [
                'name'     => 'Admin',
                'password' => Hash::make('password123'),
            ]
        );

        // Jalankan ProductSeeder
        $this->call([
            ProductSeeder::class,
        ]);
    }
}