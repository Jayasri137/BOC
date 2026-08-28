<?php
require_once 'includes/config.php';
$pageTitle = 'Top Universities Abroad for Indian Students | Bluestone Overseas';
$pageDesc = 'Find leading universities across the USA, UK, Canada, Australia, Europe, and more.';
$pageHeroImage = 'assets/images/areowomen.png';
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
        </div>
      </section>

      <section class="section" style="background-color: #fdf7fe; padding: 6rem 0;">
        <div class="container">
          <!-- Centered Title and Description -->
          <div class="text-center animate-on-scroll" style="margin-bottom: 4rem;">
            <h1 class="section__title" style="font-weight: 800; font-size: clamp(2.5rem, 4vw, 3.5rem); color: #1e293b; line-height: 1.2; margin-bottom: 1.5rem;">Partnering with <span style="color: #38bdf8;">Global Leaders</span></h1>
            <p style="color: #475569; font-size: 1.1rem; line-height: 1.7; max-width: 800px; margin: 0 auto;">We have direct partnerships with over 700+ top-ranked universities across 20+ countries, offering you priority processing and exclusive scholarships.</p>
          </div>

          <div class="grid grid--2 gap--4 align-center">
            <div class="col-lg-5 mb-4 mb-lg-0 animate-on-scroll delay-1">
              <!-- Main contextual image -->
              <div style="position: relative; max-width: 100%; margin-bottom: 2rem;">
                <img src="assets/images/areowomen.png" alt="Global Leaders" style="width: 100%; border-radius: 24px; box-shadow: 0 20px 40px rgba(0,0,0,0.08);">
                <!-- Floating badge -->
                <div style="position: absolute; bottom: -20px; right: -20px; background: white; padding: 1rem 1.5rem; border-radius: 16px; box-shadow: 0 10px 25px rgba(0,0,0,0.1); display: flex; align-items: center; gap: 1rem; z-index: 5;">
                    <div style="width: 40px; height: 40px; background: #fef3c7; color: #f59e0b; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 1.2rem;">
                        <i class="fa-solid fa-trophy"></i>
                    </div>
                    <div>
                        <div style="font-weight: 800; font-size: 1.2rem; color: #0f172a; line-height: 1.1;">700+</div>
                        <div style="font-size: 0.85rem; color: #64748b; font-weight: 600;">Partner Unis</div>
                    </div>
                </div>
              </div>
            </div>
            <div class="col-lg-7 animate-on-scroll delay-2">
              <div style="display: flex; align-items: center; gap: 1.5rem; margin-bottom: 2.5rem;">
                <div style="display: flex; align-items: center;">
                  <img src="assets/images/3d_usa.png" alt="USA" style="width: 55px; height: 55px; border-radius: 50%; object-fit: cover; border: 4px solid #fdf7fe; margin-right: -20px; box-shadow: 0 4px 10px rgba(0,0,0,0.1); position: relative; z-index: 4;">
                  <img src="assets/images/3d_canada.png" alt="Canada" style="width: 55px; height: 55px; border-radius: 50%; object-fit: cover; border: 4px solid #fdf7fe; margin-right: -20px; box-shadow: 0 4px 10px rgba(0,0,0,0.1); position: relative; z-index: 3;">
                  <img src="assets/images/3d_germany.png" alt="Germany" style="width: 55px; height: 55px; border-radius: 50%; object-fit: cover; border: 4px solid #fdf7fe; margin-right: -20px; box-shadow: 0 4px 10px rgba(0,0,0,0.1); position: relative; z-index: 2;">
                  <img src="assets/images/3d_ireland.png" alt="Ireland" style="width: 55px; height: 55px; border-radius: 50%; object-fit: cover; border: 4px solid #fdf7fe; margin-right: -20px; box-shadow: 0 4px 10px rgba(0,0,0,0.1); position: relative; z-index: 1;">
                  <div style="width: 55px; height: 55px; border-radius: 50%; background: #38bdf8; color: white; display: flex; align-items: center; justify-content: center; font-weight: 800; border: 4px solid #fdf7fe; box-shadow: 0 4px 10px rgba(0,0,0,0.1); font-size: 1rem; position: relative; z-index: 0;">20+</div>
                </div>
                <div style="color: #334155; font-weight: 700; font-size: 1.05rem; line-height: 1.3;">
                  Top Destinations<br><span style="color: #64748b; font-weight: 500; font-size: 0.95rem;">Worldwide</span>
                </div>
              </div>

              <div style="display: flex; flex-direction: column; gap: 1rem;">
                <div style="background: white; border-radius: 12px; padding: 1.25rem 1.5rem; display: flex; align-items: center; gap: 1rem; box-shadow: 0 4px 15px rgba(0,0,0,0.02);">
                   <div style="width: 28px; height: 28px; background: #fee2e2; color: #ef4444; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 0.8rem; flex-shrink: 0;"><i class="fa-solid fa-check"></i></div>
                   <span style="color: #334155; font-weight: 600; font-size: 1.05rem;">Russell Group & Ivy League Pathways</span>
                </div>
                <div style="background: white; border-radius: 12px; padding: 1.25rem 1.5rem; display: flex; align-items: center; gap: 1rem; box-shadow: 0 4px 15px rgba(0,0,0,0.02);">
                   <div style="width: 28px; height: 28px; background: #fee2e2; color: #ef4444; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 0.8rem; flex-shrink: 0;"><i class="fa-solid fa-check"></i></div>
                   <span style="color: #334155; font-weight: 600; font-size: 1.05rem;">QS World Ranked Institutions</span>
                </div>
                <div style="background: white; border-radius: 12px; padding: 1.25rem 1.5rem; display: flex; align-items: center; gap: 1rem; box-shadow: 0 4px 15px rgba(0,0,0,0.02);">
                   <div style="width: 28px; height: 28px; background: #fee2e2; color: #ef4444; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 0.8rem; flex-shrink: 0;"><i class="fa-solid fa-check"></i></div>
                   <span style="color: #334155; font-weight: 600; font-size: 1.05rem;">Exclusive Partnership Benefits</span>
                </div>
                <div style="background: white; border-radius: 12px; padding: 1.25rem 1.5rem; display: flex; align-items: center; gap: 1rem; box-shadow: 0 4px 15px rgba(0,0,0,0.02);">
                   <div style="width: 28px; height: 28px; background: #fee2e2; color: #ef4444; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 0.8rem; flex-shrink: 0;"><i class="fa-solid fa-check"></i></div>
                   <span style="color: #334155; font-weight: 600; font-size: 1.05rem;">Campus Life & Alumni Network Insights</span>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>

      <section class="section" style="padding-top: 5rem;">
        <div class="container">
          <div class="text-center animate-on-scroll">
            <span class="section__tag">Process</span>
            <h2 class="section__title">How It <span>Works</span></h2>
            <p class="section__subtitle" style="max-width: 600px; margin: 0 auto;">A streamlined, step-by-step approach to ensure your success.</p>
          </div>
          <style>
            .process-hover-card {
                position: relative;
                padding: 4rem 2rem 3rem 2rem;
                color: white;
                display: flex;
                flex-direction: column;
                align-items: flex-start;
                justify-content: flex-end;
                min-height: 420px;
                text-align: left;
                overflow: hidden;
                cursor: pointer;
            }
            .process-hover-card .bg-img {
                position: absolute;
                inset: 0;
                background-size: cover;
                background-position: center;
                z-index: 0;
                transition: transform 0.6s cubic-bezier(0.16, 1, 0.3, 1);
            }
            .process-hover-card:hover .bg-img {
                transform: scale(1.1);
            }
            .process-hover-card .overlay-base {
                position: absolute;
                inset: 0;
                background: linear-gradient(to top, rgba(15,23,42,0.95) 0%, transparent 60%);
                z-index: 1;
                pointer-events: none;
            }
            .process-hover-card .overlay-hover {
                position: absolute;
                inset: 0;
                z-index: 2;
                opacity: 0;
                transition: opacity 0.4s ease;
                pointer-events: none;
            }
            .process-hover-card:hover .overlay-hover {
                opacity: 1;
            }
            .process-hover-card .content-wrap {
                position: relative;
                z-index: 3;
                display: flex;
                flex-direction: column;
                width: 100%;
                pointer-events: none;
                transform: translateY(40px);
                transition: transform 0.4s cubic-bezier(0.16, 1, 0.3, 1);
            }
            .process-hover-card:hover .content-wrap {
                transform: translateY(0);
            }
            .process-hover-card .process-text {
                opacity: 0;
                transition: opacity 0.4s ease;
                margin: 0;
                font-weight: 400;
                font-size: 0.95rem;
                line-height: 1.6;
                color: rgba(255,255,255,0.85);
                margin-top: 1rem;
            }
            .process-hover-card:hover .process-text {
                opacity: 1;
            }
          </style>
          
          <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); margin-top: 3rem; width: 100%; box-shadow: 0 10px 30px rgba(0,0,0,0.1); border-radius: 12px; overflow: hidden;">
            <!-- Card 1 -->
            <div class="process-hover-card animate-on-scroll">
               <div class="bg-img" style="background-image: url('assets/images/hero-bg-1.jpg');"></div>
               <div class="overlay-base"></div>
               <div class="overlay-hover" style="background: linear-gradient(to top, rgba(15,23,42,0.95) 0%, rgba(30,58,138,0.8) 50%, rgba(30,58,138,0.7) 100%);"></div>
               <div class="content-wrap">
                  <div style="font-size: 2.5rem; color: white; opacity: 0.9; margin-bottom: 0.5rem;"><i class="fa-solid fa-address-card"></i></div>
                  <h3 style="color: white; font-size: 1.4rem; font-weight: 700; margin-bottom: 0; line-height: 1.3;">Profile Match</h3>
                  <p class="process-text">We match your academic profile with university admission criteria to ensure the best fit.</p>
               </div>
            </div>
            
            <!-- Card 2 -->
            <div class="process-hover-card animate-on-scroll delay-1">
               <div class="bg-img" style="background-image: url('assets/images/hero-bg-2.jpg');"></div>
               <div class="overlay-base"></div>
               <div class="overlay-hover" style="background: linear-gradient(to top, rgba(15,23,42,0.95) 0%, rgba(12,74,110,0.8) 50%, rgba(12,74,110,0.7) 100%);"></div>
               <div class="content-wrap">
                  <div style="font-size: 2.5rem; color: white; opacity: 0.9; margin-bottom: 0.5rem;"><i class="fa-solid fa-vr-cardboard"></i></div>
                  <h3 style="color: white; font-size: 1.4rem; font-weight: 700; margin-bottom: 0; line-height: 1.3;">Virtual Tours</h3>
                  <p class="process-text">Explore campuses, facilities, and city life through our exclusive virtual resources.</p>
               </div>
            </div>

            <!-- Card 3 -->
            <div class="process-hover-card animate-on-scroll delay-2">
               <div class="bg-img" style="background-image: url('assets/images/s3.jpg');"></div>
               <div class="overlay-base"></div>
               <div class="overlay-hover" style="background: linear-gradient(to top, rgba(15,23,42,0.95) 0%, rgba(2,132,199,0.8) 50%, rgba(2,132,199,0.7) 100%);"></div>
               <div class="content-wrap">
                  <div style="font-size: 2.5rem; color: white; opacity: 0.9; margin-bottom: 0.5rem;"><i class="fa-solid fa-users-viewfinder"></i></div>
                  <h3 style="color: white; font-size: 1.4rem; font-weight: 700; margin-bottom: 0; line-height: 1.3;">Networking</h3>
                  <p class="process-text">Attend our education fairs to meet university representatives directly and build connections.</p>
               </div>
            </div>

            <!-- Card 4 -->
            <div class="process-hover-card animate-on-scroll delay-3">
               <div class="bg-img" style="background-image: url('assets/images/hero-bg-3.jpg');"></div>
               <div class="overlay-base"></div>
               <div class="overlay-hover" style="background: linear-gradient(to top, rgba(15,23,42,0.95) 0%, rgba(15,118,110,0.8) 50%, rgba(15,118,110,0.7) 100%);"></div>
               <div class="content-wrap">
                  <div style="font-size: 2.5rem; color: white; opacity: 0.9; margin-bottom: 0.5rem;"><i class="fa-solid fa-user-graduate"></i></div>
                  <h3 style="color: white; font-size: 1.4rem; font-weight: 700; margin-bottom: 0; line-height: 1.3;">Enrollment</h3>
                  <p class="process-text">Receive your offer letter and secure your place at the university with our expert guidance.</p>
               </div>
            </div>
          </div>
        </div>
      <?php endif; ?>
    </div>
  </section>

  <section class="section" style="background-color: #f8fafc;">
    <div class="container">
      <div class="text-center animate-on-scroll">
        <span class="section__tag">Benefits</span>
        <h2 class="section__title">Why Choose <span>Bluestone</span></h2>
        <p class="section__subtitle" style="max-width: 600px; margin: 0 auto;">Experience the advantage of working with industry-leading experts.</p>
      </div>

      <style>
      .benefit-card-modern {
          background: #ffffff;
          border-radius: 24px;
          padding: 3rem 2.5rem;
          position: relative;
          overflow: hidden;
          box-shadow: 0 10px 30px rgba(0,0,0,0.03);
          transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
          border: 1px solid rgba(0,0,0,0.03);
          z-index: 1;
      }
      .benefit-card-modern:hover {
          transform: translateY(-10px);
          box-shadow: 0 20px 40px rgba(0,0,0,0.08);
      }
      .benefit-icon-wrapper {
          width: 80px;
          height: 80px;
          border-radius: 24px;
          display: flex;
          align-items: center;
          justify-content: center;
          font-size: 2rem;
          margin-bottom: 2rem;
          color: white;
          position: relative;
          z-index: 2;
          transition: transform 0.4s ease;
      }
      .benefit-card-modern:hover .benefit-icon-wrapper {
          transform: scale(1.1) rotate(5deg);
      }
      .bg-shape {
          position: absolute;
          top: -50px;
          right: -50px;
          width: 200px;
          height: 200px;
          border-radius: 50%;
          opacity: 0.05;
          z-index: 0;
          transition: transform 0.8s ease;
      }
      .benefit-card-modern:hover .bg-shape {
          transform: scale(1.8);
      }
      /* Colors */
      .b-color-1 .benefit-icon-wrapper { background: linear-gradient(135deg, #38bdf8, #0284c7); box-shadow: 0 15px 30px rgba(56, 189, 248, 0.3); }
      .b-color-1 .bg-shape { background: #38bdf8; }

      .b-color-2 .benefit-icon-wrapper { background: linear-gradient(135deg, #f472b6, #db2777); box-shadow: 0 15px 30px rgba(244, 114, 182, 0.3); }
      .b-color-2 .bg-shape { background: #f472b6; }

      .b-color-3 .benefit-icon-wrapper { background: linear-gradient(135deg, #fbbf24, #d97706); box-shadow: 0 15px 30px rgba(251, 191, 36, 0.3); }
      .b-color-3 .bg-shape { background: #fbbf24; }
      </style>

      <div class="grid grid--3 gap--4" style="margin-top: 4rem;">
        <div class="benefit-card-modern b-color-1 animate-on-scroll">
          <div class="bg-shape"></div>
          <div class="benefit-icon-wrapper"><i class="fa-solid fa-handshake"></i></div>
          <h3 style="font-size: 1.5rem; font-weight: 800; margin-bottom: 1rem; color: #0f172a;">Direct Tie-Ups</h3>
          <p style="color: #475569; line-height: 1.7; font-size: 1.1rem; font-weight: 400;">Benefit from our direct partnerships, which often means waived application fees and priority processing times.</p>
        </div>
        <div class="benefit-card-modern b-color-2 animate-on-scroll delay-1">
          <div class="bg-shape"></div>
          <div class="benefit-icon-wrapper"><i class="fa-solid fa-ranking-star"></i></div>
          <h3 style="font-size: 1.5rem; font-weight: 800; margin-bottom: 1rem; color: #0f172a;">Top Ranked</h3>
          <p style="color: #475569; line-height: 1.7; font-size: 1.1rem; font-weight: 400;">We work exclusively with universities that are recognized globally for their academic excellence and research.</p>
        </div>
        <div class="benefit-card-modern b-color-3 animate-on-scroll delay-2">
          <div class="bg-shape"></div>
          <div class="benefit-icon-wrapper"><i class="fa-solid fa-comments"></i></div>
          <h3 style="font-size: 1.5rem; font-weight: 800; margin-bottom: 1rem; color: #0f172a;">Alumni Connections</h3>
          <p style="color: #475569; line-height: 1.7; font-size: 1.1rem; font-weight: 400;">Get connected with our past students currently studying at your target university to learn firsthand insights.</p>
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
