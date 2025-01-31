<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Country;
use Illuminate\Http\Request;
use App\Http\Resources\ProductResource;

class PriceCalculatorController extends Controller
{
    public function index()
    {
        try {
            $products = Product::all();
            return response()->json(['data'=>ProductResource::collection($products), 'code'=>1, 'error'=>null]);
        } catch (\Throwable $th) {
            return response()->json(['data' => null, 'code' => 0, 'error' => $th->getMessage()], 500);
        }
    }

    public function calculate(Request $request)
    {
        return 'ok';
    }
}