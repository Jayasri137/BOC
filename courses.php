<?php
require_once 'includes/config.php';
$pageTitle = 'Best Courses to Study Abroad | Bluestone Overseas';
$pageDesc = 'Discover top undergraduate, postgraduate, diploma, and professional courses abroad.';
require_once 'includes/header.php';

// Fetch active countries for the filter
$countries = [];
try {
    $stmt = $pdo->query("SELECT id, name, flag, slug FROM countries WHERE is_active = 1 ORDER BY name ASC");
    $countries = $stmt->fetchAll();
} catch (PDOException $e) {
    // Silently fail or log
}

// Get selected country from GET
$selected_country_id = isset($_GET['country']) ? intval($_GET['country']) : 0;

// Fetch courses if country is selected
$universities_with_courses = [];
if ($selected_country_id > 0) {
    try {
        $stmt = $pdo->prepare("
            SELECT u.name as university_name, c.* 
            FROM courses c
            JOIN universities u ON c.university_id = u.id
            WHERE u.country_id = :cid AND c.is_active = 1 AND u.is_active = 1
            ORDER BY u.name ASC, c.name ASC
        ");
        $stmt->execute(['cid' => $selected_country_id]);
        $courses = $stmt->fetchAll();
        
        // Group by university
        foreach ($courses as $row) {
            $universities_with_courses[$row['university_name']][] = $row;
        }
    } catch (PDOException $e) {
        // Silently fail
    }
}
?>

<main>
<div class="container" style="padding-top: 2rem; padding-bottom: 1rem;"><h1 class="section__title" style="text-align:center; margin:0; font-size: 2.2rem;">Explore Courses for International Education</h1></div>

  <!-- DESTINATION FILTER SECTION -->
  <section class="section filter-section" style="padding-bottom: 2rem; background: #fff;">
    <div class="container">
      <div class="filter-card animate-on-scroll" style="background: white; padding: 2rem; border-radius: 20px; box-shadow: 0 10px 40px rgba(0,0,0,0.05); margin-top: -5rem; position: relative; z-index: 10; border: 1px solid rgba(0,0,0,0.02);">
        <form method="GET" action="courses.php" id="courseFilterForm">
          <div style="display: flex; align-items: flex-end; justify-content: space-between; flex-wrap: wrap; gap: 1.5rem;">
            <div style="flex: 1; min-width: 280px;">
              <label for="country_select" style="display: block; font-weight: 700; margin-bottom: 0.75rem; color: var(--dark); font-size: 0.95rem;">
                <i class="fa-solid fa-earth-americas text-primary" style="margin-right: 0.5rem;"></i> Choose Destination
              </label>
              <div style="position: relative;">
                <select name="country" id="country_select" class="form-control" style="appearance: none; background: #f8fafc; border: 2px solid #e2e8f0; border-radius: 12px; padding: 0.85rem 1.25rem; width: 100%; font-weight: 500; cursor: pointer; transition: all 0.3s ease;">
                  <option value="">-- All Destinations --</option>
                  <?php foreach ($countries as $c): ?>
                    <option value="<?= $c['id'] ?>" <?= $selected_country_id == $c['id'] ? 'selected' : '' ?>>
                      <?= clean_output($c['flag'] . ' ' . $c['name']) ?>
                    </option>
                  <?php endforeach; ?>
                </select>
                <i class="fa-solid fa-chevron-down" style="position: absolute; right: 1.25rem; top: 50%; transform: translateY(-50%); pointer-events: none; color: var(--gray); font-size: 0.8rem;"></i>
              </div>
            </div>
            <div style="display: flex; gap: 1rem; align-items: flex-end;">
               <button type="submit" class="btn btn--primary" style="padding: 0.85rem 2rem; border-radius: 12px; font-weight: 600;">
                  <i class="fa-solid fa-magnifying-glass"></i> Search Courses
               </button>
               <?php if($selected_country_id > 0): ?>
               <a href="courses.php" class="btn btn--ghost" style="padding: 0.85rem 1.5rem; border-radius: 12px; border: 2px solid #e2e8f0;">
                  <i class="fa-solid fa-rotate-left"></i> Reset
               </a>
               <?php endif; ?>
            </div>
          </div>
        </form>
      </div>
    </div>
  </section>

  <!-- COURSE LISTING SECTION -->
  <section class="section" style="padding-top: 2rem;">
    <div class="container">
      <?php if ($selected_country_id > 0): ?>
        <?php if (empty($universities_with_courses)): ?>
          <div class="text-center animate-on-scroll" style="padding: 4rem 1rem;">
            <div style="font-size: 4rem; color: #e2e8f0; margin-bottom: 1.5rem;"><i class="fa-solid fa-book-open"></i></div>
            <h3>No courses found for this destination yet.</h3>
            <p style="color: var(--gray); margin-top: 1rem;">Our team is constantly updating our database. Please check back soon or contact us for personalized guidance.</p>
            <a href="consultation.php" class="btn btn--primary" style="margin-top: 2rem;">Contact Advisor</a>
          </div>
        <?php else: ?>
          <div class="animate-on-scroll" style="margin-bottom: 3rem;">
            <h2 style="font-size: 1.75rem; color: var(--dark);">Available Programs in <span class="text-primary"><?php 
                $selected_country_name = '';
                foreach($countries as $c) if($c['id'] == $selected_country_id) $selected_country_name = $c['name'];
                echo clean_output($selected_country_name);
            ?></span></h2>
            <div class="accent-bar" style="margin-left: 0;"></div>
          </div>

          <?php foreach ($universities_with_courses as $uni_name => $uni_courses): ?>
            <div class="university-group animate-on-scroll" style="margin-bottom: 4rem;">
              <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 2rem; border-bottom: 2px solid #f1f5f9; padding-bottom: 1rem;">
                <div style="width: 45px; height: 45px; background: var(--primary); color: white; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.25rem;">
                  <i class="fa-solid fa-building-columns"></i>
                </div>
                <h3 style="font-size: 1.4rem; color: var(--dark);"><?= clean_output($uni_name) ?></h3>
              </div>

              <div class="grid grid--3 gap--2">
                <?php foreach ($uni_courses as $course): ?>
                  <div class="course-card" style="background: white; border-radius: 16px; border: 1px solid #f1f5f9; padding: 1.5rem; transition: all 0.3s ease; display: flex; flex-direction: column; height: 100%; box-shadow: 0 4px 15px rgba(0,0,0,0.02);" onmouseover="this.style.transform='translateY(-5px)'; this.style.boxShadow='0 12px 25px rgba(0,0,0,0.06)'; this.style.borderColor='var(--primary)';" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 15px rgba(0,0,0,0.02)'; this.style.borderColor='#f1f5f9';">
                    <div style="flex-grow: 1;">
                      <span style="display: inline-block; padding: 0.3rem 0.8rem; background: rgba(255,0,0,0.05); color: var(--primary); border-radius: 30px; font-size: 0.75rem; font-weight: 700; text-transform: uppercase; margin-bottom: 1rem; border: 1px solid rgba(255,0,0,0.1);">Study Program</span>
                      <h4 style="font-size: 1.15rem; color: var(--dark); margin-bottom: 1rem; line-height: 1.4;"><?= clean_output($course['name']) ?></h4>
                      
                      <div style="display: grid; grid-template-columns: 1fr; gap: 0.75rem; margin-top: 1.5rem;">
                        <div style="display: flex; align-items: center; gap: 0.6rem; color: var(--gray); font-size: 0.9rem;">
                          <i class="fa-regular fa-clock text-primary"></i>
                          <span><strong>Duration:</strong> <?= clean_output($course['duration'] ?: '2 Years') ?></span>
                        </div>
                        <div style="display: flex; align-items: center; gap: 0.6rem; color: var(--gray); font-size: 0.9rem;">
                          <i class="fa-solid fa-money-bill-wave text-primary"></i>
                          <span><strong>Tuition:</strong> <?= clean_output($course['tuition_fee'] ?: 'Variable') ?></span>
                        </div>
                        <div style="display: flex; align-items: center; gap: 0.6rem; color: var(--gray); font-size: 0.9rem;">
                          <i class="fa-solid fa-calendar-days text-primary"></i>
                          <span><strong>Intakes:</strong> <?= clean_output($course['intakes'] ?: 'Feb / Sept') ?></span>
                        </div>
                      </div>
                    </div>
                    
                    <div style="margin-top: 2rem; padding-top: 1.25rem; border-top: 1px solid #f1f5f9;">
                      <a href="consultation.php?course=<?= urlencode($course['name']) ?>" class="btn btn--primary btn--sm" style="width: 100%; border-radius: 10px;">Check Eligibility</a>
                    </div>
                  </div>
                <?php endforeach; ?>
              </div>
            </div>
          <?php endforeach; ?>
        <?php endif; ?>
      <?php else: ?>
        <!-- Default Content when no filter selected -->
        <div class="grid grid--2 gap--4 align-center">
          <div class="col-lg-6 mb-4 mb-lg-0 animate-on-scroll">
            <h1 class="section__title" style="text-align:left; margin-top:2rem">Find the Perfect <span>Program</span></h1>
            <p class="lead">Explore thousands of courses across top universities globally. Whether you are looking for STEM, Business, Arts, or Medicine, we have the right fit for your career goals.</p>
          </div>
          <div class="animate-on-scroll delay-1">
            <div class="service-details grid grid--1 gap--1">
              <div class="a-feat"><i class="fa-solid fa-check-circle"></i><span>Undergraduate &amp; Postgraduate Degrees</span></div>
              <div class="a-feat"><i class="fa-solid fa-check-circle"></i><span>Diploma &amp; Certificate Programs</span></div>
              <div class="a-feat"><i class="fa-solid fa-check-circle"></i><span>STEM, Business, Arts &amp; Humanities</span></div>
              <div class="a-feat"><i class="fa-solid fa-check-circle"></i><span>Course Advice &amp; Career Mapping</span></div>
            </div>
          </div>
        </div>

        <!-- PROCESS SECTION (moved inside the default state) -->
        <div style="margin-top: 5rem;">
          <div class="text-center animate-on-scroll">
            <span class="section__tag">Process</span>
            <h2 class="section__title">How It <span>Works</span></h2>
            <p class="section__subtitle" style="max-width: 600px; margin: 0 auto;">A streamlined, step-by-step approach to ensure your success.</p>
          </div>
          <div class="grid grid--4 gap--2" style="margin-top: 3rem;">
            <div class="service-card text-center animate-on-scroll">
              <div class="service-card__icon service-card__icon--blue" style="margin: 0 auto 1.5rem;"><i class="fa-solid fa-1"></i></div>
              <h3>Interests</h3>
              <p>We analyze your academic interests and long-term career goals.</p>
            </div>
            <div class="service-card text-center animate-on-scroll delay-1">
              <div class="service-card__icon service-card__icon--purple" style="margin: 0 auto 1.5rem;"><i class="fa-solid fa-2"></i></div>
              <h3>Filtering</h3>
              <p>We filter through thousands of courses based on your budget and preferences.</p>
            </div>
            <div class="service-card text-center animate-on-scroll delay-2">
              <div class="service-card__icon service-card__icon--orange" style="margin: 0 auto 1.5rem;"><i class="fa-solid fa-3"></i></div>
              <h3>Comparison</h3>
              <p>Compare course modules, faculty rankings, and post-study work opportunities.</p>
            </div>
            <div class="service-card text-center animate-on-scroll delay-3">
              <div class="service-card__icon service-card__icon--teal" style="margin: 0 auto 1.5rem;"><i class="fa-solid fa-4"></i></div>
              <h3>Selection</h3>
              <p>Finalize the best course that maximizes your Return on Investment (ROI).</p>
            </div>
          </div>
        </div>
      <?php endif; ?>
    </div>
  </section>

  <section class="section">
    <div class="container">
      <div class="text-center animate-on-scroll">
        <span class="section__tag">Benefits</span>
        <h2 class="section__title">Why Choose <span>Bluestone</span></h2>
        <p class="section__subtitle" style="max-width: 600px; margin: 0 auto;">Experience the advantage of working with industry-leading experts.</p>
      </div>
      <div class="grid grid--3 gap--2" style="margin-top: 3rem;">
        <div class="service-card animate-on-scroll">
          <h3 style="display: flex; align-items: center; gap: 0.5rem;"><i class="fa-solid fa-database text-primary"></i> Massive Database</h3>
          <p>Access an extensive, up-to-date database of global courses and curriculums.</p>
        </div>
        <div class="service-card animate-on-scroll delay-1">
          <h3 style="display: flex; align-items: center; gap: 0.5rem;"><i class="fa-solid fa-chalkboard-user text-primary"></i> Industry Aligned</h3>
          <p>We recommend courses that have high employability and industry demand.</p>
        </div>
        <div class="service-card animate-on-scroll delay-2">
          <h3 style="display: flex; align-items: center; gap: 0.5rem;"><i class="fa-solid fa-scale-balanced text-primary"></i> Unbiased Matching</h3>
          <p>Our sophisticated matching process ensures the course fits you, not the other way around.</p>
        </div>
      </div>
    </div>
  </section>

  <section class="section" style="padding-top: 0;">
    <div class="container animate-on-scroll">
      <div style="background: var(--gradient); padding: 4rem 2rem; border-radius: var(--radius-lg); text-align: center; color: white; box-shadow: var(--shadow-lg);">
        <h2 style="font-size: 2.5rem; margin-bottom: 1rem;">Ready to Start Your Global Journey?</h2>
        <p style="font-size: 1.1rem; opacity: 0.9; max-width: 600px; margin: 0 auto 2rem;">Join thousands of successful students who have achieved their dreams with Bluestone Overseas.</p>
        <a href="consultation.php" class="btn btn--white btn--lg pulse-btn" style="background: white; color: var(--primary);">Book Free Consultation</a>
      </div>
    </div>
  </section>
</main>
<?php require_once 'includes/footer.php'; ?>
