<?php
require 'includes/db.php';
$pdo->exec("UPDATE countries SET travel_hours = 'Approx 18-24 hours (From India to Major Hubs)' WHERE slug = 'canada'");
echo 'Done';
?>
