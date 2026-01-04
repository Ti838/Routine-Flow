# Gradient Flow UI - Visual Style Guide

> A visual reference guide for the Gradient Flow design system

---

## 🎨 Color Palette

![Color Palette](C:/Users/TIMON/.gemini/antigravity/brain/486cd21a-2106-486d-8427-20177c8389a9/gradient_flow_palette_1766760621820.png)

### Primary Colors

| Color | Hex | Usage |
|-------|-----|-------|
| **Primary Blue** | `#667eea` | Main brand color, buttons, links, accents |
| **Primary Dark** | `#764ba2` | Gradient end, dark accents, hover states |
| **Primary Light** | `#818cf8` | Light accents, backgrounds, subtle highlights |

### Gradient

```css
background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
```

**Applications**: Hero sections, buttons, headers, accent bars

---

## 🔘 Button Components

![Button Components](C:/Users/TIMON/.gemini/antigravity/brain/486cd21a-2106-486d-8427-20177c8389a9/button_components_1766760647935.png)

### Button Variants

#### Primary Button

- **Background**: Gradient (#667eea → #764ba2)
- **Text**: White, 600 weight
- **Padding**: 10px 20px
- **Border Radius**: 8px
- **Hover**: Lift 1px + glow shadow

#### Outline Button

- **Background**: Transparent
- **Border**: 1px solid #667eea
- **Text**: #667eea, 600 weight
- **Hover**: Light background fill

#### Icon Button

- **Size**: 36px × 36px
- **Shape**: Circular (50% border radius)
- **Hover**: Background + scale 1.1

---

## 📦 Card Components

![Card Components](C:/Users/TIMON/.gemini/antigravity/brain/486cd21a-2106-486d-8427-20177c8389a9/card_components_1766760669033.png)

### Card Variants

#### Standard Card

- **Background**: White (#FFFFFF)
- **Border**: 1px solid #E5E9F0
- **Border Radius**: 12px
- **Shadow**: `0 2px 4px rgba(0, 0, 0, 0.02)`
- **Hover**: Lift 5px + deeper shadow

#### Stat Card

- **Left Border**: 5px solid #667eea
- **Icon Background**: rgba(102, 126, 234, 0.1)
- **Value**: 32px, 700 weight
- **Label**: Uppercase, 14px, 600 weight

#### Premium Card

- **Border Radius**: 20px
- **Shadow**: `0 4px 20px rgba(0, 0, 0, 0.05)`
- **Header**: Separator line, padding bottom
- **Hover**: Lift 2px + enhanced shadow

---

## 📏 Spacing Examples

### Spacing Scale

```
xs:  8px   ████
sm:  16px  ████████
md:  24px  ████████████
lg:  32px  ████████████████
xl:  48px  ████████████████████████
```

### Common Applications

| Element | Spacing |
|---------|---------|
| Card padding | `var(--space-md)` (24px) |
| Grid gap | `var(--space-md)` (24px) |
| Section margin | `var(--space-lg)` (32px) |
| Button padding | `10px 20px` |
| Input padding | `10px 14px` |

---

## 🔤 Typography Scale

### Heading Hierarchy

```
H1: 32px / 600 weight  ████████████████
H2: 24px / 600 weight  ████████████
H3: 20px / 600 weight  ██████████
H4: 18px / 600 weight  █████████
```

### Body Text

```
Large:   18px / 400 weight
Base:    16px / 400 weight  (Default)
Small:   14px / 400 weight
XSmall:  12px / 400 weight
```

### Font Weights

- **Light**: 300 - Rarely used
- **Regular**: 400 - Body text
- **Medium**: 500 - Labels, nav links
- **Semibold**: 600 - Headings, buttons
- **Bold**: 700 - Stats, emphasis

---

## 🎭 Shadow Levels

### Light Mode

```
Small:   0 2px 4px rgba(148, 163, 184, 0.15)
Medium:  0 4px 12px rgba(148, 163, 184, 0.15)
Large:   0 8px 24px rgba(148, 163, 184, 0.15)
XLarge:  0 16px 32px rgba(148, 163, 184, 0.15)
```

### Dark Mode

```
Small:   0 1px 3px rgba(0, 0, 0, 0.3)
Medium:  0 2px 8px rgba(0, 0, 0, 0.4)
Large:   0 4px 12px rgba(0, 0, 0, 0.5)
XLarge:  0 8px 24px rgba(0, 0, 0, 0.6)
```

---

## 🎨 Color Usage Guidelines

### Do's ✅

- Use gradient for primary CTAs
- Use primary blue for links and accents
- Maintain consistent color usage across pages
- Use semantic colors (success, warning, danger) appropriately

### Don'ts ❌

- Don't use gradient on small text
- Don't mix multiple gradients on one page
- Don't use primary colors for body text
- Don't override theme colors directly

---

## 📐 Layout Grid

### Desktop (≥1025px)

```
┌─────────────────────────────────────┐
│  Navbar (64px height)               │
├──────┬──────────────────────────────┤
│      │                              │
│ Side │  Main Content                │
│ bar  │  (max-width: 1200px)         │
│260px │                              │
│      │                              │
└──────┴──────────────────────────────┘
```

### Mobile (≤768px)

```
┌─────────────────┐
│ Navbar (56px)   │
├─────────────────┤
│                 │
│  Main Content   │
│  (full width)   │
│                 │
│  [Sidebar as    │
│   drawer]       │
└─────────────────┘
```

---

## 🎬 Animation Guidelines

### Timing Functions

- **Fast**: 150ms ease-in-out (hover effects)
- **Normal**: 300ms ease-in-out (theme toggle)
- **Slow**: 500ms ease-in-out (page transitions)

### Common Animations

#### Hover Lift

```css
transform: translateY(-5px);
transition: transform 0.3s ease;
```

#### Fade In

```css
animation: fadeIn 0.3s ease;
```

#### Slide Up

```css
animation: slideUp 0.3s ease;
```

---

## 🌓 Theme Comparison

### Light Theme

- **Background**: #F5F7FA
- **Card**: #FFFFFF
- **Text**: #333333
- **Border**: #E5E9F0

### Dark Theme

- **Background**: #16213e
- **Card**: #1e293b
- **Text**: #FFFFFF
- **Border**: #334155

---

## 📱 Responsive Breakpoints

| Device | Width | Adjustments |
|--------|-------|-------------|
| **Mobile** | ≤768px | Single column, drawer sidebar |
| **Tablet** | 769-1024px | 2-column grids, visible sidebar |
| **Desktop** | ≥1025px | Full layout, all features |

---

## ♿ Accessibility

### Color Contrast Ratios

- **Primary Blue on White**: 4.8:1 (AA)
- **Text Dark on White**: 12.6:1 (AAA)
- **Text Medium on White**: 5.7:1 (AA)

### Focus States

All interactive elements have visible focus indicators:

- **Buttons**: Blue border + glow
- **Inputs**: Blue border + shadow
- **Links**: Underline + color change

---

## 🎯 Component States

### Interactive States

| State | Visual Change |
|-------|---------------|
| **Default** | Base styling |
| **Hover** | Background change, lift, or scale |
| **Focus** | Blue border + glow shadow |
| **Active** | Pressed appearance |
| **Disabled** | Reduced opacity, no pointer |

---

## 📊 Icon System

### Icon Sizes

- **Small**: 16px (inline text)
- **Medium**: 20px (sidebar, buttons)
- **Large**: 24px (navbar, headers)
- **XLarge**: 48px (stat cards)

### Icon Style

- Emoji-based for simplicity
- Consistent sizing
- Proper spacing from text
- Color inherits from parent

---

## 🎨 Design Tokens Quick Reference

```css
/* Colors */
--primary-blue: #667eea;
--primary-dark: #764ba2;
--gradient-primary: linear-gradient(135deg, #667eea 0%, #764ba2 100%);

/* Spacing */
--space-sm: 16px;
--space-md: 24px;
--space-lg: 32px;

/* Typography */
--font-family: 'Inter', sans-serif;
--font-size-base: 16px;

/* Shadows */
--shadow-md: 0 4px 12px rgba(148, 163, 184, 0.15);

/* Border Radius */
--radius-md: 8px;
--radius-lg: 12px;

/* Transitions */
--transition-normal: 300ms ease-in-out;
```

---

## 📚 Related Documentation

- **[Complete Design System](DESIGN_SYSTEM.md)** - Full token reference
- **[Component Showcase](COMPONENT_SHOWCASE.md)** - Code examples
- **[README](README.md)** - Project overview

---

**© 2025 Timon Biswas | Gradient Flow UI Design System**
