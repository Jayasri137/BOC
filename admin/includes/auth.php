<?php
// admin/includes/auth.php - Session validation and authentication helper utilities

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/**
 * Guard page: Redirects to login.php if the admin is not logged in.
 */
function check_auth() {
    if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
        $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http";
        $host = $_SERVER['HTTP_HOST'];
        $base_dir = dirname($_SERVER['SCRIPT_NAME']);
        // If the script is in a subdirectory (like login/), we need to go up to /admin
        if (basename($base_dir) !== 'admin') {
            $base_dir = dirname($base_dir);
        }
        $redirect_url = $protocol . "://" . $host . $base_dir . "/login.php";
        header("Location: " . $redirect_url);
        exit;
    }
}

/**
 * Checks if the current request is an AJAX request
 */
function is_ajax() {
    return !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
}

/**
 * Clean user string input to prevent XSS
 */
if (!function_exists('clean_output')) {
    function clean_output($str) {
        return htmlspecialchars($str ?? '', ENT_QUOTES, 'UTF-8');
    }
}

/**
 * Returns a human-friendly background color class for avatars
 */
function get_avatar_color($name) {
    $colors = ['blue', 'purple', 'orange', 'teal', 'pink', 'gold'];
    $char_code = ord(substr(strtoupper($name), 0, 1));
    return $colors[$char_code % count($colors)];
}
