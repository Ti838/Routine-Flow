// ===================================
// ROUTINE FLOW - UTILITY FUNCTIONS
// Helper functions for the application
// ===================================

// --- THEME MANAGEMENT ---

// Initialize theme on page load
function initTheme() {
  // Get saved theme from localStorage, default to 'light'
  const savedTheme = localStorage.getItem('theme') || 'light';
  setTheme(savedTheme);
}

// Set theme and save to localStorage
function setTheme(theme) {
  document.documentElement.setAttribute('data-theme', theme);
  localStorage.setItem('theme', theme);

  // Update theme toggle button icon
  const themeToggle = document.getElementById('themeToggle');
  if (themeToggle) {
    themeToggle.innerHTML = theme === 'dark' ? '☀️' : '🌙';
  }
}

// Toggle between light and dark theme
function toggleTheme() {
  const currentTheme = document.documentElement.getAttribute('data-theme');
  const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
  setTheme(newTheme);
}

// --- DATE & TIME UTILITIES ---

// Format date to readable string (e.g., "December 9, 2025")
function formatDate(dateString) {
  const date = new Date(dateString);
  return date.toLocaleDateString('en-US', {
    year: 'numeric',
    month: 'long',
    day: 'numeric'
  });
}

// Format date to short string (e.g., "Dec 9, 2025")
function formatDateShort(dateString) {
  const date = new Date(dateString);
  return date.toLocaleDateString('en-US', {
    month: 'short',
    day: 'numeric',
    year: 'numeric'
  });
}

// Format time to 12-hour format (e.g., "2:30 PM")
function formatTime(timeString) {
  const [hours, minutes] = timeString.split(':');
  const hour = parseInt(hours);
  const ampm = hour >= 12 ? 'PM' : 'AM';
  const displayHour = hour % 12 || 12;
  return `${displayHour}:${minutes} ${ampm}`;
}

// Get current date in YYYY-MM-DD format
function getCurrentDate() {
  const date = new Date();
  return date.toISOString().split('T')[0];
}

// Get day of week (e.g., "Monday")
function getDayOfWeek(dateString) {
  const date = new Date(dateString);
  return date.toLocaleDateString('en-US', { weekday: 'long' });
}

// Check if date is today
function isToday(dateString) {
  return dateString === getCurrentDate();
}

// Get week dates (Sunday to Saturday)
function getWeekDates(startDate = new Date()) {
  const dates = [];
  const current = new Date(startDate);

  // Go to Sunday of the week
  current.setDate(current.getDate() - current.getDay());

  // Get all 7 days
  for (let i = 0; i < 7; i++) {
    dates.push(new Date(current).toISOString().split('T')[0]);
    current.setDate(current.getDate() + 1);
  }

  return dates;
}

// --- USER SESSION MANAGEMENT ---

// --- NOTIFICATION UI FUNCTIONS ---

function toggleNotifications() {
  const dropdown = document.getElementById('notifDropdown');
  if (dropdown) {
    if (dropdown.style.display === 'none') {
      dropdown.style.display = 'block';
    } else {
      dropdown.style.display = 'none';
    }
  }
}

function clearNotifications() {
  const list = document.getElementById('notifList');
  const badge = document.getElementById('notifBadge');

  if (list) {
    list.innerHTML = '<div class="empty-state-small">No new notifications</div>';
  }
  if (badge) {
    badge.style.display = 'none';
    badge.textContent = '0';
  }

  // In a real app, we would clear this from storage too, but for this demo, 
  // the UI clear is sufficient until next refresh.
  // Also optional: close dropdown
  // document.getElementById('notifDropdown').style.display = 'none';
}

function updateNotificationUI(count, message) {
  const badge = document.getElementById('notifBadge');
  const list = document.getElementById('notifList');

  // Update Badge
  if (badge && count > 0) {
    badge.style.display = 'flex';
    badge.textContent = count > 9 ? '9+' : count;
  }

  // Add to List
  if (list && message) {
    // Remove empty state if present
    const emptyState = list.querySelector('.empty-state-small');
    if (emptyState) {
      emptyState.remove();
    }

    const item = document.createElement('div');
    item.className = 'notif-item unread';
    item.innerHTML = `
            <div class="notif-icon">🔔</div>
            <div>${message}</div>
        `;
    list.prepend(item);
  }
}

// Close Dropdown when clicking outside
window.addEventListener('click', function (e) {
  const dropdown = document.getElementById('notifDropdown');
  const btn = document.getElementById('notifBtn');

  if (dropdown && btn && !dropdown.contains(e.target) && !btn.contains(e.target)) {
    dropdown.style.display = 'none';
  }
});

// Check if user is authenticated
function isAuthenticated() {
  const user = localStorage.getItem('currentUser');
  return user !== null;
}

// Get current logged-in user
function getCurrentUser() {
  const userJson = localStorage.getItem('currentUser');
  return userJson ? JSON.parse(userJson) : null;
}

// Set current user
function setCurrentUser(user) {
  localStorage.setItem('currentUser', JSON.stringify(user));
}

// Clear current user (logout)
function clearCurrentUser() {
  localStorage.removeItem('currentUser');
}

// Check if user has specific role
function hasRole(role) {
  const user = getCurrentUser();
  return user && user.role === role;
}

// Redirect to login if not authenticated
function requireAuth() {
  if (!isAuthenticated()) {
    // Detect if we're in a subdirectory or at root
    const path = window.location.pathname;
    const inSubdir = path.includes('/admin/') || path.includes('/teacher/') || path.includes('/student/') || path.includes('/shared/');
    window.location.href = inSubdir ? '../login.html' : 'login.html';
    return false;
  }
  return true;
}

// Redirect to appropriate dashboard based on role
function redirectToDashboard(role) {
  // Detect if we're in a subdirectory or at root
  const path = window.location.pathname;
  const inSubdir = path.includes('/admin/') || path.includes('/teacher/') || path.includes('/student/') || path.includes('/shared/');

  const dashboards = {
    'Admin': inSubdir ? '../admin/dashboard.html' : 'admin/dashboard.html',
    'Teacher': inSubdir ? '../teacher/dashboard.html' : 'teacher/dashboard.html',
    'Student': inSubdir ? '../student/dashboard.html' : 'student/dashboard.html'
  };

  window.location.href = dashboards[role] || (inSubdir ? '../index.html' : 'index.html');
}

// --- DOM UTILITIES ---

// Show/hide element
function toggleElement(elementId) {
  const element = document.getElementById(elementId);
  if (element) {
    element.classList.toggle('hidden');
  }
}

// Show element
function showElement(elementId) {
  const element = document.getElementById(elementId);
  if (element) {
    element.classList.remove('hidden');
  }
}

// Hide element
function hideElement(elementId) {
  const element = document.getElementById(elementId);
  if (element) {
    element.classList.add('hidden');
  }
}

// Set active nav link
function setActiveNavLink(currentPage) {
  const links = document.querySelectorAll('.sidebar-link');
  links.forEach(link => {
    const href = link.getAttribute('href');
    if (href && (href === currentPage || href.endsWith(currentPage))) {
      link.classList.add('active');
    } else {
      link.classList.remove('active');
    }
  });
}

// --- MODAL UTILITIES ---

// Open modal
function openModal(modalId) {
  const modal = document.getElementById(modalId);
  if (modal) {
    modal.classList.add('active');
  }
}

// Close modal
function closeModal(modalId) {
  const modal = document.getElementById(modalId);
  if (modal) {
    modal.classList.remove('active');
  }
}

// --- NOTIFICATIONS ---

// Show toast notification
function showToast(message, type = 'info') {
  // Create toast element
  const toast = document.createElement('div');
  toast.className = `toast toast-${type}`;
  toast.textContent = message;
  toast.style.cssText = `
    position: fixed;
    top: 80px;
    right: 20px;
    background-color: ${type === 'success' ? '#4CAF50' : type === 'error' ? '#F44336' : '#2196F3'};
    color: white;
    padding: 16px 24px;
    border-radius: 8px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    z-index: 10000;
    animation: slideInRight 0.3s ease-out;
  `;

  document.body.appendChild(toast);

  // Remove after 3 seconds
  setTimeout(() => {
    toast.style.animation = 'slideOutRight 0.3s ease-out';
    setTimeout(() => {
      document.body.removeChild(toast);
    }, 300);
  }, 3000);
}

// --- STRING UTILITIES ---

// Capitalize first letter
function capitalize(string) {
  return string.charAt(0).toUpperCase() + string.slice(1);
}

// Get initials from name (e.g., "John Doe" => "JD")
function getInitials(name) {
  return name
    .split(' ')
    .map(word => word[0])
    .join('')
    .toUpperCase()
    .slice(0, 2);
}

// Generate random ID
function generateId(prefix = 'ID') {
  const timestamp = Date.now();
  const random = Math.floor(Math.random() * 1000);
  return `${prefix}${timestamp}${random}`;
}

// --- ARRAY UTILITIES ---

// Sort array by property
function sortBy(array, property) {
  return array.sort((a, b) => {
    if (a[property] < b[property]) return -1;
    if (a[property] > b[property]) return 1;
    return 0;
  });
}

// Filter array by property value
function filterBy(array, property, value) {
  return array.filter(item => item[property] === value);
}

// --- SIDEBAR TOGGLE ---

// Toggle sidebar on mobile
function toggleSidebar() {
  const sidebar = document.getElementById('sidebar');
  if (sidebar) {
    sidebar.classList.toggle('active');
  }
}

// Close sidebar when clicking outside
function closeSidebarOnClickOutside() {
  document.addEventListener('click', (e) => {
    const sidebar = document.getElementById('sidebar');
    const toggle = document.getElementById('sidebarToggle');

    if (sidebar && toggle &&
      !sidebar.contains(e.target) &&
      !toggle.contains(e.target) &&
      sidebar.classList.contains('active')) {
      sidebar.classList.remove('active');
    }
  });
}

// --- INITIALIZATION ---

// Initialize app on page load
document.addEventListener('DOMContentLoaded', () => {
  // Initialize theme
  initTheme();

  // Close sidebar on outside click
  closeSidebarOnClickOutside();

  // Add animation styles
  const style = document.createElement('style');
  style.textContent = `
    @keyframes slideInRight {
      from { transform: translateX(100%); opacity: 0; }
      to { transform: translateX(0); opacity: 1; }
    }
    @keyframes slideOutRight {
      from { transform: translateX(0); opacity: 1; }
      to { transform: translateX(100%); opacity: 0; }
    }
  `;
  document.head.appendChild(style);
});

// --- CALENDAR SAFE UTILS (Global) ---
window.getSafeWeek = function (startDate) {
  const dates = [];
  const current = new Date(startDate);
  current.setDate(current.getDate() - current.getDay()); // Sunday
  for (let i = 0; i < 7; i++) {
    dates.push(new Date(current).toISOString().split('T')[0]);
    current.setDate(current.getDate() + 1);
  }
  return dates;
};

window.renderLocalCalendarGrid = function (startDate, routines) {
  const weekDates = getSafeWeek(startDate);
  const dayNames = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
  let html = '<div class="calendar"><div class="calendar-grid">';

  // Headers
  dayNames.forEach(day => html += `<div class="calendar-day header">${day}</div>`);

  // Cells
  weekDates.forEach(date => {
    const dayRoutines = routines.filter(r => r.date === date);
    const isToday = date === new Date().toISOString().split('T')[0];
    const activeClass = isToday ? 'today' : '';

    html += `
    <div class="calendar-day ${activeClass}" onclick="showRoutinesForDate('${date}')">
            <div style="display:flex; justify-content:space-between; align-items:center;">
                <span style="font-weight:bold;">${new Date(date).getDate()}</span>
                <button class="btn btn-sm btn-link p-0" 
                        style="font-size:18px; color: var(--primary-blue); text-decoration:none;" 
                        onclick="event.stopPropagation(); openQuickAddModal('${date}')" 
                        title="Add">+</button>
            </div>
            <div style="font-size: 11px; margin-top:5px; color:#666;">
                ${dayRoutines.length} task${dayRoutines.length !== 1 ? 's' : ''}
            </div>
        </div>`;
  });
  html += '</div></div>';
  return html;
};
