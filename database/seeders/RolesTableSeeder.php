<?php

namespace Database\Seeders;

use App\Models\Role;
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
            ['id' => Role::ID_ADMIN, 'name' => 'Admin',   'description' => 'User with steroids',    'abilities' => 'admin'],
            ['id' => Role::ID_TEACHER, 'name' => 'Teacher', 'description' => 'The base of society',   'abilities' => 'teacher'],
            ['id' => Role::ID_STUDENT, 'name' => 'Student', 'description' => 'Our adorable students', 'abilities' => 'student'],
        ]);
    }
}
