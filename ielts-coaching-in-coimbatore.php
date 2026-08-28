<?php
require_once 'includes/config.php';
$pageTitle = 'Best IELTS Coaching in Coimbatore | Bluestone Overseas';
$pageDesc = 'IELTS Classes in Coimbatore – personalised support for course selection, university applications, visas and planning your overseas education journey.';
$pageKeywords = 'UK Education Consultants in Coimbatore, Australia Education Consultants in Coimbatore, New Zealand Education Consultants in Coimbatore, UG Programs Abroad, PG Programs Abroad, Study Abroad Consultants in Coimbatore, IELTS Coaching in Coimbatore, IELTS classes in Coimbatore, Best IELTS Coaching in Coimbatore, IELTS Training in Coimbatore, German language course, Japanese language course, German language classes, Japanese language classes, German Language Course in Coimbatore, Japanese Language Course in Coimbatore, German Language Training Centre in Coimbatore, Japanese Language Training Centre in Coimbatore, Postgraduate study in UK, Postgraduate study in Australia, Postgraduate study in New Zealand, Undergraduate study in Australia, Undergraduate study in UK, Undergraduate study in New Zealand, Postgraduate Study in UK – Coimbatore, Postgraduate Study in Australia – Coimbatore, Undergraduate Study in UK – Coimbatore, Undergraduate Study in Australia – Coimbatore, Postgraduate Study in New Zealand – Coimbatore, Undergraduate Study in New Zealand – Coimbatore';
$pageHeroImage = 'assets/images/ielts.png';
$hideDefaultHero = true;
require_once 'includes/header.php';
?>

<style>
/* IELTS Premium Theme Variables */
:root {
  --IELTS-primary: #8b5cf6; /* violet-500 */
  --IELTS-light: #f5f3ff; /* violet-50 */
  --IELTS-gradient: linear-gradient(135deg, #a78bfa, #7c3aed);
  --dark: #0f172a;
  --gray: #475569;
}

/* Base Styles specific to IELTS page */
.IELTS-text-gradient {
  background: var(--IELTS-gradient);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
}

/* 1. WAVE HERO SECTION */
.IELTS-hero {
  position: relative;
  padding: 8rem 0 12rem;
  background: linear-gradient(135deg, #f8fafc 0%, #ffffff 100%);
  overflow: hidden;
  border-bottom: none;
}
.IELTS-hero::before {
  content: '';
  position: absolute;
  top: -20%; right: -10%;
  width: 700px; height: 700px;
  background: linear-gradient(135deg, rgba(139, 92, 246, 0.05), rgba(167, 139, 250, 0.08));
  border-radius: 50%;
  filter: blur(80px);
  z-index: 0;
  animation: float 10s infinite ease-in-out alternate;
}
.IELTS-hero::after {
  content: '';
  position: absolute;
  bottom: -10%; left: -10%;
  width: 500px; height: 500px;
  background: linear-gradient(135deg, rgba(56, 189, 248, 0.05), rgba(139, 92, 246, 0.08));
  border-radius: 50%;
  filter: blur(60px);
  z-index: 0;
  animation: float 8s infinite ease-in-out alternate-reverse;
}
@keyframes float {
  0% { transform: translateY(0) scale(1); }
  100% { transform: translateY(-30px) scale(1.05); }
}
.IELTS-hero .container { position: relative; z-index: 2; }
.IELTS-hero-tag {
  display: inline-block;
  padding: 0.5rem 1.5rem;
  background: white;
  color: var(--IELTS-primary);
  border-radius: 50px;
  font-weight: 700;
  font-size: 0.9rem;
  margin-bottom: 1.5rem;
  box-shadow: 0 10px 20px rgba(139, 92, 246, 0.1);
  border: 1px solid rgba(139, 92, 246, 0.1);
  letter-spacing: 1px;
  text-transform: uppercase;
}
.IELTS-hero-title {
  font-size: clamp(2.5rem, 5vw, 4.5rem);
  font-weight: 800;
  color: var(--dark);
  line-height: 1.1;
  margin-bottom: 1.5rem;
  font-family: 'Plus Jakarta Sans', sans-serif;
}
.IELTS-hero-subtitle {
  font-size: 1.15rem;
  color: var(--gray);
  line-height: 1.8;
  max-width: 600px;
  margin-bottom: 2.5rem;
}
.IELTS-hero-stats {
  display: flex;
  gap: 3rem;
  margin-top: 3rem;
  padding-top: 2rem;
  border-top: 1px solid rgba(0,0,0,0.05);
}
.IELTS-stat-item h4 {
  font-size: 2rem;
  font-weight: 800;
  color: var(--dark);
  margin-bottom: 0.2rem;
}
.IELTS-stat-item p { color: var(--gray); font-size: 0.9rem; font-weight: 500; }

/* 2. WAVE CARDS SECTION (Why Choose Us) */
.IELTS-wave-feature-card {
  background: white;
  border-radius: 24px;
  padding: 3.5rem 2.5rem 2.5rem;
  position: relative;
  overflow: hidden;
  z-index: 1;
  box-shadow: 0 15px 35px rgba(0,0,0,0.04);
  border: 1px solid rgba(0,0,0,0.03);
  transition: all 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275);
  height: 100%;
}
.IELTS-wave-feature-card:hover {
  transform: translateY(-15px);
  box-shadow: 0 30px 60px rgba(0,0,0,0.08);
}
.twf-icon-wrap {
  width: 80px;
  height: 80px;
  border-radius: 20px;
  background: linear-gradient(135deg, var(--w-c1), var(--w-c2));
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 2rem;
  color: white;
  margin-bottom: 2rem;
  box-shadow: 0 15px 30px rgba(0,0,0,0.1);
  position: relative;
  z-index: 2;
  transition: transform 0.4s ease;
}
.IELTS-wave-feature-card:hover .twf-icon-wrap { transform: scale(1.1) rotate(-5deg); }
.twf-title {
  font-size: 1.4rem;
  font-weight: 800;
  color: var(--dark);
  margin-bottom: 1rem;
  font-family: 'Plus Jakarta Sans', sans-serif;
  position: relative;
  z-index: 2;
  transition: color 0.4s ease;
}
.twf-desc {
  color: var(--gray);
  line-height: 1.7;
  position: relative;
  z-index: 2;
  transition: color 0.4s ease;
}
/* Wave effect bottom */
.twf-bottom {
  position: absolute;
  bottom: 0; left: 0; width: 100%; height: 65%;
  background: linear-gradient(180deg, var(--w-c1), var(--w-c2));
  color: var(--w-c1);
  z-index: 1;
  transition: all 0.5s cubic-bezier(0.25, 0.8, 0.25, 1);
  transform: translateY(100%);
}
.twf-svg {
  position: absolute;
  top: -29px; left: 0; width: 100%; height: 30px;
  fill: currentColor;
}
.IELTS-wave-feature-card:hover .twf-bottom { transform: translateY(0); }
.IELTS-wave-feature-card:hover .twf-title,
.IELTS-wave-feature-card:hover .twf-desc { color: white; }

/* 3. ELITE HORIZONTAL CARDS (Curriculum) */
.IELTS-elite-card {
  display: flex;
  background: white;
  border-radius: 30px;
  overflow: hidden;
  box-shadow: 0 20px 50px rgba(0,0,0,0.05);
  margin-bottom: 3rem;
  transition: all 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275);
  border: 1px solid rgba(0,0,0,0.02);
}
.IELTS-elite-card:hover {
  transform: translateY(-10px);
  box-shadow: 0 30px 60px rgba(0,0,0,0.1);
}
.tec-image {
  flex: 0 0 45%;
  position: relative;
  overflow: hidden;
  background: linear-gradient(135deg, var(--tec-bg1), var(--tec-bg2));
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 1.5rem;
}
.tec-image img {
  width: 100%;
  max-width: 250px;
  object-fit: contain;
  transition: transform 0.6s cubic-bezier(0.175, 0.885, 0.32, 1.275);
  filter: drop-shadow(0 20px 30px rgba(0,0,0,0.15));
}
.IELTS-elite-card:hover .tec-image img { transform: scale(1.1); }
.tec-content {
  flex: 1;
  padding: 2rem;
  display: flex;
  flex-direction: column;
  justify-content: center;
}
.tec-icon-wrap {
  width: 70px; height: 70px;
  border-radius: 16px;
  background: linear-gradient(135deg, var(--tec-c1), var(--tec-c2));
  display: flex; align-items: center; justify-content: center;
  font-size: 2rem; color: white;
  margin-bottom: 1.5rem;
  box-shadow: 0 10px 20px rgba(0,0,0,0.1);
}
.tec-title {
  font-size: 2rem;
  font-weight: 800;
  color: var(--dark);
  margin-bottom: 1.5rem;
  font-family: 'Plus Jakarta Sans', sans-serif;
}
.tec-list {
  list-style: none; padding: 0; margin: 0;
  display: grid; gap: 1rem;
}
.tec-list li {
  position: relative;
  padding-left: 2rem;
  color: var(--gray);
  font-size: 1.05rem;
  line-height: 1.6;
}
.tec-list li::before {
  content: '\f00c';
  font-family: 'Font Awesome 6 Free';
  font-weight: 900;
  position: absolute;
  left: 0; top: 2px;
  color: var(--tec-c1);
}
/* Alternate layout */
.IELTS-elite-card:nth-child(even) { flex-direction: row-reverse; }

/* 4. SPLIT LAYOUT (Target Audience) */
.IELTS-split-card {
  background: white; border-radius: 20px; padding: 2.5rem; display: flex; gap: 2rem; box-shadow: 0 10px 30px rgba(0,0,0,0.04); border: 1px solid rgba(0,0,0,0.03); transition: all 0.3s ease;
}
.IELTS-split-card:hover {
  transform: translateX(10px);
  box-shadow: 0 20px 40px rgba(0,0,0,0.08);
  border-color: rgba(139, 92, 246, 0.2);
}
@media (max-width: 992px) {
  .IELTS-elite-card, .IELTS-elite-card:nth-child(even) { flex-direction: column; }
  .tec-content { padding: 2.5rem; }
  .audience-left { position: static !important; margin-bottom: 3rem; }
  .IELTS-split-card { flex-direction: column; gap: 1.5rem; }
  .IELTS-hero-title { font-size: 2.5rem; }
  .IELTS-hero-stats { flex-direction: column; gap: 1.5rem; }
}

/* Theme Colors */
/* Wave Cards */
.tw-theme-1 { --w-c1: #8b5cf6; --w-c2: #7c3aed; } /* Purple */
.tw-theme-2 { --w-c1: #0ea5e9; --w-c2: #0284c7; } /* Sky Blue */
.tw-theme-3 { --w-c1: #10b981; --w-c2: #059669; } /* Emerald */

/* Elite Cards */
.tec-theme-1 { --tec-c1: #8b5cf6; --tec-c2: #7c3aed; --tec-bg1: #f5f3ff; --tec-bg2: #ede9fe; } /* Purple */
.tec-theme-2 { --tec-c1: #0ea5e9; --tec-c2: #0284c7; --tec-bg1: #f0f9ff; --tec-bg2: #e0f2fe; } /* Sky Blue */
.tec-theme-3 { --tec-c1: #10b981; --tec-c2: #059669; --tec-bg1: #f0fdf4; --tec-bg2: #dcfce7; } /* Emerald */

</style>

<main>
  <!-- 1. HERO SECTION -->
  <!-- CUSTOM COUNTRY HERO -->
  <section class="country-hero-custom" style="background-image: url('<?= htmlspecialchars($pageHeroImage) ?>');">
    <!-- Dark overlay to ensure text readability -->
    <div style="position: absolute; inset: 0; background: linear-gradient(to right, rgba(15, 23, 42, 0.9), rgba(15, 23, 42, 0.5));"></div>
    
    <div class="container animate-on-scroll" style="position: relative; z-index: 2; text-align: left; color: white; width: 100%;">
      <div style="max-width: 800px;">
        <span style="display: inline-block; padding: 0.5rem 1.25rem; background: rgba(255,255,255,0.15); backdrop-filter: blur(8px); border-radius: 50px; font-weight: 600; margin-bottom: 1.5rem; border: 1px solid rgba(255,255,255,0.3); text-transform: uppercase; letter-spacing: 0.1em; color: white;"><i class="fa-solid fa-microchip"></i> AI-Scored Excellence</span>
        <h1 style="font-size: clamp(2.5rem, 5vw, 4rem); font-weight: 800; margin-bottom: 1.5rem; line-height: 1.2; text-shadow: 0 10px 30px rgba(0,0,0,0.5);">Crack the IELTS Academic in Record Time</h1>
        <p class="country-hero-desc" style="font-size: 1.15rem; opacity: 0.9; line-height: 1.7; text-shadow: 0 4px 15px rgba(0,0,0,0.5); border-left: 4px solid var(--IELTS-primary); padding-left: 1.5rem;">Fast results, fair scoring, and targeted preparation. In association with Bluestone Language Hub, our highly strategic IELTS coaching decodes the AI algorithm to help you rapidly meet visa and university requirements for Australia, New Zealand, and the UK.</p>
        
        <div style="display: flex; gap: 1rem; flex-wrap: wrap; margin-top: 2rem;">
            <a href="consultation.php" class="btn btn--primary btn--lg pulse-btn" style="background: var(--IELTS-primary); box-shadow: 0 10px 25px rgba(139, 92, 246, 0.4);">Join IELTS Batch</a>
            <a href="#curriculum" class="btn btn--outline btn--lg" style="border-color: rgba(255,255,255,0.3); color: white;">View Syllabus</a>
        </div>
      </div>
    </div>
    
    <!-- Decorative bottom curve matching the site background -->
    <div class="page-hero__curve">
      <svg viewBox="0 0 1440 100" fill="none" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="none">
        <path d="M0,100 C480,0 960,0 1440,100 L1440,100 L0,100 Z" fill="currentColor"/>
      </svg>
    </div>
  </section>

  <style>
  .country-hero-custom {
      position: relative;
      width: 100%;
      min-height: 600px;
      padding-bottom: 120px;
      display: flex;
      align-items: center;
      justify-content: center;
      background-size: cover;
      background-position: center;
      background-repeat: no-repeat;
      padding-top: 100px;
  }
  @media (max-width: 768px) {
      .country-hero-custom {
          height: auto;
          min-height: 400px;
          padding-top: 120px;
          padding-bottom: 60px;
      }
      .country-hero-custom .container {
          text-align: center !important;
      }
      .country-hero-custom h1 {
          font-size: 2.8rem !important;
      }
      .country-hero-desc {
          font-size: 1.05rem !important;
          border-left: none !important;
          padding-left: 0 !important;
      }
  }

  .country-fact-pill {
      display: flex;
      align-items: center;
      gap: 1.25rem;
      background: linear-gradient(rgba(255, 255, 255, 0.75), rgba(255, 255, 255, 0.9));
      backdrop-filter: blur(10px);
      -webkit-backdrop-filter: blur(10px);
      padding: 1.5rem;
      border-radius: 20px;
      box-shadow: 0 10px 30px rgba(0,0,0,0.04);
      border: 1px solid rgba(255,255,255,0.8);
      transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
  }
  .country-fact-pill:hover {
      transform: translateY(-5px) scale(1.02);
      box-shadow: 0 15px 35px rgba(139,92,246,0.15);
  }
  .cf-icon {
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
  .cf-icon--blue { background: linear-gradient(135deg, #0ea5e9, #3b82f6); box-shadow: 0 8px 20px rgba(14,165,233,0.3); }
  .cf-icon--purple { background: linear-gradient(135deg, #8b5cf6, #d946ef); box-shadow: 0 8px 20px rgba(139,92,246,0.3); }
  .cf-icon--orange { background: linear-gradient(135deg, #f97316, #f59e0b); box-shadow: 0 8px 20px rgba(249,115,22,0.3); }
  .cf-text-label { font-size: 0.85rem; color: #64748b; text-transform: uppercase; letter-spacing: 0.05em; font-weight: 600; margin-bottom: 0.25rem; }
  .cf-text-val { font-size: 1.35rem; font-weight: 800; color: #0f172a; line-height: 1.2; }
  </style>

  <!-- QUICK FACTS & ROI (BENTO STYLE) -->
  <section class="section" style="padding-top: 0; margin-top: -50px; position: relative; z-index: 10;">
    <div class="container">
      <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); gap: 1.5rem;">
        
        <div class="country-fact-pill animate-on-scroll">
            <div class="cf-icon cf-icon--blue"><i class="fa-solid fa-chart-line"></i></div>
            <div>
                <div class="cf-text-label">Average Score</div>
                <div class="cf-text-val">75+</div>
            </div>
        </div>

        <div class="country-fact-pill animate-on-scroll delay-1">
            <div class="cf-icon cf-icon--purple"><i class="fa-solid fa-clock"></i></div>
            <div>
                <div class="cf-text-label">Test Results</div>
                <div class="cf-text-val">48 Hrs</div>
            </div>
        </div>

        <div class="country-fact-pill animate-on-scroll delay-2">
            <div class="cf-icon cf-icon--orange"><i class="fa-solid fa-robot"></i></div>
            <div>
                <div class="cf-text-label">AI Mock Scored</div>
                <div class="cf-text-val">100%</div>
            </div>
        </div>

      </div>
    </div>
  </section>

  <!-- LEAD FORM SECTION -->
  <section class="section bg-light" style="padding-top: 3rem;">
    <div class="container">
       <div class="grid grid--2 align-center gap--4">
           <div class="animate-on-scroll">
               <h2 class="section__title" style="text-align: left;">Start Your <span>Preparation</span> Today</h2>
               <p class="section__subtitle" style="text-align: left; margin-bottom: 2rem;">Our language experts will contact you shortly to arrange a free trial class.</p>

               <?php
               try {
                   $stmtBatch = $pdo->prepare("SELECT * FROM upcoming_batches WHERE course_slug = 'ielts' AND is_active = 1 ORDER BY id DESC");
                   $stmtBatch->execute();
                   $batches = $stmtBatch->fetchAll();
                   
                   if (!empty($batches)):
               ?>
               <div class="upcoming-batches-wrapper animate-on-scroll" style="background: var(--IELTS-primary); border-radius: 24px; padding: 2.5rem; box-shadow: 0 25px 50px rgba(220, 38, 38, 0.2); margin-top: 1rem;">
                   <h3 style="font-size: 1.6rem; font-weight: 800; color: white; margin-bottom: 1.5rem; display: flex; align-items: center; gap: 0.75rem; font-family: 'Plus Jakarta Sans', sans-serif;"><i class="fa-solid fa-calendar-check"></i> Upcoming Batches</h3>
                   <div style="display: flex; flex-direction: column; gap: 1rem; max-height: 350px; overflow-y: auto; padding-right: 0.5rem;" class="batch-list-scroll">
                       <?php foreach($batches as $batch): 
                            $statusColor = '#64748b';
                            $s = strtolower($batch['status']);
                            if (strpos($s, 'filling') !== false || strpos($s, 'fast') !== false) $statusColor = '#f59e0b';
                            elseif (strpos($s, 'open') !== false) $statusColor = '#10b981';
                            elseif (strpos($s, 'closed') !== false || strpos($s, 'full') !== false) $statusColor = '#ef4444';
                       ?>
                       <div style="background: white; border-radius: 16px; padding: 1.25rem; display: flex; justify-content: space-between; align-items: center; box-shadow: 0 10px 25px rgba(0,0,0,0.1);">
                           <div>
                               <div style="font-weight: 800; color: var(--dark); font-size: 1.1rem; margin-bottom: 0.4rem;"><?php echo clean_output($batch['start_date']); ?></div>
                               <div style="color: var(--gray); font-size: 0.9rem; display: flex; gap: 1rem; flex-wrap: wrap; font-weight: 500;">
                                   <span><i class="fa-regular fa-clock" style="color: var(--IELTS-primary);"></i> <?php echo clean_output($batch['batch_time']); ?></span>
                                   <?php if(!empty($batch['duration'])): ?><span><i class="fa-solid fa-hourglass-half" style="color: var(--IELTS-primary);"></i> <?php echo clean_output($batch['duration']); ?></span><?php endif; ?>
                                   <span><i class="fa-solid fa-laptop-house" style="color: var(--IELTS-primary);"></i> <?php echo clean_output($batch['batch_mode']); ?></span>
                               </div>
                           </div>
                           <div>
                               <span style="display: inline-block; padding: 0.4rem 1rem; border-radius: 50px; background: <?php echo $statusColor; ?>15; color: <?php echo $statusColor; ?>; font-size: 0.85rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.5px;">
                                   <?php echo clean_output($batch['status']); ?>
                               </span>
                           </div>
                       </div>
                       <?php endforeach; ?>
                   </div>
               </div>
               <?php 
                   endif;
               } catch(PDOException $e) {}
               ?>
           </div>
           <div class="animate-on-scroll delay-1" style="background: white; border-radius: 24px; padding: 2.5rem; box-shadow: 0 25px 50px rgba(0,0,0,0.08); border: 1px solid rgba(0,0,0,0.05);">
             <h3 style="font-size: 1.6rem; font-weight: 800; color: var(--dark); margin-bottom: 2rem; font-family: 'Plus Jakarta Sans', sans-serif;">Book a Free Trial Class</h3>
             <form id="heroLeadForm" onsubmit="return handleFormSubmit(event)">
               <input type="hidden" name="form_type" value="course_enquiry">
               <input type="hidden" name="destination" value="IELTS">
               <div style="margin-bottom: 1rem;">
                 <input type="text" name="first_name" placeholder="Full Name" required style="width: 100%; padding: 1rem 1.2rem; border-radius: 12px; border: 1px solid #e2e8f0; background: #f8fafc; color: var(--dark); font-size: 0.95rem; outline: none; transition: all 0.3s;" onfocus="this.style.borderColor='var(--IELTS-primary)'; this.style.background='white'; this.style.boxShadow='0 0 0 4px rgba(139, 92, 246, 0.1)';" onblur="this.style.borderColor='#e2e8f0'; this.style.background='#f8fafc'; this.style.boxShadow='none';">
               </div>
               <div style="margin-bottom: 1rem;">
                 <input type="tel" name="phone" placeholder="Phone / WhatsApp Number" required style="width: 100%; padding: 1rem 1.2rem; border-radius: 12px; border: 1px solid #e2e8f0; background: #f8fafc; color: var(--dark); font-size: 0.95rem; outline: none; transition: all 0.3s;" onfocus="this.style.borderColor='var(--IELTS-primary)'; this.style.background='white'; this.style.boxShadow='0 0 0 4px rgba(139, 92, 246, 0.1)';" onblur="this.style.borderColor='#e2e8f0'; this.style.background='#f8fafc'; this.style.boxShadow='none';">
               </div>
               <div style="margin-bottom: 1.5rem;">
                 <input type="email" name="email" placeholder="Email Address" required style="width: 100%; padding: 1rem 1.2rem; border-radius: 12px; border: 1px solid #e2e8f0; background: #f8fafc; color: var(--dark); font-size: 0.95rem; outline: none; transition: all 0.3s;" onfocus="this.style.borderColor='var(--IELTS-primary)'; this.style.background='white'; this.style.boxShadow='0 0 0 4px rgba(139, 92, 246, 0.1)';" onblur="this.style.borderColor='#e2e8f0'; this.style.background='#f8fafc'; this.style.boxShadow='none';">
               </div>
               <button type="submit" class="btn btn--primary" style="width: 100%; background: var(--IELTS-gradient); border: none; padding: 1.1rem; border-radius: 12px; color: white; font-weight: 700; font-size: 1rem; cursor: pointer; transition: transform 0.3s ease, box-shadow 0.3s ease;" onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 10px 20px rgba(139, 92, 246, 0.3)';" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none';">Get Started Now</button>
             </form>
           </div>
       </div>
    </div>
  </section>

  <!-- 2. WHY CHOOSE US (Wave Cards) -->
  <section class="section bg-light">
    <div class="container">
      <div class="text-center animate-on-scroll" style="margin-bottom: 4rem;">
        <span class="section__tag" style="color: var(--IELTS-primary); background: rgba(139, 92, 246, 0.1);">The Advantage</span>
        <h2 class="section__title">Why Choose <span>Bluestone for IELTS?</span></h2>
        <p class="section__subtitle" style="max-width: 600px; margin: 0 auto;">Experience the advantage of working with algorithmic experts and state-of-the-art computer testing environments.</p>
      </div>

      <div class="grid grid--3 gap--2">
        <!-- Card 1 -->
        <div class="IELTS-wave-feature-card tw-theme-1 animate-on-scroll">
          <div class="twf-icon-wrap"><i class="fa-solid fa-robot"></i></div>
          <h3 class="twf-title">Algorithm Insights</h3>
          <p class="twf-desc">We teach you exactly what the IELTS Artificial Intelligence is analyzing—from oral fluency pacing to written discourse structure.</p>
          <div class="twf-bottom">
            <svg class="twf-svg" viewBox="0 0 1440 320" preserveAspectRatio="none"><path d="M0,160L48,170.7C96,181,192,203,288,192C384,181,480,139,576,133.3C672,128,768,160,864,181.3C960,203,1056,213,1152,192C1248,171,1344,117,1392,90.7L1440,64L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"></path></svg>
          </div>
        </div>
        <!-- Card 2 -->
        <div class="IELTS-wave-feature-card tw-theme-2 animate-on-scroll delay-1">
          <div class="twf-icon-wrap"><i class="fa-solid fa-microphone-lines"></i></div>
          <h3 class="twf-title">Simulated Audio Labs</h3>
          <p class="twf-desc">Practice speaking in a simulated test center environment with ambient background noise to build bulletproof focus.</p>
          <div class="twf-bottom">
            <svg class="twf-svg" viewBox="0 0 1440 320" preserveAspectRatio="none"><path d="M0,160L48,170.7C96,181,192,203,288,192C384,181,480,139,576,133.3C672,128,768,160,864,181.3C960,203,1056,213,1152,192C1248,171,1344,117,1392,90.7L1440,64L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"></path></svg>
          </div>
        </div>
        <!-- Card 3 -->
        <div class="IELTS-wave-feature-card tw-theme-3 animate-on-scroll delay-2">
          <div class="twf-icon-wrap"><i class="fa-solid fa-bolt"></i></div>
          <h3 class="twf-title">Rapid Results Mastery</h3>
          <p class="twf-desc">Leverage our intensive, fast-tracked coaching modules specifically designed for students facing tight application deadlines.</p>
          <div class="twf-bottom">
            <svg class="twf-svg" viewBox="0 0 1440 320" preserveAspectRatio="none"><path d="M0,160L48,170.7C96,181,192,203,288,192C384,181,480,139,576,133.3C672,128,768,160,864,181.3C960,203,1056,213,1152,192C1248,171,1344,117,1392,90.7L1440,64L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"></path></svg>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- 3. CURRICULUM SECTION (Elite Horizontal Cards) -->
  <section id="curriculum" class="section" style="padding: 6rem 0;">
    <div class="container">
      <div class="text-center animate-on-scroll" style="margin-bottom: 4rem;">
        <span class="section__tag" style="color: var(--IELTS-primary); background: rgba(139, 92, 246, 0.1);">Syllabus</span>
        <h2 class="section__title">Comprehensive <span>Module Breakdown</span></h2>
        <p class="section__subtitle" style="max-width: 600px; margin: 0 auto;">IELTS integrates skills heavily. We decode every single question type.</p>
      </div>

      <div class="IELTS-elite-wrapper">
        <!-- Speaking & Writing -->
        <div class="IELTS-elite-card tec-theme-1 animate-on-scroll">
          <div class="tec-image">
            <img src="assets/images/ielts_speaking_3d.png" alt="Speaking & Writing Module">
          </div>
          <div class="tec-content">
            <div class="tec-icon-wrap"><i class="fa-solid fa-microphone"></i></div>
            <h3 class="tec-title">Speaking & Writing</h3>
            <ul class="tec-list">
              <li>Mastering 'Read Aloud' and 'Repeat Sentence' for maximum fluency scores</li>
              <li>Utilizing flawless templates for 'Describe Image' and 'Retell Lecture'</li>
              <li>Typing strategies and structure for the 'Summarize Written Text' task</li>
              <li>Crafting high-scoring essays formatted exactly for AI evaluation</li>
            </ul>
          </div>
        </div>

        <!-- Reading -->
        <div class="IELTS-elite-card tec-theme-2 animate-on-scroll">
          <div class="tec-image">
            <img src="assets/images/ielts_reading_3d.png" alt="Reading Module">
          </div>
          <div class="tec-content">
            <div class="tec-icon-wrap"><i class="fa-solid fa-tablet-screen-button"></i></div>
            <h3 class="tec-title">Reading Module</h3>
            <ul class="tec-list">
              <li>Collocation mastery for Fill in the Blanks (Reading & Writing)</li>
              <li>Logical sequencing strategies for 'Re-order Paragraphs'</li>
              <li>Skimming techniques for Multiple Choice Questions</li>
              <li>Time management hacks to never leave a question unanswered</li>
            </ul>
          </div>
        </div>

        <!-- Listening -->
        <div class="IELTS-elite-card tec-theme-3 animate-on-scroll">
          <div class="tec-image">
            <img src="assets/images/ielts_listening_3d.png" alt="Listening Module">
          </div>
          <div class="tec-content">
            <div class="tec-icon-wrap"><i class="fa-solid fa-headphones"></i></div>
            <h3 class="tec-title">Listening Module</h3>
            <ul class="tec-list">
              <li>Perfecting 'Write from Dictation' - the highest scoring task in IELTS</li>
              <li>Effective note-taking for 'Summarize Spoken Text'</li>
              <li>Concentration techniques for 'Highlight Incorrect Words'</li>
              <li>Managing audio pacing and handling diverse global accents</li>
            </ul>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- 4. TARGET AUDIENCE (Split Layout) -->
  <section class="section" style="background: linear-gradient(to right, #f8fafc, #ffffff); padding: 8rem 0; overflow: hidden;">
    <div class="container">
      <div class="grid grid--2 gap--4" style="align-items: flex-start;">
        
        <!-- Left Sticky Column -->
        <div class="audience-left animate-on-scroll" style="position: sticky; top: 120px;">
          <span class="section__tag" style="color: var(--IELTS-primary); background: rgba(139, 92, 246, 0.1);">Target Audience</span>
          <h2 class="section__title" style="text-align: left; margin-bottom: 1.5rem;">Who is this <span>training for?</span></h2>
          <p class="section__subtitle" style="text-align: left; max-width: 100%; margin-bottom: 3rem;">Whether you are applying for permanent residency down under or enrolling in a UK university, IELTS offers a fast, fair, and highly flexible testing option.</p>
          
          <div style="position: relative; border-radius: 30px; overflow: hidden; box-shadow: 0 20px 40px rgba(0,0,0,0.08);">
            <div style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: linear-gradient(135deg, rgba(139, 92, 246, 0.1), rgba(14, 165, 233, 0.1)); z-index: 1;"></div>
            <img src="assets/images/ielts_audience_3d.png" alt="Target Audience" style="width: 100%; display: block; position: relative; z-index: 2; transition: transform 0.5s ease;" onmouseover="this.style.transform='scale(1.05)'" onmouseout="this.style.transform='scale(1)'">
          </div>
        </div>
        
        <!-- Right Cards Column -->
        <div class="audience-right animate-on-scroll delay-1" style="display: flex; flex-direction: column; gap: 2rem;">
          
          <!-- Card 1 -->
          <div class="IELTS-split-card" style="background: linear-gradient(135deg, #f5f3ff, #ede9fe); border: 1px solid rgba(139, 92, 246, 0.2); box-shadow: 0 10px 30px rgba(139, 92, 246, 0.05);">
            <div style="width: 60px; height: 60px; border-radius: 16px; background: white; color: var(--IELTS-primary); display: flex; align-items: center; justify-content: center; font-size: 1.5rem; flex-shrink: 0; box-shadow: 0 5px 15px rgba(139, 92, 246, 0.1);">
              <i class="fa-solid fa-passport"></i>
            </div>
            <div>
              <h3 style="font-size: 1.3rem; font-weight: 700; color: var(--dark); margin-bottom: 0.5rem; font-family: 'Plus Jakarta Sans', sans-serif;">PR Applicants (Australia/NZ)</h3>
              <p style="color: var(--gray); line-height: 1.6; font-size: 1rem; margin-bottom: 1rem;">Individuals applying for Permanent Residency in Australia and New Zealand where achieving a IELTS score of 79+ yields maximum migration points.</p>
              <span style="display: inline-block; padding: 0.4rem 1rem; background: white; color: var(--IELTS-primary); font-weight: 600; font-size: 0.85rem; border-radius: 50px; box-shadow: 0 2px 5px rgba(0,0,0,0.05);">Immigration Visas</span>
            </div>
          </div>

          <!-- Card 2 -->
          <div class="IELTS-split-card" style="background: linear-gradient(135deg, #f0f9ff, #e0f2fe); border: 1px solid rgba(14, 165, 233, 0.2); box-shadow: 0 10px 30px rgba(14, 165, 233, 0.05);">
            <div style="width: 60px; height: 60px; border-radius: 16px; background: white; color: #0ea5e9; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; flex-shrink: 0; box-shadow: 0 5px 15px rgba(14, 165, 233, 0.1);">
              <i class="fa-solid fa-user-nurse"></i>
            </div>
            <div>
              <h3 style="font-size: 1.3rem; font-weight: 700; color: var(--dark); margin-bottom: 0.5rem; font-family: 'Plus Jakarta Sans', sans-serif;">Nurses & Healthcare</h3>
              <p style="color: var(--gray); line-height: 1.6; font-size: 1rem; margin-bottom: 1rem;">Nursing professionals pursuing registration with AHPRA in Australia or the NMC in the UK, as IELTS offers a highly objective, unbiased scoring system.</p>
              <span style="display: inline-block; padding: 0.4rem 1rem; background: white; color: #0ea5e9; font-weight: 600; font-size: 0.85rem; border-radius: 50px; box-shadow: 0 2px 5px rgba(0,0,0,0.05);">Professional Registration</span>
            </div>
          </div>

          <!-- Card 3 -->
          <div class="IELTS-split-card" style="background: linear-gradient(135deg, #f0fdf4, #dcfce7); border: 1px solid rgba(16, 185, 129, 0.2); box-shadow: 0 10px 30px rgba(16, 185, 129, 0.05);">
            <div style="width: 60px; height: 60px; border-radius: 16px; background: white; color: #10b981; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; flex-shrink: 0; box-shadow: 0 5px 15px rgba(16, 185, 129, 0.1);">
              <i class="fa-solid fa-building-columns"></i>
            </div>
            <div>
              <h3 style="font-size: 1.3rem; font-weight: 700; color: var(--dark); margin-bottom: 0.5rem; font-family: 'Plus Jakarta Sans', sans-serif;">UK & Global Students</h3>
              <p style="color: var(--gray); line-height: 1.6; font-size: 1rem; margin-bottom: 1rem;">Students requiring fast turnaround times for university admissions in the UK, USA, and Canada, thanks to IELTS's typical 48-hour result delivery.</p>
              <span style="display: inline-block; padding: 0.4rem 1rem; background: white; color: #10b981; font-weight: 600; font-size: 0.85rem; border-radius: 50px; box-shadow: 0 2px 5px rgba(0,0,0,0.05);">Study Abroad</span>
            </div>
          </div>

        </div>
      </div>
    </div>
  </section>

  <!-- 5. CALL TO ACTION -->
  <section class="section" style="padding-top: 2rem;">
    <div class="container animate-on-scroll">
      <div style="background: var(--IELTS-gradient); padding: 4rem 2rem; border-radius: 30px; text-align: center; color: white; box-shadow: 0 20px 40px rgba(139, 92, 246, 0.2);">
        <h2 style="font-size: 2.5rem; margin-bottom: 1rem; font-weight: 800; font-family: 'Plus Jakarta Sans', sans-serif;">Ready to Decode the IELTS Algorithm?</h2>
        <p style="font-size: 1.1rem; opacity: 0.9; max-width: 600px; margin: 0 auto 2rem;">Join the ranks of successful students who have achieved 79+ scores with Bluestone Overseas.</p>
        <a href="consultation.php" class="btn btn--white btn--lg pulse-btn" style="background: white; color: var(--IELTS-primary); font-weight: 700;">Book Free Consultation</a>
      </div>
    </div>
  </section>
</main>

<?php require_once 'includes/footer.php'; ?>


