<?php
require_once 'includes/config.php';
$pageTitle = 'Scholarships & Grants | Bluestone Overseas Consultants';
$pageDesc = 'Find scholarships and grants for studying abroad at top universities worldwide.';
$pageHeroImage = 'assets/images/Offer.png';
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
      <div class="filter-card filter-card-pad animate-on-scroll" style="background: white; padding: 2rem; border-radius: 20px; box-shadow: 0 10px 40px rgba(0,0,0,0.05); margin-top: -5rem; position: relative; z-index: 10; border: 1px solid rgba(0,0,0,0.02);">
        <form method="GET" action="scholarships.php" id="scholFilterForm">
          <div style="display: flex; align-items: flex-end; justify-content: space-between; flex-wrap: wrap; gap: 1.5rem;">
            <div style="flex: 1; min-width: 200px;">
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
                      
                      <div style="background: white; border: 1px solid #f1f5f9; padding: 1.25rem; border-radius: 12px; margin-bottom: 1.5rem;">
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
        <!-- PROCESS SECTION -->
        <!-- PROCESS SECTION -->
        <div class="process-section" style="margin-bottom: 5rem;">
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

  <?php if ($selected_country_id <= 0): ?>
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
              <h1 style="font-weight: 800; font-size: clamp(2.5rem, 4vw, 3.5rem); color: #0f172a; line-height: 1.2; margin-bottom: 1.5rem;">Fund Your <span style="color: #fd47ba;">Future</span></h1>
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
