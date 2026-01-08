<!-- Layout container -->
<div class="layout-page">
    <?php echo view('User/UserLeyout/UserNavbar'); ?>

    <div class="content-wrapper">
        <div class="container-xxl flex-grow-1 container-p-y">
            <div class="row">
                <div class="col-lg-12 mb-4 order-0">
                    <div class="card bg-primary text-white">
                        <div class="d-flex align-items-end row">
                            <div class="col-sm-7">
                                <div class="card-body">
                                    <h3 class="card-title text-white mb-3">ยินดีต้อนรับสู่ SKJ SportBase 2026! 🏆</h3>
                                    <p class="mb-4">
                                        ระบบจัดการข้อมูลนักกีฬา โรงเรียนสวนกุหลาบวิทยาลัย (จิรประวัติ) นครสวรรค์
                                        รวมข้อมูลความสามารถและผลงานของนักเรียนเข้าไว้ในที่เดียว
                                    </p>
                                    <a href="javascript:;" class="btn btn-outline-white text-white border-white">ดูทำเนียบนักกีฬา</a>
                                </div>
                            </div>
                            <div class="col-sm-5 text-center text-sm-left">
                                <div class="card-body pb-0 px-0 px-md-4">
                                    <img src="<?= base_url('assets/img/illustrations/athlete-silhouette.png') ?>" 
                                         height="160" alt="Athlete Silhouette" 
                                         style="filter: invert(1); mix-blend-mode: screen; opacity: 0.9;">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mt-4">
                <!-- Upcoming Matches Section -->
                <div class="col-md-12 mb-4">
                    <div class="card h-100 shadow-none border">
                        <div class="card-header d-flex justify-content-between align-items-center bg-transparent border-bottom">
                            <h5 class="card-title mb-0"><i class="bx bx-calendar-event me-2 text-primary"></i>ตารางการแข่งขันที่กำลังจะมาถึง</h5>
                            <a href="<?= base_url('User/Match') ?>" class="btn btn-sm btn-outline-primary rounded-pill">ดูตารางทั้งหมด</a>
                        </div>
                        <div class="card-body p-0">
                            <?php if (empty($upcomingMatches)): ?>
                                <div class="text-center py-5">
                                    <i class='bx bx-calendar-x fs-1 text-muted opacity-50 mb-2'></i>
                                    <p class="text-muted">ยังไม่มีโปรแกรมการแข่งขันในขณะนี้</p>
                                </div>
                            <?php else: ?>
                                <div class="table-responsive">
                                    <table class="table table-hover align-middle mb-0">
                                        <thead class="bg-light">
                                            <tr>
                                                <th class="border-0">รุ่น/ทีม</th>
                                                <th class="border-0">รายการการแข่งขัน</th>
                                                <th class="border-0">วัน/เวลา</th>
                                                <th class="border-0">สถานที่</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($upcomingMatches as $match): 
                                                $sColor = ($match['match_status'] == 'In Progress') ? 'warning' : 'info';
                                                $sText = ($match['match_status'] == 'In Progress') ? 'Live Now' : 'Upcoming';
                                            ?>
                                                <tr>
                                                    <td>
                                                        <span class="badge bg-label-primary"><?= $match['team_name'] ?></span>
                                                        <div class="small text-muted mt-1"><?= $match['team_sport_type'] ?></div>
                                                    </td>
                                                    <td class="fw-bold text-dark"><?= $match['match_title'] ?></td>
                                                    <td>
                                                        <div class="fw-semibold text-primary"><?= date('d/m/Y', strtotime($match['match_date'])) ?></div>
                                                        <small class="text-muted"><?= $match['match_time'] ? date('H:i', strtotime($match['match_time'])) . ' น.' : '-' ?></small>
                                                    </td>
                                                    <td><small class="text-truncate" style="max-width: 150px; display: inline-block;"><?= $match['match_location'] ?: '-' ?></small></td>
                                                    <td class="text-end">
                                                        <span class="badge bg-label-<?= $sColor ?> rounded-pill"><?= $sText ?></span>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Search Section -->
                <div class="col-md-12">
                    <div class="card mb-4">
                        <div class="card-body">
                            <h5 class="card-title">ค้นหานักกีฬา</h5>
                            <div class="input-group input-group-merge">
                                <span class="input-group-text" id="basic-addon-search31"><i class="bx bx-search"></i></span>
                                <input type="text" class="form-control" placeholder="ค้นหาด้วยชื่อ หรือเลขประจำตัว..." aria-label="Search..." aria-describedby="basic-addon-search31">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            
        </div>
    </div>
</div>