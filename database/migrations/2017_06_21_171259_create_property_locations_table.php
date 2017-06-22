<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePropertyLocationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('property_locations', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('property_details_id')->unsigned()->index();
            $table->string('Matrix_Unique_ID')->index();
            $table->string('MLSNumber')->index();
            $table->string('Area')->nullable();
            $table->string('CommunityName')->nullable();
            $table->string('ElementarySchool35')->nullable();
            $table->string('ElementarySchoolK2')->nullable();
            $table->string('HighSchool')->nullable();
            $table->text('HouseFaces')->nullable();
            $table->string('JrHighSchool')->nullable();
            $table->string('ParcelNumber')->nullable();
            $table->string('StreetNumberNumeric')->nullable();
            $table->string('SubdivisionName')->nullable();
            $table->string('SubdivisionNumber')->nullable();
            $table->string('SubdivisionNumSearch')->nullable();
            $table->string('TaxDistrict')->nullable();
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
        Schema::dropIfExists('property_locations');
    }
}
