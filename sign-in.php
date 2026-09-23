<?php
require_once 'includes/config.php';
$pageTitle = 'Sign In - Bluestone Circle';
require_once 'includes/header.php';
?>
<!-- Custom CSS for Auth Pages -->
<style>
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
    padding: 2rem;
}
.auth-left-graphic {
    position: relative;
    width: 100%;
    max-width: 400px;
    margin: 0 auto;
}
.auth-shape-1 {
    position: absolute;
    top: 10%;
    left: 0;
    width: 60%;
    height: 70%;
    background-color: #5b21b6;
    border-radius: 50% 0 50% 50%;
    z-index: 1;
}
.auth-shape-2 {
    position: absolute;
    bottom: 5%;
    right: 0;
    width: 60%;
    height: 60%;
    background-color: #0ea5e9;
    border-radius: 50% 50% 50% 0;
    z-index: 1;
}
.auth-shape-3 {
    position: absolute;
    top: 0;
    right: 20%;
    width: 30%;
    height: 30%;
    background-color: #ec4899;
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
    flex: 1;
    background: #ffffff;
    border-radius: 24px;
    padding: 4rem 3rem;
    box-shadow: 0 10px 40px rgba(0,0,0,0.05);
}
.auth-title {
    font-size: 2.2rem;
    font-weight: 800;
    color: var(--dark);
    margin-bottom: 0.5rem;
    text-align: center;
}
.auth-subtitle {
    color: #64748b;
    text-align: center;
    margin-bottom: 2.5rem;
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
    padding: 1rem;
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
.auth-forgot {
    display: block;
    text-align: right;
    color: #5b21b6;
    font-size: 0.9rem;
    font-weight: 700;
    text-decoration: none;
    margin-top: 0.75rem;
}
.auth-footer {
    text-align: center;
    margin-top: 2rem;
    font-size: 0.95rem;
    color: #64748b;
}
.auth-footer a {
    color: #5b21b6;
    font-weight: 700;
    text-decoration: none;
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
@media(max-width: 992px) {
    .auth-container { flex-direction: column; gap: 2rem; }
    .auth-left { display: none; }
}
</style>

<div class="auth-wrapper">
    <div class="auth-container">
        <!-- Left Side Graphic -->
        <div class="auth-left">
            <div class="auth-left-graphic">
                <div class="auth-shape-1"></div>
                <div class="auth-shape-2"></div>
                <div class="auth-shape-3"></div>
                <!-- Default image fallback -->
                <img src="assets/images/cont.png" alt="Bluestone Circle" class="auth-img">
            </div>
        </div>
        
        <!-- Right Side Form -->
        <div class="auth-right">
            <h1 class="auth-title">Sign in</h1>
            <p class="auth-subtitle">Welcome back! Please enter your details.</p>
            
            <form action="#" method="POST">
                <div class="auth-form-group">
                    <label>Email</label>
                    <input type="email" class="auth-input" placeholder="Enter your email" required>
                </div>
                
                <div class="auth-form-group">
                    <label>Password</label>
                    <div class="password-wrap">
                        <input type="password" class="auth-input" placeholder="Enter your password" required>
                        <i class="fa-regular fa-eye-slash password-toggle"></i>
                    </div>
                    <a href="#" class="auth-forgot">Forgot password?</a>
                </div>
                
                <button type="submit" class="auth-btn">Sign in</button>
                
                <div class="auth-footer">
                    Don't have an account? <a href="sign-up.php">Sign up</a>
                </div>
            </form>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
