<?php
require_once 'includes/config.php';
$pageTitle = 'Top Universities Abroad for Indian Students | Bluestone Overseas';
$pageDesc = 'Find leading universities across the USA, UK, Canada, Australia, Europe, and more.';
require_once 'includes/header.php';

// Fetch active countries for the filter
$countries = [];
try {
    $stmt = $pdo->query("SELECT id, name, flag, slug FROM countries WHERE is_active = 1 ORDER BY name ASC");
    $countries = $stmt->fetchAll();
} catch (PDOException $e) {
    // Silently fail
}

// Get selected country from GET
$selected_country_id = isset($_GET['country']) ? intval($_GET['country']) : 0;

// Fetch universities if country is selected
$universities_list = [];
if ($selected_country_id > 0) {
    try {
        $stmt = $pdo->prepare("
            SELECT * FROM universities 
            WHERE country_id = :cid AND is_active = 1
            ORDER BY name ASC
        ");
        $stmt->execute(['cid' => $selected_country_id]);
        $universities_list = $stmt->fetchAll();
    } catch (PDOException $e) {
        // Silently fail
    }
}
?>

<main>

  <!-- DESTINATION FILTER SECTION -->
  <section class="section filter-section" style="padding-bottom: 2rem; background: #fff;">
    <div class="container">
      <div class="filter-card animate-on-scroll" style="background: white; padding: 2rem; border-radius: 20px; box-shadow: 0 10px 40px rgba(0,0,0,0.05); margin-top: -5rem; position: relative; z-index: 10; border: 1px solid rgba(0,0,0,0.02);">
        <form method="GET" action="universities.php" id="uniFilterForm">
          <div style="display: flex; align-items: flex-end; justify-content: space-between; flex-wrap: wrap; gap: 1.5rem;">
            <div style="flex: 1; min-width: 280px;">
              <label for="country_select" style="display: block; font-weight: 700; margin-bottom: 0.75rem; color: var(--dark); font-size: 0.95rem;">
                <i class="fa-solid fa-earth-americas text-primary" style="margin-right: 0.5rem;"></i> Select Study Destination
              </label>
              <div style="position: relative;">
                <select name="country" id="country_select" class="form-control" style="appearance: none; background: #f8fafc; border: 2px solid #e2e8f0; border-radius: 12px; padding: 0.85rem 1.25rem; width: 100%; font-weight: 500; cursor: pointer; transition: all 0.3s ease;">
                  <option value="">-- Choose Country --</option>
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
                  <i class="fa-solid fa-building-columns"></i> View Universities
               </button>
               <?php if($selected_country_id > 0): ?>
               <a href="universities.php" class="btn btn--ghost" style="padding: 0.85rem 1.5rem; border-radius: 12px; border: 2px solid #e2e8f0;">
                  <i class="fa-solid fa-rotate-left"></i> Reset
               </a>
               <?php endif; ?>
            </div>
          </div>
        </form>
      </div>
    </div>
  </section>

  <!-- UNIVERSITY LISTING SECTION -->
  <section class="section" style="padding-top: 2rem;">
    <div class="container">
      <?php if ($selected_country_id > 0): ?>
        <?php if (empty($universities_list)): ?>
          <div class="text-center animate-on-scroll" style="padding: 4rem 1rem;">
            <div style="font-size: 4rem; color: #f1f5f9; margin-bottom: 1.5rem;"><i class="fa-solid fa-building-columns"></i></div>
            <h3>No universities found for this destination yet.</h3>
            <p style="color: var(--gray); margin-top: 1rem;">Our team is constantly updating our partner network. Please contact us for more information about this destination.</p>
            <a href="contact.php" class="btn btn--primary" style="margin-top: 2rem;">Contact Us</a>
          </div>
        <?php else: ?>
          <div class="animate-on-scroll" style="margin-bottom: 3rem;">
            <h2 style="font-size: 1.75rem; color: var(--dark);">Universities in <span class="text-gradient"><?php 
                $selected_country_name = '';
                foreach($countries as $c) if($c['id'] == $selected_country_id) $selected_country_name = $c['name'];
                echo clean_output($selected_country_name);
            ?></span></h2>
            <div class="accent-bar" style="margin-left: 0;"></div>
          </div>

          <div class="grid grid--3 gap--2">
            <?php foreach ($universities_list as $uni): ?>
              <div class="university-card-modern animate-on-scroll" style="background: white; border-radius: 20px; border: 1px solid #f1f5f9; padding: 2rem; transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1); position: relative; overflow: hidden; display: flex; flex-direction: column; height: 100%;" onmouseover="this.style.transform='translateY(-10px)'; this.style.boxShadow='0 20px 40px rgba(0,0,0,0.08)';" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none';">
                <div style="position: absolute; top: -20px; right: -20px; width: 100px; height: 100px; background: rgba(59, 130, 246, 0.03); border-radius: 50%;"></div>
                
                <div style="flex-grow: 1;">
                  <div style="width: 50px; height: 50px; background: rgba(59, 130, 246, 0.1); color: var(--primary); border-radius: 14px; display: flex; align-items: center; justify-content: center; font-size: 1.25rem; margin-bottom: 1.5rem;">
                    <i class="fa-solid fa-building-columns"></i>
                  </div>
                  
                  <h3 style="font-size: 1.25rem; color: var(--dark); margin-bottom: 0.75rem; line-height: 1.4;"><?= clean_output($uni['name']) ?></h3>
                  
                  <div style="display: flex; flex-wrap: wrap; gap: 0.5rem; margin-bottom: 1.5rem;">
                    <?php if (!empty($uni['qs_ranking'])): ?>
                      <span style="display: flex; align-items: center; gap: 0.4rem; padding: 0.3rem 0.75rem; background: #fff7ed; color: #f97316; border-radius: 30px; font-size: 0.75rem; font-weight: 700; border: 1px solid rgba(249, 115, 22, 0.2);">
                        <i class="fa-solid fa-star"></i> QS: <?= clean_output($uni['qs_ranking']) ?>
                      </span>
                    <?php endif; ?>
                    <span style="display: flex; align-items: center; gap: 0.4rem; padding: 0.3rem 0.75rem; background: #f0fdf4; color: #16a34a; border-radius: 30px; font-size: 0.75rem; font-weight: 700; border: 1px solid rgba(22, 163, 74, 0.2);">
                      <i class="fa-solid fa-check"></i> Partner
                    </span>
                  </div>

                  <div style="border-top: 1px solid #f1f5f9; padding-top: 1.25rem;">
                    <span style="display: block; font-size: 0.75rem; color: var(--gray); text-transform: uppercase; letter-spacing: 1px; font-weight: 700; margin-bottom: 0.5rem;">Primary Focus</span>
                    <p style="font-size: 0.9rem; color: var(--dark); margin: 0; line-height: 1.6;">
                      <?= !empty($uni['specialization']) ? clean_output($uni['specialization']) : 'Multi-disciplinary Excellence' ?>
                    </p>
                  </div>
                </div>

                <div style="margin-top: 2rem; display: flex; gap: 0.75rem;">
                  <a href="courses.php?country=<?= $selected_country_id ?>" class="btn btn--primary" style="flex: 1; padding: 0.7rem; font-size: 0.85rem; border-radius: 10px;">View Courses</a>
                  <a href="scholarships.php?country=<?= $selected_country_id ?>" class="btn btn--ghost" style="padding: 0.7rem 1rem; border-radius: 10px; border: 1px solid #e2e8f0; color: #f59e0b;" title="Scholarships Available">
                    <i class="fa-solid fa-award"></i>
                  </a>
                </div>
              </div>
            <?php endforeach; ?>
          </div>
        <?php endif; ?>
      <?php else: ?>
        <!-- Default Content when no filter selected -->
        <div class="grid grid--2 gap--4 align-center">
          <div class="col-lg-6 mb-4 mb-lg-0 animate-on-scroll">
            <h1 class="section__title" style="text-align:left; margin-top:2rem">Partnering with <span>Global Leaders</span></h1>
            <p class="lead">We have direct partnerships with over 700+ top-ranked universities across 20+ countries, offering you priority processing and exclusive scholarships.</p>
          </div>
          <div class="animate-on-scroll delay-1">
            <div class="service-details grid grid--1 gap--1">
              <div class="a-feat"><i class="fa-solid fa-check-circle"></i><span>Russell Group &amp; Ivy League Pathways</span></div>
              <div class="a-feat"><i class="fa-solid fa-check-circle"></i><span>QS World Ranked Institutions</span></div>
              <div class="a-feat"><i class="fa-solid fa-check-circle"></i><span>Exclusive Partnership Benefits</span></div>
              <div class="a-feat"><i class="fa-solid fa-check-circle"></i><span>Campus Life &amp; Alumni Network Insights</span></div>
            </div>
          </div>
        </div>

        <div style="margin-top: 5rem;">
          <div class="text-center animate-on-scroll">
            <span class="section__tag">Process</span>
            <h2 class="section__title">How It <span>Works</span></h2>
            <p class="section__subtitle" style="max-width: 600px; margin: 0 auto;">A streamlined, step-by-step approach to ensure your success.</p>
          </div>
          <div class="grid grid--4 gap--2" style="margin-top: 3rem;">
            <div class="service-card text-center animate-on-scroll">
              <div class="service-card__icon service-card__icon--blue" style="margin: 0 auto 1.5rem;"><i class="fa-solid fa-1"></i></div>
              <h3>Profile Match</h3>
              <p>We match your academic profile with university admission criteria.</p>
            </div>
            <div class="service-card text-center animate-on-scroll delay-1">
              <div class="service-card__icon service-card__icon--purple" style="margin: 0 auto 1.5rem;"><i class="fa-solid fa-2"></i></div>
              <h3>Virtual Tours</h3>
              <p>Explore campuses, facilities, and city life through our resources.</p>
            </div>
            <div class="service-card text-center animate-on-scroll delay-2">
              <div class="service-card__icon service-card__icon--orange" style="margin: 0 auto 1.5rem;"><i class="fa-solid fa-3"></i></div>
              <h3>Networking</h3>
              <p>Attend our education fairs to meet university representatives directly.</p>
            </div>
            <div class="service-card text-center animate-on-scroll delay-3">
              <div class="service-card__icon service-card__icon--teal" style="margin: 0 auto 1.5rem;"><i class="fa-solid fa-4"></i></div>
              <h3>Enrollment</h3>
              <p>Receive your offer letter and secure your place at the university.</p>
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
          <h3 style="display: flex; align-items: center; gap: 0.5rem;"><i class="fa-solid fa-handshake text-primary"></i> Direct Tie-Ups</h3>
          <p>Benefit from our direct partnerships, which often means waived application fees and faster decisions.</p>
        </div>
        <div class="service-card animate-on-scroll delay-1">
          <h3 style="display: flex; align-items: center; gap: 0.5rem;"><i class="fa-solid fa-ranking-star text-primary"></i> Top Ranked</h3>
          <p>We work exclusively with universities that are recognized globally for their academic excellence.</p>
        </div>
        <div class="service-card animate-on-scroll delay-2">
          <h3 style="display: flex; align-items: center; gap: 0.5rem;"><i class="fa-solid fa-comments text-primary"></i> Alumni Connections</h3>
          <p>Get connected with our past students currently studying at your target university.</p>
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
