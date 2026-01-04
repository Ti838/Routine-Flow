# Gradient Flow UI Design System

> **A modern, premium design system for academic routine management**  
> Created by Timon Biswas | Version 1.0 | December 2025

---

## 🎨 Overview

**Gradient Flow UI** is a comprehensive design system built for the Routine Flow application. It features a sophisticated purple-to-blue gradient aesthetic with clean, modern components optimized for educational institutions.

### Design Philosophy

- **Premium & Professional**: Sophisticated gradients, soft shadows, and smooth animations
- **Accessibility First**: High contrast ratios, semantic HTML, and ARIA support
- **Responsive by Default**: Mobile-first approach with fluid layouts
- **Theme-Aware**: Full light/dark mode support with smooth transitions

---

## 🎨 Color Palette

### Primary Colors

```css
--primary-blue: #667eea    /* Main brand color */
--primary-dark: #764ba2    /* Gradient accent */
--primary-light: #818cf8   /* Hover states */
```

### Gradient

```css
--gradient-primary: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
```

**Usage**: Headers, buttons, accent elements, text gradients

### Neutral Colors

#### Light Theme

```css
--white: #FFFFFF
--light-bg: #F5F7FA
--light-gray: #E5E9F0
--medium-gray: #D1D5DB
```

#### Dark Theme

```css
--white: #1a1a2e
--light-bg: #16213e
--light-gray: #0f172a
--medium-gray: #1e293b
```

### Text Colors

```css
--text-dark: #333333    /* Primary text (light mode) */
--text-medium: #666666  /* Secondary text */
--text-light: #999999   /* Tertiary text */
```

### Semantic Colors

```css
--success: #4CAF50
--warning: #FFC107
--danger: #F44336
--info: #667eea
```

---

## 📐 Spacing System

Consistent spacing scale based on 8px grid:

```css
--space-xs: 8px
--space-sm: 16px
--space-md: 24px
--space-lg: 32px
--space-xl: 48px
```

**Usage Examples**:

- Card padding: `var(--space-md)` (24px)
- Section margins: `var(--space-lg)` (32px)
- Element gaps: `var(--space-sm)` (16px)

---

## 🔤 Typography

### Font Family

```css
--font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
```

**Inter** is a modern, highly legible sans-serif optimized for UI design.

### Type Scale

```css
--font-size-xs: 12px
--font-size-sm: 14px
--font-size-base: 16px
--font-size-lg: 18px
--font-size-xl: 20px
--font-size-2xl: 24px
--font-size-3xl: 32px
```

### Font Weights

- **Light**: 300 (rarely used)
- **Regular**: 400 (body text)
- **Medium**: 500 (labels, nav links)
- **Semibold**: 600 (headings, emphasis)
- **Bold**: 700 (titles, stats)

### Heading Styles

```css
h1 { font-size: 32px; font-weight: 600; }
h2 { font-size: 24px; font-weight: 600; }
h3 { font-size: 20px; font-weight: 600; }
h4 { font-size: 18px; font-weight: 600; }
```

---

## 🎭 Shadows

Premium soft shadows for depth:

```css
--shadow-sm: 0 2px 4px rgba(148, 163, 184, 0.15)
--shadow-md: 0 4px 12px rgba(148, 163, 184, 0.15)
--shadow-lg: 0 8px 24px rgba(148, 163, 184, 0.15)
--shadow-xl: 0 16px 32px rgba(148, 163, 184, 0.15)
```

**Dark Mode Shadows**:

```css
--shadow-sm: 0 1px 3px rgba(0, 0, 0, 0.3)
--shadow-md: 0 2px 8px rgba(0, 0, 0, 0.4)
--shadow-lg: 0 4px 12px rgba(0, 0, 0, 0.5)
```

---

## 📦 Border Radius

```css
--radius-sm: 4px   /* Small elements */
--radius-md: 8px   /* Buttons, inputs */
--radius-lg: 12px  /* Cards */
--radius-xl: 16px  /* Large containers */
```

---

## ⚡ Transitions

```css
--transition-fast: 150ms ease-in-out
--transition-normal: 300ms ease-in-out
--transition-slow: 500ms ease-in-out
```

**Usage**:

- Hover effects: `transition-fast`
- Theme switching: `transition-normal`
- Animations: `transition-slow`

---

## 🧩 Components

### Buttons

#### Primary Button

```html
<button class="btn btn-primary">Get Started</button>
```

**Styles**:

- Background: Gradient primary
- Color: White
- Hover: Lift effect + shadow
- Border radius: 8px

#### Outline Button

```html
<button class="btn btn-outline">Learn More</button>
```

**Styles**:

- Border: 1px solid primary blue
- Color: Primary blue
- Hover: Light background

#### Icon Button

```html
<button class="icon-btn">🔔</button>
```

**Styles**:

- Size: 36px × 36px
- Border radius: 50% (circular)
- Hover: Background + scale

---

### Cards

#### Standard Card

```html
<div class="card">
  <!-- Content -->
</div>
```

**Features**:

- Background: White
- Border: 1px solid light gray
- Shadow: Soft subtle
- Hover: Lift + border color change

#### Premium Card

```html
<div class="card-premium">
  <div class="card-header">
    <h3 class="card-title">Title</h3>
  </div>
  <!-- Content -->
</div>
```

**Features**:

- Border radius: 20px
- Deeper shadows
- Smooth hover transitions

#### Stat Card

```html
<div class="stat-card-premium">
  <div class="stat-icon">📊</div>
  <div class="stat-value">42</div>
  <div class="stat-label">Total Classes</div>
</div>
```

**Features**:

- Left border accent (5px)
- Icon with colored background
- Large value typography
- Hover lift effect

---

### Navigation

#### Navbar

```html
<nav class="navbar">
  <div class="navbar-content">
    <a href="#" class="navbar-brand">
      <img src="logo.png" class="navbar-logo">
      <span class="navbar-title">Routine Flow</span>
    </a>
    <div class="navbar-actions">
      <!-- Actions -->
    </div>
  </div>
</nav>
```

**Specifications**:

- Height: 64px (desktop), 56px (mobile)
- Position: Fixed top
- Background: White with border bottom
- Z-index: 100

#### Sidebar

```html
<aside class="sidebar">
  <ul class="sidebar-menu">
    <li class="sidebar-item">
      <a href="#" class="sidebar-link active">
        <span class="sidebar-icon">🏠</span>
        <span>Dashboard</span>
      </a>
    </li>
  </ul>
</aside>
```

**Specifications**:

- Width: 260px
- Position: Fixed left
- Active state: Blue background + left accent bar
- Mobile: Slide-in drawer

---

### Forms

#### Input Fields

```html
<input type="text" class="form-control-premium" placeholder="Enter text">
```

**Features**:

- Border radius: 8px
- Focus: Blue border + glow shadow
- Dark mode: Dark background with proper contrast

#### Labels

```html
<label class="form-label-premium">
  📧 Email Address
</label>
```

**Features**:

- Uppercase with letter spacing
- Icon support
- Semibold weight

---

### Tables

#### Premium Table

```html
<table class="table-premium">
  <thead>
    <tr>
      <th>Column</th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <td>Data</td>
    </tr>
  </tbody>
</table>
```

**Features**:

- Row spacing: 8px
- Rounded row corners
- Hover: Scale + shadow
- Uppercase headers

---

## 🎬 Animations

### Keyframes

#### Fade In

```css
@keyframes fadeIn {
  from { opacity: 0; }
  to { opacity: 1; }
}
```

#### Slide Up

```css
@keyframes slideUp {
  from {
    transform: translateY(50px);
    opacity: 0;
  }
  to {
    transform: translateY(0);
    opacity: 1;
  }
}
```

#### Fade Up

```css
@keyframes fadeUp {
  from {
    opacity: 0;
    transform: translateY(10px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}
```

### Usage Examples

```css
.modal-content {
  animation: slideUp 0.3s ease;
}

.card:hover {
  transform: translateY(-5px);
  transition: transform 0.3s ease;
}
```

---

## 🌓 Dark Mode

### Theme Toggle

```html
<button id="themeToggle" class="theme-toggle" onclick="toggleTheme()">
  🌙
</button>
```

### Implementation

```javascript
function toggleTheme() {
  const html = document.documentElement;
  const currentTheme = html.getAttribute('data-theme');
  const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
  html.setAttribute('data-theme', newTheme);
  localStorage.setItem('theme', newTheme);
}
```

### Dark Mode Variables

All colors automatically adapt via CSS custom properties:

```css
[data-theme="dark"] {
  --white: #1a1a2e;
  --text-dark: #FFFFFF;
  --bg-card: #1e293b;
  /* ... */
}
```

---

## 📱 Responsive Design

### Breakpoints

```css
/* Mobile */
@media (max-width: 768px) { }

/* Tablet */
@media (max-width: 1024px) { }
```

### Mobile Adaptations

- **Sidebar**: Transforms to slide-in drawer
- **Grids**: Stack to single column
- **Navbar**: Reduced height, compact spacing
- **Typography**: Smaller heading sizes

---

## ♿ Accessibility

### Best Practices

✅ **Semantic HTML**: Proper heading hierarchy, landmarks  
✅ **ARIA Labels**: All interactive elements labeled  
✅ **Keyboard Navigation**: Full keyboard support  
✅ **Color Contrast**: WCAG AA compliant  
✅ **Focus States**: Visible focus indicators  
✅ **Screen Reader**: Descriptive text for icons

### Example

```html
<button class="theme-toggle" aria-label="Toggle Theme">
  🌙
</button>
```

---

## 🚀 Usage Guidelines

### Getting Started

1. **Include CSS Files**:

```html
<link rel="stylesheet" href="css/global.css">
<link rel="stylesheet" href="css/components.css">
<link rel="stylesheet" href="css/dashboard.css">
```

1. **Set Theme Attribute**:

```html
<html lang="en" data-theme="light">
```

1. **Use Design Tokens**:

```css
.custom-element {
  padding: var(--space-md);
  border-radius: var(--radius-lg);
  color: var(--text-dark);
}
```

### Component Composition

Build complex UIs by combining base components:

```html
<div class="card-premium">
  <div class="stats-grid">
    <div class="stat-card-premium">
      <!-- Stat content -->
    </div>
  </div>
</div>
```

---

## 📊 Design Tokens Reference

### Complete Token List

| Category | Token | Value |
|----------|-------|-------|
| **Color** | `--primary-blue` | `#667eea` |
| **Color** | `--primary-dark` | `#764ba2` |
| **Spacing** | `--space-md` | `24px` |
| **Typography** | `--font-size-base` | `16px` |
| **Shadow** | `--shadow-md` | `0 4px 12px rgba(...)` |
| **Radius** | `--radius-lg` | `12px` |
| **Transition** | `--transition-normal` | `300ms ease-in-out` |

---

## 🎯 Design Principles

1. **Consistency**: Use design tokens for all values
2. **Hierarchy**: Clear visual hierarchy through typography and spacing
3. **Feedback**: Immediate visual feedback for interactions
4. **Performance**: Optimized animations and transitions
5. **Scalability**: Modular components that compose well

---

## 📄 License

© 2025 Timon Biswas. All Rights Reserved.

This design system is proprietary and confidential. Unauthorized use, reproduction, or distribution is prohibited.

---

## 📧 Contact

For design system inquiries or licensing:  
**Timon Biswas** - [timonbiswas33@gmail.com](mailto:timonbiswas33@gmail.com)
