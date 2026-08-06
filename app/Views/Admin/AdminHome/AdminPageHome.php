<?= $this->extend('Admin/layout/AdminLayout') ?>

<?= $this->section('content') ?>
<style>
    /* Premium Modern Orange Dashboard Styles */
    .admin-hero-card {
        background: linear-gradient(135deg, #fd7e14 0%, #ff9e43 100%);
        border-radius: 24px;
        color: white;
        padding: 2.75rem 3rem;
        margin-bottom: 2.5rem;
        position: relative;
        overflow: hidden;
        box-shadow: 0 20px 40px rgba(253, 126, 20, 0.25);
    }

    .admin-hero-card::before {
        content: '';
        position: absolute;
        top: -40%;
        right: -10%;
        width: 450px;
        height: 450px;
        background: radial-gradient(circle, rgba(255, 255, 255, 0.2) 0%, transparent 70%);
        border-radius: 50%;
        pointer-events: none;
    }

    .hero-badge-pill {
        background: rgba(255, 255, 255, 0.2);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.3);
        padding: 6px 16px;
        border-radius: 30px;
        font-size: 0.8rem;
        font-weight: 700;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        color: #ffffff;
    }

    .hero-btn-white {
        background: #ffffff;
        color: #fd7e14 !important;
        font-weight: 700;
        border-radius: 50px;
        padding: 10px 24px;
        border: none;
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
        transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
    }

    .hero-btn-white:hover {
        transform: translateY(-3px) scale(1.03);
        box-shadow: 0 12px 25px rgba(0, 0, 0, 0.15);
        background: #ffffff;
    }

    .hero-btn-outline {
        background: rgba(255, 255, 255, 0.15);
        color: white !important;
        font-weight: 600;
        border-radius: 50px;
        padding: 10px 24px;
        border: 1px solid rgba(255, 255, 255, 0.4);
        backdrop-filter: blur(8px);
        transition: all 0.3s ease;
    }

    .hero-btn-outline:hover {
        background: rgba(255, 255, 255, 0.28);
        transform: translateY(-2px);
    }

    /* Stat Cards */
    .stat-card-modern {
        background: #ffffff;
        border-radius: 20px;
        border: 1px solid rgba(226, 232, 240, 0.8);
        padding: 1.5rem;
        transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
        position: relative;
        overflow: hidden;
        height: 100%;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.03);
    }

    .stat-card-modern:hover {
        transform: translateY(-6px);
        box-shadow: 0 18px 35px rgba(253, 126, 20, 0.15);
        border-color: rgba(253, 126, 20, 0.4);
    }

    .stat-card-icon {
        width: 54px;
        height: 54px;
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.6rem;
        transition: transform 0.3s ease;
    }

    .stat-card-modern:hover .stat-card-icon {
        transform: scale(1.1) rotate(4deg);
    }

    .bg-gradient-orange {
        background: linear-gradient(135deg, #fd7e14 0%, #ff9e43 100%);
        color: white;
    }

    /* Quick Shortcuts Grid */
    .quick-shortcut-btn {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        padding: 1.2rem 1rem;
        text-align: center;
        transition: all 0.25s ease;
        text-decoration: none;
        display: block;
        color: #334155;
        box-shadow: 0 4px 15px rgba(0,0,0,0.02);
    }

    .quick-shortcut-btn:hover {
        background: #fffcf9;
        border-color: #fd7e14;
        box-shadow: 0 8px 20px rgba(253, 126, 20, 0.15);
        transform: translateY(-3px);
        color: #fd7e14;
    }

    .quick-shortcut-icon {
        font-size: 1.8rem;
        margin-bottom: 0.5rem;
        display: block;
        color: #fd7e14;
        transition: transform 0.25s ease;
    }

    .quick-shortcut-btn:hover .quick-shortcut-icon {
        transform: scale(1.18);
    }

    /* Table styling */
    .match-row {
        transition: background-color 0.2s ease;
        border-bottom: 1px solid #f1f5f9;
    }

    .match-row:hover {
        background-color: #fffcf9;
    }

    /* Responsive Mobile & Smartphone Enhancements */
    @media (max-width: 767.98px) {
        .admin-hero-card {
            padding: 1.5rem 1.15rem;
            border-radius: 18px;
            margin-bottom: 1.5rem;
        }
        .hero-badge-pill {
            font-size: 0.72rem;
            padding: 4px 12px;
        }
        .admin-hero-card h1 {
            font-size: 1.45rem !important;
            margin-bottom: 0.5rem !important;
        }
        .admin-hero-card p {
            font-size: 0.82rem !important;
            margin-bottom: 1rem !important;
        }
        .hero-btn-white, .hero-btn-outline {
            width: 100%;
            justify-content: center;
            padding: 9px 18px;
            font-size: 0.85rem;
        }
    }

    @media (max-width: 575.98px) {
        .stat-card-modern {
            padding: 0.85rem 0.75rem;
            border-radius: 14px;
        }
        .stat-card-icon {
            width: 42px;
            height: 42px;
            font-size: 1.25rem;
            border-radius: 12px;
        }
        .stat-card-modern h2 {
            font-size: 1.35rem !important;
        }
        .stat-card-modern small {
            font-size: 0.68rem !important;
        }
        .quick-shortcut-btn {
            padding: 0.85rem 0.4rem;
            border-radius: 12px;
        }
        .quick-shortcut-icon {
            font-size: 1.45rem;
            margin-bottom: 0.25rem;
        }
        .quick-shortcut-btn span {
            font-size: 0.72rem !important;
        }
    }
</style>

<div class="container-xxl flex-grow-1 container-p-y">
    <!-- Hero Section (Orange Theme) -->
    <div class="admin-hero-card">
        <div class="row align-items-center relative-zone z-1">
            <div class="col-lg-8">
                <div class="hero-badge-pill mb-3">
                    <i class='bx bxs-hot'></i>
                    <span>ระบบบริหารจัดการกีฬาสวนกุหลาบฯ จิรประวัติ 2026</span>
                </div>
                <h1 class="text-white fw-extrabold mb-2 display-6" style="font-family: 'Outfit', sans-serif;">
                    ยินดีต้อนรับ, <?= session()->get('username') ?> 👋
                </h1>
                <p class="text-white-50 fs-6 mb-4 leading-relaxed" style="max-width: 600px;">
                    ศูนย์กลางการบริหารจัดการสถิตินักกีฬา การลงเวลาซ้อม และตารางการแข่งขันอย่างเป็นระบบและทรงประสิทธิภาพสูงสุด
                </p>
                <div class="d-flex flex-wrap gap-3">
                    <a href="<?= base_url('Admin/Attendance') ?>" class="btn hero-btn-white d-inline-flex align-items-center">
                        <i class='bx bx-calendar-check fs-5 me-2 text-warning'></i>เช็กชื่อ & บันทึกการลา
                    </a>
                    <a href="<?= base_url('Admin/Team') ?>" class="btn hero-btn-outline d-inline-flex align-items-center">
                        <i class='bx bx-group fs-5 me-2'></i>จัดการทีมและรุ่นกีฬา
                    </a>
                    <a href="<?= base_url('Admin/Match') ?>" class="btn hero-btn-outline d-inline-flex align-items-center">
                        <i class='bx bx-trophy fs-5 me-2'></i>ตารางแข่งขัน
                    </a>
                </div>
            </div>
            <div class="col-lg-4 d-none d-lg-block text-center position-relative">
                <div class="position-relative d-inline-block">
                    <img src="<?= base_url('assets/img/skj_sportbase_logo.png') ?>" 
                         alt="SportBase Logo" 
                         class="img-fluid rounded-circle shadow-lg position-relative" 
                         style="width: 160px; height: 160px; object-fit: cover; border: 4px solid rgba(255, 255, 255, 0.4); box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2) !important;">
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Overview (Orange Styling) -->
    <div class="row g-3 g-lg-4 mb-4">
        <div class="col-6 col-md-6 col-lg-3">
            <div class="stat-card-modern">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-muted fw-semibold small d-block mb-1">นักกีฬาทั้งหมด</span>
                        <h2 class="fw-extrabold text-dark mb-0" style="font-family: 'Outfit', sans-serif;"><?= number_format($countAllAthlete) ?></h2>
                        <small class="text-warning fw-bold me-1" style="font-size: 0.75rem;">
                            <i class='bx bx-trending-up me-1'></i>ในระบบ
                        </small>
                    </div>
                    <div class="stat-card-icon bg-gradient-orange shadow-sm">
                        <i class='bx bxs-user-detail'></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-6 col-lg-3">
            <div class="stat-card-modern">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-muted fw-semibold small d-block mb-1">ผู้ฝึกสอน (โค้ช)</span>
                        <h2 class="fw-extrabold text-dark mb-0" style="font-family: 'Outfit', sans-serif;"><?= number_format($countAllPersonnel) ?></h2>
                        <small class="text-warning fw-bold me-1" style="font-size: 0.75rem;">
                            <i class='bx bx-badge-check me-1'></i>ผู้ดูแล
                        </small>
                    </div>
                    <div class="stat-card-icon bg-gradient-orange shadow-sm">
                        <i class='bx bxs-user-voice'></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-6 col-lg-3">
            <div class="stat-card-modern">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-muted fw-semibold small d-block mb-1">ประเภทกีฬา</span>
                        <h2 class="fw-extrabold text-dark mb-0" style="font-family: 'Outfit', sans-serif;"><?= number_format($countAllSportType ?? 0) ?></h2>
                        <small class="text-warning fw-bold me-1" style="font-size: 0.75rem;">
                            <i class='bx bx-star me-1'></i>ชนิดกีฬา
                        </small>
                    </div>
                    <div class="stat-card-icon bg-gradient-orange shadow-sm">
                        <i class='bx bxs-trophy'></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-6 col-lg-3">
            <div class="stat-card-modern">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-muted fw-semibold small d-block mb-1">การแข่งขันทั้งหมด</span>
                        <h2 class="fw-extrabold text-dark mb-0" style="font-family: 'Outfit', sans-serif;"><?= number_format($countAllMatch ?? 0) ?></h2>
                        <small class="text-warning fw-bold me-1" style="font-size: 0.75rem;">
                            <i class='bx bx-calendar-event me-1'></i>แมตช์แข่ง
                        </small>
                    </div>
                    <div class="stat-card-icon bg-gradient-orange shadow-sm">
                        <i class='bx bxs-medal'></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Shortcuts Bar -->
    <div class="row g-3 mb-4">
        <div class="col-6 col-md-3">
            <a href="<?= base_url('Admin/Attendance') ?>" class="quick-shortcut-btn">
                <i class='bx bx-calendar-check quick-shortcut-icon'></i>
                <span class="fw-bold small">เช็กชื่อ / ลาการซ้อม</span>
            </a>
        </div>
        <div class="col-6 col-md-3">
            <a href="<?= base_url('Admin/Team') ?>" class="quick-shortcut-btn">
                <i class='bx bx-group quick-shortcut-icon'></i>
                <span class="fw-bold small">จัดการทีม / นักกีฬา</span>
            </a>
        </div>
        <div class="col-6 col-md-3">
            <a href="<?= base_url('Admin/Match') ?>" class="quick-shortcut-btn">
                <i class='bx bx-trophy quick-shortcut-icon'></i>
                <span class="fw-bold small">จัดตารางแข่งขัน</span>
            </a>
        </div>
        <div class="col-6 col-md-3">
            <a href="<?= base_url('Admin/Attendance/History') ?>" class="quick-shortcut-btn">
                <i class='bx bx-history quick-shortcut-icon'></i>
                <span class="fw-bold small">รายงานประวัติย้อนหลัง</span>
            </a>
        </div>
    </div>

    <!-- Main Dashboard Body (Full Width - Removed School Card) -->
    <div class="row g-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm rounded-20 p-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h5 class="fw-extrabold mb-1 d-flex align-items-center text-dark">
                            <i class='bx bx-run text-warning fs-4 me-2'></i>ตารางแมตช์แข่งขันที่กำลังจะมาถึง
                        </h5>
                        <small class="text-muted">รายการแข่งขันกีฬาของโรงเรียนสวนกุหลาบวิทยาลัย (จิรประวัติ)</small>
                    </div>
                    <a href="<?= base_url('Admin/Match') ?>" class="btn btn-sm btn-outline-warning rounded-pill px-3 fw-bold">
                        ดูรายการทั้งหมด <i class='bx bx-chevron-right ms-1'></i>
                    </a>
                </div>
                
                <?php if (empty($upcomingMatches)): ?>
                    <div class="text-center py-5">
                        <div class="avatar avatar-xl bg-label-warning rounded-circle mb-3 mx-auto d-flex align-items-center justify-content-center" style="width: 70px; height: 70px;">
                            <i class='bx bx-calendar-x fs-1 text-warning'></i>
                        </div>
                        <h6 class="fw-bold text-muted">ไม่มีตารางการแข่งขันที่กำลังจะมาถึง</h6>
                        <p class="text-muted small">ท่านสามารถเพิ่มโปรแกรมการแข่งขันใหม่ได้ที่เมนูจัดการการแข่งขัน</p>
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table align-middle mb-0">
                            <thead>
                                <tr class="text-muted small border-bottom">
                                    <th>รายการแข่งขัน / ทีม</th>
                                    <th class="text-center">วันที่ & เวลา</th>
                                    <th class="text-end">สถานะ</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($upcomingMatches as $match): 
                                    $isLive = ($match['match_status'] == 'In Progress');
                                    $badgeBg = $isLive ? 'bg-danger' : 'bg-warning text-dark';
                                    $badgeText = $isLive ? '● Live ตอนนี้' : 'เร็วๆ นี้';
                                ?>
                                    <tr class="match-row py-3">
                                        <td>
                                            <div class="d-flex align-items-center gap-3">
                                                <div class="stat-card-icon bg-gradient-orange rounded-circle text-white shadow-sm flex-shrink-0" style="width: 42px; height: 42px; font-size: 1.2rem;">
                                                    <i class='bx bx-trophy'></i>
                                                </div>
                                                <div>
                                                    <div class="fw-bold text-dark fs-6"><?= esc($match['match_title']) ?></div>
                                                    <div class="text-muted small">
                                                        <span class="badge bg-label-warning me-1"><?= esc($match['team_name']) ?></span>
                                                        <small><?= esc($match['team_sport_type']) ?></small>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <div class="fw-bold text-warning"><?= date('d/m/Y', strtotime($match['match_date'])) ?></div>
                                            <small class="text-muted"><i class='bx bx-time-five me-1'></i><?= $match['match_time'] ? date('H:i', strtotime($match['match_time'])) . ' น.' : 'ไม่ระบุเวลา' ?></small>
                                        </td>
                                        <td class="text-end">
                                            <span class="badge <?= $badgeBg ?> rounded-pill px-3 py-1.5 fw-semibold shadow-sm" style="font-size: 0.75rem;">
                                                <?= $badgeText ?>
                                            </span>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>