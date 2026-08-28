<?php
require_once 'includes/config.php';
$pageTitle = 'Privacy Policy | Bluestone Overseas Consultants';
$pageDesc = 'Read the privacy policy of Bluestone Overseas Consultants. Learn how we collect, protect, and use your personal information.';
require_once 'includes/header.php';
?>

<style>
.legal-header {
  padding: 6rem 0 2rem;
  color: var(--dark);
  position: relative;
}
.legal-card {
  background: var(--white);
  padding: 4rem;
  border-radius: 24px;
  box-shadow: var(--shadow);
  border: 1px solid rgba(0, 0, 0, 0.05);
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
  <section class="legal-header animate-on-scroll text-center" style="text-align: center;">
    <div class="container">
      <h1 style="font-family: 'Plus Jakarta Sans', sans-serif; font-size: clamp(2rem, 4vw, 3rem); font-weight: 800; line-height: 1.2; margin: 0; letter-spacing: -0.02em;">
        Privacy <span style="background: var(--gradient); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;">Policy</span>
      </h1>
      <p style="margin-top: 1rem; font-size: 1.05rem; color: var(--gray); max-width: 600px; line-height: 1.5; font-weight: 400; margin-left: auto; margin-right: auto;">
        At Bluestone Overseas Consultants, we respect your privacy and are committed to protecting your personal data.
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
              <i class="fa-solid fa-user-shield"></i>
            </span>
            <h2 style="font-family: 'Plus Jakarta Sans', sans-serif; font-size: 1.5rem; font-weight: 700; color: var(--dark); margin: 0;">
              Your Privacy Matters to Us
            </h2>
          </div>
          <p style="color: var(--gray); font-size: 1.05rem; line-height: 1.8; margin: 0;">
            This Privacy Policy explains how Bluestone Overseas Consultants collects, protects, uses, and shares your personal information when you use our website, counseling services, or interact with our consulting team.
          </p>
        </div>

        <!-- Privacy Details -->
        <div class="legal-sections-container">
          
          <!-- 1. Information We Collect -->
          <div class="legal-section">
            <h3 class="legal-section-title">
              <span class="legal-section-icon"><i class="fa-solid fa-circle-chevron-right"></i></span>
              1. Information We Collect
            </h3>
            <div class="legal-content">
              <p>We collect personal information necessary to offer top-tier consulting, admission support, and visa processing, which includes:</p>
              <ul class="legal-list">
                <li class="legal-list-item">
                  <i class="fa-solid fa-circle-check"></i>
                  <span><strong>Personal Identifiers:</strong> Name, physical address, email address, phone number, and date of birth.</span>
                </li>
                <li class="legal-list-item">
                  <i class="fa-solid fa-circle-check"></i>
                  <span><strong>Official Documents:</strong> Passport details, passport scans, and other national identification records.</span>
                </li>
                <li class="legal-list-item">
                  <i class="fa-solid fa-circle-check"></i>
                  <span><strong>Academic Records:</strong> Transcripts, certificates, test scores (IELTS, TOEFL, PTE), and relevant educational histories.</span>
                </li>
                <li class="legal-list-item">
                  <i class="fa-solid fa-circle-check"></i>
                  <span><strong>Website Analytics Data:</strong> IP addresses, browser types, cookies, session durations, and general browsing behavior on our platform.</span>
                </li>
              </ul>
            </div>
          </div>

          <!-- 2. How We Use Your Information -->
          <div class="legal-section">
            <h3 class="legal-section-title">
              <span class="legal-section-icon"><i class="fa-solid fa-circle-chevron-right"></i></span>
              2. How We Use Your Information
            </h3>
            <div class="legal-content">
              <p>We use the gathered information to fulfill your aspirations and provide robust, customized support:</p>
              <ul class="legal-list">
                <li class="legal-list-item">
                  <i class="fa-solid fa-circle-check"></i>
                  <span>To accurately process university admissions, college registrations, and visa applications.</span>
                </li>
                <li class="legal-list-item">
                  <i class="fa-solid fa-circle-check"></i>
                  <span>To communicate important updates, customized offers, pre-departure advisories, and seminars.</span>
                </li>
                <li class="legal-list-item">
                  <i class="fa-solid fa-circle-check"></i>
                  <span>To constantly enhance our website functionality, online services, and student support systems.</span>
                </li>
              </ul>
            </div>
          </div>

          <!-- 3. Information Sharing -->
          <div class="legal-section">
            <h3 class="legal-section-title">
              <span class="legal-section-icon"><i class="fa-solid fa-circle-chevron-right"></i></span>
              3. Information Sharing
            </h3>
            <div class="legal-content">
              <p>We understand the importance of keeping your private records secure. Your information is only shared with authorized entities to facilitate your applications, including:</p>
              <ul class="legal-list">
                <li class="legal-list-item">
                  <i class="fa-solid fa-circle-check"></i>
                  <span>Partner universities, colleges, and global educational institutions for admissions processing.</span>
                </li>
                <li class="legal-list-item">
                  <i class="fa-solid fa-circle-check"></i>
                  <span>Embassies, high commissions, visa centers, and official immigration authorities.</span>
                </li>
                <li class="legal-list-item">
                  <i class="fa-solid fa-circle-check"></i>
                  <span>Third-party essential service partners, such as document couriers, banking affiliates for education loans, or travel coordinators.</span>
                </li>
              </ul>
              <p style="margin-top: 1rem;"><strong>Marketing Safeguard:</strong> We strictly do not sell, lease, trade, or distribute your personal information to third parties for independent marketing or promotional activities.</p>
            </div>
          </div>

          <!-- 4. Data Security -->
          <div class="legal-section">
            <h3 class="legal-section-title">
              <span class="legal-section-icon"><i class="fa-solid fa-circle-chevron-right"></i></span>
              4. Data Security
            </h3>
            <div class="legal-content">
              <p>We implement a multi-layered suite of administrative, physical, and technical security measures to guarantee that your personal data is protected against unauthorized access, loss, alteration, or disclosure. However, please be aware that no data transmission over the internet or cloud storage network is completely secure, and we cannot guarantee absolute security.</p>
            </div>
          </div>

          <!-- 5. Your Rights -->
          <div class="legal-section">
            <h3 class="legal-section-title">
              <span class="legal-section-icon"><i class="fa-solid fa-circle-chevron-right"></i></span>
              5. Your Rights
            </h3>
            <div class="legal-content">
              <p>You have full control over your personal data. You are entitled to the following rights:</p>
              <ul class="legal-list">
                <li class="legal-list-item">
                  <i class="fa-solid fa-circle-check"></i>
                  <span>The right to request access, correction, or deletion of the personal information stored in our databases.</span>
                </li>
                <li class="legal-list-item">
                  <i class="fa-solid fa-circle-check"></i>
                  <span>The right to opt-out of marketing newsletters, updates, or communication lists at any point in time.</span>
                </li>
              </ul>
            </div>
          </div>

          <!-- 6. Cookies Policy -->
          <div class="legal-section">
            <h3 class="legal-section-title">
              <span class="legal-section-icon"><i class="fa-solid fa-circle-chevron-right"></i></span>
              6. Cookies Policy
            </h3>
            <div class="legal-content">
              <p>Our website utilizes cookies to recognize your browser, remember critical preferences, and analyze visitor traffic patterns. This helps us customize and enhance your overall browsing experience. If you prefer, you can modify your web browser settings to block or decline cookies; however, some sections of the website may not function optimally as a result.</p>
            </div>
          </div>

          <!-- 7. Changes to this Privacy Policy -->
          <div class="legal-section">
            <h3 class="legal-section-title">
              <span class="legal-section-icon"><i class="fa-solid fa-circle-chevron-right"></i></span>
              7. Changes to this Privacy Policy
            </h3>
            <div class="legal-content">
              <p>We may update this Privacy Policy periodically to reflect shifts in our practices, legal mandates, or service upgrades. Any changes will be posted immediately on this webpage with the revised "Effective Date" at the top of the policy.</p>
            </div>
          </div>

          <!-- 8. Contact Us -->
          <div class="legal-section" style="margin-bottom: 0;">
            <h3 class="legal-section-title">
              <span class="legal-section-icon"><i class="fa-solid fa-circle-chevron-right"></i></span>
              8. Contact Us
            </h3>
            <div class="legal-content">
              <p>If you have any questions, comments, or concerns regarding this Privacy Policy, our data handling procedures, or if you wish to exercise your rights, please reach out directly to our support team:</p>
              <ul class="legal-list" style="margin-top: 1rem;">
                <li class="legal-list-item">
                  <i class="fa-solid fa-circle-check"></i>
                  <span><strong>Call Us:</strong> +91 93428 99904</span>
                </li>
                <li class="legal-list-item">
                  <i class="fa-solid fa-circle-check"></i>
                  <span><strong>Email Us:</strong> coimbatore@bluestoneoverseas.com</span>
                </li>
                <li class="legal-list-item">
                  <i class="fa-solid fa-circle-check"></i>
                  <span><strong>Visit Us:</strong> Renaissance Terrace, NO.126L, 2nd Floor, Opp. Bishop Appasamy College, Coimbatore - 641018, Tamil Nadu, India.</span>
                </li>
              </ul>
            </div>
          </div>

        </div>

      </div>
    </div>
  </section>
</main>

<?php require_once 'includes/footer.php'; ?>
