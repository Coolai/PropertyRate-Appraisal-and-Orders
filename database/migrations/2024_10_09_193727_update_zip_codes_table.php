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
        // US Zip Codes data are from: https://simplemaps.com/data/us-zips
        if (Schema::hasTable('zip_codes')) {
            Schema::table('zip_codes', function (Blueprint $table) {
                $table->decimal('latitude', 10, 8)->nullable()->after('city_id');
                $table->decimal('longitude', 11, 8)->nullable()->after('latitude');
            });


            $ca_zipcodes_file_path = public_path('ca_zip_code_lat_long.csv');
            $zp_latlong_file = file($ca_zipcodes_file_path);

            foreach ($zp_latlong_file as $line) {
                $data = str_getcsv($line);
                $zip = $data[0];
                $lat = $data[1];
                $long = $data[2];

                DB::table('zip_codes')
                    ->where('zip', $zip)
                    ->update([
                        'latitude' => $lat, 
                        'longitude' => $long
                    ]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('zip_codes')) {
            Schema::table('zip_codes', function (Blueprint $table) {
                $table->dropColumn(['latitude', 'longitude']);
            });
        }
    }
};
