<?php

namespace App\Http\Controllers;

use App\Models\Appraiser;
use App\Models\City;
use Illuminate\Http\Request;

class TestController extends Controller
{
    /**
     * Provision a new web server.
     */
    public function __invoke()
    {
        /**
         * TEST SCENARIO
         * 
         * OrderID: 435875435875
         * Product: CONV 1004
         * City: Pasadena
         * State: CA
         * Zip: 91106
         * County: Los Angeles
         * 
         * AssignedAppraiser: 64946
         * 
         */

        $order_city = City::where('name', 'Pasadena')->first();

        echo "{$order_city->name} {$order_city->state_code} {$order_city->latitude},{$order_city->longitude}</br></br>";

        $appraisers = Appraiser::all();
        
        foreach ($appraisers as $appraiser) {
            if ($appraiser->zipCodeDetails && $appraiser->zipCodeDetails->latitude != null) {
                echo "{$appraiser->id} {$appraiser->appraiser_user_id}: {$appraiser->zip_code} {$appraiser->zipCodeDetails->latitude},{$appraiser->zipCodeDetails->longitude}</br>";
                echo $this->distance($order_city->latitude, $order_city->longitude, $appraiser->zipCodeDetails->latitude, $appraiser->zipCodeDetails->longitude) . " Miles<br><br>";
            }
        }

        // echo "<pre>";
        // var_dump(Appraiser::first()->zipCodeDetails);
        // echo "</pre>";
    }

    function distance($lat1, $lon1, $lat2, $lon2) {
        if (($lat1 == $lat2) && ($lon1 == $lon2)) {
          return 0;
        }
        else {
          $theta = $lon1 - $lon2;
          $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
          $dist = acos($dist);
          $dist = rad2deg($dist);
          $miles = $dist * 60 * 1.1515;
          return $miles;
        }
      }
}
