<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

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
    public function definition()
    {
        $user_role = ['worker', 'employer'];
        $random_user_value = array_rand($user_role);
        return [
            'role' => $user_role[$random_user_value],
            'email' => $this->faker->unique()->email,
            'password' => '123456',
            'first_name' => $this->faker->name(),
            'last_name' => $this->faker->lastName,
            'country' => $this->faker->country,
            'city' => $this-> faker -> city,
            'phone' => $this -> faker -> e164PhoneNumber,
        ];
    }
}
