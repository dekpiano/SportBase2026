-- ตาราง tb_admin_rloes สำหรับจัดการสิทธิ์ผู้ใช้
-- ฐานข้อมูล: skjacth_sportbase

CREATE TABLE IF NOT EXISTS `tb_admin_rloes` (
    `id` INT(11) NOT NULL AUTO_INCREMENT COMMENT 'รหัส',
    `pers_id` VARCHAR(20) NOT NULL COMMENT 'รหัสบุคลากร (จาก tb_personnel)',
    `role_type` VARCHAR(20) NOT NULL COMMENT 'ประเภทสิทธิ์: admin/manager/coach',
    `role_position` VARCHAR(100) NULL COMMENT 'ตำแหน่งย่อย',
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP COMMENT 'วันเวลาสร้าง',
    PRIMARY KEY (`id`),
    INDEX `idx_pers` (`pers_id`),
    INDEX `idx_role` (`role_type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='ตารางจัดการสิทธิ์ผู้ใช้งาน SportBase';

-- =====================================
-- สิทธิ์ 3 ประเภท:
-- =====================================

-- 1. admin (ผู้ดูแลระบบ)
--    ตำแหน่ง: ผู้ดูแลระบบ
--    สิทธิ์: จัดการทุกอย่างในระบบ

-- 2. manager (ผู้บริหาร) 
--    ตำแหน่ง: ผู้อำนวยการ, รองผู้อำนวยการ, หัวหน้ากลุ่มสาระ
--    สิทธิ์: ดูรายงาน, อนุมัติ

-- 3. coach (ผู้ดูแลนักกีฬา/โค้ช)
--    ตำแหน่ง: ผู้ดูแลนักกีฬา, โค้ช
--    สิทธิ์: จัดการเช็คชื่อ, ดูข้อมูลนักกีฬา

-- =====================================
-- ตัวอย่างข้อมูล:
-- =====================================
-- INSERT INTO tb_admin_rloes (pers_id, role_type, role_position) 
-- VALUES ('pers_021', 'admin', 'ผู้ดูแลระบบ');

-- INSERT INTO tb_admin_rloes (pers_id, role_type, role_position) 
-- VALUES ('pers_001', 'manager', 'ผู้อำนวยการ');

-- INSERT INTO tb_admin_rloes (pers_id, role_type, role_position) 
-- VALUES ('pers_010', 'coach', 'โค้ช');
