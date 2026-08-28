<?php
$files = ['pte.php', 'ielts-coaching-in-coimbatore.php', 'german.php', 'japanese.php', 'toefl.php'];
foreach ($files as $f) {
    $c = file_get_contents($f);
    // Find the extra endforeach block
    $pattern = '/<\?php endforeach; \?>\s*<\/div>\s*<\/div>\s*<div>\s*<span style="display: inline-block; padding: 0\.25rem 0\.75rem.*?<\?php endforeach; \?>/s';
    $new = preg_replace($pattern, '<?php endforeach; ?>', $c);
    if ($new !== $c) {
        file_put_contents($f, $new);
        echo "Fixed $f\n";
    } else {
        echo "Pattern not found in $f\n";
    }
}
