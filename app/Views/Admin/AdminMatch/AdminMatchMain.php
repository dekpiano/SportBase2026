<?= $this->extend('Admin/layout/AdminLayout') ?>

<?= $this->section('content') ?>
<style>
    .match-header {
        background: linear-gradient(135deg, #fd7e14 0%, #ff9e43 100%);
        border-radius: 20px;
        padding: 3rem;
        color: white;
        margin-bottom: 2rem;
        box-shadow: 0 10px 30px rgba(253, 126, 20, 0.25);
        position: relative;
    }

    .btn-add-match {
        background: #fff;
        color: #fd7e14 !important;
        border: none;
        padding: 0.8rem 2rem;
        font-weight: 800;
        letter-spacing: 0.5px;
        box-shadow: 0 6px 20px rgba(0,0,0,0.15);
        transition: all 0.3s ease;
    }

    .btn-add-match:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.2);
        background: #fff;
        color: #e66a00 !important;
    }

    .match-card {
        border-radius: 15px;
        border: none;
        transition: all 0.3s ease;
    }

    .match-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 35px rgba(0,0,0,0.1);
    }

    .status-badge {
        padding: 6px 12px;
        border-radius: 10px;
        font-weight: 600;
        font-size: 0.8rem;
    }

    .stat-icon {
        width: 45px;
        height: 45px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
</style>

<div class="container-xxl flex-grow-1 container-p-y">
    <div class="match-header">
        <div class="row align-items-center">
            <div class="col-md-8">
                <h2 class="text-white fw-bold mb-2">ตารางการแข่งขัน</h2>
                <p class="text-white-50 mb-0">จัดการตารางการแข่งขันและแจ้งข่าวสารการแข่งขันของรุ่นนักกีฬาต่างๆ</p>
            </div>
            <div class="col-md-4 text-md-end mt-3 mt-md-0">
                <button class="btn btn-add-match rounded-pill" data-bs-toggle="modal" data-bs-target="#modalMatch">
                    <i class='bx bx-plus-circle me-1'></i> เพิ่มตารางแข่งขันใหม่
                </button>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-20">
        <div class="card-body p-4">
            <div class="table-responsive">
                <table class="table table-hover align-middle" id="tableMatch">
                    <thead>
                        <tr>
                            <th width="50">#</th>
                            <th>รายการแข่งขัน / สถานที่</th>
                            <th>รุ่น / ทีม</th>
                            <th>วันที่ - เวลา</th>
                            <th class="text-center">สถานะ</th>
                            <th class="text-end">จัดการ</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $i = 1; foreach ($matches as $row) : ?>
                            <tr>
                                <td><?= $i++; ?></td>
                                <td>
                                    <div class="fw-bold text-dark"><?= $row['match_title']; ?></div>
                                    <small class="text-muted"><i class='bx bx-map-pin me-1'></i><?= $row['match_location']; ?></small>
                                </td>
                                <td>
                                    <span class="badge bg-label-primary rounded-pill"><?= $row['team_name']; ?></span>
                                    <div class="small text-muted mt-1"><?= $row['team_sport_type']; ?></div>
                                </td>
                                <td>
                                    <div class="fw-semibold text-primary"><i class='bx bx-calendar me-1'></i><?= date('d/m/Y', strtotime($row['match_date'])); ?></div>
                                    <small class="text-muted"><i class='bx bx-time me-1'></i><?= $row['match_time'] ? date('H:i', strtotime($row['match_time'])) . ' น.' : '-'; ?></small>
                                </td>
                                <td class="text-center">
                                    <?php 
                                    $statusColor = [
                                        'Upcoming' => 'info',
                                        'In Progress' => 'warning',
                                        'Finished' => 'success',
                                        'Canceled' => 'danger'
                                    ];
                                    $statusText = [
                                        'Upcoming' => 'รอดำเนินการ',
                                        'In Progress' => 'กำลังแข่งขัน',
                                        'Finished' => 'แข่งขันเสร็จสิ้น',
                                        'Canceled' => 'ยกเลิก'
                                    ];
                                    $color = $statusColor[$row['match_status']] ?? 'secondary';
                                    $text = $statusText[$row['match_status']] ?? $row['match_status'];
                                    ?>
                                    <span class="status-badge bg-label-<?= $color ?>"><?= $text ?></span>
                                </td>
                                <td class="text-end">
                                    <div class="dropdown">
                                         <?php 
                                         $canManage = true;
                                         if ($allowedTeamIds !== null && !in_array($row['team_id'], $allowedTeamIds)) {
                                             $canManage = false;
                                         }
                                         ?>
                                         
                                         <?php if ($canManage): ?>
                                         <button class="btn p-0" type="button" data-bs-toggle="dropdown">
                                             <i class="bx bx-dots-vertical-rounded"></i>
                                         </button>
                                         <div class="dropdown-menu dropdown-menu-end">
                                             <a class="dropdown-item edit-match" href="javascript:void(0);" 
                                                data-id="<?= $row['match_id'] ?>" 
                                                data-team="<?= $row['team_id'] ?>"
                                                data-title="<?= $row['match_title'] ?>"
                                                data-location="<?= $row['match_location'] ?>"
                                                data-date="<?= $row['match_date'] ?>"
                                                data-time="<?= $row['match_time'] ?>"
                                                data-status="<?= $row['match_status'] ?>"
                                                data-note="<?= $row['match_note'] ?>">
                                                 <i class="bx bx-edit-alt me-1"></i> แก้ไข
                                             </a>
                                             <a class="dropdown-item text-danger delete-match" href="javascript:void(0);" data-id="<?= $row['match_id'] ?>">
                                                 <i class="bx bx-trash me-1"></i> ลบ
                                             </a>
                                         </div>
                                         <?php else: ?>
                                            <button class="btn p-0 text-muted" type="button" disabled title="ไม่มีสิทธิ์แก้ไข">
                                                <i class="bx bx-lock-alt"></i>
                                            </button>
                                         <?php endif; ?>
                                     </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal Match -->
<div class="modal fade" id="modalMatch" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-20">
            <div class="modal-header bg-primary py-3">
                <h5 class="modal-title text-white fw-bold"><i class='bx bx-trophy me-2'></i>ข้อมูลการแข่งขัน</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="formMatch">
                <div class="modal-body p-4">
                    <input type="hidden" name="match_id" id="match_id">
                    
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label fw-bold">รุ่น / ทีม <span class="text-danger">*</span></label>
                            <select name="team_id" id="team_id" class="form-select select2-modal" required>
                                <option value="">-- เลือกทีม --</option>
                                <?php foreach($teams as $t): ?>
                                    <option value="<?= $t['team_id'] ?>"><?= $t['team_name'] ?> (<?= $t['team_sport_type'] ?>)</option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-bold">รายการแข่งขัน <span class="text-danger">*</span></label>
                            <input type="text" name="match_title" id="match_title" class="form-control" placeholder="เช่น ฟุตบอลมวลชนอำเภอ รอบรองชนะเลิศ" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-bold">สถานที่แข่งขัน</label>
                            <input type="text" name="match_location" id="match_location" class="form-control" placeholder="เช่น สนามกีฬาจังหวัดนครสวรรค์">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">วันที่แข่งขัน <span class="text-danger">*</span></label>
                            <input type="text" name="match_date" id="match_date" class="form-control date-picker" placeholder="เลือกวันที่" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">เวลาแข่งขัน</label>
                            <input type="time" name="match_time" id="match_time" class="form-control">
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-bold">สถานะ</label>
                            <select name="match_status" id="match_status" class="form-select">
                                <option value="Upcoming">รอดำเนินการ (Upcoming)</option>
                                <option value="In Progress">กำลังแข่งขัน (In Progress)</option>
                                <option value="Finished">แข่งขันเสร็จสิ้น (Finished)</option>
                                <option value="Canceled">ยกเลิก (Canceled)</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-bold">หมายเหตุ</label>
                            <textarea name="match_note" id="match_note" class="form-control" rows="2" placeholder="รายละเอียดเพิ่มเติม..."></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-top-0 pb-4 px-4">
                    <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">ยกเลิก</button>
                    <button type="submit" class="btn btn-primary px-5 py-2 fw-bold shadow">
                        <i class='bx bx-save me-1'></i> บันทึกข้อมูลการแข่งขัน
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('script') ?>
<script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/th.js"></script>
<script>
$(document).ready(function() {
    $('#tableMatch').DataTable({
        language: { url: '//cdn.datatables.net/plug-ins/1.13.4/i18n/th.json' },
        order: [[3, 'asc']]
    });

    $('.select2-modal').select2({
        dropdownParent: $('#modalMatch'),
        width: '100%'
    });

    const fpMatchDate = flatpickr("#match_date", {
        locale: "th",
        dateFormat: "Y-m-d",
        altInput: true,
        altFormat: "j F Y",
        onReady: function(selectedDates, dateStr, instance) {
            const year = instance.currentYear + 543;
            const curYear = instance.calendarContainer.querySelector('.cur-year');
            if(curYear) curYear.textContent = year;
        }
    });

    // Save handler
    $('#formMatch').on('submit', function(e) {
        e.preventDefault();
        $.ajax({
            url: '<?= base_url('Admin/Match/Save') ?>',
            method: 'POST',
            data: $(this).serialize(),
            success: function(res) {
                if(res.success) {
                    $('#modalMatch').modal('hide');
                    Swal.fire('สำเร็จ', res.message, 'success').then(() => location.reload());
                }
            }
        });
    });

    // Clear form for new match
    $('.btn-add-match').on('click', function() {
        $('#formMatch')[0].reset();
        $('#match_id').val('');
        $('#team_id').val('').trigger('change');
        fpMatchDate.clear();
        $('.modal-title').html("<i class='bx bx-plus-circle me-2'></i>เพิ่มตารางการแข่งขัน");
    });

    // Edit handler
    $('.edit-match').on('click', function() {
        const d = $(this).data();
        $('.modal-title').html("<i class='bx bx-edit-alt me-2'></i>แก้ไขตารางการแข่งขัน");
        $('#match_id').val(d.id);
        $('#team_id').val(d.team).trigger('change');
        $('#match_title').val(d.title);
        $('#match_location').val(d.location);
        fpMatchDate.setDate(d.date);
        $('#match_time').val(d.time);
        $('#match_status').val(d.status);
        $('#match_note').val(d.note);
        $('#modalMatch').modal('show');
    });

    // Delete handler
    $('.delete-match').on('click', function() {
        const id = $(this).data('id');
        Swal.fire({
            title: 'ยืนยันการลบ?',
            text: "ข้อมูลการแข่งขันจะหายไปและไม่สามารถกู้คืนได้",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            confirmButtonText: 'ลบข้อมูล'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '<?= base_url('Admin/Match/Delete') ?>/' + id,
                    method: 'GET',
                    success: function(res) {
                        if(res.success) {
                            Swal.fire('ลบแล้ว!', res.message, 'success').then(() => location.reload());
                        }
                    }
                });
            }
        });
    });
});
</script>
<?= $this->endSection() ?>
