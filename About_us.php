<?php
require_once 'includes/config.php';
$pageTitle = 'About Us | Bluestone Overseas Consultants';
$pageDesc = 'Learn about Bluestone Overseas Consultants - trusted study abroad experts since 2015 with 8 branches across India.';
require_once 'includes/header.php';
?>
<main><!-- Our Story Section -->
  <section class="section about-story">
    <div class="container">
      <div class="about-grid">
        <div class="about-img-wrap animate-on-scroll">
          <div class="img-frame">
            <img src="assets/images/ocs3.png" alt="Bluestone Overseas Team" style="width:100%; height:100%; object-fit:cover; border-radius:15px;">
          </div>
          <div class="experience-badge animate-on-scroll delay-2">
            <span class="num">10k+</span>
            <span class="label">Success<br>Stories</span>
          </div>
          <div class="floating-stats glass animate-on-scroll delay-3">
            <div class="f-stat">
              <i class="fa-solid fa-graduation-cap"></i>
              <span><strong>700+</strong> Universities</span>
            </div>
          </div>
        </div>
        <div class="animate-on-scroll delay-1">
          <span class="section__tag">Our Story</span>
          <h2 class="section__title" style="text-align:left;margin-top:.75rem">A Beacon of Hope for <span>Global Aspirations</span></h2>
          <p class="p-lead">Bluestones Overseas Consultants is a top-tier visa and immigration consultancy known for its dedicated and personalized client support.</p>
          <div class="story-content">
            <p>Established in 2015, our firm has become a beacon of hope for individuals aspiring to navigate the complexities of immigration processes across major global destinations.</p>
            <p>For the past many years, we have been sending thousands of students every year to various highly reputed Universities/ Colleges in <strong><span class="fi fi-gb"></span> UK, <span class="fi fi-us"></span> USA, <span class="fi fi-au"></span> Australia, <span class="fi fi-my"></span> Malaysia, <span class="fi fi-mu"></span> Mauritius, <span class="fi fi-ca"></span> Canada, <span class="fi fi-ie"></span> Ireland, <span class="fi fi-ch"></span> Switzerland, <span class="fi fi-fr"></span> France, <span class="fi fi-it"></span> Italy, <span class="fi fi-de"></span> Germany, <span class="fi fi-sg"></span> Singapore, <span class="fi fi-nl"></span> Netherlands, <span class="fi fi-ae"></span> Dubai</strong>, and more.</p>
            <p>We aspire to not just send students abroad, but to prepare them to thrive, lead, and excel on international platforms. Our journey is defined by the success of our students who are now building their futures worldwide.</p>
          </div>
          <div class="about-features">
            <div class="a-feat">
              <div class="a-feat-icon a-feat-icon--blue">
                <i class="fa-solid fa-earth-americas"></i>
              </div>
              <span>25+ Countries Served</span>
            </div>
            <div class="a-feat">
              <div class="a-feat-icon a-feat-icon--purple">
                <i class="fa-solid fa-university"></i>
              </div>
              <span>700+ University Partners</span>
            </div>
            <div class="a-feat">
              <div class="a-feat-icon a-feat-icon--orange">
                <i class="fa-solid fa-award"></i>
              </div>
              <span>10,000+ Success Stories</span>
            </div>
          </div>
          <div style="margin-top:2.5rem">
            <a href="consultation.php" class="btn btn--primary btn--lg">
              <i class="fa-solid fa-calendar-check"></i> Start Your Journey
            </a>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Vision & Mission -->
  <section class="section vision-mission" style="background:#f8fafc">
    <div class="container">
      <div class="section__header animate-on-scroll">
        <span class="section__tag">Purpose</span>
        <h2 class="section__title">Our Vision & <span>Mission</span></h2>
        <div class="accent-bar"></div>
      </div>
      <div class="vision-grid">
        <div class="vision-card animate-on-scroll">
          <div class="icon-colorful icon-colorful--blue" style="margin: 0 auto 1.5rem;"><i class="fa-solid fa-eye"></i></div>
          <h3>Our Vision</h3>
          <p>To be the most trusted and sought-after global education consultancy, empowering students to become global citizens and leaders of tomorrow.</p>
        </div>
        <div class="vision-card animate-on-scroll delay-1">
          <div class="icon-colorful icon-colorful--purple" style="margin: 0 auto 1.5rem;"><i class="fa-solid fa-bullseye"></i></div>
          <h3>Our Mission</h3>
          <p>To provide transparent, personalized, and expert guidance to students, bridging the gap between their aspirations and world-class international education.</p>
        </div>
      </div>
    </div>
  </section>

  <!-- Milestones Timeline -->
  <section class="section timeline-section">
    <div class="container">
      <div class="section__header animate-on-scroll">
        <span class="section__tag">Milestones</span>
        <h2 class="section__title">Our Journey <span>Since 2015</span></h2>
        <div class="accent-bar"></div>
      </div>
      <div class="timeline">
        <?php
        $milestones = [
          ['2015', 'The Beginning', 'Bluestone Overseas was founded in Coimbatore with a vision to help students study abroad.'],
          ['2017', 'Expansion Starts', 'Opened our second branch in Salem and established first few international tie-ups.'],
          ['2019', 'Reaching New Heights', 'Crossed 1,000 successful student placements and expanded to Erode and Namakkal.'],
          ['2021', 'Global Footprint', 'Opened our international office in Nepal to support students across borders.'],
          ['2023', 'Canada Operations', 'Launched our Canada office to provide post-landing support to our students.'],
          ['2025', 'The Digital Era', '8 branches across India and a global community of 10,000+ successful alumni worldwide.']
        ];
        foreach ($milestones as $index => [$year, $title, $desc]):
        ?>
        <div class="timeline-item animate-on-scroll <?= $index % 2 == 0 ? 'left' : 'right' ?>">
          <div class="timeline-content">
            <span class="t-year" style="background: var(--<?= ['blue-gradient', 'purple-gradient', 'orange-gradient', 'teal-gradient', 'pink-gradient', 'gold-gradient'][$index % 6] ?>); background: linear-gradient(135deg, <?= ['#0ea5e9, #38bdf8', '#8b5cf6, #a78bfa', '#f97316, #fb923c', '#14b8a6, #2dd4bf', '#ec4899, #f472b6', '#f59e0b, #fbbf24'][$index % 6] ?>);"><?= $year ?></span>
            <h4><?= $title ?></h4>
            <p><?= $desc ?></p>
          </div>
        </div>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

  <!-- Leadership Section -->
  <section class="section leadership-premium" style="background:#f8fafc; color:#333">
    <div class="container">
      <div class="leadership-wrapper">
        <div class="leadership-image-col animate-on-scroll">
          <div class="leadership-image-frame">
            <img src="assets/images/MD.jpeg" alt="Mr. Kumaresan - Managing Director">
            <div class="leadership-experience">
              <span class="exp-num">10+</span>
              <span class="exp-text">Years of Excellence</span>
            </div>
            <div class="leadership-image-dots"></div>
          </div>
        </div>
        <div class="leadership-content-col animate-on-scroll delay-1">
          <span class="section__tag" style="background:rgba(255, 0, 0, 0.1); color: #ef4444; border-color: rgba(239, 68, 68, 0.2);">Managing Director</span>
          <h2 class="leadership-title">The Visionary <span style="color: #ef4444;">Behind Bluestone</span></h2>
          <div class="leadership-message">
            <div class="message-quote-icon">
              <i class="fa-solid fa-quote-left"></i>
            </div>
            <p class="message-highlight" style="color: #ef4444;">
              A dynamic visionary and transformative leader, dedicated to shaping the future of young minds and propelling them toward prosperity and success.
            </p>
            <p style="color: #333;">
              With over a decade of impactful leadership at <strong>Bluestone Overseas Consultants</strong>, he has been the driving force guiding countless students to realize their global education and career aspirations with integrity, excellence, and personalized care. He continues to ignite change and build futures, blending the best of education, inspiration, and human values.
            </p>
            <p style="color: #333;">
              Fuelled by an unwavering passion for holistic development and empowerment, <strong>Mr. Kumaresan</strong> has deftly expanded Bluestone into a multifaceted institution, nurturing talent across diverse realms—ranging from civil services coaching and foreign language mastery to career placements, professional sports, advanced IT training, and early childhood education.
            </p>
            <p style="color: #333;">
              Each initiative is crafted to unlock the boundless potential within every student and foster confident, empowered leaders. Inspired by his visionary approach, Bluestone stands not just as an institution, but as a launchpad where innovation meets empathy, and where every learner is encouraged to dream bigger, reach higher, and forge their unique path to success.
            </p>
          </div>
          <div class="leadership-signature">
            <h4 style="color: #ef4444;">Mr. Kumaresan</h4>
            <p style="color: #ef4444;">Managing Director, Bluestone Group of Institutions</p>
            <div class="leader-social-minimal">
              <a href="#" aria-label="LinkedIn"><i class="fa-brands fa-linkedin-in"></i></a>
              <a href="#" aria-label="Twitter"><i class="fa-brands fa-twitter"></i></a>
              <a href="#" aria-label="Instagram"><i class="fa-brands fa-instagram"></i></a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- CTA Banner -->
  <section class="cta-banner">
    <div class="container cta-banner__inner">
      <div class="animate-on-scroll">
        <h2>Ready to Start Your Global Journey?</h2>
        <p>Talk to our experts for free and take the first step towards your dream university.</p>
        <div class="cta-buttons">
          <a href="consultation.php" class="btn btn--white btn--lg">
            <i class="fa-solid fa-calendar-check"></i> Free Consultation
          </a>
          <a href="contact.php" class="btn btn--ghost btn--lg">
            <i class="fa-solid fa-envelope"></i> Contact Us
          </a>
        </div>
      </div>
    </div>
  </section>
</main>
<?php require_once 'includes/footer.php'; ?>
