<?php
require 'includes/db.php';
$deleted = $pdo->exec("DELETE FROM universities WHERE name REGEXP 'University of [A-Za-z ]+ [0-9]'");
echo "Deleted dummy universities: " . $deleted . "\n";
