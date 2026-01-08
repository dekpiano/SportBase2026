<style>
    .athlete-card {
        border: none;
        border-radius: 20px;
        transition: all 0.3s ease;
        background: #fff;
        height: 100%;
        overflow: hidden;
    }
    .athlete-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 15px 30px rgba(0,0,0,0.1);
    }
    .athlete-img-container {
        position: relative;
        padding-top: 100%;
        background: #f8f9fa;
        overflow: hidden;
    }
    .athlete-img {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    .team-badge {
        position: absolute;
        bottom: 10px;
        right: 10px;
        z-index: 1;
    }
    .nav-pills .nav-link.active {
        background-color: var(--bs-primary);
        box-shadow: 0 4px 12px rgba(var(--bs-primary-rgb), 0.3);
    }
    .filter-sidebar {
        background: #fff;
        border-radius: 15px;
        padding: 1.5rem;
        position: sticky;
        top: 1.5rem;
    }
</style>

<div class="layout-page">
    <?php echo view('User/UserLeyout/UserNavbar'); ?>

    <div class="content-wrapper">
        <div class="container-xxl flex-grow-1 container-p-y">
            <div class="row align-items-center mb-4">
                <div class="col-md-6">
                    <h4 class="fw-bold mb-0">
                        <span class="text-muted fw-light">ทำเนียบ /</span> นักกีฬา SKJ
                    </h4>
                </div>
            </div>

            <div class="row">
                <!-- Sidebar Filter -->
                <div class="col-lg-3">
                    <div class="filter-sidebar shadow-sm mb-4">
                        <h6 class="fw-bold mb-3"><i class='bx bx-filter-alt me-2'></i>เลือกรุ่น/ทีม</h6>
                        <div class="nav nav-pills flex-column">
                            <?php foreach ($teams as $team): ?>
                                <a href="?team_id=<?= $team['team_id'] ?>" 
                                   class="nav-link mb-2 border <?= (isset($currentTeam) && $currentTeam['team_id'] == $team['team_id']) ? 'active' : '' ?>">
                                    <div class="d-flex justify-content-between align-items-center w-100">
                                        <span><?= $team['team_name'] ?></span>
                                        <span class="badge rounded-pill bg-label-<?= (isset($currentTeam) && $currentTeam['team_id'] == $team['team_id']) ? 'white' : 'primary' ?>">
                                            <?= $team['athlete_count'] ?>
                                        </span>
                                    </div>
                                    <small class="d-block opacity-75"><?= $team['team_sport_type'] ?></small>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>

                <!-- Athlete Grid -->
                <div class="col-lg-9">
                    <?php if (isset($currentTeam)): ?>
                        <div class="mb-4 d-flex justify-content-between align-items-center">
                            <div>
                                <h5 class="fw-bold mb-1"><?= $currentTeam['team_name'] ?></h5>
                                <p class="text-muted mb-0"><?= $currentTeam['team_sport_type'] ?> • ประเภทกีฬา</p>
                            </div>
                            <div class="text-muted">
                                พบนักกีฬา <?= count($athletes) ?> คน
                            </div>
                        </div>
                    <?php endif; ?>

                    <div class="row g-4">
                        <?php if (empty($athletes)): ?>
                            <div class="col-12">
                                <div class="card p-5 text-center shadow-none border">
                                    <i class='bx bx-user-x fs-1 text-muted mb-3'></i>
                                    <h5>ไม่พบคลังนักกีฬาในรุ่นนี้</h5>
                                </div>
                            </div>
                        <?php else: ?>
                            <?php foreach ($athletes as $athlete): ?>
                                <div class="col-md-6 col-xl-4">
                                    <div class="card athlete-card shadow-sm">
                                        <div class="athlete-img-container">
                                            <?php 
                                                // ใช้รูปที่อัพโหลดก่อน ถ้าไม่มีใช้รูปจากระบบโรงเรียน
                                                if (!empty($athlete['athlete_image'])) {
                                                    $photoUrl = base_url('uploads/athletes/' . $athlete['athlete_image']);
                                                } else {
                                                    $photoUrl = "https://skj.ac.th/uploads/students_photo/{$athlete['StudentCode']}.jpg";
                                                }
                                                $fallbackUrl = 'https://ui-avatars.com/api/?name=' . urlencode($athlete['StudentFirstName'] . ' ' . $athlete['StudentLastName']) . '&background=random&size=200';
                                            ?>
                                            <img src="<?= $photoUrl ?>" 
                                                 onerror="this.src='<?= $fallbackUrl ?>'" 
                                                 alt="<?= $athlete['StudentFirstName'] ?>" 
                                                 class="athlete-img">
                                            <span class="badge bg-primary team-badge">No. <?= $athlete['StudentCode'] ?></span>
                                        </div>
                                        <div class="card-body p-4">
                                            <div class="mb-2">
                                                <small class="text-primary fw-bold text-uppercase">ชั้นมัธยมศึกษาปีที่ <?= $athlete['StudentClass'] ?></small>
                                            </div>
                                            <h5 class="fw-bold mb-1"><?= $athlete['StudentPrefix'].$athlete['StudentFirstName'].' '.$athlete['StudentLastName'] ?></h5>
                                            <div class="d-flex align-items-center text-muted small mt-3">
                                                <i class='bx bx-award me-1'></i> สถาบันกีฬาโรงเรียน SKJ
                                            </div>
                                            <hr class="my-3 opacity-50">
                                            <a href="javascript:void(0)" class="btn btn-label-primary w-100 btn-sm rounded-pill">
                                                ดูรายละเอียด/ผลงาน
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
