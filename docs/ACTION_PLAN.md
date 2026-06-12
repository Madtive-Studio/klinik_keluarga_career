# ACTION PLAN - Controller Update & Completion

## Phase 1: Migrations & Database Setup

### Step 1.1: Review Migrations
```
Files created:
- database/migrations/2025_01_15_000001_add_identity_completeness_to_candidates_table.php
- database/migrations/2025_01_15_000002_create_candidate_identities_table.php
- database/migrations/2025_01_15_000003_add_document_completeness_to_documents_table.php

Review content and ensure correct before running.
```

### Step 1.2: Run Migrations
```bash
# Backup database first (production only)
# Then run:
php artisan migrate

# Check if successful
php artisan migrate:status
```

### Step 1.3: Verify Database
- Check `candidates` table for new columns
- Check `candidate_identities` table exists
- Check `documents` table has new columns

---

## Phase 2: Core Controller Updates (High Priority)

### Controller Update Template
```php
// BEFORE: Using thick repository
class HomeController extends Controller {
    public function __construct(private HomeRepository $repo) {}
    public function home() {
        $data = $this->repo->getHomeDisplayData($jobTypes); // Business logic in repo
        return view('home', $data);
    }
}

// AFTER: Using service layer
class HomeController extends Controller {
    public function __construct(private JobService $jobService) {}
    public function home() {
        $data = $this->jobService->getHomePageData($jobTypes); // Business logic in service
        return view('home', $data);
    }
}
```

### Priority 1: Candidate\DocumentController
**File**: `app/Http/Controllers/Candidate/DocumentController.php`
**Changes Required**:
- [ ] Inject DocumentService instead of DocumentRepository
- [ ] Replace all document upload logic with DocumentService
- [ ] Update document deletion to use DocumentService
- [ ] Update document retrieval to use DocumentRepository (query only)
- [ ] Add validation for document types and sizes

**Methods to Update**:
- upload() → Use DocumentService::uploadDocument()
- store() → Use DocumentService::uploadDocument()
- delete() → Use DocumentService::deleteDocument()
- show/index → Use DocumentRepository directly

### Priority 2: Candidate\HomeController
**File**: `app/Http/Controllers/Candidate/HomeController.php`
**Changes Required**:
- [ ] Replace HomeRepository with JobService
- [ ] Replace CategoryRepository::getAll() with CategoryRepository::getAllOrdered()
- [ ] Update batch retrieval to use BatchRepository::getActive()
- [ ] Format batch label in controller (moved from repo)

### Priority 3: Candidate\Jobs\ApplicationController
**File**: `app/Http/Controllers/Candidate/Jobs/ApplicationController.php`
**Changes Required**:
- [ ] Inject ApplicationService instead of ApplicationRepository & DocumentRepository
- [ ] Replace submitApplication logic with ApplicationService::submitApplication()
- [ ] Update success response handling

### Priority 4: Candidate\Jobs\VacancyController
**File**: `app/Http/Controllers/Candidate/Jobs/VacancyController.php`
**Changes Required**:
- [ ] Inject JobService instead of JobRepository
- [ ] Replace vacancy detail logic with JobService::getVacancyDetail()
- [ ] Replace apply form logic with JobService::getApplyFormData()

### Priority 5: Admin\ApplicantController
**File**: `app/Http/Controllers/Admin/ApplicantController.php`
**Changes Required**:
- [ ] Inject ApplicationService
- [ ] Replace update logic with ApplicationService::updateStatus()
- [ ] Update status label mapping

---

## Phase 3: UI/UX Improvements

### Task A: Mobile Responsiveness (Priority: HIGH)
**Estimated Time**: 4-6 hours

**Views to Check**:
- [ ] resources/views/candidate/*.blade.php
- [ ] resources/views/admin/*.blade.php
- [ ] resources/views/layouts/*.blade.php

**Common Issues to Fix**:
1. Grid columns not responsive
2. Navigation menu not mobile-friendly
3. Tables overflow on small screens
4. Forms too wide for mobile
5. Images not responsive

**Tools to Use**:
```bash
# Install responsive design checker
npm install responsive-viewer --save-dev

# Or use browser DevTools to test mobile views
```

**Steps**:
1. Test each view on mobile (375px, 768px widths)
2. Fix CSS grid/flex layout
3. Add mobile menu toggle if needed
4. Test form inputs on mobile
5. Check all buttons are clickable (min 44x44px)

### Task B: Footer Positioning (Priority: MEDIUM)
**Estimated Time**: 1-2 hours

**Current Issue**: Footer not fixed to bottom on short pages

**Solution**:
```css
/* Add to main layout CSS */
body {
    display: flex;
    flex-direction: column;
    min-height: 100vh;
}

main {
    flex: 1;
}

footer {
    position: relative;
    margin-top: auto;
}
```

**Files to Update**:
- [ ] resources/views/layouts/app.blade.php
- [ ] resources/views/layouts/admin.blade.php
- [ ] resources/css/app.css or resources/css/custom.css

### Task C: Text Coloring & Typography (Priority: LOW)
**Estimated Time**: 2-3 hours

**Actions**:
1. Audit current colors
2. Create color palette
3. Update CSS variables
4. Test contrast ratios (WCAG AA)
5. Update all pages

---

## Phase 4: Testing Setup

### Unit Tests for Services

**File Structure**:
```
tests/
├── Unit/
│   ├── Services/
│   │   ├── ApplicationServiceTest.php
│   │   ├── DocumentServiceTest.php
│   │   ├── CandidateServiceTest.php
│   │   └── JobServiceTest.php
│   └── Repositories/
│       ├── ApplicationRepositoryTest.php
│       └── DocumentRepositoryTest.php
```

### Creating First Test
```php
// tests/Unit/Services/ApplicationServiceTest.php
namespace Tests\Unit\Services;

use Tests\TestCase;
use App\Services\ApplicationService;
use App\Models\Candidate;
use App\Models\Job;

class ApplicationServiceTest extends TestCase
{
    public function test_can_submit_application()
    {
        // Setup
        $candidate = Candidate::factory()->create();
        $job = Job::factory()->create();
        
        // Execute
        $service = app(ApplicationService::class);
        $result = $service->submitApplication(
            jobUuid: $job->uuid,
            candidateId: $candidate->id,
            data: [...],
        );
        
        // Assert
        $this->assertTrue($result['success']);
    }
}
```

### Test Commands
```bash
# Run all tests
php artisan test

# Run specific test class
php artisan test tests/Unit/Services/ApplicationServiceTest.php

# Run with coverage
php artisan test --coverage

# Run and stop on first failure
php artisan test --bail
```

---

## Checklist for Completion

### Database
- [ ] Run migrations
- [ ] Verify all new columns/tables
- [ ] Backup data (if on production)

### Controllers (Critical)
- [ ] DocumentController updated
- [ ] HomeController updated
- [ ] ApplicationController updated
- [ ] VacancyController updated
- [ ] ApplicantController updated

### Controllers (Standard)
- [ ] BatchController (no changes needed, use repos)
- [ ] CategoryController (no changes needed, use repos)
- [ ] CandidateController (update to use repos)
- [ ] DashboardController (update to use repos)
- [ ] ScheduleInterviewController (update to use repos)

### UI/UX
- [ ] Mobile responsiveness tested on 375px
- [ ] Mobile responsiveness tested on 768px
- [ ] Footer sticky on all pages
- [ ] Text colors consistent
- [ ] No contrast issues

### Testing
- [ ] ApplicationService tests written
- [ ] DocumentService tests written
- [ ] CandidateService tests written
- [ ] All tests passing
- [ ] Code coverage > 70%

### Final
- [ ] All features working
- [ ] No console errors
- [ ] No console warnings
- [ ] Database queries optimized
- [ ] Ready for deployment

---

## Important Code Patterns

### Service Injection
```php
// In controller constructor
public function __construct(
    private ApplicationService $applicationService,
    private DocumentService $documentService,
) {}

// In methods
public function submit(Request $request) {
    $result = $this->applicationService->submitApplication(...);
}
```

### Repository QueryBuilder
```php
// DON'T use this anymore:
$apply = $this->applicationRepository->getApplicationsByCandidate($id);

// USE this instead:
$applies = $this->applicationRepository->getByCandidatePaginated($id);

// Repository methods return Builder or Model, not formatted data
```

### Model Casts
```php
// Enums are automatically cast
$apply->status;  // Returns ApplicationStatus enum
$apply->status->value;  // Get string value
$apply->status->getLabel();  // Get label
```

---

## Deployment Checklist

Before deploying to production:

1. **Pre-Deployment**
   - [ ] Run `composer install`
   - [ ] Run `npm install && npm run production`
   - [ ] Run `php artisan migrate` on staging
   - [ ] Run full test suite
   - [ ] Backup production database
   - [ ] Notify users of maintenance window

2. **Deployment**
   - [ ] Push code to production
   - [ ] Run `php artisan migrate`
   - [ ] Clear caches: `php artisan cache:clear`
   - [ ] Monitor error logs

3. **Post-Deployment**
   - [ ] Test all critical features
   - [ ] Check application status page
   - [ ] Monitor user reports
   - [ ] Review error logs
   - [ ] Update documentation

---

## Emergency Rollback

If issues occur after deployment:

```bash
# Rollback migrations
php artisan migrate:rollback

# Or specific migration
php artisan migrate:rollback --path=database/migrations/2025_01_15_000001_*.php

# Restore from backup
# (Use your backup tool/script)
```

---

## Support & Resources

### In Project:
- [REFACTORING_GUIDE.md](./REFACTORING_GUIDE.md) - Detailed service usage
- [REFACTORING_REPORT.md](./REFACTORING_REPORT.md) - Full progress report
- [Overview.md](./Overview.md) - Project architecture

### Enum Reference:
- [ApplicationStatus](./app/Enums/ApplicationStatus.php)
- [DocumentStatus](./app/Enums/DocumentStatus.php)
- [DocumentCategory](./app/Enums/DocumentCategory.php)
- [DocumentType](./app/Enums/DocumentType.php)

### Service Files:
- [ApplicationService](./app/Services/ApplicationService.php)
- [DocumentService](./app/Services/DocumentService.php)
- [CandidateService](./app/Services/CandidateService.php)
- [JobService](./app/Services/JobService.php)

---

**Last Updated**: 2026-06-02
**Status**: Ready for implementation
