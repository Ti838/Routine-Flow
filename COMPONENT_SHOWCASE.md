# Gradient Flow UI - Component Showcase

> Visual examples and code snippets for all Gradient Flow UI components

---

## 🎨 Color Swatches

### Primary Palette

```
┌─────────────────────┐
│   Primary Blue      │
│   #667eea          │
└─────────────────────┘

┌─────────────────────┐
│   Primary Dark      │
│   #764ba2          │
└─────────────────────┘

┌─────────────────────┐
│   Primary Light     │
│   #818cf8          │
└─────────────────────┘
```

### Gradient Showcase

```
╔═══════════════════════════════════╗
║  Gradient Primary                 ║
║  135deg, #667eea → #764ba2       ║
╚═══════════════════════════════════╝
```

**Used in**: Buttons, Headers, Accent Elements

---

## 🔘 Buttons

### Primary Button

```html
<button class="btn btn-primary">Get Started</button>
<button class="btn btn-primary btn-lg">Large Button</button>
```

**Visual**: Purple-blue gradient background, white text, lift on hover

### Outline Button

```html
<button class="btn btn-outline">Learn More</button>
<button class="btn btn-outline btn-lg">Large Outline</button>
```

**Visual**: Transparent background, blue border, fills on hover

### Icon Button

```html
<button class="icon-btn">🔔</button>
<button class="icon-btn">⚙️</button>
<button class="icon-btn danger">🗑️</button>
```

**Visual**: Circular, 36px, scales on hover

---

## 📦 Cards

### Standard Card

```html
<div class="card">
  <h3>Card Title</h3>
  <p>Card content goes here with proper spacing and typography.</p>
</div>
```

**Features**:

- White background
- 12px border radius
- Subtle shadow
- Hover: Lifts 5px

### Premium Card

```html
<div class="card-premium">
  <div class="card-header">
    <h3 class="card-title">Premium Card</h3>
  </div>
  <div class="card-body">
    <p>Enhanced card with deeper shadows and smoother animations.</p>
  </div>
</div>
```

**Features**:

- 20px border radius
- Deeper shadows
- Header separator
- Smooth transitions

### Stat Card

```html
<div class="stat-card-premium">
  <div class="stat-icon">📊</div>
  <div class="stat-value">42</div>
  <div class="stat-label">Total Classes</div>
</div>
```

**Visual**:

```
┌─────────────────────────┐
│  📊                     │
│                         │
│  42                     │
│  TOTAL CLASSES          │
└─────────────────────────┘
  ↑ 5px blue left border
```

### Routine Card

```html
<div class="routine-card">
  <div class="routine-header">
    <div>
      <h3 class="routine-title">Mathematics 101</h3>
      <div class="routine-time">
        <span>🕐</span>
        <span>10:00 AM - 11:30 AM</span>
      </div>
    </div>
    <span class="badge">Active</span>
  </div>
  <p class="routine-description">
    Introduction to Calculus and Linear Algebra
  </p>
  <div class="routine-footer">
    <div class="routine-meta">
      <span>📍 Room 301</span>
      <span>👨‍🏫 Dr. Smith</span>
    </div>
    <div class="action-buttons">
      <button class="icon-btn">✏️</button>
      <button class="icon-btn danger">🗑️</button>
    </div>
  </div>
</div>
```

---

## 📊 Stats Grid

```html
<div class="stats-grid">
  <div class="stat-card-premium">
    <div class="stat-icon">📚</div>
    <div class="stat-value">24</div>
    <div class="stat-label">Total Courses</div>
  </div>
  
  <div class="stat-card-premium">
    <div class="stat-icon">👨‍🏫</div>
    <div class="stat-value">12</div>
    <div class="stat-label">Teachers</div>
  </div>
  
  <div class="stat-card-premium">
    <div class="stat-icon">🎓</div>
    <div class="stat-value">350</div>
    <div class="stat-label">Students</div>
  </div>
</div>
```

**Layout**: 3-column grid (stacks on mobile)

---

## 🧭 Navigation

### Navbar

```html
<nav class="navbar">
  <div class="navbar-content">
    <a href="#" class="navbar-brand">
      <img src="favicon.png" alt="RF" class="navbar-logo">
      <span class="navbar-title">Routine Flow</span>
    </a>
    <div class="navbar-actions">
      <a href="#features" class="nav-link">Features</a>
      <a href="#about" class="nav-link">About</a>
      <button class="theme-toggle">🌙</button>
      <a href="login.html" class="btn btn-primary">Login</a>
    </div>
  </div>
</nav>
```

**Specifications**:

- Fixed position
- Height: 64px
- White background
- Bottom border

### Sidebar

```html
<aside class="sidebar">
  <ul class="sidebar-menu">
    <li class="sidebar-item">
      <a href="dashboard.html" class="sidebar-link active">
        <span class="sidebar-icon">🏠</span>
        <span>Dashboard</span>
      </a>
    </li>
    <li class="sidebar-item">
      <a href="calendar.html" class="sidebar-link">
        <span class="sidebar-icon">📅</span>
        <span>Calendar</span>
      </a>
    </li>
    <li class="sidebar-item">
      <a href="profile.html" class="sidebar-link">
        <span class="sidebar-icon">👤</span>
        <span>Profile</span>
      </a>
    </li>
  </ul>
</aside>
```

**Active State**:

- Light blue background
- Blue text color
- 3px left accent bar
- Bold font weight

---

## 📝 Forms

### Input Field

```html
<div>
  <label class="form-label-premium">
    📧 Email Address
  </label>
  <input 
    type="email" 
    class="form-control-premium" 
    placeholder="you@example.com"
  >
</div>
```

**States**:

- Default: Light border
- Focus: Blue border + glow
- Error: Red border
- Disabled: Grayed out

### Select Dropdown

```html
<div>
  <label class="form-label-premium">
    📚 Department
  </label>
  <select class="form-control-premium">
    <option>Computer Science</option>
    <option>Mathematics</option>
    <option>Physics</option>
  </select>
</div>
```

### Textarea

```html
<div>
  <label class="form-label-premium">
    📝 Description
  </label>
  <textarea 
    class="form-control-premium" 
    rows="4"
    placeholder="Enter description..."
  ></textarea>
</div>
```

---

## 📋 Tables

### Premium Table

```html
<table class="table-premium">
  <thead>
    <tr>
      <th>Course</th>
      <th>Time</th>
      <th>Room</th>
      <th>Actions</th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <td>Mathematics 101</td>
      <td>10:00 AM - 11:30 AM</td>
      <td>Room 301</td>
      <td>
        <button class="icon-btn">✏️</button>
        <button class="icon-btn danger">🗑️</button>
      </td>
    </tr>
    <tr>
      <td>Physics 201</td>
      <td>2:00 PM - 3:30 PM</td>
      <td>Lab 102</td>
      <td>
        <button class="icon-btn">✏️</button>
        <button class="icon-btn danger">🗑️</button>
      </td>
    </tr>
  </tbody>
</table>
```

**Features**:

- 8px row spacing
- Rounded corners on rows
- Hover: Scale + shadow
- Uppercase headers

---

## 🎭 Modals

### Standard Modal

```html
<div id="exampleModal" class="modal">
  <div class="modal-content">
    <div class="modal-header">
      <h2>Modal Title</h2>
      <button class="close-modal" onclick="closeModal('exampleModal')">×</button>
    </div>
    <div class="modal-body">
      <p>Modal content goes here...</p>
    </div>
  </div>
</div>
```

**Animations**:

- Background: Fade in
- Content: Slide up
- Close button: Rotate on hover

---

## 🎨 Theme Toggle

### Floating Theme Toggle

```html
<button class="theme-toggle floating" onclick="toggleTheme()">
  🌙
</button>
```

**Visual**:

- Fixed position (bottom-right)
- Glassmorphism effect
- Rotates 180° on hover
- Changes icon (🌙 ↔ ☀️)

---

## 📱 Responsive Utilities

### Mobile Stack

```html
<div class="stats-grid mobile-stack">
  <!-- Stacks to single column on mobile -->
</div>
```

### Mobile Visibility

```html
<div class="mobile-left">
  <!-- Left-aligned on mobile -->
</div>
```

---

## 🎬 Animation Examples

### Hover Effects

```css
/* Card Lift */
.card:hover {
  transform: translateY(-5px);
  box-shadow: var(--shadow-md);
}

/* Button Hover */
.btn-primary:hover {
  opacity: 0.9;
  transform: translateY(-1px);
  box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
}

/* Icon Button Scale */
.icon-btn:hover {
  transform: scale(1.1);
}
```

### Entry Animations

```css
/* Fade Up */
.modal-content {
  animation: slideUp 0.3s ease;
}

/* Slide In */
.sidebar {
  animation: slideInLeft 0.8s cubic-bezier(0.16, 1, 0.3, 1);
}
```

---

## 🎯 Usage Patterns

### Dashboard Layout

```html
<div class="page-wrapper">
  <nav class="navbar"><!-- Navbar --></nav>
  <aside class="sidebar"><!-- Sidebar --></aside>
  
  <main class="main-content with-sidebar">
    <div class="stats-grid">
      <!-- Stat cards -->
    </div>
    
    <div class="card-premium">
      <!-- Main content -->
    </div>
  </main>
  
  <footer class="main-footer with-sidebar">
    <!-- Footer -->
  </footer>
</div>
```

### Landing Page Layout

```html
<body>
  <nav class="navbar"><!-- Navbar --></nav>
  
  <header class="landing-hero">
    <h1 class="landing-title">Title</h1>
    <p class="landing-subtitle">Subtitle</p>
    <div class="cta-buttons">
      <button class="btn btn-primary btn-lg">CTA</button>
    </div>
  </header>
  
  <section class="features-grid">
    <!-- Feature cards -->
  </section>
  
  <footer class="main-footer"><!-- Footer --></footer>
</body>
```

---

## 💡 Best Practices

### Do's ✅

- Use design tokens for all values
- Maintain consistent spacing
- Apply hover states to interactive elements
- Use semantic HTML
- Test in both light and dark modes

### Don'ts ❌

- Don't use hardcoded colors
- Don't skip accessibility attributes
- Don't mix spacing values
- Don't forget mobile responsiveness
- Don't override theme variables directly

---

## 📚 Component Checklist

When creating new components:

- [ ] Uses design tokens
- [ ] Responsive on mobile
- [ ] Works in dark mode
- [ ] Has hover/focus states
- [ ] Includes ARIA labels
- [ ] Follows naming conventions
- [ ] Documented with examples

---

**For complete design system documentation, see [DESIGN_SYSTEM.md](DESIGN_SYSTEM.md)**
