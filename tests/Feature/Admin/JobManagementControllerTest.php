<?php

namespace Tests\Feature\Admin;

use App\Models\Batch;
use App\Models\Category;
use App\Models\Job;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class JobManagementControllerTest extends TestCase
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
        $response = $this->get(route('admin.jobs.index'));
        $response->assertRedirect(route('admin.login'));
    }

    #[Test]
    public function indexDisplaysJobsPage(): void
    {
        $response = $this->actingAs($this->admin, 'admin')->get(route('admin.jobs.index'));

        $response->assertStatus(200);
        $response->assertViewIs('admin.jobs.index');
        $response->assertViewHas(['categories', 'batches']);
    }

    #[Test]
    public function datatablesReturnsJsonData(): void
    {
        Job::factory()->count(2)->create();

        $response = $this->actingAs($this->admin, 'admin')
            ->getJson(route('admin.jobs.datatables'));

        $response->assertStatus(200);
        $response->assertJsonStructure(['data']);
    }

    #[Test]
    public function toggleSalaryUpdatesJobVisibility(): void
    {
        $job = Job::factory()->create(['is_show_salary' => false]);

        $response = $this->actingAs($this->admin, 'admin')
            ->patchJson(route('admin.jobs.toggle-salary', $job->id), [
                'is_show_salary' => true,
            ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('jobs', [
            'id' => $job->id,
            'is_show_salary' => true,
        ]);
    }

    #[Test]
    public function destroyDeletesJob(): void
    {
        $job = Job::factory()->create();

        $response = $this->actingAs($this->admin, 'admin')->delete(route('admin.jobs.destroy', $job->id));

        $response->assertRedirect(route('admin.jobs.index'));
        $this->assertDatabaseMissing('jobs', ['id' => $job->id]);
    }
}
