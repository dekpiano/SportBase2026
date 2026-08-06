<?php

namespace App\Models;

use CodeIgniter\Model;

class MatchModel extends Model
{
    protected $table            = 'tb_matches';
    protected $primaryKey       = 'match_id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'team_id',
        'match_title',
        'match_location',
        'match_date',
        'match_end_date',
        'match_time',
        'match_status',
        'match_note',
        'created_by',
        'updated_by'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    public function getMatchesWithTeams($status = null)
    {
        $builder = $this->db->table($this->table . ' m');
        $builder->select('m.*, t.team_name, t.team_sport_type, r.report_id, r.match_result, r.match_summary, r.report_photos');
        $builder->join('tb_teams t', 't.team_id = m.team_id');
        $builder->join('tb_match_reports r', 'r.match_id = m.match_id', 'left');
        
        if ($status) {
            $builder->where('m.match_status', $status);
        }
        
        $builder->orderBy('m.match_date', 'ASC');
        $builder->orderBy('m.match_time', 'ASC');
        
        return $builder->get()->getResultArray();
    }

    public function getUpcomingMatches($limit = 5)
    {
        return $this->db->table($this->table . ' m')
            ->select('m.*, t.team_name, t.team_sport_type, r.match_result')
            ->join('tb_teams t', 't.team_id = m.team_id')
            ->join('tb_match_reports r', 'r.match_id = m.match_id', 'left')
            ->where('m.match_date >=', date('Y-m-d'))
            ->whereIn('m.match_status', ['Upcoming', 'In Progress'])
            ->orderBy('m.match_date', 'ASC')
            ->orderBy('m.match_time', 'ASC')
            ->limit($limit)
            ->get()->getResultArray();
    }
}
