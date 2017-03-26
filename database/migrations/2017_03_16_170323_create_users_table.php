<?php

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
            $table->uuid('id');
	        $table->string('email');
	        $table->string('password');
            $table->string('name');
            $table->string('gender');
            $table->string('phone');
            $table->unsignedBigInteger('balance');
	        $table->text('last_location')->nullable(); // json
	        $table->boolean('is_admin')->default(false);
	        $table->string('remember_token')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->primary('id');
        });
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
