<?php
require_once 'includes/config.php';
$pageTitle = 'Student Health Insurance (OSHC) | Bluestone Overseas';
$pageDesc = 'Get the best overseas student health cover (OSHC) and international insurance for your study abroad journey. Affordable and mandatory cover for Australia, UK, and more.';
require_once 'includes/header.php';
?>

<main>

    <section class="section" style="background: #ffffff">
        <div class="container">
            <div class="text-center animate-on-scroll" style="margin-bottom: 3rem;">
                <h2 class="section__title">Insurance by <span>Destination</span></h2>
                <p class="section__subtitle">Different countries have specific health cover mandates for international students.</p>
            </div>
            <div class="process-steps" style="justify-content: center; gap: 3rem; margin-top: 2rem; display: flex; flex-wrap: wrap;">
                <div class="process-step animate-on-scroll" style="flex: 1 1 200px; max-width: 280px; text-align: center;">
                    <div class="process-step__image-box" style="width: 160px; height: 160px; margin: 0 auto 1.5rem;">
                        <img src="assets/images/aus.png" alt="Australia">
                        <div class="process-step__badge" style="bottom: -15px; width: 32px; height: 32px; font-size: 1rem; line-height: 32px; background: var(--primary);">01</div>
                    </div>
                    <h3 style="font-size: 1.5rem; font-weight: 800; color: var(--dark); margin-bottom: 0.5rem;">AUSTRALIA</h3>
                    <h4 style="font-size: 1.1rem; color: var(--primary); margin-bottom: 1rem;">OSHC</h4>
                    <p style="color: var(--gray); font-size: 0.95rem; line-height: 1.6;">Mandatory for Subclass 500 visa. Covers doctor visits, hospital, ambulance, and limited medicines.</p>
                </div>
                
                <div class="process-step animate-on-scroll delay-1" style="flex: 1 1 200px; max-width: 280px; text-align: center;">
                    <div class="process-step__image-box" style="width: 160px; height: 160px; margin: 0 auto 1.5rem;">
                        <img src="assets/images/rus.png" alt="UK">
                        <div class="process-step__badge" style="bottom: -15px; width: 32px; height: 32px; font-size: 1rem; line-height: 32px; background: var(--accent);">02</div>
                    </div>
                    <h3 style="font-size: 1.5rem; font-weight: 800; color: var(--dark); margin-bottom: 0.5rem;">UK</h3>
                    <h4 style="font-size: 1.1rem; color: var(--accent); margin-bottom: 1rem;">IHS</h4>
                    <p style="color: var(--gray); font-size: 0.95rem; line-height: 1.6;">Paid during visa application, giving you access to the National Health Service (NHS) just like a UK resident.</p>
                </div>
                
                <div class="process-step animate-on-scroll delay-2" style="flex: 1 1 200px; max-width: 280px; text-align: center;">
                    <div class="process-step__image-box" style="width: 160px; height: 160px; margin: 0 auto 1.5rem;">
                        <img src="assets/images/3d_canada.png" alt="USA & Canada">
                        <div class="process-step__badge" style="bottom: -15px; width: 32px; height: 32px; font-size: 1rem; line-height: 32px; background: #f97316;">03</div>
                    </div>
                    <h3 style="font-size: 1.5rem; font-weight: 800; color: var(--dark); margin-bottom: 0.5rem;">USA & CANADA</h3>
                    <h4 style="font-size: 1.1rem; color: #f97316; margin-bottom: 1rem;">International Student Health Plan</h4>
                    <p style="color: var(--gray); font-size: 0.95rem; line-height: 1.6;">Customizable private insurance plans required by universities to cover high medical costs in North America.</p>
                </div>
            </div>
        </div>
    </section>

    <section class="section bg-light">
        <div class="container">
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 4rem; align-items: center;">
                <div class="animate-on-scroll">
                    <span style="display: inline-block; background: #ffffff; color: var(--primary); padding: 0.35rem 1.25rem; border-radius: 50px; font-size: 0.85rem; font-weight: 700; margin-bottom: 1.5rem;">Health Cover</span>
                    <h2 style="font-size: 2.5rem; margin-bottom: 1.5rem; line-height: 1.2;">Protect Your <span style="color: var(--primary);">Well-being</span> Abroad</h2>
                    <p style="color:var(--gray); margin-bottom:2.5rem; line-height:1.7; font-size: 1.05rem;">
                        Most countries require international students to have valid health insurance for the entire duration of their studies. We ensure you have the right policy that meets your visa requirements.
                    </p>
                    <ul style="list-style: none; padding: 0; margin: 0; display: flex; flex-direction: column; gap: 1rem;">
                        <li style="display: flex; align-items: center; gap: 1rem; font-size: 1.05rem; color: var(--dark); font-weight: 500;">
                            <i class="fa-solid fa-check-circle" style="color: var(--primary); font-size: 1.25rem;"></i> OSHC for Australia
                        </li>
                        <li style="display: flex; align-items: center; gap: 1rem; font-size: 1.05rem; color: var(--dark); font-weight: 500;">
                            <i class="fa-solid fa-check-circle" style="color: var(--primary); font-size: 1.25rem;"></i> IHS for the UK
                        </li>
                        <li style="display: flex; align-items: center; gap: 1rem; font-size: 1.05rem; color: var(--dark); font-weight: 500;">
                            <i class="fa-solid fa-check-circle" style="color: var(--primary); font-size: 1.25rem;"></i> Private Medical Cover for USA & Canada
                        </li>
                    </ul>
                </div>
                
                <div class="animate-on-scroll delay-1">
                    <div style="background: linear-gradient(135deg, #10b981, #059669); padding: 3rem; border-radius: 24px; box-shadow: 0 20px 40px rgba(0,0,0,0.06); border: 1px solid #f1f5f9; position: relative;">
                        <div style="position: absolute; top: -20px; right: 30px; width: 60px; height: 60px; background: #f59e0b; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-size: 1.5rem; box-shadow: 0 10px 20px rgba(245,158,11,0.3);">
                            <i class="fa-solid fa-shield-halved"></i>
                        </div>
                        <h3 style="margin-bottom: 1.5rem; font-size: 1.75rem; color: white;">Reliable Visa Support</h3>
                        <p style="font-size: 1.05rem; color: white; line-height: 1.8; opacity: 0.95;">
                            We guide you through mandatory cover, premium comparison, and claim support so your move abroad is completely stress-free.
                        </p>
                        <div style="margin-top: 2.5rem; padding: 1.5rem; background: rgba(255,255,255,0.1); backdrop-filter: blur(5px); -webkit-backdrop-filter: blur(5px); border-radius: 12px; display: flex; gap: 1.25rem; border-left: 4px solid #f59e0b;">
                            <i class="fa-solid fa-file-invoice-dollar" style="color: #f59e0b; font-size: 1.5rem; margin-top: 0.25rem;"></i>
                            <p style="font-size: 1rem; color: white; font-weight: 600; margin: 0; line-height: 1.6;">Don't risk your visa approval. Ensure your health cover meets all requirements.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="section" style="padding-top: 4rem; padding-bottom: 4rem; background: linear-gradient(135deg, #ffffff 0%, #f5f3ff 100%);">
        <div class="container">
            <div class="text-center animate-on-scroll" style="margin-bottom: 3rem;">
                <h2 class="section__title">Our Network <span>Partners</span></h2>
                <p class="section__subtitle">We have partnered with India's most trusted insurance providers to give you complete peace of mind.</p>
            </div>
            
            <div class="animate-on-scroll" style="display: flex; justify-content: center; align-items: center;">
                <div style="background: #579df9; border-radius: 32px; border: 1px solid rgba(255, 255, 255, 0.15); box-shadow: 0 25px 50px -12px rgba(24, 119, 242, 0.25); width: 100%; max-width: 1100px; display: flex; flex-wrap: wrap; align-items: center; overflow: hidden; position: relative;">
                    <!-- Decorative faint background shapes matching index.php -->
                    <div style="position: absolute; top: -50px; right: -50px; width: 300px; height: 300px; background: rgba(255,255,255,0.05); border-radius: 50%; pointer-events: none;"></div>
                    <div style="position: absolute; bottom: -100px; left: 20%; width: 400px; height: 400px; background: rgba(255,255,255,0.05); border-radius: 50%; pointer-events: none;"></div>
                    
                    <div style="flex: 1 1 400px; padding: 4rem; position: relative; z-index: 1;">
                        <div style="display: inline-flex; align-items: center; gap: 1rem; color: #d97706; margin-bottom: 2rem; background: #fff; padding: 1rem 2rem; border-radius: 16px; box-shadow: 0 10px 20px rgba(0,0,0,0.05); border: 1px solid rgba(245, 158, 11, 0.1);">
                            <i class="fa-solid fa-umbrella" style="font-size: 2rem;"></i>
                            <div style="text-align: left;">
                                <h4 style="font-size: 1.5rem; font-weight: 900; margin: 0; color: #b45309; line-height: 1;">ICICI LOMBARD</h4>
                                <span style="font-size: 0.85rem; font-weight: 700; letter-spacing: 2px; text-transform: uppercase;">Partner</span>
                            </div>
                        </div>
                        
                        <h3 style="font-size: 2.5rem; font-weight: 800; color: #ffffff; margin-bottom: 1.25rem; line-height: 1.15;">India's Leading<br><span style="color: #FDE047;">Health Cover</span></h3>
                        <p style="font-size: 1.1rem; color: rgba(255, 255, 255, 0.9); line-height: 1.7; margin-bottom: 2.5rem;">Get cashless claims, comprehensive medical coverage, and 24x7 international support for your entire study abroad journey.</p>
                        
                        <ul style="list-style: none; padding: 0; margin: 0; display: flex; flex-direction: column; gap: 1.25rem;">
                            <li style="display: flex; align-items: center; gap: 1rem; color: #ffffff; font-weight: 600; font-size: 1.05rem;">
                                <i class="fa-solid fa-check-circle" style="color: #FDE047; font-size: 1.25rem;"></i> Global Cashless Hospitals
                            </li>
                            <li style="display: flex; align-items: center; gap: 1rem; color: #ffffff; font-weight: 600; font-size: 1.05rem;">
                                <i class="fa-solid fa-check-circle" style="color: #FDE047; font-size: 1.25rem;"></i> Instant Policy Issuance
                            </li>
                            <li style="display: flex; align-items: center; gap: 1rem; color: #ffffff; font-weight: 600; font-size: 1.05rem;">
                                <i class="fa-solid fa-check-circle" style="color: #FDE047; font-size: 1.25rem;"></i> University Approved Cover
                            </li>
                        </ul>
                    </div>
                    
                    <div style="flex: 1 1 300px; padding: 3rem; display: flex; justify-content: center; align-items: center; background: radial-gradient(circle at center, rgba(255, 255, 255, 0.1) 0%, transparent 70%); min-height: 100%;">
                        <img src="assets/images/insurance_shield.png" alt="Premium Insurance" style="width: 100%; max-width: 400px; height: auto; object-fit: contain; filter: drop-shadow(0 25px 35px rgba(0,0,0,0.15)); transform: scale(1.05); transition: transform 0.5s ease;" onmouseover="this.style.transform='scale(1.1) translateY(-10px)'" onmouseout="this.style.transform='scale(1.05)'">
                    </div>
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
