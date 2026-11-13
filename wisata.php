<?php
require_once 'koneksi.php';
require_once 'includes/public_ui.php';
$query = "SELECT * FROM wisata ORDER BY id_wisata ASC";
$result = mysqli_query($conn, $query);
$wisata_data = [];
while($row = mysqli_fetch_assoc($result)){
    $wisata_data[] = $row;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Wisata - SITOPENG</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css"/>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@700;800;900&display=swap" rel="stylesheet">
    <!-- Cirebon Culture Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Cinzel+Decorative:wght@700&family=Noto+Sans+Javanese&display=swap" rel="stylesheet">

    <style>
        :root {
            --primary-color: #4361ee;
            --secondary-color: #3f37c9;
            --accent-color: #4cc9f0;
            --gradient-1: linear-gradient(135deg, #4361ee, #4cc9f0);
            --text-main: #2b2d42;
        }

        body {
            font-family: 'Outfit', 'Plus Jakarta Sans', sans-serif;
            background-color: #f0f2f5;
            color: var(--text-main);
        }

        /* Modern Cozy Navbar */
        .navbar-custom {
            position: sticky;
            top: 0;
            z-index: 1050;
            padding: 0;
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
            background: #ffffff;
            box-shadow: 0 4px 30px rgba(0, 0, 0, 0.05);
            overflow: hidden;
        }

        .ticker-wrapper {
            width: 100%;
            background: linear-gradient(90deg, #1e3c72, #2a5298);
            border-top: 3px solid var(--accent-color);
            color: #fff;
            padding: 6px 0;
            font-family: 'Poppins', sans-serif;
            font-size: 0.85rem;
            font-style: italic;
            font-weight: 600;
            letter-spacing: 1px;
            overflow: hidden;
            display: flex;
            white-space: nowrap;
        }
        
        .section-title {
            font-weight: 800;
            color: var(--text-main);
            position: relative;
            display: inline-block;
            padding-bottom: 12px;
            font-family: 'Outfit', sans-serif;
            letter-spacing: -0.5px;
        }
        
        .ticker-text {
            display: inline-block;
            white-space: nowrap;
            padding-right: 50px;
            animation: scroller 25s linear infinite;
        }
        
        .ticker-text:hover {
            animation-play-state: paused;
        }

        @keyframes scroller {
            0% { transform: translateX(0); }
            100% { transform: translateX(-50%); }
        }

        .navbar-custom::before {
            content: "";
            position: absolute;
            top: 0; left: 0; right: 0; bottom: 0;
            background-image: url('assets/img/nav-bg.jpg?v=3');
            background-position: center 85%;
            background-size: cover;
            background-repeat: no-repeat;
            opacity: 0.35;
            z-index: 0;
            pointer-events: none;
        }
        
        .navbar-custom .container {
            position: relative;
            z-index: 1;
        }
        
        .navbar-brand {
            font-weight: 800;
            font-size: 1.6rem;
            color: #ffffff !important;
        }

        .navbar-brand .latin-text {
            font-family: 'Poppins', sans-serif;
            font-weight: 900;
            color: var(--primary-color);
            font-size: 1.45rem;
            letter-spacing: 0.5px;
            line-height: 1.1;
            margin-bottom: -2px;
            display: block;
        }

        .navbar-brand .aksara-text {
            font-family: 'Noto Sans Javanese', sans-serif;
            font-size: 0.95rem;
            color: var(--secondary-color);
            letter-spacing: 2px;
            line-height: 1;
            display: block;
            text-transform: none;
            opacity: 0.8;
        }

        .nav-link {
            font-weight: 600;
            color: #444444 !important;
            transition: all 0.3s ease;
            padding: 8px 16px !important;
            border-radius: 12px;
            margin: 0 4px;
        }

        .nav-link:hover, .nav-link.active {
            background: rgba(67, 97, 238, 0.1);
            color: var(--primary-color) !important;
            transform: translateY(-2px);
        }

        .hero-section {
            background: url('assets/img/bg-heading.jpg?v=2') center/cover no-repeat;
            color: white;
            padding: 140px 0;
            text-align: center;
            position: relative;
            overflow: hidden;
            border-bottom: 3px solid var(--accent-color);
        }
        
        .hero-section::before {
            content: '';
            position: absolute;
            top: 0; left: 0; width: 100%; height: 100%;
            background: linear-gradient(rgba(43, 45, 66, 0.3), rgba(43, 45, 66, 0.7));
            backdrop-filter: blur(3px); /* Sedikit efek kaca agar gambar sangat jelas */
            -webkit-backdrop-filter: blur(3px);
            z-index: 0;
        }

        /* 3D Animations */
        @keyframes float3D {
            0% { 
                transform: translateY(0px) rotateX(0deg) rotateY(0deg); 
                text-shadow: 
                    0 1px 0 #4cc9f0, 0 2px 0 #4895ef, 0 3px 0 #4361ee, 0 4px 0 #3f37c9, 0 5px 0 #3a0ca3, 
                    0 6px 1px rgba(0,0,0,.1), 0 0 10px rgba(67,97,238,.4), 0 1px 3px rgba(0,0,0,.3), 
                    0 3px 5px rgba(0,0,0,.2), 0 5px 10px rgba(0,0,0,.25), 0 10px 10px rgba(0,0,0,.2), 
                    0 20px 20px rgba(0,0,0,.15); 
            }
            50% { 
                transform: translateY(-15px) rotateX(15deg) rotateY(-8deg) scale(1.05); 
                text-shadow: 
                    0 1px 0 #4cc9f0, 0 2px 0 #4895ef, 0 3px 0 #4361ee, 0 4px 0 #3f37c9, 0 5px 0 #3a0ca3, 
                    0 6px 1px rgba(0,0,0,.1), 0 0 15px rgba(76,201,240,.6), 0 1px 3px rgba(0,0,0,.3), 
                    0 3px 5px rgba(0,0,0,.2), 0 5px 10px rgba(0,0,0,.25), 0 15px 15px rgba(67,97,238,.5), 
                    0 30px 30px rgba(0,0,0,.3); 
            }
            100% { 
                transform: translateY(0px) rotateX(0deg) rotateY(0deg); 
                text-shadow: 
                    0 1px 0 #4cc9f0, 0 2px 0 #4895ef, 0 3px 0 #4361ee, 0 4px 0 #3f37c9, 0 5px 0 #3a0ca3, 
                    0 6px 1px rgba(0,0,0,.1), 0 0 10px rgba(67,97,238,.4), 0 1px 3px rgba(0,0,0,.3), 
                    0 3px 5px rgba(0,0,0,.2), 0 5px 10px rgba(0,0,0,.25), 0 10px 10px rgba(0,0,0,.2), 
                    0 20px 20px rgba(0,0,0,.15); 
            }
        }

        .animated-3d-text {
            display: inline-block;
            animation: float3D 4s cubic-bezier(0.4, 0, 0.2, 1) infinite;
            font-family: 'Outfit', sans-serif;
            font-weight: 900;
            color: #ffffff;
            perspective: 1000px;
            letter-spacing: 2px;
            transform-style: preserve-3d;
            margin-bottom: 15px;
        }

        @keyframes floatShape {
            0% { transform: translateY(0) rotate(0deg) scale(1); }
            50% { transform: translateY(-40px) rotate(180deg) scale(1.1); opacity: 0.8; }
            100% { transform: translateY(0) rotate(360deg) scale(1); }
        }

        .shape-3d {
            position: absolute;
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border-radius: 20px;
            animation: floatShape 8s infinite cubic-bezier(0.4, 0, 0.2, 1);
            z-index: 1;
            box-shadow: 0 15px 35px rgba(0,0,0,0.2), inset 0 0 20px rgba(255,255,255,0.3);
            border: 1px solid rgba(255,255,255,0.4);
        }
        
        .hero-content {
            position: relative;
            z-index: 2;
        }

        .wisata-card {
            border: 1px solid rgba(255,255,255,0.6);
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0,0,0,0.08);
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            background: rgba(255, 255, 255, 0.85); /* Tembus pandang Glass Effect */
            backdrop-filter: blur(10px);
        }

        .wisata-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 15px 40px rgba(0,0,0,0.1);
        }

        .wisata-img {
            height: 220px;
            object-fit: cover;
            width: 100%;
        }

        .wisata-body {
            padding: 24px;
        }

        .btn-gradient {
            background: var(--gradient-1);
            color: white !important;
            border: none;
            border-radius: 50px;
            padding: 10px 24px;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(67, 97, 238, 0.3);
        }

        .btn-gradient:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(67, 97, 238, 0.4);
        }

        .wisata-title {
            font-family: 'Outfit', sans-serif;
            font-weight: 700;
            color: var(--primary-color);
            transition: color 0.3s ease;
        }
        .wisata-card:hover .wisata-title {
            color: var(--accent-color);
        }
        /* Logo Interactive Effect (3D Coin Spin) */
        .navbar-brand img {
            transition: all 0.8s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            transform-style: preserve-3d;
        }
        .navbar-brand:hover img {
            transform: rotateY(360deg) scale(1.15);
            filter: drop-shadow(0 0 15px rgba(255,255,255,0.8));
        }

        /* 3D Hover on Cards */
        .card-wisata {
            transition: transform 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275), box-shadow 0.4s ease;
        }
        .card-wisata:hover {
            transform: translateY(-12px) scale(1.02);
            box-shadow: 0 25px 50px rgba(67, 97, 238, 0.2) !important;
        }
        .search-panel .form-control,
        .search-panel .form-select {
            min-height: 56px;
            border-radius: 999px;
        }
        .badge-category {
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            padding: 0.55rem 0.9rem;
            font-size: 0.8rem;
            font-weight: 700;
            border-radius: 999px;
            color: #fff;
            backdrop-filter: blur(8px);
            background: rgba(0, 0, 0, 0.2);
            margin-bottom: 0.75rem;
        }
        .wisata-sejarah  { background: rgba(229, 57, 53, 0.9); }
        .wisata-budaya   { background: rgba(117, 80, 255, 0.9); }
        .wisata-religi   { background: rgba(0, 148, 146, 0.9); }
        .wisata-kuliner  { background: rgba(67, 97, 238, 0.9); }
    </style>
<link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&family=Cormorant+Garamond:wght@500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="assets/css/cirebon-theme.css?v=4">

<link rel="stylesheet" href="assets/css/public-enhancements.css?v=1">
</head>
<body>
<?php sig_research_bar(); ?>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-custom flex-wrap">
        <div class="container px-4 py-4">
            <a class="navbar-brand d-flex align-items-center text-decoration-none" href="index.php" style="text-transform: uppercase;">
                <img src="assets/img/logo.png?v=<?= time() ?>" alt="Logo SITOPENG" style="height: 122px; object-fit: contain; margin-right: 18px; filter: drop-shadow(0 4px 8px rgba(0,0,0,0.24)); transform: scale(1.08);">
                <div class="d-flex flex-column justify-content-center pt-1">
                    <span class="latin-text">SITOPENG</span>
                    <span class="sitopeng-brand-sub">WebGIS Pariwisata Kota Cirebon</span>
                    <span class="aksara-text">Jelajahi Cirebon, Temukan Pesonanya.</span>
                </div>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <i class="fa-solid fa-bars" style="color: var(--primary-color);"></i>
            </button>
            <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
                <ul class="navbar-nav align-items-center" style="font-size: 1.1rem;">
                    <li class="nav-item"><a class="nav-link fw-bold" href="index.php"><i class="fa-solid fa-map me-1"></i>Peta</a></li>
                    <li class="nav-item">
                        <a class="nav-link fw-bold active" href="wisata.php"><i class="fa-solid fa-list me-1"></i>Daftar Wisata</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link fw-bold" href="about.php"><i class="fa-solid fa-circle-info me-1"></i>Tentang Cirebon</a>
                    </li>
                    <li class="nav-item"><a class="nav-link fw-bold" href="contact.php"><i class="fa-solid fa-address-card me-1"></i>Kontak</a></li>
                    <li class="nav-item"><a class="nav-link fw-bold" href="masukan.php"><i class="fa-solid fa-message me-1"></i>Masukan</a></li>
                    <li class="nav-item ms-lg-3 mt-2 mt-lg-0">
                        <a href="admin/login.php" class="btn px-4" style="background: var(--primary-color); color: white; border-radius: 50px; font-weight: 700; box-shadow: 0 4px 15px rgba(0,0,0,0.1);"><i class="fa-solid fa-right-to-bracket me-1"></i> Admin Login</a>
                    </li>
                </ul>
            </div>
        </div>
        
        <!-- Marquee Ticker Divider -->
        <div class="ticker-wrapper" style="flex-basis: 100%; z-index: 2;">
            <!-- Repeated twice seamlessly inside ticker-text for infinite effect -->
            <div class="ticker-text">
                <i class="fa-solid fa-cloud" style="color:var(--accent-color);"></i> Mampir Sedela, Kenang Salawase. &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
                <i class="fa-solid fa-cloud" style="color:var(--accent-color);"></i> Mampir Sedela, Kenang Salawase. &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
                <i class="fa-solid fa-cloud" style="color:var(--accent-color);"></i> Mampir Sedela, Kenang Salawase. &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
                <i class="fa-solid fa-cloud" style="color:var(--accent-color);"></i> Mampir Sedela, Kenang Salawase. &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
                <!-- Text block duplicated exactly below for 50% shift loop -->
                <i class="fa-solid fa-cloud" style="color:var(--accent-color);"></i> Mampir Sedela, Kenang Salawase. &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
                <i class="fa-solid fa-cloud" style="color:var(--accent-color);"></i> Mampir Sedela, Kenang Salawase. &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
                <i class="fa-solid fa-cloud" style="color:var(--accent-color);"></i> Mampir Sedela, Kenang Salawase. &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
                <i class="fa-solid fa-cloud" style="color:var(--accent-color);"></i> Mampir Sedela, Kenang Salawase. &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
            </div>
        </div>
    </nav>

    <!-- Hero -->
    <div class="hero-section">
        <!-- Floating Animated 3D Shapes -->
        <div class="shape-3d" style="background: linear-gradient(135deg, rgba(67,97,238,0.5), rgba(76,201,240,0.5)); width: 120px; height: 120px; top: 15%; left: 10%; animation-duration: 6s; transform-origin: center;"></div>
        <div class="shape-3d" style="background: linear-gradient(135deg, rgba(58,12,163,0.5), rgba(67,97,238,0.5)); width: 160px; height: 160px; bottom: 5%; right: 8%; animation-duration: 9s; border-radius: 50%;"></div>
        <div class="shape-3d" style="background: linear-gradient(135deg, rgba(248,150,30,0.5), rgba(249,199,79,0.5)); width: 70px; height: 70px; top: 35%; right: 25%; animation-duration: 7s; border-radius: 10px;"></div>
        <div class="shape-3d" style="background: linear-gradient(135deg, rgba(6,214,160,0.5), rgba(76,201,240,0.5)); width: 90px; height: 90px; bottom: 20%; left: 20%; animation-duration: 8s; border-radius: 30%;"></div>

        <div class="container hero-content">
            <div class="row align-items-center">
                <div class="col-lg-8 mx-auto text-center">
                    <div class="d-inline-block rounded-pill fw-bold badge-custom px-4 py-2 mb-4" style="border: 1px solid rgba(255,255,255,0.4); background: rgba(0,0,0,0.2); backdrop-filter: blur(5px);">
                        <i class="fa-solid fa-map-location-dot me-2 text-warning"></i>Daftar Wisata Pilihan
                    </div>
                    <!-- The typewriter target -->
                    <h1 class="fw-bold mb-3 animated-3d-text" style="font-size: 3.5rem; line-height: 1.2;"><span id="typewriter-text"></span><span class="type-cursor">|</span></h1>
                    <p class="text-white opacity-75 mb-0 mx-auto" style="font-weight: 400; font-size: 1.25rem; max-width: 800px; line-height: 1.6;">
                        Temukan berbagai tempat menarik, sejarah unik, kuliner lezat, dan pesona wisata di sudut-sudut Kota Udang.
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Content -->
    <div class="container py-5">
        <div class="row align-items-center mb-4">
            <div class="col-lg-5">
                <h2 class="section-title">Eksplorasi Wisata Cirebon</h2>
                <p class="text-muted">Total <strong id="wisata-count"><?= count($wisata_data) ?></strong> entri inventaris tersedia; koordinat yang belum pasti tetap ditandai.</p>
            </div>
            <div class="col-lg-4 mt-3 mt-lg-0">
                <div class="input-group shadow-sm">
                    <span class="input-group-text bg-white border-end-0"><i class="fa-solid fa-magnifying-glass text-primary"></i></span>
                    <input id="searchInput" type="text" class="form-control border-start-0" placeholder="Cari nama, kategori, atau lokasi...">
                </div>
            </div>
            <div class="col-lg-3 mt-3 mt-lg-0">
                <select id="categoryFilter" class="form-select shadow-sm">
                    <option value="">Semua Kelompok Wisata</option>
                    <?php foreach (sig_category_groups() as $group): ?>
                    <option value="<?= sig_h($group) ?>"><?= sig_h($group) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>

        <div class="row g-4" id="wisataContainer">
            <?php if(empty($wisata_data)): ?>
                <div class="col-12 text-center py-5">
                    <h4 class="text-muted">Data wisata belum tersedia.</h4>
                </div>
            <?php else: ?>
                <?php foreach($wisata_data as $wisata): ?>
                    <div class="col-md-4 col-sm-6 wisata-item">
                        <?php 
                        $img_src = sig_image($wisata);
                        $searchData = strtolower($wisata['nama_wisata'] . ' ' . $wisata['kategori'] . ' ' . $wisata['alamat'] . ' ' . $wisata['deskripsi']);
                        ?>
                        <div class="card wisata-card category-card <?= sig_h(sig_category_class($wisata['kategori'])) ?> h-100" data-search="<?= htmlspecialchars($searchData) ?>" data-category-group="<?= sig_h(sig_category_group($wisata['kategori'])) ?>">
                            <img src="<?= sig_h($img_src) ?>" class="wisata-img" alt="<?= htmlspecialchars($wisata['nama_wisata']) ?>" title="<?= empty($wisata['gambar_utama']) ? 'Foto ilustrasi bawaan proyek, bukan dokumentasi destinasi ini' : 'Foto dari proyek awal; kesesuaian perlu diperiksa pengelola' ?>">
                            <div class="card-body wisata-body d-flex flex-column">
                                <span class="badge badge-category mb-3"><?= htmlspecialchars($wisata['kategori']) ?></span>
                                <h5 class="fw-bold wisata-title" style="font-size: 1.25rem; margin-bottom: 0.6rem;"><?= htmlspecialchars($wisata['nama_wisata']) ?></h5>
                                <p class="text-muted mb-3 small"><i class="fa-solid fa-location-dot me-2"></i><?= htmlspecialchars($wisata['alamat']) ?></p>
                                <p class="text-muted mb-4 flex-grow-1" style="font-size: 0.95rem; line-height: 1.6; text-align: justify;">
                                    <?= sig_h(implode('', array_slice(preg_split('//u', (string)$wisata['deskripsi'], -1, PREG_SPLIT_NO_EMPTY), 0, 120))) ?>...
                                </p>
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <span class="text-secondary small"><i class="fa-regular fa-clock me-1"></i><?= sig_coords($wisata['lat'], $wisata['lng']) ? sig_h($wisata['jam_buka']) : 'Koordinat perlu verifikasi' ?></span>
                                    <span class="text-secondary small"><i class="fa-solid fa-ticket me-1"></i><?= htmlspecialchars($wisata['harga_tiket']) ?></span>
                                </div>
                                <a href="detail.php?id=<?= $wisata['id_wisata'] ?>" class="btn btn-outline-primary rounded-pill w-100 mt-auto" style="border-width: 2px; font-weight: 600;">Lihat Detail</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>

    <!-- Footer Data Diri -->
<?php sig_public_footer(); ?>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    
    <style>
    .type-cursor {
        animation: blink 0.8s infinite;
        font-weight: 300;
        color: #ffb703;
    }
    @keyframes blink {
        0%, 100% { opacity: 1; }
        50% { opacity: 0; }
    }
    </style>
    
    <script>
        // JS Typewriter Effect
        document.addEventListener("DOMContentLoaded", function() {
            const textToType = "Eksplorasi Kota Cirebon";
            const targetElement = document.getElementById("typewriter-text");
            let index = 0;
            
            function typeWriter() {
                if (index < textToType.length) {
                    targetElement.innerHTML += textToType.charAt(index);
                    index++;
                    setTimeout(typeWriter, 100);
                }
            }
            setTimeout(typeWriter, 500);

            const searchInput = document.getElementById('searchInput');
            const categoryFilter = document.getElementById('categoryFilter');
            const wisataItems = document.querySelectorAll('.wisata-item');
            const wisataCount = document.getElementById('wisata-count');

            function filterWisata() {
                const query = searchInput.value.toLowerCase().trim();
                const selectedCategory = categoryFilter.value;
                let visibleCount = 0;

                wisataItems.forEach(item => {
                    const card = item.querySelector('.wisata-card');
                    const searchText = card.dataset.search;
                    const categoryGroup = card.dataset.categoryGroup;
                    const matchesSearch = !query || searchText.includes(query);
                    const matchesCategory = !selectedCategory || categoryGroup === selectedCategory;

                    if (matchesSearch && matchesCategory) {
                        item.style.display = 'block';
                        visibleCount++;
                    } else {
                        item.style.display = 'none';
                    }
                });

                wisataCount.textContent = visibleCount;
            }

            searchInput.addEventListener('input', filterWisata);
            categoryFilter.addEventListener('change', filterWisata);
        });
    </script>
</body>
</html>
