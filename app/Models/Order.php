<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Order extends Model
{
    use HasFactory;

    /**
     * Get the zip code associated with the order.
     */
    public function zipCodeDetails(): HasOne
    {
        return $this->hasOne(ZipCode::class, 'zip', 'zip_code');
    }

    function distance($lat1, $lon1, $lat2, $lon2) {
        if (($lat1 == $lat2) && ($lon1 == $lon2)) {
            return 0;
        } else {
            $theta = $lon1 - $lon2;
            $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
            $dist = acos($dist);
            $dist = rad2deg($dist);
            $miles = $dist * 60 * 1.1515;
            return $miles;
        }
    }

    public function listAppraiserMatch(String $rating = '')
    {
        if (strlen($rating) === 0) {
            $rating = 'great';
        }

        $great_match_appraisers = []; // 0-10 miles away and no 1 star
        $good_match_appraisers = []; // 10-20 miles away and no 1-2 stars
        $okay_match_appraisers = []; // 20-50 miles away and ONLY 4 star, if nothing show none
        $far_away_match_appraisrs = [];

        $appraisers = Appraiser::all();
        foreach ($appraisers as $appraiser) {
            if ($appraiser->zipCodeDetails) {
                $miles_to_order = $this->distance(
                    $this->zipCodeDetails->latitude, 
                    $this->zipCodeDetails->longitude, 
                    $appraiser->zipCodeDetails->latitude,
                    $appraiser->zipCodeDetails->longitude
                );

                if ($miles_to_order > 0 && $miles_to_order <= 10 && $appraiser->rank > 1) {
                    array_push($great_match_appraisers, $appraiser->id);
                } elseif ($miles_to_order > 10 && $miles_to_order <= 20 && $appraiser->rank > 2) {
                    array_push($good_match_appraisers, $appraiser->id);
                } elseif ($miles_to_order > 20 && $miles_to_order <= 50) {
                    array_push($far_away_match_appraisrs, $appraiser->id);
                    if ($appraiser->rank === 4) {
                        array_push($okay_match_appraisers, $appraiser->id);
                    }
                }
            }
        }

        switch ($rating) {
            case 'great':
                return Appraiser::whereIn('id', $great_match_appraisers)->pluck('id');
                break;
            case 'good':
                return Appraiser::whereIn('id', $good_match_appraisers)->pluck('id');
                break;
            case 'okay':
                return Appraiser::whereIn('id', $okay_match_appraisers)->pluck('id');
                break;
            default:
                return Appraiser::all()->pluck('id');
                break;
        }
    }

    public function appraiser(): HasOne
    {
        return $this->hasOne(Appraiser::class, 'id', 'appraiser_id');
    }
}
