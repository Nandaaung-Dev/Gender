<?php

namespace App\Http\Controllers;

use App\Models\Location;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LocationController extends Controller
{
    public function index()
    {
        // Fetch males
        $males = Location::with(['males' => function ($query) {
            $query->select('location_id', DB::raw('SUM(CAST(age AS UNSIGNED)) as total_age'))
                ->where('age', 22)
                ->groupBy('location_id');
        }])->get();

        // Fetch females
        $females = Location::with(['females' => function ($query) {
            $query->select('location_id', DB::raw('SUM(CAST(age AS UNSIGNED)) as total_age'))
                ->where('age', 22)
                ->groupBy('location_id');
        }])->get();

        // Fetch others
        $others = Location::with(['others' => function ($query) {
            $query->select('location_id', DB::raw('SUM(CAST(age AS UNSIGNED)) as total_age'))
                ->where('age', 22)
                ->groupBy('location_id');
        }])->get();

        $mergedResults = [];

        // Process males
        foreach ($males as $location) {
            $totalAge = $location->males->first() ? $location->males->first()->total_age : 0;
            $mergedResults[$location->id] = [
                'location' => $location->name,
                'male_total_age' => $totalAge,
                'female_total_age' => 0,
                'other_total_age' => 0,
            ];
        }

        // Process females
        foreach ($females as $location) {
            $totalAge = $location->females->first() ? $location->females->first()->total_age : 0;
            if (isset($mergedResults[$location->id])) {
                $mergedResults[$location->id]['female_total_age'] = $totalAge;
            } else {
                $mergedResults[$location->id] = [
                    'location' => $location->name,
                    'male_total_age' => 0,
                    'female_total_age' => $totalAge,
                    'other_total_age' => 0,
                ];
            }
        }

        // Process others
        foreach ($others as $location) {
            $totalAge = $location->others->first() ? $location->others->first()->total_age : 0;
            if (isset($mergedResults[$location->id])) {
                $mergedResults[$location->id]['other_total_age'] = $totalAge;
            } else {
                $mergedResults[$location->id] = [
                    'location' => $location->name,
                    'male_total_age' => 0,
                    'female_total_age' => 0,
                    'other_total_age' => $totalAge,
                ];
            }
        }

        dd($mergedResults);

        // Convert the merged results to an array of objects
        $finalResults = array_values($mergedResults);

        dump($finalResults);
    }
}
