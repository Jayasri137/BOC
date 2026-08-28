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
if (preg_match('/^(usa|uk|canada|australia|ireland|germany|france|italy|singapore|malaysia|denmark|bulgaria|russia|switzerland|south-korea|netherlands|uae|spain)\.php$/i', $script_name, $matches)) {
    $country = strtolower($matches[1]);
    header("Location: study-in-{$country}.php", true, 301);
    exit();
}

// Test preps redirect
if (preg_match('/^(ielts|ielts-test|ielts_test)\.php$/i', $script_name)) {
    header("Location: ielts-coaching-in-coimbatore.php", true, 301);
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

// Fetch active announcements globally
$globalAnnouncements = [];
try {
    if (isset($pdo)) {
        $stmtAnn = $pdo->prepare("SELECT * FROM announcements WHERE is_active = 1 ORDER BY id DESC");
        $stmtAnn->execute();
        $globalAnnouncements = $stmtAnn->fetchAll(PDO::FETCH_ASSOC);
    }
} catch (PDOException $e) {}

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
  <style>
    /* Premium Announcement Banner */
    .announcement-banner {
      background: #ffffff;
      color: #0f172a;
      padding: 0.6rem 1rem;
      display: flex;
      justify-content: center;
      align-items: center;
      gap: 1rem;
      position: relative;
      z-index: 10;
      overflow: hidden;
      border-bottom: 1px solid rgba(0,0,0,0.06);
    }
    .announcement-banner::before {
      content: '';
      position: absolute;
      top: 0; left: 0; right: 0; bottom: 0;
      background: linear-gradient(90deg, transparent, rgba(0,0,0,0.03), transparent);
      transform: translateX(-100%);
      animation: shimmer 3s infinite;
    }
    @keyframes shimmer {
      100% { transform: translateX(100%); }
    }
    .banner-badge {
      background: #f43f5e;
      color: white;
      padding: 0.2rem 0.75rem;
      border-radius: 50px;
      font-weight: 800;
      font-size: 0.75rem;
      letter-spacing: 1px;
      text-transform: uppercase;
      animation: pulse-badge 2s infinite;
      white-space: nowrap;
    }
    @keyframes pulse-badge {
      0% { box-shadow: 0 0 0 0 rgba(244, 63, 94, 0.4); }
      70% { box-shadow: 0 0 0 6px rgba(244, 63, 94, 0); }
      100% { box-shadow: 0 0 0 0 rgba(244, 63, 94, 0); }
    }
    .banner-text {
      font-weight: 600;
      font-size: 0.95rem;
      font-family: 'Plus Jakarta Sans', sans-serif;
      margin-right: 3rem;
      display: inline-flex;
      align-items: center;
    }
    .marquee-container {
      flex: 1;
      overflow: hidden;
      position: relative;
      display: flex;
      align-items: center;
      white-space: nowrap;
      mask-image: linear-gradient(90deg, transparent, #000 2%, #000 98%, transparent);
      -webkit-mask-image: linear-gradient(90deg, transparent, #000 2%, #000 98%, transparent);
    }
    .marquee-content {
      display: inline-flex;
      padding-left: 100%;
      animation: marquee 25s linear infinite;
      align-items: center;
    }
    .marquee-content:hover {
      animation-play-state: paused;
    }
    @keyframes marquee {
      0% { transform: translate(0, 0); }
      100% { transform: translate(-100%, 0); }
    }
    .banner-btn {
      background: #eff6ff;
      color: #2563eb;
      padding: 0.2rem 0.8rem;
      border-radius: 50px;
      font-weight: 700;
      font-size: 0.8rem;
      text-decoration: none;
      transition: all 0.3s;
      display: inline-flex;
      align-items: center;
      gap: 0.3rem;
      margin-left: 0.8rem;
      border: 1px solid #bfdbfe;
    }
    .banner-btn:hover {
      background: #2563eb;
      color: #ffffff;
      transform: translateY(-2px);
      box-shadow: 0 4px 10px rgba(37,99,235,0.2);
    }
    @media (max-width: 768px) {
      .announcement-banner { flex-direction: column; text-align: center; gap: 0.5rem; padding: 0.8rem; }
      .banner-btn, .banner-badge { display: none !important; }
      .marquee-content { animation: marquee 15s linear infinite; }
    }
  </style>
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
<body class="<?= $currentPage === 'index' ? 'page-home' : '' ?>">

<!-- Header Wrapper for Absolute Overlay on Homepage -->
<div class="header-wrapper">
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
      <a href="https://wa.me/919342899904" target="_blank" aria-label="WhatsApp"><i class="fa-brands fa-whatsapp"></i></a>
    </div>
  </div>
</div>

<?php if (!empty($globalAnnouncements)): ?>
<!-- Global Announcement Bar -->
<div class="announcement-banner">
  <div class="banner-badge">UPDATES</div>
  <div class="marquee-container">
    <div class="marquee-content">
      <?php foreach ($globalAnnouncements as $ann): ?>
        <span class="banner-text">
          <?= htmlspecialchars($ann['text']) ?>
          <?php if (!empty($ann['link'])): ?>
            <a href="<?= htmlspecialchars($ann['link']) ?>" class="banner-btn">View Details <i class="fa-solid fa-arrow-right"></i></a>
          <?php endif; ?>
        </span>
      <?php endforeach; ?>
    </div>
  </div>
</div>
<?php endif; ?>

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
        <!-- Home -->
        <li class="<?= ($currentPage === 'index') ? 'active' : '' ?>">
          <a href="index.php">Home</a>
        </li>
        
        <!-- About Us -->
        <li class="has-dropdown <?= in_array($currentPage, ['About_us','Award_Achievements','events','Blog','gallery','contact','guide-me']) ? 'active' : '' ?>">
          <a href="About_us.php">About Us <i class="fa-solid fa-chevron-down"></i></a>
          <div class="dropdown">
            <div class="mega-menu-inner">
              <div>
                <p class="mega-menu-col-title">Company</p>
                <a href="About_us.php" class="mega-item"><i class="fa-solid fa-building"></i> Our Profile</a>
                <a href="team.php" class="mega-item"><i class="fa-solid fa-users"></i> Our Team</a>
                <a href="branch.php" class="mega-item"><i class="fa-solid fa-location-dot"></i> Branches</a>
                <a href="contact.php" class="mega-item"><i class="fa-solid fa-address-book"></i> Contact Us</a>
              </div>
              <div>
                <p class="mega-menu-col-title">Updates & Media</p>
                <a href="Blog.php" class="mega-item"><i class="fa-solid fa-newspaper"></i> Blog</a>
                <a href="gallery.php" class="mega-item"><i class="fa-solid fa-images"></i> Gallery</a>
              </div>
            </div>
          </div>
        </li>
        
        <!-- Study destinations -->
        <li class="has-dropdown <?= isset($isStudyAbroad) && $isStudyAbroad ? 'active' : '' ?>">
          <a href="country.php">Study Destinations <i class="fa-solid fa-chevron-down"></i></a>
          <div class="dropdown">
            <div class="mega-menu-inner mega-menu-inner--2col">
              <div>
                <p class="mega-menu-col-title">Popular Destinations</p>
                <div class="countries-grid-secondary">
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
              </div>
              
              <div>
                <p class="mega-menu-col-title">Other Destinations</p>
                <div class="countries-grid-secondary">
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
            </div>
          </div>
        </li>

        <!-- Services -->
        <li class="has-dropdown <?= in_array($currentPage, ['Free_Counselling','Course_Advice','Universities_Recommendation','Admission_Guidance','Student_Visa','financial-assistance','education-loan','accommodation','part-time-jobs','health-insurance','bank-account','courses','universities','scholarships']) ? 'active' : '' ?>">
          <a href="services.php">Services <i class="fa-solid fa-chevron-down"></i></a>
          <div class="dropdown">
            <div class="mega-menu-inner mega-menu-inner--3col">
              <div>
                <p class="mega-menu-col-title">Admissions & Counselling</p>
                <a href="student-counselling.php" class="mega-item"><i class="fa-solid fa-question-circle"></i> Student Counselling</a>
                <a href="courses.php" class="mega-item"><i class="fa-solid fa-book-open"></i> Course Advice</a>
                <a href="university-selection.php" class="mega-item"><i class="fa-solid fa-map-location-dot"></i> University Selection</a>
                <a href="admission-processing.php" class="mega-item"><i class="fa-solid fa-file-signature"></i> Admission Processing</a>
              </div>
              <div>
                <p class="mega-menu-col-title">Finance & Scholarships</p>
                <a href="education-loan.php" class="mega-item"><i class="fa-solid fa-hand-holding-dollar"></i> Education Loan</a>
                <a href="scholarships.php" class="mega-item"><i class="fa-solid fa-graduation-cap"></i> Find a Scholarship</a>
                <a href="bank-account.php" class="mega-item"><i class="fa-solid fa-building-columns"></i> Bank Account Opening</a>
              </div>
              <div>
                <p class="mega-menu-col-title">Visa & Pre-Departure</p>
                <a href="visa-processing.php" class="mega-item"><i class="fa-solid fa-passport"></i> Visa Processing</a>
                <a href="accommodation.php" class="mega-item"><i class="fa-solid fa-plane-departure"></i> Accommodation & Travel</a>
                <a href="health-insurance.php" class="mega-item"><i class="fa-solid fa-shield-heart"></i> Health Insurance</a>
              </div>
            </div>
          </div>
        </li>

        <!-- Test Prep -->
        <li class="has-dropdown">
          <a href="test-prep.php">Test Prep <i class="fa-solid fa-chevron-down"></i></a>
          <div class="dropdown">
            <div class="mega-menu-inner">
              <div>
                <p class="mega-menu-col-title">English Proficiency</p>
                <a href="ielts-coaching-in-coimbatore.php" class="mega-item"><i class="fa-solid fa-pen-to-square"></i> IELTS Coaching</a>
                <a href="toefl.php" class="mega-item"><i class="fa-solid fa-pen-to-square"></i> TOEFL</a>
                <a href="pte.php" class="mega-item"><i class="fa-solid fa-pen-to-square"></i> PTE</a>
              </div>
              <div>
                <p class="mega-menu-col-title">Foreign Languages</p>
                <a href="japanese.php" class="mega-item"><span class="country-flag fi fi-jp"></span> Japanese (JLPT)</a>
                <a href="german.php" class="mega-item"><span class="country-flag fi fi-de"></span> German (Goethe)</a>
              </div>
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
</header>
</div>

<?php if ($currentPage !== 'index' && empty($hideDefaultHero)): ?>
<!-- Global Page Hero for Internal Pages -->
<?php
$servicePages = ['student-counselling', 'courses', 'university-selection', 'admission-processing', 'education-loan', 'scholarships', 'bank-account', 'visa-processing', 'accommodation', 'health-insurance', 'Free_Counselling','Course_Advice','Universities_Recommendation','Admission_Guidance','Student_Visa','financial-assistance','part-time-jobs','universities'];
$heroClass = in_array($currentPage, $servicePages) ? 'page-hero page-hero--services' : 'page-hero';
?>
<section class="<?= $heroClass ?>">
  
  <div class="container page-hero__inner">
    <!-- Left Column: Content -->
    <div class="page-hero__content animate-on-scroll">
      <?php 
        // Clean up title for hero display
        $displayTitle = isset($pageTitle) ? explode('|', $pageTitle)[0] : 'Welcome';
        $displayTitle = trim($displayTitle);
      ?>
      <h1 class="page-hero__title"><?= htmlspecialchars($displayTitle) ?></h1>
      


      <?php if (!empty($pageDesc)): ?>
        <p class="page-hero__desc" style="color: #ffffff !important;"><?= htmlspecialchars($pageDesc) ?></p>
      <?php endif; ?>
    </div>
    
    <!-- Right Column: Image -->
    <div class="page-hero__image-col animate-on-scroll delay-1">
      <?php 
        // Intelligent dynamic image mapping
        if (isset($pageHeroImage)) {
            $heroImg = $pageHeroImage;
        } else {
            $heroImg = 'assets/images/cont.png'; // Fallback
            $pageStr = strtolower($currentPage);
            
            // Map based on keywords in the page name
            if (strpos($pageStr, 'uk') !== false && $pageStr !== 'uk-template') {
                $heroImg = 'assets/images/3d_tower_bridge.png';
            } elseif (strpos($pageStr, 'usa') !== false) {
                $heroImg = 'assets/images/3d_usa.png';
            } elseif (strpos($pageStr, 'canada') !== false) {
                $heroImg = 'assets/images/3d_canada.png';
            } elseif (strpos($pageStr, 'australia') !== false) {
                $heroImg = 'assets/images/3d_sydney_opera.png';
            } elseif (strpos($pageStr, 'new-zealand') !== false || strpos($pageStr, 'nz') !== false) {
                $heroImg = 'assets/images/3d_new_zealand.png';
            } elseif (strpos($pageStr, 'ireland') !== false) {
                $heroImg = 'assets/images/3d_ireland.png';
            } elseif (strpos($pageStr, 'germany') !== false) {
                $heroImg = 'assets/images/3d_germany.png';
            } elseif (strpos($pageStr, 'france') !== false) {
                $heroImg = 'assets/images/3d_eiffel_tower.png';
            } elseif (strpos($pageStr, 'visa') !== false) {
                $heroImg = 'assets/images/service_visa_3d.png';
            } elseif (strpos($pageStr, 'financial') !== false || strpos($pageStr, 'loan') !== false || strpos($pageStr, 'scholarship') !== false) {
                $heroImg = 'assets/images/service_financing_3d.png';
            } elseif (strpos($pageStr, 'admission') !== false || strpos($pageStr, 'university') !== false || strpos($pageStr, 'universities') !== false) {
                $heroImg = 'assets/images/service_university_3d.png';
            } elseif (strpos($pageStr, 'coaching') !== false || strpos($pageStr, 'ielts') !== false || strpos($pageStr, 'toefl') !== false || strpos($pageStr, 'pte') !== false) {
                $heroImg = 'assets/images/service_coaching_3d.png';
            } elseif (strpos($pageStr, 'mbbs') !== false) {
                $heroImg = 'assets/images/service_mbbs_3d.png';
            } elseif ($pageStr === 'services') {
                $heroImg = 'assets/images/services_banner.png';
            } elseif ($pageStr === 'contact' || strpos($pageStr, 'branch') !== false) {
                $heroImg = 'assets/images/hero-counselling.png';
            } elseif ($pageStr === 'about_us' || $pageStr === 'about') {
                $heroImg = 'assets/images/woman-hero.png';
            } elseif ($pageStr === 'student-counselling' || strpos($pageStr, 'guide') !== false) {
                $heroImg = 'assets/images/img10.png';
            }
        }
      ?>
      <div class="page-hero__image-wrapper">
        <!-- Organic Morphing Blob Background -->
        <div class="page-hero__blob"></div>
        
        <img src="<?= htmlspecialchars($heroImg) ?>" alt="<?= htmlspecialchars($displayTitle) ?>" class="page-hero__image">
      </div>
    </div>
  </div>
  
  <!-- Bottom Curve matching site background -->
  <div class="page-hero__curve">
    <svg viewBox="0 0 1440 100" fill="none" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="none">
      <path d="M0,100 C480,0 960,0 1440,100 L1440,100 L0,100 Z" fill="currentColor"/>
    </svg>
  </div>
</section>
<?php endif; ?>
