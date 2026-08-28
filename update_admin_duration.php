<?php
$file = 'admin/upcoming_batches.php';
$content = file_get_contents($file);

// Replace insert logic
$content = str_replace(
    '$status = isset($_POST[\'status\']) ? trim($_POST[\'status\']) : \'\';',
    '$status = isset($_POST[\'status\']) ? trim($_POST[\'status\']) : \'\';
        $duration = isset($_POST[\'duration\']) ? trim($_POST[\'duration\']) : \'\';',
    $content
);

$content = str_replace(
    'INSERT INTO upcoming_batches (course_slug, start_date, batch_time, batch_mode, status, is_active)',
    'INSERT INTO upcoming_batches (course_slug, start_date, batch_time, batch_mode, duration, status, is_active)',
    $content
);

$content = str_replace(
    'VALUES (:course_slug, :start_date, :batch_time, :batch_mode, :status, :is_active)',
    'VALUES (:course_slug, :start_date, :batch_time, :batch_mode, :duration, :status, :is_active)',
    $content
);

$content = str_replace(
    "'batch_mode' => \$batch_mode,",
    "'batch_mode' => \$batch_mode,\n                    'duration' => \$duration,",
    $content
);

// Replace update logic
$content = str_replace(
    'SET course_slug = :course_slug, start_date = :start_date, batch_time = :batch_time, batch_mode = :batch_mode, status = :status, is_active = :is_active',
    'SET course_slug = :course_slug, start_date = :start_date, batch_time = :batch_time, batch_mode = :batch_mode, duration = :duration, status = :status, is_active = :is_active',
    $content
);

$content = preg_replace(
    "/'batch_mode' => \\\$batch_mode, 'status' => \\\$status, 'is_active' => \\\$is_active, 'id' => \\\$id/s",
    "'batch_mode' => \$batch_mode, 'duration' => \$duration, 'status' => \$status, 'is_active' => \$is_active, 'id' => \$id",
    $content
);

// Replace table headers
$content = str_replace(
    '<th style="padding: 1rem; text-align: left; border-bottom: 2px solid var(--border);">Mode</th>',
    '<th style="padding: 1rem; text-align: left; border-bottom: 2px solid var(--border);">Mode</th>
                        <th style="padding: 1rem; text-align: left; border-bottom: 2px solid var(--border);">Duration</th>',
    $content
);

// Replace table data
$content = str_replace(
    '<td style="padding: 1rem; border-bottom: 1px solid var(--border);"><?php echo clean_output($b[\'batch_mode\']); ?></td>',
    '<td style="padding: 1rem; border-bottom: 1px solid var(--border);"><?php echo clean_output($b[\'batch_mode\']); ?></td>
                            <td style="padding: 1rem; border-bottom: 1px solid var(--border);"><?php echo clean_output($b[\'duration\'] ?? \'\'); ?></td>',
    $content
);

// Add to modal HTML
$modalAddition = <<<EOT
            <div class="form-group" style="margin-bottom: 1.5rem;">
                <label style="display: block; font-weight: 600; margin-bottom: 0.5rem; font-size: 0.9rem; color: var(--dark);">Duration</label>
                <input type="text" name="duration" id="inputDuration" class="form-control" placeholder="e.g., 1.5 Months, 11 Days" style="width: 100%; padding: 0.75rem; border: 1px solid var(--border); border-radius: 8px; font-family: inherit; font-size: 0.95rem; outline: none; transition: border-color 0.3s;">
            </div>
EOT;

$content = str_replace(
    '<div class="form-group" style="margin-bottom: 1.5rem;">
                <label style="display: block; font-weight: 600; margin-bottom: 0.5rem; font-size: 0.9rem; color: var(--dark);">Status *</label>',
    $modalAddition . "\n\n            " . '<div class="form-group" style="margin-bottom: 1.5rem;">
                <label style="display: block; font-weight: 600; margin-bottom: 0.5rem; font-size: 0.9rem; color: var(--dark);">Status *</label>',
    $content
);

// JS Updates
$content = str_replace(
    "document.getElementById('inputBatchMode').value = 'Online';",
    "document.getElementById('inputBatchMode').value = 'Online';\n        document.getElementById('inputDuration').value = '';",
    $content
);

$content = str_replace(
    "document.getElementById('inputBatchMode').value = b.batch_mode;",
    "document.getElementById('inputBatchMode').value = b.batch_mode;\n        document.getElementById('inputDuration').value = b.duration || '';",
    $content
);

file_put_contents($file, $content);
echo "Updated admin/upcoming_batches.php\n";
