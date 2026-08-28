<?php
require_once 'includes/config.php';
$pageTitle = 'Japanese Language Course in Coimbatore | Japanese Language Training';
$pageDesc = 'Join a Japanese language course with expert Japanese language classes in Coimbatore, offering practical lessons, structured training and guidance for learners.';
$pageKeywords = 'UK Education Consultants in Coimbatore, Australia Education Consultants in Coimbatore, New Zealand Education Consultants in Coimbatore, UG Programs Abroad, PG Programs Abroad, Study Abroad Consultants in Coimbatore, IELTS Coaching in Coimbatore, IELTS classes in Coimbatore, Best IELTS Coaching in Coimbatore, IELTS Training in Coimbatore, German language course, Japanese language course, German language classes, Japanese language classes, German Language Course in Coimbatore, Japanese Language Course in Coimbatore, German Language Training Centre in Coimbatore, Japanese Language Training Centre in Coimbatore, Postgraduate study in UK, Postgraduate study in Australia, Postgraduate study in New Zealand, Undergraduate study in Australia, Undergraduate study in UK, Undergraduate study in New Zealand, Postgraduate Study in UK – Coimbatore, Postgraduate Study in Australia – Coimbatore, Undergraduate Study in UK – Coimbatore, Undergraduate Study in Australia – Coimbatore, Postgraduate Study in New Zealand – Coimbatore, Undergraduate Study in New Zealand – Coimbatore';
$pageHeroImage = 'assets/images/japan.png';
$hideDefaultHero = true;
require_once 'includes/header.php';
?>

<style>
/* Premium Theme Variables (Purple) */
:root {
  --pte-primary: #8b5cf6;
  --pte-light: #f5f3ff;
  --pte-gradient: linear-gradient(135deg, #a78bfa, #7c3aed);
  --dark: #0f172a;
  --gray: #475569;
}

/* Base Styles specific to Japanese page */
.pte-text-gradient { background: var(--pte-gradient); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }

/* 1. WAVE HERO SECTION */
.pte-hero { position: relative; padding: 8rem 0 8rem; background: #ffffff; overflow: hidden; border-bottom: none; }
.pte-hero::before { content: ''; position: absolute; top: -20%; right: -10%; width: 700px; height: 700px; background: linear-gradient(135deg, rgba(139, 92, 246, 0.05), rgba(167, 139, 250, 0.08)); border-radius: 50%; filter: blur(80px); z-index: 0; animation: float 10s infinite ease-in-out alternate; }
.pte-hero::after { content: ''; position: absolute; bottom: -10%; left: -10%; width: 500px; height: 500px; background: linear-gradient(135deg, rgba(56, 189, 248, 0.05), rgba(139, 92, 246, 0.08)); border-radius: 50%; filter: blur(60px); z-index: 0; animation: float 8s infinite ease-in-out alternate-reverse; }
@keyframes float { 0% { transform: translateY(0) scale(1); } 100% { transform: translateY(-30px) scale(1.05); } }
.pte-hero .container { position: relative; z-index: 2; }
.pte-hero-tag { display: inline-block; padding: 0.5rem 1.5rem; background: white; color: var(--pte-primary); border-radius: 50px; font-weight: 700; font-size: 0.9rem; margin-bottom: 1.5rem; box-shadow: 0 10px 20px rgba(139, 92, 246, 0.1); border: 1px solid rgba(139, 92, 246, 0.1); letter-spacing: 1px; text-transform: uppercase; }
.pte-hero-title { font-size: clamp(2.5rem, 3vw, 4.5rem); font-weight: 800; color: var(--dark); line-height: 1.1; margin-bottom: 1.5rem; font-family: 'Plus Jakarta Sans', sans-serif; }
.pte-hero-subtitle { font-size: 1.15rem; color: var(--gray); line-height: 1.8; max-width: 600px; margin-bottom: 2.5rem; }

/* 2. WAVE CARDS SECTION (Why Choose Us) */
.pte-wave-feature-card { background: white; border-radius: 24px; padding: 3.5rem 2.5rem 2.5rem; position: relative; overflow: hidden; z-index: 1; box-shadow: 0 15px 35px rgba(0,0,0,0.04); border: 1px solid rgba(0,0,0,0.03); transition: all 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275); height: 100%; }
.pte-wave-feature-card::after { content: attr(data-letter); position: absolute; bottom: -20px; right: -10px; font-size: 12rem; font-weight: 900; color: var(--w-c1); opacity: 0.08; z-index: 0; pointer-events: none; transition: all 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275); line-height: 1; font-family: "Noto Sans JP", "Yu Gothic", "Meiryo", sans-serif; }
.pte-wave-feature-card:hover { transform: translateY(-15px); box-shadow: 0 30px 60px rgba(0,0,0,0.08); }
.pte-wave-feature-card:hover::after { opacity: 0.15; transform: scale(1.1) rotate(-5deg); }
.twf-icon-wrap { width: 80px; height: 80px; border-radius: 20px; background: linear-gradient(135deg, var(--w-c1), var(--w-c2)); display: flex; align-items: center; justify-content: center; font-size: 2rem; color: white; margin-bottom: 2rem; box-shadow: 0 15px 30px rgba(0,0,0,0.1); position: relative; z-index: 2; transition: transform 0.4s ease; }
.pte-wave-feature-card:hover .twf-icon-wrap { transform: scale(1.1) rotate(-5deg); }
.twf-title { font-size: 1.4rem; font-weight: 800; color: var(--dark); margin-bottom: 1rem; font-family: 'Plus Jakarta Sans', sans-serif; position: relative; z-index: 2; transition: color 0.4s ease; }
.twf-desc { color: var(--gray); line-height: 1.7; position: relative; z-index: 2; transition: color 0.4s ease; }
.twf-bottom { position: absolute; bottom: 0; left: 0; width: 100%; height: 65%; background: linear-gradient(180deg, var(--w-c1), var(--w-c2)); color: var(--w-c1); z-index: 1; transition: all 0.5s cubic-bezier(0.25, 0.8, 0.25, 1); transform: translateY(100%); }
.twf-svg { position: absolute; top: -29px; left: 0; width: 100%; height: 30px; fill: currentColor; }
.pte-wave-feature-card:hover .twf-bottom { transform: translateY(0); }
.pte-wave-feature-card:hover .twf-title, .pte-wave-feature-card:hover .twf-desc { color: white; }

/* 3. ELITE HORIZONTAL CARDS (Curriculum) */
.pte-elite-card { display: flex; background: white; border-radius: 30px; overflow: hidden; box-shadow: 0 20px 50px rgba(0,0,0,0.05); margin-bottom: 3rem; transition: all 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275); border: 1px solid rgba(0,0,0,0.02); }
.pte-elite-card:hover { transform: translateY(-10px); box-shadow: 0 30px 60px rgba(0,0,0,0.1); }
.tec-image { flex: 0 0 35%; position: relative; overflow: hidden; background: linear-gradient(135deg, var(--tec-bg1), var(--tec-bg2)); display: flex; align-items: center; justify-content: center; padding: 2rem; color: var(--tec-c1); font-size: 5rem;}
.tec-content { flex: 1; padding: 2.5rem; display: flex; flex-direction: column; justify-content: center; }
.tec-title { font-size: 1.8rem; font-weight: 800; color: var(--dark); margin-bottom: 0.5rem; font-family: 'Plus Jakarta Sans', sans-serif; }
.tec-subtitle { font-size: 1.1rem; color: var(--pte-primary); font-weight: 600; margin-bottom: 1.5rem; font-style: italic; }
.tec-list { list-style: none; padding: 0; margin: 0; display: grid; gap: 1rem; }
.tec-list li { position: relative; padding-left: 2rem; color: var(--gray); font-size: 1.05rem; line-height: 1.6; }
.tec-list li::before { content: '\f00c'; font-family: 'Font Awesome 6 Free'; font-weight: 900; position: absolute; left: 0; top: 2px; color: var(--tec-c1); }
.pte-elite-card:nth-child(even) { flex-direction: row-reverse; }

/* 4. SPLIT LAYOUT (Target Audience & Features) */
.pte-split-card { background: white; border-radius: 20px; padding: 2.5rem; display: flex; gap: 1.5rem; box-shadow: 0 10px 30px rgba(0,0,0,0.04); border: 1px solid rgba(0,0,0,0.03); transition: all 0.3s ease; height: 100%;}
.pte-split-card:hover { transform: translateY(-5px); box-shadow: 0 20px 40px rgba(139, 92, 246, 0.1); border-color: rgba(139, 92, 246, 0.2); }
@media (max-width: 992px) {
  .pte-elite-card, .pte-elite-card:nth-child(even) { flex-direction: column; }
  .tec-content { padding: 2rem; }
}
.pte-hero-stats { display: flex; gap: 3rem; margin-top: 3rem; padding: 2rem; background: white; border-radius: 16px; box-shadow: 0 15px 35px rgba(0,0,0,0.05); border: 1px solid rgba(0,0,0,0.03); }
@media (max-width: 768px) {
  .pte-hero { padding: 7rem 0 4rem; }
  .pte-hero-title { font-size: 2.2rem; }
  .pte-wave-feature-card { padding: 2rem 1.5rem; }
  .pte-split-card { flex-direction: column; padding: 1.5rem; }
  .pte-hero-stats { flex-direction: column; gap: 1.5rem; margin-top: 2rem; }
}

/* Data Table */
.modern-table { width: 100%; border-collapse: separate; border-spacing: 0; border-radius: 20px; overflow: hidden; box-shadow: 0 15px 35px rgba(0,0,0,0.05); }
.modern-table th { background: var(--pte-gradient); color: white; padding: 1.5rem; text-align: left; font-size: 1.1rem; font-weight: 700; }
.modern-table td { padding: 1.5rem; border-bottom: 1px solid rgba(0,0,0,0.05); background: white; color: var(--gray); font-size: 1.05rem; line-height: 1.6; vertical-align: top;}
.modern-table tr:last-child td { border-bottom: none; }
.modern-table tr:nth-child(even) td { background: #f8fafc; }

/* FAQ Accordion */
.faq-item { background: white; border-radius: 16px; margin-bottom: 1rem; box-shadow: 0 5px 15px rgba(0,0,0,0.03); overflow: hidden; }
.faq-question { padding: 1.5rem; cursor: pointer; font-weight: 700; font-size: 1.1rem; display: flex; justify-content: space-between; align-items: center; color: var(--dark); transition: color 0.3s ease; }
.faq-question:hover { color: var(--pte-primary); }
.faq-answer { padding: 0 1.5rem 1.5rem; color: var(--gray); line-height: 1.6; display: none; }
.faq-item.active .faq-answer { display: block; }
.faq-item.active .faq-question { color: var(--pte-primary); }

/* Theme Colors */
.tw-theme-1 { --w-c1: #8b5cf6; --w-c2: #7c3aed; }
.tw-theme-2 { --w-c1: #0ea5e9; --w-c2: #0284c7; }
.tw-theme-3 { --w-c1: #10b981; --w-c2: #059669; }
.tw-theme-4 { --w-c1: #f43f5e; --w-c2: #e11d48; }

.tec-theme-1 { --tec-c1: #8b5cf6; --tec-c2: #7c3aed; --tec-bg1: #f5f3ff; --tec-bg2: #ede9fe; }
.tec-theme-2 { --tec-c1: #0ea5e9; --tec-c2: #0284c7; --tec-bg1: #f0f9ff; --tec-bg2: #e0f2fe; }
.tec-theme-3 { --tec-c1: #f43f5e; --tec-c2: #e11d48; --tec-bg1: #fff1f2; --tec-bg2: #ffe4e6; }
.tec-theme-4 { --tec-c1: #f97316; --tec-c2: #ea580c; --tec-bg1: #fff7ed; --tec-bg2: #ffedd5; }
.tec-theme-5 { --tec-c1: #10b981; --tec-c2: #059669; --tec-bg1: #f0fdf4; --tec-bg2: #dcfce7; }
.tec-theme-6 { --tec-c1: #6366f1; --tec-c2: #4f46e5; --tec-bg1: #eef2ff; --tec-bg2: #e0e7ff; }
</style>

<main>


  <!-- 1. HERO SECTION -->
  <!-- CUSTOM COUNTRY HERO -->
  <section class="country-hero-custom" style="background-image: url('<?= htmlspecialchars($pageHeroImage) ?>');">
    <!-- Dark overlay to ensure text readability -->
    <div style="position: absolute; inset: 0; background: linear-gradient(to right, rgba(15, 23, 42, 0.9), rgba(15, 23, 42, 0.5));"></div>
    
    <div class="container animate-on-scroll" style="position: relative; z-index: 2; text-align: left; color: white; width: 100%;">
      <div style="max-width: 800px;">
        <span style="display: inline-block; padding: 0.5rem 1.25rem; background: rgba(255,255,255,0.15); backdrop-filter: blur(8px); border-radius: 50px; font-weight: 600; margin-bottom: 1.5rem; border: 1px solid rgba(255,255,255,0.3); text-transform: uppercase; letter-spacing: 0.1em; color: white;"><i class="fa-solid fa-language"></i> Japanese Language & Certification</span>
        <h1 style="font-size: clamp(2.5rem, 5vw, 4rem); font-weight: 800; margin-bottom: 1.5rem; line-height: 1.2; text-shadow: 0 10px 30px rgba(0,0,0,0.5);">Japanese Language Classes in Coimbatore</h1>
        <p class="country-hero-desc" style="font-size: 1.15rem; opacity: 0.9; line-height: 1.7; text-shadow: 0 4px 15px rgba(0,0,0,0.5); border-left: 4px solid var(--pte-primary); padding-left: 1.5rem;">Learning a new language is more than memorising words and grammar. It is about gaining the confidence to communicate, understand a different culture, and open yourself to new possibilities. At Bluestone Language Hub, our Japanese Language Classes in Coimbatore are designed for learners at different stages, from complete beginners to students preparing for the JLPT.</p>
        
        <div style="display: flex; gap: 1rem; flex-wrap: wrap; margin-top: 2rem;">
            <a href="consultation.php" class="btn btn--primary btn--lg pulse-btn" style="background: var(--pte-primary); box-shadow: 0 10px 25px rgba(139, 92, 246, 0.4);">Join Upcoming Batch</a>
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
            <div class="cf-icon cf-icon--blue"><i class="fa-solid fa-users"></i></div>
            <div>
                <div class="cf-text-label">Students Trained</div>
                <div class="cf-text-val">5,000+</div>
            </div>
        </div>

        <div class="country-fact-pill animate-on-scroll delay-1">
            <div class="cf-icon cf-icon--purple"><i class="fa-solid fa-graduation-cap"></i></div>
            <div>
                <div class="cf-text-label">JLPT Pass Rate</div>
                <div class="cf-text-val">95%</div>
            </div>
        </div>

        <div class="country-fact-pill animate-on-scroll delay-2">
            <div class="cf-icon cf-icon--orange"><i class="fa-solid fa-medal"></i></div>
            <div>
                <div class="cf-text-label">Years Excellence</div>
                <div class="cf-text-val">10+</div>
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
                   $stmtBatch = $pdo->prepare("SELECT * FROM upcoming_batches WHERE course_slug = 'japanese' AND is_active = 1 ORDER BY id DESC");
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
               <input type="hidden" name="destination" value="Japanese Language">
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

  <!-- WORK AND STUDY BRIEF SECTIONS -->
  <section class="section" style="padding: 4rem 0; position: relative; background: url('assets/images/sky_clouds_hero_bg.png') center/cover fixed;">
    <div style="position: absolute; top: 0; left: 0; right: 0; bottom: 0; background: rgba(248, 250, 252, 0.85); z-index: 0;"></div>
    <div class="container" style="position: relative; z-index: 1;">
      <div class="grid grid--2 gap--4">
        
        <style>
          .bg-hover-card {
            position: relative;
            border-radius: 24px;
            overflow: hidden;
            min-height: 420px;
            display: flex;
            flex-direction: column;
            justify-content: flex-end;
            padding: 2.5rem;
            box-shadow: 0 15px 35px rgba(0,0,0,0.06);
            cursor: pointer;
          }
          .bg-hover-card .bg-img {
            position: absolute;
            top: 0; left: 0; width: 100%; height: 100%;
            object-fit: cover;
            z-index: 0;
            transition: transform 0.6s cubic-bezier(0.25, 0.46, 0.45, 0.94);
          }
          .bg-hover-card .bg-overlay {
            position: absolute;
            top: 0; left: 0; width: 100%; height: 100%;
            background: linear-gradient(to top, rgba(15, 23, 42, 0.9) 0%, rgba(15, 23, 42, 0.3) 60%, rgba(15, 23, 42, 0) 100%);
            z-index: 1;
            transition: background 0.4s ease;
          }
          .bg-hover-card:hover .bg-img {
            transform: scale(1.08);
          }
          .bg-hover-card:hover .bg-overlay {
            background: linear-gradient(to top, rgba(15, 23, 42, 0.95) 0%, rgba(15, 23, 42, 0.7) 60%, rgba(15, 23, 42, 0.3) 100%);
          }
          .bg-hover-content {
            position: relative;
            z-index: 2;
            color: white;
            transition: transform 0.4s ease;
            transform: translateY(110px); /* Height of the description paragraph + margin */
          }
          .bg-hover-card:hover .bg-hover-content {
            transform: translateY(0);
          }
          .bg-hover-desc {
            opacity: 0;
            height: 0;
            overflow: hidden;
            transition: opacity 0.4s ease, height 0.4s ease, margin 0.4s ease;
            margin-bottom: 0;
          }
          .bg-hover-card:hover .bg-hover-desc {
            opacity: 1;
            height: auto;
            margin-bottom: 1.5rem;
          }
          @media (max-width: 768px) {
            .bg-hover-content { transform: translateY(0); }
            .bg-hover-desc { opacity: 1; height: auto; margin-bottom: 1.5rem; }
            .bg-hover-card { padding: 1.5rem; min-height: 380px; }
          }
        </style>

        <!-- Work Brief -->
        <div class="bg-hover-card animate-on-scroll">
          <img src="assets/images/s1.jpg" class="bg-img" alt="Work in Japan">
          <div class="bg-overlay"></div>
          <div class="bg-hover-content">
            <div style="width: 60px; height: 60px; border-radius: 16px; background: rgba(255,255,255,0.15); backdrop-filter: blur(10px); color: white; display: flex; align-items: center; justify-content: center; font-size: 1.8rem; margin-bottom: 1rem; border: 1px solid rgba(255,255,255,0.2);"><i class="fa-solid fa-briefcase"></i></div>
            <h3 style="font-size: 2rem; font-weight: 800; color: white; margin-bottom: 1rem; font-family: 'Plus Jakarta Sans', sans-serif;">Work in Japan</h3>
            <div class="bg-hover-desc">
              <p style="color: rgba(255,255,255,0.85); line-height: 1.7;">As Japan faces workforce shortages, companies are actively hiring skilled talent across engineering, healthcare, hospitality, and specialized trades. We provide end-to-end language, visa, and placement support.</p>
            </div>
            <a href="work-in-japan.php" class="btn btn--outline" style="border-color: rgba(255,255,255,0.4); color: white; font-weight: 700;">Explore Job Tracks & Visa Details <i class="fa-solid fa-arrow-right" style="margin-left: 8px;"></i></a>
          </div>
        </div>

        <!-- Study Brief -->
        <div class="bg-hover-card animate-on-scroll delay-1">
          <img src="assets/images/s3.jpg" class="bg-img" alt="Study in Japan">
          <div class="bg-overlay"></div>
          <div class="bg-hover-content">
            <div style="width: 60px; height: 60px; border-radius: 16px; background: rgba(255,255,255,0.15); backdrop-filter: blur(10px); color: white; display: flex; align-items: center; justify-content: center; font-size: 1.8rem; margin-bottom: 1rem; border: 1px solid rgba(255,255,255,0.2);"><i class="fa-solid fa-graduation-cap"></i></div>
            <h3 style="font-size: 2rem; font-weight: 800; color: white; margin-bottom: 1rem; font-family: 'Plus Jakarta Sans', sans-serif;">Study in Japan</h3>
            <div class="bg-hover-desc">
              <p style="color: rgba(255,255,255,0.85); line-height: 1.7;">Japan’s top universities are consistently led by the former Imperial Universities alongside prestigious national institutes and private institutions offering world-class research and education.</p>
            </div>
            <a href="universities-in-japan.php" class="btn btn--outline" style="border-color: rgba(255,255,255,0.4); color: white; font-weight: 700;">Explore Top Universities <i class="fa-solid fa-arrow-right" style="margin-left: 8px;"></i></a>
          </div>
        </div>

      </div>
    </div>
  </section>

  <!-- CURRICULUM SECTION -->
  <section id="curriculum" class="section bg-light" style="padding: 6rem 0;">
    <div class="container">
      <div class="text-center animate-on-scroll" style="margin-bottom: 4rem;">
        <span class="section__tag" style="color: var(--pte-primary); background: rgba(139, 92, 246, 0.1);">Course Directory</span>
        <h2 class="section__title">Detailed Course <span>Curriculum</span></h2>
        <p class="section__subtitle" style="max-width: 700px; margin: 0 auto;">All our core courses strictly follow the official Japan Foundation frameworks. Integrated training across Reading, Writing, Listening, and Speaking.</p>
      </div>

            <style>
        .course-tabs-wrapper { display: flex; gap: 2rem; background: white; border-radius: 24px; padding: 2rem; box-shadow: 0 15px 35px rgba(0,0,0,0.05); border: 1px solid rgba(0,0,0,0.03); min-height: 500px; }
        .course-tabs-sidebar { flex: 0 0 300px; display: flex; flex-direction: column; gap: 0.5rem; border-right: 1px solid rgba(0,0,0,0.05); padding-right: 2rem; }
        .course-tab-btn { padding: 1rem 1.5rem; border: none; background: transparent; text-align: left; font-size: 1.1rem; font-weight: 700; color: var(--gray); border-radius: 12px; cursor: pointer; transition: all 0.3s ease; display: flex; justify-content: space-between; align-items: center; }
        .course-tab-btn:hover { background: rgba(139, 92, 246, 0.05); color: var(--pte-primary); }
        
        .course-tab-btn.active { color: white; }
        .course-tab-btn[data-color="purple"].active { background: linear-gradient(135deg, #8b5cf6, #7c3aed); box-shadow: 0 10px 20px rgba(139, 92, 246, 0.2); }
        .course-tab-btn[data-color="blue"].active { background: linear-gradient(135deg, #0ea5e9, #0284c7); box-shadow: 0 10px 20px rgba(14, 165, 233, 0.2); }
        .course-tab-btn[data-color="rose"].active { background: linear-gradient(135deg, #f43f5e, #e11d48); box-shadow: 0 10px 20px rgba(244, 63, 94, 0.2); }
        .course-tab-btn[data-color="orange"].active { background: linear-gradient(135deg, #f97316, #ea580c); box-shadow: 0 10px 20px rgba(249, 115, 22, 0.2); }
        .course-tab-btn[data-color="emerald"].active { background: linear-gradient(135deg, #10b981, #059669); box-shadow: 0 10px 20px rgba(16, 185, 129, 0.2); }
        .course-tab-btn[data-color="indigo"].active { background: linear-gradient(135deg, #6366f1, #4f46e5); box-shadow: 0 10px 20px rgba(99, 102, 241, 0.2); }
        
        .ctp-overview.purple { border-left-color: #8b5cf6; }
        .ctp-overview.blue { border-left-color: #0ea5e9; }
        .ctp-overview.rose { border-left-color: #f43f5e; }
        .ctp-overview.orange { border-left-color: #f97316; }
        .ctp-overview.emerald { border-left-color: #10b981; }
        .ctp-overview.indigo { border-left-color: #6366f1; }
        
        .ctp-list.purple li::before { color: #8b5cf6; }
        .ctp-list.blue li::before { color: #0ea5e9; }
        .ctp-list.rose li::before { color: #f43f5e; }
        .ctp-list.orange li::before { color: #f97316; }
        .ctp-list.emerald li::before { color: #10b981; }
        .ctp-list.indigo li::before { color: #6366f1; }

        .course-tab-content-area { flex: 1; padding: 1rem; position: relative; }
        
        
        
        .course-tab-pane { position: relative; overflow: hidden; display: none; animation: fadeIn 0.4s ease forwards; padding: 2rem; border-radius: 20px; border: 1px solid transparent; z-index: 1; }
        .course-tab-pane::after { content: attr(data-letter); position: absolute; bottom: -20px; right: 0; font-size: 15rem; font-weight: 900; color: rgba(0, 0, 0, 0.04); z-index: 0; pointer-events: none; line-height: 1; font-family: "Noto Sans JP", "Yu Gothic", "Meiryo", sans-serif; transform: rotate(-5deg); transition: transform 0.4s ease; }
        .course-tab-pane:hover::after { transform: scale(1.05) rotate(-5deg); }
        .course-tab-pane > * { position: relative; z-index: 2; }
        .course-tab-pane.active { display: block; }
        
        .course-tab-pane.purple { background-color: #f5f3ff; border-color: rgba(139, 92, 246, 0.2); }
        .course-tab-pane.blue { background-color: #f0f9ff; border-color: rgba(14, 165, 233, 0.2); }
        .course-tab-pane.rose { background-color: #fff1f2; border-color: rgba(244, 63, 94, 0.2); }
        .course-tab-pane.orange { background-color: #fff7ed; border-color: rgba(249, 115, 22, 0.2); }
        .course-tab-pane.emerald { background-color: #f0fdf4; border-color: rgba(16, 185, 129, 0.2); }
        .course-tab-pane.indigo { background-color: #eef2ff; border-color: rgba(99, 102, 241, 0.2); }
        
        .ctp-overview { font-size: 1.1rem; color: var(--gray); line-height: 1.7; margin-bottom: 2rem; padding: 1.5rem; background: white; border-radius: 16px; border-left: 4px solid var(--pte-primary); box-shadow: 0 5px 15px rgba(0,0,0,0.02); }
        .ctp-header { display: flex; align-items: center; gap: 1.5rem; margin-bottom: 2rem; }
        .ctp-icon { width: 70px; height: 70px; border-radius: 16px; display: flex; align-items: center; justify-content: center; font-size: 2rem; color: white; box-shadow: 0 10px 20px rgba(0,0,0,0.1); }
        .ctp-title { font-size: 2rem; font-weight: 800; color: var(--dark); margin-bottom: 0.2rem; font-family: 'Plus Jakarta Sans', sans-serif; }
        .ctp-subtitle { font-size: 1.1rem; color: var(--pte-primary); font-weight: 600; font-style: italic; }
        
        .ctp-list { list-style: none; padding: 0; margin: 0; display: grid; gap: 1rem; }
        .ctp-list li { position: relative; padding-left: 2rem; color: var(--gray); font-size: 1.05rem; line-height: 1.6; }
        .ctp-list li::before { content: '\f00c'; font-family: 'Font Awesome 6 Free'; font-weight: 900; position: absolute; left: 0; top: 2px; color: var(--pte-primary); }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
        @media (max-width: 992px) {
          .course-tabs-wrapper { flex-direction: column; }
          .course-tabs-sidebar { flex: none; border-right: none; border-bottom: 1px solid rgba(0,0,0,0.05); padding-right: 0; padding-bottom: 2rem; }
        }
        @media (max-width: 768px) {
          .course-tabs-wrapper { padding: 1.25rem; }
          .course-tab-pane { padding: 1.5rem; }
          .ctp-header { flex-direction: column; align-items: flex-start; gap: 1rem; }
          .ctp-icon { width: 50px; height: 50px; font-size: 1.5rem; }
        }
      </style>

      <div class="course-tabs-wrapper animate-on-scroll">
        
        <!-- Sidebar Tabs -->
        <div class="course-tabs-sidebar">
          <button class="course-tab-btn active" data-color="purple" onclick="openCourseTab('tab-n5', this)">1. JLPT N5 <i class="fa-solid fa-chevron-right"></i></button>
          <button class="course-tab-btn" data-color="blue" onclick="openCourseTab('tab-n4', this)">2. JLPT N4 <i class="fa-solid fa-chevron-right"></i></button>
          <button class="course-tab-btn" data-color="rose" onclick="openCourseTab('tab-n3', this)">3. JLPT N3 <i class="fa-solid fa-chevron-right"></i></button>
          <button class="course-tab-btn" data-color="orange" onclick="openCourseTab('tab-n2', this)">4. JLPT N2 & N1 <i class="fa-solid fa-chevron-right"></i></button>
          <button class="course-tab-btn" data-color="emerald" onclick="openCourseTab('tab-spoken', this)">5. Spoken Japanese <i class="fa-solid fa-chevron-right"></i></button>
          <button class="course-tab-btn" data-color="indigo" onclick="openCourseTab('tab-basic', this)">6. Basic Foundation <i class="fa-solid fa-chevron-right"></i></button>
        </div>

        <!-- Content Area -->
        <div class="course-tab-content-area">
          
          <!-- N5 Pane -->
          <div id="tab-n5" class="course-tab-pane active purple" data-letter="五">
            <div class="ctp-header">
              <div class="ctp-icon" style="background: linear-gradient(135deg, #8b5cf6, #7c3aed);"><i class="fa-solid fa-seedling"></i></div>
              <div>
                <h3 class="ctp-title">JLPT N5 — Foundation Level</h3>
                <p class="ctp-subtitle">"Start your Japanese journey from scratch."</p>
              </div>
            </div>
            <div class="ctp-overview purple"><strong>Overview:</strong> Learn to read and write basic Japanese scripts, introduce yourself, make purchases, and hold basic daily conversations.</div>
            <ul class="ctp-list purple">
              <li>Master Hiragana, Katakana, and ~100 basic Kanji characters.</li>
              <li>Acquire ~800 essential vocabulary words.</li>
              <li>Grasp fundamental SOV sentence structure and polite speech markers.</li>
              <li><strong>Duration:</strong> 80 – 100 Hours (Weekday or Weekend Batches).</li>
            </ul>
          </div>

          <!-- N4 Pane -->
          <div id="tab-n4" class="course-tab-pane blue" data-letter="四">
            <div class="ctp-header">
              <div class="ctp-icon" style="background: linear-gradient(135deg, #0ea5e9, #0284c7);"><i class="fa-solid fa-leaf"></i></div>
              <div>
                <h3 class="ctp-title">JLPT N4 — Elementary Level</h3>
                <p class="ctp-subtitle">"Build natural daily conversational ability."</p>
              </div>
            </div>
            <div class="ctp-overview blue"><strong>Overview:</strong> Transition from basic expressions to complex grammar structures, expressing reasons, intentions, and conditions.</div>
            <ul class="ctp-list blue">
              <li>Master ~300 Kanji and expand vocabulary to ~1,500 words.</li>
              <li>Understand casual speech styles and passive/causative verbs.</li>
              <li>Read short passages and listen to natural-speed slow dialogue.</li>
              <li><strong>Duration:</strong> 100 – 120 Hours (Fast-Track available).</li>
            </ul>
          </div>

          <!-- N3 Pane -->
          <div id="tab-n3" class="course-tab-pane rose" data-letter="三">
            <div class="ctp-header">
              <div class="ctp-icon" style="background: linear-gradient(135deg, #f43f5e, #e11d48);"><i class="fa-solid fa-tree"></i></div>
              <div>
                <h3 class="ctp-title">JLPT N3 — Intermediate Level</h3>
                <p class="ctp-subtitle">"Bridge the gap between basic and natural fluency."</p>
              </div>
            </div>
            <div class="ctp-overview rose"><strong>Overview:</strong> Understand Japanese used in everyday situations at a near-native pace. Key for academic admission to language schools.</div>
            <ul class="ctp-list rose">
              <li>Master ~650 Kanji and ~3,700 vocabulary words.</li>
              <li>Express nuanced opinions and complex hypothetical scenarios.</li>
              <li>Read newspaper headlines and listen to near-normal speed conversations.</li>
              <li><strong>Duration:</strong> 120 – 150 Hours (Weekend & Evening Batches).</li>
            </ul>
          </div>

          <!-- N2 Pane -->
          <div id="tab-n2" class="course-tab-pane orange" data-letter="二">
            <div class="ctp-header">
              <div class="ctp-icon" style="background: linear-gradient(135deg, #f97316, #ea580c);"><i class="fa-solid fa-mountain"></i></div>
              <div>
                <h3 class="ctp-title">JLPT N2 & N1 — Advanced</h3>
                <p class="ctp-subtitle">"Master professional Japanese for careers in Japan."</p>
              </div>
            </div>
            <div class="ctp-overview orange"><strong>Overview:</strong> Specialized modules focusing on formal business Keigo, technical vocabulary, and corporate communication protocols.</div>
            <ul class="ctp-list orange">
              <li><strong>N2:</strong> ~1,000 Kanji / 6,000 words — Capability to work and study fully.</li>
              <li><strong>N1:</strong> ~2,000 Kanji / 10,000 words — Complete fluency across complex domains.</li>
              <li>Business etiquette, corporate email writing, and interview presentation prep.</li>
              <li><strong>Duration:</strong> 150+ Hours per level.</li>
            </ul>
          </div>

          <!-- Spoken Pane -->
          <div id="tab-spoken" class="course-tab-pane emerald" data-letter="話">
            <div class="ctp-header">
              <div class="ctp-icon" style="background: linear-gradient(135deg, #10b981, #059669);"><i class="fa-solid fa-comments"></i></div>
              <div>
                <h3 class="ctp-title">Spoken Japanese (Romaji)</h3>
                <p class="ctp-subtitle">"Speak Japanese from Day 1—no reading required!"</p>
              </div>
            </div>
            <div class="ctp-overview emerald"><strong>Overview:</strong> Fast, practical speaking and listening skills written entirely in Romaji (English script).</div>
            <ul class="ctp-list emerald">
              <li>Master basic greetings, introductions, and travel emergencies.</li>
              <li>Develop correct pronunciation, pitch accent, and natural cadence.</li>
              <li>Zero Japanese Characters (100% Romaji-based curriculum).</li>
              <li><strong>Duration:</strong> 40 – 50 Hours.</li>
            </ul>
          </div>

          <!-- Basic Pane -->
          <div id="tab-basic" class="course-tab-pane indigo" data-letter="基">
            <div class="ctp-header">
              <div class="ctp-icon" style="background: linear-gradient(135deg, #6366f1, #4f46e5);"><i class="fa-solid fa-puzzle-piece"></i></div>
              <div>
                <h3 class="ctp-title">Basic Japanese Foundation</h3>
                <p class="ctp-subtitle">"A gentle entry into reading, writing, and speaking."</p>
              </div>
            </div>
            <div class="ctp-overview indigo"><strong>Overview:</strong> A relaxed alternative to the full N5 track to test the waters before committing to certification.</div>
            <ul class="ctp-list indigo">
              <li>Read and write Hiragana and Katakana scripts with confidence.</li>
              <li>Learn 45 essential Kanji and a core vocabulary of ~300 words.</li>
              <li>Form simple present/past tense sentences for short dialogues.</li>
              <li><strong>Duration:</strong> 45 – 50 Hours.</li>
            </ul>
          </div>

        </div>
      </div>

      <script>
        function openCourseTab(tabId, btnElement) {
          // Hide all panes
          document.querySelectorAll('.course-tab-pane').forEach(pane => {
            pane.classList.remove('active');
          });
          // Remove active class from all buttons
          document.querySelectorAll('.course-tab-btn').forEach(btn => {
            btn.classList.remove('active');
          });
          // Show selected pane and set button to active
          document.getElementById(tabId).classList.add('active');
          btnElement.classList.add('active');
        }
      </script>      </div>
    </div>
  </section>

  <!-- SPECIALIZED PROGRAMS & EXAM COMPARISON -->
  <section class="section" style="padding: 6rem 0;">
    <div class="container">
      
      <!-- Specialized Programs Grid -->
      <div class="text-center animate-on-scroll" style="margin-bottom: 3rem;">
        <h2 class="section__title">Specialized <span>Learning Programs</span></h2>
      </div>
      <div class="grid grid--4 gap--2" style="margin-bottom: 6rem;">
        
        <div class="pte-wave-feature-card tw-theme-1 animate-on-scroll" data-letter="速">
          <div class="twf-icon-wrap"><i class="fa-solid fa-bolt"></i></div>
          <h3 class="twf-title">Fast-Track Intensive</h3>
          <p class="twf-desc">Double-pace classes designed to complete N5 or N4 in 6–8 weeks for job seekers with tight deadlines.</p>
          <div class="twf-bottom">
            <svg class="twf-svg" viewBox="0 0 1000 100" preserveAspectRatio="none"><path d="M0,50 Q250,100 500,50 T1000,50 L1000,100 L0,100 Z"></path></svg>
          </div>
        </div>

        <div class="pte-wave-feature-card tw-theme-2 animate-on-scroll delay-1" data-letter="個">
          <div class="twf-icon-wrap"><i class="fa-solid fa-user-tie"></i></div>
          <h3 class="twf-title">1-on-1 Private</h3>
          <p class="twf-desc">Custom pace, personalized curriculum, and flexible rescheduling for executives.</p>
          <div class="twf-bottom">
            <svg class="twf-svg" viewBox="0 0 1000 100" preserveAspectRatio="none"><path d="M0,50 Q250,100 500,50 T1000,50 L1000,100 L0,100 Z"></path></svg>
          </div>
        </div>

        <div class="pte-wave-feature-card tw-theme-3 animate-on-scroll delay-2" data-letter="子">
          <div class="twf-icon-wrap"><i class="fa-solid fa-gamepad"></i></div>
          <h3 class="twf-title">Kids & Teens (8-16)</h3>
          <p class="twf-desc">Fun, gamified cultural learning, basic scripts, and conversational games.</p>
          <div class="twf-bottom">
            <svg class="twf-svg" viewBox="0 0 1000 100" preserveAspectRatio="none"><path d="M0,50 Q250,100 500,50 T1000,50 L1000,100 L0,100 Z"></path></svg>
          </div>
        </div>

        <div class="pte-wave-feature-card tw-theme-4 animate-on-scroll delay-3" data-letter="業">
          <div class="twf-icon-wrap"><i class="fa-solid fa-building"></i></div>
          <h3 class="twf-title">Business & Keigo</h3>
          <p class="twf-desc">Strict focus on business email writing, meeting etiquette, and honorifics for professionals.</p>
          <div class="twf-bottom">
            <svg class="twf-svg" viewBox="0 0 1000 100" preserveAspectRatio="none"><path d="M0,50 Q250,100 500,50 T1000,50 L1000,100 L0,100 Z"></path></svg>
          </div>
        </div>

      </div>

      <!-- Exam Comparison -->
      <div class="animate-on-scroll" style="border-radius: 24px; padding: 1rem;">
        <div class="text-center" style="margin-bottom: 2rem;">
          <h2 class="section__title">Exam Comparison <span>Guide</span></h2>
          <p class="section__subtitle" style="margin: 0 auto;">Not sure which exam to register for? Here is a breakdown of the two primary official options.</p>
        </div>
        <style>
.exam-comparison-table {
  width: 100%; border-collapse: separate; border-spacing: 0 1rem; margin-top: -1rem;
}
.exam-comparison-table th {
  padding: 1.5rem; text-align: left; font-size: 1.15rem; font-weight: 800; color: white; border-radius: 16px; border: 4px solid white;
}
.exam-comparison-table tbody tr {
  background: white; transition: all 0.3s ease; box-shadow: 0 5px 15px rgba(0,0,0,0.03); border-radius: 16px;
}
.exam-comparison-table tbody tr:hover {
  transform: translateY(-5px); box-shadow: 0 15px 30px rgba(139, 92, 246, 0.15); z-index: 10; position: relative;
}
.exam-comparison-table td {
  padding: 1.5rem; color: var(--gray); font-size: 1.05rem; line-height: 1.6; border-top: 1px solid transparent; border-bottom: 1px solid transparent; background: #fafafa;
}
.exam-comparison-table tbody tr:hover td {
  background: white;
}
.exam-comparison-table td:first-child {
  font-weight: 700; color: var(--dark); border-left: 1px solid transparent; border-top-left-radius: 16px; border-bottom-left-radius: 16px;
}
.exam-comparison-table td:last-child {
  border-right: 1px solid transparent; border-top-right-radius: 16px; border-bottom-right-radius: 16px;
}
.exam-comparison-table tbody tr:hover td {
  border-color: rgba(139, 92, 246, 0.1);
}
.table-icon {
  color: var(--pte-primary); margin-right: 8px; width: 20px; text-align: center;
}
</style>

<div style="overflow-x: auto; padding: 1rem 0.5rem;">
  <table class="exam-comparison-table">
    <thead>
      <tr>
        <th style="width: 20%; background: transparent; color: var(--dark);">Feature</th>
        <th style="width: 40%; background: linear-gradient(135deg, #a78bfa, #8b5cf6); box-shadow: 0 10px 20px rgba(139, 92, 246, 0.2);">JLPT (Japanese Language Proficiency Test)</th>
        <th style="width: 40%; background: linear-gradient(135deg, #8b5cf6, #7c3aed); box-shadow: 0 10px 20px rgba(124, 58, 237, 0.2);">NAT-TEST</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td><i class="fa-solid fa-globe table-icon"></i> Global Recognition</td>
        <td>Universally recognized worldwide by employers & universities.</td>
        <td>Recognized by Japanese immigration, language schools, & employers.</td>
      </tr>
      <tr>
        <td><i class="fa-regular fa-calendar-check table-icon"></i> Frequency</td>
        <td>2 Times a Year (July & December)</td>
        <td>6 Times a Year (Every even month: Feb, Apr, Jun, Aug, Oct, Dec)</td>
      </tr>
      <tr>
        <td><i class="fa-solid fa-clipboard-list table-icon"></i> Format</td>
        <td>Multiple-choice (Vocabulary, Grammar, Reading, Listening)</td>
        <td>Identical structure and difficulty level to JLPT.</td>
      </tr>
      <tr>
        <td><i class="fa-solid fa-bullseye table-icon"></i> Best Used For</td>
        <td>Official career record, university admissions, permanent residency points.</td>
        <td>Fast-track visa applications and rapid progress verification.</td>
      </tr>
    </tbody>
  </table>
</div>
      </div>

    </div>
  </section>

  <!-- SEO CONTENT SECTION -->
  <section class="section" style="position: relative; padding: 6rem 0; background: url('assets/images/jap.png') center/cover fixed;">
    <div style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: linear-gradient(135deg, rgba(255, 255, 255, 0.92), rgba(255, 255, 255, 0.8)); z-index: 1;"></div>
    
    <div class="container" style="position: relative; z-index: 2;">
      <div class="seo-glass-card">
        
        <div class="seo-premium-content" style="max-width: 900px; margin: 0 auto; color: var(--gray);">
          
          <style>
            .seo-glass-card { background: rgba(255, 255, 255, 0.6); backdrop-filter: blur(20px); -webkit-backdrop-filter: blur(20px); border: 1px solid rgba(255, 255, 255, 0.8); padding: 4rem; border-radius: 32px; box-shadow: 0 20px 50px rgba(0,0,0,0.06); }
            .seo-premium-content h2, .seo-premium-content h3 { color: var(--dark); font-family: 'Plus Jakarta Sans', sans-serif; font-weight: 800; }
            .seo-premium-content h2 { font-size: 2.5rem; margin-bottom: 1.5rem; }
            .seo-premium-content h3 { font-size: 1.8rem; margin-top: 3.5rem; margin-bottom: 1.25rem; border-left: 4px solid var(--pte-primary); padding-left: 1rem; }
            .seo-premium-content p { font-size: 1.1rem; line-height: 1.8; margin-bottom: 1.5rem; font-weight: 400; color: var(--gray); }
            .seo-premium-content ul { list-style: none; padding: 0; margin-bottom: 2rem; display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 1.25rem; }
            .seo-premium-content ul li { position: relative; font-size: 1.05rem; background: rgba(255,255,255,0.7); padding: 1.25rem 1.25rem 1.25rem 3.5rem; border-radius: 16px; border: 1px solid rgba(0,0,0,0.04); transition: transform 0.3s ease, background 0.3s ease, box-shadow 0.3s ease; color: var(--dark); font-weight: 500;}
            .seo-premium-content ul li:hover { transform: translateY(-3px); background: #ffffff; box-shadow: 0 10px 20px rgba(0,0,0,0.04); }
            .seo-premium-content ul li::before { content: '\f00c'; font-family: 'Font Awesome 6 Free'; font-weight: 900; position: absolute; left: 1.25rem; top: 1.25rem; color: var(--pte-primary); font-size: 1.2rem; }
            .seo-premium-content strong { color: var(--dark); font-weight: 700; }
            @media (max-width: 768px) {
              .seo-premium-content h2 { font-size: 2rem; }
              .seo-premium-content ul { grid-template-columns: 1fr; }
              .seo-glass-card { padding: 1.5rem; }
            }
          </style>

          <h2>Learn Japanese in Coimbatore from the Basics</h2>
          <p>Japanese may look difficult when you first come across its writing systems and sentence structure. However, learning becomes much easier when the concepts are introduced in the right order. Our Japanese language course in Coimbatore helps learners build a strong foundation before moving towards more advanced topics.</p>
          
          <ul>
            <li>Hiragana and Katakana</li>
            <li>Basic Japanese vocabulary & Kanji fundamentals</li>
            <li>Grammar and sentence formation</li>
            <li>Reading, writing, and listening practice</li>
            <li>Everyday Japanese conversations</li>
            <li>JLPT-focused practice</li>
          </ul>
          
          <h3>JLPT Coaching & Preparation in Coimbatore</h3>
          <p>The Japanese-Language Proficiency Test (JLPT) is an important qualification for people who want to demonstrate their Japanese language ability. For students who have a specific JLPT goal, JLPT coaching in Coimbatore can provide a more organised way to prepare.</p>
          <p>Clearing JLPT requires more than knowing a collection of Japanese words. Students need to understand grammar, recognise Kanji and vocabulary, read passages, and follow spoken Japanese. Effective JLPT preparation should therefore include a combination of learning and consistent practice.</p>

          <h3>Japanese N5, N4 and N3 Classes</h3>
          <p>If you are new to Japanese, <strong>JLPT N5 classes in Coimbatore</strong> can help you establish the basics. Students who already have a foundation can move towards <strong>JLPT N4 classes</strong>, handling more vocabulary and everyday communication. For those with a stronger understanding, <strong>JLPT N3 classes</strong> offer an opportunity to develop intermediate-level skills.</p>

          <h3>Learn Japanese for Opportunities in Japan</h3>
