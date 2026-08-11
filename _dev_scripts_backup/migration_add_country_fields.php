<?php
// migration_add_country_fields.php - Dynamic Country Details & University Hub Migrator
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'includes/db.php';

echo "<pre>";
echo "Starting Database Migration for 2026 Country Detail Modules...\n";

// Helper function to check if a column exists
function columnExists($pdo, $table, $column) {
    $stmt = $pdo->query("SHOW COLUMNS FROM `$table` LIKE '$column'");
    return $stmt->rowCount() > 0;
}

try {
    // 1. Alter the 'countries' table to add metrics
    $columnsToAdd = [
        'travel_hours' => "VARCHAR(100) DEFAULT NULL",
        'study_options' => "VARCHAR(255) DEFAULT NULL",
        'roi_advantage' => "VARCHAR(255) DEFAULT NULL",
        'roi_priority' => "VARCHAR(255) DEFAULT NULL",
        'roi_wage' => "VARCHAR(255) DEFAULT NULL",
        'roi_qs' => "VARCHAR(255) DEFAULT NULL",
        'living_cost_local' => "VARCHAR(100) DEFAULT NULL",
        'living_cost_inr' => "VARCHAR(100) DEFAULT NULL",
        'visa_fee_local' => "VARCHAR(100) DEFAULT NULL",
        'visa_fee_inr' => "VARCHAR(100) DEFAULT NULL",
        'weekly_budget_local' => "VARCHAR(100) DEFAULT NULL",
        'weekly_budget_inr' => "VARCHAR(100) DEFAULT NULL",
        'earnings_potential_local' => "VARCHAR(100) DEFAULT NULL",
        'earnings_potential_inr' => "VARCHAR(100) DEFAULT NULL",
        'stayback_bachelors' => "VARCHAR(150) DEFAULT NULL",
        'stayback_bachelors_stem' => "VARCHAR(150) DEFAULT NULL",
        'stayback_masters' => "VARCHAR(150) DEFAULT NULL",
        'stayback_doctoral' => "VARCHAR(150) DEFAULT NULL",
        'stayback_regional' => "VARCHAR(150) DEFAULT NULL",
        'upcoming_intakes' => "TEXT DEFAULT NULL",
        'demand_careers' => "TEXT DEFAULT NULL",
        'image_url' => "VARCHAR(255) DEFAULT NULL",
        'features' => "TEXT DEFAULT NULL"
    ];

    foreach ($columnsToAdd as $col => $definition) {
        if (!columnExists($pdo, 'countries', $col)) {
            $pdo->exec("ALTER TABLE `countries` ADD COLUMN `$col` $definition");
            echo "Added column '$col' to 'countries' table.\n";
        } else {
            echo "Column '$col' already exists in 'countries' table.\n";
        }
    }

    // 2. Create the 'universities' table
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS `universities` (
            `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
            `country_id` INT(11) UNSIGNED NOT NULL,
            `name` VARCHAR(255) NOT NULL,
            `qs_ranking` VARCHAR(50) DEFAULT NULL,
            `specialization` VARCHAR(255) DEFAULT NULL,
            `is_active` TINYINT(1) NOT NULL DEFAULT 1,
            `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (`id`),
            KEY `idx_uni_country` (`country_id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
    ");
    echo "Table 'universities' is ready.\n";

    // 3. Create the 'courses' table
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS `courses` (
            `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
            `university_id` INT(11) UNSIGNED NOT NULL,
            `name` VARCHAR(255) NOT NULL,
            `duration` VARCHAR(100) DEFAULT NULL,
            `tuition_fee` VARCHAR(100) DEFAULT NULL,
            `intakes` VARCHAR(150) DEFAULT NULL,
            `is_active` TINYINT(1) NOT NULL DEFAULT 1,
            `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (`id`),
            KEY `idx_course_uni` (`university_id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
    ");
    echo "Table 'courses' is ready.\n";

    // 4. Update core destinations with 2026 data
    $destinationsData = [
        'australia' => [
            'travel_hours' => 'Approx 12-14 hours (Flight from India)',
            'study_options' => "Bachelor's, Master's, MBA, Doctoral (PhD), Diploma & Pathway Programs",
            'roi_advantage' => 'Indian Advantage: Exclusive 3-Year stay-back for Master’s graduates (AI-ECTA Benefit)',
            'roi_priority' => 'Priority Triage: Fast-track visa processing for Priority 1 Institutions',
            'roi_wage' => 'Minimum Wage: $24.95 AUD/hr (Among the highest student earnings globally)',
            'roi_qs' => 'QS Power: 6 Universities in the World’s Top 50',
            'living_cost_local' => '$29,710 AUD',
            'living_cost_inr' => '₹16.8 Lakhs',
            'visa_fee_local' => '$2,000 AUD',
            'visa_fee_inr' => '₹1.1 Lakhs',
            'weekly_budget_local' => '$450 – $650 AUD',
            'weekly_budget_inr' => 'Covers Rent, Food & Transit',
            'earnings_potential_local' => '$48,000+ AUD',
            'earnings_potential_inr' => 'Annual income if working maximum allowed hours',
            'stayback_bachelors' => '2 Years',
            'stayback_bachelors_stem' => '3 Years (STEM 1st Class)',
            'stayback_masters' => '3 Years (AI-ECTA)',
            'stayback_doctoral' => '4 Years',
            'stayback_regional' => '+1 Year (Up to 6 Years Total)',
            'upcoming_intakes' => "February 2027 (Major Intake) | Deadline: October 2026\nJuly 2026 / 2027 (Secondary Intake) | Deadline: March / May",
            'demand_careers' => "AI Engineers\nRenewable Energy Specialists\nRegistered Nurses\nCyber Security Experts",
            'features' => '["AI-ECTA 3-Yr Stay-back","Priority Triage Processing","High Minimum Wage ($24.95/hr)","6 Top 50 Universities"]'
        ],
        'usa' => [
            'travel_hours' => 'Approx 15-18 hours',
            'study_options' => "Bachelor's, Master's, MBA, STEM OPT, Doctoral (PhD)",
            'roi_advantage' => 'STEM OPT Extension: Up to 36 Months legal stay-back',
            'roi_priority' => 'Premium Processing: Fast-track I-20 and F-1 student visa processing',
            'roi_wage' => '$15.00 - $20.00 USD/hr (Varies by state & campus jobs)',
            'roi_qs' => 'Ivy League & Tier 1: Most universities in the World’s Top 100',
            'living_cost_local' => '$20,000 USD',
            'living_cost_inr' => '₹16.5 Lakhs',
            'visa_fee_local' => '$185 USD',
            'visa_fee_inr' => '₹15,000',
            'weekly_budget_local' => '$350 – $500 USD',
            'weekly_budget_inr' => 'Rent, utilities & meal plans',
            'earnings_potential_local' => '$35,000+ USD',
            'earnings_potential_inr' => 'Annual part-time & internship earnings potential',
            'stayback_bachelors' => '1 Year (OPT)',
            'stayback_bachelors_stem' => '3 Years (STEM OPT)',
            'stayback_masters' => '3 Years (STEM OPT)',
            'stayback_doctoral' => '3 Years',
            'stayback_regional' => 'CPT & OPT industry placements',
            'upcoming_intakes' => "Fall (August) | Deadline: Dec - Jan\nSpring (January) | Deadline: June - August",
            'demand_careers' => "Software Engineers\nData Scientists\nFinTech Specialists\nBiomedical Engineers",
            'features' => '["3-Year STEM OPT","Ivy League & Tier 1","High Internships Pay","Flexible Majors"]'
        ],
        'uk' => [
            'travel_hours' => 'Approx 9-11 hours',
            'study_options' => "1-Year Master's, Bachelor's, MBA, PhD",
            'roi_advantage' => 'Graduate Route: Guaranteed 2 Years stay-back for all grads',
            'roi_priority' => 'Shorted Duration: 1-year Master saves 50% tuition & living cost',
            'roi_wage' => '£11.44 GBP/hr (National Minimum Wage)',
            'roi_qs' => 'Russell Group: Historic universities with elite worldwide reputation',
            'living_cost_local' => '£12,006 GBP',
            'living_cost_inr' => '₹12.5 Lakhs',
            'visa_fee_local' => '£490 GBP',
            'visa_fee_inr' => '₹52,000',
            'weekly_budget_local' => '£200 – £350 GBP',
            'weekly_budget_inr' => 'Covers student halls & local transit',
            'earnings_potential_local' => '£25,000+ GBP',
            'earnings_potential_inr' => 'Estimated annual income if working allowed 20 hrs/week',
            'stayback_bachelors' => '2 Years',
            'stayback_bachelors_stem' => '2 Years',
            'stayback_masters' => '2 Years',
            'stayback_doctoral' => '3 Years',
            'stayback_regional' => 'Russell Group Placement Benefits',
            'upcoming_intakes' => "September (Major Intake) | Deadline: May - June\nJanuary (Secondary Intake) | Deadline: September - October",
            'demand_careers' => "Business Analysts\nCybersecurity Specialists\nRegistered Nurses\nMechanical Engineers",
            'features' => '["1-Year Master\'s Saving","2-Year Graduate Visa","Russell Group Network","No GRE/GMAT Required"]'
        ],
        'canada' => [
            'travel_hours' => 'Approx 14-16 hours',
            'study_options' => "Diploma, Postgraduate Diploma, Bachelor's, Master's",
            'roi_advantage' => 'Post-Graduation Work Permit (PGWP): Up to 3 Years',
            'roi_priority' => 'SDS Stream: Fast-track visa processing with GIC',
            'roi_wage' => '$15.00 - $17.30 CAD/hr (Depending on province)',
            'roi_qs' => 'Top Public Unis: Highly ranked options in Toronto, BC, Montreal',
            'living_cost_local' => '$20,635 CAD',
            'living_cost_inr' => '₹12.6 Lakhs',
            'visa_fee_local' => '$150 CAD',
            'visa_fee_inr' => '₹9,000',
            'weekly_budget_local' => '$300 – $450 CAD',
            'weekly_budget_inr' => 'Covers shared rooms & bus pass',
            'earnings_potential_local' => '$30,000+ CAD',
            'earnings_potential_inr' => 'Covers basic necessities and tuition buffers',
            'stayback_bachelors' => '3 Years',
            'stayback_bachelors_stem' => '3 Years',
            'stayback_masters' => '3 Years',
            'stayback_doctoral' => '3 Years',
            'stayback_regional' => 'Provincial Nominee Program (PNP) pathways',
            'upcoming_intakes' => "September (Fall) | Deadline: Feb - April\nJanuary (Winter) | Deadline: June - August\nMay (Spring) | Deadline: Nov - January",
            'demand_careers' => "Cloud Architects\nConstruction Managers\nRegistered Nurses\nAI Researchers",
            'features' => '["Up to 3-Year PGWP","Express Entry & PR Path","SDS Fast Visa stream","High Quality of Life"]'
        ]
    ];

    foreach ($destinationsData as $slug => $data) {
        // Find existing country ID
        $stmt = $pdo->prepare("SELECT id FROM `countries` WHERE `slug` = :slug");
        $stmt->execute(['slug' => $slug]);
        $country = $stmt->fetch();
        
        if ($country) {
            $countryId = $country['id'];
            
            // Perform update
            $updateSql = "UPDATE `countries` SET 
                `travel_hours` = :travel_hours,
                `study_options` = :study_options,
                `roi_advantage` = :roi_advantage,
                `roi_priority` = :roi_priority,
                `roi_wage` = :roi_wage,
                `roi_qs` = :roi_qs,
                `living_cost_local` = :living_cost_local,
                `living_cost_inr` = :living_cost_inr,
                `visa_fee_local` = :visa_fee_local,
                `visa_fee_inr` = :visa_fee_inr,
                `weekly_budget_local` = :weekly_budget_local,
                `weekly_budget_inr` = :weekly_budget_inr,
                `earnings_potential_local` = :earnings_potential_local,
                `earnings_potential_inr` = :earnings_potential_inr,
                `stayback_bachelors` = :stayback_bachelors,
                `stayback_bachelors_stem` = :stayback_bachelors_stem,
                `stayback_masters` = :stayback_masters,
                `stayback_doctoral` = :stayback_doctoral,
                `stayback_regional` = :stayback_regional,
                `upcoming_intakes` = :upcoming_intakes,
                `demand_careers` = :demand_careers,
                `features` = :features
                WHERE `id` = :id";
            
            $updateParams = array_merge($data, ['id' => $countryId]);
            $updateStmt = $pdo->prepare($updateSql);
            $updateStmt->execute($updateParams);
            
            echo "Seeded 2026 details for existing country: '$slug' (ID: $countryId)\n";
        } else {
            // Insert brand new
            $insertSql = "INSERT INTO `countries` (
                `slug`, `name`, `flag`, `description`, `travel_hours`, `study_options`, 
                `roi_advantage`, `roi_priority`, `roi_wage`, `roi_qs`, 
                `living_cost_local`, `living_cost_inr`, `visa_fee_local`, `visa_fee_inr`, 
                `weekly_budget_local`, `weekly_budget_inr`, `earnings_potential_local`, `earnings_potential_inr`, 
                `stayback_bachelors`, `stayback_bachelors_stem`, `stayback_masters`, `stayback_doctoral`, `stayback_regional`, 
                `upcoming_intakes`, `demand_careers`, `features`
            ) VALUES (
                :slug, :name, :flag, :description, :travel_hours, :study_options, 
                :roi_advantage, :roi_priority, :roi_wage, :roi_qs, 
                :living_cost_local, :living_cost_inr, :visa_fee_local, :visa_fee_inr, 
                :weekly_budget_local, :weekly_budget_inr, :earnings_potential_local, :earnings_potential_inr, 
                :stayback_bachelors, :stayback_bachelors_stem, :stayback_masters, :stayback_doctoral, :stayback_regional, 
                :upcoming_intakes, :demand_careers, :features
            )";
            
            $insertParams = array_merge($data, [
                'slug' => $slug,
                'name' => ucfirst($slug === 'usa' ? 'USA' : ($slug === 'uk' ? 'UK' : $slug)),
                'flag' => $slug === 'australia' ? '🇦🇺' : ($slug === 'usa' ? '🇺🇸' : ($slug === 'uk' ? '🇬🇧' : '🇨🇦')),
                'description' => "Explore premium 2026 pathways for study, stay-back, and career success in " . ucfirst($slug)
            ]);
            
            $insertStmt = $pdo->prepare($insertSql);
            $insertStmt->execute($insertParams);
            $countryId = $pdo->lastInsertId();
            echo "Created and seeded new country: '$slug' (ID: $countryId)\n";
        }
        
        // Let's seed universities and courses for Australia specifically
        if ($slug === 'australia') {
            // Delete old universities/courses first to prevent duplication
            $pdo->exec("DELETE FROM `universities` WHERE `country_id` = $countryId");
            
            $australiaUnis = [
                [
                    'name' => 'University of Melbourne',
                    'qs_ranking' => '#19',
                    'specialization' => 'Medicine, Psychology, Law',
                    'courses' => [
                        ['name' => 'Master of Medicine', 'duration' => '3 Years', 'tuition_fee' => '$65,000 AUD', 'intakes' => 'Feb'],
                        ['name' => 'Doctor of Medicine', 'duration' => '4 Years', 'tuition_fee' => '$85,000 AUD', 'intakes' => 'Feb'],
                        ['name' => 'Master of Laws (LLM)', 'duration' => '1 Year', 'tuition_fee' => '$48,000 AUD', 'intakes' => 'Feb, July']
                    ]
                ],
                [
                    'name' => 'UNSW Sydney',
                    'qs_ranking' => '#20',
                    'specialization' => 'AI, Engineering, Technology',
                    'courses' => [
                        ['name' => 'Master of Information Technology', 'duration' => '2 Years', 'tuition_fee' => '$45,000 AUD', 'intakes' => 'Feb, July'],
                        ['name' => 'Master of Engineering (AI Specialization)', 'duration' => '2 Years', 'tuition_fee' => '$46,500 AUD', 'intakes' => 'Feb, July'],
                        ['name' => 'Bachelor of Science (Computer Science)', 'duration' => '3 Years', 'tuition_fee' => '$44,000 AUD', 'intakes' => 'Feb, July']
                    ]
                ],
                [
                    'name' => 'University of Sydney',
                    'qs_ranking' => '#25',
                    'specialization' => 'MBA, Sports-related Subjects',
                    'courses' => [
                        ['name' => 'Master of Business Administration (MBA)', 'duration' => '1.5 Years', 'tuition_fee' => '$52,000 AUD', 'intakes' => 'Feb, July'],
                        ['name' => 'Master of Sports Science', 'duration' => '2 Years', 'tuition_fee' => '$43,500 AUD', 'intakes' => 'Feb']
                    ]
                ],
                [
                    'name' => 'ANU Canberra',
                    'qs_ranking' => '#32',
                    'specialization' => 'Research, Public Policy',
                    'courses' => [
                        ['name' => 'Master of Public Policy', 'duration' => '2 Years', 'tuition_fee' => '$42,000 AUD', 'intakes' => 'Feb, July'],
                        ['name' => 'Master of Science (Research)', 'duration' => '2 Years', 'tuition_fee' => '$41,000 AUD', 'intakes' => 'Feb']
                    ]
                ],
                [
                    'name' => 'Monash University',
                    'qs_ranking' => '#18',
                    'specialization' => 'Pharmacy & Pharmacology',
                    'courses' => [
                        ['name' => 'Bachelor of Pharmacy (Honours)', 'duration' => '4 Years', 'tuition_fee' => '$46,000 AUD', 'intakes' => 'Feb'],
                        ['name' => 'Master of Clinical Pharmacy', 'duration' => '2 Years', 'tuition_fee' => '$48,000 AUD', 'intakes' => 'Feb, July']
                    ]
                ]
            ];
            
            foreach ($australiaUnis as $uniData) {
                $uniStmt = $pdo->prepare("INSERT INTO `universities` (country_id, name, qs_ranking, specialization, is_active) VALUES (:cid, :name, :qs, :spec, 1)");
                $uniStmt->execute([
                    'cid' => $countryId,
                    'name' => $uniData['name'],
                    'qs' => $uniData['qs_ranking'],
                    'spec' => $uniData['specialization']
                ]);
                $uniId = $pdo->lastInsertId();
                echo "  Seeded University: '{$uniData['name']}' (ID: $uniId)\n";
                
                foreach ($uniData['courses'] as $cData) {
                    $cStmt = $pdo->prepare("INSERT INTO `courses` (university_id, name, duration, tuition_fee, intakes, is_active) VALUES (:uid, :name, :duration, :fee, :intakes, 1)");
                    $cStmt->execute([
                        'uid' => $uniId,
                        'name' => $cData['name'],
                        'duration' => $cData['duration'],
                        'fee' => $cData['tuition_fee'],
                        'intakes' => $cData['intakes']
                    ]);
                }
                echo "    Seeded " . count($uniData['courses']) . " courses against '{$uniData['name']}'\n";
            }
        }
    }

    echo "\nDatabase migration completed successfully! All tables created, columns added, and Australia, USA, UK, Canada data seeded!\n";
    echo "You can now safely delete this script or leave it for development.\n";

} catch (PDOException $e) {
    echo "\nMigration Error: " . $e->getMessage() . "\n";
    echo "Stack Trace:\n" . $e->getTraceAsString() . "\n";
}
echo "</pre>";
