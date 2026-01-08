<!-- Layout wrapper -->
<div class="layout-wrapper layout-content-navbar">
    <div class="layout-container">
        <!-- Menu -->

    <aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
        <div class="app-brand demo py-4 px-3">
            <a href="<?=base_url()?>" class="app-brand-link w-100 text-decoration-none">
                <div class="d-flex align-items-center p-3 bg-white rounded-3 shadow-sm">
                    <img src="https://skj.ac.th/uploads/logoSchool/LogoSKJ_4.png" alt="SKJ" width="48" class="rounded-circle" style="border: 2px solid #fd7e14;">
                    <div class="ms-3">
                        <div class="fw-bold" style="font-family: 'Outfit', sans-serif; font-size: 1.15rem; letter-spacing: -0.5px; line-height: 1.2;">
                            <span class="text-primary">SKJ</span> <span class="text-dark">SportBase</span>
                        </div>
                        <div class="d-flex align-items-center gap-2 mt-1">
                            <span class="badge bg-primary rounded-pill px-2" style="font-size: 0.6rem;">2026</span>
                            <span class="text-muted" style="font-size: 0.65rem;">ระบบนักกีฬา</span>
                        </div>
                    </div>
                </div>
            </a>

            <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto d-block d-xl-none position-absolute" style="top: 20px; right: 15px;">
                <i class="bx bx-chevron-left bx-sm align-middle text-primary"></i>
            </a>
        </div>

        <div class="menu-inner-shadow"></div>

        <ul class="menu-inner py-3">
            <!-- Main Section -->
            <li class="menu-header small text-uppercase">
                <span class="menu-header-text opacity-50">Main Menu</span>
            </li>
            <li class="menu-item <?= ($UrlMenuMain == "Main") ? "active" : "" ?>">
                <a href="<?=base_url();?>" class="menu-link p-3">
                    <i class="menu-icon tf-icons bx bx-grid-alt"></i>
                    <div class="fw-semibold">หน้าแรก</div>
                </a>
            </li>

            <!-- Athletes Section -->
            <li class="menu-header small text-uppercase mt-4">
                <span class="menu-header-text opacity-50">Data & Statistics</span>
            </li>
            <li class="menu-item <?= ($UrlMenuMain == "Athlete") ? "active" : "" ?>">
                <a href="<?= base_url('User/Athlete'); ?>" class="menu-link p-3">
                    <i class="menu-icon tf-icons bx bx-run"></i>
                    <div class="fw-semibold">ทำเนียบนักกีฬา</div>
                </a>
            </li>
            <li class="menu-item <?= ($UrlMenuMain == "Match") ? "active" : "" ?>">
                <a href="<?= base_url('User/Match'); ?>" class="menu-link p-3">
                    <i class="menu-icon tf-icons bx bx-calendar-event"></i>
                    <div class="fw-semibold">ตารางการแข่งขัน</div>
                </a>
            </li>
            <li class="menu-item <?= ($UrlMenuMain == "Attendance") ? "active" : "" ?>">
                <a href="<?= base_url('User/Attendance'); ?>" class="menu-link p-3">
                    <i class="menu-icon tf-icons bx bx-check-double"></i>
                    <div class="fw-semibold">สถานะภาพประจำวัน</div>
                </a>
            </li>
        </ul>

        <div class="mt-auto p-4">
            <?php if(isset($_SESSION['username']) && in_array(@$_SESSION['status'], ['admin', 'super_admin', 'coach'])): ?>
            <div class="card bg-label-primary border-0 shadow-none p-3" style="border-radius: 15px;">
                <div class="d-flex align-items-center">
                    <div class="avatar avatar-sm me-2">
                        <span class="avatar-initial rounded bg-primary"><i class="bx bxs-dashboard"></i></span>
                    </div>
                    <div class="flex-grow-1">
                        <h6 class="mb-0 fw-bold small">แผงควบคุม</h6>
                        <a href="<?=base_url('Admin/Home');?>" class="stretched-link text-primary small">ไปหลังบ้าน <i class='bx bx-right-arrow-alt'></i></a>
                    </div>
                </div>
            </div>
            <?php else: ?>
            <a href="<?=base_url('LoginOfficerSportBase');?>" class="btn btn-outline-primary w-100 rounded-pill py-2">
                <i class="bx bx-log-in me-1"></i> เข้าสู่ระบบเจ้าหน้าที่
            </a>
            <?php endif; ?>
        </div>
    </aside>
        <!-- / Menu -->

        <!-- Modal 1-->
        <div class="modal fade" id="modalToggle" aria-labelledby="modalToggleLabel" tabindex="-1" style="display: none;"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">

                    <div class="modal-body">
                        <div class="authentication-inner">
                            <!-- Register -->

                            <h4 class="mb-2">Welcome to Login SKJ E-Office 👋</h4>
                            <p class="mb-4">สำหรับเจ้าหน้าที่</p>


                            <div class="d-flex justify-content-center">
                                <?php //echo $GoogleButton; ?>
                            </div>

                            <!-- /Register -->
                        </div>
                    </div>
                </div>
            </div>
        </div>