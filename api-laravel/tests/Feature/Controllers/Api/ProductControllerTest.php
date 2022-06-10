<?php

namespace Tests\Feature\Controllers\Api;

use App\Models\Product;
use App\Models\User;
use Tests\TestCase;

class ProductControllerTest extends TestCase
{
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
    public function shouldBeAbleToShowProductBySlug()
    {
        $user = User::factory()->create();
        $product = Product::factory()->create();
  
        $this->actingAs($user);

        $response = $this->getJson("api/product/$product->slug");

        $response
            ->assertStatus(200)
            ->assertJson(['success' => true]);
    }

    /**
     * @test
     */
    public function shouldBeAbleToStoreProduct()
    {
        $user = User::factory()->create();
        $data = [
            'name' => 'testando nome',
            'image' => 'path.jpeg',
            'sale_price' => '15.00',
            'purchase_price' => '10.00',
            'minimum_stock' => 10
        ];

        $this->actingAs($user);

        $response = $this->postJson('/api/product', $data);

        $response
            ->assertStatus(201)
            ->assertJson(['success' => true]);

        $this->assertDatabaseHas('products', [
            'name' => 'testando nome'
        ]);
    }

    /**
     * @test
     */
    public function shouldBeAbleToUpdateProduct()
    {
        $user = User::factory()->create();
        $product = Product::factory()->create();
        $slug = $product->slug->value;

        $data = [
            'name' => 'testando update name'
        ];

        $this->actingAs($user);

        $response = $this->putJson("/api/product/$slug", $data);

        $response
            ->assertStatus(200)
            ->assertJson(["success" => true]);

        $this->assertDatabaseHas('products', [
            'name' => 'testando update name'
        ]);
    }

    /**
     * @test
     */
    public function shouldBeReturnAErrorWhenNotExistUpdateProductInDataBase()
    {
        $user = User::factory()->create();
        $data = [
            'name' => 'testando nome'
        ];

        $this->actingAs($user);

        $response = $this->putJson("/api/product/slug-invalido", $data);

        $response
            ->assertStatus(404)
            ->assertJson(["success" => false]);
    }

    /**
     * @test
     */
    public function shouldBeAbleToGetProductBySlug()
    {
        $user = User::factory()->create();
        $product = Product::factory()->create([
            'name' => 'testando método show'
        ]);
        $slug = $product->slug->value;

        $this->actingAs($user);

        $response = $this->getJson("/api/product/$slug");

        $response
            ->assertStatus(200)
            ->assertJson(["success" => true]);
    }

    /**
     * @test
     */
    public function shouldBeReturnAErrorWhenGetProductNotExist()
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        $response = $this->getJson("/api/product/slug-invalido");

        $response
            ->assertStatus(404)
            ->assertJson(["success" => false]);
    }

    /**
     * @test
     */
    public function shouldBeAbleToDestroyAProduct()
    {
        $user = User::factory()->create();
        $product = Product::factory()->create([
            'name' => 'testando destroy'
        ]);
        $slug = $product->slug->value;
        $this->actingAs($user);

        $response = $this->deleteJson("/api/product/$slug");

        $response
            ->assertStatus(200)
            ->assertJson(["success" => true]);

        $this->assertDatabaseMissing("products", [
            'name' => 'testando destroy'
        ]);
    }

    /**
     * @test
     */
    public function shouldBeAbleToReturnAErrorWhenDestroyProductNotExist()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->deleteJson("/api/product/sluyg-invalido");

        $response
            ->assertStatus(404)
            ->assertJson(["success" => false]);
    }
}