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
<div class="container" style="padding-top: 2rem; padding-bottom: 1rem;"><h1 class="section__title" style="text-align:center; margin:0; font-size: 2.2rem;"><?= $pageH1 ?></h1></div>

  <?php
    $hero_img = 'assets/images/lh1.jpg';
    if ($test && !empty($test['image_path'])) {
        $hero_img = $test['image_path'];
    }
  ?>
  <section class="section">
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
        <div class="test-prep-grid grid grid--3 gap--2">
          <?php 
          $colors = ['blue', 'purple', 'orange', 'teal', 'pink', 'gold'];
          $i = 0;
          foreach($all_tests as $s => $data): 
            $color = $data['color'] ?? $colors[$i % count($colors)];
            $i++;
          ?>
            <div class="test-card animate-on-scroll" style="display: flex; flex-direction: column; overflow: hidden; border-radius: 16px; background: #fff; box-shadow: 0 10px 25px rgba(0,0,0,0.05); border: 1px solid rgba(0,0,0,0.05);">
              <?php if (!empty($data['image_path'])): ?>
                <div style="height: 160px; width: 100%; overflow: hidden;">
                  <img src="<?= clean_output($data['image_path']) ?>" alt="<?= clean_output($data['name']) ?>" style="width: 100%; height: 100%; object-fit: cover;">
                </div>
              <?php endif; ?>
              <div class="test-card__header test-card__header--<?= $s ?>" style="padding: 2rem 2rem 1rem; text-align: center; flex-grow: 1;">
                <?php if (empty($data['image_path'])): ?>
                  <div class="stat-icon stat-icon--<?= $color ?>" style="margin: 0 auto 1.25rem;"><i class="fa-solid <?= !empty($data['icon']) ? $data['icon'] : 'fa-pen-to-square' ?>"></i></div>
                <?php endif; ?>
                <h3 style="font-size: 1.5rem; font-weight: 700; color: var(--dark); margin-bottom: 0.5rem;"><?= $data['name'] ?></h3>
                <p style="color: var(--gray); font-size: 0.9rem; line-height: 1.6;"><?= $data['full_name'] ?></p>
              </div>
              <div class="test-card__body" style="padding: 0 2rem 2rem; text-align: center;">
                <p style="font-size:.85rem; color:var(--gray); margin-bottom:1.5rem"><?= substr($data['desc'], 0, 100) ?>...</p>
                <a href="test-prep.php?t=<?= $s ?>" class="btn btn--outline" style="width:100%; justify-content:center">Learn More</a>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>
    </div>
  </section>
</main>
<?php require_once 'includes/footer.php'; ?>
