<?php
$files = [
    ['file' => 'pte.php', 'var' => 'var(--pte-gradient)', 'shadow' => 'rgba(234, 88, 12, 0.2)'],
    ['file' => 'ielts-coaching-in-coimbatore.php', 'var' => 'var(--IELTS-primary)', 'shadow' => 'rgba(220, 38, 38, 0.2)'],
    ['file' => 'german.php', 'var' => 'var(--pte-gradient)', 'shadow' => 'rgba(139, 92, 246, 0.2)'],
    ['file' => 'japanese.php', 'var' => 'var(--pte-gradient)', 'shadow' => 'rgba(139, 92, 246, 0.2)'],
    ['file' => 'toefl.php', 'var' => 'var(--toefl-gradient)', 'shadow' => 'rgba(139, 92, 246, 0.2)']
];

foreach ($files as $item) {
    $content = file_get_contents($item['file']);
    $pattern = '/<div class=\"upcoming-batches-wrapper\".*?<\/div>\s*<\/div>/s';
    
    $replacement = '<div class="upcoming-batches-wrapper animate-on-scroll" style="background: ' . $item['var'] . '; border-radius: 24px; padding: 2.5rem; box-shadow: 0 25px 50px ' . $item['shadow'] . '; margin-top: 1rem;">
                   <h3 style="font-size: 1.6rem; font-weight: 800; color: white; margin-bottom: 1.5rem; display: flex; align-items: center; gap: 0.75rem; font-family: \'Plus Jakarta Sans\', sans-serif;"><i class="fa-solid fa-calendar-check"></i> Upcoming Batches</h3>
                   <div style="display: flex; flex-direction: column; gap: 1rem;">
                       <?php foreach($batches as $batch): 
                            $statusColor = \'#64748b\';
                            $s = strtolower($batch[\'status\']);
                            if (strpos($s, \'filling\') !== false || strpos($s, \'fast\') !== false) $statusColor = \'#f59e0b\';
                            elseif (strpos($s, \'open\') !== false) $statusColor = \'#10b981\';
                            elseif (strpos($s, \'closed\') !== false || strpos($s, \'full\') !== false) $statusColor = \'#ef4444\';
                       ?>
                       <div style="background: white; border-radius: 16px; padding: 1.25rem; display: flex; justify-content: space-between; align-items: center; box-shadow: 0 10px 25px rgba(0,0,0,0.1);">
                           <div>
                               <div style="font-weight: 800; color: var(--dark); font-size: 1.1rem; margin-bottom: 0.4rem;"><?php echo clean_output($batch[\'start_date\']); ?></div>
                               <div style="color: var(--gray); font-size: 0.9rem; display: flex; gap: 1rem; flex-wrap: wrap; font-weight: 500;">
                                   <span><i class="fa-regular fa-clock" style="color: ' . $item['var'] . ';"></i> <?php echo clean_output($batch[\'batch_time\']); ?></span>
                                   <span><i class="fa-solid fa-laptop-house" style="color: ' . $item['var'] . ';"></i> <?php echo clean_output($batch[\'batch_mode\']); ?></span>
                               </div>
                           </div>
                           <div>
                               <span style="display: inline-block; padding: 0.4rem 1rem; border-radius: 50px; background: <?php echo $statusColor; ?>15; color: <?php echo $statusColor; ?>; font-size: 0.85rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.5px;">
                                   <?php echo clean_output($batch[\'status\']); ?>
                               </span>
                           </div>
                       </div>
                       <?php endforeach; ?>
                   </div>
               </div>';
               
    $newContent = preg_replace($pattern, $replacement, $content);
    if ($newContent !== null && $newContent !== $content) {
        file_put_contents($item['file'], $newContent);
        echo "Updated {$item['file']}\n";
    } else {
        echo "Failed to update {$item['file']}\n";
    }
}
