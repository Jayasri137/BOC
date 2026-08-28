<?php
require_once 'includes/config.php';

// This data would ideally come from a database, but we define it here for 20 countries.
$all_countries = [
    'usa' => [
        'name' => 'USA',
        'full_name' => 'United States of America',
        'tag' => 'World\'s #1 Destination',
        'desc' => 'The USA is the pinnacle of global education, offering unparalleled research opportunities and career growth at Ivy League and top-ranked universities.',
        'fact_1' => ['4,000+', 'Universities'],
        'fact_2' => ['Fall & Spring', 'Intakes'],
        'fact_3' => ['3 Years', 'STEM OPT'],
        'fact_4' => ['$25k-$50k', 'Annual Cost'],
        'benefits' => [
            'Ivy League and top-ranked global institutions.',
            'Unmatched research facilities and funding.',
            'Flexible curriculum allowing major changes.',
            'Global networking in Silicon Valley & Wall Street.'
        ],
        'intakes' => [
            'Fall (Aug/Sep) - Primary',
            'Spring (Jan/Feb) - Secondary',
            'Summer (May/Jun) - Limited'
        ]
    ],
    'uk' => [
        'name' => 'UK',
        'full_name' => 'United Kingdom',
        'tag' => 'Prestigious & Efficient',
        'desc' => 'The UK offers world-class degrees in a shorter duration, helping you enter the workforce faster with 1-year Master\'s and Post-study work rights.',
        'fact_1' => ['1 Year', 'Master\'s Degree'],
        'fact_2' => ['2 Years', 'Graduate Visa'],
        'fact_3' => ['No GRE/GMAT', 'Required'],
        'fact_4' => ['£12k-£25k', 'Annual Cost'],
        'benefits' => [
            'Shorter course duration saves time and money.',
            'Global reputation of Russell Group universities.',
            'Post-study work rights for 2 years (Graduate Route).',
            'Rich cultural heritage and gateway to Europe.'
        ],
        'intakes' => [
            'September - Main Intake',
            'January - Second Intake'
        ]
    ],

    'canada' => [
        'name' => 'Canada',
        'full_name' => 'Canada',
        'tag' => 'The PR Hub',
        'desc' => 'Canada is the most student-friendly nation with clear pathways to permanent residency (PR) and a high quality of life through the SDS program.',
        'fact_1' => ['SDS', 'Fast Visa'],
        'fact_2' => ['3 Years', 'PGWP'],
        'fact_3' => ['90%', 'Employment Rate'],
        'fact_4' => ['CAD 15k-30k', 'Annual Cost'],
        'benefits' => [
            'Direct PR pathways through Express Entry & PNP.',
            'High quality of life and safety.',
            'Work while you study (20 hours/week).',
            'Affordable tuition compared to US/UK.'
        ],
        'intakes' => [
            'September - Fall',
            'January - Winter',
            'May - Summer'
        ]
    ],

    'australia' => [
        'name' => 'Australia',
        'full_name' => 'Australia',
        'tag' => 'Innovation & Sunshine',
        'desc' => 'Australia offers a perfect blend of high-ranked universities like the Group of Eight and a fantastic outdoor lifestyle with great work rights.',
        'fact_1' => ['Group of 8', 'Top Universities'],
        'fact_2' => ['2-4 Years', 'Post-Study Work'],
        'fact_3' => ['CRICOS', 'Regulated Quality'],
        'fact_4' => ['AUD 25k-45k', 'Annual Cost'],
        'benefits' => [
            'Post-study work rights in regional areas.',
            'Strong economy with high minimum wages.',
            'Cutting-edge research in healthcare & tech.',
            'Beautiful cities and great weather.'
        ],
        'intakes' => [
            'February - Major Intake',
            'July - Second Intake'
        ]
    ],
    
    'germany' => [
        'name' => 'Germany',
        'full_name' => 'Germany',
        'tag' => 'Engine of Europe',
        'desc' => 'Study for free or at very low cost in the land of engineering and innovation, with a strong focus on technical excellence.',
        'fact_1' => ['Free', 'Public Education'],
        'fact_2' => ['1.5 Years', 'Job Seek Visa'],
        'fact_3' => ['TU9', 'Elite Universities'],
        'fact_4' => ['€11k', 'Living Blocked Account'],
        'benefits' => [
            'Zero or low tuition fees at public universities.',
            'Strongest economy in Europe.',
            'Extensive part-time job opportunities.',
            'Learn German to boost career prospects.'
        ],
        'intakes' => [
            'Winter (October) - Main',
            'Summer (April) - Secondary'
        ]
    ],
    'singapore' => [
        'name' => 'Singapore',
        'full_name' => 'Singapore',
        'tag' => 'Global Business Hub',
        'desc' => 'The education capital of Asia, offering world-class universities like NUS and NTU right at India\'s doorstep.',
        'fact_1' => ['Top 20', 'Global Rankings'],
        'fact_2' => ['Close to India', '4h Flight'],
        'fact_3' => ['Bilingual', 'English Speaking'],
        'fact_4' => ['SGD 20k-40k', 'Annual Cost'],
        'benefits' => [
            'Top-tier universities like NUS & NTU.',
            'Financial hub with global MNC headquarters.',
            'Extremely safe and clean environment.',
            'Cultural familiarity for Indian students.'
        ],
        'intakes' => [
            'August - Main Intake',
            'January - Second Intake'
        ]
    ],
    'ireland' => [
        'name' => 'Ireland',
        'full_name' => 'Ireland',
        'tag' => 'The Silicon Docks',
        'desc' => 'A fast-growing tech hub with a friendly atmosphere and excellent post-study work options at companies like Google and Meta.',
        'fact_1' => ['2 Years', 'Stay Back'],
        'fact_2' => ['Tech Hub', 'Google, Meta HQ'],
        'fact_3' => ['English', 'Native Speaking'],
        'fact_4' => ['€12k-€20k', 'Annual Cost'],
        'benefits' => [
            'Headquarters of top tech & pharma companies.',
            'Strong focus on research and innovation.',
            'Warm, welcoming, and friendly culture.',
            'Stable and growing economy.'
        ],
        'intakes' => [
            'September - Main',
            'January - Secondary'
        ]
    ],
    'newzealand' => [
        'name' => 'New Zealand',
        'full_name' => 'New Zealand',
        'tag' => 'Safe & Scenic',
        'desc' => 'World-class education in a peaceful, stunning landscape with great post-study work rights and a focus on hands-on learning.',
        'fact_1' => ['100%', 'QS Ranked Unis'],
        'fact_2' => ['3 Years', 'Work Rights'],
        'fact_3' => ['Safe', '#2 Global Peace'],
        'fact_4' => ['NZD 25k-40k', 'Annual Cost'],
        'benefits' => [
            'All universities are in the top 3% globally.',
            'Practical and research-based learning.',
            'Exceptional quality of life and nature.',
            'One of the easiest places to start a business.'
        ],
        'intakes' => [
            'February - Primary',
            'July - Secondary'
        ]
    ],
    'france' => [
        'name' => 'France',
        'full_name' => 'France',
        'tag' => 'Art & Innovation',
        'desc' => 'A leading global destination for business, fashion, and culinary arts, offering subsidized housing and top-tier business schools.',
        'fact_1' => ['2 Years', 'Stay Back'],
        'fact_2' => ['Affordable', 'Public Unis'],
        'fact_3' => ['Business', 'Top Schools'],
        'fact_4' => ['€10k-€20k', 'Annual Cost'],
        'benefits' => [
            'World-class business schools (HEC, INSEAD).',
            'Subsidized student housing (CAF).',
            'Rich culture and gateway to Europe.',
            '5-year alumni visa for Indian graduates.'
        ],
        'intakes' => [
            'September - Main',
            'February - Secondary'
        ]
    ],
    'italy' => [
        'name' => 'Italy',
        'full_name' => 'Italy',
        'tag' => 'Cradle of Design',
        'desc' => 'Study at the world\'s oldest universities in the land of art, fashion, and heritage with very low tuition fees.',
        'fact_1' => ['Low Tuition', 'Scholarships'],
        'fact_2' => ['Oldest Unis', 'Since 1088'],
        'fact_3' => ['Design', 'World Capital'],
        'fact_4' => ['€3k-€10k', 'Tuition Fees'],
        'benefits' => [
            'Extremely affordable public universities.',
            'Government scholarships for international students.',
            'Unmatched heritage and lifestyle.',
            'Leader in Fashion, Design & Automotive.'
        ],
        'intakes' => [
            'September - Main Intake'
        ]
    ],
    'sweden' => [
        'name' => 'Sweden',
        'full_name' => 'Sweden',
        'tag' => 'Innovation Leader',
        'desc' => 'Sweden is home to the Nobel Prize and some of the world\'s most innovative companies like IKEA, Spotify, and Volvo.',
        'fact_1' => ['1 Year', 'Stay Back'],
        'fact_2' => ['English', 'Taught Programs'],
        'fact_3' => ['Global', 'Innovation Hub'],
        'fact_4' => ['SEK 100k', 'Annual Cost'],
        'benefits' => [
            'World-class research environment.',
            'Focus on sustainability and equality.',
            'English is widely spoken.',
            'Innovation hub of Europe.'
        ],
        'intakes' => [
            'August - Main'
        ]
    ],
    'south-korea' => [
        'name' => 'South Korea',
        'full_name' => 'South Korea',
        'tag' => 'The K-Wave Hub',
        'desc' => 'Experience a unique blend of ancient tradition and futuristic technology in the land of Samsung, LG, and K-Pop.',
        'fact_1' => ['Top Tech', 'Samsung/LG'],
        'fact_2' => ['Hallyu', 'Cultural Hub'],
        'fact_3' => ['STEM', 'Advanced Research'],
        'fact_4' => ['KRW 5m-10m', 'Annual Cost'],
        'benefits' => [
            'Leading in Tech, Robotics & AI.',
            'Unique cultural experience.',
            'Affordable living compared to the US.',
            'Strong government scholarships (GKS).'
        ],
        'intakes' => [
            'March - Main',
            'September - Second'
        ]
    ],
    'uae' => [
        'name' => 'UAE',
        'full_name' => 'United Arab Emirates',
        'tag' => 'Middle East Hub',
        'desc' => 'Dubai offers a global education experience with campus branches of top world universities and tax-free work opportunities.',
        'fact_1' => ['Dubai', 'Global City'],
        'fact_2' => ['Tax Free', 'Earnings'],
        'fact_3' => ['UK/US', 'Branch Campus'],
        'fact_4' => ['AED 40k-80k', 'Annual Cost'],
        'benefits' => [
            'Proximity to India (3-4 hour flight).',
            'Global networking opportunities.',
            'Safe and luxury lifestyle.',
            'Growing business hub of the world.'
        ],
        'intakes' => [
            'September',
            'January'
        ]
    ],
    'netherlands' => [
        'name' => 'Netherlands',
        'full_name' => 'Netherlands',
        'tag' => 'English Hub of Europe',
        'desc' => 'The first non-English speaking country to offer courses taught in English with high quality research-based education.',
        'fact_1' => ['95%', 'English Speaking'],
        'fact_2' => ['1 Year', 'Orientation Year'],
        'fact_3' => ['Creative', 'Industry Hub'],
        'fact_4' => ['€10k-€20k', 'Annual Cost'],
        'benefits' => [
            'High quality education at affordable rates.',
            'Innovative teaching methods.',
            'Strategic location in the heart of Europe.',
            'Search year visa for graduates.'
        ],
        'intakes' => [
            'September',
            'February'
        ]
    ],
    'switzerland' => [
        'name' => 'Switzerland',
        'full_name' => 'Switzerland',
        'tag' => 'Hospitality Capital',
        'desc' => 'The global center for banking, research, and world-class hospitality education in the heart of the Alps.',
        'fact_1' => ['Top 10', 'Global Peace'],
        'fact_2' => ['Hospitality', 'World Best'],
        'fact_3' => ['Research', 'CERN Hub'],
        'fact_4' => ['CHF 20k-40k', 'Annual Cost'],
        'benefits' => [
            'Unmatched scenic beauty.',
            'Global hub for international organizations.',
            'Highest salaries in Europe.',
            'Excellence in Hotel & Luxury Management.'
        ],
        'intakes' => [
            'September',
            'February'
        ]
    ],
    'malaysia' => [
        'name' => 'Malaysia',
        'full_name' => 'Malaysia',
        'tag' => 'Value for Money',
        'desc' => 'Get a UK or Australian degree at a fraction of the cost through branch campuses in a vibrant multicultural environment.',
        'fact_1' => ['Budget', 'Friendly'],
        'fact_2' => ['Twinning', 'Programs'],
        'fact_3' => ['Diverse', 'Culture'],
        'fact_4' => ['MYR 20k', 'Annual Cost'],
        'benefits' => [
            'Affordable high-quality education.',
            'No IELTS for many programs.',
            'Excellent quality of life.',
            'Gateway to South East Asia.'
        ],
        'intakes' => [
            'Jan',
            'May',
            'Sep'
        ]
    ],
    'denmark' => [
        'name' => 'Denmark',
        'full_name' => 'Denmark',
        'tag' => 'Happiest Nation',
        'desc' => 'Focus on collaborative learning, innovation, and a high standard of living in the world\'s happiest country.',
        'fact_1' => ['Happiest', 'Country'],
        'fact_2' => ['6 Months', 'Job Search'],
        'fact_3' => ['Clean', 'Green Energy'],
        'fact_4' => ['€8k-€16k', 'Annual Cost'],
        'benefits' => [
            'Informal and friendly teaching style.',
            'Leader in renewable energy & design.',
            'Excellent work-life balance.',
            'Safe and egalitarian society.'
        ],
        'intakes' => [
            'September'
        ]
    ],
    'bulgaria' => [
        'name' => 'Bulgaria',
        'full_name' => 'Bulgaria',
        'tag' => 'Affordable Europe',
        'desc' => 'Low tuition fees and living costs with EU-recognized medical and tech degrees, ideal for budget-conscious students.',
        'fact_1' => ['Low Cost', 'Tuition'],
        'fact_2' => ['EU Degree', 'Recognized'],
        'fact_3' => ['Medicine', 'Popular'],
        'fact_4' => ['€4k-€8k', 'Annual Cost'],
        'benefits' => [
            'Extremely low living costs.',
            'Simple visa process.',
            'High standard of Medical education.',
            'Access to the entire EU job market.'
        ],
        'intakes' => [
            'October'
        ]
    ],
    'russia' => [
        'name' => 'Russia',
        'full_name' => 'Russia',
        'tag' => 'Engineering Legacy',
        'desc' => 'A destination with a strong legacy in medicine, space research, and heavy engineering at a very low cost.',
        'fact_1' => ['Low Fee', 'Medicine'],
        'fact_2' => ['Space', 'Research'],
        'fact_3' => ['Legacy', 'Oldest Unis'],
        'fact_4' => ['$3k-$6k', 'Annual Cost'],
        'benefits' => [
            'Affordable medical education.',
            'Highly subsidized by the government.',
            'World-class technical universities.',
            'Rich cultural and linguistic exposure.'
        ],
        'intakes' => [
            'September'
        ]
    ],
    'philippines' => [
        'name' => 'Philippines',
        'full_name' => 'Philippines',
        'tag' => 'Medical Gateway',
        'desc' => 'The largest exporter of nurses and doctors to the USA, offering US-pattern medical education in English.',
        'fact_1' => ['US Pattern', 'MBBS/MD'],
        'fact_2' => ['English', 'Medium'],
        'fact_3' => ['NMAT', 'Focused'],
        'fact_4' => ['$4k-$6k', 'Annual Cost'],
        'benefits' => [
            'American pattern of education.',
            'High success rate in FMGE/NEXT exams.',
            'Very affordable living expenses.',
            'Direct clinical exposure in local hospitals.'
        ],
        'intakes' => [
            'June',
            'November'
        ]
    ],
    'china' => [
        'name' => 'China',
        'full_name' => 'China',
        'tag' => 'Asian Tech Powerhouse',
        'desc' => 'Experience cutting-edge technology and a rapidly growing economy with affordable tuition and numerous scholarships for international students.',
        'fact_1' => ['Affordable', 'Tuition Fees'],
        'fact_2' => ['Top Ranked', 'Global Unis'],
        'fact_3' => ['English', 'Programs Available'],
        'fact_4' => ['$3k-$10k', 'Annual Cost'],
        'benefits' => [
            'Generous government and university scholarships.',
            'Rapidly advancing medical and engineering programs.',
            'Low cost of living compared to the West.',
            'Opportunity to learn Mandarin, a major global language.'
        ],
        'intakes' => [
            'September',
            'March'
        ]
    ],
    'japan' => [
        'name' => 'Japan',
        'full_name' => 'Japan',
        'tag' => 'Innovation & Tradition',
        'desc' => 'Study in one of the safest countries in the world, renowned for advanced technology, rich culture, and excellent employability.',
        'fact_1' => ['High Tech', 'Research Facilities'],
        'fact_2' => ['Extremely', 'Safe Country'],
        'fact_3' => ['28 Hours', 'Part-time Work'],
        'fact_4' => ['$4k-$8k', 'Annual Cost'],
        'benefits' => [
            'World-class education in technology and business.',
            'Extensive part-time job opportunities for international students.',
            'Strong job market for graduates.',
            'Unique blend of ultra-modern living and traditional culture.'
        ],
        'intakes' => [
            'April',
            'October'
        ]
    ],
    'russia' => [
        'name' => 'Russia',
        'full_name' => 'Russia',
        'tag' => 'Medical Education Hub',
        'desc' => 'A top destination for medical and engineering students, offering high-quality education at a fraction of the cost found in other countries.',
        'fact_1' => ['WHO & NMC', 'Approved MBBS'],
        'fact_2' => ['No Entrance', 'Exams Required'],
        'fact_3' => ['English', 'Medium Instruction'],
        'fact_4' => ['$3k-$6k', 'Annual Cost'],
        'benefits' => [
            'Highly subsidized education for international students.',
            'Globally recognized medical and technical degrees.',
            'No IELTS/TOEFL required for many programs.',
            'Rich cultural history and beautiful architecture.'
        ],
        'intakes' => [
            'September',
            'February'
        ]
    ],
    'spain' => [
        'name' => 'Spain',
        'full_name' => 'Spain',
        'tag' => 'Sunny Europe',
        'desc' => 'Enjoy an excellent Mediterranean lifestyle while studying at some of Europe’s oldest and most prestigious universities.',
        'fact_1' => ['Rich', 'Cultural Heritage'],
        'fact_2' => ['Low', 'Cost of Living'],
        'fact_3' => ['Schengen', 'Visa Access'],
        'fact_4' => ['€1.5k-€5k', 'Annual Cost'],
        'benefits' => [
            'Very affordable public university fees.',
            'Opportunity to learn Spanish, the 2nd most spoken native language.',
            'Great weather, food, and vibrant student life.',
            'Gateway to travel across the European Union.'
        ],
        'intakes' => [
            'September',
            'February'
        ]
    ],
    'uae' => [
        'name' => 'UAE',
        'full_name' => 'United Arab Emirates',
        'tag' => 'Global Crossroads',
        'desc' => 'Study in a rapidly growing international hub that offers modern campuses, tax-free income opportunities, and a cosmopolitan lifestyle.',
        'fact_1' => ['Tax-Free', 'Income'],
        'fact_2' => ['Global', 'University Campuses'],
        'fact_3' => ['Safe', 'Environment'],
        'fact_4' => ['$10k-$20k', 'Annual Cost'],
        'benefits' => [
            'Access to branch campuses of top UK, US, and Australian universities.',
            'Strategic location bridging East and West.',
            'Excellent post-study work prospects in a booming economy.',
            'Extremely safe and diverse multicultural society.'
        ],
        'intakes' => [
            'September',
            'January'
        ]
    ]
];

// Determine which country to show
if (!isset($country_slug)) {
    $country_slug = $_GET['c'] ?? 'usa';
}

// 1. Fetch from Database first
$db_country = null;
$has_advanced_modules = false;
try {
    $stmt = $pdo->prepare("SELECT * FROM countries WHERE slug = :slug AND is_active = 1");
    $stmt->execute(['slug' => $country_slug]);
    $db_country = $stmt->fetch();
    
    // If we have 2026 benchmarks loaded, enable premium layouts
    if ($db_country && !empty($db_country['living_cost_local'])) {
        $has_advanced_modules = true;
    }
} catch (PDOException $e) {
    $db_country = null;
}

// 1.5 Fetch dynamic sections
$dynamic_sections = [];
if ($db_country) {
    try {
        $stmt = $pdo->prepare("SELECT * FROM country_sections WHERE country_id = :cid ORDER BY sort_order ASC, id ASC");
        $stmt->execute(['cid' => $db_country['id']]);
        $dynamic_sections = $stmt->fetchAll();
    } catch (PDOException $e) {}
}

// 2. Fetch from static config as a fallback
$country = $all_countries[$country_slug] ?? $all_countries['usa'];

// If the country wasn't found in the hardcoded array, but we have it in the database, 
// build the $country structure dynamically so the page doesn't fall back to USA.
if (!isset($all_countries[$country_slug]) && $db_country) {
    $country = [
        'name' => $db_country['name'],
        'full_name' => $db_country['name'],
        'tag' => 'Study in ' . $db_country['name'],
        'desc' => $db_country['description'] ?: 'Explore top universities, affordable tuition fees and student visa guidance for studying in ' . $db_country['name'] . '.',
        'fact_1' => [!empty($db_country['study_options']) ? $db_country['study_options'] : '100+', 'Universities'],
        'fact_2' => [!empty($db_country['upcoming_intakes']) ? explode(',', $db_country['upcoming_intakes'])[0] : 'Multiple', 'Intakes'],
        'fact_3' => [!empty($db_country['stayback_bachelors']) ? $db_country['stayback_bachelors'] : '1 Year', 'Stay-Back'],
        'fact_4' => [!empty($db_country['living_cost_local']) ? explode(' ', $db_country['living_cost_local'])[0] : 'Varies', 'Living Cost'],
        'benefits' => explode(',', !empty($db_country['demand_careers']) ? $db_country['demand_careers'] : 'Quality Education,Global Recognition'),
        'intakes' => array_map('trim', explode(',', !empty($db_country['upcoming_intakes']) ? $db_country['upcoming_intakes'] : 'Fall,Spring'))
    ];
}

if (!isset($pageTitle)) {
    $pageTitle = 'Study in ' . ($db_country ? $db_country['name'] : $country['name']) . ' | Bluestone Overseas Consultants';
}
if (!isset($pageDesc)) {
    $pageDesc = $db_country ? $db_country['description'] : $country['desc'];
}
$isStudyAbroad = true;
$hideDefaultHero = true;
require_once 'includes/header.php';

$heroImgUrl = get_country_image_url($country_slug);
$countryName = $country['full_name'] ?? $country['name'];
?>

<main>
  
  <!-- CUSTOM COUNTRY HERO -->
  <section class="country-hero-custom" style="background-image: url('<?= htmlspecialchars($heroImgUrl) ?>');">
    <!-- Dark overlay to ensure text readability -->
    <div style="position: absolute; inset: 0; background: linear-gradient(to right, rgba(15, 23, 42, 0.9), rgba(15, 23, 42, 0.5));"></div>
    
    <div class="container animate-on-scroll" style="position: relative; z-index: 2; text-align: left; color: white; width: 100%;">
      <div style="max-width: 800px;">
        <span style="display: inline-block; padding: 0.5rem 1.25rem; background: rgba(255,255,255,0.15); backdrop-filter: blur(8px); border-radius: 50px; font-weight: 600; margin-bottom: 1.5rem; border: 1px solid rgba(255,255,255,0.3); text-transform: uppercase; letter-spacing: 0.1em; color: white;">Study In</span>
        <h1 style="font-size: clamp(3.5rem, 8vw, 5.5rem); font-weight: 800; margin-bottom: 1.5rem; line-height: 1.1; text-shadow: 0 10px 30px rgba(0,0,0,0.5);"><?= htmlspecialchars($countryName) ?></h1>
        <p class="country-hero-desc" style="font-size: 1.25rem; opacity: 0.9; line-height: 1.7; text-shadow: 0 4px 15px rgba(0,0,0,0.5); border-left: 4px solid var(--neon-blue); padding-left: 1.5rem;"><?= htmlspecialchars($pageDesc) ?></p>
      </div>
    </div>
    
    <!-- Decorative bottom curve matching the site background -->
    <div class="page-hero__curve">
      <svg viewBox="0 0 1440 100" fill="none" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="none">
        <path d="M0,100 C480,0 960,0 1440,100 L1440,100 L0,100 Z" fill="currentColor"/>
      </svg>
    </div>
  </section>

  <style>
  .country-hero-custom {
      position: relative;
      width: 100%;
      height: 55vh;
      min-height: 500px;
      display: flex;
      align-items: center;
      justify-content: center;
      background-size: cover;
      background-position: center;
      background-repeat: no-repeat;
      padding-top: 80px;
  }
  @media (max-width: 768px) {
      .country-hero-custom {
          height: auto;
          min-height: 400px;
          padding-top: 120px;
          padding-bottom: 60px;
      }
      .country-hero-custom .container {
          text-align: center !important;
      }
      .country-hero-custom h1 {
          font-size: 2.8rem !important;
      }
      .country-hero-desc {
          font-size: 1.05rem !important;
          border-left: none !important;
          padding-left: 0 !important;
      }
  }

  .country-fact-pill {
      display: flex;
      align-items: center;
      gap: 1.25rem;
      background: linear-gradient(rgba(255, 255, 255, 0.75), rgba(255, 255, 255, 0.9));
      backdrop-filter: blur(10px);
      -webkit-backdrop-filter: blur(10px);
      padding: 1.5rem;
      border-radius: 20px;
      box-shadow: 0 10px 30px rgba(0,0,0,0.04);
      border: 1px solid rgba(255,255,255,0.8);
      transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
  }
  .country-fact-pill:hover {
      transform: translateY(-5px) scale(1.02);
      box-shadow: 0 15px 35px rgba(14,165,233,0.15);
  }
  .cf-icon {
      width: 60px;
      height: 60px;
      border-radius: 16px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 1.75rem;
      color: white;
      flex-shrink: 0;
  }
  .cf-icon--blue { background: linear-gradient(135deg, #0ea5e9, #3b82f6); box-shadow: 0 8px 20px rgba(14,165,233,0.3); }
  .cf-icon--purple { background: linear-gradient(135deg, #8b5cf6, #d946ef); box-shadow: 0 8px 20px rgba(139,92,246,0.3); }
  .cf-icon--orange { background: linear-gradient(135deg, #f97316, #f59e0b); box-shadow: 0 8px 20px rgba(249,115,22,0.3); }
  .cf-icon--teal { background: linear-gradient(135deg, #14b8a6, #0d9488); box-shadow: 0 8px 20px rgba(20,184,166,0.3); }
  .cf-text-label { font-size: 0.85rem; color: #64748b; text-transform: uppercase; letter-spacing: 0.05em; font-weight: 600; margin-bottom: 0.25rem; }
  .cf-text-val { font-size: 1.35rem; font-weight: 800; color: #0f172a; line-height: 1.2; }
  
  /* --- STACKED CARD UI CSS --- */
  .uni-stack-wrapper {
      position: relative;
      width: 100%;
      max-width: 600px;
      margin: 0 auto;
      height: 520px;
      perspective: 1000px;
  }
  .uni-stacked-card {
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: white;
      border-radius: 20px;
      box-shadow: 0 10px 40px rgba(0,0,0,0.1);
      border: 1px solid #e2e8f0;
      display: flex;
      flex-direction: column;
      overflow: hidden;
      transition: transform 0.5s cubic-bezier(0.2, 0.8, 0.2, 1), opacity 0.5s ease;
      transform-origin: bottom center;
      cursor: pointer;
      opacity: 0;
      pointer-events: none;
      z-index: 0;
  }
  
  .uni-stacked-card.stack-active {
      transform: translateY(0) scale(1);
      opacity: 1;
      z-index: 10;
      pointer-events: auto;
  }
  .uni-stacked-card.stack-next-1 {
      transform: translateY(20px) scale(0.95);
      opacity: 0.8;
      z-index: 9;
  }
  .uni-stacked-card.stack-next-2 {
      transform: translateY(40px) scale(0.9);
      opacity: 0.5;
      z-index: 8;
  }
  .uni-stacked-card.stack-fly-out {
      transform: translateY(-100%) rotate(5deg) scale(1.05);
      opacity: 0;
      z-index: 11;
  }
  
  .uni-stack-controls {
      display: flex;
      justify-content: center;
      gap: 1rem;
      margin-top: 2rem;
  }
  .uni-stack-btn {
      width: 50px;
      height: 50px;
      border-radius: 50%;
      background: white;
      border: 1px solid #e2e8f0;
      box-shadow: 0 5px 15px rgba(0,0,0,0.05);
      display: flex;
      align-items: center;
      justify-content: center;
      color: var(--primary);
      font-size: 1.25rem;
      cursor: pointer;
      transition: all 0.3s ease;
  }
  .uni-stack-btn:hover {
      background: var(--primary);
      color: white;
      transform: scale(1.1);
  }

  @media (max-width: 576px) {
      .uni-stack-wrapper {
          height: 480px;
      }
      .uni-stacked-card h4 {
          font-size: 1.15rem !important;
          margin-bottom: 0.5rem !important;
      }
      .uni-card-img {
          height: 180px !important;
      }
      .uni-stacked-card > div:last-child {
          padding: 1.5rem !important;
      }
  }

  /* Responsive Section Cards */
  .country-card {
      background: #fff;
      border: 1px solid #e2e8f0;
      border-radius: 20px;
      padding: 2.5rem;
      position: relative;
      overflow: hidden;
  }
  .country-card-img {
      height: 250px;
      width: calc(100% + 5rem);
      margin: -2.5rem -2.5rem 2rem -2.5rem;
      background-size: cover;
      background-position: center;
      background-repeat: no-repeat;
  }
  .country-card--no-pad { padding: 0; }
  .country-card-inner-pad { padding: 2.5rem; }
  .country-card-lg-pad { padding: 3rem; }
  .cost-card { padding: 3rem 2rem; border-radius: 20px; }

  .scholarship-inner-card {
      background: linear-gradient(135deg, #f8fafc, #f1f5f9);
      border: 1px solid #e2e8f0;
      padding: 2rem;
      border-radius: 16px;
      position: relative;
      overflow: hidden;
  }
  .scholarship-inner-card-content {
      display: flex;
      align-items: flex-start;
      gap: 1.5rem;
  }

  @media (max-width: 768px) {
      .country-card { padding: 1.25rem; }
      .country-card-img {
          width: calc(100% + 2.5rem);
          margin: -1.25rem -1.25rem 1.25rem -1.25rem;
          height: 200px;
      }
      .country-card-inner-pad { padding: 1.25rem; }
      .country-card-lg-pad { padding: 1.25rem; }
      .cost-card { padding: 2rem 1rem; }
      .scholarship-inner-card { padding: 1.25rem; }
      .scholarship-inner-card-content { gap: 1rem; }
  }

  /* CTA Banner */
  .country-cta-banner {
      background: linear-gradient(135deg, var(--neon-blue), var(--neon-purple));
      padding: 4rem 2rem;
      border-radius: 24px;
      text-align: center;
      color: white;
      box-shadow: 0 20px 40px rgba(37,99,235,0.2);
  }
  .country-cta-title {
      font-size: 2.5rem;
      margin-bottom: 1rem;
      color: white;
  }
  @media (max-width: 768px) {
      .country-cta-banner {
          padding: 2.5rem 1.25rem;
          border-radius: 16px;
      }
      .country-cta-title {
          font-size: 1.85rem !important;
      }
  }
  </style>

  <!-- QUICK FACTS & ROI (BENTO STYLE) -->
  <section class="section" style="padding-top: 0; margin-top: -50px; position: relative; z-index: 10;">
    <div class="container">
      <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); gap: 1.5rem;">
        
        <div class="country-fact-pill animate-on-scroll">
            <div class="cf-icon cf-icon--blue"><i class="fa-solid fa-award"></i></div>
            <div>
                <div class="cf-text-label">Top Universities</div>
                <div class="cf-text-val"><?= $country['fact_1'][0] ?? 'World Class' ?></div>
            </div>
        </div>

        <div class="country-fact-pill animate-on-scroll delay-1">
            <div class="cf-icon cf-icon--purple"><i class="fa-solid fa-calendar-check"></i></div>
            <div>
                <div class="cf-text-label">Intakes</div>
                <div class="cf-text-val"><?= $country['fact_2'][0] ?? 'Multiple Intakes' ?></div>
            </div>
        </div>

        <div class="country-fact-pill animate-on-scroll delay-2">
            <div class="cf-icon cf-icon--orange"><i class="fa-solid fa-briefcase"></i></div>
            <div>
                <div class="cf-text-label">Stay-back Rights</div>
                <div class="cf-text-val"><?= $country['fact_3'][0] ?? 'Post-Study Work' ?></div>
            </div>
        </div>

        <div class="country-fact-pill animate-on-scroll delay-3">
            <div class="cf-icon cf-icon--teal"><i class="fa-solid fa-wallet"></i></div>
            <div>
                <div class="cf-text-label">Living Cost</div>
                <div class="cf-text-val"><?= $country['fact_4'][0] ?? 'Affordable' ?></div>
            </div>
        </div>

      </div>
    </div>
  </section>
  <!-- 2-COLUMN SIDEBAR LAYOUT -->
  <section class="section" style="padding-top: 2rem;">
    <div class="container country-sidebar-layout">
      
      <!-- LEFT SIDEBAR -->
      <aside class="country-sidebar animate-on-scroll">
        <ul>
          <li><a href="#overview">Study in <?= $countryName ?> Overview</a></li>
          <li><a href="#universities">Universities in <?= $countryName ?></a></li>
          <li><a href="#courses">Courses to Study in <?= $countryName ?></a></li>
          <li><a href="#cost">Cost of Studying & Living</a></li>
          <li><a href="#scholarships">Scholarships in <?= $countryName ?></a></li>
          <li><a href="#intakes">Intakes in <?= $countryName ?></a></li>
          <li><a href="#eligibility">Eligibility Criteria</a></li>
          <li><a href="#exams">Exams Required</a></li>
          <li><a href="#visa">Study in <?= $countryName ?> Visa</a></li>
          <li><a href="#jobs">Jobs in <?= $countryName ?></a></li>
          <li><a href="#admits">Top Admits in <?= $countryName ?></a></li>
          <li><a href="#why-kc">Why Choose Bluestone Overseas</a></li>
        </ul>
      </aside>

      <!-- RIGHT CONTENT -->
      <div class="country-content">

                <!-- 1. OVERVIEW -->
        <div id="overview" class="country-content-section animate-on-scroll">
          <h2>Study in <?= $countryName ?> Overview</h2>
          <div class="country-card">
            <?php if (!empty($db_country['overview_image'])): ?>
                <div class="country-card-img" style="background-image: url('<?= htmlspecialchars($db_country['overview_image']) ?>');"></div>
            <?php endif; ?>
            <p style="color:var(--gray); line-height:1.8; margin-bottom:1.5rem">Our students choose <?= $country['name'] ?> for its unique blend of academic excellence and lifestyle benefits. Here are the top reasons to consider this destination:</p>
            <div class="grid grid--2 gap--2" style="margin-top: 1.5rem;">
              <?php foreach($country['benefits'] as $index => $benefit): 
                  // Alternate colors for a more vibrant look
                  $colors = [
                      ['bg' => '#eff6ff', 'icon' => '#3b82f6', 'border' => '#bfdbfe'],
                      ['bg' => '#f0fdf4', 'icon' => '#22c55e', 'border' => '#bbf7d0'],
                      ['bg' => '#fdf4ff', 'icon' => '#d946ef', 'border' => '#fbcfe8'],
                      ['bg' => '#fffbeb', 'icon' => '#f59e0b', 'border' => '#fde68a']
                  ];
                  $color = $colors[$index % count($colors)];
              ?>
              <div class="benefit-card hover-lift" style="display: flex; align-items: center; gap: 1.25rem; padding: 1.5rem; background: <?= $color['bg'] ?>; border: 1px solid <?= $color['border'] ?>; border-radius: 16px; transition: transform 0.3s, box-shadow 0.3s;">
                <div style="background: white; width: 40px; height: 40px; border-radius: 12px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; box-shadow: 0 4px 10px rgba(0,0,0,0.05); color: <?= $color['icon'] ?>; font-size: 1.2rem;">
                    <i class="fa-solid fa-check"></i>
                </div>
                <div style="font-size: 1.05rem; color: #334155; font-weight: 500; line-height: 1.4;">
                    <?= $benefit ?>
                </div>
              </div>
              <?php endforeach; ?>
            </div>
          </div>
        </div>

        <!-- 2. UNIVERSITIES -->
        <div id="universities" class="country-content-section animate-on-scroll">
          <div style="background: #579df9; border-radius: 30px; padding: 4rem 2rem; position: relative; overflow: hidden; box-shadow: 0 20px 40px rgba(24, 119, 242, 0.25);">
            <!-- Decorative faint background shapes -->
            <div style="position: absolute; top: -50px; right: -50px; width: 300px; height: 300px; background: rgba(255,255,255,0.05); border-radius: 50%; pointer-events: none;"></div>
            <div style="position: absolute; bottom: -100px; left: -10%; width: 400px; height: 400px; background: rgba(255,255,255,0.05); border-radius: 50%; pointer-events: none;"></div>
            
            <div style="position: relative; z-index: 1;">
              <h2 style="color: white; margin-bottom: 2.5rem; text-align: center;">Universities in <?= $countryName ?></h2>
          <?php
          // Fetch universities for this country
          $unis_db = [];
          if ($db_country) {
              try {
                  $stmtUnis = $pdo->prepare("SELECT * FROM universities WHERE country_id = :cid AND is_active = 1 ORDER BY name ASC");
                  $stmtUnis->execute(['cid' => $db_country['id']]);
                  $unis_db = $stmtUnis->fetchAll();
              } catch (PDOException $e) {
                  $unis_db = [];
              }
          }
          
          if (empty($unis_db)): ?>
              <div style="text-align:center; padding: 4rem 2rem; background:#f8fafc; border-radius:20px; border:1px solid #e2e8f0;">
                  <i class="fa-solid fa-school" style="font-size:3rem; color:var(--neon-blue); margin-bottom:1rem;"></i>
                  <h4>Global Institutional Network</h4>
                  <p style="color:var(--gray); max-width:500px; margin: 0.5rem auto 1.5rem;">We hold direct tie-ups with premier universities in this region. Talk with an advisor to review all available options.</p>
                  <a href="consultation.php" class="btn btn--primary">Speak to an Expert</a>
              </div>
          <?php else: ?>
              <div class="uni-stack-wrapper" id="uniStack">
                  <?php foreach ($unis_db as $index => $uni): 
                      $imgUrl = !empty($uni['image_url']) ? htmlspecialchars($uni['image_url']) : 'https://images.unsplash.com/photo-1541339907198-e08756dedf3f?q=80&w=600&auto=format&fit=crop';
                  ?>
                    <div class="uni-stacked-card <?= $index === 0 ? 'stack-active' : ($index === 1 ? 'stack-next-1' : ($index === 2 ? 'stack-next-2' : '')) ?>" data-index="<?= $index ?>" onclick="nextUniCard()">
                      <div class="uni-card-img" style="height: 250px; width: 100%; position: relative;">
                        <img src="<?= $imgUrl ?>" alt="<?= htmlspecialchars($uni['name']) ?>" style="width: 100%; height: 100%; object-fit: cover;">
                        <div style="position: absolute; inset: 0; background: linear-gradient(to top, rgba(15,23,42,0.4), transparent);"></div>
                        <div style="position: absolute; top: 1rem; right: 1rem; background: rgba(255,255,255,0.95); padding: 0.35rem 0.85rem; border-radius: 50px; font-size: 0.75rem; font-weight: 700; color: var(--neon-blue); box-shadow: 0 4px 10px rgba(0,0,0,0.1); display: flex; align-items: center; gap: 0.4rem;">
                            <i class="fa-solid fa-building-columns"></i> UNIVERSITY
                        </div>
                      </div>
                      <div style="padding: 2rem; display: flex; flex-direction: column; flex-grow: 1;">
                        <h4 style="font-size: 1.35rem; margin-bottom: 1rem; color: #0f172a; line-height: 1.4; flex-grow: 1;"><?= htmlspecialchars($uni['name']) ?></h4>
                        <div style="display: flex; flex-direction: column; gap: 0.8rem; margin-bottom: 1.5rem; font-size: 1rem; color: #475569;">
                          <?php if (!empty($uni['qs_ranking'])): ?>
                            <div style="display: flex; align-items: center; gap: 0.8rem;">
                              <i class="fa-solid fa-star" style="color: #f59e0b; width: 18px; text-align: center;"></i> <strong>QS Rank:</strong> <?= htmlspecialchars($uni['qs_ranking']) ?>
                            </div>
                          <?php else: ?>
                            <div style="display: flex; align-items: center; gap: 0.8rem;">
                              <i class="fa-solid fa-award" style="color: #3b82f6; width: 18px; text-align: center;"></i> <strong>Ranked:</strong> Top Globally
                            </div>
                          <?php endif; ?>
                          <div style="display: flex; align-items: center; gap: 0.8rem;">
                            <i class="fa-solid fa-location-dot" style="color: #ef4444; width: 18px; text-align: center;"></i> <strong>Location:</strong> <?= htmlspecialchars($country['name']) ?>
                          </div>
                        </div>
                        <a href="enquiry.php?university=<?= urlencode($uni['name']) ?>" class="btn btn--primary btn--block" style="border-radius: 12px; padding: 1rem; font-weight: 600;" onclick="event.stopPropagation();">Apply Now</a>
                      </div>
                    </div>
                  <?php endforeach; ?>
              </div>
              
              <?php if (count($unis_db) > 1): ?>
              <div class="uni-stack-controls">
                  <button class="uni-stack-btn" onclick="prevUniCard()"><i class="fa-solid fa-arrow-left"></i></button>
                  <button class="uni-stack-btn" onclick="nextUniCard()"><i class="fa-solid fa-arrow-right"></i></button>
              </div>
              <?php endif; ?>

              <script>
                  let uniCards = Array.from(document.querySelectorAll('.uni-stacked-card'));
                  let isAnimating = false;

                  function updateUniStack() {
                      uniCards.forEach((card, i) => {
                          card.className = 'uni-stacked-card'; 
                          if (i === 0) card.classList.add('stack-active');
                          else if (i === 1) card.classList.add('stack-next-1');
                          else if (i === 2) card.classList.add('stack-next-2');
                      });
                  }

                  function nextUniCard() {
                      if (isAnimating || uniCards.length <= 1) return;
                      isAnimating = true;
                      
                      const topCard = uniCards[0];
                      topCard.classList.add('stack-fly-out');
                      
                      setTimeout(() => {
                          topCard.classList.remove('stack-fly-out');
                          uniCards.push(uniCards.shift()); 
                          updateUniStack();
                          isAnimating = false;
                      }, 400);
                  }

                  function prevUniCard() {
                      if (isAnimating || uniCards.length <= 1) return;
                      isAnimating = true;
                      
                      const lastCard = uniCards.pop(); 
                      lastCard.classList.add('stack-fly-out'); 
                      uniCards.unshift(lastCard); 
                      
                      setTimeout(() => {
                          lastCard.classList.remove('stack-fly-out');
                          updateUniStack();
                          isAnimating = false;
                      }, 50);
                  }
              </script>
          <?php endif; ?>
            </div>
          </div>
        </div>

        <!-- 3. COURSES -->
        <div id="courses" class="country-content-section animate-on-scroll">
          <h2>Courses to Study in <?= $countryName ?></h2>
          <div class="country-card" style="--theme-grad: linear-gradient(135deg, #14b8a6, #0d9488); text-align: left;">
            <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 2rem;">
               <div class="icon-colorful icon-colorful--teal"><i class="fa-solid fa-graduation-cap"></i></div>
               <h3 style="font-size: 1.5rem; color: #0f172a; margin-bottom: 0;">Popular Programs</h3>
            </div>
            
            <?php
            $courses_db = [];
            if ($db_country) {
                try {
                    $stmtCourses = $pdo->prepare("
                        SELECT DISTINCT c.name 
                        FROM courses c 
                        JOIN universities u ON c.university_id = u.id 
                        WHERE u.country_id = :cid AND c.is_active = 1 
                        ORDER BY c.name ASC 
                        LIMIT 30
                    ");
                    $stmtCourses->execute(['cid' => $db_country['id']]);
                    $courses_db = $stmtCourses->fetchAll();
                } catch (PDOException $e) {
                    $courses_db = [];
                }
            }
            ?>
            
            <div style="display: flex; flex-wrap: wrap; gap: 0.75rem;">
               <?php if (empty($courses_db)): ?>
                   <span class="career-pill" style="background: #f8fafc; border: 1px solid #e2e8f0; padding: 0.5rem 1rem; border-radius: 30px; font-size: 0.9rem; font-weight: 600; color: #334155;"><i class="fa-solid fa-microchip" style="color:#14b8a6; margin-right: 0.5rem;"></i> STEM & IT</span>
                   <span class="career-pill" style="background: #f8fafc; border: 1px solid #e2e8f0; padding: 0.5rem 1rem; border-radius: 30px; font-size: 0.9rem; font-weight: 600; color: #334155;"><i class="fa-solid fa-chart-line" style="color:#14b8a6; margin-right: 0.5rem;"></i> Business & MBA</span>
                   <span class="career-pill" style="background: #f8fafc; border: 1px solid #e2e8f0; padding: 0.5rem 1rem; border-radius: 30px; font-size: 0.9rem; font-weight: 600; color: #334155;"><i class="fa-solid fa-stethoscope" style="color:#14b8a6; margin-right: 0.5rem;"></i> Healthcare & Medicine</span>
                   <span class="career-pill" style="background: #f8fafc; border: 1px solid #e2e8f0; padding: 0.5rem 1rem; border-radius: 30px; font-size: 0.9rem; font-weight: 600; color: #334155;"><i class="fa-solid fa-pen-ruler" style="color:#14b8a6; margin-right: 0.5rem;"></i> Engineering & Design</span>
               <?php else: ?>
                   <?php foreach($courses_db as $course): ?>
                   <span class="career-pill hover-lift" style="background: #f8fafc; border: 1px solid #e2e8f0; padding: 0.5rem 1rem; border-radius: 30px; font-size: 0.9rem; font-weight: 600; color: #334155; display: inline-block;">
                       <i class="fa-solid fa-book-open" style="color:#14b8a6; margin-right: 0.5rem;"></i> <?= htmlspecialchars($course['name']) ?>
                   </span>
                   <?php endforeach; ?>
               <?php endif; ?>
            </div>
          </div>
        </div>

        <!-- 4. COST OF STUDYING & LIVING -->
        <div id="cost" class="country-content-section animate-on-scroll">
          <h2>Cost of Studying & Living</h2>
          <div style="border-radius: 20px; overflow: hidden; margin-bottom: 2rem;"><img src="https://images.unsplash.com/photo-1554224155-6726b3ff858f?q=80&w=800&auto=format&fit=crop" alt="Cost of Studying" style="width: 100%; height: auto; max-height: 400px; object-fit: cover;"></div>
          <div class="cost-card" style="background: #6d28d9; color: white; text-align: center; box-shadow: 0 20px 40px rgba(109, 40, 217, 0.2);">
            <h3 style="font-size: 2rem; font-weight: 800; margin-bottom: 1.5rem;">Cost of Living & Studying in <?= $countryName ?></h3>
            <p style="font-size: 1.1rem; line-height: 1.6; opacity: 0.9; margin-bottom: 2rem; max-width: 800px; margin-inline: auto;">
              Careful financial planning is essential. <?= $country['name'] ?> offers a range of options depending on your location, university, and lifestyle choices.
            </p>
            
            <div class="grid grid--2 gap--2" style="text-align: left;">
              <div style="background: white; border-radius: 12px; padding: 1.5rem; color: #0f172a;">
                <div style="color: #6d28d9; font-size: 2rem; margin-bottom: 1rem;"><i class="fa-solid fa-wallet"></i></div>
                <h4 style="margin-bottom: 0.5rem;">Annual Living Cost</h4>
                <p style="font-size: 1.25rem; font-weight: 800; color: #0f172a; margin: 0;"><?= !empty($db_country['living_cost_local']) ? htmlspecialchars($db_country['living_cost_local']) : 'Check with Advisor' ?></p>
                <p style="font-size: 0.9rem; color: #64748b; margin: 0.25rem 0 0;"><?= !empty($db_country['living_cost_inr']) ? htmlspecialchars($db_country['living_cost_inr']) : '' ?></p>
              </div>
              <div style="background: white; border-radius: 12px; padding: 1.5rem; color: #0f172a;">
                <div style="color: #6d28d9; font-size: 2rem; margin-bottom: 1rem;"><i class="fa-solid fa-money-bill-wave"></i></div>
                <h4 style="margin-bottom: 0.5rem;">Weekly Budget</h4>
                <p style="font-size: 1.25rem; font-weight: 800; color: #0f172a; margin: 0;"><?= !empty($db_country['weekly_budget_local']) ? htmlspecialchars($db_country['weekly_budget_local']) : 'Check with Advisor' ?></p>
                <p style="font-size: 0.9rem; color: #64748b; margin: 0.25rem 0 0;"><?= !empty($db_country['weekly_budget_inr']) ? htmlspecialchars($db_country['weekly_budget_inr']) : '' ?></p>
              </div>
            </div>
          </div>
        </div>

        <!-- 5. SCHOLARSHIPS -->
        <div id="scholarships" class="country-content-section animate-on-scroll">
          <h2>Scholarships in <?= $countryName ?></h2>
          <div class="country-card country-card-lg-pad" style="border-radius: 24px; box-shadow: 0 10px 30px rgba(0,0,0,0.03);">
              
              <?php if (!empty($db_country['scholarships_image'])): ?>
                  <div style="border-radius: 16px; overflow: hidden; margin-bottom: 2.5rem; box-shadow: 0 10px 30px rgba(0,0,0,0.08); position: relative; height: 300px;">
                      <img src="<?= htmlspecialchars($db_country['scholarships_image']) ?>" alt="Scholarships in <?= $countryName ?>" style="width: 100%; height: 100%; object-fit: cover;">
                      <div style="position: absolute; inset: 0; background: linear-gradient(to top, rgba(15,23,42,0.6) 0%, transparent 60%);"></div>
                      <div style="position: absolute; bottom: 2rem; left: 2rem; display: flex; align-items: center; gap: 1rem;">
                          <div style="background: var(--primary, #ff5c8d); color: white; width: 50px; height: 50px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; box-shadow: 0 4px 15px rgba(255, 92, 141, 0.4);">
                              <i class="fa-solid fa-graduation-cap"></i>
                          </div>
                          <div>
                              <h3 style="color: white; margin: 0; font-size: 1.5rem; font-weight: 800; text-shadow: 0 2px 10px rgba(0,0,0,0.3);">Financial Aid</h3>
                              <p style="color: rgba(255,255,255,0.9); margin: 0; font-size: 0.95rem; text-shadow: 0 1px 5px rgba(0,0,0,0.3);">Fund your dream education.</p>
                          </div>
                      </div>
                  </div>
              <?php endif; ?>
              
              <div class="grid grid--2 gap--2">
                  <!-- Card 1 -->
                  <div class="hover-lift scholarship-inner-card">
                      <div style="position: absolute; top: 0; left: 0; width: 4px; height: 100%; background: var(--accent, #0ea5e9);"></div>
                      <div class="scholarship-inner-card-content">
                          <div style="width: 48px; height: 48px; border-radius: 12px; background: white; color: var(--accent, #0ea5e9); display: flex; align-items: center; justify-content: center; font-size: 1.5rem; box-shadow: 0 4px 10px rgba(14, 165, 233, 0.15); flex-shrink: 0;">
                              <i class="fa-solid fa-building-columns"></i>
                          </div>
                          <div>
                              <h4 style="color: var(--dark, #0f172a); margin-bottom: 0.5rem; font-size: 1.25rem; font-weight: 700;">University Specific</h4>
                              <p style="font-size: 0.95rem; color: var(--gray, #64748b); margin: 0; line-height: 1.6;">Merit-based waivers offered directly by institutions upon admission based on your academic profile.</p>
                          </div>
                      </div>
                  </div>
                  
                  <!-- Card 2 -->
                  <div class="hover-lift scholarship-inner-card">
                      <div style="position: absolute; top: 0; left: 0; width: 4px; height: 100%; background: var(--primary, #ff5c8d);"></div>
                      <div class="scholarship-inner-card-content">
                          <div style="width: 48px; height: 48px; border-radius: 12px; background: white; color: var(--primary, #ff5c8d); display: flex; align-items: center; justify-content: center; font-size: 1.5rem; box-shadow: 0 4px 10px rgba(255, 92, 141, 0.15); flex-shrink: 0;">
                              <i class="fa-solid fa-landmark-flag"></i>
                          </div>
                          <div>
                              <h4 style="color: var(--dark, #0f172a); margin-bottom: 0.5rem; font-size: 1.25rem; font-weight: 700;">Government Funded</h4>
                              <p style="font-size: 0.95rem; color: var(--gray, #64748b); margin: 0; line-height: 1.6;">Prestigious national scholarships designed for outstanding international students globally.</p>
                          </div>
                      </div>
                  </div>
              </div>
              
          </div>
        </div>

        <!-- 6. INTAKES -->
        <div id="intakes" class="country-content-section animate-on-scroll">
          <h2>Intakes in <?= $countryName ?></h2>
       
              <div class="country-card-lg-pad">
                  <div class="grid grid--2 gap--2">
                    <?php 
                    $intakeList = !empty($db_country['upcoming_intakes']) ? explode("\n", trim($db_country['upcoming_intakes'])) : $country['intakes'];
                    $gradients = [
                        'linear-gradient(135deg, #3b82f6, #2563eb)',
                        'linear-gradient(135deg, #10b981, #059669)',
                        'linear-gradient(135deg, #f59e0b, #d97706)',
                        'linear-gradient(135deg, #8b5cf6, #7c3aed)'
                    ];
                    $icons = ['fa-calendar-check', 'fa-calendar-days', 'fa-calendar-plus', 'fa-clock'];
                    
                    foreach($intakeList as $index => $intake): 
                        if(empty(trim($intake))) continue;
                        $grad = $gradients[$index % count($gradients)];
                        $icon = $icons[$index % count($icons)];
                        
                        // Determine season image
                        $intakeLower = strtolower(trim($intake));
                        $imageUrl = 'https://images.unsplash.com/photo-1523050854058-8df90110c9f1?q=80&w=600&auto=format&fit=crop'; // default campus
                        
                        if (strpos($intakeLower, 'spring') !== false || strpos($intakeLower, 'mar') !== false || strpos($intakeLower, 'apr') !== false || strpos($intakeLower, 'may') !== false) {
                            $imageUrl = 'https://images.unsplash.com/photo-1490750967868-88aa4486c946?q=80&w=600&auto=format&fit=crop'; // spring flowers
                        } elseif (strpos($intakeLower, 'summer') !== false || strpos($intakeLower, 'jun') !== false || strpos($intakeLower, 'jul') !== false || strpos($intakeLower, 'aug') !== false) {
                            $imageUrl = 'https://images.unsplash.com/photo-1507525428034-b723cf961d3e?q=80&w=600&auto=format&fit=crop'; // summer beach
                        } elseif (strpos($intakeLower, 'fall') !== false || strpos($intakeLower, 'autumn') !== false || strpos($intakeLower, 'sep') !== false || strpos($intakeLower, 'oct') !== false || strpos($intakeLower, 'nov') !== false) {
                            $imageUrl = 'https://images.unsplash.com/photo-1477414348463-c0eb7f1359b6?q=80&w=600&auto=format&fit=crop'; // fall leaves
                        } elseif (strpos($intakeLower, 'winter') !== false || strpos($intakeLower, 'jan') !== false || strpos($intakeLower, 'feb') !== false || strpos($intakeLower, 'dec') !== false) {
                            $imageUrl = 'https://images.unsplash.com/photo-1478265409131-1f65c88f965c?q=80&w=600&auto=format&fit=crop'; // winter snow
                        }
                    ?>
                    <div class="hover-lift" style="border-radius: 16px; overflow: hidden; box-shadow: 0 10px 20px rgba(0,0,0,0.05); border: 1px solid #e2e8f0; background: white; display: flex; flex-direction: column;">
                        <div style="height: 140px; background: url('<?= $imageUrl ?>') no-repeat center center/cover; position: relative;">
                           <div style="position: absolute; inset: 0; background: linear-gradient(to top, rgba(0,0,0,0.7) 0%, transparent 70%);"></div>
                           <div style="position: absolute; bottom: 12px; left: 15px; color: white; display: flex; align-items: center; gap: 0.5rem; font-size: 0.85rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em;">
                               <i class="fa-regular <?= $icon ?>" style="color: #60a5fa;"></i> Upcoming Intake
                           </div>
                        </div>
                        <div style="padding: 1.5rem; background: <?= $grad ?>; color: white; flex-grow: 1; display: flex; align-items: center; justify-content: space-between; position: relative; overflow: hidden;">
                            <span style="font-size: 1.35rem; font-weight: 800; position: relative; z-index: 2;"><?= htmlspecialchars(trim($intake)) ?></span>
                            <!-- decorative element -->
                            <div style="position: absolute; right: -10px; bottom: -20px; font-size: 5rem; opacity: 0.1; z-index: 1;"><i class="fa-regular <?= $icon ?>"></i></div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                  </div>
              </div>
          </div>
        </div>

        <!-- 7. ELIGIBILITY CRITERIA -->
        <div id="eligibility" class="country-content-section animate-on-scroll">
          <h2>Eligibility Criteria</h2>
          <div class="country-card country-card--no-pad">
            <div style="height: 250px; width: 100%; background: url('https://images.unsplash.com/photo-1434030216411-0b793f4b4173?q=80&w=800&auto=format&fit=crop') no-repeat center top/cover;"></div>
            <div class="country-card-inner-pad">
                <ul style="list-style: none; padding: 0; display: flex; flex-direction: column; gap: 1rem; margin: 0;">
                   <li style="display: flex; align-items: center; justify-content: space-between; border-bottom: 1px dashed #e2e8f0; padding-bottom: 0.75rem;">
                     <span style="font-weight: 600; color: #334155;">Academic Score (Bachelors)</span>
                     <span style="color: #64748b; font-size: 0.95rem;">Min. 60-70% in 12th Standard</span>
                   </li>
                   <li style="display: flex; align-items: center; justify-content: space-between; border-bottom: 1px dashed #e2e8f0; padding-bottom: 0.75rem;">
                     <span style="font-weight: 600; color: #334155;">Academic Score (Masters)</span>
                     <span style="color: #64748b; font-size: 0.95rem;">Min. 60% in Bachelor's Degree</span>
                   </li>
                   <li style="display: flex; align-items: center; justify-content: space-between; padding-bottom: 0.25rem;">
                     <span style="font-weight: 600; color: #334155;">Backlogs</span>
                     <span style="color: #64748b; font-size: 0.95rem;">Varies by institution (Usually < 5)</span>
                   </li>
                </ul>
            </div>
          </div>
        </div>

        <!-- 8. EXAMS REQUIRED -->
        <div id="exams" class="country-content-section animate-on-scroll">
          <h2>Exams Required</h2>
          <div style="border-radius: 20px; overflow: hidden; margin-bottom: 2rem;"><img src="https://images.unsplash.com/photo-1606326608606-aa0b62935f2b?q=80&w=800&auto=format&fit=crop" alt="Exams Required" style="width: 100%; height: auto; max-height: 400px; object-fit: cover;"></div>
          <div class="grid grid--2 gap--2">
             <div style="background: #fff; padding: 1.5rem; border-radius: 12px; border: 1px solid #e2e8f0; text-align: center;">
                <h4 style="color: #0f172a; margin-bottom: 1rem;">English Proficiency</h4>
                <div style="display: flex; justify-content: center; gap: 0.5rem; flex-wrap: wrap;">
                   <span style="background: #f1f5f9; padding: 0.5rem 1rem; border-radius: 20px; font-size: 0.9rem; font-weight: 600; color: #475569;">IELTS</span>
                   <span style="background: #f1f5f9; padding: 0.5rem 1rem; border-radius: 20px; font-size: 0.9rem; font-weight: 600; color: #475569;">TOEFL</span>
                   <span style="background: #f1f5f9; padding: 0.5rem 1rem; border-radius: 20px; font-size: 0.9rem; font-weight: 600; color: #475569;">PTE</span>
                </div>
             </div>
             <div style="background: #fff; padding: 1.5rem; border-radius: 12px; border: 1px solid #e2e8f0; text-align: center;">
                <h4 style="color: #0f172a; margin-bottom: 1rem;">Standardized Tests</h4>
                <div style="display: flex; justify-content: center; gap: 0.5rem; flex-wrap: wrap;">
                   <span style="background: #f1f5f9; padding: 0.5rem 1rem; border-radius: 20px; font-size: 0.9rem; font-weight: 600; color: #475569;">GRE</span>
                   <span style="background: #f1f5f9; padding: 0.5rem 1rem; border-radius: 20px; font-size: 0.9rem; font-weight: 600; color: #475569;">GMAT</span>
                   <span style="background: #f1f5f9; padding: 0.5rem 1rem; border-radius: 20px; font-size: 0.9rem; font-weight: 600; color: #475569;">SAT</span>
                </div>
             </div>
          </div>
        </div>

        <!-- 9. VISA -->
        <div id="visa" class="country-content-section animate-on-scroll">
          <h2>Study in <?= $countryName ?> Visa</h2>
          <div class="country-card country-card--no-pad">
             <div style="height: 300px; width: 100%; background: url('https://images.unsplash.com/photo-1555848962-6e79363ec58f?q=80&w=800&auto=format&fit=crop') no-repeat center center/cover;"></div>
             <div class="country-card-inner-pad">
                 <p style="color:var(--gray); line-height:1.8; margin-bottom:1.5rem">Obtaining a student visa is a crucial step. Our visa experts provide end-to-end guidance to ensure a smooth application process.</p>
                 
                 <?php if (!empty($db_country['visa_fee_local'])): ?>
                 <div style="background: #eff6ff; border: 1px solid #bfdbfe; padding: 1rem 1.5rem; border-radius: 12px; margin-bottom: 2rem; display: flex; align-items: center; gap: 1rem;">
                     <i class="fa-solid fa-passport" style="font-size: 1.5rem; color: #3b82f6;"></i>
                     <div>
                         <span style="display: block; font-size: 0.85rem; color: #1e40af; font-weight: 600; text-transform: uppercase;">Visa Application Fee</span>
                         <strong style="color: #1e3a8a; font-size: 1.1rem;"><?= htmlspecialchars($db_country['visa_fee_local']) ?></strong>
                     </div>
                 </div>
                 <?php endif; ?>
                 
                 <a href="visa.php" class="btn btn--outline" style="border-color: #3b82f6; color: #3b82f6;">View Visa Guide <i class="fa-solid fa-arrow-right"></i></a>
             </div>
          </div>
        </div>

        <!-- 10. JOBS -->
        <div id="jobs" class="country-content-section animate-on-scroll">
          <h2>Jobs & Career in <?= $countryName ?></h2>
          <div style="border-radius: 20px; overflow: hidden; margin-bottom: 2rem;"><img src="https://images.unsplash.com/photo-1521737604893-d14cc237f11d?q=80&w=800&auto=format&fit=crop" alt="Jobs and Career" style="width: 100%; height: auto; max-height: 400px; object-fit: cover;"></div>
          <div class="country-card">
             <div class="grid grid--2 gap--2">
               <div style="display: flex; gap: 1rem; align-items: flex-start;">
                 <i class="fa-solid fa-plane-departure" style="color: #14b8a6; font-size: 1.5rem; margin-top: 0.25rem;"></i>
                 <div>
                   <h4 style="margin-bottom: 0.25rem;">Post-Study Work</h4>
                   <p style="font-size: 0.9rem; color: #64748b;">
                       <?= !empty($db_country['stayback_bachelors']) ? htmlspecialchars($db_country['stayback_bachelors']) : 'Generous stay back opportunities available.' ?>
                   </p>
                 </div>
               </div>
               <div style="display: flex; gap: 1rem; align-items: flex-start;">
                 <i class="fa-solid fa-briefcase" style="color: #3b82f6; font-size: 1.5rem; margin-top: 0.25rem;"></i>
                 <div>
                   <h4 style="margin-bottom: 0.25rem;">Earnings Potential</h4>
                   <p style="font-size: 0.9rem; color: #64748b;">
                       <?= !empty($db_country['earnings_potential_local']) ? htmlspecialchars($db_country['earnings_potential_local']) : 'Competitive salaries for graduates.' ?>
                   </p>
                 </div>
               </div>
             </div>
             
             <?php if (!empty($db_country['demand_careers'])): ?>
             <div style="margin-top: 2rem; padding-top: 2rem; border-top: 1px solid #e2e8f0;">
                 <h4 style="margin-bottom: 1rem;">High Demand Careers</h4>
                 <div style="display: flex; flex-wrap: wrap; gap: 0.5rem;">
                     <?php foreach(explode("\n", trim($db_country['demand_careers'])) as $career): 
                         if(empty(trim($career))) continue;
                     ?>
                         <span style="background: #f1f5f9; padding: 0.4rem 0.8rem; border-radius: 6px; font-size: 0.85rem; color: #334155; font-weight: 500;"><i class="fa-solid fa-check" style="color: #10b981; margin-right: 0.25rem;"></i> <?= htmlspecialchars(trim($career)) ?></span>
                     <?php endforeach; ?>
                 </div>
             </div>
             <?php endif; ?>
          </div>
        </div>


        <!-- 12. TOP ADMITS -->
        <div id="admits" class="country-content-section animate-on-scroll">
          <h2>Top Admits in <?= $countryName ?></h2>
          <div class="country-card country-card--no-pad" style="text-align: center;">
             <div style="height: 300px; width: 100%; background: url('https://images.unsplash.com/photo-1523240795612-9a054b0db644?q=80&w=800&auto=format&fit=crop') no-repeat center center/cover;"></div>
             <div class="country-card-inner-pad">
                 <p style="color:var(--gray); line-height:1.8; margin: 0;">Our students have successfully secured admissions in top-tier universities across <?= $countryName ?>.</p>
             </div>
          </div>
        </div>

        <!-- 13. WHY CHOOSE BLUESTONE -->
        <div id="why-kc" class="country-content-section animate-on-scroll">
          <h2>Why Choose Bluestone Overseas</h2>
          <div class="country-card" style="background: linear-gradient(135deg, #0ea5e9, #0284c7); color: white;">
             <h3 style="color: white; margin-bottom: 1rem;">Your Trusted Education Partner</h3>
             <ul style="list-style: none; padding: 0; display: flex; flex-direction: column; gap: 1rem;">
               <li><i class="fa-solid fa-check" style="margin-right: 0.5rem; color: #bae6fd;"></i> 10+ Years of Excellence</li>
               <li><i class="fa-solid fa-check" style="margin-right: 0.5rem; color: #bae6fd;"></i> End-to-End Counseling</li>
               <li><i class="fa-solid fa-check" style="margin-right: 0.5rem; color: #bae6fd;"></i> High Visa Success Rate</li>
             </ul>
          </div>
        </div>

      </div> <!-- End country-content -->
    </div> <!-- End container sidebar-layout -->
  </section>

  <!-- CTA SECTION -->
  <section class="cta-banner-wrapper" style="padding: 4rem 1rem;">
    <div class="container cta-banner animate-on-scroll">
      <div class="cta-banner__left">
        <h2>Ready to Begin Your Journey to <?= $country['name'] ?>?</h2>
        <p>Join thousands of students who transformed their future with Bluestone Overseas Consultants.<br>Get expert counseling, university selection, and visa assistance tailored to your profile.</p>
        
        <div class="cta-buttons">
          <a href="consultation.php" class="btn btn--cyan"><i class="fa-solid fa-graduation-cap"></i> Book Free Consultation</a>
          <a href="tel:+919342899904" class="btn btn--orange"><i class="fa-solid fa-phone"></i> Call +91 93428 99904</a>
        </div>

        <div class="cta-tags">
          <span class="cta-tag"><i class="fa-solid fa-fire" style="color: #fbbf24;"></i> Trending</span>
          <span class="cta-tag">Data Science</span>
          <span class="cta-tag">MBA</span>
          <span class="cta-tag">Computer Science</span>
          <span class="cta-tag">Nursing</span>
        </div>
      </div>
      <div class="cta-banner__right">
        <div class="cta-image-circle">
          <img src="assets/images/cont.png" alt="Happy Student">
        </div>
      </div>
    </div>
  </section>

</main>
<?php require_once 'includes/footer.php'; ?>



