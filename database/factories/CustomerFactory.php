<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class CustomerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            //
            'job_title' =>  $this->faker->name(),
            'email_address' =>  $this->faker->unique()->safeEmail,
            'first_name'   => $this->faker->firstName(),
            'last_name'         => $this->faker->lastName(),
            'registered_since'   => now(),
            'phone'         => $this->faker->randomDigit()
        ];
    }
}
