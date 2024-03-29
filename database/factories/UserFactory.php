<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use JetBrains\PhpStorm\ArrayShape;

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
        $phoneNumber = preg_replace('/\D+/', '', $this->faker->phoneNumber());

        if (strlen($phoneNumber) === 10) {
            $phoneNumber = '7' . $phoneNumber;
        }

        return [
            'first_name' => $this->faker->firstName(),
            'last_name' => $this->faker->lastName(),
            'phone_number' => $phoneNumber,
            'email' => $this->faker->email(),
            'password' => User::hashPassword('Qiqr$LGD3a4bf$&$'),
            'ref_code' => User::generateRefCode()
        ];
    }
}
