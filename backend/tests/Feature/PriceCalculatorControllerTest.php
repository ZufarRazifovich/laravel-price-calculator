<?php

namespace Tests\Feature;

use App\Models\Country;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PriceCalculatorControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_calculate_price_with_valid_data()
    {
        $country = Country::factory()->create(['code' => 'DE', 'tax_rate' => 19]);
        $product = Product::factory()->create(['price' => 100]);

        $response = $this->postJson('/api/calculate', [
            'product_id' => $product->id,
            'tax_number' => 'DE123456789',
        ]);

        $response->assertStatus(200);

        $response->assertJsonStructure(['data' => ['total_price']]);

        $expectedTotalPrice = $product->price + ($product->price * $country->tax_rate / 100);
        $response->assertJsonFragment(['total_price' => $expectedTotalPrice]);
    }

    public function test_calculate_price_with_invalid_tax_number()
    {
        $product = Product::factory()->create();

        $response = $this->postJson('/api/calculate', [
            'product_id' => $product->id,
            'tax_number' => 'INVALID',
        ]);

        $response->assertStatus(422);

        $response->assertJsonValidationErrors('tax_number');
    }
}