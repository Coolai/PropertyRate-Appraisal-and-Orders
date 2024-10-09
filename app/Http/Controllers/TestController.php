<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TestController extends Controller
{
    /**
     * Provision a new web server.
     */
    public function __invoke()
    {
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
            $zip_codes = $data[15];

            echo "{$city_name} {$state_code} {$county_name} {$latitude} {$longitude} </br>";

            // $zips = explode(" ", $zip_codes);
            // print_r($zips);

            break;
        }
    }
}
