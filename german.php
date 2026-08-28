<?php
require_once 'includes/config.php';
$pageTitle = 'German Language Course in Coimbatore | German Language Training';
$pageDesc = 'Join a German language course with expert German language classes in Coimbatore, offering practical lessons, structured training and guidance for learners.';
$pageKeywords = 'UK Education Consultants in Coimbatore, Australia Education Consultants in Coimbatore, New Zealand Education Consultants in Coimbatore, UG Programs Abroad, PG Programs Abroad, Study Abroad Consultants in Coimbatore, IELTS Coaching in Coimbatore, IELTS classes in Coimbatore, Best IELTS Coaching in Coimbatore, IELTS Training in Coimbatore, German language course, Japanese language course, German language classes, Japanese language classes, German Language Course in Coimbatore, Japanese Language Course in Coimbatore, German Language Training Centre in Coimbatore, Japanese Language Training Centre in Coimbatore, Postgraduate study in UK, Postgraduate study in Australia, Postgraduate study in New Zealand, Undergraduate study in Australia, Undergraduate study in UK, Undergraduate study in New Zealand, Postgraduate Study in UK – Coimbatore, Postgraduate Study in Australia – Coimbatore, Undergraduate Study in UK – Coimbatore, Undergraduate Study in Australia – Coimbatore, Postgraduate Study in New Zealand – Coimbatore, Undergraduate Study in New Zealand – Coimbatore';
$pageHeroImage = 'assets/images/german.png';
$hideDefaultHero = true;
require_once 'includes/header.php';
?>

<style>
/* German Premium Theme Variables (Purple) */
:root {
  --pte-primary: #8b5cf6; /* violet-500 */
  --pte-light: #f5f3ff; /* violet-50 */
  --pte-gradient: linear-gradient(135deg, #a78bfa, #7c3aed);
  --dark: #0f172a;
  --gray: #475569;
}

/* Base Styles specific to German page */
.pte-text-gradient {
  background: var(--pte-gradient);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
}

/* 1. WAVE HERO SECTION */
.pte-hero {
  position: relative;
  padding: 8rem 0 12rem;
  background: #ffffff;
  overflow: hidden;
  border-bottom: none;
}
.pte-hero::before {
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
.pte-hero::after {
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
.pte-hero .container { position: relative; z-index: 2; }
.pte-hero-tag {
  display: inline-block;
  padding: 0.5rem 1.5rem;
  background: white;
  color: var(--pte-primary);
  border-radius: 50px;
  font-weight: 700;
  font-size: 0.9rem;
  margin-bottom: 1.5rem;
  box-shadow: 0 10px 20px rgba(139, 92, 246, 0.1);
  border: 1px solid rgba(139, 92, 246, 0.1);
  letter-spacing: 1px;
  text-transform: uppercase;
}
.pte-hero-title {
  font-size: clamp(2.5rem, 5vw, 4.5rem);
  font-weight: 800;
  color: var(--dark);
  line-height: 1.1;
  margin-bottom: 1.5rem;
  font-family: 'Plus Jakarta Sans', sans-serif;
}
.pte-hero-subtitle {
  font-size: 1.15rem;
  color: var(--gray);
  line-height: 1.8;
  max-width: 600px;
  margin-bottom: 2.5rem;
}
.pte-hero-stats {
  display: flex;
  gap: 3rem;
  margin-top: 3rem;
  padding-top: 2rem;
  border-top: 1px solid rgba(0,0,0,0.05);
}
.pte-stat-item h4 {
  font-size: 2rem;
  font-weight: 800;
  color: var(--dark);
  margin-bottom: 0.2rem;
}
.pte-stat-item p { color: var(--gray); font-size: 0.9rem; font-weight: 500; }

/* 2. WAVE CARDS SECTION (Why Choose Us) */
.pte-wave-feature-card {
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
.pte-wave-feature-card:hover {
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
.pte-wave-feature-card:hover .twf-icon-wrap { transform: scale(1.1) rotate(-5deg); }
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
.pte-wave-feature-card:hover .twf-bottom { transform: translateY(0); }
.pte-wave-feature-card:hover .twf-title,
.pte-wave-feature-card:hover .twf-desc { color: white; }

/* 3. ELITE HORIZONTAL CARDS (Curriculum) */
.pte-elite-card {
  display: flex;
  background: white;
  border-radius: 30px;
  overflow: hidden;
  box-shadow: 0 20px 50px rgba(0,0,0,0.05);
  margin-bottom: 3rem;
  transition: all 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275);
  border: 1px solid rgba(0,0,0,0.02);
}
.pte-elite-card:hover {
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
.pte-elite-card:hover .tec-image img { transform: scale(1.1); }
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
.pte-elite-card:nth-child(even) { flex-direction: row-reverse; }

/* 4. SPLIT LAYOUT (Target Audience) */
.pte-split-card {
  background: white; border-radius: 20px; padding: 2.5rem; display: flex; gap: 2rem; box-shadow: 0 10px 30px rgba(0,0,0,0.04); border: 1px solid rgba(0,0,0,0.03); transition: all 0.3s ease;
}
.pte-split-card:hover {
  transform: translateX(10px);
  box-shadow: 0 20px 40px rgba(0,0,0,0.08);
  border-color: rgba(139, 92, 246, 0.2);
}
@media (max-width: 992px) {
  .pte-elite-card, .pte-elite-card:nth-child(even) { flex-direction: column; }
  .tec-content { padding: 2.5rem; }
  .audience-left { position: static !important; margin-bottom: 3rem; }
  .pte-split-card { flex-direction: column; gap: 1.5rem; }
  .pte-hero-title { font-size: 2.5rem; }
  .pte-hero-stats { flex-direction: column; gap: 1.5rem; }
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
.tec-theme-4 { --tec-c1: #f97316; --tec-c2: #ea580c; --tec-bg1: #fff7ed; --tec-bg2: #ffedd5; } /* Orange */
</style>

<main>
  <!-- 1. HERO SECTION -->
  <!-- CUSTOM COUNTRY HERO -->
  <section class="country-hero-custom" style="background-image: url('<?= htmlspecialchars($pageHeroImage) ?>');">
    <!-- Dark overlay to ensure text readability -->
    <div style="position: absolute; inset: 0; background: linear-gradient(to right, rgba(15, 23, 42, 0.9), rgba(15, 23, 42, 0.5));"></div>
    
    <div class="container animate-on-scroll" style="position: relative; z-index: 2; text-align: left; color: white; width: 100%;">
      <div style="max-width: 800px;">
        <span style="display: inline-block; padding: 0.5rem 1.25rem; background: rgba(255,255,255,0.15); backdrop-filter: blur(8px); border-radius: 50px; font-weight: 600; margin-bottom: 1.5rem; border: 1px solid rgba(255,255,255,0.3); text-transform: uppercase; letter-spacing: 0.1em; color: white;"><i class="fa-solid fa-language"></i> Premium Language Training</span>
        <h1 style="font-size: clamp(2.5rem, 5vw, 4rem); font-weight: 800; margin-bottom: 1.5rem; line-height: 1.2; text-shadow: 0 10px 30px rgba(0,0,0,0.5);">Master German in Coimbatore</h1>
        <p class="country-hero-desc" style="font-size: 1.15rem; opacity: 0.9; line-height: 1.7; text-shadow: 0 4px 15px rgba(0,0,0,0.5); border-left: 4px solid var(--pte-primary); padding-left: 1.5rem;">Preparing for the Goethe-Zertifikat is about understanding the test and developing the right language skills. We provide structured coaching for A1 to B2 levels.</p>
        
        <div style="display: flex; gap: 1rem; flex-wrap: wrap; margin-top: 2rem;">
            <a href="consultation.php" class="btn btn--primary btn--lg pulse-btn" style="background: var(--pte-primary); box-shadow: 0 10px 25px rgba(139, 92, 246, 0.4);">Join German Batch</a>
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
            <div class="cf-icon cf-icon--blue"><i class="fa-solid fa-layer-group"></i></div>
            <div>
                <div class="cf-text-label">Levels Covered</div>
                <div class="cf-text-val">A1-B2</div>
            </div>
        </div>

        <div class="country-fact-pill animate-on-scroll delay-1">
            <div class="cf-icon cf-icon--purple"><i class="fa-solid fa-graduation-cap"></i></div>
            <div>
                <div class="cf-text-label">Pass Rate</div>
                <div class="cf-text-val">95%</div>
            </div>
        </div>

        <div class="country-fact-pill animate-on-scroll delay-2">
            <div class="cf-icon cf-icon--orange"><i class="fa-solid fa-certificate"></i></div>
            <div>
                <div class="cf-text-label">Goethe Prepared</div>
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
                   $stmtBatch = $pdo->prepare("SELECT * FROM upcoming_batches WHERE course_slug = 'german' AND is_active = 1 ORDER BY id DESC");
                   $stmtBatch->execute();
                   $batches = $stmtBatch->fetchAll();
                   
                   if (!empty($batches)):
               ?>
               <div class="upcoming-batches-wrapper animate-on-scroll" style="background: var(--pte-gradient); border-radius: 24px; padding: 2.5rem; box-shadow: 0 25px 50px rgba(139, 92, 246, 0.2); margin-top: 1rem;">
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
                                   <span><i class="fa-regular fa-clock" style="color: var(--pte-gradient);"></i> <?php echo clean_output($batch['batch_time']); ?></span>
                                   <?php if(!empty($batch['duration'])): ?><span><i class="fa-solid fa-hourglass-half" style="color: var(--pte-gradient);"></i> <?php echo clean_output($batch['duration']); ?></span><?php endif; ?>
                                   <span><i class="fa-solid fa-laptop-house" style="color: var(--pte-gradient);"></i> <?php echo clean_output($batch['batch_mode']); ?></span>
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
               <input type="hidden" name="destination" value="German Language">
               <div style="margin-bottom: 1rem;">
                 <input type="text" name="first_name" placeholder="Full Name" required style="width: 100%; padding: 1rem 1.2rem; border-radius: 12px; border: 1px solid #e2e8f0; background: #f8fafc; color: var(--dark); font-size: 0.95rem; outline: none; transition: all 0.3s;" onfocus="this.style.borderColor='var(--pte-primary)'; this.style.background='white'; this.style.boxShadow='0 0 0 4px rgba(139, 92, 246, 0.1)';" onblur="this.style.borderColor='#e2e8f0'; this.style.background='#f8fafc'; this.style.boxShadow='none';">
               </div>
               <div style="margin-bottom: 1rem;">
                 <input type="tel" name="phone" placeholder="Phone / WhatsApp Number" required style="width: 100%; padding: 1rem 1.2rem; border-radius: 12px; border: 1px solid #e2e8f0; background: #f8fafc; color: var(--dark); font-size: 0.95rem; outline: none; transition: all 0.3s;" onfocus="this.style.borderColor='var(--pte-primary)'; this.style.background='white'; this.style.boxShadow='0 0 0 4px rgba(139, 92, 246, 0.1)';" onblur="this.style.borderColor='#e2e8f0'; this.style.background='#f8fafc'; this.style.boxShadow='none';">
               </div>
               <div style="margin-bottom: 1.5rem;">
                 <input type="email" name="email" placeholder="Email Address" required style="width: 100%; padding: 1rem 1.2rem; border-radius: 12px; border: 1px solid #e2e8f0; background: #f8fafc; color: var(--dark); font-size: 0.95rem; outline: none; transition: all 0.3s;" onfocus="this.style.borderColor='var(--pte-primary)'; this.style.background='white'; this.style.boxShadow='0 0 0 4px rgba(139, 92, 246, 0.1)';" onblur="this.style.borderColor='#e2e8f0'; this.style.background='#f8fafc'; this.style.boxShadow='none';">
               </div>
               <button type="submit" class="btn btn--primary" style="width: 100%; background: var(--pte-gradient); border: none; padding: 1.1rem; border-radius: 12px; color: white; font-weight: 700; font-size: 1rem; cursor: pointer; transition: transform 0.3s ease, box-shadow 0.3s ease;" onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 10px 20px rgba(139, 92, 246, 0.3)';" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none';">Get Started Now</button>
             </form>
           </div>
       </div>
    </div>
  </section>

  <!-- 2. WHY CHOOSE US (Wave Cards) -->
  <section class="section bg-light">
    <div class="container">
      <div class="text-center animate-on-scroll" style="margin-bottom: 4rem;">
        <span class="section__tag" style="color: var(--pte-primary); background: rgba(139, 92, 246, 0.1);">The Advantage</span>
        <h2 class="section__title">Why Choose <span>Bluestone for German?</span></h2>
        <p class="section__subtitle" style="max-width: 600px; margin: 0 auto;">Our German preparation combines structured lessons, practical exercises, trainer guidance and regular assessment.</p>
      </div>

      <div class="grid grid--3 gap--2">
        <div class="pte-wave-feature-card tw-theme-1 animate-on-scroll">
          <div class="twf-icon-wrap"><i class="fa-solid fa-list-check"></i></div>
          <h3 class="twf-title">Structured Approach</h3>
          <p class="twf-desc">Students follow a planned curriculum covering Hören, Lesen, Schreiben and Sprechen instead of random practice.</p>
          <div class="twf-bottom">
            <svg class="twf-svg" viewBox="0 0 1440 320" preserveAspectRatio="none"><path d="M0,160L48,170.7C96,181,192,203,288,192C384,181,480,139,576,133.3C672,128,768,160,864,181.3C960,203,1056,213,1152,192C1248,171,1344,117,1392,90.7L1440,64L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"></path></svg>
          </div>
        </div>
        <div class="pte-wave-feature-card tw-theme-2 animate-on-scroll delay-1">
          <div class="twf-icon-wrap"><i class="fa-solid fa-bullseye"></i></div>
          <h3 class="twf-title">Individual Improvement</h3>
          <p class="twf-desc">Every learner has different strengths. Our training focuses on identifying areas that need improvement and providing tailored guidance.</p>
          <div class="twf-bottom">
            <svg class="twf-svg" viewBox="0 0 1440 320" preserveAspectRatio="none"><path d="M0,160L48,170.7C96,181,192,203,288,192C384,181,480,139,576,133.3C672,128,768,160,864,181.3C960,203,1056,213,1152,192C1248,171,1344,117,1392,90.7L1440,64L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"></path></svg>
          </div>
        </div>
        <div class="pte-wave-feature-card tw-theme-3 animate-on-scroll delay-2">
          <div class="twf-icon-wrap"><i class="fa-solid fa-laptop-file"></i></div>
          <h3 class="twf-title">Practical Mock Tests</h3>
          <p class="twf-desc">Regular practice helps students become familiar with the Goethe-Zertifikat exam pattern and manage time effectively.</p>
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
        <span class="section__tag" style="color: var(--pte-primary); background: rgba(139, 92, 246, 0.1);">Syllabus</span>
        <h2 class="section__title">Comprehensive <span>Module Breakdown</span></h2>
        <p class="section__subtitle" style="max-width: 600px; margin: 0 auto;">A well-planned Goethe-Zertifikat curriculum helps students develop the language ability required across all four sections.</p>
      </div>

      <div class="pte-elite-wrapper">
        <div class="pte-elite-card tec-theme-1 animate-on-scroll">
          <div class="tec-image">
            <img src="assets/images/ielts_listening_3d.png" alt="Module 1">
          </div>
          <div class="tec-content">
            <div class="tec-icon-wrap"><i class="fa-solid fa-headphones"></i></div>
            <h3 class="tec-title">Hören (Listening)</h3>
            <p style="color: var(--gray); margin-bottom: 1rem;">Practise listening to different types of conversations and announcements to understand spoken German in daily life.</p>
            <ul class="tec-list">
              <li>Listening comprehension</li>
              <li>Vocabulary building</li>
            </ul>
          </div>
        </div>

        <div class="pte-elite-card tec-theme-2 animate-on-scroll">
          <div class="tec-image">
            <img src="assets/images/ielts_reading_3d.png" alt="Module 2">
          </div>
          <div class="tec-content">
            <div class="tec-icon-wrap"><i class="fa-solid fa-book-open-reader"></i></div>
            <h3 class="tec-title">Lesen (Reading)</h3>
            <p style="color: var(--gray); margin-bottom: 1rem;">Focuses on comprehension of texts ranging from short messages to detailed articles.</p>
            <ul class="tec-list">
              <li>Reading strategies</li>
              <li>Grammar application</li>
            </ul>
          </div>
