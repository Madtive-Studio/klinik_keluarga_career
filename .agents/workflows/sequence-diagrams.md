---
description: # SKILL: Generate Sequence Diagram (PlantUML)
---

# Untuk Laporan KP - Klinik Keluarga Career

## TUJUAN
Generate sequence diagram dalam format PlantUML berdasarkan use case yang ada,
dengan style dan struktur yang konsisten untuk laporan KP.

---

## KONTEKS PROJECT
- Aplikasi: Klinik Keluarga Career (E-Recruitment)
- Framework: Laravel 11 (MVC Architecture)
- Dua aktor utama: Pelamar (Candidate) dan HRD (Admin)
- Struktur MVC: Page (View/Blade) → Controller → Model → Database

---

## ATURAN LIFELINE

Gunakan 4 lifeline standar sesuai arsitektur MVC Laravel:

```
Actor       → Aktor (Pelamar atau HRD)
Boundary    → Page/View (Blade template, contoh: "LoginPage", "VacancyPage")
Control     → Controller (contoh: "AuthController", "JobController")
Entity      → Model/Database (contoh: "CandidateModel", "JobModel")
```

Notasi PlantUML:
```plantuml
actor "Pelamar" as Pelamar
boundary "NamaPage" as Page
control "NamaController" as Controller
entity "NamaModel" as Model
```

---

## TEMPLATE DASAR

```plantuml
@startuml
skinparam defaultFontName Arial
skinparam defaultFontSize 10
skinparam shadowing false
skinparam sequenceArrowThickness 1
skinparam sequenceParticipantBorderThickness 1
skinparam sequenceLifeLineBorderColor #000000
skinparam sequenceLifeLineBorderThickness 1
skinparam sequenceGroupBorderThickness 1
skinparam sequenceGroupFontSize 10
skinparam sequenceMessageAlign left
skinparam responseMessageBelowArrow true
skinparam maxMessageSize 150
skinparam pageMargin 5

skinparam participant {
  BackgroundColor #7ACFF5
  BorderColor #000000
  FontSize 10
}

skinparam actor {
  BackgroundColor #FFFFFF
  BorderColor #000000
  FontSize 10
}

skinparam boundary {
  BackgroundColor #7ACFF5
  BorderColor #000000
  FontSize 10
}

skinparam control {
  BackgroundColor #7ACFF5
  BorderColor #000000
  FontSize 10
}

skinparam entity {
  BackgroundColor #7ACFF5
  BorderColor #000000
  FontSize 10
}

title Nama Use Case

' Deklarasi lifeline
actor "NamaAktor" as Aktor
boundary "NamaPage" as Page
control "NamaController" as Controller
entity "NamaModel" as Model

' Alur utama (gunakan sd untuk label grup)
group sd View/Read
  Aktor -> Page : 1. Akses halaman
  Page -> Controller : 1.1 Request data
  Controller -> Model : 1.1.1 getData()
  Model --> Controller : 1.1.2 Result
  Controller --> Page : 1.2 Return data
  Page --> Aktor : 1.3 Tampilkan data
end

' Gunakan alt untuk kondisi sukses/gagal
group sd Create
  Aktor -> Page : 2. Klik tombol tambah
  Page --> Aktor : 2.1 Tampilkan form
  Aktor -> Page : 3. Isi form
  Page -> Controller : 3.1 Kirim data form
  Controller -> Model : 3.1.1 createData()
  Model --> Controller : 3.1.2 Result

  alt [Success]
    Controller --> Page : 3.2 Return Message ("Success")
    Page --> Aktor : 3.3 Tampilkan notifikasi sukses
  else [Fail]
    Controller --> Page : 3.4 Return Message ("Failed")
    Page --> Aktor : 3.5 Tampilkan pesan error
  end
end

@enduml
```

---

## ATURAN PENOMORAN PESAN

Gunakan penomoran hierarkis seperti contoh berikut:
```
1.      → Aksi utama dari aktor
1.1     → Response/request pertama dari sistem
1.1.1   → Interaksi ke layer berikutnya (Controller → Model)
1.1.2   → Return/result dari layer tersebut
1.2     → Response balik ke aktor
```

Untuk grup baru (Create, Update, Delete), lanjutkan nomor:
```
2.      → Aksi aktor untuk Create
3.      → Lanjutan aksi dalam Create (isi form, dll)
4.      → Aksi aktor untuk Update
5.      → Lanjutan aksi dalam Update
6.      → Aksi aktor untuk Delete
```

---

## ATURAN GRUP

Gunakan label grup sesuai operasi CRUD:
```plantuml
group sd View/Read
  ...
end

group sd Create
  ...
end

group sd Update
  ...
end

group sd Delete
  ...
end
```

Gunakan `alt` untuk kondisi sukses/gagal di dalam grup:
```plantuml
alt [Success]
  Controller --> Page : Return Message ("Success ...")
else [Fail]
  Controller --> Page : Return Message ("Failed ...")
end
```

---

## DAFTAR USE CASE & LIFELINE

Gunakan referensi ini saat generate diagram:

### Sisi Pelamar
| Use Case | Page | Controller | Model |
|---|---|---|---|
| Register Akun | RegisterPage | AuthController | CandidateModel |
| Login | LoginPage | AuthController | CandidateModel |
| Kelola Profil | ProfilePage | ProfileController | CandidateModel |
| Kelola Dokumen | DocumentPage | DocumentController | DocumentModel |
| Mencari Lowongan | VacancyPage | VacancyController | JobModel |
| Melamar Pekerjaan | VacancyPage | ApplicationController | ApplyModel |
| Melihat Riwayat Lamaran | ApplicationPage | ApplicationController | ApplyModel |

### Sisi HRD
| Use Case | Page | Controller | Model |
|---|---|---|---|
| Login HRD | LoginPage | AuthController | UserModel |
| Kelola Batch | BatchPage | BatchController | BatchModel |
| Kelola Kategori | CategoryPage | CategoryController | CategoryModel |
| Kelola Lowongan | JobPage | JobController | JobModel |
| Melihat Informasi Pelamar | CandidatePage | CandidateController | CandidateModel |
| Meninjau Lamaran | ApplicationPage | ApplicantController | ApplyModel |
| Menjadwalkan Wawancara | SchedulePage | ScheduleController | ScheduleModel |
| Kirim Undangan Wawancara | SchedulePage | ScheduleController | ScheduleModel |

---

## CARA PAKAI (Instruksi untuk Agent Code)

Saat diminta generate sequence diagram, lakukan langkah berikut:

1. Baca file ini (sequence-diagrams.md) terlebih dahulu
2. Identifikasi use case yang diminta
3. Tentukan lifeline yang relevan dari tabel di atas
4. Baca controller yang relevan di `app/Http/Controllers/`
5. Baca model yang relevan di `app/Models/`
6. Generate PlantUML mengikuti template dan aturan penomoran di atas
7. Simpan output ke file `{NamaUseCase}_Sequence.puml`


---

## CATATAN PENTING

- Panah solid `->` untuk request/aksi
- Panah putus `-->` untuk response/return
- Jangan tampilkan detail implementasi kode, cukup nama method
- Nama method mengikuti yang ada di Controller dan Model
- Maksimal kedalaman penomoran: 3 level (1.1.1)
- Satu file PlantUML per use case, jangan digabung kecuali CRUD dalam 1 halaman