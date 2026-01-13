/**
 * File Upload Module
 * Handles drag-drop file upload, preview, and progress tracking
 */

class FileUploader {
    constructor(options = {}) {
        this.dropZone = options.dropZone || document.getElementById('dropZone');
        this.fileInput = options.fileInput || document.getElementById('fileInput');
        this.previewContainer = options.previewContainer || document.getElementById('filePreview');
        this.uploadUrl = options.uploadUrl || '../api/upload_routine.php';
        this.maxFileSize = options.maxFileSize || 10485760; // 10MB
        this.allowedTypes = options.allowedTypes || ['application/pdf', 'image/png', 'image/jpeg'];
        this.files = [];

        this.init();
    }

    init() {
        if (!this.dropZone || !this.fileInput) return;

        // Drag and drop events
        this.dropZone.addEventListener('dragover', (e) => this.handleDragOver(e));
        this.dropZone.addEventListener('dragleave', (e) => this.handleDragLeave(e));
        this.dropZone.addEventListener('drop', (e) => this.handleDrop(e));

        // File input change
        this.fileInput.addEventListener('change', (e) => this.handleFileSelect(e));

        // Click to browse
        this.dropZone.addEventListener('click', () => this.fileInput.click());
    }

    handleDragOver(e) {
        e.preventDefault();
        e.stopPropagation();
        this.dropZone.classList.add('drag-over');
    }

    handleDragLeave(e) {
        e.preventDefault();
        e.stopPropagation();
        this.dropZone.classList.remove('drag-over');
    }

    handleDrop(e) {
        e.preventDefault();
        e.stopPropagation();
        this.dropZone.classList.remove('drag-over');

        const files = Array.from(e.dataTransfer.files);
        this.processFiles(files);
    }

    handleFileSelect(e) {
        const files = Array.from(e.target.files);
        this.processFiles(files);
    }

    processFiles(files) {
        files.forEach(file => {
            if (this.validateFile(file)) {
                this.files.push(file);
                this.createPreview(file);
            }
        });
    }

    validateFile(file) {
        // Check file size
        if (file.size > this.maxFileSize) {
            this.showError(`File "${file.name}" exceeds 10MB limit`);
            return false;
        }

        // Check file type
        if (!this.allowedTypes.includes(file.type)) {
            this.showError(`File "${file.name}" has invalid type. Allowed: PDF, PNG, JPG`);
            return false;
        }

        return true;
    }

    createPreview(file) {
        const fileId = Date.now() + Math.random();
        const fileCard = document.createElement('div');
        fileCard.className = 'file-preview-card';
        fileCard.dataset.fileId = fileId;

        const fileType = file.type.startsWith('image/') ? 'image' : 'pdf';
        const icon = fileType === 'image' ? 'ri-image-line' : 'ri-file-pdf-line';

        fileCard.innerHTML = `
            <div class="file-preview-icon">
                <i class="${icon}"></i>
            </div>
            <div class="file-preview-info">
                <div class="file-preview-name">${file.name}</div>
                <div class="file-preview-size">${this.formatFileSize(file.size)}</div>
                <div class="file-preview-progress">
                    <div class="progress-bar" style="width: 0%"></div>
                </div>
            </div>
            <button type="button" class="file-preview-remove" onclick="fileUploader.removeFile('${fileId}')">
                <i class="ri-close-line"></i>
            </button>
        `;

        if (fileType === 'image') {
            const reader = new FileReader();
            reader.onload = (e) => {
                fileCard.querySelector('.file-preview-icon').innerHTML = `
                    <img src="${e.target.result}" alt="${file.name}">
                `;
            };
            reader.readAsDataURL(file);
        }

        this.previewContainer.appendChild(fileCard);

        // Store file reference
        file._previewId = fileId;
    }

    removeFile(fileId) {
        // Remove from files array
        this.files = this.files.filter(f => f._previewId != fileId);

        // Remove preview card
        const card = this.previewContainer.querySelector(`[data-file-id="${fileId}"]`);
        if (card) card.remove();
    }

    async uploadFiles(additionalData = {}) {
        if (this.files.length === 0) {
            this.showError('No files selected');
            return;
        }

        const uploadPromises = this.files.map(file => this.uploadSingleFile(file, additionalData));

        try {
            const results = await Promise.all(uploadPromises);
            this.showSuccess(`Successfully uploaded ${results.length} file(s)`);
            return results;
        } catch (error) {
            this.showError('Upload failed: ' + error.message);
            throw error;
        }
    }

    async uploadSingleFile(file, additionalData = {}) {
        const formData = new FormData();
        formData.append('file', file);

        // Add additional data
        Object.keys(additionalData).forEach(key => {
            formData.append(key, additionalData[key]);
        });

        const card = this.previewContainer.querySelector(`[data-file-id="${file._previewId}"]`);
        const progressBar = card?.querySelector('.progress-bar');

        try {
            const response = await fetch(this.uploadUrl, {
                method: 'POST',
                body: formData
            });

            const result = await response.json();

            if (result.success) {
                if (progressBar) progressBar.style.width = '100%';
                if (card) card.classList.add('upload-complete');
                return result.data;
            } else {
                throw new Error(result.message);
            }
        } catch (error) {
            if (card) card.classList.add('upload-error');
            throw error;
        }
    }

    formatFileSize(bytes) {
        if (bytes >= 1048576) {
            return (bytes / 1048576).toFixed(2) + ' MB';
        } else if (bytes >= 1024) {
            return (bytes / 1024).toFixed(2) + ' KB';
        } else {
            return bytes + ' bytes';
        }
    }

    showError(message) {
        // Create toast notification
        const toast = document.createElement('div');
        toast.className = 'toast toast-error';
        toast.innerHTML = `
            <i class="ri-error-warning-line"></i>
            <span>${message}</span>
        `;
        document.body.appendChild(toast);

        setTimeout(() => toast.classList.add('show'), 100);
        setTimeout(() => {
            toast.classList.remove('show');
            setTimeout(() => toast.remove(), 300);
        }, 3000);
    }

    showSuccess(message) {
        const toast = document.createElement('div');
        toast.className = 'toast toast-success';
        toast.innerHTML = `
            <i class="ri-checkbox-circle-line"></i>
            <span>${message}</span>
        `;
        document.body.appendChild(toast);

        setTimeout(() => toast.classList.add('show'), 100);
        setTimeout(() => {
            toast.classList.remove('show');
            setTimeout(() => toast.remove(), 300);
        }, 3000);
    }

    reset() {
        this.files = [];
        this.previewContainer.innerHTML = '';
        this.fileInput.value = '';
    }
}

// Export for use in other scripts
if (typeof module !== 'undefined' && module.exports) {
    module.exports = FileUploader;
}
