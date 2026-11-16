<?php
require_once 'includes/config.php';
$pageTitle = 'Student Health Insurance | OSHC | Bluestone Overseas';
$pageDesc = 'Get comprehensive Overseas Student Health Cover (OSHC) and international student health insurance for Australia, UK, Canada, USA, and more.';
$pageKeywords = 'OSHC, student health insurance, overseas student health cover, international student insurance, study abroad insurance';
require_once 'includes/header.php';
?>

<!-- Hero Section -->
<style>
.hero-health {
    background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%);
    padding: 8rem 0 4rem;
    position: relative;
    overflow: hidden;
    color: white;
}
.hero-health::after {
    content: '';
    position: absolute;
    bottom: -50px;
    right: -50px;
    width: 300px;
    height: 300px;
    background: rgba(255,255,255,0.1);
    border-radius: 50%;
}
.hero-health .container {
    position: relative;
    z-index: 2;
    display: flex;
    align-items: center;
    gap: 2rem;
}
.hero-health-text {
    flex: 1;
}
.hero-health-text h1 {
    font-size: clamp(2.5rem, 4vw, 3.5rem);
    font-weight: 900;
    margin-bottom: 1rem;
    line-height: 1.2;
}
.hero-health-text h1 span {
    color: #fde047;
}
.hero-health-text p {
    font-size: 1.15rem;
    opacity: 0.95;
    margin-bottom: 2.5rem;
    max-width: 600px;
    line-height: 1.6;
}
.hero-health-image {
    flex: 1;
    text-align: center;
}
.hero-health-image img {
    max-width: 100%;
    border-radius: 20px;
    box-shadow: 0 20px 40px rgba(0,0,0,0.2);
}
.health-benefits {
    padding: 6rem 0;
    background: #f8fafc;
}
.benefit-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 2rem;
    margin-top: 3.5rem;
}
.benefit-card {
    background: white;
    padding: 2.5rem;
    border-radius: 20px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.05);
    transition: transform 0.4s, box-shadow 0.4s;
    border-top: 4px solid #3b82f6;
    height: 100%;
    display: flex;
    flex-direction: column;
}
.benefit-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 15px 40px rgba(0,0,0,0.1);
    border-color: #facc15;
}
.benefit-icon {
    width: 70px;
    height: 70px;
    background: #eff6ff;
    color: #3b82f6;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.8rem;
    margin-bottom: 1.5rem;
    transition: all 0.3s;
}
.benefit-card:hover .benefit-icon {
    background: #3b82f6;
    color: white;
}
.benefit-card h3 {
    font-size: 1.4rem;
    font-weight: 800;
    margin-bottom: 1rem;
    color: #1e293b;
}
.benefit-card p {
    color: #64748b;
    line-height: 1.6;
    margin: 0;
    flex-grow: 1;
}
.why-oshc {
    padding: 6rem 0;
    background: white;
}
.why-oshc-content {
    display: flex;
    gap: 5rem;
    align-items: center;
}
.why-oshc-text {
    flex: 1;
}
.why-oshc-text h2 {
    font-size: 2.8rem;
    font-weight: 800;
    color: #0f172a;
    margin-bottom: 2rem;
    line-height: 1.2;
}
.why-oshc-text h2 span {
    color: #10b981;
}
.why-oshc-list {
    list-style: none;
    padding: 0;
    margin: 0;
}
.why-oshc-list li {
    display: flex;
    align-items: flex-start;
    gap: 1.2rem;
    margin-bottom: 2rem;
    background: #f8fafc;
    padding: 1.5rem;
    border-radius: 15px;
    transition: all 0.3s;
}
.why-oshc-list li:hover {
    transform: translateX(10px);
    box-shadow: 0 5px 15px rgba(0,0,0,0.05);
}
.why-oshc-list i {
    color: #10b981;
    font-size: 1.5rem;
    margin-top: 3px;
}
.why-oshc-list h4 {
    margin: 0 0 0.5rem;
    color: #1e293b;
    font-size: 1.2rem;
    font-weight: 700;
}
.why-oshc-list p {
    margin: 0;
    color: #475569;
    line-height: 1.6;
}
.why-oshc-image {
    flex: 1;
    position: relative;
}
.why-oshc-image img {
    width: 100%;
    border-radius: 30px;
    box-shadow: 0 25px 50px rgba(0,0,0,0.15);
}
.cta-section {
    padding: 5rem 0;
    background: linear-gradient(135deg, #facc15 0%, #eab308 100%);
    text-align: center;
    position: relative;
    overflow: hidden;
}
.cta-section::before, .cta-section::after {
    content: '';
    position: absolute;
    width: 200px;
    height: 200px;
    background: rgba(255,255,255,0.1);
    border-radius: 50%;
}
.cta-section::before {
    top: -100px;
    left: -100px;
}
.cta-section::after {
    bottom: -100px;
    right: -100px;
}
.cta-section h2 {
    color: #0f172a;
    font-size: 2.8rem;
    font-weight: 900;
    margin-bottom: 1.5rem;
    position: relative;
    z-index: 2;
}
.cta-section p {
    color: #1e293b;
    font-size: 1.3rem;
    margin-bottom: 2.5rem;
    max-width: 700px;
    margin-left: auto;
    margin-right: auto;
    position: relative;
    z-index: 2;
}
.cta-btn {
    background: #1e3a8a;
    color: white;
    padding: 1.2rem 3rem;
    border-radius: 50px;
    font-weight: 700;
    font-size: 1.15rem;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 0.8rem;
    transition: all 0.3s;
    position: relative;
    z-index: 2;
    box-shadow: 0 10px 20px rgba(30,58,138,0.2);
}
.cta-btn:hover {
    background: #0f172a;
    transform: translateY(-3px);
    box-shadow: 0 15px 30px rgba(30,58,138,0.3);
    color: white;
}
@media (max-width: 992px) {
    .hero-health .container, .why-oshc-content {
        flex-direction: column;
    }
    .why-oshc-content {
        gap: 3rem;
    }
    .hero-health {
        padding: 6rem 0 3rem;
    }
}
</style>

<main>
    <section class="hero-health">
        <div class="container">
            <div class="hero-health-text animate-on-scroll">
                <span style="background: rgba(255,255,255,0.2); padding: 5px 15px; border-radius: 20px; font-size: 0.9rem; font-weight: 600; margin-bottom: 1.5rem; display: inline-block;">International Students</span>
                <h1>Student Health Insurance <span>(OSHC)</span></h1>
                <p>Ensure your health and safety while studying abroad. We help you compare and choose the best health cover that meets your visa requirements and provides peace of mind.</p>
                <a href="consultation.php" class="cta-btn" style="background: white; color: #1e3a8a;"><i class="fa-solid fa-shield-heart"></i> Get Free Quote</a>
            </div>
            <div class="hero-health-image animate-on-scroll delay-1">
                <!-- Using an existing image placeholder, replace with actual if available -->
                <img src="assets/images/img4.png" alt="Student Health Insurance" style="background: white;">
            </div>
        </div>
    </section>

    <!-- Adding Insurance by Destination back but styled better -->
    <section class="section" style="background: #ffffff; padding: 5rem 0;">
        <div class="container">
            <div class="text-center animate-on-scroll" style="margin-bottom: 3rem;">
                <h2 class="section__title" style="font-size: 2.5rem; font-weight: 800; color: #1e3a8a;">Insurance by <span style="color: #f59e0b;">Destination</span></h2>
                <p class="section__subtitle" style="font-size: 1.1rem; color: #64748b;">Different countries have specific health cover mandates for international students.</p>
            </div>
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 2.5rem; margin-top: 2rem;">
                <div class="animate-on-scroll" style="text-align: center; padding: 2.5rem 1.5rem; background: #fff; border-radius: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.05); border: 1px solid #e2e8f0; transition: transform 0.3s;">
                    <div style="width: 120px; height: 120px; margin: 0 auto 1.5rem;">
                        <img src="assets/images/aus.png" alt="Australia" style="width: 100%; height: 100%; object-fit: contain;">
                    </div>
                    <h3 style="font-size: 1.5rem; font-weight: 800; color: #1e293b; margin-bottom: 0.5rem;">AUSTRALIA</h3>
                    <h4 style="font-size: 1.1rem; color: #3b82f6; margin-bottom: 1rem; font-weight: 700;">OSHC</h4>
                    <p style="color: #64748b; font-size: 0.95rem; line-height: 1.6;">Mandatory for Subclass 500 visa. Covers doctor visits, hospital, ambulance, and limited medicines.</p>
                </div>
                
                <div class="animate-on-scroll delay-1" style="text-align: center; padding: 2.5rem 1.5rem; background: #fff; border-radius: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.05); border: 1px solid #e2e8f0; transition: transform 0.3s;">
                    <div style="width: 120px; height: 120px; margin: 0 auto 1.5rem;">
                        <img src="assets/images/rus.png" alt="UK" style="width: 100%; height: 100%; object-fit: contain;">
                    </div>
                    <h3 style="font-size: 1.5rem; font-weight: 800; color: #1e293b; margin-bottom: 0.5rem;">UK</h3>
                    <h4 style="font-size: 1.1rem; color: #ef4444; margin-bottom: 1rem; font-weight: 700;">IHS</h4>
                    <p style="color: #64748b; font-size: 0.95rem; line-height: 1.6;">Paid during visa application, giving you access to the National Health Service (NHS) just like a UK resident.</p>
                </div>
                
                <div class="animate-on-scroll delay-2" style="text-align: center; padding: 2.5rem 1.5rem; background: #fff; border-radius: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.05); border: 1px solid #e2e8f0; transition: transform 0.3s;">
                    <div style="width: 120px; height: 120px; margin: 0 auto 1.5rem;">
                        <img src="assets/images/3d_canada.png" alt="USA & Canada" style="width: 100%; height: 100%; object-fit: contain;">
                    </div>
                    <h3 style="font-size: 1.5rem; font-weight: 800; color: #1e293b; margin-bottom: 0.5rem;">USA & CANADA</h3>
                    <h4 style="font-size: 1.1rem; color: #f97316; margin-bottom: 1rem; font-weight: 700;">Private Health Plan</h4>
                    <p style="color: #64748b; font-size: 0.95rem; line-height: 1.6;">Customizable private insurance plans required by universities to cover high medical costs in North America.</p>
                </div>
            </div>
        </div>
    </section>

    <section class="health-benefits">
        <div class="container">
            <div class="section__header text-center animate-on-scroll">
                <span class="section__tag" style="background: #e0f2fe; color: #0284c7; padding: 5px 15px; border-radius: 20px; font-weight: 600;">Coverage Details</span>
                <h2 class="section__title" style="font-size: 2.5rem; margin-top: 1rem; color: #1e293b;">What Does Student Health Cover Include?</h2>
                <div class="accent-bar" style="margin: 1rem auto; width: 60px; height: 4px; background: #3b82f6; border-radius: 2px;"></div>
                <p style="max-width: 700px; margin: 1rem auto; color: #64748b; font-size: 1.1rem;">Most standard Overseas Student Health Covers provide comprehensive protection for your everyday and emergency medical needs.</p>
            </div>
            
            <div class="benefit-grid">
                <div class="benefit-card animate-on-scroll delay-1">
                    <div class="benefit-icon"><i class="fa-solid fa-user-doctor"></i></div>
                    <h3>Medical & Hospital Care</h3>
                    <p>Covers costs for visits to the doctor (GP), specialists, and hospital accommodations during your stay abroad.</p>
                </div>
                <div class="benefit-card animate-on-scroll delay-2">
                    <div class="benefit-icon"><i class="fa-solid fa-truck-medical"></i></div>
                    <h3>Ambulance Services</h3>
                    <p>Includes emergency ambulance transport to a hospital or clinic for immediate medical attention when you need it most.</p>
                </div>
                <div class="benefit-card animate-on-scroll delay-3">
                    <div class="benefit-icon"><i class="fa-solid fa-pills"></i></div>
                    <h3>Prescription Medicines</h3>
                    <p>Provides subsidies for many prescription medicines, helping you manage out-of-pocket costs for essential treatments.</p>
                </div>
                <div class="benefit-card animate-on-scroll delay-4">
                    <div class="benefit-icon"><i class="fa-solid fa-passport"></i></div>
                    <h3>Visa Compliance</h3>
                    <p>Ensures you meet the strict health insurance requirements necessary for the approval and maintenance of your student visa.</p>
                </div>
            </div>
        </div>
    </section>

    <section class="why-oshc">
        <div class="container">
            <div class="why-oshc-content">
                <div class="why-oshc-image animate-on-scroll">
                    <div style="background: linear-gradient(135deg, #10b981, #059669); border-radius: 30px; width: 100%; height: 500px; display: flex; align-items: center; justify-content: center; flex-direction: column; color: white; box-shadow: 0 25px 50px rgba(16, 185, 129, 0.3);">
                        <i class="fa-solid fa-heart-pulse" style="font-size: 8rem; color: white; opacity: 0.9; margin-bottom: 1rem;"></i>
                        <div style="font-size: 1.8rem; font-weight: 800; color: white;">Your Health.</div>
                        <div style="font-size: 1.8rem; font-weight: 800; color: #fde047;">Fully Covered.</div>
                    </div>
                </div>
                <div class="why-oshc-text animate-on-scroll delay-1">
                    <h2>Why You Need <br><span style="color: #10b981;">Health Insurance</span></h2>
                    <p style="font-size: 1.1rem; color: #64748b; margin-bottom: 2rem;">Studying abroad is an exciting journey. Having the right insurance ensures you don't face unexpected financial burdens during medical emergencies.</p>
                    
                    <ul class="why-oshc-list">
                        <li>
                            <i class="fa-solid fa-circle-check"></i>
                            <div>
                                <h4>Mandatory for Visa Processing</h4>
                                <p>Major destinations like Australia (OSHC), the UK (IHS), and Canada require international students to hold valid health coverage for the entirety of their visa.</p>
                            </div>
                        </li>
                        <li>
                            <i class="fa-solid fa-shield-halved"></i>
                            <div>
                                <h4>Complete Financial Protection</h4>
                                <p>Healthcare costs can be exorbitant in foreign countries. Insurance protects you from massive out-of-pocket medical bills.</p>
                            </div>
                        </li>
                        <li>
                            <i class="fa-solid fa-face-smile-beam"></i>
                            <div>
                                <h4>Peace of Mind for You & Your Family</h4>
                                <p>Focus entirely on your academics and new experiences knowing that your well-being is safeguarded against the unexpected.</p>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <!-- Network Partners Section -->
    <section class="section" style="padding: 5rem 0; background: linear-gradient(135deg, #ffffff 0%, #f5f3ff 100%);">
        <div class="container">
            <div class="text-center animate-on-scroll" style="margin-bottom: 3rem;">
                <h2 class="section__title" style="font-size: 2.5rem; font-weight: 800; color: #1e3a8a;">Our Network <span style="color: #f59e0b;">Partners</span></h2>
                <p class="section__subtitle" style="font-size: 1.1rem; color: #64748b;">We have partnered with top-tier insurance providers to give you complete peace of mind.</p>
            </div>
            
            <div class="animate-on-scroll" style="display: flex; justify-content: center; align-items: center;">
                <div style="background: #3b82f6; border-radius: 32px; border: 1px solid rgba(255, 255, 255, 0.15); box-shadow: 0 25px 50px -12px rgba(59, 130, 246, 0.4); width: 100%; max-width: 1100px; display: flex; flex-wrap: wrap; align-items: center; overflow: hidden; position: relative;">
                    <div style="position: absolute; top: -50px; right: -50px; width: 300px; height: 300px; background: rgba(255,255,255,0.05); border-radius: 50%; pointer-events: none;"></div>
                    <div style="position: absolute; bottom: -100px; left: 20%; width: 400px; height: 400px; background: rgba(255,255,255,0.05); border-radius: 50%; pointer-events: none;"></div>
                    
                    <div style="flex: 1 1 400px; padding: 4rem; position: relative; z-index: 1;">
                        <div style="display: inline-flex; align-items: center; gap: 1rem; color: #d97706; margin-bottom: 2rem; background: #fff; padding: 1rem 2rem; border-radius: 16px; box-shadow: 0 10px 20px rgba(0,0,0,0.1);">
                            <i class="fa-solid fa-umbrella" style="font-size: 2rem;"></i>
                            <div style="text-align: left;">
                                <h4 style="font-size: 1.5rem; font-weight: 900; margin: 0; color: #1e3a8a; line-height: 1;">ICICI LOMBARD</h4>
                                <span style="font-size: 0.85rem; font-weight: 700; letter-spacing: 2px; text-transform: uppercase;">Trusted Partner</span>
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
                        <img src="assets/images/insurance_shield.png" alt="Premium Insurance" style="width: 100%; max-width: 350px; height: auto; object-fit: contain; filter: drop-shadow(0 25px 35px rgba(0,0,0,0.15)); transform: scale(1.05); transition: transform 0.5s ease;" onmouseover="this.style.transform='scale(1.1) translateY(-10px)'" onmouseout="this.style.transform='scale(1.05)'">
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="cta-section">
        <div class="container animate-on-scroll">
            <h2>Let Us Find the Right Cover For You</h2>
            <p>Don't navigate complex insurance policies alone. Our experts will help you compare top providers and secure the best policy tailored to your destination.</p>
            <a href="consultation.php" class="cta-btn">
                Talk to an Expert <i class="fa-solid fa-arrow-right"></i>
            </a>
        </div>
    </section>
</main>

<?php require_once 'includes/footer.php'; ?>
