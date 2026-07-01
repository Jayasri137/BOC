<?php
require_once 'includes/config.php';
$pageTitle = 'International Bank Account Opening | Bluestone Overseas';
$pageDesc = 'Open your international bank account before you arrive. Assistance for student banking in UK, Canada, Australia, and USA with leading global banks.';
require_once 'includes/header.php';
?>

<main>
    <section class="section">
        <div class="container">
            <!-- Destination Filter -->
            <div class="filter-card animate-on-scroll" style="margin-bottom: 4rem; background: #fff; padding: 2rem; border-radius: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.05); border: 1px solid #f1f5f9;">
                <form action="" method="GET" class="grid grid--2 gap--2 align-center" style="grid-template-columns: 1fr auto;">
                    <div>
                        <h3 style="margin: 0; font-size: 1.25rem;">Choose your destination to see pre-arrival student banking partners.</h3>
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
                $query = "SELECT * FROM essential_partners WHERE category = 'bank' AND is_active = 1";
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
                                    <i class="fa-solid fa-circle-check" style="color: var(--accent);"></i>
                                    <span><?= clean_output(trim($f)) ?></span>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        <div style="margin-top: auto; padding-top: 1.5rem; border-top: 1px solid #f1f5f9; display: flex; justify-content: space-between; align-items: center;">
                            <div style="display: flex; align-items: center; gap: 0.5rem; color: var(--accent); font-weight: 700;">
                                <i class="fa-solid fa-building-columns"></i>
                                <span>Premier Student Banking</span>
                            </div>
                            <a href="<?= clean_output($p['link']) ?>" class="btn btn--outline btn--sm">Open Account</a>
                        </div>
                    </div>
                <?php 
                    endforeach;
                else:
                ?>
                    <div class="col-span-2 text-center py-5">
                        <p style="color: var(--gray);">No specific bank partners found for this destination yet. Contact us for assistance.</p>
                        <a href="consultation.php" class="btn btn--primary btn--sm" style="margin-top: 1rem;">Get Banking Advice</a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <section class="section">
        <div class="container">
            <div class="grid grid--2 gap--4 align-center">
                <div class="animate-on-scroll">
                    <div class="v-icon" style="width:100px; height:100px; font-size:2.5rem; margin:0; color:var(--accent);"><i class="fa-solid fa-building-columns"></i></div>
                    <h2 class="section__title" style="text-align:left; margin-top:2rem">Seamless <span>Student Banking</span></h2>
                    <p style="color:var(--gray); margin-top:1rem; line-height:1.7;">
                        Setting up a bank account is one of the first things you'll need to do as an international student. We facilitate <strong>Pre-Arrival Account Opening</strong> with major banks, so you can transfer your funds safely and have your debit card ready when you land.
                    </p>
                </div>
                <div class="animate-on-scroll delay-1">
                    <div class="grid grid--2 gap--1">
                        <div class="service-card text-center" style="padding: 1.5rem;">
                            <i class="fa-solid fa-flag-usa" style="font-size: 2rem; color: #ef4444;"></i>
                            <h4 style="margin-top: 1rem;">USA</h4>
                            <p style="font-size: 0.8rem;">BOA, Chase, Wells Fargo</p>
                        </div>
                        <div class="service-card text-center" style="padding: 1.5rem;">
                            <i class="fa-solid fa-map-pin" style="font-size: 2rem; color: #0ea5e9;"></i>
                            <h4 style="margin-top: 1rem;">UK</h4>
                            <p style="font-size: 0.8rem;">HSBC, Barclays, Lloyds</p>
                        </div>
                        <div class="service-card text-center" style="padding: 1.5rem;">
                            <i class="fa-solid fa-leaf" style="font-size: 2rem; color: #dc2626;"></i>
                            <h4 style="margin-top: 1rem;">Canada</h4>
                            <p style="font-size: 0.8rem;">CIBC, RBC, Scotiabank</p>
                        </div>
                        <div class="service-card text-center" style="padding: 1.5rem;">
                            <i class="fa-solid fa-earth-oceania" style="font-size: 2rem; color: #10b981;"></i>
                            <h4 style="margin-top: 1rem;">Australia</h4>
                            <p style="font-size: 0.8rem;">CommBank, ANZ, NAB</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="section bg-light">
        <div class="container">
            <div class="text-center animate-on-scroll" style="margin-bottom: 3rem;">
                <h2 class="section__title">Why Open <span>Pre-Arrival?</span></h2>
                <p class="section__subtitle">Avoid the stress of visiting banks and waiting in queues after you land.</p>
            </div>
            <div class="grid grid--3 gap--2">
                <div class="service-card animate-on-scroll">
                    <div class="stat-icon stat-icon--blue"><i class="fa-solid fa-shield-check"></i></div>
                    <h3>Fund Security</h3>
                    <p>Transfer your tuition and living expenses to your own international account before you leave India.</p>
                </div>
                <div class="service-card animate-on-scroll delay-1">
                    <div class="stat-icon stat-icon--purple"><i class="fa-solid fa-address-card"></i></div>
                    <h3>Visa Proof</h3>
                    <p>For many countries, a pre-opened and funded account serves as strong proof of financial capacity.</p>
                </div>
                <div class="service-card animate-on-scroll delay-2">
                    <div class="stat-icon stat-icon--orange"><i class="fa-solid fa-bolt"></i></div>
                    <h3>Instant Access</h3>
                    <p>Get your debit card and account activation within 24 hours of landing in your new country.</p>
                </div>
            </div>
        </div>
    </section>

    <section class="section" style="padding-top: 0;">
        <div class="container animate-on-scroll">
            <div style="background: var(--gradient); padding: 4rem 2rem; border-radius: var(--radius-lg); text-align: center; color: white; box-shadow: var(--shadow-lg);">
                <h2 style="font-size: 2.5rem; margin-bottom: 1rem;">Start Your Application</h2>
                <p style="font-size: 1.1rem; opacity: 0.9; max-width: 600px; margin: 0 auto 2rem;">Our banking specialists will guide you through the digital onboarding process for your chosen country.</p>
                <a href="consultation.php" class="btn btn--white btn--lg pulse-btn" style="background: white; color: var(--primary);">Open Account Now</a>
            </div>
        </div>
    </section>
</main>

<?php require_once 'includes/footer.php'; ?>
