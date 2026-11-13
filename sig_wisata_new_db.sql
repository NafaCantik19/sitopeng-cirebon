-- OPSIONAL: struktur kosong saja, bukan dataset lama.
-- Untuk pemakaian biasa TIDAK PERLU mengimpor berkas ini.
-- Buka index.php: aplikasi membuat database lalu mengimpor CSV satu kali.
-- Database lama tidak dihapus. Tidak ada akun/sandi admin bawaan.
CREATE DATABASE IF NOT EXISTS sig_wisata_kota_asli_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE sig_wisata_kota_asli_db;
CREATE TABLE IF NOT EXISTS admin (
 id INT AUTO_INCREMENT PRIMARY KEY, username VARCHAR(50) NOT NULL UNIQUE,
 password VARCHAR(255) NOT NULL, nama_lengkap VARCHAR(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
CREATE TABLE IF NOT EXISTS wisata (
 id_wisata INT AUTO_INCREMENT PRIMARY KEY, nama_wisata VARCHAR(191) NOT NULL,
 deskripsi TEXT, alamat TEXT, lat VARCHAR(50) NULL, lng VARCHAR(50) NULL,
 kategori VARCHAR(150), jam_buka VARCHAR(150), harga_tiket VARCHAR(200), gambar_utama VARCHAR(255),
 harga_min INT UNSIGNED DEFAULT NULL, harga_max INT UNSIGNED DEFAULT NULL,
 durasi_kunjungan SMALLINT UNSIGNED DEFAULT NULL, jam_mulai TIME DEFAULT NULL,
 jam_selesai TIME DEFAULT NULL, rating_manual DECIMAL(3,2) DEFAULT NULL,
 status_koordinat VARCHAR(255) DEFAULT 'Belum diverifikasi', sumber_csv LONGTEXT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
CREATE TABLE IF NOT EXISTS galeri (
 id_galeri INT AUTO_INCREMENT PRIMARY KEY, id_wisata INT NOT NULL, foto VARCHAR(255) NOT NULL,
 FOREIGN KEY (id_wisata) REFERENCES wisata(id_wisata) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Sesudah struktur dibuat, buka aplikasi untuk impor 35 entri CSV.
-- Buat akun pertama melalui admin/setup.php dari localhost.
