<?php require 'includes/db.php'; $pdo->exec('UPDATE hero_slides SET is_active = 0 WHERE id IN (4, 5)'); $pdo->exec('UPDATE hero_slides SET is_active = 1 WHERE id = 1');
