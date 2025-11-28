<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Modules\Product\Entities\Product;
use Modules\Product\Entities\Category;
use Illuminate\Support\Facades\Gate;

class ProductControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Create a test category
        $this->category = Category::factory()->create();
    }

    /**
     * Test index returns products list view
     */
    public function test_index_returns_products_view()
    {
        // Mock gate to allow access
        Gate::shouldReceive('denies')->with('access_products')->andReturn(false);

        $response = $this->get(route('products.index'));

        $response->assertStatus(200);
    }

    /**
     * Test index denies access without permission
     */
    public function test_index_denies_access_without_permission()
    {
        // Mock gate to deny access
        Gate::shouldReceive('denies')->with('access_products')->andReturn(true);

        $response = $this->get(route('products.index'));

        $response->assertStatus(403);
    }

    /**
     * Test create returns create product view
     */
    public function test_create_returns_product_create_view()
    {
        Gate::shouldReceive('denies')->with('create_products')->andReturn(false);

        $response = $this->get(route('products.create'));

        $response->assertStatus(200);
        $response->assertViewIs('product::products.create');
    }

    /**
     * Test create denies access without permission
     */
    public function test_create_denies_access_without_permission()
    {
        Gate::shouldReceive('denies')->with('create_products')->andReturn(true);

        $response = $this->get(route('products.create'));

        $response->assertStatus(403);
    }

    /**
     * Test store creates a new product
     */
    public function test_store_creates_new_product()
    {
        Gate::shouldReceive('denies')->with('create_products')->andReturn(false);

        $data = [
            'category_id' => $this->category->id,
            'product_name' => 'Test Product',
            'product_code' => 'TP001',
            'product_quantity' => 100,
            'product_cost' => 50.00,
            'product_price' => 75.00,
            'product_stock_alert' => 10,
        ];

        $response = $this->post(route('products.store'), $data);

        $this->assertDatabaseHas('products', [
            'product_name' => 'Test Product',
            'product_code' => 'TP001',
            'category_id' => $this->category->id,
        ]);

        $response->assertRedirect(route('products.index'));
    }

    /**
     * Test store with missing required fields
     */
    public function test_store_with_missing_required_fields()
    {
        Gate::shouldReceive('denies')->with('create_products')->andReturn(false);

        $data = [
            'product_name' => 'Test Product',
            // Missing category_id and other required fields
        ];

        $response = $this->post(route('products.store'), $data);

        $response->assertSessionHasErrors();
    }

    /**
     * Test show returns product detail view
     */
    public function test_show_returns_product_detail()
    {
        Gate::shouldReceive('denies')->with('show_products')->andReturn(false);

        $product = Product::factory()
            ->for($this->category)
            ->create();

        $response = $this->get(route('products.show', $product));

        $response->assertStatus(200);
        $response->assertViewIs('product::products.show');
        $response->assertViewHas('product', $product);
    }

    /**
     * Test show denies access without permission
     */
    public function test_show_denies_access_without_permission()
    {
        Gate::shouldReceive('denies')->with('show_products')->andReturn(true);

        $product = Product::factory()
            ->for($this->category)
            ->create();

        $response = $this->get(route('products.show', $product));

        $response->assertStatus(403);
    }

    /**
     * Test edit returns product edit view
     */
    public function test_edit_returns_product_edit_view()
    {
        Gate::shouldReceive('denies')->with('edit_products')->andReturn(false);

        $product = Product::factory()
            ->for($this->category)
            ->create();

        $response = $this->get(route('products.edit', $product));

        $response->assertStatus(200);
        $response->assertViewIs('product::products.edit');
        $response->assertViewHas('product', $product);
    }

    /**
     * Test edit denies access without permission
     */
    public function test_edit_denies_access_without_permission()
    {
        Gate::shouldReceive('denies')->with('edit_products')->andReturn(true);

        $product = Product::factory()
            ->for($this->category)
            ->create();

        $response = $this->get(route('products.edit', $product));

        $response->assertStatus(403);
    }

    /**
     * Test update modifies an existing product
     */
    public function test_update_modifies_existing_product()
    {
        Gate::shouldReceive('denies')->with('edit_products')->andReturn(false);

        $product = Product::factory()
            ->for($this->category)
            ->create();

        $updatedData = [
            'category_id' => $this->category->id,
            'product_name' => 'Updated Product Name',
            'product_code' => 'TP001',
            'product_quantity' => 150,
            'product_cost' => 60.00,
            'product_price' => 85.00,
            'product_stock_alert' => 15,
        ];

        $response = $this->put(route('products.update', $product), $updatedData);

        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'product_name' => 'Updated Product Name',
            'product_quantity' => 150,
        ]);

        $response->assertRedirect(route('products.index'));
    }

    /**
     * Test destroy deletes a product
     */
    public function test_destroy_deletes_product()
    {
        Gate::shouldReceive('denies')->with('delete_products')->andReturn(false);

        $product = Product::factory()
            ->for($this->category)
            ->create();

        $productId = $product->id;

        $response = $this->delete(route('products.destroy', $product));

        $this->assertDatabaseMissing('products', [
            'id' => $productId,
        ]);

        $response->assertRedirect(route('products.index'));
    }

    /**
     * Test destroy denies access without permission
     */
    public function test_destroy_denies_access_without_permission()
    {
        Gate::shouldReceive('denies')->with('delete_products')->andReturn(true);

        $product = Product::factory()
            ->for($this->category)
            ->create();

        $response = $this->delete(route('products.destroy', $product));

        $response->assertStatus(403);

        $this->assertDatabaseHas('products', [
            'id' => $product->id,
        ]);
    }
}
