# Routine Flow - Deployment Guide

## 📋 Prerequisites

Before deploying Routine Flow, ensure you have:

- ✅ **Web Server**: Apache or Nginx
- ✅ **PHP**: Version 7.4 or higher
- ✅ **MySQL**: Version 5.7 or higher (or MariaDB 10.2+)
- ✅ **XAMPP/WAMP/MAMP** (for local development)

---

## 🚀 Installation Steps

### Step 1: Setup Database

1. **Create Database**

   ```sql
   CREATE DATABASE routine_flow_db;
   ```

2. **Import Base Schema**

   ```bash
   mysql -u root -p routine_flow_db < database/database.sql
   ```

3. **Import Enhanced Schema**

   ```bash
   mysql -u root -p routine_flow_db < database/enhanced_schema.sql
   ```

### Step 2: Configure Database Connection

Edit `includes/db.php`:

```php
$host = 'localhost';
$dbname = 'routine_flow_db';
$username = 'root';  // Change for production
$password = '';      // Change for production
```

### Step 3: Create Upload Directories

```bash
mkdir -p uploads/routines/admin
mkdir -p uploads/routines/teacher
chmod 755 uploads
chmod 755 uploads/routines
chmod 755 uploads/routines/admin
chmod 755 uploads/routines/teacher
```

**Windows (PowerShell)**:

```powershell
New-Item -Path "uploads\routines\admin" -ItemType Directory -Force
New-Item -Path "uploads\routines\teacher" -ItemType Directory -Force
```

### Step 4: Configure PHP Settings

Edit `php.ini` (or `.htaccess`):

```ini
upload_max_filesize = 10M
post_max_size = 10M
max_execution_time = 300
memory_limit = 128M
```

### Step 5: Set File Permissions

**Linux/Mac**:

```bash
chmod 644 *.php
chmod 644 api/*.php
chmod 644 includes/*.php
chmod 755 uploads/routines/admin
chmod 755 uploads/routines/teacher
```

**Windows**: Ensure IIS_IUSRS or IUSR has write permissions to `uploads/` directory.

---

## 🔐 Security Configuration

### 1. Protect Upload Directory

Create `.htaccess` in `uploads/` directory:

```apache
# Prevent direct access to PHP files
<FilesMatch "\.php$">
    Order Deny,Allow
    Deny from all
</FilesMatch>

# Allow only specific file types
<FilesMatch "\.(pdf|png|jpg|jpeg)$">
    Order Allow,Deny
    Allow from all
</FilesMatch>
```

### 2. Enable HTTPS (Production)

Update all links to use HTTPS:

```javascript
// In JavaScript files
const apiUrl = 'https://yourdomain.com/api/';
```

### 3. Secure Database Credentials

- Use environment variables for production
- Never commit `includes/db.php` with real credentials
- Use strong passwords

---

## 🧪 Testing the Installation

### 1. Test Database Connection

Visit: `http://localhost/routine-flow/test-db.php`

Create `test-db.php`:

```php
<?php
require_once 'includes/db.php';
echo "Database connected successfully!";
?>
```

### 2. Test File Upload

1. Login as admin
2. Navigate to "Create Routine"
3. Upload a test PDF file
4. Check if file appears in `uploads/routines/admin/YYYY-MM/`

### 3. Test Student Features

1. Login as student
2. View today's routine
3. Test color picker
4. Test star marking
5. Test file download

---

## 📊 Sample Data (Optional)

### Create Test Users

```sql
-- Admin User
INSERT INTO admins (name, email, password) 
VALUES ('Admin User', 'admin@example.com', '$2y$10$...');  -- Use password_hash()

-- Teacher User
INSERT INTO teachers (name, email, password, gender, department, designation) 
VALUES ('John Doe', 'teacher@example.com', '$2y$10$...', 'Male', 'Science', 'Professor');

-- Student User
INSERT INTO students (student_id, name, email, password, gender, department, semester) 
VALUES ('STU-2025-001', 'Jane Smith', 'student@example.com', '$2y$10$...', 'Female', 'Science', 'Fall 2025');
```

### Create Sample Routines

```sql
INSERT INTO routines (subject_name, day_of_week, start_time, end_time, room_number, department, semester, teacher_id) 
VALUES 
('Mathematics', 'Monday', '09:00:00', '10:30:00', 'Room 301', 'Science', 'Fall 2025', 1),
('Physics', 'Monday', '11:00:00', '12:30:00', 'Lab 102', 'Science', 'Fall 2025', 1),
('Chemistry', 'Tuesday', '09:00:00', '10:30:00', 'Room 205', 'Science', 'Fall 2025', 1);
```

---

## 🌐 Production Deployment

### Option 1: Shared Hosting

1. **Upload Files via FTP**
   - Upload all files to `public_html/` or `www/`
   - Ensure `uploads/` directory is writable

2. **Import Database**
   - Use phpMyAdmin or cPanel MySQL
   - Import both SQL files

3. **Update Configuration**
   - Edit `includes/db.php` with hosting credentials
   - Update file paths if needed

### Option 2: VPS/Cloud Server

1. **Install LAMP Stack**

   ```bash
   sudo apt update
   sudo apt install apache2 mysql-server php php-mysql
   ```

2. **Clone Repository**

   ```bash
   cd /var/www/html
   git clone <your-repo-url> routine-flow
   ```

3. **Configure Apache**

   ```apache
   <VirtualHost *:80>
       ServerName yourdomain.com
       DocumentRoot /var/www/html/routine-flow
       <Directory /var/www/html/routine-flow>
           AllowOverride All
           Require all granted
       </Directory>
   </VirtualHost>
   ```

4. **Enable Rewrite Module**

   ```bash
   sudo a2enmod rewrite
   sudo systemctl restart apache2
   ```

---

## 🔄 Maintenance

### Backup Database

```bash
# Daily backup
mysqldump -u root -p routine_flow_db > backup_$(date +%Y%m%d).sql
```

### Clean Old Files

Create a cron job to delete old files:

```bash
# Delete files older than 365 days
find uploads/routines -type f -mtime +365 -delete
```

### Monitor Disk Space

```bash
du -sh uploads/routines/*
```

---

## 🐛 Troubleshooting

### File Upload Fails

**Issue**: "Failed to upload file"

**Solution**:

1. Check `uploads/` directory permissions
2. Verify PHP `upload_max_filesize` setting
3. Check error logs: `tail -f /var/log/apache2/error.log`

### Database Connection Error

**Issue**: "Database connection failed"

**Solution**:

1. Verify MySQL is running: `sudo systemctl status mysql`
2. Check credentials in `includes/db.php`
3. Ensure database exists: `SHOW DATABASES;`

### Customizations Not Saving

**Issue**: Color/star changes don't persist

**Solution**:

1. Check browser console for JavaScript errors
2. Verify session is active
3. Check `student_routine_customizations` table exists

---

## 📞 Support

For issues or questions:

- Email: <timonbiswas33@gmail.com>
- Check error logs
- Review browser console

---

## ✅ Post-Deployment Checklist

- [ ] Database imported successfully
- [ ] Upload directories created and writable
- [ ] PHP settings configured
- [ ] Test admin file upload
- [ ] Test teacher file upload
- [ ] Test student download
- [ ] Test customization features
- [ ] HTTPS enabled (production)
- [ ] Backup system configured
- [ ] Error logging enabled
- [ ] Security headers configured

---

## 🎉 Deployment Complete

Your Routine Flow application is now live and ready to use!

**Default Login Credentials** (Change immediately):

- Admin: <admin@example.com> / admin123
- Teacher: <teacher@example.com> / teacher123
- Student: <student@example.com> / student123

**Remember to**:

- Change default passwords
- Enable HTTPS in production
- Set up regular backups
- Monitor server logs
