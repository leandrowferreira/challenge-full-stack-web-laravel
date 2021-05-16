<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRolesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->id()
                ->comment('Auto-incrementing unique id');

            $table->string('name', 100)
                ->comment("The role's name");

            $table->string('description', 255)
                ->nullable()
                ->comment('Optional description to this role');

            $table->string('abilities', 255)
                ->nullable()
                ->comment('Comma-separated ability names assigned to this role');
        });

        //Laravel does not provide default methods to add table comments
        $table = env('DB_PREFIX') . 'roles';
        DB::statement("ALTER TABLE {$table} comment 'The roles applied to institution\'s users'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('roles');
    }
}
