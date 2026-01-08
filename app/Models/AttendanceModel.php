<?php

namespace App\Models;

use CodeIgniter\Model;

class AttendanceModel extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'tb_attendance';
    protected $primaryKey       = 'att_id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'team_id',
        'StudentID',
        'att_date',
        'att_start_date',
        'att_end_date',
        'att_status',
        'att_note',
        'att_time',
        'checked_by',
        'created_at'
    ];

    protected $useTimestamps = false;

    /**
     * สถานะการลา
     */
    public static $statuses = [
        'sick'     => ['label' => 'ลาป่วย', 'color' => 'warning', 'icon' => 'bx-plus-medical'],
        'personal' => ['label' => 'ลากิจธุระ', 'color' => 'info', 'icon' => 'bx-briefcase'],
        'home'     => ['label' => 'ลากิจกลับบ้าน', 'color' => 'danger', 'icon' => 'bx-home']
    ];

    /**
     * ดึงข้อมูลการลาของทีมตามวันที่ (ตรวจสอบว่าวันที่เลือกอยู่ในช่วงลาหรือไม่)
     */
    public function getAttendanceByTeamAndDate($teamId, $date)
    {
        return $this->db->table('tb_attendance att')
            ->select('att.*, std.StudentCode, std.StudentPrefix, std.StudentFirstName, std.StudentLastName, std.StudentClass')
            ->join('skjacth_academic.tb_students std', 'std.StudentID = att.StudentID')
            ->where('att.team_id', $teamId)
            ->where('att.att_start_date <=', $date)
            ->where('att.att_end_date >=', $date)
            ->get()
            ->getResultArray();
    }

    /**
     * ดึงข้อมูลการลาของนักกีฬาคนหนึ่ง
     */
    public function getStudentAttendance($studentId, $date)
    {
        return $this->db->table('tb_attendance')
            ->where('StudentID', $studentId)
            ->where('att_date', $date)
            ->get()
            ->getRowArray();
    }

    /**
     * บันทึกการลา
     */
    public function saveAttendance($data)
    {
        // ตรวจสอบว่ามีข้อมูลอยู่แล้วหรือไม่
        $existing = $this->getStudentAttendance($data['StudentID'], $data['att_date']);
        
        if ($existing) {
            // อัปเดต
            return $this->db->table('tb_attendance')
                ->where('att_id', $existing['att_id'])
                ->update($data);
        } else {
            // เพิ่มใหม่
            return $this->db->table('tb_attendance')->insert($data);
        }
    }

    /**
     * ลบการลา (เปลี่ยนเป็นอยู่)
     */
    public function removeAttendance($studentId, $date)
    {
        return $this->db->table('tb_attendance')
            ->where('StudentID', $studentId)
            ->where('att_date', $date)
            ->delete();
    }

    /**
     * ลบการลาในช่วงวันที่ (สำหรับอัปเดตช่วงลาใหม่)
     */
    public function removeAttendanceRange($studentId, $startDate, $endDate)
    {
        return $this->db->table('tb_attendance')
            ->where('StudentID', $studentId)
            ->where('att_date >=', $startDate)
            ->where('att_date <=', $endDate)
            ->delete();
    }

    /**
     * ลบการลาทั้งหมดของนักกีฬาคนหนึ่ง
     */
    public function removeAttendanceByStudent($studentId)
    {
        return $this->db->table('tb_attendance')
            ->where('StudentID', $studentId)
            ->delete();
    }

    /**
     * สรุปจำนวนคนลาวันนี้
     */
    public function getTodaySummary($teamId = null)
    {
        $builder = $this->db->table('tb_attendance')
            ->select('att_status, COUNT(*) as count')
            ->where('att_date', date('Y-m-d'))
            ->groupBy('att_status');

        if ($teamId) {
            $builder->where('team_id', $teamId);
        }

        return $builder->get()->getResultArray();
    }

    /**
     * ดึงประวัติการลาของนักกีฬา
     */
    public function getStudentHistory($studentId, $limit = 30)
    {
        return $this->db->table('tb_attendance')
            ->where('StudentID', $studentId)
            ->orderBy('att_date', 'DESC')
            ->limit($limit)
            ->get()
            ->getResultArray();
    }
}
