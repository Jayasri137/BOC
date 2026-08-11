<?php
require_once 'includes/config.php';
$pageTitle = 'Free Study Abroad Counselling in Coimbatore | Bluestone Overseas';
$pageDesc = 'Receive expert counselling on universities, courses, scholarships, and student visas.';
$pageHeroImage = 'assets/images/SC.png';
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
                        'color' => 'purple',
                        'image' => 'assets/images/RO.png'
                    ],
                    [
                        'num' => '02',
                        'title' => 'Speak with a Counsellor',
                        'icon' => 'fa-headset',
                        'desc' => 'Book a FREE session with our experts. We provide personalized advice, profile assessment, and help you narrow down your best-fit options.',
                        'color' => 'blue',
                        'image' => 'assets/images/SC.png'
                    ],
                    [
                        'num' => '03',
                        'title' => 'Make Your Application',
                        'icon' => 'fa-file-signature',
                        'desc' => 'We help you prepare your SOP, LORs, and academic documents. Our team manages the entire submission process to ensure error-free applications.',
                        'color' => 'orange',
                        'image' => 'assets/images/Appli.png'
                    ],
                    [
                        'num' => '04',
                        'title' => 'Receive Your Offer',
                        'icon' => 'fa-envelope-open-text',
                        'desc' => 'Celebrate your acceptance! We guide you through the conditions of your offer letter and help you secure your seat at the university.',
                        'color' => 'teal',
                        'image' => 'assets/images/Offer.png'
                    ],
                    [
                        'num' => '05',
                        'title' => 'Secure Your Funding',
                        'icon' => 'fa-hand-holding-dollar',
                        'desc' => 'Explore scholarship opportunities and apply for student loans. We provide guidance on financial documentation and forex services.',
                        'color' => 'pink',
                        'image' => 'assets/images/Fund.png'
                    ],
                    [
                        'num' => '06',
                        'title' => 'Apply for Your Visa',
                        'icon' => 'fa-passport',
                        'desc' => 'Our visa experts assist with documentation, financial requirements, and interview preparation to ensure a high success rate.',
                        'color' => 'gold',
                        'image' => 'assets/images/Visa.png'
                    ],
                    [
                        'num' => '07',
                        'title' => 'Find Accommodation',
                        'icon' => 'fa-house-user',
                        'desc' => 'Arrange your new home abroad. We help you find student housing, homestays, or private rentals near your campus.',
                        'color' => 'blue',
                        'image' => 'assets/images/Acc.png'
                    ],
                    [
                        'num' => '08',
                        'title' => 'Ready, Set, Go!',
                        'icon' => 'fa-plane-departure',
                        'desc' => 'Attend our pre-departure briefing. Get tips on culture, packing, and airport pickup. Your global journey officially begins!',
                        'color' => 'purple',
                        'image' => 'assets/images/img4.png'
                    ]
                ];

                foreach ($steps as $i => $step):
                    $isEven = ($i % 2 !== 0);
                ?>
                <div class="guide-step-row <?= $isEven ? 'guide-step-row--reverse' : '' ?> animate-on-scroll">
                    <div class="guide-step-content guide-step-content--<?= $step['color'] ?>">
                        <div class="guide-step-badge"><?= $step['num'] ?></div>
                        <h3><?= $step['title'] ?></h3>
                        <p><?= $step['desc'] ?></p>
                        <div class="guide-step-icon"><i class="fa-solid <?= $step['icon'] ?>"></i></div>
                    </div>
                    <div class="guide-step-visual">
                        <div class="guide-step-line"></div>
                        <div class="guide-step-dot"></div>
                    </div>
                    <div class="guide-step-image-col">
                        <img src="<?= $step['image'] ?>" alt="<?= $step['title'] ?>" class="guide-step-img">
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- STUDENT ESSENTIALS -->
    <section class="section" style="background: linear-gradient(to bottom, #ffffff, #f1f5f9);">
        <div class="container">
            <div class="text-center animate-on-scroll" style="margin-bottom: 4rem;">
                <span class="section__tag">Support</span>
                <h2 class="section__title">Student <span>Essentials</span></h2>
                <p class="section__subtitle">Critical services to ensure you are safe, secure, and prepared for your life overseas.</p>
            </div>

            <div class="essentials-grid">
                <!-- Health Insurance -->
                <a href="health-insurance.php" class="essential-card essential-card--health animate-on-scroll">
                    <div class="essential-card__content">
                        <div class="essential-card__icon"><i class="fa-solid fa-shield-heart"></i></div>
                        <h3>Health Insurance</h3>
                        <p>Mandatory health cover (OSHC, NHS) to protect you against medical expenses while studying abroad.</p>
                        <span class="essential-card__btn">Explore Options <i class="fa-solid fa-arrow-right"></i></span>
                    </div>
                    <div class="essential-card__bg">
                        <i class="fa-solid fa-stethoscope"></i>
                    </div>
                </a>

                <!-- Money Transfer -->
                <a href="money-transfer.php" class="essential-card essential-card--finance animate-on-scroll delay-1">
                    <div class="essential-card__content">
                        <div class="essential-card__icon"><i class="fa-solid fa-money-bill-transfer"></i></div>
                        <h3>Money Transfer</h3>
                        <p>Secure, fast, and low-cost international money transfers for tuition fees and living expenses.</p>
                        <span class="essential-card__btn">View Rates <i class="fa-solid fa-arrow-right"></i></span>
                    </div>
                    <div class="essential-card__bg">
                        <i class="fa-solid fa-coins"></i>
                    </div>
                </a>

                <!-- International SIM -->
                <a href="sim-card.php" class="essential-card essential-card--sim animate-on-scroll delay-2">
                    <div class="essential-card__content">
                        <div class="essential-card__icon"><i class="fa-solid fa-mobile-screen-button"></i></div>
                        <h3>International SIM</h3>
                        <p>Stay connected from the moment you land. Get your international SIM card before you depart India.</p>
                        <span class="essential-card__btn">Get Connected <i class="fa-solid fa-arrow-right"></i></span>
                    </div>
                    <div class="essential-card__bg">
                        <i class="fa-solid fa-satellite-dish"></i>
                    </div>
                </a>
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



<?php require_once 'includes/footer.php'; ?>
