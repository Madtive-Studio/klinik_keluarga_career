<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('job_images', function (Blueprint $table) {
            $table->id();
            $table->foreignId('job_id')->nullable()->constrained()->cascadeOnDelete();
            $table->string('job_uuid', 36);
            $table->string('original_name', 255);
            $table->string('hash_name', 255);
            $table->decimal('size', 8, 2); // Size in MB
            $table->string('extension', 10);
            $table->string('mime_type', 100);
            $table->boolean('is_primary')->default(false);
            $table->timestamps();
        });

        // Migrate existing JSON images data from jobs table
        $jobs = DB::table('jobs')->whereNotNull('images')->get();

        foreach ($jobs as $job) {
            $images = json_decode($job->images, true);
            if (!is_array($images)) {
                continue;
            }

            foreach ($images as $index => $path) {
                if (empty($path)) {
                    continue;
                }

                $size = 0.0;
                $extension = 'png';
                $mimeType = 'image/png';

                try {
                    if (Storage::disk('public')->exists($path)) {
                        $size = round(Storage::disk('public')->size($path) / (1024 * 1024), 2);
                        $mimeType = Storage::disk('public')->mimeType($path) ?: 'image/png';
                        $extension = pathinfo($path, PATHINFO_EXTENSION) ?: 'png';
                    } else {
                        $extension = pathinfo($path, PATHINFO_EXTENSION) ?: 'png';
                    }
                } catch (\Throwable $e) {
                    $extension = pathinfo($path, PATHINFO_EXTENSION) ?: 'png';
                }

                DB::table('job_images')->insert([
                    'job_id' => $job->id,
                    'job_uuid' => $job->uuid,
                    'original_name' => basename($path),
                    'hash_name' => $path,
                    'size' => $size,
                    'extension' => $extension,
                    'mime_type' => $mimeType,
                    'is_primary' => $index === 0,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        // Drop images column from jobs table
        Schema::table('jobs', function (Blueprint $table) {
            $table->dropColumn('images');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Re-create images column in jobs table
        Schema::table('jobs', function (Blueprint $table) {
            $table->json('images')->nullable()->after('code');
        });

        // Migrate data back from job_images to jobs.images
        $jobImages = DB::table('job_images')->get()->groupBy('job_id');

        foreach ($jobImages as $jobId => $images) {
            if (!$jobId) {
                continue;
            }

            $paths = $images->pluck('hash_name')->toArray();

            DB::table('jobs')->where('id', $jobId)->update([
                'images' => json_encode($paths),
            ]);
        }

        Schema::dropIfExists('job_images');
    }
};
