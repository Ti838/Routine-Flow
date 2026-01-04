# Routine Flow

> **Smart Routine Management System for Educational Institutions**  
> Built with Gradient Flow UI Design System

![Version](https://img.shields.io/badge/version-1.0.0-blue)
![License](https://img.shields.io/badge/license-Proprietary-red)
![Design System](https://img.shields.io/badge/design-Gradient%20Flow%20UI-purple)

---

## 📖 Overview

**Routine Flow** is a modern, role-based routine management system designed to simplify academic scheduling for educational institutions. With a clean, premium interface powered by the **Gradient Flow UI** design system, it provides seamless schedule management for administrators, teachers, and students.

### ✨ Key Features

- 🎨 **Premium UI/UX** - Gradient Flow design system with purple-blue aesthetics
- 🌓 **Dark Mode** - Full light/dark theme support
- 👥 **Role-Based Access** - Separate dashboards for Admin, Teacher, and Student
- 📱 **Fully Responsive** - Optimized for desktop, tablet, and mobile
- ⚡ **Real-Time Updates** - Dynamic schedule management
- 🎯 **Zero Conflicts** - Smart scheduling prevents overlaps

---

## 🎨 Design System

This project uses the **Gradient Flow UI** design system - a comprehensive, modern design language featuring:

- **Sophisticated Gradients**: Purple (#764ba2) to Blue (#667eea)
- **Premium Components**: Cards, buttons, forms, tables
- **Smooth Animations**: Micro-interactions and transitions
- **Accessibility First**: WCAG AA compliant
- **Theme Support**: Light and dark modes

📚 **[View Complete Design System Documentation →](DESIGN_SYSTEM.md)**  
🎨 **[Browse Component Showcase →](COMPONENT_SHOWCASE.md)**

---

## 🚀 Quick Start

### Prerequisites

- Modern web browser (Chrome, Firefox, Safari, Edge)
- Local web server (optional, for development)

### Installation

1. **Clone or Download** the repository

```bash
git clone https://github.com/yourusername/routine-flow.git
cd routine-flow
```

1. **Open in Browser**

```bash
# Simply open index.html in your browser
# Or use a local server:
python -m http.server 8000
# Then visit: http://localhost:8000
```

1. **Login**

- Navigate to `login.html`
- Create an account or use demo credentials

---

## 📁 Project Structure

```
Routine Flow/
├── index.html              # Landing page
├── login.html              # Authentication page
├── css/
│   ├── global.css          # Design system core
│   ├── components.css      # Reusable components
│   ├── dashboard.css       # Dashboard layouts
│   └── landing.css         # Landing page styles
├── js/
│   ├── utils.js            # Utility functions
│   ├── data.js             # Data management
│   ├── auth.js             # Authentication
│   └── components.js       # Component logic
├── admin/                  # Admin dashboard
├── teacher/                # Teacher dashboard
├── student/                # Student dashboard
├── shared/                 # Shared resources
├── DESIGN_SYSTEM.md        # Design system docs
├── COMPONENT_SHOWCASE.md   # Component examples
└── README.md               # This file
```

---

## 👥 User Roles

### 🔑 Admin

- Create and manage master routine
- Add/edit/delete courses and schedules
- Manage users and departments
- View system analytics

### 👨‍🏫 Teacher

- View personal teaching schedule
- Access assigned classes
- Check room assignments
- View student lists

### 🎓 Student

- View class schedule
- Filter by department
- Check upcoming classes
- Access course details

---

## 🎨 Design Highlights

### Color Palette

```css
Primary Blue:  #667eea
Primary Dark:  #764ba2
Primary Light: #818cf8
Gradient:      linear-gradient(135deg, #667eea 0%, #764ba2 100%)
```

### Typography

- **Font**: Inter (Google Fonts)
- **Weights**: 300, 400, 500, 600, 700
- **Scale**: 12px - 32px

### Components

- ✅ Premium Cards with soft shadows
- ✅ Gradient buttons with hover effects
- ✅ Responsive navigation (navbar + sidebar)
- ✅ Modern form controls
- ✅ Animated modals
- ✅ Premium tables with row hover
- ✅ Stat cards with icons

---

## 📱 Responsive Design

### Breakpoints

- **Mobile**: ≤ 768px
- **Tablet**: 769px - 1024px
- **Desktop**: ≥ 1025px

### Mobile Features

- Collapsible sidebar drawer
- Stacked grid layouts
- Touch-optimized buttons
- Compact navigation

---

## 🌓 Theme System

### Light Mode (Default)

- Clean white backgrounds
- High contrast text
- Soft shadows

### Dark Mode

- Deep slate backgrounds
- Adjusted contrast
- Darker shadows

### Toggle Theme

```javascript
function toggleTheme() {
  const html = document.documentElement;
  const currentTheme = html.getAttribute('data-theme');
  const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
  html.setAttribute('data-theme', newTheme);
  localStorage.setItem('theme', newTheme);
}
```

---

## 🛠️ Technologies Used

- **HTML5** - Semantic markup
- **CSS3** - Custom properties, Grid, Flexbox
- **Vanilla JavaScript** - No frameworks
- **Google Fonts** - Inter typeface
- **LocalStorage** - Data persistence

---

## 📊 Features Breakdown

### ✅ Implemented

- [x] Landing page with features showcase
- [x] Login/Signup system
- [x] Role-based dashboards
- [x] Routine creation and management
- [x] Calendar view
- [x] Profile management
- [x] Dark mode toggle
- [x] Responsive design
- [x] Premium UI components

### 🚧 Planned

- [ ] Backend integration
- [ ] Real-time notifications
- [ ] Export schedules (PDF/CSV)
- [ ] Multi-language support
- [ ] Advanced analytics

---

## 🎯 Design Principles

1. **Premium & Professional** - Sophisticated aesthetics
2. **User-Centric** - Intuitive navigation
3. **Accessible** - WCAG AA compliance
4. **Performant** - Optimized animations
5. **Consistent** - Design system adherence

---

## 📄 Documentation

- **[Design System](DESIGN_SYSTEM.md)** - Complete design token reference
- **[Component Showcase](COMPONENT_SHOWCASE.md)** - Visual component library
- **[Project Proposal](PROJECT_PROPOSAL.md)** - Original project vision
- **[System Capabilities](SYSTEM_CAPABILITIES.md)** - Technical specifications

---

## 🤝 Contributing

This is a proprietary project. Contributions are not currently accepted.

---

## 📜 License

**© 2025 Timon Biswas. All Rights Reserved.**

This software and its design system are proprietary and confidential. Unauthorized use, reproduction, modification, or distribution is strictly prohibited.

For licensing inquiries, contact: [timonbiswas33@gmail.com](mailto:timonbiswas33@gmail.com)

---

## 👨‍💻 Developer

**Timon Biswas**  
Full-Stack Developer & UI/UX Designer

- 📧 Email: [timonbiswas33@gmail.com](mailto:timonbiswas33@gmail.com)
- 🎨 Design System: Gradient Flow UI
- 💼 Project: Routine Flow v1.0

---

## 🙏 Acknowledgments

- **Inter Font** by Rasmus Andersson
- **Design Inspiration** from modern SaaS applications
- **Color Palette** inspired by twilight gradients

---

## 📸 Screenshots

### Landing Page

Clean, modern landing page with gradient hero section and feature cards.

### Dashboard

Role-specific dashboards with stats, routine cards, and calendar views.

### Dark Mode

Fully functional dark theme with adjusted colors and shadows.

---

## 🔗 Quick Links

- [View Design System](DESIGN_SYSTEM.md)
- [Browse Components](COMPONENT_SHOWCASE.md)
- [Project Proposal](PROJECT_PROPOSAL.md)
- [System Capabilities](SYSTEM_CAPABILITIES.md)

---

<div align="center">

**Built with ❤️ using Gradient Flow UI**

*Simplify Your Academic Schedule*

</div>
