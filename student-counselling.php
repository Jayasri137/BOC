<?php
require_once 'includes/config.php';
$pageTitle = 'Free Study Abroad Counselling in Coimbatore | Bluestone Overseas';
$pageDesc = 'Get free study abroad counselling in Coimbatore. Expert guidance for admissions, student visas, scholarships, IELTS, PTE and top universities abroad.';
require_once 'includes/header.php';
$pageHeroImage = 'assets/images/pr.png';
?>
<main>
  <!-- 1. Hero Section -->
  <section class="section" style="position: relative; overflow: hidden; padding-top: 1.5rem; padding-bottom: 5rem; background-color: #ffffff;">
    <div style="position: absolute; top: -100px; left: -100px; width: 400px; height: 400px; background: radial-gradient(circle, rgba(236,72,153,0.1) 0%, transparent 70%); border-radius: 50%; z-index: 1;"></div>
    
    <div class="container" style="position: relative; z-index: 2;">
      <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 4rem; align-items: center;">
        
        <!-- Text Side -->
        <div class="animate-on-scroll">
          <h1 style="font-size: clamp(1.5rem, 4vw, 3rem); line-height: 1.15; margin-bottom: 1.5rem; color: #1e3a8a; font-weight: 800;">
            Your Future Deserves <br>
            More Than a <span style="color: #ec4899;">Guess.</span>
          </h1>
          <p style="color: #334155; font-size: 1.3rem; font-weight: 700; margin-bottom: 1rem;">
            Personalised Study Abroad Counselling That Starts With You.
          </p>
          <p style="color: var(--gray); font-size: 1.15rem; line-height: 1.7; margin-bottom: 2.5rem; max-width: 600px;">
            Choosing the right country, course and university can be overwhelming. Our counsellors understand your academic profile, career goals, interests and budget to help you discover the right path forward.
          </p>
          <a href="consultation.php" class="btn btn--primary btn--lg pulse-btn">Book Your Free Counselling <i class="fa-solid fa-arrow-right" style="margin-left: 0.5rem;"></i></a>
          <div style="margin-top: 1.5rem; font-size: 0.95rem; color: #64748b; font-weight: 500;">
          </div>
        </div>

        <!-- Image Side -->
        <div class="animate-on-scroll" style="position: relative;">
          <div style="position: absolute; bottom: -30px; right: -30px; width: 250px; height: 250px; background: radial-gradient(circle, rgba(14,165,233,0.2) 0%, transparent 70%); border-radius: 50%; z-index: -1;"></div>
          <img src="assets/images/img.png" alt="Student Counselling" style="width: 100%; height: auto; display: block; max-width: 600px; margin: 0 auto; filter: drop-shadow(0 20px 40px rgba(0,0,0,0.1));">
        </div>

      </div>
    </div>
  </section>


  <!-- 3. Why Counselling Matters (Premium Bento Design) -->
  <style>
    .counselling-bento {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 1.5rem;
        margin-top: 3rem;
    }
    .bento-item {
        border-radius: 24px;
        padding: 2rem;
        position: relative;
        overflow: hidden;
        transition: transform 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275), box-shadow 0.4s ease;
        display: flex;
        flex-direction: column;
        justify-content: center;
        border: 1px solid rgba(255,255,255,0.5);
    }
    .bento-item:hover {
        transform: translateY(-8px);
        box-shadow: 0 20px 40px rgba(0,0,0,0.08);
        z-index: 10;
    }
    .bento-eligibility {
        grid-column: span 2;
        display: flex;
        flex-direction: row;
        align-items: center;
        gap: 2rem;
    }
    
    .bento-icon {
        width: 60px;
        height: 60px;
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        margin-bottom: 1.5rem;
        background: rgba(255,255,255,0.9);
        box-shadow: 0 8px 16px rgba(0,0,0,0.05);
    }
    .bento-eligibility .bento-icon {
        margin-bottom: 0;
        width: 80px;
        height: 80px;
        font-size: 2rem;
        flex-shrink: 0;
    }
    
    .bento-item h4 {
        font-size: 1.35rem;
        font-weight: 800;
        color: #1e293b;
        margin-bottom: 0.5rem;
    }
    .bento-item p {
        font-size: 0.95rem;
        color: #475569;
        line-height: 1.6;
        margin: 0;
    }

    /* specific colors and unique shapes */
    .bg-country { 
        background: linear-gradient(135deg, #fdf2f8, #fce7f3); 
        border-radius: 40px 10px 40px 10px;
    }
    .bg-country .bento-icon { color: #ec4899; }
    
    .bg-course { 
        background: linear-gradient(135deg, #f0f9ff, #e0f2fe); 
        border-radius: 10px 40px 10px 40px;
    }
    .bg-course .bento-icon { color: #0ea5e9; }
    
    .bg-university { 
        background: linear-gradient(135deg, #f0fdf4, #dcfce7); 
        border-radius: 40px 40px 10px 10px;
    }
    .bg-university .bento-icon { color: #10b981; }
    
    .bg-budget { 
        background: linear-gradient(135deg, #fffbeb, #fef3c7); 
        border-radius: 10px 10px 40px 40px;
    }
    .bg-budget .bento-icon { color: #f59e0b; }
    
    .bg-eligibility { 
        background: linear-gradient(135deg, #faf5ff, #f3e8ff); 
        border-radius: 30px;
    }
    .bg-eligibility .bento-icon { color: #8b5cf6; }

    @media (max-width: 992px) {
        .counselling-bento {
            grid-template-columns: repeat(2, 1fr);
        }
    }
    @media (max-width: 768px) {
        .counselling-bento {
            grid-template-columns: 1fr;
        }
        .bento-eligibility {
            grid-column: span 1;
            flex-direction: column;
            text-align: center;
            gap: 1rem;
        }
    }
  </style>
  <section class="section" style="background-color: #ffffff; padding: 4rem 1rem;">
    <div class="container animate-on-scroll" style="max-width: 80rem; margin: 0 auto; background: linear-gradient(135deg, #1e293b, #0f172a); border-radius: 24px; box-shadow: 0 25px 50px rgba(0,0,0,0.25); padding: 5rem 2rem; position: relative; overflow: hidden; border: 1px solid rgba(255,255,255,0.05);">
      
      <!-- Blob 1 -->
      <div style="position: absolute; top: -100px; left: -100px; width: 400px; height: 400px; background: rgba(56, 189, 248, 0.15); border-radius: 50%; filter: blur(60px); pointer-events: none;"></div>
      <!-- Blob 2 -->
      <div style="position: absolute; bottom: -150px; right: -50px; width: 500px; height: 500px; background: rgba(236, 72, 153, 0.15); border-radius: 50%; filter: blur(70px); pointer-events: none;"></div>

      <div class="text-center" style="position: relative; z-index: 2;">
        <span class="section__tag" style="background: rgba(255,255,255,0.1); color: #f8fafc; border: 1px solid rgba(255,255,255,0.2);">Why Counselling Matters</span>
        <h2 class="section__title" style="color: white;">Thousands of options.<br>One path that's <span style="color: #38bdf8; background: none; -webkit-text-fill-color: initial;">right for you.</span></h2>
        <p class="section__subtitle" style="max-width: 600px; margin: 0 auto; color: #cbd5e1;">We help you move beyond random university searches and make informed decisions.</p>
      </div>

      <div class="counselling-bento" style="position: relative; z-index: 2;">
        
        <a href="index.php#destinations" class="bento-item bg-country animate-on-scroll" style="text-decoration:none; color:inherit;">
            <div class="bento-icon"><i class="fa-solid fa-earth-americas"></i></div>
            <h4>Country</h4>
            <p>Find destinations that match your goals, budget, and future plans.</p>
        </a>

        <a href="courses.php" class="bento-item bg-course animate-on-scroll delay-1" style="text-decoration:none; color:inherit;">
            <div class="bento-icon"><i class="fa-solid fa-book-open-reader"></i></div>
            <h4>Course</h4>
            <p>Explore programmes that connect your education with your ambitions.</p>
        </a>

        <a href="universities.php" class="bento-item bg-university animate-on-scroll delay-2" style="text-decoration:none; color:inherit;">
            <div class="bento-icon"><i class="fa-solid fa-building-columns"></i></div>
            <h4>University</h4>
            <p>Compare universities based on course, location, cost and suitability.</p>
        </a>

        <a href="scholarships.php" class="bento-item bg-budget animate-on-scroll" style="text-decoration:none; color:inherit;">
            <div class="bento-icon"><i class="fa-solid fa-wallet"></i></div>
            <h4>Budget</h4>
            <p>Understand tuition, living costs, scholarships and finance options.</p>
        </a>

        <a href="test-prep.php" class="bento-item bento-eligibility bg-eligibility animate-on-scroll delay-1" style="text-decoration:none; color:inherit;">
            <div class="bento-icon"><i class="fa-solid fa-clipboard-check"></i></div>
            <div>
                <h4>Eligibility & Profile Building</h4>
                <p>Identify realistic options based on your academic profile and requirements, and learn how to improve your chances.</p>
            </div>
        </a>

      </div>
    </div>
  </section>

  <!-- 4. What Happens in Your Session? (Light UI Redesign) -->
  <section class="section" style="background-color: #f8fafc; position: relative; overflow: hidden;">
    <div style="position: absolute; right: -5%; top: -10%; font-size: 30rem; color: rgba(0,0,0,0.02); z-index: 0; font-weight: 900; line-height: 1;"><i class="fa-regular fa-comments"></i></div>
    
    <div class="container" style="position: relative; z-index: 1;">
      <div class="text-center animate-on-scroll">
        <span class="section__tag">WHAT HAPPENS IN YOUR SESSION?</span>
        <h2 class="section__title">One conversation. A clearer <span>direction.</span></h2>
      </div>

      <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 4rem; margin-top: 4rem; align-items: center;">
        
        <!-- Image Side -->
        <div class="animate-on-scroll delay-2" style="position: relative;">
            <div style="position: absolute; top: -20px; right: -20px; bottom: 20px; left: 20px; background: linear-gradient(135deg, #e0f2fe, #fce7f3); border-radius: 24px; z-index: 0;"></div>
            <img src="assets/images/s3.jpg" alt="Session in Progress" style="width: 100%; height: 100%; object-fit: cover; border-radius: 24px; min-height: 400px; box-shadow: 0 20px 40px rgba(0,0,0,0.1); position: relative; z-index: 1;">
        </div>

        <!-- Content Side -->
        <div style="display: flex; flex-direction: column; gap: 1.5rem;">
            <div class="animate-on-scroll hover-lift" style="background: #075985; border: 1px solid #0369a1; padding: 2rem; border-radius: 20px; box-shadow: 0 15px 35px rgba(7,89,133,0.2); position: relative; overflow: hidden; border-left: 6px solid #38bdf8;">
                <div style="position: absolute; right: -10px; top: -10px; font-size: 6rem; font-weight: 900; color: rgba(255,255,255,0.05); line-height: 1; user-select: none;">01</div>
                <div style="font-size: 1.25rem; font-weight: 800; color: #ffffff; margin-bottom: 0.5rem; position: relative; z-index: 1;">Understand</div>
                <p style="color: #e0f2fe; font-size: 0.95rem; line-height: 1.6; margin: 0; position: relative; z-index: 1;">We assess your academic background, interests, goals and preferences.</p>
            </div>

            <div class="animate-on-scroll hover-lift delay-1" style="background: #4c1d95; border: 1px solid #5b21b6; padding: 2rem; border-radius: 20px; box-shadow: 0 15px 35px rgba(76,29,149,0.2); position: relative; overflow: hidden; border-left: 6px solid #a78bfa; margin-left: 2rem;">
                <div style="position: absolute; right: -10px; top: -10px; font-size: 6rem; font-weight: 900; color: rgba(255,255,255,0.05); line-height: 1; user-select: none;">02</div>
                <div style="font-size: 1.25rem; font-weight: 800; color: #ffffff; margin-bottom: 0.5rem; position: relative; z-index: 1;">Explore</div>
                <p style="color: #ede9fe; font-size: 0.95rem; line-height: 1.6; margin: 0; position: relative; z-index: 1;">Discover suitable countries, courses and study options tailored for you.</p>
            </div>

            <div class="animate-on-scroll hover-lift delay-2" style="background: #831843; border: 1px solid #9d174d; padding: 2rem; border-radius: 20px; box-shadow: 0 15px 35px rgba(131,24,67,0.2); position: relative; overflow: hidden; border-left: 6px solid #f472b6;">
                <div style="position: absolute; right: -10px; top: -10px; font-size: 6rem; font-weight: 900; color: rgba(255,255,255,0.05); line-height: 1; user-select: none;">03</div>
                <div style="font-size: 1.25rem; font-weight: 800; color: #ffffff; margin-bottom: 0.5rem; position: relative; z-index: 1;">Shortlist</div>
                <p style="color: #fce7f3; font-size: 0.95rem; line-height: 1.6; margin: 0; position: relative; z-index: 1;">Compare and identify options that perfectly fit your profile and budget.</p>
            </div>

            <div class="animate-on-scroll hover-lift delay-3" style="background: #78350f; border: 1px solid #92400e; padding: 2rem; border-radius: 20px; box-shadow: 0 15px 35px rgba(120,53,15,0.2); position: relative; overflow: hidden; border-left: 6px solid #fbbf24; margin-left: 2rem;">
                <div style="position: absolute; right: -10px; top: -10px; font-size: 6rem; font-weight: 900; color: rgba(255,255,255,0.05); line-height: 1; user-select: none;">04</div>
                <div style="font-size: 1.25rem; font-weight: 800; color: #ffffff; margin-bottom: 0.5rem; position: relative; z-index: 1;">Plan</div>
                <p style="color: #fef3c7; font-size: 0.95rem; line-height: 1.6; margin: 0; position: relative; z-index: 1;">Understand the next steps—from applications and scholarships to visas and preparation.</p>
            </div>
        </div>

      </div>
    </div>
  </section>

  <!-- 5. What Can Bluestone Help You With? (Minimalist Clean UI) -->
  <style>
    .minimal-section {
        background-color: #ffffff;
        color: #1e293b;
        position: relative;
        padding: 6rem 0;
    }
    .minimal-item {
        display: flex;
        flex-direction: column;
        align-items: flex-start;
        text-align: left;
        gap: 1.25rem;
        padding: 1.5rem;
        transition: transform 0.4s ease;
    }
    .minimal-item:hover {
        transform: translateY(-5px);
    }
    .minimal-icon-wrapper {
        width: 80px;
        height: 80px;
        position: relative;
        margin-bottom: 0.5rem;
    }
    .minimal-icon-wrapper::after {
        content: '';
        position: absolute;
        bottom: 5px; left: 10%; right: 10%;
        height: 10px;
        background: rgba(0,0,0,0.1);
        border-radius: 50%;
        filter: blur(5px);
        z-index: -1;
        transition: opacity 0.4s ease, transform 0.4s ease;
    }
    .minimal-item:hover .minimal-icon-wrapper::after {
        opacity: 0.5;
        transform: scale(0.8) translateY(5px);
    }
    .minimal-icon-wrapper img {
        width: 100%;
        height: 100%;
        object-fit: contain;
        transition: transform 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    }
    .minimal-item:hover .minimal-icon-wrapper img {
        transform: translateY(-5px) scale(1.05);
    }
    .minimal-item h4 {
        font-size: 1.35rem;
        font-weight: 800;
        color: #0f172a;
        margin-bottom: 0.5rem;
        letter-spacing: -0.01em;
        position: relative;
        display: inline-block;
    }
    .minimal-item h4::after {
        content: '';
        position: absolute;
        bottom: -5px; left: 0;
        width: 0; height: 2px;
        background-color: #ec4899;
        transition: width 0.4s ease;
    }
    .minimal-item:hover h4::after {
        width: 30px;
    }
    .minimal-item p {
        font-size: 1.05rem;
        color: #475569;
        line-height: 1.6;
        margin: 0;
    }
  </style>
  <section class="section minimal-section">
    <div class="container" style="position: relative; z-index: 1;">
      <div class="text-center animate-on-scroll">
        <span class="section__tag" style="background: #f8fafc; color: #64748b; border: 1px solid #e2e8f0;">Our Expertise</span>
        <h2 class="section__title" style="color: #0f172a;">Guidance that goes beyond choosing a <span style="color: #ec4899;">university.</span></h2>
      </div>

      <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 4rem 3rem; margin-top: 5rem;">
        
        <div class="minimal-item animate-on-scroll">
            <div class="minimal-icon-wrapper">
                <img src="assets/images/service_guidance_3d.png" alt="Career & Course Guidance">
            </div>
            <div>
                <h4>Career & Course Guidance</h4>
                <p>Find study options aligned with your interests and career plans.</p>
            </div>
        </div>

        <div class="minimal-item animate-on-scroll delay-1">
            <div class="minimal-icon-wrapper">
                <img src="assets/images/service_university_3d.png" alt="University Selection">
            </div>
            <div>
                <h4>University Selection</h4>
                <p>Shortlist universities based your profile, preferences and future goals.</p>
            </div>
        </div>

        <div class="minimal-item animate-on-scroll delay-2">
            <div class="minimal-icon-wrapper">
                <img src="assets/images/service_coaching_3d.png" alt="Application Planning">
            </div>
            <div>
                <h4>Application Planning</h4>
                <p>Understand admission requirements, documents and timelines.</p>
            </div>
        </div>

        <div class="minimal-item animate-on-scroll">
            <div class="minimal-icon-wrapper">
                <img src="assets/images/service_financing_3d.png" alt="Scholarships & Finance">
            </div>
            <div>
                <h4>Scholarships & Finance</h4>
                <p>Explore scholarships, funding and education loan options.</p>
            </div>
        </div>

        <div class="minimal-item animate-on-scroll delay-1">
            <div class="minimal-icon-wrapper">
                <img src="assets/images/service_visa_3d.png" alt="Visa Guidance">
            </div>
            <div>
                <h4>Visa Guidance</h4>
                <p>Understand documentation and prepare for the visa process.</p>
            </div>
        </div>

        <div class="minimal-item animate-on-scroll delay-2">
            <div class="minimal-icon-wrapper">
                <img src="assets/images/plane.png" alt="Pre-Departure Support">
            </div>
            <div>
                <h4>Pre-Departure Support</h4>
                <p>Get ready for accommodation, travel, banking and life abroad.</p>
            </div>
        </div>

      </div>
    </div>
  </section>

  <!-- 6. Who Can Book a Session? -->
  <section class="section" style="background-color: #fffbeb;">
    <div class="container">
      <div class="text-center animate-on-scroll">
        <span class="section__tag">Who Can Book?</span>
        <h2 class="section__title">Wherever you are in your journey, we're <span>here to help.</span></h2>
      </div>

      <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 2rem; margin-top: 4rem;">
        
        <!-- Card 1 -->
        <div class="animate-on-scroll" style="background: white; border-radius: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.04); overflow: hidden; position: relative; padding: 2.5rem 2rem 4rem; display: flex; flex-direction: column;">
            <div style="background: #b45309; width: 60px; height: 60px; border-radius: 16px; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; color: white; margin-bottom: 1.5rem; box-shadow: 0 8px 20px rgba(180, 83, 9, 0.3);">
                <i class="fa-solid fa-graduation-cap"></i>
            </div>
            <h4 style="font-size: 1.25rem; font-weight: 800; color: #1e293b; margin-bottom: 1rem;">Class 12 Students</h4>
            <p style="font-size: 0.95rem; color: #64748b; line-height: 1.6; margin: 0; position: relative; z-index: 1;">Explore undergraduate courses and destinations.</p>
            <svg viewBox="0 0 100 25" preserveAspectRatio="none" style="position: absolute; bottom: -1px; left: 0; width: 100%; height: 30px; z-index: 0; fill: #b45309; display: block;">
                <path d="M0,25 C30,10 70,25 100,5 L100,25 L0,25 Z"></path>
            </svg>
        </div>

        <!-- Card 2 -->
        <div class="animate-on-scroll delay-1" style="background: white; border-radius: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.04); overflow: hidden; position: relative; padding: 2.5rem 2rem 4rem; display: flex; flex-direction: column;">
            <div style="background: #1d4ed8; width: 60px; height: 60px; border-radius: 16px; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; color: white; margin-bottom: 1.5rem; box-shadow: 0 8px 20px rgba(29, 78, 216, 0.3);">
                <i class="fa-solid fa-user-tie"></i>
            </div>
            <h4 style="font-size: 1.25rem; font-weight: 800; color: #1e293b; margin-bottom: 1rem;">Graduates</h4>
            <p style="font-size: 0.95rem; color: #64748b; line-height: 1.6; margin: 0; position: relative; z-index: 1;">Find postgraduate programmes that support your career goals.</p>
            <svg viewBox="0 0 100 25" preserveAspectRatio="none" style="position: absolute; bottom: -1px; left: 0; width: 100%; height: 30px; z-index: 0; fill: #1d4ed8; display: block;">
                <path d="M0,25 C30,10 70,25 100,5 L100,25 L0,25 Z"></path>
            </svg>
        </div>

        <!-- Card 3 -->
        <div class="animate-on-scroll delay-2" style="background: white; border-radius: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.04); overflow: hidden; position: relative; padding: 2.5rem 2rem 4rem; display: flex; flex-direction: column;">
            <div style="background: #be123c; width: 60px; height: 60px; border-radius: 16px; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; color: white; margin-bottom: 1.5rem; box-shadow: 0 8px 20px rgba(190, 18, 60, 0.3);">
                <i class="fa-solid fa-briefcase"></i>
            </div>
            <h4 style="font-size: 1.25rem; font-weight: 800; color: #1e293b; margin-bottom: 1rem;">Working Professionals</h4>
            <p style="font-size: 0.95rem; color: #64748b; line-height: 1.6; margin: 0; position: relative; z-index: 1;">Explore master's, MBA and career-focused programmes abroad.</p>
            <svg viewBox="0 0 100 25" preserveAspectRatio="none" style="position: absolute; bottom: -1px; left: 0; width: 100%; height: 30px; z-index: 0; fill: #be123c; display: block;">
                <path d="M0,25 C30,10 70,25 100,5 L100,25 L0,25 Z"></path>
            </svg>
        </div>

        <!-- Card 4 -->
        <div class="animate-on-scroll delay-3" style="background: white; border-radius: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.04); overflow: hidden; position: relative; padding: 2.5rem 2rem 4rem; display: flex; flex-direction: column;">
            <div style="background: #047857; width: 60px; height: 60px; border-radius: 16px; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; color: white; margin-bottom: 1.5rem; box-shadow: 0 8px 20px rgba(4, 120, 87, 0.3);">
                <i class="fa-solid fa-users"></i>
            </div>
            <h4 style="font-size: 1.25rem; font-weight: 800; color: #1e293b; margin-bottom: 1rem;">Parents</h4>
            <p style="font-size: 0.95rem; color: #64748b; line-height: 1.6; margin: 0; position: relative; z-index: 1;">Understand costs, destinations, safety and the overall study-abroad journey.</p>
            <svg viewBox="0 0 100 25" preserveAspectRatio="none" style="position: absolute; bottom: -1px; left: 0; width: 100%; height: 30px; z-index: 0; fill: #047857; display: block;">
                <path d="M0,25 C30,10 70,25 100,5 L100,25 L0,25 Z"></path>
            </svg>
        </div>

      </div>
      
      <div class="text-center animate-on-scroll" style="margin-top: 3rem;">
          <h4 style="font-size: 1.3rem; color: #334155; margin-bottom: 1rem;">Exploring Your Options?</h4>
          <p style="font-size: 1rem; color: #64748b; margin-bottom: 1.5rem;">Not sure what or where to study? Start with a conversation.</p>
          <a href="consultation.php" class="btn btn--outline">Start a Conversation</a>
      </div>
    </div>
  </section>

  <!-- 7. Before Your Session & Document Checklist -->
  <section class="section" style="background-color: #ffffff;">
    <div class="container">
      <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(400px, 1fr)); gap: 4rem;">
        
        <!-- Left: Before Session -->
        <div class="animate-on-scroll">
            <span class="section__tag">Preparation</span>
            <h2 class="section__title" style="margin-bottom: 1rem;">Come with questions. <span>Leave with clarity.</span></h2>
            <p style="font-size: 1rem; color: #64748b; margin-bottom: 2rem; line-height: 1.6;">You don't need to have everything figured out. But having a few details ready will help us make your session more useful.</p>
            
            <div style="background: #f8fafc; border-radius: 16px; padding: 2rem; border-left: 5px solid #3b82f6;">
                <h4 style="font-size: 1.2rem; font-weight: 800; color: #1e293b; margin-bottom: 1.5rem;"><i class="fa-regular fa-lightbulb" style="color: #3b82f6; margin-right: 10px;"></i> Think About:</h4>
                <ul style="list-style: none; padding: 0; margin: 0; display: flex; flex-direction: column; gap: 0.8rem;">
                    <li style="display: flex; gap: 10px; color: #334155;"><i class="fa-solid fa-angle-right" style="color: #3b82f6; margin-top: 4px;"></i> What have you studied?</li>
                    <li style="display: flex; gap: 10px; color: #334155;"><i class="fa-solid fa-angle-right" style="color: #3b82f6; margin-top: 4px;"></i> What would you like to study next?</li>
                    <li style="display: flex; gap: 10px; color: #334155;"><i class="fa-solid fa-angle-right" style="color: #3b82f6; margin-top: 4px;"></i> Which countries interest you?</li>
                    <li style="display: flex; gap: 10px; color: #334155;"><i class="fa-solid fa-angle-right" style="color: #3b82f6; margin-top: 4px;"></i> When would you like to go?</li>
                    <li style="display: flex; gap: 10px; color: #334155;"><i class="fa-solid fa-angle-right" style="color: #3b82f6; margin-top: 4px;"></i> What is your approximate budget?</li>
                    <li style="display: flex; gap: 10px; color: #334155;"><i class="fa-solid fa-angle-right" style="color: #3b82f6; margin-top: 4px;"></i> What are your career goals?</li>
                </ul>
            </div>
            <p style="font-size: 0.95rem; color: #64748b; margin-top: 1.5rem; font-style: italic;">Not sure about these? That's okay. We'll help you figure them out.</p>
        </div>

        <!-- Right: Document Checklist -->
        <div class="animate-on-scroll delay-1">
            <span class="section__tag">Checklist</span>
            <h2 class="section__title" style="margin-bottom: 1rem;">Bring what you have. <span>We'll guide the rest.</span></h2>
            <p style="font-size: 1rem; color: #64748b; margin-bottom: 2rem; line-height: 1.6;">For a more meaningful profile discussion, keep these documents/details ready where applicable:</p>
            
            <div style="display: flex; flex-direction: column; gap: 1.5rem;">
                
                <div style="background: white; border: 1px solid #e2e8f0; border-radius: 12px; padding: 1.5rem; box-shadow: 0 4px 10px rgba(0,0,0,0.02);">
                    <h5 style="font-size: 1.1rem; font-weight: 700; color: #1e293b; margin-bottom: 0.8rem;"><i class="fa-solid fa-graduation-cap" style="color: #ec4899; margin-right: 8px;"></i> Academic Documents</h5>
                    <ul style="list-style: none; padding: 0; margin: 0; font-size: 0.9rem; color: #64748b; display: flex; flex-direction: column; gap: 5px;">
                        <li>• Class 10 & 12 certificates/mark sheets</li>
                        <li>• Diploma / Bachelor's / Master's certificates</li>
                        <li>• Academic transcripts</li>
                    </ul>
                </div>

                <div style="background: white; border: 1px solid #e2e8f0; border-radius: 12px; padding: 1.5rem; box-shadow: 0 4px 10px rgba(0,0,0,0.02);">
                    <h5 style="font-size: 1.1rem; font-weight: 700; color: #1e293b; margin-bottom: 0.8rem;"><i class="fa-solid fa-language" style="color: #ec4899; margin-right: 8px;"></i> English Language Scores</h5>
                    <ul style="list-style: none; padding: 0; margin: 0; font-size: 0.9rem; color: #64748b; display: flex; flex-direction: column; gap: 5px;">
                        <li>• IELTS / PTE / TOEFL scorecard, if available</li>
                    </ul>
                </div>

                <div style="background: white; border: 1px solid #e2e8f0; border-radius: 12px; padding: 1.5rem; box-shadow: 0 4px 10px rgba(0,0,0,0.02);">
                    <h5 style="font-size: 1.1rem; font-weight: 700; color: #1e293b; margin-bottom: 0.8rem;"><i class="fa-regular fa-file-lines" style="color: #ec4899; margin-right: 8px;"></i> Additional Documents</h5>
                    <ul style="list-style: none; padding: 0; margin: 0; font-size: 0.9rem; color: #64748b; display: flex; flex-direction: column; gap: 5px;">
                        <li>• Updated CV / Resume</li>
                        <li>• Passport, if available</li>
                        <li>• Work experience documents, if applicable</li>
                        <li>• Previous visa/refusal details, if applicable</li>
                    </ul>
                </div>

            </div>
            
            <p style="font-size: 0.95rem; color: #64748b; margin-top: 1.5rem; font-style: italic;"><strong>Don't have everything yet?</strong> No problem. You can still speak to our counsellors and understand what you need.</p>
        </div>

      </div>
    </div>
  </section>

  <!-- 8. CTA section -->
  <section class="section" style="background-color: #f8fafc;">
    <div class="container animate-on-scroll">
      <div style="background: linear-gradient(135deg, #1e293b, #0f172a); padding: 4rem 2rem; border-radius: var(--radius-lg); text-align: center; color: white; box-shadow: var(--shadow-lg); border: 1px solid rgba(255,255,255,0.1);">
        
        <div style="font-size: 0.85rem; font-weight: 700; letter-spacing: 2px; color: #38bdf8; margin-bottom: 1.5rem;">
            DISCOVER → EXPLORE → SHORTLIST → PLAN → APPLY → FLY
        </div>
        
        <h2 style="font-size: clamp(2rem, 4vw, 2.5rem); margin-bottom: 1rem; font-weight: 800;">Your Journey Starts Here</h2>
        <p style="font-size: 1.15rem; color: #cbd5e1; max-width: 600px; margin: 0 auto 2.5rem; line-height: 1.6;">You don't need to know exactly where you're going. You just need to take the first step.</p>
        
        <div style="display: flex; flex-wrap: wrap; justify-content: center; gap: 1rem;">
            <a href="consultation.php" class="btn btn--primary btn--lg pulse-btn">Book Your Free Counselling <i class="fa-solid fa-arrow-right" style="margin-left: 8px;"></i></a>
            <a href="https://wa.me/919342899904" target="_blank" class="btn btn--lg" style="background: #25D366; color: white; border: none;"><i class="fa-brands fa-whatsapp" style="margin-right: 8px; font-size: 1.2rem;"></i> Chat on WhatsApp</a>
        </div>
        
        <div style="margin-top: 2rem; font-size: 0.85rem; color: #64748b;">
            * Visa decisions are made by the respective immigration authorities.
        </div>
      </div>
    </div>
  </section>

</main>
<?php require_once 'includes/footer.php'; ?>
