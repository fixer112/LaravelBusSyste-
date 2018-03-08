<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTicketsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tickets', function (Blueprint $table) {
            $table->increments('ticket_id');
            $table->integer('ticket_owner')->unsigned();
            $table->integer('start')->unsigned();
            $table->integer('destination')->unsigned();
            $table->integer('scheduled_drive')->unsigned();
            $table->float('ticket_price')->unsigned();
            $table->date('ticket_date');
            $table->timestamps();

            $table->foreign('start')->references('stop_id')->on('stops')->onDelete('cascade');
            $table->foreign('destination')->references('stop_id')->on('stops')->onDelete('cascade');
            $table->foreign('ticket_owner')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('scheduled_drive')->references('schedule_id')->on('schedules')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tickets');
    }
}
