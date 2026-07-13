<?php
require_once 'includes/config.php';

$country_slug = 'uk';

// Fetch from Database first
$db_country = null;
try {
    $stmt = $pdo->prepare("SELECT * FROM countries WHERE slug = :slug AND is_active = 1");
    $stmt->execute(['slug' => $country_slug]);
    $db_country = $stmt->fetch();
} catch (PDOException $e) {
    $db_country = null;
}

if (!isset($pageTitle)) {
    $pageTitle = 'Study Abroad with Bluestone Overseas - UK';
}
if (!isset($pageDesc)) {
    $pageDesc = 'Get expert guidance from university selection to visa approval.';
}

// Inject Google Tag specifically for the UK landing page
$extraCSS = ($extraCSS ?? '') . <<<HTML

<!-- Google tag (gtag.js) -->
<script async src="https://www.googletagmanager.com/gtag/js?id=AW-17848179717"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'AW-17848179717');
</script>
HTML;

require_once 'includes/header.php';

function get_uk_image_url($slug, $db_url) {
    if (!empty($db_url)) return $db_url;
    return 'assets/images/countries/uk.jpg'; // fallback
}
?>

<!-- Clean template specifically for the UK page -->
<style>
/* Feature Card Hover Effects */
.feature-card {
  position: relative;
  overflow: hidden;
  background: white;
  border-radius: 16px;
  box-shadow: 0 10px 40px -10px rgba(0,0,0,0.1);
  border: 1px solid rgba(0,0,0,0.05);
  text-align: center;
  transition: all 0.3s ease;
  height: 100%;
}
.feature-card:hover {
  transform: translateY(-8px);
}
.card-blue:hover { box-shadow: 0 20px 40px -10px rgba(59, 130, 246, 0.15); }
.card-purple:hover { box-shadow: 0 20px 40px -10px rgba(139, 92, 246, 0.15); }
.card-green:hover { box-shadow: 0 20px 40px -10px rgba(16, 185, 129, 0.15); }
.card-orange:hover { box-shadow: 0 20px 40px -10px rgba(245, 158, 11, 0.15); }
.card-red:hover { box-shadow: 0 20px 40px -10px rgba(239, 68, 68, 0.15); }

.feature-front {
  padding: 2rem 1.5rem;
  transition: opacity 0.3s ease, transform 0.3s ease;
  height: 100%;
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: center;
}
.feature-card:hover .feature-front {
  opacity: 0;
  transform: translateY(-20px);
}
.feature-back {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  padding: 1.5rem;
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: center;
  background: white;
  opacity: 0;
  transform: translateY(20px);
  transition: opacity 0.3s ease, transform 0.3s ease;
}
.feature-card:hover .feature-back {
  opacity: 1;
  transform: translateY(0);
}
.feature-back p {
  margin: 0;
  font-size: 0.9rem;
  line-height: 1.5;
  color: var(--text-secondary, #475569);
}
.feature-back h5 {
  font-size: 1.05rem;
  font-weight: 800;
  margin: 0 0 0.5rem 0;
  color: #0f172a;
}
.horizontal-card {
  display: flex;
  flex-direction: column;
  background: white;
  border-radius: 16px;
  overflow: hidden;
  box-shadow: 0 10px 30px rgba(0,0,0,0.05);
  transition: transform 0.3s ease, box-shadow 0.3s ease;
  border: 1px solid rgba(0,0,0,0.05);
}
@media (min-width: 768px) {
  .horizontal-card {
    flex-direction: row;
    align-items: stretch;
  }
}
.horizontal-card:hover {
  transform: translateY(-4px);
  box-shadow: 0 20px 40px rgba(0,0,0,0.1);
}
.horizontal-card__img {
  width: 100%;
  height: 200px;
  background-size: cover;
  background-position: center;
  position: relative;
}
@media (min-width: 768px) {
  .horizontal-card__img {
    width: 35%;
    height: auto;
    min-height: 250px;
  }
}
.horizontal-card__content {
  padding: 1.5rem;
  flex: 1;
  display: flex;
  flex-direction: column;
}
@media (min-width: 768px) {
  .horizontal-card__content {
    padding: 2rem 2.5rem;
  }
}
.horizontal-card__header {
  display: flex;
  flex-direction: column;
  gap: 1rem;
  margin-bottom: 1.5rem;
}
@media (min-width: 768px) {
  .horizontal-card__header {
    flex-direction: row;
    justify-content: space-between;
    align-items: flex-start;
  }
  }
}
details.faq-accordion {
  border: 1px solid #e2e8f0;
  border-radius: 12px;
  margin-bottom: 1rem;
  background: #f8fafc;
  overflow: hidden;
  transition: all 0.3s ease;
}
details.faq-accordion[open] {
  box-shadow: 0 4px 15px rgba(0,0,0,0.05);
  border-color: #cbd5e1;
}
details.faq-accordion summary {
  padding: 1.5rem;
  font-size: 1.1rem;
  font-weight: 700;
  color: #0f172a;
  cursor: pointer;
  list-style: none; /* Hide default marker */
  display: flex;
  justify-content: space-between;
  align-items: center;
}
details.faq-accordion summary::-webkit-details-marker {
  display: none;
}
details.faq-accordion summary::after {
  content: '\f078';
  font-family: 'Font Awesome 6 Free', 'FontAwesome', sans-serif;
  font-weight: 900;
  font-size: 0.9rem;
  color: #64748b;
  transition: transform 0.3s ease;
}
details.faq-accordion[open] summary::after {
  transform: rotate(180deg);
}
.faq-accordion-content {
  padding: 0 1.5rem 1.5rem 1.5rem;
  color: var(--text-secondary);
  font-size: 0.95rem;
  line-height: 1.6;
}

/* Hide navbar menu links and hamburger on this landing page */
.navbar__menu, .hamburger {
  display: none !important;
}

/* New University Card UI */
.uni-card {
  position: relative;
  border-radius: 16px;
  overflow: hidden;
  height: 320px;
  display: flex;
  flex-direction: column;
  justify-content: flex-end;
  box-shadow: 0 10px 30px rgba(0,0,0,0.1);
  transition: transform 0.4s cubic-bezier(0.4, 0, 0.2, 1), box-shadow 0.4s ease;
  cursor: pointer;
}

.uni-card:hover {
  transform: translateY(-8px);
  box-shadow: 0 25px 50px -12px rgba(0,0,0,0.25);
}

.uni-card__bg {
  position: absolute;
  top: 0; left: 0; right: 0; bottom: 0;
  background-size: cover;
  background-position: center;
  transition: transform 0.6s cubic-bezier(0.4, 0, 0.2, 1);
  z-index: 1;
}

.uni-card:hover .uni-card__bg {
  transform: scale(1.08);
}

.uni-card__overlay {
  position: absolute;
  top: 0; left: 0; right: 0; bottom: 0;
  background: linear-gradient(to top, rgba(15, 23, 42, 0.95) 0%, rgba(15, 23, 42, 0.5) 45%, transparent 100%);
  z-index: 2;
  transition: all 0.4s ease;
}

.uni-card:hover .uni-card__overlay {
  background: linear-gradient(to top, rgba(15, 23, 42, 0.95) 0%, rgba(15, 23, 42, 0.9) 70%, rgba(15, 23, 42, 0.4) 100%);
}

.uni-card__content {
  position: relative;
  z-index: 3;
  padding: 1.5rem;
  color: white;
  text-align: left;
}

.uni-card__title {
  font-size: 1.35rem;
  font-weight: 800;
  color: white;
  margin: 0;
  line-height: 1.3;
  transform: translateY(0);
  transition: transform 0.4s ease;
}

.uni-card__details {
  max-height: 0;
  opacity: 0;
  overflow: hidden;
  transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
  transform: translateY(20px);
  margin-top: 0;
}

.uni-card:hover .uni-card__details {
  max-height: 400px; /* arbitrary large value */
  opacity: 1;
  transform: translateY(0);
  margin-top: 1rem;
}

.uni-course-pill {
  display: inline-block;
  background: rgba(255,255,255,0.15);
  backdrop-filter: blur(4px);
  color: white;
  font-size: 0.8rem;
  padding: 0.4rem 0.8rem;
  border-radius: 8px;
  font-weight: 600;
  border: 1px solid rgba(255,255,255,0.1);
  margin-bottom: 0.5rem;
  margin-right: 0.5rem;
}
</style>

<main>
  <!-- 1, 2, 3: Hero Section (Headline, Subheading, Call-to-Action) -->
  <section class="page-hero" style="background-image: linear-gradient(rgba(15, 23, 42, 0.6), rgba(15, 23, 42, 0.6)), url('<?= get_uk_image_url($country_slug, $db_country['image_url'] ?? null) ?>'); background-size: cover; background-position: center;">
    <div class="container page-hero__inner">
      <div class="animate-on-scroll" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 3rem; align-items: center; width: 100%; text-align: left;">
        
        <div>
          <h1 style="text-align: left; margin-bottom: 0.25rem; line-height: 1.15;">Dreaming of Studying in the <span class="text-gradient">UK?</span></h1>
          <h2 style="color: #fbbf24; font-size: 1.5rem; margin: 0 0 0.5rem 0; font-weight: 700; text-align: left;">September 2026 Intake Applications Are Open!</h2>

          <p style="color:white; font-size: 1.25rem; font-weight: 600; margin-bottom: 1.25rem; margin-top: 1rem; text-align: left;">Start Your Application Today</p>
          
          <div style="display: flex; gap: 1rem; flex-wrap: wrap;">
            <a href="javascript:void(0)" onclick="openUkModal()" class="btn btn--primary" style="padding: 1rem 2.5rem; font-size: 1.1rem; box-shadow: 0 4px 15px rgba(236, 72, 153, 0.4);">Check Your Eligibility</a>
            <a href="https://wa.me/919342899904" target="_blank" class="btn" style="background:rgba(255,255,255,0.1); color:white; border:1px solid rgba(255,255,255,0.5); padding: 1rem 2.5rem; font-size: 1.1rem;">WhatsApp Us</a>
          </div>
        </div>

        <div style="background: white; border-radius: 20px; padding: 1.25rem 1.5rem; box-shadow: 0 25px 50px -12px rgba(0,0,0,0.25); text-align: left; max-width: 450px; margin-left: auto;">
          <h3 style="font-size: 1.35rem; font-weight: 800; color: #0f172a; margin: 0 0 0.15rem 0;">Get Expert UK Guidance!</h3>
          <p style="color: #64748b; font-size: 0.9rem; margin: 0 0 0.75rem 0;">Leave your details and our senior counsellors will contact you shortly.</p>
          
          <form id="ukHeroForm" onsubmit="return handleFormSubmit(event)" style="display: flex; flex-direction: column; gap: 0.75rem;">
            <input type="hidden" name="form_type" value="contact">
            <input type="hidden" name="destination" value="UK">
            <input type="hidden" name="query" value="Lead from UK Page Hero Form">
            
            <div>
              <label style="display: block; font-size: 0.8rem; font-weight: 600; color: #0f172a; margin-bottom: 0.25rem;">Full Name *</label>
              <input type="text" name="first_name" required style="width: 100%; padding: 0.6rem 0.8rem; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 0.95rem; outline: none; transition: border-color 0.2s; box-sizing: border-box;" onfocus="this.style.borderColor='#3b82f6';" onblur="this.style.borderColor='#cbd5e1';">
            </div>
            
            <div>
              <label style="display: block; font-size: 0.8rem; font-weight: 600; color: #0f172a; margin-bottom: 0.25rem;">Phone Number *</label>
              <input type="tel" name="phone" required style="width: 100%; padding: 0.6rem 0.8rem; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 0.95rem; outline: none; transition: border-color 0.2s; box-sizing: border-box;" onfocus="this.style.borderColor='#3b82f6';" onblur="this.style.borderColor='#cbd5e1';">
            </div>

            <div>
              <label style="display: block; font-size: 0.8rem; font-weight: 600; color: #0f172a; margin-bottom: 0.25rem;">Email Address *</label>
              <input type="email" name="email" required style="width: 100%; padding: 0.6rem 0.8rem; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 0.95rem; outline: none; transition: border-color 0.2s; box-sizing: border-box;" onfocus="this.style.borderColor='#3b82f6';" onblur="this.style.borderColor='#cbd5e1';">
            </div>
            
            <button type="submit" class="btn btn--primary" style="width: 100%; padding: 0.75rem; font-size: 1.05rem; margin-top: 0.25rem; justify-content: center; font-weight: 700;">
              Request Free Callback
            </button>
          </form>
        </div>

      </div>
    </div>
    <div class="hero-wave">
      <svg data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 120" preserveAspectRatio="none">
        <path d="M321.39,56.44c58-10.79,114.16-30.13,172-41.86,82.39-16.72,168.19-17.73,250.45-.39C823.78,31,906.67,72,985.66,92.83c70.05,18.48,146.53,26.09,214.34,3V0H0V27.35A600.21,600.21,0,0,0,321.39,56.44Z" fill="#ffffff" opacity="1"></path>
      </svg>
    </div>
  </section>

  <!-- PROMOTIONAL BANNER (September Intake) -->
  <section style="background: var(--uk-accent, #dc2626); color: white; padding: 2.5rem 0 5.5rem 0; position: relative; z-index: 10;">
    <div class="container">
      <div style="display: flex; flex-direction: row; flex-wrap: wrap; align-items: center; justify-content: space-between; gap: 2rem;">
        <div style="flex: 1; min-width: 300px;">
          <div style="display: inline-block; background: rgba(255,255,255,0.2); padding: 0.25rem 1rem; border-radius: 50px; font-weight: 700; font-size: 0.85rem; letter-spacing: 1px; text-transform: uppercase; margin-bottom: 0.75rem;">
            <i class="fa-solid fa-fire" style="color: #fbbf24;"></i> Admissions Open
          </div>
          <h2 style="font-size: 2rem; font-weight: 800; margin: 0 0 0.5rem 0; color: white;">September 2026 Intake</h2>
          <p style="font-size: 1.1rem; margin: 0; opacity: 0.9;">Secure your spot in top UK universities. Limited seats and scholarships available. Apply early to beat the rush!</p>
        </div>
        <div>
          <a href="javascript:void(0)" onclick="openUkModal()" class="btn" style="background: white; color: #dc2626; font-weight: 800; padding: 1rem 2.5rem; font-size: 1.1rem; border-radius: 50px; text-transform: uppercase; box-shadow: 0 10px 20px rgba(0,0,0,0.15);">
            Register Now <i class="fa-solid fa-arrow-right" style="margin-left: 0.5rem;"></i>
          </a>
        </div>
      </div>
    </div>
  </section>

  <!-- HIGHLIGHTS SECTION -->
  <section class="section" style="padding-top: 0; margin-top: -60px; position: relative; z-index: 10;">
    <div class="container">
      <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1.5rem; width: 100%;">
        
        <!-- Card 1 -->
        <div class="animate-on-scroll feature-card card-blue">
          <div class="feature-front">
            <div style="width: 64px; height: 64px; margin: 0 auto 1.25rem; background: linear-gradient(135deg, #eff6ff 0%, #bfdbfe 100%); color: #2563eb; border-radius: 16px; display: flex; align-items: center; justify-content: center; font-size: 1.75rem;">
              <i class="fa-solid fa-user-tie"></i>
            </div>
            <h4 style="font-weight: 800; font-size: 1.15rem; color: #0f172a; margin: 0;">Free Counselling</h4>
          </div>
          <div class="feature-back">
            <h5>Free Counselling</h5>
            <p>Get personalized advice from our expert counsellors to help you select the best course and university based on your profile.</p>
          </div>
        </div>

        <!-- Card 2 -->
        <div class="animate-on-scroll delay-1 feature-card card-purple">
          <div class="feature-front">
            <div style="width: 64px; height: 64px; margin: 0 auto 1.25rem; background: linear-gradient(135deg, #f5f3ff 0%, #ddd6fe 100%); color: #7c3aed; border-radius: 16px; display: flex; align-items: center; justify-content: center; font-size: 1.75rem;">
              <i class="fa-solid fa-building-columns"></i>
            </div>
            <h4 style="font-weight: 800; font-size: 1.15rem; color: #0f172a; margin: 0;">500+ UK Universities</h4>
          </div>
          <div class="feature-back">
            <h5>500+ UK Universities</h5>
            <p>Access a vast network of top-ranked institutions across the UK, offering thousands of diverse programs.</p>
          </div>
        </div>

        <!-- Card 3 -->
        <div class="animate-on-scroll delay-2 feature-card card-green">
          <div class="feature-front">
            <div style="width: 64px; height: 64px; margin: 0 auto 1.25rem; background: linear-gradient(135deg, #ecfdf5 0%, #a7f3d0 100%); color: #059669; border-radius: 16px; display: flex; align-items: center; justify-content: center; font-size: 1.75rem;">
              <i class="fa-solid fa-sack-dollar"></i>
            </div>
            <h4 style="font-weight: 800; font-size: 1.15rem; color: #0f172a; margin: 0;">Scholarships Up to £10,000*</h4>
          </div>
          <div class="feature-back">
            <h5>Scholarships</h5>
            <p>Maximize your chances of securing scholarships up to £10,000* to help fund your education and living expenses.</p>
          </div>
        </div>

        <!-- Card 4 -->
        <div class="animate-on-scroll delay-3 feature-card card-orange">
          <div class="feature-front">
            <div style="width: 64px; height: 64px; margin: 0 auto 1.25rem; background: linear-gradient(135deg, #fffbeb 0%, #fde68a 100%); color: #d97706; border-radius: 16px; display: flex; align-items: center; justify-content: center; font-size: 1.75rem;">
              <i class="fa-solid fa-passport"></i>
            </div>
            <h4 style="font-weight: 800; font-size: 1.15rem; color: #0f172a; margin: 0;">Visa Assistance</h4>
          </div>
          <div class="feature-back">
            <h5>Visa Assistance</h5>
            <p>Benefit from our high visa success rate with step-by-step guidance, documentation support, and interview preparation.</p>
          </div>
        </div>

        <!-- Card 5 -->
        <div class="animate-on-scroll feature-card card-red">
          <div class="feature-front">
            <div style="width: 64px; height: 64px; margin: 0 auto 1.25rem; background: linear-gradient(135deg, #fef2f2 0%, #fee2e2 100%); color: #dc2626; border-radius: 16px; display: flex; align-items: center; justify-content: center; font-size: 1.75rem;">
              <i class="fa-solid fa-list-check"></i>
            </div>
            <h4 style="font-weight: 800; font-size: 1.15rem; color: #0f172a; margin: 0;">Course & University Shortlisting</h4>
          </div>
          <div class="feature-back">
            <h5>Course Shortlisting</h5>
            <p>We analyze your academic background to shortlist the ideal courses and universities that align with your career goals.</p>
          </div>
        </div>

      </div>
    </div>
  </section>

  <!-- 4: Universities (Using dynamic DB pull for UK) -->
  <section class="section" style="background:#ffffff; padding-top: 5rem;">
    <div class="container">
      <div class="section__header animate-on-scroll">
        <span class="section__tag">Program Catalog</span>
        <h2 class="section__title">UK <span>Universities</span></h2>
        <p style="color:var(--gray); margin-top: 0.5rem;">Explore our leading partner institutions in the UK.</p>
        <div class="accent-bar"></div>
      </div>
      
      <div style="margin-top: 2rem;">
        <?php
        $unis_db = [];
        try {
            if ($db_country) {
                $stmtUnis = $pdo->prepare("SELECT * FROM universities WHERE country_id = :cid AND is_active = 1 ORDER BY name ASC");
                $stmtUnis->execute(['cid' => $db_country['id']]);
                $unis_db = $stmtUnis->fetchAll();
            }
        } catch (PDOException $e) {
            $unis_db = [];
        }
        
        if (empty($unis_db)): ?>
            <div style="text-align:center; padding: 4rem 2rem; background:#f8fafc; border-radius:20px; border:1px solid #e2e8f0;">
                <i class="fa-solid fa-school" style="font-size:3rem; color:var(--neon-blue); margin-bottom:1rem;"></i>
                <h4>Global Institutional Network Active</h4>
                <p style="color:var(--gray); max-width:500px; margin: 0.5rem auto 1.5rem;">We hold direct tie-ups with premier universities in the UK. Talk with an advisor to review all courses.</p>
                <a href="javascript:void(0)" onclick="openUkModal()" class="btn btn--primary">Get Curated Course List</a>
            </div>
        <?php else: ?>
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(min(100%, 340px), 1fr)); gap: 2rem;">
            <?php foreach ($unis_db as $uniIndex => $uni): ?>
                <div class="uni-card animate-on-scroll delay-<?= $uniIndex % 4 ?>" onclick="openUkModal()">
                    <div class="uni-card__bg" style="background-image: url('<?= !empty($uni['image_url']) ? clean_output($uni['image_url']) : 'assets/images/countries/uk.jpg' ?>');"></div>
                    <div class="uni-card__overlay"></div>
                    
                    <?php if (!empty($uni['qs_ranking'])): ?>
                    <div style="position: absolute; top: 1.5rem; right: 1.5rem; background: rgba(255, 255, 255, 0.95); color: #0f172a; font-weight: 800; font-size: 0.9rem; padding: 0.5rem 1rem; border-radius: 50px; box-shadow: 0 4px 10px rgba(0,0,0,0.2); z-index: 5;">
                        <i class="fa-solid fa-trophy" style="color: #f59e0b; margin-right: 0.25rem;"></i> QS: <?= clean_output($uni['qs_ranking']) ?>
                    </div>
                    <?php endif; ?>

                    <div class="uni-card__content">
                        <h3 class="uni-card__title">
                            <?= clean_output($uni['name']) ?>
                        </h3>
                        
                        <div class="uni-card__details">
                            <!-- Courses / Specializations -->
                            <?php 
                            $stmtCourses = $pdo->prepare("SELECT name FROM courses WHERE university_id = :uid AND is_active = 1 LIMIT 3");
                            $stmtCourses->execute(['uid' => $uni['id']]);
                            $real_courses = $stmtCourses->fetchAll(PDO::FETCH_COLUMN);
                            
                            if (!empty($real_courses)): 
                            ?>
                                <p style="font-size: 0.85rem; font-weight: 700; color: #cbd5e1; text-transform: uppercase; letter-spacing: 1px; margin: 0 0 0.75rem 0;">Programs Available</p>
                                <div>
                                    <?php foreach ($real_courses as $cname): ?>
                                    <span class="uni-course-pill">
                                        <?= clean_output($cname) ?>
                                    </span>
                                    <?php endforeach; ?>
                                </div>
                            <?php elseif (!empty($uni['specialization'])): ?>
                                <p style="font-size: 0.85rem; font-weight: 700; color: #cbd5e1; text-transform: uppercase; letter-spacing: 1px; margin: 0 0 0.75rem 0;">Top Specializations</p>
                                <div>
                                    <?php 
                                    $courses = array_slice(explode(',', $uni['specialization']), 0, 3);
                                    foreach ($courses as $course): 
                                        if (trim($course) == '') continue;
                                    ?>
                                    <span class="uni-course-pill">
                                        <?= clean_output(trim($course)) ?>
                                    </span>
                                    <?php endforeach; ?>
                                </div>
                            <?php else: ?>
                                <p style="font-size: 0.85rem; font-weight: 700; color: #cbd5e1; text-transform: uppercase; letter-spacing: 1px; margin: 0 0 0.75rem 0;">Programs Available</p>
                                <p style="font-size: 0.95rem; color: #f8fafc; margin: 0 0 1rem 0;">Undergraduate & Postgraduate programs available.</p>
                            <?php endif; ?>
                            
                            <button class="btn btn--primary" style="margin-top: 1rem; width: 100%; text-align: center; border-radius: 12px; pointer-events: none;">Apply Now</button>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
            </div>
        <?php endif; ?>
      </div>
    </div>
  </section>

  <!-- 5: Why Choose Bluestone Overseas? (Pillar Grid) -->
  <section class="section" style="background:#f8fafc; border-top:1px solid #e2e8f0;">
    <div class="container">
      <div class="section__header animate-on-scroll">
        <span class="section__tag">Your Trusted Partner</span>
        <h2 class="section__title">Why Choose <span>Bluestone Overseas?</span></h2>
        <div class="accent-bar"></div>
      </div>
      
      <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 2rem; margin-top: 3rem;">
        
        <div class="animate-on-scroll" style="background: white; border-radius: 16px; padding: 2rem; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03); border: 1px solid #f1f5f9; border-left: 4px solid #3b82f6; transition: all 0.3s ease;" onmouseover="this.style.transform='translateY(-5px)'; this.style.boxShadow='0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04)';" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03)';">
          <div style="width: 50px; height: 50px; border-radius: 12px; background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%); color: #2563eb; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; margin-bottom: 1.25rem;">
            <i class="fa-solid fa-users"></i>
          </div>
          <h4 style="font-size:1.25rem; font-weight:800; margin-bottom:0.75rem; color:#0f172a; letter-spacing: -0.025em;">Experienced Counsellors</h4>
          <p style="color:#64748b; line-height:1.7; font-size: 0.95rem; margin: 0;">Personalized counselling based on your academic profile and career goals.</p>
        </div>

        <div class="animate-on-scroll delay-1" style="background: white; border-radius: 16px; padding: 2rem; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03); border: 1px solid #f1f5f9; border-left: 4px solid #8b5cf6; transition: all 0.3s ease;" onmouseover="this.style.transform='translateY(-5px)'; this.style.boxShadow='0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04)';" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03)';">
          <div style="width: 50px; height: 50px; border-radius: 12px; background: linear-gradient(135deg, #f5f3ff 0%, #ede9fe 100%); color: #7c3aed; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; margin-bottom: 1.25rem;">
            <i class="fa-solid fa-globe"></i>
          </div>
          <h4 style="font-size:1.25rem; font-weight:800; margin-bottom:0.75rem; color:#0f172a; letter-spacing: -0.025em;">Wide University Network</h4>
          <p style="color:#64748b; line-height:1.7; font-size: 0.95rem; margin: 0;">Access to top-ranked universities across multiple countries globally.</p>
        </div>

        <div class="animate-on-scroll delay-2" style="background: white; border-radius: 16px; padding: 2rem; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03); border: 1px solid #f1f5f9; border-left: 4px solid #10b981; transition: all 0.3s ease;" onmouseover="this.style.transform='translateY(-5px)'; this.style.boxShadow='0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04)';" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03)';">
          <div style="width: 50px; height: 50px; border-radius: 12px; background: linear-gradient(135deg, #ecfdf5 0%, #d1fae5 100%); color: #059669; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; margin-bottom: 1.25rem;">
            <i class="fa-solid fa-award"></i>
          </div>
          <h4 style="font-size:1.25rem; font-weight:800; margin-bottom:0.75rem; color:#0f172a; letter-spacing: -0.025em;">Scholarship Assistance</h4>
          <p style="color:#64748b; line-height:1.7; font-size: 0.95rem; margin: 0;">Expert guidance to maximize your scholarship and funding opportunities.</p>
        </div>

        <div class="animate-on-scroll delay-3" style="background: white; border-radius: 16px; padding: 2rem; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03); border: 1px solid #f1f5f9; border-left: 4px solid #f59e0b; transition: all 0.3s ease;" onmouseover="this.style.transform='translateY(-5px)'; this.style.boxShadow='0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04)';" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03)';">
          <div style="width: 50px; height: 50px; border-radius: 12px; background: linear-gradient(135deg, #fffbeb 0%, #fef3c7 100%); color: #d97706; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; margin-bottom: 1.25rem;">
            <i class="fa-solid fa-file-signature"></i>
          </div>
          <h4 style="font-size:1.25rem; font-weight:800; margin-bottom:0.75rem; color:#0f172a; letter-spacing: -0.025em;">Admission Support</h4>
          <p style="color:#64748b; line-height:1.7; font-size: 0.95rem; margin: 0;">Application preparation, SOP review, LOR guidance, and verification.</p>
        </div>

        <div class="animate-on-scroll" style="background: white; border-radius: 16px; padding: 2rem; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03); border: 1px solid #f1f5f9; border-left: 4px solid #ef4444; transition: all 0.3s ease;" onmouseover="this.style.transform='translateY(-5px)'; this.style.boxShadow='0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04)';" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03)';">
          <div style="width: 50px; height: 50px; border-radius: 12px; background: linear-gradient(135deg, #fef2f2 0%, #fee2e2 100%); color: #dc2626; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; margin-bottom: 1.25rem;">
            <i class="fa-solid fa-plane-arrival"></i>
          </div>
          <h4 style="font-size:1.25rem; font-weight:800; margin-bottom:0.75rem; color:#0f172a; letter-spacing: -0.025em;">Visa Assistance</h4>
          <p style="color:#64748b; line-height:1.7; font-size: 0.95rem; margin: 0;">Expert visa filing with extensive mock interview preparation.</p>
        </div>

        <div class="animate-on-scroll delay-1" style="background: white; border-radius: 16px; padding: 2rem; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03); border: 1px solid #f1f5f9; border-left: 4px solid #14b8a6; transition: all 0.3s ease;" onmouseover="this.style.transform='translateY(-5px)'; this.style.boxShadow='0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04)';" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03)';">
          <div style="width: 50px; height: 50px; border-radius: 12px; background: linear-gradient(135deg, #f0fdfa 0%, #ccfbf1 100%); color: #0d9488; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; margin-bottom: 1.25rem;">
            <i class="fa-solid fa-building-columns"></i>
          </div>
          <h4 style="font-size:1.25rem; font-weight:800; margin-bottom:0.75rem; color:#0f172a; letter-spacing: -0.025em;">Education Loan Support</h4>
          <p style="color:#64748b; line-height:1.7; font-size: 0.95rem; margin: 0;">End-to-end assistance in obtaining education loans from leading banks.</p>
        </div>

        <div class="animate-on-scroll delay-2" style="background: white; border-radius: 16px; padding: 2rem; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03); border: 1px solid #f1f5f9; border-left: 4px solid #6366f1; transition: all 0.3s ease;" onmouseover="this.style.transform='translateY(-5px)'; this.style.boxShadow='0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04)';" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03)';">
          <div style="width: 50px; height: 50px; border-radius: 12px; background: linear-gradient(135deg, #eef2ff 0%, #e0e7ff 100%); color: #4f46e5; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; margin-bottom: 1.25rem;">
            <i class="fa-solid fa-suitcase-rolling"></i>
          </div>
          <h4 style="font-size:1.25rem; font-weight:800; margin-bottom:0.75rem; color:#0f172a; letter-spacing: -0.025em;">Pre-Departure Briefing</h4>
          <p style="color:#64748b; line-height:1.7; font-size: 0.95rem; margin: 0;">Prepare for your life abroad with comprehensive travel & settlement guidance.</p>
        </div>

        <div class="animate-on-scroll delay-3" style="background: white; border-radius: 16px; padding: 2rem; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03); border: 1px solid #f1f5f9; border-left: 4px solid #ec4899; transition: all 0.3s ease;" onmouseover="this.style.transform='translateY(-5px)'; this.style.boxShadow='0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04)';" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03)';">
          <div style="width: 50px; height: 50px; border-radius: 12px; background: linear-gradient(135deg, #fdf2f8 0%, #fce7f3 100%); color: #db2777; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; margin-bottom: 1.25rem;">
            <i class="fa-solid fa-house-chimney"></i>
          </div>
          <h4 style="font-size:1.25rem; font-weight:800; margin-bottom:0.75rem; color:#0f172a; letter-spacing: -0.025em;">Post-Arrival Support</h4>
          <p style="color:#64748b; line-height:1.7; font-size: 0.95rem; margin: 0;">We arrange airport pickup, accommodation guidance, and local networking.</p>
        </div>

      </div>
    </div>
  </section>

  <!-- 6: Admission Process -->
  <section class="section" style="background:#ffffff; border-top:1px solid #e2e8f0; border-bottom:1px solid #e2e8f0;">
    <div class="container">
      <div class="section__header animate-on-scroll">
        <span class="section__tag">Simple & Transparent</span>
        <h2 class="section__title">Our <span>Admission Process</span></h2>
        <div class="accent-bar"></div>
      </div>
      <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 2rem; margin-top: 3rem; text-align: center;">
        <div class="animate-on-scroll">
          <div style="width:70px; height:70px; background:#3b82f6; color:white; border-radius:50%; display:flex; align-items:center; justify-content:center; font-size:1.8rem; font-weight:800; margin:0 auto 1.5rem; box-shadow:0 10px 20px rgba(59,130,246,0.3);">1</div>
          <h4 style="font-weight:700; margin-bottom:0.5rem; color:#0f172a;">Profile Evaluation</h4>
          <p style="color:var(--text-secondary); font-size:0.95rem;">Free counselling to understand your background & goals.</p>
        </div>
        <div class="animate-on-scroll delay-1">
          <div style="width:70px; height:70px; background:#3b82f6; color:white; border-radius:50%; display:flex; align-items:center; justify-content:center; font-size:1.8rem; font-weight:800; margin:0 auto 1.5rem; box-shadow:0 10px 20px rgba(59,130,246,0.3);">2</div>
          <h4 style="font-weight:700; margin-bottom:0.5rem; color:#0f172a;">University Selection</h4>
          <p style="color:var(--text-secondary); font-size:0.95rem;">Shortlisting the best-fit institutions globally.</p>
        </div>
        <div class="animate-on-scroll delay-2">
          <div style="width:70px; height:70px; background:#3b82f6; color:white; border-radius:50%; display:flex; align-items:center; justify-content:center; font-size:1.8rem; font-weight:800; margin:0 auto 1.5rem; box-shadow:0 10px 20px rgba(59,130,246,0.3);">3</div>
          <h4 style="font-weight:700; margin-bottom:0.5rem; color:#0f172a;">Application & SOPs</h4>
          <p style="color:var(--text-secondary); font-size:0.95rem;">Handling all documentation & submission.</p>
        </div>
        <div class="animate-on-scroll delay-3">
          <div style="width:70px; height:70px; background:#3b82f6; color:white; border-radius:50%; display:flex; align-items:center; justify-content:center; font-size:1.8rem; font-weight:800; margin:0 auto 1.5rem; box-shadow:0 10px 20px rgba(59,130,246,0.3);">4</div>
          <h4 style="font-weight:700; margin-bottom:0.5rem; color:#0f172a;">Visa & Departure</h4>
          <p style="color:var(--text-secondary); font-size:0.95rem;">Mock interviews, visa filing & travel support.</p>
        </div>
      </div>
    </div>
  </section>

  <!-- 7: Student Testimonials -->
  <section class="section" style="background:#f8fafc; border-bottom:1px solid #e2e8f0;">
    <div class="container">
      <div class="section__header animate-on-scroll">
        <span class="section__tag">Student Testimonials</span>
        <h2 class="section__title">What Our <span>Students Say</span></h2>
        <div class="accent-bar"></div>
      </div>
      
      <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 2rem; margin-top: 2rem;" class="animate-on-scroll">
        <div style="background: white; border: 1px solid #e2e8f0; border-radius: 20px; padding: 2.5rem; text-align: left; box-shadow: 0 10px 30px rgba(0,0,0,0.03);">
          <div style="color:#fbbf24; margin-bottom:1rem; font-size:1.2rem;">
            <i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i>
          </div>
          <p style="font-size:1rem; line-height:1.6; color:var(--text-secondary); margin-bottom:1.5rem; font-style:italic;">"Bluestone Overseas made my UK admission and visa process completely stress-free. Their team guided me at every stage."</p>
          <h4 style="font-weight:700; color:#0f172a; margin:0;">— Arjun M.</h4>
        </div>
        
        <div style="background: white; border: 1px solid #e2e8f0; border-radius: 20px; padding: 2.5rem; text-align: left; box-shadow: 0 10px 30px rgba(0,0,0,0.03);">
          <div style="color:#fbbf24; margin-bottom:1rem; font-size:1.2rem;">
            <i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i>
          </div>
          <p style="font-size:1rem; line-height:1.6; color:var(--text-secondary); margin-bottom:1.5rem; font-style:italic;">"I received admission with a scholarship, and my visa was approved without any issues. Highly recommended!"</p>
          <h4 style="font-weight:700; color:#0f172a; margin:0;">— Janani S.</h4>
        </div>
        
        <div style="background: white; border: 1px solid #e2e8f0; border-radius: 20px; padding: 2.5rem; text-align: left; box-shadow: 0 10px 30px rgba(0,0,0,0.03);">
          <div style="color:#fbbf24; margin-bottom:1rem; font-size:1.2rem;">
            <i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i>
          </div>
          <p style="font-size:1rem; line-height:1.6; color:var(--text-secondary); margin-bottom:1.5rem; font-style:italic;">"Professional guidance, transparent process, and excellent support throughout. Couldn't have asked for more."</p>
          <h4 style="font-weight:700; color:#0f172a; margin:0;">— Mohammed R.</h4>
        </div>
      </div>
    </div>
  </section>

  <!-- 8: FAQs -->
  <section class="section" style="background:#ffffff;">
    <div class="container">
      <div class="section__header animate-on-scroll">
        <span class="section__tag">Got Questions?</span>
        <h2 class="section__title">Frequently Asked <span>Questions</span></h2>
        <div class="accent-bar"></div>
      </div>
      
      <div style="max-width: 800px; margin: 2rem auto 0;" class="animate-on-scroll">
        <details class="faq-accordion">
            <summary>Is it easy to get a UK student visa?</summary>
            <div class="faq-accordion-content">Yes, as long as you meet the academic requirements, have a CAS (Confirmation of Acceptance for Studies), and show sufficient funds, the visa process is very straightforward.</div>
        </details>
        <details class="faq-accordion">
            <summary>Can I work while studying in the UK?</summary>
            <div class="faq-accordion-content">Yes, international students are generally allowed to work up to 20 hours per week during term time and full-time during holidays.</div>
        </details>
        <details class="faq-accordion">
            <summary>Is there a post-study work visa in the UK?</summary>
            <div class="faq-accordion-content">Yes! The Graduate Route allows international students to stay and work in the UK for 2 years (or 3 years for PhD graduates) after completing their studies.</div>
        </details>
      </div>
    </div>
  </section>

  <!-- 9: Contact Section -->
  <section class="section" style="background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);">
    <div class="container">
      <div style="background: white; border-radius: 30px; padding: 4rem 2rem; text-align: center; box-shadow: 0 25px 50px -12px rgba(0,0,0,0.1); max-width: 900px; margin: 0 auto;" class="animate-on-scroll">
        <h2 style="font-size: 2.5rem; font-weight: 800; color: #0f172a; margin-bottom: 1rem;">Ready to Start Your Global Journey?</h2>
        <p style="font-size: 1.1rem; color: var(--text-secondary); margin-bottom: 2rem;">Take the first step towards a world-class education in the UK.</p>
        <div style="display: flex; gap: 1rem; justify-content: center; flex-wrap: wrap;">
          <a href="javascript:void(0)" onclick="openUkModal()" class="btn btn--primary" style="padding: 1rem 2rem; font-size: 1.1rem;">
            Apply Now
          </a>
          <a href="javascript:void(0)" onclick="openUkModal()" class="btn btn--primary" style="background: #0f172a; border-color: #0f172a; padding: 1rem 2rem; font-size: 1.1rem;">
            Talk to an Expert
          </a>
        </div>
      </div>
    </div>
  </section>

</main>

<!-- ENTRY POPUP MODAL -->
<div id="ukEntryModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; z-index: 9999; background: rgba(15, 23, 42, 0.85); backdrop-filter: blur(5px); align-items: center; justify-content: center; opacity: 0; transition: opacity 0.4s ease;">
  <div style="background: white; border-radius: 20px; width: 90%; max-width: 500px; position: relative; box-shadow: 0 25px 50px -12px rgba(0,0,0,0.25); transform: translateY(20px); transition: transform 0.4s ease;" id="ukModalContent">
    
    <!-- Close Button -->
    <button onclick="closeUkModal()" style="position: absolute; top: 1rem; right: 1rem; background: rgba(0,0,0,0.05); border: none; width: 32px; height: 32px; border-radius: 50%; display: flex; align-items: center; justify-content: center; cursor: pointer; color: #64748b; font-size: 1.2rem; transition: background 0.2s;">
      <i class="fa-solid fa-xmark"></i>
    </button>
    
    <!-- Modal Header -->
    <div style="padding: 2rem 2rem 1.5rem; text-align: center; border-bottom: 1px solid #e2e8f0;">
      <h3 style="font-size: 1.5rem; font-weight: 800; color: #0f172a; margin: 0 0 0.5rem 0;">Get Expert UK Guidance!</h3>
      <p style="color: #64748b; font-size: 0.95rem; margin: 0;">Leave your details and our senior counsellors will contact you shortly.</p>
    </div>
    
    <!-- Modal Body (Form) -->
    <div style="padding: 1.5rem 2rem 2rem;">
      <form id="ukPopupForm" onsubmit="return handleFormSubmit(event)" style="display: flex; flex-direction: column; gap: 1rem;">
        <input type="hidden" name="form_type" value="contact">
        <input type="hidden" name="destination" value="UK">
        <input type="hidden" name="query" value="Lead from UK Page Popup">
        
        <div>
          <label style="display: block; font-size: 0.85rem; font-weight: 600; color: #0f172a; margin-bottom: 0.25rem;">Full Name *</label>
          <input type="text" name="first_name" required style="width: 100%; padding: 0.75rem 1rem; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 1rem; outline: none; transition: border-color 0.2s;" onfocus="this.style.borderColor='#3b82f6';" onblur="this.style.borderColor='#cbd5e1';">
        </div>
        
        <div>
          <label style="display: block; font-size: 0.85rem; font-weight: 600; color: #0f172a; margin-bottom: 0.25rem;">Phone Number *</label>
          <input type="tel" name="phone" required style="width: 100%; padding: 0.75rem 1rem; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 1rem; outline: none; transition: border-color 0.2s;" onfocus="this.style.borderColor='#3b82f6';" onblur="this.style.borderColor='#cbd5e1';">
        </div>

        <div>
          <label style="display: block; font-size: 0.85rem; font-weight: 600; color: #0f172a; margin-bottom: 0.25rem;">Email Address *</label>
          <input type="email" name="email" required style="width: 100%; padding: 0.75rem 1rem; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 1rem; outline: none; transition: border-color 0.2s;" onfocus="this.style.borderColor='#3b82f6';" onblur="this.style.borderColor='#cbd5e1';">
        </div>
        
        <button type="submit" class="btn btn--primary" style="width: 100%; padding: 1rem; font-size: 1.1rem; margin-top: 0.5rem; justify-content: center; font-weight: 700;">
          Request Free Callback
        </button>
      </form>
    </div>
  </div>
</div>

<script>
  // Show modal function
  function openUkModal() {
    const modal = document.getElementById('ukEntryModal');
    const content = document.getElementById('ukModalContent');
    
    // Check if user has already seen it this session (optional, removing for strict "when enter into this link")
    // if (sessionStorage.getItem('ukModalSeen')) return;
    
    modal.style.display = 'flex';
    // Trigger reflow
    void modal.offsetWidth;
    
    modal.style.opacity = '1';
    content.style.transform = 'translateY(0)';
    
    // Mark as seen for this session
    sessionStorage.setItem('ukModalSeen', 'true');
  }

  // Close modal function
  function closeUkModal() {
    const modal = document.getElementById('ukEntryModal');
    const content = document.getElementById('ukModalContent');
    
    modal.style.opacity = '0';
    content.style.transform = 'translateY(20px)';
    
    setTimeout(() => {
      modal.style.display = 'none';
    }, 400); // match transition duration
  }

  // Fire the modal 1.5 seconds after page loads
  document.addEventListener("DOMContentLoaded", () => {
    setTimeout(openUkModal, 1500);
  });
</script>

<?php require_once 'includes/footer.php'; ?>
