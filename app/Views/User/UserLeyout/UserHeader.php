<!DOCTYPE html>
<html lang="th" class="light-style layout-menu-fixed" dir="ltr" data-theme="theme-default"
    data-assets-path="<?=base_url()?>/assets/" data-template="vertical-menu-template-free">

<head>
    <meta charset="utf-8" />
    <meta name="viewport"
        content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

    <title><?=$title;?> | SportBase 2026</title>

    <meta name="description" content="<?= $description ?>" />
    <meta
        content="ระบบงาน,E-Office,โรงเรียนสวนกุหลาบวิทยาลัย,โรงเรียน,สวนกุหลาบ,จิรประวัติ,นครสวรรค์,สวนกุหลาบจิรประวัติ,โรงเรียนสวนกุหลาบ"
        name="keywords">
    <meta http-equiv="content-language" content="th" />
    <meta name="robots" content="index, follow" />
    <meta name="revisit-after" content="1 day" />
    <meta name="author" content="Dekpiano" />
    <meta property="og:url" content="<?= $full_url ?>" />
    <meta property="og:title" content="<?=$title;?>" />
    <meta property="og:description" content="<?= $description ?>" />
    <meta property="og:type" content="website" />
    <?php if($uri->getSegment(1) == 'Booking') : ?>
    <meta property="og:image" content="<?=base_url();?>uploads/banner/booking/bannerBooking.png" />
    <?php elseif($uri->getSegment(1) == 'Repair'): ?>
    <meta property="og:image" content="<?=base_url();?>uploads/banner/repair/bannerRepair.jpg" />
    <?php else: ?>
    <meta property="og:image" content="<?=base_url();?>uploads/banner/home/bannerHome.png" />
    <?php endif?>

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="<?=base_url()?>/assets/img/favicon/favicon.ico" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Sarabun:wght@300;400;500;600;700&family=Outfit:wght@300;400;500;600;700&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Icons -->
    <link rel="stylesheet" href="<?=base_url()?>/assets/vendor/fonts/boxicons.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <!-- Core CSS -->
    <link rel="stylesheet" href="<?=base_url()?>/assets/vendor/css/core.css" class="template-customizer-core-css" />
    <link rel="stylesheet" href="<?=base_url()?>/assets/vendor/css/theme-blue.css?v=2" class="template-customizer-theme-css" />
    <link rel="stylesheet" href="<?=base_url()?>/assets/css/demo.css?v=1" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    
    <style>
        :root {
            --sb-orange: #fd7e14;
            --sb-orange-warm: #ff9e43;
            --sb-orange-dark: #e86c00;
            --sb-orange-grad: linear-gradient(135deg, #fd7e14 0%, #ff9e43 100%);
            --sb-glass: rgba(255, 255, 255, 0.85);
            --sb-glass-dark: rgba(25, 25, 25, 0.8);
        }

        body {
            font-family: 'Sarabun', 'Inter', sans-serif;
            background-color: #f8f9fa;
            background-image: 
                radial-gradient(at 0% 0%, rgba(253, 126, 20, 0.05) 0px, transparent 50%),
                radial-gradient(at 100% 0%, rgba(255, 158, 67, 0.05) 0px, transparent 50%);
            background-attachment: fixed;
        }

        /* --- Premium Sidebar --- */
        #layout-menu {
            background: var(--sb-glass) !important;
            backdrop-filter: blur(15px);
            -webkit-backdrop-filter: blur(15px);
            border-right: 1px solid rgba(253, 126, 20, 0.1);
            box-shadow: 10px 0 30px rgba(0, 0, 0, 0.02) !important;
        }
        
        .menu-inner .menu-item.active > .menu-link {
            background: var(--sb-orange-grad) !important;
            box-shadow: 0 4px 15px rgba(253, 126, 20, 0.3) !important;
            color: #fff !important;
        }

        .menu-link {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1) !important;
            border-radius: 12px !important;
            margin: 4px 15px !important;
            width: auto !important;
        }

        .menu-link:hover {
            background: rgba(253, 126, 20, 0.08) !important;
            transform: translateX(5px);
        }

        .app-brand {
            margin-bottom: 1.5rem;
            padding: 1.5rem 1.5rem !important;
        }

        /* --- Premium Navbar --- */
        #layout-navbar {
            background: var(--sb-glass) !important;
            backdrop-filter: blur(15px);
            -webkit-backdrop-filter: blur(15px);
            border: 1px solid rgba(255, 255, 255, 0.5);
            border-radius: 16px !important;
            margin-top: 15px !important;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.04) !important;
            transition: all 0.3s ease;
        }

        .navbar-detached.fixed-top {
            top: 10px !important;
        }

        /* --- Global Elements --- */
        h1, h2, h3, h4, h5, h6, .fw-bold {
            font-family: 'Outfit', 'Sarabun', sans-serif;
            letter-spacing: -0.01em;
        }

        .card {
            border: none !important;
            border-radius: 20px !important;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.03) !important;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.06) !important;
        }

        .btn-primary {
            background: var(--sb-orange-grad) !important;
            border: none !important;
            box-shadow: 0 4px 15px rgba(253, 126, 20, 0.3) !important;
            border-radius: 12px !important;
            padding: 0.6rem 1.5rem !important;
            transition: all 0.3s ease !important;
        }

        .btn-primary:hover {
            transform: scale(1.02);
            box-shadow: 0 8px 25px rgba(253, 126, 20, 0.4) !important;
        }

        .badge {
            font-weight: 600 !important;
            letter-spacing: 0.02em;
        }

        /* Scrollbar Optimization */
        ::-webkit-scrollbar {
            width: 8px;
        }
        ::-webkit-scrollbar-track {
            background: #f1f1f1;
        }
        ::-webkit-scrollbar-thumb {
            background: #e0e0e0;
            border-radius: 10px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: var(--sb-orange-warm);
        }
    </style>

    <!-- Helpers -->
    <script src="<?=base_url()?>/assets/vendor/js/helpers.js"></script>
    <script src="<?=base_url()?>/assets/js/config.js"></script>
</head>

<body style="font-family:'Sarabun', sans-serif;">