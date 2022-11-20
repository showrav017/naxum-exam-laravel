<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ContactsFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'contact_id'=>uniqid('').bin2hex(random_bytes(8)),
            'name' => $this->faker->name(),
            'phone_number' => $this->faker->phoneNumber(),
            'mobile_number' => $this->faker->phoneNumber(),
            'work_number' => $this->faker->phoneNumber(),
            'email' => $this->faker->email(),
            'created_by' => "6379b7e377c8eae446748e3421cc7"
        ];
    }
}
