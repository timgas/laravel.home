<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
       $modelUserAdmin = User::make([
            'role' => 'admin',
            'email' => 'admin@localhost',
            'password' => '123456',
            'first_name' => 'Admin',
            'last_name' => 'Adminskiy',
            'country' => 'USA',
            'city' => 'Washington',
            'phone' => '+385005343',
        ]);
        $modelUserAdmin->save();
        $modelUserAdmin->createToken('api');

    }
}
