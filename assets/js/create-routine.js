let teacherOptions = [];

document.addEventListener('DOMContentLoaded', async function () {
    await fetchTeachers();
    addRow(); // Add one row by default

    document.getElementById('addClassBtn').addEventListener('click', addRow);
    document.getElementById('createRoutineForm').addEventListener('submit', submitRoutine);
});

async function fetchTeachers() {
    try {
        const response = await fetch('../api/get_teachers.php');
        const data = await response.json();
        if (data.success) {
            teacherOptions = data.payload;
        } else {
            console.error('Failed to fetch teachers:', data.message);
        }
    } catch (error) {
        console.error('Error fetching teachers:', error);
    }
}

function addRow() {
    const tbody = document.getElementById('routineList');
    const rowId = 'row-' + Date.now();

    // Generate Teacher Options HTML
    let teacherSelectHtml = '<option value="">Select Teacher</option>';
    teacherOptions.forEach(t => {
        teacherSelectHtml += `<option value="${t.id}">${t.name} (${t.department})</option>`;
    });

    const row = document.createElement('tr');
    row.id = rowId;
    row.className = 'group hover:bg-gray-50 dark:hover:bg-white/5 transition-colors';
    row.innerHTML = `
        <td class="p-4"><input type="text" name="subject" placeholder="Subject Name" required class="w-full bg-transparent border-b border-gray-200 dark:border-white/10 focus:border-primary-blue outline-none text-sm py-1"></td>
        <td class="p-4">
            <select name="day" required class="w-full bg-transparent border-b border-gray-200 dark:border-white/10 focus:border-primary-blue outline-none text-sm py-1">
                <option value="Monday">Monday</option>
                <option value="Tuesday">Tuesday</option>
                <option value="Wednesday">Wednesday</option>
                <option value="Thursday">Thursday</option>
                <option value="Friday">Friday</option>
                <option value="Saturday">Saturday</option>
                <option value="Sunday">Sunday</option>
            </select>
        </td>
        <td class="p-4"><input type="time" name="start" required class="w-full bg-transparent border-b border-gray-200 dark:border-white/10 focus:border-primary-blue outline-none text-sm py-1"></td>
        <td class="p-4"><input type="time" name="end" required class="w-full bg-transparent border-b border-gray-200 dark:border-white/10 focus:border-primary-blue outline-none text-sm py-1"></td>
        <td class="p-4"><input type="text" name="room" placeholder="Room No" required class="w-full bg-transparent border-b border-gray-200 dark:border-white/10 focus:border-primary-blue outline-none text-sm py-1"></td>
        <td class="p-4">
            <select name="teacher_id" required class="w-full bg-transparent border-b border-gray-200 dark:border-white/10 focus:border-primary-blue outline-none text-sm py-1">
                ${teacherSelectHtml}
            </select>
        </td>
        <td class="p-4 text-right">
            <button type="button" onclick="removeRow('${rowId}')" class="text-red-400 hover:text-red-500 transition-colors"><i class="ri-delete-bin-line text-lg"></i></button>
        </td>
    `;
    tbody.appendChild(row);
}

function removeRow(rowId) {
    const row = document.getElementById(rowId);
    if (row) row.remove();
}

async function submitRoutine(e) {
    e.preventDefault();

    const department = document.getElementById('department').value;
    const semester = document.getElementById('semester').value;

    if (!department || !semester) {
        alert('Please select Department and Semester.');
        return;
    }

    const items = [];
    const rows = document.querySelectorAll('#routineList tr');

    rows.forEach(row => {
        const subject = row.querySelector('input[name="subject"]').value;
        const day = row.querySelector('select[name="day"]').value;
        const start = row.querySelector('input[name="start"]').value;
        const end = row.querySelector('input[name="end"]').value;
        const room = row.querySelector('input[name="room"]').value;
        const teacher_id = row.querySelector('select[name="teacher_id"]').value;

        if (subject && day && start && end) {
            items.push({
                subject, day, start, end, room, teacher_id,
                department, semester
            });
        }
    });

    if (items.length === 0) {
        alert('Please add at least one Class slot.');
        return;
    }

    // Send to API
    try {
        const response = await fetch('../api/create_routine.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ items })
        });
        const result = await response.json();

        if (result.success) {
            alert('Routine published successfully!');
            window.location.href = 'dashboard.html';
        } else {
            alert('Error: ' + result.message);
        }
    } catch (error) {
        console.error('Error submitting routine:', error);
        alert('An error occurred. Please try again.');
    }
}
