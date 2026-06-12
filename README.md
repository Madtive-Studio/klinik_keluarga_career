# Klinik Keluarga Career Portal

Platform sistem manajemen karir dan rekrutmen untuk mengelola lowongan pekerjaan dan aplikasi kandidat di Klinik Keluarga.

## Deskripsi Singkat

Aplikasi web berbasis Laravel yang menyediakan solusi lengkap untuk:
- **Admin**: Mengelola lowongan pekerjaan, kategori, batch, dan jadwal interview
- **Kandidat**: Mencari lowongan, melamar pekerjaan, mengunggah dokumen, dan menerima undangan interview

## Setup Aplikasi

### Requirement
- PHP 8.2+
- Composer
- Node.js & npm
- MySQL/PostgreSQL
- Docker (opsional)

### Instalasi

```bash
# Clone repository
git clone <repo-url>
cd klinik_keluarga_career

# Install PHP dependencies
composer install

# Install Node dependencies
npm install

# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate

# Run migrations
php artisan migrate

# Build assets
npm run production

# Start development server
php artisan serve
```

### Dengan Docker

```bash
docker-compose up -d
docker-compose exec app php artisan migrate
docker-compose exec app npm run production
```

## Dokumentasi

- [Arsitektur MVC](docs/ARCHITECTURE.md)
- [Overview lengkap](docs/archive/Overview.md)
- [Proposal Kerja Praktek](docs/Proposal%20Kerja%20Praktek.pdf)

## Testing

```bash
./vendor/bin/pest
```

## License

MIT
