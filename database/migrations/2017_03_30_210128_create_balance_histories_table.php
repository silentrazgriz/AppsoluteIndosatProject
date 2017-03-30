<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBalanceHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
	    Schema::create('balance_histories', function (Blueprint $table) {
		    $table->increments('id');
		    $table->uuid('user_id');
		    $table->bigInteger('balance');
		    $table->boolean('added_by_admin');
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
	    Schema::dropIfExists('balance_histories');
    }
}
