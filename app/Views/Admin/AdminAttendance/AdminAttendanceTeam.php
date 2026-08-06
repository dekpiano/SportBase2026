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

    /* Mobile First Toggle Group */
    .status-container {
        display: flex;
        background: #f1f5f9;
        padding: 4px;
        border-radius: 14px;
        border: 1px solid #e2e8f0;
        width: 100%;
        max-width: 400px;
    }

    .status-item {
        flex: 1;
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
        font-size: 0.8rem;
        font-weight: 700;
        color: #475569;
        cursor: pointer;
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        text-align: center;
        line-height: 1.2;
    }

    /* Active States */
    .status-item input[value="present"]:checked + .status-btn { background: #10b981; color: white; box-shadow: 0 4px 10px rgba(16, 185, 129, 0.3); }
    .status-item input[value="sick"]:checked + .status-btn { background: #f59e0b; color: white; box-shadow: 0 4px 10px rgba(245, 158, 11, 0.3); }
    .status-item input[value="personal"]:checked + .status-btn { background: #3b82f6; color: white; box-shadow: 0 4px 10px rgba(59, 130, 246, 0.3); }
    .status-item input[value="home"]:checked + .status-btn { background: #ef4444; color: white; box-shadow: 0 4px 10px rgba(239, 68, 68, 0.3); }
    .status-item input[value="competition"]:checked + .status-btn { background: #8b5cf6; color: white; box-shadow: 0 4px 10px rgba(139, 92, 246, 0.3); }

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
        .attendance-header {
            padding: 1.5rem;
            text-align: center;
        }
        .status-container {
            max-width: 100%;
        }
        .status-btn {
            height: 44px;
            font-size: 0.8rem;
        }
        .swal2-popup {
            padding: 1rem 0.75rem !important;
        }
        .swal2-title {
            font-size: 1.1rem !important;
        }
    }
</style>

<div class="container-xxl flex-grow-1 container-p-y">
    <div class="attendance-header">
        <div class="row align-items-center">
            <div class="col-md-8">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-style2 mb-2">
                        <li class="breadcrumb-item"><a href="<?= base_url('Admin/Attendance'); ?>" class="text-white opacity-75">บันทึกการลานักกีฬา</a></li>
                        <li class="breadcrumb-item active text-white" aria-current="page"><?= $team['team_name']; ?></li>
                    </ol>
                </nav>
                <h2 class="text-white fw-bold mb-0"><?= $team['team_name']; ?></h2>
                <div class="mt-2 text-white-50">
                    <span class="me-3"><i class='bx bx-run me-1'></i> <?= $team['team_sport_type']; ?></span>
                    <span><i class='bx bx-group me-1'></i> ทั้งหมด <?= count($athletes); ?> คน</span>
                </div>
            </div>
            <div class="col-md-4 mt-3 mt-md-0">
                <div class="date-card p-3">
                    <label class="form-label fw-bold text-dark small mb-1">เลือกวันที่บันทึกการลา</label>
                    <div class="input-group input-group-merge">
                        <span class="input-group-text border-0 bg-light"><i class='bx bx-calendar-event'></i></span>
                        <input type="text" id="attendanceDate" class="form-control border-0 bg-light fw-bold" value="<?= $selectedDate; ?>">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-12">
            <div class="card sb-card shadow-sm">
                <div class="card-header bg-white border-bottom py-3 d-flex flex-wrap justify-content-between align-items-center gap-3">
                    <h5 class="mb-0 fw-bold text-heading">ระบบลงเวลาการซ้อมประจำวัน</h5>
                    <div class="d-flex gap-2 flex-wrap">
                        <div class="badge bg-label-success rounded-pill px-3 py-2 fw-bold" id="presentBadge">0 อยู่</div>
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
                                <th width="60">เลขที่</th>
                                <th width="120">รูป</th>
                                <th width="120">รหัส</th>
                                <th>ชื่อ - นามสกุล</th>
                                <th width="80" class="text-center">ชั้น</th>
                                <th width="450">บันทึกสถานะ</th>
                                <th width="150" class="text-center">หมายเหตุ</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($athletes)) : ?>
                                <?php $i = 1; foreach ($athletes as $row) : 
                                    $currentStatus = isset($attendanceMap[$row['StudentID']]) ? $attendanceMap[$row['StudentID']]['att_status'] : 'present';
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
                                        <td><code><?= $row['StudentCode']; ?></code></td>
                                        <td>
                                            <span class="fw-semibold d-block text-dark"><?= $row['StudentPrefix'] . $row['StudentFirstName'] . ' ' . $row['StudentLastName']; ?></span>
                                            <?php if (isset($attendanceMap[$row['StudentID']])): 
                                                $att = $attendanceMap[$row['StudentID']];
                                                if (!empty($att['att_start_date']) && !empty($att['att_end_date']) && $att['att_start_date'] !== $att['att_end_date']): ?>
                                                    <small class="text-primary d-block fw-bold" style="font-size: 0.7rem;">
                                                        <i class="bx bx-calendar-event"></i> ลา: <?= date('d/m', strtotime($att['att_start_date'])) . '/' . (date('y', strtotime($att['att_start_date'])) + 43); ?> - <?= date('d/m', strtotime($att['att_end_date'])) . '/' . (date('y', strtotime($att['att_end_date'])) + 43); ?>
                                                    </small>
                                                <?php endif; ?>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-center small"><?= $row['StudentClass']; ?></td>
                                        <td>
                                            <div class="status-container">
                                                <div class="status-item">
                                                    <input type="radio" class="status-btn-radio" name="st_<?= $row['StudentID']; ?>" id="p_<?= $row['StudentID']; ?>" value="present" <?= ($currentStatus == 'present') ? 'checked' : ''; ?>>
                                                    <label class="status-btn" for="p_<?= $row['StudentID']; ?>">อยู่</label>
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
                            $currentStatus = isset($attendanceMap[$row['StudentID']]) ? $attendanceMap[$row['StudentID']]['att_status'] : 'present';
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
                                    <div class="flex-grow-1">
                                        <div class="fw-bold text-dark lh-1 mb-1" style="font-size: 1rem;"><?= $row['StudentPrefix'] . $row['StudentFirstName'] . ' ' . $row['StudentLastName']; ?></div>
                                        <div class="text-muted small">
                                            <span class="me-2">#<?= $row['StudentCode']; ?></span>
                                            <span>ชั้น: <?= $row['StudentClass']; ?></span>
                                        </div>
                                        <?php if (isset($attendanceMap[$row['StudentID']])): 
                                            $att = $attendanceMap[$row['StudentID']];
                                            if (!empty($att['att_start_date']) && !empty($att['att_end_date']) && $att['att_start_date'] !== $att['att_end_date']): ?>
                                                <small class="text-primary d-block fw-bold mt-1" style="font-size: 0.7rem;">
                                                    <i class="bx bx-calendar-event"></i> ลา: <?= date('d/m', strtotime($att['att_start_date'])) . '/' . (date('y', strtotime($att['att_start_date'])) + 43); ?> - <?= date('d/m', strtotime($att['att_end_date'])) . '/' . (date('y', strtotime($att['att_end_date'])) + 43); ?>
                                                </small>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <div class="status-container mx-auto">
                                    <div class="status-item">
                                        <input type="radio" class="status-btn-radio" name="mst_<?= $row['StudentID']; ?>" id="mp_<?= $row['StudentID']; ?>" value="present" <?= ($currentStatus == 'present') ? 'checked' : ''; ?>>
                                        <label class="status-btn" for="mp_<?= $row['StudentID']; ?>">อยู่</label>
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
        onChange: function(selectedDates, dateStr) {
            window.location.href = '<?= base_url('Admin/Attendance/Team/' . $team['team_id']); ?>?date=' + dateStr;
        },
        onReady: function(selectedDates, dateStr, instance) {
            if (instance.calendarContainer) {
                const el = instance.calendarContainer.querySelector('.cur-year');
                if (el) el.textContent = instance.currentYear + 543;
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
                const el = instance.calendarContainer.querySelector('.cur-year');
                if (el) el.textContent = instance.currentYear + 543;
            }
        }
    });

    // Initial counts - นับเฉพาะ Desktop radio (st_) เพื่อไม่ให้นับซ้ำกับ Mobile (mst_)
    function updateLiveStats() {
        let p = 0, s = 0, pe = 0, h = 0, c = 0;
        $('input.status-btn-radio:checked').each(function() {
            const name = $(this).attr('name');
            if (name && name.startsWith('st_')) {
                const val = $(this).val();
                if(val === 'present') p++;
                else if(val === 'sick') s++;
                else if(val === 'personal') pe++;
                else if(val === 'home') h++;
                else if(val === 'competition') c++;
            }
        });
        $('#presentBadge').text(p + ' อยู่');
        $('#sickBadge').text(s + ' ป่วย');
        $('#personalBadge').text(pe + ' กิจธุระ');
        $('#homeBadge').text(h + ' กลับบ้าน');
        $('#competitionBadge').text(c + ' แข่งขัน');
    }
    updateLiveStats();

    // AJAX Save handler
    $(document).on('change', '.status-btn-radio', function() {
        const row = $(this).closest('.athlete-row');
        const val = $(this).val();
        const studentId = row.data('student-id');
        
        // Sync mobile and desktop radios
        $(`.status-btn-radio[name="st_${studentId}"], .status-btn-radio[name="mst_${studentId}"]`).filter(`[value="${val}"]`).prop('checked', true);
        
        const prevVal = $(this).data('prev') || 'present';

        if (val === 'present') {
            saveStudentAttendance(row);
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
                    $(`.status-btn-radio[name="st_${studentId}"], .status-btn-radio[name="mst_${studentId}"]`).filter(`[value="${prevVal}"]`).prop('checked', true);
                    updateLiveStats();
                }
            });
        }
    });

    // Store previous value before change
    $('.status-btn-radio').on('click', function() {
        const name = $(this).attr('name');
        const currentChecked = $(this).closest('.status-container').find('.status-btn-radio:checked').val();
        $(this).data('prev', currentChecked);
    });

    function saveStudentAttendance(row, endDate = null) {
        const studentId = row.data('student-id');
        const status = row.find('.status-btn-radio:checked').val();
        const date = $('#attendanceDate').val();

        $.ajax({
            url: '<?= base_url('Admin/Attendance/Save'); ?>',
            method: 'POST',
            data: {
                student_id: studentId,
                team_id: teamId,
                status: status,
                note: '',
                date: date,
                end_date: endDate
            },
            success: function(res) {
                if(res.success) {
                    // ถ้าเปลี่ยนเป็น "อยู่" ให้ลบป้ายวันลาและหมายเหตุออกจาก UI ทันที
                    if (status === 'present') {
                        // ลบป้ายวันลาทั้ง Desktop และ Mobile ที่ตรงกับ student ID เดียวกัน
                        $(`.athlete-row[data-student-id="${studentId}"]`).each(function() {
                            $(this).find('.text-primary.fw-bold').remove();  // ป้ายช่วงวันลา
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
                }
            },
            error: function() {
                Swal.fire('Error', 'ไม่สามารถบันทึกข้อมูลได้', 'error');
            }
        });
    }

    function saveRangeAttendance(row, data) {
        $.ajax({
            url: '<?= base_url('Admin/Attendance/Save'); ?>',
            method: 'POST',
            data: {
                student_id: row.data('student-id'),
                team_id: teamId,
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
