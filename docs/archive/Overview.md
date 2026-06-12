# Klinik Keluarga Career Portal - Project Overview

## Daftar Isi
1. [Deskripsi Project](#deskripsi-project)
2. [Tech Stack](#tech-stack)
3. [Struktur Project](#struktur-project)
4. [Fitur Utama](#fitur-utama)
5. [Database Schema](#database-schema)
6. [Package Dependencies](#package-dependencies)
7. [Panduan Pengembangan](#panduan-pengembangan)

---

## Deskripsi Project

**Klinik Keluarga Career Portal** adalah platform web yang dirancang untuk mengelola proses rekrutmen dan manajemen karir di lingkungan Klinik Keluarga. Sistem ini terbagi menjadi dua portal utama:

- **Portal Admin**: Manajemen lowongan pekerjaan, kategori, batch interview, calon karyawan, dan penjadwalan interview
- **Portal Kandidat**: Pencarian lowongan, pengajuan lamaran, unggah dokumen, dan manajemen profil

---

## Tech Stack

### Backend
- **Framework**: Laravel 11.0
- **PHP Version**: 8.2+
- **Authentication**: Laravel Sanctum (API authentication)
- **ORM**: Eloquent ORM
- **Database**: MySQL/PostgreSQL (via migrations)

### Frontend
- **Asset Build**: Laravel Mix
- **Asset Bundler**: Webpack
- **JavaScript Package Manager**: npm

### Server & Deployment
- **Web Server**: PHP built-in server (development) / Apache/Nginx (production)
- **Containerization**: Docker & Docker Compose
- **Package Manager**: Composer (PHP), npm (Node.js)

---

## Struktur Project

### Direktori Utama

```
klinik_keluarga_career/
├── app/                          # Kode aplikasi utama
│   ├── Console/                  # Artisan commands
│   ├── Enums/                    # Enumeration classes
│   │   ├── DocumentType.php      # Tipe dokumen (CV, Ijazah, Sertifikat, dll)
│   │   └── JobType.php           # Tipe pekerjaan
│   ├── Exceptions/               # Custom exceptions
│   ├── Helpers/                  # Helper functions
│   │   ├── asset_helpers.php     # Asset URL helpers
│   │   └── helpers.php           # Global utility functions
│   ├── Http/
│   │   ├── Controllers/          # Route controllers
│   │   │   ├── Admin/            # Admin controllers
│   │   │   │   ├── DashboardController.php
│   │   │   │   ├── JobManagementController.php
│   │   │   │   ├── BatchController.php
│   │   │   │   ├── CategoryController.php
│   │   │   │   ├── CandidateController.php
│   │   │   │   ├── ApplicantController.php
│   │   │   │   ├── ScheduleInterviewController.php
│   │   │   │   └── LoginController.php
│   │   │   └── Candidate/        # Candidate controllers
│   │   │       ├── AuthController.php
│   │   │       ├── HomeController.php
│   │   │       ├── DocumentController.php
│   │   │       └── Jobs/
│   │   │           ├── VacancyController.php
│   │   │           └── ApplicationController.php
│   │   ├── Kernel.php            # HTTP middleware configuration
│   │   ├── Middleware/           # Custom middleware
│   │   └── Requests/             # Form request validation
│   ├── Models/                   # Eloquent models
│   │   ├── User.php              # Admin user model
│   │   ├── Candidate.php         # Job candidate model
│   │   ├── Job.php               # Job listing model
│   │   ├── Apply.php             # Job application model
│   │   ├── Batch.php             # Batch/wave of interviews
│   │   ├── Category.php          # Job category
│   │   ├── Company.php           # Company information
│   │   ├── Document.php          # Candidate documents (CV, etc)
│   │   ├── CV.php                # CV management
│   │   └── ScheduleInterview.php # Interview scheduling
│   ├── Notifications/            # Email notification classes
│   │   ├── ActivationEmailNotification.php
│   │   ├── ApplicationStatusUpdatedNotification.php
│   │   ├── ApplicationSubmittedNotification.php
│   │   └── InterviewInvitationNotification.php
│   ├── Providers/                # Service providers
│   ├── Repositories/             # Data access layer
│   │   ├── ApplicationRepository.php
│   │   ├── BatchRepository.php
│   │   ├── CandidateRepository.php
│   │   ├── CategoryRepository.php
│   │   ├── DocumentRepository.php
│   │   ├── HomeRepository.php
│   │   └── JobRepository.php
│   └── Console/
│       └── Kernel.php            # Console command configuration
│
├── bootstrap/                    # Bootstrap files
├── config/                       # Configuration files
│   ├── app.php                   # Application configuration
│   ├── auth.php                  # Authentication guards
│   ├── database.php              # Database configuration
│   ├── mail.php                  # Email configuration
│   ├── filesystems.php           # File storage configuration
│   ├── session.php               # Session configuration
│   └── view.php                  # Blade template options
├── database/
│   ├── factories/                # Model factories for testing
│   ├── migrations/               # Database migrations
│   └── seeders/                  # Database seeders
├── public/                       # Web root directory
│   ├── index.php                 # Entry point
│   └── assets/                   # Static assets (CSS, JS, images)
├── resources/
│   ├── css/                      # CSS source files
│   ├── js/                       # JavaScript source files
│   ├── views/                    # Blade templates
│   │   ├── candidate/            # Candidate portal views
│   │   ├── admin/                # Admin portal views
│   │   └── layouts/              # Layout templates
│   └── lang/                     # Language localization files
├── routes/
│   ├── web.php                   # Web routes
│   ├── api.php                   # API routes
│   ├── channels.php              # Broadcasting channels
│   └── console.php               # Console commands
├── storage/
│   ├── app/                      # Application storage (uploads, documents)
│   ├── framework/                # Framework generated files
│   └── logs/                     # Application logs
├── tests/                        # Test files
│   ├── Feature/                  # Feature tests
│   ├── Unit/                     # Unit tests
│   └── TestCase.php              # Base test case
├── vendor/                       # Composer dependencies
├── docker-compose.yml            # Docker compose configuration
├── Dockerfile                    # Docker image configuration
├── artisan                       # Laravel CLI command
├── composer.json                 # PHP dependencies
├── package.json                  # Node.js dependencies
├── webpack.mix.js                # Laravel Mix build configuration
└── README.md                     # Project documentation
```

---

## Fitur Utama

### 1. **Portal Admin**

#### Dashboard
- Tampilan ringkasan statistik rekrutmen
- Quick stats: jumlah lowongan, aplikasi, kandidat

#### Manajemen Lowongan Pekerjaan
- Buat, edit, hapus lowongan pekerjaan
- Tentukan kategori, tipe pekerjaan, batch
- Daftar lowongan dengan filter dan pencarian
- DataTables integration untuk view data interaktif

#### Manajemen Kategori
- Buat kategori pekerjaan baru
- Edit dan hapus kategori
- Aktif/nonaktifkan kategori
- DataTables integration

#### Manajemen Batch/Wave Interview
- Buat batch interview (gelombang)
- Set tanggal dan jadwal interview
- Aktif/nonaktifkan batch
- DataTables integration

#### Manajemen Kandidat
- Lihat daftar semua kandidat yang terdaftar
- Lihat detail profil kandidat
- DataTables dengan search dan filter

#### Manajemen Aplikasi/Lamaran
- Lihat semua aplikasi yang masuk
- Update status aplikasi (pending, diterima, ditolak)
- Detail view untuk setiap aplikasi
- DataTables integration

#### Penjadwalan Interview
- Jadwalkan interview untuk kandidat yang lolos
- Kirim undangan interview via email
- Atur batch dan tanggal interview
- DataTables integration

#### Autentikasi Admin
- Login admin dengan username/password
- Logout functionality

### 2. **Portal Kandidat**

#### Autentikasi Kandidat
- Registrasi akun baru
- Verifikasi email
- Login dengan email/password
- Password reset

#### Beranda/Dashboard
- Tampilan ringkasan profil
- Statistik aplikasi dan interview

#### Pencarian & Filter Lowongan
- Search lowongan berdasarkan keyword
- Filter berdasarkan tipe pekerjaan (Full-time, Part-time, Kontrak, Magang)
- Filter berdasarkan kategori pekerjaan
- Pagination hasil pencarian
- Perubahan per halaman (5, 10, 25 items)
- AJAX-based filtering tanpa reload halaman
- History pencarian dalam URL query string

#### Detail Lowongan
- Lihat detail lengkap lowongan
- Tombol untuk melamar pekerjaan

#### Pengajuan Lamaran
- Submit lamaran untuk lowongan
- Upload/select CV
- Unggah dokumen pendukung
- Konfirmasi aplikasi

#### Manajemen Dokumen
- Unggah CV
- Unggah dokumen tambahan (ijazah, sertifikat, dll)
- View daftar dokumen
- Download dokumen
- Hapus dokumen

#### Undangan Interview
- Lihat daftar undangan interview
- Detail jadwal interview
- Konfirmasi kehadiran

#### Notifikasi
- Email notifikasi untuk berbagai events:
  - Aktivasi akun
  - Lamaran terima/ditolak
  - Undangan interview
  - Update status aplikasi

---

## Database Schema

### Tables Utama

#### Users (Admin)
```
- id (PK)
- email (unique)
- password
- name
- created_at, updated_at
```

#### Candidates
```
- id (PK)
- email (unique)
- password
- name
- phone
- address
- birth_date
- gender
- is_verified
- created_at, updated_at
```

#### Categories
```
- id (PK)
- name
- slug
- description
- is_active
- created_at, updated_at
```

#### JobTypes (Enum)
```
- FULL_TIME: Full-time position
- PART_TIME: Part-time position
- CONTRACT: Contract/temporary
- INTERNSHIP: Internship/magang
```

#### Jobs
```
- id (PK)
- title
- slug
- description
- category_id (FK)
- type (enum: FULL_TIME, PART_TIME, CONTRACT, INTERNSHIP)
- salary_min
- salary_max
- requirements
- batch_id (FK)
- is_active
- created_at, updated_at
```

#### Batches (Interview Waves)
```
- id (PK)
- name
- start_date
- end_date
- is_active
- created_at, updated_at
```

#### Applies (Job Applications)
```
- id (PK)
- job_id (FK)
- candidate_id (FK)
- cv_id (FK nullable)
- status (pending, accepted, rejected)
- applied_at
- created_at, updated_at
```

#### CVs
```
- id (PK)
- candidate_id (FK)
- file_path
- original_name
- created_at, updated_at
```

#### Documents (Dokumen Pendukung)
```
- id (PK)
- candidate_id (FK)
- type (enum: DocumentType)
- file_path
- original_name
- created_at, updated_at
```

#### ScheduleInterviews
```
- id (PK)
- job_id (FK)
- candidate_id (FK)
- batch_id (FK)
- apply_id (FK)
- interview_date
- interview_time
- location
- status (pending, scheduled, done, cancelled)
- created_at, updated_at
```

#### Companies
```
- id (PK)
- name
- email
- phone
- address
- description
- created_at, updated_at
```

---

## Package Dependencies

### PHP Packages (composer.json)

#### Production Dependencies
| Package | Version | Deskripsi |
|---------|---------|-----------|
| `laravel/framework` | ^11.0 | Core Laravel framework |
| `laravel/sanctum` | ^4.0 | API authentication |
| `laravel/tinker` | ^2.0 | Interactive shell |
| `guzzlehttp/guzzle` | ^7.0.1 | HTTP client |
| `yajra/laravel-datatables-oracle` | ^11.0 | DataTables integration |

#### Development Dependencies
| Package | Version | Deskripsi |
|---------|---------|-----------|
| `spatie/laravel-ignition` | ^2.0 | Error debugging |
| `fakerphp/faker` | ^1.9.1 | Fake data generator |
| `mockery/mockery` | ^1.4 | Mocking library |
| `nunomaduro/collision` | ^8.0 | Collision error handler |
| `phpunit/phpunit` | ^11.0 | Unit testing framework |

### JavaScript Packages (package.json)

#### Dependencies
| Package | Version | Deskripsi |
|---------|---------|-----------|
| `axios` | ^0.19 | HTTP client |
| `lodash` | ^4.17.19 | Utility library |

#### Dev Dependencies
| Package | Version | Deskripsi |
|---------|---------|-----------|
| `cross-env` | ^7.0 | Cross-platform env vars |
| `laravel-mix` | ^5.0.1 | Build tool |
| `resolve-url-loader` | ^3.1.0 | Resolve URLs in CSS |

### External Libraries (Frontend)
- **Bootstrap 4/5**: CSS framework (via Blade templates)
- **jQuery**: DOM manipulation (likely in views)
- **Font Awesome**: Icon library
- **Select2**: Enhanced select dropdown
- **DataTables**: Advanced table plugin

---

## Panduan Pengembangan

### Environment Setup

1. **Copy .env file**
   ```bash
   cp .env.example .env
   ```

2. **Generate App Key**
   ```bash
   php artisan key:generate
   ```

3. **Database Configuration**
   Edit `.env` untuk mengatur database:
   ```
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=klinik_career
   DB_USERNAME=root
   DB_PASSWORD=
   ```

4. **Run Migrations**
   ```bash
   php artisan migrate
   ```

5. **Seed Database (Optional)**
   ```bash
   php artisan db:seed
   ```

### Development Commands

```bash
# Start development server
php artisan serve

# Watch for CSS/JS changes
npm run watch

# Compile assets
npm run dev          # Development
npm run production   # Production

# Run tests
php artisan test
php ./vendor/bin/phpunit

# Database commands
php artisan migrate          # Run migrations
php artisan migrate:fresh    # Fresh migration
php artisan db:seed          # Run seeders
php artisan tinker           # Interactive shell

# Create new resources
php artisan make:model Model
php artisan make:controller ControllerName
php artisan make:migration create_table
php artisan make:seeder TableSeeder
```

### Code Organization

- **Controllers**: Letakkan logic bisnis di repository, controllers hanya untuk routing
- **Models**: Define relationships dan accessors/mutators
- **Repositories**: Data access logic (DRY principle)
- **Views**: Blade templates di `resources/views`
- **Routes**: Define routes di `routes/web.php` atau `routes/api.php`

### Testing

```bash
# Run semua tests
php artisan test

# Run specific test file
php artisan test tests/Feature/AuthTest.php

# Run with coverage
php artisan test --coverage
```

### Debugging

- Check logs di `storage/logs/`
- Gunakan `dd()` untuk debug
- Aktifkan `APP_DEBUG=true` di `.env` untuk development

---

## Notes Tambahan

- Menggunakan **Repository Pattern** untuk akses data
- **AJAX-based filtering** untuk UX yang lebih baik (tidak perlu reload halaman)
- **Email notifications** untuk event-event penting
- **DataTables** untuk display data yang besar dengan search/sort/pagination
- **Blade templating** untuk frontend
- Support untuk **multiple authentication guards** (admin dan candidate terpisah)

---

**Last Updated**: May 31, 2026
