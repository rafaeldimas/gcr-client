<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCompaniesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('companies', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('process_id');
            $table->unsignedInteger('address_id')->nullable();
            $table->string('name')->nullable();
            $table->string('nire')->nullable();
            $table->string('cnpj')->nullable();
            $table->date('activity_start')->nullable();
            $table->decimal('share_capital')->nullable();
            $table->string('activity_description')->nullable();
            $table->integer('size')->nullable();
            $table->date('signed')->nullable();
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
        Schema::dropIfExists('companies');
    }
}
