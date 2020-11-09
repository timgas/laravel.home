<?php

namespace Database\Factories;

use App\Models\Organization;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrganizationFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Organization::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $user_employer = User::where('role', 'employer')->inRandomOrder();

        return [
            'title' => $this->faker->company,
            'city' => $this->faker->city,
            'country' => $this->faker->country,
            'user_id' => $user_employer->first()
        ];
    }
}
