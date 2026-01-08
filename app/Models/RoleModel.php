<?php

namespace App\Models;

use CodeIgniter\Model;

class RoleModel extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'tb_admin_rloes';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'pers_id',
        'role_type',
        'role_position',
        'created_at',
        'created_by',
        'updated_by'
    ];

    protected $useTimestamps = false;

    /**
     * ประเภทสิทธิ์
     */
    public static $roleTypes = [
        'admin'   => ['label' => 'ผู้ดูแลระบบ', 'color' => 'danger', 'icon' => 'bx-shield'],
        'manager' => ['label' => 'ผู้บริหาร', 'color' => 'warning', 'icon' => 'bx-crown'],
        'coach'   => ['label' => 'ผู้ดูแลนักกีฬา/โค้ช', 'color' => 'info', 'icon' => 'bx-user-voice']
    ];

    /**
     * ตำแหน่งในแต่ละประเภท
     */
    public static $rolePositions = [
        'admin'   => ['ผู้ดูแลระบบ'],
        'manager' => ['ผู้อำนวยการ', 'รองผู้อำนวยการ', 'หัวหน้ากลุ่มสาระ'],
        'coach'   => ['ผู้ดูแลนักกีฬา', 'โค้ช']
    ];

    /**
     * ดึงข้อมูลสิทธิ์ทั้งหมดพร้อมข้อมูลบุคลากร
     */
    public function getRolesWithPersonnel()
    {
        $dbPersonnel = \Config\Database::connect('personnel');
        
        $roles = $this->findAll();
        $result = [];
        
        foreach ($roles as $role) {
            $person = $dbPersonnel->table('tb_personnel')
                ->select('pers_id, pers_prefix, pers_firstname, pers_lastname, pers_position')
                ->where('pers_id', $role['pers_id'])
                ->get()
                ->getRowArray();
            
            if ($person) {
                $role['person'] = $person;
                $result[] = $role;
            }
        }
        
        return $result;
    }

    /**
     * ดึงสิทธิ์ตามประเภท
     */
    public function getRolesByType($type)
    {
        $dbPersonnel = \Config\Database::connect('personnel');
        
        $roles = $this->where('role_type', $type)->findAll();
        $result = [];
        
        foreach ($roles as $role) {
            $person = $dbPersonnel->table('tb_personnel')
                ->select('pers_id, pers_prefix, pers_firstname, pers_lastname, pers_position')
                ->where('pers_id', $role['pers_id'])
                ->get()
                ->getRowArray();
            
            if ($person) {
                $role['person'] = $person;
                $result[] = $role;
            }
        }
        
        return $result;
    }

    /**
     * ตรวจสอบว่าผู้ใช้มีสิทธิ์หรือไม่
     */
    public function hasRole($persId, $roleType = null)
    {
        $builder = $this->where('pers_id', $persId);
        
        if ($roleType) {
            $builder->where('role_type', $roleType);
        }
        
        return $builder->countAllResults() > 0;
    }

    /**
     * ดึงสิทธิ์ของผู้ใช้
     */
    public function getUserRoles($persId)
    {
        return $this->where('pers_id', $persId)->findAll();
    }
}
