<?php
/**
 * Reset Password - Validates token and allows user to set a new password.
 */
session_start();
require_once 'includes/config.php';

$error = '';
$success = '';
$showForm = false;
$token = $_GET['token'] ?? $_POST['token'] ?? '';

if (empty($token) || !ctype_xdigit($token) || strlen($token) !== 64) {
    $error = 'Invalid or missing reset token. Please request a new password reset link.';
} else {
    // Verify token
    try {
        $stmt = $pdo->prepare("SELECT id, email, expires_at, used FROM password_resets WHERE token = ? LIMIT 1");
        $stmt->execute([$token]);
        $resetRecord = $stmt->fetch();

        if (!$resetRecord) {
            $error = 'This reset link is invalid. Please request a new one.';
        } elseif ($resetRecord['used']) {
            $error = 'This reset link has already been used. Please request a new one.';
        } elseif (strtotime($resetRecord['expires_at']) < time()) {
            $error = 'This reset link has expired. Please request a new one.';
        } else {
            $showForm = true;
        }
    } catch (PDOException $e) {
        error_log('Password reset token verification failed: ' . $e->getMessage());
        $error = 'Unable to verify your reset link. Please try again later.';
    }
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $showForm) {
    // CSRF check
    if (
        !isset($_POST['csrf_token']) ||
        !is_string($_POST['csrf_token']) ||
        empty($_SESSION['reset_csrf_token']) ||
        !hash_equals($_SESSION['reset_csrf_token'], $_POST['csrf_token'])
    ) {
        $error = 'Your session expired. Please try again.';
        $showForm = true;
    } else {
        $newPassword = $_POST['password'] ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';

        if (empty($newPassword) || strlen($newPassword) < 8) {
            $error = 'Password must be at least 8 characters long.';
        } elseif ($newPassword !== $confirmPassword) {
            $error = 'Passwords do not match.';
        } else {
            try {
                // Update password
                $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
                $updateStmt = $pdo->prepare("UPDATE users SET password = ? WHERE email = ?");
                $updateStmt->execute([$hashedPassword, $resetRecord['email']]);

                // Mark token as used
                $pdo->prepare("UPDATE password_resets SET used = 1 WHERE id = ?")->execute([$resetRecord['id']]);

                // Create notification for the user
                try {
                    $userStmt = $pdo->prepare("SELECT id FROM users WHERE email = ? LIMIT 1");
                    $userStmt->execute([$resetRecord['email']]);
                    $userId = $userStmt->fetchColumn();
                    if ($userId) {
                        $notifStmt = $pdo->prepare("INSERT INTO notifications (user_id, title, message, type, category) VALUES (?, 'Password Changed', 'Your password was successfully reset. If you did not make this change, contact support immediately.', 'warning', 'system')");
                        $notifStmt->execute([$userId]);
                    }
                } catch (PDOException $e) {
                    error_log('Failed to create password change notification: ' . $e->getMessage());
                }

                $success = 'Your password has been reset successfully! You can now sign in with your new password.';
                $showForm = false;

            } catch (PDOException $e) {
                error_log('Password reset failed: ' . $e->getMessage());
                $error = 'Unable to reset your password. Please try again.';
            }
        }
    }
}

// Generate CSRF token
if (empty($_SESSION['reset_csrf_token'])) {
    $_SESSION['reset_csrf_token'] = bin2hex(random_bytes(32));
}
$csrfToken = $_SESSION['reset_csrf_token'];

$pageTitle = 'Reset Password - Bluestone Overseas';
require_once 'includes/header.php';
?>
<style>
.auth-wrapper {
    background: linear-gradient(135deg, #f8fafc 0%, #eff6ff 100%);
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 3rem 1rem;
}
.auth-card {
    max-width: 480px;
    width: 100%;
    background: #ffffff;
    border-radius: 24px;
    padding: 3rem;
    box-shadow: 0 10px 40px rgba(0,0,0,0.05);
}
.auth-title {
    font-size: 1.8rem;
    font-weight: 800;
    color: #1e293b;
    margin-bottom: 0.5rem;
    text-align: center;
}
.auth-subtitle {
    color: #64748b;
    text-align: center;
    margin-bottom: 2rem;
    font-size: 0.95rem;
}
.auth-form-group {
    margin-bottom: 1.5rem;
}
.auth-form-group label {
    display: block;
    font-weight: 600;
    color: #334155;
    margin-bottom: 0.5rem;
    font-size: 0.9rem;
}
.auth-input {
    width: 100%;
    padding: 0.85rem 1rem;
    border: 1px solid #cbd5e1;
    border-radius: 8px;
    font-size: 1rem;
    color: #1e293b;
    transition: all 0.3s;
}
.auth-input:focus {
    outline: none;
    border-color: #5b21b6;
    box-shadow: 0 0 0 3px rgba(91, 33, 182, 0.1);
}
.auth-btn {
    width: 100%;
    padding: 1rem;
    background: #5b21b6;
    color: white;
    border: none;
    border-radius: 8px;
    font-size: 1.05rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s;
}
.auth-btn:hover {
    background: #4c1d95;
    box-shadow: 0 5px 15px rgba(91, 33, 182, 0.2);
}
.auth-footer {
    text-align: center;
    margin-top: 1.5rem;
    font-size: 0.95rem;
    color: #64748b;
}
.auth-footer a {
    color: #5b21b6;
    font-weight: 700;
    text-decoration: none;
}
.auth-icon {
    text-align: center;
    margin-bottom: 1.5rem;
}
.auth-icon i {
    font-size: 3rem;
    color: #5b21b6;
    background: #ede9fe;
    width: 80px;
    height: 80px;
    line-height: 80px;
    border-radius: 50%;
    display: inline-block;
}
.auth-icon.success-icon i {
    color: #059669;
    background: #d1fae5;
}
.alert-success {
    background: #d1fae5;
    color: #065f46;
    padding: 1rem;
    border-radius: 8px;
    margin-bottom: 1rem;
    border: 1px solid #10b981;
}
.alert-error {
    background: #fee2e2;
    color: #dc2626;
    padding: 1rem;
    border-radius: 8px;
    margin-bottom: 1rem;
    border: 1px solid #fca5a5;
}
.password-wrap {
    position: relative;
}
.password-toggle {
    position: absolute;
    right: 15px;
    top: 50%;
    transform: translateY(-50%);
    color: #94a3b8;
    cursor: pointer;
}
.password-strength {
    height: 4px;
    border-radius: 2px;
    margin-top: 8px;
    background: #e2e8f0;
    overflow: hidden;
}
.password-strength-bar {
    height: 100%;
    border-radius: 2px;
    transition: width 0.3s, background 0.3s;
    width: 0;
}
.password-hint {
    font-size: 0.8rem;
    color: #94a3b8;
    margin-top: 6px;
}
</style>

<div class="auth-wrapper">
    <div class="auth-card">
        <?php if ($success): ?>
            <div class="auth-icon success-icon">
                <i class="fa-solid fa-circle-check"></i>
            </div>
            <h1 class="auth-title">Password Reset!</h1>
            <div class="alert-success"><?= htmlspecialchars($success) ?></div>
            <div class="auth-footer" style="margin-top: 2rem;">
                <a href="sign-in.php" class="auth-btn" style="display: block; text-decoration: none; text-align: center; color: white;">
                    <i class="fa-solid fa-arrow-right-to-bracket"></i> Sign in now
                </a>
            </div>
        <?php else: ?>
            <div class="auth-icon">
                <i class="fa-solid fa-key"></i>
            </div>
            <h1 class="auth-title">Set new password</h1>
            <p class="auth-subtitle">Your new password must be at least 8 characters long.</p>

            <?php if ($error): ?>
                <div class="alert-error"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>

            <?php if ($showForm): ?>
            <form action="reset-password.php" method="POST">
                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken) ?>">
                <input type="hidden" name="token" value="<?= htmlspecialchars($token) ?>">

                <div class="auth-form-group">
                    <label for="password">New password</label>
                    <div class="password-wrap">
                        <input type="password" id="password" name="password" class="auth-input" placeholder="Enter new password" required minlength="8">
                        <i class="fa-regular fa-eye-slash password-toggle" id="toggle-pw"></i>
                    </div>
                    <div class="password-strength"><div class="password-strength-bar" id="pw-bar"></div></div>
                    <div class="password-hint" id="pw-hint">Min 8 characters</div>
                </div>

                <div class="auth-form-group">
                    <label for="confirm_password">Confirm password</label>
                    <div class="password-wrap">
                        <input type="password" id="confirm_password" name="confirm_password" class="auth-input" placeholder="Confirm new password" required minlength="8">
                        <i class="fa-regular fa-eye-slash password-toggle" id="toggle-cpw"></i>
                    </div>
                </div>

                <button type="submit" class="auth-btn">
                    <i class="fa-solid fa-shield-check"></i> Reset password
                </button>
            </form>
            <?php else: ?>
                <div class="auth-footer" style="margin-top: 1rem;">
                    <a href="forgot-password.php">Request a new reset link</a>
                </div>
            <?php endif; ?>

            <div class="auth-footer">
                <a href="sign-in.php"><i class="fa-solid fa-arrow-left"></i> Back to sign in</a>
            </div>
        <?php endif; ?>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Password visibility toggles
    document.querySelectorAll('.password-toggle').forEach(function(icon) {
        icon.addEventListener('click', function() {
            const input = this.previousElementSibling;
            if (input.type === 'password') {
                input.type = 'text';
                this.classList.remove('fa-eye-slash');
                this.classList.add('fa-eye');
            } else {
                input.type = 'password';
                this.classList.remove('fa-eye');
                this.classList.add('fa-eye-slash');
            }
        });
    });

    // Password strength indicator
    const pwInput = document.getElementById('password');
    const pwBar = document.getElementById('pw-bar');
    const pwHint = document.getElementById('pw-hint');

    if (pwInput && pwBar) {
        pwInput.addEventListener('input', function() {
            const val = this.value;
            let strength = 0;
            if (val.length >= 8) strength++;
            if (val.length >= 12) strength++;
            if (/[A-Z]/.test(val)) strength++;
            if (/[0-9]/.test(val)) strength++;
            if (/[^A-Za-z0-9]/.test(val)) strength++;

            const colors = ['#ef4444', '#f59e0b', '#f59e0b', '#22c55e', '#16a34a'];
            const labels = ['Weak', 'Fair', 'Fair', 'Strong', 'Very Strong'];
            const widths = ['20%', '40%', '60%', '80%', '100%'];

            const idx = Math.max(0, Math.min(strength - 1, 4));
            if (val.length === 0) {
                pwBar.style.width = '0';
                pwHint.textContent = 'Min 8 characters';
                pwHint.style.color = '#94a3b8';
            } else {
                pwBar.style.width = widths[idx];
                pwBar.style.background = colors[idx];
                pwHint.textContent = labels[idx];
                pwHint.style.color = colors[idx];
            }
        });
    }
});
</script>

<?php require_once 'includes/footer.php'; ?>
