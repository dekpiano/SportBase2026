<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'รายงานการเช็กชื่อและการลา' ?></title>
    <!-- Google Fonts: Sarabun & Prompt -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Prompt:wght@400;500;600;700&family=Sarabun:wght@400;500;600;700&display=swap" rel="stylesheet">
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Boxicons -->
    <link href="https://cdn.jsdelivr.net/npm/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">

    <style>
        :root {
            --font-main: 'TH SarabunPSK', 'TH Sarabun PSK', 'TH Sarabun New', 'Sarabun', sans-serif;
        }

        body {
            font-family: var(--font-main);
            font-size: 16pt;
            background-color: #f4f6f9;
            color: #0f172a;
            margin: 0;
            padding: 0;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }

        /* A4 Landscape Setup */
        .page-container {
            width: 297mm;
            min-height: 210mm;
            padding: 12mm 15mm;
            margin: 20px auto;
            background: #ffffff;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            position: relative;
            box-sizing: border-box;
        }

        /* Header Document Style */
        .doc-header {
            border-bottom: 2px solid #0f172a;
            padding-bottom: 8px;
            margin-bottom: 12px;
        }

        .doc-title {
            font-size: 20pt;
            font-weight: 700;
            color: #0f172a;
            margin: 0;
            line-height: 1.2;
        }

        .doc-subtitle {
            font-size: 16pt;
            font-weight: 600;
            color: #475569;
            margin-top: 2px;
        }

        .meta-box {
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 8px 12px;
            font-size: 14pt;
            margin-bottom: 14px;
        }

        /* Summary Badges Table */
        .summary-table {
            width: 100%;
            margin-bottom: 14px;
            border-collapse: collapse;
        }

        .summary-table td {
            padding: 6px 10px;
            border: 1px solid #cbd5e1;
            text-align: center;
            font-size: 14pt;
            font-weight: 600;
        }

        .summary-table .summary-val {
            font-size: 16pt;
            font-weight: 700;
            display: block;
        }

        /* Main Data Table */
        .report-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 14pt;
            margin-bottom: 20px;
        }

        .report-table th, 
        .report-table td {
            border: 1px solid #94a3b8;
            padding: 4px 6px;
            vertical-align: middle;
        }

        .report-table th {
            background-color: #f1f5f9 !important;
            color: #0f172a;
            font-weight: 700;
            text-align: center;
            font-size: 15pt;
            white-space: nowrap;
        }

        .report-table tr:nth-child(even) td {
            background-color: #f8fafc !important;
        }

        .note-cell {
            font-size: 13pt;
            line-height: 1.25;
            word-break: break-word;
        }

        .name-cell {
            font-size: 14pt;
            line-height: 1.25;
            word-break: break-word;
        }

        /* Signature Section */
        .signature-section {
            margin-top: 30px;
            font-size: 15pt;
            page-break-inside: avoid;
        }

        .signature-box {
            text-align: center;
            font-size: 15pt;
        }

        .signature-line {
            margin-top: 38px;
            border-bottom: 1px dashed #475569;
            width: 220px;
            display: inline-block;
        }

        /* Status Pills for Print */
        .status-pill {
            display: inline-block;
            padding: 2px 10px;
            border-radius: 4px;
            font-size: 0.8rem;
            font-weight: 700;
            text-align: center;
            min-width: 48px;
        }
        .st-present { background-color: #dcfce7 !important; color: #15803d !important; }
        .st-sick { background-color: #fef3c7 !important; color: #b45309 !important; }
        .st-personal { background-color: #e0f2fe !important; color: #0369a1 !important; }
        .st-home { background-color: #ffe4e6 !important; color: #be123c !important; }
        .st-competition { background-color: #f3e8ff !important; color: #6b21a8 !important; }
        .st-absent { background-color: #f1f5f9 !important; color: #475569 !important; }

        /* Top Action Bar (Non-Printable) */
        .action-bar {
            position: fixed;
            top: 15px;
            right: 20px;
            z-index: 9999;
            background: rgba(15, 23, 42, 0.85);
            backdrop-filter: blur(8px);
            padding: 10px 18px;
            border-radius: 30px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
            display: flex;
            gap: 10px;
            align-items: center;
        }

        /* Print Media Styles */
        @media print {
            @page {
                size: A4 landscape;
                margin: 8mm 10mm 10mm 10mm;
            }

            body {
                background: #ffffff !important;
            }

            .page-container {
                width: 100% !important;
                min-height: auto !important;
                margin: 0 !important;
                padding: 0 !important;
                box-shadow: none !important;
                border-radius: 0 !important;
            }

            .action-bar {
                display: none !important;
            }

            .report-table th {
                background-color: #e2e8f0 !important;
            }
        }
    </style>
</head>
<body>

    <?php
    // Short Status Label Mapping
    $shortStatuses = [
        'present' => 'มา',
        'sick' => 'ป่วย',
        'personal' => 'ลากิจ',
        'home' => 'กลับบ้าน',
        'competition' => 'แข่ง',
        'absent' => 'ไม่มา'
    ];
    ?>

    <!-- Action Bar for Screen Viewing -->
    <div class="action-bar">
        <span class="text-white small fw-bold me-2"><i class="bx bx-printer me-1"></i>พรีวิวเอกสารพิมพ์ (A4 แนวนอน)</span>
        <button onclick="window.print()" class="btn btn-success btn-sm rounded-pill fw-bold px-3">
            <i class="bx bx-printer me-1"></i> พิมพ์เอกสาร
        </button>
        <button onclick="window.close()" class="btn btn-outline-light btn-sm rounded-pill px-3">
            <i class="bx bx-x me-1"></i> ปิดหน้านี้
        </button>
    </div>

    <!-- Main A4 Container -->
    <div class="page-container">
        
        <!-- Header Section -->
        <div class="doc-header d-flex align-items-center justify-content-between">
            <div>
                <h1 class="doc-title">โรงเรียนสวนกุหลาบวิทยาลัย (จิรประวัต) นครสวรรค์</h1>
                <div class="doc-subtitle">รายงานประวัติการเช็กชื่อและการลานักเรียนกีฬา (SportBase 2026)</div>
            </div>
            <div class="text-end">
                <span class="badge bg-dark px-3 py-2 fs-7">เอกสารรายงานผล (แนวนอน)</span>
            </div>
        </div>

        <!-- Metadata Section -->
        <div class="meta-box d-flex justify-content-between align-items-center flex-wrap gap-2">
            <div>
                <div><strong>รุ่น / ทีม:</strong> <?= $teamInfo ? $teamInfo['team_name'] : 'ทั้งหมดทุกรุ่น/ทีม' ?></div>
                <div><strong>ช่วงเวลาข้อมูล:</strong> ประจำวันที่ <?= date('d/m/', strtotime($startDate)) . (date('Y', strtotime($startDate)) + 543) ?> ถึงวันที่ <?= date('d/m/', strtotime($endDate)) . (date('Y', strtotime($endDate)) + 543) ?></div>
            </div>
            <div class="text-end">
                <div><strong>วันที่พิมพ์เอกสาร:</strong> <?= date('d/m/') . (date('Y') + 543) ?> (เวลา <?= date('H:i') ?> น.)</div>
                <div><strong>ผู้พิมพ์:</strong> <?= session()->get('username') ?? 'เจ้าหน้าที่ผู้ดูแลระบบ' ?></div>
            </div>
        </div>

        <!-- Summary Statistics Table -->
        <table class="summary-table">
            <tr>
                <td style="background-color: #f8fafc;">
                    ยอดรวมทั้งหมด
                    <span class="summary-val text-dark"><?= number_format($summary['total']) ?> รายการ</span>
                </td>
                <td style="background-color: #ecfdf5; color: #166534;">
                    มา
                    <span class="summary-val"><?= number_format($summary['present']) ?></span>
                </td>
                <td style="background-color: #fffbe6; color: #92400e;">
                    ป่วย
                    <span class="summary-val"><?= number_format($summary['sick']) ?></span>
                </td>
                <td style="background-color: #e0f2fe; color: #075985;">
                    ลากิจ
                    <span class="summary-val"><?= number_format($summary['personal']) ?></span>
                </td>
                <td style="background-color: #ffe4e6; color: #9f1239;">
                    กลับบ้าน
                    <span class="summary-val"><?= number_format($summary['home']) ?></span>
                </td>
                <td style="background-color: #f3e8ff; color: #6b21a8;">
                    แข่ง
                    <span class="summary-val"><?= number_format($summary['competition']) ?></span>
                </td>
                <td style="background-color: #f1f5f9; color: #475569;">
                    ไม่มา
                    <span class="summary-val"><?= number_format($summary['absent']) ?></span>
                </td>
            </tr>
        </table>

        <!-- Main Data Table -->
        <table class="report-table">
            <thead>
                <tr>
                    <th width="3%">#</th>
                    <th width="9%">วันที่ลงเวลา</th>
                    <th width="13%">ช่วงวันที่ลา/แข่ง</th>
                    <th width="13%">รุ่น/ทีม</th>
                    <th width="8%">รหัส</th>
                    <th width="18%">ชื่อ - นามสกุล</th>
                    <th width="5%">ชั้น</th>
                    <th width="9%">ช่วงเวลา</th>
                    <th width="6%">สถานะ</th>
                    <th width="16%">หมายเหตุ</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($history)): ?>
                    <tr>
                        <td colspan="10" class="text-center py-4 text-muted">ไม่พบข้อมูลตามเงื่อนไขที่เลือก</td>
                    </tr>
                <?php else: ?>
                    <?php $i = 1; foreach ($history as $row): 
                        $stLabelShort = $shortStatuses[$row['att_status']] ?? $row['att_status'];
                        $peInfo = $periods[$row['att_period']] ?? ['label' => $row['att_period']];
                        
                        $sDate = !empty($row['att_start_date']) ? $row['att_start_date'] : $row['att_date'];
                        $eDate = !empty($row['att_end_date']) ? $row['att_end_date'] : $sDate;
                        
                        $sText = date('d/m/', strtotime($sDate)) . (date('Y', strtotime($sDate)) + 543);
                        $eText = date('d/m/', strtotime($eDate)) . (date('Y', strtotime($eDate)) + 543);
                        $rangeStr = ($sDate === $eDate) ? '-' : ($sText . ' - ' . $eText);
                        
                        $classPill = 'st-' . $row['att_status'];
                    ?>
                        <tr>
                            <td class="text-center"><?= $i++ ?></td>
                            <td class="text-center"><?= date('d/m/', strtotime($row['att_date'])) . (date('Y', strtotime($row['att_date'])) + 543) ?></td>
                            <td class="text-center"><?= $rangeStr ?></td>
                            <td><?= $row['team_name'] ?></td>
                            <td class="text-center"><?= $row['StudentCode'] ?></td>
                            <td class="name-cell" title="<?= esc($row['StudentPrefix'] . $row['StudentFirstName'] . ' ' . $row['StudentLastName']); ?>"><?= $row['StudentPrefix'] . $row['StudentFirstName'] . ' ' . $row['StudentLastName'] ?></td>
                            <td class="text-center"><?= $row['StudentClass'] ?></td>
                            <td class="text-center"><?= $peInfo['label'] ?></td>
                            <td class="text-center">
                                <span class="status-pill <?= $classPill ?>"><?= $stLabelShort ?></span>
                            </td>
                            <td class="note-cell" title="<?= esc($row['att_note']); ?>"><?= $row['att_note'] ?: '-' ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>

        <!-- Signatures Section -->
        <div class="signature-section row text-center">
            <div class="col-6">
                <div class="signature-box">
                    <p class="mb-0">ลงชื่อ...........................................................................ผู้รายงาน</p>
                    <div class="signature-line"></div>
                    <p class="mt-2 mb-0">(...........................................................................)</p>
                    <p class="text-muted small">ตำแหน่ง: โค้ช / ผู้ดูแลประจำทีม</p>
                    <p class="text-muted small">วันที่ ........ / ........ / ................</p>
                </div>
            </div>
            <div class="col-6">
                <div class="signature-box">
                    <p class="mb-0">ลงชื่อ...........................................................................ผู้ตรวจรับรอง</p>
                    <div class="signature-line"></div>
                    <p class="mt-2 mb-0">(...........................................................................)</p>
                    <p class="text-muted small">ตำแหน่ง: หัวหน้าผู้ฝึกสอน / หัวหน้าโครงการ</p>
                    <p class="text-muted small">วันที่ ........ / ........ / ................</p>
                </div>
            </div>
        </div>

    </div>

    <script>
        // Auto trigger print when page opens
        window.addEventListener('DOMContentLoaded', () => {
            setTimeout(() => {
                window.print();
            }, 500);
        });
    </script>
</body>
</html>
