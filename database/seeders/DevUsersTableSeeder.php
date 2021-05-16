<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DevUsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //One admin
        User::create([
            'ra'       => null,
            'name'     => 'Administrator',
            'email'    => 'admin@edtech.tmp.br',
            'password' => Hash::make('password'),
            'role_id'  => Role::where('name', 'Admin')->firstOrFail()->id,
        ]);

        //A lot of students
        User::factory()->count(50)->create();
    }
}
