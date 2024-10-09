<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (Schema::hasTable('cities') && Schema::hasTable('zip_codes')) {
            // start city import from CSV file
            $ca_cities_file_path = public_path('ca_state_cities.csv');
            $cities_file = file($ca_cities_file_path);

            foreach ($cities_file as $line) {
                $data = str_getcsv($line);
                $city_name = $data[0];
                $state_code = $data[2];
                $state_name = $data[3];
                $county_name = $data[5];
                $latitude = $data[6];
                $longitude = $data[7];

                $city_id = DB::table('cities')->insertGetId([
                    'name' => $city_name,
                    'state_code' => $state_code,
                    'state_name' => $state_name,
                    'county_name' => $county_name,
                    'latitude' => $latitude,
                    'longitude' => $longitude
                ]);

                $zip_codes = $data[15];
                $zips = explode(" ", $zip_codes);

                foreach ($zips as $zip) {
                    DB::table('zip_codes')->insert([
                        'zip' => $zip,
                        'city_id' => $city_id
                    ]);
                }

            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('zip_codes') && Schema::hasTable('cities')) {
            DB::statement('SET FOREIGN_KEY_CHECKS = 0');
            DB::table('zip_codes')->truncate();
            DB::table('cities')->truncate();
            DB::statement('SET FOREIGN_KEY_CHECKS = 1');
        }
    }
};
