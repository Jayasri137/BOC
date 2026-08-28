<?php
require_once 'includes/config.php';
$pageTitle = 'International Student Bank Account Opening Assistance';
$pageDesc = 'Get support opening overseas student bank accounts before you travel abroad.';
require_once 'includes/header.php';
?>

<main>



    <section class="section" style="background-color: #ffffff;">
        <div class="container text-center">
            <div class="animate-on-scroll" style="max-width: 800px; margin: 0 auto 3rem auto;">
                <div class="v-icon" style="width:100px; height:100px; font-size:2.5rem; margin:0 auto; color:var(--accent);"><i class="fa-solid fa-building-columns"></i></div>
                <h2 class="section__title" style="margin-top:2rem">Seamless <span>Student Banking</span></h2>
                <p style="color:var(--gray); margin-top:1rem; line-height:1.7;">
                    Setting up a bank account is one of the first things you'll need to do as an international student. We facilitate <strong>Pre-Arrival Account Opening</strong> with major banks, so you can transfer your funds safely and have your debit card ready when you land.
                </p>
            </div>
            <div class="animate-on-scroll delay-1" style="max-width: 1200px; margin: 0 auto;">
                <div class="process-steps" style="justify-content: center; gap: 2rem; display: flex; flex-wrap: wrap;">
                    <div class="process-step animate-on-scroll" style="flex: 1 1 200px; max-width: 250px; text-align: center;">
                        <div class="process-step__image-box" style="width: 140px; height: 140px; margin: 0 auto 1.5rem;">
                            <img src="assets/images/3d_usa.png" alt="USA">
                            <div class="process-step__badge" style="bottom: -12px; width: 30px; height: 30px; font-size: 0.9rem; line-height: 30px; background: #ef4444;">01</div>
                        </div>
                        <h3 style="font-size: 1.3rem; font-weight: 800; color: var(--dark); margin-bottom: 0.5rem;">USA</h3>
                        <p style="color: var(--gray); font-size: 0.95rem; line-height: 1.5;">BOA, Chase, Wells Fargo</p>
                    </div>
                    
                    <div class="process-step animate-on-scroll delay-1" style="flex: 1 1 200px; max-width: 250px; text-align: center;">
                        <div class="process-step__image-box" style="width: 140px; height: 140px; margin: 0 auto 1.5rem;">
                            <img src="assets/images/3d_tower_bridge.png" alt="UK">
                            <div class="process-step__badge" style="bottom: -12px; width: 30px; height: 30px; font-size: 0.9rem; line-height: 30px; background: #0ea5e9;">02</div>
                        </div>
                        <h3 style="font-size: 1.3rem; font-weight: 800; color: var(--dark); margin-bottom: 0.5rem;">UK</h3>
                        <p style="color: var(--gray); font-size: 0.95rem; line-height: 1.5;">HSBC, Barclays, Lloyds</p>
                    </div>

                    <div class="process-step animate-on-scroll delay-2" style="flex: 1 1 200px; max-width: 250px; text-align: center;">
                        <div class="process-step__image-box" style="width: 140px; height: 140px; margin: 0 auto 1.5rem;">
                            <img src="assets/images/3d_canada.png" alt="Canada">
                            <div class="process-step__badge" style="bottom: -12px; width: 30px; height: 30px; font-size: 0.9rem; line-height: 30px; background: #dc2626;">03</div>
                        </div>
                        <h3 style="font-size: 1.3rem; font-weight: 800; color: var(--dark); margin-bottom: 0.5rem;">Canada</h3>
                        <p style="color: var(--gray); font-size: 0.95rem; line-height: 1.5;">CIBC, RBC, Scotiabank</p>
                    </div>

                    <div class="process-step animate-on-scroll delay-3" style="flex: 1 1 200px; max-width: 250px; text-align: center;">
                        <div class="process-step__image-box" style="width: 140px; height: 140px; margin: 0 auto 1.5rem;">
                            <img src="assets/images/aus.png" alt="Australia">
                            <div class="process-step__badge" style="bottom: -12px; width: 30px; height: 30px; font-size: 0.9rem; line-height: 30px; background: #10b981;">04</div>
                        </div>
                        <h3 style="font-size: 1.3rem; font-weight: 800; color: var(--dark); margin-bottom: 0.5rem;">Australia</h3>
                        <p style="color: var(--gray); font-size: 0.95rem; line-height: 1.5;">CommBank, ANZ, NAB</p>
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
            <style>
                .bank-card {
                    padding: 2.5rem 2rem;
                    border-radius: 24px;
                    transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
                    box-shadow: 0 10px 30px rgba(0,0,0,0.03);
                    text-align: left;
                    display: flex;
                    flex-direction: column;
                    height: 100%;
                }
                .bank-card:hover {
                    transform: translateY(-10px);
                    box-shadow: 0 20px 40px rgba(0,0,0,0.08);
                }
                .bc-1 { background: linear-gradient(135deg, #f0fdf4, #dcfce7); border: 1px solid #86efac; }
                .bc-1 .stat-icon-new { background: linear-gradient(135deg, #22c55e, #16a34a); color: white; border-radius: 16px; width: 60px; height: 60px; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; margin-bottom: 1.5rem; box-shadow: 0 10px 20px rgba(34, 197, 94, 0.3); }
                .bc-1 h3 { color: #166534; font-size: 1.5rem; margin-bottom: 1rem; font-weight: 800; }
                .bc-1 p { color: #15803d; line-height: 1.6; font-weight: 500; }

                .bc-2 { background: linear-gradient(135deg, #eff6ff, #dbeafe); border: 1px solid #93c5fd; }
                .bc-2 .stat-icon-new { background: linear-gradient(135deg, #3b82f6, #2563eb); color: white; border-radius: 16px; width: 60px; height: 60px; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; margin-bottom: 1.5rem; box-shadow: 0 10px 20px rgba(59, 130, 246, 0.3); }
                .bc-2 h3 { color: #1e40af; font-size: 1.5rem; margin-bottom: 1rem; font-weight: 800; }
                .bc-2 p { color: #1d4ed8; line-height: 1.6; font-weight: 500; }

                .bc-3 { background: linear-gradient(135deg, #fff7ed, #ffedd5); border: 1px solid #fdba74; }
                .bc-3 .stat-icon-new { background: linear-gradient(135deg, #f97316, #ea580c); color: white; border-radius: 16px; width: 60px; height: 60px; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; margin-bottom: 1.5rem; box-shadow: 0 10px 20px rgba(249, 115, 22, 0.3); }
                .bc-3 h3 { color: #9a3412; font-size: 1.5rem; margin-bottom: 1rem; font-weight: 800; }
                .bc-3 p { color: #c2410c; line-height: 1.6; font-weight: 500; }
            </style>
            
            <div class="grid grid--3 gap--4" style="margin-top: 3rem;">
                <div class="bank-card bc-1 animate-on-scroll">
                    <div class="stat-icon-new"><i class="fa-solid fa-shield-halved"></i></div>
                    <h3>Fund Security</h3>
                    <p>Transfer your tuition and living expenses to your own international account before you leave India.</p>
                </div>
                <div class="bank-card bc-2 animate-on-scroll delay-1">
                    <div class="stat-icon-new"><i class="fa-solid fa-address-card"></i></div>
                    <h3>Visa Proof</h3>
                    <p>For many countries, a pre-opened and funded account serves as strong proof of financial capacity.</p>
                </div>
                <div class="bank-card bc-3 animate-on-scroll delay-2">
                    <div class="stat-icon-new"><i class="fa-solid fa-bolt"></i></div>
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
