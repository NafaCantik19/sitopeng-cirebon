# SITOPENG - revisi antarmuka selaras skripsi

Paket ini menggunakan tampilan proyek SITOPENG sebagai dasar, kemudian merapikan antarmuka agar konsisten dengan ruang lingkup skripsi: pencarian destinasi terdekat menggunakan Haversine dan informasi rute menggunakan OSRM.

## Perubahan utama

- Paket Safari Cirebon dan perencana itinerary dihapus dari antarmuka publik karena tidak termasuk fokus penelitian dan dataset tidak memiliki parameter jam/durasi yang memadai.
- Fitur Haversine dibuat lebih menonjol pada halaman peta. Titik asal dapat dipilih melalui lokasi perangkat, koordinat manual, atau klik peta.
- Kategori asli pada database tidak diubah. Filter publik diringkas menjadi enam kelompok tampilan agar lebih mudah digunakan.
- Kartu destinasi memakai aksen warna berbeda berdasarkan kelompok tampilan kategori.
- Galeri About memakai empat kartu persegi seragam; item keempat tidak lagi melebar penuh.
- Header dan footer publik dibuat konsisten dan lebih profesional.
- Cuaca, audio guide AI, serta pilihan basemap tambahan dihapus dari antarmuka publik agar fitur yang terlihat tetap sesuai ruang lingkup penelitian. Peta dasar menggunakan OpenStreetMap.
- OSRM menggunakan profil `driving`. Jarak Haversine dan jarak jalan OSRM tetap ditampilkan sebagai konsep yang berbeda.

## Data

Dataset memuat 35 entri inventaris: 23 memiliki koordinat utama dan 12 masih memerlukan verifikasi lokasi/akses. Entri tanpa koordinat utama tetap dapat muncul sebagai informasi, tetapi tidak diberi marker, jarak palsu, atau rute.

## Menjalankan di XAMPP

1. Ekstrak folder `sig-wisata-new` ke `C:\xampp\htdocs\`.
2. Jalankan Apache dan MySQL. Gunakan PHP 8.1+ dengan `mysqli` dan `fileinfo`.
3. Buka `http://localhost/sig-wisata-new/`.
4. Pada konfigurasi bawaan, aplikasi membuat database baru `sig_wisata_kota_asli_db` dan mengimpor CSV satu kali. Sesuaikan `config.php` jika koneksi MySQL berbeda.
5. Buat akun admin pertama melalui `admin/setup.php`. Tidak ada username/sandi bawaan.
6. Gunakan `Ctrl+F5` setelah mengganti paket agar CSS/JS lama tidak tertahan cache.

## Catatan penggunaan

Haversine menghitung jarak geografis antarkoordinat dan digunakan untuk mengurutkan destinasi. OSRM dipanggil setelah titik asal dan tujuan valid untuk memperoleh rute jalan, jarak perjalanan, dan estimasi durasi. Estimasi durasi OSRM bukan informasi lalu lintas waktu nyata.

Harga kosong tidak berarti gratis. Koordinat rujukan dapat menunjuk bangunan/kompleks dan tidak selalu merupakan gerbang kendaraan. Periksa sumber dan kondisi lapangan sebelum menjadikannya klaim penelitian.

## Status pemeriksaan paket

Pemeriksaan dalam lingkungan penyusunan paket mencakup lint PHP, sintaks JavaScript, pembacaan CSV, dan pemetaan kategori tampilan. MySQL nyata, peta Leaflet daring, izin GPS perangkat, dan respons OSRM langsung tetap harus diuji pada komputer/server pengguna sebelum dijadikan bukti BAB IV. Lihat `HASIL_PEMERIKSAAN.md`.
