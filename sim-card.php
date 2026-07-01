<?php
require_once 'includes/config.php';
$pageTitle = 'International SIM Cards for Students | Bluestone Overseas';
$pageDesc = 'Get your international SIM card before you depart India. Stay connected with family from the moment you land with our affordable student mobile plans.';
require_once 'includes/header.php';
?>

<main>
    <section class="section">
        <div class="container">
            <!-- Destination Filter -->
            <div class="filter-card animate-on-scroll" style="margin-bottom: 4rem; background: #fff; padding: 2rem; border-radius: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.05); border: 1px solid #f1f5f9;">
                <form action="" method="GET" class="grid grid--2 gap--2 align-center" style="grid-template-columns: 1fr auto;">
                    <div>
                        <h3 style="margin: 0; font-size: 1.25rem;">Choose your destination to see pre-activated SIM card providers.</h3>
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
                $query = "SELECT * FROM essential_partners WHERE category = 'sim' AND is_active = 1";
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
                            <i class="fa-solid fa-sim-card" style="font-size: 2rem; color: #f43f5e;"></i>
                        </div>
                        <div class="grid grid--1 gap--05">
                            <?php foreach ($features as $f): ?>
                                <div style="display: flex; align-items: center; gap: 0.75rem; color: var(--gray); font-size: 0.95rem;">
                                    <i class="fa-solid fa-circle-check" style="color: #f43f5e;"></i>
                                    <span><?= clean_output(trim($f)) ?></span>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        <div style="margin-top: auto; padding-top: 1.5rem; border-top: 1px solid #f1f5f9; display: flex; justify-content: space-between; align-items: center;">
                            <div style="display: flex; align-items: center; gap: 0.5rem; color: #f43f5e; font-weight: 700;">
                                <i class="fa-solid fa-signal"></i>
                                <span>Global Roaming Ready</span>
                            </div>
                            <a href="<?= clean_output($p['link']) ?>" class="btn btn--outline btn--sm">Claim SIM</a>
                        </div>
                    </div>
                <?php 
                    endforeach;
                else:
                ?>
                    <div class="col-span-2 text-center py-5">
                        <p style="color: var(--gray);">We provide universal SIM cards that work in almost any country. Contact us to reserve yours.</p>
                        <a href="consultation.php" class="btn btn--primary btn--sm" style="margin-top: 1rem;">Reserve My SIM</a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <section class="section">
        <div class="container">
            <div class="grid grid--2 gap--4 align-center">
                <div class="animate-on-scroll">
                    <div class="v-icon" style="width:100px; height:100px; font-size:2.5rem; margin:0; color:#f43f5e;"><i class="fa-solid fa-mobile-screen-button"></i></div>
                    <h2 class="section__title" style="text-align:left; margin-top:2rem">Pre-Activated <span>SIM Cards</span></h2>
                    <p style="color:var(--gray); margin-top:1rem; line-height:1.7;">
                        Finding a mobile store and providing local ID after a long flight can be stressful. We ensure you have your <strong>Local Number</strong> ready before you leave India. Our SIM cards work the moment you land, allowing you to use maps, call your family, or book an Uber immediately.
                    </p>
                </div>
                <div class="animate-on-scroll delay-1">
                    <div style="background: white; border: 2px dashed #e2e8f0; border-radius: 24px; padding: 2rem; position: relative;">
                        <div style="position: absolute; top: -15px; right: 20px; background: #f43f5e; color: white; padding: 0.3rem 1rem; border-radius: 50px; font-weight: 700; font-size: 0.8rem;">BEST SELLER</div>
                        <h4 style="margin-bottom: 1.5rem; display: flex; align-items: center; gap: 0.5rem;"><i class="fa-solid fa-bolt" style="color: #f59e0b;"></i> Student Global Plan</h4>
                        <div style="display: flex; flex-direction: column; gap: 1rem;">
                            <div style="display: flex; align-items: center; gap: 1rem; padding-bottom: 1rem; border-bottom: 1px solid #f1f5f9;">
                                <i class="fa-solid fa-wifi" style="color: var(--primary);"></i>
                                <span><strong>50GB</strong> High-Speed Data</span>
                            </div>
                            <div style="display: flex; align-items: center; gap: 1rem; padding-bottom: 1rem; border-bottom: 1px solid #f1f5f9;">
                                <i class="fa-solid fa-phone" style="color: var(--primary);"></i>
                                <span><strong>Unlimited</strong> Local Calls</span>
                            </div>
                            <div style="display: flex; align-items: center; gap: 1rem; padding-bottom: 1rem; border-bottom: 1px solid #f1f5f9;">
                                <i class="fa-solid fa-globe" style="color: var(--primary);"></i>
                                <span><strong>100 Mins</strong> International to India</span>
                            </div>
                        </div>
                        <div style="margin-top: 1.5rem; text-align: center;">
                            <span style="font-size: 1.5rem; font-weight: 800; color: var(--dark);">£ 15 / $ 20</span>
                            <small style="display: block; color: var(--gray);">per month (Estimated)</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="section bg-light">
        <div class="container">
            <div class="text-center animate-on-scroll" style="margin-bottom: 3rem;">
                <h2 class="section__title">Our <span>Network Partners</span></h2>
                <p class="section__subtitle">We provide SIM cards from the leading mobile networks in each destination.</p>
            </div>
            <div class="grid grid--4 gap--2">
                <div class="service-card text-center animate-on-scroll">
                    <i class="fa-solid fa-tower-broadcast" style="font-size: 2.5rem; color: #ef4444; margin-bottom: 1rem;"></i>
                    <h4>Vodafone / O2</h4>
                    <span class="section__tag">UK & Europe</span>
                </div>
                <div class="service-card text-center animate-on-scroll delay-1">
                    <i class="fa-solid fa-tower-broadcast" style="font-size: 2.5rem; color: #0ea5e9; margin-bottom: 1rem;"></i>
                    <h4>Lyca / Lebara</h4>
                    <span class="section__tag">Canada & Australia</span>
                </div>
                <div class="service-card text-center animate-on-scroll delay-2">
                    <i class="fa-solid fa-tower-broadcast" style="font-size: 2.5rem; color: #f97316; margin-bottom: 1rem;"></i>
                    <h4>T-Mobile / AT&T</h4>
                    <span class="section__tag">USA</span>
                </div>
                <div class="service-card text-center animate-on-scroll delay-3">
                    <i class="fa-solid fa-tower-broadcast" style="font-size: 2.5rem; color: #10b981; margin-bottom: 1rem;"></i>
                    <h4>Optus / Telstra</h4>
                    <span class="section__tag">Australia</span>
                </div>
            </div>
        </div>
    </section>

    <section class="section">
        <div class="container animate-on-scroll">
            <div style="background: var(--gradient); padding: 4rem 2rem; border-radius: var(--radius-lg); text-align: center; color: white; box-shadow: var(--shadow-lg);">
                <h2 style="font-size: 2.5rem; margin-bottom: 1rem;">Book Your Free SIM</h2>
                <p style="font-size: 1.1rem; opacity: 0.9; max-width: 600px; margin: 0 auto 2rem;">Don't leave connection to chance. Secure your international number before you fly.</p>
                <a href="consultation.php" class="btn btn--white btn--lg pulse-btn" style="background: white; color: var(--primary);">Claim My SIM Card</a>
            </div>
        </div>
    </section>
</main>

<?php require_once 'includes/footer.php'; ?>
