<?php

namespace App\Controllers;

use App\Models\RoleModel;

class ConAdminRoles extends BaseController
{
    protected $roleModel;

    public function __construct()
    {
        $session = session();
        if (!$session->get('username')) {
            header("Location:" . base_url());
            exit();
        }
        
        // จำกัดสิทธิ์เฉพาะผู้ดูแระบบ (admin) และ ผู้ดูแลระบบสูงสุด (super_admin) เท่านั้น
        $status = $session->get('status');
        if ($status != "admin" && $status != "super_admin") {
            echo "คุณไม่มีสิทธิ์เข้าถึงหน้านี้";
            exit();
        }

        $this->roleModel = new RoleModel();
    }

    public function DataMain()
    {
        $data['full_url'] = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http') . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
        $data['uri'] = service('uri')->setSilent(true);
        return $data;
    }

    public function index()
    {
        $data = $this->DataMain();
        $data['title'] = "กำหนดสิทธิ์การใช้งาน";

        // ดึงข้อมูลสิทธิ์ทั้งหมด
        $data['roles'] = $this->roleModel->getRolesWithPersonnel();
        $data['roleTypes'] = RoleModel::$roleTypes;
        $data['rolePositions'] = RoleModel::$rolePositions;

        // ดึงรายชื่อบุคลากรสำหรับเลือก
        $dbPersonnel = \Config\Database::connect('personnel');
        $data['personnel'] = $dbPersonnel->table('tb_personnel')
            ->select('pers_id, pers_prefix, pers_firstname, pers_lastname, pers_position, pers_img')
            ->where('pers_status', 'กำลังใช้งาน')
            ->orderBy('pers_firstname', 'ASC')
            ->get()->getResultArray();

        return view('Admin/AdminRoles/AdminRolesMain', $data);
    }

    /**
     * เพิ่มสิทธิ์
     */
    public function add()
    {
        $data = [
            'pers_id'       => $this->request->getPost('pers_id'),
            'role_type'     => $this->request->getPost('role_type'),
            'role_position' => $this->request->getPost('role_position'),
            'created_at'    => date('Y-m-d H:i:s'),
            'created_by'    => session()->get('id')
        ];

        // ตรวจสอบว่ามีสิทธิ์นี้อยู่แล้วหรือไม่
        $existing = $this->roleModel
            ->where('pers_id', $data['pers_id'])
            ->where('role_type', $data['role_type'])
            ->first();

        if ($existing) {
            return $this->response->setJSON(['success' => false, 'message' => 'ผู้ใช้นี้มีสิทธิ์นี้อยู่แล้ว']);
        }

        if ($this->roleModel->insert($data)) {
            return $this->response->setJSON(['success' => true, 'message' => 'เพิ่มสิทธิ์เรียบร้อยแล้ว']);
        } else {
            return $this->response->setJSON(['success' => false, 'message' => 'ไม่สามารถเพิ่มสิทธิ์ได้']);
        }
    }

    /**
     * ลบสิทธิ์
     */
    public function delete($id)
    {
        if ($this->roleModel->delete($id)) {
            return redirect()->to(base_url('Admin/Roles/Setting'))->with('success', 'ลบสิทธิ์เรียบร้อยแล้ว');
        } else {
            return redirect()->back()->with('error', 'ไม่สามารถลบสิทธิ์ได้');
        }
    }
}
