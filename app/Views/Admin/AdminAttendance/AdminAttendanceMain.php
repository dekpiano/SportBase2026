<?= $this->extend('Admin/layout/AdminLayout') ?>

<?= $this->section('content') ?>
<style>
    /* Keyframe Animations */
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(18px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes floatCircle {
        0%, 100% { transform: translateY(0px) rotate(0deg); }
        50% { transform: translateY(-10px) rotate(5deg); }
    }

    /* Header Banner with Decorative Animated Circles */
    .premium-header {
        background: linear-gradient(135deg, #fd7e14 0%, #ff9e43 100%);
        border-radius: 16px;
        padding: 1.25rem 1.75rem;
        margin-bottom: 1.25rem;
        color: white;
        box-shadow: 0 8px 25px rgba(253, 126, 20, 0.2);
        position: relative;
        overflow: hidden;
        animation: fadeInUp 0.45s ease-out forwards;
    }

    .premium-header-bg-circle {
        position: absolute;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.12);
        pointer-events: none;
        animation: floatCircle 8s ease-in-out infinite;
    }

    .premium-header-bg-circle.circle-1 {
        width: 140px;
        height: 140px;
        top: -30px;
        right: 15%;
    }

    .premium-header-bg-circle.circle-2 {
        width: 90px;
        height: 90px;
        bottom: -20px;
        right: 5%;
        animation-delay: 2.5s;
    }

    /* Summary Section Cards Animation & Design */
    .summary-card-col {
        animation: fadeInUp 0.45s ease-out forwards;
        animation-delay: calc(var(--i, 0) * 0.08s);
        opacity: 0;
    }

    .summary-card {
        border: 1px solid rgba(0, 0, 0, 0.06) !important;
        border-radius: 16px;
        transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
        background: #ffffff;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.03);
        position: relative;
        overflow: hidden;
    }

    .summary-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 4px;
        height: 100%;
        transition: all 0.3s ease;
    }

    .summary-card.card-primary::before { background: linear-gradient(180deg, #1E62EB, #00E5FF); }
    .summary-card.card-warning::before { background: linear-gradient(180deg, #fd7e14, #ff9e43); }
    .summary-card.card-info::before { background: linear-gradient(180deg, #03c3ec, #00E5FF); }
    .summary-card.card-danger::before { background: linear-gradient(180deg, #FF3D87, #ff6b8b); }

    .summary-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08) !important;
    }

    .stat-icon-wrapper {
        width: 48px;
        height: 48px;
        min-width: 48px;
        min-height: 48px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.45rem;
        transition: transform 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
    }

    .summary-card:hover .stat-icon-wrapper {
        transform: scale(1.12);
    }

    .icon-primary { background: rgba(30, 98, 235, 0.08); color: #1E62EB; border: 1px solid rgba(30, 98, 235, 0.15); }
    .icon-warning { background: rgba(253, 126, 20, 0.08); color: #fd7e14; border: 1px solid rgba(253, 126, 20, 0.15); }
    .icon-info { background: rgba(3, 195, 236, 0.08); color: #03c3ec; border: 1px solid rgba(3, 195, 236, 0.15); }
    .icon-danger { background: rgba(255, 61, 135, 0.08); color: #FF3D87; border: 1px solid rgba(255, 61, 135, 0.15); }

    /* Team Cards Animation */
    .team-card-col {
        animation: fadeInUp 0.45s ease-out forwards;
        animation-delay: calc(var(--i, 0) * 0.06s + 0.22s);
        opacity: 0;
    }

    .team-card {
        border-radius: 16px;
        border: 1px solid rgba(0,0,0,0.05);
        transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
        overflow: hidden;
        background: #fff;
    }

    .team-card:hover {
        border-color: #fd7e14;
        background: #fffcf9;
        box-shadow: 0 12px 28px rgba(253, 126, 20, 0.16) !important;
        transform: translateY(-5px);
    }

    .team-overlay {
        height: 4px;
        width: 100%;
        background: linear-gradient(90deg, #fd7e14, #ff9e43);
        transition: all 0.3s ease;
    }

    .team-card:hover .team-overlay {
        height: 5px;
        background: linear-gradient(90deg, #fd7e14, #ff3d87);
    }

    .team-card .avatar-initial {
        transition: transform 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
    }

    .team-card:hover .avatar-initial {
        transform: scale(1.12) rotate(6deg);
    }

    .team-card .btn-primary {
        transition: all 0.25s ease;
    }

    .team-card:hover .btn-primary {
        background: #e66a00 !important;
        border-color: #e66a00 !important;
        box-shadow: 0 4px 12px rgba(253, 126, 20, 0.3) !important;
    }

    .team-card .btn-primary i.bx-chevron-right {
        transition: transform 0.25s ease;
    }

    .team-card:hover .btn-primary i.bx-chevron-right {
        transform: translateX(4px);
    }

    .glass-badge {
        background: rgba(255, 255, 255, 0.2);
        backdrop-filter: blur(5px);
        border: 1px solid rgba(255, 255, 255, 0.3);
        color: white;
        padding: 5px 12px;
        border-radius: 10px;
        font-size: 0.8rem;
        transition: all 0.3s ease;
    }

    .glass-badge:hover {
        background: rgba(255, 255, 255, 0.3);
        transform: scale(1.04);
    }

    /* Responsive adjustments */
    @media (max-width: 576px) {
        .premium-header {
            padding: 1.2rem;
            border-radius: 14px;
        }
        .premium-header h3 {
            font-size: 1.35rem;
        }
        .stat-icon-wrapper {
            width: 40px;
            height: 40px;
            min-width: 40px;
            min-height: 40px;
            font-size: 1.2rem;
            border-radius: 10px;
        }
        .summary-card h3 {
            font-size: 1.3rem !important;
        }
        .summary-card span {
            font-size: 0.65rem !important;
        }
        .summary-card .card-body {
            padding: 0.75rem !important;
        }
        .team-card .card-body {
            padding: 0.85rem !important;
        }
        .team-card h6 {
            font-size: 0.95rem;
        }
    }
</style>

<div class="container-xxl flex-grow-1 container-p-y">
    <div class="premium-header">
        <div class="premium-header-bg-circle circle-1"></div>
        <div class="premium-header-bg-circle circle-2"></div>
        <div class="row align-items-center position-relative" style="z-index: 2;">
            <div class="col-lg-8">
                <h3 class="text-white fw-bold mb-1 fs-4">ระบบเช็กชื่อ & ติดตามสถานะนักเรียน</h3>
                <p class="text-white-50 mb-0 small">เช็กชื่อเข้าแถวเช้า • ลงเวลาซ้อมกีฬา • เช็กชื่อเข้าห้องนอน • บันทึกการลา</p>
            </div>
            <div class="col-lg-4 text-lg-end mt-2 mt-lg-0">
                <span class="glass-badge d-inline-flex align-items-center">
                    <i class='bx bx-calendar me-1'></i> วันนี้: <?= date('d/m/Y') ?>
                </span>
            </div>
        </div>
    </div>

    <!-- Summary Section -->
    <div class="row g-3 mb-4">
        <div class="col-6 col-md-3 summary-card-col" style="--i:1;">
            <div class="card summary-card card-primary h-100 border-0">
                <div class="card-body p-3 d-flex align-items-center justify-content-between">
                    <div class="d-flex flex-column justify-content-center overflow-hidden ps-1">
                        <span class="text-uppercase fw-bold text-muted mb-1" style="font-size: 0.7rem; letter-spacing: 0.5px;">รวมนักกีฬา</span>
                        <h3 class="fw-bolder mb-0 text-dark" style="font-family: 'Outfit', sans-serif;">
                            <?php 
                            $totalAll = 0;
                            foreach($teams as $t) $totalAll += $t['athlete_count'];
                            echo number_format($totalAll);
                            ?>
                        </h3>
                    </div>
                    <div class="stat-icon-wrapper icon-primary flex-shrink-0">
                        <i class='bx bxs-group'></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-6 col-md-3 summary-card-col" style="--i:2;">
            <div class="card summary-card card-warning h-100 border-0">
                <div class="card-body p-3 d-flex align-items-center justify-content-between">
                    <div class="d-flex flex-column justify-content-center overflow-hidden ps-1">
                        <span class="text-uppercase fw-bold text-muted mb-1" style="font-size: 0.7rem; letter-spacing: 0.5px;">ลาป่วย</span>
                        <h3 class="fw-bolder mb-0 text-warning" style="font-family: 'Outfit', sans-serif;">
                            <?php 
                            $sickCount = 0;
                            foreach($todaySummary as $s) if($s['att_status'] == 'sick') $sickCount = $s['count'];
                            echo number_format($sickCount);
                            ?>
                        </h3>
                    </div>
                    <div class="stat-icon-wrapper icon-warning flex-shrink-0">
                        <i class='bx bxs-first-aid'></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-6 col-md-3 summary-card-col" style="--i:3;">
            <div class="card summary-card card-info h-100 border-0">
                <div class="card-body p-3 d-flex align-items-center justify-content-between">
                    <div class="d-flex flex-column justify-content-center overflow-hidden ps-1">
                        <span class="text-uppercase fw-bold text-muted mb-1" style="font-size: 0.7rem; letter-spacing: 0.5px;">ลากิจธุระ</span>
                        <h3 class="fw-bolder mb-0 text-info" style="font-family: 'Outfit', sans-serif;">
                            <?php 
                            $personalCount = 0;
                            foreach($todaySummary as $s) if($s['att_status'] == 'personal') $personalCount = $s['count'];
                            echo number_format($personalCount);
                            ?>
                        </h3>
                    </div>
                    <div class="stat-icon-wrapper icon-info flex-shrink-0">
                        <i class='bx bxs-briefcase'></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-6 col-md-3 summary-card-col" style="--i:4;">
            <div class="card summary-card card-danger h-100 border-0">
                <div class="card-body p-3 d-flex align-items-center justify-content-between">
                    <div class="d-flex flex-column justify-content-center overflow-hidden ps-1">
                        <span class="text-uppercase fw-bold text-muted mb-1" style="font-size: 0.7rem; letter-spacing: 0.5px;">ลากิจกลับบ้าน</span>
                        <h3 class="fw-bolder mb-0 text-danger" style="font-family: 'Outfit', sans-serif;">
                            <?php 
                            $homeCount = 0;
                            foreach($todaySummary as $s) if($s['att_status'] == 'home') $homeCount = $s['count'];
                            echo number_format($homeCount);
                            ?>
                        </h3>
                    </div>
                    <div class="stat-icon-wrapper icon-danger flex-shrink-0">
                        <i class='bx bxs-home'></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
            <h5 class="mb-0 fw-bold"><i class='bx bx-grid-alt me-2 text-primary'></i>รายการรุ่น / ทีม</h5>
            <div class="d-flex gap-2">
                <a href="<?= base_url('Admin/Attendance/History'); ?>" class="btn btn-outline-primary btn-sm rounded-pill">
                    <i class="bx bx-history me-1"></i> ประวัติการลา
                </a>
            </div>
        </div>
        <div class="card-body">
            <?php if (empty($teams)) : ?>
                <div class="text-center py-5">
                    <img src="<?= base_url('assets/img/illustrations/empty-state.png') ?>" alt="empty" class="img-fluid mb-4" style="max-width: 200px;">
                    <h5 class="text-muted">ยังไม่มีข้อมูลทีมในระบบ</h5>
                    <a href="<?= base_url('Admin/Team'); ?>" class="btn btn-primary mt-2">
                        <i class="bx bx-plus me-1"></i> สร้างทีมใหม่
                    </a>
                </div>
            <?php else : ?>
                <div class="row g-3">
                    <?php $teamIdx = 1; foreach ($teams as $team) : ?>
                        <div class="col-12 col-sm-6 col-lg-4 col-xl-3 team-card-col" style="--i:<?= $teamIdx++; ?>;">
                            <div class="team-card card h-100 shadow-sm border-0">
                                <div class="team-overlay"></div>
                                <div class="card-body p-3 d-flex flex-column justify-content-between">
                                    <div>
                                        <div class="d-flex align-items-center justify-content-between mb-2">
                                            <div class="d-flex align-items-center gap-2 overflow-hidden me-1">
                                                <div class="avatar avatar-md flex-shrink-0">
                                                    <span class="avatar-initial rounded-3 bg-label-primary">
                                                        <i class="bx bx-group fs-5"></i>
                                                    </span>
                                                </div>
                                                <div class="overflow-hidden">
                                                    <h6 class="fw-bold mb-0 text-truncate text-dark" title="<?= $team['team_name']; ?>"><?= $team['team_name']; ?></h6>
                                                    <small class="text-muted text-truncate d-block" style="font-size: 0.73rem;">
                                                        <?= $team['team_sport_type'] ?: 'ไม่ระบุประเภทกีฬา'; ?>
                                                    </small>
                                                </div>
                                            </div>
                                            <div class="dropdown flex-shrink-0">
                                                <button class="btn p-0 text-muted" type="button" data-bs-toggle="dropdown">
                                                    <i class="bx bx-dots-vertical-rounded fs-5"></i>
                                                </button>
                                                <ul class="dropdown-menu dropdown-menu-end shadow-sm">
                                                    <li><a class="dropdown-item py-1" style="font-size: 0.82rem;" href="<?= base_url('Admin/Team/Detail/' . $team['team_id']) ?>"><i class="bx bx-info-circle me-1 text-primary"></i> ข้อมูลทีม</a></li>
                                                </ul>
                                            </div>
                                        </div>

                                        <div class="d-flex align-items-center gap-2 my-2 flex-wrap">
                                            <span class="badge bg-label-primary rounded-pill px-2 py-1" style="font-size: 0.72rem;">
                                                <i class='bx bx-user me-1'></i><?= $team['athlete_count']; ?> นักกีฬา
                                            </span>
                                            <span class="badge bg-label-warning rounded-pill px-2 py-1" style="font-size: 0.72rem;">
                                                <i class='bx bx-user-voice me-1'></i><?= $team['coach_count']; ?> โค้ช
                                            </span>
                                        </div>
                                    </div>

                                    <a href="<?= base_url('Admin/Attendance/Team/' . $team['team_id']); ?>" 
                                       class="btn btn-primary btn-sm w-100 rounded-pill fw-bold mt-2 py-1.5">
                                        <i class="bx bx-calendar-check me-1"></i> เช็กชื่อทีมนี้ <i class="bx bx-chevron-right ms-1"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
