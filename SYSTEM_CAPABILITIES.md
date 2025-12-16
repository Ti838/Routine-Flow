# SYSTEM CAPABILITIES REPORT
**System Name:** Routine Flow  
**Document Type:** Functional Capabilities & Workflow Guide

---

## 1. OVERVIEW
This report details the specific tasks and operations ("works") that can be performed using the **Routine Flow** system. It serves as a guide to the functional scope of the application, demonstrating how different users facilitate the academic routine process.

---

## 2. ADMINISTRATIVE CAPABILITIES
The **Admin** has full control over the system's data and configuration.

### 👥 User Management
*   **Create Users:** Admins can register new Teachers and Students into the system, assigning them unique IDs and passwords.
*   **Update Profiles:** Edit existing user details (Name, Department, Role) to correct errors.
*   **Remove Users:** Delete old or inactive accounts to keep the system clean.
*   **View User Directory:** Search and browse a complete list of all registered members.

### 🏢 Department Management
*   **Create Departments:** Add new academic streams (e.g., "Computer Science", "Business Administration").
*   **Manage Batches:** Organize students into semesters or sections within a department.

### 📅 Routine Orchestration (The Core Work)
*   **Create Master Routine:** The Admin performs the primary task of scheduling.
    *   Select Department & Semester.
    *   Assign a **Subject**, **Teacher**, **Room No**, and **Time Slot**.
    *   *System Action:* The system saves this slot and instantly pushes it to the relevant Student and Teacher dashboards.
*   **Modify Schedule:** Update time slots or change assigned teachers in real-time.

---

## 3. TEACHER CAPABILITIES
Teachers use the system to manage their personal time and obligations.

### 🗓️ Personal Scheduling
*   **View "My Routine":** Instead of searching through a large master file, teachers see a filtered view showing *only* the classes they are teaching.
*   **Check Workload:** Quickly assess how many classes they have in a day or week.

### 📌 Academic Planning
*   **Access Calendar:** View upcoming academic events, holidays, or exam dates (via the Calendar feature).
*   **Profile Management:** Update contact information so students can reach them.

---

## 4. STUDENT CAPABILITIES
Students use the system to stay organized and punctual.

### 🕒 Daily Tracking
*   **"Today's Routine":** A highlight feature that shows *only* the classes scheduled for the current day.
*   **Next Class Indicator:** Helps students identify where they need to be immediately.

### 📆 Weekly Planning
*   **View Full Timetable:** Access the complete weekly schedule to plan study sessions or project work.
*   **Identify Details:** Easily find out the Room Number and Teacher Name for any specific subject without asking peers.

---

## 5. GENERAL SYSTEM CAPABILITIES
Features available to all users to enhance experience.

*   **Authentication & Security:** Secure login flow ensures Students cannot access Admin features.
*   **Theme Personalization:** Toggle between **Dark Mode** (for low light) and **Light Mode** to reduce eye strain.
*   **Offline Access:** The system saves data locally, allowing users to check the last loaded routine even without an active internet connection (via LocalStorage).

---

## 6. WORKFLOW SUMMARY
| Task | Who Performs It? | Result |
| :--- | :--- | :--- |
| **New Semester Setup** | Admin | Departments and generic routine structure created. |
| **Student Registration** | Admin | Students get check-in credentials. |
| **Class Scheduling** | Admin | A specific "Class" is linked to a "Time" and "Room". |
| **Routine Check** | Student/Teacher | They log in and instantly see their schedule. |
| **Emergency Update** | Admin | Changes room no; Students see update immediately. |

---
*Routine Flow System Documentation*
