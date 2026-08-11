<?php
require_once 'includes/db.php';
$stmt = $pdo->query("SELECT u.id, u.name, c.name as country_name FROM universities u JOIN countries c ON u.country_id = c.id");
while($row = $stmt->fetch()) {
    echo "ID: " . $row['id'] . " | " . $row['name'] . " (" . $row['country_name'] . ")\n";
}
