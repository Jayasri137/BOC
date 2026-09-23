<?php
require_once 'includes/config.php';
$pageTitle = 'Find Courses & Programs Abroad | Bluestone Overseas';
$pageDesc = 'Explore thousands of courses across top universities globally. Find the right degree for you with Bluestone\'s course finder.';
$pageKeywords = 'study abroad courses, find a course, universities abroad, overseas education, bachelor degree abroad, master degree abroad, IDP alternative';
require_once 'includes/header.php';

// Fetch active countries for the filter
$countries = [];
try {
    $stmt = $pdo->query("SELECT id, name, flag, slug FROM countries WHERE is_active = 1 ORDER BY name ASC");
    $countries = $stmt->fetchAll();
} catch (PDOException $e) {
    // Silently fail or log
}

// Get selected parameters from GET
$selected_country_id = isset($_GET['country']) ? intval($_GET['country']) : 0;
$search_query = isset($_GET['q']) ? trim($_GET['q']) : '';

// Fetch courses based on filters
$courses = [];
try {
    $query = "
        SELECT u.name as university_name, u.country_id, c.* 
        FROM courses c
        JOIN universities u ON c.university_id = u.id
        WHERE c.is_active = 1 AND u.is_active = 1
    ";
    
    $params = [];
    
    if ($selected_country_id > 0) {
        $query .= " AND u.country_id = :cid";
        $params['cid'] = $selected_country_id;
    }
    
    if (!empty($search_query)) {
        $query .= " AND (c.name LIKE :q OR u.name LIKE :q)";
        $params['q'] = '%' . $search_query . '%';
    }
    
    $query .= " ORDER BY c.name ASC LIMIT 100"; // Limit to prevent massive loads
    
    $stmt = $pdo->prepare($query);
    $stmt->execute($params);
    $courses = $stmt->fetchAll();
    
} catch (PDOException $e) {
    // Silently fail
}

// Helper to get country name
$selected_country_name = 'All Destinations';
if ($selected_country_id > 0) {
    foreach($countries as $c) {
        if($c['id'] == $selected_country_id) {
            $selected_country_name = $c['name'];
            break;
        }
    }
}
?>

<main class="page-courses">
    
  <!-- HERO SEARCH SECTION -->
  <section class="hero-search">
    <div class="hero-search__bg"></div>
    <div class="container hero-search__content">
      <h1 class="hero-search__title">Find a course</h1>
      <p class="hero-search__subtitle">
        Looking to study abroad and gain a degree overseas? Find the right degree for you with our course finder!
      </p>
      
      <!-- Search Form Widget -->
      <div class="search-widget">
        <form method="GET" action="courses.php" class="search-form">
          
          <!-- Subject Input -->
          <div class="search-input-group">
            <i class="fa-solid fa-magnifying-glass search-icon"></i>
            <div class="search-input-wrapper">
              <label>Subject or University</label>
              <input type="text" name="q" value="<?= htmlspecialchars($search_query) ?>" placeholder="e.g. Computer Science">
            </div>
          </div>
          
          <!-- Divider -->
          <div class="search-divider"></div>
          
          <!-- Destination Select -->
          <div class="search-input-group">
            <i class="fa-solid fa-location-dot search-icon"></i>
            <div class="search-input-wrapper">
              <label>Where to study</label>
              <select name="country">
                <option value="">All Destinations</option>
                <?php foreach ($countries as $c): ?>
                  <option value="<?= $c['id'] ?>" <?= $selected_country_id == $c['id'] ? 'selected' : '' ?>>
                    <?= clean_output($c['name']) ?>
                  </option>
                <?php endforeach; ?>
              </select>
            </div>
            <i class="fa-solid fa-chevron-down search-dropdown-icon"></i>
          </div>
          
          <!-- Submit Button -->
          <button type="submit" class="search-btn pulse-btn">Search</button>
        </form>
      </div>
    </div>
  </section>

  <!-- MAIN LAYOUT -->
  <section class="layout-main section">
    <div class="container layout-grid">
        
        <!-- LEFT SIDEBAR: FILTERS -->
        <aside class="layout-sidebar">
          <div class="sidebar-card">
            <div class="sidebar-header">
              <h3>Filters</h3>
              <?php if($selected_country_id > 0 || !empty($search_query)): ?>
              <a href="courses.php" class="clear-filters">Clear all</a>
              <?php endif; ?>
            </div>
            
            <form method="GET" action="courses.php" id="sidebarFilterForm">
              <?php if(!empty($search_query)): ?>
              <input type="hidden" name="q" value="<?= htmlspecialchars($search_query) ?>">
              <?php endif; ?>
              
              <!-- Destination Filter -->
              <div class="filter-group">
                <h4>Destination</h4>
                <div class="filter-options custom-scrollbar">
                  <label class="filter-label">
                    <input type="radio" name="country" value="" <?= $selected_country_id == 0 ? 'checked' : '' ?> onchange="this.form.submit()">
                    <span>All Destinations</span>
                  </label>
                  <?php foreach ($countries as $c): ?>
                  <label class="filter-label">
                    <input type="radio" name="country" value="<?= $c['id'] ?>" <?= $selected_country_id == $c['id'] ? 'checked' : '' ?> onchange="this.form.submit()">
                    <span class="filter-country-name">
                      <?php if($c['flag']): ?><img src="uploads/flags/<?= $c['flag'] ?>" alt=""><?php endif; ?>
                      <?= clean_output($c['name']) ?>
                    </span>
                  </label>
                  <?php endforeach; ?>
                </div>
              </div>
              
              <!-- Dummy Filters for UI aesthetic -->
              <div class="filter-group">
                <h4>Study Level</h4>
                <div class="filter-options">
                  <label class="filter-label"><input type="checkbox" disabled><span class="disabled-text">Undergraduate</span></label>
                  <label class="filter-label"><input type="checkbox" disabled><span class="disabled-text">Postgraduate</span></label>
                  <label class="filter-label"><input type="checkbox" disabled><span class="disabled-text">Doctorate</span></label>
                </div>
              </div>

              <div class="filter-actions">
                <button type="submit" class="apply-filters-btn">Apply Filters</button>
              </div>
            </form>
          </div>
        </aside>

        <!-- RIGHT MAIN AREA: COURSE LIST -->
        <div class="layout-content">
          
          <!-- Results Header -->
          <div class="results-header">
            <h2>
              <?php if(empty($courses)): ?>
                No courses found
              <?php else: ?>
                Showing <span><?= count($courses) ?></span> courses 
                <?php if($selected_country_id > 0): ?>in <strong><?= clean_output($selected_country_name) ?></strong><?php endif; ?>
                <?php if(!empty($search_query)): ?>for "<strong><?= htmlspecialchars($search_query) ?></strong>"<?php endif; ?>
              <?php endif; ?>
            </h2>
          </div>

          <!-- Course Cards -->
          <?php if (empty($courses)): ?>
            <div class="no-results-card">
              <div class="no-results-icon"><i class="fa-solid fa-search"></i></div>
              <h3>We couldn't find any exact matches</h3>
              <p>Try adjusting your search filters or try a different keyword.</p>
              <a href="courses.php" class="clear-search-btn">Clear Search</a>
            </div>
          <?php else: ?>
            <div class="course-list">
              <?php $c_idx = 0; foreach ($courses as $course): 
                $theme_class = 'theme-' . (($c_idx % 4) + 1);
                $c_idx++;
              ?>
                <div class="course-item <?= $theme_class ?>">
                  
                  <!-- Course Details -->
                  <div class="course-item__details">
                    <div class="course-badge">Course</div>
                    <h3 class="course-title"><?= clean_output($course['name']) ?></h3>
                    <p class="course-university">
                      <i class="fa-solid fa-university"></i>
                      <?= clean_output($course['university_name']) ?>
                      <?php if($selected_country_id == 0): ?>
                        <span class="divider">|</span>
                        <i class="fa-solid fa-location-dot"></i>
                        <?php 
                          foreach($countries as $c) {
                            if($c['id'] == $course['country_id']) echo clean_output($c['name']);
                          }
                        ?>
                      <?php endif; ?>
                    </p>
                    
                    <!-- Info Grid -->
                    <div class="course-info-grid">
                      <div class="info-cell">
                        <i class="fa-regular fa-clock"></i>
                        <div>
                          <p class="info-label">Duration</p>
                          <p class="info-val"><?= clean_output($course['duration'] ?: 'N/A') ?></p>
                        </div>
                      </div>
                      <div class="info-cell">
                        <i class="fa-solid fa-money-bill-wave"></i>
                        <div>
                          <p class="info-label">Tuition Fee</p>
                          <p class="info-val"><?= clean_output($course['tuition_fee'] ?: 'Variable') ?></p>
                        </div>
                      </div>
                      <div class="info-cell">
                        <i class="fa-regular fa-calendar"></i>
                        <div>
                          <p class="info-label">Intakes</p>
                          <p class="info-val"><?= clean_output($course['intakes'] ?: 'Flexible') ?></p>
                        </div>
                      </div>
                    </div>
                    
                    <!-- Action Area -->
                    <div class="course-item__actions">
                      <a href="consultation.php?course=<?= urlencode($course['name']) ?>&uni=<?= urlencode($course['university_name']) ?>" class="course-btn course-btn--primary">
                        Apply Now
                      </a>
                      <a href="contact.php" class="course-btn course-btn--outline">
                        Get Details
                      </a>
                    </div>
                  </div>
                </div>
              <?php endforeach; ?>
            </div>
            
            <!-- Pagination Placeholder -->
            <?php if(count($courses) == 100): ?>
            <div class="load-more-wrapper">
              <button class="load-more-btn">Load More Courses</button>
            </div>
            <?php endif; ?>
            
          <?php endif; ?>
          
        </div>
      </div>
    </div>
  </section>

  <!-- Consultation Form Section -->
  <section class="consultation-section" style="background-color: #fff7ed;">
    <div class="container consultation-container">
      <div class="landing-wrapper" style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem;">
        
        <!-- Left Form Area -->
        <div class="form-area">
          <h2 class="form-title" style="font-family: 'Plus Jakarta Sans', sans-serif; font-size: clamp(2rem, 5vw, 2.6rem); font-weight: 800; color: #0f172a; margin-bottom: 0.5rem; line-height: 1.2;"><span style="color: #ec4899;">Get FREE</span> <span style="color: #0ea5e9;">Counselling Today!</span></h2>
          <div style="width: 40px; height: 4px; background: #ea580c; margin-top: 10px; border-radius: 2px; margin-bottom: 1.5rem;"></div>
          <p class="form-desc" style="color: #475569; margin-bottom: 2.5rem; line-height: 1.6; font-size: 1.15rem; max-width: 90%;">Enter your details and our expert will reach out to you to discuss your plans. By the way, all our services are free!</p>
          
          <form id="counsellingForm" class="c-form">
            <input type="hidden" name="form_type" value="enquiry">
            
            <div class="fg-row">
              <div class="fg">
                <label>First name<span>*</span></label>
                <input type="text" name="first_name" class="c-input" required>
              </div>
              <div class="fg">
                <label>Last name<span>*</span></label>
                <input type="text" name="last_name" class="c-input" required>
              </div>
            </div>
            
            <div class="fg-row">
              <div class="fg">
                <label>Email address<span>*</span></label>
                <input type="email" name="email" class="c-input" required>
              </div>
              <div class="fg">
                <label>Phone Number<span>*</span></label>
                <input type="tel" name="phone" class="c-input" required pattern="[0-9]{10,15}" title="Please enter a valid phone number (10-15 digits)">
              </div>
            </div>

            <div class="fg">
              <label>Your City<span>*</span></label>
              <input type="text" name="city" class="c-input" required>
            </div>

            <div class="fg-row">
              <div class="fg">
                <label>Preferred Study Destination<span>*</span></label>
                <select name="study_destination" class="c-input" required>
                  <option value="">Select Country</option>
                  <?php foreach($countries as $c): ?>
                    <option value="<?= clean_output($c['name']) ?>"><?= clean_output($c['name']) ?></option>
                  <?php endforeach; ?>
                  <option value="Other">Other</option>
                </select>
              </div>
              <div class="fg">
                <label>Preferred mode of counselling<span>*</span></label>
                <select name="counselling_mode" class="c-input" required>
                  <option value="">Select</option>
                  <option value="In-Person (Office Visit)">In-Person (Office Visit)</option>
                  <option value="Virtual (Video Call)">Virtual (Video Call)</option>
                  <option value="Phone Call">Phone Call</option>
                </select>
              </div>
            </div>

            <div class="fg-row">
              <div class="fg">
                <label>Preferred study level<span>*</span></label>
                <select name="study_level" class="c-input" required>
                  <option value="">Select</option>
                  <option value="Undergraduate (Bachelors)">Undergraduate (Bachelors)</option>
                  <option value="Postgraduate (Masters)">Postgraduate (Masters)</option>
                  <option value="Doctorate (PhD)">Doctorate (PhD)</option>
                  <option value="Diploma / Certificate">Diploma / Certificate</option>
                </select>
              </div>
              <div class="fg">
                <label>How would you fund your education?<span>*</span></label>
                <select name="funding_mode" class="c-input" required>
                  <option value="">Select</option>
                  <option value="Self-Funded / Family">Self-Funded / Family</option>
                  <option value="Education Loan">Education Loan</option>
                  <option value="Seeking Scholarships">Seeking Scholarships</option>
                </select>
              </div>
            </div>
            
            <div class="checkbox-group">
              <label class="c-check">
                <input type="checkbox" required>
                <span>I agree to Bluestone <a href="terms.php" target="_blank">Terms</a> and <a href="privacy.php" target="_blank">privacy policy</a> *</span>
              </label>
              <label class="c-check">
                <input type="checkbox">
                <span>Please contact me by phone, email or SMS to assist with my enquiry</span>
              </label>
              <label class="c-check">
                <input type="checkbox">
                <span>I would like to receive updates and offers from Bluestone</span>
              </label>
            </div>
            
            <button type="submit" class="submit-btn" id="submitBtn">Avail FREE Counselling</button>
            <div id="formMsg"></div>
          </form>
        </div>

        <!-- Right Graphic Area -->
        <div class="graphic-area">
          <div class="graphic-img-wrap">
            <img src="assets/images/img4.png" alt="Student Counselling" class="graphic-img">
          </div>
        </div>

      </div>
    </div>
  </section>

  <style>
    /* PAGE SPECIFIC CSS TO MATCH IDP STYLE */
    .page-courses { background-color: #f8f9fa; }
    
    /* Hero Search */
    .hero-search { position: relative; padding: 1.5rem 1rem 1.5rem; background: #fff; overflow: hidden; border-bottom: 1px solid #edf2f7; text-align: center; }
    .hero-search__bg { position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: linear-gradient(135deg, rgba(14,165,233,0.05), rgba(236,72,153,0.05)); z-index: 0; }
    .hero-search__content { position: relative; z-index: 10; max-width: 900px; margin: 0 auto; }
    .hero-search__title { font-size: 2.5rem; font-weight: 700; color: #0ea5e9; margin-bottom: 0.5rem; }
    @media(min-width: 768px) { .hero-search__title { font-size: 3rem; } }
    .hero-search__subtitle { font-size: 1.125rem; color: #4a5568; margin-bottom: 1rem; }
    
    /* Search Widget */
    .search-widget { background: #fff; padding: 0.75rem; border-radius: 1rem; box-shadow: 0 10px 25px rgba(0,0,0,0.05); border: 1px solid #edf2f7; margin: 0 auto; }
    .search-form { display: flex; flex-direction: column; gap: 0.75rem; }
    @media(min-width: 768px) { .search-form { flex-direction: row; align-items: center; } }
    
    .search-input-group { flex: 1; display: flex; align-items: center; background: #f8fafc; border-radius: 0.75rem; padding: 0.75rem 1rem; border: 1px solid transparent; transition: all 0.2s; position: relative; }
    .search-input-group:focus-within { background: #fff; border-color: var(--primary); box-shadow: 0 0 0 3px rgba(236, 72, 153, 0.1); }
    .search-icon { color: #a0aec0; margin-right: 0.75rem; font-size: 1.1rem; }
    .search-input-wrapper { flex: 1; text-align: left; display: flex; flex-direction: column; }
    .search-input-wrapper label { font-size: 0.7rem; font-weight: 700; color: #718096; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.25rem; }
    .search-input-wrapper input, .search-input-wrapper select { background: transparent; border: none; padding: 0; font-size: 1rem; color: #2d3748; outline: none; width: 100%; cursor: pointer; }
    .search-input-wrapper select { appearance: none; }
    .search-dropdown-icon { position: absolute; right: 1rem; color: #a0aec0; font-size: 0.8rem; pointer-events: none; }
    
    .search-divider { display: none; width: 1px; height: 3rem; background: #edf2f7; }
    @media(min-width: 768px) { .search-divider { display: block; } }
    
    .search-btn { background: var(--primary); color: #fff; font-weight: 700; border: none; border-radius: 0.75rem; padding: 1rem 2rem; cursor: pointer; transition: background 0.2s; }
    .search-btn:hover { background: #d03d85; }
    
    /* Layout */
    .layout-grid { display: flex; flex-direction: column; gap: 2rem; margin-top: 2rem; }
    @media(min-width: 992px) { .layout-grid { flex-direction: row; } }
    .layout-sidebar { width: 100%; }
    @media(min-width: 992px) { .layout-sidebar { width: 25%; flex-shrink: 0; } }
    .layout-content { width: 100%; }
    @media(min-width: 992px) { .layout-content { width: 75%; } }
    
    /* Sidebar */
    .sidebar-card { background: #fff; border-radius: 1rem; padding: 1.5rem; border: 1px solid #edf2f7; position: sticky; top: 100px; }
    .sidebar-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; }
    .sidebar-header h3 { font-size: 1.125rem; font-weight: 700; margin: 0; color: #1a202c; }
    .clear-filters { font-size: 0.875rem; color: var(--primary); text-decoration: none; font-weight: 600; }
    .clear-filters:hover { text-decoration: underline; }
    
    .filter-group { margin-bottom: 1.5rem; padding-bottom: 1.5rem; border-bottom: 1px solid #edf2f7; }
    .filter-group h4 { font-size: 0.875rem; font-weight: 600; color: #2d3748; margin-bottom: 0.75rem; }
    .filter-options { display: flex; flex-direction: column; gap: 0.5rem; max-height: 200px; overflow-y: auto; }
    .filter-label { display: flex; align-items: center; gap: 0.5rem; cursor: pointer; }
    .filter-label input[type="radio"], .filter-label input[type="checkbox"] { accent-color: var(--primary); width: 1rem; height: 1rem; cursor: pointer; }
    .filter-label span { font-size: 0.875rem; color: #4a5568; transition: color 0.2s; }
    .filter-label:hover span { color: var(--primary); }
    .filter-country-name { display: flex; align-items: center; gap: 0.5rem; }
    .filter-country-name img { width: 1.25rem; height: 0.875rem; object-fit: cover; border-radius: 2px; }
    .disabled-text { color: #a0aec0 !important; cursor: not-allowed; }
    
    .apply-filters-btn { width: 100%; background: #1a202c; color: #fff; border: none; padding: 0.75rem; border-radius: 0.75rem; font-weight: 600; cursor: pointer; transition: background 0.2s; }
    .apply-filters-btn:hover { background: #2d3748; }
    
    /* Results Area */
    .results-header { background: #fff; padding: 1rem 1.25rem; border-radius: 0.75rem; border: 1px solid #edf2f7; margin-bottom: 1.5rem; display: flex; justify-content: space-between; align-items: center; }
    .results-header h2 { font-size: 1rem; color: #2d3748; margin: 0; font-weight: 500; }
    .results-header h2 span, .results-header h2 strong { color: var(--primary); font-weight: 700; }
    
    .no-results-card { background: #fff; border-radius: 1rem; border: 1px solid #edf2f7; padding: 3rem 1rem; text-align: center; }
    .no-results-icon { width: 4rem; height: 4rem; background: #f8fafc; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1rem; color: #cbd5e0; font-size: 1.5rem; }
    .no-results-card h3 { font-size: 1.25rem; color: #2d3748; margin-bottom: 0.5rem; }
    .no-results-card p { color: #718096; margin-bottom: 1.5rem; }
    .clear-search-btn { display: inline-block; background: var(--primary); color: #fff; font-weight: 600; padding: 0.75rem 1.5rem; border-radius: 0.75rem; text-decoration: none; }
    
    .course-list { display: grid; grid-template-columns: 1fr; gap: 1.5rem; }
    @media(min-width: 768px) { .course-list { grid-template-columns: repeat(2, 1fr); } }
    @media(min-width: 1024px) { .course-list { grid-template-columns: repeat(3, 1fr); } }
    
    .course-item { display: flex; flex-direction: column; background: #fff; border-radius: 1rem; border: 1px solid #edf2f7; overflow: hidden; transition: box-shadow 0.2s, border-color 0.2s; height: 100%; }
    .course-item:hover { box-shadow: 0 4px 15px rgba(0,0,0,0.05); }
    
    .theme-1 { --theme-color: #b57bee; --theme-bg: #f3e8f9; }
    .theme-2 { --theme-color: #ea580c; --theme-bg: #ffedd5; }
    .theme-3 { --theme-color: #0d9488; --theme-bg: #ccfbf1; }
    .theme-4 { --theme-color: #2563eb; --theme-bg: #dbeafe; }
    
    .course-item.theme-1:hover { border-color: #b57bee; box-shadow: 0 4px 15px rgba(181,123,238,0.15); }
    .course-item.theme-2:hover { border-color: #ea580c; box-shadow: 0 4px 15px rgba(234,88,12,0.15); }
    .course-item.theme-3:hover { border-color: #0d9488; box-shadow: 0 4px 15px rgba(13,148,136,0.15); }
    .course-item.theme-4:hover { border-color: #2563eb; box-shadow: 0 4px 15px rgba(37,99,235,0.15); }
    
    .course-item__details { padding: 1.5rem; flex: 1; display: flex; flex-direction: column; }
    .course-badge { display: inline-block; background: var(--theme-bg, #edf2f7); color: var(--theme-color, #4a5568); font-size: 0.65rem; font-weight: 700; text-transform: uppercase; padding: 0.35rem 0.6rem; border-radius: 0.25rem; margin-bottom: 0.75rem; align-self: flex-start; letter-spacing: 0.5px; }
    .course-title { font-size: 1.15rem; font-weight: 700; color: #1a202c; margin-bottom: 0.5rem; transition: color 0.2s; line-height: 1.4; }
    .course-item:hover .course-title { color: var(--theme-color, var(--primary)); }
    .course-university { font-size: 0.85rem; color: #4a5568; margin-bottom: 1rem; display: flex; align-items: center; gap: 0.5rem; flex-wrap: wrap; }
    .course-university i { color: #a0aec0; }
    .course-university .divider { color: #e2e8f0; margin: 0 0.25rem; }
    
    .course-info-grid { display: grid; grid-template-columns: 1fr; gap: 1rem; margin-top: auto; border-top: 1px dashed #edf2f7; padding-top: 1.25rem; }
    .course-item:hover .course-info-grid { display: none; }
    @media(min-width: 640px) { .course-info-grid { grid-template-columns: 1fr 1fr; } }
    .info-cell { display: flex; gap: 0.5rem; align-items: flex-start; }
    .info-cell i { color: var(--theme-color, var(--primary)); margin-top: 0.25rem; }
    .info-label { font-size: 0.7rem; font-weight: 600; color: #718096; text-transform: uppercase; margin: 0 0 0.1rem; }
    .info-val { font-size: 0.85rem; font-weight: 600; color: #2d3748; margin: 0; }
    
    .course-item__actions { margin-top: auto; padding-top: 1.25rem; display: none; flex-direction: column; justify-content: center; gap: 0.75rem; border-top: 1px dashed #edf2f7; }
    .course-item:hover .course-item__actions { display: flex; }
    .course-btn { text-align: center; padding: 0.75rem 1rem; border-radius: 0.75rem; font-weight: 600; text-decoration: none; font-size: 0.9rem; transition: all 0.2s; }
    .course-btn--primary { background: var(--theme-color, var(--primary)); color: #fff; border: none; }
    .course-btn--primary:hover { filter: brightness(0.9); box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
    .course-btn--outline { background: #fff; color: var(--theme-color, #4a5568); border: 1px solid var(--theme-color, #e2e8f0); }
    .course-btn--outline:hover { background: var(--theme-bg, #f7fafc); }
    
    .load-more-wrapper { text-align: center; margin-top: 2rem; }
    .load-more-btn { background: #fff; color: #4a5568; border: 1px solid #e2e8f0; padding: 0.75rem 2rem; border-radius: 0.75rem; font-weight: 600; cursor: pointer; transition: background 0.2s; }
    .load-more-btn:hover { background: #f7fafc; }
    
    /* Consultation Section (Matched to consultation.php) */
    .consultation-section { padding: 4rem 1rem; border-top: 1px solid #edf2f7; }
    .consultation-container { max-width: 1200px; margin: 0 auto; }
    
    .c-form { display: flex; flex-direction: column; gap: 0.85rem; width: 100%; }
    .fg-row { display: grid; grid-template-columns: 1fr; gap: 0.85rem; }
    @media(min-width: 640px) { .fg-row { grid-template-columns: 1fr 1fr; } }
    .fg { display: flex; flex-direction: column; gap: 0.3rem; }
    .fg label { font-size: 0.95rem; font-weight: 600; color: #5c4033; }
    .fg label span { color: #ef4444; }
    
    .c-input { padding: 0.7rem 0.9rem; border: 1px solid #cbd5e1; border-radius: 4px; font-family: inherit; font-size: 0.95rem; background: #ffffff; color: #0f172a; transition: all 0.3s; width: 100%; outline: none; }
    .c-input:focus { border-color: #ea580c; box-shadow: 0 0 0 3px rgba(234, 88, 12, 0.15); }
    
    .checkbox-group { display: flex; flex-direction: column; gap: 0.5rem; margin-top: 0.25rem; }
    .c-check { display: flex; align-items: flex-start; gap: 0.5rem; cursor: pointer; }
    .c-check input { margin-top: 0.2rem; width: 14px; height: 14px; accent-color: #ea580c; }
    .c-check span { font-size: 0.85rem; color: #475569; line-height: 1.4; }
    .c-check a { color: #ea580c; font-weight: 600; text-decoration: underline; }
    
    .submit-btn { margin-top: 0.75rem; background: #ea580c; color: #ffffff; border: none; padding: 0.9rem 2rem; font-size: 1.1rem; font-weight: 600; border-radius: 50px; cursor: pointer; width: fit-content; transition: all 0.3s; }
    .submit-btn:hover { background: #c2410c; transform: translateY(-2px); }
    
    .graphic-area { position: relative; display: flex; align-items: center; justify-content: center; padding: 0; }
    .graphic-img-wrap { width: 100%; max-width: 550px; }
    .graphic-img { width: 100%; object-fit: cover; mix-blend-mode: multiply; }
    
    #formMsg { display: none; margin-top: 1rem; padding: 1rem; border-radius: 8px; font-weight: 500; font-size: 0.95rem; }
    #formMsg.success { display: block; background: #dcfce7; color: #166534; border: 1px solid #bbf7d0; }
    #formMsg.error { display: block; background: #fee2e2; color: #991b1b; border: 1px solid #fecaca; }
    
    @media(max-width: 992px) {
      .landing-wrapper { grid-template-columns: 1fr !important; gap: 1rem !important; }
      .graphic-area { order: -1; }
      .submit-btn { width: 100%; text-align: center; }
    }
    
    /* Custom scrollbar for filter */
    .custom-scrollbar::-webkit-scrollbar { width: 4px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: #f1f5f9; border-radius: 4px; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 4px; }
  </style>

  <script>
  document.getElementById('counsellingForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const form = this;
    const btn = document.getElementById('submitBtn');
    const msg = document.getElementById('formMsg');
    const originalBtnHtml = btn.innerHTML;
    
    btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Processing...';
    btn.disabled = true;
    msg.className = '';
    msg.style.display = 'none';
    
    const formData = new FormData(form);
    
    fetch('submit-enquiry.php', {
      method: 'POST',
      body: formData
    })
    .then(response => response.json())
    .then(data => {
      msg.style.display = 'block';
      if(data.success) {
        msg.className = 'success';
        msg.innerHTML = '<i class="fa-solid fa-check-circle"></i> Thanks! Your counselling session request has been received. Our team will contact you shortly.';
        form.reset();
        btn.innerHTML = '<i class="fa-solid fa-check"></i> Requested';
      } else {
        msg.className = 'error';
        msg.innerHTML = '<i class="fa-solid fa-circle-exclamation"></i> ' + (data.error || 'Failed to submit request.');
        btn.innerHTML = originalBtnHtml;
        btn.disabled = false;
      }
    })
    .catch(err => {
      msg.style.display = 'block';
      msg.className = 'error';
      msg.innerHTML = '<i class="fa-solid fa-circle-exclamation"></i> Network error. Please try again.';
      btn.innerHTML = originalBtnHtml;
      btn.disabled = false;
    });
  });
  </script>

</main>
<?php require_once 'includes/footer.php'; ?>
