<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        // Buat role admin dan karyawan
        $adminRole    = Role::firstOrCreate(['name' => 'admin']);
        $karyawanRole = Role::firstOrCreate(['name' => 'karyawan']);

        // Buat akun admin default jika belum ada
        $admin = User::firstOrCreate(
            ['email' => 'admin@kos.com'],
            [
                'name'     => 'Admin Kos',
                'password' => Hash::make('password123'),
            ]
        );
        $admin->assignRole($adminRole);
    }
}
