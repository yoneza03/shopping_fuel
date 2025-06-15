<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class ShoppingFuelSeeder extends Seeder
{
    public function run()
    {
        DB::table('shopping_fuels')->insert([
            ['name' => 'Premium Gasoline', 'price' => 160],
            ['name' => 'Regular Gasoline', 'price' => 150],
            ['name' => 'Diesel', 'price' => 140],
        ]);
    }
}