-- ตาราง tb_attendance สำหรับบันทึกการลานักกีฬา
-- ฐานข้อมูล: skjacth_sportbase

CREATE TABLE IF NOT EXISTS `tb_attendance` (
    `att_id` INT(11) NOT NULL AUTO_INCREMENT COMMENT 'รหัสการลา',
    `team_id` INT(11) NOT NULL COMMENT 'รหัสรุ่น/ทีม',
    `StudentID` VARCHAR(20) NOT NULL COMMENT 'รหัสนักเรียน',
    `att_date` DATE NOT NULL COMMENT 'วันที่',
    `att_status` ENUM('sick', 'personal', 'home') NOT NULL COMMENT 'สถานะ: sick=ลาป่วย, personal=ลากิจธุระ, home=ลากิจกลับบ้าน',
    `att_note` TEXT NULL COMMENT 'หมายเหตุ',
    `att_time` TIME NULL COMMENT 'เวลาบันทึก',
    `checked_by` VARCHAR(50) NULL COMMENT 'รหัสผู้บันทึก',
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP COMMENT 'วันเวลาสร้าง',
    PRIMARY KEY (`att_id`),
    INDEX `idx_team_date` (`team_id`, `att_date`),
    INDEX `idx_student_date` (`StudentID`, `att_date`),
    INDEX `idx_date` (`att_date`),
    CONSTRAINT `fk_attendance_team` FOREIGN KEY (`team_id`) REFERENCES `tb_teams`(`team_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='ตารางบันทึกการลานักกีฬา';

-- หมายเหตุ: 
-- 1. เก็บเฉพาะคนที่ "ไม่อยู่" (ลา/กลับบ้าน) เท่านั้น
-- 2. ถ้าไม่มี record = อยู่ปกติ
-- 3. สถานะ:
--    - sick = ลาป่วย (สีเหลือง)
--    - personal = ลากิจธุระ (สีฟ้า)
--    - home = ลากิจกลับบ้าน (สีแดง)
