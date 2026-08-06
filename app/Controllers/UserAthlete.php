<?php

namespace App\Controllers;

use App\Models\TeamModel;

class UserAthlete extends BaseController
{
    public function index()
    {
        $data['title'] = "ทำเนียบนักกีฬา";
        $data['UrlMenuMain'] = "Athlete";
        $data['full_url'] = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http') . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
        $data['uri'] = service('uri')->setSilent(true); 
        $data['description'] = "ทำเนียบนักกีฬาและผู้มีทักษะด้านกีฬา โรงเรียนสวนกุหลาบวิทยาลัย (จิรประวัติ) นครสวรรค์";

        $teamModel = new TeamModel();
        
        // ดึงข้อมูลรุ่น/ทีมทั้งหมด
        $data['teams'] = $teamModel->getTeamsWithCounts();
        
        // ถ้ามีการเลือกทีม ให้ดึงนักกีฬาในทีมนั้น
        $selectedTeam = $this->request->getGet('team_id');
        if ($selectedTeam) {
            $data['athletes'] = $teamModel->getTeamAthletes($selectedTeam);
            $data['currentTeam'] = $teamModel->find($selectedTeam);
        } else {
            // ถ้าไม่เลือกทีม ให้แสดงเป็นค่าว่างเพื่อให้ผู้ใช้กดเลือกก่อน
            $data['athletes'] = null; 
            $data['currentTeam'] = null;
        }

        return view('User/UserLeyout/UserHeader', $data)
            . view('User/UserLeyout/UserMenuLeft', $data)
            . view('User/UserAthlete/UserAthleteList', $data)
            . view('User/UserLeyout/UserFooter', $data);
    }
}
