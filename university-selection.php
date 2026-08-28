<?php
require_once 'includes/config.php';
$pageTitle = 'University Selection Guidance for Study Abroad';
$pageDesc = 'Get expert recommendations to select the best university based on your profile and goals.';
$pageHeroImage = 'assets/images/areowomen.png';
require_once 'includes/header.php';
?>
<main>
  <!-- Country Choose Section -->
  <section class="section" style="padding-top: 4rem; background: #ffffff">
    <div class="container">
      <!-- Destination Filter -->
      <div class="filter-card animate-on-scroll" style="margin: 0 0 4rem; background: linear-gradient(135deg, #0ea5e9, #3b82f6); padding: 2.5rem; border-radius: 24px; box-shadow: 0 15px 40px rgba(0,0,0,0.08); border: 1px solid #f1f5f9;">
        <form action="" method="GET" style="display: flex; flex-wrap: wrap; gap: 2rem; align-items: center; justify-content: space-between;">
          <div style="flex: 1; min-width: 300px;">
            <h3 style="margin: 0; font-size: 1.5rem; color: var(--dark);">Explore Partner Universities</h3>
            <p style="margin: 0.5rem 0 0; color: #ffffff;">Select a destination to discover where you can study.</p>
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
                  <?= clean_output($c['flag'] . ' ' . $c['name']) ?>
                </option>
              <?php endforeach; ?>
            </select>
            <button type="submit" class="btn btn--primary" style="padding: 1rem 2rem; border-radius: 12px;">Search</button>
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
              $imgUrl = !empty($u['image_url']) ? htmlspecialchars($u['image_url']) : 'https://images.unsplash.com/photo-1541339907198-e08756dedf3f?auto=format&fit=crop&q=80&w=800';
          ?>
            <div style="background: #fff; border-radius: 24px; border: 1px solid rgba(0,0,0,0.05); box-shadow: 0 10px 30px rgba(0,0,0,0.04); transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275); overflow: hidden; position: relative; display: flex; flex-direction: column;" class="uni-card" onmouseover="this.style.transform='translateY(-12px)'; this.style.boxShadow='0 25px 50px rgba(14,165,233,0.15)';" onmouseout="this.style.transform='none'; this.style.boxShadow='0 10px 30px rgba(0,0,0,0.04)';">
              <!-- Cover Image -->
              <div style="height: 180px; width: 100%; background: url('<?= $imgUrl ?>') center/cover; position: relative;">
                 <div style="position: absolute; inset: 0; background: linear-gradient(to top, rgba(0,0,0,0.7), transparent);"></div>
                 <div style="position: absolute; bottom: 1.5rem; left: 1.5rem; display: flex; gap: 0.5rem;">
                    <span style="background: rgba(255,255,255,0.2); backdrop-filter: blur(8px); -webkit-backdrop-filter: blur(8px); color: white; padding: 0.4rem 0.8rem; border-radius: 50px; font-size: 0.8rem; font-weight: 600; border: 1px solid rgba(255,255,255,0.3);"><i class="fa-solid fa-star" style="color: #fbbf24;"></i> Top Ranked</span>
                 </div>
              </div>

              <!-- Content -->
              <div style="padding: 2rem 1.5rem; flex: 1; display: flex; flex-direction: column;">
                <div style="display: flex; align-items: flex-start; gap: 1rem; margin-bottom: 1.5rem; position: relative; margin-top: -4rem;">
                  <div style="width: 70px; height: 70px; border-radius: 20px; background: white; padding: 0.5rem; box-shadow: 0 10px 25px rgba(0,0,0,0.1); border: 2px solid white; display: flex; align-items: center; justify-content: center; font-size: 1.8rem; color: #0ea5e9; flex-shrink: 0; position: relative; z-index: 2;">
                      <i class="fa-solid fa-building-columns"></i>
                  </div>
                </div>
                
                <h4 style="margin: 0 0 1.25rem; font-size: 1.3rem; line-height: 1.3; color: var(--dark); font-weight: 700;"><?= clean_output($u['name']) ?></h4>

                <div style="font-size: 0.95rem; color: var(--gray); margin-bottom: 2rem; display: flex; flex-direction: column; gap: 1rem; flex: 1;">
                  <div style="display: flex; align-items: center; gap: 0.8rem;">
                     <div style="width: 36px; height: 36px; border-radius: 10px; background: #e0f2fe; display: flex; align-items: center; justify-content: center; color: #0284c7; flex-shrink: 0;"><i class="fa-solid fa-ranking-star"></i></div>
                     <span style="line-height: 1.4;">Global Rank: <br><strong style="color: var(--dark); font-size: 1.05rem;">#<?= clean_output($u['qs_ranking'] ?: 'N/A') ?></strong></span>
                  </div>
                  <div style="display: flex; align-items: center; gap: 0.8rem;">
                     <div style="width: 36px; height: 36px; border-radius: 10px; background: #ede9fe; display: flex; align-items: center; justify-content: center; color: #7c3aed; flex-shrink: 0;"><i class="fa-solid fa-graduation-cap"></i></div>
                     <span style="line-height: 1.4;">Specialization: <br><strong style="color: var(--dark); font-size: 1.05rem;"><?= clean_output($u['specialization'] ?: 'General Studies') ?></strong></span>
                  </div>
                </div>

                <a href="javascript:void(0)" onclick="openEnquiryModal('<?= addslashes(htmlspecialchars($u['name'], ENT_QUOTES)) ?>')" class="btn btn--primary" style="width: 100%; justify-content: center; border-radius: 12px; padding: 0.85rem; font-weight: 600; box-shadow: 0 8px 20px rgba(14,165,233,0.25); text-transform: uppercase; font-size: 0.9rem; letter-spacing: 0.5px;">Apply Now <i class="fa-solid fa-arrow-right" style="margin-left: 0.5rem;"></i></a>
              </div>
            </div>
          <?php 
            endforeach;
          else:
          ?>
            <div style="grid-column: 1 / -1; text-align: center; padding: 4rem 2rem; background: #f8fafc; border-radius: 20px;">
              <p style="color: var(--gray); font-size: 1.1rem;">No universities listed for this country yet. Contact us for the full list of our 500+ partners.</p>
            </div>
          <?php endif; ?>
        </div>
      <?php else: ?>
        <div class="text-center animate-on-scroll" style="opacity: 0.6; padding: 4rem 0;">
            <i class="fa-solid fa-building-columns" style="font-size: 4rem; margin-bottom: 1.5rem; color: #cbd5e1;"></i>
            <p style="font-size: 1.2rem; color: var(--gray);">Select a country above to view the elite universities we partner with.</p>
        </div>
      <?php endif; ?>
    </div>
  </section>

  <section class="section bg-light" style="padding: 6rem 0;">
    <div class="container">
      <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 4rem; align-items: center;">
        <div class="animate-on-scroll">
          <span style="display: inline-block; background: #e0f2fe; color: #0284c7; padding: 0.35rem 1.25rem; border-radius: 50px; font-size: 0.85rem; font-weight: 700; margin-bottom: 1.5rem;">The Bluestone Advantage</span>
          <h2 style="font-size: 2.5rem; margin-bottom: 1.5rem; line-height: 1.2;">Expert <span>Shortlisting</span></h2>
          <p style="color:var(--gray); margin-bottom:2.5rem; line-height:1.7; font-size: 1.05rem;">
            Choosing where to study is a life-changing decision. We don't just give you a list; we provide a strategy. Based on your grades, career goals, and budget, we help you pick the best fit.
          </p>
          <ul style="list-style: none; padding: 0; margin: 0; display: flex; flex-direction: column; gap: 1rem;">
            <li style="display: flex; align-items: center; gap: 1rem; font-size: 1.05rem; color: var(--dark); font-weight: 500;">
              <i class="fa-solid fa-circle-check" style="color: #10b981; font-size: 1.25rem;"></i> Academic Profile Assessment
            </li>
            <li style="display: flex; align-items: center; gap: 1rem; font-size: 1.05rem; color: var(--dark); font-weight: 500;">
              <i class="fa-solid fa-circle-check" style="color: #10b981; font-size: 1.25rem;"></i> University Ranking Comparisons
            </li>
            <li style="display: flex; align-items: center; gap: 1rem; font-size: 1.05rem; color: var(--dark); font-weight: 500;">
              <i class="fa-solid fa-circle-check" style="color: #10b981; font-size: 1.25rem;"></i> Course Curriculum Analysis
            </li>
            <li style="display: flex; align-items: center; gap: 1rem; font-size: 1.05rem; color: var(--dark); font-weight: 500;">
              <i class="fa-solid fa-circle-check" style="color: #10b981; font-size: 1.25rem;"></i> Post-Study Work Opportunity Check
            </li>
          </ul>
        </div>
        <div class="animate-on-scroll delay-1" style="position: relative; padding: 1rem;">
            <!-- Main Image -->
            <img src="https://images.unsplash.com/photo-1523240795612-9a054b0db644?auto=format&fit=crop&q=80&w=800" alt="Students collaborating" style="width: 100%; height: 420px; object-fit: cover; border-radius: 24px; box-shadow: 0 15px 40px rgba(0,0,0,0.08);">
            
            <!-- Secondary Image overlapping -->
            <img src="https://images.unsplash.com/photo-1517486808906-6ca8b3f04846?auto=format&fit=crop&q=80&w=500" alt="University Campus" style="position: absolute; bottom: -30px; right: -10px; width: 200px; height: 200px; object-fit: cover; border-radius: 20px; border: 8px solid white; box-shadow: 0 15px 35px rgba(0,0,0,0.12); z-index: 2;">

            <!-- The Quote Box Overlapping -->
            <div style="position: absolute; top: 40px; left: -20px; background: white; padding: 1.5rem; border-radius: 16px; box-shadow: 0 20px 40px rgba(0,0,0,0.1); max-width: 260px; border-left: 4px solid #0ea5e9; z-index: 3;">
                <i class="fa-solid fa-quote-left" style="color: #0ea5e9; font-size: 1.25rem; margin-bottom: 0.5rem;"></i>
                <p style="font-style: italic; font-size: 0.9rem; color: var(--dark); margin: 0; line-height: 1.5;">"Bluestone helped me find a university that perfectly matched my budget and research interests."</p>
                <strong style="display: block; margin-top: 0.75rem; color: #0ea5e9; font-size: 0.85rem;">- Sneha R.</strong>
            </div>

            <!-- Stats Overlay -->
            <div style="position: absolute; bottom: 40px; left: 20px; background: rgba(255, 255, 255, 0.9); backdrop-filter: blur(10px); -webkit-backdrop-filter: blur(10px); padding: 1rem 1.5rem; border-radius: 16px; box-shadow: 0 10px 25px rgba(0,0,0,0.05); z-index: 2; display: flex; align-items: center; gap: 1rem; border: 1px solid rgba(255,255,255,0.5);">
               <div style="width: 45px; height: 45px; background: #e0f2fe; color: #0ea5e9; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.25rem;">
                   <i class="fa-solid fa-bullseye"></i>
               </div>
               <div>
                   <div style="font-weight: 800; font-size: 1.25rem; color: var(--dark); line-height: 1.1;">98%</div>
                   <div style="font-size: 0.8rem; color: var(--gray); font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px;">Success Rate</div>
               </div>
            </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Premium Intro Section -->
  <section class="section" style="position: relative; overflow: hidden; padding-top: 6rem; padding-bottom: 5rem; background-color: #ffffff;">
    <!-- Decorative background blobs -->
    <div style="position: absolute; top: -100px; left: -100px; width: 400px; height: 400px; background: radial-gradient(circle, rgba(14,165,233,0.1) 0%, transparent 70%); border-radius: 50%; z-index: -1;"></div>
    <div style="position: absolute; bottom: -50px; right: -50px; width: 300px; height: 300px; background: radial-gradient(circle, rgba(139,92,246,0.1) 0%, transparent 70%); border-radius: 50%; z-index: -1;"></div>

    <div class="container">
      <div style="text-align: center; max-width: 800px; margin: 0 auto; margin-bottom: 4rem;">
        <div class="animate-on-scroll">
          <div style="display: inline-flex; align-items: center; gap: 0.5rem; background: transparent; color: #0ea5e9; padding: 0.5rem 1.25rem; border-radius: 50px; font-weight: 600; font-size: 0.95rem; margin-bottom: 1.5rem; border: 1px solid rgba(14, 165, 233, 0.2);">
            <i class="fa-solid fa-building-columns"></i> Partnered with Top Institutions
          </div>
          <h2 style="font-size: clamp(2.5rem, 5vw, 4rem); line-height: 1.15; margin-bottom: 1.5rem; color: var(--dark);">
            Find Your Perfect <br>
            <span style="background: linear-gradient(135deg, #0ea5e9, #3b82f6); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">Academic Match</span>
          </h2>
          <p style="color: var(--gray); font-size: 1.15rem; line-height: 1.7; margin-bottom: 2.5rem;">
            We analyze your profile, budget, and career goals to shortlist universities where you have the highest probability of admission and success.
          </p>
        </div>
      </div>

      <!-- Feature Pills -->
      <div class="animate-on-scroll delay-1" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 1.5rem;">
          <div class="feature-pill feature-pill--center" style="background: linear-gradient(135deg, #0ea5e9, #3b82f6); border: none; color: white;">
            <img src="assets/images/uni_data_3d.png" alt="Data-Driven Shortlisting" style="width: 80px; height: 80px; object-fit: contain; margin: 0 auto 1rem; filter: drop-shadow(0 10px 20px rgba(0,0,0,0.1)); border-radius: 20px;">
            <div class="feature-pill__text" style="color: white;">Data-Driven Shortlisting</div>
          </div>
          
          <div class="feature-pill feature-pill--center" style="background: linear-gradient(135deg, #8b5cf6, #d946ef); border: none; color: white;">
            <img src="assets/images/uni_ranking_3d.png" alt="Global Rankings Focus" style="width: 80px; height: 80px; object-fit: contain; margin: 0 auto 1rem; filter: drop-shadow(0 10px 20px rgba(0,0,0,0.1)); border-radius: 20px;">
            <div class="feature-pill__text" style="color: white;">Global Rankings Focus</div>
          </div>
          
          <div class="feature-pill feature-pill--center" style="background: linear-gradient(135deg, #f97316, #f59e0b); border: none; color: white;">
            <img src="assets/images/uni_budget_3d.png" alt="Budget-Friendly Options" style="width: 80px; height: 80px; object-fit: contain; margin: 0 auto 1rem; filter: drop-shadow(0 10px 20px rgba(0,0,0,0.1)); border-radius: 20px;">
            <div class="feature-pill__text" style="color: white;">Budget-Friendly Options</div>
          </div>
          
          <div class="feature-pill feature-pill--center" style="background: linear-gradient(135deg, #14b8a6, #0d9488); border: none; color: white;">
            <img src="assets/images/uni_work_3d.png" alt="Post-Study Work Check" style="width: 80px; height: 80px; object-fit: contain; margin: 0 auto 1rem; filter: drop-shadow(0 10px 20px rgba(0,0,0,0.1)); border-radius: 20px;">
            <div class="feature-pill__text" style="color: white;">Post-Study Work Check</div>
          </div>
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
      box-shadow: 0 15px 35px rgba(14,165,233,0.15);
      border-color: rgba(14,165,233,0.4);
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
  .feature-pill__icon--blue { background: linear-gradient(135deg, #0ea5e9, #3b82f6); box-shadow: 0 8px 20px rgba(14,165,233,0.3); }
  .feature-pill__icon--purple { background: linear-gradient(135deg, #8b5cf6, #d946ef); box-shadow: 0 8px 20px rgba(139,92,246,0.3); }
  .feature-pill__icon--orange { background: linear-gradient(135deg, #f97316, #f59e0b); box-shadow: 0 8px 20px rgba(249,115,22,0.3); }
  .feature-pill__icon--teal { background: linear-gradient(135deg, #14b8a6, #0d9488); box-shadow: 0 8px 20px rgba(20,184,166,0.3); }
  .feature-pill__text { font-size: 1.15rem; font-weight: 700; color: var(--dark); line-height: 1.4; }
  </style>

  <section class="section" style="padding-top: 2rem;">
    <div class="container animate-on-scroll">
      <div style="background: var(--gradient); padding: 4rem 2rem; border-radius: var(--radius-lg); text-align: center; color: white; box-shadow: var(--shadow-lg);">
        <h2 style="font-size: 2.5rem; margin-bottom: 1rem;">Ready to Find Your Match?</h2>
        <p style="font-size: 1.1rem; opacity: 0.9; max-width: 600px; margin: 0 auto 2rem;">Our experts have helped over 10,000 students find their perfect academic home.</p>
        <a href="consultation.php" class="btn btn--white btn--lg pulse-btn" style="background: white; color: var(--primary);">Start Selection Process</a>
      </div>
    </div>
  </section>

<!-- ENQUIRY POPUP MODAL -->
<div id="enquiryEntryModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; z-index: 9999; background: rgba(15, 23, 42, 0.85); backdrop-filter: blur(5px); align-items: center; justify-content: center; opacity: 0; transition: opacity 0.4s ease;">
  <div style="background: white; border-radius: 20px; width: 90%; max-width: 500px; position: relative; box-shadow: 0 25px 50px -12px rgba(0,0,0,0.25); transform: translateY(20px); transition: transform 0.4s ease;" id="enquiryModalContent">
    
    <!-- Close Button -->
    <button onclick="closeEnquiryModal()" style="position: absolute; top: 1rem; right: 1rem; background: rgba(0,0,0,0.05); border: none; width: 32px; height: 32px; border-radius: 50%; display: flex; align-items: center; justify-content: center; cursor: pointer; color: #64748b; font-size: 1.2rem; transition: background 0.2s;">
      <i class="fa-solid fa-xmark"></i>
    </button>
    
    <!-- Modal Header -->
    <div style="padding: 2rem 2rem 1.5rem; text-align: center; border-bottom: 1px solid #e2e8f0;">
      <h3 style="font-size: 1.5rem; font-weight: 800; color: #0f172a; margin: 0 0 0.5rem 0;">University Application</h3>
      <p style="color: #64748b; font-size: 0.95rem; margin: 0;">Apply to <strong id="modalUniName" style="color: #3b82f6;"></strong>. Leave your details below.</p>
    </div>
    
    <!-- Modal Body (Form) -->
    <div style="padding: 1.5rem 2rem 2rem;">
      <form id="enquiryPopupForm" onsubmit="return handleFormSubmit(event)" style="display: flex; flex-direction: column; gap: 1rem;">
        <input type="hidden" name="form_type" value="enquiry">
        <input type="hidden" name="query" id="modalQueryField" value="Application Inquiry">
        
        <div>
          <label style="display: block; font-size: 0.85rem; font-weight: 600; color: #0f172a; margin-bottom: 0.25rem;">Full Name *</label>
          <input type="text" name="first_name" required style="width: 100%; padding: 0.75rem 1rem; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 1rem; outline: none; transition: border-color 0.2s;" onfocus="this.style.borderColor='#3b82f6';" onblur="this.style.borderColor='#cbd5e1';">
        </div>
        
        <div>
          <label style="display: block; font-size: 0.85rem; font-weight: 600; color: #0f172a; margin-bottom: 0.25rem;">Phone Number *</label>
          <input type="tel" name="phone" required style="width: 100%; padding: 0.75rem 1rem; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 1rem; outline: none; transition: border-color 0.2s;" onfocus="this.style.borderColor='#3b82f6';" onblur="this.style.borderColor='#cbd5e1';">
        </div>

        <div>
          <label style="display: block; font-size: 0.85rem; font-weight: 600; color: #0f172a; margin-bottom: 0.25rem;">Email Address *</label>
          <input type="email" name="email" required style="width: 100%; padding: 0.75rem 1rem; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 1rem; outline: none; transition: border-color 0.2s;" onfocus="this.style.borderColor='#3b82f6';" onblur="this.style.borderColor='#cbd5e1';">
        </div>
        
        <button type="submit" class="btn btn--primary" style="width: 100%; padding: 1rem; font-size: 1.1rem; margin-top: 0.5rem; justify-content: center; font-weight: 700;">
          Submit Application
        </button>
      </form>
    </div>
  </div>
</div>

<script>
  function openEnquiryModal(uniName) {
    const modal = document.getElementById('enquiryEntryModal');
    const content = document.getElementById('enquiryModalContent');
    const uniNameEl = document.getElementById('modalUniName');
    const queryField = document.getElementById('modalQueryField');
    
    if (uniNameEl && uniName) uniNameEl.textContent = uniName;
    if (queryField && uniName) queryField.value = 'Application Inquiry for ' + uniName;
    
    modal.style.display = 'flex';
    void modal.offsetWidth;
    modal.style.opacity = '1';
    content.style.transform = 'translateY(0)';
  }

  function closeEnquiryModal() {
    const modal = document.getElementById('enquiryEntryModal');
    const content = document.getElementById('enquiryModalContent');
    
    modal.style.opacity = '0';
    content.style.transform = 'translateY(20px)';
    
    setTimeout(() => {
      modal.style.display = 'none';
    }, 400);
  }

  // Close modal when clicking outside of content
  document.getElementById('enquiryEntryModal').addEventListener('click', function(e) {
    if (e.target === this) {
      closeEnquiryModal();
    }
  });
</script>

</main>
<?php require_once 'includes/footer.php'; ?>
