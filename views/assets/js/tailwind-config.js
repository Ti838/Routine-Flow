// Tailwind CSS Configuration for Routine Flow
// This configures Tailwind with your custom design system

tailwind.config = {
  darkMode: 'class',
  theme: {
    extend: {
      colors: {
        // Primary Colors
        primary: {
          DEFAULT: '#667eea',
          light: '#818cf8',
          dark: '#764ba2',
          50: '#f5f7ff',
          100: '#ebf0ff',
          200: '#d6e0ff',
          300: '#b8c9ff',
          400: '#8fa3ff',
          500: '#667eea',
          600: '#5568d3',
          700: '#4553b8',
          800: '#3a4694',
          900: '#2f3a7a',
        },
        purple: {
          DEFAULT: '#764ba2',
          light: '#9b6fc9',
          dark: '#5a3a7a',
        },
        // Status Colors
        success: '#10b981',
        warning: '#f59e0b',
        error: '#ef4444',
        info: '#3b82f6',
        // Dark Mode Colors
        dark: {
          bg: '#111827',
          card: '#1f2937',
          border: '#374151',
        },
      },
      fontFamily: {
        sans: ['Inter', 'system-ui', '-apple-system', 'BlinkMacSystemFont', 'Segoe UI', 'Roboto', 'sans-serif'],
      },
      borderRadius: {
        'xl': '1rem',
        '2xl': '1.5rem',
        '3xl': '2rem',
      },
      boxShadow: {
        'premium': '0 10px 40px rgba(102, 126, 234, 0.15)',
        'premium-lg': '0 20px 60px rgba(102, 126, 234, 0.2)',
      },
      backgroundImage: {
        'gradient-primary': 'linear-gradient(135deg, #667eea 0%, #764ba2 100%)',
        'gradient-light': 'linear-gradient(135deg, rgba(102, 126, 234, 0.1) 0%, rgba(118, 75, 162, 0.1) 100%)',
      },
      animation: {
        'fade-in': 'fadeIn 0.3s ease-in-out',
        'slide-up': 'slideUp 0.4s ease-out',
        'slide-down': 'slideDown 0.4s ease-out',
        'pulse-slow': 'pulse 3s cubic-bezier(0.4, 0, 0.6, 1) infinite',
      },
      keyframes: {
        fadeIn: {
          '0%': { opacity: '0' },
          '100%': { opacity: '1' },
        },
        slideUp: {
          '0%': { transform: 'translateY(20px)', opacity: '0' },
          '100%': { transform: 'translateY(0)', opacity: '1' },
        },
        slideDown: {
          '0%': { transform: 'translateY(-20px)', opacity: '0' },
          '100%': { transform: 'translateY(0)', opacity: '1' },
        },
      },
    },
  },
  plugins: [],
}

// Inject Global Styles using Tailwind's @apply directives
const style = document.createElement('style');
style.type = 'text/tailwindcss';
style.innerHTML = `
    @layer components {
        .navbar {
            @apply bg-white dark:bg-dark-card border-b border-gray-200 dark:border-dark-border py-4 px-6 sticky top-0 z-50;
        }
        .navbar-content {
            @apply flex justify-between items-center max-w-[1400px] mx-auto;
        }
        .navbar-brand {
            @apply flex items-center gap-3 font-bold text-xl text-gray-900 dark:text-white;
        }
        .navbar-logo {
            @apply w-10 h-10 rounded-xl;
        }
        .navbar-title {
            @apply bg-gradient-primary bg-clip-text text-transparent;
        }
        .theme-toggle {
            @apply w-10 h-10 rounded-xl bg-gray-100 dark:bg-gray-800 flex items-center justify-center text-xl hover:bg-gray-200 dark:hover:bg-gray-700 transition-all;
        }
        .sidebar {
            @apply w-72 bg-white dark:bg-dark-card border-r border-gray-200 dark:border-dark-border h-screen sticky top-0 overflow-y-auto hidden md:block;
        }
        .sidebar-menu {
            @apply p-6 space-y-2;
        }
        .sidebar-link {
            @apply flex items-center gap-3 p-3 rounded-xl text-gray-600 dark:text-gray-400 font-medium transition-all hover:bg-gray-100 dark:hover:bg-gray-800 hover:text-primary;
        }
        .sidebar-link.active {
            @apply bg-gradient-light text-primary font-semibold;
        }
        .sidebar-icon {
            @apply text-xl;
        }
        .menu-section-title {
            @apply px-3 py-2 text-xs font-bold text-gray-400 uppercase tracking-wider mt-6;
        }
        .main-content {
            @apply flex-1 p-6 lg:p-10 bg-gray-50 dark:bg-dark-bg min-h-screen;
        }
        .card-premium {
            @apply bg-white dark:bg-dark-card border border-gray-200 dark:border-dark-border rounded-2xl p-6 shadow-sm hover:shadow-md transition-all;
        }
        .btn {
            @apply inline-flex items-center justify-center gap-2 px-6 py-3 rounded-xl font-semibold transition-all cursor-pointer;
        }
        .btn-primary {
            @apply bg-gradient-primary text-white shadow-premium hover:shadow-premium-lg hover:-translate-y-0.5;
        }
        .btn-outline {
            @apply border-2 border-primary text-primary hover:bg-primary hover:text-white;
        }
        .btn-sm {
            @apply px-4 py-2 text-sm;
        }
        .form-label-premium {
            @apply block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-2;
        }
        .form-control-premium {
            @apply w-full px-4 py-3 rounded-xl border border-gray-200 dark:border-dark-border bg-white dark:bg-dark-card text-gray-900 dark:text-white focus:ring-2 focus:ring-primary focus:border-primary outline-none transition-all;
        }
        .table-premium {
            @apply w-full text-left;
        }
        .table-premium th {
            @apply px-4 py-3 text-xs font-bold text-gray-400 uppercase tracking-wider border-b border-gray-200 dark:border-dark-border;
        }
        .table-premium td {
            @apply px-4 py-4 border-b border-gray-100 dark:border-dark-border/50;
        }
        .content-title {
            @apply text-3xl font-bold bg-gradient-primary bg-clip-text text-transparent mb-2;
        }
        .content-subtitle {
            @apply text-gray-500 dark:text-gray-400;
        }
        .meta-item {
            @apply flex items-center gap-2 text-sm text-gray-500 dark:text-gray-400;
        }
        .meta-item i {
            @apply text-primary;
        }
        .badge {
            @apply inline-block px-3 py-1 rounded-full text-xs font-bold;
        }
    }
`;
document.head.appendChild(style);
