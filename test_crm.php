<?php
$pdo = new PDO('mysql:host=srv696.hstgr.io;dbname=u287260207_bgoi_bg;charset=utf8mb4', 'u287260207_bgoi_user', '4g@LMW2026', [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
$stmt = $pdo->query('DESCRIBE leads');
$res = $stmt->fetchAll(PDO::FETCH_ASSOC);
foreach($res as $r) {
    if (strpos($r['Field'], 'assign') !== false || strpos($r['Field'], 'counsel') !== false) {
        echo $r['Field'] . "\n";
    }
}
