<?php

namespace Database\Factories;

use App\Models\FeedbackForm;
use Illuminate\Database\Eloquent\Factories\Factory;

class FeedbackFormFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = FeedbackForm::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id' => 1,
            'title' => $this->faker->title,
            'q1' =>$this->faker->sentence,
            'q2' =>$this->faker->sentence,
            'q3' =>$this->faker->sentence,
            'q4' =>$this->faker->sentence,
            'q5' =>$this->faker->sentence,
            'q6' =>$this->faker->sentence,
        ];
    }
}
