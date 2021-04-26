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
            ],[
                'name' => "Stof",
                'email' => "stefdemeulmeester@gmail.com",
                'password' => '$2y$10$djqeGQkB6X0vSr3Mazdg..oLkpqYvyxHLvKUo3WIg8tqDj70mf0My'
            ],[
                'name' => "rojan",
                'email' => "2505rohan@gmail.com",
                'password' => '$2y$10$ZcZ4viBU5KbxTFiMyQAALuRoFiP0G0gL/8d2GbMsFr2xpy9eR.IVy'
            ],[
                'name' => "ThomThom",
                'email' => "meli0033@hz.nl",
                'password' => '$2y$10$dV5ilKiskm2UVW7sNRvZ9.ddk9Qk94U5dDICoWwS5KYBS7k53J61.'
            ],[
                'name' => "Flip",
                'email' => "dsf",
                'password' => 'dfdfdf'
            ],
        ]);
    }
}
