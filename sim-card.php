<?php
require_once 'includes/config.php';
$pageTitle = 'International SIM Card for Students Studying Abroad | Bluestone Overseas';
$pageDesc = 'Stay connected overseas with affordable international SIM cards designed for students studying abroad.';
require_once 'includes/header.php';
?>

<main>

    <section class="section">
        <div class="container text-center">
            <div class="animate-on-scroll" style="max-width: 800px; margin: 0 auto 3rem auto;">
                <div class="v-icon" style="width:100px; height:100px; font-size:2.5rem; margin:0 auto; color:#f43f5e;"><i class="fa-solid fa-mobile-screen-button"></i></div>
                <h2 class="section__title" style="margin-top:2rem">Pre-Activated <span>SIM Cards</span></h2>
                <p style="color:var(--gray); margin-top:1rem; line-height:1.7;">
                    Finding a mobile store and providing local ID after a long flight can be stressful. We ensure you have your <strong>Local Number</strong> ready before you leave India. Our SIM cards work the moment you land, allowing you to use maps, call your family, or book an Uber immediately.
                </p>
            </div>
            
            <div class="animate-on-scroll delay-1" style="max-width: 1000px; margin: 0 auto;">
                <div style="background: linear-gradient(135deg, #1e293b, #0f172a); border-radius: 30px; padding: 4rem 2rem; position: relative; overflow: hidden; display: flex; align-items: center; justify-content: space-around; flex-wrap: wrap; gap: 2rem; box-shadow: 0 25px 50px rgba(0,0,0,0.15);">
                    <!-- Decorative Background elements -->
                    <div style="position: absolute; top: -50px; left: -50px; width: 200px; height: 200px; background: #f43f5e; filter: blur(80px); opacity: 0.3; border-radius: 50%;"></div>
                    <div style="position: absolute; bottom: -50px; right: -50px; width: 200px; height: 200px; background: #3b82f6; filter: blur(80px); opacity: 0.3; border-radius: 50%;"></div>
                    
                    <div style="flex: 1 1 300px; text-align: left; position: relative; z-index: 1;">
                        <div style="display: inline-block; background: rgba(244, 63, 94, 0.2); color: #f43f5e; padding: 0.4rem 1.2rem; border-radius: 50px; font-weight: 700; font-size: 0.85rem; margin-bottom: 1rem; border: 1px solid rgba(244, 63, 94, 0.3);">#1 BEST SELLER</div>
                        <h3 style="font-size: 2.2rem; color: #ffffff; margin-bottom: 1.5rem; font-weight: 800; line-height: 1.2;">Student Global Plan</h3>
                        <ul style="list-style: none; padding: 0; margin: 0 0 2rem 0; display: flex; flex-direction: column; gap: 1rem;">
                            <li style="display: flex; align-items: center; gap: 1rem; color: #cbd5e1; font-size: 1.1rem;">
                                <i class="fa-solid fa-wifi" style="color: #38bdf8; font-size: 1.2rem;"></i> <strong>50GB</strong> High-Speed Data
                            </li>
                            <li style="display: flex; align-items: center; gap: 1rem; color: #cbd5e1; font-size: 1.1rem;">
                                <i class="fa-solid fa-phone" style="color: #38bdf8; font-size: 1.2rem;"></i> <strong>Unlimited</strong> Local Calls
                            </li>
                            <li style="display: flex; align-items: center; gap: 1rem; color: #cbd5e1; font-size: 1.1rem;">
                                <i class="fa-solid fa-globe" style="color: #38bdf8; font-size: 1.2rem;"></i> <strong>100 Mins</strong> International to India
                            </li>
                        </ul>
                        <div style="display: flex; align-items: center; gap: 1.5rem;">
                            <div>
                                <span style="font-size: 2rem; font-weight: 900; color: #ffffff;">£15 <span style="color: #94a3b8; font-size: 1rem; font-weight: 500;">/ mo</span></span>
                            </div>
                            <a href="consultation.php" class="btn btn--primary" style="background: #f43f5e; border-color: #f43f5e;">Claim Now</a>
                        </div>
                    </div>
                    
                    <div style="flex: 1 1 300px; text-align: center; position: relative; z-index: 1;">
                        <img src="assets/images/sim_card_elite.png" alt="Pre-Activated SIM Card" style="max-width: 100%; height: auto; max-height: 380px; object-fit: contain; filter: drop-shadow(0 20px 30px rgba(0,0,0,0.4)); animation: float 6s ease-in-out infinite;">
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
            
            <style>
                .network-card {
                    border-radius: 24px;
                    padding: 2.5rem;
                    position: relative;
                    overflow: hidden;
                    box-shadow: 0 10px 30px rgba(0,0,0,0.05);
                    transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
                    z-index: 1;
                    display: flex;
                    align-items: center;
                    gap: 2rem;
                    text-align: left;
                }
                .network-card:hover {
                    transform: translateY(-10px);
                    box-shadow: 0 20px 40px rgba(0,0,0,0.1);
                }
                .network-icon-wrapper {
                    width: 80px;
                    height: 80px;
                    border-radius: 20px;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    font-size: 2.5rem;
                    color: white;
                    flex-shrink: 0;
                    box-shadow: 0 10px 20px rgba(0,0,0,0.15);
                    transition: transform 0.4s ease;
                }
                .network-card:hover .network-icon-wrapper {
                    transform: scale(1.1) rotate(5deg);
                }
                
                .n-color-1 { background: linear-gradient(135deg, #fef2f2, #fee2e2); border: 1px solid #fca5a5; }
                .n-color-1 .network-icon-wrapper { background: linear-gradient(135deg, #ef4444, #b91c1c); }
                .n-color-1 h4 { color: #991b1b; }
                .n-color-1 span { background: #ef4444; color: white; }

                .n-color-2 { background: linear-gradient(135deg, #f0f9ff, #e0f2fe); border: 1px solid #7dd3fc; }
                .n-color-2 .network-icon-wrapper { background: linear-gradient(135deg, #0ea5e9, #0369a1); }
                .n-color-2 h4 { color: #075985; }
                .n-color-2 span { background: #0ea5e9; color: white; }

                .n-color-3 { background: linear-gradient(135deg, #fff7ed, #ffedd5); border: 1px solid #fdba74; }
                .n-color-3 .network-icon-wrapper { background: linear-gradient(135deg, #f97316, #c2410c); }
                .n-color-3 h4 { color: #9a3412; }
                .n-color-3 span { background: #f97316; color: white; }

                .n-color-4 { background: linear-gradient(135deg, #ecfdf5, #d1fae5); border: 1px solid #6ee7b7; }
                .n-color-4 .network-icon-wrapper { background: linear-gradient(135deg, #10b981, #047857); }
                .n-color-4 h4 { color: #065f46; }
                .n-color-4 span { background: #10b981; color: white; }
                
                @media (max-width: 768px) {
                    .network-card {
                        flex-direction: column;
                        text-align: center;
                        gap: 1.5rem;
                        padding: 2rem 1.5rem;
                    }
                }
            </style>
            
            <div class="grid grid--2 gap--4">
                <div class="network-card n-color-1 animate-on-scroll">
                    <div class="network-icon-wrapper"><i class="fa-solid fa-tower-broadcast"></i></div>
                    <div>
                        <h4 style="font-size: 1.75rem; margin-bottom: 0.75rem; font-weight: 800;">Vodafone / O2</h4>
                        <span style="padding: 0.35rem 1rem; border-radius: 50px; font-size: 0.9rem; font-weight: 700; display: inline-block;">UK & Europe</span>
                    </div>
                </div>
                <div class="network-card n-color-2 animate-on-scroll delay-1">
                    <div class="network-icon-wrapper"><i class="fa-solid fa-tower-broadcast"></i></div>
                    <div>
                        <h4 style="font-size: 1.75rem; margin-bottom: 0.75rem; font-weight: 800;">Lyca / Lebara</h4>
                        <span style="padding: 0.35rem 1rem; border-radius: 50px; font-size: 0.9rem; font-weight: 700; display: inline-block;">Canada & Aus</span>
                    </div>
                </div>
                <div class="network-card n-color-3 animate-on-scroll delay-2">
                    <div class="network-icon-wrapper"><i class="fa-solid fa-tower-broadcast"></i></div>
                    <div>
                        <h4 style="font-size: 1.75rem; margin-bottom: 0.75rem; font-weight: 800;">T-Mobile / AT&T</h4>
                        <span style="padding: 0.35rem 1rem; border-radius: 50px; font-size: 0.9rem; font-weight: 700; display: inline-block;">USA</span>
                    </div>
                </div>
                <div class="network-card n-color-4 animate-on-scroll delay-3">
                    <div class="network-icon-wrapper"><i class="fa-solid fa-tower-broadcast"></i></div>
                    <div>
                        <h4 style="font-size: 1.75rem; margin-bottom: 0.75rem; font-weight: 800;">Optus / Telstra</h4>
                        <span style="padding: 0.35rem 1rem; border-radius: 50px; font-size: 0.9rem; font-weight: 700; display: inline-block;">Australia</span>
                    </div>
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
