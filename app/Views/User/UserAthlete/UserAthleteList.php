<style>
    :root {
        --sb-orange: #fd7e14;
        --sb-orange-grad: linear-gradient(135deg, #fd7e14 0%, #ff9e43 100%);
    }

    .athlete-card {
        border: none;
        border-radius: 16px;
        transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
        background: #ffffff;
        overflow: hidden;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
        border: 1px solid #edf2f7;
    }
    
    .athlete-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 12px 24px rgba(253, 126, 20, 0.12);
        border-color: rgba(253, 126, 20, 0.3);
    }

    .athlete-img-container {
        position: relative;
        padding-top: 130%; /* 3:4 aspect ratio for portraits */
        background: #f8fafc;
        overflow: hidden;
    }

    .athlete-img {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.5s ease;
    }

    .athlete-card:hover .athlete-img {
        transform: scale(1.04);
    }

    .student-code-badge {
        position: absolute;
        bottom: 12px;
        left: 12px;
        z-index: 2;
        background: rgba(15, 23, 42, 0.75);
        backdrop-filter: blur(4px);
        color: #ffffff;
        font-size: 0.72rem;
        font-weight: 600;
        padding: 4px 10px;
        border-radius: 20px;
    }

    .nav-pills-horizontal {
        display: flex;
        gap: 8px;
        overflow-x: auto;
        padding-bottom: 8px;
        -webkit-overflow-scrolling: touch;
    }

    .nav-pills-horizontal::-webkit-scrollbar {
        height: 4px;
    }

    .nav-pills-horizontal::-webkit-scrollbar-thumb {
        background: #e2e8f0;
        border-radius: 10px;
    }

    .team-filter-pill {
        white-space: nowrap;
        background: #ffffff;
        border: 1px solid #edf2f7;
        color: #475569;
        font-size: 0.88rem;
        font-weight: 600;
        padding: 8px 16px;
        border-radius: 30px;
        transition: all 0.2s ease;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        text-decoration: none !important;
    }

    .team-filter-pill:hover {
        color: var(--sb-orange);
        border-color: var(--sb-orange);
        background: rgba(253, 126, 20, 0.04);
    }

    .team-filter-pill.active {
        background: var(--sb-orange-grad) !important;
        color: #ffffff !important;
        border-color: transparent !important;
        box-shadow: 0 4px 12px rgba(253, 126, 20, 0.25);
    }

    .team-filter-pill .badge {
        font-size: 0.72rem;
        padding: 4px 8px;
        border-radius: 20px;
        font-weight: 700;
        background: #f1f5f9 !important;
        color: #64748b !important;
        transition: all 0.2s ease;
    }

    .team-filter-pill:hover .badge {
        background: rgba(253, 126, 20, 0.1) !important;
        color: #fd7e14 !important;
    }

    .team-filter-pill.active .badge {
        background: #ffffff !important;
        color: #fd7e14 !important;
    }

    .search-input-wrap {
        position: relative;
    }

    .search-input-wrap i {
        position: absolute;
        top: 50%;
        left: 14px;
        transform: translateY(-50%);
        color: #94a3b8;
    }

    .search-input-wrap input {
        padding-left: 40px;
        border-radius: 30px;
        border: 1px solid #edf2f7;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.02);
    }

    .search-input-wrap input:focus {
        border-color: #fd7e14;
        box-shadow: 0 0 0 3px rgba(253, 126, 20, 0.15);
    }
</style>

<div class="layout-page">
    <?php echo view('User/UserLeyout/UserNavbar'); ?>

    <div class="content-wrapper">
        <div class="container-xxl flex-grow-1 container-p-y">
            
            <!-- Header Section -->
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
                <div>
                    <h4 class="fw-bold mb-1 text-dark">
                        ทำเนียบนักเรียนนักกีฬา 🏃‍♂️
                    </h4>
                    <p class="text-muted mb-0 small">รายชื่อและข้อมูลนักกีฬาของโรงเรียนตามรุ่นและประเภทกีฬา</p>
                </div>
                <div class="search-input-wrap" style="min-width: 280px;">
                    <i class="bx bx-search fs-5"></i>
                    <input type="text" id="athleteSearch" class="form-control py-2" placeholder="ค้นหาชื่อ หรือเลขประจำตัว...">
                </div>
            </div>

            <!-- Mobile & Tablet Horizontal Scroll Filters -->
            <div class="d-lg-none mb-3">
                <div class="nav-pills-horizontal">
                    <?php foreach ($teams as $team): 
                        $isActive = (isset($currentTeam) && $currentTeam['team_id'] == $team['team_id']);
                    ?>
                        <a href="?team_id=<?= $team['team_id'] ?>" class="team-filter-pill <?= $isActive ? 'active' : '' ?>">
                            <span><?= htmlspecialchars($team['team_name'], ENT_QUOTES) ?></span>
                            <span class="badge">
                                <?= $team['athlete_count'] ?>
                            </span>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>

            <div class="row">
                <!-- Desktop Sidebar Filter (Visible on large screens only) -->
                <div class="col-lg-3 d-none d-lg-block">
                    <div class="card border-0 shadow-sm p-4 rounded-20 position-sticky" style="top: 1.5rem; border: 1px solid #edf2f7 !important;">
                        <h6 class="fw-bold text-dark mb-3"><i class='bx bx-filter-alt me-2 text-orange'></i>รุ่น / ทีมทั้งหมด</h6>
                        <div class="d-flex flex-column gap-2">
                            <?php foreach ($teams as $team): 
                                $isActive = (isset($currentTeam) && $currentTeam['team_id'] == $team['team_id']);
                            ?>
                                <a href="?team_id=<?= $team['team_id'] ?>" 
                                   class="team-filter-pill justify-content-between w-100 <?= $isActive ? 'active' : '' ?>">
                                    <div class="d-flex flex-column text-start">
                                        <span class="lh-sm"><?= htmlspecialchars($team['team_name'], ENT_QUOTES) ?></span>
                                        <small class="opacity-75" style="font-size: 0.7rem; font-weight: normal;"><?= htmlspecialchars($team['team_sport_type'], ENT_QUOTES) ?></small>
                                    </div>
                                    <span class="badge">
                                        <?= $team['athlete_count'] ?>
                                    </span>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>

                <!-- Athlete Grid -->
                <div class="col-lg-9">
                    <?php if (isset($currentTeam)): ?>
                        <div class="card bg-orange-light border-0 p-3 rounded-16 mb-4 d-flex flex-row justify-content-between align-items-center flex-wrap gap-2" style="background: rgba(253, 126, 20, 0.06); border-left: 4px solid #fd7e14 !important;">
                            <div>
                                <h5 class="fw-bold text-dark mb-0"><?= htmlspecialchars($currentTeam['team_name'], ENT_QUOTES) ?></h5>
                                <small class="text-muted"><?= htmlspecialchars($currentTeam['team_sport_type'], ENT_QUOTES) ?></small>
                            </div>
                            <span class="badge bg-label-warning text-orange rounded-pill px-3 py-2 fw-semibold">
                                สมาชิกในรุ่น: <?= count($athletes) ?> คน
                            </span>
                        </div>
                    <?php endif; ?>

                    <div class="row g-3" id="athleteGrid">
                        <?php if ($athletes === null): ?>
                            <div class="col-12">
                                <div class="card p-5 text-center shadow-none border rounded-20" style="background: rgba(253, 126, 20, 0.02); border-style: dashed !important; border-color: rgba(253, 126, 20, 0.3) !important;">
                                    <div class="p-3 bg-white d-inline-flex rounded-circle shadow-xs mx-auto mb-3" style="width: fit-content;">
                                        <i class='bx bx-run fs-1 text-orange'></i>
                                    </div>
                                    <h5 class="fw-bold text-dark">กรุณาเลือกรุ่น หรือประเภทกีฬา 👆</h5>
                                    <p class="text-muted small mb-0">เลือกรายชื่อทีมหรือรุ่นนักกีฬาด้านบน/เมนูด้านซ้ายเพื่อแสดงข้อมูลทำเนียบนักกีฬา</p>
                                </div>
                            </div>
                        <?php elseif (empty($athletes)): ?>
                            <div class="col-12">
                                <div class="card p-5 text-center shadow-none border rounded-20">
                                    <i class='bx bx-user-x fs-1 text-muted mb-2 d-block'></i>
                                    <h6 class="text-secondary mb-0">ไม่พบคลังรายชื่อนักกีฬาในรุ่นนี้</h6>
                                </div>
                            </div>
                        <?php else: ?>
                            <?php foreach ($athletes as $athlete): 
                                $persName = $athlete['StudentPrefix'].$athlete['StudentFirstName'].' '.$athlete['StudentLastName'];
                                // Use uploaded image if available, fallback to school database
                                if (!empty($athlete['athlete_image'])) {
                                    $photoUrl = base_url('uploads/athletes/' . $athlete['athlete_image']);
                                } else {
                                    $photoUrl = "https://skj.ac.th/uploads/students_photo/{$athlete['StudentCode']}.jpg";
                                }
                                $fallbackUrl = 'https://ui-avatars.com/api/?name=' . urlencode($athlete['StudentFirstName'] . ' ' . $athlete['StudentLastName']) . '&background=random&size=200';
                            ?>
                                <div class="col-6 col-sm-4 col-md-4 col-xl-3 athlete-grid-item">
                                    <div class="card athlete-card">
                                        <div class="athlete-img-container">
                                            <img src="<?= $photoUrl ?>" 
                                                 onerror="this.onerror=null; this.src='<?= $fallbackUrl ?>'" 
                                                 alt="<?= htmlspecialchars($athlete['StudentFirstName'], ENT_QUOTES) ?>" 
                                                 class="athlete-img">
                                            <span class="student-code-badge">#<?= htmlspecialchars($athlete['StudentCode'], ENT_QUOTES) ?></span>
                                        </div>
                                        <div class="card-body p-3">
                                            <div class="mb-1">
                                                <span class="badge bg-label-warning text-orange" style="font-size: 0.65rem; padding: 2px 6px;">ม.<?= htmlspecialchars($athlete['StudentClass'], ENT_QUOTES) ?></span>
                                            </div>
                                            <h6 class="fw-bold text-dark text-truncate mb-0" title="<?= htmlspecialchars($persName, ENT_QUOTES) ?>">
                                                <?= htmlspecialchars($persName, ENT_QUOTES) ?>
                                            </h6>
                                            <div class="d-flex align-items-center text-muted small mt-2" style="font-size: 0.68rem;">
                                                <i class='bx bx-check-shield text-success me-1'></i> นักกีฬาสังกัด SKJ
                                            </div>
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

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Client-side search filtering
    const searchInput = document.getElementById('athleteSearch');
    if (searchInput) {
        searchInput.addEventListener('keyup', function() {
            const term = this.value.toLowerCase().trim();
            const items = document.querySelectorAll('.athlete-grid-item');
            
            items.forEach(item => {
                const text = item.textContent.toLowerCase();
                if (text.includes(term)) {
                    item.style.display = '';
                } else {
                    item.style.display = 'none';
                }
            });
        });
    }
});
</script>
