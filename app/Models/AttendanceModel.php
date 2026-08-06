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
        'att_period',
        'att_status',
        'att_note',
        'att_time',
        'checked_by',
        'created_at'
    ];

    protected $useTimestamps = false;

    /**
     * ช่วงเวลาเช็กชื่อ (Periods)
     */
    public static $periods = [
        'morning'  => ['label' => 'เช็กเข้าแถวเช้า', 'color' => 'warning', 'icon' => 'bx-sun', 'time' => '07:30 น.'],
        'training' => ['label' => 'เช็กเข้าซ้อมกีฬา', 'color' => 'primary', 'icon' => 'bx-run', 'time' => '16:30 น.'],
        'night'    => ['label' => 'เช็กเข้าห้องนอน', 'color' => 'info', 'icon' => 'bx-moon', 'time' => '21:00 น.']
    ];

    /**
     * สถานะการเช็กชื่อ/การลา
     */
    public static $statuses = [
        'present'  => ['label' => 'มาซ้อม / มาเรียน', 'color' => 'success', 'icon' => 'bx-check-circle'],
        'sick'     => ['label' => 'ลาป่วย', 'color' => 'warning', 'icon' => 'bx-plus-medical'],
        'personal' => ['label' => 'ลากิจธุระ', 'color' => 'info', 'icon' => 'bx-briefcase'],
        'home'     => ['label' => 'ลากิจกลับบ้าน', 'color' => 'danger', 'icon' => 'bx-home'],
        'competition' => ['label' => 'ไปแข่งขัน', 'color' => 'primary', 'icon' => 'bx-trophy'],
        'absent'   => ['label' => 'ขาดซ้อม / ขาดเรียน', 'color' => 'dark', 'icon' => 'bx-x-circle']
    ];

    /**
     * ตรวจสอบและเพิ่มคอลัมน์อัตโนมัติหากยังไม่มีใน MySQL
     */
    public function checkTableColumns()
    {
        static $checked = false;
        if ($checked) return;
        $checked = true;

        try {
            if ($this->db->tableExists($this->table)) {
                $fields = $this->db->getFieldNames($this->table);
                if (!in_array('att_period', $fields)) {
                    $this->db->query("ALTER TABLE {$this->table} ADD COLUMN att_period VARCHAR(50) DEFAULT 'morning' AFTER att_date");
                }
                if (!in_array('checked_by', $fields)) {
                    $this->db->query("ALTER TABLE {$this->table} ADD COLUMN checked_by VARCHAR(100) AFTER att_time");
                }
            }
        } catch (\Exception $e) {
            // Log or ignore if already added
        }
    }

    /**
     * ดึงข้อมูลการเช็กชื่อของทีมตามวันที่และช่วงเวลา
     * (หากนักเรียนมีสถานะการลา != 'present' จะแสดงผลครอบคลุมทุกช่วงเวลาโดยอัตโนมัติ)
     */
    public function getAttendanceByTeamAndDate($teamId, $date, $period = 'morning')
    {
        $this->checkTableColumns();
        $builder = $this->db->table('tb_attendance att')
            ->select('att.*, std.StudentCode, std.StudentPrefix, std.StudentFirstName, std.StudentLastName, std.StudentClass')
            ->join('skjacth_academic.tb_students std', 'std.StudentID = att.StudentID')
            ->where('att.team_id', $teamId)
            ->where('att.att_start_date <=', $date)
            ->where('att.att_end_date >=', $date);

        if ($period) {
            $builder->groupStart()
                    ->where('att.att_period', $period)
                    ->orWhere('att.att_period IS NULL')
                    ->orWhere('att.att_status !=', 'present')
                    ->groupEnd();
        }

        return $builder->get()->getResultArray();
    }

    /**
     * ดึงข้อมูลการเช็กชื่อของนักกีฬาคนหนึ่งตามวันที่และช่วงเวลา
     */
    public function getStudentAttendance($studentId, $date, $period = 'morning')
    {
        $this->checkTableColumns();
        $builder = $this->db->table('tb_attendance')
            ->where('StudentID', $studentId)
            ->where('att_start_date <=', $date)
            ->where('att_end_date >=', $date);

        if ($period) {
            $builder->groupStart()
                    ->where('att_period', $period)
                    ->orWhere('att_period IS NULL')
                    ->orWhere('att_status !=', 'present')
                    ->groupEnd();
        }

        return $builder->get()->getRowArray();
    }

    /**
     * บันทึกการเช็กชื่อ/การลา
     */
    public function saveAttendance($data)
    {
        $period = $data['att_period'] ?? 'morning';
        $existing = $this->getStudentAttendance($data['StudentID'], $data['att_date'], $period);
        
        if ($existing) {
            return $this->db->table('tb_attendance')
                ->where('att_id', $existing['att_id'])
                ->update($data);
        } else {
            return $this->db->table('tb_attendance')->insert($data);
        }
    }

    /**
     * ลบการเช็กชื่อตามช่วงเวลา หรือลบใบลาเมื่อสลับเป็น 'มา'
     */
    public function removeAttendance($studentId, $date, $period = 'morning')
    {
        return $this->db->table('tb_attendance')
            ->where('StudentID', $studentId)
            ->where('att_start_date <=', $date)
            ->where('att_end_date >=', $date)
            ->delete();
    }

    /**
     * ลบการลาในช่วงวันที่คาบเกี่ยวกัน (สำหรับอัปเดตช่วงลาใหม่ให้ไม่ซ้ำซ้อน)
     */
    public function removeAttendanceRange($studentId, $startDate, $endDate)
    {
        return $this->db->table('tb_attendance')
            ->where('StudentID', $studentId)
            ->where('att_start_date <=', $endDate)
            ->where('att_end_date >=', $startDate)
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
     * สรุปจำนวนคนลาในวันที่กำหนด (ตรวจสอบว่าอยู่ในช่วงลาหรือไม่)
     */
    public function getTodaySummary($teamId = null, $date = null)
    {
        $date = $date ?: date('Y-m-d');
        $builder = $this->db->table('tb_attendance')
            ->select('att_status, COUNT(*) as count')
            ->where('att_start_date <=', $date)
            ->where('att_end_date >=', $date)
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

    /**
     * ตรวจสอบสถานะการเช็กชื่อของแต่ละช่วงเวลาในทีม (ว่าลงเวลาแล้วหรือยังไม่ได้ลงเวลา)
     */
    public function getPeriodStatusMap($teamId, $date = null)
    {
        $this->checkTableColumns();
        $date = $date ?: date('Y-m-d');
        
        $rows = $this->db->table('tb_attendance')
            ->select('att_period, checked_by, MAX(att_time) as last_time, COUNT(*) as checked_count')
            ->where('team_id', $teamId)
            ->where('att_start_date <=', $date)
            ->where('att_end_date >=', $date)
            ->groupBy('att_period')
            ->get()
            ->getResultArray();

        $map = [
            'morning'  => ['checked' => false, 'by' => '', 'time' => ''],
            'training' => ['checked' => false, 'by' => '', 'time' => ''],
            'night'    => ['checked' => false, 'by' => '', 'time' => '']
        ];

        foreach ($rows as $r) {
            $p = $r['att_period'] ?: 'morning';
            if (isset($map[$p])) {
                $map[$p]['checked'] = ($r['checked_count'] > 0);
                $map[$p]['by'] = $r['checked_by'] ?? '';
                $map[$p]['time'] = $r['last_time'] ?? '';
            }
        }

        return $map;
    }
}
