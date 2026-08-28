<?php
require_once 'includes/config.php';

$slug = $_GET['t'] ?? '';

// Static fallback data in case database is empty
$tests_data = [
    'ielts' => [
        'name' => 'IELTS',
        'full_name' => 'International English Language Testing System',
        'desc' => 'The International English Language Testing System (IELTS) is the world\'s most popular English language proficiency test for higher education and global migration.',
        'features' => ['Band 7+ Focused Coaching', 'Flexible Timings', 'Full Length Mock Tests', 'Personalised Feedback'],
        'icon' => 'fa-pen-to-square',
        'color' => 'blue',
        'image_path' => ''
    ],
    'toefl' => [
        'name' => 'TOEFL',
        'full_name' => 'Test of English as a Foreign Language',
        'desc' => 'The TOEFL iBT test measures your ability to use and understand English at the university level. It evaluates how well you combine your listening, reading, speaking and writing skills.',
        'features' => ['Section-wise Strategies', 'Official Prep Materials', 'Computer-based Mock Tests', 'Score Improvement Guarantee'],
        'icon' => 'fa-pen-to-square',
        'color' => 'purple',
        'image_path' => ''
    ],
    'pte' => [
        'name' => 'PTE Academic',
        'full_name' => 'Pearson Test of English Academic',
        'desc' => 'PTE Academic is the world\'s leading computer-based test of English for study abroad and immigration. It provides fast results and is accepted by thousands of institutions.',
        'features' => ['AI-Scored Mock Tests', 'Fast Track Batches', 'Latest Exam Patterns', 'Small Batch Sizes'],
        'icon' => 'fa-pen-to-square',
        'color' => 'orange',
        'image_path' => ''
    ],
];

$test = null;
$all_tests = [];

// Try to fetch from database first
try {
    if (!empty($slug)) {
        $stmt = $pdo->prepare("SELECT * FROM test_preps WHERE slug = :slug AND is_active = 1 LIMIT 1");
        $stmt->execute(['slug' => $slug]);
        $db_test = $stmt->fetch();
        if ($db_test) {
            $features = [];
            for ($f = 1; $f <= 4; $f++) {
                if (!empty($db_test["feature$f"])) {
                    $features[] = $db_test["feature$f"];
                }
            }
            $test = [
                'name' => $db_test['name'],
                'full_name' => $db_test['description'],
                'desc' => $db_test['description'],
                'features' => $features,
                'icon' => $db_test['icon'],
                'color' => $db_test['color'],
                'image_path' => $db_test['image_path']
            ];
        }
    } else {
        $stmt = $pdo->query("SELECT * FROM test_preps WHERE is_active = 1 ORDER BY id ASC");
        $db_all = $stmt->fetchAll();
        foreach ($db_all as $db_item) {
            $features = [];
            for ($f = 1; $f <= 4; $f++) {
                if (!empty($db_item["feature$f"])) {
                    $features[] = $db_item["feature$f"];
                }
            }
            $all_tests[$db_item['slug']] = [
                'name' => $db_item['name'],
                'full_name' => $db_item['description'],
                'desc' => $db_item['description'],
                'features' => $features,
                'icon' => $db_item['icon'],
                'color' => $db_item['color'],
                'image_path' => $db_item['image_path']
            ];
        }
    }
} catch (PDOException $e) {
    // Graceful fallback to static data
}

// Fallback logic if database query returned empty
if (!empty($slug) && !$test) {
    $fallback = $tests_data[$slug] ?? null;
    if ($fallback) {
        $test = $fallback;
    }
}

if (empty($slug) && empty($all_tests)) {
    $all_tests = $tests_data;
}


$seo_data = [
    'toefl' => [
        'title' => 'TOEFL Coaching in Coimbatore | Expert TOEFL Training',
        'desc' => 'Prepare for TOEFL with experienced trainers, mock tests, and personalized guidance.',
        'h1' => 'TOEFL Coaching for Higher Scores'
    ],
    'ielts' => [
        'title' => 'IELTS Coaching in Coimbatore | Expert IELTS Training',
        'desc' => 'Boost your IELTS score with comprehensive coaching, practice tests, and expert guidance.',
        'h1' => 'IELTS Coaching for Study Abroad Success'
    ]
];

$pageTitle = 'IELTS, TOEFL & Test Preparation Coaching | Bluestone Overseas';
$pageDesc = 'Achieve your target scores with expert coaching for IELTS, TOEFL, PTE, and other study abroad exams.';
$pageH1 = 'Test Preparation for Study Abroad Exams';

if (!empty($slug) && isset($seo_data[$slug])) {
    $pageTitle = $seo_data[$slug]['title'];
    $pageDesc = $seo_data[$slug]['desc'];
    $pageH1 = $seo_data[$slug]['h1'];
}

require_once 'includes/header.php';

?>
<main>

  <?php
    $hero_img = 'assets/images/lh1.jpg';
    if ($test && !empty($test['image_path'])) {
        $hero_img = $test['image_path'];
    }
  ?>
  <section class="section" style="background-color: #ffffff">
    <div class="container">
      <?php if ($test): ?>
        <div class="grid grid--2 gap--4 align-center">
          <div class="animate-on-scroll">
            <h2 class="section__title" style="text-align:left">Why Choose Bluestone for <span><?= $test['name'] ?>?</span></h2>
            <p style="color:var(--gray); margin-bottom:2rem; line-height:1.8"><?= $test['desc'] ?></p>
            <div class="test-features grid grid--1 gap--1">
              <?php foreach($test['features'] as $feat): ?>
                <div class="a-feat"><i class="fa-solid fa-check-circle"></i><span><?= $feat ?></span></div>
              <?php endforeach; ?>
            </div>
            <div style="margin-top:2.5rem">
              <a href="consultation.php" class="btn btn--primary btn--lg">Enroll Now</a>
            </div>
          </div>
          <div class="animate-on-scroll delay-1">
            <div class="image-stack" style="position:relative; margin-bottom:2rem;">
              <img src="<?= !empty($test['image_path']) ? $test['image_path'] : 'assets/images/lh1.jpg' ?>" alt="Test Prep Sessions for IELTS, TOEFL and PTE at Bluestone Overseas" style="width:100%; max-height: 400px; object-fit: cover; border-radius:15px; box-shadow:var(--shadow-lg);">
              <?php if (empty($test['image_path'])): ?>
                <div style="position:absolute; bottom:-20px; right:-20px; width:60%; border:5px solid #fff; border-radius:15px; overflow:hidden; box-shadow:var(--shadow);">
                  <img src="assets/images/lh2.jpg" alt="Bluestone Overseas English Test Preparation Classroom" style="width:100%;">
                </div>
              <?php endif; ?>
            </div>
            <div class="highlight-box">
              <h3><i class="fa-solid fa-graduation-cap" style="color:var(--primary)"></i> Free Demo Class</h3>
              <p>Experience our teaching methodology first-hand. Join a free demo session today!</p>
              <form onsubmit="return handleFormSubmit(event)" style="margin-top:1.5rem">
                <input type="hidden" name="form_type" value="enquiry">
                <input type="hidden" name="destination" value="<?= $test ? $test['name'] : 'Coaching' ?>">
                <input type="hidden" name="study_level" value="Test Preparation">
                <input type="hidden" name="funding_mode" value="Self-funded">
                <input type="hidden" name="counselling_mode" value="In-person">
                
                <input type="text" name="first_name" placeholder="Your Name" class="form-control" style="margin-bottom:1rem; width:100%; padding:.75rem; border:1px solid #e2e8f0; border-radius:8px" required>
                <input type="email" name="email" placeholder="Email Address" class="form-control" style="margin-bottom:1rem; width:100%; padding:.75rem; border:1px solid #e2e8f0; border-radius:8px" required>
                <input type="tel" name="phone" placeholder="Phone Number" class="form-control" style="margin-bottom:1rem; width:100%; padding:.75rem; border:1px solid #e2e8f0; border-radius:8px" required>
                <button type="submit" class="btn btn--primary" style="width:100%; justify-content:center">Book Demo</button>
              </form>
            </div>
          </div>
        </div>
    <?php else: ?>
        
        <?php
        // Fetch all active upcoming batches for the 5 main courses
        try {
            $stmtAllBatches = $pdo->query("SELECT * FROM upcoming_batches WHERE is_active = 1 AND course_slug IN ('ielts', 'toefl', 'pte', 'german', 'japanese') ORDER BY id DESC LIMIT 50");
            $all_batches = $stmtAllBatches->fetchAll(PDO::FETCH_ASSOC);
            
            // Group batches by course_slug
            $grouped_batches = [];
            foreach ($all_batches as $batch) {
                $slug = $batch['course_slug'];
                if (!isset($grouped_batches[$slug])) {
                    $grouped_batches[$slug] = [];
                }
                $grouped_batches[$slug][] = $batch;
            }

            // Define specific order for the 5 main courses to match the requested layout
            $desired_order = ['pte', 'german', 'ielts', 'japanese', 'toefl'];
            $sorted_batches = [];
            foreach ($desired_order as $slug) {
                if (isset($grouped_batches[$slug])) {
                    $sorted_batches[$slug] = $grouped_batches[$slug];
                }
            }
            // Add any other courses that weren't in the specific order list
            foreach ($grouped_batches as $slug => $batches) {
                if (!isset($sorted_batches[$slug])) {
                    $sorted_batches[$slug] = $batches;
                }
            }
            $grouped_batches = $sorted_batches;
        } catch (PDOException $e) {
            $grouped_batches = [];
        }

        if (!empty($grouped_batches)):
        ?>
        <!-- Include Swiper CSS -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.css" />
        
        <div class="upcoming-global-section animate-on-scroll" style="margin-bottom: 5rem; position: relative;">
            <div class="text-center" style="margin-bottom: 3rem;">
                <h2 class="section__title">Upcoming <span>Batches</span></h2>
            </div>
            
            <div style="position: relative; max-width: 950px; margin: 0 auto; padding: 2rem 0;">
                
                <!-- Swiper main container -->
                <div class="swiper batchSwiper">
                    <!-- Additional required wrapper -->
                    <div class="swiper-wrapper">
                        <!-- Slides -->
                        <?php foreach($grouped_batches as $bSlug => $batchesList): 
                            $bUrl = '#';
                            $bTitle = 'Course';
                            $bColor = '#000';
                            $bIcon = '';
                            $bBgColor = '#ffffff';
                            $bBorderColor = 'rgba(0,0,0,0.03)';
                            $bBtnColor = '#ff8c42';
                            
                            if ($bSlug === 'ielts') { 
                                $bUrl = 'ielts-coaching-in-coimbatore.php'; $bTitle = 'IELTS'; $bColor = '#dc2626'; $bIcon = 'IELTS'; 
                                $bBgColor = '#fef2f2'; $bBorderColor = '#fecaca'; $bBtnColor = '#ef4444'; 
                            }
                            elseif ($bSlug === 'pte') { 
                                $bUrl = 'pte.php'; $bTitle = 'PTE'; $bColor = '#ea580c'; $bIcon = 'PTE'; 
                                $bBgColor = '#fff7ed'; $bBorderColor = '#fed7aa'; $bBtnColor = '#f97316'; 
                            }
                            elseif ($bSlug === 'toefl') { 
                                $bUrl = 'toefl.php'; $bTitle = 'TOEFL'; $bColor = '#8b5cf6'; $bIcon = 'TOEFL'; 
                                $bBgColor = '#f5f3ff'; $bBorderColor = '#ddd6fe'; $bBtnColor = '#8b5cf6'; 
                            }
                            elseif ($bSlug === 'german') { 
                                $bUrl = 'german.php'; $bTitle = 'German'; $bColor = '#059669'; $bIcon = 'GERMAN'; 
                                $bBgColor = '#ecfdf5'; $bBorderColor = '#a7f3d0'; $bBtnColor = '#10b981'; 
                            }
                            elseif ($bSlug === 'french') {
                                $bUrl = 'french.php'; $bTitle = 'French'; $bColor = '#2563eb'; $bIcon = 'FRENCH';
                                $bBgColor = '#eff6ff'; $bBorderColor = '#bfdbfe'; $bBtnColor = '#3b82f6';
                            }
                            elseif ($bSlug === 'gre-gmat') {
                                $bUrl = 'gre.php'; $bTitle = 'GRE/GMAT'; $bColor = '#4f46e5'; $bIcon = 'GRE/GMAT';
                                $bBgColor = '#eef2ff'; $bBorderColor = '#c7d2fe'; $bBtnColor = '#6366f1';
                            }
                            elseif ($bSlug === 'sat') {
                                $bUrl = 'sat.php'; $bTitle = 'SAT'; $bColor = '#d97706'; $bIcon = 'SAT';
                                $bBgColor = '#fffbeb'; $bBorderColor = '#fde68a'; $bBtnColor = '#f59e0b';
                            }
                            elseif ($bSlug === 'det') {
                                $bUrl = 'det.php'; $bTitle = 'DET'; $bColor = '#0284c7'; $bIcon = 'DET';
                                $bBgColor = '#f0f9ff'; $bBorderColor = '#bae6fd'; $bBtnColor = '#0ea5e9';
                            }
                            elseif ($bSlug === 'dmat') {
                                $bUrl = 'dmat.php'; $bTitle = 'dMAT'; $bColor = '#b45309'; $bIcon = 'dMAT';
                                $bBgColor = '#fef3c7'; $bBorderColor = '#fde68a'; $bBtnColor = '#d97706';
                            }
                            elseif ($bSlug === 'japanese') { 
                                $bUrl = 'japanese.php'; $bTitle = 'Japanese'; $bColor = '#e11d48'; $bIcon = 'JAPANESE'; 
                                $bBgColor = '#fff1f2'; $bBorderColor = '#fecdd3'; $bBtnColor = '#f43f5e'; 
                            }
                        ?>
                        <div class="swiper-slide batch-slide">
                            <div class="global-batch-card-inner" style="background: <?php echo $bBgColor; ?>; border-radius: 20px; box-shadow: 0 15px 35px rgba(0,0,0,0.1); padding: 2.5rem 1.5rem; text-align: center; border: 8px solid white; transition: all 0.4s ease; position: relative; display: flex; flex-direction: column; height: 100%; outline: 3px solid transparent;">
                                
                                <!-- Floating Icon Badge -->
                                <div class="floating-badge" style="position: absolute; top: -15px; right: -15px; width: 45px; height: 45px; background: #ec4899; border-radius: 50%; color: white; display: flex; align-items: center; justify-content: center; font-size: 1.2rem; border: 3px solid white; box-shadow: 0 4px 10px rgba(0,0,0,0.1); z-index: 5;">
                                    <i class="fa-solid fa-graduation-cap"></i>
                                </div>

                                <!-- Logo / Title -->
                                <div style="font-size: 2.2rem; font-weight: 900; color: <?php echo $bColor; ?>; margin-bottom: 0.5rem; letter-spacing: -1px; font-family: 'Arial Black', sans-serif;">
                                    <?php echo $bIcon; ?>
                                </div>
                                <div style="color: <?php echo $bColor; ?>; opacity: 0.8; font-size: 0.95rem; font-weight: 600; margin-bottom: 1.5rem; text-transform: uppercase;">Upcoming Batches</div>
                                
                                <!-- Details List with Scrollbar -->
                                <div class="batch-list-scroll" style="margin-bottom: 2rem; display: flex; flex-direction: column; gap: 0.75rem; text-align: left; max-height: 250px; overflow-y: auto; padding-right: 0.5rem; flex-grow: 1;">
                                    <?php foreach($batchesList as $batch): ?>
                                    <div style="background: #ffffff; border-radius: 12px; padding: 1rem; border: 1px solid <?php echo $bBorderColor; ?>;">
                                        <div style="font-weight: 800; color: <?php echo $bColor; ?>; font-size: 0.95rem; display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.4rem;">
                                            <i class="fa-regular fa-calendar"></i> <?php echo clean_output($batch['start_date']); ?>
                                        </div>
                                        <div style="font-weight: 600; color: var(--dark); font-size: 0.85rem; display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.2rem;">
                                            <i class="fa-regular fa-clock"></i> <?php echo clean_output($batch['batch_time']); ?>
                                        </div>
                                        <?php if(!empty($batch['duration'])): ?>
                                        <div style="font-weight: 600; color: var(--gray); font-size: 0.8rem; display: flex; align-items: center; gap: 0.5rem;">
                                            <i class="fa-solid fa-layer-group"></i> Level: <?php echo clean_output($batch['duration']); ?>
                                        </div>
                                        <?php endif; ?>
                                    </div>
                                    <?php endforeach; ?>
                                </div>
                                
                                <!-- Actions -->
                                <div style="display: flex; flex-direction: column; gap: 1rem; align-items: center; margin-top: auto;">
                                    <button onclick="openApplyModal('<?php echo $bTitle; ?>')" class="btn btn--primary" style="background: <?php echo $bBtnColor; ?>; border: none; border-radius: 8px; padding: 0.8rem 2rem; width: 100%; font-weight: 700; font-size: 1rem; box-shadow: 0 5px 15px <?php echo $bBorderColor; ?>; color: white; display: flex; justify-content: center; cursor: pointer;">Apply Now</button>
                                </div>
                            </div>
                            <!-- Save course color in a data attribute to apply to outline dynamically -->
                            <div class="color-data" style="display:none;" data-color="<?php echo $bBorderColor; ?>"></div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- Custom Navigation Arrows (floating outside) -->
                <div class="swiper-button-prev custom-swiper-btn"><i class="fa-solid fa-chevron-left"></i></div>
                <div class="swiper-button-next custom-swiper-btn"><i class="fa-solid fa-chevron-right"></i></div>
                
            </div>
            
            <style>
                /* Swiper Container Settings */
                .batchSwiper {
                    width: 100%;
                    padding-top: 50px;
                    padding-bottom: 50px;
                }

                /* Slide Settings */
                .swiper-slide.batch-slide {
                    background-position: center;
                    background-size: cover;
                    width: 360px; /* Width of the card */
                    height: 520px; /* Uniform height */
                    opacity: 1; /* Removed transparency for side cards */
                    transition: transform 0.4s ease;
                }
                
                /* Active Slide gets full opacity and thick outline */
                .swiper-slide-active {
                    opacity: 1;
                    z-index: 2 !important;
                }
                
                /* Thick outline on active card mimicking the yellow outline in user image */
                .swiper-slide-active .global-batch-card-inner {
                    outline: 8px solid #fef08a; /* Soft yellow outline */
                    outline-offset: -8px; /* Inset outline */
                }

                /* Internal scrollbar for batch lists */
                .batch-list-scroll::-webkit-scrollbar { width: 6px; }
                .batch-list-scroll::-webkit-scrollbar-track { background: rgba(0,0,0,0.03); border-radius: 4px; }
                .batch-list-scroll::-webkit-scrollbar-thumb { background: rgba(0,0,0,0.15); border-radius: 4px; }
                .batch-list-scroll::-webkit-scrollbar-thumb:hover { background: rgba(0,0,0,0.25); }
                
                /* Custom Floating Arrows */
                .custom-swiper-btn {
                    position: absolute;
                    top: 50%;
                    transform: translateY(-50%);
                    width: 60px;
                    height: 60px;
                    background: white;
                    border-radius: 50%;
                    box-shadow: 0 10px 30px rgba(0,0,0,0.15);
                    cursor: pointer;
                    z-index: 10;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    color: #ec4899; /* Pink color matching the user image arrows */
                    font-size: 1.5rem;
                    font-weight: 900;
                    transition: all 0.3s ease;
                }
                .custom-swiper-btn:after {
                    display: none; /* Hide default swiper icon */
                }
                .custom-swiper-btn:hover {
                    background: #ec4899;
                    color: white;
                    transform: translateY(-50%) scale(1.1);
                }
                .swiper-button-prev.custom-swiper-btn { left: 10px; }
                .swiper-button-next.custom-swiper-btn { right: 10px; }
                
                @media (max-width: 1200px) {
                    .swiper-button-prev.custom-swiper-btn { left: 0px; }
                    .swiper-button-next.custom-swiper-btn { right: 0px; }
                }
                
                @media (max-width: 768px) {
                    .swiper-slide.batch-slide {
                        width: 300px;
                    }
                    .custom-swiper-btn {
                        display: none; /* Hide arrows on mobile, swipe is better */
                    }
                }
            </style>

            <!-- Swiper JS -->
            <script src="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.js"></script>
            <script>
                // Initialize Swiper
                const swiper = new Swiper('.batchSwiper', {
                    effect: 'coverflow',
                    grabCursor: true,
                    centeredSlides: true,
                    slidesPerView: 'auto',
                    initialSlide: 2, // Start on the third slide (IELTS) to center it
                    loop: true,
                    coverflowEffect: {
                        rotate: 0, // No rotation, just depth
                        stretch: 80, // Negative stretch pulls them closer, positive pushes them apart
                        depth: 200, // Depth of side slides
                        modifier: 1,
                        slideShadows: false, // Turn off shadows to keep it clean
                    },
                    navigation: {
                        nextEl: '.swiper-button-next',
                        prevEl: '.swiper-button-prev',
                    }
                });
            </script>

        </div>
        <?php endif; ?>


        <!-- 1. Why Choose Us -->
        <div class="test-prep-features animate-on-scroll" style="margin-top: 5rem; margin-bottom: 5rem;">
            <div class="text-center" style="margin-bottom: 3rem;">
                <h2 class="section__title">Why Choose Bluestone for <span>Test Prep?</span></h2>
                <p style="color: var(--gray); max-width: 600px; margin: 0 auto; line-height: 1.6;">We don't just teach; we mentor. Our proven strategies and expert trainers ensure you achieve your highest potential score.</p>
            </div>
            <div class="grid grid--3 gap--2">
                <div style="background: #f8fafc; padding: 2.5rem 2rem; border-radius: 16px; text-align: center; border: 1px solid #e2e8f0; transition: transform 0.3s ease, box-shadow 0.3s ease;" onmouseover="this.style.transform='translateY(-10px)'; this.style.boxShadow='0 20px 40px rgba(0,0,0,0.05)';" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none';">
                    <div style="width: 70px; height: 70px; background: rgba(37,99,235,0.1); color: #2563eb; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 1.75rem; margin: 0 auto 1.5rem;">
                        <i class="fa-solid fa-chalkboard-user"></i>
                    </div>
                    <h3 style="font-size: 1.25rem; font-weight: 800; margin-bottom: 1rem; color: var(--dark);">Certified Trainers</h3>
                    <p style="color: var(--gray); font-size: 0.95rem; line-height: 1.6;">Learn from industry experts with years of experience and proven track records in global standardized tests.</p>
                </div>
                <div style="background: #f8fafc; padding: 2.5rem 2rem; border-radius: 16px; text-align: center; border: 1px solid #e2e8f0; transition: transform 0.3s ease, box-shadow 0.3s ease;" onmouseover="this.style.transform='translateY(-10px)'; this.style.boxShadow='0 20px 40px rgba(0,0,0,0.05)';" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none';">
                    <div style="width: 70px; height: 70px; background: rgba(16,185,129,0.1); color: #10b981; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 1.75rem; margin: 0 auto 1.5rem;">
                        <i class="fa-solid fa-book-open-reader"></i>
                    </div>
                    <h3 style="font-size: 1.25rem; font-weight: 800; margin-bottom: 1rem; color: var(--dark);">Premium Material</h3>
                    <p style="color: var(--gray); font-size: 0.95rem; line-height: 1.6;">Get access to exclusive study materials, mock tests, and practice papers tailored to the latest exam patterns.</p>
                </div>
                <div style="background: #f8fafc; padding: 2.5rem 2rem; border-radius: 16px; text-align: center; border: 1px solid #e2e8f0; transition: transform 0.3s ease, box-shadow 0.3s ease;" onmouseover="this.style.transform='translateY(-10px)'; this.style.boxShadow='0 20px 40px rgba(0,0,0,0.05)';" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none';">
                    <div style="width: 70px; height: 70px; background: rgba(245,158,11,0.1); color: #f59e0b; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 1.75rem; margin: 0 auto 1.5rem;">
                        <i class="fa-solid fa-bullseye"></i>
                    </div>
                    <h3 style="font-size: 1.25rem; font-weight: 800; margin-bottom: 1rem; color: var(--dark);">Personalized Coaching</h3>
                    <p style="color: var(--gray); font-size: 0.95rem; line-height: 1.6;">Small batch sizes ensure individual attention, focused doubt-clearing sessions, and customized study plans.</p>
                </div>
            </div>
        </div>

        <!-- 2. Coaching Process -->
        <div class="test-prep-process animate-on-scroll" style="margin-bottom: 5rem; background: var(--dark); border-radius: 24px; padding: 5rem 2rem; color: white;">
            <div class="text-center" style="margin-bottom: 4rem;">
                <h2 class="section__title" style="color: white;">Our Proven <span>Process</span></h2>
                <p style="color: rgba(255,255,255,0.7); max-width: 600px; margin: 0 auto; line-height: 1.6;">A systematic approach designed to elevate your score from day one.</p>
            </div>
            <div class="grid grid--4 gap--2 text-center" style="position: relative;">
                <div>
                    <div style="width: 80px; height: 80px; background: rgba(255,255,255,0.1); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 2rem; margin: 0 auto 1.5rem; color: var(--primary); border: 2px dashed rgba(255,255,255,0.2);">1</div>
                    <h4 style="font-size: 1.2rem; font-weight: 800; margin-bottom: 0.5rem; color: white;">Diagnostic Test</h4>
                    <p style="color: rgba(255,255,255,0.7); font-size: 0.95rem; line-height: 1.5;">Identify your baseline and weak areas.</p>
                </div>
                <div>
                    <div style="width: 80px; height: 80px; background: rgba(255,255,255,0.1); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 2rem; margin: 0 auto 1.5rem; color: var(--primary); border: 2px dashed rgba(255,255,255,0.2);">2</div>
                    <h4 style="font-size: 1.2rem; font-weight: 800; margin-bottom: 0.5rem; color: white;">Concept Building</h4>
                    <p style="color: rgba(255,255,255,0.7); font-size: 0.95rem; line-height: 1.5;">Master the fundamentals and strategies.</p>
                </div>
                <div>
                    <div style="width: 80px; height: 80px; background: rgba(255,255,255,0.1); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 2rem; margin: 0 auto 1.5rem; color: var(--primary); border: 2px dashed rgba(255,255,255,0.2);">3</div>
                    <h4 style="font-size: 1.2rem; font-weight: 800; margin-bottom: 0.5rem; color: white;">Rigorous Practice</h4>
                    <p style="color: rgba(255,255,255,0.7); font-size: 0.95rem; line-height: 1.5;">Sectional tests and timed assignments.</p>
                </div>
                <div>
                    <div style="width: 80px; height: 80px; background: rgba(255,255,255,0.1); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 2rem; margin: 0 auto 1.5rem; color: var(--primary); border: 2px dashed rgba(255,255,255,0.2);">4</div>
                    <h4 style="font-size: 1.2rem; font-weight: 800; margin-bottom: 0.5rem; color: white;">Mock Exams</h4>
                    <p style="color: rgba(255,255,255,0.7); font-size: 0.95rem; line-height: 1.5;">Full-length tests with detailed analysis.</p>
                </div>
            </div>
        </div>

        <!-- 3. FAQ -->
        <div class="test-prep-faq animate-on-scroll" style="margin-bottom: 5rem;">
            <div class="text-center" style="margin-bottom: 3rem;">
                <h2 class="section__title">Frequently Asked <span>Questions</span></h2>
                <p style="color: var(--gray); margin-top: 1rem;">Got questions? We've got answers.</p>
            </div>
            <div style="max-width: 800px; margin: 0 auto;">
                <div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 16px; padding: 2rem; margin-bottom: 1.25rem; box-shadow: 0 4px 6px rgba(0,0,0,0.02);">
                    <h4 style="font-size: 1.15rem; font-weight: 800; margin-bottom: 0.5rem; color: var(--dark); display: flex; align-items: flex-start;"><i class="fa-solid fa-circle-question" style="color: var(--primary); margin-right: 0.75rem; margin-top: 0.2rem;"></i> Do I need to take IELTS or TOEFL?</h4>
                    <p style="color: var(--gray); font-size: 0.95rem; margin-top: 0.75rem; margin-left: 2rem; line-height: 1.6;">It depends on your target country and university. Generally, IELTS is widely accepted in the UK, Australia, and Canada, while TOEFL is popular in the USA. We provide free counseling to help you choose the right test.</p>
                </div>
                <div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 16px; padding: 2rem; margin-bottom: 1.25rem; box-shadow: 0 4px 6px rgba(0,0,0,0.02);">
                    <h4 style="font-size: 1.15rem; font-weight: 800; margin-bottom: 0.5rem; color: var(--dark); display: flex; align-items: flex-start;"><i class="fa-solid fa-circle-question" style="color: var(--primary); margin-right: 0.75rem; margin-top: 0.2rem;"></i> Do you offer online classes?</h4>
                    <p style="color: var(--gray); font-size: 0.95rem; margin-top: 0.75rem; margin-left: 2rem; line-height: 1.6;">Yes! We offer online, offline, and hybrid batches to suit your schedule. Our online classes are live, interactive, and just as effective as in-person training.</p>
                </div>
                <div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 16px; padding: 2rem; margin-bottom: 1.25rem; box-shadow: 0 4px 6px rgba(0,0,0,0.02);">
                    <h4 style="font-size: 1.15rem; font-weight: 800; margin-bottom: 0.5rem; color: var(--dark); display: flex; align-items: flex-start;"><i class="fa-solid fa-circle-question" style="color: var(--primary); margin-right: 0.75rem; margin-top: 0.2rem;"></i> How long does the coaching take?</h4>
                    <p style="color: var(--gray); font-size: 0.95rem; margin-top: 0.75rem; margin-left: 2rem; line-height: 1.6;">Most of our intensive test prep batches run for 4 to 8 weeks, depending on the test and your current proficiency level. We also offer fast-track crash courses for students on a tight deadline.</p>
                </div>
        


      <?php endif; ?>
    </div>
  </section>

<!-- Apply Now Modal (Moved outside animate container) -->
<div id="applyModal" class="custom-modal">
    <div class="custom-modal-content">
        <span class="custom-modal-close" onclick="closeApplyModal()">&times;</span>
        <h3 style="margin-bottom: 1.5rem; color: var(--dark); font-weight: 800; font-family: 'Plus Jakarta Sans', sans-serif;">Apply for <span id="modalCourseName">Course</span></h3>
        <form id="applyForm" onsubmit="submitApplyForm(event)">
            <input type="hidden" name="form_type" value="course_enquiry">
            <input type="hidden" name="destination" id="modalCourseInput" value="">
            <div style="margin-bottom: 1rem;">
                <input type="text" name="first_name" placeholder="Your Name" required style="width: 100%; padding: 0.8rem 1rem; border-radius: 8px; border: 1px solid #ddd; outline: none;">
            </div>
            <div style="margin-bottom: 1rem;">
                <input type="tel" name="phone" placeholder="Phone Number" required style="width: 100%; padding: 0.8rem 1rem; border-radius: 8px; border: 1px solid #ddd; outline: none;">
            </div>
            <div style="margin-bottom: 1.5rem;">
                <input type="email" name="email" placeholder="Email Address" required style="width: 100%; padding: 0.8rem 1rem; border-radius: 8px; border: 1px solid #ddd; outline: none;">
            </div>
            <button type="submit" class="btn btn--primary" style="width: 100%; border-radius: 8px; padding: 1rem; border: none; background: #3b82f6; color: white; font-weight: 700; font-size: 1rem; cursor: pointer; box-shadow: 0 5px 15px rgba(59,130,246,0.3);">Submit Application</button>
        </form>
    </div>
</div>

<style>
.custom-modal {
    display: none; position: fixed; z-index: 10000; left: 0; top: 0; width: 100%; height: 100%; overflow: auto; background-color: rgba(0,0,0,0.6); backdrop-filter: blur(5px);
}
.custom-modal-content {
    background-color: #fefefe; margin: 10vh auto; padding: 2.5rem; border: none; width: 90%; max-width: 450px; border-radius: 20px; box-shadow: 0 25px 50px rgba(0,0,0,0.2); position: relative; animation: modalFadeIn 0.3s ease; text-align: left;
}
.custom-modal-close {
    color: #aaa; position: absolute; top: 15px; right: 20px; font-size: 28px; font-weight: bold; cursor: pointer; transition: color 0.3s;
}
.custom-modal-close:hover, .custom-modal-close:focus { color: #333; text-decoration: none; }
@keyframes modalFadeIn {
    from { opacity: 0; transform: translateY(-20px); }
    to { opacity: 1; transform: translateY(0); }
}

/* Toast Styles */
.custom-toast {
    visibility: hidden; min-width: 280px; background-color: #10b981; color: #fff; text-align: center; border-radius: 8px; padding: 16px; position: fixed; z-index: 10001; left: 50%; bottom: 30px; font-weight: 600; box-shadow: 0 10px 25px rgba(16,185,129,0.4); transform: translateX(-50%); display: flex; align-items: center; justify-content: center; gap: 10px; font-family: 'Plus Jakarta Sans', sans-serif;
}
.custom-toast.show {
    visibility: visible; animation: fadein 0.5s, fadeout 0.5s 2.5s;
}
@keyframes fadein {
    from {bottom: 0; opacity: 0;} to {bottom: 30px; opacity: 1;}
}
@keyframes fadeout {
    from {bottom: 30px; opacity: 1;} to {bottom: 0; opacity: 0;}
}
</style>

<!-- Success Toast -->
<div id="applyToast" class="custom-toast">
    <i class="fa-solid fa-circle-check" style="font-size: 1.2rem;"></i> Application submitted successfully!
</div>

<script>
function openApplyModal(courseName) {
    document.getElementById('modalCourseName').innerText = courseName;
    document.getElementById('modalCourseInput').value = courseName;
    document.getElementById('applyModal').style.display = 'block';
}
function closeApplyModal() {
    document.getElementById('applyModal').style.display = 'none';
}
window.onclick = function(event) {
    if (event.target == document.getElementById('applyModal')) {
        closeApplyModal();
    }
}
function submitApplyForm(e) {
    e.preventDefault();
    const form = e.target;
    const btn = form.querySelector('button[type="submit"]');
    const origText = btn.innerHTML;
    btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Submitting...';
    btn.disabled = true;

    const formData = new FormData(form);
    fetch('submit-enquiry.php', { method: 'POST', body: formData })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                closeApplyModal();
                form.reset();
                var toast = document.getElementById("applyToast");
                toast.className = "custom-toast show";
                setTimeout(function(){ toast.className = toast.className.replace("custom-toast show", "custom-toast"); }, 3000);
            } else {
                alert(data.error || 'Submission failed. Please try again.');
            }
        })
        .catch(err => {
            alert('Failed to connect to server. Please check your connection.');
        })
        .finally(() => {
            btn.innerHTML = origText;
            btn.disabled = false;
        });
}
</script>

</main>
<?php require_once 'includes/footer.php'; ?>
