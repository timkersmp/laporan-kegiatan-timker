# Pengumpulan Laporan BBPMP — Laravel

Aplikasi PHP native sudah dipindahkan ke Laravel 12. Sumber lama tetap disimpan terpisah di folder `../kegiatanpetugas`.

## Menjalankan secara lokal

1. Pastikan PHP 8.2 atau lebih baru, Composer, MySQL, dan ekstensi `pdo_mysql` tersedia.
2. Nyalakan MySQL di XAMPP, lalu buat database kosong bernama `db_kegiatan_laravel` melalui phpMyAdmin.
3. Dari folder `laravel-app`, jalankan `composer install` dan `php artisan key:generate` bila `APP_KEY` belum terisi.
4. Pastikan pengaturan `DB_*` di `.env` sesuai MySQL lokal, lalu jalankan `php artisan migrate --seed`.
5. Jalankan `php artisan storage:link` agar lampiran yang diunggah dapat dibuka, kemudian `php artisan serve`.
6. Buka alamat yang ditampilkan oleh `artisan serve`.

## Akun lokal awal

- Admin: `admin` / `admin123`
- Karyawan contoh: `verifikator` / `admin123`

Akun contoh dibuat oleh seeder untuk penggunaan lokal. Ganti kata sandinya sebelum aplikasi dipakai di lingkungan bersama atau produksi.

## Fitur

- Login dengan pilihan Admin atau Karyawan. Password lama berbentuk teks biasa tetap dapat dipakai saat login pertama.
- Ringkasan dashboard admin dan karyawan, serta pengelolaan data karyawan.
- Karyawan mengirim laporan langsung dengan mengisi data kegiatan, isi laporan, dan lampiran tanpa penugasan terlebih dahulu.
- Admin menerima dan memverifikasi laporan, lalu melihat arsip pada halaman Data Laporan.
- Middleware peran, validasi Laravel, CSRF, query Eloquent, dan penyimpanan file melalui disk publik.

## Data dari aplikasi lama

Folder lama tidak diubah. Repositori ini tidak menyertakan dump SQL dan konfigurasi lama merujuk ke dua nama database (`db_pengumpulan_laporan` dan `db_kegiatan`) dengan beberapa variasi kolom tabel. Karena itu migrasi ini membuat skema Laravel baru dan belum menyalin baris data lama. Ekspor database lama dan pemetaan kolom perlu dilakukan sebelum data produksi dipindahkan.
