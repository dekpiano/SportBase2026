<?php

namespace App\Controllers;

use App\Models\TeamModel;
use App\Models\StudentModel;

class ConAdminTeam extends BaseController
{
    public function __construct()
    {
        $session = session();
        if (!$session->get('username')) {
            header("Location:" . base_url());
            exit();
        }

        // จำกัดสิทธิ์เฉพาะผู้ดูแลระบบ และ โค้ช เท่านั้น
        $status = $session->get('status');
        if (!in_array($status, ["admin", "super_admin", "coach"])) {
            echo "คุณไม่มีสิทธิ์เข้าถึงหน้านี้";
            exit();
        }
    }

    /**
     * ตรวจสอบสิทธิ์การเข้าถึงทีม (เฉพาะโค้ช)
     */
    private function checkPermission($teamId)
    {
        $session = session();
        if (in_array($session->get('status'), ["admin", "super_admin"])) {
            return true;
        }

        $teamModel = new TeamModel();
        if (!$teamModel->isCoachOfTeam($teamId, $session->get('id'))) {
            // โค้ชที่ไม่ได้เป็นผู้ดูแลทีมนี้ ไม่มีสิทธิ์
            return false;
        }
        return true;
    }

    public function DataMain()
    {
        $data['full_url'] = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http') . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
        $data['uri'] = service('uri');
        return $data;
    }

    /**
     * หน้ารายการรุ่น/ทีม
     */
    public function index()
    {
        $data = $this->DataMain();
        $data['title'] = "จัดการรุ่นนักกีฬา";

        $teamModel = new TeamModel();
        
        $coachId = null;
        if (session()->get('status') == "coach") {
            $coachId = session()->get('id');
        }
        
        $data['teams'] = $teamModel->getTeamsWithCounts($coachId);

        // ดึงรายชื่อบุคลากรสำหรับเลือกโค้ช
        $dbPersonnel = \Config\Database::connect('personnel');
        $data['personnel'] = $dbPersonnel->table('tb_personnel')
            ->select('pers_id, pers_prefix, pers_firstname, pers_lastname')
            ->where('pers_status', 'กำลังใช้งาน')
            ->orderBy('pers_firstname', 'ASC')
            ->get()->getResultArray();

        return view('Admin/AdminTeam/AdminTeamMain', $data);
    }

    /**
     * หน้ารายละเอียดรุ่น
     */
    public function detail($teamId)
    {
        if (!$this->checkPermission($teamId)) {
            return redirect()->to(base_url('Admin/Team'))->with('error', 'คุณไม่มีสิทธิ์ดูข้อมูลรุ่นนี้');
        }

        $data = $this->DataMain();
        $data['title'] = "รายละเอียดรุ่นนักกีฬา";

        $teamModel = new TeamModel();
        $data['team'] = $teamModel->find($teamId);
        $data['athletes'] = $teamModel->getTeamAthletes($teamId);
        $data['teamCoaches'] = $teamModel->getTeamCoaches($teamId);

        // ดึงรายชื่อบุคลากรสำหรับเลือกโค้ช
        $dbPersonnel = \Config\Database::connect('personnel');
        $data['coaches'] = $dbPersonnel->table('tb_personnel')
            ->select('pers_id, pers_prefix, pers_firstname, pers_lastname')
            ->where('pers_status', 'กำลังใช้งาน')
            ->orderBy('pers_firstname', 'ASC')
            ->get()->getResultArray();

        return view('Admin/AdminTeam/AdminTeamDetail', $data);
    }

    /**
     * สร้างรุ่นใหม่
     */
    public function create()
    {
        // แอดมินสร้างได้ทุกคน แต่ถ้าโค้ชสร้าง จะต้องเพิ่มชื่อตัวเองเป็นโค้ชในรุ่นนี้อัตโนมัติ
        $teamModel = new TeamModel();
        $data = [
            'team_name' => $this->request->getPost('team_name'),
            'team_sport_type' => $this->request->getPost('team_sport_type'),
            'team_year' => $this->request->getPost('team_year'),
            'team_status' => 'ใช้งาน',
            'created_at' => date('Y-m-d H:i:s'),
            'created_by' => session()->get('id')
        ];

        $teamId = $teamModel->insert($data);
        if ($teamId) {
            $coachIds = $this->request->getPost('coach_ids') ?: [];
            
            // ถ้าเป็นโค้ช ให้เพิ่มชื่อตัวเองลงไปในรายการด้วยถ้ายังไม่อยู่ในรายการที่เลือกมา
            if (session()->get('status') == "coach") {
                if (!in_array(session()->get('id'), $coachIds)) {
                    $coachIds[] = session()->get('id');
                }
            }

            // เพิ่มโค้ชทุกคนเข้ารุ่น
            foreach ($coachIds as $coachId) {
                if (!empty($coachId)) {
                    $teamModel->addCoach($teamId, $coachId);
                }
            }
            
            return $this->response->setJSON(['success' => true, 'message' => 'สร้างรุ่นนักกีฬาและเพิ่มผู้ดูแลเรียบร้อยแล้ว']);
        } else {
            return $this->response->setJSON(['success' => false, 'message' => 'ไม่สามารถสร้างรุ่นนักกีฬาได้']);
        }
    }

    /**
     * ลบรุ่น
     */
    public function delete($teamId)
    {
        if (!$this->checkPermission($teamId)) {
            return redirect()->back()->with('error', 'คุณไม่มีสิทธิ์ลบรุ่นนี้');
        }
        
        $teamModel = new TeamModel();
        // ลบข้อมูลที่เกี่ยวข้องก่อน
        $this->db = \Config\Database::connect();
        $this->db->table('tb_team_athletes')->where('team_id', $teamId)->delete();
        $this->db->table('tb_team_coaches')->where('team_id', $teamId)->delete();
        
        if ($teamModel->delete($teamId)) {
            return redirect()->to(base_url('Admin/Team'))->with('success', 'ลบรุ่นเรียบร้อยแล้ว');
        } else {
            return redirect()->back()->with('error', 'ไม่สามารถลบรุ่นได้');
        }
    }

    /**
     * ค้นหานักเรียน (AJAX)
     */
    public function searchStudents()
    {
        $search = $this->request->getVar('term');
        $teamId = $this->request->getVar('team_id');

        if (!$this->checkPermission($teamId)) {
            return $this->response->setJSON([]);
        }
        
        $db = \Config\Database::connect('academic');
        $builder = $db->table('tb_students');
        $builder->select('StudentID, StudentCode, StudentPrefix, StudentFirstName, StudentLastName, StudentClass');
        // ไม่รวมนักเรียนที่อยู่ในรุ่นนี้แล้ว และต้องสถานะพฤติกรรมเป็น 'ปกติ'
        $builder->where('StudentStatus', '1/ปกติ');
        $builder->where("StudentID NOT IN (SELECT StudentID FROM skjacth_sportbase.tb_team_athletes WHERE team_id = " . (int)$teamId . ")");
        
        if ($search) {
            $builder->groupStart()
                ->like('StudentFirstName', $search)
                ->orLike('StudentLastName', $search)
                ->orLike('StudentCode', $search)
                ->groupEnd();
        }

        $students = $builder->limit(20)->get()->getResultArray();

        $results = [];
        foreach ($students as $student) {
            $results[] = [
                'id' => $student['StudentID'],
                'text' => "[{$student['StudentCode']}] {$student['StudentPrefix']}{$student['StudentFirstName']} {$student['StudentLastName']} ({$student['StudentClass']})"
            ];
        }

        return $this->response->setJSON($results);
    }

    /**
     * เพิ่มนักกีฬาเข้ารุ่น
     */
    public function addAthlete()
    {
        $teamId = $this->request->getPost('team_id');

        if (!$this->checkPermission($teamId)) {
            return $this->response->setJSON(['success' => false, 'message' => 'คุณไม่มีสิทธิ์จัดการรุ่นนี้']);
        }

        $teamModel = new TeamModel();
        $studentId = $this->request->getPost('StudentID');
        
        // Handle cropped image (base64)
        $imageName = null;
        $croppedImage = $this->request->getPost('cropped_image');
        
        if ($croppedImage && strpos($croppedImage, 'data:image') === 0) {
            // Create directory if not exists
            $uploadPath = FCPATH . 'uploads/athletes/';
            if (!is_dir($uploadPath)) {
                mkdir($uploadPath, 0755, true);
            }
            
            // Decode base64 image
            $imageData = explode(',', $croppedImage);
            $imageDecoded = base64_decode($imageData[1]);
            
            // Generate unique filename
            $imageName = $studentId . '_' . time() . '.jpg';
            
            // Save file
            file_put_contents($uploadPath . $imageName, $imageDecoded);
        }

        if ($teamModel->addAthlete($teamId, $studentId, $imageName)) {
            return $this->response->setJSON(['success' => true, 'message' => 'เพิ่มนักกีฬาเรียบร้อยแล้ว']);
        } else {
            return $this->response->setJSON(['success' => false, 'message' => 'ไม่สามารถเพิ่มนักกีฬาได้']);
        }
    }

    /**
     * ลบนักกีฬาออกจากรุ่น
     */
    public function removeAthlete($id, $teamId)
    {
        if (!$this->checkPermission($teamId)) {
            return redirect()->back()->with('error', 'คุณไม่มีสิทธิ์จัดการรุ่นนี้');
        }

        $teamModel = new TeamModel();
        
        // ดึงข้อมูลนักกีฬาก่อนลบ เพื่อลบรูปภาพ
        $db = \Config\Database::connect();
        $athlete = $db->table('tb_team_athletes')->where('id', $id)->get()->getRowArray();
        
        if ($athlete && !empty($athlete['athlete_image'])) {
            // ลบไฟล์รูปภาพ
            $imagePath = FCPATH . 'uploads/athletes/' . $athlete['athlete_image'];
            if (file_exists($imagePath)) {
                unlink($imagePath);
            }
        }
        
        if ($teamModel->removeAthlete($id)) {
            return redirect()->to(base_url('Admin/Team/Detail/' . $teamId))->with('success', 'ลบนักกีฬาออกจากรุ่นแล้ว');
        } else {
            return redirect()->back()->with('error', 'ไม่สามารถลบนักกีฬาได้');
        }
    }

    /**
     * เพิ่มโค้ชเข้ารุ่น
     */
    public function addCoach()
    {
        $teamId = $this->request->getPost('team_id');

        if (!$this->checkPermission($teamId)) {
            return redirect()->back()->with('error', 'คุณไม่มีสิทธิ์จัดการรุ่นนี้');
        }

        $teamModel = new TeamModel();
        $coachId = $this->request->getPost('coach_id');

        if ($teamModel->addCoach($teamId, $coachId)) {
            return $this->response->setJSON(['success' => true, 'message' => 'เพิ่มครูผู้ฝึกสอนเรียบร้อยแล้ว']);
        } else {
            return $this->response->setJSON(['success' => false, 'message' => 'ไม่สามารถเพิ่มครูผู้ฝึกสอนได้']);
        }
    }

    /**
     * ลบโค้ชออกจากรุ่น
     */
    public function removeCoach($id, $teamId)
    {
        if (!$this->checkPermission($teamId)) {
            return redirect()->back()->with('error', 'คุณไม่มีสิทธิ์จัดการรุ่นนี้');
        }

        $teamModel = new TeamModel();
        if ($teamModel->removeCoach($id)) {
            return redirect()->to(base_url('Admin/Team/Detail/' . $teamId))->with('success', 'ลบครูผู้ฝึกสอนออกจากรุ่นแล้ว');
        } else {
            return redirect()->back()->with('error', 'ไม่สามารถลบครูผู้ฝึกสอนได้');
        }
    }

    /**
     * อัปเดตรูปภาพนักกีฬา (AJAX)
     */
    public function updateAthleteImage()
    {
        $id = $this->request->getPost('athlete_id');
        $teamId = $this->request->getPost('team_id');
        $studentId = $this->request->getPost('StudentID');

        if (!$this->checkPermission($teamId)) {
            return $this->response->setJSON(['success' => false, 'message' => 'คุณไม่มีสิทธิ์จัดการรุ่นนี้']);
        }

        $teamModel = new TeamModel();
        
        // ดึงข้อมูลเดิมเพื่อลบรูปเก่า
        $db = \Config\Database::connect();
        $athlete = $db->table('tb_team_athletes')->where('id', $id)->get()->getRowArray();
        
        $croppedImage = $this->request->getPost('cropped_image');
        if ($croppedImage && strpos($croppedImage, 'data:image') === 0) {
            $uploadPath = FCPATH . 'uploads/athletes/';
            
            // ลบรูปเก่าถ้ามี
            if ($athlete && !empty($athlete['athlete_image'])) {
                $oldPath = $uploadPath . $athlete['athlete_image'];
                if (file_exists($oldPath)) {
                    unlink($oldPath);
                }
            }

            // Decode และบันทึกรูปใหม่
            $imageData = explode(',', $croppedImage);
            $imageDecoded = base64_decode($imageData[1]);
            $imageName = $studentId . '_' . time() . '.jpg';
            
            if (file_put_contents($uploadPath . $imageName, $imageDecoded)) {
                if ($teamModel->updateAthleteImage($id, $imageName)) {
                    return $this->response->setJSON(['success' => true, 'message' => 'อัปเดตรูปภาพเรียบร้อยแล้ว']);
                }
            }
        }

        return $this->response->setJSON(['success' => false, 'message' => 'ไม่สามารถอัปเดตรูปภาพได้']);
    }
}
