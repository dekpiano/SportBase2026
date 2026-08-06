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
        $data['uri'] = service('uri')->setSilent(true);
        return $data;
    }

    /**
     * คำนวณสถานะการแข่งขันตามช่วงวัน-เวลาอัตโนมัติ (ระดับนาที)
     * เวลาปัจจุบัน < เวลาเริ่ม => Upcoming (รอดำเนินการ)
     * เวลาเริ่ม <= เวลาปัจจุบัน <= เวลาจบ => In Progress (กำลังแข่งขัน)
     * เวลาปัจจุบัน > เวลาจบ => Finished (แข่งขันเสร็จสิ้น)
     */
    private function calculateAutoStatus($startDateTime, $endDateTime, $requestedStatus = null)
    {
        if ($requestedStatus === 'Canceled') {
            return 'Canceled';
        }

        $now = date('Y-m-d H:i:s');
        $start = !empty($startDateTime) ? date('Y-m-d H:i:s', strtotime($startDateTime)) : $now;
        $end = !empty($endDateTime) ? date('Y-m-d H:i:s', strtotime($endDateTime)) : $start;

        if ($now < $start) {
            return 'Upcoming';
        } else if ($now >= $start && $now <= $end) {
            return 'In Progress';
        } else {
            return 'Finished';
        }
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
                match_date DATETIME NOT NULL,
                match_end_date DATETIME,
                match_time TIME,
                match_status ENUM('Upcoming', 'In Progress', 'Finished', 'Canceled') DEFAULT 'Upcoming',
                match_note TEXT,
                created_by VARCHAR(100),
                updated_by VARCHAR(100),
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                FOREIGN KEY (team_id) REFERENCES tb_teams(team_id) ON DELETE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");
        } else {
            // Auto add match_end_date column if not exists
            if (!$db->fieldExists('match_end_date', 'tb_matches')) {
                $db->query("ALTER TABLE tb_matches ADD COLUMN match_end_date DATETIME AFTER match_date");
                $db->query("UPDATE tb_matches SET match_end_date = match_date WHERE match_end_date IS NULL");
            }
            // Modify column types to DATETIME if they were DATE
            try {
                $db->query("ALTER TABLE tb_matches MODIFY match_date DATETIME NOT NULL, MODIFY match_end_date DATETIME");
            } catch (\Exception $e) {
                // Ignore if already DATETIME
            }
        }

        // Auto create tb_match_reports if not exists
        if (!$db->tableExists('tb_match_reports')) {
            $db->query("CREATE TABLE tb_match_reports (
                report_id INT AUTO_INCREMENT PRIMARY KEY,
                match_id INT NOT NULL,
                match_result VARCHAR(255) NOT NULL,
                match_summary TEXT,
                report_photos TEXT,
                created_by VARCHAR(100),
                updated_by VARCHAR(100),
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                FOREIGN KEY (match_id) REFERENCES tb_matches(match_id) ON DELETE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");
        }

        $data = $this->DataMain();
        $data['title'] = "ตารางการแข่งขัน";
        $matches = $this->matchModel->getMatchesWithTeams();

        // คำนวณและอัปเดตสถานะอัตโนมัติสำหรับทุกรายการแข่งขัน
        foreach ($matches as &$m) {
            $endDate = !empty($m['match_end_date']) ? $m['match_end_date'] : $m['match_date'];
            $autoStatus = $this->calculateAutoStatus($m['match_date'], $endDate, $m['match_status']);
            if ($autoStatus !== $m['match_status']) {
                $this->matchModel->update($m['match_id'], ['match_status' => $autoStatus]);
                $m['match_status'] = $autoStatus;
            }
            $m['match_end_date'] = $endDate;
        }

        $data['matches'] = $matches;

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

        $matchDate = $this->request->getPost('match_date');
        $matchEndDate = $this->request->getPost('match_end_date') ?: $matchDate;
        $requestedStatus = $this->request->getPost('match_status');

        // คำนวณสถานะอัตโนมัติ
        $autoStatus = $this->calculateAutoStatus($matchDate, $matchEndDate, $requestedStatus);

        $data = [
            'team_id'        => $teamId,
            'match_title'    => $this->request->getPost('match_title'),
            'match_location' => $this->request->getPost('match_location'),
            'match_date'     => date('Y-m-d H:i:s', strtotime($matchDate)),
            'match_end_date' => date('Y-m-d H:i:s', strtotime($matchEndDate)),
            'match_status'   => $autoStatus,
            'match_note'     => $this->request->getPost('match_note'),
            'updated_by'     => $userId,
        ];

        if ($id) {
            // กรณีแก้ไข ต้องเช็คอีกรอบว่า Match เดิมเป็นของทีมที่เราดูแลไหม
            if (session()->get('status') == 'coach') {
                $existingMatch = $this->matchModel->find($id);
                if ($existingMatch && !$this->teamModel->isCoachOfTeam($existingMatch['team_id'], $userId)) {
                    return $this->response->setJSON(['success' => false, 'message' => 'คุณไม่มีสิทธิ์แก้ไขข้อมูลเดิมของทีมนี้']);
                }
            }
            $this->matchModel->update($id, $data);
            $targetMatchId = $id;
            $message = 'แก้ไขข้อมูลการแข่งขันเรียบร้อยแล้ว';
        } else {
            $data['created_by'] = $userId;
            $targetMatchId = $this->matchModel->insert($data);
            $message = 'เพิ่มข้อมูลการแข่งขันเรียบร้อยแล้ว';
        }

        // จัดการบันทึกรายงานผลการแข่งขันและภาพบรรยากาศเพิ่มเติม (ถ้ามีการกรอกหรือแนบไฟล์)
        $matchResult = $this->request->getPost('match_result');
        $matchSummary = $this->request->getPost('match_summary');

        $db = \Config\Database::connect();
        $existingReport = $db->table('tb_match_reports')->where('match_id', $targetMatchId)->get()->getRowArray();
        $photosList = $existingReport && !empty($existingReport['report_photos']) 
            ? json_decode($existingReport['report_photos'], true) 
            : [];
        if (!is_array($photosList)) {
            $photosList = [];
        }

        $uploadDir = FCPATH . 'uploads/matches/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $files = $this->request->getFiles();
        if (isset($files['photos'])) {
            foreach ($files['photos'] as $file) {
                if ($file && $file->isValid() && !$file->hasMoved()) {
                    $newName = $file->getRandomName();
                    $file->move($uploadDir, $newName);
                    $photosList[] = $newName;
                }
            }
        }

        if (!empty($matchResult) || !empty($matchSummary) || !empty($files['photos']) || $existingReport) {
            $reportData = [
                'match_id'      => $targetMatchId,
                'match_result'  => $matchResult !== null ? $matchResult : ($existingReport['match_result'] ?? ''),
                'match_summary' => $matchSummary !== null ? $matchSummary : ($existingReport['match_summary'] ?? ''),
                'report_photos' => json_encode(array_values($photosList)),
                'updated_by'     => $userId,
            ];

            if ($existingReport) {
                $db->table('tb_match_reports')->where('report_id', $existingReport['report_id'])->update($reportData);
            } else {
                $reportData['created_by'] = $userId;
                $db->table('tb_match_reports')->insert($reportData);
            }
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

        // ลบไฟล์รูปภาพการแข่งขันในรายงานผลออกจากดิสก์ (ถ้ามี)
        $db = \Config\Database::connect();
        $report = $db->table('tb_match_reports')->where('match_id', $id)->get()->getRowArray();
        if ($report && !empty($report['report_photos'])) {
            $photos = json_decode($report['report_photos'], true);
            if (is_array($photos)) {
                foreach ($photos as $photoName) {
                    $filePath = FCPATH . 'uploads/matches/' . $photoName;
                    if (file_exists($filePath)) {
                        @unlink($filePath);
                    }
                }
            }
            $db->table('tb_match_reports')->where('match_id', $id)->delete();
        }

        if ($this->matchModel->delete($id)) {
            return $this->response->setJSON(['success' => true, 'message' => 'ลบข้อมูลการแข่งขันเรียบร้อยแล้ว']);
        }
        return $this->response->setJSON(['success' => false, 'message' => 'ไม่สามารถลบข้อมูลได้']);
    }

    /**
     * ดึงข้อมูลรายงานผลการแข่งขัน
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

    /**
     * บันทึกรายงานผลการแข่งขันและรูปภาพ
     */
    public function saveReport()
    {
        $matchId = $this->request->getPost('match_id');
        $matchResult = $this->request->getPost('match_result');
        $matchSummary = $this->request->getPost('match_summary');
        $userId = session()->get('id');

        $match = $this->matchModel->find($matchId);
        if (!$match) {
            return $this->response->setJSON(['success' => false, 'message' => 'ไม่พบรายการแข่งขัน']);
        }

        if (session()->get('status') == 'coach') {
            if (!$this->teamModel->isCoachOfTeam($match['team_id'], $userId)) {
                return $this->response->setJSON(['success' => false, 'message' => 'คุณไม่มีสิทธิ์บันทึกรายงานผลของทีมนี้']);
            }
        }

        $db = \Config\Database::connect();
        $existingReport = $db->table('tb_match_reports')->where('match_id', $matchId)->get()->getRowArray();
        $photosList = $existingReport && !empty($existingReport['report_photos']) 
            ? json_decode($existingReport['report_photos'], true) 
            : [];

        // Upload Direct Match Photos
        $uploadDir = FCPATH . 'uploads/matches/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $files = $this->request->getFiles();
        if (isset($files['photos'])) {
            foreach ($files['photos'] as $file) {
                if ($file && $file->isValid() && !$file->hasMoved()) {
                    $newName = $file->getRandomName();
                    $file->move($uploadDir, $newName);
                    $photosList[] = $newName;
                }
            }
        }

        $data = [
            'match_id'      => $matchId,
            'match_result'  => $matchResult,
            'match_summary' => $matchSummary,
            'report_photos' => json_encode(array_values($photosList)),
            'updated_by'     => $userId,
        ];

        if ($existingReport) {
            $db->table('tb_match_reports')->where('report_id', $existingReport['report_id'])->update($data);
        } else {
            $data['created_by'] = $userId;
            $db->table('tb_match_reports')->insert($data);
        }

        return $this->response->setJSON(['success' => true, 'message' => 'บันทึกรายงานผลการแข่งขันเรียบร้อยแล้ว']);
    }

    /**
     * ลบรูปภาพออกจากรายงานผลการแข่งขัน
     */
    public function deleteReportPhoto()
    {
        $matchId = $this->request->getPost('match_id');
        $photoName = trim((string)$this->request->getPost('photo_name'));
        $userId = session()->get('id');

        $match = $this->matchModel->find($matchId);
        if (!$match) {
            return $this->response->setJSON(['success' => false, 'message' => 'ไม่พบรายการแข่งขัน']);
        }

        if (session()->get('status') == 'coach') {
            if (!$this->teamModel->isCoachOfTeam($match['team_id'], $userId)) {
                return $this->response->setJSON(['success' => false, 'message' => 'คุณไม่มีสิทธิ์จัดการข้อมูลของทีมนี้']);
            }
        }

        $db = \Config\Database::connect();
        $report = $db->table('tb_match_reports')->where('match_id', $matchId)->get()->getRowArray();
        if ($report) {
            $photosList = !empty($report['report_photos']) ? json_decode($report['report_photos'], true) : [];
            if (!is_array($photosList)) {
                $photosList = [];
            }

            $newPhotos = [];
            $found = false;
            foreach ($photosList as $p) {
                if (trim((string)$p) === $photoName) {
                    $found = true;
                    $filePath = FCPATH . 'uploads/matches/' . trim((string)$p);
                    if (file_exists($filePath)) {
                        @unlink($filePath);
                    }
                } else {
                    $newPhotos[] = $p;
                }
            }

            if ($found) {
                $db->table('tb_match_reports')
                    ->where('report_id', $report['report_id'])
                    ->update(['report_photos' => json_encode(array_values($newPhotos))]);

                return $this->response->setJSON(['success' => true, 'message' => 'ลบรูปภาพเรียบร้อยแล้ว']);
            }
        }
        return $this->response->setJSON(['success' => false, 'message' => 'ไม่พบรูปภาพที่ระบุ']);
    }
}
