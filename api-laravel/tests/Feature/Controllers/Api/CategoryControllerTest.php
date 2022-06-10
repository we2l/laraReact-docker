<?php

namespace tests\Feature\Controllerrs\Api;

use App\Models\User;
use Tests\TestCase;

class CategoryControllerTest extends TestCase
{
    /**
     * @test
     */
    public function shouldBeAbleToGetAllCategory()
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        $response = $this->getJson("/api/category");

        $response
            ->assertStatus(200)
            ->assertJson(["success" => true]);
    }
}