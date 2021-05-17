<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id()
                ->comment('Auto-incrementing unique id');

            $table->string('ra')
                ->unique()
                ->nullable()
                ->comment("Acronym to student's Academic Record Number [Registro Acadêmico]");

            $table->string('cpf', 11)
                ->unique()
                ->nullable()
                ->comment('Brazilian document, unique for every person');

            $table->string('name')
                ->comment("User's name");

            $table->string('email')
                ->unique()
                ->comment("User's e-mail");

            $table->string('password')
                ->nullable()
                ->comment('Hashed password');

            $table->foreignId('role_id')
                ->nullable()
                ->default(3)
                ->comment("The user's role in the company, that is 'Student' by default. For sake of simplicity, only one rule per user");

            //Creation, update and delete timestamps
            $table->timestamps();
            $table->softDeletes();

            //Foreign key to Role model
            $table->foreign('role_id')->references('id')->on('roles')->onDelete('cascade');
        });

        //Laravel does not provide default methods to add table comments
        if (DB::connection()->getDriverName() != 'sqlite') {
            $table = env('DB_PREFIX') . 'users';
            DB::statement("ALTER TABLE {$table} comment 'The institution\'s users, including (not only) administrative and students'");
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
