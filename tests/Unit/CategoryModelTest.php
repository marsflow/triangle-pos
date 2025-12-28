<?php

namespace Tests\Unit;

use Tests\TestCase;
use Modules\Product\Entities\Category;
use Modules\Product\Entities\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CategoryModelTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test category instantiation
     */
    public function test_category_can_be_instantiated()
    {
        $category = new Category();

        $this->assertInstanceOf(Category::class, $category);
    }

    /**
     * Test category can be created
     */
    public function test_category_can_be_created()
    {
        $category = Category::factory()->create();

        $this->assertDatabaseHas('categories', [
            'id' => $category->id,
            'category_name' => $category->category_name,
        ]);
    }

    /**
     * Test category has many products relationship
     */
    public function test_category_has_many_products()
    {
        $category = Category::factory()->create();
        $products = Product::factory()->count(3)->for($category)->create();

        $this->assertCount(3, $category->products);
        $this->assertTrue($category->products->contains($products[0]));
    }

    /**
     * Test category code is unique
     */
    public function test_category_code_is_unique()
    {
        $code = 'UNIQUE-CODE';
        Category::factory()->create(['category_code' => $code]);

        $this->expectException(\Illuminate\Database\QueryException::class);
        Category::factory()->create(['category_code' => $code]);
    }

    /**
     * Test category can be updated
     */
    public function test_category_can_be_updated()
    {
        $category = Category::factory()->create();
        $newName = 'Updated Category Name';

        $category->update(['category_name' => $newName]);

        $this->assertEquals($newName, $category->fresh()->category_name);
    }

    /**
     * Test category can be deleted
     */
    public function test_category_can_be_deleted()
    {
        $category = Category::factory()->create();
        $categoryId = $category->id;

        $category->delete();

        $this->assertDatabaseMissing('categories', [
            'id' => $categoryId,
        ]);
    }
}
