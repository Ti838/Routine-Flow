<?php
/**
 * File Handler Utility
 * Centralized file upload, validation, and management
 */

class FileHandler
{
    // Configuration
    const MAX_FILE_SIZE = 10485760; // 10MB in bytes
    const ALLOWED_EXTENSIONS = ['pdf', 'png', 'jpg', 'jpeg'];
    const ALLOWED_MIME_TYPES = [
        'application/pdf',
        'image/png',
        'image/jpeg',
        'image/jpg'
    ];
    const UPLOAD_BASE_DIR = __DIR__ . '/../uploads/routines/';

    /**
     * Validate uploaded file
     * @param array $file - $_FILES array element
     * @return array - ['valid' => bool, 'error' => string|null]
     */
    public static function validateFile($file)
    {
        // Check if file was uploaded
        if (!isset($file['tmp_name']) || !is_uploaded_file($file['tmp_name'])) {
            return ['valid' => false, 'error' => 'No file uploaded'];
        }

        // Check for upload errors
        if ($file['error'] !== UPLOAD_ERR_OK) {
            return ['valid' => false, 'error' => self::getUploadErrorMessage($file['error'])];
        }

        // Check file size
        if ($file['size'] > self::MAX_FILE_SIZE) {
            $maxSizeMB = self::MAX_FILE_SIZE / 1048576;
            return ['valid' => false, 'error' => "File size exceeds {$maxSizeMB}MB limit"];
        }

        // Check file extension
        $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        if (!in_array($extension, self::ALLOWED_EXTENSIONS)) {
            return ['valid' => false, 'error' => 'Invalid file type. Allowed: ' . implode(', ', self::ALLOWED_EXTENSIONS)];
        }

        // Check MIME type
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mimeType = finfo_file($finfo, $file['tmp_name']);
        finfo_close($finfo);

        if (!in_array($mimeType, self::ALLOWED_MIME_TYPES)) {
            return ['valid' => false, 'error' => 'Invalid file MIME type'];
        }

        return ['valid' => true, 'error' => null];
    }

    /**
     * Generate secure unique filename
     * @param string $originalName - Original filename
     * @return string - Secure filename
     */
    public static function generateSecureFilename($originalName)
    {
        $extension = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));
        $timestamp = time();
        $randomString = bin2hex(random_bytes(8));
        return "routine_{$timestamp}_{$randomString}.{$extension}";
    }

    /**
     * Upload file to server
     * @param array $file - $_FILES array element
     * @param string $role - 'admin' or 'teacher'
     * @return array - ['success' => bool, 'path' => string|null, 'error' => string|null]
     */
    public static function uploadFile($file, $role)
    {
        // Validate file
        $validation = self::validateFile($file);
        if (!$validation['valid']) {
            return ['success' => false, 'path' => null, 'error' => $validation['error']];
        }

        // Create directory structure
        $yearMonth = date('Y-m');
        $uploadDir = self::UPLOAD_BASE_DIR . $role . '/' . $yearMonth . '/';

        if (!file_exists($uploadDir)) {
            if (!mkdir($uploadDir, 0755, true)) {
                return ['success' => false, 'path' => null, 'error' => 'Failed to create upload directory'];
            }
        }

        // Generate secure filename
        $secureFilename = self::generateSecureFilename($file['name']);
        $destinationPath = $uploadDir . $secureFilename;

        // Move uploaded file
        if (move_uploaded_file($file['tmp_name'], $destinationPath)) {
            // Set proper permissions
            chmod($destinationPath, 0644);

            // Return relative path for database storage
            $relativePath = "uploads/routines/{$role}/{$yearMonth}/{$secureFilename}";
            return ['success' => true, 'path' => $relativePath, 'error' => null];
        } else {
            return ['success' => false, 'path' => null, 'error' => 'Failed to move uploaded file'];
        }
    }

    /**
     * Delete file from server
     * @param string $filePath - Relative file path
     * @return bool - Success status
     */
    public static function deleteFile($filePath)
    {
        $fullPath = __DIR__ . '/../' . $filePath;

        if (file_exists($fullPath)) {
            return unlink($fullPath);
        }

        return false;
    }

    /**
     * Get file type (pdf or image)
     * @param string $filename - Filename
     * @return string - 'pdf' or 'image'
     */
    public static function getFileType($filename)
    {
        $extension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
        return ($extension === 'pdf') ? 'pdf' : 'image';
    }

    /**
     * Get human-readable upload error message
     * @param int $errorCode - PHP upload error code
     * @return string - Error message
     */
    private static function getUploadErrorMessage($errorCode)
    {
        switch ($errorCode) {
            case UPLOAD_ERR_INI_SIZE:
            case UPLOAD_ERR_FORM_SIZE:
                return 'File size exceeds maximum allowed';
            case UPLOAD_ERR_PARTIAL:
                return 'File was only partially uploaded';
            case UPLOAD_ERR_NO_FILE:
                return 'No file was uploaded';
            case UPLOAD_ERR_NO_TMP_DIR:
                return 'Missing temporary folder';
            case UPLOAD_ERR_CANT_WRITE:
                return 'Failed to write file to disk';
            case UPLOAD_ERR_EXTENSION:
                return 'File upload stopped by extension';
            default:
                return 'Unknown upload error';
        }
    }

    /**
     * Format file size for display
     * @param int $bytes - File size in bytes
     * @return string - Formatted size (e.g., "2.5 MB")
     */
    public static function formatFileSize($bytes)
    {
        if ($bytes >= 1073741824) {
            return number_format($bytes / 1073741824, 2) . ' GB';
        } elseif ($bytes >= 1048576) {
            return number_format($bytes / 1048576, 2) . ' MB';
        } elseif ($bytes >= 1024) {
            return number_format($bytes / 1024, 2) . ' KB';
        } else {
            return $bytes . ' bytes';
        }
    }
}

/**
 * Convenience helper for routine uploads
 */
function uploadRoutineFile($file, $userId, $role, $metadata = [])
{
    $upload = FileHandler::uploadFile($file, $role);
    if (!$upload['success'])
        return $upload;

    global $conn; // Assume DB connection is available
    try {
        $stmt = $conn->prepare("INSERT INTO routine_files (user_id, role, file_path, file_type, department, semester, description) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $fileType = FileHandler::getFileType($upload['path']);
        $stmt->execute([
            $userId,
            $role,
            $upload['path'],
            $fileType,
            $metadata['department'] ?? '',
            $metadata['semester'] ?? '',
            $metadata['description'] ?? ''
        ]);
        return ['success' => true, 'path' => $upload['path']];
    } catch (PDOException $e) {
        return ['success' => false, 'error' => "Database error: " . $e->getMessage()];
    }
}
?>