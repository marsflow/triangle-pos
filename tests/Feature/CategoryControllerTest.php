<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Product\Entities\Category;
use Illuminate\Support\Facades\Gate;

class CategoryControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test categories index returns view
     */
    public function test_categories_index_returns_view()
    {
        Gate::shouldReceive('denies')->andReturn(false);

        $response = $this->get(route('categories.index'));

        $response->assertStatus(200);
    }

    /**
     * Test categories index denies access without permission
     */
    public function test_categories_index_denies_access_without_permission()
    {
        Gate::shouldReceive('denies')->with('access_categories')->andReturn(true);

        $response = $this->get(route('categories.index'));

        $response->assertStatus(403);
    }

    /**
     * Test create category returns view
     */
    public function test_create_category_returns_view()
    {
        Gate::shouldReceive('denies')->with('create_categories')->andReturn(false);

        $response = $this->get(route('categories.create'));

        $response->assertStatus(200);
    }

    /**
     * Test store creates new category
     */
    public function test_store_creates_new_category()
    {
        Gate::shouldReceive('denies')->with('create_categories')->andReturn(false);

        $data = [
            'category_code' => 'CAT001',
            'category_name' => 'Electronics',
        ];

        $response = $this->post(route('categories.store'), $data);

        $this->assertDatabaseHas('categories', [
            'category_code' => 'CAT001',
            'category_name' => 'Electronics',
        ]);

        $response->assertRedirect(route('categories.index'));
    }

    /**
     * Test store with duplicate category code fails
     */
    public function test_store_with_duplicate_category_code_fails()
    {
        Gate::shouldReceive('denies')->with('create_categories')->andReturn(false);

        Category::create([
            'category_code' => 'CAT001',
            'category_name' => 'Electronics',
        ]);

        $data = [
            'category_code' => 'CAT001',
            'category_name' => 'Another Electronics',
        ];

        $response = $this->post(route('categories.store'), $data);

        $response->assertSessionHasErrors();
    }

    /**
     * Test edit category returns view
     */
    public function test_edit_category_returns_view()
    {
        Gate::shouldReceive('denies')->with('edit_categories')->andReturn(false);

        $category = Category::factory()->create();

        $response = $this->get(route('categories.edit', $category));

        $response->assertStatus(200);
        $response->assertViewHas('category', $category);
    }

    /**
     * Test update category modifies existing category
     */
    public function test_update_category_modifies_existing_category()
    {
        Gate::shouldReceive('denies')->with('edit_categories')->andReturn(false);

        $category = Category::factory()->create();

        $data = [
            'category_code' => 'CAT002',
            'category_name' => 'Updated Category',
        ];

        $response = $this->put(route('categories.update', $category), $data);

        $this->assertDatabaseHas('categories', [
            'id' => $category->id,
            'category_name' => 'Updated Category',
        ]);

        $response->assertRedirect(route('categories.index'));
    }

    /**
     * Test destroy deletes category
     */
    public function test_destroy_deletes_category()
    {
        Gate::shouldReceive('denies')->with('delete_categories')->andReturn(false);

        $category = Category::factory()->create();
        $categoryId = $category->id;

        $response = $this->delete(route('categories.destroy', $category));

        $this->assertDatabaseMissing('categories', [
            'id' => $categoryId,
        ]);

        $response->assertRedirect(route('categories.index'));
    }

    /**
     * Test destroy denies access without permission
     */
    public function test_destroy_denies_access_without_permission()
    {
        Gate::shouldReceive('denies')->with('delete_categories')->andReturn(true);

        $category = Category::factory()->create();

        $response = $this->delete(route('categories.destroy', $category));

        $response->assertStatus(403);

        $this->assertDatabaseHas('categories', [
            'id' => $category->id,
        ]);
    }
}
