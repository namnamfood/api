<?php
/**
 * Developed by Caspian Soft.
 *
 * Description:
 *
 * @author Orkhan Alirzayev
 * @date 1/5/18
 */

namespace App\Helpers;


class FilterBy
{
    public static function distance($latA, $lonA, $latB, $lonB)
    {

        $theta = $lonA - $lonB;
        $dist = sin(deg2rad($latA)) * sin(deg2rad($latB)) + cos(deg2rad($latA)) * cos(deg2rad($latB)) * cos(deg2rad($theta));
        $dist = acos($dist);
        $dist = rad2deg($dist);
        $miles = $dist * 60 * 1.1515;
        // Convert  miles to KM
        $distance = $miles * 1.609344;
        return round($distance, 1);
    }
}