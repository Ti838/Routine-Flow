# 🎓 Routine Flow

**Complete Routine Management System for Educational Institutions**

[![Version](https://img.shields.io/badge/version-2.0-blue)](https://github.com)
[![License](https://img.shields.io/badge/license-Proprietary-red)](LICENSE)
[![Tailwind](https://img.shields.io/badge/CSS-Tailwind-38bdf8)](https://tailwindcss.com)

---

## ✨ Features

### 📤 File Management

- **Upload**: Drag-and-drop PDF/Image files (max 10MB)
- **Download**: One-click file downloads for students
- **Security**: File validation, secure storage, access control

### 🎨 Customization

- **Colors**: 10 premium color palette for highlighting
- **Stars**: Mark favorite/important classes
- **Priority**: 4 levels (Normal, Low, Medium, High)
- **Templates**: Daily, weekly, monthly custom routines

### 👥 User Roles

- **Admin**: Upload routines, manage users, view analytics
- **Teacher**: Upload class schedules, manage own uploads
- **Student**: Download files, customize routines, create templates

---

## 🚀 Quick Start

### 1. Database Setup

```bash
mysql -u root -p routine_flow_db < database/database.sql
mysql -u root -p routine_flow_db < database/enhanced_schema.sql
```

### 2. Configure

Edit `includes/db.php` with your database credentials

### 3. Run

```bash
# Using XAMPP: Start Apache and MySQL
# Or PHP built-in server:
php -S localhost:8000
```

### 4. Access

Open `http://localhost:8000`

---

## 📁 Structure

```
Routine Flow/
├── api/              # 12 Backend APIs
├── assets/           # CSS, JS, Images
├── database/         # SQL schemas
├── includes/         # PHP utilities
├── admin/            # Admin pages
├── teacher/          # Teacher pages
├── student/          # Student pages
├── uploads/          # File storage
├── index.html        # Landing page
├── login.html        # Login portal
└── login.php         # Authentication
```

---

## 🎨 Tech Stack

- **Frontend**: HTML5, Tailwind CSS, JavaScript
- **Backend**: PHP 7.4+
- **Database**: MySQL 5.7+
- **Design**: Pure Tailwind CSS with custom config

---

## 📚 Documentation

- **[FEATURES.md](FEATURES.md)** - Complete feature list
- **[DEPLOYMENT.md](DEPLOYMENT.md)** - Deployment guide
- **[PROJECT_COMPLETE.md](PROJECT_COMPLETE.md)** - Project summary

---

## 🔐 Security

- File type & size validation
- SQL injection prevention (prepared statements)
- XSS prevention (input sanitization)
- Role-based access control
- Secure file storage

---

## 📄 License

**© 2025 Timon Biswas. All Rights Reserved.**

Proprietary software. Unauthorized use, reproduction, or distribution is prohibited.

---

## 👨‍💻 Developer

**Timon Biswas**  
Email: <timonbiswas33@gmail.com>  
Version: 2.0 (Pure Tailwind Edition)

---

**Built with ❤️ using Tailwind CSS**
