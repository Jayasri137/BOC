<?php require 'includes/db.php'; $pdo->exec("UPDATE team_members SET image_path = 'uploads/team/badf424f77b4b74e1634d01d7486502d.png' WHERE id = 6"); echo 'Fixed image for ID 6';
