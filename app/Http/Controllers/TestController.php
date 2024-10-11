<?php

namespace App\Http\Controllers;

use App\Models\Appraiser;
use App\Models\City;
use App\Models\Order;
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

        $order_city = City::where('name', 'La Mesa')->first();
        echo "{$order_city->name} {$order_city->state_code} {$order_city->latitude},{$order_city->longitude}</br></br>";

        $great_match_appraisers = collect([]); // 0-10 miles away and no 1 star
        $good_match_appraisers = collect([]); // 10-20 miles away and no 1-2 stars
        $okay_match_appraisers = collect([]); // 20-50 miles away and ONLY 4 star, if nothing show none
        $far_away_match_appraisrs = collect([]);

        
        $appraisers = Appraiser::all();
        foreach ($appraisers as $appraiser) {
            if ($appraiser->zipCodeDetails) {
                $miles_to_order = $this->distance(
                    $order_city->latitude, 
                    $order_city->longitude, 
                    $appraiser->zipCodeDetails->latitude,
                    $appraiser->zipCodeDetails->longitude
                );

                if ($miles_to_order > 0 && $miles_to_order <= 10 && $appraiser->rank > 1) {
                    $great_match_appraisers->push($appraiser);
                } elseif ($miles_to_order > 10 && $miles_to_order <= 20 && $appraiser->rank > 2) {
                    $good_match_appraisers->push($appraiser);
                } elseif ($miles_to_order > 20 && $miles_to_order <= 50) {
                    $far_away_match_appraisrs->push($appraiser);
                    if ($appraiser->rank === 4) {
                        $okay_match_appraisers->push($appraiser);
                    }
                }
            }
        }

        foreach ($great_match_appraisers as  $collection) {
            echo "{$collection->appraiser_user_id}: {$collection->rank}, {$collection->county} {$collection->zip_code} {$collection->zipCodeDetails->latitude},{$collection->zipCodeDetails->longitude}</br>";
        }

        // echo "{$appraiser->id} {$appraiser->appraiser_user_id}: {$appraiser->zip_code} {$appraiser->zipCodeDetails->latitude},{$appraiser->zipCodeDetails->longitude}</br>";
        // $order = Order::first();
        // echo "<pre>";
        // var_dump($order->listAppraiserMatch("great"));
        // echo "</pre>";
    }

    /**
     * https://www.geodatasource.com/developers/php
     */
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
