<?php

namespace App\Controllers;

class ConUserProcurement extends BaseController
{  

    function __construct(){
       
    }


    public function DataMain(){
        $data['full_url'] = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http') . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
   
        $data['uri'] = service('uri'); 
        return $data;
    }

    public function ProcurementProcess()
    {
        $session = session();
        $database = \Config\Database::connect('personnel');
        $builder = $database->table('tb_personnel');

        $data = $this->DataMain();
        $data['title']="ขั้นตอนการจัดซื้อ / จัดจ้าง";
        $data['description']="ระบบบริหารงานงบประมาณและแผน";
        $data['UrlMenuMain'] = 'Procurement';
        $data['UrlMenuSub'] = 'Process';

        //$data['DictationAll'] = $builder->countAll();
       
       

        return view('User/UserLeyout/UserHeader',$data)
                .view('User/UserLeyout/UserMenuLeft')
                .view('User/UserProcurement/ProcurementProcess')
                .view('User/UserLeyout/UserFooter');
    }


  

    
}
