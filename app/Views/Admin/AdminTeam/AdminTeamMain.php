<?= $this->extend('Admin/layout/AdminLayout') ?>

<?= $this->section('content') ?>
<style>
    /* Premium Design System */
    :root {
        --sb-primary: #fd7e14;
        --sb-primary-gradient: linear-gradient(135deg, #fd7e14 0%, #ff9e43 100%);
        --sb-glass: rgba(255, 255, 255, 0.9);
        --sb-shadow: 0 10px 30px rgba(0,0,0,0.08);
    }

    .premium-page-header {
        background: var(--sb-primary-gradient);
        border-radius: 24px;
        color: white;
        padding: 3rem 2.5rem;
        margin-bottom: 2.5rem;
        position: relative;
        overflow: hidden;
        box-shadow: 0 15px 35px rgba(253, 126, 20, 0.25);
    }

    .premium-header-content {
        position: relative;
        z-index: 2;
    }

    .header-illustration {
        position: absolute;
        right: 2rem;
        top: 50%;
        transform: translateY(-50%);
        height: 140%;
        opacity: 0.15;
        mix-blend-mode: overlay;
        pointer-events: none;
    }

    .btn-create-premium {
        background: white;
        color: var(--sb-primary);
        font-weight: 700;
        padding: 0.85rem 2rem;
        border-radius: 16px;
        border: none;
        transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    }

    .btn-create-premium:hover {
        transform: translateY(-5px) scale(1.02);
        box-shadow: 0 8px 25px rgba(0,0,0,0.15);
        background: #fff;
        color: #e66a00;
    }

    .sb-card {
        border: none;
        border-radius: 24px;
        box-shadow: var(--sb-shadow);
        background: var(--sb-glass);
        backdrop-filter: blur(10px);
        transition: all 0.3s ease;
    }

    /* Desktop Table Polishing */
    .sb-table thead th {
        background: #f8faff;
        color: #566a7f;
        font-weight: 700;
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 0.8px;
        padding: 1.25rem 1rem;
        border: none;
    }

    .sb-table tbody tr {
        transition: all 0.2s;
        border-bottom: 1px solid #f1f4f8;
    }

    .sb-table tbody tr:hover {
        background-color: #fcfdff;
        transform: scale(1.002);
    }

    .sb-table td {
        padding: 1.25rem 1rem;
        vertical-align: middle;
    }

    .sport-type-badge {
        background: #eef2ff;
        color: #4f46e5;
        font-weight: 700;
        padding: 0.5rem 1rem;
        border-radius: 10px;
        font-size: 0.75rem;
    }

    /* Mobile Enhancements */
    .mobile-team-item {
        background: white;
        border-radius: 20px;
        padding: 1.5rem;
        margin-bottom: 1.25rem;
        border: 1px solid #edf2f7;
        box-shadow: 0 4px 12px rgba(0,0,0,0.03);
        text-decoration: none;
        color: inherit;
        display: block;
        transition: all 0.2s;
    }

    .mobile-team-item:active {
        transform: scale(0.97);
        background: #fdfdfd;
    }

    .mobile-icon-box {
        width: 54px;
        height: 54px;
        background: var(--sb-primary-gradient);
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.6rem;
        box-shadow: 0 6px 12px rgba(253, 126, 20, 0.2);
    }

    .stat-badge-sm {
        font-size: 0.7rem;
        font-weight: 700;
        padding: 0.4rem 0.8rem;
        border-radius: 8px;
    }

    @media (max-width: 768px) {
        .premium-page-header {
            padding: 2.5rem 1.5rem;
            text-align: center;
            border-radius: 0 0 30px 30px;
            margin: -1.5rem -1rem 2rem -1rem;
        }
        .header-illustration {
            display: none;
        }
        .btn-create-premium {
            position: fixed;
            bottom: 2rem;
            right: 1.5rem;
            border-radius: 50px;
            padding: 1rem 1.8rem;
            z-index: 1000;
            box-shadow: 0 10px 25px rgba(253, 126, 20, 0.4);
            background: var(--sb-primary-gradient);
            color: white;
            width: auto;
        }
    }
</style>

<div class="container-xxl flex-grow-1 container-p-y">
    <!-- Premium Header -->
    <div class="premium-page-header">
        <img src="<?= base_url('assets/img/illustrations/athlete-silhouette.png') ?>" class="header-illustration" alt="">
        <div class="premium-header-content">
            <h2 class="text-white fw-bold mb-2">จัดการรุ่นนักกีฬา</h2>
            <p class="text-white opacity-75 mb-4 fs-5">ขับเคลื่อนศักยภาพทีมของคุณ ด้วยระบบบริหารจัดการที่แม่นยำ</p>
            <button type="button" class="btn btn-create-premium" data-bs-toggle="modal" data-bs-target="#modalAddTeam">
                <i class="bx bx-plus-circle me-2 fs-4"></i> เพิ่มรุ่นนักกีฬาใหม่
            </button>
        </div>
    </div>

    <?php if (session()->getFlashdata('success')) : ?>
        <div class="alert alert-success border-0 shadow-sm d-flex align-items-center mb-4 rounded-16" role="alert">
            <i class='bx bxs-check-circle me-3 fs-3'></i>
            <div class="fw-bold"><?= session()->getFlashdata('success'); ?></div>
            <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <!-- Desktop View: Polished Table -->
    <div class="card sb-card d-none d-md-block overflow-hidden">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table sb-table mb-0" id="tableTeam">
                    <thead>
                        <tr>
                            <th class="ps-4">รุ่น / ชื่อทีม</th>
                            <th>ประเภทกีฬา</th>
                            <th class="text-center">ปีการศึกษา</th>
                            <th class="text-center">ขุมกำลัง</th>
                            <th class="text-center">สถานะ</th>
                            <th class="text-center pe-4">ตัวเลือก</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($teams as $row) : ?>
                            <tr>
                                <td class="ps-4">
                                    <div class="d-flex align-items-center">
                                        <div class="avatar avatar-md me-3">
                                            <span class="avatar-initial rounded-14 bg-label-primary shadow-xs">
                                                <i class="bx bx-group fs-4"></i>
                                            </span>
                                        </div>
                                        <div>
                                            <div class="fw-bold text-dark fs-6"><?= $row['team_name']; ?></div>
                                            <small class="text-muted">ID: #<?= str_pad($row['team_id'], 4, '0', STR_PAD_LEFT); ?></small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="sport-type-badge shadow-xs"><?= $row['team_sport_type']; ?></span>
                                </td>
                                <td class="text-center fw-bold text-primary">
                                    <?= $row['team_year'] ?: '-'; ?>
                                </td>
                                <td class="text-center">
                                    <div class="d-flex justify-content-center gap-2">
                                        <div class="badge bg-label-info stat-badge-sm" title="นักกีฬา">
                                            <i class='bx bx-run me-1'></i> <?= $row['athlete_count']; ?>
                                        </div>
                                        <div class="badge bg-label-secondary stat-badge-sm" title="โค้ช">
                                            <i class='bx bx-user-voice me-1'></i> <?= $row['coach_count']; ?>
                                        </div>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <span class="badge rounded-pill <?= $row['team_status'] == 'ใช้งาน' ? 'bg-label-success' : 'bg-label-secondary'; ?> px-3 p-2">
                                        <?= $row['team_status'] == 'ใช้งาน' ? 'กำลังใช้งาน' : 'ปิดการใช้งาน'; ?>
                                    </span>
                                </td>
                                <td class="text-center pe-4">
                                    <div class="dropdown">
                                        <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                            <i class="bx bx-dots-vertical-rounded fs-4"></i>
                                        </button>
                                        <div class="dropdown-menu dropdown-menu-end shadow-lg border-0 rounded-16">
                                            <a class="dropdown-item py-2" href="<?= base_url('Admin/Team/Detail/' . $row['team_id']); ?>">
                                                <i class="bx bx-edit-alt me-2 text-primary"></i> จัดการข้อมูล
                                            </a>
                                            <div class="dropdown-divider"></div>
                                            <button type="button" class="dropdown-item py-2 text-danger btn-delete-team" 
                                                    data-id="<?= $row['team_id']; ?>" data-name="<?= $row['team_name']; ?>">
                                                <i class="bx bx-trash me-2"></i> ลบรายการ
                                            </button>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Mobile View: Premium Cards -->
    <div class="d-md-none">
        <?php foreach ($teams as $row) : ?>
            <div class="mobile-team-item shadow-sm border-0 position-relative animate__animated animate__fadeIn">
                <a href="<?= base_url('Admin/Team/Detail/' . $row['team_id']); ?>" class="text-decoration-none">
                    <div class="d-flex align-items-center mb-3">
                        <div class="mobile-icon-box me-3">
                            <i class='bx bx-group'></i>
                        </div>
                        <div class="flex-grow-1">
                            <h5 class="fw-bold mb-1 text-dark"><?= $row['team_name']; ?></h5>
                            <span class="badge bg-label-primary rounded-8 px-2 py-1" style="font-size: 0.65rem;">
                                <?= $row['team_sport_type']; ?>
                            </span>
                        </div>
                        <i class='bx bx-chevron-right fs-1 text-light'></i>
                    </div>
                </a>
                
                <div class="d-flex justify-content-between align-items-center pt-3 border-top mt-2">
                    <div class="d-flex gap-2">
                        <div class="badge bg-label-primary rounded-pill p-2 px-3 stat-badge-sm">
                            <i class='bx bx-run me-1'></i> <?= $row['athlete_count']; ?>
                        </div>
                    </div>
                    <div class="d-flex gap-2">
                        <button type="button" class="btn btn-icon btn-label-danger btn-sm rounded-circle btn-delete-team"
                                data-id="<?= $row['team_id']; ?>" data-name="<?= $row['team_name']; ?>">
                            <i class='bx bx-trash'></i>
                        </button>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<!-- Modal Add Team -->
<div class="modal fade" id="modalAddTeam" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0">
            <div class="modal-header border-bottom py-3">
                <h5 class="modal-title fw-bold"><i class="bx bx-plus-circle me-2"></i>สร้างรุ่นนักกีฬาใหม่</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="<?= base_url('Admin/Team/Create'); ?>" method="post">
                <div class="modal-body p-4">
                    <div class="mb-4">
                        <label for="team_name" class="form-label fw-semibold">ชื่อรุ่น หรือ ชื่อทีม <span class="text-danger">*</span></label>
                        <input type="text" id="team_name" name="team_name" class="form-control" placeholder="เช่น รุ่นที่ 1/2568, ทีมฟุตบอลรุ่น 15 ปี" required />
                    </div>
                    <div class="row g-3">
                        <div class="col-7">
                            <label for="team_sport_type" class="form-label fw-semibold">ประเภทกีฬา <span class="text-danger">*</span></label>
                            <select id="team_sport_type" name="team_sport_type" class="form-select" required>
                                <option value="" disabled selected>เลือกประเภทกีฬา</option>
                                <option value="ฟุตบอล">ฟุตบอล</option>
                                <option value="ฟุตซอล">ฟุตซอล</option>
                                <option value="วอลเลย์บอล">วอลเลย์บอล</option>
                                <option value="บาสเกตบอล">บาสเกตบอล</option>
                                <option value="ปิงปอง">ปิงปอง</option>
                                <option value="เปตอง">เปตอง</option>
                                <option value="กรีฑา">กรีฑา</option>
                                <option value="พายเรือ">พายเรือ</option>
                            </select>
                        </div>
                        <div class="col-5">
                            <label for="team_year" class="form-label fw-semibold">ปีการศึกษา</label>
                            <input type="number" id="team_year" name="team_year" class="form-control" placeholder="2568" value="<?= date('Y')+543 ?>" />
                        </div>
                    </div>
                    <div class="mt-4">
                        <label for="coach_ids" class="form-label fw-semibold">ระบุผู้ดูแลนักกีฬา / โค้ชเพิ่มเติม</label>
                        <select id="coach_ids" name="coach_ids[]" class="form-select select2-coach" multiple data-placeholder="ค้นหาชื่อผู้ดูแล...">
                            <?php foreach($personnel as $p): ?>
                                <option value="<?= $p['pers_id']; ?>"><?= $p['pers_prefix'].$p['pers_firstname'].' '.$p['pers_lastname']; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="modal-footer border-top-0 pb-4 px-4">
                    <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">ยกเลิก</button>
                    <button type="submit" class="btn btn-primary px-4 shadow-sm">บันทึกข้อมูล</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    if ($('#tableTeam').length) {
        $('#tableTeam').DataTable({
            language: { url: '//cdn.datatables.net/plug-ins/1.13.4/i18n/th.json' },
            pageLength: 25,
            ordering: true,
            columnDefs: [{ orderable: false, targets: [5] }]
        });
    }

    $('.select2-coach').select2({
        dropdownParent: $('#modalAddTeam'),
        width: '100%',
        placeholder: "ค้นหาและเลือกผู้ดูแล..."
    });

    // Delete confirmation with Swal2
    $('.btn-delete-team').on('click', function() {
        const id = $(this).data('id');
        const name = $(this).data('name');
        
        Swal.fire({
            title: 'ยืนยันการลบ?',
            text: `คุณต้องการลบข้อมูล "${name}" ใช่หรือไม่? หากลบแล้วข้อมูลนักกีฬาในทีมจะหายไปด้วย`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#8592a3',
            confirmButtonText: 'ยืนยัน ลบเลย',
            cancelButtonText: 'ยกเลิก'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = `<?= base_url('Admin/Team/Delete/'); ?>/${id}`;
            }
        });
    });

    $('#modalAddTeam form').on('submit', function(e) {
        e.preventDefault();
        const form = $(this);
        const btn = form.find('button[type="submit"]');
        const originalHtml = btn.html();
        btn.prop('disabled', true).html("<i class='bx bx-loader-alt bx-spin me-1'></i> กำลังบันทึกข้อมูล...");

        $.ajax({
            url: form.attr('action'),
            method: form.attr('method'),
            data: form.serialize(),
            success: function(res) {
                if(res.success) {
                    $('#modalAddTeam').modal('hide');
                    Swal.fire({ icon: 'success', title: 'สำเร็จ', text: res.message }).then(() => location.reload());
                } else {
                    Swal.fire('ผิดพลาด', res.message, 'error');
                    btn.prop('disabled', false).html(originalHtml);
                }
            },
            error: function() {
                Swal.fire('ผิดพลาด', 'เกิดข้อผิดพลาดในการเชื่อมต่อเซิร์ฟเวอร์', 'error');
                btn.prop('disabled', false).html(originalHtml);
            }
        });
    });
});
</script>

<?= $this->endSection() ?>
