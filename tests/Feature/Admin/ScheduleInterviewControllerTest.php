<?php

namespace Tests\Feature\Admin;

use App\Models\Apply;
use App\Models\Candidate;
use App\Models\Company;
use App\Models\Job;
use App\Models\ScheduleInterview;
use App\Models\User;
use App\Notifications\InterviewInvitationNotification;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Str;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class ScheduleInterviewControllerTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->create();

        Company::create([
            'name' => 'Klinik Keluarga',
            'address' => 'Jl. Raya Cilaku No. 1, Cianjur',
            'location' => 'Cianjur',
        ]);
    }

    #[Test]
    public function guestIsRedirectedToAdminLogin(): void
    {
        $response = $this->get(route('admin.schedule-interviews.index'));
        $response->assertRedirect(route('admin.login'));
    }

    #[Test]
    public function indexDisplaysScheduleInterviewsPage(): void
    {
        $response = $this->actingAs($this->admin, 'admin')
            ->get(route('admin.schedule-interviews.index'));

        $response->assertStatus(200);
        $response->assertViewIs('admin.schedule-interviews.index');
    }

    #[Test]
    public function datatablesReturnsJsonData(): void
    {
        $response = $this->actingAs($this->admin, 'admin')
            ->getJson(route('admin.schedule-interviews.datatables'));

        $response->assertStatus(200);
        $response->assertJsonStructure(['data']);
    }

    #[Test]
    public function createDisplaysFormWithShortlistedCandidates(): void
    {
        Apply::factory()->create(['status' => 'SHORTLISTED']);

        $response = $this->actingAs($this->admin, 'admin')
            ->get(route('admin.schedule-interviews.create'));

        $response->assertStatus(200);
        $response->assertViewIs('admin.schedule-interviews.form');
        $response->assertViewHas(['uuid', 'code', 'applies']);
    }

    #[Test]
    public function storeCreatesScheduleAndSendsInvitationNotification(): void
    {
        Notification::fake();

        $candidate = Candidate::factory()->create();
        $job = Job::factory()->create();
        $apply = Apply::factory()->create([
            'candidate_id' => $candidate->id,
            'job_id' => $job->id,
            'status' => 'SHORTLISTED',
        ]);

        $startDate = Carbon::now()->addDays(3)->format('d-m-Y H:i:s');
        $endDate = Carbon::now()->addDays(3)->addHours(2)->format('d-m-Y H:i:s');

        $response = $this->actingAs($this->admin, 'admin')
            ->post(route('admin.schedule-interviews.store'), [
                'uuid' => (string) Str::uuid(),
                'code' => '#INT-TEST',
                'apply_id' => $apply->id,
                'title' => 'Wawancara Medis Tahap 1',
                'start_datetime' => $startDate,
                'end_datetime' => $endDate,
                'is_online' => '1',
                'link' => 'https://meet.google.com/abc-defg-hij',
                'description' => 'Silakan hadir 10 menit sebelum waktu wawancara.',
            ]);

        $response->assertRedirect(route('admin.schedule-interviews.index'));
        $this->assertDatabaseHas('schedule_interviews', [
            'code' => '#INT-TEST',
            'apply_id' => $apply->id,
            'title' => 'Wawancara Medis Tahap 1',
        ]);

        Notification::assertSentTo($candidate, InterviewInvitationNotification::class);
    }
}
