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
        $data['uri'] = service('uri')->setSilent(true); 
        $data['description'] = "ตารางการแข่งขันกีฬา และกิจกรรมต่างๆ ของนักกีฬาโรงเรียนสวนกุหลาบวิทยาลัย (จิรประวัติ) นครสวรรค์";
        
        $matchModel = new MatchModel();
        $matches = $matchModel->select('tb_matches.*, tb_teams.team_name, tb_teams.team_sport_type, tb_match_reports.report_id, tb_match_reports.match_result, tb_match_reports.match_summary, tb_match_reports.report_photos')
            ->join('tb_teams', 'tb_teams.team_id = tb_matches.team_id')
            ->join('tb_match_reports', 'tb_match_reports.match_id = tb_matches.match_id', 'left')
            ->orderBy('match_date', 'DESC')
            ->findAll();

        // Calculate auto status for public view
        $now = date('Y-m-d H:i:s');
        foreach ($matches as &$m) {
            $endDate = !empty($m['match_end_date']) ? $m['match_end_date'] : $m['match_date'];
            if ($m['match_status'] !== 'Canceled') {
                if ($now < $m['match_date']) {
                    $m['match_status'] = 'Upcoming';
                } else if ($now >= $m['match_date'] && $now <= $endDate) {
                    $m['match_status'] = 'In Progress';
                } else {
                    $m['match_status'] = 'Finished';
                }
            }
        }

        $data['matches'] = $matches;

        return view('User/UserLeyout/UserHeader', $data)
            . view('User/UserLeyout/UserMenuLeft', $data)
            . view('User/UserMatch/UserMatchList', $data)
            . view('User/UserLeyout/UserFooter', $data);
    }

    /**
     * ดึงข้อมูลรายงานผลการแข่งขันสำหรับผู้ใช้ทั่วไป
     */
    public function getReport($matchId)
    {
        $db = \Config\Database::connect();
        $report = $db->table('tb_match_reports')->where('match_id', $matchId)->get()->getRowArray();
        
        if ($report) {
            $report['photos'] = !empty($report['report_photos']) ? json_decode($report['report_photos'], true) : [];
            return $this->response->setJSON(['success' => true, 'report' => $report]);
        }
        return $this->response->setJSON(['success' => false, 'message' => 'ยังไม่มีรายงานผลการแข่งขัน']);
    }
}
