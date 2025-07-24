<?php

namespace Database\Seeders;

use App\Models\Mesin;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MesinSeeder extends Seeder
{
    public function run(): void
    {
        Mesin::updateOrCreate(
            ['kodeMesin' => 'M001'],
            [
                'name' => 'Servolift',
                'kapasitas' => 'Max. 150kg, 50L-400L',
                'speed' => '5-20 rpm',
                'inupby' => 'tsup@kalbe.co.id'
            ]
        );

        Mesin::updateOrCreate(
            ['kodeMesin' => 'M002'],
            [
                'name' => 'IBC Servolift',
                'kapasitas' => '800L',
                'inupby' => 'tsup@kalbe.co.id'
            ]
        );

        Mesin::updateOrCreate(
            ['kodeMesin' => 'M003'],
            [
                'name' => 'HDGC 100 (Huttlin)',
                'kapasitas' => 'Max. 100kg, Max. 250L, Max. Air flow 2000 m3/jam',
                'inupby' => 'tsup@kalbe.co.id'
            ]
        );

        Mesin::updateOrCreate(
            ['kodeMesin' => 'M004'],
            [
                'name' => 'M0801 Quadrocomill U-20 (1)',
                'speed' => '450-2700 rpm',
                'inupby' => 'tsup@kalbe.co.id'
            ]
        );
    }
}