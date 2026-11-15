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
- `public/images/moment/photography`: 12 foto WebP dari aset pengguna.
- `public/images/moment/branding`: logo Sepulang Moment putih dan logo kolaborasi Sepulang × MAHRM.
- `routes/moment.php` dan `routes/mahrm.php`: route masing-masing website.

Implementasi memakai struktur Figma dan screenshot Landing serta Group Portrait yang sudah berhasil dibaca. Pengambilan detail Figma berikutnya terkena batas Starter; pengguna menyetujui pendekatan dari referensi yang tersedia dengan aset sementara. Tampilan ini belum diklaim sebagai salinan presisi seluruh frame.

Harga Rp250.000 / Rp300.000 / Rp450.000 adalah **estimasi untuk preview**, bukan harga yang diverifikasi dari Figma. `prices_confirmed=false` menampilkan penjelasan estimasi di website. Verifikasi harga sebelum mengubah flag tersebut. Nama dan contoh koleksi Preset & Desain merupakan usulan konten karena katalog pada frame itu belum terbaca. Footer menyediakan dialog informasi untuk meminta ketentuan layanan dan privasi; dokumen kebijakan final perlu disiapkan sebelum publikasi.

Foto dan logo asli dipindahkan dari folder `result_webp` ke direktori aset publik di atas. File diberi nama berdasarkan isi, dengan data gambar dan transparansi tetap dipertahankan. Foto stok Unsplash sebelumnya sudah dihapus. Hero memakai foto malam, paket Group Portrait memakai foto dua wisudawati pada malam hari, dan galeri Portrait/Exclusive memakai seri foto kampus, keluarga, serta pasangan dari folder yang diberikan.

Pemetaan aset sumber:

| Nama sumber di `result_webp` | Path di `public/images/moment` |
| --- | --- |
| `1c46793b98453ba176fb0c233a7c2bd7047812cc.webp` | `photography/portrait-arch.webp` |
| `52761405c54cbf43cf8688435e9acc75d7e9d133.webp` | `photography/group-night.webp` |
| `58fb417557b855086978bc34af44362c4e57c388.webp` | `photography/portrait-campus-wide.webp` |
| `64b148a78fd99a9cdd91b35e99124fa3f97d8fa4.webp` | `photography/graduation-family.webp` |
| `7569e03cc49638cf1ee8d4eb41066365fa9f9b86.webp` | `photography/portrait-campus-back.webp` |
| `8401c6e74ad72aa430f2b3c53ed7aafbab42f952.webp` | `photography/portrait-campus-steps.webp` |
| `c5eb2446fd91d54d90d5a08335571468200c6a44.webp` | `photography/graduation-portrait.webp` |
| `c6f68306a6a7859c64eb789c7397ccc398a58ee2.webp` | `photography/graduation-family-group.webp` |
| `cb35ce8ebef9c022f2a1c857b7d9da85769fe5ce.webp` | `photography/portrait-couple-steps.webp` |
| `d43a02409c1c69e2c6b8d4725fa3db8dbb527dbb.webp` | `photography/portrait-campus-bouquet.webp` |
| `ec23c8924cfc1b213a02ffc418d9108190ab123e.webp` | `photography/graduation-street.webp` |
| `fce9c628683d5d56ed037cea5feca715c0fbace0.webp` | `photography/hero-night.webp` |
| `logo_Sepulang_x_MHR 1.webp` | `branding/sepulang-mahrm.webp` |
| `logo_sepulang_moment_putih.webp` | `branding/sepulang-moment-white.webp` |

Tidak ada URL aset Figma sementara yang dipakai oleh halaman. Tautan media sosial ditampilkan setelah diisi melalui admin Moment. Environment hanya menjadi nilai awal saat inisialisasi.

## Admin Konten Moment

Buka dashboard MAHRM → **Konten Moment**, atau `/admin/moment` pada domain toko. Gunakan akun admin MAHRM yang sudah ada. Tersedia lima tab: Foto Beranda, Paket, Preset & Desain, Kontak, dan Media. Hanya pengaturan rutin operator yang ditampilkan. Perubahan langsung tampil setelah disimpan.

- Upload JPG, PNG, atau WebP maksimal 5 MB pada tab Media, lalu pilih pada form konten. Gambar dapat dipakai ulang. Atur deskripsi foto. Posisi fokus yang sudah tersimpan tetap dipakai tanpa menampilkan angka teknis kepada operator. Gambar yang masih digunakan tidak dapat dihapus.
- Paket dapat ditambah, diurutkan, dan dinonaktifkan. Slug tetap setelah dibuat; nama boleh berubah. Benefit diisi satu per baris. Pilih galeri sesuai urutan pada slot yang tersedia. Status harga estimasi/final diatur per paket.
- Koleksi dapat ditambah, diurutkan, dinonaktifkan, serta diganti nama, deskripsi, jenis, dan gambarnya. Gaya serta teks dekorasi desain ditentukan oleh kode.
- Teks halaman, About, SEO, kebijakan, template pesan, identitas/logo, favicon, serta budget awal ditentukan di kode. Nilai lama untuk pengaturan tersebut di database tidak lagi mengubah tampilan website. Pengaturan kontak dan konten rutin tetap terpisah dari toko MAHRM.
- Data awal mempertahankan foto, pertukaran galeri Portrait/Exclusive, paket, dan konten terbaru. `config/moment.php` dan `resources/moment-copy.php` menentukan nilai tetap. Hanya konten operasional website yang dibaca dari tabel khusus Moment. Tidak ada cache konten yang perlu dihapus setelah menyimpan.

Saat deployment, jalankan:

```sh
php artisan migrate
php artisan storage:link
npm run build
```

Migrasi menginisialisasi konten sekali. `php artisan moment:initialize` dapat dijalankan ulang untuk melengkapi data bawaan tanpa menimpa edit admin. Upload tersimpan di `storage/app/public/moment`; sertakan database dan direktori upload dalam backup. URL media pada website Moment menggunakan hostname Moment, bukan APP_URL toko.

## Verifikasi

```sh
DB_CONNECTION=sqlite DB_DATABASE=:memory: php artisan test tests/Unit/MomentWebsiteTest.php tests/Feature/Admin/MomentContentTest.php
npm run build
```

Pengujian menggunakan migrasi pada database pengujian terisolasi; jangan menjalankannya pada database produksi. Tes memeriksa render kelima halaman, pemisahan domain, benefit dan tautan booking per paket, serta keberadaan aset lokal. Browser QA mencakup navigasi, budget, panel harga, galeri, filter preset, dan pemeriksaan overflow pada layar mobile.

Hasil regression test SQLite in-memory: **233 lulus, 1 gagal**. Kegagalan berada pada tes lama `settings page shows custom homepage banner preview`: assertion mencari `Pulihkan banner bawaan` sebagai satu string, sedangkan template yang tidak berubah memisahkan kata-katanya dengan newline (`resources/views/admin/settings/index.blade.php:128`). Ke-13 tes khusus Moment lulus.

QA browser: 30 kombinasi dari lima halaman pada lebar 320, 360, 390, 430, 768, dan 1280 px selesai tanpa overflow horizontal atau gambar rusak. Menu, budget minimum/maksimum, harga, galeri, filter Desain, preview komposisi, dan dialog footer telah dicoba. Screenshot QA tersedia di `output/playwright/` (diabaikan Git). Build Vite, pemeriksaan format PHP untuk file baru, serta pembuatan dan penghapusan route cache berhasil.

Verifikasi CMS: 22 tes khusus halaman/admin Moment lulus (119 assertions). Regression test sebelum tiga tes tambahan: 239 lulus, satu kegagalan lama pada assertion teks banner admin. Browser QA berhasil login pada database sementara, menyimpan budget, melihat hasilnya langsung pada domain Moment, dan membuka tujuh tab admin serta lima halaman publik pada viewport 390 px. Migrasi CMS telah diterapkan pada database lokal project.
