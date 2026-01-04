CREATE DATABASE IF NOT EXISTS routine_flow_db;
USE routine_flow_db;

-- 1. Users Table
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    gender ENUM('Male', 'Female', 'Other') NOT NULL,
    role ENUM('Student', 'Teacher', 'Admin') NOT NULL,
    department VARCHAR(50),
    profile_image VARCHAR(255) DEFAULT 'default_avatar.png', -- Stores file path
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- 2. Routines Table (Master Schedule)
CREATE TABLE IF NOT EXISTS routines (
    id INT AUTO_INCREMENT PRIMARY KEY,
    department VARCHAR(50) NOT NULL,
    day_of_week ENUM('Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday') NOT NULL,
    start_time TIME NOT NULL,
    end_time TIME NOT NULL,
    subject_name VARCHAR(100) NOT NULL,
    room_number VARCHAR(20),
    teacher_id INT, -- Link to a registered teacher
    attachment_path VARCHAR(255), -- Stores path to PDF/Image materials
    created_by INT, -- Admin who created this
    FOREIGN KEY (teacher_id) REFERENCES users(id) ON DELETE SET NULL,
    FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE SET NULL
);

-- 3. Custom Activities (For Students personal schedule)
CREATE TABLE IF NOT EXISTS custom_activities (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    title VARCHAR(100) NOT NULL,
    activity_date DATE NOT NULL,
    start_time TIME NOT NULL,
    end_time TIME NOT NULL,
    location VARCHAR(100),
    is_completed BOOLEAN DEFAULT FALSE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- 4. Notices (Dashboard Announcements)
CREATE TABLE IF NOT EXISTS notices (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(150) NOT NULL,
    content TEXT NOT NULL,
    audience ENUM('All', 'Student', 'Teacher') DEFAULT 'All',
    created_by INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE SET NULL
);

-- Note:
-- Images and files are stored as file paths in the database (e.g., 'uploads/profiles/user123.jpg').
-- The actual files will be stored in an 'uploads' directory on the server.
