-- ==========================================
-- ROUTINE FLOW - FINAL CONSOLIDATED DATABASE
-- Target: MySQL / MariaDB (XAMPP)
-- Database Name: routine_flow_db
-- ==========================================

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

-- 1. DATABASE CREATION
CREATE DATABASE IF NOT EXISTS `routine_flow_db` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `routine_flow_db`;

-- 2. DEPARTMENTS
CREATE TABLE IF NOT EXISTS `departments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `code` varchar(10) NOT NULL UNIQUE,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `departments` (`id`, `name`, `code`) VALUES
(1, 'Computer Science & Engineering', 'CSE'),
(2, 'Electrical & Electronic Engineering', 'EEE'),
(3, 'Mechanical Engineering', 'ME'),
(4, 'Civil Engineering', 'CE'),
(5, 'Electronics & Communication Engineering', 'ECE'),
(6, 'Chemical Engineering', 'CHE'),
(7, 'Industrial & Production Engineering', 'IPE'),
(8, 'Textile Engineering', 'TE'),
(9, 'Software Engineering', 'SWE'),
(10, 'Biomedical Engineering', 'BME'),
(11, 'Aerospace Engineering', 'AE'),
(12, 'Environmental Engineering', 'EnvE')
ON DUPLICATE KEY UPDATE name = VALUES(name);

-- 3. USERS (ADMINS, TEACHERS, STUDENTS)
CREATE TABLE IF NOT EXISTS `admins` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `full_name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL UNIQUE,
  `password` varchar(255) NOT NULL,
  `gender` varchar(20) DEFAULT NULL,
  `profile_pic` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `teachers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `full_name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL UNIQUE,
  `password` varchar(255) NOT NULL,
  `gender` varchar(20) DEFAULT NULL,
  `profile_pic` varchar(255) DEFAULT NULL,
  `department` varchar(100) DEFAULT NULL,
  `department_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  FOREIGN KEY (department_id) REFERENCES departments(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `students` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `full_name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL UNIQUE,
  `password` varchar(255) NOT NULL,
  `gender` varchar(20) DEFAULT NULL,
  `profile_pic` varchar(255) DEFAULT NULL,
  `student_id` varchar(20) DEFAULT NULL UNIQUE,
  `department` varchar(100) DEFAULT NULL,
  `department_id` int(11) DEFAULT NULL,
  `semester` varchar(20) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  FOREIGN KEY (department_id) REFERENCES departments(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 4. ROUTINES & ATTACHMENTS
CREATE TABLE IF NOT EXISTS `routines` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `department_id` int(11) NOT NULL,
  `department` varchar(100) NOT NULL,
  `semester` varchar(20) NOT NULL,
  `subject_name` varchar(255) NOT NULL,
  `teacher_id` int(11) DEFAULT NULL,
  `teacher_name` varchar(255) NOT NULL,
  `room_number` varchar(50) NOT NULL,
  `day_of_week` varchar(20) NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  `status` enum('active','inactive') DEFAULT 'active',
  `is_file_only` BOOLEAN DEFAULT FALSE,
  `file_description` TEXT,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  FOREIGN KEY (department_id) REFERENCES departments(id) ON DELETE CASCADE,
  INDEX idx_dept_semester (department, semester),
  INDEX idx_day_time (day_of_week, start_time)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `routine_attachments` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `routine_id` int(11) DEFAULT NULL,
    `file_name` varchar(255) NOT NULL,
    `file_path` varchar(500) NOT NULL,
    `file_type` enum('pdf','image') NOT NULL,
    `file_size` int(11) NOT NULL,
    `uploaded_by_role` enum('admin','teacher') NOT NULL,
    `uploaded_by_id` int(11) NOT NULL,
    `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    FOREIGN KEY (`routine_id`) REFERENCES `routines` (`id`) ON DELETE CASCADE,
    INDEX idx_routine_id (routine_id),
    INDEX idx_uploaded_by (uploaded_by_role, uploaded_by_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 5. STUDENT CUSTOMIZATIONS & TEMPLATES
CREATE TABLE IF NOT EXISTS `student_routine_customizations` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `student_id` int(11) NOT NULL,
    `routine_id` int(11) NOT NULL,
    `color_code` varchar(7) DEFAULT NULL,
    `is_starred` boolean DEFAULT FALSE,
    `priority` int(11) DEFAULT 0,
    `notes` text,
    `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE,
    FOREIGN KEY (`routine_id`) REFERENCES `routines` (`id`) ON DELETE CASCADE,
    UNIQUE KEY unique_student_routine (student_id, routine_id),
    INDEX idx_student_starred (student_id, is_starred),
    INDEX idx_student_priority (student_id, priority)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `personal_events` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `student_id` int(11) NOT NULL,
    `title` varchar(255) NOT NULL,
    `day_of_week` varchar(20) NOT NULL,
    `start_time` time NOT NULL,
    `end_time` time NOT NULL,
    `color` varchar(20) DEFAULT 'orange',
    `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `routine_templates` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `student_id` int(11) NOT NULL,
    `template_name` varchar(100) NOT NULL,
    `template_type` enum('daily', 'weekly', 'monthly', 'custom') NOT NULL,
    `template_data` json NOT NULL,
    `is_active` boolean DEFAULT TRUE,
    `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE,
    INDEX idx_student_type (student_id, template_type)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 6. SYSTEM LOGS & NOTICES
CREATE TABLE IF NOT EXISTS `activity_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `user_role` enum('admin','teacher','student') NOT NULL,
  `user_name` varchar(255) NOT NULL,
  `action_type` varchar(50) NOT NULL,
  `action_title` varchar(255) NOT NULL,
  `action_description` text,
  `icon_class` varchar(50) DEFAULT 'ri-information-line',
  `badge_type` enum('new','alert','info','success') DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `download_logs` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `attachment_id` int(11) NOT NULL,
    `student_id` int(11) NOT NULL,
    `downloaded_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    FOREIGN KEY (attachment_id) REFERENCES routine_attachments(id) ON DELETE CASCADE,
    FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE CASCADE,
    INDEX idx_attachment (attachment_id),
    INDEX idx_student (student_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `notices` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `system_settings` (
  `setting_key` varchar(50) NOT NULL,
  `setting_value` text NOT NULL,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`setting_key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `system_settings` (`setting_key`, `setting_value`) VALUES ('maintenance_mode', '0') ON DUPLICATE KEY UPDATE setting_value = VALUES(setting_value);

-- 7. DEFAULT SAMPLE DATA
-- Admin: admin@routineflow.com / password
INSERT INTO `admins` (`full_name`, `email`, `password`) VALUES
('Admin User', 'admin@routineflow.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi')
ON DUPLICATE KEY UPDATE full_name = VALUES(full_name);

INSERT INTO `notices` (`title`, `content`) VALUES
('Spring 2026 Routine Active', 'The new routine for the Spring 2026 semester is now live for all departments.'),
('System Maintenance', 'Scheduled maintenance on Jan 15th from 2:00 AM to 4:00 AM.');

COMMIT;
