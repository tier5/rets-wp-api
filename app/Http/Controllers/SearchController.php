<?php

namespace App\Http\Controllers;

use App\City;
use App\PropertyDetails;
use App\PropertyImage;
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
