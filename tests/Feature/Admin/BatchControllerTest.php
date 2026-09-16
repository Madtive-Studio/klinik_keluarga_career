<?php

namespace Tests\Feature\Admin;

use App\Models\Batch;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class BatchControllerTest extends TestCase
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
        $response = $this->get(route('admin.batches.index'));
        $response->assertRedirect(route('admin.login'));
    }

    #[Test]
    public function indexDisplaysBatchesPage(): void
    {
        $response = $this->actingAs($this->admin, 'admin')->get(route('admin.batches.index'));

        $response->assertStatus(200);
        $response->assertViewIs('admin.batches.index');
        $response->assertViewHas('code');
    }

    #[Test]
    public function datatablesReturnsJsonData(): void
    {
        Batch::factory()->count(3)->create();

        $response = $this->actingAs($this->admin, 'admin')
            ->getJson(route('admin.batches.datatables'));

        $response->assertStatus(200);
        $response->assertJsonStructure(['data']);
    }

    #[Test]
    public function storeCreatesNewBatch(): void
    {
        $startDate = Carbon::now()->addDay()->format('d-m-Y H:i:s');
        $endDate = Carbon::now()->addDays(14)->format('d-m-Y H:i:s');

        $response = $this->actingAs($this->admin, 'admin')->post(route('admin.batches.store'), [
            'code' => '#BATCH-TEST',
            'name' => 'Batch Medis 2026',
            'quota' => 50,
            'start_date' => $startDate,
            'end_date' => $endDate,
        ]);

        $response->assertRedirect(route('admin.batches.index'));
        $this->assertDatabaseHas('batches', [
            'code' => '#BATCH-TEST',
            'name' => 'Batch Medis 2026',
            'quota' => 50,
        ]);
    }

    #[Test]
    public function statusToggleUpdatesBatchStatus(): void
    {
        $batch = Batch::factory()->create(['status' => 'INACTIVE']);

        $response = $this->actingAs($this->admin, 'admin')
            ->get(route('admin.batches.status', ['id' => $batch->id, 'status' => 'ACTIVE']));

        $response->assertStatus(200);
        $this->assertDatabaseHas('batches', [
            'id' => $batch->id,
            'status' => 'ACTIVE',
        ]);
    }

    #[Test]
    public function updateModifiesBatchData(): void
    {
        $batch = Batch::factory()->create();

        $startDate = Carbon::now()->addDays(2)->format('d-m-Y H:i:s');
        $endDate = Carbon::now()->addDays(20)->format('d-m-Y H:i:s');

        $response = $this->actingAs($this->admin, 'admin')->put(route('admin.batches.update', $batch->id), [
            'code' => $batch->code,
            'name' => 'Batch Terupdate',
            'quota' => 100,
            'start_date' => $startDate,
            'end_date' => $endDate,
        ]);

        $response->assertRedirect(route('admin.batches.index'));
        $this->assertDatabaseHas('batches', [
            'id' => $batch->id,
            'name' => 'Batch Terupdate',
            'quota' => 100,
        ]);
    }

    #[Test]
    public function destroyDeletesBatch(): void
    {
        $batch = Batch::factory()->create();

        $response = $this->actingAs($this->admin, 'admin')->delete(route('admin.batches.destroy', $batch->id));

        $response->assertRedirect(route('admin.batches.index'));
        $this->assertDatabaseMissing('batches', ['id' => $batch->id]);
    }
}
