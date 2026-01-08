<?php

namespace App\Controllers;

class ConUserHome extends BaseController
{  

    function __construct(){
       
    }


    public function DataMain(){
        $data['full_url'] = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http') . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
   
        $data['uri'] = service('uri'); 
        return $data;
    }

    public function index()
    {
        $session = session();
        $data = $this->DataMain();
        $data['title']="หน้าแรก";
        $data['description']="ระบบจัดการข้อมูลนักกีฬา SKJ SportBase โรงเรียนสวนกุหลาบวิทยาลัย (จิรประวัติ) นครสวรรค์";
        $data['UrlMenuMain'] = 'Main';
        $data['UrlMenuSub'] = '';

        // ดึงข้อมูลการแข่งขันที่จะเกิดขึ้นสำหรับหน้าแรก
        $matchModel = new \App\Models\MatchModel();
        $data['upcomingMatches'] = $matchModel->getUpcomingMatches(5);

        return view('User/UserLeyout/UserHeader',$data)
                .view('User/UserLeyout/UserMenuLeft', $data)
                .view('User/UserHome/UserPageHome', $data)
                .view('User/UserLeyout/UserFooter', $data);
    }


  

    
}
