<?php

namespace Tests\Feature;

use App\Models\Client;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ClientControllerTest extends TestCase
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
        $client = Client::factory()->create();

        $response = $this->get('/api/clients');

        $response->assertStatus(200)
            ->assertJson([$client->toArray()]);
    }

    public function test_show()
    {
        $client = Client::factory()->create();

        $response = $this->get("/api/clients/{$client->id}");

        $response->assertStatus(200)
            ->assertJson($client->toArray());
    }

    public function test_store()
    {
        $data = [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'phone' => '1234567890',
            'address' => '123 Main St',
        ];

        $response = $this->post('/api/clients', $data);

        $response->assertStatus(201)
            ->assertJson($data);
    }

    public function test_update()
    {
        $client = Client::factory()->create();
        $data = [
            'name' => 'Jane Doe',
            'email' => 'jane@example.com',
            'phone' => '0987654321',
            'address' => '456 Elm St',
        ];

        $response = $this->put("/api/clients/{$client->id}", $data);

        $response->assertStatus(200)
            ->assertJson($data);
    }

    public function test_destroy()
    {
        $client = Client::factory()->create();

        $response = $this->delete("/api/clients/{$client->id}");

        $response->assertStatus(204);
    }
}
