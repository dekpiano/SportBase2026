<?= $this->extend('Admin/layout/AdminLayout') ?>

<?= $this->section('content') ?>
<style>
    .premium-header-card {
        background: linear-gradient(135deg, #fd7e14 0%, #ff9e43 100%);
        border-radius: 20px;
        color: white;
        padding: 2.5rem;
        margin-bottom: 2rem;
        box-shadow: 0 10px 25px rgba(253, 126, 20, 0.2);
        position: relative;
        overflow: hidden;
    }

    .premium-header-card::after {
        content: '';
        position: absolute;
        top: -20%;
        right: -5%;
        width: 250px;
        height: 250px;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 50%;
    }

    .stats-pill {
        background: rgba(255, 255, 255, 0.2);
        backdrop-filter: blur(5px);
        border: 1px solid rgba(255, 255, 255, 0.3);
        border-radius: 12px;
        padding: 1rem 1.5rem;
        display: flex;
        flex-direction: column;
    }

    .detail-card {
        border-radius: 20px;
        border: none;
        box-shadow: 0 5px 20px rgba(0,0,0,0.05);
        transition: transform 0.2s;
    }

    .detail-card:hover {
        transform: translateY(-5px);
    }

    .coach-item {
        padding: 1.25rem;
        border-bottom: 1px solid #f8f9fa;
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .coach-item:last-child {
        border-bottom: none;
    }

    .avatar-icon {
        width: 45px;
        height: 45px;
        border-radius: 12px;
        background: #f0f2f5;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
    }

    .custom-table thead th {
        background: #f8faff;
        border: none;
        font-weight: 700;
        text-transform: uppercase;
        font-size: 0.75rem;
        padding: 1rem;
    }

    .btn-action-minimal {
        width: 32px;
        height: 32px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s;
    }

    .btn-label-danger-hover:hover {
        background: #fee2e2;
        color: #ef4444;
    }

    /* Athlete Card Styles (Mobile) */
    .mobile-athlete-card {
        border-radius: 15px;
        border: 1px solid #f1f4f8;
        padding: 1rem;
        margin-bottom: 0.75rem;
        background: white;
        display: flex;
        align-items: center;
        gap: 12px;
    }
    .mobile-athlete-card .athlete-img {
        width: 45px;
        height: 60px;
        border-radius: 8px;
        object-fit: cover;
    }
    
    @media (max-width: 768px) {
        .premium-header-card {
            padding: 1.5rem;
            text-align: center;
        }
        .premium-header-card h1 {
            font-size: 1.5rem;
        }
        .stats-pill {
            padding: 0.75rem;
        }
        .stats-pill .fs-4 {
            font-size: 1.1rem !important;
        }
    }
</style>

<!-- Cropper.js CSS -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.2/cropper.min.css">

<div class="container-xxl flex-grow-1 container-p-y">
    <!-- Header -->
    <div class="premium-header-card">
        <div class="row align-items-center">
            <div class="col-lg-7">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-2">
                        <li class="breadcrumb-item"><a href="<?= base_url('Admin/Team'); ?>" class="text-white opacity-75">รุ่นนักกีฬา</a></li>
                        <li class="breadcrumb-item active text-white"><?= $team['team_name']; ?></li>
                    </ol>
                </nav>
                <h1 class="text-white fw-bold mb-0">ข้อมูลประจำรุ่น: <?= $team['team_name']; ?></h1>
                <p class="text-white-50 mt-1 mb-0"><i class='bx bx-trophy me-1'></i> สาระสำคัญของกลุ่มนักกีฬาและคณะผู้ฝึกสอน</p>
            </div>
            <div class="col-lg-5 mt-4 mt-lg-0">
                <div class="row g-3">
                    <div class="col">
                        <div class="stats-pill">
                            <span class="small opacity-75">ปีการศึกษา</span>
                            <span class="fw-bold fs-4"><?= $team['team_year'] ?: '-'; ?></span>
                        </div>
                    </div>
                    <div class="col">
                        <div class="stats-pill">
                            <span class="small opacity-75">ประเภทกีฬา</span>
                            <span class="fw-bold fs-4"><?= $team['team_sport_type']; ?></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Feedback Alerts -->
    <?php if (session()->getFlashdata('success')) : ?>
        <div class="alert alert-success border-0 shadow-sm d-flex align-items-center mb-4" role="alert">
            <i class='bx bxs-check-circle me-2'></i>
            <div><?= session()->getFlashdata('success'); ?></div>
            <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <div class="row g-4">
        <!-- Athletes View -->
        <div class="col-xl-8">
            <div class="card detail-card">
                <div class="card-header bg-white border-bottom py-3 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold"><i class='bx bx-user me-2 text-primary'></i>นักกีฬา (<?= count($athletes); ?> คน)</h5>
                    <button type="button" class="btn btn-primary rounded-pill btn-sm px-3 shadow-sm" data-bs-toggle="modal" data-bs-target="#modalAddAthlete">
                        <i class="bx bx-plus me-1"></i> เพิ่มนักกีฬา
                    </button>
                </div>
                <div class="card-body p-0">
                    <!-- Table View for Desktop -->
                    <div class="table-responsive d-none d-md-block">
                        <table class="table custom-table" id="tableAthletes">
                            <thead>
                                <tr>
                                    <th width="60">#</th>
                                    <th width="150">รหัสประจำตัว</th>
                                    <th>ชื่อ - นามสกุล</th>
                                    <th width="100" class="text-center">ชั้นเรียน</th>
                                    <th width="100" class="text-end">การจัดการ</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $i = 1; foreach ($athletes as $row) : ?>
                                    <tr>
                                        <td class="text-center text-muted"><?= $i++; ?></td>
                                        <td><code><?= $row['StudentCode']; ?></code></td>
                                        <td>
                                            <div class="d-flex align-items-center gap-3">
                                                <?php 
                                                $imagePath = !empty($row['athlete_image']) 
                                                    ? base_url('uploads/athletes/' . $row['athlete_image']) 
                                                    : base_url('assets/img/avatars/default-avatar.png');
                                                ?>
                                                <img src="<?= $imagePath; ?>" alt="<?= $row['StudentFirstName']; ?>" 
                                                     class="rounded border shadow-xs" style="width: 45px; height: 60px; object-fit: cover;">
                                                <span class="fw-semibold"><?= $row['StudentPrefix'] . $row['StudentFirstName'] . ' ' . $row['StudentLastName']; ?></span>
                                            </div>
                                        </td>
                                        <td class="text-center"><span class="badge bg-label-secondary"><?= $row['StudentClass']; ?></span></td>
                                        <td>
                                            <div class="d-flex justify-content-end">
                                                <a href="<?= base_url('Admin/Team/RemoveAthlete/' . $row['id'] . '/' . $team['team_id']); ?>" 
                                                   class="btn btn-icon btn-label-danger btn-sm rounded-circle" 
                                                   onclick="return confirm('ยืนยันลบนักกีฬาออกจากรุ่น?')">
                                                    <i class="bx bx-trash-alt"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>

                    <!-- Card View for Mobile -->
                    <div class="p-3 d-md-none">
                        <?php if(empty($athletes)): ?>
                            <div class="text-center py-4">
                                <p class="text-muted mb-0">ยังไม่มีรายชื่อนักกีฬา</p>
                            </div>
                        <?php else: ?>
                            <?php foreach ($athletes as $row) : ?>
                                <div class="mobile-athlete-card shadow-sm">
                                    <?php 
                                    $imagePath = !empty($row['athlete_image']) 
                                        ? base_url('uploads/athletes/' . $row['athlete_image']) 
                                        : base_url('assets/img/avatars/default-avatar.png');
                                    ?>
                                    <img src="<?= $imagePath; ?>" class="athlete-img" alt="Athlete">
                                    <div class="flex-grow-1">
                                        <div class="fw-bold text-heading" style="font-size: 0.9rem;">
                                            <?= $row['StudentPrefix'] . $row['StudentFirstName'] . ' ' . $row['StudentLastName']; ?>
                                        </div>
                                        <small class="text-muted"><?= $row['StudentCode']; ?> • ชั้น <?= $row['StudentClass']; ?></small>
                                    </div>
                                    <button type="button" 
                                            class="btn btn-icon btn-label-danger btn-sm rounded-circle btn-remove-athlete"
                                            data-id="<?= $row['id']; ?>"
                                            data-name="<?= $row['StudentFirstName']; ?>">
                                        <i class='bx bx-trash-alt'></i>
                                    </button>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Coaches View -->
        <div class="col-xl-4">
            <div class="card detail-card mb-4">
                <div class="card-header bg-white border-bottom py-3 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold"><i class='bx bx-shield-quarter me-2 text-warning'></i>ครูผู้ฝึกสอน / โค้ช</h5>
                    <button type="button" class="btn btn-warning rounded-pill btn-sm px-3 shadow-sm text-white" data-bs-toggle="modal" data-bs-target="#modalAddCoach">
                        <i class="bx bx-plus me-1"></i> เพิ่มโค้ช
                    </button>
                </div>
                <div class="card-body p-0">
                    <?php if(count($teamCoaches) > 0): ?>
                        <div class="coach-list">
                            <?php foreach ($teamCoaches as $coach) : ?>
                                <div class="coach-item">
                                    <div class="avatar-icon text-warning bg-label-warning">
                                        <i class="bx bxs-user-voice"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <span class="fw-bold d-block text-heading" style="font-size: 0.9rem;"><?= $coach['pers_prefix'] . $coach['pers_firstname'] . ' ' . $coach['pers_lastname']; ?></span>
                                        <small class="text-muted"><i class='bx bx-check-shield'></i> ผู้ฝึกสอนหลัก</small>
                                    </div>
                                    <button type="button" 
                                       class="btn-action-minimal btn-label-danger-hover text-danger border-0 bg-transparent btn-remove-coach" 
                                       data-id="<?= $coach['id']; ?>"
                                       data-name="<?= $coach['pers_firstname']; ?>">
                                        <i class="bx bx-x fs-4"></i>
                                    </button>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-5">
                            <i class='bx bx-user-voice fs-huge text-light opacity-50 mb-3'></i>
                            <p class="text-muted">ยังไม่มีรายชื่อผู้ฝึกสอน</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Add Athlete -->
<div class="modal fade" id="modalAddAthlete" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header border-bottom py-3">
                <h5 class="modal-title fw-bold"><i class="bx bx-user-plus me-2 text-primary"></i>เพิ่มรายชื่อนักกีฬาใหม่</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="<?= base_url('Admin/Team/AddAthlete'); ?>" method="post" enctype="multipart/form-data" id="formAddAthlete">
                <input type="hidden" name="team_id" value="<?= $team['team_id']; ?>">
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label for="searchStudent" class="form-label fw-semibold">ค้นชื่อนักเรียนในระบบสถาบัน</label>
                        <select id="searchStudent" name="StudentID" class="form-select select2-premium" required>
                            <option value="">ค้นหาด้วยชื่อหรือรหัสผ่าน...</option>
                        </select>
                        <div class="form-text mt-2"><i class='bx bx-info-circle me-1'></i> ระบุชื่อ 2 ตัวอักษรขึ้นไปเพื่อเริ่มค้นหา</div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">รูปภาพนักกีฬา</label>
                        <div class="text-center mb-3">
                            <img id="imagePreview" src="<?= base_url('assets/img/avatars/default-avatar.png'); ?>" 
                                 alt="Preview" class="rounded" 
                                 style="width: 90px; height: 120px; object-fit: cover; border: 3px solid #e9ecef; cursor: pointer;"
                                 title="คลิกเพื่อเลือกรูป">
                        </div>
                        <input type="file" class="form-control" id="athleteImageInput" accept="image/*">
                        <input type="hidden" id="croppedImageData" name="cropped_image">
                        <div class="form-text"><i class='bx bx-crop me-1'></i> เลือกรูปแล้วกดครอบตัด (รองรับ JPG, PNG ไม่เกิน 2MB)</div>
                    </div>
                </div>
                <div class="modal-footer border-top-0 pb-4 px-4">
                    <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">ยกเลิก</button>
                    <button type="submit" class="btn btn-primary px-4 shadow-sm">เพิ่มเข้าทีม</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Image Cropper -->
<div class="modal fade" id="modalCropper" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header border-bottom py-3">
                <h5 class="modal-title fw-bold"><i class="bx bx-crop me-2 text-primary"></i>ครอบตัดรูปภาพ</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <div class="text-center">
                    <img id="cropperPreview" src="" alt="Crop Preview">
                </div>
            </div>
            <div class="modal-footer border-top py-3">
                <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">ยกเลิก</button>
                <button type="button" class="btn btn-primary" id="btnCropImage">
                    <i class="bx bx-check me-1"></i> ตกลง ใช้รูปนี้
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Add Coach -->
<div class="modal fade" id="modalAddCoach" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header border-bottom py-3">
                <h5 class="modal-title fw-bold"><i class="bx bx-medal me-2 text-warning"></i>แต่งตั้งผู้ฝึกสอนประจำรุ่น</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="<?= base_url('Admin/Team/AddCoach'); ?>" method="post">
                <input type="hidden" name="team_id" value="<?= $team['team_id']; ?>">
                <div class="modal-body p-4">
                    <div class="mb-2">
                        <label for="coach_id" class="form-label fw-semibold">เลือกบุคลากรจากรายชื่อผู้ฝึกสอน</label>
                        <select id="coach_id" name="coach_id" class="form-select select2-basic" required>
                            <option value="">กรุณาเลือกโค้ช...</option>
                            <?php foreach($coaches as $coach): ?>
                                <option value="<?= $coach['pers_id']; ?>"><?= $coach['pers_prefix'].$coach['pers_firstname'].' '.$coach['pers_lastname']; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="modal-footer border-top-0 pb-4 px-4">
                    <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">ยกเลิก</button>
                    <button type="submit" class="btn btn-warning px-4 shadow-sm text-white">แต่งตั้งโค้ช</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // DataTables setup
    $('#tableAthletes').DataTable({
        language: { url: '//cdn.datatables.net/plug-ins/1.13.4/i18n/th.json' },
        pageLength: 25,
        columnDefs: [ { orderable: false, targets: [4] } ]
    });

    // Select2 Ajax for Students
    $('#searchStudent').select2({
        dropdownParent: $('#modalAddAthlete'),
        placeholder: 'ค้นหาด้วยชื่อหรือรหัส...',
        minimumInputLength: 2,
        width: '100%',
        ajax: {
            url: '<?= base_url('Admin/Team/SearchStudents'); ?>',
            dataType: 'json',
            delay: 300,
            data: function(params) {
                return { term: params.term, team_id: <?= $team['team_id']; ?> };
            },
            processResults: function(data) {
                return { results: data };
            },
            cache: true
        }
    });

    // Select2 basic for Coach
    $('#coach_id').select2({
        dropdownParent: $('#modalAddCoach'),
        width: '100%'
    });

    // Cropper.js Setup
    let cropper = null;
    
    // When file is selected, open cropper modal
    $('#athleteImageInput').on('change', function() {
        const file = this.files[0];
        if (file) {
            // Validate file size (2MB max)
            if (file.size > 2 * 1024 * 1024) {
                Swal.fire('ไฟล์ใหญ่เกินไป', 'กรุณาเลือกไฟล์ขนาดไม่เกิน 2MB', 'warning');
                return;
            }
            
            const reader = new FileReader();
            reader.onload = function(e) {
                // Destroy existing cropper
                if (cropper) {
                    cropper.destroy();
                    cropper = null;
                }
                
                // Set image source and show cropper modal
                $('#cropperPreview').attr('src', e.target.result);
                $('#modalCropper').modal('show');
                
                // Initialize cropper after modal is shown
                $('#modalCropper').one('shown.bs.modal', function() {
                    cropper = new Cropper(document.getElementById('cropperPreview'), {
                        aspectRatio: 3/4, // Portrait (3:4)
                        viewMode: 2,
                        dragMode: 'move',
                        autoCropArea: 0.8,
                        restore: false,
                        guides: true,
                        center: true,
                        highlight: false,
                        cropBoxMovable: true,
                        cropBoxResizable: true,
                        toggleDragModeOnDblclick: false
                    });
                });
            };
            reader.readAsDataURL(file);
        }
    });
    
    // Crop and apply image
    $('#btnCropImage').on('click', function() {
        if (cropper) {
            // Get cropped canvas
            const canvas = cropper.getCroppedCanvas({
                width: 300,
                height: 400,
                imageSmoothingEnabled: true,
                imageSmoothingQuality: 'high'
            });
            
            // Convert to base64
            const croppedData = canvas.toDataURL('image/jpeg', 0.9);
            
            // Set preview
            $('#imagePreview').attr('src', croppedData);
            
            // Store in hidden input
            $('#croppedImageData').val(croppedData);
            
            // Close cropper modal
            $('#modalCropper').modal('hide');
            
            // Destroy cropper
            cropper.destroy();
            cropper = null;
        }
    });
    
    // Clean up cropper on modal close
    $('#modalCropper').on('hidden.bs.modal', function() {
        if (cropper) {
            cropper.destroy();
            cropper = null;
        }
    });

    // AJAX for Adding Athlete with Cropped Image
    $('#formAddAthlete').on('submit', function(e) {
        e.preventDefault();
        const form = $(this);
        const formData = new FormData(this);
        
        $.ajax({
            url: form.attr('action'),
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(res) {
                if(res.success) {
                    $('#modalAddAthlete').modal('hide');
                    Swal.fire('สำเร็จ', res.message, 'success').then(() => location.reload());
                } else {
                    Swal.fire('ผิดพลาด', res.message, 'error');
                }
            },
            error: function() {
                Swal.fire('ผิดพลาด', 'ไม่สามารถเพิ่มนักกีฬาได้', 'error');
            }
        });
    });

    // Remove Athlete confirmation
    $(document).on('click', '.btn-remove-athlete', function() {
        const id = $(this).data('id');
        const name = $(this).data('name');
        Swal.fire({
            title: 'ยืนยันการลบ?',
            text: `ต้องการนำคุณ "${name}" ออกจากทีมใช่หรือไม่?`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            confirmButtonText: 'ยืนยัน ลบเลย',
            cancelButtonText: 'ยกเลิก'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = `<?= base_url('Admin/Team/RemoveAthlete'); ?>/${id}/<?= $team['team_id']; ?>`;
            }
        });
    });

    // Remove Coach confirmation
    $(document).on('click', '.btn-remove-coach', function() {
        const id = $(this).data('id');
        const name = $(this).data('name');
        Swal.fire({
            title: 'ยืนยันการนำออก?',
            text: `ต้องการนำโค้ช "${name}" ออกจากทีมใช่หรือไม่?`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            confirmButtonText: 'ยืนยัน นำออก',
            cancelButtonText: 'ยกเลิก'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = `<?= base_url('Admin/Team/RemoveCoach'); ?>/${id}/<?= $team['team_id']; ?>`;
            }
        });
    });

    // AJAX for Adding Coach
    $('#modalAddCoach form').on('submit', function(e) {
        e.preventDefault();
        const form = $(this);
        $.ajax({
            url: form.attr('action'),
            method: form.attr('method'),
            data: form.serialize(),
            success: function(res) {
                if(res.success) {
                    $('#modalAddCoach').modal('hide');
                    Swal.fire('สำเร็จ', res.message, 'success').then(() => location.reload());
                } else {
                    Swal.fire('ผิดพลาด', res.message, 'error');
                }
            }
        });
    });
});
</script>

<!-- Cropper.js Library -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.2/cropper.min.js"></script>

<?= $this->endSection() ?>
