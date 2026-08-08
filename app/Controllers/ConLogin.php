<?php

namespace App\Controllers;

class ConLogin extends BaseController
{
    private $GoogleButton = "";

    function __construct()
    {
        // สร้างปุ่ม Google Login แบบใหม่ (Google Identity Services)
        // ปุ่มจริงจะถูกเรนเดอร์ใน View ผ่าน JavaScript
    }

    public function DataMain()
    {
        $data['full_url'] = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http') . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
        $data['uri'] = service('uri')->setSilent(true);
        return $data;
    }

    public function LoginOfficerSportBase()
    {
        $session = session();
        if ($session->get('logged_in') || $session->get('username')) {
            return redirect()->to(base_url('Admin/Home'));
        }

        $data = $this->DataMain();
        $data['title'] = "หน้าแรก";
        $data['description'] = "เข้าสู่ระบบ";
        $data['UrlMenuMain'] = 'LoginOfficerSportBase';
        $data['UrlMenuSub'] = '';

        $credential = $this->request->getPost('credential');

        if ($credential) {
            // ยืนยันตัวตนกับ Google ผ่าน API โดยตรง (Lightweight)
            $client = \Config\Services::curlrequest();
            try {
                $response = $client->get("https://oauth2.googleapis.com/tokeninfo?id_token=" . $credential);
                $payload = json_decode($response->getBody(), true);
            } catch (\Exception $e) {
                $session->setFlashdata('Error', 'การเชื่อมต่อกับ Google ล้มเหลว');
                return redirect()->to(base_url('LoginOfficerSportBase'));
            }

            if (!$payload || isset($payload['error'])) {
                $session->setFlashdata('Error', 'การยืนยันตัวตนกับ Google ล้มเหลว');
                return redirect()->to(base_url('LoginOfficerSportBase'));
            }

            // ตรวจสอบ Client ID จาก .env (รองรับทั้งแบบเก่าและแบบใหม่)
            $client_id = env('google.clientId') ?: env('GOOGLE_CLIENT_ID');
            $client_id = trim($client_id, "'\""); // ลบเครื่องหมายคำพูดถ้ามี
            if ($client_id && $payload['aud'] !== $client_id) {
                $session->setFlashdata('Error', 'Client ID ไม่ถูกต้อง');
                return redirect()->to(base_url('LoginOfficerSportBase'));
            }

            $email = $payload['email'];
            $google_id = $payload['sub'];

            $DB_Default = \Config\Database::connect();
            $DB_Personnel = \Config\Database::connect('personnel');
            $DBrloes = $DB_Default->table('tb_admin_rloes');
            $DBPers = $DB_Personnel->table('tb_personnel');

            // ค้นหาผู้ใช้จาก email
            $User = $DBPers->where('pers_username', $email)->get()->getRowArray();

            if ($User) {
                // ดึงสิทธิ์จากตาราง skjacth_sportbase.tb_admin_rloes
                $UserRoles = $DBrloes->select('role_type, role_position')
                    ->where('pers_id', $User['pers_id'])
                    ->get()->getResultArray();

                if (empty($UserRoles)) {
                    $session->setFlashdata('Error', 'Email นี้ไม่มีสิทธิ์เข้าใช้งานระบบ! กรุณาติดต่อผู้ดูแลระบบเพื่อกำหนดสิทธิ์');
                    return redirect()->to(base_url('LoginOfficerSportBase'));
                }

                // อัพเดทข้อมูล OAuth UID
                $UserData = [
                    'login_oauth_uid' => $google_id,
                    'updated_at'      => date('Y-m-d H:i:s')
                ];
                $DBPers->where('pers_username', $email)->update($UserData);

                // หาสถานะสูงสุด: super_admin > admin > manager > coach > member
                $status = 'member';
                $rolesList = [];
                foreach ($UserRoles as $r) {
                    $rolesList[] = $r['role_position'];
                    if ($r['role_type'] == 'super_admin') $status = 'super_admin';
                    elseif ($r['role_type'] == 'admin' && $status != 'super_admin') $status = 'admin';
                    elseif ($r['role_type'] == 'manager' && !in_array($status, ['super_admin', 'admin'])) $status = 'manager';
                    elseif ($r['role_type'] == 'coach' && !in_array($status, ['super_admin', 'admin', 'manager'])) $status = 'coach';
                }

                $newdata = [
                    'username'  => $User['pers_prefix'] . $User['pers_firstname'] . ' ' . $User['pers_lastname'],
                    'id'        => $User['pers_id'],
                    'pers_img'  => $User['pers_img'],
                    'logged_in' => true,
                    'rloes'     => implode(',', $rolesList),
                    'status'    => $status
                ];
                $session->set($newdata);

                return redirect()->to(base_url('Admin/Home'));
            } else {
                $session->setFlashdata('Error', "Email ($email) นี้ไม่พบในฐานข้อมูลบุคลากร! กรุณาติดต่อผู้ดูแลระบบ");
                return redirect()->to(base_url('LoginOfficerSportBase'));
            }
        }

        return view('User/UserLeyout/UserHeader', $data)
            . view('User/UserLeyout/UserMenuLeft')
            . view('Login/LoginGoogle')
            . view('User/UserLeyout/UserFooter');
    }

    public function LogoutOfficerSportBase()
    {
        $session = session();
        $session->destroy();
        return redirect()->to(base_url());
    }
}
