<?php
require_once 'includes/config.php';
$pageTitle = 'Sitemap | Bluestone Overseas Consultants';
$pageDesc = 'Navigate through Bluestone Overseas Consultants website. Access our study destinations, essentials, test preparation guides, and company pages.';
require_once 'includes/header.php';
?>

<style>
.sitemap-header {
  background: linear-gradient(135deg, var(--secondary-dark), var(--dark2));
  padding: 6rem 0 4.5rem;
  color: var(--white);
  position: relative;
  overflow: hidden;
  border-bottom: 1px solid rgba(255, 255, 255, 0.05);
}
.sitemap-header::after {
  content: '';
  position: absolute;
  top: -50px;
  right: -50px;
  width: 350px;
  height: 350px;
  background: radial-gradient(circle, rgba(255, 0, 0, 0.15) 0%, transparent 70%);
  border-radius: 50%;
  pointer-events: none;
}
.sitemap-header::before {
  content: '';
  position: absolute;
  bottom: -50px;
  left: -50px;
  width: 250px;
  height: 250px;
  background: radial-gradient(circle, rgba(255, 51, 51, 0.08) 0%, transparent 70%);
  border-radius: 50%;
  pointer-events: none;
}
.sitemap-breadcrumb {
  display: flex;
  align-items: center;
  gap: 0.5rem;
  font-size: 0.85rem;
  color: rgba(255, 255, 255, 0.6);
  margin-bottom: 1.25rem;
  font-weight: 500;
}
.sitemap-breadcrumb a {
  color: rgba(255, 255, 255, 0.8);
  transition: color var(--transition);
}
.sitemap-breadcrumb a:hover {
  color: var(--primary);
}
.sitemap-grid {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 2.5rem;
}
.sitemap-card {
  background: var(--white);
  padding: 2.5rem;
  border-radius: 24px;
  box-shadow: var(--shadow);
  border: 1px solid rgba(255, 0, 0, 0.04);
  transition: transform var(--transition), box-shadow var(--transition);
}
.sitemap-card:hover {
  transform: translateY(-5px);
  box-shadow: var(--shadow-lg);
}
.sitemap-card-title {
  font-family: 'Plus Jakarta Sans', sans-serif;
  font-size: 1.35rem;
  font-weight: 800;
  color: var(--dark);
  margin-bottom: 1.5rem;
  display: flex;
  align-items: center;
  gap: 0.75rem;
  border-bottom: 2px solid rgba(255, 0, 0, 0.08);
  padding-bottom: 0.75rem;
}
.sitemap-card-title i {
  color: var(--primary);
}
.sitemap-links-list {
  list-style: none;
  padding: 0;
  margin: 0;
  display: flex;
  flex-direction: column;
  gap: 0.85rem;
}
.sitemap-link-item a {
  display: inline-flex;
  align-items: center;
  gap: 0.6rem;
  color: var(--gray);
  font-weight: 500;
  font-size: 0.95rem;
  transition: color 0.3s, transform 0.3s;
}
.sitemap-link-item a:hover {
  color: var(--primary);
  transform: translateX(4px);
}
.sitemap-link-item i {
  font-size: 0.8rem;
  color: rgba(255, 0, 0, 0.4);
  transition: color 0.3s;
}
.sitemap-link-item a:hover i {
  color: var(--primary);
}

/* Destinations sitemap styles */
.sitemap-countries-grid {
  display: grid;
  grid-template-columns: repeat(4, 1fr);
  gap: 1.25rem;
}
.sitemap-country-card {
  background: var(--white);
  padding: 1rem;
  border-radius: var(--radius);
  border: 1px solid rgba(0, 0, 0, 0.05);
  display: flex;
  align-items: center;
  gap: 0.75rem;
  transition: all var(--transition);
}
.sitemap-country-card:hover {
  border-color: var(--primary);
  transform: translateY(-2px);
  box-shadow: var(--shadow);
}
.sitemap-country-card .flag-circle {
  width: 32px;
  height: 32px;
  border-radius: 50%;
  overflow: hidden;
  display: flex;
  align-items: center;
  justify-content: center;
  flex-shrink: 0;
}
.sitemap-country-card span {
  font-size: 0.9rem;
  font-weight: 600;
  color: var(--dark);
}

@media (max-width: 1024px) {
  .sitemap-grid {
    grid-template-columns: repeat(2, 1fr);
  }
  .sitemap-countries-grid {
    grid-template-columns: repeat(3, 1fr);
  }
}
@media (max-width: 768px) {
  .sitemap-grid {
    grid-template-columns: 1fr;
  }
  .sitemap-countries-grid {
    grid-template-columns: repeat(2, 1fr);
  }
  .sitemap-card {
    padding: 2rem 1.5rem;
  }
}
@media (max-width: 480px) {
  .sitemap-countries-grid {
    grid-template-columns: 1fr;
  }
}
</style>

<main>
  <!-- Page Header -->
  <section class="sitemap-header animate-on-scroll">
    <div class="container">
      <div class="sitemap-breadcrumb">
        <a href="index.php">Home</a>
        <i class="fa-solid fa-chevron-right" style="font-size: 0.7rem; opacity: 0.7;"></i>
        <span style="color: var(--primary);">Sitemap</span>
      </div>
      <h1 style="font-family: 'Plus Jakarta Sans', sans-serif; font-size: clamp(2rem, 4vw, 3rem); font-weight: 800; line-height: 1.2; margin: 0; letter-spacing: -0.02em;">
        Website <span style="background: var(--gradient); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;">Sitemap</span>
      </h1>
      <p style="margin-top: 1rem; font-size: 1.05rem; color: rgba(255,255,255,0.7); max-width: 600px; line-height: 1.5; font-weight: 400;">
        Explore all active departments, corporate information, test guides, and popular global destinations on our website.
      </p>
    </div>
  </section>

  <!-- Sitemap Categories Section -->
  <section class="section" style="background: var(--light); padding: 4rem 0;">
    <div class="container animate-on-scroll">
      <div class="sitemap-grid">
        
        <!-- Category 1: Study Abroad Steps -->
        <div class="sitemap-card">
          <h3 class="sitemap-card-title">
            <i class="fa-solid fa-route"></i> Study Abroad Steps
          </h3>
          <ul class="sitemap-links-list">
            <li class="sitemap-link-item"><a href="guide-me.php"><i class="fa-solid fa-chevron-right"></i> Step-by-Step Guide</a></li>
            <li class="sitemap-link-item"><a href="student-counselling.php"><i class="fa-solid fa-chevron-right"></i> Student Counselling</a></li>
            <li class="sitemap-link-item"><a href="university-selection.php"><i class="fa-solid fa-chevron-right"></i> Where &amp; What to Study</a></li>
            <li class="sitemap-link-item"><a href="admission-processing.php"><i class="fa-solid fa-chevron-right"></i> Admission Processing</a></li>
            <li class="sitemap-link-item"><a href="visa-processing.php"><i class="fa-solid fa-chevron-right"></i> Visa Processing Support</a></li>
            <li class="sitemap-link-item"><a href="accommodation.php"><i class="fa-solid fa-chevron-right"></i> Prepare to Depart</a></li>
          </ul>
        </div>

        <!-- Category 2: Student Essentials -->
        <div class="sitemap-card">
          <h3 class="sitemap-card-title">
            <i class="fa-solid fa-hand-holding-dollar"></i> Student Essentials
          </h3>
          <ul class="sitemap-links-list">
            <li class="sitemap-link-item"><a href="education-loan.php"><i class="fa-solid fa-chevron-right"></i> Education Loans</a></li>
            <li class="sitemap-link-item"><a href="accommodation.php"><i class="fa-solid fa-chevron-right"></i> Student Housing</a></li>
            <li class="sitemap-link-item"><a href="health-insurance.php"><i class="fa-solid fa-chevron-right"></i> Health Insurance (OSHC)</a></li>
            <li class="sitemap-link-item"><a href="money-transfer.php"><i class="fa-solid fa-chevron-right"></i> Outward Money Transfer</a></li>
            <li class="sitemap-link-item"><a href="bank-account.php"><i class="fa-solid fa-chevron-right"></i> Bank Account Opening</a></li>
            <li class="sitemap-link-item"><a href="sim-card.php"><i class="fa-solid fa-chevron-right"></i> International SIM Cards</a></li>
            <li class="sitemap-link-item"><a href="part-time-jobs.php"><i class="fa-solid fa-chevron-right"></i> Part-Time Work Guide</a></li>
          </ul>
        </div>

        <!-- Category 3: Find a Course -->
        <div class="sitemap-card">
          <h3 class="sitemap-card-title">
            <i class="fa-solid fa-graduation-cap"></i> Find your Course
          </h3>
          <ul class="sitemap-links-list">
            <li class="sitemap-link-item"><a href="courses.php"><i class="fa-solid fa-chevron-right"></i> Subject Categories</a></li>
            <li class="sitemap-link-item"><a href="universities.php"><i class="fa-solid fa-chevron-right"></i> Global Institutions</a></li>
            <li class="sitemap-link-item"><a href="scholarships.php"><i class="fa-solid fa-chevron-right"></i> Scholarships &amp; Funding</a></li>
            <li class="sitemap-link-item"><a href="ielts.php"><i class="fa-solid fa-chevron-right"></i> IELTS Coaching</a></li>
            <li class="sitemap-link-item"><a href="toefl.php"><i class="fa-solid fa-chevron-right"></i> TOEFL Exam Prep</a></li>
            <li class="sitemap-link-item"><a href="pte.php"><i class="fa-solid fa-chevron-right"></i> PTE Academic Training</a></li>
          </ul>
        </div>

        <!-- Category 4: Corporate & Info -->
        <div class="sitemap-card">
          <h3 class="sitemap-card-title">
            <i class="fa-solid fa-building"></i> Company Information
          </h3>
          <ul class="sitemap-links-list">
            <li class="sitemap-link-item"><a href="About_us.php"><i class="fa-solid fa-chevron-right"></i> Corporate Profile</a></li>
            <li class="sitemap-link-item"><a href="Award_Achievements.php"><i class="fa-solid fa-chevron-right"></i> Key Achievements</a></li>
            <li class="sitemap-link-item"><a href="events.php"><i class="fa-solid fa-chevron-right"></i> Student Events &amp; Fairs</a></li>
            <li class="sitemap-link-item"><a href="Blog.php"><i class="fa-solid fa-chevron-right"></i> Study Abroad Blogs</a></li>
            <li class="sitemap-link-item"><a href="gallery.php"><i class="fa-solid fa-chevron-right"></i> Gallery &amp; Campus Fairs</a></li>
            <li class="sitemap-link-item"><a href="branch.php"><i class="fa-solid fa-chevron-right"></i> Find Nearest Branch</a></li>
            <li class="sitemap-link-item"><a href="contact.php"><i class="fa-solid fa-chevron-right"></i> Get in Touch</a></li>
          </ul>
        </div>

        <!-- Category 5: Quick Support -->
        <div class="sitemap-card">
          <h3 class="sitemap-card-title">
            <i class="fa-solid fa-circle-info"></i> Support &amp; Legal
          </h3>
          <ul class="sitemap-links-list">
            <li class="sitemap-link-item"><a href="consultation.php"><i class="fa-solid fa-chevron-right"></i> Book Free Consultation</a></li>
            <li class="sitemap-link-item"><a href="privacy-policy.php"><i class="fa-solid fa-chevron-right"></i> Privacy Policy</a></li>
            <li class="sitemap-link-item"><a href="terms-and-conditions.php"><i class="fa-solid fa-chevron-right"></i> Terms &amp; Conditions</a></li>
            <li class="sitemap-link-item"><a href="sitemap.xml" target="_blank"><i class="fa-solid fa-chevron-right"></i> XML Sitemap (Search Engines)</a></li>
          </ul>
        </div>

      </div>
    </div>
  </section>

  <!-- Destinations Section -->
  <section class="section" style="background: var(--white); padding: 4rem 0 6rem;">
    <div class="container animate-on-scroll">
      <div class="text-center" style="margin-bottom: 3.5rem;">
        <span class="section__tag">Destinations</span>
        <h2 class="section__title">Study <span>Destinations</span></h2>
        <p class="section__subtitle">Direct pathways to major universities across the globe.</p>
      </div>

      <div class="sitemap-countries-grid">
        <?php
        $countries = [
          ['study-in-australia.php', 'Australia', 'au'],
          ['study-in-canada.php', 'Canada', 'ca'],
          ['study-in-germany.php', 'Germany', 'de'],
          ['study-in-ireland.php', 'Ireland', 'ie'],
          ['study-in-new-zealand.php', 'New Zealand', 'nz'],
          ['study-in-singapore.php', 'Singapore', 'sg'],
          ['study-in-switzerland.php', 'Switzerland', 'ch'],
          ['study-in-uk.php', 'United Kingdom', 'gb'],
          ['study-in-usa.php', 'United States', 'us'],
          ['study-in-uae.php', 'UAE', 'ae'],
          ['study-in-italy.php', 'Italy', 'it'],
          ['study-in-france.php', 'France', 'fr'],
          ['study-in-netherlands.php', 'Netherlands', 'nl'],
          ['study-in-sweden.php', 'Sweden', 'se'],
          ['study-in-spain.php', 'Spain', 'es'],
          ['study-in-austria.php', 'Austria', 'at'],
          ['study-in-denmark.php', 'Denmark', 'dk'],
          ['study-in-finland.php', 'Finland', 'fi'],
          ['study-in-hungary.php', 'Hungary', 'hu'],
          ['study-in-poland.php', 'Poland', 'pl'],
          ['study-in-czech-republic.php', 'Czech Republic', 'cz'],
          ['study-in-malaysia.php', 'Malaysia', 'my'],
          ['study-in-japan.php', 'Japan', 'jp'],
          ['study-in-china.php', 'China', 'cn'],
          ['study-in-belgium.php', 'Belgium', 'be'],
          ['study-in-south-korea.php', 'South Korea', 'kr'],
        ];
        foreach ($countries as [$url, $name, $flag]):
        ?>
          <a href="<?= $url ?>" class="sitemap-country-card">
            <div class="flag-circle">
              <span class="country-flag fi fi-<?= $flag ?>"></span>
            </div>
            <span><?= $name ?></span>
          </a>
        <?php endforeach; ?>
      </div>
    </div>
  </section>
</main>

<?php require_once 'includes/footer.php'; ?>
