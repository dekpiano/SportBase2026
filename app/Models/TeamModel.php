<?php

namespace App\Models;

use CodeIgniter\Model;

class TeamModel extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'tb_teams';
    protected $primaryKey       = 'team_id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'team_name',
        'team_sport_type',
        'team_year',
        'team_status',
        'created_at',
        'created_by',
        'updated_by'
    ];

    protected $useTimestamps = false;

    /**
     * ดึงข้อมูลรุ่น/ทีมทั้งหมดพร้อมจำนวนสมาชิกและโค้ช
     */
    public function getTeamsWithCounts($coachId = null)
    {
        $builder = $this->db->table('tb_teams')
            ->select('tb_teams.*, 
                      (SELECT COUNT(*) FROM tb_team_athletes WHERE tb_team_athletes.team_id = tb_teams.team_id) as athlete_count,
                      (SELECT COUNT(*) FROM tb_team_coaches WHERE tb_team_coaches.team_id = tb_teams.team_id) as coach_count');
        
        if ($coachId) {
            $builder->join('tb_team_coaches', 'tb_team_coaches.team_id = tb_teams.team_id')
                    ->where('tb_team_coaches.coach_id', $coachId);
        }

        return $builder->orderBy('created_at', 'DESC')
            ->get()
            ->getResultArray();
    }

    /**
     * ตรวจสอบว่าเป็นผู้ฝึกสอนของรุ่นนี้หรือไม่
     */
    public function isCoachOfTeam($teamId, $coachId)
    {
        return $this->db->table('tb_team_coaches')
            ->where('team_id', $teamId)
            ->where('coach_id', $coachId)
            ->countAllResults() > 0;
    }

    /**
     * ดึงข้อมูลนักกีฬาในรุ่น
     */
    public function getTeamAthletes($teamId)
    {
        return $this->db->table('tb_team_athletes ta')
            ->select('ta.*, ta.athlete_image, std.StudentCode, std.StudentPrefix, std.StudentFirstName, std.StudentLastName, std.StudentClass')
            ->join('skjacth_academic.tb_students std', 'std.StudentID = ta.StudentID')
            ->where('ta.team_id', $teamId)
            ->get()
            ->getResultArray();
    }

    /**
     * ดึงข้อมูลโค้ชในรุ่น
     */
    public function getTeamCoaches($teamId)
    {
        return $this->db->table('tb_team_coaches tc')
            ->select('tc.*, pers.pers_prefix, pers.pers_firstname, pers.pers_lastname')
            ->join('skjacth_personnel.tb_personnel pers', 'pers.pers_id = tc.coach_id')
            ->where('tc.team_id', $teamId)
            ->get()
            ->getResultArray();
    }

    /**
     * เพิ่มนักกีฬาเข้ารุ่น
     */
    public function addAthlete($teamId, $studentId, $imageName = null)
    {
        $data = [
            'team_id' => $teamId,
            'StudentID' => $studentId,
            'created_by' => session()->get('id')
        ];
        
        if ($imageName) {
            $data['athlete_image'] = $imageName;
        }
        
        return $this->db->table('tb_team_athletes')->insert($data);
    }

    /**
     * เพิ่มโค้ชเข้ารุ่น
     */
    public function addCoach($teamId, $coachId)
    {
        return $this->db->table('tb_team_coaches')->insert([
            'team_id' => $teamId,
            'coach_id' => $coachId,
            'created_by' => session()->get('id')
        ]);
    }

    /**
     * ลบนักกีฬาออกจากรุ่น
     */
    public function removeAthlete($id)
    {
        return $this->db->table('tb_team_athletes')->where('id', $id)->delete();
    }

    /**
     * ลบโค้ชออกจากรุ่น
     */
    public function removeCoach($id)
    {
        return $this->db->table('tb_team_coaches')->where('id', $id)->delete();
    }
}
