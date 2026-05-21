<?php
if (!defined('SITE_NAME')) require_once __DIR__ . '/config.php';
$currentPage = basename($_SERVER['PHP_SELF'], '.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title><?= htmlspecialchars($pageTitle ?? SITE_NAME . ' | Study Abroad Consultants') ?></title>
  <meta name="description" content="<?= htmlspecialchars($pageDesc ?? 'Bluestone Overseas Consultancy – trusted study abroad consultants offering expert guidance, university admissions support, and visa services for Indian students.') ?>">
  <meta name="keywords" content="study abroad, overseas consultants, IELTS, TOEFL, visa processing, UK USA Canada Australia Germany">
  <meta property="og:title" content="<?= htmlspecialchars($pageTitle ?? SITE_NAME) ?>">
  <meta property="og:description" content="<?= htmlspecialchars($pageDesc ?? 'Your Gateway to Global Education') ?>">
  <meta property="og:type" content="website">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/lipis/flag-icons@6.14.0/css/flag-icons.min.css">
  <link rel="stylesheet" href="assets/css/main.css?v=<?= filemtime(__DIR__ . '/../assets/css/main.css') ?>">
  <?= $extraCSS ?? '' ?>
</head>
<body>

<!-- Top Bar -->
<div class="topbar">
  <div class="container topbar__inner">
    <div class="topbar__left">
      <span><i class="fa-solid fa-envelope"></i><a href="mailto:<?= SITE_EMAIL ?>"><?= SITE_EMAIL ?></a></span>
      <span><i class="fa-solid fa-phone"></i><a href="tel:<?= SITE_PHONE ?>"><?= SITE_PHONE ?></a></span>
      <span><i class="fa-regular fa-clock"></i><?= SITE_HOURS ?></span>
    </div>
    <div class="topbar__right">
      <a href="<?= SITE_FACEBOOK ?>" target="_blank" aria-label="Facebook"><i class="fa-brands fa-facebook-f"></i></a>
      <a href="<?= SITE_INSTAGRAM ?>" target="_blank" aria-label="Instagram"><i class="fa-brands fa-instagram"></i></a>
      <a href="<?= SITE_YOUTUBE ?>" target="_blank" aria-label="YouTube"><i class="fa-brands fa-youtube"></i></a>
      <a href="<?= SITE_LINKEDIN ?>" target="_blank" aria-label="LinkedIn"><i class="fa-brands fa-linkedin-in"></i></a>
      <a href="https://wa.me/919342899904" target="_blank" class="topbar__wa"><i class="fa-brands fa-whatsapp"></i> WhatsApp</a>
    </div>
  </div>
</div>

<!-- Main Navigation -->
<header class="navbar" id="mainNavbar">
  <div class="container navbar__inner">
    <a href="index.php" class="navbar__logo">
      <img src="assets/images/logo_clean.png" alt="Bluestone Overseas Consultants" class="navbar__logo-img">
    </a>

    <nav class="navbar__menu" id="navMenu">
      <div class="mobile-menu-header">
        <img src="assets/images/logo_clean.png" alt="Bluestone Logo">
        <button id="mobileMenuClose" aria-label="Close menu"><i class="fa-solid fa-xmark"></i></button>
      </div>
      <ul class="nav-list">
        <!-- Study abroad steps -->
        <li class="has-dropdown">
          <a href="services.php">Study abroad steps <i class="fa-solid fa-chevron-down"></i></a>
          <div class="dropdown">
            <a href="guide-me.php" class="dropdown-item">
              <span class="di-icon"><i class="fa-solid fa-route"></i></span>
              <span class="di-text"><strong>Step-by-Step Guide</strong><small>The Student Journey</small></span>
            </a>
            <a href="student-counselling.php" class="dropdown-item">
              <span class="di-icon"><i class="fa-solid fa-question-circle"></i></span>
              <span class="di-text"><strong>Why study abroad?</strong><small>Student Counselling</small></span>
            </a>
            <a href="university-selection.php" class="dropdown-item">
              <span class="di-icon"><i class="fa-solid fa-map-location-dot"></i></span>
              <span class="di-text"><strong>Where and what to study?</strong><small>University Selection</small></span>
            </a>
            <a href="admission-processing.php" class="dropdown-item">
              <span class="di-icon"><i class="fa-solid fa-file-signature"></i></span>
              <span class="di-text"><strong>How do I apply?</strong><small>Admission Processing</small></span>
            </a>
            <a href="visa-processing.php" class="dropdown-item">
              <span class="di-icon"><i class="fa-solid fa-passport"></i></span>
              <span class="di-text"><strong>After receiving an offer</strong><small>Visa Processing</small></span>
            </a>
            <a href="accommodation.php" class="dropdown-item">
              <span class="di-icon"><i class="fa-solid fa-plane-departure"></i></span>
              <span class="di-text"><strong>Prepare to depart</strong><small>Accommodation &amp; Travel</small></span>
            </a>
          </div>
        </li>

        <!-- Study destinations -->
        <li class="has-dropdown <?= isset($isStudyAbroad) && $isStudyAbroad ? 'active' : '' ?>">
          <a href="country.php">Study destinations <i class="fa-solid fa-chevron-down"></i></a>
          <div class="dropdown mega-dropdown mega-countries">
            <p class="mega-label">Popular Destinations</p>
            <div class="countries-grid">
              <?php
              $mainCountries = [
                ['australia','Australia','au','study-in-australia.php'],
                ['canada','Canada','ca','study-in-canada.php'],
                ['uae','UAE','ae','study-in-uae.php'],
                ['germany','Germany','de','study-in-germany.php'],
                ['ireland','Ireland','ie','study-in-ireland.php'],
                ['newzealand','New Zealand','nz','study-in-new-zealand.php'],
                ['singapore','Singapore','sg','study-in-singapore.php'],
                ['switzerland','Switzerland','ch','study-in-switzerland.php'],
                ['uk','United Kingdom','gb','study-in-uk.php'],
                ['usa','United States','us','study-in-usa.php'],
              ];
              foreach ($mainCountries as [$slug, $name, $flagCode, $url]):
              ?>
              <a href="<?= $url ?>" class="country-item">
                <span class="country-flag fi fi-<?= $flagCode ?>"></span>
                <span><?= $name ?></span>
              </a>
              <?php endforeach; ?>
            </div>
            
            <!-- Other Destinations -->
            <p class="mega-label" style="margin-top: 0.5px;">Other Destinations</p>
            <div class="countries-grid countries-grid-secondary">
              <?php
              $otherCountries = [
                ['italy','Italy','it','study-in-italy.php'],
                ['france','France','fr','study-in-france.php'],
                ['netherlands','Netherlands','nl','study-in-netherlands.php'],
                ['sweden','Sweden','se','study-in-sweden.php'],
                ['spain','Spain','es','study-in-spain.php'],
                ['austria','Austria','at','study-in-austria.php'],
                ['denmark','Denmark','dk','study-in-denmark.php'],
                ['finland','Finland','fi','study-in-finland.php'],
                ['hungary','Hungary','hu','study-in-hungary.php'],
                ['poland','Poland','pl','study-in-poland.php'],
                ['czech-republic','Czech Republic','cz','study-in-czech-republic.php'],
                ['malaysia','Malaysia','my','study-in-malaysia.php'],
                ['japan','Japan','jp','study-in-japan.php'],
                ['china','China','cn','study-in-china.php'],
                ['belgium','Belgium','be','study-in-belgium.php'],
                ['south-korea','South Korea','kr','study-in-south-korea.php'],
              ];
              foreach ($otherCountries as [$slug, $name, $flagCode, $url]):
              ?>
              <a href="<?= $url ?>" class="country-item country-item-secondary">
                <span class="country-flag fi fi-<?= $flagCode ?>"></span>
                <span><?= $name ?></span>
              </a>
              <?php endforeach; ?>
            </div>
          </div>
        </li>

        <!-- Find a course -->
        <li class="has-dropdown">
          <a href="#">Find a course <i class="fa-solid fa-chevron-down"></i></a>
          <div class="dropdown">
            <a href="courses.php" class="dropdown-item">
              <span class="di-icon"><i class="fa-solid fa-book-open"></i></span>
              <span class="di-text"><strong>Course advice</strong><small>Explore subjects</small></span>
            </a>
            <a href="universities.php" class="dropdown-item">
              <span class="di-icon"><i class="fa-solid fa-building-columns"></i></span>
              <span class="di-text"><strong>Find a university</strong><small>Explore institutions</small></span>
            </a>
            <a href="scholarships.php" class="dropdown-item">
              <span class="di-icon"><i class="fa-solid fa-graduation-cap"></i></span>
              <span class="di-text"><strong>Find a scholarship</strong><small>Funding options</small></span>
            </a>
          </div>
        </li>

        <!-- Test Prep -->
        <li class="has-dropdown">
          <a href="#">Test Prep <i class="fa-solid fa-chevron-down"></i></a>
          <div class="dropdown">
            <a href="ielts.php" class="dropdown-item">
              <span class="di-icon"><i class="fa-solid fa-pen-to-square"></i></span>
              <span class="di-text"><strong>IELTS</strong><small>International English Testing</small></span>
            </a>
            <a href="toefl.php" class="dropdown-item">
              <span class="di-icon"><i class="fa-solid fa-pen-to-square"></i></span>
              <span class="di-text"><strong>TOEFL</strong><small>Test of English</small></span>
            </a>
            <a href="pte.php" class="dropdown-item">
              <span class="di-icon"><i class="fa-solid fa-pen-to-square"></i></span>
              <span class="di-text"><strong>PTE</strong><small>Pearson Test of English</small></span>
            </a>
          </div>
        </li>

        <!-- Student Essentials -->
        <li class="has-dropdown <?= in_array($currentPage, ['education-loan','accommodation','part-time-jobs','health-insurance','money-transfer','bank-account','sim-card']) ? 'active' : '' ?>">
          <a href="#">Student Essentials <i class="fa-solid fa-chevron-down"></i></a>
          <div class="dropdown mega-dropdown">
            <div class="dropdown-grid">
              <a href="education-loan.php" class="dropdown-item">
                <span class="di-icon"><i class="fa-solid fa-hand-holding-dollar"></i></span>
                <span class="di-text"><strong>Education loan</strong><small>Financial support</small></span>
              </a>
              <a href="accommodation.php" class="dropdown-item">
                <span class="di-icon"><i class="fa-solid fa-house"></i></span>
                <span class="di-text"><strong>Accommodation</strong><small>Find a place to stay</small></span>
              </a>
              <a href="health-insurance.php" class="dropdown-item">
                <span class="di-icon"><i class="fa-solid fa-shield-heart"></i></span>
                <span class="di-text"><strong>Health Insurance</strong><small>OSHC &amp; Travel Cover</small></span>
              </a>
              <a href="money-transfer.php" class="dropdown-item">
                <span class="di-icon"><i class="fa-solid fa-money-bill-transfer"></i></span>
                <span class="di-text"><strong>Money Transfer</strong><small>Forex &amp; Fee Payments</small></span>
              </a>
              <a href="bank-account.php" class="dropdown-item">
                <span class="di-icon"><i class="fa-solid fa-building-columns"></i></span>
                <span class="di-text"><strong>Bank Account</strong><small>Pre-arrival Opening</small></span>
              </a>
              <a href="sim-card.php" class="dropdown-item">
                <span class="di-icon"><i class="fa-solid fa-mobile-screen-button"></i></span>
                <span class="di-text"><strong>SIM Card</strong><small>Stay Connected</small></span>
              </a>
            </div>
          </div>
        </li>

        <!-- About Us -->
        <li class="has-dropdown <?= in_array($currentPage, ['About_us','Award_Achievements','events','Blog','gallery','contact','guide-me']) ? 'active' : '' ?>">
          <a href="About_us.php">About Us <i class="fa-solid fa-chevron-down"></i></a>
          <div class="dropdown mega-dropdown">
            <div class="dropdown-grid">
              <a href="About_us.php" class="dropdown-item">
                <span class="di-icon"><i class="fa-solid fa-building"></i></span>
                <span class="di-text"><strong>Our Profile</strong><small>Who we are</small></span>
              </a>
              <a href="events.php" class="dropdown-item">
                <span class="di-icon"><i class="fa-solid fa-calendar-check"></i></span>
                <span class="di-text"><strong>Events</strong><small>Join our fairs &amp; seminars</small></span>
              </a>
              <a href="Blog.php" class="dropdown-item">
                <span class="di-icon"><i class="fa-solid fa-newspaper"></i></span>
                <span class="di-text"><strong>News and articles</strong><small>Stay updated</small></span>
              </a>
              <a href="branch.php" class="dropdown-item">
                <span class="di-icon"><i class="fa-solid fa-location-dot"></i></span>
                <span class="di-text"><strong>Find nearest offices</strong><small>Our branches</small></span>
              </a>
              <a href="contact.php" class="dropdown-item">
                <span class="di-icon"><i class="fa-solid fa-address-book"></i></span>
                <span class="di-text"><strong>Contact Us</strong><small>Get in touch</small></span>
              </a>
            </div>
          </div>
        </li>
      </ul>
      <div class="mobile-menu-footer">
        <a href="consultation.php" class="btn btn--primary btn--block"><i class="fa-solid fa-calendar-check"></i> Book Free Consultation</a>
        <div class="mobile-menu-social">
          <a href="<?= SITE_FACEBOOK ?>" target="_blank" aria-label="Facebook"><i class="fa-brands fa-facebook-f"></i></a>
          <a href="<?= SITE_INSTAGRAM ?>" target="_blank" aria-label="Instagram"><i class="fa-brands fa-instagram"></i></a>
          <a href="<?= SITE_YOUTUBE ?>" target="_blank" aria-label="YouTube"><i class="fa-brands fa-youtube"></i></a>
          <a href="<?= SITE_LINKEDIN ?>" target="_blank" aria-label="LinkedIn"><i class="fa-brands fa-linkedin-in"></i></a>
          <a href="https://wa.me/919342899904" target="_blank" aria-label="WhatsApp"><i class="fa-brands fa-whatsapp"></i></a>
        </div>
      </div>
    </nav>

    <div class="navbar__actions">
      <a href="consultation.php" class="btn btn--primary btn--sm pulse-btn" id="consultBtn" style="white-space: nowrap;">
        <i class="fa-solid fa-calendar-check"></i> Book Consultation
      </a>
      <button class="hamburger" id="hamburger" aria-label="Open menu" aria-expanded="false">
        <span></span><span></span><span></span>
      </button>
    </div>
  </div>
</header>
