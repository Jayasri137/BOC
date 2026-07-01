<?php
require_once 'includes/config.php';

$slugRaw = trim($_GET['s'] ?? '');
$redirects = [
    'counselling' => 'student-counselling.php',
    'university' => 'university-selection.php',
    'admission' => 'admission-processing.php',
    'financial' => 'financial-assistance.php',
    'visa' => 'visa-processing.php',
    'accommodation' => 'accommodation.php',
    'jobs' => 'part-time-jobs.php'
];
if (isset($redirects[$slugRaw])) {
    $target = $redirects[$slugRaw];
    $params = $_GET;
    unset($params['s']);
    if (!empty($params)) {
        $target .= '?' . http_build_query($params);
    }
    header("Location: " . $target, true, 301);
    exit;
}

$slugAliases = [
    'university-selection' => 'university',
    'admission-processing' => 'admission',
];
$slug = $slugAliases[$slugRaw] ?? $slugRaw;
$services_data = [
    'counselling' => [
        'name' => 'Student Counselling',
        'icon' => 'fa-user-graduate',
        'desc' => 'Our expert counsellors provide personalised guidance to help you choose the right course and country matching your academic goals, budget, and future aspirations.',
        'details' => ['Profile Assessment', 'Career Goal Mapping', 'Budget Planning', 'Country Selection']
    ],
    'university' => [
        'name' => 'University Selection',
        'icon' => 'fa-university',
        'desc' => 'We help you navigate the complex process of choosing the perfect university from our 500+ global partners based on rankings, course modules, and placement records.',
        'details' => ['Shortlisting Universities', 'Course Comparison', 'Entrance Requirement Check', 'Scholarship Availability']
    ],
    'admission' => [
        'name' => 'Admission Processing',
        'icon' => 'fa-file-contract',
        'desc' => 'Our dedicated team manages your entire application process, ensuring all documents like SOPs, LORs, and transcripts are flawless and submitted before deadlines.',
        'details' => ['Document Verification', 'SOP Writing Guidance', 'LOR Preparation', 'Application Tracking']
    ],
    'financial' => [
        'name' => 'Financial Assistance',
        'icon' => 'fa-hand-holding-dollar',
        'desc' => 'We guide you on various financial options including university scholarships, external grants, and education loans to make your international education affordable.',
        'details' => ['Scholarship Application', 'Education Loan Assistance', 'Budget Management', 'Sponsorship Guidance']
    ],
    'visa' => [
        'name' => 'Visa Processing',
        'icon' => 'fa-passport',
        'desc' => 'With a 98% success rate, our visa experts guide you through the intricate immigration requirements, documentation, and mock interviews for all major destinations.',
        'details' => ['Visa Documentation', 'Financial Proof Prep', 'Interview Mock Sessions', 'Visa Filing']
    ],
    'accommodation' => [
        'name' => 'Accommodation & Travel',
        'icon' => 'fa-house',
        'desc' => 'We don\'t just stop at the visa. We help you find safe student housing and book your travel so you can settle into your new life abroad comfortably.',
        'details' => ['On-Campus Housing', 'Off-Campus Flats', 'Flight Bookings', 'Pre-Departure Briefing']
    ],
    'jobs' => [
        'name' => 'Part-Time Job Assistance',
        'icon' => 'fa-briefcase',
        'desc' => 'We provide guidance on legal part-time work rights and help you prepare your resume to find suitable job opportunities while studying abroad.',
        'details' => ['Work Rights Education', 'Resume Building', 'Local Job Portal Access', 'Networking Tips']
    ],
];

$service = $services_data[$slug] ?? null;

$selectedCountry = 0;
$selectedCountryName = '';
$countries = [];
$universities = [];

if ($slug === 'university' || $slug === 'admission') {
    $selectedCountry = isset($_GET['country_id']) ? intval($_GET['country_id']) : 0;
    $countries = $pdo->query("SELECT id, name, flag FROM countries WHERE is_active = 1 ORDER BY name ASC")->fetchAll();
    if ($selectedCountry > 0) {
        $stmt = $pdo->prepare("SELECT name FROM countries WHERE id = ? AND is_active = 1");
        $stmt->execute([$selectedCountry]);
        $countryRow = $stmt->fetch();
        $selectedCountryName = $countryRow ? $countryRow['name'] : '';

        $stmt = $pdo->prepare("SELECT * FROM universities WHERE country_id = ? AND is_active = 1 ORDER BY name ASC");
        $stmt->execute([$selectedCountry]);
        $universities = $stmt->fetchAll();
    }
}

if (!$service) {
    $pageTitle = 'Our Services | Bluestone Overseas Consultants';
} else {
    $pageTitle = $service['name'] . ' | Bluestone Overseas Consultants';
}

require_once 'includes/header.php';
?>
<main>
  <section class="section">
    <div class="container">
      <?php if ($service): ?>
        <?php if ($slug === 'university' || $slug === 'admission'): ?>
          <div class="filter-card animate-on-scroll" style="margin-bottom: 4rem; background: #fff; padding: 2rem; border-radius: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.05); border: 1px solid #f1f5f9;">
            <form action="services.php" method="GET" class="grid grid--2 gap--2 align-center" style="grid-template-columns: 1fr auto;">
              <input type="hidden" name="s" value="<?= clean_output($slug) ?>">
              <div>
                <h3 style="margin: 0; font-size: 1.25rem;">
                  <?= $slug === 'university' ? 'Select your dream destination to explore our partner universities.' : 'Select your destination to view application-ready universities.' ?>
                </h3>
              </div>
              <div style="display: flex; gap: 1rem; flex-wrap: wrap; align-items: center;">
                <select name="country_id" class="form-control" style="min-width: 250px; padding: 0.75rem 1rem; border-radius: 10px; border: 1px solid #e2e8f0;" onchange="this.form.submit()">
                  <option value="">-- Choose Country --</option>
                  <?php foreach ($countries as $c): ?>
                    <option value="<?= $c['id'] ?>" <?= $selectedCountry == $c['id'] ? 'selected' : '' ?>>
                      <?= clean_output($c['flag'] . ' ' . $c['name']) ?>
                    </option>
                  <?php endforeach; ?>
                </select>
                <button type="submit" class="btn btn--primary">Search</button>
              </div>
            </form>
          </div>

          <?php if ($selectedCountry): ?>
            <div class="section-heading" style="margin-bottom: 2rem; text-align: center;">
              <h2 style="font-size: 2rem; margin-bottom: 0.5rem;">
                <?= $slug === 'university' ? 'Partner Universities in ' : 'Universities in ' ?><?= clean_output($selectedCountryName ?: 'Selected Country') ?>
              </h2>
              <p style="color: var(--gray);">
                <?= $slug === 'university' ? 'Explore our active university partners for this destination.' : 'Choose any listed university to start admission processing.' ?>
              </p>
            </div>

            <div class="grid grid--3 gap--2 animate-on-scroll">
              <?php if ($universities): ?>
                <?php foreach ($universities as $u): ?>
                  <div class="service-card" style="padding: 2rem; background: #fff; border-radius: 15px; border: 1px solid #f1f5f9; transition: var(--transition);">
                    <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 1.5rem;">
                      <div class="v-icon" style="width:50px; height:50px; font-size:1.2rem; margin:0;<?= $slug === 'admission' ? ' background: linear-gradient(135deg, #f59e0b, #fbbf24);' : '' ?>"><i class="fa-solid <?= $slug === 'university' ? 'fa-building-columns' : 'fa-file-circle-check' ?>"></i></div>
                      <h4 style="margin: 0; font-size: 1.1rem; line-height: 1.3;"><?= clean_output($u['name']) ?></h4>
                    </div>
                    <?php if ($slug === 'university'): ?>
                      <div style="font-size: 0.85rem; color: var(--gray); margin-bottom: 1.5rem;">
                        <p><i class="fa-solid fa-ranking-star text-primary"></i> Global Ranking: <strong>#<?= clean_output($u['qs_ranking'] ?: 'N/A') ?></strong></p>
                        <p><i class="fa-solid fa-graduation-cap text-primary"></i> Specialized in: <?= clean_output($u['specialization'] ?: 'General Studies') ?></p>
                      </div>
                      <a href="courses.php?university_id=<?= $u['id'] ?>" class="btn btn--outline btn--sm" style="width: 100%; justify-content: center;">View Courses</a>
                    <?php else: ?>
                      <div style="font-size: 0.85rem; color: var(--gray); margin-bottom: 1.5rem;">
                        <p><i class="fa-solid fa-clock text-primary"></i> Intake Status: <strong>Open for 2026</strong></p>
                        <p><i class="fa-solid fa-bolt text-primary"></i> Application Mode: <strong>Fast-Track Available</strong></p>
                      </div>
                      <a href="enquiry.php?university=<?= urlencode($u['name']) ?>" class="btn btn--primary btn--sm" style="width: 100%; justify-content: center;">Start Application</a>
                    <?php endif; ?>
                  </div>
                <?php endforeach; ?>
              <?php else: ?>
                <div class="col-span-3 text-center py-5">
                  <p style="color: var(--gray);">
                    <?= $slug === 'university' ? 'No universities listed for this country yet. Contact us for the full list of our 500+ partners.' : 'No active intakes found for this country. Please contact our admission desk for manual processing.' ?>
                  </p>
                </div>
              <?php endif; ?>
            </div>
          <?php else: ?>
            <div class="text-center animate-on-scroll" style="padding: 3rem 1rem; opacity: 0.9;">
              <div style="font-size: 4rem; color: #f1f5f9; margin-bottom: 1.25rem;"><i class="fa-solid fa-earth-americas"></i></div>
              <h3 style="margin-bottom: 0.75rem;">Select a country to view <?= $service['name'] ?> universities.</h3>
              <p style="max-width: 680px; margin: 0 auto; color: var(--gray); line-height: 1.7;">
                Use the filter above to choose a destination. University data is fetched from the admin panel and displayed once you select the relevant country.
              </p>
            </div>
          <?php endif; ?>
        <?php endif; ?>

        <div class="grid grid--2 gap--4 align-center">
          <div class="col-lg-6 mb-4 mb-lg-0 animate-on-scroll">
            <h1 class="section__title" style="text-align:left; margin-top:2rem">What we cover in <span><?= $service['name'] ?></span></h1>
            <p class="lead"><?= clean_output($service['description'] ?? '') ?></p>
          </div>
          <div class="animate-on-scroll delay-1">
            <div class="service-details grid grid--1 gap--1">
              <?php foreach($service['details'] as $detail): ?>
                <div class="a-feat"><i class="fa-solid fa-check-circle"></i><span><?= $detail ?></span></div>
              <?php endforeach; ?>
            </div>
            <div style="margin-top:2.5rem">
              <a href="consultation.php" class="btn btn--primary btn--lg">Get Started Today</a>
            </div>
          </div>
        </div>
      <?php else: ?>
        <div class="services-grid grid grid--3 gap--2">
          <?php 
          $colors = ['blue', 'purple', 'orange', 'teal', 'pink', 'gold', 'blue'];
          $i = 0;
          $redirects = [
              'counselling' => 'student-counselling.php',
              'university' => 'university-selection.php',
              'admission' => 'admission-processing.php',
              'financial' => 'financial-assistance.php',
              'visa' => 'visa-processing.php',
              'accommodation' => 'accommodation.php',
              'jobs' => 'part-time-jobs.php'
          ];
          foreach($services_data as $s => $data): 
            $color = $colors[$i % count($colors)];
            $i++;
            $dedicatedLink = $redirects[$s] ?? "services.php?s={$s}";
          ?>
            <div class="service-card animate-on-scroll" onclick="location.href='<?= $dedicatedLink ?>'">
              <div class="service-card__icon service-card__icon--<?= $color ?>"><i class="fa-solid <?= $data['icon'] ?>"></i></div>
              <h3><?= $data['name'] ?></h3>
              <p><?= substr($data['desc'], 0, 100) ?>...</p>
              <a href="<?= $dedicatedLink ?>" class="btn btn--outline btn--sm" style="margin-top:1.5rem">Learn More</a>
            </div>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>
    </div>
  </section>
</main>
<?php require_once 'includes/footer.php'; ?>
