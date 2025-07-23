<?php

namespace Database\Seeders;

use App\Models\Proses;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProsesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Proses::create([
            'name' => 'Mixing',
            'inupby' => 'tsup@kalbe.co.id'
        ]);
    }
}
