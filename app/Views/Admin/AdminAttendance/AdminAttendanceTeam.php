<?= $this->extend('Admin/layout/AdminLayout') ?>

<?= $this->section('content') ?>
<style>
    /* Premium Orange Theme & Mobile First */
    :root {
        --sb-orange: #f97316;
        --sb-orange-gradient: linear-gradient(135deg, #f97316 0%, #fb923c 100%);
        --sb-shadow: 0 10px 25px rgba(249, 115, 22, 0.15);
    }

    .attendance-header {
        background: var(--sb-orange-gradient);
        border-radius: 20px;
        padding: 2rem;
        color: white;
        margin-bottom: 2rem;
        box-shadow: var(--sb-shadow);
    }

    /* Mobile First Horizontal Scroll Bar for Batch Actions */
    .batch-scroll-container {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        overflow-x: auto;
        white-space: nowrap;
        -webkit-overflow-scrolling: touch;
        scrollbar-width: none; /* Firefox */
        padding-bottom: 4px;
    }
    .batch-scroll-container::-webkit-scrollbar {
        display: none; /* Chrome/Safari */
    }

    /* Mobile First Toggle Group */
    .status-container {
        display: flex;
        background: #f1f5f9;
        padding: 4px;
        border-radius: 14px;
        border: 1px solid #e2e8f0;
        width: 100%;
        max-width: 440px;
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
        scrollbar-width: none;
    }
    .status-container::-webkit-scrollbar {
        display: none;
    }

    .status-item {
        flex: 1 0 auto;
        min-width: 68px;
        position: relative;
    }

    .status-item input[type="radio"] {
        position: absolute;
        opacity: 0;
        width: 0;
        height: 0;
    }

    .status-btn {
        display: flex;
        align-items: center;
        justify-content: center;
        height: 44px;
        border-radius: 10px;
        font-size: 0.82rem;
        font-weight: 700;
        color: #475569;
        cursor: pointer;
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        text-align: center;
        line-height: 1.2;
        background: #ffffff;
        border: 1px solid #d1d5db;
        margin: 2px;
        box-shadow: 0 2px 5px rgba(0,0,0,0.06);
        user-select: none;
    }

    .status-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 12px rgba(0, 0, 0, 0.12);
        border-color: #9ca3af;
        color: #1e293b;
    }

    .status-btn:active {
        transform: scale(0.92) translateY(0);
    }

    /* Active Checked States with Scale Ripple Bounce */
    .status-item input[value="present"]:checked + .status-btn { 
        background: #10b981; 
        color: white; 
        border-color: #059669; 
        box-shadow: 0 4px 14px rgba(16, 185, 129, 0.45); 
        animation: btnSelectPulse 0.25s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .status-item input[value="sick"]:checked + .status-btn { 
        background: #f59e0b; 
        color: white; 
        border-color: #d97706; 
        box-shadow: 0 4px 14px rgba(245, 158, 11, 0.45); 
        animation: btnSelectPulse 0.25s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .status-item input[value="personal"]:checked + .status-btn { 
        background: #3b82f6; 
        color: white; 
        border-color: #2563eb; 
        box-shadow: 0 4px 14px rgba(59, 130, 246, 0.45); 
        animation: btnSelectPulse 0.25s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .status-item input[value="home"]:checked + .status-btn { 
        background: #ef4444; 
        color: white; 
        border-color: #dc2626; 
        box-shadow: 0 4px 14px rgba(239, 68, 68, 0.45); 
        animation: btnSelectPulse 0.25s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .status-item input[value="competition"]:checked + .status-btn { 
        background: #8b5cf6; 
        color: white; 
        border-color: #7c3aed; 
        box-shadow: 0 4px 14px rgba(139, 92, 246, 0.45); 
        animation: btnSelectPulse 0.25s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .status-item input[value="absent"]:checked + .status-btn { 
        background: #64748b; 
        color: white; 
        border-color: #475569; 
        box-shadow: 0 4px 14px rgba(100, 116, 139, 0.45); 
        animation: btnSelectPulse 0.25s cubic-bezier(0.4, 0, 0.2, 1);
    }

    @keyframes btnSelectPulse {
        0% { transform: scale(0.92); }
        50% { transform: scale(1.06); }
        100% { transform: scale(1); }
    }

    /* Card Layout for Mobile */
    .mobile-athlete-card {
        background: white;
        border-radius: 20px;
        padding: 1.25rem;
        margin-bottom: 1rem;
        border: 1px solid #edf2f7;
        box-shadow: 0 4px 12px rgba(0,0,0,0.03);
    }

    .athlete-info {
        display: flex;
        align-items: center;
        margin-bottom: 1rem;
    }

    .athlete-avatar-box {
        width: 50px;
        height: 50px;
        border-radius: 14px;
        background: #f8fafc;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 12px;
        font-size: 1.4rem;
        color: var(--sb-orange);
    }

    .sb-card {
        border: none;
        border-radius: 15px;
        box-shadow: 0 5px 20px rgba(0,0,0,0.05);
    }

    .sb-table thead th {
        background: #f8fafc;
        color: #64748b;
        font-size: 0.7rem;
        font-weight: 800;
        text-transform: uppercase;
        padding: 1.25rem 1rem;
        border: none;
    }

    .date-card {
        background: rgba(255, 255, 255, 0.9);
        border-radius: 12px;
        padding: 10px;
    }

    /* Swal Popup Responsive */
    .swal2-popup {
        width: 92% !important;
        max-width: 460px !important;
        padding: 1.25rem 1rem !important;
        border-radius: 24px !important;
    }
    .swal2-container { z-index: 99999 !important; }

    /* Inline Calendar inside Swal - scoped */
    .swal-calendar-wrap .flatpickr-calendar {
        position: relative !important;
        top: auto !important;
        left: auto !important;
        transform: none !important;
        width: 100% !important;
        max-width: 100% !important;
        margin: 0 auto !important;
        box-shadow: none !important;
        border: 1px solid #e2e8f0 !important;
        border-radius: 16px !important;
    }

    .swal-calendar-wrap .flatpickr-months {
        padding: 6px 0 !important;
    }

    .swal-calendar-wrap .flatpickr-days,
    .swal-calendar-wrap .dayContainer {
        width: 100% !important;
        min-width: 100% !important;
        max-width: 100% !important;
    }

    .swal-calendar-wrap .flatpickr-day {
        max-width: 14.28% !important;
        height: 40px !important;
        line-height: 40px !important;
        font-size: 0.9rem !important;
        font-weight: 600 !important;
        border-radius: 10px !important;
    }

    .swal-calendar-wrap .flatpickr-day.selected,
    .swal-calendar-wrap .flatpickr-day.startRange,
    .swal-calendar-wrap .flatpickr-day.endRange {
        background: var(--sb-orange) !important;
        border-color: var(--sb-orange) !important;
    }
    .swal-calendar-wrap .flatpickr-day.inRange {
        background: rgba(249, 115, 22, 0.15) !important;
        border-color: rgba(249, 115, 22, 0.15) !important;
    }

    .quick-date-preset {
        border-radius: 10px;
        font-size: 0.75rem;
        font-weight: 700;
        padding: 0.4rem 0.65rem;
        transition: all 0.2s ease;
    }
    .quick-date-preset:hover, .quick-date-preset:focus {
        transform: translateY(-2px);
    }
    .quick-date-preset.active-preset {
        background: var(--sb-orange) !important;
        border-color: var(--sb-orange) !important;
        color: white !important;
    }

    @media (max-width: 768px) {
        .container-p-y {
            padding-top: 0.35rem !important;
            padding-bottom: 0.75rem !important;
        }

        /* SECTION 1: Attendance Header Card */
        .attendance-header {
            padding: 0.75rem 0.9rem !important;
            border-radius: 12px !important;
            margin-bottom: 0.45rem !important;
            text-align: left !important;
        }
        .attendance-header h2 {
            font-size: 1.05rem !important;
            margin-bottom: 0.15rem !important;
        }
        .attendance-header .breadcrumb {
            margin-bottom: 0.15rem !important;
            font-size: 0.68rem !important;
        }
        .attendance-header .date-card {
            padding: 0.35rem 0.6rem !important;
            margin-top: 0.35rem !important;
            border-radius: 8px !important;
        }
        .attendance-header .date-card label {
            font-size: 0.68rem !important;
            margin-bottom: 0.1rem !important;
        }
        .attendance-header .date-card input {
            font-size: 0.8rem !important;
            height: 30px !important;
        }

        /* SECTION 2: Period Selector Banner */
        .period-selector-card {
            margin-bottom: 0.45rem !important;
            border-radius: 12px !important;
        }
        .period-selector-card .card-body {
            padding: 0.4rem 0.5rem !important;
        }
        .period-tab-btn {
            padding: 0.35rem 0.45rem !important;
            border-radius: 8px !important;
        }
        .period-tab-btn span.fw-bold {
            font-size: 0.76rem !important;
        }
        .period-tab-btn .badge {
            font-size: 0.58rem !important;
            padding: 1px 4px !important;
        }

        /* SECTION 3: Status Alert Banner */
        #attendanceAlertBanner {
            margin-bottom: 0.45rem !important;
        }
        #attendanceAlertBanner .alert {
            padding: 0.45rem 0.65rem !important;
            margin-bottom: 0.45rem !important;
            border-radius: 12px !important;
        }
        #attendanceAlertBanner .avatar {
            width: 32px !important;
            height: 32px !important;
            min-width: 32px !important;
            font-size: 0.9rem !important;
        }
        #attendanceAlertBanner h6 {
            font-size: 0.82rem !important;
        }
        #attendanceAlertBanner small {
            font-size: 0.68rem !important;
        }

        /* SECTION 4: Dedicated Batch Action 1-Click Card */
        .batch-action-card {
            margin-bottom: 0.45rem !important;
            border-radius: 12px !important;
        }
        .batch-action-card .card-body {
            padding: 0.45rem 0.6rem !important;
        }
        .batch-action-card .d-flex {
            margin-bottom: 0.25rem !important;
        }
        .quick-mark-all {
            padding: 0.2rem 0.55rem !important;
            font-size: 0.72rem !important;
            border-radius: 20px !important;
        }

        /* SECTION 5: Main Card Header & Counters */
        .sb-card {
            border-radius: 12px !important;
        }
        .sb-card .card-header {
            padding: 0.45rem 0.65rem !important;
        }
        .sb-card .card-header h5 {
            font-size: 0.84rem !important;
        }
        .sb-card .card-header .badge {
            padding: 2px 6px !important;
            font-size: 0.65rem !important;
        }
        .sb-card .card-body {
            padding: 0.45rem !important;
        }

        /* Student Status Selector Buttons */
        .status-container {
            max-width: 100% !important;
            padding: 2px !important;
            border-radius: 8px !important;
        }
        .status-btn {
            height: 34px !important;
            font-size: 0.76rem !important;
            border-radius: 6px !important;
            margin: 1px !important;
        }

        /* Student Mobile Card Item */
        .mobile-athlete-card {
            padding: 0.5rem 0.65rem !important;
            margin-bottom: 0.4rem !important;
            border-radius: 12px !important;
        }
        .athlete-info {
            margin-bottom: 0.35rem !important;
        }
        .athlete-avatar-box {
            width: 36px !important;
            height: 46px !important;
            border-radius: 6px !important;
            margin-right: 8px !important;
        }

        /* SweetAlert Popup */
        .swal2-popup {
            padding: 0.85rem 0.65rem !important;
            border-radius: 16px !important;
        }
        .swal2-title {
            font-size: 1.05rem !important;
        }
    }
    /* Interactive Period Selection Buttons */
    .period-tab-btn {
        position: relative;
        border-radius: 14px !important;
        transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1) !important;
        border: 2px solid transparent !important;
        user-select: none;
        box-shadow: 0 3px 10px rgba(0,0,0,0.06);
        padding: 0.65rem 1rem !important;
    }
    
    .period-tab-btn:hover {
        transform: translateY(-3px) scale(1.02);
        box-shadow: 0 8px 22px rgba(249, 115, 22, 0.25) !important;
        border-color: #fd7e14 !important;
    }
    
    .period-tab-btn:active {
        transform: scale(0.95);
    }
    
    .period-tab-btn.active {
        background: linear-gradient(135deg, #fd7e14 0%, #ff9e43 100%) !important;
        color: white !important;
        box-shadow: 0 6px 20px rgba(253, 126, 20, 0.4) !important;
        border-color: #f97316 !important;
    }

    .period-tab-btn.inactive {
        background: #ffffff !important;
        color: #475569 !important;
        border: 2px solid #cbd5e1 !important;
    }
    .period-tab-btn.inactive:hover {
        background: #fff7ed !important;
        color: #c2410c !important;
        border-color: #fd7e14 !important;
    }
    @keyframes pulseIcon {
        0% { transform: scale(1); box-shadow: 0 0 0 0 rgba(249, 115, 22, 0.4); }
        70% { transform: scale(1.08); box-shadow: 0 0 0 10px rgba(249, 115, 22, 0); }
        100% { transform: scale(1); box-shadow: 0 0 0 0 rgba(249, 115, 22, 0); }
    }
    .animate-pulse {
        animation: pulseIcon 1.8s infinite;
    }
</style>

<div class="container-xxl flex-grow-1 container-p-y">
    <!-- Header Section (Moved to Top) -->
    <div class="attendance-header">
        <div class="row align-items-center">
            <div class="col-md-8">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-style2 mb-2">
                        <li class="breadcrumb-item"><a href="<?= base_url('Admin/Attendance'); ?>" class="text-white opacity-75">ระบบเช็กชื่อ & ติดตามสถานะ</a></li>
                        <li class="breadcrumb-item active text-white" aria-current="page"><?= $team['team_name']; ?></li>
                    </ol>
                </nav>
                <div class="d-flex align-items-center flex-wrap gap-2 mb-1">
                    <h2 class="text-white fw-bold mb-0 me-2"><?= $periods[$selectedPeriod]['label'] ?? 'เช็กชื่อนักเรียน' ?>: <?= $team['team_name']; ?></h2>
                    <span class="badge bg-white text-dark fw-bold rounded-pill px-3 py-1 shadow-sm">
                        <i class='bx <?= $periods[$selectedPeriod]['icon'] ?? 'bx-sun' ?> text-warning me-1'></i>
                        <?= $periods[$selectedPeriod]['label'] ?? 'เช็กเข้าแถวเช้า' ?> (<?= $periods[$selectedPeriod]['time'] ?? '' ?>)
                    </span>
                </div>
                <div class="mt-2 text-white-50 small">
                    <span class="me-3"><i class='bx bx-run me-1'></i> <?= $team['team_sport_type']; ?></span>
                    <span class="me-3"><i class='bx bx-group me-1'></i> ทั้งหมด <?= count($athletes); ?> คน</span>
                    <span><i class='bx bx-user-check me-1'></i> ผู้คุม/ผู้บันทึก: <strong class="text-white"><?= session()->get('username') ?></strong></span>
                </div>
            </div>
            <div class="col-md-4 mt-3 mt-md-0">
                <div class="date-card p-3">
                    <label class="form-label fw-bold text-dark small mb-1">เลือกวันที่เช็กชื่อ</label>
                    <div class="input-group input-group-merge">
                        <span class="input-group-text border-0 bg-light"><i class='bx bx-calendar-event'></i></span>
                        <input type="text" id="attendanceDate" class="form-control border-0 bg-light fw-bold" value="<?= $selectedDate; ?>" data-base-url="<?= base_url('Admin/Attendance/Team/' . $team['team_id']); ?>" data-period="<?= $selectedPeriod; ?>">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Period Selector Banner (Interactive Buttons with Status Badges) -->
    <div class="card period-selector-card border-0 shadow-sm rounded-20 mb-3 overflow-hidden" style="background: #f8fafc; border: 1px solid #e2e8f0 !important;">
        <div class="card-body p-2.5">
            <div class="text-muted small fw-bold mb-2 px-1 d-flex align-items-center justify-content-between period-selector-title">
                <div class="d-flex align-items-center gap-1">
                    <i class='bx bx-pointer text-warning fs-5'></i>
                    <span>แตะเลือกช่วงเวลาที่ต้องการเช็กชื่อ:</span>
                </div>
            </div>
            <div class="row g-2 align-items-center">
                <div class="col-12">
                    <?php $curPeriod = $selectedPeriod ?? 'morning'; ?>
                    <!-- Period Action Buttons -->
                    <div class="nav nav-pills nav-justified gap-2" id="periodTabs" role="tablist">
                        <?php 
                        $totalAthletes = count($athletes);
                        foreach ($periods as $pKey => $pInfo): 
                            $isActive = ($curPeriod === $pKey);
                            $activeClass = $isActive ? 'active' : 'inactive';
                            $pStatus = $periodStatusMap[$pKey] ?? ['checked' => false, 'count' => 0, 'is_complete' => false];
                            $pCount = $pStatus['count'] ?? 0;
                            $isComplete = !empty($pStatus['is_complete']);
                        ?>
                            <a class="nav-link fw-bold d-flex align-items-center justify-content-center gap-2 period-tab-btn <?= $activeClass ?>"
                               href="<?= base_url('Admin/Attendance/Team/' . $team['team_id'] . '?date=' . $selectedDate . '&period=' . $pKey); ?>">
                                <i class='bx <?= $pInfo['icon'] ?> fs-5'></i>
                                <span class="fw-bold" style="font-size: 0.9rem; white-space: nowrap;"><?= $pInfo['label'] ?></span>
                                <?php if ($isComplete): ?>
                                    <span class="badge bg-success rounded-pill px-2 py-0.5 fw-bold ms-1" style="font-size: 0.65rem;">✓ บันทึกครบแล้ว</span>
                                <?php elseif ($pCount > 0): ?>
                                    <span class="badge bg-warning text-dark rounded-pill px-2 py-0.5 fw-bold ms-1" style="font-size: 0.65rem;">! เช็กแล้ว <?= $pCount ?>/<?= $totalAthletes ?></span>
                                <?php else: ?>
                                    <span class="badge bg-danger rounded-pill px-2 py-0.5 fw-bold ms-1" style="font-size: 0.65rem;">! ยังไม่เช็ก</span>
                                <?php endif; ?>
                            </a>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- PROMINENT DAILY CHECK-IN STATUS ALERT BANNER -->
    <?php 
    $curStatusInfo = $periodStatusMap[$selectedPeriod] ?? ['checked' => false, 'by' => '', 'time' => '', 'count' => 0, 'is_complete' => false];
    $checkedCount = 0;
    foreach ($athletes as $a) {
        if (isset($attendanceMap[$a['StudentID']]) && !empty($attendanceMap[$a['StudentID']]['att_status'])) {
            $checkedCount++;
        }
    }
    $totalCount = count($athletes);
    $remainingCount = $totalCount - $checkedCount;
    $isFullyChecked = ($totalCount > 0 && $checkedCount >= $totalCount);
    ?>

    <div id="attendanceAlertBanner">
        <?php if ($isFullyChecked): ?>
            <div class="alert alert-success d-flex align-items-center justify-content-between rounded-16 shadow-sm border-0 mb-3 p-3" style="background: #ecfdf5; border-left: 6px solid #10b981 !important;">
                <div class="d-flex align-items-center gap-3">
                    <div class="avatar avatar-md bg-success text-white rounded-circle d-flex align-items-center justify-content-center">
                        <i class="bx bx-check-circle fs-3"></i>
                    </div>
                    <div>
                        <h6 class="fw-bold text-dark mb-0 d-flex align-items-center gap-2 flex-wrap">
                            <span>✅ บันทึกการเช็กชื่อครบทุกคนเรียบร้อยแล้ว (<?= $checkedCount ?>/<?= $totalCount ?> คน)</span>
                            <span class="badge bg-success text-white">ประจำวันที่ <?= date('d/m/', strtotime($selectedDate)) . (date('Y', strtotime($selectedDate)) + 543) ?></span>
                        </h6>
                        <small class="text-muted d-block mt-1">
                            <i class="bx bx-user me-1"></i>ผู้ลงเวลาบันทึก: <strong class="text-dark"><?= !empty($curStatusInfo['by']) ? $curStatusInfo['by'] : session()->get('username') ?></strong>
                            <?php if (!empty($curStatusInfo['time'])): ?>
                                <span class="ms-2"><i class="bx bx-time me-1"></i>เวลา: <?= date('H:i', strtotime($curStatusInfo['time'])) ?> น.</span>
                            <?php endif; ?>
                        </small>
                    </div>
                </div>
                <span class="badge bg-label-success fw-bold px-3 py-2 rounded-pill fs-7 d-none d-md-inline-block">
                    <i class="bx bx-shield-quarter me-1"></i>เช็กชื่อครบแล้ว
                </span>
            </div>
        <?php elseif ($checkedCount > 0): ?>
            <div class="alert alert-warning d-flex align-items-center justify-content-between rounded-16 shadow-sm border-0 mb-3 p-3" style="background: #fffbe6; border-left: 6px solid #f59e0b !important;">
                <div class="d-flex align-items-center gap-3">
                    <div class="avatar avatar-md bg-warning text-white rounded-circle d-flex align-items-center justify-content-center animate-pulse">
                        <i class="bx bx-error-circle fs-3"></i>
                    </div>
                    <div>
                        <h6 class="fw-bold text-dark mb-0 d-flex align-items-center gap-2 flex-wrap">
                            <span class="fw-bold" style="color: #b45309;">⚠️ ยังเช็คชื่อไม่ครบทุกคนในทีม! (เช็กแล้ว <?= $checkedCount ?>/<?= $totalCount ?> คน)</span>
                            <span class="badge bg-warning text-dark fw-bold">เหลืออีก <?= $remainingCount ?> คนยังไม่ได้เลือก</span>
                        </h6>
                        <small class="text-muted d-block mt-1">
                            กรุณาเลือกสถานะให้นักกีฬาที่เหลืออีก <strong><?= $remainingCount ?> คน</strong> สำหรับช่วงเวลา <strong><?= $periods[$selectedPeriod]['label'] ?? '' ?></strong> (ประจำวันที่ <?= date('d/m/', strtotime($selectedDate)) . (date('Y', strtotime($selectedDate)) + 543) ?>)
                        </small>
                    </div>
                </div>
                <span class="badge bg-warning text-dark fw-bold px-3 py-2 rounded-pill fs-7 d-none d-md-inline-block">
                    <i class="bx bx-time-five me-1"></i>ยังเช็กไม่ครบ
                </span>
            </div>
        <?php else: ?>
            <div class="alert alert-danger d-flex align-items-center justify-content-between rounded-16 shadow-sm border-0 mb-3 p-3 animate-pulse-border" style="background: #fff5f5; border-left: 6px solid #ef4444 !important;">
                <div class="d-flex align-items-center gap-3">
                    <div class="avatar avatar-md bg-danger text-white rounded-circle d-flex align-items-center justify-content-center animate-pulse">
                        <i class="bx bx-x-circle fs-3"></i>
                    </div>
                    <div>
                        <h6 class="fw-bold text-dark mb-0 d-flex align-items-center gap-2 flex-wrap">
                            <span class="text-danger fw-bold">⚠️ ยังไม่ได้ลงเวลาเช็กชื่อช่วงเวลานี้!</span>
                            <span class="badge bg-danger text-white animate-pulse">ยังไม่เช็กชื่อ (0/<?= $totalCount ?> คน)</span>
                        </h6>
                        <small class="text-muted d-block mt-1">
                            กรุณาลงเวลาเช็กชื่อสำหรับช่วงเวลา <strong><?= $periods[$selectedPeriod]['label'] ?? '' ?></strong> (ประจำวันที่ <?= date('d/m/', strtotime($selectedDate)) . (date('Y', strtotime($selectedDate)) + 543) ?>)
                        </small>
                    </div>
                </div>
                <span class="badge bg-danger text-white fw-bold px-3 py-2 rounded-pill fs-7 d-none d-md-inline-block animate-pulse">
                    <i class="bx bx-time-five me-1"></i>รอดำเนินการ
                </span>
            </div>
        <?php endif; ?>
    </div>

    <!-- Dedicated Batch Action Card (แยกส่วนทางลัดเลือกสถานะทั้งทีม) -->
    <div class="card batch-action-card border-0 shadow-sm rounded-20 mb-3 overflow-hidden" style="background: #ffffff; border: 1px solid #fed7aa !important;">
        <div class="card-body p-3">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-2.5">
                <div class="d-flex align-items-center gap-2">
                    <span class="badge bg-warning text-white rounded-circle p-1.5 d-flex align-items-center justify-content-center">
                        <i class='bx bx-bolt fs-5'></i>
                    </span>
                    <span class="fw-bold text-dark fs-6">⚡ ทางลัดเลือกสถานะเดียวกันทั้งทีม (Batch Action 1-Click)</span>
                </div>
                <span class="text-muted small"><i class='bx bx-info-circle me-1'></i>แตะเลือกสถานะเพื่อกำหนดให้ทุกคนในทีมพร้อมกัน</span>
            </div>
            
            <div class="batch-scroll-container">
                <button type="button" class="btn btn-outline-success btn-sm rounded-pill fw-bold px-3 py-2 quick-mark-all d-inline-flex align-items-center gap-1 shadow-sm flex-shrink-0" data-status="present">
                    <i class='bx bx-check-circle fs-5'></i> "มา" ทั้งทีม
                </button>
                <button type="button" class="btn btn-outline-secondary btn-sm rounded-pill fw-bold px-3 py-2 quick-mark-all d-inline-flex align-items-center gap-1 shadow-sm flex-shrink-0" data-status="absent">
                    <i class='bx bx-x-circle fs-5'></i> "ไม่มา" ทั้งทีม
                </button>
                <button type="button" class="btn btn-outline-warning btn-sm rounded-pill fw-bold px-3 py-2 quick-mark-all d-inline-flex align-items-center gap-1 shadow-sm text-dark flex-shrink-0" data-status="sick">
                    <i class='bx bx-plus-medical fs-5'></i> "ลาป่วย" ทั้งทีม
                </button>
                <button type="button" class="btn btn-outline-info btn-sm rounded-pill fw-bold px-3 py-2 quick-mark-all d-inline-flex align-items-center gap-1 shadow-sm flex-shrink-0" data-status="personal">
                    <i class='bx bx-briefcase fs-5'></i> "ลากิจ" ทั้งทีม
                </button>
                <button type="button" class="btn btn-outline-danger btn-sm rounded-pill fw-bold px-3 py-2 quick-mark-all d-inline-flex align-items-center gap-1 shadow-sm flex-shrink-0" data-status="home">
                    <i class='bx bx-home fs-5'></i> "กลับบ้าน" ทั้งทีม
                </button>
                <button type="button" class="btn btn-outline-primary btn-sm rounded-pill fw-bold px-3 py-2 quick-mark-all d-inline-flex align-items-center gap-1 shadow-sm flex-shrink-0" data-status="competition">
                    <i class='bx bx-trophy fs-5'></i> "ไปแข่งขัน" ทั้งทีม
                </button>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-12">
            <div class="card sb-card shadow-sm">
                <div class="card-header bg-white border-bottom py-3 d-flex flex-wrap justify-content-between align-items-center gap-3">
                    <h5 class="mb-0 fw-bold text-heading d-flex align-items-center gap-2">
                        <i class='bx <?= $periods[$selectedPeriod]['icon'] ?? 'bx-sun' ?> text-warning fs-4'></i>
                        <span>ระบบลงเวลา: <?= $periods[$selectedPeriod]['label'] ?? 'เข้าแถวเช้า' ?></span>
                    </h5>
                    <div class="d-flex gap-2 align-items-center flex-wrap">
                        <div class="badge bg-label-success rounded-pill px-3 py-2 fw-bold" id="presentBadge">0 มา</div>
                        <div class="badge rounded-pill px-3 py-2 fw-bold" id="absentBadge" style="background: rgba(100, 116, 139, 0.16); color: #475569;">0 ไม่มา</div>
                        <div class="badge bg-label-warning rounded-pill px-3 py-2 fw-bold" id="sickBadge">0 ลาป่วย</div>
                        <div class="badge bg-label-info rounded-pill px-3 py-2 fw-bold" id="personalBadge">0 ลากิจ</div>
                        <div class="badge bg-label-danger rounded-pill px-3 py-2 fw-bold" id="homeBadge">0 กลับบ้าน</div>
                        <div class="badge rounded-pill px-3 py-2 fw-bold" id="competitionBadge" style="background: rgba(139, 92, 246, 0.16); color: #8b5cf6;">0 แข่งขัน</div>
                    </div>
                </div>
                
                <!-- Desktop View -->
                <div class="table-responsive d-none d-md-block">
                    <table class="table sb-table athlete-table mb-0">
                        <thead>
                            <tr>
                                <th width="50" class="text-center">#</th>
                                <th width="80" class="text-center">รูป</th>
                                <th>ชื่อ - นามสกุล / ชั้นเรียน</th>
                                <th width="450">บันทึกสถานะ</th>
                                <th width="150" class="text-center">หมายเหตุ</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($athletes)) : ?>
                                <?php $i = 1; foreach ($athletes as $row) : 
                                    $currentStatus = isset($attendanceMap[$row['StudentID']]) ? $attendanceMap[$row['StudentID']]['att_status'] : '';
                                    $currentNote = isset($attendanceMap[$row['StudentID']]) ? $attendanceMap[$row['StudentID']]['att_note'] : '';
                                    
                                    // Photo Logic
                                    if (!empty($row['athlete_image'])) {
                                        $photoUrl = base_url('uploads/athletes/' . $row['athlete_image']);
                                    } else {
                                        $photoUrl = "https://skj.ac.th/uploads/students_photo/{$row['StudentCode']}.jpg";
                                    }
                                    $fallbackUrl = 'https://ui-avatars.com/api/?name=' . urlencode($row['StudentFirstName'] . ' ' . $row['StudentLastName']) . '&background=random&size=100';
                                ?>
                                    <tr class="athlete-row" data-student-id="<?= $row['StudentID']; ?>">
                                        <td class="text-center fw-bold text-muted"><?= $i++; ?></td>
                                        <td class="text-center">
                                            <img src="<?= $photoUrl ?>" onerror="this.src='<?= $fallbackUrl ?>'" class="athlete-avatar" style="width: 45px; height: 60px; object-fit: cover; border-radius: 8px;">
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center gap-2 flex-wrap mb-0.5">
                                                <span class="fw-bold text-dark fs-6"><?= $row['StudentPrefix'] . $row['StudentFirstName'] . ' ' . $row['StudentLastName']; ?></span>
                                                <span class="badge bg-label-primary rounded-pill fw-bold" style="font-size: 0.75rem;"><?= $row['StudentClass']; ?></span>
                                            </div>
                                            <small class="text-muted">#<?= $row['StudentCode']; ?></small>
                                            <?php if (isset($attendanceMap[$row['StudentID']]) && !empty($currentStatus) && $currentStatus !== 'present'): 
                                                $att = $attendanceMap[$row['StudentID']];
                                                $sDate = !empty($att['att_start_date']) ? $att['att_start_date'] : $selectedDate;
                                                $eDate = !empty($att['att_end_date']) ? $att['att_end_date'] : $sDate;
                                                $diffDays = (int)((strtotime($eDate) - strtotime($sDate)) / 86400) + 1;
                                                $stLabel = isset($statuses[$currentStatus]) ? $statuses[$currentStatus]['label'] : 'ลา';
                                                $stColor = isset($statuses[$currentStatus]) ? $statuses[$currentStatus]['color'] : 'warning';
                                                $sText = date('d/m/', strtotime($sDate)) . (date('Y', strtotime($sDate)) + 543);
                                                $eText = date('d/m/', strtotime($eDate)) . (date('Y', strtotime($eDate)) + 543);
                                                $rangeStr = ($sDate === $eDate) ? $sText : ($sText . ' - ' . $eText);
                                            ?>
                                                <div class="mt-1 leave-badge-info">
                                                    <span class="badge bg-label-<?= $stColor ?> fw-bold" style="font-size: 0.72rem;">
                                                        <i class="bx bx-calendar-event me-1"></i><?= $stLabel ?> <?= $diffDays ?> วัน (<?= $rangeStr ?>)
                                                    </span>
                                                </div>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <div class="status-container">
                                                <div class="status-item">
                                                    <input type="radio" class="status-btn-radio" name="st_<?= $row['StudentID']; ?>" id="p_<?= $row['StudentID']; ?>" value="present" <?= ($currentStatus == 'present') ? 'checked' : ''; ?>>
                                                    <label class="status-btn" for="p_<?= $row['StudentID']; ?>">มา</label>
                                                </div>
                                                <div class="status-item">
                                                    <input type="radio" class="status-btn-radio" name="st_<?= $row['StudentID']; ?>" id="ab_<?= $row['StudentID']; ?>" value="absent" <?= $currentStatus == 'absent' ? 'checked' : ''; ?>>
                                                    <label class="status-btn" for="ab_<?= $row['StudentID']; ?>">ไม่มา</label>
                                                </div>
                                                <div class="status-item">
                                                    <input type="radio" class="status-btn-radio" name="st_<?= $row['StudentID']; ?>" id="s_<?= $row['StudentID']; ?>" value="sick" <?= $currentStatus == 'sick' ? 'checked' : ''; ?>>
                                                    <label class="status-btn" for="s_<?= $row['StudentID']; ?>">ลาป่วย</label>
                                                </div>
                                                <div class="status-item">
                                                    <input type="radio" class="status-btn-radio" name="st_<?= $row['StudentID']; ?>" id="pe_<?= $row['StudentID']; ?>" value="personal" <?= $currentStatus == 'personal' ? 'checked' : ''; ?>>
                                                    <label class="status-btn" for="pe_<?= $row['StudentID']; ?>">ลากิจ</label>
                                                </div>
                                                <div class="status-item">
                                                    <input type="radio" class="status-btn-radio" name="st_<?= $row['StudentID']; ?>" id="h_<?= $row['StudentID']; ?>" value="home" <?= $currentStatus == 'home' ? 'checked' : ''; ?>>
                                                    <label class="status-btn" for="h_<?= $row['StudentID']; ?>">กลับบ้าน</label>
                                                </div>
                                                <div class="status-item">
                                                    <input type="radio" class="status-btn-radio" name="st_<?= $row['StudentID']; ?>" id="c_<?= $row['StudentID']; ?>" value="competition" <?= $currentStatus == 'competition' ? 'checked' : ''; ?>>
                                                    <label class="status-btn" for="c_<?= $row['StudentID']; ?>">แข่งขัน</label>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <span class="text-muted small"><?= $currentNote ?: '-'; ?></span>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <!-- Mobile View -->
                <div class="card-body p-3 d-md-none">
                    <?php if (empty($athletes)) : ?>
                        <div class="text-center py-5">
                            <i class="bx bx-user-x fs-1 text-muted opacity-25"></i>
                            <p class="text-muted mt-2">ไม่พบรายชื่อนักกีฬา</p>
                        </div>
                    <?php else : ?>
                        <?php foreach ($athletes as $row) : 
                            $currentStatus = isset($attendanceMap[$row['StudentID']]) ? $attendanceMap[$row['StudentID']]['att_status'] : '';
                            $currentNote = isset($attendanceMap[$row['StudentID']]) ? $attendanceMap[$row['StudentID']]['att_note'] : '';
                            
                            // Photo Logic
                            if (!empty($row['athlete_image'])) {
                                $photoUrl = base_url('uploads/athletes/' . $row['athlete_image']);
                            } else {
                                $photoUrl = "https://skj.ac.th/uploads/students_photo/{$row['StudentCode']}.jpg";
                            }
                            $fallbackUrl = 'https://ui-avatars.com/api/?name=' . urlencode($row['StudentFirstName'] . ' ' . $row['StudentLastName']) . '&background=random&size=100';
                        ?>
                            <div class="mobile-athlete-card athlete-row" data-student-id="<?= $row['StudentID']; ?>">
                                <div class="athlete-info">
                                    <div class="athlete-avatar-box p-0 overflow-hidden">
                                        <img src="<?= $photoUrl ?>" onerror="this.src='<?= $fallbackUrl ?>'" style="width: 100%; height: 100%; object-fit: cover;">
                                    </div>
                                    <div class="flex-grow-1 overflow-hidden">
                                        <div class="d-flex align-items-center justify-content-between">
                                            <span class="fw-bold text-dark lh-1 text-truncate" style="font-size: 0.9rem;"><?= $row['StudentPrefix'] . $row['StudentFirstName'] . ' ' . $row['StudentLastName']; ?></span>
                                            <span class="badge bg-label-primary rounded-pill px-2 py-0.5 fw-bold flex-shrink-0 ms-1" style="font-size: 0.68rem;"><?= $row['StudentClass']; ?></span>
                                        </div>
                                        <div class="text-muted" style="font-size: 0.72rem; line-height: 1.2; margin-top: 2px;">
                                            <span>#<?= $row['StudentCode']; ?></span>
                                        </div>
                                        <?php if (isset($attendanceMap[$row['StudentID']]) && !empty($currentStatus) && $currentStatus !== 'present'): 
                                            $att = $attendanceMap[$row['StudentID']];
                                            $sDate = !empty($att['att_start_date']) ? $att['att_start_date'] : $selectedDate;
                                            $eDate = !empty($att['att_end_date']) ? $att['att_end_date'] : $sDate;
                                            $diffDays = (int)((strtotime($eDate) - strtotime($sDate)) / 86400) + 1;
                                            $stLabel = isset($statuses[$currentStatus]) ? $statuses[$currentStatus]['label'] : 'ลา';
                                            $stColor = isset($statuses[$currentStatus]) ? $statuses[$currentStatus]['color'] : 'warning';
                                            $sText = date('d/m/', strtotime($sDate)) . (date('Y', strtotime($sDate)) + 543);
                                            $eText = date('d/m/', strtotime($eDate)) . (date('Y', strtotime($eDate)) + 543);
                                            $rangeStr = ($sDate === $eDate) ? $sText : ($sText . ' - ' . $eText);
                                        ?>
                                            <div class="mt-1 leave-badge-info">
                                                <span class="badge bg-label-<?= $stColor ?> fw-bold" style="font-size: 0.68rem; padding: 2px 6px;">
                                                    <i class="bx bx-calendar-event me-0.5"></i><?= $stLabel ?> <?= $diffDays ?> วัน (<?= $rangeStr ?>)
                                                </span>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <div class="status-container mx-auto">
                                    <div class="status-item">
                                        <input type="radio" class="status-btn-radio" name="mst_<?= $row['StudentID']; ?>" id="mp_<?= $row['StudentID']; ?>" value="present" <?= ($currentStatus == 'present') ? 'checked' : ''; ?>>
                                        <label class="status-btn" for="mp_<?= $row['StudentID']; ?>">มา</label>
                                    </div>
                                    <div class="status-item">
                                        <input type="radio" class="status-btn-radio" name="mst_<?= $row['StudentID']; ?>" id="mab_<?= $row['StudentID']; ?>" value="absent" <?= $currentStatus == 'absent' ? 'checked' : ''; ?>>
                                        <label class="status-btn" for="mab_<?= $row['StudentID']; ?>">ไม่มา</label>
                                    </div>
                                    <div class="status-item">
                                        <input type="radio" class="status-btn-radio" name="mst_<?= $row['StudentID']; ?>" id="ms_<?= $row['StudentID']; ?>" value="sick" <?= $currentStatus == 'sick' ? 'checked' : ''; ?>>
                                        <label class="status-btn" for="ms_<?= $row['StudentID']; ?>">ป่วย</label>
                                    </div>
                                    <div class="status-item">
                                        <input type="radio" class="status-btn-radio" name="mst_<?= $row['StudentID']; ?>" id="mpe_<?= $row['StudentID']; ?>" value="personal" <?= $currentStatus == 'personal' ? 'checked' : ''; ?>>
                                        <label class="status-btn" for="mpe_<?= $row['StudentID']; ?>">ลากิจ</label>
                                    </div>
                                    <div class="status-item">
                                        <input type="radio" class="status-btn-radio" name="mst_<?= $row['StudentID']; ?>" id="mh_<?= $row['StudentID']; ?>" value="home" <?= $currentStatus == 'home' ? 'checked' : ''; ?>>
                                        <label class="status-btn" for="mh_<?= $row['StudentID']; ?>">กลับ</label>
                                    </div>
                                    <div class="status-item">
                                        <input type="radio" class="status-btn-radio" name="mst_<?= $row['StudentID']; ?>" id="mc_<?= $row['StudentID']; ?>" value="competition" <?= $currentStatus == 'competition' ? 'checked' : ''; ?>>
                                        <label class="status-btn" for="mc_<?= $row['StudentID']; ?>">แข่ง</label>
                                    </div>
                                </div>
                                <?php if (!empty($currentNote)): ?>
                                    <div class="mt-2 small text-muted text-center italic">
                                        <i class='bx bx-note me-1'></i> <?= $currentNote; ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('script') ?>
<!-- Flatpickr Thai Locale -->
<script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/th.js"></script>

<script>
$(document).ready(function() {
    const teamId = <?= $team['team_id']; ?>;
    
    // Mobile Period Dropdown Change Listener
    $('#mobilePeriodSelect').on('change', function() {
        const period = $(this).val();
        const dateStr = $('#attendanceDate').val();
        window.location.href = '<?= base_url('Admin/Attendance/Team/' . $team['team_id']); ?>?date=' + dateStr + '&period=' + period;
    });

    // Quick Batch Mark All Handler for Any Status (กลับบ้าน / แข่งขัน / มา / ลาป่วย / ลากิจ)
    $(document).on('click', '.quick-mark-all', function() {
        const targetStatus = $(this).data('status') || 'present';
        const currentDateStr = $('#attendanceDate').val() || new Date().toISOString().split('T')[0];
        const period = $('#attendanceDate').data('period') || 'morning';

        const statusLabels = {
            'present': 'มาซ้อม / มาเรียน',
            'sick': 'ลาป่วย',
            'personal': 'ลากิจธุระ',
            'home': 'ลากิจกลับบ้าน',
            'competition': 'ไปแข่งขัน',
            'absent': 'ขาดซ้อม / ขาดเรียน'
        };
        const statusLabel = statusLabels[targetStatus] || targetStatus;

        if (targetStatus === 'present' || targetStatus === 'absent') {
            Swal.fire({
                title: `ยืนยันบันทึก "${statusLabel}" ทั้งทีม?`,
                text: `ระบบจะกำหนดสถานะนักเรียนทุกคนในทีมเป็น "${statusLabel}"`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: '<i class="bx bx-check-double me-1"></i> ยืนยันบันทึก',
                cancelButtonText: 'ยกเลิก',
                confirmButtonColor: targetStatus === 'present' ? '#10b981' : '#64748b'
            }).then((res) => {
                if (res.isConfirmed) {
                    saveBatchAttendance(targetStatus, currentDateStr, currentDateStr, '');
                }
            });
            return;
        }

        // สำหรับสถานะอื่นๆ (กลับบ้าน, แข่งขัน, ลาป่วย, ลากิจ) ให้เปิดป๊อปอัปเลือกช่วงวันที่ (วันไป - วันกลับ)
        let popupTitle = `บันทึก "${statusLabel}" ทั้งทีม`;
        let dateLabel = 'ระบุช่วงวันที่ (วันไป - วันกลับ)';
        
        Swal.fire({
            title: popupTitle,
            html: `
                <div class="text-start mb-2">
                    <div class="d-flex align-items-center gap-3 mb-3 p-2.5 rounded-3" style="background: #fff7ed; border: 1px solid #ffedd5;">
                        <i class="bx bx-group fs-2 text-warning"></i>
                        <div>
                            <div class="fw-bold text-dark" style="font-size: 1rem;"><?= $team['team_name'] ?> (${<?= count($athletes) ?>} คน)</div>
                            <span class="badge bg-warning text-white fs-6">${statusLabel} ทั้งทีม</span>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold text-dark mb-1">${dateLabel}</label>
                        
                        <!-- Quick Preset Buttons -->
                        <div class="d-flex gap-1 mb-2 flex-wrap" id="swalPresetBatchButtons">
                            <button type="button" class="btn btn-sm btn-outline-primary quick-date-preset-batch active-preset" data-days="1">⚡ วันนี้</button>
                            <button type="button" class="btn btn-sm btn-outline-primary quick-date-preset-batch" data-days="2">⚡ 2 วัน</button>
                            <button type="button" class="btn btn-sm btn-outline-primary quick-date-preset-batch" data-days="3">⚡ 3 วัน</button>
                            <button type="button" class="btn btn-sm btn-outline-primary quick-date-preset-batch" data-days="7">⚡ 1 สัปดาห์</button>
                        </div>

                        <!-- Inline Calendar Container -->
                        <div class="swal-calendar-wrap" id="swalCalendarContainerBatch"></div>
                        <small class="text-muted d-block mt-1 text-center" style="font-size: 0.75rem;"><i class="bx bx-info-circle me-1"></i> แตะ 1 ครั้ง = เลือก 1 วัน, แตะ 2 ครั้ง = เลือกช่วงวันที่ไป-กลับ</small>
                    </div>
                    
                    <div class="mb-2">
                        <label class="form-label fw-bold text-dark mb-1">หมายเหตุ</label>
                        <input type="text" id="swal-note-batch" class="form-control" placeholder="ระบุเหตุผล (ถ้ามี)...">
                    </div>
                </div>
            `,
            didOpen: () => {
                const baseDate = new Date(currentDateStr);

                window.batchRangePicker = flatpickr("#swalCalendarContainerBatch", {
                    mode: "range",
                    locale: "th",
                    dateFormat: "Y-m-d",
                    defaultDate: [baseDate],
                    disableMobile: true,
                    inline: true,
                    onChange: function(selectedDates, dateStr, instance) {
                        window.selectedBatchRangeDates = selectedDates;
                        setBuddhistYear(instance);
                    },
                    onReady: function(selectedDates, dateStr, instance) {
                        setBuddhistYear(instance);
                    },
                    onMonthChange: function(selectedDates, dateStr, instance) {
                        setBuddhistYear(instance);
                    },
                    onYearChange: function(selectedDates, dateStr, instance) {
                        setBuddhistYear(instance);
                    }
                });

                function setBuddhistYear(instance) {
                    if (!instance.calendarContainer) return;
                    const buddhistYear = instance.currentYear + 543;
                    const yearInput = instance.calendarContainer.querySelector('input.cur-year');
                    if (yearInput) {
                        yearInput.value = buddhistYear;
                        return;
                    }
                    const yearSpan = instance.calendarContainer.querySelector('.cur-year');
                    if (yearSpan) yearSpan.textContent = buddhistYear;
                }
                window.selectedBatchRangeDates = [baseDate];

                // Quick Preset Click Handler for Batch
                $(document).off('click', '.quick-date-preset-batch').on('click', '.quick-date-preset-batch', function() {
                    const days = parseInt($(this).data('days')) || 1;
                    const startDate = new Date(currentDateStr);
                    const endDate = new Date(currentDateStr);
                    endDate.setDate(startDate.getDate() + (days - 1));

                    const picker = Array.isArray(window.batchRangePicker) ? window.batchRangePicker[0] : window.batchRangePicker;
                    picker.setDate([startDate, endDate], true);
                    window.selectedBatchRangeDates = [startDate, endDate];
                    
                    $('.quick-date-preset-batch').removeClass('btn-primary text-white').addClass('btn-outline-primary');
                    $(this).removeClass('btn-outline-primary').addClass('btn-primary text-white');
                });
            },
            showCancelButton: true,
            confirmButtonText: '<i class="bx bx-save me-1"></i> ยืนยันบันทึกทั้งทีม',
            cancelButtonText: 'ยกเลิก',
            confirmButtonColor: '#fd7e14',
            allowOutsideClick: false,
            preConfirm: () => {
                const selectedDates = window.selectedBatchRangeDates || [];
                const note = $('#swal-note-batch').val();
                
                if (!selectedDates || selectedDates.length === 0) {
                    Swal.showValidationMessage('กรุณาเลือกช่วงวันที่ (วันไป - วันกลับ)');
                    return false;
                }
                
                let startDate, endDate;
                if (typeof selectedDates[0] === 'string') {
                    startDate = selectedDates[0];
                    endDate = selectedDates[1] || selectedDates[0];
                } else {
                    startDate = selectedDates[0].toISOString().split('T')[0];
                    endDate = selectedDates[1] ? selectedDates[1].toISOString().split('T')[0] : startDate;
                }
                
                return {
                    status: targetStatus,
                    startDate: startDate,
                    endDate: endDate,
                    note: note
                };
            }
        }).then((result) => {
            if (result.isConfirmed) {
                saveBatchAttendance(result.value.status, result.value.startDate, result.value.endDate, result.value.note);
            }
        });
    });

    function saveBatchAttendance(status, startDate, endDate, note) {
        const period = $('#attendanceDate').data('period') || 'morning';
        $.ajax({
            url: '<?= base_url('Admin/Attendance/MarkAllStatus'); ?>',
            method: 'POST',
            data: {
                team_id: teamId,
                date: startDate,
                end_date: endDate,
                period: period,
                status: status,
                note: note
            },
            success: function(r) {
                if (r.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'สำเร็จ',
                        text: r.message,
                        timer: 1500,
                        showConfirmButton: false
                    }).then(() => {
                        location.reload();
                    });
                }
            },
            error: function() {
                Swal.fire('Error', 'ไม่สามารถบันทึกข้อมูลได้', 'error');
            }
        });
    }
    
    // Helper: แปลงปีค.ศ. ในข้อความ altInput เป็น พ.ศ.
    function toBuddhistYearText(text) {
        return text.replace(/(\d{4})/, function(match) {
            return parseInt(match) + 543;
        });
    }

    // Flatpickr setup with Thai Locale and Buddhist Year
    flatpickr("#attendanceDate", {
        locale: "th",
        dateFormat: "Y-m-d",
        altInput: true,
        altFormat: "j F Y",
        disableMobile: true,
        onChange: function(selectedDates, dateStr) {
            const period = $('#attendanceDate').data('period') || 'morning';
            window.location.href = '<?= base_url('Admin/Attendance/Team/' . $team['team_id']); ?>?date=' + dateStr + '&period=' + period;
        },
        onReady: function(selectedDates, dateStr, instance) {
            if (instance.calendarContainer) {
                const yr = instance.currentYear < 2500 ? instance.currentYear + 543 : instance.currentYear;
                const el = instance.calendarContainer.querySelector('input.cur-year') || instance.calendarContainer.querySelector('.cur-year') || instance.currentYearElement;
                if (el) {
                    if (el.tagName === 'INPUT') el.value = yr;
                    else el.textContent = yr;
                }
            }
            // แปลง altInput เป็น พ.ศ.
            if (instance.altInput) {
                instance.altInput.value = toBuddhistYearText(instance.altInput.value);
            }
        },
        onValueUpdate: function(selectedDates, dateStr, instance) {
            // แปลง altInput เป็น พ.ศ. ทุกครั้งที่ค่าเปลี่ยน
            if (instance.altInput) {
                setTimeout(() => {
                    instance.altInput.value = toBuddhistYearText(instance.altInput.value);
                }, 0);
            }
        },
        onYearChange: function(selectedDates, dateStr, instance) {
            if (instance.calendarContainer) {
                const yr = instance.currentYear < 2500 ? instance.currentYear + 543 : instance.currentYear;
                const el = instance.calendarContainer.querySelector('input.cur-year') || instance.calendarContainer.querySelector('.cur-year') || instance.currentYearElement;
                if (el) {
                    if (el.tagName === 'INPUT') el.value = yr;
                    else el.textContent = yr;
                }
            }
        }
    });

    // Initial counts and dynamic banner update
    const totalAthletesCount = <?= count($athletes); ?>;
    const selectedPeriodLabel = "<?= $periods[$selectedPeriod]['label'] ?? 'เช็กชื่อ' ?>";
    const selectedDateFormatted = "<?= date('d/m/', strtotime($selectedDate)) . (date('Y', strtotime($selectedDate)) + 543) ?>";
    const currentUsername = "<?= session()->get('username') ?>";

    function updateLiveStats() {
        let p = 0, s = 0, pe = 0, h = 0, c = 0, ab = 0;
        let checkedStudentIds = new Set();

        $('input.status-btn-radio:checked').each(function() {
            const name = $(this).attr('name');
            const row = $(this).closest('.athlete-row');
            const studentId = row.data('student-id');
            const val = $(this).val();

            if (studentId && val) {
                checkedStudentIds.add(studentId);
            }

            if (name && name.startsWith('st_')) {
                if(val === 'present') p++;
                else if(val === 'sick') s++;
                else if(val === 'personal') pe++;
                else if(val === 'home') h++;
                else if(val === 'competition') c++;
                else if(val === 'absent') ab++;
            }
        });

        $('#presentBadge').text(p + ' มา');
        $('#sickBadge').text(s + ' ป่วย');
        $('#personalBadge').text(pe + ' กิจธุระ');
        $('#homeBadge').text(h + ' กลับบ้าน');
        $('#competitionBadge').text(c + ' แข่งขัน');
        $('#absentBadge').text(ab + ' ไม่มา');

        // Dynamic Alert Banner Update
        const checkedCount = checkedStudentIds.size;
        const remainingCount = totalAthletesCount - checkedCount;
        let bannerHtml = '';

        if (totalAthletesCount > 0 && checkedCount >= totalAthletesCount) {
            bannerHtml = `
                <div class="alert alert-success d-flex align-items-center justify-content-between rounded-16 shadow-sm border-0 mb-3 p-3" style="background: #ecfdf5; border-left: 6px solid #10b981 !important;">
                    <div class="d-flex align-items-center gap-3">
                        <div class="avatar avatar-md bg-success text-white rounded-circle d-flex align-items-center justify-content-center">
                            <i class="bx bx-check-circle fs-3"></i>
                        </div>
                        <div>
                            <h6 class="fw-bold text-dark mb-0 d-flex align-items-center gap-2 flex-wrap">
                                <span>✅ บันทึกการเช็กชื่อครบทุกคนเรียบร้อยแล้ว (${checkedCount}/${totalAthletesCount} คน)</span>
                                <span class="badge bg-success text-white">ประจำวันที่ ${selectedDateFormatted}</span>
                            </h6>
                            <small class="text-muted d-block mt-1">
                                <i class="bx bx-user me-1"></i>ผู้ลงเวลาบันทึก: <strong class="text-dark">${currentUsername}</strong>
                            </small>
                        </div>
                    </div>
                    <span class="badge bg-label-success fw-bold px-3 py-2 rounded-pill fs-7 d-none d-md-inline-block">
                        <i class="bx bx-shield-quarter me-1"></i>เช็กชื่อครบแล้ว
                    </span>
                </div>`;
        } else if (checkedCount > 0) {
            bannerHtml = `
                <div class="alert alert-warning d-flex align-items-center justify-content-between rounded-16 shadow-sm border-0 mb-3 p-3" style="background: #fffbe6; border-left: 6px solid #f59e0b !important;">
                    <div class="d-flex align-items-center gap-3">
                        <div class="avatar avatar-md bg-warning text-white rounded-circle d-flex align-items-center justify-content-center animate-pulse">
                            <i class="bx bx-error-circle fs-3"></i>
                        </div>
                        <div>
                            <h6 class="fw-bold text-dark mb-0 d-flex align-items-center gap-2 flex-wrap">
                                <span class="fw-bold" style="color: #b45309;">⚠️ ยังเช็คชื่อไม่ครบทุกคนในทีม! (เช็กแล้ว ${checkedCount}/${totalAthletesCount} คน)</span>
                                <span class="badge bg-warning text-dark fw-bold">เหลืออีก ${remainingCount} คนยังไม่ได้เลือก</span>
                            </h6>
                            <small class="text-muted d-block mt-1">
                                กรุณาเลือกสถานะให้นักกีฬาที่เหลืออีก <strong>${remainingCount} คน</strong> สำหรับช่วงเวลา <strong>${selectedPeriodLabel}</strong> (ประจำวันที่ ${selectedDateFormatted})
                            </small>
                        </div>
                    </div>
                    <span class="badge bg-warning text-dark fw-bold px-3 py-2 rounded-pill fs-7 d-none d-md-inline-block">
                        <i class="bx bx-time-five me-1"></i>ยังเช็กไม่ครบ
                    </span>
                </div>`;
        } else {
            bannerHtml = `
                <div class="alert alert-danger d-flex align-items-center justify-content-between rounded-16 shadow-sm border-0 mb-3 p-3 animate-pulse-border" style="background: #fff5f5; border-left: 6px solid #ef4444 !important;">
                    <div class="d-flex align-items-center gap-3">
                        <div class="avatar avatar-md bg-danger text-white rounded-circle d-flex align-items-center justify-content-center animate-pulse">
                            <i class="bx bx-x-circle fs-3"></i>
                        </div>
                        <div>
                            <h6 class="fw-bold text-dark mb-0 d-flex align-items-center gap-2 flex-wrap">
                                <span class="text-danger fw-bold">⚠️ ยังไม่ได้ลงเวลาเช็กชื่อช่วงเวลานี้!</span>
                                <span class="badge bg-danger text-white animate-pulse">ยังไม่เช็กชื่อ (0/${totalAthletesCount} คน)</span>
                            </h6>
                            <small class="text-muted d-block mt-1">
                                กรุณาลงเวลาเช็กชื่อสำหรับช่วงเวลา <strong>${selectedPeriodLabel}</strong> (ประจำวันที่ ${selectedDateFormatted})
                            </small>
                        </div>
                    </div>
                    <span class="badge bg-danger text-white fw-bold px-3 py-2 rounded-pill fs-7 d-none d-md-inline-block animate-pulse">
                        <i class="bx bx-time-five me-1"></i>รอดำเนินการ
                    </span>
                </div>`;
        }
        $('#attendanceAlertBanner').html(bannerHtml);
    }
    updateLiveStats();

    // AJAX Save handler
    $(document).on('change', '.status-btn-radio', function() {
        const row = $(this).closest('.athlete-row');
        const val = $(this).val();
        const studentId = row.data('student-id');
        
        // Sync mobile and desktop radios
        $(`.status-btn-radio[name="st_${studentId}"], .status-btn-radio[name="mst_${studentId}"]`).filter(`[value="${val}"]`).prop('checked', true);
        
        const prevVal = $(this).data('prev') || '';

        if (val === 'present' || val === 'absent') {
            saveStudentAttendance(row, null, val);
            updateLiveStats();
        } else {
            // หาชื่อนักเรียนจาก Desktop (.fw-semibold) หรือ Mobile (.fw-bold)
            const studentName = row.find('.fw-semibold').text() || row.find('.fw-bold.text-dark').first().text() || 'นักกีฬา';
            const statusLabel = $(this).next('label').text();
            
            // ดึงรูปภาพนักเรียนจาก img ในแถว
            const studentImg = row.find('img.athlete-avatar, .athlete-avatar-box img').first();
            const studentPhotoUrl = studentImg.length ? studentImg.attr('src') : '';
            
            // กำหนด title และ label ตามประเภทสถานะ
            let popupTitle = 'บันทึกการลา';
            let dateLabel = 'ระบุช่วงวันที่ลา';
            if (val === 'competition') {
                popupTitle = 'บันทึกการแข่งขัน';
                dateLabel = 'ระบุช่วงวันที่ไปแข่งขัน';
            }
            
            Swal.fire({
                title: popupTitle,
                html: `
                    <div class="text-start mb-2">
                        <!-- Student Info Header with Photo -->
                        <div class="d-flex align-items-center gap-3 mb-3 p-2 rounded-3" style="background: #f8fafc; border: 1px solid #e2e8f0;">
                            ${studentPhotoUrl ? `<img src="${studentPhotoUrl}" style="width: 50px; height: 66px; object-fit: cover; border-radius: 10px; border: 2px solid #fff; box-shadow: 0 2px 8px rgba(0,0,0,0.1);" onerror="this.style.display='none'">` : ''}
                            <div>
                                <div class="fw-bold text-dark" style="font-size: 1rem;">${studentName.trim()}</div>
                                <span class="badge bg-label-primary fs-6">${statusLabel}</span>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold text-dark mb-1">${dateLabel}</label>
                            
                            <!-- Quick Preset Buttons -->
                            <div class="d-flex gap-1 mb-2 flex-wrap" id="swalPresetButtons">
                                <button type="button" class="btn btn-sm btn-outline-primary quick-date-preset active-preset" data-days="1">⚡ วันนี้</button>
                                <button type="button" class="btn btn-sm btn-outline-primary quick-date-preset" data-days="2">⚡ 2 วัน</button>
                                <button type="button" class="btn btn-sm btn-outline-primary quick-date-preset" data-days="3">⚡ 3 วัน</button>
                                <button type="button" class="btn btn-sm btn-outline-primary quick-date-preset" data-days="7">⚡ 1 สัปดาห์</button>
                            </div>

                            <!-- Inline Calendar Container -->
                            <div class="swal-calendar-wrap" id="swalCalendarContainer"></div>
                            <small class="text-muted d-block mt-1 text-center" style="font-size: 0.75rem;"><i class="bx bx-info-circle me-1"></i> แตะ 1 ครั้ง = เลือก 1 วัน, แตะ 2 ครั้ง = เลือกช่วงวัน</small>
                        </div>
                        
                        <div class="mb-2">
                            <label class="form-label fw-bold text-dark mb-1">หมายเหตุ</label>
                            <input type="text" id="swal-note-inline" class="form-control" placeholder="ระบุเหตุผลการลา (ถ้ามี)...">
                        </div>
                    </div>
                `,
                didOpen: () => {
                    const currentDateStr = $('#attendanceDate').val() || new Date().toISOString().split('T')[0];
                    const baseDate = new Date(currentDateStr);

                    window.rangePicker = flatpickr("#swalCalendarContainer", {
                        mode: "range",
                        locale: "th",
                        dateFormat: "Y-m-d",
                        defaultDate: [baseDate],
                        disableMobile: true,
                        inline: true,
                        onChange: function(selectedDates, dateStr, instance) {
                            window.selectedRangeDates = selectedDates;
                            setBuddhistYear(instance);
                        },
                        onReady: function(selectedDates, dateStr, instance) {
                            setBuddhistYear(instance);
                        },
                        onMonthChange: function(selectedDates, dateStr, instance) {
                            setBuddhistYear(instance);
                        },
                        onYearChange: function(selectedDates, dateStr, instance) {
                            setBuddhistYear(instance);
                        }
                    });

                    // Helper: แปลงปี ค.ศ. → พ.ศ. ในปฏิทิน inline
                    function setBuddhistYear(instance) {
                        if (!instance.calendarContainer) return;
                        const buddhistYear = instance.currentYear + 543;
                        // Handle <input class="cur-year"> (inline mode)
                        const yearInput = instance.calendarContainer.querySelector('input.cur-year');
                        if (yearInput) {
                            yearInput.value = buddhistYear;
                            return;
                        }
                        // Handle <span class="cur-year"> (popup mode)
                        const yearSpan = instance.calendarContainer.querySelector('.cur-year');
                        if (yearSpan) yearSpan.textContent = buddhistYear;
                    }
                    window.selectedRangeDates = [baseDate];

                    // Quick Preset Click Handler
                    $(document).off('click', '.quick-date-preset').on('click', '.quick-date-preset', function() {
                        const days = parseInt($(this).data('days')) || 1;
                        const startDate = new Date(currentDateStr);
                        const endDate = new Date(currentDateStr);
                        endDate.setDate(startDate.getDate() + (days - 1));

                        // Handle array return from flatpickr
                        const picker = Array.isArray(window.rangePicker) ? window.rangePicker[0] : window.rangePicker;
                        picker.setDate([startDate, endDate], true);
                        window.selectedRangeDates = [startDate, endDate];
                        
                        $('.quick-date-preset').removeClass('btn-primary text-white').addClass('btn-outline-primary');
                        $(this).removeClass('btn-outline-primary').addClass('btn-primary text-white');
                    });
                },
                showCancelButton: true,
                confirmButtonText: '<i class="bx bx-save me-1"></i> บันทึกข้อมูล',
                cancelButtonText: 'ยกเลิก',
                allowOutsideClick: false,
                preConfirm: () => {
                    const selectedDates = window.selectedRangeDates || [];
                    const note = $('#swal-note-inline').val();
                    
                    if (!selectedDates || selectedDates.length === 0) {
                        Swal.showValidationMessage('กรุณาเลือกวันที่');
                        return false;
                    }
                    
                    // แปลง Date object เป็น string format Y-m-d
                    let startDate, endDate;
                    if (typeof selectedDates[0] === 'string') {
                        startDate = selectedDates[0];
                        endDate = selectedDates[1] || selectedDates[0];
                    } else {
                        startDate = selectedDates[0].toISOString().split('T')[0];
                        endDate = selectedDates[1] ? selectedDates[1].toISOString().split('T')[0] : startDate;
                    }
                    
                    return {
                        status: val,
                        startDate: startDate,
                        endDate: endDate,
                        note: note
                    };
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    saveRangeAttendance(row, result.value);
                    updateLiveStats();
                } else {
                    // Revert to previous value
                    $(`.status-btn-radio[name="st_${studentId}"], .status-btn-radio[name="mst_${studentId}"]`).prop('checked', false);
                    if (prevVal) {
                        $(`.status-btn-radio[name="st_${studentId}"], .status-btn-radio[name="mst_${studentId}"]`).filter(`[value="${prevVal}"]`).prop('checked', true);
                    }
                    updateLiveStats();
                }
            });
        }
    });

    // Store previous value before change
    $('.status-btn-radio').on('focus mousedown', function() {
        const name = $(this).attr('name');
        const currentChecked = $(this).closest('.status-container').find('.status-btn-radio:checked').val() || '';
        $(this).data('prev', currentChecked);
    });

    function saveStudentAttendance(row, endDate = null, statusOverride = null) {
        const studentId = row.data('student-id');
        // ใช้ค่า statusOverride ที่ส่งมาตรงๆ จาก onChange handler (ปลอดภัยกว่าการ re-query)
        const status = statusOverride || $(`.status-btn-radio[name="st_${studentId}"]:checked, .status-btn-radio[name="mst_${studentId}"]:checked`).first().val();
        const date = $('#attendanceDate').val();
        const period = $('#attendanceDate').data('period') || 'morning';

        // ถ้าไม่มีค่า status ไม่ต้องส่ง AJAX
        if (!status) {
            console.warn('saveStudentAttendance: status is empty for student', studentId);
            return;
        }

        $.ajax({
            url: '<?= base_url('Admin/Attendance/Save'); ?>',
            method: 'POST',
            data: {
                student_id: studentId,
                team_id: teamId,
                period: period,
                status: status,
                note: '',
                date: date,
                end_date: endDate
            },
            success: function(res) {
                if(res.success) {
                    // ถ้าเปลี่ยนเป็น "อยู่" ให้ลบป้ายวันลาและหมายเหตุออกจาก UI ทันที
                    // ถ้าเปลี่ยนเป็น "มา" หรือ "ไม่มา" ให้ลบป้ายวันลาและหมายเหตุออกจาก UI ทันที
                    if (status === 'present' || status === 'absent') {
                        // ลบป้ายวันลาทั้ง Desktop และ Mobile ที่ตรงกับ student ID เดียวกัน
                        $(`.athlete-row[data-student-id="${studentId}"]`).each(function() {
                            $(this).find('.text-primary.fw-bold').remove();  // ป้ายช่วงวันลา
                            $(this).find('.leave-badge-info').remove();     // กล่องวันลา
                            $(this).find('.text-muted.italic').remove();     // หมายเหตุ
                            // เปลี่ยนข้อความหมายเหตุในตาราง Desktop ให้เป็น "-"
                            $(this).find('td.text-center .text-muted.small').text('-');
                        });
                    }

                    const Toast = Swal.mixin({
                        toast: true,
                        position: 'top',
                        showConfirmButton: false,
                        timer: 1500,
                        timerProgressBar: true
                    });
                    Toast.fire({
                        icon: 'success',
                        title: res.message
                    });
                } else {
                    Swal.fire('ข้อผิดพลาด', res.message || 'ไม่สามารถบันทึกข้อมูลได้', 'error');
                    // Revert radio button checked state visually
                    $(`.status-btn-radio[name="st_${studentId}"], .status-btn-radio[name="mst_${studentId}"]`).prop('checked', false);
                }
            },
            error: function() {
                Swal.fire('Error', 'ไม่สามารถบันทึกข้อมูลได้', 'error');
            }
        });
    }

    function saveRangeAttendance(row, data) {
        const period = $('#attendanceDate').data('period') || 'morning';
        $.ajax({
            url: '<?= base_url('Admin/Attendance/Save'); ?>',
            method: 'POST',
            data: {
                student_id: row.data('student-id'),
                team_id: teamId,
                period: period,
                status: data.status,
                note: data.note,
                date: data.startDate,
                end_date: data.endDate
            },
            success: function(res) {
                if(res.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'สำเร็จ',
                        text: res.message,
                        timer: 1500,
                        showConfirmButton: false
                    }).then(() => {
                        // ถ้าบันทึกช่วงวันที่ยาวกว่า 1 วัน หรือ ไม่ใช่วันที่กำลังดู ให้รีโหลดหน้า
                        if (data.startDate !== data.endDate || data.startDate !== $('#attendanceDate').val()) {
                            location.reload();
                        }
                    });
                }
            },
            error: function() {
                Swal.fire('Error', 'ไม่สามารถบันทึกข้อมูลได้', 'error');
            }
        });
    }
});
</script>
<?= $this->endSection() ?>
