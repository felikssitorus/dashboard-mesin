<?php

namespace Database\Seeders;

use App\Models\Permissions;
use App\Models\Roles;
use App\Models\User;
use Hash;
use Illuminate\Database\Seeder;
use Route;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Menentukan role Administrator
        $adminRole = Roles::firstOrCreate([
            'name' => 'Administrator',
            // 'slug_name' => 'administrator',
        ]);

        // Menentukan role SUPERVISOR
        $supervisorRole = Roles::firstOrCreate([
            'name' => 'SUPERVISOR',
        ]);

        // Menambahkan user Administrator
        User::create([
            'employeId' => '000000000',
            'empTypeGroup' => 'PKWTT',
            'fullname' => 'SuperAdmin',
            'email' => 'tsup@kalbe.co.id',
            'jobLvl' => 'Administrator',
            'jobTitle' => 'Administrator',
            'groupName' => 'Cikarang',
            'groupKode' => 'KF.9999',
            'password' => Hash::make('123')
        ]);

        // Menambahkan user SUPERVISOR
        User::create([
            'employeId' => '000000001',
            'empTypeGroup' => 'PKWTT',
            'fullname' => 'SuperVisor',
            'email' => 'supervisor@kalbe.co.id',
            'jobLvl' => 'SUPERVISOR',
            'jobTitle' => 'SUPERVISOR',
            'groupName' => 'Cikarang',
            'groupKode' => 'KF.9999',
            'password' => Hash::make('123')
        ]);

        $routes = Route::getRoutes()->getRoutesByName();

        foreach ($routes as $routeName => $route) {
            // Simpan routeName dan URL ke tabel permissions
            Permissions::create([
                'url' => $routeName, // Menggunakan nama rute sebagai identifikasi
                'role_id' => $adminRole->id // Set default jobLvl, ini dapat diubah sesuai kebutuhan Anda
            ]);
        }

        // Menambahkan permissions untuk role SUPERVISOR
        $permissions = [
            'login',
            'logout',
            'v1.dashboard.index',
            'v1.proses.index',
            'v1.proses.store',
            'v1.proses.edit',
            'v1.proses.update',
            'v1.proses.destroy',
            'v1.line.index',
            'v1.line.store',
            'v1.line.edit',
            'v1.line.update',
            'v1.line.destroy',
        ];

        foreach ($routes as $routeName => $route) {
            // Simpan routeName dan URL ke tabel permissions
            Permissions::create([
                'url' => $routeName, // Menggunakan nama rute sebagai identifikasi
                'role_id' => $supervisorRole->id // Set default jobLvl, ini dapat diubah sesuai kebutuhan Anda
            ]);
        }
    }
}
