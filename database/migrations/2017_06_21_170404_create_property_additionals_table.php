<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePropertyAdditionalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('property_additionals', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('property_details_id')->unsigned()->index();
            $table->string('Matrix_Unique_ID')->index();
            $table->string('MLSNumber')->index();
            $table->string('AgeRestrictedCommunityYN')->nullable();
            $table->string('Assessments')->nullable();
            $table->text('AssociationFeaturesAvailable')->nullable();
            $table->text('AssociationFeeIncludes')->nullable();
            $table->string('AssociationName')->nullable();
            $table->string('Builder')->nullable();
            $table->string('CensusTract')->nullable();
            $table->string('CourtApproval')->nullable();
            $table->string('GatedYN')->nullable();
            $table->string('GreenBuildingCertificationYN')->nullable();
            $table->string('BathsHalf')->nullable();
            $table->string('ListingAgreementType')->nullable();
            $table->string('Litigation')->nullable();
            $table->string('MasterPlanFeeMQYN')->nullable();
            $table->text('MiscellaneousDescription')->nullable();
            $table->string('Model')->nullable();
            $table->string('OwnerLicensee')->nullable();
            $table->string('Ownership')->nullable();
            $table->string('PoweronorOff')->nullable();
            $table->text('PropertyDescription')->nullable();
            $table->string('PropertySubType')->nullable();
            $table->text('PublicAddress')->nullable();
            $table->string('PublicAddressYN')->nullable();
            $table->longText('PublicRemarks')->nullable();
            $table->string('ListAgentMLSID')->nullable();
            $table->string('ListAgentFullName')->nullable();
            $table->string('ListOfficeName')->nullable();
            $table->text('ListAgentDirectWorkPhone')->nullable();
            $table->string('RealtorYN')->nullable();
            $table->string('RefrigeratorYN')->nullable();
            $table->string('Spa')->nullable();
            $table->text('SpaDescription')->nullable();
            $table->string('YearRoundSchoolYN')->nullable();
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
        Schema::dropIfExists('property_additionals');
    }
}
