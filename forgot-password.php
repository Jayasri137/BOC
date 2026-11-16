<?php
/**
 * Forgot Password - Generates a secure reset token and emails it.
 * If email sending is unavailable, shows the reset link on-screen (dev mode).
 */
session_start();
require_once 'includes/config.php';

$error = '';
$success = '';
$showForm = true;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // CSRF check
    if (
        !isset($_POST['csrf_token']) ||
        !is_string($_POST['csrf_token']) ||
        empty($_SESSION['forgot_csrf_token']) ||
        !hash_equals($_SESSION['forgot_csrf_token'], $_POST['csrf_token'])
    ) {
        $error = 'Your session expired. Please refresh the page and try again.';
    } else {
        $email = trim($_POST['email'] ?? '');

        if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $error = 'Please enter a valid email address.';
        } else {
            // Rate limit: max 5 reset requests per email per hour
            try {
                $rateStmt = $pdo->prepare("SELECT COUNT(*) FROM password_resets WHERE email = ? AND created_at > DATE_SUB(NOW(), INTERVAL 1 HOUR)");
                $rateStmt->execute([$email]);
                if ((int) $rateStmt->fetchColumn() >= 5) {
                    $error = 'Too many reset requests. Please wait an hour before trying again.';
                }
            } catch (PDOException $e) {
                // Table might not exist yet - continue anyway
                error_log('Password reset rate check failed: ' . $e->getMessage());
            }

            if (empty($error)) {
                // Check if user exists
                $stmt = $pdo->prepare("SELECT id, email FROM users WHERE email = ?");
                $stmt->execute([$email]);
                $user = $stmt->fetch();

                // Always show success message to prevent email enumeration
                $success = 'If an account with that email exists, we\'ve sent a password reset link. Please check your inbox (and spam folder).';
                $showForm = false;

                if ($user) {
                    // Generate secure token
                    $token = bin2hex(random_bytes(32));
                    $expiresAt = date('Y-m-d H:i:s', strtotime('+1 hour'));

                    // Invalidate any existing reset tokens for this email
                    try {
                        $pdo->prepare("UPDATE password_resets SET used = 1 WHERE email = ? AND used = 0")->execute([$email]);
                    } catch (PDOException $e) {
                        error_log('Failed to invalidate old tokens: ' . $e->getMessage());
                    }

                    // Store token
                    try {
                        $insertStmt = $pdo->prepare("INSERT INTO password_resets (email, token, expires_at) VALUES (?, ?, ?)");
                        $insertStmt->execute([$email, $token, $expiresAt]);
                    } catch (PDOException $e) {
                        error_log('Failed to insert password reset token: ' . $e->getMessage());
                        $error = 'Unable to process your request right now. Please try again later.';
                        $success = '';
                        $showForm = true;
                    }

                    if (!empty($success)) {
                        // Build reset URL
                        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
                        $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
                        $basePath = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/\\');
                        $resetUrl = "{$protocol}://{$host}{$basePath}/reset-password.php?token={$token}";

                        // Try to send email
                        $emailSent = false;
                        $subject = 'Reset your Bluestone password';
                        $htmlBody = '
                        <div style="font-family: Inter, Arial, sans-serif; max-width: 600px; margin: 0 auto; padding: 40px 20px;">
                            <div style="text-align: center; margin-bottom: 30px;">
                                <h2 style="color: #1e293b; margin: 0;">Bluestone Overseas</h2>
                            </div>
                            <div style="background: #ffffff; border-radius: 12px; padding: 32px; border: 1px solid #e2e8f0;">
                                <h3 style="color: #1e293b; margin-top: 0;">Password Reset Request</h3>
                                <p style="color: #475569; line-height: 1.6;">
                                    We received a request to reset your password. Click the button below to set a new password. 
                                    This link will expire in <strong>1 hour</strong>.
                                </p>
                                <div style="text-align: center; margin: 30px 0;">
                                    <a href="' . $resetUrl . '" style="background: #5b21b6; color: white; padding: 14px 32px; border-radius: 8px; text-decoration: none; font-weight: 600; display: inline-block;">Reset Password</a>
                                </div>
                                <p style="color: #94a3b8; font-size: 13px; line-height: 1.5;">
                                    If you didn\'t request this, you can safely ignore this email. Your password will remain unchanged.
                                </p>
                                <hr style="border: none; border-top: 1px solid #e2e8f0; margin: 24px 0;">
                                <p style="color: #94a3b8; font-size: 12px;">
                                    If the button doesn\'t work, copy and paste this URL into your browser:<br>
                                    <a href="' . $resetUrl . '" style="color: #5b21b6; word-break: break-all;">' . $resetUrl . '</a>
                                </p>
                            </div>
                        </div>';

                        $headers = "MIME-Version: 1.0\r\n";
                        $headers .= "Content-type: text/html; charset=UTF-8\r\n";
                        $headers .= "From: Bluestone Overseas <noreply@bluestoneocs.com>\r\n";

                        if (function_exists('mail')) {
                            $emailSent = @mail($email, $subject, $htmlBody, $headers);
                        }

                        if (!$emailSent) {
                            // Dev fallback: show the link on screen
                            error_log("Password reset link for {$email}: {$resetUrl}");
                            $success = 'Email sending is not configured on this server. <br><br>
                                <strong>Reset link (dev mode):</strong><br>
                                <a href="' . htmlspecialchars($resetUrl) . '" style="color: #5b21b6; word-break: break-all;">' . htmlspecialchars($resetUrl) . '</a><br><br>
                                <small style="color: #94a3b8;">This link expires in 1 hour.</small>';
                        }
                    }
                }
            }
        }
    }
}

// Generate CSRF token
if (empty($_SESSION['forgot_csrf_token'])) {
    $_SESSION['forgot_csrf_token'] = bin2hex(random_bytes(32));
}
$csrfToken = $_SESSION['forgot_csrf_token'];

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
    line-height: 1.5;
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
.alert-success {
    background: #d1fae5;
    color: #065f46;
    padding: 1rem;
    border-radius: 8px;
    margin-bottom: 1rem;
    border: 1px solid #10b981;
    line-height: 1.5;
    font-size: 0.9rem;
}
.alert-error {
    background: #fee2e2;
    color: #dc2626;
    padding: 1rem;
    border-radius: 8px;
    margin-bottom: 1rem;
    border: 1px solid #fca5a5;
}
</style>

<div class="auth-wrapper">
    <div class="auth-card">
        <div class="auth-icon">
            <i class="fa-solid fa-lock"></i>
        </div>
        <h1 class="auth-title">Forgot password?</h1>
        <p class="auth-subtitle">No worries! Enter your email address and we'll send you a link to reset your password.</p>

        <?php if ($error): ?>
            <div class="alert-error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <?php if ($success): ?>
            <div class="alert-success"><?= $success ?></div>
        <?php endif; ?>

        <?php if ($showForm): ?>
        <form action="forgot-password.php" method="POST">
            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken) ?>">
            
            <div class="auth-form-group">
                <label for="email">Email address</label>
                <input type="email" id="email" name="email" class="auth-input" placeholder="Enter your email" required autofocus>
            </div>

            <button type="submit" class="auth-btn">
                <i class="fa-solid fa-paper-plane"></i> Send reset link
            </button>
        </form>
        <?php endif; ?>

        <div class="auth-footer">
            <a href="sign-in.php"><i class="fa-solid fa-arrow-left"></i> Back to sign in</a>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
