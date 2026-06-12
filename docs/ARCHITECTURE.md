# Arsitektur Project

Project ini menggunakan **pure MVC** (Laravel 11) tanpa Repository/Service layer.

## Struktur

```
app/
├── Enums/              # ApplicationStatus, DocumentType, dll.
├── Http/
│   ├── Controllers/    # Admin/ & Candidate/
│   ├── Middleware/
│   ├── Requests/       # Form Request validation
│   └── Resources/      # JSON Resource untuk response AJAX
├── Models/             # Eloquent + query scopes
└── Notifications/

resources/views/errors/ # Halaman error (403, 404, 419, 429, 500, 503)

tests/
├── Feature/            # Pest feature tests
└── Unit/               # Pest unit tests
```

## Pola MVC

| Layer | Tanggung jawab |
|---|---|
| **Controller** | HTTP request/response, redirect, view |
| **Form Request** | Validasi input |
| **Model** | Relasi database, query scopes, business helper |
| **Resource** | Transform data JSON (AJAX listing) |
| **Enum** | Status & tipe terstruktur |

## Error Handling

Fallback error di `bootstrap/app.php`:
- Request web → blade `resources/views/errors/{code}.blade.php`
- Request JSON/AJAX → response JSON dengan `message`

## Testing

```bash
composer test
# atau
./vendor/bin/pest
```

Database test: PostgreSQL (`klinik_keluarga_career_test`) — konfigurasi di `phpunit.xml`.
