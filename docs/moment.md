# Sepulang Moment dalam project Laravel MAHRM

Moment dan MAHRM berjalan di satu instalasi Laravel, dengan route berdasarkan hostname. Tidak ada migrasi database baru untuk lima halaman publik ini.

## Halaman

| Path pada domain Moment | Halaman |
| --- | --- |
| `/` | Landing / Beranda |
| `/exclusive` | Moment Exclusive |
| `/portrait` | Moment Portrait |
| `/group-portrait` | Moment Group Portrait |
| `/preset-desain` | Preset & Desain |

Menu mobile, pemilih budget, rekomendasi paket, panel harga, tautan konsultasi WhatsApp, thumbnail galeri, lightbox, dan filter koleksi berfungsi di browser. Route yang tidak dikenal menampilkan halaman 404 Moment, termasuk path toko seperti `/catalog` atau `/admin/dashboard`.

## Preview lokal

Jalankan dari direktori project:

```sh
MOMENT_DOMAIN=moment.localhost php artisan serve --host=127.0.0.1 --port=8000
npm run build
```

- Toko MAHRM: `http://127.0.0.1:8000`
- Moment: `http://moment.localhost:8000`

Saat `APP_ENV=local`, domain default Moment adalah `moment.localhost`; pada environment lain defaultnya `moment.mahrmofficial.com`. Nilai `MOMENT_DOMAIN` dapat mengganti default tersebut.

Browser modern biasanya memetakan `*.localhost` ke loopback. Jika resolusi lokal tidak tersedia, tambahkan `127.0.0.1 moment.localhost` pada hosts komputer. Jangan menambahkan scheme atau port ke `MOMENT_DOMAIN`.

Untuk development Vite, gunakan `npm run dev` sebagai pengganti build. Project menyediakan entry CSS dan JS terpisah untuk Moment; halaman Moment tidak memuat shell toko atau bundle Preline toko.

## Produksi

Atur konfigurasi deployment:

```dotenv
APP_URL=https://mahrmofficial.com
MAHRM_DOMAIN=mahrmofficial.com
MOMENT_DOMAIN=moment.mahrmofficial.com
MOMENT_WHATSAPP=6285867480640
SESSION_DOMAIN=null
SESSION_SECURE_COOKIE=true
```

1. Arahkan DNS `moment.mahrmofficial.com` ke server aplikasi yang sama (A/AAAA atau CNAME sesuai hosting).
2. Atur virtual host/server name untuk kedua hostname dan arahkan document root ke direktori `public` project ini.
3. Pasang sertifikat HTTPS untuk kedua hostname. Wildcard dapat dipakai bila memang tersedia.
4. Jalankan build frontend dan cache konfigurasi/route sesuai prosedur deployment project: `npm run build`, `php artisan config:cache`, `php artisan route:cache`.

`MAHRM_DOMAIN` boleh kosong untuk mempertahankan akses localhost yang sudah ada. Di produksi isi eksplisit agar route toko dibatasi ke hostname MAHRM. Konfigurasi ini memakai domain utama tanpa `www`; jika `www.mahrmofficial.com` digunakan, arahkan ke domain utama melalui redirect di web server.

Biarkan cookie session bersifat host-only (`SESSION_DOMAIN=null`) bila kedua website tidak membutuhkan login bersama. DNS, virtual host, SSL, dan konfigurasi produksi belum diubah oleh implementasi lokal ini.

## Konten dan aset sementara

- `config/moment.php`: daftar paket, benefit, estimasi harga, kontak, serta koleksi preset/desain.
- `resources/views/moment`: layout, komponen, dan halaman Blade Moment.
- `resources/css/moment.css` dan `resources/js/moment.js`: tampilan mobile first dan interaksi.
- `public/images/moment`: foto dummy WebP yang tersimpan lokal.
- `routes/moment.php` dan `routes/mahrm.php`: route masing-masing website.

Implementasi memakai struktur Figma dan screenshot Landing serta Group Portrait yang sudah berhasil dibaca. Pengambilan detail Figma berikutnya terkena batas Starter; pengguna menyetujui pendekatan dari referensi yang tersedia dengan aset sementara. Tampilan ini belum diklaim sebagai salinan presisi seluruh frame.

Harga Rp250.000 / Rp300.000 / Rp450.000 adalah **estimasi untuk preview**, bukan harga yang diverifikasi dari Figma. `prices_confirmed=false` menampilkan penjelasan estimasi di website. Verifikasi harga sebelum mengubah flag tersebut. Nama dan contoh koleksi Preset & Desain merupakan usulan konten karena katalog pada frame itu belum terbaca. Wordmark Sepulang dan MAHRM menggunakan teks sementara. Footer menyediakan dialog informasi untuk meminta ketentuan layanan dan privasi; dokumen kebijakan final perlu disiapkan sebelum publikasi.

Foto dummy dari Unsplash (bukan hasil karya Sepulang Moment):

- `portrait.webp`: Galuh Hari Setiawan — https://unsplash.com/photos/a-woman-in-a-graduation-cap-and-gown-TKVd-GfohVk
- `exclusive.webp`: 150 Billi — https://unsplash.com/photos/a-woman-in-a-graduation-cap-and-gown-6oO5mI-Zikk
- `group.webp`: Satria Perkasa — https://unsplash.com/photos/four-women-in-blac-academic-dresses-standing-on-road-between-trees-z9RZbpIAqKg

Foto dioptimalkan menjadi WebP; tidak ada URL aset Figma sementara yang dipakai oleh halaman. Ganti foto dengan aset pemilik sebelum website dipublikasikan sebagai portofolio. Instagram tidak ditampilkan sampai `MOMENT_INSTAGRAM_URL` diisi.

Implementasi ini mencakup halaman publik pada Figma. **CMS/admin pengelolaan konten belum dibuat**; konten masih terpusat pada konfigurasi agar sumber datanya dapat dihubungkan ke CMS pada tahap berikutnya.

## Verifikasi

```sh
php artisan test tests/Unit/MomentWebsiteTest.php
npm run build
```

Pengujian Laravel ini tidak melakukan migrasi atau mengubah database. Tes memeriksa render kelima halaman, pemisahan domain, benefit dan tautan booking per paket, serta keberadaan aset lokal. Browser QA mencakup navigasi, budget, panel harga, galeri, filter preset, dan pemeriksaan overflow pada layar mobile.

Hasil regression test SQLite in-memory: **233 lulus, 1 gagal**. Kegagalan berada pada tes lama `settings page shows custom homepage banner preview`: assertion mencari `Pulihkan banner bawaan` sebagai satu string, sedangkan template yang tidak berubah memisahkan kata-katanya dengan newline (`resources/views/admin/settings/index.blade.php:128`). Ke-13 tes khusus Moment lulus.

QA browser: 30 kombinasi dari lima halaman pada lebar 320, 360, 390, 430, 768, dan 1280 px selesai tanpa overflow horizontal atau gambar rusak. Menu, budget minimum/maksimum, harga, galeri, filter Desain, preview komposisi, dan dialog footer telah dicoba. Screenshot QA tersedia di `output/playwright/` (diabaikan Git). Build Vite, pemeriksaan format PHP untuk file baru, serta pembuatan dan penghapusan route cache berhasil.
