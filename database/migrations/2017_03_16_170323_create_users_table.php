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
            $table->uuid('event_id')->nullable();
            $table->unsignedInteger('sales_area_id')->nullable();
            $table->string('area')->nullable();
	        $table->string('email');
	        $table->string('password');
            $table->string('name');
            $table->string('gender');
            $table->string('phone');
            $table->unsignedBigInteger('balance');
	        $table->smallInteger('is_admin')->default(0);
	        $table->string('remember_token')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->primary('id');
	        $table->foreign('event_id')->references('id')->on('events');
	        $table->foreign('sales_area_id')->references('id')->on('sales_areas');
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
