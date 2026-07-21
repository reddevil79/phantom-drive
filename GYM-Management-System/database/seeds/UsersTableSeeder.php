<?php

use App\User;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Create User
        User::create([
            'name' => 'Bchrome',
            'email' => 'bikrambasnet@sentinellab.io',
            'password' => bcrypt('Sity1c213050'),
            'status' => '1',
        ]);
    }
}
