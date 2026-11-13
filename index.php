<?php
require_once 'koneksi.php';
require_once 'includes/public_ui.php';
// Fetch all wisata data
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
    <title>Sistem Informasi Geografis Wisata Cirebon</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome for Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css"/>
    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>
    <!-- Leaflet Routing Machine CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet-routing-machine/dist/leaflet-routing-machine.css"/>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@700;800;900&display=swap" rel="stylesheet">
    <!-- Cirebon Culture Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Cinzel+Decorative:wght@700&family=Noto+Sans+Javanese&display=swap" rel="stylesheet">

    <style>
        :root {
            --primary-color: #4361ee;
            --secondary-color: #3f37c9;
            --accent-color: #4cc9f0;
            --glass-bg: rgba(255, 255, 255, 0.70);
            --gradient-1: linear-gradient(135deg, #4361ee, #4cc9f0);
            --text-main: #2b2d42;
        }

        body, html {
            height: 100%;
            margin: 0;
            font-family: 'Plus Jakarta Sans', sans-serif;
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

        /* Map Container Wrapper */
        .map-layout-wrapper {
            background: #f8f9fa;
            background-image: url('assets/img/batik-new.png');
            background-size: 150px;
            background-position: center;
            background-blend-mode: multiply;
            padding: 24px;
            position: relative;
        }
        .map-layout-wrapper::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0; bottom: 0;
            background: rgba(248, 249, 250, 0.94);
            z-index: 1;
        }

        .map-card-container {
            border-radius: 28px;
            overflow: hidden;
            position: relative;
            border: 6px solid #ffffff;
            height: calc(100vh - 160px);
            min-height: 600px;
            box-shadow: 0 15px 50px rgba(0,0,0,0.08);
            z-index: 2;
        }

        /* Map Layer */
        #map {
            height: 100%;
            width: 100%;
            z-index: 1;
        }

        /* Float Sidebar for Info */
        .info-panel {
            position: absolute;
            top: 24px;
            left: 24px;
            width: 360px;
            max-height: calc(100% - 48px);
            background: var(--glass-bg);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border-radius: 24px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.08);
            border: 1px solid rgba(255, 255, 255, 0.6);
            z-index: 1000;
            overflow-y: auto;
            padding: 24px;
            transition: transform 0.3s ease;
        }

        @media (max-width: 991px) {
            .map-layout-wrapper {
                padding: 12px 10px;
            }
            .map-card-container {
                border-radius: 20px;
                border-width: 4px;
                height: calc(100vh - 120px);
                min-height: 500px;
            }
            .info-panel {
                width: 100%;
                left: 0;
                top: auto;
                bottom: 0px;
                max-height: 45%;
                padding: 30px 20px 20px 20px;
                border-radius: 25px 25px 0 0;
                box-shadow: 0 -10px 40px rgba(0,0,0,0.15) !important;
                border: none;
                margin: 0;
            }
            .info-panel::before {
                content: '';
                position: absolute;
                top: 10px;
                left: 50%;
                transform: translateX(-50%);
                width: 40px;
                height: 5px;
                background: rgba(0,0,0,0.15);
                border-radius: 10px;
            }
            .navbar-brand .latin-text {
                font-size: 1.15rem !important;
            }
            .navbar-brand .aksara-text {
                font-size: 0.8rem !important;
            }
            .navbar-brand img {
                height: 42px !important;
                margin-right: 10px !important;
            }
        }

        .info-panel::-webkit-scrollbar { width: 4px; }
        .info-panel::-webkit-scrollbar-track { background: transparent; }
        .info-panel::-webkit-scrollbar-thumb { background-color: rgba(0,0,0,0.15); border-radius: 10px; }

        .card-wisata {
            border: none;
            border-radius: 16px;
            background: rgba(255, 255, 255, 0.95);
            box-shadow: 0 4px 15px rgba(0,0,0,0.03);
            transition: all 0.3s ease;
            cursor: pointer;
            margin-bottom: 16px;
            overflow: hidden;
        }

        .card-wisata:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 25px rgba(0,0,0,0.1);
        }

        .card-wisata img {
            height: 130px;
            object-fit: cover;
            width: 100%;
        }

        .btn-gradient {
            background: var(--gradient-1);
            color: white !important;
            border: none;
            border-radius: 50px;
            padding: 8px 24px;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(67, 97, 238, 0.3);
        }

        .btn-gradient:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(67, 97, 238, 0.4);
        }

        /* Popups */
        .leaflet-popup-content-wrapper {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(8px);
            border-radius: 12px;
            box-shadow: 0 6px 20px rgba(0,0,0,0.15);
            padding: 0;
            overflow: hidden;
        }
        .leaflet-popup-content {
            margin: 0;
            width: 140px !important;
        }
        .leaflet-popup-tip {
            background: rgba(255, 255, 255, 0.85);
        }
        
        .map-popup-inner {
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 8px;
            position: relative;
        }
        .map-card-img {
            width: 100%;
            height: 75px;
            border-radius: 8px;
            object-fit: cover;
            margin-bottom: 6px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        .map-card-content {
            width: 100%;
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        .map-card-content b {
            font-size: 11px;
            color: var(--primary-color);
            text-align: center;
            margin-bottom: 8px;
            line-height: 1.25;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;  
            overflow: hidden;
            width: 100%;
        }
        .map-card-actions {
            display: flex;
            justify-content: space-between;
            width: 100%;
            gap: 4px;
        }
        .map-btn {
            font-size: 10px;
            padding: 5px 0;
            border-radius: 6px;
            cursor: pointer;
            text-decoration: none;
            text-align: center;
            font-weight: 700;
            border: none;
            flex: 1;
            transition: all 0.2s;
        }
        .leaflet-popup-close-button {
            color: white !important;
            text-shadow: 0 1px 2px rgba(0,0,0,0.5);
            font-size: 14px !important;
            padding: 4px !important;
            top: 2px !important;
            right: 2px !important;
            z-index: 10;
        }
            cursor: pointer;
            text-decoration: none;
            text-align: center;
            font-weight: 700;
            border: none;
            flex: 1;
            transition: all 0.2s;
        }
        .map-btn-outline {
            background: rgba(67, 97, 238, 0.1);
            color: var(--primary-color);
        }
        .map-btn-outline:hover {
            background: var(--primary-color);
            color: white;
        }
        .map-btn-primary {
            background: var(--gradient-1);
            color: white;
        }
        .map-btn-primary:hover {
            opacity: 0.9;
            color: white;
        }

        .popup-img {
            width: 100%;
            height: 130px;
            object-fit: cover;
            border-radius: 10px;
            margin-bottom: 12px;
        }
        /* Safe Inner Animation for Leaflet Markers */
        .map-floating-card {
            animation: popInInner 0.6s cubic-bezier(0.175, 0.885, 0.32, 1.275) backwards;
            transition: all 0.3s ease;
        }
        .map-floating-card:hover {
            transform: translateY(-8px) scale(1.03);
            box-shadow: 0 15px 35px rgba(67, 97, 238, 0.4);
        }
        @keyframes popInInner {
            0% { transform: scale(0.5); opacity: 0; }
            100% { transform: scale(1); opacity: 1; }
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
                <ul class="navbar-nav align-items-center" style="font-size: 1.05rem;">
                    <li class="nav-item">
                        <a class="nav-link fw-bold active" href="index.php"><i class="fa-solid fa-map me-1"></i>Peta</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link fw-bold" href="wisata.php"><i class="fa-solid fa-list me-1"></i>Daftar Wisata</a>
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
    <!-- Layout Wrapper -->
    <div class="map-layout-wrapper">
        <div class="container-fluid px-xl-5">
            <div class="map-card-container">

                <!-- Floating Panel -->
                <div class="info-panel">
        <h5 class="fw-bold mb-4" style="color: var(--primary-color);"><i class="fa-solid fa-list me-2"></i>Lokasi Menarik</h5>
        
        <div class="mb-3">
            <input type="text" class="form-control rounded-pill px-4 py-2 border-0 bg-white" style="box-shadow: inset 0 2px 4px rgba(0,0,0,0.05);" id="searchInput" placeholder="Cari wisata...">
        </div>

        <div class="mb-4">
            <label class="form-label" style="font-size: 0.85rem; font-weight: 600; color: #555;"><i class="fa-solid fa-route me-1 text-primary"></i>Mode Transportasi (Arah)</label>
            <select class="form-select rounded-pill px-3 border-0 bg-white" style="box-shadow: inset 0 2px 4px rgba(0,0,0,0.05);" id="routingMode">
                <option value="car">🚗 Mobil</option>
                <option value="motorcycle">🏍️ Sepeda Motor</option>
                <option value="bicycle">🚲 Sepeda</option>
                <option value="walking">🚶 Jalan Kaki</option>
            </select>
        </div>
        <section class="haversine-focus-panel mb-4" aria-label="Pencarian destinasi terdekat dengan Haversine">
            <div class="method-kicker">Fitur Utama Skripsi</div>
            <h6 class="fw-bold mt-1 mb-2"><i class="fa-solid fa-location-crosshairs me-2"></i>Rekomendasi Wisata Terdekat</h6>
            <p class="small mb-3">Tentukan titik keberangkatan. Sistem menghitung jarak geografis Haversine ke seluruh destinasi yang memiliki koordinat valid, lalu mengurutkan dari jarak terkecil.</p>
            <label class="small fw-bold mb-1" for="prefOrigin">Titik keberangkatan</label>
            <select id="prefOrigin" class="form-select form-select-sm mb-2">
                <option value="">Pilih cara menentukan titik asal</option>
                <option value="gps">Lokasi perangkat saya</option>
                <option value="manual">Masukkan koordinat manual</option>
                <option value="map">Pilih titik pada peta</option>
            </select>
            <div class="origin-actions">
                <button type="button" class="btn btn-light btn-sm fw-bold" data-origin-mode="gps"><i class="fa-solid fa-location-arrow me-1"></i> Lokasi Saya</button>
                <button type="button" class="btn btn-outline-light btn-sm fw-bold" data-origin-mode="manual"><i class="fa-solid fa-keyboard me-1"></i> Koordinat Manual</button>
                <button type="button" class="btn btn-outline-light btn-sm fw-bold" data-origin-mode="map"><i class="fa-solid fa-map-pin me-1"></i> Pilih di Peta</button>
            </div>
            <div class="origin-preview" id="originSummary"><strong>Status titik keberangkatan</strong>Belum dipilih. Silakan gunakan lokasi perangkat, input manual, atau klik peta.</div>
            <div class="quick-legend">
                <div class="legend-item"><i class="fa-solid fa-compass me-1"></i><strong>Haversine</strong><br>Untuk mengurutkan destinasi terdekat.</div>
                <div class="legend-item"><i class="fa-solid fa-route me-1"></i><strong>OSRM</strong><br>Untuk rute jalan dan estimasi perjalanan.</div>
            </div>
            <div class="method-note">Haversine = jarak geografis. Rute jalan dan estimasi durasi baru diminta melalui OSRM setelah tujuan dipilih.</div>
        </section>

        <div id="sig-route-status" class="route-status-box status-info d-none" role="status" aria-live="polite"></div>
        <div class="route-action-row mb-3">
            <button type="button" id="btn-clear-route" class="btn btn-sm btn-soft-light"><i class="fa-solid fa-ban me-1"></i> Batalkan rute</button>
            <button type="button" id="btn-reset-origin" class="btn btn-sm btn-soft-danger"><i class="fa-solid fa-rotate-left me-1"></i> Reset titik awal</button>
        </div>
        <div id="wisataList">
            <?php if(empty($wisata_data)): ?>
                <div class="alert alert-info text-center" role="alert">
                    Belum ada data wisata.
                </div>
            <?php else: ?>
                <?php foreach($wisata_data as $wisata): ?>
                    <div class="card card-wisata category-card <?= sig_h(sig_category_class($wisata['kategori'])) ?>" data-category-group="<?= sig_h(sig_category_group($wisata['kategori'])) ?>" data-wisata-id="<?= (int)$wisata['id_wisata'] ?>" onclick="focusMap(<?= sig_coordinate_json($wisata, 'lat') ?>, <?= sig_coordinate_json($wisata, 'lng') ?>, <?= (int)$wisata['id_wisata'] ?>)">
                        <?php 
                        $img_src = sig_image($wisata);
                        ?>
                        <img src="<?= $img_src ?>" alt="<?= htmlspecialchars($wisata['nama_wisata']) ?>">
                        <div class="card-body p-3">
                            <div class="d-flex align-items-start gap-2"><span class="category-mini"><?= sig_h(sig_category_group($wisata['kategori'])) ?></span><span class="distance-rank" hidden></span></div>
                            <h6 class="card-title fw-bold mb-1 text-truncate"><?= htmlspecialchars($wisata['nama_wisata']) ?></h6>
                            <p class="card-text text-muted mb-2" style="font-size: 0.8rem;"><i class="fa-solid fa-tag me-1"></i><?= htmlspecialchars($wisata['kategori']) ?><span class="d-block small text-primary" data-distance></span></p>
                            <div class="d-flex gap-2 mt-2">
                                <a href="detail.php?id=<?= $wisata['id_wisata'] ?>" class="btn btn-sm btn-outline-primary rounded-pill w-100" onclick="event.stopPropagation();">Detail Info</a>
                                <button type="button" class="btn btn-sm btn-primary rounded-pill w-100 text-white" style="background: var(--gradient-1); border: none;" onclick="event.stopPropagation(); routeTo(<?= sig_coordinate_json($wisata, 'lat') ?>, <?= sig_coordinate_json($wisata, 'lng') ?>);"><i class="fa-solid fa-route"></i> Petunjuk Arah</button>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>

        <!-- Map Area -->
        <div id="map"></div>
        
            </div> <!-- End Card -->
        </div> <!-- End Container -->
    </div> <!-- End Layout Wrapper -->
<?php sig_public_footer(); ?>
    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <!-- Routing Machine JS -->
    <script src="https://unpkg.com/leaflet-routing-machine/dist/leaflet-routing-machine.js"></script>
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <script src="assets/js/revisi-data.js?v=1"></script>
    <script>
        // Beberapa peta dasar + overlay tematik untuk eksplorasi WebGIS.
        const baseLayers = {
            'OSM Standard': L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {maxZoom:19, attribution:'&copy; OpenStreetMap contributors'}),
            'OSM Humanitarian': L.tileLayer('https://{s}.tile.openstreetmap.fr/hot/{z}/{x}/{y}.png', {maxZoom:19, attribution:'&copy; OpenStreetMap contributors, Tiles style by HOT'}),
            'Topografi': L.tileLayer('https://{s}.tile.opentopomap.org/{z}/{x}/{y}.png', {maxZoom:17, attribution:'Map data &copy; OpenStreetMap contributors, SRTM | Map style &copy; OpenTopoMap'}),
            'Satelit': L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {maxZoom:19, attribution:'Tiles &copy; Esri'}),
            'Peta Ringan': L.tileLayer('https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png', {maxZoom:20, attribution:'&copy; OpenStreetMap contributors &copy; CARTO'})
        };
        var defaultLayer = baseLayers['OSM Standard'];
        var map = L.map('map', {zoomControl:false,layers:[defaultLayer]}).setView([-6.7320,108.5523], 13);
        const thematicLayer=L.layerGroup().addTo(map);
        window.addEventListener('resize', function() { map.invalidateSize(); });


        L.control.zoom({
            position: 'bottomright'
        }).addTo(map);

        // Custom Icon
        var customIcon = L.icon({
           iconUrl: 'https://cdn-icons-png.flaticon.com/512/684/684908.png', 
           iconSize: [38, 38], // size of the icon
           iconAnchor: [19, 38], // point of the icon which will correspond to marker's location
           popupAnchor: [0, -40] // point from which the popup should open relative to the iconAnchor
        });

        var markers = [];
        var routingControl = null;

        // Data from PHP
        const wisataData = <?= json_encode($wisata_data, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT | JSON_INVALID_UTF8_SUBSTITUTE) ?>;

        wisataData.forEach(function(item) {
            if (!SIGGeo.valid(item.lat, item.lng)) return;
            let lat = parseFloat(item.lat);
            let lng = parseFloat(item.lng);
            let img = item.gambar_utama ? 'assets/uploads/' + encodeURIComponent(item.gambar_utama) : 'assets/img/placeholder.jpg';
            const cat=(item.kategori||'').toLowerCase();
            const thematicColor=cat.includes('sejarah')||cat.includes('monumen')||cat.includes('arsitektur')?'#d43f3a':cat.includes('budaya')?'#1f9d55':cat.includes('religi')?'#7c4dff':cat.includes('bahari')?'#0284c7':cat.includes('rekreasi')?'#f59e0b':cat.includes('edukasi')||cat.includes('museum')?'#c2410c':'#0f8b8d';
            L.circleMarker([lat,lng],{radius:8,color:'#fff',weight:2,fillColor:thematicColor,fillOpacity:.9})
                .bindTooltip(SIGGeo.esc(item.nama_wisata)+'<br>'+SIGGeo.esc(item.kategori),{direction:'top'})
                .addTo(thematicLayer);
            
            let customCardHtml = `
                <div class="map-popup-inner">
                    <img src="${img}" class="map-card-img" alt="${SIGGeo.esc(item.nama_wisata)}">
                    <div class="map-card-content">
                        <b title="${SIGGeo.esc(item.nama_wisata)}">${SIGGeo.esc(item.nama_wisata)}</b>
                        <div class="map-card-actions">
                            <a href="detail.php?id=${item.id_wisata}" class="map-btn map-btn-outline" title="Info Detail"><i class="fa-solid fa-info"></i></a>
                            <button onclick="routeTo(${lat}, ${lng}, event)" class="map-btn map-btn-primary" style="background: rgba(67, 97, 238, 0.9);" title="Tampilkan rute OSRM"><i class="fa-solid fa-route"></i></button>
                        </div>
                    </div>
                </div>
            `;

            // Membuat Pin Default Leaflet yang sangat aman dan tidak menghalangi zoom layer map
            let marker = L.marker([lat, lng], {icon: customIcon}).addTo(map);
            
            // Menyematkan Pop-up Box transparan kecil yang muncul ketika Pin di-klik
            marker.bindPopup(customCardHtml, {
                closeButton: true,
                autoPanPadding: [50, 50]
            });
            
            // Auto Zoom (Fly) ketika Pin di Peta di Klik
            marker.on('click', function() {
                focusMap(lat, lng, item.id_wisata);
            });
            
            marker.id_wisata = item.id_wisata;
            markers.push(marker);
        });

        L.control.layers(baseLayers, {'Tematik Kategori Wisata': thematicLayer}, {position:'topright',collapsed:true}).addTo(map);
        const legend=L.control({position:'bottomleft'});
        legend.onAdd=function(){const d=L.DomUtil.create('div','map-legend');d.innerHTML='<strong>Legenda Tematik</strong><span><i style="background:#d43f3a"></i>Sejarah</span><span><i style="background:#1f9d55"></i>Budaya</span><span><i style="background:#7c4dff"></i>Religi</span><span><i style="background:#0284c7"></i>Bahari</span><span><i style="background:#f59e0b"></i>Rekreasi</span>';return d;};
        legend.addTo(map);

        function focusMap(lat, lng, id_wisata) {
            if (!SIGGeo.valid(lat,lng)) { alert("Koordinat destinasi ini belum ditetapkan. Informasi tetap dapat dibuka melalui Detail Info."); return; }
            map.flyTo([lat, lng], 17, {
                animate: true,
                duration: 1.5
            });
        }

        let userLat = null, userLng = null;
        let userMarker = null;
        const routeProfiles={
            car:{label:'Mobil',serviceUrl:'https://router.project-osrm.org/route/v1',profile:'driving'},
            motorcycle:{label:'Sepeda Motor',serviceUrl:'https://router.project-osrm.org/route/v1',profile:'driving'},
            bicycle:{label:'Sepeda',serviceUrl:'https://routing.openstreetmap.de/routed-bike/route/v1',profile:'driving'},
            walking:{label:'Jalan Kaki',serviceUrl:'https://routing.openstreetmap.de/routed-foot/route/v1',profile:'driving'}
        };
        let currentRoutingMode = 'car';
        let currentDestination=null;
        let pendingDestination = null;
        const routeStatus=document.getElementById('sig-route-status');
        const originSelect=document.getElementById('prefOrigin');
        const originSummary=document.getElementById('originSummary');
        const clearRouteBtn=document.getElementById('btn-clear-route');
        const resetOriginBtn=document.getElementById('btn-reset-origin');
        document.querySelectorAll('[data-origin-mode]').forEach(btn=>btn.addEventListener('click',()=>{originSelect.value=btn.dataset.originMode;originSelect.dispatchEvent(new Event('change'));}));
        function showRouteStatus(message,type='info') {
            routeStatus.textContent=message;
            routeStatus.classList.remove('d-none','status-info','status-success','status-warning','status-danger');
            routeStatus.classList.add('status-'+type);
        }
        function renderOriginSummary(origin=null){
            if(!origin){
                originSummary.innerHTML='<strong>Status titik keberangkatan</strong>Belum dipilih. Silakan gunakan lokasi perangkat, input manual, atau klik peta.';
                return;
            }
            originSummary.innerHTML='<strong>Titik keberangkatan aktif</strong>'+SIGGeo.esc(origin.label)+'<br><span class="small">Koordinat: '+Number(origin.lat).toFixed(6)+', '+Number(origin.lng).toFixed(6)+'</span>';
        }
        function clearRoute(message=true){
            if(routingControl){map.removeControl(routingControl);routingControl=null;}
            if(message) showRouteStatus('Rute OSRM dibatalkan. Anda dapat memilih destinasi lain kapan saja.','warning');
        }
        function sortFromOrigin(origin) {
            userLat=origin.lat; userLng=origin.lng;
            if(userMarker) map.removeLayer(userMarker);
            userMarker=L.marker([userLat,userLng]).addTo(map).bindPopup(SIGGeo.esc(origin.label));
            const list=document.getElementById('wisataList');
            const ranked=wisataData.map(w=>({...w,distance:SIGGeo.haversine(origin,w)}))
                .sort((a,b)=>(a.distance ?? Infinity)-(b.distance ?? Infinity) || Number(a.id_wisata)-Number(b.id_wisata));
            list.querySelectorAll('.card-wisata').forEach(card=>{card.classList.remove('nearest-highlight');const badge=card.querySelector('.distance-rank');if(badge){badge.hidden=true;badge.textContent='';}});
            ranked.forEach((w,index)=>{
                const card=list.querySelector('[data-wisata-id="'+Number(w.id_wisata)+'"]');
                if(!card)return;
                card.querySelector('[data-distance]').textContent=w.distance===null?'Koordinat belum tersedia':w.distance.toFixed(2)+' km (Haversine)';
                const rankBadge=card.querySelector('.distance-rank');if(index<3){card.classList.add('nearest-highlight');if(rankBadge){rankBadge.hidden=false;rankBadge.textContent=String(index+1);}}
                list.appendChild(card);
            });
            renderOriginSummary(origin);
            showRouteStatus('Urutan jarak geografis dari '+origin.label+'. Bukan jarak tempuh jalan.','success');
        }
        document.addEventListener('sig:origin',e=>sortFromOrigin(e.detail));
        originSelect.addEventListener('change',function(){
            if(this.value==='gps') {showRouteStatus('Mencari lokasi perangkat...','info'); map.locate({setView:false,maxZoom:16,timeout:12000});}
            else if(this.value==='manual') SIGGeo.askManual();
            else if(this.value==='map') showRouteStatus('Klik titik keberangkatan pada peta; titik tersebut menjadi acuan Haversine dan OSRM.','info');
        });
        map.on('click',function(e){ if(originSelect.value==='map') SIGGeo.setOrigin(e.latlng.lat,e.latlng.lng,'Titik pilihan pengguna pada peta'); });
        map.on('locationfound',function(e){
            if(!SIGGeo.valid(e.latlng.lat,e.latlng.lng))return;
            originSelect.value='gps';
            SIGGeo.setOrigin(e.latlng.lat,e.latlng.lng,'Lokasi perangkat pengguna');
            if(pendingDestination){const destination=pendingDestination; pendingDestination=null; routeTo(destination.lat,destination.lng);}
        });
        map.on('locationerror',function(){
            pendingDestination=null;
            showRouteStatus('Lokasi perangkat belum tersedia. Pilih Koordinat manual atau Pilih titik pada peta pada Titik berangkat.','warning');
        });
        const savedOrigin=SIGGeo.getOrigin();
        if(savedOrigin) {sortFromOrigin(savedOrigin); originSelect.value=savedOrigin.label.includes('perangkat')?'gps':'manual'; renderOriginSummary(savedOrigin);} else {renderOriginSummary();}
        document.addEventListener('sig:origin-clear',()=>{renderOriginSummary();clearRoute(false);userLat=null;userLng=null;routeStatus.classList.add('d-none');const list=document.getElementById('wisataList');list.querySelectorAll('.card-wisata [data-distance]').forEach(el=>el.textContent='');list.querySelectorAll('.card-wisata').forEach(card=>{card.classList.remove('nearest-highlight');const badge=card.querySelector('.distance-rank');if(badge){badge.hidden=true;badge.textContent='';}});if(userMarker){map.removeLayer(userMarker);userMarker=null;}});
        document.getElementById('routingMode').addEventListener('change',function(){
            currentRoutingMode=this.value;
            const cfg=routeProfiles[currentRoutingMode]||routeProfiles.car;
            showRouteStatus('Mode rute diubah ke '+cfg.label+'.'+(currentRoutingMode==='motorcycle'?' Mode motor menggunakan pendekatan jaringan kendaraan bermotor OSRM.':''),'info');
            if(currentDestination && SIGGeo.valid(userLat,userLng)) routeTo(currentDestination.lat,currentDestination.lng);
        });
        function routeTo(destLat,destLng,event=null) {
            if(event)event.stopPropagation();
            currentDestination={lat:Number(destLat),lng:Number(destLng)};
            if(!SIGGeo.valid(destLat,destLng)) {showRouteStatus('Koordinat tujuan belum tersedia. Rute tidak dibuat.','danger');return;}
            if(!SIGGeo.valid(userLat,userLng)) {
                pendingDestination={lat:Number(destLat),lng:Number(destLng)};
                showRouteStatus('Pilih titik berangkat atau izinkan lokasi perangkat. Mencari GPS...','warning');
                map.locate({setView:false,maxZoom:16,timeout:12000});return;
            }
            if(routingControl){map.removeControl(routingControl);routingControl=null;}
            const geographic=SIGGeo.haversine({lat:userLat,lng:userLng},{lat:destLat,lng:destLng});
            showRouteStatus('Jarak geografis Haversine '+geographic.toFixed(2)+' km. Meminta rute jalan OSRM...','info');
            const control=L.Routing.control({
                waypoints:[L.latLng(userLat,userLng),L.latLng(destLat,destLng)],
                router:L.Routing.osrmv1({serviceUrl:(routeProfiles[currentRoutingMode]||routeProfiles.car).serviceUrl,profile:(routeProfiles[currentRoutingMode]||routeProfiles.car).profile,timeout:15000}),
                routeWhileDragging:false,addWaypoints:false,draggableWaypoints:false,collapsible:true,show:true,
                lineOptions:{styles:[{color:'#4361ee',opacity:0.8,weight:6}]},createMarker:function(){return null;}
            });
            routingControl=control;
            control.on('routesfound',function(e){
                if(routingControl!==control)return;
                const route=e.routes && e.routes[0];
                if(!SIGGeo.routeValid(route)){showRouteStatus('Respons OSRM tidak valid; tidak ada hasil perjalanan yang dapat digunakan.','danger');map.removeControl(control);routingControl=null;return;}
                showRouteStatus('Haversine: '+geographic.toFixed(2)+' km | Jarak jalan OSRM: '+(route.summary.totalDistance/1000).toFixed(2)+' km | Estimasi: '+Math.round(route.summary.totalTime/60)+' menit ('+(routeProfiles[currentRoutingMode]||routeProfiles.car).label+'; bukan lalu lintas langsung).','success');
            });
            control.on('routingerror',function(){
                if(routingControl!==control)return;
                showRouteStatus('Rute OSRM tidak tersedia. Haversine '+geographic.toFixed(2)+' km bukan pengganti jarak jalan. Silakan coba lagi.','danger');
                map.removeControl(control);routingControl=null;
            });
            control.addTo(map);
        }

        clearRouteBtn.addEventListener('click',function(){ clearRoute(true); });
        resetOriginBtn.addEventListener('click',function(){
            if(SIGGeo.clearOrigin) SIGGeo.clearOrigin();
            originSelect.value='';
            showRouteStatus('Titik keberangkatan direset. Silakan pilih kembali asal perjalanan.','warning');
        });

        // Search Function
        document.getElementById('searchInput').addEventListener('keyup', function() {
            let filter = this.value.toLowerCase();
            let cards = document.querySelectorAll('.card-wisata');
            
            cards.forEach(function(card) {
                let text = card.querySelector('.card-title').innerText.toLowerCase();
                if(text.indexOf(filter) > -1) {
                    card.style.display = "";
                } else {
                    card.style.display = "none";
                }
            });
        });
    </script>
</body>
</html>
