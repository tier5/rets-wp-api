<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePropertyLatLongsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('property_lat_longs', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('property_details_id')->unsigned()->index();
            $table->string('Matrix_Unique_ID')->index();
            $table->string('MLSNumber')->index();
            $table->string('latitude')->nullable();
            $table->string('longitude')->nullable();
            $table->text('FormatedAddress')->nullable();
            $table->timestamps();

            $table->foreign('property_details_id')
                ->references('id')->on('property_details')
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
        Schema::dropIfExists('property_lat_longs');
    }
}
