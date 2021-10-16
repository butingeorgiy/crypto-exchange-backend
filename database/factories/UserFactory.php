<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use JetBrains\PhpStorm\ArrayShape;
use Str;

class UserFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = User::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    #[ArrayShape([
        'first_name' => "string",
        'last_name' => "string",
        'phone_number' => "string",
        'email' => "string",
        'password' => "string",
        'ref_code' => "string"
    ])]
    public function definition(): array
    {
        return [
            'first_name' => $this->faker->firstName(),
            'last_name' => $this->faker->lastName(),
            'phone_number' => preg_replace('/\D+/', '', $this->faker->phoneNumber()),
            'email' => $this->faker->email(),
            'password' => User::hashPassword('password'),
            'ref_code' => Str::random(8)
        ];
    }
}
