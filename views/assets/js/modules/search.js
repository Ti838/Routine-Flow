/**
 * Routine Flow - Client-side Search Module
 * Provides real-time filtering for cards/items
 */

document.addEventListener('DOMContentLoaded', () => {
    const searchInput = document.getElementById('routineSearch');
    if (!searchInput) return;

    const cards = document.querySelectorAll('.routine-card');

    searchInput.addEventListener('input', (e) => {
        const query = e.target.value.toLowerCase().trim();

        cards.forEach(card => {
            const subject = card.getAttribute('data-subject')?.toLowerCase() || '';
            const teacher = card.getAttribute('data-teacher')?.toLowerCase() || '';
            const room = card.getAttribute('data-room')?.toLowerCase() || '';

            if (subject.includes(query) || teacher.includes(query) || room.includes(query)) {
                card.style.display = '';
                card.style.opacity = '1';
                card.style.transform = 'scale(1)';
            } else {
                card.style.opacity = '0';
                card.style.transform = 'scale(0.95)';
                setTimeout(() => {
                    if (searchInput.value.toLowerCase().trim() === query) {
                        card.style.display = 'none';
                    }
                }, 300);
            }
        });
    });
});
