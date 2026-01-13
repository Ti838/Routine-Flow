document.addEventListener('DOMContentLoaded', function () {
    fetchRoutines();
    updateGreeting();
});

function updateGreeting() {
    const hour = new Date().getHours();
    const greetingElement = document.getElementById('greeting');
    if (!greetingElement) return;

    let greeting = 'Good Morning';
    if (hour >= 12 && hour < 17) greeting = 'Good Afternoon';
    else if (hour >= 17) greeting = 'Good Evening';

    greetingElement.textContent = `${greeting}, Student`;
}

async function fetchRoutines() {
    try {
        // In a real app, we might get these from a user profile endpoint or session
        // For now, we'll fetch all or mock the dept/semester
        const response = await fetch('../api/get_routine.php?department=Science&semester=Fall 2025');
        const data = await response.json();

        if (data.success) {
            renderTodaySchedule(data.payload);
            renderNextClass(data.payload);
            updateStats(data.payload);
        } else {
            console.error('Failed to fetch routines:', data.message);
        }
    } catch (error) {
        console.error('Error fetching routines:', error);
    }
}

function renderTodaySchedule(routines) {
    const tableBody = document.querySelector('tbody'); // Assuming only one table for now
    if (!tableBody) return;

    tableBody.innerHTML = '';

    // Filter for today's routines
    const today = new Date().toLocaleDateString('en-US', { weekday: 'long' });
    const todaysRoutines = routines.filter(r => r.day === 'Monday'); // Hardcoded for demo/testing, usually matches 'today'

    if (todaysRoutines.length === 0) {
        tableBody.innerHTML = '<tr><td colspan="5" class="px-6 py-4 text-center text-gray-500">No classes scheduled for today.</td></tr>';
        return;
    }

    todaysRoutines.forEach(routine => {
        const row = `
            <tr class="hover:bg-gray-50 dark:hover:bg-white/5 transition-colors group">
                <td class="px-6 py-5">
                    <div class="font-bold text-gray-900 dark:text-white text-sm">${formatTime(routine.start_time)}</div>
                    <div class="text-xs text-gray-500 dark:text-gray-400 font-medium">to ${formatTime(routine.end_time)}</div>
                </td>
                <td class="px-6 py-5">
                    <div class="font-bold text-primary-blue dark:text-primary-light">${routine.subject}</div>
                    <div class="text-xs text-gray-500 dark:text-gray-400">Teacher: ${routine.teacher || 'N/A'}</div>
                </td>
                <td class="px-6 py-5">
                    <span class="px-3 py-1 rounded-lg bg-gray-100 dark:bg-white/10 text-gray-600 dark:text-gray-300 text-xs font-bold">Room ${routine.room}</span>
                </td>
                <td class="px-6 py-5">
                    <span class="px-3 py-1 rounded-lg bg-green-50 dark:bg-green-500/10 text-green-600 dark:text-green-400 text-xs font-bold border border-green-100 dark:border-green-500/20">Scheduled</span>
                </td>
                <td class="px-6 py-5 text-right space-x-2">
                   <button class="p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-white/10 text-gray-400 hover:text-primary-blue transition-colors" title="Details"><i class="ri-information-line text-lg"></i></button>
                </td>
            </tr>
        `;
        tableBody.innerHTML += row;
    });
}

function renderNextClass(routines) {
    // Logic to find the next class based on current time
    // For simplicity, just pick the first standard one or placeholder
    // This is optional for the MVP
}

function updateStats(routines) {
    const totalClassesEl = document.getElementById('totalClasses');
    if (totalClassesEl) {
        totalClassesEl.textContent = routines.length;
    }
}

function formatTime(timeString) {
    if (!timeString) return '';
    const date = new Date(`2000-01-01T${timeString}`);
    return date.toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit' });
}
