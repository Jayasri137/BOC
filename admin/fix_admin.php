<?php
// admin/fix_admin.php - One-click admin account repair tool
// DELETE THIS FILE after use for security!

require_once '../includes/config.php';

$username = 'admin';
$password = 'admin123';
$fullName = 'Administrator';
$email    = 'admin@bluestoneocs.com';

$hash = password_hash($password, PASSWORD_DEFAULT);

try {
    // Check if admin exists
    $check = $pdo->prepare("SELECT COUNT(*) FROM admins WHERE username = :u");
    $check->execute(['u' => $username]);
    $exists = $check->fetchColumn();

    if ($exists) {
        // Update existing admin password
        $stmt = $pdo->prepare("UPDATE admins SET password = :p, full_name = :n, email = :e WHERE username = :u");
        $stmt->execute(['p' => $hash, 'n' => $fullName, 'e' => $email, 'u' => $username]);
        $msg = "✅ Admin password has been RESET successfully!";
    } else {
        // Insert new admin
        $stmt = $pdo->prepare("INSERT INTO admins (full_name, username, email, password) VALUES (:n, :u, :e, :p)");
        $stmt->execute(['n' => $fullName, 'u' => $username, 'e' => $email, 'p' => $hash]);
        $msg = "✅ Admin account CREATED successfully!";
    }

    // Verify it works
    $verify = $pdo->prepare("SELECT password FROM admins WHERE username = :u");
    $verify->execute(['u' => $username]);
    $row = $verify->fetch();
    $verified = $row && password_verify($password, $row['password']);

} catch (PDOException $e) {
    $msg   = "❌ Database error: " . $e->getMessage();
    $verified = false;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Fix | Bluestone Overseas</title>
    <style>
        body { font-family: Arial, sans-serif; background: #0f172a; color: #f1f5f9; display: flex; align-items: center; justify-content: center; min-height: 100vh; margin: 0; }
        .box { background: #1e293b; border: 1px solid #334155; border-radius: 16px; padding: 2.5rem; max-width: 500px; width: 100%; text-align: center; }
        h2 { margin-bottom: 1rem; font-size: 1.5rem; }
        .result { font-size: 1.25rem; margin: 1.5rem 0; }
        .creds { background: #0f172a; border-radius: 10px; padding: 1rem 1.5rem; margin: 1.5rem 0; text-align: left; font-size: 0.95rem; line-height: 2; }
        .creds strong { color: #38bdf8; }
        .warn { background: rgba(239,68,68,0.1); border: 1px solid rgba(239,68,68,0.3); color: #fca5a5; padding: 0.75rem 1rem; border-radius: 8px; font-size: 0.85rem; margin-top: 1.5rem; }
        a { display: inline-block; margin-top: 1.5rem; background: #3b82f6; color: white; padding: 0.75rem 2rem; border-radius: 8px; text-decoration: none; font-weight: 600; }
        a:hover { background: #2563eb; }
    </style>
</head>
<body>
<div class="box">
    <h2>🔧 Admin Account Repair</h2>
    <div class="result"><?php echo $msg; ?></div>

    <?php if ($verified): ?>
        <p style="color:#86efac;">✔ Password verification confirmed — login will work!</p>
        <div class="creds">
            <div>🔑 <strong>Username:</strong> admin</div>
            <div>🔒 <strong>Password:</strong> admin_password_123</div>
        </div>
        <a href="login.php">Go to Admin Login →</a>
    <?php else: ?>
        <p style="color:#f87171;">⚠ Password verification failed. Check database connection.</p>
    <?php endif; ?>

    <div class="warn">
        ⚠ <strong>Security Warning:</strong> Delete <code>admin/fix_admin.php</code> from your server immediately after logging in!
    </div>
</div>
</body>
</html>
