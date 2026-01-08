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
        $data['title'] = 'บันทึกการลานักกีฬา';
        $data['uri'] = service('uri');

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

        // ดึงรายชื่อนักกีฬาในทีม
        $athletes = $this->teamModel->getTeamAthletes($teamId);
        
        // ดึงข้อมูลการลาวันนี้
        $attendanceToday = $this->attendanceModel->getAttendanceByTeamAndDate($teamId, $date);
        
        // สร้าง array สำหรับค้นหาง่าย
        $attendanceMap = [];
        foreach ($attendanceToday as $att) {
            $attendanceMap[$att['StudentID']] = $att;
        }

        $data['title'] = 'บันทึกการลา: ' . $team['team_name'];
        $data['uri'] = service('uri');
        $data['team'] = $team;
        $data['athletes'] = $athletes;
        $data['attendanceMap'] = $attendanceMap;
        $data['selectedDate'] = $date;
        $data['statuses'] = AttendanceModel::$statuses;

        return view('Admin/AdminAttendance/AdminAttendanceTeam', $data);
    }

    /**
     * บันทึกการลา (AJAX)
     */
    public function save()
    {
        $studentId = $this->request->getPost('student_id');
        $teamId = $this->request->getPost('team_id');

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

        if (empty($status) || $status === 'present') {
            // ลบ record (เปลี่ยนเป็นอยู่)
            $this->attendanceModel->removeAttendance($studentId, $date);
            return $this->response->setJSON([
                'success' => true,
                'message' => 'บันทึกสถานะ: อยู่',
                'status' => 'present'
            ]);
        }

        // เตรียมข้อมูลพื้นฐาน
        $baseData = [
            'team_id'    => $teamId,
            'StudentID'  => $studentId,
            'att_status' => $status,
            'att_note'   => $note,
            'att_time'   => date('H:i:s'),
            'checked_by' => session()->get('id'),
            'created_at' => date('Y-m-d H:i:s')
        ];

        // ถ้ามีช่วงวันที่ (ลาหลายวัน)
        if ($endDate && $endDate != $date) {
            // บันทึกแค่ 1 record พร้อมช่วงวันที่
            $baseData['att_date'] = $date;
            $baseData['att_start_date'] = $date;
            $baseData['att_end_date'] = $endDate;
            
            // ตรวจสอบว่ามี record อยู่แล้วหรือไม่
            $existing = $this->attendanceModel->where('StudentID', $studentId)->first();
            
            if ($existing) {
                // อัปเดต record เดิม
                $this->attendanceModel->update($existing['att_id'], $baseData);
            } else {
                // เพิ่มใหม่
                $this->attendanceModel->insert($baseData);
            }
            
            // คำนวณจำนวนวัน
            $dayCount = (strtotime($endDate) - strtotime($date)) / 86400 + 1;
            $message = 'บันทึกการลาช่วงวันที่ ' . date('d/m/Y', strtotime($date)) . ' ถึง ' . date('d/m/Y', strtotime($endDate)) . ' (' . $dayCount . ' วัน)';
        } else {
            // บันทึกแค่วันเดียว
            $baseData['att_date'] = $date;
            $baseData['att_start_date'] = $date;
            $baseData['att_end_date'] = $date;
            
            $this->attendanceModel->saveAttendance($baseData);
            $statusLabel = AttendanceModel::$statuses[$status]['label'] ?? $status;
            $message = 'บันทึกสถานะ: ' . $statusLabel;
        }

        return $this->response->setJSON([
            'success' => true,
            'message' => $message ?? ('บันทึกสถานะ: ' . (AttendanceModel::$statuses[$status]['label'] ?? $status)),
            'status' => $status,
            'debug' => [
                'date' => $date,
                'endDate' => $endDate,
                'count' => isset($count) ? $count : 1
            ]
        ]);
    }

    /**
     * ดูประวัติการลา
     */
    public function history($teamId = null)
    {
        $data['title'] = 'ประวัติการบันทึกการลา';
        $data['uri'] = service('uri');
        
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
