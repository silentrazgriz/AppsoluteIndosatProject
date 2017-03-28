<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserLocationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
	    Schema::create('user_locations', function (Blueprint $table) {
		    $table->increments('id');
		    $table->uuid('user_id');
		    $table->string('state');
		    $table->text('location')->nullable(); // json
		    $table->timestamps();
		    $table->softDeletes();

		    $table->foreign('user_id')->references('id')->on('users');
	    });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
	    Schema::dropIfExists('user_locations');
    }
}
