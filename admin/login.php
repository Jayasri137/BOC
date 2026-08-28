<?php
// admin/login.php - Secure login interface for Bluestone Overseas Admin Panel
require_once '../includes/config.php';
require_once 'includes/auth.php';
require_once '../includes/db.php';

// If already logged in, redirect directly to dashboard
if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
    header('Location: index.php');
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = isset($_POST['admin_username']) ? trim($_POST['admin_username']) : '';
    $password = isset($_POST['admin_password']) ? trim($_POST['admin_password']) : '';
    
    if (empty($username) || empty($password)) {
        $error = 'Please fill in all fields.';
    } else {
        try {
            // Find admin user
            $stmt = $pdo->prepare("SELECT * FROM admins WHERE username = :username LIMIT 1");
            $stmt->execute(['username' => $username]);
            $admin = $stmt->fetch();
            
            if ($admin && password_verify($password, $admin['password'])) {
                // Regenerate session ID for security to prevent session fixation
                session_regenerate_id(true);
                
                // Store in session
                $_SESSION['admin_logged_in'] = true;
                $_SESSION['admin_user_id']   = $admin['id'];
                $_SESSION['admin_username']  = $admin['username'];
                $_SESSION['admin_full_name'] = $admin['full_name'];
                $_SESSION['admin_email']     = $admin['email'];
                
                header('Location: index.php');
                exit;
            } else {
                $error = 'Invalid username or password.';
            }
        } catch (PDOException $e) {
            $error = 'A database error occurred: ' . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Secure Admin Login | Bluestone Overseas</title>
    <!-- Fonts -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Custom stylesheet -->
    <link rel="stylesheet" href="css/admin-style.css">
</head>
<body>

<div class="auth-wrapper">
    <div class="auth-card">
        <div class="auth-header">
            <div class="auth-logo">
                <i class="fa-solid fa-user-shield"></i>
            </div>
            <h1 class="auth-title">Admin Panel</h1>
            <p class="auth-subtitle">Bluestone Overseas Consultants</p>
        </div>

        <?php if (!empty($error)): ?>
            <div class="alert alert-danger">
                <i class="fa-solid fa-circle-exclamation"></i>
                <span><?php echo htmlspecialchars($error); ?></span>
            </div>
        <?php endif; ?>

        <form action="login.php" method="POST">
            <div class="form-group">
                <label for="admin_username" class="form-label">Username</label>
                <input type="text" name="admin_username" id="admin_username" class="form-control" placeholder="Enter admin username" required autofocus>
            </div>
            
            <div class="form-group" style="margin-bottom: 2rem;">
                <label for="admin_password" class="form-label">Password</label>
                <div class="pw-wrap">
                    <input type="password" name="admin_password" id="admin_password" class="form-control" placeholder="Enter password" required>
                    <button type="button" class="pw-toggle" onclick="togglePw('admin_password',this)" tabindex="-1">
                        <i class="fa-solid fa-eye"></i>
                    </button>
                </div>
            </div>
            
            <button type="submit" class="btn-submit">
                <span>Secure Log In</span>
                <i class="fa-solid fa-arrow-right-to-bracket"></i>
            </button>
        </form>
    </div>
</div>

<script>
function togglePw(id, btn) {
    var inp = document.getElementById(id);
    var icon = btn.querySelector('i');
    if (inp.type === 'password') {
        inp.type = 'text';
        icon.classList.replace('fa-eye','fa-eye-slash');
    } else {
        inp.type = 'password';
        icon.classList.replace('fa-eye-slash','fa-eye');
    }
}
</script>
</body>
</html>
