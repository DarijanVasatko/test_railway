<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Country;
use App\Models\CountryTown;

class CountryTownController extends Controller
{
    public function search(Request $request)
    {
        $query = $request->get('q', '');
        $country = $request->get('country', '');

        $countryId = Country::where('name', $country)->value('id');

        $towns = CountryTown::where('country_id', $countryId)
            ->where('name', 'like', $query . '%')
            ->orderBy('name')
            ->take(10)
            ->pluck('name');

        return response()->json($towns);
    }
}
