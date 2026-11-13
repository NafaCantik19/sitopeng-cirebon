<?php
require_once 'koneksi.php';
require_once 'includes/public_ui.php';
if (session_status() !== PHP_SESSION_ACTIVE) session_start();
if (empty($_SESSION['feedback_csrf'])) $_SESSION['feedback_csrf']=bin2hex(random_bytes(24));
$success=''; $error='';
$name=''; $email=''; $message=''; $category='Saran tampilan';
if ($_SERVER['REQUEST_METHOD']==='POST') {
    try {
        $token=(string)($_POST['csrf'] ?? '');
        if (!hash_equals($_SESSION['feedback_csrf'],$token)) throw new RuntimeException('Sesi formulir tidak valid. Muat ulang halaman dan coba lagi.');
        $name=trim((string)($_POST['name'] ?? ''));
        $email=trim((string)($_POST['email'] ?? ''));
        $category=trim((string)($_POST['category'] ?? 'Saran tampilan'));
        $message=trim((string)($_POST['message'] ?? ''));
        $allowed=['Saran tampilan','Koreksi data wisata','Kendala Haversine','Kendala OSRM / navigasi','Masukan umum'];
        if ($name==='' || mb_strlen($name)>100) throw new RuntimeException('Nama wajib diisi dan maksimal 100 karakter.');
        if ($email!=='' && (!filter_var($email,FILTER_VALIDATE_EMAIL) || mb_strlen($email)>150)) throw new RuntimeException('Format email tidak valid.');
        if (!in_array($category,$allowed,true)) throw new RuntimeException('Kategori masukan tidak valid.');
        if (mb_strlen($message)<5 || mb_strlen($message)>1500) throw new RuntimeException('Masukan harus berisi 5 sampai 1500 karakter.');
        $dir=__DIR__.'/storage';
        if (!is_dir($dir) && !mkdir($dir,0755,true)) throw new RuntimeException('Folder penyimpanan masukan tidak dapat dibuat.');
        $file=$dir.'/masukan.csv';
        $isNew=!file_exists($file) || filesize($file)===0;
        $fp=fopen($file,'ab');
        if(!$fp) throw new RuntimeException('Masukan belum dapat disimpan. Periksa izin folder proyek.');
        if(!flock($fp,LOCK_EX)){fclose($fp);throw new RuntimeException('Penyimpanan sedang digunakan. Coba lagi.');}
        if($isNew) fputcsv($fp,['waktu','nama','email','kategori','masukan']);
        fputcsv($fp,[date('Y-m-d H:i:s'),$name,$email,$category,$message]);
        fflush($fp); flock($fp,LOCK_UN); fclose($fp);
        $success='Terima kasih. Masukan berhasil disimpan untuk evaluasi proyek.';
        $name=$email=$message=''; $category='Saran tampilan';
        $_SESSION['feedback_csrf']=bin2hex(random_bytes(24));
    } catch(Throwable $e){ $error=$e->getMessage(); }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masukan - SITOPENG</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"/>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&family=Cormorant+Garamond:wght@500;600;700&family=Noto+Sans+Javanese&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/cirebon-theme.css?v=5">
    <link rel="stylesheet" href="assets/css/public-enhancements.css?v=2">
</head>
<body>
<?php sig_research_bar(); ?>
<nav class="navbar navbar-expand-lg navbar-custom flex-wrap">
    <div class="container px-4 py-3">
        <a class="navbar-brand d-flex align-items-center text-decoration-none" href="index.php">
            <img src="assets/img/logo.png?v=2" alt="Logo SITOPENG" class="brand-logo">
            <div class="d-flex flex-column ms-2"><span class="latin-text">SITOPENG</span><span class="sitopeng-brand-sub">WebGIS Pariwisata Kota Cirebon</span><span class="aksara-text">Jelajahi Cirebon, Temukan Pesonanya.</span></div>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation"><i class="fa-solid fa-bars"></i></button>
        <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
            <ul class="navbar-nav align-items-lg-center mt-3 mt-lg-0">
                <li class="nav-item"><a class="nav-link fw-bold" href="index.php"><i class="fa-solid fa-map me-1"></i>Peta</a></li>
                <li class="nav-item"><a class="nav-link fw-bold" href="wisata.php"><i class="fa-solid fa-list me-1"></i>Daftar Wisata</a></li>
                <li class="nav-item"><a class="nav-link fw-bold" href="about.php"><i class="fa-solid fa-landmark me-1"></i>Tentang Cirebon</a></li>
                <li class="nav-item"><a class="nav-link fw-bold" href="contact.php"><i class="fa-solid fa-address-card me-1"></i>Kontak</a></li>
                <li class="nav-item"><a class="nav-link fw-bold active" href="masukan.php"><i class="fa-solid fa-message me-1"></i>Masukan</a></li>
                <li class="nav-item ms-lg-3 mt-2 mt-lg-0"><a href="admin/login.php" class="btn px-4"><i class="fa-solid fa-right-to-bracket me-1"></i>Admin Login</a></li>
            </ul>
        </div>
    </div>
    <div class="ticker-wrapper" style="flex-basis:100%"><div class="ticker-text"><i class="fa-solid fa-message"></i> Masukan Anda membantu evaluasi WebGIS. &nbsp;&nbsp;&nbsp;&nbsp; <i class="fa-solid fa-route"></i> Laporkan kendala rute, data, dan tampilan. &nbsp;&nbsp;&nbsp;&nbsp; <i class="fa-solid fa-message"></i> Masukan Anda membantu evaluasi WebGIS.</div></div>
</nav>
<section class="contact-shell">
    <div class="container">
        <div class="contact-grid">
            <div class="contact-card">
                <div class="small fw-bold text-uppercase text-primary mb-2" style="letter-spacing:.12em">Formulir Masukan</div>
                <h1 class="mb-3">Bantu Tingkatkan WebGIS</h1>
                <p class="text-secondary">Masukan dapat digunakan untuk mencatat saran tampilan, koreksi data destinasi, serta kendala pada perhitungan Haversine dan navigasi OSRM.</p>
                <div class="contact-chip"><i class="fa-solid fa-user-pen"></i><div><div class="small text-secondary">Pengelola</div><strong>Nafa Fadhila</strong></div></div>
                <div class="contact-chip"><i class="fa-solid fa-envelope"></i><div><div class="small text-secondary">Email</div><a href="mailto:nfadhilla78@gmail.com">nfadhilla78@gmail.com</a></div></div>
            </div>
            <div class="contact-card">
                <?php if($success): ?><div class="success-box mb-3"><i class="fa-solid fa-circle-check me-2"></i><?= sig_h($success) ?></div><?php endif; ?>
                <?php if($error): ?><div class="error-box mb-3"><i class="fa-solid fa-triangle-exclamation me-2"></i><?= sig_h($error) ?></div><?php endif; ?>
                <form method="post" class="feedback-form" novalidate>
                    <input type="hidden" name="csrf" value="<?= sig_h($_SESSION['feedback_csrf']) ?>">
                    <div class="mb-3"><label class="form-label">Nama</label><input class="form-control" name="name" maxlength="100" required value="<?= sig_h($name) ?>" placeholder="Nama pengirim"></div>
                    <div class="mb-3"><label class="form-label">Email <span class="text-secondary fw-normal">(opsional)</span></label><input class="form-control" type="email" name="email" maxlength="150" value="<?= sig_h($email) ?>" placeholder="nama@email.com"></div>
                    <div class="mb-3"><label class="form-label">Kategori</label><select class="form-select" name="category"><?php foreach(['Saran tampilan','Koreksi data wisata','Kendala Haversine','Kendala OSRM / navigasi','Masukan umum'] as $opt): ?><option value="<?= sig_h($opt) ?>" <?= $category===$opt?'selected':'' ?>><?= sig_h($opt) ?></option><?php endforeach; ?></select></div>
                    <div class="mb-3"><label class="form-label">Masukan</label><textarea class="form-control" name="message" rows="6" maxlength="1500" required placeholder="Tuliskan saran atau kendala secara jelas..."><?= sig_h($message) ?></textarea></div>
                    <div class="d-flex justify-content-between align-items-center gap-3 flex-wrap"><span class="feedback-note">Data masukan disimpan di server lokal proyek.</span><button class="btn-submit" type="submit"><i class="fa-solid fa-paper-plane me-2"></i>Kirim Masukan</button></div>
                </form>
            </div>
        </div>
    </div>
</section>
<?php sig_public_footer(); ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
