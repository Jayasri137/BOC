<?php
$files = [
    'university-selection.php',
    'universities.php',
    'services.php',
    'scholarships.php',
    'includes/university-selection.php',
    'courses.php',
    'admission-processing.php',
    'admin/universities.php'
];

foreach ($files as $file) {
    if (file_exists($file)) {
        $content = file_get_contents($file);
        $content = str_replace("\$c['flag'] . ' ' . \$c['name']", "\$c['name']", $content);
        file_put_contents($file, $content);
        echo "Updated $file\n";
    }
}
