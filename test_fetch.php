<?php
try {
    $crm_pdo = new PDO("mysql:host=srv696.hstgr.io;dbname=u287260207_bgoi_bg;charset=utf8mb4", "u287260207_bgoi_user", "4g@LMW2026", [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_TIMEOUT => 5
    ]);
    $stmt = $crm_pdo->query("SELECT id, phone, student_name, status, primary_status, source FROM leads ORDER BY id DESC LIMIT 10");
    print_r($stmt->fetchAll(PDO::FETCH_ASSOC));
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
