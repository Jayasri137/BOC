<?php require 'includes/db.php'; print_r(\->query('SHOW COLUMNS FROM team_members')->fetchAll(PDO::FETCH_ASSOC));
