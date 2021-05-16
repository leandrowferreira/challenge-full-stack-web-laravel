<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('roles')->insert([
            ['name' => 'Admin',   'description' => 'User with steroids',    'abilities' => 'admin'],
            ['name' => 'Teacher', 'description' => 'The base of society',   'abilities' => 'teacher'],
            ['name' => 'Student', 'description' => 'Our adorable students', 'abilities' => 'student'],
        ]);
    }
}
