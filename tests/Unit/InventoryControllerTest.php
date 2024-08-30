<?php

namespace Tests\Feature;

use App\Models\Inventory;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InventoryControllerTest extends TestCase
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
        $inventory = Inventory::factory()->create();

        $response = $this->get('/api/inventories');

        $response->assertStatus(200)
            ->assertJson([$inventory->toArray()]);
    }

    public function test_show()
    {
        $inventory = Inventory::factory()->create();

        $response = $this->get("/api/inventories/{$inventory->id}");

        $response->assertStatus(200)
            ->assertJson($inventory->toArray());
    }

    public function test_store()
    {
        $product = Product::factory()->create();
        $data = [
            'location' => 'Warehouse A',
            'capacity' => 1000,
            'current_stock' => 500,
            'quantity' => 200,
            'product_id' => $product->id,
        ];

        $response = $this->post('/api/inventories', $data);

        $response->assertStatus(201)
            ->assertJson($data);
    }

    public function test_update()
    {
        $inventory = Inventory::factory()->create();
        $data = [
            'location' => 'Warehouse B',
            'capacity' => 1500,
            'current_stock' => 600,
            'quantity' => 250,
            'product_id' => $inventory->product_id,
        ];

        $response = $this->put("/api/inventories/{$inventory->id}", $data);

        $response->assertStatus(200)
            ->assertJson($data);
    }

    public function test_destroy()
    {
        $inventory = Inventory::factory()->create();

        $response = $this->delete("/api/inventories/{$inventory->id}");

        $response->assertStatus(204);
    }
}
