<?php
session_start();
require_once 'includes/config.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $firstName = trim($_POST['first_name'] ?? '');
    $lastName = trim($_POST['last_name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $dialCode = trim($_POST['dial_code'] ?? '+91');
    $rawPhone = trim($_POST['phone'] ?? '');
    $allowedDialCodes = ['+91', '+1', '+44', '+61'];
    $dialCodeDigits = preg_replace('/\D+/', '', $dialCode);
    $enteredPhoneDigits = preg_replace('/\D+/', '', $rawPhone);
    $phoneDigits = $enteredPhoneDigits;
    if ($dialCodeDigits !== '' && strpos($phoneDigits, $dialCodeDigits) === 0 && strlen($phoneDigits) > strlen($dialCodeDigits)) {
        $phoneDigits = substr($phoneDigits, strlen($dialCodeDigits));
    }
    $phone = $dialCode . ' ' . $rawPhone;

    if (empty($firstName) || empty($lastName) || empty($email) || empty($password) || empty($phoneDigits)) {
        $error = "Please fill in all required fields, including your mobile number.";
    } elseif (!in_array($dialCode, $allowedDialCodes, true) || strlen($phoneDigits) < 7 || strlen($phoneDigits) > 15) {
        $error = "Please enter a valid mobile number and dial code.";
    } else {
        // Do not create an account or CRM record until the supplied phone is checked centrally.
        $existingCrmLead = false;
        try {
            require_once __DIR__ . '/includes/crm_db.php';
            $crmPdo = get_central_crm_pdo();
            $nationalNumber = $phoneDigits;
            $fullNumber = $dialCodeDigits . $nationalNumber;
            $internationalNumber = '00' . $fullNumber;
            $crmMatchStmt = $crmPdo->prepare("
                SELECT id
                FROM leads
                WHERE REGEXP_REPLACE(COALESCE(phone, ''), '[^0-9]', '') IN (?, ?, ?)
                ORDER BY id DESC
                LIMIT 1
            ");
            $crmMatchStmt->execute([$nationalNumber, $fullNumber, $internationalNumber]);
            $existingCrmLead = (bool) $crmMatchStmt->fetchColumn();
        } catch (PDOException $e) {
            error_log('Sign-up CRM phone check failed: ' . $e->getMessage());
            $error = "We couldn't verify your mobile number against our CRM right now. Please try again shortly.";
        }

        // Check if email exists
        if ($error !== '') {
            // Keep the form open; no account or lead is created when the CRM cannot be checked.
        } else {
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->fetch()) {
            $error = "Email is already registered.";
        } else {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("INSERT INTO users (first_name, last_name, email, password, phone) VALUES (?, ?, ?, ?, ?)");
            if ($stmt->execute([$firstName, $lastName, $email, $hashedPassword, $phone])) {
                $fullName = trim($firstName . ' ' . $lastName);
                $crmLeadCreated = false;
                if (!$existingCrmLead) {
                    $crmPayload = [
                        'name'          => $fullName,
                        'email'         => $email,
                        'phone'         => '+' . $dialCodeDigits . $phoneDigits,
                        'message'       => 'User registered via Student Community.',
                        'domain'        => 'Overseas',
                        'category'      => 'Community Sign-Up',
                        'interested_in' => 'Overseas Consulting',
                        'portal_signup' => true,
                        'phone_country_code' => $dialCode,
                        'phone_national' => $phoneDigits
                    ];
                    $ch = curl_init('https://bluestoneinternationalpreschool.com/bgoi_portal/api/contact');
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($ch, CURLOPT_POST, true);
                    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($crmPayload));
                    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
                    curl_setopt($ch, CURLOPT_TIMEOUT, 8);
                    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
                    $crmResponse = curl_exec($ch);
                    $crmHttpCode = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
                    $crmCurlError = curl_error($ch);
                    curl_close($ch);

                    $crmResponseData = is_string($crmResponse) ? json_decode($crmResponse, true) : null;
                    if ($crmCurlError !== '' || $crmHttpCode < 200 || $crmHttpCode >= 300 || !is_array($crmResponseData) || empty($crmResponseData['success'])) {
                        error_log('Sign-up CRM forwarding failed. HTTP ' . $crmHttpCode . '; ' . $crmCurlError);
                        $success = "Your portal account was created. We couldn't sync your enquiry with the CRM right now; please contact our team for assistance.";
                    } elseif (!empty($crmResponseData['duplicate'])) {
                        $existingCrmLead = true;
                    } else {
                        $crmLeadCreated = true;
                    }
                }

                if ($crmLeadCreated) {
                    try {
                        $localMatch = $pdo->prepare("
                            SELECT id FROM leads
                            WHERE REGEXP_REPLACE(COALESCE(phone, ''), '[^0-9]', '') IN (?, ?, ?)
                            LIMIT 1
                        ");
                        $localMatch->execute([$phoneDigits, $dialCodeDigits . $phoneDigits, '00' . $dialCodeDigits . $phoneDigits]);
                        if (!$localMatch->fetchColumn()) {
                            $prefix = 'BGOI';
                            $stmtCode = $pdo->prepare("SELECT lead_code FROM leads WHERE lead_code LIKE :pattern ORDER BY CAST(SUBSTRING_INDEX(lead_code, '-', -1) AS UNSIGNED) DESC LIMIT 1");
                            $stmtCode->execute(['pattern' => "$prefix-%"]);
                            $row = $stmtCode->fetch();
                            $nextNum = $row ? intval(explode('-', $row['lead_code'])[1]) + 1 : 1;
                            $leadCode = $prefix . '-' . str_pad($nextNum, 4, '0', STR_PAD_LEFT);
                            $stmtLeads = $pdo->prepare("
                                INSERT INTO leads (student_name, email, phone, domain, source, category, interested_in, remarks, lead_code, status, is_active, created_at)
                                VALUES (?, ?, ?, 'Overseas Consulting', 'Student Community Portal', 'Community Sign-Up', 'Overseas Consulting', 'User registered via Student Community.', ?, 'New', 1, NOW())
                            ");
                            $stmtLeads->execute([$fullName, $email, '+' . $dialCodeDigits . $phoneDigits, $leadCode]);
                        }
                    } catch (PDOException $e) {
                        error_log('Local sign-up lead mirror failed: ' . $e->getMessage());
                    }
                }

                if ($existingCrmLead) {
                    $success = "Your account was created. An existing CRM record already uses this mobile number, so we did not create a duplicate lead. An administrator must manually link your account to the correct CRM record before your application status appears. You can now <a href='sign-in.php' style='color:#059669;text-decoration:underline;'>sign in</a>.";
                } elseif ($success === '') {
                    $success = "Account created successfully. You can now <a href='sign-in.php' style='color:#059669;text-decoration:underline;'>sign in</a>.";
                }
            } else {
                $error = "Error creating account. Please try again.";
            }
        }
        }
    }
}

$pageTitle = 'Create Account - Bluestone Circle';
require_once 'includes/header.php';
?>
<style>
/* Copying the styles from sign-in for simplicity */
.auth-wrapper {
    background: linear-gradient(135deg, #f8fafc 0%, #eff6ff 100%);
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 3rem 1rem;
}
.auth-container {
    max-width: 1100px;
    width: 100%;
    display: flex;
    gap: 4rem;
    align-items: stretch;
}
.auth-left {
    flex: 1;
    display: flex;
    flex-direction: column;
    justify-content: center;
    position: relative;
    padding: 2rem 0;
}
.auth-left-graphic {
    position: relative;
    width: 100%;
    max-width: 350px;
    margin: 0 0 2.5rem 0;
}
.auth-shape-1 {
    position: absolute;
    top: 10%;
    left: 0;
    width: 60%;
    height: 70%;
    background-color: #f59e0b;
    border-radius: 50% 0 50% 50%;
    z-index: 1;
}
.auth-shape-2 {
    position: absolute;
    bottom: -10%;
    right: -10%;
    width: 80%;
    height: 60%;
    background-color: #0ea5e9;
    border-radius: 0 50% 50% 50%;
    z-index: 1;
}
.auth-shape-3 {
    position: absolute;
    top: 5%;
    right: 15%;
    width: 30%;
    height: 30%;
    background-color: #10b981;
    border-radius: 50% 50% 0 50%;
    z-index: 1;
}
.auth-img {
    position: relative;
    z-index: 2;
    width: 100%;
    border-radius: 20px;
}
.auth-right {
    flex: 1.2;
    background: #ffffff;
    border-radius: 24px;
    padding: 3.5rem;
    box-shadow: 0 10px 40px rgba(0,0,0,0.05);
}
.auth-form-group {
    margin-bottom: 1.5rem;
}
.auth-form-group.half-width {
    display: inline-block;
    width: 48%;
}
.auth-form-group.half-width:nth-child(2) {
    margin-left: 3%;
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
    padding: 0.8rem 1rem;
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
    padding: 1.1rem;
    background: #5b21b6;
    color: white;
    border: none;
    border-radius: 8px;
    font-size: 1.05rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s;
    margin-top: 1rem;
}
.auth-btn:hover {
    background: #4c1d95;
    box-shadow: 0 5px 15px rgba(91, 33, 182, 0.2);
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
.auth-checkbox-group {
    margin-bottom: 1rem;
    display: flex;
    align-items: flex-start;
    gap: 0.75rem;
}
.auth-checkbox-group input[type="checkbox"] {
    margin-top: 4px;
    accent-color: #5b21b6;
    width: 16px;
    height: 16px;
}
.auth-checkbox-group label {
    font-size: 0.85rem;
    color: #475569;
    font-weight: 400;
    line-height: 1.5;
}
.auth-checkbox-group a {
    color: #5b21b6;
    font-weight: 700;
    text-decoration: none;
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
.auth-left-text h2 {
    font-size: 2.2rem;
    color: var(--dark);
    font-weight: 800;
    margin-bottom: 0.75rem;
    letter-spacing: -0.5px;
    line-height: 1.2;
}
.auth-left-text p {
    color: #475569;
    line-height: 1.6;
    font-size: 1rem;
}
.auth-benefits {
    margin-top: 2rem;
    list-style: none;
    padding: 0;
}
.auth-benefits li {
    display: flex;
    align-items: flex-start;
    gap: 0.75rem;
    color: #1e293b;
    margin-bottom: 1.2rem;
    font-size: 0.95rem;
    line-height: 1.5;
    font-weight: 500;
}
.auth-benefits li i {
    color: #0ea5e9;
    margin-top: 4px;
}
.auth-note {
    font-size: 0.85rem;
    color: #64748b;
    margin-top: 2.5rem;
    line-height: 1.6;
    padding-top: 1.5rem;
    border-top: 1px solid #e2e8f0;
}
@media(max-width: 992px) {
    .auth-container { flex-direction: column; gap: 2rem; }
    .auth-left { display: none; }
    .auth-form-group.half-width { width: 100%; display: block; margin-left: 0 !important; }
}
</style>

<div class="auth-wrapper">
    <div class="auth-container">
        <!-- Left Side -->
        <div class="auth-left">
            <div class="auth-left-graphic">
                <div class="auth-shape-1"></div>
                <div class="auth-shape-2"></div>
                <div class="auth-shape-3"></div>
                <img src="assets/images/cont.png" alt="Bluestone Circle" class="auth-img">
            </div>
            <div class="auth-left-text">
                <h2>Create your Bluestone account</h2>
                <p>One account for all your study abroad needs. Sign up today.</p>
                
                <ul class="auth-benefits">
                    <li><i class="fa-solid fa-check"></i> Access your personalised dashboard.</li>
                    <li><i class="fa-solid fa-check"></i> Shortlist and save your favourite courses.</li>
                    <li><i class="fa-solid fa-check"></i> Same account username and password can be used for accessing Bluestone Live app.</li>
                </ul>

            </div>
        </div>
        
        <!-- Right Side Form -->

        <div class="auth-right">
            <?php if($error): ?>
                <div style="background:#fee2e2;color:#dc2626;padding:1rem;border-radius:8px;margin-bottom:1rem;"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>
            <?php if($success): ?>
                <div style="background:#d1fae5;color:#059669;padding:1rem;border-radius:8px;margin-bottom:1rem;"><?= $success ?></div>
            <?php endif; ?>
            <form action="sign-up.php" method="POST">
                <div>
                    <div class="auth-form-group half-width">
                        <label>* First name</label>
                        <input type="text" name="first_name" class="auth-input" placeholder="First name" required>
                    </div>
                    <div class="auth-form-group half-width">
                        <label>* Last name</label>
                        <input type="text" name="last_name" class="auth-input" placeholder="Last name" required>
                    </div>
                </div>
                
                <div style="display: flex; gap: 1rem;">
                    <div class="auth-form-group" style="width: 120px; flex-shrink: 0;">
                        <label>Dial code</label>
                        <select class="auth-input" name="dial_code">
                            <option>+91</option>
                            <option>+1</option>
                            <option>+44</option>
                            <option>+61</option>
                        </select>
                    </div>
                    
                    <div class="auth-form-group" style="flex-grow: 1;">
                        <label>* Mobile number</label>
                        <input type="tel" name="phone" class="auth-input" placeholder="Mobile number" required>
                    </div>
                </div>
                
                <div class="auth-form-group">
                    <label>* Email</label>
                    <input type="email" name="email" class="auth-input" placeholder="Enter your email" required>
                </div>
                
                <div class="auth-form-group">
                    <label>* Create a password</label>
                    <div class="password-wrap">
                        <input type="password" name="password" class="auth-input" placeholder="Enter your password" required>
                        <i class="fa-regular fa-eye-slash password-toggle"></i>
                    </div>
                </div>
                
                <div style="margin-top: 2rem;">
                    <div class="auth-checkbox-group">
                        <input type="checkbox" id="terms" required>
                        <label for="terms">I agree to Bluestone <a href="terms-and-conditions.php">Terms</a> and <a href="privacy-policy.php">privacy policy</a> *</label>
                    </div>
                    <div class="auth-checkbox-group">
                        <input type="checkbox" id="contact_consent">
                        <label for="contact_consent">Please contact me by phone, email or SMS to assist with my enquiry *</label>
                    </div>
                    <div class="auth-checkbox-group">
                        <input type="checkbox" id="marketing_consent">
                        <label for="marketing_consent">I agree to receive occasional communications from Bluestone about courses, offers and other marketing information</label>
                    </div>
                </div>
                
                <button type="submit" class="auth-btn">Create an account</button>
                
                <div class="auth-footer">
                    Already have an account? <a href="sign-in.php">Sign in</a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const togglePasswords = document.querySelectorAll('.password-toggle');
    togglePasswords.forEach(function(icon) {
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
});
</script>

<?php require_once 'includes/footer.php'; ?>
