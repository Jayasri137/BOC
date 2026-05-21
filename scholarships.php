<?php
require_once 'includes/config.php';
$pageTitle = 'Scholarships & Grants | Bluestone Overseas Consultants';
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

// Fetch scholarships if country is selected
$universities_with_scholarships = [];
if ($selected_country_id > 0) {
    try {
        $stmt = $pdo->prepare("
            SELECT u.name as university_name, s.* 
            FROM scholarships s
            JOIN universities u ON s.university_id = u.id
            WHERE u.country_id = :cid AND s.is_active = 1 AND u.is_active = 1
            ORDER BY u.name ASC, s.name ASC
        ");
        $stmt->execute(['cid' => $selected_country_id]);
        $scholarships = $stmt->fetchAll();
        
        // Group by university
        foreach ($scholarships as $row) {
            $universities_with_scholarships[$row['university_name']][] = $row;
        }
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
        <form method="GET" action="scholarships.php" id="scholFilterForm">
          <div style="display: flex; align-items: flex-end; justify-content: space-between; flex-wrap: wrap; gap: 1.5rem;">
            <div style="flex: 1; min-width: 280px;">
              <label for="country_select" style="display: block; font-weight: 700; margin-bottom: 0.75rem; color: var(--dark); font-size: 0.95rem;">
                <i class="fa-solid fa-earth-americas text-primary" style="margin-right: 0.5rem;"></i> Choose Destination
              </label>
              <div style="position: relative;">
                <select name="country" id="country_select" class="form-control" style="appearance: none; background: #f8fafc; border: 2px solid #e2e8f0; border-radius: 12px; padding: 0.85rem 1.25rem; width: 100%; font-weight: 500; cursor: pointer; transition: all 0.3s ease;">
                  <option value="">-- All Destinations --</option>
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
               <button type="submit" class="btn btn--primary" style="padding: 0.85rem 2rem; border-radius: 12px; font-weight: 600; background: #f59e0b; border-color: #f59e0b;">
                  <i class="fa-solid fa-magnifying-glass"></i> Find Scholarships
               </button>
               <?php if($selected_country_id > 0): ?>
               <a href="scholarships.php" class="btn btn--ghost" style="padding: 0.85rem 1.5rem; border-radius: 12px; border: 2px solid #e2e8f0;">
                  <i class="fa-solid fa-rotate-left"></i> Reset
               </a>
               <?php endif; ?>
            </div>
          </div>
        </form>
      </div>
    </div>
  </section>

  <!-- SCHOLARSHIP LISTING SECTION -->
  <section class="section" style="padding-top: 2rem;">
    <div class="container">
      <?php if ($selected_country_id > 0): ?>
        <?php if (empty($universities_with_scholarships)): ?>
          <div class="text-center animate-on-scroll" style="padding: 4rem 1rem;">
            <div style="font-size: 4rem; color: #fef3c7; margin-bottom: 1.5rem;"><i class="fa-solid fa-award"></i></div>
            <h3>No university scholarships found for this destination yet.</h3>
            <p style="color: var(--gray); margin-top: 1rem;">However, there are many global grants you might be eligible for. Contact our experts for a personalized list.</p>
            <a href="consultation.php" class="btn btn--primary" style="margin-top: 2rem; background: #f59e0b; border-color: #f59e0b;">Get Expert Advice</a>
          </div>
        <?php else: ?>
          <div class="animate-on-scroll" style="margin-bottom: 3rem;">
            <h2 style="font-size: 1.75rem; color: var(--dark);">University Scholarships in <span style="color: #f59e0b;"><?php 
                $selected_country_name = '';
                foreach($countries as $c) if($c['id'] == $selected_country_id) $selected_country_name = $c['name'];
                echo clean_output($selected_country_name);
            ?></span></h2>
            <div class="accent-bar" style="margin-left: 0; background: #f59e0b;"></div>
          </div>

          <?php foreach ($universities_with_scholarships as $uni_name => $uni_scholarships): ?>
            <div class="university-group animate-on-scroll" style="margin-bottom: 4rem;">
              <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 2rem; border-bottom: 2px solid #fffbeb; padding-bottom: 1rem;">
                <div style="width: 45px; height: 45px; background: #f59e0b; color: white; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.25rem;">
                  <i class="fa-solid fa-building-columns"></i>
                </div>
                <h3 style="font-size: 1.4rem; color: var(--dark);"><?= clean_output($uni_name) ?></h3>
              </div>

              <div class="grid grid--2 gap--2">
                <?php foreach ($uni_scholarships as $schol): ?>
                  <div class="scholarship-card" style="background: white; border-radius: 16px; border: 1px solid #fffbeb; padding: 2rem; transition: all 0.3s ease; display: flex; flex-direction: column; height: 100%; box-shadow: 0 4px 15px rgba(245,158,11,0.05);" onmouseover="this.style.transform='translateY(-5px)'; this.style.boxShadow='0 12px 25px rgba(245,158,11,0.1)'; this.style.borderColor='#f59e0b';" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 15px rgba(245,158,11,0.05)'; this.style.borderColor='#fffbeb';">
                    <div style="flex-grow: 1;">
                      <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 1.5rem;">
                        <span style="display: inline-block; padding: 0.4rem 1rem; background: #fffbeb; color: #f59e0b; border-radius: 30px; font-size: 0.75rem; font-weight: 700; text-transform: uppercase; border: 1px solid rgba(245, 158, 11, 0.2);">Scholarship</span>
                        <div style="color: #f59e0b; font-size: 1.25rem;"><i class="fa-solid fa-award"></i></div>
                      </div>
                      
                      <h4 style="font-size: 1.25rem; color: var(--dark); margin-bottom: 1rem; line-height: 1.4;"><?= clean_output($schol['name']) ?></h4>
                      
                      <div style="background: #f8fafc; padding: 1.25rem; border-radius: 12px; margin-bottom: 1.5rem;">
                        <span style="display: block; font-size: 0.8rem; color: var(--gray); text-transform: uppercase; letter-spacing: 1px; font-weight: 700; margin-bottom: 0.5rem;">Scholarship Value</span>
                        <h5 style="font-size: 1.4rem; color: #f59e0b; margin: 0; font-weight: 800;"><?= clean_output($schol['amount']) ?></h5>
                      </div>

                      <div style="display: grid; grid-template-columns: 1fr; gap: 1rem;">
                        <div style="display: flex; align-items: flex-start; gap: 0.75rem; color: var(--gray); font-size: 0.9rem;">
                          <i class="fa-solid fa-user-check" style="color: #f59e0b; margin-top: 0.2rem;"></i>
                          <span><strong>Eligibility:</strong> <?= clean_output($schol['eligibility']) ?></span>
                        </div>
                        <div style="display: flex; align-items: center; gap: 0.75rem; color: var(--gray); font-size: 0.9rem;">
                          <i class="fa-solid fa-calendar-xmark" style="color: #f59e0b;"></i>
                          <span><strong>Deadline:</strong> <?= clean_output($schol['deadline']) ?></span>
                        </div>
                      </div>
                    </div>
                    
                    <div style="margin-top: 2rem; padding-top: 1.5rem; border-top: 1px solid #f1f5f9;">
                      <a href="consultation.php?enquiry=scholarship&name=<?= urlencode($schol['name']) ?>" class="btn btn--primary btn--sm" style="width: 100%; border-radius: 10px; background: #f59e0b; border-color: #f59e0b;">Check Eligibility</a>
                    </div>
                  </div>
                <?php endforeach; ?>
              </div>
            </div>
          <?php endforeach; ?>
        <?php endif; ?>
      <?php else: ?>
        <!-- Default Content when no filter selected -->
        <div class="grid grid--2 gap--4 align-center">
          <div class="animate-on-scroll">
            <div class="v-icon" style="width:120px; height:120px; font-size:3rem; margin:0; background: #fffbeb; color: #f59e0b;"><i class="fa-solid fa-graduation-cap"></i></div>
            <h2 class="section__title" style="text-align:left; margin-top:2rem">Fund Your <span>Future</span></h2>
            <p style="color:var(--gray); margin-top:1rem; line-height:1.6;">
              In association with <strong>Bluestone Overseas</strong> and drawing inspiration from global standards set by leaders like <strong>IDP.com</strong>, we help you identify and apply for scholarships that can fund up to 100% of your tuition.
            </p>
          </div>
          <div class="animate-on-scroll delay-1">
            <div class="service-details grid grid--1 gap--1">
              <div class="a-feat"><i class="fa-solid fa-check-circle" style="color: #f59e0b;"></i><span>Merit-Based Scholarships</span></div>
              <div class="a-feat"><i class="fa-solid fa-check-circle" style="color: #f59e0b;"></i><span>Need-Based Financial Aid</span></div>
              <div class="a-feat"><i class="fa-solid fa-check-circle" style="color: #f59e0b;"></i><span>Government &amp; Private Grants</span></div>
              <div class="a-feat"><i class="fa-solid fa-check-circle" style="color: #f59e0b;"></i><span>Scholarship Essay Support</span></div>
            </div>
          </div>
        </div>

        <!-- PROCESS SECTION -->
        <div style="margin-top: 5rem;">
          <div class="text-center animate-on-scroll">
            <span class="section__tag">Steps</span>
            <h2 class="section__title">How It <span>Works</span></h2>
            <p class="section__subtitle" style="max-width: 600px; margin: 0 auto;">A streamlined, step-by-step approach to ensuring your financial success.</p>
          </div>
          <div class="grid grid--4 gap--2" style="margin-top: 3rem;">
            <div class="service-card text-center animate-on-scroll">
              <div class="service-card__icon" style="margin: 0 auto 1.5rem; background: #eff6ff; color: #3b82f6;"><i class="fa-solid fa-1"></i></div>
              <h3>Eligibility</h3>
              <p>We assess your profile against various global scholarship criteria.</p>
            </div>
            <div class="service-card text-center animate-on-scroll delay-1">
              <div class="service-card__icon" style="margin: 0 auto 1.5rem; background: #f5f3ff; color: #8b5cf6;"><i class="fa-solid fa-2"></i></div>
              <h3>Matching</h3>
              <p>Match with university-specific, government, and private funding.</p>
            </div>
            <div class="service-card text-center animate-on-scroll delay-2">
              <div class="service-card__icon" style="margin: 0 auto 1.5rem; background: #fff7ed; color: #f97316;"><i class="fa-solid fa-3"></i></div>
              <h3>Documentation</h3>
              <p>Assistance with scholarship essays and letters of recommendation.</p>
            </div>
            <div class="service-card text-center animate-on-scroll delay-3">
              <div class="service-card__icon" style="margin: 0 auto 1.5rem; background: #f0fdfa; color: #14b8a6;"><i class="fa-solid fa-4"></i></div>
              <h3>Application</h3>
              <p>We submit applications alongside your university admission file.</p>
            </div>
          </div>
        </div>
      <?php endif; ?>
    </div>
  </section>

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

  <section class="section" style="padding-top: 0;">
    <div class="container animate-on-scroll">
      <div style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); padding: 4rem 2rem; border-radius: var(--radius-lg); text-align: center; color: white; box-shadow: 0 20px 40px rgba(245, 158, 11, 0.3);">
        <h2 style="font-size: 2.5rem; margin-bottom: 1rem;">Unlock Your Financial Potential</h2>
        <p style="font-size: 1.1rem; opacity: 0.9; max-width: 600px; margin: 0 auto 2rem;">Don't let budget constraints stop you from achieving your dreams. Get a free financial assessment today.</p>
        <a href="consultation.php" class="btn btn--white btn--lg pulse-btn" style="background: white; color: #f59e0b;">Book Free Assessment</a>
      </div>
    </div>
  </section>
</main>
<?php require_once 'includes/footer.php'; ?>
