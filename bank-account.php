<?php
require_once 'includes/config.php';
$pageTitle = 'Banking for International Students | Bluestone Overseas';
$pageDesc = 'Wondering how to manage banking as an international student? Explore our hassle-free student banking services to streamline your finances abroad.';
require_once 'includes/header.php';
?>

<style>
/* Premium Banking Page Styles */
:root {
    --bank-primary: #3b82f6;
    --bank-primary-dark: #2563eb;
    --bank-secondary: #8b5cf6;
    --bank-accent: #f59e0b;
    --bank-dark: #0f172a;
    --bank-light: #f8fafc;
    --bank-gray: #64748b;
}

.banking-hero {
    position: relative;
    padding: 8rem 2rem 6rem;
    background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%);
    overflow: hidden;
}

.banking-hero::before {
    content: '';
    position: absolute;
    top: -50%;
    left: -10%;
    width: 60%;
    height: 150%;
    background: radial-gradient(circle, rgba(59, 130, 246, 0.1) 0%, rgba(255,255,255,0) 70%);
    transform: rotate(30deg);
}

.banking-hero::after {
    content: '';
    position: absolute;
    bottom: -20%;
    right: -10%;
    width: 50%;
    height: 100%;
    background: radial-gradient(circle, rgba(139, 92, 246, 0.1) 0%, rgba(255,255,255,0) 70%);
}

.banking-hero-content {
    position: relative;
    z-index: 2;
    max-width: 1200px;
    margin: 0 auto;
    display: flex;
    align-items: center;
    gap: 4rem;
}

.banking-hero-text {
    flex: 1.2;
    text-align: left;
}

.banking-hero-image {
    flex: 1;
    text-align: center;
    position: relative;
}

.banking-hero-image img {
    max-width: 100%;
    height: auto;
    border-radius: 20px;
    animation: float 6s ease-in-out infinite;
    filter: drop-shadow(0 25px 35px rgba(0,0,0,0.1));
}

@keyframes float {
    0% { transform: translateY(0px); }
    50% { transform: translateY(-20px); }
    100% { transform: translateY(0px); }
}

.hero-badge {
    display: inline-block;
    padding: 0.5rem 1rem;
    background: rgba(59, 130, 246, 0.15);
    color: var(--bank-primary-dark);
    font-weight: 700;
    border-radius: 50px;
    font-size: 0.9rem;
    margin-bottom: 1.5rem;
    letter-spacing: 1px;
    text-transform: uppercase;
}

.banking-hero h1 {
    font-size: 3.5rem;
    font-weight: 900;
    color: var(--bank-dark);
    line-height: 1.2;
    margin-bottom: 1.5rem;
    letter-spacing: -1px;
}

.banking-hero h1 span {
    background: linear-gradient(to right, var(--bank-primary), var(--bank-secondary));
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
}

.banking-hero p {
    font-size: 1.2rem;
    color: var(--bank-gray);
    margin-bottom: 2.5rem;
    line-height: 1.8;
}

.banking-benefits {
    padding: 5rem 2rem;
    background: #ffffff;
}

.benefits-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 2rem;
    max-width: 1200px;
    margin: 0 auto;
}

.benefit-card {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 24px;
    padding: 2.5rem 2rem;
    transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    position: relative;
    overflow: hidden;
    z-index: 1;
}

.benefit-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: linear-gradient(135deg, rgba(59,130,246,0.05), rgba(139,92,246,0.05));
    opacity: 0;
    transition: opacity 0.4s;
    z-index: -1;
}

.benefit-card:hover {
    transform: translateY(-10px);
    border-color: #bfdbfe;
    box-shadow: 0 20px 40px rgba(15, 23, 42, 0.08);
}

.benefit-card:hover::before {
    opacity: 1;
}

.benefit-icon {
    width: 70px;
    height: 70px;
    border-radius: 20px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.8rem;
    margin-bottom: 1.5rem;
    color: white;
}

.icon-blue { background: linear-gradient(135deg, #60a5fa, #3b82f6); box-shadow: 0 10px 20px rgba(59, 130, 246, 0.3); }
.icon-purple { background: linear-gradient(135deg, #a78bfa, #8b5cf6); box-shadow: 0 10px 20px rgba(139, 92, 246, 0.3); }
.icon-emerald { background: linear-gradient(135deg, #34d399, #10b981); box-shadow: 0 10px 20px rgba(16, 185, 129, 0.3); }

.benefit-card h3 {
    font-size: 1.4rem;
    font-weight: 800;
    color: var(--bank-dark);
    margin-bottom: 1rem;
}

.benefit-card p {
    color: var(--bank-gray);
    line-height: 1.7;
    font-size: 0.95rem;
}

.how-it-works {
    padding: 6rem 2rem;
    background: linear-gradient(rgba(15, 23, 42, 0.85), rgba(15, 23, 42, 0.95)), url('assets/images/hero-bg-2.jpg') center/cover no-repeat;
    color: white;
}

.how-it-works .section-header h2 {
    color: white;
}

.how-it-works .section-header p {
    color: #cbd5e1;
}

.section-header {
    text-align: center;
    max-width: 700px;
    margin: 0 auto 4rem;
}

.section-header h2 {
    font-size: 2.8rem;
    font-weight: 900;
    color: var(--bank-dark);
    margin-bottom: 1rem;
}

.section-header p {
    font-size: 1.1rem;
    color: var(--bank-gray);
    line-height: 1.7;
}

.steps-container {
    max-width: 80rem; /* 1280px - max-w-7xl */
    margin: 0 auto;
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
    gap: 2rem;
    position: relative;
}

.step-card {
    background: rgba(255, 255, 255, 0.05);
    backdrop-filter: blur(12px);
    border: 1px solid rgba(255, 255, 255, 0.15);
    border-radius: 24px;
    padding: 2.5rem 2rem;
    text-align: center;
    transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
}

.step-card:hover {
    transform: translateY(-10px);
    background: rgba(255, 255, 255, 0.1);
    border-color: rgba(255, 255, 255, 0.3);
    box-shadow: 0 20px 40px rgba(0,0,0,0.3);
}

.step-number {
    width: 60px;
    height: 60px;
    margin: 0 auto 1.5rem;
    background: linear-gradient(135deg, var(--bank-primary), var(--bank-secondary));
    color: white;
    font-size: 1.8rem;
    font-weight: 900;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 10px 20px rgba(0,0,0,0.2);
}

.step-card h3 {
    font-size: 1.3rem;
    font-weight: 800;
    color: white;
    margin-bottom: 1rem;
}

.step-card p {
    color: #cbd5e1;
    line-height: 1.6;
    font-size: 0.95rem;
}

.destinations {
    padding: 6rem 2rem;
    background: white;
    text-align: center;
}

.dest-grid {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    gap: 2rem;
    max-width: 1200px;
    margin: 3rem auto 0;
}

.dest-card {
    flex: 1 1 200px;
    max-width: 250px;
    padding: 2rem;
    border-radius: 24px;
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    transition: all 0.3s;
}

.dest-card:hover {
    background: white;
    box-shadow: 0 15px 35px rgba(0,0,0,0.06);
    border-color: #cbd5e1;
}

.dest-image {
    width: 120px;
    height: 120px;
    margin: 0 auto 1.5rem;
}

.dest-image img {
    width: 100%;
    height: 100%;
    object-fit: contain;
    filter: drop-shadow(0 10px 15px rgba(0,0,0,0.1));
}

.dest-card h3 {
    font-size: 1.25rem;
    font-weight: 800;
    color: var(--bank-dark);
    margin-bottom: 0.5rem;
}

.dest-card p {
    color: var(--bank-gray);
    font-size: 0.9rem;
}

.cta-section {
    padding: 6rem 2rem;
    background: linear-gradient(135deg, var(--bank-dark) 0%, #1e293b 100%);
    text-align: center;
    color: white;
}

.cta-container {
    max-width: 800px;
    margin: 0 auto;
}

.cta-container h2 {
    font-size: 3rem;
    font-weight: 900;
    margin-bottom: 1.5rem;
}

.cta-container p {
    font-size: 1.2rem;
    color: #cbd5e1;
    margin-bottom: 2.5rem;
    line-height: 1.7;
}

.btn-primary {
    display: inline-block;
    padding: 1rem 2.5rem;
    background: var(--bank-primary);
    color: white;
    font-weight: 700;
    font-size: 1.1rem;
    border-radius: 50px;
    text-decoration: none;
    transition: all 0.3s;
    box-shadow: 0 10px 20px rgba(59, 130, 246, 0.4);
}

.btn-primary:hover {
    background: var(--bank-primary-dark);
    transform: translateY(-3px);
    box-shadow: 0 15px 25px rgba(59, 130, 246, 0.5);
    color: white;
}

@media(max-width: 768px) {
    .banking-hero h1 { font-size: 2.5rem; }
    .banking-hero-content { flex-direction: column; text-align: center; }
    .banking-hero-text { text-align: center; }
    .banking-hero-image { margin-top: 3rem; }
    .step-row { flex-direction: column; text-align: center; gap: 1.5rem; }
    .section-header h2 { font-size: 2.2rem; }
}
</style>

<main>
    <!-- Hero Section -->
    <section class="banking-hero">
        <div class="banking-hero-content animate-on-scroll">
            <div class="banking-hero-text">
                <div class="hero-badge"><i class="fa-solid fa-earth-americas" style="margin-right: 8px;"></i> Student Essentials</div>
                <h1>Banking for <span>International Students</span></h1>
                <p>Wondering how to manage banking as an international student? We make it easy. Set up your bank account before you leave home, so you can transfer funds securely and have your card ready when you arrive.</p>
                <a href="consultation.php" class="btn-primary">Get Expert Advice</a>
            </div>
            <div class="banking-hero-image">
                <img src="assets/images/service_financing_3d.png" alt="International Student Banking">
            </div>
        </div>
    </section>

    <!-- Why Choose Pre-Arrival Banking -->
    <section class="banking-benefits">
        <div class="section-header animate-on-scroll">
            <h2>Why set up your bank account early?</h2>
            <p>Avoid the stress of visiting branches and waiting in long queues after you land. Enjoy peace of mind with our pre-arrival banking services.</p>
        </div>
        <div class="benefits-grid">
            <div class="benefit-card animate-on-scroll">
                <div class="benefit-icon icon-blue"><i class="fa-solid fa-money-bill-transfer"></i></div>
                <h3>Seamless Fund Transfers</h3>
                <p>Transfer your tuition and living expenses safely to your own account before you depart. Favorable exchange rates and secure global networks.</p>
            </div>
            <div class="benefit-card animate-on-scroll delay-1">
                <div class="benefit-icon icon-emerald"><i class="fa-solid fa-file-invoice-dollar"></i></div>
                <h3>Zero Account Fees</h3>
                <p>Most of our partner banks offer specialized student accounts with zero monthly keeping fees and exclusive discounts for international students.</p>
            </div>
            <div class="benefit-card animate-on-scroll delay-2">
                <div class="benefit-icon icon-purple"><i class="fa-solid fa-credit-card"></i></div>
                <h3>Card Ready on Arrival</h3>
                <p>Pick up your debit card from a local branch as soon as you arrive, or have it mailed directly to your new student accommodation.</p>
            </div>
        </div>
    </section>

    <!-- How It Works -->
    <section class="how-it-works">
        <div class="section-header animate-on-scroll">
            <h2>How it works</h2>
            <p>A simple, streamlined process to get your finances sorted.</p>
        </div>
        <div class="steps-container">
            <div class="step-card animate-on-scroll">
                <div class="step-number">1</div>
                <h3>Consult with our experts</h3>
                <p>Speak to our student advisors. We'll help you choose the right bank based on your destination country and financial needs.</p>
            </div>
            <div class="step-card animate-on-scroll delay-1">
                <div class="step-number">2</div>
                <h3>Submit your application</h3>
                <p>Provide your passport, student visa, and university offer letter. Our team will guide you through the digital application process.</p>
            </div>
            <div class="step-card animate-on-scroll delay-2">
                <div class="step-number">3</div>
                <h3>Receive account details</h3>
                <p>Once approved (usually within a few days), you will receive your new bank account number and routing details via email.</p>
            </div>
            <div class="step-card animate-on-scroll delay-3">
                <div class="step-number">4</div>
                <h3>Activate upon arrival</h3>
                <p>When you arrive at your destination, visit a local branch with your passport to fully activate your account and collect your debit card.</p>
            </div>
        </div>
    </section>

    <!-- Partner Banks / Destinations -->
    <section class="destinations">
        <div class="section-header animate-on-scroll">
            <h2>Top Banking Destinations</h2>
            <p>We partner with leading national banks across major study destinations to ensure your money is safe and accessible.</p>
        </div>
        <div class="dest-grid">
            <div class="dest-card animate-on-scroll">
                <div class="dest-image"><img src="assets/images/3d_usa.png" alt="USA"></div>
                <h3>United States</h3>
                <p>Bank of America, Chase, Wells Fargo</p>
            </div>
            <div class="dest-card animate-on-scroll delay-1">
                <div class="dest-image"><img src="assets/images/3d_tower_bridge.png" alt="UK"></div>
                <h3>United Kingdom</h3>
                <p>HSBC, Barclays, Lloyds Bank</p>
            </div>
            <div class="dest-card animate-on-scroll delay-2">
                <div class="dest-image"><img src="assets/images/3d_canada.png" alt="Canada"></div>
                <h3>Canada</h3>
                <p>CIBC, RBC, Scotiabank, TD</p>
            </div>
            <div class="dest-card animate-on-scroll delay-3">
                <div class="dest-image"><img src="assets/images/aus.png" alt="Australia"></div>
                <h3>Australia</h3>
                <p>Commonwealth Bank, ANZ, NAB, Westpac</p>
            </div>
        </div>
    </section>

    <!-- Call to Action -->
    <section class="cta-section">
        <div class="cta-container animate-on-scroll">
            <h2>Ready to secure your finances?</h2>
            <p>Don't wait until you arrive. Set up your international student bank account today and start your journey with confidence.</p>
            <a href="contact.php" class="btn-primary" style="background: white; color: var(--bank-dark); box-shadow: 0 10px 20px rgba(255,255,255,0.2);">Contact Us Now</a>
        </div>
    </section>
</main>

<?php require_once 'includes/footer.php'; ?>
