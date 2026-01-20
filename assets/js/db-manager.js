/**
 * Simple Mock Database & Session Manager
 * Uses localStorage to persist user data and session state.
 */

const DB = {
    users: [
        {
            id: 'u1',
            name: 'Timon Biswas',
            username: 'timon_dev',
            email: 'timon@example.com',
            password: 'password123',
            role: 'student', // or 'teacher', 'admin'
            profile_pic: '', // filename or URL
            gender: 'Male',
            // Student specific
            student_id: '2023001',
            semester: '3rd Year, 1st Sem',
            // Teacher specific
            teacher_id: '',
            department: '',
            created_at: new Date().toISOString()
        }
    ],

    init() {
        if (!localStorage.getItem('rf_users')) {
            localStorage.setItem('rf_users', JSON.stringify(this.users));
        }
        // Auto-login the first user if no session exists (FOR DEVELOPMENT CONVENIENCE)
        if (!localStorage.getItem('rf_session')) {
            this.login('timon@example.com', 'password123');
        }
    },

    getUsers() {
        return JSON.parse(localStorage.getItem('rf_users') || '[]');
    },

    saveUsers(users) {
        localStorage.setItem('rf_users', JSON.stringify(users));
    },

    login(email, password) {
        const users = this.getUsers();
        const user = users.find(u => u.email === email && u.password === password);
        if (user) {
            const session = {
                user_id: user.id,
                role: user.role,
                name: user.name,
                login_time: Date.now()
            };
            localStorage.setItem('rf_session', JSON.stringify(session));
            return { success: true, user };
        }
        return { success: false, message: 'Invalid credentials' };
    },

    logout() {
        localStorage.removeItem('rf_session');
        // Redirect to server-side logout (use absolute root path which exists in this repo)
        window.location.href = '/logout.php';
    },

    getCurrentUser() {
        const session = JSON.parse(localStorage.getItem('rf_session'));
        if (!session) return null;

        const users = this.getUsers();
        return users.find(u => u.id === session.user_id) || null;
    },

    updateUser(userId, updateData) {
        const users = this.getUsers();
        const index = users.findIndex(u => u.id === userId);

        if (index !== -1) {
            users[index] = { ...users[index], ...updateData };
            this.saveUsers(users);

            // Update session if name changed
            const session = JSON.parse(localStorage.getItem('rf_session'));
            if (session && session.user_id === userId) {
                session.name = users[index].name;
                localStorage.setItem('rf_session', JSON.stringify(session));
            }

            return { success: true, user: users[index] };
        }
        return { success: false, message: 'User not found' };
    },

    // Calculate profile completion percentage
    calculateCompletion(user) {
        if (!user) return 0;
        const fields = ['name', 'email', 'gender', 'profile_pic'];
        if (user.role === 'student') fields.push('student_id', 'semester');
        if (user.role === 'teacher') fields.push('teacher_id', 'department');

        let filled = 0;
        fields.forEach(f => {
            if (user[f] && user[f].length > 0) filled++;
        });

        return Math.round((filled / fields.length) * 100);
    }
};

// Initialize on load
DB.init();

// Expose globally
window.DB = DB;
