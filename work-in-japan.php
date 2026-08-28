<?php
require_once 'includes/config.php';
$pageTitle = 'Work in Japan | Bluestone Overseas';
$pageDescription = 'Turn Your Japanese Skills into a High-Growth International Career. Discover visa tracks, job roles, and career counseling for working in Japan.';
$pageKeywords = 'Work in Japan, Japan Jobs, TITP, SSW Visa, Japan IT Jobs';
$pageHeroImage = 'assets/images/areowomen.png';
require_once 'includes/header.php';
?>

<style>
/* Premium Theme Variables (Purple) */
:root {
  --pte-primary: #8b5cf6; /* violet-500 */
  --pte-light: #f5f3ff; /* violet-50 */
  --pte-gradient: linear-gradient(135deg, #a78bfa, #7c3aed);
  --dark: #0f172a;
  --gray: #475569;
}

/* Base Styles */
.pte-text-gradient {
  background: var(--pte-gradient);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
}

/* 1. WAVE HERO SECTION */
.pte-hero {
  position: relative;
  padding: 8rem 0 6rem;
  background: linear-gradient(135deg, #f8fafc 0%, #ffffff 100%);
  overflow: hidden;
}
.pte-hero::before {
  content: ''; position: absolute; top: -20%; right: -10%; width: 700px; height: 700px; background: linear-gradient(135deg, rgba(139, 92, 246, 0.05), rgba(167, 139, 250, 0.08)); border-radius: 50%; filter: blur(80px); z-index: 0; animation: float 10s infinite ease-in-out alternate;
}
.pte-hero::after {
  content: ''; position: absolute; bottom: -10%; left: -10%; width: 500px; height: 500px; background: linear-gradient(135deg, rgba(56, 189, 248, 0.05), rgba(139, 92, 246, 0.08)); border-radius: 50%; filter: blur(60px); z-index: 0; animation: float 8s infinite ease-in-out alternate-reverse;
}
@keyframes float { 0% { transform: translateY(0) scale(1); } 100% { transform: translateY(-30px) scale(1.05); } }
.pte-hero .container { position: relative; z-index: 2; }
.pte-hero-tag { display: inline-block; padding: 0.5rem 1.5rem; background: white; color: var(--pte-primary); border-radius: 50px; font-weight: 700; font-size: 0.9rem; margin-bottom: 1.5rem; box-shadow: 0 10px 20px rgba(139, 92, 246, 0.1); border: 1px solid rgba(139, 92, 246, 0.1); letter-spacing: 1px; text-transform: uppercase; }
.pte-hero-title { font-size: clamp(2.5rem, 5vw, 4.5rem); font-weight: 800; color: var(--dark); line-height: 1.1; margin-bottom: 1.5rem; font-family: 'Plus Jakarta Sans', sans-serif; }
.pte-hero-subtitle { font-size: 1.15rem; color: var(--gray); line-height: 1.8; max-width: 600px; margin-bottom: 2.5rem; }

/* 2. WAVE CARDS SECTION (Advantages) */
.pte-wave-feature-card { background: white; border-radius: 24px; padding: 3.5rem 2.5rem 2.5rem; position: relative; overflow: hidden; z-index: 1; box-shadow: 0 15px 35px rgba(0,0,0,0.04); border: 1px solid rgba(0,0,0,0.03); transition: all 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275); height: 100%; }
.pte-wave-feature-card:hover { transform: translateY(-15px); box-shadow: 0 30px 60px rgba(0,0,0,0.08); }
.twf-icon-wrap { width: 80px; height: 80px; border-radius: 20px; background: linear-gradient(135deg, var(--w-c1), var(--w-c2)); display: flex; align-items: center; justify-content: center; font-size: 2rem; color: white; margin-bottom: 2rem; box-shadow: 0 15px 30px rgba(0,0,0,0.1); position: relative; z-index: 2; transition: transform 0.4s ease; }
.pte-wave-feature-card:hover .twf-icon-wrap { transform: scale(1.1) rotate(-5deg); }
.twf-title { font-size: 1.3rem; font-weight: 800; color: var(--dark); margin-bottom: 1rem; font-family: 'Plus Jakarta Sans', sans-serif; position: relative; z-index: 2; transition: color 0.4s ease; }
.twf-desc { color: var(--gray); line-height: 1.7; position: relative; z-index: 2; transition: color 0.4s ease; }
.twf-bottom { position: absolute; bottom: 0; left: 0; width: 100%; height: 65%; background: linear-gradient(180deg, var(--w-c1), var(--w-c2)); color: var(--w-c1); z-index: 1; transition: all 0.5s cubic-bezier(0.25, 0.8, 0.25, 1); transform: translateY(100%); }
.twf-svg { position: absolute; top: -29px; left: 0; width: 100%; height: 30px; fill: currentColor; }
.pte-wave-feature-card:hover .twf-bottom { transform: translateY(0); }
.pte-wave-feature-card:hover .twf-title, .pte-wave-feature-card:hover .twf-desc { color: white; }

/* 3. ELITE HORIZONTAL CARDS (Sectors) */
.pte-elite-card { display: flex; background: white; border-radius: 30px; overflow: hidden; box-shadow: 0 20px 50px rgba(0,0,0,0.05); margin-bottom: 3rem; transition: all 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275); border: 1px solid rgba(0,0,0,0.02); }
.pte-elite-card:hover { transform: translateY(-10px); box-shadow: 0 30px 60px rgba(0,0,0,0.1); }
.tec-image { flex: 0 0 35%; position: relative; overflow: hidden; background: linear-gradient(135deg, var(--tec-bg1), var(--tec-bg2)); display: flex; align-items: center; justify-content: center; padding: 2rem; font-size: 6rem; color: var(--tec-c1); }
.tec-content { flex: 1; padding: 2.5rem; display: flex; flex-direction: column; justify-content: center; }
.tec-title { font-size: 1.8rem; font-weight: 800; color: var(--dark); margin-bottom: 1rem; font-family: 'Plus Jakarta Sans', sans-serif; }
.tec-list { list-style: none; padding: 0; margin: 0; display: grid; gap: 1rem; }
.tec-list li { position: relative; padding-left: 2rem; color: var(--gray); font-size: 1.05rem; line-height: 1.6; }
.tec-list li::before { content: '\f00c'; font-family: 'Font Awesome 6 Free'; font-weight: 900; position: absolute; left: 0; top: 2px; color: var(--tec-c1); }
.pte-elite-card:nth-child(even) { flex-direction: row-reverse; }

/* Responsive */
@media (max-width: 992px) {
  .pte-elite-card, .pte-elite-card:nth-child(even) { flex-direction: column; }
  .tec-content { padding: 2rem; }
}

/* Theme Colors */
.tw-theme-1 { --w-c1: #8b5cf6; --w-c2: #7c3aed; }
.tw-theme-2 { --w-c1: #0ea5e9; --w-c2: #0284c7; }
.tw-theme-3 { --w-c1: #10b981; --w-c2: #059669; }
.tw-theme-4 { --w-c1: #f43f5e; --w-c2: #e11d48; }

/* Elite Cards */
.tec-theme-1 { --tec-c1: #8b5cf6; --tec-c2: #7c3aed; --tec-bg1: #f5f3ff; --tec-bg2: #ede9fe; }
.tec-theme-2 { --tec-c1: #0ea5e9; --tec-c2: #0284c7; --tec-bg1: #f0f9ff; --tec-bg2: #e0f2fe; }
.tec-theme-3 { --tec-c1: #f97316; --tec-c2: #ea580c; --tec-bg1: #fff7ed; --tec-bg2: #ffedd5; }
.tec-theme-4 { --tec-c1: #10b981; --tec-c2: #059669; --tec-bg1: #f0fdf4; --tec-bg2: #dcfce7; }

/* Form Controls */
.form-control {
  width: 100%;
  padding: 1rem 1.5rem;
  font-size: 1rem;
  color: var(--dark);
  background-color: #f8fafc;
  border: 1px solid #e2e8f0;
  border-radius: 12px;
  transition: all 0.3s ease;
  font-family: inherit;
}
.form-control:focus {
  outline: none;
  border-color: var(--pte-primary);
  background-color: #ffffff;
  box-shadow: 0 0 0 4px rgba(139, 92, 246, 0.1);
}
select.form-control {
  appearance: none;
  background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 24 24' fill='none' stroke='%23475569' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpath d='M6 9l6 6 6-6'/%3E%3C/svg%3E");
  background-repeat: no-repeat;
  background-position: right 1rem center;
  padding-right: 3rem;
}
</style>

<main>
  <!-- HERO -->
  <section class="pte-hero">
    <div class="container text-center">
      <div class="animate-on-scroll">
        <span class="pte-hero-tag"><i class="fa-solid fa-briefcase"></i> Work in Japan</span>
        <h1 class="pte-hero-title">Build a Rewarding Global <br><span class="pte-text-gradient">Career in Japan</span></h1>
        <p class="pte-hero-subtitle" style="margin: 0 auto 2.5rem;">As Japan faces unprecedented workforce shortages, Japanese companies are actively hiring skilled Indian talent across engineering, healthcare, hospitality, and specialized trades. We provide end-to-end language preparation, visa processing, and job placement support.</p>
        
        <div style="display: flex; gap: 1rem; flex-wrap: wrap; justify-content: center;">
          <a href="#sectors" class="btn btn--primary btn--lg pulse-btn" style="background: var(--pte-primary); box-shadow: 0 10px 25px rgba(139, 92, 246, 0.4);">Explore Job Tracks</a>
          <a href="#visa" class="btn btn--outline btn--lg" style="border-color: rgba(139, 92, 246, 0.2); color: var(--dark);">Visa Tracks</a>
          <a href="#register" class="btn btn--outline btn--lg" style="border-color: rgba(139, 92, 246, 0.2); color: var(--dark);">Book Career Counseling</a>
        </div>
      </div>
    </div>
  </section>

  <!-- WHY WORK IN JAPAN (Wave Cards) -->
  <section class="section bg-light">
    <div class="container">
      <div class="text-center animate-on-scroll" style="margin-bottom: 4rem;">
        <span class="section__tag" style="color: var(--pte-primary); background: rgba(139, 92, 246, 0.1);">Key Advantages</span>
        <h2 class="section__title">Why Choose <span>Japan?</span></h2>
        <p class="section__subtitle" style="max-width: 700px; margin: 0 auto;">Japan is rapidly becoming a top-tier destination for Indian professionals seeking safety, higher quality of life, and international exposure.</p>
      </div>

      <div class="grid grid--4 gap--2">
        <div class="pte-wave-feature-card tw-theme-1 animate-on-scroll">
          <div class="twf-icon-wrap"><i class="fa-solid fa-shield-halved"></i></div>
          <h3 class="twf-title">Unmatched Safety</h3>
          <p class="twf-desc">Consistently ranks among the safest nations globally with world-class infrastructure.</p>
          <div class="twf-bottom"><svg class="twf-svg" viewBox="0 0 1440 320" preserveAspectRatio="none"><path d="M0,160L48,170.7C96,181,192,203,288,192C384,181,480,139,576,133.3C672,128,768,160,864,181.3C960,203,1056,213,1152,192C1248,171,1344,117,1392,90.7L1440,64L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"></path></svg></div>
        </div>
        <div class="pte-wave-feature-card tw-theme-2 animate-on-scroll delay-1">
          <div class="twf-icon-wrap"><i class="fa-solid fa-handshake"></i></div>
          <h3 class="twf-title">Bilateral Support</h3>
          <p class="twf-desc">Strong G2G partnerships make visa processing smooth through specialized schemes like TITP and SSW.</p>
          <div class="twf-bottom"><svg class="twf-svg" viewBox="0 0 1440 320" preserveAspectRatio="none"><path d="M0,160L48,170.7C96,181,192,203,288,192C384,181,480,139,576,133.3C672,128,768,160,864,181.3C960,203,1056,213,1152,192C1248,171,1344,117,1392,90.7L1440,64L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"></path></svg></div>
        </div>
        <div class="pte-wave-feature-card tw-theme-3 animate-on-scroll delay-2">
          <div class="twf-icon-wrap"><i class="fa-solid fa-coins"></i></div>
          <h3 class="twf-title">High Earnings</h3>
          <p class="twf-desc">Competitive salaries, overtime bonuses, national health insurance, and subsidized housing options.</p>
          <div class="twf-bottom"><svg class="twf-svg" viewBox="0 0 1440 320" preserveAspectRatio="none"><path d="M0,160L48,170.7C96,181,192,203,288,192C384,181,480,139,576,133.3C672,128,768,160,864,181.3C960,203,1056,213,1152,192C1248,171,1344,117,1392,90.7L1440,64L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"></path></svg></div>
        </div>
        <div class="pte-wave-feature-card tw-theme-4 animate-on-scroll delay-3">
          <div class="twf-icon-wrap"><i class="fa-solid fa-passport"></i></div>
          <h3 class="twf-title">Long-Term Stay</h3>
          <p class="twf-desc">Clear paths for visa renewals, bringing dependents, and eligibility for Permanent Residency (PR).</p>
          <div class="twf-bottom"><svg class="twf-svg" viewBox="0 0 1440 320" preserveAspectRatio="none"><path d="M0,160L48,170.7C96,181,192,203,288,192C384,181,480,139,576,133.3C672,128,768,160,864,181.3C960,203,1056,213,1152,192C1248,171,1344,117,1392,90.7L1440,64L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"></path></svg></div>
        </div>
      </div>
    </div>
  </section>

  <!-- JOB SECTORS -->
  <section id="sectors" class="section" style="padding: 6rem 0;">
    <div class="container">
      <div class="text-center animate-on-scroll" style="margin-bottom: 4rem;">
        <span class="section__tag" style="color: var(--pte-primary); background: rgba(139, 92, 246, 0.1);">Demand Survey</span>
        <h2 class="section__title">High-Demand <span>Job Sectors</span></h2>
        <p class="section__subtitle" style="max-width: 600px; margin: 0 auto;">Japan is projected to require over 1 million foreign workers by 2040. Here are the main industry categories seeking talent.</p>
      </div>

      <div class="pte-elite-wrapper">
        <div class="pte-elite-card tec-theme-1 animate-on-scroll">
          <div class="tec-image" style="padding: 0;"><img src="assets/images/sector_tech_japan.png" alt="Tech Careers Japan" style="width: 100%; height: 100%; object-fit: cover; position: absolute; top: 0; left: 0;"></div>
          <div class="tec-content">
            <h3 class="tec-title">1. White-Collar Careers (Engineering & Tech)</h3>
            <p style="color: var(--gray); margin-bottom: 1rem;">Higher education degrees paired with JLPT N3 to N1 proficiency open doors to major Japanese enterprises and MNCs.</p>
            <ul class="tec-list">
              <li><strong>Information Technology:</strong> Full Stack Developers, AI/ML Engineers, Cloud Architects, Data Scientists.</li>
              <li><strong>Engineering:</strong> Industrial Automation, Automotive R&D, Mechatronics, and Robotics.</li>
              <li><strong>Global Business:</strong> HR Specialists, International Sales, Financial Analysts.</li>
              <li><strong>Average Salary:</strong> ¥4,500,000 – ¥9,000,000+ per year (Approx. $30k – $60k USD).</li>
            </ul>
          </div>
        </div>

        <div class="pte-elite-card tec-theme-4 animate-on-scroll">
          <div class="tec-image" style="padding: 0;"><img src="assets/images/sector_health_japan.png" alt="Healthcare Japan" style="width: 100%; height: 100%; object-fit: cover; position: absolute; top: 0; left: 0;"></div>
          <div class="tec-content">
            <h3 class="tec-title">2. Healthcare & Social Care Services</h3>
            <p style="color: var(--gray); margin-bottom: 1rem;">Due to an aging demographic, Japan's healthcare system relies heavily on certified foreign caregivers and medical technicians.</p>
            <ul class="tec-list">
              <li><strong>Key Roles:</strong> Geriatric Care Workers, Nursing Aides, Medical Technologists.</li>
              <li><strong>Target:</strong> JLPT N4/N3 + Nursing Care Japanese Evaluation Test.</li>
              <li><strong>Career Growth:</strong> High demand ensures job security and training for RN certification in Japan.</li>
              <li><strong>Average Salary:</strong> ¥3,200,000 – ¥4,500,000 per year.</li>
            </ul>
          </div>
        </div>

        <div class="pte-elite-card tec-theme-3 animate-on-scroll">
          <div class="tec-image" style="padding: 0;"><img src="assets/images/sector_hospitality_japan.png" alt="Hospitality Japan" style="width: 100%; height: 100%; object-fit: cover; position: absolute; top: 0; left: 0;"></div>
          <div class="tec-content">
            <h3 class="tec-title">3. Grey-Collar & Service Management</h3>
            <p style="color: var(--gray); margin-bottom: 1rem;">Roles combining practical skills with customer interaction, operational management, and bilingual communication.</p>
            <ul class="tec-list">
              <li><strong>Hospitality & Tourism:</strong> Hotel Front Desk Officers, Guest Relations Managers.</li>
              <li><strong>Aviation & Logistics:</strong> Airport Ground Support, Aircraft Maintenance.</li>
              <li><strong>Food & Beverage:</strong> Restaurant Floor Supervisors, Kitchen Operations Managers.</li>
              <li><strong>Target:</strong> JLPT N4 to N3 + Industry Skill Exam.</li>
            </ul>
          </div>
        </div>
        
        <div class="pte-elite-card tec-theme-2 animate-on-scroll">
          <div class="tec-image" style="padding: 0;"><img src="assets/images/sector_industry_japan.png" alt="Industrial Japan" style="width: 100%; height: 100%; object-fit: cover; position: absolute; top: 0; left: 0;"></div>
          <div class="tec-content">
            <h3 class="tec-title">4. Blue-Collar & Industrial Skilled Trades (SSW)</h3>
            <p style="color: var(--gray); margin-bottom: 1rem;">Structured entry programs under Japan's official Specified Skilled Worker (SSW) visa categories.</p>
            <ul class="tec-list">
              <li><strong>Manufacturing:</strong> Factory assembly, metal processing, welding.</li>
              <li><strong>Construction:</strong> Civil engineering site work, scaffolding, plumbing.</li>
              <li><strong>Automobile:</strong> Car inspection, mechanical repairs, maintenance.</li>
              <li><strong>Target:</strong> JLPT N4 / JFT-Basic + Industry Specific Test.</li>
            </ul>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- VISA OPTIONS -->
  <section id="visa" class="section bg-light" style="padding: 6rem 0;">
    <div class="container">
      <div class="text-center animate-on-scroll" style="margin-bottom: 4rem;">
        <span class="section__tag" style="color: var(--pte-primary); background: rgba(139, 92, 246, 0.1);">Immigration</span>
        <h2 class="section__title">Work Visa <span>Pathways</span></h2>
        <p class="section__subtitle" style="max-width: 600px; margin: 0 auto;">Demystifying technical eligibility and legal processes for entering Japan.</p>
      </div>
      
      <style>
.visa-card { background: white; border-radius: 24px; overflow: hidden; box-shadow: 0 15px 35px rgba(0,0,0,0.05); border: 1px solid rgba(0,0,0,0.03); transition: all 0.4s ease; }
.visa-card:hover { transform: translateY(-10px); box-shadow: 0 25px 50px rgba(139, 92, 246, 0.1); border-color: rgba(139, 92, 246, 0.2); }
.visa-img-container { width: 100%; height: 250px; overflow: hidden; position: relative; }
.visa-img-container img { width: 100%; height: 100%; object-fit: cover; transition: transform 0.6s ease; }
.visa-card:hover .visa-img-container img { transform: scale(1.08); }
.visa-content { padding: 2.5rem; }
.visa-title { font-size: 1.5rem; font-weight: 800; margin-bottom: 1rem; display: flex; align-items: center; gap: 0.75rem; }
.visa-desc { color: var(--gray); margin-bottom: 1.5rem; line-height: 1.6; }
.visa-list { list-style: none; padding: 0; }
.visa-list li { margin-bottom: 1rem; position: relative; padding-left: 2rem; color: var(--dark); font-size: 0.95rem; line-height: 1.6; }
.visa-list li i { position: absolute; left: 0; top: 3px; font-size: 1.1rem; }
</style>

      <div class="grid grid--2 gap--4">
        
        <div class="visa-card animate-on-scroll">
           <div class="visa-img-container">
             <img src="assets/images/visa_engineer_japan.png" alt="Engineer Specialist Visa">
           </div>
           <div class="visa-content">
             <h3 class="visa-title" style="color: var(--pte-primary);"><i class="fa-solid fa-user-tie"></i> 1. Engineer / Specialist</h3>
             <p class="visa-desc">For university degree holders in STEM, Business, Arts, or IT.</p>
             <ul class="visa-list">
               <li><i class="fa-solid fa-check text-success"></i> <strong>Requirement:</strong> Bachelor's Degree + JLPT N3-N1</li>
               <li><i class="fa-solid fa-check text-success"></i> <strong>Key Benefit:</strong> Multi-year renewable visa with full eligibility to sponsor spouse and children.</li>
             </ul>
           </div>
        </div>

        <div class="visa-card animate-on-scroll delay-1">
           <div class="visa-img-container">
             <img src="assets/images/visa_ssw_japan.png" alt="Specified Skilled Worker Visa">
           </div>
           <div class="visa-content">
             <h3 class="visa-title" style="color: #0ea5e9;"><i class="fa-solid fa-helmet-safety"></i> 2. Specified Skilled Worker</h3>
             <p class="visa-desc">For candidates with vocational skills passing field tests (Degree optional).</p>
             <ul class="visa-list">
               <li><i class="fa-solid fa-check text-success"></i> <strong>Requirement:</strong> Vocational Skill Test + JLPT N4</li>
               <li><i class="fa-solid fa-check text-success"></i> <strong>Key Benefit:</strong> Direct employment in high-demand sectors. Type 2 leads to long-term residency rights.</li>
             </ul>
           </div>
        </div>
        
      </div>
    </div>
    </div>
  </section>

  <!-- HOW WE SUPPORT -->
  <section class="section" style="padding: 6rem 0;">
    <div class="container">
      <div class="text-center animate-on-scroll" style="margin-bottom: 4rem;">
        <span class="section__tag" style="color: var(--pte-primary); background: rgba(139, 92, 246, 0.1);">Our Framework</span>
        <h2 class="section__title">Career Assistance <span>Framework</span></h2>
        <p class="section__subtitle" style="max-width: 600px; margin: 0 auto;">We provide complete step-by-step assistance to help you secure a verified position in Japan.</p>
      </div>
      
      <style>
.process-path { display: flex; justify-content: space-between; position: relative; margin-top: 4rem; margin-bottom: 2rem; }
.process-path::before { content: ''; position: absolute; top: 40px; left: 5%; right: 5%; height: 4px; background: rgba(139, 92, 246, 0.2); z-index: 0; border-radius: 4px; }
.process-step { flex: 1; text-align: center; position: relative; z-index: 1; padding: 0 1rem; }
.process-icon { width: 80px; height: 80px; border-radius: 50%; background: white; margin: 0 auto 1.5rem; display: flex; align-items: center; justify-content: center; font-size: 2rem; color: var(--pte-primary); box-shadow: 0 10px 25px rgba(139, 92, 246, 0.2); border: 4px solid #f8fafc; position: relative; transition: transform 0.3s ease, box-shadow 0.3s ease; cursor: pointer; }
.process-step:hover .process-icon { transform: scale(1.1); box-shadow: 0 15px 35px rgba(139, 92, 246, 0.4); background: var(--pte-gradient); color: white; border-color: white; }
.process-step-num { position: absolute; top: -5px; right: -5px; width: 28px; height: 28px; background: var(--dark); color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 0.9rem; font-weight: 800; border: 3px solid white; box-shadow: 0 2px 5px rgba(0,0,0,0.2); }
.process-title { font-size: 1.3rem; font-weight: 800; color: var(--dark); margin-bottom: 0.75rem; font-family: 'Plus Jakarta Sans', sans-serif; transition: color 0.3s ease; }
.process-step:hover .process-title { color: var(--pte-primary); }
.process-desc { font-size: 1rem; color: var(--gray); line-height: 1.6; }

@media (max-width: 768px) {
  .process-path { flex-direction: column; gap: 3rem; }
  .process-path::before { top: 0; left: 40px; width: 4px; height: 100%; right: auto; }
  .process-step { display: flex; text-align: left; align-items: flex-start; gap: 1.5rem; }
  .process-icon { margin: 0; flex: 0 0 80px; }
  .process-step-num { right: auto; left: -5px; }
}
</style>

      <div class="process-path">
        <div class="process-step animate-on-scroll">
          <div class="process-icon">
            <i class="fa-solid fa-file-word"></i>
            <div class="process-step-num">1</div>
          </div>
          <div>
            <h4 class="process-title">Resume Formatting</h4>
            <p class="process-desc">We translate and format your academic/work history into official Japanese standards (Rirekisho).</p>
          </div>
        </div>
        
        <div class="process-step animate-on-scroll delay-1">
          <div class="process-icon">
            <i class="fa-solid fa-comments"></i>
            <div class="process-step-num">2</div>
          </div>
          <div>
            <h4 class="process-title">Mock Interviews</h4>
            <p class="process-desc">Intensive practice for cultural interview etiquette and self-introductions (Jikoshoukai).</p>
          </div>
        </div>
        
        <div class="process-step animate-on-scroll delay-2">
          <div class="process-icon">
            <i class="fa-solid fa-handshake"></i>
            <div class="process-step-num">3</div>
          </div>
          <div>
            <h4 class="process-title">Agency Matching</h4>
            <p class="process-desc">Connecting you directly with verified Japanese employers and registered support organizations.</p>
          </div>
        </div>
        
        <div class="process-step animate-on-scroll delay-3">
          <div class="process-icon">
            <i class="fa-solid fa-passport"></i>
            <div class="process-step-num">4</div>
          </div>
          <div>
            <h4 class="process-title">Visa Documentation</h4>
            <p class="process-desc">Complete assistance with Certificate of Eligibility (COE) submission and embassy filing.</p>
          </div>
        </div>
      </div>
</div>
    </div>
  </section>

  <!-- FORM CTA -->
  <section id="register" class="section bg-light" style="padding: 6rem 0;">
    <div class="container animate-on-scroll">
      <div style="background: white; padding: 4rem; border-radius: 30px; box-shadow: 0 20px 50px rgba(0,0,0,0.05); max-width: 800px; margin: 0 auto;">
        <h2 style="font-size: 2.2rem; margin-bottom: 1rem; font-weight: 800; font-family: 'Plus Jakarta Sans', sans-serif; text-align: center;">Register for Career Placement</h2>
        <p style="font-size: 1.1rem; color: var(--gray); text-align: center; margin-bottom: 3rem;">Start your process by submitting your resume and language background below.</p>
        
        <form class="enquiry-form" id="workLeadForm" onsubmit="return handleFormSubmit(event)">
          <input type="hidden" name="form_type" value="course_enquiry">
          <input type="hidden" name="destination" value="Work in Japan">
          
          <div class="grid grid--2 gap--2" style="margin-bottom: 1rem;">
            <div class="form-group">
              <input type="text" name="first_name" class="form-control" placeholder="Full Name" required>
            </div>
            <div class="form-group">
              <input type="tel" name="phone" class="form-control" placeholder="Phone / WhatsApp Number" required>
            </div>
          </div>
          
          <div class="form-group" style="margin-bottom: 1rem;">
            <input type="email" name="email" class="form-control" placeholder="Email Address" required>
          </div>
          
          <div class="form-group" style="margin-bottom: 1rem;">
            <select name="query" class="form-control" required>
              <option value="" disabled selected>Highest Qualification (Degree / Diploma / Vocational)</option>
              <option value="degree">Bachelor's/Master's Degree</option>
              <option value="diploma">Diploma</option>
              <option value="vocational">Vocational Training</option>
              <option value="other">Other</option>
            </select>
          </div>
          
          <div class="grid grid--2 gap--2" style="margin-bottom: 2rem;">
            <div class="form-group">
              <select name="message" class="form-control" required>
                <option value="" disabled selected>Current Japanese Level</option>
                <option value="none">Beginner (None)</option>
                <option value="n5">JLPT N5</option>
                <option value="n4">JLPT N4</option>
                <option value="n3">JLPT N3</option>
                <option value="n2">JLPT N2</option>
                <option value="n1">JLPT N1</option>
              </select>
            </div>
            <div class="form-group">
              <select name="target_field" class="form-control" required>
                <option value="" disabled selected>Target Work Field</option>
                <option value="it">Information Technology</option>
                <option value="healthcare">Caregiving & Healthcare</option>
                <option value="hospitality">Hospitality & Tourism</option>
                <option value="manufacturing">Manufacturing</option>
                <option value="construction">Construction</option>
                <option value="other">Other</option>
              </select>
            </div>
          </div>
          
          <div class="text-center">
             <button type="submit" class="btn btn--primary btn--lg pulse-btn" style="background: var(--pte-primary); width: 100%;">Submit Career Profile</button>
          </div>
        </form>
      </div>
    </div>
  </section>

</main>

<?php require_once 'includes/footer.php'; ?>
