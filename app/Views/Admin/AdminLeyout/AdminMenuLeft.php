<!-- Layout wrapper -->
<div class="layout-wrapper layout-content-navbar">
    <div class="layout-container">
        <!-- Menu -->

        <aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
            <div class="app-brand demo">
                <a href="<?=base_url('Admin/Home');?>" class="app-brand-link">
                    <span class="app-brand-logo demo">
                        <img src="https://skj.ac.th/uploads/logoSchool/LogoSKJ_4.png" alt="" width="40">
                    </span>
                    <span class="app-brand-text menu-text fw-bolder ms-2">SKJ SportBase 2026
                        <small>(Admin)</small></span>
                </a>

                <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto d-block d-xl-none">
                    <i class="bx bx-chevron-left bx-sm align-middle"></i>
                </a>
            </div>

            <div class="menu-inner-shadow"></div>

            <ul class="menu-inner py-1">
                <!-- Dashboard -->
                <li class="menu-item <?php echo ($uri->getSegment(2) == "Home"?"active":"")?>">
                    <a href="<?=base_url('Admin/Home');?>" class="menu-link">
                        <i class="menu-icon tf-icons bx bx-home-circle"></i>
                        <div data-i18n="Analytics">หน้าแรก </div>
                    </a>
                </li>
                <?php if(in_array(session()->get('status'), ["admin", "super_admin", "coach"])) : ?>
                <li class="menu-header small text-uppercase">
                    <span class="menu-header-text">จัดการข้อมูลนักกีฬา</span>
                </li>

                <li class="menu-item <?php echo ($uri->getSegment(2) == "Team"?"active":"")?>">
                    <a href="<?=base_url('Admin/Team');?>" class="menu-link">
                        <i class="menu-icon tf-icons bx bx-group"></i>
                        <div data-i18n="Analytics">รุ่นนักกีฬา</div>
                    </a>
                </li>
                <?php endif; ?>

                <li class="menu-header small text-uppercase">
                    <span class="menu-header-text">การแข่งขัน</span>
                </li>

                <li class="menu-item <?php echo ($uri->getSegment(2) == "Match"?"active":"")?>">
                    <a href="<?=base_url('Admin/Match');?>" class="menu-link">
                        <i class="menu-icon tf-icons bx bx-trophy"></i>
                        <div data-i18n="Analytics">ตารางแข่งขัน</div>
                    </a>
                </li>

                <li class="menu-header small text-uppercase">
                    <span class="menu-header-text">การติดตามผล</span>
                </li>

                <li class="menu-item <?php echo ($uri->getSegment(2) == "Attendance" && $uri->getSegment(3) == "" ?"active":"")?>">
                    <a href="<?=base_url('Admin/Attendance');?>" class="menu-link">
                        <i class="menu-icon tf-icons bx bx-calendar-check"></i>
                        <div data-i18n="Analytics">บันทึกการลานักกีฬา</div>
                    </a>
                </li>

                <li class="menu-item <?php echo ($uri->getSegment(2) == "Attendance" && $uri->getSegment(3) == "History" ?"active":"")?>">
                    <a href="<?=base_url('Admin/Attendance/History');?>" class="menu-link">
                        <i class="menu-icon tf-icons bx bx-history"></i>
                        <div data-i18n="Analytics">สรุปการลาทั้งหมด</div>
                    </a>
                </li>

            



                <!-- Layouts -->
                <?php $SubRloes = explode(',', (session()->get('rloes') ?? 'Member')); ?>



            </ul>

            <?php if(session()->get('status') == "admin" || session()->get('status') == "super_admin" ) : ?>
            <div>
                <ul class="menu-inner py-1">
                    <li class="menu-item <?php echo $uri->getSegment(2) == "Roles"?"active":""?>">
                        <a href="<?=base_url('Admin/Roles/Setting');?>" class="menu-link">
                            <i class="menu-icon tf-icons bx bx-spreadsheet"></i>
                            <div data-i18n="Analytics">กำหนดสิทธิ์ใช้งาน </div>
                        </a>
                    </li>
                </ul>
            </div>
            <?php endif; ?>
        </aside>
        <!-- / Menu -->