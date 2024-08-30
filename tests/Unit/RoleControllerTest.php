<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RoleControllerTest extends TestCase
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
        $role = Role::factory()->create();

        $response = $this->get('/api/roles');

        $response->assertStatus(200)
            ->assertJson([$role->toArray()]);
    }

    public function test_show()
    {
        $role = Role::factory()->create();

        $response = $this->get("/api/roles/{$role->id}");

        $response->assertStatus(200)
            ->assertJson($role->toArray());
    }

    public function test_store()
{
    $data = [
        'name' => 'Admin',
    ];

    $response = $this->post('/api/roles', $data);

    $response->assertStatus(201)
             ->assertJson([
                 'name' => 'Admin',
             ]);
}

public function test_update()
{
    $role = Role::create([
        'name' => 'Admin',
    ]);

    $data = [
        'name' => 'Super Admin',
        
    ];

    $response = $this->put("/api/roles/{$role->id}", $data);

    $response->assertStatus(200)
             ->assertJson([
                 'name' => 'Super Admin',
             ]);
}


    public function test_destroy()
    {
        $role = Role::factory()->create();

        $response = $this->delete("/api/roles/{$role->id}");

        $response->assertStatus(204);
    }
}
