# Lapor MBG

Mini Project Proyek 3, Case A: **Pelaporan dan Pemantauan Keamanan Makanan MBG**.

**Nama:** Ananda Firdaus  
**NIM:** 251511003  
**Kelas:** 2A  
**Repository:** https://github.com/FirdausSinau/Mini-Project---MBG

## Fitur

- Login dan logout dengan tiga peran: Pelapor, Pemantau Komunitas, dan Koordinator Komunitas.
- Pelapor membuat laporan, mengunggah bukti, memperbaiki laporan, dan memantau status laporan sendiri.
- Pemantau meninjau laporan, memberi catatan, dan menandai laporan yang perlu diteruskan.
- Koordinator mencatat penerusan, menutup laporan, dan mengelola data sekolah serta distribusi.
- Pencarian, filter, pagination, timeline status, dan rekap sederhana.

## Stack

- Laravel 13
- PHP 8.4
- MySQL/MariaDB
- Blade, CSS, JavaScript

## Akun demo

| Username | Nama | Role | Password |
|---|---|---|---|
| `Giorno` | Giorno Giovanna | Pelapor | `rahasia123` |
| `Jotaro` | Jotaro Kujo | Pelapor | `rahasia123` |
| `Dramok` | Dramok Reza Ladesh | Pemantau | `rahasia123` |
| `Perrell` | Laquarius Brown | Koordinator | `rahasia123` |

## Menjalankan

1. `composer install`
2. Salin `.env.example` menjadi `.env`
3. Atur koneksi database di `.env`
4. `php artisan key:generate`
5. `php artisan migrate --seed`
6. `php artisan serve`

Buka `http://127.0.0.1:8000`.

## Pengujian

```bash
php artisan test
```

## Catatan

Project ini tidak memakai Vite, Tailwind, atau framework CSS. Bukti pendukung disimpan di disk `local` dan diakses melalui route yang dilindungi policy.
