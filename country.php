<?php
require_once 'includes/config.php';

$slug = $_GET['c'] ?? '';

try {
    $stmt = $pdo->query("SELECT * FROM countries WHERE is_active = 1 ORDER BY id ASC");
    $countries_db = $stmt->fetchAll();
    $countries_data = [];
    foreach ($countries_db as $c) {
        $features = [];
        try {
            $decoded = json_decode($c['features'], true);
            if (is_array($decoded)) {
                $features = $decoded;
            } else {
                $features = $c['features'] ? array_map('trim', explode(',', $c['features'])) : [];
            }
        } catch (Exception $e) {
            $features = [];
        }
        
        $countries_data[$c['slug']] = [
            'name' => $c['name'],
            'img' => get_country_image_url($c['slug'], $c['image_url'] ?? null),
            'desc' => $c['description'],
            'features' => $features
        ];
    }
} catch (PDOException $e) {
    $countries_data = [
        'usa' => [
            'name' => 'United States',
            'img' => get_country_image_url('usa'),
            'desc' => 'The USA is the most popular destination for international students, offering world-class research, flexible curricula, and thousands of universities to choose from.',
            'features' => ['STEM Extensions', 'High Research Output', 'Flexible Coursework']
        ],
        'uk' => [
            'name' => 'United Kingdom',
            'img' => get_country_image_url('uk'),
            'desc' => 'The UK offers prestigious degrees with a global reputation. With 1-year master\'s programs and 2-year post-study work permits, it\'s a top choice for efficiency and career growth.',
            'features' => ['2-Year Post Study Work', 'Renowned Institutions', '1-Year Master\'s']
        ],
        'canada' => [
            'name' => 'Canada',
            'img' => get_country_image_url('canada'),
            'desc' => 'Canada is known for its high standard of living, welcoming culture, and straightforward pathways to permanent residency for international graduates.',
            'features' => ['Easy PR Pathways', 'Affordable Living', 'Safe & Multicultural']
        ],
    ];
}

$country = $countries_data[$slug] ?? null;

if (!$country) {
    // If no slug, show all countries
    } else {
    }

$pageTitle = 'Study Abroad Countries for Indian Students | Bluestone Overseas';
$pageDesc = 'Explore the best countries to study abroad for Indian students including UK, Canada, Australia, Germany, USA, Ireland and more with expert guidance from Bluestone Overseas.';
require_once 'includes/header.php';
?>
<main>

 

  <section class="section">
    <div class="container">
      <?php if ($country): ?>
        <div class="grid grid--2 gap--4 align-center">
          <div class="animate-on-scroll">
            <img src="<?= $country['img'] ?>" alt="<?= $country['name'] ?>" style="border-radius:var(--radius-lg); width:100%; box-shadow:var(--shadow-lg)">
          </div>
          <div class="animate-on-scroll delay-1">
            <h2 class="section__title" style="text-align:left">Why Study in <span><?= $country['name'] ?>?</span></h2>
            <p style="color:var(--gray); margin-bottom:2rem; line-height:1.8">Our experts have years of experience helping students secure admissions in top universities across <?= $country['name'] ?>. We provide end-to-end support from course selection to visa approval.</p>
            <div class="country-features grid grid--1 gap--1">
              <?php foreach($country['features'] as $feat): ?>
                <div class="a-feat"><i class="fa-solid fa-check-circle"></i><span><?= $feat ?></span></div>
              <?php endforeach; ?>
            </div>
            <div style="margin-top:2.5rem">
              <a href="consultation.php" class="btn btn--primary btn--lg">Book Free Consultation</a>
            </div>
          </div>
        </div>
      <?php else: ?>
        <div class="section__header animate-on-scroll" style="text-align: center; margin-bottom: 4rem; position: relative;">
            <div style="display: inline-block; padding: 0.5rem 1.5rem; background: rgba(236, 72, 153, 0.1); color: #ec4899; border-radius: 50px; font-weight: 800; font-size: 0.9rem; text-transform: uppercase; letter-spacing: 2px; margin-bottom: 1rem;">Explore the World</div>
            <h1 class="section__title" style="font-size: 3rem; line-height: 1.2; color: #1e293b; margin-bottom: 1rem; font-weight: 900;">Best Study Abroad Countries<br>for <span style="color: #0ea5e9; font-style: italic;">Indian Students</span></h1>
            <p style="font-size: 1.1rem; color: #64748b; max-width: 600px; margin: 0 auto; line-height: 1.6;">Discover top-ranked universities, vibrant cultures, and excellent career opportunities across the globe with Bluestone Overseas.</p>
            <div class="accent-bar" style="margin: 2rem auto 0; background: linear-gradient(90deg, #ec4899, #0ea5e9); width: 80px; height: 4px; border-radius: 2px;"></div>
        </div>
        <div class="countries-showcase">
          <!-- Iterate all countries from data -->
            <?php
            foreach($countries_data as $s => $c):
              // Map slug to the new SEO-friendly URL if it's one of our 20 pages
              $url = "study-in-{$s}.php";
            ?>
              <div class="country-card animate-on-scroll">
                <div class="country-card__img-wrap">
                  <img src="<?= $c['img'] ?>" alt="<?= $c['name'] ?>" class="country-card__img">
                  <div class="country-card__overlay"></div>
                </div>
                <div class="country-card__body">
                  <div class="country-card__name"><?= $c['name'] ?></div>
                  <div class="country-card__desc"><?= substr($c['desc'], 0, 80) ?>...</div>
                  <a href="<?= $url ?>" class="country-card__link">Learn More <i class="fa-solid fa-arrow-right"></i></a>
                </div>
              </div>
            <?php endforeach; ?>
        </div>
      <?php endif; ?>
    </div>
  </section>
</main>
<?php require_once 'includes/footer.php'; ?>
