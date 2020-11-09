<?php

namespace Database\Seeders;

use App\Models\Organization;
use App\Models\User;
use App\Models\Vacancy;
use Illuminate\Database\Seeder;

class VacancySeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Vacancy::factory()
            ->count(100)
            ->create()
            ->each(function (Vacancy $vacancy) {

                $userWorkerRandom = User::where('role', 'worker')
                ->inRandomOrder()
                    ->take(rand(1,2))
                    ->get();

                $vacancy->workers_booked = $userWorkerRandom->count();
                if ($vacancy->workers_amount == $userWorkerRandom->count()) {
                    $vacancy->status = 'closed';
                }
                $vacancy->users()->attach($userWorkerRandom);
                $vacancy->save();
            });
    }
}
