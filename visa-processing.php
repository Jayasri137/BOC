<?php
require_once 'includes/config.php';
$pageTitle = 'Visa Application Assistance | Bluestone Overseas';
$pageDesc = 'Get expert student visa application assistance from Bluestone Overseas. We provide documentation support, mock interviews, and seamless visa filing to ensure your success.';
$pageKeywords = 'Student visa consultants, Visa application assistance, Study abroad visa, Visa processing';
require_once 'includes/header.php';
?>

<style>
/* Premium Visa Page Styles */
:root {
    --visa-primary: #ec4899; /* Pink */
    --visa-primary-dark: #be185d;
    --visa-secondary: #f43f5e;
    --visa-dark: #0f172a;
    --visa-light: #fdf2f8;
    --visa-gray: #64748b;
}

.visa-hero {
    position: relative;
    padding: 8rem 2rem 6rem;
    background: linear-gradient(135deg, #fdf2f8 0%, #fce7f3 100%);
    overflow: hidden;
}

.visa-hero::before {
    content: '';
    position: absolute;
    top: -50%;
    left: -10%;
    width: 60%;
    height: 150%;
    background: radial-gradient(circle, rgba(236, 72, 153, 0.1) 0%, rgba(255,255,255,0) 70%);
    transform: rotate(30deg);
}

.visa-hero-content {
    position: relative;
    z-index: 2;
    max-width: 1200px;
    margin: 0 auto;
    display: flex;
    align-items: center;
    gap: 4rem;
}

.visa-hero-text {
    flex: 1.2;
    text-align: left;
}

.visa-hero-image {
    flex: 1;
    text-align: center;
    position: relative;
}

.visa-hero-image img {
    max-width: 100%;
    height: auto;
    border-radius: 20px;
    animation: float 6s ease-in-out infinite;
    filter: drop-shadow(0 25px 35px rgba(0,0,0,0.15));
}

@keyframes float {
    0% { transform: translateY(0px); }
    50% { transform: translateY(-20px); }
    100% { transform: translateY(0px); }
}

.hero-badge {
    display: inline-block;
    padding: 0.5rem 1rem;
    background: rgba(236, 72, 153, 0.15);
    color: var(--visa-primary-dark);
    font-weight: 700;
    border-radius: 50px;
    font-size: 0.9rem;
    margin-bottom: 1.5rem;
    letter-spacing: 1px;
    text-transform: uppercase;
}

.visa-hero h1 {
    font-size: 3.5rem;
    font-weight: 900;
    color: var(--visa-dark);
    line-height: 1.2;
    margin-bottom: 1.5rem;
    letter-spacing: -1px;
}

.visa-hero h1 span {
    background: linear-gradient(to right, var(--visa-primary), var(--visa-secondary));
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
}

.visa-hero p {
    font-size: 1.2rem;
    color: var(--visa-gray);
    margin-bottom: 2.5rem;
    line-height: 1.8;
}

.btn-visa {
    display: inline-block;
    padding: 1rem 2.5rem;
    background: var(--visa-primary);
    color: white;
    font-weight: 700;
    font-size: 1.1rem;
    border-radius: 50px;
    text-decoration: none;
    transition: all 0.3s;
    box-shadow: 0 10px 20px rgba(236, 72, 153, 0.4);
}

.btn-visa:hover {
    background: var(--visa-primary-dark);
    transform: translateY(-3px);
    box-shadow: 0 15px 25px rgba(236, 72, 153, 0.5);
    color: white;
}

.visa-services {
    padding: 6rem 2rem;
    background: #ffffff;
}

.section-header {
    text-align: center;
    max-width: 800px;
    margin: 0 auto 4rem;
}

.section-header h2 {
    font-size: 2.8rem;
    font-weight: 900;
    color: var(--visa-dark);
    margin-bottom: 1rem;
}

.section-header p {
    font-size: 1.1rem;
    color: var(--visa-gray);
    line-height: 1.7;
}

.services-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 2rem;
    max-width: 1200px;
    margin: 0 auto;
}

.service-card {
    background: #ffffff;
    border: 1px solid #f1f5f9;
    border-radius: 24px;
    padding: 2.5rem 2rem;
    transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    box-shadow: 0 10px 30px rgba(0,0,0,0.03);
    position: relative;
    overflow: hidden;
    z-index: 1;
}

.service-card:hover {
    transform: translateY(-10px);
    border-color: #fbcfe8;
    box-shadow: 0 20px 40px rgba(236, 72, 153, 0.1);
}

.service-icon {
    width: 70px;
    height: 70px;
    border-radius: 20px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.8rem;
    margin-bottom: 1.5rem;
    color: white;
    background: linear-gradient(135deg, var(--visa-primary), var(--visa-secondary));
    box-shadow: 0 10px 20px rgba(236, 72, 153, 0.3);
}

.service-card h3 {
    font-size: 1.4rem;
    font-weight: 800;
    color: var(--visa-dark);
    margin-bottom: 1rem;
}

.service-card p {
    color: var(--visa-gray);
    line-height: 1.7;
    font-size: 0.95rem;
}

/* How It Works Grid */
.how-it-works {
    padding: 6rem 2rem;
    background: linear-gradient(rgba(15, 23, 42, 0.85), rgba(15, 23, 42, 0.95)), url('assets/images/hero-bg-2.jpg') center/cover no-repeat;
    color: white;
}

.how-it-works .section-header h2 { color: white; }
.how-it-works .section-header p { color: #cbd5e1; }

.steps-container {
    max-width: 80rem; 
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
    background: linear-gradient(135deg, var(--visa-primary), var(--visa-secondary));
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

/* Advantage Section */
.advantage-section {
    padding: 6rem 2rem;
    background: white;
}

.advantage-container {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 4rem;
    align-items: center;
    max-width: 1200px;
    margin: 0 auto;
}

.advantage-card {
    background: var(--visa-primary);
    padding: 3rem;
    border-radius: 24px;
    box-shadow: 0 20px 40px rgba(236, 72, 153, 0.2);
    position: relative;
    color: white;
}

.advantage-card h3 {
    font-size: 2rem;
    font-weight: 900;
    margin-bottom: 1.5rem;
}

.advantage-card p {
    font-size: 1.1rem;
    line-height: 1.7;
    margin-bottom: 2rem;
    opacity: 0.9;
}

.advantage-list {
    list-style: none;
    padding: 0;
    margin: 0;
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.advantage-list li {
    display: flex;
    align-items: center;
    gap: 1rem;
    font-size: 1.1rem;
    font-weight: 600;
}

.advantage-list li i {
    color: #fbcfe8;
    font-size: 1.25rem;
}

.cta-section {
    padding: 6rem 2rem;
    background: linear-gradient(135deg, var(--visa-dark) 0%, #1e293b 100%);
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

@media(max-width: 992px) {
    .advantage-container { grid-template-columns: 1fr; }
}

@media(max-width: 768px) {
    .visa-hero h1 { font-size: 2.5rem; }
    .visa-hero-content { flex-direction: column; text-align: center; }
    .visa-hero-text { text-align: center; }
    .visa-hero-image { margin-top: 3rem; }
    .section-header h2 { font-size: 2.2rem; }
}
</style>

<main>
    <!-- Hero Section -->
    <section class="visa-hero">
        <div class="visa-hero-content animate-on-scroll">
            <div class="visa-hero-text">
                <div class="hero-badge"><i class="fa-solid fa-passport" style="margin-right: 8px;"></i> Visa Assistance</div>
                <h1>Expert Student <span>Visa Application</span> Support</h1>
                <p>Navigating visa requirements can be complex and stressful. Let our experienced visa consultants guide you through the documentation, interviews, and application process for a hassle-free experience.</p>
                <a href="consultation.php" class="btn-visa">Get Visa Assistance</a>
            </div>
            <div class="visa-hero-image">
                <img src="assets/images/service_visa_3d.png" alt="Visa Application Assistance">
            </div>
        </div>
    </section>

    <!-- Why Do You Need Assistance -->
    <section class="visa-services">
        <div class="section-header animate-on-scroll">
            <h2>Comprehensive Visa Services</h2>
            <p>We provide end-to-end support to ensure your student visa is approved the first time. Our experts are up-to-date with the latest immigration policies of top study destinations.</p>
        </div>
        <div class="services-grid">
            <div class="service-card animate-on-scroll">
                <div class="service-icon"><i class="fa-solid fa-file-signature"></i></div>
                <h3>Document Verification</h3>
                <p>We help you compile, verify, and translate all necessary documents, including financial proofs, academic transcripts, and university offer letters, ensuring 100% compliance.</p>
            </div>
            <div class="service-card animate-on-scroll delay-1">
                <div class="service-icon"><i class="fa-solid fa-user-tie"></i></div>
                <h3>Mock Interviews</h3>
                <p>Prepare for your visa interview with confidence. We conduct one-on-one mock interview sessions, providing personalized feedback and likely questions asked by visa officers.</p>
            </div>
            <div class="service-card animate-on-scroll delay-2">
                <div class="service-icon"><i class="fa-solid fa-plane-circle-check"></i></div>
                <h3>Seamless Filing</h3>
                <p>We securely lodge your visa application on your behalf, minimizing errors and delays. We continuously track your application status until you receive your visa.</p>
            </div>
        </div>
    </section>

    <!-- How It Works -->
    <section class="how-it-works">
        <div class="section-header animate-on-scroll">
            <h2>The Visa Process</h2>
            <p>Our streamlined approach takes the guesswork out of your visa application.</p>
        </div>
        <div class="steps-container">
            <div class="step-card animate-on-scroll">
                <div class="step-number">1</div>
                <h3>Consultation</h3>
                <p>Meet with our visa experts to understand the specific visa requirements and financial criteria for your chosen country.</p>
            </div>
            <div class="step-card animate-on-scroll delay-1">
                <div class="step-number">2</div>
                <h3>Documentation</h3>
                <p>We provide a customized checklist. You gather the documents, and our team rigorously verifies them for accuracy.</p>
            </div>
            <div class="step-card animate-on-scroll delay-2">
                <div class="step-number">3</div>
                <h3>Application & Interview</h3>
                <p>We submit the online application, schedule your biometrics appointment, and conduct rigorous mock interviews.</p>
            </div>
            <div class="step-card animate-on-scroll delay-3">
                <div class="step-number">4</div>
                <h3>Visa Approval</h3>
                <p>Track your visa status with us. Once approved, you are ready to attend our pre-departure briefing and fly!</p>
            </div>
        </div>
    </section>

    <!-- The Bluestone Advantage -->
    <section class="advantage-section">
        <div class="advantage-container">
            <div class="animate-on-scroll">
                <h2 style="font-size: 2.5rem; margin-bottom: 1.5rem; line-height: 1.2; color: var(--visa-dark);">Why Choose <span style="color: var(--visa-primary);">Bluestone</span></h2>
                <p style="color:var(--visa-gray); margin-bottom:2.5rem; line-height:1.7; font-size: 1.1rem;">
                    Don't risk a visa rejection that could delay or ruin your study abroad plans. Our authorized visa consultants have direct access to embassies and the latest policy updates.
                </p>
                <div style="padding: 1.5rem; background: #fdf2f8; border-radius: 16px; border-left: 4px solid var(--visa-primary); margin-bottom: 2rem;">
                    <p style="margin: 0; color: var(--visa-primary-dark); font-weight: 600; line-height: 1.6;">
                        <i class="fa-solid fa-triangle-exclamation" style="margin-right: 10px;"></i>
                        A minor mistake in your application or financial documents can lead to immediate rejection. Trust the experts to handle it flawlessly.
                    </p>
                </div>
            </div>
            
            <div class="advantage-card animate-on-scroll delay-1">
                <div style="position: absolute; top: -30px; right: -30px; width: 100px; height: 100px; background: rgba(255,255,255,0.2); border-radius: 50%; display: flex; align-items: center; justify-content: center; backdrop-filter: blur(5px);">
                    <i class="fa-solid fa-award" style="font-size: 2.5rem; color: white;"></i>
                </div>
                <h3>99% Visa Success Rate</h3>
                <p>Our meticulous attention to detail has resulted in an industry-leading visa approval rate for students across all major destinations.</p>
                <ul class="advantage-list">
                    <li><i class="fa-solid fa-check-circle"></i> Authorized Global Agents</li>
                    <li><i class="fa-solid fa-check-circle"></i> Dedicated Visa Case Managers</li>
                    <li><i class="fa-solid fa-check-circle"></i> Regular Policy Updates</li>
                    <li><i class="fa-solid fa-check-circle"></i> 5,000+ Visas Approved</li>
                </ul>
            </div>
        </div>
    </section>

    <!-- Call to Action -->
    <section class="cta-section">
        <div class="cta-container animate-on-scroll">
            <h2>Don't leave your visa to chance.</h2>
            <p>Get in touch with our expert visa consultants today and ensure your journey to studying abroad is smooth and successful.</p>
            <a href="contact.php" class="btn-visa" style="background: white; color: var(--visa-dark); box-shadow: 0 10px 20px rgba(255,255,255,0.2);">Book Your Free Visa Consultation</a>
        </div>
    </section>
</main>

<?php require_once 'includes/footer.php'; ?>
