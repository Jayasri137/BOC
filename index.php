<?php
require_once 'includes/config.php';
$pageTitle = 'Bluestone Overseas | Study Abroad Consultants';
$pageDesc = 'Expert study abroad guidance for USA, UK, Canada, Australia & more. Book a free consultation today.';
require_once 'includes/header.php';
?>
<main>
<!-- REDESIGNED COMPACT HERO SLIDER -->
<section class="hero-idp hero-slider" id="home">
  <!-- Blurred Background Shapes -->
  <div class="hero-idp__blur-bg">
    <div class="blur-shape blur-shape--1"></div>
    <div class="blur-shape blur-shape--2"></div>
    <div class="blur-shape blur-shape--3"></div>
  </div>

  <div class="slider-container">
    <?php
    try {
        $stmt = $pdo->query("SELECT * FROM hero_slides WHERE is_active = 1 ORDER BY id ASC");
        $db_slides = $stmt->fetchAll();
    } catch (PDOException $e) {
        $db_slides = [];
    }
    
    if (empty($db_slides)) {
        $db_slides = [
            [
                'image_path' => 'assets/images/img4.png',
                'badge' => 'Biggest Education Fair',
                'title' => 'Scholarships – Attend <span>Bluestone’s Biggest</span> Education Fair',
                'description' => 'USA | UK | Canada | Australia | New Zealand | Germany | Ireland',
                'button_text' => 'Secure your spot'
            ]
        ];
    }
    foreach($db_slides as $i => $slide):
    ?>
    <div class="slide <?= $i===0?'active':'' ?>">
      <div class="container hero-idp__inner">
        <div class="hero-idp__content animate-on-scroll">
          <?php if (!empty($slide['badge'])): ?>
            <span class="hero-idp__badge animate-up" style="display:inline-block; background:rgba(239, 68, 68, 0.1); color:var(--primary); padding:0.35rem 0.85rem; border-radius:50px; font-weight:600; font-size:0.8rem; margin-bottom:1rem; border:1px solid rgba(239, 68, 68, 0.15);"><?= clean_output($slide['badge']) ?></span>
          <?php endif; ?>
          <?php if ($i === 0): ?>
            <h1 class="hero-idp__title animate-up"><?= $slide['title'] ?></h1>
          <?php else: ?>
            <h2 class="hero-idp__title animate-up"><?= $slide['title'] ?></h2>
          <?php endif; ?>
          <p class="hero-idp__desc animate-up"><?= clean_output($slide['description']) ?></p>
          <div class="hero__actions animate-up">
            <a href="consultation.php" class="btn btn--primary btn--lg pulse-btn"><i class="fa-solid fa-calendar-check"></i> <?= clean_output($slide['button_text']) ?></a>
            <a href="country.php" class="btn btn--outline btn--lg"><i class="fa-solid fa-earth-americas"></i> Explore Countries</a>
          </div>
        </div>
        
        <div class="hero-idp__image-col animate-on-scroll">
          <div class="hero-idp__image-wrap">
            <img src="<?= clean_output($slide['image_path']) ?>" alt="Bluestone Overseas Consultant Global Education">
          </div>
        </div>
      </div>
    </div>
    <?php endforeach; ?>
  </div>

  <!-- Slider Dots -->
  <div class="slider-nav">
    <div class="slider-dots"></div>
  </div>

  <!-- OVERLAPPING STATS CARD -->
  <div class="hero-stats-card animate-on-scroll">
    <div class="hero-stats-card__grid">
      <div class="h-stat-item animate-up">
        <div class="h-stat-icon h-stat-icon--blue"><i class="fa-solid fa-graduation-cap"></i></div>
        <div class="h-stat-num">5,000+</div>
        <div class="h-stat-label">scholarships awarded through<br>Bluestone in one year</div>
      </div>
      <div class="h-stat-item animate-up" style="transition-delay: 0.1s;">
        <div class="h-stat-icon h-stat-icon--purple"><i class="fa-solid fa-globe"></i></div>
        <div class="h-stat-num">50,000</div>
        <div class="h-stat-label">students went to study abroad<br>with Bluestone in last 3 years</div>
      </div>
      <div class="h-stat-item animate-up" style="transition-delay: 0.2s;">
        <div class="h-stat-icon h-stat-icon--orange"><i class="fa-solid fa-building-columns"></i></div>
        <div class="h-stat-num">1000+</div>
        <div class="h-stat-label">Bluestone's prestigious<br>university partners</div>
      </div>
      <div class="h-stat-item animate-up" style="transition-delay: 0.3s;">
        <div class="h-stat-icon h-stat-icon--teal"><i class="fa-solid fa-users"></i></div>
        <div class="h-stat-num">FREE</div>
        <div class="h-stat-label">counselling for students &<br>parents</div>
      </div>
    </div>
  </div>

  <!-- HERO QUICK LINKS -->
  <div class="hero-links animate-up" style="transition-delay: 0.4s;">
    <div class="container">
      <div class="hero-links__inner">
        <a href="courses.php">Courses <i class="fa-solid fa-chevron-right"></i></a>
        <a href="scholarships.php">Scholarships <i class="fa-solid fa-chevron-right"></i></a>
        <a href="universities.php">Universities <i class="fa-solid fa-chevron-right"></i></a>
        <a href="events.php">Events <i class="fa-solid fa-chevron-right"></i></a>
        <a href="guide-me.php">Guide me <i class="fa-solid fa-chevron-right"></i></a>
        <a href="consultation.php">Get instant offer <i class="fa-solid fa-chevron-right"></i></a>
      </div>
    </div>
  </div>
</section>


<!-- HOME ENQUIRY SECTION (IDP STYLE) -->
<section class="home-enquiry section">
  <div class="container">
    <div class="home-enquiry__grid">
      <div class="home-enquiry__form-col animate-on-scroll">
        <div class="section__header" style="text-align: left; margin-bottom: 2.5rem; max-width: 100%;">
          <span class="section__tag">Direct Admission & Counselling</span>
          <h2 class="section__title" style="margin-top: 1rem;">Interested in <span>Studying Abroad</span> with Bluestone?</h2>
          <p>Enter your details below and our expert study abroad counsellor will contact you shortly to guide you through every step.</p>
        </div>
        <div class="home-enquiry__form-card">
          <!-- Background Glowing Blobs -->
          <div class="form-blobs">
            <div class="form-blob form-blob--1"></div>
            <div class="form-blob form-blob--2"></div>
            <div class="form-blob form-blob--3"></div>
          </div>
          <form id="homeEnquiryForm" onsubmit="return handleFormSubmit(event)">
            <input type="hidden" name="form_type" value="enquiry">
            <div class="cf-grid-2">
              <div class="cf-group"><label>First name*</label><input type="text" name="first_name" placeholder="First name" required></div>
              <div class="cf-group"><label>Last name*</label><input type="text" name="last_name" placeholder="Last name" required></div>
            </div>
             <div class="cf-grid-2">
            <div class="cf-group"><label>Email address*</label><input type="email" name="email" placeholder="Email address" required></div>
            
            <div class="cf-group"><label>Mobile number*</label>
            
              <div style="display: flex; gap: 0.5rem;">
                <input type="text" value="+91" readonly style="width: 60px; text-align: center; background: #e2e8f0; font-weight: 700;">
                <input type="tel" name="phone" placeholder="Mobile number" required style="flex: 1;">
              </div>
              </div>
            </div>

            <div class="cf-grid-2">
              <div class="cf-group"><label>Preferred study destination*</label>
                <select name="destination" required>
                  <option value="" disabled selected>Select</option>
                  <option>Australia</option><option>Canada</option><option>UK</option><option>USA</option><option>New Zealand</option><option>Ireland</option><option>Germany</option>
                </select>
              </div>
              <div class="cf-group"><label>When would you like to start?*</label>
                <select name="start_date" required>
                  <option value="" disabled selected>Select</option>
                  <option>Jan 2026</option><option>May 2026</option><option>Sept 2026</option><option>Jan 2027</option>
                </select>
              </div>
            </div>

            <div class="cf-grid-2">
              <div class="cf-group"><label>Preferred study level*</label>
                <select name="study_level" required>
                  <option value="" disabled selected>Select</option>
                  <option>Undergraduate</option><option>Postgraduate</option><option>MBA</option><option>PhD</option>
                </select>
              </div>
              <div class="cf-group"><label>Preferred mode of counselling*</label>
                <select name="counselling_mode" required>
                  <option value="" disabled selected>Select</option>
                  <option>In-person</option><option>Virtual Counselling</option>
                </select>
              </div>
            </div>

               <div class="cf-group"><label>Message</label>
          
                <textarea name="message" placeholder="Message"></textarea>
              
            </div>
<!-- 
            <div class="cf-group"><label>How would you fund your education?*</label>
              <select name="funding_mode" required>
                <option value="" disabled selected>Select</option>
                <option>Self-funded</option><option>Student Loan</option><option>Scholarship</option><option>Parents/Guardian</option>
              </select>
            </div> -->

            <button type="submit" class="btn btn--primary btn--lg" style="width: 100%; justify-content: center; margin-top: 1.5rem; height: 56px;">Help me study abroad</button>
            <p style="font-size: 0.75rem; color: #94a3b8; margin-top: 1.5rem; text-align: center;">Bluestone Overseas will use your information to contact you for study abroad services.</p>
          </form>
        </div>
      </div>
      <div class="home-enquiry__image animate-on-scroll">
        <img src="assets/images/form.png" alt="Study Abroad Expert Counsellor helping student" width="100%" height="150%">
      </div>
    </div>
  </div>
</section>


<!-- SERVICES -->
<section class="section services-section" id="services">
  <div class="container">
    <div class="section__header animate-on-scroll">
      <span class="section__tag">What We Do</span>
      <h2 class="section__title">Comprehensive <span>Study Abroad</span> Services</h2>
      <p class="section__subtitle">From counselling to visa processing &mdash; we support you at every step of your international education journey.</p>
      <div class="accent-bar"></div>
    </div>
    <div class="services-grid-new">
      <?php
      try {
          $stmt = $pdo->query("SELECT * FROM services WHERE is_active = 1 ORDER BY id ASC");
          $services_db = $stmt->fetchAll();
          $services = [];
          foreach ($services_db as $s) {
              $services[] = [
                  $s['icon'],
                  $s['title'],
                  $s['description'],
                  $s['link'],
                  $s['color']
              ];
          }
      } catch (PDOException $e) {
          $services = [
              ['fa-user-graduate','Student Counselling','Personalised guidance to help you choose the right course and institution matching your academic goals and budget.','student-counselling.php','blue'],
              ['fa-university','University Selection','We help identify the best-fit universities across 20+ countries based on your profile and aspirations.','university-selection.php','purple'],
              ['fa-file-contract','Admission Processing','Expert application management ensuring all documents are accurate, complete and submitted on time.','admission-processing.php','orange'],
              ['fa-hand-holding-dollar','Financial Assistance','Guidance on scholarships, student loans and funding options to make your dream affordable.','financial-assistance.php','teal'],
              ['fa-passport','Visa Processing','End-to-end visa assistance with a 98% success rate, navigating complex immigration requirements.','visa-processing.php','pink'],
              ['fa-house','Accommodation & Travel','We help arrange housing and travel plans so you arrive and settle comfortably in your new country.','accommodation.php','gold'],
              ['fa-pen-to-square','Test Preparation','Specialised coaching for IELTS, TOEFL and PTE to achieve the scores required by top universities.','test-prep.php','blue'],
              ['fa-briefcase','Part-Time Job Help','Guidance on finding legal part-time work opportunities abroad to support yourself financially.','part-time-jobs.php','purple'],
          ];
      }

      // Dynamic URL normalization mapping to ensure DB records also link directly to dedicated pages
      $linkMap = [
          'services.php?s=counselling' => 'student-counselling.php',
          'services.php?s=university' => 'university-selection.php',
          'services.php?s=admission' => 'admission-processing.php',
          'services.php?s=financial' => 'financial-assistance.php',
          'services.php?s=visa' => 'visa-processing.php',
          'services.php?s=accommodation' => 'accommodation.php',
          'services.php?s=jobs' => 'part-time-jobs.php'
      ];
      $titleMap = [
          'student counselling' => 'student-counselling.php',
          'university selection' => 'university-selection.php',
          'application assistance' => 'admission-processing.php',
          'admission processing' => 'admission-processing.php',
          'scholarship assistance' => 'financial-assistance.php',
          'financial assistance' => 'financial-assistance.php',
          'visa guidance' => 'visa-processing.php',
          'visa processing' => 'visa-processing.php',
          'travel & accommodation' => 'accommodation.php',
          'accommodation & travel' => 'accommodation.php',
          'pre-departure briefing' => 'accommodation.php',
          'ielts/toefl coaching' => 'test-prep.php',
          'test preparation' => 'test-prep.php',
          'part-time job help' => 'part-time-jobs.php',
          'part-time job assistance' => 'part-time-jobs.php'
      ];
      foreach ($services as &$s_item) {
          $currentLink = $s_item[3];
          $titleLower = strtolower(trim($s_item[1]));
          if (isset($linkMap[$currentLink])) {
              $s_item[3] = $linkMap[$currentLink];
          } elseif ($currentLink === '#' || strpos($currentLink, 'services.php') !== false) {
              if (isset($titleMap[$titleLower])) {
                  $s_item[3] = $titleMap[$titleLower];
              }
          }
      }
      unset($s_item); // break loop reference safety

      foreach($services as $i=>[$icon,$title,$desc,$link,$color]):
      ?>
      <div class="s-card-premium animate-on-scroll delay-<?= $i%4 ?>">
        <div class="s-card-premium__inner">
          <div class="s-card-premium__front">
            <div class="s-card-premium__icon s-card-premium__icon--<?= $color ?>"><i class="fa-solid <?= $icon ?>"></i></div>
            <h3><?= $title ?></h3>
          </div>
          <div class="s-card-premium__back">
            <p><?= $desc ?></p>
            <a href="<?= $link ?>" class="btn btn--white btn--sm">Explore Details <i class="fa-solid fa-arrow-right"></i></a>
          </div>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- STUDENT JOURNEY (NEW SECTION) -->
<section class="section journey-section">
  <div class="container">
    <div class="section__header animate-on-scroll">
      <span class="section__tag">The Roadmap</span>
      <h2 class="section__title">Your <span>Success Journey</span> with Us</h2>
      <p class="section__subtitle">A clear, step-by-step path to your dream international university.</p>
    </div>
    
    <div class="journey-path">
      <div class="journey-step animate-on-scroll">
        <div class="journey-step__icon journey-step__icon--blue"><i class="fa-solid fa-comments"></i></div>
        <div class="journey-step__content">
          <span class="journey-step__num">01</span>
          <h4>Initial Counselling</h4>
          <p>Talk to our experts to discover your potential and explore study options.</p>
        </div>
      </div>
      <div class="journey-step animate-on-scroll delay-1">
        <div class="journey-step__icon journey-step__icon--purple"><i class="fa-solid fa-file-signature"></i></div>
        <div class="journey-step__content">
          <span class="journey-step__num">02</span>
          <h4>Application & Admission</h4>
          <p>We handle all paperwork and secure your spot in top universities.</p>
        </div>
      </div>
      <div class="journey-step animate-on-scroll delay-2">
        <div class="journey-step__icon journey-step__icon--orange"><i class="fa-solid fa-passport"></i></div>
        <div class="journey-step__content">
          <span class="journey-step__num">03</span>
          <h4>Visa Processing</h4>
          <p>High success rate in securing visas through expert documentation.</p>
        </div>
      </div>
      <div class="journey-step animate-on-scroll delay-3">
        <div class="journey-step__icon journey-step__icon--teal"><i class="fa-solid fa-plane-departure"></i></div>
        <div class="journey-step__content">
          <span class="journey-step__num">04</span>
          <h4>Pre-Departure Support</h4>
          <p>From flights to accommodation, we prepare you for your new life.</p>
        </div>
      </div>
    </div>
  </div>
</section>
<!-- COUNTRIES -->
<section class="section countries-section" id="destinations">
  <div class="container">
    <div class="section__header animate-on-scroll">
      <span class="section__tag">Study Destinations</span>
      <h2 class="section__title">Choose Your <span>Dream Country</span></h2>
      <p class="section__subtitle">Explore 20+ top study destinations. We have local expertise and university tie-ups in every country.</p>
      <div class="accent-bar"></div>
    </div>
    <div class="country-marquee-container">
      <?php
      try {
          $stmt = $pdo->query("SELECT * FROM countries WHERE is_active = 1 ORDER BY id ASC");
          $countries_db = $stmt->fetchAll();
          $all_countries = [];
          foreach ($countries_db as $c) {
              $all_countries[] = [
                  $c['slug'],
                  $c['name'],
                  $c['flag'],
                  $c['description']
              ];
          }
      } catch (PDOException $e) {
          $all_countries = [
            ['usa', 'United States', '🇺🇸', 'World-class universities with cutting-edge research facilities.'],
            ['uk', 'United Kingdom', '🇬🇧', 'Short duration courses with excellent academic reputation.'],
            ['canada', 'Canada', '🇨🇦', 'Safe, multicultural and affordable with great PR pathways.'],
            ['australia', 'Australia', '🇦🇺', 'Globally recognised degrees with excellent post-study work rights.'],
            ['germany', 'Germany', '🇩🇪', 'Free or low-cost tuition at top-ranked public universities.'],
            ['ireland', 'Ireland', '🇮🇪', 'English-speaking, tech-hub with a vibrant student community.'],
            ['singapore', 'Singapore', '🇸🇬', 'Asia\'s education capital with globally ranked universities.'],
            ['newzealand', 'New Zealand', '🇳🇿', 'Safe, scenic and student-friendly with excellent QS-ranked universities.'],
            ['france', 'France', '🇫🇷', 'Global leader in business, fashion, and culinary arts.'],
            ['italy', 'Italy', '🇮🇹', 'Study at the world\'s oldest universities in the land of art.'],
            ['sweden', 'Sweden', '🇸🇪', 'Innovation hub of Europe and home to the Nobel Prize.'],
            ['south-korea', 'South Korea', '🇰🇷', 'Leading in technology, robotics, and advanced research.'],
            ['uae', 'UAE', '🇦🇪', 'Tax-free work opportunities and global branch campuses.'],
            ['netherlands', 'Netherlands', '🇳🇱', 'First non-English country to offer courses in English.'],
            ['switzerland', 'Switzerland', '🇨🇭', 'Global center for banking, research, and hospitality.'],
            ['malaysia', 'Malaysia', '🇲🇾', 'UK and Australian degrees at a fraction of the cost.'],
            ['denmark', 'Denmark', '🇩🇰', 'Focus on innovation and high standard of living.'],
            ['bulgaria', 'Bulgaria', '🇧🇬', 'EU-recognized degrees with low tuition and living costs.'],
            ['russia', 'Russia', '🇷🇺', 'Strong legacy in medicine and engineering at low cost.'],
            ['philippines', 'Philippines', '🇵🇭', 'US-pattern medical education in a friendly environment.']
          ];
      }

      $row1 = array_slice($all_countries, 0, 10);
      $row2 = array_slice($all_countries, 10, 10);
      ?>

      <!-- Row 1: Right to Left -->
      <div class="country-marquee-wrapper">
        <div class="country-marquee-track marquee-left">
          <?php foreach (array_merge($row1, $row1) as $i => [$slug, $name, $flag, $desc]): ?>
            <div class="country-card-minimal">
              <div class="country-card-minimal__img">
                <img src="<?= get_country_image_url($slug) ?>" alt="<?= $name ?>">
                <div class="country-card-minimal__flag"><?= $flag ?></div>
              </div>
              <div class="country-card-minimal__content">
                <h3>Study in <?= $name ?></h3>
                <p><?= $desc ?></p>
                <div class="country-card-minimal__footer">
                  <a href="study-in-<?= $slug ?>.php" class="country-link-btn">Explore Opportunities <i class="fa-solid fa-arrow-right-long"></i></a>
                </div>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      </div>

      <!-- Row 2: Left to Right -->
      <div class="country-marquee-wrapper" style="margin-top: 1.5rem;">
        <div class="country-marquee-track marquee-right">
          <?php foreach (array_merge($row2, $row2) as $i => [$slug, $name, $flag, $desc]): ?>
            <div class="country-card-minimal">
              <div class="country-card-minimal__img">
                <img src="<?= get_country_image_url($slug) ?>" alt="<?= $name ?>">
                <div class="country-card-minimal__flag"><?= $flag ?></div>
              </div>
              <div class="country-card-minimal__content">
                <h3>Study in <?= $name ?></h3>
                <p><?= $desc ?></p>
                <div class="country-card-minimal__footer">
                  <a href="study-in-<?= $slug ?>.php" class="country-link-btn">Explore Opportunities <i class="fa-solid fa-arrow-right-long"></i></a>
                </div>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      </div>

      <div class="slider-hint animate-up" style="margin-top: 2rem;"><i class="fa-solid fa-mouse-pointer"></i> Hover on a card to pause</div>
    </div>

    <div style="text-align:center;margin-top:3.5rem">
      <a href="country.php" class="btn btn--outline btn--lg"><i class="fa-solid fa-earth-americas"></i> View All 20+ Destinations</a>
    </div>
  </div>
</section>
<!-- WHY US -->
<section class="section why-us" id="about">
  <div class="container">
    <div class="why-grid">
      <div>
        <span class="section__tag" style="background:rgba(14,165,233,.2);color:var(--primary)">Why Bluestone</span>
        <h2 class="section__title" style="margin-top:.75rem">Why <span>10,000+ Students</span><br>Trust Us?</h2>
        <p style="margin-bottom:2rem">Since 2015, we have been guiding students from across India to their dream universities abroad. Our team of experienced counsellors ensures every student gets personalised, honest advice.</p>
        <div class="why-features">
          <?php
          $features=[
            ['fa-shield-halved','Honest &amp; Accurate Guidance','No false promises. We assess your profile realistically and suggest the best options.','blue'],
            ['fa-headset','Dedicated Counsellors','Each student is assigned a personal counsellor who guides from start to finish.','purple'],
            ['fa-handshake','500+ University Tie-ups','Direct partnerships with universities for faster admissions and exclusive scholarships.','orange'],
            ['fa-passport','98% Visa Success Rate','Our visa team has an exceptional track record across all major destinations.','teal'],
            ['fa-location-dot','8 Branches + Global Support','Local support from our offices across Tamil Nadu, Nepal and Canada.','pink'],
          ];
          foreach($features as [$icon,$title,$desc,$color]):
          ?>
          <div class="why-feature">
            <div class="why-feature__icon why-feature__icon--<?= $color ?>"><i class="fa-solid <?= $icon ?>"></i></div>
            <div><h4><?= $title ?></h4><p><?= $desc ?></p></div>
          </div>
          <?php endforeach; ?>
        </div>
        <div style="margin-top:2rem; display:flex; gap:1rem; flex-wrap:wrap;">
          <a href="About_us.php" class="btn btn--primary"><i class="fa-solid fa-circle-info"></i> Learn About Us</a>
          <a href="consultation.php" class="btn btn--outline"><i class="fa-solid fa-phone"></i> Talk to Expert</a>
        </div>
      </div>
      <div class="why-image" style="display:block; margin-top:3rem;">
        <img src="assets/images/ocs.png" alt="Bluestone Overseas Award Winning Consultancy Event" style="border-radius:20px; width:100%; height:100%; object-fit:cover;">
        <div class="why-badge why-badge--tl"><i class="fa-solid fa-trophy"></i><span>Award Winning<br>Consultancy</span></div>
        <div class="why-badge why-badge--br"><i class="fa-solid fa-star"></i><span>Since 2015<br>Trusted Brand</span></div>
      </div>
    </div>
  </div>
</section>

<!-- PROCESS -->
<section class="section process-section">
  <div class="container">
    <div class="section__header animate-on-scroll">
      <span class="section__tag">How It Works</span>
      <h2 class="section__title">Your Journey to <span>Global Education</span></h2>
      <div class="accent-bar"></div>
    </div>
    <div class="process-steps">
      <?php
      $steps=[
        ['fa-comments','Free Counselling','Book a free session with our expert counsellor who assesses your profile and goals.','blue'],
        ['fa-magnifying-glass','Course & Country Selection','We shortlist the best universities and programs matching your ambitions and budget.','purple'],
        ['fa-file-contract','Application Filing','Our team prepares and submits your application with a flawless SOP and documents.','orange'],
        ['fa-passport','Visa Processing','Get expert help with student visa applications, ensuring all requirements are met.','teal'],
        ['fa-plane-departure','Fly Abroad!','Pre-departure briefing, accommodation guidance and you are off to your dream university!','pink'],
      ];
      foreach($steps as $i=>[$icon,$title,$desc,$color]):
      ?>
      <div class="process-step animate-on-scroll delay-<?= $i ?>">
        <div class="process-step__num"><?= $i+1 ?></div>
        <div class="stat-icon stat-icon--<?= $color ?>" style="margin: 0 auto 1.25rem;"><i class="fa-solid <?= $icon ?>"></i></div>
        <h4><?= $title ?></h4>
        <p><?= $desc ?></p>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- TEST PREP -->
<section class="section" style="background:linear-gradient(135deg,#f8fafc,#f0fdf4)">
  <div class="container">
    <div class="section__header animate-on-scroll">
      <span class="section__tag">Test Preparation</span>
      <h2 class="section__title">Ace Your <span>English Tests</span></h2>
      <p class="section__subtitle">Expert coaching for IELTS, TOEFL and PTE to maximise your scores and secure university offers.</p>
      <div class="accent-bar"></div>
    </div>
    <div class="test-cards">
      <?php
      try {
          $stmt = $pdo->query("SELECT * FROM test_preps WHERE is_active = 1 ORDER BY id ASC");
          $db_tests = $stmt->fetchAll();
      } catch (PDOException $e) {
          $db_tests = [];
      }
      
      if (empty($db_tests)) {
          $db_tests = [
              [
                  'slug' => 'ielts',
                  'name' => 'IELTS',
                  'icon' => 'fa-pen-to-square',
                  'description' => 'International English Language Testing System',
                  'feature1' => 'Band 7+ Achievers',
                  'feature2' => 'Expert Trainers',
                  'feature3' => 'Full Mock Tests',
                  'feature4' => 'Study Material Included',
                  'color' => 'blue'
              ]
          ];
      }
      
      foreach($db_tests as $test):
          $slug = clean_output($test['slug']);
          $name = clean_output($test['name']);
          $icon = clean_output($test['icon']);
          $desc = clean_output($test['description']);
          $color = clean_output($test['color']);
          
          $features = [];
          for ($f = 1; $f <= 4; $f++) {
              if (!empty($test["feature$f"])) {
                  $features[] = $test["feature$f"];
              }
          }
      ?>
      <div class="test-card animate-on-scroll" style="display: flex; flex-direction: column; overflow: hidden; border-radius: 16px; background: #fff; box-shadow: 0 10px 25px rgba(0,0,0,0.05); border: 1px solid rgba(0,0,0,0.05);">
        <?php if (!empty($test['image_path'])): ?>
          <div style="height: 160px; width: 100%; overflow: hidden;">
            <img src="<?= clean_output($test['image_path']) ?>" alt="<?= $name ?>" style="width: 100%; height: 100%; object-fit: cover;">
          </div>
        <?php endif; ?>
        <div class="test-card__header test-card__header--<?= $slug ?>" style="padding: 2rem 2rem 1rem; text-align: center; flex-grow: 1;">
          <?php if (empty($test['image_path'])): ?>
            <div class="stat-icon stat-icon--<?= $color ?>" style="margin: 0 auto 1.25rem;"><i class="fa-solid <?= $icon ?>"></i></div>
          <?php endif; ?>
          <h3 style="font-size: 1.5rem; font-weight: 700; color: #fff; margin-bottom: 0.5rem;"><?= $name ?></h3>
          <p style="color: #fff; font-size: 0.9rem; line-height: 1.6;"><?= $desc ?></p>
        </div>
        <div class="test-card__body" style="padding: 0 2rem 2rem; text-align: center;">
          <?php foreach($features as $f): ?>
          <div class="test-feature" style="display: flex; align-items: center; justify-content: center; gap: 0.5rem; margin-bottom: 0.4rem; color: var(--gray); font-size: 0.85rem;"><i class="fa-solid fa-check-circle" style="color: var(--success);"></i><span><?= clean_output($f) ?></span></div>
          <?php endforeach; ?>
          <a href="test-prep.php?t=<?= $slug ?>" class="btn btn--outline" style="width:100%;justify-content:center;margin-top:1.25rem">Learn More</a>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
<!-- GALLERY SECTION -->
<section class="section gallery-section" style="background:#f8fafc">
  <div class="container">
    <div class="section__header animate-on-scroll">
      <span class="section__tag">Success Stories</span>
      <h2 class="section__title">Glimpses of our <span>Success Events</span></h2>
      <div class="accent-bar"></div>
    </div>
    <div class="masonry-gallery animate-on-scroll delay-1">
      <?php
      try {
          $stmt = $pdo->query("SELECT * FROM gallery_items WHERE is_active = 1 ORDER BY id ASC LIMIT 5");
          $db_gallery = $stmt->fetchAll();
      } catch (PDOException $e) {
          $db_gallery = [];
      }
      
      if (empty($db_gallery)) {
          $db_gallery = [
              ['image_path' => 'assets/images/md-gallery5.png', 'title' => 'Student Seminar Event', 'category' => 'Events'],
              ['image_path' => 'assets/images/ias5.png', 'title' => 'IELTS Coaching Session', 'category' => 'Training'],
              ['image_path' => 'assets/images/start.png', 'title' => 'Pre-Departure Briefing', 'category' => 'Workshops'],
              ['image_path' => 'assets/images/img1.png', 'title' => 'Visa Success Meet', 'category' => 'Success'],
              ['image_path' => 'assets/images/placement.jpeg', 'title' => 'Placement Seminar', 'category' => 'Events']
          ];
      }
      
      foreach($db_gallery as $item):
      ?>
      <div class="masonry-item">
        <img src="<?= clean_output($item['image_path']) ?>" alt="<?= clean_output($item['title']) ?>">
        <div class="masonry-overlay">
          <div class="masonry-info">
            <span class="masonry-cat"><?= clean_output($item['category'] ?? 'Gallery') ?></span>
            <h4 class="masonry-title"><?= clean_output($item['title']) ?></h4>
          </div>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
    <div style="text-align:center; margin-top:2.5rem">
      <a href="gallery.php" class="btn btn--outline">View All Gallery <i class="fa-solid fa-images"></i></a>
    </div>
  </div>
</section>

<!-- CONTACT SECTION -->
<section class="section contact-section" id="contact-home" style="background:#fff">
  <div class="container">
    <div class="section__header animate-on-scroll">
      <span class="section__tag">Contact Us</span>
      <h2 class="section__title">Get a Free <span>Consultation</span></h2>
      <p class="section__subtitle">Reach out to our experts and start your journey today. We respond within 24 hours.</p>
      <div class="accent-bar"></div>
    </div>
    <div class="contact-grid">
      <div class="animate-on-scroll">
        <h3>Talk to Our <span class="text-gradient">Experts</span></h3>
        <p>Whether you&rsquo;re just starting your study abroad journey or need help with a visa application, our counsellors are here to help — for free.</p>
        <div class="contact-cards">
          <div class="contact-card">
            <div class="stat-icon stat-icon--blue" style="width:40px;height:40px;font-size:1rem"><i class="fa-solid fa-phone"></i></div>
            <div><h4>Call Us</h4><a href="tel:+919342899904">+91 93428 99904</a></div>
          </div>
          <div class="contact-card" style="border-left-color:var(--accent)">
            <div class="stat-icon stat-icon--pink" style="width:40px;height:40px;font-size:1rem"><i class="fa-solid fa-envelope"></i></div>
            <div><h4>Email Us</h4><a href="mailto:info@bluestoneocs.com">info@bluestoneocs.com</a></div>
          </div>
          <div class="contact-card" style="border-left-color:var(--teal)">
            <div class="stat-icon stat-icon--teal" style="width:40px;height:40px;font-size:1rem"><i class="fa-regular fa-clock"></i></div>
            <div><h4>Working Hours</h4><p>Mon–Fri: 09:00 AM – 6:30 PM</p></div>
          </div>
        </div>
        <a href="<?= SITE_MAP_LINK ?>" target="_blank" style="display:block;margin-top:2rem;padding:1.5rem;background:linear-gradient(135deg,rgba(14,165,233,.08),rgba(139,92,246,.08));border-radius:var(--radius);border:1px solid rgba(14,165,233,.15);text-decoration:none;color:inherit;transition:transform 0.3s ease,border-color 0.3s ease;" class="hover-scale-card">
          <h4 style="margin-bottom:1rem;display:flex;align-items:center;gap:0.5rem;"><i class="fa-solid fa-location-dot" style="color:var(--primary)"></i> Head Office – Coimbatore</h4>
          <p style="font-size:.875rem;color:var(--gray);line-height:1.7">Renaissance Terrace, NO.126L, 2nd Floor, Opp. Bishop Appasamy College, Coimbatore, TN - 641018</p>
        </a>
      </div>
      <div class="contact-form-wrap animate-on-scroll delay-1">
        <form id="contactHomeForm" onsubmit="return handleFormSubmit(event)">
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
            <select name="destination"><option value="">Select Country</option><option>USA</option><option>UK</option><option>Canada</option><option>Australia</option><option>Germany</option><option>Ireland</option><option>New Zealand</option><option>Singapore</option></select>
          </div>
          <div class="cf-group"><label>Your Message</label>
            <textarea name="query" rows="4" placeholder="How can we help you?"></textarea>
          </div>
          <button type="submit" class="btn btn--primary btn--lg" style="width:100%;justify-content:center">
            <i class="fa-solid fa-paper-plane"></i> Send Message
          </button>
        </form>
      </div>
    </div>
  </div>
</section>

<!-- VIDEO TESTIMONIALS -->
<section class="section testimonials-section" id="testimonials" style="background:#fff">
  <div class="container">
    <div class="section__header animate-on-scroll" style="text-align: center; margin-bottom: 3.5rem;">
      <span class="section__tag">Success Stories</span>
      <h2 class="section__title">Student <span>Video Reviews</span></h2>
      <p class="section__subtitle" style="margin: 0.5rem auto 0; max-width: 600px;">Hear directly from our successful scholars sharing their visa journey and university experiences.</p>
      <div class="accent-bar" style="margin: 1rem auto 0;"></div>
    </div>
    
    <div class="testimonial-showcase">
      <?php
      try {
          $stmt = $pdo->query("SELECT * FROM testimonial_videos WHERE is_active = 1 ORDER BY id DESC LIMIT 4");
          $db_videos = $stmt->fetchAll();
      } catch (PDOException $e) {
          $db_videos = [];
      }
      
      if (empty($db_videos)) {
          $db_videos = [
              ['student_name' => 'Sai Raksha Manoharan', 'details' => 'MSc in United Kingdom', 'youtube_url' => 'https://www.youtube.com/embed/dQw4w9WgXcQ'],
              ['student_name' => 'Ashok Saravanan', 'details' => 'MBA in Canada', 'youtube_url' => 'https://www.youtube.com/embed/dQw4w9WgXcQ'],
              ['student_name' => 'Priya Krishnamoorthy', 'details' => 'MS in United States', 'youtube_url' => 'https://www.youtube.com/embed/dQw4w9WgXcQ'],
              ['student_name' => 'Anish Kumar', 'details' => 'BE in Australia', 'youtube_url' => 'https://www.youtube.com/embed/dQw4w9WgXcQ']
          ];
      }
      
      $featured = $db_videos[0];
      $others = array_slice($db_videos, 1);
      ?>
      
      <!-- Featured Video -->
      <div class="t-featured animate-on-scroll">
        <div class="t-featured__video">
          <?php 
          $f_src = clean_output($featured['youtube_url']);
          $f_is_local = (strpos($f_src, 'uploads/') === 0);
          if ($f_is_local): 
          ?>
            <video src="<?= $f_src ?>" controls></video>
          <?php else: ?>
            <iframe src="<?= $f_src ?>" allowfullscreen></iframe>
          <?php endif; ?>
        </div>
        <div class="t-featured__info">
          <div class="visa-badge"><i class="fa-solid fa-circle-check"></i> Visa Approved</div>
          <h3 class="t-featured__name"><?= clean_output($featured['student_name']) ?></h3>
          <p style="color: rgba(255,255,255,0.8); display: flex; align-items: center; gap: 0.5rem;">
            <i class="fa-solid fa-user-graduate" style="color: var(--primary);"></i>
            <?= clean_output($featured['details']) ?>
          </p>
        </div>
      </div>

      <!-- Side List -->
      <div class="t-list">
        <?php foreach($others as $i => $video): 
            $v_src = clean_output($video['youtube_url']);
            // Extract YT ID for thumbnail
            $yt_id = "";
            if (preg_match('/embed\/([^\/\?]+)/', $v_src, $matches)) {
                $yt_id = $matches[1];
            }
            $thumb = !empty($yt_id) ? "https://img.youtube.com/vi/$yt_id/mqdefault.jpg" : "assets/images/logo.png";
        ?>
        <div class="t-item-small animate-on-scroll delay-<?= $i ?>" onclick="window.location.href='testimonial-videos.php'">
          <div class="t-item-small__thumb">
            <img src="<?= $thumb ?>" alt="<?= clean_output($video['student_name']) ?>">
            <i class="fa-solid fa-circle-play"></i>
          </div>
          <div class="t-item-small__content">
            <div class="visa-badge" style="padding: 0.15rem 0.4rem; font-size: 0.65rem;"><i class="fa-solid fa-circle-check"></i> Success</div>
            <h4 style="font-size: 1rem; font-weight: 700; color: var(--dark); margin: 0 0 0.2rem;"><?= clean_output($video['student_name']) ?></h4>
            <p style="font-size: 0.75rem; color: var(--gray); margin: 0;"><?= clean_output($video['details']) ?></p>
          </div>
        </div>
        <?php endforeach; ?>
        
        <a href="testimonial-videos.php" class="btn btn--ghost btn--sm" style="margin-top: 1rem; justify-content: center; border-color: var(--primary); color: var(--primary);">
          View All Stories <i class="fa-solid fa-arrow-right"></i>
        </a>
      </div>
    </div>
  </div>
</section>
<!-- CTA BANNER -->
<section class="cta-banner">
  <div class="container cta-banner__inner animate-on-scroll">
    <h2>Ready to Begin Your Global Education Journey?</h2>
    <p>Join 5,000+ students who transformed their future with Bluestone Overseas Consultants.<br>Book your FREE consultation today &mdash; no commitment required!</p>
    <div class="cta-buttons">
      <a href="consultation.php" class="btn btn--white btn--lg"><i class="fa-solid fa-calendar-check"></i> Book Free Consultation</a>
      <a href="tel:+919342899904" class="btn btn--ghost btn--lg"><i class="fa-solid fa-phone"></i> Call +91 93428 99904</a>
    </div>
  </div>
</section>

<!-- SPECIALIST SERVICES (PR, JOBS & VISITOR VISAS) -->
<section class="section" style="background:#f8fafc">
  <div class="container">
    <div class="section__header animate-on-scroll">
      <span class="section__tag">Global Opportunities</span>
      <h2 class="section__title">Specialist <span>Services</span></h2>
      <p class="section__subtitle">Explore our elite pathways for professional settlement, global careers, and stress-free international travel.</p>
      <div class="accent-bar"></div>
    </div>
    
    <div class="services-grid">
      <?php
      try {
          $stmt = $pdo->query("SELECT * FROM specialist_services WHERE is_active = 1 ORDER BY id ASC");
          $db_specs = $stmt->fetchAll();
      } catch (PDOException $e) {
          $db_specs = [];
      }
      
      foreach($db_specs as $spec):
          $color = clean_output($spec['color']);
          $icon = clean_output($spec['icon']);
          $title = clean_output($spec['title']);
          $tag = clean_output($spec['category_tag']);
          $desc = clean_output($spec['description']);
          $btn = clean_output($spec['button_text']);
          $link = clean_output($spec['button_link']);
          
          $bullets = [];
          for ($b = 1; $b <= 3; $b++) {
              if (!empty($spec["bullet$b"])) {
                  $bullets[] = $spec["bullet$b"];
              }
          }
          
          $sideColor = 'var(--primary)';
          $bgColor = 'rgba(239, 68, 68, 0.1)';
          if ($color === 'purple') {
              $sideColor = 'var(--accent)';
              $bgColor = 'rgba(139, 92, 246, 0.1)';
          } elseif ($color === 'orange') {
              $sideColor = '#f97316';
              $bgColor = 'rgba(249, 115, 22, 0.1)';
          } elseif ($color === 'teal') {
              $sideColor = '#14b8a6';
              $bgColor = 'rgba(20, 184, 166, 0.1)';
          } elseif ($color === 'pink') {
              $sideColor = '#ec4899';
              $bgColor = 'rgba(236, 72, 153, 0.1)';
          } elseif ($color === 'gold') {
              $sideColor = '#eab308';
              $bgColor = 'rgba(234, 179, 8, 0.1)';
          }
      ?>
      <div class="service-card-new animate-on-scroll" style="background: #fff; border-radius: 16px; padding: 2.25rem 2rem; box-shadow: 0 10px 30px rgba(0,0,0,0.04); border: 1px solid rgba(14, 165, 233, 0.08); display: flex; flex-direction: column; transition: all 0.3s ease; position: relative; overflow: hidden;">
        <div style="position: absolute; top: 0; left: 0; width: 4px; height: 100%; background: <?= $sideColor ?>;"></div>
        <div class="service-card-new__icon" style="width: 60px; height: 60px; border-radius: 12px; background: <?= $bgColor ?>; display: grid; place-items: center; font-size: 1.75rem; color: <?= $sideColor ?>; margin-bottom: 1.5rem;">
          <i class="fa-solid <?= $icon ?>"></i>
        </div>
        <span style="font-size: 0.75rem; text-transform: uppercase; font-weight: 700; color: #64748b; letter-spacing: 1px; margin-bottom: 0.5rem; display: block;"><?= $tag ?></span>
        <h3 style="font-size: 1.35rem; font-weight: 700; margin-bottom: 1rem; color: var(--dark);"><?= $title ?></h3>
        <p style="font-size: 0.9rem; color: #64748b; line-height: 1.6; margin-bottom: 1.5rem;"><?= $desc ?></p>
        <ul style="list-style: none; padding: 0; margin: 0 0 2rem 0; display: flex; flex-direction: column; gap: 0.75rem;">
          <?php foreach($bullets as $bText): ?>
            <li style="font-size: 0.85rem; color: #334155; display: flex; align-items: center; gap: 0.5rem;"><i class="fa-solid fa-circle-check" style="color: #22c55e;"></i> <?= clean_output($bText) ?></li>
          <?php endforeach; ?>
        </ul>
        <a href="<?= $link ?>" class="btn" style="margin-top: auto; justify-content: center; width: 100%; background: <?= $sideColor ?>; color: #fff;"><?= $btn ?> <i class="fa-solid fa-arrow-right"></i></a>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

</main>
<?php require_once 'includes/footer.php'; ?>
