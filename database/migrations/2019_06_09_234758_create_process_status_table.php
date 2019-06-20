<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProcessStatusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('process_status', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('process_id');
            $table->unsignedInteger('status_id');
            $table->timestamps();

            $table->foreign('process_id')
                ->references('id')
                ->on('processes')
                ->onDelete('cascade');

            $table->foreign('status_id')
                ->references('id')
                ->on('statuses')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('process_status');
    }
}
