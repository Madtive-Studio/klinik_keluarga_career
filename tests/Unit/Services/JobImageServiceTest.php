<?php

namespace Tests\Unit\Services;

use App\Services\JobImageService;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class JobImageServiceTest extends TestCase
{
    #[Test]
    public function itStoresUploadUnderJobUuidDirectory(): void
    {
        Storage::fake('public');
        $service = app(JobImageService::class);
        $uuid = (string) Str::uuid();

        $path = $service->storeUpload(
            UploadedFile::fake()->image('cover.jpg')->size(500),
            $uuid
        );

        $this->assertStringStartsWith("jobs/images/{$uuid}/", $path);
        Storage::disk('public')->assertExists($path);
    }

    #[Test]
    public function itRejectsUploadsLargerThanFiveMegabytes(): void
    {
        Storage::fake('public');
        $service = app(JobImageService::class);

        $this->expectException(\InvalidArgumentException::class);

        $service->storeUpload(
            UploadedFile::fake()->image('large.jpg')->size(5121),
            (string) Str::uuid()
        );
    }

    #[Test]
    public function itRejectsPathsOutsideJobDirectory(): void
    {
        Storage::fake('public');
        $service = app(JobImageService::class);
        $invalidPath = UploadedFile::fake()->image('other.jpg')->store('jobs/images/other-uuid', 'public');

        $this->expectException(\InvalidArgumentException::class);

        $service->assertPathsBelongToJob([$invalidPath], (string) Str::uuid());
    }
}
