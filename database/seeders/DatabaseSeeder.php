<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            RolesTableSeeder::class,
            UsersTableSeeder::class,
        ]);

        if (env('APP_ENV') == 'local' || env('APP_ENV') == 'testing') {
            $this->call([
                DevUsersTableSeeder::class
            ]);
        }
    }
}
