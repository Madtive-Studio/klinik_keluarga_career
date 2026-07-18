<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('jobs', function (Blueprint $table) {
            $table->unsignedBigInteger('salary_min')->nullable()->after('quota');
            $table->unsignedBigInteger('salary_max')->nullable()->after('salary_min');
        });

        foreach (DB::table('jobs')->orderBy('id')->get() as $job) {
            [$min, $max] = $this->parseSalaryString($job->salary);

            DB::table('jobs')->where('id', $job->id)->update([
                'salary_min' => $min,
                'salary_max' => $max,
            ]);
        }

        Schema::table('jobs', function (Blueprint $table) {
            $table->dropColumn('salary');
        });
    }

    public function down(): void
    {
        Schema::table('jobs', function (Blueprint $table) {
            $table->string('salary')->nullable()->after('quota');
        });

        foreach (DB::table('jobs')->orderBy('id')->get() as $job) {
            DB::table('jobs')->where('id', $job->id)->update([
                'salary' => formatSalaryRange($job->salary_min, $job->salary_max),
            ]);
        }

        Schema::table('jobs', function (Blueprint $table) {
            $table->dropColumn(['salary_min', 'salary_max']);
        });
    }

    private function parseSalaryString(?string $salary): array
    {
        if ($salary === null || trim($salary) === '') {
            return [null, null];
        }

        preg_match_all('/[\d.]+/', $salary, $matches);
        $amounts = [];

        foreach ($matches[0] ?? [] as $match) {
            $value = (int) str_replace('.', '', $match);

            if ($value > 0) {
                $amounts[] = $value;
            }
        }

        if ($amounts === []) {
            return [null, null];
        }

        if (count($amounts) === 1) {
            return [$amounts[0], $amounts[0]];
        }

        sort($amounts);

        return [$amounts[0], $amounts[count($amounts) - 1]];
    }
};
