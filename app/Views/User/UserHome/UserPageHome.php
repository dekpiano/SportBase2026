<!-- Layout container -->
<div class="layout-page">
    <?php echo view('User/UserLeyout/UserNavbar'); ?>

    <style>
        :root {
            --sb-orange: #fd7e14;
            --sb-orange-grad: linear-gradient(135deg, #fd7e14 0%, #ff9e43 100%);
            --sb-shadow: 0 10px 25px rgba(253, 126, 20, 0.2);
        }

        .home-hero-card {
            background: var(--sb-orange-grad);
            border-radius: 24px;
            color: white;
            padding: 2.25rem 2rem;
            margin-bottom: 1.5rem;
            box-shadow: var(--sb-shadow);
            position: relative;
            overflow: hidden;
            border: none;
        }

        .home-hero-card::after {
            content: '';
            position: absolute;
            top: -40%;
            right: -8%;
            width: 280px;
            height: 280px;
            background: rgba(255, 255, 255, 0.12);
            border-radius: 50%;
            pointer-events: none;
        }

        .hero-badge {
            background: rgba(255, 255, 255, 0.22);
            backdrop-filter: blur(8px);
            border: 1px solid rgba(255, 255, 255, 0.35);
            color: white;
            font-size: 0.75rem;
            font-weight: 700;
            padding: 0.35rem 0.85rem;
            border-radius: 20px;
            display: inline-flex;
            align-items: center;
        }

        .quick-action-chip {
            background: white;
            border: 1px solid #edf2f7;
            border-radius: 16px;
            padding: 0.85rem 1rem;
            display: flex;
            align-items: center;
            gap: 12px;
            text-decoration: none !important;
            color: #2d3748;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.03);
            transition: all 0.25s ease;
        }

        .quick-action-chip:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(253, 126, 20, 0.15);
            border-color: #fd7e14;
            color: #fd7e14;
        }

        .chip-icon-box {
            width: 42px;
            height: 42px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.35rem;
            flex-shrink: 0;
        }

        .mobile-home-card {
            background: white;
            border-radius: 20px;
            padding: 1.25rem;
            margin-bottom: 1rem;
            border: 1px solid #edf2f7;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.04);
            transition: all 0.2s ease;
        }

        .mobile-home-card:hover {
            border-color: #fd7e14;
            box-shadow: 0 6px 20px rgba(253, 126, 20, 0.12);
        }

        .search-box-card {
            background: white;
            border-radius: 20px;
            padding: 1.25rem;
            border: 1px solid #edf2f7;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.04);
        }

        .badge-live {
            background: #ff4757;
            color: white;
            animation: pulse-live 1.8s infinite;
        }

        @keyframes pulse-live {
            0% { box-shadow: 0 0 0 0 rgba(255, 71, 87, 0.5); }
            70% { box-shadow: 0 0 0 8px rgba(255, 71, 87, 0); }
            100% { box-shadow: 0 0 0 0 rgba(255, 71, 87, 0); }
        }

        @media (max-width: 768px) {
            .home-hero-card {
                padding: 1.5rem 1.25rem;
                border-radius: 20px;
            }
            .home-hero-card h2 {
                font-size: 1.4rem;
            }
        }
    </style>

    <div class="content-wrapper">
        <div class="container-xxl flex-grow-1 container-p-y">
            
            <!-- Hero Banner -->
            <div class="home-hero-card">
                <div class="row align-items-center">
                    <div class="col-lg-8">
                        <div class="hero-badge mb-2">
                            <i class="bx bx-award me-1"></i> SKJ SPORTBASE 2026
                        </div>
                        <h2 class="text-white fw-bold mb-2">ยินดีต้อนรับสู่ศูนย์รวมข้อมูลนักกีฬา 🏆</h2>
                        <p class="mb-3 text-white-50 opacity-90 small">
                            ระบบจัดการข้อมูลและติดตามผลการแข่งขันนักกีฬา โรงเรียนสวนกุหลาบวิทยาลัย (จิรประวัติ) นครสวรรค์
                        </p>
                        <div class="d-flex gap-2 flex-wrap">
                            <a href="<?= base_url('User/Match') ?>" class="btn btn-light text-orange fw-bold rounded-pill px-4 shadow-sm">
                                <i class="bx bx-calendar-event me-1"></i> ดูตารางแข่งขัน
                            </a>
                            <a href="<?= base_url('User/Match') ?>" class="btn btn-outline-light text-white rounded-pill px-4">
                                <i class="bx bx-trophy me-1"></i> รายงานผลงาน
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Action Shortcut Chips for Mobile -->
            <div class="row g-2 mb-4">
                <div class="col-6 col-md-4">
                    <a href="<?= base_url('User/Match') ?>" class="quick-action-chip">
                        <div class="chip-icon-box bg-label-primary text-primary">
                            <i class="bx bx-trophy"></i>
                        </div>
                        <div>
                            <div class="fw-bold lh-1" style="font-size: 0.9rem;">ตารางแข่งขัน</div>
                            <small class="text-muted" style="font-size: 0.72rem;">โปรแกรมทั้งหมด</small>
                        </div>
                    </a>
                </div>
                <div class="col-6 col-md-4">
                    <a href="<?= base_url('User/Match') ?>" class="quick-action-chip">
                        <div class="chip-icon-box bg-label-warning text-orange">
                            <i class="bx bx-check-shield"></i>
                        </div>
                        <div>
                            <div class="fw-bold lh-1" style="font-size: 0.9rem;">รายงานผลงาน</div>
                            <small class="text-muted" style="font-size: 0.72rem;">ภาพบรรยากาศ</small>
                        </div>
                    </a>
                </div>
                <div class="col-12 col-md-4">
                    <a href="<?= base_url('LoginOfficerSportBase') ?>" class="quick-action-chip">
                        <div class="chip-icon-box bg-label-info text-info">
                            <i class="bx bx-user-circle"></i>
                        </div>
                        <div>
                            <div class="fw-bold lh-1" style="font-size: 0.9rem;">เข้าสู่ระบบเจ้าหน้าที่</div>
                            <small class="text-muted" style="font-size: 0.72rem;">โค้ช / ผู้จัดการทีม</small>
                        </div>
                    </a>
                </div>
            </div>

            <!-- Search Section -->
            <div class="search-box-card mb-4">
                <div class="row align-items-center g-2">
                    <div class="col-md-8">
                        <div class="input-group input-group-merge">
                            <span class="input-group-text border-0 bg-light"><i class="bx bx-search fs-4 text-orange"></i></span>
                            <input type="text" id="homeSearchInput" class="form-control border-0 bg-light py-2 fw-semibold" placeholder="ค้นหารายการแข่งขัน, ชื่อทีม หรือสถานที่...">
                        </div>
                    </div>
                    <div class="col-md-4 text-md-end">
                        <small class="text-muted"><i class="bx bx-info-circle me-1"></i> พิมพ์เพื่อค้นหาข้อมูลในตารางทันที</small>
                    </div>
                </div>
            </div>

            <!-- Upcoming Matches Section -->
            <div class="card border-0 shadow-sm rounded-20 overflow-hidden mb-4">
                <div class="card-header bg-white border-bottom py-3 d-flex justify-content-between align-items-center flex-wrap gap-2">
                    <div class="d-flex align-items-center gap-2">
                        <div class="p-2 bg-label-warning rounded-circle text-orange">
                            <i class="bx bx-calendar-star fs-4"></i>
                        </div>
                        <div>
                            <h5 class="mb-0 fw-bold text-dark">ตารางการแข่งขันที่กำลังจะมาถึง</h5>
                            <small class="text-muted">โปรแกรมการแข่งขันล่าสุดที่รอดำเนินการและกำลังแข่งขัน</small>
                        </div>
                    </div>
                    <a href="<?= base_url('User/Match') ?>" class="btn btn-sm btn-label-warning text-orange rounded-pill fw-bold">
                        ดูตารางทั้งหมด <i class="bx bx-chevron-right ms-1"></i>
                    </a>
                </div>

                <div class="card-body p-0">
                    <?php if (empty($upcomingMatches)): ?>
                        <div class="text-center py-5">
                            <i class='bx bx-calendar-x fs-1 text-muted opacity-40 mb-2 d-block'></i>
                            <p class="text-muted mb-0">ยังไม่มีโปรแกรมการแข่งขันในขณะนี้</p>
                        </div>
                    <?php else: ?>
                        
                        <!-- Desktop View Table -->
                        <div class="table-responsive d-none d-md-block">
                            <table class="table table-hover align-middle mb-0" id="desktopMatchTable">
                                <thead class="bg-light">
                                    <tr>
                                        <th class="border-0 ps-4" width="160">รุ่น/ทีม</th>
                                        <th class="border-0">รายการแข่งขัน</th>
                                        <th class="border-0" width="180">วัน/เวลา</th>
                                        <th class="border-0" width="200">สถานที่</th>
                                        <th class="border-0 text-center" width="130">สถานะ</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($upcomingMatches as $match): 
                                        $isLive = ($match['match_status'] == 'In Progress');
                                        $sColor = $isLive ? 'danger badge-live' : 'info';
                                        $sText = $isLive ? '🔴 กำลังแข่งขัน' : '🔵 รอดำเนินการ';

                                        // Date Thai Format with Buddhist Year
                                        $thDate = date('j M', strtotime($match['match_date'])) . ' ' . (date('Y', strtotime($match['match_date'])) + 543);
                                        $thTime = !empty($match['match_time']) ? date('H:i', strtotime($match['match_time'])) . ' น.' : '-';
                                    ?>
                                        <tr class="searchable-match-item">
                                            <td class="ps-4">
                                                <span class="badge bg-label-primary font-bold"><?= htmlspecialchars($match['team_name'], ENT_QUOTES) ?></span>
                                                <div class="small text-muted mt-1"><i class="bx bx-run me-1"></i><?= htmlspecialchars($match['team_sport_type'], ENT_QUOTES) ?></div>
                                            </td>
                                            <td>
                                                <div class="fw-bold text-dark"><?= htmlspecialchars($match['match_title'], ENT_QUOTES) ?></div>
                                                <?php if(!empty($match['match_result'])): ?>
                                                    <div class="small fw-bold text-orange mt-1">
                                                        <i class="bx bx-trophy me-1"></i>ผล: <?= htmlspecialchars($match['match_result'], ENT_QUOTES) ?>
                                                    </div>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <div class="fw-semibold text-primary"><i class="bx bx-calendar me-1"></i><?= $thDate ?></div>
                                                <small class="text-muted"><i class="bx bx-time me-1"></i><?= $thTime ?></small>
                                            </td>
                                            <td>
                                                <small class="text-dark d-block"><i class="bx bx-map me-1 text-muted"></i><?= htmlspecialchars($match['match_location'] ?: '-', ENT_QUOTES) ?></small>
                                            </td>
                                            <td class="text-center">
                                                <span class="badge bg-<?= $sColor ?> rounded-pill px-3 py-2 fw-bold"><?= $sText ?></span>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>

                        <!-- Mobile View Compact Cards -->
                        <div class="p-3 d-md-none" id="mobileMatchCards">
                            <?php foreach ($upcomingMatches as $match): 
                                $isLive = ($match['match_status'] == 'In Progress');
                                $sColor = $isLive ? 'danger badge-live' : 'info';
                                $sText = $isLive ? '🔴 กำลังแข่งขัน' : '🔵 รอดำเนินการ';

                                $thDate = date('j M', strtotime($match['match_date'])) . ' ' . (date('Y', strtotime($match['match_date'])) + 543);
                                $thTime = !empty($match['match_time']) ? date('H:i', strtotime($match['match_time'])) . ' น.' : '-';
                            ?>
                                <div class="mobile-home-card searchable-match-item">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <span class="badge bg-label-primary fw-bold"><?= htmlspecialchars($match['team_name'], ENT_QUOTES) ?> (<?= htmlspecialchars($match['team_sport_type'], ENT_QUOTES) ?>)</span>
                                        <span class="badge bg-<?= $sColor ?> rounded-pill px-2.5 py-1.5 fw-bold" style="font-size: 0.72rem;"><?= $sText ?></span>
                                    </div>

                                    <h6 class="fw-bold text-dark mb-2" style="font-size: 0.95rem; line-height: 1.35;"><?= htmlspecialchars($match['match_title'], ENT_QUOTES) ?></h6>

                                    <div class="d-flex flex-wrap gap-2 text-muted small mb-2">
                                        <span class="text-primary fw-semibold"><i class="bx bx-calendar me-1"></i><?= $thDate ?></span>
                                        <span><i class="bx bx-time me-1"></i><?= $thTime ?></span>
                                    </div>

                                    <div class="small text-muted">
                                        <i class="bx bx-map me-1 text-secondary"></i><?= htmlspecialchars($match['match_location'] ?: '-', ENT_QUOTES) ?>
                                    </div>

                                    <?php if(!empty($match['match_result'])): ?>
                                        <div class="mt-2 pt-2 border-top small fw-bold text-orange">
                                            <i class="bx bx-trophy me-1"></i>ผลการแข่งขัน: <?= htmlspecialchars($match['match_result'], ENT_QUOTES) ?>
                                        </div>
                                    <?php endif; ?>
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
document.addEventListener('DOMContentLoaded', function() {
    // Instant Live Search Filter for Home Page
    const searchInput = document.getElementById('homeSearchInput');
    if (searchInput) {
        searchInput.addEventListener('keyup', function() {
            const term = this.value.toLowerCase().trim();
            const items = document.querySelectorAll('.searchable-match-item');
            
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