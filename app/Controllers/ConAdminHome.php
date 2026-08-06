<?php

namespace App\Controllers;

class ConAdminHome extends BaseController
{
    public function __construct(){
        $session = session();
        if(!$session->get('username') && $session->get('status') != "admin" && $session->get('status') != "manager"){
            header("Location:".base_url()); exit();
        } 
    }


    public function DataMain(){
        $data['full_url'] = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http') . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
        $data['uri'] = service('uri')->setSilent(true); 
        $data['description'] = "ระบบจัดการข้อมูลนักกีฬา SKJ SportBase 2026 โรงเรียนสวนกุหลาบวิทยาลัย (จิรประวัติ) นครสวรรค์";
        return $data;
    }

    public function index()
    {
        $session = session();
        $data = $this->DataMain();
        $data['title']="หน้าแรก";
        
        $db = \Config\Database::connect();
        
        // นับนักกีฬา (นับเฉพาะ StudentID ที่ไม่ซ้ำจากตาราง tb_team_athletes)
        $data['countAllAthlete'] = $db->table('tb_team_athletes')
            ->select('StudentID')
            ->distinct()
            ->countAllResults();

        // นับผู้ฝึกสอน/โค้ช (นับเฉพาะ coach_id ที่ไม่ซ้ำจากตาราง tb_team_coaches)
        $data['countAllPersonnel'] = $db->table('tb_team_coaches')
            ->select('coach_id')
            ->distinct()
            ->countAllResults();

        // นับประเภทกีฬา (นับจากตาราง tb_teams)
        $data['countAllSportType'] = $db->table('tb_teams')
            ->select('team_sport_type')
            ->distinct()
            ->countAllResults();

        // --- ระบบตรวจสอบและเพิ่มคอลัมน์ Audit (สร้างโดยใคร/แก้ไขโดยใคร) ---
        $tables = ['tb_teams', 'tb_team_athletes', 'tb_team_coaches', 'tb_attendance', 'tb_admin_rloes'];
        foreach ($tables as $table) {
            if ($db->tableExists($table)) {
                $fields = $db->getFieldNames($table);
                if (!in_array('created_by', $fields) && !in_array('checked_by', $fields)) {
                    $db->query("ALTER TABLE $table ADD COLUMN created_by VARCHAR(100)");
                }
                if (!in_array('updated_by', $fields)) {
                    $db->query("ALTER TABLE $table ADD COLUMN updated_by VARCHAR(100)");
                }
            }
        }
        // ---------------------------------------------------------

        // ดึงข้อมูลการแข่งขันที่จะเกิดขึ้น
        $matchModel = new \App\Models\MatchModel();
        $data['upcomingMatches'] = $matchModel->getUpcomingMatches(10);
        $data['countAllMatch'] = $matchModel->countAllResults();

        return view('Admin/AdminHome/AdminPageHome', $data);
    }

    public function User()
    {
        $session = session();
        $data = $this->DataMain();
        $data['title']="หน้าแรก";
        $data['UrlMenuMain'] = "Main";

        // ดึงข้อมูลการแข่งขันที่จะเกิดขึ้นสำหรับหน้าแรก (Public)
        $matchModel = new \App\Models\MatchModel();
        $data['upcomingMatches'] = $matchModel->getUpcomingMatches(5);

        return view('User/UserLeyout/UserHeader',$data)
                .view('User/UserLeyout/UserMenuLeft', $data)
                .view('User/UserHome/UserPageHome', $data)
                .view('User/UserLeyout/UserFooter', $data);
    }    

}
