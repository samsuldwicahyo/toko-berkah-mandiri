# Toko Berkah Mandiri

Aplikasi inventory UMKM berbasis Laravel 12, PHP 8.2+, Blade, Tailwind CSS, Alpine.js, dan SQLite/MySQL.

## Fitur
- Login, profil, dan pengaturan pengguna dengan peran admin/staf.
- Pengelolaan kategori dan barang.
- Pencatatan stok masuk/keluar dan riwayat mutasi.
- Dashboard, laporan stok menipis, dan laporan mutasi.
- Seeder 24 barang contoh dan kategori untuk pengembangan.

## Menjalankan lokal
Prasyarat: PHP 8.2+, Composer, Node.js 20.19+ atau 22.12+ (sesuai Vite), dan PDO SQLite atau MySQL.

1. Jalankan `composer install` dan `npm ci`.
2. Salin `.env.example` menjadi `.env`, lalu jalankan `php artisan key:generate`.
3. Default database SQLite. Buat berkas kosong `database/database.sqlite` (PowerShell: `New-Item database/database.sqlite -ItemType File`).
4. Isi `SEED_ADMIN_EMAIL` dan password pilihanmu pada `SEED_ADMIN_PASSWORD` (minimal 12 karakter). Tidak ada password admin bawaan.
5. Jalankan `php artisan migrate --seed`.
6. Jalankan `npm run build`, lalu `php artisan serve`.
7. Buka URL yang dicetak Artisan dan login memakai kredensial langkah 4.

Untuk MySQL, ubah `DB_CONNECTION` dan variabel database pada `.env` sebelum migrasi.

## Verifikasi
```bash
php artisan test
npm run build
```

Pengujian memakai SQLite in-memory, terpisah dari database lokal. Seeder mempertahankan password admin yang sudah ada saat dijalankan ulang.

## Cakupan repository
Repository berisi source, migrasi, seed contoh, dan tests. Database operasional, environment lokal, dependency, dan hasil build tidak disertakan. Ini aplikasi inventory; tidak mencakup checkout/pembayaran pelanggan.
