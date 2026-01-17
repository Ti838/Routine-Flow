# Routine Flow - Complete System Overview

**Academic Scheduling & Routine Management System**

---

## 📋 What This System Can Do

### 🔴 Admin Capabilities

**Dashboard & Overview:**

- View total users, departments, routines, and activities
- Real-time system statistics
- Quick access to all management sections

**User Management:**

- ✅ Create new users (Admin, Teacher, Student)
- ✅ Edit user details (name, email, username, role)
- ✅ Delete users with confirmation
- ✅ View all users in organized table
- ✅ Search and filter users
- ✅ Manage user permissions

**Department Management:**

- ✅ Create departments with name and code
- ✅ Edit department information
- ✅ Delete departments
- ✅ View all departments

**Routine Creation & Management:**

- ✅ Create class routines with:
  - Subject name
  - Teacher assignment
  - Department and semester (1st-4th Year, 1st-2nd Sem)
  - Day of week
  - Start and end time
  - Room number
  - **10 Color options** (Indigo, Rose, Emerald, Amber, Sky, Violet, Fuchsia, Cyan, Orange, Lime)
- ✅ View all routines in organized table
- ✅ Search by subject, teacher, or department
- ✅ Filter by department
- ✅ Delete routines
- ✅ Color-coded visual organization

**Analytics Dashboard:**

- ✅ **Summary Statistics Cards:**
  - Total active classes
  - Total faculty members
  - Total classrooms used
  - Total departments
- ✅ **Professional Charts (Chart.js):**
  - **Classroom Occupancy** - Bar chart showing top 10 most-used rooms
  - **Faculty Workload** - Horizontal bar chart of teacher class counts
  - **Department Distribution** - **Pie/Doughnut chart** with percentages and legend
  - **Weekly Distribution** - Line chart showing classes per day
- ✅ Print-friendly report generation
- ✅ Real-time data from database

**System Settings:**

- ✅ Configure system-wide settings
- ✅ Manage maintenance mode
- ✅ View activity logs
- ✅ System configuration

---

### 🟢 Teacher Capabilities

**Dashboard:**

- ✅ View assigned classes
- ✅ Today's schedule overview
- ✅ Quick statistics
- ✅ Department routines

**Routine Creation:**

- ✅ Create routines for their classes
- ✅ Same features as admin (subject, time, room, color)
- ✅ Department-specific creation
- ✅ Semester selection

**Department View:**

- ✅ View all routines in their department
- ✅ Filter by semester
- ✅ See complete schedule
- ✅ Color-coded display

**Today's Schedule:**

- ✅ View today's classes
- ✅ See upcoming sessions
- ✅ Color-coded display
- ✅ Time-based organization

**File Upload:**

- ✅ Upload routine PDFs for students
- ✅ Add descriptions and metadata
- ✅ Manage uploaded files
- ✅ Department and semester tagging

---

### 🔵 Student Capabilities

**Dashboard:**

- ✅ **Next Session Display:**
  - Shows upcoming class with countdown
  - Subject name and time
  - "Done Today" when no more classes
- ✅ **Today's Classes:**
  - All classes for current day
  - Time, subject, teacher, room
  - Color-coded by subject
- ✅ **Department Routines:**
  - Download uploaded PDF routines
  - View official schedules
- ✅ **Quick Stats:**
  - Total classes this week
  - Completed classes today

**Today's View:**

- ✅ **Live Clock** - Real-time date and time
- ✅ **Class Cards** with:
  - Start time
  - Subject name
  - Teacher name
  - Room number
  - Color coding
  - **Star button** to mark important
  - Priority indicators
- ✅ **Search Functionality:**
  - Search by subject, teacher, or room
  - Real-time filtering
  - Smooth animations
- ✅ **Export to PDF:**
  - Print current view
  - Preserves all customizations

**Weekly View:**

- ✅ See entire week's schedule
- ✅ Day-by-day breakdown
- ✅ Color-coded classes
- ✅ Time slots organized
- ✅ Responsive grid layout

**Custom Routine Builder:**

- ✅ **Semester Selection:**
  - Choose any semester (1st-4th Year, 1st-2nd Sem)
- ✅ **Time Period Filters:**
  - ☀️ **Morning Classes** (8:00 AM - 11:59 AM)
  - 🌤️ **Afternoon Classes** (12:00 PM - 4:59 PM)
  - 🌙 **Evening Classes** (5:00 PM onwards)
  - Mix and match filters
- ✅ **Apply Filters Button:**
  - Instant filtering
  - Smooth animations
  - Shows count of visible classes
- ✅ **Export Filtered View:**
  - Download as PDF
  - Clean, professional format
  - Includes all customizations

**Personalization Features:**

- ✅ **Star Marking:**
  - Mark important classes
  - Filled star icon for starred items
  - Amber highlighting
  - Saved to database
- ✅ **Custom Colors:**
  - Change class colors
  - Personal color preferences
  - Saved to database
- ✅ **Priority Levels:**
  - Set class priorities (Normal, Low, Medium, High)
  - Visual indicators
- ✅ **Personal Notes:**
  - Add notes to classes
  - Private to student

**Profile Management:**

- ✅ Edit full name
- ✅ **Edit username** (login identifier)
- ✅ Update email
- ✅ Change password
- ✅ Upload profile picture
- ✅ **Optional Fields:**
  - Student ID (can be added later)
  - Semester
  - Gender
  - Department

---

## 🛠️ Technology Stack Used

### **Backend Technologies**

| Technology | Version | Purpose |
|------------|---------|---------|
| **PHP** | 7.4+ | Server-side logic, authentication, database operations |
| **MySQL** | 5.7+ / MariaDB 10.2+ | Database management system |
| **PDO** | Built-in | Database abstraction layer, prepared statements |
| **Sessions** | PHP Native | User authentication and state management |
| **bcrypt** | PHP Native | Password hashing algorithm |

### **Frontend Technologies**

| Technology | Version | Purpose |
|------------|---------|---------|
| **HTML5** | Latest | Semantic markup, structure |
| **Tailwind CSS** | 3.x (CDN) | Utility-first CSS framework |
| **JavaScript** | ES6+ | Client-side interactivity, AJAX |
| **Chart.js** | 4.x | Data visualization (pie charts, bar charts, line charts) |
| **Remix Icons** | 3.5.0 | Icon library |

### **Libraries & APIs**

| Library/API | Purpose |
|-------------|---------|
| **Google reCAPTCHA v2** | Bot protection on password reset |
| **html2pdf.js** | Client-side PDF generation |
| **Fetch API** | Modern AJAX requests |
| **LocalStorage API** | Theme persistence (dark/light mode) |

### **Security Implementations**

| Feature | Implementation |
|---------|----------------|
| **SQL Injection Prevention** | PDO Prepared Statements |
| **Password Security** | `password_hash()` with bcrypt |
| **Password Verification** | `password_verify()` |
| **Session Management** | PHP Sessions with regeneration |
| **CSRF Protection** | Session tokens |
| **XSS Protection** | `htmlspecialchars()` on outputs |
| **File Upload Validation** | MIME type and extension checks |
| **Authentication** | Role-based access control |

---

## 🗄️ Database Architecture

### **User Tables**

```sql
-- Admins Table
admins (
    id INT PRIMARY KEY AUTO_INCREMENT,
    full_name VARCHAR(100),
    username VARCHAR(50) UNIQUE,
    email VARCHAR(100) UNIQUE,
    password VARCHAR(255),
    gender ENUM('Male', 'Female', 'Other'),
    profile_pic VARCHAR(255),
    created_at TIMESTAMP
)

-- Teachers Table
teachers (
    id INT PRIMARY KEY AUTO_INCREMENT,
    full_name VARCHAR(100),
    username VARCHAR(50) UNIQUE,
    email VARCHAR(100) UNIQUE,
    password VARCHAR(255),
    teacher_id VARCHAR(50) [OPTIONAL],
    gender ENUM('Male', 'Female', 'Other'),
    department_id INT,
    profile_pic VARCHAR(255),
    created_at TIMESTAMP
)

-- Students Table
students (
    id INT PRIMARY KEY AUTO_INCREMENT,
    full_name VARCHAR(100),
    username VARCHAR(50) UNIQUE,
    email VARCHAR(100) UNIQUE,
    password VARCHAR(255),
    student_id VARCHAR(50) [OPTIONAL],
    gender ENUM('Male', 'Female', 'Other'),
    department_id INT,
    semester VARCHAR(20),
    profile_pic VARCHAR(255),
    created_at TIMESTAMP
)
```

### **Core Tables**

```sql
-- Departments
departments (id, name, code, created_at)

-- Routines
routines (
    id, department_id, department, semester,
    subject_name, teacher_name, room_number,
    day_of_week, start_time, end_time,
    color_tag, status, created_at
)

-- Student Customizations
student_routine_customizations (
    id, student_id, routine_id,
    color_code, is_starred, priority,
    notes, created_at, updated_at
)

-- Personal Events
personal_events (
    id, student_id, title,
    day_of_week, start_time, end_time,
    color, created_at
)

-- Routine Files (Uploaded PDFs)
routine_files (
    id, user_id, role, file_path,
    file_type, department, semester,
    description, created_at
)

-- Notices
notices (
    id, title, content, priority,
    created_by, created_at
)

-- Activity Log
activity_log (
    id, user_id, role, action,
    details, created_at
)

-- Download Logs
download_logs (
    id, attachment_id, student_id,
    downloaded_at
)
```

**Total Tables:** 12

---

## 🎨 Design System

### **Color Palette**

| Color | Hex Code | Usage |
|-------|----------|-------|
| **Indigo** | #6366f1 | Primary brand color |
| **Emerald** | #10b981 | Success states |
| **Amber** | #f59e0b | Warnings, highlights |
| **Red** | #ef4444 | Errors, danger |
| **Purple** | #8b5cf6 | Accents |
| **Cyan** | #06b6d4 | Info states |

### **Routine Color Tags (10 Options)**

- Indigo, Rose, Emerald, Amber, Sky
- Violet, Fuchsia, Cyan, Orange, Lime

### **Typography**

- **Font Family:** System fonts (optimized for performance)
- **Headings:** Bold, tracking-tight
- **Body:** Regular weight, readable
- **Labels:** Uppercase, tracking-widest

### **UI Components**

- **Rounded Corners:** 24px-40px for cards
- **Shadows:** Soft, layered shadows
- **Gradients:** Smooth color transitions
- **Animations:** 300ms transitions
- **Dark Mode:** Full support with proper contrast

---

## 🔐 Authentication & Security

### **Login System**

- ✅ **Flexible Login:** Username OR email
- ✅ **Auto Role Detection:** No manual role selection
- ✅ **Password Hashing:** bcrypt algorithm
- ✅ **Session Management:** Secure PHP sessions
- ✅ **Session Regeneration:** Prevents session fixation
- ✅ **Multi-tab Support:** Session monitoring

### **Registration**

- ✅ Full name, username, email, password
- ✅ Role selection (Admin, Teacher, Student)
- ✅ Gender selection
- ✅ Department assignment
- ✅ Password confirmation
- ✅ Duplicate email/username check

### **Password Reset**

- ✅ Email validation
- ✅ **reCAPTCHA protection**
- ✅ New password with confirmation
- ✅ Secure password update

### **Optional Fields**

- ✅ `student_id` - Can be added later
- ✅ `teacher_id` - Can be added later
- ✅ Not required for login

---

## 📊 Key Features Implementation

### **Time-Based Filtering**

```javascript
// Morning: 8:00 AM - 11:59 AM (480-719 minutes)
// Afternoon: 12:00 PM - 4:59 PM (720-1019 minutes)
// Evening: 5:00 PM+ (1020+ minutes)

function applyFilters() {
    const timeInMinutes = hours * 60 + minutes;
    // Filter logic based on selected time periods
}
```

### **Star Marking**

```sql
-- Toggle star status
UPDATE student_routine_customizations 
SET is_starred = NOT is_starred 
WHERE student_id = ? AND routine_id = ?
```

### **Search Functionality**

```javascript
// Real-time search with data attributes
cards.forEach(card => {
    const subject = card.getAttribute('data-subject');
    const teacher = card.getAttribute('data-teacher');
    const room = card.getAttribute('data-room');
    
    if (matches query) {
        card.style.display = '';
    }
});
```

### **Analytics Charts**

```javascript
// Chart.js implementation
new Chart(ctx, {
    type: 'doughnut', // or 'bar', 'line'
    data: {
        labels: data.map(i => i.label),
        datasets: [{
            data: data.map(i => i.value),
            backgroundColor: colors
        }]
    }
});
```

---

## 📱 Browser Support

- ✅ Chrome 90+
- ✅ Firefox 88+
- ✅ Safari 14+
- ✅ Edge 90+
- ✅ Mobile browsers (iOS Safari, Chrome Mobile)

---

## 🚀 Performance Features

- ✅ **Database Indexing** on frequently queried columns
- ✅ **Prepared Statements** for query caching
- ✅ **CDN Usage** for Tailwind CSS and Chart.js
- ✅ **Lazy Loading** for images
- ✅ **Session-based** caching for user data
- ✅ **Optimized Queries** with proper JOINs

---

## 📝 API Endpoints

| Endpoint | Method | Purpose |
|----------|--------|---------|
| `/api/customize_routine.php` | POST | Save student customizations (star, color, priority) |
| `/api/delete_routine.php` | POST | Delete routine entry |
| `/api/download_routine.php` | GET | Download uploaded PDF files |
| `/api/get_analytics_data.php` | GET | Fetch analytics data for charts |
| `/api/reset_password.php` | POST | Reset user password |
| `/api/student_scheduler.php` | POST | Manage personal events |
| `/api/check_session.php` | GET | Check current session status |

---

## 📁 File Structure

```
Routine Flow/
├── admin/                      # Admin dashboard and pages
│   ├── analytics.php          # Analytics with charts
│   ├── create-routine.php     # Create new routine
│   ├── dashboard.php          # Admin dashboard
│   ├── departments.php        # Department management
│   ├── manage_routines.php    # View/manage routines
│   ├── users.php              # User management
│   └── views/                 # HTML templates
├── teacher/                    # Teacher pages
│   ├── create-routine.php
│   ├── dashboard.php
│   ├── department.php
│   └── today.php
├── student/                    # Student pages
│   ├── custom-routine.php     # Custom routine builder
│   ├── dashboard.php
│   ├── today.php
│   └── weekly.php
├── shared/                     # Shared pages
│   ├── profile.php
│   └── settings.php
├── api/                        # API endpoints
│   ├── customize_routine.php
│   ├── delete_routine.php
│   ├── download_routine.php
│   ├── get_analytics_data.php
│   ├── reset_password.php
│   ├── student_scheduler.php
│   └── check_session.php
├── includes/                   # Shared PHP files
│   ├── auth_check.php         # Authentication
│   ├── db.php                 # Database connection
│   ├── file_handler.php       # File uploads
│   └── layout.php             # Navbar/Sidebar
├── assets/                     # Static assets
│   ├── css/
│   ├── js/
│   │   ├── theme.js           # Dark mode toggle
│   │   ├── session-monitor.js # Multi-tab support
│   │   └── modules/
│   │       ├── export.js      # PDF export
│   │       └── search.js      # Search functionality
│   └── img/
│       ├── favicon.png        # Logo
│       └── hero-bg.png
├── database/
│   └── routine_flow_final.sql # Database schema
├── views/                      # Public pages
│   ├── index.html             # Landing page
│   └── login.html             # Login/Register
├── uploads/                    # User uploaded files
│   └── routines/
├── login.php                   # Login handler
├── register.php                # Registration handler
├── forgot_password.php         # Password reset page
├── maintenance.php             # Maintenance mode
├── LICENSE                     # Proprietary license
├── README.md                   # Project documentation
└── SYSTEM_OVERVIEW.md         # This file
```

---

## ✨ System Capabilities Summary

**What This System Can Do:**

✅ Manage academic schedules for entire institution  
✅ Support unlimited users across 3 roles (Admin, Teacher, Student)  
✅ Create and organize routines with visual color coding  
✅ Allow students to personalize their schedules (star, color, priority)  
✅ Filter routines by time periods (morning/afternoon/evening)  
✅ Generate professional analytics and reports with pie charts  
✅ Export schedules to PDF with customizations preserved  
✅ Search and filter across all data in real-time  
✅ Secure authentication with flexible login options (username or email)  
✅ Dark mode for comfortable viewing  
✅ Responsive design for all devices (desktop, tablet, mobile)  
✅ Professional, modern interface throughout  
✅ Multi-tab session monitoring  
✅ File upload and download management  

---

## 🎯 Production Ready

**Status:** ✅ Fully tested and deployable

**Requirements:**

- PHP 7.4+
- MySQL 5.7+ / MariaDB 10.2+
- Apache/Nginx web server
- 100MB+ disk space
- SSL certificate (recommended for production)

**Security:**

- ✅ SQL injection prevention
- ✅ XSS protection
- ✅ CSRF protection
- ✅ Password hashing
- ✅ reCAPTCHA integration
- ✅ Session security
- ✅ File upload validation

---

## 📞 Developer Information

**Developer:** Timon Biswas  
**Project:** Routine Flow v1.0  
**Date:** January 2026  
**License:** Proprietary (See LICENSE file)

---

**Copyright © 2026 Timon Biswas. All Rights Reserved.**

*This is proprietary software. Unauthorized use, copying, or distribution is strictly prohibited.*
