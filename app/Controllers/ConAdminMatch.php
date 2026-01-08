<?php

namespace App\Controllers;

use App\Models\MatchModel;
use App\Models\TeamModel;

class ConAdminMatch extends BaseController
{
    protected $matchModel;
    protected $teamModel;

    public function __construct()
    {
        $this->matchModel = new MatchModel();
        $this->teamModel = new TeamModel();
    }

    private function DataMain()
    {
        $data['uri'] = service('uri');
        return $data;
    }

    public function index()
    {
        if (!in_array(session()->get('status'), ['admin', 'super_admin', 'coach'])) {
            return redirect()->to('Admin/Home');
        }

        // Check if table exists, if not create it
        $db = \Config\Database::connect();
        if (!$db->tableExists('tb_matches')) {
            $db->query("CREATE TABLE tb_matches (
                match_id INT AUTO_INCREMENT PRIMARY KEY,
                team_id INT NOT NULL,
                match_title VARCHAR(255) NOT NULL,
                match_location VARCHAR(255),
                match_date DATE NOT NULL,
                match_time TIME,
                match_status ENUM('Upcoming', 'In Progress', 'Finished', 'Canceled') DEFAULT 'Upcoming',
                match_note TEXT,
                created_by VARCHAR(100),
                updated_by VARCHAR(100),
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                FOREIGN KEY (team_id) REFERENCES tb_teams(team_id) ON DELETE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");
        }

        $data = $this->DataMain();
        $data['title'] = "ตารางการแข่งขัน";
        $data['matches'] = $this->matchModel->getMatchesWithTeams();

        // ---------------------------------------------
        // Permission & Filter Logic
        // ---------------------------------------------
        $coachId = (session()->get('status') == 'coach') ? session()->get('id') : null;
        
        // ดึงเฉพาะทีมที่ตัวเองดูแล (ถ้าเป็นโค้ช) หรือทั้งหมด (ถ้าเป็นแอดมิน)
        $teams = $this->teamModel->getTeamsWithCounts($coachId);
        $data['teams'] = $teams;

        // สร้างรายการ ID ทีมที่อนุญาตให้จัดการได้
        $allowedTeamIds = [];
        if (session()->get('status') == 'coach') {
            foreach ($teams as $t) {
                $allowedTeamIds[] = $t['team_id'];
            }
        } else {
            // Admin/SuperAdmin - อนุญาตทั้งหมด (ใช้ค่า null หรือ empty check ใน view เอา)
            $allowedTeamIds = null; 
        }
        $data['allowedTeamIds'] = $allowedTeamIds;
        // ---------------------------------------------

        return view('Admin/AdminMatch/AdminMatchMain', $data);
    }

    public function save()
    {
        $id = $this->request->getPost('match_id');
        $teamId = $this->request->getPost('team_id');
        $userId = session()->get('id');

        // Check Permission
        if (session()->get('status') == 'coach') {
            if (!$this->teamModel->isCoachOfTeam($teamId, $userId)) {
                return $this->response->setJSON(['success' => false, 'message' => 'คุณไม่มีสิทธิ์จัดการข้อมูลของทีมนี้']);
            }
        }

        $data = [
            'team_id'        => $teamId,
            'match_title'    => $this->request->getPost('match_title'),
            'match_location' => $this->request->getPost('match_location'),
            'match_date'     => $this->request->getPost('match_date'),
            'match_time'     => $this->request->getPost('match_time'),
            'match_status'   => $this->request->getPost('match_status'),
            'match_note'     => $this->request->getPost('match_note'),
            'updated_by'     => $userId,
        ];

        if ($id) {
            // กรณีแก้ไข ต้องเช็คอีกรอบว่า Match เดิมเป็นของทีมที่เราดูแลไหม (เผื่อเปลี่ยน team_id ข้ามไปมา)
            if (session()->get('status') == 'coach') {
                $existingMatch = $this->matchModel->find($id);
                if ($existingMatch && !$this->teamModel->isCoachOfTeam($existingMatch['team_id'], $userId)) {
                    return $this->response->setJSON(['success' => false, 'message' => 'คุณไม่มีสิทธิ์แก้ไขข้อมูลเดิมของทีมนี้']);
                }
            }
            $this->matchModel->update($id, $data);
            $message = 'แก้ไขข้อมูลการแข่งขันเรียบร้อยแล้ว';
        } else {
            $data['created_by'] = $userId;
            $this->matchModel->insert($data);
            $message = 'เพิ่มข้อมูลการแข่งขันเรียบร้อยแล้ว';
        }

        return $this->response->setJSON(['success' => true, 'message' => $message]);
    }

    public function delete($id)
    {
        $match = $this->matchModel->find($id);
        if (!$match) {
            return $this->response->setJSON(['success' => false, 'message' => 'ไม่พบข้อมูล']);
        }

        // Check Permission
        if (session()->get('status') == 'coach') {
            if (!$this->teamModel->isCoachOfTeam($match['team_id'], session()->get('id'))) {
                 return $this->response->setJSON(['success' => false, 'message' => 'คุณไม่มีสิทธิ์ลบข้อมูลของทีมนี้']);
            }
        }

        if ($this->matchModel->delete($id)) {
            return $this->response->setJSON(['success' => true, 'message' => 'ลบข้อมูลการแข่งขันเรียบร้อยแล้ว']);
        }
        return $this->response->setJSON(['success' => false, 'message' => 'ไม่สามารถลบข้อมูลได้']);
    }
}
