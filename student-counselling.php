<?php
require_once 'includes/config.php';
$pageTitle = 'Free Study Abroad Counselling in Coimbatore | Bluestone Overseas';
$pageDesc = 'Get free study abroad counselling in Coimbatore. Expert guidance for admissions, student visas, scholarships, IELTS, PTE and top universities abroad.';
require_once 'includes/header.php';
$pageHeroImage = 'assets/images/pr.png';

?>
<main>
  <section class="section" style="position: relative; overflow: hidden; padding-top: 6rem; padding-bottom: 5rem; background-color: #ffffff;">
    <!-- Decorative background blobs -->
    <div style="position: absolute; top: -100px; left: -100px; width: 400px; height: 400px; background: radial-gradient(circle, rgba(236,72,153,0.1) 0%, transparent 70%); border-radius: 50%; z-index: -1;"></div>
    <div style="position: absolute; bottom: -50px; right: -50px; width: 300px; height: 300px; background: radial-gradient(circle, rgba(14,165,233,0.1) 0%, transparent 70%); border-radius: 50%; z-index: -1;"></div>

    <div class="container">
      <div style="text-align: center; max-width: 800px; margin: 0 auto; margin-bottom: 4rem;">
        <div class="animate-on-scroll">
          <div style="display: inline-flex; align-items: center; gap: 0.5rem; background: rgba(236, 72, 153, 0.1); color: var(--primary); padding: 0.5rem 1.25rem; border-radius: 50px; font-weight: 600; font-size: 0.95rem; margin-bottom: 1.5rem; border: 1px solid rgba(236, 72, 153, 0.2);">
            <i class="fa-solid fa-star" style="color: #f59e0b;"></i> Premium Counselling
          </div>
          <h2 style="font-size: clamp(2.5rem, 5vw, 4rem); line-height: 1.15; margin-bottom: 1.5rem; color: var(--dark);">
            Expert Guidance for <br>
            <span style="color: #ea00ffff;">Your Future</span>
          </h2>
          <p style="color: var(--gray); font-size: 1.15rem; line-height: 1.7; margin-bottom: 2.5rem;">
            In association with <strong>Bluestone Overseas</strong> and drawing inspiration from global standards set by leaders like <strong>IDP.com</strong>, we bring you world-class counselling services to fast-track your international education journey.
          </p>
          <a href="consultation.php" class="btn btn--primary btn--lg pulse-btn">Start Free Profile Assessment <i class="fa-solid fa-arrow-right" style="margin-left: 0.5rem;"></i></a>
        </div>
      </div>

      <!-- Feature Pills Centered Grid -->
      <div class="animate-on-scroll delay-1" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 1.5rem;">
          
          <div class="feature-pill feature-pill--center" style="background: linear-gradient(135deg, #0ea5e9, #3b82f6); border: none; color: white;">
            <img src="assets/images/card_profile_3d.png" alt="Profile Assessment" style="width: 80px; height: 80px; object-fit: contain; margin: 0 auto 1rem; filter: drop-shadow(0 10px 20px rgba(0,0,0,0.1)); border-radius: 20px;">
            <div class="feature-pill__text" style="color: white;">Comprehensive Profile Assessment</div>
          </div>
          
          <div class="feature-pill feature-pill--center" style="background: linear-gradient(135deg, #8b5cf6, #d946ef); border: none; color: white;">
            <img src="assets/images/card_career_3d.png" alt="Career Goal" style="width: 80px; height: 80px; object-fit: contain; margin: 0 auto 1rem; filter: drop-shadow(0 10px 20px rgba(0,0,0,0.1)); border-radius: 20px;">
            <div class="feature-pill__text" style="color: white;">Career Goal & Interest Mapping</div>
          </div>
          
          <div class="feature-pill feature-pill--center" style="background: linear-gradient(135deg, #f97316, #f59e0b); border: none; color: white;">
            <img src="assets/images/card_budget_3d.png" alt="Budget Planning" style="width: 80px; height: 80px; object-fit: contain; margin: 0 auto 1rem; filter: drop-shadow(0 10px 20px rgba(0,0,0,0.1)); border-radius: 20px;">
            <div class="feature-pill__text" style="color: white;">Budget Planning & Estimation</div>
          </div>
          
          <div class="feature-pill feature-pill--center" style="background: linear-gradient(135deg, #14b8a6, #0d9488); border: none; color: white;">
            <img src="assets/images/card_country_3d.png" alt="Country Selection" style="width: 80px; height: 80px; object-fit: contain; margin: 0 auto 1rem; filter: drop-shadow(0 10px 20px rgba(0,0,0,0.1)); border-radius: 20px;">
            <div class="feature-pill__text" style="color: white;">Personalized Country Selection</div>
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
      box-shadow: 0 15px 35px rgba(236,72,153,0.15);
      border-color: rgba(236,72,153,0.4);
      background: linear-gradient(rgba(255, 255, 255, 0.5), rgba(255, 255, 255, 0.8)), url('assets/images/premium_card_bg.png');
      background-size: cover;
      background-position: center;
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

  .feature-pill__text {
      font-size: 1.15rem;
      font-weight: 700;
      color: var(--dark);
      line-height: 1.4;
  }
  </style>

<section class="section">
    <div class="container">
      <div class="text-center animate-on-scroll">
        <span class="section__tag">Our Services</span>
        <h2 class="section__title">End-to-End <span>Support</span></h2>
        <p class="section__subtitle" style="max-width: 800px; margin: 0 auto;">From the day you consult us till the day you land in your dream university, we support you all the way. Explore our comprehensive services.</p>
      </div>
      <style>
      .grid-bento {
          display: grid;
          grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
          gap: 2rem;
          margin-top: 4rem;
      }
      
      .sc-card {
          position: relative;
          border-radius: 20px;
          background: #fff;
          box-shadow: 0 10px 30px rgba(0,0,0,0.05);
          overflow: hidden;
          cursor: pointer;
          display: flex;
          flex-direction: column;
          min-height: 320px;
          border: 1px solid #f1f5f9;
          transition: transform 0.4s ease, box-shadow 0.4s ease;
      }
      .sc-card:hover {
          transform: translateY(-10px);
          box-shadow: 0 20px 40px rgba(0,0,0,0.12);
      }

      .sc-img-wrap {
          height: 220px;
          overflow: hidden;
          position: relative;
      }
      .sc-img-wrap img {
          width: 100%;
          height: 100%;
          object-fit: cover;
          transition: transform 0.6s ease;
      }
      .sc-card:hover .sc-img-wrap img {
          transform: scale(1.1);
      }

      .sc-front-title {
          padding: 1.5rem;
          flex-grow: 1;
          display: flex;
          align-items: center;
          justify-content: center;
          background: #fff;
          z-index: 1;
      }
      .sc-front-title h3 {
          font-size: 1.35rem;
          font-weight: 800;
          color: var(--dark);
          margin: 0;
          text-align: center;
      }

      .sc-hover-content {
          position: absolute;
          inset: 0;
          background: var(--theme-grad);
          z-index: 2;
          padding: 2rem;
          display: flex;
          flex-direction: column;
          justify-content: center;
          opacity: 0;
          transform: translateY(100%);
          transition: transform 0.5s cubic-bezier(0.4, 0, 0.2, 1), opacity 0.5s ease;
      }
      .sc-card:hover .sc-hover-content {
          opacity: 1;
          transform: translateY(0);
      }

      .sc-hover-content h4 {
          color: white;
          font-size: 1.4rem;
          font-weight: 800;
          margin-bottom: 1rem;
      }
      .sc-hover-content p {
          color: rgba(255, 255, 255, 0.95);
          font-size: 0.95rem;
          margin-bottom: 1.5rem;
          line-height: 1.5;
      }
      .sc-hover-content ul {
          list-style: none;
          padding: 0;
          margin: 0;
          display: flex;
          flex-direction: column;
          gap: 0.75rem;
      }
      .sc-hover-content li {
          font-size: 0.9rem;
          color: white;
          display: flex;
          align-items: flex-start;
          gap: 0.5rem;
      }
      .sc-hover-content li i {
          color: rgba(255, 255, 255, 0.9);
          margin-top: 0.25rem;
      }
      </style>

      <div class="grid-bento">
          <?php
          $interactive_services = [
              [
                  'title' => 'Counselling',
                  'desc' => 'Make the best academic decision suiting your career choices with our expert guidance and personalized sessions.',
                  'list' => ['Career-oriented counselling', 'Emphasis on futuristic courses', 'Interactive sessions with delegates'],
                  'image' => 'assets/images/SC.png',
                  'grad' => 'linear-gradient(135deg, #0ea5e9, #3b82f6)'
              ],
              [
                  'title' => 'Test Preparation',
                  'desc' => 'Reach your highest potential test score with our certified tutors.',
                  'list' => ['Interactive classrooms', 'Score-oriented mock tests'],
                  'image' => 'assets/images/service_coaching_3d.png',
                  'grad' => 'linear-gradient(135deg, #8b5cf6, #d946ef)'
              ],
              [
                  'title' => 'University Selection',
                  'desc' => 'Choose the ideal course, university & country that perfectly match your preferences.',
                  'list' => ['Academic mapping', '1000+ universities', 'Rankings comparison'],
                  'image' => 'assets/images/RO.png',
                  'grad' => 'linear-gradient(135deg, #f97316, #f59e0b)'
              ],
              [
                  'title' => 'Application & Admission',
                  'desc' => 'Apply smartly in courses that are your right fit.',
                  'list' => ['Flawless applications', 'SOPs & Resumes'],
                  'image' => 'assets/images/Appli.png',
                  'grad' => 'linear-gradient(135deg, #14b8a6, #0d9488)'
              ],
              [
                  'title' => 'Scholarships',
                  'desc' => 'Identify and apply for the most deserving high-value scholarships globally.',
                  'list' => ['Alerts on latest & high value scholarships', 'Assistance for scholarship essays'],
                  'image' => 'assets/images/Offer.png',
                  'grad' => 'linear-gradient(135deg, #f43f5e, #e11d48)'
              ],
              [
                  'title' => 'Internships',
                  'desc' => 'Programs with inbuilt internship opportunities.',
                  'list' => ['Guaranteed roles', 'Stipend guidance'],
                  'image' => 'assets/images/card_career_3d.png',
                  'grad' => 'linear-gradient(135deg, #6366f1, #4f46e5)'
              ],
              [
                  'title' => 'Education Loan',
                  'desc' => 'Finance your studies easily with top banking partners.',
                  'list' => ['20+ Bank Tie-ups', 'Hassle-free process'],
                  'image' => 'assets/images/Fund.png',
                  'grad' => 'linear-gradient(135deg, #10b981, #059669)'
              ],
              [
                  'title' => 'Visa Processing',
                  'desc' => 'Ensure successful visa outcomes with our skilled visa experts and guidance.',
                  'list' => ['Impeccable guidance', 'Excellent success ratio', 'Mock visa interviews'],
                  'image' => 'assets/images/Visa.png',
                  'grad' => 'linear-gradient(135deg, #0284c7, #0369a1)'
              ],
              [
                  'title' => 'Allied Services',
                  'desc' => 'Accommodation, Remittance, Forex and more to make your transition seamless.',
                  'list' => ['Use exchange rates to your advantage', 'Minimum premium, maximum insurance cover'],
                  'image' => 'assets/images/Acc.png',
                  'grad' => 'linear-gradient(135deg, #d946ef, #a21caf)'
              ]
          ];
          
          foreach($interactive_services as $svc):
          ?>
          <div class="sc-card animate-on-scroll" style="--theme-grad: <?= $svc['grad'] ?>;">
              <div class="sc-img-wrap">
                  <img src="<?= $svc['image'] ?>" alt="<?= $svc['title'] ?>">
              </div>
              <div class="sc-front-title">
                  <h3><?= $svc['title'] ?></h3>
              </div>
              
              <div class="sc-hover-content">
                  <h4><?= $svc['title'] ?></h4>
                  <p><?= $svc['desc'] ?></p>
                  <ul>
                      <?php foreach($svc['list'] as $li): ?>
                      <li><i class="fa-solid fa-check"></i> <span><?= $li ?></span></li>
                      <?php endforeach; ?>
                  </ul>
              </div>
          </div>
          <?php endforeach; ?>
      </div>
    </div>
  </section>
  
  <section class="section bg-light">
    <div class="container">
      <div class="text-center animate-on-scroll">
        <span class="section__tag">Process</span>
        <h2 class="section__title">How It <span>Works</span></h2>
        <p class="section__subtitle" style="max-width: 600px; margin: 0 auto;">A streamlined, step-by-step approach to ensure your success.</p>
      </div>
      
      <div class="process-grid" style="margin-top: 4rem;">
          <?php
          $steps = [
              [
                  'num' => '01',
                  'title' => 'Consultation',
                  'desc' => 'Book a free session with our expert advisors to discuss your goals.',
                  'image' => 'assets/images/SC.png',
                  'delay' => ''
              ],
              [
                  'num' => '02',
                  'title' => 'Evaluation',
                  'desc' => 'We assess your academic background, test scores, and budget.',
                  'image' => 'assets/images/Appli.png',
                  'delay' => 'delay-1'
              ],
              [
                  'num' => '03',
                  'title' => 'Shortlisting',
                  'desc' => 'Receive a curated list of universities and courses matching your profile.',
                  'image' => 'assets/images/Offer.png',
                  'delay' => 'delay-2'
              ],
              [
                  'num' => '04',
                  'title' => 'Action Plan',
                  'desc' => 'Get a clear roadmap for applications, tests, and deadlines.',
                  'image' => 'assets/images/Fund.png',
                  'delay' => 'delay-3'
              ]
          ];
          ?>
      <div class="timeline-container" style="margin-top: 4rem;">
          <?php foreach ($steps as $index => $step): ?>
          <div class="timeline-step animate-on-scroll <?= $step['delay'] ?>">
              <div class="timeline-step__marker"><?= $step['num'] ?></div>
              
              <div class="timeline-step__content">
                  <h3><?= $step['title'] ?></h3>
                  <p><?= $step['desc'] ?></p>
              </div>

              <div class="timeline-step__image">
                  <img src="<?= $step['image'] ?>" alt="<?= $step['title'] ?>">
              </div>
          </div>
          <?php endforeach; ?>
      </div>
    </div>
  </section>

  <style>
  /* TIMELINE PROCESS UI FOR STUDENT COUNSELLING */
  .timeline-container {
      position: relative;
      max-width: 900px;
      margin: 0 auto;
      padding: 2rem 0;
  }
  .timeline-container::before {
      content: '';
      position: absolute;
      top: 0;
      bottom: 0;
      left: 50%;
      width: 4px;
      background: linear-gradient(to bottom, var(--primary-light), var(--primary), #0ea5e9);
      transform: translateX(-50%);
      border-radius: 4px;
      opacity: 0.3;
  }
  .timeline-step {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 5rem;
      position: relative;
      width: 100%;
  }
  .timeline-step:last-child {
      margin-bottom: 0;
  }
  .timeline-step:nth-child(even) {
      flex-direction: row-reverse;
  }
  .timeline-step__content {
      width: 42%;
      text-align: right;
  }
  .timeline-step:nth-child(even) .timeline-step__content {
      text-align: left;
  }
  .timeline-step__content h3 {
      font-size: 1.8rem;
      color: var(--dark);
      margin-bottom: 0.75rem;
      font-weight: 800;
  }
  .timeline-step__content p {
      color: var(--gray);
      font-size: 1.05rem;
      line-height: 1.6;
      margin: 0;
  }
  .timeline-step__marker {
      width: 60px;
      height: 60px;
      background: white;
      border: 4px solid var(--primary);
      border-radius: 50%;
      position: absolute;
      left: 50%;
      top: 50%;
      transform: translate(-50%, -50%);
      display: flex;
      align-items: center;
      justify-content: center;
      color: var(--primary);
      font-weight: 900;
      font-size: 1.4rem;
      z-index: 2;
      box-shadow: 0 0 0 8px rgba(236, 72, 153, 0.1);
      transition: all 0.3s ease;
  }
  .timeline-step:hover .timeline-step__marker {
      background: var(--primary);
      color: white;
      transform: translate(-50%, -50%) scale(1.1);
      box-shadow: 0 0 0 12px rgba(236, 72, 153, 0.2);
  }
  .timeline-step__image {
      width: 42%;
      display: flex;
      justify-content: flex-start;
  }
  .timeline-step:nth-child(even) .timeline-step__image {
      justify-content: flex-end;
  }
  .timeline-step__image img {
      max-width: 150px;
      width: 100%;
      object-fit: contain;
      filter: drop-shadow(0 15px 25px rgba(0,0,0,0.1));
      transition: transform 0.4s ease;
  }
  .timeline-step:hover .timeline-step__image img {
      transform: scale(1.1) translateY(-10px);
  }

  @media (max-width: 768px) {
      .timeline-container::before {
          left: 30px;
      }
      .timeline-step, .timeline-step:nth-child(even) {
          flex-direction: column;
          align-items: flex-start;
          padding-left: 90px;
          margin-bottom: 3rem;
      }
      .timeline-step__marker {
          left: 30px;
          top: 0;
          transform: translate(-50%, 0);
      }
      .timeline-step:hover .timeline-step__marker {
          transform: translate(-50%, 0) scale(1.1);
      }
      .timeline-step__content, .timeline-step:nth-child(even) .timeline-step__content {
          width: 100%;
          text-align: left;
          margin-bottom: 1.5rem;
      }
      .timeline-step__image, .timeline-step:nth-child(even) .timeline-step__image {
          width: 100%;
          justify-content: flex-start;
      }
  }
  </style>

  <section class="section">
    <div class="container">
      <div class="text-center animate-on-scroll">
        <span class="section__tag">Benefits</span>
        <h2 class="section__title">Why Choose <span>Bluestone</span></h2>
        <p class="section__subtitle" style="max-width: 600px; margin: 0 auto;">Experience the advantage of working with industry-leading experts.</p>
      </div>
      <style>
      .why-grid {
          display: grid;
          grid-template-columns: repeat(1, 1fr);
          gap: 2rem;
          margin-top: 3rem;
      }
      @media (min-width: 768px) {
          .why-grid {
              grid-template-columns: repeat(3, 1fr);
          }
      }
      .why-card {
          position: relative;
          margin-bottom: 2.5rem;
          display: block;
          text-decoration: none;
      }
      .why-card__img-wrapper {
          width: 100%;
          height: 250px;
          overflow: hidden;
      }
      .why-card__img {
          width: 100%;
          height: 100%;
          object-fit: cover;
          transition: transform 0.5s ease;
      }
      .why-card:hover .why-card__img {
          transform: scale(1.05);
      }
      .why-card__content {
          background: #ffffff;
          box-shadow: 0 10px 30px rgba(0,0,0,0.08);
          position: absolute;
          bottom: -2.5rem;
          left: 8%;
          right: 8%;
          width: 84%;
          padding: 1.5rem 1rem;
          text-align: center;
          z-index: 2;
          transition: transform 0.3s ease, box-shadow 0.3s ease;
      }
      .why-card:hover .why-card__content {
          transform: translateY(-5px);
          box-shadow: 0 15px 35px rgba(0,0,0,0.12);
      }
      .why-card__content h3 {
          font-size: 1.25rem;
          color: #17191c;
          margin-bottom: 0.5rem;
          font-weight: 700;
      }
      .why-card__content .read-more {
          color: #84cc16;
          font-size: 0.95rem;
          font-weight: 500;
          transition: color 0.3s ease;
      }
      .why-card:hover .why-card__content .read-more {
          color: #65a30d;
      }
      </style>
      <div class="why-grid">
          <!-- Benefit 1 -->
          <a href="#" class="why-card animate-on-scroll">
              <div class="why-card__img-wrapper">
                  <img src="assets/images/why_global.png" alt="Global Standards" class="why-card__img">
              </div>
              <div class="why-card__content">
                  <h3>Global Standards</h3>
                  <div class="read-more">read more...</div>
              </div>
          </a>

          <!-- Benefit 2 -->
          <a href="#" class="why-card animate-on-scroll delay-1">
              <div class="why-card__img-wrapper">
                  <img src="assets/images/why_expert.png" alt="Expert Advisors" class="why-card__img">
              </div>
              <div class="why-card__content">
                  <h3>Expert Advisors</h3>
                  <div class="read-more">read more...</div>
              </div>
          </a>

          <!-- Benefit 3 -->
          <a href="#" class="why-card animate-on-scroll delay-2">
              <div class="why-card__img-wrapper">
                  <img src="assets/images/why_unbiased.png" alt="Unbiased Advice" class="why-card__img">
              </div>
              <div class="why-card__content">
                  <h3>Unbiased Advice</h3>
                  <div class="read-more">read more...</div>
              </div>
          </a>
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
