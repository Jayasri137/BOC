<?php
require_once 'includes/config.php';
$pageTitle = 'Study Abroad Scholarships | Overseas Education Consultants';
$pageDesc = 'Looking for study abroad scholarships? Bluestone Overseas provides expert guidance on securing university scholarships, grants and financial aid for Indian students.';
$pageKeywords = 'Study abroad scholarships, University scholarships for Indian students, Scholarships to study abroad, Study abroad financial aid';
$pageHeroImage = 'assets/images/Offer.png';
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
$selected_country_id = isset($_GET['country']) ? intval($_GET['country']) : 0;
$search_query = isset($_GET['q']) ? trim($_GET['q']) : '';

// Check if this is the default state (no filters)
$is_default_state = ($selected_country_id <= 0 && empty($search_query));

// Fetch scholarships
$scholarships_list = [];
try {
    $sql = "SELECT s.*, u.name as university_name, c.name as country_name 
            FROM scholarships s
            JOIN universities u ON s.university_id = u.id
            LEFT JOIN countries c ON u.country_id = c.id
            WHERE s.is_active = 1 AND u.is_active = 1";
    $params = [];
    
    if ($selected_country_id > 0) {
        $sql .= " AND u.country_id = :cid";
        $params['cid'] = $selected_country_id;
    }
    
    if (!empty($search_query)) {
        $sql .= " AND (s.name LIKE :q OR u.name LIKE :q)";
        $params['q'] = "%{$search_query}%";
    }
    
    $sql .= " ORDER BY u.name ASC, s.name ASC";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $scholarships_list = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    // Silently fail
}
?>

<main class="page-scholarships">
  <style>
    :root {
      --primary: #ec4899;
      --secondary: #0ea5e9;
      --dark: #1a202c;
    }
    
    .page-scholarships { background-color: #f8f9fa; }
    
    /* Hero Search */
    .hero-search { position: relative; padding: 1.5rem 1rem 1.5rem; background: #fff; overflow: hidden; border-bottom: 1px solid #edf2f7; text-align: center; }
    .hero-search__bg { position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: linear-gradient(135deg, rgba(245,158,11,0.05), rgba(236,72,153,0.05)); z-index: 0; }
    .hero-search__content { position: relative; z-index: 10; max-width: 900px; margin: 0 auto; }
    .hero-search__title { font-size: 2.5rem; font-weight: 700; color: #f59e0b; margin-bottom: 0.5rem; }
    @media(min-width: 768px) { .hero-search__title { font-size: 3rem; } }
    .hero-search__subtitle { font-size: 1.125rem; color: #4a5568; margin-bottom: 1rem; }
    
    /* Search Widget */
    .search-widget { background: #fff; padding: 0.75rem; border-radius: 1rem; box-shadow: 0 10px 25px rgba(0,0,0,0.05); border: 1px solid #edf2f7; margin: 0 auto; }
    .search-form { display: flex; flex-direction: column; gap: 0.5rem; }
    @media(min-width: 768px) { .search-form { flex-direction: row; } }
    .search-input-group { position: relative; flex: 1; display: flex; align-items: center; background: #f8fafc; border-radius: 0.75rem; padding: 0.5rem 1rem; transition: background 0.2s; }
    .search-input-group:focus-within { background: #fff; box-shadow: inset 0 0 0 2px rgba(245,158,11,0.2); }
    .search-icon { color: #94a3b8; font-size: 1.25rem; margin-right: 0.75rem; }
    .search-input-wrapper { flex: 1; display: flex; flex-direction: column; text-align: left; }
    .search-label { font-size: 0.65rem; font-weight: 700; color: #64748b; text-transform: uppercase; margin-bottom: 0.2rem; }
    .search-input { width: 100%; border: none; background: transparent; font-size: 1rem; color: #1e293b; outline: none; padding: 0; margin: 0; font-family: inherit; }
    .search-input::placeholder { color: #94a3b8; }
    .search-select { width: 100%; border: none; background: transparent; font-size: 1rem; color: #1e293b; outline: none; padding: 0; margin: 0; font-family: inherit; cursor: pointer; appearance: none; }
    .search-divider { display: none; width: 1px; background: #e2e8f0; margin: 0 0.5rem; }
    @media(min-width: 768px) { .search-divider { display: block; } }
    .search-btn { background: #f59e0b; color: #fff; border: none; padding: 1rem 2.5rem; border-radius: 0.75rem; font-weight: 700; font-size: 1rem; cursor: pointer; transition: all 0.2s; white-space: nowrap; }
    .search-btn:hover { background: #d97706; box-shadow: 0 4px 12px rgba(245,158,11,0.3); }
    
    /* Layout */
    .schol-container { max-width: 1200px; margin: 0 auto; padding: 3rem 1rem; }
    .schol-layout { display: flex; flex-direction: column; gap: 2rem; }
    @media(min-width: 992px) { .schol-layout { flex-direction: row; align-items: flex-start; } }
    
    /* Sidebar Filter */
    .schol-sidebar { background: #fff; border-radius: 1rem; border: 1px solid #edf2f7; padding: 1.5rem; width: 100%; }
    @media(min-width: 992px) { .schol-sidebar { width: 300px; position: sticky; top: 100px; } }
    .filter-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; padding-bottom: 1rem; border-bottom: 1px solid #edf2f7; }
    .filter-title { font-size: 1.125rem; font-weight: 700; color: #1a202c; margin: 0; display: flex; align-items: center; gap: 0.5rem; }
    .filter-clear { font-size: 0.875rem; color: #f59e0b; text-decoration: none; font-weight: 600; }
    .filter-group { margin-bottom: 1.5rem; }
    .filter-group-title { font-size: 0.9rem; font-weight: 700; color: #4a5568; margin-bottom: 0.75rem; }
    
    /* Custom select style for sidebar */
    .custom-select-wrap { position: relative; }
    .custom-select { width: 100%; appearance: none; background: #f8fafc; border: 1px solid #e2e8f0; padding: 0.75rem 1rem; border-radius: 0.5rem; font-size: 0.95rem; color: #1a202c; cursor: pointer; outline: none; transition: border-color 0.2s; }
    .custom-select:focus { border-color: #f59e0b; }
    .custom-select-wrap::after { content: '\f107'; font-family: 'Font Awesome 6 Free'; font-weight: 900; position: absolute; right: 1rem; top: 50%; transform: translateY(-50%); color: #a0aec0; pointer-events: none; }
    
    /* Main Content */
    .schol-main { flex: 1; min-width: 0; }
    .results-header { display: flex; flex-direction: column; gap: 1rem; margin-bottom: 2rem; }
    @media(min-width: 640px) { .results-header { flex-direction: row; justify-content: space-between; align-items: center; } }
    .results-count { font-size: 1.25rem; font-weight: 700; color: #1a202c; margin: 0; }
    .results-count span { color: #f59e0b; }
    
    /* Scholarship Cards */
    .no-results-card { background: #fff; border-radius: 1rem; border: 1px dashed #cbd5e1; padding: 4rem 2rem; text-align: center; }
    .no-results-icon { font-size: 3rem; color: #cbd5e1; margin-bottom: 1rem; }
    .no-results-card h3 { color: #1a202c; margin-bottom: 0.5rem; }
    .no-results-card p { color: #718096; margin-bottom: 1.5rem; }
    .clear-search-btn { display: inline-block; background: #f59e0b; color: #fff; font-weight: 600; padding: 0.75rem 1.5rem; border-radius: 0.75rem; text-decoration: none; }
    
    .schol-list { display: grid; grid-template-columns: 1fr; gap: 1.5rem; }
    @media(min-width: 768px) { .schol-list { grid-template-columns: repeat(2, 1fr); } }
    
    .schol-item { display: flex; flex-direction: column; background: #fff; border-radius: 1rem; border: 1px solid #edf2f7; overflow: hidden; transition: box-shadow 0.2s, border-color 0.2s; height: 100%; position: relative; }
    .schol-item:hover { box-shadow: 0 4px 15px rgba(0,0,0,0.05); }
    
    .theme-1 { --theme-color: #b57bee; --theme-bg: #f3e8f9; }
    .theme-2 { --theme-color: #ea580c; --theme-bg: #ffedd5; }
    .theme-3 { --theme-color: #0d9488; --theme-bg: #ccfbf1; }
    .theme-4 { --theme-color: #2563eb; --theme-bg: #dbeafe; }
    
    .schol-item.theme-1:hover { border-color: #b57bee; box-shadow: 0 4px 15px rgba(181,123,238,0.15); }
    .schol-item.theme-2:hover { border-color: #ea580c; box-shadow: 0 4px 15px rgba(234,88,12,0.15); }
    .schol-item.theme-3:hover { border-color: #0d9488; box-shadow: 0 4px 15px rgba(13,148,136,0.15); }
    .schol-item.theme-4:hover { border-color: #2563eb; box-shadow: 0 4px 15px rgba(37,99,235,0.15); }
    
    .schol-item__details { padding: 1.5rem; flex: 1; display: flex; flex-direction: column; }
    .schol-logo-box { width: 50px; height: 50px; background: var(--theme-bg, #fffbeb); border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.25rem; color: var(--theme-color, #f59e0b); margin-bottom: 1rem; }
    .schol-title { font-size: 1.15rem; font-weight: 700; color: #1a202c; margin-bottom: 0.5rem; transition: color 0.2s; line-height: 1.4; }
    .schol-item:hover .schol-title { color: var(--theme-color, #f59e0b); }
    .schol-uni { font-size: 0.85rem; color: #4a5568; margin-bottom: 1rem; display: flex; align-items: flex-start; gap: 0.5rem; flex-wrap: wrap; }
    .schol-uni i { color: #a0aec0; margin-top: 0.15rem; }
    
    .schol-val-box { background: var(--theme-bg, #f8fafc); border: 1px solid #edf2f7; padding: 1rem; border-radius: 0.75rem; margin-bottom: 1.25rem; }
    .schol-val-label { display: block; font-size: 0.7rem; color: #718096; text-transform: uppercase; letter-spacing: 1px; font-weight: 700; margin-bottom: 0.25rem; }
    .schol-val-amount { font-size: 1.2rem; color: var(--theme-color, #1a202c); margin: 0; font-weight: 800; }

    .schol-info-grid { display: grid; grid-template-columns: 1fr; gap: 0.75rem; margin-top: auto; border-top: 1px dashed #edf2f7; padding-top: 1.25rem; }
    .schol-item:hover .schol-info-grid { display: none; }
    .info-cell { display: flex; gap: 0.5rem; align-items: flex-start; }
    .info-cell i { color: var(--theme-color, #f59e0b); margin-top: 0.25rem; }
    .info-label { font-size: 0.7rem; font-weight: 600; color: #718096; text-transform: uppercase; margin: 0 0 0.1rem; }
    .info-val { font-size: 0.85rem; font-weight: 600; color: #2d3748; margin: 0; line-height: 1.4; }
    
    .schol-item__actions { margin-top: auto; padding-top: 1.25rem; display: none; flex-direction: column; justify-content: center; gap: 0.75rem; border-top: 1px dashed #edf2f7; }
    .schol-item:hover .schol-item__actions { display: flex; }
    .schol-btn { text-align: center; padding: 0.75rem 1rem; border-radius: 0.75rem; font-weight: 600; text-decoration: none; font-size: 0.9rem; transition: all 0.2s; cursor: pointer; border: none; display: block; width: 100%; box-sizing: border-box; }
    .schol-btn--primary { background: var(--theme-color, #f59e0b); color: #fff; }
    .schol-btn--primary:hover { filter: brightness(0.9); box-shadow: 0 2px 10px rgba(0,0,0,0.1); color: #fff; }
  </style>

  <!-- Hero Search Section -->
  <section class="hero-search">
    <div class="hero-search__bg"></div>
    <div class="hero-search__content">
      <h1 class="hero-search__title">Find a Scholarship</h1>
      <p class="hero-search__subtitle">Discover financial aid, grants, and scholarships from universities worldwide.</p>
      
      <div class="search-widget">
        <form method="GET" action="scholarships.php" class="search-form">
          <div class="search-input-group">
            <i class="fa-solid fa-search search-icon"></i>
            <div class="search-input-wrapper">
              <span class="search-label">Keyword</span>
              <input type="text" name="q" class="search-input" placeholder="e.g. Merit, Vice-Chancellor" value="<?= htmlspecialchars($search_query) ?>">
            </div>
          </div>
          
          <div class="search-divider"></div>
          
          <div class="search-input-group">
            <i class="fa-solid fa-location-dot search-icon"></i>
            <div class="search-input-wrapper">
              <span class="search-label">Destination</span>
              <select name="country" class="search-select">
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
  <section class="schol-container">
    <div class="schol-layout">
      
      <!-- Sidebar Filter -->
      <aside class="schol-sidebar">
        <div class="filter-header">
          <h2 class="filter-title"><i class="fa-solid fa-sliders"></i> Filters</h2>
          <?php if($selected_country_id > 0 || !empty($search_query)): ?>
            <a href="scholarships.php" class="filter-clear">Clear all</a>
          <?php endif; ?>
        </div>
        
        <form method="GET" action="scholarships.php" id="sidebar-filter-form">
          <?php if(!empty($search_query)): ?>
            <input type="hidden" name="q" value="<?= htmlspecialchars($search_query) ?>">
          <?php endif; ?>
          
          <div class="filter-group">
            <div class="filter-group-title">Study Destination</div>
            <div class="custom-select-wrap">
              <select name="country" class="custom-select" onchange="document.getElementById('sidebar-filter-form').submit()">
                <option value="0">Any Destination</option>
                <?php foreach ($countries as $c): ?>
                  <option value="<?= $c['id'] ?>" <?= $selected_country_id == $c['id'] ? 'selected' : '' ?>>
                    <?= clean_output($c['name']) ?>
                  </option>
                <?php endforeach; ?>
              </select>
            </div>
          </div>
        </form>
      </aside>

      <!-- Main Results -->
      <div class="schol-main">
        <div class="results-header">
          <h2 class="results-count">
            <span><?= count($scholarships_list) ?></span> Scholarships found
          </h2>
        </div>
        
        <?php if (empty($scholarships_list)): ?>
          <div class="no-results-card">
            <div class="no-results-icon"><i class="fa-solid fa-award"></i></div>
            <h3>No scholarships found</h3>
            <p>Try adjusting your search filters or try a different keyword.</p>
            <a href="scholarships.php" class="clear-search-btn">Clear Search</a>
          </div>
        <?php else: ?>
          <div class="schol-list">
            <?php 
            $c_idx = 0; 
            foreach ($scholarships_list as $schol): 
              $theme_class = 'theme-' . (($c_idx % 4) + 1);
              $c_idx++;
            ?>
              <div class="schol-item <?= $theme_class ?>">
                <!-- Details -->
                <div class="schol-item__details">
                  <div class="schol-logo-box">
                    <i class="fa-solid fa-award"></i>
                  </div>
                  <h3 class="schol-title"><?= clean_output($schol['name']) ?></h3>
                  <div class="schol-uni">
                    <i class="fa-solid fa-building-columns"></i>
                    <div>
                      <strong><?= clean_output($schol['university_name']) ?></strong><br>
                      <?= !empty($schol['country_name']) ? clean_output($schol['country_name']) : 'Worldwide' ?>
                    </div>
                  </div>
                  
                  <div class="schol-val-box">
                    <span class="schol-val-label">Scholarship Value</span>
                    <h5 class="schol-val-amount"><?= clean_output($schol['amount']) ?></h5>
                  </div>
                  
                  <!-- Info Grid -->
                  <div class="schol-info-grid">
                    <div class="info-cell">
                      <i class="fa-solid fa-user-check"></i>
                      <div>
                        <p class="info-label">Eligibility</p>
                        <p class="info-val"><?= clean_output($schol['eligibility']) ?></p>
                      </div>
                    </div>
                    
                    <div class="info-cell">
                      <i class="fa-solid fa-calendar-xmark"></i>
                      <div>
                        <p class="info-label">Deadline</p>
                        <p class="info-val"><?= clean_output($schol['deadline']) ?></p>
                      </div>
                    </div>
                  </div>
                  
                  <!-- Action Area -->
                  <div class="schol-item__actions">
                    <a href="consultation.php?enquiry=scholarship&name=<?= urlencode($schol['name']) ?>" class="schol-btn schol-btn--primary">
                      Check Eligibility
                    </a>
                  </div>
                </div>
              </div>
            <?php endforeach; ?>
          </div>
        <?php endif; ?>
      </div>
      
    </div>
  </section>

  <?php if ($is_default_state): ?>
    <!-- PROCESS SECTION -->
    <div class="process-section" style="margin-bottom: 5rem;">
      <div class="container">
      <div style="background: #579df9; border-radius: 30px; padding: 4rem; position: relative; overflow: hidden; box-shadow: 0 20px 40px rgba(24, 119, 242, 0.25);">
        <!-- Decorative faint background shapes -->
        <div style="position: absolute; top: -50px; right: -50px; width: 300px; height: 300px; background: rgba(255,255,255,0.05); border-radius: 50%; pointer-events: none;"></div>
        <div style="position: absolute; bottom: -100px; left: 20%; width: 400px; height: 400px; background: rgba(255,255,255,0.05); border-radius: 50%; pointer-events: none;"></div>

        <div style="position: relative; z-index: 1;">
          <div class="section__header animate-on-scroll" style="text-align: center; margin-bottom: 3rem;">
            <span class="section__tag" style="background: rgba(255,255,255,0.2); color: #fff; margin-bottom: 1rem; display: inline-block;">Steps</span>
            <h2 class="section__title" style="color: #fff; font-size: 2.8rem; line-height: 1.2;">How It <span style="background: none; -webkit-text-fill-color: #fd47ba; color: #fd47ba;">Works</span></h2>
            <p class="section__subtitle" style="max-width: 600px; margin: 0 auto; color: rgba(255,255,255,0.9);">A streamlined, step-by-step approach to ensuring your financial success.</p>
          </div>
          
          <div class="process-steps" style="justify-content: center; gap: 2rem; margin-top: 2rem; display: flex; flex-wrap: wrap; position: relative;">
            <!-- Decorative curved dashed line connecting the steps -->
            <svg width="100%" height="200" style="position: absolute; top: 40px; left: 0; z-index: 0; pointer-events: none;" viewBox="0 0 1000 200" preserveAspectRatio="none">
               <path d="M 50 100 Q 250 -20, 500 100 T 950 100" fill="none" stroke="rgba(255,255,255,0.3)" stroke-width="3" stroke-dasharray="8 8" />
               <!-- Little airplane on the path -->
               <path d="M 490 95 L 510 100 L 490 105 L 495 100 Z" fill="#FDE047" transform="rotate(15 500 100)" />
            </svg>

            <?php
            $steps = [
              ['Eligibility', 'We assess your profile against various global scholarship criteria.'],
              ['Matching', 'Match with university-specific, government, and private funding.'],
              ['Documentation', 'Assistance with scholarship essays and letters of recommendation.'],
              ['Application', 'We submit applications alongside your university admission file.'],
            ];
            foreach($steps as $i => [$title, $desc]):
            ?>
            <div class="process-step animate-on-scroll delay-<?= $i ?>" style="flex: 1 1 160px; max-width: 220px; text-align: center; position: relative; z-index: 1;">
              <div class="process-step__image-box" style="width: 140px; height: 140px; margin: 0 auto 1.5rem; position: relative;">
                <img src="assets/images/img<?= $i+1 ?>.png" alt="<?= $title ?>" style="width: 100%; height: 100%; border-radius: 50%; border: 4px solid white; object-fit: cover; box-shadow: 0 10px 20px rgba(0,0,0,0.1);">
                <div class="process-step__badge" style="bottom: -12px; width: 28px; height: 28px; font-size: 0.85rem; line-height: 28px; position: absolute; left: 50%; transform: translateX(-50%); background: var(--dark); color: white; border-radius: 50%; font-weight: bold; border: 2px solid white;"><?= str_pad($i+1, 2, '0', STR_PAD_LEFT) ?></div>
              </div>
              <h4 style="color: #ffffff; font-size: 1.1rem; margin-bottom: 0.5rem; font-weight: 700;"><?= $title ?></h4>
              <p style="color: rgba(255,255,255,0.85); font-size: 0.9rem; line-height: 1.4;"><?= $desc ?></p>
            </div>
            <?php endforeach; ?>
          </div>
        </div>
      </div>
      </div>
    </div>
  
    <section class="section">
      <div class="container">
        <div class="text-center animate-on-scroll">
          <span class="section__tag">Advantages</span>
          <h2 class="section__title">Why Choose <span>Bluestone</span></h2>
          <p class="section__subtitle" style="max-width: 600px; margin: 0 auto;">Experience the advantage of working with industry-leading financial experts.</p>
        </div>
        <div class="grid grid--3 gap--2" style="margin-top: 3rem;">
          <div class="service-card animate-on-scroll">
            <h3 style="display: flex; align-items: center; gap: 0.5rem;"><i class="fa-solid fa-percent" style="color: #f59e0b;"></i> Up to 100% Funding</h3>
            <p>We have successfully helped students secure full-ride scholarships and massive tuition waivers.</p>
          </div>
          <div class="service-card animate-on-scroll delay-1">
            <h3 style="display: flex; align-items: center; gap: 0.5rem;"><i class="fa-solid fa-magnifying-glass-dollar" style="color: #f59e0b;"></i> Extensive Search</h3>
            <p>We don't just look at university aid; we explore external grants and bursaries worldwide.</p>
          </div>
          <div class="service-card animate-on-scroll delay-2">
            <h3 style="display: flex; align-items: center; gap: 0.5rem;"><i class="fa-solid fa-pen-nib" style="color: #f59e0b;"></i> Essay Assistance</h3>
            <p>Our editorial team knows exactly what scholarship committees look for in an application.</p>
          </div>
        </div>
      </div>
    </section>
  
    <section class="section">
      <div class="container">
          <!-- Fund Your Future -->
          <div class="financial-card" style="background: linear-gradient(135deg, #ecc52cff, #fef3c7); border-radius: 30px; position: relative; overflow: hidden; margin-top: 2rem; margin-bottom: 2rem; box-shadow: 0 20px 40px rgba(245,158,11,0.05);">
            <!-- Decorative Background Elements -->
            <div style="position: absolute; top: -50px; right: -50px; width: 300px; height: 300px; background: rgba(245, 158, 11, 0.1); border-radius: 50%; filter: blur(40px);"></div>
            <div style="position: absolute; bottom: -50px; left: -50px; width: 200px; height: 200px; background: rgba(245, 158, 11, 0.1); border-radius: 50%; filter: blur(30px);"></div>
  
            <div class="grid grid--2 gap--4 align-center" style="position: relative; z-index: 1;">
              
              <!-- Left Side: Text and Features -->
              <div class="animate-on-scroll">
                <div style="display: inline-block; padding: 0.5rem 1.25rem; background: rgba(245, 158, 11, 0.15); color: #d97706; border-radius: 30px; font-weight: 700; font-size: 0.85rem; letter-spacing: 1px; text-transform: uppercase; margin-bottom: 1.5rem;">
                  Financial Support
                </div>
                <h1 style="font-weight: 800; font-size: clamp(2.5rem, 4vw, 3.5rem); color: #0f172a; line-height: 1.2; margin-bottom: 1.5rem;">Study Abroad <span style="color: #fd47ba;">Scholarships</span></h1>
                <p style="color: #475569; font-size: 1.15rem; line-height: 1.7; margin-bottom: 1.5rem;">We help you discover and apply for exclusive scholarships, grants, and bursaries offered by universities and governments across the globe. Our experts identify options that can fund up to 100% of your tuition.</p>
                
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 1rem; margin-top: 2.5rem;">
                  <div style="background: white; padding: 1rem 1.25rem; border-radius: 16px; display: flex; align-items: center; gap: 1rem; box-shadow: 0 10px 25px rgba(0,0,0,0.03);">
                    <div style="width: 40px; height: 40px; background: #fffbeb; color: #f59e0b; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.1rem; flex-shrink: 0;"><i class="fa-solid fa-medal"></i></div>
                    <span style="font-weight: 600; color: #1e293b; font-size: 0.95rem;">Merit-Based</span>
                  </div>
                  <div style="background: white; padding: 1rem 1.25rem; border-radius: 16px; display: flex; align-items: center; gap: 1rem; box-shadow: 0 10px 25px rgba(0,0,0,0.03);">
                    <div style="width: 40px; height: 40px; background: #fffbeb; color: #f59e0b; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.1rem; flex-shrink: 0;"><i class="fa-solid fa-hand-holding-dollar"></i></div>
                    <span style="font-weight: 600; color: #1e293b; font-size: 0.95rem;">Need-Based</span>
                  </div>
                  <div style="background: white; padding: 1rem 1.25rem; border-radius: 16px; display: flex; align-items: center; gap: 1rem; box-shadow: 0 10px 25px rgba(0,0,0,0.03);">
                    <div style="width: 40px; height: 40px; background: #fffbeb; color: #f59e0b; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.1rem; flex-shrink: 0;"><i class="fa-solid fa-landmark"></i></div>
                    <span style="font-weight: 600; color: #1e293b; font-size: 0.95rem;">Govt Grants</span>
                  </div>
                  <div style="background: white; padding: 1rem 1.25rem; border-radius: 16px; display: flex; align-items: center; gap: 1rem; box-shadow: 0 10px 25px rgba(0,0,0,0.03);">
                    <div style="width: 40px; height: 40px; background: #fffbeb; color: #f59e0b; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.1rem; flex-shrink: 0;"><i class="fa-solid fa-pen-nib"></i></div>
                    <span style="font-weight: 600; color: #1e293b; font-size: 0.95rem;">Essay Support</span>
                  </div>
                </div>
              </div>
  
              <!-- Right Side: Image and Floating Badges -->
              <div class="animate-on-scroll delay-1" style="position: relative;">
                <div class="financial-img-wrapper" style="position: relative;">
                  <img src="assets/images/s1.jpg" alt="Scholarship Opportunities" style="width: 100%; border-radius: 24px; box-shadow: 0 25px 50px rgba(0,0,0,0.1); border: 8px solid white;">
                  
                  <!-- Floating Badge 1 -->
                  <div class="financial-badge-1" style="position: absolute; background: white; padding: 1rem 1.5rem; border-radius: 16px; display: flex; align-items: center; gap: 1rem; box-shadow: 0 15px 35px rgba(0,0,0,0.1); animation: float 6s ease-in-out infinite;">
                    <div style="width: 45px; height: 45px; background: #fefce8; color: #eab308; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 1.3rem;">
                      <i class="fa-solid fa-star"></i>
                    </div>
                    <div>
                      <div style="font-weight: 800; color: #0f172a; font-size: 1.2rem;">Up to 100%</div>
                      <div style="color: #64748b; font-size: 0.85rem; font-weight: 600;">Tuition Coverage</div>
                    </div>
                  </div>
  
                  <!-- Floating Badge 2 -->
                  <div class="financial-badge-2" style="position: absolute; background: white; padding: 1rem 1.5rem; border-radius: 16px; display: flex; align-items: center; gap: 1rem; box-shadow: 0 15px 35px rgba(0,0,0,0.1); animation: float 5s ease-in-out infinite alternate;">
                    <div style="width: 45px; height: 45px; background: #f0fdf4; color: #22c55e; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 1.3rem;">
                      <i class="fa-solid fa-check-double"></i>
                    </div>
                    <div>
                      <div style="font-weight: 800; color: #0f172a; font-size: 1.2rem;">Expert</div>
                      <div style="color: #64748b; font-size: 0.85rem; font-weight: 600;">Guidance</div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
  
          <style>
            .financial-card { padding: 4rem; }
            .financial-badge-1 { right: -10px; top: 10%; }
            .financial-badge-2 { left: -20px; bottom: 15%; }
            .financial-img-wrapper { padding: 0 1.5rem; }
            
            @media (max-width: 768px) {
              .financial-card { padding: 1.5rem; }
              .financial-badge-1 { right: 0px; top: -15px; padding: 0.75rem 1rem !important; }
              .financial-badge-2 { left: 0px; bottom: -15px; padding: 0.75rem 1rem !important; }
              .financial-badge-1 div:first-child, .financial-badge-2 div:first-child { width: 35px !important; height: 35px !important; font-size: 1rem !important; }
              .financial-badge-1 div:nth-child(2) div:first-child, .financial-badge-2 div:nth-child(2) div:first-child { font-size: 1rem !important; }
              .financial-badge-1 div:nth-child(2) div:last-child, .financial-badge-2 div:nth-child(2) div:last-child { font-size: 0.75rem !important; }
              .financial-img-wrapper { padding: 0; margin-top: 1.5rem; }
            }
            
            @keyframes float {
              0% { transform: translateY(0px); }
              50% { transform: translateY(-15px); }
              100% { transform: translateY(0px); }
            }
          </style>
      </div>
    </section>
  <?php endif; ?>

  <section class="section" style="padding-top: 0;">
    <div class="container animate-on-scroll">
      <div style="background: linear-gradient(135deg, #579df9 0%, #3b82f6 100%); padding: 4rem 2rem; border-radius: var(--radius-lg); text-align: center; color: white; box-shadow: 0 20px 40px rgba(59, 130, 246, 0.3);">
        <h2 style="font-size: 2.5rem; margin-bottom: 1rem;">Unlock Your Financial Potential</h2>
        <p style="font-size: 1.1rem; opacity: 0.9; max-width: 600px; margin: 0 auto 2rem;">Don't let budget constraints stop you from achieving your dreams. Get a free financial assessment today.</p>
        <a href="consultation.php" class="btn btn--white btn--lg pulse-btn" style="background: white; color: #3b82f6;">Book Free Assessment</a>
      </div>
    </div>
  </section>
</main>
<?php require_once 'includes/footer.php'; ?>
