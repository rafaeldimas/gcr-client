<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProcessesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('processes', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('viability_id')->nullable();
            $table->string('protocol');
            $table->integer('operation')->nullable();
            $table->integer('type_company')->nullable();
            $table->integer('new_type_company')->nullable();
            $table->text('description')->nullable();
            $table->boolean('editing');
            $table->boolean('scanned')->nullable();
            $table->boolean('post_office')->nullable();
            $table->text('fields_editing')->nullable();
            $table->timestamps();

            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');

            $table->foreign('viability_id')
                ->references('id')
                ->on('viabilities')
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
        Schema::dropIfExists('processes');
    }
}
