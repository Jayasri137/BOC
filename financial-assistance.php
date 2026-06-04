<?php
require_once 'includes/config.php';
$pageTitle = 'Financial Assistance | Bluestone Overseas Consultants';
require_once 'includes/header.php';
?>
<main>
  <!-- HERO SECTION WITH RICH AESTHETICS -->
  <section class="section" style="position: relative; overflow: hidden; background: linear-gradient(135deg, #f0fdfa 0%, #fffbeb 100%); padding: 6rem 0 4rem;">
    <!-- Abstract blurred background shapes -->
    <div style="position: absolute; top: -10%; left: -10%; width: 300px; height: 300px; background: rgba(20, 184, 166, 0.15); filter: blur(80px); border-radius: 50%;"></div>
    <div style="position: absolute; bottom: -10%; right: -10%; width: 300px; height: 300px; background: rgba(245, 158, 11, 0.15); filter: blur(80px); border-radius: 50%;"></div>

    <div class="container" style="position: relative; z-index: 2;">
      <div class="grid grid--2 gap--4 align-center">
        <div class="animate-on-scroll">
          <div class="v-icon" style="width:100px; height:100px; font-size:2.75rem; margin:0; background: linear-gradient(135deg, #14b8a6, #f59e0b); color: white; box-shadow: 0 10px 25px rgba(20, 184, 166, 0.25);">
            <i class="fa-solid fa-hand-holding-dollar"></i>
          </div>
          <h1 class="section__title" style="text-align:left; margin-top:2rem; font-size: 2.75rem; line-height: 1.2;">
            Empower Your Study Dreams with <span>Financial Support</span>
          </h1>
          <p style="color:var(--gray); margin-top:1.5rem; line-height:1.8; font-size: 1.05rem;">
            At <strong>Bluestone Overseas</strong>, we believe budget constraints should never stand in the way of global success. We offer complete financial advisory services, helping you map out funding through elite global scholarships and affordable education loans.
          </p>
        </div>
        <div class="animate-on-scroll delay-1">
          <div class="service-details grid grid--1 gap--1" style="background: rgba(255, 255, 255, 0.7); backdrop-filter: blur(10px); padding: 2.5rem; border-radius: 24px; border: 1px solid rgba(255, 255, 255, 0.6); box-shadow: 0 20px 40px rgba(0,0,0,0.03);">
            <h3 style="margin-bottom: 1.5rem; color: var(--dark); font-size: 1.25rem; text-align: center;">Our Financial Support Offerings</h3>
            <div class="a-feat">
              <i class="fa-solid fa-check-circle" style="color: #14b8a6;"></i>
              <span>100% Transparent Financial Profiling</span>
            </div>
            <div class="a-feat">
              <i class="fa-solid fa-check-circle" style="color: #14b8a6;"></i>
              <span>Exclusive University Scholarship Matching</span>
            </div>
            <div class="a-feat">
              <i class="fa-solid fa-check-circle" style="color: #14b8a6;"></i>
              <span>Tie-ups with Premier Educational Banks</span>
            </div>
            <div class="a-feat">
              <i class="fa-solid fa-check-circle" style="color: #14b8a6;"></i>
              <span>Professional Scholarship Essay Guidance</span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- TWO ELITE PATHWAY CARDS -->
  <section class="section" style="background: #fff; padding: 5rem 0;">
    <div class="container">
      <div class="text-center animate-on-scroll" style="margin-bottom: 4rem;">
        <span class="section__tag" style="background: rgba(20, 184, 166, 0.1); color: #14b8a6;">Dual Pathways</span>
        <h2 class="section__title" style="margin-top: 1rem;">Choose Your <span>Funding Solution</span></h2>
        <p class="section__subtitle" style="max-width: 600px; margin: 0 auto;">Select from either scholarships based on academic merit or custom study loans from top financial partners.</p>
      </div>

      <div class="grid grid--2 gap--4">
        <!-- Pathway 1: Scholarships -->
        <div class="service-card animate-on-scroll" style="padding: 3.5rem 3rem; background: #fff; border-radius: 24px; border: 1px solid #f1f5f9; box-shadow: 0 15px 35px rgba(0,0,0,0.02); display: flex; flex-direction: column; gap: 2rem; transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1); position: relative; overflow: hidden;" onmouseover="this.style.transform='translateY(-8px)'; this.style.boxShadow='0 25px 50px rgba(245,158,11,0.1)'; this.style.borderColor='#f59e0b';" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 15px 35px rgba(0,0,0,0.02)'; this.style.borderColor='#f1f5f9';">
          <div style="position: absolute; top: 0; left: 0; width: 6px; height: 100%; background: #f59e0b;"></div>
          
          <div style="display: flex; justify-content: space-between; align-items: center;">
            <div style="width: 70px; height: 70px; background: #fffbeb; color: #f59e0b; border-radius: 16px; display: grid; place-items: center; font-size: 2.25rem;">
              <i class="fa-solid fa-award"></i>
            </div>
            <span style="font-size: 0.75rem; font-weight: 800; text-transform: uppercase; color: #f59e0b; background: rgba(245, 158, 11, 0.1); padding: 0.4rem 1rem; border-radius: 30px;">Merit & Grants</span>
          </div>

          <div>
            <h3 style="font-size: 1.75rem; color: var(--dark); font-weight: 700; margin-bottom: 0.75rem;">Scholarships & Grants</h3>
            <p style="color: var(--gray); line-height: 1.7; font-size: 0.95rem;">
              Unlock exclusive international grants, university-specific fee waivers, and government scholarships that can fund up to 100% of your tuition and living expenses worldwide.
            </p>
          </div>

          <div style="margin-top: auto; padding-top: 1.5rem; border-top: 1px solid #f1f5f9;">
            <a href="scholarships.php" class="btn btn--primary" style="width: 100%; justify-content: center; background: #f59e0b; border-color: #f59e0b; box-shadow: 0 4px 15px rgba(245,158,11,0.2);">
              Find Scholarships <i class="fa-solid fa-arrow-right-long" style="margin-left: 0.5rem;"></i>
            </a>
          </div>
        </div>

        <!-- Pathway 2: Education Loans -->
        <div class="service-card animate-on-scroll delay-1" style="padding: 3.5rem 3rem; background: #fff; border-radius: 24px; border: 1px solid #f1f5f9; box-shadow: 0 15px 35px rgba(0,0,0,0.02); display: flex; flex-direction: column; gap: 2rem; transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1); position: relative; overflow: hidden;" onmouseover="this.style.transform='translateY(-8px)'; this.style.boxShadow='0 25px 50px rgba(20,184,166,0.1)'; this.style.borderColor='#14b8a6';" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 15px 35px rgba(0,0,0,0.02)'; this.style.borderColor='#f1f5f9';">
          <div style="position: absolute; top: 0; left: 0; width: 6px; height: 100%; background: #14b8a6;"></div>

          <div style="display: flex; justify-content: space-between; align-items: center;">
            <div style="width: 70px; height: 70px; background: #f0fdfa; color: #14b8a6; border-radius: 16px; display: grid; place-items: center; font-size: 2.25rem;">
              <i class="fa-solid fa-building-columns"></i>
            </div>
            <span style="font-size: 0.75rem; font-weight: 800; text-transform: uppercase; color: #14b8a6; background: rgba(20, 184, 166, 0.1); padding: 0.4rem 1rem; border-radius: 30px;">Unsecured & Collateral</span>
          </div>

          <div>
            <h3 style="font-size: 1.75rem; color: var(--dark); font-weight: 700; margin-bottom: 0.75rem;">Education Loans</h3>
            <p style="color: var(--gray); line-height: 1.7; font-size: 0.95rem;">
              Access competitive, fast-sanctioned student loans with discounted interest rates through our direct tie-ups with leading private, public banks, and global financial partners.
            </p>
          </div>

          <div style="margin-top: auto; padding-top: 1.5rem; border-top: 1px solid #f1f5f9;">
            <a href="education-loan.php" class="btn btn--primary" style="width: 100%; justify-content: center; background: #14b8a6; border-color: #14b8a6; box-shadow: 0 4px 15px rgba(20,184,166,0.2);">
              Explore Loan Partners <i class="fa-solid fa-arrow-right-long" style="margin-left: 0.5rem;"></i>
            </a>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- CHROMIUM 4-STEP TIMELINE -->
  <section class="section bg-light" style="padding: 5rem 0;">
    <div class="container">
      <div class="text-center animate-on-scroll" style="margin-bottom: 4rem;">
        <span class="section__tag" style="background: rgba(139, 92, 246, 0.1); color: #8b5cf6;">The Strategy</span>
        <h2 class="section__title" style="margin-top: 1rem;">Our <span>Funding Roadmap</span></h2>
        <p class="section__subtitle" style="max-width: 600px; margin: 0 auto;">A step-by-step financial plan to secure your visa and university seat.</p>
      </div>

      <div class="grid grid--4 gap--2">
        <div class="service-card text-center animate-on-scroll" style="background: #fff; border-radius: 20px; padding: 2.5rem 2rem; box-shadow: 0 10px 25px rgba(0,0,0,0.02); border: 1px solid #f1f5f9;">
          <div class="service-card__icon" style="margin: 0 auto 1.5rem; background: rgba(59, 130, 246, 0.1); color: #3b82f6; width: 60px; height: 60px; font-size: 1.25rem; font-weight: 800; border-radius: 50%; display: grid; place-items: center;"><i class="fa-solid fa-1"></i></div>
          <h3 style="font-size: 1.15rem; font-weight: 700; margin-bottom: 0.75rem;">Budgeting</h3>
          <p style="font-size: 0.875rem; color: var(--gray); line-height: 1.6;">We detail total tuition, health insurance, housing, and travel expenses for your profile.</p>
        </div>

        <div class="service-card text-center animate-on-scroll delay-1" style="background: #fff; border-radius: 20px; padding: 2.5rem 2rem; box-shadow: 0 10px 25px rgba(0,0,0,0.02); border: 1px solid #f1f5f9;">
          <div class="service-card__icon" style="margin: 0 auto 1.5rem; background: rgba(139, 92, 246, 0.1); color: #8b5cf6; width: 60px; height: 60px; font-size: 1.25rem; font-weight: 800; border-radius: 50%; display: grid; place-items: center;"><i class="fa-solid fa-2"></i></div>
          <h3 style="font-size: 1.15rem; font-weight: 700; margin-bottom: 0.75rem;">Scholarship Match</h3>
          <p style="font-size: 0.875rem; color: var(--gray); line-height: 1.6;">We scan hundreds of merit and external grants to match you with valid fee waivers.</p>
        </div>

        <div class="service-card text-center animate-on-scroll delay-2" style="background: #fff; border-radius: 20px; padding: 2.5rem 2rem; box-shadow: 0 10px 25px rgba(0,0,0,0.02); border: 1px solid #f1f5f9;">
          <div class="service-card__icon" style="margin: 0 auto 1.5rem; background: rgba(249, 115, 22, 0.1); color: #f97316; width: 60px; height: 60px; font-size: 1.25rem; font-weight: 800; border-radius: 50%; display: grid; place-items: center;"><i class="fa-solid fa-3"></i></div>
          <h3 style="font-size: 1.15rem; font-weight: 700; margin-bottom: 0.75rem;">Loan Processing</h3>
          <p style="font-size: 0.875rem; color: var(--gray); line-height: 1.6;">We handle bank filing directly to secure the fastest unsecured or secured loans.</p>
        </div>

        <div class="service-card text-center animate-on-scroll delay-3" style="background: #fff; border-radius: 20px; padding: 2.5rem 2rem; box-shadow: 0 10px 25px rgba(0,0,0,0.02); border: 1px solid #f1f5f9;">
          <div class="service-card__icon" style="margin: 0 auto 1.5rem; background: rgba(20, 184, 166, 0.1); color: #14b8a6; width: 60px; height: 60px; font-size: 1.25rem; font-weight: 800; border-radius: 50%; display: grid; place-items: center;"><i class="fa-solid fa-4"></i></div>
          <h3 style="font-size: 1.15rem; font-weight: 700; margin-bottom: 0.75rem;">Visa Proofs</h3>
          <p style="font-size: 0.875rem; color: var(--gray); line-height: 1.6;">We organize financial documentation flawlessly, leading to high student visa approval rates.</p>
        </div>
      </div>
    </div>
  </section>

  <!-- CTA BLOCK -->
  <section class="section" style="padding: 5rem 0;">
    <div class="container animate-on-scroll">
      <div style="background: linear-gradient(135deg, #14b8a6 0%, #0d9488 100%); padding: 4.5rem 2rem; border-radius: 28px; text-align: center; color: white; box-shadow: 0 20px 45px rgba(20, 184, 166, 0.35); position: relative; overflow: hidden;">
        <div style="position: absolute; top: -50%; left: -20%; width: 400px; height: 400px; background: rgba(255,255,255,0.05); border-radius: 50%;"></div>
        
        <h2 style="font-size: 2.5rem; margin-bottom: 1.25rem; font-weight: 800;">Get a Free Financial Roadmap</h2>
        <p style="font-size: 1.15rem; opacity: 0.95; max-width: 600px; margin: 0 auto 2.5rem; line-height: 1.6;">Speak with our senior financial counsellors today to structure your study abroad funding successfully.</p>
        <a href="consultation.php" class="btn btn--white btn--lg pulse-btn" style="background: white; color: #0d9488; font-weight: 700; padding: 1rem 2.5rem; border-radius: 12px; font-size: 1.05rem; box-shadow: 0 10px 25px rgba(0,0,0,0.05);">
          Book Free Assessment <i class="fa-solid fa-calendar-check" style="margin-left: 0.5rem;"></i>
        </a>
      </div>
    </div>
  </section>
</main>
<?php require_once 'includes/footer.php'; ?>
