<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use DB;

class GuestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('guests')->insert([
            [
                'Name' => "Dave",
                'role_id' => "1"
            ],
            [
                'Name' => "Elio",
                'role_id' => "2"            ],
            [
                'Name' => "person",
                'role_id' => "3"            ]
        ]);
    }
}
