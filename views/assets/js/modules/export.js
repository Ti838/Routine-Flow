/**
 * Routine Flow - Export Module
 * Handles PDF export functionality
 */

document.addEventListener('DOMContentLoaded', () => {
    const exportBtn = document.getElementById('exportPdf');
    if (!exportBtn) return;

    exportBtn.addEventListener('click', () => {
        // Add a temporary class to body for print styling
        document.body.classList.add('printing-mode');

        // Notify user
        const originalText = exportBtn.innerHTML;
        exportBtn.innerHTML = '<i class="ri-loader-4-line animate-spin"></i> Preparing...';
        exportBtn.disabled = true;

        setTimeout(() => {
            window.print();

            // Revert changes
            document.body.classList.remove('printing-mode');
            exportBtn.innerHTML = originalText;
            exportBtn.disabled = false;
        }, 500);
    });
});
