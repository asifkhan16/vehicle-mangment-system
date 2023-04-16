<?php

namespace App\Http\Controllers\Api\V1;

use GuzzleHttp\Client;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Vehicle;

class VehicleController extends Controller
{
    public function get_vehicle_specifications(Request $request){
        // return $request->vin;

        $vehical_data = Vehicle::where('vin',$request->vin)->first();
        if($vehical_data){
            return SuccessResponse($vehical_data);
        }

        $client = new Client(['base_uri' => 'https://specifications.vinaudit.com/v3/']);

        $response = $client->request('GET', 'specifications', [
            'query' => [
                'key' => env('VEHICLE_API_KEY'),
                'format' => 'json',
                'vin' => $request->vin,
                'include' => 'attributes,photos'
            ]
        ]);

        $vehical_data = json_decode($response->getBody());

        if(!$vehical_data->success){
            return $vehical_data->error;
        }

        return SuccessResponse($vehical_data);

        $vehical = Vehicle::create([
            'vin' => $request->vin,
            "year" => $vehical_data->attributes->year,
            "make" => $vehical_data->attributes->make,
            "model" => $vehical_data->attributes->model,
            "trim" => $vehical_data->attributes->trim,
            "style" => $vehical_data->attributes->style,
            "type" => $vehical_data->attributes->type,
            "size" => $vehical_data->attributes->size,
            "category" => $vehical_data->attributes->category,
            "made_in" => $vehical_data->attributes->made_in,
            "made_in_city" => $vehical_data->attributes->made_in_city,
            "doors" => $vehical_data->attributes->doors,
            "fuel_type" => $vehical_data->attributes->fuel_type,
            "fuel_capacity" => $vehical_data->attributes->fuel_capacity,
            "city_mileage" => $vehical_data->attributes->city_mileage,
            "highway_mileage" => $vehical_data->attributes->highway_mileage,
            "engine" => $vehical_data->attributes->engine,
            "engine_size" => $vehical_data->attributes->engine_size,
            "engine_cylinders" => $vehical_data->attributes->engine_cylinders,
            "transmission" => $vehical_data->attributes->transmission,
            "transmission_type" => $vehical_data->attributes->transmission_type,
            "transmission_speeds" => $vehical_data->attributes->transmission_speeds,
            "drivetrain" => $vehical_data->attributes->drivetrain,
            "anti_brake_system" => $vehical_data->attributes->anti_brake_system,
            "steering_type" => $vehical_data->attributes->steering_type,
            "curb_weight" => $vehical_data->attributes->curb_weight,
            "gross_vehicle_weight_rating" => $vehical_data->attributes->gross_vehicle_weight_rating,
            "overall_height" => $vehical_data->attributes->overall_height,
            "overall_length" => $vehical_data->attributes->overall_length,
            "overall_width" => $vehical_data->attributes->overall_width,
            "wheelbase_length" => $vehical_data->attributes->wheelbase_length,
            "standard_seating" => $vehical_data->attributes->standard_seating,
            "invoice_price" => $vehical_data->attributes->invoice_price,
            "delivery_charges" => $vehical_data->attributes->delivery_charges,
            "manufacturer_suggested_retail_price" => $vehical_data->attributes->manufacturer_suggested_retail_price
        ]);
        return $vehical_data;


        $result = $response->getBody();

        return $response->getBody();

        return response()->json($response->getBody());
        
        //https://specifications.vinaudit.com/v3/specifications?
        //key=0BY2VDKPO9RUND&format=xml&
        //include=attributes,equipment,colors,recalls,warranties,photos&vin=WBS8M9C52J5J78248
    }
}
