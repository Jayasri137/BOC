<?php
require_once __DIR__ . '/includes/db.php';

try {
    $stmt = $pdo->query("SELECT id, image_path FROM team_members");
    $members = $stmt->fetchAll();
    
    $updated = 0;
    foreach ($members as $member) {
        $path = $member['image_path'];
        // If the path is not a URL and not a base64 string
        if (strpos($path, 'http') !== 0 && strpos($path, 'data:image') !== 0) {
            $local_path = __DIR__ . '/' . $path;
            if (file_exists($local_path)) {
                $mime_type = mime_content_type($local_path);
                $base64 = base64_encode(file_get_contents($local_path));
                $new_path = 'data:' . $mime_type . ';base64,' . $base64;
                
                $updateStmt = $pdo->prepare("UPDATE team_members SET image_path = ? WHERE id = ?");
                $updateStmt->execute([$new_path, $member['id']]);
                $updated++;
                echo "Updated ID {$member['id']}\n";
            } else {
                echo "File not found for ID {$member['id']}: $local_path\n";
            }
        }
    }
    echo "Total updated: $updated\n";
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?>
