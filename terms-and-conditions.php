<?php
require_once 'includes/config.php';
$pageTitle = 'Terms and Conditions | Bluestone Overseas Consultants';
$pageDesc = 'Read the terms and conditions of Bluestone Overseas Consultants. Agree to our terms of service before using our study abroad consultancy.';
require_once 'includes/header.php';
?>

<style>
.legal-header {
  background: linear-gradient(135deg, var(--secondary-dark), var(--dark2));
  padding: 6rem 0 4.5rem;
  color: var(--white);
  position: relative;
  overflow: hidden;
  border-bottom: 1px solid rgba(255, 255, 255, 0.05);
}
.legal-header::after {
  content: '';
  position: absolute;
  top: -50px;
  right: -50px;
  width: 350px;
  height: 350px;
  background: radial-gradient(circle, rgba(255, 0, 0, 0.15) 0%, transparent 70%);
  border-radius: 50%;
  pointer-events: none;
}
.legal-header::before {
  content: '';
  position: absolute;
  bottom: -50px;
  left: -50px;
  width: 250px;
  height: 250px;
  background: radial-gradient(circle, rgba(255, 51, 51, 0.08) 0%, transparent 70%);
  border-radius: 50%;
  pointer-events: none;
}
.legal-breadcrumb {
  display: flex;
  align-items: center;
  gap: 0.5rem;
  font-size: 0.85rem;
  color: rgba(255, 255, 255, 0.6);
  margin-bottom: 1.25rem;
  font-weight: 500;
}
.legal-breadcrumb a {
  color: rgba(255, 255, 255, 0.8);
  transition: color var(--transition);
}
.legal-breadcrumb a:hover {
  color: var(--primary);
}
.legal-card {
  background: var(--white);
  padding: 4rem;
  border-radius: 24px;
  box-shadow: var(--shadow);
  border: 1px solid rgba(255, 0, 0, 0.05);
  margin-top: -2.5rem;
  position: relative;
  z-index: 10;
  transition: transform var(--transition);
}
.legal-card:hover {
  transform: translateY(-2px);
  box-shadow: var(--shadow-lg);
}
.legal-welcome {
  margin-bottom: 3.5rem;
  padding-bottom: 2rem;
  border-bottom: 1px solid #f1f5f9;
}
.legal-title-section {
  display: flex;
  align-items: center;
  gap: 0.85rem;
  margin-bottom: 1.25rem;
}
.legal-title-icon {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  width: 42px;
  height: 42px;
  background: rgba(255, 0, 0, 0.08);
  border-radius: 12px;
  color: var(--primary);
  font-size: 1.15rem;
}
.legal-section {
  margin-bottom: 3rem;
}
.legal-section-title {
  font-family: 'Plus Jakarta Sans', sans-serif;
  font-size: 1.35rem;
  font-weight: 700;
  color: var(--dark);
  margin-bottom: 1.25rem;
  display: flex;
  align-items: center;
  gap: 0.85rem;
}
.legal-section-icon {
  color: var(--primary);
  font-size: 1.2rem;
  transition: transform 0.3s;
}
.legal-section:hover .legal-section-icon {
  transform: translateX(3px);
}
.legal-content {
  padding-left: 2.5rem;
  color: var(--gray);
  font-size: 1.02rem;
  line-height: 1.75;
}
.legal-list {
  list-style: none;
  padding: 0;
  margin: 1.25rem 0 0 0;
  display: flex;
  flex-direction: column;
  gap: 0.85rem;
}
.legal-list-item {
  display: flex;
  align-items: flex-start;
  gap: 0.85rem;
}
.legal-list-item i {
  color: #10b981;
  margin-top: 0.3rem;
  font-size: 0.95rem;
}
.legal-list-item span {
  flex: 1;
}

@media (max-width: 768px) {
  .legal-card {
    padding: 2.5rem 1.5rem;
    margin-top: -1.5rem;
  }
  .legal-content {
    padding-left: 0;
  }
  .legal-section-title {
    font-size: 1.2rem;
  }
}
</style>

<main>
  <!-- Page Title Banner -->
  <section class="legal-header animate-on-scroll">
    <div class="container">
      <div class="legal-breadcrumb">
        <a href="index.php">Home</a>
        <i class="fa-solid fa-chevron-right" style="font-size: 0.7rem; opacity: 0.7;"></i>
        <span style="color: var(--primary);">Terms & Conditions</span>
      </div>
      <h1 style="font-family: 'Plus Jakarta Sans', sans-serif; font-size: clamp(2rem, 4vw, 3rem); font-weight: 800; line-height: 1.2; margin: 0; letter-spacing: -0.02em;">
        Terms & <span style="background: var(--gradient); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;">Conditions</span>
      </h1>
      <p style="margin-top: 1rem; font-size: 1.05rem; color: rgba(255,255,255,0.7); max-width: 600px; line-height: 1.5; font-weight: 400;">
        Welcome to Bluestone Overseas Consultants. Please read these terms and conditions carefully before using our services.
      </p>
    </div>
  </section>

  <!-- Legal Content Section -->
  <section class="section" style="background: var(--light); padding: 4rem 0 6rem;">
    <div class="container animate-on-scroll">
      <div class="legal-card">
        
        <!-- Welcome Section -->
        <div class="legal-welcome">
          <div class="legal-title-section">
            <span class="legal-title-icon">
              <i class="fa-solid fa-handshake"></i>
            </span>
            <h2 style="font-family: 'Plus Jakarta Sans', sans-serif; font-size: 1.5rem; font-weight: 700; color: var(--dark); margin: 0;">
              Welcome to Bluestone Overseas Consultants!
            </h2>
          </div>
          <p style="color: var(--gray); font-size: 1.05rem; line-height: 1.8; margin: 0;">
            By using our website and services, you agree to adhere to and be bound by the following terms and conditions. If you express disagreement with any part of these terms and conditions, we advise you not to avail of our services.
          </p>
        </div>

        <!-- Terms Details -->
        <div class="legal-sections-container">
          
          <!-- 1. Services -->
          <div class="legal-section">
            <h3 class="legal-section-title">
              <span class="legal-section-icon"><i class="fa-solid fa-circle-chevron-right"></i></span>
              1. Services
            </h3>
            <div class="legal-content">
              <p>We offer specialized study abroad consultancy services for students who are interested in pursuing higher education in international universities. Our dedicated services include:</p>
              <ul class="legal-list">
                <li class="legal-list-item">
                  <i class="fa-solid fa-circle-check"></i>
                  <span>One-to-one personalized career counselling and guidance.</span>
                </li>
                <li class="legal-list-item">
                  <i class="fa-solid fa-circle-check"></i>
                  <span>Complete assistance with university application compilation and submission.</span>
                </li>
                <li class="legal-list-item">
                  <i class="fa-solid fa-circle-check"></i>
                  <span>Comprehensive visa document verification, submission support, and interview preparation.</span>
                </li>
                <li class="legal-list-item">
                  <i class="fa-solid fa-circle-check"></i>
                  <span>Pre-departure guidance, accommodation advisory, and post-arrival transition support.</span>
                </li>
              </ul>
            </div>
          </div>

          <!-- 2. Eligibility -->
          <div class="legal-section">
            <h3 class="legal-section-title">
              <span class="legal-section-icon"><i class="fa-solid fa-circle-chevron-right"></i></span>
              2. Eligibility
            </h3>
            <div class="legal-content">
              <p>You must be a minimum of 16 years old to use our services. If you are under 18, parental or legal guardian consent is mandatory before starting any application or consultancy process with us.</p>
            </div>
          </div>

          <!-- 3. User Responsibilities -->
          <div class="legal-section">
            <h3 class="legal-section-title">
              <span class="legal-section-icon"><i class="fa-solid fa-circle-chevron-right"></i></span>
              3. User Responsibilities
            </h3>
            <div class="legal-content">
              <p>To ensure a successful admission and visa application process, you agree to the following responsibilities:</p>
              <ul class="legal-list">
                <li class="legal-list-item">
                  <i class="fa-solid fa-circle-check"></i>
                  <span>You must furnish fully precise, authentic, and complete information and documentation.</span>
                </li>
                <li class="legal-list-item">
                  <i class="fa-solid fa-circle-check"></i>
                  <span>You must strictly adhere to all the deadlines for submission of appropriate documents as requested.</span>
                </li>
                <li class="legal-list-item">
                  <i class="fa-solid fa-circle-check"></i>
                  <span>You must strictly adhere to the academic rules, guidelines, and code of conduct of the institutions to which you apply.</span>
                </li>
              </ul>
            </div>
          </div>

          <!-- 4. Service Fees -->
          <div class="legal-section">
            <h3 class="legal-section-title">
              <span class="legal-section-icon"><i class="fa-solid fa-circle-chevron-right"></i></span>
              4. Service Fees
            </h3>
            <div class="legal-content">
              <p>Service fees (if applicable) for counselling, processing, or application support must be paid in full upfront. All payments made are non-refundable and non-transferable under any circumstances, unless explicitly stated otherwise in a written contract signed by our management.</p>
            </div>
          </div>

          <!-- 5. Intellectual Property -->
          <div class="legal-section">
            <h3 class="legal-section-title">
              <span class="legal-section-icon"><i class="fa-solid fa-circle-chevron-right"></i></span>
              5. Intellectual Property
            </h3>
            <div class="legal-content">
              <p>All content presented on our website, including text, custom graphics, logos, layouts, buttons, audio clips, and digital assets, is the sole property of Bluestone Overseas Consultants and is fully protected under national and international copyright laws. Reproduction, redistribution, or modification without our prior written permission is strictly prohibited and subject to legal action.</p>
            </div>
          </div>

          <!-- 6. Limitation of Liability -->
          <div class="legal-section">
            <h3 class="legal-section-title">
              <span class="legal-section-icon"><i class="fa-solid fa-circle-chevron-right"></i></span>
              6. Limitation of Liability
            </h3>
            <div class="legal-content">
              <p>We strive to maintain the absolute accuracy of the information provided and dedicate our best professional efforts to achieve success for our students. However, Bluestone Overseas Consultants shall not be held liable for any direct or indirect errors, university admission rejections, visa approval delays, visa rejections, or any third-party courier and external service discrepancies.</p>
            </div>
          </div>

          <!-- 7. Modifications -->
          <div class="legal-section">
            <h3 class="legal-section-title">
              <span class="legal-section-icon"><i class="fa-solid fa-circle-chevron-right"></i></span>
              7. Modifications
            </h3>
            <div class="legal-content">
              <p>We reserve the right to modify, add, or replace these terms and conditions at any point in time. Updated terms will be posted directly on this webpage, and your continued utilization of our website and services signifies your agreement to the modified terms.</p>
            </div>
          </div>

          <!-- 8. Governing Law -->
          <div class="legal-section" style="margin-bottom: 0;">
            <h3 class="legal-section-title">
              <span class="legal-section-icon"><i class="fa-solid fa-circle-chevron-right"></i></span>
              8. Governing Law
            </h3>
            <div class="legal-content">
              <p>These terms and conditions are governed by and construed in accordance with the laws of Tamil Nadu, India. Any disputes arising from these terms shall be subject to the exclusive jurisdiction of the competent courts in Coimbatore, Tamil Nadu, India.</p>
            </div>
          </div>

        </div>

      </div>
    </div>
  </section>
</main>

<?php require_once 'includes/footer.php'; ?>