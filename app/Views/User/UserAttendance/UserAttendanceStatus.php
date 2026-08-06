<style>
    :root {
        --sb-orange: #fd7e14;
        --sb-orange-grad: linear-gradient(135deg, #fd7e14 0%, #ff9e43 100%);
    }

    .stat-card-attendance {
        border: none;
        border-radius: 16px;
        transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.03);
    }
    
    .stat-card-attendance:hover {
        transform: translateY(-4px);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08);
    }

    .pulse-online {
        display: inline-block;
        width: 8px;
        height: 8px;
        background: #28a745;
        border-radius: 50%;
        margin-right: 4px;
        box-shadow: 0 0 0 rgba(40, 167, 69, 0.4);
        animation: pulse-active 2s infinite;
    }

    @keyframes pulse-active {
        0% { box-shadow: 0 0 0 0 rgba(40, 167, 69, 0.4); }
        70% { box-shadow: 0 0 0 8px rgba(40, 167, 69, 0); }
        100% { box-shadow: 0 0 0 0 rgba(40, 167, 69, 0); }
    }

    .mobile-attendance-card {
        background: #ffffff;
        border-radius: 16px;
        padding: 1.15rem;
        margin-bottom: 0.85rem;
        border: 1px solid #edf2f7;
        box-shadow: 0 3px 12px rgba(0,0,0,0.03);
        transition: all 0.2s ease;
    }

    .mobile-attendance-card:hover {
        border-color: rgba(253, 126, 20, 0.3);
        box-shadow: 0 6px 18px rgba(253, 126, 20, 0.08);
    }

    .datepicker-trigger-btn {
        background: #ffffff;
        border: 1px solid #edf2f7;
        border-radius: 30px;
        padding: 8px 16px;
        font-weight: 600;
        color: #475569;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.02);
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .datepicker-trigger-btn:hover {
        border-color: #fd7e14;
        color: #fd7e14;
    }

    .search-wrap {
        position: relative;
    }

    .search-wrap i {
        position: absolute;
        top: 50%;
        left: 14px;
        transform: translateY(-50%);
        color: #94a3b8;
    }

    .search-wrap input {
        padding-left: 40px;
        border-radius: 30px;
        border: 1px solid #edf2f7;
    }

    .search-wrap input:focus {
        border-color: #fd7e14;
        box-shadow: 0 0 0 3px rgba(253, 126, 20, 0.15);
    }
</style>

<div class="layout-page">
    <?php echo view('User/UserLeyout/UserNavbar'); ?>

    <div class="content-wrapper">
        <div class="container-xxl flex-grow-1 container-p-y">
            
            <!-- Page Header -->
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
                <div>
                    <h4 class="fw-bold mb-1 text-dark">
                        สถานะภาพและข้อมูลการลาประจำวัน 📅
                    </h4>
                    <p class="text-muted mb-0 small">ติดตามความเคลื่อนไหวและการแจ้งยอดนักกีฬา</p>
                </div>
                <div class="d-flex align-items-center">
                    <div class="input-group input-group-merge" style="width: 260px; border-radius: 30px; overflow: hidden; box-shadow: 0 4px 15px rgba(0,0,0,0.04);">
                        <span class="input-group-text bg-white border-end-0"><i class="bx bx-calendar text-orange fs-5"></i></span>
                        <input type="text" id="attendanceDate" class="form-control bg-white border-start-0 fw-semibold cursor-pointer py-2" value="<?= $selectedDate; ?>" data-base-url="<?= base_url('User/Attendance') ?>" readonly style="cursor: pointer;">
                    </div>
                </div>
            </div>

            <!-- Summary Stats Widgets -->
            <div class="row g-3 mb-4">
                <!-- Present Count -->
                <div class="col-6 col-md-3">
                    <div class="card stat-card-attendance bg-label-success border-0">
                        <div class="card-body d-flex align-items-center gap-2.5 py-3">
                            <div class="avatar avatar-sm flex-shrink-0">
                                <span class="avatar-initial rounded-circle bg-success"><i class='bx bx-check-double fs-5'></i></span>
                            </div>
                            <div class="flex-grow-1">
                                <div class="d-flex align-items-baseline gap-1">
                                    <h4 class="fw-bold mb-0 text-success"><?= $totalPresent ?></h4>
                                    <span class="text-dark fw-semibold small">อยู่ปฏิบัติหน้าที่</span>
                                </div>
                                <small class="text-muted" style="font-size: 0.65rem;"><span class="pulse-online"></span> สมาชิกพร้อมซ้อม</small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Custom statuses loop -->
                <?php foreach($statuses as $key => $status): 
                    $count = 0;
                    foreach($summary as $s) { if($s['att_status'] == $key) $count = $s['count']; }
                ?>
                <div class="col-6 col-md-3">
                    <div class="card stat-card-attendance bg-label-<?= $status['color'] ?> border-0">
                        <div class="card-body d-flex align-items-center gap-2.5 py-3">
                            <div class="avatar avatar-sm flex-shrink-0">
                                <span class="avatar-initial rounded-circle bg-<?= $status['color'] ?>"><i class='bx <?= $status['icon'] ?> fs-5'></i></span>
                            </div>
                            <div class="flex-grow-1">
                                <div class="d-flex align-items-baseline gap-1">
                                    <h4 class="fw-bold mb-0 text-<?= $status['color'] ?>"><?= $count ?></h4>
                                    <span class="text-dark fw-semibold small"><?= $status['label'] ?></span>
                                </div>
                                <small class="text-muted" style="font-size: 0.65rem;">ประจำวันนี้</small>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>

            <!-- List Section -->
            <div class="card border-0 shadow-sm rounded-20 overflow-hidden">
                <div class="card-header border-bottom py-3 d-flex justify-content-between align-items-center flex-wrap gap-2 bg-white">
                    <div class="d-flex align-items-center gap-2">
                        <h5 class="card-title mb-0 fw-bold text-dark"><i class='bx bx-list-ol me-1 text-orange'></i>นักกีฬาที่ไม่ได้ปฏิบัติการซ้อม</h5>
                        <span class="badge bg-label-warning text-orange rounded-pill px-3 py-1fw-bold"><?= count($attendanceList) ?> คน</span>
                    </div>
                    <div class="search-wrap" style="min-width: 250px;">
                        <i class="bx bx-search fs-5"></i>
                        <input type="text" id="attendanceSearch" class="form-control py-1.5" placeholder="ค้นหาชื่อ, รุ่น หรือสถานะ...">
                    </div>
                </div>
                
                <div class="card-body p-0">
                    <?php if (empty($attendanceList)): ?>
                        <div class="text-center py-5">
                            <i class='bx bx-check-circle fs-1 text-success mb-2 d-block'></i>
                            <h6 class="text-secondary mb-0">ในวันที่เลือก นักกีฬาทุกคนมาฝึกซ้อมครบถ้วน</h6>
                        </div>
                    <?php else: ?>
                        
                        <!-- Desktop View Table -->
                        <div class="table-responsive d-none d-md-block">
                            <table class="table table-hover align-middle mb-0" id="desktopAttendanceTable">
                                <thead class="bg-light">
                                    <tr>
                                        <th class="ps-4">นักกีฬา</th>
                                        <th>รุ่น / สังกัดกีฬา</th>
                                        <th class="text-center">สถานะการลา</th>
                                        <th>ระยะเวลาการลา</th>
                                        <th class="pe-4">รายละเอียดเพิ่มเติม</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($attendanceList as $att): 
                                        $sInfo = $statuses[$att['att_status']];
                                        
                                        // Thai Date range format with +543 year offset
                                        $yStart = date('Y', strtotime($att['att_start_date'])) + 543;
                                        $yEnd = date('Y', strtotime($att['att_end_date'])) + 543;
                                        
                                        $dateRangeStr = date('d/m', strtotime($att['att_start_date'])) . '/' . $yStart;
                                        if ($att['att_start_date'] !== $att['att_end_date']) {
                                            $dateRangeStr .= ' - ' . date('d/m', strtotime($att['att_end_date'])) . '/' . $yEnd;
                                        }
                                    ?>
                                        <tr class="searchable-att-item">
                                            <td class="ps-4">
                                                <div class="d-flex align-items-center">
                                                    <img src="https://skj.ac.th/uploads/students_photo/<?= $att['StudentCode'] ?>.jpg" 
                                                         onerror="this.src='https://ui-avatars.com/api/?name=<?= urlencode($att['StudentFirstName']) ?>&background=random'" 
                                                         class="rounded-circle border me-3" style="width: 40px; height: 50px; object-fit: cover;">
                                                    <div>
                                                        <div class="fw-bold text-dark mb-0"><?= $att['StudentPrefix'].$att['StudentFirstName'].' '.$att['StudentLastName'] ?></div>
                                                        <small class="text-muted">เลขประจำตัว: #<?= $att['StudentCode'] ?> • ม.<?= $att['StudentClass'] ?></small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="badge bg-label-primary"><?= htmlspecialchars($att['team_name'], ENT_QUOTES) ?></span>
                                                <div class="small text-muted mt-1"><?= htmlspecialchars($att['team_sport_type'], ENT_QUOTES) ?></div>
                                            </td>
                                            <td class="text-center">
                                                <span class="badge bg-label-<?= $sInfo['color'] ?> px-3.5 py-2 fw-semibold">
                                                    <i class='bx <?= $sInfo['icon'] ?> me-1'></i> <?= $sInfo['label'] ?>
                                                </span>
                                            </td>
                                            <td>
                                                <div class="fw-semibold text-primary"><i class="bx bx-calendar me-1"></i><?= $dateRangeStr ?></div>
                                                <small class="text-muted"><i class="bx bx-time me-1"></i>บันทึกเมื่อ: <?= date('H:i', strtotime($att['att_time'])) ?> น.</small>
                                            </td>
                                            <td class="pe-4">
                                                <span class="small text-secondary lh-sm d-inline-block"><?= htmlspecialchars($att['att_note'] ?: '-', ENT_QUOTES) ?></span>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>

                        <!-- Mobile View Compact Cards -->
                        <div class="p-3 d-md-none" id="mobileAttendanceCards">
                            <?php foreach ($attendanceList as $att): 
                                $sInfo = $statuses[$att['att_status']];
                                
                                $yStart = date('Y', strtotime($att['att_start_date'])) + 543;
                                $yEnd = date('Y', strtotime($att['att_end_date'])) + 543;
                                
                                $dateRangeStr = date('d/m', strtotime($att['att_start_date'])) . '/' . $yStart;
                                if ($att['att_start_date'] !== $att['att_end_date']) {
                                    $dateRangeStr .= ' - ' . date('d/m', strtotime($att['att_end_date'])) . '/' . $yEnd;
                                }
                            ?>
                                <div class="mobile-attendance-card searchable-att-item">
                                    <div class="d-flex align-items-center mb-3">
                                        <img src="https://skj.ac.th/uploads/students_photo/<?= $att['StudentCode'] ?>.jpg" 
                                             onerror="this.src='https://ui-avatars.com/api/?name=<?= urlencode($att['StudentFirstName']) ?>&background=random'" 
                                             class="rounded-circle border me-3" style="width: 36px; height: 45px; object-fit: cover;">
                                        <div class="flex-grow-1">
                                            <h6 class="fw-bold text-dark mb-0"><?= $att['StudentPrefix'].$att['StudentFirstName'].' '.$att['StudentLastName'] ?></h6>
                                            <small class="text-muted">ม.<?= htmlspecialchars($att['StudentClass'], ENT_QUOTES) ?> • <?= htmlspecialchars($att['team_name'], ENT_QUOTES) ?></small>
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center mb-2 flex-wrap gap-1">
                                        <span class="badge bg-label-<?= $sInfo['color'] ?> py-1.5 px-3 fw-bold">
                                            <i class='bx <?= $sInfo['icon'] ?> me-1'></i> <?= $sInfo['label'] ?>
                                        </span>
                                        <span class="small text-primary fw-semibold"><i class="bx bx-calendar me-1"></i><?= $dateRangeStr ?></span>
                                    </div>
                                    <div class="bg-light p-2 rounded-10 text-secondary small mb-1" style="font-size: 0.76rem;">
                                        <strong>หมายเหตุ:</strong> <?= htmlspecialchars($att['att_note'] ?: '-', ENT_QUOTES) ?>
                                    </div>
                                    <div class="text-end" style="font-size: 0.65rem; color: #94a3b8;">
                                        <i class="bx bx-time"></i> บันทึกเมื่อ: <?= date('H:i', strtotime($att['att_time'])) ?> น.
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>

                    <?php endif; ?>
                </div>
            </div>

        </div>
    </div>
</div>

<script>
// Client-side quick filter logic
(function() {
    var searchInput = document.getElementById('attendanceSearch');
    if (searchInput) {
        searchInput.addEventListener('keyup', function() {
            var term = this.value.toLowerCase().trim();
            var items = document.querySelectorAll('.searchable-att-item');
            items.forEach(function(item) {
                var text = item.textContent.toLowerCase();
                item.style.display = text.includes(term) ? '' : 'none';
            });
        });
    }
})();
</script>
