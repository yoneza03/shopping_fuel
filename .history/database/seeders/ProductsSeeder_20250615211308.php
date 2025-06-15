<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class PricesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('prices')->insert([
            [
                'product_id' => 1, 
                'store_id' => 1, 
                'user_id' => 1, 
                'price' => 160.50, 
                'purchase_date' => '2025-06-01'
            ],
            [
                'product_id' => 2,
                'store_id' => 2, 
                'user_id' => 1, 
                'price' => 150.00, 
                'purchase_date' => '2025-06-02'
            ],
        ]);
    }
}
