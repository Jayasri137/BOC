<?php
// reset_admin.php — Run this ONCE via browser then DELETE immediately!
// Place in root: http://localhost/Bluestone Overseas/reset_admin.php

$host   = 'auth-db1278.hstgr.io';
$user   = 'u287260207_new_user';
$pass   = 'nwUser@>26';
$dbname = 'u287260207_overseas_newdb';

$newPassword = 'admin123';
$newHash     = password_hash($newPassword, PASSWORD_DEFAULT);

$lines = [];

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $user, $pass, [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
    $lines[] = ['ok', "✅ Connected to Hostinger database successfully."];

    // Ensure admins table exists
    $pdo->exec("CREATE TABLE IF NOT EXISTS `admins` (
        `id`         INT UNSIGNED NOT NULL AUTO_INCREMENT,
        `full_name`  VARCHAR(150) NOT NULL DEFAULT 'Administrator',
        `username`   VARCHAR(80) NOT NULL UNIQUE,
        `email`      VARCHAR(150) NOT NULL UNIQUE,
        `password`   VARCHAR(255) NOT NULL,
        `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");
    $lines[] = ['ok', "✅ Admins table verified."];

    // Delete existing admin (if any) and re-insert with fresh hash
    $pdo->exec("DELETE FROM admins WHERE username = 'admin'");
    $ins = $pdo->prepare("INSERT INTO admins (full_name, username, email, password) VALUES (?,?,?,?)");
    $ins->execute(['Administrator', 'admin', 'admin@bluestoneocs.com', $newHash]);
    $lines[] = ['ok', "✅ Admin account created with fresh password hash."];

    // Verify it immediately
    $row = $pdo->query("SELECT password FROM admins WHERE username = 'admin'")->fetch();
    if ($row && password_verify($newPassword, $row['password'])) {
        $lines[] = ['ok', "✅ Password verification PASSED — login will work!"];
        $success = true;
    } else {
        $lines[] = ['err', "❌ Password verification FAILED — something is wrong."];
        $success = false;
    }

} catch (PDOException $e) {
    $lines[] = ['err', "❌ Database Error: " . $e->getMessage()];
    $success = false;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Reset | Bluestone Overseas</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: Arial, sans-serif; background: #0f172a; color: #f1f5f9; min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: 2rem; }
        .card { background: #1e293b; border: 1px solid #334155; border-radius: 16px; padding: 2.5rem; max-width: 520px; width: 100%; }
        h2 { font-size: 1.4rem; margin-bottom: 1.5rem; color: #e2e8f0; }
        .line { padding: 0.5rem 0; font-size: 0.95rem; border-bottom: 1px solid #1e293b; }
        .ok  { color: #4ade80; }
        .err { color: #f87171; }
        .creds { background: #0f172a; border-radius: 10px; padding: 1.25rem; margin: 1.5rem 0; font-size: 1rem; line-height: 2; }
        .creds span { color: #38bdf8; font-weight: bold; }
        .btn { display: block; text-align: center; background: #2563eb; color: white; padding: 0.9rem; border-radius: 10px; text-decoration: none; font-weight: 700; margin-top: 1.5rem; font-size: 1rem; }
        .btn:hover { background: #1d4ed8; }
        .warn { background: rgba(239,68,68,0.1); border: 1px solid rgba(239,68,68,0.3); color: #fca5a5; padding: 1rem; border-radius: 8px; font-size: 0.82rem; margin-top: 1.25rem; line-height: 1.6; }
    </style>
</head>
<body>
<div class="card">
    <h2>🔧 Admin Account Reset Tool</h2>
    <?php foreach ($lines as [$type, $msg]): ?>
        <div class="line <?= $type ?>"><?= $msg ?></div>
    <?php endforeach; ?>

    <?php if (!empty($success) && $success): ?>
        <div class="creds">
            🔑 <span>Username:</span> admin<br>
            🔒 <span>Password:</span> admin123
        </div>
        <a href="admin/login.php" class="btn">→ Go to Admin Login</a>
    <?php endif; ?>

    <div class="warn">
        ⚠ <strong>Security:</strong> Delete <code>reset_admin.php</code> from your project folder immediately after logging in!
    </div>
</div>
</body>
</html>
