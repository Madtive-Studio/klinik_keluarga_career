<?php

namespace Tests\Unit\Repositories;

use App\Models\Category;
use App\Repositories\CategoryRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class CategoryRepositoryTest extends TestCase
{
    use RefreshDatabase;

    private CategoryRepository $repository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = new CategoryRepository();
    }

    #[Test]
    public function getAllReturnsCategoriesOrderedByNameAscending(): void
    {
        Category::factory()->create(['name' => 'Zebra']);
        Category::factory()->create(['name' => 'Alpha']);
        Category::factory()->create(['name' => 'Mike']);

        $all = $this->repository->getAll();

        $this->assertCount(3, $all);
        $this->assertSame(['Alpha', 'Mike', 'Zebra'], $all->pluck('name')->all());
    }
}
