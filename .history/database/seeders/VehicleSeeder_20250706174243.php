<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class VehicleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('vehicles')->insert([
            ['name' => 'ステップワゴン', 'maker' => 'ホンダ', 'year' => 2022],
            ['name' => 'N-BOX', 'maker' => 'ホンダ', 'year' => 2021],
            ['name' => 'ノア', 'maker' => 'トヨタ', 'year' => 2020],
        ]);

    }
}
