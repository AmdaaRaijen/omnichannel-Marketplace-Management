<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('products')->insert([
            'name' => 'barang 1',
            'description' => 'ini adalah barang 1',
            'price' => '2000',
            'weight' => '20',
            'length' => '20',
            'width' => '20',
            'height' => '20',
            'sku' => 'BARANG-1',
            'created_at' => now(),
            'updated_at' => now()
        ]);

    }
}
