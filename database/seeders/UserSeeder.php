<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            [
                'name' => "Dave",
                'email' => "davyelshout@hotmail.com",
                'password' => '$2y$10$GSfYbfVnG2.1BgiQp1HGLuamxX7ZGsF1DLa5ka3pqsZ7DZJlUNQa.'
            ]
        ]);
    }
}
