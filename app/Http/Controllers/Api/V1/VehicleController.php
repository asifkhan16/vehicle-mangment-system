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

        $vehical = Vehicle::where('vin',$request->vin)->first();
        if($vehical){
            return SuccessResponse($vehical);
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

            $vehical_data = json_decode($response->getBody());

            if(!$vehical_data->success){
                return $vehical_data->error;
            }

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

            $vehical_images = [];

            foreach ($vehical_data->photos as $image) {
                array_push($vehical_images,[
                    'vehicle_id' => $vehical->id,
                    'image_url' => $image->url,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                ]);
            }

            VehicleImage::insert($vehical_images);
            DB::commit();

            return SuccessResponse($vehical);

        } catch (\Throwable $th) {
            DB::rollBack();
            Log::error('get_vehicle_specifications Error : '. $th->getMessage());
            return ErrorResponse('Operation failed.');
        }
        
    }

    public function get_vehicle_market_valuation(Request $request){
        $validator = Validator::make($request->all(),[
            'id' => 'required|integer|min:1'
        ]);

        if($validator->fails()){
            return ErrorResponse($validator->errors()->first());
        }

        

        try {
            
        } catch (\Throwable $th) {
            Log::error('get_vehicle_market_valuation Error : '.$th->getMessage());
            return ErrorResponse('Operation Failed.');
        }


    }
}
