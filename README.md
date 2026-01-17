# Routine Flow

**Academic Scheduling & Routine Management System**

[![License](https://img.shields.io/badge/License-Proprietary-red.svg)](LICENSE)
[![PHP](https://img.shields.io/badge/PHP-7.4+-blue.svg)](https://php.net)
[![MySQL](https://img.shields.io/badge/MySQL-5.7+-orange.svg)](https://mysql.com)

---

## 📋 Overview

**Routine Flow** is a comprehensive academic scheduling system designed for educational institutions. It provides role-based access for Admins, Teachers, and Students with powerful features for managing class schedules, routines, and academic resources.

---

## ✨ Key Features

### 🔴 Admin Features

- User management (Create, Edit, Delete)
- Department management
- Routine creation with 10 color options
- **Professional Analytics Dashboard** with pie charts and graphs
- Activity logging and monitoring
- System-wide settings control

### 🟢 Teacher Features

- Create and manage class routines
- Upload routine PDFs for students
- View department schedules
- Today's schedule overview

### 🔵 Student Features

- **Custom Routine Builder** with time-based filters (Morning/Afternoon/Evening)
- **Star marking** for important classes
- **Custom color coding** for personalization
- Download routine PDFs
- Today's classes with live clock
- Weekly schedule view
- Search and filter functionality

---

## 🛠️ Technology Stack

**Backend:**

- PHP 7.4+
- MySQL/MariaDB
- PDO (Database abstraction)
- Session-based authentication

**Frontend:**

- HTML5
- Tailwind CSS
- JavaScript (ES6+)
- Chart.js (Analytics)
- Remix Icons

**Security:**

- Password hashing (bcrypt)
- Prepared statements (SQL injection prevention)
- reCAPTCHA integration
- Session management
- CSRF protection

---

## 📦 Installation

### Requirements

- PHP 7.4 or higher
- MySQL 5.7 or MariaDB 10.2+
- Apache/Nginx web server
- 100MB+ disk space

### Setup Steps

1. **Clone or Download**

   ```bash
   # Download the project files
   ```

2. **Database Setup**

   ```bash
   # Create a MySQL database
   mysql -u root -p
   CREATE DATABASE routine_flow;
   
   # Import the schema
   mysql -u root -p routine_flow < database/routine_flow_final.sql
   ```

3. **Configure Database**

   ```php
   // Edit includes/db.php
   $host = 'localhost';
   $dbname = 'routine_flow';
   $username = 'your_username';
   $password = 'your_password';
   ```

4. **Set Permissions**

   ```bash
   # Create uploads directory
   mkdir -p uploads/routines
   chmod 755 uploads
   chmod 755 uploads/routines
   ```

5. **Configure reCAPTCHA** (Optional)
   - Get keys from [Google reCAPTCHA](https://www.google.com/recaptcha)
   - Update keys in `forgot_password.php`

6. **Access the System**

   ```
   http://localhost/Routine-Flow/
   ```

---

## 🎯 Default Login Credentials

After importing the database, use these credentials:

**Admin:**

- Username: `admin`
- Password: `admin123`

**Teacher:**

- Username: `teacher1`
- Password: `teacher123`

**Student:**

- Username: `student1`
- Password: `student123`

⚠️ **Change these passwords immediately after first login!**

---

## 📁 Project Structure

```
Routine Flow/
├── admin/              # Admin dashboard and pages
├── teacher/            # Teacher dashboard and pages
├── student/            # Student dashboard and pages
├── shared/             # Shared pages (profile, settings)
├── api/                # API endpoints
├── includes/           # PHP utilities and helpers
├── assets/             # CSS, JS, images
├── database/           # SQL schema
├── uploads/            # User uploaded files
├── views/              # Public pages (landing, login)
└── LICENSE             # Proprietary license
```

---

## 🔒 License

**PROPRIETARY SOFTWARE - ALL RIGHTS RESERVED**

Copyright © 2026 Timon Biswas

This software is proprietary and confidential. Unauthorized copying, distribution, modification, or use of this software is strictly prohibited and may result in legal action.

See [LICENSE](LICENSE) file for full terms and conditions.

**This is NOT open source software.**

---

## 📞 Support & Contact

**Developer:** Timon Biswas

For licensing inquiries, support, or questions:

- Email: [Your Email]
- Project: Routine Flow v1.0

---

## 🚀 Features Highlights

✅ Role-based access control (Admin, Teacher, Student)  
✅ Professional analytics with Chart.js  
✅ Time-based routine filtering  
✅ PDF export with customizations  
✅ Dark mode support  
✅ Responsive design  
✅ Search and filter functionality  
✅ Secure authentication  
✅ Session monitoring for multi-tab support  
✅ Star marking and color coding  
✅ Real-time data updates  

---

## ⚠️ Important Notes

- **Security:** Change default passwords immediately
- **Backup:** Regular database backups recommended
- **Updates:** Keep PHP and MySQL updated
- **Production:** Use HTTPS in production environment
- **License:** Commercial use requires separate licensing

---

## 📝 Version History

**v1.0** (January 2026)

- Initial release
- Complete admin, teacher, and student modules
- Analytics dashboard with charts
- Time-based filtering
- Multi-tab session support
- Comprehensive security features

---

**Developed with ❤️ by Timon Biswas**

*Routine Flow - Making Academic Scheduling Simple and Efficient*
