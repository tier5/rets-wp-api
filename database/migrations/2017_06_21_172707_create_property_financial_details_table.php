<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePropertyFinancialDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('property_financial_details', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('property_details_id')->unsigned()->index();
            $table->string('Matrix_Unique_ID')->index();
            $table->string('MLSNumber')->index();
            $table->string('AnnualPropertyTaxes')->nullable();
            $table->string('AppxAssociationFee')->nullable();
            $table->string('AssociationFee1')->nullable();
            $table->string('AssociationFee1MQYN')->nullable();
            $table->string('AVMYN')->nullable();
            $table->string('CurrentPrice')->nullable();
            $table->string('EarnestDeposit')->nullable();
            $table->text('FinancingConsidered')->nullable();
            $table->string('ForeclosureCommencedYN')->nullable();
            $table->string('MasterPlanFeeAmount')->nullable();
            $table->string('RATIO_CurrentPrice_By_SQFT')->nullable();
            $table->string('RepoReoYN')->nullable();
            $table->string('ShortSale')->nullable();
            $table->string('SIDLIDYN')->nullable();
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
        Schema::dropIfExists('property_financial_details');
    }
}
