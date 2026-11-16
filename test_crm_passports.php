<?php
$pdo = new PDO('mysql:host=srv696.hstgr.io;dbname=u287260207_bgoi_bg;charset=utf8mb4', 'u287260207_bgoi_user', '4g@LMW2026', [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
$stmt = $pdo->prepare("SELECT id, phone, student_name, assigned_to_name, passport_no, university_student_id, university_name, course_name, primary_status FROM leads WHERE passport_no IS NOT NULL AND passport_no != '' ORDER BY id DESC LIMIT 5");
$stmt->execute();
print_r($stmt->fetchAll(PDO::FETCH_ASSOC));
