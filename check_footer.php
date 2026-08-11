<?php
require_once 'includes/db.php';
$output = [];

// Check if footer.php is readable
$footer = file_get_contents('includes/footer.php');
$output[] = "footer.php exists: " . (file_exists('includes/footer.php') ? 'yes' : 'no');
$output[] = "main tag appears in index.php bottom:";

$index = file_get_contents('index.php');
$mainCount = substr_count($index, '<main>');
$mainCloseCount = substr_count($index, '</main>');
$output[] = "  <main> count: $mainCount";
$output[] = "  </main> count: $mainCloseCount";
$output[] = "  <?php require_once 'includes/footer.php'; ?> present: " . (strpos($index, "require_once 'includes/footer.php'") !== false ? 'yes' : 'no');

// Check for unclosed PHP blocks or JS errors
$scriptOpen = substr_count($index, '<script');
$scriptClose = substr_count($index, '</script>');
$output[] = "  <script> count: $scriptOpen";
$output[] = "  </script> count: $scriptClose";

// PHP syntax check
$syntaxCheck = shell_exec('c:\xampp\php\php.exe -l index.php 2>&1');
$output[] = "PHP syntax: " . trim($syntaxCheck);

echo implode("\n", $output);
