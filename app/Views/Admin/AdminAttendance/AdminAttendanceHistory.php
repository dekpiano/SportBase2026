<?= $this->extend('Admin/layout/AdminLayout') ?>

<?= $this->section('content') ?>
<style>
    .glass-card {
        background: rgba(255, 255, 255, 0.8);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.4);
        border-radius: 20px;
        box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.07);
    }

    .premium-header {
        background: linear-gradient(135deg, #fd7e14 0%, #ff9e43 100%);
        border-radius: 20px;
        padding: 2.5rem;
        color: white;
        margin-bottom: 2rem;
        box-shadow: 0 10px 20px rgba(253, 126, 20, 0.2);
    }

    .filter-section {
        margin-top: -3rem;
        margin-bottom: 2rem;
        padding: 0 1rem;
    }

    .stat-icon {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
    }

    .summary-badge-card {
        border: none;
        border-radius: 15px;
        transition: transform 0.2s;
    }

    .summary-badge-card:hover {
        transform: translateY(-3px);
    }

    .custom-table thead th {
        background-color: #f8faff;
        border: none;
        color: #4b5d6d;
        font-weight: 700;
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 0.05em;
        padding: 1.25rem 1rem;
    }

    .custom-table tbody td {
        border-bottom: 1px solid #f1f4f8;
        padding: 1rem;
        vertical-align: middle;
    }

    .status-badge {
        padding: 6px 12px;
        border-radius: 8px;
        font-weight: 600;
        font-size: 0.8rem;
    }
</style>

<div class="container-xxl flex-grow-1 container-p-y">
    <div class="premium-header">
        <div class="row align-items-center">
            <div class="col-md-8">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-2">
                        <li class="breadcrumb-item"><a href="<?= base_url('Admin/Attendance'); ?>" class="text-white opacity-75">เช็กชื่อประจำวัน</a></li>
                        <li class="breadcrumb-item active text-white">รายงานประวัติเช็กชื่อ & การลา</li>
                    </ol>
                </nav>
                <h2 class="text-white fw-bold mb-0">รายงานประวัติเช็กชื่อ & การลา</h2>
                <p class="text-white-50 mt-1 mb-0">ตรวจสอบและเรียกดูข้อมูลการเช็กชื่อ (เช้า/ซ้อม/นอน) และการลานักเรียนย้อนหลัง</p>
            </div>
            <div class="col-md-4 text-md-end">
                <i class='bx bx-history fs-huge opacity-25'></i>
            </div>
        </div>
    </div>

    <!-- Filter Form -->
    <div class="filter-section">
        <div class="card glass-card">
            <div class="card-body p-4">
                <form method="get" class="row g-3">
                    <div class="col-lg-4">
                        <label class="form-label fw-semibold"><i class='bx bx-group me-1'></i>เลือกทีม / รุ่น</label>
                        <select name="team" class="form-select select2" onchange="this.form.submit()">
                            <option value="">-- ทั้งหมดทุกทีม --</option>
                            <?php foreach($teams as $team): ?>
                                <option value="<?= $team['team_id']; ?>" <?= (string)$selectedTeam === (string)$team['team_id'] ? 'selected' : ''; ?>>
                                    <?= $team['team_name']; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-lg-3">
                        <label class="form-label fw-semibold"><i class='bx bx-calendar me-1'></i>ตั้งแต่วันที่</label>
                        <input type="text" name="start" id="startDate" class="form-control" value="<?= $startDate; ?>">
                    </div>
                    <div class="col-lg-3">
                        <label class="form-label fw-semibold"><i class='bx bx-calendar-check me-1'></i>ถึงวันที่</label>
                        <input type="text" name="end" id="endDate" class="form-control" value="<?= $endDate; ?>">
                    </div>
                    <div class="col-lg-2 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary w-100 py-2 fw-bold">
                            <i class="bx bx-search-alt me-1"></i> ค้นหาข้อมูล
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Summary Statistics -->
    <div class="row g-3 mb-4">
        <?php 
        $summary = ['sick' => 0, 'personal' => 0, 'home' => 0];
        foreach($history as $h) {
            if(isset($summary[$h['att_status']])) $summary[$h['att_status']]++;
        }
        ?>
        <div class="col-md-4">
            <div class="card summary-badge-card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center gap-3">
                        <div class="stat-icon bg-label-warning">
                            <i class='bi bi-plus-lg fs-4'></i>
                        </div>
                        <div>
                            <h4 class="mb-0 fw-bold text-warning"><?= $summary['sick']; ?> เคส</h4>
                            <small class="text-muted">ลาป่วยสะสม</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card summary-badge-card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center gap-3">
                        <div class="stat-icon bg-label-info">
                            <i class='bi bi-briefcase fs-4'></i>
                        </div>
                        <div>
                            <h4 class="mb-0 fw-bold text-info"><?= $summary['personal']; ?> เคส</h4>
                            <small class="text-muted">ลากิจธุระสะสม</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card summary-badge-card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center gap-3">
                        <div class="stat-icon bg-label-danger">
                            <i class='bi bi-house fs-4'></i>
                        </div>
                        <div>
                            <h4 class="mb-0 fw-bold text-danger"><?= $summary['home']; ?> เคส</h4>
                            <small class="text-muted">กลับบ้านสะสม</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Summary by Team (Only show when "All Teams" is selected) -->
    <?php if (!$selectedTeam && !empty($teamSummary)): ?>
    <div class="card border-0 shadow-sm rounded-20 mb-4 overflow-hidden">
        <div class="card-header bg-white py-3 border-bottom">
            <h5 class="mb-0 fw-bold"><i class='bx bx-pie-chart-alt-2 me-2 text-primary'></i>สรุปรายรุ่น / ทีม (ช่วงเวลาที่เลือก)</h5>
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4"><i class='bx bx-group me-1'></i>ชื่อรุ่น / ทีม</th>
                        <th class="text-center"><i class='bx bx-plus-medical me-1'></i>ลาป่วย</th>
                        <th class="text-center"><i class='bx bx-briefcase me-1'></i>ลากิจธุรกรรม</th>
                        <th class="text-center"><i class='bx bx-home me-1'></i>ลากิจกลับบ้าน</th>
                        <th class="text-center fw-bold"><i class='bx bx-objects-vertical-bottom me-1'></i>รวมทั้งหมด</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($teamSummary as $teamName => $counts): ?>
                    <tr>
                        <td class="ps-4 fw-semibold"><?= $teamName ?></td>
                        <td class="text-center"><span class="badge bg-label-warning"><i class='bx bx-plus-medical me-1'></i><?= $counts['sick'] ?></span></td>
                        <td class="text-center"><span class="badge bg-label-info"><i class='bx bx-briefcase me-1'></i><?= $counts['personal'] ?></span></td>
                        <td class="text-center"><span class="badge bg-label-danger"><i class='bx bx-home me-1'></i><?= $counts['home'] ?></span></td>
                        <td class="text-center fw-bold text-primary"><?= $counts['total'] ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php endif; ?>

    <!-- History List (Desktop Table + Mobile Cards) -->
    <div class="card border-0 shadow-sm rounded-20 overflow-hidden">
        <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
            <h5 class="mb-0 fw-bold"><i class='bx bx-list-ul me-2 text-primary'></i>รายละเอียดประวัติการเช็กชื่อ & การลา</h5>
            <button class="btn btn-sm btn-outline-secondary rounded-pill fw-bold" onclick="window.print()"><i class='bx bx-printer me-1'></i> พิมพ์รายงาน</button>
        </div>
        
        <!-- Desktop View Table -->
        <div class="table-responsive d-none d-md-block">
            <table class="table custom-table mb-0" id="tableHistory">
                <thead>
                    <tr>
                        <th width="50" class="text-center">#</th>
                        <th width="140"><i class='bx bx-calendar me-1'></i>ช่วงวันที่</th>
                        <th><i class='bx bx-group me-1'></i>รุ่น / ทีม</th>
                        <th><i class='bx bx-user me-1'></i>นักเรียน / ชั้นเรียน</th>
                        <th><i class='bx bx-time-five me-1'></i>ช่วงเวลา</th>
                        <th><i class='bx bx-check-shield me-1'></i>สถานะ</th>
                        <th><i class='bx bx-note me-1'></i>หมายเหตุ</th>
                        <th width="120"><i class='bx bx-time me-1'></i>เวลาบันทึก</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $i = 1; foreach($history as $row): 
                        $status = $statuses[$row['att_status']] ?? ['label' => $row['att_status'], 'color' => 'secondary', 'icon' => 'bx-help-circle'];
                        $periodInfo = $periods[$row['att_period']] ?? ['label' => 'เข้าแถวเช้า', 'icon' => 'bx-sun'];
                    ?>
                        <tr>
                            <td class="text-muted small text-center"><?= $i++; ?></td>
                            <td>
                                <?php if (!empty($row['att_start_date']) && !empty($row['att_end_date']) && $row['att_start_date'] !== $row['att_end_date']): ?>
                                    <div class="fw-bold text-primary" style="font-size: 0.82rem;">
                                        <?= date('d/m/y', strtotime($row['att_start_date'])); ?> - <?= date('d/m/y', strtotime($row['att_end_date'])); ?>
                                    </div>
                                    <small class="text-muted" style="font-size: 0.7rem;">ลงวันที่: <?= date('d/m/y', strtotime($row['att_date'])); ?></small>
                                <?php else: ?>
                                    <span class="fw-bold"><?= date('d/m/Y', strtotime($row['att_date'])); ?></span>
                                <?php endif; ?>
                            </td>
                            <td><span class="badge bg-label-primary rounded-pill fw-bold"><?= $row['team_name']; ?></span></td>
                            <td>
                                <div class="d-flex align-items-center gap-2 flex-wrap mb-0.5">
                                    <span class="fw-bold text-dark fs-6"><?= $row['StudentPrefix'] . $row['StudentFirstName'] . ' ' . $row['StudentLastName']; ?></span>
                                    <span class="badge bg-label-secondary rounded-pill small fw-bold"><?= $row['StudentClass']; ?></span>
                                </div>
                                <small class="text-muted">#<?= $row['StudentCode']; ?></small>
                            </td>
                            <td>
                                <span class="badge bg-label-secondary rounded-pill small fw-bold">
                                    <i class="bx <?= $periodInfo['icon'] ?> text-warning me-1"></i><?= $periodInfo['label'] ?>
                                </span>
                            </td>
                            <td>
                                <span class="status-badge bg-label-<?= $status['color']; ?>">
                                    <i class='bx <?= $status['icon'] ?> me-1'></i><?= $status['label']; ?>
                                </span>
                            </td>
                            <td><span class="text-muted italic small"><?= $row['att_note'] ?: '-'; ?></span></td>
                            <td>
                                <div class="small text-muted">
                                    <i class='bx bx-time-five me-1'></i><?= !empty($row['att_time']) ? date('H:i', strtotime($row['att_time'])) . ' น.' : '-'; ?>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <!-- Mobile View Cards Grid -->
        <div class="card-body p-3 d-md-none bg-light">
            <?php if (empty($history)): ?>
                <div class="text-center py-5 bg-white rounded-20 shadow-sm p-4">
                    <i class="bx bx-history fs-1 text-muted opacity-25"></i>
                    <p class="text-muted mt-2">ไม่พบประวัติการเช็กชื่อ/การลา</p>
                </div>
            <?php else: ?>
                <?php foreach ($history as $row): 
                    $status = $statuses[$row['att_status']] ?? ['label' => $row['att_status'], 'color' => 'secondary', 'icon' => 'bx-help-circle'];
                    $periodInfo = $periods[$row['att_period']] ?? ['label' => 'เข้าแถวเช้า', 'icon' => 'bx-sun'];
                    
                    // Photo Logic
                    if (!empty($row['athlete_image'])) {
                        $photoUrl = base_url('uploads/athletes/' . $row['athlete_image']);
                    } else {
                        $photoUrl = "https://skj.ac.th/uploads/students_photo/{$row['StudentCode']}.jpg";
                    }
                    $fallbackUrl = 'https://ui-avatars.com/api/?name=' . urlencode($row['StudentFirstName'] . ' ' . $row['StudentLastName']) . '&background=random&size=100';
                    
                    $sDate = !empty($row['att_start_date']) ? $row['att_start_date'] : $row['att_date'];
                    $eDate = !empty($row['att_end_date']) ? $row['att_end_date'] : $sDate;
                    $diffDays = (int)((strtotime($eDate) - strtotime($sDate)) / 86400) + 1;
                    
                    $sText = date('d/m/', strtotime($sDate)) . (date('Y', strtotime($sDate)) + 543);
                    $eText = date('d/m/', strtotime($eDate)) . (date('Y', strtotime($eDate)) + 543);
                    $dateRangeStr = ($sDate === $eDate) ? $sText : ($sText . ' - ' . $eText);
                ?>
                    <div class="mobile-history-card shadow-sm rounded-20 p-3 mb-3 bg-white border border-light">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <span class="badge bg-label-primary rounded-pill small fw-bold">
                                <i class="bx bx-group me-1"></i><?= $row['team_name'] ?>
                            </span>
                            <span class="badge bg-label-<?= $status['color'] ?> fw-bold rounded-pill">
                                <i class="bx <?= $status['icon'] ?> me-1"></i><?= $status['label'] ?>
                            </span>
                        </div>
                        
                        <div class="d-flex align-items-center gap-3 my-2">
                            <img src="<?= $photoUrl ?>" onerror="this.src='<?= $fallbackUrl ?>'" style="width: 48px; height: 62px; object-fit: cover; border-radius: 10px; border: 2px solid #fff; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
                            <div class="flex-grow-1">
                                <div class="fw-bold text-dark fs-6"><?= $row['StudentPrefix'] . $row['StudentFirstName'] . ' ' . $row['StudentLastName'] ?></div>
                                <div class="text-muted small">
                                    <span class="me-2">#<?= $row['StudentCode'] ?></span>
                                    <span>ชั้น <?= $row['StudentClass'] ?></span>
                                </div>
                                <div class="mt-1">
                                    <span class="badge bg-light text-dark border fw-bold" style="font-size: 0.72rem;">
                                        <i class="bx bx-calendar-event me-1 text-warning"></i><?= $diffDays ?> วัน (<?= $dateRangeStr ?>)
                                    </span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="pt-2 mt-2 border-top d-flex justify-content-between align-items-center text-muted small" style="font-size: 0.78rem;">
                            <div>
                                <i class="bx <?= $periodInfo['icon'] ?> text-warning me-1"></i><?= $periodInfo['label'] ?>
                            </div>
                            <div>
                                <i class="bx bx-time me-1"></i>เวลา: <?= !empty($row['att_time']) ? date('H:i', strtotime($row['att_time'])) . ' น.' : '-' ?>
                            </div>
                        </div>
                        <?php if (!empty($row['att_note'])): ?>
                            <div class="mt-2 small text-muted italic bg-light p-2 rounded-10" style="font-size: 0.75rem;">
                                <i class="bx bx-note me-1 text-secondary"></i><?= $row['att_note'] ?>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('script') ?>
<!-- Flatpickr Thai Locale -->
<script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/th.js"></script>

<script>
$(document).ready(function() {
    // Select2 premium
    $('.select2').select2({
        width: '100%'
    });

    // Flatpickr setup with Thai Locale and Buddhist Year
    const flatpickrConfig = {
        locale: "th",
        dateFormat: "Y-m-d",
        allowInput: true,
        altInput: true,
        altFormat: "j F Y",
        disableMobile: true,
        onReady: function(selectedDates, dateStr, instance) {
            const yr = instance.currentYear < 2500 ? instance.currentYear + 543 : instance.currentYear;
            const el = instance.calendarContainer.querySelector('input.cur-year') || instance.calendarContainer.querySelector('.cur-year') || instance.currentYearElement;
            if (el) {
                if (el.tagName === 'INPUT') el.value = yr;
                else el.textContent = yr;
            }
        },
        onYearChange: function(selectedDates, dateStr, instance) {
            const yr = instance.currentYear < 2500 ? instance.currentYear + 543 : instance.currentYear;
            const el = instance.calendarContainer.querySelector('input.cur-year') || instance.calendarContainer.querySelector('.cur-year') || instance.currentYearElement;
            if (el) {
                if (el.tagName === 'INPUT') el.value = yr;
                else el.textContent = yr;
            }
        }
    };

    flatpickr("#startDate", flatpickrConfig);
    flatpickr("#endDate", flatpickrConfig);

    // DataTable setup
    $('#tableHistory').DataTable({
        language: {
            url: '//cdn.datatables.net/plug-ins/1.13.4/i18n/th.json',
        },
        order: [[1, 'desc']],
        pageLength: 25,
        dom: '<"row px-3 py-2"<"col-md-6"l><"col-md-6"f>>rt<"row px-3 py-2"<"col-md-6"i><"col-md-6"p>>'
    });
});
</script>
<?= $this->endSection() ?>
