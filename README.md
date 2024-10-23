SpanReserve

## Prerequisites

Sebelum menjalankan proyek ini, pastikan Anda memiliki hal-hal berikut:

-   PHP >= 7.3
-   Composer
-   Node.js >= 12.x
-   NPM
-   Database (misalnya MySQL)

## Instalasi

Ikuti langkah-langkah berikut untuk menginstal proyek ini:

1. **Clone repository**

    - git clone https://github.com/yasfaa/SnapReserve.git
    - cd repo

2. **Install dependensi**

    - composer install
    - npm install

3. **Setup environment**

    Salin file .env.example menjadi .env dan sesuaikan pengaturan database dan konfigurasi lainnya.

    - cp .env.example .env

    jalankan perintah 
    
    - php artisan key:generate

    Impor database snapreserve.sql ke dalam database milik anda

4. **Jalankan Aplikasi**

    Pastikan kedua server berikut menyala. gunakan terminal yang berbeda

-   php artisan serve
-   npm run dev
