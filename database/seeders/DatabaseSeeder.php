<?php

namespace Database\Seeders;

use App\Models\Answer;
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
        $this->call(UserSeeder::class);
        $this->call(RoleSeeder::class);
        $this->call(FeedbackFormSeeder::class);
        $this->call(QuestionSeeder::class);
        $this->call(GuestSeeder::class);
        //$this->call(AnswerSeeder::class);
    }
}
