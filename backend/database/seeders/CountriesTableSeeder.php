<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CountriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('countries')->insert([
            ['code' => 'DE', 'name' => 'Германия', 'tax_rate' => 19], 
            ['code' => 'IT', 'name' => 'Италия', 'tax_rate' => 22],
            ['code' => 'GR', 'name' => 'Греция', 'tax_rate' => 24], 
        ]);
    }
}