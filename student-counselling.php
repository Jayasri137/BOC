<?php
require_once 'includes/config.php';
$pageTitle = 'Student Counselling | Bluestone Overseas Consultants';
require_once 'includes/header.php';
?>
<main>
  <section class="section">
    <div class="container">
      <div class="grid grid--2 gap--4 align-center">
        <div class="animate-on-scroll">
          <div class="v-icon" style="width:120px; height:120px; font-size:3rem; margin:0"><i class="fa-solid fa-question-circle"></i></div>
          <h2 class="section__title" style="text-align:left; margin-top:2rem">Expert Guidance for <span>Your Future</span></h2>
          <p style="color:var(--gray); margin-top:1rem; line-height:1.6;">
            In association with <strong>Bluestone Overseas</strong> and drawing inspiration from global standards set by leaders like <strong>IDP.com</strong>, we bring you world-class counselling services to fast-track your international education journey.
          </p>
        </div>
        <div class="animate-on-scroll delay-1">
          <div class="service-details grid grid--1 gap--1">
            <div class="a-feat"><i class="fa-solid fa-check-circle"></i><span>Comprehensive Profile Assessment</span></div>
            <div class="a-feat"><i class="fa-solid fa-check-circle"></i><span>Career Goal &amp; Interest Mapping</span></div>
            <div class="a-feat"><i class="fa-solid fa-check-circle"></i><span>Budget Planning &amp; Estimation</span></div>
            <div class="a-feat"><i class="fa-solid fa-check-circle"></i><span>Personalized Country Selection</span></div>
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
          <h3>Consultation</h3>
          <p>Book a free session with our expert advisors to discuss your goals.</p>
        </div>
        <div class="service-card text-center animate-on-scroll delay-1">
          <div class="service-card__icon service-card__icon--purple" style="margin: 0 auto 1.5rem;"><i class="fa-solid fa-2"></i></div>
          <h3>Evaluation</h3>
          <p>We assess your academic background, test scores, and budget.</p>
        </div>
        <div class="service-card text-center animate-on-scroll delay-2">
          <div class="service-card__icon service-card__icon--orange" style="margin: 0 auto 1.5rem;"><i class="fa-solid fa-3"></i></div>
          <h3>Shortlisting</h3>
          <p>Receive a curated list of universities and courses matching your profile.</p>
        </div>
        <div class="service-card text-center animate-on-scroll delay-3">
          <div class="service-card__icon service-card__icon--teal" style="margin: 0 auto 1.5rem;"><i class="fa-solid fa-4"></i></div>
          <h3>Action Plan</h3>
          <p>Get a clear roadmap for applications, tests, and deadlines.</p>
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
          <h3 style="display: flex; align-items: center; gap: 0.5rem;"><i class="fa-solid fa-globe text-primary"></i> Global Standards</h3>
          <p>Our counselling framework is inspired by international leaders like IDP to ensure the highest quality of service.</p>
        </div>
        <div class="service-card animate-on-scroll delay-1">
          <h3 style="display: flex; align-items: center; gap: 0.5rem;"><i class="fa-solid fa-user-tie text-primary"></i> Expert Advisors</h3>
          <p>Work with seasoned professionals who have successfully guided thousands of students abroad.</p>
        </div>
        <div class="service-card animate-on-scroll delay-2">
          <h3 style="display: flex; align-items: center; gap: 0.5rem;"><i class="fa-solid fa-bullseye text-primary"></i> Unbiased Advice</h3>
          <p>We prioritize your career goals and offer 100% transparent and unbiased university recommendations.</p>
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
