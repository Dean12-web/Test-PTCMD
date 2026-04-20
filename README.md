<p align="center">TEST PT Capella Multidana</p>

## Deskripsi
Aplikasi ini dibangun menggunakan **Laravel 11** dan **Alpine.js** sebagai frontend scripting.


## Fitur Aplikasi : 
- Input pengajuan
- Validasi otomatis
- Approve / Reject
- Detail cicilan

## Aturan Aplikasi : 
- Penghasilan < 1jt → ditolak
- Maksimum pinjaman 200jt
- Maksimum tenor 24 bulan
- Maksimum 3 pengajuan

## Cara Menjalankan Aplikasi

1. Clone aplikasi dari github

```bash
git clone git@github.com:Dean12-web/Test-PTCMD.git
cd Test-PTCMD
```
2. Install dependency
```bash
composer install
npm install
```
3. Setup Environtment
```bash
cp .env.example .env
php artisan key:generate
```
4. Jalankan Migrasi
```bash
php artisan migrate
```

5.Jalankan aplikasi
```bash
php artisan serve
npm run dev
```



