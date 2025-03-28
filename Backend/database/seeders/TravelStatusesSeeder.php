<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TravelStatusesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('travel_order_statuses')->insert([
            ['id' => 1, 'name' => 'solicitado'],
            ['id' => 2, 'name' => 'aprovado'],
            ['id' => 3, 'name' => 'cancelado'],
        ]);
    }        
}
