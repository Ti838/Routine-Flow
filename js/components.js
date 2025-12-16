// ===================================
// ROUTINE FLOW - REUSABLE COMPONENTS
// JavaScript functions to generate HTML components
// ===================================

// --- ICONS LIBRARY (Premium SVG Outlines) ---
const ICONS = {
  dashboard: `<svg class="svg-icon" viewBox="0 0 24 24"><rect x="3" y="3" width="7" height="7"></rect><rect x="14" y="3" width="7" height="7"></rect><rect x="14" y="14" width="7" height="7"></rect><rect x="3" y="14" width="7" height="7"></rect></svg>`,
  users: `<svg class="svg-icon" viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>`,
  departments: `<svg class="svg-icon" viewBox="0 0 24 24"><rect x="2" y="7" width="20" height="14" rx="2" ry="2"></rect><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"></path></svg>`,
  calendar: `<svg class="svg-icon" viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>`,
  profile: `<svg class="svg-icon" viewBox="0 0 24 24"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>`,
  settings: `<svg class="svg-icon" viewBox="0 0 24 24"><circle cx="12" cy="12" r="3"></circle><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"></path></svg>`,
  plus: `<svg class="svg-icon" viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>`,
  list: `<svg class="svg-icon" viewBox="0 0 24 24"><line x1="8" y1="6" x2="21" y2="6"></line><line x1="8" y1="12" x2="21" y2="12"></line><line x1="8" y1="18" x2="21" y2="18"></line><line x1="3" y1="6" x2="3.01" y2="6"></line><line x1="3" y1="12" x2="3.01" y2="12"></line><line x1="3" y1="18" x2="3.01" y2="18"></line></svg>`,
  edit: `<svg class="svg-icon" viewBox="0 0 24 24"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>`,
  trash: `<svg class="svg-icon" viewBox="0 0 24 24"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>`,
  logout: `<svg class="svg-icon" viewBox="0 0 24 24"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path><polyline points="16 17 21 12 16 7"></polyline><line x1="21" y1="12" x2="9" y2="12"></line></svg>`,
  moon: `<svg class="svg-icon" viewBox="0 0 24 24"><path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"></path></svg>`,
  sun: `<svg class="svg-icon" viewBox="0 0 24 24"><circle cx="12" cy="12" r="5"></circle><line x1="12" y1="1" x2="12" y2="3"></line><line x1="12" y1="21" x2="12" y2="23"></line><line x1="4.22" y1="4.22" x2="5.64" y2="5.64"></line><line x1="18.36" y1="18.36" x2="19.78" y2="19.78"></line><line x1="1" y1="12" x2="3" y2="12"></line><line x1="21" y1="12" x2="23" y2="12"></line><line x1="4.22" y1="19.78" x2="5.64" y2="18.36"></line><line x1="18.36" y1="5.64" x2="19.78" y2="4.22"></line></svg>`,
  book: `<svg class="svg-icon" viewBox="0 0 24 24"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path></svg>`,
  clock: `<svg class="svg-icon" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>`,
  bell: `<svg class="svg-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path><path d="M13.73 21a2 2 0 0 1-3.46 0"></path></svg>`,
  menu: `<svg class="svg-icon" viewBox="0 0 24 24"><line x1="3" y1="12" x2="21" y2="12"></line><line x1="3" y1="6" x2="21" y2="6"></line><line x1="3" y1="18" x2="21" y2="18"></line></svg>`
};

// --- NAVBAR COMPONENT ---

function generateNavbar() {
  const currentUser = getCurrentUser();
  if (!currentUser) return '';

  return `
        <nav class="navbar">
            <div class="navbar-content">
                <div class="navbar-brand">
                    <button class="icon-btn menu-toggle-btn" onclick="toggleSidebar()" style="margin-right: 10px;">
                        ${ICONS.menu}
                    </button>
                    <img src="../favicon.png" alt="Routine Flow Logo" class="navbar-logo">
                    <span class="navbar-title">Routine Flow</span>
                </div>
                <div class="navbar-actions">
                    <!-- Notification Bell -->
                    <div class="notification-wrapper" style="position: relative; margin-right: 10px;">
                        <button class="nav-btn" id="notifBtn" onclick="toggleNotifications()" aria-label="Notifications">
                            ${ICONS.bell}
                            <span class="badge-count" id="notifBadge" style="display: none;">0</span>
                        </button>
                        <!-- Dropdown -->
                        <div class="notification-dropdown" id="notifDropdown" style="display: none;">
                            <div class="dropdown-header">
                                <span>Notifications</span>
                                <button class="text-btn small" onclick="clearNotifications()">Clear</button>
                            </div>
                            <div class="dropdown-body" id="notifList">
                                <div class="empty-state-small">No new notifications</div>
                            </div>
                        </div>
                    </div>

                    <button class="theme-toggle" id="themeToggle" onclick="toggleTheme()" aria-label="Toggle theme">
                        <span id="themeIcon">${ICONS.moon}</span>
                    </button>
                    <div class="user-profile">
                        <div class="user-avatar">
                            ${currentUser.profileImage
      ? `<img src="${currentUser.profileImage}" alt="Data" style="width: 100%; height: 100%; object-fit: cover; border-radius: 50%;">`
      : currentUser.name.charAt(0).toUpperCase()}
                        </div>
                        <div class="user-info">
                            <div class="user-name">${currentUser.name}</div>
                            <div class="user-role">${currentUser.role}</div>
                        </div>
                    </div>
                </div>
            </div>
        </nav>
        </nav>
    `;
}

// --- FOOTER COMPONENT ---


function generateFooter() {
  const termsModalHTML = `
    <!-- Terms & Conditions Modal (Injected) -->
    <div id="termsModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Terms & Conditions</h2>
                <button class="close-modal" onclick="closeModal('termsModal')">×</button>
            </div>
            <div class="modal-body">
                <p><em>Last Updated: December 11, 2025</em></p>
                <p style="background: rgba(255, 243, 205, 0.5); padding: 15px; border-radius: 10px; border-left: 4px solid #ffc107; margin-bottom: 20px; color: var(--text-dark);">
                    <strong>⚠️ IMPORTANT NOTICE:</strong> This software is proprietary and confidential. All rights reserved by Timon Biswas.
                </p>

                <h3>1. Copyright & Ownership</h3>
                <p><strong>© 2025 Timon Biswas. All Rights Reserved.</strong></p>
                <p>Routine Flow and all associated intellectual property rights are the exclusive property of <strong>Timon Biswas</strong>.</p>

                <h3>2. License Grant</h3>
                <p>You are granted a limited license for personal, non-commercial educational use only.</p>
                <ul>
                    <li>No commercial use.</li>
                    <li>No redistribution or modification.</li>
                </ul>

                <h3>3. Prohibited Actions</h3>
                <p>You may not copy, modify, reverse engineer, or distribute this software without written permission.</p>

                <h3>4. Contact</h3>
                <p>For inquiries, please contact <strong>Timon Biswas</strong> at <a href="mailto:timonbiswas33@gmail.com">timonbiswas33@gmail.com</a>.</p>
                
                <div style="text-align: center; margin-top: 30px;">
                    <!-- Button removed by request -->
                </div>
            </div>
        </div>
    </div>
  `;

  return `
        <footer class="main-footer">
            <div class="footer-content">
                <p>&copy; ${new Date().getFullYear()} Routine Flow. All rights reserved.</p>
                <p class="developer-credit">Developed by <strong>Timon Biswas</strong></p>
                <div style="margin-top: 10px;">
                    <button class="footer-link-btn" onclick="openModal('termsModal')">
                        📜 Terms & Conditions
                    </button>
                </div>
            </div>
        </footer>
        ${termsModalHTML}
    `;
}


// --- SIDEBAR COMPONENT ---

function generateSidebar(role) {
  const menus = {
    Admin: [
      { icon: ICONS.dashboard, label: 'Dashboard', href: '../admin/dashboard.html' },
      { icon: ICONS.users, label: 'Users', href: '../admin/users.html' },
      { icon: ICONS.departments, label: 'Departments', href: '../admin/departments.html' },
      { icon: ICONS.calendar, label: 'Routines', href: '../admin/routines.html' },
      { icon: ICONS.profile, label: 'Profile', href: '../shared/profile.html' },
      { icon: ICONS.settings, label: 'Settings', href: '../shared/settings.html' }
    ],
    Teacher: [
      { icon: ICONS.dashboard, label: 'Dashboard', href: '../teacher/dashboard.html' },
      {
        icon: ICONS.calendar,
        label: 'Class Routine',
        children: [
          { icon: ICONS.plus, label: 'Create Routine', href: '../teacher/create-routine.html' },
          { icon: ICONS.calendar, label: 'View Calendar', href: '../teacher/schedule.html' }
        ]
      },
      { icon: ICONS.profile, label: 'Profile', href: '../shared/profile.html' },
      { icon: ICONS.settings, label: 'Settings', href: '../shared/settings.html' }
    ],
    Student: [
      { icon: ICONS.dashboard, label: 'Dashboard', href: '../student/dashboard.html' },
      { icon: ICONS.list, label: "Today's Routine", href: '../student/today.html' },
      { icon: ICONS.calendar, label: 'Weekly Routine', href: '../student/weekly.html' },
      { icon: ICONS.edit, label: 'My Custom Routine', href: '../student/custom-routine.html' },
      { icon: ICONS.profile, label: 'Profile', href: '../shared/profile.html' },
      { icon: ICONS.settings, label: 'Settings', href: '../shared/settings.html' }
    ]
  };

  const menuItems = menus[role] || [];

  /* Helper to render menu items recursively */
  function renderMenuItem(item) {
    if (item.children) {
      const childrenHTML = item.children.map(child => `
        <li class="sidebar-item sub-item">
            <a href="${child.href}" class="sidebar-link">
                <span class="sidebar-icon" style="font-size: 14px;">${child.icon}</span>
                <span style="font-size: 14px;">${child.label}</span>
            </a>
        </li>
      `).join('');

      // Check if any child is active to expand parent by default
      const isActive = item.children.some(child => window.location.href.includes(child.href.split('/').pop()));

      return `
        <li class="sidebar-item has-children ${isActive ? 'expanded' : ''}">
            <a href="javascript:void(0)" class="sidebar-link parent-link" onclick="toggleSubmenu(this)">
                <span class="sidebar-icon">${item.icon}</span>
                <span>${item.label}</span>
                <span class="dropdown-arrow" style="margin-left:auto; font-size:10px;">▼</span>
            </a>
            <ul class="sidebar-submenu ${isActive ? 'open' : ''}">
                ${childrenHTML}
            </ul>
        </li>
      `;
    }

    return `
      <li class="sidebar-item">
        <a href="${item.href}" class="sidebar-link">
          <span class="sidebar-icon">${item.icon}</span>
          <span>${item.label}</span>
        </a>
      </li>
    `;
  }

  const menuHTML = menuItems.map(renderMenuItem).join('');

  return `
    <aside class="sidebar" id="sidebar">
      <ul class="sidebar-menu">
        ${menuHTML}
        <li class="sidebar-item">
          <a href="#" class="sidebar-link" onclick="logout()">
            <span class="sidebar-icon">${ICONS.logout}</span>
            <span>Logout</span>
          </a>
        </li>
      </ul>
  `;
}

// Global Sidebar Toggle Function
// Global Sidebar Toggle Function
window.toggleSubmenu = function (element) {
  console.log("Toggling Submenu");
  const submenu = element.nextElementSibling;
  const arrow = element.querySelector('.dropdown-arrow');
  submenu.classList.toggle('open');

  if (submenu.classList.contains('open')) {
    if (arrow) arrow.style.transform = 'rotate(180deg)';
  } else {
    if (arrow) arrow.style.transform = 'rotate(0deg)';
  }
};

// --- STATS CARD COMPONENT ---

function createStatsCard(icon, value, label, color = 'primary') {
  return `
    <div class="stat-card-premium">
      <div class="stat-icon">
        ${icon.includes('<svg') ? icon : `<span style="font-size:24px">${icon}</span>`}
      </div>
      <div class="stat-value">${value}</div>
      <div class="stat-label">${label}</div>
    </div>
  `;
}

// --- ROUTINE CARD COMPONENT ---

function createRoutineCard(routine, showActions = false, currentUserId = null) {
  const isCompleted = currentUserId ? isTaskCompleted(routine.id, currentUserId) : false;
  const completedClass = isCompleted ? 'completed' : '';

  const actionsHTML = showActions ? `
    <div class="action-buttons">
      <button class="icon-btn" onclick="editRoutine('${routine.id}')" title="Edit">${ICONS.edit}</button>
      <button class="icon-btn danger" onclick="deleteRoutineConfirm('${routine.id}')" title="Delete">${ICONS.trash}</button>
    </div>
  ` : '';

  const checkboxHTML = currentUserId ? `
    <input type="checkbox" class="task-checkbox" 
           ${isCompleted ? 'checked' : ''}
           onchange="toggleTaskCompletion('${routine.id}', '${currentUserId}', this.checked)">
  ` : '';

  // Attachment Handler
  let attachmentHTML = '';
  if (routine.attachment && routine.attachment.data) {
    attachmentHTML = `
        <button class="btn btn-sm btn-outline-primary" style="margin-left: auto; width:auto; padding: 4px 10px; font-size: 12px;"
            onclick="downloadAttachment('${routine.attachment.name}', '${routine.attachment.type}', '${routine.id}')">
            📎 ${routine.attachment.name.length > 15 ? routine.attachment.name.substring(0, 12) + '...' : routine.attachment.name}
        </button>
  `;
  }

  // Announcement Handler
  const announcementHTML = routine.announcement ? `
    <div style="background: #fff3cd; color: #856404; padding: 8px; border-radius: 4px; font-size: 0.85rem; margin-top: 8px; border: 1px solid #ffeeba;">
    <strong>📢 Note:</strong> ${routine.announcement}
      </div>
  ` : '';

  return `
    <div class="routine-card ${completedClass}">
      <div class="routine-header">
        <div>
          <h3 class="routine-title">${routine.taskName}</h3>
          <div class="routine-time">
            ${ICONS.clock} ${formatTime(routine.timeStart)} - ${formatTime(routine.timeEnd)}
          </div>
          <div class="routine-time">
            ${ICONS.calendar} ${formatDateShort(routine.date)}
          </div>
        </div>
        ${checkboxHTML}
      </div>
      
      <p class="routine-description">${routine.description || ''}</p>
      ${announcementHTML}

<div class="routine-footer" style="display:flex; justify-content: space-between; align-items: center; width:100%;">
  <div class="routine-meta">
    <span class="badge badge-primary">${routine.department}</span>
    ${routine.room ? `<span class="badge badge-secondary ml-1">📍 ${routine.room}</span>` : ''}
  </div>
  ${attachmentHTML}
  ${actionsHTML}
</div>
    </div>
  `;
}

// Helper: Download Attachment (Global scope)
window.downloadAttachment = function (name, type, routineId) {
  // We need to fetch the routine again to get the full data if we passed partial data, 
  // but here we likely passed the full object. However, the onclick string limit might be an issue.
  // Better to fetch from ID.
  const routine = getRoutineById(routineId);
  if (!routine || !routine.attachment) {
    alert('Attachment not found');
    return;
  }

  const link = document.createElement('a');
  link.href = routine.attachment.data;
  link.download = routine.attachment.name;
  document.body.appendChild(link);
  link.click();
  document.body.removeChild(link);
}

// --- TASK CARD COMPONENT (Simplified) ---

function createTaskCard(task, studentId) {
  const isCompleted = isTaskCompleted(task.id, studentId);
  const completedClass = isCompleted ? 'completed' : '';

  return `
    <div class="task-card ${completedClass}">
    <input type="checkbox" class="task-checkbox"
      ${isCompleted ? 'checked' : ''}
      onchange="toggleTaskCompletion('${task.id}', '${studentId}', this.checked)">
      <div class="task-content">
        <div class="task-title">${task.taskName}</div>
        <div class="task-time">${formatTime(task.timeStart)} - ${formatTime(task.timeEnd)}</div>
      </div>
    </div>
`;
}

// --- USER PROFILE CARD COMPONENT ---

function createUserProfileCard(user) {
  const initials = getInitials(user.name);

  return `
    <div class="profile-card">
      <div class="profile-avatar">${initials}</div>
      <h2 class="profile-name">${user.name}</h2>
      <span class="profile-role">${user.role}</span>
      
      <div class="profile-info">
        <div class="profile-info-item">
          <span class="profile-info-label">User ID</span>
          <span class="profile-info-value">${user.userId}</span>
        </div>
        <div class="profile-info-item">
          <span class="profile-info-label">Email</span>
          <span class="profile-info-value">${user.email}</span>
        </div>
        <div class="profile-info-item">
          <span class="profile-info-label">Department</span>
          <span class="profile-info-value">${user.department}</span>
        </div>
        <div class="profile-info-item">
          <span class="profile-info-label">Gender</span>
          <span class="profile-info-value">${user.gender}</span>
        </div>
      </div>
    </div>
  `;
}

// --- USER TABLE ROW COMPONENT ---

function createUserTableRow(user) {
  return `
  <tr>
      <td>${user.userId}</td>
      <td>${user.name}</td>
      <td>${user.email}</td>
      <td><span class="badge badge-primary">${user.role}</span></td>
      <td>${user.department}</td>
      <td>${user.gender}</td>
      <td>
        <div class="action-buttons">
          <button class="icon-btn" onclick="editUser('${user.userId}')" title="Edit">${ICONS.edit}</button>
          <button class="icon-btn danger" onclick="deleteUserConfirm('${user.userId}')" title="Delete">${ICONS.trash}</button>
        </div>
      </td>
    </tr>
  `;
}

// --- CALENDAR GRID COMPONENT ---

function createCalendarGrid(startDate, routines, studentId = null) {
  const weekDates = getWeekDates(new Date(startDate));
  const dayNames = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];

  let html = '<div class="calendar">';
  html += '<div class="calendar-grid">';

  // Header row
  dayNames.forEach(day => {
    html += `<div class="calendar-day header"> ${day}</div>`;
  });

  // Date cells
  weekDates.forEach((date, index) => {
    const dayRoutines = routines.filter(r => r.date === date);
    const isToday = date === getCurrentDate();
    const activeClass = isToday ? 'active' : '';

    html += `
  <div class="calendar-day ${activeClass}" onclick="showRoutinesForDate('${date}')" style="position:relative;">
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom: 4px;">
            <span style="font-weight: 600;">${new Date(date).getDate()}</span>
            <button class="btn btn-sm btn-link p-0" 
                    style="font-size:16px; line-height:1; color: var(--primary-blue);" 
                    onclick="event.stopPropagation(); openQuickAddModal('${date}')" 
                    title="Quick Add Task">+</button>
        </div>
        <div style="font-size: 10px; color: var(--text-light);">
          ${dayRoutines.length} tasks
        </div>
      </div>
  `;
  });

  html += '</div>';
  html += '</div>';

  return html;
}

// --- PROGRESS BAR COMPONENT ---

function createProgressBar(percentage, label = 'Completion Progress') {
  return `
    <div class="progress-container">
      <div class="progress-header">
        <span class="progress-label">${label}</span>
        <span class="progress-value">${percentage}%</span>
      </div>
      <div class="progress-bar">
        <div class="progress-fill" style="width: ${percentage}%"></div>
      </div>
    </div>
  `;
}

// --- EMPTY STATE COMPONENT ---

function createEmptyState(icon, title, description, actionButton = null) {
  // If icon is emoji (short string), wrap or replace. If longer (SVG), use as is.
  const iconHtml = icon.length < 5 ? `<span style="font-size:48px">${icon}</span>` : icon;

  const buttonHTML = actionButton ? `
  <button class="btn btn-primary" onclick="${actionButton.onClick}">
    ${actionButton.label}
    </button>
  ` : '';

  return `
    <div class="empty-state">
      <div class="empty-icon">${iconHtml}</div>
      <h3 class="empty-title">${title}</h3>
      <p class="empty-description">${description}</p>
      ${buttonHTML}
    </div>
  `;
}

// --- DEPARTMENT SELECTOR COMPONENT ---

function createDepartmentSelector(selectedDepartment = '') {
  const departments = getAllDepartments();

  const options = departments.map(dept => `
  <option value="${dept}" ${dept === selectedDepartment ? 'selected' : ''}>
    ${dept}
    </option>
  `).join('');

  return `
  <select class="form-select" name="department" required>
    <option value="">Select Department</option>
      ${options}
    </select>
  `;
}

// --- USER ROLE SELECTOR COMPONENT ---

function createRoleSelector(selectedRole = '') {
  const roles = ['Admin', 'Teacher', 'Student'];

  return roles.map(role => `
  <label class="role-option ${role === selectedRole ? 'selected' : ''}">
    <input type="radio" name="role" value="${role}"
      ${role === selectedRole ? 'checked' : ''}
      onchange="selectRole(this)">
      <span class="role-label">${role}</span>
    </label>
`).join('');
}

// --- GENDER SELECTOR COMPONENT ---

function createGenderSelector(selectedGender = '') {
  const genders = ['Male', 'Female', 'Other'];

  return genders.map(gender => `
  <label class="gender-option ${gender === selectedGender ? 'selected' : ''}">
    <input type="radio" name="gender" value="${gender}"
      ${gender === selectedGender ? 'checked' : ''}
      onchange="selectGender(this)">
      ${gender}
    </label>
`).join('');
}

// --- HELPER: Toggle Task Completion ---

function toggleTaskCompletion(routineId, studentId, isChecked) {
  if (isChecked) {
    markTaskComplete(routineId, studentId);
    showToast('Task marked as complete!', 'success');
  } else {
    markTaskIncomplete(routineId, studentId);
    showToast('Task marked as incomplete', 'info');
  }

  // Reload page to update UI
  setTimeout(() => {
    window.location.reload();
  }, 500);
}

// --- HELPER: Select Role (for signup page) ---

function selectRole(input) {
  const options = document.querySelectorAll('.role-option');
  options.forEach(opt => opt.classList.remove('selected'));
  input.closest('.role-option').classList.add('selected');
}

// --- HELPER: Select Gender (for signup page) ---


function selectGender(input) {
  const options = document.querySelectorAll('.gender-option');
  options.forEach(opt => opt.classList.remove('selected'));
  input.closest('.gender-option').classList.add('selected');
}

// --- HELPER: Toggle Sidebar ---

// Global Sidebar Toggle is handled in utils.js

