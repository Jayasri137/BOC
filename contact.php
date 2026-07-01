<?php
require_once 'includes/config.php';
$pageTitle = 'Contact Us | Bluestone Overseas Consultants';
$pageDesc = 'Contact Bluestone Overseas Consultants for study abroad guidance. Offices in Coimbatore, Chennai, Salem, Erode, Namakkal, Tirunelveli, Nepal and Canada.';
require_once 'includes/header.php';
?>
<main>
<section class="section contact-section">
  <div class="container">
    <div class="contact-grid">
      <div>
        <h1 style="font-size: 2rem; font-weight: 700; margin-bottom: 1rem;">Talk to Our <span class="text-gradient">Experts</span></h1>
        <p>Whether you&rsquo;re just starting your study abroad journey or need help with a visa application, our counsellors are here to help — for free.</p>
        <div class="contact-cards">
          <div class="contact-card"><i class="fa-solid fa-phone"></i><div><h4>Call Us</h4><a href="tel:+919342899904">+91 93428 99904</a></div></div>
          <div class="contact-card" style="border-left-color:var(--accent)"><i class="fa-solid fa-envelope" style="color:var(--accent)"></i><div><h4>Email Us</h4><a href="mailto:info@bluestoneocs.com">info@bluestoneocs.com</a></div></div>
          <div class="contact-card" style="border-left-color:var(--teal)"><i class="fa-regular fa-clock" style="color:var(--teal)"></i><div><h4>Working Hours</h4><p>Mon–Fri: 09:00 AM – 6:30 PM</p></div></div>
          <div class="contact-card" style="border-left-color:var(--secondary)"><i class="fa-brands fa-whatsapp" style="color:#25d366;font-size:1.4rem"></i><div><h4>WhatsApp</h4><a href="https://wa.me/919342899904" target="_blank">Chat with us instantly</a></div></div>
        </div>
        <a href="<?= SITE_MAP_LINK ?>" target="_blank" style="display:block;margin-top:2rem;padding:1.5rem;background:linear-gradient(135deg,rgba(14,165,233,.08),rgba(139,92,246,.08));border-radius:var(--radius);border:1px solid rgba(14,165,233,.15);text-decoration:none;color:inherit;transition:transform 0.3s ease,border-color 0.3s ease;" class="hover-scale-card">
          <h4 style="margin-bottom:1rem;display:flex;align-items:center;gap:0.5rem;"><i class="fa-solid fa-location-dot" style="color:var(--primary)"></i> Head Office – Coimbatore</h4>
          <p style="font-size:.875rem;color:var(--gray);line-height:1.7">Renaissance Terrace, NO.126L, 2nd Floor,<br>Opp. Bishop Appasamy College,<br>Coimbatore, Tamil Nadu - 641018</p>
        </a>

        <!-- Google Maps Embed -->
        <div style="margin-top:1.5rem;border-radius:var(--radius-lg);overflow:hidden;box-shadow:var(--shadow);border:1px solid rgba(0,0,0,0.06);height:280px;position:relative;">
          <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3916.457619491774!2d76.97578759999999!3d11.004251499999999!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3ba8f79532fbd2a7%3A0xecfa1d86f9485eb7!2sBluestone%20Overseas%20Consultants!5e0!3m2!1sen!2sin!4v1779183697198!5m2!1sen!2sin" width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
        </div>
      </div>
      <div class="contact-form-wrap">
        <h3>Send Us a <span class="text-gradient">Message</span></h3>
        <p>Fill in your details and we will get back to you within 24 hours.</p>
        <form id="contactForm" onsubmit="return handleFormSubmit(event)">
          <input type="hidden" name="form_type" value="contact">
          <div class="cf-grid-2">
            <div class="cf-group"><label>First Name *</label><input type="text" name="first_name" placeholder="John" required></div>
            <div class="cf-group"><label>Last Name *</label><input type="text" name="last_name" placeholder="Doe" required></div>
          </div>
          <div class="cf-grid-2">
            <div class="cf-group"><label>Email *</label><input type="email" name="email" placeholder="john@email.com" required></div>
            <div class="cf-group"><label>Phone *</label><input type="tel" name="phone" placeholder="+91 98765 43210" required></div>
          </div>
          <div class="cf-group"><label>Preferred Country</label>
            <select name="destination"><option value="">Select Country</option><option>USA</option><option>UK</option><option>Canada</option><option>Australia</option><option>Germany</option><option>Ireland</option><option>New Zealand</option><option>Singapore</option><option>Other</option></select>
          </div>
          <div class="cf-group"><label>Your Query</label>
            <textarea name="query" rows="4" placeholder="Tell us about your study abroad plans, your academic background, or any specific questions..."></textarea>
          </div>
          <button type="submit" class="btn btn--primary btn--lg" style="width:100%;justify-content:center">
            <i class="fa-solid fa-paper-plane"></i> Send Message
          </button>
        </form>
      </div>
    </div>
  </div>
</section>
<!-- BRANCHES GRID -->
<section class="section" style="background:linear-gradient(135deg,#f0f9ff,#e0f2fe)">
  <div class="container">
    <div class="section__header animate-on-scroll">
      <span class="section__tag">Our Locations</span>
      <h2 class="section__title">Find a Branch <span>Near You</span></h2>
      <div class="accent-bar"></div>
    </div>
    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(280px,1fr));gap:1.5rem">
    <?php
    $branches=[
      ['Coimbatore','fa-building','Renaissance Terrace, NO.126L, 2nd Floor, Opp. Bishop Appasamy College, Coimbatore, TN 641018','var(--primary)'],
      ['Chennai','fa-city','No.13, Velachery Main Road, Mailai Balaji Nagar, Pallikaranai, Chennai - 600100','var(--accent)'],
      ['Salem','fa-location-dot','9.3/14, Vettukadu, Konganapuram PO, Edappadi TK, Salem, TN 637102','var(--secondary)'],
      ['Erode','fa-location-dot','No1, Vairam Street, Municipal Colony, Near Arasan Eye Hospital, Erode - 638004, TN','var(--teal)'],
      ['Namakkal','fa-location-dot','53/17, Second Floor, Paramathi Main Road, S.P. Pudur, Namakkal - 637001','var(--pink)'],
      ['Tirunelveli','fa-location-dot','No.160/5, First Floor, Apollo Pharmacy Upstairs, Thoothukudi Main Road, KTC Nagar, Tirunelveli - 627011','var(--gold)'],
      ['Nepal','fa-globe-asia','MCVG+V9R Hongkong Bazzar, Bharatpur 44207, Nepal','#22c55e'],
      ['Canada','fa-flag','30 Denton Ave Unit No:214, Toronto, ON M1L 4P2, Canada','#3b82f6'],
    ];
    foreach($branches as [$city,$icon,$addr,$color]):
    ?>
    <div class="animate-on-scroll" style="background:#fff;border-radius:var(--radius-lg);padding:1.75rem;box-shadow:var(--shadow);border-top:4px solid <?= $color ?>">
      <div style="display:flex;align-items:center;gap:.75rem;margin-bottom:1rem">
        <div style="width:44px;height:44px;background:linear-gradient(135deg,<?= $color ?>,<?= $color ?>99);border-radius:12px;display:grid;place-items:center;color:#fff">
          <i class="fa-solid <?= $icon ?>"></i>
        </div>
        <h4 style="font-size:1rem;font-weight:700"><?= $city ?></h4>
      </div>
      <?php if ($city === 'Coimbatore'): ?>
        <a href="<?= SITE_MAP_LINK ?>" target="_blank" style="text-decoration:none; color:inherit; display:block;"><p style="font-size:.84rem;color:var(--gray);line-height:1.65;margin-bottom:1rem; transition:color 0.2s ease;" class="hover-red-text"><?= $addr ?></p></a>
      <?php else: ?>
        <p style="font-size:.84rem;color:var(--gray);line-height:1.65;margin-bottom:1rem"><?= $addr ?></p>
      <?php endif; ?>
      <a href="tel:+919342899904" style="font-size:.83rem;color:<?= $color ?>;font-weight:600;display:flex;align-items:center;gap:.35rem"><i class="fa-solid fa-phone"></i> Call Branch</a>
    </div>
    <?php endforeach; ?>
    </div>
  </div>
</section>
</main>
<?php require_once 'includes/footer.php'; ?>
