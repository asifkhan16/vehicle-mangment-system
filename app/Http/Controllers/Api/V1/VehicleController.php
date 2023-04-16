<?php

namespace App\Http\Controllers\Api\V1;

use GuzzleHttp\Client;
use App\Models\Vehicle;
use App\Models\VehicleImage;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Models\VehicleMarketValuation;
use Illuminate\Support\Facades\Validator;

class VehicleController extends Controller
{
    public function get_vehicle_specifications(Request $request){
        $validator = Validator::make($request->all(),[
            'vin' => 'string|required'
        ]);

        if($validator->fails()){
            return ErrorResponse($validator->errors()->first());
        }

        $vehicle = Vehicle::where('vin',$request->vin)->first();
        if($vehicle){
            $data['vehicle'] = $vehicle;
            return SuccessResponse($data);
        }
        try {
            DB::beginTransaction();

            $client = new Client(['base_uri' => 'https://specifications.vinaudit.com/v3/']);

            $response = $client->request('GET', 'specifications', [
                'query' => [
                    'key' => env('VEHICLE_API_KEY'),
                    'format' => 'json',
                    'vin' => $request->vin,
                    'include' => 'attributes,photos'
                ]
            ]);

            $vehicle_data = json_decode($response->getBody());

            if(!$vehicle_data->success){
                return $vehicle_data->error;
            }

            $vehicle = Vehicle::create([
                'vin' => $request->vin,
                "year" => $vehicle_data->attributes->year,
                "make" => $vehicle_data->attributes->make,
                "model" => $vehicle_data->attributes->model,
                "trim" => $vehicle_data->attributes->trim,
                "style" => $vehicle_data->attributes->style,
                "type" => $vehicle_data->attributes->type,
                "size" => $vehicle_data->attributes->size,
                "category" => $vehicle_data->attributes->category,
                "made_in" => $vehicle_data->attributes->made_in,
                "made_in_city" => $vehicle_data->attributes->made_in_city,
                "doors" => $vehicle_data->attributes->doors,
                "fuel_type" => $vehicle_data->attributes->fuel_type,
                "fuel_capacity" => $vehicle_data->attributes->fuel_capacity,
                "city_mileage" => $vehicle_data->attributes->city_mileage,
                "highway_mileage" => $vehicle_data->attributes->highway_mileage,
                "engine" => $vehicle_data->attributes->engine,
                "engine_size" => $vehicle_data->attributes->engine_size,
                "engine_cylinders" => $vehicle_data->attributes->engine_cylinders,
                "transmission" => $vehicle_data->attributes->transmission,
                "transmission_type" => $vehicle_data->attributes->transmission_type,
                "transmission_speeds" => $vehicle_data->attributes->transmission_speeds,
                "drivetrain" => $vehicle_data->attributes->drivetrain,
                "anti_brake_system" => $vehicle_data->attributes->anti_brake_system,
                "steering_type" => $vehicle_data->attributes->steering_type,
                "curb_weight" => $vehicle_data->attributes->curb_weight,
                "gross_vehicle_weight_rating" => $vehicle_data->attributes->gross_vehicle_weight_rating,
                "overall_height" => $vehicle_data->attributes->overall_height,
                "overall_length" => $vehicle_data->attributes->overall_length,
                "overall_width" => $vehicle_data->attributes->overall_width,
                "wheelbase_length" => $vehicle_data->attributes->wheelbase_length,
                "standard_seating" => $vehicle_data->attributes->standard_seating,
                "invoice_price" => $vehicle_data->attributes->invoice_price,
                "delivery_charges" => $vehicle_data->attributes->delivery_charges,
                "manufacturer_suggested_retail_price" => $vehicle_data->attributes->manufacturer_suggested_retail_price
            ]);

            $vehicle_images = [];

            foreach ($vehicle_data->photos as $image) {
                array_push($vehicle_images,[
                    'vehicle_id' => $vehicle->id,
                    'image_url' => $image->url,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                ]);
            }

            VehicleImage::insert($vehicle_images);
            DB::commit();
            $data['vehicle'] = $vehicle;
            return SuccessResponse($data);

        } catch (\Throwable $th) {
            DB::rollBack();
            Log::error('get_vehicle_specifications Error : '. $th->getMessage());
            return ErrorResponse('Operation failed.');
        }
        
    }

    public function get_vehicle_market_valuation(Request $request){
        $validator = Validator::make($request->all(),[
            'vin' => 'string|required'
        ]);

        if($validator->fails()){
            return ErrorResponse($validator->errors()->first());
        }

        // $vehicle_market_valuation = VehicleMarketValuation::where('vehicle_id',$request->vehicle_id)->first();

        // if($vehicle_market_valuation){
        //     return SuccessResponse($vehicle_market_valuation);
        // }

        try {
            $client = new Client(['base_uri' => 'https://marketvalue.vinaudit.com/']);

            $response = $client->request('GET', 'getmarketvalue.php', [
                'query' => [
                    'key' => env('VEHICLE_API_KEY'),
                    'format' => 'json',
                    'vin' => $request->vin,
                ]
            ]);

            $vehicle_data = json_decode($response->getBody());

            return SuccessResponse($vehicle_data);
            
        } catch (\Throwable $th) {
            Log::error('get_vehicle_market_valuation Error : '.$th->getMessage());
            return ErrorResponse('Operation Failed.');
        }


    }

    public function get_vehicle_ownership_cost(Request $request){
        $validator = Validator::make($request->all(),[
            'vin' => 'string|required'
        ]);

        if($validator->fails()){
            return ErrorResponse($validator->errors()->first());
        }

        try {
            $client = new Client(['base_uri' => 'https://ownershipcost.vinaudit.com/']);

            $response = $client->request('GET', 'getownershipcost.php', [
                'query' => [
                    'key' => env('VEHICLE_API_KEY'),
                    'format' => 'json',
                    'vin' => $request->vin,
                    'state_code' => "WA"
                ]
            ]);

            $vehicle_data = json_decode($response->getBody());

            return SuccessResponse($vehicle_data);
            
        } catch (\Throwable $th) {
            Log::error('get_vehicle_ownership_cost Error : '.$th->getMessage());
            return ErrorResponse('Operation Failed.');
        }


    }
}
