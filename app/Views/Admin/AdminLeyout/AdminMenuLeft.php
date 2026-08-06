<?php 
$seg2 = $uri->getSegment(2, '');
$seg3 = $uri->getSegment(3, '');

$isHome = in_array($seg2, ['Home', '']);
$isTeam = ($seg2 == 'Team');
$isMatch = ($seg2 == 'Match');
$isAttendanceMain = ($seg2 == 'Attendance' && $seg3 != 'History');
$isAttendanceHistory = ($seg2 == 'Attendance' && $seg3 == 'History');
$isRoles = ($seg2 == 'Roles');
?>

<style>
    /* Premium Sidebar UX/UI Styling */
    #layout-menu {
        background: #ffffff !important;
        box-shadow: 0 0 25px rgba(0, 0, 0, 0.08) !important;
        border-right: 1px solid #f1f5f9;
    }

    @media (max-width: 1199.98px) {
        #layout-menu {
            width: 280px !important;
        }
        .app-brand {
            padding: 1.1rem 1.1rem 0.75rem 1.1rem !important;
        }
    }
    
    .app-brand {
        padding: 1.25rem 1.25rem;
        margin-bottom: 0.5rem;
    }
    
    .app-brand-logo-img {
        transition: transform 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
        filter: drop-shadow(0 4px 10px rgba(253, 126, 20, 0.25));
    }
    
    .app-brand-link:hover .app-brand-logo-img {
        transform: scale(1.1) rotate(-4deg);
    }
    
    .menu-header-text {
        font-size: 0.68rem !important;
        font-weight: 800 !important;
        letter-spacing: 0.8px;
        color: #94a3b8 !important;
    }

    .menu-inner .menu-item .menu-link {
        border-radius: 12px !important;
        margin: 0.2rem 0.8rem !important;
        padding: 0.68rem 1rem !important;
        transition: all 0.25s ease-in-out !important;
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

    .admin-badge {
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
            <div class="app-brand demo">
                <a href="<?=base_url('Admin/Home');?>" class="app-brand-link d-flex align-items-center gap-2 text-decoration-none" title="ระบบจัดการฐานข้อมูลนักกีฬา สวนกุหลาบวิทยาลัย (จิรประวัติ)">
                    <div class="d-flex align-items-center me-1">
                        <div class="position-relative overflow-hidden rounded-circle flex-shrink-0 shadow-sm" style="width: 38px; height: 38px; border: 2px solid #FF3D87; box-shadow: 0 0 10px rgba(255, 61, 135, 0.4) !important;">
                            <img src="<?=base_url('assets/img/skj_sportbase_logo.png')?>" alt="SportBase Crest" class="w-100 h-100 app-brand-logo-img" style="object-fit: cover; transform: scale(1.18);">
                        </div>
                    </div>
                    <div class="d-flex flex-column">
                        <span class="app-brand-text menu-text fw-bold text-dark fs-6 lh-1" style="font-family: 'Outfit', sans-serif;">
                            SKJ <span style="background: linear-gradient(135deg, #FF3D87 0%, #1E62EB 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; font-weight: 800;">SportBase</span>
                        </span>
                        <div class="mt-1 d-flex align-items-center gap-1">
                            <span class="badge rounded-pill text-white fw-bold px-2 py-0" style="background: linear-gradient(135deg, #FF3D87 0%, #FF8A00 100%); font-size: 0.58rem; letter-spacing: 0.5px; line-height: 14px;">ADMIN</span>
                            <span class="badge rounded-pill text-white fw-bold px-2 py-0" style="background: linear-gradient(135deg, #1E62EB 0%, #00E5FF 100%); font-size: 0.58rem; letter-spacing: 0.5px; line-height: 14px;">2026</span>
                        </div>
                    </div>
                </a>

                <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto d-block d-xl-none">
                    <i class="bx bx-chevron-left bx-sm align-middle"></i>
                </a>
            </div>

            <div class="menu-inner-shadow"></div>

            <ul class="menu-inner py-2">
                <!-- Dashboard -->
                <li class="menu-item <?= ($isHome ? 'active' : '') ?>">
                    <a href="<?=base_url('Admin/Home');?>" class="menu-link">
                        <i class="menu-icon tf-icons bx bx-home-circle"></i>
                        <div data-i18n="Analytics">หน้าแรก</div>
                    </a>
                </li>

                <?php if(in_array(session()->get('status'), ["admin", "super_admin", "coach"])) : ?>
                <li class="menu-header small text-uppercase mt-2">
                    <span class="menu-header-text">จัดการข้อมูลนักกีฬา</span>
                </li>

                <li class="menu-item <?= ($isTeam ? 'active' : '') ?>">
                    <a href="<?=base_url('Admin/Team');?>" class="menu-link">
                        <i class="menu-icon tf-icons bx bx-group"></i>
                        <div data-i18n="Analytics">รุ่นนักกีฬา</div>
                    </a>
                </li>
                <?php endif; ?>

                <li class="menu-header small text-uppercase mt-2">
                    <span class="menu-header-text">การแข่งขัน & กิจกรรม</span>
                </li>

                <li class="menu-item <?= ($isMatch ? 'active' : '') ?>">
                    <a href="<?=base_url('Admin/Match');?>" class="menu-link">
                        <i class="menu-icon tf-icons bx bx-trophy"></i>
                        <div data-i18n="Analytics">ตารางแข่งขัน</div>
                    </a>
                </li>

                <li class="menu-header small text-uppercase mt-2">
                    <span class="menu-header-text">ระบบเช็กชื่อ & ติดตามสถานะ</span>
                </li>

                <li class="menu-item <?= ($isAttendanceMain ? 'active' : '') ?>">
                    <a href="<?=base_url('Admin/Attendance');?>" class="menu-link">
                        <i class="menu-icon tf-icons bx bx-calendar-check"></i>
                        <div data-i18n="Analytics" style="line-height: 1.3;">เช็กชื่อนักเรียน (เช้า/ซ้อม/นอน)</div>
                    </a>
                </li>

                <li class="menu-item <?= ($isAttendanceHistory ? 'active' : '') ?>">
                    <a href="<?=base_url('Admin/Attendance/History');?>" class="menu-link">
                        <i class="menu-icon tf-icons bx bx-history"></i>
                        <div data-i18n="Analytics" style="line-height: 1.3;">รายงานประวัติเช็กชื่อ & การลา</div>
                    </a>
                </li>

                <?php if(in_array(session()->get('status'), ["admin", "super_admin"])) : ?>
                <li class="menu-header small text-uppercase mt-2">
                    <span class="menu-header-text">ตั้งค่าระบบ</span>
                </li>

                <li class="menu-item <?= ($isRoles ? 'active' : '') ?>">
                    <a href="<?=base_url('Admin/Roles/Setting');?>" class="menu-link">
                        <i class="menu-icon tf-icons bx bx-shield-quarter"></i>
                        <div data-i18n="Analytics">กำหนดสิทธิ์ใช้งาน</div>
                    </a>
                </li>
                <?php endif; ?>

            </ul>
        </aside>
        <!-- / Menu -->