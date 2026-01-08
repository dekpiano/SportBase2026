# SportBase 2026 - บันทึกการพัฒนา

## วันที่: 6 มกราคม 2569 (2026-01-06)

---

### สร้างระบบเช็คชื่อนักกีฬา (Attendance System)

**โครงสร้างฐานข้อมูล:**

| ตาราง           | คำอธิบาย                                                                                                   |
| --------------- | ---------------------------------------------------------------------------------------------------------- |
| `tb_attendance` | บันทึกการลา (att_id, team_id, StudentID, att_date, att_status, att_note, att_time, checked_by, created_at) |

**สถานะการลา (เก็บเฉพาะคนที่ไม่อยู่):**

| สถานะ         | รหัส       | สี     | ไอคอน           |
| ------------- | ---------- | ------ | --------------- |
| ลาป่วย        | `sick`     | เหลือง | bx-plus-medical |
| ลากิจธุระ     | `personal` | ฟ้า    | bx-briefcase    |
| ลากิจกลับบ้าน | `home`     | แดง    | bx-home         |

_หมายเหตุ: ถ้าไม่มี record = อยู่ปกติ (สีเขียว)_

**ไฟล์ที่สร้างใหม่:**

- `app/Models/AttendanceModel.php` - Model จัดการการลา
- `app/Controllers/ConAdminAttendance.php` - Controller จัดการเช็คชื่อ
- `app/Views/Admin/AdminAttendance/AdminAttendanceMain.php` - หน้าหลักเช็คชื่อ
- `app/Views/Admin/AdminAttendance/AdminAttendanceTeam.php` - หน้าเช็คชื่อตามทีม
- `app/Views/Admin/AdminAttendance/AdminAttendanceHistory.php` - หน้าประวัติการลา
- `database/tb_attendance.sql` - SQL สร้างตาราง

**ฟีเจอร์:**

- ✅ เลือกทีม/รุ่นเพื่อเช็คชื่อ
- ✅ เช็คชื่อรายวัน (เลือกวันที่ได้)
- ✅ บันทึกอัตโนมัติด้วย AJAX
- ✅ สถานะ 4 แบบ: อยู่, ลาป่วย, ลากิจธุระ, ลากิจกลับบ้าน
- ✅ ใส่หมายเหตุได้
- ✅ ดูประวัติการลา (กรองตามทีม/ช่วงเวลา)
- ✅ แสดงสรุปจำนวนคนลาวันนี้

---

## วันที่: 5 มกราคม 2569 (2026-01-05)

---

## สรุปการพัฒนาวันนี้

### 1. แก้ไขปัญหาการ Login

- แก้ไข 404 Error สำหรับ route `LoginOfficerPersonnel`
- เปลี่ยนชื่อ route เป็น `LoginOfficerSportBase` และ `LogoutOfficerSportBase`
- แก้ไข MySQL `only_full_group_by` error โดยเพิ่ม `groupBy()` ใน query

### 2. ปรับปรุงการเชื่อมต่อฐานข้อมูล

- ตั้งค่า Database.php ให้ใช้ `hostname: db` สำหรับ Docker
- เปลี่ยน charset เป็น `utf8mb4` และ collation เป็น `utf8mb4_unicode_ci` ทุก connection group
- แก้ไขปัญหา "Illegal mix of collations"

### 3. สร้างระบบจัดการรุ่นนักกีฬา (Team System)

**โครงสร้างฐานข้อมูล (skjacth_sportbase):**

| ตาราง              | คำอธิบาย                                                                                 |
| ------------------ | ---------------------------------------------------------------------------------------- |
| `tb_teams`         | ข้อมูลรุ่น/ทีม (team_id, team_name, team_sport_type, team_year, team_status, created_at) |
| `tb_team_athletes` | ความสัมพันธ์รุ่น-นักกีฬา (id, team_id, StudentID, created_at)                            |
| `tb_team_coaches`  | ความสัมพันธ์รุ่น-โค้ช (id, team_id, coach_id, created_at)                                |

**ไฟล์ที่สร้างใหม่:**

- `app/Models/TeamModel.php` - Model จัดการรุ่น/ทีม
- `app/Controllers/ConAdminTeam.php` - Controller จัดการ CRUD รุ่น/นักกีฬา/โค้ช
- `app/Views/Admin/AdminTeam/AdminTeamMain.php` - หน้ารายการรุ่น
- `app/Views/Admin/AdminTeam/AdminTeamDetail.php` - หน้ารายละเอียดรุ่น (จัดการนักกีฬา/โค้ช)

**ฟีเจอร์:**

- ✅ สร้างรุ่นใหม่ (ชื่อรุ่น, ประเภทกีฬา, ปีการศึกษา)
- ✅ เพิ่มนักกีฬาหลายคนเข้ารุ่น (ค้นหาจาก skjacth_academic.tb_students)
- ✅ เพิ่มโค้ช/ครูผู้ฝึกสอนหลายคนเข้ารุ่น (ดึงจาก skjacth_personnel.tb_personnel)
- ✅ ลบนักกีฬา/โค้ชออกจากรุ่น
- ✅ ลบรุ่นทั้งหมด

### 4. ลบระบบเก่าที่ไม่ใช้แล้ว

**ไฟล์ที่ลบ:**

- `app/Controllers/ConAdminAthlete.php`
- `app/Models/AthleteModel.php`
- `app/Views/Admin/AdminAthlete/` (ทั้งโฟลเดอร์)

**ตารางที่ลบ:**

- `tb_athletes` (ระบบเก่าที่ผูก 1:1)

**Routes ที่ลบ:**

- `/Admin/Athlete` และ routes ที่เกี่ยวข้อง

### 5. ปรับปรุง CSS

- ปรับ Select2 ให้สมดุลกับ Bootstrap 5 form-select
- เพิ่ม focus states, dropdown styling, validation states

---

## สถานะปัจจุบัน

- ✅ ระบบ Login ทำงานได้
- ✅ ระบบรุ่นนักกีฬาพร้อมใช้งาน
- ⏳ ยังไม่เสร็จสมบูรณ์ - รอพัฒนาต่อพรุ่งนี้

## สิ่งที่อาจต้องทำต่อ

- [ ] เพิ่มระบบการแข่งขัน
- [ ] เพิ่มระบบบันทึกผลงาน/รางวัล
- [ ] เพิ่มรายงานสรุป
- [ ] เพิ่มการแก้ไขข้อมูลรุ่น
- [ ] เพิ่มการอัปโหลดรูปนักกีฬา/ทีม
- [ ] อื่นๆ ตามที่ต้องการ

---

## การเข้าถึงระบบ

- URL: https://localhost:8089
- เมนู Admin: `/Admin/Team` (รุ่นนักกีฬา)
- ฐานข้อมูลหลัก: `skjacth_sportbase`
