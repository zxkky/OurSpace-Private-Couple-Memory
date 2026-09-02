# 💕 OurSpace — Private Couple Memory

> **A private digital space to store, organize, and relive memories together.**

**OurSpace** adalah aplikasi web berbasis PHP dan MySQL yang dirancang sebagai ruang digital pribadi untuk menyimpan dan mengelola kenangan bersama pasangan.

Aplikasi ini memungkinkan dua pengguna untuk menyimpan foto dan video dalam berbagai album, memberikan caption dan lokasi, berinteraksi melalui like dan komentar, serta melihat kembali kenangan melalui **Timeline** dan **Calendar**.

---

## ✨ Features

### 🔐 Authentication

* Register akun baru
* Login dan logout
* Session-based authentication
* Password menggunakan `password_hash()`
* Maksimal **2 akun** untuk digunakan oleh pasangan

### 📊 Dashboard

Dashboard menampilkan ringkasan hubungan dan koleksi kenangan, seperti:

* 💕 Jumlah hari bersama
* ⏳ Countdown menuju anniversary berikutnya
* 📸 Total foto
* 🎥 Total video
* 📁 Total album
* 🕰️ Fitur **On This Day** untuk melihat kenangan pada tanggal yang sama di tahun sebelumnya
* 🖼️ Koleksi foto/video terbaru

### 📁 Album Management

Pengguna dapat:

* Membuat album
* Mengedit album
* Menghapus album
* Mengelompokkan album berdasarkan kategori:

  * `Liburan`
  * `Anniversary`
  * `Date`
  * `Random`
* Melihat jumlah media dalam setiap album
* Menggunakan media terbaru sebagai cover album

### 📸 Photo & Video Management

Setiap album dapat menyimpan foto maupun video.

Format yang didukung:

**Image**

* JPG / JPEG
* PNG
* GIF
* WEBP

**Video**

* MP4
* WEBM
* MOV

Batas ukuran upload:

> **20 MB per file**

Setiap media dapat memiliki:

* Caption
* Lokasi
* Tanggal pengambilan
* Like
* Komentar

### ❤️ Like & Comment

Pengguna dapat:

* Memberikan like pada foto/video
* Membatalkan like
* Menambahkan komentar
* Mengedit komentar miliknya sendiri
* Menghapus komentar miliknya sendiri

### 📅 Timeline

Semua kenangan dapat dilihat dalam bentuk timeline berdasarkan:

* Bulan
* Tahun
* Tanggal pengambilan media

Timeline memudahkan pengguna untuk melihat perjalanan kenangan dari waktu ke waktu.

### 🗓️ Calendar

OurSpace menyediakan tampilan kalender untuk melihat kenangan berdasarkan tanggal.

Pengguna dapat memilih:

* Bulan
* Tahun

Foto yang memiliki tanggal pengambilan akan ditampilkan pada tanggal terkait.

### 📱 Responsive Design

Interface dirancang agar dapat digunakan pada:

* 💻 Desktop
* 💻 Laptop
* 📱 Smartphone

Pada perangkat mobile tersedia navigation bar di bagian bawah untuk mempermudah navigasi.

---

## 🛠️ Tech Stack

| Technology   | Usage                       |
| ------------ | --------------------------- |
| PHP          | Backend & application logic |
| MySQL        | Database                    |
| PDO          | Database connection         |
| HTML5        | Application structure       |
| Tailwind CSS | UI styling                  |
| JavaScript   | Client-side functionality   |
| Apache       | Web server                  |
| `.htaccess`  | URL rewriting               |

---

## 📂 Project Structure

```text
ourspace/
│
├── .htaccess
├── a.php
├── auth.php
├── bootstrap.php
├── config.php
├── db.php
├── helper.php
├── index.php
├── routes.php
├── schema.sql
│
├── partials/
│   ├── head.php
│   └── navbar.php
│
└── view/
    ├── 404.php
    ├── albums.php
    ├── calendar.php
    ├── dashboard.php
    ├── detail_album.php
    ├── detial_photo.php
    ├── edit_album.php
    ├── login.php
    ├── new_album.php
    ├── register.php
    ├── timeline.php
    └── view_edit.php
```

---

## 🗄️ Database

OurSpace menggunakan **MySQL** dengan beberapa tabel utama:

```text
users
albums
photos
likes
comments
```

### Database Relationship

```text
users
  │
  ├── albums
  │      │
  │      └── photos
  │             ├── likes
  │             └── comments
  │
  └── likes / comments
```

Relasi menggunakan foreign key dan `ON DELETE CASCADE` untuk menjaga konsistensi data.

---

## ⚙️ Installation

### 1. Clone Repository

```bash
git clone https://github.com/USERNAME/ourspace.git
```

Masuk ke folder project:

```bash
cd ourspace
```

### 2. Setup Web Server

Letakkan project di document root web server.

Contoh menggunakan XAMPP:

```text
C:/xampp/htdocs/ourspace
```

Kemudian jalankan:

* Apache
* MySQL

---

### 3. Create Database

Buka **phpMyAdmin**, kemudian import:

```text
schema.sql
```

Atau melalui MySQL:

```bash
mysql -u root -p < schema.sql
```

> Pastikan nama database pada `schema.sql` sesuai dengan konfigurasi aplikasi.

---

### 4. Configure Database

Edit file:

```text
config.php
```

Konfigurasi default:

```php
DB_HOST = 127.0.0.1
DB_PORT = 3306
DB_NAME = ourspace
DB_USER = root
DB_PASS = ''
```

Sesuaikan dengan konfigurasi MySQL kamu.

---

### 5. Configure Relationship Start Date

Pada `config.php`, kamu juga dapat menentukan tanggal awal hubungan:

```php
define(
    'RELATIONSHIP_START_DATE',
    env('RELATIONSHIP_START_DATE', '2023-01-01')
);
```

Tanggal tersebut digunakan untuk menghitung:

* Jumlah hari bersama
* Countdown anniversary

Contoh:

```php
'2024-08-17'
```

---

### 6. Create Upload Directory

Buat folder:

```text
uploads/
```

di dalam folder project:

```text
ourspace/
├── uploads/
├── config.php
├── index.php
└── ...
```

Pastikan web server memiliki permission untuk menulis ke folder tersebut.

---

### 7. Run Application

Buka browser:

```text
http://localhost/ourspace
```

Kemudian:

1. Daftar akun pertama
2. Daftar akun kedua
3. Login
4. Buat album
5. Upload foto/video
6. Tambahkan caption, lokasi, dan tanggal
7. Bagikan kenangan bersama ❤️

---

## 🔒 Security

OurSpace menerapkan beberapa mekanisme keamanan dasar:

* Password hashing menggunakan `password_hash()`
* Password verification menggunakan `password_verify()`
* Session authentication
* Session ID regeneration setelah login/register
* PDO prepared statements
* MIME type validation untuk upload
* Random filename untuk file yang diupload
* Pembatasan ukuran file maksimal 20 MB
* Validasi tipe file yang diperbolehkan
* HTML escaping menggunakan helper `h()`

---

## 🎨 UI Design

OurSpace menggunakan konsep visual yang romantis dan minimalis dengan:

* Dark theme
* Rose / pink accent
* Typography menggunakan **Inter**
* Heading menggunakan **Cormorant Garamond**
* Responsive layout
* Mobile bottom navigation
* Card-based interface

Konsep desain dibuat agar aplikasi terasa seperti **private digital space**, bukan media sosial publik.

---

## 🧭 Application Routes

| Method | Route                   | Description          |
| ------ | ----------------------- | -------------------- |
| GET    | `/`                     | Home / redirect      |
| GET    | `/login`                | Login                |
| POST   | `/login`                | Process login        |
| GET    | `/register`             | Registration         |
| POST   | `/register`             | Process registration |
| GET    | `/logout`               | Logout               |
| GET    | `/dashboard`            | Dashboard            |
| GET    | `/albums`               | Album list           |
| GET    | `/albums/new`           | Create album         |
| POST   | `/albums/new`           | Save album           |
| GET    | `/albums/{id}`          | Album detail         |
| POST   | `/albums/{id}/upload`   | Upload media         |
| GET    | `/albums/{id}/edit`     | Edit album           |
| POST   | `/albums/{id}/edit`     | Update album         |
| POST   | `/albums/{id}/delete`   | Delete album         |
| GET    | `/photos/{id}`          | Media detail         |
| POST   | `/photos/{id}/like`     | Like / unlike        |
| POST   | `/photos/{id}/edit`     | Edit media           |
| POST   | `/photos/{id}/delete`   | Delete media         |
| POST   | `/photos/{id}/comment`  | Add comment          |
| POST   | `/comments/{id}/edit`   | Edit comment         |
| POST   | `/comments/{id}/delete` | Delete comment       |
| GET    | `/timeline`             | Memory timeline      |
| GET    | `/calendar`             | Memory calendar      |

---

## 💡 Use Case

OurSpace cocok digunakan oleh pasangan yang ingin memiliki tempat pribadi untuk menyimpan:

* 📸 Foto kencan
* ✈️ Foto liburan
* 🎂 Anniversary
* 🎥 Video bersama
* 💌 Momen spesial
* 📅 Perjalanan hubungan
* 📝 Cerita atau caption dari setiap kenangan

Berbeda dari media sosial biasa, OurSpace dirancang sebagai **ruang privat untuk dua orang**.

---

## 🚀 Future Development

Beberapa fitur yang dapat dikembangkan selanjutnya:

* 🔔 Notification
* 🔍 Search memories
* 🏷️ Tags untuk foto
* 📍 Interactive map untuk lokasi kenangan
* 🔐 End-to-end encryption
* ☁️ Cloud storage
* 📊 Memory statistics
* 🎵 Background music untuk album
* 💬 Real-time chat
* 📱 PWA / installable mobile app
* 🖼️ Slideshow kenangan
* 🎁 Special anniversary mode
* 🌙 Theme customization

---

## 🤝 Contributing

Project ini dapat dikembangkan lebih lanjut dengan melakukan:

1. Fork repository
2. Buat branch baru

```bash
git checkout -b feature/new-feature
```

3. Commit perubahan

```bash
git commit -m "Add new feature"
```

4. Push branch

```bash
git push origin feature/new-feature
```

5. Buat Pull Request

---

## 📄 License

This project is intended for educational and personal use.

---

## ❤️ About

**OurSpace** dibuat sebagai sebuah private digital space untuk menyimpan dan mengabadikan perjalanan, cerita, serta kenangan bersama orang tersayang.

> **Some memories deserve their own space. 💕**

**OurSpace — Your memories. Your space. Your story.**
