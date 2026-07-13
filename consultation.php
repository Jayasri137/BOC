<?php
require_once 'includes/config.php';
$pageTitle = 'Free Study Abroad Consultation in Coimbatore | Bluestone Overseas';
$pageDesc = 'Book a free consultation with expert study abroad advisors for university selection, admissions, and visas.';
require_once 'includes/header.php';
?>
<main>
<div class="container" style="padding-top: 2rem; padding-bottom: 1rem;"><h1 class="section__title" style="text-align:center; margin:0; font-size: 2.2rem;">Free Overseas Education Consultation</h1></div>

  <section class="section">
    <div class="container">
      <div class="grid grid--2 gap--4">
        <div class="animate-on-scroll">
          <h2 class="section__title" style="text-align:left">Why Book a <span>Free Session?</span></h2>
          <ul class="consult-perks" style="margin-top:2rem">
            <li style="margin-bottom:1.5rem; display:flex; gap:1rem; align-items:flex-start">
              <i class="fa-solid fa-check-circle" style="color:var(--primary); font-size:1.25rem"></i>
              <div><h4 style="font-weight:700">Expert Profile Evaluation</h4><p style="font-size:.85rem; color:var(--gray)">We analyze your academics, scores, and interests to suggest the best path.</p></div>
            </li>
            <li style="margin-bottom:1.5rem; display:flex; gap:1rem; align-items:flex-start">
              <i class="fa-solid fa-check-circle" style="color:var(--primary); font-size:1.25rem"></i>
              <div><h4 style="font-weight:700">Country & Course Selection</h4><p style="font-size:.85rem; color:var(--gray)">Get a shortlisted list of universities that match your career goals.</p></div>
            </li>
            <li style="margin-bottom:1.5rem; display:flex; gap:1rem; align-items:flex-start">
              <i class="fa-solid fa-check-circle" style="color:var(--primary); font-size:1.25rem"></i>
              <div><h4 style="font-weight:700">Financial Planning</h4><p style="font-size:.85rem; color:var(--gray)">Understand the total cost and available scholarship options.</p></div>
            </li>
          </ul>
        </div>
        <div class="animate-on-scroll delay-1">
          <div class="contact-form-wrap">
            <form id="consultationForm" onsubmit="return handleFormSubmit(event)">
              <input type="hidden" name="form_type" value="enquiry">
              <input type="hidden" name="counselling_mode" value="Virtual Counselling">
              <input type="hidden" name="funding_mode" value="Self-funded">
              <div class="cf-grid-2">
                <div class="cf-group"><label>First Name</label><input type="text" name="first_name" required></div>
                <div class="cf-group"><label>Last Name</label><input type="text" name="last_name" required></div>
              </div>
              <div class="cf-group"><label>Email</label><input type="email" name="email" required></div>
              <div class="cf-group"><label>Phone</label><input type="tel" name="phone" required></div>
              <div class="cf-group"><label>Preferred Country</label>
                <select name="destination"><option value="">Select Country</option><option>USA</option><option>UK</option><option>Canada</option><option>Australia</option><option>Germany</option><option>Other</option></select>
              </div>
              <button type="submit" class="btn btn--primary btn--lg" style="width:100%; justify-content:center">Book My Free Session</button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </section>
</main>
<?php require_once 'includes/footer.php'; ?>
