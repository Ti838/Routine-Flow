// ===================================
// ROUTINE FLOW - DATA MANAGEMENT
// LocalStorage CRUD operations and data models
// ===================================

// --- DATA MODELS ---

// Default departments
const DEFAULT_DEPARTMENTS = [
    'Science',
    'Arts',
    'Commerce',
    'Engineering',
    'Business',
    'Law',
    'Medical'
];

// --- LOCALSTORAGE HELPERS ---

// Get data from localStorage
function getFromStorage(key) {
    const data = localStorage.getItem(key);
    return data ? JSON.parse(data) : null;
}

// Save data to localStorage
function saveToStorage(key, data) {
    localStorage.setItem(key, JSON.stringify(data));
}

// --- USER OPERATIONS ---

// Get all users
function getAllUsers() {
    return getFromStorage('users') || [];
}

// Get user by ID
function getUserById(userId) {
    const users = getAllUsers();
    return users.find(user => user.userId === userId);
}

// Get user by email
function getUserByEmail(email) {
    const users = getAllUsers();
    return users.find(user => user.email === email);
}

// Add new user
function addUser(user) {
    const users = getAllUsers();
    users.push(user);
    saveToStorage('users', users);
    return user;
}

// Update user
function updateUser(userId, updatedData) {
    const users = getAllUsers();
    const index = users.findIndex(user => user.userId === userId);

    if (index !== -1) {
        users[index] = { ...users[index], ...updatedData };
        saveToStorage('users', users);
        return users[index];
    }

    return null;
}

// Delete user
function deleteUser(userId) {
    const users = getAllUsers();
    const filteredUsers = users.filter(user => user.userId !== userId);
    saveToStorage('users', filteredUsers);
    return true;
}

// Get users by role
function getUsersByRole(role) {
    const users = getAllUsers();
    return users.filter(user => user.role === role);
}

// Get users by department
function getUsersByDepartment(department) {
    const users = getAllUsers();
    return users.filter(user => user.department === department);
}

// --- ROUTINE OPERATIONS ---

// Get all routines
function getAllRoutines() {
    return getFromStorage('routines') || [];
}

// Get routine by ID
function getRoutineById(routineId) {
    const routines = getAllRoutines();
    return routines.find(routine => routine.id === routineId);
}

// Add new routine
function addRoutine(routine) {
    const routines = getAllRoutines();
    routine.id = generateId('RT');
    routine.createdAt = new Date().toISOString();
    routines.push(routine);
    saveToStorage('routines', routines);
    return routine;
}

// Update routine
function updateRoutine(routineId, updatedData) {
    const routines = getAllRoutines();
    const index = routines.findIndex(routine => routine.id === routineId);

    if (index !== -1) {
        routines[index] = { ...routines[index], ...updatedData };
        saveToStorage('routines', routines);
        return routines[index];
    }

    return null;
}

// Delete routine
function deleteRoutine(routineId) {
    const routines = getAllRoutines();
    const filteredRoutines = routines.filter(routine => routine.id !== routineId);
    saveToStorage('routines', filteredRoutines);
    return true;
}

// Get routines by date
function getRoutinesByDate(date) {
    const routines = getAllRoutines();
    return routines.filter(routine => routine.date === date);
}

// Get routines by department
function getRoutinesByDepartment(department) {
    const routines = getAllRoutines();
    return routines.filter(routine =>
        routine.department === department ||
        routine.department === 'All'
    );
}

// Get routines for specific student
function getRoutinesForStudent(studentId) {
    const routines = getAllRoutines();
    const student = getUserById(studentId);

    if (!student) return [];

    return routines.filter(routine => {
        // Check if routine is for student's department
        const isDepartmentMatch = routine.department === student.department || routine.department === 'All';

        // Check if student is specifically assigned
        const isAssigned = routine.assignedStudents && routine.assignedStudents.includes(studentId);

        return isDepartmentMatch || isAssigned;
    });
}

// Get routines created by teacher
function getRoutinesByTeacher(teacherId) {
    const routines = getAllRoutines();
    return routines.filter(routine => routine.createdBy === teacherId);
}

// --- STUDENT CUSTOM ROUTINE OPERATIONS ---

// Get custom routines for a student
function getCustomRoutines(studentId) {
    const allCustom = getFromStorage('custom_routines') || [];
    return allCustom.filter(r => r.studentId === studentId);
}

// Save a new custom routine entry
function addCustomRoutine(customRoutine) {
    const allCustom = getFromStorage('custom_routines') || [];
    customRoutine.id = generateId('CR');
    customRoutine.createdAt = new Date().toISOString();
    allCustom.push(customRoutine);
    saveToStorage('custom_routines', allCustom);
    return customRoutine;
}

// Delete a custom routine entry
function deleteCustomRoutine(id) {
    const allCustom = getFromStorage('custom_routines') || [];
    const filtered = allCustom.filter(r => r.id !== id);
    saveToStorage('custom_routines', filtered);
    return true;
}

// --- DEPARTMENT OPERATIONS ---

// Get all departments
function getAllDepartments() {
    const departments = getFromStorage('departments');
    return departments || DEFAULT_DEPARTMENTS;
}

// Add department
function addDepartment(departmentName) {
    const departments = getAllDepartments();
    if (!departments.includes(departmentName)) {
        departments.push(departmentName);
        saveToStorage('departments', departments);
        return true;
    }
    return false;
}

// Delete department
function deleteDepartment(departmentName) {
    const departments = getAllDepartments();
    const filtered = departments.filter(dept => dept !== departmentName);
    saveToStorage('departments', filtered);
    return true;
}

// --- TASK COMPLETION OPERATIONS ---

// Get all completions
function getAllCompletions() {
    return getFromStorage('completions') || [];
}

// Mark task as complete
function markTaskComplete(routineId, studentId) {
    const completions = getAllCompletions();

    const completion = {
        routineId,
        studentId,
        completedAt: new Date().toISOString()
    };

    // Check if already completed
    const exists = completions.find(c =>
        c.routineId === routineId && c.studentId === studentId
    );

    if (!exists) {
        completions.push(completion);
        saveToStorage('completions', completions);
    }

    return completion;
}

// Mark task as incomplete
function markTaskIncomplete(routineId, studentId) {
    const completions = getAllCompletions();
    const filtered = completions.filter(c =>
        !(c.routineId === routineId && c.studentId === studentId)
    );
    saveToStorage('completions', filtered);
    return true;
}

// Check if task is completed
function isTaskCompleted(routineId, studentId) {
    const completions = getAllCompletions();
    return completions.some(c =>
        c.routineId === routineId && c.studentId === studentId
    );
}

// Get completion percentage for student
function getStudentCompletionPercentage(studentId) {
    const routines = getRoutinesForStudent(studentId);
    const completions = getAllCompletions().filter(c => c.studentId === studentId);

    if (routines.length === 0) return 0;

    return Math.round((completions.length / routines.length) * 100);
}

// --- STATISTICS ---

// Get total count by role
function getTotalByRole(role) {
    const users = getUsersByRole(role);
    return users.length;
}

// Get total routines
function getTotalRoutines() {
    const routines = getAllRoutines();
    return routines.length;
}

// Get total departments
function getTotalDepartments() {
    const departments = getAllDepartments();
    return departments.length;
}

// --- AUTO-GENERATE ROUTINE ---

// Generate automatic routine for a department
function generateAutoRoutine(department, startDate) {
    const tasks = [
        { name: 'Lecture', duration: 60, description: 'Main lecture session' },
        { name: 'Tutorial', duration: 30, description: 'Tutorial and practice' },
        { name: 'Lab Work', duration: 90, description: 'Practical lab session' },
        { name: 'Study Group', duration: 45, description: 'Group study session' },
        { name: 'Assignment', duration: 60, description: 'Complete assignment' }
    ];

    const routines = [];
    const weekDates = getWeekDates(new Date(startDate));

    // Generate routines for each weekday (Mon-Fri)
    for (let i = 1; i <= 5; i++) {
        const date = weekDates[i];
        let currentTime = 8; // Start at 8 AM

        // Generate 3-4 tasks per day
        const tasksPerDay = Math.floor(Math.random() * 2) + 3;

        for (let j = 0; j < tasksPerDay; j++) {
            const task = tasks[Math.floor(Math.random() * tasks.length)];

            const startHour = currentTime;
            const startMinute = 0;
            const endTime = currentTime + (task.duration / 60);
            const endHour = Math.floor(endTime);
            const endMinute = Math.round((endTime % 1) * 60);

            const routine = {
                id: generateId('RT'),
                taskName: task.name,
                description: task.description,
                date: date,
                timeStart: `${String(startHour).padStart(2, '0')}:${String(startMinute).padStart(2, '0')}`,
                timeEnd: `${String(endHour).padStart(2, '0')}:${String(endMinute).padStart(2, '0')}`,
                department: department,
                assignedStudents: [],
                createdBy: 'system',
                createdAt: new Date().toISOString()
            };

            routines.push(routine);
            currentTime = endTime + 0.5; // 30 min break

            if (currentTime >= 16) break; // Stop at 4 PM
        }
    }

    // Save all generated routines
    const allRoutines = getAllRoutines();
    const combined = [...allRoutines, ...routines];
    saveToStorage('routines', combined);

    return routines;
}

// --- SEED DATA ---

// Initialize app with sample data
function initializeAppData() {
    // Check if already initialized
    if (getFromStorage('initialized')) {
        return;
    }

    // Create sample users
    const sampleUsers = [
        {
            userId: 'ADMIN001',
            name: 'Admin User',
            email: 'admin@routineflow.com',
            password: 'admin123',
            role: 'Admin',
            department: 'Administration',
            gender: 'Male',
            createdAt: new Date().toISOString()
        },
        {
            userId: 'TEACH001',
            name: 'Sarah Johnson',
            email: 'teacher@routineflow.com',
            password: 'teacher123',
            role: 'Teacher',
            department: 'Science',
            gender: 'Female',
            createdAt: new Date().toISOString()
        },
        {
            userId: 'STU001',
            name: 'John Smith',
            email: 'student@routineflow.com',
            password: 'student123',
            role: 'Student',
            department: 'Science',
            gender: 'Male',
            createdAt: new Date().toISOString()
        }
    ];

    saveToStorage('users', sampleUsers);
    saveToStorage('departments', DEFAULT_DEPARTMENTS);
    saveToStorage('routines', []);
    saveToStorage('completions', []);

    // Generate some sample routines
    generateAutoRoutine('Science', getCurrentDate());

    // Mark as initialized
    saveToStorage('initialized', true);
}

// Reset all data
function resetAllData() {
    localStorage.clear();
    initializeAppData();
}

// Initialize on load
if (typeof document !== 'undefined') {
    document.addEventListener('DOMContentLoaded', () => {
        initializeAppData();
    });
}
