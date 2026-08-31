<?php
require_once 'includes/config.php';
$pageTitle = 'Best IELTS Coaching in Coimbatore | Language & Study Courses';
$pageDesc = 'Learn German language course and Japanese language course in Coimbatore with expert training, practical lessons and guidance for study and career opportunities.';
$pageKeywords = 'UK Education Consultants in Coimbatore, Australia Education Consultants in Coimbatore, New Zealand Education Consultants in Coimbatore, UG Programs Abroad, PG Programs Abroad, Study Abroad Consultants in Coimbatore, IELTS Coaching in Coimbatore, IELTS classes in Coimbatore, Best IELTS Coaching in Coimbatore, IELTS Training in Coimbatore, German language course, Japanese language course, German language classes, Japanese language classes, German Language Course in Coimbatore, Japanese Language Course in Coimbatore, German Language Training Centre in Coimbatore, Japanese Language Training Centre in Coimbatore, Postgraduate study in UK, Postgraduate study in Australia, Postgraduate study in New Zealand, Undergraduate study in Australia, Undergraduate study in UK, Undergraduate study in New Zealand, Postgraduate Study in UK – Coimbatore, Postgraduate Study in Australia – Coimbatore, Undergraduate Study in UK – Coimbatore, Undergraduate Study in Australia – Coimbatore, Postgraduate Study in New Zealand – Coimbatore, Undergraduate Study in New Zealand – Coimbatore';
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
  <!-- HERO SECTION (similar to student-counselling) -->
  <section class="section" style="position: relative; overflow: hidden; padding-top: 6rem; padding-bottom: <?php echo ($selected_country_id > 0) ? '8rem' : '5rem'; ?>; background-color: #ffffff;">
    <!-- Decorative background blobs -->
    <div style="position: absolute; top: -100px; left: -100px; width: 400px; height: 400px; background: radial-gradient(circle, rgba(236,72,153,0.1) 0%, transparent 70%); border-radius: 50%; z-index: -1;"></div>
    <div style="position: absolute; bottom: -50px; right: -50px; width: 300px; height: 300px; background: radial-gradient(circle, rgba(14,165,233,0.1) 0%, transparent 70%); border-radius: 50%; z-index: -1;"></div>

    <div class="container">
      <div style="text-align: center; max-width: 800px; margin: 0 auto; <?php echo ($selected_country_id > 0) ? 'margin-bottom: 2rem;' : 'margin-bottom: 4rem;'; ?>">
        <div class="animate-on-scroll">
          <div style="display: inline-flex; align-items: center; gap: 0.5rem; background: rgba(236, 72, 153, 0.1); color: var(--primary); padding: 0.5rem 1.25rem; border-radius: 50px; font-weight: 600; font-size: 0.95rem; margin-bottom: 1.5rem; border: 1px solid rgba(236, 72, 153, 0.2);">
            <i class="fa-solid fa-graduation-cap" style="color: #f59e0b;"></i> Global Education
          </div>
          <h2 style="font-size: clamp(2.5rem, 5vw, 4rem); line-height: 1.15; margin-bottom: 1.5rem; color: var(--dark);">
            Find the Perfect <br>
            <span style="color: #ea00ffff;">Program</span>
          </h2>
          <p style="color: var(--gray); font-size: 1.15rem; line-height: 1.7; margin-bottom: 2.5rem;">
            Explore thousands of courses across top universities globally. Whether you are looking for STEM, Business, Arts, or Medicine, we have the right fit for your career goals.
          </p>
        </div>
      </div>
      
      <?php if ($selected_country_id == 0): ?>
      <!-- New SVG Wave Feature Cards (only when no filter) -->
      <div class="animate-on-scroll delay-1" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 1.5rem;">
          <div class="wave-card wave-card-1">
            <div class="wave-card-top">
              <div class="wave-icon"><i class="fa-solid fa-graduation-cap"></i></div>
              <h3 class="wave-title">Degrees</h3>
            </div>
            <div class="wave-bg">
              <svg class="wave-svg" viewBox="0 0 1440 320" preserveAspectRatio="none">
                <path fill="var(--wave-fill)" fill-opacity="0.5" d="M0,160L48,144C96,128,192,96,288,106.7C384,117,480,171,576,170.7C672,171,768,117,864,106.7C960,96,1056,128,1152,133.3C1248,139,1344,117,1392,106.7L1440,96L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"></path>
                <path fill="var(--wave-fill)" fill-opacity="1" d="M0,224L48,213.3C96,203,192,181,288,181.3C384,181,480,203,576,218.7C672,235,768,245,864,229.3C960,213,1056,171,1152,170.7C1248,171,1344,213,1392,234.7L1440,256L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"></path>
              </svg>
              <div class="wave-tags">
                <span>UNDERGRAD</span>
                <span>POSTGRAD</span>
              </div>
              <div class="wave-content">
                <p>Comprehensive academic foundation for your future.</p>
                <div class="wave-arrow"><i class="fa-solid fa-arrow-right"></i></div>
              </div>
            </div>
          </div>
          <div class="wave-card wave-card-2">
            <div class="wave-card-top">
              <div class="wave-icon"><i class="fa-solid fa-certificate"></i></div>
              <h3 class="wave-title">Diplomas</h3>
            </div>
            <div class="wave-bg">
              <svg class="wave-svg" viewBox="0 0 1440 320" preserveAspectRatio="none">
                <path fill="var(--wave-fill)" fill-opacity="0.5" d="M0,160L48,144C96,128,192,96,288,106.7C384,117,480,171,576,170.7C672,171,768,117,864,106.7C960,96,1056,128,1152,133.3C1248,139,1344,117,1392,106.7L1440,96L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"></path>
                <path fill="var(--wave-fill)" fill-opacity="1" d="M0,224L48,213.3C96,203,192,181,288,181.3C384,181,480,203,576,218.7C672,235,768,245,864,229.3C960,213,1056,171,1152,170.7C1248,171,1344,213,1392,234.7L1440,256L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"></path>
              </svg>
              <div class="wave-tags">
                <span>SKILLS</span>
                <span>CERTIFICATE</span>
              </div>
              <div class="wave-content">
                <p>Specialized skill building programs designed for industry needs.</p>
                <div class="wave-arrow"><i class="fa-solid fa-arrow-right"></i></div>
              </div>
            </div>
          </div>
          <div class="wave-card wave-card-3">
            <div class="wave-card-top">
              <div class="wave-icon"><i class="fa-solid fa-flask"></i></div>
              <h3 class="wave-title">STEM & Arts</h3>
            </div>
            <div class="wave-bg">
              <svg class="wave-svg" viewBox="0 0 1440 320" preserveAspectRatio="none">
                <path fill="var(--wave-fill)" fill-opacity="0.5" d="M0,160L48,144C96,128,192,96,288,106.7C384,117,480,171,576,170.7C672,171,768,117,864,106.7C960,96,1056,128,1152,133.3C1248,139,1344,117,1392,106.7L1440,96L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"></path>
                <path fill="var(--wave-fill)" fill-opacity="1" d="M0,224L48,213.3C96,203,192,181,288,181.3C384,181,480,203,576,218.7C672,235,768,245,864,229.3C960,213,1056,171,1152,170.7C1248,171,1344,213,1392,234.7L1440,256L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"></path>
              </svg>
              <div class="wave-tags">
                <span>BUSINESS</span>
                <span>SCIENCE</span>
              </div>
              <div class="wave-content">
                <p>Explore thousands of disciplines from top global institutions.</p>
                <div class="wave-arrow"><i class="fa-solid fa-arrow-right"></i></div>
              </div>
            </div>
          </div>
          <div class="wave-card wave-card-4">
            <div class="wave-card-top">
              <div class="wave-icon"><i class="fa-solid fa-map"></i></div>
              <h3 class="wave-title">Career Advice</h3>
            </div>
            <div class="wave-bg">
              <svg class="wave-svg" viewBox="0 0 1440 320" preserveAspectRatio="none">
                <path fill="var(--wave-fill)" fill-opacity="0.5" d="M0,160L48,144C96,128,192,96,288,106.7C384,117,480,171,576,170.7C672,171,768,117,864,106.7C960,96,1056,128,1152,133.3C1248,139,1344,117,1392,106.7L1440,96L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"></path>
                <path fill="var(--wave-fill)" fill-opacity="1" d="M0,224L48,213.3C96,203,192,181,288,181.3C384,181,480,203,576,218.7C672,235,768,245,864,229.3C960,213,1056,171,1152,170.7C1248,171,1344,213,1392,234.7L1440,256L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"></path>
              </svg>
              <div class="wave-tags">
                <span>MAPPING</span>
                <span>GUIDANCE</span>
              </div>
              <div class="wave-content">
                <p>Personalized path to success with expert counseling.</p>
                <div class="wave-arrow"><i class="fa-solid fa-arrow-right"></i></div>
              </div>
            </div>
          </div>
      </div>
      <?php endif; ?>
    </div>
  </section>

  <style>
  /* WAVE FEATURE CARDS */
  .wave-card {
      position: relative;
      background: #fff;
      border-radius: 20px;
      overflow: hidden;
      box-shadow: 0 10px 30px rgba(0,0,0,0.08);
      display: flex;
      flex-direction: column;
      height: 380px;
      transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
      cursor: pointer;
  }
  .wave-card:hover {
      transform: translateY(-10px);
      box-shadow: 0 20px 40px rgba(0,0,0,0.15);
  }
  
  .wave-card-top {
      flex: 1;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: flex-start;
      padding-top: 3.5rem;
      z-index: 2;
  }
  .wave-icon {
      font-size: 3rem;
      color: var(--dark);
      margin-bottom: 1.5rem;
      transition: all 0.4s ease;
  }
  .wave-title {
      font-size: 1.3rem;
      font-weight: 700;
      color: var(--dark);
      text-align: center;
      margin: 0;
      padding: 0 1rem;
      transition: all 0.4s ease;
  }
  .wave-card:hover .wave-icon {
      transform: scale(1.1);
      color: var(--primary);
  }
  
  .wave-bg {
      position: absolute;
      bottom: 0;
      left: 0;
      width: 100%;
      height: 40%;
      background: var(--bg-grad);
      transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
      z-index: 1;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: flex-end;
      padding-bottom: 2rem;
  }
  .wave-card:hover .wave-bg {
      height: 65%;
  }
  
  .wave-svg {
      position: absolute;
      top: -30px;
      left: 0;
      width: 100%;
      height: 31px;
      transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
  }
  
  .wave-card-1 { --bg-grad: linear-gradient(to bottom, #d8b4e2, #b57bee); --wave-fill: #d8b4e2; }
  .wave-card-2 { --bg-grad: linear-gradient(to bottom, #fb923c, #ea580c); --wave-fill: #fb923c; }
  .wave-card-3 { --bg-grad: linear-gradient(to bottom, #2dd4bf, #0d9488); --wave-fill: #2dd4bf; }
  .wave-card-4 { --bg-grad: linear-gradient(to bottom, #60a5fa, #2563eb); --wave-fill: #60a5fa; }

  .wave-content {
      position: relative;
      z-index: 2;
      color: white;
      text-align: center;
      padding: 0 2rem;
      opacity: 0;
      transform: translateY(20px);
      transition: all 0.4s ease;
  }
  .wave-card:hover .wave-content {
      opacity: 1;
      transform: translateY(0);
      transition-delay: 0.1s;
  }
  .wave-tags {
      position: absolute;
      bottom: 2rem;
      left: 0;
      width: 100%;
      display: flex;
      justify-content: center;
      gap: 1.5rem;
      color: rgba(255,255,255,0.9);
      font-size: 0.85rem;
      font-weight: 700;
      text-transform: uppercase;
      letter-spacing: 1px;
      transition: all 0.3s ease;
  }
  .wave-card:hover .wave-tags {
      opacity: 0;
      transform: translateY(10px);
      pointer-events: none;
  }
  .wave-content p {
      font-size: 1rem;
      line-height: 1.5;
      margin-bottom: 1.5rem;
      font-weight: 500;
  }
  .wave-arrow {
      font-size: 1.5rem;
  }
  </style>

  <!-- DESTINATION FILTER SECTION -->
  <section class="section filter-section" style="padding-top: 0; padding-bottom: 2rem; background: transparent; margin-top: <?php echo ($selected_country_id > 0) ? '-6rem' : '3rem'; ?>; position: relative; z-index: 20;">
    <div class="container">
      <div class="filter-card filter-card-pad animate-on-scroll" style="background: white; padding: 2rem; border-radius: 20px; box-shadow: 0 10px 40px rgba(0,0,0,0.1); border: 1px solid rgba(0,0,0,0.05);">
        <form method="GET" action="courses.php" id="courseFilterForm">
          <div style="display: flex; align-items: flex-end; justify-content: space-between; flex-wrap: wrap; gap: 1.5rem;">
            <div style="flex: 1; min-width: 200px;">
              <label for="country_select" style="display: block; font-weight: 700; margin-bottom: 0.75rem; color: var(--dark); font-size: 0.95rem;">
                <i class="fa-solid fa-earth-americas text-primary" style="margin-right: 0.5rem;"></i> Choose Destination
              </label>
              <div style="position: relative;">
                <select name="country" id="country_select" class="form-control" style="appearance: none; background: #f8fafc; border: 2px solid #e2e8f0; border-radius: 12px; padding: 0.85rem 1.25rem; width: 100%; font-weight: 500; cursor: pointer; transition: all 0.3s ease;">
                  <option value="">-- All Destinations --</option>
                  <?php foreach ($countries as $c): ?>
                    <option value="<?= $c['id'] ?>" <?= $selected_country_id == $c['id'] ? 'selected' : '' ?>>
                      <?= clean_output($c['name']) ?>
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
  <?php if ($selected_country_id > 0): ?>
  <section class="section" style="padding-top: 2rem;">
    <div class="container">
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

              <style>
              .course-grid-bento {
                  display: grid;
                  grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
                  gap: 2rem;
              }
              
              .course-card-bento {
                  position: relative;
                  border-radius: 20px;
                  background: #fff;
                  box-shadow: 0 10px 30px rgba(0,0,0,0.05);
                  overflow: hidden;
                  display: flex;
                  flex-direction: column;
                  border: 1px solid #f1f5f9;
                  padding: 1.5rem;
                  transition: transform 0.4s ease, box-shadow 0.4s ease;
              }
              .course-card-bento:hover {
                  transform: translateY(-10px);
                  box-shadow: 0 20px 40px rgba(0,0,0,0.12);
                  border-color: rgba(236, 72, 153, 0.4);
              }
              </style>

              <div class="course-grid-bento">
                <?php 
                $wave_classes = ['wave-card-1', 'wave-card-2', 'wave-card-3', 'wave-card-4'];
                $w_idx = 0;
                foreach ($uni_courses as $course): 
                  $c_wave = $wave_classes[$w_idx % 4];
                  $w_idx++;
                ?>
                  <div class="wave-card <?= $c_wave ?>" style="height: 380px;">
                    <div class="wave-card-top" style="padding-top: 2rem;">
                      <div class="wave-icon" style="font-size: 2rem; margin-bottom: 1rem;"><i class="fa-solid fa-graduation-cap"></i></div>
                      <h4 class="wave-title" style="font-size: 1.15rem; line-height: 1.4;"><?= clean_output($course['name']) ?></h4>
                    </div>
                    <div class="wave-bg">
                      <svg class="wave-svg" viewBox="0 0 1440 320" preserveAspectRatio="none">
                        <path fill="var(--wave-fill)" fill-opacity="0.5" d="M0,160L48,144C96,128,192,96,288,106.7C384,117,480,171,576,170.7C672,171,768,117,864,106.7C960,96,1056,128,1152,133.3C1248,139,1344,117,1392,106.7L1440,96L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"></path>
                        <path fill="var(--wave-fill)" fill-opacity="1" d="M0,224L48,213.3C96,203,192,181,288,181.3C384,181,480,203,576,218.7C672,235,768,245,864,229.3C960,213,1056,171,1152,170.7C1248,171,1344,213,1392,234.7L1440,256L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"></path>
                      </svg>
                      <div class="wave-tags">
                        <span>COURSE</span>
                      </div>
                      <div class="wave-content" style="padding: 0 1.5rem; text-align: left; width: 100%;">
                        <div style="display: flex; flex-direction: column; gap: 0.6rem; margin-bottom: 1.5rem;">
                          <div style="display: flex; align-items: center; gap: 0.6rem; font-size: 0.9rem;">
                            <i class="fa-regular fa-clock" style="width: 20px; text-align: center;"></i> <strong>Duration:</strong> <?= clean_output($course['duration'] ?: '2 Years') ?>
                          </div>
                          <div style="display: flex; align-items: center; gap: 0.6rem; font-size: 0.9rem;">
                            <i class="fa-solid fa-money-bill-wave" style="width: 20px; text-align: center;"></i> <strong>Tuition:</strong> <?= clean_output($course['tuition_fee'] ?: 'Variable') ?>
                          </div>
                          <div style="display: flex; align-items: center; gap: 0.6rem; font-size: 0.9rem;">
                            <i class="fa-solid fa-calendar-days" style="width: 20px; text-align: center;"></i> <strong>Intakes:</strong> <?= clean_output($course['intakes'] ?: 'Feb / Sept') ?>
                          </div>
                        </div>
                        <div style="text-align: center;">
                          <a href="consultation.php?course=<?= urlencode($course['name']) ?>" class="btn" style="background: white; color: var(--wave-fill); padding: 0.5rem 1.5rem; border-radius: 50px; font-weight: 700; font-size: 0.9rem;">Check Eligibility</a>
                        </div>
                      </div>
                    </div>
                  </div>
                <?php endforeach; ?>
              </div>
            </div>
          <?php endforeach; ?>
        <?php endif; ?>
    </div>
  </section>
  <?php else: ?>

  <!-- PROCESS SECTION (default state) -->
  <section class="section bg-light" style="position: relative;">
    <div class="container">
      <div class="text-center animate-on-scroll">
        <span class="section__tag">Process</span>
        <h2 class="section__title">How It <span>Works</span></h2>
        <p class="section__subtitle" style="max-width: 600px; margin: 0 auto;">A streamlined, step-by-step approach to ensure your success.</p>
      </div>

      <div class="guide-timeline" style="margin-top: 4rem;">
          <?php
          $steps = [
              [
                  'num' => '01',
                  'title' => 'Interests',
                  'icon' => 'fa-clipboard-user',
                  'desc' => 'We analyze your academic interests and long-term career goals.',
                  'color' => 'orange',
                  'image' => 'assets/images/uni_data_3d.png'
              ],
              [
                  'num' => '02',
                  'title' => 'Filtering',
                  'icon' => 'fa-filter',
                  'desc' => 'We filter through thousands of courses based on your budget and preferences.',
                  'color' => 'teal',
                  'image' => 'assets/images/service_guidance_3d.png'
              ],
              [
                  'num' => '03',
                  'title' => 'Comparison',
                  'icon' => 'fa-scale-balanced',
                  'desc' => 'Compare course modules, faculty rankings, and post-study work opportunities.',
                  'color' => 'blue',
                  'image' => 'assets/images/service_university_3d.png'
              ],
              [
                  'num' => '04',
                  'title' => 'Selection',
                  'icon' => 'fa-bullseye',
                  'desc' => 'Finalize the best course that maximizes your Return on Investment (ROI).',
                  'color' => 'pink',
                  'image' => 'assets/images/uni_ranking_3d.png'
              ]
          ];

          foreach ($steps as $i => $step):
              $isEven = ($i % 2 !== 0);
          ?>
          <div class="guide-step-row <?= $isEven ? 'guide-step-row--reverse' : '' ?> animate-on-scroll">
              <div class="guide-step-content guide-step-content--<?= $step['color'] ?>">
                  <div class="guide-step-badge"><?= $step['num'] ?></div>
                  <h3><?= $step['title'] ?></h3>
                  <p><?= $step['desc'] ?></p>
                  <div class="guide-step-icon"><i class="fa-solid <?= $step['icon'] ?>"></i></div>
              </div>
              <div class="guide-step-visual">
                  <div class="guide-step-line"></div>
                  <div class="guide-step-dot"></div>
              </div>
              <div class="guide-step-image-col">
                  <img src="<?= $step['image'] ?>" alt="<?= $step['title'] ?>" class="guide-step-img">
              </div>
          </div>
          <?php endforeach; ?>
      </div>

  <section class="section">
    <div class="container">
      <div class="text-center animate-on-scroll">
        <span class="section__tag">Benefits</span>
        <h2 class="section__title">Why Choose <span>Bluestone</span></h2>
        <p class="section__subtitle" style="max-width: 600px; margin: 0 auto;">Experience the advantage of working with industry-leading experts.</p>
      </div>
      <style>
      .why-grid {
          display: grid;
          grid-template-columns: repeat(1, 1fr);
          gap: 2rem;
          margin-top: 3rem;
      }
      @media (min-width: 768px) {
          .why-grid {
              grid-template-columns: repeat(3, 1fr);
          }
      }
      .why-card {
          position: relative;
          margin-bottom: 2.5rem;
          display: block;
          text-decoration: none;
      }
      .why-card__img-wrapper {
          width: 100%;
          height: 250px;
          overflow: hidden;
      }
      .why-card__img {
          width: 100%;
          height: 100%;
          object-fit: cover;
          transition: transform 0.5s ease;
      }
      .why-card:hover .why-card__img {
          transform: scale(1.05);
      }
      .why-card__content {
          background: #ffffff;
          box-shadow: 0 10px 30px rgba(0,0,0,0.08);
          position: absolute;
          bottom: -2.5rem;
          left: 8%;
          right: 8%;
          width: 84%;
          padding: 1.5rem 1rem;
          text-align: center;
          z-index: 2;
          transition: transform 0.3s ease, box-shadow 0.3s ease;
      }
      .why-card:hover .why-card__content {
          transform: translateY(-5px);
          box-shadow: 0 15px 35px rgba(0,0,0,0.12);
      }
      .why-card__content h3 {
          font-size: 1.25rem;
          color: #17191c;
          margin-bottom: 0.5rem;
          font-weight: 700;
      }
      .why-card__content p {
          color: var(--gray);
          font-size: 0.95rem;
          margin-bottom: 0;
      }
      </style>
      <div class="why-grid">
          <!-- Benefit 1 -->
          <div class="why-card animate-on-scroll">
              <div class="why-card__img-wrapper">
                  <img src="assets/images/why_global.png" alt="Massive Database" class="why-card__img" onerror="this.src='assets/images/placeholder.jpg'">
              </div>
              <div class="why-card__content">
                  <h3>Massive Database</h3>
                  <p>Access an extensive, up-to-date database of global courses and curriculums.</p>
              </div>
          </div>

          <!-- Benefit 2 -->
          <div class="why-card animate-on-scroll delay-1">
              <div class="why-card__img-wrapper">
                  <img src="assets/images/why_expert.png" alt="Industry Aligned" class="why-card__img" onerror="this.src='assets/images/placeholder.jpg'">
              </div>
              <div class="why-card__content">
                  <h3>Industry Aligned</h3>
                  <p>We recommend courses that have high employability and industry demand.</p>
              </div>
          </div>

          <!-- Benefit 3 -->
          <div class="why-card animate-on-scroll delay-2">
              <div class="why-card__img-wrapper">
                  <img src="assets/images/why_unbiased.png" alt="Unbiased Matching" class="why-card__img" onerror="this.src='assets/images/placeholder.jpg'">
              </div>
              <div class="why-card__content">
                  <h3>Unbiased Matching</h3>
                  <p>Our sophisticated matching process ensures the course fits you, not the other way around.</p>
              </div>
          </div>
      </div>
    </div>
  </section>
  <?php endif; ?>

  <section class="section" style="padding-top: 3rem;">
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
