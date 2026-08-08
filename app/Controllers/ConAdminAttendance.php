<?php

namespace App\Controllers;

use App\Models\AttendanceModel;
use App\Models\TeamModel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\PageSetup;

class ConAdminAttendance extends BaseController
{
    protected $attendanceModel;
    protected $teamModel;

    public function __construct()
    {
        $session = session();
        if (!$session->get('username')) {
            header("Location:" . base_url());
            exit();
        }

        // จำกัดสิทธิ์เฉพาะผู้ดูแลระบบ และ โค้ช เท่านั้น
        $status = $session->get('status');
        if (!in_array($status, ["admin", "super_admin", "coach"])) {
            echo "คุณไม่มีสิทธิ์เข้าถึงหน้านี้";
            exit();
        }

        $this->attendanceModel = new AttendanceModel();
        $this->teamModel = new TeamModel();
    }

    /**
     * หน้าหลักเช็คชื่อ - เลือกทีม/รุ่น
     */
    public function index()
    {
        $data['title'] = 'ระบบเช็กชื่อ & ติดตามสถานะนักเรียน';
        $data['uri'] = service('uri')->setSilent(true);

        // โค้ชหรือผู้ดูแลสามารถเข้าบันทึกช่วงเวลา แถวเช้า/ห้องนอน ได้ทุกคนโดยไม่ต้องแยกรุ่น จึงดึงทีมทั้งหมดเสมอ
        $data['teams'] = $this->teamModel->getTeamsWithCounts(null);
        
        $data['todaySummary'] = $this->attendanceModel->getTodaySummary();
        $data['statuses'] = AttendanceModel::$statuses;

        return view('Admin/AdminAttendance/AdminAttendanceMain', $data);
    }

    /**
     * หน้าเช็คชื่อตามทีม
     */
    public function team($teamId)
    {
        // ตรวจสอบสิทธิ์ถ้าเป็น Coach (ถ้าเช็กซ้อมกีฬา ให้เช็กสิทธิ์เฉพาะรุ่นตนเอง แต่ถ้าเป็นแถวเช้า/ห้องนอน ไม่ต้องแยกรุ่นให้เข้าได้หมด)
        if (session()->get('status') == 'coach') {
            $period = $this->request->getGet('period') ?? 'morning';
            if ($period === 'training') {
                if (!$this->teamModel->isCoachOfTeam($teamId, session()->get('id'))) {
                    return redirect()->to('Admin/Attendance')->with('error', 'คุณไม่มีสิทธิ์เข้าถึงทีมนี้');
                }
            }
        }

        $team = $this->teamModel->find($teamId);
        if (!$team) {
            return redirect()->to('Admin/Attendance')->with('error', 'ไม่พบข้อมูลทีม');
        }

        $date = $this->request->getGet('date') ?? date('Y-m-d');
        $period = $this->request->getGet('period') ?? 'morning';

        // ดึงรายชื่อนักกีฬาในทีม
        $athletes = $this->teamModel->getTeamAthletes($teamId);
        
        // ดึงข้อมูลการลาวันนี้ตามช่วงเวลา
        $attendanceToday = $this->attendanceModel->getAttendanceByTeamAndDate($teamId, $date, $period);
        
        // สร้าง array สำหรับค้นหาง่าย
        $attendanceMap = [];
        foreach ($attendanceToday as $att) {
            $attendanceMap[$att['StudentID']] = $att;
        }

        $periodLabel = AttendanceModel::$periods[$period]['label'] ?? 'เช็กชื่อนักเรียน';
        $data['title'] = $periodLabel . ': ' . $team['team_name'];
        $data['uri'] = service('uri')->setSilent(true);
        $data['team'] = $team;
        $data['athletes'] = $athletes;
        $data['attendanceMap'] = $attendanceMap;
        $data['selectedDate'] = $date;
        $data['selectedPeriod'] = $period;
        $data['periods'] = AttendanceModel::$periods;
        $data['statuses'] = AttendanceModel::$statuses;
        $data['periodStatusMap'] = $this->attendanceModel->getPeriodStatusMap($teamId, $date, count($athletes));

        return view('Admin/AdminAttendance/AdminAttendanceTeam', $data);
    }

    /**
     * บันทึกการเช็กชื่อ/การลา (AJAX)
     */
    public function save()
    {
        $studentId = $this->request->getPost('student_id');
        $teamId = $this->request->getPost('team_id');
        $period = $this->request->getPost('period') ?? 'morning';

        // ตรวจสอบสิทธิ์ก่อนบันทึก (ถ้าเช็กซ้อมกีฬา ให้เช็กสิทธิ์เฉพาะรุ่นตนเอง แต่ถ้าเป็นแถวเช้า/ห้องนอน ไม่ต้องแยกรุ่น)
        if (session()->get('status') == 'coach' && $period === 'training') {
            if (!$this->teamModel->isCoachOfTeam($teamId, session()->get('id'))) {
                return $this->response->setJSON(['success' => false, 'message' => 'คุณไม่มีสิทธิ์จัดการทีมนี้']);
            }
        }

        $date = $this->request->getPost('date');
        $endDate = $this->request->getPost('end_date');
        $status = $this->request->getPost('status');
        $note = $this->request->getPost('note') ?? '';
        $checkedBy = session()->get('id');

        if (empty($status)) {
            // ลบ record (ลบสถานะ)
            $this->attendanceModel->removeAttendance($studentId, $date, $period);
            return $this->response->setJSON([
                'success' => true,
                'message' => 'ยกเลิกการเช็กชื่อแล้ว',
                'status' => ''
            ]);
        }

        $targetEndDate = ($endDate && $endDate != $date) ? $endDate : $date;

        // ลบข้อมูลการลาเก่าที่คาบเกี่ยวกับช่วงวันที่ใหม่นี้ออกก่อน ป้องกันข้อมูลทับซ้อน (ส่ง period ไปด้วยเพื่อไม่ให้ลบช่วงเวลาอื่น)
        $this->attendanceModel->removeAttendanceRange($studentId, $date, $targetEndDate, $period);

        // เตรียมข้อมูลพื้นฐาน
        $baseData = [
            'team_id'        => $teamId,
            'StudentID'      => $studentId,
            'att_period'     => $period,
            'att_status'     => $status,
            'att_note'       => $note,
            'att_time'       => date('H:i:s'),
            'checked_by'     => $checkedBy,
            'created_at'     => date('Y-m-d H:i:s'),
            'att_date'       => $date,
            'att_start_date' => $date,
            'att_end_date'   => $targetEndDate
        ];

        // บันทึกข้อมูลใบลาใหม่
        $this->attendanceModel->insert($baseData);

        // คำนวณความยาวการลาและเตรียมข้อความตอบกลับ
        if ($targetEndDate != $date) {
            $dayCount = (strtotime($targetEndDate) - strtotime($date)) / 86400 + 1;
            $message = 'บันทึกช่วงวันที่ ' . date('d/m/Y', strtotime($date)) . ' ถึง ' . date('d/m/Y', strtotime($targetEndDate)) . ' (' . $dayCount . ' วัน)';
        } else {
            $statusLabel = AttendanceModel::$statuses[$status]['label'] ?? $status;
            $message = 'บันทึกสถานะ: ' . $statusLabel;
        }

        return $this->response->setJSON([
            'success' => true,
            'message' => $message,
            'status' => $status
        ]);
    }

    /**
     * บันทึกสถานะทั้งทีมใน 1 คลิก (Mark All Status AJAX)
     */
    public function markAllStatus()
    {
        $teamId = $this->request->getPost('team_id');
        $date = $this->request->getPost('date') ?? date('Y-m-d');
        $endDate = $this->request->getPost('end_date') ?? $date;
        $period = $this->request->getPost('period') ?? 'morning';
        $status = $this->request->getPost('status') ?? 'present';
        $note = $this->request->getPost('note') ?? '';

        if (empty($teamId)) {
            return $this->response->setJSON(['success' => false, 'message' => 'ระบุข้อมูลทีมไม่ถูกต้อง']);
        }

        // ตรวจสอบสิทธิ์ก่อนบันทึกทั้งทีม (ถ้าเช็กซ้อมกีฬา ให้เช็กสิทธิ์เฉพาะรุ่นตนเอง แต่ถ้าเป็นแถวเช้า/ห้องนอน ไม่ต้องแยกรุ่น)
        if (session()->get('status') == 'coach' && $period === 'training') {
            if (!$this->teamModel->isCoachOfTeam($teamId, session()->get('id'))) {
                return $this->response->setJSON(['success' => false, 'message' => 'คุณไม่มีสิทธิ์จัดการทีมนี้']);
            }
        }

        $athletes = $this->teamModel->getTeamAthletes($teamId);
        $checkedBy = session()->get('id');
        $statusLabel = AttendanceModel::$statuses[$status]['label'] ?? $status;

        foreach ($athletes as $athlete) {
            $this->attendanceModel->removeAttendanceRange($athlete['StudentID'], $date, $endDate, $period);

            $baseData = [
                'team_id'        => $teamId,
                'StudentID'      => $athlete['StudentID'],
                'att_date'       => $date,
                'att_start_date' => $date,
                'att_end_date'   => $endDate,
                'att_period'     => $period,
                'att_status'     => $status,
                'att_note'       => $note ?: ('เลือก "' . $statusLabel . '" ทั้งทีม'),
                'att_time'       => date('H:i:s'),
                'checked_by'     => $checkedBy,
                'created_at'     => date('Y-m-d H:i:s')
            ];
            $this->attendanceModel->insert($baseData);
        }

        return $this->response->setJSON([
            'success' => true,
            'message' => 'บันทึกสถานะ "' . $statusLabel . '" ให้กับทุกคนในทีมเรียบร้อยแล้ว'
        ]);
    }

    /**
     * ส่งออกรายงานการเช็กชื่อและประวัติการลาเป็นไฟล์ Excel จริง (.xlsx)
     */
    public function exportExcel()
    {
        $teamId = $this->request->getGet('team');
        $startDate = $this->request->getGet('start') ?? date('Y-m-01');
        $endDate = $this->request->getGet('end') ?? date('Y-m-d');

        $teamInfo = null;
        if ($teamId) {
            $teamInfo = $this->teamModel->find($teamId);
        }

        $builder = $this->attendanceModel->db->table('tb_attendance att')
            ->select('att.*, std.StudentCode, std.StudentPrefix, std.StudentFirstName, std.StudentLastName, std.StudentClass, t.team_name')
            ->join('skjacth_academic.tb_students std', 'std.StudentID = att.StudentID')
            ->join('tb_teams t', 't.team_id = att.team_id')
            ->where('att.att_date >=', $startDate)
            ->where('att.att_date <=', $endDate)
            ->orderBy('att.att_date', 'DESC')
            ->orderBy('att.created_at', 'DESC');

        if ($teamId) {
            $builder->where('att.team_id', $teamId);
        }

        $history = $builder->get()->getResultArray();
        $periods = AttendanceModel::$periods;

        $shortStatuses = [
            'present' => 'มา',
            'sick' => 'ป่วย',
            'personal' => 'ลากิจ',
            'home' => 'กลับบ้าน',
            'competition' => 'แข่ง',
            'absent' => 'ไม่มา'
        ];

        // คำนวณสรุปสถานะ
        $summary = [
            'present' => 0,
            'sick' => 0,
            'personal' => 0,
            'home' => 0,
            'competition' => 0,
            'absent' => 0,
            'total' => count($history)
        ];
        foreach ($history as $row) {
            if (isset($summary[$row['att_status']])) {
                $summary[$row['att_status']]++;
            }
        }

        $teamNameStr = $teamInfo ? $teamInfo['team_name'] : 'ทั้งหมดทุกรุ่น';

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('ประวัติการเช็กชื่อ');

        // ตั้งค่าหน้ากระดาษพิมพ์ใน Excel เป็น A4 แนวนอน (Landscape & Fit to 1 page wide)
        $sheet->getPageSetup()->setOrientation(PageSetup::ORIENTATION_LANDSCAPE);
        $sheet->getPageSetup()->setPaperSize(PageSetup::PAPERSIZE_A4);
        $sheet->getPageSetup()->setFitToPage(true);
        $sheet->getPageSetup()->setFitToWidth(1);
        $sheet->getPageSetup()->setFitToHeight(0);

        // ตั้งระยะขอบ (Margins: 0.5 นิ้ว)
        $sheet->getPageMargins()->setTop(0.5);
        $sheet->getPageMargins()->setRight(0.5);
        $sheet->getPageMargins()->setLeft(0.5);
        $sheet->getPageMargins()->setBottom(0.5);

        // Font Family default: TH SarabunPSK (ฟอนต์สารบรรณราชการไทย)
        $spreadsheet->getDefaultStyle()->getFont()->setName('TH SarabunPSK')->setSize(14);

        // 1. Document Title
        $sheet->mergeCells('A1:M1');
        $sheet->setCellValue('A1', 'โรงเรียนสวนกุหลาบวิทยาลัย (จิรประวัต) นครสวรรค์');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(20);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        $sheet->mergeCells('A2:M2');
        $sheet->setCellValue('A2', 'รายงานประวัติการเช็กชื่อและการลานักเรียนกีฬา (SportBase 2026)');
        $sheet->getStyle('A2')->getFont()->setBold(true)->setSize(16)->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color('475569'));
        $sheet->getStyle('A2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        $sheet->mergeCells('A3:M3');
        $metaText = 'รุ่น/ทีม: ' . $teamNameStr . ' | ประจำวันที่: ' . date('d/m/', strtotime($startDate)) . (date('Y', strtotime($startDate)) + 543) . ' ถึงวันที่: ' . date('d/m/', strtotime($endDate)) . (date('Y', strtotime($endDate)) + 543) . ' | วันที่ออกรายงาน: ' . date('d/m/') . (date('Y') + 543) . ' ' . date('H:i') . ' น.';
        $sheet->setCellValue('A3', $metaText);
        $sheet->getStyle('A3')->getFont()->setSize(14)->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color('64748B'));
        $sheet->getStyle('A3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // 2. Summary Section
        $sheet->setCellValue('A5', '📊 สรุปภาพรวมสถิติการเช็กชื่อและการลา');
        $sheet->getStyle('A5')->getFont()->setBold(true);

        $sumHeaders = ['ยอดรวมทั้งหมด', 'มา', 'ป่วย', 'ลากิจ', 'กลับบ้าน', 'แข่ง', 'ไม่มา / ขาด'];
        $cols = ['A', 'B', 'C', 'D', 'E', 'F'];
        
        for ($c = 0; $c < 6; $c++) {
            $sheet->setCellValue($cols[$c] . '6', $sumHeaders[$c]);
            $sheet->getStyle($cols[$c] . '6')->getFont()->setBold(true);
            $sheet->getStyle($cols[$c] . '6')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle($cols[$c] . '6')->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN)->getColor()->setRGB('CBD5E1');
            $sheet->getStyle($cols[$c] . '6')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('F1F5F9');
        }
        $sheet->mergeCells('G6:M6');
        $sheet->setCellValue('G6', $sumHeaders[6]);
        $sheet->getStyle('G6')->getFont()->setBold(true);
        $sheet->getStyle('G6')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('G6:M6')->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN)->getColor()->setRGB('CBD5E1');
        $sheet->getStyle('G6')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('F1F5F9');

        $sumVals = [
            $summary['total'] . ' รายการ',
            $summary['present'],
            $summary['sick'],
            $summary['personal'],
            $summary['home'],
            $summary['competition']
        ];
        for ($c = 0; $c < 6; $c++) {
            $sheet->setCellValue($cols[$c] . '7', $sumVals[$c]);
            $sheet->getStyle($cols[$c] . '7')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle($cols[$c] . '7')->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN)->getColor()->setRGB('CBD5E1');
        }
        $sheet->mergeCells('G7:M7');
        $sheet->setCellValue('G7', $summary['absent']);
        $sheet->getStyle('G7')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('G7:M7')->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN)->getColor()->setRGB('CBD5E1');

        // 3. Table Headers (Row 9)
        $tableHeaders = ['#', 'วันที่ลงเวลา', 'ช่วงวันที่ลา/แข่ง', 'รุ่น/ทีม', 'รหัสนักเรียน', 'คำนำหน้า', 'ชื่อ', 'นามสกุล', 'ชั้นเรียน', 'ช่วงเวลา', 'สถานะ', 'หมายเหตุ', 'เวลาบันทึก'];
        $allCols = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M'];

        foreach ($allCols as $idx => $col) {
            $cell = $col . '9';
            $sheet->setCellValue($cell, $tableHeaders[$idx]);
            $sheet->getStyle($cell)->getFont()->setBold(true)->setSize(15)->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color('FFFFFF'));
            $sheet->getStyle($cell)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER)->setVertical(Alignment::VERTICAL_CENTER);
            $sheet->getStyle($cell)->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('0F172A');
            $sheet->getStyle($cell)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN)->getColor()->setRGB('94A3B8');
        }

        // 4. Data Rows (Row 10+)
        $rowNum = 10;
        $i = 1;
        foreach ($history as $row) {
            $st = $shortStatuses[$row['att_status']] ?? $row['att_status'];
            $pe = $periods[$row['att_period']]['label'] ?? $row['att_period'];

            $dateStr = date('d/m/', strtotime($row['att_date'])) . (date('Y', strtotime($row['att_date'])) + 543);
            $rangeStr = '-';
            if (!empty($row['att_start_date']) && !empty($row['att_end_date']) && $row['att_start_date'] !== $row['att_end_date']) {
                $sDate = date('d/m/', strtotime($row['att_start_date'])) . (date('Y', strtotime($row['att_start_date'])) + 543);
                $eDate = date('d/m/', strtotime($row['att_end_date'])) . (date('Y', strtotime($row['att_end_date'])) + 543);
                $rangeStr = $sDate . ' - ' . $eDate;
            }
            $timeStr = !empty($row['att_time']) ? date('H:i', strtotime($row['att_time'])) . ' น.' : '-';

            $sheet->setCellValue('A' . $rowNum, $i++);
            $sheet->setCellValue('B' . $rowNum, $dateStr);
            $sheet->setCellValue('C' . $rowNum, $rangeStr);
            $sheet->setCellValue('D' . $rowNum, $row['team_name']);
            $sheet->setCellValueExplicit('E' . $rowNum, (string)$row['StudentCode'], \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
            $sheet->setCellValue('F' . $rowNum, $row['StudentPrefix']);
            $sheet->setCellValue('G' . $rowNum, $row['StudentFirstName']);
            $sheet->setCellValue('H' . $rowNum, $row['StudentLastName']);
            $sheet->setCellValue('I' . $rowNum, $row['StudentClass']);
            $sheet->setCellValue('J' . $rowNum, $pe);
            $sheet->setCellValue('K' . $rowNum, $st);
            $sheet->setCellValue('L' . $rowNum, $row['att_note'] ?: '-');
            $sheet->setCellValue('M' . $rowNum, $timeStr);

            // Alignment & Borders
            foreach (['A', 'B', 'C', 'E', 'I', 'J', 'K', 'M'] as $col) {
                $sheet->getStyle($col . $rowNum)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            }
            foreach ($allCols as $col) {
                $sheet->getStyle($col . $rowNum)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN)->getColor()->setRGB('CBD5E1');
            }

            $rowNum++;
        }

        // 5. Signatures Block
        $rowNum += 2;
        $sheet->mergeCells('A' . $rowNum . ':F' . $rowNum);
        $sheet->setCellValue('A' . $rowNum, 'ลงชื่อ...........................................................................ผู้รายงาน');
        $sheet->getStyle('A' . $rowNum)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        $sheet->mergeCells('G' . $rowNum . ':M' . $rowNum);
        $sheet->setCellValue('G' . $rowNum, 'ลงชื่อ...........................................................................ผู้ตรวจรับรอง');
        $sheet->getStyle('G' . $rowNum)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        $rowNum++;
        $sheet->mergeCells('A' . $rowNum . ':F' . $rowNum);
        $sheet->setCellValue('A' . $rowNum, '(...........................................................................)');
        $sheet->getStyle('A' . $rowNum)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        $sheet->mergeCells('G' . $rowNum . ':M' . $rowNum);
        $sheet->setCellValue('G' . $rowNum, '(...........................................................................)');
        $sheet->getStyle('G' . $rowNum)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        $rowNum++;
        $sheet->mergeCells('A' . $rowNum . ':F' . $rowNum);
        $sheet->setCellValue('A' . $rowNum, 'ตำแหน่ง: โค้ช / ผู้ดูแลประจำทีม');
        $sheet->getStyle('A' . $rowNum)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        $sheet->mergeCells('G' . $rowNum . ':M' . $rowNum);
        $sheet->setCellValue('G' . $rowNum, 'ตำแหน่ง: หัวหน้าผู้ฝึกสอน / หัวหน้าโครงการ');
        $sheet->getStyle('G' . $rowNum)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        $rowNum++;
        $sheet->mergeCells('A' . $rowNum . ':F' . $rowNum);
        $sheet->setCellValue('A' . $rowNum, 'วันที่ ........ / ........ / ................');
        $sheet->getStyle('A' . $rowNum)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        $sheet->mergeCells('G' . $rowNum . ':M' . $rowNum);
        $sheet->setCellValue('G' . $rowNum, 'วันที่ ........ / ........ / ................');
        $sheet->getStyle('G' . $rowNum)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // ตั้งค่าความกว้างคอลัมน์ A ถึง M ให้ได้สัดส่วนพอดีกับขนาด A4 แนวนอน
        $columnWidths = [
            'A' => 6,   // #
            'B' => 14,  // วันที่ลงเวลา
            'C' => 18,  // ช่วงวันที่ลา/แข่ง
            'D' => 20,  // รุ่น/ทีม
            'E' => 13,  // รหัสนักเรียน
            'F' => 10,  // คำนำหน้า
            'G' => 15,  // ชื่อ
            'H' => 16,  // นามสกุล
            'I' => 9,   // ชั้นเรียน
            'J' => 13,  // ช่วงเวลา
            'K' => 9,   // สถานะ
            'L' => 22,  // หมายเหตุ
            'M' => 12   // เวลาบันทึก
        ];
        foreach ($columnWidths as $col => $width) {
            $sheet->getColumnDimension($col)->setWidth($width);
        }

        $filename = 'รายงานการเช็กชื่อและประวัติการลา_' . date('Ymd_His') . '.xlsx';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        header('Pragma: no-cache');
        header('Expires: 0');

        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }

    /**
     * บันทึกมาซ้อมครบทุกคนใน 1 คลิก (Mark All Present AJAX)
     */
    public function markAllPresent()
    {
        return $this->markAllStatus();
    }

    /**
     * ดูประวัติการลา
     */
    public function history($teamId = null)
    {
        $data['title'] = 'ประวัติการบันทึกการลา';
        $data['uri'] = service('uri')->setSilent(true);
        
        $data['teams'] = $this->teamModel->getTeamsWithCounts(null);

        // โค้ชสามารถสลับดูประวัติการลาได้ของทุกรุ่น
        $data['selectedTeam'] = $teamId;
        $data['statuses'] = AttendanceModel::$statuses;

        // กรองตามวันที่
        $startDate = $this->request->getGet('start') ?? date('Y-m-01');
        $endDate = $this->request->getGet('end') ?? date('Y-m-d');
        
        $data['startDate'] = $startDate;
        $data['endDate'] = $endDate;

        // ดึงข้อมูลประวัติ
        $builder = $this->attendanceModel->db->table('tb_attendance att')
            ->select('att.*, std.StudentCode, std.StudentPrefix, std.StudentFirstName, std.StudentLastName, std.StudentClass, t.team_name')
            ->join('skjacth_academic.tb_students std', 'std.StudentID = att.StudentID')
            ->join('tb_teams t', 't.team_id = att.team_id')
            ->where('att.att_date >=', $startDate)
            ->where('att.att_date <=', $endDate)
            ->orderBy('att.att_date', 'DESC')
            ->orderBy('att.created_at', 'DESC');

        if ($teamId) {
            $builder->where('att.team_id', $teamId);
        }

        $history = $builder->get()->getResultArray();
        $data['history'] = $history;

        // คำนวณสรุปรายทีม (สำหรับแสดงในหน้าสรุป)
        $teamSummary = [];
        foreach ($history as $row) {
            $teamName = $row['team_name'];
            if (!isset($teamSummary[$teamName])) {
                $teamSummary[$teamName] = ['sick' => 0, 'personal' => 0, 'home' => 0, 'total' => 0];
            }
            if (isset($teamSummary[$teamName][$row['att_status']])) {
                $teamSummary[$teamName][$row['att_status']]++;
                $teamSummary[$teamName]['total']++;
            }
        }
        $data['teamSummary'] = $teamSummary;

        return view('Admin/AdminAttendance/AdminAttendanceHistory', $data);
    }

    /**
        if ($teamId) {
            $builder->where('att.team_id', $teamId);
        }

        $history = $builder->get()->getResultArray();

        $statuses = AttendanceModel::$statuses;
        $periods = AttendanceModel::$periods;

        $filename = 'รายงานการเช็กชื่อและประวัติการลา_' . date('Ymd_His') . '.csv';

        header('Content-Type: text/csv; charset=UTF-8');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Pragma: no-cache');
        header('Expires: 0');

        $output = fopen('php://output', 'w');

        // ใส่ UTF-8 BOM เพื่อให้ Microsoft Excel เปิดภาษาไทยได้ถูกต้องโดยไม่ต้องแปลง Encoding
        fwrite($output, "\xEF\xBB\xBF");

        // หัวตาราง
        fputcsv($output, [
            'ลำดับ',
            'วันที่ลงเวลา',
            'ช่วงวันที่ลา/แข่งขัน',
            'รุ่น/ทีม',
            'รหัสนักเรียน',
            'คำนำหน้า',
            'ชื่อ',
            'นามสกุล',
            'ชั้นเรียน',
            'ช่วงเวลา',
            'สถานะ',
            'หมายเหตุ',
            'เวลาบันทึก'
        ]);

        $i = 1;
        foreach ($history as $row) {
            $st = $statuses[$row['att_status']]['label'] ?? $row['att_status'];
            $pe = $periods[$row['att_period']]['label'] ?? $row['att_period'];

            $dateStr = date('d/m/Y', strtotime($row['att_date']));
            $rangeStr = '-';
            if (!empty($row['att_start_date']) && !empty($row['att_end_date']) && $row['att_start_date'] !== $row['att_end_date']) {
                $rangeStr = date('d/m/Y', strtotime($row['att_start_date'])) . ' - ' . date('d/m/Y', strtotime($row['att_end_date']));
            }

            $timeStr = !empty($row['att_time']) ? date('H:i', strtotime($row['att_time'])) . ' น.' : '-';

            fputcsv($output, [
                $i++,
                $dateStr,
                $rangeStr,
                $row['team_name'],
                $row['StudentCode'],
                $row['StudentPrefix'],
                $row['StudentFirstName'],
                $row['StudentLastName'],
                $row['StudentClass'],
                $pe,
                $st,
                $row['att_note'] ?: '-',
                $timeStr
            ]);
        }

        fclose($output);
        exit;
    }

    /**
     * พิมพ์รายงานการเช็กชื่อและประวัติการลา (หน้า A4)
     */
    public function printHistory()
    {
        $teamId = $this->request->getGet('team');
        $startDate = $this->request->getGet('start') ?? date('Y-m-01');
        $endDate = $this->request->getGet('end') ?? date('Y-m-d');

        $data['title'] = 'รายงานการเช็กชื่อและประวัติการลา (A4)';
        $data['startDate'] = $startDate;
        $data['endDate'] = $endDate;
        $data['selectedTeam'] = $teamId;
        $data['statuses'] = AttendanceModel::$statuses;
        $data['periods'] = AttendanceModel::$periods;

        $teamInfo = null;
        if ($teamId) {
            $teamInfo = $this->teamModel->find($teamId);
        }
        $data['teamInfo'] = $teamInfo;

        $builder = $this->attendanceModel->db->table('tb_attendance att')
            ->select('att.*, std.StudentCode, std.StudentPrefix, std.StudentFirstName, std.StudentLastName, std.StudentClass, t.team_name')
            ->join('skjacth_academic.tb_students std', 'std.StudentID = att.StudentID')
            ->join('tb_teams t', 't.team_id = att.team_id')
            ->where('att.att_date >=', $startDate)
            ->where('att.att_date <=', $endDate)
            ->orderBy('att.att_date', 'DESC')
            ->orderBy('att.created_at', 'DESC');

        if ($teamId) {
            $builder->where('att.team_id', $teamId);
        }

        $history = $builder->get()->getResultArray();
        $data['history'] = $history;

        // คำนวณสรุปสถานะ
        $summary = [
            'present' => 0,
            'sick' => 0,
            'personal' => 0,
            'home' => 0,
            'competition' => 0,
            'absent' => 0,
            'total' => count($history)
        ];
        foreach ($history as $row) {
            if (isset($summary[$row['att_status']])) {
                $summary[$row['att_status']]++;
            }
        }
        $data['summary'] = $summary;

        return view('Admin/AdminAttendance/AdminAttendancePrint', $data);
    }
}
