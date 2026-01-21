

const Component = {
    render(componentName, targetId, props = {}) {
        const target = document.getElementById(targetId);
        if (!target) return;

        if (componentName === 'Navbar') {
            target.innerHTML = this.getNavbarHTML(props);
        } else if (componentName === 'Sidebar') {
            target.innerHTML = this.getSidebarHTML(props);
        }

        
        
    },

    getNavbarHTML({ role, name }) {
        return `
        <nav class="fixed top-0 w-full z-50 bg-white/80 dark:bg-[#0B1121]/80 backdrop-blur-xl border-b border-gray-100 dark:border-white/5 h-[70px] flex items-center transition-colors duration-300">
            <div class="w-full px-6 flex justify-between items-center">
                <div class="flex items-center gap-4">
                    <button onclick="document.getElementById('sidebar').classList.toggle('-translate-x-full')" class="lg:hidden text-2xl text-gray-500 hover:text-indigo-600 transition-colors">
                        <i class="ri-menu-line"></i>
                    </button>
                    <a href="/index.php" class="flex items-center gap-3">
                        <div class="relative">
                            <div class="absolute inset-0 bg-indigo-500 blur opacity-20 dark:opacity-40 rounded-full"></div>
                            <img src="/assets/img/favicon.png" alt="RF" class="h-9 w-9 rounded-full relative z-10 border-2 border-white dark:border-white/10 shadow-sm">
                        </div>
                        <span class="text-xl font-black text-gray-900 dark:text-white tracking-tight hidden sm:block">Routine<span class="text-indigo-600">Flow</span></span>
                    </a>
                </div>

                <div class="flex items-center gap-6">
                    <button onclick="toggleTheme()" class="w-10 h-10 rounded-xl bg-gray-50 dark:bg-white/5 flex items-center justify-center text-gray-500 dark:text-yellow-400 hover:bg-gray-100 dark:hover:bg-white/10 transition-all focus:ring-2 ring-indigo-500/20">
                        <i class="ri-sun-line hidden dark:block"></i>
                        <i class="ri-moon-line dark:hidden"></i>
                    </button>
                    
                    <div class="h-8 w-[1px] bg-gray-200 dark:bg-white/10 hidden sm:block"></div>

                    <div class="flex items-center gap-3 pl-2">
                        <div class="text-right hidden sm:block">
                            <p class="text-xs font-bold text-gray-400 uppercase tracking-widest leading-none mb-1">${role}</p>
                            <p class="text-sm font-black text-gray-900 dark:text-white leading-none">${name}</p>
                        </div>
                        <img src="https:
                    </div>
                </div>
            </div>
        </nav>
        <div class="h-[70px]"></div> <!-- Spacer -->
        `;
    },

    getSidebarHTML({ role, activePage }) {
        const menuItems = [
            { id: 'dashboard', label: 'Dashboard', icon: 'ri-dashboard-3-line', roles: ['student', 'teacher', 'admin'], link: `/${role}/dashboard.php` },
            { id: 'schedule', label: 'My Schedule', icon: 'ri-calendar-event-line', roles: ['student', 'teacher'], link: `/${role}/today.php` },
            { id: 'manage-users', label: 'Users', icon: 'ri-user-settings-line', roles: ['admin'], link: '/admin/users.php' },
            { id: 'analytics', label: 'Analytics', icon: 'ri-bar-chart-box-line', roles: ['admin'], link: '/admin/analytics.php' },
            { id: 'profile', label: 'Profile', icon: 'ri-user-3-line', roles: ['student', 'teacher', 'admin'], link: '/shared/profile.php' },
            { id: 'settings', label: 'Settings', icon: 'ri-settings-4-line', roles: ['student', 'teacher', 'admin'], link: '/shared/settings.php' },
        ];

        const filteredItems = menuItems.filter(item => item.roles.includes(role));

        const listHtml = filteredItems.map(item => {
            const isActive = activePage.includes(item.id) || (item.link && item.link.includes(activePage));
            const activeClass = isActive
                ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-600/30'
                : 'text-gray-500 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-white/5 hover:text-indigo-600 dark:hover:text-white';

            return `
            <li>
                <a href="${item.link}" class="flex items-center gap-4 px-4 py-3.5 rounded-2xl font-bold transition-all group ${activeClass}">
                    <i class="${item.icon} text-xl transition-colors"></i>
                    <span class="tracking-wide">${item.label}</span>
                    ${isActive ? '<i class="ri-arrow-right-s-line ml-auto"></i>' : ''}
                </a>
            </li>
            `;
        }).join('');

        return `
        <aside id="sidebar" class="fixed left-0 top-[70px] h-[calc(100vh-70px)] w-[280px] bg-white dark:bg-[#0B1121] border-r border-gray-100 dark:border-white/5 transform -translate-x-full lg:translate-x-0 transition-transform duration-300 z-40 overflow-y-auto">
            <div class="p-6">
                <div class="mb-8 px-4">
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-widest">Menu</p>
                </div>
                <ul class="space-y-2">
                    ${listHtml}
                </ul>
                
                <div class="mt-10 pt-10 border-t border-gray-50 dark:border-white/5">
                    <button onclick="DB.logout()" class="w-full flex items-center gap-4 px-4 py-3.5 rounded-2xl font-bold text-red-500 hover:bg-red-50 dark:hover:bg-red-500/10 transition-all">
                        <i class="ri-logout-box-line text-xl"></i>
                        <span class="tracking-wide">Sign Out</span>
                    </button>
                </div>
            </div>
        </aside>
        <div class="hidden lg:block w-[280px] shrink-0"></div> <!-- Spacer -->
        `;
    }
};

window.Component = Component;


