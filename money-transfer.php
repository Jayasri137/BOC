<?php
require_once 'includes/config.php';
$pageTitle = 'International Money Transfer & Forex | Bluestone Overseas';
$pageDesc = 'Pay your tuition fees and send living expenses abroad with Bluestone Overseas. Fast, secure, and affordable international money transfers at the best exchange rates.';
require_once 'includes/header.php';
?>

<main>
    <section class="section">
        <div class="container">
            <!-- Destination Filter -->
            <div class="filter-card animate-on-scroll" style="margin-bottom: 4rem; background: #fff; padding: 2rem; border-radius: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.05); border: 1px solid #f1f5f9;">
                <form action="" method="GET" class="grid grid--2 gap--2 align-center" style="grid-template-columns: 1fr auto;">
                    <div>
                        <h3 style="margin: 0; font-size: 1.25rem;">Compare exchange rates and fee payment options for your destination.</h3>
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

            <div class="grid grid--2 gap--4 align-center animate-on-scroll" style="margin-bottom: 4rem;">
        <div>
          <h2 style="font-size: 1.75rem; margin-bottom: 0.5rem; color: var(--dark);">Seamless money transfer solutions</h2>
          <div style="width: 50px; height: 3px; background: #16a34a; margin-bottom: 1.5rem;"></div>
          <p style="color: var(--gray); line-height: 1.6;">Whether it's paying admission fees to your institution, purchasing health insurance, or covering accommodation costs, we've got you sorted. Our partners provide seamless experiences at competitive rates, so you can focus on your studies with peace of mind.</p>
        </div>
        <div style="background: linear-gradient(135deg, #16a34a, #22c55e); padding: 2rem; border-radius: 24px; color: #fff; display: flex; align-items: center; justify-content: center; position: relative; overflow: hidden;">
            <div style="z-index: 1;">
                <h3 style="margin-bottom: 0.5rem;">Transfer money globally with confidence</h3>
                <p style="font-size: 0.9rem; opacity: 0.9;">Learn to send safe payments for tuition, rent & more</p>
                <div style="margin-top: 1.5rem; width: 60px; height: 60px; background: rgba(255,255,255,0.2); border-radius: 50%; display: grid; place-items: center; font-size: 1.5rem;">
                    <i class="fa-solid fa-play"></i>
                </div>
            </div>
            <i class="fa-solid fa-earth-americas" style="position: absolute; right: -20px; bottom: -20px; font-size: 8rem; opacity: 0.1;"></i>
        </div>
      </div>

      <div class="animate-on-scroll" style="margin-bottom: 2rem;">
        <h3 style="font-size: 1.5rem; color: var(--dark);">Explore our global partners</h3>
        <div style="width: 40px; height: 3px; background: #16a34a; margin-top: 0.5rem;"></div>
      </div>

      <div class="grid grid--2 gap--4" style="margin-bottom: 4rem;">
        <?php
        $query = "SELECT * FROM essential_partners WHERE category = 'forex' AND is_active = 1 AND (partner_name LIKE '%Flywire%' OR partner_name LIKE '%Convera%')";
        $params = [];
        if ($selectedDest) {
            $query .= " AND (country_name = ? OR country_name = 'Global')";
            $params[] = $selectedDest;
        }
        $stmt = $pdo->prepare($query);
        $stmt->execute($params);
        $partners = $stmt->fetchAll();

        foreach ($partners as $p):
            $features = explode(',', $p['features']);
        ?>
          <div class="service-card" style="padding: 2.5rem; background: #fff; border-radius: 20px; border: 1px solid #f1f5f9; box-shadow: 0 10px 30px rgba(0,0,0,0.03); display: flex; flex-direction: column; gap: 1.5rem;">
            <div style="display: flex; justify-content: space-between; align-items: flex-start;">
              <h3 style="margin: 0; font-size: 1.5rem; color: var(--dark);"><?= clean_output($p['partner_name']) ?></h3>
              <img src="<?= clean_output($p['logo_path']) ?>" alt="<?= clean_output($p['partner_name']) ?>" style="height: 35px; width: auto; object-fit: contain;">
            </div>
            <div style="font-size: 0.9rem; font-weight: 700; color: #16a34a; display: flex; align-items: center; gap: 0.5rem;">
                <span>Best Price Guarantee</span>
                <i class="fa-solid fa-circle-info"></i>
            </div>
            <div class="grid grid--2 gap--05">
              <?php foreach ($features as $f): ?>
                <div style="display: flex; align-items: center; gap: 0.75rem; color: var(--gray); font-size: 0.9rem;">
                  <i class="fa-solid fa-circle-check" style="color: #16a34a;"></i>
                  <span><?= clean_output(trim($f)) ?></span>
                </div>
              <?php endforeach; ?>
            </div>
            
            <?php if ($p['country_name'] == 'Global'): ?>
            <div style="margin-top: 1rem;">
                <p style="font-size: 0.85rem; font-weight: 700; color: var(--dark); margin-bottom: 0.5rem;">Available for these destinations:</p>
                <div style="display: flex; gap: 0.5rem; font-size: 0.75rem; font-weight: 800; color: #64748b;">
                    <span>🇦🇺 AU</span><span>🇨🇦 CA</span><span>🇬🇧 GB</span><span>🇮🇪 IE</span><span>🇳🇿 NZ</span><span>🇺🇸 US</span>
                </div>
            </div>
            <?php endif; ?>

            <div style="margin-top: auto; padding-top: 1.5rem; border-top: 1px solid #f1f5f9; display: flex; flex-direction: column; gap: 0.75rem;">
                <a href="enquiry.php" class="btn" style="width: 100%; background: #0066ff; color: #fff;">Enquire now</a>
                <a href="<?= clean_output($p['link']) ?>" class="btn btn--outline" style="width: 100%;">Learn more</a>
            </div>
          </div>
        <?php endforeach; ?>
      </div>

      <div class="animate-on-scroll" style="margin-bottom: 2rem;">
        <h3 style="font-size: 1.5rem; color: var(--dark);">Explore our global partners for living expenses</h3>
        <div style="width: 40px; height: 3px; background: #16a34a; margin-top: 0.5rem;"></div>
      </div>

      <div class="grid grid--2 gap--4">
        <?php
        $query = "SELECT * FROM essential_partners WHERE category = 'forex' AND is_active = 1 AND (partner_name LIKE '%WSFx%' OR partner_name LIKE '%EBIXCASH%')";
        $params = [];
        if ($selectedDest) {
            $query .= " AND (country_name = ? OR country_name = 'Global')";
            $params[] = $selectedDest;
        }
        $stmt = $pdo->prepare($query);
        $stmt->execute($params);
        $partners = $stmt->fetchAll();

        foreach ($partners as $p):
            $features = explode(',', $p['features']);
        ?>
          <div class="service-card" style="padding: 2.5rem; background: #fff; border-radius: 20px; border: 1px solid #f1f5f9; box-shadow: 0 10px 30px rgba(0,0,0,0.03); display: flex; flex-direction: column; gap: 1.5rem;">
            <div style="display: flex; justify-content: space-between; align-items: flex-start;">
              <h3 style="margin: 0; font-size: 1.5rem; color: var(--dark);"><?= clean_output($p['partner_name']) ?></h3>
              <img src="<?= clean_output($p['logo_path']) ?>" alt="<?= clean_output($p['partner_name']) ?>" style="height: 35px; width: auto; object-fit: contain;">
            </div>
            <div class="grid grid--1 gap--05">
              <?php foreach ($features as $f): ?>
                <div style="display: flex; align-items: center; gap: 0.75rem; color: var(--gray); font-size: 0.9rem;">
                  <i class="fa-solid fa-circle-check" style="color: #16a34a;"></i>
                  <span><?= clean_output(trim($f)) ?></span>
                </div>
              <?php endforeach; ?>
            </div>

            <?php if ($p['country_name'] == 'Global'): ?>
            <div style="margin-top: 1rem;">
                <p style="font-size: 0.85rem; font-weight: 700; color: var(--dark); margin-bottom: 0.5rem;">Available for these destinations:</p>
                <div style="display: flex; gap: 0.5rem; font-size: 0.75rem; font-weight: 800; color: #64748b;">
                    <span>🇦🇺 AU</span><span>🇨🇦 CA</span><span>🇬🇧 GB</span><span>🇮🇪 IE</span><span>🇳🇿 NZ</span><span>🇺🇸 US</span>
                </div>
            </div>
            <?php endif; ?>

            <div style="margin-top: auto; padding-top: 1.5rem; border-top: 1px solid #f1f5f9; display: flex; flex-direction: column; gap: 0.75rem;">
                <a href="enquiry.php" class="btn" style="width: 100%; background: #0066ff; color: #fff;">Enquire now</a>
                <a href="<?= clean_output($p['link']) ?>" class="btn btn--outline" style="width: 100%;">Learn more</a>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
      </div>
        </div>
    </section>

    <section class="section">
        <div class="container">
            <div class="grid grid--2 gap--4 align-center">
                <div class="animate-on-scroll">
                    <div class="v-icon" style="width:100px; height:100px; font-size:2.5rem; margin:0; color:#16a34a;"><i class="fa-solid fa-money-bill-transfer"></i></div>
                    <h2 class="section__title" style="text-align:left; margin-top:2rem">Smart Way to <span>Pay Fees</span></h2>
                    <p style="color:var(--gray); margin-top:1rem; line-height:1.7;">
                        Paying your university tuition fees or sending monthly living expenses shouldn't be a hassle. We partner with leading financial institutions to offer you <strong>Bank-Beating Exchange Rates</strong> and <strong>Zero-Fee transfers</strong> for students.
                    </p>
                </div>
                <div class="animate-on-scroll delay-1">
                    <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 24px; padding: 3rem; text-align: center;">
                        <div style="font-size: 3rem; color: #16a34a; margin-bottom: 1.5rem;"><i class="fa-solid fa-vault"></i></div>
                        <h3 style="font-size: 1.5rem; margin-bottom: 1rem;">Lowest Rate Guarantee</h3>
                        <p style="color: var(--gray); font-size: 0.9rem;">We compare rates across multiple providers to ensure you get more currency for your Rupee.</p>
                        <div style="margin-top: 2rem; display: flex; flex-direction: column; gap: 0.75rem;">
                            <div style="background: #fff; padding: 1rem; border-radius: 12px; display: flex; justify-content: space-between; align-items: center; border: 1px solid #f1f5f9;">
                                <span style="font-weight: 700;">USD</span>
                                <span style="color: #16a34a; font-weight: 800;">₹ 83.45*</span>
                            </div>
                            <div style="background: #fff; padding: 1rem; border-radius: 12px; display: flex; justify-content: space-between; align-items: center; border: 1px solid #f1f5f9;">
                                <span style="font-weight: 700;">GBP</span>
                                <span style="color: #16a34a; font-weight: 800;">₹ 105.12*</span>
                            </div>
                            <div style="background: #fff; padding: 1rem; border-radius: 12px; display: flex; justify-content: space-between; align-items: center; border: 1px solid #f1f5f9;">
                                <span style="font-weight: 700;">EUR</span>
                                <span style="color: #16a34a; font-weight: 800;">₹ 90.88*</span>
                            </div>
                        </div>
                        <small style="display: block; margin-top: 1rem; color: var(--gray);">*Rates shown are for illustration only.</small>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="section bg-light">
        <div class="container">
            <div class="text-center animate-on-scroll" style="margin-bottom: 3rem;">
                <h2 class="section__title">Our <span>Solutions</span></h2>
                <p class="section__subtitle">Comprehensive financial tools for the modern international student.</p>
            </div>
            <div class="grid grid--3 gap--2">
                <div class="service-card animate-on-scroll">
                    <div class="stat-icon stat-icon--blue"><i class="fa-solid fa-graduation-cap"></i></div>
                    <h3>Fee Payments</h3>
                    <p>Pay your tuition and accommodation fees directly to the university account via wire transfer or portal payments.</p>
                </div>
                <div class="service-card animate-on-scroll delay-1">
                    <div class="stat-icon stat-icon--purple"><i class="fa-solid fa-credit-card"></i></div>
                    <h3>Forex Cards</h3>
                    <p>Reloadable multi-currency cards for your daily expenses, shopping, and ATM withdrawals abroad.</p>
                </div>
                <div class="service-card animate-on-scroll delay-2">
                    <div class="stat-icon stat-icon--orange"><i class="fa-solid fa-piggy-bank"></i></div>
                    <h3>GIC & Blocked Accounts</h3>
                    <p>Assistance in opening GIC (Canada) or Blocked Accounts (Germany) required for student visa applications.</p>
                </div>
            </div>
        </div>
    </section>

    <section class="section">
        <div class="container animate-on-scroll">
            <div style="background: var(--gradient); padding: 4rem 2rem; border-radius: var(--radius-lg); text-align: center; color: white; box-shadow: var(--shadow-lg);">
                <h2 style="font-size: 2.5rem; margin-bottom: 1rem;">Save More on Transfers</h2>
                <p style="font-size: 1.1rem; opacity: 0.9; max-width: 600px; margin: 0 auto 2rem;">Get an instant quote for your tuition fee payment or book a forex card today.</p>
                <a href="consultation.php" class="btn btn--white btn--lg pulse-btn" style="background: white; color: var(--primary);">Check Best Rates</a>
            </div>
        </div>
    </section>
</main>

<?php require_once 'includes/footer.php'; ?>
