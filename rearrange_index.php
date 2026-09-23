<?php
$content = file_get_contents('index.php');

// Extract sections
$pattern_hero = '/(.*?<section class="hero-sky" id="home">.*?<\/section>)/s';
$pattern_enquiry = '/(<!-- HOME ENQUIRY SECTION \(IDP STYLE\) -->.*?<\/section>)/s';
$pattern_why = '/(<section class="section why-elite-section" id="about".*?<\/section>)/s';
$pattern_countries = '/(<!-- COUNTRIES -->.*?<\/section>)/s';
$pattern_process = '/(<!-- PROCESS -->.*?<\/section>)/s';
$pattern_services = '/(<section class="section services-bento" id="services".*?<\/section>)/s';
$pattern_testprep = '/(<!-- TEST PREP -->.*?<\/section>)/s';
$pattern_gallery = '/(<!-- GALLERY SECTION -->.*?<\/section>)/s';
$pattern_contact = '/(<!-- CONTACT SECTION -->.*?<\/section>)/s';
$pattern_video = '/(<!-- VIDEO TESTIMONIALS -->.*?<\/section>)/s';
$pattern_team = '/(<!-- TEAM MEMBERS SECTION -->.*?<\/section>)/s';
$pattern_cta = '/(<!-- CTA BANNER -->.*?<\/section>)/s';

preg_match($pattern_hero, $content, $m_hero);
preg_match($pattern_enquiry, $content, $m_enquiry);
preg_match($pattern_why, $content, $m_why);
preg_match($pattern_countries, $content, $m_countries);
preg_match($pattern_process, $content, $m_process);
preg_match($pattern_services, $content, $m_services);
preg_match($pattern_testprep, $content, $m_testprep);
preg_match($pattern_gallery, $content, $m_gallery);
preg_match($pattern_contact, $content, $m_contact);
preg_match($pattern_video, $content, $m_video);
preg_match($pattern_team, $content, $m_team);
preg_match($pattern_cta, $content, $m_cta);

$hero = $m_hero[0];
$why = $m_why[0];
$countries = $m_countries[0];
$process = $m_process[0];
$services = $m_services[0];
$testprep = $m_testprep[0];
$gallery = $m_gallery[0];
$contact = $m_contact[0];
$video = $m_video[0];
$team = $m_team[0];
$cta = $m_cta[0];

// New sections
$trust_numbers = <<<EOT
<!-- TRUST NUMBERS -->
<section class="trust-numbers" style="background: #ffffff; padding: 2rem 0; border-bottom: 1px solid #f1f5f9;">
  <div class="container">
    <div style="display: flex; justify-content: center; align-items: center; gap: 2rem; flex-wrap: wrap; text-align: center;">
      <div style="flex: 1; min-width: 200px;">
        <h3 style="font-size: 2.5rem; font-weight: 800; color: #1e293b; margin: 0;">1,000+</h3>
        <p style="color: #64748b; font-weight: 600; margin: 0; text-transform: uppercase; font-size: 0.9rem; letter-spacing: 1px;">University Partners</p>
      </div>
      <div style="width: 2px; height: 50px; background: #e2e8f0; display: inline-block;"></div>
      <div style="flex: 1; min-width: 200px;">
        <h3 style="font-size: 2.5rem; font-weight: 800; color: #1e293b; margin: 0;">20+</h3>
        <p style="color: #64748b; font-weight: 600; margin: 0; text-transform: uppercase; font-size: 0.9rem; letter-spacing: 1px;">Countries</p>
      </div>
      <div style="width: 2px; height: 50px; background: #e2e8f0; display: inline-block;"></div>
      <div style="flex: 1; min-width: 200px;">
        <h3 style="font-size: 2.5rem; font-weight: 800; color: #1e293b; margin: 0;">2015</h3>
        <p style="color: #64748b; font-weight: 600; margin: 0; text-transform: uppercase; font-size: 0.9rem; letter-spacing: 1px;">Since</p>
      </div>
      <div style="width: 2px; height: 50px; background: #e2e8f0; display: inline-block;"></div>
      <div style="flex: 1; min-width: 200px;">
        <h3 style="font-size: 2.5rem; font-weight: 800; color: #1e293b; margin: 0;">98%</h3>
        <p style="color: #64748b; font-weight: 600; margin: 0; text-transform: uppercase; font-size: 0.9rem; letter-spacing: 1px;">Visa Success</p>
      </div>
    </div>
  </div>
</section>
EOT;

$find_uni = <<<EOT
<!-- FIND YOUR UNIVERSITY SECTION -->
<section id="find-university" class="section" style="background: #f8fafc; padding: 4rem 0;">
  <div class="container">
    <div class="section__header text-center" style="margin-bottom: 2rem;">
      <h2 class="section__title">Find Your <span>University</span></h2>
      <p class="section__subtitle">Search across 1,000+ top institutions globally</p>
    </div>
    
    <div style="background: white; border-radius: 20px; padding: 2rem; box-shadow: 0 10px 30px rgba(0,0,0,0.05); max-width: 1000px; margin: 0 auto;">
      <form id="universitySearchForm" action="universities.php" method="GET" style="display: flex; flex-wrap: wrap; gap: 1rem; align-items: flex-end;">
        <div style="flex: 1; min-width: 200px;">
          <label style="display: block; font-weight: 600; color: #1e293b; margin-bottom: 0.5rem; font-size: 0.9rem;">Country</label>
          <select name="country" style="width: 100%; padding: 0.8rem 1rem; border: 1px solid #e2e8f0; border-radius: 10px; background: #f8fafc; color: #1e293b; font-family: inherit; font-size: 1rem; outline: none; transition: border-color 0.3s ease;">
            <option value="">Any Country</option>
            <option value="usa">USA</option>
            <option value="uk">UK</option>
            <option value="canada">Canada</option>
            <option value="australia">Australia</option>
            <option value="germany">Germany</option>
          </select>
        </div>
        <div style="flex: 1; min-width: 200px;">
          <label style="display: block; font-weight: 600; color: #1e293b; margin-bottom: 0.5rem; font-size: 0.9rem;">Course Level</label>
          <select name="course" style="width: 100%; padding: 0.8rem 1rem; border: 1px solid #e2e8f0; border-radius: 10px; background: #f8fafc; color: #1e293b; font-family: inherit; font-size: 1rem; outline: none; transition: border-color 0.3s ease;">
            <option value="">Any Course Level</option>
            <option value="ug">Undergraduate</option>
            <option value="pg">Postgraduate</option>
            <option value="phd">PhD</option>
            <option value="diploma">Diploma</option>
          </select>
        </div>
        <div style="flex: 1; min-width: 200px;">
          <label style="display: block; font-weight: 600; color: #1e293b; margin-bottom: 0.5rem; font-size: 0.9rem;">Intake</label>
          <select name="intake" style="width: 100%; padding: 0.8rem 1rem; border: 1px solid #e2e8f0; border-radius: 10px; background: #f8fafc; color: #1e293b; font-family: inherit; font-size: 1rem; outline: none; transition: border-color 0.3s ease;">
            <option value="">Any Intake</option>
            <option value="fall">Fall (Sep/Oct)</option>
            <option value="spring">Spring (Jan/Feb)</option>
            <option value="summer">Summer (May/Jun)</option>
          </select>
        </div>
        <div style="flex: 0 0 auto;">
          <button type="submit" class="btn btn--primary" style="padding: 0.8rem 2rem; border-radius: 10px; height: 50px; justify-content: center; background:#0d315c; color:white; border:none; cursor:pointer;">
            <i class="fa-solid fa-search"></i> Search
          </button>
        </div>
      </form>
    </div>
  </div>
</section>
EOT;

$scholarships = <<<EOT
<!-- SCHOLARSHIPS SECTION -->
<section class="section" style="background: #ffffff; padding: 4rem 0;">
  <div class="container">
    <div class="section__header text-center" style="margin-bottom: 3rem;">
      <span class="section__tag">Funding Options</span>
      <h2 class="section__title">Scholarships & <span>Financial Aid</span></h2>
      <p class="section__subtitle">We help you secure scholarships, grants, and education loans to fund your dream.</p>
    </div>
    <div style="display: flex; flex-wrap: wrap; gap: 2rem; justify-content: center;">
      <div style="background: #f8fafc; border-radius: 20px; padding: 2rem; flex: 1; min-width: 300px; text-align: center; box-shadow: 0 10px 30px rgba(0,0,0,0.05);">
        <i class="fa-solid fa-graduation-cap" style="font-size: 3rem; color: #10b981; margin-bottom: 1rem;"></i>
        <h3 style="margin-bottom: 1rem; font-size: 1.5rem;">University Scholarships</h3>
        <p style="color: #64748b; margin-bottom: 1.5rem;">Merit-based scholarships offered directly by our partner universities.</p>
        <a href="scholarships.php" class="btn btn--outline" style="border-color: #10b981; color: #10b981;">Learn More</a>
      </div>
      <div style="background: #f8fafc; border-radius: 20px; padding: 2rem; flex: 1; min-width: 300px; text-align: center; box-shadow: 0 10px 30px rgba(0,0,0,0.05);">
        <i class="fa-solid fa-building-columns" style="font-size: 3rem; color: #3b82f6; margin-bottom: 1rem;"></i>
        <h3 style="margin-bottom: 1rem; font-size: 1.5rem;">Education Loans</h3>
        <p style="color: #64748b; margin-bottom: 1.5rem;">Hassle-free loan processing with our banking partners at low interest rates.</p>
        <a href="education-loan.php" class="btn btn--outline" style="border-color: #3b82f6; color: #3b82f6;">Apply Now</a>
      </div>
    </div>
  </div>
</section>
EOT;

$events = <<<EOT
<!-- UPCOMING EVENTS SECTION -->
<section class="section" style="background: #f8fafc; padding: 4rem 0;">
  <div class="container">
    <div class="section__header text-center" style="margin-bottom: 3rem;">
      <span class="section__tag">Join Us</span>
      <h2 class="section__title">Upcoming <span>Events</span></h2>
      <p class="section__subtitle">Meet university delegates and our expert counsellors at our events.</p>
    </div>
    <div style="background: white; border-radius: 20px; padding: 2rem; box-shadow: 0 10px 30px rgba(0,0,0,0.05); max-width: 800px; margin: 0 auto; display: flex; align-items: center; gap: 2rem; flex-wrap: wrap;">
      <div style="background: #eff6ff; padding: 1.5rem; border-radius: 15px; text-align: center; min-width: 120px;">
        <span style="display: block; font-size: 1rem; font-weight: 700; color: #3b82f6; text-transform: uppercase;">Oct</span>
        <span style="display: block; font-size: 2.5rem; font-weight: 800; color: #1e293b; line-height: 1;">15</span>
      </div>
      <div style="flex: 1; min-width: 250px;">
        <h3 style="font-size: 1.4rem; margin-bottom: 0.5rem; color: #1e293b;">Global Education Fair 2026</h3>
        <p style="color: #64748b; margin-bottom: 0.5rem;"><i class="fa-solid fa-location-dot" style="margin-right: 0.5rem;"></i> Coimbatore Branch & Virtual</p>
        <p style="color: #64748b; font-size: 0.95rem;">Interact directly with delegates from top universities in USA, UK, Canada & Australia.</p>
      </div>
      <div>
        <a href="consultation.php" class="btn btn--primary" style="background: #0d315c; color: white;">Register Now</a>
      </div>
    </div>
  </div>
</section>
EOT;

$branches = <<<EOT
<!-- BRANCHES SECTION -->
<section class="section" style="background: #ffffff; padding: 4rem 0;">
  <div class="container">
    <div class="section__header text-center" style="margin-bottom: 3rem;">
      <span class="section__tag">Our Offices</span>
      <h2 class="section__title">Our <span>Branches</span></h2>
      <p class="section__subtitle">Visit our offices across Tamil Nadu for personalized counselling.</p>
    </div>
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 2rem;">
      <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 15px; padding: 2rem; text-align: center;">
        <h3 style="font-size: 1.25rem; margin-bottom: 1rem; color: #1e293b;">Coimbatore</h3>
        <p style="color: #64748b; font-size: 0.9rem; margin-bottom: 1.5rem;">Head Office</p>
        <a href="study-abroad-consultants-in-coimbatore.php" class="btn btn--outline" style="width: 100%; justify-content: center;">View Details</a>
      </div>
      <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 15px; padding: 2rem; text-align: center;">
        <h3 style="font-size: 1.25rem; margin-bottom: 1rem; color: #1e293b;">Chennai</h3>
        <p style="color: #64748b; font-size: 0.9rem; margin-bottom: 1.5rem;">Branch Office</p>
        <a href="study-abroad-consultants-in-chennai.php" class="btn btn--outline" style="width: 100%; justify-content: center;">View Details</a>
      </div>
      <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 15px; padding: 2rem; text-align: center;">
        <h3 style="font-size: 1.25rem; margin-bottom: 1rem; color: #1e293b;">Erode</h3>
        <p style="color: #64748b; font-size: 0.9rem; margin-bottom: 1.5rem;">Branch Office</p>
        <a href="study-abroad-consultants-in-erode.php" class="btn btn--outline" style="width: 100%; justify-content: center;">View Details</a>
      </div>
      <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 15px; padding: 2rem; text-align: center;">
        <h3 style="font-size: 1.25rem; margin-bottom: 1rem; color: #1e293b;">Salem</h3>
        <p style="color: #64748b; font-size: 0.9rem; margin-bottom: 1.5rem;">Branch Office</p>
        <a href="study-abroad-consultants-in-salem.php" class="btn btn--outline" style="width: 100%; justify-content: center;">View Details</a>
      </div>
    </div>
    <div style="text-align: center; margin-top: 2rem;">
      <a href="branch.php" class="btn btn--primary">View All Branches</a>
    </div>
  </div>
</section>
EOT;

// Extract top and bottom parts
preg_match('/(.*?)(<section class="hero-sky" id="home">)/s', $content, $m_top);
preg_match('/(<\?php\s*\/\/\s*Fetch active popups.*?)$/s', $content, $m_bottom);

$top = $m_top[1];
$bottom = $m_bottom[1];

// Also update final CTA text
$cta = preg_replace(
    '/(<h2>).*?(<\/h2>)/s', 
    '$1Ready to Start Your Study Abroad Journey?$2', 
    $cta
);

$new_content = $top . "\n" .
$hero . "\n" .
$trust_numbers . "\n" .
$find_uni . "\n" .
$countries . "\n" .
$why . "\n" .
$process . "\n" .
$services . "\n" .
$scholarships . "\n" .
"<!-- STUDENT SUCCESS STORIES -->\n" .
$gallery . "\n" .
$video . "\n" .
$events . "\n" .
$branches . "\n" .
$contact . "\n" .
$team . "\n" .
$cta . "\n" .
$bottom;

file_put_contents('index.php', $new_content);
echo "index.php rearranged successfully!\n";
?>
