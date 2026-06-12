# Refactoring Documentation - Klinik Keluarga Career Portal

## Overview
Project telah melalui refactoring besar-besaran untuk membersihkan struktur codebase dari pattern Repository yang "thick" menjadi clean architecture dengan Service Layer yang jelas.

## Architecture Change

### BEFORE (Thick Repository Pattern)
```
Controller → Repository (Business Logic + Query) → Model
```

### AFTER (Clean Architecture)
```
Controller → Service (Business Logic) → Repository (QueryBuilder only) → Model
```

## Enums yang Sudah Dibuat

### 1. ApplicationStatus
```php
use App\Enums\ApplicationStatus;

ApplicationStatus::IN_REVIEW      // 'Sedang Dalam Review'
ApplicationStatus::NOT_SUITABLE   // 'Tidak Sesuai'
ApplicationStatus::SHORTLISTED    // 'Lolos Tahap Selanjutnya'
ApplicationStatus::HIRED          // 'Diterima'

// Methods
$status->getLabel()       // Get display label
$status->getColor()       // Get badge color
$status->getBadgeClass()  // Get HTML badge class
```

### 2. DocumentStatus
```php
use App\Enums\DocumentStatus;

DocumentStatus::PENDING       // 'Menunggu Review'
DocumentStatus::VERIFIED      // 'Terverifikasi'
DocumentStatus::REJECTED      // 'Ditolak'
DocumentStatus::UNDER_REVIEW  // 'Sedang Direview'
```

### 3. DocumentCategory
```php
use App\Enums\DocumentCategory;

DocumentCategory::IDENTITY         // Dokumen Identitas
DocumentCategory::EDUCATIONAL      // Dokumen Pendidikan
DocumentCategory::CERTIFICATION    // Sertifikasi
DocumentCategory::MEDICAL          // Dokumen Kesehatan
DocumentCategory::PORTFOLIO        // Portfolio
DocumentCategory::OTHER            // Dokumen Lainnya
```

### 4. IdentityType
```php
use App\Enums\IdentityType;

IdentityType::KTP              // KTP (Kartu Tanda Penduduk)
IdentityType::PASSPORT         // Passport
IdentityType::DRIVING_LICENSE  // Surat Ijin Mengemudi (SIM)
IdentityType::NATIONAL_ID      // Nomor Induk Kependudukan (NIK)
```

## Services

### ApplicationService
Menangani logic berkaitan dengan job application.

```php
use App\Services\ApplicationService;

// Submit application dengan document
$result = app(ApplicationService::class)->submitApplication(
    jobUuid: 'uuid-xxx',
    candidateId: 1,
    data: [
        'cover_letter' => 'Letter...',
        'description' => 'Description...',
        'type_of_document' => 'upload', // or 'select'
        'document_id' => 1, // jika select
    ],
    documentFile: $file // jika upload
);

// Response structure:
// [
//     'success' => true/false,
//     'apply' => Apply model (jika success),
//     'error' => 'Error message',
//     'message' => 'Success message',
// ]

// Update application status
app(ApplicationService::class)->updateStatus(
    applicationId: 1,
    status: ApplicationStatus::SHORTLISTED,
    notes: 'Passed initial screening'
);

// Check if candidate already applied
$hasApplied = app(ApplicationService::class)->hasApplied($candidateId, $job);

// Get candidate applications
$applications = app(ApplicationService::class)->getCandidateApplicationsPaginated(
    candidateId: 1,
    perPage: 10,
    filters: ['status' => 'IN REVIEW']
);
```

### DocumentService
Menangani upload, verifikasi, dan management dokumen.

```php
use App\Services\DocumentService;
use App\Enums\DocumentType;

// Upload document
$document = app(DocumentService::class)->uploadDocument(
    file: $uploadedFile,
    candidateId: 1,
    type: DocumentType::CV,
    isRequired: true
);

// Verify document
app(DocumentService::class)->verifyDocument(
    documentId: 1,
    notes: 'CV diterima dan terverifikasi'
);

// Reject document
app(DocumentService::class)->rejectDocument(
    documentId: 1,
    notes: 'Format tidak sesuai, mohon upload ulang'
);

// Delete document
app(DocumentService::class)->deleteDocument(documentId: 1);

// Get candidate documents paginated
$documents = app(DocumentService::class)->getCandidateDocumentsPaginated(
    candidateId: 1,
    perPage: 10,
    type: 'CV' // optional
);

// Check document completeness
$completeness = app(DocumentService::class)->checkDocumentCompleteness($candidateId);
// Returns: [
//     'total_required' => 2,
//     'verified_count' => 1,
//     'is_complete' => false,
//     'percentage' => 50.0,
// ]
```

### CandidateService
Menangani candidate profile, kelengkapan identitas, dan dokumentasi.

```php
use App\Services\CandidateService;

// Check identity completeness
$identity = app(CandidateService::class)->checkIdentityCompleteness($candidateId);
// Returns: [
//     'completed_count' => 2,
//     'total_required' => 3,
//     'percentage' => 66.67,
//     'is_complete' => false,
//     'missing_fields' => ['education_background'],
// ]

// Check document completeness
$documents = app(CandidateService::class)->checkDocumentCompleteness($candidateId);

// Get overall completeness (identity + documents)
$overall = app(CandidateService::class)->getOverallCompleteness($candidateId);
// Returns: [
//     'identity' => [...],
//     'documents' => [...],
//     'overall_percentage' => 60.0,
//     'is_complete' => false,
// ]

// Update candidate identity info
app(CandidateService::class)->updateIdentityInfo($candidateId, [
    'ktp_number' => '1234567890',
    'gender' => 'male',
    'education_background' => 'S1 Teknik Informatika',
    'work_experience' => '2 tahun sebagai Developer',
]);

// Verify candidate identity
app(CandidateService::class)->verifyIdentity($candidateId);

// Mark documents as complete
app(CandidateService::class)->markDocumentComplete($candidateId);

// Get profile summary
$profile = app(CandidateService::class)->getProfileSummary($candidateId);
// Returns: [
//     'candidate' => Candidate model,
//     'completeness' => [...],
//     'documents_count' => 3,
//     'identities_count' => 2,
// ]
```

### JobService
Menangani job listings, search, dan vacancy display.

```php
use App\Services\JobService;

// Get home page data
$homeData = app(JobService::class)->getHomePageData([
    'WFH/Remote' => 'WFH/Remote',
    'Fulltime/Onsite' => 'Fulltime/Onsite',
    // ...
]);
// Returns: [
//     'activeBatch' => Batch model,
//     'batchLabel' => 'Batch label string',
//     'categories' => Collection,
//     'jobsByType' => array of job collections,
// ]

// Search vacancies
$results = app(JobService::class)->searchVacancies(
    searchQuery: 'Dokter',
    categoryId: 2,
    jobType: 'Fulltime/Onsite',
    batchId: 1,
    perPage: 15
);

// Get vacancy detail
$vacancyDetail = app(JobService::class)->getVacancyDetail(
    jobUuid: 'uuid-xxx',
    candidateId: 1
);

// Get apply form data
$applyFormData = app(JobService::class)->getApplyFormData(
    jobUuid: 'uuid-xxx',
    candidateId: 1
);

// Get jobs by type
$jobs = app(JobService::class)->getJobsByType(
    jobType: 'WFH/Remote',
    batchId: 1,
    limit: 10
);
```

## Repository Usage (QueryBuilder only)

### ApplicationRepository
```php
use App\Repositories\ApplicationRepository;

$repo = app(ApplicationRepository::class);

// Find by ID
$apply = $repo->find($id);

// Find by job, batch, candidate
$apply = $repo->findByJobBatchAndCandidate($jobId, $batchId, $candidateId);

// Find by job UUID
$apply = $repo->findByJobUuidAndCandidate($jobUuid, $candidateId);

// Get by candidate paginated
$applies = $repo->getByCandidatePaginated($candidateId, perPage: 10);

// Create
$apply = $repo->create($data);

// Update
$repo->update($apply, $data);
```

### DocumentRepository
```php
use App\Repositories\DocumentRepository;

$repo = app(DocumentRepository::class);

// Find CVs by candidate
$cvs = $repo->findCVsByCandidate($candidateId);

// Get by candidate paginated
$docs = $repo->getByCandidatePaginated($candidateId, perPage: 10, type: 'CV');

// Get by category
$idDocs = $repo->getByCategory('IDENTITY')->get();

// Get by status
$verified = $repo->getByStatus('VERIFIED')->get();

// Get required documents
$required = $repo->getRequired()->get();

// Count verified
$count = $repo->countVerifiedByCandidate($candidateId);
```

### JobRepository
```php
use App\Repositories\JobRepository;

$repo = app(JobRepository::class);

// Find by UUID
$job = $repo->findByUuid($uuid);

// Get by batch paginated
$jobs = $repo->getByBatchPaginated($batchId, perPage: 15);

// Search with filters
$jobs = $repo->searchWithFilters(
    searchQuery: 'Dokter',
    categoryId: 2,
    jobType: 'Fulltime',
    batchId: 1,
    perPage: 15
);

// Get active jobs
$jobs = $repo->getActivePaginated(perPage: 15);
```

### CandidateRepository
```php
use App\Repositories\CandidateRepository;

$repo = app(CandidateRepository::class);

// Find with documents
$candidate = $repo->findWithDocuments($id);

// Find with identities
$candidate = $repo->findWithIdentities($id);

// Find with all completeness data
$candidate = $repo->findWithCompleteness($id);

// Get paginated
$candidates = $repo->getPaginated(perPage: 15);

// Search by name or email
$candidates = $repo->search('john')->get();

// By verification status
$verified = $repo->byIdentityVerificationStatus(true)->get();

// By document status
$complete = $repo->byDocumentCompletionStatus(true)->get();
```

## Migration Instructions

Untuk menjalankan migrations baru yang sudah dibuat:

```bash
# Run all pending migrations
php artisan migrate

# Atau specific migration
php artisan migrate --path=database/migrations/2025_01_15_000001_add_identity_completeness_to_candidates_table.php
```

## Controller Example

Berikut contoh bagaimana controller seharusnya diupdate:

```php
<?php

namespace App\Http\Controllers\Candidate\Jobs;

use App\Http\Controllers\Controller;
use App\Services\JobService;
use App\Services\ApplicationService;
use Illuminate\Http\Request;

class ApplicationController extends Controller
{
    public function __construct(
        private ApplicationService $applicationService,
        private JobService $jobService,
    ) {}

    public function store(Request $request, $uuid)
    {
        $validated = $request->validate([
            'cover_letter' => 'required|string',
            'description' => 'required|string',
            'type_of_document' => 'required|in:upload,select',
            'document_id' => 'required_if:type_of_document,select|integer',
            'file' => 'required_if:type_of_document,upload|file',
        ]);

        $result = $this->applicationService->submitApplication(
            jobUuid: $uuid,
            candidateId: auth('candidate')->id(),
            data: $validated,
            documentFile: $request->file('file'),
        );

        if (!$result['success']) {
            if ($result['already_applied'] ?? false) {
                return redirect()->route('candidate.my.applications.index')
                    ->with('warning', $result['message']);
            }
            return back()->withErrors($result['error']);
        }

        return redirect()->route('candidate.jobs.applications.success', $uuid)
            ->with('success', $result['message']);
    }
}
```

## Next Steps

1. Update semua Controllers untuk menggunakan Services
2. Replace semua calls ke Repository business logic methods dengan Service methods
3. Run migrations untuk database changes
4. Test semua fitur untuk memastikan tidak ada breaking changes
5. Lanjut dengan UI improvements (responsive, footer, coloring)
6. Add Laravel Tests

## Important Notes

- Semua Enum sudah cast di Model (DocumentStatus, ApplicationStatus, etc)
- Services bisa di-inject ke Controller via constructor
- Repository QueryBuilder methods bisa di-chain dengan Eloquent methods
- Business logic sekarang centralized di Services
- Models sudah memiliki helper methods (isVerified, markAsVerified, etc)
