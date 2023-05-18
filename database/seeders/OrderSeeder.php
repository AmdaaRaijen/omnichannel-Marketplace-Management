<?php

namespace Database\Seeders;

use App\Models\Customer;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('orders')->insert([
            'payment_status' => 'paid',
            'paid_at' => now(),
            'total_price' => 'ORD-1',
            'customer_id' => 1,
            'sales_channel_id' => 1,
            'total_price' => 2000,
            'created_at' => now(),
            'updated_at' => now()
            
        ]);
    }
}
