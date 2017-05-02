<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEventsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('events', function (Blueprint $table) {
	        $table->uuid('id');
	        $table->string('name');
	        $table->date('start_date');
	        $table->date('end_date');
	        $table->string('auth_code');
	        $table->text('survey'); // json
	        $table->text('kpi'); // json
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
        Schema::dropIfExists('events');
    }
}
