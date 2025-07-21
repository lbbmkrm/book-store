# Proyek Toko Buku

Ini adalah proyek studi kasus untuk aplikasi e-commerce toko buku sederhana. Proyek ini dibangun menggunakan arsitektur backend dan frontend yang terpisah.

## Fitur

### Pengguna
- Melihat daftar buku
- Melihat detail buku
- Mencari buku
- Menambahkan buku ke keranjang
- Melihat keranjang belanja
- Checkout
- Melihat riwayat pesanan
- Login dan Register

### Admin
- Mengelola buku (tambah, edit, hapus)
- Mengelola kategori (tambah, edit, hapus)
- Mengelola pesanan

## Teknologi

### Backend
- Laravel
- PHP
- MySQL

### Frontend
- Vue.js
- Vite
- Pinia
- Axios
- Tailwind CSS

## Prasyarat

- PHP >= 8.2
- Composer
- Node.js >= 18.x
- NPM
- MySQL

## Instalasi

### Backend

1.  Masuk ke direktori `backend`.
    ```sh
    cd backend
    ```
2.  Salin file `.env.example` menjadi `.env`.
    ```sh
    cp .env.example .env
    ```
3.  Instal dependensi Composer.
    ```sh
    composer install
    ```
4.  Buat kunci aplikasi.
    ```sh
    php artisan key:generate
    ```
5.  Konfigurasikan koneksi database di file `.env`.
6.  Jalankan migrasi database.
    ```sh
    php artisan migrate
    ```
7.  Jalankan seeder database.
    ```sh
    php artisan db:seed
    ```
8.  Jalankan server pengembangan.
    ```sh
    php artisan serve
    ```

### Frontend

1.  Masuk ke direktori `frontend`.
    ```sh
    cd frontend
    ```
2.  Instal dependensi NPM.
    ```sh
    npm install
    ```
3.  Jalankan server pengembangan.
    ```sh
    npm run dev
    ```

## Endpoint API

| Method | URI | Deskripsi |
| --- | --- | --- |
| POST | `/api/register` | Mendaftarkan pengguna baru |
| POST | `/api/login` | Login pengguna |
| GET | `/api/authenticated` | Memeriksa apakah pengguna sudah login |
| POST | `/api/logout` | Logout pengguna |
| GET | `/api/books` | Mendapatkan semua buku |
| GET | `/api/top-books` | Mendapatkan buku terlaris |
| GET | `/api/books/{id}` | Mendapatkan detail buku |
| POST | `/api/books` | Membuat buku baru (admin) |
| PATCH | `/api/books/{id}` | Memperbarui buku (admin) |
| DELETE | `/api/books/{id}` | Menghapus buku (admin) |
| GET | `/api/categories` | Mendapatkan semua kategori |
| GET | `/api/categories/{id}` | Mendapatkan detail kategori (admin) |
| POST | `/api/categories` | Membuat kategori baru (admin) |
| PATCH | `/api/categories/{id}` | Memperbarui kategori (admin) |
| DELETE | `/api/categories/{id}` | Menghapus kategori (admin) |
| GET | `/api/cart` | Mendapatkan isi keranjang |
| POST | `/api/cart` | Menambahkan item ke keranjang |
| PUT | `/api/cart/{cartDetailId}` | Memperbarui jumlah item di keranjang |
| DELETE | `/api/cart/{cartDetailId}` | Menghapus item dari keranjang |
| DELETE | `/api/cart` | Mengosongkan keranjang |
| GET | `/api/orders` | Mendapatkan riwayat pesanan |
| GET | `/api/orders/{id}` | Mendapatkan detail pesanan |
| POST | `/api/orders` | Membuat pesanan baru |
| POST | `/api/orders/{id}/status` | Memperbarui status pesanan (admin) |

