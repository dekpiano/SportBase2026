<?php

namespace App\Controllers;

class ConLogin extends BaseController
{
        //$path = dirname(dirname(dirname(dirname((dirname(__FILE__))))));
	    //require $path . '/skj.ac.th/public_html/librarie_skj/google_sheet/vendor/autoload.php';

    private $googleClient = null;
    private $GoogleButton = "";
    private $ReturnUrl = "";
    function __construct(){
        // ตรวจสอบ OS ว่าเป็น Windows หรือไม่ (Windows ใช้ \ เป็นตัวคั่น path, Linux ใช้ /)
        if (DIRECTORY_SEPARATOR === '\\') {
            // โค้ดกำลังรันบนเครื่องคอมพิวเตอร์ของคุณ (Windows / Local XAMPP)
            defined('SHARED_LIB_PATH') OR define('SHARED_LIB_PATH', 'D:\xampp\librarie_skj');
        } else {
            // โค้ดกำลังรันบน Linux (Docker หรือ Production Server)
            defined('SHARED_LIB_PATH') OR define('SHARED_LIB_PATH', '/home/skjacth/domains/skj.ac.th/public_html/librarie_skj');
        }
		require SHARED_LIB_PATH . '/google_sheet/vendor/autoload.php';

        $redirect_uri = base_url('LoginOfficerSportBase');
        
        $this->googleClient = new \Google_Client();
        $this->googleClient->setClientId(env('GOOGLE_CLIENT_ID', ''));
		$this->googleClient->setClientSecret(env('GOOGLE_CLIENT_SECRET', ''));
        $this->googleClient->setRedirectUri($redirect_uri);
        $this->googleClient->addScope('email');
        $this->googleClient->addScope('profile');

        $this->GoogleButton = '<a href="'.$this->googleClient->createAuthUrl().'" class="btn btn-primary me-3 w-auto"><i class="tf-icons bx bxl-google-plus"></i> Login by Google </a>';
    }

    public function DataMain(){
        $data['full_url'] = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http') . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
        $data['GoogleButton'] = $this->GoogleButton;
        $data['uri'] = service('uri'); 
        return $data;
    }

       
    public function LoginOfficerSportBase(){
      
        $data = $this->DataMain();
        $data['title']="หน้าแรก";
        $data['description']="เข้าสู่ระบบ";
        $data['UrlMenuMain'] = 'LoginOfficerSportBase';
        $data['UrlMenuSub'] = '';
        

        $session = session();
        $DB_Default = \Config\Database::connect();
        $DB_Personnel = \Config\Database::connect('personnel');
        $DBrloes = $DB_Default->table('tb_admin_rloes');
        $DBPers = $DB_Personnel->table('tb_personnel');     

        //print_r($this->request->getVar("code"));exit();
        
        if($this->request->getVar("return_to") == ""){

        }else{
            session()->set('Return',$this->request->getVar("return_to"));
        }
        
        
            if($this->request->getVar("code")){

            $token = $this->googleClient->fetchAccessTokenWithAuthCode($this->request->getVar("code"));
            
                if(!isset($token['error'])){
                
                    $this->googleClient->setAccessToken($token['access_token']);           
                    session()->set('AccessToken', $token['access_token']);
                

                    $googleService = new \Google_Service_Oauth2($this->googleClient);  
                    $data = $googleService->userinfo->get();            
                           
                   

                $User = $DBPers->where('pers_username', $data['email'])->get()->getRowArray();
                
                if($User){
                    // ดึงสิทธิ์จากตารางใหม่ skjacth_sportbase.tb_admin_rloes
                    $UserRoles = $DBrloes->select('role_type, role_position')
                        ->where('pers_id', $User['pers_id'])
                        ->get()->getResultArray();

                    if (empty($UserRoles)) {
                        $session->setFlashdata('Error', 'Email นี้ไม่มีสิทธิ์เข้าใช้งานระบบ! กรุณาติดต่อผู้ดูแลระบบเพื่อกำหนดสิทธิ์');
                        return redirect()->back();
                    }

                    $UserData = array('login_oauth_uid' => $data['id'],
                                        'updated_at' => date('Y-m-d H:i:s'));
                    $DBPers->where('pers_username', $data['email'])->update($UserData);

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
                            'username'  => $User['pers_prefix'].$User['pers_firstname'].' '.$User['pers_lastname'],
                            'id'     => $User['pers_id'],
                            'pers_img' => $User['pers_img'],
                            'logged_in' => true,
                            'rloes' => implode(',', $rolesList),
                            'status' => $status
                        ];                
                        $session->set($newdata);  
                        
                        return redirect()->to(base_url('Admin/Home'));
                          
                } else{
                    $session->setFlashdata('Error', 'Email นี้ไม่พบในฐานข้อมูลบุคลากร! กรุณาติดต่อผู้ดูแลระบบ');
                    return redirect()->back();
                }        

                }else{
                    session()->set('Error', "Something went Wrong!");     
                    
                }
        
            }
        
        return view('User/UserLeyout/UserHeader',$data)
        .view('User/UserLeyout/UserMenuLeft')
        .view('Login/LoginGoogle')
        .view('User/UserLeyout/UserFooter');
          
    }

    public function LogoutOfficerSportBase(){
        $session = session();
        $session->destroy();
        return redirect()->to(base_url());
    }
}
