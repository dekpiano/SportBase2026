<?= $this->extend('Admin/layout/AdminLayout') ?>

<?= $this->section('content') ?>
<style>
    .match-header {
        background: linear-gradient(135deg, #fd7e14 0%, #ff9e43 100%);
        border-radius: 20px;
        padding: 2.5rem;
        color: white;
        margin-bottom: 2rem;
        box-shadow: 0 10px 30px rgba(253, 126, 20, 0.25);
        position: relative;
    }

    .btn-add-match {
        background: #fff;
        color: #fd7e14 !important;
        border: none;
        padding: 0.8rem 2rem;
        font-weight: 800;
        letter-spacing: 0.5px;
        box-shadow: 0 6px 20px rgba(0,0,0,0.15);
        transition: all 0.3s ease;
    }

    .btn-add-match:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.2);
        background: #fff;
        color: #e66a00 !important;
    }

    .match-card {
        border-radius: 15px;
        border: none;
        transition: all 0.3s ease;
    }

    .status-badge {
        padding: 6px 12px;
        border-radius: 10px;
        font-weight: 600;
        font-size: 0.8rem;
    }

    /* Mobile Match Cards */
    .mobile-match-card {
        border-radius: 16px;
        border: 1px solid #f1f4f8;
        background: #ffffff;
        padding: 1.25rem;
        margin-bottom: 1rem;
        box-shadow: 0 4px 15px rgba(0,0,0,0.03);
        transition: transform 0.2s, box-shadow 0.2s;
    }
    .mobile-match-card .card-top-bar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 0.75rem;
        gap: 8px;
        flex-wrap: wrap;
    }
    .mobile-match-card .match-title-text {
        font-size: 1.05rem;
        font-weight: 700;
        color: #1e293b;
        margin-bottom: 0.5rem;
        line-height: 1.4;
    }
    .mobile-match-card .info-pill {
        background: #f8fafc;
        border-radius: 10px;
        padding: 0.5rem 0.75rem;
        font-size: 0.85rem;
        display: flex;
        align-items: center;
        gap: 6px;
    }
    .mobile-match-card .action-bar {
        display: flex;
        justify-content: flex-end;
        align-items: center;
        gap: 8px;
        margin-top: 1rem;
        padding-top: 0.75rem;
        border-top: 1px dashed #e2e8f0;
    }

    @media (max-width: 768px) {
        .match-header {
            padding: 1.5rem;
            text-align: center;
        }
        .match-header h2 {
            font-size: 1.5rem;
        }
        .btn-add-match {
            width: 100%;
            padding: 0.75rem 1.25rem;
        }
    }

    /* Custom Orange Theme for Match Reports */
    .bg-gradient-orange {
        background: linear-gradient(135deg, #fd7e14 0%, #ff9e43 100%) !important;
    }
    .btn-orange {
        background-color: #fd7e14 !important;
        color: #ffffff !important;
        border: none;
    }
    .btn-orange:hover, .btn-orange:focus {
        background-color: #e66a00 !important;
        color: #ffffff !important;
        box-shadow: 0 4px 12px rgba(253, 126, 20, 0.35);
    }
    .badge-orange-light {
        background-color: rgba(253, 126, 20, 0.15) !important;
        color: #d95300 !important;
    }
    .text-orange {
        color: #fd7e14 !important;
    }

    /* Ensure SweetAlert2 always stays on top of all Bootstrap modals */
    .swal2-container {
        z-index: 99999 !important;
    }
</style>

<div class="container-xxl flex-grow-1 container-p-y">
    <div class="match-header">
        <div class="row align-items-center">
            <div class="col-md-8">
                <h2 class="text-white fw-bold mb-2">ตารางการแข่งขัน</h2>
                <p class="text-white-50 mb-0">จัดการตารางการแข่งขันและแจ้งข่าวสารการแข่งขันของรุ่นนักกีฬาต่างๆ</p>
            </div>
            <div class="col-md-4 text-md-end mt-3 mt-md-0">
                <button class="btn btn-add-match rounded-pill" data-bs-toggle="modal" data-bs-target="#modalMatch">
                    <i class='bx bx-plus-circle me-1'></i> เพิ่มตารางแข่งขันใหม่
                </button>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-20">
        <div class="card-body p-3 p-md-4">
            <!-- Table View for Desktop -->
            <div class="table-responsive d-none d-md-block">
                <table class="table table-hover align-middle" id="tableMatch">
                    <thead>
                        <tr>
                            <th width="50">#</th>
                            <th>รายการแข่งขัน / สถานที่</th>
                            <th>รุ่น / ทีม</th>
                            <th>วันที่ - เวลา</th>
                            <th class="text-center">สถานะ</th>
                            <th class="text-end">จัดการ</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $i = 1; foreach ($matches as $row) : ?>
                            <?php 
                            $startTs = strtotime($row['match_date']);
                            $endTs = !empty($row['match_end_date']) ? strtotime($row['match_end_date']) : $startTs;

                            $startDateOnly = date('Y-m-d', $startTs);
                            $endDateOnly = date('Y-m-d', $endTs);
                            $startTime = date('H:i', $startTs);
                            $endTime = date('H:i', $endTs);

                            if ($startDateOnly === $endDateOnly) {
                                $dateDisplay = date('d/m/Y', $startTs);
                                $timeDisplay = ($startTime !== '00:00' || $endTime !== '00:00') ? 'เวลา ' . $startTime . ' - ' . $endTime . ' น.' : '';
                            } else {
                                $dateDisplay = date('d/m/Y H:i', $startTs) . ' - ' . date('d/m/Y H:i', $endTs) . ' น.';
                                $timeDisplay = '';
                            }
                            ?>
                            <tr>
                                <td><?= $i++; ?></td>
                                <td>
                                    <div class="fw-bold text-dark"><?= $row['match_title']; ?></div>
                                    <small class="text-muted"><i class='bx bx-map-pin me-1'></i><?= $row['match_location']; ?></small>
                                </td>
                                <td>
                                    <span class="badge bg-label-primary rounded-pill"><?= $row['team_name']; ?></span>
                                    <div class="small text-muted mt-1"><?= $row['team_sport_type']; ?></div>
                                </td>
                                <td>
                                    <div class="fw-semibold text-primary"><i class='bx bx-calendar me-1'></i><?= $dateDisplay; ?></div>
                                    <?php if($timeDisplay): ?>
                                        <small class="text-muted"><i class='bx bx-time me-1'></i><?= $timeDisplay; ?></small>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center">
                                     <?php 
                                     $statusColor = [
                                         'Upcoming' => 'info',
                                         'In Progress' => 'warning',
                                         'Finished' => 'success',
                                         'Canceled' => 'danger'
                                     ];
                                     $statusText = [
                                         'Upcoming' => 'รอดำเนินการ',
                                         'In Progress' => 'กำลังแข่งขัน',
                                         'Finished' => 'แข่งขันเสร็จสิ้น',
                                         'Canceled' => 'ยกเลิก'
                                     ];
                                     $color = $statusColor[$row['match_status']] ?? 'secondary';
                                     $text = $statusText[$row['match_status']] ?? $row['match_status'];
                                     ?>
                                     <span class="status-badge bg-label-<?= $color ?>"><?= $text ?></span>
                                     <?php if(!empty($row['match_result'])): ?>
                                         <div class="small fw-bold text-orange mt-1" title="ผลการแข่งขัน">
                                             <i class='bx bx-trophy me-1'></i><?= $row['match_result'] ?>
                                         </div>
                                     <?php endif; ?>
                                 </td>
                                 <td class="text-end text-nowrap">
                                      <?php 
                                      $canManage = true;
                                      if ($allowedTeamIds !== null && !in_array($row['team_id'], $allowedTeamIds)) {
                                          $canManage = false;
                                      }
                                      ?>
                                      
                                      <?php if ($canManage): ?>
                                          <div class="d-inline-flex gap-1 align-items-center">
                                              <!-- Edit Button -->
                                              <button type="button" class="btn btn-sm btn-icon btn-label-primary edit-match" 
                                                      data-id="<?= $row['match_id'] ?>" 
                                                      data-team="<?= $row['team_id'] ?>"
                                                      data-title="<?= htmlspecialchars($row['match_title'], ENT_QUOTES) ?>"
                                                      data-location="<?= htmlspecialchars($row['match_location'], ENT_QUOTES) ?>"
                                                      data-date="<?= date('Y-m-d H:i', strtotime($row['match_date'])) ?>"
                                                      data-date-end="<?= !empty($row['match_end_date']) ? date('Y-m-d H:i', strtotime($row['match_end_date'])) : date('Y-m-d H:i', strtotime($row['match_date'])) ?>"
                                                      data-status="<?= $row['match_status'] ?>"
                                                      data-note="<?= htmlspecialchars($row['match_note'], ENT_QUOTES) ?>"
                                                      title="แก้ไขข้อมูลการแข่งขัน & ผลงาน">
                                                  <i class="bx bx-edit-alt fs-5"></i>
                                              </button>

                                              <!-- Report & Photos Button -->
                                              <button type="button" class="btn btn-sm btn-icon btn-label-warning text-orange btn-report-match" 
                                                      data-bs-toggle="modal" data-bs-target="#modalReport"
                                                      data-id="<?= $row['match_id'] ?>"
                                                      data-title="<?= htmlspecialchars($row['match_title'], ENT_QUOTES) ?>"
                                                      data-team="<?= htmlspecialchars($row['team_name'], ENT_QUOTES) ?>"
                                                      data-date="<?= htmlspecialchars($dateDisplay, ENT_QUOTES) ?>"
                                                      title="รายงานผล / ภาพถ่ายบรรยากาศ">
                                                  <i class="bx bx-trophy fs-5"></i>
                                              </button>

                                              <!-- Delete Button -->
                                              <button type="button" class="btn btn-sm btn-icon btn-label-danger delete-match" 
                                                      data-id="<?= $row['match_id'] ?>" 
                                                      title="ลบรายการแข่งขัน">
                                                  <i class="bx bx-trash fs-5"></i>
                                              </button>
                                          </div>
                                      <?php else: ?>
                                          <span class="badge bg-label-secondary"><i class="bx bx-lock-alt me-1"></i>ไม่มีสิทธิ์</span>
                                      <?php endif; ?>
                                  </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <!-- Cards View for Mobile -->
            <div class="d-md-none">
                <?php if(empty($matches)): ?>
                    <div class="text-center py-5">
                        <i class='bx bx-calendar-x fs-huge text-light opacity-50 mb-3'></i>
                        <p class="text-muted mb-0">ยังไม่มีตารางการแข่งขัน</p>
                    </div>
                <?php else: ?>
                    <?php foreach ($matches as $row) : ?>
                        <?php 
                        $statusColor = [
                            'Upcoming' => 'info',
                            'In Progress' => 'warning',
                            'Finished' => 'success',
                            'Canceled' => 'danger'
                        ];
                        $statusText = [
                            'Upcoming' => 'รอดำเนินการ',
                            'In Progress' => 'กำลังแข่งขัน',
                            'Finished' => 'แข่งขันเสร็จสิ้น',
                            'Canceled' => 'ยกเลิก'
                        ];
                        $color = $statusColor[$row['match_status']] ?? 'secondary';
                        $text = $statusText[$row['match_status']] ?? $row['match_status'];

                        $canManage = true;
                        if ($allowedTeamIds !== null && !in_array($row['team_id'], $allowedTeamIds)) {
                            $canManage = false;
                        }

                        $startTs = strtotime($row['match_date']);
                        $endTs = !empty($row['match_end_date']) ? strtotime($row['match_end_date']) : $startTs;

                        $startDateOnly = date('d/m/Y', $startTs);
                        $endDateOnly = date('d/m/Y', $endTs);
                        $startTime = date('H:i', $startTs);
                        $endTime = date('H:i', $endTs);

                        if ($startDateOnly === $endDateOnly) {
                            $dateTimeLabel = $startDateOnly . ($startTime !== '00:00' || $endTime !== '00:00' ? ' • เวลา ' . $startTime . ' - ' . $endTime . ' น.' : '');
                        } else {
                            $dateTimeLabel = date('d/m/Y H:i', $startTs) . ' - ' . date('d/m/Y H:i', $endTs) . ' น.';
                        }
                        ?>
                        <div class="mobile-match-card">
                            <div class="card-top-bar">
                                <span class="badge bg-label-primary rounded-pill fw-semibold">
                                    <i class='bx bx-group me-1'></i><?= $row['team_name']; ?> (<?= $row['team_sport_type']; ?>)
                                </span>
                                <span class="status-badge bg-label-<?= $color ?>"><?= $text ?></span>
                            </div>
                            
                            <div class="match-title-text">
                                <i class='bx bx-trophy text-warning me-1'></i><?= $row['match_title']; ?>
                            </div>

                             <?php if(!empty($row['match_result'])): ?>
                                 <div class="badge badge-orange-light p-2 w-100 text-start my-1 fs-6 fw-bold">
                                     🏆 ผลการแข่งขัน: <?= $row['match_result'] ?>
                                 </div>
                             <?php endif; ?>

                            <div class="row g-2 my-2">
                                <div class="col-12">
                                    <div class="info-pill text-primary fw-semibold">
                                        <i class='bx bx-calendar fs-5 me-1'></i>
                                        <span><?= $dateTimeLabel; ?></span>
                                    </div>
                                </div>
                                <?php if(!empty($row['match_location'])): ?>
                                <div class="col-12">
                                    <div class="info-pill text-muted">
                                        <i class='bx bx-map-pin fs-5 text-danger me-1'></i>
                                        <span class="text-truncate"><?= $row['match_location']; ?></span>
                                    </div>
                                </div>
                                <?php endif; ?>
                            </div>

                            <?php if(!empty($row['match_note'])): ?>
                                <div class="small text-muted bg-light p-2 rounded mt-2">
                                    <i class='bx bx-info-circle me-1'></i><?= $row['match_note']; ?>
                                </div>
                            <?php endif; ?>

                            <?php if ($canManage): ?>
                                <div class="action-bar">
                                     <?php if($row['match_status'] === 'Finished'): ?>
                                         <button type="button" class="btn btn-sm btn-orange btn-report-match rounded-pill px-3 me-auto"
                                                 data-bs-toggle="modal" data-bs-target="#modalReport"
                                                 data-id="<?= $row['match_id'] ?>"
                                                 data-title="<?= htmlspecialchars($row['match_title'], ENT_QUOTES) ?>"
                                                 data-team="<?= htmlspecialchars($row['team_name'], ENT_QUOTES) ?>"
                                                 data-date="<?= htmlspecialchars($dateTimeLabel, ENT_QUOTES) ?>">
                                             <i class="bx bx-file me-1"></i> รายงานผล/รูปภาพ
                                         </button>
                                     <?php endif; ?>
                                    <button type="button" class="btn btn-sm btn-label-primary edit-match rounded-pill px-3"
                                            data-id="<?= $row['match_id'] ?>" 
                                            data-team="<?= $row['team_id'] ?>"
                                            data-title="<?= $row['match_title'] ?>"
                                            data-location="<?= $row['match_location'] ?>"
                                            data-date="<?= date('Y-m-d H:i', strtotime($row['match_date'])) ?>"
                                            data-date-end="<?= !empty($row['match_end_date']) ? date('Y-m-d H:i', strtotime($row['match_end_date'])) : date('Y-m-d H:i', strtotime($row['match_date'])) ?>"
                                            data-status="<?= $row['match_status'] ?>"
                                            data-note="<?= $row['match_note'] ?>">
                                        <i class="bx bx-edit-alt me-1"></i> แก้ไข
                                    </button>
                                    <button type="button" class="btn btn-sm btn-label-danger delete-match rounded-circle"
                                            data-id="<?= $row['match_id'] ?>" title="ลบ">
                                        <i class="bx bx-trash"></i>
                                    </button>
                                </div>
                            <?php else: ?>
                                <div class="action-bar">
                                    <span class="small text-muted"><i class="bx bx-lock-alt me-1"></i>ไม่มีสิทธิ์จัดการรายการนี้</span>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Modal Match -->
<div class="modal fade" id="modalMatch" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg rounded-20">
            <div class="modal-header bg-primary py-3">
                <h5 class="modal-title text-white fw-bold"><i class='bx bx-trophy me-2'></i>ข้อมูลการแข่งขัน</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="formMatch" enctype="multipart/form-data">
                <div class="modal-body p-4">
                    <input type="hidden" name="match_id" id="match_id">
                    
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label fw-bold">รุ่น / ทีม <span class="text-danger">*</span></label>
                            <select name="team_id" id="team_id" class="form-select select2-modal" required>
                                <option value="">-- เลือกทีม --</option>
                                <?php 
                                $groupedTeams = [];
                                foreach ($teams as $t) {
                                    $coachKey = !empty($t['coach_names']) ? 'ผู้ฝึกสอน: ' . $t['coach_names'] : 'ยังไม่ระบุผู้ฝึกสอน';
                                    $groupedTeams[$coachKey][] = $t;
                                }
                                foreach ($groupedTeams as $coachLabel => $teamList): 
                                ?>
                                    <optgroup label="<?= $coachLabel ?>">
                                        <?php foreach ($teamList as $t): ?>
                                            <option value="<?= $t['team_id'] ?>"><?= $t['team_name'] ?> (<?= $t['team_sport_type'] ?>)</option>
                                        <?php endforeach; ?>
                                    </optgroup>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-bold">รายการแข่งขัน <span class="text-danger">*</span></label>
                            <input type="text" name="match_title" id="match_title" class="form-control" placeholder="เช่น ฟุตบอลมวลชนอำเภอ รอบรองชนะเลิศ" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-bold">สถานที่แข่งขัน</label>
                            <input type="text" name="match_location" id="match_location" class="form-control" placeholder="เช่น สนามกีฬาจังหวัดนครสวรรค์">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">วัน-เวลาเริ่มแข่งขัน <span class="text-danger">*</span></label>
                            <input type="text" name="match_date" id="match_date" class="form-control date-picker" placeholder="เลือกวันและเวลาเริ่ม" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">วัน-เวลาสิ้นสุดแข่งขัน <span class="text-danger">*</span></label>
                            <input type="text" name="match_end_date" id="match_end_date" class="form-control date-picker" placeholder="เลือกวันและเวลาจบ" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-bold d-flex justify-content-between align-items-center">
                                <span>สถานะการแข่งขัน</span>
                                <small class="text-primary fw-semibold"><i class='bx bx-check-shield me-1'></i>คำนวณตามช่วงวัน-เวลาให้อัตโนมัติ</small>
                            </label>
                            <select name="match_status" id="match_status" class="form-select">
                                <option value="Upcoming">รอดำเนินการ / รอแข่งขัน (Upcoming)</option>
                                <option value="In Progress">กำลังแข่งขัน (In Progress)</option>
                                <option value="Finished">แข่งขันเสร็จสิ้น (Finished)</option>
                                <option value="Canceled">ยกเลิก (Canceled)</option>
                            </select>
                            <div class="form-text mt-1 text-muted" id="autoStatusHelp">
                                <i class='bx bx-info-circle me-1'></i> ระบบจะเลือกสถานะให้อัตโนมัติตามวันและเวลาจริง (ความแม่นยำระดับนาที)
                            </div>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-bold">หมายเหตุ</label>
                            <textarea name="match_note" id="match_note" class="form-control" rows="2" placeholder="รายละเอียดเพิ่มเติม..."></textarea>
                        </div>

                        <!-- Match Report & Photos Section inside Edit Modal -->
                        <div class="col-12 mt-4 pt-3 border-top" id="editReportContainer">
                            <h6 class="fw-bold text-orange mb-3"><i class='bx bx-trophy me-1'></i>ผลการแข่งขันและภาพบรรยากาศ</h6>
                            <div class="row g-3">
                                <div class="col-12">
                                    <label class="form-label fw-bold">ผลการแข่งขัน / รางวัลที่ได้รับ</label>
                                    <input type="text" name="match_result" id="edit_match_result" class="form-control" placeholder="เช่น ชนะเลิศ 3 - 1 หรือ ได้รับรางวัลเหรียญทอง">
                                </div>
                                <div class="col-12">
                                    <label class="form-label fw-bold">สรุปรายละเอียดภาพรวมการแข่งขัน</label>
                                    <textarea name="match_summary" id="edit_match_summary" class="form-control" rows="3" placeholder="กรอกสรุปรูปเกม รายละเอียด..."></textarea>
                                </div>
                                <div class="col-12">
                                    <label class="form-label fw-bold">เพิ่มภาพบรรยากาศการแข่งขัน (เลือกหลายรูปได้)</label>
                                    <input type="file" name="photos[]" id="edit_photos" class="form-control" accept="image/*" multiple>
                                </div>
                                <div class="col-12 mt-2" id="editPendingPreviewSection" style="display: none;">
                                    <h6 class="fw-bold text-orange mb-2"><i class='bx bx-cloud-upload me-1'></i>รูปภาพใหม่ที่เลือก (รอการบันทึก)</h6>
                                    <div class="row g-2" id="editNewPhotosPreview"></div>
                                </div>
                                <div class="col-12 mt-2">
                                    <h6 class="fw-bold mb-2"><i class='bx bx-images me-1'></i>คลังภาพบรรยากาศการแข่งขัน</h6>
                                    <div class="row g-2" id="editPhotosGallery">
                                        <!-- Dynamic Photo Thumbnails -->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-top-0 pb-4 px-4">
                    <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">ยกเลิก</button>
                    <button type="submit" class="btn btn-primary px-5 py-2 fw-bold shadow">
                        <i class='bx bx-save me-1'></i> บันทึกข้อมูลการแข่งขัน
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Match Report -->
<div class="modal fade" id="modalReport" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg rounded-20">
            <div class="modal-header bg-gradient-orange py-3">
                <h5 class="modal-title text-white fw-bold"><i class='bx bx-file me-2'></i>รายงานผลการแข่งขันและภาพบรรยากาศ</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="formReport" enctype="multipart/form-data">
                <div class="modal-body p-4">
                    <input type="hidden" name="match_id" id="report_match_id">
                    
                    <div class="alert alert-warning border-0 bg-label-warning mb-3 py-2 px-3 rounded-12 text-dark" id="reportMatchHeaderInfo">
                        <!-- Filled by JS -->
                    </div>

                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label fw-bold">ผลการแข่งขัน / รางวัลที่ได้รับ <span class="text-danger">*</span></label>
                            <input type="text" name="match_result" id="report_match_result" class="form-control form-control-lg" placeholder="เช่น ชนะเลิศ 3 - 1 หรือ ได้รับรางวัลเหรียญทอง" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-bold">สรุปรายละเอียดและภาพรวมการแข่งขัน</label>
                            <textarea name="match_summary" id="report_match_summary" class="form-control" rows="4" placeholder="กรอกสรุปรูปเกม ความประทับใจ หรือรายละเอียดเพิ่มเติม..."></textarea>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-bold">เพิ่มภาพบรรยากาศการแข่งขัน (เลือกได้หลายรูป)</label>
                            <input type="file" name="photos[]" id="report_photos" class="form-control" accept="image/*" multiple>
                            <div class="form-text text-muted">รองรับไฟล์รูปภาพ JPG, PNG, WEBP (กดเลือกหลายรูปพร้อมกันได้)</div>
                        </div>

                        <div class="col-12 mt-2" id="pendingPreviewSection" style="display: none;">
                            <h6 class="fw-bold text-orange mb-2"><i class='bx bx-cloud-upload me-1'></i>รูปภาพใหม่ที่เลือก (รอการบันทึก)</h6>
                            <div class="row g-2" id="reportNewPhotosPreview"></div>
                        </div>

                        <div class="col-12 mt-3">
                            <h6 class="fw-bold mb-2"><i class='bx bx-images me-1'></i>คลังภาพบรรยากาศการแข่งขัน</h6>
                            <div class="row g-2" id="reportPhotosGallery">
                                <!-- Loaded dynamically via JS -->
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-top-0 pb-4 px-4">
                    <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">ยกเลิก</button>
                    <button type="submit" class="btn btn-orange px-5 py-2 fw-bold shadow">
                        <i class='bx bx-save me-1'></i> บันทึกรายงานผล
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('script') ?>
<script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/th.js"></script>
<script>
$(document).ready(function() {
    $('#tableMatch').DataTable({
        language: { url: '//cdn.datatables.net/plug-ins/1.13.4/i18n/th.json' },
        order: [[3, 'asc']]
    });

    $('.select2-modal').select2({
        dropdownParent: $('#modalMatch'),
        width: '100%'
    });

    function updateAutoStatusPreview() {
        const startDateVal = $('#match_date').val();
        const endDateVal = $('#match_end_date').val() || startDateVal;
        const currentStatus = $('#match_status').val();

        if (currentStatus === 'Canceled') return;

        if (startDateVal) {
            const now = new Date();
            const start = new Date(startDateVal.replace(' ', 'T'));
            const end = new Date((endDateVal || startDateVal).replace(' ', 'T'));

            if (now < start) {
                $('#match_status').val('Upcoming');
            } else if (now >= start && now <= end) {
                $('#match_status').val('In Progress');
            } else {
                $('#match_status').val('Finished');
            }
        }
    }

    const flatpickrConfig = {
        locale: "th",
        enableTime: true,
        time_24hr: true,
        dateFormat: "Y-m-d H:i",
        altInput: true,
        altFormat: "j F Y H:i น.",
        disableMobile: true,
        onReady: function(selectedDates, dateStr, instance) {
            if (instance && instance.calendarContainer) {
                const yr = instance.currentYear < 2500 ? instance.currentYear + 543 : instance.currentYear;
                const el = instance.calendarContainer.querySelector('input.cur-year') || instance.calendarContainer.querySelector('.cur-year') || instance.currentYearElement;
                if (el) {
                    if (el.tagName === 'INPUT') el.value = yr;
                    else el.textContent = yr;
                }
            }
        },
        onChange: function() {
            updateAutoStatusPreview();
        }
    };

    const fpMatchDateInstances = flatpickr("#match_date", {
        ...flatpickrConfig,
        onChange: function(selectedDates, dateStr) {
            if (fpMatchEndDate && typeof fpMatchEndDate.setDate === 'function' && !$('#match_end_date').val()) {
                fpMatchEndDate.setDate(dateStr);
            }
            updateAutoStatusPreview();
        }
    });
    const fpMatchDate = Array.isArray(fpMatchDateInstances) ? fpMatchDateInstances[0] : fpMatchDateInstances;

    const fpMatchEndDateInstances = flatpickr("#match_end_date", flatpickrConfig);
    const fpMatchEndDate = Array.isArray(fpMatchEndDateInstances) ? fpMatchEndDateInstances[0] : fpMatchEndDateInstances;

    // Save handler for Match Form (with Multi-part FormData support)
    $('#formMatch').on('submit', function(e) {
        e.preventDefault();
        const btn = $(this).find('button[type="submit"]');
        const originalHtml = btn.html();
        btn.prop('disabled', true).html("<i class='bx bx-loader-alt bx-spin me-1'></i> กำลังบันทึกข้อมูล...");

        const formData = new FormData(this);

        $.ajax({
            url: '<?= base_url('Admin/Match/Save') ?>',
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(res) {
                if(res.success) {
                    $('#modalMatch').modal('hide');
                    Swal.fire('สำเร็จ', res.message, 'success').then(() => location.reload());
                } else {
                    Swal.fire('ข้อผิดพลาด', res.message || 'ไม่สามารถบันทึกข้อมูลได้', 'error');
                    btn.prop('disabled', false).html(originalHtml);
                }
            },
            error: function() {
                Swal.fire('ข้อผิดพลาด', 'เกิดข้อผิดพลาดในการเชื่อมต่อเซิร์ฟเวอร์', 'error');
                btn.prop('disabled', false).html(originalHtml);
            }
        });
    });

    // Clear form for new match
    $('.btn-add-match').on('click', function() {
        $('#formMatch')[0].reset();
        $('#match_id').val('');
        $('#team_id').val('').trigger('change');
        if (fpMatchDate && typeof fpMatchDate.clear === 'function') fpMatchDate.clear();
        if (fpMatchEndDate && typeof fpMatchEndDate.clear === 'function') fpMatchEndDate.clear();
        $('#edit_match_result').val('');
        $('#edit_match_summary').val('');
        $('#edit_photos').val('');
        $('#editNewPhotosPreview').html('');
        $('#editPendingPreviewSection').hide();
        $('#editPhotosGallery').html('<div class="col-12 text-muted small p-2"><i class="bx bx-image-alt me-1"></i>ยังไม่มีรูปภาพบรรยากาศการแข่งขัน</div>');
        $('.modal-title').html("<i class='bx bx-plus-circle me-2'></i>เพิ่มตารางการแข่งขัน");
    });

    // Edit handler (delegated)
    $(document).on('click', '.edit-match', function() {
        const d = $(this).data();
        $('.modal-title').html("<i class='bx bx-edit-alt me-2'></i>แก้ไขตารางการแข่งขัน");
        $('#match_id').val(d.id);
        $('#team_id').val(d.team).trigger('change');
        $('#match_title').val(d.title);
        $('#match_location').val(d.location);
        if (d.date && fpMatchDate && typeof fpMatchDate.setDate === 'function') fpMatchDate.setDate(d.date);
        if (d.dateEnd && fpMatchEndDate && typeof fpMatchEndDate.setDate === 'function') fpMatchEndDate.setDate(d.dateEnd);
        $('#match_status').val(d.status);
        $('#match_note').val(d.note);

        // Reset & Load report photos for edit form
        $('#edit_photos').val('');
        $('#editNewPhotosPreview').html('');
        $('#editPendingPreviewSection').hide();
        $('#editPhotosGallery').html('<div class="col-12 text-muted small"><i class="bx bx-loader-alt bx-spin me-1"></i>กำลังโหลดคลังภาพ...</div>');

        $.ajax({
            url: '<?= base_url('Admin/Match/GetReport') ?>/' + d.id,
            method: 'GET',
            success: function(res) {
                if(res.success && res.report) {
                    $('#edit_match_result').val(res.report.match_result);
                    $('#edit_match_summary').val(res.report.match_summary);
                    renderEditReportGallery(d.id, res.report.photos);
                } else {
                    $('#edit_match_result').val('');
                    $('#edit_match_summary').val('');
                    renderEditReportGallery(d.id, []);
                }
            }
        });

        $('#modalMatch').modal('show');
    });

    // Instant Pre-save Image Preview for Edit Form Photos
    $('#edit_photos').on('change', function() {
        const files = this.files;
        const container = $('#editNewPhotosPreview');
        const section = $('#editPendingPreviewSection');

        if (!files || files.length === 0) {
            container.html('');
            section.hide();
            return;
        }

        let html = '';
        Array.from(files).forEach(function(file) {
            const objUrl = URL.createObjectURL(file);
            html += `
                <div class="col-6 col-sm-4 col-md-3">
                    <div class="position-relative border border-warning rounded-12 overflow-hidden shadow-sm bg-light">
                        <img src="${objUrl}" class="w-100 object-fit-cover" style="height: 110px;" alt="รูปพรีวิว">
                        <span class="badge bg-warning text-white position-absolute top-0 start-0 m-1 small shadow-xs">รอการบันทึก</span>
                    </div>
                </div>
            `;
        });
        container.html(html);
        section.show();
    });

    function renderEditReportGallery(matchId, photos) {
        if (!photos || photos.length === 0) {
            $('#editPhotosGallery').html('<div class="col-12 text-muted small p-2"><i class="bx bx-image-alt me-1"></i>ยังไม่มีรูปภาพบรรยากาศการแข่งขัน</div>');
            return;
        }

        let html = '';
        photos.forEach(function(photo) {
            const photoUrl = '<?= base_url('uploads/matches/') ?>/' + photo;
            html += `
                <div class="col-6 col-sm-4 col-md-3">
                    <div class="position-relative border rounded-12 overflow-hidden shadow-sm">
                        <img src="${photoUrl}" class="w-100 object-fit-cover" style="height: 110px;" alt="ภาพการแข่งขัน">
                        <button type="button" class="btn btn-sm btn-danger position-absolute top-0 end-0 m-1 rounded-circle p-1 delete-report-photo" 
                                data-match="${matchId}" data-photo="${photo}" title="ลบรูปภาพ">
                            <i class="bx bx-trash fs-6" style="pointer-events: none;"></i>
                        </button>
                    </div>
                </div>
            `;
        });
        $('#editPhotosGallery').html(html);
    }

    // Delete handler (delegated)
    $(document).on('click', '.delete-match', function() {
        const id = $(this).data('id');
        Swal.fire({
            title: 'ยืนยันการลบ?',
            text: "ข้อมูลการแข่งขันจะหายไปและไม่สามารถกู้คืนได้",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            confirmButtonText: 'ลบข้อมูล'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '<?= base_url('Admin/Match/Delete') ?>/' + id,
                    method: 'GET',
                    success: function(res) {
                        if(res.success) {
                            Swal.fire('ลบแล้ว!', res.message, 'success').then(() => location.reload());
                        }
                    }
                });
            }
        });
    });

    // Instant Pre-save Image Preview for Report Photos Modal
    $('#report_photos').on('change', function() {
        const files = this.files;
        const container = $('#reportNewPhotosPreview');
        const section = $('#pendingPreviewSection');

        if (!files || files.length === 0) {
            container.html('');
            section.hide();
            return;
        }

        let html = '';
        Array.from(files).forEach(function(file) {
            const objUrl = URL.createObjectURL(file);
            html += `
                <div class="col-6 col-sm-4 col-md-3">
                    <div class="position-relative border border-warning rounded-12 overflow-hidden shadow-sm bg-light">
                        <img src="${objUrl}" class="w-100 object-fit-cover" style="height: 110px;" alt="รูปพรีวิว">
                        <span class="badge bg-warning text-white position-absolute top-0 start-0 m-1 small shadow-xs">รอการบันทึก</span>
                    </div>
                </div>
            `;
        });
        container.html(html);
        section.show();
    });

    // Report Match Handler (delegated)
    $(document).on('click', '.btn-report-match', function() {
        const d = $(this).data();
        $('#report_match_id').val(d.id);
        $('#formReport')[0].reset();
        $('#reportNewPhotosPreview').html('');
        $('#pendingPreviewSection').hide();
        $('#reportPhotosGallery').html('<div class="col-12 text-muted small"><i class="bx bx-loader-alt bx-spin me-1"></i>กำลังโหลดข้อมูล...</div>');
        
        $('#reportMatchHeaderInfo').html(`
            <div class="fw-bold text-dark fs-6"><i class='bx bx-trophy text-warning me-1'></i>${d.title}</div>
            <div class="small text-muted"><i class='bx bx-group me-1'></i>ทีม: ${d.team} | <i class='bx bx-calendar me-1'></i>${d.date}</div>
        `);

        // Load existing report
        $.ajax({
            url: '<?= base_url('Admin/Match/GetReport') ?>/' + d.id,
            method: 'GET',
            success: function(res) {
                if(res.success && res.report) {
                    $('#report_match_result').val(res.report.match_result);
                    $('#report_match_summary').val(res.report.match_summary);
                    renderReportGallery(d.id, res.report.photos);
                } else {
                    $('#report_match_result').val('');
                    $('#report_match_summary').val('');
                    renderReportGallery(d.id, []);
                }
            }
        });

        $('#modalReport').modal('show');
    });

    function renderReportGallery(matchId, photos) {
        if (!photos || photos.length === 0) {
            $('#reportPhotosGallery').html('<div class="col-12 text-muted small p-2"><i class="bx bx-image-alt me-1"></i>ยังไม่มีรูปภาพบรรยากาศการแข่งขัน</div>');
            return;
        }

        let html = '';
        photos.forEach(function(photo) {
            const photoUrl = '<?= base_url('uploads/matches/') ?>/' + photo;
            html += `
                <div class="col-6 col-sm-4 col-md-3">
                    <div class="position-relative border rounded-12 overflow-hidden shadow-sm">
                        <img src="${photoUrl}" class="w-100 object-fit-cover" style="height: 110px;" alt="ภาพการแข่งขัน">
                        <button type="button" class="btn btn-sm btn-danger position-absolute top-0 end-0 m-1 rounded-circle p-1 delete-report-photo" 
                                data-match="${matchId}" data-photo="${photo}" title="ลบรูปภาพ">
                            <i class="bx bx-trash fs-6" style="pointer-events: none;"></i>
                        </button>
                    </div>
                </div>
            `;
        });
        $('#reportPhotosGallery').html(html);
    }

    // Delete Report Photo Handler (delegated)
    $(document).on('click', '.delete-report-photo', function(e) {
        e.preventDefault();
        e.stopPropagation();

        const btn = $(this).closest('.delete-report-photo');
        const matchId = btn.data('match') || btn.attr('data-match');
        const photoName = btn.data('photo') || btn.attr('data-photo');

        if (!matchId || !photoName) {
            console.error('Missing matchId or photoName', matchId, photoName);
            return;
        }

        Swal.fire({
            title: 'ยืนยันการลบรูปภาพ?',
            text: "รูปภาพนี้จะถูกลบออกจากรายงานผลการแข่งขัน",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#8592a3',
            confirmButtonText: 'ยืนยัน ลบเลย',
            cancelButtonText: 'ยกเลิก'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '<?= base_url('Admin/Match/DeleteReportPhoto') ?>',
                    method: 'POST',
                    data: { match_id: matchId, photo_name: photoName },
                    success: function(res) {
                        if(res.success) {
                            Swal.fire('ลบแล้ว!', res.message, 'success');
                            // Refresh both galleries
                            $.ajax({
                                url: '<?= base_url('Admin/Match/GetReport') ?>/' + matchId,
                                method: 'GET',
                                success: function(r) {
                                    const photosList = (r.success && r.report) ? r.report.photos : [];
                                    if ($('#reportPhotosGallery').length) {
                                        renderReportGallery(matchId, photosList);
                                    }
                                    if ($('#editPhotosGallery').length) {
                                        renderEditReportGallery(matchId, photosList);
                                    }
                                }
                            });
                        } else {
                            Swal.fire('ผิดพลาด', res.message || 'ไม่สามารถลบรูปภาพได้', 'error');
                        }
                    },
                    error: function() {
                        Swal.fire('ผิดพลาด', 'เกิดข้อผิดพลาดในการเชื่อมต่อเซิร์ฟเวอร์', 'error');
                    }
                });
            }
        });
    });

    // Save Form Report Handler with Button Loading State
    $('#formReport').on('submit', function(e) {
        e.preventDefault();
        const btn = $(this).find('button[type="submit"]');
        const originalHtml = btn.html();
        btn.prop('disabled', true).html("<i class='bx bx-loader-alt bx-spin me-1'></i> กำลังบันทึกรายงานผล...");

        const formData = new FormData(this);

        $.ajax({
            url: '<?= base_url('Admin/Match/SaveReport') ?>',
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(res) {
                if(res.success) {
                    $('#modalReport').modal('hide');
                    Swal.fire('สำเร็จ', res.message, 'success').then(() => location.reload());
                } else {
                    Swal.fire('ข้อผิดพลาด', res.message || 'ไม่สามารถบันทึกรายงานได้', 'error');
                    btn.prop('disabled', false).html(originalHtml);
                }
            },
            error: function() {
                Swal.fire('ข้อผิดพลาด', 'เกิดข้อผิดพลาดในการเชื่อมต่อเซิร์ฟเวอร์', 'error');
                btn.prop('disabled', false).html(originalHtml);
            }
        });
    });
});
</script>
<?= $this->endSection() ?>
