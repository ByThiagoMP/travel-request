<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            ['id' => 1, 'name' => 'admin', 'email' => 'admin@example.com', 'password' => '$2y$10$VI8Y/QM38XJz2cuUqKyguelOyu2COhjx8OB6Qm0tEPl3dgGJgmDaa', 'permission_id' => 1],
            ['id' => 2, 'name' => 'user', 'email' => 'user@example.com', 'password' => '$2y$10$VI8Y/QM38XJz2cuUqKyguelOyu2COhjx8OB6Qm0tEPl3dgGJgmDaa', 'permission_id' => 2],
        ]);
    }
}
