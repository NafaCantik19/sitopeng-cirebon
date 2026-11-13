<?php
require_once 'koneksi.php';
require_once 'includes/public_ui.php';

$query = "SELECT * FROM wisata WHERE gambar_utama != '' AND gambar_utama IS NOT NULL ORDER BY id_wisata ASC LIMIT 7";
$result = mysqli_query($conn, $query);
$wisata_list = [];
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $wisata_list[] = $row;
    }
}
$explore_gallery = array_slice($wisata_list, 0, 4);
$popular_tours = array_slice($wisata_list, 4, 3);

function safeImagePath($filename) {
    if (!empty($filename)) {
        return 'assets/uploads/' . $filename;
    }
    return 'assets/img/placeholder.jpg';
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tentang Cirebon - SITOPENG</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"/>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&family=Cormorant+Garamond:wght@500;600;700&family=Noto+Sans+Javanese&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #284f9f;
            --primary-dark: #1f3770;
            --secondary: #d4af37;
            --accent: #8fd3ff;
            --text: #24324a;
            --muted: #627085;
            --soft: #f6f8fc;
            --line: rgba(40, 79, 159, 0.12);
            --card: rgba(255,255,255,0.9);
            --shadow: 0 18px 45px rgba(28, 46, 82, 0.10);
            --radius-xl: 28px;
            --radius-lg: 22px;
            --radius-md: 18px;
        }

        * { box-sizing: border-box; }

        html { scroll-behavior: smooth; }

        body {
            margin: 0;
            font-family: 'Manrope', sans-serif;
            color: var(--text);
            background:
                radial-gradient(circle at top right, rgba(212, 175, 55, 0.10), transparent 30%),
                radial-gradient(circle at top left, rgba(40, 79, 159, 0.10), transparent 28%),
                #fcfdff;
            overflow-x: hidden;
        }

        h1, h2, h3, h4, h5, .section-kicker, .hero-badge, .stat-number {
            font-family: 'Cormorant Garamond', serif;
        }

        .page-pattern::before,
        .page-pattern::after {
            content: "";
            position: fixed;
            width: 280px;
            height: 280px;
            z-index: -1;
            opacity: 0.10;
            pointer-events: none;
            background:
                radial-gradient(circle at 50% 50%, rgba(40,79,159,0.9) 0 8%, transparent 8% 100%),
                radial-gradient(circle at 15% 50%, rgba(40,79,159,0.55) 0 7%, transparent 7% 100%),
                radial-gradient(circle at 85% 50%, rgba(40,79,159,0.55) 0 7%, transparent 7% 100%),
                radial-gradient(circle at 50% 15%, rgba(212,175,55,0.75) 0 7%, transparent 7% 100%),
                radial-gradient(circle at 50% 85%, rgba(212,175,55,0.75) 0 7%, transparent 7% 100%);
            filter: blur(0.2px);
            transform: rotate(18deg);
        }

        .page-pattern::before {
            top: 110px;
            left: -70px;
        }

        .page-pattern::after {
            bottom: 80px;
            right: -90px;
            transform: rotate(-12deg);
        }

        .navbar-custom {
            position: sticky;
            top: 0;
            z-index: 1050;
            background: rgba(255, 255, 255, 0.92);
            backdrop-filter: blur(12px);
            border-bottom: 1px solid rgba(40, 79, 159, 0.08);
            box-shadow: 0 10px 30px rgba(24, 34, 56, 0.06);
            overflow: hidden;
        }

        .navbar-custom::before {
            content: "";
            position: absolute;
            inset: 0;
            background-image: linear-gradient(rgba(255,255,255,0.82), rgba(255,255,255,0.82)), url('assets/img/nav-bg.jpg?v=3');
            background-size: cover;
            background-position: center;
            opacity: 0.96;
            z-index: 0;
        }

        .navbar-custom .container {
            position: relative;
            z-index: 1;
        }

        .brand-wrap {
            gap: 14px;
        }

        .brand-logo {
            height: 84px;
            width: 84px;
            object-fit: contain;
            border-radius: 22px;
            background: rgba(255,255,255,0.9);
            padding: 6px;
            box-shadow: 0 12px 26px rgba(31, 55, 112, 0.16);
        }

        .latin-text {
            display: block;
            color: var(--primary-dark);
            font-size: 1.55rem;
            font-weight: 700;
            line-height: 1;
            letter-spacing: 0.3px;
        }

        .brand-sub {
            display: block;
            font-size: 0.82rem;
            color: var(--muted);
            margin-top: 3px;
            letter-spacing: 0.3px;
        }

        .aksara-text {
            display: block;
            color: #6a7bb5;
            font-family: 'Noto Sans Javanese', sans-serif;
            font-size: 0.88rem;
            line-height: 1.1;
            opacity: 0.9;
            margin-top: 5px;
        }

        .nav-link {
            color: #37455f !important;
            font-weight: 700;
            border-radius: 14px;
            padding: 10px 16px !important;
            margin: 0 4px;
            transition: all 0.25s ease;
        }

        .nav-link:hover,
        .nav-link.active {
            color: var(--primary) !important;
            background: rgba(40, 79, 159, 0.09);
            transform: translateY(-1px);
        }

        .btn-admin {
            border: none;
            background: linear-gradient(135deg, var(--primary), #4a79da);
            color: #fff;
            font-weight: 800;
            border-radius: 999px;
            padding: 12px 22px;
            box-shadow: 0 14px 26px rgba(40, 79, 159, 0.18);
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .ticker-wrapper {
            width: 100%;
            background: linear-gradient(90deg, #15336c, #1f4b9e 55%, #194080);
            color: #fff;
            padding: 8px 0;
            border-top: 3px solid #7fd5f6;
            overflow: hidden;
            display: flex;
            white-space: nowrap;
            position: relative;
            z-index: 1;
        }

        .ticker-text {
            display: inline-block;
            white-space: nowrap;
            padding-right: 60px;
            animation: marquee 25s linear infinite;
            font-size: 0.88rem;
            font-weight: 700;
            letter-spacing: 0.6px;
        }

        @keyframes marquee {
            from { transform: translateX(0); }
            to { transform: translateX(-50%); }
        }

        .hero-section {
            position: relative;
            overflow: hidden;
            padding: 88px 0 64px;
        }

        .hero-card {
            position: relative;
            border-radius: 36px;
            overflow: hidden;
            min-height: 520px;
            background:
                linear-gradient(100deg, rgba(18,35,78,0.88) 0%, rgba(18,35,78,0.74) 42%, rgba(18,35,78,0.28) 100%),
                url('assets/img/bg-heading.jpg?v=2') center/cover no-repeat;
            box-shadow: 0 24px 60px rgba(27, 42, 71, 0.16);
        }

        .hero-card::before {
            content: "";
            position: absolute;
            inset: 0;
            background:
                radial-gradient(circle at 10% 18%, rgba(255,255,255,0.18), transparent 18%),
                radial-gradient(circle at 85% 20%, rgba(212,175,55,0.16), transparent 16%),
                linear-gradient(180deg, transparent 55%, rgba(255,255,255,0.04) 100%);
        }

        .hero-content {
            position: relative;
            z-index: 1;
            padding: 76px 60px;
            color: #fff;
            max-width: 720px;
        }

        .hero-badge {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 20px;
            padding: 10px 18px;
            border-radius: 999px;
            background: rgba(255,255,255,0.12);
            border: 1px solid rgba(255,255,255,0.18);
            backdrop-filter: blur(10px);
            font-size: 1.08rem;
            letter-spacing: 0.6px;
        }

        .hero-title {
            font-size: clamp(3rem, 6vw, 5.3rem);
            line-height: 0.98;
            font-weight: 700;
            margin-bottom: 20px;
            text-shadow: 0 8px 22px rgba(0,0,0,0.20);
        }

        .hero-title span {
            color: #f4d98d;
        }

        .hero-desc {
            color: rgba(255,255,255,0.92);
            font-size: 1.06rem;
            line-height: 1.9;
            max-width: 620px;
        }

        .hero-meta {
            display: flex;
            flex-wrap: wrap;
            gap: 14px;
            margin-top: 28px;
        }

        .hero-meta-item {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            color: #fff;
            background: rgba(255,255,255,0.11);
            border: 1px solid rgba(255,255,255,0.16);
            padding: 12px 16px;
            border-radius: 18px;
            backdrop-filter: blur(8px);
            font-size: 0.95rem;
        }

        .section-shell {
            padding: 0 0 74px;
        }

        .section-kicker {
            color: var(--secondary);
            font-size: 1.1rem;
            font-weight: 700;
            letter-spacing: 1.1px;
            text-transform: uppercase;
            margin-bottom: 8px;
        }

        .section-title {
            font-size: clamp(2.2rem, 4vw, 3.35rem);
            font-weight: 700;
            color: #13284d;
            margin-bottom: 16px;
        }

        .section-subtitle {
            max-width: 760px;
            font-size: 1.02rem;
            line-height: 1.9;
            color: var(--muted);
            margin: 0 auto;
        }

        .intro-box {
            margin-top: -36px;
            position: relative;
            z-index: 3;
        }

        .intro-panel {
            background: rgba(255,255,255,0.82);
            border: 1px solid rgba(255,255,255,0.72);
            backdrop-filter: blur(16px);
            box-shadow: var(--shadow);
            border-radius: 26px;
            padding: 28px;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 18px;
        }

        .stat-card {
            border-radius: 22px;
            padding: 24px 22px;
            background: linear-gradient(180deg, #ffffff, #f9fbff);
            border: 1px solid var(--line);
            box-shadow: 0 14px 26px rgba(37, 55, 97, 0.06);
            min-height: 100%;
        }

        .stat-icon {
            width: 52px;
            height: 52px;
            border-radius: 16px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, rgba(40,79,159,0.15), rgba(143,211,255,0.28));
            color: var(--primary);
            font-size: 1.2rem;
            margin-bottom: 14px;
        }

        .stat-number {
            font-size: 2.15rem;
            font-weight: 700;
            color: #18325f;
            margin-bottom: 6px;
            line-height: 1;
        }

        .stat-label {
            font-size: 0.96rem;
            color: var(--muted);
            line-height: 1.7;
            margin: 0;
        }

        .story-grid {
            display: grid;
            gap: 28px;
            margin-top: 54px;
        }

        .story-card {
            position: relative;
            border-radius: 28px;
            overflow: hidden;
            border: 1px solid rgba(40,79,159,0.10);
            background: linear-gradient(180deg, rgba(255,255,255,0.95), rgba(247,250,255,0.95));
            box-shadow: var(--shadow);
        }

        .story-card .row {
            min-height: 360px;
        }

        .story-copy {
            padding: 34px 34px 34px 38px;
        }

        .story-index {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 72px;
            height: 36px;
            border-radius: 999px;
            background: rgba(40,79,159,0.08);
            color: var(--primary);
            font-weight: 800;
            font-size: 0.86rem;
            letter-spacing: 1px;
            margin-bottom: 18px;
        }

        .story-title {
            font-size: clamp(2rem, 3vw, 2.75rem);
            line-height: 1.05;
            color: #162b4d;
            margin-bottom: 14px;
            font-weight: 700;
        }

        .story-text {
            color: #5b6880;
            font-size: 1rem;
            line-height: 1.95;
            text-align: justify;
            margin-bottom: 0;
        }

        .story-highlights {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-top: 22px;
        }

        .story-pill {
            padding: 10px 14px;
            border-radius: 999px;
            background: #f5f8ff;
            border: 1px solid rgba(40,79,159,0.10);
            color: #355081;
            font-size: 0.9rem;
            font-weight: 700;
        }

        .story-media {
            height: 100%;
            min-height: 320px;
            position: relative;
            padding: 26px;
            display: flex;
            align-items: center;
            justify-content: center;
            background:
                linear-gradient(135deg, rgba(40,79,159,0.08), rgba(212,175,55,0.08));
        }

        .story-media::before {
            content: "";
            position: absolute;
            width: 78%;
            height: 78%;
            border-radius: 28px;
            border: 1px dashed rgba(40,79,159,0.15);
        }

        .story-media img {
            position: relative;
            z-index: 1;
            width: 100%;
            max-width: 420px;
            height: 280px;
            object-fit: cover;
            border-radius: 24px;
            box-shadow: 0 18px 35px rgba(21, 40, 78, 0.16);
        }

        .motif-banner {
            margin: 68px 0 40px;
            padding: 28px 30px;
            border-radius: 28px;
            background:
                linear-gradient(135deg, rgba(19,40,77,0.96), rgba(34,72,138,0.92)),
                url('assets/img/nav-bg.jpg') center/cover no-repeat;
            color: #fff;
            position: relative;
            overflow: hidden;
            box-shadow: 0 18px 40px rgba(19, 40, 77, 0.18);
        }

        .motif-banner::before {
            content: "";
            position: absolute;
            inset: 0;
            background:
                radial-gradient(circle at 15% 30%, rgba(255,255,255,0.12) 0 7%, transparent 7% 100%),
                radial-gradient(circle at 30% 55%, rgba(255,255,255,0.10) 0 6%, transparent 6% 100%),
                radial-gradient(circle at 80% 35%, rgba(212,175,55,0.24) 0 8%, transparent 8% 100%),
                radial-gradient(circle at 68% 70%, rgba(255,255,255,0.09) 0 7%, transparent 7% 100%);
            opacity: 0.8;
        }

        .motif-banner .content {
            position: relative;
            z-index: 1;
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
        }

        .motif-banner h3 {
            margin: 0;
            font-size: clamp(1.9rem, 3vw, 2.6rem);
            font-weight: 700;
            color: #fff2c5;
        }

        .motif-banner p {
            margin: 8px 0 0;
            max-width: 760px;
            color: rgba(255,255,255,0.92);
            line-height: 1.8;
        }

        .gallery-section {
            padding-top: 8px;
        }

        .gallery-grid {
            display: grid;
            grid-template-columns: repeat(12, 1fr);
            gap: 18px;
            margin-top: 38px;
        }

        .gallery-item {
            position: relative;
            border-radius: 24px;
            overflow: hidden;
            min-height: 240px;
            box-shadow: 0 16px 34px rgba(21, 40, 78, 0.10);
            border: 1px solid rgba(255,255,255,0.6);
        }

        .gallery-item:nth-child(1) { grid-column: span 4; min-height: 340px; }
        .gallery-item:nth-child(2) { grid-column: span 3; min-height: 260px; }
        .gallery-item:nth-child(3) { grid-column: span 5; min-height: 340px; }
        .gallery-item:nth-child(4) { grid-column: span 12; min-height: 250px; }

        .gallery-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.5s ease;
        }

        .gallery-item:hover .gallery-img { transform: scale(1.05); }

        .gallery-overlay {
            position: absolute;
            inset: 0;
            background: linear-gradient(180deg, rgba(12,21,43,0.02), rgba(12,21,43,0.72));
            display: flex;
            align-items: end;
            justify-content: space-between;
            padding: 18px;
            color: #fff;
        }

        .gallery-count {
            width: 42px;
            height: 42px;
            border-radius: 14px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: rgba(255,255,255,0.16);
            backdrop-filter: blur(8px);
            border: 1px solid rgba(255,255,255,0.18);
            font-weight: 800;
        }

        .gallery-label {
            font-size: 0.95rem;
            font-weight: 700;
            color: rgba(255,255,255,0.92);
        }

        .popular-section {
            padding: 78px 0 96px;
            position: relative;
        }

        .popular-shell {
            background: linear-gradient(180deg, #fff, #f5f8ff);
            border-radius: 34px;
            padding: 42px 26px;
            box-shadow: var(--shadow);
            border: 1px solid rgba(40,79,159,0.08);
        }

        .tours-grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 20px;
            margin-top: 30px;
        }

        .tour-card {
            position: relative;
            display: block;
            min-height: 380px;
            border-radius: 26px;
            overflow: hidden;
            text-decoration: none;
            color: #fff;
            box-shadow: 0 18px 38px rgba(23, 41, 74, 0.13);
            transition: transform 0.35s ease, box-shadow 0.35s ease;
        }

        .tour-card:hover {
            transform: translateY(-6px);
            color: #fff;
            box-shadow: 0 24px 48px rgba(23, 41, 74, 0.18);
        }

        .tour-card-img {
            position: absolute;
            inset: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.7s ease;
        }

        .tour-card:hover .tour-card-img { transform: scale(1.08); }

        .tour-card::after {
            content: "";
            position: absolute;
            inset: 0;
            background: linear-gradient(180deg, rgba(9, 18, 41, 0.08), rgba(9, 18, 41, 0.85));
        }

        .tour-card-content {
            position: absolute;
            inset: auto 0 0 0;
            z-index: 1;
            padding: 24px;
        }

        .tour-num {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 10px;
            padding: 9px 14px;
            border-radius: 999px;
            background: rgba(255,255,255,0.14);
            border: 1px solid rgba(255,255,255,0.18);
            font-size: 0.86rem;
            font-weight: 800;
            letter-spacing: 1px;
        }

        .tour-name {
            font-size: 1.35rem;
            font-weight: 800;
            line-height: 1.45;
        }

        .tour-desc {
            font-size: 0.95rem;
            color: rgba(255,255,255,0.88);
            margin-top: 8px;
            line-height: 1.65;
        }

        .see-more-wrap {
            text-align: center;
            margin-top: 34px;
        }

        .see-more-btn {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 14px 24px;
            border-radius: 999px;
            background: linear-gradient(135deg, var(--primary), #4f7bdd);
            color: #fff;
            font-weight: 800;
            text-decoration: none;
            box-shadow: 0 14px 26px rgba(40,79,159,0.18);
        }

        .footer-note {
            text-align: center;
            padding: 28px 0 40px;
            color: var(--muted);
            font-size: 0.93rem;
        }

        .footer-note strong { color: var(--primary-dark); }

        .footer-note span { margin: 0 9px; opacity: 0.45; }

        @media (max-width: 1199px) {
            .stats-grid,
            .tours-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); }
            .gallery-item:nth-child(1),
            .gallery-item:nth-child(2),
            .gallery-item:nth-child(3),
            .gallery-item:nth-child(4) { grid-column: span 6; }
        }

        @media (max-width: 991px) {
            .hero-content { padding: 46px 32px; }
            .hero-card { min-height: 480px; }
            .stats-grid,
            .tours-grid,
            .gallery-grid { grid-template-columns: 1fr; }
            .gallery-item:nth-child(1),
            .gallery-item:nth-child(2),
            .gallery-item:nth-child(3),
            .gallery-item:nth-child(4) { grid-column: span 1; min-height: 240px; }
            .story-copy { padding: 30px 24px 24px; }
            .story-media { min-height: 260px; }
            .story-media img { height: 240px; }
            .intro-box { margin-top: -18px; }
        }

        @media (max-width: 767px) {
            .brand-logo { height: 66px; width: 66px; border-radius: 18px; }
            .latin-text { font-size: 1.2rem; }
            .brand-sub { font-size: 0.72rem; }
            .aksara-text { font-size: 0.72rem; }
            .hero-section { padding-top: 54px; }
            .hero-card { border-radius: 28px; min-height: auto; }
            .hero-content { padding: 36px 22px; }
            .hero-meta-item { width: 100%; }
            .section-shell { padding-bottom: 56px; }
            .intro-panel { padding: 22px; }
            .stats-grid { grid-template-columns: 1fr; }
            .story-card .row { min-height: auto; }
            .motif-banner { padding: 24px 22px; }
            .popular-shell { padding: 34px 18px; }
        }
    </style>
<link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&family=Cormorant+Garamond:wght@500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="assets/css/cirebon-theme.css?v=4">

<link rel="stylesheet" href="assets/css/public-enhancements.css?v=1">
</head>
<body class="page-pattern">
<?php sig_research_bar(); ?>
    <nav class="navbar navbar-expand-lg navbar-custom">
        <div class="container px-4 py-3">
            <a class="navbar-brand d-flex align-items-center text-decoration-none brand-wrap" href="index.php">
                <img src="assets/img/logo.png?v=<?= time() ?>" alt="Logo SITOPENG" class="brand-logo">
                <div>
                    <span class="latin-text">SITOPENG</span>
                    <span class="brand-sub">Sistem Informasi Terpadu Objek Pariwisata dan Eksplorasi Geografis</span>
                    <span class="aksara-text">Jelajahi Cirebon, Temukan Pesonanya.</span>
                </div>
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
                <ul class="navbar-nav align-items-lg-center mt-3 mt-lg-0">
                    <li class="nav-item"><a class="nav-link" href="index.php"><i class="fa-solid fa-map-location-dot me-1"></i>Peta</a></li>
                    <li class="nav-item"><a class="nav-link" href="wisata.php"><i class="fa-solid fa-list-ul me-1"></i>Daftar Wisata</a></li>
                    <li class="nav-item"><a class="nav-link active" href="about.php"><i class="fa-solid fa-landmark me-1"></i>Tentang Cirebon</a></li>
                    <li class="nav-item"><a class="nav-link" href="contact.php"><i class="fa-solid fa-address-card me-1"></i>Kontak</a></li>
                    <li class="nav-item"><a class="nav-link" href="masukan.php"><i class="fa-solid fa-message me-1"></i>Masukan</a></li>
                    <li class="nav-item ms-lg-3 mt-2 mt-lg-0"><a href="admin/login.php" class="btn-admin"><i class="fa-solid fa-right-to-bracket"></i>Admin Login</a></li>
                </ul>
            </div>
        </div>
        <div class="ticker-wrapper">
            <div class="ticker-text">
                <i class="fa-solid fa-cloud" style="color:#7fd5f6"></i> Mampir Sedela, Kenang Salawase. &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <i class="fa-solid fa-cloud" style="color:#7fd5f6"></i> Sentuhan budaya, rasa kuliner, dan jejak sejarah Cirebon. &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <i class="fa-solid fa-cloud" style="color:#7fd5f6"></i> Mampir Sedela, Kenang Salawase. &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <i class="fa-solid fa-cloud" style="color:#7fd5f6"></i> Sentuhan budaya, rasa kuliner, dan jejak sejarah Cirebon. &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <i class="fa-solid fa-cloud" style="color:#7fd5f6"></i> Mampir Sedela, Kenang Salawase. &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <i class="fa-solid fa-cloud" style="color:#7fd5f6"></i> Sentuhan budaya, rasa kuliner, dan jejak sejarah Cirebon. &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            </div>
        </div>
    </nav>

    <section class="hero-section">
        <div class="container">
            <div class="hero-card">
                <div class="hero-content">
                    <div class="hero-badge"><i class="fa-solid fa-crown"></i> Warisan Kota Cirebon</div>
                    <h1 class="hero-title">About <span>Cirebon</span></h1>
                    <p class="hero-desc">
                        Cirebon adalah ruang pertemuan antara sejarah, budaya, dan cita rasa. Kota ini tumbuh sebagai simpul penting di pesisir utara Jawa, menghadirkan kesultanan, batik Mega Mendung, tradisi topeng, serta kuliner legendaris yang masih hidup hingga sekarang.
                    </p>
                    <div class="hero-meta">
                        <div class="hero-meta-item"><i class="fa-solid fa-landmark"></i> Kota sejarah dan kesultanan</div>
                        <div class="hero-meta-item"><i class="fa-solid fa-palette"></i> Ikon batik Mega Mendung</div>
                        <div class="hero-meta-item"><i class="fa-solid fa-bowl-rice"></i> Destinasi kuliner khas Pantura</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="section-shell intro-box">
        <div class="container">
            <div class="intro-panel">
                <div class="text-center mb-4">
                    <div class="section-kicker">Pesona Utama</div>
                    <h2 class="section-title">Cirebon dalam Satu Tampilan yang Lebih Rapi</h2>
                    <p class="section-subtitle">
                        Halaman ini dirancang ulang agar lebih modern, lebih nyaman dibaca, dan tetap membawa identitas Cirebon melalui nuansa warna biru keemasan, tipografi modern-klasik, serta susunan konten yang lebih proporsional.
                    </p>
                </div>
                <div class="stats-grid">
                    <div class="stat-card">
                        <div class="stat-icon"><i class="fa-solid fa-landmark-flag"></i></div>
                        <div class="stat-number">Sejarah</div>
                        <p class="stat-label">Jejak kesultanan, pelabuhan niaga, dan peran dakwah Islam membentuk identitas kota.</p>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon"><i class="fa-solid fa-mask-face"></i></div>
                        <div class="stat-number">Budaya</div>
                        <p class="stat-label">Tari Topeng, batik Mega Mendung, dan akulturasi budaya menjadi daya tarik utama.</p>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon"><i class="fa-solid fa-utensils"></i></div>
                        <div class="stat-number">Kuliner</div>
                        <p class="stat-label">Nasi Jamblang, Empal Gentong, dan ragam hidangan khas memperkuat citra wisata.</p>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon"><i class="fa-solid fa-route"></i></div>
                        <div class="stat-number">Eksplorasi</div>
                        <p class="stat-label">Wisatawan dapat menikmati pengalaman sejarah, budaya, dan rasa dalam satu kota.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="section-shell">
        <div class="container">
            <div class="text-center mb-2">
                <div class="section-kicker">Cerita Kota</div>
                <h2 class="section-title">Tiga Wajah Penting Cirebon</h2>
                <p class="section-subtitle">
                    Setiap sudut Cirebon menyimpan karakter yang berbeda. Ada kota yang lahir dari sejarah besar, tumbuh dengan kebudayaan yang khas, lalu dikenang melalui kulinernya yang melekat di hati.
                </p>
            </div>

            <div class="story-grid">
                <article class="story-card">
                    <div class="row g-0 align-items-center">
                        <div class="col-lg-7">
                            <div class="story-copy">
                                <div class="story-index">01 · Sejarah</div>
                                <h3 class="story-title">Sejarah Singkat Cirebon</h3>
                                <p class="story-text">
                                    Cirebon merupakan salah satu kota tua di pesisir utara Pulau Jawa. Berawal dari pedukuhan kecil bernama Lemahwungkuk, Cirebon kemudian berkembang menjadi pelabuhan penting yang ramai oleh arus perdagangan. Letaknya yang strategis membuat kota ini menjadi titik temu budaya Sunda, Jawa, hingga pengaruh luar seperti Tionghoa, Arab, dan Eropa. Keberadaan Sunan Gunung Jati juga menjadikan Cirebon sebagai pusat penyebaran Islam yang sangat berpengaruh di Jawa Barat.
                                </p>
                                <div class="story-highlights">
                                    <span class="story-pill">Pelabuhan Niaga</span>
                                    <span class="story-pill">Kesultanan Islam</span>
                                    <span class="story-pill">Pantura Jawa</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-5">
                            <div class="story-media">
                                <img src="assets/img/keraton.jpg" alt="Keraton Cirebon">
                            </div>
                        </div>
                    </div>
                </article>

                <article class="story-card">
                    <div class="row g-0 align-items-center flex-lg-row-reverse">
                        <div class="col-lg-7">
                            <div class="story-copy">
                                <div class="story-index">02 · Budaya</div>
                                <h3 class="story-title">Kultur dan Kebudayaan yang Khas</h3>
                                <p class="story-text">
                                    Daya tarik Cirebon terletak pada akulturasi budayanya yang sangat kuat. Perpaduan antara unsur Sunda, Jawa, Tionghoa, Arab, dan Eropa tercermin pada arsitektur, kesenian, hingga kerajinan lokal. Batik Mega Mendung menjadi simbol visual paling ikonik dari Cirebon, sementara Tari Topeng menghadirkan lapisan makna filosofis yang kaya. Warna-warni budaya inilah yang membuat Cirebon terasa khas dan berkarakter, bukan sekadar kota transit di jalur Pantura.
                                </p>
                                <div class="story-highlights">
                                    <span class="story-pill">Mega Mendung</span>
                                    <span class="story-pill">Tari Topeng</span>
                                    <span class="story-pill">Akulturasi Budaya</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-5">
                            <div class="story-media">
                                <img src="assets/img/bg-about.png" alt="Budaya Cirebon">
                            </div>
                        </div>
                    </div>
                </article>

                <article class="story-card">
                    <div class="row g-0 align-items-center">
                        <div class="col-lg-7">
                            <div class="story-copy">
                                <div class="story-index">03 · Kuliner</div>
                                <h3 class="story-title">Surga Kuliner Tradisional</h3>
                                <p class="story-text">
                                    Cirebon juga dikenal sebagai kota yang kaya rasa. Nasi Jamblang, Empal Gentong, Tahu Gejrot, dan beragam sajian khas lain menjadi alasan mengapa wisata kuliner di kota ini selalu dirindukan. Hidangan-hidangan tersebut bukan hanya lezat, tetapi juga menyimpan cerita tradisi, teknik memasak, serta identitas lokal yang diwariskan dari generasi ke generasi. Karena itu, pengalaman menjelajahi Cirebon hampir selalu terasa belum lengkap tanpa singgah menikmati kulinernya.
                                </p>
                                <div class="story-highlights">
                                    <span class="story-pill">Nasi Jamblang</span>
                                    <span class="story-pill">Empal Gentong</span>
                                    <span class="story-pill">Wisata Rasa</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-5">
                            <div class="story-media">
                                <img src="assets/img/bg-wisata.jpg" alt="Kuliner Cirebon">
                            </div>
                        </div>
                    </div>
                </article>
            </div>

            <div class="motif-banner">
                <div class="content">
                    <div>
                        <h3>Cirebon dalam Perspektif WebGIS</h3>
                        <p>
                            Halaman ini memperkenalkan Cirebon melalui informasi sejarah, budaya, wisata, dan galeri visual yang terhubung dengan WebGIS sehingga pengguna dapat mengenali konteks kota sebelum memilih destinasi.
                        </p>
                    </div>
                </div>
            </div>

            <div class="gallery-section text-center">
                <div class="section-kicker">Galeri Wisata</div>
                <h2 class="section-title">Sekilas Sudut Menarik di Cirebon</h2>
                <p class="section-subtitle">
                    Tampilan galeri diperbarui menjadi satu foto unggulan di bagian atas lalu diikuti deretan foto berjalan agar halaman terasa lebih hidup dan modern.
                </p>

                <div class="gallery-feature-shell">
                    <?php $featured = $wisata_list[0] ?? null; $featuredImg = $featured ? safeImagePath($featured['gambar_utama']) : 'assets/img/placeholder.jpg'; ?>
                    <div class="gallery-feature-hero">
                        <img src="<?= htmlspecialchars($featuredImg) ?>" alt="<?= htmlspecialchars($featured['nama_wisata'] ?? 'SITOPENG') ?>">
                        <div class="gallery-feature-overlay">
                            <div class="gallery-feature-copy">
                                <div class="eyebrow">Foto Unggulan</div>
                                <h3><?= htmlspecialchars($featured['nama_wisata'] ?? 'SITOPENG') ?></h3>
                                <p><?= htmlspecialchars(mb_substr($featured['deskripsi'] ?? 'Jelajahi ragam destinasi unggulan Kota Cirebon melalui galeri visual yang lebih dinamis.', 0, 150)) ?>...</p>
                            </div>
                            <div class="gallery-feature-count">01</div>
                        </div>
                    </div>

                    <div class="gallery-marquee-shell">
                        <div class="gallery-marquee">
                            <div class="gallery-track">
                                <?php foreach (array_merge($wisata_list, $wisata_list) as $g): $imgSrc = safeImagePath($g['gambar_utama']); ?>
                                    <a class="gallery-chip" href="detail.php?id=<?= $g['id_wisata'] ?>">
                                        <img src="<?= htmlspecialchars($imgSrc) ?>" alt="<?= htmlspecialchars($g['nama_wisata']) ?>">
                                        <div class="caption"><?= htmlspecialchars($g['nama_wisata']) ?></div>
                                    </a>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        <div class="gallery-marquee">
                            <div class="gallery-track reverse">
                                <?php foreach (array_merge(array_reverse($wisata_list), array_reverse($wisata_list)) as $g): $imgSrc = safeImagePath($g['gambar_utama']); ?>
                                    <a class="gallery-chip" href="detail.php?id=<?= $g['id_wisata'] ?>">
                                        <img src="<?= htmlspecialchars($imgSrc) ?>" alt="<?= htmlspecialchars($g['nama_wisata']) ?>">
                                        <div class="caption"><?= htmlspecialchars($g['nama_wisata']) ?></div>
                                    </a>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="popular-section">
        <div class="container">
            <div class="popular-shell text-center">
                <div class="section-kicker">Rekomendasi</div>
                <h2 class="section-title">Destinasi Pilihan</h2>
                <p class="section-subtitle">
                    Jelajahi beberapa destinasi yang ditampilkan sebagai contoh pilihan untuk membantu pengguna mengenali ragam wisata di Kota Cirebon.
                </p>

                <div class="tours-grid">
                    <?php $tCount = 1; foreach ($popular_tours as $t): $imgSrc = safeImagePath($t['gambar_utama']); ?>
                        <a href="detail.php?id=<?= $t['id_wisata'] ?>" class="tour-card">
                            <img src="<?= htmlspecialchars($imgSrc) ?>" class="tour-card-img" alt="<?= htmlspecialchars($t['nama_wisata']) ?>">
                            <div class="tour-card-content">
                                <div class="tour-num"><i class="fa-solid fa-location-dot"></i> PILIHAN <?= $tCount ?></div>
                                <div class="tour-name"><?= htmlspecialchars($t['nama_wisata']) ?></div>
                                <div class="tour-desc">Temukan pengalaman wisata yang merepresentasikan pesona Cirebon dari sisi budaya, sejarah, dan suasana lokalnya.</div>
                            </div>
                        </a>
                    <?php $tCount++; endforeach; ?>

                    <?php while ($tCount <= 3): ?>
                        <a href="wisata.php" class="tour-card">
                            <img src="assets/img/placeholder.jpg" class="tour-card-img" alt="Destinasi wisata">
                            <div class="tour-card-content">
                                <div class="tour-num"><i class="fa-solid fa-location-dot"></i> PILIHAN <?= $tCount ?></div>
                                <div class="tour-name">Discover Destination</div>
                                <div class="tour-desc">Jelajahi lebih banyak destinasi menarik yang siap memperkaya pengalaman wisata Anda.</div>
                            </div>
                        </a>
                    <?php $tCount++; endwhile; ?>
                </div>

                <div class="see-more-wrap">
                    <a href="wisata.php" class="see-more-btn"><i class="fa-solid fa-compass"></i> Lihat Semua Wisata</a>
                </div>
            </div>
        </div>
    </section>

<?php sig_public_footer(); ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
