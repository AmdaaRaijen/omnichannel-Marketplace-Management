<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OrderItemsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('order_items')->insert([
            'order_id' => 1,
            'product_id' => 1,
            'quantity' => 1,
            'price' => 2000,
            'total_price' => 2000,
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }
}
