<?php if (!defined('SITE_NAME')) require_once __DIR__ . '/config.php'; ?>
<!-- Footer -->
<<style>
.footer-redesign {
    background-color: #e6edf7;
    color: #475569;
    padding: 4.5rem 0 0 0;
    font-family: 'Inter', sans-serif;
}
.footer-redesign a {
    color: #64748b;
    text-decoration: none;
    transition: color 0.3s;
}
.footer-redesign a:hover {
    color: #0ea5e9;
}
.footer-redesign-grid {
    display: grid;
    grid-template-columns: 2fr 1.2fr 1.3fr 1fr;
    gap: 2rem;
    margin-bottom: 3.5rem;
}
.footer-redesign h4 {
    color: #1e293b;
    font-size: 1.15rem;
    font-weight: 700;
    margin-bottom: 1.8rem;
    position: relative;
    display: inline-block;
}
.footer-redesign h4::after {
    content: '';
    position: absolute;
    bottom: -8px;
    left: 0;
    width: 25px;
    height: 2px;
    background-color: #f43f5e;
}
.footer-brand p {
    font-size: 0.9rem;
    line-height: 1.7;
    margin-bottom: 1.8rem;
}
.footer-social-icons {
    display: flex;
    gap: 0.75rem;
}
.footer-social-icons a {
    width: 36px;
    height: 36px;
    border-radius: 6px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.1rem;
    transition: transform 0.3s;
}
.footer-social-icons a:hover { transform: translateY(-3px); }
.fs-fb { background: #dbeafe; color: #3b82f6 !important; }
.fs-ig { background: #fce7f3; color: #db2777 !important; }
.fs-yt { background: #fee2e2; color: #ef4444 !important; }
.fs-li { background: #dbeafe; color: #0284c7 !important; }
.fs-wa { background: #dcfce7; color: #16a34a !important; }

.footer-links-list {
    list-style: none;
    padding: 0;
    margin: 0;
}
.footer-links-list li {
    margin-bottom: 0.9rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 0.92rem;
}
.footer-links-list i {
    color: #f43f5e;
    font-size: 0.75rem;
}
.footer-address-box {
    background: #ffffff;
    border-radius: 12px;
    padding: 1.25rem;
    display: flex;
    gap: 1rem;
    box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.05);
    margin-bottom: 1.5rem;
}
.footer-address-icon {
    width: 38px;
    height: 38px;
    background: linear-gradient(135deg, #a855f7, #6366f1);
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.1rem;
    flex-shrink: 0;
}
.footer-address-text h5 {
    color: #1e293b;
    font-size: 0.9rem;
    font-weight: 700;
    margin: 0 0 0.3rem 0;
}
.footer-address-text p {
    font-size: 0.82rem;
    margin: 0;
    line-height: 1.6;
    color: #64748b;
}
.footer-contact-items {
    display: flex;
    flex-direction: column;
    gap: 0.85rem;
    font-size: 0.85rem;
    color: #64748b;
}
.footer-contact-items .fc-item {
    display: flex;
    align-items: center;
    gap: 0.75rem;
}
.footer-contact-items i {
    color: #f43f5e;
    width: 16px;
    font-size: 1rem;
}
.footer-redesign-bottom {
    border-top: 1px solid rgba(0,0,0,0.06);
    padding: 1.5rem 0;
    font-size: 0.85rem;
}
.footer-bottom-flex {
    display: flex;
    justify-content: space-between;
    align-items: center;
}
.footer-bottom-links a {
    margin: 0 0.75rem;
}
@media (max-width: 1200px) {
    .footer-redesign-grid { grid-template-columns: repeat(3, 1fr); gap: 3rem 2rem; }
}
@media (max-width: 768px) {
    .footer-redesign-grid { grid-template-columns: repeat(2, 1fr); }
    .footer-bottom-flex { flex-direction: column; gap: 1rem; text-align: center; }
}
@media (max-width: 480px) {
    .footer-redesign-grid { grid-template-columns: 1fr; }
}
</style>

<footer class="footer-redesign">
  <div class="container">
    <div class="footer-redesign-grid animate-on-scroll">
      
      <!-- Brand Column -->
      <div class="footer-brand">
        <a href="index.php" style="display: block; margin-bottom: 0;">
          <img src="assets/images/Logo_old.png" alt="Bluestone Overseas Consultants" style="width: 250px; height: auto; margin-top: -60px; margin-bottom: -45px; margin-left: -15px;">
        </a>
        
        <div class="footer-address-box" style="margin-bottom: 2rem; padding: 0; background: transparent; box-shadow: none; align-items: flex-start; gap: 1rem;">
          <div class="footer-address-icon" style="border-radius: 10px; width: 45px; height: 45px; font-size: 1.25rem;"><i class="fa-solid fa-location-dot"></i></div>
          <div class="footer-address-text" style="padding-top: 4px;">
            <h5 style="font-size: 1.05rem; margin-bottom: 0.5rem;">Head Office (Coimbatore)</h5>
            <p style="font-size: 0.95rem; line-height: 1.6;">Renaissance Terrace, NO.126L, 2nd Floor, Opp.<br>Bishop Appasamy College, TN - 641018</p>
          </div>
        </div>
        
        <div class="footer-contact-items" style="margin-bottom: 2rem; padding-left: 0;">
          <div class="fc-item" style="font-size: 0.95rem; margin-bottom: 0.4rem;"><i class="fa-solid fa-phone" style="width: 25px; font-size: 1.1rem; text-align: center;"></i> +91 93428 99904</div>
          <div class="fc-item" style="font-size: 0.95rem; margin-bottom: 0.4rem;"><i class="fa-solid fa-envelope" style="width: 25px; font-size: 1.1rem; text-align: center;"></i> info@bluestoneocs.com</div>
          <div class="fc-item" style="font-size: 0.95rem;"><i class="fa-regular fa-clock" style="width: 25px; font-size: 1.1rem; text-align: center;"></i> <?= SITE_HOURS ?? 'Mon-Fri: 09:30 AM - 06:00 PM' ?></div>
        </div>

        <div class="footer-social-icons">
          <a href="<?= SITE_FACEBOOK ?>" target="_blank" aria-label="Facebook" class="fs-fb"><i class="fa-brands fa-facebook-f"></i></a>
          <a href="<?= SITE_INSTAGRAM ?>" target="_blank" aria-label="Instagram" class="fs-ig"><i class="fa-brands fa-instagram"></i></a>
          <a href="<?= SITE_YOUTUBE ?>" target="_blank" aria-label="YouTube" class="fs-yt"><i class="fa-brands fa-youtube"></i></a>
          <a href="<?= SITE_LINKEDIN ?>" target="_blank" aria-label="LinkedIn" class="fs-li"><i class="fa-brands fa-linkedin-in"></i></a>
          <a href="https://wa.me/919342899904" target="_blank" aria-label="WhatsApp" class="fs-wa"><i class="fa-brands fa-whatsapp"></i></a>
        </div>
      </div>

      <!-- Services -->
      <div>
        <h4>Our Services</h4>
        <ul class="footer-links-list">
          <li><i class="fa-solid fa-angle-right"></i> <a href="services.php?s=counselling">Student Counselling</a></li>
          <li><i class="fa-solid fa-angle-right"></i> <a href="services.php?s=university">Course Selection</a></li>
          <li><i class="fa-solid fa-angle-right"></i> <a href="services.php?s=admission">Admission Processing</a></li>
          <li><i class="fa-solid fa-angle-right"></i> <a href="services.php?s=financial">Financial Assistance</a></li>
          <li><i class="fa-solid fa-angle-right"></i> <a href="services.php?s=visa">Visa Processing</a></li>
          <li><i class="fa-solid fa-angle-right"></i> <a href="services.php?s=accommodation">Travel &amp; Housing</a></li>
          <li><i class="fa-solid fa-angle-right"></i> <a href="services.php?s=jobs">Part-Time Jobs</a></li>
        </ul>
      </div>

      <!-- Essentials -->
      <div>
        <h4>Student Essentials</h4>
        <ul class="footer-links-list">
          <li><i class="fa-solid fa-angle-right"></i> <a href="education-loan.php">Education Loan</a></li>
          <li><i class="fa-solid fa-angle-right"></i> <a href="accommodation.php">Accommodation</a></li>
          <li><i class="fa-solid fa-angle-right"></i> <a href="health-insurance.php">Health Insurance</a></li>
          <li><i class="fa-solid fa-angle-right"></i> <a href="money-transfer.php">Money Transfer</a></li>
          <li><i class="fa-solid fa-angle-right"></i> <a href="bank-account.php">Bank Account</a></li>
          <li><i class="fa-solid fa-angle-right"></i> <a href="sim-card.php">International SIM</a></li>
          <li><i class="fa-solid fa-angle-right"></i> <a href="part-time-jobs.php">Part-Time Jobs</a></li>
        </ul>
      </div>

      <!-- Branches -->
      <div>
        <h4>Our Branches</h4>
        <ul class="footer-links-list">
          <li><i class="fa-solid fa-circle-dot" style="font-size: 0.45rem;"></i> <a href="https://www.google.com/search?q=bluestone+overseas+canada" target="_blank">Canada</a></li>
          <li><i class="fa-solid fa-circle-dot" style="font-size: 0.45rem;"></i> <a href="https://www.google.com/search?q=bluestone+overseas+nepal" target="_blank">Nepal</a></li>
          <li><i class="fa-solid fa-circle-dot" style="font-size: 0.45rem;"></i> <a href="https://www.google.com/search?q=bluestone+overseas+coimbatore" target="_blank">Coimbatore</a></li>
          <li><i class="fa-solid fa-circle-dot" style="font-size: 0.45rem;"></i> <a href="https://www.google.com/search?q=bluestone+overseas+chennai" target="_blank">Chennai</a></li>
          <li><i class="fa-solid fa-circle-dot" style="font-size: 0.45rem;"></i> <a href="https://www.google.com/search?q=bluestone+overseas+salem" target="_blank">Salem</a></li>
          <li><i class="fa-solid fa-circle-dot" style="font-size: 0.45rem;"></i> <a href="https://www.google.com/search?q=bluestone+overseas+erode" target="_blank">Erode</a></li>
          <li><i class="fa-solid fa-circle-dot" style="font-size: 0.45rem;"></i> <a href="https://www.google.com/search?q=bluestone+overseas+namakkal" target="_blank">Namakkal</a></li>
          <li><i class="fa-solid fa-circle-dot" style="font-size: 0.45rem;"></i> <a href="https://www.google.com/search?q=bluestone+overseas+tirunelveli" target="_blank">Tirunelveli</a></li>
        </ul>
      </div>



    </div>
  </div>

  <div class="footer-redesign-bottom">
    <div class="container footer-bottom-flex">
      <div>
        &copy; <?= YEAR ?> <strong><?= SITE_NAME ?></strong>. All rights reserved.<br>
        <span style="color: #64748b; font-size: 0.8rem; margin-top: 5px; display: inline-block;">Crafted by <a href="https://bluestonetechpark.com" target="_blank" style="color: #6366f1;">Bluestone Techpark</a></span>
      </div>
      <div class="footer-bottom-links">
        <a href="privacy-policy.php">Privacy Policy</a>
        <a href="terms-and-conditions.php">Terms &amp; Conditions</a>
        <a href="sitemap.php">Sitemap</a>
      </div>
      <div>
        Powered by <a href="https://bluestonegroupofinstitutions.com" target="_blank" style="color: #ef4444;">Bluestone Group of Institutions</a>
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

<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script>
  if (typeof AOS !== 'undefined') {
    AOS.init({
      once: true,
      duration: 800,
      offset: 100
    });
  }
</script>
<script src="assets/js/main.js?v=<?= filemtime(__DIR__ . '/../assets/js/main.js') ?>"></script>
<?= $extraJS ?? '' ?>
</body>
</html>
