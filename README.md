# Routine Flow - Quick Reference Guide

> **Smart Academic Routine Management System**  
> Role-based web application for managing academic schedules

---

## 🎯 What is Routine Flow?

A complete routine management system for educational institutions with three user roles:

- **Admin** - Full system control, analytics, user management
- **Teacher** - Create routines, manage schedules
- **Student** - View schedules, customize routines, manage tasks

---

## 🚀 Quick Start

### 1. Setup (XAMPP)

```bash
# 1. Copy project to htdocs
C:\xampp\htdocs\Routine Flow

# 2. Import database
phpMyAdmin → Import → database/routine_flow_final.sql

# 3. Start services
XAMPP Control Panel → Start Apache & MySQL

# 4. Open browser
http://localhost/Routine%20Flow/
```

### 2. Default Login

```text
Admin:
Email: admin@example.com
Password: admin123

Teacher:
Email: teacher@example.com
Password: teacher123

Student:
Email: student@example.com
Password: student123
```

---

## 📁 Project Structure

```text
Routine Flow/
├── admin/          # Admin dashboard & management
├── teacher/        # Teacher routine creation
├── student/        # Student schedules & tasks
├── shared/         # Profile, settings, notices
├── api/            # Backend API endpoints
├── includes/       # Core utilities (DB, Auth, Layout)
├── assets/
│   ├── js/         # JavaScript files
│   ├── css/        # Stylesheets
│   └── img/        # Images & logos
├── database/       # SQL schema
└── uploads/        # User files
```

---

## 🎨 Key Features

### For Admin

✅ User management (Add/Edit/Delete)  
✅ Real-time analytics dashboard  
✅ Department management  
✅ System-wide routine oversight  

### For Teacher

✅ Dual-mode routine creation (File upload + Manual)  
✅ Department-specific views  
✅ Semester management  

### For Student

✅ Personalized weekly planner  
✅ Pomodoro task manager  
✅ Custom routine builder (Star/Color-code classes)  
✅ Personal event creation  

---

## 🛠️ Technology Stack

| Component | Technology |
|:----------|:-----------|
| **Backend** | PHP 7.4+, MySQL, PDO |
| **Frontend** | HTML5, Tailwind CSS, JavaScript |
| **Charts** | Chart.js |
| **Icons** | Remixicon |

---

## 🗄️ Database Tables (15 Total)

### User Management (3 tables)

- `admins` - Administrator accounts
- `teachers` - Teacher accounts with department association
- `students` - Student accounts with department and semester info

### Core Functionality (4 tables)

- `departments` - 12 engineering departments (CSE, EEE, ME, etc.)
- `routines` - Class schedules and timetables
- `routine_attachments` - PDF/Image files attached to routines
- `routine_files` - Uploaded routine documents

### Student Features (4 tables)

- `student_routine_customizations` - Star/color preferences
- `personal_events` - Student-created events
- `student_tasks` - Pomodoro task management
- `routine_templates` - Saved routine templates

### System Tables (4 tables)

- `activity_log` - Audit trail of all user actions
- `download_logs` - Track file downloads
- `notices` - System-wide announcements
- `system_settings` - Configuration settings

---

## 📱 Responsive Design

**Fully responsive across all devices:**

- ✅ Mobile (320px+)
- ✅ Tablet (768px+)
- ✅ Laptop (1024px+)
- ✅ Desktop/4K (1920px+)

**Zoom stability:**

- ✅ 50%-200% browser zoom support
- ✅ Window resize compatible
- ✅ Multi-monitor ready

---

## 🔒 Security Features

✅ Password hashing (PHP `password_hash`)  
✅ SQL injection prevention (PDO prepared statements)  
✅ Session-based authentication  
✅ Role-based access control  
✅ File upload validation  

---

## 🎯 Common Tasks

### Add a New User (Admin)

1. Login as Admin
2. Go to **User Management**
3. Click **Add Member**
4. Fill form → Submit

### Create Routine (Teacher)

1. Login as Teacher
2. Go to **Create Routine**
3. Choose:
   - **File Upload** → Select PDF/Excel
   - **Manual Entry** → Fill multi-row form
4. Submit

### Customize Schedule (Student)

1. Login as Student
2. Go to **Routine Builder**
3. Star important classes
4. Apply custom colors
5. Save changes

---

## 🐛 Troubleshooting

### Issue: White screen

**Fix:** Enable error display

```php
// Add to top of problematic file
ini_set('display_errors', 1);
error_reporting(E_ALL);
```

### Issue: Database connection failed

**Fix:** Check credentials in `includes/db.php`

```php
$pdo = new PDO(
    "mysql:host=localhost;dbname=routine_flow_final",
    "root",  // Your username
    ""       // Your password
);
```

### Issue: File upload fails

**Fix:** Set permissions

```bash
chmod 777 uploads/routines/admin
chmod 777 uploads/routines/teacher
```

---

## 📊 File Locations (Quick Reference)

### Core Files

- **Database Connection:** `includes/db.php`
- **Authentication:** `includes/auth_check.php`
- **Layout (Navbar/Sidebar):** `includes/layout.php`
- **Theme System:** `assets/js/theme.js`
- **Zoom Fix:** `assets/css/zoom-fix.css`

### Student Pages

- Dashboard: `student/dashboard.html`
- Tasks: `student/tasks.html`
- Weekly Planner: `student/weekly.html`
- Custom Routine: `student/custom-routine.html`

### Admin Pages

- Dashboard: `admin/dashboard.html`
- Analytics: `admin/analytics.html`
- Users: `admin/users.html`
- Routines: `admin/manage-routines.html`

### Teacher Pages

- Dashboard: `teacher/dashboard.html`
- Create Routine: `teacher/create-routine.html`
- Department View: `teacher/department.html`

---

## 🎨 Theme System

**Auto-detects system preference:**

- Light mode (default)
- Dark mode (auto-enabled if OS is in dark mode)
- Manual toggle available

**No flash on page load** (Anti-FOUC implemented)

---

## 📝 API Endpoints (Quick List)

| Endpoint | Purpose |
|:---------|:--------|
| `api/create_routine.php` | Create routines |
| `api/upload_routine.php` | Upload files |
| `api/customize_routine.php` | Save customizations |
| `api/student_tasks.php` | Manage tasks |
| `api/student_scheduler.php` | Personal events |
| `api/add_user.php` | Add new user |
| `api/get_analytics_data.php` | Fetch analytics |

---

## ✅ Testing Checklist

Before deployment, verify:

- [ ] All roles can login
- [ ] CRUD operations work
- [ ] File uploads save correctly
- [ ] Responsive on mobile/tablet/desktop
- [ ] Theme toggle works
- [ ] Zoom stability (50%-200%)
- [ ] No console errors

---

## 📞 Support

**For detailed documentation, see:**  
`PROJECT_DOCUMENTATION.md` (Complete technical reference)

**For zoom/viewport issues, see:**  
`ZOOM_FIX_GUIDE.md` (Step-by-step implementation)

---

## 📄 License

Proprietary software. All rights reserved.

---

**Version:** 1.1.0  
**Last Updated:** 2026-01-20  
**Maintained By:** TIMON

---

## 🎉 Quick Tips

💡 **Tip 1:** Use `Ctrl + F` to search in this document  
💡 **Tip 2:** All HTML files are in role-specific folders  
💡 **Tip 3:** API files handle all backend operations  
💡 **Tip 4:** Theme persists across sessions via localStorage  
💡 **Tip 5:** Zoom-fix.css is already linked in all pages
