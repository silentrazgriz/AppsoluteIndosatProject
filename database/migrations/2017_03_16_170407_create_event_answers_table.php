<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEventAnswersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('event_answers', function (Blueprint $table) {
	        $table->uuid('id');
	        $table->uuid('event_id');
	        $table->uuid('user_id');
	        $table->text('location')->nullable(); // json
	        $table->text('answer');
	        $table->timestamps();
	        $table->softDeletes();

	        $table->primary('id');
	        $table->foreign('event_id')->references('id')->on('events');
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
        Schema::dropIfExists('event_answers');
    }
}
