<?= $this->extend('Admin/layout/AdminLayout') ?>

<?= $this->section('content') ?>
<style>
    .welcome-card {
        background: linear-gradient(135deg, #fd7e14 0%, #ff9e43 100%);
        border-radius: 20px;
        color: white;
        padding: 3rem;
        margin-bottom: 2.5rem;
        position: relative;
        overflow: hidden;
        box-shadow: 0 15px 35px rgba(253, 126, 20, 0.2);
    }

    .welcome-card::after {
        content: '';
        position: absolute;
        top: -50px;
        right: -50px;
        width: 200px;
        height: 200px;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 50%;
    }

    .glass-stat-card {
        border-radius: 20px;
        border: none;
        background: #fff;
        box-shadow: 0 5px 25px rgba(0,0,0,0.05);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        height: 100%;
    }

    .glass-stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    }

    .stat-icon-wrapper {
        width: 60px;
        height: 60px;
        border-radius: 15px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.8rem;
    }

    .quick-action-card {
        background: #fff;
        border-radius: 15px;
        padding: 1.5rem;
        border: 1px solid #f1f4f8;
        transition: all 0.2s;
        text-align: center;
    }

    .quick-action-card:hover {
        border-color: #fd7e14;
        background: #fffcf9;
    }

    /* Mobile Responsive Adjustments */
    @media (max-width: 576px) {
        .welcome-card {
            padding: 1.5rem;
        }
        .glass-stat-card .card-body {
            padding: 1rem;
        }
        .stat-icon-wrapper {
            width: 40px;
            height: 40px;
            font-size: 1.2rem;
            border-radius: 10px;
        }
        .glass-stat-card h2 {
            font-size: 1.5rem;
        }
        .glass-stat-card p {
            font-size: 0.75rem;
        }
        .row.g-4 {
            --bs-gutter-x: 1rem;
            --bs-gutter-y: 1rem;
        }
    }
</style>

<div class="container-xxl flex-grow-1 container-p-y">
    <!-- Hero Section -->
    <div class="welcome-card">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <h1 class="text-white fw-bold mb-3">สวัสดี, <?= session()->get('username') ?></h1>
                <p class="text-white-50 fs-5 mb-4">ยินดีต้อนรับเข้าใช้งานระบบ SKJ SportBase 2026<br>ศูนย์รวมข้อมูลและสถิตินักกีฬาของโรงเรียนที่มีประสิทธิภาพสูงสุด</p>
                <div class="d-flex gap-3">
                    <a href="<?= base_url('Admin/Attendance') ?>" class="btn btn-white bg-white text-dark fw-bold rounded-pill px-4 shadow-sm border-0">
                        <i class='bx bx-calendar-check me-2 text-primary'></i>บันทึกการลา
                    </a>
                    <a href="<?= base_url('Admin/Team') ?>" class="btn btn-outline-light rounded-pill px-4 border-2">
                        <i class='bx bx-group me-2'></i>จัดการทีม
                    </a>
                </div>
            </div>
            <div class="col-lg-4 d-none d-lg-block text-center">
                <img src="<?= base_url('assets/img/illustrations/athlete-silhouette.png') ?>" 
                     height="200" alt="Athlete Silhouette" 
                     style="filter: invert(1); mix-blend-mode: screen; opacity: 0.2; transform: translateX(20px);">
            </div>
        </div>
    </div>

    <!-- Stats Overview -->
    <div class="row g-4 mb-5">
        <div class="col-6 col-md-6 col-lg-3">
            <div class="card glass-stat-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-3">
                        <div class="stat-icon-wrapper bg-label-primary">
                            <i class='bx bxs-user'></i>
                        </div>
                        <div class="text-end">
                            <p class="text-muted mb-0">นักกีฬาทั้งหมด</p>
                            <h2 class="fw-bold mb-0"><?= $countAllAthlete ?></h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-6 col-lg-3">
            <div class="card glass-stat-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-3">
                        <div class="stat-icon-wrapper bg-label-warning">
                            <i class='bx bxs-user-voice'></i>
                        </div>
                        <div class="text-end">
                            <p class="text-muted mb-0">ผู้ฝึกสอน (โค้ช)</p>
                            <h2 class="fw-bold mb-0"><?= $countAllPersonnel ?></h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-6 col-lg-3">
            <div class="card glass-stat-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-3">
                        <div class="stat-icon-wrapper bg-label-success">
                            <i class='bx bxs-trophy'></i>
                        </div>
                        <div class="text-end">
                            <p class="text-muted mb-0">ประเภทกีฬา</p>
                            <h2 class="fw-bold mb-0"><?= $countAllSportType ?? 0 ?></h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-6 col-lg-3">
            <div class="card glass-stat-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-3">
                        <div class="stat-icon-wrapper bg-label-info">
                            <i class='bx bxs-medal'></i>
                        </div>
                        <div class="text-end">
                            <p class="text-muted mb-0">การแข่งขันทั้งหมด</p>
                            <h2 class="fw-bold mb-0"><?= $countAllMatch ?? 0 ?></h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Access / Bottom Section -->
    <div class="row">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-20 p-4 mb-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h5 class="fw-bold mb-0"><i class='bx bx-trophy me-2 text-primary'></i>ตารางการแข่งขันที่กำลังจะมาถึง</h5>
                    <a href="<?= base_url('Admin/Match') ?>" class="btn btn-sm btn-label-primary">ดูทั้งหมด</a>
                </div>
                
                <?php if (empty($upcomingMatches)): ?>
                    <div class="text-center py-5">
                        <i class='bx bx-calendar-x fs-1 text-muted mb-3 d-block'></i>
                        <p class="text-muted">ไม่มีตารางการแข่งขันที่กำลังจะมาถึงในขณะนี้</p>
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-borderless align-middle">
                            <tbody>
                                <?php foreach ($upcomingMatches as $match): 
                                    $sColor = ($match['match_status'] == 'In Progress') ? 'warning' : 'info';
                                    $sText = ($match['match_status'] == 'In Progress') ? 'Live Now' : 'Upcoming';
                                ?>
                                    <tr class="border-bottom">
                                        <td width="60">
                                            <div class="stat-icon-wrapper bg-label-primary rounded-circle" style="width: 45px; height: 45px;">
                                                <i class='bx bx-run fs-4'></i>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="fw-bold text-dark"><?= $match['match_title'] ?></div>
                                            <small class="text-muted"><?= $match['team_name'] ?> • <?= $match['team_sport_type'] ?></small>
                                        </td>
                                        <td class="text-center">
                                            <div class="fw-semibold text-primary"><?= date('d M Y', strtotime($match['match_date'])) ?></div>
                                            <small class="text-muted"><?= $match['match_time'] ? date('H:i', strtotime($match['match_time'])) . ' น.' : 'ไม่ระบุเวลา' ?></small>
                                        </td>
                                        <td class="text-end">
                                            <span class="badge bg-label-<?= $sColor ?> rounded-pill"><?= $sText ?></span>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm rounded-20 p-4 mb-4">
                <h5 class="fw-bold mb-4">ข้อมูลโรงเรียน</h5>
                <div class="text-center">
                    <img src="https://skj.ac.th/uploads/logoSchool/LogoSKJ_4.png" alt="SKJ Logo" width="80" class="mb-3">
                    <h6 class="fw-bold">สวนกุหลาบวิทยาลัย (จิรประวัติ)</h6>
                    <p class="text-muted small">Suankularb Wittayalai Jiraprawat School</p>
                    <hr>
                    <div class="text-start mt-3">
                        <div class="d-flex align-items-center mb-2">
                            <i class='bx bx-map me-2 text-primary'></i>
                            <small>ต.นครสวรรค์ออก อ.เมือง จ.นครสวรรค์</small>
                        </div>
                        <div class="d-flex align-items-center">
                            <i class='bx bx-world me-2 text-primary'></i>
                            <small>www.skj.ac.th</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>