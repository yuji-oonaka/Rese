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
        $areas = [
        ['name' => '東京都', 'name_kana' => 'とうきょうと', 'name_katakana' => 'トウキョウト'],
        ['name' => '大阪府', 'name_kana' => 'おおさかふ', 'name_katakana' => 'オオサカフ'],
        ['name' => '福岡県', 'name_kana' => 'ふくおかけん', 'name_katakana' => 'フクオカケン'],
        ];

        foreach ($areas as $area) {
            Area::create($area);
        }
    }
}
