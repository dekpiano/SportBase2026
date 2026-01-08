<!DOCTYPE html>
<html lang="en" class="light-style layout-menu-fixed" dir="ltr" data-theme="theme-default" data-assets-path="<?= base_url() ?>/assets/" data-template="vertical-menu-template-free">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
    <title><?= $title ?? 'SportBase 2026' ?> | SportBase 2026</title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="<?= base_url() ?>/assets/img/favicon/favicon.ico" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Sarabun:wght@200;300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Icons -->
    <link rel="stylesheet" href="<?= base_url() ?>/assets/vendor/fonts/boxicons.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!-- Core CSS -->
    <link rel="stylesheet" href="<?= base_url() ?>/assets/vendor/css/core.css" class="template-customizer-core-css" />
    <link rel="stylesheet" href="<?= base_url() ?>/assets/vendor/css/theme-blue.css?v=1" class="template-customizer-theme-css" />
    <link rel="stylesheet" href="<?= base_url() ?>/assets/css/demo.css?v=1" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

    <!-- Helpers -->
    <script src="<?= base_url() ?>/assets/vendor/js/helpers.js"></script>
    <script src="<?= base_url() ?>/assets/js/config.js"></script>

    <style>
        body { font-family: 'Sarabun', sans-serif !important; }
        
        /* Premium Scrollbar */
        ::-webkit-scrollbar { width: 8px; }
        ::-webkit-scrollbar-track { background: #f1f1f1; }
        ::-webkit-scrollbar-thumb { background: #888; border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background: #555; }

        /* Custom Styles for Premium Feel */
        .layout-menu { box-shadow: 0 0.125rem 0.625rem 0 rgba(161, 172, 184, 0.45); }
        .menu-inner > .menu-item.active > .menu-link { 
            background: linear-gradient(135deg, #fd7e14 0%, #ff9e43 100%) !important; 
            box-shadow: 0 4px 12px rgba(253, 126, 20, 0.3);
        }
        .menu-inner > .menu-item.active > .menu-link > i,
        .menu-inner > .menu-item.active > .menu-link > div {
            color: #fff !important;
        }

        /* Generic Primary Button Override */
        .btn-primary {
            background: linear-gradient(135deg, #fd7e14 0%, #ff9e43 100%) !important;
            border: none !important;
            box-shadow: 0 4px 10px rgba(253, 126, 20, 0.2) !important;
        }
        .btn-primary:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 15px rgba(253, 126, 20, 0.3) !important;
            background: linear-gradient(135deg, #e86c00 0%, #fd7e14 100%) !important;
        }

        /* Premium Select2 Styling */
        .select2-container--default .select2-selection--single,
        .select2-container--default .select2-selection--multiple {
            border: 1px solid #e0e6ed;
            border-radius: 12px;
            min-height: 48px;
            padding: 8px 12px;
            background-color: #fbfcff;
            transition: all 0.2s ease;
        }
        
        .select2-container--default .select2-selection--single .select2-selection__rendered {
            color: #4b5d73;
            line-height: 30px;
            font-size: 0.95rem;
        }
        
        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 46px;
            right: 10px;
        }

        /* Focus & Open state */
        .select2-container--default.select2-container--focus .select2-selection--single,
        .select2-container--default.select2-container--focus .select2-selection--multiple,
        .select2-container--default.select2-container--open .select2-selection--single,
        .select2-container--default.select2-container--open .select2-selection--multiple {
            border-color: #fd7e14;
            background-color: #fff;
            box-shadow: 0 0 0 4px rgba(253, 126, 20, 0.1);
            outline: none;
        }

        /* Multi-selection chips */
        .select2-container--default .select2-selection--multiple .select2-selection__choice {
            background-color: #fd7e14;
            border: none;
            color: #fff;
            border-radius: 6px;
            padding: 2px 10px;
            margin-top: 2px;
            font-size: 0.85rem;
        }

        .select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
            color: #fff;
            margin-right: 5px;
            border-right: 1px solid rgba(255,255,255,0.2);
            padding-right: 5px;
        }

        .select2-container--default .select2-selection--multiple .select2-selection__choice__remove:hover {
            background-color: rgba(255,255,255,0.2);
            color: #fff;
        }

        .select2-dropdown {
            border: none;
            border-radius: 12px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.1);
            overflow: hidden;
            margin-top: 5px;
            z-index: 1061;
            background-color: #fff;
        }

        /* Search field styling and text color */
        .select2-container--default .select2-search--dropdown .select2-search__field {
            border: 1px solid #f0f2f5;
            border-radius: 8px;
            padding: 8px 12px;
            margin: 8px;
            width: calc(100% - 16px);
            background-color: #f8f9fa;
            color: #4b5d73 !important; /* Dark text for visibility */
            outline: none;
        }

        /* Normal result item text color */
        .select2-results__option {
            padding: 10px 15px;
            font-size: 0.9rem;
            color: #4b5d73; /* Dark text for visibility */
        }

        .select2-container--default .select2-results__option--highlighted[aria-selected] {
            background: linear-gradient(135deg, #fd7e14 0%, #ff9e43 100%) !important;
            color: #fff !important; /* Force white text */
        }

        .select2-container--default .select2-results__option[aria-selected=true] {
            background-color: rgba(253, 126, 20, 0.08);
            color: #fd7e14;
            font-weight: 600;
        }

        /* Fix for Select2 inside Modals */
        .select2-container--open {
            z-index: 9999 !important;
        }
    </style>
    
    <?= $this->renderSection('style') ?>
</head>
<body>
    <div class="layout-wrapper layout-content-navbar">
        <div class="layout-container">
            <!-- Sidebar -->
            <?php echo view('Admin/AdminLeyout/AdminMenuLeft'); ?>

            <div class="layout-page">
                <!-- Navbar -->
                <?php echo view('Admin/AdminLeyout/AdminNavbar'); ?>

                <!-- Content -->
                <div class="content-wrapper">
                    <?= $this->renderSection('content') ?>
                    
                    <!-- Footer -->
                    <footer class="content-footer footer bg-footer-theme">
                        <div class="container-xxl d-flex flex-wrap justify-content-between py-2 flex-md-row flex-column text-muted">
                            <div class="mb-2 mb-md-0">
                                © <?= date('Y') ?> SportBase 2026. <a href="https://skj.ac.th" class="footer-link fw-bolder">โรงเรียนสวนกุหลาบวิทยาลัย (จิรประวัติ) นครสวรรค์</a> 
                                พัฒนาโดย <a href="https://facebook.com/dekpiano" class="footer-link fw-bolder">Dekpiano</a>
                            </div>
                        </div>
                    </footer>
                </div>
            </div>
        </div>
    </div>

    <!-- Core JS -->
    <script src="<?= base_url() ?>/assets/vendor/libs/jquery/jquery.js"></script>
    <script src="<?= base_url() ?>/assets/vendor/libs/popper/popper.js"></script>
    <script src="<?= base_url() ?>/assets/vendor/js/bootstrap.js"></script>
    <script src="<?= base_url() ?>/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js"></script>
    <script src="<?= base_url() ?>/assets/vendor/js/menu.js"></script>
    
    <!-- Flatpickr & Select2 -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Main JS -->
    <script src="<?= base_url() ?>/assets/js/main.js"></script>
    
    <?= $this->renderSection('script') ?>
</body>
</html>
