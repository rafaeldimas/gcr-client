<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOwnersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('owners', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('process_id');
            $table->unsignedInteger('address_id')->nullable();
            $table->string('name')->nullable();
            $table->decimal('share_capital')->nullable();
            $table->string('job_roles')->nullable();
            $table->string('change_type')->nullable();
            $table->string('job_roles_other')->nullable();
            $table->integer('marital_status')->nullable();
            $table->integer('wedding_regime')->nullable();
            $table->string('rg')->nullable();
            $table->date('rg_expedition')->nullable();
            $table->date('date_of_birth')->nullable();
            $table->string('cpf')->nullable();
            $table->timestamps();

            $table->foreign('process_id')
                ->references('id')
                ->on('processes')
                ->onDelete('cascade');

            $table->foreign('address_id')
                ->references('id')
                ->on('addresses')
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
        Schema::dropIfExists('owners');
    }
}
