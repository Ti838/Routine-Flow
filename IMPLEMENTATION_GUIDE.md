# Routine Flow - Implementation Guide

## Project Overview

**Routine Flow** is a comprehensive web-based routine management system designed for educational institutions. It provides role-based dashboards for Admins, Teachers, and Students to manage class schedules, routines, and academic activities.

---

## 🎯 What Was Implemented

### 1. **User Authentication System**

- **Login/Registration** with role-based access (Admin, Teacher, Student)
- **Password hashing** using PHP's `password_hash()` and `password_verify()`
- **Session management** with PHP sessions
- **Math Captcha** for security during login/registration
- **Password recovery** functionality

### 2. **Admin Module** (16 files)

- **Dashboard**: Overview of system statistics (total users, departments, routines)
- **User Management**: Add, edit, delete users (admins, teachers, students)
- **Department Management**: Manage 12 departments (CSE, EEE, ME, etc.)
- **Routine Management**: Create, edit, delete class routines
- **Analytics Dashboard**: Visual charts and statistics
- **Activity Log**: Track all user actions (login, create, delete, etc.)
- **Notice Management**: Create and manage system-wide announcements

### 3. **Teacher Module** (8 files)

- **Dashboard**: Teacher-specific statistics and upcoming classes
- **Create Routine**: Teachers can create routines for their subjects
- **Department View**: View all routines for their department
- **Today's Schedule**: View today's classes

### 4. **Student Module** (10 files)

- **Dashboard**: Student-specific overview with upcoming classes
- **Weekly Routine**: View full week's class schedule
- **Today's Schedule**: View today's classes
- **Custom Routine Builder**: Customize routine colors, add personal events
- **Task Management**: Pomodoro-based task tracker with time tracking

### 5. **Shared Features** (6 files)

- **Profile Page**: View and edit user profile with profile picture upload
- **Settings Page**: Change password, theme preferences, notification settings
- **Notices Page**: View system-wide announcements

### 6. **Backend API** (28 endpoints)

All API endpoints are RESTful and return JSON responses:

- **Authentication**: `check_session.php`, `reset_password.php`
- **User Management**: `add_user.php`, `delete_user.php`
- **Department Management**: `add_department.php`, `delete_department.php`
- **Routine Management**: `create_routine.php`, `get_routine.php`, `delete_routine.php`
- **File Management**: `upload_routine.php`, `download_routine.php`
- **Customization**: `customize_routine.php`, `save_template.php`
- **Analytics**: `get_admin_stats.php`, `get_analytics_data.php`
- **Tasks**: `student_tasks.php`, `student_scheduler.php`

### 7. **Database Schema** (15 tables)

- **User Tables**: `admins`, `teachers`, `students`
- **Core Tables**: `departments`, `routines`, `routine_attachments`, `routine_files`
- **Student Features**: `student_routine_customizations`, `personal_events`, `student_tasks`, `routine_templates`
- **System Tables**: `activity_log`, `download_logs`, `notices`, `system_settings`

---

## 💻 Technologies & Code Used

### Frontend Technologies

#### 1. **HTML5**

- Semantic HTML structure
- Form validation with HTML5 attributes
- Accessible markup with ARIA labels

#### 2. **Tailwind CSS**

```html
<!-- CDN Integration -->
<script src="https://cdn.tailwindcss.com"></script>
<script src="../assets/js/tailwind-config.js"></script>
```

**Key Tailwind Features Used:**

- **Utility Classes**: `bg-gray-50`, `dark:bg-[#0B1121]`, `text-gray-900`
- **Responsive Design**: `lg:p-10`, `md:grid-cols-2`, `sm:text-sm`
- **Dark Mode**: `dark:` prefix for dark theme support
- **Custom Colors**: Defined in `tailwind-config.js`
- **Animations**: `transition-all`, `hover:shadow-xl`, `group-hover:scale-110`

#### 3. **Vanilla JavaScript**

```javascript
// Theme Management (assets/js/theme.js)
function toggleTheme() {
    const html = document.documentElement;
    const isDark = html.classList.contains('dark');
    if (isDark) {
        html.classList.remove('dark');
        localStorage.setItem('theme', 'light');
    } else {
        html.classList.add('dark');
        localStorage.setItem('theme', 'dark');
    }
}

// Session Monitoring (assets/js/session-monitor.js)
async function checkSession() {
    const response = await fetch('/api/check_session.php');
    const data = await response.json();
    if (!data.authenticated) {
        window.location.href = '/login.php';
    }
}

// Component Loader (assets/js/component-loader.js)
function loadNavbar(role, activePage) {
    // Dynamically loads navbar based on user role
}
```

#### 4. **Remix Icon**

```html
<link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet">
```

- Used for all icons throughout the application
- Examples: `ri-dashboard-line`, `ri-user-line`, `ri-calendar-line`

### Backend Technologies

#### 1. **PHP 8.x**

**Authentication Example** (`login.php`):

```php
<?php
session_start();
require_once 'includes/db.php';

// Verify credentials
$stmt = $conn->prepare("SELECT * FROM admins WHERE email = ?");
$stmt->execute([$email]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($user && password_verify($password, $user['password'])) {
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['user_role'] = 'admin';
    $_SESSION['user_name'] = $user['full_name'];
    
    // Log activity
    $log_stmt = $conn->prepare("INSERT INTO activity_log (user_id, user_role, user_name, action_type, action_title, action_description, icon_class, badge_type) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $log_stmt->execute([$user['id'], 'admin', $user['full_name'], 'login', 'Login', 'Admin logged in', 'ri-login-box-line', 'success']);
    
    header('Location: admin/dashboard.php');
}
?>
```

**Database Connection** (`includes/db.php`):

```php
<?php
$host = 'localhost';
$dbname = 'routine_flow_db';
$username = 'root';
$password = '';

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>
```

**Authentication Middleware** (`includes/auth_check.php`):

```php
<?php
function checkAuth($required_role = null) {
    if (!isset($_SESSION['user_id'])) {
        header('Location: /login.php');
        exit;
    }
    
    if ($required_role && $_SESSION['user_role'] !== $required_role) {
        header('Location: /login.php');
        exit;
    }
}
?>
```

**API Endpoint Example** (`api/get_routine.php`):

```php
<?php
header('Content-Type: application/json');
require_once '../includes/db.php';

$department = $_GET['department'] ?? '';
$semester = $_GET['semester'] ?? '';

$stmt = $conn->prepare("SELECT * FROM routines WHERE department = ? AND semester = ? AND status = 'active' ORDER BY day_of_week, start_time");
$stmt->execute([$department, $semester]);
$routines = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode(['success' => true, 'data' => $routines]);
?>
```

#### 2. **MySQL 8.0 / MariaDB**

**Database Schema Example**:

```sql
-- Activity Log Table
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

-- Sample Data
INSERT INTO `activity_log` (user_id, user_role, user_name, action_type, action_title, action_description, icon_class, badge_type) VALUES
(1, 'admin', 'Admin User', 'login', 'Login', 'Admin logged in', 'ri-login-box-line', 'success'),
(2, 'teacher', 'John Doe', 'create_user', 'Created User', 'Added new teacher account', 'ri-user-add-line', 'info');
```

**Foreign Key Relationships**:

```sql
-- Teachers belong to departments
ALTER TABLE teachers 
ADD FOREIGN KEY (department_id) REFERENCES departments(id) ON DELETE SET NULL;

-- Routines cascade delete attachments
ALTER TABLE routine_attachments 
ADD FOREIGN KEY (routine_id) REFERENCES routines(id) ON DELETE CASCADE;
```

---

## 🏗️ Project Structure

### Directory Organization

```text
Routine Flow/
│
├── Root Files (Authentication & Landing)
│   ├── index.html, index.php          # Landing pages
│   ├── login.html, login.php          # Login/Register
│   ├── register.php                   # User registration
│   ├── logout.php                     # Session termination
│   └── forgot_password.php            # Password recovery
│
├── admin/                              # Admin Module
│   ├── dashboard.html/php             # Admin dashboard
│   ├── users.html/php                 # User management
│   ├── departments.html/php           # Department management
│   ├── create-routine.html/php        # Routine creation
│   ├── manage-routines.html/php       # Routine management
│   ├── analytics.html/php             # Analytics dashboard
│   ├── activity-log.html/php          # Activity logs
│   └── notices.html/php               # Notice management
│
├── teacher/                            # Teacher Module
│   ├── dashboard.html/php             # Teacher dashboard
│   ├── create-routine.html/php        # Create routines
│   ├── department.html/php            # Department view
│   └── today.html/php                 # Today's schedule
│
├── student/                            # Student Module
│   ├── dashboard.html/php             # Student dashboard
│   ├── weekly.html/php                # Weekly routine
│   ├── today.html/php                 # Today's schedule
│   ├── custom-routine.html/php        # Custom routine builder
│   └── tasks.html/php                 # Task management
│
├── shared/                             # Shared Pages
│   ├── profile.html/php               # User profile
│   ├── settings.html/php              # Settings
│   └── notices.html/php               # Notices
│
├── api/                                # Backend API (28 files)
│   ├── Authentication APIs
│   ├── User Management APIs
│   ├── Department APIs
│   ├── Routine APIs
│   ├── File Management APIs
│   ├── Customization APIs
│   └── Analytics APIs
│
├── includes/                           # Core PHP Libraries
│   ├── db.php                         # Database connection
│   ├── auth_check.php                 # Authentication
│   ├── layout.php                     # Layout rendering
│   ├── core.php                       # Core utilities
│   └── file_handler.php               # File operations
│
├── assets/                             # Static Resources
│   ├── css/zoom-fix.css               # Zoom fix styles
│   ├── js/                            # JavaScript modules
│   │   ├── tailwind-config.js
│   │   ├── theme.js
│   │   ├── db-manager.js
│   │   ├── component-loader.js
│   │   ├── session-monitor.js
│   │   ├── create-routine.js
│   │   ├── routine-customizer.js
│   │   ├── student-dashboard.js
│   │   └── file-upload.js
│   └── img/                           # Images & logos
│
├── database/
│   └── routine_flow_final.sql         # Complete schema
│
└── uploads/                            # User uploads
    ├── routines/admin/
    ├── routines/teacher/
    └── profiles/
```

---

## 🔧 Key Features Implementation

### 1. **Dark Mode Toggle**

```javascript
// Implemented in assets/js/theme.js
(function () {
    const saved = localStorage.getItem('theme');
    if (saved === 'dark' || saved === 'light') {
        if (saved === 'dark') document.documentElement.classList.add('dark');
    } else if (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches) {
        document.documentElement.classList.add('dark');
    }
})();
```

### 2. **Activity Logging**

Every major action is logged in the `activity_log` table:

```php
function logActivity($conn, $user_id, $user_role, $user_name, $action_type, $action_title, $description, $icon, $badge_type) {
    $stmt = $conn->prepare("INSERT INTO activity_log (user_id, user_role, user_name, action_type, action_title, action_description, icon_class, badge_type) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([$user_id, $user_role, $user_name, $action_type, $action_title, $description, $icon, $badge_type]);
}
```

### 3. **File Upload System**

```php
// File upload with validation
$allowed_types = ['application/pdf', 'image/jpeg', 'image/png'];
$max_size = 5 * 1024 * 1024; // 5MB

if (in_array($_FILES['file']['type'], $allowed_types) && $_FILES['file']['size'] <= $max_size) {
    $upload_dir = '../uploads/routines/' . $_SESSION['user_role'] . '/';
    $filename = uniqid() . '_' . $_FILES['file']['name'];
    move_uploaded_file($_FILES['file']['tmp_name'], $upload_dir . $filename);
}
```

### 4. **Responsive Design**

```html
<!-- Tailwind responsive classes -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    <!-- Cards adapt to screen size -->
</div>
```

---

## 📊 Database Design Highlights

### Entity Relationships

- **One-to-Many**: `departments` → `teachers`, `students`, `routines`
- **Many-to-Many**: `students` ↔ `routines` (via `student_routine_customizations`)
- **Cascade Delete**: Deleting a routine removes all attachments
- **Soft References**: Activity log doesn't use foreign keys for audit integrity

### Indexing Strategy

```sql
-- Improve query performance
CREATE INDEX idx_dept_semester ON routines(department, semester);
CREATE INDEX idx_day_time ON routines(day_of_week, start_time);
CREATE INDEX idx_student_starred ON student_routine_customizations(student_id, is_starred);
```

---

## 🚀 Deployment Instructions

### 1. **XAMPP Setup**

1. Install XAMPP (Apache + MySQL)
2. Start Apache and MySQL services
3. Place project in `C:\xampp\htdocs\Routine Flow`

### 2. **Database Setup**

```bash
# Import database schema
mysql -u root < "C:\Users\TIMON\Desktop\Routine Flow\database\routine_flow_final.sql"
```

### 3. **Access Application**

- **URL**: `http://localhost/Routine Flow/`
- **Admin Login**: `admin@routineflow.com` / `password`

---

## 📝 Code Standards Used

### PHP Standards

- **PSR-12** coding style
- **PDO** for database operations (prepared statements)
- **Error handling** with try-catch blocks
- **Session security** with httponly cookies

### JavaScript Standards

- **ES6+** syntax (arrow functions, async/await, template literals)
- **Modular code** with separate files for different features
- **Event delegation** for dynamic content
- **Fetch API** for AJAX requests

### CSS Standards

- **Tailwind utility-first** approach
- **Mobile-first** responsive design
- **Dark mode** support with CSS variables
- **Consistent spacing** using Tailwind's spacing scale

---

## 🔐 Security Features

1. **Password Hashing**: `password_hash()` with bcrypt
2. **SQL Injection Prevention**: PDO prepared statements
3. **XSS Prevention**: `htmlspecialchars()` for output
4. **CSRF Protection**: Session tokens (can be enhanced)
5. **Session Security**: `session_regenerate_id()` on login
6. **File Upload Validation**: Type and size checks

---

## 📈 Performance Optimizations

1. **Database Indexing**: Strategic indexes on frequently queried columns
2. **Lazy Loading**: Images and components load on demand
3. **CDN Usage**: Tailwind CSS and Remix Icon from CDN
4. **Minification**: Production-ready CSS/JS minification
5. **Caching**: Browser caching for static assets

---

*This implementation guide provides a comprehensive overview of the Routine Flow project, including all technologies, code examples, and implementation details.*

**Version**: 1.0  
**Last Updated**: January 2026  
**Database Version**: 1.0
