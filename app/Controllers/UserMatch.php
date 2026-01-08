<?php

namespace App\Controllers;

use App\Models\MatchModel;

class UserMatch extends BaseController
{
    public function index()
    {
        $data['title'] = "ตารางการแข่งขัน";
        $data['UrlMenuMain'] = "Match";
        $data['full_url'] = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http') . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
        $data['uri'] = service('uri'); 
        $data['description'] = "ตารางการแข่งขันกีฬา และกิจกรรมต่างๆ ของนักกีฬาโรงเรียนสวนกุหลาบวิทยาลัย (จิรประวัติ) นครสวรรค์";
        
        $matchModel = new MatchModel();
        // ดึงข้อมูลการแข่งขันทั้งหมดที่ยังไม่จบ หรือเพิ่งจบไปไม่นาน
        $data['matches'] = $matchModel->select('tb_matches.*, tb_teams.team_name, tb_teams.team_sport_type')
            ->join('tb_teams', 'tb_teams.team_id = tb_matches.team_id')
            ->orderBy('match_date', 'DESC')
            ->findAll();

        return view('User/UserLeyout/UserHeader', $data)
            . view('User/UserLeyout/UserMenuLeft', $data)
            . view('User/UserMatch/UserMatchList', $data)
            . view('User/UserLeyout/UserFooter', $data);
    }
}
