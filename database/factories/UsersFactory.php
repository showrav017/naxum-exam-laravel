<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

class UsersFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id'=>uniqid('').bin2hex(random_bytes(8)),
            'user_name' => $this->faker->userName(),
            'last_logged_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
            'password' => Hash::make("password"), // password
        ];
    }
}
