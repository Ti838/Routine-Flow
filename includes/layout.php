<?php
// layout.php

function renderNavbar($role, $name)
{
    ?>
    <nav
        class="navbar fixed top-0 w-full z-50 bg-white/80 dark:bg-[#0B1121]/80 backdrop-blur-xl border-b border-gray-100 dark:border-white/5 h-20">
        <div class="max-w-[1600px] mx-auto h-full px-6 flex items-center justify-between">
            <div class="flex items-center gap-4">
                <button id="mobileMenuToggle"
                    class="lg:hidden w-10 h-10 rounded-xl bg-gray-50 dark:bg-white/5 flex items-center justify-center text-gray-500">
                    <i class="ri-menu-2-line text-2xl"></i>
                </button>
                <a href="../index.php" class="flex items-center gap-3">
                    <img src="../assets/img/favicon.png" alt="RF"
                        class="w-10 h-10 rounded-xl shadow-lg shadow-indigo-500/20">
                    <span
                        class="text-xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-blue-600 to-indigo-600 hidden sm:block">Routine
                        Flow</span>
                </a>
            </div>
            <div class="flex items-center gap-6">

                <!-- Theme Toggle -->
                <button onclick="toggleTheme()"
                    class="w-10 h-10 rounded-full bg-gray-100 dark:bg-white/5 flex items-center justify-center text-gray-500 dark:text-yellow-400 hover:bg-gray-200 dark:hover:bg-white/10 transition-all"
                    title="Toggle Theme">
                    <i class="ri-sun-line hidden dark:block text-xl"></i>
                    <i class="ri-moon-line dark:hidden text-xl"></i>
                </button>

                <!-- Notifications -->
                <div class="relative">
                    <button onclick="document.getElementById('notificationDropdown').classList.toggle('hidden')"
                        class="w-10 h-10 rounded-full bg-gray-100 dark:bg-white/5 flex items-center justify-center text-gray-500 dark:text-gray-400 hover:bg-gray-200 dark:hover:bg-white/10 transition-all relative">
                        <i class="ri-notification-3-line text-xl"></i>
                        <span
                            class="absolute top-2 right-2 w-2 h-2 rounded-full bg-red-500 border border-white dark:border-[#0B1121]"></span>
                    </button>

                    <!-- Dropdown -->
                    <div id="notificationDropdown"
                        class="hidden absolute top-14 right-0 w-80 bg-white dark:bg-[#16213e] rounded-2xl shadow-2xl border border-gray-100 dark:border-white/5 overflow-hidden z-[60] animate-in fade-in slide-in-from-top-2">
                        <div
                            class="p-4 border-b border-gray-100 dark:border-white/5 flex items-center justify-between bg-gray-50/50 dark:bg-white/5">
                            <h3 class="font-bold text-sm uppercase tracking-widest">Notifications</h3>
                            <span class="text-[10px] bg-indigo-500 text-white px-2 py-0.5 rounded-full font-bold">New</span>
                        </div>
                        <div class="max-h-[300px] overflow-y-auto">
                            <?php
                            global $conn;
                            try {
                                $n_stmt = $conn->query("SELECT * FROM notices ORDER BY created_at DESC LIMIT 5");
                                $notices = $n_stmt->fetchAll(PDO::FETCH_ASSOC);

                                if (count($notices) > 0) {
                                    foreach ($notices as $note) {
                                        echo '<div class="p-4 border-b border-gray-100 dark:border-white/5 hover:bg-gray-50 dark:hover:bg-white/5 transition-colors group cursor-pointer">
                                                <div class="flex gap-3">
                                                    <div class="w-8 h-8 rounded-full bg-indigo-100 dark:bg-indigo-500/20 flex items-center justify-center text-indigo-600 flex-shrink-0">
                                                        <i class="ri-megaphone-line"></i>
                                                    </div>
                                                    <div>
                                                        <h4 class="text-sm font-bold text-gray-800 dark:text-gray-200 leading-tight mb-1 group-hover:text-indigo-500 transition-colors">' . htmlspecialchars($note['title']) . '</h4>
                                                        <p class="text-xs text-gray-400 line-clamp-2">' . htmlspecialchars($note['content']) . '</p>
                                                        <span class="text-[10px] font-bold text-gray-300 mt-2 block">' . date('M d, H:i', strtotime($note['created_at'])) . '</span>
                                                    </div>
                                                </div>
                                            </div>';
                                    }
                                } else {
                                    echo '<div class="p-8 text-center text-gray-400 text-sm font-bold">No new notifications</div>';
                                }
                            } catch (Exception $e) {
                                echo '<div class="p-4 text-center text-red-400 text-xs">Error loading notices</div>';
                            }
                            ?>
                        </div>
                        <a href="../shared/notices.php"
                            class="block p-3 text-center text-xs font-bold text-indigo-500 hover:bg-indigo-50 dark:hover:bg-indigo-500/10 transition-colors">
                            View All Notifications
                        </a>
                    </div>
                </div>
                <a href="../shared/profile.php"
                    class="flex items-center gap-4 pl-6 border-l border-gray-200 dark:border-white/10 hover:opacity-80 transition-opacity"
                    title="Edit Profile">
                    <div class="hidden md:block text-right">
                        <span class="block text-sm font-bold dark:text-white">
                            <?php echo htmlspecialchars($name); ?>
                        </span>
                        <span class="text-xs text-gray-400 uppercase tracking-widest">
                            <?php echo ucfirst($role); ?>
                        </span>
                    </div>
                    <?php
                    // Fetch fresh profile pic if available (optional optimization, but reliable)
                    $pic_path = isset($_SESSION['profile_pic']) && !empty($_SESSION['profile_pic']) ? '../assets/uploads/profiles/' . $_SESSION['profile_pic'] : "https://ui-avatars.com/api/?background=0D8ABC&color=fff&name=" . urlencode($name);

                    // Note: To make session profile_pic dynamic, we should update session on login. 
                    // For now, we fallback to UI Avatars if not explicitly in session.
                    ?>
                    <img src="<?php echo $pic_path; ?>"
                        class="w-10 h-10 rounded-full bg-gray-100 dark:bg-dark-bg p-0.5 border border-indigo-500/30 object-cover">
                </a>
                <a href="../logout.php" id="logoutBtn"
                    class="p-2 text-red-500 hover:bg-red-500/10 rounded-lg transition-all" title="Logout">
                    <i class="ri-logout-box-line text-2xl"></i>
                </a>
            </div>
        </div>
    </nav>
    <div class="h-20"></div> <!-- Spacer for fixed navbar -->
    <?php
}

function renderSidebar($role, $active_page)
{
    $menu = [];
    if ($role === 'admin') {
        $menu = [
            ['title' => 'Dashboard', 'icon' => 'ri-dashboard-line', 'link' => '../admin/dashboard.php'],
            ['title' => 'User Management', 'icon' => 'ri-user-settings-line', 'link' => '../admin/users.php'],
            ['title' => 'Departments', 'icon' => 'ri-building-line', 'link' => '../admin/departments.php'],
            ['title' => 'Create Routine', 'icon' => 'ri-calendar-event-line', 'link' => '../admin/create-routine.php'],
            ['title' => 'Routine Library', 'icon' => 'ri-list-settings-line', 'link' => '../admin/manage_routines.php'],
            ['title' => 'Analytics', 'icon' => 'ri-bar-chart-2-line', 'link' => '../admin/analytics.php'],
            ['title' => 'Bulletins', 'icon' => 'ri-notification-badge-line', 'link' => '../admin/notices.php'],
            ['title' => 'Activity Log', 'icon' => 'ri-history-line', 'link' => '../admin/activity-log.php'],
        ];
    } elseif ($role === 'teacher') {
        $menu = [
            ['title' => 'Dashboard', 'icon' => 'ri-dashboard-line', 'link' => '../teacher/dashboard.php'],
            ['title' => 'My Classes', 'icon' => 'ri-calendar-todo-line', 'link' => '../teacher/today.php'],
            ['title' => 'Submit Routine', 'icon' => 'ri-upload-cloud-2-line', 'link' => '../teacher/create-routine.php'],
            ['title' => 'Department View', 'icon' => 'ri-building-2-line', 'link' => '../teacher/department.php'],
        ];
    } elseif ($role === 'student') {
        $menu = [
            ['title' => 'Dashboard', 'icon' => 'ri-dashboard-line', 'link' => '../student/dashboard.php'],
            ['title' => 'Today\'s Routine', 'icon' => 'ri-calendar-check-line', 'link' => '../student/today.php'],
            ['title' => 'Weekly Planner', 'icon' => 'ri-calendar-2-line', 'link' => '../student/weekly.php'],
            ['title' => 'Custom Builder', 'icon' => 'ri-equalizer-line', 'link' => '../student/custom-routine.php'],
        ];
    }

    // Common settings for all roles
    $settings_menu = [
        ['title' => 'My Profile', 'icon' => 'ri-user-3-line', 'link' => '../shared/profile.php'],
        ['title' => 'Settings', 'icon' => 'ri-settings-4-line', 'link' => '../shared/settings.php'],
    ];

    ?>
    <div id="sidebarOverlay" class="fixed inset-0 bg-black/50 z-40 hidden lg:hidden transition-opacity"></div>
    <aside id="mainSidebar"
        class="w-72 bg-white dark:bg-[#0B1121] border-r border-gray-100 dark:border-white/5 h-[calc(100vh-80px)] overflow-y-auto p-6 lg:block sticky top-20 z-40 transition-transform -translate-x-full lg:translate-x-0 fixed lg:sticky">
        <div class="space-y-8">
            <div>
                <p class="text-[10px] font-bold text-gray-400 dark:text-gray-500 uppercase tracking-[0.2em] mb-4">Main Menu
                </p>
                <nav class="space-y-1">
                    <?php foreach ($menu as $item):
                        $is_active = basename($_SERVER['PHP_SELF']) == basename($item['link']);
                        ?>
                        <a href="<?php echo $item['link']; ?>"
                            class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all group <?php echo $is_active ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-600/20' : 'text-gray-500 hover:bg-gray-50 dark:hover:bg-white/5 hover:text-indigo-600'; ?>">
                            <i
                                class="<?php echo $item['icon']; ?> text-xl <?php echo $is_active ? '' : 'group-hover:scale-110'; ?> transition-transform"></i>
                            <span class="font-semibold">
                                <?php echo $item['title']; ?>
                            </span>
                        </a>
                    <?php endforeach; ?>
                </nav>
            </div>

            <div>
                <p class="text-[10px] font-bold text-gray-400 dark:text-gray-500 uppercase tracking-[0.2em] mb-4">Account
                </p>
                <nav class="space-y-1">
                    <?php foreach ($settings_menu as $item):
                        $is_active = strpos($_SERVER['PHP_SELF'], basename($item['link'])) !== false;
                        ?>
                        <a href="<?php echo $item['link']; ?>"
                            class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all group <?php echo $is_active ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-600/20' : 'text-gray-500 hover:bg-gray-50 dark:hover:bg-white/5 hover:text-indigo-600'; ?>">
                            <i
                                class="<?php echo $item['icon']; ?> text-xl <?php echo $is_active ? '' : 'group-hover:scale-110'; ?> transition-transform"></i>
                            <span class="font-semibold">
                                <?php echo $item['title']; ?>
                            </span>
                        </a>
                    <?php endforeach; ?>
                </nav>
            </div>
        </div>
    </aside>
    <?php
}
?>