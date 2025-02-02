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
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'tax_number' => [
                'required',
                'string',
                function ($attribute, $value, $fail) {
                    $countryCode = strtoupper(substr($value, 0, 2));
                    $numericPart = substr($value, 2);

                    if (!in_array($countryCode, ['DE', 'IT', 'GR'])) {
                        $fail('Invalid country code.');
                        return;
                    }

                    if ($countryCode === 'IT' && strlen($numericPart) !== 11) {
                        $fail('Invalid tax number format for Italy. 11 digits are expected after the country code.');
                        return;
                    }

                    if (in_array($countryCode, ['DE', 'GR']) && strlen($numericPart) !== 9) {
                        $fail('Invalid tax number format. 9 digits are expected after the country code.');
                        return;
                    }
                },
            ],
        ]);

        $product = Product::findOrFail($request->product_id);
        $countryCode = strtoupper(substr($request->tax_number, 0, 2));
        $country = Country::where('code', $countryCode)->firstOrFail();

        $taxAmount = $product->price * ($country->tax_rate / 100);
        $totalPrice = $product->price + $taxAmount;

        return response()->json([
            'data' => [
                'product_name' => $product->name,
                'country_code' => $country->code,
                'tax_rate' => $country->tax_rate,
                'price_without_tax' => $product->price,
                'tax_amount' => $taxAmount,
                'total_price' => $totalPrice
            ],
            'code' => 1,
            'error' => null,
        ]);
    }
}