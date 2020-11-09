<?php

namespace Database\Seeders;

use App\Http\Controllers\AuthController;
use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $collection = User::factory() -> count(100)->create();

        foreach ($collection as $item) {
            $item->createToken('api');
        }
    }
}
