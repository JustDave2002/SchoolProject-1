<?php

namespace Database\Factories;

use App\Models\Answer;
use Illuminate\Database\Eloquent\Factories\Factory;

class AnswerFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Answer::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'form_id' =>$this->faker->numberBetween(1, 10),
            'name' => $this->faker->firstName(),
            'function' =>$this->faker->jobTitle,
            'a1' =>$this->faker->numberBetween(1,5),
            'a2' =>$this->faker->numberBetween(1,5),
            'a3' =>$this->faker->numberBetween(1,5),
            'a4' =>$this->faker->numberBetween(1,5),
            'a5' =>$this->faker->numberBetween(1,5),
            'a6' =>$this->faker->numberBetween(1,5),
        ];
    }
}
