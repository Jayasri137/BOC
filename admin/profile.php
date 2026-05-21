<?php
// admin/profile.php - Admin Profile & Password Management
$pageTitle = 'My Profile';
require_once 'includes/header.php'; // handles session and pdo load

$alertSuccess = '';
$alertError = '';

$adminId = $_SESSION['admin_user_id'];

// Fetch latest admin info from database
try {
    $stmt = $pdo->prepare("SELECT * FROM admins WHERE id = :id LIMIT 1");
    $stmt->execute(['id' => $adminId]);
    $adminData = $stmt->fetch();
    
    if (!$adminData) {
        // If they don't exist, log them out
        header('Location: logout.php');
        exit;
    }
} catch (PDOException $e) {
    $alertError = 'Database error: ' . $e->getMessage();
}

// Handle Form Submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fullName = isset($_POST['full_name']) ? trim($_POST['full_name']) : '';
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $username = isset($_POST['username']) ? trim($_POST['username']) : '';
    $currentPassword = isset($_POST['current_password']) ? trim($_POST['current_password']) : '';
    $newPassword = isset($_POST['new_password']) ? trim($_POST['new_password']) : '';
    $confirmPassword = isset($_POST['confirm_password']) ? trim($_POST['confirm_password']) : '';
    
    if (empty($fullName) || empty($email) || empty($username) || empty($currentPassword)) {
        $alertError = 'Full Name, Email, Username, and Current Password are required fields.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $alertError = 'Please provide a valid email address.';
    } elseif (!empty($newPassword) && $newPassword !== $confirmPassword) {
        $alertError = 'New password and confirmation password do not match.';
    } else {
        // Verify current password first
        if (!password_verify($currentPassword, $adminData['password'])) {
            $alertError = 'Current password is incorrect. Profile updates rejected.';
        } else {
            try {
                // Check if username/email is taken by other admins
                $check = $pdo->prepare("SELECT COUNT(*) FROM admins WHERE (username = :user OR email = :email) AND id != :id");
                $check->execute(['user' => $username, 'email' => $email, 'id' => $adminId]);
                if ($check->fetchColumn() > 0) {
                    $alertError = 'The username or email address is already taken by another administrator.';
                } else {
                    // Start building update query
                    if (!empty($newPassword)) {
                        // Change password as well
                        $newHash = password_hash($newPassword, PASSWORD_DEFAULT);
                        $update = $pdo->prepare("
                            UPDATE admins 
                            SET full_name = :full_name, 
                                email = :email, 
                                username = :username, 
                                password = :password 
                            WHERE id = :id
                        ");
                        $update->execute([
                            'full_name' => $fullName,
                            'email' => $email,
                            'username' => $username,
                            'password' => $newHash,
                            'id' => $adminId
                        ]);
                    } else {
                        // Just change basic details
                        $update = $pdo->prepare("
                            UPDATE admins 
                            SET full_name = :full_name, 
                                email = :email, 
                                username = :username 
                            WHERE id = :id
                        ");
                        $update->execute([
                            'full_name' => $fullName,
                            'email' => $email,
                            'username' => $username,
                            'id' => $adminId
                        ]);
                    }
                    
                    // Refresh current session variables
                    $_SESSION['admin_username']  = $username;
                    $_SESSION['admin_full_name'] = $fullName;
                    $_SESSION['admin_email']     = $email;
                    
                    // Force refresh page values
                    $adminData['full_name'] = $fullName;
                    $adminData['email'] = $email;
                    $adminData['username'] = $username;
                    
                    $alertSuccess = 'Profile details updated successfully!';
                }
            } catch (PDOException $e) {
                $alertError = 'Failed to save changes: ' . $e->getMessage();
            }
        }
    }
}
?>

<h1 class="page-title">
    My Profile
    <span>Manage account credentials, change password, and modify administrator data</span>
</h1>

<?php if (!empty($alertSuccess)): ?>
    <div class="alert alert-success">
        <i class="fa-solid fa-circle-check"></i>
        <span><?php echo clean_output($alertSuccess); ?></span>
    </div>
<?php endif; ?>

<?php if (!empty($alertError)): ?>
    <div class="alert alert-danger">
        <i class="fa-solid fa-triangle-exclamation"></i>
        <span><?php echo clean_output($alertError); ?></span>
    </div>
<?php endif; ?>

<div class="panel-grid" style="grid-template-columns: 1fr 1fr; align-items: flex-start; max-width: 900px; margin-top: 1rem;">
    <!-- Profile form card -->
    <div class="panel-card">
        <div class="panel-header" style="margin-bottom: 1.5rem;">
            <h3 class="panel-title">
                <i class="fa-solid fa-id-card"></i>
                <span>Profile Settings</span>
            </h3>
        </div>
        
        <form action="profile.php" method="POST">
            <div class="form-group">
                <label class="form-label" for="full_name">Full Name</label>
                <input type="text" name="full_name" id="full_name" class="form-control" value="<?php echo clean_output($adminData['full_name'] ?? ''); ?>" required placeholder="e.g., John Doe">
            </div>
            
            <div class="form-group">
                <label class="form-label" for="email">Email Address</label>
                <input type="email" name="email" id="email" class="form-control" value="<?php echo clean_output($adminData['email'] ?? ''); ?>" required placeholder="e.g., admin@bluestoneocs.com">
            </div>
            
            <div class="form-group" style="margin-bottom: 1.75rem;">
                <label class="form-label" for="username">Username</label>
                <input type="text" name="username" id="username" class="form-control" value="<?php echo clean_output($adminData['username'] ?? ''); ?>" required placeholder="e.g., admin">
            </div>

            <div style="border-top: 1px solid var(--border); padding-top: 1.5rem; margin-top: 2rem;">
                <h4 style="margin-bottom: 1rem; font-size: 0.95rem; color: var(--accent);"><i class="fa-solid fa-key"></i> Change Password (Optional)</h4>
                
                <div class="form-group">
                    <label class="form-label" for="new_password">New Password</label>
                    <div class="pw-wrap">
                        <input type="password" name="new_password" id="new_password" class="form-control" placeholder="Leave blank to keep current">
                        <button type="button" class="pw-toggle" onclick="togglePw('new_password',this)" tabindex="-1"><i class="fa-solid fa-eye"></i></button>
                    </div>
                </div>
                
                <div class="form-group" style="margin-bottom: 1.5rem;">
                    <label class="form-label" for="confirm_password">Confirm New Password</label>
                    <div class="pw-wrap">
                        <input type="password" name="confirm_password" id="confirm_password" class="form-control" placeholder="Re-type new password">
                        <button type="button" class="pw-toggle" onclick="togglePw('confirm_password',this)" tabindex="-1"><i class="fa-solid fa-eye"></i></button>
                    </div>
                </div>
            </div>

            <div style="background: rgba(14, 165, 233, 0.05); border: 1px solid rgba(14, 165, 233, 0.15); padding: 1.25rem; border-radius: var(--radius-sm); margin: 2rem 0 1.5rem 0;">
                <label class="form-label" for="current_password" style="color: var(--text-primary); font-weight: 600;"><i class="fa-solid fa-shield-halved" style="color: var(--primary);"></i> Current Password (Required)</label>
                <div class="pw-wrap">
                    <input type="password" name="current_password" id="current_password" class="form-control" style="background: #f8fafc;" required placeholder="Enter current password to save details">
                    <button type="button" class="pw-toggle" onclick="togglePw('current_password',this)" tabindex="-1"><i class="fa-solid fa-eye"></i></button>
                </div>
            </div>

            <button type="submit" class="btn-pill" style="width: 100%; justify-content: center; height: 48px;">
                <i class="fa-solid fa-floppy-disk"></i>
                <span>Save Profile Settings</span>
            </button>
        </form>
    </div>

    <!-- Avatar display info panel -->
    <div style="display: flex; flex-direction: column; gap: 1.5rem;">
        <div class="panel-card" style="text-align: center; padding: 3rem 1.5rem;">
            <div class="user-avatar user-avatar--<?php echo $avatarColor; ?>" style="width: 100px; height: 100px; font-size: 2.5rem; margin: 0 auto 1.5rem;">
                <?php echo $initials; ?>
            </div>
            <h2 style="font-size: 1.5rem; font-weight: 700; margin-bottom: 0.25rem;"><?php echo clean_output($adminData['full_name']); ?></h2>
            <p style="color: var(--text-secondary); font-size: 0.9rem; margin-bottom: 1.5rem;">@<?php echo clean_output($adminData['username']); ?></p>
            
            <div style="display: inline-block; background: rgba(16, 185, 129, 0.1); border: 1px solid rgba(16, 185, 129, 0.2); padding: 0.4rem 1rem; border-radius: 20px; font-size: 0.8rem; font-weight: 600; color: var(--success); text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 2rem;">
                <i class="fa-solid fa-shield-halved"></i> Super Administrator
            </div>

            <div style="border-top: 1px solid var(--border); padding-top: 1.5rem; text-align: left; font-size: 0.88rem; color: var(--text-secondary); display: flex; flex-direction: column; gap: 0.75rem;">
                <div><i class="fa-solid fa-envelope" style="width: 20px; color: var(--accent);"></i> <?php echo clean_output($adminData['email']); ?></div>
                <div><i class="fa-solid fa-calendar-day" style="width: 20px; color: var(--accent);"></i> Account Created: <?php echo date('M d, Y', strtotime($adminData['created_at'])); ?></div>
            </div>
        </div>
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

<?php require_once 'includes/footer.php'; ?>

