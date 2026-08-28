<?php
require_once 'includes/config.php';
$pageTitle = 'Study Abroad Consultants in Coimbatore | Bluestone Overseas';
$pageDesc = 'Trusted UK Education Consultants in Coimbatore offering expert university selection, admissions, scholarships, visa guidance and complete study abroad support.';
$pageKeywords = 'UK Education Consultants in Coimbatore, Australia Education Consultants in Coimbatore, New Zealand Education Consultants in Coimbatore, UG Programs Abroad, PG Programs Abroad, Study Abroad Consultants in Coimbatore, IELTS Coaching in Coimbatore, IELTS classes in Coimbatore, Best IELTS Coaching in Coimbatore, IELTS Training in Coimbatore, German language course, Japanese language course, German language classes, Japanese language classes, German Language Course in Coimbatore, Japanese Language Course in Coimbatore, German Language Training Centre in Coimbatore, Japanese Language Training Centre in Coimbatore, Postgraduate study in UK, Postgraduate study in Australia, Postgraduate study in New Zealand, Undergraduate study in Australia, Undergraduate study in UK, Undergraduate study in New Zealand, Postgraduate Study in UK – Coimbatore, Postgraduate Study in Australia – Coimbatore, Undergraduate Study in UK – Coimbatore, Undergraduate Study in Australia – Coimbatore, Postgraduate Study in New Zealand – Coimbatore, Undergraduate Study in New Zealand – Coimbatore';
$heroSlides = [];
try {
    $stmt = $pdo->prepare("SELECT * FROM hero_slides WHERE is_active = 1 ORDER BY id ASC");
    $stmt->execute();
    $heroSlides = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    // Silently ignore if table doesn't exist or error occurs
}

require_once 'includes/header.php';
?>
<main>
<section class="hero-sky" id="home">
  <!-- Sky background image -->
  <div class="hero-sky__bg"></div>

  <!-- Subtle world map overlay (decorative) -->
  <div class="hero-sky__map-overlay" aria-hidden="true">
    <svg viewBox="0 0 1200 400" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="xMidYMid meet">
      <g fill="none" stroke="currentColor" stroke-width="1" stroke-linejoin="round">
        <path d="M60 200 C140 140 260 120 360 140 C460 160 580 140 700 120 C820 100 940 120 1080 160" opacity="0.9" />
      </g>
      <g fill="currentColor" opacity="0.85">
        <!-- simple continent shapes as stylized blobs -->
        <ellipse cx="180" cy="190" rx="70" ry="30" />
        <ellipse cx="420" cy="160" rx="60" ry="28" />
        <ellipse cx="700" cy="180" rx="90" ry="36" />
        <ellipse cx="980" cy="170" rx="50" ry="22" />
      </g>
    </svg>
  </div>


  <div class="hero-sky__arc-container">
    <svg class="hero-sky__arc-svg" viewBox="0 0 1000 300" fill="none" xmlns="http://www.w3.org/2000/svg">
      <defs>
        <mask id="path-mask">
          <path id="mask-path" d="M -200 250 C 400 250, 300 50, 1200 50" stroke="white" stroke-width="20" fill="none" stroke-linecap="round" />
        </mask>
      </defs>
      <!-- Dashed trail -->
      <path id="sky-arc-path" d="M -200 250 C 400 250, 300 50, 1200 50" />
    </svg>

    <!-- Original plane image -->
    <div class="hero-sky__plane-on-arc" aria-hidden="true">
      <img src="assets/images/plane.png" alt="Airplane">
    </div>
  </div>

  <div class="container hero-sky__inner" style="flex-shrink: 0; min-height: max-content; padding-bottom: 2rem;">
    <!-- Center Content Slider -->
    <div class="hero-slider-container" style="flex-shrink: 0;">
      <!-- Left Arrow -->
      <button class="hero-slider-arrow hero-slider-prev" aria-label="Previous Slide"><i class="fa-solid fa-chevron-left"></i></button>

      <div class="hero-slider-track">
        <!-- Slide 1: Original Fixed Content -->
        <div class="hero-slide active">
          <div class="hero-sky__content text-center">
            <span class="hero-sky__badge">
              <i class="fa-solid fa-graduation-cap" style="color: #0d315c; margin-right: 0.4rem;"></i>
              Top Overseas Study Partner
            </span>
            <h1 class="hero-sky__title">
              Best <span class="serif-italic">Overseas</span> Education<br>Consultants in India.
            </h1>
            <div class="hero-sky__actions">
              <a href="consultation.php" class="btn-sky-pill btn-sky-pill--solid">
                <i class="fa-solid fa-phone" style="margin-right: 0.5rem;"></i> Get Free Counselling
</a>
            </div>
          </div>
        </div>

        <!-- Dynamic Admin Slides -->
        <?php foreach($heroSlides as $slide): ?>
          <div class="hero-slide">
            <div class="hero-slide-split" style="display: flex; align-items: center; justify-content: <?= !empty($slide['image_path']) ? 'space-between' : 'center' ?>; gap: 2rem; max-width: 1000px; margin: 0 auto; text-align: <?= !empty($slide['image_path']) ? 'left' : 'center' ?>;">
              
              <!-- Left Text Content -->
              <div class="hero-slide-text" style="flex: 1; max-width: 600px;">
                <?php if (!empty($slide['badge'])): ?>
                  <span class="hero-sky__badge" style="margin-bottom: 1rem;">
                    <i class="fa-solid fa-star" style="color: #0d315c; margin-right: 0.4rem;"></i>
                    <?= htmlspecialchars($slide['badge']) ?>
                  </span>
                <?php endif; ?>
                
                <h1 class="hero-sky__title" style="margin-bottom: 1rem; font-size: clamp(1.2rem, 2.5vw, 2.2rem);">
                  <?= str_replace(['&lt;span&gt;', '&lt;/span&gt;'], ['<span class="serif-italic">', '</span>'], htmlspecialchars($slide['title'])) ?>
                </h1>
                
                <?php if (!empty($slide['description'])): ?>
                  <p class="hero-sky__desc" style="color: rgba(13, 49, 92, 0.85); font-size: 1.1rem; margin-bottom: 1.5rem; font-weight: 500;">
                    <?= htmlspecialchars($slide['description']) ?>
                  </p>
                <?php endif; ?>

                <div class="hero-sky__actions" style="justify-content: flex-start;">
                  <a href="consultation.php" class="btn-sky-pill btn-sky-pill--solid">
                    <?= htmlspecialchars($slide['button_text']) ?: 'Get Started' ?> <i class="fa-solid fa-arrow-right" style="margin-left: 0.5rem;"></i>
                  </a>
                </div>
              </div>

              <!-- Right Image Content -->
              <?php if (!empty($slide['image_path'])): ?>
                <div class="hero-slide-image" style="flex: 0 0 45%; max-width: 450px; text-align: right;">
                  <img src="<?= htmlspecialchars($slide['image_path']) ?>" alt="Slide Image" style="max-height: 280px; width: 100%; object-fit: contain; filter: drop-shadow(0 15px 25px rgba(0,0,0,0.25)); border-radius: 10px;">
                </div>
              <?php endif; ?>
              
            </div>
          </div>
        <?php endforeach; ?>
      </div>

      <!-- Right Arrow -->
      <button class="hero-slider-arrow hero-slider-next" aria-label="Next Slide"><i class="fa-solid fa-chevron-right"></i></button>
    </div>

    <!-- Destination 3D Pop-out Cards — Arc Fan (Rotates automatically like a clock) -->
    <div class="hero-sky__cards-row" id="skyCardsCarousel" style="flex-shrink: 0;">
      <!-- 1. Australia -->
      <div class="sky-card sky-card--sydney active" data-index="0" onclick="window.location.href='study-in-australia.php'" style="cursor: pointer;">
        <div class="sky-card__body">
          <div class="sky-card__header">AUSTRALIA</div>
          <div class="sky-card__subtitle">Top Universities</div>
          <div class="sky-card__image-wrapper">
            <img src="assets/images/aus.png" alt="Sydney Opera House 3D Pop-out">
          </div>
        </div>
      </div>

      <!-- 2. China -->
      <div class="sky-card sky-card--china" data-index="1" onclick="window.location.href='study-in-china.php'" style="cursor: pointer;">
        <div class="sky-card__body">
          <div class="sky-card__header">CHINA</div>
          <div class="sky-card__subtitle">Global Hub</div>
          <div class="sky-card__image-wrapper">
            <img src="assets/images/rus.png" alt="China Pagoda 3D Pop-out">
          </div>
        </div>
      </div>

      <!-- 3. Dubai -->
      <div class="sky-card sky-card--paris" data-index="2" onclick="window.location.href='study-in-uae.php'" style="cursor: pointer;">
        <div class="sky-card__body">
          <div class="sky-card__header">DUBAI</div>
          <div class="sky-card__subtitle">Rapid Growth</div>
          <div class="sky-card__image-wrapper">
            <img src="assets/images/3d_eiffel_tower.png" alt="Luxembourg Tower 3D Pop-out">
          </div>
        </div>
      </div>

      <!-- 4. Singapore -->
      <div class="sky-card sky-card--singapore" data-index="3" onclick="window.location.href='study-in-singapore.php'" style="cursor: pointer;">
        <div class="sky-card__body">
          <div class="sky-card__header">SINGAPORE</div>
          <div class="sky-card__subtitle">Asian Gateway</div>
          <div class="sky-card__image-wrapper">
            <img src="assets/images/3d_st_basil.png" alt="Singapore 3D Pop-out">
          </div>
        </div>
      </div>

      <!-- 5. Russia -->
      <div class="sky-card sky-card--russia" data-index="4" onclick="window.location.href='study-in-russia.php'" style="cursor: pointer;">
        <div class="sky-card__body">
          <div class="sky-card__header">RUSSIA</div>
          <div class="sky-card__subtitle">Medical Hub</div>
          <div class="sky-card__image-wrapper">
            <img src="assets/images/3d_st_basil.png" alt="Russia Kremlin 3D Pop-out">
          </div>
        </div>
      </div>

      <!-- 6. Spain -->
      <div class="sky-card sky-card--spain" data-index="5" onclick="window.location.href='study-in-spain.php'" style="cursor: pointer;">
        <div class="sky-card__body">
          <div class="sky-card__header">SPAIN</div>
          <div class="sky-card__subtitle">Cultural Vibe</div>
          <div class="sky-card__image-wrapper">
            <img src="assets/images/3d_eiffel_tower.png" alt="Spain Palace 3D Pop-out">
          </div>
        </div>
      </div>

      <!-- 7. UK -->
      <div class="sky-card sky-card--china" data-index="6" onclick="window.location.href='study-in-uk.php'" style="cursor: pointer;">
        <div class="sky-card__body">
          <div class="sky-card__header">UK</div>
          <div class="sky-card__subtitle">Historic Excellence</div>
          <div class="sky-card__image-wrapper">
            <img src="assets/images/rus.png" alt="Moldova Castle 3D Pop-out">
          </div>
        </div>
      </div>

      <!-- 8. Canada -->
      <div class="sky-card sky-card--london" data-index="7" onclick="window.location.href='study-in-canada.php'" style="cursor: pointer;">
        <div class="sky-card__body">
          <div class="sky-card__header">CANADA</div>
          <div class="sky-card__subtitle">Welcoming Culture</div>
          <div class="sky-card__image-wrapper">
            <img src="assets/images/3d_canada.png" alt="Canada CN Tower 3D Pop-out">
          </div>
        </div>
      </div>

      <!-- 9. Japan -->
      <div class="sky-card sky-card--london" data-index="8" onclick="window.location.href='study-in-japan.php'" style="cursor: pointer;">
        <div class="sky-card__body">
          <div class="sky-card__header">JAPAN</div>
          <div class="sky-card__subtitle">Tech & Tradition</div>
          <div class="sky-card__image-wrapper">
            <img src="assets/images/3d_tower_bridge.png" alt="Ukraine Monument 3D Pop-out">
          </div>
        </div>
      </div>

      <!-- 10. Germany -->
      <div class="sky-card sky-card--sydney" data-index="9">
        <div class="sky-card__body">
          <div class="sky-card__header">GERMANY</div>
          <div class="sky-card__subtitle">Engineering Hub</div>
          <div class="sky-card__image-wrapper">
            <img src="assets/images/3d_germany.png" alt="Germany Brandenburg Gate 3D Pop-out">
          </div>
        </div>
      </div>
    </div>

    <!-- Country Pill Badges Grid -->
    <div class="hero-sky__countries-grid" style="flex-shrink: 0; position: relative; z-index: 20;">
      <a href="Australia.php" class="country-pill-badge">
        <span class="flag-emoji">🇦🇺</span> Australia
      </a>
      <a href="study-in-china.php" class="country-pill-badge">
        <span class="flag-emoji">🇨🇳</span> China
      </a>
      <a href="study-in-luxembourg.php" class="country-pill-badge active">
        <span class="flag-emoji">🇱🇺</span> Luxembourg
      </a>
      <a href="study-in-kazakhstan.php" class="country-pill-badge">
        <span class="flag-emoji">🇰🇿</span> Kazakhstan
      </a>
      <a href="Russia.php" class="country-pill-badge">
        <span class="flag-emoji">🇷🇺</span> Russia
      </a>
      <a href="study-in-spain.php" class="country-pill-badge">
        <span class="flag-emoji">🇪🇸</span> Spain
      </a>
      <a href="index.php" class="country-pill-badge">
        <span class="flag-emoji">🇲🇩</span> Moldova
      </a>
      <a href="canada.php" class="country-pill-badge">
        <span class="flag-emoji">🇨🇦</span> Canada
      </a>
      <a href="index.php" class="country-pill-badge">
        <span class="flag-emoji">🇺🇦</span> Ukraine
      </a>
      <a href="Germany.php" class="country-pill-badge">
        <span class="flag-emoji">🇩🇪</span> Germany
      </a>
    </div>
  </div>
  <!-- Bottom Concave Curve -->
  <div class="hero-sky__bottom-curve">
    <svg viewBox="0 0 1440 120" fill="none" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="none">
      <path d="M0,0 C480,100 960,100 1440,0 L1440,120 L0,120 Z" fill="#ffffff"/>
    </svg>
  </div>
</section>




<!-- HOME ENQUIRY SECTION (IDP STYLE) -->
<section class="home-enquiry section" style="background: #ffffff;">
  <div class="container">
    <div class="home-enquiry__grid">
      <!-- LEFT: Heading + Image -->
      <div class="home-enquiry__image-col">
        <div class="section__header" style="text-align: left; margin-bottom: 2rem; max-width: 100%;">
          <span class="section__tag">Direct Admission &amp; Counselling</span>
          <h2 class="section__title" style="margin-top: 1rem;">Interested in <span>Studying Abroad</span> with Bluestone?</h2>
          <p>Enter your details below and our expert study abroad counsellor will contact you shortly to guide you through every step.</p>
        </div>
        <div class="home-enquiry__image">
          <img src="assets/images/cont.png" alt="Study Abroad Expert Counsellor helping student">
        </div>
      </div>

      <!-- RIGHT: Form Card Only -->
      <div class="home-enquiry__form-col">
        <div class="home-enquiry__form-card">
          <!-- Background Glowing Blobs -->
          <div class="form-blobs">
            <div class="form-blob form-blob--1"></div>
            <div class="form-blob form-blob--2"></div>
            <div class="form-blob form-blob--3"></div>
          </div>
          <form id="homeEnquiryForm" onsubmit="return handleFormSubmit(event)">
            <input type="hidden" name="form_type" value="enquiry">
            <div class="cf-grid-2">
              <div class="cf-group"><label>First name*</label><input type="text" name="first_name" placeholder="First name" required></div>
              <div class="cf-group"><label>Last name*</label><input type="text" name="last_name" placeholder="Last name" required></div>
            </div>
             <div class="cf-grid-2">
            <div class="cf-group"><label>Email address*</label><input type="email" name="email" placeholder="Email address" required></div>
            
            <div class="cf-group"><label>Mobile number*</label>
            
              <div style="display: flex; gap: 0.5rem;">
                <input type="text" value="+91" readonly style="width: 60px; text-align: center; background: #e2e8f0; font-weight: 700;">
                <input type="tel" name="phone" placeholder="Mobile number" required style="flex: 1;">
              </div>
              </div>
            </div>

            <div class="cf-grid-2">
              <div class="cf-group"><label>Preferred study destination*</label>
                <select name="destination" required>
                  <option value="" disabled selected>Select</option>
                  <option>Australia</option><option>Canada</option><option>UK</option><option>USA</option><option>New Zealand</option><option>Ireland</option><option>Germany</option>
                </select>
              </div>
              <div class="cf-group"><label>When would you like to start?*</label>
                <select name="start_date" required>
                  <option value="" disabled selected>Select</option>
                  <option>Jan 2026</option><option>May 2026</option><option>Sept 2026</option><option>Jan 2027</option>
                </select>
              </div>
            </div>

            <div class="cf-grid-2">
              <div class="cf-group"><label>Preferred study level*</label>
                <select name="study_level" required>
                  <option value="" disabled selected>Select</option>
                  <option>Undergraduate</option><option>Postgraduate</option><option>MBA</option><option>PhD</option>
                </select>
              </div>
              <div class="cf-group"><label>Preferred mode of counselling*</label>
                <select name="counselling_mode" required>
                  <option value="" disabled selected>Select</option>
                  <option>In-person</option><option>Virtual Counselling</option>
                </select>
              </div>
            </div>

               <div class="cf-group"><label>Message</label>
          
                <textarea name="message" placeholder="Message"></textarea>
              
            </div>

            <button type="submit" class="btn btn--primary btn--md" style="width: 100%; justify-content: center; margin-top: 1rem; height: 46px;">Help me study abroad</button>
            <p style="font-size: 0.75rem; color: #94a3b8; margin-top: 1rem; text-align: center;">Bluestone Overseas will use your information to contact you for study abroad services.</p>
          </form>
        </div>
      </div>
    </div>
  </div>
</section>


<section class="section why-elite-section" id="about" style="background: var(--light); padding: 5rem 0; overflow: hidden;">
  <div class="container">
    <div style="background: var(--gradient); border-radius: 40px; padding: 4rem; box-shadow: 0 20px 50px rgba(204, 35, 102, 0.25); position: relative; overflow: hidden;">
      
      <!-- Decorative faint background shapes -->
      <div style="position: absolute; top: -50px; right: -50px; width: 300px; height: 300px; background: rgba(255,255,255,0.05); border-radius: 50%; pointer-events: none;"></div>
      <div style="position: absolute; bottom: -100px; left: 20%; width: 400px; height: 400px; background: rgba(255,255,255,0.05); border-radius: 50%; pointer-events: none;"></div>

      <div class="why-elite-grid" style="position: relative; z-index: 1;">
        <!-- LEFT COLUMN -->
        <div class="why-elite-content animate-on-scroll">
          <span class="section__tag" style="background: rgba(255,255,255,0.2); color: #fff; border-color: rgba(255,255,255,0.3);">Why Choose</span>
          <h2 class="section__title" style="margin-bottom: 1rem; color: #fff;">Bluestone </h2>
          <p class="section__subtitle" style="margin-bottom: 2.5rem; color: rgba(255,255,255,0.9);">Achieve your dream of studying abroad with expert admission, visa, IELTS, and university guidance.</p>
          
          <div class="elite-features-grid">
            <!-- Feature 1 -->
            <div style="display: flex; flex-direction: column; gap: 1rem; align-items: flex-start; background: rgba(255, 255, 255, 0.1); padding: 1.5rem; border-radius: 20px; border: 1px solid rgba(255, 255, 255, 0.15); backdrop-filter: blur(10px); transition: all 0.3s ease;" onmouseover="this.style.transform='translateY(-5px)'; this.style.background='rgba(255, 255, 255, 0.2)';" onmouseout="this.style.transform='translateY(0)'; this.style.background='rgba(255, 255, 255, 0.1)';">
              <div style="width: 50px; height: 50px; border-radius: 14px; background: rgba(255,255,255,0.2); color: #fff; display: flex; align-items: center; justify-content: center; font-size: 1.3rem; transition: all 0.3s ease;"><i class="fa-solid fa-trophy"></i></div>
              <div>
                <h4 style="font-size: 1.15rem; font-weight: 700; margin-bottom: 0.5rem; color: #fff;">Award Winning Consultancy</h4>
                <p style="font-size: 0.9rem; color: rgba(255,255,255,0.8); line-height: 1.5; margin: 0;">Trusted brand since 2015, offering honest and accurate guidance. No false promises.</p>
              </div>
            </div>
            
            <!-- Feature 2 -->
            <div style="display: flex; flex-direction: column; gap: 1rem; align-items: flex-start; background: rgba(255, 255, 255, 0.1); padding: 1.5rem; border-radius: 20px; border: 1px solid rgba(255, 255, 255, 0.15); backdrop-filter: blur(10px); transition: all 0.3s ease;" onmouseover="this.style.transform='translateY(-5px)'; this.style.background='rgba(255, 255, 255, 0.2)';" onmouseout="this.style.transform='translateY(0)'; this.style.background='rgba(255, 255, 255, 0.1)';">
              <div style="width: 50px; height: 50px; border-radius: 14px; background: rgba(255,255,255,0.2); color: #fff; display: flex; align-items: center; justify-content: center; font-size: 1.3rem; transition: all 0.3s ease;"><i class="fa-solid fa-headset"></i></div>
              <div>
                <h4 style="font-size: 1.15rem; font-weight: 700; margin-bottom: 0.5rem; color: #fff;">Dedicated Counsellors</h4>
                <p style="font-size: 0.9rem; color: rgba(255,255,255,0.8); line-height: 1.5; margin: 0;">Each student is assigned a personal counsellor who guides from start to finish.</p>
              </div>
            </div>
            
            <!-- Feature 3 -->
            <div style="display: flex; flex-direction: column; gap: 1rem; align-items: flex-start; background: rgba(255, 255, 255, 0.1); padding: 1.5rem; border-radius: 20px; border: 1px solid rgba(255, 255, 255, 0.15); backdrop-filter: blur(10px); transition: all 0.3s ease;" onmouseover="this.style.transform='translateY(-5px)'; this.style.background='rgba(255, 255, 255, 0.2)';" onmouseout="this.style.transform='translateY(0)'; this.style.background='rgba(255, 255, 255, 0.1)';">
              <div style="width: 50px; height: 50px; border-radius: 14px; background: rgba(255,255,255,0.2); color: #fff; display: flex; align-items: center; justify-content: center; font-size: 1.3rem; transition: all 0.3s ease;"><i class="fa-solid fa-handshake"></i></div>
              <div>
                <h4 style="font-size: 1.15rem; font-weight: 700; margin-bottom: 0.5rem; color: #fff;">500+ University Tie-ups</h4>
                <p style="font-size: 0.9rem; color: rgba(255,255,255,0.8); line-height: 1.5; margin: 0;">Direct partnerships with universities for faster admissions & scholarships.</p>
              </div>
            </div>
            
            <!-- Feature 4 -->
            <div style="display: flex; flex-direction: column; gap: 1rem; align-items: flex-start; background: rgba(255, 255, 255, 0.1); padding: 1.5rem; border-radius: 20px; border: 1px solid rgba(255, 255, 255, 0.15); backdrop-filter: blur(10px); transition: all 0.3s ease;" onmouseover="this.style.transform='translateY(-5px)'; this.style.background='rgba(255, 255, 255, 0.2)';" onmouseout="this.style.transform='translateY(0)'; this.style.background='rgba(255, 255, 255, 0.1)';">
              <div style="width: 50px; height: 50px; border-radius: 14px; background: rgba(255,255,255,0.2); color: #fff; display: flex; align-items: center; justify-content: center; font-size: 1.3rem; transition: all 0.3s ease;"><i class="fa-solid fa-passport"></i></div>
              <div>
                <h4 style="font-size: 1.15rem; font-weight: 700; margin-bottom: 0.5rem; color: #fff;">99% Visa Success Rate</h4>
                <p style="font-size: 0.9rem; color: rgba(255,255,255,0.8); line-height: 1.5; margin: 0;">Our visa team has an exceptional track record across all major destinations.</p>
              </div>
            </div>
          </div>

          <div style="display: flex; gap: 1rem; flex-wrap: wrap;">
            <a href="About_us.php" class="btn btn--primary" style="background: var(--primary); border-color: var(--primary); color: #fff;"><i class="fa-solid fa-circle-info"></i> Learn About Us</a>
            <a href="consultation.php" class="btn" style="border: 1px solid rgba(255,255,255,0.5); color: #fff; background: transparent; transition: all 0.3s;" onmouseover="this.style.background='rgba(255,255,255,0.1)'; this.style.borderColor='#fff';" onmouseout="this.style.background='transparent'; this.style.borderColor='rgba(255,255,255,0.5)';"><i class="fa-solid fa-phone"></i> Talk to Expert</a>
          </div>
        </div>
        
        <!-- RIGHT COLUMN (IMAGE) -->
        <div class="why-elite-image animate-on-scroll delay-2">
          
          <!-- Background Shape Blob -->
          <div class="why-elite-blob" onmouseover="this.style.borderRadius='57% 43% 59% 41% / 54% 62% 38% 46%'; this.style.transform='rotate(5deg) scale(1.05)';" onmouseout="this.style.borderRadius='41% 59% 43% 57% / 46% 38% 62% 54%'; this.style.transform='rotate(-15deg) scale(1)';"></div>
          
          <!-- Foreground Circular Image -->
          <div class="why-elite-circle" onmouseover="this.style.transform='translate(-10%, 10%) scale(1.03)';" onmouseout="this.style.transform='translate(-10%, 10%) scale(1)';">
            <img src="assets/images/seminar.png" alt="Consultancy Event" style="width: 100%; height: 100%; object-fit: cover; display: block;">
          </div>
          
        </div>
      </div>
    </div>
  </div>
</section>


<!-- COUNTRIES -->
<section class="section countries-section" id="destinations" style="background: #ffffff;">
  <div class="container">
    <div class="section__header animate-on-scroll">
      <span class="section__tag">Study Destinations</span>
      <h2 class="section__title">Choose Your <span>Dream Country</span></h2>
      <p class="section__subtitle">Explore 20+ top study destinations. We have local expertise and university tie-ups in every country.</p>
      <div class="accent-bar"></div>
    </div>
    <div class="country-marquee-container">
      <?php
      try {
          $stmt = $pdo->query("SELECT * FROM countries WHERE is_active = 1 ORDER BY id ASC");
          $countries_db = $stmt->fetchAll();
          $all_countries = [];
          foreach ($countries_db as $c) {
              $all_countries[] = [
                  $c['slug'],
                  $c['name'],
                  $c['flag'],
                  $c['description']
              ];
          }
      } catch (PDOException $e) {
          $all_countries = [
            ['usa', 'United States', '🇺🇸', 'World-class universities with cutting-edge research facilities.'],
            ['uk', 'United Kingdom', '🇬🇧', 'Short duration courses with excellent academic reputation.'],
            ['canada', 'Canada', '🇨🇦', 'Safe, multicultural and affordable with great PR pathways.'],
            ['australia', 'Australia', '🇦🇺', 'Globally recognised degrees with excellent post-study work rights.'],
            ['germany', 'Germany', '🇩🇪', 'Free or low-cost tuition at top-ranked public universities.'],
            ['ireland', 'Ireland', '🇮🇪', 'English-speaking, tech-hub with a vibrant student community.'],
            ['singapore', 'Singapore', '🇸🇬', 'Asia\'s education capital with globally ranked universities.'],
            ['newzealand', 'New Zealand', '🇳🇿', 'Safe, scenic and student-friendly with excellent QS-ranked universities.'],
            ['france', 'France', '🇫🇷', 'Global leader in business, fashion, and culinary arts.'],
            ['italy', 'Italy', '🇮🇹', 'Study at the world\'s oldest universities in the land of art.'],
            ['sweden', 'Sweden', '🇸🇪', 'Innovation hub of Europe and home to the Nobel Prize.'],
            ['south-korea', 'South Korea', '🇰🇷', 'Leading in technology, robotics, and advanced research.'],
            ['uae', 'UAE', '🇦🇪', 'Tax-free work opportunities and global branch campuses.'],
            ['netherlands', 'Netherlands', '🇳🇱', 'First non-English country to offer courses in English.'],
            ['switzerland', 'Switzerland', '🇨🇭', 'Global center for banking, research, and hospitality.'],
            ['malaysia', 'Malaysia', '🇲🇾', 'UK and Australian degrees at a fraction of the cost.'],
            ['denmark', 'Denmark', '🇩🇰', 'Focus on innovation and high standard of living.'],
            ['bulgaria', 'Bulgaria', '🇧🇬', 'EU-recognized degrees with low tuition and living costs.'],
            ['russia', 'Russia', '🇷🇺', 'Strong legacy in medicine and engineering at low cost.'],
            ['philippines', 'Philippines', '🇵🇭', 'US-pattern medical education in a friendly environment.']
          ];
      }

      $row1 = array_slice($all_countries, 0, 10);
      $row2 = array_slice($all_countries, 10, 10);
      ?>

      <!-- Row 1: Right to Left -->
      <div class="country-marquee-wrapper">
        <div class="country-marquee-track marquee-left">
          <?php foreach (array_merge($row1, $row1) as $i => [$slug, $name, $flag, $desc]): ?>
            <a href="study-in-<?= $slug ?>.php" class="country-card-minimal">
              <div class="country-card-minimal__img">
                <img src="<?= get_country_image_url($slug) ?>" alt="<?= $name ?>">
                <div class="country-card-minimal__content">
                  <h3><?= $name ?></h3>
                  <p><?= $desc ?></p>
                </div>
              </div>
            </a>
          <?php endforeach; ?>
        </div>
      </div>

      <!-- Row 2: Left to Right -->
      <div class="country-marquee-wrapper" style="margin-top: 1.5rem;">
        <div class="country-marquee-track marquee-right">
          <?php foreach (array_merge($row2, $row2) as $i => [$slug, $name, $flag, $desc]): ?>
            <a href="study-in-<?= $slug ?>.php" class="country-card-minimal">
              <div class="country-card-minimal__img">
                <img src="<?= get_country_image_url($slug) ?>" alt="<?= $name ?>">
                <div class="country-card-minimal__content">
                  <h3><?= $name ?></h3>
                  <p><?= $desc ?></p>
                </div>
              </div>
            </a>
          <?php endforeach; ?>
        </div>
      </div>

      <div class="slider-hint animate-up" style="margin-top: 2rem;"><i class="fa-solid fa-mouse-pointer"></i> Hover on a card to pause</div>
    </div>

    <div style="text-align:center;margin-top:3.5rem">
      <a href="country.php" class="btn btn--outline btn--lg"><i class="fa-solid fa-earth-americas"></i> View All 20+ Destinations</a>
    </div>
  </div>
</section>
<!-- PROCESS -->
<section class="section process-section" style="background: #ffffff; padding: 4rem 0;">
  <div class="container">
    <div style="background: #579df9ff; border-radius: 30px; padding: 4rem; position: relative; overflow: hidden; box-shadow: 0 20px 40px rgba(24, 119, 242, 0.25);">
      
      <!-- Decorative faint background shapes -->
      <div style="position: absolute; top: -50px; right: -50px; width: 300px; height: 300px; background: rgba(255,255,255,0.05); border-radius: 50%; pointer-events: none;"></div>
      <div style="position: absolute; bottom: -100px; left: 20%; width: 400px; height: 400px; background: rgba(255,255,255,0.05); border-radius: 50%; pointer-events: none;"></div>

      <div style="position: relative; z-index: 1;">
        
        <div class="section__header animate-on-scroll" style="text-align: center; margin-bottom: 3rem;">
          <span class="section__tag" style="background: rgba(255,255,255,0.2); color: #fff; margin-bottom: 1rem; display: inline-block;">How It Works</span>
          <h2 class="section__title" style="color: #fff; font-size: 2.8rem; line-height: 1.2;">Your Journey to <span style="color: #FDE047;">Global Education</span></h2>
        </div>
        
        <div class="process-steps" style="justify-content: center; gap: 2rem; margin-top: 0;">
          <?php
          try {
              $stmt = $pdo->query("SELECT icon, title, description, color FROM process_steps WHERE is_active = 1 ORDER BY id ASC LIMIT 5");
              $db_steps = $stmt->fetchAll();
              if (count($db_steps) > 0) {
                  $steps = [];
                  foreach ($db_steps as $s) {
                      $steps[] = [$s['icon'], $s['title'], $s['description'], $s['color']];
                  }
              }
          } catch (PDOException $e) {
              // Fallback is handled below if $steps is not set
          }

          if (!isset($steps) || count($steps) == 0) {
              $steps=[
                ['fa-comments','Free Counselling','Book a free session with our expert counsellor who assesses your profile and goals.','blue'],
                ['fa-magnifying-glass','Course & Country Selection','We shortlist the best universities and programs matching your ambitions and budget.','purple'],
                ['fa-file-contract','Application Filing','Our team prepares and submits your application with a flawless SOP and documents.','orange'],
                ['fa-passport','Visa Processing','Get expert help with student visa applications, ensuring all requirements are met.','teal'],
                ['fa-plane-departure','Fly Abroad!','Pre-departure briefing, accommodation guidance and you are off to your dream university!','pink'],
              ];
          }
          foreach($steps as $i=>[$icon,$title,$desc,$color]):
          ?>
          <div class="process-step animate-on-scroll delay-<?= $i ?>" style="flex: 1 1 160px; max-width: 220px; text-align: center;">
            <div class="process-step__image-box" style="width: 140px; height: 140px; margin: 0 auto 1.5rem;">
              <img src="assets/images/img<?= $i+1 ?>.png" alt="<?= $title ?>">
              <div class="process-step__badge" style="bottom: -12px; width: 28px; height: 28px; font-size: 0.85rem; line-height: 28px;"><?= str_pad($i+1, 2, '0', STR_PAD_LEFT) ?></div>
            </div>
            <h4 style="color: #ffffff; font-size: 1.1rem; margin-bottom: 0.5rem;"><?= $title ?></h4>
            <p style="color: rgba(255,255,255,0.85); font-size: 0.9rem; line-height: 1.4;"><?= $desc ?></p>
          </div>
          <?php endforeach; ?>
        </div>
        
        <div style="text-align: center; margin-top: 3rem;">
          <a href="consultation.php" class="btn btn--primary" style="background: var(--primary); color: #fff; padding: 1rem 2.5rem; border-radius: 50px; font-weight: 600; text-decoration: none; display: inline-flex; align-items: center; gap: 0.5rem; transition: all 0.3s ease; box-shadow: 0 10px 20px rgba(30, 41, 59, 0.2);" onmouseover="this.style.transform='translateY(-3px)'; this.style.boxShadow='0 15px 25px rgba(30, 41, 59, 0.3)';" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 10px 20px rgba(30, 41, 59, 0.2)';">
          Talk to Expert <i class="fa-solid fa-arrow-right"></i>
          </a>
        </div>

      </div>
    </div>
  </div>
</section>

<section class="section services-bento" id="services" style="background: #ffffff;">
  <div class="container">
    <div class="section__header animate-on-scroll" style="text-align: center; margin-bottom: 3rem;">
      <span class="section__tag" style="background: rgba(124,58,237,0.1); color: #7c3aed; border-radius: 20px; padding: 5px 15px; font-weight: 600; display: inline-block; margin-bottom: 1rem; text-transform: none;">What We Do</span>
      <h2 class="section__title" style="text-transform: none; font-size: 2.8rem; font-weight: 500; color: #1e293b; line-height: 1.2;">Comprehensive <span class="serif-italic" style="color: #6366f1; font-weight: 400; font-size: 1.1em;">Study Abroad</span> Services</h2>
      <p class="section__subtitle">From counselling to visa processing &mdash; we support you at every step of your international education journey.</p>
    </div>

    <!-- Full-width grid container -->
    <div style="width: 100%; overflow: hidden;">
      <?php
      try {
          $stmt = $pdo->query("SELECT * FROM services WHERE is_active = 1 ORDER BY id ASC LIMIT 8");
          $services_db = $stmt->fetchAll();
          $services = [];
          foreach ($services_db as $s) {
              $services[] = [
                  $s['icon'],
                  $s['title'],
                  $s['description'],
                  $s['link'],
                  $s['color']
              ];
          }
      } catch (PDOException $e) {
          $services = [
              ['fa-user-graduate','Student Counselling','Personalised guidance to help you choose the right course and institution matching your academic goals and budget.','student-counselling.php','blue'],
              ['fa-university','University Selection','We help identify the best-fit universities across 20+ countries based on your profile and aspirations.','university-selection.php','purple'],
              ['fa-file-contract','Admission Processing','Expert application management ensuring all documents are accurate, complete and submitted on time.','admission-processing.php','orange'],
              ['fa-hand-holding-dollar','Financial Assistance','Guidance on scholarships, student loans and funding options to make your dream affordable.','financial-assistance.php','teal'],
              ['fa-passport','Visa Processing','End-to-end visa assistance with a 98% success rate, navigating complex immigration requirements.','visa-processing.php','pink'],
              ['fa-house','Accommodation & Travel','We help arrange housing and travel plans so you arrive and settle comfortably in your new country.','accommodation.php','gold'],
              ['fa-pen-to-square','Test Preparation','Specialised coaching for IELTS, TOEFL and PTE to achieve the scores required by top universities.','test-prep.php','blue'],
              ['fa-briefcase','Part-Time Job Help','Guidance on finding legal part-time work opportunities abroad to support yourself financially.','part-time-jobs.php','purple'],
          ];
      }

      // Dynamic URL normalization mapping to ensure DB records also link directly to dedicated pages
      $linkMap = [
          'services.php?s=counselling' => 'student-counselling.php',
          'services.php?s=university' => 'university-selection.php',
          'services.php?s=admission' => 'admission-processing.php',
          'services.php?s=financial' => 'financial-assistance.php',
          'services.php?s=visa' => 'visa-processing.php',
          'services.php?s=accommodation' => 'accommodation.php',
          'services.php?s=jobs' => 'part-time-jobs.php'
      ];
      $titleMap = [
          'student counselling' => 'student-counselling.php',
          'university selection' => 'university-selection.php',
          'application assistance' => 'admission-processing.php',
          'admission processing' => 'admission-processing.php',
          'scholarship assistance' => 'financial-assistance.php',
          'financial assistance' => 'financial-assistance.php',
          'visa guidance' => 'visa-processing.php',
          'visa processing' => 'visa-processing.php',
          'travel & accommodation' => 'accommodation.php',
          'accommodation & travel' => 'accommodation.php',
          'pre-departure briefing' => 'accommodation.php',
          'ielts/toefl coaching' => 'test-prep.php',
          'part-time job help' => 'part-time-jobs.php',
          'part-time job assistance' => 'part-time-jobs.php'
      ];
      
      $imageMap = [
          'student counselling' => 's7.jpg',
          'university selection' => 's2.png',
          'admission processing' => 's3.jpg',
          'application assistance' => 's3.jpg',
          'financial assistance' => 's5.jpg',
          'scholarship assistance' => 's5.jpg',
          'visa processing' => 's5.jpg',
          'visa guidance' => 's5.jpg',
          'accommodation & travel' => 's9.webp',
          'travel & accommodation' => 's9.webp',
          'pre-departure briefing' => 's9.webp',
          'test preparation' => 's6.jpg',
          'ielts/toefl coaching' => 's6.jpg',
          'part-time job help' => 's6.jpg',
          'part-time job assistance' => 's6.jpg'
      ];

      foreach ($services as &$s_item) {
          $currentLink = $s_item[3];
          $titleLower = strtolower(trim($s_item[1]));
          if (isset($linkMap[$currentLink])) {
              $s_item[3] = $linkMap[$currentLink];
          } elseif ($currentLink === '#' || strpos($currentLink, 'services.php') !== false) {
              if (isset($titleMap[$titleLower])) {
                  $s_item[3] = $titleMap[$titleLower];
              }
          }
      }
      unset($s_item); // break loop reference safety
      ?>
      <div class="portfolio-grid">
      <?php
      // Varying heights to create the masonry effect
      $heights = ['300px', '400px', '350px', '450px', '320px', '380px', '420px', '280px'];
      foreach($services as $i=>[$icon,$title,$desc,$link,$color]):
          $imgHeight = $heights[$i % 8];
          $titleLower = strtolower(trim($title));
          $imgSrc = isset($imageMap[$titleLower]) ? $imageMap[$titleLower] : "img".(($i % 8) + 1).".png";
      ?>
      <a href="<?= $link ?>" class="portfolio-card animate-on-scroll delay-<?= $i%4 ?>" style="height: <?= $imgHeight ?>;">
        <img src="assets/images/<?= $imgSrc ?>" alt="<?= $title ?>" class="portfolio-card__bg">
        <div class="portfolio-card__overlay"></div>
        <div class="portfolio-card__content">
          <h3 class="portfolio-card__title"><?= $title ?></h3>
          <p class="portfolio-card__desc"><?= $desc ?></p>
        </div>
      </a>
      <?php endforeach; ?>
      </div>
  </div>
</section>

<!-- TEST PREP -->
<section class="section" style="background: #ffffff; padding: 4rem 0;">
  <div class="container">
    <div style="background: #e481ebff; border-radius: 30px; padding: 4rem; position: relative; overflow: hidden; box-shadow: 0 20px 40px rgba(124, 58, 237, 0.25);">
      
      <!-- Decorative faint background shapes -->
      <div style="position: absolute; top: -50px; right: -50px; width: 300px; height: 300px; background: rgba(255,255,255,0.05); border-radius: 50%; pointer-events: none;"></div>
      <div style="position: absolute; bottom: -100px; left: 20%; width: 400px; height: 400px; background: rgba(255,255,255,0.05); border-radius: 50%; pointer-events: none;"></div>

      <div style="position: relative; z-index: 1;">
        
        <div class="section__header animate-on-scroll" style="text-align: center; margin-bottom: 3rem;">
          <span class="section__tag" style="background: rgba(255,255,255,0.2); color: #fff; border-radius: 20px; padding: 0.5rem 1rem; display: inline-flex; align-items: center; gap: 0.5rem; margin-bottom: 1rem;">
            <i class="fa-solid fa-bolt"></i> Coachings
          </span>
          <h2 class="section__title coaching-title" style="color: #fff; line-height: 1.2;">Discover the elite training<br><span style="color: #FDE047;">Program you need</span></h2>
        </div>

        <div class="test-cards-new">
          <?php
          try {
              $stmt = $pdo->query("SELECT * FROM test_preps WHERE is_active = 1 ORDER BY id ASC LIMIT 5");
              $db_tests = $stmt->fetchAll();
          } catch (PDOException $e) {
              $db_tests = [];
          }
          
          if (count($db_tests) < 5) {
              $db_tests = [
                  [
                      'slug' => 'toefl',
                      'name' => 'TOEFL',
                      'icon' => 'fa-globe',
                      'description' => 'Ace the TOEFL with proven strategies.',
                      'image_path' => 'assets/images/service_university_3d.png'
                  ],
                  [
                      'slug' => 'pte',
                      'name' => 'PTE',
                      'icon' => 'fa-computer',
                      'description' => 'Achieve your desired PTE score with specialized training.',
                      'image_path' => 'assets/images/service_guidance_3d.png'
                  ],
                  [
                      'slug' => 'ielts',
                      'name' => 'IELTS',
                      'icon' => 'fa-pen-to-square',
                      'description' => 'Master the IELTS exam with our comprehensive training.',
                      'image_path' => 'assets/images/service_coaching_3d.png'
                  ],
                  [
                      'slug' => 'japanese',
                      'name' => 'Japanese',
                      'icon' => 'fa-language',
                      'description' => 'Learn from basics to JLPT mastery.',
                      'image_path' => 'assets/images/service_visa_3d.png'
                  ],
                  [
                      'slug' => 'german',
                      'name' => 'German',
                      'icon' => 'fa-language',
                      'description' => 'Master German language for your global career.',
                      'image_path' => 'assets/images/service_university_3d.png'
                  ]
              ];
          }
          
          $bg_classes = ['test-card-new--orange', 'test-card-new--blue', 'test-card-new--green', 'test-card-new--pink', 'test-card-new--purple'];
          $fallback_images = ['assets/images/service_university_3d.png', 'assets/images/service_guidance_3d.png', 'assets/images/service_coaching_3d.png', 'assets/images/service_visa_3d.png', 'assets/images/service_university_3d.png'];
          foreach($db_tests as $index => $test):
              $slug = clean_output($test['slug']);
              $name = clean_output($test['name']);
              $desc = clean_output($test['description']);
              $bg_class = $bg_classes[$index % count($bg_classes)];
              $img_src = !empty($test['image_path']) ? clean_output($test['image_path']) : $fallback_images[$index % count($fallback_images)];
          ?>
          <div class="test-card-new <?= $bg_class ?>" data-pos="<?= $index + 1 ?>">
            <div class="test-card-new__img">
              <img src="<?= $img_src ?>" alt="<?= $name ?>">
            </div>
            <div class="test-card-new__content">
              
              <div class="app-title-area text-center" style="margin-bottom: 1rem;">
                <h3 class="app-title" style="font-weight: 800; font-size: 1.5rem; color: #1f2937; margin-bottom: 0.5rem;"><?= $name ?></h3>
                <p class="app-subtitle" style="font-size: 0.9rem; color: #6b7280; line-height: 1.4; height: 40px; overflow: hidden;"><?= $desc ?></p>
              </div>
              
              <!-- App Widgets Grid -->
              <div class="app-widgets-grid">
                <div class="app-widget">
                  <i class="fa-solid fa-star" style="color: #F59E0B; font-size: 1.25rem; margin-bottom: 0.5rem;"></i>
                  <span style="font-size: 0.75rem; font-weight: 700; color: #374151;">Top Rated</span>
                </div>
                <div class="app-widget">
                  <i class="fa-solid fa-laptop" style="color: #3B82F6; font-size: 1.25rem; margin-bottom: 0.5rem;"></i>
                  <span style="font-size: 0.75rem; font-weight: 700; color: #374151;">Mock Tests</span>
                </div>
                <div class="app-widget">
                  <i class="fa-solid fa-book" style="color: #10B981; font-size: 1.25rem; margin-bottom: 0.5rem;"></i>
                  <span style="font-size: 0.75rem; font-weight: 700; color: #374151;">Materials</span>
                </div>
                <div class="app-widget">
                  <i class="fa-solid fa-user-tie" style="color: #8B5CF6; font-size: 1.25rem; margin-bottom: 0.5rem;"></i>
                  <span style="font-size: 0.75rem; font-weight: 700; color: #374151;">Experts</span>
                </div>
              </div>
              
              <a href="test-prep.php?t=<?= $slug ?>" class="btn test-card-new__btn" style="width: 100%; justify-content: center; background: #111827; color: white; border-radius: 12px; padding: 1rem; margin-top: 1rem; font-weight: 700;">Start Learning</a>
            </div>
          </div>
          <?php endforeach; ?>
          
          <button class="card-slider-nav card-slider-prev"><i class="fa-solid fa-chevron-left"></i></button>
          <button class="card-slider-nav card-slider-next"><i class="fa-solid fa-chevron-right"></i></button>
        </div>
        
        <script>
        document.addEventListener('DOMContentLoaded', function() {
            var cards = document.querySelectorAll('.test-card-new');
            var prevBtn = document.querySelector('.card-slider-prev');
            var nextBtn = document.querySelector('.card-slider-next');
            var autoRotate;

            function shiftCards(direction) {
                cards.forEach(function(card) {
                    var currentPos = parseInt(card.getAttribute('data-pos'));
                    var newPos = currentPos + direction;
                    if (newPos < 1) newPos = cards.length;
                    if (newPos > cards.length) newPos = 1;
                    card.setAttribute('data-pos', newPos);
                });
            }

            if (cards.length > 0) {
                autoRotate = setInterval(function() {
                    shiftCards(-1);
                }, 3000);

                if(prevBtn && nextBtn) {
                    prevBtn.addEventListener('click', function() {
                        clearInterval(autoRotate);
                        shiftCards(1);
                    });
                    nextBtn.addEventListener('click', function() {
                        clearInterval(autoRotate);
                        shiftCards(-1);
                    });
                }
            }
        });
        </script>
      </div>
    </div>
  </div>
<!-- GALLERY SECTION -->
<section class="section gallery-section" style="background: #ffffff;">
  <div class="container">
    <div class="section__header animate-on-scroll">
      <span class="section__tag">Success Stories</span>
      <h2 class="section__title">Glimpses of our <span>Success Events</span></h2>
      <div class="accent-bar"></div>
    </div>
    <div class="collage-gallery animate-on-scroll delay-1">
      <?php
      try {
          $stmt = $pdo->query("SELECT * FROM gallery_items WHERE is_active = 1 ORDER BY id DESC LIMIT 7");
          $db_gallery = $stmt->fetchAll();
      } catch (PDOException $e) {
          $db_gallery = [];
      }
      
      $fallbacks = [
          ['image_path' => 'assets/images/md-gallery5.png', 'title' => 'Student Seminar Event'],
          ['image_path' => 'assets/images/ocs5.png', 'title' => 'IELTS Coaching Session'],
          ['image_path' => 'assets/images/start.png', 'title' => 'Pre-Departure Briefing'],
          ['image_path' => 'assets/images/img1.png', 'title' => 'Visa Success Meet'],
          ['image_path' => 'assets/images/ocs.png', 'title' => 'Placement Seminar'],
          ['image_path' => 'assets/images/img2.png', 'title' => 'University Tour'],
          ['image_path' => 'assets/images/img3.png', 'title' => 'Admission Success']
      ];
      
      $items = [];
      for ($i=0; $i<7; $i++) {
          if (isset($db_gallery[$i])) {
              $items[] = $db_gallery[$i];
          } else {
              $items[] = $fallbacks[$i % count($fallbacks)];
          }
      }
      
      foreach($items as $i => $item):
      ?>
      <div class="collage-item collage-item-<?= $i+1 ?>">
        <img src="<?= clean_output($item['image_path']) ?>" alt="<?= clean_output($item['title']) ?>">

      </div>
      <?php endforeach; ?>
    </div>
    <div style="text-align:center; margin-top:2.5rem">
      <a href="gallery.php" class="btn btn--outline">View All Gallery <i class="fa-solid fa-images"></i></a>
    </div>
  </div>
</section>

<!-- CONTACT SECTION -->
<section class="section contact-section" id="contact-home" style="background: #ffffff; padding: 4rem 0;">
  <div class="container">
    <div style="background: #14b8a6; border-radius: 30px; padding: 4rem; position: relative; overflow: hidden; box-shadow: 0 20px 40px rgba(20, 184, 166, 0.25);">
      
      <!-- Decorative faint background shapes -->
      <div style="position: absolute; top: -50px; right: -50px; width: 300px; height: 300px; background: rgba(255,255,255,0.05); border-radius: 50%; pointer-events: none;"></div>
      <div style="position: absolute; bottom: -100px; left: 20%; width: 400px; height: 400px; background: rgba(255,255,255,0.05); border-radius: 50%; pointer-events: none;"></div>

      <div style="position: relative; z-index: 1;">
        <div class="section__header animate-on-scroll" style="text-align: center; margin-bottom: 3rem;">
          <span class="section__tag" style="background: rgba(255,255,255,0.2); color: #fff;">Contact Us</span>
          <h2 class="section__title" style="color: #fff;">Get a Free <span style="color: #FDE047; background: none; -webkit-text-fill-color: initial;">Consultation</span></h2>
          <p class="section__subtitle" style="color: rgba(255,255,255,0.9);">Reach out to our experts and start your journey today. We respond within 24 hours.</p>
        </div>
        
        <div class="contact-grid">
          <div class="animate-on-scroll">
            <h3 style="color: #fff; margin-bottom: 1rem; font-size: 2rem;">Talk to Our <span style="color: #FDE047; background: none; -webkit-text-fill-color: initial;">Experts</span></h3>
            <p style="color: rgba(255,255,255,0.9); margin-bottom: 2rem;">Whether you&rsquo;re just starting your study abroad journey or need help with a visa application, our counsellors are here to help — for free.</p>
            
            <div class="contact-cards">
              <div class="contact-card contact-card--glass">
                <div class="stat-icon stat-icon--glass" style="width:40px;height:40px;font-size:1rem"><i class="fa-solid fa-phone"></i></div>
                <div><h4>Call Us</h4><a href="tel:+919342899904">+91 93428 99904</a></div>
              </div>
              <div class="contact-card contact-card--glass">
                <div class="stat-icon stat-icon--glass" style="width:40px;height:40px;font-size:1rem"><i class="fa-solid fa-envelope"></i></div>
                <div><h4>Email Us</h4><a href="mailto:info@bluestoneocs.com">info@bluestoneocs.com</a></div>
              </div>
              <div class="contact-card contact-card--glass">
                <div class="stat-icon stat-icon--glass" style="width:40px;height:40px;font-size:1rem"><i class="fa-regular fa-clock"></i></div>
                <div><h4>Working Hours</h4><p>Mon–Fri: 09:00 AM – 6:30 PM</p></div>
              </div>
            </div>
            
            <a href="<?= SITE_MAP_LINK ?>" target="_blank" style="display:block;margin-top:2rem;padding:1.5rem;background:rgba(255,255,255,0.1);backdrop-filter:blur(10px);border-radius:var(--radius);border:1px solid rgba(255,255,255,0.2);text-decoration:none;color:#fff;transition:transform 0.3s ease,background 0.3s ease;" class="hover-scale-card glass-link">
              <h4 style="margin-bottom:1rem;display:flex;align-items:center;gap:0.5rem;color:#fff;"><i class="fa-solid fa-location-dot" style="color:#FDE047;"></i> Head Office – Coimbatore</h4>
              <p style="font-size:.875rem;color:rgba(255,255,255,0.9);line-height:1.7">Renaissance Terrace, NO.126L, 2nd Floor, Opp. Bishop Appasamy College, Coimbatore, TN - 641018</p>
            </a>
          </div>
          
          <div class="contact-form-wrap animate-on-scroll delay-1" style="background: #ffffff; border: none; box-shadow: 0 25px 50px rgba(0,0,0,0.15);">
            <form id="contactHomeForm" onsubmit="return handleFormSubmit(event)">
              <input type="hidden" name="form_type" value="contact">
              <div class="cf-grid-2">
                <div class="cf-group"><label>First Name *</label><input type="text" name="first_name" placeholder="John" required></div>
                <div class="cf-group"><label>Last Name *</label><input type="text" name="last_name" placeholder="Doe" required></div>
              </div>
              <div class="cf-grid-2">
                <div class="cf-group"><label>Email *</label><input type="email" name="email" placeholder="john@email.com" required></div>
                <div class="cf-group"><label>Phone *</label><input type="tel" name="phone" placeholder="+91 98765 43210" required></div>
              </div>
              <div class="cf-group"><label>Preferred Country</label>
                <select name="destination"><option value="">Select Country</option><option>USA</option><option>UK</option><option>Canada</option><option>Australia</option><option>Germany</option><option>Ireland</option><option>New Zealand</option><option>Singapore</option></select>
              </div>
              <div class="cf-group"><label>Your Message</label>
                <textarea name="query" rows="4" placeholder="How can we help you?"></textarea>
              </div>
              <button type="submit" class="btn btn--primary btn--lg" style="width:100%;justify-content:center">
                <i class="fa-solid fa-paper-plane"></i> Send Message
              </button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- VIDEO TESTIMONIALS -->
<section class="section testimonials-section" id="testimonials" style="background: #ffffff;">
  <div class="container">
    <div class="section__header animate-on-scroll" style="text-align: center; margin-bottom: 3.5rem;">
      <span class="section__tag">Success Stories</span>
      <h2 class="section__title">Student <span>Video Reviews</span></h2>
      <p class="section__subtitle" style="margin: 0.5rem auto 0; max-width: 600px;">Hear directly from our successful scholars sharing their visa journey and university experiences.</p>
      <div class="accent-bar" style="margin: 1rem auto 0;"></div>
    </div>
    
    <div class="testimonial-showcase">
      <?php
      try {
          $stmt = $pdo->query("SELECT * FROM testimonial_videos WHERE is_active = 1 ORDER BY id DESC LIMIT 4");
          $db_videos = $stmt->fetchAll();
      } catch (PDOException $e) {
          $db_videos = [];
      }
      
      if (empty($db_videos)) {
          $db_videos = [
              ['student_name' => 'Sai Raksha Manoharan', 'details' => 'MSc in United Kingdom', 'youtube_url' => 'https://www.youtube.com/embed/dQw4w9WgXcQ'],
              ['student_name' => 'Ashok Saravanan', 'details' => 'MBA in Canada', 'youtube_url' => 'https://www.youtube.com/embed/dQw4w9WgXcQ'],
              ['student_name' => 'Priya Krishnamoorthy', 'details' => 'MS in United States', 'youtube_url' => 'https://www.youtube.com/embed/dQw4w9WgXcQ'],
              ['student_name' => 'Anish Kumar', 'details' => 'BE in Australia', 'youtube_url' => 'https://www.youtube.com/embed/dQw4w9WgXcQ']
          ];
      }
      
      $featured = $db_videos[0];
      $others = array_slice($db_videos, 1);
      ?>
      
      <!-- Featured Video -->
      <div class="t-featured animate-on-scroll">
        <div class="t-featured__video">
          <?php 
          $f_src = clean_output($featured['youtube_url']);
          $f_is_local = (strpos($f_src, 'uploads/') === 0);
          if ($f_is_local): 
          ?>
            <video src="<?= $f_src ?>" controls></video>
          <?php else: ?>
            <iframe src="<?= $f_src ?>" allowfullscreen></iframe>
          <?php endif; ?>
        </div>
        <div class="t-featured__info">
          <div class="visa-badge"><i class="fa-solid fa-circle-check"></i> Visa Approved</div>
          <h3 class="t-featured__name"><?= clean_output($featured['student_name']) ?></h3>
          <p style="color: rgba(255,255,255,0.8); display: flex; align-items: center; gap: 0.5rem;">
            <i class="fa-solid fa-user-graduate" style="color: var(--primary);"></i>
            <?= clean_output($featured['details']) ?>
          </p>
        </div>
      </div>

      <!-- Side List -->
      <div class="t-list">
        <?php foreach($others as $i => $video): 
            $v_src = clean_output($video['youtube_url']);
        ?>
        <div class="t-item-small animate-on-scroll delay-<?= $i ?>" onclick="window.location.href='testimonial-videos.php'">
          <div class="t-item-small__thumb" style="position: relative; overflow: hidden;">
            <div style="pointer-events: none; position: absolute; top: 0; left: 0; width: 100%; height: 100%;">
                <?php if (strpos($v_src, 'uploads/') === 0): ?>
                    <video src="<?= $v_src ?>" style="width: 100%; height: 100%; border: none; object-fit: cover;"></video>
                <?php else: ?>
                    <iframe src="<?= $v_src ?>" style="width: 100%; height: 100%; border: none;" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                <?php endif; ?>
            </div>
            <div style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.15); display: flex; align-items: center; justify-content: center;">
                <i class="fa-solid fa-circle-play" style="position: static; transform: none; font-size: 1.5rem;"></i>
            </div>
          </div>
          <div class="t-item-small__content">
            <div class="visa-badge" style="padding: 0.15rem 0.4rem; font-size: 0.65rem;"><i class="fa-solid fa-circle-check"></i> Success</div>
            <h4 style="font-size: 1rem; font-weight: 700; color: var(--dark); margin: 0 0 0.2rem;"><?= clean_output($video['student_name']) ?></h4>
            <p style="font-size: 0.75rem; color: var(--gray); margin: 0;"><?= clean_output($video['details']) ?></p>
          </div>
        </div>
        <?php endforeach; ?>
        
        <a href="testimonial-videos.php" class="btn btn--ghost btn--sm" style="margin-top: 1rem; justify-content: center; border-color: var(--primary); color: var(--primary);">
          View All Stories <i class="fa-solid fa-arrow-right"></i>
        </a>
      </div>
    </div>
  </div>
</section>
<!-- TEAM MEMBERS SECTION -->
<section id="team" class="section team-section bg-light" style="padding: 5rem 1rem; background: #f8fafc; position: relative;">
  <div class="container">
    <div class="section__header animate-on-scroll" style="text-align: center; margin-bottom: 3.5rem;">
      <span class="section__tag">Our Leadership</span>
      <h2 class="section__title">Meet Our <span>Team</span></h2>
      <p class="section__subtitle" style="margin: 0.5rem auto 0; max-width: 600px;">The experienced professionals dedicated to making your global education dreams a reality.</p>
      <div class="accent-bar" style="margin: 1rem auto 0;"></div>
    </div>
    
    <div style="position: relative; padding: 0 40px;">
      <!-- Navigation Buttons -->
      <button id="teamPrev" class="team-nav-btn" style="position: absolute; left: 0; top: 50%; transform: translateY(-50%); z-index: 10; background: white; border: 1px solid var(--border); width: 45px; height: 45px; border-radius: 50%; box-shadow: 0 4px 10px rgba(0,0,0,0.1); cursor: pointer; display: flex; align-items: center; justify-content: center; color: var(--primary); font-size: 1.2rem; transition: all 0.3s ease;">
        <i class="fa-solid fa-chevron-left"></i>
      </button>
      <button id="teamNext" class="team-nav-btn" style="position: absolute; right: 0; top: 50%; transform: translateY(-50%); z-index: 10; background: white; border: 1px solid var(--border); width: 45px; height: 45px; border-radius: 50%; box-shadow: 0 4px 10px rgba(0,0,0,0.1); cursor: pointer; display: flex; align-items: center; justify-content: center; color: var(--primary); font-size: 1.2rem; transition: all 0.3s ease;">
        <i class="fa-solid fa-chevron-right"></i>
      </button>

      <!-- Slider Container -->
      <div id="teamSlider" class="team-slider" style="display: flex; gap: 2rem; overflow-x: auto; scroll-snap-type: x mandatory; scroll-behavior: smooth; padding: 2rem 5px 3rem; scrollbar-width: none; -ms-overflow-style: none; align-items: flex-end;">
        <style>
          .team-slider::-webkit-scrollbar { display: none; }
          .team-nav-btn:hover { background: var(--primary); color: white !important; }

          .wave-card {
            min-width: 300px;
            max-width: 320px;
            flex: 0 0 auto;
            scroll-snap-align: center;
            background: #0f172a;
            border-radius: 20px;
            position: relative;
            box-shadow: 0 10px 30px rgba(0,0,0,0.08);
            overflow: hidden;
            display: flex;
            flex-direction: column;
            justify-content: flex-start;
            transition: transform 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275), box-shadow 0.4s ease;
            height: 420px;
          }

          .wave-card:hover {
            transform: translateY(-15px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.25);
          }

          .wave-card__full-img {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            z-index: 0;
            transition: transform 0.5s ease;
          }

          .wave-card:hover .wave-card__full-img {
            transform: scale(1.08);
          }

          .wave-card__overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            /* Darken the bottom so the name/role is readable */
            background: linear-gradient(to top, rgba(0,0,0,0.9) 0%, rgba(0,0,0,0.4) 30%, rgba(0,0,0,0) 60%);
            z-index: 1;
            pointer-events: none;
          }

          .wave-card__bottom {
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 260px; 
            transform: translateY(100%);
            transition: transform 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            z-index: 3;
            display: flex;
            flex-direction: column;
            justify-content: flex-end;
          }

          .wave-card:hover .wave-card__bottom {
            transform: translateY(0);
          }

          .wave-card__info {
            position: absolute;
            top: -110px; /* Sits above the wave */
            left: 0;
            width: 100%;
            text-align: center;
            z-index: 5;
            padding: 0 1rem;
          }

          .wave-card__title {
            font-size: 1.6rem;
            font-weight: 800;
            color: #ffffff;
            margin: 0;
            letter-spacing: -0.5px;
            text-shadow: 0 2px 5px rgba(0,0,0,0.8);
          }

          .wave-card__role-top {
            font-size: 0.95rem;
            font-weight: 600;
            color: #e2e8f0;
            margin: 0.25rem 0 0 0;
            text-shadow: 0 2px 4px rgba(0,0,0,0.8);
          }

          .wave-svg {
            position: absolute;
            top: -45px;
            left: 0;
            width: 100%;
            height: 50px;
            display: block;
            pointer-events: none;
          }

          .wave-card__content {
            position: relative;
            padding: 0 1.5rem 1.5rem;
            color: white;
            text-align: center;
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: flex-start;
            align-items: center;
            overflow-y: auto;
            scrollbar-width: none; /* Firefox */
          }
          
          .wave-card__content::-webkit-scrollbar {
            display: none; /* Chrome/Safari */
          }

          .wave-card__desc {
            font-size: 0.95rem;
            line-height: 1.6;
            margin-bottom: 0;
            padding-top: 1rem;
          }

          /* Gradients for cards */
          .bg-grad-0 { background: linear-gradient(135deg, #a78bfa 0%, #c084fc 100%); }
          .bg-grad-0 .wave-svg { color: #a78bfa; }
          
          .bg-grad-1 { background: linear-gradient(135deg, #fb923c 0%, #f97316 100%); }
          .bg-grad-1 .wave-svg { color: #fb923c; }
          
          .bg-grad-2 { background: linear-gradient(135deg, #2dd4bf 0%, #14b8a6 100%); }
          .bg-grad-2 .wave-svg { color: #2dd4bf; }
          
          .bg-grad-3 { background: linear-gradient(135deg, #60a5fa 0%, #3b82f6 100%); }
          .bg-grad-3 .wave-svg { color: #60a5fa; }

          @media(max-width: 768px) { .wave-card { min-width: 280px; } }
        </style>

        <?php
        try {
            $stmt = $pdo->query("SELECT * FROM team_members WHERE is_active = 1 ORDER BY display_order ASC, id ASC");
            $team_members = $stmt->fetchAll();
        } catch (PDOException $e) {
            $team_members = [];
        }
        
        foreach($team_members as $index => $member):
            $gradClass = "bg-grad-" . ($index % 4);
        ?>
        <div class="wave-card animate-on-scroll" style="animation-delay: <?= $index * 100 ?>ms;">
          
          <img src="<?= clean_output($member['image_path']) ?>" alt="<?= clean_output($member['name']) ?>" class="wave-card__full-img">
          <div class="wave-card__overlay"></div>

          <div class="wave-card__bottom <?= $gradClass ?>">
            <!-- Name and Role pinned above the wave -->
            <div class="wave-card__info">
              <h3 class="wave-card__title"><?= clean_output($member['name']) ?></h3>
              <p class="wave-card__role-top"><?= clean_output($member['role']) ?></p>
            </div>

            <!-- SVG Wave shape -->
            <svg class="wave-svg" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320" preserveAspectRatio="none">
              <path fill="currentColor" fill-opacity="1" d="M0,128L48,144C96,160,192,192,288,181.3C384,171,480,117,576,117.3C672,117,768,171,864,192C960,213,1056,203,1152,176C1248,149,1344,107,1392,85.3L1440,64L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"></path>
            </svg>
            
            <div class="wave-card__content">
              <p class="wave-card__desc"><?= nl2br(clean_output($member['description'])) ?></p>
            </div>
          </div>
          
        </div>
        <?php endforeach; ?>
      </div>
    </div>
  </div>
</section>

<script>
  document.addEventListener("DOMContentLoaded", function() {
    const teamSlider = document.getElementById('teamSlider');
    const teamPrev = document.getElementById('teamPrev');
    const teamNext = document.getElementById('teamNext');

    if(teamSlider && teamPrev && teamNext) {
      teamPrev.addEventListener('click', () => {
        const cardWidth = teamSlider.querySelector('.wave-card').offsetWidth + 32; // width + gap
        teamSlider.scrollBy({ left: -cardWidth, behavior: 'smooth' });
      });

      teamNext.addEventListener('click', () => {
        const cardWidth = teamSlider.querySelector('.wave-card').offsetWidth + 32; // width + gap
        teamSlider.scrollBy({ left: cardWidth, behavior: 'smooth' });
      });
    }
  });
</script>

<!-- CTA BANNER -->
<section class="cta-banner-wrapper" style="padding: 4rem 1rem;">
  <div class="container cta-banner animate-on-scroll">
    <div class="cta-banner__left">
      <h2>Ready to Begin Your Global Education Journey?</h2>
      <p>Join 5,000+ students who transformed their future with Bluestone Overseas Consultants.<br>Book your FREE consultation today &mdash; no commitment required!</p>
      
      <div class="cta-buttons">
        <a href="consultation.php" class="btn btn--cyan"><i class="fa-solid fa-graduation-cap"></i> Book Free Consultation</a>
        <a href="tel:+919342899904" class="btn btn--orange"><i class="fa-solid fa-phone"></i> Call +91 93428 99904</a>
      </div>

      <div class="cta-tags">
        <span class="cta-tag"><i class="fa-solid fa-fire" style="color: #fbbf24;"></i> Trending</span>
        <span class="cta-tag">Data Science</span>
        <span class="cta-tag">MBA</span>
        <span class="cta-tag">Computer Science</span>
        <span class="cta-tag">Nursing</span>
      </div>
    </div>
    <div class="cta-banner__right">
      <div class="cta-image-circle">
        <img src="assets/images/cont.png" alt="Happy Student">
      </div>
    </div>
  </div>
</section>



<?php
// Fetch active popups
$popups = [];
if (isset($pdo)) {
    try {
        $stmt = $pdo->query("SELECT * FROM site_popup WHERE is_active = 1 ORDER BY id DESC");
        $popups = $stmt->fetchAll();
    } catch (PDOException $e) {
        $popups = [];
    }
}
$popupCount = count($popups);
?>

<?php if ($popupCount > 0): ?>
<div id="sitePopupModal" class="site-popup-overlay">
    <!-- Reduced container size to 400px for smaller posts -->
    <div class="site-popup-container">
        <button id="sitePopupClose" class="site-popup-close">&times;</button>
        
        <?php if ($popupCount === 1): 
            $popup = $popups[0];
            $imgPath = $popup['image_path'];
            if (strpos($imgPath, 'assets/') !== 0) $imgPath = 'assets/images/uploads/' . ltrim($imgPath, '/');
        ?>
            <div class="single-popup-card">
                <?php if (!empty($popup['link_url'])): ?>
                    <a href="<?php echo htmlspecialchars($popup['link_url']); ?>" target="_blank">
                        <img src="<?php echo htmlspecialchars(BASE_URL . $imgPath); ?>" alt="Social Media Business Post">
                    </a>
                <?php else: ?>
                    <img src="<?php echo htmlspecialchars(BASE_URL . $imgPath); ?>" alt="Social Media Business Post">
                <?php endif; ?>
            </div>
            
        <?php else: ?>
            <div class="stack-slider-container">
                <?php foreach ($popups as $index => $popup): 
                    $imgPath = $popup['image_path'];
                    if (strpos($imgPath, 'assets/') !== 0) $imgPath = 'assets/images/uploads/' . ltrim($imgPath, '/');
                ?>
                    <div class="stack-card" data-index="<?= $index ?>">
                        <?php if (!empty($popup['link_url'])): ?>
                            <a href="<?php echo htmlspecialchars($popup['link_url']); ?>" target="_blank">
                                <img src="<?php echo htmlspecialchars(BASE_URL . $imgPath); ?>" alt="Social Media Business Post">
                            </a>
                        <?php else: ?>
                            <img src="<?php echo htmlspecialchars(BASE_URL . $imgPath); ?>" alt="Social Media Business Post">
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<style>
.site-popup-overlay {
    position: fixed;
    top: 0; left: 0; width: 100%; height: 100%;
    background: rgba(0, 0, 0, 0.7);
    z-index: 99999;
    display: none;
    align-items: center;
    justify-content: center;
    opacity: 0;
    transition: opacity 0.4s ease;
    backdrop-filter: blur(5px);
}
.site-popup-overlay.show {
    display: flex;
    opacity: 1;
}
.site-popup-container {
    position: relative;
    max-width: 540px; /* Slightly larger to allow buffer room */
    width: 85%;
    max-height: 85vh;
    background: transparent;
    /* Removed border-radius and overflow to prevent browser from clipping child layers */
    transform: scale(0.9);
    transition: transform 0.4s ease;
    margin: 20px;
}
.site-popup-overlay.show .site-popup-container {
    transform: scale(1);
}

.single-popup-card {
    background: #fff;
    border: 8px solid #fff;
    border-radius: 12px;
    box-shadow: 0 15px 35px rgba(0,0,0,0.3);
    overflow: hidden;
}
.single-popup-card img {
    width: 100%;
    max-height: 80vh;
    display: block;
    object-fit: contain;
}

.stack-slider-container {
    position: relative;
    width: 100%;
    aspect-ratio: 4/5;
    max-height: 80vh;
    margin: 0 auto;
    perspective: 1200px;
    transform-style: preserve-3d;
    overflow: visible !important;
}
.stack-card {
    position: absolute;
    top: 5%; left: 5%;
    width: 90%; height: 90%;
    border-radius: 12px;
    background: #fff;
    box-shadow: 0 15px 35px rgba(0,0,0,0.25);
    transition: transform 0.6s cubic-bezier(0.4, 0.0, 0.2, 1), opacity 0.6s ease;
    border: 8px solid #fff;
    overflow: hidden;
    transform-origin: center center;
}
.stack-card a, .single-popup-card a {
    display: block;
    width: 100%;
    height: 100%;
}
.stack-card img {
    width: 100%; height: 100%;
    object-fit: contain;
    background: #f8fafc;
}

.site-popup-close {
    position: absolute;
    top: -15px;
    right: -15px;
    background: #ef4444;
    color: white;
    border: none;
    border-radius: 50%;
    width: 36px;
    height: 36px;
    font-size: 22px;
    cursor: pointer;
    box-shadow: 0 4px 10px rgba(0,0,0,0.2);
    z-index: 999;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: background 0.3s, transform 0.3s;
}
.site-popup-close:hover {
    background: #dc2626;
    transform: scale(1.1);
}
</style>
<?php endif; ?>

<script>
// ── Popup Modal ──
<?php if ($popupCount > 0): ?>
document.addEventListener('DOMContentLoaded', function() {
    setTimeout(function() {
        var popup = document.getElementById('sitePopupModal');
        if (!popup) return;
        popup.classList.add('show');

        var stackCards = document.querySelectorAll('.stack-card');
        if (stackCards.length > 0) {
            var currentIndex = 0;
            var totalCards = stackCards.length;
            function updateStack() {
                stackCards.forEach(function(card, i) {
                    var offset = i - currentIndex;
                    if (offset < 0) offset += totalCards;
                    if (offset === 0) {
                        card.style.transform = 'translateZ(0) rotate(0deg) scale(1)';
                        card.style.zIndex = 100; card.style.opacity = 1;
                    } else if (offset === 1) {
                        card.style.transform = 'translateZ(-50px) translateX(15px) rotate(10deg) scale(0.95)';
                        card.style.zIndex = 90; card.style.opacity = 0.9;
                    } else if (offset === 2) {
                        card.style.transform = 'translateZ(-100px) translateX(-15px) rotate(-15deg) scale(0.9)';
                        card.style.zIndex = 80; card.style.opacity = 0.8;
                    } else {
                        card.style.transform = 'translateZ(-150px) scale(0.8)';
                        card.style.zIndex = 10; card.style.opacity = 0;
                    }
                });
            }
            setTimeout(updateStack, 50);
            setInterval(function() { currentIndex = (currentIndex + 1) % totalCards; updateStack(); }, 3000);
        }
    }, 3000);

    var closeBtn = document.getElementById('sitePopupClose');
    if (closeBtn) {
        closeBtn.addEventListener('click', function() {
            var modal = document.getElementById('sitePopupModal');
            if (modal) {
                modal.classList.remove('show');
                setTimeout(function() { modal.style.display = 'none'; }, 400);
            }
        });
    }
});
<?php endif; ?>
</script>
</main>
<?php require_once 'includes/footer.php'; ?>

