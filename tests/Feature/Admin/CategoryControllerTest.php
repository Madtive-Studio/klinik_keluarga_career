<?php

namespace Tests\Feature\Admin;

use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class CategoryControllerTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->create();
    }

    #[Test]
    public function guestIsRedirectedToAdminLogin(): void
    {
        $response = $this->get(route('admin.categories.index'));
        $response->assertRedirect(route('admin.login'));
    }

    #[Test]
    public function indexDisplaysCategoriesPage(): void
    {
        $response = $this->actingAs($this->admin, 'admin')->get(route('admin.categories.index'));

        $response->assertStatus(200);
        $response->assertViewIs('admin.categories.index');
    }

    #[Test]
    public function datatablesReturnsJsonData(): void
    {
        Category::factory()->count(3)->create();

        $response = $this->actingAs($this->admin, 'admin')
            ->getJson(route('admin.categories.datatables'));

        $response->assertStatus(200);
        $response->assertJsonStructure(['data']);
    }

    #[Test]
    public function storeCreatesNewCategory(): void
    {
        $response = $this->actingAs($this->admin, 'admin')->post(route('admin.categories.store'), [
            'name' => 'Divisi Keperawatan',
        ]);

        $response->assertRedirect(route('admin.categories.index'));
        $this->assertDatabaseHas('categories', [
            'name' => 'Divisi Keperawatan',
        ]);
    }

    #[Test]
    public function updateModifiesCategory(): void
    {
        $category = Category::factory()->create(['name' => 'Divisi Lama']);

        $response = $this->actingAs($this->admin, 'admin')->put(route('admin.categories.update', $category->id), [
            'name' => 'Divisi Baru',
        ]);

        $response->assertRedirect(route('admin.categories.index'));
        $this->assertDatabaseHas('categories', [
            'id' => $category->id,
            'name' => 'Divisi Baru',
        ]);
    }

    #[Test]
    public function destroyDeletesCategory(): void
    {
        $category = Category::factory()->create();

        $response = $this->actingAs($this->admin, 'admin')->delete(route('admin.categories.destroy', $category->id));

        $response->assertRedirect(route('admin.categories.index'));
        $this->assertDatabaseMissing('categories', ['id' => $category->id]);
    }
}
