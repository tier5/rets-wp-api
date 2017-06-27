<?php

namespace App\Http\Controllers;

use App\City;
use App\PropertyAdditional;
use App\PropertyDetails;
use App\PropertyExternalFeature;
use App\PropertyFeature;
use App\PropertyFinancialDetails;
use App\PropertyImage;
use App\PropertyInteriorFeature;
use App\PropertyLocation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use \PHRETS\Configuration;

class SearchController extends Controller
{
    public function __construct()
    {
        ini_set('max_execution_time', 30000000);
        set_time_limit(0);
        ini_set('memory_limit', '2048M');
    }
    public function search()
    {
        $city = 'MOAPA';
        try{
            $config = Configuration::load([
                'login_url' => env('RETS_LOGIN_URL'),
                'username' => env('RETS_USERNAME'),
                'password' => env('RETS_PASSWORD'),
                'rets_version' => '1.7.2',
            ]);
            $rets = new \PHRETS\Session($config);
            $bulletin = $rets->Login();
            if($bulletin){
                $query = "(City=\"$city\")";
                $search = $rets->Search("Property","Listing",$query,array("StandardNames" => 0));
                $result_count = $search->getTotalResultsCount();
                $cityList = City::where('name',$city)->first();
                $cityList->total = $result_count;
                $cityList->status = 1;
                $cityList->update();
                foreach ($search as $key=>$value){
                    try{
                        //Get Property Details
                        $propertyDeatils = PropertyDetails::where('Matrix_Unique_ID',$value['Matrix_Unique_ID'])->first();
                        if($propertyDeatils == null){
                            $propertyDeatils = new PropertyDetails();
                            $propertyDeatils->cityId = $cityList->id;
                            $propertyDeatils->Matrix_Unique_ID = $value['Matrix_Unique_ID'];
                            $propertyDeatils->ListPrice = $value['ListPrice'];
                            $propertyDeatils->Status = $value['Status'];
                            $propertyDeatils->BedroomsTotalPossibleNum = (isset($value['BedroomsTotalPossibleNum']) && $value['BedroomsTotalPossibleNum'] != '')?$value['BedroomsTotalPossibleNum']:0;
                            $propertyDeatils->BathsTotal = (isset($value['BathsTotal']) && $value['BathsTotal'] != '')?$value['BathsTotal']:0;
                            $propertyDeatils->BathsHalf = (isset($value['BathsHalf']) && $value['BathsHalf'] != '')?$value['BathsHalf']:0;
                            $propertyDeatils->BathsFull = (isset($value['BathsFull']) && $value['BathsFull'] != '')?$value['BathsFull']:0;
                            $propertyDeatils->NumAcres = (isset($value['NumAcres']) && $value['NumAcres'] != '')?$value['NumAcres']:0;
                            $propertyDeatils->SqFtTotal = (isset($value['SqFtTotal']) && $value['SqFtTotal'] != '')?$value['SqFtTotal']:0;
                            $propertyDeatils->StreetNumber = $value['StreetNumber'];
                            $propertyDeatils->StreetName = $value['StreetName'];
                            $propertyDeatils->City = $city;
                            $propertyDeatils->MLSNumber = $value['MLSNumber'];
                            $propertyDeatils->PostalCode = $value['PostalCode'];
                            $propertyDeatils->PhotoCount = $value['PhotoCount'];
                            $propertyDeatils->PublicAddress = $value['PublicAddress'];
                            $propertyDeatils->VirtualTourLink = $value['VirtualTourLink'];
                            $propertyDeatils->OriginalEntryTimestamp = $value['OriginalEntryTimestamp'];
                            $propertyDeatils->save();
                            $previous = $cityList->inserted;
                            $cityList->inserted = $previous+1;
                            $cityList->update();
                        } else {
                            $propertyDeatils->cityId = $cityList->id;
                            $propertyDeatils->ListPrice = $value['ListPrice'];
                            $propertyDeatils->Status = $value['Status'];
                            $propertyDeatils->BedroomsTotalPossibleNum = (isset($value['BedroomsTotalPossibleNum']) && $value['BedroomsTotalPossibleNum'] != '')?$value['BedroomsTotalPossibleNum']:0;
                            $propertyDeatils->BathsTotal = (isset($value['BathsTotal']) && $value['BathsTotal'] != '')?$value['BathsTotal']:0;
                            $propertyDeatils->BathsHalf = (isset($value['BathsHalf']) && $value['BathsHalf'] != '')?$value['BathsHalf']:0;
                            $propertyDeatils->BathsFull = (isset($value['BathsFull']) && $value['BathsFull'] != '')?$value['BathsFull']:0;
                            $propertyDeatils->NumAcres = (isset($value['NumAcres']) && $value['NumAcres'] != '')?$value['NumAcres']:0;
                            $propertyDeatils->SqFtTotal = (isset($value['SqFtTotal']) && $value['SqFtTotal'] != '')?$value['SqFtTotal']:0;
                            $propertyDeatils->StreetNumber = $value['StreetNumber'];
                            $propertyDeatils->StreetName = $value['StreetName'];
                            $propertyDeatils->City = $city;
                            $propertyDeatils->MLSNumber = $value['MLSNumber'];
                            $propertyDeatils->PostalCode = $value['PostalCode'];
                            $propertyDeatils->PhotoCount = $value['PhotoCount'];
                            $propertyDeatils->PublicAddress = $value['PublicAddress'];
                            $propertyDeatils->VirtualTourLink = $value['VirtualTourLink'];
                            $propertyDeatils->OriginalEntryTimestamp = $value['OriginalEntryTimestamp'];
                            $propertyDeatils->update();
                        }
                        //Get Photo For Listing
                        $photos = $rets->GetObject("Property", "LargePhoto", $value['Matrix_Unique_ID'], "*", 0);
                        foreach ($photos as $keyImage => $photo) {
                            try{
                                if(!$photo->isError()){
                                    $photoSearch = PropertyImage::where('Matrix_Unique_ID',$value['Matrix_Unique_ID'])
                                        ->where('MLSNumber',$value['MLSNumber'])
                                        ->where('ContentId',$photo->getContentId())
                                        ->where('ObjectId',$photo->getObjectId())->first();
                                    if($photoSearch == null){
                                        $propertyImage = new PropertyImage();
                                        $propertyImage->property_details_id = $propertyDeatils->id;
                                        $propertyImage->Matrix_Unique_ID = $value['Matrix_Unique_ID'];
                                        $propertyImage->MLSNumber = $value['MLSNumber'];
                                        $propertyImage->ContentId = $photo->getContentId();
                                        $propertyImage->ObjectId = $photo->getObjectId();
                                        $propertyImage->Success = 1;
                                        $propertyImage->ContentType = $photo->getContentType();
                                        $propertyImage->Encoded_image = base64_encode($photo->getContent());
                                        $propertyImage->ContentDesc = $photo->getContentDescription();
                                        $propertyImage->save();
                                    }
                                } else {
                                    Log::info('Photo Error !! '.$photo->getError());
                                }
                            } catch (\Exception $errPhoto) {
                                Log::info('Photo Error Catch !! '.$errPhoto->getMessage());
                            }
                        }
                        // Property feature
                        $is_property_feature = PropertyFeature::where('property_details_id', $propertyDeatils->id)->first();
                        if ($is_property_feature) {
                            $is_property_feature->property_details_id = $propertyDeatils->id;
                            $is_property_feature->YearBuilt = (isset($value['YearBuilt']) && $value['YearBuilt'] != '')?$value['YearBuilt']:0;
                            $is_property_feature->PropertyType = $value['PropertyType'];
                            $is_property_feature->PropertySubType = $value['PropertySubType'];
                            $is_property_feature->CountyOrParish = $value['CountyOrParish'];
                            $is_property_feature->Zoning = $value['Zoning'];
                            $is_property_feature->MLSNumber = $value['MLSNumber'];
                            $is_property_feature->save();
                        } else {
                            $propertyfeature = new PropertyFeature();
                            $propertyfeature->property_details_id = $propertyDeatils->id;
                            $propertyfeature->Matrix_Unique_ID = $value['Matrix_Unique_ID'];
                            $propertyfeature->YearBuilt = (isset($value['YearBuilt']) && $value['YearBuilt'] != '')?$value['YearBuilt']:0;
                            $propertyfeature->PropertyType = $value['PropertyType'];
                            $propertyfeature->PropertySubType = $value['PropertySubType'];
                            $propertyfeature->CountyOrParish = $value['CountyOrParish'];
                            $propertyfeature->Zoning = $value['Zoning'];
                            $propertyfeature->MLSNumber = $value['MLSNumber'];
                            $propertyfeature->save();
                        }
                        // Property External Feature
                        $is_property_external_feature = PropertyExternalFeature::where('property_details_id', '=', $propertyDeatils->id)->first();
                        if ($is_property_external_feature) {
                            $is_property_external_feature->property_details_id = $propertyDeatils->id;
                            $is_property_external_feature->MLSNumber = $value['MLSNumber'];
                            $is_property_external_feature->BuildingDescription = $value['BuildingDescription'];
                            $is_property_external_feature->BuiltDescription = $value['BuiltDescription'];
                            $is_property_external_feature->ConstructionDescription = $value['ConstructionDescription'];
                            $is_property_external_feature->ConvertedGarageYN = (isset($value['ConvertedGarageYN']) && $value['ConvertedGarageYN'] != '')?$value['ConvertedGarageYN']:0;
                            $is_property_external_feature->EquestrianDescription = $value['EquestrianDescription'];
                            $is_property_external_feature->Fence = $value['Fence'];
                            $is_property_external_feature->FenceType = $value['FenceType'];
                            $is_property_external_feature->Garage = (isset($value['Garage']) && $value['Garage'] != '')?$value['Garage']:0;
                            $is_property_external_feature->GarageDescription = $value['GarageDescription'];
                            $is_property_external_feature->HouseViews = $value['HouseViews'];
                            $is_property_external_feature->LandscapeDescription = $value['LandscapeDescription'];
                            $is_property_external_feature->LotDescription = $value['LotDescription'];
                            $is_property_external_feature->LotSqft = (isset($value['LotSqft']) && $value['LotSqft'] != '')?$value['LotSqft']:0;
                            $is_property_external_feature->ParkingDescription = $value['ParkingDescription'];
                            $is_property_external_feature->PoolDescription = $value['PoolDescription'];
                            $is_property_external_feature->PvPool = (isset($value['PvPool']) && $value['PvPool'] != '')?$value['PvPool']:0;
                            $is_property_external_feature->RoofDescription = $value['RoofDescription'];
                            $is_property_external_feature->Sewer = $value['Sewer'];
                            $is_property_external_feature->SolarElectric = $value['SolarElectric'];
                            $is_property_external_feature->Type = $value['Type'];
                            $is_property_external_feature->BuiltDescription = $value['BuiltDescription'];
                            $is_property_external_feature->ParkingDescription = $value['ParkingDescription'];
                            $is_property_external_feature->ParkingDescription = $value['ParkingDescription'];
                            $is_property_external_feature->ParkingDescription = $value['ParkingDescription'];
                            $is_property_external_feature->ParkingDescription = $value['ParkingDescription'];
                            $is_property_external_feature->update();
                        } else {
                            $propertyexternalfeature = new PropertyExternalFeature();
                            $propertyexternalfeature->property_details_id = $propertyDeatils->id;
                            $propertyexternalfeature->Matrix_Unique_ID = $value['Matrix_Unique_ID'];
                            $propertyexternalfeature->MLSNumber = $value['MLSNumber'];
                            $propertyexternalfeature->BuildingDescription = $value['BuildingDescription'];
                            $propertyexternalfeature->BuiltDescription = $value['BuiltDescription'];
                            $propertyexternalfeature->ConstructionDescription = $value['ConstructionDescription'];
                            $propertyexternalfeature->ConvertedGarageYN = (isset($value['ConvertedGarageYN']) && $value['ConvertedGarageYN'] != '')?$value['ConvertedGarageYN']:0;
                            $propertyexternalfeature->EquestrianDescription = $value['EquestrianDescription'];
                            $propertyexternalfeature->Fence = $value['Fence'];
                            $propertyexternalfeature->FenceType = $value['FenceType'];
                            $propertyexternalfeature->Garage = (isset($value['Garage']) && $value['Garage'] != '')?$value['Garage']:0;
                            $propertyexternalfeature->GarageDescription = $value['GarageDescription'];
                            $propertyexternalfeature->HouseViews = $value['HouseViews'];
                            $propertyexternalfeature->LandscapeDescription = $value['LandscapeDescription'];
                            $propertyexternalfeature->LotDescription = $value['LotDescription'];
                            $propertyexternalfeature->LotSqft = (isset($value['LotSqft']) && $value['LotSqft'] != '')?$value['LotSqft']:0;
                            $propertyexternalfeature->ParkingDescription = $value['ParkingDescription'];
                            $propertyexternalfeature->PoolDescription = $value['PoolDescription'];
                            $propertyexternalfeature->PvPool = (isset($value['PvPool']) && $value['PvPool'] != '')?$value['PvPool']:0;
                            $propertyexternalfeature->RoofDescription = $value['RoofDescription'];
                            $propertyexternalfeature->Sewer = $value['Sewer'];
                            $propertyexternalfeature->SolarElectric = $value['SolarElectric'];
                            $propertyexternalfeature->Type = $value['Type'];
                            $propertyexternalfeature->BuiltDescription = $value['BuiltDescription'];
                            $propertyexternalfeature->ParkingDescription = $value['ParkingDescription'];
                            $propertyexternalfeature->ParkingDescription = $value['ParkingDescription'];
                            $propertyexternalfeature->ParkingDescription = $value['ParkingDescription'];
                            $propertyexternalfeature->ParkingDescription = $value['ParkingDescription'];
                            $propertyexternalfeature->save();
                        }
                        // Property Additional
                        $is_property_additional = PropertyAdditional::where('property_details_id', $propertyDeatils->id)->first();
                        if ($is_property_additional) {
                            $is_property_additional->property_details_id = $propertyDeatils->id;
                            $is_property_additional->MLSNumber = $value['MLSNumber'];
                            $is_property_additional->AgeRestrictedCommunityYN = (isset($value['AgeRestrictedCommunityYN']) && $value['AgeRestrictedCommunityYN'] != '')?$value['AgeRestrictedCommunityYN']:0;
                            $is_property_additional->Assessments = (isset($value['Assessments']) && $value['Assessments'] != '')?$value['Assessments']:0;
                            $is_property_additional->AssociationFeaturesAvailable = $value['AssociationFeaturesAvailable'];
                            $is_property_additional->AssociationFeeIncludes = $value['AssociationFeeIncludes'];
                            $is_property_additional->AssociationName = $value['AssociationName'];
                            $is_property_additional->Builder = $value['Builder'];
                            $is_property_additional->CensusTract = $value['CensusTract'];
                            $is_property_additional->CourtApproval = (isset($value['CourtApproval']) && $value['CourtApproval'] != '')?$value['CourtApproval']:0;
                            $is_property_additional->GatedYN = (isset($value['GatedYN']) && $value['GatedYN'] != '')?$value['GatedYN']:0;
                            $is_property_additional->GreenBuildingCertificationYN = (isset($value['GreenBuildingCertificationYN']) && $value['GreenBuildingCertificationYN'] != '')?$value['GreenBuildingCertificationYN']:0;
                            $is_property_additional->BathsHalf = (isset($value['BathsHalf']) && $value['BathsHalf'] != '')?$value['BathsHalf']:0;
                            $is_property_additional->ListingAgreementType = $value['ListingAgreementType'];
                            $is_property_additional->Litigation = $value['Litigation'];
                            $is_property_additional->MasterPlanFeeMQYN = $value['MasterPlanFeeMQYN'];
                            $is_property_additional->MiscellaneousDescription = $value['MiscellaneousDescription'];
                            $is_property_additional->Model = $value['Model'];
                            $is_property_additional->OwnerLicensee = $value['OwnerLicensee'];
                            $is_property_additional->Ownership = $value['Ownership'];
                            $is_property_additional->PoweronorOff = $value['PoweronorOff'];
                            $is_property_additional->PropertyDescription = $value['PropertyDescription'];
                            $is_property_additional->PropertySubType = $value['PropertySubType'];
                            $is_property_additional->PublicAddress = $value['PublicAddress'];
                            $is_property_additional->PublicAddressYN = $value['PublicAddressYN'];
                            $is_property_additional->PublicRemarks = $value['PublicRemarks'];
                            $is_property_additional->ListAgentMLSID = $value['ListAgentMLSID'];
                            $is_property_additional->ListAgentFullName = $value['ListAgentFullName'];
                            $is_property_additional->ListOfficeName = $value['ListOfficeName'];
                            $is_property_additional->ListAgentDirectWorkPhone = $value['ListAgentDirectWorkPhone'];
                            $is_property_additional->RealtorYN = (isset($value['RealtorYN']) && $value['RealtorYN'] != '')?$value['RealtorYN']:0;
                            $is_property_additional->RefrigeratorYN = (isset($value['RefrigeratorYN']) && $value['RefrigeratorYN'] != '')?$value['RefrigeratorYN']:0;
                            $is_property_additional->Spa = $value['Spa'];
                            $is_property_additional->SpaDescription = $value['SpaDescription'];
                            $is_property_additional->YearRoundSchoolYN = (isset($value['YearRoundSchoolYN']) && $value['YearRoundSchoolYN'] != '')?$value['YearRoundSchoolYN']:0 ;
                            $is_property_additional->update();
                        } else {
                            $propertyadditional = new PropertyAdditional();
                            $propertyadditional->Matrix_Unique_ID = $value['Matrix_Unique_ID'];
                            $propertyadditional->property_details_id = $propertyDeatils->id;
                            $propertyadditional->MLSNumber = $value['MLSNumber'];
                            $propertyadditional->AgeRestrictedCommunityYN = (isset($value['AgeRestrictedCommunityYN']) && $value['AgeRestrictedCommunityYN'] != '')?$value['AgeRestrictedCommunityYN']:0;
                            $propertyadditional->Assessments = (isset($value['Assessments']) && $value['Assessments'] != '')?$value['Assessments']:0;
                            $propertyadditional->AssociationFeaturesAvailable = $value['AssociationFeaturesAvailable'];
                            $propertyadditional->AssociationFeeIncludes = $value['AssociationFeeIncludes'];
                            $propertyadditional->AssociationName = $value['AssociationName'];
                            $propertyadditional->Builder = $value['Builder'];
                            $propertyadditional->CensusTract = $value['CensusTract'];
                            $propertyadditional->CourtApproval = (isset($value['CourtApproval']) && $value['CourtApproval'] != '')?$value['CourtApproval']:0;
                            $propertyadditional->GatedYN = (isset($value['GatedYN']) && $value['GatedYN'] != '')?$value['GatedYN']:0;
                            $propertyadditional->GreenBuildingCertificationYN = (isset($value['GreenBuildingCertificationYN']) && $value['GreenBuildingCertificationYN'] != '')?$value['GreenBuildingCertificationYN']:0;
                            $propertyadditional->BathsHalf = (isset($value['BathsHalf']) && $value['BathsHalf'] != '')?$value['BathsHalf']:0;;
                            $propertyadditional->ListingAgreementType = $value['ListingAgreementType'];
                            $propertyadditional->Litigation = $value['Litigation'];
                            $propertyadditional->MasterPlanFeeMQYN = $value['MasterPlanFeeMQYN'];
                            $propertyadditional->MiscellaneousDescription = $value['MiscellaneousDescription'];
                            $propertyadditional->Model = $value['Model'];
                            $propertyadditional->OwnerLicensee = $value['OwnerLicensee'];
                            $propertyadditional->Ownership = $value['Ownership'];
                            $propertyadditional->PoweronorOff = $value['PoweronorOff'];
                            $propertyadditional->PropertyDescription = $value['PropertyDescription'];
                            $propertyadditional->PropertySubType = $value['PropertySubType'];
                            $propertyadditional->PublicAddress = $value['PublicAddress'];
                            $propertyadditional->PublicAddressYN = $value['PublicAddressYN'];
                            $propertyadditional->PublicRemarks = $value['PublicRemarks'];
                            $propertyadditional->ListAgentMLSID = $value['ListAgentMLSID'];
                            $propertyadditional->ListAgentFullName = $value['ListAgentFullName'];
                            $propertyadditional->ListOfficeName = $value['ListOfficeName'];
                            $propertyadditional->ListAgentDirectWorkPhone = $value['ListAgentDirectWorkPhone'];
                            $propertyadditional->RealtorYN = (isset($value['RealtorYN']) && $value['RealtorYN'] != '')?$value['RealtorYN']:0;
                            $propertyadditional->RefrigeratorYN = (isset($value['RefrigeratorYN']) && $value['RefrigeratorYN'] != '')?$value['RefrigeratorYN']:0;
                            $propertyadditional->Spa = $value['Spa'];
                            $propertyadditional->SpaDescription = $value['SpaDescription'];
                            $propertyadditional->YearRoundSchoolYN = (isset($value['YearRoundSchoolYN']) && $value['YearRoundSchoolYN'] != '')?$value['YearRoundSchoolYN']:0 ;
                            $propertyadditional->save();
                        }
                        //Property Financial Details
                        $propertyfinancialdetail = PropertyFinancialDetails::where('property_details_id', $propertyDeatils->id)->first();
                        if ($propertyfinancialdetail) {
                            $propertyfinancialdetail->property_details_id = $propertyDeatils->id;
                            $propertyfinancialdetail->MLSNumber = $value['MLSNumber'];
                            $propertyfinancialdetail->AnnualPropertyTaxes = (isset($value['AnnualPropertyTaxes']) && $value['AnnualPropertyTaxes'] != '')?$value['AnnualPropertyTaxes']:0;
                            $propertyfinancialdetail->AppxAssociationFee = (isset($value['AppxAssociationFee']) && $value['AppxAssociationFee'] != '')?$value['AppxAssociationFee']:0;
                            $propertyfinancialdetail->AssociationFee1 = (isset($value['AssociationFee1']) && $value['AssociationFee1'] != '')?$value['AssociationFee1']:0;
                            $propertyfinancialdetail->AssociationFee1MQYN = $value['AssociationFee1MQYN'];
                            $propertyfinancialdetail->AVMYN = (isset($value['AVMYN']) && $value['AVMYN'] != '')?$value['AVMYN']:0;
                            $propertyfinancialdetail->CurrentPrice = (isset($value['CurrentPrice']) && $value['CurrentPrice'] != '')?$value['CurrentPrice']:0;
                            $propertyfinancialdetail->EarnestDeposit = (isset($value['EarnestDeposit']) && $value['EarnestDeposit'] != '')?$value['EarnestDeposit']:0;
                            $propertyfinancialdetail->FinancingConsidered = $value['FinancingConsidered'];
                            $propertyfinancialdetail->ForeclosureCommencedYN = (isset($value['ForeclosureCommencedYN']) && $value['ForeclosureCommencedYN'] != '')?$value['ForeclosureCommencedYN']:0;
                            $propertyfinancialdetail->MasterPlanFeeAmount = (isset($value['MasterPlanFeeAmount']) && $value['MasterPlanFeeAmount'] != '')?$value['MasterPlanFeeAmount']:0;
                            $propertyfinancialdetail->RATIO_CurrentPrice_By_SQFT = (isset($value['RATIO_CurrentPrice_By_SQFT']) && $value['RATIO_CurrentPrice_By_SQFT'] != '')?$value['RATIO_CurrentPrice_By_SQFT']:0;
                            $propertyfinancialdetail->RepoReoYN = (isset($value['RepoReoYN']) && $value['RepoReoYN'] != '')?$value['RepoReoYN']:0;
                            $propertyfinancialdetail->ShortSale = (isset($value['ShortSale']) && $value['ShortSale'] != '')?$value['ShortSale']:0;
                            $propertyfinancialdetail->SIDLIDYN = (isset($value['SIDLIDYN']) && $value['SIDLIDYN'] != '')?$value['SIDLIDYN']:0;
                            $propertyfinancialdetail->save();
                        } else {
                            $propertyfinancialdetail = new PropertyFinancialDetails();
                            $propertyfinancialdetail->property_details_id = $propertyDeatils->id;
                            $propertyfinancialdetail->Matrix_Unique_ID = $value['Matrix_Unique_ID'];
                            $propertyfinancialdetail->MLSNumber = $value['MLSNumber'];
                            $propertyfinancialdetail->AnnualPropertyTaxes = (isset($value['AnnualPropertyTaxes']) && $value['AnnualPropertyTaxes'] != '')?$value['AnnualPropertyTaxes']:0;
                            $propertyfinancialdetail->AppxAssociationFee = (isset($value['AppxAssociationFee']) && $value['AppxAssociationFee'] != '')?$value['AppxAssociationFee']:0;
                            $propertyfinancialdetail->AssociationFee1 = (isset($value['AssociationFee1']) && $value['AssociationFee1'] != '')?$value['AssociationFee1']:0;
                            $propertyfinancialdetail->AssociationFee1MQYN = $value['AssociationFee1MQYN'];
                            $propertyfinancialdetail->AVMYN = (isset($value['AVMYN']) && $value['AVMYN'] != '')?$value['AVMYN']:0;
                            $propertyfinancialdetail->CurrentPrice = (isset($value['CurrentPrice']) && $value['CurrentPrice'] != '')?$value['CurrentPrice']:0;
                            $propertyfinancialdetail->EarnestDeposit = (isset($value['EarnestDeposit']) && $value['EarnestDeposit'] != '')?$value['EarnestDeposit']:0;
                            $propertyfinancialdetail->FinancingConsidered = $value['FinancingConsidered'];
                            $propertyfinancialdetail->ForeclosureCommencedYN = (isset($value['ForeclosureCommencedYN']) && $value['ForeclosureCommencedYN'] != '')?$value['ForeclosureCommencedYN']:0;
                            $propertyfinancialdetail->MasterPlanFeeAmount = (isset($value['MasterPlanFeeAmount']) && $value['MasterPlanFeeAmount'] != '')?$value['MasterPlanFeeAmount']:0;
                            $propertyfinancialdetail->RATIO_CurrentPrice_By_SQFT = (isset($value['RATIO_CurrentPrice_By_SQFT']) && $value['RATIO_CurrentPrice_By_SQFT'] != '')?$value['RATIO_CurrentPrice_By_SQFT']:0;
                            $propertyfinancialdetail->RepoReoYN = (isset($value['RepoReoYN']) && $value['RepoReoYN'] != '')?$value['RepoReoYN']:0;
                            $propertyfinancialdetail->ShortSale = (isset($value['ShortSale']) && $value['ShortSale'] != '')?$value['ShortSale']:0;
                            $propertyfinancialdetail->SIDLIDYN = (isset($value['SIDLIDYN']) && $value['SIDLIDYN'] != '')?$value['SIDLIDYN']:0;
                            $propertyfinancialdetail->save();
                        }
                        // Property Interior Feature
                        $propertyInteriorFeature = PropertyInteriorFeature::where('property_details_id', $propertyDeatils->id)->first();
                        if ($propertyInteriorFeature) {
                            $propertyInteriorFeature->property_details_id = $propertyDeatils->id;
                            $propertyInteriorFeature->MLSNumber = $value['MLSNumber'];
                            $propertyInteriorFeature->ApproxTotalLivArea = (isset($value['ApproxTotalLivArea']) && $value['ApproxTotalLivArea'] != '')?$value['ApproxTotalLivArea']:0;
                            $propertyInteriorFeature->BathDownstairsDescription = $value['BathDownstairsDescription'];
                            $propertyInteriorFeature->BathDownYN = (isset($value['BathDownYN']) && $value['BathDownYN'] != '')?$value['BathDownYN']:0;
                            $propertyInteriorFeature->BedroomDownstairsYN = (isset($value['BedroomDownstairsYN']) && $value['BedroomDownstairsYN'] != '')?$value['BedroomDownstairsYN']:0;
                            $propertyInteriorFeature->BedroomsTotalPossibleNum = (isset($value['BedroomsTotalPossibleNum']) && $value['BedroomsTotalPossibleNum'] != '')?$value['BedroomsTotalPossibleNum']:0;
                            $propertyInteriorFeature->CoolingDescription = $value['CoolingDescription'];
                            $propertyInteriorFeature->CoolingFuel = $value['CoolingFuel'];
                            $propertyInteriorFeature->DishwasherYN = (isset($value['DishwasherYN']) && $value['DishwasherYN'] != '')?$value['DishwasherYN']:0;
                            $propertyInteriorFeature->DisposalYN = (isset($value['DisposalYN']) && $value['DisposalYN'] != '')?$value['DisposalYN']:0;
                            $propertyInteriorFeature->DryerIncluded = (isset($value['DryerIncluded']) && $value['DryerIncluded'] != '')?$value['DryerIncluded']:0;
                            $propertyInteriorFeature->DryerUtilities = $value['DryerUtilities'];
                            $propertyInteriorFeature->EnergyDescription = $value['EnergyDescription'];
                            $propertyInteriorFeature->FireplaceDescription = $value['FireplaceDescription'];
                            $propertyInteriorFeature->FireplaceLocation = $value['FireplaceLocation'];
                            $propertyInteriorFeature->Fireplaces = (isset($value['Fireplaces']) && $value['Fireplaces'] != '')?$value['Fireplaces']:0;
                            $propertyInteriorFeature->FlooringDescription = $value['FlooringDescription'];
                            $propertyInteriorFeature->FurnishingsDescription = $value['FurnishingsDescription'];
                            $propertyInteriorFeature->HeatingDescription = $value['HeatingDescription'];
                            $propertyInteriorFeature->HeatingFuel = $value['HeatingFuel'];
                            $propertyInteriorFeature->Interior = $value['Interior'];
                            $propertyInteriorFeature->NumDenOther = (isset($value['NumDenOther']) && $value['NumDenOther'] != '')?$value['NumDenOther']:0;
                            $propertyInteriorFeature->OtherApplianceDescription = $value['OtherApplianceDescription'];
                            $propertyInteriorFeature->OvenDescription = $value['OvenDescription'];
                            $propertyInteriorFeature->RoomCount = (isset($value['RoomCount']) && $value['RoomCount'] != '')?$value['RoomCount']:0;
                            $propertyInteriorFeature->ThreeQtrBaths = (isset($value['ThreeQtrBaths']) && $value['ThreeQtrBaths'] != '')?$value['ThreeQtrBaths']:0;
                            $propertyInteriorFeature->UtilityInformation = $value['UtilityInformation'];
                            $propertyInteriorFeature->WasherIncluded = (isset($value['WasherIncluded']) && $value['WasherIncluded'] != '')?$value['WasherIncluded']:0;
                            $propertyInteriorFeature->WasherDryerLocation = $value['WasherDryerLocation'];
                            $propertyInteriorFeature->Water = $value['Water'];
                            $propertyInteriorFeature->update();
                        } else {
                            $propertyInteriorFeature = new PropertyInteriorFeature();
                            $propertyInteriorFeature->property_details_id = $propertyDeatils->id;
                            $propertyInteriorFeature->Matrix_Unique_ID = $value['Matrix_Unique_ID'];
                            $propertyInteriorFeature->MLSNumber = $value['MLSNumber'];
                            $propertyInteriorFeature->ApproxTotalLivArea = (isset($value['ApproxTotalLivArea']) && $value['ApproxTotalLivArea'] != '')?$value['ApproxTotalLivArea']:0;
                            $propertyInteriorFeature->BathDownstairsDescription = $value['BathDownstairsDescription'];
                            $propertyInteriorFeature->BathDownYN = (isset($value['BathDownYN']) && $value['BathDownYN'] != '')?$value['BathDownYN']:0;
                            $propertyInteriorFeature->BedroomDownstairsYN = (isset($value['BedroomDownstairsYN']) && $value['BedroomDownstairsYN'] != '')?$value['BedroomDownstairsYN']:0;
                            $propertyInteriorFeature->BedroomsTotalPossibleNum = (isset($value['BedroomsTotalPossibleNum']) && $value['BedroomsTotalPossibleNum'] != '')?$value['BedroomsTotalPossibleNum']:0;
                            $propertyInteriorFeature->CoolingDescription = $value['CoolingDescription'];
                            $propertyInteriorFeature->CoolingFuel = $value['CoolingFuel'];
                            $propertyInteriorFeature->DishwasherYN = (isset($value['DishwasherYN']) && $value['DishwasherYN'] != '')?$value['DishwasherYN']:0;
                            $propertyInteriorFeature->DisposalYN = (isset($value['DisposalYN']) && $value['DisposalYN'] != '')?$value['DisposalYN']:0;
                            $propertyInteriorFeature->DryerIncluded = (isset($value['DryerIncluded']) && $value['DryerIncluded'] != '')?$value['DryerIncluded']:0;
                            $propertyInteriorFeature->DryerUtilities = $value['DryerUtilities'];
                            $propertyInteriorFeature->EnergyDescription = $value['EnergyDescription'];
                            $propertyInteriorFeature->FireplaceDescription = $value['FireplaceDescription'];
                            $propertyInteriorFeature->FireplaceLocation = $value['FireplaceLocation'];
                            $propertyInteriorFeature->Fireplaces = (isset($value['Fireplaces']) && $value['Fireplaces'] != '')?$value['Fireplaces']:0;
                            $propertyInteriorFeature->FlooringDescription = $value['FlooringDescription'];
                            $propertyInteriorFeature->FurnishingsDescription = $value['FurnishingsDescription'];
                            $propertyInteriorFeature->HeatingDescription = $value['HeatingDescription'];
                            $propertyInteriorFeature->HeatingFuel = $value['HeatingFuel'];
                            $propertyInteriorFeature->Interior = $value['Interior'];
                            $propertyInteriorFeature->NumDenOther = (isset($value['NumDenOther']) && $value['NumDenOther'] != '')?$value['NumDenOther']:0;
                            $propertyInteriorFeature->OtherApplianceDescription = $value['OtherApplianceDescription'];
                            $propertyInteriorFeature->OvenDescription = $value['OvenDescription'];
                            $propertyInteriorFeature->RoomCount = (isset($value['RoomCount']) && $value['RoomCount'] != '')?$value['RoomCount']:0;
                            $propertyInteriorFeature->ThreeQtrBaths = (isset($value['ThreeQtrBaths']) && $value['ThreeQtrBaths'] != '')?$value['ThreeQtrBaths']:0;
                            $propertyInteriorFeature->UtilityInformation = $value['UtilityInformation'];
                            $propertyInteriorFeature->WasherIncluded = (isset($value['WasherIncluded']) && $value['WasherIncluded'] != '')?$value['WasherIncluded']:0;
                            $propertyInteriorFeature->WasherDryerLocation = $value['WasherDryerLocation'];
                            $propertyInteriorFeature->Water = $value['Water'];
                            $propertyInteriorFeature->save();
                        }
                        // Property Deatils
                        $propertylocation = PropertyLocation::where('property_details_id', '=', $propertyDeatils->id)->first();
                        if ($propertylocation) {
                            $propertylocation->property_details_id = $propertyDeatils->id;
                            $propertylocation->MLSNumber = $value['MLSNumber'];
                            $propertylocation->Area = $value['Area'];
                            $propertylocation->CommunityName = $value['CommunityName'];
                            $propertylocation->ElementarySchool35 = $value['ElementarySchool35'];
                            $propertylocation->ElementarySchoolK2 = $value['ElementarySchoolK2'];
                            $propertylocation->HighSchool = $value['HighSchool'];
                            $propertylocation->HouseFaces = $value['HouseFaces'];
                            $propertylocation->JrHighSchool = $value['JrHighSchool'];
                            $propertylocation->ParcelNumber = $value['ParcelNumber'];
                            $propertylocation->StreetNumberNumeric = (isset($value['StreetNumberNumeric']) && $value['StreetNumberNumeric'] != '')?$value['StreetNumberNumeric']:0;
                            $propertylocation->SubdivisionName = $value['SubdivisionName'];
                            $propertylocation->SubdivisionNumber = (isset($value['SubdivisionNumber']) && $value['SubdivisionNumber'] != '')?$value['SubdivisionNumber']:0;
                            $propertylocation->SubdivisionNumSearch = $value['SubdivisionNumSearch'];
                            $propertylocation->TaxDistrict = $value['TaxDistrict'];
                            $propertylocation->update();
                        } else {
                            $propertylocation = new PropertyLocation();
                            $propertylocation->Matrix_Unique_ID = $value['Matrix_Unique_ID'];
                            $propertylocation->property_details_id = $propertyDeatils->id;
                            $propertylocation->MLSNumber = $value['MLSNumber'];
                            $propertylocation->Area = $value['Area'];
                            $propertylocation->CommunityName = $value['CommunityName'];
                            $propertylocation->ElementarySchool35 = $value['ElementarySchool35'];
                            $propertylocation->ElementarySchoolK2 = $value['ElementarySchoolK2'];
                            $propertylocation->HighSchool = $value['HighSchool'];
                            $propertylocation->HouseFaces = $value['HouseFaces'];
                            $propertylocation->JrHighSchool = $value['JrHighSchool'];
                            $propertylocation->ParcelNumber = $value['ParcelNumber'];
                            $propertylocation->StreetNumberNumeric = (isset($value['StreetNumberNumeric']) && $value['StreetNumberNumeric'] != '')?$value['StreetNumberNumeric']:0;
                            $propertylocation->SubdivisionName = $value['SubdivisionName'];
                            $propertylocation->SubdivisionNumber = (isset($value['SubdivisionNumber']) && $value['SubdivisionNumber'] != '')?$value['SubdivisionNumber']:0;
                            $propertylocation->SubdivisionNumSearch = $value['SubdivisionNumSearch'];
                            $propertylocation->TaxDistrict = $value['TaxDistrict'];
                            $propertylocation->save();
                        }
                    } catch (\Exception $forError){
                        Log::info('foreach Error !! '.$forError->getMessage());
                    }
                }
            } else {
                dd('no');
            }
        } catch (\Exception $e){
            dd($e->getMessage());
        }
    }
}
