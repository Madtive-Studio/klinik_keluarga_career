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

    #[Test]
    public function createDisplaysFormWithEditableCodeInput(): void
    {
        $response = $this->actingAs($this->admin, 'admin')->get(route('admin.jobs.create'));

        $response->assertStatus(200);
        $response->assertViewIs('admin.jobs.form');
        $response->assertViewHas('code');
        $response->assertDontSee('name="code" readonly', false);
        $response->assertSee('id="job_code"', false);
        $response->assertSee('id="btn-generate-code"', false);
    }

    #[Test]
    public function storeAcceptsCustomAdminJobCode(): void
    {
        $batch = Batch::factory()->create(['quota' => 50]);
        $category = Category::factory()->create();

        $response = $this->actingAs($this->admin, 'admin')->post(route('admin.jobs.store'), [
            'uuid' => (string) \Illuminate\Support\Str::uuid(),
            'code' => 'CUSTOM-JOB-CODE-01',
            'batch_id' => $batch->id,
            'category_id' => $category->id,
            'title' => 'Software Engineer',
            'type' => \App\Enums\JobType::FULLTIME_ONSITE->value,
            'quota' => 5,
            'salary_min' => 5000000,
            'salary_max' => 8000000,
            'experience' => '1-2 years',
            'qualification' => 'PHP, Laravel',
            'description' => 'Develop web apps',
        ]);

        $response->assertRedirect(route('admin.jobs.index'));
        $this->assertDatabaseHas('jobs', [
            'code' => 'CUSTOM-JOB-CODE-01',
            'title' => 'Software Engineer',
            'quota' => 5,
        ]);
    }

    #[Test]
    public function updateAcceptsCustomAdminJobCode(): void
    {
        $job = Job::factory()->create(['code' => 'INITIAL-JOB-CODE']);

        $response = $this->actingAs($this->admin, 'admin')->put(route('admin.jobs.update', $job->id), [
            'uuid' => $job->uuid,
            'code' => 'UPDATED-CUSTOM-JOB-CODE',
            'batch_id' => $job->batch_id,
            'category_id' => $job->category_id,
            'title' => $job->title,
            'type' => $job->type,
            'quota' => $job->quota,
            'salary_min' => $job->salary_min,
            'salary_max' => $job->salary_max,
            'experience' => $job->experience,
            'qualification' => $job->qualification,
            'description' => $job->description,
        ]);

        $response->assertRedirect(route('admin.jobs.index'));
        $this->assertDatabaseHas('jobs', [
            'id' => $job->id,
            'code' => 'UPDATED-CUSTOM-JOB-CODE',
        ]);
    }
}
