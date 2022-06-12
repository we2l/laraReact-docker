<?php

namespace Tests\Feature\Controllers\Api;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Tests\TestCase;
use Illuminate\Testing\Fluent\AssertableJson;
use stdClass;

class ProductCategoryControllerTest extends TestCase
{

    /**
     * @test
     */
    public function shouldBeReturnStatus401Unauthenticated()
    {
        $response = $this->getJson("/api/product-category");

        $response
            ->assertUnauthorized()
            ->assertJson(['message' => 'Unauthenticated.']);
    }

   /**
    * @test
    */
    public function shouldBeAbleToGetAllCategoriesByProduct()
    {
        $user = User::factory()->create();
        $category = Category::factory()
                        ->count(2);
        $product = Product::factory()
                            ->has($category)
                            ->count(2)
                            ->create();

        $this->actingAs($user);
        $response = $this->getJson("api/product-category");

        $response
            ->assertStatus(200)
            ->assertJson(["success" => true])
            ->assertJsonCount(2, 'data')
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'name',
                        'description',
                        'slug',
                        'image',
                        'minimum_stock',
                        'sale_price',
                        'purchase_price',
                        'created_at',
                        'updated_at',
                        'categories' => [
                            '*' => [
                                'id',
                                'name',
                                'slug',
                                'created_at',
                                'updated_at'
                            ]
                        ]
                    ]
                ]
            ]);

        $this->assertDatabaseCount('product_category', 4);
    }

    /**
     * @test
     */
    public function shouldBeAbleToBindProductToCategory()
    {
        $user = User::factory()->create();
        $product = Product::factory()->create([
            'name' => 'teste produto'
        ]);
        $category = Category::factory()->create([
            'name' => 'teste categoria'
        ]);
        $data = [
            'slug-product' => $product->slug->value,
            'slug-categories' => [
                $category->slug->value
            ]
        ];

        $this->actingAs($user);

        $response = $this->postJson("/api/product-category", $data);

        $response
            ->assertStatus(201)
            ->assertJson(["success" => true]);
    }

    /**
     * @test
     */
    public function shouldBeReturnAErrorWhenCategoryDontExistInDataBaseWhenBindToProduct()
    {
        $this->withoutExceptionHandling();
        $user = User::factory()->create();
        $product = Product::factory()->create();
        $data = [
            'slug-product' => $product->slug->value,
            'slug-categories' => [
                'slug-inexistente'
            ]
        ];

        $this->actingAs($user);

        $response = $this->postJson("/api/product-category", $data);

        $response
            ->assertStatus(404)
            ->assertJson(["success" => false]);

        $this->assertDatabaseMissing("product_category", [
            "product_id" => $product->id
        ]);
    }
}
