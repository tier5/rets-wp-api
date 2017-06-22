<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePropertyExternalFeaturesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('property_external_features', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('property_details_id')->unsigned()->index();
            $table->string('Matrix_Unique_ID')->index();
            $table->string('MLSNumber')->index();
            $table->text('BuildingDescription')->nullable();
            $table->text('BuiltDescription')->nullable();
            $table->text('ConstructionDescription')->nullable();
            $table->string('ConvertedGarageYN')->nullable();
            $table->text('EquestrianDescription')->nullable();
            $table->text('Fence')->nullable();
            $table->text('FenceType')->nullable();
            $table->string('Garage')->nullable();
            $table->text('GarageDescription')->nullable();
            $table->text('HouseViews')->nullable();
            $table->text('LandscapeDescription')->nullable();
            $table->text('LotDescription')->nullable();
            $table->string('LotSqft')->nullable();
            $table->text('ParkingDescription')->nullable();
            $table->text('PoolDescription')->nullable();
            $table->string('PvPool')->nullable();
            $table->text('RoofDescription')->nullable();
            $table->text('Sewer')->nullable();
            $table->text('SolarElectric')->nullable();
            $table->text('Type')->nullable();
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
        Schema::dropIfExists('property_external_features');
    }
}
