<?php
require_once 'includes/config.php';
$pageTitle = 'Student Accommodation Assistance Abroad | Bluestone Overseas';
$pageDesc = 'Get help finding affordable and comfortable accommodation near your university.';
require_once 'includes/header.php';
?>
<main>
<div class="container" style="padding-top: 2rem; padding-bottom: 1rem;"><h1 class="section__title" style="text-align:center; margin:0; font-size: 2.2rem;">Find Safe Student Accommodation Abroad</h1></div>

  <section class="section">
    <div class="container">
      <div class="grid grid--2 gap--4 align-center">
        <div class="animate-on-scroll">
          <div class="v-icon" style="width:120px; height:120px; font-size:3rem; margin:0"><i class="fa-solid fa-plane-departure"></i></div>
          <h2 class="section__title" style="text-align:left; margin-top:2rem">Your Home <span>Away From Home</span></h2>
          <p style="color:var(--gray); margin-top:1rem; line-height:1.6;">
            In association with <strong>Bluestone Overseas</strong> and drawing inspiration from global standards set by leaders like <strong>IDP.com</strong>, we ensure you have a safe and comfortable place to stay the moment you land.
          </p>
        </div>
        <div class="animate-on-scroll delay-1">
          <div class="service-details grid grid--1 gap--1">
            <div class="a-feat"><i class="fa-solid fa-check-circle"></i><span>On-Campus Housing Assistance</span></div>
            <div class="a-feat"><i class="fa-solid fa-check-circle"></i><span>Off-Campus Flats &amp; Homestays</span></div>
            <div class="a-feat"><i class="fa-solid fa-check-circle"></i><span>International Flight Bookings</span></div>
            <div class="a-feat"><i class="fa-solid fa-check-circle"></i><span>Comprehensive Pre-Departure Briefing</span></div>
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
          <h3>Preferences</h3>
          <p>Tell us your budget, location preferences, and roommate requests.</p>
        </div>
        <div class="service-card text-center animate-on-scroll delay-1">
          <div class="service-card__icon service-card__icon--purple" style="margin: 0 auto 1.5rem;"><i class="fa-solid fa-2"></i></div>
          <h3>Options</h3>
          <p>We provide a curated list of verified student accommodations and flights.</p>
        </div>
        <div class="service-card text-center animate-on-scroll delay-2">
          <div class="service-card__icon service-card__icon--orange" style="margin: 0 auto 1.5rem;"><i class="fa-solid fa-3"></i></div>
          <h3>Booking</h3>
          <p>We handle the contracts, deposits, and ticketing securely.</p>
        </div>
        <div class="service-card text-center animate-on-scroll delay-3">
          <div class="service-card__icon service-card__icon--teal" style="margin: 0 auto 1.5rem;"><i class="fa-solid fa-4"></i></div>
          <h3>Arrival</h3>
          <p>Attend our pre-departure briefing and fly out fully prepared.</p>
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
          <h3 style="display: flex; align-items: center; gap: 0.5rem;"><i class="fa-solid fa-shield text-primary"></i> Verified Housing</h3>
          <p>All off-campus properties are thoroughly vetted for student safety and convenience.</p>
        </div>
        <div class="service-card animate-on-scroll delay-1">
          <h3 style="display: flex; align-items: center; gap: 0.5rem;"><i class="fa-solid fa-tags text-primary"></i> Student Discounts</h3>
          <p>Access exclusive flight discounts and affordable student baggage allowances.</p>
        </div>
        <div class="service-card animate-on-scroll delay-2">
          <h3 style="display: flex; align-items: center; gap: 0.5rem;"><i class="fa-solid fa-users text-primary"></i> Network Building</h3>
          <p>Our pre-departure sessions connect you with other students traveling to your city.</p>
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
