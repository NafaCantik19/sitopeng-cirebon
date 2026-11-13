<?php
require_once 'koneksi.php';
require_once 'includes/public_ui.php';
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if($id === 0){
    header("Location: index.php");
    exit();
}

// Fetch Wisata
$query = "SELECT * FROM wisata WHERE id_wisata = $id";
$result = mysqli_query($conn, $query);
$wisata = mysqli_fetch_assoc($result);

if(!$wisata){
    die("Wisata tidak ditemukan.");
}

// Fallback values for incomplete or newly added wisata
$deskripsi = !empty(trim($wisata['deskripsi'])) ? $wisata['deskripsi'] : "Destinasi tempat wisata di Kota Cirebon ini menawarkan pengalaman berkunjung yang berkesan. Pengunjung dapat mengunjungi lokasi secara langsung untuk mendapatkan informasi lebih detail dari pengelola setempat.";
$alamat = !empty(trim($wisata['alamat'])) ? $wisata['alamat'] : "Kawasan Wisata Kota Cirebon, Jawa Barat";
$jam_buka = !empty(trim($wisata['jam_buka'])) ? $wisata['jam_buka'] : "Belum terverifikasi";
$harga_tiket = !empty(trim($wisata['harga_tiket'])) ? $wisata['harga_tiket'] : "Belum terverifikasi";
$kategori = !empty(trim($wisata['kategori'])) ? $wisata['kategori'] : "Wisata Umum";

// Gambar menggunakan aset asli, tanpa mengganti tema.
$hero_bg = sig_image($wisata);
$source = sig_source($wisata);
$has_coords = sig_coords($wisata['lat'], $wisata['lng']);

// Fetch Galeri
$query_galeri = "SELECT * FROM galeri WHERE id_wisata = $id";
$result_galeri = mysqli_query($conn, $query_galeri);
$galeri_data = [];
while($row = mysqli_fetch_assoc($result_galeri)){
    $galeri_data[] = $row;
}

// Kandidat Haversine diperingkat di browser dari titik keberangkatan pengguna.
$res_all = mysqli_query($conn, "SELECT * FROM wisata WHERE id_wisata != $id ORDER BY id_wisata ASC");
$nearest_wisata = [];
while ($row = mysqli_fetch_assoc($res_all)) {
    if (!sig_coords($row['lat'], $row['lng'])) continue;
    $row['distance_km'] = null;
    $row['image_url'] = sig_image($row);
    $nearest_wisata[] = $row;
}
$top_nearest = array_slice($nearest_wisata, 0, 3);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($wisata['nama_wisata']) ?> - SITOPENG</title>
    
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css"/>
    <!-- Leaflet -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>
    <link rel="stylesheet" href="https://unpkg.com/leaflet-routing-machine/dist/leaflet-routing-machine.css"/>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@700;800;900&display=swap" rel="stylesheet">
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

        .hero {
            background: linear-gradient(rgba(43, 45, 66, 0.65), rgba(43, 45, 66, 0.9)), url('<?= $hero_bg ?>') center/cover no-repeat;
            height: 480px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            text-align: center;
            perspective: 1000px;
            position: relative;
        }

        @keyframes float3D {
            0% { 
                transform: translateY(0px) rotateX(0deg) rotateY(0deg); 
                text-shadow: 
                    0 1px 0 #4cc9f0, 0 2px 0 #4895ef, 0 3px 0 #4361ee, 0 4px 0 #3f37c9, 0 5px 0 #3a0ca3, 
                    0 6px 1px rgba(0,0,0,.1), 0 0 10px rgba(67,97,238,.4), 0 1px 3px rgba(0,0,0,.3); 
            }
            50% { 
                transform: translateY(-15px) rotateX(15deg) rotateY(-8deg) scale(1.05); 
                text-shadow: 
                    0 1px 0 #4cc9f0, 0 2px 0 #4895ef, 0 3px 0 #4361ee, 0 4px 0 #3f37c9, 0 5px 0 #3a0ca3, 
                    0 6px 1px rgba(0,0,0,.1), 0 0 15px rgba(76,201,240,.6), 0 15px 15px rgba(67,97,238,.5); 
            }
            100% { 
                transform: translateY(0px) rotateX(0deg) rotateY(0deg); 
                text-shadow: 
                    0 1px 0 #4cc9f0, 0 2px 0 #4895ef, 0 3px 0 #4361ee, 0 4px 0 #3f37c9, 0 5px 0 #3a0ca3, 
                    0 6px 1px rgba(0,0,0,.1), 0 0 10px rgba(67,97,238,.4), 0 1px 3px rgba(0,0,0,.3); 
            }
        }

        .animated-3d-text {
            display: inline-block;
            animation: float3D 4s cubic-bezier(0.4, 0, 0.2, 1) infinite;
            font-family: 'Poppins', sans-serif;
            font-weight: 900;
            color: #ffffff;
            letter-spacing: 2px;
            transform-style: preserve-3d;
            margin-bottom: 20px;
        }

        .detail-card {
            border: none;
            border-radius: 24px;
            box-shadow: 0 15px 40px rgba(0,0,0,0.08);
            margin-top: -80px;
            background: white;
            padding: 48px;
            position: relative;
            z-index: 10;
        }

        #map {
            height: 380px;
            border-radius: 16px;
            width: 100%;
            border: 1px solid rgba(0,0,0,0.05);
            box-shadow: inset 0 2px 10px rgba(0,0,0,0.03);
        }

        .info-pill {
            background: #f8f9fa;
            border-radius: 16px;
            padding: 16px 20px;
            border-left: 6px solid var(--primary-color);
            margin-bottom: 20px;
            font-weight: 500;
            display: flex;
            align-items: center;
            box-shadow: 0 2px 10px rgba(0,0,0,0.02);
            transition: transform 0.2s ease;
        }
        .info-pill:hover {
            transform: translateX(5px);
        }
        .info-pill i { font-size: 24px; color: var(--primary-color); width: 44px; }

        .galeri-img {
            width: 100%;
            height: 250px;
            object-fit: cover;
            border-radius: 20px;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 10px 20px rgba(0,0,0,0.05);
        }
        .galeri-img:hover { 
            transform: scale(1.03) translateY(-5px); 
            box-shadow: 0 15px 30px rgba(0,0,0,0.15);
        }

        .btn-route {
            background: var(--gradient-1);
            color: white !important;
            border: none;
            border-radius: 50px;
            padding: 14px 30px;
            font-weight: 600;
            display: inline-block;
            text-decoration: none;
            box-shadow: 0 4px 15px rgba(67, 97, 238, 0.3);
            transition: all 0.3s ease;
        }
        .btn-route:hover { 
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(67, 97, 238, 0.4);
        }

        /* Pulsing Voice Badge */
        @keyframes pulseVoice {
            0% { transform: scale(1); opacity: 1; }
            50% { transform: scale(1.08); opacity: 0.8; }
            100% { transform: scale(1); opacity: 1; }
        }
        .speaking-active {
            animation: pulseVoice 1.2s infinite ease-in-out;
            background-color: #dc3545 !important;
            border-color: #dc3545 !important;
            color: white !important;
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
                <ul class="navbar-nav align-items-center" style="font-size: 1.1rem;">
                    <li class="nav-item"><a class="nav-link fw-bold" href="index.php"><i class="fa-solid fa-map me-1"></i>Peta</a></li>
                    <li class="nav-item">
                        <a class="nav-link fw-bold" href="wisata.php"><i class="fa-solid fa-list me-1"></i>Daftar Wisata</a>
                    </li>
                    <li class="nav-item"><a class="nav-link fw-bold" href="about.php"><i class="fa-solid fa-circle-info me-1"></i>Tentang Cirebon</a></li>
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
            <div class="ticker-text">
                <i class="fa-solid fa-cloud" style="color:var(--accent-color);"></i> Mampir Sedela, Kenang Salawase. &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
                <i class="fa-solid fa-cloud" style="color:var(--accent-color);"></i> Mampir Sedela, Kenang Salawase. &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
                <i class="fa-solid fa-cloud" style="color:var(--accent-color);"></i> Mampir Sedela, Kenang Salawase. &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
                <i class="fa-solid fa-cloud" style="color:var(--accent-color);"></i> Mampir Sedela, Kenang Salawase. &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
                <i class="fa-solid fa-cloud" style="color:var(--accent-color);"></i> Mampir Sedela, Kenang Salawase. &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
                <i class="fa-solid fa-cloud" style="color:var(--accent-color);"></i> Mampir Sedela, Kenang Salawase. &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
                <i class="fa-solid fa-cloud" style="color:var(--accent-color);"></i> Mampir Sedela, Kenang Salawase. &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
                <i class="fa-solid fa-cloud" style="color:var(--accent-color);"></i> Mampir Sedela, Kenang Salawase. &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
            </div>
        </div>
    </nav>

    <!-- Hero Header -->
    <div class="hero">
        <div class="container pb-4">
            <h1 class="display-4 animated-3d-text"><?= htmlspecialchars($wisata['nama_wisata']) ?></h1><br>
            <span class="badge bg-primary fs-6 me-2 shadow-sm rounded-pill px-4 py-2 mt-2" style="background: var(--gradient-1) !important;"><i class="fa-solid fa-tag me-2"></i><?= htmlspecialchars($kategori) ?></span>
        </div>
    </div>

    <!-- Main Detail Card -->
    <div class="container mb-5">
        <div class="detail-card">
            <div class="d-flex justify-content-between align-items-center mb-4 pb-2 border-bottom flex-wrap gap-3">
                <a href="wisata.php" class="btn btn-outline-secondary rounded-pill px-4" style="font-weight: 600; border-width: 2px;"><i class="fa-solid fa-arrow-left me-2"></i>Kembali ke Daftar Wisata</a>
                <div class="d-flex gap-2">
                    <button class="btn btn-outline-primary rounded-pill px-3 fw-bold" onclick="copyGPSCoordinates()"><i class="fa-solid fa-copy me-1"></i> Salin GPS</button>
                    <a href="<?= $has_coords ? 'https://www.google.com/maps/dir/?api=1&amp;destination='.sig_h($wisata['lat'].','.$wisata['lng']) : '#' ?>" onclick="if(!<?= $has_coords ? 'true':'false' ?>){alert('Koordinat destinasi belum ditetapkan.');return false;}" target="_blank" rel="noopener noreferrer" class="btn text-white rounded-pill px-4 shadow-sm" style="background: #34a853; border: none; font-weight: 600;">
                        <i class="fa-brands fa-google me-1"></i> Buka Maps Asli
                    </a>
                </div>
            </div>
            
            <div class="row mt-4">
                <div class="col-lg-7 pe-lg-5">
                    <div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-2">
                        <h3 class="fw-bold mb-0 text-primary"><i class="fa-solid fa-circle-info me-2"></i>Informasi Destinasi</h3>
                    </div>

                    <p class="text-secondary" style="line-height: 1.85; font-size: 1.08rem; text-align: justify;">
                        <?= nl2br(htmlspecialchars($deskripsi)) ?>
                    </p>

                    <div class="mt-5">
                        <div class="info-pill"><i class="fa-solid fa-location-dot"></i> <div><strong>Alamat Lengkap:</strong><br><?= htmlspecialchars($alamat) ?><?php if (!empty($source['kelurahan']) || !empty($source['kecamatan'])): ?><br><small><?= sig_h(implode(', ', array_filter([$source['kelurahan'] ?? '', $source['kecamatan'] ?? '']))) ?></small><?php endif; ?></div></div>
                        <div class="info-pill"><i class="fa-solid fa-clock"></i> <div><strong>Jam Operasional:</strong><br><?= htmlspecialchars($jam_buka) ?></div></div>
                        <div class="info-pill"><i class="fa-solid fa-ticket"></i> <div><strong>Harga Tiket Rujukan:</strong><br><?= htmlspecialchars($harga_tiket) ?></div></div>
                        <details class="small text-secondary mt-3">
                            <summary>Sumber, status data, dan catatan kunjungan</summary>
                            <?php foreach (['keterangan_harga'=>'Rincian harga', 'status_harga'=>'Status harga', 'tanggal_sumber_harga'=>'Tanggal sumber harga', 'status_koordinat'=>'Status koordinat sumber', 'jenis_titik'=>'Jenis titik', 'catatan'=>'Catatan', 'sumber_identitas'=>'Sumber identitas', 'sumber_koordinat'=>'Sumber koordinat', 'sumber_harga'=>'Sumber harga', 'tanggal_penelusuran'=>'Tanggal penelusuran', 'kelompok_data'=>'Kelompok data'] as $field=>$label): ?>
                                <?php if (!empty($source[$field])): ?><p class="mb-2"><strong><?= sig_h($label) ?>:</strong> <?php if (preg_match('~^https?://~i', $source[$field])): ?><a href="<?= sig_h($source[$field]) ?>" target="_blank" rel="noopener noreferrer">Buka sumber</a><?php else: ?><?= sig_h($source[$field]) ?><?php endif; ?></p><?php endif; ?>
                            <?php endforeach; ?>
                            <p>Status koordinat saat ini: <?= sig_h($wisata['status_koordinat']) ?>.</p>
                            <p><?= empty($wisata['gambar_utama']) ? 'Foto yang ditampilkan adalah ilustrasi bawaan proyek, bukan dokumentasi destinasi ini.' : 'Foto memakai aset proyek awal. Kesesuaian dan hak penggunaan tetap perlu diperiksa pengelola.' ?></p>
                        </details>
                    </div>
                </div>

                <div class="col-lg-5 mt-5 mt-lg-0">
                    <div class="osrm-panel">
                        <div class="panel-kicker">Navigasi Jalan</div>
                        <h3 class="fw-bold mb-3"><i class="fa-solid fa-map-location-dot me-2"></i>Lokasi Peta & Rute (OSRM)</h3>
                        <p class="subtle mb-3">Panel ini menampilkan rute jalan terhitung, jarak aktual OSRM, durasi estimasi, dan tetap membedakannya dari jarak Haversine.</p>
                        <div id="map" class="shadow-sm mb-4" title="Klik titik pada peta untuk memilih titik keberangkatan"></div>
                        <div id="osrm-route-info" class="d-none mb-3">
                            <div class="route-metric-grid">
                                <div class="route-metric"><div class="label">Jarak Haversine</div><div class="value" id="haversine-distance">...</div></div>
                                <div class="route-metric"><div class="label">Jarak jalan OSRM</div><div class="value" id="osrm-distance">...</div></div>
                                <div class="route-metric"><div class="label">Estimasi waktu</div><div class="value" id="osrm-time">...</div></div>
                            </div>
                            <div id="route-instructions-card" class="nav-instructions-card d-none">
                                <h6><i class="fa-solid fa-diamond-turn-right me-2"></i>Petunjuk Arah</h6>
                                <ol id="route-instructions-list"></ol>
                            </div>
                        </div>
                        <div class="mb-3 d-flex align-items-center" id="routingModeWrapper" style="display: none !important;">
                            <label class="form-label mb-0 me-3 fw-bold" style="white-space: nowrap;"><i class="fa-solid fa-route text-white me-2"></i>Mode Transportasi</label>
                            <select id="routingModeDetail" class="form-select rounded-pill">
                                <option value="car">🚗 Mobil</option>
                                <option value="motorcycle">🏍️ Sepeda Motor</option>
                                <option value="bicycle">🚲 Sepeda</option>
                                <option value="walking">🚶 Jalan Kaki</option>
                            </select>
                        </div>
                        <div class="osrm-button-group">
                            <button id="btn-arah" class="btn-route w-100 mb-0"><i class="fa-solid fa-location-arrow me-2"></i>Cari Rute & Navigasi Live</button>
                            <button id="btn-batal-arah" class="btn-route-outline" type="button"><i class="fa-solid fa-ban me-2"></i>Batalkan Rute</button>
                        </div>
                        <div id="routing-inst" class="small subtle mt-3" role="status" aria-live="polite"><?= $has_coords ? 'Klik peta untuk memilih titik awal, atau gunakan tombol rute untuk lokasi perangkat.' : 'Koordinat destinasi ini belum ditetapkan. Tidak ada marker tujuan atau rute buatan.' ?></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Galeri Foto -->
        <?php if(!empty($galeri_data)): ?>
        <div class="mt-5">
            <h3 class="fw-bold mb-4"><i class="fa-solid fa-images text-primary me-2"></i>Galeri Foto Pilihan</h3>
            <div class="row g-4">
                <?php foreach($galeri_data as $g): ?>
                    <div class="col-md-4 col-sm-6">
                        <img src="assets/uploads/<?= htmlspecialchars($g['foto']) ?>" class="galeri-img shadow-sm" alt="Galeri Foto" onclick="openLightbox('assets/uploads/<?= htmlspecialchars($g['foto']) ?>')">
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>

        <!-- Algoritma Haversine (Wisata Terdekat) -->
        <?php if(!empty($top_nearest)): ?>
        <section class="haversine-feature-panel mt-5">
            <div class="small fw-bold text-uppercase mb-2" style="letter-spacing:.12em;color:#b56b2b;">Fitur Utama · Haversine</div>
            <h3 class="fw-bold mb-2"><i class="fa-solid fa-location-crosshairs text-primary me-2"></i>Rekomendasi Wisata Terdekat (Algoritma Haversine)</h3>
            <p id="nearestOrigin" class="text-secondary mb-3">Pilih titik keberangkatan melalui tombol rute atau klik peta untuk menentukan wisata terdekat dari posisi pengguna. Daftar awal belum diperingkat.</p>
            <div class="haversine-detail-grid">
                <div class="haversine-detail-card"><div class="label">Metode</div><div class="value">Haversine Formula</div></div>
                <div class="haversine-detail-card"><div class="label">Titik Tujuan</div><div class="value"><?= sig_h($wisata['nama_wisata']) ?></div></div>
                <div class="haversine-detail-card"><div class="label">Kandidat Dibandingkan</div><div class="value"><?= count($nearest_wisata) ?> destinasi</div></div>
                <div class="haversine-detail-card"><div class="label">Status Koordinat</div><div class="value"><?= sig_h($wisata['status_koordinat']) ?></div></div>
            </div>
            <div class="haversine-explainer"><strong>Penjelasan:</strong> sistem menghitung jarak garis lurus dari titik keberangkatan ke seluruh kandidat destinasi yang memiliki koordinat valid, lalu mengurutkannya dari jarak terkecil agar rekomendasi terdekat lebih mudah dipahami.</div>
            <div class="alert alert-info border-0 shadow-sm rounded-4 mb-4" style="background-color: #e0f2fe; color: #0369a1;">
                <h5 class="fw-bold"><i class="fa-solid fa-calculator me-2"></i>Hasil Kalkulasi Jarak Haversine Formula</h5>
                <div class="row g-2 mt-2 mb-3 small">
                    <div class="col-md-6"><div class="p-2 rounded-3" style="background:rgba(255,255,255,.12)"><strong>Titik asal:</strong> <span id="calc-origin">Belum dipilih</span></div></div>
                    <div class="col-md-6"><div class="p-2 rounded-3" style="background:rgba(255,255,255,.12)"><strong>Tujuan halaman:</strong> <?= sig_h($wisata['nama_wisata']) ?> (<?= sig_h($wisata['lat']) ?>, <?= sig_h($wisata['lng']) ?>)</div></div>
                    <div class="col-md-3"><div class="p-2 rounded-3" style="background:rgba(255,255,255,.12)"><strong>Δφ:</strong> <span id="calc-dlat">-</span></div></div>
                    <div class="col-md-3"><div class="p-2 rounded-3" style="background:rgba(255,255,255,.12)"><strong>Δλ:</strong> <span id="calc-dlon">-</span></div></div>
                    <div class="col-md-3"><div class="p-2 rounded-3" style="background:rgba(255,255,255,.12)"><strong>a:</strong> <span id="calc-a">-</span></div></div>
                    <div class="col-md-3"><div class="p-2 rounded-3" style="background:rgba(255,255,255,.12)"><strong>c:</strong> <span id="calc-c">-</span></div></div>
                    <div class="col-12"><div class="p-2 rounded-3" style="background:rgba(255,255,255,.18)"><strong>d = R × c, R = 6.371 km:</strong> <span id="calc-distance">Belum dihitung</span></div></div>
                </div>
                <div class="small mb-2 opacity-75">Tiga rekomendasi terdekat setelah seluruh kandidat berkoordinat valid diurutkan:</div>
                <ol id="nearestList" class="mb-0 mt-2 fs-6" style="font-weight: 500;">
                    <?php foreach($top_nearest as $n): ?>
                        <li><?= htmlspecialchars($n['nama_wisata']) ?> – <strong><?= $n['distance_km'] === null ? 'Belum dihitung' : sig_h($n['distance_km']).' km' ?></strong> dari titik keberangkatan.</li>
                    <?php endforeach; ?>
                </ol>
            </div>

            <div id="nearestCards" class="row g-4">
                <?php foreach($top_nearest as $n): ?>
                    <div class="col-md-4">
                        <div class="card nearest-result-card shadow-sm border-0 h-100 rounded-4 overflow-hidden" style="transition: transform 0.3s ease; background: #fff;" onmouseover="this.style.transform='translateY(-8px)'; this.style.boxShadow='0 15px 30px rgba(0,0,0,0.1)';" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='';">
                            <?php 
                            $img_n = (!empty($n['gambar_utama']) && file_exists('assets/uploads/'.$n['gambar_utama'])) ? 'assets/uploads/'.$n['gambar_utama'] : 'assets/img/placeholder.jpg'; 
                            ?>
                            <img src="<?= $img_n ?>" alt="<?= htmlspecialchars($n['nama_wisata']) ?>" style="height: 200px; object-fit: cover; width: 100%;">
                            <div class="card-body p-4 d-flex flex-column">
                                <h5 class="fw-bold text-dark text-truncate" title="<?= htmlspecialchars($n['nama_wisata']) ?>"><?= htmlspecialchars($n['nama_wisata']) ?></h5>
                                <div class="mb-3">
                                    <span class="badge bg-light text-primary border border-primary"><i class="fa-solid fa-tag me-1"></i><?= htmlspecialchars($n['kategori']) ?></span>
                                </div>
                                <p class="text-muted mb-4 small flex-grow-1"><i class="fa-solid fa-route me-1 text-danger"></i> Jarak Haversine: <strong><?= $n['distance_km'] === null ? 'Belum dihitung' : sig_h($n['distance_km']).' km' ?></strong></p>
                                <a href="detail.php?id=<?= $n['id_wisata'] ?>" class="btn btn-outline-primary rounded-pill w-100 mt-auto" style="border-width: 2px; font-weight: 600;">Lihat Detail Lokasi</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>
        <?php endif; ?>

    </div>

    <!-- Lightbox Modal untuk Galeri Foto -->
    <div class="modal fade" id="lightboxModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content bg-transparent border-0">
                <div class="modal-body p-0 text-center position-relative">
                    <button type="button" class="btn-close btn-close-white position-absolute top-0 end-0 m-3" data-bs-dismiss="modal" aria-label="Close" style="z-index: 1055; filter: drop-shadow(0 2px 4px rgba(0,0,0,0.8));"></button>
                    <img id="lightboxImg" src="" class="img-fluid rounded-4 shadow-lg" style="max-height: 80vh; object-fit: contain;">
                </div>
            </div>
        </div>
    </div>
<?php sig_public_footer(); ?>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="https://unpkg.com/leaflet-routing-machine/dist/leaflet-routing-machine.js"></script>
    
    <script src="assets/js/revisi-data.js?v=1"></script>
    <script>
        const destLat = <?= sig_coordinate_json($wisata, 'lat') ?>;
        const destLng = <?= sig_coordinate_json($wisata, 'lng') ?>;
        const wisataName = <?= sig_json($wisata['nama_wisata']) ?>;
        const nearestCandidates = <?= sig_json($nearest_wisata) ?>;
        const baseLayers = {
            'OSM Standard': L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {maxZoom:19,attribution:'&copy; OpenStreetMap contributors'}),
            'OSM Humanitarian': L.tileLayer('https://{s}.tile.openstreetmap.fr/hot/{z}/{x}/{y}.png', {maxZoom:19,attribution:'&copy; OpenStreetMap contributors, HOT'}),
            'Topografi': L.tileLayer('https://{s}.tile.opentopomap.org/{z}/{x}/{y}.png', {maxZoom:17,attribution:'&copy; OpenStreetMap contributors | OpenTopoMap'}),
            'Satelit': L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {maxZoom:19,attribution:'Tiles &copy; Esri'}),
            'Peta Ringan': L.tileLayer('https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png', {maxZoom:20,attribution:'&copy; OpenStreetMap contributors &copy; CARTO'})
        };
        var defaultLayer = baseLayers['OSM Standard'];
        var map = L.map('map', {layers:[defaultLayer]}).setView([destLat || -6.7320, destLng || 108.5523], 15);
        L.control.layers(baseLayers,null,{position:'topright',collapsed:true}).addTo(map);
        window.addEventListener('resize', function() { map.invalidateSize(); });


        var customIcon = L.icon({
           iconUrl: 'https://cdn-icons-png.flaticon.com/512/684/684908.png', 
           iconSize: [46, 46],
           iconAnchor: [23, 46],
           popupAnchor: [0, -46]
        });

        if(SIGGeo.valid(destLat,destLng)) L.marker([destLat, destLng], {icon: customIcon}).addTo(map)
            .bindPopup(`<b>${SIGGeo.esc(wisataName)}</b>`).openPopup();

        let routingControl=null, userMarker=null;
        let userLat=null,userLng=null;
        const routeProfiles={
            car:{label:'Mobil',serviceUrl:'https://router.project-osrm.org/route/v1',profile:'driving'},
            motorcycle:{label:'Sepeda Motor',serviceUrl:'https://router.project-osrm.org/route/v1',profile:'driving'},
            bicycle:{label:'Sepeda',serviceUrl:'https://routing.openstreetmap.de/routed-bike/route/v1',profile:'driving'},
            walking:{label:'Jalan Kaki',serviceUrl:'https://routing.openstreetmap.de/routed-foot/route/v1',profile:'driving'}
        };
        let currentRouteMode='car';
        const routeButton=document.getElementById('btn-arah');
        const cancelRouteButton=document.getElementById('btn-batal-arah');
        const status=document.getElementById('routing-inst');
        const nearestCards=document.getElementById('nearestCards');
        const nearestTemplate=nearestCards && nearestCards.firstElementChild ? nearestCards.firstElementChild.cloneNode(true) : null;
        function updateHaversineFormula(origin){
            const originEl=document.getElementById('calc-origin');
            if(!originEl || !SIGGeo.valid(destLat,destLng)) return;
            originEl.textContent=origin.label+' ('+Number(origin.lat).toFixed(6)+', '+Number(origin.lng).toFixed(6)+')';
            const rad=Math.PI/180;
            const phi1=Number(origin.lat)*rad, phi2=Number(destLat)*rad;
            const dphi=(Number(destLat)-Number(origin.lat))*rad;
            const dlambda=(Number(destLng)-Number(origin.lng))*rad;
            const a=Math.sin(dphi/2)**2+Math.cos(phi1)*Math.cos(phi2)*Math.sin(dlambda/2)**2;
            const c=2*Math.atan2(Math.sqrt(a),Math.sqrt(1-a));
            const d=6371*c;
            document.getElementById('calc-dlat').textContent=dphi.toFixed(8)+' rad';
            document.getElementById('calc-dlon').textContent=dlambda.toFixed(8)+' rad';
            document.getElementById('calc-a').textContent=a.toFixed(10);
            document.getElementById('calc-c').textContent=c.toFixed(10);
            document.getElementById('calc-distance').textContent=d.toFixed(3)+' km';
        }
        function updateNearest(origin) {
            userLat=origin.lat;userLng=origin.lng;
            updateHaversineFormula(origin);
            if(userMarker)map.removeLayer(userMarker);
            userMarker=L.marker([userLat,userLng]).addTo(map).bindPopup(SIGGeo.esc(origin.label));
            const ranked=nearestCandidates.map(w=>({...w,distance:SIGGeo.haversine(origin,w)}))
                .filter(w=>w.distance!==null).sort((a,b)=>a.distance-b.distance || Number(a.id_wisata)-Number(b.id_wisata)).slice(0,3);
            if(!nearestTemplate)return;
            document.getElementById('nearestOrigin').textContent='Jarak geografis Haversine dari '+origin.label+' ('+userLat.toFixed(6)+', '+userLng.toFixed(6)+'). Bukan jarak jalan OSRM.';
            document.getElementById('nearestList').innerHTML=ranked.map(w=>'<li>'+SIGGeo.esc(w.nama_wisata)+' - <strong>'+w.distance.toFixed(2)+' km</strong> dari titik keberangkatan.</li>').join('');
            nearestCards.replaceChildren();
            ranked.forEach(w=>{
                const card=nearestTemplate.cloneNode(true);
                const img=card.querySelector('img');img.src=w.image_url;img.alt=w.nama_wisata;
                card.querySelector('h5').textContent=w.nama_wisata;card.querySelector('h5').title=w.nama_wisata;
                card.querySelector('.badge').innerHTML='<i class="fa-solid fa-tag me-1"></i>'+SIGGeo.esc(w.kategori);
                card.querySelector('p strong').textContent=w.distance.toFixed(2)+' km';
                card.querySelector('a').href='detail.php?id='+Number(w.id_wisata);
                nearestCards.appendChild(card);
            });
        }
        document.addEventListener('sig:origin',e=>updateNearest(e.detail));
        const savedOrigin=SIGGeo.getOrigin();if(savedOrigin)updateNearest(savedOrigin);
        if(!SIGGeo.valid(destLat,destLng)){routeButton.disabled=true;routeButton.textContent='Koordinat tujuan perlu verifikasi';cancelRouteButton.disabled=true;}
        function drawRoute() {
            if(!SIGGeo.valid(destLat,destLng)||!SIGGeo.valid(userLat,userLng))return;
            if(routingControl){map.removeControl(routingControl);routingControl=null;}
            document.getElementById('osrm-route-info').classList.add('d-none');
            routeButton.disabled=true;status.textContent='Meminta rute jalan OSRM (driving)...';
            const control=L.Routing.control({
                waypoints:[L.latLng(userLat,userLng),L.latLng(destLat,destLng)],
                router:L.Routing.osrmv1({serviceUrl:(routeProfiles[currentRouteMode]||routeProfiles.car).serviceUrl,profile:(routeProfiles[currentRouteMode]||routeProfiles.car).profile,timeout:15000}),
                routeWhileDragging:false,addWaypoints:false,draggableWaypoints:false,fitSelectedRoutes:true,
                lineOptions:{styles:[{color:'#4361ee',opacity:0.9,weight:6}]},createMarker:function(){return null;}
            });
            routingControl=control;
            control.on('routesfound',function(e){
                if(routingControl!==control)return;
                routeButton.disabled=false;
                const route=e.routes && e.routes[0];
                if(!SIGGeo.routeValid(route)){status.textContent='Respons OSRM tidak lengkap; hasil perjalanan tidak digunakan.';map.removeControl(control);routingControl=null;return;}
                document.getElementById('osrm-distance').textContent=(route.summary.totalDistance/1000).toFixed(2)+' km';
                document.getElementById('osrm-time').textContent=Math.round(route.summary.totalTime/60)+' menit · '+(routeProfiles[currentRouteMode]||routeProfiles.car).label;
                document.getElementById('haversine-distance').textContent=SIGGeo.haversine({lat:userLat,lng:userLng},{lat:destLat,lng:destLng}).toFixed(2)+' km';
                document.getElementById('osrm-route-info').classList.remove('d-none');
                const instructionCard=document.getElementById('route-instructions-card');
                const instructionList=document.getElementById('route-instructions-list');
                const steps=Array.isArray(route.instructions)?route.instructions:[];
                instructionList.innerHTML=steps.length?steps.slice(0,18).map(step=>'<li>'+SIGGeo.esc(step.text||step.type||'Lanjutkan perjalanan')+'<span class="step-distance">'+(Number.isFinite(Number(step.distance))?Math.round(Number(step.distance))+' m':'')+'</span></li>').join(''):'<li>Ikuti garis rute pada peta menuju destinasi.</li>';
                instructionCard.classList.remove('d-none');
                document.getElementById('routingModeWrapper').style.setProperty('display','flex','important');
                cancelRouteButton.disabled=false;
                status.textContent='Titik awal: '+SIGGeo.getOrigin().label+'. Klik peta untuk mengganti titik awal. Koordinat objek belum tentu gerbang masuk; periksa akses setempat.';
            });
            control.on('routingerror',function(){
                if(routingControl!==control)return;
                routeButton.disabled=false;
                document.getElementById('osrm-route-info').classList.add('d-none');
                status.textContent='OSRM tidak memberikan rute. Coba lagi; tidak ada jarak jalan atau waktu tempuh buatan.';
                map.removeControl(control);routingControl=null;
            });
            control.addTo(map);
        }
        function clearRouteDetail(){
            if(routingControl){map.removeControl(routingControl);routingControl=null;}
            document.getElementById('osrm-route-info').classList.add('d-none');
            document.getElementById('route-instructions-card').classList.add('d-none');
            document.getElementById('route-instructions-list').innerHTML='';
            document.getElementById('routingModeWrapper').style.setProperty('display','none','important');
            cancelRouteButton.disabled=true;
            routeButton.disabled=!SIGGeo.valid(destLat,destLng);
            status.textContent='Rute dibatalkan. Anda dapat memilih titik awal lain atau menjalankan navigasi lagi.';
        }
        cancelRouteButton.addEventListener('click',clearRouteDetail);
        cancelRouteButton.disabled=true;
        routeButton.addEventListener('click',function(){
            if(SIGGeo.getOrigin()){drawRoute();return;}
            routeButton.disabled=true;status.textContent='Mencari lokasi perangkat...';
            map.locate({setView:false,maxZoom:16,timeout:12000});
        });
        map.on('click',function(e){SIGGeo.setOrigin(e.latlng.lat,e.latlng.lng,'Titik pilihan pengguna pada peta');if(SIGGeo.valid(destLat,destLng))drawRoute();});
        map.on('locationfound',function(e){
            SIGGeo.setOrigin(e.latlng.lat,e.latlng.lng,'Lokasi perangkat pengguna');
            routeButton.disabled=!SIGGeo.valid(destLat,destLng);drawRoute();
        });
        map.on('locationerror',function(){
            routeButton.disabled=!SIGGeo.valid(destLat,destLng);
            status.textContent='GPS belum tersedia. Klik titik awal pada peta atau masukkan koordinat manual.';
            if(confirm('GPS belum tersedia. Masukkan koordinat keberangkatan secara manual?')){if(SIGGeo.askManual())drawRoute();}
        });
        document.getElementById('routingModeDetail').addEventListener('change',function(){
            currentRouteMode=this.value;
            status.textContent='Mode transportasi: '+(routeProfiles[currentRouteMode]||routeProfiles.car).label+(currentRouteMode==='motorcycle'?' (pendekatan jaringan kendaraan bermotor OSRM).':'.');
            if(SIGGeo.getOrigin() && SIGGeo.valid(destLat,destLng)) drawRoute();
        });

        // Lightbox function for gallery images
        function openLightbox(imgSrc) {
            document.getElementById('lightboxImg').src = imgSrc;
            var myModal = new bootstrap.Modal(document.getElementById('lightboxModal'));
            myModal.show();
        }

        // Copy GPS function
        function copyGPSCoordinates() {
            if(!SIGGeo.valid(destLat,destLng)){alert("Koordinat destinasi belum tersedia.");return;}
            const coordStr = `${destLat}, ${destLng}`;
            if(!navigator.clipboard){alert("Koordinat GPS: "+coordStr);return;}
            navigator.clipboard.writeText(coordStr).then(() => {
                alert(`Koordinat GPS (${coordStr}) berhasil disalin!`);
            }).catch(err => {
                alert(`Koordinat GPS: ${coordStr}`);
            });
        }
    </script>
</body>
</html>
