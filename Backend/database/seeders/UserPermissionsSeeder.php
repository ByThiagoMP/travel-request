<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('user_permissions')->insert([
            ['id' => 1, 'name' => 'admin'],
            ['id' => 2, 'name' => 'user'],
        ]);
    }
}
