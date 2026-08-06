<?php 
$seg1 = $uri->getSegment(1, '');
$seg2 = $uri->getSegment(2, '');

$isHome = ($seg1 == '' || ($seg1 == 'User' && ($seg2 == '' || $seg2 == 'Home')));
$isMatch = ($seg1 == 'User' && $seg2 == 'Match');
$isTeam = ($seg1 == 'User' && $seg2 == 'Athlete');
$isAttendance = ($seg1 == 'User' && $seg2 == 'Attendance');
?>

<style>
    /* Mobile-Optimized Sidebar Styling */
    #layout-menu {
        background: #ffffff !important;
        box-shadow: 0 0 25px rgba(0, 0, 0, 0.08) !important;
        border-right: 1px solid #f1f5f9;
    }
    
    @media (max-width: 1199.98px) {
        #layout-menu {
            width: 280px !important;
        }
    }

    .user-brand-header {
        padding: 1.1rem 1.1rem 0.75rem 1.1rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
        border-bottom: 1px solid #f8fafc;
    }

    .user-brand-logo-img {
        transition: transform 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
        filter: drop-shadow(0 2px 6px rgba(0, 0, 0, 0.15));
    }

    .suankularb-logo-svg {
        transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
        filter: drop-shadow(0 2px 5px rgba(255, 61, 135, 0.3));
    }

    .user-brand-link:hover .user-brand-logo-img {
        transform: scale(1.08) rotate(-5deg);
    }

    .user-brand-link:hover .suankularb-logo-svg {
        transform: scale(1.12) rotate(6deg);
        filter: drop-shadow(0 0 10px rgba(255, 61, 135, 0.7));
    }

    .menu-header-text {
        font-size: 0.68rem !important;
        font-weight: 800 !important;
        letter-spacing: 0.8px;
        color: #94a3b8 !important;
    }

    .menu-inner .menu-item .menu-link {
        border-radius: 12px !important;
        margin: 0.2rem 0.75rem !important;
        padding: 0.65rem 0.9rem !important;
        transition: all 0.22s ease-in-out !important;
        color: #475569 !important;
        font-weight: 600 !important;
    }

    .menu-inner .menu-item .menu-link:hover {
        background: rgba(253, 126, 20, 0.08) !important;
        color: #fd7e14 !important;
        transform: translateX(4px) !important;
    }

    .menu-inner .menu-item .menu-link:hover .menu-icon {
        color: #fd7e14 !important;
    }

    /* Active State styling */
    .menu-inner .menu-item.active > .menu-link {
        background: linear-gradient(135deg, #fd7e14 0%, #ff9e43 100%) !important;
        color: #ffffff !important;
        box-shadow: 0 4px 15px rgba(253, 126, 20, 0.35) !important;
        font-weight: 700 !important;
    }

    .menu-inner .menu-item.active > .menu-link .menu-icon {
        color: #ffffff !important;
    }

    .user-badge {
        background: rgba(253, 126, 20, 0.12);
        color: #fd7e14;
        font-size: 0.65rem;
        font-weight: 800;
        padding: 2px 8px;
        border-radius: 20px;
        letter-spacing: 0.5px;
    }
</style>

<!-- Layout wrapper -->
<div class="layout-wrapper layout-content-navbar">
    <div class="layout-container">
        <!-- Menu -->
        <aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
            
            <!-- Brand Header with Suankularb Theme & SVG Crest -->
            <div class="user-brand-header">
                <a href="<?=base_url();?>" class="user-brand-link d-flex align-items-center gap-2 text-decoration-none" title="ระบบฐานข้อมูลนักกีฬา โรงเรียนสวนกุหลาบวิทยาลัย (จิรประวัติ)">
                    <div class="d-flex align-items-center me-1">
                        <!-- Actual Generated Cyber-Athletic Rose Crest Image -->
                        <div class="position-relative overflow-hidden rounded-circle flex-shrink-0 shadow-sm" style="width: 38px; height: 38px; border: 2px solid #FF3D87; box-shadow: 0 0 10px rgba(255, 61, 135, 0.4) !important;">
                            <img src="<?=base_url('assets/img/skj_sportbase_logo.png')?>" alt="SportBase Suankularb Crest" class="w-100 h-100 user-brand-logo-img" style="object-fit: cover; transform: scale(1.18);">
                        </div>
                    </div>
                    <div class="d-flex flex-column">
                        <span class="fw-bold text-dark lh-1" style="font-size: 1.05rem; font-family: 'Outfit', sans-serif; letter-spacing: -0.3px;">
                            SKJ <span style="background: linear-gradient(135deg, #FF3D87 0%, #1E62EB 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; font-weight: 800;">SportBase</span>
                        </span>
                        <div class="mt-1 d-flex align-items-center gap-1">
                            <span class="badge rounded-pill text-white fw-bold px-2 py-0" style="background: linear-gradient(135deg, #1E62EB 0%, #00E5FF 100%); font-size: 0.58rem; letter-spacing: 0.5px; line-height: 14px;">2026</span>
                            <span class="badge rounded-pill text-white fw-bold px-2 py-0" style="background: linear-gradient(135deg, #FF3D87 0%, #FF8A00 100%); font-size: 0.58rem; letter-spacing: 0.4px; line-height: 14px;">SUANKULARB</span>
                        </div>
                    </div>
                </a>

                <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large p-1 text-muted d-block d-xl-none">
                    <i class="bx bx-chevron-left bx-sm align-middle"></i>
                </a>
            </div>

            <div class="menu-inner-shadow"></div>

            <ul class="menu-inner py-2">
                <!-- Main Section -->
                <li class="menu-header small text-uppercase mt-2">
                    <span class="menu-header-text">เมนูหลัก</span>
                </li>
                
                <li class="menu-item <?= ($isHome ? "active" : "") ?>">
                    <a href="<?=base_url();?>" class="menu-link">
                        <i class="menu-icon tf-icons bx bx-home-circle"></i>
                        <div>หน้าแรก</div>
                    </a>
                </li>

                <!-- Competition & Data Section -->
                <li class="menu-header small text-uppercase mt-3">
                    <span class="menu-header-text">การแข่งขัน & ข้อมูล</span>
                </li>
                
                <li class="menu-item <?= ($isTeam ? "active" : "") ?>">
                    <a href="<?= base_url('User/Athlete'); ?>" class="menu-link">
                        <i class="menu-icon tf-icons bx bx-run"></i>
                        <div>ทำเนียบนักกีฬา</div>
                    </a>
                </li>

                <li class="menu-item <?= ($isMatch ? "active" : "") ?>">
                    <a href="<?= base_url('User/Match'); ?>" class="menu-link">
                        <i class="menu-icon tf-icons bx bx-trophy"></i>
                        <div>ตารางการแข่งขัน & ผลงาน</div>
                    </a>
                </li>

                <li class="menu-item <?= ($isAttendance ? "active" : "") ?>">
                    <a href="<?= base_url('User/Attendance'); ?>" class="menu-link">
                        <i class="menu-icon tf-icons bx bx-calendar-check"></i>
                        <div>สถานะภาพประจำวัน</div>
                    </a>
                </li>

                <!-- Officer / System Section -->
                <li class="menu-header small text-uppercase mt-3">
                    <span class="menu-header-text">สำหรับเจ้าหน้าที่</span>
                </li>
            </ul>

            <div class="mt-auto p-3">
                <?php if(isset($_SESSION['username']) && in_array(@$_SESSION['status'], ['admin', 'super_admin', 'coach'])): ?>
                    <a href="<?=base_url('Admin/Home');?>" class="btn btn-warning w-100 rounded-pill py-2 text-white fw-bold shadow-sm d-flex align-items-center justify-content-center gap-2">
                        <i class="bx bxs-dashboard fs-5"></i> ไปหน้าผู้ดูแลระบบ
                    </a>
                <?php else: ?>
                    <a href="<?=base_url('LoginOfficerSportBase');?>" class="btn btn-outline-primary w-100 rounded-pill py-2 fw-semibold d-flex align-items-center justify-content-center gap-2">
                        <i class="bx bx-log-in fs-5"></i> เข้าสู่ระบบเจ้าหน้าที่
                    </a>
                <?php endif; ?>
            </div>
        </aside>
        <!-- / Menu -->