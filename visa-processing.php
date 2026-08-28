<?php
require_once 'includes/config.php';
$pageTitle = 'Student Visa Processing Services | Study Abroad Visa Experts';
$pageDesc = 'Maximize your visa success with professional documentation and application support.';
$pageHeroImage = 'assets/images/img10.png';
require_once 'includes/header.php';
?>
<main>

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
  .feature-pill:hover .feature-pill__text {
      color: var(--dark) !important;
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
  .feature-pill__icon--pink { background: linear-gradient(135deg, #ec4899, #be185d); box-shadow: 0 8px 20px rgba(236,72,153,0.3); }
  .feature-pill__text {
      font-size: 1.15rem;
      font-weight: 700;
      color: var(--dark);
      line-height: 1.4;
      transition: color 0.3s ease;
  }
  @keyframes float {
      0% { transform: translateY(0px); }
      50% { transform: translateY(-10px); }
      100% { transform: translateY(0px); }
  }
  </style>

  <section class="section" style="padding: 6rem 0; overflow: hidden; background-color:#ffffff">
    <div class="container">
      <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 4rem; align-items: center;">
        <div class="animate-on-scroll" style="position: relative; padding: 1rem;">
          <img src="assets/images/md-gallery5.png" alt="Visa Processing" style="width: 100%; border-radius: 20px; box-shadow: 0 20px 40px rgba(0,0,0,0.1); position: relative; z-index: 2;">
          <img src="assets/images/gallery3.png" alt="Passports" style="position: absolute; bottom: -10%; left: -10%; width: 50%; border-radius: 20px; border: 8px solid white; box-shadow: 0 15px 30px rgba(0,0,0,0.15); z-index: 3;">
          <div style="position: absolute; top: 10%; right: -5%; background: white; padding: 1rem 1.5rem; border-radius: 16px; box-shadow: 0 10px 25px rgba(0,0,0,0.1); z-index: 4; display: flex; align-items: center; gap: 1rem; animation: float 4s ease-in-out infinite;">
            <div style="width: 40px; height: 40px; background: #fdf2f8; color: #db2777; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 1.25rem;">
              <i class="fa-solid fa-plane-departure"></i>
            </div>
            <div>
              <p style="margin: 0; font-size: 0.85rem; color: var(--gray); font-weight: 600;">Visas Approved</p>
              <h4 style="margin: 0; font-size: 1.25rem; color: var(--dark); font-weight: 800;">5,000+</h4>
            </div>
          </div>
        </div>
        
        <div class="animate-on-scroll delay-1">
          <span style="display: inline-block; background: #fdf2f8; color: #db2777; padding: 0.35rem 1.25rem; border-radius: 50px; font-size: 0.85rem; font-weight: 700; margin-bottom: 1.5rem;">Expert Visa Guidance</span>
          <h2 style="font-size: 2.5rem; margin-bottom: 1.5rem; line-height: 1.2;">Hassle-Free <span style="color: var(--primary);">Visa Filing</span></h2>
          <p style="color:var(--gray); margin-bottom:2.5rem; line-height:1.7; font-size: 1.05rem;">
            In association with <strong>Bluestone Overseas</strong>, we provide end-to-end visa assistance to turn your dreams into reality without the stress. Our dedicated experts meticulously handle your application from documentation to mock interviews.
          </p>
          <ul style="list-style: none; padding: 0; margin: 0; display: flex; flex-direction: column; gap: 1rem;">
            <li style="display: flex; align-items: center; gap: 1rem; font-size: 1.05rem; color: var(--dark); font-weight: 500;">
              <i class="fa-solid fa-check-circle" style="color: #ec4899; font-size: 1.25rem;"></i> Complete Visa Documentation
            </li>
            <li style="display: flex; align-items: center; gap: 1rem; font-size: 1.05rem; color: var(--dark); font-weight: 500;">
              <i class="fa-solid fa-check-circle" style="color: #ec4899; font-size: 1.25rem;"></i> Financial Proof Preparation
            </li>
            <li style="display: flex; align-items: center; gap: 1rem; font-size: 1.05rem; color: var(--dark); font-weight: 500;">
              <i class="fa-solid fa-check-circle" style="color: #ec4899; font-size: 1.25rem;"></i> 1-on-1 Interview Mock Sessions
            </li>
            <li style="display: flex; align-items: center; gap: 1rem; font-size: 1.05rem; color: var(--dark); font-weight: 500;">
              <i class="fa-solid fa-check-circle" style="color: #ec4899; font-size: 1.25rem;"></i> Seamless Visa Filing & Tracking
            </li>
          </ul>
        </div>
      </div>
    </div>
  </section>

  <section class="section" style="padding: 6rem 0; background-color: #ffffff;">
    <div class="container animate-on-scroll">
      <div style="background: #d946ef; padding: 4rem 2rem 5rem; border-radius: var(--radius-lg); text-align: center; box-shadow: var(--shadow-lg); max-width: 1100px; margin: 0 auto;">
        
        <div class="section__header text-center" style="margin-bottom: 3rem;">
          <span style="display: inline-block; background: rgba(255,255,255,0.2); color: white; padding: 0.35rem 1.25rem; border-radius: 50px; font-size: 0.85rem; font-weight: 700; margin-bottom: 1rem; backdrop-filter: blur(4px);">Process</span>
          <h2 style="font-size: 2.5rem; margin-bottom: 1rem; color: white;">How It Works</h2>
          <p style="font-size: 1.1rem; color: rgba(255,255,255,0.9); max-width: 600px; margin: 0 auto;">A streamlined, step-by-step approach to ensure your success.</p>
        </div>
        
        <div class="horizontal-process">
            <?php
            $steps = [
                [
                    'num' => '01',
                    'title' => 'Pre-screening',
                    'icon' => 'fa-magnifying-glass-chart',
                    'desc' => 'We review your financial and academic documents to ensure compliance.'
                ],
                [
                    'num' => '02',
                    'title' => 'Compilation',
                    'icon' => 'fa-folder-open',
                    'desc' => 'We help you gather, translate, and verify every single required document.'
                ],
                [
                    'num' => '03',
                    'title' => 'Mock Interviews',
                    'icon' => 'fa-user-tie',
                    'desc' => 'Practice with former visa officers to build your confidence and readiness.'
                ],
                [
                    'num' => '04',
                    'title' => 'Submission',
                    'icon' => 'fa-paper-plane',
                    'desc' => 'We submit the application securely and track the process until approval.'
                ]
            ];

            foreach ($steps as $i => $step):
            ?>
            <div class="h-step animate-on-scroll" style="animation-delay: <?= $i * 0.1 ?>s; text-align: center;">
                <div class="h-step__icon"><i class="fa-solid <?= $step['icon'] ?>"></i></div>
                <div class="h-step__num"><?= $step['num'] ?></div>
                <h3><?= $step['title'] ?></h3>
                <p><?= $step['desc'] ?></p>
            </div>
            <?php endforeach; ?>
        </div>
        
      </div>
    </div>
  </section>

  <section class="section" style="padding: 6rem 0;">
    <div class="container">
      <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 4rem; align-items: center;">
        <div class="animate-on-scroll">
          <span style="display: inline-block; background: #ffffff; color: #aa0cc2ff; padding: 0.35rem 1.25rem; border-radius: 50px; font-size: 0.85rem; font-weight: 700; margin-bottom: 1.5rem;">The Bluestone Advantage</span>
          <h2 style="font-size: 2.5rem; margin-bottom: 1.5rem; line-height: 1.2;">Why Choose <span style="color: var(--primary);">Bluestone</span></h2>
          <p style="color:var(--gray); margin-bottom:2.5rem; line-height:1.7; font-size: 1.05rem;">
            Experience the advantage of working with industry-leading experts. We have direct access to embassies and the latest immigration policies to prevent any unexpected issues.
          </p>
          <ul style="list-style: none; padding: 0; margin: 0; display: flex; flex-direction: column; gap: 1rem;">
            <li style="display: flex; align-items: center; gap: 1rem; font-size: 1.05rem; color: var(--dark); font-weight: 500;">
              <i class="fa-solid fa-check-circle" style="color: #f59e0b; font-size: 1.25rem;"></i> Authorized Global Agents
            </li>
            <li style="display: flex; align-items: center; gap: 1rem; font-size: 1.05rem; color: var(--dark); font-weight: 500;">
              <i class="fa-solid fa-check-circle" style="color: #f59e0b; font-size: 1.25rem;"></i> Dedicated Case Managers
            </li>
            <li style="display: flex; align-items: center; gap: 1rem; font-size: 1.05rem; color: var(--dark); font-weight: 500;">
              <i class="fa-solid fa-check-circle" style="color: #f59e0b; font-size: 1.25rem;"></i> Latest Policy Updates
            </li>
          </ul>
        </div>
        
        <div class="animate-on-scroll delay-1">
          <div style="background: var(--accent); padding: 3rem; border-radius: 24px; box-shadow: 0 20px 40px rgba(0,0,0,0.06); border: 1px solid #f1f5f9; position: relative;">
            <div style="position: absolute; top: -20px; right: 30px; width: 60px; height: 60px; background: #ec4899; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-size: 1.5rem; box-shadow: 0 10px 20px rgba(236,72,153,0.3);">
              <i class="fa-solid fa-passport"></i>
            </div>
            <h3 style="margin-bottom: 1.5rem; font-size: 1.75rem; color: white;">99% Visa Success Rate</h3>
            <p style="font-size: 1.05rem; color: white; line-height: 1.8; opacity: 0.9;">
                Our meticulous attention to detail has resulted in an industry-leading visa approval rate. We leave no stone unturned to ensure your visa is approved the first time.
            </p>
            <div style="margin-top: 2.5rem; padding: 1.5rem; background: #fdf2f8; border-radius: 12px; display: flex; gap: 1.25rem; border-left: 4px solid #db2777;">
                <i class="fa-solid fa-bolt" style="color: #db2777; font-size: 1.5rem; margin-top: 0.25rem;"></i>
                <p style="font-size: 1rem; color: #9d174d; font-weight: 600; margin: 0; line-height: 1.6;">Don't let a simple mistake delay your intake. Trust the experts!</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <section class="section" style="padding-top: 2rem;">
    <div class="container animate-on-scroll">
      <div style="background: linear-gradient(135deg, #ec4899, #be185d); padding: 4rem 2rem; border-radius: var(--radius-lg); text-align: center; color: white; box-shadow: 0 20px 40px rgba(236,72,153,0.3);">
        <h2 style="font-size: 2.5rem; margin-bottom: 1rem;">Ready to Start Your Global Journey?</h2>
        <p style="font-size: 1.1rem; opacity: 0.9; max-width: 600px; margin: 0 auto 2rem;">Join thousands of successful students who have achieved their dreams with Bluestone Overseas.</p>
        <a href="consultation.php" class="btn btn--white btn--lg pulse-btn" style="background: white; color: #be185d;">Book Free Consultation</a>
      </div>
    </div>
  </section>
</main>
<?php require_once 'includes/footer.php'; ?>
