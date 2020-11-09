<?php

namespace Database\Seeders;

use App\Models\Organization;
use App\Models\Vacancy;
use Illuminate\Database\Seeder;

class OrganizationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return \Illuminate\Http\JsonResponse
     */


    public function run()
    {
     Organization::factory()->count(80)->create();
    }
}
