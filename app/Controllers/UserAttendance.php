<?php

namespace App\Controllers;

use App\Models\AttendanceModel;
use App\Models\TeamModel;

class UserAttendance extends BaseController
{
    public function index()
    {
        $data['title'] = "สถานะภาพนักกีฬาประจำวัน";
        $data['UrlMenuMain'] = "Attendance";
        $data['full_url'] = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http') . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
        $data['uri'] = service('uri')->setSilent(true); 
        $data['description'] = "ตรวจสอบสถานะภาพนักกีฬา รายงานการลาประจำวัน โรงเรียนสวนกุหลาบวิทยาลัย (จิรประวัติ) นครสวรรค์";

        $attendanceModel = new AttendanceModel();
        $teamModel = new TeamModel();

        $date = $this->request->getGet('date') ?: date('Y-m-d');
        $data['selectedDate'] = $date;
        $data['statuses'] = AttendanceModel::$statuses;

        // ดึงข้อมูลทีมทั้งหมด
        $data['teams'] = $teamModel->findAll();

        // สรุปภาพรวมของวันที่เลือก
        $data['summary'] = $attendanceModel->getTodaySummary(null, $date);
        
        // ดึงข้อมูลการลาทั้งหมดในวันที่เลือก (รวมคนที่ลาคาบเกี่ยวช่วงนี้)
        $data['attendanceList'] = $attendanceModel->db->table('tb_attendance att')
            ->select('att.*, std.StudentCode, std.StudentPrefix, std.StudentFirstName, std.StudentLastName, std.StudentClass, t.team_name, t.team_sport_type')
            ->join('skjacth_academic.tb_students std', 'std.StudentID = att.StudentID')
            ->join('tb_teams t', 't.team_id = att.team_id')
            ->where('att.att_start_date <=', $date)
            ->where('att.att_end_date >=', $date)
            ->get()
            ->getResultArray();

        // ดึงจำนวนนักกีฬาทั้งหมดเพื่อคำนวณจำนวนคนที่ "อยู่"
        $totalAthletes = $teamModel->db->table('tb_team_athletes')->countAllResults();
        $totalAbsent = count($data['attendanceList']);
        $data['totalPresent'] = $totalAthletes - $totalAbsent;

        return view('User/UserLeyout/UserHeader', $data)
            . view('User/UserLeyout/UserMenuLeft', $data)
            . view('User/UserAttendance/UserAttendanceStatus', $data)
            . view('User/UserLeyout/UserFooter', $data);
    }
}
