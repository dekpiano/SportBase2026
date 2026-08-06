<?= $this->extend('Admin/layout/AdminLayout') ?>

<?= $this->section('content') ?>
<style>
    :root {
        --glass-bg: rgba(255, 255, 255, 0.7);
        --glass-border: rgba(255, 255, 255, 0.3);
        --premium-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.15);
    }

    .premium-header {
        background: linear-gradient(135deg, #fd7e14 0%, #ff9e43 100%);
        color: white;
        border-radius: 15px;
        padding: 2rem;
        margin-bottom: 2rem;
        box-shadow: 0 10px 30px rgba(253, 126, 20, 0.2);
        position: relative;
        overflow: hidden;
    }

    .premium-header::after {
        content: '';
        position: absolute;
        top: -50%;
        right: -10%;
        width: 300px;
        height: 300px;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 50%;
    }

    .role-card {
        border: none;
        border-radius: 20px;
        backdrop-filter: blur(10px);
        background: var(--glass-bg);
        border: 1px solid var(--glass-border);
        box-shadow: var(--premium-shadow);
        transition: all 0.3s ease;
        overflow: hidden;
        height: 100%;
    }

    .role-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 15px 45px rgba(0,0,0,0.1);
    }

    .role-card-header {
        padding: 1.5rem;
        border-bottom: 1px solid rgba(0,0,0,0.05);
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .card-admin { border-top: 5px solid #ff4d4d; }
    .card-manager { border-top: 5px solid #ffcc00; }
    .card-coach { border-top: 5px solid #00d2ff; }

    .btn-add-circle {
        width: 35px;
        height: 35px;
        border-radius: 50%;
        padding: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s ease;
    }

    .person-item {
        padding: 1rem 1.5rem;
        border: none;
        background: transparent;
        transition: background 0.2s ease;
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .person-item:hover {
        background: rgba(0,0,0,0.02);
    }

    .avatar-wrapper {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
        background: #f0f2f5;
        overflow: hidden;
        flex-shrink: 0;
        border: 2px solid #ffffff;
        box-shadow: 0 3px 10px rgba(0,0,0,0.08);
    }

    .bg-light-red { background: #ffebeb; color: #ff4d4d; }
    .bg-light-yellow { background: #fff9e6; color: #ffcc00; }
    .bg-light-blue { background: #e6faff; color: #00d2ff; }

    .person-info {
        flex-grow: 1;
    }

    .person-name {
        font-weight: 600;
        color: #2d3436;
        margin-bottom: 0;
        display: block;
    }

    .person-title {
        font-size: 0.8rem;
        color: #636e72;
    }

    .btn-delete-minimal {
        color: #fab1a0;
        background: transparent;
        border: none;
        padding: 5px;
        transition: color 0.2s;
    }

    .btn-delete-minimal:hover {
        color: #ff7675;
    }

    /* Modal Styling */
    .modal-content {
        border-radius: 20px;
        border: none;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
    }

    .modal-header {
        border-bottom: 1px solid #f1f1f1;
        padding: 1.5rem;
    }

    .form-control, .form-select {
        border-radius: 10px;
        padding: 0.75rem 1rem;
        border: 1px solid #e0e0e0;
    }

    .form-control:focus, .form-select:focus {
        box-shadow: 0 0 0 3px rgba(253, 126, 20, 0.2);
        border-color: #fd7e14;
    }

    .btn-primary-premium {
        background: linear-gradient(135deg, #fd7e14 0%, #ff9e43 100%);
        border: none;
        border-radius: 10px;
        padding: 0.75rem 1.5rem;
        font-weight: 600;
        color: white;
        transition: all 0.3s;
    }

    .btn-primary-premium:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(253, 126, 20, 0.4);
    }
</style>

<div class="container-xxl flex-grow-1 container-p-y">
    <div class="premium-header">
        <div class="row align-items-center">
            <div class="col-auto">
                <div class="bg-white p-3 rounded-circle shadow-sm">
                    <i class='bx bxs-key-special text-primary fs-1'></i>
                </div>
            </div>
            <div class="col">
                <h2 class="mb-1 text-white fw-bold">ระบบกำหนดสิทธิ์เข้าใช้งาน</h2>
                <p class="mb-0 text-white-50 opacity-75">จัดการระดับการเข้าถึงข้อมูลและเครื่องมือต่างๆ ในระบบ SportBase 2026</p>
            </div>
        </div>
    </div>

    <?php if (session()->getFlashdata('success')) : ?>
        <div class="alert alert-success border-0 shadow-sm d-flex align-items-center" role="alert">
            <i class='bx bxs-check-circle me-2'></i>
            <div><?= session()->getFlashdata('success'); ?></div>
            <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')) : ?>
        <div class="alert alert-danger border-0 shadow-sm d-flex align-items-center" role="alert">
            <i class='bx bxs-error-circle me-2'></i>
            <div><?= session()->getFlashdata('error'); ?></div>
            <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <div class="row g-4">
        <!-- ผู้ดูแลระบบ -->
        <div class="col-lg-4 col-md-6">
            <div class="role-card card-admin">
                <div class="role-card-header">
                    <div>
                        <h5 class="fw-bold mb-0">ผู้ดูแลระบบ</h5>
                        <small class="text-muted">Admin User</small>
                    </div>
                    <button type="button" class="btn btn-danger btn-add-circle" data-bs-toggle="modal" data-bs-target="#modalAddRole" data-role-type="admin">
                        <i class="bx bx-plus"></i>
                    </button>
                </div>
                <div class="card-body p-0">
                    <div class="role-list" style="max-height: 400px; overflow-y: auto;">
                        <?php 
                        $admins = array_filter($roles, fn($r) => $r['role_type'] == 'admin');
                        if (empty($admins)) : ?>
                            <div class="text-center py-5">
                                <i class='bx bx-ghost fs-1 text-light opacity-50'></i>
                                <p class="text-muted mt-2">ยังไม่มีข้อมูล</p>
                            </div>
                        <?php else : ?>
                            <?php foreach ($admins as $role) : 
                                $persName = $role['person']['pers_prefix'] . $role['person']['pers_firstname'] . ' ' . $role['person']['pers_lastname'];
                                $persImgUrl = !empty($role['person']['pers_img']) 
                                    ? 'https://personnel.skj.ac.th/uploads/admin/Personnal/' . $role['person']['pers_img'] 
                                    : 'https://ui-avatars.com/api/?name=' . urlencode($role['person']['pers_firstname'] . ' ' . $role['person']['pers_lastname']) . '&background=ffebeb&color=ff4d4d&size=100';
                            ?>
                                <div class="person-item">
                                    <div class="avatar-wrapper bg-light-red">
                                        <img src="<?= $persImgUrl ?>" 
                                             alt="<?= $role['person']['pers_firstname'] ?>" 
                                             style="width: 100%; height: 100%; object-fit: cover;"
                                             onerror="this.onerror=null; this.src='https://ui-avatars.com/api/?name=<?= urlencode($role['person']['pers_firstname'] . ' ' . $role['person']['pers_lastname']) ?>&background=ffebeb&color=ff4d4d';">
                                    </div>
                                    <div class="person-info">
                                        <span class="person-name"><?= $persName; ?></span>
                                        <span class="person-title"><i class='bx bx-badge-check me-1'></i><?= $role['role_position']; ?></span>
                                    </div>
                                    <a href="<?= base_url('Admin/Roles/Delete/' . $role['id']); ?>" 
                                       class="btn-delete-minimal" 
                                       onclick="return confirm('ยืนยันการลบสิทธิ์ผู้ดูแลระบบ?')">
                                        <i class="bx bx-trash"></i>
                                    </a>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- ผู้บริหาร -->
        <div class="col-lg-4 col-md-6">
            <div class="role-card card-manager">
                <div class="role-card-header">
                    <div>
                        <h5 class="fw-bold mb-0">ผู้บริหาร</h5>
                        <small class="text-muted">Management Team</small>
                    </div>
                    <button type="button" class="btn btn-warning btn-add-circle" data-bs-toggle="modal" data-bs-target="#modalAddRole" data-role-type="manager">
                        <i class="bx bx-plus"></i>
                    </button>
                </div>
                <div class="card-body p-0">
                    <div class="role-list" style="max-height: 400px; overflow-y: auto;">
                        <?php 
                        $managers = array_filter($roles, fn($r) => $r['role_type'] == 'manager');
                        if (empty($managers)) : ?>
                            <div class="text-center py-5">
                                <i class='bx bx-ghost fs-1 text-light opacity-50'></i>
                                <p class="text-muted mt-2">ยังไม่มีข้อมูล</p>
                            </div>
                        <?php else : ?>
                            <?php foreach ($managers as $role) : 
                                $persName = $role['person']['pers_prefix'] . $role['person']['pers_firstname'] . ' ' . $role['person']['pers_lastname'];
                                $persImgUrl = !empty($role['person']['pers_img']) 
                                    ? 'https://personnel.skj.ac.th/uploads/admin/Personnal/' . $role['person']['pers_img'] 
                                    : 'https://ui-avatars.com/api/?name=' . urlencode($role['person']['pers_firstname'] . ' ' . $role['person']['pers_lastname']) . '&background=fff9e6&color=ffcc00&size=100';
                            ?>
                                <div class="person-item">
                                    <div class="avatar-wrapper bg-light-yellow">
                                        <img src="<?= $persImgUrl ?>" 
                                             alt="<?= $role['person']['pers_firstname'] ?>" 
                                             style="width: 100%; height: 100%; object-fit: cover;"
                                             onerror="this.onerror=null; this.src='https://ui-avatars.com/api/?name=<?= urlencode($role['person']['pers_firstname'] . ' ' . $role['person']['pers_lastname']) ?>&background=fff9e6&color=ffcc00';">
                                    </div>
                                    <div class="person-info">
                                        <span class="person-name"><?= $persName; ?></span>
                                        <span class="person-title"><i class='bx bx-bookmark-alt-minus me-1'></i><?= $role['role_position']; ?></span>
                                    </div>
                                    <a href="<?= base_url('Admin/Roles/Delete/' . $role['id']); ?>" 
                                       class="btn-delete-minimal" 
                                       onclick="return confirm('ยืนยันการลบสิทธิ์ผู้บริหาร?')">
                                        <i class="bx bx-trash"></i>
                                    </a>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- ผู้ดูแลนักกีฬา/โค้ช -->
        <div class="col-lg-4 col-md-6">
            <div class="role-card card-coach">
                <div class="role-card-header">
                    <div>
                        <h5 class="fw-bold mb-0">ผู้ดูแลนักกีฬา/โค้ช</h5>
                        <small class="text-muted">Coaching Staff</small>
                    </div>
                    <button type="button" class="btn btn-info btn-add-circle text-white" data-bs-toggle="modal" data-bs-target="#modalAddRole" data-role-type="coach">
                        <i class="bx bx-plus"></i>
                    </button>
                </div>
                <div class="card-body p-0">
                    <div class="role-list" style="max-height: 400px; overflow-y: auto;">
                        <?php 
                        $coaches = array_filter($roles, fn($r) => $r['role_type'] == 'coach');
                        if (empty($coaches)) : ?>
                            <div class="text-center py-5">
                                <i class='bx bx-ghost fs-1 text-light opacity-50'></i>
                                <p class="text-muted mt-2">ยังไม่มีข้อมูล</p>
                            </div>
                        <?php else : ?>
                            <?php foreach ($coaches as $role) : 
                                $persName = $role['person']['pers_prefix'] . $role['person']['pers_firstname'] . ' ' . $role['person']['pers_lastname'];
                                $persImgUrl = !empty($role['person']['pers_img']) 
                                    ? 'https://personnel.skj.ac.th/uploads/admin/Personnal/' . $role['person']['pers_img'] 
                                    : 'https://ui-avatars.com/api/?name=' . urlencode($role['person']['pers_firstname'] . ' ' . $role['person']['pers_lastname']) . '&background=e6faff&color=00d2ff&size=100';
                            ?>
                                <div class="person-item">
                                    <div class="avatar-wrapper bg-light-blue">
                                        <img src="<?= $persImgUrl ?>" 
                                             alt="<?= $role['person']['pers_firstname'] ?>" 
                                             style="width: 100%; height: 100%; object-fit: cover;"
                                             onerror="this.onerror=null; this.src='https://ui-avatars.com/api/?name=<?= urlencode($role['person']['pers_firstname'] . ' ' . $role['person']['pers_lastname']) ?>&background=e6faff&color=00d2ff';">
                                    </div>
                                    <div class="person-info">
                                        <span class="person-name"><?= $persName; ?></span>
                                        <span class="person-title"><i class='bx bx-run me-1'></i><?= $role['role_position']; ?></span>
                                    </div>
                                    <a href="<?= base_url('Admin/Roles/Delete/' . $role['id']); ?>" 
                                       class="btn-delete-minimal" 
                                       onclick="return confirm('ยืนยันการลบสิทธิ์โค้ช?')">
                                        <i class="bx bx-trash"></i>
                                    </a>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Add Role -->
<div class="modal fade" id="modalAddRole" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0">
            <div class="modal-header">
                <h5 class="modal-title fw-bold"><i class="bx bx-user-plus me-2 text-primary"></i>เพิ่มสิทธิ์ผู้ใช้งานใหม่</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="<?= base_url('Admin/Roles/Add'); ?>" method="post">
                <input type="hidden" name="role_type" id="roleTypeInput" value="">
                <div class="modal-body">
                    <div class="mb-4">
                        <label class="form-label fw-semibold">ประเภทสิทธิ์</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0"><i class='bx bx-lock-alt'></i></span>
                            <input type="text" class="form-control bg-light border-start-0" id="roleTypeLabel" readonly>
                        </div>
                    </div>
                    <div class="mb-4">
                        <label for="pers_id" class="form-label fw-semibold">เลือกบุคลากร</label>
                        <select id="pers_id" name="pers_id" class="form-select select2-premium" required>
                            <option value="">ค้นหาด้วยชื่อหรือนามสกุล...</option>
                            <?php foreach($personnel as $p): 
                                $pImgUrl = !empty($p['pers_img']) 
                                    ? 'https://personnel.skj.ac.th/uploads/admin/Personnal/' . $p['pers_img'] 
                                    : 'https://ui-avatars.com/api/?name=' . urlencode($p['pers_firstname'] . ' ' . $p['pers_lastname']) . '&background=random&size=80';
                            ?>
                                <option value="<?= $p['pers_id']; ?>" data-img="<?= $pImgUrl ?>"><?= $p['pers_prefix'].$p['pers_firstname'].' '.$p['pers_lastname']; ?> (<?= $p['pers_position']; ?>)</option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-2">
                        <label for="role_position" class="form-label fw-semibold">ระบุตำแหน่งงานย่อย</label>
                        <select id="role_position" name="role_position" class="form-select" required>
                            <option value="">กรุณาเลือกตำแหน่ง...</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer border-0 pb-4">
                    <button type="button" class="btn btn-link text-muted" data-bs-dismiss="modal">ยกเลิก</button>
                    <button type="submit" class="btn btn-primary-premium shadow-sm">บันทึกข้อมูลสิทธิ์</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const positions = <?= json_encode($rolePositions); ?>;
    const typeLabels = {
        'admin': 'ผู้ดูแลระบบ',
        'manager': 'ผู้บริหาร',
        'coach': 'ผู้ดูแลนักกีฬา/โค้ช'
    };

    const modal = document.getElementById('modalAddRole');
    modal.addEventListener('show.bs.modal', function(event) {
        const button = event.relatedTarget;
        const roleType = button.getAttribute('data-role-type');
        
        document.getElementById('roleTypeInput').value = roleType;
        document.getElementById('roleTypeLabel').value = typeLabels[roleType];
        
        const positionSelect = document.getElementById('role_position');
        positionSelect.innerHTML = '<option value="">กรุณาเลือกตำแหน่ง...</option>';
        
        if (positions[roleType]) {
            positions[roleType].forEach(pos => {
                const option = document.createElement('option');
                option.value = pos;
                option.textContent = pos;
                positionSelect.appendChild(option);
            });
        }
    });

    function formatPersonnel(state) {
        if (!state.id) return state.text;
        const imgUrl = $(state.element).data('img');
        if (!imgUrl) return state.text;
        return $(`
            <div class="d-flex align-items-center gap-2">
                <img src="${imgUrl}" class="rounded-circle border" style="width: 28px; height: 28px; object-fit: cover;" onerror="this.onerror=null; this.src='https://ui-avatars.com/api/?name=${encodeURIComponent(state.text)}&background=random';">
                <span>${state.text}</span>
            </div>
        `);
    }

    // Initialize Select2 with premium styling & images
    setTimeout(() => {
        $('.select2-premium').select2({
            dropdownParent: $('#modalAddRole'),
            width: '100%',
            templateResult: formatPersonnel,
            templateSelection: formatPersonnel
        });
    }, 200);

    // AJAX Form Submit for modalAddRole
    $('#modalAddRole form').on('submit', function(e) {
        e.preventDefault();
        const form = $(this);
        $.ajax({
            url: form.attr('action'),
            method: form.attr('method'),
            data: form.serialize(),
            success: function(res) {
                if(res.success) {
                    $('#modalAddRole').modal('hide');
                    Swal.fire({
                        icon: 'success',
                        title: 'สำเร็จ',
                        text: res.message
                    }).then(() => location.reload());
                } else {
                    Swal.fire('ผิดพลาด', res.message, 'error');
                }
            }
        });
    });
});
</script>

<?= $this->endSection() ?>
