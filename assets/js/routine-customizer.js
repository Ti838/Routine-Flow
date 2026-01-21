

class RoutineCustomizer {
    constructor() {
        this.apiUrl = '../api/customize_routine.php';
        this.customizations = new Map();
        this.colorPalette = [
            { name: 'Red', code: '#FF6B6B' },
            { name: 'Teal', code: '#4ECDC4' },
            { name: 'Yellow', code: '#FFE66D' },
            { name: 'Mint', code: '#95E1D3' },
            { name: 'Pink', code: '#F38181' },
            { name: 'Purple', code: '#AA96DA' },
            { name: 'Rose', code: '#FCBAD3' },
            { name: 'Green', code: '#A8E6CF' },
            { name: 'Orange', code: '#FFB347' },
            { name: 'Blue', code: '#87CEEB' }
        ];

        this.init();
    }

    async init() {
        await this.loadCustomizations();
        this.applyCustomizations();
        this.attachEventListeners();
    }

    async loadCustomizations() {
        try {
            const response = await fetch('../api/get_customizations.php');
            const result = await response.json();

            if (result.success) {
                result.data.forEach(custom => {
                    this.customizations.set(custom.routine_id, custom);
                });
            }
        } catch (error) {
            console.error('Failed to load customizations:', error);
        }
    }

    applyCustomizations() {
        this.customizations.forEach((custom, routineId) => {
            const routineCard = document.querySelector(`[data-routine-id="${routineId}"]`);
            if (!routineCard) return;

            
            if (custom.color_code) {
                routineCard.style.borderLeft = `4px solid ${custom.color_code}`;
                routineCard.style.backgroundColor = `${custom.color_code}15`;
            }

            
            if (custom.is_starred) {
                const starBtn = routineCard.querySelector('.btn-star');
                if (starBtn) {
                    starBtn.classList.add('starred');
                    starBtn.querySelector('i').className = 'ri-star-fill';
                }
            }

            
            if (custom.priority > 0) {
                const priorityBadge = this.createPriorityBadge(custom.priority);
                routineCard.querySelector('.routine-header')?.appendChild(priorityBadge);
            }
        });
    }

    attachEventListeners() {
        
        document.querySelectorAll('.btn-color-picker').forEach(btn => {
            btn.addEventListener('click', (e) => this.showColorPicker(e));
        });

        
        document.querySelectorAll('.btn-star').forEach(btn => {
            btn.addEventListener('click', (e) => this.toggleStar(e));
        });

        
        document.querySelectorAll('.btn-priority').forEach(btn => {
            btn.addEventListener('click', (e) => this.showPriorityMenu(e));
        });
    }

    showColorPicker(event) {
        event.stopPropagation();
        const btn = event.currentTarget;
        const routineId = btn.closest('[data-routine-id]').dataset.routineId;

        
        document.querySelectorAll('.color-picker-popover').forEach(p => p.remove());

        
        const picker = document.createElement('div');
        picker.className = 'color-picker-popover';
        picker.innerHTML = `
            <div class="color-picker-header">
                <span>Choose Color</span>
                <button class="btn-clear-color" data-routine-id="${routineId}">
                    <i class="ri-close-line"></i> Clear
                </button>
            </div>
            <div class="color-picker-grid">
                ${this.colorPalette.map(color => `
                    <button class="color-option" 
                            data-color="${color.code}" 
                            data-routine-id="${routineId}"
                            title="${color.name}"
                            style="background-color: ${color.code}">
                    </button>
                `).join('')}
            </div>
        `;

        
        const rect = btn.getBoundingClientRect();
        picker.style.top = `${rect.bottom + 10}px`;
        picker.style.left = `${rect.left}px`;

        document.body.appendChild(picker);

        
        picker.querySelectorAll('.color-option').forEach(option => {
            option.addEventListener('click', (e) => this.selectColor(e));
        });

        picker.querySelector('.btn-clear-color').addEventListener('click', (e) => {
            this.clearColor(e);
        });

        
        setTimeout(() => {
            document.addEventListener('click', function closePickerHandler() {
                picker.remove();
                document.removeEventListener('click', closePickerHandler);
            });
        }, 100);
    }

    async selectColor(event) {
        event.stopPropagation();
        const btn = event.currentTarget;
        const routineId = parseInt(btn.dataset.routineId);
        const colorCode = btn.dataset.color;

        await this.saveCustomization(routineId, { color_code: colorCode });

        
        const routineCard = document.querySelector(`[data-routine-id="${routineId}"]`);
        if (routineCard) {
            routineCard.style.borderLeft = `4px solid ${colorCode}`;
            routineCard.style.backgroundColor = `${colorCode}15`;
        }

        
        document.querySelector('.color-picker-popover')?.remove();
    }

    async clearColor(event) {
        event.stopPropagation();
        const routineId = parseInt(event.currentTarget.dataset.routineId);

        await this.saveCustomization(routineId, { color_code: null });

        
        const routineCard = document.querySelector(`[data-routine-id="${routineId}"]`);
        if (routineCard) {
            routineCard.style.borderLeft = '';
            routineCard.style.backgroundColor = '';
        }

        document.querySelector('.color-picker-popover')?.remove();
    }

    async toggleStar(event) {
        event.stopPropagation();
        const btn = event.currentTarget;
        const routineId = parseInt(btn.closest('[data-routine-id]').dataset.routineId);
        const isStarred = btn.classList.contains('starred');

        await this.saveCustomization(routineId, { is_starred: !isStarred });

        
        btn.classList.toggle('starred');
        const icon = btn.querySelector('i');
        icon.className = isStarred ? 'ri-star-line' : 'ri-star-fill';
    }

    showPriorityMenu(event) {
        event.stopPropagation();
        const btn = event.currentTarget;
        const routineId = btn.closest('[data-routine-id]').dataset.routineId;

        
        const menu = document.createElement('div');
        menu.className = 'priority-menu-popover';
        menu.innerHTML = `
            <button class="priority-option" data-priority="0" data-routine-id="${routineId}">
                <span class="priority-badge priority-0">Normal</span>
            </button>
            <button class="priority-option" data-priority="1" data-routine-id="${routineId}">
                <span class="priority-badge priority-1">Low</span>
            </button>
            <button class="priority-option" data-priority="2" data-routine-id="${routineId}">
                <span class="priority-badge priority-2">Medium</span>
            </button>
            <button class="priority-option" data-priority="3" data-routine-id="${routineId}">
                <span class="priority-badge priority-3">High</span>
            </button>
        `;

        const rect = btn.getBoundingClientRect();
        menu.style.top = `${rect.bottom + 10}px`;
        menu.style.left = `${rect.left}px`;

        document.body.appendChild(menu);

        menu.querySelectorAll('.priority-option').forEach(option => {
            option.addEventListener('click', (e) => this.selectPriority(e));
        });

        setTimeout(() => {
            document.addEventListener('click', function closeMenuHandler() {
                menu.remove();
                document.removeEventListener('click', closeMenuHandler);
            });
        }, 100);
    }

    async selectPriority(event) {
        event.stopPropagation();
        const btn = event.currentTarget;
        const routineId = parseInt(btn.dataset.routineId);
        const priority = parseInt(btn.dataset.priority);

        await this.saveCustomization(routineId, { priority });

        
        const routineCard = document.querySelector(`[data-routine-id="${routineId}"]`);
        if (routineCard) {
            const existingBadge = routineCard.querySelector('.priority-badge');
            if (existingBadge) existingBadge.remove();

            if (priority > 0) {
                const badge = this.createPriorityBadge(priority);
                routineCard.querySelector('.routine-header')?.appendChild(badge);
            }
        }

        document.querySelector('.priority-menu-popover')?.remove();
    }

    createPriorityBadge(priority) {
        const labels = ['Normal', 'Low', 'Medium', 'High'];
        const badge = document.createElement('span');
        badge.className = `priority-badge priority-${priority}`;
        badge.textContent = labels[priority];
        return badge;
    }

    async saveCustomization(routineId, data) {
        try {
            
            const existing = this.customizations.get(routineId) || {};

            
            const customization = {
                routine_id: routineId,
                color_code: data.color_code !== undefined ? data.color_code : existing.color_code,
                is_starred: data.is_starred !== undefined ? data.is_starred : existing.is_starred,
                priority: data.priority !== undefined ? data.priority : existing.priority,
                notes: data.notes !== undefined ? data.notes : existing.notes
            };

            const response = await fetch(this.apiUrl, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(customization)
            });

            const result = await response.json();

            if (result.success) {
                this.customizations.set(routineId, customization);
                this.showToast('Customization saved!', 'success');
            } else {
                throw new Error(result.message);
            }
        } catch (error) {
            console.error('Failed to save customization:', error);
            this.showToast('Failed to save customization', 'error');
        }
    }

    showToast(message, type = 'info') {
        const toast = document.createElement('div');
        toast.className = `toast toast-${type}`;
        toast.innerHTML = `
            <i class="ri-${type === 'success' ? 'checkbox-circle' : 'error-warning'}-line"></i>
            <span>${message}</span>
        `;
        document.body.appendChild(toast);

        setTimeout(() => toast.classList.add('show'), 100);
        setTimeout(() => {
            toast.classList.remove('show');
            setTimeout(() => toast.remove(), 300);
        }, 2000);
    }
}


let routineCustomizer;
document.addEventListener('DOMContentLoaded', () => {
    routineCustomizer = new RoutineCustomizer();
});


