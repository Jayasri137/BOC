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

// 2. Fetch from static config as a fallback
$country = $all_countries[$country_slug] ?? $all_countries['usa'];

$pageTitle = 'Study in ' . ($db_country ? $db_country['name'] : $country['name']) . ' | Bluestone Overseas Consultants';
$pageDesc = $db_country ? $db_country['description'] : $country['desc'];
require_once 'includes/header.php';
?>

<style>
/* 2026 Premium Country Modules CSS */
:root {
  --neon-blue: #2563eb;
  --neon-purple: #8b5cf6;
  --neon-green: #10b981;
  --neon-orange: #f59e0b;
  --text-primary: #0f172a;
  --text-secondary: #475569;
  --text-muted: #94a3b8;
  --accent-glow: rgba(37, 99, 235, 0.15);
}

.advanced-badge {
  background: linear-gradient(135deg, var(--neon-blue), var(--neon-purple));
  color: white;
  font-size: 0.75rem;
  font-weight: 700;
  padding: 0.35rem 0.85rem;
  border-radius: 20px;
  text-transform: uppercase;
  letter-spacing: 0.05em;
  display: inline-flex;
  align-items: center;
  gap: 0.35rem;
  box-shadow: 0 4px 10px rgba(37, 99, 235, 0.25);
  margin-bottom: 1rem;
}

.badge-bar {
  display: flex;
  gap: 0.75rem;
  flex-wrap: wrap;
  margin-top: 1.5rem;
}

.metadata-pill {
  background: rgba(255, 255, 255, 0.08);
  backdrop-filter: blur(12px);
  border: 1px solid rgba(255, 255, 255, 0.15);
  color: #f1f5f9;
  font-size: 0.85rem;
  font-weight: 500;
  padding: 0.5rem 1rem;
  border-radius: 30px;
  display: inline-flex;
  align-items: center;
  gap: 0.5rem;
  transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.metadata-pill:hover {
  background: rgba(255, 255, 255, 0.15);
  border-color: rgba(255, 255, 255, 0.25);
  transform: translateY(-2px);
}

/* ROI Cards */
.roi-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
  gap: 1.5rem;
  margin-top: 2rem;
}

.roi-card {
  background: white;
  border-radius: 20px;
  border: 1px solid #e2e8f0;
  padding: 1.75rem;
  box-shadow: 0 4px 6px -1px rgba(0,0,0,0.02), 0 2px 4px -1px rgba(0,0,0,0.01);
  transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
  position: relative;
  overflow: hidden;
}

.roi-card::before {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 4px;
  background: linear-gradient(90deg, var(--neon-blue), var(--neon-purple));
  opacity: 0;
  transition: opacity 0.3s ease;
}

.roi-card:hover {
  transform: translateY(-5px);
  box-shadow: 0 20px 25px -5px rgba(0,0,0,0.06), 0 10px 10px -5px rgba(0,0,0,0.02);
  border-color: #cbd5e1;
}

.roi-card:hover::before {
  opacity: 1;
}

.roi-card-header {
  display: flex;
  align-items: center;
  gap: 1rem;
  margin-bottom: 1rem;
}

.roi-icon-container {
  width: 48px;
  height: 48px;
  border-radius: 12px;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 1.25rem;
}

.roi-icon-container--blue { background: rgba(37, 99, 235, 0.08); color: var(--neon-blue); }
.roi-icon-container--purple { background: rgba(139, 92, 246, 0.08); color: var(--neon-purple); }
.roi-icon-container--green { background: rgba(16, 185, 129, 0.08); color: var(--neon-green); }
.roi-icon-container--orange { background: rgba(245, 158, 11, 0.08); color: var(--neon-orange); }

.roi-card-tag {
  font-size: 0.7rem;
  font-weight: 700;
  text-transform: uppercase;
  letter-spacing: 0.05em;
  padding: 0.15rem 0.5rem;
  border-radius: 10px;
  margin-left: auto;
}

.roi-card-tag--blue { background: rgba(37, 99, 235, 0.08); color: var(--neon-blue); }
.roi-card-tag--purple { background: rgba(139, 92, 246, 0.08); color: var(--neon-purple); }
.roi-card-tag--green { background: rgba(16, 185, 129, 0.08); color: var(--neon-green); }
.roi-card-tag--orange { background: rgba(245, 158, 11, 0.08); color: var(--neon-orange); }

.roi-card p {
  color: #334155;
  font-weight: 500;
  line-height: 1.6;
  font-size: 0.95rem;
}

/* Finance Grid */
.finance-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
  gap: 1.5rem;
  margin-top: 2rem;
}

.finance-card {
  background: white;
  border: 1px solid #e2e8f0;
  border-radius: 20px;
  padding: 2rem 1.75rem;
  text-align: center;
  box-shadow: 0 4px 6px -1px rgba(0,0,0,0.02);
  transition: all 0.3s ease;
  position: relative;
}

.finance-card:hover {
  border-color: #cbd5e1;
  box-shadow: 0 15px 30px rgba(0,0,0,0.05);
}

.finance-title {
  font-size: 0.95rem;
  font-weight: 600;
  color: #64748b;
  margin-bottom: 1.25rem;
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 0.5rem;
}

.finance-value-local {
  font-size: 1.85rem;
  font-weight: 800;
  color: #0f172a;
  line-height: 1.1;
}

.finance-value-inr {
  font-size: 1.1rem;
  font-weight: 700;
  color: var(--neon-green);
  margin-top: 0.5rem;
}

.finance-value-detail {
  font-size: 0.85rem;
  color: #64748b;
  margin-top: 0.5rem;
}

/* Pillar Card */
.pillar-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
  gap: 1.5rem;
  margin-top: 2rem;
}

.pillar-card {
  background: white;
  border: 1px solid #e2e8f0;
  border-radius: 20px;
  padding: 2rem;
  transition: all 0.4s ease;
}

.pillar-card:hover {
  transform: translateY(-4px);
  border-color: var(--neon-blue);
  box-shadow: 0 12px 24px rgba(37, 99, 235, 0.06);
}

.pillar-num {
  font-size: 1rem;
  font-weight: 800;
  color: var(--neon-blue);
  background: rgba(37, 99, 235, 0.08);
  width: 32px;
  height: 32px;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  margin-bottom: 1.25rem;
}

.pillar-card h4 {
  font-size: 1.15rem;
  color: #0f172a;
  margin-bottom: 0.75rem;
  font-weight: 700;
}

.pillar-card p {
  font-size: 0.9rem;
  color: #475569;
  line-height: 1.6;
}

/* Stayback Table */
.stayback-container {
  overflow-x: auto;
  border-radius: 20px;
  border: 1px solid #e2e8f0;
  box-shadow: 0 4px 6px -1px rgba(0,0,0,0.02);
  margin-top: 2rem;
}

.stayback-table {
  width: 100%;
  border-collapse: collapse;
  text-align: left;
}

.stayback-table th {
  background: #f8fafc;
  padding: 1.25rem 1.5rem;
  font-weight: 700;
  color: #0f172a;
  font-size: 0.95rem;
  border-bottom: 2px solid #e2e8f0;
}

.stayback-table td {
  padding: 1.25rem 1.5rem;
  border-bottom: 1px solid #e2e8f0;
  font-size: 0.95rem;
  color: #334155;
}

.stayback-table tr:last-child td {
  border-bottom: none;
}

.stayback-table tr:hover td {
  background: #f8fafc;
}

/* Timeline */
.timeline-list {
  display: flex;
  flex-direction: column;
  gap: 1.5rem;
}

.timeline-card {
  position: relative;
  padding-left: 2rem;
  border-left: 2px solid var(--neon-blue);
}

.timeline-dot {
  position: absolute;
  left: -7px;
  top: 0;
  width: 12px;
  height: 12px;
  border-radius: 50%;
  background: var(--neon-blue);
  border: 2px solid white;
}

.timeline-card h4 {
  font-size: 1.1rem;
  color: #0f172a;
  margin-bottom: 0.25rem;
  font-weight: 700;
}

.timeline-card p {
  font-size: 0.85rem;
  color: #64748b;
}

/* Career Tag */
.career-flex {
  display: flex;
  gap: 0.75rem;
  flex-wrap: wrap;
}

.career-pill {
  background: #f1f5f9;
  color: #0f172a;
  border: 1px solid #cbd5e1;
  padding: 0.6rem 1.2rem;
  border-radius: 30px;
  font-size: 0.9rem;
  font-weight: 600;
  display: inline-flex;
  align-items: center;
  gap: 0.5rem;
  transition: all 0.3s ease;
}

.career-pill:hover {
  background: #cbd5e1;
  transform: scale(1.03);
}

/* University Hub Styling */
.frontend-uni-card {
  background: white;
  border: 1px solid #e2e8f0;
  border-radius: 20px;
  margin-bottom: 1rem;
  box-shadow: 0 4px 6px -1px rgba(0,0,0,0.01);
  overflow: hidden;
  transition: all 0.3s ease;
}

.frontend-uni-card:hover {
  border-color: #cbd5e1;
  box-shadow: 0 10px 15px -3px rgba(0,0,0,0.03);
}

.frontend-uni-header {
  padding: 1.5rem 2rem;
  display: flex;
  align-items: center;
  justify-content: space-between;
  cursor: pointer;
  user-select: none;
}

.frontend-uni-arrow {
  transition: transform 0.3s ease;
}

.frontend-uni-courses {
  display: none;
  padding: 0 2rem 2rem;
  border-top: 1px solid #f1f5f9;
  background: #f8fafc;
}

.frontend-uni-courses.active {
  display: block;
}

.frontend-course-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
  gap: 1rem;
  margin-top: 1.5rem;
}

.frontend-course-card {
  background: white;
  border: 1px solid #e2e8f0;
  border-radius: 12px;
  padding: 1.25rem;
  transition: all 0.3s ease;
}

.frontend-course-card:hover {
  border-color: var(--neon-blue);
  box-shadow: 0 4px 12px rgba(37, 99, 235, 0.05);
}

.frontend-course-title {
  font-weight: 700;
  font-size: 0.95rem;
  color: #0f172a;
  margin-bottom: 0.75rem;
}

.frontend-course-meta {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 0.5rem;
  font-size: 0.8rem;
  color: #64748b;
}

.frontend-course-fee {
  color: var(--neon-green);
  font-weight: 600;
}

/* Flight Corridor premium styling */
.flight-corridor-card {
  background: linear-gradient(135deg, #f0f9ff, #e0f2fe);
  border-radius: 24px;
  border: 1px solid #bae6fd;
  padding: 3rem;
  display: grid;
  grid-template-columns: 1fr 1.5fr;
  gap: 3rem;
  overflow: hidden;
  position: relative;
  box-shadow: 0 15px 35px rgba(14, 165, 233, 0.1);
  margin-top: 1rem;
  margin-bottom: 3rem;
}

@media (max-width: 991px) {
  .flight-corridor-card {
    grid-template-columns: 1fr;
    gap: 2rem;
    padding: 2rem;
  }
}

.flight-info-overlay {
  z-index: 5;
  display: flex;
  flex-direction: column;
  justify-content: center;
}

.flight-badge {
  background: rgba(37, 99, 235, 0.15);
  color: #3b82f6;
  border: 1px solid rgba(37, 99, 235, 0.3);
  padding: 0.35rem 0.85rem;
  border-radius: 30px;
  font-size: 0.75rem;
  font-weight: 700;
  text-transform: uppercase;
  letter-spacing: 0.05em;
  display: inline-flex;
  align-items: center;
  gap: 0.4rem;
  width: fit-content;
  margin-bottom: 1.25rem;
}

.flight-time-title {
  font-size: 2.2rem;
  font-weight: 800;
  color: #0c4a6e;
  margin-bottom: 0.5rem;
  letter-spacing: -0.02em;
}

.flight-time-duration {
  font-size: 1.1rem;
  color: #475569;
  margin-bottom: 2rem;
}

.flight-time-duration span {
  color: #f59e0b;
  font-weight: 700;
}

.route-details {
  display: flex;
  flex-direction: column;
  gap: 1.5rem;
  border-left: 2px dashed rgba(14, 165, 233, 0.2);
  padding-left: 1.5rem;
  margin-left: 0.5rem;
  position: relative;
}

.route-node {
  display: flex;
  align-items: flex-start;
  gap: 1rem;
  position: relative;
}

.node-indicator {
  width: 14px;
  height: 14px;
  border-radius: 50%;
  position: absolute;
  left: -23px;
  top: 5px;
  border: 2px solid #ffffff;
}

.pulsing-blue {
  background: #3b82f6;
  box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.4);
  animation: pulse-blue 2s infinite;
}

.pulsing-orange {
  background: #f59e0b;
  box-shadow: 0 0 0 4px rgba(245, 158, 11, 0.4);
  animation: pulse-orange 2s infinite;
}

@keyframes pulse-blue {
  0% { box-shadow: 0 0 0 0 rgba(59, 130, 246, 0.7); }
  70% { box-shadow: 0 0 0 10px rgba(59, 130, 246, 0); }
  100% { box-shadow: 0 0 0 0 rgba(59, 130, 246, 0); }
}

@keyframes pulse-orange {
  0% { box-shadow: 0 0 0 0 rgba(245, 158, 11, 0.7); }
  70% { box-shadow: 0 0 0 10px rgba(245, 158, 11, 0); }
  100% { box-shadow: 0 0 0 0 rgba(245, 158, 11, 0); }
}

.airport-code {
  display: block;
  font-size: 1.25rem;
  font-weight: 800;
  color: #0c4a6e;
  line-height: 1.1;
}

.airport-name {
  display: block;
  font-size: 0.85rem;
  color: #64748b;
  margin-top: 0.2rem;
}

/* Map area styling */
.flight-map-container {
  background: #ffffff;
  border: 1px solid #e0f2fe;
  border-radius: 16px;
  position: relative;
  height: 300px;
  display: flex;
  align-items: center;
  justify-content: center;
  overflow: hidden;
}

.flight-grid-bg {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-image: 
    linear-gradient(rgba(14, 165, 233, 0.05) 1px, transparent 1px),
    linear-gradient(90deg, rgba(14, 165, 233, 0.05) 1px, transparent 1px);
  background-size: 20px 20px;
}

.airport-dot {
  position: absolute;
  width: 12px;
  height: 12px;
  border-radius: 50%;
  background: #ffffff;
  z-index: 10;
}

.india-airport {
  background: #3b82f6;
  box-shadow: 0 0 15px #3b82f6;
}

.dest-airport {
  background: #f59e0b;
  box-shadow: 0 0 15px #f59e0b;
}

.radar-ping {
  position: absolute;
  top: -50%;
  left: -50%;
  width: 200%;
  height: 200%;
  border-radius: 50%;
  border: 1px solid rgba(59, 130, 246, 0.5);
  animation: radar-wave 3s infinite linear;
}

.radar-ping.orange {
  border-color: rgba(245, 158, 11, 0.5);
}

@keyframes radar-wave {
  0% { transform: scale(0.5); opacity: 1; }
  100% { transform: scale(2.5); opacity: 0; }
}

.airport-label {
  position: absolute;
  bottom: -24px;
  transform: translateX(-50%);
  left: 50%;
  font-size: 0.75rem;
  font-weight: 700;
  color: #cbd5e1;
  white-space: nowrap;
  letter-spacing: 0.05em;
}

.flight-svg {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  z-index: 2;
  pointer-events: none;
}


@media (max-width: 768px) {
  .frontend-uni-header {
    flex-direction: column;
    align-items: flex-start;
    gap: 1rem;
  }
  
  .frontend-uni-header h3 {
    font-size: 1.1rem;
  }

  .roi-grid, .pillar-grid, .finance-grid {
    grid-template-columns: 1fr;
  }
  
  .flight-corridor-card {
    padding: 1.5rem;
  }
  
  .flight-time-title {
    font-size: 1.5rem;
  }
}

@media (max-width: 480px) {
  .metadata-pill {
    font-size: 0.75rem;
    padding: 0.4rem 0.75rem;
  }
  
  .frontend-course-meta {
    grid-template-columns: 1fr;
  }
}
</style>

<main>
  <!-- HERO BANNER -->
  <section class="page-hero" style="background-image: linear-gradient(rgba(15, 23, 42, 0.55), rgba(15, 23, 42, 0.55)), url('<?= get_country_image_url($country_slug, $db_country['image_url'] ?? null) ?>'); background-size: cover; background-position: center;">
    <div class="container page-hero__inner">
      <div class="animate-on-scroll">
        <?php if ($has_advanced_modules): ?>
            <span class="advanced-badge">
                <i class="fa-solid fa-bolt"></i> 2026 Strategic Advantage Active
            </span>
        <?php else: ?>
            <span class="section__tag" style="background:rgba(255,255,255,0.1); color:#fff; border-color:rgba(255,255,255,0.2)"><?= $country['tag'] ?></span>
        <?php endif; ?>
        
        <h1>Study in <span class="text-gradient"><?= $db_country ? clean_output($db_country['name']) : $country['name'] ?></span></h1>
        <p style="color:white; max-width: 750px; line-height: 1.6;"><?= $db_country ? clean_output($db_country['description']) : $country['desc'] ?></p>
        
        <!-- <div class="badge-bar">
            <?php if ($db_country && !empty($db_country['travel_hours'])): ?>
                <span class="metadata-pill"><i class="fa-solid fa-plane-departure"></i> Travel: <?= clean_output($db_country['travel_hours']) ?></span>
            <?php endif; ?>
            <?php if ($db_country && !empty($db_country['study_options'])): ?>
                <span class="metadata-pill"><i class="fa-solid fa-graduation-cap"></i> <?= clean_output($db_country['study_options']) ?></span>
            <?php endif; ?>
        </div> -->
      </div>
    </div>
    <div class="hero-wave">
      <svg data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 120" preserveAspectRatio="none">
        <path d="M321.39,56.44c58-10.79,114.16-30.13,172-41.86,82.39-16.72,168.19-17.73,250.45-.39C823.78,31,906.67,72,985.66,92.83c70.05,18.48,146.53,26.09,214.34,3V0H0V27.35A600.21,600.21,0,0,0,321.39,56.44Z" fill="#ffffff" opacity="1"></path>
      </svg>
    </div>
  </section>

  <?php if ($has_advanced_modules): ?>
      
      <!-- ==========================================
           PREMIUM 6 MODULES VIEW
           ========================================== -->

      <!-- MODULE 1: THE "ROI" HERO HIGHLIGHTS -->
      <section class="section" style="padding-top: 0; margin-top: -60px; position: relative; z-index: 10;">
        <div class="container">
          <div class="roi-grid">
            
            <div class="roi-card animate-on-scroll">
              <div class="roi-card-header">
                <div class="roi-icon-container roi-icon-container--blue"><i class="fa-solid fa-passport"></i></div>
                <span class="roi-card-tag roi-card-tag--blue">Stay-back</span>
              </div>
              <p><?= clean_output($db_country['roi_advantage']) ?></p>
            </div>
            
            <div class="roi-card animate-on-scroll delay-1">
              <div class="roi-card-header">
                <div class="roi-icon-container roi-icon-container--purple"><i class="fa-solid fa-bolt-lightning"></i></div>
                <span class="roi-card-tag roi-card-tag--purple">Fast-Track</span>
              </div>
              <p><?= clean_output($db_country['roi_priority']) ?></p>
            </div>
            
            <div class="roi-card animate-on-scroll delay-2">
              <div class="roi-card-header">
                <div class="roi-icon-container roi-icon-container--green"><i class="fa-solid fa-coins"></i></div>
                <span class="roi-card-tag roi-card-tag--green">Earnings</span>
              </div>
              <p><?= clean_output($db_country['roi_wage']) ?></p>
            </div>
            
            <div class="roi-card animate-on-scroll delay-3">
              <div class="roi-card-header">
                <div class="roi-icon-container roi-icon-container--orange"><i class="fa-solid fa-building-columns"></i></div>
                <span class="roi-card-tag roi-card-tag--orange">Elite Rank</span>
              </div>
              <p><?= clean_output($db_country['roi_qs']) ?></p>
            </div>
            
          </div>
        </div>
      </section>

      <!-- FLIGHT CORRIDOR MAP SECTION -->
      <section class="section" style="padding-top: 0; background: #ffffff;">
        <div class="container">
          <div class="flight-corridor-card animate-on-scroll">
            <div class="flight-info-overlay">
              <span class="flight-badge"><i class="fa-solid fa-plane"></i> Direct Air Corridor</span>
              <h3 class="flight-time-title">India to <?= clean_output($db_country['name']) ?></h3>
              <p class="flight-time-duration"><i class="fa-solid fa-clock"></i> Travel Duration: <span><?= clean_output($db_country['travel_hours']) ?></span></p>
              <div class="route-details">
                <div class="route-node">
                  <div class="node-indicator pulsing-blue"></div>
                  <div>
                    <span class="airport-code">DEL / BOM</span>
                    <span class="airport-name">India (New Delhi / Mumbai)</span>
                  </div>
                </div>
                <div class="route-node">
                  <div class="node-indicator pulsing-orange"></div>
                  <div>
                    <span class="airport-code"><?= strtoupper(substr($db_country['slug'], 0, 3)) ?></span>
                    <span class="airport-name"><?= clean_output($db_country['name']) ?> Principal Hub</span>
                  </div>
                </div>
              </div>
            </div>
            
            <div class="flight-map-container">
              <!-- Grid background -->
              <div class="flight-grid-bg"></div>
              
              <!-- Map elements -->
              <div class="airport-dot india-airport" style="left: 15%; top: 60%;">
                <span class="radar-ping"></span>
                <span class="airport-label">India</span>
              </div>
              
              <div class="airport-dot dest-airport" style="right: 15%; top: 35%;">
                <span class="radar-ping orange"></span>
                <span class="airport-label"><?= clean_output($db_country['name']) ?></span>
              </div>
              
              <!-- SVG curved flight path with perfectly aligned animating vector plane -->
              <svg class="flight-svg" viewBox="0 0 800 300" preserveAspectRatio="none">
                <defs>
                  <linearGradient id="pathGradient" x1="0%" y1="0%" x2="100%" y2="0%">
                    <stop offset="0%" stop-color="#3b82f6" stop-opacity="0.8" />
                    <stop offset="100%" stop-color="#f59e0b" stop-opacity="0.8" />
                  </linearGradient>
                </defs>
                <!-- Flight curve path -->
                <path id="flight-curve" d="M 120,180 Q 400,20 680,105" fill="none" stroke="url(#pathGradient)" stroke-width="3" stroke-dasharray="8 6" />
                
                <!-- Native SVG Airplane vector following the curve with auto-rotation -->
                <g filter="drop-shadow(0px 2px 5px rgba(245, 158, 11, 0.6))">
                  <path d="M 14 0 L 2 -4 L 4 -12 L 0 -12 L -2 -4 L -10 -4 L -12 -8 L -14 -8 L -13 -4 L -15 0 L -13 4 L -14 8 L -12 8 L -10 4 L -2 4 L 0 12 L 4 12 L 2 4 Z" fill="#f59e0b">
                    <animateMotion dur="6s" repeatCount="indefinite" rotate="auto">
                      <mpath href="#flight-curve"/>
                    </animateMotion>
                  </path>
                </g>
              </svg>
            </div>
          </div>
        </div>
      </section>

      <!-- MODULE 2: TRANSPARENT FINANCE -->
      <section class="section" style="background:#f8fafc; border-top:1px solid #e2e8f0; border-bottom:1px solid #e2e8f0;">
        <div class="container">
          <div class="section__header animate-on-scroll">
            <span class="section__tag">The 2026 Benchmark</span>
            <h2 class="section__title">Official 2026 <span>Financial Requirements</span></h2>
            <p style="color:var(--gray); margin-top: 0.5rem;">Transparent legal baselines to build absolute trust and secure visa compliance.</p>
            <div class="accent-bar"></div>
          </div>
          
          <div class="finance-grid animate-on-scroll">
            
            <div class="finance-card">
              <div class="finance-title"><i class="fa-solid fa-house-chimney" style="color:var(--neon-blue);"></i> Annual Living Cost</div>
              <div class="finance-value-local"><?= clean_output($db_country['living_cost_local']) ?></div>
              <div class="finance-value-inr">Approx <?= clean_output($db_country['living_cost_inr']) ?></div>
              <div class="finance-value-detail">Official government requirement</div>
            </div>
            
            <div class="finance-card">
              <div class="finance-title"><i class="fa-solid fa-passport" style="color:var(--neon-purple);"></i> Student Visa Fee</div>
              <div class="finance-value-local"><?= clean_output($db_country['visa_fee_local']) ?></div>
              <div class="finance-value-inr">Approx <?= clean_output($db_country['visa_fee_inr']) ?></div>
              <div class="finance-value-detail">Non-refundable processing fee</div>
            </div>
            
            <div class="finance-card">
              <div class="finance-title"><i class="fa-solid fa-basket-shopping" style="color:var(--neon-orange);"></i> Weekly Budget</div>
              <div class="finance-value-local"><?= clean_output($db_country['weekly_budget_local']) ?></div>
              <div class="finance-value-detail"><?= clean_output($db_country['weekly_budget_inr']) ?></div>
            </div>
            
            <div class="finance-card">
              <div class="finance-title"><i class="fa-solid fa-hand-holding-dollar" style="color:var(--neon-green);"></i> Earnings Potential</div>
              <div class="finance-value-local"><?= clean_output($db_country['earnings_potential_local']) ?></div>
              <div class="finance-value-detail"><?= clean_output($db_country['earnings_potential_inr']) ?></div>
            </div>
            
          </div>
        </div>
      </section>

      <!-- MODULE 3: THE GENUINE STUDENT MASTERCLASS -->
      <section class="section">
        <div class="container">
          <div class="section__header animate-on-scroll">
            <span class="section__tag">Visa Success Framework</span>
            <h2 class="section__title">The <span>"Genuine Student"</span> Masterclass</h2>
            <p style="color:var(--gray); margin-top: 0.5rem;">"GTE is a thing of the past." In 2026, approvals depend on the 4 pillars of Genuine Success:</p>
            <div class="accent-bar"></div>
          </div>
          
          <div class="pillar-grid">
            
            <div class="pillar-card animate-on-scroll">
              <div class="pillar-num">1</div>
              <h4>Home Ties</h4>
              <p>Proof of solid rootedness in India, detailing family assets, networks, or social stakes guaranteeing return.</p>
            </div>
            
            <div class="pillar-card animate-on-scroll delay-1">
              <div class="pillar-num">2</div>
              <h4>Course Logic</h4>
              <p>A rigorous explanation of how this program directly links to and enhances your previous academic and professional background.</p>
            </div>
            
            <div class="pillar-card animate-on-scroll delay-2">
              <div class="pillar-num">3</div>
              <h4>Future ROI</h4>
              <p>A detailed career blueprint outlining specific roles, high-growth sectors, and target salary tiers in India post-graduation.</p>
            </div>
            
            <div class="pillar-card animate-on-scroll delay-3">
              <div class="pillar-num">4</div>
              <h4>Integrity</h4>
              <p>Transparent funds declarations, authentic documentation, and absolute honesty regarding historical academic caps/visa applications.</p>
            </div>
            
          </div>
        </div>
      </section>

      <!-- MODULE 4: THE UNIVERSITY DISCOVERY HUB -->
      <section class="section" style="background:#f8fafc; border-top:1px solid #e2e8f0; border-bottom:1px solid #e2e8f0;">
        <div class="container">
          <div class="section__header animate-on-scroll">
            <span class="section__tag">Program Catalog</span>
            <h2 class="section__title">University <span>Discovery Hub</span></h2>
            <p style="color:var(--gray); margin-top: 0.5rem;">Explore our leading partner institutions. Toggle a university to inspect active dynamic courses.</p>
            <div class="accent-bar"></div>
          </div>
          
          <div style="margin-top: 2rem;">
            <?php
            // Fetch universities for this country
            $unis_db = [];
            try {
                $stmtUnis = $pdo->prepare("SELECT * FROM universities WHERE country_id = :cid AND is_active = 1 ORDER BY name ASC");
                $stmtUnis->execute(['cid' => $db_country['id']]);
                $unis_db = $stmtUnis->fetchAll();
            } catch (PDOException $e) {
                $unis_db = [];
            }
            
            if (empty($unis_db)): ?>
                <!-- Fallback in case no universities are loaded in DB yet -->
                <div style="text-align:center; padding: 4rem 2rem; background:white; border-radius:20px; border:1px solid #e2e8f0;">
                    <i class="fa-solid fa-school" style="font-size:3rem; color:var(--neon-blue); margin-bottom:1rem;"></i>
                    <h4>Global Institutional Network Active</h4>
                    <p style="color:var(--gray); max-width:500px; margin: 0.5rem auto 1.5rem;">We hold direct tie-ups with over 15+ premier universities in this region. Talk with an advisor to review all courses.</p>
                    <a href="consultation.php" class="btn btn--primary">Get Curated Course List</a>
                </div>
            <?php else: ?>
                <?php foreach ($unis_db as $uniIndex => $uni): 
                    $uniId = intval($uni['id']);
                    
                    // Fetch courses for this specific university
                    $courses_db = [];
                    try {
                        $stmtCourses = $pdo->prepare("SELECT * FROM courses WHERE university_id = :uid AND is_active = 1 ORDER BY name ASC");
                        $stmtCourses->execute(['uid' => $uniId]);
                        $courses_db = $stmtCourses->fetchAll();
                    } catch (PDOException $e) {
                        $courses_db = [];
                    }
                ?>
                    <div class="frontend-uni-card animate-on-scroll">
                        <div class="frontend-uni-header" onclick="toggleFrontendCourses(<?= $uniId ?>)">
                            <div style="display: flex; align-items: center; gap: 1.25rem;">
                                <div class="icon-colorful icon-colorful--blue" style="font-size: 1.1rem; width:42px; height:42px; margin:0;"><i class="fa-solid fa-graduation-cap"></i></div>
                                <div>
                                    <h3 style="font-size:1.1rem; color:#0f172a; margin:0; font-weight:700;"><?= clean_output($uni['name']) ?></h3>
                                    <div style="display:flex; align-items:center; gap:0.75rem; font-size:0.8rem; color:#64748b; margin-top:0.25rem;">
                                        <?php if (!empty($uni['qs_ranking'])): ?>
                                            <span style="font-weight:700; color:#f59e0b;"><i class="fa-solid fa-star"></i> QS Rank: <?= clean_output($uni['qs_ranking']) ?></span>
                                        <?php endif; ?>
                                        <span><i class="fa-solid fa-atom"></i> Focus: <?= !empty($uni['specialization']) ? clean_output($uni['specialization']) : 'General' ?></span>
                                    </div>
                                </div>
                            </div>
                            <div class="frontend-uni-arrow" id="frontend-arrow-<?= $uniId ?>" style="font-size:1.1rem; color:#64748b;"><i class="fa-solid fa-chevron-down"></i></div>
                        </div>
                        
                        <div class="frontend-uni-courses" id="frontend-courses-<?= $uniId ?>">
                            <?php if (empty($courses_db)): ?>
                                <p style="font-size:0.85rem; color:#64748b; margin:1rem 0 0;">Course listing is being synchronized by admin. Free consulting active.</p>
                            <?php else: ?>
                                <div class="frontend-course-grid">
                                    <?php foreach ($courses_db as $c): ?>
                                        <div class="frontend-course-card">
                                            <div class="frontend-course-title"><?= clean_output($c['name']) ?></div>
                                            <div class="frontend-course-meta">
                                                <div><i class="fa-solid fa-clock"></i> <?= !empty($c['duration']) ? clean_output($c['duration']) : 'Flexible' ?></div>
                                                <div class="frontend-course-fee"><i class="fa-solid fa-wallet"></i> <?= !empty($c['tuition_fee']) ? clean_output($c['tuition_fee']) : 'Consult Fee' ?></div>
                                                <div style="grid-column: span 2; margin-top: 0.25rem; font-size: 0.75rem; color: #10b981; font-weight: 600;"><i class="fa-solid fa-calendar-days"></i> Intakes: <?= !empty($c['intakes']) ? clean_output($c['intakes']) : 'General' ?></div>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
          </div>
        </div>
      </section>

      <!-- MODULE 5: STAY-BACK RIGHTS TABLE -->
      <section class="section">
        <div class="container">
          <div class="section__header animate-on-scroll">
            <span class="section__tag">Post-Study Visa Rights</span>
            <h2 class="section__title">The <span>Stay-back</span> Advantages</h2>
            <p style="color:var(--gray); margin-top: 0.5rem;">Graduates from India receive exceptional support, enhanced by bilateral trade frameworks like the AI-ECTA.</p>
            <div class="accent-bar"></div>
          </div>
          
          <div class="stayback-container animate-on-scroll">
            <table class="stayback-table">
                <thead>
                    <tr>
                        <th>Qualification Level</th>
                        <th>Standard International Rights</th>
                        <th>Exclusive Indian Graduates Advantage</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td style="font-weight:700;">Bachelor's Degree (General)</td>
                        <td>2 Years</td>
                        <td><strong><?= clean_output($db_country['stayback_bachelors']) ?></strong></td>
                    </tr>
                    <tr>
                        <td style="font-weight:700;">Bachelor's Degree (STEM / Selected)</td>
                        <td>2 Years</td>
                        <td><strong style="color:var(--neon-blue);"><?= clean_output($db_country['stayback_bachelors_stem']) ?></strong></td>
                    </tr>
                    <tr>
                        <td style="font-weight:700;">Master's Degree (Coursework / Research)</td>
                        <td>2 Years</td>
                        <td><strong style="color:var(--neon-purple);"><?= clean_output($db_country['stayback_masters']) ?></strong></td>
                    </tr>
                    <tr>
                        <td style="font-weight:700;">Doctoral Degree (PhD)</td>
                        <td>3 Years</td>
                        <td><strong><?= clean_output($db_country['stayback_doctoral']) ?></strong></td>
                    </tr>
                    <tr>
                        <td style="font-weight:700;">Regional City Placement Bonus</td>
                        <td>+1 Year</td>
                        <td><strong style="color:var(--neon-green);"><?= clean_output($db_country['stayback_regional']) ?></strong></td>
                    </tr>
                </tbody>
            </table>
          </div>
        </div>
      </section>

      <!-- MODULE 6: CAREERS & INTAKES -->
      <section class="section" style="background:#f8fafc; border-top:1px solid #e2e8f0; border-bottom:1px solid #e2e8f0;">
        <div class="container">
          <div class="grid grid--2 gap--4">
            
            <div class="animate-on-scroll">
              <h3 style="font-size:1.5rem; color:#0f172a; margin-bottom:1.5rem; font-weight:800;"><i class="fa-solid fa-calendar-days" style="color:var(--neon-blue); margin-right:0.5rem;"></i> Upcoming Intakes & Deadlines</h3>
              <div class="timeline-list">
                <?php
                $intakes = array_map('trim', explode("\n", $db_country['upcoming_intakes']));
                foreach ($intakes as $intakeItem):
                    if (empty($intakeItem)) continue;
                    $parts = explode('|', $intakeItem);
                    $title = $parts[0] ?? '';
                    $deadline = $parts[1] ?? 'Plan 6 months ahead';
                ?>
                    <div class="timeline-card">
                      <div class="timeline-dot"></div>
                      <h4><?= clean_output($title) ?></h4>
                      <p><?= clean_output($deadline) ?></p>
                    </div>
                <?php endforeach; ?>
              </div>
            </div>
            
            <div class="animate-on-scroll delay-1">
              <h3 style="font-size:1.5rem; color:#0f172a; margin-bottom:1.5rem; font-weight:800;"><i class="fa-solid fa-chart-line" style="color:var(--neon-green); margin-right:0.5rem;"></i> High-Demand Career Pathways</h3>
              <p style="color:var(--gray); margin-bottom:1.5rem; line-height:1.6;">Graduating into designated shortage fields guarantees the highest student visa approval rate and fast-track transition to industry careers:</p>
              <div class="career-flex">
                <?php
                $careers = array_map('trim', explode("\n", $db_country['demand_careers']));
                foreach ($careers as $career):
                    if (empty($career)) continue;
                ?>
                    <span class="career-pill"><i class="fa-solid fa-circle-arrow-up" style="color:var(--neon-green);"></i> <?= clean_output($career) ?></span>
                <?php endforeach; ?>
              </div>
            </div>
            
          </div>
        </div>
      </section>

      <!-- AUSTRALIA EXCLUSIVE CONTENT -->
      <?php if ($country_slug === 'australia'): ?>
      
      <!-- MODULE 7: FEES STRUCTURE -->
      <section class="section">
        <div class="container">
          <div class="section__header animate-on-scroll">
            <span class="section__tag">Investment</span>
            <h2 class="section__title">Average <span>Fees Structure</span></h2>
            <div class="accent-bar"></div>
          </div>
          <div class="stayback-container animate-on-scroll">
            <table class="stayback-table">
                <thead>
                    <tr>
                        <th>Program Type</th>
                        <th>Average Annual Tuition Fee (AUD)</th>
                    </tr>
                </thead>
                <tbody>
                    <tr><td style="font-weight:700;">Diploma Programs</td><td>AUD 12,000 – 18,000</td></tr>
                    <tr><td style="font-weight:700;">Bachelor’s Degree</td><td>AUD 20,000 – 45,000</td></tr>
                    <tr><td style="font-weight:700;">Master’s Degree</td><td>AUD 22,000 – 50,000</td></tr>
                    <tr><td style="font-weight:700;">MBA Programs</td><td>AUD 35,000 – 60,000</td></tr>
                </tbody>
            </table>
          </div>
        </div>
      </section>

      <!-- MODULE 8: STUDY OPTIONS & WORK -->
      <section class="section" style="background:#f8fafc; border-top:1px solid #e2e8f0; border-bottom:1px solid #e2e8f0;">
        <div class="container">
          <div class="grid grid--2 gap--4">
            <div class="animate-on-scroll">
              <h3 style="font-size:1.5rem; color:#0f172a; margin-bottom:1.5rem; font-weight:800;"><i class="fa-solid fa-book-open" style="color:var(--neon-blue); margin-right:0.5rem;"></i> Available Study Pathways</h3>
              <div class="career-flex">
                <span class="career-pill"><i class="fa-solid fa-circle-check" style="color:var(--neon-blue);"></i> Diploma Programs</span>
                <span class="career-pill"><i class="fa-solid fa-circle-check" style="color:var(--neon-blue);"></i> Undergraduate Degrees</span>
                <span class="career-pill"><i class="fa-solid fa-circle-check" style="color:var(--neon-blue);"></i> Postgraduate Degrees</span>
                <span class="career-pill"><i class="fa-solid fa-circle-check" style="color:var(--neon-blue);"></i> Research Programs (PhD)</span>
                <span class="career-pill"><i class="fa-solid fa-circle-check" style="color:var(--neon-blue);"></i> Online & Hybrid Learning</span>
                <span class="career-pill"><i class="fa-solid fa-circle-check" style="color:var(--neon-blue);"></i> Vocational Education (VET)</span>
              </div>
            </div>
            <div class="animate-on-scroll delay-1">
              <h3 style="font-size:1.5rem; color:#0f172a; margin-bottom:1.5rem; font-weight:800;"><i class="fa-solid fa-briefcase" style="color:var(--neon-orange); margin-right:0.5rem;"></i> Work While Studying</h3>
              <div class="highlight-box bg-dots" style="background: white; border: 1px solid #e2e8f0; border-radius: 20px; padding: 2rem;">
                <p style="color:var(--gray); line-height:1.6; font-size:1.05rem;">International students can work <strong>part-time</strong> during academic sessions and <strong>full-time</strong> during scheduled academic breaks, offering a great way to gain local experience and support living expenses.</p>
              </div>
            </div>
          </div>
        </div>
      </section>

      <!-- MODULE 9: CLIMATE & FOOD -->
      <section class="section">
        <div class="container">
          <div class="grid grid--2 gap--4">
            <div class="animate-on-scroll">
              <h3 style="font-size:1.5rem; color:#0f172a; margin-bottom:1.5rem; font-weight:800;"><i class="fa-solid fa-sun" style="color:var(--neon-orange); margin-right:0.5rem;"></i> Climate in Australia</h3>
              <ul class="benefit-list" style="margin-bottom: 2rem;">
                <li><i class="fa-solid fa-temperature-full" style="color:var(--neon-orange);"></i> Warm summers and mild winters</li>
                <li><i class="fa-solid fa-umbrella-beach" style="color:var(--neon-orange);"></i> Pleasant coastal weather</li>
                <li><i class="fa-solid fa-tree" style="color:var(--neon-orange);"></i> Tropical climate in northern regions</li>
                <li><i class="fa-solid fa-cloud-sun" style="color:var(--neon-orange);"></i> Diverse climatic conditions</li>
              </ul>
            </div>
            <div class="animate-on-scroll delay-1">
              <h3 style="font-size:1.5rem; color:#0f172a; margin-bottom:1.5rem; font-weight:800;"><i class="fa-solid fa-utensils" style="color:var(--neon-green); margin-right:0.5rem;"></i> Food & Cuisine</h3>
              <p style="color:var(--gray); margin-bottom:1.5rem; line-height:1.6;">Indian students will feel right at home with easily accessible options:</p>
              <ul class="benefit-list">
                <li><i class="fa-solid fa-bowl-food" style="color:var(--neon-green);"></i> Authentic Indian restaurants</li>
                <li><i class="fa-solid fa-leaf" style="color:var(--neon-green);"></i> Abundant vegetarian food options</li>
                <li><i class="fa-solid fa-pepper-hot" style="color:var(--neon-green);"></i> South Indian and North Indian cuisines</li>
                <li><i class="fa-solid fa-burger" style="color:var(--neon-green);"></i> International chains & multicultural dining</li>
              </ul>
            </div>
          </div>
        </div>
      </section>

      <!-- MODULE 10: ALUMNI SUCCESS -->
      <section class="section" style="background:#f8fafc; border-top:1px solid #e2e8f0;">
        <div class="container">
          <div class="section__header animate-on-scroll">
            <span class="section__tag">Global Network</span>
            <h2 class="section__title">Alumni <span>Success</span></h2>
            <p style="color:var(--gray); margin-top: 0.5rem;">Graduates from Australian universities are highly valued by employers worldwide due to practical learning and international exposure.</p>
            <div class="accent-bar"></div>
          </div>
          <div class="roi-grid" style="grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));">
            <div class="roi-card animate-on-scroll" style="text-align:center; padding:1.5rem;">
              <div class="icon-colorful icon-colorful--blue" style="margin:0 auto 1rem; width:48px; height:48px; font-size:1.25rem;"><i class="fa-solid fa-laptop-code"></i></div>
              <h4 style="font-size:1.1rem; color:#0f172a; margin:0; font-weight:700;">Technology</h4>
            </div>
            <div class="roi-card animate-on-scroll delay-1" style="text-align:center; padding:1.5rem;">
              <div class="icon-colorful icon-colorful--green" style="margin:0 auto 1rem; width:48px; height:48px; font-size:1.25rem;"><i class="fa-solid fa-stethoscope"></i></div>
              <h4 style="font-size:1.1rem; color:#0f172a; margin:0; font-weight:700;">Healthcare</h4>
            </div>
            <div class="roi-card animate-on-scroll delay-2" style="text-align:center; padding:1.5rem;">
              <div class="icon-colorful icon-colorful--orange" style="margin:0 auto 1rem; width:48px; height:48px; font-size:1.25rem;"><i class="fa-solid fa-chart-pie"></i></div>
              <h4 style="font-size:1.1rem; color:#0f172a; margin:0; font-weight:700;">Business</h4>
            </div>
            <div class="roi-card animate-on-scroll delay-3" style="text-align:center; padding:1.5rem;">
              <div class="icon-colorful icon-colorful--purple" style="margin:0 auto 1rem; width:48px; height:48px; font-size:1.25rem;"><i class="fa-solid fa-flask"></i></div>
              <h4 style="font-size:1.1rem; color:#0f172a; margin:0; font-weight:700;">Research</h4>
            </div>
            <div class="roi-card animate-on-scroll delay-4" style="text-align:center; padding:1.5rem;">
              <div class="icon-colorful icon-colorful--teal" style="margin:0 auto 1rem; width:48px; height:48px; font-size:1.25rem;"><i class="fa-solid fa-landmark"></i></div>
              <h4 style="font-size:1.1rem; color:#0f172a; margin:0; font-weight:700;">Public Policy</h4>
            </div>
          </div>
        </div>
      </section>
      <?php elseif ($country_slug === 'canada'): ?>

      <!-- MODULE: STRATEGIC ADMISSION PATHWAYS -->
      <section class="section" style="background:#f8fafc; border-top:1px solid #e2e8f0;">
        <div class="container">
          <div class="section__header animate-on-scroll">
            <span class="section__tag">The 2026 Strategic Advantage</span>
            <h2 class="section__title">Strategic <span>Admission Pathways</span></h2>
            <p style="color:var(--gray); margin-top: 0.5rem;">"In 2026, Canada is prioritizing high-potential global talent." At Bluestone, we guide students toward “Cap-Exempt” pathways and provinces with strong PAL availability.</p>
            <div class="accent-bar"></div>
          </div>
          <div class="grid grid--3 gap--4">
            <div class="pillar-card animate-on-scroll">
              <div class="pillar-num" style="background:rgba(139, 92, 246, 0.08); color:var(--neon-purple);">1</div>
              <h4>Master’s & PhD Programs</h4>
              <p><i class="fa-solid fa-check text-success"></i> No PAL/TAL required<br>
                 <i class="fa-solid fa-check text-success"></i> Direct application pathway to IRCC<br>
                 <i class="fa-solid fa-check text-success"></i> Faster 2026 processing timelines</p>
            </div>
            <div class="pillar-card animate-on-scroll delay-1">
              <div class="pillar-num" style="background:rgba(16, 185, 129, 0.08); color:var(--neon-green);">2</div>
              <h4>Undergraduate & Diploma</h4>
              <p>Strategic selection of Designated Learning Institutions (DLIs) in provinces such as Alberta and Saskatchewan with stronger quota availability.</p>
            </div>
            <div class="pillar-card animate-on-scroll delay-2">
              <div class="pillar-num" style="background:rgba(245, 158, 11, 0.08); color:var(--neon-orange);">3</div>
              <h4>Field-of-Study Alignment</h4>
              <p>Focused guidance toward STEM, Healthcare, and Skilled Trades. Improves PGWP eligibility, employment outcomes, and Permanent Residency success.</p>
            </div>
          </div>
        </div>
      </section>

      <!-- MODULE: THE 2026 PGWP FRAMEWORK -->
      <section class="section">
        <div class="container">
          <div class="section__header animate-on-scroll">
            <span class="section__tag">Post-Graduation Work Permit</span>
            <h2 class="section__title">The 2026 <span>PGWP Framework</span></h2>
            <p style="color:var(--gray); margin-top: 0.5rem;">Unlike traditional consultancies, Bluestone helps students verify PGWP eligibility before admission itself.</p>
            <div class="accent-bar"></div>
          </div>
          <div class="grid grid--3 gap--4">
            <div class="info-card animate-on-scroll">
              <div class="ic-header"><div class="icon-colorful icon-colorful--blue" style="font-size:1.5rem; margin-right:.75rem"><i class="fa-solid fa-building-columns"></i></div> <h4>University Graduates</h4></div>
              <div class="ic-body">
                <ul class="benefit-list" style="margin-top: 1rem;">
                  <li><i class="fa-solid fa-circle-check text-success"></i> Eligible for up to 3 years PGWP</li>
                  <li><i class="fa-solid fa-language text-success"></i> Minimum language requirement: CLB 7</li>
                </ul>
              </div>
            </div>
            <div class="info-card animate-on-scroll delay-1">
              <div class="ic-header"><div class="icon-colorful icon-colorful--purple" style="font-size:1.5rem; margin-right:.75rem"><i class="fa-solid fa-school"></i></div> <h4>College Graduates</h4></div>
              <div class="ic-body">
                <ul class="benefit-list" style="margin-top: 1rem;">
                  <li><i class="fa-solid fa-list-check text-success"></i> Eligibility linked to 1,107 approved Field-of-Study Codes</li>
                  <li><i class="fa-solid fa-stethoscope text-success"></i> Focused on STEM, Healthcare, and skilled sectors</li>
                  <li><i class="fa-solid fa-language text-success"></i> Minimum language requirement: CLB 5</li>
                </ul>
              </div>
            </div>
            <div class="info-card animate-on-scroll delay-2">
              <div class="ic-header"><div class="icon-colorful icon-colorful--orange" style="font-size:1.5rem; margin-right:.75rem"><i class="fa-solid fa-graduation-cap"></i></div> <h4>Master’s Graduates</h4></div>
              <div class="ic-body">
                <p style="font-weight: 600; margin-bottom: 0.5rem;">A major 2026 advantage for Indian students:</p>
                <ul class="benefit-list">
                  <li><i class="fa-solid fa-shield-halved text-success"></i> Guaranteed 3-year PGWP</li>
                  <li><i class="fa-solid fa-bolt text-success"></i> Applicable even for 1-year Master’s programs</li>
                </ul>
              </div>
            </div>
          </div>
        </div>
      </section>

      <!-- MODULE: FEES STRUCTURE -->
      <section class="section" style="background:#f8fafc; border-top:1px solid #e2e8f0;">
        <div class="container">
          <div class="section__header animate-on-scroll">
            <span class="section__tag">Investment</span>
            <h2 class="section__title">Average <span>Fees Structure</span></h2>
            <div class="accent-bar"></div>
          </div>
          <div class="stayback-container animate-on-scroll">
            <table class="stayback-table">
                <thead>
                    <tr>
                        <th>Program Type</th>
                        <th>Average Annual Tuition Fee (CAD)</th>
                    </tr>
                </thead>
                <tbody>
                    <tr><td style="font-weight:700;">Diploma Programs</td><td>CAD 12,000 – 20,000</td></tr>
                    <tr><td style="font-weight:700;">Bachelor’s Degree</td><td>CAD 18,000 – 35,000</td></tr>
                    <tr><td style="font-weight:700;">Master’s Degree</td><td>CAD 20,000 – 45,000</td></tr>
                    <tr><td style="font-weight:700;">MBA Programs</td><td>CAD 35,000 – 65,000</td></tr>
                </tbody>
            </table>
          </div>
        </div>
      </section>

      <!-- MODULE: STUDY OPTIONS & WORK -->
      <section class="section">
        <div class="container">
          <div class="grid grid--2 gap--4">
            <div class="animate-on-scroll">
              <h3 style="font-size:1.5rem; color:#0f172a; margin-bottom:1.5rem; font-weight:800;"><i class="fa-solid fa-book-open" style="color:var(--neon-blue); margin-right:0.5rem;"></i> Available Study Pathways</h3>
              <div class="career-flex">
                <span class="career-pill"><i class="fa-solid fa-circle-check" style="color:var(--neon-blue);"></i> Diploma Programs</span>
                <span class="career-pill"><i class="fa-solid fa-circle-check" style="color:var(--neon-blue);"></i> Undergraduate Degrees</span>
                <span class="career-pill"><i class="fa-solid fa-circle-check" style="color:var(--neon-blue);"></i> Postgraduate Degrees</span>
                <span class="career-pill"><i class="fa-solid fa-circle-check" style="color:var(--neon-blue);"></i> Co-op Programs</span>
                <span class="career-pill"><i class="fa-solid fa-circle-check" style="color:var(--neon-blue);"></i> Research-Based Programs</span>
                <span class="career-pill"><i class="fa-solid fa-circle-check" style="color:var(--neon-blue);"></i> Professional Certifications</span>
              </div>
            </div>
            <div class="animate-on-scroll delay-1">
              <h3 style="font-size:1.5rem; color:#0f172a; margin-bottom:1.5rem; font-weight:800;"><i class="fa-solid fa-briefcase" style="color:var(--neon-orange); margin-right:0.5rem;"></i> Work While Studying</h3>
              <div class="highlight-box bg-dots" style="background: white; border: 1px solid #e2e8f0; border-radius: 20px; padding: 2rem;">
                <p style="color:var(--gray); line-height:1.6; font-size:1.05rem;">International students can work: <strong>Up to 24 hours per week</strong> during academic sessions, and <strong>full-time</strong> during scheduled breaks and holidays.</p>
              </div>
            </div>
          </div>
        </div>
      </section>

      <!-- MODULE: CLIMATE & FOOD -->
      <section class="section" style="background:#f8fafc; border-top:1px solid #e2e8f0;">
        <div class="container">
          <div class="grid grid--2 gap--4">
            <div class="animate-on-scroll">
              <h3 style="font-size:1.5rem; color:#0f172a; margin-bottom:1.5rem; font-weight:800;"><i class="fa-solid fa-snowflake" style="color:var(--neon-blue); margin-right:0.5rem;"></i> Climate in Canada</h3>
              <ul class="benefit-list" style="margin-bottom: 2rem;">
                <li><i class="fa-solid fa-temperature-arrow-down" style="color:var(--neon-blue);"></i> Cold winters with snowfall</li>
                <li><i class="fa-solid fa-sun" style="color:var(--neon-orange);"></i> Pleasant summers</li>
                <li><i class="fa-solid fa-leaf" style="color:var(--neon-green);"></i> Beautiful autumn seasons</li>
                <li><i class="fa-solid fa-cloud-sun-rain" style="color:var(--neon-purple);"></i> Regional climate variations across provinces</li>
              </ul>
            </div>
            <div class="animate-on-scroll delay-1">
              <h3 style="font-size:1.5rem; color:#0f172a; margin-bottom:1.5rem; font-weight:800;"><i class="fa-solid fa-utensils" style="color:var(--neon-green); margin-right:0.5rem;"></i> Food & Cuisine</h3>
              <p style="color:var(--gray); margin-bottom:1.5rem; line-height:1.6;">Indian students can easily access:</p>
              <ul class="benefit-list">
                <li><i class="fa-solid fa-store" style="color:var(--neon-green);"></i> Indian restaurants and grocery stores</li>
                <li><i class="fa-solid fa-leaf" style="color:var(--neon-green);"></i> Vegetarian and vegan food options</li>
                <li><i class="fa-solid fa-pepper-hot" style="color:var(--neon-green);"></i> South Indian and North Indian cuisine</li>
                <li><i class="fa-solid fa-burger" style="color:var(--neon-green);"></i> Multicultural international food options</li>
              </ul>
            </div>
          </div>
        </div>
      </section>

      <!-- MODULE: ALUMNI SUCCESS -->
      <section class="section">
        <div class="container">
          <div class="section__header animate-on-scroll">
            <span class="section__tag">Global Network</span>
            <h2 class="section__title">Alumni <span>Success</span></h2>
            <p style="color:var(--gray); margin-top: 0.5rem;">Graduates benefit from strong employment opportunities, global exposure, and clear Permanent Residency pathways.</p>
            <div class="accent-bar"></div>
          </div>
          <div class="roi-grid" style="grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));">
            <div class="roi-card animate-on-scroll" style="text-align:center; padding:1.5rem;">
              <div class="icon-colorful icon-colorful--blue" style="margin:0 auto 1rem; width:48px; height:48px; font-size:1.25rem;"><i class="fa-solid fa-laptop-code"></i></div>
              <h4 style="font-size:1.1rem; color:#0f172a; margin:0; font-weight:700;">Technology</h4>
            </div>
            <div class="roi-card animate-on-scroll delay-1" style="text-align:center; padding:1.5rem;">
              <div class="icon-colorful icon-colorful--green" style="margin:0 auto 1rem; width:48px; height:48px; font-size:1.25rem;"><i class="fa-solid fa-stethoscope"></i></div>
              <h4 style="font-size:1.1rem; color:#0f172a; margin:0; font-weight:700;">Healthcare</h4>
            </div>
            <div class="roi-card animate-on-scroll delay-2" style="text-align:center; padding:1.5rem;">
              <div class="icon-colorful icon-colorful--orange" style="margin:0 auto 1rem; width:48px; height:48px; font-size:1.25rem;"><i class="fa-solid fa-helmet-safety"></i></div>
              <h4 style="font-size:1.1rem; color:#0f172a; margin:0; font-weight:700;">Engineering</h4>
            </div>
            <div class="roi-card animate-on-scroll delay-3" style="text-align:center; padding:1.5rem;">
              <div class="icon-colorful icon-colorful--purple" style="margin:0 auto 1rem; width:48px; height:48px; font-size:1.25rem;"><i class="fa-solid fa-flask"></i></div>
              <h4 style="font-size:1.1rem; color:#0f172a; margin:0; font-weight:700;">Research</h4>
            </div>
            <div class="roi-card animate-on-scroll delay-4" style="text-align:center; padding:1.5rem;">
              <div class="icon-colorful icon-colorful--teal" style="margin:0 auto 1rem; width:48px; height:48px; font-size:1.25rem;"><i class="fa-solid fa-lightbulb"></i></div>
              <h4 style="font-size:1.1rem; color:#0f172a; margin:0; font-weight:700;">Entrepreneurship</h4>
            </div>
          </div>
        </div>
      </section>
      <?php elseif ($country_slug === 'uae'): ?>

      <!-- MODULE: THE 2026 STRATEGIC ADVANTAGE -->
      <section class="section" style="background:#f8fafc; border-top:1px solid #e2e8f0;">
        <div class="container">
          <div class="section__header animate-on-scroll">
            <span class="section__tag">The Regional Catalyst</span>
            <h2 class="section__title">The 2026 <span>Strategic Advantage</span></h2>
            <p style="color:var(--gray); margin-top: 0.5rem;">"The UAE has evolved into a strategic education powerhouse." In 2026, Bluestone focuses on “Career-First” programs in Dubai and Abu Dhabi.</p>
            <div class="accent-bar"></div>
          </div>
          <div class="grid grid--3 gap--4">
            <div class="pillar-card animate-on-scroll">
              <div class="pillar-num" style="background:rgba(139, 92, 246, 0.08); color:var(--neon-purple);"><i class="fa-solid fa-city"></i></div>
              <h4>Industry Immersion</h4>
              <p>Direct exposure to major global ecosystems:<br>
                 <i class="fa-solid fa-check text-success"></i> Dubai Internet City<br>
                 <i class="fa-solid fa-check text-success"></i> Dubai Media City<br>
                 <i class="fa-solid fa-check text-success"></i> Abu Dhabi’s Masdar City</p>
            </div>
            <div class="pillar-card animate-on-scroll delay-1">
              <div class="pillar-num" style="background:rgba(16, 185, 129, 0.08); color:var(--neon-green);"><i class="fa-solid fa-globe"></i></div>
              <h4>Degree Portability</h4>
              <p>Earn internationally recognized degrees from UK, Australian, and American Universities, while studying in a fast-growing global business region.</p>
            </div>
            <div class="pillar-card animate-on-scroll delay-2">
              <div class="pillar-num" style="background:rgba(245, 158, 11, 0.08); color:var(--neon-orange);"><i class="fa-solid fa-plane"></i></div>
              <h4>Proximity to Home</h4>
              <p>Just 3.5 hours from South India. Enjoy international exposure, easier travel accessibility, and strong family connectivity.</p>
            </div>
          </div>
        </div>
      </section>

      <!-- MODULE: VISA FRAMEWORK -->
      <section class="section">
        <div class="container">
          <div class="section__header animate-on-scroll">
            <span class="section__tag">The “Student Residence” Success Map</span>
            <h2 class="section__title">The 2026 <span>Visa & Work Framework</span></h2>
            <div class="accent-bar"></div>
          </div>
          <div class="grid grid--2 gap--4">
            <div class="info-card animate-on-scroll">
              <div class="ic-header"><div class="icon-colorful icon-colorful--blue" style="font-size:1.5rem; margin-right:.75rem"><i class="fa-solid fa-id-card"></i></div> <h4>Student Residence Visa</h4></div>
              <div class="ic-body">
                <ul class="benefit-list" style="margin-top: 1rem;">
                  <li><i class="fa-solid fa-calendar-check text-success"></i> Valid for 1 year</li>
                  <li><i class="fa-solid fa-rotate text-success"></i> Renewable annually based on performance and enrollment</li>
                </ul>
              </div>
            </div>
            <div class="info-card animate-on-scroll delay-1">
              <div class="ic-header"><div class="icon-colorful icon-colorful--orange" style="font-size:1.5rem; margin-right:.75rem"><i class="fa-solid fa-medal"></i></div> <h4>The Golden Visa (Elite Pathway)</h4></div>
              <div class="ic-body">
                <p style="font-weight: 600; margin-bottom: 0.5rem;">For outstanding graduates (GPA 3.5 – 3.8):</p>
                <ul class="benefit-list">
                  <li><i class="fa-solid fa-crown text-success"></i> 10-Year Self-Sponsored Residency</li>
                  <li><i class="fa-solid fa-briefcase text-success"></i> Exceptional career flexibility in the UAE</li>
                </ul>
              </div>
            </div>
            <div class="info-card animate-on-scroll delay-2">
              <div class="ic-header"><div class="icon-colorful icon-colorful--green" style="font-size:1.5rem; margin-right:.75rem"><i class="fa-solid fa-leaf"></i></div> <h4>Green Visa</h4></div>
              <div class="ic-body">
                <p style="font-weight: 600; margin-bottom: 0.5rem;">5-year residency option for skilled professionals:</p>
                <ul class="benefit-list">
                  <li><i class="fa-solid fa-users text-success"></i> Family sponsorship allowed</li>
                  <li><i class="fa-solid fa-user-check text-success"></i> Independent residency and employment flexibility</li>
                </ul>
              </div>
            </div>
            <div class="info-card animate-on-scroll delay-3">
              <div class="ic-header"><div class="icon-colorful icon-colorful--purple" style="font-size:1.5rem; margin-right:.75rem"><i class="fa-solid fa-hourglass-half"></i></div> <h4>Post-Graduation Grace Period</h4></div>
              <div class="ic-body">
                <ul class="benefit-list" style="margin-top: 1rem;">
                  <li><i class="fa-solid fa-clock text-success"></i> Up to 6 months to secure employment</li>
                  <li><i class="fa-solid fa-file-signature text-success"></i> Easy conversion into a Standard UAE Work Visa</li>
                </ul>
              </div>
            </div>
          </div>
        </div>
      </section>

      <!-- MODULE: FEES STRUCTURE -->
      <section class="section" style="background:#f8fafc; border-top:1px solid #e2e8f0;">
        <div class="container">
          <div class="section__header animate-on-scroll">
            <span class="section__tag">Investment</span>
            <h2 class="section__title">Average <span>Fees Structure</span></h2>
            <div class="accent-bar"></div>
          </div>
          <div class="stayback-container animate-on-scroll">
            <table class="stayback-table">
                <thead>
                    <tr>
                        <th>Program Type</th>
                        <th>Average Annual Tuition Fee (AED)</th>
                    </tr>
                </thead>
                <tbody>
                    <tr><td style="font-weight:700;">Diploma Programs</td><td>AED 20,000 – 35,000</td></tr>
                    <tr><td style="font-weight:700;">Bachelor’s Degree</td><td>AED 35,000 – 75,000</td></tr>
                    <tr><td style="font-weight:700;">Master’s Degree</td><td>AED 45,000 – 90,000</td></tr>
                    <tr><td style="font-weight:700;">MBA Programs</td><td>AED 60,000 – 120,000</td></tr>
                </tbody>
            </table>
          </div>
        </div>
      </section>

      <!-- MODULE: STUDY OPTIONS & WORK -->
      <section class="section">
        <div class="container">
          <div class="grid grid--2 gap--4">
            <div class="animate-on-scroll">
              <h3 style="font-size:1.5rem; color:#0f172a; margin-bottom:1.5rem; font-weight:800;"><i class="fa-solid fa-book-open" style="color:var(--neon-blue); margin-right:0.5rem;"></i> Available Study Pathways</h3>
              <div class="career-flex">
                <span class="career-pill"><i class="fa-solid fa-circle-check" style="color:var(--neon-blue);"></i> Foundation Programs</span>
                <span class="career-pill"><i class="fa-solid fa-circle-check" style="color:var(--neon-blue);"></i> Undergraduate Degrees</span>
                <span class="career-pill"><i class="fa-solid fa-circle-check" style="color:var(--neon-blue);"></i> Postgraduate Degrees</span>
                <span class="career-pill"><i class="fa-solid fa-circle-check" style="color:var(--neon-blue);"></i> Branch Campus Programs</span>
                <span class="career-pill"><i class="fa-solid fa-circle-check" style="color:var(--neon-blue);"></i> Professional Certifications</span>
                <span class="career-pill"><i class="fa-solid fa-circle-check" style="color:var(--neon-blue);"></i> Research Programs</span>
              </div>
            </div>
            <div class="animate-on-scroll delay-1">
              <h3 style="font-size:1.5rem; color:#0f172a; margin-bottom:1.5rem; font-weight:800;"><i class="fa-solid fa-briefcase" style="color:var(--neon-orange); margin-right:0.5rem;"></i> Work While Studying</h3>
              <div class="highlight-box bg-dots" style="background: white; border: 1px solid #e2e8f0; border-radius: 20px; padding: 2rem;">
                <p style="color:var(--gray); line-height:1.6; font-size:1.05rem;">International students can work <strong>up to 24 hours/week</strong> with NOC approval, gaining incredible internship opportunities through university partnerships and direct access to industry-based projects.</p>
              </div>
            </div>
          </div>
        </div>
      </section>

      <!-- MODULE: CLIMATE & FOOD -->
      <section class="section" style="background:#f8fafc; border-top:1px solid #e2e8f0;">
        <div class="container">
          <div class="grid grid--2 gap--4">
            <div class="animate-on-scroll">
              <h3 style="font-size:1.5rem; color:#0f172a; margin-bottom:1.5rem; font-weight:800;"><i class="fa-solid fa-sun" style="color:var(--neon-orange); margin-right:0.5rem;"></i> Climate in UAE</h3>
              <ul class="benefit-list" style="margin-bottom: 2rem;">
                <li><i class="fa-solid fa-temperature-full" style="color:var(--neon-orange);"></i> Hot summers</li>
                <li><i class="fa-solid fa-temperature-empty" style="color:var(--neon-blue);"></i> Mild and pleasant winters</li>
                <li><i class="fa-solid fa-droplet-slash" style="color:var(--neon-orange);"></i> Low rainfall throughout the year</li>
                <li><i class="fa-solid fa-building" style="color:var(--neon-purple);"></i> Comfortable indoor lifestyle due to advanced infrastructure</li>
              </ul>
            </div>
            <div class="animate-on-scroll delay-1">
              <h3 style="font-size:1.5rem; color:#0f172a; margin-bottom:1.5rem; font-weight:800;"><i class="fa-solid fa-utensils" style="color:var(--neon-green); margin-right:0.5rem;"></i> Food & Cuisine</h3>
              <p style="color:var(--gray); margin-bottom:1.5rem; line-height:1.6;">Indian students can easily find:</p>
              <ul class="benefit-list">
                <li><i class="fa-solid fa-pepper-hot" style="color:var(--neon-green);"></i> South Indian and North Indian cuisine</li>
                <li><i class="fa-solid fa-leaf" style="color:var(--neon-green);"></i> Vegetarian and Jain food options</li>
                <li><i class="fa-solid fa-store" style="color:var(--neon-green);"></i> Indian grocery stores and restaurants</li>
                <li><i class="fa-solid fa-burger" style="color:var(--neon-green);"></i> International food chains & multicultural dining</li>
              </ul>
            </div>
          </div>
        </div>
      </section>

      <!-- MODULE: ALUMNI SUCCESS -->
      <section class="section">
        <div class="container">
          <div class="section__header animate-on-scroll">
            <span class="section__tag">Global Network</span>
            <h2 class="section__title">Alumni <span>Success</span></h2>
            <p style="color:var(--gray); margin-top: 0.5rem;">UAE graduates benefit from strong employer connections, international exposure, and opportunities in tax-free global business environments.</p>
            <div class="accent-bar"></div>
          </div>
          <div class="roi-grid" style="grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));">
            <div class="roi-card animate-on-scroll" style="text-align:center; padding:1.5rem;">
              <div class="icon-colorful icon-colorful--blue" style="margin:0 auto 1rem; width:48px; height:48px; font-size:1.25rem;"><i class="fa-solid fa-laptop-code"></i></div>
              <h4 style="font-size:1.1rem; color:#0f172a; margin:0; font-weight:700;">Technology</h4>
            </div>
            <div class="roi-card animate-on-scroll delay-1" style="text-align:center; padding:1.5rem;">
              <div class="icon-colorful icon-colorful--green" style="margin:0 auto 1rem; width:48px; height:48px; font-size:1.25rem;"><i class="fa-solid fa-coins"></i></div>
              <h4 style="font-size:1.1rem; color:#0f172a; margin:0; font-weight:700;">Finance</h4>
            </div>
            <div class="roi-card animate-on-scroll delay-2" style="text-align:center; padding:1.5rem;">
              <div class="icon-colorful icon-colorful--orange" style="margin:0 auto 1rem; width:48px; height:48px; font-size:1.25rem;"><i class="fa-solid fa-building"></i></div>
              <h4 style="font-size:1.1rem; color:#0f172a; margin:0; font-weight:700;">Architecture</h4>
            </div>
            <div class="roi-card animate-on-scroll delay-3" style="text-align:center; padding:1.5rem;">
              <div class="icon-colorful icon-colorful--purple" style="margin:0 auto 1rem; width:48px; height:48px; font-size:1.25rem;"><i class="fa-solid fa-video"></i></div>
              <h4 style="font-size:1.1rem; color:#0f172a; margin:0; font-weight:700;">Media</h4>
            </div>
            <div class="roi-card animate-on-scroll delay-4" style="text-align:center; padding:1.5rem;">
              <div class="icon-colorful icon-colorful--teal" style="margin:0 auto 1rem; width:48px; height:48px; font-size:1.25rem;"><i class="fa-solid fa-stethoscope"></i></div>
              <h4 style="font-size:1.1rem; color:#0f172a; margin:0; font-weight:700;">Healthcare</h4>
            </div>
            <div class="roi-card animate-on-scroll delay-5" style="text-align:center; padding:1.5rem;">
              <div class="icon-colorful icon-colorful--orange" style="margin:0 auto 1rem; width:48px; height:48px; font-size:1.25rem; background:rgba(234,88,12,0.1); color:#ea580c;"><i class="fa-solid fa-rocket"></i></div>
              <h4 style="font-size:1.1rem; color:#0f172a; margin:0; font-weight:700;">Startups</h4>
            </div>
          </div>
        </div>
      </section>
      <?php elseif ($country_slug === 'germany'): ?>

      <!-- MODULE: THE 2026 ACADEMIC SHIFT -->
      <section class="section" style="background:#f8fafc; border-top:1px solid #e2e8f0;">
        <div class="container">
          <div class="section__header animate-on-scroll">
            <span class="section__tag">The 70% Threshold</span>
            <h2 class="section__title">The 2026 <span>Academic Shift</span></h2>
            <p style="color:var(--gray); margin-top: 0.5rem;">"Germany has raised the academic benchmark." We simplify the complex “Anabin” eligibility system and guide students toward the right university pathway.</p>
            <div class="accent-bar"></div>
          </div>
          <div class="grid grid--3 gap--4">
            <div class="pillar-card animate-on-scroll">
              <div class="pillar-num" style="background:rgba(139, 92, 246, 0.08); color:var(--neon-purple);"><i class="fa-solid fa-file-shield"></i></div>
              <h4>The APS Advantage</h4>
              <p>The Academic Evaluation Centre (APS) process is mandatory. We provide:<br>
                 <i class="fa-solid fa-check text-success"></i> Documentation support<br>
                 <i class="fa-solid fa-check text-success"></i> Verification guidance<br>
                 <i class="fa-solid fa-check text-success"></i> Timeline management</p>
            </div>
            <div class="pillar-card animate-on-scroll delay-1">
              <div class="pillar-num" style="background:rgba(16, 185, 129, 0.08); color:var(--neon-green);"><i class="fa-solid fa-bolt"></i></div>
              <h4>Direct Entry Pathway</h4>
              <p>Students with <strong>70%+ in Class XII</strong> and <strong>one completed year</strong> of Bachelor’s education in India may qualify for direct subject-restricted admission.</p>
            </div>
            <div class="pillar-card animate-on-scroll delay-2">
              <div class="pillar-num" style="background:rgba(245, 158, 11, 0.08); color:var(--neon-orange);"><i class="fa-solid fa-bridge"></i></div>
              <h4>Studienkolleg Pathway</h4>
              <p>For students with <strong>70%+ in Class XII</strong> but <strong>no prior university year</strong> completed, we assist with admissions into preparatory foundation pathways.</p>
            </div>
          </div>
        </div>
      </section>

      <!-- MODULE: WORK & RESIDENCE BLUEPRINT -->
      <section class="section">
        <div class="container">
          <div class="section__header animate-on-scroll">
            <span class="section__tag">The 2026 Career Framework</span>
            <h2 class="section__title">The “Work & Residence” <span>Blueprint</span></h2>
            <div class="accent-bar"></div>
          </div>
          <div class="grid grid--2 gap--4">
            <div class="info-card animate-on-scroll">
              <div class="ic-header"><div class="icon-colorful icon-colorful--blue" style="font-size:1.5rem; margin-right:.75rem"><i class="fa-solid fa-clock"></i></div> <h4>Part-time Work Rights</h4></div>
              <div class="ic-body">
                <ul class="benefit-list" style="margin-top: 1rem;">
                  <li><i class="fa-solid fa-calendar-day text-success"></i> Students can work 140 full days per year</li>
                  <li><i class="fa-solid fa-calendar-check text-success"></i> Or 280 half days per year</li>
                </ul>
              </div>
            </div>
            <div class="info-card animate-on-scroll delay-1">
              <div class="ic-header"><div class="icon-colorful icon-colorful--orange" style="font-size:1.5rem; margin-right:.75rem"><i class="fa-solid fa-coins"></i></div> <h4>Minimum Wage</h4></div>
              <div class="ic-body">
                <p style="font-weight: 600; margin-bottom: 0.5rem;">Highly competitive standards:</p>
                <ul class="benefit-list">
                  <li><i class="fa-solid fa-money-bill-wave text-success"></i> €12.41/hr average</li>
                  <li><i class="fa-solid fa-wallet text-success"></i> Potential earnings up to €1,100/month</li>
                </ul>
              </div>
            </div>
            <div class="info-card animate-on-scroll delay-2">
              <div class="ic-header"><div class="icon-colorful icon-colorful--purple" style="font-size:1.5rem; margin-right:.75rem"><i class="fa-solid fa-id-card"></i></div> <h4>EU Blue Card Advantage</h4></div>
              <div class="ic-body">
                <p style="font-weight: 600; margin-bottom: 0.5rem;">For skilled graduates above the salary threshold:</p>
                <ul class="benefit-list">
                  <li><i class="fa-solid fa-forward-fast text-success"></i> Faster Permanent Residency pathways</li>
                  <li><i class="fa-solid fa-earth-europe text-success"></i> Long-term European mobility</li>
                </ul>
              </div>
            </div>
            <div class="info-card animate-on-scroll delay-3">
              <div class="ic-header"><div class="icon-colorful icon-colorful--green" style="font-size:1.5rem; margin-right:.75rem"><i class="fa-solid fa-house-circle-check"></i></div> <h4>Post-Study Permanent Residency</h4></div>
              <div class="ic-body">
                <ul class="benefit-list" style="margin-top: 1rem;">
                  <li><i class="fa-solid fa-calendar-plus text-success"></i> Apply for PR after just 2 years</li>
                  <li><i class="fa-solid fa-user-tie text-success"></i> Required: Employment related to your degree field</li>
                </ul>
              </div>
            </div>
          </div>
        </div>
      </section>

      <!-- MODULE: FEES STRUCTURE -->
      <section class="section" style="background:#f8fafc; border-top:1px solid #e2e8f0;">
        <div class="container">
          <div class="section__header animate-on-scroll">
            <span class="section__tag">Investment</span>
            <h2 class="section__title">Average <span>Fees Structure</span></h2>
            <div class="accent-bar"></div>
          </div>
          <div class="stayback-container animate-on-scroll">
            <table class="stayback-table">
                <thead>
                    <tr>
                        <th>Program Type</th>
                        <th>Average Tuition Fee</th>
                    </tr>
                </thead>
                <tbody>
                    <tr><td style="font-weight:700;">Public Universities</td><td>€0 – €500 per semester</td></tr>
                    <tr><td style="font-weight:700;">Private Universities</td><td>€10,000 – €25,000 per year</td></tr>
                    <tr><td style="font-weight:700;">MBA Programs</td><td>€15,000 – €40,000</td></tr>
                    <tr><td style="font-weight:700;">Semester Contribution Fee</td><td>€150 – €400</td></tr>
                </tbody>
            </table>
          </div>
        </div>
      </section>

      <!-- MODULE: STUDY OPTIONS & WORK -->
      <section class="section">
        <div class="container">
          <div class="grid grid--2 gap--4">
            <div class="animate-on-scroll">
              <h3 style="font-size:1.5rem; color:#0f172a; margin-bottom:1.5rem; font-weight:800;"><i class="fa-solid fa-book-open" style="color:var(--neon-blue); margin-right:0.5rem;"></i> Available Study Pathways</h3>
              <div class="career-flex">
                <span class="career-pill"><i class="fa-solid fa-circle-check" style="color:var(--neon-blue);"></i> Public University Programs</span>
                <span class="career-pill"><i class="fa-solid fa-circle-check" style="color:var(--neon-blue);"></i> Private University Programs</span>
                <span class="career-pill"><i class="fa-solid fa-circle-check" style="color:var(--neon-blue);"></i> Bachelor’s & Master's Degrees</span>
                <span class="career-pill"><i class="fa-solid fa-circle-check" style="color:var(--neon-blue);"></i> Studienkolleg Programs</span>
                <span class="career-pill"><i class="fa-solid fa-circle-check" style="color:var(--neon-blue);"></i> Research & PhD Programs</span>
              </div>
            </div>
            <div class="animate-on-scroll delay-1">
              <h3 style="font-size:1.5rem; color:#0f172a; margin-bottom:1.5rem; font-weight:800;"><i class="fa-solid fa-briefcase" style="color:var(--neon-orange); margin-right:0.5rem;"></i> Work While Studying</h3>
              <div class="highlight-box bg-dots" style="background: white; border: 1px solid #e2e8f0; border-radius: 20px; padding: 2rem;">
                <p style="color:var(--gray); line-height:1.6; font-size:1.05rem;">International students can work <strong>140 full days</strong> or <strong>280 half days</strong> annually, gain internships with top German industries, and access strong practical and research-based learning opportunities.</p>
              </div>
            </div>
          </div>
        </div>
      </section>

      <!-- MODULE: CLIMATE & FOOD -->
      <section class="section" style="background:#f8fafc; border-top:1px solid #e2e8f0;">
        <div class="container">
          <div class="grid grid--2 gap--4">
            <div class="animate-on-scroll">
              <h3 style="font-size:1.5rem; color:#0f172a; margin-bottom:1.5rem; font-weight:800;"><i class="fa-solid fa-snowflake" style="color:var(--neon-blue); margin-right:0.5rem;"></i> Climate in Germany</h3>
              <ul class="benefit-list" style="margin-bottom: 2rem;">
                <li><i class="fa-solid fa-temperature-arrow-down" style="color:var(--neon-blue);"></i> Cold winters</li>
                <li><i class="fa-solid fa-sun" style="color:var(--neon-orange);"></i> Pleasant summers</li>
                <li><i class="fa-solid fa-cloud-rain" style="color:var(--neon-blue);"></i> Moderate rainfall</li>
                <li><i class="fa-solid fa-leaf" style="color:var(--neon-green);"></i> Seasonal climate changes across regions</li>
              </ul>
            </div>
            <div class="animate-on-scroll delay-1">
              <h3 style="font-size:1.5rem; color:#0f172a; margin-bottom:1.5rem; font-weight:800;"><i class="fa-solid fa-utensils" style="color:var(--neon-green); margin-right:0.5rem;"></i> Food & Cuisine</h3>
              <p style="color:var(--gray); margin-bottom:1.5rem; line-height:1.6;">Indian students can easily access:</p>
              <ul class="benefit-list">
                <li><i class="fa-solid fa-store" style="color:var(--neon-green);"></i> Indian restaurants and grocery stores</li>
                <li><i class="fa-solid fa-leaf" style="color:var(--neon-green);"></i> Vegetarian and vegan food options</li>
                <li><i class="fa-solid fa-pepper-hot" style="color:var(--neon-green);"></i> South Indian and North Indian cuisine</li>
                <li><i class="fa-solid fa-pizza-slice" style="color:var(--neon-green);"></i> International and European food varieties</li>
              </ul>
            </div>
          </div>
        </div>
      </section>

      <!-- MODULE: ALUMNI SUCCESS -->
      <section class="section">
        <div class="container">
          <div class="section__header animate-on-scroll">
            <span class="section__tag">Global Network</span>
            <h2 class="section__title">Alumni <span>Success</span></h2>
            <p style="color:var(--gray); margin-top: 0.5rem;">German graduates are highly valued worldwide for technical expertise, practical knowledge, and strong research exposure.</p>
            <div class="accent-bar"></div>
          </div>
          <div class="roi-grid" style="grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));">
            <div class="roi-card animate-on-scroll" style="text-align:center; padding:1.5rem;">
              <div class="icon-colorful icon-colorful--orange" style="margin:0 auto 1rem; width:48px; height:48px; font-size:1.25rem;"><i class="fa-solid fa-helmet-safety"></i></div>
              <h4 style="font-size:1.1rem; color:#0f172a; margin:0; font-weight:700;">Engineering</h4>
            </div>
            <div class="roi-card animate-on-scroll delay-1" style="text-align:center; padding:1.5rem;">
              <div class="icon-colorful icon-colorful--blue" style="margin:0 auto 1rem; width:48px; height:48px; font-size:1.25rem;"><i class="fa-solid fa-car"></i></div>
              <h4 style="font-size:1.1rem; color:#0f172a; margin:0; font-weight:700;">Automotive</h4>
            </div>
            <div class="roi-card animate-on-scroll delay-2" style="text-align:center; padding:1.5rem;">
              <div class="icon-colorful icon-colorful--purple" style="margin:0 auto 1rem; width:48px; height:48px; font-size:1.25rem;"><i class="fa-solid fa-flask"></i></div>
              <h4 style="font-size:1.1rem; color:#0f172a; margin:0; font-weight:700;">Research</h4>
            </div>
            <div class="roi-card animate-on-scroll delay-3" style="text-align:center; padding:1.5rem;">
              <div class="icon-colorful icon-colorful--teal" style="margin:0 auto 1rem; width:48px; height:48px; font-size:1.25rem;"><i class="fa-solid fa-stethoscope"></i></div>
              <h4 style="font-size:1.1rem; color:#0f172a; margin:0; font-weight:700;">Healthcare</h4>
            </div>
            <div class="roi-card animate-on-scroll delay-4" style="text-align:center; padding:1.5rem;">
              <div class="icon-colorful icon-colorful--green" style="margin:0 auto 1rem; width:48px; height:48px; font-size:1.25rem;"><i class="fa-solid fa-microchip"></i></div>
              <h4 style="font-size:1.1rem; color:#0f172a; margin:0; font-weight:700;">AI & Tech</h4>
            </div>
            <div class="roi-card animate-on-scroll delay-5" style="text-align:center; padding:1.5rem;">
              <div class="icon-colorful icon-colorful--blue" style="margin:0 auto 1rem; width:48px; height:48px; font-size:1.25rem; background:rgba(14,165,233,0.1); color:#0ea5e9;"><i class="fa-solid fa-leaf"></i></div>
              <h4 style="font-size:1.1rem; color:#0f172a; margin:0; font-weight:700;">Renewables</h4>
            </div>
          </div>
        </div>
      </section>
      <?php elseif ($country_slug === 'ireland'): ?>

      <!-- MODULE: THE 2026 STRATEGIC ADVANTAGE -->
      <section class="section" style="background:#f8fafc; border-top:1px solid #e2e8f0;">
        <div class="container">
          <div class="section__header animate-on-scroll">
            <span class="section__tag">The Silicon Valley of Europe</span>
            <h2 class="section__title">The 2026 <span>Strategic Advantage</span></h2>
            <p style="color:var(--gray); margin-top: 0.5rem;">"Ireland is now Europe’s preferred innovation hub." We focus on “Industry-Aligned” programs connecting you to Dublin and Cork’s tech and pharmaceutical ecosystems.</p>
            <div class="accent-bar"></div>
          </div>
          <div class="grid grid--3 gap--4">
            <div class="pillar-card animate-on-scroll">
              <div class="pillar-num" style="background:rgba(139, 92, 246, 0.08); color:var(--neon-purple);"><i class="fa-solid fa-earth-europe"></i></div>
              <h4>The Brexit Advantage</h4>
              <p>The strongest English-speaking gateway into the EU:<br>
                 <i class="fa-solid fa-check text-success"></i> Access to EU job markets<br>
                 <i class="fa-solid fa-check text-success"></i> Multinational firm exposure</p>
            </div>
            <div class="pillar-card animate-on-scroll delay-1">
              <div class="pillar-num" style="background:rgba(16, 185, 129, 0.08); color:var(--neon-green);"><i class="fa-solid fa-briefcase"></i></div>
              <h4>High Graduate Employability</h4>
              <p>Maintaining <strong>90%+ employability</strong> rates with strong demand in Data Science, Biotech, Fintech, AI, and Pharma.</p>
            </div>
            <div class="pillar-card animate-on-scroll delay-2">
              <div class="pillar-num" style="background:rgba(245, 158, 11, 0.08); color:var(--neon-orange);"><i class="fa-solid fa-shield-heart"></i></div>
              <h4>Cultural Safety</h4>
              <p>Consistently ranked among the world’s safest countries. Enjoy a welcoming community and strong South Indian student networks.</p>
            </div>
          </div>
        </div>
      </section>

      <!-- MODULE: STAMP 1G FRAMEWORK -->
      <section class="section">
        <div class="container">
          <div class="section__header animate-on-scroll">
            <span class="section__tag">The 2026 Stay-back Pathway</span>
            <h2 class="section__title">The “Stamp 1G” <span>Career Framework</span></h2>
            <div class="accent-bar"></div>
          </div>
          <div class="grid grid--2 gap--4">
            <div class="info-card animate-on-scroll">
              <div class="ic-header"><div class="icon-colorful icon-colorful--blue" style="font-size:1.5rem; margin-right:.75rem"><i class="fa-solid fa-graduation-cap"></i></div> <h4>Third Level Graduate Scheme</h4></div>
              <div class="ic-body">
                <p style="font-weight: 600; margin-bottom: 0.5rem;">For NFQ Level 9 (Master’s) & Level 10 (PhD):</p>
                <ul class="benefit-list">
                  <li><i class="fa-solid fa-file-contract text-success"></i> 24 Months of Stamp 1G Permission</li>
                  <li><i class="fa-solid fa-briefcase text-success"></i> Full-time work authorization after graduation</li>
                </ul>
              </div>
            </div>
            <div class="info-card animate-on-scroll delay-1">
              <div class="ic-header"><div class="icon-colorful icon-colorful--orange" style="font-size:1.5rem; margin-right:.75rem"><i class="fa-solid fa-clock"></i></div> <h4>Student Work Rights</h4></div>
              <div class="ic-body">
                <ul class="benefit-list" style="margin-top: 1rem;">
                  <li><i class="fa-solid fa-business-time text-success"></i> 20 hours/week during academic sessions</li>
                  <li><i class="fa-solid fa-calendar-check text-success"></i> 40 hours/week during holidays</li>
                </ul>
              </div>
            </div>
            <div class="info-card animate-on-scroll delay-2">
              <div class="ic-header"><div class="icon-colorful icon-colorful--purple" style="font-size:1.5rem; margin-right:.75rem"><i class="fa-solid fa-id-card-clip"></i></div> <h4>Critical Skills Employment Permit</h4></div>
              <div class="ic-body">
                <p style="font-weight: 600; margin-bottom: 0.5rem;">Transition your Stamp 1G into:</p>
                <ul class="benefit-list">
                  <li><i class="fa-solid fa-briefcase text-success"></i> Long-term employment pathways</li>
                  <li><i class="fa-solid fa-house-circle-check text-success"></i> Permanent Residency eligibility in Ireland</li>
                </ul>
              </div>
            </div>
            <div class="info-card animate-on-scroll delay-3">
              <div class="ic-header"><div class="icon-colorful icon-colorful--green" style="font-size:1.5rem; margin-right:.75rem"><i class="fa-solid fa-coins"></i></div> <h4>Minimum Wage Advantage</h4></div>
              <div class="ic-body">
                <ul class="benefit-list" style="margin-top: 1rem;">
                  <li><i class="fa-solid fa-money-bill-wave text-success"></i> 2026 minimum wage: €14.15/hr</li>
                  <li><i class="fa-solid fa-wallet text-success"></i> Earn approx. €1,100/month part-time</li>
                </ul>
              </div>
            </div>
          </div>
        </div>
      </section>

      <!-- MODULE: FEES STRUCTURE -->
      <section class="section" style="background:#f8fafc; border-top:1px solid #e2e8f0;">
        <div class="container">
          <div class="section__header animate-on-scroll">
            <span class="section__tag">Investment</span>
            <h2 class="section__title">Average <span>Fees Structure</span></h2>
            <div class="accent-bar"></div>
          </div>
          <div class="stayback-container animate-on-scroll">
            <table class="stayback-table">
                <thead>
                    <tr>
                        <th>Program Type</th>
                        <th>Average Annual Tuition Fee</th>
                    </tr>
                </thead>
                <tbody>
                    <tr><td style="font-weight:700;">Undergraduate Programs</td><td>€10,000 – €20,000</td></tr>
                    <tr><td style="font-weight:700;">Master’s Programs</td><td>€12,000 – €25,000</td></tr>
                    <tr><td style="font-weight:700;">MBA Programs</td><td>€20,000 – €35,000</td></tr>
                    <tr><td style="font-weight:700;">Professional Certifications</td><td>€5,000 – €12,000</td></tr>
                </tbody>
            </table>
          </div>
        </div>
      </section>

      <!-- MODULE: STUDY OPTIONS & WORK -->
      <section class="section">
        <div class="container">
          <div class="grid grid--2 gap--4">
            <div class="animate-on-scroll">
              <h3 style="font-size:1.5rem; color:#0f172a; margin-bottom:1.5rem; font-weight:800;"><i class="fa-solid fa-book-open" style="color:var(--neon-blue); margin-right:0.5rem;"></i> Available Study Pathways</h3>
              <div class="career-flex">
                <span class="career-pill"><i class="fa-solid fa-circle-check" style="color:var(--neon-blue);"></i> Undergraduate Degrees</span>
                <span class="career-pill"><i class="fa-solid fa-circle-check" style="color:var(--neon-blue);"></i> Master’s Programs</span>
                <span class="career-pill"><i class="fa-solid fa-circle-check" style="color:var(--neon-blue);"></i> Research & PhD Programs</span>
                <span class="career-pill"><i class="fa-solid fa-circle-check" style="color:var(--neon-blue);"></i> Professional Certifications</span>
                <span class="career-pill"><i class="fa-solid fa-circle-check" style="color:var(--neon-blue);"></i> Industry Placement Programs</span>
                <span class="career-pill"><i class="fa-solid fa-circle-check" style="color:var(--neon-blue);"></i> STEM & Business Courses</span>
              </div>
            </div>
            <div class="animate-on-scroll delay-1">
              <h3 style="font-size:1.5rem; color:#0f172a; margin-bottom:1.5rem; font-weight:800;"><i class="fa-solid fa-briefcase" style="color:var(--neon-orange); margin-right:0.5rem;"></i> Work While Studying</h3>
              <div class="highlight-box bg-dots" style="background: white; border: 1px solid #e2e8f0; border-radius: 20px; padding: 2rem;">
                <p style="color:var(--gray); line-height:1.6; font-size:1.05rem;">International students can work <strong>20 hours/week</strong> during academic sessions and <strong>40 hours/week</strong> during holidays, easily accessing internships with multinational companies.</p>
              </div>
            </div>
          </div>
        </div>
      </section>

      <!-- MODULE: CLIMATE & FOOD -->
      <section class="section" style="background:#f8fafc; border-top:1px solid #e2e8f0;">
        <div class="container">
          <div class="grid grid--2 gap--4">
            <div class="animate-on-scroll">
              <h3 style="font-size:1.5rem; color:#0f172a; margin-bottom:1.5rem; font-weight:800;"><i class="fa-solid fa-cloud-sun-rain" style="color:var(--neon-blue); margin-right:0.5rem;"></i> Climate in Ireland</h3>
              <ul class="benefit-list" style="margin-bottom: 2rem;">
                <li><i class="fa-solid fa-temperature-arrow-down" style="color:var(--neon-blue);"></i> Mild winters</li>
                <li><i class="fa-solid fa-temperature-arrow-up" style="color:var(--neon-orange);"></i> Cool summers</li>
                <li><i class="fa-solid fa-cloud-showers-heavy" style="color:var(--neon-blue);"></i> Frequent rainfall</li>
                <li><i class="fa-solid fa-temperature-half" style="color:var(--neon-purple);"></i> Pleasant year-round temperatures</li>
              </ul>
            </div>
            <div class="animate-on-scroll delay-1">
              <h3 style="font-size:1.5rem; color:#0f172a; margin-bottom:1.5rem; font-weight:800;"><i class="fa-solid fa-utensils" style="color:var(--neon-green); margin-right:0.5rem;"></i> Food & Cuisine</h3>
              <p style="color:var(--gray); margin-bottom:1.5rem; line-height:1.6;">Indian students can easily access:</p>
              <ul class="benefit-list">
                <li><i class="fa-solid fa-store" style="color:var(--neon-green);"></i> Indian restaurants and grocery stores</li>
                <li><i class="fa-solid fa-leaf" style="color:var(--neon-green);"></i> Vegetarian and vegan food options</li>
                <li><i class="fa-solid fa-pepper-hot" style="color:var(--neon-green);"></i> South Indian and North Indian cuisine</li>
                <li><i class="fa-solid fa-pizza-slice" style="color:var(--neon-green);"></i> International and European food varieties</li>
              </ul>
            </div>
          </div>
        </div>
      </section>

      <!-- MODULE: ALUMNI SUCCESS -->
      <section class="section">
        <div class="container">
          <div class="section__header animate-on-scroll">
            <span class="section__tag">Global Network</span>
            <h2 class="section__title">Alumni <span>Success</span></h2>
            <p style="color:var(--gray); margin-top: 0.5rem;">Graduates benefit from strong employer networks, EU exposure, and excellent career opportunities with multinational companies.</p>
            <div class="accent-bar"></div>
          </div>
          <div class="roi-grid" style="grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));">
            <div class="roi-card animate-on-scroll" style="text-align:center; padding:1.5rem;">
              <div class="icon-colorful icon-colorful--blue" style="margin:0 auto 1rem; width:48px; height:48px; font-size:1.25rem;"><i class="fa-solid fa-laptop-code"></i></div>
              <h4 style="font-size:1.1rem; color:#0f172a; margin:0; font-weight:700;">Technology</h4>
            </div>
            <div class="roi-card animate-on-scroll delay-1" style="text-align:center; padding:1.5rem;">
              <div class="icon-colorful icon-colorful--teal" style="margin:0 auto 1rem; width:48px; height:48px; font-size:1.25rem;"><i class="fa-solid fa-pills"></i></div>
              <h4 style="font-size:1.1rem; color:#0f172a; margin:0; font-weight:700;">Pharmaceuticals</h4>
            </div>
            <div class="roi-card animate-on-scroll delay-2" style="text-align:center; padding:1.5rem;">
              <div class="icon-colorful icon-colorful--purple" style="margin:0 auto 1rem; width:48px; height:48px; font-size:1.25rem;"><i class="fa-solid fa-flask"></i></div>
              <h4 style="font-size:1.1rem; color:#0f172a; margin:0; font-weight:700;">Research</h4>
            </div>
            <div class="roi-card animate-on-scroll delay-3" style="text-align:center; padding:1.5rem;">
              <div class="icon-colorful icon-colorful--green" style="margin:0 auto 1rem; width:48px; height:48px; font-size:1.25rem;"><i class="fa-solid fa-microchip"></i></div>
              <h4 style="font-size:1.1rem; color:#0f172a; margin:0; font-weight:700;">AI</h4>
            </div>
            <div class="roi-card animate-on-scroll delay-4" style="text-align:center; padding:1.5rem;">
              <div class="icon-colorful icon-colorful--orange" style="margin:0 auto 1rem; width:48px; height:48px; font-size:1.25rem;"><i class="fa-solid fa-coins"></i></div>
              <h4 style="font-size:1.1rem; color:#0f172a; margin:0; font-weight:700;">Finance</h4>
            </div>
            <div class="roi-card animate-on-scroll delay-5" style="text-align:center; padding:1.5rem;">
              <div class="icon-colorful icon-colorful--orange" style="margin:0 auto 1rem; width:48px; height:48px; font-size:1.25rem; background:rgba(234,88,12,0.1); color:#ea580c;"><i class="fa-solid fa-rocket"></i></div>
              <h4 style="font-size:1.1rem; color:#0f172a; margin:0; font-weight:700;">Entrepreneurship</h4>
            </div>
          </div>
        </div>
      </section>
      <?php elseif ($country_slug === 'new-zealand'): ?>

      <!-- MODULE: THE 2026 STRATEGIC ADVANTAGE -->
      <section class="section" style="background:#f8fafc; border-top:1px solid #e2e8f0;">
        <div class="container">
          <div class="section__header animate-on-scroll">
            <span class="section__tag">The Green List Path</span>
            <h2 class="section__title">The 2026 <span>Strategic Advantage</span></h2>
            <p style="color:var(--gray); margin-top: 0.5rem;">"New Zealand is now a specialized global career hub." We focus on “Green List” occupations that align your education directly with New Zealand’s skill shortages.</p>
            <div class="accent-bar"></div>
          </div>
          <div class="grid grid--3 gap--4">
            <div class="pillar-card animate-on-scroll">
              <div class="pillar-num" style="background:rgba(139, 92, 246, 0.08); color:var(--neon-purple);"><i class="fa-solid fa-screwdriver-wrench"></i></div>
              <h4>Hands-on Learning</h4>
              <p>The "New Zealand Way" emphasizes:<br>
                 <i class="fa-solid fa-check text-success"></i> Practical learning<br>
                 <i class="fa-solid fa-check text-success"></i> Industry projects & problem solving</p>
            </div>
            <div class="pillar-card animate-on-scroll delay-1">
              <div class="pillar-num" style="background:rgba(16, 185, 129, 0.08); color:var(--neon-green);"><i class="fa-solid fa-clipboard-list"></i></div>
              <h4>The Green List Advantage</h4>
              <p>Focus on Construction, Engineering, Healthcare, and IT for <strong>faster employment pathways</strong> and potential residency advantages.</p>
            </div>
            <div class="pillar-card animate-on-scroll delay-2">
              <div class="pillar-num" style="background:rgba(245, 158, 11, 0.08); color:var(--neon-orange);"><i class="fa-solid fa-leaf"></i></div>
              <h4>Climate Leadership</h4>
              <p>New Zealand leads globally in Agricultural Technology, Renewable Energy Research, and Environmental Sustainability.</p>
            </div>
          </div>
        </div>
      </section>

      <!-- MODULE: WORK & RESIDENCE FRAMEWORK -->
      <section class="section">
        <div class="container">
          <div class="section__header animate-on-scroll">
            <span class="section__tag">The 2026 Post-Study Work Guide</span>
            <h2 class="section__title">The “Post-Study Work” <span>Framework</span></h2>
            <div class="accent-bar"></div>
          </div>
          <div class="grid grid--2 gap--4">
            <div class="info-card animate-on-scroll">
              <div class="ic-header"><div class="icon-colorful icon-colorful--blue" style="font-size:1.5rem; margin-right:.75rem"><i class="fa-solid fa-graduation-cap"></i></div> <h4>Bachelor’s & Master’s Graduates</h4></div>
              <div class="ic-body">
                <ul class="benefit-list" style="margin-top: 1rem;">
                  <li><i class="fa-solid fa-file-contract text-success"></i> Eligible for a 3-Year Post-Study Work Visa</li>
                  <li><i class="fa-solid fa-globe text-success"></i> Open work rights across all of New Zealand</li>
                </ul>
              </div>
            </div>
            <div class="info-card animate-on-scroll delay-1">
              <div class="ic-header"><div class="icon-colorful icon-colorful--orange" style="font-size:1.5rem; margin-right:.75rem"><i class="fa-solid fa-award"></i></div> <h4>Level 7 Graduate Diplomas</h4></div>
              <div class="ic-body">
                <ul class="benefit-list" style="margin-top: 1rem;">
                  <li><i class="fa-solid fa-calendar-check text-success"></i> Work visa matching the duration of study</li>
                  <li><i class="fa-solid fa-clipboard-check text-success"></i> Eligibility linked to Green List occupations</li>
                </ul>
              </div>
            </div>
            <div class="info-card animate-on-scroll delay-2">
              <div class="ic-header"><div class="icon-colorful icon-colorful--purple" style="font-size:1.5rem; margin-right:.75rem"><i class="fa-solid fa-clock"></i></div> <h4>Student Work Rights</h4></div>
              <div class="ic-body">
                <ul class="benefit-list" style="margin-top: 1rem;">
                  <li><i class="fa-solid fa-business-time text-success"></i> 20 hours/week during academic sessions</li>
                  <li><i class="fa-solid fa-calendar-plus text-success"></i> Unlimited hours during scheduled breaks</li>
                </ul>
              </div>
            </div>
            <div class="info-card animate-on-scroll delay-3">
              <div class="ic-header"><div class="icon-colorful icon-colorful--green" style="font-size:1.5rem; margin-right:.75rem"><i class="fa-solid fa-coins"></i></div> <h4>Minimum Wage Advantage</h4></div>
              <div class="ic-body">
                <ul class="benefit-list" style="margin-top: 1rem;">
                  <li><i class="fa-solid fa-money-bill-wave text-success"></i> Strong current minimum wage of $23.15/hr</li>
                  <li><i class="fa-solid fa-basket-shopping text-success"></i> Comfortably supports groceries and leisure</li>
                </ul>
              </div>
            </div>
          </div>
        </div>
      </section>

      <!-- MODULE: FEES STRUCTURE -->
      <section class="section" style="background:#f8fafc; border-top:1px solid #e2e8f0;">
        <div class="container">
          <div class="section__header animate-on-scroll">
            <span class="section__tag">Investment</span>
            <h2 class="section__title">Average <span>Fees Structure</span></h2>
            <div class="accent-bar"></div>
          </div>
          <div class="stayback-container animate-on-scroll">
            <table class="stayback-table">
                <thead>
                    <tr>
                        <th>Program Type</th>
                        <th>Average Annual Tuition Fee (NZD)</th>
                    </tr>
                </thead>
                <tbody>
                    <tr><td style="font-weight:700;">Undergraduate Programs</td><td>$25,000 – $45,000</td></tr>
                    <tr><td style="font-weight:700;">Postgraduate Programs</td><td>$20,000 – $37,000</td></tr>
                    <tr><td style="font-weight:700;">MBA Programs</td><td>$35,000 – $55,000</td></tr>
                    <tr><td style="font-weight:700;">PhD Programs</td><td>$6,500 – $9,000</td></tr>
                </tbody>
            </table>
          </div>
        </div>
      </section>

      <!-- MODULE: STUDY OPTIONS & WORK -->
      <section class="section">
        <div class="container">
          <div class="grid grid--2 gap--4">
            <div class="animate-on-scroll">
              <h3 style="font-size:1.5rem; color:#0f172a; margin-bottom:1.5rem; font-weight:800;"><i class="fa-solid fa-book-open" style="color:var(--neon-blue); margin-right:0.5rem;"></i> Available Study Pathways</h3>
              <div class="career-flex">
                <span class="career-pill"><i class="fa-solid fa-circle-check" style="color:var(--neon-blue);"></i> Diploma Programs</span>
                <span class="career-pill"><i class="fa-solid fa-circle-check" style="color:var(--neon-blue);"></i> Undergraduate Degrees</span>
                <span class="career-pill"><i class="fa-solid fa-circle-check" style="color:var(--neon-blue);"></i> Postgraduate Degrees</span>
                <span class="career-pill"><i class="fa-solid fa-circle-check" style="color:var(--neon-blue);"></i> Research & PhD Programs</span>
                <span class="career-pill"><i class="fa-solid fa-circle-check" style="color:var(--neon-blue);"></i> Vocational Education</span>
                <span class="career-pill"><i class="fa-solid fa-circle-check" style="color:var(--neon-blue);"></i> Industry Placements</span>
              </div>
            </div>
            <div class="animate-on-scroll delay-1">
              <h3 style="font-size:1.5rem; color:#0f172a; margin-bottom:1.5rem; font-weight:800;"><i class="fa-solid fa-briefcase" style="color:var(--neon-orange); margin-right:0.5rem;"></i> Work While Studying</h3>
              <div class="highlight-box bg-dots" style="background: white; border: 1px solid #e2e8f0; border-radius: 20px; padding: 2rem;">
                <p style="color:var(--gray); line-height:1.6; font-size:1.05rem;">International students can work <strong>20 hours/week</strong> during academic sessions and <strong>unlimited hours</strong> during holidays, gaining highly valued practical exposure through industry-integrated learning.</p>
              </div>
            </div>
          </div>
        </div>
      </section>

      <!-- MODULE: CLIMATE & FOOD -->
      <section class="section" style="background:#f8fafc; border-top:1px solid #e2e8f0;">
        <div class="container">
          <div class="grid grid--2 gap--4">
            <div class="animate-on-scroll">
              <h3 style="font-size:1.5rem; color:#0f172a; margin-bottom:1.5rem; font-weight:800;"><i class="fa-solid fa-cloud-sun" style="color:var(--neon-blue); margin-right:0.5rem;"></i> Climate in New Zealand</h3>
              <ul class="benefit-list" style="margin-bottom: 2rem;">
                <li><i class="fa-solid fa-temperature-half" style="color:var(--neon-blue);"></i> Mild temperatures year-round</li>
                <li><i class="fa-solid fa-sun" style="color:var(--neon-orange);"></i> Pleasant summers</li>
                <li><i class="fa-solid fa-snowflake" style="color:var(--neon-blue);"></i> Cool winters</li>
                <li><i class="fa-solid fa-leaf" style="color:var(--neon-green);"></i> Clean and eco-friendly environments</li>
              </ul>
            </div>
            <div class="animate-on-scroll delay-1">
              <h3 style="font-size:1.5rem; color:#0f172a; margin-bottom:1.5rem; font-weight:800;"><i class="fa-solid fa-utensils" style="color:var(--neon-green); margin-right:0.5rem;"></i> Food & Cuisine</h3>
              <p style="color:var(--gray); margin-bottom:1.5rem; line-height:1.6;">Indian students can easily access:</p>
              <ul class="benefit-list">
                <li><i class="fa-solid fa-store" style="color:var(--neon-green);"></i> Indian restaurants and grocery stores</li>
                <li><i class="fa-solid fa-leaf" style="color:var(--neon-green);"></i> Vegetarian and vegan food options</li>
                <li><i class="fa-solid fa-pepper-hot" style="color:var(--neon-green);"></i> South Indian and North Indian cuisine</li>
                <li><i class="fa-solid fa-earth-oceania" style="color:var(--neon-green);"></i> International and multicultural dining</li>
              </ul>
            </div>
          </div>
        </div>
      </section>

      <!-- MODULE: ALUMNI SUCCESS -->
      <section class="section">
        <div class="container">
          <div class="section__header animate-on-scroll">
            <span class="section__tag">Global Network</span>
            <h2 class="section__title">Alumni <span>Success</span></h2>
            <p style="color:var(--gray); margin-top: 0.5rem;">Graduates are recognized globally for practical skills, research exposure, and industry readiness.</p>
            <div class="accent-bar"></div>
          </div>
          <div class="roi-grid" style="grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));">
            <div class="roi-card animate-on-scroll" style="text-align:center; padding:1.5rem;">
              <div class="icon-colorful icon-colorful--blue" style="margin:0 auto 1rem; width:48px; height:48px; font-size:1.25rem;"><i class="fa-solid fa-laptop-code"></i></div>
              <h4 style="font-size:1.1rem; color:#0f172a; margin:0; font-weight:700;">Technology</h4>
            </div>
            <div class="roi-card animate-on-scroll delay-1" style="text-align:center; padding:1.5rem;">
              <div class="icon-colorful icon-colorful--teal" style="margin:0 auto 1rem; width:48px; height:48px; font-size:1.25rem;"><i class="fa-solid fa-stethoscope"></i></div>
              <h4 style="font-size:1.1rem; color:#0f172a; margin:0; font-weight:700;">Healthcare</h4>
            </div>
            <div class="roi-card animate-on-scroll delay-2" style="text-align:center; padding:1.5rem;">
              <div class="icon-colorful icon-colorful--green" style="margin:0 auto 1rem; width:48px; height:48px; font-size:1.25rem;"><i class="fa-solid fa-leaf"></i></div>
              <h4 style="font-size:1.1rem; color:#0f172a; margin:0; font-weight:700;">Sustainability</h4>
            </div>
            <div class="roi-card animate-on-scroll delay-3" style="text-align:center; padding:1.5rem;">
              <div class="icon-colorful icon-colorful--purple" style="margin:0 auto 1rem; width:48px; height:48px; font-size:1.25rem;"><i class="fa-solid fa-flask"></i></div>
              <h4 style="font-size:1.1rem; color:#0f172a; margin:0; font-weight:700;">Research</h4>
            </div>
            <div class="roi-card animate-on-scroll delay-4" style="text-align:center; padding:1.5rem;">
              <div class="icon-colorful icon-colorful--orange" style="margin:0 auto 1rem; width:48px; height:48px; font-size:1.25rem;"><i class="fa-solid fa-seedling"></i></div>
              <h4 style="font-size:1.1rem; color:#0f172a; margin:0; font-weight:700;">Agriculture</h4>
            </div>
            <div class="roi-card animate-on-scroll delay-5" style="text-align:center; padding:1.5rem;">
              <div class="icon-colorful icon-colorful--orange" style="margin:0 auto 1rem; width:48px; height:48px; font-size:1.25rem; background:rgba(234,88,12,0.1); color:#ea580c;"><i class="fa-solid fa-lightbulb"></i></div>
              <h4 style="font-size:1.1rem; color:#0f172a; margin:0; font-weight:700;">Innovation</h4>
            </div>
          </div>
        </div>
      </section>
      <?php elseif ($country_slug === 'singapore'): ?>

      <!-- MODULE: THE 2026 STRATEGIC ADVANTAGE -->
      <section class="section" style="background:#f8fafc; border-top:1px solid #e2e8f0;">
        <div class="container">
          <div class="section__header animate-on-scroll">
            <span class="section__tag">The Tuition Grant Path</span>
            <h2 class="section__title">The 2026 <span>Strategic Advantage</span></h2>
            <p style="color:var(--gray); margin-top: 0.5rem;">"Singapore offers a unique Work-for-Study advantage." Gain significant tuition subsidies and long-term career growth in Asia’s strongest economy.</p>
            <div class="accent-bar"></div>
          </div>
          <div class="grid grid--3 gap--4">
            <div class="pillar-card animate-on-scroll">
              <div class="pillar-num" style="background:rgba(139, 92, 246, 0.08); color:var(--neon-purple);"><i class="fa-solid fa-file-signature"></i></div>
              <h4>The MOE Tuition Grant</h4>
              <p>Tuition fees are heavily subsidized. Graduates commit to working in Singapore for 3 years, gaining immediate global work exposure and a strong ROI.</p>
            </div>
            <div class="pillar-card animate-on-scroll delay-1">
              <div class="pillar-num" style="background:rgba(16, 185, 129, 0.08); color:var(--neon-green);"><i class="fa-solid fa-plane"></i></div>
              <h4>Proximity to Home</h4>
              <p>Just a <strong>4-hour flight</strong> from Chennai or Trichy, providing easier travel access for families without sacrificing global exposure.</p>
            </div>
            <div class="pillar-card animate-on-scroll delay-2">
              <div class="pillar-num" style="background:rgba(245, 158, 11, 0.08); color:var(--neon-orange);"><i class="fa-solid fa-earth-asia"></i></div>
              <h4>Multicultural Advantage</h4>
              <p>Enjoy an English-medium education, strong Indian communities, and unparalleled business exposure in Asia's financial hub.</p>
            </div>
          </div>
        </div>
      </section>

      <!-- MODULE: COMPASS FRAMEWORK -->
      <section class="section">
        <div class="container">
          <div class="section__header animate-on-scroll">
            <span class="section__tag">The 2026 Post-Graduation Pathway</span>
            <h2 class="section__title">The “COMPASS” <span>Career Framework</span></h2>
            <div class="accent-bar"></div>
          </div>
          <div class="grid grid--2 gap--4">
            <div class="info-card animate-on-scroll">
              <div class="ic-header"><div class="icon-colorful icon-colorful--blue" style="font-size:1.5rem; margin-right:.75rem"><i class="fa-solid fa-envelope-open-text"></i></div> <h4>The IPA Letter</h4></div>
              <div class="ic-body">
                <ul class="benefit-list" style="margin-top: 1rem;">
                  <li><i class="fa-solid fa-plane-arrival text-success"></i> Functions as your initial entry authorization</li>
                  <li><i class="fa-solid fa-check-double text-success"></i> Pre-approved pass clearance before arrival</li>
                </ul>
              </div>
            </div>
            <div class="info-card animate-on-scroll delay-1">
              <div class="ic-header"><div class="icon-colorful icon-colorful--orange" style="font-size:1.5rem; margin-right:.75rem"><i class="fa-solid fa-briefcase"></i></div> <h4>Employment Pass (EP)</h4></div>
              <div class="ic-body">
                <p style="font-weight: 600; margin-bottom: 0.5rem;">2026 Minimum Salary Thresholds:</p>
                <ul class="benefit-list">
                  <li><i class="fa-solid fa-money-bill-wave text-success"></i> SGD $6,000/month standard</li>
                  <li><i class="fa-solid fa-building-columns text-success"></i> SGD $6,600/month for Financial Services</li>
                </ul>
              </div>
            </div>
            <div class="info-card animate-on-scroll delay-2">
              <div class="ic-header"><div class="icon-colorful icon-colorful--purple" style="font-size:1.5rem; margin-right:.75rem"><i class="fa-solid fa-chart-pie"></i></div> <h4>The COMPASS System</h4></div>
              <div class="ic-body">
                <p style="font-weight: 600; margin-bottom: 0.5rem;">Points-based EP framework evaluating:</p>
                <ul class="benefit-list">
                  <li><i class="fa-solid fa-scale-balanced text-success"></i> Salary, Qualifications, and Diversity</li>
                  <li><i class="fa-solid fa-graduation-cap text-success"></i> Strong advantages for NUS and NTU graduates</li>
                </ul>
              </div>
            </div>
            <div class="info-card animate-on-scroll delay-3">
              <div class="ic-header"><div class="icon-colorful icon-colorful--green" style="font-size:1.5rem; margin-right:.75rem"><i class="fa-solid fa-id-card"></i></div> <h4>S-Pass & LTVP</h4></div>
              <div class="ic-body">
                <ul class="benefit-list" style="margin-top: 1rem;">
                  <li><i class="fa-solid fa-user-gear text-success"></i> S-Pass starts around SGD $3,600/month</li>
                  <li><i class="fa-solid fa-calendar-plus text-success"></i> Local grads may qualify for 1-Year LTVP</li>
                </ul>
              </div>
            </div>
          </div>
        </div>
      </section>

      <!-- MODULE: FEES STRUCTURE -->
      <section class="section" style="background:#f8fafc; border-top:1px solid #e2e8f0;">
        <div class="container">
          <div class="section__header animate-on-scroll">
            <span class="section__tag">Investment</span>
            <h2 class="section__title">Average <span>Fees Structure</span></h2>
            <div class="accent-bar"></div>
          </div>
          <div class="stayback-container animate-on-scroll">
            <table class="stayback-table">
                <thead>
                    <tr>
                        <th>Program Type</th>
                        <th>Average Annual Tuition Fee (SGD)</th>
                    </tr>
                </thead>
                <tbody>
                    <tr><td style="font-weight:700;">Undergraduate Programs</td><td>$18,000 – $40,000</td></tr>
                    <tr><td style="font-weight:700;">Postgraduate Programs</td><td>$20,000 – $50,000</td></tr>
                    <tr><td style="font-weight:700;">MBA Programs</td><td>$35,000 – $80,000</td></tr>
                    <tr><td style="font-weight:700;">Private Institutions</td><td>$15,000 – $30,000</td></tr>
                </tbody>
            </table>
          </div>
        </div>
      </section>

      <!-- MODULE: STUDY OPTIONS & WORK -->
      <section class="section">
        <div class="container">
          <div class="grid grid--2 gap--4">
            <div class="animate-on-scroll">
              <h3 style="font-size:1.5rem; color:#0f172a; margin-bottom:1.5rem; font-weight:800;"><i class="fa-solid fa-book-open" style="color:var(--neon-blue); margin-right:0.5rem;"></i> Available Study Pathways</h3>
              <div class="career-flex">
                <span class="career-pill"><i class="fa-solid fa-circle-check" style="color:var(--neon-blue);"></i> Undergraduate Degrees</span>
                <span class="career-pill"><i class="fa-solid fa-circle-check" style="color:var(--neon-blue);"></i> Postgraduate Degrees</span>
                <span class="career-pill"><i class="fa-solid fa-circle-check" style="color:var(--neon-blue);"></i> Private Institution Programs</span>
                <span class="career-pill"><i class="fa-solid fa-circle-check" style="color:var(--neon-blue);"></i> International Partner Degrees</span>
                <span class="career-pill"><i class="fa-solid fa-circle-check" style="color:var(--neon-blue);"></i> Research Programs</span>
                <span class="career-pill"><i class="fa-solid fa-circle-check" style="color:var(--neon-blue);"></i> Professional Certifications</span>
              </div>
            </div>
            <div class="animate-on-scroll delay-1">
              <h3 style="font-size:1.5rem; color:#0f172a; margin-bottom:1.5rem; font-weight:800;"><i class="fa-solid fa-briefcase" style="color:var(--neon-orange); margin-right:0.5rem;"></i> Work While Studying</h3>
              <div class="highlight-box bg-dots" style="background: white; border: 1px solid #e2e8f0; border-radius: 20px; padding: 2rem;">
                <p style="color:var(--gray); line-height:1.6; font-size:1.05rem;">International students can work up to <strong>16 hours/week</strong> during academic terms and <strong>full-time</strong> during vacations, easily accessing prestigious internships.</p>
              </div>
            </div>
          </div>
        </div>
      </section>

      <!-- MODULE: CLIMATE & FOOD -->
      <section class="section" style="background:#f8fafc; border-top:1px solid #e2e8f0;">
        <div class="container">
          <div class="grid grid--2 gap--4">
            <div class="animate-on-scroll">
              <h3 style="font-size:1.5rem; color:#0f172a; margin-bottom:1.5rem; font-weight:800;"><i class="fa-solid fa-cloud-sun-rain" style="color:var(--neon-blue); margin-right:0.5rem;"></i> Climate in Singapore</h3>
              <ul class="benefit-list" style="margin-bottom: 2rem;">
                <li><i class="fa-solid fa-temperature-full" style="color:var(--neon-orange);"></i> Warm and humid tropical weather</li>
                <li><i class="fa-solid fa-temperature-half" style="color:var(--neon-blue);"></i> Consistent temperatures year-round</li>
                <li><i class="fa-solid fa-cloud-rain" style="color:var(--neon-blue);"></i> Frequent short rain showers</li>
                <li><i class="fa-solid fa-city" style="color:var(--neon-purple);"></i> Modern, comfortable urban living</li>
              </ul>
            </div>
            <div class="animate-on-scroll delay-1">
              <h3 style="font-size:1.5rem; color:#0f172a; margin-bottom:1.5rem; font-weight:800;"><i class="fa-solid fa-utensils" style="color:var(--neon-green); margin-right:0.5rem;"></i> Food & Cuisine</h3>
              <p style="color:var(--gray); margin-bottom:1.5rem; line-height:1.6;">Indian students can easily access:</p>
              <ul class="benefit-list">
                <li><i class="fa-solid fa-pepper-hot" style="color:var(--neon-green);"></i> South Indian and North Indian cuisine</li>
                <li><i class="fa-solid fa-leaf" style="color:var(--neon-green);"></i> Vegetarian and Jain food options</li>
                <li><i class="fa-solid fa-store" style="color:var(--neon-green);"></i> Indian grocery stores and restaurants</li>
                <li><i class="fa-solid fa-bowl-rice" style="color:var(--neon-green);"></i> Unmatched International and Asian varieties</li>
              </ul>
            </div>
          </div>
        </div>
      </section>

      <!-- MODULE: ALUMNI SUCCESS -->
      <section class="section">
        <div class="container">
          <div class="section__header animate-on-scroll">
            <span class="section__tag">Global Network</span>
            <h2 class="section__title">Alumni <span>Success</span></h2>
            <p style="color:var(--gray); margin-top: 0.5rem;">Graduates benefit from global employer networks, high-paying opportunities, and exposure to Asia’s leading financial and tech ecosystem.</p>
            <div class="accent-bar"></div>
          </div>
          <div class="roi-grid" style="grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));">
            <div class="roi-card animate-on-scroll" style="text-align:center; padding:1.5rem;">
              <div class="icon-colorful icon-colorful--blue" style="margin:0 auto 1rem; width:48px; height:48px; font-size:1.25rem;"><i class="fa-solid fa-laptop-code"></i></div>
              <h4 style="font-size:1.1rem; color:#0f172a; margin:0; font-weight:700;">Technology</h4>
            </div>
            <div class="roi-card animate-on-scroll delay-1" style="text-align:center; padding:1.5rem;">
              <div class="icon-colorful icon-colorful--teal" style="margin:0 auto 1rem; width:48px; height:48px; font-size:1.25rem;"><i class="fa-solid fa-coins"></i></div>
              <h4 style="font-size:1.1rem; color:#0f172a; margin:0; font-weight:700;">Finance</h4>
            </div>
            <div class="roi-card animate-on-scroll delay-2" style="text-align:center; padding:1.5rem;">
              <div class="icon-colorful icon-colorful--green" style="margin:0 auto 1rem; width:48px; height:48px; font-size:1.25rem;"><i class="fa-solid fa-microchip"></i></div>
              <h4 style="font-size:1.1rem; color:#0f172a; margin:0; font-weight:700;">AI</h4>
            </div>
            <div class="roi-card animate-on-scroll delay-3" style="text-align:center; padding:1.5rem;">
              <div class="icon-colorful icon-colorful--purple" style="margin:0 auto 1rem; width:48px; height:48px; font-size:1.25rem;"><i class="fa-solid fa-briefcase"></i></div>
              <h4 style="font-size:1.1rem; color:#0f172a; margin:0; font-weight:700;">Business Leadership</h4>
            </div>
            <div class="roi-card animate-on-scroll delay-4" style="text-align:center; padding:1.5rem;">
              <div class="icon-colorful icon-colorful--orange" style="margin:0 auto 1rem; width:48px; height:48px; font-size:1.25rem;"><i class="fa-solid fa-flask"></i></div>
              <h4 style="font-size:1.1rem; color:#0f172a; margin:0; font-weight:700;">Research</h4>
            </div>
            <div class="roi-card animate-on-scroll delay-5" style="text-align:center; padding:1.5rem;">
              <div class="icon-colorful icon-colorful--orange" style="margin:0 auto 1rem; width:48px; height:48px; font-size:1.25rem; background:rgba(234,88,12,0.1); color:#ea580c;"><i class="fa-solid fa-rocket"></i></div>
              <h4 style="font-size:1.1rem; color:#0f172a; margin:0; font-weight:700;">Entrepreneurship</h4>
            </div>
          </div>
        </div>
      </section>
      <?php elseif ($country_slug === 'switzerland'): ?>

      <!-- MODULE: THE 2026 STRATEGIC ADVANTAGE -->
      <section class="section" style="background:#f8fafc; border-top:1px solid #e2e8f0;">
        <div class="container">
          <div class="section__header animate-on-scroll">
            <span class="section__tag">The Innovation ROI</span>
            <h2 class="section__title">The 2026 <span>Strategic Advantage</span></h2>
            <p style="color:var(--gray); margin-top: 0.5rem;">"Switzerland offers a high-value education with elite global outcomes." Maximize your ROI with extremely low public tuition and access to a high-paying economy.</p>
            <div class="accent-bar"></div>
          </div>
          <div class="grid grid--3 gap--4">
            <div class="pillar-card animate-on-scroll">
              <div class="pillar-num" style="background:rgba(139, 92, 246, 0.08); color:var(--neon-purple);"><i class="fa-solid fa-microscope"></i></div>
              <h4>Innovation Powerhouse</h4>
              <p>Gain exposure to world-class innovation ecosystems including CERN, Google’s European Engineering Hub, and the UN.</p>
            </div>
            <div class="pillar-card animate-on-scroll delay-1">
              <div class="pillar-num" style="background:rgba(16, 185, 129, 0.08); color:var(--neon-green);"><i class="fa-solid fa-crosshairs"></i></div>
              <h4>The “Precision” Curriculum</h4>
              <p>Swiss education emphasizes practical industry integration, research, and technical specialization. Average starting salaries exceed <strong>CHF 60,000</strong> annually.</p>
            </div>
            <div class="pillar-card animate-on-scroll delay-2">
              <div class="pillar-num" style="background:rgba(245, 158, 11, 0.08); color:var(--neon-orange);"><i class="fa-solid fa-language"></i></div>
              <h4>Multilingual Advantage</h4>
              <p>Programs are in English, but you can learn German, French, or Italian, strengthening careers in diplomacy, luxury management, and international business.</p>
            </div>
          </div>
        </div>
      </section>

      <!-- MODULE: B-PERMIT FRAMEWORK -->
      <section class="section">
        <div class="container">
          <div class="section__header animate-on-scroll">
            <span class="section__tag">The 2026 Swiss Residence & Work Blueprint</span>
            <h2 class="section__title">The “B-Permit” & <span>Work Framework</span></h2>
            <div class="accent-bar"></div>
          </div>
          <div class="grid grid--2 gap--4">
            <div class="info-card animate-on-scroll">
              <div class="ic-header"><div class="icon-colorful icon-colorful--blue" style="font-size:1.5rem; margin-right:.75rem"><i class="fa-solid fa-id-card-clip"></i></div> <h4>Residence Permit (B Permit)</h4></div>
              <div class="ic-body">
                <ul class="benefit-list" style="margin-top: 1rem;">
                  <li><i class="fa-solid fa-calendar-check text-success"></i> Valid for the entire duration of your study</li>
                  <li><i class="fa-solid fa-map-location-dot text-success"></i> Mandatory Canton registration within 14 days</li>
                </ul>
              </div>
            </div>
            <div class="info-card animate-on-scroll delay-1">
              <div class="ic-header"><div class="icon-colorful icon-colorful--orange" style="font-size:1.5rem; margin-right:.75rem"><i class="fa-solid fa-business-time"></i></div> <h4>Part-time Work Rights</h4></div>
              <div class="ic-body">
                <p style="font-weight: 600; margin-bottom: 0.5rem; color:var(--red);">*Eligibility begins after 6 months of residency.</p>
                <ul class="benefit-list">
                  <li><i class="fa-solid fa-clock text-success"></i> Work up to 15 hours/week during terms</li>
                  <li><i class="fa-solid fa-calendar-plus text-success"></i> Work full-time during semester breaks</li>
                </ul>
              </div>
            </div>
            <div class="info-card animate-on-scroll delay-2">
              <div class="ic-header"><div class="icon-colorful icon-colorful--purple" style="font-size:1.5rem; margin-right:.75rem"><i class="fa-solid fa-magnifying-glass-chart"></i></div> <h4>Post-Study Job-Seeker Window</h4></div>
              <div class="ic-body">
                <ul class="benefit-list" style="margin-top: 1rem;">
                  <li><i class="fa-solid fa-hourglass-half text-success"></i> 6-month dedicated period to secure employment</li>
                  <li><i class="fa-solid fa-briefcase text-success"></i> May continue working 15 hours/week during this time</li>
                </ul>
              </div>
            </div>
            <div class="info-card animate-on-scroll delay-3">
              <div class="ic-header"><div class="icon-colorful icon-colorful--green" style="font-size:1.5rem; margin-right:.75rem"><i class="fa-solid fa-users-gear"></i></div> <h4>The “Local Quota” Rule</h4></div>
              <div class="ic-body">
                <p style="color:var(--gray); font-size:0.95rem;">Swiss employers must prove a specialist skill requirement, but <strong>Swiss-trained international graduates</strong> receive highly favorable consideration within the hiring framework.</p>
              </div>
            </div>
          </div>
        </div>
      </section>

      <!-- MODULE: FEES STRUCTURE -->
      <section class="section" style="background:#f8fafc; border-top:1px solid #e2e8f0;">
        <div class="container">
          <div class="section__header animate-on-scroll">
            <span class="section__tag">Investment</span>
            <h2 class="section__title">Average <span>Fees Structure</span></h2>
            <div class="accent-bar"></div>
          </div>
          <div class="stayback-container animate-on-scroll">
            <table class="stayback-table">
                <thead>
                    <tr>
                        <th>Program Type</th>
                        <th>Average Tuition Fee (CHF)</th>
                    </tr>
                </thead>
                <tbody>
                    <tr><td style="font-weight:700;">Public Universities</td><td>400 – 1,500 per semester</td></tr>
                    <tr><td style="font-weight:700;">Private Universities</td><td>20,000 – 45,000 per year</td></tr>
                    <tr><td style="font-weight:700;">Hospitality Programs</td><td>25,000 – 50,000</td></tr>
                    <tr><td style="font-weight:700;">MBA Programs</td><td>30,000 – 70,000</td></tr>
                </tbody>
            </table>
          </div>
        </div>
      </section>

      <!-- MODULE: STUDY OPTIONS & WORK -->
      <section class="section">
        <div class="container">
          <div class="grid grid--2 gap--4">
            <div class="animate-on-scroll">
              <h3 style="font-size:1.5rem; color:#0f172a; margin-bottom:1.5rem; font-weight:800;"><i class="fa-solid fa-book-open" style="color:var(--neon-blue); margin-right:0.5rem;"></i> Available Study Pathways</h3>
              <div class="career-flex">
                <span class="career-pill"><i class="fa-solid fa-circle-check" style="color:var(--neon-blue);"></i> Undergraduate Degrees</span>
                <span class="career-pill"><i class="fa-solid fa-circle-check" style="color:var(--neon-blue);"></i> Master’s Programs</span>
                <span class="career-pill"><i class="fa-solid fa-circle-check" style="color:var(--neon-blue);"></i> Research & PhD Programs</span>
                <span class="career-pill"><i class="fa-solid fa-circle-check" style="color:var(--neon-blue);"></i> Hospitality Management</span>
                <span class="career-pill"><i class="fa-solid fa-circle-check" style="color:var(--neon-blue);"></i> Business & Finance</span>
                <span class="career-pill"><i class="fa-solid fa-circle-check" style="color:var(--neon-blue);"></i> Technical Specializations</span>
              </div>
            </div>
            <div class="animate-on-scroll delay-1">
              <h3 style="font-size:1.5rem; color:#0f172a; margin-bottom:1.5rem; font-weight:800;"><i class="fa-solid fa-briefcase" style="color:var(--neon-orange); margin-right:0.5rem;"></i> Work While Studying</h3>
              <div class="highlight-box bg-dots" style="background: white; border: 1px solid #e2e8f0; border-radius: 20px; padding: 2rem;">
                <p style="color:var(--gray); line-height:1.6; font-size:1.05rem;">After 6 months of residency, international students can work <strong>15 hours/week</strong> during academic terms and <strong>full-time</strong> during breaks, accessing internships with global organizations.</p>
              </div>
            </div>
          </div>
        </div>
      </section>

      <!-- MODULE: CLIMATE & FOOD -->
      <section class="section" style="background:#f8fafc; border-top:1px solid #e2e8f0;">
        <div class="container">
          <div class="grid grid--2 gap--4">
            <div class="animate-on-scroll">
              <h3 style="font-size:1.5rem; color:#0f172a; margin-bottom:1.5rem; font-weight:800;"><i class="fa-solid fa-mountain" style="color:var(--neon-blue); margin-right:0.5rem;"></i> Climate in Switzerland</h3>
              <ul class="benefit-list" style="margin-bottom: 2rem;">
                <li><i class="fa-solid fa-snowflake" style="color:var(--neon-blue);"></i> Cold, snowy winters</li>
                <li><i class="fa-solid fa-sun" style="color:var(--neon-orange);"></i> Pleasant, beautiful summers</li>
                <li><i class="fa-solid fa-panorama" style="color:var(--neon-purple);"></i> Stunning alpine landscapes</li>
                <li><i class="fa-solid fa-leaf" style="color:var(--neon-green);"></i> Clean, environmentally friendly living</li>
              </ul>
            </div>
            <div class="animate-on-scroll delay-1">
              <h3 style="font-size:1.5rem; color:#0f172a; margin-bottom:1.5rem; font-weight:800;"><i class="fa-solid fa-utensils" style="color:var(--neon-green); margin-right:0.5rem;"></i> Food & Cuisine</h3>
              <p style="color:var(--gray); margin-bottom:1.5rem; line-height:1.6;">Indian students can easily access:</p>
              <ul class="benefit-list">
                <li><i class="fa-solid fa-store" style="color:var(--neon-green);"></i> Indian restaurants and grocery stores</li>
                <li><i class="fa-solid fa-leaf" style="color:var(--neon-green);"></i> Vegetarian and vegan food options</li>
                <li><i class="fa-solid fa-pepper-hot" style="color:var(--neon-green);"></i> South Indian and North Indian cuisine</li>
                <li><i class="fa-solid fa-cheese" style="color:var(--neon-green);"></i> Premium International & European food</li>
              </ul>
            </div>
          </div>
        </div>
      </section>

      <!-- MODULE: ALUMNI SUCCESS -->
      <section class="section">
        <div class="container">
          <div class="section__header animate-on-scroll">
            <span class="section__tag">Global Network</span>
            <h2 class="section__title">Alumni <span>Success</span></h2>
            <p style="color:var(--gray); margin-top: 0.5rem;">Graduates benefit from premium salaries, global employer recognition, and elite international networking.</p>
            <div class="accent-bar"></div>
          </div>
          <div class="roi-grid" style="grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));">
            <div class="roi-card animate-on-scroll" style="text-align:center; padding:1.5rem;">
              <div class="icon-colorful icon-colorful--blue" style="margin:0 auto 1rem; width:48px; height:48px; font-size:1.25rem;"><i class="fa-solid fa-cogs"></i></div>
              <h4 style="font-size:1.1rem; color:#0f172a; margin:0; font-weight:700;">Engineering</h4>
            </div>
            <div class="roi-card animate-on-scroll delay-1" style="text-align:center; padding:1.5rem;">
              <div class="icon-colorful icon-colorful--teal" style="margin:0 auto 1rem; width:48px; height:48px; font-size:1.25rem;"><i class="fa-solid fa-bell-concierge"></i></div>
              <h4 style="font-size:1.1rem; color:#0f172a; margin:0; font-weight:700;">Hospitality</h4>
            </div>
            <div class="roi-card animate-on-scroll delay-2" style="text-align:center; padding:1.5rem;">
              <div class="icon-colorful icon-colorful--green" style="margin:0 auto 1rem; width:48px; height:48px; font-size:1.25rem;"><i class="fa-solid fa-building-columns"></i></div>
              <h4 style="font-size:1.1rem; color:#0f172a; margin:0; font-weight:700;">Banking</h4>
            </div>
            <div class="roi-card animate-on-scroll delay-3" style="text-align:center; padding:1.5rem;">
              <div class="icon-colorful icon-colorful--purple" style="margin:0 auto 1rem; width:48px; height:48px; font-size:1.25rem;"><i class="fa-solid fa-microscope"></i></div>
              <h4 style="font-size:1.1rem; color:#0f172a; margin:0; font-weight:700;">Scientific Research</h4>
            </div>
            <div class="roi-card animate-on-scroll delay-4" style="text-align:center; padding:1.5rem;">
              <div class="icon-colorful icon-colorful--orange" style="margin:0 auto 1rem; width:48px; height:48px; font-size:1.25rem;"><i class="fa-solid fa-globe"></i></div>
              <h4 style="font-size:1.1rem; color:#0f172a; margin:0; font-weight:700;">Diplomacy</h4>
            </div>
            <div class="roi-card animate-on-scroll delay-5" style="text-align:center; padding:1.5rem;">
              <div class="icon-colorful icon-colorful--orange" style="margin:0 auto 1rem; width:48px; height:48px; font-size:1.25rem; background:rgba(234,88,12,0.1); color:#ea580c;"><i class="fa-solid fa-microchip"></i></div>
              <h4 style="font-size:1.1rem; color:#0f172a; margin:0; font-weight:700;">AI & Tech</h4>
            </div>
          </div>
        </div>
      </section>
      <?php elseif ($country_slug === 'uk'): ?>

      <!-- MODULE: THE 2026 STRATEGIC ADVANTAGE -->
      <section class="section" style="background:#f8fafc; border-top:1px solid #e2e8f0;">
        <div class="container">
          <div class="section__header animate-on-scroll">
            <span class="section__tag">The ROI Accelerator</span>
            <h2 class="section__title">The 2026 <span>Strategic Advantage</span></h2>
            <p style="color:var(--gray); margin-top: 0.5rem;">"In 2026, time is the ultimate career advantage." The UK’s accelerated education system allows you to complete globally recognized degrees faster while reducing living expenses.</p>
            <div class="accent-bar"></div>
          </div>
          <div class="grid grid--3 gap--4">
            <div class="pillar-card animate-on-scroll">
              <div class="pillar-num" style="background:rgba(139, 92, 246, 0.08); color:var(--neon-purple);"><i class="fa-solid fa-graduation-cap"></i></div>
              <h4>Early Specialization</h4>
              <p>Unlike broader systems, UK universities allow you to focus directly on your chosen specialization, building career-ready expertise from the first semester.</p>
            </div>
            <div class="pillar-card animate-on-scroll delay-1">
              <div class="pillar-num" style="background:rgba(16, 185, 129, 0.08); color:var(--neon-green);"><i class="fa-solid fa-passport"></i></div>
              <h4>Graduate Route Visa</h4>
              <p>Stay in the UK for 2 years after graduation to work full-time or search for employment, building experience in one of the world’s largest economies.</p>
            </div>
            <div class="pillar-card animate-on-scroll delay-2">
              <div class="pillar-num" style="background:rgba(245, 158, 11, 0.08); color:var(--neon-orange);"><i class="fa-solid fa-file-contract"></i></div>
              <h4>CAS Support Expertise</h4>
              <p>Our team expertly guides you in securing your Confirmation of Acceptance for Studies (CAS), the essential digital document for your Student Visa.</p>
            </div>
          </div>
        </div>
      </section>

      <!-- MODULE: GRADUATE ROUTE CAREER SAFEGUARD -->
      <section class="section">
        <div class="container">
          <div class="section__header animate-on-scroll">
            <span class="section__tag">Post-Study Work (PSW) 2026 Framework</span>
            <h2 class="section__title">The “Graduate Route” <span>Career Safeguard</span></h2>
            <div class="accent-bar"></div>
          </div>
          <div class="grid grid--2 gap--4">
            <div class="info-card animate-on-scroll">
              <div class="ic-header"><div class="icon-colorful icon-colorful--blue" style="font-size:1.5rem; margin-right:.75rem"><i class="fa-solid fa-stamp"></i></div> <h4>Graduate Visa (2 Years)</h4></div>
              <div class="ic-body">
                <ul class="benefit-list" style="margin-top: 1rem;">
                  <li><i class="fa-solid fa-file-signature text-success"></i> No job offer required to apply</li>
                  <li><i class="fa-solid fa-briefcase text-success"></i> Full-time work flexibility across sectors</li>
                </ul>
              </div>
            </div>
            <div class="info-card animate-on-scroll delay-1">
              <div class="ic-header"><div class="icon-colorful icon-colorful--orange" style="font-size:1.5rem; margin-right:.75rem"><i class="fa-solid fa-user-graduate"></i></div> <h4>PhD Graduate Advantage</h4></div>
              <div class="ic-body">
                <ul class="benefit-list" style="margin-top: 1rem;">
                  <li><i class="fa-solid fa-id-card text-success"></i> Doctoral graduates eligible for 3-Year Visa</li>
                  <li><i class="fa-solid fa-microscope text-success"></i> Extended research & employment opportunities</li>
                </ul>
              </div>
            </div>
            <div class="info-card animate-on-scroll delay-2">
              <div class="ic-header"><div class="icon-colorful icon-colorful--purple" style="font-size:1.5rem; margin-right:.75rem"><i class="fa-solid fa-users"></i></div> <h4>Dependents Policy (2026)</h4></div>
              <div class="ic-body">
                <p style="font-weight: 600; margin-bottom: 0.5rem; color:var(--red);">*Dependents permitted only for:</p>
                <ul class="benefit-list">
                  <li><i class="fa-solid fa-flask text-success"></i> Research-based Master’s programs</li>
                  <li><i class="fa-solid fa-book-journal-whills text-success"></i> PhD and doctoral programs</li>
                </ul>
              </div>
            </div>
            <div class="info-card animate-on-scroll delay-3">
              <div class="ic-header"><div class="icon-colorful icon-colorful--green" style="font-size:1.5rem; margin-right:.75rem"><i class="fa-solid fa-business-time"></i></div> <h4>Student Work Rights</h4></div>
              <div class="ic-body">
                <ul class="benefit-list" style="margin-top: 1rem;">
                  <li><i class="fa-solid fa-clock text-success"></i> 20 hours/week during academic sessions</li>
                  <li><i class="fa-solid fa-calendar-plus text-success"></i> Unlimited hours during official vacation periods</li>
                </ul>
              </div>
            </div>
          </div>
          <div class="highlight-box bg-dots animate-on-scroll delay-4" style="margin-top: 2rem; background: white; border: 1px solid #e2e8f0; border-radius: 20px; padding: 2rem;">
            <h3 style="font-size: 1.3rem; margin-bottom: 1rem;"><i class="fa-solid fa-shield-halved" style="color:var(--primary); margin-right: 0.5rem;"></i> The Bluestone Edge</h3>
            <p style="color:var(--gray); line-height:1.6; font-size:1.05rem;">We partner exclusively with universities maintaining strong compliance records and trusted <strong>UKVI sponsorship status</strong>, minimizing visa risks and maximizing student security.</p>
          </div>
        </div>
      </section>

      <!-- MODULE: FEES STRUCTURE -->
      <section class="section" style="background:#f8fafc; border-top:1px solid #e2e8f0;">
        <div class="container">
          <div class="section__header animate-on-scroll">
            <span class="section__tag">Investment</span>
            <h2 class="section__title">Average <span>Fees Structure</span></h2>
            <div class="accent-bar"></div>
          </div>
          <div class="stayback-container animate-on-scroll">
            <table class="stayback-table">
                <thead>
                    <tr>
                        <th>Program Type</th>
                        <th>Average Annual Tuition Fee (GBP)</th>
                    </tr>
                </thead>
                <tbody>
                    <tr><td style="font-weight:700;">Undergraduate Programs</td><td>£12,000 – £25,000</td></tr>
                    <tr><td style="font-weight:700;">Master’s Programs (1-Year)</td><td>£14,000 – £30,000</td></tr>
                    <tr><td style="font-weight:700;">MBA Programs</td><td>£25,000 – £60,000</td></tr>
                    <tr><td style="font-weight:700;">Medical Programs</td><td>£30,000 – £50,000</td></tr>
                </tbody>
            </table>
          </div>
        </div>
      </section>

      <!-- MODULE: STUDY OPTIONS & WORK -->
      <section class="section">
        <div class="container">
          <div class="grid grid--2 gap--4">
            <div class="animate-on-scroll">
              <h3 style="font-size:1.5rem; color:#0f172a; margin-bottom:1.5rem; font-weight:800;"><i class="fa-solid fa-book-open" style="color:var(--neon-blue); margin-right:0.5rem;"></i> Available Study Pathways</h3>
              <div class="career-flex">
                <span class="career-pill"><i class="fa-solid fa-circle-check" style="color:var(--neon-blue);"></i> Undergraduate Degrees</span>
                <span class="career-pill"><i class="fa-solid fa-circle-check" style="color:var(--neon-blue);"></i> One-Year Master’s</span>
                <span class="career-pill"><i class="fa-solid fa-circle-check" style="color:var(--neon-blue);"></i> Research & PhD Programs</span>
                <span class="career-pill"><i class="fa-solid fa-circle-check" style="color:var(--neon-blue);"></i> Foundation Pathways</span>
                <span class="career-pill"><i class="fa-solid fa-circle-check" style="color:var(--neon-blue);"></i> Sandwich & Placement</span>
                <span class="career-pill"><i class="fa-solid fa-circle-check" style="color:var(--neon-blue);"></i> Professional Certifications</span>
              </div>
            </div>
            <div class="animate-on-scroll delay-1">
              <h3 style="font-size:1.5rem; color:#0f172a; margin-bottom:1.5rem; font-weight:800;"><i class="fa-solid fa-rocket" style="color:var(--neon-orange); margin-right:0.5rem;"></i> Accelerated Timelines</h3>
              <div class="highlight-box bg-dots" style="background: white; border: 1px solid #e2e8f0; border-radius: 20px; padding: 2rem;">
                <p style="color:var(--gray); line-height:1.6; font-size:1.05rem;">The UK allows you to complete a Bachelor’s in just <strong>3 years</strong> and a Master’s in <strong>1 year</strong>, launching you into the global workforce significantly faster.</p>
              </div>
            </div>
          </div>
        </div>
      </section>

      <!-- MODULE: CLIMATE & FOOD -->
      <section class="section" style="background:#f8fafc; border-top:1px solid #e2e8f0;">
        <div class="container">
          <div class="grid grid--2 gap--4">
            <div class="animate-on-scroll">
              <h3 style="font-size:1.5rem; color:#0f172a; margin-bottom:1.5rem; font-weight:800;"><i class="fa-solid fa-cloud-sun-rain" style="color:var(--neon-blue); margin-right:0.5rem;"></i> Climate in the UK</h3>
              <ul class="benefit-list" style="margin-bottom: 2rem;">
                <li><i class="fa-solid fa-snowflake" style="color:var(--neon-blue);"></i> Cool, manageable winters</li>
                <li><i class="fa-solid fa-sun" style="color:var(--neon-orange);"></i> Mild, beautiful summers</li>
                <li><i class="fa-solid fa-cloud-rain" style="color:var(--neon-blue);"></i> Frequent rainfall</li>
                <li><i class="fa-solid fa-temperature-half" style="color:var(--neon-purple);"></i> Distinct seasonal variations</li>
              </ul>
            </div>
            <div class="animate-on-scroll delay-1">
              <h3 style="font-size:1.5rem; color:#0f172a; margin-bottom:1.5rem; font-weight:800;"><i class="fa-solid fa-utensils" style="color:var(--neon-green); margin-right:0.5rem;"></i> Food & Cuisine</h3>
              <p style="color:var(--gray); margin-bottom:1.5rem; line-height:1.6;">Indian students can easily access:</p>
              <ul class="benefit-list">
                <li><i class="fa-solid fa-store" style="color:var(--neon-green);"></i> Thriving Indian restaurants and groceries</li>
                <li><i class="fa-solid fa-leaf" style="color:var(--neon-green);"></i> Abundant vegetarian and vegan options</li>
                <li><i class="fa-solid fa-pepper-hot" style="color:var(--neon-green);"></i> South Indian and North Indian cuisine</li>
                <li><i class="fa-solid fa-earth-europe" style="color:var(--neon-green);"></i> Massive multicultural dining scenes</li>
              </ul>
            </div>
          </div>
        </div>
      </section>

      <!-- MODULE: ALUMNI SUCCESS -->
      <section class="section">
        <div class="container">
          <div class="section__header animate-on-scroll">
            <span class="section__tag">Global Network</span>
            <h2 class="section__title">Alumni <span>Success</span></h2>
            <p style="color:var(--gray); margin-top: 0.5rem;">Graduates benefit from unmatched international recognition and access to multinational employers worldwide.</p>
            <div class="accent-bar"></div>
          </div>
          <div class="roi-grid" style="grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));">
            <div class="roi-card animate-on-scroll" style="text-align:center; padding:1.5rem;">
              <div class="icon-colorful icon-colorful--blue" style="margin:0 auto 1rem; width:48px; height:48px; font-size:1.25rem;"><i class="fa-solid fa-building-columns"></i></div>
              <h4 style="font-size:1.1rem; color:#0f172a; margin:0; font-weight:700;">Politics & Leadership</h4>
            </div>
            <div class="roi-card animate-on-scroll delay-1" style="text-align:center; padding:1.5rem;">
              <div class="icon-colorful icon-colorful--teal" style="margin:0 auto 1rem; width:48px; height:48px; font-size:1.25rem;"><i class="fa-solid fa-cogs"></i></div>
              <h4 style="font-size:1.1rem; color:#0f172a; margin:0; font-weight:700;">Science & Tech</h4>
            </div>
            <div class="roi-card animate-on-scroll delay-2" style="text-align:center; padding:1.5rem;">
              <div class="icon-colorful icon-colorful--green" style="margin:0 auto 1rem; width:48px; height:48px; font-size:1.25rem;"><i class="fa-solid fa-chart-line"></i></div>
              <h4 style="font-size:1.1rem; color:#0f172a; margin:0; font-weight:700;">Business & Finance</h4>
            </div>
            <div class="roi-card animate-on-scroll delay-3" style="text-align:center; padding:1.5rem;">
              <div class="icon-colorful icon-colorful--purple" style="margin:0 auto 1rem; width:48px; height:48px; font-size:1.25rem;"><i class="fa-solid fa-microchip"></i></div>
              <h4 style="font-size:1.1rem; color:#0f172a; margin:0; font-weight:700;">AI</h4>
            </div>
            <div class="roi-card animate-on-scroll delay-4" style="text-align:center; padding:1.5rem;">
              <div class="icon-colorful icon-colorful--orange" style="margin:0 auto 1rem; width:48px; height:48px; font-size:1.25rem;"><i class="fa-solid fa-stethoscope"></i></div>
              <h4 style="font-size:1.1rem; color:#0f172a; margin:0; font-weight:700;">Medicine</h4>
            </div>
            <div class="roi-card animate-on-scroll delay-5" style="text-align:center; padding:1.5rem;">
              <div class="icon-colorful icon-colorful--orange" style="margin:0 auto 1rem; width:48px; height:48px; font-size:1.25rem; background:rgba(234,88,12,0.1); color:#ea580c;"><i class="fa-solid fa-masks-theater"></i></div>
              <h4 style="font-size:1.1rem; color:#0f172a; margin:0; font-weight:700;">Media & Arts</h4>
            </div>
          </div>
        </div>
      </section>
      <?php elseif ($country_slug === 'usa'): ?>

      <!-- MODULE: THE 2026 STRATEGIC ADVANTAGE -->
      <section class="section" style="background:#f8fafc; border-top:1px solid #e2e8f0;">
        <div class="container">
          <div class="section__header animate-on-scroll">
            <span class="section__tag">The Innovation Economy</span>
            <h2 class="section__title">The 2026 <span>Strategic Advantage</span></h2>
            <p style="color:var(--gray); margin-top: 0.5rem;">"The USA is not just a study destination — it is the world’s largest innovation ecosystem." Focus on high-ROI STEM programs and long-term global career opportunities.</p>
            <div class="accent-bar"></div>
          </div>
          <div class="grid grid--3 gap--4">
            <div class="pillar-card animate-on-scroll">
              <div class="pillar-num" style="background:rgba(139, 92, 246, 0.08); color:var(--neon-purple);"><i class="fa-solid fa-microchip"></i></div>
              <h4>Innovation Leadership</h4>
              <p>Lead globally in AI, Data Science, Biotech, Space Tech, and Robotics. Work on funded projects and collaborate with industry leaders.</p>
            </div>
            <div class="pillar-card animate-on-scroll delay-1">
              <div class="pillar-num" style="background:rgba(16, 185, 129, 0.08); color:var(--neon-green);"><i class="fa-solid fa-sliders"></i></div>
              <h4>Flexible Education</h4>
              <p>Explore multiple subjects, customize your major, and combine technology with business to build a highly personalized career path.</p>
            </div>
            <div class="pillar-card animate-on-scroll delay-2">
              <div class="pillar-num" style="background:rgba(245, 158, 11, 0.08); color:var(--neon-orange);"><i class="fa-solid fa-building-user"></i></div>
              <h4>Global Employability</h4>
              <p>Top multinationals actively recruit US grads: Google, Microsoft, Amazon, Tesla, and Meta. Gain unparalleled access to high-paying tech careers.</p>
            </div>
          </div>
        </div>
      </section>

      <!-- MODULE: OPT & STEM FRAMEWORK -->
      <section class="section">
        <div class="container">
          <div class="section__header animate-on-scroll">
            <span class="section__tag">The 2026 USA Work & Stay-back Pathway</span>
            <h2 class="section__title">The “OPT & STEM” <span>Career Framework</span></h2>
            <div class="accent-bar"></div>
          </div>
          <div class="grid grid--2 gap--4">
            <div class="info-card animate-on-scroll">
              <div class="ic-header"><div class="icon-colorful icon-colorful--blue" style="font-size:1.5rem; margin-right:.75rem"><i class="fa-solid fa-briefcase"></i></div> <h4>CPT (Curricular Practical Training)</h4></div>
              <div class="ic-body">
                <ul class="benefit-list" style="margin-top: 1rem;">
                  <li><i class="fa-solid fa-building text-success"></i> Gain paid or unpaid internship experience</li>
                  <li><i class="fa-solid fa-calendar-check text-success"></i> Usually available after 1 academic year</li>
                </ul>
              </div>
            </div>
            <div class="info-card animate-on-scroll delay-1">
              <div class="ic-header"><div class="icon-colorful icon-colorful--orange" style="font-size:1.5rem; margin-right:.75rem"><i class="fa-solid fa-file-contract"></i></div> <h4>OPT (Optional Practical Training)</h4></div>
              <div class="ic-body">
                <ul class="benefit-list" style="margin-top: 1rem;">
                  <li><i class="fa-solid fa-stamp text-success"></i> Receive 12 months of post-study work authorization</li>
                  <li><i class="fa-solid fa-globe text-success"></i> Work full-time and gain international experience</li>
                </ul>
              </div>
            </div>
            <div class="info-card animate-on-scroll delay-2">
              <div class="ic-header"><div class="icon-colorful icon-colorful--purple" style="font-size:1.5rem; margin-right:.75rem"><i class="fa-solid fa-microchip"></i></div> <h4>STEM OPT Extension</h4></div>
              <div class="ic-body">
                <p style="font-weight: 600; margin-bottom: 0.5rem;">Approved STEM programs receive:</p>
                <ul class="benefit-list">
                  <li><i class="fa-solid fa-plus text-success"></i> An additional 24-month OPT Extension</li>
                  <li><i class="fa-solid fa-calendar-days text-success"></i> <strong>Up to 3 Years</strong> of total post-study work</li>
                </ul>
              </div>
            </div>
            <div class="info-card animate-on-scroll delay-3">
              <div class="ic-header"><div class="icon-colorful icon-colorful--green" style="font-size:1.5rem; margin-right:.75rem"><i class="fa-solid fa-clock"></i></div> <h4>On-campus Work Rights</h4></div>
              <div class="ic-body">
                <ul class="benefit-list" style="margin-top: 1rem;">
                  <li><i class="fa-solid fa-business-time text-success"></i> Work up to 20 hours/week during sessions</li>
                  <li><i class="fa-solid fa-calendar-plus text-success"></i> Work full-time during vacations and breaks</li>
                </ul>
              </div>
            </div>
          </div>
        </div>
      </section>

      <!-- MODULE: SCHOLARSHIPS -->
      <section class="section" style="background:#f8fafc; border-top:1px solid #e2e8f0;">
        <div class="container">
          <div class="section__header animate-on-scroll">
            <span class="section__tag">Financial Support</span>
            <h2 class="section__title">Scholarships for <span>Indian Students</span></h2>
            <div class="accent-bar"></div>
          </div>
          <div class="grid grid--2 gap--4">
            <div class="info-card animate-on-scroll">
              <div class="ic-header"><div class="icon-colorful icon-colorful--blue" style="font-size:1.25rem; margin-right:.75rem"><i class="fa-solid fa-award"></i></div> <h4>Fulbright-Nehru Fellowships</h4></div>
              <div class="ic-body">
                <p style="color:var(--gray); font-size:0.95rem;">Govt-funded for Master’s/PhD applicants covering tuition, airfare, and living expenses.</p>
              </div>
            </div>
            <div class="info-card animate-on-scroll delay-1">
              <div class="ic-header"><div class="icon-colorful icon-colorful--orange" style="font-size:1.25rem; margin-right:.75rem"><i class="fa-solid fa-users"></i></div> <h4>Hubert Humphrey Fellowship</h4></div>
              <div class="ic-body">
                <p style="color:var(--gray); font-size:0.95rem;">Designed for mid-career professionals focusing on leadership and academic exchange.</p>
              </div>
            </div>
            <div class="info-card animate-on-scroll delay-2">
              <div class="ic-header"><div class="icon-colorful icon-colorful--purple" style="font-size:1.25rem; margin-right:.75rem"><i class="fa-solid fa-earth-americas"></i></div> <h4>#YouAreWelcomeHere</h4></div>
              <div class="ic-body">
                <p style="color:var(--gray); font-size:0.95rem;">Focused on international diversity, leadership potential, and global student engagement.</p>
              </div>
            </div>
            <div class="info-card animate-on-scroll delay-3">
              <div class="ic-header"><div class="icon-colorful icon-colorful--green" style="font-size:1.25rem; margin-right:.75rem"><i class="fa-solid fa-venus"></i></div> <h4>AAUW International Fellowships</h4></div>
              <div class="ic-body">
                <p style="color:var(--gray); font-size:0.95rem;">Special scholarship support for women pursuing graduate and postgraduate study in the USA.</p>
              </div>
            </div>
          </div>
        </div>
      </section>

      <!-- MODULE: FEES & VISA -->
      <section class="section">
        <div class="container">
          <div class="grid grid--2 gap--4">
            <div class="animate-on-scroll">
              <h3 style="font-size:1.5rem; color:#0f172a; margin-bottom:1.5rem; font-weight:800;"><i class="fa-solid fa-wallet" style="color:var(--neon-blue); margin-right:0.5rem;"></i> Average Fees Structure</h3>
              <div class="stayback-container">
                <table class="stayback-table" style="font-size:0.95rem;">
                    <thead>
                        <tr>
                            <th>Program Type</th>
                            <th>Average Annual Tuition (USD)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr><td style="font-weight:700;">Undergraduate Programs</td><td>$20,000 – $40,000</td></tr>
                        <tr><td style="font-weight:700;">Master’s Programs</td><td>$20,000 – $45,000</td></tr>
                        <tr><td style="font-weight:700;">MBA Programs</td><td>$40,000 – $80,000</td></tr>
                        <tr><td style="font-weight:700;">Community Colleges</td><td>$6,000 – $20,000</td></tr>
                    </tbody>
                </table>
              </div>
            </div>
            <div class="animate-on-scroll delay-1">
              <h3 style="font-size:1.5rem; color:#0f172a; margin-bottom:1.5rem; font-weight:800;"><i class="fa-solid fa-passport" style="color:var(--neon-orange); margin-right:0.5rem;"></i> Student Visa Framework</h3>
              <div class="stayback-container">
                <table class="stayback-table" style="font-size:0.95rem;">
                    <thead>
                        <tr>
                            <th>Visa Type</th>
                            <th>Purpose</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr><td style="font-weight:700; color:var(--primary);">F-1 Visa</td><td>Academic programs and university study</td></tr>
                        <tr><td style="font-weight:700; color:var(--primary);">J-1 Visa</td><td>Exchange programs and research</td></tr>
                        <tr><td style="font-weight:700; color:var(--primary);">M-1 Visa</td><td>Vocational and non-academic training</td></tr>
                    </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </section>

      <!-- MODULE: TOP CITIES & PATHWAYS -->
      <section class="section" style="background:#f8fafc; border-top:1px solid #e2e8f0;">
        <div class="container">
          <div class="grid grid--2 gap--4">
            <div class="animate-on-scroll">
              <h3 style="font-size:1.5rem; color:#0f172a; margin-bottom:1.5rem; font-weight:800;"><i class="fa-solid fa-city" style="color:var(--neon-blue); margin-right:0.5rem;"></i> Top Student Cities</h3>
              <ul class="benefit-list" style="margin-bottom: 2rem;">
                <li><strong>Boston:</strong> Elite universities and innovation ecosystem</li>
                <li><strong>New York:</strong> Finance, media, and global business hub</li>
                <li><strong>San Francisco:</strong> Technology and startup capital</li>
                <li><strong>Los Angeles:</strong> Entertainment, business, and engineering</li>
                <li><strong>Chicago:</strong> Research, analytics, and finance</li>
                <li><strong>Washington DC:</strong> Policy, law, and international relations</li>
              </ul>
            </div>
            <div class="animate-on-scroll delay-1">
              <h3 style="font-size:1.5rem; color:#0f172a; margin-bottom:1.5rem; font-weight:800;"><i class="fa-solid fa-book-open" style="color:var(--neon-orange); margin-right:0.5rem;"></i> Available Study Pathways</h3>
              <div class="career-flex">
                <span class="career-pill"><i class="fa-solid fa-circle-check" style="color:var(--neon-orange);"></i> Undergraduate Degrees</span>
                <span class="career-pill"><i class="fa-solid fa-circle-check" style="color:var(--neon-orange);"></i> Master’s Programs</span>
                <span class="career-pill"><i class="fa-solid fa-circle-check" style="color:var(--neon-orange);"></i> Research & PhD Programs</span>
                <span class="career-pill"><i class="fa-solid fa-circle-check" style="color:var(--neon-orange);"></i> Community College Transfer</span>
                <span class="career-pill"><i class="fa-solid fa-circle-check" style="color:var(--neon-orange);"></i> STEM & Research-focused</span>
                <span class="career-pill"><i class="fa-solid fa-circle-check" style="color:var(--neon-orange);"></i> Certificate Programs</span>
              </div>
            </div>
          </div>
        </div>
      </section>

      <!-- MODULE: ALUMNI SUCCESS -->
      <section class="section">
        <div class="container">
          <div class="section__header animate-on-scroll">
            <span class="section__tag">Global Network</span>
            <h2 class="section__title">Alumni <span>Success</span></h2>
            <p style="color:var(--gray); margin-top: 0.5rem;">Graduates benefit from global employer recognition, access to Silicon Valley, and high-paying international careers.</p>
            <div class="accent-bar"></div>
          </div>
          <div class="roi-grid" style="grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));">
            <div class="roi-card animate-on-scroll" style="text-align:center; padding:1.5rem;">
              <div class="icon-colorful icon-colorful--blue" style="margin:0 auto 1rem; width:48px; height:48px; font-size:1.25rem;"><i class="fa-solid fa-microchip"></i></div>
              <h4 style="font-size:1.1rem; color:#0f172a; margin:0; font-weight:700;">Tech & AI</h4>
            </div>
            <div class="roi-card animate-on-scroll delay-1" style="text-align:center; padding:1.5rem;">
              <div class="icon-colorful icon-colorful--teal" style="margin:0 auto 1rem; width:48px; height:48px; font-size:1.25rem;"><i class="fa-solid fa-rocket"></i></div>
              <h4 style="font-size:1.1rem; color:#0f172a; margin:0; font-weight:700;">Startups</h4>
            </div>
            <div class="roi-card animate-on-scroll delay-2" style="text-align:center; padding:1.5rem;">
              <div class="icon-colorful icon-colorful--green" style="margin:0 auto 1rem; width:48px; height:48px; font-size:1.25rem;"><i class="fa-solid fa-stethoscope"></i></div>
              <h4 style="font-size:1.1rem; color:#0f172a; margin:0; font-weight:700;">Medicine</h4>
            </div>
            <div class="roi-card animate-on-scroll delay-3" style="text-align:center; padding:1.5rem;">
              <div class="icon-colorful icon-colorful--purple" style="margin:0 auto 1rem; width:48px; height:48px; font-size:1.25rem;"><i class="fa-solid fa-building-columns"></i></div>
              <h4 style="font-size:1.1rem; color:#0f172a; margin:0; font-weight:700;">Finance</h4>
            </div>
            <div class="roi-card animate-on-scroll delay-4" style="text-align:center; padding:1.5rem;">
              <div class="icon-colorful icon-colorful--orange" style="margin:0 auto 1rem; width:48px; height:48px; font-size:1.25rem;"><i class="fa-solid fa-satellite"></i></div>
              <h4 style="font-size:1.1rem; color:#0f172a; margin:0; font-weight:700;">Space Tech</h4>
            </div>
            <div class="roi-card animate-on-scroll delay-5" style="text-align:center; padding:1.5rem;">
              <div class="icon-colorful icon-colorful--orange" style="margin:0 auto 1rem; width:48px; height:48px; font-size:1.25rem; background:rgba(234,88,12,0.1); color:#ea580c;"><i class="fa-solid fa-clapperboard"></i></div>
              <h4 style="font-size:1.1rem; color:#0f172a; margin:0; font-weight:700;">Entertainment</h4>
            </div>
          </div>
        </div>
      </section>
      <?php elseif ($country_slug === 'italy'): ?>

      <!-- MODULE: THE 2026 STRATEGIC ADVANTAGE -->
      <section class="section" style="background:#f8fafc; border-top:1px solid #e2e8f0;">
        <div class="container">
          <div class="section__header animate-on-scroll">
            <span class="section__tag">The Low-Cost / High-Rank Path</span>
            <h2 class="section__title">The 2026 <span>Strategic Advantage</span></h2>
            <p style="color:var(--gray); margin-top: 0.5rem;">"Italy is Europe’s best-kept secret for high-ranking education at an Indian price point." Leverage family income (ISEE) for near-zero tuition at world-class public universities.</p>
            <div class="accent-bar"></div>
          </div>
          <div class="grid grid--3 gap--4">
            <div class="pillar-card animate-on-scroll">
              <div class="pillar-num" style="background:rgba(139, 92, 246, 0.08); color:var(--neon-purple);"><i class="fa-solid fa-language"></i></div>
              <h4>English-Taught Revolution</h4>
              <p>Access over 500+ programs in Engineering, Design, Business, and Tech — all taught entirely in English at historic institutions.</p>
            </div>
            <div class="pillar-card animate-on-scroll delay-1">
              <div class="pillar-num" style="background:rgba(16, 185, 129, 0.08); color:var(--neon-green);"><i class="fa-solid fa-gem"></i></div>
              <h4>The “Made in Italy” Edge</h4>
              <p>Gain direct industry exposure through connections with global brands like Ferrari, Gucci, and Eni for internships and projects.</p>
            </div>
            <div class="pillar-card animate-on-scroll delay-2">
              <div class="pillar-num" style="background:rgba(245, 158, 11, 0.08); color:var(--neon-orange);"><i class="fa-solid fa-euro-sign"></i></div>
              <h4>Strategic European Hub</h4>
              <p>As the Eurozone's third-largest economy, Italy offers central connectivity and vast opportunities in luxury, manufacturing, and tech.</p>
            </div>
          </div>
        </div>
      </section>

      <!-- MODULE: PERMESSO & WORK FRAMEWORK -->
      <section class="section">
        <div class="container">
          <div class="section__header animate-on-scroll">
            <span class="section__tag">The 2026 Italy Work & Residence Blueprint</span>
            <h2 class="section__title">The “Permesso” & <span>Work Framework</span></h2>
            <div class="accent-bar"></div>
          </div>
          <div class="grid grid--2 gap--4">
            <div class="info-card animate-on-scroll">
              <div class="ic-header"><div class="icon-colorful icon-colorful--blue" style="font-size:1.5rem; margin-right:.75rem"><i class="fa-solid fa-id-card"></i></div> <h4>Permesso di Soggiorno</h4></div>
              <div class="ic-body">
                <ul class="benefit-list" style="margin-top: 1rem;">
                  <li><i class="fa-solid fa-calendar-check text-success"></i> Apply for residence permit within 8 days of entry</li>
                  <li><i class="fa-solid fa-briefcase text-success"></i> Mandatory for all legal activities in Italy</li>
                </ul>
              </div>
            </div>
            <div class="info-card animate-on-scroll delay-1">
              <div class="ic-header"><div class="icon-colorful icon-colorful--orange" style="font-size:1.5rem; margin-right:.75rem"><i class="fa-solid fa-clock"></i></div> <h4>Part-time Work Rights</h4></div>
              <div class="ic-body">
                <ul class="benefit-list" style="margin-top: 1rem;">
                  <li><i class="fa-solid fa-business-time text-success"></i> Work 20 hours/week (Max 1,040 hours/year)</li>
                  <li><i class="fa-solid fa-euro-sign text-success"></i> Supplement living costs while studying</li>
                </ul>
              </div>
            </div>
            <div class="info-card animate-on-scroll delay-2">
              <div class="ic-header"><div class="icon-colorful icon-colorful--purple" style="font-size:1.5rem; margin-right:.75rem"><i class="fa-solid fa-hourglass-half"></i></div> <h4>12-Month Stay-back Window</h4></div>
              <div class="ic-body">
                <ul class="benefit-list" style="margin-top: 1rem;">
                  <li><i class="fa-solid fa-search text-success"></i> Dedicated window to search for employment or start a business</li>
                  <li><i class="fa-solid fa-arrows-to-dot text-success"></i> Transition seamlessly into long-term work opportunities</li>
                </ul>
              </div>
            </div>
            <div class="info-card animate-on-scroll delay-3">
              <div class="ic-header"><div class="icon-colorful icon-colorful--green" style="font-size:1.5rem; margin-right:.75rem"><i class="fa-solid fa-users-gear"></i></div> <h4>Decreto Flussi Advantage</h4></div>
              <div class="ic-body">
                <p style="color:var(--gray); font-size:0.95rem;">Italy’s 2026 workforce plan recognizes India as a <strong>Priority Partner Nation</strong>, improving skilled employment and work visa conversion.</p>
              </div>
            </div>
          </div>
        </div>
      </section>

      <!-- MODULE: FEES & SCHOLARSHIPS -->
      <section class="section" style="background:#f8fafc; border-top:1px solid #e2e8f0;">
        <div class="container">
          <div class="grid grid--2 gap--4">
            <div class="animate-on-scroll">
              <h3 style="font-size:1.5rem; color:#0f172a; margin-bottom:1.5rem; font-weight:800;"><i class="fa-solid fa-wallet" style="color:var(--neon-blue); margin-right:0.5rem;"></i> Average Fees Structure</h3>
              <div class="stayback-container">
                <table class="stayback-table">
                    <thead>
                        <tr>
                            <th>Program Type</th>
                            <th>Average Annual Tuition (€)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr><td style="font-weight:700;">Public Universities</td><td>€900 – €4,000</td></tr>
                        <tr><td style="font-weight:700;">Private Universities</td><td>€8,000 – €25,000</td></tr>
                        <tr><td style="font-weight:700;">MBA Programs</td><td>€15,000 – €40,000</td></tr>
                        <tr><td style="font-weight:700;">Design & Fashion</td><td>€10,000 – €30,000</td></tr>
                    </tbody>
                </table>
              </div>
            </div>
            <div class="animate-on-scroll delay-1">
              <h3 style="font-size:1.5rem; color:#0f172a; margin-bottom:1.5rem; font-weight:800;"><i class="fa-solid fa-award" style="color:var(--neon-green); margin-right:0.5rem;"></i> Pro Tip: MAECI Scholarship</h3>
              <div class="highlight-box bg-dots" style="background: white; border: 1px solid #e2e8f0; border-radius: 20px; padding: 2rem;">
                <ul class="benefit-list">
                  <li><i class="fa-solid fa-check-circle text-success"></i> €9,000/year financial support</li>
                  <li><i class="fa-solid fa-check-circle text-success"></i> Full tuition fee waivers</li>
                  <li><i class="fa-solid fa-check-circle text-success"></i> Apply early for maximum eligibility</li>
                </ul>
              </div>
            </div>
          </div>
        </div>
      </section>

      <!-- MODULE: UNIVERSITALY PORTAL -->
      <section class="section">
        <div class="container">
          <div class="section__header animate-on-scroll">
            <span class="section__tag">Essential Process</span>
            <h2 class="section__title">The <span>Universitaly Portal</span></h2>
            <p style="color:var(--gray); margin-top: 0.5rem;">Bluestone assists with registration, pre-enrollment, and visa coordination through the official Italian portal.</p>
            <div class="accent-bar"></div>
          </div>
          <div class="grid grid--2 gap--4 align-center">
             <div class="animate-on-scroll">
               <ul class="benefit-list">
                 <li><i class="fa-solid fa-circle-check" style="color:var(--neon-blue);"></i> Portal registration and profile setup</li>
                 <li><i class="fa-solid fa-circle-check" style="color:var(--neon-blue);"></i> Official university pre-enrollment</li>
                 <li><i class="fa-solid fa-circle-check" style="color:var(--neon-blue);"></i> Documentation and degree verification</li>
                 <li><i class="fa-solid fa-circle-check" style="color:var(--neon-blue);"></i> Direct coordination with Italian Embassies</li>
               </ul>
             </div>
             <div class="animate-on-scroll delay-1">
               <div class="info-card" style="background:var(--primary); color:white; border:none;">
                 <div class="ic-body">
                   <h4 style="color:white; margin-bottom:1rem;">Strategic Timeline</h4>
                   <p style="opacity:0.9;"><strong>Fall Intake:</strong> Jan – April Application</p>
                   <p style="opacity:0.9;"><strong>Spring Intake:</strong> Apply by September</p>
                 </div>
               </div>
             </div>
          </div>
        </div>
      </section>

      <!-- MODULE: CLIMATE & FOOD -->
      <section class="section" style="background:#f8fafc; border-top:1px solid #e2e8f0;">
        <div class="container">
          <div class="grid grid--2 gap--4">
            <div class="animate-on-scroll">
              <h3 style="font-size:1.5rem; color:#0f172a; margin-bottom:1.5rem; font-weight:800;"><i class="fa-solid fa-sun" style="color:var(--neon-orange); margin-right:0.5rem;"></i> Climate in Italy</h3>
              <ul class="benefit-list" style="margin-bottom: 2rem;">
                <li><i class="fa-solid fa-umbrella-beach" style="color:var(--neon-orange);"></i> Warm Mediterranean summers</li>
                <li><i class="fa-solid fa-temperature-low" style="color:var(--neon-blue);"></i> Mild winters in most regions</li>
                <li><i class="fa-solid fa-snowflake" style="color:var(--neon-purple);"></i> Snowfall in northern mountains</li>
                <li><i class="fa-solid fa-wind" style="color:var(--neon-green);"></i> Pleasant coastal breezes</li>
              </ul>
            </div>
            <div class="animate-on-scroll delay-1">
              <h3 style="font-size:1.5rem; color:#0f172a; margin-bottom:1.5rem; font-weight:800;"><i class="fa-solid fa-pizza-slice" style="color:var(--neon-green); margin-right:0.5rem;"></i> Food & Lifestyle</h3>
              <p style="color:var(--gray); margin-bottom:1.5rem; line-height:1.6;">Enjoy the best of both worlds:</p>
              <ul class="benefit-list">
                <li><i class="fa-solid fa-store" style="color:var(--neon-green);"></i> Indian groceries in major cities</li>
                <li><i class="fa-solid fa-leaf" style="color:var(--neon-green);"></i> Extensive vegetarian/vegan options</li>
                <li><i class="fa-solid fa-bowl-food" style="color:var(--neon-green);"></i> Authentic Mediterranean diet</li>
                <li><i class="fa-solid fa-mug-hot" style="color:var(--neon-green);"></i> World-famous Italian culinary culture</li>
              </ul>
            </div>
          </div>
        </div>
      </section>

      <!-- MODULE: ALUMNI SUCCESS -->
      <section class="section">
        <div class="container">
          <div class="section__header animate-on-scroll">
            <span class="section__tag">Global Network</span>
            <h2 class="section__title">Alumni <span>Success</span></h2>
            <p style="color:var(--gray); margin-top: 0.5rem;">Italian graduates excel in luxury, automotive, and design sectors globally.</p>
            <div class="accent-bar"></div>
          </div>
          <div class="roi-grid" style="grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));">
            <div class="roi-card animate-on-scroll" style="text-align:center; padding:1.5rem;">
              <div class="icon-colorful icon-colorful--blue" style="margin:0 auto 1rem; width:48px; height:48px; font-size:1.25rem;"><i class="fa-solid fa-shirt"></i></div>
              <h4 style="font-size:1.1rem; color:#0f172a; margin:0; font-weight:700;">Fashion & Luxury</h4>
            </div>
            <div class="roi-card animate-on-scroll delay-1" style="text-align:center; padding:1.5rem;">
              <div class="icon-colorful icon-colorful--teal" style="margin:0 auto 1rem; width:48px; height:48px; font-size:1.25rem;"><i class="fa-solid fa-car"></i></div>
              <h4 style="font-size:1.1rem; color:#0f172a; margin:0; font-weight:700;">Automotive</h4>
            </div>
            <div class="roi-card animate-on-scroll delay-2" style="text-align:center; padding:1.5rem;">
              <div class="icon-colorful icon-colorful--green" style="margin:0 auto 1rem; width:48px; height:48px; font-size:1.25rem;"><i class="fa-solid fa-compass-drafting"></i></div>
              <h4 style="font-size:1.1rem; color:#0f172a; margin:0; font-weight:700;">Design</h4>
            </div>
            <div class="roi-card animate-on-scroll delay-3" style="text-align:center; padding:1.5rem;">
              <div class="icon-colorful icon-colorful--purple" style="margin:0 auto 1rem; width:48px; height:48px; font-size:1.25rem;"><i class="fa-solid fa-chart-pie"></i></div>
              <h4 style="font-size:1.1rem; color:#0f172a; margin:0; font-weight:700;">Business</h4>
            </div>
            <div class="roi-card animate-on-scroll delay-4" style="text-align:center; padding:1.5rem;">
              <div class="icon-colorful icon-colorful--orange" style="margin:0 auto 1rem; width:48px; height:48px; font-size:1.25rem;"><i class="fa-solid fa-microscope"></i></div>
              <h4 style="font-size:1.1rem; color:#0f172a; margin:0; font-weight:700;">Scientific Research</h4>
            </div>
            <div class="roi-card animate-on-scroll delay-5" style="text-align:center; padding:1.5rem;">
              <div class="icon-colorful icon-colorful--orange" style="margin:0 auto 1rem; width:48px; height:48px; font-size:1.25rem; background:rgba(234,88,12,0.1); color:#ea580c;"><i class="fa-solid fa-utensils"></i></div>
              <h4 style="font-size:1.1rem; color:#0f172a; margin:0; font-weight:700;">Culinary</h4>
            </div>
          </div>
        </div>
      </section>
      <?php elseif ($country_slug === 'france'): ?>

      <!-- MODULE: THE 2026 STRATEGIC ADVANTAGE -->
      <section class="section" style="background:#f8fafc; border-top:1px solid #e2e8f0;">
        <div class="container">
          <div class="section__header animate-on-scroll">
            <span class="section__tag">The Art of Innovation</span>
            <h2 class="section__title">The 2026 <span>Strategic Advantage</span></h2>
            <p style="color:var(--gray); margin-top: 0.5rem;">"France is no longer just about art; it is a tech and industrial titan." Access the elite Grandes Écoles system for direct pathways into Europe's aerospace, luxury, and tech sectors.</p>
            <div class="accent-bar"></div>
          </div>
          <div class="grid grid--3 gap--4">
            <div class="pillar-card animate-on-scroll">
              <div class="pillar-num" style="background:rgba(139, 92, 246, 0.08); color:var(--neon-purple);"><i class="fa-solid fa-building-columns"></i></div>
              <h4>Grandes Écoles Advantage</h4>
              <p>95%+ employment rates, industry-driven curriculum, and elite alumni networks at prestigious business and engineering schools.</p>
            </div>
            <div class="pillar-card animate-on-scroll delay-1">
              <div class="pillar-num" style="background:rgba(16, 185, 129, 0.08); color:var(--neon-green);"><i class="fa-solid fa-shuttle-space"></i></div>
              <h4>The Aerospace Valley</h4>
              <p>Home to Airbus and the French Space Agency. Gain direct exposure to aviation, satellite, and space technology research ecosystems.</p>
            </div>
            <div class="pillar-card animate-on-scroll delay-2">
              <div class="pillar-num" style="background:rgba(245, 158, 11, 0.08); color:var(--neon-orange);"><i class="fa-solid fa-euro-sign"></i></div>
              <h4>Affordable Public Excellence</h4>
              <p>Globally respected education with public university fees as low as €2,850/year, creating a strong ROI for STEM and research.</p>
            </div>
          </div>
        </div>
      </section>

      <!-- MODULE: APS & WORK FRAMEWORK -->
      <section class="section">
        <div class="container">
          <div class="section__header animate-on-scroll">
            <span class="section__tag">The 2026 France Career Path</span>
            <h2 class="section__title">The “APS” & <span>Work Framework</span></h2>
            <div class="accent-bar"></div>
          </div>
          <div class="grid grid--2 gap--4">
            <div class="info-card animate-on-scroll">
              <div class="ic-header"><div class="icon-colorful icon-colorful--blue" style="font-size:1.5rem; margin-right:.75rem"><i class="fa-solid fa-id-card"></i></div> <h4>APS (Job Seeker Visa)</h4></div>
              <div class="ic-body">
                <ul class="benefit-list" style="margin-top: 1rem;">
                  <li><i class="fa-solid fa-calendar-check text-success"></i> 1-Year post-graduation stay-back for employment search</li>
                  <li><i class="fa-solid fa-rocket text-success"></i> Renewable pathways for long-term career growth</li>
                </ul>
              </div>
            </div>
            <div class="info-card animate-on-scroll delay-1">
              <div class="ic-header"><div class="icon-colorful icon-colorful--orange" style="font-size:1.5rem; margin-right:.75rem"><i class="fa-solid fa-passport"></i></div> <h4>Alumni Circulation Visa</h4></div>
              <div class="ic-body">
                <ul class="benefit-list" style="margin-top: 1rem;">
                  <li><i class="fa-solid fa-earth-europe text-success"></i> 5-Year Short-Stay Schengen Visa for Indian Master’s grads</li>
                  <li><i class="fa-solid fa-briefcase text-success"></i> Easier European travel and professional mobility</li>
                </ul>
              </div>
            </div>
            <div class="info-card animate-on-scroll delay-2">
              <div class="ic-header"><div class="icon-colorful icon-colorful--purple" style="font-size:1.5rem; margin-right:.75rem"><i class="fa-solid fa-business-time"></i></div> <h4>Student Work Rights</h4></div>
              <div class="ic-body">
                <ul class="benefit-list" style="margin-top: 1rem;">
                  <li><i class="fa-solid fa-clock text-success"></i> Work up to 964 hours/year (~20 hours/week)</li>
                  <li><i class="fa-solid fa-euro-sign text-success"></i> Minimum wage: €11.65/hour (gross SMIC)</li>
                </ul>
              </div>
            </div>
            <div class="info-card animate-on-scroll delay-3">
              <div class="ic-header"><div class="icon-colorful icon-colorful--green" style="font-size:1.5rem; margin-right:.75rem"><i class="fa-solid fa-house-circle-check"></i></div> <h4>Housing Subsidy (CAF)</h4></div>
              <div class="ic-body">
                <p style="color:var(--gray); font-size:0.95rem;">International students are eligible for government assistance, reducing rent by up to <strong>30–40%</strong> (Up to €200/month).</p>
              </div>
            </div>
          </div>
        </div>
      </section>

      <!-- MODULE: FEES STRUCTURE -->
      <section class="section" style="background:#f8fafc; border-top:1px solid #e2e8f0;">
        <div class="container">
          <div class="section__header animate-on-scroll">
            <span class="section__tag">Investment</span>
            <h2 class="section__title">Average <span>Fees Structure</span></h2>
            <div class="accent-bar"></div>
          </div>
          <div class="stayback-container animate-on-scroll">
            <table class="stayback-table">
                <thead>
                    <tr>
                        <th>Program Type</th>
                        <th>Average Annual Tuition (€)</th>
                    </tr>
                </thead>
                <tbody>
                    <tr><td style="font-weight:700;">Public Universities</td><td>€2,850 – €3,879</td></tr>
                    <tr><td style="font-weight:700;">Business & Tech Schools</td><td>€8,000 – €25,000</td></tr>
                    <tr><td style="font-weight:700;">MBA Programs</td><td>€20,000 – €60,000</td></tr>
                    <tr><td style="font-weight:700;">Design & Fashion</td><td>€10,000 – €30,000</td></tr>
                </tbody>
            </table>
          </div>
        </div>
      </section>

      <!-- MODULE: CAMPUS FRANCE INTERVIEW -->
      <section class="section">
        <div class="container">
          <div class="grid grid--2 gap--4 align-center">
            <div class="animate-on-scroll">
              <h3 style="font-size:1.5rem; color:#0f172a; margin-bottom:1.5rem; font-weight:800;"><i class="fa-solid fa-comments" style="color:var(--neon-blue); margin-right:0.5rem;"></i> The Campus France Interview</h3>
              <p style="color:var(--gray); margin-bottom:1.5rem; line-height:1.6;">The Études en France interview is critical for your visa. Bluestone provides specialized support:</p>
              <ul class="benefit-list">
                <li><i class="fa-solid fa-circle-check" style="color:var(--neon-blue);"></i> Mock interviews and feedback sessions</li>
                <li><i class="fa-solid fa-circle-check" style="color:var(--neon-blue);"></i> Academic project and SOP alignment</li>
                <li><i class="fa-solid fa-circle-check" style="color:var(--neon-blue);"></i> Portal registration and documentation help</li>
              </ul>
            </div>
            <div class="animate-on-scroll delay-1">
              <div class="highlight-box bg-dots" style="background: white; border: 1px solid #e2e8f0; border-radius: 20px; padding: 2rem;">
                 <h4 style="margin-bottom:1rem;"><i class="fa-solid fa-lightbulb" style="color:var(--neon-orange);"></i> Pro Tip: Learn French</h4>
                 <p style="color:var(--gray); font-size:0.95rem;">Even for English-taught courses, A1/A2 level French can double your internship and part-time job chances.</p>
              </div>
            </div>
          </div>
        </div>
      </section>

      <!-- MODULE: CLIMATE & FOOD -->
      <section class="section" style="background:#f8fafc; border-top:1px solid #e2e8f0;">
        <div class="container">
          <div class="grid grid--2 gap--4">
            <div class="animate-on-scroll">
              <h3 style="font-size:1.5rem; color:#0f172a; margin-bottom:1.5rem; font-weight:800;"><i class="fa-solid fa-cloud-sun" style="color:var(--neon-blue); margin-right:0.5rem;"></i> Climate in France</h3>
              <ul class="benefit-list" style="margin-bottom: 2rem;">
                <li><i class="fa-solid fa-snowflake" style="color:var(--neon-blue);"></i> Mild winters and warm summers</li>
                <li><i class="fa-solid fa-mountain" style="color:var(--neon-purple);"></i> Snowfall in mountain regions</li>
                <li><i class="fa-solid fa-leaf" style="color:var(--neon-green);"></i> Beautiful spring and autumn seasons</li>
                <li><i class="fa-solid fa-umbrella-beach" style="color:var(--neon-orange);"></i> Mediterranean vibes in the south</li>
              </ul>
            </div>
            <div class="animate-on-scroll delay-1">
              <h3 style="font-size:1.5rem; color:#0f172a; margin-bottom:1.5rem; font-weight:800;"><i class="fa-solid fa-utensils" style="color:var(--neon-green); margin-right:0.5rem;"></i> Food & Cuisine</h3>
              <p style="color:var(--gray); margin-bottom:1.5rem; line-height:1.6;">A global culinary capital for everyone:</p>
              <ul class="benefit-list">
                <li><i class="fa-solid fa-store" style="color:var(--neon-green);"></i> Indian restaurants and grocery stores</li>
                <li><i class="fa-solid fa-leaf" style="color:var(--neon-green);"></i> Excellent vegetarian and vegan options</li>
                <li><i class="fa-solid fa-cheese" style="color:var(--neon-green);"></i> Authentic French and international dining</li>
                <li><i class="fa-solid fa-bowl-food" style="color:var(--neon-green);"></i> South and North Indian cuisines available</li>
              </ul>
            </div>
          </div>
        </div>
      </section>

      <!-- MODULE: ALUMNI SUCCESS -->
      <section class="section">
        <div class="container">
          <div class="section__header animate-on-scroll">
            <span class="section__tag">Global Network</span>
            <h2 class="section__title">Alumni <span>Success</span></h2>
            <p style="color:var(--gray); margin-top: 0.5rem;">French graduates lead global industries in luxury, aerospace, and finance.</p>
            <div class="accent-bar"></div>
          </div>
          <div class="roi-grid" style="grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));">
            <div class="roi-card animate-on-scroll" style="text-align:center; padding:1.5rem;">
              <div class="icon-colorful icon-colorful--blue" style="margin:0 auto 1rem; width:48px; height:48px; font-size:1.25rem;"><i class="fa-solid fa-gem"></i></div>
              <h4 style="font-size:1.1rem; color:#0f172a; margin:0; font-weight:700;">Luxury & Fashion</h4>
            </div>
            <div class="roi-card animate-on-scroll delay-1" style="text-align:center; padding:1.5rem;">
              <div class="icon-colorful icon-colorful--teal" style="margin:0 auto 1rem; width:48px; height:48px; font-size:1.25rem;"><i class="fa-solid fa-jet-fighter-up"></i></div>
              <h4 style="font-size:1.1rem; color:#0f172a; margin:0; font-weight:700;">Aerospace</h4>
            </div>
            <div class="roi-card animate-on-scroll delay-2" style="text-align:center; padding:1.5rem;">
              <div class="icon-colorful icon-colorful--green" style="margin:0 auto 1rem; width:48px; height:48px; font-size:1.25rem;"><i class="fa-solid fa-landmark"></i></div>
              <h4 style="font-size:1.1rem; color:#0f172a; margin:0; font-weight:700;">Finance</h4>
            </div>
            <div class="roi-card animate-on-scroll delay-3" style="text-align:center; padding:1.5rem;">
              <div class="icon-colorful icon-colorful--purple" style="margin:0 auto 1rem; width:48px; height:48px; font-size:1.25rem;"><i class="fa-solid fa-user-tie"></i></div>
              <h4 style="font-size:1.1rem; color:#0f172a; margin:0; font-weight:700;">Management</h4>
            </div>
            <div class="roi-card animate-on-scroll delay-4" style="text-align:center; padding:1.5rem;">
              <div class="icon-colorful icon-colorful--orange" style="margin:0 auto 1rem; width:48px; height:48px; font-size:1.25rem;"><i class="fa-solid fa-brain"></i></div>
              <h4 style="font-size:1.1rem; color:#0f172a; margin:0; font-weight:700;">AI & Research</h4>
            </div>
            <div class="roi-card animate-on-scroll delay-5" style="text-align:center; padding:1.5rem;">
              <div class="icon-colorful icon-colorful--orange" style="margin:0 auto 1rem; width:48px; height:48px; font-size:1.25rem; background:rgba(234,88,12,0.1); color:#ea580c;"><i class="fa-solid fa-utensils"></i></div>
              <h4 style="font-size:1.1rem; color:#0f172a; margin:0; font-weight:700;">Culinary</h4>
            </div>
          </div>
        </div>
      </section>
      <?php elseif ($country_slug === 'netherlands'): ?>

      <!-- MODULE: THE 2026 STRATEGIC ADVANTAGE -->
      <section class="section" style="background:#f8fafc; border-top:1px solid #e2e8f0;">
        <div class="container">
          <div class="section__header animate-on-scroll">
            <span class="section__tag">Innovation & Excellence</span>
            <h2 class="section__title">The 2026 <span>Strategic Advantage</span></h2>
            <p style="color:var(--gray); margin-top: 0.5rem;">"The Netherlands offers two distinct paths to success." Strategically choose between Research Universities (WO) for academic depth and Universities of Applied Sciences (HBO) for industry-ready skills.</p>
            <div class="accent-bar"></div>
          </div>
          <div class="grid grid--3 gap--4">
            <div class="pillar-card animate-on-scroll">
              <div class="pillar-num" style="background:rgba(139, 92, 246, 0.08); color:var(--neon-purple);"><i class="fa-solid fa-microchip"></i></div>
              <h4>The Startup Nation</h4>
              <p>Home to ASML and Philips. Gain access to Brainport Eindhoven, Europe's strongest technology and startup ecosystem.</p>
            </div>
            <div class="pillar-card animate-on-scroll delay-1">
              <div class="pillar-num" style="background:rgba(16, 185, 129, 0.08); color:var(--neon-green);"><i class="fa-solid fa-users-gear"></i></div>
              <h4>Interactive Learning</h4>
              <p>Problem-Based Learning (PBL) culture focuses on team collaboration, analytical thinking, and real-world industrial projects.</p>
            </div>
            <div class="pillar-card animate-on-scroll delay-2">
              <div class="pillar-num" style="background:rgba(245, 158, 11, 0.08); color:var(--neon-orange);"><i class="fa-solid fa-train-subway"></i></div>
              <h4>Global Connectivity</h4>
              <p>Exceptional European access: reach Paris, Berlin, or Brussels in under 3 hours via Schiphol or high-speed rail networks.</p>
            </div>
          </div>
        </div>
      </section>

      <!-- MODULE: ZOEKJAAR FRAMEWORK -->
      <section class="section">
        <div class="container">
          <div class="section__header animate-on-scroll">
            <span class="section__tag">The 2026 Netherlands Work & Residence Blueprint</span>
            <h2 class="section__title">The “Zoekjaar” <span>Orientation Year</span></h2>
            <div class="accent-bar"></div>
          </div>
          <div class="grid grid--2 gap--4">
            <div class="info-card animate-on-scroll">
              <div class="ic-header"><div class="icon-colorful icon-colorful--blue" style="font-size:1.5rem; margin-right:.75rem"><i class="fa-solid fa-calendar-check"></i></div> <h4>1-Year Orientation Year</h4></div>
              <div class="ic-body">
                <ul class="benefit-list" style="margin-top: 1rem;">
                  <li><i class="fa-solid fa-briefcase text-success"></i> Work or freelance freely without a separate permit (TWV)</li>
                  <li><i class="fa-solid fa-clock-rotate-left text-success"></i> Apply anytime within 3 years after graduation</li>
                </ul>
              </div>
            </div>
            <div class="info-card animate-on-scroll delay-1">
              <div class="ic-header"><div class="icon-colorful icon-colorful--orange" style="font-size:1.5rem; margin-right:.75rem"><i class="fa-solid fa-user-tie"></i></div> <h4>Highly Skilled Migrant Path</h4></div>
              <div class="ic-body">
                <ul class="benefit-list" style="margin-top: 1rem;">
                  <li><i class="fa-solid fa-arrow-up-right-dots text-success"></i> Transition seamlessly after securing skilled employment</li>
                  <li><i class="fa-solid fa-money-bill-trend-up text-success"></i> Reduced salary threshold for graduates (~€3,122/month)</li>
                </ul>
              </div>
            </div>
            <div class="info-card animate-on-scroll delay-2">
              <div class="ic-header"><div class="icon-colorful icon-colorful--purple" style="font-size:1.5rem; margin-right:.75rem"><i class="fa-solid fa-business-time"></i></div> <h4>Student Work Rights</h4></div>
              <div class="ic-body">
                <ul class="benefit-list" style="margin-top: 1rem;">
                  <li><i class="fa-solid fa-clock text-success"></i> Work up to 16 hours/week during semesters</li>
                  <li><i class="fa-solid fa-sun text-success"></i> Work full-time during June, July, and August</li>
                </ul>
              </div>
            </div>
            <div class="info-card animate-on-scroll delay-3">
              <div class="ic-header"><div class="icon-colorful icon-colorful--green" style="font-size:1.5rem; margin-right:.75rem"><i class="fa-solid fa-ranking-star"></i></div> <h4>English-Taught Leadership</h4></div>
              <div class="ic-body">
                <p style="color:var(--gray); font-size:0.95rem;">Access <strong>2,100+</strong> programs entirely in English — the highest in non-native Europe. No language barrier for academic success.</p>
              </div>
            </div>
          </div>
        </div>
      </section>

      <!-- MODULE: FEES & FINANCES -->
      <section class="section" style="background:#f8fafc; border-top:1px solid #e2e8f0;">
        <div class="container">
          <div class="grid grid--2 gap--4">
            <div class="animate-on-scroll">
              <h3 style="font-size:1.5rem; color:#0f172a; margin-bottom:1.5rem; font-weight:800;"><i class="fa-solid fa-wallet" style="color:var(--neon-blue); margin-right:0.5rem;"></i> Average Fees Structure</h3>
              <div class="stayback-container">
                <table class="stayback-table">
                    <thead>
                        <tr>
                            <th>Program Type</th>
                            <th>Average Annual Tuition (€)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr><td style="font-weight:700;">Bachelor’s Programs</td><td>€8,000 – €15,000</td></tr>
                        <tr><td style="font-weight:700;">Master’s Programs</td><td>€12,000 – €25,000</td></tr>
                        <tr><td style="font-weight:700;">MBA Programs</td><td>€25,000 – €45,000</td></tr>
                        <tr><td style="font-weight:700;">Specialized Tech</td><td>Up to €38,000</td></tr>
                    </tbody>
                </table>
              </div>
            </div>
            <div class="animate-on-scroll delay-1">
              <h3 style="font-size:1.5rem; color:#0f172a; margin-bottom:1.5rem; font-weight:800;"><i class="fa-solid fa-circle-info" style="color:var(--neon-orange); margin-right:0.5rem;"></i> 2026 Proof of Funds</h3>
              <div class="highlight-box bg-dots" style="background: white; border: 1px solid #e2e8f0; border-radius: 20px; padding: 2rem;">
                <ul class="benefit-list">
                  <li><i class="fa-solid fa-check-circle text-success"></i> Living Funds: €15,000 – €18,000 / year</li>
                  <li><i class="fa-solid fa-check-circle text-success"></i> IND Visa Fee: €254 (One-time)</li>
                  <li><i class="fa-solid fa-check-circle text-success"></i> Monthly Living: €1,000 – €1,500</li>
                  <li><i class="fa-solid fa-house-circle-exclamation text-danger"></i> <strong>Pro Tip:</strong> Start housing search 4–5 months early</li>
                </ul>
              </div>
            </div>
          </div>
        </div>
      </section>

      <!-- MODULE: COMPETITIVE ADMISSIONS -->
      <section class="section">
        <div class="container">
          <div class="section__header animate-on-scroll">
            <span class="section__tag">Selection Process</span>
            <h2 class="section__title">Numerus <span>Fixus Support</span></h2>
            <p style="color:var(--gray); margin-top: 0.5rem;">Bluestone provides specialized guidance for competitive programs with limited seating (numerus fixus).</p>
            <div class="accent-bar"></div>
          </div>
          <div class="grid grid--2 gap--4 align-center">
             <div class="animate-on-scroll">
               <ul class="benefit-list">
                 <li><i class="fa-solid fa-circle-check" style="color:var(--neon-blue);"></i> Psychology, Data Science & AI programs</li>
                 <li><i class="fa-solid fa-circle-check" style="color:var(--neon-blue);"></i> Ranking portfolio preparation</li>
                 <li><i class="fa-solid fa-circle-check" style="color:var(--neon-blue);"></i> Motivation letter & SOP enhancement</li>
                 <li><i class="fa-solid fa-circle-check" style="color:var(--neon-blue);"></i> Strategic application timeline management</li>
               </ul>
             </div>
             <div class="animate-on-scroll delay-1">
               <div class="info-card" style="background:var(--primary); color:white; border:none;">
                 <div class="ic-body">
                   <h4 style="color:white; margin-bottom:1rem;">Strategic Timeline</h4>
                   <p style="opacity:0.9;"><strong>Fall (Sept):</strong> Apply Jan – April</p>
                   <p style="opacity:0.9;"><strong>Spring (Feb):</strong> Apply Sept – Oct</p>
                 </div>
               </div>
             </div>
          </div>
        </div>
      </section>

      <!-- MODULE: CLIMATE & FOOD -->
      <section class="section" style="background:#f8fafc; border-top:1px solid #e2e8f0;">
        <div class="container">
          <div class="grid grid--2 gap--4">
            <div class="animate-on-scroll">
              <h3 style="font-size:1.5rem; color:#0f172a; margin-bottom:1.5rem; font-weight:800;"><i class="fa-solid fa-cloud-sun-rain" style="color:var(--neon-blue); margin-right:0.5rem;"></i> Climate in Netherlands</h3>
              <ul class="benefit-list" style="margin-bottom: 2rem;">
                <li><i class="fa-solid fa-wind" style="color:var(--neon-blue);"></i> Cool winters and mild summers</li>
                <li><i class="fa-solid fa-cloud-showers-heavy" style="color:var(--neon-blue);"></i> Frequent rainfall (Coastal charm)</li>
                <li><i class="fa-solid fa-leaf" style="color:var(--neon-green);"></i> Vibrant spring and autumn seasons</li>
                <li><i class="fa-solid fa-bicycle" style="color:var(--neon-orange);"></i> Perfect weather for the Dutch cycling culture</li>
              </ul>
            </div>
            <div class="animate-on-scroll delay-1">
              <h3 style="font-size:1.5rem; color:#0f172a; margin-bottom:1.5rem; font-weight:800;"><i class="fa-solid fa-bowl-food" style="color:var(--neon-green); margin-right:0.5rem;"></i> Food & Diversity</h3>
              <p style="color:var(--gray); margin-bottom:1.5rem; line-height:1.6;">Multicultural dining at your doorstep:</p>
              <ul class="benefit-list">
                <li><i class="fa-solid fa-store" style="color:var(--neon-green);"></i> Extensive Indian supermarkets & restaurants</li>
                <li><i class="fa-solid fa-leaf" style="color:var(--neon-green);"></i> World-class vegetarian and vegan options</li>
                <li><i class="fa-solid fa-earth-europe" style="color:var(--neon-green);"></i> International food festivals and markets</li>
                <li><i class="fa-solid fa-cheese" style="color:var(--neon-green);"></i> Authentic Dutch culinary experiences</li>
              </ul>
            </div>
          </div>
        </div>
      </section>

      <!-- MODULE: ALUMNI SUCCESS -->
      <section class="section">
        <div class="container">
          <div class="section__header animate-on-scroll">
            <span class="section__tag">Global Network</span>
            <h2 class="section__title">Alumni <span>Success</span></h2>
            <p style="color:var(--gray); margin-top: 0.5rem;">Dutch graduates thrive in global tech, sustainability, and international trade sectors.</p>
            <div class="accent-bar"></div>
          </div>
          <div class="roi-grid" style="grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));">
            <div class="roi-card animate-on-scroll" style="text-align:center; padding:1.5rem;">
              <div class="icon-colorful icon-colorful--blue" style="margin:0 auto 1rem; width:48px; height:48px; font-size:1.25rem;"><i class="fa-solid fa-microchip"></i></div>
              <h4 style="font-size:1.1rem; color:#0f172a; margin:0; font-weight:700;">Tech & ASML</h4>
            </div>
            <div class="roi-card animate-on-scroll delay-1" style="text-align:center; padding:1.5rem;">
              <div class="icon-colorful icon-colorful--teal" style="margin:0 auto 1rem; width:48px; height:48px; font-size:1.25rem;"><i class="fa-solid fa-seedling"></i></div>
              <h4 style="font-size:1.1rem; color:#0f172a; margin:0; font-weight:700;">Sustainability</h4>
            </div>
            <div class="roi-card animate-on-scroll delay-2" style="text-align:center; padding:1.5rem;">
              <div class="icon-colorful icon-colorful--green" style="margin:0 auto 1rem; width:48px; height:48px; font-size:1.25rem;"><i class="fa-solid fa-robot"></i></div>
              <h4 style="font-size:1.1rem; color:#0f172a; margin:0; font-weight:700;">AI & Robotics</h4>
            </div>
            <div class="roi-card animate-on-scroll delay-3" style="text-align:center; padding:1.5rem;">
              <div class="icon-colorful icon-colorful--purple" style="margin:0 auto 1rem; width:48px; height:48px; font-size:1.25rem;"><i class="fa-solid fa-ship"></i></div>
              <h4 style="font-size:1.1rem; color:#0f172a; margin:0; font-weight:700;">Logistics</h4>
            </div>
            <div class="roi-card animate-on-scroll delay-4" style="text-align:center; padding:1.5rem;">
              <div class="icon-colorful icon-colorful--orange" style="margin:0 auto 1rem; width:48px; height:48px; font-size:1.25rem;"><i class="fa-solid fa-gavel"></i></div>
              <h4 style="font-size:1.1rem; color:#0f172a; margin:0; font-weight:700;">Int. Law</h4>
            </div>
            <div class="roi-card animate-on-scroll delay-5" style="text-align:center; padding:1.5rem;">
              <div class="icon-colorful icon-colorful--orange" style="margin:0 auto 1rem; width:48px; height:48px; font-size:1.25rem; background:rgba(234,88,12,0.1); color:#ea580c;"><i class="fa-solid fa-bullhorn"></i></div>
              <h4 style="font-size:1.1rem; color:#0f172a; margin:0; font-weight:700;">Media</h4>
            </div>
          </div>
        </div>
      </section>
      <?php elseif ($country_slug === 'sweden'): ?>

      <!-- MODULE: THE 2026 STRATEGIC ADVANTAGE -->
      <section class="section" style="background:#f8fafc; border-top:1px solid #e2e8f0;">
        <div class="container">
          <div class="section__header animate-on-scroll">
            <span class="section__tag">The Sustainability ROI</span>
            <h2 class="section__title">The 2026 <span>Strategic Advantage</span></h2>
            <p style="color:var(--gray); margin-top: 0.5rem;">"Sweden is the ultimate destination for the future-focused student." Master the centralized Single Portal System to access a world of innovation and sustainability leadership.</p>
            <div class="accent-bar"></div>
          </div>
          <div class="grid grid--3 gap--4">
            <div class="pillar-card animate-on-scroll">
              <div class="pillar-num" style="background:rgba(139, 92, 246, 0.08); color:var(--neon-purple);"><i class="fa-solid fa-layer-group"></i></div>
              <h4>One Portal, Many Dreams</h4>
              <p>Apply to 4 Master’s or 8 Bachelor’s programs through UniversityAdmissions.se using one unified document set for a seamless process.</p>
            </div>
            <div class="pillar-card animate-on-scroll delay-1">
              <div class="pillar-num" style="background:rgba(16, 185, 129, 0.08); color:var(--neon-green);"><i class="fa-solid fa-leaf"></i></div>
              <h4>Sustainability Hub</h4>
              <p>Globally recognized for leadership in Green Tech, Renewable Energy, and Environmental Policy. Ideal for climate-focused innovators.</p>
            </div>
            <div class="pillar-card animate-on-scroll delay-2">
              <div class="pillar-num" style="background:rgba(245, 158, 11, 0.08); color:var(--neon-orange);"><i class="fa-solid fa-scale-balanced"></i></div>
              <h4>Equality & Safety</h4>
              <p>Built on the philosophy of "Lagom" (balance). Enjoy inclusive communities, high safety standards, and excellent work-life balance.</p>
            </div>
          </div>
        </div>
      </section>

      <!-- MODULE: RESIDENCE PERMIT & WORK FRAMEWORK -->
      <section class="section">
        <div class="container">
          <div class="section__header animate-on-scroll">
            <span class="section__tag">The 2026 Sweden Work & Residence Blueprint</span>
            <h2 class="section__title">The “Residence Permit” & <span>Work Framework</span></h2>
            <div class="accent-bar"></div>
          </div>
          <div class="grid grid--2 gap--4">
            <div class="info-card animate-on-scroll">
              <div class="ic-header"><div class="icon-colorful icon-colorful--blue" style="font-size:1.5rem; margin-right:.75rem"><i class="fa-solid fa-briefcase"></i></div> <h4>Unlimited Working Hours</h4></div>
              <div class="ic-body">
                <ul class="benefit-list" style="margin-top: 1rem;">
                  <li><i class="fa-solid fa-clock-rotate-left text-success"></i> Work without hourly restrictions during your studies</li>
                  <li><i class="fa-solid fa-check-circle text-success"></i> Condition: Maintain academic progress and visa compliance</li>
                </ul>
              </div>
            </div>
            <div class="info-card animate-on-scroll delay-1">
              <div class="ic-header"><div class="icon-colorful icon-colorful--orange" style="font-size:1.5rem; margin-right:.75rem"><i class="fa-solid fa-calendar-check"></i></div> <h4>12-Month Orientation Year</h4></div>
              <div class="ic-body">
                <ul class="benefit-list" style="margin-top: 1rem;">
                  <li><i class="fa-solid fa-search text-success"></i> Post-study permit to search for jobs or build startups</li>
                  <li><i class="fa-solid fa-rocket text-success"></i> Seamless transition into long-term work permits</li>
                </ul>
              </div>
            </div>
            <div class="info-card animate-on-scroll delay-2">
              <div class="ic-header"><div class="icon-colorful icon-colorful--purple" style="font-size:1.5rem; margin-right:.75rem"><i class="fa-solid fa-users-viewfinder"></i></div> <h4>Family Support Advantage</h4></div>
              <div class="ic-body">
                <ul class="benefit-list" style="margin-top: 1rem;">
                  <li><i class="fa-solid fa-users text-success"></i> Master’s students can bring dependents</li>
                  <li><i class="fa-solid fa-briefcase text-success"></i> Spouses receive full-time work authorization</li>
                </ul>
              </div>
            </div>
            <div class="info-card animate-on-scroll delay-3">
              <div class="ic-header"><div class="icon-colorful icon-colorful--green" style="font-size:1.5rem; margin-right:.75rem"><i class="fa-solid fa-map-location-dot"></i></div> <h4>PR & PhD Advantage</h4></div>
              <div class="ic-body">
                <p style="color:var(--gray); font-size:0.95rem;">Securing work permits for <strong>4 consecutive years</strong> can lead to Permanent Residency. PhD graduates often receive faster settlement options.</p>
              </div>
            </div>
          </div>
        </div>
      </section>

      <!-- MODULE: FEES & FINANCES -->
      <section class="section" style="background:#f8fafc; border-top:1px solid #e2e8f0;">
        <div class="container">
          <div class="grid grid--2 gap--4">
            <div class="animate-on-scroll">
              <h3 style="font-size:1.5rem; color:#0f172a; margin-bottom:1.5rem; font-weight:800;"><i class="fa-solid fa-wallet" style="color:var(--neon-blue); margin-right:0.5rem;"></i> Average Fees Structure</h3>
              <div class="stayback-container">
                <table class="stayback-table">
                    <thead>
                        <tr>
                            <th>Program Type</th>
                            <th>Average Annual Tuition (SEK)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr><td style="font-weight:700;">Bachelor’s Programs</td><td>80,000 – 140,000</td></tr>
                        <tr><td style="font-weight:700;">Master’s Programs</td><td>80,000 – 155,000</td></tr>
                        <tr><td style="font-weight:700;">MBA Programs</td><td>180,000 – 495,000</td></tr>
                        <tr><td style="font-weight:700;">PhD Programs</td><td>Usually Fully Funded</td></tr>
                    </tbody>
                </table>
              </div>
            </div>
            <div class="animate-on-scroll delay-1">
              <h3 style="font-size:1.5rem; color:#0f172a; margin-bottom:1.5rem; font-weight:800;"><i class="fa-solid fa-circle-check" style="color:var(--neon-orange); margin-right:0.5rem;"></i> 2026 Proof of Funds</h3>
              <div class="highlight-box bg-dots" style="background: white; border: 1px solid #e2e8f0; border-radius: 20px; padding: 2rem;">
                <ul class="benefit-list">
                  <li><i class="fa-solid fa-money-bill-1 text-success"></i> Monthly Funds: SEK 10,656 (~₹88k)</li>
                  <li><i class="fa-solid fa-vault text-success"></i> Annual Funds: SEK 127,872 (~₹10.5L)</li>
                  <li><i class="fa-solid fa-file-invoice-dollar text-success"></i> Portal Fee: SEK 900 (One-time)</li>
                  <li><i class="fa-solid fa-warning text-danger"></i> <strong>Pro Tip:</strong> The January 15 deadline is absolute!</li>
                </ul>
              </div>
            </div>
          </div>
        </div>
      </section>

      <!-- MODULE: CLIMATE & FOOD -->
      <section class="section">
        <div class="container">
          <div class="grid grid--2 gap--4">
            <div class="animate-on-scroll">
              <h3 style="font-size:1.5rem; color:#0f172a; margin-bottom:1.5rem; font-weight:800;"><i class="fa-solid fa-snowflake" style="color:var(--neon-blue); margin-right:0.5rem;"></i> Climate in Sweden</h3>
              <ul class="benefit-list" style="margin-bottom: 2rem;">
                <li><i class="fa-solid fa-temperature-arrow-down" style="color:var(--neon-blue);"></i> Cold winters with magical snowfall</li>
                <li><i class="fa-solid fa-sun" style="color:var(--neon-orange);"></i> Long daylight hours during mild summers</li>
                <li><i class="fa-solid fa-leaf" style="color:var(--neon-green);"></i> Crisp and colorful spring & autumn</li>
                <li><i class="fa-solid fa-wand-magic-sparkles" style="color:var(--neon-purple);"></i> Northern Lights visibility in many regions</li>
              </ul>
            </div>
            <div class="animate-on-scroll delay-1">
              <h3 style="font-size:1.5rem; color:#0f172a; margin-bottom:1.5rem; font-weight:800;"><i class="fa-solid fa-utensils" style="color:var(--neon-green); margin-right:0.5rem;"></i> Food & Lifestyle</h3>
              <p style="color:var(--gray); margin-bottom:1.5rem; line-height:1.6;">Sustainable and diverse dining options:</p>
              <ul class="benefit-list">
                <li><i class="fa-solid fa-store" style="color:var(--neon-green);"></i> Indian restaurants & grocery stores in cities</li>
                <li><i class="fa-solid fa-leaf" style="color:var(--neon-green);"></i> High availability of vegetarian/vegan food</li>
                <li><i class="fa-solid fa-earth-europe" style="color:var(--neon-green);"></i> International and authentic Nordic cuisine</li>
                <li><i class="fa-solid fa-mug-hot" style="color:var(--neon-green);"></i> Experience the Swedish "Fika" culture</li>
              </ul>
            </div>
          </div>
        </div>
      </section>

      <!-- MODULE: ALUMNI SUCCESS -->
      <section class="section" style="background:#f8fafc; border-top:1px solid #e2e8f0;">
        <div class="container">
          <div class="section__header animate-on-scroll">
            <span class="section__tag">Global Network</span>
            <h2 class="section__title">Alumni <span>Success</span></h2>
            <p style="color:var(--gray); margin-top: 0.5rem;">Swedish graduates lead the world in green technology, innovation, and automotive engineering.</p>
            <div class="accent-bar"></div>
          </div>
          <div class="roi-grid" style="grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));">
            <div class="roi-card animate-on-scroll" style="text-align:center; padding:1.5rem;">
              <div class="icon-colorful icon-colorful--blue" style="margin:0 auto 1rem; width:48px; height:48px; font-size:1.25rem;"><i class="fa-solid fa-leaf"></i></div>
              <h4 style="font-size:1.1rem; color:#0f172a; margin:0; font-weight:700;">Green Tech</h4>
            </div>
            <div class="roi-card animate-on-scroll delay-1" style="text-align:center; padding:1.5rem;">
              <div class="icon-colorful icon-colorful--teal" style="margin:0 auto 1rem; width:48px; height:48px; font-size:1.25rem;"><i class="fa-solid fa-car"></i></div>
              <h4 style="font-size:1.1rem; color:#0f172a; margin:0; font-weight:700;">Automotive</h4>
            </div>
            <div class="roi-card animate-on-scroll delay-2" style="text-align:center; padding:1.5rem;">
              <div class="icon-colorful icon-colorful--green" style="margin:0 auto 1rem; width:48px; height:48px; font-size:1.25rem;"><i class="fa-solid fa-robot"></i></div>
              <h4 style="font-size:1.1rem; color:#0f172a; margin:0; font-weight:700;">AI & Robotics</h4>
            </div>
            <div class="roi-card animate-on-scroll delay-3" style="text-align:center; padding:1.5rem;">
              <div class="icon-colorful icon-colorful--purple" style="margin:0 auto 1rem; width:48px; height:48px; font-size:1.25rem;"><i class="fa-solid fa-vial-circle-check"></i></div>
              <h4 style="font-size:1.1rem; color:#0f172a; margin:0; font-weight:700;">Biotech</h4>
            </div>
            <div class="roi-card animate-on-scroll delay-4" style="text-align:center; padding:1.5rem;">
              <div class="icon-colorful icon-colorful--orange" style="margin:0 auto 1rem; width:48px; height:48px; font-size:1.25rem;"><i class="fa-solid fa-house-laptop"></i></div>
              <h4 style="font-size:1.1rem; color:#0f172a; margin:0; font-weight:700;">Spotify/Tech</h4>
            </div>
            <div class="roi-card animate-on-scroll delay-5" style="text-align:center; padding:1.5rem;">
              <div class="icon-colorful icon-colorful--orange" style="margin:0 auto 1rem; width:48px; height:48px; font-size:1.25rem; background:rgba(234,88,12,0.1); color:#ea580c;"><i class="fa-solid fa-gamepad"></i></div>
              <h4 style="font-size:1.1rem; color:#0f172a; margin:0; font-weight:700;">Game Dev</h4>
            </div>
          </div>
        </div>
      </section>
      <?php elseif ($country_slug === 'spain'): ?>

      <!-- MODULE: THE 2026 STRATEGIC ADVANTAGE -->
      <section class="section" style="background:#f8fafc; border-top:1px solid #e2e8f0;">
        <div class="container">
          <div class="section__header animate-on-scroll">
            <span class="section__tag">The Bologna Gateway</span>
            <h2 class="section__title">The 2026 <span>Strategic Advantage</span></h2>
            <p style="color:var(--gray); margin-top: 0.5rem;">"Spain offers a unique High-Rank, Low-Cost education model." Earn degrees recognized across 48 European countries, unlocking EU-wide career opportunities and international mobility.</p>
            <div class="accent-bar"></div>
          </div>
          <div class="grid grid--3 gap--4">
            <div class="pillar-card animate-on-scroll">
              <div class="pillar-num" style="background:rgba(139, 92, 246, 0.08); color:var(--neon-purple);"><i class="fa-solid fa-file-signature"></i></div>
              <h4>Homologation Support</h4>
              <p>Indian qualifications require official recognition (Homologation). Bluestone assists with academic verification, legalization, and translation.</p>
            </div>
            <div class="pillar-card animate-on-scroll delay-1">
              <div class="pillar-num" style="background:rgba(16, 185, 129, 0.08); color:var(--neon-green);"><i class="fa-solid fa-briefcase"></i></div>
              <h4>Tech & Business Hubs</h4>
              <p>Gain access to Barcelona Tech City and Madrid's Financial District. Strong ecosystems for Fintech, Renewable Energy, and Hospitality.</p>
            </div>
            <div class="pillar-card animate-on-scroll delay-2">
              <div class="pillar-num" style="background:rgba(245, 158, 11, 0.08); color:var(--neon-orange);"><i class="fa-solid fa-earth-americas"></i></div>
              <h4>Global Language Advantage</h4>
              <p>Spanish is the world’s 2nd most spoken native language. Mastering it provides a massive edge in international business and trade.</p>
            </div>
          </div>
        </div>
      </section>

      <!-- MODULE: 30-HOUR WORK & RESIDENCE -->
      <section class="section">
        <div class="container">
          <div class="section__header animate-on-scroll">
            <span class="section__tag">The 2026 Spain Career Path</span>
            <h2 class="section__title">The “30-Hour” Work & <span>Residence Blueprint</span></h2>
            <div class="accent-bar"></div>
          </div>
          <div class="grid grid--2 gap--4">
            <div class="info-card animate-on-scroll">
              <div class="ic-header"><div class="icon-colorful icon-colorful--blue" style="font-size:1.5rem; margin-right:.75rem"><i class="fa-solid fa-clock"></i></div> <h4>30-Hour Work Rights</h4></div>
              <div class="ic-body">
                <ul class="benefit-list" style="margin-top: 1rem;">
                  <li><i class="fa-solid fa-check-circle text-success"></i> Major 2026 Update: Work up to 30 hours/week while studying</li>
                  <li><i class="fa-solid fa-wallet text-success"></i> Significantly improved financial flexibility and industry exposure</li>
                </ul>
              </div>
            </div>
            <div class="info-card animate-on-scroll delay-1">
              <div class="ic-header"><div class="icon-colorful icon-colorful--orange" style="font-size:1.5rem; margin-right:.75rem"><i class="fa-solid fa-user-check"></i></div> <h4>Job Seeker Visa</h4></div>
              <div class="ic-body">
                <ul class="benefit-list" style="margin-top: 1rem;">
                  <li><i class="fa-solid fa-calendar-check text-success"></i> 12-Month residence permit to search for jobs after graduation</li>
                  <li><i class="fa-solid fa-rocket text-success"></i> Immediate conversion to work permit once employed</li>
                </ul>
              </div>
            </div>
            <div class="info-card animate-on-scroll delay-2">
              <div class="ic-header"><div class="icon-colorful icon-colorful--purple" style="font-size:1.5rem; margin-right:.75rem"><i class="fa-solid fa-map-location-dot"></i></div> <h4>PR Pathway</h4></div>
              <div class="ic-body">
                <ul class="benefit-list" style="margin-top: 1rem;">
                  <li><i class="fa-solid fa-house-circle-check text-success"></i> Eligible for Permanent Residency after 5 years of legal stay</li>
                  <li><i class="fa-solid fa-earth-europe text-success"></i> Access to the broader European workforce and residency</li>
                </ul>
              </div>
            </div>
            <div class="info-card animate-on-scroll delay-3">
              <div class="ic-header"><div class="icon-colorful icon-colorful--green" style="font-size:1.5rem; margin-right:.75rem"><i class="fa-solid fa-graduation-cap"></i></div> <h4>English-Taught Expansion</h4></div>
              <div class="ic-body">
                <p style="color:var(--gray); font-size:0.95rem;">Access <strong>600+</strong> English-taught Master’s programs for 2026–2027 in fields like AI, Data Science, and Hospitality.</p>
              </div>
            </div>
          </div>
        </div>
      </section>

      <!-- MODULE: FEES & FINANCES -->
      <section class="section" style="background:#f8fafc; border-top:1px solid #e2e8f0;">
        <div class="container">
          <div class="grid grid--2 gap--4">
            <div class="animate-on-scroll">
              <h3 style="font-size:1.5rem; color:#0f172a; margin-bottom:1.5rem; font-weight:800;"><i class="fa-solid fa-euro-sign" style="color:var(--neon-blue); margin-right:0.5rem;"></i> Average Fees Structure</h3>
              <div class="stayback-container">
                <table class="stayback-table">
                    <thead>
                        <tr>
                            <th>Program Type</th>
                            <th>Average Annual Tuition (€)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr><td style="font-weight:700;">Public Bachelor’s</td><td>€750 – €3,500</td></tr>
                        <tr><td style="font-weight:700;">Public Master’s</td><td>€1,000 – €4,500</td></tr>
                        <tr><td style="font-weight:700;">Private / MBA</td><td>€8,000 – €35,000</td></tr>
                        <tr><td style="font-weight:700;">Language Programs</td><td>€2,000 – €6,000</td></tr>
                    </tbody>
                </table>
              </div>
            </div>
            <div class="animate-on-scroll delay-1">
              <h3 style="font-size:1.5rem; color:#0f172a; margin-bottom:1.5rem; font-weight:800;"><i class="fa-solid fa-circle-info" style="color:var(--neon-orange); margin-right:0.5rem;"></i> 2026 Proof of Funds</h3>
              <div class="highlight-box bg-dots" style="background: white; border: 1px solid #e2e8f0; border-radius: 20px; padding: 2rem;">
                <ul class="benefit-list">
                  <li><i class="fa-solid fa-check-circle text-success"></i> Living Funds: €7,200 / year (~₹6.5L)</li>
                  <li><i class="fa-solid fa-check-circle text-success"></i> Monthly Budget: €600 (IPREM)</li>
                  <li><i class="fa-solid fa-check-circle text-success"></i> Visa Fee: €80 (One-time)</li>
                  <li><i class="fa-solid fa-stamp text-danger"></i> <strong>Pro Tip:</strong> MEA Apostille is mandatory for all documents!</li>
                </ul>
              </div>
            </div>
          </div>
        </div>
      </section>

      <!-- MODULE: PCE EXAMS -->
      <section class="section">
        <div class="container">
          <div class="section__header animate-on-scroll">
            <span class="section__tag">Selection Process</span>
            <h2 class="section__title">PCE Examination <span>Support</span></h2>
            <p style="color:var(--gray); margin-top: 0.5rem;">Bluestone provides specialized guidance for PCE (Specific Competency Exams) required for many undergraduate public universities.</p>
            <div class="accent-bar"></div>
          </div>
          <div class="grid grid--2 gap--4 align-center">
             <div class="animate-on-scroll">
               <ul class="benefit-list">
                 <li><i class="fa-solid fa-circle-check" style="color:var(--neon-blue);"></i> Eligibility and exam subject selection</li>
                 <li><i class="fa-solid fa-circle-check" style="color:var(--neon-blue);"></i> Documentation and coordination support</li>
                 <li><i class="fa-solid fa-circle-check" style="color:var(--neon-blue);"></i> Application timeline management (May/June Exams)</li>
                 <li><i class="fa-solid fa-circle-check" style="color:var(--neon-blue);"></i> UNEDasiss portal registration help</li>
               </ul>
             </div>
             <div class="animate-on-scroll delay-1">
               <div class="info-card" style="background:var(--primary); color:white; border:none;">
                 <div class="ic-body">
                   <h4 style="color:white; margin-bottom:1rem;">Strategic Timeline</h4>
                   <p style="opacity:0.9;"><strong>Fall (Sept):</strong> Apply Oct – May</p>
                   <p style="opacity:0.9;"><strong>Spring (Feb):</strong> Private Schools Only</p>
                 </div>
               </div>
             </div>
          </div>
        </div>
      </section>

      <!-- MODULE: CLIMATE & FOOD -->
      <section class="section" style="background:#f8fafc; border-top:1px solid #e2e8f0;">
        <div class="container">
          <div class="grid grid--2 gap--4">
            <div class="animate-on-scroll">
              <h3 style="font-size:1.5rem; color:#0f172a; margin-bottom:1.5rem; font-weight:800;"><i class="fa-solid fa-sun" style="color:var(--neon-orange); margin-right:0.5rem;"></i> Climate in Spain</h3>
              <ul class="benefit-list" style="margin-bottom: 2rem;">
                <li><i class="fa-solid fa-umbrella-beach" style="color:var(--neon-orange);"></i> Warm Mediterranean weather (Sunny & Bright)</li>
                <li><i class="fa-solid fa-temperature-arrow-up" style="color:var(--neon-orange);"></i> Mild winters and comfortable year-round lifestyle</li>
                <li><i class="fa-solid fa-city" style="color:var(--neon-blue);"></i> Iconic student hubs: Barcelona, Madrid, Valencia</li>
                <li><i class="fa-solid fa-mountain-sun" style="color:var(--neon-green);"></i> Beautiful coastal and mountainous landscapes</li>
              </ul>
            </div>
            <div class="animate-on-scroll delay-1">
              <h3 style="font-size:1.5rem; color:#0f172a; margin-bottom:1.5rem; font-weight:800;"><i class="fa-solid fa-utensils" style="color:var(--neon-green); margin-right:0.5rem;"></i> Food & Lifestyle</h3>
              <p style="color:var(--gray); margin-bottom:1.5rem; line-height:1.6;">Vibrant Mediterranean and international dining:</p>
              <ul class="benefit-list">
                <li><i class="fa-solid fa-store" style="color:var(--neon-green);"></i> Indian restaurants and supermarkets in major cities</li>
                <li><i class="fa-solid fa-leaf" style="color:var(--neon-green);"></i> Excellent vegetarian and Halal food availability</li>
                <li><i class="fa-solid fa-plate-wheat" style="color:var(--neon-green);"></i> Authentic Tapas, Paella, and local delicacies</li>
                <li><i class="fa-solid fa-basket-shopping" style="color:var(--neon-green);"></i> Affordable groceries and world-class supermarkets</li>
              </ul>
            </div>
          </div>
        </div>
      </section>

      <!-- MODULE: ALUMNI SUCCESS -->
      <section class="section">
        <div class="container">
          <div class="section__header animate-on-scroll">
            <span class="section__tag">Global Network</span>
            <h2 class="section__title">Alumni <span>Success</span></h2>
            <p style="color:var(--gray); margin-top: 0.5rem;">Spanish graduates excel in renewable energy, hospitality, and European business sectors.</p>
            <div class="accent-bar"></div>
          </div>
          <div class="roi-grid" style="grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));">
            <div class="roi-card animate-on-scroll" style="text-align:center; padding:1.5rem;">
              <div class="icon-colorful icon-colorful--blue" style="margin:0 auto 1rem; width:48px; height:48px; font-size:1.25rem;"><i class="fa-solid fa-solar-panel"></i></div>
              <h4 style="font-size:1.1rem; color:#0f172a; margin:0; font-weight:700;">Renewables</h4>
            </div>
            <div class="roi-card animate-on-scroll delay-1" style="text-align:center; padding:1.5rem;">
              <div class="icon-colorful icon-colorful--teal" style="margin:0 auto 1rem; width:48px; height:48px; font-size:1.25rem;"><i class="fa-solid fa-hotel"></i></div>
              <h4 style="font-size:1.1rem; color:#0f172a; margin:0; font-weight:700;">Hospitality</h4>
            </div>
            <div class="roi-card animate-on-scroll delay-2" style="text-align:center; padding:1.5rem;">
              <div class="icon-colorful icon-colorful--green" style="margin:0 auto 1rem; width:48px; height:48px; font-size:1.25rem;"><i class="fa-solid fa-landmark"></i></div>
              <h4 style="font-size:1.1rem; color:#0f172a; margin:0; font-weight:700;">Fintech</h4>
            </div>
            <div class="roi-card animate-on-scroll delay-3" style="text-align:center; padding:1.5rem;">
              <div class="icon-colorful icon-colorful--purple" style="margin:0 auto 1rem; width:48px; height:48px; font-size:1.25rem;"><i class="fa-solid fa-chart-line"></i></div>
              <h4 style="font-size:1.1rem; color:#0f172a; margin:0; font-weight:700;">Business</h4>
            </div>
            <div class="roi-card animate-on-scroll delay-4" style="text-align:center; padding:1.5rem;">
              <div class="icon-colorful icon-colorful--orange" style="margin:0 auto 1rem; width:48px; height:48px; font-size:1.25rem;"><i class="fa-solid fa-rocket"></i></div>
              <h4 style="font-size:1.1rem; color:#0f172a; margin:0; font-weight:700;">Startups</h4>
            </div>
            <div class="roi-card animate-on-scroll delay-5" style="text-align:center; padding:1.5rem;">
              <div class="icon-colorful icon-colorful--orange" style="margin:0 auto 1rem; width:48px; height:48px; font-size:1.25rem; background:rgba(234,88,12,0.1); color:#ea580c;"><i class="fa-solid fa-earth-americas"></i></div>
              <h4 style="font-size:1.1rem; color:#0f172a; margin:0; font-weight:700;">Int. Trade</h4>
            </div>
          </div>
        </div>
      </section>
      <?php elseif ($country_slug === 'austria'): ?>

      <!-- MODULE: THE 2026 STRATEGIC ADVANTAGE -->
      <section class="section" style="background:#f8fafc; border-top:1px solid #e2e8f0;">
        <div class="container">
          <div class="section__header animate-on-scroll">
            <span class="section__tag">Alpine Excellence</span>
            <h2 class="section__title">The 2026 <span>Strategic Advantage</span></h2>
            <p style="color:var(--gray); margin-top: 0.5rem;">"Austria is Europe’s best-kept secret for ROI." Access world-class public universities where tuition starts at just €726/semester, rivaling the quality of top Western European institutions.</p>
            <div class="accent-bar"></div>
          </div>
          <div class="grid grid--3 gap--4">
            <div class="pillar-card animate-on-scroll">
              <div class="pillar-num" style="background:rgba(139, 92, 246, 0.08); color:var(--neon-purple);"><i class="fa-solid fa-file-shield"></i></div>
              <h4>No-Points Advantage</h4>
              <p>Austrian university graduates are exempt from the standard external "Red-White-Red" points-based entry process, speeding up residency pathways.</p>
            </div>
            <div class="pillar-card animate-on-scroll delay-1">
              <div class="pillar-num" style="background:rgba(16, 185, 129, 0.08); color:var(--neon-green);"><i class="fa-solid fa-industry"></i></div>
              <h4>Industrial Ecosystem</h4>
              <p>Gain exposure to Central Europe’s industrial giants like Red Bull, OMV, and Siemens Austria. Strong sectors in Automotive and Energy.</p>
            </div>
            <div class="pillar-card animate-on-scroll delay-2">
              <div class="pillar-num" style="background:rgba(245, 158, 11, 0.08); color:var(--neon-orange);"><i class="fa-solid fa-star"></i></div>
              <h4>Elite Rankings</h4>
              <p>Home to globally recognized institutions like the University of Vienna (#152) and TU Wien (#197), specializing in AI, Data Science, and Robotics.</p>
            </div>
          </div>
        </div>
      </section>

      <!-- MODULE: JOB SEARCH & WORK FRAMEWORK -->
      <section class="section">
        <div class="container">
          <div class="section__header animate-on-scroll">
            <span class="section__tag">The 2026 Austria Work & Residence Blueprint</span>
            <h2 class="section__title">The “Job Search” & <span>Work Framework</span></h2>
            <div class="accent-bar"></div>
          </div>
          <div class="grid grid--2 gap--4">
            <div class="info-card animate-on-scroll">
              <div class="ic-header"><div class="icon-colorful icon-colorful--blue" style="font-size:1.5rem; margin-right:.75rem"><i class="fa-solid fa-clock"></i></div> <h4>Student Work Rights</h4></div>
              <div class="ic-body">
                <ul class="benefit-list" style="margin-top: 1rem;">
                  <li><i class="fa-solid fa-check-circle text-success"></i> Work up to 20 hours/week from the first day of studies</li>
                  <li><i class="fa-solid fa-money-bill-trend-up text-success"></i> Earn up to €518.44/month tax-free (Marginal earnings)</li>
                </ul>
              </div>
            </div>
            <div class="info-card animate-on-scroll delay-1">
              <div class="ic-header"><div class="icon-colorful icon-colorful--orange" style="font-size:1.5rem; margin-right:.75rem"><i class="fa-solid fa-user-check"></i></div> <h4>12-Month Job Search</h4></div>
              <div class="ic-body">
                <ul class="benefit-list" style="margin-top: 1rem;">
                  <li><i class="fa-solid fa-calendar-check text-success"></i> Extend your stay for 12 months post-graduation to find a job</li>
                  <li><i class="fa-solid fa-rocket text-success"></i> Direct transition into the Red-White-Red (RWR) Card</li>
                </ul>
              </div>
            </div>
            <div class="info-card animate-on-scroll delay-2">
              <div class="ic-header"><div class="icon-colorful icon-colorful--purple" style="font-size:1.5rem; margin-right:.75rem"><i class="fa-solid fa-passport"></i></div> <h4>RWR Card (Work Permit)</h4></div>
              <div class="ic-body">
                <ul class="benefit-list" style="margin-top: 1rem;">
                  <li><i class="fa-solid fa-id-card text-success"></i> 24-Month work permit after securing a qualified job offer</li>
                  <li><i class="fa-solid fa-bolt text-success"></i> Simplified eligibility specifically for Austrian graduates</li>
                </ul>
              </div>
            </div>
            <div class="info-card animate-on-scroll delay-3">
              <div class="ic-header"><div class="icon-colorful icon-colorful--green" style="font-size:1.5rem; margin-right:.75rem"><i class="fa-solid fa-graduation-cap"></i></div> <h4>English-Taught Expansion</h4></div>
              <div class="ic-body">
                <p style="color:var(--gray); font-size:0.95rem;">Access <strong>350+</strong> English-taught Master’s programs for 2026 in Engineering, AI, Data Science, and Renewables.</p>
              </div>
            </div>
          </div>
        </div>
      </section>

      <!-- MODULE: FEES & FINANCES -->
      <section class="section" style="background:#f8fafc; border-top:1px solid #e2e8f0;">
        <div class="container">
          <div class="grid grid--2 gap--4">
            <div class="animate-on-scroll">
              <h3 style="font-size:1.5rem; color:#0f172a; margin-bottom:1.5rem; font-weight:800;"><i class="fa-solid fa-euro-sign" style="color:var(--neon-blue); margin-right:0.5rem;"></i> Average Fees Structure</h3>
              <div class="stayback-container">
                <table class="stayback-table">
                    <thead>
                        <tr>
                            <th>Program Type</th>
                            <th>Average Annual Tuition</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr><td style="font-weight:700;">Public Universities</td><td>€726.72 per semester</td></tr>
                        <tr><td style="font-weight:700;">Private Universities</td><td>€5,000 – €25,000/year</td></tr>
                        <tr><td style="font-weight:700;">MBA Programs</td><td>€15,000 – €40,000</td></tr>
                        <tr><td style="font-weight:700;">Student Union Fee</td><td>€25.20 per semester</td></tr>
                    </tbody>
                </table>
              </div>
            </div>
            <div class="animate-on-scroll delay-1">
              <h3 style="font-size:1.5rem; color:#0f172a; margin-bottom:1.5rem; font-weight:800;"><i class="fa-solid fa-circle-info" style="color:var(--neon-orange); margin-right:0.5rem;"></i> 2026 Proof of Funds</h3>
              <div class="highlight-box bg-dots" style="background: white; border: 1px solid #e2e8f0; border-radius: 20px; padding: 2rem;">
                <ul class="benefit-list">
                  <li><i class="fa-solid fa-check-circle text-success"></i> Students < 24: €722.58 / month</li>
                  <li><i class="fa-solid fa-check-circle text-success"></i> Students 24+: €1,308.39 / month</li>
                  <li><i class="fa-solid fa-check-circle text-success"></i> Health Insurance: ~€69 / month (ÖGK)</li>
                  <li><i class="fa-solid fa-stamp text-danger"></i> <strong>Pro Tip:</strong> MEA Apostille & German translation mandatory!</li>
                </ul>
              </div>
            </div>
          </div>
        </div>
      </section>

      <!-- MODULE: SUCCESS TIMELINE -->
      <section class="section">
        <div class="container">
          <div class="section__header animate-on-scroll">
            <span class="section__tag">Application Strategy</span>
            <h2 class="section__title">Success <span>Timeline</span></h2>
            <p style="color:var(--gray); margin-top: 0.5rem;">Austria's residence permit process is thorough. Early application is critical for a smooth transition.</p>
            <div class="accent-bar"></div>
          </div>
          <div class="grid grid--2 gap--4 align-center">
             <div class="animate-on-scroll">
               <ul class="benefit-list">
                 <li><i class="fa-solid fa-circle-check" style="color:var(--neon-blue);"></i> Winter Intake: Recommended Feb – March 2026 app</li>
                 <li><i class="fa-solid fa-circle-check" style="color:var(--neon-blue);"></i> Summer Intake: Recommended by Oct – Nov 2026</li>
                 <li><i class="fa-solid fa-circle-check" style="color:var(--neon-blue);"></i> Permit Processing: Plan for 3–5 months buffer</li>
                 <li><i class="fa-solid fa-circle-check" style="color:var(--neon-blue);"></i> Specialized support for Austrian public uni entry</li>
               </ul>
             </div>
             <div class="animate-on-scroll delay-1">
               <div class="info-card" style="background:var(--primary); color:white; border:none;">
                 <div class="ic-body">
                   <h4 style="color:white; margin-bottom:1rem;">Deadline Thresholds</h4>
                   <p style="opacity:0.9;"><strong>Winter Semester:</strong> Sept 5 Deadline</p>
                   <p style="opacity:0.9;"><strong>Summer Semester:</strong> Feb 5 Deadline</p>
                 </div>
               </div>
             </div>
          </div>
        </div>
      </section>

      <!-- MODULE: CLIMATE & FOOD -->
      <section class="section" style="background:#f8fafc; border-top:1px solid #e2e8f0;">
        <div class="container">
          <div class="grid grid--2 gap--4">
            <div class="animate-on-scroll">
              <h3 style="font-size:1.5rem; color:#0f172a; margin-bottom:1.5rem; font-weight:800;"><i class="fa-solid fa-mountain-sun" style="color:var(--neon-blue); margin-right:0.5rem;"></i> Alpine Climate</h3>
              <ul class="benefit-list" style="margin-bottom: 2rem;">
                <li><i class="fa-solid fa-snowflake" style="color:var(--neon-blue);"></i> Cold snowy winters (Perfect for Alpine sports)</li>
                <li><i class="fa-solid fa-sun" style="color:var(--neon-orange);"></i> Pleasant, mild summers with scenic greenery</li>
                <li><i class="fa-solid fa-city" style="color:var(--neon-blue);"></i> Top cities: Vienna, Graz, Innsbruck, Salzburg</li>
                <li><i class="fa-solid fa-leaf" style="color:var(--neon-green);"></i> Vibrant seasonal changes and fresh air</li>
              </ul>
            </div>
            <div class="animate-on-scroll delay-1">
              <h3 style="font-size:1.5rem; color:#0f172a; margin-bottom:1.5rem; font-weight:800;"><i class="fa-solid fa-utensils" style="color:var(--neon-green); margin-right:0.5rem;"></i> Food & Diversity</h3>
              <p style="color:var(--gray); margin-bottom:1.5rem; line-height:1.6;">A blend of Central European and global flavors:</p>
              <ul class="benefit-list">
                <li><i class="fa-solid fa-store" style="color:var(--neon-green);"></i> Easy access to Indian restaurants & Asian groceries</li>
                <li><i class="fa-solid fa-leaf" style="color:var(--neon-green);"></i> Extensive vegetarian and vegan food options</li>
                <li><i class="fa-solid fa-cookie" style="color:var(--neon-green);"></i> Authentic Schnitzel, Strudel, and Viennese coffee</li>
                <li><i class="fa-solid fa-basket-shopping" style="color:var(--neon-green);"></i> High-quality supermarkets and local markets</li>
              </ul>
            </div>
          </div>
        </div>
      </section>

      <!-- MODULE: ALUMNI SUCCESS -->
      <section class="section">
        <div class="container">
          <div class="section__header animate-on-scroll">
            <span class="section__tag">Global Network</span>
            <h2 class="section__title">Alumni <span>Success</span></h2>
            <p style="color:var(--gray); margin-top: 0.5rem;">Austrian graduates lead industries in automotive, energy, and Central European finance.</p>
            <div class="accent-bar"></div>
          </div>
          <div class="roi-grid" style="grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));">
            <div class="roi-card animate-on-scroll" style="text-align:center; padding:1.5rem;">
              <div class="icon-colorful icon-colorful--blue" style="margin:0 auto 1rem; width:48px; height:48px; font-size:1.25rem;"><i class="fa-solid fa-car"></i></div>
              <h4 style="font-size:1.1rem; color:#0f172a; margin:0; font-weight:700;">Automotive</h4>
            </div>
            <div class="roi-card animate-on-scroll delay-1" style="text-align:center; padding:1.5rem;">
              <div class="icon-colorful icon-colorful--teal" style="margin:0 auto 1rem; width:48px; height:48px; font-size:1.25rem;"><i class="fa-solid fa-bolt"></i></div>
              <h4 style="font-size:1.1rem; color:#0f172a; margin:0; font-weight:700;">Renewables</h4>
            </div>
            <div class="roi-card animate-on-scroll delay-2" style="text-align:center; padding:1.5rem;">
              <div class="icon-colorful icon-colorful--green" style="margin:0 auto 1rem; width:48px; height:48px; font-size:1.25rem;"><i class="fa-solid fa-robot"></i></div>
              <h4 style="font-size:1.1rem; color:#0f172a; margin:0; font-weight:700;">AI & Robotics</h4>
            </div>
            <div class="roi-card animate-on-scroll delay-3" style="text-align:center; padding:1.5rem;">
              <div class="icon-colorful icon-colorful--purple" style="margin:0 auto 1rem; width:48px; height:48px; font-size:1.25rem;"><i class="fa-solid fa-building-columns"></i></div>
              <h4 style="font-size:1.1rem; color:#0f172a; margin:0; font-weight:700;">Banking</h4>
            </div>
            <div class="roi-card animate-on-scroll delay-4" style="text-align:center; padding:1.5rem;">
              <div class="icon-colorful icon-colorful--orange" style="margin:0 auto 1rem; width:48px; height:48px; font-size:1.25rem;"><i class="fa-solid fa-vial-circle-check"></i></div>
              <h4 style="font-size:1.1rem; color:#0f172a; margin:0; font-weight:700;">BioTech</h4>
            </div>
            <div class="roi-card animate-on-scroll delay-5" style="text-align:center; padding:1.5rem;">
              <div class="icon-colorful icon-colorful--orange" style="margin:0 auto 1rem; width:48px; height:48px; font-size:1.25rem; background:rgba(234,88,12,0.1); color:#ea580c;"><i class="fa-solid fa-flask"></i></div>
              <h4 style="font-size:1.1rem; color:#0f172a; margin:0; font-weight:700;">Chemical Eng</h4>
            </div>
          </div>
        </div>
      </section>
      <?php elseif ($country_slug === 'denmark'): ?>

      <!-- MODULE: THE 2026 STRATEGIC ADVANTAGE -->
      <section class="section" style="background:#f8fafc; border-top:1px solid #e2e8f0;">
        <div class="container">
          <div class="section__header animate-on-scroll">
            <span class="section__tag">Establishment Card Advantage</span>
            <h2 class="section__title">The 2026 <span>Strategic Advantage</span></h2>
            <p style="color:var(--gray); margin-top: 0.5rem;">"Denmark has redefined the post-study landscape." Leverage the unique Establishment Card (Etableringskort) for an elite career pathway in Europe’s most sustainable nation.</p>
            <div class="accent-bar"></div>
          </div>
          <div class="grid grid--3 gap--4">
            <div class="pillar-card animate-on-scroll">
              <div class="pillar-num" style="background:rgba(139, 92, 246, 0.08); color:var(--neon-purple);"><i class="fa-solid fa-microchip"></i></div>
              <h4>The 3-Year STEM Edge</h4>
              <p>STEM, IT, and Science graduates can receive a 3-Year Job Search Residence Permit — one of the longest post-study work windows in Europe.</p>
            </div>
            <div class="pillar-card animate-on-scroll delay-1">
              <div class="pillar-num" style="background:rgba(16, 185, 129, 0.08); color:var(--neon-green);"><i class="fa-solid fa-leaf"></i></div>
              <h4>Green Tech Leadership</h4>
              <p>Global leader in Wind Energy, Sustainable Engineering, and Climate Innovation. Home to giants like Vestas, Ørsted, and LEGO.</p>
            </div>
            <div class="pillar-card animate-on-scroll delay-2">
              <div class="pillar-num" style="background:rgba(245, 158, 11, 0.08); color:var(--neon-orange);"><i class="fa-solid fa-hand-holding-heart"></i></div>
              <h4>Unmatched Happiness</h4>
              <p>Consistently ranked in the Global Top 3 for safety and work-life balance. Experience the collaborative and inclusive "Nordic Model" of learning.</p>
            </div>
          </div>
        </div>
      </section>

      <!-- MODULE: WORK & CAREER BLUEPRINT -->
      <section class="section">
        <div class="container">
          <div class="section__header animate-on-scroll">
            <span class="section__tag">The 2026 Denmark Work & Residence Path</span>
            <h2 class="section__title">The “Work & <span>Career” Blueprint</span></h2>
            <div class="accent-bar"></div>
          </div>
          <div class="grid grid--2 gap--4">
            <div class="info-card animate-on-scroll">
              <div class="ic-header"><div class="icon-colorful icon-colorful--blue" style="font-size:1.5rem; margin-right:.75rem"><i class="fa-solid fa-clock"></i></div> <h4>Work During Studies</h4></div>
              <div class="ic-body">
                <ul class="benefit-list" style="margin-top: 1rem;">
                  <li><i class="fa-solid fa-check-circle text-success"></i> Work up to 90 hours/month during academic sessions</li>
                  <li><i class="fa-solid fa-sun text-success"></i> Work full-time during June, July, and August</li>
                </ul>
              </div>
            </div>
            <div class="info-card animate-on-scroll delay-1">
              <div class="ic-header"><div class="icon-colorful icon-colorful--orange" style="font-size:1.5rem; margin-right:.75rem"><i class="fa-solid fa-id-card"></i></div> <h4>The Establishment Card</h4></div>
              <div class="ic-body">
                <ul class="benefit-list" style="margin-top: 1rem;">
                  <li><i class="fa-solid fa-rocket text-success"></i> STEM / IT Graduates: 3-Year Job Search Permit</li>
                  <li><i class="fa-solid fa-briefcase text-success"></i> Others: 2-Year Job Search Residence Permit</li>
                </ul>
              </div>
            </div>
            <div class="info-card animate-on-scroll delay-2">
              <div class="ic-header"><div class="icon-colorful icon-colorful--purple" style="font-size:1.5rem; margin-right:.75rem"><i class="fa-solid fa-language"></i></div> <h4>Language & Integration</h4></div>
              <div class="ic-body">
                <ul class="benefit-list" style="margin-top: 1rem;">
                  <li><i class="fa-solid fa-graduation-cap text-success"></i> Access to FREE Danish language classes via municipalities</li>
                  <li><i class="fa-solid fa-users text-success"></i> Improved employability and long-term residency prospects</li>
                </ul>
              </div>
            </div>
            <div class="info-card animate-on-scroll delay-3">
              <div class="ic-header"><div class="icon-colorful icon-colorful--green" style="font-size:1.5rem; margin-right:.75rem"><i class="fa-solid fa-gear"></i></div> <h4>Work Freedom</h4></div>
              <div class="ic-body">
                <p style="color:var(--gray); font-size:0.95rem;">Work for any employer, change jobs freely, or start your own business without requiring a separate work permit during stay-back.</p>
              </div>
            </div>
          </div>
        </div>
      </section>

      <!-- MODULE: FEES & FINANCES -->
      <section class="section" style="background:#f8fafc; border-top:1px solid #e2e8f0;">
        <div class="container">
          <div class="grid grid--2 gap--4">
            <div class="animate-on-scroll">
              <h3 style="font-size:1.5rem; color:#0f172a; margin-bottom:1.5rem; font-weight:800;"><i class="fa-solid fa-wallet" style="color:var(--neon-blue); margin-right:0.5rem;"></i> Average Fees Structure</h3>
              <div class="stayback-container">
                <table class="stayback-table">
                    <thead>
                        <tr>
                            <th>Program Type</th>
                            <th>Average Annual Tuition (DKK)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr><td style="font-weight:700;">Bachelor’s Programs</td><td>45,000 – 120,000</td></tr>
                        <tr><td style="font-weight:700;">Master’s Programs</td><td>60,000 – 130,000</td></tr>
                        <tr><td style="font-weight:700;">MBA Programs</td><td>150,000 – 350,000</td></tr>
                        <tr><td style="font-weight:700;">PhD Programs</td><td>Often Fully Funded</td></tr>
                    </tbody>
                </table>
              </div>
            </div>
            <div class="animate-on-scroll delay-1">
              <h3 style="font-size:1.5rem; color:#0f172a; margin-bottom:1.5rem; font-weight:800;"><i class="fa-solid fa-circle-info" style="color:var(--neon-orange); margin-right:0.5rem;"></i> 2026 Proof of Funds</h3>
              <div class="highlight-box bg-dots" style="background: white; border: 1px solid #e2e8f0; border-radius: 20px; padding: 2rem;">
                <ul class="benefit-list">
                  <li><i class="fa-solid fa-check-circle text-success"></i> Living Funds: DKK 6,820 / month (~₹83k)</li>
                  <li><i class="fa-solid fa-check-circle text-success"></i> Visa Fee (SIRI): DKK 2,115</li>
                  <li><i class="fa-solid fa-check-circle text-success"></i> Monthly Budget: DKK 7.5k – 10k</li>
                  <li><i class="fa-solid fa-house-user text-danger"></i> <strong>Pro Tip:</strong> Start housing search 6 months early!</li>
                </ul>
              </div>
            </div>
          </div>
        </div>
      </section>

      <!-- MODULE: SUCCESS TIMELINE -->
      <section class="section">
        <div class="container">
          <div class="section__header animate-on-scroll">
            <span class="section__tag">Visa Strategy</span>
            <h2 class="section__title">Success <span>Timeline</span></h2>
            <p style="color:var(--gray); margin-top: 0.5rem;">Denmark’s student visa (SIRI) processing takes approx. 60 days. Timely documentation is key for Nordic success.</p>
            <div class="accent-bar"></div>
          </div>
          <div class="grid grid--2 gap--4 align-center">
             <div class="animate-on-scroll">
               <ul class="benefit-list">
                 <li><i class="fa-solid fa-circle-check" style="color:var(--neon-blue);"></i> Autumn Intake: Apply Jan 15 – March 1, 2026</li>
                 <li><i class="fa-solid fa-circle-check" style="color:var(--neon-blue);"></i> Spring Intake: Recommended by Sept 1, 2026</li>
                 <li><i class="fa-solid fa-circle-check" style="color:var(--neon-blue);"></i> SIRI Processing: Plan for a 60-day buffer</li>
                 <li><i class="fa-solid fa-circle-check" style="color:var(--neon-blue);"></i> Mandatory address registration for CPR Number</li>
               </ul>
             </div>
             <div class="animate-on-scroll delay-1">
               <div class="info-card" style="background:var(--primary); color:white; border:none;">
                 <div class="ic-body">
                   <h4 style="color:white; margin-bottom:1rem;">Housing Competition</h4>
                   <p style="opacity:0.9;"><strong>Copenhagen/Aarhus:</strong> Start 6 months early.</p>
                   <p style="opacity:0.9;"><strong>Mandatory:</strong> Address required for bank/health access.</p>
                 </div>
               </div>
             </div>
          </div>
        </div>
      </section>

      <!-- MODULE: CLIMATE & FOOD -->
      <section class="section" style="background:#f8fafc; border-top:1px solid #e2e8f0;">
        <div class="container">
          <div class="grid grid--2 gap--4">
            <div class="animate-on-scroll">
              <h3 style="font-size:1.5rem; color:#0f172a; margin-bottom:1.5rem; font-weight:800;"><i class="fa-solid fa-cloud-sun" style="color:var(--neon-blue); margin-right:0.5rem;"></i> Nordic Climate</h3>
              <ul class="benefit-list" style="margin-bottom: 2rem;">
                <li><i class="fa-solid fa-snowflake" style="color:var(--neon-blue);"></i> Cold winters with short daylight hours</li>
                <li><i class="fa-solid fa-wind" style="color:var(--neon-blue);"></i> Refreshing coastal breezes and cool summers</li>
                <li><i class="fa-solid fa-city" style="color:var(--neon-blue);"></i> Top cities: Copenhagen, Aarhus, Aalborg, Odense</li>
                <li><i class="fa-solid fa-sun" style="color:var(--neon-orange);"></i> Enjoy the "Hygge" lifestyle during winter</li>
              </ul>
            </div>
            <div class="animate-on-scroll delay-1">
              <h3 style="font-size:1.5rem; color:#0f172a; margin-bottom:1.5rem; font-weight:800;"><i class="fa-solid fa-utensils" style="color:var(--neon-green); margin-right:0.5rem;"></i> Food & Lifestyle</h3>
              <p style="color:var(--gray); margin-bottom:1.5rem; line-height:1.6;">Healthy, fresh, and multicultural dining:</p>
              <ul class="benefit-list">
                <li><i class="fa-solid fa-store" style="color:var(--neon-green);"></i> Indian restaurants and Asian supermarkets available</li>
                <li><i class="fa-solid fa-leaf" style="color:var(--neon-green);"></i> High focus on organic and sustainable food</li>
                <li><i class="fa-solid fa-bread-slice" style="color:var(--neon-green);"></i> Authentic Smørrebrød and world-class pastries</li>
                <li><i class="fa-solid fa-fish" style="color:var(--neon-green);"></i> Fresh seafood and modern Nordic cuisine</li>
              </ul>
            </div>
          </div>
        </div>
      </section>

      <!-- MODULE: ALUMNI SUCCESS -->
      <section class="section">
        <div class="container">
          <div class="section__header animate-on-scroll">
            <span class="section__tag">Global Network</span>
            <h2 class="section__title">Alumni <span>Success</span></h2>
            <p style="color:var(--gray); margin-top: 0.5rem;">Danish graduates are highly sought after in green tech, pharma, and automation sectors.</p>
            <div class="accent-bar"></div>
          </div>
          <div class="roi-grid" style="grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));">
            <div class="roi-card animate-on-scroll" style="text-align:center; padding:1.5rem;">
              <div class="icon-colorful icon-colorful--blue" style="margin:0 auto 1rem; width:48px; height:48px; font-size:1.25rem;"><i class="fa-solid fa-wind"></i></div>
              <h4 style="font-size:1.1rem; color:#0f172a; margin:0; font-weight:700;">Renewables</h4>
            </div>
            <div class="roi-card animate-on-scroll delay-1" style="text-align:center; padding:1.5rem;">
              <div class="icon-colorful icon-colorful--teal" style="margin:0 auto 1rem; width:48px; height:48px; font-size:1.25rem;"><i class="fa-solid fa-vial-circle-check"></i></div>
              <h4 style="font-size:1.1rem; color:#0f172a; margin:0; font-weight:700;">Pharma</h4>
            </div>
            <div class="roi-card animate-on-scroll delay-2" style="text-align:center; padding:1.5rem;">
              <div class="icon-colorful icon-colorful--green" style="margin:0 auto 1rem; width:48px; height:48px; font-size:1.25rem;"><i class="fa-solid fa-robot"></i></div>
              <h4 style="font-size:1.1rem; color:#0f172a; margin:0; font-weight:700;">Robotics</h4>
            </div>
            <div class="roi-card animate-on-scroll delay-3" style="text-align:center; padding:1.5rem;">
              <div class="icon-colorful icon-colorful--purple" style="margin:0 auto 1rem; width:48px; height:48px; font-size:1.25rem;"><i class="fa-solid fa-chart-line"></i></div>
              <h4 style="font-size:1.1rem; color:#0f172a; margin:0; font-weight:700;">Business</h4>
            </div>
            <div class="roi-card animate-on-scroll delay-4" style="text-align:center; padding:1.5rem;">
              <div class="icon-colorful icon-colorful--orange" style="margin:0 auto 1rem; width:48px; height:48px; font-size:1.25rem;"><i class="fa-solid fa-cubes"></i></div>
              <h4 style="font-size:1.1rem; color:#0f172a; margin:0; font-weight:700;">Design/LEGO</h4>
            </div>
            <div class="roi-card animate-on-scroll delay-5" style="text-align:center; padding:1.5rem;">
              <div class="icon-colorful icon-colorful--orange" style="margin:0 auto 1rem; width:48px; height:48px; font-size:1.25rem; background:rgba(234,88,12,0.1); color:#ea580c;"><i class="fa-solid fa-microchip"></i></div>
              <h4 style="font-size:1.1rem; color:#0f172a; margin:0; font-weight:700;">IT/Tech</h4>
            </div>
          </div>
        </div>
      </section>
      <?php elseif ($country_slug === 'finland'): ?>

      <!-- MODULE: THE 2026 STRATEGIC ADVANTAGE -->
      <section class="section" style="background:#f8fafc; border-top:1px solid #e2e8f0;">
        <div class="container">
          <div class="section__header animate-on-scroll">
            <span class="section__tag">Happiest Education</span>
            <h2 class="section__title">The 2026 <span>Strategic Advantage</span></h2>
            <p style="color:var(--gray); margin-top: 0.5rem;">"Finland offers a tailored approach to success." Choose between world-class Research Universities for academic innovation or Universities of Applied Sciences (UAS) for industry-integrated practical learning.</p>
            <div class="accent-bar"></div>
          </div>
          <div class="grid grid--3 gap--4">
            <div class="pillar-card animate-on-scroll">
              <div class="pillar-num" style="background:rgba(139, 92, 246, 0.08); color:var(--neon-purple);"><i class="fa-solid fa-graduation-cap"></i></div>
              <h4>Joint App System</h4>
              <p>Apply to up to 6 programs in one cycle via Studyinfo.fi. Bluestone handles the complex documentation for scholarship-integrated admissions.</p>
            </div>
            <div class="pillar-card animate-on-scroll delay-1">
              <div class="pillar-num" style="background:rgba(16, 185, 129, 0.08); color:var(--neon-green);"><i class="fa-solid fa-hand-holding-dollar"></i></div>
              <h4>Scholarship Integration</h4>
              <p>Most universities offer 50% – 100% Tuition Waivers integrated directly into admission offers. No separate scholarship application needed at many institutions.</p>
            </div>
            <div class="pillar-card animate-on-scroll delay-2">
              <div class="pillar-num" style="background:rgba(245, 158, 11, 0.08); color:var(--neon-orange);"><i class="fa-solid fa-microchip"></i></div>
              <h4>Innovation Economy</h4>
              <p>Gain exposure to world-leading sectors in 6G (University of Oulu), AI, Clean Energy, and Gaming. Home to giants like Nokia and Supercell.</p>
            </div>
          </div>
        </div>
      </section>

      <!-- MODULE: WORK & RESIDENCE BLUEPRINT -->
      <section class="section">
        <div class="container">
          <div class="section__header animate-on-scroll">
            <span class="section__tag">The 2026 Finland Work & Career Path</span>
            <h2 class="section__title">The “Work & <span>Residence” Blueprint</span></h2>
            <div class="accent-bar"></div>
          </div>
          <div class="grid grid--2 gap--4">
            <div class="info-card animate-on-scroll">
              <div class="ic-header"><div class="icon-colorful icon-colorful--blue" style="font-size:1.5rem; margin-right:.75rem"><i class="fa-solid fa-clock"></i></div> <h4>Work During Study</h4></div>
              <div class="ic-body">
                <ul class="benefit-list" style="margin-top: 1rem;">
                  <li><i class="fa-solid fa-check-circle text-success"></i> Work up to 30 hours/week (Updated for 2026)</li>
                  <li><i class="fa-solid fa-briefcase text-success"></i> Unlimited internships linked to your degree field</li>
                </ul>
              </div>
            </div>
            <div class="info-card animate-on-scroll delay-1">
              <div class="ic-header"><div class="icon-colorful icon-colorful--orange" style="font-size:1.5rem; margin-right:.75rem"><i class="fa-solid fa-user-tie"></i></div> <h4>2-Year Job Search Permit</h4></div>
              <div class="ic-body">
                <ul class="benefit-list" style="margin-top: 1rem;">
                  <li><i class="fa-solid fa-calendar-check text-success"></i> Stay for up to 2 years post-graduation to find work</li>
                  <li><i class="fa-solid fa-rocket text-success"></i> Launch startups or build long-term careers locally</li>
                </ul>
              </div>
            </div>
            <div class="info-card animate-on-scroll delay-2">
              <div class="ic-header"><div class="icon-colorful icon-colorful--purple" style="font-size:1.5rem; margin-right:.75rem"><i class="fa-solid fa-passport"></i></div> <h4>Path to Permanent Residency</h4></div>
              <div class="ic-body">
                <ul class="benefit-list" style="margin-top: 1rem;">
                  <li><i class="fa-solid fa-id-card text-success"></i> PR eligibility after just 4 years of continuous residence</li>
                  <li><i class="fa-solid fa-language text-success"></i> Free Finnish language classes at most universities</li>
                </ul>
              </div>
            </div>
            <div class="info-card animate-on-scroll delay-3">
              <div class="ic-header"><div class="icon-colorful icon-colorful--green" style="font-size:1.5rem; margin-right:.75rem"><i class="fa-solid fa-vial"></i></div> <h4>PhD Advantage</h4></div>
              <div class="ic-body">
                <p style="color:var(--gray); font-size:0.95rem;">PhD programs are <strong>Tuition-Free</strong> for all nationalities at public universities, with numerous funded research opportunities.</p>
              </div>
            </div>
          </div>
        </div>
      </section>

      <!-- MODULE: FEES & FINANCES -->
      <section class="section" style="background:#f8fafc; border-top:1px solid #e2e8f0;">
        <div class="container">
          <div class="grid grid--2 gap--4">
            <div class="animate-on-scroll">
              <h3 style="font-size:1.5rem; color:#0f172a; margin-bottom:1.5rem; font-weight:800;"><i class="fa-solid fa-euro-sign" style="color:var(--neon-blue); margin-right:0.5rem;"></i> Estimated Annual Budget</h3>
              <div class="stayback-container">
                <table class="stayback-table">
                    <thead>
                        <tr>
                            <th>Category</th>
                            <th>Estimated Cost (INR)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr><td style="font-weight:700;">Tuition Fees</td><td>₹7L – ₹16L/year</td></tr>
                        <tr><td style="font-weight:700;">Living Expenses</td><td>₹8L – ₹9L/year</td></tr>
                        <tr><td style="font-weight:700;">Residence Permit</td><td>₹50k – ₹1L</td></tr>
                        <tr><td style="font-weight:700;">Studyinfo Fee</td><td>€100 (~₹9k)</td></tr>
                    </tbody>
                </table>
              </div>
            </div>
            <div class="animate-on-scroll delay-1">
              <h3 style="font-size:1.5rem; color:#0f172a; margin-bottom:1.5rem; font-weight:800;"><i class="fa-solid fa-circle-info" style="color:var(--neon-orange); margin-right:0.5rem;"></i> 2026 Migri Proof of Funds</h3>
              <div class="highlight-box bg-dots" style="background: white; border: 1px solid #e2e8f0; border-radius: 20px; padding: 2rem;">
                <ul class="benefit-list">
                  <li><i class="fa-solid fa-check-circle text-success"></i> Living Funds (1 Year): €9,600 (~₹8.7L)</li>
                  <li><i class="fa-solid fa-check-circle text-success"></i> Monthly Budget: €800 / month</li>
                  <li><i class="fa-solid fa-check-circle text-success"></i> Residence Permit (Online): €450</li>
                  <li><i class="fa-solid fa-bolt text-danger"></i> <strong>Pro Tip:</strong> Apply via "Enter Finland" immediately after admit!</li>
                </ul>
              </div>
            </div>
          </div>
        </div>
      </section>

      <!-- MODULE: SUCCESS TIMELINE -->
      <section class="section">
        <div class="container">
          <div class="section__header animate-on-scroll">
            <span class="section__tag">Application Strategy</span>
            <h2 class="section__title">Success <span>Timeline</span></h2>
            <p style="color:var(--gray); margin-top: 0.5rem;">Finland’s joint application system is highly structured. Timing is critical for securing the best scholarships.</p>
            <div class="accent-bar"></div>
          </div>
          <div class="grid grid--2 gap--4 align-center">
             <div class="animate-on-scroll">
               <ul class="benefit-list">
                 <li><i class="fa-solid fa-circle-check" style="color:var(--neon-blue);"></i> Joint Application: Jan 2026 cycle for Autumn start</li>
                 <li><i class="fa-solid fa-circle-check" style="color:var(--neon-blue);"></i> Rolling Admits (UAS): Nov 2025 – March 2026</li>
                 <li><i class="fa-solid fa-circle-check" style="color:var(--neon-blue);"></i> Scholarship Priority: Early apps get 50% - 100% waivers</li>
                 <li><i class="fa-solid fa-circle-check" style="color:var(--neon-blue);"></i> Permit Processing: Plan for a 1–3 month buffer</li>
               </ul>
             </div>
             <div class="animate-on-scroll delay-1">
               <div class="info-card" style="background:var(--primary); color:white; border:none;">
                 <div class="ic-body">
                   <h4 style="color:white; margin-bottom:1rem;">Joint App Advantage</h4>
                   <p style="opacity:0.9;"><strong>Single Process:</strong> Apply to 6 programs at once.</p>
                   <p style="opacity:0.9;"><strong>Integrated:</strong> Scholarships awarded with admission.</p>
                 </div>
               </div>
             </div>
          </div>
        </div>
      </section>

      <!-- MODULE: CLIMATE & FOOD -->
      <section class="section" style="background:#f8fafc; border-top:1px solid #e2e8f0;">
        <div class="container">
          <div class="grid grid--2 gap--4">
            <div class="animate-on-scroll">
              <h3 style="font-size:1.5rem; color:#0f172a; margin-bottom:1.5rem; font-weight:800;"><i class="fa-solid fa-snowflake" style="color:var(--neon-blue); margin-right:0.5rem;"></i> Nordic Lifestyle</h3>
              <ul class="benefit-list" style="margin-bottom: 2rem;">
                <li><i class="fa-solid fa-moon" style="color:var(--neon-blue);"></i> Snowy winters with magical Northern Lights</li>
                <li><i class="fa-solid fa-tree" style="color:var(--neon-green);"></i> Beautiful lakes, forests, and pristine air quality</li>
                <li><i class="fa-solid fa-sun" style="color:var(--neon-orange);"></i> Extended daylight hours and mild summers</li>
                <li><i class="fa-solid fa-shield-halved" style="color:var(--neon-blue);"></i> World-class public infrastructure and safety</li>
              </ul>
            </div>
            <div class="animate-on-scroll delay-1">
              <h3 style="font-size:1.5rem; color:#0f172a; margin-bottom:1.5rem; font-weight:800;"><i class="fa-solid fa-utensils" style="color:var(--neon-green); margin-right:0.5rem;"></i> Food & Dining</h3>
              <p style="color:var(--gray); margin-bottom:1.5rem; line-height:1.6;">High-quality, healthy, and sustainable options:</p>
              <ul class="benefit-list">
                <li><i class="fa-solid fa-store" style="color:var(--neon-green);"></i> Indian restaurants & vegan options in major hubs</li>
                <li><i class="fa-solid fa-school" style="color:var(--neon-green);"></i> Affordable and nutritious university cafeterias</li>
                <li><i class="fa-solid fa-fish" style="color:var(--neon-green);"></i> Fresh salmon, rye bread, and forest berry desserts</li>
                <li><i class="fa-solid fa-mug-hot" style="color:var(--neon-green);"></i> Vibrant coffee culture and peaceful environments</li>
              </ul>
            </div>
          </div>
        </div>
      </section>

      <!-- MODULE: ALUMNI SUCCESS -->
      <section class="section">
        <div class="container">
          <div class="section__header animate-on-scroll">
            <span class="section__tag">Global Network</span>
            <h2 class="section__title">Alumni <span>Success</span></h2>
            <p style="color:var(--gray); margin-top: 0.5rem;">Finnish graduates lead the world in 6G, clean energy, and sustainable innovation.</p>
            <div class="accent-bar"></div>
          </div>
          <div class="roi-grid" style="grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));">
            <div class="roi-card animate-on-scroll" style="text-align:center; padding:1.5rem;">
              <div class="icon-colorful icon-colorful--blue" style="margin:0 auto 1rem; width:48px; height:48px; font-size:1.25rem;"><i class="fa-solid fa-tower-broadcast"></i></div>
              <h4 style="font-size:1.1rem; color:#0f172a; margin:0; font-weight:700;">6G & Telecom</h4>
            </div>
            <div class="roi-card animate-on-scroll delay-1" style="text-align:center; padding:1.5rem;">
              <div class="icon-colorful icon-colorful--teal" style="margin:0 auto 1rem; width:48px; height:48px; font-size:1.25rem;"><i class="fa-solid fa-gamepad"></i></div>
              <h4 style="font-size:1.1rem; color:#0f172a; margin:0; font-weight:700;">Gaming/IT</h4>
            </div>
            <div class="roi-card animate-on-scroll delay-2" style="text-align:center; padding:1.5rem;">
              <div class="icon-colorful icon-colorful--green" style="margin:0 auto 1rem; width:48px; height:48px; font-size:1.25rem;"><i class="fa-solid fa-leaf"></i></div>
              <h4 style="font-size:1.1rem; color:#0f172a; margin:0; font-weight:700;">Clean Energy</h4>
            </div>
            <div class="roi-card animate-on-scroll delay-3" style="text-align:center; padding:1.5rem;">
              <div class="icon-colorful icon-colorful--purple" style="margin:0 auto 1rem; width:48px; height:48px; font-size:1.25rem;"><i class="fa-solid fa-robot"></i></div>
              <h4 style="font-size:1.1rem; color:#0f172a; margin:0; font-weight:700;">Automation</h4>
            </div>
            <div class="roi-card animate-on-scroll delay-4" style="text-align:center; padding:1.5rem;">
              <div class="icon-colorful icon-colorful--orange" style="margin:0 auto 1rem; width:48px; height:48px; font-size:1.25rem;"><i class="fa-solid fa-vial-circle-check"></i></div>
              <h4 style="font-size:1.1rem; color:#0f172a; margin:0; font-weight:700;">Life Sciences</h4>
            </div>
            <div class="roi-card animate-on-scroll delay-5" style="text-align:center; padding:1.5rem;">
              <div class="icon-colorful icon-colorful--orange" style="margin:0 auto 1rem; width:48px; height:48px; font-size:1.25rem; background:rgba(234,88,12,0.1); color:#ea580c;"><i class="fa-solid fa-palette"></i></div>
              <h4 style="font-size:1.1rem; color:#0f172a; margin:0; font-weight:700;">Art & Design</h4>
            </div>
          </div>
        </div>
      </section>
      <?php elseif ($country_slug === 'hungary'): ?>

      <!-- MODULE: THE 2026 STRATEGIC ADVANTAGE -->
      <section class="section" style="background:#f8fafc; border-top:1px solid #e2e8f0;">
        <div class="container">
          <div class="section__header animate-on-scroll">
            <span class="section__tag">High-Value Europe</span>
            <h2 class="section__title">The 2026 <span>Strategic Advantage</span></h2>
            <p style="color:var(--gray); margin-top: 0.5rem;">"Hungary offers a prestigious European degree without the financial burden." Access one of the most affordable student lifestyles in the EU with world-class medical and engineering excellence.</p>
            <div class="accent-bar"></div>
          </div>
          <div class="grid grid--3 gap--4">
            <div class="pillar-card animate-on-scroll">
              <div class="pillar-num" style="background:rgba(139, 92, 246, 0.08); color:var(--neon-purple);"><i class="fa-solid fa-hand-holding-dollar"></i></div>
              <h4>Stipendium Pathway</h4>
              <p>Home to the prestigious Stipendium Hungaricum — a fully-funded government scholarship covering tuition, living stipend, and accommodation.</p>
            </div>
            <div class="pillar-card animate-on-scroll delay-1">
              <div class="pillar-num" style="background:rgba(16, 185, 129, 0.08); color:var(--neon-green);"><i class="fa-solid fa-stethoscope"></i></div>
              <h4>Medical Excellence</h4>
              <p>Globally recognized Medical, Dentistry, and Pharmacy degrees valid across the UK, USA, and EU at a fraction of the cost of private Indian colleges.</p>
            </div>
            <div class="pillar-card animate-on-scroll delay-2">
              <div class="pillar-num" style="background:rgba(245, 158, 11, 0.08); color:var(--neon-orange);"><i class="fa-solid fa-passport"></i></div>
              <h4>Schengen Gateway</h4>
              <p>A Hungarian Residence Permit grants visa-free travel across 29 Schengen countries, providing unparalleled international networking and exposure.</p>
            </div>
          </div>
        </div>
      </section>

      <!-- MODULE: STAY-BACK & WORK FRAMEWORK -->
      <section class="section">
        <div class="container">
          <div class="section__header animate-on-scroll">
            <span class="section__tag">The 2026 Hungary Work & Residence Blueprint</span>
            <h2 class="section__title">The “Stay-Back” & <span>Work Framework</span></h2>
            <div class="accent-bar"></div>
          </div>
          <div class="grid grid--2 gap--4">
            <div class="info-card animate-on-scroll">
              <div class="ic-header"><div class="icon-colorful icon-colorful--blue" style="font-size:1.5rem; margin-right:.75rem"><i class="fa-solid fa-clock"></i></div> <h4>Work During Study</h4></div>
              <div class="ic-body">
                <ul class="benefit-list" style="margin-top: 1rem;">
                  <li><i class="fa-solid fa-check-circle text-success"></i> Work up to 30 hours/week during sessions</li>
                  <li><i class="fa-solid fa-sun text-success"></i> Full-time work during semester holidays</li>
                </ul>
              </div>
            </div>
            <div class="info-card animate-on-scroll delay-1">
              <div class="ic-header"><div class="icon-colorful icon-colorful--orange" style="font-size:1.5rem; margin-right:.75rem"><i class="fa-solid fa-user-check"></i></div> <h4>9-Month Stay-Back</h4></div>
              <div class="ic-body">
                <ul class="benefit-list" style="margin-top: 1rem;">
                  <li><i class="fa-solid fa-calendar-check text-success"></i> Apply for "Study-to-Work" residence extension</li>
                  <li><i class="fa-solid fa-rocket text-success"></i> Search for skilled employment or launch startups</li>
                </ul>
              </div>
            </div>
            <div class="info-card animate-on-scroll delay-2">
              <div class="ic-header"><div class="icon-colorful icon-colorful--purple" style="font-size:1.5rem; margin-right:.75rem"><i class="fa-solid fa-earth-europe"></i></div> <h4>Degree Portability</h4></div>
              <div class="ic-body">
                <ul class="benefit-list" style="margin-top: 1rem;">
                  <li><i class="fa-solid fa-id-card text-success"></i> Bologna Process degrees valid across the EU & UK</li>
                  <li><i class="fa-solid fa-briefcase text-success"></i> Direct transition into Hungary's skilled work permits</li>
                </ul>
              </div>
            </div>
            <div class="info-card animate-on-scroll delay-3">
              <div class="ic-header"><div class="icon-colorful icon-colorful--green" style="font-size:1.5rem; margin-right:.75rem"><i class="fa-solid fa-industry"></i></div> <h4>Industry Hub</h4></div>
              <div class="ic-body">
                <p style="color:var(--gray); font-size:0.95rem;">Access Central Europe’s manufacturing hub with giants like <strong>Audi, Mercedes-Benz, Bosch, and IBM</strong> offering career paths.</p>
              </div>
            </div>
          </div>
        </div>
      </section>

      <!-- MODULE: FEES & FINANCES -->
      <section class="section" style="background:#f8fafc; border-top:1px solid #e2e8f0;">
        <div class="container">
          <div class="grid grid--2 gap--4">
            <div class="animate-on-scroll">
              <h3 style="font-size:1.5rem; color:#0f172a; margin-bottom:1.5rem; font-weight:800;"><i class="fa-solid fa-euro-sign" style="color:var(--neon-blue); margin-right:0.5rem;"></i> Average Annual Tuition</h3>
              <div class="stayback-container">
                <table class="stayback-table">
                    <thead>
                        <tr>
                            <th>Program Type</th>
                            <th>Average Annual Fee</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr><td style="font-weight:700;">Bachelor’s Programs</td><td>€2,500 – €5,000</td></tr>
                        <tr><td style="font-weight:700;">Master’s Programs</td><td>€3,000 – €6,000</td></tr>
                        <tr><td style="font-weight:700;">Medical Programs</td><td>€12,000 – €16,000</td></tr>
                        <tr><td style="font-weight:700;">Living Expenses</td><td>€550 – €750/month</td></tr>
                    </tbody>
                </table>
              </div>
            </div>
            <div class="animate-on-scroll delay-1">
              <h3 style="font-size:1.5rem; color:#0f172a; margin-bottom:1.5rem; font-weight:800;"><i class="fa-solid fa-circle-info" style="color:var(--neon-orange); margin-right:0.5rem;"></i> 2026 Proof of Funds</h3>
              <div class="highlight-box bg-dots" style="background: white; border: 1px solid #e2e8f0; border-radius: 20px; padding: 2rem;">
                <ul class="benefit-list">
                  <li><i class="fa-solid fa-check-circle text-success"></i> Living Funds (1 Year): €6.5k – €8k</li>
                  <li><i class="fa-solid fa-check-circle text-success"></i> Monthly Proof: €550 – €750</li>
                  <li><i class="fa-solid fa-check-circle text-success"></i> D-Type Visa Fee: €110 (~₹10k)</li>
                  <li><i class="fa-solid fa-pen-to-square text-danger"></i> <strong>Pro Tip:</strong> Most universities require entrance exams!</li>
                </ul>
              </div>
            </div>
          </div>
        </div>
      </section>

      <!-- MODULE: SUCCESS TIMELINE -->
      <section class="section">
        <div class="container">
          <div class="section__header animate-on-scroll">
            <span class="section__tag">Application Strategy</span>
            <h2 class="section__title">Success <span>Timeline</span></h2>
            <p style="color:var(--gray); margin-top: 0.5rem;">Hungary’s application process often includes interviews and exams. Strategic preparation is the key to success.</p>
            <div class="accent-bar"></div>
          </div>
          <div class="grid grid--2 gap--4 align-center">
             <div class="animate-on-scroll">
               <ul class="benefit-list">
                 <li><i class="fa-solid fa-circle-check" style="color:var(--neon-blue);"></i> Fall Intake: Recommended Feb – April 2026 app</li>
                 <li><i class="fa-solid fa-circle-check" style="color:var(--neon-blue);"></i> Spring Intake: Recommended by Oct 2026</li>
                 <li><i class="fa-solid fa-circle-check" style="color:var(--neon-blue);"></i> Stipendium Deadline: Apply by Jan each year</li>
                 <li><i class="fa-solid fa-circle-check" style="color:var(--neon-blue);"></i> Entrance Support: Mock tests & interview prep</li>
               </ul>
             </div>
             <div class="animate-on-scroll delay-1">
               <div class="info-card" style="background:var(--primary); color:white; border:none;">
                 <div class="ic-body">
                   <h4 style="color:white; margin-bottom:1rem;">Stipendium Hungaricum</h4>
                   <p style="opacity:0.9;"><strong>Fully Funded:</strong> Tuition, Stipend, & Housing.</p>
                   <p style="opacity:0.9;"><strong>Highly Selective:</strong> Apply early for top consideration.</p>
                 </div>
               </div>
             </div>
          </div>
        </div>
      </section>

      <!-- MODULE: CLIMATE & FOOD -->
      <section class="section" style="background:#f8fafc; border-top:1px solid #e2e8f0;">
        <div class="container">
          <div class="grid grid--2 gap--4">
            <div class="animate-on-scroll">
              <h3 style="font-size:1.5rem; color:#0f172a; margin-bottom:1.5rem; font-weight:800;"><i class="fa-solid fa-cloud-sun" style="color:var(--neon-blue); margin-right:0.5rem;"></i> Central European Climate</h3>
              <ul class="benefit-list" style="margin-bottom: 2rem;">
                <li><i class="fa-solid fa-snowflake" style="color:var(--neon-blue);"></i> Cold winters with magical snowfall</li>
                <li><i class="fa-solid fa-sun" style="color:var(--neon-orange);"></i> Warm, vibrant summers and pleasant springs</li>
                <li><i class="fa-solid fa-city" style="color:var(--neon-blue);"></i> Top cities: Budapest, Debrecen, Szeged, Pécs</li>
                <li><i class="fa-solid fa-map-location-dot" style="color:var(--neon-green);"></i> Perfectly located for exploring all of Europe</li>
              </ul>
            </div>
            <div class="animate-on-scroll delay-1">
              <h3 style="font-size:1.5rem; color:#0f172a; margin-bottom:1.5rem; font-weight:800;"><i class="fa-solid fa-utensils" style="color:var(--neon-green); margin-right:0.5rem;"></i> Food & Lifestyle</h3>
              <p style="color:var(--gray); margin-bottom:1.5rem; line-height:1.6;">Hearty, flavorful, and multicultural dining:</p>
              <ul class="benefit-list">
                <li><i class="fa-solid fa-store" style="color:var(--neon-green);"></i> Easy access to Indian restaurants and vegetarian food</li>
                <li><i class="fa-solid fa-leaf" style="color:var(--neon-green);"></i> Halal food options available in major student hubs</li>
                <li><i class="fa-solid fa-bowl-food" style="color:var(--neon-green);"></i> Authentic Goulash, Lángos, and Chimney Cake</li>
                <li><i class="fa-solid fa-wallet" style="color:var(--neon-green);"></i> One of the most affordable living costs in the EU</li>
              </ul>
            </div>
          </div>
        </div>
      </section>

      <!-- MODULE: ALUMNI SUCCESS -->
      <section class="section">
        <div class="container">
          <div class="section__header animate-on-scroll">
            <span class="section__tag">Global Network</span>
            <h2 class="section__title">Alumni <span>Success</span></h2>
            <p style="color:var(--gray); margin-top: 0.5rem;">Hungarian graduates are leaders in European medicine, automotive tech, and IT.</p>
            <div class="accent-bar"></div>
          </div>
          <div class="roi-grid" style="grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));">
            <div class="roi-card animate-on-scroll" style="text-align:center; padding:1.5rem;">
              <div class="icon-colorful icon-colorful--blue" style="margin:0 auto 1rem; width:48px; height:48px; font-size:1.25rem;"><i class="fa-solid fa-user-doctor"></i></div>
              <h4 style="font-size:1.1rem; color:#0f172a; margin:0; font-weight:700;">Medicine</h4>
            </div>
            <div class="roi-card animate-on-scroll delay-1" style="text-align:center; padding:1.5rem;">
              <div class="icon-colorful icon-colorful--teal" style="margin:0 auto 1rem; width:48px; height:48px; font-size:1.25rem;"><i class="fa-solid fa-car"></i></div>
              <h4 style="font-size:1.1rem; color:#0f172a; margin:0; font-weight:700;">Automotive</h4>
            </div>
            <div class="roi-card animate-on-scroll delay-2" style="text-align:center; padding:1.5rem;">
              <div class="icon-colorful icon-colorful--green" style="margin:0 auto 1rem; width:48px; height:48px; font-size:1.25rem;"><i class="fa-solid fa-laptop-code"></i></div>
              <h4 style="font-size:1.1rem; color:#0f172a; margin:0; font-weight:700;">IT & AI</h4>
            </div>
            <div class="roi-card animate-on-scroll delay-3" style="text-align:center; padding:1.5rem;">
              <div class="icon-colorful icon-colorful--purple" style="margin:0 auto 1rem; width:48px; height:48px; font-size:1.25rem;"><i class="fa-solid fa-chart-pie"></i></div>
              <h4 style="font-size:1.1rem; color:#0f172a; margin:0; font-weight:700;">Finance</h4>
            </div>
            <div class="roi-card animate-on-scroll delay-4" style="text-align:center; padding:1.5rem;">
              <div class="icon-colorful icon-colorful--orange" style="margin:0 auto 1rem; width:48px; height:48px; font-size:1.25rem;"><i class="fa-solid fa-wheat-awn"></i></div>
              <h4 style="font-size:1.1rem; color:#0f172a; margin:0; font-weight:700;">Agriculture</h4>
            </div>
            <div class="roi-card animate-on-scroll delay-5" style="text-align:center; padding:1.5rem;">
              <div class="icon-colorful icon-colorful--orange" style="margin:0 auto 1rem; width:48px; height:48px; font-size:1.25rem; background:rgba(234,88,12,0.1); color:#ea580c;"><i class="fa-solid fa-flask"></i></div>
              <h4 style="font-size:1.1rem; color:#0f172a; margin:0; font-weight:700;">Biotech</h4>
            </div>
          </div>
        </div>
      </section>
      <?php elseif ($country_slug === 'lithuania'): ?>

      <!-- MODULE: THE 2026 STRATEGIC ADVANTAGE -->
      <section class="section" style="background:#f8fafc; border-top:1px solid #e2e8f0;">
        <div class="container">
          <div class="section__header animate-on-scroll">
            <span class="section__tag">Baltic Tech-Hub</span>
            <h2 class="section__title">The 2026 <span>Strategic Advantage</span></h2>
            <p style="color:var(--gray); margin-top: 0.5rem;">"Lithuania has digitized the future of education." Access the EU’s leading Fintech licensing hub with a modern, fast-track digital immigration system.</p>
            <div class="accent-bar"></div>
          </div>
          <div class="grid grid--3 gap--4">
            <div class="pillar-card animate-on-scroll">
              <div class="pillar-num" style="background:rgba(139, 92, 246, 0.08); color:var(--neon-purple);"><i class="fa-solid fa-bolt"></i></div>
              <h4>MIGRIS Fast-Track</h4>
              <p>Direct application for a Temporary Residence Permit (TRP) via the MIGRIS digital portal. No separate National D-Visa required for students.</p>
            </div>
            <div class="pillar-card animate-on-scroll delay-1">
              <div class="pillar-num" style="background:rgba(16, 185, 129, 0.08); color:var(--neon-green);"><i class="fa-solid fa-coins"></i></div>
              <h4>Fintech Capital</h4>
              <p>Lithuania is the EU’s top destination for Fintech licensing. Ideal for careers in Banking, AI, Cyber Security, and Digital Finance.</p>
            </div>
            <div class="pillar-card animate-on-scroll delay-2">
              <div class="pillar-num" style="background:rgba(245, 158, 11, 0.08); color:var(--neon-orange);"><i class="fa-solid fa-rocket"></i></div>
              <h4>Startup Ecosystem</h4>
              <p>A fast-growing technology hub with simplified hiring for international graduates and specialized support for launching new ventures.</p>
            </div>
          </div>
        </div>
      </section>

      <!-- MODULE: WORK & DISCOVERY FRAMEWORK -->
      <section class="section">
        <div class="container">
          <div class="section__header animate-on-scroll">
            <span class="section__tag">The 2026 Lithuania Work & Residence Blueprint</span>
            <h2 class="section__title">The “Work & <span>Discovery” Framework</span></h2>
            <div class="accent-bar"></div>
          </div>
          <div class="grid grid--2 gap--4">
            <div class="info-card animate-on-scroll">
              <div class="ic-header"><div class="icon-colorful icon-colorful--blue" style="font-size:1.5rem; margin-right:.75rem"><i class="fa-solid fa-clock"></i></div> <h4>Flexible Work Rights</h4></div>
              <div class="ic-body">
                <ul class="benefit-list" style="margin-top: 1rem;">
                  <li><i class="fa-solid fa-check-circle text-success"></i> Master's/PhD: Full-time work (40h/week) during studies</li>
                  <li><i class="fa-solid fa-check-circle text-success"></i> Bachelor's: Up to 20 hours/week during sessions</li>
                </ul>
              </div>
            </div>
            <div class="info-card animate-on-scroll delay-1">
              <div class="ic-header"><div class="icon-colorful icon-colorful--orange" style="font-size:1.5rem; margin-right:.75rem"><i class="fa-solid fa-user-check"></i></div> <h4>12-Month Job Search</h4></div>
              <div class="ic-body">
                <ul class="benefit-list" style="margin-top: 1rem;">
                  <li><i class="fa-solid fa-calendar-check text-success"></i> Post-study TRP for seeking work or starting a business</li>
                  <li><i class="fa-solid fa-briefcase text-success"></i> No separate work permit required for graduates</li>
                </ul>
              </div>
            </div>
            <div class="info-card animate-on-scroll delay-2">
              <div class="ic-header"><div class="icon-colorful icon-colorful--purple" style="font-size:1.5rem; margin-right:.75rem"><i class="fa-solid fa-shield-halved"></i></div> <h4>Simplified Hiring</h4></div>
              <div class="ic-body">
                <ul class="benefit-list" style="margin-top: 1rem;">
                  <li><i class="fa-solid fa-check-double text-success"></i> No Labour Market Test required for local graduates</li>
                  <li><i class="fa-solid fa-earth-europe text-success"></i> TRP allows visa-free travel across 29 Schengen states</li>
                </ul>
              </div>
            </div>
            <div class="info-card animate-on-scroll delay-3">
              <div class="ic-header"><div class="icon-colorful icon-colorful--green" style="font-size:1.5rem; margin-right:.75rem"><i class="fa-solid fa-microchip"></i></div> <h4>Tech-Ready Outcomes</h4></div>
              <div class="ic-body">
                <p style="color:var(--gray); font-size:0.95rem;">Lithuanian degrees focus on <strong>real-world employability</strong> in Robotics, Fintech, and Biotech sectors.</p>
              </div>
            </div>
          </div>
        </div>
      </section>

      <!-- MODULE: FEES & FINANCES -->
      <section class="section" style="background:#f8fafc; border-top:1px solid #e2e8f0;">
        <div class="container">
          <div class="grid grid--2 gap--4">
            <div class="animate-on-scroll">
              <h3 style="font-size:1.5rem; color:#0f172a; margin-bottom:1.5rem; font-weight:800;"><i class="fa-solid fa-euro-sign" style="color:var(--neon-blue); margin-right:0.5rem;"></i> Estimated Annual Costs</h3>
              <div class="stayback-container">
                <table class="stayback-table">
                    <thead>
                        <tr>
                            <th>Category</th>
                            <th>Average Annual Cost</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr><td style="font-weight:700;">Bachelor’s Programs</td><td>€2,500 – €5,000</td></tr>
                        <tr><td style="font-weight:700;">Master’s Programs</td><td>€3,000 – €6,500</td></tr>
                        <tr><td style="font-weight:700;">Accommodation</td><td>€150 – €400/month</td></tr>
                        <tr><td style="font-weight:700;">Food & Utilities</td><td>€200 – €350/month</td></tr>
                    </tbody>
                </table>
              </div>
            </div>
            <div class="animate-on-scroll delay-1">
              <h3 style="font-size:1.5rem; color:#0f172a; margin-bottom:1.5rem; font-weight:800;"><i class="fa-solid fa-circle-info" style="color:var(--neon-orange); margin-right:0.5rem;"></i> 2026 MIGRIS Proof of Funds</h3>
              <div class="highlight-box bg-dots" style="background: white; border: 1px solid #e2e8f0; border-radius: 20px; padding: 2rem;">
                <ul class="benefit-list">
                  <li><i class="fa-solid fa-check-circle text-success"></i> Monthly Funds: €577 (~₹52k)</li>
                  <li><i class="fa-solid fa-check-circle text-success"></i> Annual Maintenance: €8,077 (~₹7.2L)</li>
                  <li><i class="fa-solid fa-check-circle text-success"></i> TRP Application Fee: €160 (~₹14.5k)</li>
                  <li><i class="fa-solid fa-award text-primary"></i> <strong>Pro Tip:</strong> Apply before March 1st for State Scholarships!</li>
                </ul>
              </div>
            </div>
          </div>
        </div>
      </section>

      <!-- MODULE: SUCCESS TIMELINE -->
      <section class="section">
        <div class="container">
          <div class="section__header animate-on-scroll">
            <span class="section__tag">Application Strategy</span>
            <h2 class="section__title">Success <span>Timeline</span></h2>
            <p style="color:var(--gray); margin-top: 0.5rem;">Lithuania uses the DreamApply system for centralized admissions. Timing is critical for scholarship eligibility.</p>
            <div class="accent-bar"></div>
          </div>
          <div class="grid grid--2 gap--4 align-center">
             <div class="animate-on-scroll">
               <ul class="benefit-list">
                 <li><i class="fa-solid fa-circle-check" style="color:var(--neon-blue);"></i> Autumn Intake: Apply by Feb – May 2026</li>
                 <li><i class="fa-solid fa-circle-check" style="color:var(--neon-blue);"></i> Spring Intake: Recommended by Nov 2026</li>
                 <li><i class="fa-solid fa-circle-check" style="color:var(--neon-blue);"></i> State Scholarship: Apply before March 1st</li>
                 <li><i class="fa-solid fa-circle-check" style="color:var(--neon-blue);"></i> MIGRIS Process: Digital TRP application buffer</li>
               </ul>
             </div>
             <div class="animate-on-scroll delay-1">
               <div class="info-card" style="background:var(--primary); color:white; border:none;">
                 <div class="ic-body">
                   <h4 style="color:white; margin-bottom:1rem;">State Scholarship Lead</h4>
                   <p style="opacity:0.9;"><strong>Tuition Support:</strong> Plus a monthly stipend of ~€400.</p>
                   <p style="opacity:0.9;"><strong>Centralized:</strong> Most unis use DreamApply for easy tracking.</p>
                 </div>
               </div>
             </div>
          </div>
        </div>
      </section>

      <!-- MODULE: CLIMATE & FOOD -->
      <section class="section" style="background:#f8fafc; border-top:1px solid #e2e8f0;">
        <div class="container">
          <div class="grid grid--2 gap--4">
            <div class="animate-on-scroll">
              <h3 style="font-size:1.5rem; color:#0f172a; margin-bottom:1.5rem; font-weight:800;"><i class="fa-solid fa-temperature-empty" style="color:var(--neon-blue); margin-right:0.5rem;"></i> Baltic Climate</h3>
              <ul class="benefit-list" style="margin-bottom: 2rem;">
                <li><i class="fa-solid fa-snowflake" style="color:var(--neon-blue);"></i> Cold snowy winters (-6°C to 3°C)</li>
                <li><i class="fa-solid fa-sun" style="color:var(--neon-orange);"></i> Mild and pleasant summers (18°C to 27°C)</li>
                <li><i class="fa-solid fa-leaf" style="color:var(--neon-green);"></i> Beautiful autumn and spring transitions</li>
                <li><i class="fa-solid fa-bicycle" style="color:var(--neon-blue);"></i> Student-friendly cities with efficient transit</li>
              </ul>
            </div>
            <div class="animate-on-scroll delay-1">
              <h3 style="font-size:1.5rem; color:#0f172a; margin-bottom:1.5rem; font-weight:800;"><i class="fa-solid fa-utensils" style="color:var(--neon-green); margin-right:0.5rem;"></i> Food & Dining</h3>
              <p style="color:var(--gray); margin-bottom:1.5rem; line-height:1.6;">Affordable, hearty, and tech-hub convenience:</p>
              <ul class="benefit-list">
                <li><i class="fa-solid fa-store" style="color:var(--neon-green);"></i> Indian grocery stores & restaurants in major cities</li>
                <li><i class="fa-solid fa-bowl-food" style="color:var(--neon-green);"></i> Authentic Cepelinai and Šaltibarščiai (Beetroot soup)</li>
                <li><i class="fa-solid fa-leaf" style="color:var(--neon-green);"></i> Abundant vegetarian and vegan options</li>
                <li><i class="fa-solid fa-cart-shopping" style="color:var(--neon-green);"></i> Significant student discounts on transit and dining</li>
              </ul>
            </div>
          </div>
        </div>
      </section>

      <!-- MODULE: ALUMNI SUCCESS -->
      <section class="section">
        <div class="container">
          <div class="section__header animate-on-scroll">
            <span class="section__tag">Global Network</span>
            <h2 class="section__title">Alumni <span>Success</span></h2>
            <p style="color:var(--gray); margin-top: 0.5rem;">Lithuanian graduates lead Europe in Fintech licensing and AI integration.</p>
            <div class="accent-bar"></div>
          </div>
          <div class="roi-grid" style="grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));">
            <div class="roi-card animate-on-scroll" style="text-align:center; padding:1.5rem;">
              <div class="icon-colorful icon-colorful--blue" style="margin:0 auto 1rem; width:48px; height:48px; font-size:1.25rem;"><i class="fa-solid fa-coins"></i></div>
              <h4 style="font-size:1.1rem; color:#0f172a; margin:0; font-weight:700;">Fintech</h4>
            </div>
            <div class="roi-card animate-on-scroll delay-1" style="text-align:center; padding:1.5rem;">
              <div class="icon-colorful icon-colorful--teal" style="margin:0 auto 1rem; width:48px; height:48px; font-size:1.25rem;"><i class="fa-solid fa-shield-virus"></i></div>
              <h4 style="font-size:1.1rem; color:#0f172a; margin:0; font-weight:700;">Cyber Security</h4>
            </div>
            <div class="roi-card animate-on-scroll delay-2" style="text-align:center; padding:1.5rem;">
              <div class="icon-colorful icon-colorful--green" style="margin:0 auto 1rem; width:48px; height:48px; font-size:1.25rem;"><i class="fa-solid fa-robot"></i></div>
              <h4 style="font-size:1.1rem; color:#0f172a; margin:0; font-weight:700;">Robotics</h4>
            </div>
            <div class="roi-card animate-on-scroll delay-3" style="text-align:center; padding:1.5rem;">
              <div class="icon-colorful icon-colorful--purple" style="margin:0 auto 1rem; width:48px; height:48px; font-size:1.25rem;"><i class="fa-solid fa-laptop-code"></i></div>
              <h4 style="font-size:1.1rem; color:#0f172a; margin:0; font-weight:700;">Software</h4>
            </div>
            <div class="roi-card animate-on-scroll delay-4" style="text-align:center; padding:1.5rem;">
              <div class="icon-colorful icon-colorful--orange" style="margin:0 auto 1rem; width:48px; height:48px; font-size:1.25rem;"><i class="fa-solid fa-vial"></i></div>
              <h4 style="font-size:1.1rem; color:#0f172a; margin:0; font-weight:700;">Biotech</h4>
            </div>
            <div class="roi-card animate-on-scroll delay-5" style="text-align:center; padding:1.5rem;">
              <div class="icon-colorful icon-colorful--orange" style="margin:0 auto 1rem; width:48px; height:48px; font-size:1.25rem; background:rgba(234,88,12,0.1); color:#ea580c;"><i class="fa-solid fa-compass-drafting"></i></div>
              <h4 style="font-size:1.1rem; color:#0f172a; margin:0; font-weight:700;">Design</h4>
            </div>
          </div>
        </div>
      </section>
      <?php elseif ($country_slug === 'cyprus'): ?>

      <!-- MODULE: THE 2026 STRATEGIC ADVANTAGE -->
      <section class="section" style="background:#f8fafc; border-top:1px solid #e2e8f0;">
        <div class="container">
          <div class="section__header animate-on-scroll">
            <span class="section__tag">Mediterranean Gateway</span>
            <h2 class="section__title">The 2026 <span>Strategic Advantage</span></h2>
            <p style="color:var(--gray); margin-top: 0.5rem;">"Cyprus has evolved into a modern Tech-Island." Connect with career-integrated programs in Fintech, Shipping, and AI in a safe, sun-drenched European environment.</p>
            <div class="accent-bar"></div>
          </div>
          <div class="grid grid--3 gap--4">
            <div class="pillar-card animate-on-scroll">
              <div class="pillar-num" style="background:rgba(139, 92, 246, 0.08); color:var(--neon-purple);"><i class="fa-solid fa-language"></i></div>
              <h4>No IELTS Required</h4>
              <p>Many Cypriot universities accept Medium of Instruction (MOI) certificates or conduct internal assessments, simplifying the admission journey for Indian students.</p>
            </div>
            <div class="pillar-card animate-on-scroll delay-1">
              <div class="pillar-num" style="background:rgba(16, 185, 129, 0.08); color:var(--neon-green);"><i class="fa-solid fa-anchor"></i></div>
              <h4>High-Tech Hub</h4>
              <p>Directly access industries in Maritime Shipping, Blockchain, and Hospitality. Cyprus is home to global blockchain leaders and innovation centers.</p>
            </div>
            <div class="pillar-card animate-on-scroll delay-2">
              <div class="pillar-num" style="background:rgba(245, 158, 11, 0.08); color:var(--neon-orange);"><i class="fa-solid fa-earth-europe"></i></div>
              <h4>EU Mobility Path</h4>
              <p>Cypriot degrees are recognized throughout the EU, creating professional pathways into Germany, Ireland, France, and the Netherlands.</p>
            </div>
          </div>
        </div>
      </section>

      <!-- MODULE: WORK & RESIDENCE BLUEPRINT -->
      <section class="section">
        <div class="container">
          <div class="section__header animate-on-scroll">
            <span class="section__tag">The 2026 Cyprus Work & Career Path</span>
            <h2 class="section__title">The “Work & <span>Residence” Blueprint</span></h2>
            <div class="accent-bar"></div>
          </div>
          <div class="grid grid--2 gap--4">
            <div class="info-card animate-on-scroll">
              <div class="ic-header"><div class="icon-colorful icon-colorful--blue" style="font-size:1.5rem; margin-right:.75rem"><i class="fa-solid fa-clock"></i></div> <h4>Work During Study</h4></div>
              <div class="ic-body">
                <ul class="benefit-list" style="margin-top: 1rem;">
                  <li><i class="fa-solid fa-check-circle text-success"></i> Work up to 20 hours/week during sessions</li>
                  <li><i class="fa-solid fa-check-circle text-success"></i> Up to 38 hours/week during official holidays</li>
                </ul>
              </div>
            </div>
            <div class="info-card animate-on-scroll delay-1">
              <div class="ic-header"><div class="icon-colorful icon-colorful--orange" style="font-size:1.5rem; margin-right:.75rem"><i class="fa-solid fa-user-check"></i></div> <h4>12-Month Stay-Back</h4></div>
              <div class="ic-body">
                <ul class="benefit-list" style="margin-top: 1rem;">
                  <li><i class="fa-solid fa-calendar-check text-success"></i> Post-study residence extension for job searching</li>
                  <li><i class="fa-solid fa-rocket text-success"></i> Entrepreneurial and startup launch opportunities</li>
                </ul>
              </div>
            </div>
            <div class="info-card animate-on-scroll delay-2">
              <div class="ic-header"><div class="icon-colorful icon-colorful--purple" style="font-size:1.5rem; margin-right:.75rem"><i class="fa-solid fa-handshake"></i></div> <h4>Employer Sponsorship</h4></div>
              <div class="ic-body">
                <ul class="benefit-list" style="margin-top: 1rem;">
                  <li><i class="fa-solid fa-id-card text-success"></i> Category E Work Permits renewed for up to 5 years</li>
                  <li><i class="fa-solid fa-suitcase-rolling text-success"></i> Fast-track hiring in Hospitality and IT sectors</li>
                </ul>
              </div>
            </div>
            <div class="info-card animate-on-scroll delay-3">
              <div class="ic-header"><div class="icon-colorful icon-colorful--green" style="font-size:1.5rem; margin-right:.75rem"><i class="fa-solid fa-shield-halved"></i></div> <h4>Safety & Lifestyle</h4></div>
              <div class="ic-body">
                <p style="color:var(--gray); font-size:0.95rem;">One of Europe’s <strong>safest environments</strong> with a warm Mediterranean climate and welcoming international communities.</p>
              </div>
            </div>
          </div>
        </div>
      </section>

      <!-- MODULE: FEES & FINANCES -->
      <section class="section" style="background:#f8fafc; border-top:1px solid #e2e8f0;">
        <div class="container">
          <div class="grid grid--2 gap--4">
            <div class="animate-on-scroll">
              <h3 style="font-size:1.5rem; color:#0f172a; margin-bottom:1.5rem; font-weight:800;"><i class="fa-solid fa-euro-sign" style="color:var(--neon-blue); margin-right:0.5rem;"></i> Estimated Annual Tuition</h3>
              <div class="stayback-container">
                <table class="stayback-table">
                    <thead>
                        <tr>
                            <th>Program Type</th>
                            <th>Average Annual Fee</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr><td style="font-weight:700;">Undergraduate Programs</td><td>€3,500 – €9,500</td></tr>
                        <tr><td style="font-weight:700;">Postgraduate Programs</td><td>€5,000 – €10,000</td></tr>
                        <tr><td style="font-weight:700;">Medical Programs</td><td>€12,000 – €18,000</td></tr>
                        <tr><td style="font-weight:700;">Living Expenses</td><td>€700 – €1,000/month</td></tr>
                    </tbody>
                </table>
              </div>
            </div>
            <div class="animate-on-scroll delay-1">
              <h3 style="font-size:1.5rem; color:#0f172a; margin-bottom:1.5rem; font-weight:800;"><i class="fa-solid fa-circle-info" style="color:var(--neon-orange); margin-right:0.5rem;"></i> 2026 Proof of Funds</h3>
              <div class="highlight-box bg-dots" style="background: white; border: 1px solid #e2e8f0; border-radius: 20px; padding: 2rem;">
                <ul class="benefit-list">
                  <li><i class="fa-solid fa-check-circle text-success"></i> Living Funds (Annual): €9k – €12k</li>
                  <li><i class="fa-solid fa-check-circle text-success"></i> Visa Application Fee: €60</li>
                  <li><i class="fa-solid fa-vial-virus text-danger"></i> <strong>Mandatory:</strong> HIV, Hepatitis & TB screening</li>
                  <li><i class="fa-solid fa-house-circle-check text-primary"></i> <strong>Pro Tip:</strong> Secure housing before visa approval!</li>
                </ul>
              </div>
            </div>
          </div>
        </div>
      </section>

      <!-- MODULE: SUCCESS TIMELINE -->
      <section class="section">
        <div class="container">
          <div class="section__header animate-on-scroll">
            <span class="section__tag">Application Strategy</span>
            <h2 class="section__title">Success <span>Timeline</span></h2>
            <p style="color:var(--gray); margin-top: 0.5rem;">Cyprus has a structured visa and medical screening process. Planning early ensures a smooth transition.</p>
            <div class="accent-bar"></div>
          </div>
          <div class="grid grid--2 gap--4 align-center">
             <div class="animate-on-scroll">
               <ul class="benefit-list">
                 <li><i class="fa-solid fa-circle-check" style="color:var(--neon-blue);"></i> Fall Intake: Apply by Feb – June 2026</li>
                 <li><i class="fa-solid fa-circle-check" style="color:var(--neon-blue);"></i> Spring Intake: Recommended by Nov 2026</li>
                 <li><i class="fa-solid fa-circle-check" style="color:var(--neon-blue);"></i> Visa Duration: Standard 5–8 week buffer</li>
                 <li><i class="fa-solid fa-circle-check" style="color:var(--neon-blue);"></i> Medical Coordination: Bluestone clinic guidance</li>
               </ul>
             </div>
             <div class="animate-on-scroll delay-1">
               <div class="info-card" style="background:var(--primary); color:white; border:none;">
                 <div class="ic-body">
                   <h4 style="color:white; margin-bottom:1rem;">Fast-Track Advantage</h4>
                   <p style="opacity:0.9;"><strong>MOI Support:</strong> Skip IELTS with internal assessments.</p>
                   <p style="opacity:0.9;"><strong>Integrated:</strong> Assistance with medicals & VFS appointments.</p>
                 </div>
               </div>
             </div>
          </div>
        </div>
      </section>

      <!-- MODULE: CLIMATE & FOOD -->
      <section class="section" style="background:#f8fafc; border-top:1px solid #e2e8f0;">
        <div class="container">
          <div class="grid grid--2 gap--4">
            <div class="animate-on-scroll">
              <h3 style="font-size:1.5rem; color:#0f172a; margin-bottom:1.5rem; font-weight:800;"><i class="fa-solid fa-sun" style="color:var(--neon-orange); margin-right:0.5rem;"></i> Mediterranean Lifestyle</h3>
              <ul class="benefit-list" style="margin-bottom: 2rem;">
                <li><i class="fa-solid fa-umbrella-beach" style="color:var(--neon-orange);"></i> Long sunny summers and warm mild winters</li>
                <li><i class="fa-solid fa-shield" style="color:var(--neon-blue);"></i> Top safety rankings and peaceful environments</li>
                <li><i class="fa-solid fa-city" style="color:var(--neon-blue);"></i> Vibrant hubs: Nicosia, Limassol, Larnaca, Paphos</li>
                <li><i class="fa-solid fa-ship" style="color:var(--neon-blue);"></i> Iconic island living with high-speed EU connectivity</li>
              </ul>
            </div>
            <div class="animate-on-scroll delay-1">
              <h3 style="font-size:1.5rem; color:#0f172a; margin-bottom:1.5rem; font-weight:800;"><i class="fa-solid fa-utensils" style="color:var(--neon-green); margin-right:0.5rem;"></i> Dining & Culture</h3>
              <p style="color:var(--gray); margin-bottom:1.5rem; line-height:1.6;">Hearty, healthy, and Indian-student friendly:</p>
              <ul class="benefit-list">
                <li><i class="fa-solid fa-store" style="color:var(--neon-green);"></i> Easy access to Indian restaurants and vegetarian options</li>
                <li><i class="fa-solid fa-cheese" style="color:var(--neon-green);"></i> Authentic Halloumi, Souvlaki, and Moussaka</li>
                <li><i class="fa-solid fa-leaf" style="color:var(--neon-green);"></i> Abundant fresh seafood and Mediterranean produce</li>
                <li><i class="fa-solid fa-cart-shopping" style="color:var(--neon-green);"></i> Affordable student cafeterias and supermarkets</li>
              </ul>
            </div>
          </div>
        </div>
      </section>

      <!-- MODULE: ALUMNI SUCCESS -->
      <section class="section">
        <div class="container">
          <div class="section__header animate-on-scroll">
            <span class="section__tag">Global Network</span>
            <h2 class="section__title">Alumni <span>Success</span></h2>
            <p style="color:var(--gray); margin-top: 0.5rem;">Cypriot graduates lead the Mediterranean in Blockchain and Maritime operations.</p>
            <div class="accent-bar"></div>
          </div>
          <div class="roi-grid" style="grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));">
            <div class="roi-card animate-on-scroll" style="text-align:center; padding:1.5rem;">
              <div class="icon-colorful icon-colorful--blue" style="margin:0 auto 1rem; width:48px; height:48px; font-size:1.25rem;"><i class="fa-solid fa-bitcoin"></i></div>
              <h4 style="font-size:1.1rem; color:#0f172a; margin:0; font-weight:700;">Blockchain</h4>
            </div>
            <div class="roi-card animate-on-scroll delay-1" style="text-align:center; padding:1.5rem;">
              <div class="icon-colorful icon-colorful--teal" style="margin:0 auto 1rem; width:48px; height:48px; font-size:1.25rem;"><i class="fa-solid fa-ship"></i></div>
              <h4 style="font-size:1.1rem; color:#0f172a; margin:0; font-weight:700;">Maritime</h4>
            </div>
            <div class="roi-card animate-on-scroll delay-2" style="text-align:center; padding:1.5rem;">
              <div class="icon-colorful icon-colorful--orange" style="margin:0 auto 1rem; width:48px; height:48px; font-size:1.25rem;"><i class="fa-solid fa-hotel"></i></div>
              <h4 style="font-size:1.1rem; color:#0f172a; margin:0; font-weight:700;">Hospitality</h4>
            </div>
            <div class="roi-card animate-on-scroll delay-3" style="text-align:center; padding:1.5rem;">
              <div class="icon-colorful icon-colorful--purple" style="margin:0 auto 1rem; width:48px; height:48px; font-size:1.25rem;"><i class="fa-solid fa-code"></i></div>
              <h4 style="font-size:1.1rem; color:#0f172a; margin:0; font-weight:700;">IT Services</h4>
            </div>
            <div class="roi-card animate-on-scroll delay-4" style="text-align:center; padding:1.5rem;">
              <div class="icon-colorful icon-colorful--green" style="margin:0 auto 1rem; width:48px; height:48px; font-size:1.25rem;"><i class="fa-solid fa-bolt"></i></div>
              <h4 style="font-size:1.1rem; color:#0f172a; margin:0; font-weight:700;">Renewables</h4>
            </div>
            <div class="roi-card animate-on-scroll delay-5" style="text-align:center; padding:1.5rem;">
              <div class="icon-colorful icon-colorful--blue" style="margin:0 auto 1rem; width:48px; height:48px; font-size:1.25rem; background:rgba(37,99,235,0.1); color:#2563eb;"><i class="fa-solid fa-microscope"></i></div>
              <h4 style="font-size:1.1rem; color:#0f172a; margin:0; font-weight:700;">Biosciences</h4>
            </div>
          </div>
        </div>
      </section>
      <?php elseif ($country_slug === 'poland'): ?>

      <!-- MODULE: THE 2026 STRATEGIC ADVANTAGE -->
      <section class="section" style="background:#f8fafc; border-top:1px solid #e2e8f0;">
        <div class="container">
          <div class="section__header animate-on-scroll">
            <span class="section__tag">The Nearshoring Hub</span>
            <h2 class="section__title">The 2026 <span>Strategic Advantage</span></h2>
            <p style="color:var(--gray); margin-top: 0.5rem;">"Poland has become Europe’s leading Nearshoring destination." Access the booming tech, finance, and logistics sectors of Warsaw, Kraków, and Wrocław.</p>
            <div class="accent-bar"></div>
          </div>
          <div class="grid grid--3 gap--4">
            <div class="pillar-card animate-on-scroll">
              <div class="pillar-num" style="background:rgba(139, 92, 246, 0.08); color:var(--neon-purple);"><i class="fa-solid fa-microchip"></i></div>
              <h4>STEM Powerhouse</h4>
              <p>Strong emphasis on Engineering, AI, and Robotics. Polish technical graduates are among the most highly-skilled and employable across the EU.</p>
            </div>
            <div class="pillar-card animate-on-scroll delay-1">
              <div class="pillar-num" style="background:rgba(16, 185, 129, 0.08); color:var(--neon-green);"><i class="fa-solid fa-briefcase-medical"></i></div>
              <h4>Industry-Link Programs</h4>
              <p>Direct career connections with global giants like Google, IBM, and Accenture, who use Poland as their primary European operational hub.</p>
            </div>
            <div class="pillar-card animate-on-scroll delay-2">
              <div class="pillar-num" style="background:rgba(245, 158, 11, 0.08); color:var(--neon-orange);"><i class="fa-solid fa-wallet"></i></div>
              <h4>High-Value Lifestyle</h4>
              <p>Enjoy a premium European lifestyle with living costs 30–40% lower than Western Europe, combined with rapidly expanding multinational hubs.</p>
            </div>
          </div>
        </div>
      </section>

      <!-- MODULE: DIGITAL RESIDENCE & WORK FRAMEWORK -->
      <section class="section">
        <div class="container">
          <div class="section__header animate-on-scroll">
            <span class="section__tag">The 2026 Poland Work & Residence Path</span>
            <h2 class="section__title">The “Digital Residence” & <span>Work Framework</span></h2>
            <div class="accent-bar"></div>
          </div>
          <div class="grid grid--2 gap--4">
            <div class="info-card animate-on-scroll">
              <div class="ic-header"><div class="icon-colorful icon-colorful--blue" style="font-size:1.5rem; margin-right:.75rem"><i class="fa-solid fa-display"></i></div> <h4>Digital Karta Pobytu</h4></div>
              <div class="ic-body">
                <ul class="benefit-list" style="margin-top: 1rem;">
                  <li><i class="fa-solid fa-check-circle text-success"></i> Fully digital residence applications from 2026</li>
                  <li><i class="fa-solid fa-bolt text-success"></i> Faster approvals and simplified tracking system</li>
                </ul>
              </div>
            </div>
            <div class="info-card animate-on-scroll delay-1">
              <div class="ic-header"><div class="icon-colorful icon-colorful--orange" style="font-size:1.5rem; margin-right:.75rem"><i class="fa-solid fa-clock"></i></div> <h4>Unlimited Work Rights</h4></div>
              <div class="ic-body">
                <ul class="benefit-list" style="margin-top: 1rem;">
                  <li><i class="fa-solid fa-calendar-check text-success"></i> Work unlimited hours if enrolled in full-time programs</li>
                  <li><i class="fa-solid fa-briefcase text-success"></i> No separate work permit required for students</li>
                </ul>
              </div>
            </div>
            <div class="info-card animate-on-scroll delay-2">
              <div class="ic-header"><div class="icon-colorful icon-colorful--purple" style="font-size:1.5rem; margin-right:.75rem"><i class="fa-solid fa-user-graduate"></i></div> <h4>1-Year Graduate Stay-back</h4></div>
              <div class="ic-body">
                <ul class="benefit-list" style="margin-top: 1rem;">
                  <li><i class="fa-solid fa-id-card text-success"></i> 12-month residence extension for job searching</li>
                  <li><i class="fa-solid fa-earth-europe text-success"></i> Pathway to the premium EU Blue Card residency</li>
                </ul>
              </div>
            </div>
            <div class="info-card animate-on-scroll delay-3">
              <div class="ic-header"><div class="icon-colorful icon-colorful--green" style="font-size:1.5rem; margin-right:.75rem"><i class="fa-solid fa-chart-line"></i></div> <h4>Career Outcomes</h4></div>
              <div class="ic-body">
                <p style="color:var(--gray); font-size:0.95rem;">Poland offers a clear roadmap from <strong>Education to EU-wide career growth</strong> in Central Europe's top tech markets.</p>
              </div>
            </div>
          </div>
        </div>
      </section>

      <!-- MODULE: FEES & FINANCES -->
      <section class="section" style="background:#f8fafc; border-top:1px solid #e2e8f0;">
        <div class="container">
          <div class="grid grid--2 gap--4">
            <div class="animate-on-scroll">
              <h3 style="font-size:1.5rem; color:#0f172a; margin-bottom:1.5rem; font-weight:800;"><i class="fa-solid fa-coins" style="color:var(--neon-blue); margin-right:0.5rem;"></i> Official 2026 Benchmarks</h3>
              <div class="stayback-container">
                <table class="stayback-table">
                    <thead>
                        <tr>
                            <th>Category</th>
                            <th>2026 Requirement (PLN)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr><td style="font-weight:700;">Living Funds (Monthly)</td><td>PLN 1,373 (~₹29k)</td></tr>
                        <tr><td style="font-weight:700;">Annual Maintenance</td><td>PLN 13,730 (~₹2.9L)</td></tr>
                        <tr><td style="font-weight:700;">Tuition (English)</td><td>PLN 10k – 18k /year</td></tr>
                        <tr><td style="font-weight:700;">Karta Pobytu Fee</td><td>PLN 440 (Digital)</td></tr>
                    </tbody>
                </table>
              </div>
            </div>
            <div class="animate-on-scroll delay-1">
              <h3 style="font-size:1.5rem; color:#0f172a; margin-bottom:1.5rem; font-weight:800;"><i class="fa-solid fa-circle-info" style="color:var(--neon-orange); margin-right:0.5rem;"></i> Expert Note</h3>
              <div class="highlight-box bg-dots" style="background: white; border: 1px solid #e2e8f0; border-radius: 20px; padding: 2rem;">
                <ul class="benefit-list">
                  <li><i class="fa-solid fa-check-circle text-success"></i> National Visa (Type D): €200</li>
                  <li><i class="fa-solid fa-check-circle text-success"></i> Monthly Budget: PLN 2,500 – 4,000</li>
                  <li><i class="fa-solid fa-award text-primary"></i> <strong>Pro Tip:</strong> Indian documents must be <strong>Apostilled</strong> by the MEA!</li>
                  <li><i class="fa-solid fa-graduation-cap text-primary"></i> NAWA and Erasmus+ Scholarships available.</li>
                </ul>
              </div>
            </div>
          </div>
        </div>
      </section>

      <!-- MODULE: SUCCESS TIMELINE -->
      <section class="section">
        <div class="container">
          <div class="section__header animate-on-scroll">
            <span class="section__tag">Application Strategy</span>
            <h2 class="section__title">Success <span>Timeline</span></h2>
            <div class="accent-bar"></div>
          </div>
          <div class="grid grid--2 gap--4 align-center">
             <div class="animate-on-scroll">
               <ul class="benefit-list">
                 <li><i class="fa-solid fa-circle-check" style="color:var(--neon-blue);"></i> Winter Intake: Apply Jan – June 2026</li>
                 <li><i class="fa-solid fa-circle-check" style="color:var(--neon-blue);"></i> Summer Intake: Recommended by Oct 2026</li>
                 <li><i class="fa-solid fa-circle-check" style="color:var(--neon-blue);"></i> Visa Buffer: 15–30 working days standard</li>
                 <li><i class="fa-solid fa-circle-check" style="color:var(--neon-blue);"></i> VFS Booking: At least 8 weeks prior to travel</li>
               </ul>
             </div>
             <div class="animate-on-scroll delay-1">
               <div class="info-card" style="background:var(--primary); color:white; border:none;">
                 <div class="ic-body">
                   <h4 style="color:white; margin-bottom:1rem;">End-to-End Support</h4>
                   <p style="opacity:0.9;"><strong>Apostille:</strong> Bluestone simplifies document legalization.</p>
                   <p style="opacity:0.9;"><strong>Digital:</strong> Expert guidance on the new Karta Pobytu system.</p>
                 </div>
               </div>
             </div>
          </div>
        </div>
      </section>

      <!-- MODULE: ALUMNI SUCCESS -->
      <section class="section" style="background:#f8fafc; border-top:1px solid #e2e8f0;">
        <div class="container">
          <div class="section__header animate-on-scroll">
            <span class="section__tag">Global Network</span>
            <h2 class="section__title">Alumni <span>Success</span></h2>
            <p style="color:var(--gray); margin-top: 0.5rem;">Graduates in Poland are hired by global leaders like Google, Motorola, and Amazon.</p>
            <div class="accent-bar"></div>
          </div>
          <div class="roi-grid" style="grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));">
            <div class="roi-card animate-on-scroll" style="text-align:center; padding:1.5rem;">
              <div class="icon-colorful icon-colorful--blue" style="margin:0 auto 1rem; width:48px; height:48px; font-size:1.25rem;"><i class="fa-solid fa-laptop-code"></i></div>
              <h4 style="font-size:1.1rem; color:#0f172a; margin:0; font-weight:700;">IT & Software</h4>
            </div>
            <div class="roi-card animate-on-scroll delay-1" style="text-align:center; padding:1.5rem;">
              <div class="icon-colorful icon-colorful--teal" style="margin:0 auto 1rem; width:48px; height:48px; font-size:1.25rem;"><i class="fa-solid fa-car"></i></div>
              <h4 style="font-size:1.1rem; color:#0f172a; margin:0; font-weight:700;">Automotive</h4>
            </div>
            <div class="roi-card animate-on-scroll delay-2" style="text-align:center; padding:1.5rem;">
              <div class="icon-colorful icon-colorful--orange" style="margin:0 auto 1rem; width:48px; height:48px; font-size:1.25rem;"><i class="fa-solid fa-truck-fast"></i></div>
              <h4 style="font-size:1.1rem; color:#0f172a; margin:0; font-weight:700;">Logistics</h4>
            </div>
            <div class="roi-card animate-on-scroll delay-3" style="text-align:center; padding:1.5rem;">
              <div class="icon-colorful icon-colorful--purple" style="margin:0 auto 1rem; width:48px; height:48px; font-size:1.25rem;"><i class="fa-solid fa-building-columns"></i></div>
              <h4 style="font-size:1.1rem; color:#0f172a; margin:0; font-weight:700;">Banking</h4>
            </div>
            <div class="roi-card animate-on-scroll delay-4" style="text-align:center; padding:1.5rem;">
              <div class="icon-colorful icon-colorful--green" style="margin:0 auto 1rem; width:48px; height:48px; font-size:1.25rem;"><i class="fa-solid fa-industry"></i></div>
              <h4 style="font-size:1.1rem; color:#0f172a; margin:0; font-weight:700;">Manufacturing</h4>
            </div>
          </div>
        </div>
      </section>

      <?php elseif ($country_slug === 'czech-republic'): ?>

      <!-- MODULE: THE 2026 STRATEGIC ADVANTAGE -->
      <section class="section" style="background:#f8fafc; border-top:1px solid #e2e8f0;">
        <div class="container">
          <div class="section__header animate-on-scroll">
            <span class="section__tag">Historic Excellence</span>
            <h2 class="section__title">The 2026 <span>Strategic Advantage</span></h2>
            <p style="color:var(--gray); margin-top: 0.5rem;">"The Czech Republic is a hidden gem for high-quality education." Connect with historic prestige and the future of European Robotics and AI.</p>
            <div class="accent-bar"></div>
          </div>
          <div class="grid grid--3 gap--4">
            <div class="pillar-card animate-on-scroll">
              <div class="pillar-num" style="background:rgba(139, 92, 246, 0.08); color:var(--neon-purple);"><i class="fa-solid fa-graduation-cap"></i></div>
              <h4>Free-Tuition Path</h4>
              <p>Achieve B2 Czech Language Proficiency and study at public universities with €0 tuition fees in Czech-medium programs. A massive ROI win.</p>
            </div>
            <div class="pillar-card animate-on-scroll delay-1">
              <div class="pillar-num" style="background:rgba(16, 185, 129, 0.08); color:var(--neon-green);"><i class="fa-solid fa-vial"></i></div>
              <h4>Nostrification Mastery</h4>
              <p>Bluestone provides expert assistance in officially validating your academic qualifications (Nostrification) for seamless Czech university admission.</p>
            </div>
            <div class="pillar-card animate-on-scroll delay-2">
              <div class="pillar-num" style="background:rgba(245, 158, 11, 0.08); color:var(--neon-orange);"><i class="fa-solid fa-robot"></i></div>
              <h4>Tech & Innovation</h4>
              <p>Europe’s emerging hub for Robotics, Cyber Security, and Automotive tech. Home to Skoda Auto, Honeywell, and major R&D centers.</p>
            </div>
          </div>
        </div>
      </section>

      <!-- MODULE: WORK & RESIDENCE BLUEPRINT -->
      <section class="section">
        <div class="container">
          <div class="section__header animate-on-scroll">
            <span class="section__tag">The 2026 Czech Work & Career Path</span>
            <h2 class="section__title">The “Work & <span>Residence” Blueprint</span></h2>
            <div class="accent-bar"></div>
          </div>
          <div class="grid grid--2 gap--4">
            <div class="info-card animate-on-scroll">
              <div class="ic-header"><div class="icon-colorful icon-colorful--blue" style="font-size:1.5rem; margin-right:.75rem"><i class="fa-solid fa-briefcase"></i></div> <h4>Student Work Rights</h4></div>
              <div class="ic-body">
                <ul class="benefit-list" style="margin-top: 1rem;">
                  <li><i class="fa-solid fa-check-circle text-success"></i> Work up to 20 hours/week from the first year</li>
                  <li><i class="fa-solid fa-coins text-success"></i> Hourly Wage: CZK 134.40 (~₹620/hour)</li>
                </ul>
              </div>
            </div>
            <div class="info-card animate-on-scroll delay-1">
              <div class="ic-header"><div class="icon-colorful icon-colorful--orange" style="font-size:1.5rem; margin-right:.75rem"><i class="fa-solid fa-id-card-clip"></i></div> <h4>Graduate Extension</h4></div>
              <div class="ic-body">
                <ul class="benefit-list" style="margin-top: 1rem;">
                  <li><i class="fa-solid fa-calendar-check text-success"></i> Automatic extension pathway for job searching</li>
                  <li><i class="fa-solid fa-bolt text-success"></i> No separate labor-market test for local graduates</li>
                </ul>
              </div>
            </div>
            <div class="info-card animate-on-scroll delay-2">
              <div class="ic-header"><div class="icon-colorful icon-colorful--purple" style="font-size:1.5rem; margin-right:.75rem"><i class="fa-solid fa-address-card"></i></div> <h4>Employee Card System</h4></div>
              <div class="ic-body">
                <ul class="benefit-list" style="margin-top: 1rem;">
                  <li><i class="fa-solid fa-check-double text-success"></i> Unified work and residence permit after job offer</li>
                  <li><i class="fa-solid fa-earth-europe text-success"></i> Full Schengen mobility with Temporary Residence Card</li>
                </ul>
              </div>
            </div>
            <div class="info-card animate-on-scroll delay-3">
              <div class="ic-header"><div class="icon-colorful icon-colorful--green" style="font-size:1.5rem; margin-right:.75rem"><i class="fa-solid fa-sack-dollar"></i></div> <h4>Earnings Power</h4></div>
              <div class="ic-body">
                <p style="color:var(--gray); font-size:0.95rem;">New 2026 <strong>Graduate Min Wage</strong>: CZK 22,400/month (~₹1.03 Lakhs) for professionals.</p>
              </div>
            </div>
          </div>
        </div>
      </section>

      <!-- MODULE: FEES & FINANCES -->
      <section class="section" style="background:#f8fafc; border-top:1px solid #e2e8f0;">
        <div class="container">
          <div class="grid grid--2 gap--4">
            <div class="animate-on-scroll">
              <h3 style="font-size:1.5rem; color:#0f172a; margin-bottom:1.5rem; font-weight:800;"><i class="fa-solid fa-wallet" style="color:var(--neon-blue); margin-right:0.5rem;"></i> Official 2026 Benchmarks</h3>
              <div class="stayback-container">
                <table class="stayback-table">
                    <thead>
                        <tr>
                            <th>Category</th>
                            <th>2026 Requirement (CZK)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr><td style="font-weight:700;">Living Funds (Annual)</td><td>CZK 115,810 (~₹5.3L)</td></tr>
                        <tr><td style="font-weight:700;">Monthly Living Cost</td><td>CZK 12k – 20k /month</td></tr>
                        <tr><td style="font-weight:700;">Tuition (English)</td><td>€3,000 – €10,000 /yr</td></tr>
                        <tr><td style="font-weight:700;">Residence Fee</td><td>CZK 2,500 (~₹11.5k)</td></tr>
                    </tbody>
                </table>
              </div>
            </div>
            <div class="animate-on-scroll delay-1">
              <h3 style="font-size:1.5rem; color:#0f172a; margin-bottom:1.5rem; font-weight:800;"><i class="fa-solid fa-circle-info" style="color:var(--neon-orange); margin-right:0.5rem;"></i> Expert Note</h3>
              <div class="highlight-box bg-dots" style="background: white; border: 1px solid #e2e8f0; border-radius: 20px; padding: 2rem;">
                <ul class="benefit-list">
                  <li><i class="fa-solid fa-check-circle text-success"></i> Health Insurance: €400 – €800 /yr</li>
                  <li><i class="fa-solid fa-check-circle text-success"></i> CZ-Medium Programs: €0 Tuition</li>
                  <li><i class="fa-solid fa-award text-primary"></i> <strong>Pro Tip:</strong> Most technical unis require online math tests or Technical Interviews!</li>
                  <li><i class="fa-solid fa-shield-halved text-primary"></i> Safe student environment with top-tier public transport.</li>
                </ul>
              </div>
            </div>
          </div>
        </div>
      </section>

      <!-- MODULE: SUCCESS TIMELINE -->
      <section class="section">
        <div class="container">
          <div class="section__header animate-on-scroll">
            <span class="section__tag">Application Strategy</span>
            <h2 class="section__title">Success <span>Timeline</span></h2>
            <div class="accent-bar"></div>
          </div>
          <div class="grid grid--2 gap--4 align-center">
             <div class="animate-on-scroll">
               <ul class="benefit-list">
                 <li><i class="fa-solid fa-circle-check" style="color:var(--neon-blue);"></i> Winter Intake: Apply Feb – April 2026</li>
                 <li><i class="fa-solid fa-circle-check" style="color:var(--neon-blue);"></i> Spring Intake: Recommended by Sept 2026</li>
                 <li><i class="fa-solid fa-circle-check" style="color:var(--neon-blue);"></i> Visa Duration: 60+ days buffer required</li>
                 <li><i class="fa-solid fa-circle-check" style="color:var(--neon-blue);"></i> Preparation: Online technical interview support</li>
               </ul>
             </div>
             <div class="animate-on-scroll delay-1">
               <div class="info-card" style="background:var(--primary); color:white; border:none;">
                 <div class="ic-body">
                   <h4 style="color:white; margin-bottom:1rem;">Nostrification Support</h4>
                   <p style="opacity:0.9;"><strong>Seamless:</strong> We handle your academic validation procedures.</p>
                   <p style="opacity:0.9;"><strong>Mock Exams:</strong> Interview and entrance test prep included.</p>
                 </div>
               </div>
             </div>
          </div>
        </div>
      </section>

      <!-- MODULE: ALUMNI SUCCESS -->
      <section class="section" style="background:#f8fafc; border-top:1px solid #e2e8f0;">
        <div class="container">
          <div class="section__header animate-on-scroll">
            <span class="section__tag">Global Network</span>
            <h2 class="section__title">Alumni <span>Success</span></h2>
            <p style="color:var(--gray); margin-top: 0.5rem;">Czech graduates excel in Automotive, Robotics, and Software R&D across Europe.</p>
            <div class="accent-bar"></div>
          </div>
          <div class="roi-grid" style="grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));">
            <div class="roi-card animate-on-scroll" style="text-align:center; padding:1.5rem;">
              <div class="icon-colorful icon-colorful--teal" style="margin:0 auto 1rem; width:48px; height:48px; font-size:1.25rem;"><i class="fa-solid fa-car-side"></i></div>
              <h4 style="font-size:1.1rem; color:#0f172a; margin:0; font-weight:700;">Automotive</h4>
            </div>
            <div class="roi-card animate-on-scroll delay-1" style="text-align:center; padding:1.5rem;">
              <div class="icon-colorful icon-colorful--blue" style="margin:0 auto 1rem; width:48px; height:48px; font-size:1.25rem;"><i class="fa-solid fa-robot"></i></div>
              <h4 style="font-size:1.1rem; color:#0f172a; margin:0; font-weight:700;">Robotics</h4>
            </div>
            <div class="roi-card animate-on-scroll delay-2" style="text-align:center; padding:1.5rem;">
              <div class="icon-colorful icon-colorful--green" style="margin:0 auto 1rem; width:48px; height:48px; font-size:1.25rem;"><i class="fa-solid fa-code"></i></div>
              <h4 style="font-size:1.1rem; color:#0f172a; margin:0; font-weight:700;">Software R&D</h4>
            </div>
            <div class="roi-card animate-on-scroll delay-3" style="text-align:center; padding:1.5rem;">
              <div class="icon-colorful icon-colorful--purple" style="margin:0 auto 1rem; width:48px; height:48px; font-size:1.25rem;"><i class="fa-solid fa-shield-halved"></i></div>
              <h4 style="font-size:1.1rem; color:#0f172a; margin:0; font-weight:700;">Cyber Security</h4>
            </div>
            <div class="roi-card animate-on-scroll delay-4" style="text-align:center; padding:1.5rem;">
              <div class="icon-colorful icon-colorful--orange" style="margin:0 auto 1rem; width:48px; height:48px; font-size:1.25rem;"><i class="fa-solid fa-flask"></i></div>
              <h4 style="font-size:1.1rem; color:#0f172a; margin:0; font-weight:700;">Biotech</h4>
            </div>
          </div>
        </div>
      </section>
      <?php elseif ($country_slug === 'malaysia'): ?>

      <!-- MODULE: THE 2026 STRATEGIC ADVANTAGE -->
      <section class="section" style="background:#f8fafc; border-top:1px solid #e2e8f0;">
        <div class="container">
          <div class="section__header animate-on-scroll">
            <span class="section__tag">The Global Degree Pathway</span>
            <h2 class="section__title">The 2026 <span>Strategic Advantage</span></h2>
            <p style="color:var(--gray); margin-top: 0.5rem;">"Malaysia offers the perfect balance between affordability and international recognition." Earn world-class UK or Australian degrees at a fraction of the cost.</p>
            <div class="accent-bar"></div>
          </div>
          <div class="grid grid--3 gap--4">
            <div class="pillar-card animate-on-scroll">
              <div class="pillar-num" style="background:rgba(139, 92, 246, 0.08); color:var(--neon-purple);"><i class="fa-solid fa-graduation-cap"></i></div>
              <h4>Elite Branch Campuses</h4>
              <p>Home to Monash, Nottingham, Southampton, and Heriot-Watt. Earn the exact same degree as the home campus with identical academic standards.</p>
            </div>
            <div class="pillar-card animate-on-scroll delay-1">
              <div class="pillar-num" style="background:rgba(16, 185, 129, 0.08); color:var(--neon-green);"><i class="fa-solid fa-earth-asia"></i></div>
              <h4>ASEAN Career Gateway</h4>
              <p>Strategic access to Southeast Asia’s booming economies. Ideal for careers in Semiconductor Manufacturing, AI, and Digital Banking.</p>
            </div>
            <div class="pillar-card animate-on-scroll delay-2">
              <div class="pillar-num" style="background:rgba(245, 158, 11, 0.08); color:var(--neon-orange);"><i class="fa-solid fa-handshake-angle"></i></div>
              <h4>Industry-Integrated</h4>
              <p>Programs feature mandatory paid internships and industrial placements, connecting you directly with Malaysia’s high-tech manufacturing sector.</p>
            </div>
          </div>
        </div>
      </section>

      <!-- MODULE: GRADUATE PASS & WORK FRAMEWORK -->
      <section class="section">
        <div class="container">
          <div class="section__header animate-on-scroll">
            <span class="section__tag">The 2026 Malaysia Work & Residence Blueprint</span>
            <h2 class="section__title">The “Graduate Pass” & <span>Work Framework</span></h2>
            <div class="accent-bar"></div>
          </div>
          <div class="grid grid--2 gap--4">
            <div class="info-card animate-on-scroll">
              <div class="ic-header"><div class="icon-colorful icon-colorful--blue" style="font-size:1.5rem; margin-right:.75rem"><i class="fa-solid fa-id-card"></i></div> <h4>12-Month Graduate Pass</h4></div>
              <div class="ic-body">
                <ul class="benefit-list" style="margin-top: 1rem;">
                  <li><i class="fa-solid fa-check-circle text-success"></i> New post-study stay-back for eligible graduates</li>
                  <li><i class="fa-solid fa-magnifying-glass text-success"></i> Freedom to search for jobs and intern locally</li>
                </ul>
              </div>
            </div>
            <div class="info-card animate-on-scroll delay-1">
              <div class="ic-header"><div class="icon-colorful icon-colorful--orange" style="font-size:1.5rem; margin-right:.75rem"><i class="fa-solid fa-briefcase"></i></div> <h4>Part-Time Work Rights</h4></div>
              <div class="ic-body">
                <ul class="benefit-list" style="margin-top: 1rem;">
                  <li><i class="fa-solid fa-clock text-success"></i> Work up to 20 hours/week during semester breaks</li>
                  <li><i class="fa-solid fa-calendar-day text-success"></i> Full rights during public and university holidays</li>
                </ul>
              </div>
            </div>
            <div class="info-card animate-on-scroll delay-2">
              <div class="ic-header"><div class="icon-colorful icon-colorful--purple" style="font-size:1.5rem; margin-right:.75rem"><i class="fa-solid fa-user-check"></i></div> <h4>Employment Pass (EP)</h4></div>
              <div class="ic-body">
                <ul class="benefit-list" style="margin-top: 1rem;">
                  <li><i class="fa-solid fa-shield-halved text-success"></i> Employer-sponsored work authorization after job offer</li>
                  <li><i class="fa-solid fa-arrow-up-right-dots text-success"></i> Potential pathway toward long-term residence</li>
                </ul>
              </div>
            </div>
            <div class="info-card animate-on-scroll delay-3">
              <div class="ic-header"><div class="icon-colorful icon-colorful--green" style="font-size:1.5rem; margin-right:.75rem"><i class="fa-solid fa-chart-line"></i></div> <h4>High-Demand Sectors</h4></div>
              <div class="ic-body">
                <p style="color:var(--gray); font-size:0.95rem;">Rapid growth in <strong>Fintech, Cybersecurity, and Data Science</strong> creates massive demand for skilled international talent.</p>
              </div>
            </div>
          </div>
        </div>
      </section>

      <!-- MODULE: FEES & FINANCES -->
      <section class="section" style="background:#f8fafc; border-top:1px solid #e2e8f0;">
        <div class="container">
          <div class="grid grid--2 gap--4">
            <div class="animate-on-scroll">
              <h3 style="font-size:1.5rem; color:#0f172a; margin-bottom:1.5rem; font-weight:800;"><i class="fa-solid fa-tags" style="color:var(--neon-blue); margin-right:0.5rem;"></i> Estimated Annual Fees (RM)</h3>
              <div class="stayback-container">
                <table class="stayback-table">
                    <thead>
                        <tr>
                            <th>Program Type</th>
                            <th>Average Annual Tuition</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr><td style="font-weight:700;">Foundation Programs</td><td>RM 12,000 – 35,000</td></tr>
                        <tr><td style="font-weight:700;">Bachelor’s Programs</td><td>RM 25,000 – 55,000</td></tr>
                        <tr><td style="font-weight:700;">Master’s Programs</td><td>RM 30,000 – 75,000</td></tr>
                        <tr><td style="font-weight:700;">Living Expenses</td><td>RM 18k – 30k /year</td></tr>
                    </tbody>
                </table>
              </div>
            </div>
            <div class="animate-on-scroll delay-1">
              <h3 style="font-size:1.5rem; color:#0f172a; margin-bottom:1.5rem; font-weight:800;"><i class="fa-solid fa-circle-info" style="color:var(--neon-orange); margin-right:0.5rem;"></i> 2026 Benchmarks</h3>
              <div class="highlight-box bg-dots" style="background: white; border: 1px solid #e2e8f0; border-radius: 20px; padding: 2rem;">
                <ul class="benefit-list">
                  <li><i class="fa-solid fa-check-circle text-success"></i> Living Cost: RM 1,500 – 2,500 /month</li>
                  <li><i class="fa-solid fa-check-circle text-success"></i> Visa Process: University-led via EMGS</li>
                  <li><i class="fa-solid fa-shield-virus text-danger"></i> <strong>Mandatory:</strong> Medical Insurance under EMGS</li>
                  <li><i class="fa-solid fa-award text-primary"></i> <strong>ROI Tip:</strong> UK/Australian degrees at 40-60% lower cost!</li>
                </ul>
              </div>
            </div>
          </div>
        </div>
      </section>

      <!-- MODULE: SUCCESS TIMELINE -->
      <section class="section">
        <div class="container">
          <div class="section__header animate-on-scroll">
            <span class="section__tag">Application Strategy</span>
            <h2 class="section__title">Success <span>Timeline</span></h2>
            <div class="accent-bar"></div>
          </div>
          <div class="grid grid--2 gap--4 align-center">
             <div class="animate-on-scroll">
               <ul class="benefit-list">
                 <li><i class="fa-solid fa-circle-check" style="color:var(--neon-blue);"></i> Main Intake: Apply before June 2026</li>
                 <li><i class="fa-solid fa-circle-check" style="color:var(--neon-blue);"></i> Secondary Intake: Recommended by Nov 2026</li>
                 <li><i class="fa-solid fa-circle-check" style="color:var(--neon-blue);"></i> Visa Buffer: 10–14 working days (after approval)</li>
                 <li><i class="fa-solid fa-circle-check" style="color:var(--neon-blue);"></i> EMGS Portal: Centralized student pass tracking</li>
               </ul>
             </div>
             <div class="animate-on-scroll delay-1">
               <div class="info-card" style="background:var(--primary); color:white; border:none;">
                 <div class="ic-body">
                   <h4 style="color:white; margin-bottom:1rem;">Fast-Track Advantage</h4>
                   <p style="opacity:0.9;"><strong>Seamless:</strong> Bluestone handles the university-led visa filings.</p>
                   <p style="opacity:0.9;"><strong>Flexible:</strong> Private unis offer rolling June/July intakes.</p>
                 </div>
               </div>
             </div>
          </div>
        </div>
      </section>

      <!-- MODULE: CLIMATE & FOOD -->
      <section class="section" style="background:#f8fafc; border-top:1px solid #e2e8f0;">
        <div class="container">
          <div class="grid grid--2 gap--4">
            <div class="animate-on-scroll">
              <h3 style="font-size:1.5rem; color:#0f172a; margin-bottom:1.5rem; font-weight:800;"><i class="fa-solid fa-sun" style="color:var(--neon-orange); margin-right:0.5rem;"></i> Tropical Lifestyle</h3>
              <ul class="benefit-list" style="margin-bottom: 2rem;">
                <li><i class="fa-solid fa-umbrella-beach" style="color:var(--neon-orange);"></i> Year-round tropical weather (24°C – 33°C)</li>
                <li><i class="fa-solid fa-tree" style="color:var(--neon-green);"></i> Green, modern urban environments with safe cities</li>
                <li><i class="fa-solid fa-city" style="color:var(--neon-blue);"></i> Vibrant hubs: KL, Penang, Johor Bahru, Cyberjaya</li>
                <li><i class="fa-solid fa-train-subway" style="color:var(--neon-blue);"></i> World-class public transport and infrastructure</li>
              </ul>
            </div>
            <div class="animate-on-scroll delay-1">
              <h3 style="font-size:1.5rem; color:#0f172a; margin-bottom:1.5rem; font-weight:800;"><i class="fa-solid fa-utensils" style="color:var(--neon-green); margin-right:0.5rem;"></i> Multicultural Dining</h3>
              <p style="color:var(--gray); margin-bottom:1.5rem; line-height:1.6;">A haven for Indian students with global standards:</p>
              <ul class="benefit-list">
                <li><i class="fa-solid fa-store" style="color:var(--neon-green);"></i> South Indian & Mamak restaurants widely available</li>
                <li><i class="fa-solid fa-bowl-food" style="color:var(--neon-green);"></i> Iconic Nasi Lemak, Roti Canai, and Satay</li>
                <li><i class="fa-solid fa-leaf" style="color:var(--neon-green);"></i> Abundant vegetarian and halal options everywhere</li>
                <li><i class="fa-solid fa-cart-shopping" style="color:var(--neon-green);"></i> Budget-friendly street food and student cafeterias</li>
              </ul>
            </div>
          </div>
        </div>
      </section>

      <!-- MODULE: ALUMNI SUCCESS -->
      <section class="section">
        <div class="container">
          <div class="section__header animate-on-scroll">
            <span class="section__tag">Global Network</span>
            <h2 class="section__title">Alumni <span>Success</span></h2>
            <p style="color:var(--gray); margin-top: 0.5rem;">Graduates from Malaysia lead high-tech manufacturing and digital banking across ASEAN.</p>
            <div class="accent-bar"></div>
          </div>
          <div class="roi-grid" style="grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));">
            <div class="roi-card animate-on-scroll" style="text-align:center; padding:1.5rem;">
              <div class="icon-colorful icon-colorful--teal" style="margin:0 auto 1rem; width:48px; height:48px; font-size:1.25rem;"><i class="fa-solid fa-microchip"></i></div>
              <h4 style="font-size:1.1rem; color:#0f172a; margin:0; font-weight:700;">Semiconductors</h4>
            </div>
            <div class="roi-card animate-on-scroll delay-1" style="text-align:center; padding:1.5rem;">
              <div class="icon-colorful icon-colorful--blue" style="margin:0 auto 1rem; width:48px; height:48px; font-size:1.25rem;"><i class="fa-solid fa-building-columns"></i></div>
              <h4 style="font-size:1.1rem; color:#0f172a; margin:0; font-weight:700;">Fintech</h4>
            </div>
            <div class="roi-card animate-on-scroll delay-2" style="text-align:center; padding:1.5rem;">
              <div class="icon-colorful icon-colorful--orange" style="margin:0 auto 1rem; width:48px; height:48px; font-size:1.25rem;"><i class="fa-solid fa-hotel"></i></div>
              <h4 style="font-size:1.1rem; color:#0f172a; margin:0; font-weight:700;">Hospitality</h4>
            </div>
            <div class="roi-card animate-on-scroll delay-3" style="text-align:center; padding:1.5rem;">
              <div class="icon-colorful icon-colorful--purple" style="margin:0 auto 1rem; width:48px; height:48px; font-size:1.25rem;"><i class="fa-solid fa-code"></i></div>
              <h4 style="font-size:1.1rem; color:#0f172a; margin:0; font-weight:700;">Software</h4>
            </div>
            <div class="roi-card animate-on-scroll delay-4" style="text-align:center; padding:1.5rem;">
              <div class="icon-colorful icon-colorful--green" style="margin:0 auto 1rem; width:48px; height:48px; font-size:1.25rem;"><i class="fa-solid fa-flask"></i></div>
              <h4 style="font-size:1.1rem; color:#0f172a; margin:0; font-weight:700;">Biotech</h4>
            </div>
          </div>
        </div>
      </section>
      <?php elseif ($country_slug === 'mauritius'): ?>

      <!-- MODULE: THE 2026 STRATEGIC ADVANTAGE -->
      <section class="section" style="background:#f8fafc; border-top:1px solid #e2e8f0;">
        <div class="container">
          <div class="section__header animate-on-scroll">
            <span class="section__tag">The Indian Ocean Hub</span>
            <h2 class="section__title">The 2026 <span>Strategic Advantage</span></h2>
            <p style="color:var(--gray); margin-top: 0.5rem;">"Mauritius has transformed into a global education island." Earn world-class British, Australian, and French degrees in a safe tropical paradise.</p>
            <div class="accent-bar"></div>
          </div>
          <div class="grid grid--3 gap--4">
            <div class="pillar-card animate-on-scroll">
              <div class="pillar-num" style="background:rgba(139, 92, 246, 0.08); color:var(--neon-purple);"><i class="fa-solid fa-shield-halved"></i></div>
              <h4>Fees Security</h4>
              <p>One of the safest pathways for Indian students. Pay the majority of your tuition fees only <strong>after</strong> receiving your visa approval.</p>
            </div>
            <div class="pillar-card animate-on-scroll delay-1">
              <div class="pillar-num" style="background:rgba(16, 185, 129, 0.08); color:var(--neon-green);"><i class="fa-solid fa-file-circle-check"></i></div>
              <h4>Simplified Visa</h4>
              <p>No mandatory interview process for most Indian applicants. Simplified documentation leads to high approval rates and faster processing.</p>
            </div>
            <div class="pillar-card animate-on-scroll delay-2">
              <div class="pillar-num" style="background:rgba(245, 158, 11, 0.08); color:var(--neon-orange);"><i class="fa-solid fa-umbrella-beach"></i></div>
              <h4>Multicultural Comfort</h4>
              <p>Strong Indian cultural influence with Tamil and Hindi communities. Enjoy South Indian cuisine and a safe, welcoming tropical environment.</p>
            </div>
          </div>
        </div>
      </section>

      <!-- MODULE: YPOP WORK FRAMEWORK -->
      <section class="section">
        <div class="container">
          <div class="section__header animate-on-scroll">
            <span class="section__tag">The 2026 Mauritius Work & Residence Blueprint</span>
            <h2 class="section__title">The “Young Professional” <span>Work Framework</span></h2>
            <div class="accent-bar"></div>
          </div>
          <div class="grid grid--2 gap--4">
            <div class="info-card animate-on-scroll">
              <div class="ic-header"><div class="icon-colorful icon-colorful--blue" style="font-size:1.5rem; margin-right:.75rem"><i class="fa-solid fa-briefcase"></i></div> <h4>YPOP Permit (3 Years)</h4></div>
              <div class="ic-body">
                <ul class="benefit-list" style="margin-top: 1rem;">
                  <li><i class="fa-solid fa-check-circle text-success"></i> Young Professional Occupation Permit for graduates</li>
                  <li><i class="fa-solid fa-id-card text-success"></i> Combined Work & Residence permit after job offer</li>
                </ul>
              </div>
            </div>
            <div class="info-card animate-on-scroll delay-1">
              <div class="ic-header"><div class="icon-colorful icon-colorful--orange" style="font-size:1.5rem; margin-right:.75rem"><i class="fa-solid fa-clock"></i></div> <h4>Student Work Rights</h4></div>
              <div class="ic-body">
                <ul class="benefit-list" style="margin-top: 1rem;">
                  <li><i class="fa-solid fa-calendar-check text-success"></i> Work up to 20 hours/week from Day 1 of studies</li>
                  <li><i class="fa-solid fa-hotel text-success"></i> Approved sectors: Hospitality, Retail, IT, and Fintech</li>
                </ul>
              </div>
            </div>
            <div class="info-card animate-on-scroll delay-2">
              <div class="ic-header"><div class="icon-colorful icon-colorful--purple" style="font-size:1.5rem; margin-right:.75rem"><i class="fa-solid fa-money-bill-trend-up"></i></div> <h4>Salary Threshold</h4></div>
              <div class="ic-body">
                <p style="color:var(--gray); font-size:0.95rem;">Qualify for professional work permits with a minimum salary of <strong>USD $655/month</strong> (~₹55k) for skilled graduates.</p>
              </div>
            </div>
            <div class="info-card animate-on-scroll delay-3">
              <div class="ic-header"><div class="icon-colorful icon-colorful--green" style="font-size:1.5rem; margin-right:.75rem"><i class="fa-solid fa-earth-europe"></i></div> <h4>Career Stepping Stone</h4></div>
              <div class="ic-body">
                <p style="color:var(--gray); font-size:0.95rem;">Graduates frequently use Mauritius as a gateway to careers in <strong>Europe, UAE, and Australia</strong>.</p>
              </div>
            </div>
          </div>
        </div>
      </section>

      <!-- MODULE: FEES & FINANCES -->
      <section class="section" style="background:#f8fafc; border-top:1px solid #e2e8f0;">
        <div class="container">
          <div class="grid grid--2 gap--4">
            <div class="animate-on-scroll">
              <h3 style="font-size:1.5rem; color:#0f172a; margin-bottom:1.5rem; font-weight:800;"><i class="fa-solid fa-coins" style="color:var(--neon-blue); margin-right:0.5rem;"></i> Official 2026 Benchmarks</h3>
              <div class="stayback-container">
                <table class="stayback-table">
                    <thead>
                        <tr>
                            <th>Category</th>
                            <th>2026 Requirement (USD)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr><td style="font-weight:700;">Living Cost (Monthly)</td><td>$400 – $800 (~₹33k-66k)</td></tr>
                        <tr><td style="font-weight:700;">Annual Maintenance</td><td>$4,800 – $9,600 (~₹4L-8L)</td></tr>
                        <tr><td style="font-weight:700;">Undergrad Tuition</td><td>$4,000 – $10,000 /year</td></tr>
                        <tr><td style="font-weight:700;">MBBS Tuition</td><td>$10,000 – $15,000 /year</td></tr>
                    </tbody>
                </table>
              </div>
            </div>
            <div class="animate-on-scroll delay-1">
              <h3 style="font-size:1.5rem; color:#0f172a; margin-bottom:1.5rem; font-weight:800;"><i class="fa-solid fa-circle-info" style="color:var(--neon-orange); margin-right:0.5rem;"></i> Expert Note</h3>
              <div class="highlight-box bg-dots" style="background: white; border: 1px solid #e2e8f0; border-radius: 20px; padding: 2rem;">
                <ul class="benefit-list">
                  <li><i class="fa-solid fa-check-circle text-success"></i> Visa Fee: Usually included (Institutional)</li>
                  <li><i class="fa-solid fa-check-circle text-success"></i> "Approval in Principle" required before travel</li>
                  <li><i class="fa-solid fa-award text-primary"></i> <strong>Pro Tip:</strong> Mauritius is one of the lowest-risk pathways due to post-visa fee payments!</li>
                  <li><i class="fa-solid fa-globe text-primary"></i> UK/Australian degrees available locally.</li>
                </ul>
              </div>
            </div>
          </div>
        </div>
      </section>

      <!-- MODULE: SUCCESS TIMELINE -->
      <section class="section">
        <div class="container">
          <div class="section__header animate-on-scroll">
            <span class="section__tag">Application Strategy</span>
            <h2 class="section__title">Success <span>Timeline</span></h2>
            <div class="accent-bar"></div>
          </div>
          <div class="grid grid--2 gap--4 align-center">
             <div class="animate-on-scroll">
               <ul class="benefit-list">
                 <li><i class="fa-solid fa-circle-check" style="color:var(--neon-blue);"></i> Main Intake (Jan/Feb): Apply before Nov 2025</li>
                 <li><i class="fa-solid fa-circle-check" style="color:var(--neon-blue);"></i> Secondary Intake (Aug/Sept): Apply by June 2026</li>
                 <li><i class="fa-solid fa-circle-check" style="color:var(--neon-blue);"></i> Visa Duration: 4 to 6 weeks standard</li>
                 <li><i class="fa-solid fa-circle-check" style="color:var(--neon-blue);"></i> Entry: Travel on Approval-in-Principle</li>
               </ul>
             </div>
             <div class="animate-on-scroll delay-1">
               <div class="info-card" style="background:var(--primary); color:white; border:none;">
                 <div class="ic-body">
                   <h4 style="color:white; margin-bottom:1rem;">Safe Tropical Lifestyle</h4>
                   <p style="opacity:0.9;"><strong>Multicultural:</strong> Easy adaptation for Indian students.</p>
                   <p style="opacity:0.9;"><strong>Safe:</strong> Secure coastal living with global standards.</p>
                 </div>
               </div>
             </div>
          </div>
        </div>
      </section>

      <?php elseif ($country_slug === 'japan'): ?>

      <!-- MODULE: THE 2026 STRATEGIC ADVANTAGE -->
      <section class="section" style="background:#f8fafc; border-top:1px solid #e2e8f0;">
        <div class="container">
          <div class="section__header animate-on-scroll">
            <span class="section__tag">High-Tech Heritage</span>
            <h2 class="section__title">The 2026 <span>Strategic Advantage</span></h2>
            <p style="color:var(--gray); margin-top: 0.5rem;">"Japan is raising the standard for global talent recruitment." Access the world's most precise technical education and an elite corporate ecosystem.</p>
            <div class="accent-bar"></div>
          </div>
          <div class="grid grid--3 gap--4">
            <div class="pillar-card animate-on-scroll">
              <div class="pillar-num" style="background:rgba(139, 92, 246, 0.08); color:var(--neon-purple);"><i class="fa-solid fa-robot"></i></div>
              <h4>Innovation Frontier</h4>
              <p>Global leader in Robotics, Automotive Engineering, AI, and Semiconductor tech. Direct exposure to Sony, Toyota, and Nintendo.</p>
            </div>
            <div class="pillar-card animate-on-scroll delay-1">
              <div class="pillar-num" style="background:rgba(16, 185, 129, 0.08); color:var(--neon-green);"><i class="fa-solid fa-file-shield"></i></div>
              <h4>COE Mastery</h4>
              <p>Structure your visa through the mandatory Certificate of Eligibility (COE) with a verified ~95% approval rate via institution sponsorship.</p>
            </div>
            <div class="pillar-card animate-on-scroll delay-2">
              <div class="pillar-num" style="background:rgba(245, 158, 11, 0.08); color:var(--neon-orange);"><i class="fa-solid fa-language"></i></div>
              <h4>G30 English Programs</h4>
              <p>No longer limited to Japanese-medium studies. Access hundreds of "Global 30" programs taught fully in English at elite universities.</p>
            </div>
          </div>
        </div>
      </section>

      <!-- MODULE: WORK & CAREER BLUEPRINT -->
      <section class="section">
        <div class="container">
          <div class="section__header animate-on-scroll">
            <span class="section__tag">The 2026 Japan Work & Residence Path</span>
            <h2 class="section__title">The “28-Hour” <span>Work & Career Blueprint</span></h2>
            <div class="accent-bar"></div>
          </div>
          <div class="grid grid--2 gap--4">
            <div class="info-card animate-on-scroll">
              <div class="ic-header"><div class="icon-colorful icon-colorful--blue" style="font-size:1.5rem; margin-right:.75rem"><i class="fa-solid fa-clock"></i></div> <h4>Part-Time Work Rights</h4></div>
              <div class="ic-body">
                <ul class="benefit-list" style="margin-top: 1rem;">
                  <li><i class="fa-solid fa-check-circle text-success"></i> Work 28 hours/week during studies (40h on holidays)</li>
                  <li><i class="fa-solid fa-coins text-success"></i> Average Earnings: ¥1,000 – ¥1,500/hour (~₹600-900)</li>
                </ul>
              </div>
            </div>
            <div class="info-card animate-on-scroll delay-1">
              <div class="ic-header"><div class="icon-colorful icon-colorful--orange" style="font-size:1.5rem; margin-right:.75rem"><i class="fa-solid fa-id-card-clip"></i></div> <h4>Job-Seeker Stay-back</h4></div>
              <div class="ic-body">
                <ul class="benefit-list" style="margin-top: 1rem;">
                  <li><i class="fa-solid fa-calendar-check text-success"></i> 1-Year Designated Activities Visa after graduation</li>
                  <li><i class="fa-solid fa-bolt text-success"></i> Direct pathway to Engineer/Specialist work visas</li>
                </ul>
              </div>
            </div>
            <div class="info-card animate-on-scroll delay-2">
              <div class="ic-header"><div class="icon-colorful icon-colorful--purple" style="font-size:1.5rem; margin-right:.75rem"><i class="fa-solid fa-chart-line"></i></div> <h4>Professional PR Pathway</h4></div>
              <div class="ic-body">
                <p style="color:var(--gray); font-size:0.95rem;">Japan offers <strong>accelerated Permanent Residency</strong> for highly skilled professionals earning top points in their field.</p>
              </div>
            </div>
            <div class="info-card animate-on-scroll delay-3">
              <div class="ic-header"><div class="icon-colorful icon-colorful--green" style="font-size:1.5rem; margin-right:.75rem"><i class="fa-solid fa-building"></i></div> <h4>Corporate Exposure</h4></div>
              <div class="ic-body">
                <p style="color:var(--gray); font-size:0.95rem;">Japanese universities provide one of Asia’s best internship systems, allowing students to gain <strong>real corporate exposure</strong> while studying.</p>
              </div>
            </div>
          </div>
        </div>
      </section>

      <!-- MODULE: FEES & FINANCES -->
      <section class="section" style="background:#f8fafc; border-top:1px solid #e2e8f0;">
        <div class="container">
          <div class="grid grid--2 gap--4">
            <div class="animate-on-scroll">
              <h3 style="font-size:1.5rem; color:#0f172a; margin-bottom:1.5rem; font-weight:800;"><i class="fa-solid fa-wallet" style="color:var(--neon-blue); margin-right:0.5rem;"></i> Official 2026 Benchmarks</h3>
              <div class="stayback-container">
                <table class="stayback-table">
                    <thead>
                        <tr>
                            <th>Category</th>
                            <th>2026 Requirement (JPY ¥)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr><td style="font-weight:700;">Living Funds (Annual)</td><td>¥2,000,000 (~₹11.5L-12.5L)</td></tr>
                        <tr><td style="font-weight:700;">Public Univ Tuition</td><td>¥535,800 – ¥820,000 /yr</td></tr>
                        <tr><td style="font-weight:700;">Private Univ Tuition</td><td>¥1.2M – ¥1.8M /yr</td></tr>
                        <tr><td style="font-weight:700;">Visa Fee (Single)</td><td>¥3,000 (~₹1,750)</td></tr>
                    </tbody>
                </table>
              </div>
            </div>
            <div class="animate-on-scroll delay-1">
              <h3 style="font-size:1.5rem; color:#0f172a; margin-bottom:1.5rem; font-weight:800;"><i class="fa-solid fa-circle-info" style="color:var(--neon-orange); margin-right:0.5rem;"></i> 2026 Lifestyle Costs</h3>
              <div class="highlight-box bg-dots" style="background: white; border: 1px solid #e2e8f0; border-radius: 20px; padding: 2rem;">
                <ul class="benefit-list">
                  <li><i class="fa-solid fa-check-circle text-success"></i> Monthly Living: ¥120,000 – ¥150,000</li>
                  <li><i class="fa-solid fa-check-circle text-success"></i> Supermarket "8 PM Rule" for budget savings!</li>
                  <li><i class="fa-solid fa-shield-halved text-primary"></i> <strong>COE Buffer:</strong> Allow 1–3 months for COE processing.</li>
                  <li><i class="fa-solid fa-train text-primary"></i> Safe society with world-class public transport.</li>
                </ul>
              </div>
            </div>
          </div>
        </div>
      </section>

      <!-- MODULE: SUCCESS TIMELINE -->
      <section class="section">
        <div class="container">
          <div class="section__header animate-on-scroll">
            <span class="section__tag">Application Strategy</span>
            <h2 class="section__title">Success <span>Timeline</span></h2>
            <div class="accent-bar"></div>
          </div>
          <div class="grid grid--2 gap--4 align-center">
             <div class="animate-on-scroll">
               <ul class="benefit-list">
                 <li><i class="fa-solid fa-circle-check" style="color:var(--neon-blue);"></i> Spring Intake (April): Close Nov 2025</li>
                 <li><i class="fa-solid fa-circle-check" style="color:var(--neon-blue);"></i> Autumn Intake (Sept/Oct): Secondary Intake</li>
                 <li><i class="fa-solid fa-circle-check" style="color:var(--neon-blue);"></i> Preparation: Begin at least 6 months early</li>
                 <li><i class="fa-solid fa-circle-check" style="color:var(--neon-blue);"></i> Path: Language schools to full degree options</li>
               </ul>
             </div>
             <div class="animate-on-scroll delay-1">
               <div class="info-card" style="background:var(--primary); color:white; border:none;">
                 <div class="ic-body">
                   <h4 style="color:white; margin-bottom:1rem;">N2 Pathway Focus</h4>
                   <p style="opacity:0.9;"><strong>Career:</strong> Reach JLPT N2 for high-paying corporate roles.</p>
                   <p style="opacity:0.9;"><strong>Support:</strong> Expert guidance on COE and Institution sponsorship.</p>
                 </div>
               </div>
             </div>
          </div>
        </div>
      </section>

      <!-- MODULE: CLIMATE & FOOD -->
      <section class="section" style="background:#f8fafc; border-top:1px solid #e2e8f0;">
        <div class="container">
          <div class="grid grid--2 gap--4">
            <div class="animate-on-scroll">
              <h3 style="font-size:1.5rem; color:#0f172a; margin-bottom:1.5rem; font-weight:800;"><i class="fa-solid fa-mountain-sun" style="color:var(--neon-orange); margin-right:0.5rem;"></i> Seasonal Experience</h3>
              <ul class="benefit-list" style="margin-bottom: 2rem;">
                <li><i class="fa-solid fa-spa" style="color:var(--neon-purple);"></i> Spring: Pleasant with iconic Cherry Blossoms</li>
                <li><i class="fa-solid fa-sun" style="color:var(--neon-orange);"></i> Summer: Warm, humid, and vibrant festivals</li>
                <li><i class="fa-solid fa-leaf" style="color:var(--neon-green);"></i> Autumn: Cool, colorful, and scenic landscapes</li>
                <li><i class="fa-solid fa-snowflake" style="color:var(--neon-blue);"></i> Winter: Cold with heavy snowfall in northern regions</li>
              </ul>
            </div>
            <div class="animate-on-scroll delay-1">
              <h3 style="font-size:1.5rem; color:#0f172a; margin-bottom:1.5rem; font-weight:800;"><i class="fa-solid fa-bowl-rice" style="color:var(--neon-green); margin-right:0.5rem;"></i> Gastronomy & Living</h3>
              <p style="color:var(--gray); margin-bottom:1.5rem; line-height:1.6;">Global culinary hub with strong Indian support:</p>
              <ul class="benefit-list">
                <li><i class="fa-solid fa-store" style="color:var(--neon-green);"></i> South Indian groceries in Tokyo, Osaka, and Nagoya</li>
                <li><i class="fa-solid fa-fish-fins" style="color:var(--neon-green);"></i> World-class Ramen, Sushi, Bento, and Udon</li>
                <li><i class="fa-solid fa-leaf" style="color:var(--neon-green);"></i> Extensive vegetarian and halal options in cities</li>
                <li><i class="fa-solid fa-shield" style="color:var(--neon-blue);"></i> Globally admired for safety, cleanliness, and discipline</li>
              </ul>
            </div>
          </div>
        </div>
      </section>
      <?php elseif ($country_slug === 'vietnam'): ?>

      <!-- MODULE: THE 2026 STRATEGIC ADVANTAGE -->
      <section class="section" style="background:#f8fafc; border-top:1px solid #e2e8f0;">
        <div class="container">
          <div class="section__header animate-on-scroll">
            <span class="section__tag">Affordable Excellence</span>
            <h2 class="section__title">The 2026 <span>Strategic Advantage</span></h2>
            <p style="color:var(--gray); margin-top: 0.5rem;">"Vietnam is the new frontier for high-value global degrees." Access world-class STEM, Medicine, and Business programs in Asia's fastest-growing economy.</p>
            <div class="accent-bar"></div>
          </div>
          <div class="grid grid--3 gap--4">
            <div class="pillar-card animate-on-scroll">
              <div class="pillar-num" style="background:rgba(139, 92, 246, 0.08); color:var(--neon-purple);"><i class="fa-solid fa-stethoscope"></i></div>
              <h4>The MBBS Advantage</h4>
              <p>100% NMC-compliant medical programs. Complete your 6-year degree at nearly half the cost of many private Indian institutions.</p>
            </div>
            <div class="pillar-card animate-on-scroll delay-1">
              <div class="pillar-num" style="background:rgba(16, 185, 129, 0.08); color:var(--neon-green);"><i class="fa-solid fa-microchip"></i></div>
              <h4>Southeast Asian Tech Hub</h4>
              <p>Strategic gateway for IT Outsourcing and Semiconductor manufacturing. Home to massive R&D centers for Samsung, Intel, and LG.</p>
            </div>
            <div class="pillar-card animate-on-scroll delay-2">
              <div class="pillar-num" style="background:rgba(245, 158, 11, 0.08); color:var(--neon-orange);"><i class="fa-solid fa-file-invoice-dollar"></i></div>
              <h4>Digital DH Visa</h4>
              <p>Fully digital, institution-sponsored student visa system (DH Visa) with simplified processing and high approval rates for 2026.</p>
            </div>
          </div>
        </div>
      </section>

      <!-- MODULE: DH VISA & RESIDENCE BLUEPRINT -->
      <section class="section">
        <div class="container">
          <div class="section__header animate-on-scroll">
            <span class="section__tag">The 2026 Vietnam Work & Residence Path</span>
            <h2 class="section__title">The “DH Visa” & <span>Residence Blueprint</span></h2>
            <div class="accent-bar"></div>
          </div>
          <div class="grid grid--2 gap--4">
            <div class="info-card animate-on-scroll">
              <div class="ic-header"><div class="icon-colorful icon-colorful--blue" style="font-size:1.5rem; margin-right:.75rem"><i class="fa-solid fa-id-card"></i></div> <h4>DH Student Visa</h4></div>
              <div class="ic-body">
                <ul class="benefit-list" style="margin-top: 1rem;">
                  <li><i class="fa-solid fa-check-circle text-success"></i> Valid for 6–12 months and extendable locally</li>
                  <li><i class="fa-solid fa-university text-success"></i> Sponsored directly by your Vietnamese institution</li>
                </ul>
              </div>
            </div>
            <div class="info-card animate-on-scroll delay-1">
              <div class="ic-header"><div class="icon-colorful icon-colorful--orange" style="font-size:1.5rem; margin-right:.75rem"><i class="fa-solid fa-address-card"></i></div> <h4>Residence Card (TRC)</h4></div>
              <div class="ic-body">
                <ul class="benefit-list" style="margin-top: 1rem;">
                  <li><i class="fa-solid fa-calendar-check text-success"></i> Qualify for 2–5 year cards for longer programs</li>
                  <li><i class="fa-solid fa-building-columns text-success"></i> Easier access to banking and local services</li>
                </ul>
              </div>
            </div>
            <div class="info-card animate-on-scroll delay-2">
              <div class="ic-header"><div class="icon-colorful icon-colorful--purple" style="font-size:1.5rem; margin-right:.75rem"><i class="fa-solid fa-briefcase"></i></div> <h4>Paid Practicums</h4></div>
              <div class="ic-body">
                <ul class="benefit-list" style="margin-top: 1rem;">
                  <li><i class="fa-solid fa-check-double text-success"></i> Legally participate in university-authorized internships</li>
                  <li><i class="fa-solid fa-money-bill-wave text-success"></i> Companies can sponsor paid practical training sessions</li>
                </ul>
              </div>
            </div>
            <div class="info-card animate-on-scroll delay-3">
              <div class="ic-header"><div class="icon-colorful icon-colorful--green" style="font-size:1.5rem; margin-right:.75rem"><i class="fa-solid fa-arrow-right-arrow-left"></i></div> <h4>Status Conversion</h4></div>
              <div class="ic-body">
                <p style="color:var(--gray); font-size:0.95rem;">Legally convert from <strong>Student to Worker</strong> status without exiting Vietnam after securing employment.</p>
              </div>
            </div>
          </div>
        </div>
      </section>

      <!-- MODULE: FEES & FINANCES -->
      <section class="section" style="background:#f8fafc; border-top:1px solid #e2e8f0;">
        <div class="container">
          <div class="grid grid--2 gap--4">
            <div class="animate-on-scroll">
              <h3 style="font-size:1.5rem; color:#0f172a; margin-bottom:1.5rem; font-weight:800;"><i class="fa-solid fa-coins" style="color:var(--neon-blue); margin-right:0.5rem;"></i> Official 2026 Benchmarks (INR)</h3>
              <div class="stayback-container">
                <table class="stayback-table">
                    <thead>
                        <tr>
                            <th>Category</th>
                            <th>2026 Requirement (Approx.)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr><td style="font-weight:700;">Living Funds (Monthly)</td><td>₹25,000 – ₹45,000</td></tr>
                        <tr><td style="font-weight:700;">Public Univ Tuition</td><td>₹80k – ₹2.5 Lakhs /year</td></tr>
                        <tr><td style="font-weight:700;">MBBS Tuition</td><td>₹3.5L – ₹6 Lakhs /year</td></tr>
                        <tr><td style="font-weight:700;">DH Visa Stamping</td><td>₹2,200 – ₹4,500</td></tr>
                    </tbody>
                </table>
              </div>
            </div>
            <div class="animate-on-scroll delay-1">
              <h3 style="font-size:1.5rem; color:#0f172a; margin-bottom:1.5rem; font-weight:800;"><i class="fa-solid fa-circle-info" style="color:var(--neon-orange); margin-right:0.5rem;"></i> Expert Note</h3>
              <div class="highlight-box bg-dots" style="background: white; border: 1px solid #e2e8f0; border-radius: 20px; padding: 2rem;">
                <ul class="benefit-list">
                  <li><i class="fa-solid fa-check-circle text-success"></i> Health Insurance: ₹25k – ₹50k /year</li>
                  <li><i class="fa-solid fa-check-circle text-success"></i> 6-Year MBBS Total: ₹22L – ₹35L</li>
                  <li><i class="fa-solid fa-award text-primary"></i> <strong>Pro Tip:</strong> Indian MBBS aspirants must have a valid <strong>NEET-UG</strong> score!</li>
                  <li><i class="fa-solid fa-bolt text-primary"></i> Institution-sponsored visa approval letter is mandatory.</li>
                </ul>
              </div>
            </div>
          </div>
        </div>
      </section>

      <!-- MODULE: SUCCESS TIMELINE -->
      <section class="section">
        <div class="container">
          <div class="section__header animate-on-scroll">
            <span class="section__tag">Application Strategy</span>
            <h2 class="section__title">Success <span>Timeline</span></h2>
            <div class="accent-bar"></div>
          </div>
          <div class="grid grid--2 gap--4 align-center">
             <div class="animate-on-scroll">
               <ul class="benefit-list">
                 <li><i class="fa-solid fa-circle-check" style="color:var(--neon-blue);"></i> Fall Intake (Sept): Apply Mar – June 2026</li>
                 <li><i class="fa-solid fa-circle-check" style="color:var(--neon-blue);"></i> Spring Intake (Feb): Limited secondary intake</li>
                 <li><i class="fa-solid fa-circle-check" style="color:var(--neon-blue);"></i> Visa Buffer: Start process 4–6 months early</li>
                 <li><i class="fa-solid fa-circle-check" style="color:var(--neon-blue);"></i> Entry: Secure Approval Letter before travel</li>
               </ul>
             </div>
             <div class="animate-on-scroll delay-1">
               <div class="info-card" style="background:var(--primary); color:white; border:none;">
                 <div class="ic-body">
                   <h4 style="color:white; margin-bottom:1rem;">MBBS Career Bridge</h4>
                   <p style="opacity:0.9;"><strong>NMC-Compliant:</strong> Verified medical programs for Indian students.</p>
                   <p style="opacity:0.9;"><strong>English-Medium:</strong> Expanding tech and business degree options.</p>
                 </div>
               </div>
             </div>
          </div>
        </div>
      </section>

      <?php elseif ($country_slug === 'malta'): ?>

      <!-- MODULE: THE 2026 STRATEGIC ADVANTAGE -->
      <section class="section" style="background:#f8fafc; border-top:1px solid #e2e8f0;">
        <div class="container">
          <div class="section__header animate-on-scroll">
            <span class="section__tag">Mediterranean English-Taught Excellence</span>
            <h2 class="section__title">The 2026 <span>Strategic Advantage</span></h2>
            <p style="color:var(--gray); margin-top: 0.5rem;">"Malta is the affordable gateway to European degrees." Access a 100% English-speaking EU education system with unmatched Schengen mobility.</p>
            <div class="accent-bar"></div>
          </div>
          <div class="grid grid--3 gap--4">
            <div class="pillar-card animate-on-scroll">
              <div class="pillar-num" style="background:rgba(139, 92, 246, 0.08); color:var(--neon-purple);"><i class="fa-solid fa-earth-europe"></i></div>
              <h4>Schengen Mobility</h4>
              <p>Your e-Residence Permit enables visa-free travel across 29 Schengen countries. Network and explore careers across the entire EU.</p>
            </div>
            <div class="pillar-card animate-on-scroll delay-1">
              <div class="pillar-num" style="background:rgba(16, 185, 129, 0.08); color:var(--neon-green);"><i class="fa-solid fa-id-card"></i></div>
              <h4>Single Permit Pathway</h4>
              <p>Seamlessly transition from student to professional. Malta's "Single Permit" combines work and residence for qualified graduates.</p>
            </div>
            <div class="pillar-card animate-on-scroll delay-2">
              <div class="pillar-num" style="background:rgba(245, 158, 11, 0.08); color:var(--neon-orange);"><i class="fa-solid fa-building-columns"></i></div>
              <h4>British Heritage</h4>
              <p>Academic standards and curriculum based on the UK model. Earn globally recognized qualifications in a Mediterranean setting.</p>
            </div>
          </div>
        </div>
      </section>

      <!-- MODULE: WORK & RESIDENCE BLUEPRINT -->
      <section class="section">
        <div class="container">
          <div class="section__header animate-on-scroll">
            <span class="section__tag">The 2026 Malta Work & Residence Path</span>
            <h2 class="section__title">The “Work & <span>Residence” Blueprint</span></h2>
            <div class="accent-bar"></div>
          </div>
          <div class="grid grid--2 gap--4">
            <div class="info-card animate-on-scroll">
              <div class="ic-header"><div class="icon-colorful icon-colorful--blue" style="font-size:1.5rem; margin-right:.75rem"><i class="fa-solid fa-briefcase"></i></div> <h4>Student Work Rights</h4></div>
              <div class="ic-body">
                <ul class="benefit-list" style="margin-top: 1rem;">
                  <li><i class="fa-solid fa-check-circle text-success"></i> Work 20 hours/week from the first year of study</li>
                  <li><i class="fa-solid fa-bolt text-success"></i> Immediate authorization after residence permit approval</li>
                </ul>
              </div>
            </div>
            <div class="info-card animate-on-scroll delay-1">
              <div class="ic-header"><div class="icon-colorful icon-colorful--orange" style="font-size:1.5rem; margin-right:.75rem"><i class="fa-solid fa-id-card-clip"></i></div> <h4>12-Month Stay-back</h4></div>
              <div class="ic-body">
                <ul class="benefit-list" style="margin-top: 1rem;">
                  <li><i class="fa-solid fa-calendar-check text-success"></i> Post-study extension for job searching and interviews</li>
                  <li><i class="fa-solid fa-rocket text-success"></i> Open work rights during the extension period</li>
                </ul>
              </div>
            </div>
            <div class="info-card animate-on-scroll delay-2">
              <div class="ic-header"><div class="icon-colorful icon-colorful--purple" style="font-size:1.5rem; margin-right:.75rem"><i class="fa-solid fa-address-card"></i></div> <h4>e-Residence Permit</h4></div>
              <div class="ic-body">
                <ul class="benefit-list" style="margin-top: 1rem;">
                  <li><i class="fa-solid fa-check-double text-success"></i> Your official legal residence and travel document</li>
                  <li><i class="fa-solid fa-shield-halved text-success"></i> Apply within 90 days of arrival for full EU mobility</li>
                </ul>
              </div>
            </div>
            <div class="info-card animate-on-scroll delay-3">
              <div class="ic-header"><div class="icon-colorful icon-colorful--green" style="font-size:1.5rem; margin-right:.75rem"><i class="fa-solid fa-chart-line"></i></div> <h4>Digital Economy</h4></div>
              <div class="ic-body">
                <p style="color:var(--gray); font-size:0.95rem;">Malta is a rising hub for <strong>FinTech, iGaming, and Blockchain</strong>, creating massive professional demand.</p>
              </div>
            </div>
          </div>
        </div>
      </section>

      <!-- MODULE: FEES & FINANCES -->
      <section class="section" style="background:#f8fafc; border-top:1px solid #e2e8f0;">
        <div class="container">
          <div class="grid grid--2 gap--4">
            <div class="animate-on-scroll">
              <h3 style="font-size:1.5rem; color:#0f172a; margin-bottom:1.5rem; font-weight:800;"><i class="fa-solid fa-wallet" style="color:var(--neon-blue); margin-right:0.5rem;"></i> Official 2026 Benchmarks</h3>
              <div class="stayback-container">
                <table class="stayback-table">
                    <thead>
                        <tr>
                            <th>Category</th>
                            <th>2026 Requirement (€)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr><td style="font-weight:700;">Living Funds (Annual)</td><td>€14,000 (~₹12.6 Lakhs)</td></tr>
                        <tr><td style="font-weight:700;">Public Univ Tuition</td><td>€5,000 – €12,000 /yr</td></tr>
                        <tr><td style="font-weight:700;">Visa App Fee</td><td>€80 – €100</td></tr>
                        <tr><td style="font-weight:700;">e-Residence Fee</td><td>€100 – €230</td></tr>
                    </tbody>
                </table>
              </div>
            </div>
            <div class="animate-on-scroll delay-1">
              <h3 style="font-size:1.5rem; color:#0f172a; margin-bottom:1.5rem; font-weight:800;"><i class="fa-solid fa-circle-info" style="color:var(--neon-orange); margin-right:0.5rem;"></i> Expert Note</h3>
              <div class="highlight-box bg-dots" style="background: white; border: 1px solid #e2e8f0; border-radius: 20px; padding: 2rem;">
                <ul class="benefit-list">
                  <li><i class="fa-solid fa-check-circle text-success"></i> Monthly Budget: €800 – €1,200</li>
                  <li><i class="fa-solid fa-check-circle text-success"></i> Master's Total Cost: ₹15L – ₹25L</li>
                  <li><i class="fa-solid fa-award text-primary"></i> <strong>Pro Tip:</strong> Most institutions require 50% tuition payment before visa support!</li>
                  <li><i class="fa-solid fa-sun text-primary"></i> Enjoy over 300 sunny days and a relaxed Mediterranean lifestyle.</li>
                </ul>
              </div>
            </div>
          </div>
        </div>
      </section>

      <!-- MODULE: SUCCESS TIMELINE -->
      <section class="section">
        <div class="container">
          <div class="section__header animate-on-scroll">
            <span class="section__tag">Application Strategy</span>
            <h2 class="section__title">Success <span>Timeline</span></h2>
            <div class="accent-bar"></div>
          </div>
          <div class="grid grid--2 gap--4 align-center">
             <div class="animate-on-scroll">
               <ul class="benefit-list">
                 <li><i class="fa-solid fa-circle-check" style="color:var(--neon-blue);"></i> Autumn Intake (Oct): Apply by Aug 2026</li>
                 <li><i class="fa-solid fa-circle-check" style="color:var(--neon-blue);"></i> Spring Intake (Feb): Secondary Intake</li>
                 <li><i class="fa-solid fa-circle-check" style="color:var(--neon-blue);"></i> Visa Duration: 4–8 weeks standard</li>
                 <li><i class="fa-solid fa-circle-check" style="color:var(--neon-blue);"></i> VFS Booking: Start at least 5 months early</li>
               </ul>
             </div>
             <div class="animate-on-scroll delay-1">
               <div class="info-card" style="background:var(--primary); color:white; border:none;">
                 <div class="ic-body">
                   <h4 style="color:white; margin-bottom:1rem;">EU Career Gateway</h4>
                   <p style="opacity:0.9;"><strong>English:</strong> 100% English-speaking environment.</p>
                   <p style="opacity:0.9;"><strong>Single Permit:</strong> Combined work + residence authorization.</p>
                 </div>
               </div>
             </div>
          </div>
        </div>
      </section>

      <!-- MODULE: CLIMATE & FOOD -->
      <section class="section" style="background:#f8fafc; border-top:1px solid #e2e8f0;">
        <div class="container">
          <div class="grid grid--2 gap--4">
            <div class="animate-on-scroll">
              <h3 style="font-size:1.5rem; color:#0f172a; margin-bottom:1.5rem; font-weight:800;"><i class="fa-solid fa-umbrella-beach" style="color:var(--neon-orange); margin-right:0.5rem;"></i> Mediterranean Life</h3>
              <ul class="benefit-list" style="margin-bottom: 2rem;">
                <li><i class="fa-solid fa-sun" style="color:var(--neon-orange);"></i> Warm, sunny summers and mild, pleasant winters</li>
                <li><i class="fa-solid fa-sailboat" style="color:var(--neon-blue);"></i> Iconic beach lifestyle with vibrant coastal cities</li>
                <li><i class="fa-solid fa-people-group" style="color:var(--neon-purple);"></i> Inclusive international student community</li>
                <li><i class="fa-solid fa-shield-heart" style="color:var(--neon-blue);"></i> One of the safest and most welcoming EU nations</li>
              </ul>
            </div>
            <div class="animate-on-scroll delay-1">
              <h3 style="font-size:1.5rem; color:#0f172a; margin-bottom:1.5rem; font-weight:800;"><i class="fa-solid fa-utensils" style="color:var(--neon-green); margin-right:0.5rem;"></i> Cuisine & Comfort</h3>
              <p style="color:var(--gray); margin-bottom:1.5rem; line-height:1.6;">A blend of Mediterranean flavors and global support:</p>
              <ul class="benefit-list">
                <li><i class="fa-solid fa-store" style="color:var(--neon-green);"></i> Increasing number of Indian & Asian restaurants</li>
                <li><i class="fa-solid fa-pizza-slice" style="color:var(--neon-green);"></i> Iconic Pastizzi, Seafood Pasta, and Maltese Bread</li>
                <li><i class="fa-solid fa-leaf" style="color:var(--neon-green);"></i> Abundant vegetarian-friendly and global options</li>
                <li><i class="fa-solid fa-comments" style="color:var(--neon-blue);"></i> English-speaking locals make daily life effortless</li>
              </ul>
            </div>
          </div>
        </div>
      </section>
      <?php elseif ($country_slug === 'china'): ?>

      <!-- MODULE: THE 2026 STRATEGIC ADVANTAGE -->
      <section class="section" style="background:#f8fafc; border-top:1px solid #e2e8f0;">
        <div class="container">
          <div class="section__header animate-on-scroll">
            <span class="section__tag">Global Powerhouse</span>
            <h2 class="section__title">The 2026 <span>Strategic Advantage</span></h2>
            <p style="color:var(--gray); margin-top: 0.5rem;">"China is the global engine of tech, medicine, and innovation." Access the world's largest research ecosystem and top-tier C9 League institutions.</p>
            <div class="accent-bar"></div>
          </div>
          <div class="grid grid--3 gap--4">
            <div class="pillar-card animate-on-scroll">
              <div class="pillar-num" style="background:rgba(139, 92, 246, 0.08); color:var(--neon-purple);"><i class="fa-solid fa-microchip"></i></div>
              <h4>Tech Dominance</h4>
              <p>Global leader in AI, 5G, Robotics, and Semiconductor research. Directly link to innovation pipelines in Huawei, Alibaba, and Tencent.</p>
            </div>
            <div class="pillar-card animate-on-scroll delay-1">
              <div class="pillar-num" style="background:rgba(16, 185, 129, 0.08); color:var(--neon-green);"><i class="fa-solid fa-file-invoice"></i></div>
              <h4>JW202 System Mastery</h4>
              <p>Full guidance on the mandatory JW201/JW202 issuance system. Bluestone ensures your documentation is ready for smooth X1 visa approval.</p>
            </div>
            <div class="pillar-card animate-on-scroll delay-2">
              <div class="pillar-num" style="background:rgba(245, 158, 11, 0.08); color:var(--neon-orange);"><i class="fa-solid fa-graduation-cap"></i></div>
              <h4>CSC Scholarship Access</h4>
              <p>Maximize your chances for the Chinese Government Scholarship, covering tuition, housing, and a monthly stipend for select candidates.</p>
            </div>
          </div>
        </div>
      </section>

      <!-- MODULE: MBBS & RESEARCH BLUEPRINT -->
      <section class="section">
        <div class="container">
          <div class="section__header animate-on-scroll">
            <span class="section__tag">The 2026 China Education Roadmap</span>
            <h2 class="section__title">The “C9 League” & <span>MBBS Blueprint</span></h2>
            <div class="accent-bar"></div>
          </div>
          <div class="grid grid--2 gap--4">
            <div class="info-card animate-on-scroll">
              <div class="ic-header"><div class="icon-colorful icon-colorful--blue" style="font-size:1.5rem; margin-right:.75rem"><i class="fa-solid fa-user-doctor"></i></div> <h4>MBBS English-Medium</h4></div>
              <div class="ic-body">
                <ul class="benefit-list" style="margin-top: 1rem;">
                  <li><i class="fa-solid fa-check-circle text-success"></i> 45+ universities offering NMC-aligned medical programs</li>
                  <li><i class="fa-solid fa-hospital text-success"></i> Clinical training in high-exposure global hospitals</li>
                </ul>
              </div>
            </div>
            <div class="info-card animate-on-scroll delay-1">
              <div class="ic-header"><div class="icon-colorful icon-colorful--orange" style="font-size:1.5rem; margin-right:.75rem"><i class="fa-solid fa-flask-vial"></i></div> <h4>Research Integration</h4></div>
              <div class="ic-body">
                <ul class="benefit-list" style="margin-top: 1rem;">
                  <li><i class="fa-solid fa-check-circle text-success"></i> Direct access to elite AI, 5G, and Engineering labs</li>
                  <li><i class="fa-solid fa-industry text-success"></i> Industry-linked internships in China's tech hubs</li>
                </ul>
              </div>
            </div>
            <div class="info-card animate-on-scroll delay-2">
              <div class="ic-header"><div class="icon-colorful icon-colorful--purple" style="font-size:1.5rem; margin-right:.75rem"><i class="fa-solid fa-coins"></i></div> <h4>Affordability</h4></div>
              <div class="ic-body">
                <p style="color:var(--gray); font-size:0.95rem;">One of the lowest costs globally for STEM education, with living expenses starting from just <strong>₹25,000/month</strong>.</p>
              </div>
            </div>
            <div class="info-card animate-on-scroll delay-3">
              <div class="ic-header"><div class="icon-colorful icon-colorful--green" style="font-size:1.5rem; margin-right:.75rem"><i class="fa-solid fa-passport"></i></div> <h4>Fast Visa Process</h4></div>
              <div class="ic-body">
                <p style="color:var(--gray); font-size:0.95rem;">X1 Visa processing typically takes only <strong>1–2 weeks</strong> after the issuance of your JW202 document.</p>
              </div>
            </div>
          </div>
        </div>
      </section>

      <!-- MODULE: FEES & FINANCES -->
      <section class="section" style="background:#f8fafc; border-top:1px solid #e2e8f0;">
        <div class="container">
          <div class="grid grid--2 gap--4">
            <div class="animate-on-scroll">
              <h3 style="font-size:1.5rem; color:#0f172a; margin-bottom:1.5rem; font-weight:800;"><i class="fa-solid fa-wallet" style="color:var(--neon-blue); margin-right:0.5rem;"></i> Official 2026 Benchmarks (CNY)</h3>
              <div class="stayback-container">
                <table class="stayback-table">
                    <thead>
                        <tr>
                            <th>Category</th>
                            <th>2026 Requirement (Approx.)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr><td style="font-weight:700;">Living Cost (Monthly)</td><td>2,200 – 4,300 CNY (~₹25k-50k)</td></tr>
                        <tr><td style="font-weight:700;">Public Univ Tuition</td><td>20,000 – 40,000 CNY /yr</td></tr>
                        <tr><td style="font-weight:700;">MBBS Tuition</td><td>30,000 – 50,000 CNY /yr</td></tr>
                        <tr><td style="font-weight:700;">X1 Visa Fee</td><td>₹15,000 – ₹18,000</td></tr>
                    </tbody>
                </table>
              </div>
            </div>
            <div class="animate-on-scroll delay-1">
              <h3 style="font-size:1.5rem; color:#0f172a; margin-bottom:1.5rem; font-weight:800;"><i class="fa-solid fa-circle-info" style="color:var(--neon-orange); margin-right:0.5rem;"></i> Expert Note</h3>
              <div class="highlight-box bg-dots" style="background: white; border: 1px solid #e2e8f0; border-radius: 20px; padding: 2rem;">
                <ul class="benefit-list">
                  <li><i class="fa-solid fa-check-circle text-success"></i> Annual Living: 26.4k – 51.6k CNY</li>
                  <li><i class="fa-solid fa-check-circle text-success"></i> Scholarship success depends on early JW202 processing.</li>
                  <li><i class="fa-solid fa-award text-primary"></i> <strong>Pro Tip:</strong> Apply early to secure CSC and Provincial funding chances!</li>
                  <li><i class="fa-solid fa-bolt text-primary"></i> Start the JW202 process 8 weeks before travel.</li>
                </ul>
              </div>
            </div>
          </div>
        </div>
      </section>

      <!-- MODULE: SUCCESS TIMELINE -->
      <section class="section">
        <div class="container">
          <div class="section__header animate-on-scroll">
            <span class="section__tag">Application Strategy</span>
            <h2 class="section__title">Success <span>Timeline</span></h2>
            <div class="accent-bar"></div>
          </div>
          <div class="grid grid--2 gap--4 align-center">
             <div class="animate-on-scroll">
               <ul class="benefit-list">
                 <li><i class="fa-solid fa-circle-check" style="color:var(--neon-blue);"></i> Fall Intake (Sept): Apply Mar – June 2026</li>
                 <li><i class="fa-solid fa-circle-check" style="color:var(--neon-blue);"></i> Scholarship (CSC): Closes February 2026</li>
                 <li><i class="fa-solid fa-circle-check" style="color:var(--neon-blue);"></i> Spring Intake: Limited secondary programs</li>
                 <li><i class="fa-solid fa-circle-check" style="color:var(--neon-blue);"></i> Pre-departure: 4–8 weeks recommended buffer</li>
               </ul>
             </div>
             <div class="animate-on-scroll delay-1">
               <div class="info-card" style="background:var(--primary); color:white; border:none;">
                 <div class="ic-body">
                   <h4 style="color:white; margin-bottom:1rem;">C9 League Elite Path</h4>
                   <p style="opacity:0.9;"><strong>Peking:</strong> #2 in Asia, leader in Medicine & Physics.</p>
                   <p style="opacity:0.9;"><strong>Tsinghua:</strong> The "MIT of China" for Engineering excellence.</p>
                 </div>
               </div>
             </div>
          </div>
        </div>
      </section>

      <?php elseif ($country_slug === 'belgium'): ?>

      <!-- MODULE: THE 2026 STRATEGIC ADVANTAGE -->
      <section class="section" style="background:#f8fafc; border-top:1px solid #e2e8f0;">
        <div class="container">
          <div class="section__header animate-on-scroll">
            <span class="section__tag">The Capital of Europe</span>
            <h2 class="section__title">The 2026 <span>Strategic Advantage</span></h2>
            <p style="color:var(--gray); margin-top: 0.5rem;">"Belgium is the decision-making center of Europe." Access world-class STEM innovation and global governance exposure in the heart of the Eurozone.</p>
            <div class="accent-bar"></div>
          </div>
          <div class="grid grid--3 gap--4">
            <div class="pillar-card animate-on-scroll">
              <div class="pillar-num" style="background:rgba(139, 92, 246, 0.08); color:var(--neon-purple);"><i class="fa-solid fa-landmark-dome"></i></div>
              <h4>Policy Hub Exposure</h4>
              <p>Direct access to internships in the European Commission, NATO, and global NGOs. Headquartered in Brussels, the political heart of Europe.</p>
            </div>
            <div class="pillar-card animate-on-scroll delay-1">
              <div class="pillar-num" style="background:rgba(16, 185, 129, 0.08); color:var(--neon-green);"><i class="fa-solid fa-flask"></i></div>
              <h4>Innovation Leader</h4>
              <p>Home to KU Leuven (#60 Globally). Strong pipelines into biotech, pharmaceuticals (GSK, Pfizer, Janssen), and advanced logistics.</p>
            </div>
            <div class="pillar-card animate-on-scroll delay-2">
              <div class="pillar-num" style="background:rgba(245, 158, 11, 0.08); color:var(--neon-orange);"><i class="fa-solid fa-euro-sign"></i></div>
              <h4>Eurozone Connectivity</h4>
              <p>Paris, London, Amsterdam, and Frankfurt are all within 1–2 hours via high-speed rail. Study in a multilingual, highly connected ecosystem.</p>
            </div>
          </div>
        </div>
      </section>

      <!-- MODULE: WORK & RESIDENCE BLUEPRINT -->
      <section class="section">
        <div class="container">
          <div class="section__header animate-on-scroll">
            <span class="section__tag">The 2026 Belgium Work & Residence Path</span>
            <h2 class="section__title">The “Orientation Year” <span>Work & Stay-back</span></h2>
            <div class="accent-bar"></div>
          </div>
          <div class="grid grid--2 gap--4">
            <div class="info-card animate-on-scroll">
              <div class="ic-header"><div class="icon-colorful icon-colorful--blue" style="font-size:1.5rem; margin-right:.75rem"><i class="fa-solid fa-clock"></i></div> <h4>Student Work Allowance</h4></div>
              <div class="ic-body">
                <ul class="benefit-list" style="margin-top: 1rem;">
                  <li><i class="fa-solid fa-check-circle text-success"></i> Work up to 650 hours/year during study</li>
                  <li><i class="fa-solid fa-shield-halved text-success"></i> Reduced social security contributions for students</li>
                </ul>
              </div>
            </div>
            <div class="info-card animate-on-scroll delay-1">
              <div class="ic-header"><div class="icon-colorful icon-colorful--orange" style="font-size:1.5rem; margin-right:.75rem"><i class="fa-solid fa-id-card-clip"></i></div> <h4>12-Month Stay-back</h4></div>
              <div class="ic-body">
                <ul class="benefit-list" style="margin-top: 1rem;">
                  <li><i class="fa-solid fa-calendar-check text-success"></i> "Orientation Year" for job search or starting a startup</li>
                  <li><i class="fa-solid fa-earth-europe text-success"></i> Legal residence extension across the entire EU area</li>
                </ul>
              </div>
            </div>
            <div class="info-card animate-on-scroll delay-2">
              <div class="ic-header"><div class="icon-colorful icon-colorful--purple" style="font-size:1.5rem; margin-right:.75rem"><i class="fa-solid fa-award"></i></div> <h4>Master Mind Scholarship</h4></div>
              <div class="ic-body">
                <p style="color:var(--gray); font-size:0.95rem;">Up to <strong>€10,000/year</strong> grant + full tuition waiver for high-performing international students in Flanders.</p>
              </div>
            </div>
            <div class="info-card animate-on-scroll delay-3">
              <div class="ic-header"><div class="icon-colorful icon-colorful--green" style="font-size:1.5rem; margin-right:.75rem"><i class="fa-solid fa-chart-simple"></i></div> <h4>EU Career Entry</h4></div>
              <div class="ic-body">
                <p style="color:var(--gray); font-size:0.95rem;">Direct entry into <strong>EU Institutions and Pharma clusters</strong>. Belgium is one of the strongest EU career entry points.</p>
              </div>
            </div>
          </div>
        </div>
      </section>

      <!-- MODULE: FEES & FINANCES -->
      <section class="section" style="background:#f8fafc; border-top:1px solid #e2e8f0;">
        <div class="container">
          <div class="grid grid--2 gap--4">
            <div class="animate-on-scroll">
              <h3 style="font-size:1.5rem; color:#0f172a; margin-bottom:1.5rem; font-weight:800;"><i class="fa-solid fa-piggy-bank" style="color:var(--neon-blue); margin-right:0.5rem;"></i> Official 2026 Benchmarks</h3>
              <div class="stayback-container">
                <table class="stayback-table">
                    <thead>
                        <tr>
                            <th>Category</th>
                            <th>2026 Requirement (Approx.)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr><td style="font-weight:700;">Blocked Account (Annual)</td><td>€12,744 (~₹11.8 Lakhs)</td></tr>
                        <tr><td style="font-weight:700;">Living Funds (Monthly)</td><td>€1,062 (~₹98,000)</td></tr>
                        <tr><td style="font-weight:700;">Public Univ Tuition</td><td>€1,000 – €9,000 /yr</td></tr>
                        <tr><td style="font-weight:700;">Visa (Type D) Fee</td><td>€180 – €220</td></tr>
                    </tbody>
                </table>
              </div>
            </div>
            <div class="animate-on-scroll delay-1">
              <h3 style="font-size:1.5rem; color:#0f172a; margin-bottom:1.5rem; font-weight:800;"><i class="fa-solid fa-circle-info" style="color:var(--neon-orange); margin-right:0.5rem;"></i> Expert Note</h3>
              <div class="highlight-box bg-dots" style="background: white; border: 1px solid #e2e8f0; border-radius: 20px; padding: 2rem;">
                <ul class="benefit-list">
                  <li><i class="fa-solid fa-check-circle text-success"></i> Tuition is highly subsidized at public universities.</li>
                  <li><i class="fa-solid fa-check-circle text-success"></i> 500+ Master’s programs fully taught in English.</li>
                  <li><i class="fa-solid fa-award text-primary"></i> <strong>Pro Tip:</strong> Apply early to secure scholarship + housing priority!</li>
                  <li><i class="fa-solid fa-bolt text-primary"></i> Legalized PCC and medical certificate are mandatory for visa.</li>
                </ul>
              </div>
            </div>
          </div>
        </div>
      </section>

      <!-- MODULE: SUCCESS TIMELINE -->
      <section class="section">
        <div class="container">
          <div class="section__header animate-on-scroll">
            <span class="section__tag">Application Strategy</span>
            <h2 class="section__title">Success <span>Timeline</span></h2>
            <div class="accent-bar"></div>
          </div>
          <div class="grid grid--2 gap--4 align-center">
             <div class="animate-on-scroll">
               <ul class="benefit-list">
                 <li><i class="fa-solid fa-circle-check" style="color:var(--neon-blue);"></i> Fall Intake (Sept): Apply Feb – April 2026</li>
                 <li><i class="fa-solid fa-circle-check" style="color:var(--neon-blue);"></i> Visa Processing: 6–10 weeks standard</li>
                 <li><i class="fa-solid fa-circle-check" style="color:var(--neon-blue);"></i> Prep: Start blocked account process early</li>
                 <li><i class="fa-solid fa-circle-check" style="color:var(--neon-blue);"></i> Spring Intake: Limited select programs</li>
               </ul>
             </div>
             <div class="animate-on-scroll delay-1">
               <div class="info-card" style="background:var(--primary); color:white; border:none;">
                 <div class="ic-body">
                   <h4 style="color:white; margin-bottom:1rem;">Top Belgian Rankings</h4>
                   <p style="opacity:0.9;"><strong>KU Leuven:</strong> Global leader in Engineering & AI.</p>
                   <p style="opacity:0.9;"><strong>Ghent:</strong> Strong in Life Sciences & Biotechnology.</p>
                 </div>
               </div>
             </div>
          </div>
        </div>
      </section>

      <!-- MODULE: CLIMATE & FOOD -->
      <section class="section" style="background:#f8fafc; border-top:1px solid #e2e8f0;">
        <div class="container">
          <div class="grid grid--2 gap--4">
            <div class="animate-on-scroll">
              <h3 style="font-size:1.5rem; color:#0f172a; margin-bottom:1.5rem; font-weight:800;"><i class="fa-solid fa-cloud-sun" style="color:var(--neon-blue); margin-right:0.5rem;"></i> Seasonal Experience</h3>
              <ul class="benefit-list" style="margin-bottom: 2rem;">
                <li><i class="fa-solid fa-snowflake" style="color:var(--neon-blue);"></i> Mild winters with frequent light rainfall (0°C to 7°C)</li>
                <li><i class="fa-solid fa-sun" style="color:var(--neon-orange);"></i> Cool, pleasant summers ideal for travel (18°C to 25°C)</li>
                <li><i class="fa-solid fa-train" style="color:var(--neon-purple);"></i> Unmatched EU mobility via high-speed rail networks</li>
              </ul>
            </div>
            <div class="animate-on-scroll delay-1">
              <h3 style="font-size:1.5rem; color:#0f172a; margin-bottom:1.5rem; font-weight:800;"><i class="fa-solid fa-cookie-bite" style="color:var(--neon-green); margin-right:0.5rem;"></i> Gastronomy & Living</h3>
              <p style="color:var(--gray); margin-bottom:1.5rem; line-height:1.6;">Famous for global culinary standards and multicultural life:</p>
              <ul class="benefit-list">
                <li><i class="fa-solid fa-utensils" style="color:var(--neon-green);"></i> Iconic Waffles, Fries, Chocolate, and Beer culture</li>
                <li><i class="fa-solid fa-bowl-food" style="color:var(--neon-green);"></i> Strong Indian, Asian, and Middle Eastern cuisine hubs</li>
                <li><i class="fa-solid fa-users-viewfinder" style="color:var(--neon-blue);"></i> Multicultural dining in Brussels, Leuven, and Ghent</li>
                <li><i class="fa-solid fa-building-circle-check" style="color:var(--neon-blue);"></i> High safety standards with a relaxed student lifestyle</li>
              </ul>
            </div>
          </div>
        </div>
      </section>
      <?php elseif ($country_slug === 'russia'): ?>

      <!-- MODULE: THE 2026 STRATEGIC ADVANTAGE -->
      <section class="section" style="background:#f8fafc; border-top:1px solid #e2e8f0;">
        <div class="container">
          <div class="section__header animate-on-scroll">
            <span class="section__tag">Elite Technical Education</span>
            <h2 class="section__title">The 2026 <span>Strategic Advantage</span></h2>
            <p style="color:var(--gray); margin-top: 0.5rem;">"Russia offers world-class infrastructure at the lowest global cost." Access elite government universities and unmatched ROI for Medicine and STEM.</p>
            <div class="accent-bar"></div>
          </div>
          <div class="grid grid--3 gap--4">
            <div class="pillar-card animate-on-scroll">
              <div class="pillar-num" style="background:rgba(139, 92, 246, 0.08); color:var(--neon-purple);"><i class="fa-solid fa-stethoscope"></i></div>
              <h4>MBBS Authority</h4>
              <p>100% NMC compliant programs. 5.8 years including internship, recognized by WDOMS, WHO, and ECFMG (USA).</p>
            </div>
            <div class="pillar-card animate-on-scroll delay-1">
              <div class="pillar-num" style="background:rgba(16, 185, 129, 0.08); color:var(--neon-green);"><i class="fa-solid fa-id-card"></i></div>
              <h4>Direct PR Pathway</h4>
              <p>New 2026 Skilled Visa provides a direct Permanent Residency pathway for STEM graduates and industry professionals.</p>
            </div>
            <div class="pillar-card animate-on-scroll delay-2">
              <div class="pillar-num" style="background:rgba(245, 158, 11, 0.08); color:var(--neon-orange);"><i class="fa-solid fa-language"></i></div>
              <h4>Admission Ease</h4>
              <p>No IELTS/TOEFL required for most English-medium programs. Direct entry into world-class government university labs.</p>
            </div>
          </div>
        </div>
      </section>

      <!-- MODULE: MEDICAL & TECH BLUEPRINT -->
      <section class="section">
        <div class="container">
          <div class="section__header animate-on-scroll">
            <span class="section__tag">The 2026 Russia Career Roadmap</span>
            <h2 class="section__title">The “Medical & Tech” <span>Blueprint</span></h2>
            <div class="accent-bar"></div>
          </div>
          <div class="grid grid--2 gap--4">
            <div class="info-card animate-on-scroll">
              <div class="ic-header"><div class="icon-colorful icon-colorful--blue" style="font-size:1.5rem; margin-right:.75rem"><i class="fa-solid fa-user-doctor"></i></div> <h4>NMC-Aligned Clinicals</h4></div>
              <div class="ic-body">
                <ul class="benefit-list" style="margin-top: 1rem;">
                  <li><i class="fa-solid fa-check-circle text-success"></i> Mandatory clinical training in government hospitals</li>
                  <li><i class="fa-solid fa-book-medical text-success"></i> Curriculum mapped to FMGE/NExT eligibility standards</li>
                </ul>
              </div>
            </div>
            <div class="info-card animate-on-scroll delay-1">
              <div class="ic-header"><div class="icon-colorful icon-colorful--orange" style="font-size:1.5rem; margin-right:.75rem"><i class="fa-solid fa-rocket"></i></div> <h4>Aerospace & Engineering</h4></div>
              <div class="ic-body">
                <ul class="benefit-list" style="margin-top: 1rem;">
                  <li><i class="fa-solid fa-check-circle text-success"></i> Bauman Moscow State Technical University research access</li>
                  <li><i class="fa-solid fa-atom text-success"></i> Global leader in Nuclear and Aerospace innovation</li>
                </ul>
              </div>
            </div>
            <div class="info-card animate-on-scroll delay-2">
              <div class="ic-header"><div class="icon-colorful icon-colorful--purple" style="font-size:1.5rem; margin-right:.75rem"><i class="fa-solid fa-coins"></i></div> <h4>Unmatched ROI</h4></div>
              <div class="ic-body">
                <p style="color:var(--gray); font-size:0.95rem;">Total medical education budget from <strong>₹18L – ₹30L</strong> for the entire duration, including living costs.</p>
              </div>
            </div>
            <div class="info-card animate-on-scroll delay-3">
              <div class="ic-header"><div class="icon-colorful icon-colorful--green" style="font-size:1.5rem; margin-right:.75rem"><i class="fa-solid fa-house-chimney-user"></i></div> <h4>Indian Support System</h4></div>
              <div class="ic-body">
                <p style="color:var(--gray); font-size:0.95rem;">Subsidized university hostels with <strong>Indian mess facilities</strong> widely available for comfortable student life.</p>
              </div>
            </div>
          </div>
        </div>
      </section>

      <!-- MODULE: FEES & FINANCES -->
      <section class="section" style="background:#f8fafc; border-top:1px solid #e2e8f0;">
        <div class="container">
          <div class="grid grid--2 gap--4">
            <div class="animate-on-scroll">
              <h3 style="font-size:1.5rem; color:#0f172a; margin-bottom:1.5rem; font-weight:800;"><i class="fa-solid fa-wallet" style="color:var(--neon-blue); margin-right:0.5rem;"></i> Official 2026 Benchmarks (INR)</h3>
              <div class="stayback-container">
                <table class="stayback-table">
                    <thead>
                        <tr>
                            <th>Category</th>
                            <th>2026 Requirement (Approx.)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr><td style="font-weight:700;">Living Cost (Monthly)</td><td>₹12,000 – ₹16,000</td></tr>
                        <tr><td style="font-weight:700;">MBBS Tuition (Yearly)</td><td>₹2.5L – ₹8L</td></tr>
                        <tr><td style="font-weight:700;">Engineering Tuition</td><td>₹2L – ₹4L /yr</td></tr>
                        <tr><td style="font-weight:700;">Visa Fee</td><td>₹4,000 – ₹12,000</td></tr>
                    </tbody>
                </table>
              </div>
            </div>
            <div class="animate-on-scroll delay-1">
              <h3 style="font-size:1.5rem; color:#0f172a; margin-bottom:1.5rem; font-weight:800;"><i class="fa-solid fa-circle-info" style="color:var(--neon-orange); margin-right:0.5rem;"></i> Expert Note</h3>
              <div class="highlight-box bg-dots" style="background: white; border: 1px solid #e2e8f0; border-radius: 20px; padding: 2rem;">
                <ul class="benefit-list">
                  <li><i class="fa-solid fa-check-circle text-success"></i> Government universities are highly preferred.</li>
                  <li><i class="fa-solid fa-check-circle text-success"></i> Valid NEET-UG score is mandatory for MBBS.</li>
                  <li><i class="fa-solid fa-award text-primary"></i> <strong>Pro Tip:</strong> Apply early for better hostel allocation and lower fee brackets!</li>
                  <li><i class="fa-solid fa-calendar text-primary"></i> Invitation letter processing takes 30-45 days.</li>
                </ul>
              </div>
            </div>
          </div>
        </div>
      </section>

      <!-- MODULE: SUCCESS TIMELINE -->
      <section class="section">
        <div class="container">
          <div class="section__header animate-on-scroll">
            <span class="section__tag">Application Strategy</span>
            <h2 class="section__title">Success <span>Timeline</span></h2>
            <div class="accent-bar"></div>
          </div>
          <div class="grid grid--2 gap--4 align-center">
             <div class="animate-on-scroll">
               <ul class="benefit-list">
                 <li><i class="fa-solid fa-circle-check" style="color:var(--neon-blue);"></i> Main Intake (Sept): Apply May – July 2026</li>
                 <li><i class="fa-solid fa-circle-check" style="color:var(--neon-blue);"></i> Invitation: Issued by Ministry of Education</li>
                 <li><i class="fa-solid fa-circle-check" style="color:var(--neon-blue);"></i> Visa: 2–4 weeks after invitation receipt</li>
                 <li><i class="fa-solid fa-circle-check" style="color:var(--neon-blue);"></i> Passport: Min 18 months validity required</li>
               </ul>
             </div>
             <div class="animate-on-scroll delay-1">
               <div class="info-card" style="background:var(--primary); color:white; border:none;">
                 <div class="ic-body">
                   <h4 style="color:white; margin-bottom:1rem;">Top Russian Rankings</h4>
                   <p style="opacity:0.9;"><strong>MSU:</strong> Russia's #1, global leader in Math & Physics.</p>
                   <p style="opacity:0.9;"><strong>Bauman:</strong> Elite engineering, robotics, and aerospace hub.</p>
                 </div>
               </div>
             </div>
          </div>
        </div>
      </section>

      <?php elseif ($country_slug === 'south-korea'): ?>

      <!-- MODULE: THE 2026 STRATEGIC ADVANTAGE -->
      <section class="section" style="background:#f8fafc; border-top:1px solid #e2e8f0;">
        <div class="container">
          <div class="section__header animate-on-scroll">
            <span class="section__tag">Hallyu Innovation Powerhouse</span>
            <h2 class="section__title">The 2026 <span>Strategic Advantage</span></h2>
            <p style="color:var(--gray); margin-top: 0.5rem;">"South Korea is the global frontier of tech and creativity." Transition from elite education to long-term tech careers with a structured growth ladder.</p>
            <div class="accent-bar"></div>
          </div>
          <div class="grid grid--3 gap--4">
            <div class="pillar-card animate-on-scroll">
              <div class="pillar-num" style="background:rgba(139, 92, 246, 0.08); color:var(--neon-purple);"><i class="fa-solid fa-microchip"></i></div>
              <h4>Global Tech Pipeline</h4>
              <p>Direct career links into Samsung, LG, Hyundai, and Naver. Benefit from talent-driven immigration and high-tech industry exposure.</p>
            </div>
            <div class="pillar-card animate-on-scroll delay-1">
              <div class="pillar-num" style="background:rgba(16, 185, 129, 0.08); color:var(--neon-green);"><i class="fa-solid fa-bolt"></i></div>
              <h4>K-CORE Transition Visa</h4>
              <p>Launch of the K-CORE Skilled Transition Visa. Seamlessly move from graduation to professional skilled employment in high-demand sectors.</p>
            </div>
            <div class="pillar-card animate-on-scroll delay-2">
              <div class="pillar-num" style="background:rgba(245, 158, 11, 0.08); color:var(--neon-orange);"><i class="fa-solid fa-briefcase"></i></div>
              <h4>Enhanced Work Rights</h4>
              <p>Unprecedented flexibility. Work up to 35 hours/week during semesters and <strong>full-time</strong> during vacations to support your lifestyle.</p>
            </div>
          </div>
        </div>
      </section>

      <!-- MODULE: GROWTH LADDER BLUEPRINT -->
      <section class="section">
        <div class="container">
          <div class="section__header animate-on-scroll">
            <span class="section__tag">The 2026 Korea Career Path</span>
            <h2 class="section__title">The “Growth Ladder” <span>System</span></h2>
            <div class="accent-bar"></div>
          </div>
          <div class="grid grid--2 gap--4">
            <div class="info-card animate-on-scroll">
              <div class="ic-header"><div class="icon-colorful icon-colorful--blue" style="font-size:1.5rem; margin-right:.75rem"><i class="fa-solid fa-id-card"></i></div> <h4>Skilled Worker Pathway</h4></div>
              <div class="ic-body">
                <ul class="benefit-list" style="margin-top: 1rem;">
                  <li><i class="fa-solid fa-check-circle text-success"></i> Transition to E-7 Skilled Worker Visa via TOPIK L5</li>
                  <li><i class="fa-solid fa-handshake text-success"></i> 16 newly designated fast-track technical programs</li>
                </ul>
              </div>
            </div>
            <div class="info-card animate-on-scroll delay-1">
              <div class="ic-header"><div class="icon-colorful icon-colorful--orange" style="font-size:1.5rem; margin-right:.75rem"><i class="fa-solid fa-file-invoice-dollar"></i></div> <h4>Financial Relaxation</h4></div>
              <div class="ic-body">
                <ul class="benefit-list" style="margin-top: 1rem;">
                  <li><i class="fa-solid fa-check-circle text-success"></i> Reduced proof-of-funds for TOPIK Level 3+ students</li>
                  <li><i class="fa-solid fa-coins text-success"></i> Min Wage: ₩10,320/hour (~₹660/hour) for 2026</li>
                </ul>
              </div>
            </div>
            <div class="info-card animate-on-scroll delay-2">
              <div class="ic-header"><div class="icon-colorful icon-colorful--purple" style="font-size:1.5rem; margin-right:.75rem"><i class="fa-solid fa-user-graduate"></i></div> <h4>Post-Grad Job Search</h4></div>
              <div class="ic-body">
                <p style="color:var(--gray); font-size:0.95rem;">Graduates qualify for the <strong>D-10 Job Search Visa</strong> to explore careers and attend interviews locally.</p>
              </div>
            </div>
            <div class="info-card animate-on-scroll delay-3">
              <div class="ic-header"><div class="icon-colorful icon-colorful--green" style="font-size:1.5rem; margin-right:.75rem"><i class="fa-solid fa-id-badge"></i></div> <h4>Long-term Residency</h4></div>
              <div class="ic-body">
                <p style="color:var(--gray); font-size:0.95rem;">Successful professionals can transition directly toward the <strong>F-2 long-term residency system</strong> for stability.</p>
              </div>
            </div>
          </div>
        </div>
      </section>

      <!-- MODULE: FEES & FINANCES -->
      <section class="section" style="background:#f8fafc; border-top:1px solid #e2e8f0;">
        <div class="container">
          <div class="grid grid--2 gap--4">
            <div class="animate-on-scroll">
              <h3 style="font-size:1.5rem; color:#0f172a; margin-bottom:1.5rem; font-weight:800;"><i class="fa-solid fa-coins" style="color:var(--neon-blue); margin-right:0.5rem;"></i> Official 2026 Benchmarks</h3>
              <div class="stayback-container">
                <table class="stayback-table">
                    <thead>
                        <tr>
                            <th>Category</th>
                            <th>2026 Requirement (Approx.)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr><td style="font-weight:700;">Living Proof (Annual)</td><td>$9,000 – $10,000 (~₹7.5L-8.5L)</td></tr>
                        <tr><td style="font-weight:700;">Public Univ Tuition</td><td>₩2.4M – ₩5M /semester</td></tr>
                        <tr><td style="font-weight:700;">Private Univ Tuition</td><td>₩5M – ₩8M /semester</td></tr>
                        <tr><td style="font-weight:700;">ARC Fee</td><td>₩30,000 (~₹1,900)</td></tr>
                    </tbody>
                </table>
              </div>
            </div>
            <div class="animate-on-scroll delay-1">
              <h3 style="font-size:1.5rem; color:#0f172a; margin-bottom:1.5rem; font-weight:800;"><i class="fa-solid fa-circle-info" style="color:var(--neon-orange); margin-right:0.5rem;"></i> Expert Note</h3>
              <div class="highlight-box bg-dots" style="background: white; border: 1px solid #e2e8f0; border-radius: 20px; padding: 2rem;">
                <ul class="benefit-list">
                  <li><i class="fa-solid fa-check-circle text-success"></i> ARC issuance is mandatory within 90 days.</li>
                  <li><i class="fa-solid fa-check-circle text-success"></i> TOPIK L3+ improves visa flexibility and support.</li>
                  <li><i class="fa-solid fa-award text-primary"></i> <strong>Pro Tip:</strong> STEM students have the highest conversion rate to work visas!</li>
                  <li><i class="fa-solid fa-bolt text-primary"></i> Seoul offers high salaries but smaller cities offer lower costs.</li>
                </ul>
              </div>
            </div>
          </div>
        </div>
      </section>

      <!-- MODULE: SUCCESS TIMELINE -->
      <section class="section">
        <div class="container">
          <div class="section__header animate-on-scroll">
            <span class="section__tag">Application Strategy</span>
            <h2 class="section__title">Success <span>Timeline</span></h2>
            <div class="accent-bar"></div>
          </div>
          <div class="grid grid--2 gap--4 align-center">
             <div class="animate-on-scroll">
               <ul class="benefit-list">
                 <li><i class="fa-solid fa-circle-check" style="color:var(--neon-blue);"></i> March 2027 Intake: Apply Sep – Nov 2026</li>
                 <li><i class="fa-solid fa-circle-check" style="color:var(--neon-blue);"></i> Sept 2026 Intake: Apply May – June 2026</li>
                 <li><i class="fa-solid fa-circle-check" style="color:var(--neon-blue);"></i> Residence: ARC issuance within 90 days</li>
                 <li><i class="fa-solid fa-circle-check" style="color:var(--neon-blue);"></i> Job Search: D-10 visa post-graduation</li>
               </ul>
             </div>
             <div class="animate-on-scroll delay-1">
               <div class="info-card" style="background:var(--primary); color:white; border:none;">
                 <div class="ic-body">
                   <h4 style="color:white; margin-bottom:1rem;">Elite Korean Institutions</h4>
                   <p style="opacity:0.9;"><strong>Seoul National:</strong> #39 Globally, leader in Medicine & AI.</p>
                   <p style="opacity:0.9;"><strong>KAIST:</strong> Asia's top STEM institution for Robotics.</p>
                 </div>
               </div>
             </div>
          </div>
        </div>
      </section>

      <!-- MODULE: CLIMATE & FOOD -->
      <section class="section" style="background:#f8fafc; border-top:1px solid #e2e8f0;">
        <div class="container">
          <div class="grid grid--2 gap--4">
            <div class="animate-on-scroll">
              <h3 style="font-size:1.5rem; color:#0f172a; margin-bottom:1.5rem; font-weight:800;"><i class="fa-solid fa-mountain-sun" style="color:var(--neon-orange); margin-right:0.5rem;"></i> Seasonal Experience</h3>
              <ul class="benefit-list" style="margin-bottom: 2rem;">
                <li><i class="fa-solid fa-snowflake" style="color:var(--neon-blue);"></i> Cold winters (Dec–Feb) with snow in Seoul</li>
                <li><i class="fa-solid fa-sun" style="color:var(--neon-orange);"></i> Hot, humid summers (Jun–Aug)</li>
                <li><i class="fa-solid fa-leaf" style="color:var(--neon-green);"></i> Perfect comfort during Spring and Autumn seasons</li>
                <li><i class="fa-solid fa-plane-up" style="color:var(--neon-purple);"></i> Global hub access to Japan, China, and SE Asia</li>
              </ul>
            </div>
            <div class="animate-on-scroll delay-1">
              <h3 style="font-size:1.5rem; color:#0f172a; margin-bottom:1.5rem; font-weight:800;"><i class="fa-solid fa-bowl-rice" style="color:var(--neon-green); margin-right:0.5rem;"></i> Hallyu Gastronomy</h3>
              <p style="color:var(--gray); margin-bottom:1.5rem; line-height:1.6;">Iconic rice-based cuisine with strong global fusion:</p>
              <ul class="benefit-list">
                <li><i class="fa-solid fa-utensils" style="color:var(--neon-green);"></i> Kimchi, Bibimbap, Korean BBQ, and Ramyeon culture</li>
                <li><i class="fa-solid fa-mug-hot" style="color:var(--neon-green);"></i> Strong café culture and 24/7 convenience zones</li>
                <li><i class="fa-solid fa-leaf" style="color:var(--neon-green);"></i> Rapidly increasing vegetarian options in major cities</li>
                <li><i class="fa-solid fa-shield-heart" style="color:var(--neon-blue);"></i> High quality of life with world-class infrastructure</li>
              </ul>
            </div>
          </div>
        </div>
      </section>
      <?php elseif ($country_slug === 'georgia'): ?>

      <!-- MODULE: THE 2026 STRATEGIC ADVANTAGE -->
      <section class="section" style="background:#f8fafc; border-top:1px solid #e2e8f0;">
        <div class="container">
          <div class="section__header animate-on-scroll">
            <span class="section__tag">The Gold Standard for Medical Education</span>
            <h2 class="section__title">The 2026 <span>Strategic Advantage</span></h2>
            <p style="color:var(--gray); margin-top: 0.5rem;">"Georgia is the premier destination for Indian medical aspirants." Access European-standard MD programs fully aligned with the latest NMC guidelines.</p>
            <div class="accent-bar"></div>
          </div>
          <div class="grid grid--3 gap--4">
            <div class="pillar-card animate-on-scroll">
              <div class="pillar-num" style="background:rgba(139, 92, 246, 0.08); color:var(--neon-purple);"><i class="fa-solid fa-stethoscope"></i></div>
              <h4>NMC Compliance Mastery</h4>
              <p>5.8–6 year MD programs fully aligned with NMC 2021 Gazette. 54 months academic + 12 months clinical internship structure.</p>
            </div>
            <div class="pillar-card animate-on-scroll delay-1">
              <div class="pillar-num" style="background:rgba(16, 185, 129, 0.08); color:var(--neon-green);"><i class="fa-solid fa-shield-halved"></i></div>
              <h4>Global Safety Lead</h4>
              <p>Ranked among the Top 10 safest countries globally. Study in a secure, welcoming environment with a massive Indian student ecosystem.</p>
            </div>
            <div class="pillar-card animate-on-scroll delay-2">
              <div class="pillar-num" style="background:rgba(245, 158, 11, 0.08); color:var(--neon-orange);"><i class="fa-solid fa-earth-europe"></i></div>
              <h4>European PG Mobility</h4>
              <p>ECTS-based curriculum enabling seamless PG pathways in Germany, UK, and USA. Degrees listed in WDOMS and recognized by WHO.</p>
            </div>
          </div>
        </div>
      </section>

      <!-- MODULE: COMPLIANCE & CLINICAL BLUEPRINT -->
      <section class="section">
        <div class="container">
          <div class="section__header animate-on-scroll">
            <span class="section__tag">The 2026 Georgia Medical Path</span>
            <h2 class="section__title">The “India-Valid” <span>Medical Blueprint</span></h2>
            <div class="accent-bar"></div>
          </div>
          <div class="grid grid--2 gap--4">
            <div class="info-card animate-on-scroll">
              <div class="ic-header"><div class="icon-colorful icon-colorful--blue" style="font-size:1.5rem; margin-right:.75rem"><i class="fa-solid fa-book-medical"></i></div> <h4>NMC 2021 Alignment</h4></div>
              <div class="ic-body">
                <ul class="benefit-list" style="margin-top: 1rem;">
                  <li><i class="fa-solid fa-check-circle text-success"></i> Full verification of 54+12 month program structure</li>
                  <li><i class="fa-solid fa-graduation-cap text-success"></i> High FMGE/NExT success focus across top universities</li>
                </ul>
              </div>
            </div>
            <div class="info-card animate-on-scroll delay-1">
              <div class="ic-header"><div class="icon-colorful icon-colorful--orange" style="font-size:1.5rem; margin-right:.75rem"><i class="fa-solid fa-hospital-user"></i></div> <h4>Clinical Rotation Mapping</h4></div>
              <div class="ic-body">
                <ul class="benefit-list" style="margin-top: 1rem;">
                  <li><i class="fa-solid fa-check-circle text-success"></i> Hands-on training in Georgia's leading government hospitals</li>
                  <li><i class="fa-solid fa-microscope text-success"></i> Modern diagnostic and research infrastructure access</li>
                </ul>
              </div>
            </div>
            <div class="info-card animate-on-scroll delay-2">
              <div class="ic-header"><div class="icon-colorful icon-colorful--purple" style="font-size:1.5rem; margin-right:.75rem"><i class="fa-solid fa-id-card"></i></div> <h4>Residence Permit</h4></div>
              <div class="ic-body">
                <p style="color:var(--gray); font-size:0.95rem;">Mandatory residence permit required for the full duration of stay and clinical training. Bluestone provides end-to-end legal support.</p>
              </div>
            </div>
            <div class="info-card animate-on-scroll delay-3">
              <div class="ic-header"><div class="icon-colorful icon-colorful--green" style="font-size:1.5rem; margin-right:.75rem"><i class="fa-solid fa-user-doctor"></i></div> <h4>FMGE/NExT Strategy</h4></div>
              <div class="ic-body">
                <p style="color:var(--gray); font-size:0.95rem;">Strategic focus on Indian medical licensing pathways. Universities selected based on historical <strong>FMGE success rates</strong>.</p>
              </div>
            </div>
          </div>
        </div>
      </section>

      <!-- MODULE: FEES & FINANCES -->
      <section class="section" style="background:#f8fafc; border-top:1px solid #e2e8f0;">
        <div class="container">
          <div class="grid grid--2 gap--4">
            <div class="animate-on-scroll">
              <h3 style="font-size:1.5rem; color:#0f172a; margin-bottom:1.5rem; font-weight:800;"><i class="fa-solid fa-wallet" style="color:var(--neon-blue); margin-right:0.5rem;"></i> Official 2026 Benchmarks (USD)</h3>
              <div class="stayback-container">
                <table class="stayback-table">
                    <thead>
                        <tr>
                            <th>Category</th>
                            <th>2026 Requirement (Approx.)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr><td style="font-weight:700;">Annual Tuition</td><td>$4,800 – $8,500 (~₹4.5L-7.9L)</td></tr>
                        <tr><td style="font-weight:700;">Hostel & Mess (Yearly)</td><td>$3,200 – $4,800 (~₹2.9L-4.4L)</td></tr>
                        <tr><td style="font-weight:700;">Proof of Funds</td><td>$5,000 (~₹4.5L approx.)</td></tr>
                        <tr><td style="font-weight:700;">Visa & Invitation</td><td>₹12,000 – ₹15,000</td></tr>
                    </tbody>
                </table>
              </div>
            </div>
            <div class="animate-on-scroll delay-1">
              <h3 style="font-size:1.5rem; color:#0f172a; margin-bottom:1.5rem; font-weight:800;"><i class="fa-solid fa-circle-info" style="color:var(--neon-orange); margin-right:0.5rem;"></i> Expert Note</h3>
              <div class="highlight-box bg-dots" style="background: white; border: 1px solid #e2e8f0; border-radius: 20px; padding: 2rem;">
                <ul class="benefit-list">
                  <li><i class="fa-solid fa-check-circle text-success"></i> 6-Year MBBS Total: ₹28L – ₹42L</li>
                  <li><i class="fa-solid fa-check-circle text-success"></i> Mandatory 30,000 GEL insurance coverage.</li>
                  <li><i class="fa-solid fa-award text-primary"></i> <strong>Pro Tip:</strong> Only NMC-approved universities ensure licensing in India!</li>
                  <li><i class="fa-solid fa-bolt text-primary"></i> Invitation letter processing takes 30-45 days.</li>
                </ul>
              </div>
            </div>
          </div>
        </div>
      </section>

      <!-- MODULE: SUCCESS TIMELINE -->
      <section class="section">
        <div class="container">
          <div class="section__header animate-on-scroll">
            <span class="section__tag">Application Strategy</span>
            <h2 class="section__title">Success <span>Timeline</span></h2>
            <div class="accent-bar"></div>
          </div>
          <div class="grid grid--2 gap--4 align-center">
             <div class="animate-on-scroll">
               <ul class="benefit-list">
                 <li><i class="fa-solid fa-circle-check" style="color:var(--neon-blue);"></i> Sept 2026 Intake: Apply Mar – July 2026</li>
                 <li><i class="fa-solid fa-circle-check" style="color:var(--neon-blue);"></i> Feb 2027 Intake: Limited secondary cycle</li>
                 <li><i class="fa-solid fa-circle-check" style="color:var(--neon-blue);"></i> Visa Duration: 2–4 weeks processing</li>
                 <li><i class="fa-solid fa-circle-check" style="color:var(--neon-blue);"></i> Travel: August–September for main intake</li>
               </ul>
             </div>
             <div class="animate-on-scroll delay-1">
               <div class="info-card" style="background:var(--primary); color:white; border:none;">
                 <div class="ic-body">
                   <h4 style="color:white; margin-bottom:1rem;">Top Georgian Rankings</h4>
                   <p style="opacity:0.9;"><strong>TSMU:</strong> Georgia's #1 medical hub for international students.</p>
                   <p style="opacity:0.9;"><strong>Ivane Javakhishvili:</strong> #651 globally for research excellence.</p>
                 </div>
               </div>
             </div>
          </div>
        </div>
      </section>

      <?php elseif ($country_slug === 'croatia'): ?>

      <!-- MODULE: THE 2026 STRATEGIC ADVANTAGE -->
      <section class="section" style="background:#f8fafc; border-top:1px solid #e2e8f0;">
        <div class="container">
          <div class="section__header animate-on-scroll">
            <span class="section__tag">The Adriatic Gateway to Europe</span>
            <h2 class="section__title">The 2026 <span>Strategic Advantage</span></h2>
            <p style="color:var(--gray); margin-top: 0.5rem;">"Croatia is the future of European innovation." Access elite historic universities with full EU + Schengen mobility and unmatched post-study work rights.</p>
            <div class="accent-bar"></div>
          </div>
          <div class="grid grid--3 gap--4">
            <div class="pillar-card animate-on-scroll">
              <div class="pillar-num" style="background:rgba(139, 92, 246, 0.08); color:var(--neon-purple);"><i class="fa-solid fa-earth-europe"></i></div>
              <h4>Schengen Mobility Advantage</h4>
              <p>Full EU + Schengen membership. Your residence permit enables visa-free travel across 29 European countries for networking and exploration.</p>
            </div>
            <div class="pillar-card animate-on-scroll delay-1">
              <div class="pillar-num" style="background:rgba(16, 185, 129, 0.08); color:var(--neon-green);"><i class="fa-solid fa-id-card"></i></div>
              <h4>Nostrification Mastery</h4>
              <p>Strategic focus on degree recognition (Nostrification) ensuring your eligibility aligns with global standards for professional practice.</p>
            </div>
            <div class="pillar-card animate-on-scroll delay-2">
              <div class="pillar-num" style="background:rgba(245, 158, 11, 0.08); color:var(--neon-orange);"><i class="fa-solid fa-briefcase"></i></div>
              <h4>12-Month Stay-back</h4>
              <p>Generous post-study residence permit for job searching. Seamless transition to a work and residence permit after securing employment.</p>
            </div>
          </div>
        </div>
      </section>

      <!-- MODULE: STUDENT SERVICE & CAREER BLUEPRINT -->
      <section class="section">
        <div class="container">
          <div class="section__header animate-on-scroll">
            <span class="section__tag">The 2026 Croatia Work & Residence Path</span>
            <h2 class="section__title">The “Student Service” <span>& Career Path</span></h2>
            <div class="accent-bar"></div>
          </div>
          <div class="grid grid--2 gap--4">
            <div class="info-card animate-on-scroll">
              <div class="ic-header"><div class="icon-colorful icon-colorful--blue" style="font-size:1.5rem; margin-right:.75rem"><i class="fa-solid fa-users-gear"></i></div> <h4>Student Service System</h4></div>
              <div class="ic-body">
                <ul class="benefit-list" style="margin-top: 1rem;">
                  <li><i class="fa-solid fa-check-circle text-success"></i> Government-run platform connecting students with part-time jobs</li>
                  <li><i class="fa-solid fa-briefcase text-success"></i> Temporary jobs perfectly aligned with your academic schedule</li>
                </ul>
              </div>
            </div>
            <div class="info-card animate-on-scroll delay-1">
              <div class="ic-header"><div class="icon-colorful icon-colorful--orange" style="font-size:1.5rem; margin-right:.75rem"><i class="fa-solid fa-city"></i></div> <h4>Tourism & IT Hub</h4></div>
              <div class="ic-body">
                <ul class="benefit-list" style="margin-top: 1rem;">
                  <li><i class="fa-solid fa-check-circle text-success"></i> Massive demand in Tourism (Split, Dubrovnik) and IT (Zagreb)</li>
                  <li><i class="fa-solid fa-chart-line text-success"></i> Strong pipelines into EU-funded research and business sectors</li>
                </ul>
              </div>
            </div>
            <div class="info-card animate-on-scroll delay-2">
              <div class="ic-header"><div class="icon-colorful icon-colorful--purple" style="font-size:1.5rem; margin-right:.75rem"><i class="fa-solid fa-landmark"></i></div> <h4>Elite Heritage</h4></div>
              <div class="ic-body">
                <p style="color:var(--gray); font-size:0.95rem;">Home to the <strong>University of Zagreb</strong> (#701 globally), one of Europe’s oldest and most prestigious research universities.</p>
              </div>
            </div>
            <div class="info-card animate-on-scroll delay-3">
              <div class="ic-header"><div class="icon-colorful icon-colorful--green" style="font-size:1.5rem; margin-right:.75rem"><i class="fa-solid fa-shield-halved"></i></div> <h4>Legal Recognition</h4></div>
              <div class="ic-body">
                <p style="color:var(--gray); font-size:0.95rem;">Strategic 2026 focus on <strong>Nostrification</strong> ensures your degree is legally recognized across the entire EU area.</p>
              </div>
            </div>
          </div>
        </div>
      </section>

      <!-- MODULE: FEES & FINANCES -->
      <section class="section" style="background:#f8fafc; border-top:1px solid #e2e8f0;">
        <div class="container">
          <div class="grid grid--2 gap--4">
            <div class="animate-on-scroll">
              <h3 style="font-size:1.5rem; color:#0f172a; margin-bottom:1.5rem; font-weight:800;"><i class="fa-solid fa-coins" style="color:var(--neon-blue); margin-right:0.5rem;"></i> Official 2026 Benchmarks (Euro)</h3>
              <div class="stayback-container">
                <table class="stayback-table">
                    <thead>
                        <tr>
                            <th>Category</th>
                            <th>2026 Requirement (Approx.)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr><td style="font-weight:700;">Living Cost (Monthly)</td><td>€400 – €800 (~₹36k-72k)</td></tr>
                        <tr><td style="font-weight:700;">Public Univ Tuition</td><td>€1,100 – €7,000 /yr</td></tr>
                        <tr><td style="font-weight:700;">Medicine Programs</td><td>€6,000 – €12,000 /yr</td></tr>
                        <tr><td style="font-weight:700;">Visa (D-Type) Fee</td><td>€93 (~₹8,400)</td></tr>
                    </tbody>
                </table>
              </div>
            </div>
            <div class="animate-on-scroll delay-1">
              <h3 style="font-size:1.5rem; color:#0f172a; margin-bottom:1.5rem; font-weight:800;"><i class="fa-solid fa-circle-info" style="color:var(--neon-orange); margin-right:0.5rem;"></i> Expert Note</h3>
              <div class="highlight-box bg-dots" style="background: white; border: 1px solid #e2e8f0; border-radius: 20px; padding: 2rem;">
                <ul class="benefit-list">
                  <li><i class="fa-solid fa-check-circle text-success"></i> Maintenance: €4,800 – €9,600 /year</li>
                  <li><i class="fa-solid fa-check-circle text-success"></i> Mandatory €30,000 travel insurance coverage.</li>
                  <li><i class="fa-solid fa-award text-primary"></i> <strong>Pro Tip:</strong> Start Nostrification 3–6 months before admission!</li>
                  <li><i class="fa-solid fa-bolt text-primary"></i> Public universities offer high-quality education at a fraction of the cost.</li>
                </ul>
              </div>
            </div>
          </div>
        </div>
      </section>

      <!-- MODULE: SUCCESS TIMELINE -->
      <section class="section">
        <div class="container">
          <div class="section__header animate-on-scroll">
            <span class="section__tag">Application Strategy</span>
            <h2 class="section__title">Success <span>Timeline</span></h2>
            <div class="accent-bar"></div>
          </div>
          <div class="grid grid--2 gap--4 align-center">
             <div class="animate-on-scroll">
               <ul class="benefit-list">
                 <li><i class="fa-solid fa-circle-check" style="color:var(--neon-blue);"></i> October 2026 Intake: Apply Mar – June 2026</li>
                 <li><i class="fa-solid fa-circle-check" style="color:var(--neon-blue);"></i> Nostrification: Start 6 months early</li>
                 <li><i class="fa-solid fa-circle-check" style="color:var(--neon-blue);"></i> Visa Duration: 4–8 weeks processing</li>
                 <li><i class="fa-solid fa-circle-check" style="color:var(--neon-blue);"></i> March 2027 Intake: Limited secondary cycle</li>
               </ul>
             </div>
             <div class="animate-on-scroll delay-1">
               <div class="info-card" style="background:var(--primary); color:white; border:none;">
                 <div class="ic-body">
                   <h4 style="color:white; margin-bottom:1rem;">Elite Croatian Hubs</h4>
                   <p style="opacity:0.9;"><strong>Zagreb:</strong> Home to Croatia's oldest and most prestigious university.</p>
                   <p style="opacity:0.9;"><strong>RIT Croatia:</strong> Top US-affiliated institution for IT & Business.</p>
                 </div>
               </div>
             </div>
          </div>
        </div>
      </section>

      <!-- MODULE: CLIMATE & FOOD -->
      <section class="section" style="background:#f8fafc; border-top:1px solid #e2e8f0;">
        <div class="container">
          <div class="grid grid--2 gap--4">
            <div class="animate-on-scroll">
              <h3 style="font-size:1.5rem; color:#0f172a; margin-bottom:1.5rem; font-weight:800;"><i class="fa-solid fa-cloud-sun" style="color:var(--neon-blue); margin-right:0.5rem;"></i> Seasonal Experience</h3>
              <ul class="benefit-list" style="margin-bottom: 2rem;">
                <li><i class="fa-solid fa-sun" style="color:var(--neon-orange);"></i> Hot Mediterranean summers (coastal Split/Dubrovnik)</li>
                <li><i class="fa-solid fa-snowflake" style="color:var(--neon-blue);"></i> Cold continental winters (inland Zagreb)</li>
                <li><i class="fa-solid fa-leaf" style="color:var(--neon-green);"></i> Mild, beautiful Spring and Autumn seasons</li>
                <li><i class="fa-solid fa-earth-europe" style="color:var(--neon-purple);"></i> Travel-free access to 29 Schengen countries</li>
              </ul>
            </div>
            <div class="animate-on-scroll delay-1">
              <h3 style="font-size:1.5rem; color:#0f172a; margin-bottom:1.5rem; font-weight:800;"><i class="fa-solid fa-utensils" style="color:var(--neon-green); margin-right:0.5rem;"></i> Adriatic Gastronomy</h3>
              <p style="color:var(--gray); margin-bottom:1.5rem; line-height:1.6;">A blend of Mediterranean freshness and European bakery culture:</p>
              <ul class="benefit-list">
                <li><i class="fa-solid fa-fish" style="color:var(--neon-green);"></i> Fresh seafood, olive oil, and iconic coastal produce</li>
                <li><i class="fa-solid fa-pizza-slice" style="color:var(--neon-green);"></i> Historic European bakery and coffee culture</li>
                <li><i class="fa-solid fa-leaf" style="color:var(--neon-green);"></i> Growing number of vegetarian and Asian options</li>
                <li><i class="fa-solid fa-shield-heart" style="color:var(--neon-blue);"></i> High-quality life with safe, vibrant urban centers</li>
              </ul>
            </div>
          </div>
        </div>
      </section>
      <?php elseif ($country_slug === 'latvia'): ?>

      <!-- MODULE: THE 2026 STRATEGIC ADVANTAGE -->
      <section class="section" style="background:#f8fafc; border-top:1px solid #e2e8f0;">
        <div class="container">
          <div class="section__header animate-on-scroll">
            <span class="section__tag">The Baltic Tech Corridor</span>
            <h2 class="section__title">The 2026 <span>Strategic Advantage</span></h2>
            <p style="color:var(--gray); margin-top: 0.5rem;">"Latvia is the most affordable gateway to EU tech careers." Access world-class engineering and IT programs with full Schengen mobility.</p>
            <div class="accent-bar"></div>
          </div>
          <div class="grid grid--3 gap--4">
            <div class="pillar-card animate-on-scroll">
              <div class="pillar-num" style="background:rgba(139, 92, 246, 0.08); color:var(--neon-purple);"><i class="fa-solid fa-microchip"></i></div>
              <h4>Tech Entry Strategy</h4>
              <p>Low-cost entry into Schengen higher education with a strong pipeline into Nordic and Western Europe job markets. Tuition starts from just €1,600/year.</p>
            </div>
            <div class="pillar-card animate-on-scroll delay-1">
              <div class="pillar-num" style="background:rgba(16, 185, 129, 0.08); color:var(--neon-green);"><i class="fa-solid fa-shield-check"></i></div>
              <h4>AIC Verification Mastery</h4>
              <p>Strategic expertise in AIC academic validation and OCMA invitation coordination. We ensure 100% compliance with Latvia's strict admission system.</p>
            </div>
            <div class="pillar-card animate-on-scroll delay-2">
              <div class="pillar-num" style="background:rgba(245, 158, 11, 0.08); color:var(--neon-orange);"><i class="fa-solid fa-plane-departure"></i></div>
              <h4>Aviation & Logistics Hub</h4>
              <p>Global leader in transport and telecommunication studies. Direct career links into the Baltic aviation industry and EU logistics sectors.</p>
            </div>
          </div>
        </div>
      </section>

      <!-- MODULE: COMPLIANCE & CAREER BLUEPRINT -->
      <section class="section">
        <div class="container">
          <div class="section__header animate-on-scroll">
            <span class="section__tag">The 2026 Latvia Career Path</span>
            <h2 class="section__title">The “Academic Validation” <span>System</span></h2>
            <div class="accent-bar"></div>
          </div>
          <div class="grid grid--2 gap--4">
            <div class="info-card animate-on-scroll">
              <div class="ic-header"><div class="icon-colorful icon-colorful--blue" style="font-size:1.5rem; margin-right:.75rem"><i class="fa-solid fa-file-shield"></i></div> <h4>AIC & OCMA Coordination</h4></div>
              <div class="ic-body">
                <ul class="benefit-list" style="margin-top: 1rem;">
                  <li><i class="fa-solid fa-check-circle text-success"></i> Mandatory AIC verification for all foreign qualifications</li>
                  <li><i class="fa-solid fa-handshake text-success"></i> University admission proceeds only after OCMA Invitation Number approval</li>
                </ul>
              </div>
            </div>
            <div class="info-card animate-on-scroll delay-1">
              <div class="ic-header"><div class="icon-colorful icon-colorful--orange" style="font-size:1.5rem; margin-right:.75rem"><i class="fa-solid fa-briefcase"></i></div> <h4>EU-Aligned Mobility</h4></div>
              <div class="ic-body">
                <ul class="benefit-list" style="margin-top: 1rem;">
                  <li><i class="fa-solid fa-check-circle text-success"></i> 9-month post-study job search permit for Master’s/PhD</li>
                  <li><i class="fa-solid fa-globe text-success"></i> Conversion to TRP after job offer with EU-wide work mobility</li>
                </ul>
              </div>
            </div>
            <div class="info-card animate-on-scroll delay-2">
              <div class="ic-header"><div class="icon-colorful icon-colorful--purple" style="font-size:1.5rem; margin-right:.75rem"><i class="fa-solid fa-user-gear"></i></div> <h4>Skilled Sectors</h4></div>
              <div class="ic-body">
                <p style="color:var(--gray); font-size:0.95rem;">High demand in <strong>IT, Software Development, and Engineering</strong>. Strong exposure to the Nordic manufacturing ecosystem.</p>
              </div>
            </div>
            <div class="info-card animate-on-scroll delay-3">
              <div class="ic-header"><div class="icon-colorful icon-colorful--green" style="font-size:1.5rem; margin-right:.75rem"><i class="fa-solid fa-id-card-clip"></i></div> <h4>PR Eligibility</h4></div>
              <div class="ic-body">
                <p style="color:var(--gray); font-size:0.95rem;">Graduates can apply for <strong>Permanent Residency</strong> after 5 years of continuous residence and meeting basic requirements.</p>
              </div>
            </div>
          </div>
        </div>
      </section>

      <!-- MODULE: FEES & FINANCES -->
      <section class="section" style="background:#f8fafc; border-top:1px solid #e2e8f0;">
        <div class="container">
          <div class="grid grid--2 gap--4">
            <div class="animate-on-scroll">
              <h3 style="font-size:1.5rem; color:#0f172a; margin-bottom:1.5rem; font-weight:800;"><i class="fa-solid fa-wallet" style="color:var(--neon-blue); margin-right:0.5rem;"></i> Official 2026 Benchmarks (Euro)</h3>
              <div class="stayback-container">
                <table class="stayback-table">
                    <thead>
                        <tr>
                            <th>Category</th>
                            <th>2026 Requirement (Approx.)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr><td style="font-weight:700;">Living Cost (Monthly)</td><td>€780 (~₹70k/month)</td></tr>
                        <tr><td style="font-weight:700;">Engineering Tuition</td><td>€2,700 – €3,500 /yr</td></tr>
                        <tr><td style="font-weight:700;">Bachelor’s Tuition</td><td>€1,600 – €4,000 /yr</td></tr>
                        <tr><td style="font-weight:700;">D-Visa Fee</td><td>€90 (~₹8,100)</td></tr>
                    </tbody>
                </table>
              </div>
            </div>
            <div class="animate-on-scroll delay-1">
              <h3 style="font-size:1.5rem; color:#0f172a; margin-bottom:1.5rem; font-weight:800;"><i class="fa-solid fa-circle-info" style="color:var(--neon-orange); margin-right:0.5rem;"></i> Expert Note</h3>
              <div class="highlight-box bg-dots" style="background: white; border: 1px solid #e2e8f0; border-radius: 20px; padding: 2rem;">
                <ul class="benefit-list">
                  <li><i class="fa-solid fa-check-circle text-success"></i> Annual Maintenance: €9,360</li>
                  <li><i class="fa-solid fa-check-circle text-success"></i> Work: 20 hrs/week during study.</li>
                  <li><i class="fa-solid fa-award text-primary"></i> <strong>Pro Tip:</strong> Master’s students get a 9-month job search window!</li>
                  <li><i class="fa-solid fa-bolt text-primary"></i> Riga Technical University is a Top #761 engineering global choice.</li>
                </ul>
              </div>
            </div>
          </div>
        </div>
      </section>

      <!-- MODULE: SUCCESS TIMELINE -->
      <section class="section">
        <div class="container">
          <div class="section__header animate-on-scroll">
            <span class="section__tag">Application Strategy</span>
            <h2 class="section__title">Success <span>Timeline</span></h2>
            <div class="accent-bar"></div>
          </div>
          <div class="grid grid--2 gap--4 align-center">
             <div class="animate-on-scroll">
               <ul class="benefit-list">
                 <li><i class="fa-solid fa-circle-check" style="color:var(--neon-blue);"></i> Sept 2026 Intake: Apply Mar – June 2026</li>
                 <li><i class="fa-solid fa-circle-check" style="color:var(--neon-blue);"></i> AIC Verification: Takes 2–4 weeks</li>
                 <li><i class="fa-solid fa-circle-check" style="color:var(--neon-blue);"></i> OCMA Invitation: Post-AIC approval</li>
                 <li><i class="fa-solid fa-circle-check" style="color:var(--neon-blue);"></i> Visa Duration: 4–8 weeks processing</li>
               </ul>
             </div>
             <div class="animate-on-scroll delay-1">
               <div class="info-card" style="background:var(--primary); color:white; border:none;">
                 <div class="ic-body">
                   <h4 style="color:white; margin-bottom:1rem;">Top Latvian Institutions</h4>
                   <p style="opacity:0.9;"><strong>RTU:</strong> #761 Engineering & IT powerhouse.</p>
                   <p style="opacity:0.9;"><strong>Uni of Latvia:</strong> #801 Globally for Science & Medicine.</p>
                 </div>
               </div>
             </div>
          </div>
        </div>
      </section>

      <?php elseif ($country_slug === 'luxembourg'): ?>

      <!-- MODULE: THE 2026 STRATEGIC ADVANTAGE -->
      <section class="section" style="background:#f8fafc; border-top:1px solid #e2e8f0;">
        <div class="container">
          <div class="section__header animate-on-scroll">
            <span class="section__tag">Elite Wealth & Innovation</span>
            <h2 class="section__title">The 2026 <span>Strategic Advantage</span></h2>
            <p style="color:var(--gray); margin-top: 0.5rem;">"Study in the world’s richest nation." Access the administrative heart of the EU with unrivaled global career pipelines in Finance and Law.</p>
            <div class="accent-bar"></div>
          </div>
          <div class="grid grid--3 gap--4">
            <div class="pillar-card animate-on-scroll">
              <div class="pillar-num" style="background:rgba(139, 92, 246, 0.08); color:var(--neon-purple);"><i class="fa-solid fa-piggy-bank"></i></div>
              <h4>Financial Engine Access</h4>
              <p>Direct entry into a global financial powerhouse with Amazon EU HQ and 120+ global banks. Highest income potential in Europe for skilled graduates.</p>
            </div>
            <div class="pillar-card animate-on-scroll delay-1">
              <div class="pillar-num" style="background:rgba(16, 185, 129, 0.08); color:var(--neon-green);"><i class="fa-solid fa-train-subway"></i></div>
              <h4>100% Free Mobility</h4>
              <p>World's first country with 100% free public transport (trains, trams, buses) for everyone, drastically reducing student living expenses.</p>
            </div>
            <div class="pillar-card animate-on-scroll delay-2">
              <div class="pillar-num" style="background:rgba(245, 158, 11, 0.08); color:var(--neon-orange);"><i class="fa-solid fa-building-columns"></i></div>
              <h4>EU Administrative Heart</h4>
              <p>Unique proximity to European Union institutions and administrative bodies, providing exclusive networking for Law and Policy students.</p>
            </div>
          </div>
        </div>
      </section>

      <!-- MODULE: PROFESSIONAL INTEGRATION BLUEPRINT -->
      <section class="section">
        <div class="container">
          <div class="section__header animate-on-scroll">
            <span class="section__tag">The 2026 Luxembourg Career Path</span>
            <h2 class="section__title">The “Professional <span>Integration” System</span></h2>
            <div class="accent-bar"></div>
          </div>
          <div class="grid grid--2 gap--4">
            <div class="info-card animate-on-scroll">
              <div class="ic-header"><div class="icon-colorful icon-colorful--blue" style="font-size:1.5rem; margin-right:.75rem"><i class="fa-solid fa-handshake"></i></div> <h4>Corporate Pipelines</h4></div>
              <div class="ic-body">
                <ul class="benefit-list" style="margin-top: 1rem;">
                  <li><i class="fa-solid fa-check-circle text-success"></i> Direct access to EU institutions and multinational headquarters</li>
                  <li><i class="fa-solid fa-language text-success"></i> Free French/German language modules for employability boost</li>
                </ul>
              </div>
            </div>
            <div class="info-card animate-on-scroll delay-1">
              <div class="ic-header"><div class="icon-colorful icon-colorful--orange" style="font-size:1.5rem; margin-right:.75rem"><i class="fa-solid fa-clock"></i></div> <h4>Enhanced Work Rights</h4></div>
              <div class="ic-body">
                <ul class="benefit-list" style="margin-top: 1rem;">
                  <li><i class="fa-solid fa-check-circle text-success"></i> 15 hours/week during term, 40 hours/week during holidays</li>
                  <li><i class="fa-solid fa-coins text-success"></i> €15–€20/hour minimum social wage for student roles</li>
                </ul>
              </div>
            </div>
            <div class="info-card animate-on-scroll delay-2">
              <div class="ic-header"><div class="icon-colorful icon-colorful--purple" style="font-size:1.5rem; margin-right:.75rem"><i class="fa-solid fa-magnifying-glass-chart"></i></div> <h4>Search Year Permit</h4></div>
              <div class="ic-body">
                <p style="color:var(--gray); font-size:0.95rem;">9-month post-study job seeker permit for all graduates to secure high-tier professional roles within the country.</p>
              </div>
            </div>
            <div class="info-card animate-on-scroll delay-3">
              <div class="ic-header"><div class="icon-colorful icon-colorful--green" style="font-size:1.5rem; margin-right:.75rem"><i class="fa-solid fa-user-shield"></i></div> <h4>Permanent Residency</h4></div>
              <div class="ic-body">
                <p style="color:var(--gray); font-size:0.95rem;">Path to PR after 5 years of legal residence + achieving A2 Luxembourgish language proficiency.</p>
              </div>
            </div>
          </div>
        </div>
      </section>

      <!-- MODULE: FEES & FINANCES -->
      <section class="section" style="background:#f8fafc; border-top:1px solid #e2e8f0;">
        <div class="container">
          <div class="grid grid--2 gap--4">
            <div class="animate-on-scroll">
              <h3 style="font-size:1.5rem; color:#0f172a; margin-bottom:1.5rem; font-weight:800;"><i class="fa-solid fa-coins" style="color:var(--neon-blue); margin-right:0.5rem;"></i> Official 2026 Benchmarks (Euro)</h3>
              <div class="stayback-container">
                <table class="stayback-table">
                    <thead>
                        <tr>
                            <th>Category</th>
                            <th>2026 Requirement (Approx.)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr><td style="font-weight:700;">Monthly Living Funds</td><td>€1,517 (~₹1.36 Lakhs)</td></tr>
                        <tr><td style="font-weight:700;">Public Univ Tuition</td><td>€400 – €800 /semester</td></tr>
                        <tr><td style="font-weight:700;">Master’s Tuition</td><td>€800 – €4,000 /year</td></tr>
                        <tr><td style="font-weight:700;">D-Type Visa Fee</td><td>€50 (~₹4,500)</td></tr>
                    </tbody>
                </table>
              </div>
            </div>
            <div class="animate-on-scroll delay-1">
              <h3 style="font-size:1.5rem; color:#0f172a; margin-bottom:1.5rem; font-weight:800;"><i class="fa-solid fa-circle-info" style="color:var(--neon-orange); margin-right:0.5rem;"></i> Expert Note</h3>
              <div class="highlight-box bg-dots" style="background: white; border: 1px solid #e2e8f0; border-radius: 20px; padding: 2rem;">
                <ul class="benefit-list">
                  <li><i class="fa-solid fa-check-circle text-success"></i> Annual Maintenance: €18,211</li>
                  <li><i class="fa-solid fa-check-circle text-success"></i> Transport is 100% Free country-wide.</li>
                  <li><i class="fa-solid fa-award text-primary"></i> <strong>Pro Tip:</strong> Uni of Luxembourg is ranked in the Top 1% for international outlook!</li>
                  <li><i class="fa-solid fa-bolt text-primary"></i> High living costs are offset by extreme income potential and social benefits.</li>
                </ul>
              </div>
            </div>
          </div>
        </div>
      </section>

      <!-- MODULE: CLIMATE & FOOD -->
      <section class="section">
        <div class="container">
          <div class="grid grid--2 gap--4">
            <div class="animate-on-scroll">
              <h3 style="font-size:1.5rem; color:#0f172a; margin-bottom:1.5rem; font-weight:800;"><i class="fa-solid fa-cloud-sun" style="color:var(--neon-orange); margin-right:0.5rem;"></i> Seasonal Experience</h3>
              <ul class="benefit-list" style="margin-bottom: 2rem;">
                <li><i class="fa-solid fa-snowflake" style="color:var(--neon-blue);"></i> Mild continental winters with frequent rain</li>
                <li><i class="fa-solid fa-sun" style="color:var(--neon-orange);"></i> Pleasant, vibrant European summers</li>
                <li><i class="fa-solid fa-mountain" style="color:var(--neon-green);"></i> Beautiful forested regions and historic castles</li>
                <li><i class="fa-solid fa-train" style="color:var(--neon-purple);"></i> Weekend travel to France, Germany & Belgium in hours</li>
              </ul>
            </div>
            <div class="animate-on-scroll delay-1">
              <h3 style="font-size:1.5rem; color:#0f172a; margin-bottom:1.5rem; font-weight:800;"><i class="fa-solid fa-bowl-food" style="color:var(--neon-green); margin-right:0.5rem;"></i> Global Gastronomy</h3>
              <p style="color:var(--gray); margin-bottom:1.5rem; line-height:1.6;">A melting pot of French, German, and Italian culinary influences:</p>
              <ul class="benefit-list">
                <li><i class="fa-solid fa-utensils" style="color:var(--neon-green);"></i> Michelin-standard dining and high-quality local produce</li>
                <li><i class="fa-solid fa-mug-hot" style="color:var(--neon-green);"></i> Sophisticated café and international fusion culture</li>
                <li><i class="fa-solid fa-leaf" style="color:var(--neon-green);"></i> Excellent vegetarian options in Luxembourg City</li>
                <li><i class="fa-solid fa-shield-heart" style="color:var(--neon-blue);"></i> Safest urban environment with premium healthcare</li>
              </ul>
            </div>
          </div>
        </div>
      </section>
      <?php elseif ($country_slug === 'greece'): ?>

      <!-- MODULE: THE 2026 STRATEGIC ADVANTAGE -->
      <section class="section" style="background:#f8fafc; border-top:1px solid #e2e8f0;">
        <div class="container">
          <div class="section__header animate-on-scroll">
            <span class="section__tag">Mediterranean Lifestyle & EU Talent</span>
            <h2 class="section__title">The 2026 <span>Strategic Advantage</span></h2>
            <p style="color:var(--gray); margin-top: 0.5rem;">"Greece is the new frontier of European talent." Access the birthplace of academia with modern high-tech pathways and a clear EU Blue Card roadmap.</p>
            <div class="accent-bar"></div>
          </div>
          <div class="grid grid--3 gap--4">
            <div class="pillar-card animate-on-scroll">
              <div class="pillar-num" style="background:rgba(139, 92, 246, 0.08); color:var(--neon-purple);"><i class="fa-solid fa-id-card"></i></div>
              <h4>2026 Talent Visa</h4>
              <p>New Talent Entry permit allows Master’s graduates to enter Greece and search for work. Strategic integration into the EU labor market via Greece’s major 2026 reforms.</p>
            </div>
            <div class="pillar-card animate-on-scroll delay-1">
              <div class="pillar-num" style="background:rgba(16, 185, 129, 0.08); color:var(--neon-green);"><i class="fa-solid fa-earth-europe"></i></div>
              <h4>Blue Card Conversion</h4>
              <p>Seamless transition to an EU Blue Card after just a 6-month job contract. Type H.11 residence permit provides a 12-month job search window post-graduation.</p>
            </div>
            <div class="pillar-card animate-on-scroll delay-2">
              <div class="pillar-num" style="background:rgba(245, 158, 11, 0.08); color:var(--neon-orange);"><i class="fa-solid fa-ship"></i></div>
              <h4>Shipping & Maritime Hub</h4>
              <p>Direct exposure to the world's leading maritime industry. Elite programs in Shipping and Logistics with high-income global placement potential.</p>
            </div>
          </div>
        </div>
      </section>

      <!-- MODULE: TALENT & EU PATHWAY BLUEPRINT -->
      <section class="section">
        <div class="container">
          <div class="section__header animate-on-scroll">
            <span class="section__tag">The 2026 Greece Career roadmap</span>
            <h2 class="section__title">The “Talent & EU <span>Pathway” Masterclass</span></h2>
            <div class="accent-bar"></div>
          </div>
          <div class="grid grid--2 gap--4">
            <div class="info-card animate-on-scroll">
              <div class="ic-header"><div class="icon-colorful icon-colorful--blue" style="font-size:1.5rem; margin-right:.75rem"><i class="fa-solid fa-language"></i></div> <h4>English-Taught Growth</h4></div>
              <div class="ic-body">
                <ul class="benefit-list" style="margin-top: 1rem;">
                  <li><i class="fa-solid fa-check-circle text-success"></i> Over 200+ expanded English programs in Tech, Business, and Archaeology</li>
                  <li><i class="fa-solid fa-building-columns text-success"></i> Access to the National Technical University of Athens (#355 Globally)</li>
                </ul>
              </div>
            </div>
            <div class="info-card animate-on-scroll delay-1">
              <div class="ic-header"><div class="icon-colorful icon-colorful--orange" style="font-size:1.5rem; margin-right:.75rem"><i class="fa-solid fa-coins"></i></div> <h4>Affordable EU Entry</h4></div>
              <div class="ic-body">
                <ul class="benefit-list" style="margin-top: 1rem;">
                  <li><i class="fa-solid fa-check-circle text-success"></i> Public transport card: €15/month with student discount</li>
                  <li><i class="fa-solid fa-euro-sign text-success"></i> Competitive Master’s tuition from €3,000 – €9,000/year</li>
                </ul>
              </div>
            </div>
            <div class="info-card animate-on-scroll delay-2">
              <div class="ic-header"><div class="icon-colorful icon-colorful--purple" style="font-size:1.5rem; margin-right:.75rem"><i class="fa-solid fa-briefcase"></i></div> <h4>Work Rights</h4></div>
              <div class="ic-body">
                <p style="color:var(--gray); font-size:0.95rem;">Work 20 hours/week during semesters and <strong>full-time</strong> during holidays. Minimum wage benchmark: ~€880/month.</p>
              </div>
            </div>
            <div class="info-card animate-on-scroll delay-3">
              <div class="ic-header"><div class="icon-colorful icon-colorful--green" style="font-size:1.5rem; margin-right:.75rem"><i class="fa-solid fa-user-shield"></i></div> <h4>PR & Citizenship</h4></div>
              <div class="ic-body">
                <p style="color:var(--gray); font-size:0.95rem;">Permanent Residency after 5 years. Citizenship eligibility after 8 years with B1 Greek language proficiency.</p>
              </div>
            </div>
          </div>
        </div>
      </section>

      <!-- MODULE: FEES & FINANCES -->
      <section class="section" style="background:#f8fafc; border-top:1px solid #e2e8f0;">
        <div class="container">
          <div class="grid grid--2 gap--4">
            <div class="animate-on-scroll">
              <h3 style="font-size:1.5rem; color:#0f172a; margin-bottom:1.5rem; font-weight:800;"><i class="fa-solid fa-wallet" style="color:var(--neon-blue); margin-right:0.5rem;"></i> Official 2026 Benchmarks (Euro)</h3>
              <div class="stayback-container">
                <table class="stayback-table">
                    <thead>
                        <tr>
                            <th>Category</th>
                            <th>2026 Requirement (Approx.)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr><td style="font-weight:700;">Annual Living Funds</td><td>€7,200 (~₹6.5 Lakhs)</td></tr>
                        <tr><td style="font-weight:700;">Monthly Budget</td><td>€600 /month</td></tr>
                        <tr><td style="font-weight:700;">Master’s Tuition</td><td>€3,000 – €9,000 /yr</td></tr>
                        <tr><td style="font-weight:700;">Visa (Type-D) Fee</td><td>€100 – €180</td></tr>
                    </tbody>
                </table>
              </div>
            </div>
            <div class="animate-on-scroll delay-1">
              <h3 style="font-size:1.5rem; color:#0f172a; margin-bottom:1.5rem; font-weight:800;"><i class="fa-solid fa-circle-info" style="color:var(--neon-orange); margin-right:0.5rem;"></i> Expert Note</h3>
              <div class="highlight-box bg-dots" style="background: white; border: 1px solid #e2e8f0; border-radius: 20px; padding: 2rem;">
                <ul class="benefit-list">
                  <li><i class="fa-solid fa-check-circle text-success"></i> Greek Mediterranean diet is healthy & affordable.</li>
                  <li><i class="fa-solid fa-check-circle text-success"></i> Indian restaurants available in Athens & Thessaloniki.</li>
                  <li><i class="fa-solid fa-award text-primary"></i> <strong>Pro Tip:</strong> Shipping graduates have some of the highest EU placement rates!</li>
                  <li><i class="fa-solid fa-sun text-primary"></i> Mediterranean climate offers hot summers and mild winters.</li>
                </ul>
              </div>
            </div>
          </div>
        </div>
      </section>

      <?php elseif ($country_slug === 'india'): ?>

      <!-- MODULE: THE 2026 STRATEGIC ADVANTAGE -->
      <section class="section" style="background:#f8fafc; border-top:1px solid #e2e8f0;">
        <div class="container">
          <div class="section__header animate-on-scroll">
            <span class="section__tag">Global Engine of Research</span>
            <h2 class="section__title">The 2026 <span>Strategic Advantage</span></h2>
            <p style="color:var(--gray); margin-top: 0.5rem;">"Bharat Innovates on a global scale." Access one of the world's largest academic networks offering global quality at a fraction of Western costs.</p>
            <div class="accent-bar"></div>
          </div>
          <div class="grid grid--3 gap--4">
            <div class="pillar-card animate-on-scroll">
              <div class="pillar-num" style="background:rgba(139, 92, 246, 0.08); color:var(--neon-purple);"><i class="fa-solid fa-laptop-code"></i></div>
              <h4>Bharat Innovates 2026</h4>
              <p>National initiative driving startups, AI, and research innovation. Study in the world's fastest-growing major economy with a massive tech ecosystem.</p>
            </div>
            <div class="pillar-card animate-on-scroll delay-1">
              <div class="pillar-num" style="background:rgba(16, 185, 129, 0.08); color:var(--neon-green);"><i class="fa-solid fa-bridge"></i></div>
              <h4>SII Digital Bridge</h4>
              <p>Unified "Study in India" portal for seamless admissions and visa coordination. Single digital identity for all international student compliance.</p>
            </div>
            <div class="pillar-card animate-on-scroll delay-2">
              <div class="pillar-num" style="background:rgba(245, 158, 11, 0.08); color:var(--neon-orange);"><i class="fa-solid fa-award"></i></div>
              <h4>ICCR & SII Scholarships</h4>
              <p>Strategic merit-based funding. Benefit from ICCR scholarships and SII fee waivers up to 100% for eligible international students.</p>
            </div>
          </div>
        </div>
      </section>

      <!-- MODULE: SII DIGITAL ECOSYSTEM BLUEPRINT -->
      <section class="section">
        <div class="container">
          <div class="section__header animate-on-scroll">
            <span class="section__tag">The 2026 India Study Path</span>
            <h2 class="section__title">The “SII Digital” <span>Ecosystem</span></h2>
            <div class="accent-bar"></div>
          </div>
          <div class="grid grid--2 gap--4">
            <div class="info-card animate-on-scroll">
              <div class="ic-header"><div class="icon-colorful icon-colorful--blue" style="font-size:1.5rem; margin-right:.75rem"><i class="fa-solid fa-passport"></i></div> <h4>FRRO & Compliance</h4></div>
              <div class="ic-body">
                <ul class="benefit-list" style="margin-top: 1rem;">
                  <li><i class="fa-solid fa-check-circle text-success"></i> Mandatory FRRO registration within 14 days for long-stay visas</li>
                  <li><i class="fa-solid fa-id-card text-success"></i> SII Student ID for unified digital academic and visa tracking</li>
                </ul>
              </div>
            </div>
            <div class="info-card animate-on-scroll delay-1">
              <div class="ic-header"><div class="icon-colorful icon-colorful--orange" style="font-size:1.5rem; margin-right:.75rem"><i class="fa-solid fa-microchip"></i></div> <h4>Research & Tech Hubs</h4></div>
              <div class="ic-body">
                <ul class="benefit-list" style="margin-top: 1rem;">
                  <li><i class="fa-solid fa-check-circle text-success"></i> Access to IISc Bengaluru (#1 Research) and elite IIT networks</li>
                  <li><i class="fa-solid fa-atom text-success"></i> Strong internship ecosystem in AI, Cybersecurity, and R&D</li>
                </ul>
              </div>
            </div>
            <div class="info-card animate-on-scroll delay-2">
              <div class="ic-header"><div class="icon-colorful icon-colorful--purple" style="font-size:1.5rem; margin-right:.75rem"><i class="fa-solid fa-om"></i></div> <h4>Cultural Heritage</h4></div>
              <div class="ic-body">
                <p style="color:var(--gray); font-size:0.95rem;">Global leadership in <strong>Yoga, Ayurveda, and Sanskrit</strong> studies alongside modern high-tech engineering degrees.</p>
              </div>
            </div>
            <div class="info-card animate-on-scroll delay-3">
              <div class="ic-header"><div class="icon-colorful icon-colorful--green" style="font-size:1.5rem; margin-right:.75rem"><i class="fa-solid fa-chart-line"></i></div> <h4>Career Pipeline</h4></div>
              <div class="ic-body">
                <p style="color:var(--gray); font-size:0.95rem;">Graduates from Indian institutions feed directly into global tech giants like Google, Microsoft, and Amazon.</p>
              </div>
            </div>
          </div>
        </div>
      </section>

      <!-- MODULE: FEES & FINANCES -->
      <section class="section" style="background:#f8fafc; border-top:1px solid #e2e8f0;">
        <div class="container">
          <div class="grid grid--2 gap--4">
            <div class="animate-on-scroll">
              <h3 style="font-size:1.5rem; color:#0f172a; margin-bottom:1.5rem; font-weight:800;"><i class="fa-solid fa-coins" style="color:var(--neon-blue); margin-right:0.5rem;"></i> Official 2026 Benchmarks (USD)</h3>
              <div class="stayback-container">
                <table class="stayback-table">
                    <thead>
                        <tr>
                            <th>Category</th>
                            <th>2026 Requirement (Approx.)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr><td style="font-weight:700;">Living Funds (Monthly)</td><td>$300 – $600 (~₹25k-50k)</td></tr>
                        <tr><td style="font-weight:700;">Public Univ Tuition</td><td>$500 – $3,000 /yr</td></tr>
                        <tr><td style="font-weight:700;">MBBS Tuition (Private)</td><td>$10,000 – $25,000 /yr</td></tr>
                        <tr><td style="font-weight:700;">Visa & FRRO Fees</td><td>$100 – $250</td></tr>
                    </tbody>
                </table>
              </div>
            </div>
            <div class="animate-on-scroll delay-1">
              <h3 style="font-size:1.5rem; color:#0f172a; margin-bottom:1.5rem; font-weight:800;"><i class="fa-solid fa-circle-info" style="color:var(--neon-orange); margin-right:0.5rem;"></i> Expert Note</h3>
              <div class="highlight-box bg-dots" style="background: white; border: 1px solid #e2e8f0; border-radius: 20px; padding: 2rem;">
                <ul class="benefit-list">
                  <li><i class="fa-solid fa-check-circle text-success"></i> Annual Maintenance: $4,000 – $8,000</li>
                  <li><i class="fa-solid fa-check-circle text-success"></i> English-medium instruction in most STEM programs.</li>
                  <li><i class="fa-solid fa-award text-primary"></i> <strong>Pro Tip:</strong> 54 Indian universities in QS 2026 Rankings!</li>
                  <li><i class="fa-solid fa-bolt text-primary"></i> Metro cities offer global cuisine and diverse lifestyle options.</li>
                </ul>
              </div>
            </div>
          </div>
        </div>
      </section>

      <?php elseif ($country_slug === 'kazakhstan'): ?>

      <!-- MODULE: THE 2026 STRATEGIC ADVANTAGE -->
      <section class="section" style="background:#f8fafc; border-top:1px solid #e2e8f0;">
        <div class="container">
          <div class="section__header animate-on-scroll">
            <span class="section__tag">Modern Innovation & Global Medicine</span>
            <h2 class="section__title">The 2026 <span>Strategic Advantage</span></h2>
            <p style="color:var(--gray); margin-top: 0.5rem;">"Kazakhstan is the strategic bridge to global careers." Access 100% English-medium medical degrees at an unmatched global price point.</p>
            <div class="accent-bar"></div>
          </div>
          <div class="grid grid--3 gap--4">
            <div class="pillar-card animate-on-scroll">
              <div class="pillar-num" style="background:rgba(139, 92, 246, 0.08); color:var(--neon-purple);"><i class="fa-solid fa-stethoscope"></i></div>
              <h4>NMC Compliance First</h4>
              <p>Top choice for Indian medical aspirants. 100% NMC 2021 compliant programs with 54 months theory + 12 months clinical internship structure.</p>
            </div>
            <div class="pillar-card animate-on-scroll delay-1">
              <div class="pillar-num" style="background:rgba(16, 185, 129, 0.08); color:var(--neon-green);"><i class="fa-solid fa-qrcode"></i></div>
              <h4>Digital Residency</h4>
              <p>QR-coded residence cards and digital permits for faster verification. Individual Identification Number (IIN) registration for seamless banking.</p>
            </div>
            <div class="pillar-card animate-on-scroll delay-2">
              <div class="pillar-num" style="background:rgba(245, 158, 11, 0.08); color:var(--neon-orange);"><i class="fa-solid fa-microchip"></i></div>
              <h4>Skilled Talent Reform</h4>
              <p>New 2026 simplified work permits for IT, Engineering, and Healthcare sectors. Launch your career in Central Asia's fastest-growing economy.</p>
            </div>
          </div>
        </div>
      </section>

      <!-- MODULE: COMPLIANCE & RESIDENCE BLUEPRINT -->
      <section class="section">
        <div class="container">
          <div class="section__header animate-on-scroll">
            <span class="section__tag">The 2026 Kazakhstan Career Path</span>
            <h2 class="section__title">The “Rule 5” <span>Compliance Blueprint</span></h2>
            <div class="accent-bar"></div>
          </div>
          <div class="grid grid--2 gap--4">
            <div class="info-card animate-on-scroll">
              <div class="ic-header"><div class="icon-colorful icon-colorful--blue" style="font-size:1.5rem; margin-right:.75rem"><i class="fa-solid fa-file-invoice-dollar"></i></div> <h4>VIL Coordination</h4></div>
              <div class="ic-body">
                <ul class="benefit-list" style="margin-top: 1rem;">
                  <li><i class="fa-solid fa-check-circle text-success"></i> Visa Invitation Letter (VIL) handled before embassy submission</li>
                  <li><i class="fa-solid fa-language text-success"></i> 100% English-medium clinical rotations and theory</li>
                </ul>
              </div>
            </div>
            <div class="info-card animate-on-scroll delay-1">
              <div class="ic-header"><div class="icon-colorful icon-colorful--orange" style="font-size:1.5rem; margin-right:.75rem"><i class="fa-solid fa-graduation-cap"></i></div> <h4>Elite Standing</h4></div>
              <div class="ic-body">
                <ul class="benefit-list" style="margin-top: 1rem;">
                  <li><i class="fa-solid fa-check-circle text-success"></i> Al-Farabi Kazakh National University (#1 in Central Asia)</li>
                  <li><i class="fa-solid fa-medal text-success"></i> Degrees recognized in WDOMS, WHO, USMLE, and GMC pathways</li>
                </ul>
              </div>
            </div>
            <div class="info-card animate-on-scroll delay-2">
              <div class="ic-header"><div class="icon-colorful icon-colorful--purple" style="font-size:1.5rem; margin-right:.75rem"><i class="fa-solid fa-clock"></i></div> <h4>Work Flexibility</h4></div>
              <div class="ic-body">
                <p style="color:var(--gray); font-size:0.95rem;">Work up to 20 hours/week with university permission. Massive demand in IT and Energy sectors.</p>
              </div>
            </div>
            <div class="info-card animate-on-scroll delay-3">
              <div class="ic-header"><div class="icon-colorful icon-colorful--green" style="font-size:1.5rem; margin-right:.75rem"><i class="fa-solid fa-id-card-clip"></i></div> <h4>Digital verification</h4></div>
              <div class="ic-body">
                <p style="color:var(--gray); font-size:0.95rem;">QR-coded residence cards allow for <strong>real-time digital verification</strong> through government migration portals.</p>
              </div>
            </div>
          </div>
        </div>
      </section>

      <!-- MODULE: FEES & FINANCES -->
      <section class="section" style="background:#f8fafc; border-top:1px solid #e2e8f0;">
        <div class="container">
          <div class="grid grid--2 gap--4">
            <div class="animate-on-scroll">
              <h3 style="font-size:1.5rem; color:#0f172a; margin-bottom:1.5rem; font-weight:800;"><i class="fa-solid fa-coins" style="color:var(--neon-blue); margin-right:0.5rem;"></i> Official 2026 Benchmarks (USD)</h3>
              <div class="stayback-container">
                <table class="stayback-table">
                    <thead>
                        <tr>
                            <th>Category</th>
                            <th>2026 Requirement (Approx.)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr><td style="font-weight:700;">Living Funds (Monthly)</td><td>$300 – $600 (~₹25k-50k)</td></tr>
                        <tr><td style="font-weight:700;">MBBS Tuition (Annual)</td><td>$3,500 – $5,000 /yr</td></tr>
                        <tr><td style="font-weight:700;">Engineering Tuition</td><td>$1,500 – $4,000 /yr</td></tr>
                        <tr><td style="font-weight:700;">Multiple Entry Visa</td><td>$200 (~₹16,600)</td></tr>
                    </tbody>
                </table>
              </div>
            </div>
            <div class="animate-on-scroll delay-1">
              <h3 style="font-size:1.5rem; color:#0f172a; margin-bottom:1.5rem; font-weight:800;"><i class="fa-solid fa-circle-info" style="color:var(--neon-orange); margin-right:0.5rem;"></i> Expert Note</h3>
              <div class="highlight-box bg-dots" style="background: white; border: 1px solid #e2e8f0; border-radius: 20px; padding: 2rem;">
                <ul class="benefit-list">
                  <li><i class="fa-solid fa-check-circle text-success"></i> Visa Proof of Funds: $3,000 (~₹2.5L)</li>
                  <li><i class="fa-solid fa-check-circle text-success"></i> Indian mess facilities widely available in major cities.</li>
                  <li><i class="fa-solid fa-award text-primary"></i> <strong>Pro Tip:</strong> Nazarbayev University is a global research leader!</li>
                  <li><i class="fa-solid fa-snowflake text-primary"></i> Continental climate: expect very cold winters and warm summers.</li>
                </ul>
              </div>
            </div>
          </div>
        </div>
      </section>
      <?php endif; ?>

  <?php else: ?>
      
      <!-- ==========================================
           CLASSIC FALLBACK STATIC VIEW
           ========================================== -->

      <!-- QUICK FACTS -->
      <section class="section" style="padding-top: 0; margin-top: -50px; position: relative; z-index: 10;">
        <div class="container">
          <div class="grid grid--4 gap--2">
            <div class="fact-card animate-on-scroll">
              <div class="icon-colorful icon-colorful--blue" style="margin: 0 auto 1.25rem;"><i class="fa-solid fa-award"></i></div>
              <h4><?= $country['fact_1'][0] ?></h4>
              <p><?= $country['fact_1'][1] ?></p>
            </div>
            <div class="fact-card animate-on-scroll delay-1">
              <div class="icon-colorful icon-colorful--purple" style="margin: 0 auto 1.25rem;"><i class="fa-solid fa-calendar-check"></i></div>
              <h4><?= $country['fact_2'][0] ?></h4>
              <p><?= $country['fact_2'][1] ?></p>
            </div>
            <div class="fact-card animate-on-scroll delay-2">
              <div class="icon-colorful icon-colorful--orange" style="margin: 0 auto 1.25rem;"><i class="fa-solid fa-briefcase"></i></div>
              <h4><?= $country['fact_3'][0] ?></h4>
              <p><?= $country['fact_3'][1] ?></p>
            </div>
            <div class="fact-card animate-on-scroll delay-3">
              <div class="icon-colorful icon-colorful--teal" style="margin: 0 auto 1.25rem;"><i class="fa-solid fa-wallet"></i></div>
              <h4><?= $country['fact_4'][0] ?></h4>
              <p><?= $country['fact_4'][1] ?></p>
            </div>
          </div>
        </div>
      </section>

      <!-- WHY -->
      <section class="section">
        <div class="container">
          <div class="grid grid--2 gap--4 align-center">
            <div class="animate-on-scroll">
              <h2 class="section__title" style="text-align:left">Why Choose <span><?= $country['name'] ?>?</span></h2>
              <p style="color:var(--gray); line-height:1.8; margin-bottom:1.5rem">Our students choose <?= $country['name'] ?> for its unique blend of academic excellence and lifestyle benefits. Here are the top reasons to consider this destination:</p>
              <ul class="benefit-list">
                <?php foreach($country['benefits'] as $benefit): ?>
                <li><i class="fa-solid fa-circle-check"></i> <?= $benefit ?></li>
                <?php endforeach; ?>
              </ul>
            </div>
            <div class="animate-on-scroll delay-1">
              <div class="highlight-box bg-dots">
                <h3><i class="fa-solid fa-handshake" style="color:var(--primary)"></i> Bluestone Edge</h3>
                <p>We provide exclusive application fee waivers and scholarship assistance for top universities in <?= $country['name'] ?>.</p>
                <a href="consultation.php" class="btn btn--primary" style="margin-top:1.5rem">Get Personalized List</a>
              </div>
            </div>
          </div>
        </div>
      </section>

      <!-- INTAKES -->
      <section class="section" style="background:#f8fafc">
        <div class="container">
          <div class="section__header animate-on-scroll">
            <h2 class="section__title">Intakes in <span><?= $country['name'] ?></span></h2>
            <div class="accent-bar"></div>
          </div>
          <div class="grid grid--3 gap--2">
            <?php 
            $colors = ['blue', 'purple', 'orange'];
            foreach($country['intakes'] as $index => $intake): 
              $color = $colors[$index % 3];
            ?>
            <div class="info-card animate-on-scroll delay-<?= $index ?>">
              <div class="ic-header"><div class="icon-colorful icon-colorful--<?= $color ?>" style="font-size:1.5rem; margin-right:.75rem"><i class="fa-solid fa-clock"></i></div> <h4><?= $intake ?></h4></div>
              <div class="ic-body"><p>Plan your application at least 6-8 months in advance for this intake.</p></div>
            </div>
            <?php endforeach; ?>
          </div>
        </div>
      </section>

  <?php endif; ?>

  <!-- CTA BANNER -->
  <section class="cta-banner">
    <div class="container cta-banner__inner animate-on-scroll">
      <h2>Kickstart Your <?= $db_country ? clean_output($db_country['name']) : $country['name'] ?> Journey</h2>
      <p>Talk to our <?= $db_country ? clean_output($db_country['name']) : $country['name'] ?> experts and get free end-to-end guidance.</p>
      <div class="cta-buttons">
        <a href="consultation.php" class="btn btn--white btn--lg">Book Free Session</a>
        <a href="https://wa.me/919342899904" class="btn btn--ghost btn--lg"><i class="fa-brands fa-whatsapp" style="margin-right: 0.5rem; font-size: 1.15rem;"></i> Chat on WhatsApp</a>
      </div>
    </div>
  </section>
</main>

<script>
// Toggle Frontend Courses accordion
function toggleFrontendCourses(id) {
    const courseBlock = document.getElementById('frontend-courses-' + id);
    const arrow = document.getElementById('frontend-arrow-' + id);
    
    courseBlock.classList.toggle('active');
    
    if (courseBlock.classList.contains('active')) {
        arrow.style.transform = 'rotate(180deg)';
    } else {
        arrow.style.transform = 'rotate(0deg)';
    }
}
</script>

<?php require_once 'includes/footer.php'; ?>

