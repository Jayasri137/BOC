<?php
// admin/debug_login.php - Diagnose login issues
// DELETE THIS FILE immediately after fixing the issue!

require_once '../includes/config.php';

echo "<style>body{font-family:monospace;background:#0f172a;color:#f1f5f9;padding:2rem;line-height:2;}
.ok{color:#4ade80;} .err{color:#f87171;} .info{color:#38bdf8;} 
table{border-collapse:collapse;width:100%;margin:1rem 0;}
td,th{border:1px solid #334155;padding:0.5rem 1rem;text-align:left;}
th{background:#1e293b;color:#94a3b8;}
</style>";

echo "<h2>🔍 Admin Login Diagnostics</h2>";

// 1. Check DB connection
echo "<h3 class='info'>1. Database Connection</h3>";
echo "<span class='ok'>✅ Connected to: <b>{$dbname}</b> on <b>{$host}</b></span><br>";

// 2. Check admins table exists
echo "<h3 class='info'>2. Admins Table Contents</h3>";
try {
    $rows = $pdo->query("SELECT id, full_name, username, email, LEFT(password,30) as hash_preview, created_at FROM admins")->fetchAll();
    if (empty($rows)) {
        echo "<span class='err'>❌ Admins table is EMPTY! No admin accounts exist.</span><br>";
        echo "<br><b>Creating admin account now...</b><br>";
        
        $password = 'admin123';
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $pdo->prepare("INSERT INTO admins (full_name, username, email, password) VALUES (?,?,?,?)")
            ->execute(['Administrator', 'admin', 'admin@bluestoneocs.com', $hash]);
        echo "<span class='ok'>✅ Admin account created! Username: <b>admin</b> | Password: <b>admin123</b></span><br>";
    } else {
        echo "<table><tr><th>ID</th><th>Username</th><th>Email</th><th>Hash Preview</th><th>Created</th></tr>";
        foreach ($rows as $r) {
            echo "<tr><td>{$r['id']}</td><td><b>{$r['username']}</b></td><td>{$r['email']}</td><td style='font-size:0.8rem'>{$r['hash_preview']}...</td><td>{$r['created_at']}</td></tr>";
        }
        echo "</table>";
    }
} catch (PDOException $e) {
    echo "<span class='err'>❌ Error: {$e->getMessage()}</span><br>";
}

// 3. Test password_verify for all accounts
echo "<h3 class='info'>3. Password Verification Tests</h3>";
$testPasswords = ['admin123', 'admin_password_123', 'Admin123', 'password'];
try {
    $admins = $pdo->query("SELECT username, password FROM admins")->fetchAll();
    foreach ($admins as $adm) {
        echo "<b>User: {$adm['username']}</b><br>";
        foreach ($testPasswords as $tp) {
            $ok = password_verify($tp, $adm['password']);
            $icon = $ok ? "<span class='ok'>✅ MATCHES</span>" : "<span style='color:#64748b'>✗ no match</span>";
            echo "&nbsp;&nbsp;&nbsp;Password '<b>{$tp}</b>': {$icon}<br>";
        }
    }
} catch (PDOException $e) {
    echo "<span class='err'>❌ Error: {$e->getMessage()}</span><br>";
}

// 4. Force reset admin password to 'admin123'
echo "<h3 class='info'>4. Force Reset Admin Password to 'admin123'</h3>";
$newHash = password_hash('admin123', PASSWORD_DEFAULT);
try {
    $updated = $pdo->prepare("UPDATE admins SET password = ? WHERE username = 'admin'");
    $updated->execute([$newHash]);
    if ($updated->rowCount() > 0) {
        echo "<span class='ok'>✅ Password force-reset to <b>admin123</b> successfully!</span><br>";
    } else {
        // No admin row yet — insert one
        $pdo->prepare("INSERT IGNORE INTO admins (full_name, username, email, password) VALUES (?,?,?,?)")
            ->execute(['Administrator', 'admin', 'admin@bluestoneocs.com', $newHash]);
        echo "<span class='ok'>✅ Admin account inserted with password <b>admin123</b></span><br>";
    }
} catch (PDOException $e) {
    echo "<span class='err'>❌ Error: {$e->getMessage()}</span><br>";
}

echo "<br><hr style='border-color:#334155'><br>";
echo "<span class='ok' style='font-size:1.2rem'>🔑 Login credentials: <b>admin</b> / <b>admin123</b></span><br><br>";
echo "<a href='login.php' style='background:#3b82f6;color:white;padding:0.75rem 2rem;border-radius:8px;text-decoration:none;font-weight:bold;'>→ Go to Login Page</a>";
echo "<br><br><span style='color:#f87171;font-size:0.85rem;'>⚠ DELETE this file (debug_login.php) immediately after logging in!</span>";
?>
