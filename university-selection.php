<?php
require_once 'includes/config.php';
$pageTitle = 'University Selection Guidance for Study Abroad';
$pageDesc = 'Get expert recommendations to select the best university based on your profile and goals.';
$pageHeroImage = 'assets/images/areowomen.png';
require_once 'includes/header.php';

// Fetch active countries for the filter
$countries = [];
try {
    $stmt = $pdo->query("SELECT id, name, flag, slug FROM countries WHERE is_active = 1 ORDER BY name ASC");
    $countries = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    // Silently fail
}

// Get selected parameters from GET
$selected_country_id = isset($_GET['country_id']) ? intval($_GET['country_id']) : 0;
$search_query = isset($_GET['q']) ? trim($_GET['q']) : '';

// Fetch universities
$universities_list = [];
try {
    $sql = "SELECT u.*, c.name as country_name 
            FROM universities u
            LEFT JOIN countries c ON u.country_id = c.id
            WHERE u.is_active = 1";
    $params = [];
    
    if ($selected_country_id > 0) {
        $sql .= " AND u.country_id = :cid";
        $params['cid'] = $selected_country_id;
    }
    
    if (!empty($search_query)) {
        $sql .= " AND (u.name LIKE :q OR u.specialization LIKE :q)";
        $params['q'] = "%{$search_query}%";
    }
    
    $sql .= " ORDER BY u.name ASC";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $universities_list = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    // Silently fail
}
?>
<main class="page-university-selection">
  <style>
    :root {
      --primary: #ec4899;
      --secondary: #0ea5e9;

    }
    
    .page-university-selection { background-color: #f8f9fa; min-height: 100vh; color: #1a202c; }
    .dark-search-section { background-color: #152c5fff; color: #fff; padding-bottom: 2rem; }
    
    /* Hero Search */
    .hero-search { position: relative; padding: 4rem 1rem 2rem; text-align: center; }
    .hero-search__content { position: relative; z-index: 10; max-width: 900px; margin: 0 auto; }
    .hero-search__title { font-size: 2.5rem; font-weight: 800; color: #fff; margin-bottom: 0.5rem; }
    @media(min-width: 768px) { .hero-search__title { font-size: 3.5rem; } }
    .hero-search__subtitle { font-size: 1.125rem; color: #cbd5e1; margin-bottom: 2rem; }
    
    /* Search Widget */
    .search-widget { background: #fff; padding: 0.75rem; border-radius: 1rem; box-shadow: 0 10px 25px rgba(0,0,0,0.2); margin: 0 auto; }
    .search-form { display: flex; flex-direction: column; gap: 0.5rem; }
    @media(min-width: 768px) { .search-form { flex-direction: row; } }
    .search-input-group { position: relative; flex: 1; display: flex; align-items: center; background: #f8fafc; border-radius: 0.75rem; padding: 0.5rem 1rem; transition: background 0.2s; }
    .search-input-group:focus-within { background: #fff; box-shadow: inset 0 0 0 2px rgba(14,165,233,0.2); }
    .search-icon { color: #94a3b8; font-size: 1.25rem; margin-right: 0.75rem; }
    .search-input-wrapper { flex: 1; display: flex; flex-direction: column; text-align: left; }
    .search-label { font-size: 0.65rem; font-weight: 700; color: #64748b; text-transform: uppercase; margin-bottom: 0.2rem; }
    .search-input { width: 100%; border: none; background: transparent; font-size: 1rem; color: #1e293b; outline: none; padding: 0; margin: 0; font-family: inherit; }
    .search-input::placeholder { color: #94a3b8; }
    .search-select { width: 100%; border: none; background: transparent; font-size: 1rem; color: #1e293b; outline: none; padding: 0; margin: 0; font-family: inherit; cursor: pointer; appearance: none; }
    .search-divider { display: none; width: 1px; background: #e2e8f0; margin: 0 0.5rem; }
    @media(min-width: 768px) { .search-divider { display: block; } }
    .search-btn { background: var(--primary); color: #fff; border: none; padding: 1rem 2.5rem; border-radius: 0.75rem; font-weight: 700; font-size: 1rem; cursor: pointer; transition: all 0.2s; white-space: nowrap; }
    .search-btn:hover { background: #d03d85; box-shadow: 0 4px 12px rgba(236,72,153,0.3); }
    
    /* Layout */
    .uni-container { max-width: 1400px; margin: 0 auto; padding: 2rem 1rem 4rem; }
    .uni-layout { display: flex; flex-direction: column; gap: 2rem; }
    
    /* Main Content */
    .uni-main { flex: 1; min-width: 0; }
    .results-header { display: flex; flex-direction: column; gap: 1rem; margin-bottom: 2rem; }
    @media(min-width: 640px) { .results-header { flex-direction: row; justify-content: space-between; align-items: center; } }
    .results-count { font-size: 1.25rem; font-weight: 700; color: #fff; margin: 0; }
    .results-count span { color: var(--secondary); }
    
    /* University Cards */
    .no-results-card { background: rgba(255,255,255,0.05); border-radius: 1rem; border: 1px dashed rgba(255,255,255,0.2); padding: 4rem 2rem; text-align: center; }
    .no-results-icon { font-size: 3rem; color: rgba(255,255,255,0.2); margin-bottom: 1rem; }
    .no-results-card h3 { color: #fff; margin-bottom: 0.5rem; }
    .no-results-card p { color: #cbd5e1; margin-bottom: 1.5rem; }
    .clear-search-btn { display: inline-block; background: var(--primary); color: #fff; font-weight: 600; padding: 0.75rem 1.5rem; border-radius: 0.75rem; text-decoration: none; }
    
    .uni-list { display: grid; grid-template-columns: 1fr; gap: 1.5rem; }
    @media(min-width: 640px) { .uni-list { grid-template-columns: repeat(2, 1fr); } }
    @media(min-width: 992px) { .uni-list { grid-template-columns: repeat(3, 1fr); } }
    @media(min-width: 1200px) { .uni-list { grid-template-columns: repeat(4, 1fr); } }
    
    .uni-item { display: flex; flex-direction: column; background: #fff; border-radius: 1rem; border: 1px solid #edf2f7; overflow: hidden; transition: box-shadow 0.2s, border-color 0.2s; height: 100%; position: relative; }
    .uni-item:hover { box-shadow: 0 4px 15px rgba(0,0,0,0.05); }
    
    .theme-1 { --theme-color: #b57bee; --theme-bg: #f3e8f9; }
    .theme-2 { --theme-color: #ea580c; --theme-bg: #ffedd5; }
    .theme-3 { --theme-color: #0d9488; --theme-bg: #ccfbf1; }
    .theme-4 { --theme-color: #2563eb; --theme-bg: #dbeafe; }
    
    .uni-item.theme-1:hover { border-color: #b57bee; box-shadow: 0 4px 15px rgba(181,123,238,0.15); }
    .uni-item.theme-2:hover { border-color: #ea580c; box-shadow: 0 4px 15px rgba(234,88,12,0.15); }
    .uni-item.theme-3:hover { border-color: #0d9488; box-shadow: 0 4px 15px rgba(13,148,136,0.15); }
    .uni-item.theme-4:hover { border-color: #2563eb; box-shadow: 0 4px 15px rgba(37,99,235,0.15); }
    
    .uni-item__details { padding: 1.5rem; flex: 1; display: flex; flex-direction: column; }
    .uni-logo-box { width: 50px; height: 50px; background: var(--theme-bg, #f8fafc); border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.25rem; color: var(--theme-color, var(--primary)); margin-bottom: 1rem; }
    .uni-title { font-size: 1.15rem; font-weight: 700; color: #1a202c; margin-bottom: 0.5rem; transition: color 0.2s; line-height: 1.4; }
    .uni-item:hover .uni-title { color: var(--theme-color, var(--primary)); }
    .uni-location { font-size: 0.85rem; color: #4a5568; margin-bottom: 1rem; display: flex; align-items: center; gap: 0.5rem; flex-wrap: wrap; }
    .uni-location i { color: #a0aec0; }
    
    .uni-info-grid { display: grid; grid-template-columns: 1fr; gap: 1rem; margin-top: auto; border-top: 1px dashed #edf2f7; padding-top: 1.25rem; }
    .uni-item:hover .uni-info-grid { display: none; }
    .info-cell { display: flex; gap: 0.5rem; align-items: flex-start; }
    .info-cell i { color: var(--theme-color, var(--primary)); margin-top: 0.25rem; }
    .info-label { font-size: 0.7rem; font-weight: 600; color: #718096; text-transform: uppercase; margin: 0 0 0.1rem; }
    .info-val { font-size: 0.85rem; font-weight: 600; color: #2d3748; margin: 0; }
    
    .uni-item__actions { margin-top: auto; padding-top: 1.25rem; display: none; flex-direction: column; justify-content: center; gap: 0.75rem; border-top: 1px dashed #edf2f7; }
    .uni-item:hover .uni-item__actions { display: flex; }
    .uni-btn { text-align: center; padding: 0.75rem 1rem; border-radius: 0.75rem; font-weight: 600; text-decoration: none; font-size: 0.9rem; transition: all 0.2s; cursor: pointer; border: none; }
    .uni-btn--primary { background: var(--theme-color, var(--primary)); color: #fff; }
    .uni-btn--primary:hover { filter: brightness(0.9); box-shadow: 0 2px 10px rgba(0,0,0,0.1); color: #fff; }
    .uni-btn--outline { background: #fff; color: var(--theme-color, #4a5568); border: 1px solid var(--theme-color, #e2e8f0); }
    .uni-btn--outline:hover { background: var(--theme-bg, #f7fafc); }
  </style>

  <!-- Overview Section (1st Section) -->
  <section class="section bg-light" style="padding: 2rem 0; background-color: #f8f9fa; color: #1a202c; border-bottom: 1px solid #edf2f7;">
    <div class="container">
      <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 4rem; align-items: center;">
        <div class="animate-on-scroll">
          <h1 style="font-size: 3rem; font-weight: 800; margin-bottom: 1.5rem; line-height: 1.2; color: var(--dark);">Find The University <br> <span style="color: var(--primary);">That Fits You</span></h1>
          <p style="color:var(--gray, #4a5568); margin-bottom:2.5rem; line-height:1.7; font-size: 1.1rem;">
            Choosing where to study is a life-changing decision. We don't just give you a list; we provide a strategy. Based on your grades, career goals, and budget, we help you pick the best fit.
          </p>
          <ul style="list-style: none; padding: 0; margin: 0; display: flex; flex-direction: column; gap: 1rem;">
            <li style="display: flex; align-items: center; gap: 1rem; font-size: 1.05rem; color: var(--dark); font-weight: 500;">
              <i class="fa-solid fa-circle-check" style="color: #10b981; font-size: 1.25rem;"></i> Academic Profile Assessment
            </li>
            <li style="display: flex; align-items: center; gap: 1rem; font-size: 1.05rem; color: var(--dark); font-weight: 500;">
              <i class="fa-solid fa-circle-check" style="color: #10b981; font-size: 1.25rem;"></i> University Ranking Comparisons
            </li>
            <li style="display: flex; align-items: center; gap: 1rem; font-size: 1.05rem; color: var(--dark); font-weight: 500;">
              <i class="fa-solid fa-circle-check" style="color: #10b981; font-size: 1.25rem;"></i> Course Curriculum Analysis
            </li>
            <li style="display: flex; align-items: center; gap: 1rem; font-size: 1.05rem; color: var(--dark); font-weight: 500;">
              <i class="fa-solid fa-circle-check" style="color: #10b981; font-size: 1.25rem;"></i> Post-Study Work Opportunity Check
            </li>
          </ul>
        </div>
        <div class="animate-on-scroll delay-1" style="position: relative; padding: 1rem;">
            <!-- Main Image -->
            <img src="https://images.unsplash.com/photo-1523240795612-9a054b0db644?auto=format&fit=crop&q=80&w=800" alt="Students collaborating" style="width: 100%; height: 420px; object-fit: cover; border-radius: 24px; box-shadow: 0 15px 40px rgba(0,0,0,0.08);">
            
            <!-- Secondary Image overlapping -->
            <img src="https://images.unsplash.com/photo-1517486808906-6ca8b3f04846?auto=format&fit=crop&q=80&w=500" alt="University Campus" style="position: absolute; bottom: -30px; right: -10px; width: 200px; height: 200px; object-fit: cover; border-radius: 20px; border: 8px solid white; box-shadow: 0 15px 35px rgba(0,0,0,0.12); z-index: 2;">

        

            <!-- Stats Overlay -->
            <div style="position: absolute; top: -10px; left: 20px; background: rgba(255, 255, 255, 0.9); backdrop-filter: blur(10px); -webkit-backdrop-filter: blur(10px); padding: 1rem 1.5rem; border-radius: 16px; box-shadow: 0 10px 25px rgba(0,0,0,0.05); z-index: 2; display: flex; align-items: center; gap: 1rem; border: 1px solid rgba(255,255,255,0.5);">
               <div style="width: 45px; height: 45px; background: #e0f2fe; color: var(--secondary, #0ea5e9); border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.25rem;">
                   <i class="fa-solid fa-bullseye"></i>
               </div>
               <div>
                   <div style="font-weight: 800; font-size: 1.25rem; color: var(--dark); line-height: 1.1;">99%</div>
                   <div style="font-size: 0.8rem; color: var(--gray, #4a5568); font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px;">Visa Success Rate</div>
               </div>
            </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Dark Search & Results Section -->
  <div class="dark-search-section">
    <!-- Hero Section -->
    <section class="hero-search">
    <div class="hero-search__content">
      <h1 class="hero-search__title">Find a university</h1>
      <p class="hero-search__subtitle">Discover top-ranked universities worldwide and find your perfect campus.</p>
      
      <div class="search-widget">
        <form method="GET" action="university-selection.php" class="search-form">
          <div class="search-input-group">
            <i class="fa-solid fa-search search-icon"></i>
            <div class="search-input-wrapper">
              <span class="search-label">University Name</span>
              <input type="text" name="q" class="search-input" placeholder="e.g. Oxford, Stanford" value="<?= htmlspecialchars($search_query) ?>">
            </div>
          </div>
          
          <div class="search-divider"></div>
          
          <div class="search-input-group">
            <i class="fa-solid fa-location-dot search-icon"></i>
            <div class="search-input-wrapper">
              <span class="search-label">Destination</span>
              <select name="country_id" class="search-select" onchange="this.form.submit()">
                <option value="0">All Destinations</option>
                <?php foreach ($countries as $c): ?>
                  <option value="<?= $c['id'] ?>" <?= $selected_country_id == $c['id'] ? 'selected' : '' ?>>
                    <?= clean_output($c['name']) ?>
                  </option>
                <?php endforeach; ?>
              </select>
            </div>
            <i class="fa-solid fa-chevron-down" style="color: #94a3b8; font-size: 0.8rem; margin-left: 0.5rem;"></i>
          </div>
          
          <button type="submit" class="search-btn">Search</button>
        </form>
      </div>
    </div>
  </section>

  <!-- Main Layout -->
  <section class="uni-container">
    <div class="uni-layout">
      <!-- Main Results -->
      <div class="uni-main">
        <div class="results-header">
          <h2 class="results-count">
            <span><?= count($universities_list) ?></span> Universities found
          </h2>
        </div>
        
        <?php if (empty($universities_list)): ?>
          <div class="no-results-card">
            <div class="no-results-icon"><i class="fa-solid fa-building-columns"></i></div>
            <h3>No universities found</h3>
            <p>Try adjusting your search filters or try a different keyword.</p>
            <a href="university-selection.php" class="clear-search-btn">Clear Search</a>
          </div>
        <?php else: ?>
          <div class="uni-list">
            <?php 
            $c_idx = 0; 
            foreach ($universities_list as $uni): 
              $theme_class = 'theme-' . (($c_idx % 4) + 1);
              $c_idx++;
            ?>
              <div class="uni-item <?= $theme_class ?>">
                <!-- University Details -->
                <div class="uni-item__details">
                  <div class="uni-logo-box">
                    <i class="fa-solid fa-building-columns"></i>
                  </div>
                  <h3 class="uni-title"><?= clean_output($uni['name']) ?></h3>
                  <p class="uni-location">
                    <i class="fa-solid fa-location-dot"></i>
                    <?= !empty($uni['country_name']) ? clean_output($uni['country_name']) : 'Worldwide' ?>
                  </p>
                  
                  <!-- Info Grid -->
                  <div class="uni-info-grid">
                    <?php if (!empty($uni['qs_ranking'])): ?>
                    <div class="info-cell">
                      <i class="fa-solid fa-star"></i>
                      <div>
                        <p class="info-label">QS Ranking</p>
                        <p class="info-val"><?= clean_output($uni['qs_ranking']) ?></p>
                      </div>
                    </div>
                    <?php endif; ?>
                    
                    <div class="info-cell">
                      <i class="fa-solid fa-book-open"></i>
                      <div>
                        <p class="info-label">Primary Focus</p>
                        <p class="info-val"><?= !empty($uni['specialization']) ? clean_output($uni['specialization']) : 'Multi-disciplinary' ?></p>
                      </div>
                    </div>
                  </div>
                  
                  <!-- Action Area -->
                  <div class="uni-item__actions">
                    <a href="courses.php?country=<?= $uni['country_id'] ?>&q=<?= urlencode($uni['name']) ?>" class="uni-btn uni-btn--primary">
                      View Courses
                    </a>
                    <button onclick="openEnquiryModal('<?= addslashes(htmlspecialchars($uni['name'], ENT_QUOTES)) ?>')" class="uni-btn uni-btn--outline">
                      Apply Now
                    </button>
                  </div>
                </div>
              </div>
            <?php endforeach; ?>
          </div>
        <?php endif; ?>
      </div>
      
    </div>
  </section>
  </div> <!-- End dark-search-section -->





  <style>
  /* PREMIUM FEATURE PILLS */
  .feature-pill {
      display: flex;
      align-items: center;
      gap: 1.25rem;
      background: linear-gradient(rgba(255, 255, 255, 0.75), rgba(255, 255, 255, 0.9)), url('assets/images/premium_card_bg.png');
      background-size: cover;
      background-position: center;
      backdrop-filter: blur(10px);
      -webkit-backdrop-filter: blur(10px);
      padding: 2rem 1.5rem;
      border-radius: 20px;
      box-shadow: 0 10px 30px rgba(0,0,0,0.04);
      border: 1px solid rgba(255,255,255,0.5);
      transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
      cursor: default;
      position: relative;
      overflow: hidden;
      z-index: 1;
  }
  .feature-pill--center {
      flex-direction: column;
      text-align: center;
      gap: 1rem;
      height: 100%;
  }
  .feature-pill:hover {
      transform: translateY(-10px) scale(1.02) !important;
      box-shadow: 0 15px 35px rgba(14,165,233,0.15);
      border-color: rgba(14,165,233,0.4);
  }
  .feature-pill__icon {
      width: 60px;
      height: 60px;
      border-radius: 16px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 1.75rem;
      color: white;
      flex-shrink: 0;
  }
  .feature-pill__icon--blue { background: linear-gradient(135deg, #0ea5e9, #3b82f6); box-shadow: 0 8px 20px rgba(14,165,233,0.3); }
  .feature-pill__icon--purple { background: linear-gradient(135deg, #8b5cf6, #d946ef); box-shadow: 0 8px 20px rgba(139,92,246,0.3); }
  .feature-pill__icon--orange { background: linear-gradient(135deg, #f97316, #f59e0b); box-shadow: 0 8px 20px rgba(249,115,22,0.3); }
  .feature-pill__icon--teal { background: linear-gradient(135deg, #14b8a6, #0d9488); box-shadow: 0 8px 20px rgba(20,184,166,0.3); }
  .feature-pill__text { font-size: 1.15rem; font-weight: 700; color: var(--dark); line-height: 1.4; }
  </style>

  <section class="section" style="padding: 4rem 0; background-color: #fff; color: #1a202c;">
    <div class="container animate-on-scroll">
      <div style="background: var(--gradient); padding: 4rem 2rem; border-radius: var(--radius-lg); text-align: center; color: white; box-shadow: var(--shadow-lg);">
        <h2 style="font-size: 2.5rem; margin-bottom: 1rem;">Ready to Find Your Match?</h2>
        <p style="font-size: 1.1rem; opacity: 0.9; max-width: 600px; margin: 0 auto 2rem;">Our experts have helped over 10,000 students find their perfect academic home.</p>
        <a href="consultation.php" class="btn btn--white btn--lg pulse-btn" style="background: white; color: var(--primary);">Start Selection Process</a>
      </div>
    </div>
  </section>

<!-- ENQUIRY POPUP MODAL -->
<div id="enquiryEntryModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; z-index: 9999; background: rgba(15, 23, 42, 0.85); backdrop-filter: blur(5px); align-items: center; justify-content: center; opacity: 0; transition: opacity 0.4s ease;">
  <div style="background: white; border-radius: 20px; width: 90%; max-width: 500px; position: relative; box-shadow: 0 25px 50px -12px rgba(0,0,0,0.25); transform: translateY(20px); transition: transform 0.4s ease;" id="enquiryModalContent">
    
    <!-- Close Button -->
    <button onclick="closeEnquiryModal()" style="position: absolute; top: 1rem; right: 1rem; background: rgba(0,0,0,0.05); border: none; width: 32px; height: 32px; border-radius: 50%; display: flex; align-items: center; justify-content: center; cursor: pointer; color: #64748b; font-size: 1.2rem; transition: background 0.2s;">
      <i class="fa-solid fa-xmark"></i>
    </button>
    
    <!-- Modal Header -->
    <div style="padding: 2rem 2rem 1.5rem; text-align: center; border-bottom: 1px solid #e2e8f0;">
      <h3 style="font-size: 1.5rem; font-weight: 800; color: #0f172a; margin: 0 0 0.5rem 0;">University Application</h3>
      <p style="color: #64748b; font-size: 0.95rem; margin: 0;">Apply to <strong id="modalUniName" style="color: #3b82f6;"></strong>. Leave your details below.</p>
    </div>
    
    <!-- Modal Body (Form) -->
    <div style="padding: 1.5rem 2rem 2rem;">
      <form id="enquiryPopupForm" onsubmit="return handleFormSubmit(event)" style="display: flex; flex-direction: column; gap: 1rem;">
        <input type="hidden" name="form_type" value="enquiry">
        <input type="hidden" name="query" id="modalQueryField" value="Application Inquiry">
        
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
          Submit Application
        </button>
      </form>
    </div>
  </div>
</div>

<script>
  function openEnquiryModal(uniName) {
    const modal = document.getElementById('enquiryEntryModal');
    const content = document.getElementById('enquiryModalContent');
    const uniNameEl = document.getElementById('modalUniName');
    const queryField = document.getElementById('modalQueryField');
    
    if (uniNameEl && uniName) uniNameEl.textContent = uniName;
    if (queryField && uniName) queryField.value = 'Application Inquiry for ' + uniName;
    
    modal.style.display = 'flex';
    void modal.offsetWidth;
    modal.style.opacity = '1';
    content.style.transform = 'translateY(0)';
  }

  function closeEnquiryModal() {
    const modal = document.getElementById('enquiryEntryModal');
    const content = document.getElementById('enquiryModalContent');
    
    modal.style.opacity = '0';
    content.style.transform = 'translateY(20px)';
    
    setTimeout(() => {
      modal.style.display = 'none';
    }, 400);
  }

  // Close modal when clicking outside of content
  document.getElementById('enquiryEntryModal').addEventListener('click', function(e) {
    if (e.target === this) {
      closeEnquiryModal();
    }
  });
</script>

</main>
<?php require_once 'includes/footer.php'; ?>
