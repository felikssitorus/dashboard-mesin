<?php

namespace Database\Seeders;

use App\Models\Line;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LineSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Line::create([
            'name' => 'Line 8',
            'inupby' => 'tsup@kalbe.co.id'
        ]);
    }
}
