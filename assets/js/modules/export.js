

document.addEventListener('DOMContentLoaded', () => {
    const exportBtn = document.getElementById('exportPdf');
    if (!exportBtn) return;

    exportBtn.addEventListener('click', () => {
        
        document.body.classList.add('printing-mode');

        
        const originalText = exportBtn.innerHTML;
        exportBtn.innerHTML = '<i class="ri-loader-4-line animate-spin"></i> Preparing...';
        exportBtn.disabled = true;

        setTimeout(() => {
            window.print();

            
            document.body.classList.remove('printing-mode');
            exportBtn.innerHTML = originalText;
            exportBtn.disabled = false;
        }, 500);
    });
});


