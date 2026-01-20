// Theme Management
function toggleTheme() {
    const currentTheme = document.documentElement.classList.contains('dark') ? 'dark' : 'light';
    setTheme(currentTheme === 'dark' ? 'light' : 'dark');
}

function setTheme(theme) {
    if (theme === 'dark') {
        document.documentElement.classList.add('dark');
        localStorage.setItem('theme', 'dark');
    } else {
        document.documentElement.classList.remove('dark');
        localStorage.setItem('theme', 'light');
    }
}

// Initialize theme on page load using saved preference or system setting
function initTheme() {
    const saved = localStorage.getItem('theme');
    if (saved === 'dark' || saved === 'light') {
        setTheme(saved);
        return;
    }

    if (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches) {
        setTheme('dark');
    } else {
        setTheme('light');
    }
}

document.addEventListener('DOMContentLoaded', initTheme);

// Mobile Menu Management
document.addEventListener('DOMContentLoaded', () => {
    const toggle = document.getElementById('mobileMenuToggle');
    const sidebar = document.getElementById('mainSidebar');
    const overlay = document.getElementById('sidebarOverlay');

    if (toggle && sidebar && overlay) {
        toggle.addEventListener('click', () => {
            sidebar.classList.toggle('-translate-x-full');
            overlay.classList.toggle('hidden');
        });

        overlay.addEventListener('click', () => {
            sidebar.classList.add('-translate-x-full');
            overlay.classList.add('hidden');
        });
    }
});

// Global expose
window.toggleTheme = toggleTheme;

// --- Global Print Optimization ---
// --- Global Print Optimization ---
(function injectPrintStyles() {
    const style = document.createElement('style');
    style.textContent = `
        @media print {
            /* Hide UI Elements */
            nav, aside, button, .no-print, #logoutBtn, #mobileMenuToggle, 
            #sidebarOverlay, #notificationDropdown, .fixed, .sticky, 
            .lg\\:block, .h-20, #exportPdf, .ri-add-line, .ri-more-2-line { display: none !important; }

            /* Reset Body */
            body, html {
                background: white !important;
                color: black !important;
                width: 100% !important;
                margin: 0 !important;
                padding: 0 !important;
                font-size: 12pt;
                line-height: 1.5;
            }

            .max-w-\\[1600px\\], main {
                margin: 0 !important;
                padding: 40px !important;
                width: 100% !important;
                max-width: 100% !important;
                display: block !important;
            }

            /* Header Resets */
            h1, h2, h3, h4 {
                color: #1e1b4b !important; /* Indigo-950 */
                background: none !important;
                -webkit-background-clip: initial !important;
                -webkit-text-fill-color: initial !important;
                margin-top: 0 !important;
            }

            /* Card transformation to List Items */
            .rounded-3xl, .rounded-\\[40px\\], .rounded-\\[32px\\], section {
                border: none !important;
                border-radius: 0 !important;
                background: none !important;
                box-shadow: none !important;
                margin-bottom: 0 !important;
                padding: 0 !important;
            }

            /* Routine Cards Specifics */
            .routine-card {
                border-bottom: 1px solid #ddd !important;
                padding: 15px 0 !important;
                background: none !important;
                display: flex !important;
                align-items: center !important;
                justify-content: space-between !important;
                margin-bottom: 0 !important;
                page-break-inside: avoid;
            }
            .routine-card:last-child { border-bottom: none !important; }
            
            /* Remove left colored borders for print cleanliness, use text color instead */
            .border-l-8 { border-left: none !important; }

            /* Grid Resets */
            .grid { display: block !important; }
            .grid > div { margin-bottom: 10px !important; width: 100% !important; }

            /* Text Visibility */
            .text-white { color: black !important; }
            .text-gray-400, .text-gray-500 { color: #555 !important; }
            
            /* Canvas/Charts */
            canvas {
                max-width: 60% !important;
                height: auto !important;
                margin: 20px auto !important;
                display: block !important;
            }
        }
    `;
    document.head.appendChild(style);
})();

// --- Zoom Level Detection & Layout Adjustment ---
(function handleZoomChanges() {
    let lastZoom = window.devicePixelRatio;
    
    function adjustLayoutForZoom() {
        const currentZoom = window.devicePixelRatio;
        const zoomLevel = Math.round((currentZoom / lastZoom) * 100);
        
        // Add zoom-level class to body for CSS targeting
        document.body.classList.remove('zoom-low', 'zoom-normal', 'zoom-high', 'zoom-very-high');
        
        if (currentZoom < 1) {
            document.body.classList.add('zoom-low');
        } else if (currentZoom >= 1 && currentZoom < 1.5) {
            document.body.classList.add('zoom-normal');
        } else if (currentZoom >= 1.5 && currentZoom < 2) {
            document.body.classList.add('zoom-high');
        } else {
            document.body.classList.add('zoom-very-high');
        }
        
        lastZoom = currentZoom;
    }
    
    // Check on load
    adjustLayoutForZoom();
    
    // Monitor zoom changes
    window.addEventListener('resize', adjustLayoutForZoom);
    
    // Detect browser zoom via matchMedia
    window.matchMedia('screen and (min-resolution: 1dppx)').addListener(adjustLayoutForZoom);
})();

