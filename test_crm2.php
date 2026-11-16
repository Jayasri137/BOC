<?php
$pdo = new PDO('mysql:host=srv696.hstgr.io;dbname=u287260207_bgoi_bg;charset=utf8mb4', 'u287260207_bgoi_user', '4g@LMW2026', [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
$stmt = $pdo->prepare("SELECT phone, student_name, assigned_to_name FROM leads WHERE student_name LIKE '%Jayasri%' OR phone = '7708703137'");
$stmt->execute();
print_r($stmt->fetchAll(PDO::FETCH_ASSOC));
