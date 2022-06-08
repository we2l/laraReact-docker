<?php

namespace Tests\Feature\Controllers\Api;

use App\Models\User;
use Tests\TestCase;

class ProductControllerTest extends TestCase
{

    /**
     * @test
     */
    public function shouldBeAbleToGetAllProducts()
    {
        $user = User::factory()->create();
 
        $this->actingAs($user);
        $response = $this->getJson('/api/product');

        $response
            ->assertStatus(200)
            ->assertJson(['success' => true]);
    }

    /**
     * @test
     */
    public function shouldBeReturnStatus401Unauthenticated()
    {
        $response = $this->getJson("/api/product");

        $response
            ->assertUnauthorized()
            ->assertJson(['message' => 'Unauthenticated.']);
    }
}