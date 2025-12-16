# PROJECT PROPOSAL
**Project Name:** Routine Flow - Smart Routine Management System

---

## 1. Title
**Routine Flow: A Web-Based Routine Management System**

---

## 2. Problem Statement
In many educational institutions today, managing class schedules is still a manual and inefficient process.
*   **Manual & Error-Prone:** Routines are often created by hand or in static spreadsheets, leading to scheduling conflicts (e.g., two teachers assigned to the same room).
*   **Distribution Issues:** Whenever a change is made, the entire routine must be re-printed and re-distributed to everyone.
*   **Accessibility:** Students detailed routine is often buried in a large master schedule, making it hard to find their specific class times quickly.
*   **Lack of Personalization:** Teachers and students see a generic view rather than a dashboard tailored to their specific needs.

---

## 3. Target Users & Personas
This system is designed for three distinct user groups:

### 👤 **Admin (The Organizer)**
*   **Goal:** Efficiently manage the entire institute's schedule.
*   **Needs:** Full control to add/remove users, manage departments, and create/edit the Master Routine.
*   **Pain Point:** Hates dealing with complaints about double-booked rooms.

### 👨‍🏫 **Teacher (The Instructor)**
*   **Goal:** Know exactly where to be and when.
*   **Needs:** A simple view of their personal weekly schedule and a calendar for upcoming events.
*   **Pain Point:** Often misses updates to the routine if they aren't notified.

### 🎓 **Student (The Learner)**
*   **Goal:** Attend classes on time without confusion.
*   **Needs:** quick access to "Today's Routine" on their mobile phone.
*   **Pain Point:** Confusion over room numbers or sudden cancelled classes.

---

## 4. Proposed Solution
**Routine Flow** offers a digital transformation of the scheduling process:
*   **Centralized Web Platform:** A Single Page Application (simulated) where the Admin updates the routine once, and it is instantly reflected for all users.
*   **Role-Based Dashboards:**
    *   **Admins** get a control panel for data entry.
    *   **Teachers** see only the classes they teach.
    *   **Students** see only the classes for their specific department/semester.
*   **Dynamic Data:** The system uses JavaScript to filter and display data dynamically, ensuring no static HTML tables need to be manually edited.
*   **Responsive Design:** Optimized for mobile devices, allowing students to check routines on the go.

---

## 5. Tech Stack Used
The project maintains a **Simple & Lightweight** philosophy by using the fundamental web technologies:

*   **Frontend Interface:**
    *   **HTML5:** For semantic structure and layout.
    *   **CSS3:** Uses Flexbox/Grid for responsiveness, CSS Variables for theming (Dark/Light mode), and Keyframes for animations.
*   **Logic & Function.:**
    *   **Vanilla JavaScript (ES6):** Handles all logic, DOM manipulation, authentication, and routing without any heavy frameworks.
*   **Data Persistence:**
    *   **LocalStorage API:** Acts as a client-side database to save users and routines permanently in the browser.

---

## 6. Rough Timeline & Milestones
The development is structured into a 4-week execution plan:

*   **Week 1: Planning & Design**
    *   [x] Define requirement and user roles.
    *   [x] Create UI Mockups (Login Page, Dashboard Layouts).
    *   [x] Set up project folder structure.

*   **Week 2: Core Development**
    *   [x] Implement Authentication Logic (Login/Signup).
    *   [x] Create "Mock Database" structure in JavaScript.
    *   [x] Build the Admin Dashboard (User & Dept Management).

*   **Week 3: Feature Implementation**
    *   [x] Develop Student & Teacher personalized views.
    *   [x] Implement Theme Toggle (Dark Mode).
    *   [x] Build the "Master Routine" creator interface.

*   **Week 4: Polish & Testing (Current Phase)**
    *   [x] Fix UI responsiveness (Mobile view).
    *   [x] Standardize design across all pages.
    *   [ ] Final bug fixes and code optimization.

---

## 7. Future Improvement Plans
To scale this project from a prototype to a production-ready system, the following upgrades are planned:

*   **Backend Integration:** Migrate from LocalStorage to a real database (MySQL or Firebase) to allow data sharing across different devices.
*   **Conflict Detection:** Implement an algorithm to automatically warn Admins if a teacher or room is double-booked.
*   **Export Features:** Add a "Download as PDF" button so users can save their routine offline.
*   **Notification System:** Send email or SMS alerts to students when a class is cancelled or rescheduled.
*   **Search Functionality:** Allow searching for specific teachers or courses within the master routine.

---
*Created by Routine Flow Team*
