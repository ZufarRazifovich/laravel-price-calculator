<?php

namespace App\Http\Controllers;

use App\Models\Country;
use Illuminate\Http\Request;

class CountryController extends Controller
{
    public function index()
    {
        try {
            $countries = Country::all(['code', 'name', 'tax_rate']);

            $formattedCountries = $countries->map(function ($country) {
                return [
                    'code' => $country->code,
                    'name' => $country->name,
                    'tax_rate' => $country->tax_rate / 100, 
                ];
            });

            return response()->json(['data' => $formattedCountries, 'code' => 1, 'error' => null]);
        } catch (\Throwable $th) {
            return response()->json(['data' => null, 'code' => 0, 'error' => $th->getMessage()], 500); 
        }
    }
}