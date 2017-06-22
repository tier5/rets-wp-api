<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePropertyDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('property_details', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('cityId')->unsigned()->index();
            $table->string('Matrix_Unique_ID')->index();
            $table->string('MLSNumber')->index();
            $table->string('ListPrice')->nullable();
            $table->string('Status')->nullable();
            $table->string('BedroomsTotalPossibleNum')->nullable();
            $table->string('BathsTotal')->nullable();
            $table->string('BathsHalf')->nullable();
            $table->string('BathsFull')->nullable();
            $table->string('NumAcres')->nullable();
            $table->string('StreetNumber')->nullable();
            $table->string('StreetName')->nullable();
            $table->string('City')->nullable();
            $table->string('PostalCode')->nullable();
            $table->text('PublicAddress')->nullable();
            $table->string('PhotoCount')->nullable();
            $table->text('VirtualTourLink')->nullable();
            $table->string('OriginalEntryTimestamp')->nullable();
            $table->timestamps();

            $table->foreign('cityId')
                ->references('id')->on('cities')
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
        Schema::dropIfExists('property_details');
    }
}
