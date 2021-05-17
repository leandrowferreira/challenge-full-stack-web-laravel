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
            ['id' => '1', 'name' => 'Admin',   'description' => 'User with steroids',    'abilities' => 'admin'],
            ['id' => '2', 'name' => 'Teacher', 'description' => 'The base of society',   'abilities' => 'teacher'],
            ['id' => '3', 'name' => 'Student', 'description' => 'Our adorable students', 'abilities' => 'student'],
        ]);
    }
}
