<?= $this->extend('Admin/layout/AdminLayout') ?>

<?= $this->section('content') ?>
<style>
    .premium-header {
        background: linear-gradient(135deg, #fd7e14 0%, #ff9e43 100%);
        border-radius: 20px;
        padding: 2.5rem;
        margin-bottom: 2rem;
        color: white;
        box-shadow: 0 10px 30px rgba(253, 126, 20, 0.2);
        position: relative;
        overflow: hidden;
    }

    .summary-card {
        border: none;
        border-radius: 18px;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        background: #fff;
        box-shadow: 0 4px 15px rgba(0,0,0,0.05);
    }

    .summary-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 12px 25px rgba(0,0,0,0.1);
    }

    .stat-icon {
        width: 50px;
        height: 50px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
    }

    .team-card {
        border-radius: 20px;
        border: 1px solid rgba(0,0,0,0.05);
        transition: all 0.3s ease;
        overflow: hidden;
    }

    .team-card:hover {
        border-color: #fd7e14;
        background: #fffcf9;
    }

    .team-overlay {
        height: 8px;
        width: 100%;
        background: linear-gradient(90deg, #fd7e14, #ff9e43);
    }

    .glass-badge {
        background: rgba(255, 255, 255, 0.2);
        backdrop-filter: blur(5px);
        border: 1px solid rgba(255, 255, 255, 0.3);
        color: white;
        padding: 5px 12px;
        border-radius: 10px;
        font-size: 0.8rem;
    }

    /* Responsive adjustments for 2-column mobile layout */
    @media (max-width: 576px) {
        .premium-header {
            padding: 1.5rem;
            border-radius: 15px;
        }
        .premium-header h2 {
            font-size: 1.5rem;
        }
        .summary-card .card-body {
            padding: 0.8rem !important;
        }
        .stat-icon {
            width: 35px;
            height: 35px;
            font-size: 1.2rem;
            border-radius: 10px;
        }
        .summary-card h4 {
            font-size: 1.1rem;
        }
        .summary-card small {
            font-size: 0.7rem;
        }
        .team-card .card-body {
            padding: 1rem !important;
        }
        .team-card h5 {
            font-size: 1rem;
        }
        .avatar-lg {
            width: 38px !important;
            height: 38px !important;
        }
        .avatar-lg i {
            font-size: 1.2rem !important;
        }
    }
</style>

<div class="container-xxl flex-grow-1 container-p-y">
    <div class="premium-header">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <h2 class="text-white fw-bold mb-2">ระบบเช็กชื่อ & ติดตามสถานะนักเรียน</h2>
                <p class="text-white-50 mb-0">เช็กชื่อเข้าแถวเช้า • ลงเวลาซ้อมกีฬา • เช็กชื่อเข้าห้องนอน • บันทึกการลา</p>
            </div>
            <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
                <span class="glass-badge">
                    <i class='bx bx-calendar me-1'></i> วันนี้: <?= date('d/m/Y') ?>
                </span>
            </div>
        </div>
    </div>

    <!-- Summary Section -->
    <div class="row g-4 mb-4">
        <div class="col-6 col-md-6">
            <div class="card summary-card">
                <div class="card-body">
                    <div class="d-flex align-items-center gap-2">
                        <div class="stat-icon bg-label-primary">
                            <i class='bx bxs-group'></i>
                        </div>
                        <div>
                            <small class="text-muted d-block">รวมนักกีฬา</small>
                            <h4 class="fw-bold mb-0">
                                <?php 
                                $totalAll = 0;
                                foreach($teams as $t) $totalAll += $t['athlete_count'];
                                echo $totalAll;
                                ?>
                            </h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-6">
            <div class="card summary-card">
                <div class="card-body">
                    <div class="d-flex align-items-center gap-2">
                        <div class="stat-icon bg-label-warning">
                            <i class='bx bxs-plus-medical'></i>
                        </div>
                        <div>
                            <small class="text-muted d-block">ลาป่วย</small>
                            <h4 class="fw-bold mb-0 text-warning">
                                <?php 
                                $sickCount = 0;
                                foreach($todaySummary as $s) if($s['att_status'] == 'sick') $sickCount = $s['count'];
                                echo $sickCount;
                                ?>
                            </h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-6">
            <div class="card summary-card">
                <div class="card-body">
                    <div class="d-flex align-items-center gap-2">
                        <div class="stat-icon bg-label-info">
                            <i class='bx bxs-briefcase'></i>
                        </div>
                        <div>
                            <small class="text-muted d-block">ลากิจธุระ</small>
                            <h4 class="fw-bold mb-0 text-info">
                                <?php 
                                $personalCount = 0;
                                foreach($todaySummary as $s) if($s['att_status'] == 'personal') $personalCount = $s['count'];
                                echo $personalCount;
                                ?>
                            </h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-6">
            <div class="card summary-card">
                <div class="card-body">
                    <div class="d-flex align-items-center gap-2">
                        <div class="stat-icon bg-label-danger">
                            <i class='bx bxs-home'></i>
                        </div>
                        <div>
                            <small class="text-muted d-block">ลากิจกลับบ้าน</small>
                            <h4 class="fw-bold mb-0 text-danger">
                                <?php 
                                $homeCount = 0;
                                foreach($todaySummary as $s) if($s['att_status'] == 'home') $homeCount = $s['count'];
                                echo $homeCount;
                                ?>
                            </h4>
                        </div>
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
                <div class="row g-4">
                    <?php foreach ($teams as $team) : ?>
                        <div class="col-12 col-md-6 col-lg-4">
                            <div class="team-card card h-100">
                                <div class="team-overlay"></div>
                                <div class="card-body">
                                    <div class="d-flex align-items-start justify-content-between mb-3">
                                        <div class="avatar avatar-lg">
                                            <span class="avatar-initial rounded-circle bg-label-primary px-2">
                                                <i class="bx bx-group"></i>
                                            </span>
                                        </div>
                                        <div class="dropdown">
                                            <button class="btn p-0" type="button" data-bs-toggle="dropdown">
                                                <i class="bx bx-dots-vertical-rounded"></i>
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li><a class="dropdown-item" href="<?= base_url('Admin/Team/Detail/' . $team['team_id']) ?>">ข้อมูลทีม</a></li>
                                            </ul>
                                        </div>
                                    </div>
                                    <h5 class="fw-bold mb-1"><?= $team['team_name']; ?></h5>
                                    <p class="text-muted small mb-3"><?= $team['team_sport_type'] ?: 'ไม่ระบุประเภทกีฬา'; ?></p>
                                    
                                    <div class="d-flex align-items-center gap-3 mb-4">
                                        <span class="badge bg-label-primary">
                                            <i class='bx bx-user me-1'></i> <?= $team['athlete_count']; ?> นักกีฬา
                                        </span>
                                        <span class="badge bg-label-warning">
                                            <i class='bx bx-user-voice me-1'></i> <?= $team['coach_count']; ?> โค้ช
                                        </span>
                                    </div>

                                    <a href="<?= base_url('Admin/Attendance/Team/' . $team['team_id']); ?>" 
                                       class="btn btn-primary w-100 rounded-pill fw-bold">
                                        <i class="bx bx-calendar-check me-1"></i> เข้าสู่ระบบเช็กชื่อ <i class="bx bx-chevron-right ms-1"></i>
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
