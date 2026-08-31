<?php
require 'includes/config.php';
// Reassign any foreign keys from country 6 to country 12
$pdo->exec('UPDATE universities SET country_id = 12 WHERE country_id = 6');
// Delete the duplicate country
$pdo->exec('DELETE FROM countries WHERE id = 6');
echo "Deleted duplicate New Zealand (id 6) and reassigned universities to id 12.\n";
