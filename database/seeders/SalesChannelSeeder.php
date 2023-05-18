<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SalesChannelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('sales_channels')->insert([
            'name' => 'toko onlineku',
            'platform_name' => 'Tokopedia',
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }
}
