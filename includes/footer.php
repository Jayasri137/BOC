<?php if (!defined('SITE_NAME')) require_once __DIR__ . '/config.php'; ?>
<!-- Footer -->
<footer class="footer">


  <div class="footer__top">
    <div class="container footer__grid animate-on-scroll">

      <!-- Brand Column -->
      <div class="footer-col footer-brand">
        <a href="index.php" class="footer-logo">
          <!-- Premium transparent bird logo -->
          <img src="assets/images/logo.png" alt="Bluestone Overseas Consultants" style="height: 82px; width: auto; object-fit: contain; filter: drop-shadow(0 2px 6px rgba(0, 0, 0, 0.06));">
        </a>
        <p>The most eminent Visa and Immigration Consultant service provider in major metros and overseas. Empowering global educational dreams with absolute reliability and trust since 2015.</p>
        <div class="footer-social">
          <a href="<?= SITE_FACEBOOK ?>" target="_blank" aria-label="Facebook"><i class="fa-brands fa-facebook-f"></i></a>
          <a href="<?= SITE_INSTAGRAM ?>" target="_blank" aria-label="Instagram"><i class="fa-brands fa-instagram"></i></a>
          <a href="<?= SITE_YOUTUBE ?>" target="_blank" aria-label="YouTube"><i class="fa-brands fa-youtube"></i></a>
          <a href="<?= SITE_LINKEDIN ?>" target="_blank" aria-label="LinkedIn"><i class="fa-brands fa-linkedin-in"></i></a>
          <a href="https://wa.me/919342899904" target="_blank" aria-label="WhatsApp"><i class="fa-brands fa-whatsapp"></i></a>
        </div>
      </div>

      <!-- Services Column -->
      <div class="footer-col">
        <h4 class="footer-heading">Our Services</h4>
        <ul class="footer-links">
          <li><a href="services.php?s=counselling"><i class="fa-solid fa-chevron-right"></i> Student Counselling</a></li>
          <li><a href="services.php?s=university"><i class="fa-solid fa-chevron-right"></i> Course Selection</a></li>
          <li><a href="services.php?s=admission"><i class="fa-solid fa-chevron-right"></i> Admission Processing</a></li>
          <li><a href="services.php?s=financial"><i class="fa-solid fa-chevron-right"></i> Financial Assistance</a></li>
          <li><a href="services.php?s=visa"><i class="fa-solid fa-chevron-right"></i> Visa Processing</a></li>
          <li><a href="services.php?s=accommodation"><i class="fa-solid fa-chevron-right"></i> Travel &amp; Housing</a></li>
          <li><a href="services.php?s=jobs"><i class="fa-solid fa-chevron-right"></i> Part-Time Jobs</a></li>
        </ul>
      </div>

      <!-- Essentials Column -->
      <div class="footer-col">
        <h4 class="footer-heading">Student Essentials</h4>
        <ul class="footer-links">
          <li><a href="education-loan.php"><i class="fa-solid fa-chevron-right"></i> Education Loan</a></li>
          <li><a href="accommodation.php"><i class="fa-solid fa-chevron-right"></i> Accommodation</a></li>
          <li><a href="health-insurance.php"><i class="fa-solid fa-chevron-right"></i> Health Insurance</a></li>
          <li><a href="money-transfer.php"><i class="fa-solid fa-chevron-right"></i> Money Transfer</a></li>
          <li><a href="bank-account.php"><i class="fa-solid fa-chevron-right"></i> Bank Account</a></li>
          <li><a href="sim-card.php"><i class="fa-solid fa-chevron-right"></i> International SIM</a></li>
          <li><a href="part-time-jobs.php"><i class="fa-solid fa-chevron-right"></i> Part-Time Jobs</a></li>
        </ul>
      </div>

      <!-- Contact Us Column -->
      <div class="footer-col footer-contact-col">
        <h4 class="footer-heading">Contact Us</h4>
        
        <!-- Premium interactive address card linking to Google Maps live location -->
        <a href="<?= SITE_MAP_LINK ?>" target="_blank" class="footer-map-card">
          <div class="fmc-icon"><i class="fa-solid fa-location-dot"></i></div>
          <div class="fmc-info">
            <strong>Head Office (Coimbatore)</strong>
            <span>Renaissance Terrace, NO.126L, 2nd Floor, Opp. Bishop Appasamy College, TN - 641018</span>
          </div>
        </a>

        <div class="footer-contact-info">
          <a href="tel:+919342899904" class="fc-link"><i class="fa-solid fa-phone"></i> +91 93428 99904</a>
          <a href="mailto:info@bluestoneocs.com" class="fc-link"><i class="fa-solid fa-envelope"></i> info@bluestoneocs.com</a>
          <div class="fc-time"><i class="fa-regular fa-clock"></i> Mon–Fri: 09:00 AM – 06:30 PM</div>
        </div>
      </div>

    </div>
  </div>

  <!-- Horizontal Branches Row -->
  <div class="footer-branches-row">
    <div class="container">
      <div class="fbr-inner">
        <span class="fbr-title">Our Global Branches:</span>
        <div class="fbr-list">
          <span class="fbr-item"><i class="fa-solid fa-circle-dot"></i> Coimbatore</span>
          <span class="fbr-item"><i class="fa-solid fa-circle-dot"></i> Chennai</span>
          <span class="fbr-item"><i class="fa-solid fa-circle-dot"></i> Salem</span>
          <span class="fbr-item"><i class="fa-solid fa-circle-dot"></i> Erode</span>
          <span class="fbr-item"><i class="fa-solid fa-circle-dot"></i> Namakkal</span>
          <span class="fbr-item"><i class="fa-solid fa-circle-dot"></i> Tirunelveli</span>
          <span class="fbr-item"><i class="fa-solid fa-circle-dot"></i> Nepal</span>
          <span class="fbr-item"><i class="fa-solid fa-circle-dot"></i> Canada</span>
        </div>
      </div>
    </div>
  </div>

  <!-- Footer Bottom -->
  <div class="footer__bottom">
    <div class="container footer-bottom-inner">
      <div class="fb-left">
        <p>&copy; <?= YEAR ?> <strong><?= SITE_NAME ?></strong>. All rights reserved.</p>
        <p style="margin-top: 0.25rem;">Crafted by <a href="https://bluestonetechpark.com" target="_blank" class="credit-link techpark">Bluestone Techpark</a></p>
      </div>
      <div class="footer-bottom-links">
        <a href="privacy.php">Privacy Policy</a>
        <a href="terms.php">Terms &amp; Conditions</a>
        <a href="sitemap.php">Sitemap</a>
      </div>
      <div class="fb-right">
        <p>Powered by <a href="https://bluestonegroupofinstitutions.com" target="_blank" class="credit-link group">Bluestone Group of Institutions</a></p>
      </div>
    </div>
  </div>
</footer>

<!-- WhatsApp Floating Button -->
<a href="https://wa.me/919342899904" class="wa-float" target="_blank" aria-label="Chat on WhatsApp">
  <i class="fa-brands fa-whatsapp"></i>
  <span class="wa-tooltip">Chat with us!</span>
</a>

<!-- Back to Top -->
<button class="back-to-top" id="backToTop" aria-label="Back to top">
  <i class="fa-solid fa-chevron-up"></i>
</button>

<!-- Overlay for mobile menu -->
<div class="nav-overlay" id="navOverlay"></div>

<script src="assets/js/main.js?v=<?= filemtime(__DIR__ . '/../assets/js/main.js') ?>"></script>
<?= $extraJS ?? '' ?>
</body>
</html>
