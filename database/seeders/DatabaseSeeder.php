<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Jalankan Seeder Utama jika ada
        $this->call([
            MasterDataSeeder::class,
        ]);

        // Buat Akun Admin Default jika belum ada
        User::firstOrCreate(
            ['email' => 'admin@bappeda.go.id'],
            [
                'name'     => 'Administrator Bappeda',
                'password' => Hash::make('admin123'),
            ]
        );
    }
}