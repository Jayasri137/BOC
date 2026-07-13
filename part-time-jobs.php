<?php
require_once 'includes/config.php';
$pageTitle = 'Part-Time Jobs for International Students Abroad';
$pageDesc = 'Learn about part-time job options, work regulations, and earning opportunities while studying abroad.';
require_once 'includes/header.php';
?>
<main>
<div class="container" style="padding-top: 2rem; padding-bottom: 1rem;"><h1 class="section__title" style="text-align:center; margin:0; font-size: 2.2rem;">Part-Time Work Opportunities for Students</h1></div>

  <section class="section">
    <div class="container">
      <div class="grid grid--2 gap--4 align-center">
        <div class="animate-on-scroll">
          <div class="v-icon" style="width:120px; height:120px; font-size:3rem; margin:0"><i class="fa-solid fa-briefcase"></i></div>
          <h2 class="section__title" style="text-align:left; margin-top:2rem">Earn While You <span>Learn</span></h2>
          <p style="color:var(--gray); margin-top:1rem; line-height:1.6;">
            In association with <strong>Bluestone Overseas</strong>, we prepare you for the global job market. Understand your work rights and equip yourself to gain valuable international work experience.
          </p>
        </div>
        <div class="animate-on-scroll delay-1">
          <div class="service-details grid grid--1 gap--1">
            <div class="a-feat"><i class="fa-solid fa-check-circle"></i><span>Work Rights &amp; Regulations Education</span></div>
            <div class="a-feat"><i class="fa-solid fa-check-circle"></i><span>International Resume Building</span></div>
            <div class="a-feat"><i class="fa-solid fa-check-circle"></i><span>Interview Preparation</span></div>
            <div class="a-feat"><i class="fa-solid fa-check-circle"></i><span>Local Job Portal Access &amp; Networking</span></div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <section class="section bg-light">
    <div class="container">
      <div class="text-center animate-on-scroll">
        <span class="section__tag">Process</span>
        <h2 class="section__title">How It <span>Works</span></h2>
        <p class="section__subtitle" style="max-width: 600px; margin: 0 auto;">A streamlined, step-by-step approach to ensure your success.</p>
      </div>
      <div class="grid grid--4 gap--2" style="margin-top: 3rem;">
        <div class="service-card text-center animate-on-scroll">
          <div class="service-card__icon service-card__icon--blue" style="margin: 0 auto 1.5rem;"><i class="fa-solid fa-1"></i></div>
          <h3>Regulations</h3>
          <p>We brief you on the legal part-time working hours permitted by your visa.</p>
        </div>
        <div class="service-card text-center animate-on-scroll delay-1">
          <div class="service-card__icon service-card__icon--purple" style="margin: 0 auto 1.5rem;"><i class="fa-solid fa-2"></i></div>
          <h3>Resume Prep</h3>
          <p>We help format your CV to match the standards of your destination country.</p>
        </div>
        <div class="service-card text-center animate-on-scroll delay-2">
          <div class="service-card__icon service-card__icon--orange" style="margin: 0 auto 1.5rem;"><i class="fa-solid fa-3"></i></div>
          <h3>Job Hunting</h3>
          <p>Learn how to use local job portals, university career centers, and networking.</p>
        </div>
        <div class="service-card text-center animate-on-scroll delay-3">
          <div class="service-card__icon service-card__icon--teal" style="margin: 0 auto 1.5rem;"><i class="fa-solid fa-4"></i></div>
          <h3>Interviewing</h3>
          <p>Mock interviews to help you confidently land a part-time role.</p>
        </div>
      </div>
    </div>
  </section>

  <section class="section">
    <div class="container">
      <div class="text-center animate-on-scroll">
        <span class="section__tag">Benefits</span>
        <h2 class="section__title">Why Choose <span>Bluestone</span></h2>
        <p class="section__subtitle" style="max-width: 600px; margin: 0 auto;">Experience the advantage of working with industry-leading experts.</p>
      </div>
      <div class="grid grid--3 gap--2" style="margin-top: 3rem;">
        <div class="service-card animate-on-scroll">
          <h3 style="display: flex; align-items: center; gap: 0.5rem;"><i class="fa-solid fa-shield-halved text-primary"></i> Legal Guidance</h3>
          <p>We ensure you stay compliant with your student visa regulations while working.</p>
        </div>
        <div class="service-card animate-on-scroll delay-1">
          <h3 style="display: flex; align-items: center; gap: 0.5rem;"><i class="fa-solid fa-handshake text-primary"></i> Alumni Network</h3>
          <p>Connect with our past students who can refer you to part-time jobs in their cities.</p>
        </div>
        <div class="service-card animate-on-scroll delay-2">
          <h3 style="display: flex; align-items: center; gap: 0.5rem;"><i class="fa-solid fa-file-invoice-dollar text-primary"></i> Tax Advice</h3>
          <p>Basic orientation on how to apply for your local tax file number and manage earnings.</p>
        </div>
      </div>
    </div>
  </section>

  <section class="section" style="padding-top: 0;">
    <div class="container animate-on-scroll">
      <div style="background: var(--gradient); padding: 4rem 2rem; border-radius: var(--radius-lg); text-align: center; color: white; box-shadow: var(--shadow-lg);">
        <h2 style="font-size: 2.5rem; margin-bottom: 1rem;">Ready to Start Your Global Journey?</h2>
        <p style="font-size: 1.1rem; opacity: 0.9; max-width: 600px; margin: 0 auto 2rem;">Join thousands of successful students who have achieved their dreams with Bluestone Overseas.</p>
        <a href="consultation.php" class="btn btn--white btn--lg pulse-btn" style="background: white; color: var(--primary);">Book Free Consultation</a>
      </div>
    </div>
  </section>
</main>
<?php require_once 'includes/footer.php'; ?>
