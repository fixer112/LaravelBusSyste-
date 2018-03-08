<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRoutesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('routes', function (Blueprint $table) {
            $table->increments('route_id');
            $table->integer('start')->unsigned();
            $table->integer('destination')->unsigned();
            $table->enum('active_days',['whole week','work days', 'weekends']);
            $table->date('active_date_start');
            $table->date('active_date_end');
            $table->timestamps();

            $table->foreign('start')->references('stop_id')->on('stops')->onDelete('cascade');
            $table->foreign('destination')->references('stop_id')->on('stops')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('routes');
    }
}
