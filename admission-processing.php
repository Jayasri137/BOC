<?php
require_once 'includes/config.php';
$pageTitle = 'Study Abroad Admission Processing Services | Bluestone Overseas';
$pageDesc = 'Simplify your study abroad journey with professional admission processing and application support.';
$pageHeroImage = 'assets/images/Offer.png';

require_once 'includes/header.php';
?>
<main>
  <section class="section" style="background-color:#ffffff;">
    <div class="container" >
      <!-- Destination Filter -->
      <div class="filter-card animate-on-scroll" style="margin: 0 0 4rem; background: #fff; padding: 2.5rem; border-radius: 24px; box-shadow: 0 15px 40px rgba(0,0,0,0.08); border: 1px solid #f1f5f9;">
        <form action="" method="GET" style="display: flex; flex-wrap: wrap; gap: 2rem; align-items: center; justify-content: space-between;">
          <div style="flex: 1; min-width: 300px;">
            <h3 style="margin: 0; font-size: 1.5rem; color: var(--dark);">Universities Open for Admission</h3>
            <p style="margin: 0.5rem 0 0; color: var(--gray);">Select your destination to view application-ready universities.</p>
          </div>
          <div style="display: flex; gap: 1rem; align-items: center; flex-wrap: wrap;">
            <select name="country_id" class="form-control" style="min-width: 250px; padding: 1rem 1.5rem; border-radius: 12px; border: 1px solid #e2e8f0; font-size: 1rem; background: #f8fafc;" onchange="this.form.submit()">
              <option value="">-- Choose Country --</option>
              <?php
              try {
                  $countries = $pdo->query("SELECT id, name, flag FROM countries WHERE is_active = 1 ORDER BY name ASC")->fetchAll();
              } catch (PDOException $e) {
                  $countries = [];
              }
              $selectedCountry = $_GET['country_id'] ?? 0;
              foreach ($countries as $c):
              ?>
                <option value="<?= $c['id'] ?>" <?= $selectedCountry == $c['id'] ? 'selected' : '' ?>>
                  <?= clean_output($c['name']) ?>
                </option>
              <?php endforeach; ?>
            </select>
            <button type="submit" class="btn btn--primary" style="padding: 1rem 2rem; border-radius: 12px; background: var(--primary);">Search</button>
          </div>
        </form>
      </div>

      <?php if ($selectedCountry): ?>
        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(320px, 1fr)); gap: 2rem;" class="animate-on-scroll">
          <?php
          try {
              $stmt = $pdo->prepare("SELECT * FROM universities WHERE country_id = ? AND is_active = 1 ORDER BY name ASC");
              $stmt->execute([$selectedCountry]);
              $unis = $stmt->fetchAll();
          } catch (PDOException $e) {
              $unis = [];
          }

          if ($unis):
            foreach ($unis as $u):
              $imgUrl = !empty($u['image_url']) ? htmlspecialchars($u['image_url']) : 'https://images.unsplash.com/photo-1523050854058-8df90110c9f1?auto=format&fit=crop&q=80&w=800';
          ?>
            <div style="background: #fff; border-radius: 24px; border: 1px solid rgba(0,0,0,0.05); box-shadow: 0 10px 30px rgba(0,0,0,0.04); transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275); overflow: hidden; position: relative; display: flex; flex-direction: column;" class="uni-card" onmouseover="this.style.transform='translateY(-12px)'; this.style.boxShadow='0 25px 50px rgba(245,158,11,0.15)';" onmouseout="this.style.transform='none'; this.style.boxShadow='0 10px 30px rgba(0,0,0,0.04)';">
              <!-- Cover Image -->
              <div style="height: 180px; width: 100%; background: url('<?= $imgUrl ?>') center/cover; position: relative;">
                 <div style="position: absolute; inset: 0; background: linear-gradient(to top, rgba(0,0,0,0.7), transparent);"></div>
                 <div style="position: absolute; bottom: 1.5rem; left: 1.5rem; display: flex; gap: 0.5rem;">
                    <span style="background: rgba(255,255,255,0.2); backdrop-filter: blur(8px); -webkit-backdrop-filter: blur(8px); color: white; padding: 0.4rem 0.8rem; border-radius: 50px; font-size: 0.8rem; font-weight: 600; border: 1px solid rgba(255,255,255,0.3);"><i class="fa-solid fa-bolt" style="color: #fbbf24;"></i> Accepting Applications</span>
                 </div>
              </div>

              <!-- Content -->
              <div style="padding: 2rem 1.5rem; flex: 1; display: flex; flex-direction: column;">
                <div style="display: flex; align-items: flex-start; gap: 1rem; margin-bottom: 1.5rem; position: relative; margin-top: -4rem;">
                  <div style="width: 70px; height: 70px; border-radius: 20px; background: white; padding: 0.5rem; box-shadow: 0 10px 25px rgba(0,0,0,0.1); border: 2px solid white; display: flex; align-items: center; justify-content: center; font-size: 1.8rem; color: #f59e0b; flex-shrink: 0; position: relative; z-index: 2;">
                      <i class="fa-solid fa-file-circle-check"></i>
                  </div>
                </div>
                
                <h4 style="margin: 0 0 1.25rem; font-size: 1.3rem; line-height: 1.3; color: var(--dark); font-weight: 700;"><?= clean_output($u['name']) ?></h4>

                <div style="font-size: 0.95rem; color: var(--gray); margin-bottom: 2rem; display: flex; flex-direction: column; gap: 1rem; flex: 1;">
                  <div style="display: flex; align-items: center; gap: 0.8rem;">
                     <div style="width: 36px; height: 36px; border-radius: 10px; background: #fff7ed; display: flex; align-items: center; justify-content: center; color: #ea580c; flex-shrink: 0;"><i class="fa-solid fa-clock"></i></div>
                     <span style="line-height: 1.4;">Intake Status: <br><strong style="color: var(--dark); font-size: 1.05rem;">Open for 2026</strong></span>
                  </div>
                  <div style="display: flex; align-items: center; gap: 0.8rem;">
                     <div style="width: 36px; height: 36px; border-radius: 10px; background: #ecfdf5; display: flex; align-items: center; justify-content: center; color: #10b981; flex-shrink: 0;"><i class="fa-solid fa-bolt"></i></div>
                     <span style="line-height: 1.4;">Application Mode: <br><strong style="color: var(--dark); font-size: 1.05rem;">Fast-Track Available</strong></span>
                  </div>
                </div>

                <a href="enquiry.php?university=<?= urlencode($u['name']) ?>" class="btn btn--primary" style="width: 100%; justify-content: center; border-radius: 12px; padding: 0.85rem; font-weight: 600; background: #f59e0b; box-shadow: 0 8px 20px rgba(245,158,11,0.25); border: none; text-transform: uppercase; font-size: 0.9rem; letter-spacing: 0.5px;">Start Application <i class="fa-solid fa-arrow-right" style="margin-left: 0.5rem;"></i></a>
              </div>
            </div>
          <?php 
            endforeach;
          else:
          ?>
            <div style="grid-column: 1 / -1; text-align: center; padding: 4rem 2rem; background: #f8fafc; border-radius: 20px;">
              <p style="color: var(--gray); font-size: 1.1rem;">No active intakes found for this country. Please contact our admission desk for manual processing.</p>
            </div>
          <?php endif; ?>
        </div>
      <?php else: ?>
        <div class="text-center animate-on-scroll" style="opacity: 0.6; padding: 4rem 0;">
            <i class="fa-solid fa-file-signature" style="font-size: 4rem; margin-bottom: 1.5rem; color: #cbd5e1;"></i>
            <p style="font-size: 1.2rem; color: var(--gray);">Choose a destination above to see universities ready for admission processing.</p>
        </div>
      <?php endif; ?>
    </div>
  </section>

  <!-- Premium Intro Section -->
  <section class="section" style="position: relative; overflow: hidden; padding-top: 6rem; padding-bottom: 5rem;">
    <!-- Decorative background blobs -->
    <div style="position: absolute; top: -100px; left: -100px; width: 400px; height: 400px; background: radial-gradient(circle, rgba(245,158,11,0.15) 0%, transparent 70%); border-radius: 50%; z-index: -1;"></div>
    <div style="position: absolute; bottom: -50px; right: -50px; width: 300px; height: 300px; background: radial-gradient(circle, rgba(16,185,129,0.1) 0%, transparent 70%); border-radius: 50%; z-index: -1;"></div>

    <div class="container">
      <div style="text-align: center; max-width: 800px; margin: 0 auto; margin-bottom: 4rem;">
        <div class="animate-on-scroll">
          <div style="display: inline-flex; align-items: center; gap: 0.5rem; background: rgba(245, 158, 11, 0.1); color: #d97706; padding: 0.5rem 1.25rem; border-radius: 50px; font-weight: 600; font-size: 0.95rem; margin-bottom: 1.5rem; border: 1px solid rgba(245, 158, 11, 0.2);">
            <i class="fa-solid fa-file-signature"></i> 99% Admission Success Rate
          </div>
          <h2 style="font-size: clamp(2.5rem, 5vw, 4rem); line-height: 1.15; margin-bottom: 1.5rem; color: var(--dark);">
            Seamless & Fast <br>
            <span style="background: linear-gradient(135deg, #f59e0b, #ea580c); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">Application Process</span>
          </h2>
          <p style="color: var(--gray); font-size: 1.15rem; line-height: 1.7; margin-bottom: 2.5rem;">
            From crafting compelling SOPs to tracking deadlines, our dedicated admission officers ensure your application stands out to top global universities.
          </p>
        </div>
      </div>

      <!-- Application Process Timeline -->
      <div class="guide-timeline">
          <?php
          $steps = [
              [
                  'num' => '01',
                  'title' => 'SOP & LOR Assistance',
                  'icon' => 'fa-file-pen',
                  'desc' => 'We help you draft compelling Statements of Purpose and Letters of Recommendation.',
                  'color' => 'orange',
                  'image' => 'assets/images/uni_data_3d.png'
              ],
              [
                  'num' => '02',
                  'title' => 'Document Verification',
                  'icon' => 'fa-check-double',
                  'desc' => 'Our team meticulously verifies all your academic and financial documents for error-free submission.',
                  'color' => 'teal',
                  'image' => 'assets/images/service_guidance_3d.png'
              ],
              [
                  'num' => '03',
                  'title' => 'Fast-Track Submissions',
                  'icon' => 'fa-paper-plane',
                  'desc' => 'Leverage our direct university tie-ups to get your applications processed faster.',
                  'color' => 'blue',
                  'image' => 'assets/images/service_university_3d.png'
              ],
              [
                  'num' => '04',
                  'title' => 'Real-Time Tracking',
                  'icon' => 'fa-satellite-dish',
                  'desc' => 'Stay updated at every stage of your application with our dedicated tracking system.',
                  'color' => 'pink',
                  'image' => 'assets/images/uni_ranking_3d.png'
              ]
          ];

          foreach ($steps as $i => $step):
              $isEven = ($i % 2 !== 0);
          ?>
          <div class="guide-step-row <?= $isEven ? 'guide-step-row--reverse' : '' ?> animate-on-scroll">
              <div class="guide-step-content guide-step-content--<?= $step['color'] ?>">
                  <div class="guide-step-badge"><?= $step['num'] ?></div>
                  <h3><?= $step['title'] ?></h3>
                  <p><?= $step['desc'] ?></p>
                  <div class="guide-step-icon"><i class="fa-solid <?= $step['icon'] ?>"></i></div>
              </div>
              <div class="guide-step-visual">
                  <div class="guide-step-line"></div>
                  <div class="guide-step-dot"></div>
              </div>
              <div class="guide-step-image-col">
                  <img src="<?= $step['image'] ?>" alt="<?= $step['title'] ?>" class="guide-step-img">
              </div>
          </div>
          <?php endforeach; ?>
      </div>
    </div>
  </section>

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
      box-shadow: 0 15px 35px rgba(245,158,11,0.15);
      border-color: rgba(245,158,11,0.4);
      background: linear-gradient(rgba(255, 255, 255, 0.5), rgba(255, 255, 255, 0.8)), url('assets/images/premium_card_bg.png');
      background-size: cover;
      background-position: center;
  }
  .feature-pill:hover .feature-pill__text {
      color: var(--dark) !important;
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
  .feature-pill__text {
      font-size: 1.15rem;
      font-weight: 700;
      color: var(--dark);
      line-height: 1.4;
      transition: color 0.3s ease;
  }
  </style>

  <section class="section bg-light" style="padding: 6rem 0; background-color: #ffffff;">
    <div class="container">
      <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 4rem; align-items: center;">
        <div class="animate-on-scroll">
          <span style="display: inline-block; background: #ffffff; color: #aa0cc2ff; padding: 0.35rem 1.25rem; border-radius: 50px; font-size: 0.85rem; font-weight: 700; margin-bottom: 1.5rem;">The Bluestone Advantage</span>
          <h2 style="font-size: 2.5rem; margin-bottom: 1.5rem; line-height: 1.2;">Seamless <span style="color: var(--primary);">Application</span></h2>
          <p style="color:var(--gray); margin-bottom:2.5rem; line-height:1.7; font-size: 1.05rem;">
            We don't just fill forms; we tell your story. Our admission team works closely with you to craft a compelling Statement of Purpose (SOP) that highlights your strengths and aspirations.
          </p>
          <ul style="list-style: none; padding: 0; margin: 0; display: flex; flex-direction: column; gap: 1rem;">
            <li style="display: flex; align-items: center; gap: 1rem; font-size: 1.05rem; color: var(--dark); font-weight: 500;">
              <i class="fa-solid fa-check-circle" style="color: #f59e0b; font-size: 1.25rem;"></i> SOP & LOR Editing Support
            </li>
            <li style="display: flex; align-items: center; gap: 1rem; font-size: 1.05rem; color: var(--dark); font-weight: 500;">
              <i class="fa-solid fa-check-circle" style="color: #f59e0b; font-size: 1.25rem;"></i> Document Verification & Notarization
            </li>
            <li style="display: flex; align-items: center; gap: 1rem; font-size: 1.05rem; color: var(--dark); font-weight: 500;">
              <i class="fa-solid fa-check-circle" style="color: #f59e0b; font-size: 1.25rem;"></i> Direct Portal Submission
            </li>
            <li style="display: flex; align-items: center; gap: 1rem; font-size: 1.05rem; color: var(--dark); font-weight: 500;">
              <i class="fa-solid fa-check-circle" style="color: #f59e0b; font-size: 1.25rem;"></i> Regular Application Tracking
            </li>
          </ul>
        </div>
        <div class="animate-on-scroll delay-1">
          <div style="background: var(--accent); padding: 3rem; border-radius: 24px; box-shadow: 0 20px 40px rgba(0,0,0,0.06); border: 1px solid #f1f5f9; position: relative;">
            <div style="position: absolute; top: -20px; right: 30px; width: 60px; height: 60px; background: #f59e0b; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-size: 1.5rem; box-shadow: 0 10px 20px rgba(245,158,11,0.3);">
              <i class="fa-solid fa-award"></i>
            </div>
            <h3 style="margin-bottom: 1.5rem; font-size: 1.75rem;">Admission Advantage</h3>
            <p style="font-size: 1.05rem; color: white; line-height: 1.8;">
                Did you know that 30% of applications are rejected due to simple documentation errors? At Bluestone, we have a <strong>99% Admission Success Rate</strong> thanks to our rigorous multi-level quality check process.
            </p>
            <div style="margin-top: 2.5rem; padding: 1.5rem; background: #fff7ed; border-radius: 12px; display: flex; gap: 1.25rem; border-left: 4px solid #f59e0b;">
                <i class="fa-solid fa-clock-rotate-left" style="color: #f59e0b; font-size: 1.5rem; margin-top: 0.25rem;"></i>
                <p style="font-size: 1rem; color: #9a3412; font-weight: 600; margin: 0; line-height: 1.6;">Fast-Track your offer letter in as little as 48 hours for selected partner universities!</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <section class="section" style="padding-top: 2rem;">
    <div class="container animate-on-scroll">
      <div style="background: linear-gradient(135deg, #f59e0b, #d97706); padding: 4rem 2rem; border-radius: var(--radius-lg); text-align: center; color: white; box-shadow: 0 20px 40px rgba(245,158,11,0.3);">
        <h2 style="font-size: 2.5rem; margin-bottom: 1rem;">Ready to Apply?</h2>
        <p style="font-size: 1.1rem; opacity: 0.9; max-width: 600px; margin: 0 auto 2rem;">Don't wait for deadlines to approach. Start your application today with our expert team.</p>
        <a href="consultation.php" class="btn btn--white btn--lg pulse-btn" style="background: white; color: #d97706;">Begin My Application</a>
      </div>
    </div>
  </section>
</main>
<?php require_once 'includes/footer.php'; ?>
