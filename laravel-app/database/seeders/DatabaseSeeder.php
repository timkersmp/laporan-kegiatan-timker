<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        Admin::firstOrCreate(['username' => 'admin'], [
            'nama' => 'Administrator', 'role' => 'admin_biasa', 'password' => Hash::make('admin123'),
        ]);
        Admin::firstOrCreate(['username' => 'verifikator'], [
            'nama' => 'Karyawan', 'role' => 'verifikator', 'password' => Hash::make('admin123'),
        ]);
    }
}
