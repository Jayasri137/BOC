<?php
require_once 'includes/config.php';
$pageTitle = 'Admission Processing for Study Abroad | Bluestone Overseas';
$pageDesc = 'Apply to top universities with confidence. Expert admission processing, application support, document verification and university admission guidance.';
require_once 'includes/header.php';
?>
<main>
  <section class="section">
    <div class="container">
      <!-- Destination Filter -->
      <div class="filter-card animate-on-scroll" style="margin-bottom: 4rem; background: #fff; padding: 2rem; border-radius: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.05); border: 1px solid #f1f5f9;">
        <form action="" method="GET" class="grid grid--2 gap--2 align-center" style="grid-template-columns: 1fr auto;">
          <div>
            <h3 style="margin: 0; font-size: 1.25rem;">Select your destination to view application-ready universities.</h3>
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
                  <?= clean_output($c['flag'] . ' ' . $c['name']) ?>
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
                <div class="v-icon" style="width:50px; height:50px; font-size:1.2rem; margin:0; background: linear-gradient(135deg, #f59e0b, #fbbf24);"><i class="fa-solid fa-file-circle-check"></i></div>
                <h4 style="margin: 0; font-size: 1.1rem; line-height: 1.3;"><?= clean_output($u['name']) ?></h4>
              </div>
              <div style="font-size: 0.85rem; color: var(--gray); margin-bottom: 1.5rem;">
                <p><i class="fa-solid fa-clock text-primary"></i> Intake Status: <strong>Open for 2026</strong></p>
                <p><i class="fa-solid fa-bolt text-primary"></i> Application Mode: <strong>Fast-Track Available</strong></p>
              </div>
              <a href="enquiry.php?university=<?= urlencode($u['name']) ?>" class="btn btn--primary btn--sm" style="width: 100%; justify-content: center;">Start Application</a>
            </div>
          <?php 
            endforeach;
          else:
          ?>
            <div class="col-span-3 text-center py-5">
              <p style="color: var(--gray);">No active intakes found for this country. Please contact our admission desk for manual processing.</p>
            </div>
          <?php endif; ?>
        </div>
      <?php else: ?>
        <div class="text-center py-5 animate-on-scroll" style="opacity: 0.6;">
            <i class="fa-solid fa-file-signature" style="font-size: 3rem; margin-bottom: 1.5rem; color: #e2e8f0;"></i>
            <p>Choose a destination above to see universities ready for admission processing.</p>
        </div>
      <?php endif; ?>
    </div>
  </section>

  <section class="section bg-light">
    <div class="container">
      <div class="grid grid--2 gap--4 align-center">
        <div class="animate-on-scroll">
          <div class="v-icon" style="width:120px; height:120px; font-size:3rem; margin:0; background: linear-gradient(135deg, #f59e0b, #fbbf24);"><i class="fa-solid fa-file-signature"></i></div>
          <h2 class="section__title" style="text-align:left; margin-top:2rem">Seamless <span>Application</span></h2>
          <p style="color:var(--gray); margin-top:1rem; line-height:1.6;">
            We don't just fill forms; we tell your story. Our admission team works closely with you to craft a compelling Statement of Purpose (SOP) that highlights your strengths and aspirations.
          </p>
          <div class="service-details grid grid--1 gap--1" style="margin-top: 2rem;">
            <div class="a-feat"><i class="fa-solid fa-check-circle" style="color:#f59e0b;"></i><span>SOP & LOR Editing Support</span></div>
            <div class="a-feat"><i class="fa-solid fa-check-circle" style="color:#f59e0b;"></i><span>Document Verification & Notarization</span></div>
            <div class="a-feat"><i class="fa-solid fa-check-circle" style="color:#f59e0b;"></i><span>Direct Portal Submission</span></div>
            <div class="a-feat"><i class="fa-solid fa-check-circle" style="color:#f59e0b;"></i><span>Regular Application Tracking</span></div>
          </div>
        </div>
        <div class="animate-on-scroll delay-1">
          <div style="background: white; padding: 3rem; border-radius: 24px; box-shadow: var(--shadow); border: 1px solid #f1f5f9;">
            <h3 style="margin-bottom: 1.5rem;">Admission Advantage</h3>
            <p style="font-size: 0.95rem; color: var(--gray); line-height: 1.8;">
                Did you know that 30% of applications are rejected due to simple documentation errors? At Bluestone, we have a <strong>99% Admission Success Rate</strong> thanks to our rigorous multi-level quality check process.
            </p>
            <div style="margin-top: 2rem; padding: 1.5rem; background: #fff7ed; border-radius: 12px; display: flex; align-items: center; gap: 1rem;">
                <div style="width: 50px; height: 50px; background: #f59e0b; border-radius: 50%; display: grid; place-items: center; color: white;">
                    <i class="fa-solid fa-clock-rotate-left"></i>
                </div>
                <p style="font-size: 0.9rem; color: #9a3412; font-weight: 600;">Fast-Track your offer letter in as little as 48 hours for selected partner universities!</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <section class="section">
    <div class="container animate-on-scroll">
      <div style="background: linear-gradient(135deg, #f59e0b, #d97706); padding: 4rem 2rem; border-radius: var(--radius-lg); text-align: center; color: white; box-shadow: var(--shadow-lg);">
        <h2 style="font-size: 2.5rem; margin-bottom: 1rem;">Ready to Apply?</h2>
        <p style="font-size: 1.1rem; opacity: 0.9; max-width: 600px; margin: 0 auto 2rem;">Don't wait for deadlines to approach. Start your application today with our expert team.</p>
        <a href="consultation.php" class="btn btn--white btn--lg pulse-btn" style="background: white; color: #d97706;">Begin My Application</a>
      </div>
    </div>
  </section>
</main>
<?php require_once 'includes/footer.php'; ?>
