# Routine Flow - Quick Code Reference Guide

## System Architecture Overview

```
Routine Flow Application
├── Frontend Files (.html)
│   ├── index.html (Landing page template)
│   ├── login.html (Login form)
│   └── Admin/Student/Teacher specific UI files
│
├── PHP Backend Controllers
│   ├── index.php → Loads homepage
│   ├── login.php → Handles authentication
│   ├── register.php → Handles user registration
│   ├── logout.php → Clears session
│   │
│   ├── admin/ → Admin-only pages
│   ├── student/ → Student-only pages
│   ├── teacher/ → Teacher-only pages
│   └── shared/ → Profile, settings, notices
│
├── API Endpoints (json responses)
│   ├── api/create_routine.php → Create schedule entries
│   ├── api/get_routine.php → Fetch schedule data
│   ├── api/get_teachers.php → Teacher list
│   ├── api/add_user.php → Admin creates users
│   ├── api/delete_user.php → Admin removes users
│   └── ... (other endpoints)
│
├── Configuration Files
│   ├── includes/db.php → Database connection
│   ├── includes/core.php → System utilities
│   ├── includes/auth_check.php → Authentication middleware
│   └── includes/layout.php → HTML template components
│
└── Database
    └── database/ → SQL schema files
```

---

## User Roles & Permissions

### 1. Admin (role = 'admin')
- Full system access
- Manage users (create/delete students and teachers)
- View all routines
- Toggle maintenance mode
- View activity logs
- Create notices

### 2. Teacher (role = 'teacher')
- View own schedule
- Create routines for department
- Customize routine templates
- View student lists
- Cannot modify other teachers' work

### 3. Student (role = 'student')
- View own schedule
- Customize personal routine
- Add personal tasks/events
- Submit customized routines
- Cannot view admin functions

---

## Database Schema Quick Reference

### Main Tables:

**admins**
- id, full_name, username, email, password, gender, profile_pic

**teachers**
- id, full_name, username, email, password, gender
- teacher_id, department_id, department

**students**
- id, full_name, username, email, password, gender
- student_id, department_id, semester

**routines**
- id, subject_name, day_of_week, start_time, end_time
- room_number, teacher_id, teacher_name, department, semester

**departments**
- id, name (e.g., 'Computer Science', 'Engineering')

**activity_log**
- id, user_id, user_role, user_name, action_type, action_title
- action_description, icon_class, badge_type, created_at

**system_settings**
- setting_key, setting_value, updated_at
- (For storing maintenance_mode, etc)

---

## Common Code Patterns Used

### 1. Authentication Check
```php
require_once '../includes/auth_check.php';
checkAuth('admin');  // Ensures admin only
checkAuth('teacher'); // Ensures teacher only
```

### 2. Database Query with Error Handling
```php
try {
    $stmt = $conn->prepare("SELECT * FROM table WHERE id = ?");
    $stmt->execute([$id]);
    $data = $stmt->fetch(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    // Handle error gracefully
}
```

### 3. API Response Format
```php
echo json_encode([
    'success' => true/false,
    'message' => 'Status message',
    'payload' => $data  // Actual data
]);
```

### 4. Activity Logging
```php
logActivity(
    $conn,
    $_SESSION['user_id'],
    $_SESSION['user_role'],
    $_SESSION['user_name'],
    'action_type',  // 'create', 'update', 'delete'
    'Action Title',
    'Detailed description',
    'icon-class',
    'badge-type'    // 'success', 'danger', 'info'
);
```

### 5. Session Variables
```php
$_SESSION['user_id']      // Unique user identifier
$_SESSION['user_role']    // 'admin', 'teacher', 'student'
$_SESSION['user_name']    // User's full name
$_SESSION['user_email']   // User's email
$_SESSION['department_id'] // For teachers/students
```

---

## Key Functions Explained

### Authentication Functions

**checkAuth($required_role = null)**
- Verifies user is logged in
- If role specified, checks if user has that role
- Redirects to login if not authenticated
- Used at top of every protected page

**getBaseUrl()**
- Calculates relative path to root directory
- Used for redirects from nested directories
- Returns string like "../../../"

---

### System Functions

**getSystemSetting($key, $default)**
- Retrieves setting value from database
- Returns default if not found
- Used for maintenance_mode, theme, etc.

**setSystemSetting($key, $value)**
- Saves/updates setting in database
- Used to toggle maintenance mode, etc.

**handleMaintenance()**
- Checks if maintenance mode is enabled
- If yes, redirects users (except admins) to maintenance.html
- Admins always bypass maintenance mode

---

### Activity Functions

**logActivity(...)**
- Records user actions in activity_log table
- Parameters: conn, user_id, role, name, type, title, description, icon, badge
- Used throughout for audit trail
- Silently fails if logging fails (doesn't break app)

---

## Common Fixes Already Applied

✅ All database queries use parameterized statements (prevents SQL injection)
✅ All passwords are hashed with PASSWORD_DEFAULT algorithm
✅ Sessions regenerated after login (prevents session fixation)
✅ Error messages don't expose sensitive database info
✅ All API endpoints return JSON with success indicator
✅ Transaction support for multi-step operations (create_routine.php)
✅ Email duplicate checking before user creation
✅ CAPTCHA verification on public pages
✅ Role-based access control on all protected pages

---

## How to Add New Features

### Adding a New Admin Feature:
1. Create file in `admin/` folder (e.g., `admin/new-feature.php`)
2. Start with: `require_once '../includes/auth_check.php'; checkAuth('admin');`
3. Load layout and database: `require_once '../includes/layout.php';` + `require_once '../includes/db.php';`
4. Get data and render HTML
5. Create corresponding API in `api/new_feature.php` if needed

### Adding a New API Endpoint:
1. Create file in `api/` folder (e.g., `api/get_data.php`)
2. Set headers: `header('Content-Type: application/json');`
3. Require auth if needed: `require_once '../includes/auth_check.php'; checkAuth('admin');`
4. Process request and echo JSON response
5. Always include success/failure indicator

### Adding Database Columns:
1. Create migration file in `database/` folder
2. Use ALTER TABLE to add column
3. Update query in corresponding PHP file
4. Test with all user roles

---

## Testing Checklist

- [ ] Login works for all three roles
- [ ] Logout clears session properly
- [ ] Admin can create/delete users
- [ ] Teachers can create routines
- [ ] Students can customize routines
- [ ] Cannot access pages without login
- [ ] Cannot access other role's pages
- [ ] Database queries handle errors
- [ ] Activity log records all actions
- [ ] Maintenance mode blocks non-admins

---

## File Naming Conventions

- **Controllers** (pages): kebab-case with .php (e.g., `user-list.php`)
- **APIs** (endpoints): snake_case with .php (e.g., `get_users.php`)
- **JavaScript modules**: kebab-case with .js (e.g., `routine-creator.js`)
- **HTML templates**: kebab-case with .html (e.g., `user-list.html`)
- **CSS files**: kebab-case with .css (e.g., `form-styles.css`)

---

## Troubleshooting Guide

**"Database Connection Failed"**
- Check MySQL is running
- Verify credentials in `includes/db.php`
- Confirm database `routine_flow_db` exists

**"User not found" on login**
- Verify email exists in students/teachers/admins table
- Check password was hashed correctly
- Confirm user has active status

**"Cannot redirect"**
- Check getBaseUrl() calculation
- Verify file is in correct directory
- Check headers not already sent

**API returns empty data**
- Verify filters (department, semester) match data
- Check user has access to that department
- Verify data exists in database

---

## Performance Notes

- Queries use indexes on frequently searched columns (id, email, department_id)
- Activity logs should be archived/deleted regularly to maintain performance
- Consider caching for frequently accessed data (departments list)
- Database transactions keep data consistent during multi-step operations

---

**For detailed comments, see each individual file**
**Last updated: January 21, 2026**
