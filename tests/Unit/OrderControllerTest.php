<?php

namespace Tests\Feature;

use App\Models\Client;
use App\Models\Order;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrderControllerTest extends TestCase
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
        $order = Order::factory()->create();

        $response = $this->get('/api/orders');

        $response->assertStatus(200)
            ->assertJson([$order->toArray()]);
    }

    public function test_show()
    {
        $order = Order::factory()->create();

        $response = $this->get("/api/orders/{$order->id}");

        $response->assertStatus(200)
            ->assertJson($order->toArray());
    }

    public function test_store()
    {
        $client = Client::factory()->create();
        $data = [
            'client_id' => $client->id,
            'user_id' => User::factory()->create()->id,
            'total_price' => 100.00,
            'status' => 'Pending',
        ];

        $response = $this->post('/api/orders', $data);

        $response->assertStatus(201)
            ->assertJson($data);
    }

    public function test_update()
    {
        $order = Order::factory()->create();
        $data = [
            'total_price' => 200.00,
            'status' => 'Completed',
        ];

        $response = $this->put("/api/orders/{$order->id}", $data);

        $response->assertStatus(200)
            ->assertJson($data);
    }

    public function test_destroy()
    {
        $order = Order::factory()->create();

        $response = $this->delete("/api/orders/{$order->id}");

        $response->assertStatus(204);
    }
}
