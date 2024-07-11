<?php

namespace App\Http\Controllers;

use App\Models\Location;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LocationController extends Controller
{
    public function index()
    {
        $males = DB::table('locations')
            ->leftJoin('males', 'locations.id', '=', 'males.location_id')
            ->select('locations.id as location_id', 'locations.name as male_location', DB::raw('SUM(CAST(males.age AS UNSIGNED)) as total_age'))
            ->where('males.age', 22)
            ->groupBy('locations.id', 'locations.name')
            ->get();

        $females = DB::table('locations')
            ->leftJoin('females', 'locations.id', '=', 'females.location_id')
            ->select('locations.id as location_id', 'locations.name as female_location', DB::raw('SUM(CAST(females.age AS UNSIGNED)) as total_age'))
            ->where('females.age', 22)
            ->groupBy('locations.id', 'locations.name')
            ->get();

        $others = DB::table('locations')
            ->leftJoin('others', 'locations.id', '=', 'others.location_id')
            ->select('locations.id as location_id', 'locations.name as other_location', DB::raw('SUM(CAST(others.age AS UNSIGNED)) as total_age'))
            ->where('others.age', 22)
            ->groupBy('locations.id', 'locations.name')
            ->get();

        $mergedResults = [];

        foreach ($males as $male) {
            dump($male->location_id);
            $mergedResults[$male->location_id] = [
                'location' => $male->male_location,
                'male_total_age' => $male->total_age,
                'female_total_age' => 0,
                'other_total_age' => 0,
            ];
        }


        foreach ($females as $female) {
            if (isset($mergedResults[$female->location_id])) {
                // dump($female->location_id);
                $mergedResults[$female->location_id]['female_total_age'] = $female->total_age;
            } else {
                $mergedResults[$female->location_id] = [
                    'location' => $female->female_location,
                    'male_total_age' => 0,
                    'female_total_age' => $female->total_age,
                    'other_total_age' => 0,
                ];
            }
        }

        foreach ($others as $other) {
            if (isset($mergedResults[$other->location_id])) {
                $mergedResults[$other->location_id]['other_total_age'] = $other->total_age;
                // dd("here");
            } else {
                $mergedResults[$other->location_id] = [
                    'location' => $other->other_location,
                    'male_total_age' => 0,
                    'female_total_age' => 0,
                    'other_total_age' => $other->total_age,
                ];
            }
        }

        dd($mergedResults);


        // Convert the merged results to an array of objects
        $finalResults = array_values($mergedResults);

        dump($finalResults);


    }
}
