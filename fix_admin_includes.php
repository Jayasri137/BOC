<?php
$adminDir = __DIR__ . '/admin';
$files = scandir($adminDir);

$count = 0;
foreach ($files as $file) {
    if (pathinfo($file, PATHINFO_EXTENSION) === 'php') {
        $path = $adminDir . '/' . $file;
        $content = file_get_contents($path);
        
        $modified = false;
        
        if (strpos($content, "require_once 'includes/header.php';") !== false) {
            $content = str_replace("require_once 'includes/header.php';", "require_once __DIR__ . '/includes/header.php';", $content);
            $modified = true;
        }
        
        if (strpos($content, "require_once 'includes/footer.php';") !== false) {
            $content = str_replace("require_once 'includes/footer.php';", "require_once __DIR__ . '/includes/footer.php';", $content);
            $modified = true;
        }
        
        if ($modified) {
            file_put_contents($path, $content);
            $count++;
            echo "Fixed $file\n";
        }
    }
}
echo "Total files fixed: $count\n";
