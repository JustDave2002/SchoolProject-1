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
                'name' => "Deef",
                'email' => "davyelshout@hotmail.com",
                'password' => '$2y$10$GSfYbfVnG2.1BgiQp1HGLuamxX7ZGsF1DLa5ka3pqsZ7DZJlUNQa.',
                'admin' => TRUE,
                'role_verified' => TRUE,
                'email_verified_at' => '2021-06-08 09:13:54'
            ],[
                'name' => "Stof",
                'email' => "stefdemeulmeester@gmail.com",
                'password' => '$2y$10$djqeGQkB6X0vSr3Mazdg..oLkpqYvyxHLvKUo3WIg8tqDj70mf0My',
                'admin' => TRUE,
                'role_verified' => TRUE,
                'email_verified_at' => '2021-06-08 09:13:54'
            ],[
                'name' => "rojan",
                'email' => "2505rohan@gmail.com",
                'password' => '$2y$10$ZcZ4viBU5KbxTFiMyQAALuRoFiP0G0gL/8d2GbMsFr2xpy9eR.IVy',
                'admin' => TRUE,
                'role_verified' => TRUE,
                'email_verified_at' => '2021-06-08 09:13:54'
            ],[
                'name' => "ThomThom",
                'email' => "meli0033@hz.nl",
                'password' => '$2y$10$dV5ilKiskm2UVW7sNRvZ9.ddk9Qk94U5dDICoWwS5KYBS7k53J61.',
                'admin' => TRUE,
                'role_verified' => TRUE,
                'email_verified_at' => '2021-06-08 09:13:54'
            ],[
                'name' => "Flip",
                'email' => "filipcichoracki13@gmail.com",
                'password' => '$2y$10$gHktBRTBfHFAdWnmgl3QFuzwNLgvxZnI1Js7J19Z24MSwRrFHhwRq',
                'admin' => TRUE,
                'role_verified' => TRUE,
                'email_verified_at' => '2021-06-08 09:13:54'
            ],
        ]);
    }
}
