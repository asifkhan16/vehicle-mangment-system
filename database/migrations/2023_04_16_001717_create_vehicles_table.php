<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('vehicles', function (Blueprint $table) {
            $table->id();
            $table->string('vin');
            $table->string('year')->nullable();
            $table->string('make')->nullable();
            $table->string('model')->nullable();
            $table->string('trim')->nullable();
            $table->string('style')->nullable();
            $table->string('type')->nullable();
            $table->string('size')->nullable();
            $table->string('category')->nullable();
            $table->string('made_in')->nullable();
            $table->string('made_in_city')->nullable();
            $table->string('doors')->nullable();
            $table->string('fuel_type')->nullable();
            $table->string('fuel_capacity')->nullable();
            $table->string('city_mileage')->nullable();
            $table->string('highway_mileage')->nullable();
            $table->string('engine')->nullable();
            $table->string('engine_size')->nullable();
            $table->string('engine_cylinders')->nullable();
            $table->string('transmission')->nullable();
            $table->string('transmission_type')->nullable();
            $table->string('transmission_speeds')->nullable();
            $table->string('drivetrain')->nullable();
            $table->string('anti_brake_system')->nullable();
            $table->string('steering_type')->nullable();
            $table->string('curb_weight')->nullable();
            $table->string('gross_vehicle_weight_rating')->nullable();
            $table->string('overall_height')->nullable();
            $table->string('overall_length')->nullable();
            $table->string('overall_width')->nullable();
            $table->string('wheelbase_length')->nullable();
            $table->string('standard_seating')->nullable();
            $table->string('invoice_price')->nullable();
            $table->string('delivery_charges')->nullable();
            $table->string('manufacturer_suggested_retail_price')->nullable();
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehicles');
    }
};
        
