<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePropertyInteriorFeaturesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('property_interior_features', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('property_details_id')->unsigned()->index();
            $table->string('Matrix_Unique_ID')->index();
            $table->string('MLSNumber')->index();
            $table->string('ApproxTotalLivArea')->nullable();
            $table->string('BathDownstairsDescription')->nullable();
            $table->string('BathDownYN')->nullable();
            $table->string('BedroomDownstairsYN')->nullable();
            $table->string('BedroomsTotalPossibleNum')->nullable();
            $table->text('CoolingDescription')->nullable();
            $table->string('CoolingFuel')->nullable();
            $table->string('DishwasherYN')->nullable();
            $table->string('DisposalYN')->nullable();
            $table->string('DryerIncluded')->nullable();
            $table->string('DryerUtilities')->nullable();
            $table->text('EnergyDescription')->nullable();
            $table->text('FireplaceDescription')->nullable();
            $table->text('FireplaceLocation')->nullable();
            $table->string('Fireplaces')->nullable();
            $table->text('FlooringDescription')->nullable();
            $table->text('FurnishingsDescription')->nullable();
            $table->text('HeatingDescription')->nullable();
            $table->text('HeatingFuel')->nullable();
            $table->text('Interior')->nullable();
            $table->string('NumDenOther')->nullable();
            $table->text('OtherApplianceDescription')->nullable();
            $table->text('OvenDescription')->nullable();
            $table->string('RoomCount')->nullable();
            $table->string('ThreeQtrBaths')->nullable();
            $table->text('UtilityInformation')->nullable();
            $table->string('WasherIncluded')->nullable();
            $table->text('WasherDryerLocation')->nullable();
            $table->text('Water')->nullable();
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
        Schema::dropIfExists('property_interior_features');
    }
}
