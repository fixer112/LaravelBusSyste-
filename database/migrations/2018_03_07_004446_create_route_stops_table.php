<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRouteStopsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('route_stops', function (Blueprint $table) {
            $table->increments('route_stop_id');
            $table->integer('route_id')->unsigned();
            $table->integer('stop_id')->unsigned();
            $table->integer('stop_number')->unsigned();
            $table->timestamps();

            $table->foreign('stop_id')->references('stop_id')->on('stops')->onDelete('cascade');
            $table->foreign('route_id')->references('route_id')->on('routes')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('route_stops');
    }
}
