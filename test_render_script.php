<?php
session_start();
$_SESSION['admin_logged_in'] = true;
$_SESSION['admin_full_name'] = 'Test Admin';
$_SERVER['HTTP_HOST'] = 'localhost';
$_SERVER['HTTPS'] = 'off';
$_SERVER['SCRIPT_NAME'] = '/admin/index.php';
$_SERVER['PHP_SELF'] = '/admin/index.php';

ob_start();
require_once __DIR__ . '/admin/index.php';
$output = ob_get_clean();

file_put_contents(__DIR__ . '/test_output.html', $output);
echo "Rendered to test_output.html";
