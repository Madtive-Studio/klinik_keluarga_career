<?php

namespace App\Services;

use App\Models\Job;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use InvalidArgumentException;

class JobImageService
{
    public const MAX_IMAGES = 3;

    public const MAX_SIZE_KB = 5120;

    public const ALLOWED_MIMES = ['jpg', 'jpeg', 'png', 'webp'];

    public function storeUpload(UploadedFile $file, string $jobUuid): string
    {
        $this->assertValidUpload($file);

        $directory = $this->directoryForJob($jobUuid);
        $fileName = generateFileName('job-' . Str::slug($jobUuid), $file->extension());

        return $file->storeAs($directory, $fileName, 'public');
    }

    public function assertValidUpload(UploadedFile $file): void
    {
        if (!$file->isValid()) {
            throw new InvalidArgumentException(__('validation.custom.image.uploaded'));
        }

        if (!in_array(strtolower($file->extension()), self::ALLOWED_MIMES, true)) {
            throw new InvalidArgumentException(__('validation.custom.image.mimes'));
        }

        if ($file->getSize() > self::MAX_SIZE_KB * 1024) {
            throw new InvalidArgumentException(__('validation.custom.image.max'));
        }
    }

    public function normalizePaths(?array $paths): array
    {
        return collect($paths ?? [])
            ->filter(fn ($path) => is_string($path) && trim($path) !== '')
            ->map(fn (string $path) => trim($path))
            ->unique()
            ->values()
            ->take(self::MAX_IMAGES)
            ->all();
    }

    public function assertPathsBelongToJob(array $paths, string $jobUuid): void
    {
        $prefix = $this->directoryForJob($jobUuid) . '/';

        foreach ($paths as $path) {
            if (!str_starts_with($path, $prefix)) {
                throw new InvalidArgumentException(__('validation.custom.images.invalid_path'));
            }

            if (!Storage::disk('public')->exists($path)) {
                throw new InvalidArgumentException(__('validation.custom.images.missing_file'));
            }
        }
    }

    public function syncJobImages(?array $currentPaths, array $requestedPaths): array
    {
        $current = $this->normalizePaths($currentPaths);
        $requested = $this->normalizePaths($requestedPaths);

        $removed = array_diff($current, $requested);
        $this->deletePaths($removed);

        return $requested;
    }

    public function finalizeJobImages(?array $currentPaths, array $requestedPaths): array
    {
        return $this->normalizePaths($requestedPaths);
    }

    public function removedJobImages(?array $currentPaths, array $requestedPaths): array
    {
        $current = $this->normalizePaths($currentPaths);
        $requested = $this->normalizePaths($requestedPaths);

        return array_values(array_diff($current, $requested));
    }

    public function newlyAddedJobImages(?array $currentPaths, array $requestedPaths): array
    {
        $current = $this->normalizePaths($currentPaths);
        $requested = $this->normalizePaths($requestedPaths);

        return array_values(array_diff($requested, $current));
    }

    public function deletePaths(array $paths): void
    {
        foreach ($this->normalizePaths($paths) as $path) {
            if (Storage::disk('public')->exists($path)) {
                Storage::disk('public')->delete($path);
            }
        }
    }

    public function resolveUrls(?array $paths): array
    {
        return collect($this->normalizePaths($paths))
            ->filter(fn (string $path) => Storage::disk('public')->exists($path))
            ->map(fn (string $path) => Storage::url($path))
            ->values()
            ->all();
    }

    public function deleteDirectoryForJob(string $jobUuid): void
    {
        $directory = $this->directoryForJob($jobUuid);

        if (Storage::disk('public')->exists($directory)) {
            Storage::disk('public')->deleteDirectory($directory);
        }
    }

    public function cleanupOrphanedUploads(string $jobUuid, ?int $exceptJobId = null): void
    {
        $query = Job::query()->where('uuid', $jobUuid);

        if ($exceptJobId !== null) {
            $query->where('id', '!=', $exceptJobId);
        }

        if ($query->exists()) {
            return;
        }

        $this->deleteDirectoryForJob($jobUuid);
    }

    public function directoryForJob(string $jobUuid): string
    {
        return 'jobs/images/' . $jobUuid;
    }
}
