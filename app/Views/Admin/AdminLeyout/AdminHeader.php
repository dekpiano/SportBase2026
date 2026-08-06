<!DOCTYPE html>
<html
  lang="en"
  class="light-style layout-menu-fixed"
  dir="ltr"
  data-theme="theme-default"
  data-assets-path="<?=base_url()?>/assets/"
  data-template="vertical-menu-template-free"
>
  <head>
    <meta charset="utf-8" />
    <meta
      name="viewport"
      content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0"
    />

    <title><?=$title;?> | SportBase 2026</title>

    <meta name="description" content="" />

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="<?=base_url()?>/assets/img/favicon/favicon.ico" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Sarabun:wght@200;300&display=swap" rel="stylesheet">

    <!-- Icons. Uncomment required icon fonts -->
    <link rel="stylesheet" href="<?=base_url()?>/assets/vendor/fonts/boxicons.css" />

    <!-- Core CSS -->
    <link rel="stylesheet" href="<?=base_url()?>/assets/vendor/css/core.css" class="template-customizer-core-css" />
    <link rel="stylesheet" href="<?=base_url()?>/assets/vendor/css/theme-blue.css?v=1" class="template-customizer-theme-css" />
    <link rel="stylesheet" href="<?=base_url()?>/assets/css/select2.css?v=3" />
    <link rel="stylesheet" href="<?=base_url()?>/assets/css/demo.css?v=1" />

    <!-- <link rel="stylesheet" href="<?=base_url()?>/assets/css/flatpickr.css?v=1" /> -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <!-- Vendors CSS -->
    <link rel="stylesheet" href="<?=base_url()?>/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css" />

    <link rel="stylesheet" href="<?=base_url()?>/assets/vendor/libs/apex-charts/apex-charts.css" />

        <!-- Datatable css -->       
        <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css" />

    <!-- Page CSS -->

    <!-- Helpers -->
    <script src="<?=base_url()?>/assets/vendor/js/helpers.js"></script>

    <!--! Template customizer & Theme config files MUST be included after core stylesheets and helpers.js in the <head> section -->
    <!--? Config:  Mandatory theme config file contain global vars & default theme options, Set your preferred theme option in this file.  -->
    <script src="<?=base_url()?>/assets/js/config.js"></script>
  </head>

  <style>
    /* Select2 Styling - Match Bootstrap 5 form-select */
    .select2-container {
        width: 100% !important;
    }
    
    .select2-container--default .select2-selection--single {
        height: calc(2.25rem + 2px);
        padding: 0.375rem 0.75rem;
        font-size: 0.9375rem;
        font-weight: 400;
        line-height: 1.5;
        color: #697a8d;
        background-color: #fff;
        background-clip: padding-box;
        border: 1px solid #d9dee3;
        border-radius: 0.375rem;
        transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
    }
    
    .select2-container--default .select2-selection--single .select2-selection__rendered {
        line-height: 1.5;
        padding-left: 0;
        color: #697a8d;
    }
    
    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: calc(2.25rem);
        right: 8px;
    }
    
    .select2-container--default .select2-selection--single .select2-selection__placeholder {
        color: #a1acb8;
    }
    
    .select2-container--default.select2-container--focus .select2-selection--single,
    .select2-container--default.select2-container--open .select2-selection--single {
        border-color: #696cff;
        box-shadow: 0 0 0.25rem 0.05rem rgba(105, 108, 255, 0.1);
    }
    
    .select2-dropdown {
        border: 1px solid #d9dee3;
        border-radius: 0.375rem;
        box-shadow: 0 0.25rem 1rem rgba(161, 172, 184, 0.45);
    }
    
    .select2-container--default .select2-results__option--highlighted[aria-selected] {
        background-color: #696cff;
        color: #fff;
    }
    
    .select2-container--default .select2-results__option[aria-selected=true] {
        background-color: #e7e7ff;
        color: #696cff;
    }
    
    .select2-container--default .select2-search--dropdown .select2-search__field {
        border: 1px solid #d9dee3;
        border-radius: 0.375rem;
        padding: 0.375rem 0.75rem;
    }
    
    .select2-container--default .select2-search--dropdown .select2-search__field:focus {
        border-color: #696cff;
        outline: none;
    }

    /* Validation states */
    .was-validated select:valid + .select2 .select2-selection,
    .was-validated .is-valid + .select2 .select2-selection {
        border-color: #71dd37 !important;
    }

    .was-validated select:invalid + .select2 .select2-selection,
    .was-validated .is-invalid + .select2 .select2-selection {
        border-color: #ff3e1d !important;
    }

    /* SweetAlert2 High Priority Overlay */
    .swal2-container {
        z-index: 99999 !important;
    }
  </style>

  <body style="font-family:'Sarabun'">