<?php

namespace Config;

// Create a new instance of our RouteCollection class.
$routes = Services::routes();

/*
 * --------------------------------------------------------------------
 * Router Setup
 * --------------------------------------------------------------------
 */
$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
// The Auto Routing (Legacy) is very dangerous. It is easy to create vulnerable apps
// where controller filters or CSRF protection are bypassed.
// If you don't want to define all routes, please use the Auto Routing (Improved).
// Set `$autoRoutesImproved` to true in `app/Config/Feature.php` and set the following to true.
$routes->setAutoRoute(false);

/*
 * --------------------------------------------------------------------
 * Route Definitions
 * --------------------------------------------------------------------
 */

// We get a performance increase by specifying the default
// route since we don't have to scan directories.
$routes->get('/', 'ConUserHome::index');
//User งานจองห้อง

$routes->match(['get', 'post'], '/LoginOfficerSportBase', 'ConLogin::LoginOfficerSportBase');
$routes->get('/LogoutOfficerSportBase', 'ConLogin::LogoutOfficerSportBase');
$routes->get('/LoginOfficerBudgetPlan', 'ConLogin::LoginOfficerBudgetPlan');
$routes->get('/LogoutOfficerBudgetPlan', 'ConLogin::LogoutOfficerBudgetPlan');

//จัดซื้อจัดจ้าง
$routes->get('User/Procurement/Process', 'ConUserProcurement::ProcurementProcess');

//ใบสำคัญรับเงินตอบแทนค่าวิทยากร
$routes->get('User/Procurement/MoneyReceipt', 'ConUserMoneyReceipt::MoneyReceiptForm');

//Admin
$routes->get('Admin/Home', 'ConAdminHome::index');

$routes->get('Admin/LocationRoom/LocationRoomMain', 'ConAdminLocationRoom::LocationRoomMain');

$routes->get('Admin/Rloes/Setting', 'ConAdminRoles::index');
$routes->get('Admin/Roles/Setting', 'ConAdminRoles::index');
$routes->post('Admin/Roles/Add', 'ConAdminRoles::add');
$routes->get('Admin/Roles/Delete/(:num)', 'ConAdminRoles::delete/$1');

// Team/Generation Management (รุ่นนักกีฬา)
$routes->get('Admin/Team', 'ConAdminTeam::index');
$routes->get('Admin/Team/Detail/(:num)', 'ConAdminTeam::detail/$1');
$routes->post('Admin/Team/Create', 'ConAdminTeam::create');
$routes->get('Admin/Team/Delete/(:num)', 'ConAdminTeam::delete/$1');
$routes->get('Admin/Team/SearchStudents', 'ConAdminTeam::searchStudents');
$routes->post('Admin/Team/AddAthlete', 'ConAdminTeam::addAthlete');
$routes->post('Admin/Team/UpdateAthleteImage', 'ConAdminTeam::updateAthleteImage');
$routes->get('Admin/Team/RemoveAthlete/(:num)/(:num)', 'ConAdminTeam::removeAthlete/$1/$2');
$routes->post('Admin/Team/AddCoach', 'ConAdminTeam::addCoach');
$routes->get('Admin/Team/RemoveCoach/(:num)/(:num)', 'ConAdminTeam::removeCoach/$1/$2');

// Attendance Management (เช็คชื่อนักกีฬา)
$routes->get('Admin/Attendance', 'ConAdminAttendance::index');
$routes->get('Admin/Attendance/Team/(:num)', 'ConAdminAttendance::team/$1');
$routes->post('Admin/Attendance/Save', 'ConAdminAttendance::save');
$routes->get('Admin/Attendance/History', 'ConAdminAttendance::history');
$routes->get('Admin/Attendance/History/(:num)', 'ConAdminAttendance::history/$1');

// Match Schedule Management (ตารางการแข่งขัน)
$routes->get('Admin/Match', 'ConAdminMatch::index');
$routes->post('Admin/Match/Save', 'ConAdminMatch::save');
$routes->get('Admin/Match/Delete/(:num)', 'ConAdminMatch::delete/$1');

// Public Match Schedule
$routes->get('User/Match', 'UserMatch::index');
$routes->get('User/Athlete', 'UserAthlete::index');
$routes->get('User/Attendance', 'UserAttendance::index');

//Admin Person
$routes->get('Admin/WorkPerson/BudgetPlan', 'ConAdminWorkPerson::index');



/*
 * --------------------------------------------------------------------
 * Additional Routing
 * --------------------------------------------------------------------
 *
 * There will often be times that you need additional routing and you
 * need it to be able to override any defaults in this file. Environment
 * based routes is one such time. require() additional route files here
 * to make that happen.
 *
 * You will have access to the $routes object within that file without
 * needing to reload it.
 */
if (is_file(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php')) {
    require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}
