<?php
require_once 'includes/config.php';
$pageTitle = 'University Selection for Study Abroad | Bluestone Overseas';
$pageDesc = 'Looking for university selection guidance in Coimbatore? Get expert help choosing the right course, country and university for your study abroad goals.';
require_once 'includes/header.php';
?>
<main>
  <section class="section">
    <div class="container">
      <!-- Destination Filter -->
      <div class="filter-card animate-on-scroll" style="margin-bottom: 4rem; background: #fff; padding: 2rem; border-radius: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.05); border: 1px solid #f1f5f9;">
        <form action="" method="GET" class="grid grid--2 gap--2 align-center" style="grid-template-columns: 1fr auto;">
          <div>
            <h3 style="margin: 0; font-size: 1.25rem;">Select your dream destination to explore our partner universities.</h3>
          </div>
          <div style="display: flex; gap: 1rem;">
            <select name="country_id" class="form-control" style="min-width: 250px; padding: 0.75rem 1rem; border-radius: 10px; border: 1px solid #e2e8f0;" onchange="this.form.submit()">
              <option value="">-- Choose Country --</option>
              <?php
              $countries = $pdo->query("SELECT id, name, flag FROM countries WHERE is_active = 1 ORDER BY name ASC")->fetchAll();
              $selectedCountry = $_GET['country_id'] ?? 0;
              foreach ($countries as $c):
              ?>
                <option value="<?= $c['id'] ?>" <?= $selectedCountry == $c['id'] ? 'selected' : '' ?>>
                  <?= clean_output($c['name']) ?>
                </option>
              <?php endforeach; ?>
            </select>
            <button type="submit" class="btn btn--primary">Search</button>
          </div>
        </form>
      </div>

      <?php if ($selectedCountry): ?>
        <div class="grid grid--3 gap--2 animate-on-scroll">
          <?php
          $stmt = $pdo->prepare("SELECT * FROM universities WHERE country_id = ? AND is_active = 1 ORDER BY name ASC");
          $stmt->execute([$selectedCountry]);
          $unis = $stmt->fetchAll();

          if ($unis):
            foreach ($unis as $u):
          ?>
            <div class="service-card" style="padding: 2rem; background: #fff; border-radius: 15px; border: 1px solid #f1f5f9; transition: var(--transition);">
              <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 1.5rem;">
                <div class="v-icon" style="width:50px; height:50px; font-size:1.2rem; margin:0;"><i class="fa-solid fa-building-columns"></i></div>
                <h4 style="margin: 0; font-size: 1.1rem; line-height: 1.3;"><?= clean_output($u['name']) ?></h4>
              </div>
              <div style="font-size: 0.85rem; color: var(--gray); margin-bottom: 1.5rem;">
                <p><i class="fa-solid fa-ranking-star text-primary"></i> Global Ranking: <strong>#<?= clean_output($u['qs_ranking'] ?: 'N/A') ?></strong></p>
                <p><i class="fa-solid fa-graduation-cap text-primary"></i> Specialized in: <?= clean_output($u['specialization'] ?: 'General Studies') ?></p>
              </div>
              <a href="courses.php?university_id=<?= $u['id'] ?>" class="btn btn--outline btn--sm" style="width: 100%; justify-content: center;">View Courses</a>
            </div>
          <?php 
            endforeach;
          else:
          ?>
            <div class="col-span-3 text-center py-5">
              <p style="color: var(--gray);">No universities listed for this country yet. Contact us for the full list of our 500+ partners.</p>
            </div>
          <?php endif; ?>
        </div>
      <?php else: ?>
        <div class="text-center py-5 animate-on-scroll" style="opacity: 0.6;">
            <i class="fa-solid fa-building-columns" style="font-size: 3rem; margin-bottom: 1.5rem; color: #e2e8f0;"></i>
            <p>Select a country above to view the elite universities we partner with.</p>
        </div>
      <?php endif; ?>
    </div>
  </section>

  <section class="section bg-light">
    <div class="container">
      <div class="grid grid--2 gap--4 align-center">
        <div class="animate-on-scroll">
          <div class="v-icon" style="width:120px; height:120px; font-size:3rem; margin:0"><i class="fa-solid fa-map-location-dot"></i></div>
          <h2 class="section__title" style="text-align:left; margin-top:2rem">Expert <span>Shortlisting</span></h2>
          <p style="color:var(--gray); margin-top:1rem; line-height:1.6;">
            Choosing where to study is a life-changing decision. We don't just give you a list; we provide a strategy. Based on your grades, career goals, and budget, we help you pick the best fit.
          </p>
          <div class="service-details grid grid--1 gap--1" style="margin-top: 2rem;">
            <div class="a-feat"><i class="fa-solid fa-check-circle"></i><span>Academic Profile Assessment</span></div>
            <div class="a-feat"><i class="fa-solid fa-check-circle"></i><span>University Ranking Comparisons</span></div>
            <div class="a-feat"><i class="fa-solid fa-check-circle"></i><span>Course Curriculum Analysis</span></div>
            <div class="a-feat"><i class="fa-solid fa-check-circle"></i><span>Post-Study Work Opportunity Check</span></div>
          </div>
        </div>
        <div class="animate-on-scroll delay-1">
          <div style="background: white; padding: 3rem; border-radius: 24px; box-shadow: var(--shadow); border: 1px solid #f1f5f9;">
            <h3 style="margin-bottom: 1.5rem;">Why Selection Matters?</h3>
            <p style="font-size: 0.95rem; color: var(--gray); line-height: 1.8;">
                The wrong choice can cost you thousands in tuition and years of time. Our data-driven approach ensures you apply to universities where you have the highest probability of both <strong>Admission</strong> and <strong>Career Success</strong>.
            </p>
            <div style="margin-top: 2rem; padding: 1.5rem; background: #f8fafc; border-radius: 12px; display: flex; align-items: center; gap: 1rem;">
                <div style="width: 50px; height: 50px; background: var(--primary); border-radius: 50%; display: grid; place-items: center; color: white;">
                    <i class="fa-solid fa-quote-left"></i>
                </div>
                <p style="font-style: italic; font-size: 0.9rem; color: var(--dark);">"Bluestone helped me find a university that perfectly matched my budget and research interests." - Sneha R.</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <section class="section">
    <div class="container animate-on-scroll">
      <div style="background: var(--gradient); padding: 4rem 2rem; border-radius: var(--radius-lg); text-align: center; color: white; box-shadow: var(--shadow-lg);">
        <h2 style="font-size: 2.5rem; margin-bottom: 1rem;">Ready to Find Your Match?</h2>
        <p style="font-size: 1.1rem; opacity: 0.9; max-width: 600px; margin: 0 auto 2rem;">Our experts have helped over 10,000 students find their perfect academic home.</p>
        <a href="consultation.php" class="btn btn--white btn--lg pulse-btn" style="background: white; color: var(--primary);">Start Selection Process</a>
      </div>
    </div>
  </section>
</main>
<?php require_once 'includes/footer.php'; ?>
