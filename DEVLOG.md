# SportBase 2026 - บันทึกการพัฒนา

## วันที่: 6 สิงหาคม 2569 (2026-08-06)

---

### ปรับปรุงระบบอัปโหลดและครอบตัดรูปภาพนักกีฬา (Aspect Ratio 3:4 & Mobile Support)

**รายละเอียดการปรับปรุง:**
- ✅ **อัตราส่วนรูปภาพ (3:4)**: ปรับแก้ Cropper.js ให้ล็อคสัดส่วนการตัดรูปเป็นแนวตั้ง 3:4 (600x800 px) เหมาะสมกับรูปถ่ายติดบัตร/นักกีฬา
- ✅ **ยกเลิกการจำกัดขนาดไฟล์**: ลบข้อจำกัดขนาดไฟล์ 2MB ออกจากระบบเพื่อเปิดให้เลือกไฟล์รูปภาพขนาดใหญ่จากกล้องมือถือได้ โดยเบราว์เซอร์จะทำการย่อและครอบตัดผ่าน HTML5 Canvas Client-side ก่อนส่งไปบันทึก
- ✅ **รูปภาพเริ่มต้นเมื่อไม่มีรูปหรือรูปเสีย (Default Avatar Fallback)**: เพิ่มการตรวจสอบ `file_exists` และการใช้ `onerror` switch รูปภาพอัตโนมัติไปยัง Vector User Icon หากยังไม่มีการอัปโหลดหรือรูปภาพในเซิร์ฟเวอร์สูญหาย/โหลดไม่ได้
- ✅ **การแสดงผลรูปภาพบุคลากร/โค้ชประจำรุ่น**: เพิ่มการดึงข้อมูล `pers_img` จากฐานข้อมูลบุคลากร และแสดงผลรูปภาพโปรไฟล์ครูผู้ฝึกสอนประจำรุ่นคู่กับชื่อในหน้ารายละเอียดรุ่น
- ✅ **กู้คืนลิงก์เมนู "ทำเนียบนักกีฬา" และ "สถานะประจำวัน" (Restore Home Page Links)**:
  - เพิ่มเมนูและทางลัดด่วนกลับเข้ามาในหน้าแรกและสไลด์บาร์ฝั่งผู้ใช้ทั่วไป เพื่อเชื่อมต่อไปยังระบบ `User/Athlete` และ `User/Attendance` ได้ตามปกติ
- ✅ **แก้ไขข้อผิดพลาด CodeIgniter HTTPException (Request URI segment is out of range: "2")**:
  - เพิ่มพารามิเตอร์ fallback ค่าว่าง `''` ในการเรียก `$uri->getSegment(index, '')` ทุกจุดในระบบ (`UserMenuLeft.php`, `AdminMenuLeft.php`, `UserHeader.php`, `UserFooter.php`, `AdminFooter.php`)
  - แก้ไขปัญหาข้อผิดพลาดเมื่อเข้าใช้งานหน้าแรกสาธารณะ (`https://localhost:8089/`) ที่ไม่มี Segment ลำดับที่ 2 ให้สามารถเปิดใช้งานได้อย่างสมบูรณ์ 100%

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

- ✅ **ปรับโฉมหน้าตรวจสอบสถานะนักกีฬาประจำวัน (Daily Attendance Status UI/UX Redesign)**:
  - ออกแบบหน้าจอแบบ Dual View: บนเดสก์ท็อปแสดงผลในรูปแบบตารางสวยงามสะอาดตา และบนมือถือแสดงผลเป็นการ์ดขนาดกะทัดรัด (`.mobile-attendance-card`) อ่านง่ายไม่ล้นจอ
  - พัฒนาปุ่มเลือกวันที่ด้วย **Flatpickr ธีมสีส้มพรีเมียมและแสดงปี พ.ศ. ภาษาไทย** เต็มรูปแบบแทนระบบ Date Input เดิมของเบราว์เซอร์
  - เพิ่ม **ช่องค้นหารายชื่อและการลาแบบเรียลไทม์ (Live Search)** ช่วยให้ผู้ปกครองและแอดมินกรองรายชื่อนักกีฬาได้อย่างรวดเร็ว

- ✅ **ปรับปรุงสีตัวเลขยอดสมาชิกในการเลือกทีม (Filter Pill Badge Contrast Fix)**:
  - แก้ไขสีพื้นหลังและสีตัวเลขของตราสัญลักษณ์ (Badge) ตัวเลขจำนวนนักกีฬาในปุ่มเลือกทีมแนวนอนและเมนูด้านข้าง โดยเมื่อปุ่มไม่ทำงานจะแสดงเป็นสีเทาสุภาพเด่นชัด และเมื่อกดคลิกทำงาน (Active) จะแสดงเป็นพื้นสีขาวตัดกับข้อความสีส้มอย่างชัดเจน อ่านง่าย 100%

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
