<style>
    .stat-card-attendance {
        border: none;
        border-radius: 16px;
        transition: all 0.3s ease;
    }
    .stat-card-attendance:hover {
        transform: translateY(-3px);
    }
    .stat-card-attendance .card-body {
        padding: 1rem !important;
    }
    @media (min-width: 768px) {
        .stat-card-attendance .card-body {
            padding: 1.5rem !important;
        }
    }
    .pulse-online {
        display: inline-block;
        width: 8px;
        height: 8px;
        background: #28a745;
        border-radius: 50%;
        margin-right: 4px;
        box-shadow: 0 0 0 rgba(40, 167, 69, 0.4);
        animation: pulse 2s infinite;
    }
    @keyframes pulse {
        0% { box-shadow: 0 0 0 0 rgba(40, 167, 69, 0.4); }
        70% { box-shadow: 0 0 0 8px rgba(40, 167, 69, 0); }
        100% { box-shadow: 0 0 0 0 rgba(40, 167, 69, 0); }
    }
</style>

<div class="layout-page">
    <?php echo view('User/UserLeyout/UserNavbar'); ?>

    <div class="content-wrapper">
        <div class="container-xxl flex-grow-1 container-p-y">
            <div class="row align-items-center mb-4">
                <div class="col-md-6">
                    <h4 class="fw-bold mb-0">
                        <span class="text-muted fw-light">ตรวจสอบ /</span> สถานะภาพนักกีฬาประจำวัน
                    </h4>
                </div>
                <div class="col-md-6 text-md-end mt-3 mt-md-0">
                    <div class="d-inline-block bg-white p-2 rounded shadow-sm border">
                        <i class='bx bx-calendar me-2 text-primary'></i>
                        <input type="date" id="filterDate" value="<?= $selectedDate ?>" class="border-0 outline-none" style="cursor: pointer;">
                    </div>
                </div>
            </div>

            <!-- Summary Stats -->
            <div class="row g-3 mb-4">
                <div class="col-6 col-md-3">
                    <div class="card stat-card-attendance bg-label-success shadow-none border-0">
                        <div class="card-body d-flex align-items-center gap-2">
                            <div class="avatar avatar-sm flex-shrink-0">
                                <span class="avatar-initial rounded-circle bg-success"><i class='bx bx-check-shield fs-5'></i></span>
                            </div>
                            <div class="flex-grow-1">
                                <div class="d-flex align-items-baseline gap-1">
                                    <h4 class="fw-bold mb-0 text-success"><?= $totalPresent ?></h4>
                                    <span class="text-dark fw-semibold small">อยู่</span>
                                </div>
                                <small class="text-muted d-none d-md-block" style="font-size: 0.65rem;"><span class="pulse-online"></span> Real-time</small>
                            </div>
                        </div>
                    </div>
                </div>
                <?php foreach($statuses as $key => $status): 
                    $count = 0;
                    foreach($summary as $s) { if($s['att_status'] == $key) $count = $s['count']; }
                ?>
                <div class="col-6 col-md-3">
                    <div class="card stat-card-attendance bg-label-<?= $status['color'] ?> shadow-none border-0">
                        <div class="card-body d-flex align-items-center gap-2">
                            <div class="avatar avatar-sm flex-shrink-0">
                                <span class="avatar-initial rounded-circle bg-<?= $status['color'] ?>"><i class='bx <?= $status['icon'] ?> fs-5'></i></span>
                            </div>
                            <div class="flex-grow-1">
                                <div class="d-flex align-items-baseline gap-1">
                                    <h4 class="fw-bold mb-0 text-<?= $status['color'] ?>"><?= $count ?></h4>
                                    <span class="text-dark fw-semibold small"><?= $status['label'] ?></span>
                                </div>
                                <small class="text-muted d-none d-md-block" style="font-size: 0.65rem;">ประจำวันนี้</small>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>

            <!-- List Section -->
            <div class="card border-0 shadow-sm rounded-20">
                <div class="card-header border-bottom py-3 d-flex justify-content-between align-items-center bg-transparent">
                    <h5 class="card-title mb-0 fw-bold"><i class='bx bx-list-ul me-2 text-primary'></i>รายชื่อนักกีฬาที่ลา/แจ้งสถานะประจำวัน</h5>
                    <span class="badge bg-label-secondary"><?= count($attendanceList) ?> รายการ</span>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0" id="tableAttendancePublic">
                            <thead class="bg-light">
                                <tr>
                                    <th>รายชื่อนักกีฬา</th>
                                    <th>รุ่น/ทีม</th>
                                    <th class="text-center">สถานะ</th>
                                    <th>เวลาที่บันทึก</th>
                                    <th>หมายเหตุ</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($attendanceList as $att): ?>
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar avatar-sm me-3">
                                                    <img src="https://skj.ac.th/uploads/students_photo/<?= $att['StudentCode'] ?>.jpg" 
                                                         onerror="this.src='https://ui-avatars.com/api/?name=<?= urlencode($att['StudentFirstName']) ?>&background=random'" 
                                                         class="rounded-circle">
                                                </div>
                                                <div>
                                                    <div class="fw-bold"><?= $att['StudentPrefix'].$att['StudentFirstName'].' '.$att['StudentLastName'] ?></div>
                                                    <small class="text-muted">เลขประจำตัว: <?= $att['StudentCode'] ?></small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge bg-label-secondary"><?= $att['team_name'] ?></span>
                                            <div class="small text-muted mt-1"><?= $att['team_sport_type'] ?></div>
                                        </td>
                                        <td class="text-center">
                                            <?php $sInfo = $statuses[$att['att_status']]; ?>
                                            <span class="badge bg-label-<?= $sInfo['color'] ?> px-3">
                                                <i class='bx <?= $sInfo['icon'] ?> me-1'></i> <?= $sInfo['label'] ?>
                                            </span>
                                        </td>
                                        <td><i class='bx bx-time-five me-1'></i><?= date('H:i', strtotime($att['att_time'])) ?> น.</td>
                                        <td><small class="text-muted italic"><?= $att['att_note'] ?: '-' ?></small></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    $('#filterDate').on('change', function() {
        window.location.href = '<?= base_url('User/Attendance') ?>?date=' + $(this).val();
    });

    $('#tableAttendancePublic').DataTable({
        language: { 
            url: '//cdn.datatables.net/plug-ins/1.13.4/i18n/th.json',
            emptyTable: '<div class="text-center py-4"><i class="bx bx-info-circle fs-1 text-muted opacity-50 mb-2 d-block"></i><p class="text-muted mb-0">ยังไม่มีรายการแจ้งสถานะในวันที่เลือก</p></div>'
        },
        pageLength: 50,
        dom: '<"row mx-2"<"col-md-2"<"me-3"l>><"col-md-10"<"dt-action-buttons text-xl-end text-lg-start text-md-end text-start d-flex align-items-center justify-content-end flex-md-row flex-column mb-3 mb-md-0"fB>>>t<"row mx-2"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
    });
});
</script>
