<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Area;

class AreasTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $areas = ['東京都', '大阪府', '福岡県'];

        foreach ($areas as $area) {
            Area::create(['name' => $area]);
        }
    }
}
