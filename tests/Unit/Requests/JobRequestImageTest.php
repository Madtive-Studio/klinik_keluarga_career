<?php

namespace Tests\Unit\Requests;

use App\Http\Requests\JobRequest;
use App\Models\Batch;
use App\Models\Category;
use App\Models\User;
use App\Services\JobImageService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class JobRequestImageTest extends TestCase
{
    use RefreshDatabase;

    private function basePayload(array $overrides = []): array
    {
        $batch = Batch::factory()->create();
        $category = Category::factory()->create();
        $uuid = (string) Str::uuid();

        return array_merge([
            'uuid' => $uuid,
            'code' => 'JOB-001',
            'batch_id' => $batch->id,
            'category_id' => $category->id,
            'title' => 'Staff Admin',
            'type' => 'Fulltime/Onsite',
            'quota' => 1,
            'salary_min' => 3000000,
            'salary_max' => 5000000,
            'experience' => '1 Tahun',
            'qualification' => 'Qualification text',
            'description' => 'Description text',
        ], $overrides);
    }

    private function validate(array $data): \Illuminate\Validation\Validator
    {
        $this->actingAs(User::factory()->create(), 'admin');

        return Validator::make($data, (new JobRequest())->rules());
    }

    #[Test]
    public function itAcceptsUpToThreeStoredImagePaths(): void
    {
        Storage::fake('public');
        $uuid = (string) Str::uuid();
        $service = app(JobImageService::class);
        $paths = [];

        foreach (range(1, 3) as $index) {
            $paths[] = UploadedFile::fake()->image("job{$index}.jpg")->store($service->directoryForJob($uuid), 'public');
        }

        $validator = $this->validate($this->basePayload([
            'uuid' => $uuid,
            'images' => $paths,
        ]));

        $this->assertFalse($validator->fails());
    }

    #[Test]
    public function itRejectsMoreThanThreeImages(): void
    {
        Storage::fake('public');
        $uuid = (string) Str::uuid();
        $service = app(JobImageService::class);
        $paths = [];

        foreach (range(1, 4) as $index) {
            $paths[] = UploadedFile::fake()->image("job{$index}.jpg")->store($service->directoryForJob($uuid), 'public');
        }

        $validator = $this->validate($this->basePayload([
            'uuid' => $uuid,
            'images' => $paths,
        ]));

        $this->assertTrue($validator->fails());
        $this->assertTrue($validator->errors()->has('images'));
    }
}
