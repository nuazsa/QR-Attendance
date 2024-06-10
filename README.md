# QR Attendance System

QR Attendance System adalah aplikasi web yang memungkinkan pengguna untuk mencatat kehadiran menggunakan kode QR. Aplikasi ini dirancang untuk memudahkan pengelolaan kehadiran di kelas atau acara lainnya.

## Demo
https://qrattend.nuazsa.my.id/
- Login guru:
  - user: 00000000
  - pw: admin
- Login siswa:
  - user: 17220160
  - pw: 17.4A.02

## Instalasi Lokal

Ikuti langkah-langkah berikut untuk menginstal dan menjalankan proyek ini secara lokal:

### Prasyarat

Pastikan Anda memiliki perangkat lunak berikut yang terinstal di komputer Anda:

- [XAMPP](https://www.apachefriends.org/index.html) atau [Laragon](https://laragon.org/) untuk menjalankan server Apache dan MySQL.

### Langkah Instalasi

1. **Clone Repository**
   ```sh
   git clone https://github.com/nuazsa/QR-Attendance.git
   cd QR-Attendance

2. **Ekstrak File**
Ekstrak semua file proyek ke dalam folder htdocs atau www jika Anda menggunakan Laragon.

3.  **Nyalakan Service MySQL dan Apache**
Jalankan XAMPP atau Laragon, lalu nyalakan service MySQL dan Apache.

4. **Import Database**
Buka phpMyAdmin di browser Anda dan import file database pemodelandata.sql.

5. **Akses phpMyAdmin: http://localhost/phpmyadmin**
Buat database baru misalnya attendance_db.
Import file pemodelandata.sql ke dalam database yang baru dibuat.
Atur Konfigurasi Database
Atur koneksi database di file connection.php yang ada di folder component:
```
<?php
function connectToDatabase() {
    $host = 'localhost';
    $db = 'qrattend';
    $user = 'root';
    $pass = '';
    $charset = 'utf8mb4';

    $dsn = "mysql:host=$host;dbname=$db;charset=$charset";
    $options = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ];

    try {
        return new PDO($dsn, $user, $pass, $options);
    } catch (\PDOException $e) {
        throw new \PDOException($e->getMessage(), (int)$e->getCode());
    }
}
?>
```

## Fitur
- Login pengguna: Sistem otentikasi yang aman untuk memastikan hanya pengguna yang terdaftar yang dapat mengakses sistem.
- Generate QR Code untuk Presensi (otomatis oleh sistem): Sistem menghasilkan QR Code untuk presensi.
- Scan QR Code untuk Presensi (Admin): Admin memindai QR Code untuk mencatat kehadiran pengguna.
- Daftar Kelas User: Pengguna dapat melihat daftar kelas yang diikuti.
- Kelola Kelas Admin: Admin dapat mengelola dan mengatur kelas serta peserta kelas.
- Riwayat Presensi (User): Pengguna melihat presensi absensi mereka.
- Laporan Presensi (Admin): Admin melihat dan mengunduh laporan Presensi dengan format PDF.

## Struktur Direktori
```
├── admin
│   ├── index.php
│   ├── report.php
│   ├── barcode.php
├── component
│   ├── css
│   ├── js
│   ├── connection.php
├── user
│   ├── index.php
│   ├── history.php
│   ├── scan.php
├── qrattend.sql
├── README.md
├── index.php
```
