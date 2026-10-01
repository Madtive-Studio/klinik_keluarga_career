# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project overview

A Laravel 11 (PHP 8.3+) recruitment / applicant-tracking system for Klinik Keluarga ("klinik keluarga career"), backed by PostgreSQL. It has two independent user domains sharing one codebase:

- **Admin** (HR staff): manages batches, categories, jobs, candidates, applications, and interview schedules.
- **Candidate** (job applicants): registers (with email-token activation), builds a profile, uploads documents, and applies to job vacancies.

The UI is server-rendered Blade (Sneat/Bootstrap 5, jQuery, DataTables, Flatpickr, SweetAlert2, Quill). The app is bilingual (ID/EN). Many user-facing strings and some hardcoded messages are in Indonesian.

## Commands

```bash
composer install
cp .env.example .env && php artisan key:generate
php artisan migrate --seed        # or: php artisan migrate:fresh --seed
php artisan storage:link          # needed for uploaded documents / job images
php artisan serve

# JS/CSS assets (Laravel Mix 5 / webpack)
npm install
npm run dev | npm run watch | npm run prod

# Tests run on Pest 4 (on PHPUnit 12). Existing tests are PHPUnit-style *Test.php classes, which Pest runs as-is
php artisan test
vendor/bin/pest tests/Unit/Repositories/JobRepositoryTest.php
vendor/bin/pest --filter test_method_name
```

Seeded logins (from README): admin `admin@klinikkeluarga.com` / `password` at `/admin/login`; candidate `pelamar@klinikkeluarga.com` / `password`.

Test DB: `phpunit.xml` points at PostgreSQL database `klinik_keluarga_career_test`, so create it first. Tests use `RefreshDatabase`. CI (`.github/workflows/laravel.yml`) runs the same suite on **SQLite** by overriding `DB_CONNECTION`/`DB_DATABASE`, so avoid Postgres-only SQL in code paths that tests cover, and make migrations work on both drivers (the education-enum migrations already branch on the driver).

## Architecture

### Dual-guard authentication

`config/auth.php` defines two guards/providers:

- `admin` guard → `App\Models\User` (staff)
- `candidate` guard → `App\Models\Candidate` (applicants)

Routes in `routes/web.php` are split into `admin.*` (prefix `/admin`, `auth:admin`) and `candidate.*` (prefix `/candidate`, `auth:candidate` plus `verified`). Controllers live in `app/Http/Controllers/Admin/` and `app/Http/Controllers/Candidate/` (with `Candidate/Jobs/` for vacancy and application flow), and views are split the same way under `resources/views/admin` and `resources/views/candidate`. Check which side you're in before reusing a route name or view.

The app still uses the legacy `app/Http/Kernel.php` middleware registration (`$routeMiddleware`), not Laravel 11's `bootstrap/app.php` style. Register new middleware aliases there. `verified` maps to the custom `App\Http\Middleware\EnsureEmailIsVerified` (it checks `email_verified_at` on the candidate), not Laravel's built-in one.

### Controller → Repository → Model layering

Controllers stay thin and delegate query and business logic to `app/Repositories/*` (one per resource: `JobRepository`, `BatchRepository`, `CandidateRepository`, `CategoryRepository`, `ApplicationRepository`, `DocumentRepository`, `HomeRepository`). Repositories are constructor-injected into each other for cross-resource data. Follow this pattern for new resources rather than querying Eloquent from controllers. Validation lives in Form Requests under `app/Http/Requests/`.

Admin list pages use `yajra/laravel-datatables-oracle`: each controller has an `index()` that renders the page shell and a separate `datatables()` action (its own `*.datatables` route) that returns server-side JSON.

### Applying to a job

The flow runs through `JobRepository::getApplyEligibility()` / `findVacancyApplyFormData()` and `ApplicationRepository::submitApplication()`. A candidate can apply only if all of these hold:

- the job's batch is `ACTIVE` and not past `end_date`
- the candidate has not already applied to that job in that batch
- the candidate's profile is complete
- the candidate's `CandidateProfile` education meets `Job::min_education` (`Job::candidateMeetsEducation()`, ranked via `App\Enums\EducationLevel::rankOf()`)

On success an `Apply` is created with status `IN REVIEW` and `ApplicationSubmittedNotification` is sent. Admins then move it through `SHORTLISTED` / `NOT SUITABLE` / `HIRED` and can schedule interviews (`ScheduleInterview`, `InterviewInvitationNotification`). Statuses are plain strings, not an enum.

There is no automatic scoring any more. `JobCriteria`, `ScoringService`, the `ScoreRecommendation` enum, and the `applies` scoring columns were removed, and `min_education` moved onto the `jobs` table. Old migrations that reference `job_criteria` and scoring columns still exist and must stay runnable. `config/scoring.php` (`AUTO_SCORING_ENABLED`) is a leftover flag that nothing reads.

The education ranking also appears as raw SQL `CASE` expressions in `JobRepository` and `Admin\JobManagementController` (for filtering by minimum education). If you add an `EducationLevel` case, update those `CASE` expressions and any enum check-constraint migrations too.

### Batches and job quotas

`Batch` has a `quota`; each `Job` belongs to a `Batch` and has its own `quota`. `Batch::allocatedQuota(?$excludeJobId)` and `remainingQuota()` sum job quotas in a batch (the exclude argument is for editing a job). Respect these when creating or editing jobs so batch quotas aren't oversubscribed. `Job::applies()` is scoped to the job's current `batch_id`.

### Documents

Candidates upload documents (`Document`, typed by `App\Enums\DocumentType`) independently of any application. When applying, they select from existing documents; the link is the `ApplyDocument` pivot (`Apply::documents()` is a `hasManyThrough`). Storage paths are namespaced per type via `DocumentType::getPath()`. Job description images are handled by `App\Services\JobImageService` (the only service class).

### Enums drive labels and badges

Enums in `app/Enums/` (`DocumentType`, `EducationLevel`, `JobType`, `SkillLevel`) hold both the stored value and its display: label methods translate through `resources/lang/{en,id}/enums.php`, and badge methods return Bootstrap badge classes. Add cases and labels here rather than hardcoding strings in views or controllers. Method names differ between enums (`getLabel()`/`getBadgeClass()` on `DocumentType`; `label()`/`labelOf()`/`values()` on `EducationLevel`), so check the specific enum before calling.

### Localization

`SetLocale` middleware plus `LocaleController` (`/locale/{locale}`) store the locale in the session. Translations live in `resources/lang/en` and `resources/lang/id` (`admin.php`, `candidate.php`, `common.php`, `emails.php`, `enums.php`, `messages.php`, `validation.php`). Add new keys to both locales. Notifications use the `UsesNotificationLocale` trait so emails render in the session's locale.

### Global helpers

`app/Helpers/helpers.php` is autoloaded as a Composer `files` entry and `require`s `asset_helpers.php`. It holds small global functions used across controllers, views, and repositories: salary formatting (`formatSalaryAmount`, `formatSalaryAmountShort`, `formatSalaryRange`), Flatpickr datetime conversion (`formatFlatpickrDatetime`, `parseFlatpickrDatetime`), `parseJobExperienceYears()`, `normalizePhoneNumber()` (defaults to `+62`), and file-name helpers. Add small cross-cutting helpers here rather than creating new global functions elsewhere.
