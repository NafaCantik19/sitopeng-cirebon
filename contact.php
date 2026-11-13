<?php
require_once 'koneksi.php';
require_once 'includes/public_ui.php';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kontak - SITOPENG</title>
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
            <div class="d-flex flex-column ms-2">
                <span class="latin-text">SITOPENG</span>
                <span class="sitopeng-brand-sub">WebGIS Pariwisata Kota Cirebon</span>
                <span class="aksara-text">Jelajahi Cirebon, Temukan Pesonanya.</span>
            </div>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation"><i class="fa-solid fa-bars"></i></button>
        <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
            <ul class="navbar-nav align-items-lg-center mt-3 mt-lg-0">
                <li class="nav-item"><a class="nav-link fw-bold" href="index.php"><i class="fa-solid fa-map me-1"></i>Peta</a></li>
                <li class="nav-item"><a class="nav-link fw-bold" href="wisata.php"><i class="fa-solid fa-list me-1"></i>Daftar Wisata</a></li>
                <li class="nav-item"><a class="nav-link fw-bold" href="about.php"><i class="fa-solid fa-landmark me-1"></i>Tentang Cirebon</a></li>
                <li class="nav-item"><a class="nav-link fw-bold active" href="contact.php"><i class="fa-solid fa-address-card me-1"></i>Kontak</a></li>
                <li class="nav-item"><a class="nav-link fw-bold" href="masukan.php"><i class="fa-solid fa-message me-1"></i>Masukan</a></li>
                <li class="nav-item ms-lg-3 mt-2 mt-lg-0"><a href="admin/login.php" class="btn px-4"><i class="fa-solid fa-right-to-bracket me-1"></i>Admin Login</a></li>
            </ul>
        </div>
    </div>
    <div class="ticker-wrapper" style="flex-basis:100%"><div class="ticker-text"><i class="fa-solid fa-cloud"></i> Mampir Sedela, Kenang Salawase. &nbsp;&nbsp;&nbsp;&nbsp; <i class="fa-solid fa-envelope"></i> Hubungi pengembang WebGIS untuk pertanyaan dan masukan. &nbsp;&nbsp;&nbsp;&nbsp; <i class="fa-solid fa-cloud"></i> Mampir Sedela, Kenang Salawase. &nbsp;&nbsp;&nbsp;&nbsp; <i class="fa-solid fa-envelope"></i> Hubungi pengembang WebGIS untuk pertanyaan dan masukan.</div></div>
</nav>

<section class="contact-shell">
    <div class="container">
        <div class="contact-grid">
            <div class="contact-card">
                <div class="small fw-bold text-uppercase text-primary mb-2" style="letter-spacing:.12em">Kontak Pengembang</div>
                <h1 class="mb-3">Hubungi SITOPENG</h1>
                <p class="text-secondary">Kontak berikut digunakan untuk komunikasi terkait proyek WebGIS Pariwisata Kota Cirebon, koreksi data destinasi, dan pertanyaan mengenai penggunaan sistem.</p>
                <div class="contact-chip">
                    <i class="fa-solid fa-user"></i>
                    <div><div class="small text-secondary">Nama</div><strong>Nafa Fadhila</strong></div>
                </div>
                <div class="contact-chip">
                    <i class="fa-solid fa-envelope"></i>
                    <div><div class="small text-secondary">Email</div><a href="mailto:nfadhilla78@gmail.com">nfadhilla78@gmail.com</a></div>
                </div>
            </div>
            <div class="contact-card">
                <div class="small fw-bold text-uppercase text-primary mb-2" style="letter-spacing:.12em">Komunikasi & Masukan</div>
                <h2 class="mb-3">Ada Koreksi atau Saran?</h2>
                <p class="text-secondary">Gunakan formulir masukan untuk menyampaikan koreksi nama destinasi, lokasi, koordinat, tampilan, atau kendala saat menggunakan fitur Haversine dan OSRM.</p>
                <div class="d-flex gap-3 flex-wrap mt-4">
                    <a href="masukan.php" class="btn btn-primary rounded-pill px-4 py-3 fw-bold"><i class="fa-solid fa-message me-2"></i>Kirim Masukan</a>
                    <a href="mailto:nfadhilla78@gmail.com" class="btn btn-outline-primary rounded-pill px-4 py-3 fw-bold"><i class="fa-solid fa-paper-plane me-2"></i>Kirim Email</a>
                </div>
                <div class="mt-4 p-3 rounded-4" style="background:#f5f8ff;border:1px solid #e0e8f6">
                    <strong class="d-block mb-1">Catatan</strong>
                    <span class="text-secondary small">Masukan yang dikirim melalui formulir disimpan secara lokal pada server proyek untuk kebutuhan evaluasi demo dan pengembangan skripsi.</span>
                </div>
            </div>
        </div>
    </div>
</section>

<?php sig_public_footer(); ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
