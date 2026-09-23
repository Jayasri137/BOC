<?php
require 'includes/db.php';
$stmt = $pdo->prepare("DELETE FROM universities WHERE name LIKE 'University of % 1' OR name LIKE 'University of % 2' OR name LIKE 'University of % 3' OR name LIKE 'University of % 4' OR name LIKE 'University of % 5'");
$stmt->execute();
echo "Deleted: " . $stmt->rowCount();
