<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            PermissionSeeder::class,
            RoleSeeder::class,
            UserSeeder::class,
            CustomerSeeder::class,
            SalesChannelSeeder::class,
            ProductSeeder::class,
            OrderSeeder::class,
            OrderItemsSeeder::class,
        ]);
    }
}