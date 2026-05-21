<?php
require_once 'includes/config.php';
$pageTitle = 'Step-by-Step Guide to Studying Abroad | Bluestone Overseas';
$pageDesc = 'Navigate your study abroad journey with our comprehensive 8-step guide. From research to departure, Bluestone is with you.';
require_once 'includes/header.php';
?>

<main>
    <!-- HERO SECTION -->
    <!-- 8 STEPS JOURNEY -->
    <section class="section" style="background: #fff;">
        <div class="container">
            <div class="text-center animate-on-scroll" style="margin-bottom: 4rem;">
                <span class="section__tag">Process</span>
                <h2 class="section__title">8 Easy Steps to <span>Study Abroad</span></h2>
                <p class="section__subtitle">A comprehensive roadmap designed to take you from initial research to your first day on campus.</p>
            </div>

            <div class="guide-timeline">
                <?php
                $steps = [
                    [
                        'num' => '01',
                        'title' => 'Research Your Options',
                        'icon' => 'fa-magnifying-glass',
                        'desc' => 'Start by exploring countries, universities, and courses that align with your career goals and budget. Use our online tools to filter your preferences.',
                        'color' => 'blue'
                    ],
                    [
                        'num' => '02',
                        'title' => 'Speak with a Counsellor',
                        'icon' => 'fa-headset',
                        'desc' => 'Book a FREE session with our experts. We provide personalized advice, profile assessment, and help you narrow down your best-fit options.',
                        'color' => 'purple'
                    ],
                    [
                        'num' => '03',
                        'title' => 'Make Your Application',
                        'icon' => 'fa-file-signature',
                        'desc' => 'We help you prepare your SOP, LORs, and academic documents. Our team manages the entire submission process to ensure error-free applications.',
                        'color' => 'orange'
                    ],
                    [
                        'num' => '04',
                        'title' => 'Receive Your Offer',
                        'icon' => 'fa-envelope-open-text',
                        'desc' => 'Celebrate your acceptance! We guide you through the conditions of your offer letter and help you secure your seat at the university.',
                        'color' => 'teal'
                    ],
                    [
                        'num' => '05',
                        'title' => 'Secure Your Funding',
                        'icon' => 'fa-hand-holding-dollar',
                        'desc' => 'Explore scholarship opportunities and apply for student loans. We provide guidance on financial documentation and forex services.',
                        'color' => 'pink'
                    ],
                    [
                        'num' => '06',
                        'title' => 'Apply for Your Visa',
                        'icon' => 'fa-passport',
                        'desc' => 'Our visa experts assist with documentation, financial requirements, and interview preparation to ensure a high success rate.',
                        'color' => 'gold'
                    ],
                    [
                        'num' => '07',
                        'title' => 'Find Accommodation',
                        'icon' => 'fa-house-user',
                        'desc' => 'Arrange your new home abroad. We help you find student housing, homestays, or private rentals near your campus.',
                        'color' => 'blue'
                    ],
                    [
                        'num' => '08',
                        'title' => 'Ready, Set, Go!',
                        'icon' => 'fa-plane-departure',
                        'desc' => 'Attend our pre-departure briefing. Get tips on culture, packing, and airport pickup. Your global journey officially begins!',
                        'color' => 'purple'
                    ]
                ];

                foreach ($steps as $i => $step):
                    $isEven = ($i % 2 !== 0);
                ?>
                <div class="guide-step-row <?= $isEven ? 'guide-step-row--reverse' : '' ?> animate-on-scroll">
                    <div class="guide-step-content">
                        <div class="guide-step-badge"><?= $step['num'] ?></div>
                        <h3><?= $step['title'] ?></h3>
                        <p><?= $step['desc'] ?></p>
                        <div class="guide-step-icon guide-step-icon--<?= $step['color'] ?>"><i class="fa-solid <?= $step['icon'] ?>"></i></div>
                    </div>
                    <div class="guide-step-visual">
                        <div class="guide-step-line"></div>
                        <div class="guide-step-dot"></div>
                    </div>
                    <div class="guide-step-empty"></div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- STUDENT ESSENTIALS -->
    <section class="section bg-light">
        <div class="container">
            <div class="text-center animate-on-scroll" style="margin-bottom: 4rem;">
                <span class="section__tag">Support</span>
                <h2 class="section__title">Student <span>Essentials</span></h2>
                <p class="section__subtitle">Critical services to ensure you are safe, secure, and prepared for your life overseas.</p>
            </div>

            <div class="grid grid--3 gap--2">
                <div class="service-card animate-on-scroll">
                    <div class="stat-icon stat-icon--blue"><i class="fa-solid fa-shield-heart"></i></div>
                    <h3>Health Insurance</h3>
                    <p>Mandatory health cover (like OSHC for Australia) to protect you against medical expenses while studying.</p>
                    <a href="health-insurance.php" class="btn btn--outline btn--sm" style="margin-top: 1rem; width: 100%; justify-content: center;">Learn More</a>
                </div>
                <div class="service-card animate-on-scroll delay-1">
                    <div class="stat-icon stat-icon--purple"><i class="fa-solid fa-money-bill-transfer"></i></div>
                    <h3>Money Transfer</h3>
                    <p>Secure and fast international money transfers for tuition fees and living expenses at competitive rates.</p>
                    <a href="money-transfer.php" class="btn btn--outline btn--sm" style="margin-top: 1rem; width: 100%; justify-content: center;">Learn More</a>
                </div>
                <div class="service-card animate-on-scroll delay-2">
                    <div class="stat-icon stat-icon--orange"><i class="fa-solid fa-mobile-screen-button"></i></div>
                    <h3>International SIM</h3>
                    <p>Stay connected from the moment you land. Get your international SIM card before you depart India.</p>
                    <a href="sim-card.php" class="btn btn--outline btn--sm" style="margin-top: 1rem; width: 100%; justify-content: center;">Learn More</a>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA SECTION -->
    <section class="section">
        <div class="container animate-on-scroll">
            <div style="background: var(--gradient); padding: 4rem 2rem; border-radius: var(--radius-lg); text-align: center; color: white; box-shadow: var(--shadow-lg);">
                <h2 style="font-size: 2.5rem; margin-bottom: 1rem;">Need Personalized Guidance?</h2>
                <p style="font-size: 1.1rem; opacity: 0.9; max-width: 600px; margin: 0 auto 2rem;">Our expert counsellors are ready to walk you through each of these steps for free.</p>
                <a href="consultation.php" class="btn btn--white btn--lg pulse-btn" style="background: white; color: var(--primary);">Book Your Free Session</a>
            </div>
        </div>
    </section>
</main>

<style>
/* GUIDE TIMELINE STYLES */
.guide-timeline {
    position: relative;
    max-width: 1000px;
    margin: 0 auto;
}

.guide-step-row {
    display: flex;
    align-items: center;
    margin-bottom: 0;
}

.guide-step-row--reverse {
    flex-direction: row-reverse;
}

.guide-step-content {
    flex: 1;
    padding: 3rem;
    background: #fff;
    border-radius: 20px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.03);
    border: 1px solid #f1f5f9;
    position: relative;
    transition: all 0.3s ease;
}

.guide-step-content:hover {
    transform: translateY(-5px);
    box-shadow: 0 20px 40px rgba(0,0,0,0.06);
    border-color: var(--primary-light);
}

.guide-step-badge {
    position: absolute;
    top: -15px;
    left: 30px;
    background: var(--primary);
    color: #fff;
    font-weight: 800;
    padding: 0.5rem 1rem;
    border-radius: 10px;
    font-size: 0.9rem;
}

.guide-step-row--reverse .guide-step-badge {
    left: auto;
    right: 30px;
}

.guide-step-content h3 {
    margin-bottom: 1rem;
    color: var(--dark);
    font-size: 1.5rem;
}

.guide-step-content p {
    color: var(--gray);
    line-height: 1.7;
    margin-bottom: 0;
}

.guide-step-icon {
    position: absolute;
    bottom: -20px;
    right: 30px;
    width: 60px;
    height: 60px;
    background: #fff;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    box-shadow: 0 10px 20px rgba(0,0,0,0.1);
}

.guide-step-icon--blue { color: #0ea5e9; }
.guide-step-icon--purple { color: #8b5cf6; }
.guide-step-icon--orange { color: #f97316; }
.guide-step-icon--teal { color: #14b8a6; }
.guide-step-icon--pink { color: #ec4899; }
.guide-step-icon--gold { color: #f59e0b; }

.guide-step-visual {
    width: 100px;
    display: flex;
    flex-direction: column;
    align-items: center;
    position: relative;
    align-self: stretch;
}

.guide-step-dot {
    width: 20px;
    height: 20px;
    background: var(--primary);
    border: 4px solid #fff;
    border-radius: 50%;
    box-shadow: 0 0 0 4px var(--primary-light);
    z-index: 2;
    margin-top: 3rem;
}

.guide-step-line {
    position: absolute;
    top: 3rem;
    bottom: -3rem;
    width: 2px;
    background: #e2e8f0;
    z-index: 1;
}

.guide-step-row:last-child .guide-step-line {
    display: none;
}

.guide-step-empty {
    flex: 1;
}

@media (max-width: 768px) {
    .guide-step-row, .guide-step-row--reverse {
        flex-direction: column;
        align-items: flex-start;
        gap: 2rem;
        margin-bottom: 4rem;
    }
    .guide-step-visual, .guide-step-empty {
        display: none;
    }
    .guide-step-content {
        padding: 2rem;
        width: 100%;
    }
}
</style>

<?php require_once 'includes/footer.php'; ?>
