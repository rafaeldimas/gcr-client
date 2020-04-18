<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateViabilitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('viabilities', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('property_type')->nullable();
            $table->string('registration_number')->nullable();
            $table->string('property_area')->nullable();
            $table->string('establishment_area')->nullable();
            $table->string('avcb_clcb_number')->nullable();
            $table->boolean('establishment_has_avcb_clcb');
            $table->boolean('same_as_business_address');
            $table->boolean('thirst');
            $table->boolean('administrative_office');
            $table->boolean('closed_deposit');
            $table->boolean('warehouse');
            $table->boolean('repair_workshop');
            $table->boolean('garage');
            $table->boolean('fuel_supply_unit');
            $table->boolean('exposure_point');
            $table->boolean('training_center');
            $table->boolean('data_processing_center');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('viabilities');
    }
}
