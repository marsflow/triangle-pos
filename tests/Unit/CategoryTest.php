<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use Modules\Product\Entities\Category;

class CategoryTest extends TestCase
{
    /**
     * Test category instantiation
     */
    public function test_category_can_be_instantiated()
    {
        $category = new Category();

        $this->assertInstanceOf(Category::class, $category);
    }

    /**
     * Test category is guarded against mass assignment
     */
    public function test_category_is_guarded()
    {
        $category = new Category();
        $guarded = $category->getGuarded();

        $this->assertEquals([], $guarded);
    }
}
