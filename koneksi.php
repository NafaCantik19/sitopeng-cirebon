<?php
require_once __DIR__.'/includes/data_wisata.php';
$config=require __DIR__.'/config.php';
try {
    if (!extension_loaded('mysqli')) throw new RuntimeException('Ekstensi mysqli belum aktif. Jalankan proyek melalui PHP bawaan XAMPP dengan Apache dan MySQL aktif.');
    if (!preg_match('/^[A-Za-z0-9_]+$/', $config['database'])) throw new RuntimeException('Nama database hanya boleh berisi huruf, angka, dan garis bawah.');
    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
    try {
        $conn=new mysqli($config['host'],$config['user'],$config['password'],$config['database'],$config['port']);
    } catch (mysqli_sql_exception $e) {
        if ((int)$e->getCode() !== 1049) throw $e;
        $conn=new mysqli($config['host'],$config['user'],$config['password'],'',$config['port']);
        $conn->query('CREATE DATABASE IF NOT EXISTS `'.$config['database'].'` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci');
        $conn->select_db($config['database']);
    }
    $conn->set_charset('utf8mb4');
    sig_bootstrap($conn);
    sig_ensure_default_admin($conn);
} catch (Throwable $e) {
    http_response_code(503);
    error_log('SIG Kota Cirebon: '.$e->getMessage());
    $message=$e instanceof RuntimeException && !($e instanceof mysqli_sql_exception) ? $e->getMessage() : 'Koneksi database belum berhasil. Aktifkan MySQL dan cocokkan pengaturan config.php. Rincian tersedia pada log PHP.';
    die('<!doctype html><html lang="id"><meta charset="utf-8"><title>Koneksi database</title><body><h2>SITOPENG</h2><p>'.sig_h($message).'</p><p>Database baru: '.sig_h($config['database']).'. Database proyek lama tidak ditimpa.</p></body></html>');
}
