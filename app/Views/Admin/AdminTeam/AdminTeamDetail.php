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

    /* Modern Image Upload Zone & 3:4 Previews */
    .image-upload-zone {
        border: 2px dashed #cbd5e1;
        border-radius: 16px;
        padding: 1.25rem;
        text-align: center;
        background: #f8fafc;
        transition: all 0.2s ease-in-out;
        cursor: pointer;
    }
    .image-upload-zone:hover {
        border-color: #fd7e14;
        background: #fffaf5;
    }
    .image-preview-34 {
        width: 120px;
        height: 160px;
        object-fit: cover;
        border-radius: 12px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        border: 3px solid #ffffff;
    }
    .cropper-container-wrapper {
        max-height: 55vh;
        min-height: 260px;
        background: #0f172a;
        border-radius: 12px;
        overflow: hidden;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .cropper-container-wrapper img {
        max-width: 100%;
        max-height: 55vh;
    }
    .cropper-toolbar {
        display: flex;
        gap: 8px;
        justify-content: center;
        flex-wrap: wrap;
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
        .cropper-container-wrapper {
            max-height: 45vh;
            min-height: 220px;
        }
    }

    /* Ensure Cropper modal is on top of other modals */
    #modalCropper {
        z-index: 2000 !important;
    }
    .modal-backdrop.show:nth-last-of-type(1) {
        z-index: 1085 !important;
    }
    #modalCropper ~ .modal-backdrop {
        z-index: 1990 !important;
    }
</style>

<!-- Cropper.js CSS -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.2/cropper.min.css">

<?php
// Default User Avatar Icon (3:4 aspect ratio vector SVG)
$defaultUserIcon = "data:image/svg+xml;charset=UTF-8,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 133' width='100' height='133'%3E%3Crect width='100%25' height='100%25' fill='%23e2e8f0'/%3E%3Ccircle cx='50' cy='45' r='20' fill='%2394a3b8'/%3E%3Cpath fill='%2394a3b8' d='M15 115 c0-22 15-35 35-35 s35 13 35 35 Z'/%3E%3C/svg%3E";
?>

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
                                                $hasImage = !empty($row['athlete_image']) && file_exists(FCPATH . 'uploads/athletes/' . $row['athlete_image']);
                                                $imagePath = $hasImage 
                                                    ? base_url('uploads/athletes/' . $row['athlete_image']) 
                                                    : $defaultUserIcon;
                                                ?>
                                                <div class="position-relative">
                                                    <img src="<?= $imagePath; ?>" alt="<?= $row['StudentFirstName']; ?>" 
                                                         class="rounded border shadow-xs" style="width: 45px; height: 60px; object-fit: cover;"
                                                         onerror="this.onerror=null; this.src='<?= $defaultUserIcon; ?>';">
                                                </div>
                                                <span class="fw-semibold"><?= $row['StudentPrefix'] . $row['StudentFirstName'] . ' ' . $row['StudentLastName']; ?></span>
                                            </div>
                                        </td>
                                        <td class="text-center"><span class="badge bg-label-secondary"><?= $row['StudentClass']; ?></span></td>
                                        <td>
                                            <div class="d-flex justify-content-end gap-1">
                                                <button type="button" 
                                                        class="btn btn-icon btn-label-primary btn-sm rounded-circle btn-edit-athlete-image" 
                                                        data-id="<?= $row['id']; ?>"
                                                        data-student-id="<?= $row['StudentID']; ?>"
                                                        data-name="<?= $row['StudentFirstName']; ?>"
                                                        data-code="<?= $row['StudentCode']; ?>"
                                                        data-image="<?= $imagePath; ?>"
                                                        title="แก้ไขรูปภาพ">
                                                    <i class="bx bx-image-add"></i>
                                                </button>
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
                                    $hasImage = !empty($row['athlete_image']) && file_exists(FCPATH . 'uploads/athletes/' . $row['athlete_image']);
                                    $imagePath = $hasImage 
                                        ? base_url('uploads/athletes/' . $row['athlete_image']) 
                                        : $defaultUserIcon;
                                    ?>
                                    <img src="<?= $imagePath; ?>" class="athlete-img" alt="Athlete"
                                         onerror="this.onerror=null; this.src='<?= $defaultUserIcon; ?>';">
                                    <div class="flex-grow-1">
                                        <div class="fw-bold text-heading" style="font-size: 0.9rem;">
                                            <?= $row['StudentPrefix'] . $row['StudentFirstName'] . ' ' . $row['StudentLastName']; ?>
                                        </div>
                                        <small class="text-muted"><?= $row['StudentCode']; ?> • ชั้น <?= $row['StudentClass']; ?></small>
                                    </div>
                                    <div class="d-flex gap-1">
                                        <button type="button" 
                                                class="btn btn-icon btn-label-primary btn-sm rounded-circle btn-edit-athlete-image"
                                                data-id="<?= $row['id']; ?>"
                                                data-student-id="<?= $row['StudentID']; ?>"
                                                data-name="<?= $row['StudentFirstName']; ?>"
                                                data-code="<?= $row['StudentCode']; ?>"
                                                data-image="<?= $imagePath; ?>">
                                            <i class='bx bx-image-add'></i>
                                        </button>
                                        <button type="button" 
                                                class="btn btn-icon btn-label-danger btn-sm rounded-circle btn-remove-athlete"
                                                data-id="<?= $row['id']; ?>"
                                                data-name="<?= $row['StudentFirstName']; ?>">
                                            <i class='bx bx-trash-alt'></i>
                                        </button>
                                    </div>
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
                                    <?php 
                                    $coachImgUrl = !empty($coach['pers_img']) 
                                        ? 'https://personnel.skj.ac.th/uploads/admin/Personnal/' . $coach['pers_img'] 
                                        : $defaultUserIcon;
                                    ?>
                                    <img src="<?= $coachImgUrl; ?>" 
                                         alt="<?= $coach['pers_firstname']; ?>" 
                                         class="rounded-circle border shadow-xs" 
                                         style="width: 45px; height: 45px; object-fit: cover;"
                                         onerror="this.onerror=null; this.src='<?= $defaultUserIcon; ?>';">
                                    <div class="flex-grow-1">
                                        <span class="fw-bold d-block text-heading" style="font-size: 0.9rem;"><?= $coach['pers_prefix'] . $coach['pers_firstname'] . ' ' . $coach['pers_lastname']; ?></span>
                                        <small class="text-muted"><i class='bx bx-check-shield text-warning me-1'></i>ผู้ฝึกสอนประจำรุ่น</small>
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
                    <div class="mb-4">
                        <label for="searchStudent" class="form-label fw-semibold">ค้นชื่อนักเรียนในระบบสถาบัน</label>
                        <select id="searchStudent" name="StudentID" class="form-select select2-premium" required>
                            <option value="">ค้นหาด้วยชื่อหรือรหัส...</option>
                        </select>
                        <div class="form-text mt-2"><i class='bx bx-info-circle me-1'></i> ระบุชื่อ 2 ตัวอักษรขึ้นไปเพื่อเริ่มค้นหา</div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold d-flex justify-content-between align-items-center">
                            <span>รูปภาพนักกีฬา</span>
                            <span class="badge bg-label-primary">อัตราส่วน 3:4</span>
                        </label>
                        <div class="image-upload-zone" onclick="document.getElementById('athleteImageInput').click();">
                            <div class="text-center mb-2">
                                <img id="imagePreview" src="<?= $defaultUserIcon; ?>" 
                                     alt="Preview" class="image-preview-34"
                                     onerror="this.onerror=null; this.src='<?= $defaultUserIcon; ?>';">
                            </div>
                            <div class="mt-2 text-primary fw-semibold">
                                <i class='bx bx-camera me-1 fs-5 align-middle'></i> แตะหรือคลิกเพื่อเลือกรูปภาพ
                            </div>
                            <small class="text-muted d-block mt-1">รองรับทุกขนาดไฟล์ (ระบบย่อและตัดเป็น 3:4 อัตโนมัติ)</small>
                        </div>
                        <input type="file" class="form-control d-none" id="athleteImageInput" accept="image/*">
                        <input type="hidden" id="croppedImageData" name="cropped_image">
                    </div>
                </div>
                <div class="modal-footer border-top-0 pb-4 px-4">
                    <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">ยกเลิก</button>
                    <button type="submit" class="btn btn-primary px-4 shadow-sm"><i class='bx bx-check me-1'></i> เพิ่มเข้าทีม</button>
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
                <div class="d-flex align-items-center gap-2">
                    <h5 class="modal-title fw-bold mb-0"><i class="bx bx-crop me-2 text-primary"></i>ครอบตัดรูปภาพ</h5>
                    <span class="badge bg-label-primary">อัตราส่วน 3:4</span>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-3 p-md-4">
                <div class="cropper-container-wrapper shadow-sm mb-3">
                    <img id="cropperPreview" src="" alt="Crop Preview">
                </div>
                <!-- Cropper Mobile-friendly Toolbar -->
                <div class="cropper-toolbar">
                    <div class="btn-group shadow-sm" role="group" aria-label="Cropper controls">
                        <button type="button" class="btn btn-outline-secondary" id="btnZoomIn" title="ขยายภาพ"><i class="bx bx-zoom-in fs-5"></i></button>
                        <button type="button" class="btn btn-outline-secondary" id="btnZoomOut" title="ย่อภาพ"><i class="bx bx-zoom-out fs-5"></i></button>
                        <button type="button" class="btn btn-outline-secondary" id="btnRotateLeft" title="หมุนซ้าย 90°"><i class="bx bx-rotate-left fs-5"></i></button>
                        <button type="button" class="btn btn-outline-secondary" id="btnRotateRight" title="หมุนขวา 90°"><i class="bx bx-rotate-right fs-5"></i></button>
                        <button type="button" class="btn btn-outline-secondary" id="btnResetCrop" title="รีเซ็ตตำแหน่ง"><i class="bx bx-reset fs-5"></i></button>
                    </div>
                </div>
            </div>
            <div class="modal-footer border-top py-3">
                <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">ยกเลิก</button>
                <button type="button" class="btn btn-primary px-4 shadow-sm" id="btnCropImage">
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

<!-- Modal Edit Athlete Image -->
<div class="modal fade" id="modalEditAthleteImage" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header border-bottom py-3">
                <h5 class="modal-title fw-bold"><i class="bx bx-image-add me-2 text-primary"></i>แก้ไขรูปภาพนักกีฬา</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="<?= base_url('Admin/Team/UpdateAthleteImage'); ?>" method="post" enctype="multipart/form-data" id="formEditAthleteImage">
                <input type="hidden" name="team_id" value="<?= $team['team_id']; ?>">
                <input type="hidden" name="athlete_id" id="edit_athlete_id">
                <input type="hidden" name="StudentID" id="edit_student_id">
                <div class="modal-body p-4 text-center">
                    <div class="mb-3">
                        <h6 id="edit_athlete_name" class="fw-bold mb-1"></h6>
                        <small id="edit_athlete_code" class="text-muted d-block mb-3"></small>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold d-flex justify-content-between align-items-center mb-2">
                            <span>รูปภาพใหม่ (อัตราส่วน 3:4)</span>
                            <span class="badge bg-label-primary">3:4</span>
                        </label>
                        <div class="image-upload-zone" onclick="document.getElementById('editAthleteImageInput').click();">
                            <div class="text-center mb-2">
                                <img id="editImagePreview" src="" alt="Preview" class="image-preview-34"
                                     onerror="this.onerror=null; this.src='<?= $defaultUserIcon; ?>';">
                            </div>
                            <div class="mt-2 text-primary fw-semibold">
                                <i class='bx bx-camera me-1 fs-5 align-middle'></i> แตะหรือคลิกเพื่อเปลี่ยนรูปภาพ
                            </div>
                            <small class="text-muted d-block mt-1">ไม่จำกัดขนาดไฟล์ (ระบบจะตัดเป็น 3:4 ให้อัตโนมัติ)</small>
                        </div>
                        <input type="file" class="form-control d-none" id="editAthleteImageInput" accept="image/*">
                        <input type="hidden" id="editCroppedImageData" name="cropped_image">
                    </div>
                </div>
                <div class="modal-footer border-top-0 pb-4 px-4">
                    <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">ยกเลิก</button>
                    <button type="submit" class="btn btn-primary px-4 shadow-sm">บันทึกรูปภาพ</button>
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
    let cropperContext = 'add'; // 'add' or 'edit'
    
    // When file is selected (ADD), open cropper modal
    $('#athleteImageInput').on('change', function() {
        cropperContext = 'add';
        handleImageSelection(this);
    });

    // When file is selected (EDIT), open cropper modal
    $('#editAthleteImageInput').on('change', function() {
        cropperContext = 'edit';
        handleImageSelection(this);
    });

    function handleImageSelection(input) {
        const file = input.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                if (cropper) { cropper.destroy(); cropper = null; }
                $('#cropperPreview').attr('src', e.target.result);
                $('#modalCropper').modal('show');
                $('#modalCropper').one('shown.bs.modal', function() {
                    cropper = new Cropper(document.getElementById('cropperPreview'), {
                        aspectRatio: 3 / 4,
                        viewMode: 1,
                        dragMode: 'move',
                        autoCropArea: 0.9,
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
    }

    // Cropper Toolbar Controls
    $('#btnZoomIn').on('click', function() { if (cropper) cropper.zoom(0.1); });
    $('#btnZoomOut').on('click', function() { if (cropper) cropper.zoom(-0.1); });
    $('#btnRotateLeft').on('click', function() { if (cropper) cropper.rotate(-90); });
    $('#btnRotateRight').on('click', function() { if (cropper) cropper.rotate(90); });
    $('#btnResetCrop').on('click', function() { if (cropper) cropper.reset(); });
    
    // Crop and apply image (3:4 aspect ratio -> 600x800 px)
    $('#btnCropImage').on('click', function() {
        if (cropper) {
            const canvas = cropper.getCroppedCanvas({
                width: 600,
                height: 800,
                imageSmoothingEnabled: true,
                imageSmoothingQuality: 'high'
            });
            const croppedData = canvas.toDataURL('image/jpeg', 0.9);
            
            if (cropperContext === 'add') {
                $('#imagePreview').attr('src', croppedData);
                $('#croppedImageData').val(croppedData);
            } else {
                $('#editImagePreview').attr('src', croppedData);
                $('#editCroppedImageData').val(croppedData);
            }
            
            $('#modalCropper').modal('hide');
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
        $.ajax({
            url: form.attr('action'),
            method: 'POST',
            data: new FormData(this),
            processData: false, contentType: false,
            success: function(res) {
                if(res.success) {
                    $('#modalAddAthlete').modal('hide');
                    Swal.fire('สำเร็จ', res.message, 'success').then(() => location.reload());
                } else {
                    Swal.fire('ผิดพลาด', res.message, 'error');
                }
            }
        });
    });

    // Open Edit Image Modal
    $(document).on('click', '.btn-edit-athlete-image', function() {
        $('#edit_athlete_id').val($(this).data('id'));
        $('#edit_student_id').val($(this).data('student-id'));
        $('#edit_athlete_name').text($(this).data('name'));
        $('#edit_athlete_code').text('รหัสประจำตัว: ' + $(this).data('code'));
        $('#editImagePreview').attr('src', $(this).data('image'));
        $('#editCroppedImageData').val(''); 
        $('#editAthleteImageInput').val('');
        $('#modalEditAthleteImage').modal('show');
    });

    // AJAX for Updating Athlete Image
    $('#formEditAthleteImage').on('submit', function(e) {
        e.preventDefault();
        const croppedData = $('#editCroppedImageData').val();
        if (!croppedData) {
            Swal.fire('ไม่มีการเปลี่ยนแปลง', 'กรุณาเลือกรูปภาพใหม่และทำการครอบตัดก่อนบันทึก', 'info');
            return;
        }
        const form = $(this);
        $.ajax({
            url: form.attr('action'),
            method: 'POST',
            data: new FormData(this),
            processData: false, contentType: false,
            success: function(res) {
                if(res.success) {
                    $('#modalEditAthleteImage').modal('hide');
                    Swal.fire('สำเร็จ', res.message, 'success').then(() => location.reload());
                } else {
                    Swal.fire('ผิดพลาด', res.message, 'error');
                }
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
