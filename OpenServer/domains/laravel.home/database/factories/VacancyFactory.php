<?php

namespace Database\Factories;

use App\Http\Resources\OrganizationResource;
use App\Models\Organization;
use App\Models\User;
use App\Models\Vacancy;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Http\JsonResponse;

class VacancyFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Vacancy::class;

    public function definition()
    {
        $organization = Organization::all('id');
        $workers_amount = $this->faker->numberBetween(2, 3);

        return [
            'vacancy_name' => $this->faker->jobTitle,
            'workers_amount' => $workers_amount,
            'workers_booked' => 0,
            'status' => 'active',
            'organization_id' => $organization->random(),
            'salary' => $this->faker->numberBetween(1000, 9999)
        ];
    }
}
