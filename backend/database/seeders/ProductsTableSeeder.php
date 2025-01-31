<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('products')->insert([
            ['name' => 'Наушники', 'price' => 100], // Цена в евро
            ['name' => 'Чехол для телефона', 'price' => 20], // Цена в евро
        ]);
    }
}