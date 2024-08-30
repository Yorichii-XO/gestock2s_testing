<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\Supplier;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Authenticate a user
        $this->actingAs(User::factory()->create(), 'sanctum');
    }

    public function test_index()
    {
        $product = Product::factory()->create();

        $response = $this->get('/api/products');

        $response->assertStatus(200)
            ->assertJson([$product->toArray()]);
    }

    public function test_show()
    {
        $product = Product::factory()->create();

        $response = $this->get("/api/products/{$product->id}");

        $response->assertStatus(200)
            ->assertJson($product->toArray());
    }

    public function test_store()
    {
        $category = Category::factory()->create();
        $supplier = Supplier::factory()->create();
        $data = [
            'name' => 'Product A',
            'category_id' => $category->id,
            'supplier_id' => $supplier->id,
            'price' => 50.00,
            'image' => null,
        ];

        $response = $this->post('/api/products', $data);

        $response->assertStatus(201)
            ->assertJson($data);
    }

    public function test_update()
    {
        $product = Product::factory()->create();
        $data = [
            'name' => 'Updated Product',
            'price' => 75.00,
        ];

        $response = $this->put("/api/products/{$product->id}", $data);

        $response->assertStatus(200)
            ->assertJson($data);
    }

    public function test_destroy()
    {
        $product = Product::factory()->create();

        $response = $this->delete("/api/products/{$product->id}");

        $response->assertStatus(204);
    }
}
