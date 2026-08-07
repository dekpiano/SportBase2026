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
            $uploadRes = $this->uploadPhotosToRemoteServer($files['photos'], $matchDate);
            if (!$uploadRes['success']) {
                return $this->response->setJSON(['success' => false, 'message' => 'อัปโหลดรูปภาพล้มเหลว: ' . $uploadRes['message']]);
            }
            foreach ($uploadRes['uploaded'] as $pName) {
                $photosList[] = $pName;
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

        // ลบไฟล์รูปภาพการแข่งขันในรายงานผล (รองรับ Remote Server)
        $db = \Config\Database::connect();
        $report = $db->table('tb_match_reports')->where('match_id', $id)->get()->getRowArray();
        if ($report && !empty($report['report_photos'])) {
            $photos = json_decode($report['report_photos'], true);
            if (is_array($photos) && !empty($photos)) {
                $this->deletePhotosFromRemoteServer($photos, $match['match_date']);
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
            $rawPhotos = !empty($report['report_photos']) ? json_decode($report['report_photos'], true) : [];
            $photos = [];
            $photoUrls = [];
            
            $match = $this->matchModel->find($matchId);
            $dateFolder = (!empty($match) && !empty($match['match_date'])) ? date('Y-m-d', strtotime($match['match_date'])) : date('Y-m-d');
            $remoteBaseUrl = env('upload.server.baseurl') ?: getenv('upload.server.baseurl');

            if (is_array($rawPhotos)) {
                foreach ($rawPhotos as $photo) {
                    $photos[] = $photo;
                    if (strpos($photo, 'http://') === 0 || strpos($photo, 'https://') === 0) {
                        $photoUrls[] = $photo;
                    } else if ($remoteBaseUrl) {
                        $cleanRemote = rtrim($remoteBaseUrl, '/');
                        if (stripos($cleanRemote, 'SportBase/Matches') !== false) {
                            $base = $cleanRemote;
                        } else {
                            $baseUploads = preg_replace('#(/uploads)(/.*)?$#i', '$1', $cleanRemote);
                            $base = $baseUploads . '/SportBase/Matches';
                        }

                        if (strpos($photo, '/') !== false) {
                            $photoUrls[] = $base . '/' . ltrim($photo, '/');
                        } else {
                            $photoUrls[] = $base . '/' . $dateFolder . '/' . ltrim($photo, '/');
                        }
                    } else {
                        $photoUrls[] = base_url('uploads/matches/' . $photo);
                    }
                }
            }

            $report['photos'] = $photos;
            $report['photo_urls'] = $photoUrls;
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
        if (!is_array($photosList)) {
            $photosList = [];
        }

        $files = $this->request->getFiles();
        if (isset($files['photos'])) {
            $uploadRes = $this->uploadPhotosToRemoteServer($files['photos'], $match['match_date']);
            if (!$uploadRes['success']) {
                return $this->response->setJSON(['success' => false, 'message' => 'อัปโหลดรูปภาพล้มเหลว: ' . $uploadRes['message']]);
            }
            foreach ($uploadRes['uploaded'] as $pName) {
                $photosList[] = $pName;
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
            $deletedPhotos = [];
            $found = false;
            foreach ($photosList as $p) {
                if (trim((string)$p) === $photoName) {
                    $found = true;
                    $deletedPhotos[] = trim((string)$p);
                } else {
                    $newPhotos[] = $p;
                }
            }

            if ($found) {
                $this->deletePhotosFromRemoteServer($deletedPhotos, $match['match_date']);
                $db->table('tb_match_reports')
                    ->where('report_id', $report['report_id'])
                    ->update(['report_photos' => json_encode(array_values($newPhotos))]);

                return $this->response->setJSON(['success' => true, 'message' => 'ลบรูปภาพเรียบร้อยแล้ว']);
            }
        }
        return $this->response->setJSON(['success' => false, 'message' => 'ไม่พบรูปภาพที่ระบุ']);
    }

    /**
     * อัปโหลดไฟล์ไปยัง Remote Upload Server (ใช้ระบบเดียวกับ ConUserFoodReport)
     */
    private function uploadPhotosToRemoteServer($files, $folderDate = '')
    {
        $result = ['success' => true, 'uploaded' => [], 'message' => ''];
        $uploadedNames = [];
        if (empty($files)) {
            return $result;
        }
        if (!is_array($files)) {
            $files = [$files];
        }

        $uploadServerUrl = env('upload.server.url') ?: getenv('upload.server.url');
        $dateFolder = !empty($folderDate) ? date('Y-m-d', strtotime($folderDate)) : date('Y-m-d');

        // Fallback เป็น Local Storage หากไม่ได้ตั้งค่า upload.server.url
        if (!$uploadServerUrl) {
            $uploadDir = FCPATH . 'uploads/matches/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }
            foreach ($files as $file) {
                if ($file && $file->isValid() && !$file->hasMoved()) {
                    $newName = $file->getRandomName();
                    $file->move($uploadDir, $newName);
                    $uploadedNames[] = $newName;
                }
            }
            $result['uploaded'] = $uploadedNames;
            return $result;
        }

        $client = \Config\Services::curlrequest([
            'verify'  => false,
            'timeout' => 120
        ]);
        $remotePath = 'SportBase/Matches/' . $dateFolder;

        foreach ($files as $file) {
            if ($file && $file->isValid() && !$file->hasMoved()) {
                $localTempPath = $file->getTempName();
                $mimeType = $file->getMimeType();
                $originalName = $file->getName();
                $compressedPath = null;

                try {
                    $nameWithoutExt = pathinfo($originalName, PATHINFO_FILENAME);
                    $sanitizedName = preg_replace('/[^\w-]/', '_', $nameWithoutExt);
                    $sanitizedName = trim(preg_replace('/_+/', '_', $sanitizedName), '_');
                    $finalName = ($sanitizedName ?: 'match') . '-' . uniqid() . '.jpg';

                    $originalSize = filesize($localTempPath);
                    log_message('info', "Match photo upload: {$originalName}, original size: " . round($originalSize/1024) . "KB, mime: {$mimeType}");

                    // บีบอัดรูปภาพก่อนอัปโหลด (แก้ Error 413)
                    // ลองบีบครั้งแรก: 1280px, quality 70%
                    $compressedPath = $this->compressImage($localTempPath, $mimeType, 1280, 70);
                    
                    if ($compressedPath) {
                        $compressedSize = filesize($compressedPath);
                        log_message('info', "Compressed to: " . round($compressedSize/1024) . "KB");
                        
                        // ถ้ายังเกิน 1MB ให้บีบอีกครั้งด้วยคุณภาพที่ต่ำลง
                        if ($compressedSize > 1024 * 1024) {
                            @unlink($compressedPath);
                            $compressedPath = $this->compressImage($localTempPath, $mimeType, 1024, 50);
                            if ($compressedPath) {
                                log_message('info', "Re-compressed to: " . round(filesize($compressedPath)/1024) . "KB");
                            }
                        }
                    } else {
                        log_message('warning', "compressImage returned null for {$originalName}. GD library may not be available. Trying original file.");
                    }

                    $uploadFilePath = $compressedPath ?: $localTempPath;
                    $uploadMime = $compressedPath ? 'image/jpeg' : $mimeType;
                    $uploadSize = filesize($uploadFilePath);
                    log_message('info', "Uploading file size: " . round($uploadSize/1024) . "KB to {$uploadServerUrl}");

                    $response = $client->request('POST', $uploadServerUrl, [
                        'headers' => ['X-Auth-Token' => 'Dekpiano2025!!'],
                        'multipart' => [
                            'file'             => new \CURLFile($uploadFilePath, $uploadMime, $finalName),
                            'path'             => $remotePath,
                            'desired_filename' => $finalName,
                        ]
                    ]);

                    if ($response->getStatusCode() === 200) {
                        $body = json_decode($response->getBody());
                        if ($body && isset($body->status) && $body->status === 'success' && isset($body->filename)) {
                            $uploadedNames[] = $body->filename;
                        } else {
                            $msg = 'Remote file upload failed: ' . $response->getBody();
                            log_message('error', $msg);
                            if ($compressedPath && file_exists($compressedPath)) @unlink($compressedPath);
                            $result['success'] = false;
                            $result['message'] = $msg;
                            return $result;
                        }
                    } else {
                        $msg = 'Remote upload server returned status code: ' . $response->getStatusCode() . ' (file size: ' . round($uploadSize/1024) . 'KB)';
                        log_message('error', $msg);
                        if ($compressedPath && file_exists($compressedPath)) @unlink($compressedPath);
                        $result['success'] = false;
                        $result['message'] = $msg;
                        return $result;
                    }

                    // ลบไฟล์ temp ที่บีบอัดแล้ว
                    if ($compressedPath && file_exists($compressedPath)) {
                        @unlink($compressedPath);
                    }
                } catch (\Exception $e) {
                    $msg = 'Exception during remote match photo upload: ' . $e->getMessage();
                    log_message('error', $msg);
                    if ($compressedPath && file_exists($compressedPath)) @unlink($compressedPath);
                    $result['success'] = false;
                    $result['message'] = $msg;
                    return $result;
                }
            }
        }

        $result['uploaded'] = $uploadedNames;
        return $result;
    }

    /**
     * ลบไฟล์จาก Remote Upload Server
     */
    private function deletePhotosFromRemoteServer($photos, $folderDate = '')
    {
        if (empty($photos) || !is_array($photos)) {
            return;
        }

        $uploadServerDeleteUrl = env('upload.server.delete.url') ?: getenv('upload.server.delete.url');
        $dateFolder = !empty($folderDate) ? date('Y-m-d', strtotime($folderDate)) : date('Y-m-d');

        if (!$uploadServerDeleteUrl) {
            foreach ($photos as $photoName) {
                $filePath = FCPATH . 'uploads/matches/' . trim((string)$photoName);
                if (file_exists($filePath)) {
                    @unlink($filePath);
                }
            }
            return;
        }

        $client = \Config\Services::curlrequest([
            'verify'  => false,
            'timeout' => 30
        ]);
        $filesToDelete = [];
        foreach ($photos as $p) {
            $filenameOnly = basename(trim((string)$p));
            if (!empty($filenameOnly)) {
                $filesToDelete[] = $filenameOnly;
            }
        }

        if (empty($filesToDelete)) {
            return;
        }

        try {
            $response = $client->request('POST', $uploadServerDeleteUrl, [
                'headers' => ['X-Auth-Token' => 'Dekpiano2025!!'],
                'json' => [
                    'files' => $filesToDelete,
                    'path'  => 'SportBase/Matches/' . $dateFolder
                ]
            ]);

            if ($response->getStatusCode() !== 200) {
                log_message('error', 'Failed to delete remote match photos. Status: ' . $response->getStatusCode() . ' Body: ' . $response->getBody());
            }
        } catch (\Throwable $e) {
            log_message('error', 'Exception during remote match photo deletion: ' . $e->getMessage());
        }
    }

    /**
     * บีบอัดและลดขนาดรูปภาพ (max 1920px, JPEG quality 75%)
     * เพื่อไม่ให้ไฟล์มีขนาดใหญ่เกินไป แก้ปัญหา Error 413 จาก Remote Server
     * @return string|null คืน path ของไฟล์ temp ที่บีบอัดแล้ว หรือ null ถ้าไม่สามารถบีบอัดได้
     */
    private function compressImage($sourcePath, $mimeType, $maxDimension = 1920, $quality = 75)
    {
        try {
            // สร้าง image resource จากไฟล์ต้นฉบับ
            switch (strtolower($mimeType)) {
                case 'image/jpeg':
                case 'image/jpg':
                    $sourceImage = @imagecreatefromjpeg($sourcePath);
                    break;
                case 'image/png':
                    $sourceImage = @imagecreatefrompng($sourcePath);
                    break;
                case 'image/webp':
                    if (function_exists('imagecreatefromwebp')) {
                        $sourceImage = @imagecreatefromwebp($sourcePath);
                    } else {
                        return null;
                    }
                    break;
                case 'image/gif':
                    $sourceImage = @imagecreatefromgif($sourcePath);
                    break;
                default:
                    return null; // ไม่รองรับ format นี้
            }

            if (!$sourceImage) {
                return null;
            }

            $origWidth = imagesx($sourceImage);
            $origHeight = imagesy($sourceImage);

            // คำนวณขนาดใหม่ โดยรักษาสัดส่วนเดิม
            $newWidth = $origWidth;
            $newHeight = $origHeight;

            if ($origWidth > $maxDimension || $origHeight > $maxDimension) {
                if ($origWidth >= $origHeight) {
                    $newWidth = $maxDimension;
                    $newHeight = intval($origHeight * ($maxDimension / $origWidth));
                } else {
                    $newHeight = $maxDimension;
                    $newWidth = intval($origWidth * ($maxDimension / $origHeight));
                }
            }

            // สร้าง canvas ใหม่และ resize
            $resizedImage = imagecreatetruecolor($newWidth, $newHeight);

            // จัดการ transparency สำหรับ PNG
            if ($mimeType === 'image/png') {
                imagealphablending($resizedImage, false);
                imagesavealpha($resizedImage, true);
                $transparent = imagecolorallocatealpha($resizedImage, 255, 255, 255, 127);
                imagefilledrectangle($resizedImage, 0, 0, $newWidth, $newHeight, $transparent);
            } else {
                // พื้นขาวสำหรับ JPEG
                $white = imagecolorallocate($resizedImage, 255, 255, 255);
                imagefilledrectangle($resizedImage, 0, 0, $newWidth, $newHeight, $white);
            }

            imagecopyresampled($resizedImage, $sourceImage, 0, 0, 0, 0, $newWidth, $newHeight, $origWidth, $origHeight);

            // บันทึกเป็น JPEG ลงไฟล์ temp
            $tempPath = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'match_compressed_' . uniqid() . '.jpg';
            $saved = imagejpeg($resizedImage, $tempPath, $quality);

            imagedestroy($sourceImage);
            imagedestroy($resizedImage);

            if ($saved && file_exists($tempPath)) {
                $compressedSize = filesize($tempPath);
                $originalSize = filesize($sourcePath);
                log_message('info', "Image compressed: {$origWidth}x{$origHeight} -> {$newWidth}x{$newHeight}, Size: " . round($originalSize/1024) . "KB -> " . round($compressedSize/1024) . "KB");
                return $tempPath;
            }

            return null;
        } catch (\Throwable $e) {
            log_message('error', 'Image compression failed: ' . $e->getMessage());
            return null;
        }
    }
}
