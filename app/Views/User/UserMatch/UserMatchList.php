<style>
    .match-card {
        border: none;
        border-radius: 15px;
        transition: all 0.3s ease;
        overflow: hidden;
        background: #fff;
    }
    .match-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1);
    }
    .status-banner {
        padding: 5px 15px;
        font-size: 0.8rem;
        font-weight: bold;
        text-transform: uppercase;
    }
</style>

<div class="layout-page">
    <?php echo view('User/UserLeyout/UserNavbar'); ?>

    <div class="content-wrapper">
        <div class="container-xxl flex-grow-1 container-p-y">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h4 class="fw-bold mb-0">
                    <span class="text-muted fw-light">หน้าแรก /</span> ตารางการแข่งขัน
                </h4>
            </div>

            <div class="row">
                <?php if (empty($matches)): ?>
                    <div class="col-12 text-center py-5">
                        <div class="card p-5">
                            <i class='bx bx-calendar-x fs-1 text-muted mb-3'></i>
                            <h5>ยังไม่มีข้อมูลการแข่งขันในระบบ</h5>
                        </div>
                    </div>
                <?php else: ?>
                    <?php foreach ($matches as $match): ?>
                        <div class="col-md-6 col-lg-4 mb-4">
                            <div class="card match-card h-100 shadow-sm">
                                <?php 
                                    $statusColor = [
                                        'Upcoming' => 'info',
                                        'In Progress' => 'warning',
                                        'Finished' => 'success',
                                        'Canceled' => 'danger'
                                    ];
                                    $statusText = [
                                        'Upcoming' => 'Coming Soon',
                                        'In Progress' => 'Live Now',
                                        'Finished' => 'Finished',
                                        'Canceled' => 'Canceled'
                                    ];
                                    $color = $statusColor[$match['match_status']] ?? 'secondary';
                                    $text = $statusText[$match['match_status']] ?? $match['match_status'];
                                ?>
                                <div class="bg-<?= $color ?> text-white status-banner d-flex justify-content-between align-items-center">
                                    <span><?= $text ?></span>
                                    <i class='bx bx-trophy'></i>
                                </div>
                                <div class="card-body">
                                    <div class="d-flex align-items-center mb-3">
                                        <div class="flex-shrink-0 avatar me-3">
                                            <span class="avatar-initial rounded bg-label-primary">
                                                <i class='bx bx-run'></i>
                                            </span>
                                        </div>
                                        <div>
                                            <h6 class="mb-0 fw-bold"><?= $match['team_name'] ?></h6>
                                            <small class="text-muted"><?= $match['team_sport_type'] ?></small>
                                        </div>
                                    </div>
                                    <h5 class="card-title fw-bold text-dark mb-3"><?= $match['match_title'] ?></h5>
                                    <div class="d-flex flex-column gap-2 border-top pt-3">
                                        <div class="d-flex align-items-center">
                                            <i class='bx bx-calendar me-2 text-primary'></i>
                                            <span><?= date('d F Y', strtotime($match['match_date'])) ?></span>
                                        </div>
                                        <div class="d-flex align-items-center">
                                            <i class='bx bx-time me-2 text-primary'></i>
                                            <span><?= $match['match_time'] ? date('H:i', strtotime($match['match_time'])) . ' น.' : 'ไม่ระบุเวลา' ?></span>
                                        </div>
                                        <div class="d-flex align-items-center">
                                            <i class='bx bx-map me-2 text-primary'></i>
                                            <span class="text-truncate"><?= $match['match_location'] ?: 'ไม่ระบุสถานที่' ?></span>
                                        </div>
                                    </div>
                                    <?php if($match['match_note']): ?>
                                        <div class="mt-3 p-2 bg-light rounded small">
                                            <strong>Note:</strong> <?= $match['match_note'] ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
