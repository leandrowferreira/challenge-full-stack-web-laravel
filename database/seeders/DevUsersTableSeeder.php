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
            'cpf'      => '11111111111',
            'name'     => 'Administrator',
            'email'    => 'admin@edtech.tmp.br',
            'password' => Hash::make('password'),
            'role_id'  => Role::where('name', 'Admin')->firstOrFail()->id,
        ]);
        //One specific student (for testing purpouse)
        User::create([
            'ra'       => 1,
            'cpf'      => '22222222222',
            'name'     => 'Common user',
            'email'    => 'student@edtech.tmp.br',
            'password' => Hash::make('password'),
            'role_id'  => Role::where('name', 'Student')->firstOrFail()->id,
        ]);

        //A lot of students
        User::factory()->count(30)->create();
    }
}
