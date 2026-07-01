<?php
if (!defined('SITE_NAME')) require_once __DIR__ . '/config.php';
$currentPage = basename($_SERVER['PHP_SELF'], '.php');
$script_name = basename($_SERVER['SCRIPT_NAME']);

// Branches redirect
if (preg_match('/^(coimbatore|chennai|salem|erode|namakkal|nepal|canada-branch|canada_branch|tirunelveli|thirunelveli)\.php$/i', $script_name)) {
    header("Location: branch.php", true, 301);
    exit();
}

// Special case countries
if (strcasecmp($script_name, 'swedan.php') === 0) {
    header("Location: study-in-sweden.php", true, 301);
    exit();
}
if (strcasecmp($script_name, 'newzeland.php') === 0) {
    header("Location: study-in-new-zealand.php", true, 301);
    exit();
}
if (strcasecmp($script_name, 'philipines.php') === 0) {
    header("Location: study-in-philippines.php", true, 301);
    exit();
}

// Countries redirect
if (preg_match('/^(usa|uk|canada|australia|ireland|germany|france|italy|singapore|malaysia|denmark|bulgaria|russia|switzerland|south-korea|netherlands|uae)\.php$/i', $script_name, $matches)) {
    $country = strtolower($matches[1]);
    header("Location: study-in-{$country}.php", true, 301);
    exit();
}

// Test preps redirect
if (preg_match('/^(ielts-test|ielts_test)\.php$/i', $script_name)) {
    header("Location: ielts.php", true, 301);
    exit();
}
if (strcasecmp($script_name, 'toefl.php') === 0 && $script_name !== 'toefl.php') {
    header("Location: toefl.php", true, 301);
    exit();
}
if (strcasecmp($script_name, 'pte.php') === 0 && $script_name !== 'pte.php') {
    header("Location: pte.php", true, 301);
    exit();
}

// Legacy page redirects
if (strcasecmp($script_name, 'privacy.php') === 0) {
    header("Location: privacy-policy.php", true, 301);
    exit();
}
if (strcasecmp($script_name, 'terms.php') === 0) {
    header("Location: terms-and-conditions.php", true, 301);
    exit();
}
if (strcasecmp($script_name, 'about-us.php') === 0 || strcasecmp($script_name, 'award-achievements.php') === 0) {
    header("Location: About_us.php", true, 301);
    exit();
}
if (strcasecmp($script_name, 'contact-us.php') === 0) {
    header("Location: contact.php", true, 301);
    exit();
}
if (strcasecmp($script_name, 'services-1.php') === 0) {
    header("Location: services.php", true, 301);
    exit();
}
if (strcasecmp($script_name, 'event-mod.php') === 0) {
    header("Location: events.php", true, 301);
    exit();
}

// Blogs redirect
if (preg_match('/^(ourblog|new_blog|myblog)\d*\.php$/i', $script_name)) {
    header("Location: Blog.php", true, 301);
    exit();
}

// Canonical URL calculation
$canonical_path = rtrim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');

// Strip local development folder from canonical path
$canonical_path = preg_replace('#^/bluestone(?:%20|\s|_|-)*overseas#i', '', $canonical_path);

if (strtolower($canonical_path) === '/index.php') {
    $canonical_path = '';
}
$canonical_url = 'https://www.bluestoneoverseas.com' . $canonical_path;
if ($canonical_url === 'https://www.bluestoneoverseas.com') {
    $canonical_url .= '/';
}

// Dynamic SEO Fallback for pages that don't explicitly set them
if (!isset($pageTitle) || empty(trim($pageTitle))) {
    $generatedTitle = ucwords(str_replace(['-', '_'], ' ', $currentPage));
    $pageTitle = $generatedTitle . ' | ' . SITE_NAME;
}
if (!isset($pageDesc) || empty(trim($pageDesc))) {
    $generatedTitle = ucwords(str_replace(['-', '_'], ' ', $currentPage ?? ''));
    $pageDesc = "Learn more about {$generatedTitle} at " . SITE_NAME . ". Trusted study abroad consultants offering expert guidance, university admissions support, and visa services.";
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <!-- Google Analytics GA4 & Google Ads gtag -->
  <script async src="https://www.googletagmanager.com/gtag/js?id=G-29NKH1H779"></script>
  <script>
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    gtag('js', new Date());

    /* Google Analytics GA4 */
    gtag('config', 'G-29NKH1H779');

    /* Google Ads */
    gtag('config', 'AW-17065954362');
    gtag('config', 'AW-16603743701');
  </script>

  <!-- Google tag (gtag.js) for EO purpose -->
  <script async src="https://www.googletagmanager.com/gtag/js?id=G-PZF0HBPB7K"></script>
  <script>
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    gtag('js', new Date());

    gtag('config', 'G-PZF0HBPB7K');
  </script>

  <!-- Facebook Pixel Code -->
  <?php
  $fbPixelId = defined('SITE_FACEBOOK_PIXEL_ID') ? SITE_FACEBOOK_PIXEL_ID : 'YOUR_PIXEL_ID';
  ?>
  <script>
    !function(f,b,e,v,n,t,s)
    {if(f.fbq)return;n=f.fbq=function(){n.callMethod?
    n.callMethod.apply(n,arguments):n.queue.push(arguments)};
    if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
    n.queue=[];t=b.createElement(e);t.async=!0;
    t.src=v;s=b.getElementsByTagName(e)[0];
    s.parentNode.insertBefore(t,s)}(window, document,'script',
    'https://connect.facebook.net/en_US/fbevents.js');
    fbq('init', '<?= $fbPixelId ?>');
    fbq('track', 'PageView');
  </script>
  <noscript>
    <img height="1" width="1" style="display:none"
    src="https://www.facebook.com/tr?id=<?= $fbPixelId ?>&ev=PageView&noscript=1"/>
  </noscript>
  <!-- End Facebook Pixel Code -->

  <!-- Meta Configuration -->
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="author" content="Bluestone Overseas Consultants - Your Companion in Worldwide Education">
  <meta name="language" content="English">
  <meta name="distribution" content="global">
  <meta name="revisit-after" content="7 days">
  <meta name="robots" content="index, follow">

  <!-- Google Site Verification -->
  <meta name="google-site-verification" content="6XJeYJRBhkAMZfAqMRM7baFwTbZA54aaIcRd0WjBvYU">
  <meta name="google-site-verification" content="uufHadHWr1VYTfMfUrK7gzYjZ31PS6C9M1ZcJHA5Au4">
  <meta name="google-site-verification" content="XnLIPxSb1zS2cMqVAY2vq9EDrDmyJ7dPKCELSHbh0c8" />

  <!-- Bing Site Verification -->
  <meta name="msvalidate.01" content="52C70BEE0123E3EB38EDEDF48C48849D" />

  <!-- Canonical & Verification -->
  <link rel="canonical" href="<?= htmlspecialchars($canonical_url, ENT_QUOTES, 'UTF-8') ?>" />

  <!-- Primary SEO -->
  <title><?= htmlspecialchars($pageTitle) ?></title>
  <meta name="description" content="<?= htmlspecialchars($pageDesc) ?>">

  <!-- Open Graph / Facebook -->
  <meta property="og:title" content="<?= htmlspecialchars($pageTitle ?? SITE_NAME) ?>">
  <meta property="og:description" content="<?= htmlspecialchars($pageDesc ?? 'Your Gateway to Global Education') ?>">
  <meta property="og:url" content="<?= htmlspecialchars($canonical_url, ENT_QUOTES, 'UTF-8') ?>" />
  <meta property="og:type" content="website">
  <meta property="og:image" content="https://www.bluestoneoverseas.com/assets/images/Logo_old.png" />
  <meta property="og:site_name" content="<?= htmlspecialchars(SITE_NAME) ?>" />
  <meta property="og:locale" content="en_US" />

  <!-- Twitter -->
  <meta name="twitter:card" content="summary_large_image" />
  <meta name="twitter:title" content="<?= htmlspecialchars($pageTitle ?? SITE_NAME) ?>" />
  <meta name="twitter:description" content="<?= htmlspecialchars($pageDesc ?? 'Your Gateway to Global Education') ?>" />
  <meta name="twitter:image" content="https://www.bluestoneoverseas.com/assets/images/Logo_old.png" />

  <!-- Favicon -->
  <link rel="shortcut icon" href="assets/images/favicon.png" type="image/x-icon">

  <!-- Fonts & Icons Preconnect -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/lipis/flag-icons@6.14.0/css/flag-icons.min.css">

  <link rel="stylesheet" href="assets/css/main.css?v=<?= filemtime(__DIR__ . '/../assets/css/main.css') ?>">
  <?= $extraCSS ?? '' ?>

  <!-- JSON-LD Structured Data Schema -->
  <script type="application/ld+json">
  {
    "@context": "https://schema.org",
    "@type": ["EducationalOrganization", "LocalBusiness"],
    "name": "<?= htmlspecialchars(SITE_NAME, ENT_QUOTES, 'UTF-8') ?>",
    "url": "https://www.bluestoneoverseas.com",
    "logo": "https://www.bluestoneoverseas.com/assets/images/Logo_old.png",
    "sameAs": [
      "<?= htmlspecialchars(SITE_FACEBOOK, ENT_QUOTES, 'UTF-8') ?>",
      "<?= htmlspecialchars(SITE_INSTAGRAM, ENT_QUOTES, 'UTF-8') ?>",
      "<?= htmlspecialchars(SITE_LINKEDIN, ENT_QUOTES, 'UTF-8') ?>",
      "<?= htmlspecialchars(SITE_YOUTUBE, ENT_QUOTES, 'UTF-8') ?>"
    ],
    "description": "Study abroad consultants offering student visa services, IELTS/TOEFL coaching, personalized student counseling, and global education guidance.",
    "address": {
      "@type": "PostalAddress",
      "streetAddress": "Renaissance Terrace, NO.126L, 2nd Floor, Opp. Bishop Appasamy College",
      "addressLocality": "Coimbatore",
      "addressRegion": "Tamil Nadu",
      "postalCode": "641018",
      "addressCountry": "IN"
    },
    "contactPoint": {
      "@type": "ContactPoint",
      "telephone": "<?= htmlspecialchars(SITE_PHONE, ENT_QUOTES, 'UTF-8') ?>",
      "contactType": "Customer Support"
    },
    "openingHours": "Mo-Fr 09:30-18:00",
    "priceRange": "₹₹"
  }
  </script>

  <!-- Conditional FAQPage Schema (Only for Homepage or when FAQ schema exists) -->
  <?php if ($currentPage === 'index' || !empty($faqSchema)): ?>
  <script type="application/ld+json">
  {
    "@context": "https://schema.org",
    "@type": "FAQPage",
    "mainEntity": [
      {
        "@type": "Question",
        "name": "Which countries do you provide study abroad consultancy for?",
        "acceptedAnswer": {
          "@type": "Answer",
          "text": "We provide study abroad consultancy services for the UK, USA, Canada, Europe, Australia, Singapore, Dubai, and Malta, helping students choose suitable courses and universities."
        }
      },
      {
        "@type": "Question",
        "name": "Do you help with student visa processing?",
        "acceptedAnswer": {
          "@type": "Answer",
          "text": "Yes, we offer complete student visa assistance, including document preparation, application filing, interview guidance, and visa status support."
        }
      },
      {
        "@type": "Question",
        "name": "Is personalized counseling available for students?",
        "acceptedAnswer": {
          "@type": "Answer",
          "text": "Yes, our counselors provide one-to-one personalized guidance based on the student's academic background, career goals, and financial considerations."
        }
      },
      {
        "@type": "Question",
        "name": "What services does Bluestone Overseas Consultants offer?",
        "acceptedAnswer": {
          "@type": "Answer",
          "text": "Bluestone Overseas Consultants provides study abroad counseling, university admissions support, IELTS coaching, visa assistance, and pre-departure guidance for students planning to study overseas."
        }
      }
    ]
  }
  </script>
  <?php endif; ?>

  <!-- Support Custom Page Schema Injection -->
  <?php if (!empty($pageSchema)): ?>
  <script type="application/ld+json">
  <?= json_encode($pageSchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) ?>
  </script>
  <?php endif; ?>
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
      <img src="assets/images/Logo_old.png" alt="Bluestone Overseas Consultants" class="navbar__logo-img">
    </a>

    <nav class="navbar__menu" id="navMenu">
      <div class="mobile-menu-header">
        <img src="assets/images/Logo_old.png" alt="Bluestone Logo">
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
          <div class="dropdown">
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
        </li>

        <!-- About Us -->
        <li class="has-dropdown <?= in_array($currentPage, ['About_us','Award_Achievements','events','Blog','gallery','contact','guide-me']) ? 'active' : '' ?>">
          <a href="About_us.php">About Us <i class="fa-solid fa-chevron-down"></i></a>
          <div class="dropdown">
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
