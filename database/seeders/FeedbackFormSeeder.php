<?php

namespace Database\Seeders;

use App\Models\FeedbackForm;
use Illuminate\Database\Seeder;

class FeedbackFormSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        FeedbackForm::factory()
            ->count(3)
            ->create();
    }
}
