<?php
// Update test-prep.php
$file = 'test-prep.php';
$content = file_get_contents($file);
$content = str_replace(
    '<div style="font-weight: 700; color: var(--dark); font-size: 1rem;">Time: <?php echo clean_output($batch[\'batch_time\']); ?></div>',
    '<div style="font-weight: 700; color: var(--dark); font-size: 1rem;">Time: <?php echo clean_output($batch[\'batch_time\']); ?></div>
                        <?php if(!empty($batch[\'duration\'])): ?><div style="font-weight: 700; color: var(--dark); font-size: 1rem;">Duration: <?php echo clean_output($batch[\'duration\']); ?></div><?php endif; ?>',
    $content
);
file_put_contents($file, $content);
echo "Updated test-prep.php\n";

// Update the 5 specific course pages
$files = [
    ['file' => 'pte.php', 'var' => 'var(--pte-gradient)'],
    ['file' => 'ielts-coaching-in-coimbatore.php', 'var' => 'var(--IELTS-primary)'],
    ['file' => 'german.php', 'var' => 'var(--pte-gradient)'],
    ['file' => 'japanese.php', 'var' => 'var(--pte-gradient)'],
    ['file' => 'toefl.php', 'var' => 'var(--toefl-gradient)']
];

foreach ($files as $item) {
    $f = $item['file'];
    $c = file_get_contents($f);
    
    $search = '<span><i class="fa-regular fa-clock" style="color: ' . $item['var'] . ';"></i> <?php echo clean_output($batch[\'batch_time\']); ?></span>';
    
    $replace = '<span><i class="fa-regular fa-clock" style="color: ' . $item['var'] . ';"></i> <?php echo clean_output($batch[\'batch_time\']); ?></span>
                                   <?php if(!empty($batch[\'duration\'])): ?><span><i class="fa-solid fa-hourglass-half" style="color: ' . $item['var'] . ';"></i> <?php echo clean_output($batch[\'duration\']); ?></span><?php endif; ?>';
                                   
    $c = str_replace($search, $replace, $c);
    file_put_contents($f, $c);
    echo "Updated $f\n";
}
