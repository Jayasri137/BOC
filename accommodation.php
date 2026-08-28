<?php
require_once 'includes/config.php';
$pageTitle = 'Student Accommodation Assistance Abroad | Bluestone Overseas';
$pageDesc = 'Get help finding affordable and comfortable accommodation near your university.';
require_once 'includes/header.php';
?>
<main>

  <style>
  @keyframes float {
      0% { transform: translateY(0px); }
      50% { transform: translateY(-15px); }
      100% { transform: translateY(0px); }
  }
  </style>

  <section class="section" style="padding: 6rem 0; overflow: hidden; position: relative; background-color: #ffffff;">
    <div class="container">
      <div class="text-center animate-on-scroll" style="max-width: 800px; margin: 0 auto 4rem auto;">
          <span style="display: inline-block; background: #eff6ff; color: #3b82f6; padding: 0.35rem 1.25rem; border-radius: 50px; font-size: 0.85rem; font-weight: 700; margin-bottom: 1.5rem;">Accommodation & Travel</span>
          <h2 style="font-size: clamp(2.5rem, 5vw, 3.5rem); margin-bottom: 1.5rem; line-height: 1.2;">Your Home <span style="color: #3b82f6;">Away From Home</span></h2>
          <p style="color:var(--gray); margin-bottom:2rem; line-height:1.7; font-size: 1.1rem;">
            In association with <strong>Bluestone Overseas</strong>, we ensure you have a safe and comfortable place to stay the moment you land. We handle all logistics from pre-departure to your arrival.
          </p>
          <div style="display: flex; flex-wrap: wrap; justify-content: center; gap: 1rem; margin-top: 2rem;">
            <div style="background: #ffffff; border: 1px solid #e2e8f0; box-shadow: 0 4px 15px rgba(0,0,0,0.03); padding: 0.75rem 1.5rem; border-radius: 50px; display: flex; align-items: center; gap: 0.75rem; font-size: 1rem; color: var(--dark); font-weight: 600; transition: transform 0.3s ease;" onmouseover="this.style.transform='translateY(-3px)'" onmouseout="this.style.transform='translateY(0)'">
              <i class="fa-solid fa-house-chimney-window" style="color: #3b82f6;"></i> On-Campus Housing Assistance
            </div>
            <div style="background: #ffffff; border: 1px solid #e2e8f0; box-shadow: 0 4px 15px rgba(0,0,0,0.03); padding: 0.75rem 1.5rem; border-radius: 50px; display: flex; align-items: center; gap: 0.75rem; font-size: 1rem; color: var(--dark); font-weight: 600; transition: transform 0.3s ease;" onmouseover="this.style.transform='translateY(-3px)'" onmouseout="this.style.transform='translateY(0)'">
              <i class="fa-solid fa-building" style="color: #3b82f6;"></i> Off-Campus Flats & Homestays
            </div>
            <div style="background: #ffffff; border: 1px solid #e2e8f0; box-shadow: 0 4px 15px rgba(0,0,0,0.03); padding: 0.75rem 1.5rem; border-radius: 50px; display: flex; align-items: center; gap: 0.75rem; font-size: 1rem; color: var(--dark); font-weight: 600; transition: transform 0.3s ease;" onmouseover="this.style.transform='translateY(-3px)'" onmouseout="this.style.transform='translateY(0)'">
              <i class="fa-solid fa-plane-departure" style="color: #3b82f6;"></i> International Flight Bookings
            </div>
            <div style="background: #ffffff; border: 1px solid #e2e8f0; box-shadow: 0 4px 15px rgba(0,0,0,0.03); padding: 0.75rem 1.5rem; border-radius: 50px; display: flex; align-items: center; gap: 0.75rem; font-size: 1rem; color: var(--dark); font-weight: 600; transition: transform 0.3s ease;" onmouseover="this.style.transform='translateY(-3px)'" onmouseout="this.style.transform='translateY(0)'">
              <i class="fa-solid fa-clipboard-check" style="color: #3b82f6;"></i> Comprehensive Pre-Departure Briefing
            </div>
          </div>
      </div>

      <div class="animate-on-scroll delay-1" style="max-width: 800px; margin: 0 auto; position: relative; padding: 1rem;">
          <!-- Overlapping Images (Centered/Side-by-side) -->
          <div style="position: relative; width: 100%; padding-bottom: 60%;">
              <img src="assets/images/img4.png" alt="Student Accommodation" style="position: absolute; top: 0; right: 10%; width: 55%; height: 85%; object-fit: cover; border-radius: 30px; box-shadow: 0 20px 40px rgba(0,0,0,0.1); border: 8px solid white; z-index: 2;">
              <img src="assets/images/Acc.png" alt="Travel Booking" style="position: absolute; bottom: 0; left: 10%; width: 50%; height: 80%; object-fit: cover; border-radius: 30px; box-shadow: 0 20px 40px rgba(0,0,0,0.15); border: 8px solid white; z-index: 3;">
              
              <!-- Floating Badge -->
              <div style="position: absolute; top: 10%; left: 0%; background: white; padding: 1rem 1.5rem; border-radius: 20px; box-shadow: 0 15px 30px rgba(0,0,0,0.08); z-index: 4; display: flex; align-items: center; gap: 1rem; animation: float 6s ease-in-out infinite;">
                  <div style="width: 45px; height: 45px; background: #e0e7ff; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: #4f46e5; font-size: 1.25rem;">
                      <i class="fa-solid fa-house-user"></i>
                  </div>
                  <div>
                      <div style="font-weight: 800; color: var(--dark); font-size: 1.1rem;">Safe & Secure</div>
                      <div style="font-size: 0.85rem; color: var(--gray);">Verified Properties</div>
                  </div>
              </div>
          </div>
      </div>
    </div>
  </section>

  <section class="section" style="padding: 6rem 0; background-color: #ffffff;">
    <div class="container animate-on-scroll">
      <div style="background:#d946ef; padding: 4rem 2rem 5rem; border-radius: var(--radius-lg); text-align: center; box-shadow: var(--shadow-lg); max-width: 1100px; margin: 0 auto;">
        
        <div class="section__header text-center" style="margin-bottom: 3rem;">
          <span style="display: inline-block; background: rgba(255,255,255,0.2); color: white; padding: 0.35rem 1.25rem; border-radius: 50px; font-size: 0.85rem; font-weight: 700; margin-bottom: 1rem; backdrop-filter: blur(4px);">Process</span>
          <h2 style="font-size: 2.5rem; margin-bottom: 1rem; color: white;">How It Works</h2>
          <p style="font-size: 1.1rem; color: rgba(255,255,255,0.9); max-width: 600px; margin: 0 auto;">A streamlined, step-by-step approach to ensure your success.</p>
        </div>
        
        <div class="grid-bento" style="grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1.5rem;">
            <?php
            $steps = [
                [
                    'num' => '01',
                    'title' => 'Preferences',
                    'desc' => 'Tell us your budget, location preferences, and roommate requests.',
                    'image' => 'assets/images/SC.png',
                    'grad' => 'linear-gradient(135deg, #0ea5e9, #3b82f6)'
                ],
                [
                    'num' => '02',
                    'title' => 'Options',
                    'desc' => 'We provide a curated list of verified student accommodations and flights.',
                    'image' => 'assets/images/RO.png',
                    'grad' => 'linear-gradient(135deg, #f65c6eff, #850a0aff)'
                ],
                [
                    'num' => '03',
                    'title' => 'Booking',
                    'desc' => 'We handle the contracts, deposits, and ticketing securely.',
                    'image' => 'assets/images/Offer.png',
                    'grad' => 'linear-gradient(135deg, #f97316, #f59e0b)'
                ],
                [
                    'num' => '04',
                    'title' => 'Arrival',
                    'desc' => 'Attend our pre-departure briefing and fly out fully prepared.',
                    'image' => 'assets/images/img4.png',
                    'grad' => 'linear-gradient(135deg, #14b8a6, #0d9488)'
                ]
            ];

            foreach ($steps as $i => $step):
            ?>
            <div class="sc-card animate-on-scroll" style="--theme-grad: <?= $step['grad'] ?>; animation-delay: <?= $i * 0.1 ?>s; text-align: left;">
                <div class="sc-img-wrap">
                    <img src="<?= $step['image'] ?>" alt="<?= $step['title'] ?>">
                </div>
                <div class="sc-front-title">
                    <div class="step-num"><?= $step['num'] ?></div>
                    <h3><?= $step['title'] ?></h3>
                </div>
                <div class="sc-hover-content">
                    <div class="step-num" style="position: absolute; top: 1.5rem; right: 1.5rem; background: rgba(255,255,255,0.2); color: white; width: 35px; height: 35px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 0.9rem; font-weight: 700; border: 2px solid white;"><?= $step['num'] ?></div>
                    <h4 style="margin-top: 1rem;"><?= $step['title'] ?></h4>
                    <p><?= $step['desc'] ?></p>
                </div>
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
          <span style="display: inline-block; background: #ffffff; color: var(--primary); padding: 0.35rem 1.25rem; border-radius: 50px; font-size: 0.85rem; font-weight: 700; margin-bottom: 1.5rem;">The Bluestone Advantage</span>
          <h2 style="font-size: 2.5rem; margin-bottom: 1.5rem; line-height: 1.2;">Why Choose <span style="color: var(--primary);">Bluestone</span></h2>
          <p style="color:var(--gray); margin-bottom:2.5rem; line-height:1.7; font-size: 1.05rem;">
            Experience the advantage of working with industry-leading experts. All our properties and travel partners are thoroughly vetted to ensure student safety, convenience, and affordability.
          </p>
          <ul style="list-style: none; padding: 0; margin: 0; display: flex; flex-direction: column; gap: 1rem;">
            <li style="display: flex; align-items: center; gap: 1rem; font-size: 1.05rem; color: var(--dark); font-weight: 500;">
              <i class="fa-solid fa-shield-check" style="color: var(--primary); font-size: 1.25rem;"></i> Verified Safe Housing
            </li>
            <li style="display: flex; align-items: center; gap: 1rem; font-size: 1.05rem; color: var(--dark); font-weight: 500;">
              <i class="fa-solid fa-tags" style="color: var(--primary); font-size: 1.25rem;"></i> Exclusive Student Discounts
            </li>
            <li style="display: flex; align-items: center; gap: 1rem; font-size: 1.05rem; color: var(--dark); font-weight: 500;">
              <i class="fa-solid fa-users" style="color: var(--primary); font-size: 1.25rem;"></i> Pre-Departure Network Building
            </li>
          </ul>
        </div>
        
        <div class="animate-on-scroll delay-1">
          <div style="background: linear-gradient(135deg, #0ea5e9, #3b82f6); padding: 3rem; border-radius: 24px; box-shadow: 0 20px 40px rgba(0,0,0,0.06); border: 1px solid #f1f5f9; position: relative;">
            <div style="position: absolute; top: -20px; right: 30px; width: 60px; height: 60px; background: #f59e0b; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-size: 1.5rem; box-shadow: 0 10px 20px rgba(245,158,11,0.3);">
              <i class="fa-solid fa-house-chimney"></i>
            </div>
            <h3 style="margin-bottom: 1.5rem; font-size: 1.75rem; color: white;">Verified & Secure</h3>
            <p style="font-size: 1.05rem; color: white; line-height: 1.8; opacity: 0.95;">
                Your safety is our top priority. We partner exclusively with recognized student accommodation providers and trusted airlines globally to ensure a seamless transition.
            </p>
            <div style="margin-top: 2.5rem; padding: 1.5rem; background: rgba(255,255,255,0.1); backdrop-filter: blur(5px); -webkit-backdrop-filter: blur(5px); border-radius: 12px; display: flex; gap: 1.25rem; border-left: 4px solid #f59e0b;">
                <i class="fa-solid fa-plane-departure" style="color: #f59e0b; font-size: 1.5rem; margin-top: 0.25rem;"></i>
                <p style="font-size: 1rem; color: white; font-weight: 600; margin: 0; line-height: 1.6;">Don't wait until the last minute! Secure your housing and flights early for the best rates.</p>
            </div>
          </div>
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
