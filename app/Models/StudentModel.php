<?php

namespace App\Models;

use CodeIgniter\Model;

class StudentModel extends Model
{
    protected $DBGroup          = 'academic';
    protected $table            = 'tb_students';
    protected $primaryKey       = 'StudentID';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = []; // We usually don't write to this table from this system

    // Dates
    protected $useTimestamps = false;

    /**
     * ค้นหานักเรียนที่ยังไม่ได้เป็นนักกีฬา
     */
    public function getNonAthletes($search = '')
    {
        $builder = $this->db->table('tb_students');
        $builder->select('StudentID, StudentCode, StudentPrefix, StudentFirstName, StudentLastName, StudentClass');
        $builder->where("StudentID NOT IN (SELECT StudentID FROM skjacth_sportbase.tb_athletes)");
        
        if ($search) {
            $builder->groupStart()
                ->like('StudentFirstName', $search)
                ->orLike('StudentLastName', $search)
                ->orLike('StudentCode', $search)
                ->groupEnd();
        }

        return $builder->limit(20)->get()->getResultArray();
    }
}
