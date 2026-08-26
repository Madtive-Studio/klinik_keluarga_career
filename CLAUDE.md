# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project overview

A Laravel 11 (PHP 8.2) job-application/career-portal system ("klinik keluarga career"). It has two independent user domains sharing one codebase:

- **Admin** (HR staff) — manages batches, categories, jobs, candidates, applications, and interview schedules.
- **Candidate** (job applicants) — registers, builds a profile, uploads documents, and applies to job vacancies.

## Commands

```bash
# PHP dependencies
composer install

# JS/CSS assets (Laravel Mix / webpack)
npm install
npm run dev          # development build
npm run watch         # rebuild on change
npm run prod          # production build

# Run the app
php artisan serve

# Run all tests (Pest is installed, but tests are written as PHPUnit-style *Test.php classes)
php artisan test
vendor/bin/phpunit

# Run a single test file / method
vendor/bin/phpunit tests/Unit/Services/ScoringServiceTest.php
vendor/bin/phpunit --filter test_method_name

# Migrations / seeders
php artisan migrate
php artisan migrate:fresh --seed
```

Tests default to a PostgreSQL database `klinik_keluarga_career_test` (configured in `phpunit.xml`), with `array` drivers for mail/session/cache and `sync` queue. Prepare that database before running the feature test suite (many tests hit the DB via repositories).

## Architecture

### Dual-guard authentication

Two separate Eloquent auth guards/providers are defined in `config/auth.php`:

- `admin` guard → `App\Models\User` (staff)
- `candidate` guard → `App\Models\Candidate` (applicants)

Corresponding middleware (`app/Http/Middleware/IsAdmin.php`, `IsCandidate.php`) and route middleware groups (`auth:admin`, `auth:candidate`) gate access. Almost all controllers, routes, and views are split into `Admin/` and `Candidate/` namespaces/directories — check which side you're in before reusing a component or route name (`admin.*` vs `candidate.*` route names in `routes/web.php`).

### Controller → Repository → Model layering

Controllers (`app/Http/Controllers/Admin/*`, `app/Http/Controllers/Candidate/**`) stay thin and delegate query/business logic to `app/Repositories/*` (one repository per resource: `JobRepository`, `BatchRepository`, `CandidateRepository`, `CategoryRepository`, `ApplicationRepository`, `DocumentRepository`, `HomeRepository`). Repositories are constructor-injected into each other when they need cross-resource data (e.g. `JobRepository` depends on `BatchRepository`, `CategoryRepository`, `ApplicationRepository`, `CandidateRepository`). Follow this pattern for new resources rather than querying Eloquent models directly from controllers.

Admin list views use `yajra/laravel-datatables-oracle` — controllers expose a `datatables()` action returning server-side DataTables JSON, separate from the `index()` action that renders the page shell.

### Application scoring pipeline

`App\Services\ScoringService::calculate()` is the core scoring algorithm run when a candidate applies to a job (`Apply` model). It combines:

- Education match (`JobCriteria::min_education` vs `CandidateProfile::education_level`, ranked via `App\Enums\EducationLevel`)
- Experience match (years vs `parseJobExperienceYears()` parsed from the job's free-text `experience` field, in `app/Helpers/helpers.php`)
- Profile completeness (weighted count of filled `CandidateProfile` fields)
- Cover letter length/quality

Each component is weighted per-job via `JobCriteria` (`weight_education`, `weight_experience`, `weight_profile`, `weight_cover_letter`), and the total score is bucketed into `App\Enums\ScoreRecommendation` (`SHORTLIST` / `REVIEW` / `REJECT`) using `JobCriteria::threshold_shortlist` / `threshold_reject`. Results are persisted on `Apply` (`auto_score`, `score_recommendation`, `score_breakdown` as JSON, `scored_at`). When adjusting scoring logic, update both `ScoringServiceTest` and any `JobCriteria` defaults (`JobCriteria::defaultsForJob()`).

### Batches and job quotas

`Batch` has a `quota`; each `Job` belongs to a `Batch` and has its own `quota`. `Batch::allocatedQuota()` / `remainingQuota()` sum quotas across jobs in the batch (optionally excluding one job, used when editing) — respect this when creating/editing jobs so batch quotas aren't oversubscribed.

### Documents

Candidates upload documents (`Document` model, typed via `App\Enums\DocumentType`: CV, IJAZAH, STR, SIP, CERTIFICATE, MCU, OTHERS) independently of any single application. When applying to a job (`Candidate\Jobs\ApplicationController`), candidates select from their existing documents rather than uploading new files inline — applications link to documents through the `ApplyDocument` pivot (`Apply::documents()` is a `hasManyThrough` via `ApplyDocument`). Document storage paths are namespaced per type via `DocumentType::getPath()`.

### Enums drive labels/badges, not just values

Enums under `app/Enums/` (`DocumentType`, `EducationLevel`, `JobType`, `ScoreRecommendation`, `SkillLevel`) centralize both the stored value and its display concerns (`getLabel()`/`label()` via `__('enums....')` translations, `getBadgeClass()`/`badgeClass()` for Bootstrap badge CSS classes). Add new enum cases here rather than hardcoding label/badge strings in views or controllers.

### Locale switching

`SetLocale` middleware + `LocaleController` (`/locale/{locale}`) handle app localization; translation strings live under `resources/lang/`, including an `enums.php` file used by the enum label methods above.

### Helpers file

`app/Helpers/helpers.php` (autoloaded as a Composer `files` entry, not a class) holds small global functions used across controllers/views/services: salary formatting (`formatSalaryAmount`, `formatSalaryAmountShort`, `formatSalaryRange`), Flatpickr datetime conversion (`formatFlatpickrDatetime`, `parseFlatpickrDatetime`), and `parseJobExperienceYears()` used by the scoring service. Prefer adding small cross-cutting helpers here over creating new global functions elsewhere.
