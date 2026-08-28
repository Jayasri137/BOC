<?php
require_once 'includes/config.php';
$pageTitle = 'Education Loan Assistance for Study Abroad | Bluestone Overseas';
$pageDesc = 'Secure education loans with expert guidance for tuition fees and living expenses abroad.';
$pageHeroImage = 'assets/images/Fund.png';
require_once 'includes/header.php';
?>
<main>

  <section class="section">
    <div class="container">
      <!-- Destination Filter -->
      <div class="filter-card filter-card-pad animate-on-scroll" style="margin-bottom: 4rem; background: #fff; padding: 2rem; border-radius: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.05); border: 1px solid #f1f5f9;">
        <form action="" method="GET" style="display: flex; flex-wrap: wrap; gap: 1.5rem; align-items: center; justify-content: space-between;">
          <div style="flex: 1; min-width: 250px;">
            <h3 style="margin: 0; font-size: 1.25rem;">Discover recommended products for your study destination.</h3>
          </div>
          <div style="display: flex; flex-wrap: wrap; gap: 1rem; flex: 1; min-width: 250px;">
            <select name="destination" class="form-control" style="flex: 1; min-width: 150px; padding: 0.75rem 1rem; border-radius: 10px; border: 1px solid #e2e8f0;" onchange="this.form.submit()">
              <option value="">All Destinations</option>
              <?php
              $countries = $pdo->query("SELECT name FROM countries ORDER BY name ASC")->fetchAll();
              $selectedDest = $_GET['destination'] ?? '';
              foreach ($countries as $c):
              ?>
                <option value="<?= clean_output($c['name']) ?>" <?= $selectedDest == $c['name'] ? 'selected' : '' ?>><?= clean_output($c['name']) ?></option>
              <?php endforeach; ?>
            </select>
            <button type="submit" class="btn btn--primary">Filter</button>
          </div>
        </form>
      </div>

      <div class="animate-on-scroll" style="margin-bottom: 2rem;">
        <h2 style="font-size: 1.75rem; margin-bottom: 0.5rem; color: var(--dark);">Education loan</h2>
        <div style="width: 50px; height: 3px; background: var(--primary); margin-bottom: 1.5rem;"></div>
        <p style="color: var(--gray); line-height: 1.6;">We've partnered with some of the most trusted financial institutions to offer a one-stop solution for education loans. Bluestone and our partners will help make your study abroad journey convenient and simple.</p>
      </div>

      <div class="animate-on-scroll" style="margin-bottom: 2rem;">
        <h3 style="font-size: 1.5rem; color: var(--dark);">Explore our global partners</h3>
      </div>

      <div class="grid grid--2 gap--4">
        <!-- Hardcoded Global Partners -->
        <div class="service-card animate-on-scroll" style="padding: 2.5rem; background: #fff; border-radius: 20px; border: 1px solid #f1f5f9; box-shadow: 0 10px 30px rgba(0,0,0,0.03); display: flex; flex-direction: column; gap: 1.5rem;">
          <div style="display: flex; justify-content: space-between; align-items: flex-start;">
            <h3 style="margin: 0; font-size: 1.5rem; color: var(--dark);">HDFC Credila</h3>
            <div style="font-size: 2rem; color: var(--primary);"><i class="fa-solid fa-building-columns"></i></div>
          </div>
          <div class="grid grid--1 gap--05">
            <div style="display: flex; align-items: center; gap: 0.75rem; color: var(--gray); font-size: 0.95rem;">
              <i class="fa-solid fa-circle-check" style="color: #10b981;"></i>
              <span>Up to 100% Finance</span>
            </div>
            <div style="display: flex; align-items: center; gap: 0.75rem; color: var(--gray); font-size: 0.95rem;">
              <i class="fa-solid fa-circle-check" style="color: #10b981;"></i>
              <span>Doorstep Service</span>
            </div>
            <div style="display: flex; align-items: center; gap: 0.75rem; color: var(--gray); font-size: 0.95rem;">
              <i class="fa-solid fa-circle-check" style="color: #10b981;"></i>
              <span>Flexible Repayment Options</span>
            </div>
          </div>
          <div style="margin-top: auto; padding-top: 1.5rem; border-top: 1px solid #f1f5f9; display: flex; flex-direction: column; gap: 0.75rem;">
              <a href="enquiry.php" class="btn btn--primary" style="width: 100%;">Enquire now</a>
          </div>
        </div>

        <div class="service-card animate-on-scroll delay-1" style="padding: 2.5rem; background: #fff; border-radius: 20px; border: 1px solid #f1f5f9; box-shadow: 0 10px 30px rgba(0,0,0,0.03); display: flex; flex-direction: column; gap: 1.5rem;">
          <div style="display: flex; justify-content: space-between; align-items: flex-start;">
            <h3 style="margin: 0; font-size: 1.5rem; color: var(--dark);">Avanse Financial Services</h3>
            <div style="font-size: 2rem; color: var(--primary);"><i class="fa-solid fa-landmark"></i></div>
          </div>
          <div class="grid grid--1 gap--05">
            <div style="display: flex; align-items: center; gap: 0.75rem; color: var(--gray); font-size: 0.95rem;">
              <i class="fa-solid fa-circle-check" style="color: #10b981;"></i>
              <span>No Margin Money</span>
            </div>
            <div style="display: flex; align-items: center; gap: 0.75rem; color: var(--gray); font-size: 0.95rem;">
              <i class="fa-solid fa-circle-check" style="color: #10b981;"></i>
              <span>Fast Processing</span>
            </div>
            <div style="display: flex; align-items: center; gap: 0.75rem; color: var(--gray); font-size: 0.95rem;">
              <i class="fa-solid fa-circle-check" style="color: #10b981;"></i>
              <span>Covers all expenses</span>
            </div>
          </div>
          <div style="margin-top: auto; padding-top: 1.5rem; border-top: 1px solid #f1f5f9; display: flex; flex-direction: column; gap: 0.75rem;">
              <a href="enquiry.php" class="btn btn--primary" style="width: 100%;">Enquire now</a>
          </div>
        </div>

        <div class="service-card animate-on-scroll delay-2" style="padding: 2.5rem; background: #fff; border-radius: 20px; border: 1px solid #f1f5f9; box-shadow: 0 10px 30px rgba(0,0,0,0.03); display: flex; flex-direction: column; gap: 1.5rem;">
          <div style="display: flex; justify-content: space-between; align-items: flex-start;">
            <h3 style="margin: 0; font-size: 1.5rem; color: var(--dark);">Prodigy Finance</h3>
            <div style="font-size: 2rem; color: var(--primary);"><i class="fa-solid fa-globe"></i></div>
          </div>
          <div class="grid grid--1 gap--05">
            <div style="display: flex; align-items: center; gap: 0.75rem; color: var(--gray); font-size: 0.95rem;">
              <i class="fa-solid fa-circle-check" style="color: #10b981;"></i>
              <span>No Co-signer Required</span>
            </div>
            <div style="display: flex; align-items: center; gap: 0.75rem; color: var(--gray); font-size: 0.95rem;">
              <i class="fa-solid fa-circle-check" style="color: #10b981;"></i>
              <span>No Collateral Required</span>
            </div>
            <div style="display: flex; align-items: center; gap: 0.75rem; color: var(--gray); font-size: 0.95rem;">
              <i class="fa-solid fa-circle-check" style="color: #10b981;"></i>
              <span>150+ Supported Countries</span>
            </div>
          </div>
          <div style="margin-top: 1rem;">
              <p style="font-size: 0.85rem; font-weight: 700; color: var(--dark); margin-bottom: 0.5rem;">Available for these destinations:</p>
              <div style="display: flex; gap: 0.5rem; font-size: 0.75rem; font-weight: 800; color: #64748b;">
                  <span title="United Kingdom">🇬🇧 GB</span>
                  <span title="United States">🇺🇸 US</span>
              </div>
          </div>
          <div style="margin-top: auto; padding-top: 1.5rem; border-top: 1px solid #f1f5f9; display: flex; flex-direction: column; gap: 0.75rem;">
              <a href="enquiry.php" class="btn btn--primary" style="width: 100%;">Enquire now</a>
          </div>
        </div>

        <div class="service-card animate-on-scroll delay-3" style="padding: 2.5rem; background: #fff; border-radius: 20px; border: 1px solid #f1f5f9; box-shadow: 0 10px 30px rgba(0,0,0,0.03); display: flex; flex-direction: column; gap: 1.5rem;">
          <div style="display: flex; justify-content: space-between; align-items: flex-start;">
            <h3 style="margin: 0; font-size: 1.5rem; color: var(--dark);">MPOWER Financing</h3>
            <div style="font-size: 2rem; color: var(--primary);"><i class="fa-solid fa-graduation-cap"></i></div>
          </div>
          <div class="grid grid--1 gap--05">
            <div style="display: flex; align-items: center; gap: 0.75rem; color: var(--gray); font-size: 0.95rem;">
              <i class="fa-solid fa-circle-check" style="color: #10b981;"></i>
              <span>Loans based on future earning potential</span>
            </div>
            <div style="display: flex; align-items: center; gap: 0.75rem; color: var(--gray); font-size: 0.95rem;">
              <i class="fa-solid fa-circle-check" style="color: #10b981;"></i>
              <span>No Cosigner Needed</span>
            </div>
            <div style="display: flex; align-items: center; gap: 0.75rem; color: var(--gray); font-size: 0.95rem;">
              <i class="fa-solid fa-circle-check" style="color: #10b981;"></i>
              <span>Visa Support Letters Provided</span>
            </div>
          </div>
          <div style="margin-top: 1rem;">
              <p style="font-size: 0.85rem; font-weight: 700; color: var(--dark); margin-bottom: 0.5rem;">Available for these destinations:</p>
              <div style="display: flex; gap: 0.5rem; font-size: 0.75rem; font-weight: 800; color: #64748b;">
                  <span title="Canada">🇨🇦 CA</span>
                  <span title="United States">🇺🇸 US</span>
              </div>
          </div>
          <div style="margin-top: auto; padding-top: 1.5rem; border-top: 1px solid #f1f5f9; display: flex; flex-direction: column; gap: 0.75rem;">
              <a href="enquiry.php" class="btn btn--primary" style="width: 100%;">Enquire now</a>
          </div>
        </div>
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

      <style>
        .process-hover-card {
            position: relative;
            padding: 4rem 2rem 3rem 2rem;
            color: white;
            display: flex;
            flex-direction: column;
            align-items: flex-start;
            justify-content: flex-end;
            min-height: 420px;
            text-align: left;
            overflow: hidden;
            cursor: pointer;
        }
        .process-hover-card .bg-img {
            position: absolute;
            inset: 0;
            background-size: cover;
            background-position: center;
            z-index: 0;
            transition: transform 0.6s cubic-bezier(0.16, 1, 0.3, 1);
        }
        .process-hover-card:hover .bg-img {
            transform: scale(1.1);
        }
        .process-hover-card .overlay-base {
            position: absolute;
            inset: 0;
            background: linear-gradient(to top, rgba(15,23,42,0.95) 0%, transparent 60%);
            z-index: 1;
            pointer-events: none;
        }
        .process-hover-card .overlay-hover {
            position: absolute;
            inset: 0;
            z-index: 2;
            opacity: 0;
            transition: opacity 0.4s ease;
            pointer-events: none;
        }
        .process-hover-card:hover .overlay-hover {
            opacity: 1;
        }
        .process-hover-card .content-wrap {
            position: relative;
            z-index: 3;
            display: flex;
            flex-direction: column;
            width: 100%;
            pointer-events: none;
            transform: translateY(40px);
            transition: transform 0.4s cubic-bezier(0.16, 1, 0.3, 1);
        }
        .process-hover-card:hover .content-wrap {
            transform: translateY(0);
        }
        .process-hover-card .process-text {
            opacity: 0;
            transition: opacity 0.4s ease;
            margin: 0;
            font-weight: 400;
            font-size: 0.95rem;
            line-height: 1.6;
            color: rgba(255,255,255,0.85);
            margin-top: 1rem;
        }
        .process-hover-card:hover .process-text {
            opacity: 1;
        }
      </style>
      
      <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); margin-top: 3rem; width: 100%; box-shadow: 0 10px 30px rgba(0,0,0,0.1); border-radius: 12px; overflow: hidden;">
        <!-- Card 1 -->
        <div class="process-hover-card animate-on-scroll">
           <div class="bg-img" style="background-image: url('assets/images/s1.jpg');"></div>
           <div class="overlay-base"></div>
           <div class="overlay-hover" style="background: linear-gradient(to top, rgba(15,23,42,0.95) 0%, rgba(30,58,138,0.8) 50%, rgba(30,58,138,0.7) 100%);"></div>
           <div class="content-wrap">
              <div style="font-size: 2.5rem; color: white; opacity: 0.9; margin-bottom: 0.5rem;"><i class="fa-solid fa-clipboard-check"></i></div>
              <h3 style="color: white; font-size: 1.4rem; font-weight: 700; margin-bottom: 0; line-height: 1.3;">1. Assessment</h3>
              <p class="process-text">We evaluate your required funds, co-applicant profile, and collateral options.</p>
           </div>
        </div>
        
        <!-- Card 2 -->
        <div class="process-hover-card animate-on-scroll delay-1">
           <div class="bg-img" style="background-image: url('assets/images/hero-bg-2.jpg');"></div>
           <div class="overlay-base"></div>
           <div class="overlay-hover" style="background: linear-gradient(to top, rgba(15,23,42,0.95) 0%, rgba(12,74,110,0.8) 50%, rgba(12,74,110,0.7) 100%);"></div>
           <div class="content-wrap">
              <div style="font-size: 2.5rem; color: white; opacity: 0.9; margin-bottom: 0.5rem;"><i class="fa-solid fa-building-columns"></i></div>
              <h3 style="color: white; font-size: 1.4rem; font-weight: 700; margin-bottom: 0; line-height: 1.3;">2. Bank Selection</h3>
              <p class="process-text">We compare offers from multiple banks to find the lowest interest rates.</p>
           </div>
        </div>

        <!-- Card 3 -->
        <div class="process-hover-card animate-on-scroll delay-2">
           <div class="bg-img" style="background-image: url('assets/images/s3.jpg');"></div>
           <div class="overlay-base"></div>
           <div class="overlay-hover" style="background: linear-gradient(to top, rgba(15,23,42,0.95) 0%, rgba(2,132,199,0.8) 50%, rgba(2,132,199,0.7) 100%);"></div>
           <div class="content-wrap">
              <div style="font-size: 2.5rem; color: white; opacity: 0.9; margin-bottom: 0.5rem;"><i class="fa-solid fa-folder-open"></i></div>
              <h3 style="color: white; font-size: 1.4rem; font-weight: 700; margin-bottom: 0; line-height: 1.3;">3. Documentation</h3>
              <p class="process-text">We help you gather and organize all necessary financial documents flawlessly.</p>
           </div>
        </div>

        <!-- Card 4 -->
        <div class="process-hover-card animate-on-scroll delay-3">
           <div class="bg-img" style="background-image: url('assets/images/hero-bg-3.jpg');"></div>
           <div class="overlay-base"></div>
           <div class="overlay-hover" style="background: linear-gradient(to top, rgba(15,23,42,0.95) 0%, rgba(15,118,110,0.8) 50%, rgba(15,118,110,0.7) 100%);"></div>
           <div class="content-wrap">
              <div style="font-size: 2.5rem; color: white; opacity: 0.9; margin-bottom: 0.5rem;"><i class="fa-solid fa-stamp"></i></div>
              <h3 style="color: white; font-size: 1.4rem; font-weight: 700; margin-bottom: 0; line-height: 1.3;">4. Sanction</h3>
              <p class="process-text">Get your loan sanction letter quickly to proceed with your visa application.</p>
           </div>
        </div>
      </div>
    </div>
  </section>

  <section class="section">
    <div class="container">
      <div class="text-center animate-on-scroll">
        <span class="section__tag">Benefits</span>
        <h2 class="section__title">Why Choose <span>Bluestone</span></h2>
        <p class="section__subtitle" style="max-width: 600px; margin: 0 auto;">Experience the advantage of working with industry-leading experts.</p>
      </div>
      <style>
      .benefit-card-modern {
          background: #ffffff;
          border-radius: 24px;
          padding: 3rem 2.5rem;
          position: relative;
          overflow: hidden;
          box-shadow: 0 10px 30px rgba(0,0,0,0.03);
          transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
          border: 1px solid rgba(0,0,0,0.03);
          z-index: 1;
      }
      .benefit-card-modern:hover {
          transform: translateY(-10px);
          box-shadow: 0 20px 40px rgba(0,0,0,0.08);
      }
      .benefit-icon-wrapper {
          width: 80px;
          height: 80px;
          border-radius: 24px;
          display: flex;
          align-items: center;
          justify-content: center;
          font-size: 2rem;
          margin-bottom: 2rem;
          color: white;
          position: relative;
          z-index: 2;
          transition: transform 0.4s ease;
      }
      .benefit-card-modern:hover .benefit-icon-wrapper {
          transform: scale(1.1) rotate(5deg);
      }
      .bg-shape {
          position: absolute;
          top: -50px;
          right: -50px;
          width: 200px;
          height: 200px;
          border-radius: 50%;
          opacity: 0.05;
          z-index: 0;
          transition: transform 0.8s ease;
      }
      .benefit-card-modern:hover .bg-shape {
          transform: scale(1.8);
      }
      /* Colors */
      .b-color-1 .benefit-icon-wrapper { background: linear-gradient(135deg, #38bdf8, #0284c7); box-shadow: 0 15px 30px rgba(56, 189, 248, 0.3); }
      .b-color-1 .bg-shape { background: #38bdf8; }

      .b-color-2 .benefit-icon-wrapper { background: linear-gradient(135deg, #f472b6, #db2777); box-shadow: 0 15px 30px rgba(244, 114, 182, 0.3); }
      .b-color-2 .bg-shape { background: #f472b6; }

      .b-color-3 .benefit-icon-wrapper { background: linear-gradient(135deg, #fbbf24, #d97706); box-shadow: 0 15px 30px rgba(251, 191, 36, 0.3); }
      .b-color-3 .bg-shape { background: #fbbf24; }
      </style>

      <div class="grid grid--3 gap--4" style="margin-top: 4rem;">
        <div class="benefit-card-modern b-color-1 animate-on-scroll">
          <div class="bg-shape"></div>
          <div class="benefit-icon-wrapper"><i class="fa-solid fa-building-columns"></i></div>
          <h3 style="font-size: 1.5rem; font-weight: 800; margin-bottom: 1rem; color: #0f172a;">Bank Tie-Ups</h3>
          <p style="color: #475569; line-height: 1.7; font-size: 1.1rem; font-weight: 400;">Direct partnerships with top banks ensure priority processing for our students.</p>
        </div>
        <div class="benefit-card-modern b-color-2 animate-on-scroll delay-1">
          <div class="bg-shape"></div>
          <div class="benefit-icon-wrapper"><i class="fa-solid fa-money-bill-transfer"></i></div>
          <h3 style="font-size: 1.5rem; font-weight: 800; margin-bottom: 1rem; color: #0f172a;">Unsecured Options</h3>
          <p style="color: #475569; line-height: 1.7; font-size: 1.1rem; font-weight: 400;">We help you secure loans up to significant amounts without requiring collateral.</p>
        </div>
        <div class="benefit-card-modern b-color-3 animate-on-scroll delay-2">
          <div class="bg-shape"></div>
          <div class="benefit-icon-wrapper"><i class="fa-solid fa-tags"></i></div>
          <h3 style="font-size: 1.5rem; font-weight: 800; margin-bottom: 1rem; color: #0f172a;">Exclusive Rates</h3>
          <p style="color: #475569; line-height: 1.7; font-size: 1.1rem; font-weight: 400;">Avail discounted processing fees and better interest rates through our channel.</p>
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
