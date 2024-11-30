<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Genre;

class GenresTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $genres = ['寿司', '焼肉', '居酒屋', 'イタリアン', 'ラーメン'];

        foreach ($genres as $genre) {
            Genre::create(['name' => $genre]);
        }
    }
}
