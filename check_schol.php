<?php
require_once 'includes/db.php';
echo "Total Scholarships: " . $pdo->query("SELECT COUNT(*) FROM scholarships")->fetchColumn();
