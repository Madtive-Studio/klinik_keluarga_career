<p align="center">
  <img src="public/assets/logo/letter-logo.png" width="320" alt="Klinik Keluarga Career Logo">
</p>

<h1 align="center">Klinik Keluarga Career</h1>

<p align="center">
  <strong>Sistem E-Recruitment & Talent Acquisition Resmi Klinik Keluarga</strong><br>
  Platform digital terpadu untuk publikasi lowongan kerja, pengelolaan berkas kandidat medis/umum, penilaian kualifikasi otomatis (Auto-Scoring Engine), dan penjadwalan wawancara kerja.
</p>

<p align="center">
  <img src="https://img.shields.io/badge/Laravel-11.x-FF2D20?style=for-the-badge&logo=laravel&logoColor=white" alt="Laravel 11">
  <img src="https://img.shields.io/badge/PHP-8.3-777BB4?style=for-the-badge&logo=php&logoColor=white" alt="PHP 8.3">
  <img src="https://img.shields.io/badge/PostgreSQL-16-4169E1?style=for-the-badge&logo=postgresql&logoColor=white" alt="PostgreSQL">
  <img src="https://img.shields.io/badge/Bootstrap-5.3-7952B3?style=for-the-badge&logo=bootstrap&logoColor=white" alt="Bootstrap 5">
  <img src="https://img.shields.io/badge/Pest_PHP-v3-9333EA?style=for-the-badge&logo=php&logoColor=white" alt="Pest Testing">
  <img src="https://img.shields.io/badge/Tests-123_Passed-success?style=for-the-badge" alt="Automated Tests">
</p>

---

## 📌 Tentang Proyek

**Klinik Keluarga Career** adalah sistem rekrutmen daring (*Applicant Tracking System / ATS*) yang dikembangkan untuk memodernisasi dan mengoptimasi proses penerimaan karyawan—khususnya tenaga medis (dokter, perawat, apoteker, bidan) dan staf operasional—di fasilitas kesehatan **Klinik Keluarga**.

Sistem ini memfasilitasi integrasi dua arah:
1. **Portal Publik & Pelamar (*Candidate Area*)**: Memudahkan pencari kerja mencari posisi lowongan, melengkapi data profil/pendidikan, mengunggah berkas CV/STR, melamar pekerjaan secara daring, serta memantau status kelulusan seleksi.
2. **Portal HRD & Administrator (*Admin Area*)**: Membantu tim manajemen rekrutmen mengelola master gelombang (*batch*), mempublikasikan lowongan, mengevaluasi berkas pelamar berbantukan kalkulasi skor otomatis (*Auto-Scoring Engine*), serta menjadwalkan sesi interview dengan notifikasi surel terintegrasi.

---

## 🌟 Fitur Utama Sistem

### 👤 Portal Pelamar (Candidate Area)
* **Dukungan Dwi-Bahasa (*Bilingual*)**: Fitur pergantian bahasa dinamis (*Language Switcher*) antara Bahasa Indonesia (ID) dan Bahasa Inggris (EN).
* **Bilah Pengumuman Resmi (*Top Announcement Ticker*)**: Informasi pengumuman rekrutmen berjalan (*fraud alert* bebas biaya & panduan pendaftaran).
* **Autentikasi & Verifikasi Email**: Registrasi akun pelamar dengan aktivasi token email resmi demi keamanan data.
* **Manajemen Profil Lengkap**: Biodata diri, riwayat pendidikan, IPK, pengalaman kerja, portofolio keahlian (*skills*), dan rentang ekspektasi gaji.
* **Repositori Dokumen Digital**: Pengunggahan berkas pendukung (CV, STR, Ijazah, Transkrip, Sertifikat) dengan fitur pratinjau langsung (*Live Preview* untuk PDF, Gambar, CSV, dan format dokumen lainnya).
* **Pencarian & Pelamaran Kerja**: Filter lowongan berdasarkan kategori, tipe pekerjaan (Full Time / Part Time), rentang gaji, serta pengajuan surat lamaran (*Cover Letter*).
* **Riwayat & Pelacakan Seleksi (*Application Tracking*)**: Pemantauan tahapan status lamaran secara transparan (*In Review*, *Shortlisted*, *Not Suitable*, *Hired*) beserta jadwal interview yang didapatkan.

### 🏢 Portal HRD / Admin (Admin Area)
* **Dashboard Statistik & Analisis**: Ringkasan data gelombang aktif, kuota pendaftar, pelamar diterima, serta visualisasi grafik pendaftaran 12 bulan terakhir.
* **Manajemen Gelombang Rekrutmen (*Batch Management*)**: Pengaturan tanggal mulai, batas akhir pendaftaran, kuota kandidat, serta status aktivasi gelombang.
* **Manajemen Kategori & Lowongan**: Pembuatan rincian kualifikasi lowongan, benefit, rentang gaji, batas kuota, serta pengaturan bobot kriteria kualifikasi.
* **Mesin Penilaian Otomatis (*Auto-Scoring Engine*)**:
  * Menghitung skor kelayakan pelamar secara objektif (skala 0–100) berdasarkan 5 parameter:
    1. Kesesuaian Jenjang Pendidikan Minimal
    2. Kesesuaian Total Pengalaman Kerja
    3. Kecocokan Keahlian Wajib (*Required Skills Match*)
    4. Kelengkapan Profil & Data Diri
    5. Kelengkapan Berkas Dokumen Pendukung & Surat Lamaran
  * Memberikan rekomendasi otomatis (*Recommended / Review / Not Suitable*) untuk membantu tim HRD.
* **Direktori Akun Pelamar (*Talent Pool Directory*)**: Pencarian basis data seluruh kandidat terdaftar dengan tampilan *Dossier Evaluasi Profil*.
* **Peninjauan Lamaran & Evaluasi Seleksi**: Penelaahan berkas lamaran per posisi, pengubahan status seleksi, dan penulisan catatan review.
* **Penjadwalan Wawancara (*Interview Scheduler*)**:
  * Pengaturan tanggal, waktu, format (Daring via Zoom/Google Meet atau Luring/Tatap Muka di klinik), serta pewawancara.
  * **Filter Rentang Tanggal (*Date Range Filter*)** untuk pemantauan jadwal interview.
  * **Integrasi Surel Otomatis**: Pengiriman email undangan resmi wawancara (*Interview Invitation Notification*) ke kotak masuk pelamar.

---

## 🛠️ Arsitektur & Tumpukan Teknologi (*Tech Stack*)

| Komponen | Teknologi yang Digunakan |
| :--- | :--- |
| **Bahasa Pemrograman** | PHP 8.3 (Server-Side Scripting) |
| **Framework Backend** | Laravel 11.x (Model-View-Controller) |
| **Basis Data (RDBMS)** | PostgreSQL 16 (Dikelola dengan DBeaver) |
| **Template Engine** | Blade UI Template Engine |
| **Framework CSS & UI** | Bootstrap 5.3, Sneat Admin Template, Material Design Icons |
| **Library Interaktif** | jQuery, DataTables (Server-side processing), Flatpickr, SweetAlert2, Quill.js |
| **Testing Framework** | Pest PHP v3 & PHPUnit 11 (Automated Feature & Unit Testing) |
| **Sistem Kontrol Versi** | Git & GitHub |

---

## 🚀 Panduan Instalasi & Menjalankan Lokal

Ikuti langkah-langkah berikut untuk menjalankan proyek di lingkungan pengembangan lokal:

### 1. Prasyarat Sistem
Pastikan perangkat Anda telah terpasang:
* **PHP >= 8.2** (ekstensi `pdo_pgsql`, `pgsql`, `mbstring`, `openssl`, `curl`, `gd`, `fileinfo` aktif)
* **Composer >= 2.x**
* **PostgreSQL >= 14**
* **Git**

### 2. Kloning Repositori
```bash
git clone https://github.com/Madtive-Studio/klinik_keluarga_career.git
cd klinik_keluarga_career
```

### 3. Instalasi Dependensi
```bash
composer install
```

### 4. Konfigurasi Lingkungan (`.env`)
Salin berkas konfigurasi sampel dan buat kunci enkripsi aplikasi:
```bash
cp .env.example .env
php artisan key:generate
```

Sesuaikan konfigurasi basis data PostgreSQL pada berkas `.env`:
```env
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=klinik_keluarga_career
DB_USERNAME=postgres
DB_PASSWORD=your_password
```

### 5. Migrasi & Seeding Basis Data
Jalankan migrasi skema tabel beserta data awal (*dummy data / master data*):
```bash
php artisan migrate --seed
```

### 6. Pembuatan Tautan Simbolik Media
Hubungkan direktori penyimpanan dokumen digital (*storage symlink*):
```bash
php artisan storage:link
```

### 7. Menjalankan Rangkaian Uji Otomatis (*Test Suite*)
Pastikan seluruh 123 pengujian otomatis berjalan dengan sukses:
```bash
php artisan test
```

### 8. Menjalankan Server Lokal
```bash
php artisan serve
```
Akses aplikasi melalui peramban web di: `http://127.0.0.1:8000`

---

## 🔑 Kredensial Akun Pengujian (*Default Users*)

| Peran Pengguna | URL Masuk | Email | Kata Sandi |
| :--- | :--- | :--- | :--- |
| **HRD / Administrator** | `/admin/login` *(atau via link footer)* | `admin@klinikkeluarga.com` | `password` |
| **Pelamar Terdaftar** | `/login` | `pelamar@klinikkeluarga.com` | `password` |

---

## 📁 Struktur Direktori Utama

```
klinik_keluarga_career/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Admin/         # Controller Panel HRD (Jobs, Applies, Candidates, Interviews)
│   │   │   └── Candidate/     # Controller Portal Pelamar (Auth, Profile, Vacancies, Applies)
│   │   └── Middleware/        # SetLocale, Role Authentication
│   ├── Models/                # Model Eloquent (Job, Candidate, Apply, Batch, Category, Document)
│   ├── Notifications/         # Email Notifikasi (InterviewInvitationNotification, VerifyEmail)
│   ├── Repositories/          # Layer Akses Data & Kueri Bisnis
│   └── Services/              # Logika Bisnis (ScoringService, JobImageService, HomeService)
├── database/
│   ├── migrations/            # Skema Tabel Basis Data
│   └── seeders/               # Data Awal Master & Akun Pengujian
├── public/                    # Aset Publik (CSS, JS, Gambar, Logo, Diagram UML)
├── resources/
│   ├── lang/                  # File Terjemahan Dwibahasa (ID & EN)
│   └── views/
│       ├── admin/             # Tampilan Antarmuka Panel HRD
│       └── candidate/         # Tampilan Antarmuka Portal Pelamar
├── routes/
│   └── web.php                # Definisi Rute Aplikasi
└── tests/
    ├── Feature/               # Feature Test Endpoint & Controller (Pest/PHPUnit)
    └── Unit/                  # Unit Test Scoring Service & Repository Logic
```

---

## 📄 Hak Cipta & Lisensi

Dikembangkan untuk **Klinik Keluarga** bekerjasama dengan **PT Madtive Studio**.  
Hak Cipta © 2026 Klinik Keluarga. Seluruh hak cipta dilindungi undang-undang.
