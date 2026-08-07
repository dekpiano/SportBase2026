<style>
    :root {
        --sb-orange: #fd7e14;
        --sb-orange-gradient: linear-gradient(135deg, #fd7e14 0%, #ff9e43 100%);
    }

    .public-match-header {
        background: var(--sb-orange-gradient);
        border-radius: 20px;
        padding: 2.25rem 2rem;
        color: white;
        margin-bottom: 2rem;
        box-shadow: 0 10px 30px rgba(253, 126, 20, 0.22);
    }

    .user-match-card {
        border: none;
        border-radius: 20px;
        transition: all 0.3s cubic-bezier(0.165, 0.84, 0.44, 1);
        background: #ffffff;
        box-shadow: 0 6px 20px rgba(0,0,0,0.04);
        display: flex;
        flex-direction: column;
    }

    .user-match-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 14px 30px rgba(253, 126, 20, 0.15);
    }

    .badge-orange-light {
        background-color: rgba(253, 126, 20, 0.12) !important;
        color: #d95300 !important;
        border: 1px dashed rgba(253, 126, 20, 0.3);
    }

    .btn-orange {
        background-color: #fd7e14 !important;
        color: #ffffff !important;
        border: none;
    }
    .btn-orange:hover, .btn-orange:focus {
        background-color: #e66a00 !important;
        color: #ffffff !important;
        box-shadow: 0 4px 15px rgba(253, 126, 20, 0.35);
    }

    .bg-gradient-orange {
        background: var(--sb-orange-gradient) !important;
    }

    .info-item {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 0.88rem;
        color: #475569;
        background: #f8fafc;
        padding: 0.5rem 0.75rem;
        border-radius: 10px;
    }

    @media (max-width: 768px) {
        .public-match-header {
            padding: 1.5rem 1.25rem;
            text-align: center;
        }
    }
</style>

<div class="layout-page">
    <?php echo view('User/UserLeyout/UserNavbar'); ?>

    <div class="content-wrapper">
        <div class="container-xxl flex-grow-1 container-p-y">
            
            <!-- Page Header -->
            <div class="public-match-header">
                <div class="row align-items-center">
                    <div class="col-md-9">
                        <h2 class="text-white fw-bold mb-2">
                            <i class='bx bx-trophy me-2'></i>ตารางและผลการแข่งขันกีฬา
                        </h2>
                        <p class="text-white opacity-75 mb-0 fs-6">
                            ติดตามข่าวสาร ตารางการแข่งขัน ผลการแข่งขัน และประมวลภาพความประทับใจของทัพนักเรียนสวนกุหลาบวิทยาลัย (จิรประวัติ) นครสวรรค์
                        </p>
                    </div>
                </div>
            </div>

            <!-- Match Cards Grid -->
            <div class="row g-4">
                <?php if (empty($matches)): ?>
                    <div class="col-12 text-center py-5">
                        <div class="card p-5 border-0 shadow-sm rounded-20">
                            <i class='bx bx-calendar-x fs-huge text-light opacity-50 mb-3'></i>
                            <h5 class="text-muted fw-semibold">ยังไม่มีรายการแข่งขันในขณะนี้</h5>
                            <p class="text-muted small mb-0">โปรดติดตามข้อมูลข่าวสารอัปเดตตารางแข่งขันจากทางโรงเรียนเพิ่มเติม</p>
                        </div>
                    </div>
                <?php else: ?>
                    <?php foreach ($matches as $match): ?>
                        <?php 
                            $statusColor = [
                                'Upcoming' => 'info',
                                'In Progress' => 'warning',
                                'Finished' => 'success',
                                'Canceled' => 'danger'
                            ];
                            $statusText = [
                                'Upcoming' => '⏳ รอดำเนินการ',
                                'In Progress' => '🔥 กำลังแข่งขัน',
                                'Finished' => '✅ แข่งขันเสร็จสิ้น',
                                'Canceled' => '❌ ยกเลิก'
                            ];
                            $color = $statusColor[$match['match_status']] ?? 'secondary';
                            $text = $statusText[$match['match_status']] ?? $match['match_status'];

                            $startTs = strtotime($match['match_date']);
                            $endTs = !empty($match['match_end_date']) ? strtotime($match['match_end_date']) : $startTs;

                            $startDateOnly = date('d/m/Y', $startTs);
                            $endDateOnly = date('d/m/Y', $endTs);
                            $startTime = date('H:i', $startTs);
                            $endTime = date('H:i', $endTs);

                            if ($startDateOnly === $endDateOnly) {
                                $dateDisplay = $startDateOnly . ($startTime !== '00:00' || $endTime !== '00:00' ? ' • เวลา ' . $startTime . ' - ' . $endTime . ' น.' : '');
                            } else {
                                $dateDisplay = date('d/m/Y H:i', $startTs) . ' - ' . date('d/m/Y H:i', $endTs) . ' น.';
                            }
                        ?>
                        <div class="col-md-6 col-lg-4">
                            <div class="card user-match-card h-100 p-3">
                                <!-- Top Bar -->
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <span class="badge bg-label-primary rounded-pill px-3 py-2 fw-bold">
                                        <i class='bx bx-group me-1'></i><?= $match['team_name'] ?> (<?= $match['team_sport_type'] ?>)
                                    </span>
                                    <span class="badge bg-label-<?= $color ?> rounded-pill px-3 py-2 fw-semibold">
                                        <?= $text ?>
                                    </span>
                                </div>

                                <!-- Match Title -->
                                <h5 class="fw-bold text-dark mb-3 lh-sm">
                                    <?= $match['match_title'] ?>
                                </h5>

                                <!-- Outcome Highlight Box -->
                                <?php if (!empty($match['match_result'])): ?>
                                    <div class="badge-orange-light p-2.5 px-3 rounded-12 w-100 text-start my-2 fs-6 fw-bold shadow-xs">
                                        🏆 ผลการแข่งขัน: <?= $match['match_result'] ?>
                                    </div>
                                <?php endif; ?>

                                <!-- Match Info Pill Container -->
                                <div class="d-flex flex-column gap-2 my-2 mt-auto">
                                    <div class="info-item text-primary fw-semibold">
                                        <i class='bx bx-calendar fs-5 me-1 text-primary'></i>
                                        <span><?= $dateDisplay ?></span>
                                    </div>
                                    <?php if(!empty($match['match_location'])): ?>
                                    <div class="info-item text-muted">
                                        <i class='bx bx-map-pin fs-5 me-1 text-danger'></i>
                                        <span class="text-truncate"><?= $match['match_location'] ?></span>
                                    </div>
                                    <?php endif; ?>
                                </div>

                                <?php if($match['match_note']): ?>
                                    <div class="mt-2 p-2 bg-light rounded-12 small text-muted">
                                        <i class='bx bx-info-circle me-1'></i><?= $match['match_note'] ?>
                                    </div>
                                <?php endif; ?>

                                <!-- Report / Photo Gallery Button -->
                                <?php if ($match['match_status'] === 'Finished' || !empty($match['report_id'])): ?>
                                    <button type="button" class="btn btn-orange btn-sm w-100 rounded-pill fw-bold py-2 mt-3 shadow-xs btn-view-user-report"
                                            data-id="<?= $match['match_id'] ?>"
                                            data-title="<?= htmlspecialchars($match['match_title'], ENT_QUOTES) ?>"
                                            data-team="<?= htmlspecialchars($match['team_name'], ENT_QUOTES) ?>"
                                            data-date="<?= htmlspecialchars($dateDisplay, ENT_QUOTES) ?>">
                                        <i class='bx bx-trophy me-1'></i> ดูรายงานผล & ภาพบรรยากาศ
                                    </button>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Modal User View Match Report -->
<div class="modal fade" id="modalUserReport" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg rounded-20">
            <div class="modal-header bg-gradient-orange py-3">
                <h5 class="modal-title text-white fw-bold">
                    <i class='bx bx-trophy me-2'></i>รายงานผลการแข่งขันและภาพบรรยากาศ
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <div class="alert alert-warning border-0 bg-label-warning mb-4 py-3 px-3 rounded-16 text-dark" id="userReportHeaderInfo">
                    <!-- Filled by JS -->
                </div>

                <div class="mb-4" id="userReportOutcomeContainer">
                    <h6 class="fw-bold text-dark mb-2"><i class='bx bx-award text-warning fs-4 me-1'></i>ผลการแข่งขัน / รางวัลที่ได้รับ</h6>
                    <div class="badge-orange-light p-3 rounded-16 fs-5 fw-bold text-dark shadow-xs" id="user_report_result">
                        -
                    </div>
                </div>

                <div class="mb-4" id="userReportSummaryContainer">
                    <h6 class="fw-bold text-dark mb-2"><i class='bx bx-file text-primary me-1'></i>สรุปภาพรวมและบรรยากาศการแข่งขัน</h6>
                    <div class="p-3 bg-light rounded-16 text-secondary lh-base fs-6" id="user_report_summary">
                        -
                    </div>
                </div>

                <div>
                    <h6 class="fw-bold text-dark mb-3"><i class='bx bx-images text-danger me-1'></i>ภาพบรรยากาศการแข่งขัน</h6>
                    <div class="row g-3" id="userReportGallery">
                        <!-- Loaded dynamically via JS -->
                    </div>
                </div>
            </div>
            <div class="modal-footer border-top-0 pb-4 px-4">
                <button type="button" class="btn btn-label-secondary px-4 fw-bold rounded-pill" data-bs-dismiss="modal">ปิดหน้าต่าง</button>
            </div>
        </div>
    </div>
</div>

<!-- Image Lightbox Modal for Enlarge View -->
<div class="modal fade" id="modalImageLightbox" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content bg-transparent border-0 shadow-none">
            <div class="text-end mb-2">
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="text-center">
                <img src="" id="lightboxImage" class="img-fluid rounded-20 shadow-lg" style="max-height: 85vh; object-fit: contain;">
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    $(document).on('click', '.btn-view-user-report', function() {
        const d = $(this).data();
        $('#userReportHeaderInfo').html(`
            <div class="fw-bold text-dark fs-6 mb-1"><i class='bx bx-trophy text-warning me-1'></i>${d.title}</div>
            <div class="small text-muted"><i class='bx bx-group me-1'></i>ทีม: ${d.team} | <i class='bx bx-calendar me-1'></i>${d.date}</div>
        `);

        $('#user_report_result').text('กำลังโหลดข้อมูล...');
        $('#user_report_summary').text('กำลังโหลดข้อมูล...');
        $('#userReportGallery').html('<div class="col-12 text-muted small"><i class="bx bx-loader-alt bx-spin me-1"></i>กำลังโหลดคลังภาพบรรยากาศ...</div>');

        $.ajax({
            url: '<?= base_url('User/Match/GetReport') ?>/' + d.id,
            method: 'GET',
            success: function(res) {
                if(res.success && res.report) {
                    $('#user_report_result').text(res.report.match_result || 'ไม่ระบุสรุปผล');
                    
                    if (res.report.match_summary) {
                        $('#user_report_summary').text(res.report.match_summary);
                        $('#userReportSummaryContainer').show();
                    } else {
                        $('#userReportSummaryContainer').hide();
                    }

                    renderUserReportGallery(res.report.photos, res.report.photo_urls);
                } else {
                    $('#user_report_result').text('ยังไม่มีการลงบันทึกผลการแข่งขัน');
                    $('#userReportSummaryContainer').hide();
                    renderUserReportGallery([], []);
                }
            }
        });

        $('#modalUserReport').modal('show');
    });

    function renderUserReportGallery(photos, photoUrls) {
        if (!photos || photos.length === 0) {
            $('#userReportGallery').html('<div class="col-12 text-muted small p-2"><i class="bx bx-image-alt me-1"></i>ยังไม่มีรูปภาพบรรยากาศการแข่งขัน</div>');
            return;
        }

        let html = '';
        photos.forEach(function(photo, idx) {
            const photoUrl = (photoUrls && photoUrls[idx]) ? photoUrls[idx] : '<?= base_url('uploads/matches/') ?>/' + photo;
            html += `
                <div class="col-6 col-sm-4 col-md-3">
                    <div class="position-relative border rounded-16 overflow-hidden shadow-sm user-photo-card" style="cursor: pointer;" onclick="openLightbox('${photoUrl}')">
                        <img src="${photoUrl}" class="w-100 object-fit-cover hover-zoom" style="height: 140px; transition: transform 0.3s ease;" alt="ภาพการแข่งขัน">
                    </div>
                </div>
            `;
        });
        $('#userReportGallery').html(html);
    }
});

function openLightbox(url) {
    $('#lightboxImage').attr('src', url);
    $('#modalImageLightbox').modal('show');
}
</script>
