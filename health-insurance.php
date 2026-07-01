<?php
require_once 'includes/config.php';
$pageTitle = 'Student Health Insurance (OSHC) | Bluestone Overseas';
$pageDesc = 'Get the best overseas student health cover (OSHC) and international insurance for your study abroad journey. Affordable and mandatory cover for Australia, UK, and more.';
require_once 'includes/header.php';
?>

<main>
    <section class="section">
        <div class="container">
            <!-- Destination Filter -->
            <div class="filter-card animate-on-scroll" style="margin-bottom: 4rem; background: #fff; padding: 2rem; border-radius: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.05); border: 1px solid #f1f5f9;">
                <form action="" method="GET" class="grid grid--2 gap--2 align-center" style="grid-template-columns: 1fr auto;">
                    <div>
                        <h3 style="margin: 0; font-size: 1.25rem;">Select your study destination for tailored health cover options.</h3>
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

            <div class="grid grid--2 gap--4">
                <?php
                $query = "SELECT * FROM essential_partners WHERE category = 'insurance' AND is_active = 1";
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
                                    <i class="fa-solid fa-shield-check" style="color: var(--primary);"></i>
                                    <span><?= clean_output(trim($f)) ?></span>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        <div style="margin-top: auto; padding-top: 1.5rem; border-top: 1px solid #f1f5f9; display: flex; justify-content: space-between; align-items: center;">
                            <div style="display: flex; align-items: center; gap: 0.5rem; color: var(--primary); font-weight: 700;">
                                <i class="fa-solid fa-heart-pulse"></i>
                                <span>Visa Approved Cover</span>
                            </div>
                            <a href="<?= clean_output($p['link']) ?>" class="btn btn--outline btn--sm">Get Quote</a>
                        </div>
                    </div>
                <?php 
                    endforeach;
                else:
                ?>
                    <div class="col-span-2 text-center py-5">
                        <p style="color: var(--gray);">No insurance partners found for this specific destination. Contact us for a custom solution.</p>
                        <a href="consultation.php" class="btn btn--primary btn--sm" style="margin-top: 1rem;">Ask Our Experts</a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <section class="section bg-light">
        <div class="container">
            <div class="grid grid--2 gap--4 align-center">
                <div class="animate-on-scroll">
                    <div class="v-icon" style="width:100px; height:100px; font-size:2.5rem; margin:0"><i class="fa-solid fa-shield-heart"></i></div>
                    <h2 class="section__title" style="text-align:left; margin-top:2rem">Protect Your <span>Well-being</span> Abroad</h2>
                    <p style="color:var(--gray); margin-top:1rem; line-height:1.7;">
                        Most countries require international students to have valid health insurance for the entire duration of their studies. Whether it's **OSHC for Australia**, **IHS for the UK**, or private medical cover for the **USA and Canada**, we ensure you have the right policy that meets visa requirements.
                    </p>
                </div>
                <div class="animate-on-scroll delay-1">
                    <div class="service-card" style="padding:2rem; border-radius:20px; box-shadow:var(--shadow-lg); border:1px solid #f1f5f9; background:#fff;">
                        <div class="v-icon" style="width:72px; height:72px; font-size:1.75rem; margin:0 0 1rem; color:var(--primary);"><i class="fa-solid fa-shield-heart"></i></div>
                        <h3 style="margin-bottom:0.75rem; color:var(--dark);">Reliable support for every visa requirement</h3>
                        <p style="color:var(--gray); line-height:1.7; margin:0;">We guide you through mandatory cover, premium comparison, and claim support so your move abroad is stress-free.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="section bg-light">
        <div class="container">
            <div class="text-center animate-on-scroll" style="margin-bottom: 3rem;">
                <h2 class="section__title">Insurance by <span>Destination</span></h2>
                <p class="section__subtitle">Different countries have specific health cover mandates for international students.</p>
            </div>
            <div class="grid grid--3 gap--2">
                <div class="service-card animate-on-scroll">
                    <div style="font-size: 1.5rem; font-weight: 800; color: var(--primary); margin-bottom: 1rem;">AUSTRALIA</div>
                    <h3 style="font-size: 1.25rem;">OSHC (Overseas Student Health Cover)</h3>
                    <p>Mandatory for Subclass 500 visa. Covers doctor visits, hospital, ambulance, and limited medicines.</p>
                </div>
                <div class="service-card animate-on-scroll delay-1">
                    <div style="font-size: 1.5rem; font-weight: 800; color: var(--accent); margin-bottom: 1rem;">UK</div>
                    <h3 style="font-size: 1.25rem;">IHS (Immigration Health Surcharge)</h3>
                    <p>Paid during visa application, giving you access to the National Health Service (NHS) just like a UK resident.</p>
                </div>
                <div class="service-card animate-on-scroll delay-2">
                    <div style="font-size: 1.5rem; font-weight: 800; color: #f97316; margin-bottom: 1rem;">USA & CANADA</div>
                    <h3 style="font-size: 1.25rem;">International Student Health Plan</h3>
                    <p>Customizable private insurance plans required by universities to cover high medical costs in North America.</p>
                </div>
            </div>
        </div>
    </section>

    <section class="section">
        <div class="container">
            <div class="text-center animate-on-scroll" style="margin-bottom: 4rem;">
                <h2 class="section__title">Frequently Asked <span>Questions</span></h2>
            </div>
            <div style="max-width: 800px; margin: 0 auto;" class="animate-on-scroll">
                <div class="faq-item" style="margin-bottom: 1.5rem; padding: 1.5rem; background: #fff; border-radius: 12px; border: 1px solid #f1f5f9;">
                    <h4 style="margin-bottom: 0.75rem; color: var(--dark);">Is health insurance mandatory for a student visa?</h4>
                    <p style="color: var(--gray); font-size: 0.95rem;">Yes, for countries like Australia and the UK, health cover is a mandatory prerequisite for visa approval. In others, it is required by the university before enrollment.</p>
                </div>
                <div class="faq-item" style="margin-bottom: 1.5rem; padding: 1.5rem; background: #fff; border-radius: 12px; border: 1px solid #f1f5f9;">
                    <h4 style="margin-bottom: 0.75rem; color: var(--dark);">Can I choose my own insurance provider?</h4>
                    <p style="color: var(--gray); font-size: 0.95rem;">In Australia, you can choose from government-approved providers. In the UK, it's a fixed surcharge. We help you compare providers in the USA and Canada to find the best value.</p>
                </div>
            </div>
        </div>
    </section>

    <section class="section" style="padding-top: 0;">
        <div class="container animate-on-scroll">
            <div style="background: var(--gradient); padding: 4rem 2rem; border-radius: var(--radius-lg); text-align: center; color: white; box-shadow: var(--shadow-lg);">
                <h2 style="font-size: 2.5rem; margin-bottom: 1rem;">Get Your Health Cover Today</h2>
                <p style="font-size: 1.1rem; opacity: 0.9; max-width: 600px; margin: 0 auto 2rem;">Need a quote for OSHC or private medical insurance? Our team will help you find the best plan in minutes.</p>
                <a href="consultation.php" class="btn btn--white btn--lg pulse-btn" style="background: white; color: var(--primary);">Contact Insurance Expert</a>
            </div>
        </div>
    </section>
</main>

<?php require_once 'includes/footer.php'; ?>
