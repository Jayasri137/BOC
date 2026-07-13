<?php
require_once 'includes/config.php';
$pageTitle = 'Education Loan Assistance for Study Abroad | Bluestone Overseas';
$pageDesc = 'Secure education loans with expert guidance for tuition fees and living expenses abroad.';
require_once 'includes/header.php';
?>
<main>
<div class="container" style="padding-top: 2rem; padding-bottom: 1rem;"><h1 class="section__title" style="text-align:center; margin:0; font-size: 2.2rem;">Education Loan Support for International Students</h1></div>

  <section class="section">
    <div class="container">
      <!-- Destination Filter -->
      <div class="filter-card animate-on-scroll" style="margin-bottom: 4rem; background: #fff; padding: 2rem; border-radius: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.05); border: 1px solid #f1f5f9;">
        <form action="" method="GET" class="grid grid--2 gap--2 align-center" style="grid-template-columns: 1fr auto;">
          <div>
            <h3 style="margin: 0; font-size: 1.25rem;">Discover recommended products for your study destination.</h3>
          </div>
          <div style="display: flex; gap: 1rem;">
            <select name="destination" class="form-control" style="min-width: 250px; padding: 0.75rem 1rem; border-radius: 10px; border: 1px solid #e2e8f0;" onchange="this.form.submit()">
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
        <?php
        $query = "SELECT * FROM essential_partners WHERE category = 'loan' AND is_active = 1";
        $params = [];
        if ($selectedDest) {
            $query .= " AND (country_name = ? OR country_name = 'Global')";
            $params[] = $selectedDest;
        }
        $stmt = $pdo->prepare($query);
        $stmt->execute($params);
        $partners = $stmt->fetchAll();
        $partners = array_values(array_filter($partners, function ($p) {
            return !is_hidden_partner($p);
        }));

        if ($partners):
            foreach ($partners as $p):
                $features = explode(',', $p['features']);
        ?>
          <div class="service-card animate-on-scroll" style="padding: 2.5rem; background: #fff; border-radius: 20px; border: 1px solid #f1f5f9; box-shadow: 0 10px 30px rgba(0,0,0,0.03); display: flex; flex-direction: column; gap: 1.5rem;">
            <div style="display: flex; justify-content: space-between; align-items: flex-start;">
              <h3 style="margin: 0; font-size: 1.5rem; color: var(--dark);"><?= clean_output($p['partner_name']) ?></h3>
              <img src="<?= clean_output($p['logo_path']) ?>" alt="<?= clean_output($p['partner_name']) ?>" style="height: 40px; width: auto; object-fit: contain;">
            </div>
            <div class="grid grid--1 gap--05">
              <?php foreach ($features as $f): ?>
                <div style="display: flex; align-items: center; gap: 0.75rem; color: var(--gray); font-size: 0.95rem;">
                  <i class="fa-solid fa-circle-check" style="color: #10b981;"></i>
                  <span><?= clean_output(trim($f)) ?></span>
                </div>
              <?php endforeach; ?>
            </div>
            
            <?php if ($p['country_name'] == 'Global'): ?>
            <div style="margin-top: 1rem;">
                <p style="font-size: 0.85rem; font-weight: 700; color: var(--dark); margin-bottom: 0.5rem;">Available for these destinations:</p>
                <div style="display: flex; gap: 0.5rem; font-size: 0.75rem; font-weight: 800; color: #64748b;">
                    <span title="Australia">🇦🇺 AU</span>
                    <span title="Canada">🇨🇦 CA</span>
                    <span title="United Kingdom">🇬🇧 GB</span>
                    <span title="Ireland">🇮🇪 IE</span>
                    <span title="New Zealand">🇳🇿 NZ</span>
                    <span title="United States">🇺🇸 US</span>
                </div>
            </div>
            <?php endif; ?>

            <div style="margin-top: auto; padding-top: 1.5rem; border-top: 1px solid #f1f5f9; display: flex; flex-direction: column; gap: 0.75rem;">
                <a href="enquiry.php" class="btn btn--primary" style="width: 100%;">Enquire now</a>
                <a href="<?= clean_output($p['link']) ?>" class="btn btn--outline" style="width: 100%;">Learn more</a>
            </div>
          </div>
        <?php 
            endforeach;
        else:
        ?>
          <div class="col-span-2 text-center py-5">
            <p style="color: var(--gray);">No specific tie-ups found for this destination yet. Our global partners can still assist you.</p>
            <a href="consultation.php" class="btn btn--primary btn--sm" style="margin-top: 1rem;">Speak to a Counsellor</a>
          </div>
        <?php endif; ?>
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
      <div class="grid grid--4 gap--2" style="margin-top: 3rem;">
        <div class="service-card text-center animate-on-scroll">
          <div class="service-card__icon service-card__icon--blue" style="margin: 0 auto 1.5rem;"><i class="fa-solid fa-1"></i></div>
          <h3>Assessment</h3>
          <p>We evaluate your required funds, co-applicant profile, and collateral options.</p>
        </div>
        <div class="service-card text-center animate-on-scroll delay-1">
          <div class="service-card__icon service-card__icon--purple" style="margin: 0 auto 1.5rem;"><i class="fa-solid fa-2"></i></div>
          <h3>Bank Selection</h3>
          <p>We compare offers from multiple banks to find the lowest interest rates.</p>
        </div>
        <div class="service-card text-center animate-on-scroll delay-2">
          <div class="service-card__icon service-card__icon--orange" style="margin: 0 auto 1.5rem;"><i class="fa-solid fa-3"></i></div>
          <h3>Documentation</h3>
          <p>We help you gather and organize all necessary financial documents flawlessly.</p>
        </div>
        <div class="service-card text-center animate-on-scroll delay-3">
          <div class="service-card__icon service-card__icon--teal" style="margin: 0 auto 1.5rem;"><i class="fa-solid fa-4"></i></div>
          <h3>Sanction</h3>
          <p>Get your loan sanction letter quickly to proceed with your visa application.</p>
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
      <div class="grid grid--3 gap--2" style="margin-top: 3rem;">
        <div class="service-card animate-on-scroll">
          <h3 style="display: flex; align-items: center; gap: 0.5rem;"><i class="fa-solid fa-building-columns text-primary"></i> Bank Tie-Ups</h3>
          <p>Direct partnerships with top banks ensure priority processing for our students.</p>
        </div>
        <div class="service-card animate-on-scroll delay-1">
          <h3 style="display: flex; align-items: center; gap: 0.5rem;"><i class="fa-solid fa-money-bill-transfer text-primary"></i> Unsecured Options</h3>
          <p>We help you secure loans up to significant amounts without requiring collateral.</p>
        </div>
        <div class="service-card animate-on-scroll delay-2">
          <h3 style="display: flex; align-items: center; gap: 0.5rem;"><i class="fa-solid fa-tags text-primary"></i> Exclusive Rates</h3>
          <p>Avail discounted processing fees and better interest rates through our channel.</p>
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
