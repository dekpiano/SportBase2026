<?php

namespace App\Controllers;

use App\Models\AttendanceModel;
use App\Models\TeamModel;

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

        $coachId = (session()->get('status') == 'coach') ? session()->get('id') : null;
        $data['teams'] = $this->teamModel->getTeamsWithCounts($coachId);
        
        $data['todaySummary'] = $this->attendanceModel->getTodaySummary();
        $data['statuses'] = AttendanceModel::$statuses;

        return view('Admin/AdminAttendance/AdminAttendanceMain', $data);
    }

    /**
     * หน้าเช็คชื่อตามทีม
     */
    public function team($teamId)
    {
        // ตรวจสอบสิทธิ์ถ้าเป็น Coach
        if (session()->get('status') == 'coach') {
            if (!$this->teamModel->isCoachOfTeam($teamId, session()->get('id'))) {
                return redirect()->to('Admin/Attendance')->with('error', 'คุณไม่มีสิทธิ์เข้าถึงทีมนี้');
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

        // ตรวจสอบสิทธิ์ก่อนบันทึก
        if (session()->get('status') == 'coach') {
            if (!$this->teamModel->isCoachOfTeam($teamId, session()->get('id'))) {
                return $this->response->setJSON(['success' => false, 'message' => 'คุณไม่มีสิทธิ์จัดการทีมนี้']);
            }
        }

        $date = $this->request->getPost('date');
        $endDate = $this->request->getPost('end_date');
        $status = $this->request->getPost('status');
        $note = $this->request->getPost('note') ?? '';
        $checkedBy = session()->get('username') ?? session()->get('id');

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

        // ลบข้อมูลการลาเก่าที่คาบเกี่ยวกับช่วงวันที่ใหม่นี้ออกก่อน ป้องกันข้อมูลทับซ้อน
        $this->attendanceModel->removeAttendanceRange($studentId, $date, $targetEndDate);

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

        $athletes = $this->teamModel->getTeamAthletes($teamId);
        $checkedBy = session()->get('username') ?? session()->get('id');
        $statusLabel = AttendanceModel::$statuses[$status]['label'] ?? $status;

        foreach ($athletes as $athlete) {
            $this->attendanceModel->removeAttendanceRange($athlete['StudentID'], $date, $endDate);

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
        
        $coachId = (session()->get('status') == 'coach') ? session()->get('id') : null;
        $data['teams'] = $this->teamModel->getTeamsWithCounts($coachId);

        // ถ้าเป็น Coach และมีการระบุ teamId ต้องเช็คสิทธิ์
        if ($teamId && $coachId) {
             if (!$this->teamModel->isCoachOfTeam($teamId, $coachId)) {
                return redirect()->to('Admin/Attendance/History')->with('error', 'คุณไม่มีสิทธิ์เข้าถึงทีมนี้');
            }
        }
        
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
}
