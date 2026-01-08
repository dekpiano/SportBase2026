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
                        <li class="breadcrumb-item"><a href="<?= base_url('Admin/Attendance'); ?>" class="text-white opacity-75">บันทึกการลานักกีฬา</a></li>
                        <li class="breadcrumb-item active text-white">ประวัติการลา</li>
                    </ol>
                </nav>
                <h2 class="text-white fw-bold mb-0">ประวัติการลาและการซ้อม</h2>
                <p class="text-white-50 mt-1 mb-0">ตรวจสอบและเรียกดูข้อมูลการลานักกีฬาย้อนหลังตามช่วงเวลา</p>
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

    <!-- History Table -->
    <div class="card border-0 shadow-sm rounded-20 overflow-hidden">
        <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
            <h5 class="mb-0 fw-bold"><i class='bx bx-list-ul me-2 text-primary'></i>รายละเอียดประวัติการบันทึกการลา</h5>
            <button class="btn btn-sm btn-outline-secondary" onclick="window.print()"><i class='bx bx-printer me-1'></i> พิมพ์รายงาน</button>
        </div>
        <div class="table-responsive">
            <table class="table custom-table" id="tableHistory">
                <thead>
                    <tr>
                        <th width="50">#</th>
                        <th width="100"><i class='bx bx-calendar me-1'></i>วันที่</th>
                        <th><i class='bx bx-group me-1'></i>รุ่น / ทีม</th>
                        <th><i class='bx bx-user me-1'></i>นักกีฬา</th>
                        <th width="80" class="text-center"><i class='bx bx-layer me-1'></i>ชั้น</th>
                        <th><i class='bx bx-check-shield me-1'></i>ประเภทการลา</th>
                        <th><i class='bx bx-note me-1'></i>หมายเหตุ</th>
                        <th width="120"><i class='bx bx-time me-1'></i>เวลาบันทึก</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $i = 1; foreach($history as $row): ?>
                        <tr>
                            <td class="text-muted small"><?= $i++; ?></td>
                            <td>
                                <?php if (!empty($row['att_start_date']) && !empty($row['att_end_date']) && $row['att_start_date'] !== $row['att_end_date']): ?>
                                    <div class="fw-bold text-primary" style="font-size: 0.85rem;">
                                        <?= date('d/m/y', strtotime($row['att_start_date'])); ?> - <?= date('d/m/y', strtotime($row['att_end_date'])); ?>
                                    </div>
                                    <small class="text-muted">วันที่บันทึก: <?= date('d/m/y', strtotime($row['att_date'])); ?></small>
                                <?php else: ?>
                                    <span class="fw-bold"><?= date('d/m/Y', strtotime($row['att_date'])); ?></span>
                                <?php endif; ?>
                            </td>
                            <td><span class="badge bg-label-primary rounded-pill"><?= $row['team_name']; ?></span></td>
                            <td>
                                <span class="d-block fw-semibold"><?= $row['StudentPrefix'] . $row['StudentFirstName'] . ' ' . $row['StudentLastName']; ?></span>
                                <small class="text-muted">ID: <?= $row['StudentCode']; ?></small>
                            </td>
                            <td class="text-center"><?= $row['StudentClass']; ?></td>
                            <td>
                                <?php 
                                $statusIcons = [
                                    'sick' => 'bx-plus-medical',
                                    'personal' => 'bx-briefcase',
                                    'home' => 'bx-home'
                                ];
                                $status = $statuses[$row['att_status']] ?? ['label' => $row['att_status'], 'color' => 'secondary'];
                                $icon = $statusIcons[$row['att_status']] ?? 'bx-help-circle';
                                ?>
                                <span class="status-badge bg-label-<?= $status['color']; ?>">
                                    <i class='bx <?= $icon ?> me-1'></i><?= $status['label']; ?>
                                </span>
                            </td>
                            <td><span class="text-muted italic"><?= $row['att_note'] ?: '-'; ?></span></td>
                            <td>
                                <div class="small">
                                    <i class='bx bx-time-five me-1'></i><?= $row['att_time'] ? date('H:i', strtotime($row['att_time'])) : '-'; ?>น.
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
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
        onReady: function(selectedDates, dateStr, instance) {
            const year = instance.currentYear + 543;
            const yearElement = instance.calendarContainer.querySelector('.cur-year');
            if (yearElement) yearElement.textContent = year;
        },
        onYearChange: function(selectedDates, dateStr, instance) {
            const year = instance.currentYear + 543;
            const yearElement = instance.calendarContainer.querySelector('.cur-year');
            if (yearElement) yearElement.textContent = year;
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
