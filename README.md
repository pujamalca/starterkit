# Laravel Filament Starter Kit

Starter kit ini menyiapkan panel admin Filament 4 dengan sistem peran, manajemen konten, dan modul pengaturan terintegrasi. Repository ini berfokus pada arsitektur terstruktur untuk proyek CMS dan aplikasi internal berbasis Laravel 12.

## Fitur Inti
- Role & permission menggunakan Spatie Laravel Permission.
- Panel admin Filament (path `/admin`) dengan dashboard, manajemen pengguna, aktivitas, kategori, tag, posting, dan ekspor Excel.
- Halaman statis (Pages) dengan editor sederhana, status publikasi, dan SEO dasar.
- Modul **Manage Settings** dengan dukungan penyimpanan database menggunakan Spatie Laravel Settings.
- Seeder awal untuk pengguna Super Admin dan Content Editor, lengkap dengan peran.
- Seeder `SettingSeeder` yang mengisi nilai default dan metadata untuk pengaturan umum, email, dan sosial.

## Prasyarat
- PHP 8.2+
- Composer
- Node.js & NPM (untuk build front-end)
- Database yang kompatibel dengan Laravel (MySQL/MariaDB, PostgreSQL, dll.)

## Instalasi & Setup
```bash
cp .env.example .env
composer install
npm install
php artisan key:generate
php artisan migrate
php artisan settings:migrate    # migrasi untuk modul settings
php artisan db:seed             # membuat roles, pengguna awal, dan settings default
```

### Kredensial Admin Default
- Email: `admin@example.com`
- Password: `password`

Untuk memaksa update password atau assign role lain, jalankan `php artisan tinker` dan manfaatkan metode bawaan model User.

## Akses Panel & Pengaturan
- Panel admin: `http://localhost:8000/admin`
- **Manage Settings**: tersedia di navigasi “Pengaturan” bagi pengguna dengan permission `access-settings`. Halaman ini menyediakan tab:
  - **Umum**: identitas brand, deskripsi, keyword, maintenance mode, moderasi komentar, dan jumlah posting per halaman.
  - **Email**: konfigurasi nama & alamat pengirim, driver mail, dan kredensial SMTP.
  - **Sosial**: tautan media sosial utama.
- Setiap perubahan akan disimpan ke tabel `settings` dan dicache jika opsi autoload aktif.

## Seeder & Struktur Data
- `RolePermissionSeeder` membuat peran (Super Admin, Content Editor, dll.) beserta permission standar.
- `SettingSeeder` membuat entri metadata pengaturan (display name, urutan, autoload) serta nilai default jika belum ada.
- `ContentSeeder` menyiapkan konten contoh (posts, kategori, dan tag) untuk percobaan awal.

## Pengembangan
- Jalankan dev server terpadu: `npm run dev` atau gunakan perintah `composer dev` untuk mengaktifkan server, queue listener, dan Vite secara paralel.
- Jalankan test suite: `composer test`.
- Setelah menambahkan halaman atau widget baru di Filament, jalankan `php artisan filament:cache-components` bila diperlukan.

## Backup Database & Scheduler
- Halaman **Backup Basis Data** (navigasi Pengaturan) menyediakan backup manual dan penjadwalan otomatis (harian/mingguan/bulanan) dengan pilihan format JSON, CSV (ZIP), atau SQL. Jangan lupa klik **Simpan Pengaturan** setelah mengubah format atau jam.
- Berkas backup disimpan di `storage/app/backups` dan seluruh timestamp menggunakan zona waktu Asia/Jakarta (WIB).
- Di server Ubuntu jalankan worker queue sebagai service agar job antrean (misalnya backup terjadwal) diproses otomatis. Contoh unit `systemd`:
  ```ini
  # /etc/systemd/system/laravel-queue.service
  [Unit]
  Description=Laravel Queue Worker
  After=network.target

  [Service]
  User=www-data
  Group=www-data
  Restart=always
  ExecStart=/usr/bin/php /var/www/starterkit/artisan queue:work --queue=default --tries=1 --sleep=1
  WorkingDirectory=/var/www/starterkit

  [Install]
  WantedBy=multi-user.target
  ```
  Aktifkan dengan `sudo systemctl daemon-reload && sudo systemctl enable --now laravel-queue`.
- Tambahkan cron agar scheduler Laravel berjalan setiap menit:
  ```bash
  * * * * * cd /var/www/starterkit && /usr/bin/php artisan schedule:run >> /dev/null 2>&1
  ```
- Pantau keberhasilan backup melalui log worker (`storage/logs/laravel.log`), tabel `jobs`/`failed_jobs`, atau langsung dari log terakhir yang tampil di halaman Backup.

## Catatan Lanjutan
- Gunakan permission `access-admin-panel` untuk membatasi akses panel secara global.
- Pengaturan bersifat otentikasi tunggal: perubahan akan dimuat ulang saat aplikasi bootstrap, sehingga pertimbangkan cache konfigurasi saat deploy (`php artisan config:cache`).
- Dokumentasi tambahan tersedia di `init.md` yang merinci roadmap lengkap starter kit ini.

Selamat membangun! Jangan lupa menjaga dokumentasi dan pengujian agar starter kit ini tetap stabil dan mudah dikembangkan lebih lanjut.
