<?php

namespace tests\Feature\Controllerrs\Api;

use App\Models\Category;
use App\Models\User;
use Tests\TestCase;

class CategoryControllerTest extends TestCase
{   

    /**
     * @test
     */
    public function shouldBeReturnStatus401Unauthenticated()
    {
        $response = $this->getJson("/api/category");

        $response
            ->assertUnauthorized()
            ->assertJson(['message' => 'Unauthenticated.']);
    }

    /**
     * @test
     */
    public function shouldBeAbleToGetAllCategory()
    {   
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->getJson( "/api/category");

        $response
            ->assertStatus(200)
            ->assertJson(["success" => true]);
    }

    /**
     * @test
     */
    public function shouldBeAbleToStoreACategory()
    {
        $user = User::factory()->create();
        $data = [
            'name' => 'categoria de teste',
        ];

        $this->actingAs($user);

        $response = $this->postJson("/api/category", $data);

        $response
            ->assertStatus(201)
            ->assertJson(["success" => true]);

        $this->assertDatabaseHas("categories", [
            'name' => 'categoria de teste'
        ]);
    }

    /**
     * @test
     */
    public function shouldBeReturnAErrorWhenNameCategoryNameStoreAlreadyExists()
    {
        $user = User::factory()->create();
        $category = Category::factory()->create([
            'name' => 'categoria de teste'
        ]);
        $data = [
            'name' => 'categoria de teste',
        ];

        $this->actingAs($user);

        $response = $this->postJson("/api/category", $data);

        $response
            ->assertStatus(422)
            ->assertInvalid([
                'name' => 'The name has already been taken.'
            ]);

        $this->assertDatabaseHas("categories", [
            "name" => "categoria de teste"
        ]);
    }

    /**
     * @test
     */
    public function shouldBeAbleToUpdateACategory()
    {
        $user = User::factory()->create();
        $category = Category::factory()->create();
        $slug = $category->slug;
        $data = [
            'name' => 'name updated'
        ];

        $this->actingAs($user);

        $response = $this->putJson("api/category/$slug", $data);

        $response
            ->assertStatus(200)
            ->assertJson(["success" => true]);

        $this->assertDatabaseHas("categories", [
            'name' => 'name updated'
        ]);
    }

    /**
     * @test
     */
    public function shouldBeAbleToReturnAErrorWhenCategoryUpdateDontExistsInDataBase()
    {
        $user = User::factory()->create();
        $data = [
            'name' => 'update nome'
        ];

        $this->actingAs($user);

        $response = $this->putJson("api/category/slug-inexistente", $data);

        $response
            ->assertStatus(404)
            ->assertJson(["success" => false]);
    }

    /**
     * @test
     */
    public function shouldBeAbleToGetACategoryBySlug()
    {
        $user = User::factory()->create();
        $category = Category::factory()->create();
        $slug = $category->slug;

        $this->actingAs($user);

        $response = $this->getJson("api/category/$slug");

        $response
            ->assertStatus(200)
            ->assertJson(["success" => true]);
        
        $this->assertDatabaseHas("categories",[
            "slug" => $slug 
        ]);
    }

    /**
     * @test
     */
    public function shouldBeAbleToReturnAErrorWhenCategoryShowDontExistInDataBase()
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        $response = $this->getJson("/api/category/slug-inexistente");

        $response
            ->assertStatus(404)
            ->assertJson(["success" => false]);
    }

    /**
     * @test
     */
    public function shouldBeAbleToDestroyACategoryBySlug()
    {
        $user = User::factory()->create();
        $category = Category::factory()->create();
        $slug = $category->slug->value;

        $this->actingAs($user);

        $response = $this->deleteJson("/api/category/$slug");

        $response
            ->assertStatus(200)
            ->assertJson(["success" => true]);

        $this->assertDatabaseMissing("categories", [
            "slug" => $slug
        ]);
    }

    /**
     * @test
     */
    public function shouldBeAbleToReturnAErrorWhenCategoryDestroyDontExistInDatabase()
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        $response = $this->deleteJson("/api/category/slug-inexistente");

        $response
            ->assertStatus(404)
            ->assertJson(["success" => false]);

        $this->assertDatabaseMissing("categories", [
            "slug" => 'slug-inexistente'
        ]);
    }
}