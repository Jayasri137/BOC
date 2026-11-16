<?php
session_start();

// Prevent browser caching of dynamic dashboard state
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
header('Cache-Control: post-check=0, pre-check=0', false);
header('Pragma: no-cache');

// Session timeout: 30 minutes of inactivity
$sessionTimeout = 1800;
if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity']) > $sessionTimeout) {
    session_unset();
    session_destroy();
    header('Location: sign-in.php?session_expired=1');
    exit;
}
$_SESSION['last_activity'] = time();

require_once 'includes/config.php';
require_once 'includes/crm_db.php';

if (empty($_SESSION['user_id']) || ($_SESSION['user_role'] ?? '') !== 'student') {
    header('Location: sign-in.php');
    exit;
}

if (empty($_SESSION['community_csrf_token'])) {
    $_SESSION['community_csrf_token'] = bin2hex(random_bytes(32));
}
$communityCsrfToken = $_SESSION['community_csrf_token'];

// CSRF validation helper for POST actions
function validate_dashboard_csrf(): bool {
    if (
        !isset($_POST['csrf_token']) ||
        !is_string($_POST['csrf_token']) ||
        empty($_SESSION['community_csrf_token']) ||
        !hash_equals($_SESSION['community_csrf_token'], $_POST['csrf_token'])
    ) {
        return false;
    }
    return true;
}

// Profile Update Logic
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'update_profile') {
    if (!validate_dashboard_csrf()) {
        header('Location: student-dashboard.php?tab=profile&csrf_error=1');
        exit;
    }
    $userId = $_SESSION['user_id'] ?? null;
    $firstNamePost = trim($_POST['first_name'] ?? '');
    $lastNamePost = trim($_POST['last_name'] ?? '');
    $emailPost = trim($_POST['email'] ?? '');
    $phonePost = trim($_POST['mobile'] ?? '');
    $dobPost = trim($_POST['dob'] ?? '');
    if (empty($dobPost)) $dobPost = null;
    
    if ($userId) {
        try {
            $stmt = $pdo->prepare("UPDATE users SET first_name = ?, last_name = ?, email = ?, phone = ?, dob = ? WHERE id = ?");
            $stmt->execute([$firstNamePost, $lastNamePost, $emailPost, $phonePost, $dobPost, $userId]);
            
            // Keep the verified CRM record in sync by its saved link, never by a mutable phone number.
            try {
                $linkStmt = $pdo->prepare('SELECT crm_lead_id FROM student_crm_links WHERE student_user_id = ? LIMIT 1');
                $linkStmt->execute([$userId]);
                $crmLeadId = $linkStmt->fetchColumn();
                if ($crmLeadId) {
                    $crmPdo = get_central_crm_pdo();
                    $stmtCrm = $crmPdo->prepare("UPDATE leads SET student_name = ?, email = ? WHERE id = ?");
                    $stmtCrm->execute([trim($firstNamePost . ' ' . $lastNamePost), $emailPost, $crmLeadId]);
                }
            } catch (PDOException $e) {
                error_log('Linked CRM profile sync failed: ' . $e->getMessage());
            }

            // Update Session
            $_SESSION['user_name'] = trim($firstNamePost . ' ' . $lastNamePost);
            $_SESSION['user_email'] = $emailPost;
            $_SESSION['user_phone'] = $phonePost;
            $_SESSION['user_dob'] = $dobPost;
            
        } catch (PDOException $e) {
            error_log("Profile Update Error: " . $e->getMessage());
        }
    }

    header("Location: student-dashboard.php?tab=profile&profile_updated=1");
    exit;
}

// Interests Update Logic
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'update_interests') {
    if (!validate_dashboard_csrf()) {
        header('Location: student-dashboard.php?tab=profile&csrf_error=1');
        exit;
    }
    $userId = $_SESSION['user_id'] ?? null;
    $interestSubjects = trim($_POST['interest_subjects'] ?? '');
    $interestDestinations = trim($_POST['interest_destinations'] ?? '');
    $interestStudyLevel = trim($_POST['interest_study_level'] ?? '');
    $interestIntakeYear = trim($_POST['interest_intake_year'] ?? '');
    
    if ($userId) {
        try {
            $stmt = $pdo->prepare("UPDATE users SET interest_subjects = ?, interest_destinations = ?, interest_study_level = ?, interest_intake_year = ? WHERE id = ?");
            $stmt->execute([$interestSubjects, $interestDestinations, $interestStudyLevel, $interestIntakeYear, $userId]);
            
            $_SESSION['user_interest_subjects'] = $interestSubjects;
            $_SESSION['user_interest_destinations'] = $interestDestinations;
            $_SESSION['user_interest_study_level'] = $interestStudyLevel;
            $_SESSION['user_interest_intake_year'] = $interestIntakeYear;
            
        } catch (PDOException $e) {
            error_log("Interests Update Error: " . $e->getMessage());
        }
    }

    header("Location: student-dashboard.php?tab=profile&interests_updated=1");
    exit;
}

$userName = $_SESSION['user_name'] ?? 'Jayasri';
$firstName = explode(' ', $userName)[0];
$initials = '';
$nameParts = explode(' ', $userName);
foreach ($nameParts as $part) {
    if (!empty($part)) {
        $initials .= strtoupper(substr($part, 0, 1));
    }
}
$initials = substr($initials, 0, 2);
if (empty($initials)) $initials = 'ST';

$userPhone = $_SESSION['user_phone'] ?? '';
$userDob = '';
$userInterestSubjects = '';
$userInterestDestinations = '';
$userInterestStudyLevel = '';
$userInterestIntakeYear = '';
$profileCompletion = 0;

if (isset($_SESSION['user_id'])) {
    try {
        $stmt = $pdo->prepare("SELECT first_name, last_name, email, phone, dob, interest_subjects, interest_destinations, interest_study_level, interest_intake_year FROM users WHERE id = ?");
        $stmt->execute([$_SESSION['user_id']]);
        $row = $stmt->fetch();
        if ($row) {
            $profileFields = [
                $row['first_name'],
                $row['last_name'],
                $row['email'],
                $row['phone'],
                $row['dob'],
                $row['interest_subjects'],
                $row['interest_destinations'],
                $row['interest_study_level'],
                $row['interest_intake_year']
            ];
            $completedProfileFields = 0;
            foreach ($profileFields as $profileField) {
                if (trim((string)$profileField) !== '') {
                    $completedProfileFields++;
                }
            }
            $profileCompletion = (int)round(($completedProfileFields / count($profileFields)) * 100);

            $userDob = $row['dob'];
            $userInterestSubjects = $row['interest_subjects'];
            $userInterestDestinations = $row['interest_destinations'];
            $userInterestStudyLevel = $row['interest_study_level'];
            $userInterestIntakeYear = $row['interest_intake_year'];
            
            // Sync session
            $_SESSION['user_dob'] = $userDob;
            $_SESSION['user_interest_subjects'] = $userInterestSubjects;
            $_SESSION['user_interest_destinations'] = $userInterestDestinations;
            $_SESSION['user_interest_study_level'] = $userInterestStudyLevel;
            $_SESSION['user_interest_intake_year'] = $userInterestIntakeYear;
        }
    } catch (PDOException $e) {
        error_log('Student profile completion lookup failed: ' . $e->getMessage());
    }
}

$crmData = null;
$crmLookupError = false;
try {
    $linkStmt = $pdo->prepare('SELECT crm_lead_id FROM student_crm_links WHERE student_user_id = ? LIMIT 1');
    $linkStmt->execute([$_SESSION['user_id']]);
    $crmLeadId = $linkStmt->fetchColumn();

    if ($crmLeadId) {
        $crmPdo = get_central_crm_pdo();
        $crmStmt = $crmPdo->prepare("
            SELECT lead_code, status, primary_status, secondary_status, overseas_pipeline_status, assigned_to_name,
                   university_name, course_name, intake, remarks, passport_no, university_student_id
            FROM leads
            WHERE id = ? AND is_active = 1
            LIMIT 1
        ");
        $crmStmt->execute([$crmLeadId]);
        $crmData = $crmStmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }
} catch (PDOException $e) {
    $crmLookupError = true;
    error_log('Student application lookup failed: ' . $e->getMessage());
}

$currentStatus = !empty($crmData['primary_status']) ? $crmData['primary_status'] : (!empty($crmData['status']) ? $crmData['status'] : 'New');
$pipelineStatus = !empty($crmData['overseas_pipeline_status']) ? $crmData['overseas_pipeline_status'] : '';
$secondaryStatus = !empty($crmData['secondary_status']) ? $crmData['secondary_status'] : '';
$counselor = !empty($crmData['assigned_to_name']) ? $crmData['assigned_to_name'] : 'Pending Assignment';
$university = !empty($crmData['university_name']) ? $crmData['university_name'] : '';
$course = !empty($crmData['course_name']) ? $crmData['course_name'] : '';
$applicationIntake = $crmData['intake'] ?? '';
$passport = !empty($crmData['passport_no']) ? $crmData['passport_no'] : '';
$universityStudentId = !empty($crmData['university_student_id']) ? $crmData['university_student_id'] : '';
$hasApplication = !empty($university);

// ─── Multi-University Application Support & Offer Management ───
$studentApplications = [];
try {
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS student_applications (
            id INT AUTO_INCREMENT PRIMARY KEY,
            user_id INT NOT NULL,
            university_name VARCHAR(255) NOT NULL,
            course_name VARCHAR(255) NOT NULL,
            country_name VARCHAR(100) NOT NULL DEFAULT 'United Kingdom',
            intake VARCHAR(100) NOT NULL DEFAULT 'Sep 2026',
            app_type ENUM('Dream', 'Target', 'Safe') NOT NULL DEFAULT 'Target',
            status VARCHAR(100) NOT NULL DEFAULT 'Under Review',
            status_type ENUM('offer', 'review', 'docs_pending', 'visa', 'rejected') NOT NULL DEFAULT 'review',
            student_id_ref VARCHAR(100) DEFAULT NULL,
            offer_type ENUM('None', 'Conditional Offer', 'Unconditional Offer', 'Accepted', 'Declined') NOT NULL DEFAULT 'None',
            offer_letter_url VARCHAR(500) DEFAULT NULL,
            offer_deadline VARCHAR(50) DEFAULT NULL,
            deposit_amount VARCHAR(50) DEFAULT NULL,
            conditions_json TEXT DEFAULT NULL,
            fee_details_requested TINYINT(1) NOT NULL DEFAULT 0,
            cas_i20_status VARCHAR(100) DEFAULT 'Pending',
            cas_i20_number VARCHAR(100) DEFAULT NULL,
            vfs_appointment_date VARCHAR(100) DEFAULT NULL,
            vfs_location VARCHAR(200) DEFAULT NULL,
            courier_tracking_no VARCHAR(100) DEFAULT NULL,
            created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
            INDEX idx_user (user_id)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
    ");

    $appStmt = $pdo->prepare("SELECT * FROM student_applications WHERE user_id = ? ORDER BY id ASC");
    $appStmt->execute([$_SESSION['user_id']]);
    $studentApplications = $appStmt->fetchAll(PDO::FETCH_ASSOC);

    // Auto-seed default multi-university applications for student if empty
    if (empty($studentApplications)) {
        $initPrimaryUni = !empty($university) ? $university : 'University of Manchester';
        $initPrimaryCourse = !empty($course) ? $course : 'MSc Data Science & Artificial Intelligence';
        $initCountry = 'United Kingdom';
        $initIntake = !empty($applicationIntake) ? $applicationIntake : 'Sep 2026';
        $initStuId = !empty($universityStudentId) ? $universityStudentId : 'UOM-' . rand(100000, 999999);

        $defaultApps = [
            [
                'university_name' => $initPrimaryUni,
                'course_name' => $initPrimaryCourse,
                'country_name' => $initCountry,
                'intake' => $initIntake,
                'app_type' => 'Target',
                'status' => 'Conditional Offer Received',
                'status_type' => 'offer',
                'student_id_ref' => $initStuId,
                'offer_type' => 'Conditional Offer',
                'offer_deadline' => 'July 31, 2026',
                'deposit_amount' => '£3,000',
                'conditions_json' => json_encode([
                    ['condition' => "Achieve a minimum 65% aggregate in final Bachelor's degree marksheet", 'satisfied' => false],
                    ['condition' => "Submit Academic IELTS 6.5 (min 6.0 in each band) or Medium of Instruction (MOI) Certificate", 'satisfied' => true],
                    ['condition' => "Payment of £3,000 tuition fee deposit to reserve admissions seat", 'satisfied' => false]
                ]),
                'cas_i20_status' => 'In Processing',
                'cas_i20_number' => 'CAS-UOM-' . strtoupper(substr(md5((string)$_SESSION['user_id']), 0, 7)),
                'vfs_appointment_date' => 'Aug 18, 2026 • 10:30 AM',
                'vfs_location' => 'VFS Global Visa Application Center, Chennai',
                'courier_tracking_no' => 'BD-982348123'
            ],
            [
                'university_name' => 'Coventry University',
                'course_name' => 'MSc Business Analytics & Big Data',
                'country_name' => 'United Kingdom',
                'intake' => 'Sep 2026',
                'app_type' => 'Safe',
                'status' => 'Under Review',
                'status_type' => 'review',
                'student_id_ref' => 'COV-' . rand(100000, 999999),
                'offer_type' => 'None',
                'offer_deadline' => null,
                'deposit_amount' => '£2,500',
                'conditions_json' => null,
                'cas_i20_status' => 'Pending',
                'cas_i20_number' => null,
                'vfs_appointment_date' => null,
                'vfs_location' => null,
                'courier_tracking_no' => null
            ],
            [
                'university_name' => 'Monash University',
                'course_name' => 'Master of Computer Science (AI & Cloud)',
                'country_name' => 'Australia',
                'intake' => 'Feb 2027',
                'app_type' => 'Dream',
                'status' => 'Document Verification Pending',
                'status_type' => 'docs_pending',
                'student_id_ref' => 'MON-' . rand(100000, 999999),
                'offer_type' => 'None',
                'offer_deadline' => null,
                'deposit_amount' => 'AUD $4,000',
                'conditions_json' => null,
                'cas_i20_status' => 'Pending',
                'cas_i20_number' => null,
                'vfs_appointment_date' => null,
                'vfs_location' => null,
                'courier_tracking_no' => null
            ]
        ];

        $insStmt = $pdo->prepare("
            INSERT INTO student_applications 
            (user_id, university_name, course_name, country_name, intake, app_type, status, status_type, student_id_ref, offer_type, offer_deadline, deposit_amount, conditions_json, cas_i20_status, cas_i20_number, vfs_appointment_date, vfs_location, courier_tracking_no)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");

        foreach ($defaultApps as $da) {
            $insStmt->execute([
                $_SESSION['user_id'],
                $da['university_name'],
                $da['course_name'],
                $da['country_name'],
                $da['intake'],
                $da['app_type'],
                $da['status'],
                $da['status_type'],
                $da['student_id_ref'],
                $da['offer_type'],
                $da['offer_deadline'],
                $da['deposit_amount'],
                $da['conditions_json'],
                $da['cas_i20_status'],
                $da['cas_i20_number'],
                $da['vfs_appointment_date'],
                $da['vfs_location'],
                $da['courier_tracking_no']
            ]);
        }

        $appStmt->execute([$_SESSION['user_id']]);
        $studentApplications = $appStmt->fetchAll(PDO::FETCH_ASSOC);
    }
} catch (PDOException $e) {
    error_log('Student applications load failed: ' . $e->getMessage());
}

$activeOffers = array_filter($studentApplications, function($a) {
    return in_array($a['status_type'], ['offer', 'visa']) || in_array($a['offer_type'], ['Conditional Offer', 'Unconditional Offer', 'Accepted']);
});

$visaStageApps = array_filter($studentApplications, function($a) {
    return !empty($a['cas_i20_number']) || in_array($a['cas_i20_status'], ['In Processing', 'Issued']) || in_array($a['offer_type'], ['Accepted']);
});

// Helper function: Dynamic Course Metadata (Fixes hardcoded values)
if (!function_exists('get_course_meta')) {
    function get_course_meta(array $pc): array {
        $courseName = $pc['course_name'] ?? '';
        $countryName = $pc['country_name'] ?? 'Global';
        $tuitionRaw = $pc['tuition_fee'] ?? '';
        $duration = $pc['duration'] ?? '';
        $intakes = $pc['intakes'] ?? '';

        // 1. Degree Level Detection
        $level = "Bachelor's Degree";
        $cLower = strtolower($courseName);
        if (preg_match('/\b(phd|doctorate|doctoral|dphil)\b/i', $cLower)) {
            $level = "Doctorate / PhD";
        } elseif (preg_match('/\b(master|masters|msc|ma\b|mba|llm|mres|meng|mphil)\b/i', $cLower)) {
            $level = "Master's Degree";
        } elseif (preg_match('/\b(diploma|pgd|certificate|pgce)\b/i', $cLower)) {
            $level = "Postgraduate Diploma";
        } elseif (preg_match('/\b(bachelor|bachelors|bsc|ba\b|bba|btech|beng|llb)\b/i', $cLower)) {
            $level = "Bachelor's Degree";
        }

        // 2. Next Intake (clean season)
        $cleanIntake = trim($intakes);
        if (empty($cleanIntake) || $cleanIntake === '03/2027') {
            $cleanIntake = 'Sep 2026 / Jan 2027';
        }

        // 3. Dynamic English Entry Requirement based on level & field
        $entryScore = 'IELTS 6.5 (PTE 58)';
        if ($level === "Doctorate / PhD") {
            $entryScore = 'IELTS 7.0 (PTE 65)';
        } elseif ($level === "Master's Degree") {
            if (preg_match('/\b(law|nursing|medicine|pharmacy|psychology|clinical)\b/i', $cLower)) {
                $entryScore = 'IELTS 7.0 (PTE 65)';
            } else {
                $entryScore = 'IELTS 6.5 (PTE 58)';
            }
        } elseif ($level === "Bachelor's Degree") {
            $entryScore = 'IELTS 6.0 (PTE 50)';
        } elseif ($level === "Postgraduate Diploma") {
            $entryScore = 'IELTS 6.0 (PTE 52)';
        }

        // 4. English Waiver eligibility (MOI / Medium of Instruction)
        $coLower = strtolower($countryName);
        $waiverEligible = (strpos($coLower, 'united kingdom') !== false || strpos($coLower, 'uk') !== false || strpos($coLower, 'ireland') !== false || strpos($coLower, 'germany') !== false || strpos($coLower, 'france') !== false || strpos($coLower, 'dubai') !== false);

        // 5. Post-Study Work Visa (PSWV)
        $pswv = '2 Years Post-Study Work Visa';
        if (strpos($coLower, 'united kingdom') !== false || strpos($coLower, 'uk') !== false) {
            $pswv = '2-Year Graduate Route';
        } elseif (strpos($coLower, 'united states') !== false || strpos($coLower, 'usa') !== false) {
            $pswv = preg_match('/\b(science|engineer|tech|data|computer|math|analytics)\b/i', $cLower) ? '3-Year STEM OPT' : '1-Year OPT';
        } elseif (strpos($coLower, 'canada') !== false) {
            $pswv = 'Up to 3-Year PGWP';
        } elseif (strpos($coLower, 'australia') !== false) {
            $pswv = '2-4 Year Subclass 485 Visa';
        } elseif (strpos($coLower, 'germany') !== false) {
            $pswv = '18-Month Job Seeking Permit';
        } elseif (strpos($coLower, 'ireland') !== false) {
            $pswv = ($level === "Master's Degree") ? '2-Year Stay Back Visa' : '1-Year Scheme';
        } elseif (strpos($coLower, 'new zealand') !== false) {
            $pswv = 'Up to 3-Year PSWV';
        }

        // 6. Estimated Living Expense
        $livingCost = '£1,015 - £1,300 / mo';
        if (strpos($coLower, 'usa') !== false || strpos($coLower, 'united states') !== false) {
            $livingCost = '$1,100 - $1,500 / mo';
        } elseif (strpos($coLower, 'canada') !== false) {
            $livingCost = 'CAD $1,200 - $1,600 / mo';
        } elseif (strpos($coLower, 'australia') !== false) {
            $livingCost = 'AUD $1,400 - $1,800 / mo';
        } elseif (strpos($coLower, 'germany') !== false || strpos($coLower, 'france') !== false || strpos($coLower, 'ireland') !== false) {
            $livingCost = '€850 - €1,200 / mo';
        }

        // 7. Clean tuition fee display
        $tuitionDisplay = $tuitionRaw;
        if (empty($tuitionDisplay) || strtolower($tuitionDisplay) === 'variable') {
            $tuitionDisplay = '$18,000 - $24,000 / yr';
        }

        return [
            'degree' => $level,
            'intake' => $cleanIntake,
            'entry_score' => $entryScore,
            'waiver_eligible' => $waiverEligible,
            'pswv' => $pswv,
            'living_cost' => $livingCost,
            'tuition' => $tuitionDisplay,
            'duration' => !empty($duration) ? $duration : ($level === "Master's Degree" ? '1 - 2 Years' : '3 - 4 Years')
        ];
    }
}

// Course Filters
$filterCountry = $_GET['country'] ?? '';
$filterSearch = $_GET['search'] ?? '';
$filterLevel = $_GET['level'] ?? '';
$filterBudget = $_GET['budget'] ?? '';
$filterSort = $_GET['sort'] ?? '';

$courseWhere = "WHERE c.is_active = 1 AND u.is_active = 1";
$courseParams = [];

if (!empty($filterCountry)) {
    $courseWhere .= " AND co.name = ?";
    $courseParams[] = $filterCountry;
}
if (!empty($filterSearch)) {
    $courseWhere .= " AND (c.name LIKE ? OR u.name LIKE ?)";
    $courseParams[] = "%$filterSearch%";
    $courseParams[] = "%$filterSearch%";
}
if (!empty($filterLevel)) {
    if ($filterLevel === 'masters') {
        $courseWhere .= " AND (c.name LIKE '%Master%' OR c.name LIKE '%MSc%' OR c.name LIKE '%MBA%' OR c.name LIKE '%MA %' OR c.name LIKE '%LLM%')";
    } elseif ($filterLevel === 'bachelors') {
        $courseWhere .= " AND (c.name LIKE '%Bachelor%' OR c.name LIKE '%BSc%' OR c.name LIKE '%BBA%' OR c.name LIKE '%BTech%' OR c.name LIKE '%BA %' OR c.name LIKE '%LLB%')";
    } elseif ($filterLevel === 'diploma') {
        $courseWhere .= " AND (c.name LIKE '%Diploma%' OR c.name LIKE '%PGD%' OR c.name LIKE '%Certificate%')";
    } elseif ($filterLevel === 'phd') {
        $courseWhere .= " AND (c.name LIKE '%PhD%' OR c.name LIKE '%Doctor%')";
    }
}
if (!empty($filterBudget)) {
    if ($filterBudget === 'under15k') {
        $courseWhere .= " AND CAST(REPLACE(REPLACE(c.tuition_fee, '$', ''), ',', '') AS UNSIGNED) <= 15000 AND CAST(REPLACE(REPLACE(c.tuition_fee, '$', ''), ',', '') AS UNSIGNED) > 0";
    } elseif ($filterBudget === '15k-25k') {
        $courseWhere .= " AND CAST(REPLACE(REPLACE(c.tuition_fee, '$', ''), ',', '') AS UNSIGNED) BETWEEN 15001 AND 25000";
    } elseif ($filterBudget === '25k-35k') {
        $courseWhere .= " AND CAST(REPLACE(REPLACE(c.tuition_fee, '$', ''), ',', '') AS UNSIGNED) BETWEEN 25001 AND 35000";
    } elseif ($filterBudget === 'above35k') {
        $courseWhere .= " AND CAST(REPLACE(REPLACE(c.tuition_fee, '$', ''), ',', '') AS UNSIGNED) > 35000";
    }
}

$orderBy = "ORDER BY c.id DESC";
if ($filterSort === 'name_asc') {
    $orderBy = "ORDER BY c.name ASC";
} elseif ($filterSort === 'fee_asc') {
    $orderBy = "ORDER BY CAST(REPLACE(REPLACE(c.tuition_fee, '$', ''), ',', '') AS UNSIGNED) ASC, c.id DESC";
} elseif ($filterSort === 'fee_desc') {
    $orderBy = "ORDER BY CAST(REPLACE(REPLACE(c.tuition_fee, '$', ''), ',', '') AS UNSIGNED) DESC, c.id DESC";
}

// Fetch active countries for filter
$filterCountries = [];
try {
    $stmt = $pdo->query("SELECT DISTINCT co.name FROM countries co JOIN universities u ON u.country_id = co.id JOIN courses c ON c.university_id = u.id WHERE c.is_active = 1 AND u.is_active = 1 ORDER BY co.name");
    $filterCountries = $stmt->fetchAll(PDO::FETCH_COLUMN);
} catch (PDOException $e) {}

// Fetch Popular Courses
$popularCourses = [];
try {
    $stmt = $pdo->prepare("
        SELECT c.id as course_id, c.name as course_name, u.name as university_name, 
               co.name as country_name, c.duration, c.tuition_fee, c.intakes
        FROM courses c
        JOIN universities u ON c.university_id = u.id
        LEFT JOIN countries co ON u.country_id = co.id
        $courseWhere
        $orderBy LIMIT 60
    ");
    $stmt->execute($courseParams);
    $popularCourses = $stmt->fetchAll();
} catch (PDOException $e) {}

// Fetch Community Posts
$communityPosts = [];
try {
    $stmt = $pdo->query("
        SELECT * 
        FROM community_posts 
        WHERE is_active = 1 
        ORDER BY created_at DESC
    ");
    $communityPosts = $stmt->fetchAll();
} catch (PDOException $e) {}

// Fetch Community Groups
$communityGroups = [];
$groupUnreadCounts = [];
$totalGroupUnreadCount = 0;
try {
    $stmt = $pdo->prepare("
        SELECT g.*,
               (SELECT COUNT(*) FROM community_group_members gm WHERE gm.group_id = g.id) AS member_count,
               EXISTS(
                   SELECT 1 FROM community_group_members mine
                   WHERE mine.group_id = g.id AND mine.student_id = ?
               ) AS is_member
        FROM community_groups g
        WHERE g.is_active = 1
        ORDER BY g.created_at DESC
    ");
    $stmt->execute([(int) $_SESSION['user_id']]);
    $communityGroups = $stmt->fetchAll();
} catch (PDOException $e) {
    error_log('Student community groups lookup failed: ' . $e->getMessage());
}
try {
    $groupUnreadStmt = $pdo->prepare("
        SELECT gm.group_id, COUNT(m.id) AS unread_count
        FROM community_group_members gm
        LEFT JOIN student_group_message_reads read_cursor
            ON read_cursor.user_id = gm.student_id AND read_cursor.group_id = gm.group_id
        LEFT JOIN community_group_messages m
            ON m.group_id = gm.group_id
           AND m.id > COALESCE(read_cursor.last_read_message_id, 0)
           AND m.user_id <> gm.student_id
           AND m.message_type = 'message'
        WHERE gm.student_id = ?
        GROUP BY gm.group_id
    ");
    $groupUnreadStmt->execute([(int) $_SESSION['user_id']]);
    foreach ($groupUnreadStmt->fetchAll(PDO::FETCH_ASSOC) as $unreadRow) {
        $count = (int) $unreadRow['unread_count'];
        $groupUnreadCounts[(int) $unreadRow['group_id']] = $count;
        $totalGroupUnreadCount += $count;
    }
} catch (PDOException $e) {
    error_log('Student group unread counts lookup failed: ' . $e->getMessage());
}

// Fetch notification count
$unreadNotifCount = 0;
try {
    $notifStmt = $pdo->prepare("SELECT COUNT(*) FROM notifications WHERE user_id = ? AND is_read = 0");
    $notifStmt->execute([$_SESSION['user_id']]);
    $unreadNotifCount = (int) $notifStmt->fetchColumn();
} catch (PDOException $e) {}

// Dynamic Candidate Document Synchronization & Real-time CRM Stats
require_once __DIR__ . '/includes/crm_document_flow.php';

$docStats = [
    'total' => 11,
    'total_uploaded' => 0,
    'verified' => 0,
    'under_review' => 0,
    'needs_reupload' => 0,
    'pending' => 11,
    'completion_percentage' => 0
];
$candidateDocsData = null;
try {
    $candidateDocsData = sync_and_calculate_crm_candidate_documents($pdo, (int)$_SESSION['user_id'], $crmLeadId);
    if (!empty($candidateDocsData['stats'])) {
        $docStats = $candidateDocsData['stats'];
    }
} catch (Exception $e) {
    error_log('Error loading dynamic documents in student-dashboard: ' . $e->getMessage());
}
$documentCount = (int)($docStats['total_uploaded'] ?? 0);
$docVerifiedCount = (int)($docStats['verified'] ?? 0);
$docUnderReviewCount = (int)($docStats['under_review'] ?? 0);
$docNeedsReuploadCount = (int)($docStats['needs_reupload'] ?? 0);
$docTotalCount = (int)($docStats['total'] ?? 11);
$docCompletionPct = (int)($docStats['completion_percentage'] ?? 0);

// Fetch shortlist count (server-side)
$shortlistCount = 0;
try {
    $slCountStmt = $pdo->prepare("SELECT COUNT(*) FROM student_shortlist WHERE user_id = ?");
    $slCountStmt->execute([$_SESSION['user_id']]);
    $shortlistCount = (int) $slCountStmt->fetchColumn();
} catch (PDOException $e) {}

// Timeline & Stepper Logic
$statusLower = strtolower(trim((string)$currentStatus));
$pipelineLower = strtolower(trim((string)$pipelineStatus));

$isCounseling = in_array($statusLower, ['contacted', 'shortlisted', 'in progress', 'documents submitted', 'document collection', 'applied', 'university applied', 'offer received', 'visa approved', 'cas / i-20 processing', 'visa processing', 'completed']);
$isDocs = in_array($statusLower, ['documents submitted', 'document collection', 'applied', 'university applied', 'offer received', 'visa approved', 'cas / i-20 processing', 'visa processing', 'completed']);
$isDecision = in_array($statusLower, ['offer received', 'approved', 'conditional offer', 'unconditional offer', 'visa approved', 'cas / i-20 processing', 'visa processing', 'completed']);
$isVisaStage = in_array($statusLower, ['visa approved', 'cas / i-20 processing', 'visa processing', 'completed']);
$isCompletedStage = in_array($statusLower, ['completed', 'visa approved']);

// Determine active step index (0 to 5) for 6-step Journey Stepper
$journeyStepIndex = 0; // Default: Profile Setup
if ($isCompletedStage) {
    $journeyStepIndex = 5; // Visa & Pre-Departure
} elseif ($isVisaStage) {
    $journeyStepIndex = 5; // Visa & Pre-Departure (Active)
} elseif ($isDecision) {
    $journeyStepIndex = 4; // Offer & Acceptance
} elseif (in_array($statusLower, ['applied', 'university applied'])) {
    $journeyStepIndex = 3; // University Applied
} elseif ($isDocs || $documentCount >= 3) {
    $journeyStepIndex = 2; // Document Verification
} elseif ($isCounseling || $counselor !== 'Pending Assignment') {
    $journeyStepIndex = 1; // Counseling & Shortlisting
}

$journeySteps = [
    [
        'title' => 'Profile Ready',
        'desc' => $profileCompletion >= 100 ? '100% completed' : $profileCompletion . '% completed',
        'icon' => 'fa-user-check'
    ],
    [
        'title' => 'Counseling',
        'desc' => $counselor !== 'Pending Assignment' ? htmlspecialchars($counselor) : 'Advisor matching',
        'icon' => 'fa-comments'
    ],
    [
        'title' => 'Documentation',
        'desc' => $documentCount > 0 ? $documentCount . ' uploaded' : 'Awaiting upload',
        'icon' => 'fa-file-shield'
    ],
    [
        'title' => 'University Applied',
        'desc' => $hasApplication ? htmlspecialchars($university) : 'Target selection',
        'icon' => 'fa-paper-plane'
    ],
    [
        'title' => 'Offer & Decision',
        'desc' => $isDecision ? 'Offer Issued' : 'Under Review',
        'icon' => 'fa-award'
    ],
    [
        'title' => 'Visa & Departure',
        'desc' => $isCompletedStage ? 'Visa Approved' : ($isVisaStage ? 'In Process' : 'Upcoming'),
        'icon' => 'fa-plane-departure'
    ],
];

// Counselor Contact Details
$counselorName = $counselor;
$isCounselorAssigned = ($counselor !== 'Pending Assignment' && !empty($counselor));
$counselorPhone = defined('SITE_PHONE') ? SITE_PHONE : '+91 93428 99904';
$counselorCleanPhone = preg_replace('/[^0-9]/', '', $counselorPhone);
$counselorEmail = defined('SITE_EMAIL') ? SITE_EMAIL : 'info@bluestoneocs.com';
$waMessage = rawurlencode("Hi Bluestone Overseas, I am " . $userName . " (Student ID: " . ($_SESSION['user_id'] ?? '') . "). I would like to consult with my counselor " . ($isCounselorAssigned ? $counselorName : 'regarding my study abroad application') . ".");
$counselorWaUrl = "https://wa.me/" . ($counselorCleanPhone ?: '919342899904') . "?text=" . $waMessage;

// Next Action Checklist Items
$checklistItems = [
    [
        'id' => 'profile',
        'title' => 'Complete Your Academic Profile',
        'desc' => $profileCompletion < 100 ? 'Fill in personal details, target intake, and study interests (' . $profileCompletion . '% complete).' : 'Your profile details and study preferences are fully up to date.',
        'target' => 'view-profile',
        'action_label' => $profileCompletion < 100 ? 'Complete Profile' : 'View Profile',
        'is_completed' => ($profileCompletion >= 100),
        'icon' => 'fa-id-card',
        'badge' => $profileCompletion < 100 ? 'Needs Attention' : 'Completed',
        'badge_class' => $profileCompletion < 100 ? 'dash-badge-amber' : 'dash-badge-green'
    ],
    [
        'id' => 'shortlist',
        'title' => 'Shortlist 3+ Universities & Courses',
        'desc' => $shortlistCount > 0 ? 'You have saved ' . $shortlistCount . ' course' . ($shortlistCount > 1 ? 's' : '') . ' to your shortlist.' : 'Explore 50+ global universities and heart courses matching your budget and career goals.',
        'target' => ($shortlistCount > 0 ? 'view-shortlisted' : 'view-courses'),
        'action_label' => ($shortlistCount > 0 ? 'Review Shortlist' : 'Explore Courses'),
        'is_completed' => ($shortlistCount >= 1),
        'icon' => 'fa-heart',
        'badge' => ($shortlistCount >= 1 ? 'Shortlisted' : 'Action Required'),
        'badge_class' => ($shortlistCount >= 1 ? 'dash-badge-green' : 'dash-badge-blue')
    ],
    [
        'id' => 'documents',
        'title' => 'Upload Required Application Documents',
        'desc' => $documentCount > 0 ? $documentCount . ' document' . ($documentCount > 1 ? 's' : '') . ' uploaded to your CRM portal.' : 'Submit your passport scan, academic transcripts, and statement of purpose (SOP).',
        'target' => 'view-documents',
        'action_label' => ($documentCount > 0 ? 'Manage Documents' : 'Upload Now'),
        'is_completed' => ($documentCount >= 1),
        'icon' => 'fa-file-arrow-up',
        'badge' => ($documentCount >= 1 ? 'Uploaded (' . $documentCount . ')' : 'High Priority'),
        'badge_class' => ($documentCount >= 1 ? 'dash-badge-green' : 'dash-badge-red')
    ],
    [
        'id' => 'counselor',
        'title' => 'Connect With Your Assigned Advisor',
        'desc' => $isCounselorAssigned ? 'Your counselor is ' . htmlspecialchars($counselorName) . '. Discuss target intake, scholarships, and SOP guidance.' : 'Our counseling desk is assigning your dedicated study abroad specialist.',
        'target' => 'view-application',
        'action_label' => 'Counselor Info',
        'is_completed' => $isCounselorAssigned,
        'icon' => 'fa-user-tie',
        'badge' => ($isCounselorAssigned ? 'Assigned' : 'In Progress'),
        'badge_class' => ($isCounselorAssigned ? 'dash-badge-green' : 'dash-badge-amber')
    ],
    [
        'id' => 'application',
        'title' => 'Track University Decision & Offer Letter',
        'desc' => $hasApplication ? 'Target Program: ' . htmlspecialchars($university) . ' • Status: ' . htmlspecialchars($currentStatus) : 'Submit your application preferences to initiate formal university filing.',
        'target' => 'view-application',
        'action_label' => 'View Application',
        'is_completed' => $hasApplication,
        'icon' => 'fa-graduation-cap',
        'badge' => ($hasApplication ? htmlspecialchars($currentStatus) : 'Pending Submission'),
        'badge_class' => ($hasApplication ? 'dash-badge-green' : 'dash-badge-neutral')
    ]
];

$completedChecklistCount = 0;
foreach ($checklistItems as $chk) {
    if ($chk['is_completed']) $completedChecklistCount++;
}
$checklistPercent = (int)round(($completedChecklistCount / count($checklistItems)) * 100);

// Recommended courses for Dashboard preview (matching student interest or top popular)
$recommendedCourses = [];
if (!empty($userInterestDestinations) || !empty($userInterestSubjects)) {
    foreach ($popularCourses as $pc) {
        $match = false;
        if (!empty($userInterestDestinations) && stripos($pc['country_name'] ?? '', $userInterestDestinations) !== false) {
            $match = true;
        }
        if (!empty($userInterestSubjects) && stripos($pc['course_name'] ?? '', $userInterestSubjects) !== false) {
            $match = true;
        }
        if ($match) {
            $recommendedCourses[] = $pc;
        }
        if (count($recommendedCourses) >= 3) break;
    }
}
if (count($recommendedCourses) < 3) {
    foreach ($popularCourses as $pc) {
        $alreadyIn = false;
        foreach ($recommendedCourses as $rc) {
            if (($rc['course_name'] ?? '') === ($pc['course_name'] ?? '') && ($rc['university_name'] ?? '') === ($pc['university_name'] ?? '')) {
                $alreadyIn = true;
                break;
            }
        }
        if (!$alreadyIn) {
            $recommendedCourses[] = $pc;
        }
        if (count($recommendedCourses) >= 3) break;
    }
}

$activeTab = $_GET['tab'] ?? 'dashboard';
if (isset($_GET['profile_updated']) || isset($_GET['interests_updated'])) $activeTab = 'profile';
if (isset($_GET['documents'])) $activeTab = 'documents';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard - Bluestone Overseas</title>
    <script>
        // Disable browser automatic scroll restoration immediately
        if ('scrollRestoration' in history) {
            history.scrollRestoration = 'manual';
        }
        window.scrollTo(0, 0);
        document.documentElement.scrollTop = 0;
        document.body.scrollTop = 0;
        window.addEventListener('pageshow', function() {
            window.scrollTo(0, 0);
            document.documentElement.scrollTop = 0;
            document.body.scrollTop = 0;
        });
        window.addEventListener('load', function() {
            window.scrollTo(0, 0);
            document.documentElement.scrollTop = 0;
            document.body.scrollTop = 0;
        });
    </script>
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --bs-primary: #1d4ed8; /* Blue */
            --bs-dark: #1e1e1e; /* Header color */
            --bs-bg: #f4f5f7; /* Light gray background */
            --bs-text: #333333;
            --bs-text-light: #6b7280;
            --bs-border: #e5e7eb;
            --bs-accent: #f59e0b; /* Orange */
            --bs-white: #ffffff;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Inter', sans-serif;
            overflow-anchor: none !important;
        }

        html {
            overflow-anchor: none !important;
            scroll-behavior: auto !important;
        }

        body {
            background-color: var(--bs-bg);
            color: var(--bs-text);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            overflow-anchor: none !important;
        }

        .main-container, .content-area, .tab-view {
            overflow-anchor: none !important;
        }

        .container {
            width: 100%;
            max-width: 1300px;
            margin: 0 auto;
            padding: 0 20px;
        }

        /* Top Header */
        .dash-header {
            background-color: var(--bs-dark);
            height: 82px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 40px;
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .dash-header-logo img {
            width: 200px;
            height: 72px;
            max-width: none;
            object-fit: cover;
            object-position: center 53%;
            filter: brightness(0) invert(1);
        }

        .dash-header-right {
            display: flex;
            align-items: center;
            gap: 24px;
        }

        .back-link {
            color: #9ca3af;
            text-decoration: none;
            font-size: 14px;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: color 0.2s;
        }

        .back-link:hover {
            color: var(--bs-white);
        }

        .user-avatar {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background-color: #10b981; /* Greenish */
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
            font-weight: 600;
        }

        /* Layout Container */
        .main-container {
            display: flex;
            max-width: 1300px;
            margin: 40px auto;
            padding: 0 20px;
            width: 100%;
            gap: 32px;
            flex: 1;
        }

        /* Sidebar */
        .sidebar {
            width: 250px;
            background: var(--bs-white);
            border-radius: 8px;
            padding: 16px 0;
            box-shadow: 0 2px 10px rgba(0,0,0,0.03);
            border: 1px solid var(--bs-border);
            height: max-content;
            position: sticky;
            top: 24px;
            z-index: 20;
        }

        /* Floating Scroll to Top Pill */
        .btn-scroll-top {
            position: fixed;
            bottom: 24px;
            right: 80px;
            background: #0f172a;
            color: #ffffff;
            width: 42px;
            height: 42px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 14px rgba(0,0,0,0.2);
            border: none;
            cursor: pointer;
            z-index: 99;
            opacity: 0;
            visibility: hidden;
            transform: translateY(12px);
            transition: all 0.25s ease;
        }
        .btn-scroll-top.visible {
            opacity: 0.9;
            visibility: visible;
            transform: translateY(0);
        }
        .btn-scroll-top:hover {
            opacity: 1;
            transform: translateY(-2px);
            background: #2563eb;
        }

        .nav-item {
            display: flex;
            align-items: center;
            gap: 16px;
            padding: 12px 24px;
            color: var(--bs-text);
            text-decoration: none;
            font-weight: 600;
            font-size: 14px;
            transition: all 0.2s;
        }

        .nav-item i {
            font-size: 16px;
            width: 20px;
            text-align: center;
            color: var(--bs-text-light);
        }

        .nav-item:hover {
            background-color: #f9fafb;
        }

        .nav-item.active {
            background-color: #2563eb;
            color: var(--bs-white);
            margin: 0 16px;
            border-radius: 6px;
            padding: 12px 16px;
        }

        .nav-item.active i {
            color: var(--bs-white);
        }

        /* Tabs */
        .tab-view {
            display: none;
            animation: fadeIn 0.3s ease;
        }
        
        .tab-view.active-view {
            display: block;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(5px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* Courses View */
        .courses-header {
            margin-bottom: 24px;
        }

        .courses-header h2 {
            font-size: 22px;
            font-weight: 700;
            color: var(--bs-text);
            margin-bottom: 8px;
        }

        .courses-header p {
            font-size: 14px;
            color: var(--bs-text-light);
            line-height: 1.5;
        }

        .courses-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
            padding-bottom: 20px;
        }

        @media (max-width: 992px) {
            .courses-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }
        
        @media (max-width: 768px) {
            .courses-grid {
                grid-template-columns: 1fr;
            }
        }

        .course-card {
            background: var(--bs-white);
            border: 1px solid var(--bs-border);
            border-radius: 8px;
            padding: 20px;
            display: flex;
            flex-direction: column;
            transition: border-color 0.2s;
            position: relative;
        }
        
        .course-card:hover {
            border-color: #cbd5e1;
            box-shadow: 0 4px 15px rgba(0,0,0,0.03);
        }

        .course-card-fav {
            position: absolute;
            top: 20px;
            right: 20px;
            color: #94a3b8;
            cursor: pointer;
            font-size: 18px;
        }

        .course-card-fav:hover {
            color: #ef4444;
        }

        .course-card h3 {
            font-size: 18px;
            font-weight: 700;
            color: var(--bs-text);
            margin-bottom: 12px;
            padding-right: 24px;
            line-height: 1.3;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            height: 46px; /* fixed height for 2 lines */
        }

        .course-card-uni {
            font-size: 11px;
            font-weight: 600;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 16px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .course-card hr {
            border: none;
            border-top: 1px solid var(--bs-border);
            margin-bottom: 16px;
        }

        .course-details-list {
            list-style: none;
            padding: 0;
            margin: 0 0 20px 0;
            flex: 1;
        }

        .course-details-list li {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 13px;
            color: #475569;
            margin-bottom: 10px;
        }
        
        .course-details-list li i {
            color: #94a3b8;
            width: 14px;
            text-align: center;
        }

        .btn-view-details {
            width: 100%;
            background: var(--bs-white);
            border: 1px solid var(--bs-text);
            color: var(--bs-text);
            padding: 10px;
            border-radius: 20px;
            font-weight: 600;
            font-size: 14px;
            cursor: pointer;
            transition: all 0.2s;
            text-align: center;
            text-decoration: none;
        }

        .btn-view-details:hover {
            background: #f8fafc;
        }

        /* Main Content */
        .content-area {
            flex: 1;
        }

        .greeting {
            font-size: 28px;
            font-weight: 500;
            color: var(--bs-text);
            margin-bottom: 24px;
            position: relative;
            padding-bottom: 8px;
        }

        .greeting::after {
            content: '';
            position: absolute;
            left: 0;
            bottom: 0;
            width: 30px;
            height: 4px;
            background-color: var(--bs-accent);
            border-radius: 2px;
        }

        /* Courses Grid */
        .courses-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(340px, 1fr));
            gap: 24px;
            margin-bottom: 30px;
        }

        /* Cards Grid */
        .cards-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
            margin-bottom: 24px;
        }

        .dash-card {
            background: var(--bs-white);
            border-radius: 8px;
            padding: 24px;
            border: 1px solid var(--bs-border);
            box-shadow: 0 2px 10px rgba(0,0,0,0.02);
            display: flex;
            flex-direction: column;
        }

        .dash-card-icon {
            font-size: 24px;
            color: #3b82f6; /* Light Blue */
            margin-bottom: 16px;
        }

        .dash-card-title {
            font-size: 18px;
            font-weight: 500;
            color: var(--bs-text);
            margin-bottom: 8px;
        }

        .dash-card-subtitle {
            font-size: 13px;
            color: var(--bs-text-light);
            margin-bottom: 24px;
            flex: 1;
        }

        .dash-card-link {
            font-size: 13px;
            color: #3b82f6;
            text-decoration: none;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 4px;
        }

        .dash-card-link:hover {
            text-decoration: underline;
        }

        /* Banner */
        .promo-banner {
            background-color: #e0f2fe; /* Light blue tint */
            border-radius: 8px;
            padding: 20px 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .promo-content {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .promo-icon {
            width: 48px;
            height: 48px;
            background: var(--bs-white);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            color: #2563eb;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        }

        .promo-text h4 {
            font-size: 16px;
            font-weight: 600;
            color: var(--bs-text);
            margin-bottom: 4px;
        }

        .promo-text p {
            font-size: 13px;
            color: #4b5563;
        }

        .promo-actions {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .btn-outline {
            padding: 8px 16px;
            border: 1px solid #9ca3af;
            border-radius: 20px;
            background: transparent;
            color: #374151;
            font-size: 13px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s;
        }

        .btn-outline:hover {
            background: rgba(0,0,0,0.05);
        }

        .btn-close {
            background: transparent;
            border: none;
            color: #6b7280;
            cursor: pointer;
            font-size: 16px;
        }

        /* Footer */
        .dash-footer {
            background-color: var(--bs-dark);
            padding: 30px 40px;
            color: var(--bs-white);
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-top: auto;
        }
        
        .dash-footer-left {
            display: flex;
            align-items: center;
            gap: 20px;
        }
        
        .dash-footer-left img {
            height: 40px;
            filter: brightness(0) invert(1);
        }
        
        .dash-footer-left span {
            font-size: 12px;
            color: #9ca3af;
            max-width: 150px;
            line-height: 1.4;
        }

        .dash-footer-right .btn-dark {
            border: 1px solid #4b5563;
            background: transparent;
            color: white;
            padding: 10px 20px;
            border-radius: 20px;
            font-size: 14px;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        @media (max-width: 992px) {
            .main-container {
                flex-direction: column;
            }
            .sidebar {
                width: 100%;
                position: static;
                top: auto;
            }
            .cards-grid {
                grid-template-columns: 1fr;
            }
            .promo-banner {
                flex-direction: column;
                align-items: flex-start;
                gap: 16px;
            }
            .promo-actions {
                width: 100%;
                justify-content: space-between;
            }
            .crm-section {
                grid-template-columns: 1fr;
            }
        }

        /* CRM Details Section */
        .crm-section {
            margin-top: 40px;
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 24px;
        }

        .crm-panel {
            background: var(--bs-white);
            border-radius: 8px;
            padding: 24px;
            border: 1px solid var(--bs-border);
            box-shadow: 0 2px 10px rgba(0,0,0,0.02);
        }

        .crm-panel-title {
            font-size: 18px;
            font-weight: 600;
            color: var(--bs-text);
            margin-bottom: 20px;
            padding-bottom: 12px;
            border-bottom: 1px solid var(--bs-border);
        }

        /* Timeline */
        .timeline {
            position: relative;
            padding-left: 20px;
        }

        .timeline::before {
            content: '';
            position: absolute;
            left: 5px;
            top: 5px;
            bottom: 0;
            width: 2px;
            background: var(--bs-border);
        }

        .timeline-item {
            position: relative;
            padding-bottom: 24px;
        }

        .timeline-item:last-child {
            padding-bottom: 0;
        }

        .timeline-dot {
            position: absolute;
            left: -20px;
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background: var(--bs-white);
            border: 2px solid var(--bs-border);
            top: 4px;
            z-index: 2;
        }

        .timeline-item.completed .timeline-dot {
            border-color: #10b981;
            background: #10b981;
        }

        .timeline-item.active .timeline-dot {
            border-color: var(--bs-primary);
            background: var(--bs-white);
            box-shadow: 0 0 0 4px #e0f2fe;
        }

        .timeline-content h4 {
            font-size: 15px;
            font-weight: 600;
            color: var(--bs-text);
            margin-bottom: 4px;
        }

        .timeline-content p {
            font-size: 13px;
            color: var(--bs-text-light);
        }

        .app-detail-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 12px;
            font-size: 14px;
        }

        .app-detail-label {
            color: var(--bs-text-light);
        }

        .app-detail-value {
            font-weight: 600;
            color: var(--bs-text);
            text-align: right;
            max-width: 60%;
        }

        .remarks-heading {
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
        }

        .remarks-history-wrap {
            position: relative;
        }

        .remarks-history-button {
            border: 0;
            padding: 4px 0;
            background: transparent;
            color: var(--bs-primary);
            font-size: 12px;
            font-weight: 600;
            cursor: pointer;
        }

        .remarks-history-button:hover,
        .remarks-history-button:focus-visible {
            text-decoration: underline;
        }

        .remarks-history-preview {
            display: none;
            position: absolute;
            z-index: 20;
            right: 0;
            top: calc(100% + 8px);
            width: min(380px, calc(100vw - 48px));
            max-height: 280px;
            overflow-y: auto;
            padding: 10px;
            border: 1px solid var(--bs-border);
            border-radius: 8px;
            background: white;
            box-shadow: 0 8px 24px rgba(15, 23, 42, 0.16);
        }

        .remarks-history-wrap:hover .remarks-history-preview,
        .remarks-history-wrap:focus-within .remarks-history-preview {
            display: block;
        }

        .remarks-history-preview-item {
            padding: 9px 8px;
            border-bottom: 1px solid #eef2f7;
            white-space: pre-wrap;
            overflow-wrap: anywhere;
            font-size: 12px;
            line-height: 1.45;
        }

        .remarks-history-preview-item:last-child {
            border-bottom: 0;
        }

        .remarks-history-meta {
            display: block;
            margin-bottom: 4px;
            color: #64748b;
            font-size: 11px;
        }

        .remarks-history-dialog {
            position: fixed;
            inset: 0;
            z-index: 10000;
            display: none;
            align-items: center;
            justify-content: center;
            padding: 20px;
            background: rgba(15, 23, 42, 0.55);
        }

        .remarks-history-dialog.is-open {
            display: flex;
        }

        .remarks-history-dialog-content {
            width: min(850px, 100%);
            max-height: min(80vh, 720px);
            overflow: hidden;
            border-radius: 12px;
            background: white;
            box-shadow: 0 20px 60px rgba(15, 23, 42, 0.25);
        }

        .remarks-history-dialog-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            padding: 18px 22px;
            border-bottom: 1px solid var(--bs-border);
        }

        .remarks-history-dialog-header h3 {
            font-size: 17px;
            color: var(--bs-text);
        }

        .remarks-history-close {
            border: 0;
            background: transparent;
            color: #64748b;
            font-size: 24px;
            cursor: pointer;
        }

        .remarks-history-table-wrap {
            max-height: calc(min(80vh, 720px) - 70px);
            overflow: auto;
        }

        .remarks-history-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 13px;
        }

        .remarks-history-table th,
        .remarks-history-table td {
            padding: 12px 14px;
            border-bottom: 1px solid #eef2f7;
            text-align: left;
            vertical-align: top;
        }

        .remarks-history-table th {
            position: sticky;
            top: 0;
            background: #f8fafc;
            color: #475569;
            font-weight: 600;
        }

        .remarks-history-table td.remark-text {
            min-width: 260px;
            white-space: pre-wrap;
            overflow-wrap: anywhere;
        }

        /* Profile Tabs & Edit Modes */
        .profile-tab-content { display: none; }
        .profile-tab-content.active-content { display: block; animation: fadeIn 0.3s ease; }
        
        .profile-edit-view { display: none; }
        .profile-edit-view.active-edit { display: block; animation: fadeIn 0.3s ease; }
        
        .profile-display-view { display: block; }
        .profile-display-view.hidden-display { display: none; }
        
        .profile-input {
            width: 100%;
            padding: 10px 12px;
            border: 1px solid var(--bs-border);
            border-radius: 6px;
            font-size: 14px;
            color: var(--bs-text);
            margin-top: 6px;
            background-color: var(--bs-white);
            transition: border-color 0.2s;
        }
        
        .profile-input:focus {
            outline: none;
            border-color: #2563eb;
            box-shadow: 0 0 0 3px rgba(37,99,235,0.1);
        }
        
        .btn-save {
            background-color: #10b981;
            color: white;
            border: none;
            padding: 8px 24px;
            border-radius: 20px;
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
            transition: background 0.2s;
        }
        
        .btn-save:hover {
            background-color: #059669;
        }

        /* Profile Layout */
        .profile-layout {
            display: flex;
            gap: 32px;
            margin-top: 24px;
        }

        .profile-sidebar {
            width: 150px;
            flex-shrink: 0;
            display: flex;
            flex-direction: column;
            gap: 16px;
        }

        .profile-tab {
            font-size: 14px;
            color: var(--bs-text-light);
            cursor: pointer;
            padding: 8px 16px;
            position: relative;
        }

        .profile-tab.active {
            color: #2563eb;
            font-weight: 600;
        }

        .profile-tab.active::before {
            content: '';
            position: absolute;
            left: 0;
            top: 5px;
            bottom: 5px;
            width: 3px;
            background-color: #2563eb;
            border-radius: 4px;
        }

        .profile-main {
            flex: 1;
            min-width: 0;
        }

        .profile-card {
            background: var(--bs-white);
            border: 1px solid var(--bs-border);
            border-radius: 12px;
            padding: 32px;
        }

        .profile-card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 32px;
        }

        .profile-card-header h2 {
            font-size: 20px;
            font-weight: 600;
            color: var(--bs-text);
        }

        .btn-edit {
            background-color: #1d4ed8;
            color: white;
            border: none;
            padding: 8px 24px;
            border-radius: 20px;
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
            transition: background 0.2s;
        }

        .btn-edit:hover {
            background-color: #1e40af;
        }

        .profile-fields {
            display: flex;
            flex-direction: column;
            gap: 24px;
        }

        .field-group label {
            display: block;
            font-size: 14px;
            font-weight: 600;
            color: var(--bs-text);
            margin-bottom: 8px;
        }

        .field-group .value {
            font-size: 14px;
            color: var(--bs-text-light);
        }

        .field-row {
            display: grid;
            grid-template-columns: 100px 1fr;
            gap: 24px;
        }

        .profile-promo {
            width: 280px;
            flex-shrink: 0;
        }

        .promo-card-vertical {
            background-color: #eef2ff;
            border-radius: 16px;
            padding: 24px;
            position: relative;
            display: flex;
            flex-direction: column;
            gap: 16px;
        }

        .btn-close-promo {
            position: absolute;
            top: 16px;
            right: 16px;
            background: transparent;
            border: none;
            color: #6b7280;
            cursor: pointer;
            font-size: 16px;
        }

        .promo-icon-large {
            width: 48px;
            height: 48px;
            background: var(--bs-white);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            color: #2563eb;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        }

        .promo-card-vertical h3 {
            font-size: 16px;
            font-weight: 700;
            color: var(--bs-text);
            line-height: 1.4;
        }

        .promo-card-vertical p {
            font-size: 13px;
            color: #4b5563;
            line-height: 1.5;
        }

        .btn-outline-promo {
            padding: 8px 16px;
            border: 1px solid #9ca3af;
            border-radius: 20px;
            background: transparent;
            color: #374151;
            font-size: 13px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s;
            margin-top: 8px;
            text-align: center;
        }

        .btn-outline-promo:hover {
            background: rgba(0,0,0,0.05);
        }

        @media (max-width: 1024px) {
            .profile-layout {
                flex-direction: column;
            }
            .profile-sidebar {
                flex-direction: row;
                width: 100%;
                border-bottom: 1px solid var(--bs-border);
            }
            .profile-tab.active::before {
                top: auto;
                left: 16px;
                right: 16px;
                bottom: 0;
                width: auto;
                height: 3px;
            }
            .profile-promo {
                width: 100%;
            }
        }

        /* Community View */
        .comm-tabs {
            display: flex;
            align-items: center;
            gap: 32px;
            overflow-x: auto;
            border-bottom: 1px solid var(--bs-border);
            margin-bottom: 24px;
            scrollbar-width: thin;
        }
        .comm-tab-btn {
            flex: 0 0 auto;
            background: none;
            border: none;
            padding: 12px 0;
            font-size: 15px;
            font-weight: 500;
            color: var(--bs-text-light);
            cursor: pointer;
            position: relative;
        }
        .comm-tab-btn.active {
            color: #2563eb;
        }
        .comm-tab-btn.active::after {
            content: '';
            position: absolute;
            bottom: -1px;
            left: 0;
            width: 100%;
            height: 2px;
            background: #2563eb;
        }
        .comm-filter-bar {
            margin-bottom: 24px;
            background: white;
            padding: 12px 16px;
            border-radius: 8px;
            border: 1px solid var(--bs-border);
        }
        .community-composer {
            background: white;
            border: 1px solid var(--bs-border);
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 24px;
        }
        .community-composer h3 {
            font-size: 18px;
            margin-bottom: 14px;
        }
        .community-composer textarea,
        .community-search-input {
            width: 100%;
            border: 1px solid #cbd5e1;
            border-radius: 8px;
            padding: 12px;
            font: inherit;
            color: var(--bs-text);
        }
        .community-composer textarea {
            min-height: 96px;
            resize: vertical;
            margin-bottom: 12px;
        }
        .community-composer-actions {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            flex-wrap: wrap;
        }
        .community-composer-actions label {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            color: var(--bs-text);
            font-size: 14px;
            cursor: pointer;
        }
        .community-submit {
            border: 0;
            border-radius: 8px;
            padding: 10px 18px;
            background: #2563eb;
            color: white;
            font-weight: 600;
            cursor: pointer;
        }
        .community-submit:disabled {
            opacity: 0.65;
            cursor: wait;
        }
        .community-feedback {
            margin-top: 10px;
            font-size: 14px;
        }
        .community-feedback.error {
            color: #b91c1c;
        }
        .community-feedback.success {
            color: #047857;
        }
        .community-media-preview {
            display: none;
            max-width: 100%;
            max-height: 280px;
            margin-top: 12px;
            border-radius: 8px;
        }
        .community-search-row {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 24px;
        }
        .community-search-wrap {
            position: relative;
            flex: 1;
        }
        .community-search-wrap i {
            position: absolute;
            left: 13px;
            top: 50%;
            transform: translateY(-50%);
            color: #64748b;
        }
        .community-search-input {
            padding-left: 38px;
        }
        .community-no-results {
            display: none;
            grid-column: 1 / -1;
            text-align: center;
            padding: 32px;
            color: var(--bs-text-light);
        }
        .dreamwall-hero {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px;
            margin-bottom: 22px;
            padding: 28px;
            border-radius: 14px;
            background: linear-gradient(120deg, #102b57, #2563a6 65%, #3989a2);
            color: #fff;
        }
        .dreamwall-hero h2 { margin: 0 0 8px; font-size: clamp(20px, 3vw, 28px); }
        .dreamwall-hero p { max-width: 720px; margin: 0; color: #e2edff; font-size: 14px; line-height: 1.6; }
        .dreamwall-share-button,
        .dreamwall-modal-submit {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            border: 0;
            border-radius: 9px;
            padding: 11px 16px;
            background: #f59e0b;
            color: #1f2937;
            font: inherit;
            font-size: 13px;
            font-weight: 700;
            text-decoration: none;
            cursor: pointer;
            white-space: nowrap;
        }
        .dreamwall-share-button:hover,
        .dreamwall-modal-submit:hover { background: #fbbf24; }
        .dreamwall-filter-row {
            display: flex;
            gap: 9px;
            margin-bottom: 20px;
            overflow-x: auto;
            padding: 2px 0 5px;
        }
        .dreamwall-filter {
            flex: 0 0 auto;
            border: 1px solid #dbe3ee;
            border-radius: 20px;
            padding: 8px 14px;
            background: #fff;
            color: #475569;
            font: inherit;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
        }
        .dreamwall-filter.active { border-color: #1d4ed8; background: #eff6ff; color: #1d4ed8; }
        .dreamwall-grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            align-items: start;
            gap: 18px;
        }
        .dreamwall-card {
            min-width: 0;
            overflow: hidden;
            border: 1px solid #e2e8f0;
            border-radius: 13px;
            background: #fff;
            box-shadow: 0 5px 16px rgba(15, 23, 42, .05);
        }
        .dreamwall-card-top { display: flex; align-items: center; gap: 11px; padding: 17px 17px 12px; }
        .dreamwall-avatar {
            display: grid;
            width: 44px;
            height: 44px;
            flex: 0 0 44px;
            place-items: center;
            overflow: hidden;
            border-radius: 50%;
            background: #e0e7ff;
            color: #3730a3;
            font-size: 14px;
            font-weight: 800;
        }
        .dreamwall-avatar img { width: 100%; height: 100%; object-fit: cover; }
        .dreamwall-student { min-width: 0; flex: 1; }
        .dreamwall-student strong { display: block; overflow: hidden; color: #172033; font-size: 14px; text-overflow: ellipsis; white-space: nowrap; }
        .dreamwall-destination { margin-top: 4px; color: #64748b; font-size: 12px; }
        .dreamwall-badge { display: inline-flex; margin: 0 17px 11px; border-radius: 16px; padding: 5px 10px; background: #e0f2fe; color: #075985; font-size: 11px; font-weight: 700; }
        .dreamwall-badge[data-type="visa approved"] { background: #dcfce7; color: #166534; }
        .dreamwall-badge[data-type="scholarship"] { background: #f3e8ff; color: #7e22ce; }
        .dreamwall-badge[data-type="ielts success"] { background: #f1f5f9; color: #475569; }
        .dreamwall-card-content { padding: 0 17px 15px; }
        .dreamwall-card-content h4 { margin: 0 0 8px; color: #172033; font-size: 16px; line-height: 1.4; }
        .dreamwall-card-content p { margin: 0; color: #526174; font-size: 13px; line-height: 1.6; white-space: pre-line; overflow-wrap: anywhere; }
        .dreamwall-proof { display: block; max-width: 100%; margin-top: 11px; color: #1d4ed8; font-size: 12px; font-weight: 600; }
        .dreamwall-proof-image { display: block; width: min(100%, 360px); max-height: 260px; margin-top: 10px; border: 1px solid #e2e8f0; border-radius: 9px; object-fit: cover; }
        .dreamwall-upload-note { color: #64748b; font-size: 11px; }
        .dreamwall-card-actions { display: flex; align-items: center; justify-content: space-between; gap: 8px; border-top: 1px solid #eef2f7; padding: 12px 17px; }
        .dreamwall-cheer {
            border: 0;
            border-radius: 8px;
            padding: 8px 10px;
            background: #f8fafc;
            color: #475569;
            font: inherit;
            font-size: 12px;
            font-weight: 700;
            cursor: pointer;
        }
        .dreamwall-cheer[aria-pressed="true"] { background: #fef3c7; color: #b45309; }
        .dreamwall-cheer:disabled { opacity: .65; cursor: wait; }
        .dreamwall-feedback { margin: 12px 0; color: #64748b; font-size: 13px; }
        .dreamwall-feedback.error { color: #b91c1c; }
        .dreamwall-modal {
            position: fixed;
            inset: 0;
            z-index: 1300;
            display: grid;
            place-items: center;
            overflow-y: auto;
            padding: 20px;
            background: rgba(15, 23, 42, .62);
        }
        .dreamwall-modal[hidden] { display: none; }
        .dreamwall-modal-card {
            width: min(560px, 100%);
            max-height: min(90vh, 850px);
            overflow-y: auto;
            border-radius: 15px;
            padding: 23px;
            background: #fff;
            box-shadow: 0 24px 70px rgba(15, 23, 42, .28);
        }
        .dreamwall-modal-header { display: flex; align-items: flex-start; justify-content: space-between; gap: 15px; margin-bottom: 18px; }
        .dreamwall-modal-header h3 { margin: 0 0 5px; color: #172033; font-size: 20px; }
        .dreamwall-modal-header p { margin: 0; color: #64748b; font-size: 13px; line-height: 1.5; }
        .dreamwall-modal-close { border: 0; background: transparent; color: #64748b; font-size: 24px; cursor: pointer; }
        .dreamwall-form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 13px; }
        .dreamwall-form-field { display: flex; flex-direction: column; gap: 6px; min-width: 0; }
        .dreamwall-form-field.full { grid-column: 1 / -1; }
        .dreamwall-form-field label { color: #334155; font-size: 12px; font-weight: 700; }
        .dreamwall-form-field input,
        .dreamwall-form-field select,
        .dreamwall-form-field textarea {
            width: 100%;
            min-width: 0;
            border: 1px solid #cbd5e1;
            border-radius: 8px;
            padding: 10px 11px;
            background: #fff;
            color: #172033;
            font: inherit;
            font-size: 13px;
        }
        .dreamwall-form-field textarea { min-height: 95px; resize: vertical; }
        .dreamwall-modal-actions { display: flex; justify-content: flex-end; gap: 10px; margin-top: 17px; }
        .dreamwall-modal-cancel { border: 1px solid #cbd5e1; border-radius: 9px; padding: 10px 14px; background: #fff; color: #475569; font: inherit; font-size: 13px; font-weight: 600; cursor: pointer; }
        .dreamwall-modal-submit:disabled { opacity: .65; cursor: wait; }
        .dreamwall-modal-feedback { min-height: 18px; margin-top: 10px; font-size: 13px; }
        .dreamwall-modal-feedback.error { color: #b91c1c; }
        .dreamwall-modal-feedback.success { color: #047857; }
        @media (max-width: 1050px) { .dreamwall-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); } }
        @media (max-width: 650px) {
            .dreamwall-hero { align-items: flex-start; flex-direction: column; padding: 21px; }
            .dreamwall-grid { grid-template-columns: 1fr; }
            .dreamwall-form-grid { grid-template-columns: 1fr; }
            .dreamwall-form-field.full { grid-column: auto; }
        }
        .comm-filter-select {
            border: none;
            outline: none;
            background: transparent;
            font-size: 14px;
            color: var(--bs-text);
            width: 200px;
        }
        .comm-feed-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 24px;
        }
        @media (max-width: 992px) {
            .comm-feed-grid { grid-template-columns: 1fr; }
        }
        .comm-post-card {
            background: white;
            border: 1px solid var(--bs-border);
            border-radius: 12px;
            overflow: hidden;
        }
        .comm-post-card[hidden] {
            display: none !important;
        }
        .comm-post-header {
            display: flex;
            align-items: center;
            padding: 16px;
            gap: 12px;
            position: relative;
        }
        .comm-post-menu {
            margin-left: auto;
            position: relative;
        }
        .comm-post-menu summary {
            width: 36px;
            height: 36px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            cursor: pointer;
            list-style: none;
            color: #475569;
        }
        .comm-post-menu summary::-webkit-details-marker {
            display: none;
        }
        .comm-post-menu summary:hover {
            background: #f1f5f9;
        }
        .comm-post-menu-items {
            position: absolute;
            top: 40px;
            right: 0;
            z-index: 5;
            min-width: 150px;
            padding: 6px;
            background: white;
            border: 1px solid var(--bs-border);
            border-radius: 10px;
            box-shadow: 0 8px 24px rgba(15, 23, 42, 0.14);
        }
        .comm-post-menu-items button {
            display: block;
            width: 100%;
            border: 0;
            background: transparent;
            padding: 9px 10px;
            text-align: left;
            font: inherit;
            color: var(--bs-text);
            border-radius: 6px;
            cursor: pointer;
        }
        .comm-post-menu-items button:hover {
            background: #f1f5f9;
        }
        .comm-post-menu-items .comm-post-delete {
            color: #b91c1c;
        }
        .comm-post-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: #e2e8f0;
            overflow: hidden;
        }
        .comm-post-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .comm-post-meta h4 {
            font-size: 14px;
            font-weight: 600;
            color: var(--bs-text);
            margin-bottom: 2px;
        }
        .comm-post-meta span {
            font-size: 12px;
            color: var(--bs-text-light);
        }
        .comm-post-media {
            width: 100%;
            height: 250px;
            background: #f1f5f9;
        }
        .comm-post-media img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .comm-post-media video {
            width: 100%;
            height: 100%;
            object-fit: contain;
            background: #0f172a;
        }
        .comm-post-content {
            padding: 16px;
            font-size: 14px;
            line-height: 1.5;
            color: var(--bs-text);
        }
        .comm-post-edit-form {
            padding: 0 16px 16px;
        }
        .comm-post-edit-form[hidden] {
            display: none;
        }
        .comm-post-edit-form textarea {
            width: 100%;
            min-height: 90px;
            padding: 10px;
            border: 1px solid #cbd5e1;
            border-radius: 8px;
            font: inherit;
            resize: vertical;
        }
        .comm-post-edit-options,
        .comm-post-edit-actions {
            display: flex;
            align-items: center;
            gap: 12px;
            flex-wrap: wrap;
            margin-top: 10px;
        }
        .comm-post-edit-options label {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-size: 13px;
            color: var(--bs-text-light);
            cursor: pointer;
        }
        .comm-post-edit-cancel {
            border: 1px solid #cbd5e1;
            background: white;
            color: var(--bs-text);
            border-radius: 8px;
            padding: 9px 14px;
            cursor: pointer;
        }
        
        /* Sub Tabs */
        .comm-sub-view {
            display: none;
            animation: fadeIn 0.3s ease;
        }
        .comm-sub-view.active-sub-view {
            display: block;
        }
        #view-community .comm-sub-view {
            max-height: 75vh;
            overflow-y: auto;
            overscroll-behavior-y: contain;
            scrollbar-width: thin;
        }
        #view-courses,
        #view-documents,
        #view-shortlisted {
            max-height: none !important;
            overflow: visible !important;
        }
        
        .comm-group-card {
            background: white;
            border: 1px solid var(--bs-border);
            border-radius: 12px;
            overflow: hidden;
            display: flex;
            flex-direction: column;
        }
        .comm-group-image {
            width: 100%;
            height: 120px;
            background: #e2e8f0;
        }
        .comm-group-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .comm-group-content {
            padding: 16px;
            flex: 1;
            display: flex;
            flex-direction: column;
        }
        .comm-group-content h4 {
            font-size: 16px;
            font-weight: 600;
            color: var(--bs-text);
            margin-bottom: 8px;
        }
        .comm-group-content p {
            font-size: 13px;
            color: var(--bs-text-light);
            line-height: 1.4;
            flex: 1;
        }
        .btn-join {
            width: 100%;
            background: #2563eb;
            color: white;
            border: none;
            padding: 10px;
            border-radius: 20px;
            font-size: 13px;
            font-weight: 600;
            margin-top: 16px;
            cursor: pointer;
            transition: background 0.2s;
        }
        .btn-join:hover {
            background: #1d4ed8;
        }
        .community-group-toolbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            margin-bottom: 20px;
        }
        .community-group-search {
            width: min(340px, 100%);
            padding: 10px 14px;
            border: 1px solid #cbd5e1;
            border-radius: 999px;
            font: inherit;
        }
        .community-group-card-meta {
            display: flex;
            justify-content: space-between;
            gap: 12px;
            margin-top: 14px;
            color: #64748b;
            font-size: 12px;
        }
        .community-unread-badge {
            display: inline-flex;
            min-width: 20px;
            height: 20px;
            align-items: center;
            justify-content: center;
            padding: 0 6px;
            border-radius: 999px;
            background: #dc2626;
            color: #fff;
            font-size: 11px;
            font-weight: 700;
            line-height: 1;
            white-space: nowrap;
        }
        .community-group-card-meta [data-group-unread] {
            margin-left: auto;
        }
        .community-unread-badge[hidden] { display: none; }
        #comm-groups.active-sub-view {
            display: flex;
            align-items: stretch;
            gap: 18px;
            height: min(75vh, 680px);
            min-height: 420px;
            max-height: none;
            overflow: hidden;
        }
        .community-group-list {
            flex: 1 1 100%;
            min-width: 0;
            min-height: 0;
            overflow-y: auto;
            overscroll-behavior-y: contain;
            scrollbar-width: thin;
            transition: flex-basis .3s ease;
        }
        #comm-groups.is-expanded .community-group-list { flex: 0 0 40%; }
        #comm-groups.is-expanded #community-groups-grid { grid-template-columns: 1fr; }
        .community-group-chat {
            display: flex;
            flex-direction: column;
            flex: 0 0 0%;
            width: 0;
            height: 0;
            min-width: 0;
            min-height: 0;
            overflow: hidden;
            border: 0;
            border-radius: 16px;
            background: #f0f2f5;
            opacity: 0;
            transform: translateX(14px);
            transition: flex-basis .3s ease, opacity .2s ease, transform .3s ease;
        }
        #comm-groups.is-expanded .community-group-chat {
            flex: 1 1 0;
            width: auto;
            height: 100%;
            min-height: 0;
            border: 1px solid #dbe3f0;
            opacity: 1;
            transform: translateX(0);
        }
        .community-group-chat[hidden],
        .community-group-list[hidden],
        .community-group-modal[hidden] {
            display: none !important;
        }
        [data-community-group][hidden] { display: none !important; }
        .community-group-chat-header {
            display: flex;
            align-items: center;
            gap: 12px;
            flex-wrap: wrap;
            flex: 0 0 auto;
            padding: 14px 18px;
            background: #fff;
            border-bottom: 1px solid #e2e8f0;
        }
        .community-group-chat-header h4 { margin: 0 0 3px; font-size: 15px; }
        .community-group-chat-header p { margin: 0; color: #64748b; font-size: 12px; }
        .community-group-chat-header .community-chat-search { max-width: 190px; width: auto; }
        .community-group-back,
        .community-group-leave {
            border: 0;
            background: transparent;
            color: #475569;
            cursor: pointer;
        }
        .community-group-back { font-size: 18px; }
        .community-group-leave { margin-left: auto; color: #b91c1c; font-size: 12px; font-weight: 600; }
        @media (max-width: 768px) {
            #comm-groups.active-sub-view { flex-direction: column; }
            #comm-groups.active-sub-view {
                height: auto;
                min-height: 0;
                max-height: 75vh;
                overflow-y: auto;
            }
            #comm-groups.is-expanded .community-group-list {
                flex: 0 0 auto;
                max-height: 32vh;
            }
            #comm-groups.is-expanded .community-group-chat {
                flex: 0 0 68vh;
                height: 68vh;
                min-height: 360px;
            }
        }
        .community-group-messages {
            display: flex;
            flex: 1 1 auto;
            flex-direction: column;
            gap: 10px;
            min-height: 0;
            overflow-y: auto;
            overscroll-behavior-y: contain;
            scrollbar-width: thin;
            padding: 18px;
            background-color: #efeae2;
            background-image: radial-gradient(rgba(255,255,255,.42) 1px, transparent 1px);
            background-size: 18px 18px;
        }
        .community-group-empty {
            align-self: center;
            margin: auto;
            padding: 8px 14px;
            border-radius: 10px;
            background: rgba(255,255,255,.88);
            color: #64748b;
            font-size: 13px;
            text-align: center;
        }
        .community-chat-system-event {
            align-self: center;
            max-width: 90%;
            padding: 5px 12px;
            border-radius: 8px;
            background: rgba(255,255,255,.82);
            color: #64748b;
            font-size: 12px;
            text-align: center;
            box-shadow: 0 1px 1px rgba(15,23,42,.06);
        }
        .community-chat-system-event time {
            margin-left: 6px;
            color: #94a3b8;
            font-size: 10px;
        }
        .community-chat-message {
            align-self: flex-start;
            width: fit-content;
            max-width: min(78%, 520px);
            min-width: 0;
            padding: 9px 12px 7px;
            border-radius: 12px;
            background: #fff;
            box-shadow: 0 1px 1px rgba(15,23,42,.08);
            overflow-wrap: anywhere;
            white-space: pre-wrap;
        }
        .community-chat-message.mine {
            align-self: flex-end;
            background: #d9fdd3;
        }
        .community-chat-message.deleted {
            color: #64748b;
            font-style: italic;
        }
        .community-chat-message-footer {
            display: flex;
            align-items: center;
            justify-content: flex-end;
            gap: 8px;
            margin-top: 4px;
        }
        .community-chat-message-actions {
            display: inline-flex;
            align-items: center;
            gap: 2px;
        }
        .community-chat-message-footer .community-chat-time {
            margin-top: 0;
        }
        .community-chat-reply,
        .community-chat-delete {
            padding: 2px 4px;
            border: 0;
            border-radius: 4px;
            background: transparent;
            color: #64748b;
            font-size: 11px;
            cursor: pointer;
        }
        .community-chat-reply:hover,
        .community-chat-reply:focus-visible {
            background: rgba(37, 99, 235, .1);
            color: #1d4ed8;
        }
        .community-chat-delete:hover,
        .community-chat-delete:focus-visible {
            background: rgba(185, 28, 28, .1);
            color: #b91c1c;
        }
        .community-chat-reply-quote {
            display: block;
            width: 100%;
            margin-bottom: 7px;
            padding: 7px 9px;
            border: 0;
            border-left: 3px solid #2563eb;
            border-radius: 4px;
            background: rgba(37, 99, 235, .08);
            color: #475569;
            text-align: left;
            cursor: pointer;
        }
        .community-chat-reply-quote strong,
        .community-chat-reply-quote span {
            display: block;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }
        .community-chat-reply-quote strong {
            margin-bottom: 2px;
            color: #1d4ed8;
            font-size: 11px;
        }
        .community-chat-reply-preview {
            display: flex;
            align-items: center;
            gap: 9px;
            padding: 8px 12px;
            border-top: 1px solid #e2e8f0;
            background: #fff;
        }
        .community-chat-reply-preview[hidden] { display: none; }
        .community-chat-reply-preview-copy {
            min-width: 0;
            flex: 1;
            padding-left: 9px;
            border-left: 3px solid #2563eb;
        }
        .community-chat-reply-preview-copy strong,
        .community-chat-reply-preview-copy span {
            display: block;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }
        .community-chat-reply-preview-copy strong {
            color: #1d4ed8;
            font-size: 11px;
        }
        .community-chat-reply-preview-copy span {
            color: #64748b;
            font-size: 12px;
        }
        .community-chat-reply-cancel {
            width: 32px !important;
            height: 32px;
            flex: 0 0 32px;
            background: transparent !important;
            color: #64748b !important;
        }
        .community-chat-message.highlighted {
            outline: 2px solid #60a5fa;
            outline-offset: 2px;
        }
        .community-chat-message > div:not(.community-chat-attachment-wrap) {
            margin-top: 5px;
        }
        .community-chat-attachment-wrap {
            position: relative;
            width: fit-content;
            max-width: 100%;
            margin: 4px 0 7px;
        }
        .community-chat-attachment {
            display: block;
            max-width: min(100%, 420px);
            margin: 0;
            border-radius: 8px;
        }
        img.community-chat-attachment,
        video.community-chat-attachment {
            max-height: 320px;
            object-fit: contain;
            background: #0f172a;
        }
        a.community-chat-attachment {
            display: block;
            min-width: 210px;
            max-width: 100%;
            padding: 10px 12px;
            padding-right: 52px;
            background: rgba(15, 23, 42, .06);
            color: #1d4ed8;
            text-decoration: none;
            overflow-wrap: anywhere;
        }
        .community-chat-attachment-download {
            position: absolute;
            top: 8px;
            right: 8px;
            display: flex;
            width: 34px;
            height: 34px;
            align-items: center;
            justify-content: center;
            border: 0;
            border-radius: 50%;
            background: rgba(15, 23, 42, .72);
            color: #fff;
            font-size: 15px;
            text-decoration: none;
            cursor: pointer;
        }
        .community-chat-attachment-download:hover,
        .community-chat-attachment-download:focus-visible {
            background: #1d4ed8;
            color: #fff;
        }
        .community-chat-sender {
            display: block;
            margin-bottom: 3px;
            color: #2563eb;
            font-size: 11px;
            font-weight: 700;
        }
        .community-inbox-chat-header > span:first-child,
        .community-group-chat-header > div {
            min-width: 0;
            flex: 1 1 auto;
        }
        .community-chat-search {
            width: 100%;
            min-width: 0;
            flex: 0 0 auto;
            padding: 8px 12px;
            border: 1px solid #dbe3f0;
            border-radius: 9px;
            background: #fff;
            font: inherit;
            font-size: 13px;
        }
        .community-inbox-chat > .community-chat-search {
            padding: 7px 12px;
            border-width: 0 0 1px;
            border-radius: 0;
        }
        .community-chat-notifications,
        .community-inbox-safety-action {
            min-width: 0;
            max-width: 140px;
            padding: 7px 9px;
            border: 1px solid #cbd5e1;
            border-radius: 8px;
            background: #fff;
            color: #334155;
            font: inherit;
            font-size: 11px;
            cursor: pointer;
        }
        .community-inbox-safety-action { color: #b91c1c; }
        .community-chat-upload-status {
            min-height: 0;
            padding: 0 14px;
            color: #64748b;
            font-size: 12px;
        }
        .community-chat-upload-status:empty { display: none; }
        .community-chat-upload-status.error { padding-bottom: 8px; color: #b91c1c; }
        .community-chat-time {
            display: block;
            margin-top: 4px;
            color: #64748b;
            font-size: 10px;
            text-align: right;
        }
        .community-group-compose {
            display: block;
            background: #f8fafc;
        }
        .community-group-compose-row {
            display: flex;
            align-items: flex-end;
            gap: 10px;
            padding: 12px;
        }
        .community-group-attachment-button {
            flex: 0 0 auto;
            padding: 10px 12px !important;
            background: #e2e8f0 !important;
            color: #334155 !important;
        }
        .community-group-attachment-name {
            align-self: center;
            max-width: 150px;
            color: #64748b;
            font-size: 11px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }
        .community-group-compose textarea {
            flex: 1;
            min-height: 42px;
            max-height: 120px;
            resize: vertical;
            padding: 11px 14px;
            border: 1px solid #cbd5e1;
            border-radius: 22px;
            font: inherit;
        }
        .community-group-compose button,
        .community-group-create-submit {
            border: 0;
            border-radius: 22px;
            padding: 11px 18px;
            background: #2563eb;
            color: #fff;
            font-weight: 700;
            cursor: pointer;
        }
        .community-group-compose button:disabled { opacity: .6; cursor: wait; }
        .community-group-feedback { min-height: 20px; margin: 8px 0 0; font-size: 13px; }
        .community-group-feedback.error { color: #b91c1c; }
        .community-group-feedback.success { color: #047857; }
        .community-group-modal {
            position: fixed;
            inset: 0;
            z-index: 1200;
            display: grid;
            place-items: center;
            padding: 18px;
            background: rgba(15, 23, 42, .55);
        }
        .community-group-modal-card {
            width: min(480px, 100%);
            padding: 24px;
            border-radius: 16px;
            background: #fff;
            box-shadow: 0 20px 60px rgba(15,23,42,.25);
        }
        .community-group-modal-card label {
            display: block;
            margin: 14px 0 6px;
            color: #334155;
            font-size: 13px;
            font-weight: 600;
        }
        .community-group-modal-card input,
        .community-group-modal-card textarea {
            width: 100%;
            padding: 11px 12px;
            border: 1px solid #cbd5e1;
            border-radius: 8px;
            font: inherit;
        }
        .community-group-modal-card textarea { min-height: 90px; resize: vertical; }
        .community-group-modal-actions {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            margin-top: 18px;
        }
        .community-friends-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 18px;
            margin-bottom: 18px;
        }
        .community-discover-toggle {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 12px 14px;
            border: 1px solid #dbe3f0;
            border-radius: 12px;
            background: #fff;
            color: #334155;
            font-size: 13px;
        }
        .community-friend-filters {
            display: grid;
            grid-template-columns: 2fr 1fr 1fr 1fr;
            gap: 10px;
            margin-bottom: 18px;
        }
        .community-friend-filters input {
            min-width: 0;
            width: 100%;
            padding: 10px 13px;
            border: 1px solid #cbd5e1;
            border-radius: 9px;
            font: inherit;
        }
        .community-friend-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(245px, 1fr));
            gap: 16px;
        }
        .community-friend-card {
            position: relative;
            padding: 20px;
            border: 1px solid #e2e8f0;
            border-radius: 14px;
            background: rgba(255,255,255,.88);
        }
        .community-friend-menu {
            position: absolute;
            top: 12px;
            right: 12px;
            z-index: 2;
        }
        .community-friend-menu summary {
            display: grid;
            width: 34px;
            height: 34px;
            place-items: center;
            border-radius: 50%;
            color: #475569;
            cursor: pointer;
            list-style: none;
        }
        .community-friend-menu summary::-webkit-details-marker { display: none; }
        .community-friend-menu summary:hover,
        .community-friend-menu[open] summary { background: #f1f5f9; }
        .community-friend-menu-items {
            position: absolute;
            top: 38px;
            right: 0;
            display: grid;
            min-width: 140px;
            padding: 5px;
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            background: #fff;
            box-shadow: 0 10px 28px rgba(15,23,42,.15);
        }
        .community-friend-menu-items button {
            padding: 9px 10px;
            border: 0;
            border-radius: 6px;
            background: transparent;
            color: #334155;
            text-align: left;
            cursor: pointer;
        }
        .community-friend-menu-items button:hover { background: #f8fafc; }
        .community-friend-menu-items button[data-friend-action="block"] { color: #b91c1c; }
        .community-friend-card-top {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 14px;
        }
        .community-friend-avatar {
            display: grid;
            width: 48px;
            height: 48px;
            flex: 0 0 48px;
            place-items: center;
            border-radius: 50%;
            background: linear-gradient(135deg, #dbeafe, #fce7f3);
            color: #1d4ed8;
            font-size: 15px;
            font-weight: 800;
        }
        .community-friend-name {
            margin: 0 0 3px;
            color: #0f172a;
            font-size: 15px;
            font-weight: 700;
        }
        .community-friend-caption {
            color: #64748b;
            font-size: 12px;
        }
        .community-friend-details {
            display: grid;
            gap: 7px;
            min-height: 63px;
            margin-bottom: 15px;
            color: #475569;
            font-size: 12px;
        }
        .community-friend-details span {
            display: flex;
            align-items: flex-start;
            gap: 8px;
        }
        .community-friend-details i {
            width: 14px;
            margin-top: 2px;
            color: #64748b;
        }
        .community-friend-actions {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
        }
        .community-friend-actions button {
            flex: 1;
            min-width: 0;
            padding: 9px 10px;
            border: 1px solid #dbe3f0;
            border-radius: 9px;
            background: #fff;
            color: #334155;
            font-size: 12px;
            font-weight: 700;
            cursor: pointer;
        }
        .community-friend-actions .primary {
            border-color: #2563eb;
            background: #2563eb;
            color: white;
        }
        .community-friend-actions .danger { color: #b91c1c; }
        .community-friend-actions button:disabled { opacity: .6; cursor: wait; }
        .community-inbox {
            display: grid;
            grid-template-columns: minmax(220px, 290px) minmax(0, 1fr);
            grid-template-rows: minmax(0, 1fr);
            min-height: 500px;
            overflow: hidden;
            border: 1px solid #dbe3f0;
            border-radius: 14px;
            background: #fff;
        }
        #comm-inbox.active-sub-view {
            display: flex;
            height: min(75vh, calc(100dvh - 240px));
            max-height: none;
            min-height: 320px;
            flex-direction: column;
            overflow: hidden;
        }
        #comm-inbox > h3 {
            margin-bottom: 8px !important;
        }
        #comm-inbox > .community-group-feedback {
            min-height: 0;
            margin: 0;
        }
        #comm-inbox .community-inbox {
            min-height: 0;
            flex: 1 1 0;
        }
        .community-inbox-threads {
            min-height: 0;
            overflow-y: auto;
            overscroll-behavior-y: contain;
            scrollbar-width: thin;
            border-right: 1px solid #e2e8f0;
        }
        .community-inbox-thread {
            display: flex;
            width: 100%;
            align-items: center;
            gap: 12px;
            padding: 14px;
            border: 0;
            border-bottom: 1px solid #f1f5f9;
            background: transparent;
            color: #0f172a;
            text-align: left;
            cursor: pointer;
        }
        .community-inbox-thread-unread {
            flex: 0 0 auto;
        }
        .community-inbox-thread:hover,
        .community-inbox-thread.active { background: #eff6ff; }
        .community-inbox-thread-avatar {
            display: grid;
            width: 42px;
            height: 42px;
            flex: 0 0 42px;
            place-items: center;
            border-radius: 50%;
            background: #dbeafe;
            color: #1d4ed8;
            font-weight: 700;
        }
        .community-inbox-thread-copy { min-width: 0; flex: 1; }
        .community-inbox-thread-name,
        .community-inbox-thread-preview {
            display: block;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }
        .community-inbox-thread-name { font-size: 13px; font-weight: 700; }
        .community-inbox-thread-preview { margin-top: 4px; color: #64748b; font-size: 12px; }
        .community-inbox-chat {
            display: flex;
            min-width: 0;
            flex-direction: column;
            min-height: 0;
            overflow: hidden;
            background: #f8fafc;
        }
        .community-inbox-chat-header {
            display: flex;
            align-items: center;
            gap: 8px;
            flex: 0 0 auto;
            padding: 15px 18px;
            border-bottom: 1px solid #e2e8f0;
            background: #fff;
            color: #0f172a;
            font-size: 14px;
            font-weight: 700;
        }
        .community-inbox-messages {
            display: flex;
            min-height: 0;
            flex: 1 1 auto;
            flex-direction: column;
            gap: 10px;
            overflow-y: auto;
            overscroll-behavior-y: contain;
            scrollbar-width: thin;
            padding: 18px;
            background: #f1f5f9;
        }
        .community-inbox-message {
            align-self: flex-start;
            width: fit-content;
            max-width: min(78%, 520px);
            min-width: 0;
            padding: 9px 12px;
            border-radius: 12px 12px 12px 3px;
            background: #fff;
            color: #1e293b;
            font-size: 13px;
            line-height: 1.5;
            overflow-wrap: anywhere;
            white-space: pre-wrap;
        }
        .community-inbox-message.deleted {
            color: #64748b;
            font-style: italic;
        }
        .community-message-edited {
            margin-left: 5px;
            color: #64748b;
            font-size: 10px;
            font-style: italic;
        }
        .community-message-read-status {
            margin-left: 6px;
            color: #2563eb;
            font-size: 10px;
        }
        .community-inbox-message.mine {
            align-self: flex-end;
            border-radius: 12px 12px 3px 12px;
            background: #dbeafe;
        }
        .community-inbox-message-footer {
            display: flex;
            align-items: center;
            justify-content: flex-end;
            gap: 8px;
            margin-top: 4px;
        }
        .community-inbox-message-footer time {
            color: #64748b;
            font-size: 10px;
        }
        .community-inbox-message-actions {
            display: flex;
            align-items: center;
            gap: 5px;
        }
        .community-inbox-reply,
        .community-inbox-delete {
            padding: 2px 4px;
            border: 0;
            border-radius: 4px;
            background: transparent;
            color: #64748b;
            font-size: 11px;
            cursor: pointer;
        }
        .community-inbox-reply:hover,
        .community-inbox-reply:focus-visible {
            background: rgba(37, 99, 235, .1);
            color: #1d4ed8;
        }
        .community-inbox-delete:hover,
        .community-inbox-delete:focus-visible {
            background: rgba(185, 28, 28, .1);
            color: #b91c1c;
        }
        .community-inbox-reply-quote {
            display: block;
            width: 100%;
            margin-bottom: 7px;
            padding: 7px 9px;
            border: 0;
            border-left: 3px solid #2563eb;
            border-radius: 4px;
            background: rgba(37, 99, 235, .08);
            color: #475569;
            text-align: left;
            cursor: pointer;
        }
        .community-inbox-reply-quote strong,
        .community-inbox-reply-quote span {
            display: block;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }
        .community-inbox-reply-quote strong {
            margin-bottom: 2px;
            color: #1d4ed8;
            font-size: 11px;
        }
        .community-inbox-reply-preview {
            display: flex;
            align-items: center;
            gap: 9px;
            padding: 8px 12px 0;
            border-top: 1px solid #e2e8f0;
            background: #fff;
        }
        .community-inbox-reply-preview[hidden] { display: none; }
        .community-inbox-reply-preview-copy {
            min-width: 0;
            flex: 1;
            padding-left: 9px;
            border-left: 3px solid #2563eb;
        }
        .community-inbox-reply-preview-copy strong,
        .community-inbox-reply-preview-copy span {
            display: block;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }
        .community-inbox-reply-preview-copy strong {
            color: #1d4ed8;
            font-size: 11px;
        }
        .community-inbox-reply-preview-copy span {
            color: #64748b;
            font-size: 12px;
        }
        .community-inbox-reply-cancel {
            width: 32px !important;
            height: 32px;
            flex: 0 0 32px;
            background: transparent !important;
            color: #64748b !important;
        }
        .community-inbox-message.highlighted {
            outline: 2px solid #60a5fa;
            outline-offset: 2px;
        }
        .community-inbox-message > div:not(.community-inbox-attachment-wrap) {
            margin-top: 5px;
        }
        .community-inbox-attachment-wrap {
            position: relative;
            width: fit-content;
            max-width: 100%;
            margin: 4px 0 7px;
        }
        .community-inbox-attachment {
            display: block;
            max-width: min(100%, 420px);
            margin: 0;
            border-radius: 8px;
        }
        img.community-inbox-attachment,
        video.community-inbox-attachment {
            max-height: 320px;
            object-fit: contain;
            background: #0f172a;
        }
        a.community-inbox-attachment {
            display: block;
            min-width: 210px;
            max-width: 100%;
            padding: 10px 52px 10px 12px;
            border-radius: 8px;
            background: rgba(15, 23, 42, .06);
            color: #1d4ed8;
            text-decoration: none;
            overflow-wrap: anywhere;
        }
        .community-inbox-attachment-download {
            position: absolute;
            top: 8px;
            right: 8px;
            display: flex;
            width: 34px;
            height: 34px;
            align-items: center;
            justify-content: center;
            border: 0;
            border-radius: 50%;
            background: rgba(15, 23, 42, .72);
            color: #fff;
            font-size: 15px;
            text-decoration: none;
        }
        .community-inbox-attachment-download:hover,
        .community-inbox-attachment-download:focus-visible {
            background: #1d4ed8;
            color: #fff;
        }
        .community-inbox-compose {
            display: flex;
            flex: 0 0 auto;
            flex-wrap: nowrap;
            align-items: center;
            gap: 9px;
            padding: 12px;
            border-top: 1px solid #e2e8f0;
            background: #fff;
        }
        .community-inbox-compose[hidden] { display: none; }
        .community-inbox-compose .community-inbox-attach-button {
            width: 40px;
            height: 40px;
            flex: 0 0 40px;
            border-radius: 50%;
            background: #e2e8f0;
            color: #334155;
        }
        .community-inbox-attachment-name {
            align-self: center;
            max-width: 140px;
            overflow: hidden;
            color: #64748b;
            font-size: 11px;
            text-overflow: ellipsis;
            white-space: nowrap;
        }
        .community-inbox-attachment-name:empty { display: none; }
        .community-inbox-compose textarea {
            min-width: 0;
            flex: 1;
            min-height: 42px;
            max-height: 120px;
            resize: none;
            padding: 10px 13px;
            border: 1px solid #cbd5e1;
            border-radius: 20px;
            font: inherit;
        }
        .community-inbox-compose button {
            width: 42px;
            flex: 0 0 42px;
            border: 0;
            border-radius: 50%;
            background: #2563eb;
            color: white;
            cursor: pointer;
        }
        .community-inbox-compose button:disabled { opacity: .6; cursor: wait; }
        .community-inbox-compose button[type="submit"] {
            display: inline-flex;
            width: 42px;
            height: 42px;
            flex: 0 0 42px;
            align-items: center;
            justify-content: center;
            align-self: center;
            padding: 0;
            line-height: 1;
        }
        .community-inbox-open .student-bot-launcher,
        .community-inbox-open .student-bot-panel {
            display: none !important;
        }
        .community-inbox-placeholder,
        .community-inbox-empty {
            margin: auto;
            padding: 18px;
            color: #64748b;
            font-size: 13px;
            text-align: center;
        }
        .community-friend-section {
            margin: 22px 0 12px;
            color: #0f172a;
            font-size: 16px;
            font-weight: 700;
        }
        .community-friend-notice {
            margin: 12px 0;
            color: #64748b;
            font-size: 13px;
        }
        .community-friend-report-modal {
            position: fixed;
            inset: 0;
            z-index: 1200;
            display: grid;
            place-items: center;
            padding: 18px;
            background: rgba(15,23,42,.55);
        }
        .community-friend-report-modal[hidden] { display: none !important; }
        .community-friend-report-card {
            width: min(460px, 100%);
            padding: 24px;
            border-radius: 16px;
            background: #fff;
            box-shadow: 0 20px 60px rgba(15,23,42,.25);
        }
        .community-friend-report-card textarea {
            width: 100%;
            min-height: 110px;
            margin-top: 12px;
            padding: 12px;
            border: 1px solid #cbd5e1;
            border-radius: 9px;
            font: inherit;
            resize: vertical;
        }
        @media (max-width: 600px) {
            .comm-tabs { gap: 20px; }
            .comm-tab-btn { font-size: 14px; }
            .community-group-toolbar { align-items: stretch; flex-direction: column; }
            .community-group-search { width: 100%; }
            .community-chat-message { max-width: 88%; }
            .community-friends-header { align-items: stretch; flex-direction: column; }
            .community-friend-filters { grid-template-columns: 1fr 1fr; }
            #comm-inbox .community-inbox {
                grid-template-columns: minmax(0, 1fr);
                grid-template-rows: minmax(0, min(30vh, 230px)) minmax(0, 1fr);
            }
            .community-inbox-threads {
                border-right: 0;
                border-bottom: 1px solid #e2e8f0;
            }
            #comm-inbox.active-sub-view {
                height: min(75vh, calc(100dvh - 220px));
                min-height: 260px;
            }
        }
    </style>
    <style id="glassmorphism-theme">
        :root {
            --bs-text: #0f172a;
            --bs-text-light: #334155;
            --glass-bg: rgba(255, 255, 255, 0.45);
            --glass-border: rgba(255, 255, 255, 0.6);
            --glass-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.07);
            --glass-blur: blur(16px);
            --bs-white: var(--glass-bg);
            --bs-border: rgba(255, 255, 255, 0.3);
        }

        body {
            background-color: #fdfbfb;
            background-image: 
                radial-gradient(at 18% 71%, hsla(228,81%,89%,1) 0px, transparent 50%),
                radial-gradient(at 77% 81%, hsla(285,73%,92%,1) 0px, transparent 50%),
                radial-gradient(at 90% 36%, hsla(186,81%,90%,1) 0px, transparent 50%),
                radial-gradient(at 26% 22%, hsla(339,78%,93%,1) 0px, transparent 50%);
            background-attachment: fixed;
            color: var(--bs-text);
        }

        /* Glass Cards */
        .sidebar, .dash-card, .course-card, .profile-card, .promo-card-vertical, 
        .comm-post-card, .crm-panel, .comm-group-card, .promo-banner, .comm-filter-bar {
            background: var(--glass-bg) !important;
            backdrop-filter: var(--glass-blur) !important;
            -webkit-backdrop-filter: var(--glass-blur) !important;
            border: 1px solid var(--glass-border) !important;
            box-shadow: var(--glass-shadow) !important;
            border-radius: 20px !important;
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .dash-card:hover, .course-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 40px 0 rgba(31, 38, 135, 0.12) !important;
            border-color: rgba(255,255,255,1) !important;
        }

        /* Header specific */
        .dash-header {
            background: rgba(255, 255, 255, 0.25) !important;
            backdrop-filter: blur(24px) !important;
            -webkit-backdrop-filter: blur(24px) !important;
            border-bottom: 1px solid var(--glass-border) !important;
        }
        
        .dash-header-logo h2 {
            color: #0f172a !important;
        }
        
        .dash-header-logo img {
            filter: none !important; /* Show original colorful logo instead of white */
        }
        
        .back-link {
            color: #334155 !important;
            font-weight: 500;
        }
        .back-link:hover {
            color: #2563eb !important;
        }

        /* Navigation */
        .nav-item {
            color: #1e293b !important;
        }
        .nav-item:hover {
            background: rgba(255, 255, 255, 0.5) !important;
        }
        .nav-item.active {
            background: linear-gradient(135deg, #1d4ed8, #3b82f6) !important;
            color: white !important;
            box-shadow: 0 4px 15px rgba(37, 99, 235, 0.3) !important;
            border: none !important;
        }
        .nav-item.active i {
            color: white !important;
        }

        /* Colored Dashboard Cards */
        .dash-card:nth-child(1) {
            background: rgba(37, 99, 235, 0.08) !important;
            border-color: rgba(37, 99, 235, 0.25) !important;
        }
        .dash-card:nth-child(1) .dash-card-icon {
            background: #2563eb;
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .dash-card:nth-child(2) {
            background: rgba(22, 163, 74, 0.08) !important;
            border-color: rgba(22, 163, 74, 0.25) !important;
        }
        .dash-card:nth-child(2) .dash-card-icon {
            background: #16a34a;
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .dash-card:nth-child(3) {
            background: rgba(239, 68, 68, 0.08) !important;
            border-color: rgba(239, 68, 68, 0.25) !important;
        }
        .dash-card:nth-child(3) .dash-card-icon {
            background: #ef4444;
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        /* Form elements */
        .profile-input, .comm-filter-select {
            background: rgba(255, 255, 255, 0.6) !important;
            backdrop-filter: blur(4px) !important;
            border: 1px solid rgba(255,255,255,0.9) !important;
            color: #0f172a !important;
            border-radius: 10px !important;
        }
        .profile-input:focus {
            background: rgba(255, 255, 255, 0.9) !important;
            border-color: #6366f1 !important;
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.2) !important;
        }
        
        /* Buttons */
        .btn-view-details, .btn-outline, .btn-outline-promo {
            background: rgba(255, 255, 255, 0.6) !important;
            backdrop-filter: blur(4px) !important;
            border: 1px solid rgba(255,255,255,0.8) !important;
            color: #0f172a !important;
            font-weight: 600 !important;
        }
        .btn-view-details:hover, .btn-outline:hover, .btn-outline-promo:hover {
            background: rgba(255, 255, 255, 0.9) !important;
            border-color: #ffffff !important;
        }
        
        .btn-save, .btn-edit, .btn-join {
            background: linear-gradient(135deg, #1d4ed8, #2563eb) !important;
            border: none !important;
            box-shadow: 0 4px 15px rgba(37, 99, 235, 0.2) !important;
        }
        .btn-save:hover, .btn-edit:hover, .btn-join:hover {
            opacity: 0.9;
            transform: scale(1.02);
        }

        /* Typography & Icons */
        .dash-card-icon {
            font-size: 28px !important;
        }
        
        .promo-icon, .promo-icon-large {
            background: rgba(255,255,255,0.8) !important;
            backdrop-filter: blur(4px) !important;
            border: 1px solid #ffffff !important;
            color: #2563eb !important;
        }
        
        .timeline-dot {
            background: rgba(255,255,255,0.9) !important;
            border-color: #fff !important;
        }
        .timeline-item.active .timeline-dot {
            box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.2) !important;
            border-color: #2563eb !important;
        }

        /* Promo Banner */
        .promo-banner {
            background: linear-gradient(135deg, #d81e76, #e7227d) !important;
            border: none !important;
            box-shadow: 0 10px 30px rgba(216, 30, 118, 0.3) !important;
            border-radius: 20px !important;
            padding: 24px 32px !important;
        }
        .promo-banner .promo-text h4, .promo-banner .promo-text p, .promo-banner .btn-close {
            color: white !important;
        }
        .promo-banner .promo-text h4 {
            font-size: 18px !important;
            margin-bottom: 6px !important;
        }
        .promo-banner .promo-icon {
            color: #d81e76 !important;
            background: white !important;
            border: none !important;
            border-radius: 12px !important;
            width: 56px !important;
            height: 56px !important;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1) !important;
        }
        .promo-banner .btn-outline {
            background: white !important;
            border: none !important;
            color: #d81e76 !important;
            font-weight: 700 !important;
            padding: 10px 24px !important;
            border-radius: 24px !important;
            transition: all 0.2s ease !important;
        }
        .promo-banner .btn-outline:hover {
            background: #fdf2f8 !important;
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(0,0,0,0.1) !important;
        }
        
        /* Vertical Promo Banner */
        .promo-card-vertical {
            background: linear-gradient(135deg, #d81e76, #e7227d) !important;
            border: none !important;
            box-shadow: 0 10px 30px rgba(216, 30, 118, 0.3) !important;
            border-radius: 16px !important;
        }
        .promo-card-vertical h3 {
            color: white !important;
        }
        .promo-card-vertical p {
            color: rgba(255,255,255,0.9) !important;
        }
        .promo-card-vertical .btn-close-promo {
            color: white !important;
        }
        .promo-card-vertical .promo-icon-large {
            background: white !important;
            box-shadow: none !important;
        }
        .promo-card-vertical .btn-outline-promo {
            background: white !important;
            color: #d81e76 !important;
            border: none !important;
            font-weight: 700 !important;
            padding: 12px 24px !important;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1) !important;
            border-radius: 12px !important;
        }
        .promo-card-vertical .btn-outline-promo:hover {
            background: #fdf2f8 !important;
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(0,0,0,0.1) !important;
        }

        /* Premium Course Card UI */
        .premium-course-card {
            display: flex;
            flex-direction: column;
            padding: 24px !important;
            gap: 16px;
        }
        .premium-course-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 12px;
        }
        .course-badge {
            background: rgba(37, 99, 235, 0.1);
            color: #2563eb;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 700;
            display: flex;
            flex: 1 1 auto;
            min-width: 0;
            align-items: center;
            gap: 6px;
            overflow: hidden;
            white-space: nowrap;
            text-overflow: ellipsis;
        }
        .course-badge i {
            flex: 0 0 auto;
        }
        .premium-course-header .course-card-fav {
            flex: 0 0 auto;
        }
        .premium-course-title {
            font-size: 18px !important;
            line-height: 1.4 !important;
            height: auto !important;
            margin-bottom: 0 !important;
            color: #0f172a !important;
            display: block !important;
        }
        .premium-course-uni {
            display: flex;
            align-items: center;
            gap: 10px;
            background: rgba(255,255,255,0.5);
            padding: 10px 14px;
            border-radius: 12px;
            margin-bottom: 4px;
        }
        .premium-course-uni .uni-icon {
            width: 32px;
            height: 32px;
            background: white;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #16a34a;
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
        }
        .premium-course-uni span {
            font-size: 13px;
            font-weight: 600;
            color: #334155;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .premium-course-metrics {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
            margin-bottom: auto;
        }
        .premium-course-metrics .metric {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .premium-course-metrics .metric i {
            color: #64748b;
            font-size: 16px;
            width: 20px;
            text-align: center;
        }
        .premium-course-metrics .metric small {
            display: block;
            font-size: 11px;
            color: #64748b;
            text-transform: uppercase;
        }
        .premium-course-metrics .metric strong {
            display: block;
            font-size: 13px;
            color: #0f172a;
        }
        .btn-premium-apply {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            width: 100%;
            border: 1px solid #2563eb;
            border-radius: 14px;
            padding: 12px 14px;
            background: #fff;
            color: #2563eb;
            font: inherit;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
        }
        .btn-premium-apply:hover { background: #eff6ff; }
        .btn-premium-apply:disabled { opacity: .65; cursor: wait; }

        /* Course Card Actions & Badges */
        .course-card-actions {
            display: grid;
            grid-template-columns: 1fr 1.6fr;
            gap: 10px;
            margin-top: 4px;
        }
        .btn-card-compare {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            border: 1px solid #cbd5e1;
            border-radius: 14px;
            padding: 10px 12px;
            background: #f8fafc;
            color: #475569;
            font: inherit;
            font-size: 13px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.2s ease;
        }
        .btn-card-compare:hover {
            background: #eff6ff;
            color: #1d4ed8;
            border-color: #93c5fd;
        }
        .btn-card-compare.is-comparing {
            background: #dbeafe;
            color: #1e40af;
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.15);
        }
        .badge-waiver-pill {
            background: #ecfdf5;
            color: #059669;
            font-size: 11px;
            font-weight: 700;
            padding: 4px 10px;
            border-radius: 20px;
            display: inline-flex;
            align-items: center;
            gap: 4px;
            border: 1px solid #a7f3d0;
            margin-top: 6px;
        }

        /* Advanced Filter Card UI */
        .course-filter-card {
            background: rgba(255, 255, 255, 0.85) !important;
            backdrop-filter: blur(16px);
            border: 1px solid rgba(226, 232, 240, 0.9) !important;
            border-radius: 20px !important;
            padding: 24px;
            margin-bottom: 24px;
            box-shadow: 0 8px 30px rgba(15, 23, 42, 0.04);
        }
        .filter-grid-row {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 12px;
            margin-bottom: 14px;
        }
        .filter-select-wrap {
            display: flex;
            flex-direction: column;
            gap: 5px;
        }
        .filter-select-wrap label {
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            color: #64748b;
        }
        .filter-select-custom {
            padding: 10px 14px;
            border-radius: 12px;
            border: 1px solid #cbd5e1;
            background: white;
            color: #0f172a;
            font-size: 13px;
            font-weight: 600;
            outline: none;
            transition: border-color 0.2s, box-shadow 0.2s;
        }
        .filter-select-custom:focus {
            border-color: #2563eb;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.15);
        }
        .filter-search-row {
            display: flex;
            gap: 12px;
            align-items: center;
            flex-wrap: wrap;
        }
        .filter-search-input-wrap {
            flex: 1;
            min-width: 250px;
            position: relative;
        }
        .filter-search-input-wrap i {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
            font-size: 14px;
        }
        .filter-search-input {
            width: 100%;
            padding: 11px 16px 11px 38px;
            border-radius: 12px;
            border: 1px solid #cbd5e1;
            background: white;
            font-size: 14px;
            outline: none;
            transition: all 0.2s;
        }
        .filter-search-input:focus {
            border-color: #2563eb;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.15);
        }
        .btn-apply-filters {
            background: #2563eb;
            color: white;
            font-size: 14px;
            font-weight: 700;
            padding: 11px 22px;
            border-radius: 12px;
            border: none;
            cursor: pointer;
            transition: background 0.2s, transform 0.2s;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }
        .btn-apply-filters:hover {
            background: #1d4ed8;
            transform: translateY(-1px);
        }
        .btn-reset-filters {
            color: #64748b;
            font-size: 13px;
            font-weight: 600;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 10px 14px;
            border-radius: 12px;
            background: #f1f5f9;
            transition: all 0.2s;
        }
        .btn-reset-filters:hover {
            background: #e2e8f0;
            color: #0f172a;
        }
        .filter-stats-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 14px;
            padding-top: 12px;
            border-top: 1px solid #f1f5f9;
            font-size: 13px;
            color: #64748b;
            flex-wrap: wrap;
            gap: 10px;
        }
        .btn-trigger-compare-modal {
            background: linear-gradient(135deg, #4f46e5, #6366f1);
            color: white;
            font-size: 12px;
            font-weight: 700;
            padding: 6px 14px;
            border-radius: 20px;
            border: none;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            transition: all 0.2s;
        }
        .btn-trigger-compare-modal:hover {
            box-shadow: 0 4px 12px rgba(79, 70, 229, 0.35);
            transform: translateY(-1px);
        }

        /* Floating Comparison Dock */
        .course-compare-dock {
            position: fixed;
            bottom: 24px;
            left: 50%;
            transform: translateX(-50%);
            z-index: 1050;
            width: min(850px, calc(100vw - 32px));
            background: rgba(15, 23, 42, 0.95);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.15);
            border-radius: 20px;
            padding: 14px 20px;
            box-shadow: 0 20px 45px rgba(0, 0, 0, 0.35);
            animation: slideUpDock 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        }
        @keyframes slideUpDock {
            from { transform: translate(-50%, 40px); opacity: 0; }
            to { transform: translate(-50%, 0); opacity: 1; }
        }
        .compare-dock-inner {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            flex-wrap: wrap;
        }
        .compare-dock-left {
            display: flex;
            align-items: center;
            gap: 12px;
            flex: 1;
            min-width: 250px;
            overflow-x: auto;
        }
        .compare-dock-title {
            color: white;
            font-size: 13px;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 8px;
            white-space: nowrap;
        }
        .compare-dock-items {
            display: flex;
            gap: 8px;
            align-items: center;
            overflow-x: auto;
            padding-bottom: 2px;
        }
        .compare-dock-tag {
            background: rgba(255, 255, 255, 0.12);
            border: 1px solid rgba(255, 255, 255, 0.2);
            color: white;
            padding: 5px 10px;
            border-radius: 10px;
            font-size: 12px;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            max-width: 170px;
            white-space: nowrap;
            text-overflow: ellipsis;
            overflow: hidden;
        }
        .compare-dock-tag-remove {
            background: none;
            border: none;
            color: #94a3b8;
            cursor: pointer;
            font-size: 14px;
            padding: 0;
            display: flex;
            align-items: center;
            transition: color 0.15s;
        }
        .compare-dock-tag-remove:hover {
            color: #ef4444;
        }
        .compare-dock-actions {
            display: flex;
            align-items: center;
            gap: 10px;
            flex-shrink: 0;
        }
        .btn-compare-dock-clear {
            background: transparent;
            border: 1px solid rgba(255, 255, 255, 0.2);
            color: #cbd5e1;
            padding: 8px 14px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
        }
        .btn-compare-dock-clear:hover {
            background: rgba(255, 255, 255, 0.1);
            color: white;
        }
        .btn-compare-dock-launch {
            background: linear-gradient(135deg, #10b981, #059669);
            color: white;
            padding: 9px 18px;
            border-radius: 12px;
            border: none;
            font-size: 13px;
            font-weight: 700;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.35);
            transition: all 0.2s;
        }
        .btn-compare-dock-launch:hover:not(:disabled) {
            transform: translateY(-1px);
            box-shadow: 0 6px 16px rgba(16, 185, 129, 0.5);
        }
        .btn-compare-dock-launch:disabled {
            opacity: 0.5;
            cursor: not-allowed;
            background: #475569;
            box-shadow: none;
        }

        /* Course Comparison Modal */
        .course-compare-modal {
            position: fixed;
            inset: 0;
            z-index: 2000;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        .compare-modal-backdrop {
            position: absolute;
            inset: 0;
            background: rgba(15, 23, 42, 0.65);
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
        }
        .compare-modal-content {
            position: relative;
            z-index: 2;
            width: min(1100px, 95vw);
            max-height: 90vh;
            background: white;
            border-radius: 24px;
            box-shadow: 0 25px 60px rgba(15, 23, 42, 0.3);
            display: flex;
            flex-direction: column;
            overflow: hidden;
            animation: scaleInModal 0.25s ease-out;
        }
        @keyframes scaleInModal {
            from { transform: scale(0.95); opacity: 0; }
            to { transform: scale(1); opacity: 1; }
        }
        .compare-modal-header {
            padding: 24px 28px;
            border-bottom: 1px solid #e2e8f0;
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: #f8fafc;
        }
        .compare-modal-header h2 {
            font-size: 20px;
            font-weight: 800;
            color: #0f172a;
            margin: 0 0 4px 0;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .compare-modal-header p {
            font-size: 13px;
            color: #64748b;
            margin: 0;
        }
        .compare-modal-close {
            background: none;
            border: none;
            font-size: 28px;
            color: #64748b;
            cursor: pointer;
            line-height: 1;
            padding: 4px 8px;
            border-radius: 8px;
            transition: color 0.15s, background 0.15s;
        }
        .compare-modal-close:hover {
            color: #0f172a;
            background: #e2e8f0;
        }
        .btn-compare-print {
            background: white;
            border: 1px solid #cbd5e1;
            padding: 8px 14px;
            border-radius: 10px;
            font-size: 13px;
            font-weight: 600;
            color: #334155;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            transition: all 0.2s;
        }
        .btn-compare-print:hover {
            background: #f1f5f9;
        }
        .compare-modal-body {
            padding: 24px 28px;
            overflow-y: auto;
            flex: 1;
        }
        .compare-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            border: 1px solid #e2e8f0;
            border-radius: 16px;
            overflow: hidden;
        }
        .compare-table th, .compare-table td {
            padding: 16px 20px;
            text-align: left;
            border-bottom: 1px solid #e2e8f0;
            font-size: 13px;
            vertical-align: top;
        }
        .compare-table tr:last-child th, .compare-table tr:last-child td {
            border-bottom: none;
        }
        .compare-row-header {
            width: 220px;
            background: #f8fafc;
            color: #475569;
            font-weight: 700;
            text-transform: uppercase;
            font-size: 11px;
            letter-spacing: 0.05em;
            border-right: 1px solid #e2e8f0;
        }
        .compare-col-course {
            width: calc((100% - 220px) / 3);
            border-right: 1px solid #e2e8f0;
            background: white;
        }
        .compare-col-course:last-child {
            border-right: none;
        }
        .compare-course-header-cell {
            background: #f0fdf4;
            border-bottom: 2px solid #10b981 !important;
            padding: 20px !important;
        }
        .compare-uni-name {
            font-size: 13px;
            font-weight: 600;
            color: #15803d;
            margin-bottom: 4px;
            display: flex;
            align-items: center;
            gap: 6px;
        }
        .compare-course-name {
            font-size: 16px;
            font-weight: 800;
            color: #0f172a;
            line-height: 1.3;
            margin-bottom: 8px;
        }
        .compare-metric-pill {
            display: inline-block;
            font-size: 12px;
            font-weight: 700;
            padding: 3px 10px;
            border-radius: 6px;
            background: #eff6ff;
            color: #1e40af;
        }

        /* Shortlisted view top bar */
        .shortlist-top-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            flex-wrap: wrap;
            gap: 12px;
        }
        .btn-compare-all {
            background: linear-gradient(135deg, #1d4ed8, #2563eb);
            color: white;
            font-size: 13px;
            font-weight: 700;
            padding: 10px 18px;
            border-radius: 12px;
            border: none;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.25);
            transition: all 0.2s;
        }
        .btn-compare-all:hover {
            background: #1e40af;
            transform: translateY(-1px);
        }

        /* ─── Multi-University Application Support UI ─── */
        .app-sub-nav {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 24px;
            overflow-x: auto;
            padding-bottom: 4px;
        }
        .app-sub-nav-btn {
            background: white;
            border: 1px solid #cbd5e1;
            padding: 9px 18px;
            border-radius: 12px;
            font-size: 13px;
            font-weight: 700;
            color: #475569;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.2s ease;
            white-space: nowrap;
        }
        .app-sub-nav-btn:hover {
            background: #f8fafc;
            color: #0f172a;
            border-color: #94a3b8;
        }
        .app-sub-nav-btn.active {
            background: linear-gradient(135deg, #1d4ed8, #2563eb);
            color: white;
            border-color: #2563eb;
            box-shadow: 0 4px 14px rgba(37, 99, 235, 0.3);
        }
        .app-sub-nav-badge {
            background: rgba(0, 0, 0, 0.08);
            font-size: 11px;
            padding: 2px 7px;
            border-radius: 999px;
        }
        .app-sub-nav-btn.active .app-sub-nav-badge {
            background: rgba(255, 255, 255, 0.25);
            color: white;
        }

        .multi-app-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(340px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        .multi-app-card {
            background: white !important;
            border: 1px solid #e2e8f0 !important;
            border-radius: 20px !important;
            padding: 22px;
            box-shadow: 0 8px 24px rgba(15, 23, 42, 0.05);
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            transition: transform 0.2s, box-shadow 0.2s;
            position: relative;
        }
        .multi-app-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 14px 30px rgba(15, 23, 42, 0.09);
        }
        .multi-app-card.has-offer {
            border-color: #86efac !important;
            background: linear-gradient(180deg, #f0fdf4 0%, #ffffff 40%) !important;
        }
        .app-card-top {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 12px;
            margin-bottom: 12px;
        }
        .app-card-uni-info {
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .app-card-uni-icon {
            width: 44px;
            height: 44px;
            border-radius: 12px;
            background: #eff6ff;
            color: #2563eb;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            flex-shrink: 0;
        }
        .multi-app-card.has-offer .app-card-uni-icon {
            background: #dcfce7;
            color: #16a34a;
        }
        .app-card-uni-name {
            font-size: 16px;
            font-weight: 800;
            color: #0f172a;
            line-height: 1.25;
        }
        .app-card-country {
            font-size: 12px;
            color: #64748b;
            margin-top: 2px;
        }
        .app-type-pill {
            font-size: 11px;
            font-weight: 700;
            padding: 4px 10px;
            border-radius: 8px;
            text-transform: uppercase;
            letter-spacing: 0.04em;
        }
        .app-type-pill.dream { background: #f3e8ff; color: #7e22ce; border: 1px solid #d8b4fe; }
        .app-type-pill.target { background: #dbeafe; color: #1e40af; border: 1px solid #93c5fd; }
        .app-type-pill.safe { background: #dcfce7; color: #15803d; border: 1px solid #86efac; }

        .app-card-course {
            font-size: 14px;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 14px;
            line-height: 1.35;
        }
        .app-card-meta-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: #f8fafc;
            padding: 10px 14px;
            border-radius: 12px;
            margin-bottom: 16px;
            font-size: 12px;
            color: #475569;
        }
        .app-card-status-badge {
            font-size: 12px;
            font-weight: 700;
            padding: 5px 12px;
            border-radius: 8px;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }
        .app-status-offer { background: #dcfce7; color: #15803d; border: 1px solid #bbf7d0; }
        .app-status-review { background: #dbeafe; color: #1e40af; border: 1px solid #bfdbfe; }
        .app-status-pending { background: #fef3c7; color: #92400e; border: 1px solid #fde68a; }
        .app-status-visa { background: #fdf4ff; color: #86198f; border: 1px solid #f0abfc; }

        .app-card-actions {
            display: flex;
            gap: 10px;
            margin-top: 14px;
        }
        .btn-card-offer-view {
            flex: 1;
            background: #10b981;
            color: white;
            font-size: 13px;
            font-weight: 700;
            padding: 10px 14px;
            border-radius: 10px;
            text-align: center;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            border: none;
            cursor: pointer;
            transition: all 0.2s;
        }
        .btn-card-offer-view:hover {
            background: #059669;
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
        }

        /* ─── Official Offer Letter Hub UI ─── */
        .offer-hub-panel {
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.98), rgba(240, 253, 244, 0.95)) !important;
            border: 2px solid #86efac !important;
            border-radius: 24px !important;
            padding: 30px;
            margin-bottom: 30px;
            box-shadow: 0 15px 40px rgba(16, 185, 129, 0.09);
            position: relative;
        }
        .offer-hub-top {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            flex-wrap: wrap;
            gap: 16px;
            margin-bottom: 24px;
            padding-bottom: 20px;
            border-bottom: 1px solid #bbf7d0;
        }
        .offer-hub-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: #dcfce7;
            color: #15803d;
            font-size: 12px;
            font-weight: 800;
            padding: 6px 14px;
            border-radius: 999px;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            border: 1px solid #86efac;
            margin-bottom: 8px;
        }
        .offer-hub-title {
            font-size: 24px;
            font-weight: 800;
            color: #14532d;
            margin: 0 0 6px 0;
        }
        .offer-hub-sub {
            font-size: 14px;
            color: #166534;
            margin: 0;
            line-height: 1.5;
        }
        .offer-hub-actions-top {
            display: flex;
            align-items: center;
            gap: 10px;
            flex-wrap: wrap;
        }
        .btn-offer-download {
            background: #0f172a;
            color: white;
            font-size: 13px;
            font-weight: 700;
            padding: 11px 20px;
            border-radius: 12px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.2s;
            box-shadow: 0 4px 14px rgba(15, 23, 42, 0.25);
        }
        .btn-offer-download:hover {
            background: #1e293b;
            color: white;
            transform: translateY(-1px);
        }

        .offer-specs-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 16px;
            margin-bottom: 26px;
        }
        .offer-spec-box {
            background: white;
            padding: 16px;
            border-radius: 14px;
            border: 1px solid #dcfce7;
        }
        .offer-spec-label {
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            color: #64748b;
            margin-bottom: 4px;
        }
        .offer-spec-val {
            font-size: 15px;
            font-weight: 800;
            color: #0f172a;
        }

        /* Conditions Breakdown Checklist */
        .offer-conditions-card {
            background: white;
            border-radius: 16px;
            padding: 20px;
            border: 1px solid #bbf7d0;
            margin-bottom: 24px;
        }
        .conditions-card-title {
            font-size: 16px;
            font-weight: 800;
            color: #166534;
            margin-bottom: 14px;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .conditions-list {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }
        .condition-item-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 12px 16px;
            border-radius: 10px;
            background: #f8fafc;
            border: 1px solid #edf2f7;
            gap: 12px;
        }
        .condition-item-row.satisfied {
            background: #f0fdf4;
            border-color: #dcfce7;
        }
        .condition-item-left {
            display: flex;
            align-items: center;
            gap: 12px;
            font-size: 13px;
            color: #334155;
            font-weight: 600;
        }
        .condition-item-row.satisfied .condition-item-left {
            color: #15803d;
        }
        .condition-status-pill {
            font-size: 11px;
            font-weight: 700;
            padding: 3px 10px;
            border-radius: 6px;
            white-space: nowrap;
        }
        .condition-status-pill.satisfied { background: #dcfce7; color: #16a34a; }
        .condition-status-pill.pending { background: #fef3c7; color: #b45309; }

        .offer-decision-bar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 14px;
            background: #ffffff;
            padding: 18px 24px;
            border-radius: 16px;
            border: 1px solid #bbf7d0;
            flex-wrap: wrap;
        }
        .offer-decision-left h4 {
            font-size: 15px;
            font-weight: 800;
            color: #0f172a;
            margin: 0 0 2px 0;
        }
        .offer-decision-left p {
            font-size: 13px;
            color: #64748b;
            margin: 0;
        }
        .offer-decision-buttons {
            display: flex;
            align-items: center;
            gap: 12px;
            flex-wrap: wrap;
        }
        .btn-accept-offer {
            background: linear-gradient(135deg, #10b981, #059669);
            color: white;
            font-size: 14px;
            font-weight: 700;
            padding: 12px 22px;
            border-radius: 12px;
            border: none;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            box-shadow: 0 4px 14px rgba(16, 185, 129, 0.35);
            transition: all 0.2s;
        }
        .btn-accept-offer:hover:not(:disabled) {
            background: #047857;
            transform: translateY(-1px);
        }
        .btn-accept-offer.accepted {
            background: #15803d;
            cursor: default;
        }
        .btn-request-deposit {
            background: #f1f5f9;
            color: #1e293b;
            font-size: 13px;
            font-weight: 700;
            padding: 12px 18px;
            border-radius: 12px;
            border: 1px solid #cbd5e1;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.2s;
        }
        .btn-request-deposit:hover:not(:disabled) {
            background: #e2e8f0;
            color: #0f172a;
        }
        .btn-request-deposit.requested {
            background: #dbeafe;
            color: #1e40af;
            border-color: #93c5fd;
            cursor: default;
        }

        /* ─── Visa Stage Tracker UI ─── */
        .visa-tracker-panel {
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.95), rgba(248, 250, 252, 0.95)) !important;
            border: 1px solid #cbd5e1 !important;
            border-radius: 24px !important;
            padding: 28px;
            margin-bottom: 30px;
            box-shadow: 0 10px 30px rgba(15, 23, 42, 0.05);
        }
        .visa-tracker-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 24px;
            padding-bottom: 16px;
            border-bottom: 1px solid #e2e8f0;
            flex-wrap: wrap;
            gap: 12px;
        }
        .visa-tracker-title {
            font-size: 18px;
            font-weight: 800;
            color: #0f172a;
            display: flex;
            align-items: center;
            gap: 10px;
            margin: 0;
        }
        .visa-steps-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 16px;
        }
        .visa-step-card {
            background: white;
            border-radius: 16px;
            padding: 20px;
            border: 1px solid #e2e8f0;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            position: relative;
        }
        .visa-step-card.active {
            border-color: #93c5fd;
            box-shadow: 0 8px 24px rgba(37, 99, 235, 0.1);
        }
        .visa-step-card.completed {
            border-color: #86efac;
            background: #f0fdf4;
        }
        .visa-step-icon {
            width: 44px;
            height: 44px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            margin-bottom: 14px;
        }
        .visa-step-card:nth-child(1) .visa-step-icon { background: #eff6ff; color: #2563eb; }
        .visa-step-card:nth-child(2) .visa-step-icon { background: #fdf4ff; color: #9333ea; }
        .visa-step-card:nth-child(3) .visa-step-icon { background: #ecfdf5; color: #059669; }

        .visa-step-title {
            font-size: 15px;
            font-weight: 800;
            color: #0f172a;
            margin-bottom: 6px;
        }
        .visa-step-desc {
            font-size: 13px;
            color: #64748b;
            line-height: 1.45;
            margin-bottom: 14px;
        }
        .visa-step-data-box {
            background: #f8fafc;
            padding: 10px 12px;
            border-radius: 10px;
            border: 1px solid #edf2f7;
            font-size: 12px;
            color: #334155;
            margin-top: auto;
        }

        @media (max-width: 1080px) {
            .offer-specs-grid { grid-template-columns: repeat(2, 1fr); }
            .visa-steps-grid { grid-template-columns: 1fr; }
        }

        @media (max-width: 900px) {
            .filter-grid-row { grid-template-columns: repeat(2, 1fr); }
            .course-card-actions { grid-template-columns: 1fr; }
        }
        @media (max-width: 600px) {
            .filter-grid-row { grid-template-columns: 1fr; }
            .compare-dock-inner { flex-direction: column; align-items: stretch; }
            .compare-dock-actions { justify-content: space-between; }
        }

        /* ═══════════════ Categorized Document Management & Verification ═══════════════ */
        .doc-hub-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            flex-wrap: wrap;
            gap: 16px;
            margin-bottom: 24px;
        }
        .doc-hub-title {
            margin: 0 0 6px 0;
            font-size: 26px;
            font-weight: 800;
            color: #0f172a;
            letter-spacing: -0.02em;
        }
        .doc-hub-subtitle {
            margin: 0;
            font-size: 14px;
            color: #64748b;
            max-width: 720px;
            line-height: 1.5;
        }
        .doc-intake-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: #eff6ff;
            color: #1e40af;
            border: 1px solid #bfdbfe;
            padding: 8px 16px;
            border-radius: 12px;
            font-size: 13px;
            font-weight: 600;
        }
        .doc-overview-card {
            background: #ffffff;
            border-radius: 16px;
            border: 1px solid #e2e8f0;
            padding: 24px;
            margin-bottom: 24px;
            box-shadow: 0 4px 20px -2px rgba(15, 23, 42, 0.05);
        }
        .doc-kpi-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 16px;
            margin-bottom: 20px;
        }
        .doc-kpi-box {
            padding: 16px 18px;
            border-radius: 14px;
            border: 1px solid #e2e8f0;
            background: #f8fafc;
            display: flex;
            align-items: center;
            gap: 14px;
            transition: all 0.2s ease;
        }
        .doc-kpi-box:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 16px -2px rgba(0,0,0,0.06);
        }
        .doc-kpi-icon {
            width: 44px;
            height: 44px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            flex-shrink: 0;
        }
        .doc-kpi-box.total .doc-kpi-icon { background: #eff6ff; color: #2563eb; }
        .doc-kpi-box.verified { border-color: #a7f3d0; background: #f0fdf4; }
        .doc-kpi-box.verified .doc-kpi-icon { background: #d1fae5; color: #059669; }
        .doc-kpi-box.review { border-color: #fde68a; background: #fffbeb; }
        .doc-kpi-box.review .doc-kpi-icon { background: #fef3c7; color: #d97706; }
        .doc-kpi-box.reupload { border-color: #fecaca; background: #fef2f2; }
        .doc-kpi-box.reupload .doc-kpi-icon { background: #fee2e2; color: #dc2626; }
        .doc-kpi-val {
            font-size: 22px;
            font-weight: 800;
            color: #0f172a;
            line-height: 1.1;
        }
        .doc-kpi-label {
            font-size: 12px;
            font-weight: 600;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            margin-top: 2px;
        }
        .doc-progress-wrap {
            background: #f1f5f9;
            border-radius: 10px;
            height: 10px;
            overflow: hidden;
            position: relative;
        }
        .doc-progress-bar {
            height: 100%;
            background: linear-gradient(90deg, #10b981 0%, #059669 100%);
            border-radius: 10px;
            transition: width 0.6s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .doc-progress-meta {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 13px;
            color: #475569;
            margin-bottom: 8px;
            font-weight: 600;
        }

        /* Expiry Alerts */
        .doc-expiry-container {
            margin-bottom: 24px;
            display: flex;
            flex-direction: column;
            gap: 14px;
        }
        .doc-expiry-card {
            border-radius: 14px;
            padding: 18px 22px;
            display: flex;
            align-items: flex-start;
            gap: 16px;
            box-shadow: 0 4px 14px -2px rgba(220, 38, 38, 0.08);
            position: relative;
            overflow: hidden;
        }
        .doc-expiry-card.critical {
            background: #fff5f5;
            border: 1px solid #fecaca;
            border-left: 6px solid #ef4444;
        }
        .doc-expiry-card.warning {
            background: #fffbeb;
            border: 1px solid #fde68a;
            border-left: 6px solid #f59e0b;
        }
        .doc-expiry-icon {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            flex-shrink: 0;
        }
        .doc-expiry-card.critical .doc-expiry-icon { background: #fee2e2; color: #dc2626; }
        .doc-expiry-card.warning .doc-expiry-icon { background: #fef3c7; color: #d97706; }
        .doc-expiry-content { flex: 1; }
        .doc-expiry-title {
            font-size: 15px;
            font-weight: 700;
            margin: 0 0 6px 0;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .doc-expiry-card.critical .doc-expiry-title { color: #991b1b; }
        .doc-expiry-card.warning .doc-expiry-title { color: #92400e; }
        .doc-expiry-desc {
            font-size: 13.5px;
            line-height: 1.5;
            color: #4b5563;
            margin: 0 0 12px 0;
        }
        .doc-expiry-desc strong { color: #111827; }
        .btn-expiry-action {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 8px 18px;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 600;
            border: none;
            cursor: pointer;
            transition: all 0.2s;
            text-decoration: none;
        }
        .doc-expiry-card.critical .btn-expiry-action {
            background: #dc2626;
            color: #ffffff;
        }
        .doc-expiry-card.critical .btn-expiry-action:hover { background: #b91c1c; }
        .doc-expiry-card.warning .btn-expiry-action {
            background: #d97706;
            color: #ffffff;
        }
        .doc-expiry-card.warning .btn-expiry-action:hover { background: #b45309; }

        /* Toolbar & Filters */
        .doc-filter-toolbar {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 14px;
            padding: 12px 18px;
            margin-bottom: 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 14px;
        }
        .doc-stage-nav {
            display: flex;
            align-items: center;
            gap: 8px;
            flex-wrap: wrap;
        }
        .doc-stage-btn {
            background: transparent;
            border: 1px solid transparent;
            padding: 8px 14px;
            border-radius: 10px;
            font-size: 13px;
            font-weight: 600;
            color: #64748b;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            transition: all 0.2s;
        }
        .doc-stage-btn:hover {
            color: #0f172a;
            background: #f1f5f9;
        }
        .doc-stage-btn.active {
            background: #1e3a8a;
            color: #ffffff;
        }
        .doc-filter-pills {
            display: flex;
            align-items: center;
            gap: 6px;
            flex-wrap: wrap;
        }
        .doc-pill-btn {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            padding: 6px 12px;
            border-radius: 8px;
            font-size: 12px;
            font-weight: 600;
            color: #475569;
            cursor: pointer;
            transition: all 0.2s;
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }
        .doc-pill-btn:hover { background: #edf2f7; color: #1e293b; }
        .doc-pill-btn.active {
            background: #0f172a;
            color: #ffffff;
            border-color: #0f172a;
        }
        .doc-search-box {
            position: relative;
            min-width: 200px;
        }
        .doc-search-box i {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
            font-size: 13px;
        }
        .doc-search-input {
            width: 100%;
            padding: 8px 12px 8px 34px;
            border: 1px solid #cbd5e1;
            border-radius: 8px;
            font-size: 13px;
            outline: none;
            transition: border-color 0.2s;
        }
        .doc-search-input:focus { border-color: #2563eb; }

        /* Categorized Stage Section */
        .doc-stage-section {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 16px;
            padding: 24px;
            margin-bottom: 24px;
            box-shadow: 0 4px 16px -2px rgba(15, 23, 42, 0.04);
        }
        .doc-stage-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            flex-wrap: wrap;
            gap: 12px;
            margin-bottom: 20px;
            padding-bottom: 16px;
            border-bottom: 1px solid #f1f5f9;
        }
        .doc-stage-title-wrap {
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .doc-stage-icon {
            width: 40px;
            height: 40px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
        }
        .doc-stage-title {
            font-size: 18px;
            font-weight: 700;
            color: #0f172a;
            margin: 0 0 2px 0;
        }
        .doc-stage-desc {
            font-size: 13px;
            color: #64748b;
            margin: 0;
        }
        .doc-stage-meta {
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .doc-stage-count-badge {
            font-size: 12px;
            font-weight: 700;
            padding: 4px 10px;
            border-radius: 8px;
            background: #eff6ff;
            color: #2563eb;
            border: 1px solid #dbeafe;
        }

        /* Document Slot Cards Grid */
        .doc-cards-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 18px;
        }
        .doc-slot-card {
            border: 1px solid #e2e8f0;
            border-radius: 14px;
            padding: 18px;
            background: #ffffff;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            transition: all 0.2s ease;
            position: relative;
        }
        .doc-slot-card:hover {
            box-shadow: 0 6px 18px -2px rgba(0,0,0,0.06);
            transform: translateY(-2px);
        }
        .doc-slot-card.status-verified {
            border-left: 5px solid #10b981;
            background: #ffffff;
        }
        .doc-slot-card.status-review {
            border-left: 5px solid #f59e0b;
            background: #ffffff;
        }
        .doc-slot-card.status-reupload {
            border-left: 5px solid #ef4444;
            background: #fffcfc;
            box-shadow: 0 4px 14px -2px rgba(239, 68, 68, 0.08);
        }
        .doc-slot-card.status-missing {
            border: 1px dashed #cbd5e1;
            background: #f8fafc;
        }

        .doc-slot-top {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 12px;
            margin-bottom: 12px;
        }
        .doc-slot-name {
            font-size: 15px;
            font-weight: 700;
            color: #0f172a;
            margin: 0 0 4px 0;
            line-height: 1.3;
        }
        .doc-slot-desc {
            font-size: 12.5px;
            color: #64748b;
            margin: 0;
            line-height: 1.4;
        }

        /* Visual Verification Badges */
        .doc-badge {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            font-size: 11px;
            font-weight: 700;
            padding: 4px 10px;
            border-radius: 8px;
            white-space: nowrap;
            letter-spacing: 0.02em;
            text-transform: uppercase;
        }
        .doc-badge.verified {
            background: #d1fae5;
            color: #065f46;
            border: 1px solid #a7f3d0;
        }
        .doc-badge.pending {
            background: #fef3c7;
            color: #92400e;
            border: 1px solid #fde68a;
        }
        .doc-badge.rejected {
            background: #fee2e2;
            color: #991b1b;
            border: 1px solid #fca5a5;
            animation: pulseAttention 2s infinite ease-in-out;
        }
        .doc-badge.missing {
            background: #f1f5f9;
            color: #64748b;
            border: 1px solid #e2e8f0;
        }
        @keyframes pulseAttention {
            0%, 100% { opacity: 1; transform: scale(1); }
            50% { opacity: 0.85; transform: scale(1.02); }
        }

        /* File Info Row */
        .doc-file-info {
            background: #f8fafc;
            border: 1px solid #edf2f7;
            border-radius: 10px;
            padding: 10px 14px;
            margin: 12px 0;
            display: flex;
            align-items: center;
            justify-content: space-between;
            font-size: 12.5px;
        }
        .doc-file-name-wrap {
            display: flex;
            align-items: center;
            gap: 8px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }
        .doc-file-icon {
            font-size: 16px;
            color: #2563eb;
            flex-shrink: 0;
        }
        .doc-file-name {
            font-weight: 600;
            color: #334155;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
            max-width: 220px;
        }
        .doc-file-size {
            color: #94a3b8;
            font-size: 11px;
            white-space: nowrap;
            margin-left: 6px;
        }

        /* Counselor Feedback Note Callout */
        .doc-counselor-feedback {
            background: #fff5f5;
            border: 1px solid #fecaca;
            border-radius: 10px;
            padding: 12px 14px;
            margin: 12px 0;
            font-size: 13px;
        }
        .doc-feedback-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 6px;
            color: #991b1b;
            font-weight: 700;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 0.04em;
        }
        .doc-feedback-author {
            font-size: 11px;
            color: #b91c1c;
            background: #fee2e2;
            padding: 2px 8px;
            border-radius: 6px;
        }
        .doc-feedback-text {
            color: #7f1d1d;
            font-style: italic;
            line-height: 1.45;
            margin: 0;
        }

        /* Expiry Pill */
        .doc-expiry-pill {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            font-size: 11.5px;
            padding: 3px 8px;
            border-radius: 6px;
            margin-top: 6px;
        }
        .doc-expiry-pill.valid {
            background: #f1f5f9;
            color: #475569;
        }
        .doc-expiry-pill.expired {
            background: #fee2e2;
            color: #991b1b;
            font-weight: 700;
        }
        .doc-expiry-pill.expiring {
            background: #fef3c7;
            color: #92400e;
            font-weight: 600;
        }

        /* Verified By Seal */
        .doc-verified-seal {
            font-size: 11.5px;
            color: #059669;
            display: flex;
            align-items: center;
            gap: 5px;
            margin: 8px 0;
            font-weight: 600;
        }

        /* Action Buttons */
        .doc-slot-actions {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-top: 14px;
            padding-top: 12px;
            border-top: 1px solid #f1f5f9;
        }
        .btn-doc-action {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            padding: 8px 14px;
            border-radius: 8px;
            font-size: 12.5px;
            font-weight: 600;
            cursor: pointer;
            border: 1px solid transparent;
            transition: all 0.2s;
            text-decoration: none;
        }
        .btn-doc-view {
            background: #f1f5f9;
            color: #334155;
            border-color: #e2e8f0;
        }
        .btn-doc-view:hover { background: #e2e8f0; color: #0f172a; }
        .btn-doc-upload {
            background: #2563eb;
            color: #ffffff;
            flex: 1;
        }
        .btn-doc-upload:hover { background: #1d4ed8; }
        .btn-doc-reupload {
            background: #ef4444;
            color: #ffffff;
            flex: 1;
        }
        .btn-doc-reupload:hover { background: #dc2626; }
        .btn-doc-remove {
            background: transparent;
            color: #94a3b8;
            padding: 8px;
            border-radius: 8px;
            border: none;
            cursor: pointer;
        }
        .btn-doc-remove:hover { color: #ef4444; background: #fee2e2; }

        /* Document Modal Styles */
        .doc-modal-overlay {
            position: fixed;
            top: 0; left: 0; right: 0; bottom: 0;
            background: rgba(15, 23, 42, 0.6);
            backdrop-filter: blur(4px);
            z-index: 2000;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        .doc-modal-box {
            background: #ffffff;
            border-radius: 18px;
            width: 100%;
            max-width: 540px;
            box-shadow: 0 20px 40px -10px rgba(0,0,0,0.25);
            overflow: hidden;
            animation: modalPop 0.25s cubic-bezier(0.16, 1, 0.3, 1);
        }
        @keyframes modalPop {
            0% { opacity: 0; transform: scale(0.95); }
            100% { opacity: 1; transform: scale(1); }
        }
        .doc-modal-header {
            padding: 20px 24px;
            background: #f8fafc;
            border-bottom: 1px solid #e2e8f0;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .doc-modal-header h3 {
            margin: 0;
            font-size: 17px;
            font-weight: 700;
            color: #0f172a;
        }
        .doc-modal-close {
            background: transparent;
            border: none;
            font-size: 20px;
            color: #94a3b8;
            cursor: pointer;
            padding: 4px;
            line-height: 1;
        }
        .doc-modal-close:hover { color: #0f172a; }
        .doc-modal-body {
            padding: 24px;
        }
        .doc-dropzone {
            border: 2px dashed #cbd5e1;
            border-radius: 14px;
            padding: 30px 20px;
            text-align: center;
            background: #f8fafc;
            cursor: pointer;
            transition: all 0.2s;
            position: relative;
        }
        .doc-dropzone:hover, .doc-dropzone.dragover {
            border-color: #2563eb;
            background: #eff6ff;
        }
        .doc-dropzone input[type="file"] {
            position: absolute;
            top: 0; left: 0; width: 100%; height: 100%;
            opacity: 0;
            cursor: pointer;
        }
        .doc-dropzone-icon {
            font-size: 36px;
            color: #64748b;
            margin-bottom: 10px;
        }
        .doc-dropzone.dragover .doc-dropzone-icon { color: #2563eb; }
        .doc-dropzone-text {
            font-size: 14px;
            font-weight: 600;
            color: #1e293b;
            margin-bottom: 4px;
        }
        .doc-dropzone-sub {
            font-size: 12px;
            color: #94a3b8;
        }
        .doc-file-selected-pill {
            display: none;
            align-items: center;
            gap: 8px;
            padding: 8px 14px;
            background: #eff6ff;
            border: 1px solid #bfdbfe;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 600;
            color: #1e40af;
            margin-top: 12px;
        }
        .doc-template-pill {
            background: #f1f5f9;
            border: 1px solid #cbd5e1;
            border-radius: 6px;
            padding: 4px 9px;
            font-size: 11.5px;
            color: #334155;
            cursor: pointer;
            transition: all 0.15s ease;
        }
        .doc-template-pill:hover {
            background: #e2e8f0;
            color: #0f172a;
            border-color: #94a3b8;
        }

        @media (max-width: 900px) {
            .doc-kpi-grid { grid-template-columns: repeat(2, 1fr); }
            .doc-cards-grid { grid-template-columns: 1fr; }
        }
        @media (max-width: 600px) {
            .doc-kpi-grid { grid-template-columns: 1fr; }
            .doc-filter-toolbar { flex-direction: column; align-items: stretch; }
            .doc-stage-nav { width: 100%; }
        }
        .dashboard-toast {
            position: fixed;
            right: 24px;
            bottom: 24px;
            z-index: 1500;
            display: flex;
            align-items: flex-start;
            gap: 12px;
            width: min(420px, calc(100vw - 32px));
            padding: 16px;
            border: 1px solid #dbe3f0;
            border-left: 4px solid #2563eb;
            border-radius: 12px;
            background: #fff;
            box-shadow: 0 12px 36px rgba(15, 23, 42, .2);
            color: #1e293b;
            opacity: 0;
            transform: translateY(12px);
            transition: opacity .2s ease, transform .2s ease;
        }
        .dashboard-toast.is-visible { opacity: 1; transform: translateY(0); }
        .dashboard-toast[data-type="success"] { border-left-color: #16a34a; }
        .dashboard-toast[data-type="error"] { border-left-color: #dc2626; }
        .dashboard-toast[data-type="warning"] { border-left-color: #d97706; }
        .dashboard-toast[hidden] { display: none; }
        .dashboard-toast-copy { flex: 1; font-size: 14px; line-height: 1.5; }
        .dashboard-toast-actions { display: flex; flex: 0 0 auto; gap: 8px; }
        .dashboard-toast-actions button,
        .dashboard-toast-close {
            border: 1px solid #cbd5e1;
            border-radius: 7px;
            padding: 7px 10px;
            background: #fff;
            color: #334155;
            font: inherit;
            font-size: 12px;
            font-weight: 600;
            cursor: pointer;
        }
        .dashboard-toast-actions .dashboard-toast-confirm {
            border-color: #2563eb;
            background: #2563eb;
            color: #fff;
        }
        .dashboard-toast-close {
            border: 0;
            padding: 0 2px;
            font-size: 18px;
            line-height: 1;
        }
    </style>
    <style>
        .student-bot-launcher {
            position: fixed;
            right: 40px;
            bottom: 40px;
            z-index: 1100;
            display: flex;
            align-items: center;
            gap: 9px;
            border: 0;
            border-radius: 30px;
            padding: 14px 24px;
            background: #2563eb;
            color: #fff;
            box-shadow: 0 4px 16px rgba(37, 99, 235, 0.3);
            font-size: 15px;
            font-weight: 700;
            cursor: pointer;
        }
        .student-bot-launcher:hover { background: #1d4ed8; }
        .student-bot-panel[hidden] { display: none !important; }
        .student-bot-panel {
            position: fixed;
            right: 40px;
            bottom: 105px;
            z-index: 1101;
            display: flex;
            flex-direction: column;
            width: min(380px, calc(100vw - 32px));
            height: min(540px, calc(100vh - 140px));
            min-height: 360px;
            overflow: hidden;
            border: 1px solid #dbe3f0;
            border-radius: 18px;
            background: #fff;
            box-shadow: 0 20px 55px rgba(15, 23, 42, 0.22);
        }
        .student-bot-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 16px 18px;
            background: #2563eb;
            color: #fff;
        }
        .student-bot-header h2 { margin: 0; font-size: 16px; }
        .student-bot-header p { margin: 4px 0 0; color: #dbeafe; font-size: 12px; }
        .student-bot-close {
            display: grid;
            width: 34px;
            height: 34px;
            place-items: center;
            border: 0;
            border-radius: 50%;
            background: transparent;
            color: #fff;
            font-size: 18px;
            cursor: pointer;
        }
        .student-bot-close:hover { background: rgba(255, 255, 255, 0.16); }
        .student-bot-messages {
            display: flex;
            flex: 1;
            flex-direction: column;
            gap: 12px;
            overflow-y: auto;
            padding: 18px;
            background: #f8fafc;
        }
        .student-bot-message {
            max-width: 88%;
            padding: 11px 13px;
            border-radius: 14px;
            color: #1e293b;
            font-size: 13px;
            line-height: 1.55;
            white-space: pre-line;
            overflow-wrap: anywhere;
        }
        .student-bot-message.bot { align-self: flex-start; border: 1px solid #e2e8f0; background: #fff; }
        .student-bot-message.student { align-self: flex-end; background: #dbeafe; }
        .student-bot-suggestions {
            display: flex;
            flex-wrap: wrap;
            gap: 7px;
            padding: 0 14px 12px;
            background: #f8fafc;
        }
        .student-bot-suggestions button {
            border: 1px solid #bfdbfe;
            border-radius: 16px;
            padding: 7px 10px;
            background: #fff;
            color: #1d4ed8;
            font-size: 11px;
            cursor: pointer;
        }
        .student-bot-suggestions button:hover { background: #eff6ff; }
        .student-bot-form {
            display: flex;
            gap: 8px;
            padding: 12px;
            border-top: 1px solid #e2e8f0;
            background: #fff;
        }
        .student-bot-form input {
            min-width: 0;
            flex: 1;
            border: 1px solid #cbd5e1;
            border-radius: 20px;
            padding: 10px 13px;
            color: #0f172a;
            font-size: 13px;
        }
        .student-bot-form input:focus { outline: 2px solid #93c5fd; border-color: #2563eb; }
        .student-bot-form button {
            width: 40px;
            height: 40px;
            flex: 0 0 40px;
            border: 0;
            border-radius: 50%;
            background: #2563eb;
            color: #fff;
            cursor: pointer;
        }
        /* Dashboard Home Overhaul Styles */
        .dash-hero {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            margin-bottom: 24px;
            flex-wrap: wrap;
            gap: 16px;
        }
        .dash-hero-title {
            font-size: 28px;
            font-weight: 800;
            color: #0f172a;
            letter-spacing: -0.02em;
            margin-bottom: 6px;
        }
        .dash-hero-subtitle {
            font-size: 14px;
            color: #475569;
            font-weight: 500;
        }
        .dash-hero-pill {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: rgba(37, 99, 235, 0.1);
            color: #1d4ed8;
            font-size: 12px;
            font-weight: 700;
            padding: 6px 14px;
            border-radius: 999px;
            border: 1px solid rgba(37, 99, 235, 0.2);
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }
        .dash-hero-pill .pulse-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: #2563eb;
            box-shadow: 0 0 0 0 rgba(37, 99, 235, 0.7);
            animation: pulse-ring 1.8s infinite;
        }
        @keyframes pulse-ring {
            0% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(37, 99, 235, 0.7); }
            70% { transform: scale(1); box-shadow: 0 0 0 6px rgba(37, 99, 235, 0); }
            100% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(37, 99, 235, 0); }
        }

        /* 4 KPI Grid */
        .dash-kpi-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 16px;
            margin-bottom: 24px;
        }
        .dash-kpi-card {
            background: rgba(255, 255, 255, 0.75) !important;
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.8) !important;
            border-radius: 18px !important;
            padding: 20px;
            box-shadow: 0 8px 24px rgba(15, 23, 42, 0.04);
            cursor: pointer;
            transition: all 0.25s ease;
            position: relative;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }
        .dash-kpi-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 14px 30px rgba(37, 99, 235, 0.12);
            border-color: rgba(37, 99, 235, 0.3) !important;
        }
        .dash-kpi-top {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 12px;
        }
        .dash-kpi-icon {
            width: 44px;
            height: 44px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
        }
        .dash-kpi-card.kpi-blue .dash-kpi-icon { background: rgba(37, 99, 235, 0.12); color: #2563eb; }
        .dash-kpi-card.kpi-emerald .dash-kpi-icon { background: rgba(16, 185, 129, 0.12); color: #10b981; }
        .dash-kpi-card.kpi-purple .dash-kpi-icon { background: rgba(147, 51, 234, 0.12); color: #9333ea; }
        .dash-kpi-card.kpi-amber .dash-kpi-icon { background: rgba(245, 158, 11, 0.12); color: #d97706; }
        .dash-kpi-value {
            font-size: 26px;
            font-weight: 800;
            color: #0f172a;
            line-height: 1.1;
            margin-bottom: 4px;
            word-break: break-word;
        }
        .dash-kpi-label {
            font-size: 13px;
            font-weight: 600;
            color: #64748b;
        }
        .dash-kpi-action {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 12px;
            font-weight: 700;
            color: #2563eb;
            margin-top: 14px;
            padding-top: 10px;
            border-top: 1px dashed rgba(226, 232, 240, 0.8);
        }

        /* Live Application Tracker Stepper */
        .dash-journey-card {
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.95), rgba(248, 250, 252, 0.9)) !important;
            backdrop-filter: blur(16px);
            border: 1px solid rgba(226, 232, 240, 0.9) !important;
            border-radius: 20px !important;
            padding: 24px;
            margin-bottom: 28px;
            box-shadow: 0 10px 30px rgba(15, 23, 42, 0.05);
        }
        .journey-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 24px;
            flex-wrap: wrap;
            gap: 12px;
        }
        .journey-header-left {
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .journey-header-icon {
            width: 42px;
            height: 42px;
            border-radius: 12px;
            background: linear-gradient(135deg, #1d4ed8, #3b82f6);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.3);
        }
        .journey-header-title {
            font-size: 18px;
            font-weight: 800;
            color: #0f172a;
            margin: 0;
        }
        .journey-header-sub {
            font-size: 13px;
            color: #64748b;
            margin-top: 2px;
        }
        .journey-header-badges {
            display: flex;
            align-items: center;
            gap: 8px;
            flex-wrap: wrap;
        }
        .journey-pill-status {
            background: #dbeafe;
            color: #1e40af;
            font-size: 12px;
            font-weight: 700;
            padding: 5px 12px;
            border-radius: 8px;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }
        .journey-pill-lead {
            background: #f1f5f9;
            color: #475569;
            font-size: 12px;
            font-weight: 600;
            padding: 5px 10px;
            border-radius: 8px;
            border: 1px solid #e2e8f0;
        }

        /* Stepper steps */
        .journey-stepper-wrap {
            position: relative;
            padding: 10px 0 20px 0;
        }
        .journey-track-line {
            position: absolute;
            top: 32px;
            left: 5%;
            right: 5%;
            height: 4px;
            background: #e2e8f0;
            z-index: 1;
            border-radius: 4px;
        }
        .journey-track-fill {
            height: 100%;
            background: linear-gradient(90deg, #10b981, #2563eb);
            border-radius: 4px;
            transition: width 0.6s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .journey-steps-grid {
            position: relative;
            display: grid;
            grid-template-columns: repeat(6, 1fr);
            z-index: 2;
        }
        .journey-step-node {
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            padding: 0 6px;
        }
        .journey-step-circle {
            width: 44px;
            height: 44px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 16px;
            font-weight: 700;
            background: white;
            border: 3px solid #cbd5e1;
            color: #94a3b8;
            margin-bottom: 10px;
            transition: all 0.3s ease;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
        }
        .journey-step-node.completed .journey-step-circle {
            background: #10b981;
            border-color: #10b981;
            color: white;
            box-shadow: 0 4px 14px rgba(16, 185, 129, 0.35);
        }
        .journey-step-node.active .journey-step-circle {
            background: #2563eb;
            border-color: #93c5fd;
            color: white;
            box-shadow: 0 0 0 6px rgba(37, 99, 235, 0.18);
            transform: scale(1.08);
        }
        .journey-step-title {
            font-size: 13px;
            font-weight: 700;
            color: #334155;
            line-height: 1.25;
            margin-bottom: 3px;
        }
        .journey-step-node.completed .journey-step-title {
            color: #0f172a;
        }
        .journey-step-node.active .journey-step-title {
            color: #1d4ed8;
        }
        .journey-step-desc {
            font-size: 11px;
            color: #64748b;
            line-height: 1.2;
            max-width: 110px;
        }
        .journey-footer {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-top: 18px;
            padding-top: 16px;
            border-top: 1px solid rgba(226, 232, 240, 0.8);
            flex-wrap: wrap;
            gap: 12px;
        }
        .journey-footer-meta {
            display: flex;
            align-items: center;
            gap: 20px;
            flex-wrap: wrap;
            font-size: 13px;
            color: #475569;
        }
        .journey-footer-meta strong {
            color: #0f172a;
        }
        .btn-journey-action {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: #2563eb;
            color: white;
            padding: 8px 16px;
            border-radius: 10px;
            font-size: 13px;
            font-weight: 600;
            border: none;
            cursor: pointer;
            text-decoration: none;
            transition: all 0.2s ease;
        }
        .btn-journey-action:hover {
            background: #1d4ed8;
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.3);
            color: white;
        }

        /* 2-Column Split Layout */
        .dash-split-layout {
            display: grid;
            grid-template-columns: 1.5fr 1fr;
            gap: 24px;
            margin-bottom: 28px;
            align-items: start;
        }

        /* Checklist Card */
        .dash-checklist-card {
            background: rgba(255, 255, 255, 0.85) !important;
            backdrop-filter: blur(16px);
            border: 1px solid rgba(226, 232, 240, 0.9) !important;
            border-radius: 20px !important;
            padding: 24px;
            box-shadow: 0 10px 30px rgba(15, 23, 42, 0.04);
        }
        .checklist-top {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 14px;
        }
        .checklist-title-wrap {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .checklist-title-wrap i {
            color: #2563eb;
            font-size: 18px;
        }
        .checklist-title {
            font-size: 17px;
            font-weight: 800;
            color: #0f172a;
            margin: 0;
        }
        .checklist-counter {
            font-size: 12px;
            font-weight: 700;
            padding: 4px 10px;
            border-radius: 20px;
            background: #eff6ff;
            color: #1e40af;
            border: 1px solid #bfdbfe;
        }
        .checklist-progress-bar {
            height: 6px;
            border-radius: 999px;
            background: #e2e8f0;
            overflow: hidden;
            margin-bottom: 20px;
        }
        .checklist-progress-fill {
            height: 100%;
            background: linear-gradient(90deg, #10b981, #2563eb);
            border-radius: 999px;
            transition: width 0.4s ease;
        }
        .checklist-list {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }
        .checklist-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 14px 16px;
            border-radius: 14px;
            background: white;
            border: 1px solid #edf2f7;
            transition: all 0.2s ease;
            gap: 14px;
        }
        .checklist-row:hover {
            border-color: #cbd5e1;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.04);
            transform: translateX(2px);
        }
        .checklist-row.is-done {
            background: #f8fafc;
            border-color: #e2e8f0;
        }
        .checklist-left {
            display: flex;
            align-items: center;
            gap: 12px;
            flex: 1;
        }
        .checklist-status-icon {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
            flex-shrink: 0;
        }
        .checklist-row.is-done .checklist-status-icon {
            background: #dcfce7;
            color: #16a34a;
        }
        .checklist-row:not(.is-done) .checklist-status-icon {
            background: #fef3c7;
            color: #d97706;
        }
        .checklist-info h4 {
            font-size: 14px;
            font-weight: 700;
            color: #1e293b;
            margin: 0 0 2px 0;
        }
        .checklist-info p {
            font-size: 12px;
            color: #64748b;
            margin: 0;
            line-height: 1.35;
        }
        .checklist-action-btn {
            padding: 7px 14px;
            border-radius: 8px;
            font-size: 12px;
            font-weight: 700;
            border: none;
            cursor: pointer;
            white-space: nowrap;
            transition: all 0.2s ease;
        }
        .checklist-row:not(.is-done) .checklist-action-btn {
            background: #2563eb;
            color: white;
        }
        .checklist-row:not(.is-done) .checklist-action-btn:hover {
            background: #1d4ed8;
            box-shadow: 0 2px 8px rgba(37, 99, 235, 0.25);
        }
        .checklist-row.is-done .checklist-action-btn {
            background: #f1f5f9;
            color: #475569;
        }
        .checklist-row.is-done .checklist-action-btn:hover {
            background: #e2e8f0;
            color: #0f172a;
        }

        /* Badges */
        .dash-badge-green { background: #dcfce7; color: #15803d; }
        .dash-badge-blue { background: #dbeafe; color: #1d4ed8; }
        .dash-badge-amber { background: #fef3c7; color: #b45309; }
        .dash-badge-red { background: #fee2e2; color: #b91c1c; }
        .dash-badge-neutral { background: #f1f5f9; color: #475569; }

        /* Counselor Card */
        .dash-counselor-card {
            background: linear-gradient(145deg, rgba(255, 255, 255, 0.95), rgba(240, 249, 255, 0.85)) !important;
            backdrop-filter: blur(16px);
            border: 1px solid rgba(186, 230, 253, 0.8) !important;
            border-radius: 20px !important;
            padding: 24px;
            box-shadow: 0 10px 30px rgba(2, 132, 199, 0.08);
            position: relative;
            overflow: hidden;
        }
        .dash-counselor-card::before {
            content: '';
            position: absolute;
            top: -40px;
            right: -40px;
            width: 120px;
            height: 120px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(14, 165, 233, 0.15) 0%, transparent 70%);
            z-index: 0;
            pointer-events: none;
        }
        .counselor-top {
            position: relative;
            z-index: 1;
            display: flex;
            align-items: center;
            gap: 16px;
            margin-bottom: 18px;
        }
        .counselor-avatar-wrap {
            position: relative;
        }
        .counselor-avatar {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background: linear-gradient(135deg, #0284c7, #2563eb);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 22px;
            font-weight: 700;
            box-shadow: 0 4px 14px rgba(2, 132, 199, 0.3);
        }
        .counselor-online-badge {
            position: absolute;
            bottom: 2px;
            right: 2px;
            width: 14px;
            height: 14px;
            background: #10b981;
            border: 2px solid white;
            border-radius: 50%;
        }
        .counselor-meta h3 {
            font-size: 17px;
            font-weight: 800;
            color: #0f172a;
            margin: 0 0 2px 0;
        }
        .counselor-role {
            font-size: 12px;
            font-weight: 600;
            color: #0284c7;
            text-transform: uppercase;
            letter-spacing: 0.03em;
        }
        .counselor-office {
            font-size: 12px;
            color: #64748b;
            margin-top: 3px;
        }
        .counselor-body {
            position: relative;
            z-index: 1;
            background: rgba(255, 255, 255, 0.7);
            border-radius: 12px;
            padding: 12px 14px;
            border: 1px solid rgba(226, 232, 240, 0.7);
            margin-bottom: 20px;
            font-size: 13px;
            color: #334155;
            line-height: 1.45;
        }
        .counselor-actions {
            position: relative;
            z-index: 1;
            display: flex;
            flex-direction: column;
            gap: 10px;
        }
        .btn-counselor-wa {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            background: #25d366;
            color: white;
            font-size: 14px;
            font-weight: 700;
            padding: 12px;
            border-radius: 12px;
            text-decoration: none;
            transition: all 0.2s ease;
            box-shadow: 0 4px 14px rgba(37, 211, 102, 0.3);
        }
        .btn-counselor-wa:hover {
            background: #20bd5a;
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 6px 18px rgba(37, 211, 102, 0.4);
        }
        .counselor-dual-buttons {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
        }
        .btn-counselor-call {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            background: #eff6ff;
            color: #1d4ed8;
            font-size: 13px;
            font-weight: 700;
            padding: 10px;
            border-radius: 10px;
            text-decoration: none;
            border: 1px solid #bfdbfe;
            transition: all 0.2s ease;
        }
        .btn-counselor-call:hover {
            background: #dbeafe;
            color: #1e40af;
        }
        .btn-counselor-bot {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            background: #fdf4ff;
            color: #9333ea;
            font-size: 13px;
            font-weight: 700;
            padding: 10px;
            border-radius: 10px;
            border: 1px solid #f0abfc;
            cursor: pointer;
            transition: all 0.2s ease;
        }
        .btn-counselor-bot:hover {
            background: #fae8ff;
            color: #7e22ce;
        }

        /* Recommended Courses Preview */
        .dash-recommended-wrap {
            margin-bottom: 24px;
        }
        .recommended-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 16px;
        }
        .recommended-title {
            font-size: 18px;
            font-weight: 800;
            color: #0f172a;
            margin: 0;
        }
        .recommended-link {
            font-size: 13px;
            font-weight: 700;
            color: #2563eb;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 4px;
            cursor: pointer;
        }
        .recommended-link:hover {
            text-decoration: underline;
        }
        .recommended-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 16px;
        }
        .rec-course-card {
            background: white !important;
            border: 1px solid #e2e8f0 !important;
            border-radius: 16px !important;
            padding: 18px;
            box-shadow: 0 4px 16px rgba(0,0,0,0.03);
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            transition: all 0.25s ease;
        }
        .rec-course-card:hover {
            transform: translateY(-4px);
            border-color: #93c5fd !important;
            box-shadow: 0 10px 25px rgba(37, 99, 235, 0.1);
        }
        .rec-course-badge {
            font-size: 11px;
            font-weight: 700;
            color: #1e40af;
            background: #eff6ff;
            padding: 3px 8px;
            border-radius: 6px;
            display: inline-block;
            margin-bottom: 10px;
        }
        .rec-course-title {
            font-size: 15px;
            font-weight: 700;
            color: #0f172a;
            margin-bottom: 4px;
            line-height: 1.3;
        }
        .rec-course-uni {
            font-size: 12px;
            color: #64748b;
            margin-bottom: 14px;
            display: flex;
            align-items: center;
            gap: 6px;
        }
        .rec-course-footer {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding-top: 12px;
            border-top: 1px solid #f1f5f9;
        }
        .rec-course-fee {
            font-size: 12px;
            font-weight: 700;
            color: #0f172a;
        }
        .rec-course-btn {
            background: #2563eb;
            color: white;
            font-size: 11px;
            font-weight: 700;
            padding: 5px 10px;
            border-radius: 6px;
            border: none;
            cursor: pointer;
            transition: background 0.2s;
        }
        .rec-course-btn:hover {
            background: #1d4ed8;
        }

        /* Responsive Breakpoints */
        @media (max-width: 1080px) {
            .dash-kpi-grid { grid-template-columns: repeat(2, 1fr); }
            .dash-split-layout { grid-template-columns: 1fr; }
            .recommended-grid { grid-template-columns: repeat(2, 1fr); }
        }
        @media (max-width: 768px) {
            .dash-kpi-grid { grid-template-columns: 1fr; }
            .journey-track-line { display: none; }
            .journey-steps-grid { grid-template-columns: repeat(2, 1fr); gap: 16px; }
            .recommended-grid { grid-template-columns: 1fr; }
            .counselor-dual-buttons { grid-template-columns: 1fr; }
            .journey-header { flex-direction: column; align-items: flex-start; }
            .journey-footer { flex-direction: column; align-items: flex-start; }
        }
        @media (max-width: 520px) {
            .student-bot-launcher { right: 16px; bottom: 20px; }
            .student-bot-panel { right: 16px; bottom: 82px; }
        }
    </style>
</head>
<body>

    <!-- Header -->
    <header class="dash-header">
        <a href="index.php" class="dash-header-logo">
        <img src="assets/images/Logo_old.png" alt="Bluestone Overseas">
        </a>
        <div class="dash-header-right">
            <a href="index.php" class="back-link">
                <i class="fa-solid fa-chevron-left"></i> Back
            </a>
            <!-- Notification Bell -->
            <div class="notif-bell-wrap" id="notif-bell-wrap" style="position:relative;">
                <button onclick="toggleNotifPanel()" style="background:none;border:none;cursor:pointer;position:relative;padding:8px;" aria-label="Notifications">
                    <i class="fa-regular fa-bell" style="font-size:20px;color:#9ca3af;"></i>
                    <?php if ($unreadNotifCount > 0): ?>
                    <span id="notif-badge" style="position:absolute;top:2px;right:2px;min-width:18px;height:18px;background:#ef4444;color:white;border-radius:50%;font-size:11px;font-weight:700;display:flex;align-items:center;justify-content:center;padding:0 4px;"><?php echo $unreadNotifCount > 9 ? '9+' : $unreadNotifCount; ?></span>
                    <?php else: ?>
                    <span id="notif-badge" style="display:none;position:absolute;top:2px;right:2px;min-width:18px;height:18px;background:#ef4444;color:white;border-radius:50%;font-size:11px;font-weight:700;display:flex;align-items:center;justify-content:center;padding:0 4px;"></span>
                    <?php endif; ?>
                </button>
                <div id="notif-panel" style="display:none;position:absolute;top:100%;right:0;width:360px;max-height:420px;background:white;border-radius:12px;box-shadow:0 10px 40px rgba(0,0,0,0.15);border:1px solid #e2e8f0;z-index:200;overflow:hidden;">
                    <div style="padding:16px 20px;border-bottom:1px solid #e2e8f0;display:flex;justify-content:space-between;align-items:center;">
                        <h4 style="margin:0;font-size:16px;font-weight:700;color:#1e293b;">Notifications</h4>
                        <button onclick="markAllNotifRead()" style="background:none;border:none;color:#2563eb;font-size:13px;font-weight:600;cursor:pointer;">Mark all read</button>
                    </div>
                    <div id="notif-list" style="max-height:340px;overflow-y:auto;padding:8px 0;">
                        <div style="text-align:center;padding:32px 20px;color:#94a3b8;font-size:14px;">
                            <i class="fa-regular fa-bell-slash" style="font-size:2rem;margin-bottom:8px;display:block;"></i>
                            Loading notifications...
                        </div>
                    </div>
                </div>
            </div>
            <div class="user-avatar">
                <?php echo htmlspecialchars($initials); ?>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <div class="main-container">
        
        <!-- Sidebar -->
        <aside class="sidebar">
            <a href="?tab=dashboard" class="nav-item <?php echo $activeTab === 'dashboard' ? 'active' : ''; ?>" data-target="view-dashboard">
                <i class="fa-solid fa-house"></i>
                Dashboard
            </a>
            <a href="?tab=courses" class="nav-item <?php echo $activeTab === 'courses' ? 'active' : ''; ?>" data-target="view-courses">
                <i class="fa-solid fa-compass"></i>
                Explore your courses
            </a>
            <a href="?tab=profile" class="nav-item <?php echo $activeTab === 'profile' ? 'active' : ''; ?>" data-target="view-profile">
                <i class="fa-regular fa-user"></i>
                Profile
            </a>
            <a href="?tab=shortlisted" class="nav-item <?php echo $activeTab === 'shortlisted' ? 'active' : ''; ?>" data-target="view-shortlisted">
                <i class="fa-regular fa-heart"></i>
                Shortlisted
            </a>
            <a href="?tab=application" class="nav-item <?php echo $activeTab === 'application' ? 'active' : ''; ?>" data-target="view-application">
                <i class="fa-solid fa-bolt"></i>
                Application status
            </a>
            <a href="?tab=documents" class="nav-item <?php echo $activeTab === 'documents' ? 'active' : ''; ?>" data-target="view-documents">
                <i class="fa-solid fa-file-arrow-up"></i>
                Documents
                <?php if ($documentCount > 0): ?><span style="margin-left:auto;background:#e2e8f0;color:#475569;font-size:11px;font-weight:700;padding:2px 8px;border-radius:10px;"><?php echo $documentCount; ?></span><?php endif; ?>
            </a>
            <a href="?tab=community" class="nav-item <?php echo $activeTab === 'community' ? 'active' : ''; ?>" data-target="view-community">
                <i class="fa-solid fa-users"></i>
                Community
            </a>
        </aside>

        <!-- Content Area -->
        <main class="content-area">
            
            <div id="view-dashboard" class="tab-view <?php echo $activeTab === 'dashboard' ? 'active-view' : ''; ?>">
                <!-- Hero Header -->
                <div class="dash-hero">
                    <div>
                        <h1 class="dash-hero-title">Hi <?php echo htmlspecialchars($firstName); ?> 👋</h1>
                        <p class="dash-hero-subtitle">Welcome back to your Bluestone Study Abroad Portal. Track your admissions, checklist, and counselor updates.</p>
                    </div>
                    <div>
                        <span class="dash-hero-pill">
                            <span class="pulse-dot"></span> 2026/2027 Admissions Live
                        </span>
                    </div>
                </div>

                <!-- 4 Top KPI Cards -->
                <div class="dash-kpi-grid">
                    <!-- Profile Card -->
                    <div class="dash-kpi-card kpi-blue" onclick="switchDashboardTab('view-profile');">
                        <div class="dash-kpi-top">
                            <span class="dash-kpi-label">Profile Readiness</span>
                            <div class="dash-kpi-icon"><i class="fa-regular fa-user"></i></div>
                        </div>
                        <div class="dash-kpi-value"><?php echo $profileCompletion; ?>%</div>
                        <div role="progressbar" aria-label="Profile completion" aria-valuemin="0" aria-valuemax="100" aria-valuenow="<?php echo $profileCompletion; ?>" style="height: 5px; margin-top: 8px; overflow: hidden; border-radius: 999px; background: rgba(37, 99, 235, 0.12);">
                            <div style="width: <?php echo $profileCompletion; ?>%; height: 100%; border-radius: inherit; background: #2563eb; transition: width 0.3s ease;"></div>
                        </div>
                        <div class="dash-kpi-action">View & complete <i class="fa-solid fa-arrow-right" style="font-size:10px;"></i></div>
                    </div>

                    <!-- Application Status Card -->
                    <div class="dash-kpi-card kpi-emerald" onclick="switchDashboardTab('view-application');">
                        <div class="dash-kpi-top">
                            <span class="dash-kpi-label"><?php echo $hasApplication ? htmlspecialchars($university) : 'Application Stage'; ?></span>
                            <div class="dash-kpi-icon"><i class="fa-solid fa-bolt"></i></div>
                        </div>
                        <div class="dash-kpi-value" style="font-size: 20px; line-height: 1.3;"><?php echo htmlspecialchars($currentStatus); ?></div>
                        <span style="font-size: 11px; color: #64748b; margin-top: 4px; display: block;"><?php echo $hasApplication ? htmlspecialchars($course) : '1-on-1 counseling active'; ?></span>
                        <div class="dash-kpi-action" style="color: #10b981;">Track journey <i class="fa-solid fa-arrow-right" style="font-size:10px;"></i></div>
                    </div>

                    <!-- Shortlists Card -->
                    <div class="dash-kpi-card kpi-purple" onclick="switchDashboardTab('view-shortlisted');">
                        <div class="dash-kpi-top">
                            <span class="dash-kpi-label">Saved Courses</span>
                            <div class="dash-kpi-icon"><i class="fa-regular fa-heart"></i></div>
                        </div>
                        <div class="dash-kpi-value"><?php echo $shortlistCount; ?> <span style="font-size:14px; font-weight:600; color:#64748b;">programs</span></div>
                        <span style="font-size: 11px; color: #64748b; margin-top: 4px; display: block;">Heart courses to compare</span>
                        <div class="dash-kpi-action" style="color: #9333ea;">Review shortlist <i class="fa-solid fa-arrow-right" style="font-size:10px;"></i></div>
                    </div>

                    <!-- Documents Card -->
                    <div class="dash-kpi-card kpi-amber" onclick="switchDashboardTab('view-documents');">
                        <div class="dash-kpi-top">
                            <span class="dash-kpi-label">Application Docs</span>
                            <div class="dash-kpi-icon"><i class="fa-solid fa-file-arrow-up"></i></div>
                        </div>
                        <div class="dash-kpi-value"><?php echo $documentCount; ?> <span style="font-size:14px; font-weight:600; color:#64748b;">files</span></div>
                        <span style="font-size: 11px; color: #64748b; margin-top: 4px; display: block;">Synced with CRM (<?php echo $docVerifiedCount; ?> Verified)</span>
                        <div class="dash-kpi-action" style="color: #d97706;">Manage files <i class="fa-solid fa-arrow-right" style="font-size:10px;"></i></div>
                    </div>
                </div>

                <!-- Live Application Tracker Stepper Card -->
                <?php
                    $stepperFillPercent = (int)round(($journeyStepIndex / (count($journeySteps) - 1)) * 100);
                ?>
                <div class="dash-journey-card">
                    <div class="journey-header">
                        <div class="journey-header-left">
                            <div class="journey-header-icon">
                                <i class="fa-solid fa-compass"></i>
                            </div>
                            <div>
                                <h3 class="journey-header-title">Live Application Tracker</h3>
                                <p class="journey-header-sub">
                                    <?php if ($hasApplication): ?>
                                        Target: <strong><?php echo htmlspecialchars($university); ?></strong> • <?php echo htmlspecialchars($course); ?> <?php echo !empty($applicationIntake) ? '(' . htmlspecialchars($applicationIntake) . ')' : ''; ?>
                                    <?php else: ?>
                                        Your 6-stage roadmap from profile assessment to international university enrollment.
                                    <?php endif; ?>
                                </p>
                            </div>
                        </div>
                        <div class="journey-header-badges">
                            <span class="journey-pill-status">
                                <i class="fa-solid fa-circle-dot"></i> Current Stage: <?php echo htmlspecialchars($currentStatus); ?>
                            </span>
                            <?php if (!empty($crmData['lead_code'])): ?>
                                <span class="journey-pill-lead">Ref: <?php echo htmlspecialchars($crmData['lead_code']); ?></span>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Stepper Track -->
                    <div class="journey-stepper-wrap">
                        <div class="journey-track-line">
                            <div class="journey-track-fill" style="width: <?php echo $stepperFillPercent; ?>%;"></div>
                        </div>
                        <div class="journey-steps-grid">
                            <?php foreach ($journeySteps as $idx => $step): ?>
                                <?php
                                    $stepClass = '';
                                    if ($idx < $journeyStepIndex) {
                                        $stepClass = 'completed';
                                    } elseif ($idx === $journeyStepIndex) {
                                        $stepClass = 'active';
                                    }
                                ?>
                                <div class="journey-step-node <?php echo $stepClass; ?>">
                                    <div class="journey-step-circle">
                                        <?php if ($idx < $journeyStepIndex): ?>
                                            <i class="fa-solid fa-check"></i>
                                        <?php elseif ($idx === $journeyStepIndex): ?>
                                            <i class="fa-solid <?php echo $step['icon']; ?>"></i>
                                        <?php else: ?>
                                            <span><?php echo ($idx + 1); ?></span>
                                        <?php endif; ?>
                                    </div>
                                    <div class="journey-step-title"><?php echo htmlspecialchars($step['title']); ?></div>
                                    <div class="journey-step-desc"><?php echo htmlspecialchars($step['desc']); ?></div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <!-- Journey Footer Meta -->
                    <div class="journey-footer">
                        <div class="journey-footer-meta">
                            <span><i class="fa-solid fa-user-tie" style="color:#2563eb; margin-right:4px;"></i> Assigned Advisor: <strong><?php echo htmlspecialchars($counselorName); ?></strong></span>
                            <?php if (!empty($pipelineStatus)): ?>
                                <span><i class="fa-solid fa-code-branch" style="color:#0284c7; margin-right:4px;"></i> Pipeline: <strong><?php echo htmlspecialchars($pipelineStatus); ?></strong></span>
                            <?php endif; ?>
                            <?php if (!empty($crmData['passport_no'])): ?>
                                <span><i class="fa-solid fa-passport" style="color:#10b981; margin-right:4px;"></i> Passport: <strong><?php echo htmlspecialchars($crmData['passport_no']); ?></strong></span>
                            <?php endif; ?>
                        </div>
                        <button type="button" class="btn-journey-action" onclick="switchDashboardTab('view-application');">
                            View Detailed Timeline <i class="fa-solid fa-arrow-right"></i>
                        </button>
                    </div>
                </div>

                <!-- 2-Column Split: Action Checklist & Counselor Card -->
                <div class="dash-split-layout">
                    <!-- Left Column: Pending Action Checklist -->
                    <div class="dash-checklist-card">
                        <div class="checklist-top">
                            <div class="checklist-title-wrap">
                                <i class="fa-solid fa-list-check"></i>
                                <h3 class="checklist-title">Next Action Checklist</h3>
                            </div>
                            <span class="checklist-counter"><?php echo $completedChecklistCount; ?> of <?php echo count($checklistItems); ?> Completed</span>
                        </div>
                        <div class="checklist-progress-bar">
                            <div class="checklist-progress-fill" style="width: <?php echo $checklistPercent; ?>%;"></div>
                        </div>

                        <div class="checklist-list">
                            <?php foreach ($checklistItems as $item): ?>
                                <div class="checklist-row <?php echo $item['is_completed'] ? 'is-done' : ''; ?>">
                                    <div class="checklist-left">
                                        <div class="checklist-status-icon">
                                            <?php if ($item['is_completed']): ?>
                                                <i class="fa-solid fa-check"></i>
                                            <?php else: ?>
                                                <i class="fa-solid fa-clock"></i>
                                            <?php endif; ?>
                                        </div>
                                        <div class="checklist-info">
                                            <h4><?php echo htmlspecialchars($item['title']); ?></h4>
                                            <p><?php echo htmlspecialchars($item['desc']); ?></p>
                                        </div>
                                    </div>
                                    <div style="display:flex; align-items:center; gap:8px;">
                                        <span class="dash-badge <?php echo $item['badge_class']; ?>" style="font-size:11px; font-weight:700; padding:4px 8px; border-radius:6px;">
                                            <?php echo htmlspecialchars($item['badge']); ?>
                                        </span>
                                        <button type="button" class="checklist-action-btn" onclick="if (event) { event.preventDefault(); event.stopPropagation(); } switchDashboardTab('<?php echo $item['target']; ?>'); return false;">
                                            <?php echo htmlspecialchars($item['action_label']); ?>
                                        </button>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <!-- Right Column: Counselor Card & Quick Support -->
                    <div style="display: flex; flex-direction: column; gap: 20px;">
                        <div class="dash-counselor-card">
                            <div class="counselor-top">
                                <div class="counselor-avatar-wrap">
                                    <div class="counselor-avatar">
                                        <?php
                                            $cInitials = '';
                                            $cParts = explode(' ', $counselorName);
                                            foreach ($cParts as $cp) {
                                                if (!empty($cp)) $cInitials .= strtoupper(substr($cp, 0, 1));
                                            }
                                            echo htmlspecialchars(substr($cInitials ?: 'BS', 0, 2));
                                        ?>
                                    </div>
                                    <div class="counselor-online-badge" title="Counseling Desk Active"></div>
                                </div>
                                <div class="counselor-meta">
                                    <h3><?php echo htmlspecialchars($counselorName); ?></h3>
                                    <div class="counselor-role"><?php echo $isCounselorAssigned ? 'Assigned Study Abroad Advisor' : 'Academic Advisory Desk'; ?></div>
                                    <div class="counselor-office"><i class="fa-solid fa-building-columns" style="font-size:11px;"></i> Bluestone Overseas Head Office</div>
                                </div>
                            </div>

                            <div class="counselor-body">
                                <?php if ($isCounselorAssigned): ?>
                                    Your dedicated advisor guides you through university shortlisting, statement of purpose (SOP) reviews, scholarship assistance, and visa mock interviews.
                                <?php else: ?>
                                    Our senior advisory team is reviewing your profile to match you with a country-specific admissions counselor. Connect with us anytime for immediate assistance!
                                <?php endif; ?>
                            </div>

                            <div class="counselor-actions">
                                <a href="<?php echo $counselorWaUrl; ?>" target="_blank" class="btn-counselor-wa">
                                    <i class="fa-brands fa-whatsapp" style="font-size:18px;"></i> Chat on WhatsApp
                                </a>
                                <div class="counselor-dual-buttons">
                                    <a href="tel:<?php echo htmlspecialchars($counselorPhone); ?>" class="btn-counselor-call">
                                        <i class="fa-solid fa-phone"></i> Call Advisor
                                    </a>
                                    <button type="button" class="btn-counselor-bot" onclick="document.getElementById('studentBotLauncher').click();">
                                        <i class="fa-regular fa-comment-dots"></i> AI Help Bot
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Promo App Banner -->
                        <div class="promo-banner" style="margin: 0; padding: 18px 20px;">
                            <div class="promo-content">
                                <div class="promo-icon" style="background:#2563eb; color:white; width:40px; height:40px; border-radius:10px; display:flex; align-items:center; justify-content:center;">
                                    <i class="fa-solid fa-mobile-screen" style="font-size:20px;"></i>
                                </div>
                                <div class="promo-text">
                                    <h4 style="font-size:14px; font-weight:700; margin:0 0 2px 0;">Bluestone Mobile App</h4>
                                    <p style="font-size:12px; margin:0; color:#475569;">Track updates on iOS & Android</p>
                                </div>
                            </div>
                            <div class="promo-actions">
                                <a href="student-app.php" class="btn-outline" style="font-size:12px; padding:6px 12px; border-radius:8px; text-decoration:none;">Install</a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recommended Courses For You -->
                <?php if (!empty($recommendedCourses)): ?>
                <div class="dash-recommended-wrap">
                    <div class="recommended-header">
                        <div>
                            <h3 class="recommended-title">Recommended Programs For You</h3>
                            <p style="font-size:13px; color:#64748b; margin:2px 0 0 0;">Curated for your study interests in <?php echo !empty($userInterestDestinations) ? htmlspecialchars($userInterestDestinations) : 'top global universities'; ?>.</p>
                        </div>
                        <a href="?tab=courses" class="recommended-link" onclick="if (event) { event.preventDefault(); event.stopPropagation(); } switchDashboardTab('view-courses'); return false;">
                            Explore all courses <i class="fa-solid fa-arrow-right"></i>
                        </a>
                    </div>
                    <div class="recommended-grid">
                        <?php foreach ($recommendedCourses as $rc): ?>
                        <?php $rcMeta = get_course_meta($rc); ?>
                        <div class="rec-course-card" style="cursor:pointer;" onclick="switchDashboardTab('view-courses');">
                            <div>
                                <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:8px;">
                                    <span class="rec-course-badge" style="margin:0;"><i class="fa-solid fa-location-dot"></i> <?php echo htmlspecialchars($rc['country_name'] ?? 'Global'); ?></span>
                                    <span style="font-size:11px; font-weight:700; color:#64748b; background:#f1f5f9; padding:2px 8px; border-radius:6px;"><?php echo htmlspecialchars($rcMeta['degree']); ?></span>
                                </div>
                                <h4 class="rec-course-title"><?php echo htmlspecialchars($rc['course_name']); ?></h4>
                                <div class="rec-course-uni">
                                    <i class="fa-solid fa-building-columns"></i>
                                    <span><?php echo htmlspecialchars($rc['university_name']); ?></span>
                                </div>
                            </div>
                            <div class="rec-course-footer">
                                <div>
                                    <div style="font-size:10px; color:#64748b; text-transform:uppercase;">Tuition Fee</div>
                                    <div class="rec-course-fee"><?php echo htmlspecialchars($rcMeta['tuition']); ?></div>
                                </div>
                                <button type="button" class="rec-course-btn" onclick="if (event) { event.preventDefault(); event.stopPropagation(); } switchDashboardTab('view-courses'); return false;">
                                    Explore <i class="fa-solid fa-arrow-right" style="font-size:9px;"></i>
                                </button>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>

            </div> <!-- End Dashboard View -->

            <!-- Explore Courses View -->
            <div id="view-courses" class="tab-view <?php echo $activeTab === 'courses' ? 'active-view' : ''; ?>">
                <div class="courses-header" style="margin-bottom: 20px;">
                    <div style="display:flex; justify-content:space-between; align-items:flex-start; flex-wrap:wrap; gap:12px;">
                        <div>
                            <h2 style="font-size:24px; font-weight:800; color:#0f172a; margin:0 0 6px 0;">Explore Global Courses & Programs</h2>
                            <p style="color:#64748b; font-size:14px; margin:0;">Filter by destination, study level, and tuition budget. Compare up to 3 programs side-by-side.</p>
                            <p style="margin-top: 6px; color: #94a3b8; font-size: 12px;"><i class="fa-solid fa-circle-info"></i> Requesting to apply forwards your preference to your counselor's CRM portal for expedited processing.</p>
                        </div>
                        <button type="button" class="btn-trigger-compare-modal" onclick="openCompareModal()">
                            <i class="fa-solid fa-code-compare"></i> Compare Courses (<span class="compare-dock-count-badge">0</span>/3)
                        </button>
                    </div>

                    <!-- Advanced Multi-Filter Form -->
                    <?php
                        $activeFilterCount = 0;
                        if (!empty($filterCountry)) $activeFilterCount++;
                        if (!empty($filterLevel)) $activeFilterCount++;
                        if (!empty($filterBudget)) $activeFilterCount++;
                        if (!empty($filterSort)) $activeFilterCount++;
                        if (!empty($filterSearch)) $activeFilterCount++;
                    ?>
                    <form method="GET" action="student-dashboard.php" class="course-filter-card" style="margin-top: 20px;">
                        <input type="hidden" name="tab" value="courses">
                        
                        <div class="filter-grid-row">
                            <!-- Country Filter -->
                            <div class="filter-select-wrap">
                                <label><i class="fa-solid fa-location-dot" style="color:#2563eb;"></i> Destination Country</label>
                                <select name="country" class="filter-select-custom">
                                    <option value="">All Countries (Worldwide)</option>
                                    <?php foreach($filterCountries as $c): ?>
                                        <option value="<?php echo htmlspecialchars($c); ?>" <?php echo $filterCountry === $c ? 'selected' : ''; ?>><?php echo htmlspecialchars($c); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <!-- Degree Level Filter -->
                            <div class="filter-select-wrap">
                                <label><i class="fa-solid fa-graduation-cap" style="color:#10b981;"></i> Degree Level</label>
                                <select name="level" class="filter-select-custom">
                                    <option value="">All Degrees & Levels</option>
                                    <option value="masters" <?php echo $filterLevel === 'masters' ? 'selected' : ''; ?>>Master's Degree (MSc/MA/MBA)</option>
                                    <option value="bachelors" <?php echo $filterLevel === 'bachelors' ? 'selected' : ''; ?>>Bachelor's Degree (BSc/BA/BTech)</option>
                                    <option value="diploma" <?php echo $filterLevel === 'diploma' ? 'selected' : ''; ?>>Postgraduate Diploma / Cert</option>
                                    <option value="phd" <?php echo $filterLevel === 'phd' ? 'selected' : ''; ?>>Doctorate / PhD Programs</option>
                                </select>
                            </div>

                            <!-- Budget Range Filter -->
                            <div class="filter-select-wrap">
                                <label><i class="fa-solid fa-wallet" style="color:#f59e0b;"></i> Annual Tuition Budget</label>
                                <select name="budget" class="filter-select-custom">
                                    <option value="">Any Budget Range</option>
                                    <option value="under15k" <?php echo $filterBudget === 'under15k' ? 'selected' : ''; ?>>Affordable (Under $15,000 / yr)</option>
                                    <option value="15k-25k" <?php echo $filterBudget === '15k-25k' ? 'selected' : ''; ?>>Mid-Range ($15,000 - $25,000 / yr)</option>
                                    <option value="25k-35k" <?php echo $filterBudget === '25k-35k' ? 'selected' : ''; ?>>Upper-Mid ($25,000 - $35,000 / yr)</option>
                                    <option value="above35k" <?php echo $filterBudget === 'above35k' ? 'selected' : ''; ?>>Tier 1 / Premium ($35,000+ / yr)</option>
                                </select>
                            </div>

                            <!-- Sort Order Filter -->
                            <div class="filter-select-wrap">
                                <label><i class="fa-solid fa-arrow-down-wide-short" style="color:#8b5cf6;"></i> Sort By</label>
                                <select name="sort" class="filter-select-custom">
                                    <option value="" <?php echo empty($filterSort) ? 'selected' : ''; ?>>Featured / Recommended</option>
                                    <option value="fee_asc" <?php echo $filterSort === 'fee_asc' ? 'selected' : ''; ?>>Tuition Fee: Low to High</option>
                                    <option value="fee_desc" <?php echo $filterSort === 'fee_desc' ? 'selected' : ''; ?>>Tuition Fee: High to Low</option>
                                    <option value="name_asc" <?php echo $filterSort === 'name_asc' ? 'selected' : ''; ?>>Program Name: A to Z</option>
                                </select>
                            </div>
                        </div>

                        <!-- Search keyword & Action Buttons -->
                        <div class="filter-search-row">
                            <div class="filter-search-input-wrap">
                                <i class="fa-solid fa-magnifying-glass"></i>
                                <input type="text" name="search" placeholder="Search by course name, major (e.g. Data Science, MBA) or university..." class="filter-search-input" value="<?php echo htmlspecialchars($filterSearch); ?>">
                            </div>
                            <button type="submit" class="btn-apply-filters">
                                <i class="fa-solid fa-filter"></i> Apply Filters
                            </button>
                            <?php if($activeFilterCount > 0): ?>
                                <a href="?tab=courses" class="btn-reset-filters">
                                    <i class="fa-solid fa-rotate-left"></i> Reset (<?php echo $activeFilterCount; ?>)
                                </a>
                            <?php endif; ?>
                        </div>

                        <div class="filter-stats-bar">
                            <div>
                                Showing <strong><?php echo count($popularCourses); ?></strong> accredited programs found
                                <?php if(!empty($filterCountry)): ?> in <strong><?php echo htmlspecialchars($filterCountry); ?></strong><?php endif; ?>
                            </div>
                            <div style="font-size:12px; color:#94a3b8;">
                                <i class="fa-solid fa-lightbulb" style="color:#f59e0b;"></i> Tip: Click "Compare" on any course to view side-by-side fees and visa rights.
                            </div>
                        </div>
                    </form>
                </div>

                <div class="courses-grid">
                    <?php if (empty($popularCourses)): ?>
                        <div style="grid-column: 1 / -1; text-align: center; padding: 60px 20px; background: white; border-radius: 20px; border: 1px dashed #cbd5e1;">
                            <i class="fa-solid fa-magnifying-glass" style="font-size: 42px; color: #cbd5e1; margin-bottom: 14px;"></i>
                            <h3 style="font-size: 18px; color: #334155; margin-bottom: 6px;">No courses found matching your criteria</h3>
                            <p style="color: #64748b; font-size: 14px; max-width: 420px; margin: 0 auto 16px auto;">Try broadening your budget range, clearing filters, or searching for broader major keywords like "Engineering" or "Business".</p>
                            <a href="?tab=courses" class="btn-save" style="display:inline-block; padding: 10px 20px; border-radius: 12px; text-decoration:none; color:white; font-weight:600;">Reset All Filters</a>
                        </div>
                    <?php else: ?>
                        <?php foreach($popularCourses as $pc): ?>
                        <?php
                            $meta = get_course_meta($pc);
                            $coursePayload = array_merge($pc, $meta);
                        ?>
                        <div class="course-card premium-course-card" data-course-id="<?php echo htmlspecialchars($pc['course_id'] ?? $pc['course_name']); ?>">
                            <div class="premium-course-header">
                                <span class="course-badge"><i class="fa-solid fa-location-dot"></i> <?php echo htmlspecialchars($pc['country_name'] ?? 'Unknown'); ?></span>
                                <i class="fa-regular fa-heart course-card-fav explore-heart" data-course="<?php echo htmlspecialchars($pc['course_name']); ?>" style="position:static; margin:0; cursor:pointer; font-size:20px; transition:all 0.2s; color:#94a3b8;" onclick="toggleShortlist(this, '<?php echo rawurlencode(json_encode($coursePayload)); ?>');"></i>
                            </div>
                            
                            <h3 class="premium-course-title"><?php echo htmlspecialchars($pc['course_name']); ?></h3>
                            
                            <div class="premium-course-uni">
                                <div class="uni-icon"><i class="fa-solid fa-building-columns"></i></div>
                                <span><?php echo htmlspecialchars($pc['university_name']); ?></span>
                            </div>

                            <?php if ($meta['waiver_eligible']): ?>
                                <div>
                                    <span class="badge-waiver-pill">
                                        <i class="fa-solid fa-certificate"></i> English Waiver Eligible (MOI)
                                    </span>
                                </div>
                            <?php endif; ?>
                            
                            <!-- Dynamic Smart Metrics (No hardcoding) -->
                            <div class="premium-course-metrics">
                                <div class="metric">
                                    <i class="fa-solid fa-graduation-cap"></i>
                                    <div>
                                        <small>Degree</small>
                                        <strong><?php echo htmlspecialchars($meta['degree']); ?></strong>
                                    </div>
                                </div>
                                <div class="metric">
                                    <i class="fa-regular fa-calendar"></i>
                                    <div>
                                        <small>Next Intake</small>
                                        <strong><?php echo htmlspecialchars($meta['intake']); ?></strong>
                                    </div>
                                </div>
                                <div class="metric">
                                    <i class="fa-solid fa-money-bill-wave"></i>
                                    <div>
                                        <small>Tuition Fee</small>
                                        <strong><?php echo htmlspecialchars($meta['tuition']); ?></strong>
                                    </div>
                                </div>
                                <div class="metric">
                                    <i class="fa-regular fa-circle-check"></i>
                                    <div>
                                        <small>Entry Score</small>
                                        <strong><?php echo htmlspecialchars($meta['entry_score']); ?></strong>
                                    </div>
                                </div>
                            </div>

                            <div style="font-size:12px; color:#64748b; margin-top:-4px; padding:6px 0; border-top:1px dashed #f1f5f9;">
                                <i class="fa-solid fa-briefcase" style="color:#2563eb; margin-right:4px;"></i> <?php echo htmlspecialchars($meta['pswv']); ?>
                            </div>

                            <!-- Actions Row: Compare Toggle + Request Apply -->
                            <div class="course-card-actions">
                                <button type="button" class="btn-card-compare" data-course-compare-btn onclick="toggleCourseCompare(this, '<?php echo rawurlencode(json_encode($coursePayload)); ?>');">
                                    <i class="fa-solid fa-code-compare"></i> Compare
                                </button>
                                <button type="button" class="btn-premium-apply" data-course-application data-course-name="<?php echo htmlspecialchars($pc['course_name'], ENT_QUOTES, 'UTF-8'); ?>" data-university-name="<?php echo htmlspecialchars($pc['university_name'], ENT_QUOTES, 'UTF-8'); ?>" data-country-name="<?php echo htmlspecialchars($pc['country_name'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" data-intake="<?php echo htmlspecialchars($meta['intake'], ENT_QUOTES, 'UTF-8'); ?>">
                                    <i class="fa-solid fa-paper-plane" aria-hidden="true"></i> Request to Apply
                                </button>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div> <!-- End Courses View -->

            <!-- Profile View -->
            <div id="view-profile" class="tab-view <?php echo $activeTab === 'profile' ? 'active-view' : ''; ?>">
                <h1 class="greeting">My profile</h1>
                
                <?php if(isset($_GET['profile_updated'])): ?>
                    <div style="background-color: #d1fae5; color: #065f46; padding: 12px 16px; border-radius: 8px; margin-bottom: 20px; border: 1px solid #10b981; font-size: 14px;">
                        <i class="fa-solid fa-circle-check"></i> Profile updated successfully!
                    </div>
                <?php endif; ?>

                <?php if(isset($_GET['interests_updated'])): ?>
                    <div style="background-color: #d1fae5; color: #065f46; padding: 12px 16px; border-radius: 8px; margin-bottom: 20px; border: 1px solid #10b981; font-size: 14px;">
                        <i class="fa-solid fa-circle-check"></i> Interests updated successfully!
                    </div>
                <?php endif; ?>
                
                <div class="profile-layout">
                    <!-- Left Sub-menu -->
                    <div class="profile-sidebar">
                        <div class="profile-tab <?php echo !isset($_GET['interests_updated']) ? 'active' : ''; ?>" data-ptab="profile-details">Details</div>
                        <div class="profile-tab <?php echo isset($_GET['interests_updated']) ? 'active' : ''; ?>" data-ptab="profile-interests">Interests</div>
                    </div>
                    
                    <!-- Main Profile Card -->
                    <div class="profile-main">
                        <!-- Details Tab -->
                        <div id="profile-details" class="profile-tab-content <?php echo !isset($_GET['interests_updated']) ? 'active-content' : ''; ?>">
                            <div class="profile-card">
                                <div class="profile-card-header">
                                    <h2>Details</h2>
                                    <button class="btn-edit" id="btn-edit-profile">Edit</button>
                                </div>
                                
                                <!-- Display Mode -->
                                <div class="profile-display-view" id="profile-display-view">
                                    <div class="profile-fields">
                                        <div class="field-group">
                                            <label>First Name</label>
                                            <div class="value"><?php echo htmlspecialchars($firstName); ?></div>
                                        </div>
                                        <div class="field-group">
                                            <label>Last Name</label>
                                            <div class="value"><?php echo htmlspecialchars(count($nameParts) > 1 ? end($nameParts) : 'M'); ?></div>
                                        </div>
                                        <div class="field-group">
                                            <label>Date of birth</label>
                                            <div class="value"><?php echo !empty($userDob) ? date('d-m-Y', strtotime($userDob)) : 'dd-mm-yyyy'; ?></div>
                                        </div>
                                        <div class="field-group">
                                            <label>Nationality</label>
                                            <div class="value">-</div>
                                        </div>
                                        <div class="field-group">
                                            <label>Email address</label>
                                            <div class="value"><?php echo htmlspecialchars($_SESSION['user_email'] ?? 'jayasrimurugesan7@gmail.com'); ?></div>
                                        </div>
                                        <div class="field-row">
                                            <div class="field-group">
                                                <label>Dial code</label>
                                                <div class="value">+91</div>
                                            </div>
                                            <div class="field-group">
                                                <label>Mobile number</label>
                                                <div class="value"><?php echo htmlspecialchars($userPhone ?: 'Not provided'); ?></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Edit Mode -->
                                <form class="profile-edit-view" id="profile-edit-view" action="" method="POST">
                                    <input type="hidden" name="action" value="update_profile">
                                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($communityCsrfToken); ?>">
                                    <div class="profile-fields">
                                        <div class="field-group">
                                            <label>First Name</label>
                                            <input type="text" class="profile-input" name="first_name" value="<?php echo htmlspecialchars($firstName); ?>">
                                        </div>
                                        <div class="field-group">
                                            <label>Last Name</label>
                                            <input type="text" class="profile-input" name="last_name" value="<?php echo htmlspecialchars(count($nameParts) > 1 ? end($nameParts) : 'M'); ?>">
                                        </div>
                                        <div class="field-group">
                                            <label>Date of birth</label>
                                            <input type="date" class="profile-input" name="dob" value="<?php echo htmlspecialchars($userDob ?? ''); ?>">
                                        </div>
                                        <div class="field-group">
                                            <label>Nationality</label>
                                            <input type="text" class="profile-input" name="nationality" placeholder="e.g. Indian">
                                        </div>
                                        <div class="field-group">
                                            <label>Email address</label>
                                            <input type="email" class="profile-input" name="email" value="<?php echo htmlspecialchars($_SESSION['user_email'] ?? 'jayasrimurugesan7@gmail.com'); ?>">
                                        </div>
                                        <div class="field-row">
                                            <div class="field-group">
                                                <label>Dial code</label>
                                                <input type="text" class="profile-input" name="dial_code" value="+91">
                                            </div>
                                            <div class="field-group">
                                                <label>Mobile number</label>
                                                <input type="text" class="profile-input" name="mobile" value="<?php echo htmlspecialchars($userPhone); ?>">
                                            </div>
                                        </div>
                                        <div style="margin-top: 16px; text-align: right; display: flex; justify-content: flex-end; gap: 12px;">
                                            <button type="button" class="btn-outline" id="btn-cancel-edit" style="border-radius: 20px;">Cancel</button>
                                            <button type="submit" class="btn-save">Save changes</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        
                        <!-- Interests Tab -->
                        <div id="profile-interests" class="profile-tab-content <?php echo isset($_GET['interests_updated']) ? 'active-content' : ''; ?>">
                            <div class="profile-card">
                                <div class="profile-card-header">
                                    <h2>Interests</h2>
                                    <button class="btn-edit" id="btn-edit-interests">Edit</button>
                                </div>
                                
                                <!-- Display Mode -->
                                <div class="profile-display-view" id="interests-display-view">
                                    <div class="profile-fields">
                                        <div class="field-group" style="margin-bottom: 24px;">
                                            <label style="font-weight: 600; color: #374151; font-size: 14px; display: block; margin-bottom: 6px;">Subjects</label>
                                            <div class="value" style="color: #4b5563; font-size: 14px;"><?php echo htmlspecialchars($userInterestSubjects ?: '-'); ?></div>
                                        </div>
                                        <div class="field-group" style="margin-bottom: 24px;">
                                            <label style="font-weight: 600; color: #374151; font-size: 14px; display: block; margin-bottom: 6px;">Preferred study destination</label>
                                            <div class="value" style="color: #4b5563; font-size: 14px;"><?php echo htmlspecialchars($userInterestDestinations ?: '-'); ?></div>
                                        </div>
                                        <div class="field-group" style="margin-bottom: 24px;">
                                            <label style="font-weight: 600; color: #374151; font-size: 14px; display: block; margin-bottom: 6px;">Study Level</label>
                                            <div class="value" style="color: #4b5563; font-size: 14px;"><?php echo htmlspecialchars($userInterestStudyLevel ?: '-'); ?></div>
                                        </div>
                                        <div class="field-group">
                                            <label style="font-weight: 600; color: #374151; font-size: 14px; display: block; margin-bottom: 6px;">Preferred intake year</label>
                                            <div class="value" style="color: #6b7280; font-size: 14px;"><?php echo htmlspecialchars($userInterestIntakeYear ?: '-'); ?></div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Edit Mode -->
                                <form class="profile-edit-view" id="interests-edit-view" action="" method="POST">
                                    <input type="hidden" name="action" value="update_interests">
                                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($communityCsrfToken); ?>">
                                    <div class="profile-fields">
                                        <div class="field-group" style="margin-bottom: 16px;">
                                            <label>Subjects</label>
                                            <input type="text" class="profile-input" name="interest_subjects" value="<?php echo htmlspecialchars($userInterestSubjects); ?>" placeholder="e.g. Computer Science">
                                        </div>
                                        <div class="field-group" style="margin-bottom: 16px;">
                                            <label>Preferred study destination</label>
                                            <input type="text" class="profile-input" name="interest_destinations" value="<?php echo htmlspecialchars($userInterestDestinations); ?>" placeholder="e.g. Canada, United States">
                                        </div>
                                        <div class="field-group" style="margin-bottom: 16px;">
                                            <label>Study Level</label>
                                            <input type="text" class="profile-input" name="interest_study_level" value="<?php echo htmlspecialchars($userInterestStudyLevel); ?>" placeholder="e.g. Postgraduate">
                                        </div>
                                        <div class="field-group" style="margin-bottom: 16px;">
                                            <label>Preferred intake year</label>
                                            <input type="text" class="profile-input" name="interest_intake_year" value="<?php echo htmlspecialchars($userInterestIntakeYear); ?>" placeholder="e.g. Intake year">
                                        </div>
                                        <div style="margin-top: 16px; text-align: right; display: flex; justify-content: flex-end; gap: 12px;">
                                            <button type="button" class="btn-outline" id="btn-cancel-edit-interests" style="border-radius: 20px;">Cancel</button>
                                            <button type="submit" class="btn-save">Save changes</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Right Promo Card -->
                    <div class="profile-promo">
                        <div class="promo-card-vertical">
                            <button class="btn-close-promo"><i class="fa-solid fa-xmark"></i></button>
                            <div class="promo-icon-large">
                                <img src="assets/images/faviconlogo.png" alt="App Icon" style="width: 32px; height: 32px; object-fit: contain;" onerror="this.style.display='none'; this.parentElement.innerHTML='<i class=\'fa-solid fa-mobile-screen\'></i>';">
                            </div>
                            <h3>Download the Bluestone Live app today</h3>
                            <p>Your essential guide for every step of your international adventure!</p>
                            <a href="student-app.php" class="btn-outline-promo" style="display:inline-flex; align-items:center; justify-content:center; text-decoration:none;">Learn more</a>
                        </div>
                    </div>
                </div>
            </div> <!-- End Profile View -->

            <!-- Shortlisted View -->
            <div id="view-shortlisted" class="tab-view <?php echo $activeTab === 'shortlisted' ? 'active-view' : ''; ?>">
                <div class="shortlist-top-bar">
                    <div>
                        <h1 class="greeting" style="margin:0 0 4px 0;">Shortlisted Courses</h1>
                        <p style="color:#64748b; font-size:13px; margin:0;">Review, compare, and manage your favorite study programs.</p>
                    </div>
                    <div style="display:flex; align-items:center; gap:10px;">
                        <button type="button" class="btn-compare-all" onclick="compareShortlistedCourses()">
                            <i class="fa-solid fa-code-compare"></i> Compare Shortlisted Programs
                        </button>
                    </div>
                </div>
                
                <div class="courses-grid" id="shortlist-grid" style="margin-top: 20px;">
                    <!-- JS will inject saved courses here -->
                </div>
                <div id="empty-shortlist" style="text-align: center; padding: 40px 20px; display: none;">
                    <i class="fa-solid fa-heart-crack" style="font-size: 48px; color: #cbd5e1; margin-bottom: 16px;"></i>
                    <h3 style="font-size: 18px; color: #475569; margin-bottom: 8px;">No courses shortlisted yet</h3>
                    <p style="color: #64748b; font-size: 14px; max-width: 400px; margin: 0 auto;">Explore our courses and click the heart icon to save them here for later.</p>
                    <button class="btn-save" style="margin-top:20px; padding: 10px 20px; border-radius:12px; cursor:pointer; border:none; color:white; font-weight:600;" onclick="switchDashboardTab('view-courses'); return false;">Explore Courses</button>
                </div>

                <div class="profile-card" style="margin-top: 40px; border-top: 1px solid #e2e8f0; padding-top:24px;">
                    <div class="profile-card-header">
                        <h2>Your Approved Universities & Offers</h2>
                    </div>
                    <div class="profile-fields">
                        <?php 
                            $isOfferReceived = in_array(strtolower($currentStatus), ['offer received', 'approved', 'visa approved', 'conditional offer', 'unconditional offer']);
                            
                            if ($isOfferReceived && !empty($university)): 
                        ?>
                            <div style="background: #f0fdf4; padding: 20px; border-radius: 8px; border: 1px solid #bbf7d0; display: flex; align-items: flex-start; gap: 16px;">
                                <div style="font-size: 32px; color: #16a34a;">
                                    <i class="fa-solid fa-award"></i>
                                </div>
                                <div>
                                    <h3 style="margin: 0; font-size: 18px; color: #166534; font-weight: 600;">Congratulations on your offer!</h3>
                                    <p style="margin: 8px 0 16px 0; font-size: 14px; color: #15803d; line-height: 1.5;">You have successfully received an offer from <strong><?php echo htmlspecialchars($university); ?></strong>. Here are the details of your application.</p>
                                    
                                    <div class="app-detail-row" style="border-top: 1px solid #bbf7d0; padding-top: 12px; max-width: 400px; margin-bottom: 8px;">
                                        <span class="app-detail-label" style="color: #166534; font-weight: 500;">University</span>
                                        <span class="app-detail-value" style="color: #14532d;"><?php echo htmlspecialchars($university); ?></span>
                                    </div>
                                    <div class="app-detail-row" style="max-width: 400px; margin-bottom: 8px;">
                                        <span class="app-detail-label" style="color: #166534; font-weight: 500;">Course</span>
                                        <span class="app-detail-value" style="color: #14532d;"><?php echo htmlspecialchars($course); ?></span>
                                    </div>
                                    <div class="app-detail-row" style="max-width: 400px; margin-bottom: 8px;">
                                        <span class="app-detail-label" style="color: #166534; font-weight: 500;">Student ID</span>
                                        <span class="app-detail-value" style="color: #14532d;"><?php echo !empty($universityStudentId) ? htmlspecialchars($universityStudentId) : '-'; ?></span>
                                    </div>
                                    <div class="app-detail-row" style="max-width: 400px;">
                                        <span class="app-detail-label" style="color: #166534; font-weight: 500;">Current Status</span>
                                        <span class="app-detail-value" style="color: #14532d;"><span style="background: #16a34a; color: white; padding: 2px 8px; border-radius: 12px; font-size: 12px;"><?php echo htmlspecialchars($currentStatus); ?></span></span>
                                    </div>
                                </div>
                            </div>
                        <?php else: ?>
                            <div style="text-align: center; padding: 40px 20px;">
                                <i class="fa-solid fa-file-circle-xmark" style="font-size: 48px; color: #cbd5e1; margin-bottom: 16px;"></i>
                                <h3 style="font-size: 18px; color: #475569; margin-bottom: 8px;">No offers available yet</h3>
                                <p style="color: #64748b; font-size: 14px; max-width: 400px; margin: 0 auto;">When your applied university approves your application and issues an offer, it will securely appear here.</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div> <!-- End Shortlisted View -->

            <!-- Application Status View -->
            <div id="view-application" class="tab-view">
                <div style="display:flex; justify-content:space-between; align-items:flex-start; flex-wrap:wrap; gap:14px; margin-bottom:20px;">
                    <div>
                        <h1 class="greeting" style="margin:0 0 4px 0;">Application & Offer Letter Management</h1>
                        <p style="color:#64748b; font-size:14px; margin:0;">Track multi-university submissions across Dream, Target, and Safe categories. Download official offer letters and monitor visa milestones.</p>
                    </div>
                    <div>
                        <span class="journey-pill-lead" style="font-size:13px; padding:8px 14px; background:white; border-radius:10px;">
                            <i class="fa-solid fa-folder-open" style="color:#2563eb; margin-right:4px;"></i> Total Active Submissions: <strong><?php echo count($studentApplications); ?></strong>
                        </span>
                    </div>
                </div>

                <!-- Application View Sub-Navigation -->
                <div class="app-sub-nav">
                    <button type="button" class="app-sub-nav-btn active" onclick="switchAppSubSection('all-apps', this)">
                        <i class="fa-solid fa-building-columns"></i> Multi-University Applications <span class="app-sub-nav-badge"><?php echo count($studentApplications); ?></span>
                    </button>
                    <?php if (!empty($activeOffers)): ?>
                    <button type="button" class="app-sub-nav-btn" onclick="switchAppSubSection('offer-hub', this)">
                        <i class="fa-solid fa-award"></i> Official Offer Letter Hub <span class="app-sub-nav-badge" style="background:#10b981; color:white;"><?php echo count($activeOffers); ?></span>
                    </button>
                    <?php endif; ?>
                    <?php if (!empty($visaStageApps)): ?>
                    <button type="button" class="app-sub-nav-btn" onclick="switchAppSubSection('visa-tracker', this)">
                        <i class="fa-solid fa-passport"></i> Visa Stage Tracker <span class="app-sub-nav-badge"><?php echo count($visaStageApps); ?></span>
                    </button>
                    <?php endif; ?>
                    <button type="button" class="app-sub-nav-btn" onclick="switchAppSubSection('crm-log', this)">
                        <i class="fa-solid fa-comments"></i> Counselor Log & Remarks
                    </button>
                </div>

                <!-- ═══════════════ SECTION 1: Multi-University Application Support ═══════════════ -->
                <div id="app-sec-all-apps" class="app-sub-section">
                    <div style="background:#eff6ff; border:1px solid #bfdbfe; padding:14px 18px; border-radius:14px; margin-bottom:20px; display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:10px;">
                        <div style="display:flex; align-items:center; gap:10px; font-size:13px; color:#1e40af;">
                            <i class="fa-solid fa-layer-group" style="font-size:16px;"></i>
                            <span>Students typically apply to 3–5 universities across <strong>Dream</strong>, <strong>Target</strong>, and <strong>Safe</strong> categories to maximize scholarship and admission success.</span>
                        </div>
                        <button type="button" class="btn-save" style="padding:6px 14px; font-size:12px; border-radius:8px; border:none; color:white; cursor:pointer;" onclick="switchDashboardTab('view-courses');">
                            <i class="fa-solid fa-plus"></i> Add University
                        </button>
                    </div>

                    <div class="multi-app-grid">
                        <?php foreach ($studentApplications as $appItem): ?>
                        <?php
                            $isOffer = in_array($appItem['status_type'], ['offer', 'visa']) || in_array($appItem['offer_type'], ['Conditional Offer', 'Unconditional Offer', 'Accepted']);
                            $typeClass = strtolower($appItem['app_type']);
                            $statusPillClass = 'app-status-review';
                            if ($isOffer) $statusPillClass = 'app-status-offer';
                            elseif ($appItem['status_type'] === 'docs_pending') $statusPillClass = 'app-status-pending';
                            elseif ($appItem['status_type'] === 'visa') $statusPillClass = 'app-status-visa';
                        ?>
                        <div class="multi-app-card <?php echo $isOffer ? 'has-offer' : ''; ?>">
                            <div>
                                <div class="app-card-top">
                                    <div class="app-card-uni-info">
                                        <div class="app-card-uni-icon">
                                            <i class="fa-solid fa-building-columns"></i>
                                        </div>
                                        <div>
                                            <div class="app-card-uni-name"><?php echo htmlspecialchars($appItem['university_name']); ?></div>
                                            <div class="app-card-country"><i class="fa-solid fa-location-dot"></i> <?php echo htmlspecialchars($appItem['country_name']); ?></div>
                                        </div>
                                    </div>
                                    <span class="app-type-pill <?php echo $typeClass; ?>">
                                        <?php echo htmlspecialchars($appItem['app_type']); ?> Choice
                                    </span>
                                </div>

                                <div class="app-card-course"><?php echo htmlspecialchars($appItem['course_name']); ?></div>

                                <div class="app-card-meta-row">
                                    <div>Intake: <strong><?php echo htmlspecialchars($appItem['intake']); ?></strong></div>
                                    <div>Ref: <strong><?php echo htmlspecialchars($appItem['student_id_ref'] ?: 'Pending'); ?></strong></div>
                                </div>

                                <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:16px;">
                                    <span class="app-card-status-badge <?php echo $statusPillClass; ?>">
                                        <?php if ($isOffer): ?><i class="fa-solid fa-award"></i><?php elseif ($appItem['status_type'] === 'docs_pending'): ?><i class="fa-solid fa-clock"></i><?php else: ?><i class="fa-solid fa-spinner fa-spin"></i><?php endif; ?>
                                        <?php echo htmlspecialchars($appItem['status']); ?>
                                    </span>
                                    <?php if (!empty($appItem['deposit_amount'])): ?>
                                        <span style="font-size:11px; color:#64748b; font-weight:600;">Deposit: <strong><?php echo htmlspecialchars($appItem['deposit_amount']); ?></strong></span>
                                    <?php endif; ?>
                                </div>

                                <!-- Micro Progress Stepper -->
                                <div style="display:flex; align-items:center; justify-content:space-between; font-size:10px; color:#64748b; margin-bottom:14px; text-transform:uppercase; font-weight:700;">
                                    <span style="color:#10b981;"><i class="fa-solid fa-check"></i> Applied</span>
                                    <i class="fa-solid fa-chevron-right" style="font-size:8px; color:#cbd5e1;"></i>
                                    <span style="<?php echo $appItem['status_type'] !== 'review' ? 'color:#10b981;' : 'color:#2563eb;'; ?>"><i class="fa-solid fa-circle-dot"></i> Review</span>
                                    <i class="fa-solid fa-chevron-right" style="font-size:8px; color:#cbd5e1;"></i>
                                    <span style="<?php echo $isOffer ? 'color:#10b981;' : ''; ?>">Offer Letter</span>
                                    <i class="fa-solid fa-chevron-right" style="font-size:8px; color:#cbd5e1;"></i>
                                    <span style="<?php echo $appItem['status_type'] === 'visa' ? 'color:#10b981;' : ''; ?>">Visa</span>
                                </div>
                            </div>

                            <div class="app-card-actions">
                                <?php if ($isOffer): ?>
                                    <button type="button" class="btn-card-offer-view" onclick="switchAppSubSection('offer-hub');">
                                        <i class="fa-solid fa-award"></i> View Offer & Conditions
                                    </button>
                                <?php elseif ($appItem['status_type'] === 'docs_pending'): ?>
                                    <button type="button" class="btn-card-compare" style="flex:1;" onclick="switchDashboardTab('view-documents');">
                                        <i class="fa-solid fa-file-arrow-up"></i> Upload Pending Docs
                                    </button>
                                <?php else: ?>
                                    <button type="button" class="btn-card-compare" style="flex:1;" onclick="switchAppSubSection('crm-log');">
                                        <i class="fa-solid fa-clock-rotate-left"></i> Track Remarks
                                    </button>
                                <?php endif; ?>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- ═══════════════ SECTION 2: Official Offer Letter Hub ═══════════════ -->
                <div id="app-sec-offer-hub" class="app-sub-section" style="display:none;">
                    <?php if (!empty($activeOffers)): ?>
                        <?php foreach ($activeOffers as $offerItem): ?>
                        <?php
                            $offerConditions = !empty($offerItem['conditions_json']) ? json_decode($offerItem['conditions_json'], true) : [];
                            $isAccepted = ($offerItem['offer_type'] === 'Accepted');
                            $feeRequested = !empty($offerItem['fee_details_requested']);
                        ?>
                        <div class="offer-hub-panel" id="offer-panel-<?php echo $offerItem['id']; ?>">
                            <div class="offer-hub-top">
                                <div>
                                    <span class="offer-hub-badge">
                                        <i class="fa-solid fa-award"></i> Official Letter of Offer Issued
                                    </span>
                                    <h2 class="offer-hub-title"><?php echo htmlspecialchars($offerItem['university_name']); ?></h2>
                                    <p class="offer-hub-sub">
                                        Congratulations! You have been granted admission for <strong><?php echo htmlspecialchars($offerItem['course_name']); ?></strong> (<?php echo htmlspecialchars($offerItem['country_name']); ?>).
                                    </p>
                                </div>
                                <div class="offer-hub-actions-top">
                                    <a href="student-offer-letter.php?id=<?php echo $offerItem['id']; ?>" target="_blank" class="btn-offer-download">
                                        <i class="fa-solid fa-file-pdf"></i> Download Official Offer (PDF)
                                    </a>
                                </div>
                            </div>

                            <!-- 4 Key Offer Specs -->
                            <div class="offer-specs-grid">
                                <div class="offer-spec-box">
                                    <div class="offer-spec-label">Student ID Ref</div>
                                    <div class="offer-spec-val"><?php echo htmlspecialchars($offerItem['student_id_ref'] ?: 'STU-' . rand(100000, 999999)); ?></div>
                                </div>
                                <div class="offer-spec-box">
                                    <div class="offer-spec-label">Admission Category</div>
                                    <div class="offer-spec-val" style="color:#15803d;"><?php echo htmlspecialchars($offerItem['offer_type']); ?></div>
                                </div>
                                <div class="offer-spec-box">
                                    <div class="offer-spec-label">Tuition Deposit Required</div>
                                    <div class="offer-spec-val"><?php echo htmlspecialchars($offerItem['deposit_amount'] ?: '£3,000'); ?></div>
                                </div>
                                <div class="offer-spec-box">
                                    <div class="offer-spec-label">Acceptance Deadline</div>
                                    <div class="offer-spec-val" style="color:#d97706;"><?php echo htmlspecialchars($offerItem['offer_deadline'] ?: 'July 31, 2026'); ?></div>
                                </div>
                            </div>

                            <!-- Conditions Breakdown Checklist -->
                            <div class="offer-conditions-card">
                                <div class="conditions-card-title">
                                    <i class="fa-solid fa-list-check"></i> Conditions to be Fulfilled for Unconditional Offer & CAS:
                                </div>
                                <div class="conditions-list">
                                    <?php if (!empty($offerConditions)): ?>
                                        <?php foreach ($offerConditions as $cond): ?>
                                            <div class="condition-item-row <?php echo !empty($cond['satisfied']) ? 'satisfied' : ''; ?>">
                                                <div class="condition-item-left">
                                                    <?php if (!empty($cond['satisfied'])): ?>
                                                        <i class="fa-solid fa-circle-check" style="font-size:16px; color:#16a34a;"></i>
                                                    <?php else: ?>
                                                        <i class="fa-solid fa-clock" style="font-size:16px; color:#d97706;"></i>
                                                    <?php endif; ?>
                                                    <span><?php echo htmlspecialchars($cond['condition']); ?></span>
                                                </div>
                                                <span class="condition-status-pill <?php echo !empty($cond['satisfied']) ? 'satisfied' : 'pending'; ?>">
                                                    <?php echo !empty($cond['satisfied']) ? 'Satisfied / Verified' : 'Pending Fulfillment'; ?>
                                                </span>
                                            </div>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <div class="condition-item-row satisfied">
                                            <div class="condition-item-left">
                                                <i class="fa-solid fa-circle-check" style="color:#16a34a;"></i>
                                                <span>Standard academic submission and passport verification satisfied.</span>
                                            </div>
                                            <span class="condition-status-pill satisfied">Verified</span>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <!-- Decision Actions Row -->
                            <div class="offer-decision-bar">
                                <div class="offer-decision-left">
                                    <h4>Ready to secure your admission place?</h4>
                                    <p>Confirming your offer notifies your counselor to prepare CAS / I-20 application documents.</p>
                                </div>
                                <div class="offer-decision-buttons">
                                    <button type="button" class="btn-accept-offer <?php echo $isAccepted ? 'accepted' : ''; ?>" <?php echo $isAccepted ? 'disabled' : ''; ?> onclick="handleAcceptOffer(<?php echo $offerItem['id']; ?>)">
                                        <?php if ($isAccepted): ?>
                                            <i class="fa-solid fa-check-double"></i> Offer Accepted & Seat Reserved
                                        <?php else: ?>
                                            <i class="fa-solid fa-circle-check"></i> Accept Offer & Confirm Place
                                        <?php endif; ?>
                                    </button>

                                    <button type="button" id="btn-fee-request-<?php echo $offerItem['id']; ?>" class="btn-request-deposit <?php echo $feeRequested ? 'requested' : ''; ?>" <?php echo $feeRequested ? 'disabled' : ''; ?> onclick="handleRequestFeeDetails(<?php echo $offerItem['id']; ?>)">
                                        <?php if ($feeRequested): ?>
                                            <i class="fa-solid fa-check"></i> Remittance Details Requested
                                        <?php else: ?>
                                            <i class="fa-solid fa-receipt"></i> Request Wire / Fee Payment Details
                                        <?php endif; ?>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div style="text-align: center; padding: 60px 20px; background: white; border-radius: 20px; border: 1px dashed #cbd5e1;">
                            <i class="fa-solid fa-award" style="font-size: 48px; color: #cbd5e1; margin-bottom: 16px;"></i>
                            <h3 style="font-size: 18px; color: #475569; margin-bottom: 8px;">No offers issued yet</h3>
                            <p style="color: #64748b; font-size: 14px; max-width: 440px; margin: 0 auto;">Your university applications are currently under active evaluation by the admissions boards. Official offer letters will be displayed here as soon as decisions are released.</p>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- ═══════════════ SECTION 3: Visa Stage Tracker ═══════════════ -->
                <div id="app-sec-visa-tracker" class="app-sub-section" style="display:none;">
                    <?php
                        $primaryApp = !empty($studentApplications[0]) ? $studentApplications[0] : null;
                    ?>
                    <div class="visa-tracker-panel">
                        <div class="visa-tracker-header">
                            <div>
                                <h3 class="visa-tracker-title">
                                    <i class="fa-solid fa-passport" style="color:#2563eb;"></i> International Student Visa Milestone Tracker
                                </h3>
                                <p style="font-size:13px; color:#64748b; margin:4px 0 0 0;">
                                    Target Destination: <strong><?php echo htmlspecialchars($primaryApp['country_name'] ?? 'United Kingdom'); ?></strong> • Sponsoring University: <strong><?php echo htmlspecialchars($primaryApp['university_name'] ?? 'University of Manchester'); ?></strong>
                                </p>
                            </div>
                            <span class="journey-pill-status">
                                <i class="fa-solid fa-shield-check"></i> Passport: <?php echo htmlspecialchars($crmData['passport_no'] ?? 'Verified on File'); ?>
                            </span>
                        </div>

                        <!-- 3 Sub-Stage Cards -->
                        <div class="visa-steps-grid">
                            <!-- Step 1: CAS / I-20 Issuance -->
                            <div class="visa-step-card <?php echo !empty($primaryApp['cas_i20_number']) ? 'completed' : 'active'; ?>">
                                <div>
                                    <div class="visa-step-icon">
                                        <i class="fa-solid fa-file-invoice"></i>
                                    </div>
                                    <div class="visa-step-title">1. CAS / Form I-20 Issuance</div>
                                    <div class="visa-step-desc">Official electronic visa sponsorship document released by the institution upon tuition deposit confirmation.</div>
                                </div>
                                <div class="visa-step-data-box">
                                    <div style="display:flex; justify-content:space-between; margin-bottom:4px;">
                                        <span>Status:</span>
                                        <strong style="color:#16a34a;"><?php echo htmlspecialchars($primaryApp['cas_i20_status'] ?? 'In Processing'); ?></strong>
                                    </div>
                                    <div>Ref: <strong><?php echo htmlspecialchars($primaryApp['cas_i20_number'] ?? 'CAS-UOM-798124'); ?></strong></div>
                                </div>
                            </div>

                            <!-- Step 2: VFS / Embassy Appointment -->
                            <div class="visa-step-card active">
                                <div>
                                    <div class="visa-step-icon">
                                        <i class="fa-solid fa-building-flag"></i>
                                    </div>
                                    <div class="visa-step-title">2. VFS Biometrics & Interview</div>
                                    <div class="visa-step-desc">Biometrics data enrollment and visa filing interview at the certified international embassy center.</div>
                                </div>
                                <div class="visa-step-data-box">
                                    <div style="display:flex; justify-content:space-between; margin-bottom:4px;">
                                        <span>Scheduled:</span>
                                        <strong style="color:#2563eb;"><?php echo htmlspecialchars($primaryApp['vfs_appointment_date'] ?? 'Aug 18, 2026 • 10:30 AM'); ?></strong>
                                    </div>
                                    <div style="font-size:11px; color:#64748b;"><?php echo htmlspecialchars($primaryApp['vfs_location'] ?? 'VFS Global Center, Chennai'); ?></div>
                                </div>
                            </div>

                            <!-- Step 3: Courier Tracking -->
                            <div class="visa-step-card">
                                <div>
                                    <div class="visa-step-icon">
                                        <i class="fa-solid fa-truck-fast"></i>
                                    </div>
                                    <div class="visa-step-title">3. Passport & Visa Dispatch</div>
                                    <div class="visa-step-desc">Secure courier delivery of passport endorsed with your international entry visa sticker (vignette).</div>
                                </div>
                                <div class="visa-step-data-box">
                                    <div style="display:flex; justify-content:space-between; margin-bottom:4px;">
                                        <span>Courier:</span>
                                        <strong>Bluedart Express</strong>
                                    </div>
                                    <div style="display:flex; justify-content:space-between; align-items:center;">
                                        <span>AWB: <strong><?php echo htmlspecialchars($primaryApp['courier_tracking_no'] ?? 'BD-982348123'); ?></strong></span>
                                        <a href="https://www.bluedart.com" target="_blank" style="color:#2563eb; font-weight:700; text-decoration:none;">Track <i class="fa-solid fa-arrow-up-right-from-square"></i></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ═══════════════ SECTION 4: Counselor Remarks & CRM Log ═══════════════ -->
                <div id="app-sec-crm-log" class="app-sub-section" style="display:none;">
                    <?php if ($crmData !== null): ?>
                    <!-- CRM Data Section -->
                    <div class="crm-section" style="margin-top: 10px;">
                        
                        <!-- Timeline -->
                        <div class="crm-panel">
                            <h3 class="crm-panel-title">Application Journey</h3>
                            <div class="timeline">
                                <div class="timeline-item completed">
                                    <div class="timeline-dot"></div>
                                    <div class="timeline-content">
                                        <h4>Profile Created</h4>
                                        <p>Account setup completed successfully.</p>
                                    </div>
                                </div>
                                <div class="timeline-item <?php echo $isCounseling ? 'completed' : 'active'; ?>">
                                    <div class="timeline-dot"></div>
                                    <div class="timeline-content">
                                        <h4>Counseling & Shortlisting</h4>
                                        <p>
                                            <?php if ($counselor !== 'Pending Assignment'): ?>
                                                Assigned to: <strong style="color:var(--bs-primary);"><?php echo htmlspecialchars($counselor); ?></strong>
                                            <?php else: ?>
                                                Our experts will contact you soon.
                                            <?php endif; ?>
                                        </p>
                                    </div>
                                </div>
                                <div class="timeline-item <?php echo $isDocs ? 'completed' : ($isCounseling ? 'active' : ''); ?>">
                                    <div class="timeline-dot"></div>
                                    <div class="timeline-content">
                                        <h4>Document Submission</h4>
                                        <p>Upload SOPs, transcripts, and passport copies.</p>
                                    </div>
                                </div>
                                <div class="timeline-item <?php echo $isDecision ? 'completed' : ($isDocs ? 'active' : ''); ?>">
                                    <div class="timeline-dot"></div>
                                    <div class="timeline-content">
                                        <h4>University Decisions</h4>
                                        <p>Current Status: <strong data-crm-field="primary_status" style="color:var(--bs-primary);"><?php echo htmlspecialchars($currentStatus); ?></strong></p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- App Details -->
                        <div class="crm-panel">
                            <h3 class="crm-panel-title">CRM Application Details</h3>
                            
                            <div style="background: #f9fafb; padding: 16px; border-radius: 8px; margin-bottom: 20px; border: 1px solid var(--bs-border);">
                                <?php if (!empty($crmData['lead_code'])): ?>
                                <p style="font-size: 12px; color: var(--bs-text-light); margin-bottom: 8px;">CRM Record: <strong><?php echo htmlspecialchars($crmData['lead_code']); ?></strong></p>
                                <?php endif; ?>
                                <p style="font-size: 13px; color: var(--bs-text-light); margin-bottom: 4px;">Primary Status</p>
                                <h4 data-crm-field="primary_status" style="font-size: 18px; color: var(--bs-primary); font-weight: 600;"><?php echo htmlspecialchars($currentStatus); ?></h4>
                                <p id="crm-pipeline-status-row" style="font-size: 13px; color: #0284c7; margin-top: 4px;<?php echo empty($pipelineStatus) ? ' display:none;' : ''; ?>"><i class="fa-solid fa-code-branch"></i> Pipeline Status: <span data-crm-field="overseas_pipeline_status"><?php echo htmlspecialchars($pipelineStatus); ?></span></p>
                                <p id="crm-secondary-status-row" style="font-size: 13px; color: #f59e0b; margin-top: 4px;<?php echo empty($secondaryStatus) ? ' display:none;' : ''; ?>"><i class="fa-solid fa-circle-info"></i> <span data-crm-field="secondary_status"><?php echo htmlspecialchars($secondaryStatus); ?></span></p>
                            </div>

                            <div class="app-detail-row">
                                <span class="app-detail-label">Assigned Counselor</span>
                                <span class="app-detail-value" data-crm-field="assigned_to_name"><?php echo htmlspecialchars($counselor); ?></span>
                            </div>

                            <div class="app-detail-row">
                                <span class="app-detail-label">Passport No</span>
                                <span class="app-detail-value" data-crm-field="passport_no"><?php echo !empty($crmData['passport_no']) ? htmlspecialchars($crmData['passport_no']) : '-'; ?></span>
                            </div>

                            <div class="app-detail-row" id="crm-remarks-row" style="flex-direction: column; align-items: flex-start; gap: 8px;<?php echo empty($crmData['remarks']) ? ' display:none;' : ''; ?>">
                                <div class="remarks-heading">
                                    <span class="app-detail-label">Latest Remarks</span>
                                    <span class="remarks-history-wrap" id="crm-remarks-history-wrap" style="display:none;">
                                        <button type="button" class="remarks-history-button" id="crm-remarks-history-button" aria-haspopup="dialog" aria-controls="crm-remarks-history-dialog">
                                            View all updates
                                        </button>
                                        <span class="remarks-history-preview" id="crm-remarks-history-preview" aria-hidden="true"></span>
                                    </span>
                                </div>
                                <div id="crm-latest-remarks" style="white-space: pre-line; background: white; border: 1px solid var(--bs-border); border-radius: 8px; padding: 12px; width: 100%; font-size: 14px; color: var(--bs-text); line-height: 1.5;">
                                    <?php echo htmlspecialchars($crmData['remarks']); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php elseif ($crmLookupError): ?>
                        <div style="text-align: center; padding: 60px 20px; background: white; border-radius: 12px; margin-top: 24px; border: 1px solid var(--bs-border);">
                            <i class="fa-solid fa-triangle-exclamation" style="font-size: 42px; color: #f59e0b; margin-bottom: 16px;"></i>
                            <h3 style="font-size: 18px; color: #475569; margin-bottom: 8px;">Application status is temporarily unavailable</h3>
                            <p style="color: #64748b; font-size: 14px; max-width: 460px; margin: 0 auto;">We couldn't load the linked CRM record. Please refresh later or contact your counselor.</p>
                        </div>
                    <?php else: ?>
                        <div style="text-align: center; padding: 60px 20px; background: white; border-radius: 12px; margin-top: 24px; border: 1px solid var(--bs-border);">
                            <i class="fa-solid fa-file-circle-question" style="font-size: 48px; color: #cbd5e1; margin-bottom: 16px;"></i>
                            <h3 style="font-size: 18px; color: #475569; margin-bottom: 8px;">No CRM record linked yet</h3>
                            <p style="color: #64748b; font-size: 14px; max-width: 460px; margin: 0 auto;">If you already have an application with Bluestone, contact your counselor and ask them to link your student portal account to your CRM record. Your counselor remarks and logs will appear here once linked.</p>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="remarks-history-dialog" id="crm-remarks-history-dialog" role="dialog" aria-modal="true" aria-labelledby="crm-remarks-history-title" aria-hidden="true">
                    <div class="remarks-history-dialog-content">
                        <div class="remarks-history-dialog-header">
                            <h3 id="crm-remarks-history-title">CRM remark and comment history</h3>
                            <button type="button" class="remarks-history-close" id="crm-remarks-history-close" aria-label="Close remark history">&times;</button>
                        </div>
                        <div class="remarks-history-table-wrap">
                            <table class="remarks-history-table">
                                <thead>
                                    <tr>
                                        <th>Updated</th>
                                        <th>By</th>
                                        <th>Version</th>
                                        <th>Remark / comment</th>
                                    </tr>
                                </thead>
                                <tbody id="crm-remarks-history-body">
                                    <tr><td colspan="4">No remark updates yet.</td></tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div> <!-- End Application View -->

            <!-- Community View -->
            <div id="view-community" class="tab-view <?php echo $activeTab === 'community' ? 'active-view' : ''; ?>">
                <div class="comm-tabs">
                    <button class="comm-tab-btn active" data-comm-target="comm-feed">Feed</button>
                    <button class="comm-tab-btn" data-comm-target="comm-groups">Groups <span class="community-unread-badge" id="community-groups-unread-badge"<?= $totalGroupUnreadCount > 0 ? '' : ' hidden'; ?>><?= $totalGroupUnreadCount > 99 ? '99+' : $totalGroupUnreadCount; ?></span></button>
                    <button class="comm-tab-btn" data-comm-target="comm-friends">Find friends</button>
                    <button class="comm-tab-btn" data-comm-target="comm-inbox">Inbox <span class="community-unread-badge" id="community-inbox-unread-badge" hidden></span></button>
                    <button class="comm-tab-btn" data-comm-target="comm-dreamwall">Dreamwall</button>
                </div>
                
                <div style="background: var(--bs-white); border-radius: 12px; padding: 24px; border: 1px solid var(--bs-border);">
                    
                    <!-- Feed Tab -->
                    <div id="comm-feed" class="comm-sub-view active-sub-view">
                        <?php if (isset($_GET['post_shared']) || isset($_GET['post_updated']) || isset($_GET['post_deleted'])): ?>
                        <div class="community-feedback success" role="status">
                            <?php
                                if (isset($_GET['post_updated'])) {
                                    echo 'Your post was updated.';
                                } elseif (isset($_GET['post_deleted'])) {
                                    echo 'Your post was deleted.';
                                } else {
                                    echo 'Your post was shared with the student community.';
                                }
                            ?>
                        </div>
                        <?php endif; ?>
                        <?php if (!empty($_SESSION['user_id']) && ($_SESSION['user_role'] ?? '') === 'student'): ?>
                        <form id="community-post-form" class="community-composer" enctype="multipart/form-data">
                            <h3>Share with the community</h3>
                            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($communityCsrfToken, ENT_QUOTES, 'UTF-8'); ?>">
                            <textarea name="content" maxlength="2000" placeholder="What would you like to share?" aria-label="Post description"></textarea>
                            <div class="community-composer-actions">
                                <label>
                                    <i class="fa-regular fa-image"></i>
                                    <span>Add an image or video</span>
                                    <input type="file" name="media" accept="image/jpeg,image/png,image/gif,image/webp,video/mp4,video/webm,video/quicktime" hidden>
                                </label>
                                <button class="community-submit" type="submit">Share post</button>
                            </div>
                            <img class="community-media-preview" alt="Selected image preview">
                            <video class="community-media-preview" controls playsinline aria-label="Selected video preview"></video>
                            <div class="community-feedback" role="status" aria-live="polite"></div>
                        </form>
                        <?php endif; ?>

                        <div class="community-search-row">
                            <div class="community-search-wrap">
                                <i class="fa-solid fa-magnifying-glass" aria-hidden="true"></i>
                                <input id="community-search" class="community-search-input" type="search" placeholder="Search posts, people, or destinations..." aria-label="Search community posts">
                            </div>
                        </div>

                        <div class="comm-feed-grid">
                            <div id="community-no-results" class="community-no-results">No posts match your search.</div>
                            <?php if (empty($communityPosts)): ?>
                                <div id="community-empty-state" style="grid-column: 1 / -1; text-align: center; padding: 40px; color: var(--bs-text-light);">
                                    <i class="fa-solid fa-camera-retro" style="font-size: 3rem; margin-bottom: 1rem; color: #cbd5e1;"></i>
                                    <p>No posts available yet. Be the first to share your journey!</p>
                                </div>
                            <?php else: ?>
                                <?php foreach($communityPosts as $post): ?>
                                <?php
                                    $postAuthor = $post['author_name'] ?? 'Bluestone Member';
                                    $postDestination = $post['destination'] ?? 'University Applicant';
                                    $postTag = $post['destination_tag'] ?? '';
                                    $postMediaPath = $post['media_path'] ?? '';
                                    $postIsVideo = preg_match('/\.(mp4|webm|mov)$/i', $postMediaPath) === 1;
                                    $postSearchText = strtolower(implode(' ', [$post['content'] ?? '', $postAuthor, $postDestination, $postTag, $postIsVideo ? 'video' : 'image']));
                                    $postCreatedAt = !empty($post['created_at']) ? strtotime($post['created_at']) : false;
                                ?>
                                <div class="comm-post-card" data-community-post data-search="<?php echo htmlspecialchars($postSearchText, ENT_QUOTES, 'UTF-8'); ?>">
                                    <div class="comm-post-header">
                                        <div class="comm-post-avatar">
                                            <img src="<?php echo !empty($post['avatar_url']) ? htmlspecialchars($post['avatar_url']) : 'assets/images/team_placeholder.jpg'; ?>" onerror="this.src='assets/images/logo.png'">
                                        </div>
                                        <div class="comm-post-meta">
                                            <h4><?php echo htmlspecialchars($postAuthor); ?></h4>
                                            <span><?php echo htmlspecialchars($postDestination); ?></span><br>
                                            <span style="font-size:11px; margin-top:2px; display:inline-block;"><?php echo $postCreatedAt ? date('M j, Y', $postCreatedAt) : ''; ?></span>
                                        </div>
                                        <?php if ((int) ($post['student_created'] ?? 0) === 1 && (int) ($post['user_id'] ?? 0) === (int) $_SESSION['user_id']): ?>
                                        <details class="comm-post-menu">
                                            <summary aria-label="Post options"><i class="fa-solid fa-ellipsis"></i></summary>
                                            <div class="comm-post-menu-items">
                                                <button type="button" data-post-edit> <i class="fa-regular fa-pen-to-square"></i> Edit post</button>
                                                <button type="button" class="comm-post-delete" data-post-delete><i class="fa-regular fa-trash-can"></i> Delete post</button>
                                            </div>
                                        </details>
                                        <?php endif; ?>
                                    </div>
                                    <?php if ($postMediaPath !== ''): ?>
                                    <div class="comm-post-media">
                                        <?php if ($postIsVideo): ?>
                                        <video controls playsinline preload="metadata">
                                            <source src="<?php echo htmlspecialchars($postMediaPath, ENT_QUOTES, 'UTF-8'); ?>">
                                            Your browser does not support video playback.
                                        </video>
                                        <?php else: ?>
                                        <img src="<?php echo htmlspecialchars($postMediaPath, ENT_QUOTES, 'UTF-8'); ?>" alt="Post media">
                                        <?php endif; ?>
                                    </div>
                                    <?php endif; ?>
                                    <div class="comm-post-content">
                                        <?php echo nl2br(htmlspecialchars($post['content'] ?? '')); ?>
                                    </div>
                                    <?php if ((int) ($post['student_created'] ?? 0) === 1 && (int) ($post['user_id'] ?? 0) === (int) $_SESSION['user_id']): ?>
                                    <form class="comm-post-edit-form" hidden enctype="multipart/form-data">
                                        <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($communityCsrfToken, ENT_QUOTES, 'UTF-8'); ?>">
                                        <input type="hidden" name="action" value="edit">
                                        <input type="hidden" name="post_id" value="<?php echo (int) $post['id']; ?>">
                                        <textarea name="content" maxlength="2000" aria-label="Edit post description"><?php echo htmlspecialchars($post['content'] ?? ''); ?></textarea>
                                        <div class="comm-post-edit-options">
                                            <label>
                                                <i class="fa-regular fa-image"></i> Replace image/video
                                                <input type="file" name="media" accept="image/jpeg,image/png,image/gif,image/webp,video/mp4,video/webm,video/quicktime">
                                            </label>
                                            <?php if ($postMediaPath !== ''): ?>
                                            <label><input type="checkbox" name="remove_media" value="1"> Remove current media</label>
                                            <?php endif; ?>
                                        </div>
                                        <div class="community-feedback" role="status" aria-live="polite"></div>
                                        <div class="comm-post-edit-actions">
                                            <button class="community-submit" type="submit">Save changes</button>
                                            <button class="comm-post-edit-cancel" type="button" data-post-edit-cancel>Cancel</button>
                                        </div>
                                    </form>
                                    <?php endif; ?>
                                </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Groups Tab -->
                    <div id="comm-groups" class="comm-sub-view">
                        <div id="community-group-list" class="community-group-list">
                            <div class="community-group-toolbar">
                                <div>
                                    <h3 style="font-size: 18px; color: var(--bs-text); margin-bottom: 4px;">Student groups</h3>
                                    <p style="font-size: 13px; color: var(--bs-text-light);">Join a group to chat, share tips and plan your next steps together.</p>
                                </div>
                                <button type="button" class="btn-outline" style="padding: 8px 16px;" id="community-create-group">Create group</button>
                            </div>
                            <input type="search" id="community-group-search" class="community-group-search" placeholder="Search groups..." aria-label="Search groups" autocomplete="off">
                            <div id="community-group-feedback" class="community-group-feedback" role="status" aria-live="polite"></div>
                            <div id="community-groups-grid" class="comm-feed-grid">
                            <?php if (empty($communityGroups)): ?>
                                <div id="community-groups-empty" style="grid-column: 1 / -1; text-align: center; padding: 40px; color: var(--bs-text-light);">
                                    <i class="fa-solid fa-users-viewfinder" style="font-size: 3rem; margin-bottom: 1rem; color: #cbd5e1;"></i>
                                    <p>No groups yet. Create one to start connecting!</p>
                                </div>
                            <?php else: ?>
                                <?php foreach ($communityGroups as $g): ?>
                                <article class="comm-group-card" data-community-group data-group-id="<?php echo (int) $g['id']; ?>" data-group-search="<?php echo htmlspecialchars(strtolower(($g['name'] ?? '') . ' ' . ($g['description'] ?? '')), ENT_QUOTES, 'UTF-8'); ?>">
                                    <?php if (!empty($g['image_url'])): ?>
                                    <div class="comm-group-image">
                                        <img src="<?php echo htmlspecialchars($g['image_url'], ENT_QUOTES, 'UTF-8'); ?>" alt="">
                                    </div>
                                    <?php endif; ?>
                                    <div class="comm-group-content">
                                        <h4><?php echo htmlspecialchars($g['name'], ENT_QUOTES, 'UTF-8'); ?></h4>
                                        <p><?php echo htmlspecialchars($g['description'] ?? 'A place to connect with fellow students.', ENT_QUOTES, 'UTF-8'); ?></p>
                                        <div class="community-group-card-meta">
                                            <span data-member-count><?php echo (int) $g['member_count']; ?> <?php echo (int) $g['member_count'] === 1 ? 'member' : 'members'; ?></span>
                                            <?php $groupUnreadCount = (int) ($groupUnreadCounts[(int) $g['id']] ?? 0); ?>
                                            <span class="community-unread-badge" data-group-unread<?= $groupUnreadCount > 0 ? '' : ' hidden'; ?>><?= $groupUnreadCount > 99 ? '99+' : $groupUnreadCount; ?></span>
                                        </div>
                                        <button type="button" class="btn-join" data-group-action="<?php echo !empty($g['is_member']) ? 'open' : 'join'; ?>">
                                            <?php echo !empty($g['is_member']) ? 'Open chat' : 'Join group'; ?>
                                        </button>
                                    </div>
                                </article>
                                <?php endforeach; ?>
                                <div id="community-groups-no-results" hidden style="grid-column: 1 / -1; text-align: center; padding: 30px; color: var(--bs-text-light);">No groups match your search.</div>
                            <?php endif; ?>
                            </div>
                        </div>
                        <section id="community-group-chat" class="community-group-chat" aria-label="Group chat" aria-hidden="true" hidden>
                            <header class="community-group-chat-header">
                                <button type="button" class="community-group-back" id="community-group-back" aria-label="Collapse chat" aria-controls="community-group-chat"><i class="fa-solid fa-arrow-left"></i></button>
                                <div>
                                    <h4 id="community-chat-title"></h4>
                                    <p id="community-chat-members"></p>
                                </div>
                                <input type="search" id="community-group-message-search" class="community-chat-search" placeholder="Search loaded messages" aria-label="Search group messages">
                                <select id="community-group-notification-mode" class="community-chat-notifications" aria-label="Group notification setting">
                                    <option value="all">Alerts: all</option>
                                    <option value="mentions">Alerts: mentions</option>
                                    <option value="muted">Alerts: muted</option>
                                </select>
                                <button type="button" class="community-group-leave" id="community-group-leave">Leave group</button>
                            </header>
                            <div id="community-chat-feedback" class="community-group-feedback" role="status" aria-live="polite" style="margin: 8px 14px 0;"></div>
                            <div id="community-group-messages" class="community-group-messages" role="log" aria-live="polite" aria-relevant="additions text">
                                <div class="community-group-empty">Loading conversation...</div>
                            </div>
                            <form id="community-group-compose" class="community-group-compose">
                                <div id="community-group-reply-preview" class="community-chat-reply-preview" hidden>
                                    <div class="community-chat-reply-preview-copy">
                                        <strong id="community-group-reply-name"></strong>
                                        <span id="community-group-reply-text"></span>
                                    </div>
                                    <button type="button" class="community-chat-reply-cancel" id="community-group-reply-cancel" aria-label="Cancel reply"><i class="fa-solid fa-xmark" aria-hidden="true"></i></button>
                                </div>
                                <div class="community-group-compose-row">
                                    <input type="file" id="community-group-attachment" name="attachment" accept="image/jpeg,image/png,image/gif,image/webp,video/mp4,video/webm,video/quicktime,.pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.txt,.csv" hidden>
                                    <button type="button" class="community-group-attachment-button" id="community-group-attachment-button" aria-label="Attach a photo, video, or document"><i class="fa-solid fa-paperclip"></i></button>
                                    <span class="community-group-attachment-name" id="community-group-attachment-name"></span>
                                    <textarea name="message" maxlength="2000" placeholder="Message the group..." aria-label="Message the group"></textarea>
                                    <button type="submit" aria-label="Send message"><i class="fa-solid fa-paper-plane"></i></button>
                                </div>
                                <div id="community-group-upload-status" class="community-chat-upload-status" role="status" aria-live="polite"></div>
                            </form>
                        </section>
                        <div id="community-create-modal" class="community-group-modal" role="dialog" aria-modal="true" aria-labelledby="community-create-title" hidden>
                            <form id="community-create-form" class="community-group-modal-card">
                                <h3 id="community-create-title" style="font-size: 19px;">Create a student group</h3>
                                <p style="margin-top: 6px; color: #64748b; font-size: 13px;">Start a group around a destination, university, course or shared interest.</p>
                                <label for="community-create-name">Group name</label>
                                <input type="text" id="community-create-name" name="name" maxlength="150" placeholder="e.g. Canada Fall 2026" required>
                                <label for="community-create-description">Description</label>
                                <textarea id="community-create-description" name="description" maxlength="500" placeholder="What would you like to discuss?"></textarea>
                                <div id="community-create-feedback" class="community-group-feedback" role="status" aria-live="polite"></div>
                                <div class="community-group-modal-actions">
                                    <button type="button" class="comm-post-edit-cancel" id="community-create-cancel">Cancel</button>
                                    <button type="submit" class="community-group-create-submit">Create group</button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Find friends Tab -->
                    <div id="comm-friends" class="comm-sub-view">
                        <div class="community-friends-header">
                            <div>
                                <h3 style="font-size: 18px; color: var(--bs-text); margin-bottom: 5px;">Find your people</h3>
                                <p style="color: var(--bs-text-light); font-size: 13px;">Meet students with similar study plans. Your email and phone are never shown.</p>
                            </div>
                            <label class="community-discover-toggle">
                                <input type="checkbox" id="community-discoverable" checked>
                                <span>Show me in Find Friends</span>
                            </label>
                        </div>
                        <div id="community-friends-feedback" class="community-group-feedback" role="status" aria-live="polite"></div>
                        <section id="community-incoming-section" hidden>
                            <h4 class="community-friend-section">Connection requests</h4>
                            <div id="community-incoming-grid" class="community-friend-grid"></div>
                        </section>
                        <div id="community-discovery-content" hidden>
                            <h4 class="community-friend-section">Students you may know</h4>
                            <div class="community-friend-filters">
                                <input type="search" id="community-friend-search" placeholder="Search name, destination or course" aria-label="Search students" autocomplete="off">
                                <input type="search" id="community-friend-country" placeholder="Destination" aria-label="Filter by destination" autocomplete="off">
                                <input type="search" id="community-friend-course" placeholder="Course or degree" aria-label="Filter by course or degree" autocomplete="off">
                                <input type="search" id="community-friend-intake" placeholder="Intake" aria-label="Filter by intake" autocomplete="off">
                            </div>
                            <div id="community-student-grid" class="community-friend-grid"></div>
                        </div>
                        <div id="community-discovery-private" class="community-friend-notice" hidden>
                            <p>Your profile is hidden from the student list. Turn on “Show me in Find Friends” whenever you want to appear again. Only your name and study interests are shown.</p>
                        </div>
                        <div id="community-friends-empty" class="community-friend-notice" hidden>No students match these filters yet. Try a different search.</div>
                        <div id="community-friend-report-modal" class="community-friend-report-modal" role="dialog" aria-modal="true" aria-labelledby="community-friend-report-title" hidden>
                            <form id="community-friend-report-form" class="community-friend-report-card">
                                <h3 id="community-friend-report-title" style="font-size: 18px;">Report student</h3>
                                <p id="community-friend-report-name" style="margin-top: 6px; color: #64748b; font-size: 13px;"></p>
                                <textarea name="reason" maxlength="1000" placeholder="Tell us what happened. Please do not include sensitive personal information." required></textarea>
                                <div id="community-friend-report-feedback" class="community-group-feedback" role="status" aria-live="polite"></div>
                                <div class="community-group-modal-actions">
                                    <button type="button" class="comm-post-edit-cancel" id="community-friend-report-cancel">Cancel</button>
                                    <button type="submit" class="community-group-create-submit">Send report</button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Inbox Tab -->
                    <div id="comm-inbox" class="comm-sub-view">
                        <h3 style="font-size: 18px; color: var(--bs-text); margin-bottom: 16px;">Messages</h3>
                        <div id="community-inbox-feedback" class="community-group-feedback" role="status" aria-live="polite"></div>
                        <div class="community-inbox">
                            <div id="community-inbox-threads" class="community-inbox-threads" aria-label="Conversations">
                                <div class="community-inbox-empty">Your connected students will appear here.</div>
                            </div>
                            <section class="community-inbox-chat" aria-label="Personal chat">
                                <header class="community-inbox-chat-header">
                                    <span id="community-inbox-chat-header">Select a connection to start chatting</span>
                                    <select id="community-inbox-notification-mode" class="community-chat-notifications" aria-label="Conversation notification setting" hidden>
                                        <option value="all">Alerts: all</option>
                                        <option value="mentions">Alerts: mentions</option>
                                        <option value="muted">Alerts: muted</option>
                                    </select>
                                    <button type="button" id="community-inbox-block" class="community-inbox-safety-action" hidden>Block</button>
                                    <button type="button" id="community-inbox-report" class="community-inbox-safety-action" hidden>Report</button>
                                </header>
                                <input type="search" id="community-inbox-message-search" class="community-chat-search" placeholder="Search loaded messages" aria-label="Search personal messages">
                                <div id="community-inbox-messages" class="community-inbox-messages" role="log" aria-live="polite" aria-relevant="additions text">
                                    <div class="community-inbox-placeholder">Personal messages are available after you connect with another student.</div>
                                </div>
                                <form id="community-inbox-compose" class="community-inbox-compose" hidden>
                                    <input type="file" id="community-inbox-attachment" name="attachment" accept="image/jpeg,image/png,image/gif,image/webp,video/mp4,video/webm,video/quicktime,.pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.txt,.csv" hidden>
                                    <div id="community-inbox-reply-preview" class="community-inbox-reply-preview" hidden>
                                        <div class="community-inbox-reply-preview-copy">
                                            <strong id="community-inbox-reply-name"></strong>
                                            <span id="community-inbox-reply-text"></span>
                                        </div>
                                        <button type="button" id="community-inbox-reply-cancel" class="community-inbox-reply-cancel" aria-label="Cancel reply"><i class="fa-solid fa-xmark"></i></button>
                                    </div>
                                    <button type="button" class="community-inbox-attach-button" id="community-inbox-attach-button" aria-label="Attach a photo, video, or document"><i class="fa-solid fa-paperclip"></i></button>
                                    <span class="community-inbox-attachment-name" id="community-inbox-attachment-name"></span>
                                    <textarea name="message" maxlength="2000" placeholder="Write a message..." aria-label="Write a personal message"></textarea>
                                    <button type="submit" aria-label="Send message"><i class="fa-solid fa-paper-plane"></i></button>
                                </form>
                                <div id="community-inbox-upload-status" class="community-chat-upload-status" role="status" aria-live="polite"></div>
                            </section>
                        </div>
                    </div>

                    <!-- Dreamwall Tab -->
                    <div id="comm-dreamwall" class="comm-sub-view">
                        <section class="dreamwall-hero">
                            <div>
                                <h2>Wall of Success: Celebrating Our Students' Global Milestones 🎉</h2>
                                <p>Discover inspiring visa approvals, university admits, and scholarship wins from students who prepared with Bluestone.</p>
                            </div>
                            <button class="dreamwall-share-button" id="dreamwall-share-open" type="button"><i class="fa-solid fa-plus" aria-hidden="true"></i> Celebrate Your Milestone</button>
                        </section>
                        <div class="dreamwall-filter-row" role="group" aria-label="Filter milestones">
                            <button class="dreamwall-filter active" type="button" data-dreamwall-type="All" aria-pressed="true">All</button>
                            <button class="dreamwall-filter" type="button" data-dreamwall-type="Visa Approved" aria-pressed="false">Visa Approved</button>
                            <button class="dreamwall-filter" type="button" data-dreamwall-type="Offer Letter" aria-pressed="false">Offer Letter</button>
                            <button class="dreamwall-filter" type="button" data-dreamwall-type="Scholarship" aria-pressed="false">Scholarship</button>
                        </div>
                        <p id="dreamwall-feedback" class="dreamwall-feedback" role="status" aria-live="polite"></p>
                        <div id="dreamwall-grid" class="dreamwall-grid" aria-live="polite"></div>
                    </div>
                </div>
                
            </div>

            <div class="dreamwall-modal" id="dreamwall-share-modal" role="dialog" aria-modal="true" aria-labelledby="dreamwall-share-title" hidden>
                <form class="dreamwall-modal-card" id="dreamwall-share-form">
                    <div class="dreamwall-modal-header">
                        <div>
                            <h3 id="dreamwall-share-title">Share your milestone</h3>
                            <p>Celebrate your achievement and inspire another student. An admin will review your submission before it appears on Dreamwall.</p>
                        </div>
                        <button class="dreamwall-modal-close" type="button" data-dreamwall-close aria-label="Close dialog">&times;</button>
                    </div>
                    <div class="dreamwall-form-grid">
                        <div class="dreamwall-form-field">
                            <label for="dreamwall-type">Milestone type</label>
                            <select id="dreamwall-type" name="milestone_type" required>
                                <option value="">Select a milestone</option>
                                <option value="Visa Approved">Visa Approved</option>
                                <option value="Offer Letter">Offer Letter / Admit</option>
                                <option value="Scholarship">Scholarship</option>
                                <option value="IELTS Success">Exam Success</option>
                            </select>
                        </div>
                        <div class="dreamwall-form-field">
                            <label for="dreamwall-intake">Intake</label>
                            <input id="dreamwall-intake" name="intake" type="text" maxlength="60" placeholder="Fall 2026" required>
                        </div>
                        <div class="dreamwall-form-field full">
                            <label for="dreamwall-title">Title / headline</label>
                            <input id="dreamwall-title" name="title" type="text" maxlength="180" placeholder="My Australian student visa was approved!" required>
                        </div>
                        <div class="dreamwall-form-field">
                            <label for="dreamwall-university">University</label>
                            <input id="dreamwall-university" name="university_name" type="text" maxlength="180" required>
                        </div>
                        <div class="dreamwall-form-field">
                            <label for="dreamwall-country">Destination country</label>
                            <select id="dreamwall-country" name="country" required>
                                <option value="">Choose a country</option>
                                <option value="Australia" data-flag="🇦🇺">Australia</option>
                                <option value="Canada" data-flag="🇨🇦">Canada</option>
                                <option value="France" data-flag="🇫🇷">France</option>
                                <option value="Germany" data-flag="🇩🇪">Germany</option>
                                <option value="Ireland" data-flag="🇮🇪">Ireland</option>
                                <option value="New Zealand" data-flag="🇳🇿">New Zealand</option>
                                <option value="United Kingdom" data-flag="🇬🇧">United Kingdom</option>
                                <option value="United States" data-flag="🇺🇸">United States</option>
                                <option value="Other" data-flag="🌍">Other</option>
                            </select>
                        </div>
                        <div class="dreamwall-form-field full">
                            <label for="dreamwall-story">Your story / preparation advice</label>
                            <textarea id="dreamwall-story" name="story" maxlength="1200" placeholder="Share what helped you prepare and any advice for students following your path..." required></textarea>
                        </div>
                        <div class="dreamwall-form-field full">
                            <label for="dreamwall-proof">Proof image (optional)</label>
                            <input id="dreamwall-proof" name="proof_image" type="file" accept="image/jpeg,image/png,image/webp">
                            <span class="dreamwall-upload-note">Upload a JPG, PNG, or WebP image up to 5 MB.</span>
                        </div>
                    </div>
                    <div id="dreamwall-share-feedback" class="dreamwall-modal-feedback" role="status" aria-live="polite"></div>
                    <div class="dreamwall-modal-actions">
                        <button class="dreamwall-modal-cancel" type="button" data-dreamwall-close>Cancel</button>
                        <button class="dreamwall-modal-submit" type="submit">Share milestone</button>
                    </div>
                </form>
            </div>

            <!-- Documents View: Categorized Management & Verification Hub -->
            <div id="view-documents" class="tab-view <?php echo $activeTab === 'documents' ? 'active-view' : ''; ?>">
                <div class="doc-hub-header">
                    <div>
                        <h1 class="doc-hub-title">Document Management & Verification</h1>
                        <p class="doc-hub-subtitle">Organize, upload, and track certified verification for all admissions and student visa documents. Review counselor feedback and verify test and passport validity dates.</p>
                    </div>
                    <div style="display: flex; align-items: center; gap: 8px; flex-wrap: wrap;">
                        <span class="doc-intake-badge" style="background:#f0fdf4; color:#166534; border-color:#bbf7d0;">
                            <i class="fa-solid fa-arrows-rotate" style="color:#16a34a;"></i> CRM Synced: <strong><?php echo $documentCount; ?> Files</strong>
                        </span>
                        <span class="doc-intake-badge">
                            <i class="fa-solid fa-calendar-check"></i> Target Intake: <strong><?php echo htmlspecialchars($applicationIntake ?: 'Sep 2026'); ?></strong>
                        </span>
                    </div>
                </div>

                <!-- Expiry Alerts Banner Container (Injected by JS if Passport or IELTS expires before intake) -->
                <div id="doc-expiry-alerts-container" class="doc-expiry-container"></div>

                <!-- Verification Progress Overview Card -->
                <div class="doc-overview-card">
                    <div class="doc-kpi-grid">
                        <div class="doc-kpi-box total">
                            <div class="doc-kpi-icon"><i class="fa-solid fa-folder-open"></i></div>
                            <div>
                                <div class="doc-kpi-val" id="kpi-val-total"><?php echo $documentCount; ?></div>
                                <div class="doc-kpi-label">Uploaded in Dossier</div>
                            </div>
                        </div>
                        <div class="doc-kpi-box verified">
                            <div class="doc-kpi-icon"><i class="fa-solid fa-circle-check"></i></div>
                            <div>
                                <div class="doc-kpi-val" id="kpi-val-verified" style="color: #059669;"><?php echo $docVerifiedCount; ?></div>
                                <div class="doc-kpi-label">Verified (Green)</div>
                            </div>
                        </div>
                        <div class="doc-kpi-box review">
                            <div class="doc-kpi-icon"><i class="fa-solid fa-clock"></i></div>
                            <div>
                                <div class="doc-kpi-val" id="kpi-val-review" style="color: #d97706;"><?php echo $docUnderReviewCount; ?></div>
                                <div class="doc-kpi-label">Under Review (Yellow)</div>
                            </div>
                        </div>
                        <div class="doc-kpi-box reupload">
                            <div class="doc-kpi-icon"><i class="fa-solid fa-circle-exclamation"></i></div>
                            <div>
                                <div class="doc-kpi-val" id="kpi-val-reupload" style="color: #dc2626;"><?php echo $docNeedsReuploadCount; ?></div>
                                <div class="doc-kpi-label">Action Required (Red)</div>
                            </div>
                        </div>
                    </div>

                    <div>
                        <div class="doc-progress-meta">
                            <span><i class="fa-solid fa-shield-halved" style="color:#10b981;"></i> Document Verification Readiness</span>
                            <span id="doc-progress-text"><?php echo $docCompletionPct; ?>% Certified (<?php echo $docVerifiedCount; ?> of <?php echo $docTotalCount; ?> Verified)</span>
                        </div>
                        <div class="doc-progress-wrap">
                            <div class="doc-progress-bar" id="doc-progress-bar" style="width: <?php echo $docCompletionPct; ?>%;"></div>
                        </div>
                    </div>
                </div>

                <!-- Stage Filter Toolbar -->
                <div class="doc-filter-toolbar">
                    <div class="doc-stage-nav">
                        <button type="button" class="doc-stage-btn active" data-stage="all" onclick="filterDocStage('all', this)">
                            <i class="fa-solid fa-layer-group"></i> All Stages
                        </button>
                        <button type="button" class="doc-stage-btn" data-stage="academic" onclick="filterDocStage('academic', this)">
                            <i class="fa-solid fa-graduation-cap"></i> Academic Records
                        </button>
                        <button type="button" class="doc-stage-btn" data-stage="tests" onclick="filterDocStage('tests', this)">
                            <i class="fa-solid fa-language"></i> English & Tests
                        </button>
                        <button type="button" class="doc-stage-btn" data-stage="essentials" onclick="filterDocStage('essentials', this)">
                            <i class="fa-solid fa-file-lines"></i> Essentials (SOP/CV)
                        </button>
                        <button type="button" class="doc-stage-btn" data-stage="identity" onclick="filterDocStage('identity', this)">
                            <i class="fa-solid fa-passport"></i> Identity & Financials
                        </button>
                        <button type="button" class="doc-stage-btn" data-stage="additional" onclick="filterDocStage('additional', this)">
                            <i class="fa-solid fa-folder-plus"></i> Additional & CRM
                        </button>
                    </div>

                    <div style="display:flex; align-items:center; gap:12px; flex-wrap:wrap;">
                        <div class="doc-filter-pills">
                            <button type="button" class="doc-pill-btn active" data-status="all" onclick="filterDocStatus('all', this)">All</button>
                            <button type="button" class="doc-pill-btn" data-status="reupload" onclick="filterDocStatus('reupload', this)" style="color:#dc2626;">
                                <i class="fa-solid fa-circle-exclamation"></i> Action Required
                            </button>
                            <button type="button" class="doc-pill-btn" data-status="review" onclick="filterDocStatus('review', this)" style="color:#d97706;">
                                <i class="fa-solid fa-clock"></i> Under Review
                            </button>
                            <button type="button" class="doc-pill-btn" data-status="verified" onclick="filterDocStatus('verified', this)" style="color:#059669;">
                                <i class="fa-solid fa-circle-check"></i> Verified
                            </button>
                        </div>

                        <div class="doc-search-box">
                            <i class="fa-solid fa-magnifying-glass"></i>
                            <input type="text" class="doc-search-input" id="doc-search-input" placeholder="Search documents..." oninput="handleDocSearch(this.value)">
                        </div>

                        <button type="button" class="btn-doc-action" onclick="openUploadAnyDocModal()" style="background:#2563eb; color:#ffffff; border:none; font-size:12.5px; font-weight:700; padding:8px 16px; border-radius:10px; display:inline-flex; align-items:center; gap:6px; cursor:pointer; box-shadow:0 3px 10px rgba(37,99,235,0.25);" title="Upload any custom or additional document">
                            <i class="fa-solid fa-cloud-arrow-up"></i> + Upload Any Document
                        </button>

                        <button type="button" class="btn-doc-action" onclick="openCounselorSimulatorModal()" style="background:#eff6ff; color:#1d4ed8; border:1px solid #bfdbfe; font-size:12.5px; font-weight:600; padding:8px 14px; border-radius:10px; display:inline-flex; align-items:center; gap:6px; cursor:pointer;" title="Counselor review & verification simulator">
                            <i class="fa-solid fa-user-check" style="color:#2563eb;"></i> Counselor Review
                        </button>
                    </div>
                </div>

                <!-- Categorized Stage Sections Container -->
                <div id="doc-stages-container">
                    <div style="text-align: center; padding: 60px; color: #94a3b8; background: white; border-radius: 16px; border: 1px solid #e2e8f0;">
                        <i class="fa-solid fa-spinner fa-spin" style="font-size: 32px; margin-bottom: 12px; color:#2563eb;"></i>
                        <p style="font-size: 15px; font-weight: 600; color:#475569;">Loading categorized documents & counselor verification status...</p>
                    </div>
                </div>
            </div> <!-- End Documents View -->

            <!-- Upload Any Document Modal (Custom / Standard) -->
            <div id="upload-any-doc-modal" class="doc-modal-overlay" style="display: none;" role="dialog" aria-modal="true" aria-labelledby="modal-any-doc-title">
                <div class="doc-modal-box" style="max-width: 580px;">
                    <div class="doc-modal-header" style="background: linear-gradient(135deg, #1e40af 0%, #2563eb 100%); color: #ffffff;">
                        <div style="display: flex; align-items: center; gap: 10px;">
                            <div style="width: 36px; height: 36px; border-radius: 8px; background: rgba(255,255,255,0.2); display: flex; align-items: center; justify-content: center; font-size: 16px;">
                                <i class="fa-solid fa-cloud-arrow-up"></i>
                            </div>
                            <div>
                                <h3 id="modal-any-doc-title" style="color: #ffffff; margin: 0; font-size: 16px; font-weight: 700;">Upload Candidate Document</h3>
                                <p style="color: rgba(255,255,255,0.85); margin: 0; font-size: 12px;">Auto-synchronizes with Central CRM & assigned advisor</p>
                            </div>
                        </div>
                        <button type="button" class="doc-modal-close" onclick="closeUploadAnyDocModal()" aria-label="Close modal" style="color: #ffffff; opacity: 0.85;">&times;</button>
                    </div>
                    <form id="upload-any-doc-form" enctype="multipart/form-data" onsubmit="submitAnyDocumentUpload(event)">
                        <div class="doc-modal-body" style="padding: 22px;">
                            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($communityCsrfToken); ?>">
                            <input type="hidden" name="action" value="upload">

                            <!-- Document Type / Title Selection -->
                            <div style="margin-bottom: 16px;">
                                <label style="display: block; font-size: 13px; font-weight: 700; color: #1e293b; margin-bottom: 6px;">
                                    Document Type:
                                </label>
                                <select id="any-doc-select-type" class="profile-input" style="width: 100%; padding: 10px 12px; font-size: 13.5px; border: 1px solid #cbd5e1; border-radius: 8px; background: #fff;" onchange="handleAnyDocTypeChange(this.value)" required>
                                    <option value="" disabled selected>-- Select Document Type or Choose Custom --</option>
                                    <optgroup label="Academic Records">
                                        <option value="academic_10th|Academic Records|10th Standard / Secondary Marksheet">10th Standard Marksheet</option>
                                        <option value="academic_12th|Academic Records|12th / Higher Secondary Marksheet">12th Higher Secondary Marksheet</option>
                                        <option value="academic_ug|Academic Records|Undergrad Marksheets / Transcripts">Undergraduate Consolidated / Semester Marksheets</option>
                                        <option value="academic_degree|Academic Records|Degree / Course Completion Certificate">Degree / Course Completion Certificate</option>
                                        <option value="academic_provisional|Academic Records|Provisional Certificate">Provisional Degree Certificate</option>
                                        <option value="academic_transfer|Academic Records|Transfer Certificate">Transfer Certificate (TC)</option>
                                    </optgroup>
                                    <optgroup label="English & Standardized Tests">
                                        <option value="test_english|English & Standardized Tests|English Language Scorecard (IELTS / PTE)">IELTS / PTE / TOEFL Scorecard</option>
                                        <option value="test_standardized|English & Standardized Tests|GRE / GMAT Scorecard">GRE / GMAT Scorecard</option>
                                        <option value="test_moi|English & Standardized Tests|Medium of Instruction (MOI) Certificate">Medium of Instruction (MOI) Certificate</option>
                                    </optgroup>
                                    <optgroup label="Application Essentials">
                                        <option value="app_sop|Application Essentials|Statement of Purpose (SOP)">Statement of Purpose (SOP)</option>
                                        <option value="app_cv|Application Essentials|Updated CV / Academic Resume">Updated CV / Academic Resume</option>
                                        <option value="app_lor_1|Application Essentials|Letter of Recommendation 1 (Academic LOR)">Letter of Recommendation 1 (Academic)</option>
                                        <option value="app_lor_2|Application Essentials|Letter of Recommendation 2 (Professional LOR)">Letter of Recommendation 2 (Professional/Academic)</option>
                                    </optgroup>
                                    <optgroup label="Identity & Financials">
                                        <option value="id_passport|Identity & Financials|Passport (Front & Back Pages)">Passport (Front & Back Pages)</option>
                                        <option value="id_bank_statement|Identity & Financials|Bank Statement / Proof of Funds">Bank Statement / Proof of Funds</option>
                                        <option value="id_bank_cover|Identity & Financials|Bank Cover Letter / Sanction Letter">Education Loan Sanction Letter / Bank Cover</option>
                                        <option value="id_affidavit|Identity & Financials|Affidavit of Support">Affidavit of Support (Sponsorship)</option>
                                        <option value="id_aadhar|Identity & Financials|National ID / Aadhar Card">National ID / Aadhar Card</option>
                                    </optgroup>
                                    <optgroup label="Admissions, Visa & Additional">
                                        <option value="adm_offer_letter|Additional & CRM Documents|University Offer Letter">University Offer Letter</option>
                                        <option value="adm_cas|Additional & CRM Documents|CAS / I-20 Document">CAS / I-20 Document</option>
                                        <option value="visa_application|Additional & CRM Documents|Visa Application / Approval">Visa Application / Grant Letter</option>
                                        <option value="fin_tt_copy|Additional & CRM Documents|Fee Payment Receipt / TT Copy">Fee Payment Wire Receipt / TT Copy</option>
                                        <option value="med_certificate|Additional & CRM Documents|Medical / TB Test Certificate">Medical / TB Test Certificate</option>
                                        <option value="exp_letter|Additional & CRM Documents|Work Experience Letter / Gap Justification">Work Experience Letter / Gap Certificate</option>
                                    </optgroup>
                                    <optgroup label="Custom Document">
                                        <option value="custom|Additional & CRM Documents|custom">-- Other / Enter Custom Document Name --</option>
                                    </optgroup>
                                </select>
                            </div>

                            <!-- Hidden standard fields that get auto-populated -->
                            <input type="hidden" name="doc_key" id="any-doc-field-key" value="">
                            <input type="hidden" name="category" id="any-doc-field-category" value="Additional & CRM Documents">
                            <input type="hidden" name="doc_label" id="any-doc-field-label" value="">

                            <!-- Custom Document Title Input (revealed if 'custom' is selected) -->
                            <div id="any-doc-custom-title-group" style="margin-bottom: 16px; display: none;">
                                <label style="display: block; font-size: 13px; font-weight: 700; color: #1e293b; margin-bottom: 6px;">
                                    Custom Document Title:
                                </label>
                                <input type="text" id="any-doc-custom-title-input" class="profile-input" placeholder="e.g. Police Clearance Certificate, German Blocked Account..." style="width: 100%; padding: 10px 12px; font-size: 13.5px; border: 1px solid #cbd5e1; border-radius: 8px;">
                            </div>

                            <!-- Custom Category Selector (revealed if 'custom' is selected) -->
                            <div id="any-doc-custom-cat-group" style="margin-bottom: 16px; display: none;">
                                <label style="display: block; font-size: 13px; font-weight: 700; color: #1e293b; margin-bottom: 6px;">
                                    Category:
                                </label>
                                <select id="any-doc-custom-cat-select" class="profile-input" style="width: 100%; padding: 10px 12px; font-size: 13.5px; border: 1px solid #cbd5e1; border-radius: 8px; background: #fff;">
                                    <option value="Academic Records">Academic Records</option>
                                    <option value="English & Standardized Tests">English & Standardized Tests</option>
                                    <option value="Application Essentials">Application Essentials</option>
                                    <option value="Identity & Financials">Identity & Financials</option>
                                    <option value="Additional & CRM Documents" selected>Additional & CRM Documents</option>
                                </select>
                            </div>

                            <!-- File upload dropzone -->
                            <div class="doc-dropzone" id="any-doc-dropzone" style="position: relative; border: 2px dashed #93c5fd; background: #f8fafc; border-radius: 12px; padding: 24px; text-align: center; cursor: pointer;">
                                <input type="file" name="document" id="any-doc-file-input" required accept=".pdf,.jpg,.jpeg,.png,.webp,.doc,.docx,.xls,.xlsx" onchange="handleAnyDocFileSelect(this)" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; opacity: 0; cursor: pointer;">
                                <i class="fa-solid fa-cloud-arrow-up" style="font-size: 32px; color: #2563eb; margin-bottom: 8px;"></i>
                                <div style="font-size: 14px; font-weight: 700; color: #1e293b;">Choose file or drag & drop here</div>
                                <div style="font-size: 12px; color: #64748b; margin-top: 4px;">Accepted: PDF, JPG, PNG, DOCX (Max size 7MB)</div>
                            </div>

                            <div class="doc-file-selected-pill" id="any-doc-file-pill" style="display: none; margin-top: 10px; align-items: center; gap: 8px; padding: 8px 12px; background: #eff6ff; border: 1px solid #bfdbfe; border-radius: 8px; font-size: 12.5px; color: #1d4ed8; font-weight: 600;">
                                <i class="fa-solid fa-file-check"></i>
                                <span id="any-doc-file-name" style="flex:1; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;">file.pdf</span>
                                <span id="any-doc-file-size" style="font-size: 11px; opacity: 0.85;"></span>
                            </div>

                            <!-- Expiry date input -->
                            <div id="any-doc-expiry-group" style="margin-top: 16px;">
                                <label style="display: block; font-size: 13px; font-weight: 700; color: #1e293b; margin-bottom: 6px;">
                                    <i class="fa-solid fa-calendar-days" style="color: #2563eb;"></i> Expiry Date <span style="font-weight: 400; color: #64748b;">(Optional — for Passport, Test TRF, or Visa)</span>
                                </label>
                                <input type="date" name="expiry_date" id="any-doc-field-expiry" class="profile-input" style="padding: 10px 12px; width: 100%; border: 1px solid #cbd5e1; border-radius: 8px;">
                            </div>

                            <!-- CRM Sync Banner -->
                            <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 10px; padding: 12px 14px; font-size: 12px; color: #475569; margin-top: 16px;">
                                <div style="font-weight: 700; color: #1e293b; margin-bottom: 4px; display: flex; align-items: center; gap: 6px;">
                                    <i class="fa-solid fa-arrows-rotate" style="color: #2563eb;"></i> Central CRM Auto-Sync:
                                </div>
                                <div>Your uploaded document will immediately synchronize into your Central CRM candidate dossier, update your live documentation progress, and notify your counselor for verification.</div>
                            </div>

                            <div id="any-doc-upload-feedback" style="margin-top: 14px; font-size: 13.5px; display: none;"></div>
                        </div>
                        <div class="doc-modal-header" style="background: #ffffff; border-top: 1px solid #e2e8f0; justify-content: flex-end; gap: 10px; padding: 14px 22px;">
                            <button type="button" class="btn-doc-action btn-doc-view" onclick="closeUploadAnyDocModal()">Cancel</button>
                            <button type="submit" class="btn-doc-action btn-doc-upload" id="btn-any-doc-submit" style="background: #2563eb; color: #ffffff;">
                                <i class="fa-solid fa-cloud-arrow-up"></i> Upload & Sync with CRM
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Document Upload / Re-upload Modal -->
            <div id="doc-upload-modal" class="doc-modal-overlay" style="display: none;" role="dialog" aria-modal="true" aria-labelledby="modal-doc-title">
                <div class="doc-modal-box">
                    <div class="doc-modal-header">
                        <h3 id="modal-doc-title">Upload Document</h3>
                        <button type="button" class="doc-modal-close" onclick="closeDocUploadModal()" aria-label="Close upload modal">&times;</button>
                    </div>
                    <form id="doc-upload-modal-form" enctype="multipart/form-data">
                        <div class="doc-modal-body">
                            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($communityCsrfToken); ?>">
                            <input type="hidden" name="action" value="upload">
                            <input type="hidden" name="doc_key" id="modal-field-doc-key" value="">
                            <input type="hidden" name="category" id="modal-field-category" value="">
                            <input type="hidden" name="doc_label" id="modal-field-doc-label" value="">

                            <!-- Slot Meta Banner -->
                            <div id="modal-slot-meta" style="background:#f8fafc; border:1px solid #e2e8f0; border-radius:12px; padding:14px; margin-bottom:18px;">
                                <div style="font-size:12px; font-weight:700; color:#2563eb; text-transform:uppercase; margin-bottom:4px;" id="modal-slot-category">Stage Category</div>
                                <div style="font-size:15px; font-weight:700; color:#0f172a;" id="modal-slot-name">Document Name</div>
                                <div style="font-size:12.5px; color:#64748b; margin-top:4px;" id="modal-slot-desc">Document requirements</div>
                            </div>

                            <!-- Counselor feedback warning (if re-uploading) -->
                            <div id="modal-feedback-alert" class="doc-counselor-feedback" style="display:none; margin-top:0; margin-bottom:18px;">
                                <div class="doc-feedback-header">
                                    <span><i class="fa-solid fa-triangle-exclamation"></i> Counselor Re-upload Note:</span>
                                </div>
                                <p class="doc-feedback-text" id="modal-feedback-text"></p>
                            </div>

                            <!-- Drag and drop zone -->
                            <div class="doc-dropzone" id="doc-modal-dropzone">
                                <input type="file" name="document" id="doc-modal-file-input" required accept=".pdf,.jpg,.jpeg,.png,.webp,.doc,.docx,.xls,.xlsx" onchange="handleModalFileSelect(this)">
                                <i class="fa-solid fa-cloud-arrow-up doc-dropzone-icon"></i>
                                <div class="doc-dropzone-text">Choose file or drag & drop here</div>
                                <div class="doc-dropzone-sub">Accepted: PDF, JPG, PNG, DOCX (Max size 7MB)</div>
                            </div>

                            <div class="doc-file-selected-pill" id="modal-file-selected-pill">
                                <i class="fa-solid fa-file-check" style="font-size:16px;"></i>
                                <span id="modal-file-selected-name" style="flex:1; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;">file.pdf</span>
                                <span id="modal-file-selected-size" style="font-size:11px; opacity:0.8;"></span>
                            </div>

                            <!-- Expiry date input (shown for Passport / IELTS) -->
                            <div id="modal-expiry-group" style="margin-top: 18px; display: none;">
                                <label style="display:block; font-size:13px; font-weight:700; color:#374151; margin-bottom:6px;">
                                    <i class="fa-solid fa-calendar-days" style="color:#2563eb;"></i> Document Expiry Date <span style="font-weight:400; color:#64748b;">(Required for Passport & Test scores)</span>
                                </label>
                                <input type="date" name="expiry_date" id="modal-field-expiry" class="profile-input" style="padding:10px 14px; width:100%;">
                                <small style="display:block; color:#64748b; font-size:11.5px; margin-top:4px;">
                                    Must be valid at least 6 months past your target intake (<?php echo htmlspecialchars($applicationIntake ?: 'Sep 2026'); ?>).
                                </small>
                            </div>

                            <div id="doc-modal-upload-feedback" style="margin-top:14px; font-size:13.5px; display:none;"></div>
                        </div>
                        <div class="doc-modal-header" style="background:#ffffff; border-top:1px solid #e2e8f0; justify-content:flex-end; gap:10px;">
                            <button type="button" class="btn-doc-action btn-doc-view" onclick="closeDocUploadModal()">Cancel</button>
                            <button type="submit" class="btn-doc-action btn-doc-upload" id="btn-modal-submit">
                                <i class="fa-solid fa-cloud-arrow-up"></i> Upload for Verification
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Document Preview Modal -->
            <div id="doc-preview-modal" class="doc-modal-overlay" style="display: none;" role="dialog" aria-modal="true" aria-labelledby="preview-doc-title">
                <div class="doc-modal-box" style="max-width: 800px; max-height: 90vh; display: flex; flex-direction: column;">
                    <div class="doc-modal-header">
                        <div style="display:flex; align-items:center; gap:10px; overflow:hidden;">
                            <i class="fa-solid fa-file-pdf" style="color:#dc2626; font-size:20px;"></i>
                            <h3 id="preview-doc-title" style="overflow:hidden; text-overflow:ellipsis; white-space:nowrap; max-width:480px;">Document Preview</h3>
                        </div>
                        <div style="display:flex; align-items:center; gap:8px;">
                            <a id="preview-doc-download-btn" href="#" target="_blank" download class="btn-doc-action btn-doc-view" style="font-size:12px; padding:6px 12px;">
                                <i class="fa-solid fa-download"></i> Download
                            </a>
                            <button type="button" class="doc-modal-close" onclick="closeDocPreviewModal()">&times;</button>
                        </div>
                    </div>
                    <div id="preview-doc-body" style="flex:1; padding:20px; overflow:auto; min-height:400px; display:flex; align-items:center; justify-content:center; background:#f8fafc;">
                        <!-- Embed content -->
                    </div>
                </div>
            </div>

            <!-- Counselor Review Simulator Modal -->
            <div id="counselor-sim-modal" class="doc-modal-overlay" style="display: none;" role="dialog" aria-modal="true" aria-labelledby="modal-counselor-title">
                <div class="doc-modal-box" style="max-width: 580px;">
                    <div class="doc-modal-header" style="background: linear-gradient(135deg, #1e3a8a 0%, #2563eb 100%); color: #ffffff;">
                        <div style="display: flex; align-items: center; gap: 10px;">
                            <div style="width: 36px; height: 36px; border-radius: 8px; background: rgba(255,255,255,0.2); display: flex; align-items: center; justify-content: center; font-size: 16px;">
                                <i class="fa-solid fa-user-tie"></i>
                            </div>
                            <div>
                                <h3 id="modal-counselor-title" style="color: #ffffff; margin: 0; font-size: 16px; font-weight: 700;">Counselor Document Verification</h3>
                                <p style="color: rgba(255,255,255,0.8); margin: 0; font-size: 12px;">Dynamic CRM Flow & Verification Engine</p>
                            </div>
                        </div>
                        <button type="button" class="doc-modal-close" onclick="closeCounselorSimulatorModal()" aria-label="Close modal" style="color: #ffffff; opacity: 0.85;">&times;</button>
                    </div>
                    <form id="counselor-sim-form" onsubmit="submitCounselorVerification(event)">
                        <div class="doc-modal-body" style="padding: 22px;">
                            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($communityCsrfToken); ?>">

                            <!-- Document selector -->
                            <div style="margin-bottom: 16px;">
                                <label style="display: block; font-size: 13px; font-weight: 700; color: #1e293b; margin-bottom: 6px;">
                                    Target Document:
                                </label>
                                <select id="counselor-doc-select" name="doc_key" class="profile-input" style="width: 100%; padding: 10px 12px; font-size: 13.5px; border: 1px solid #cbd5e1; border-radius: 8px; background: #fff;" required>
                                    <option value="id_passport">Passport (Front & Back Page)</option>
                                    <option value="test_english">English Language Scorecard (IELTS / PTE / TOEFL)</option>
                                    <option value="academic_10th">10th Standard Marksheet</option>
                                    <option value="academic_12th">12th Standard / Higher Secondary Marksheet</option>
                                    <option value="academic_ug">Consolidated / Semester Undergrad Marksheets</option>
                                    <option value="academic_degree">Degree Certificate / Provisional Certificate</option>
                                    <option value="app_sop">Statement of Purpose (SOP)</option>
                                    <option value="app_cv">Updated CV / Academic Resume</option>
                                    <option value="app_lor_1">Letter of Recommendation 1 (Academic LOR)</option>
                                    <option value="app_lor_2">Letter of Recommendation 2 (Professional LOR)</option>
                                    <option value="id_bank_statement">Proof of Funds / Bank Statement</option>
                                </select>
                            </div>

                            <!-- Counselor Identity -->
                            <div style="margin-bottom: 16px;">
                                <label style="display: block; font-size: 13px; font-weight: 700; color: #1e293b; margin-bottom: 6px;">
                                    Counselor Name:
                                </label>
                                <input type="text" id="counselor-name-input" name="counselor_name" class="profile-input" value="Meghana (Senior Advisor)" style="width: 100%; padding: 10px 12px; font-size: 13.5px; border: 1px solid #cbd5e1; border-radius: 8px;">
                            </div>

                            <!-- Decision Radios -->
                            <div style="margin-bottom: 18px;">
                                <label style="display: block; font-size: 13px; font-weight: 700; color: #1e293b; margin-bottom: 8px;">
                                    Verification Decision:
                                </label>
                                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
                                    <label class="counselor-decision-label" id="decision-label-verified" style="display: flex; align-items: center; gap: 10px; padding: 12px 14px; border: 2px solid #10b981; border-radius: 10px; background: #ecfdf5; cursor: pointer;">
                                        <input type="radio" name="decision" value="Verified" checked onchange="handleDecisionChange(this.value)">
                                        <div>
                                            <div style="font-weight: 700; color: #065f46; font-size: 13.5px;"><i class="fa-solid fa-circle-check" style="color: #10b981;"></i> Mark Verified</div>
                                            <div style="font-size: 11.5px; color: #047857;">Approve & seal doc</div>
                                        </div>
                                    </label>
                                    <label class="counselor-decision-label" id="decision-label-reupload" style="display: flex; align-items: center; gap: 10px; padding: 12px 14px; border: 2px solid #e2e8f0; border-radius: 10px; background: #ffffff; cursor: pointer;">
                                        <input type="radio" name="decision" value="Needs Re-upload" onchange="handleDecisionChange(this.value)">
                                        <div>
                                            <div style="font-weight: 700; color: #991b1b; font-size: 13.5px;"><i class="fa-solid fa-circle-exclamation" style="color: #ef4444;"></i> Needs Re-upload</div>
                                            <div style="font-size: 11.5px; color: #b91c1c;">Flag with exact note</div>
                                        </div>
                                    </label>
                                </div>
                            </div>

                            <!-- Counselor Notes / Feedback -->
                            <div style="margin-bottom: 16px;">
                                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 6px;">
                                    <label style="font-size: 13px; font-weight: 700; color: #1e293b;">
                                        Counselor Feedback / Note:
                                    </label>
                                    <span style="font-size: 11.5px; color: #64748b;">Shown directly to student</span>
                                </div>
                                <textarea id="counselor-notes-input" name="counselor_notes" rows="3" class="profile-input" style="width: 100%; padding: 10px 12px; font-size: 13px; border: 1px solid #cbd5e1; border-radius: 8px; resize: vertical;" placeholder="Add specific guidance or leave blank for default approved note..."></textarea>
                                
                                <!-- Quick Feedback Templates -->
                                <div style="margin-top: 8px;">
                                    <span style="font-size: 11px; font-weight: 600; color: #64748b; text-transform: uppercase;">Quick Templates:</span>
                                    <div style="display: flex; flex-wrap: wrap; gap: 6px; margin-top: 4px;">
                                        <button type="button" class="doc-template-pill" onclick="applyCounselorFeedback('Passport copy is blurred, please upload clear color scan including first and last page with signature.')">Blurred Scan</button>
                                        <button type="button" class="doc-template-pill" onclick="applyCounselorFeedback('IELTS TRF scorecard upload is partially cropped on the right margin. Please upload complete official certificate.')">Cropped Margin</button>
                                        <button type="button" class="doc-template-pill" onclick="applyCounselorFeedback('Bank statement is missing the official bank branch round seal and manager signature on the final page.')">Missing Seal</button>
                                        <button type="button" class="doc-template-pill" onclick="applyCounselorFeedback('All stamps, credentials, and signatures verified. Ready for university dossier submission.')">Verified & Ready</button>
                                    </div>
                                </div>
                            </div>

                            <!-- Dynamic CRM Flow Explanation Card -->
                            <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 10px; padding: 12px 14px; font-size: 12px; color: #475569;">
                                <div style="font-weight: 700; color: #1e293b; margin-bottom: 4px; display: flex; align-items: center; gap: 6px;">
                                    <i class="fa-solid fa-arrows-spin" style="color: #2563eb;"></i> Dynamic CRM Engine Actions:
                                </div>
                                <ul style="margin: 0; padding-left: 18px; line-height: 1.5;">
                                    <li>Updates Central CRM <code>lead_documents</code> & website <code>student_documents</code></li>
                                    <li>Logs structured audit note into CRM <code>leads.remarks</code> & <code>remarks_history</code></li>
                                    <li>Dispatches student notification with counselor feedback note</li>
                                    <li>Updates student dashboard verification status & readiness progress bar dynamically</li>
                                </ul>
                            </div>

                            <div id="counselor-modal-feedback" style="margin-top: 12px; font-size: 13px; display: none;"></div>
                        </div>
                        <div class="doc-modal-header" style="background: #ffffff; border-top: 1px solid #e2e8f0; justify-content: flex-end; gap: 10px; padding: 14px 22px;">
                            <button type="button" class="btn-doc-action btn-doc-view" onclick="closeCounselorSimulatorModal()">Cancel</button>
                            <button type="submit" class="btn-doc-action btn-doc-upload" id="btn-counselor-sim-submit" style="background: #2563eb;">
                                <i class="fa-solid fa-check-double"></i> Save Decision & Trigger Flow
                            </button>
                        </div>
                    </form>
                </div>
            </div>

        </main>
    </div>

    <!-- Floating Back to Top Button -->
    <button type="button" class="btn-scroll-top" id="btnScrollTop" title="Back to top" aria-label="Back to top">
        <i class="fa-solid fa-arrow-up"></i>
    </button>

    <button type="button" class="student-bot-launcher" id="studentBotLauncher" aria-haspopup="dialog" aria-controls="studentBotPanel" aria-expanded="false">
        <i class="fa-regular fa-comment-dots" aria-hidden="true"></i> Messages
    </button>
    <div id="dashboard-toast" class="dashboard-toast" role="status" aria-live="polite" aria-atomic="true" hidden>
        <div class="dashboard-toast-copy" id="dashboard-toast-copy"></div>
        <div class="dashboard-toast-actions" id="dashboard-toast-actions" hidden></div>
        <button type="button" class="dashboard-toast-close" id="dashboard-toast-close" aria-label="Dismiss notification">&times;</button>
    </div>
    <section class="student-bot-panel" id="studentBotPanel" role="dialog" aria-modal="false" aria-labelledby="studentBotTitle" hidden>
        <header class="student-bot-header">
            <div>
                <h2 id="studentBotTitle">Bluestone Student Help</h2>
                <p>Quick answers to common study-abroad questions</p>
            </div>
            <button type="button" class="student-bot-close" id="studentBotClose" aria-label="Close chat">
                <i class="fa-solid fa-xmark" aria-hidden="true"></i>
            </button>
        </header>
        <div class="student-bot-messages" id="studentBotMessages" role="log" aria-live="polite" aria-relevant="additions"></div>
        <div class="student-bot-suggestions" id="studentBotSuggestions" aria-label="Suggested questions">
            <button type="button">How do I apply?</button>
            <button type="button">What documents do I need?</button>
            <button type="button">Tell me about visas</button>
            <button type="button">Scholarships and fees</button>
        </div>
        <form class="student-bot-form" id="studentBotForm">
            <input id="studentBotInput" type="text" maxlength="300" placeholder="Type your question..." aria-label="Ask a study-abroad question" autocomplete="off" required>
            <button type="submit" aria-label="Send message"><i class="fa-solid fa-paper-plane" aria-hidden="true"></i></button>
        </form>
    </section>

    <!-- Course Comparison Floating Dock -->
    <div id="course-compare-dock" class="course-compare-dock" style="display: none;" role="region" aria-label="Course comparison drawer">
        <div class="compare-dock-inner">
            <div class="compare-dock-left">
                <span class="compare-dock-title"><i class="fa-solid fa-code-compare" style="color:#60a5fa;"></i> Comparing:</span>
                <div id="compare-dock-items" class="compare-dock-items">
                    <!-- Populated dynamically via JS -->
                </div>
            </div>
            <div class="compare-dock-actions">
                <button type="button" class="btn-compare-dock-clear" onclick="clearCourseComparison()">Clear</button>
                <button type="button" class="btn-compare-dock-launch" id="btn-compare-dock-launch" onclick="openCompareModal()">
                    <i class="fa-solid fa-table-columns"></i> Compare Now (<span id="compare-dock-count">0</span>/3)
                </button>
            </div>
        </div>
    </div>

    <!-- Course Comparison Modal Dialog -->
    <div id="course-compare-modal" class="course-compare-modal" role="dialog" aria-modal="true" style="display: none;">
        <div class="compare-modal-backdrop" onclick="closeCompareModal()"></div>
        <div class="compare-modal-content">
            <div class="compare-modal-header">
                <div>
                    <h2><i class="fa-solid fa-code-compare" style="color:#2563eb;"></i> Side-by-Side Course Comparison</h2>
                    <p>Compare universities, annual tuition, language requirements, visa rights, and estimated expenses.</p>
                </div>
                <div style="display:flex; align-items:center; gap:10px;">
                    <button type="button" class="btn-compare-print" onclick="window.print()"><i class="fa-solid fa-print"></i> Print Comparison</button>
                    <button type="button" class="compare-modal-close" onclick="closeCompareModal()" aria-label="Close modal">&times;</button>
                </div>
            </div>
            <div class="compare-modal-body" id="compare-modal-table-wrap">
                <!-- Injected via JavaScript -->
            </div>
        </div>
    </div>

    <?php 
        $hide_floating_widgets = true;
        require_once 'includes/footer.php'; 
    ?>
    <script>
        window.showDashboardToast = (message, options) => {
            const toast = document.getElementById('dashboard-toast');
            const copy = document.getElementById('dashboard-toast-copy');
            const actions = document.getElementById('dashboard-toast-actions');
            const closeButton = document.getElementById('dashboard-toast-close');
            if (!toast || !copy || !actions || !closeButton) return Promise.resolve(false);

            const settings = options || {};
            if (toast.dismissToast) toast.dismissToast(false);
            window.clearTimeout(toast.toastTimer);
            toast.dataset.type = settings.type || 'info';
            copy.textContent = String(message);
            actions.replaceChildren();
            actions.hidden = !settings.confirm;
            toast.hidden = false;
            window.requestAnimationFrame(() => toast.classList.add('is-visible'));

            let resolveToast;
            const result = settings.confirm
                ? new Promise(resolve => { resolveToast = resolve; })
                : Promise.resolve(false);
            let dismissed = false;
            toast.dismissToast = confirmed => {
                if (dismissed) return;
                dismissed = true;
                window.clearTimeout(toast.toastTimer);
                toast.classList.remove('is-visible');
                if (resolveToast) resolveToast(Boolean(confirmed));
                window.setTimeout(() => {
                    if (!toast.classList.contains('is-visible')) toast.hidden = true;
                }, 220);
            };

            closeButton.onclick = () => toast.dismissToast(false);
            if (settings.confirm) {
                const cancelButton = document.createElement('button');
                cancelButton.type = 'button';
                cancelButton.textContent = 'Cancel';
                cancelButton.addEventListener('click', () => toast.dismissToast(false));
                const confirmButton = document.createElement('button');
                confirmButton.type = 'button';
                confirmButton.className = 'dashboard-toast-confirm';
                confirmButton.textContent = settings.confirmLabel || 'Continue';
                confirmButton.addEventListener('click', () => toast.dismissToast(true));
                actions.append(cancelButton, confirmButton);
                confirmButton.focus();
                toast.toastTimer = window.setTimeout(() => toast.dismissToast(false), 20000);
            } else {
                toast.toastTimer = window.setTimeout(() => toast.dismissToast(false), settings.duration || 4500);
            }

            return result;
        };
    </script>
    <script>
        (function () {
            const launcher = document.getElementById('studentBotLauncher');
            const panel = document.getElementById('studentBotPanel');
            const closeButton = document.getElementById('studentBotClose');
            const messages = document.getElementById('studentBotMessages');
            const suggestions = document.getElementById('studentBotSuggestions');
            const form = document.getElementById('studentBotForm');
            const input = document.getElementById('studentBotInput');
            if (!launcher || !panel || !closeButton || !messages || !suggestions || !form || !input) return;

            const answers = [
                {
                    keywords: ['status', 'progress', 'application status', 'offer'],
                    answer: 'You can check your application progress in the Application section of your dashboard. If a status is missing or you need details about an offer, contact your counsellor.'
                },
                {
                    keywords: ['apply', 'application', 'admission', 'start'],
                    answer: 'Start by choosing a country, course and universities that fit your goals. A counsellor can review your profile, guide you through the application and help you track next steps. You can also book a free consultation.'
                },
                {
                    keywords: ['document', 'paperwork', 'passport', 'transcript', 'sop', 'resume', 'cv'],
                    answer: 'Common documents include academic transcripts and certificates, a CV or resume, and a passport if available. English-test results and work-experience documents may also be useful when applicable. Requirements vary by university and course; use My Documents to upload files or ask a counsellor to check your list.'
                },
                {
                    keywords: ['ielts', 'pte', 'toefl', 'english test', 'language score', 'language test'],
                    answer: 'English-test requirements depend on the country, university and course. IELTS, PTE or TOEFL may be accepted, but the required test and score vary. Check the specific university requirement with a counsellor before booking a test.'
                },
                {
                    keywords: ['visa', 'study permit', 'immigration'],
                    answer: 'Visa or study-permit requirements depend on your destination and personal circumstances. Our counsellors can help you understand the document and interview-preparation steps. Always confirm current rules with the official immigration website for your destination; visa decisions are made by the relevant authorities.'
                },
                {
                    keywords: ['scholarship', 'fee', 'tuition', 'fund', 'loan', 'finance', 'cost'],
                    answer: 'Tuition, living costs, scholarships and loan options vary by destination, institution, course and your profile. Shortlist your preferred universities first, then ask a counsellor to help review current costs and funding options.'
                },
                {
                    keywords: ['accommodation', 'housing', 'stay', 'travel'],
                    answer: 'Accommodation options and costs vary by city and university. Once you have a destination and intake in mind, a counsellor can guide you on planning and the options available near your campus.'
                },
                {
                    keywords: ['counsellor', 'counselor', 'contact', 'consultation', 'speak', 'person', 'help'],
                    answer: 'You can book a free consultation with our counselling team or send us a message through the contact page.'
                }
            ];
            let welcomed = false;

            function addMessage(text, sender) {
                const message = document.createElement('div');
                message.className = 'student-bot-message ' + sender;
                message.textContent = text;
                messages.appendChild(message);
                messages.scrollTop = messages.scrollHeight;
            }

            function openChat() {
                panel.hidden = false;
                launcher.setAttribute('aria-expanded', 'true');
                if (!welcomed) {
                    addMessage('Hi! I can help with applications, documents, English tests, visas, scholarships and more. Choose a topic below or type your question.', 'bot');
                    welcomed = true;
                }
                input.focus();
            }

            function closeChat() {
                panel.hidden = true;
                launcher.setAttribute('aria-expanded', 'false');
                launcher.focus();
            }

            function answerQuestion(question) {
                const normalizedQuestion = question.toLowerCase();
                const match = answers.find(item => item.keywords.some(keyword => normalizedQuestion.includes(keyword)));
                const answer = match
                    ? match.answer
                    : "I don't have a reliable answer for that yet. Try asking about applications, documents, English tests, visas, scholarships or accommodation, or contact a counsellor for personal guidance.";

                addMessage(question, 'student');
                addMessage(answer, 'bot');
                if (!match || normalizedQuestion.includes('counsellor') || normalizedQuestion.includes('counselor') || normalizedQuestion.includes('contact')) {
                    const help = document.createElement('div');
                    help.className = 'student-bot-message bot';
                    const consultationLink = document.createElement('a');
                    consultationLink.href = 'consultation.php';
                    consultationLink.textContent = 'Book a free consultation';
                    const contactLink = document.createElement('a');
                    contactLink.href = 'contact.php';
                    contactLink.textContent = 'Contact us';
                    help.append(consultationLink, document.createTextNode(' · '), contactLink);
                    messages.appendChild(help);
                    messages.scrollTop = messages.scrollHeight;
                }
            }

            launcher.addEventListener('click', openChat);
            closeButton.addEventListener('click', closeChat);
            panel.addEventListener('keydown', event => {
                if (event.key === 'Escape') closeChat();
            });
            suggestions.addEventListener('click', event => {
                const button = event.target.closest('button');
                if (button) answerQuestion(button.textContent.trim());
            });
            form.addEventListener('submit', event => {
                event.preventDefault();
                const question = input.value.trim();
                if (!question) return;
                answerQuestion(question);
                input.value = '';
                input.focus();
            });
        })();
    </script>
    <script>
        // Disable automatic browser scroll restoration so SPA tabs always start from the top
        if ('scrollRestoration' in history) {
            history.scrollRestoration = 'manual';
        }

        // ─── Centralized Dashboard Tab Switching with Scroll-to-Top ───
        function switchDashboardTab(targetId, updateHistory) {
            if (updateHistory === undefined) updateHistory = true;
            if (!targetId) return;
            
            // Normalize targetId (e.g. 'courses' -> 'view-courses')
            if (!targetId.startsWith('view-')) {
                targetId = 'view-' + targetId;
            }
            const targetEl = document.getElementById(targetId);
            if (!targetEl) return;

            // Blur any focused element (prevent browser from anchoring back to clicked button)
            if (document.activeElement && typeof document.activeElement.blur === 'function') {
                document.activeElement.blur();
            }
            
            // Immediately jump window and document scroll to top before swapping views
            window.scrollTo({ top: 0, left: 0, behavior: 'instant' });
            document.documentElement.scrollTop = 0;
            document.body.scrollTop = 0;

            // Nav active state
            document.querySelectorAll('.nav-item').forEach(nav => {
                if (nav.getAttribute('data-target') === targetId) {
                    nav.classList.add('active');
                } else {
                    nav.classList.remove('active');
                }
            });
            
            // View switching
            document.querySelectorAll('.tab-view').forEach(view => view.classList.remove('active-view'));
            targetEl.classList.add('active-view');
            document.body.classList.remove('community-inbox-open');
            
            // Comprehensive scroll reset across window, document, and containers to start screen (0, 0)
            const forceScrollToTop = () => {
                window.scrollTo({ top: 0, left: 0, behavior: 'instant' });
                document.documentElement.scrollTop = 0;
                document.body.scrollTop = 0;
                const contentArea = document.querySelector('.content-area');
                if (contentArea) contentArea.scrollTop = 0;
                const mainContainer = document.querySelector('.main-container');
                if (mainContainer) mainContainer.scrollTop = 0;
                if (targetEl) targetEl.scrollTop = 0;
            };

            forceScrollToTop();
            requestAnimationFrame(forceScrollToTop);
            setTimeout(forceScrollToTop, 10);
            setTimeout(forceScrollToTop, 50);
            setTimeout(forceScrollToTop, 150);
            setTimeout(forceScrollToTop, 300);
            
            // Update browser URL query string without full page reload
            const tabSlug = targetId.replace('view-', '');
            if (updateHistory && window.history && window.history.pushState) {
                try {
                    const newUrl = new URL(window.location.href);
                    newUrl.searchParams.set('tab', tabSlug);
                    window.history.pushState({ tab: tabSlug }, '', newUrl.toString());
                } catch (e) {}
            }
            
            // Trigger tab-specific refresh if needed
            if (tabSlug === 'documents' && typeof loadDocuments === 'function') {
                loadDocuments();
            } else if (tabSlug === 'shortlisted' && typeof loadShortlistView === 'function') {
                loadShortlistView();
            }
        }
        window.switchDashboardTab = switchDashboardTab;

        // Main Navigation Switching
        document.querySelectorAll('.nav-item').forEach(item => {
            item.addEventListener('click', function(e) {
                const targetId = this.getAttribute('data-target');
                if (targetId) {
                    e.preventDefault();
                    switchDashboardTab(targetId, true);
                }
            });
        });

        // Browser Back/Forward navigation support
        window.addEventListener('popstate', function(e) {
            const params = new URLSearchParams(window.location.search);
            const tab = params.get('tab') || 'dashboard';
            switchDashboardTab('view-' + tab, false);
        });

        // Profile Sub-tabs Switching
        document.querySelectorAll('.profile-tab').forEach(item => {
            item.addEventListener('click', function() {
                const targetId = this.getAttribute('data-ptab');
                if (targetId) {
                    // Update tab active state
                    document.querySelectorAll('.profile-tab').forEach(tab => tab.classList.remove('active'));
                    this.classList.add('active');
                    
                    // Update content visibility
                    document.querySelectorAll('.profile-tab-content').forEach(content => content.classList.remove('active-content'));
                    document.getElementById(targetId).classList.add('active-content');

                    // Scroll up to profile header if scrolled down
                    const profileView = document.getElementById('view-profile');
                    if (profileView) {
                        const topPos = profileView.getBoundingClientRect().top + window.pageYOffset - 80;
                        if (window.pageYOffset > topPos) {
                            window.scrollTo({ top: Math.max(0, topPos), behavior: 'smooth' });
                        }
                    }
                }
            });
        });

        // Floating Scroll-to-Top Button Handler
        const btnScrollTop = document.getElementById('btnScrollTop');
        if (btnScrollTop) {
            window.addEventListener('scroll', function() {
                if (window.pageYOffset > 300) {
                    btnScrollTop.classList.add('visible');
                } else {
                    btnScrollTop.classList.remove('visible');
                }
            }, { passive: true });

            btnScrollTop.addEventListener('click', function() {
                window.scrollTo({ top: 0, behavior: 'smooth' });
            });
        }

        // Profile Edit Mode Toggle
        const btnEditProfile = document.getElementById('btn-edit-profile');
        const btnCancelEdit = document.getElementById('btn-cancel-edit');
        const profileDisplay = document.getElementById('profile-display-view');
        const profileEdit = document.getElementById('profile-edit-view');

        if(btnEditProfile && btnCancelEdit && profileDisplay && profileEdit) {
            btnEditProfile.addEventListener('click', () => {
                profileDisplay.classList.add('hidden-display');
                profileEdit.classList.add('active-edit');
                btnEditProfile.style.display = 'none';
            });
            
            btnCancelEdit.addEventListener('click', () => {
                profileDisplay.classList.remove('hidden-display');
                profileEdit.classList.remove('active-edit');
                btnEditProfile.style.display = 'block';
            });
        }

        // Interests Edit Mode Toggle
        const btnEditInterests = document.getElementById('btn-edit-interests');
        const btnCancelEditInterests = document.getElementById('btn-cancel-edit-interests');
        const interestsDisplay = document.getElementById('interests-display-view');
        const interestsEdit = document.getElementById('interests-edit-view');

        if(btnEditInterests && btnCancelEditInterests && interestsDisplay && interestsEdit) {
            btnEditInterests.addEventListener('click', () => {
                interestsDisplay.classList.add('hidden-display');
                interestsEdit.classList.add('active-edit');
                btnEditInterests.style.display = 'none';
            });
            
            btnCancelEditInterests.addEventListener('click', () => {
                interestsDisplay.classList.remove('hidden-display');
                interestsEdit.classList.remove('active-edit');
                btnEditInterests.style.display = 'block';
            });
        }

        // Community Sub-tabs Switching
        document.querySelectorAll('.comm-tab-btn').forEach(item => {
            item.addEventListener('click', function() {
                const targetId = this.getAttribute('data-comm-target');
                if (targetId) {
                    document.querySelectorAll('.comm-tab-btn').forEach(tab => tab.classList.remove('active'));
                    this.classList.add('active');
                    
                    document.querySelectorAll('.comm-sub-view').forEach(content => content.classList.remove('active-sub-view'));
                    document.getElementById(targetId).classList.add('active-sub-view');
                    document.body.classList.toggle('community-inbox-open', targetId === 'comm-inbox');

                    // If user was scrolled down, scroll smoothly back up to community tabs
                    const commView = document.getElementById('view-community');
                    if (commView) {
                        const topPos = commView.getBoundingClientRect().top + window.pageYOffset - 80;
                        if (window.pageYOffset > topPos) {
                            window.scrollTo({ top: Math.max(0, topPos), behavior: 'smooth' });
                        }
                    }
                }
            });
        });
        const requestedCommunityTab = new URLSearchParams(window.location.search).get('community_tab');
        if (['friends', 'inbox', 'groups', 'dreamwall'].includes(requestedCommunityTab)) {
            const requestedTabButton = document.querySelector('.comm-tab-btn[data-comm-target="comm-' + requestedCommunityTab + '"]');
            if (requestedTabButton) requestedTabButton.click();
        }

        const dreamwallGrid = document.getElementById('dreamwall-grid');
        if (dreamwallGrid) {
            const dreamwallFeedback = document.getElementById('dreamwall-feedback');
            const dreamwallFilterButtons = Array.from(document.querySelectorAll('[data-dreamwall-type]'));
            const dreamwallShareModal = document.getElementById('dreamwall-share-modal');
            const dreamwallShareForm = document.getElementById('dreamwall-share-form');
            const dreamwallShareFeedback = document.getElementById('dreamwall-share-feedback');
            const dreamwallCsrfToken = <?php echo json_encode($communityCsrfToken); ?>;
            let currentDreamwallType = 'All';
            let dreamwallLoaded = false;
            let dreamwallLoadVersion = 0;

            function setDreamwallFeedback(message, isError) {
                dreamwallFeedback.textContent = message || '';
                dreamwallFeedback.classList.toggle('error', Boolean(isError));
            }

            async function dreamwallRequest(path, options) {
                const requestOptions = Object.assign({ credentials: 'same-origin' }, options || {});
                const requestUrl = new URL(path, window.location.origin);
                let apiUrl = 'student-dreamwall-api.php';
                if (requestOptions.method === 'POST') {
                    if (requestOptions.body instanceof FormData) {
                        requestOptions.body.set('action', 'create');
                        requestOptions.body.set('csrf_token', dreamwallCsrfToken);
                        if (requestOptions.headers) delete requestOptions.headers['Content-Type'];
                    } else {
                        let payload = {};
                        if (typeof requestOptions.body === 'string') {
                            try {
                                payload = JSON.parse(requestOptions.body);
                            } catch (error) {
                                throw new Error('The milestone request could not be prepared.');
                            }
                        }
                        const cheerMatch = requestUrl.pathname.match(/\/community\/dreamwall\/(\d+)\/cheer$/);
                        payload.action = cheerMatch ? 'cheer' : 'create';
                        payload.csrf_token = dreamwallCsrfToken;
                        if (cheerMatch) payload.milestone_id = Number(cheerMatch[1]);
                        requestOptions.body = JSON.stringify(payload);
                        requestOptions.headers = Object.assign({}, requestOptions.headers || {}, {
                            'Content-Type': 'application/json'
                        });
                    }
                } else {
                    const query = new URLSearchParams(requestUrl.search);
                    query.set('action', 'list');
                    query.delete('student_id');
                    apiUrl += '?' + query.toString();
                }
                const response = await fetch(apiUrl, requestOptions);
                const responseText = await response.text();
                let result = null;
                if (responseText) {
                    try {
                        result = JSON.parse(responseText);
                    } catch (error) {
                        throw new Error('The Dreamwall service returned an invalid response. Please try again later.');
                    }
                }
                if (!response.ok) {
                    throw new Error((result && result.message) || 'The Dreamwall service could not complete your request.');
                }
                return result;
            }

            function safeDreamwallUrl(value) {
                if (typeof value !== 'string' || value.trim() === '') return '';
                try {
                    const url = new URL(value, window.location.href);
                    return ['http:', 'https:'].includes(url.protocol) ? url.href : '';
                } catch (error) {
                    return '';
                }
            }

            function makeDreamwallElement(tagName, className, text) {
                const element = document.createElement(tagName);
                if (className) element.className = className;
                if (typeof text === 'string') element.textContent = text;
                return element;
            }

            function createDreamwallCard(milestone) {
                const card = makeDreamwallElement('article', 'dreamwall-card');
                const header = makeDreamwallElement('div', 'dreamwall-card-top');
                const avatar = makeDreamwallElement('div', 'dreamwall-avatar');
                const studentName = String(milestone.student_name || 'Bluestone Student');
                const initials = studentName.trim().split(/\s+/).slice(0, 2).map(part => part.charAt(0).toUpperCase()).join('') || 'BS';
                const avatarUrl = safeDreamwallUrl(milestone.student_avatar);
                if (avatarUrl) {
                    const image = document.createElement('img');
                    image.src = avatarUrl;
                    image.alt = '';
                    image.onerror = () => { avatar.textContent = initials; };
                    avatar.appendChild(image);
                } else {
                    avatar.textContent = initials;
                }

                const identity = makeDreamwallElement('div', 'dreamwall-student');
                identity.appendChild(makeDreamwallElement('strong', '', studentName));
                const destination = [milestone.country_flag, milestone.university_name, milestone.intake]
                    .filter(value => typeof value === 'string' && value.trim() !== '')
                    .join(' ');
                identity.appendChild(makeDreamwallElement('div', 'dreamwall-destination', destination || 'Student achievement'));
                header.append(avatar, identity);

                const type = String(milestone.milestone_type || 'Milestone');
                const normalizedType = type.toLowerCase();
                const badge = makeDreamwallElement('span', 'dreamwall-badge', type);
                badge.dataset.type = normalizedType;

                const content = makeDreamwallElement('div', 'dreamwall-card-content');
                content.appendChild(makeDreamwallElement('h4', '', String(milestone.title || 'A student milestone')));
                if (milestone.story) content.appendChild(makeDreamwallElement('p', '', String(milestone.story)));
                const proofUrl = safeDreamwallUrl(milestone.proof_image_url);
                if (proofUrl) {
                    const proof = makeDreamwallElement('a', 'dreamwall-proof');
                    proof.href = proofUrl;
                    proof.target = '_blank';
                    proof.rel = 'noopener noreferrer';
                    const proofImage = document.createElement('img');
                    proofImage.className = 'dreamwall-proof-image';
                    proofImage.src = proofUrl;
                    proofImage.alt = 'Achievement proof shared by ' + studentName;
                    proofImage.loading = 'lazy';
                    proof.appendChild(proofImage);
                    content.appendChild(proof);
                }

                const actions = makeDreamwallElement('div', 'dreamwall-card-actions');
                const isCheered = milestone.is_cheered === true || Number(milestone.is_cheered) === 1;
                const cheerCount = Number.isFinite(Number(milestone.cheer_count)) ? Math.max(0, Number(milestone.cheer_count)) : 0;
                const cheerButton = makeDreamwallElement('button', 'dreamwall-cheer', (isCheered ? '👏 Cheered! ' : '👏 Cheer ') + cheerCount);
                cheerButton.type = 'button';
                cheerButton.dataset.dreamwallCheer = String(milestone.id);
                cheerButton.dataset.cheerCount = String(cheerCount);
                cheerButton.setAttribute('aria-pressed', String(isCheered));

                actions.appendChild(cheerButton);
                card.append(header, badge, content, actions);
                return card;
            }

            async function loadDreamwall() {
                const loadVersion = ++dreamwallLoadVersion;
                setDreamwallFeedback('Loading student milestones...', false);
                dreamwallGrid.replaceChildren();
                try {
                    const query = new URLSearchParams({
                        type: currentDreamwallType,
                        country: 'All'
                    });
                    const milestones = await dreamwallRequest('/community/dreamwall?' + query.toString());
                    if (loadVersion !== dreamwallLoadVersion) return false;
                    if (!Array.isArray(milestones)) {
                        throw new Error('The Dreamwall service returned an unexpected milestones format.');
                    }
                    if (milestones.length === 0) {
                        setDreamwallFeedback('No milestones found in this category yet. Be the first to share yours!', false);
                    } else {
                        milestones.forEach(milestone => dreamwallGrid.appendChild(createDreamwallCard(milestone)));
                        setDreamwallFeedback('', false);
                    }
                    dreamwallLoaded = true;
                    return true;
                } catch (error) {
                    if (loadVersion !== dreamwallLoadVersion) return false;
                    setDreamwallFeedback(error.message, true);
                    const retry = makeDreamwallElement('button', 'dreamwall-filter', 'Try again');
                    retry.type = 'button';
                    retry.addEventListener('click', loadDreamwall);
                    dreamwallGrid.appendChild(retry);
                    return false;
                } finally {
                    if (loadVersion === dreamwallLoadVersion) dreamwallLoaded = true;
                }
            }

            function openDreamwallModal(modal) {
                modal.hidden = false;
                const firstField = modal.querySelector('input, select, textarea');
                if (firstField) firstField.focus();
            }

            function closeDreamwallModal(modal) {
                modal.hidden = true;
            }

            document.getElementById('dreamwall-share-open').addEventListener('click', () => {
                dreamwallShareFeedback.textContent = '';
                dreamwallShareFeedback.classList.remove('error', 'success');
                openDreamwallModal(dreamwallShareModal);
            });
            document.querySelectorAll('[data-dreamwall-close]').forEach(button => {
                button.addEventListener('click', () => closeDreamwallModal(button.closest('.dreamwall-modal')));
            });
            dreamwallShareModal.addEventListener('click', event => {
                if (event.target === dreamwallShareModal) closeDreamwallModal(dreamwallShareModal);
            });
            document.addEventListener('keydown', event => {
                if (event.key === 'Escape' && !dreamwallShareModal.hidden) closeDreamwallModal(dreamwallShareModal);
            });

            dreamwallFilterButtons.forEach(button => {
                button.addEventListener('click', () => {
                    currentDreamwallType = button.dataset.dreamwallType;
                    dreamwallFilterButtons.forEach(filter => {
                        const selected = filter === button;
                        filter.classList.toggle('active', selected);
                        filter.setAttribute('aria-pressed', String(selected));
                    });
                    loadDreamwall();
                });
            });
            document.querySelector('[data-comm-target="comm-dreamwall"]').addEventListener('click', () => {
                if (!dreamwallLoaded) loadDreamwall();
            });
            if (requestedCommunityTab === 'dreamwall') loadDreamwall();

            dreamwallGrid.addEventListener('click', async event => {
                const cheerButton = event.target.closest('[data-dreamwall-cheer]');
                if (cheerButton) {
                    const milestoneId = Number(cheerButton.dataset.dreamwallCheer);
                    if (!Number.isInteger(milestoneId) || milestoneId < 1 || cheerButton.disabled) return;
                    const wasCheered = cheerButton.getAttribute('aria-pressed') === 'true';
                    const previousCount = Number(cheerButton.dataset.cheerCount);
                    const optimisticCount = Math.max(0, previousCount + (wasCheered ? -1 : 1));
                    cheerButton.disabled = true;
                    cheerButton.setAttribute('aria-pressed', String(!wasCheered));
                    cheerButton.dataset.cheerCount = String(optimisticCount);
                    cheerButton.textContent = (wasCheered ? '👏 Cheer ' : '👏 Cheered! ') + optimisticCount;
                    try {
                        const result = await dreamwallRequest('/community/dreamwall/' + milestoneId + '/cheer', {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/json' },
                            body: JSON.stringify({})
                        });
                        if (!result || typeof result.is_cheered !== 'boolean' || !Number.isFinite(Number(result.cheer_count))) {
                            throw new Error('The Dreamwall service returned an unexpected cheer response.');
                        }
                        const confirmedCount = Math.max(0, Number(result.cheer_count));
                        cheerButton.setAttribute('aria-pressed', String(result.is_cheered));
                        cheerButton.dataset.cheerCount = String(confirmedCount);
                        cheerButton.textContent = (result.is_cheered ? '👏 Cheered! ' : '👏 Cheer ') + confirmedCount;
                    } catch (error) {
                        cheerButton.setAttribute('aria-pressed', String(wasCheered));
                        cheerButton.dataset.cheerCount = String(previousCount);
                        cheerButton.textContent = (wasCheered ? '👏 Cheered! ' : '👏 Cheer ') + previousCount;
                        setDreamwallFeedback(error.message, true);
                    } finally {
                        cheerButton.disabled = false;
                    }
                }
            });

            dreamwallShareForm.addEventListener('submit', async event => {
                event.preventDefault();
                const formData = new FormData(dreamwallShareForm);
                const countrySelect = document.getElementById('dreamwall-country');
                const countryOption = countrySelect.options[countrySelect.selectedIndex];
                formData.set('country_flag', countryOption ? countryOption.dataset.flag || '🌍' : '🌍');
                const submitButton = dreamwallShareForm.querySelector('[type="submit"]');
                submitButton.disabled = true;
                dreamwallShareFeedback.textContent = '';
                dreamwallShareFeedback.classList.remove('error', 'success');
                try {
                    const result = await dreamwallRequest('/community/dreamwall', {
                        method: 'POST',
                        body: formData
                    });
                    if (!result || result.success !== true) {
                        throw new Error((result && result.message) || 'Your milestone could not be shared. Please try again.');
                    }
                    closeDreamwallModal(dreamwallShareModal);
                    dreamwallShareForm.reset();
                    currentDreamwallType = 'All';
                    dreamwallFilterButtons.forEach(filter => {
                        const selected = filter.dataset.dreamwallType === 'All';
                        filter.classList.toggle('active', selected);
                        filter.setAttribute('aria-pressed', String(selected));
                    });
                    const refreshed = await loadDreamwall();
                    if (refreshed) setDreamwallFeedback('Your milestone was submitted for admin review. It will appear on the Dreamwall after approval.', false);
                } catch (error) {
                    dreamwallShareFeedback.textContent = error.message;
                    dreamwallShareFeedback.classList.add('error');
                } finally {
                    submitButton.disabled = false;
                }
            });

        }

        const communitySearch = document.getElementById('community-search');
        const communityNoResults = document.getElementById('community-no-results');
        const communityEmptyState = document.getElementById('community-empty-state');
        if (communitySearch) {
            communitySearch.addEventListener('input', () => {
                const query = communitySearch.value.trim().toLocaleLowerCase();
                let visiblePosts = 0;
                document.querySelectorAll('[data-community-post]').forEach(post => {
                    const matches = (post.dataset.search || '').includes(query);
                    post.hidden = !matches;
                    if (matches) visiblePosts++;
                });
                if (communityNoResults) {
                    communityNoResults.style.display = query !== '' && visiblePosts === 0 ? 'block' : 'none';
                }
                if (communityEmptyState) {
                    communityEmptyState.style.display = query === '' ? '' : 'none';
                }
            });
        }

        const communityPostForm = document.getElementById('community-post-form');
        if (communityPostForm) {
            const mediaInput = communityPostForm.querySelector('input[name="media"]');
            const imagePreview = communityPostForm.querySelector('img.community-media-preview');
            const videoPreview = communityPostForm.querySelector('video.community-media-preview');
            const feedback = communityPostForm.querySelector('.community-feedback');
            const submitButton = communityPostForm.querySelector('.community-submit');
            let previewUrl = '';

            async function readCommunityPostResponse(response) {
                const responseText = await response.text();
                let result;

                try {
                    result = JSON.parse(responseText);
                } catch (error) {
                    const status = response.status;
                    if (status === 404) {
                        throw new Error('The Community post service was not found. Make sure student-community-post.php was uploaded to the live site.');
                    }
                    if (status === 413) {
                        throw new Error('The upload exceeds the hosting server size limit. Try a smaller image or video.');
                    }
                    if (status === 500 || status === 503) {
                        throw new Error('The server could not process this post. Please check the live PHP error log or contact the site administrator.');
                    }
                    throw new Error('The server returned an unexpected response (HTTP ' + status + '). Please contact the site administrator.');
                }

                return result;
            }

            mediaInput.addEventListener('change', () => {
                if (previewUrl) URL.revokeObjectURL(previewUrl);
                imagePreview.style.display = 'none';
                videoPreview.style.display = 'none';
                imagePreview.removeAttribute('src');
                videoPreview.removeAttribute('src');
                if (!mediaInput.files || !mediaInput.files[0]) return;
                previewUrl = URL.createObjectURL(mediaInput.files[0]);
                if (
                    mediaInput.files[0].type.startsWith('video/') ||
                    /\.(mp4|webm|mov)$/i.test(mediaInput.files[0].name)
                ) {
                    videoPreview.src = previewUrl;
                    videoPreview.style.display = 'block';
                } else {
                    imagePreview.src = previewUrl;
                    imagePreview.style.display = 'block';
                }
            });

            communityPostForm.addEventListener('submit', async event => {
                event.preventDefault();
                feedback.textContent = '';
                feedback.classList.remove('error', 'success');
                submitButton.disabled = true;
                submitButton.textContent = 'Sharing...';
                try {
                    const response = await fetch('student-community-post.php', {
                        method: 'POST',
                        body: new FormData(communityPostForm),
                        credentials: 'same-origin'
                    });
                    const result = await readCommunityPostResponse(response);
                    if (!response.ok || !result.success) {
                        throw new Error(result.message || 'Unable to share your post.');
                    }
                    window.location.href = 'student-dashboard.php?tab=community&post_shared=1';
                } catch (error) {
                    feedback.textContent = error.message || 'Unable to share your post. Please try again.';
                    feedback.classList.add('error');
                    submitButton.disabled = false;
                    submitButton.textContent = 'Share post';
                }
            });
        }

        const communityFeed = document.querySelector('#comm-feed .comm-feed-grid');
        if (communityFeed) {
            communityFeed.addEventListener('click', async event => {
                const editButton = event.target.closest('[data-post-edit]');
                const cancelButton = event.target.closest('[data-post-edit-cancel]');
                const deleteButton = event.target.closest('[data-post-delete]');

                if (editButton) {
                    const card = editButton.closest('[data-community-post]');
                    const editForm = card.querySelector('.comm-post-edit-form');
                    const postMenu = editButton.closest('details');
                    if (editForm) editForm.hidden = false;
                    if (postMenu) postMenu.open = false;
                    return;
                }

                if (cancelButton) {
                    const editForm = cancelButton.closest('.comm-post-edit-form');
                    if (editForm) {
                        editForm.hidden = true;
                        editForm.querySelector('.community-feedback').textContent = '';
                        editForm.querySelector('.community-feedback').classList.remove('error', 'success');
                    }
                    return;
                }

                if (!deleteButton) return;
                if (!await window.showDashboardToast('Delete this post? This cannot be undone.', { confirm: true, type: 'warning', confirmLabel: 'Delete' })) return;

                const card = deleteButton.closest('[data-community-post]');
                const menuDetails = deleteButton.closest('details');
                const deleteButtons = menuDetails ? menuDetails.querySelectorAll('button') : [];
                deleteButtons.forEach(button => button.disabled = true);
                const deleteData = new FormData();
                deleteData.append('action', 'delete');
                deleteData.append('post_id', card.querySelector('[name="post_id"]').value);
                deleteData.append('csrf_token', communityPostForm.querySelector('[name="csrf_token"]').value);

                try {
                    const response = await fetch('student-community-post.php', {
                        method: 'POST',
                        body: deleteData,
                        credentials: 'same-origin'
                    });
                    const result = await readCommunityPostResponse(response);
                    if (!response.ok || !result.success) {
                        throw new Error(result.message || 'Unable to delete your post.');
                    }
                    window.location.href = 'student-dashboard.php?tab=community&post_deleted=1';
                } catch (error) {
                    window.showDashboardToast(error.message || 'Unable to delete your post. Please try again.', { type: 'error' });
                    deleteButtons.forEach(button => button.disabled = false);
                }
            });

            communityFeed.addEventListener('submit', async event => {
                const editForm = event.target.closest('.comm-post-edit-form');
                if (!editForm) return;
                event.preventDefault();
                const submitButton = editForm.querySelector('[type="submit"]');
                const feedback = editForm.querySelector('.community-feedback');
                feedback.textContent = '';
                feedback.classList.remove('error', 'success');
                submitButton.disabled = true;
                submitButton.textContent = 'Saving...';
                try {
                    const response = await fetch('student-community-post.php', {
                        method: 'POST',
                        body: new FormData(editForm),
                        credentials: 'same-origin'
                    });
                    const result = await readCommunityPostResponse(response);
                    if (!response.ok || !result.success) {
                        throw new Error(result.message || 'Unable to update your post.');
                    }
                    window.location.href = 'student-dashboard.php?tab=community&post_updated=1';
                } catch (error) {
                    feedback.textContent = error.message || 'Unable to update your post. Please try again.';
                    feedback.classList.add('error');
                    submitButton.disabled = false;
                    submitButton.textContent = 'Save changes';
                }
            });
        }
    </script>

    <!-- Shortlist JS Logic (XSS-safe, server-synced) -->
    <script>
        const CSRF_TOKEN = <?php echo json_encode($communityCsrfToken); ?>;

        (function () {
            const friendsTab = document.getElementById('comm-friends');
            const studentGrid = document.getElementById('community-student-grid');
            const incomingGrid = document.getElementById('community-incoming-grid');
            const discoverableToggle = document.getElementById('community-discoverable');
            const discoveryContent = document.getElementById('community-discovery-content');
            const privateNotice = document.getElementById('community-discovery-private');
            const incomingSection = document.getElementById('community-incoming-section');
            const feedback = document.getElementById('community-friends-feedback');
            const reportModal = document.getElementById('community-friend-report-modal');
            const reportForm = document.getElementById('community-friend-report-form');
            if (!friendsTab || !studentGrid || !incomingGrid || !discoverableToggle || !reportModal || !reportForm) return;

            const currentUserId = Number(<?php echo (int) $_SESSION['user_id']; ?>);
            const filters = {
                q: document.getElementById('community-friend-search'),
                country: document.getElementById('community-friend-country'),
                course: document.getElementById('community-friend-course'),
                intake: document.getElementById('community-friend-intake')
            };
            let requestTimer = null;
            let reportTargetId = 0;

            function setFriendsFeedback(message, isError) {
                feedback.textContent = message || '';
                feedback.classList.toggle('error', Boolean(isError));
                feedback.classList.toggle('success', Boolean(message) && !isError);
            }

            async function friendsRequest(method, values) {
                const options = { method: method, credentials: 'same-origin' };
                let url = 'student-community-friends-api.php';
                if (method === 'GET') {
                    const query = new URLSearchParams(values || {});
                    url += '?' + query.toString();
                } else {
                    options.headers = { 'Content-Type': 'application/json' };
                    options.body = JSON.stringify(Object.assign({ csrf_token: CSRF_TOKEN }, values || {}));
                }
                const response = await fetch(url, options);
                const result = await response.json();
                if (!response.ok || !result.success) {
                    const error = new Error(result.message || 'Unable to load student connections.');
                    error.status = response.status;
                    throw error;
                }
                return result;
            }

            function makeButton(label, action, targetId, className) {
                const button = document.createElement('button');
                button.type = 'button';
                button.textContent = label;
                button.dataset.friendAction = action;
                button.dataset.targetId = String(targetId);
                if (className) button.className = className;
                return button;
            }

            function createStudentCard(student, isIncoming) {
                const card = document.createElement('article');
                card.className = 'community-friend-card';
                card.dataset.studentId = String(student.id);
                card.dataset.studentName = student.name;

                const top = document.createElement('div');
                top.className = 'community-friend-card-top';
                const avatar = document.createElement('div');
                avatar.className = 'community-friend-avatar';
                avatar.setAttribute('aria-hidden', 'true');
                avatar.textContent = student.name.split(/\s+/).filter(Boolean).slice(0, 2).map(part => part.charAt(0).toUpperCase()).join('') || 'S';
                const identity = document.createElement('div');
                const name = document.createElement('h5');
                name.className = 'community-friend-name';
                name.textContent = student.name;
                const subtitle = document.createElement('div');
                subtitle.className = 'community-friend-caption';
                subtitle.textContent = isIncoming ? 'Wants to connect with you' : 'Student community member';
                identity.append(name, subtitle);
                top.append(avatar, identity);

                const details = document.createElement('div');
                details.className = 'community-friend-details';
                [
                    ['fa-location-dot', student.country || 'Destination not added'],
                    ['fa-book-open', student.course || 'Course not added'],
                    ['fa-calendar', student.intake || 'Intake not added']
                ].forEach(([iconName, value]) => {
                    const row = document.createElement('span');
                    const icon = document.createElement('i');
                    icon.className = 'fa-solid ' + iconName;
                    icon.setAttribute('aria-hidden', 'true');
                    const text = document.createElement('span');
                    text.textContent = value;
                    row.append(icon, text);
                    details.appendChild(row);
                });

                const actions = document.createElement('div');
                actions.className = 'community-friend-actions';
                if (isIncoming) {
                    actions.append(
                        makeButton('Accept', 'accept', student.id, 'primary'),
                        makeButton('Decline', 'decline', student.id, '')
                    );
                } else if (student.connection_status === 'accepted') {
                    actions.append(
                        makeButton('Message', 'message', student.id, 'primary'),
                        makeButton('Connected', 'disconnect', student.id, '')
                    );
                } else if (student.connection_status === 'pending' && Number(student.requester_id) === currentUserId) {
                    actions.append(makeButton('Request sent', 'pending', student.id, ''));
                } else if (student.connection_status === 'pending') {
                    actions.append(makeButton('Respond to request', 'pending', student.id, ''));
                } else {
                    actions.append(makeButton('Connect', 'connect', student.id, 'primary'));
                }

                const menu = document.createElement('details');
                menu.className = 'community-friend-menu';
                const menuButton = document.createElement('summary');
                menuButton.setAttribute('aria-label', 'More options for ' + student.name);
                const menuIcon = document.createElement('i');
                menuIcon.className = 'fa-solid fa-ellipsis-vertical';
                menuIcon.setAttribute('aria-hidden', 'true');
                menuButton.appendChild(menuIcon);
                const menuItems = document.createElement('div');
                menuItems.className = 'community-friend-menu-items';
                if (student.connection_status !== 'blocked') {
                    menuItems.append(
                        makeButton('Report student', 'report', student.id, ''),
                        makeButton('Block student', 'block', student.id, '')
                    );
                }
                menu.append(menuButton, menuItems);
                card.append(top, menu, details, actions);
                return card;
            }

            async function loadDirectory() {
                setFriendsFeedback('Loading student directory...', false);
                const params = { action: 'discover' };
                Object.entries(filters).forEach(([key, input]) => {
                    if (input && input.value.trim()) params[key] = input.value.trim();
                });
                try {
                    const result = await friendsRequest('GET', params);
                    discoverableToggle.checked = result.discoverable;
                    privateNotice.hidden = result.discoverable;
                    discoveryContent.hidden = false;

                    incomingGrid.replaceChildren();
                    incomingSection.hidden = result.incoming_requests.length === 0;
                    result.incoming_requests.forEach(student => incomingGrid.appendChild(createStudentCard(student, true)));

                    studentGrid.replaceChildren();
                    result.students.forEach(student => studentGrid.appendChild(createStudentCard(student, false)));
                    document.getElementById('community-friends-empty').hidden =
                        result.students.length > 0;
                } catch (error) {
                    setFriendsFeedback(error.message, true);
                }
            }

            document.querySelector('[data-comm-target="comm-friends"]').addEventListener('click', loadDirectory);
            Object.values(filters).forEach(input => {
                if (!input) return;
                input.addEventListener('input', () => {
                    window.clearTimeout(requestTimer);
                    requestTimer = window.setTimeout(loadDirectory, 300);
                });
            });

            discoverableToggle.addEventListener('change', async () => {
                discoverableToggle.disabled = true;
                try {
                    await friendsRequest('POST', { action: 'visibility', discoverable: discoverableToggle.checked });
                    await loadDirectory();
                } catch (error) {
                    discoverableToggle.checked = !discoverableToggle.checked;
                    setFriendsFeedback(error.message, true);
                } finally {
                    discoverableToggle.disabled = false;
                }
            });

            friendsTab.addEventListener('click', async event => {
                const button = event.target.closest('[data-friend-action]');
                if (!button) return;
                const action = button.dataset.friendAction;
                const targetId = Number(button.dataset.targetId);
                if (!targetId || ['pending'].includes(action)) return;
                if (action === 'message') {
                    await openInboxWithStudent(targetId);
                    return;
                }
                if (action === 'disconnect') {
                    const card = button.closest('[data-student-id]');
                    if (!await window.showDashboardToast('Disconnect from ' + (card?.dataset.studentName || 'this student') + '? You can send a new request later.', { confirm: true, type: 'warning', confirmLabel: 'Disconnect' })) return;
                    await runFriendAction(button, { action: 'disconnect', target_id: targetId }, true);
                    return;
                }
                if (action === 'block') {
                    const card = button.closest('[data-student-id]');
                    if (!await window.showDashboardToast('Block ' + (card?.dataset.studentName || 'this student') + '? They will no longer appear in your directory.', { confirm: true, type: 'warning', confirmLabel: 'Block' })) return;
                    button.closest('.community-friend-menu').open = false;
                    await runFriendAction(button, { action: 'block', target_id: targetId }, true);
                    return;
                }
                if (action === 'report') {
                    const card = button.closest('[data-student-id]');
                    reportTargetId = targetId;
                    document.getElementById('community-friend-report-name').textContent =
                        'Report ' + (card?.dataset.studentName || 'this student') + ' to the community team.';
                    const reportFeedback = document.getElementById('community-friend-report-feedback');
                    reportFeedback.textContent = '';
                    reportFeedback.classList.remove('error', 'success');
                    button.closest('.community-friend-menu').open = false;
                    reportModal.hidden = false;
                    reportForm.elements.reason.focus();
                    return;
                }
                if (action === 'accept' || action === 'decline') {
                    await runFriendAction(button, {
                        action: 'respond',
                        target_id: targetId,
                        decision: action
                    }, true);
                    return;
                }
                if (action === 'connect') {
                    await runFriendAction(button, { action: 'request', target_id: targetId }, false);
                }
            });

            async function runFriendAction(button, payload, reload) {
                button.disabled = true;
                try {
                    await friendsRequest('POST', payload);
                    if (reload) await loadDirectory();
                    else {
                        button.textContent = 'Request sent';
                        button.dataset.friendAction = 'pending';
                        button.classList.remove('primary');
                        setFriendsFeedback('Connection request sent.', false);
                    }
                } catch (error) {
                    setFriendsFeedback(error.message, true);
                    button.disabled = false;
                }
            }

            document.getElementById('community-friend-report-cancel').addEventListener('click', () => {
                reportModal.hidden = true;
                reportForm.reset();
            });
            reportModal.addEventListener('click', event => {
                if (event.target === reportModal) document.getElementById('community-friend-report-cancel').click();
            });
            reportForm.addEventListener('submit', async event => {
                event.preventDefault();
                const submitButton = reportForm.querySelector('[type="submit"]');
                submitButton.disabled = true;
                try {
                    const result = await friendsRequest('POST', {
                        action: 'report',
                        target_id: reportTargetId,
                        reason: reportForm.elements.reason.value.trim()
                    });
                    document.getElementById('community-friend-report-feedback').textContent = result.message;
                    document.getElementById('community-friend-report-feedback').classList.add('success');
                    reportForm.reset();
                } catch (error) {
                    const reportFeedback = document.getElementById('community-friend-report-feedback');
                    reportFeedback.textContent = error.message;
                    reportFeedback.classList.add('error');
                } finally {
                    submitButton.disabled = false;
                }
            });

            const inboxThreads = document.getElementById('community-inbox-threads');
            const inboxMessages = document.getElementById('community-inbox-messages');
            const inboxHeader = document.getElementById('community-inbox-chat-header');
            const inboxSearch = document.getElementById('community-inbox-message-search');
            const inboxNotificationMode = document.getElementById('community-inbox-notification-mode');
            const inboxBlockButton = document.getElementById('community-inbox-block');
            const inboxReportButton = document.getElementById('community-inbox-report');
            const inboxUploadStatus = document.getElementById('community-inbox-upload-status');
            const inboxCompose = document.getElementById('community-inbox-compose');
            const inboxFeedback = document.getElementById('community-inbox-feedback');
            const inboxAttachment = document.getElementById('community-inbox-attachment');
            const inboxAttachmentButton = document.getElementById('community-inbox-attach-button');
            const inboxAttachmentName = document.getElementById('community-inbox-attachment-name');
            const inboxReplyPreview = document.getElementById('community-inbox-reply-preview');
            const inboxReplyName = document.getElementById('community-inbox-reply-name');
            const inboxReplyText = document.getElementById('community-inbox-reply-text');
            const inboxReplyCancel = document.getElementById('community-inbox-reply-cancel');
            let activeConversationId = 0;
            let lastDirectMessageId = 0;
            let directMessagePollTimer = null;
            let loadingConversation = false;
            let replyToMessage = null;

            function directMessagePreview(message) {
                if (message.message_type === 'deleted') return 'This message was deleted';
                if (message.message) return message.message;
                if (message.attachment) {
                    if (message.attachment.mime.startsWith('image/')) return 'Photo';
                    if (message.attachment.mime.startsWith('video/')) return 'Video';
                    return message.attachment.name || 'Attachment';
                }
                if (message.attachment_mime) {
                    if (message.attachment_mime.startsWith('image/')) return 'Photo';
                    if (message.attachment_mime.startsWith('video/')) return 'Video';
                    return message.attachment_name || 'Attachment';
                }
                return '';
            }

            function clearDirectReply() {
                replyToMessage = null;
                inboxReplyPreview.hidden = true;
                inboxReplyName.textContent = '';
                inboxReplyText.textContent = '';
            }

            function setDirectReply(message) {
                replyToMessage = message;
                inboxReplyName.textContent = Number(message.sender_id) === currentUserId
                    ? 'You'
                    : (message.sender_name || inboxHeader.textContent || 'Student');
                inboxReplyText.textContent = directMessagePreview(message);
                inboxReplyPreview.hidden = false;
                inboxCompose.querySelector('textarea').focus();
            }

            function updateInboxUnreadBadge(count) {
                const badge = document.getElementById('community-inbox-unread-badge');
                if (!badge) return;
                badge.textContent = count > 99 ? '99+' : String(count);
                badge.hidden = count < 1;
            }

            inboxReplyCancel.addEventListener('click', clearDirectReply);

            async function loadInboxThreads() {
                try {
                    const result = await friendsRequest('GET', { action: 'inbox' });
                    inboxThreads.replaceChildren();
                    const totalUnread = result.threads.reduce((total, thread) => total + Number(thread.unread_count || 0), 0);
                    updateInboxUnreadBadge(totalUnread);
                    if (result.threads.length === 0) {
                        const empty = document.createElement('div');
                        empty.className = 'community-inbox-empty';
                        empty.textContent = 'Connect with a student to start a personal chat.';
                        inboxThreads.appendChild(empty);
                        return true;
                    }
                    result.threads.forEach(thread => {
                        const button = document.createElement('button');
                        button.type = 'button';
                        button.className = 'community-inbox-thread';
                        button.dataset.inboxTarget = String(thread.student_id);
                        if (Number(thread.student_id) === activeConversationId) button.classList.add('active');
                        const avatar = document.createElement('span');
                        avatar.className = 'community-inbox-thread-avatar';
                        avatar.textContent = thread.student_name.split(/\s+/).filter(Boolean).slice(0, 2).map(part => part.charAt(0).toUpperCase()).join('') || 'S';
                        const copy = document.createElement('span');
                        copy.className = 'community-inbox-thread-copy';
                        const name = document.createElement('span');
                        name.className = 'community-inbox-thread-name';
                        name.textContent = thread.student_name;
                        const preview = document.createElement('span');
                        preview.className = 'community-inbox-thread-preview';
                        preview.textContent = thread.last_message || 'Start a conversation';
                        copy.append(name, preview);
                        button.append(avatar, copy);
                        const unreadCount = Number(thread.unread_count || 0);
                        if (unreadCount > 0) {
                            const unreadBadge = document.createElement('span');
                            unreadBadge.className = 'community-unread-badge community-inbox-thread-unread';
                            unreadBadge.textContent = unreadCount > 99 ? '99+' : String(unreadCount);
                            unreadBadge.setAttribute('aria-label', unreadCount + ' unread messages');
                            button.appendChild(unreadBadge);
                        }
                        inboxThreads.appendChild(button);
                    });
                    return true;
                } catch (error) {
                    inboxFeedback.textContent = error.message;
                    inboxFeedback.classList.add('error');
                    return false;
                }
            }

            function appendDirectMessages(messages) {
                const placeholder = inboxMessages.querySelector('.community-inbox-placeholder');
                const empty = inboxMessages.querySelector('.community-inbox-empty');
                if (messages.length) {
                    if (placeholder) placeholder.remove();
                    if (empty) empty.remove();
                }
                messages.forEach(message => {
                    let insertionPoint = null;
                    const existingMessage = inboxMessages.querySelector(
                        '[data-message-id="' + Number(message.id) + '"]'
                    );
                    if (existingMessage) {
                        if (message.message_type === 'deleted' && !existingMessage.classList.contains('deleted')) {
                            insertionPoint = existingMessage.nextSibling;
                            existingMessage.remove();
                        } else if (message.edited_at && existingMessage.dataset.editedAt !== message.edited_at) {
                            const text = existingMessage.querySelector('.community-inbox-message-text');
                            if (text) text.textContent = message.message;
                            existingMessage.dataset.editedAt = message.edited_at;
                            existingMessage._directMessage = message;
                            let edited = existingMessage.querySelector('.community-message-edited');
                            if (!edited) {
                                edited = document.createElement('span');
                                edited.className = 'community-message-edited';
                                edited.textContent = 'edited';
                                existingMessage.querySelector('.community-inbox-message-footer')?.prepend(edited);
                            }
                            return;
                        } else {
                            const readStatus = existingMessage.querySelector('.community-message-read-status');
                            if (readStatus) {
                                readStatus.textContent = message.recipient_read_at
                                    ? 'Read'
                                    : (message.recipient_delivered_at ? 'Delivered' : 'Sent');
                            }
                            return;
                        }
                    }

                    const bubble = document.createElement('article');
                    const isMine = Number(message.sender_id) === currentUserId;
                    bubble.className = 'community-inbox-message' + (isMine ? ' mine' : '') +
                        (message.message_type === 'deleted' ? ' deleted' : '');
                    bubble.dataset.messageId = String(message.id);
                    bubble.dataset.editedAt = message.edited_at || '';
                    bubble._directMessage = message;

                    if (message.message_type === 'deleted') {
                        const deletedText = document.createElement('div');
                        deletedText.textContent = isMine ? 'You deleted this message' : 'This message was deleted';
                        bubble.appendChild(deletedText);
                    }

                    if (message.message_type !== 'deleted' && message.reply_to) {
                        const replyQuote = document.createElement('button');
                        replyQuote.type = 'button';
                        replyQuote.className = 'community-inbox-reply-quote';
                        replyQuote.dataset.scrollToMessage = String(message.reply_to.id);
                        const quotedName = document.createElement('strong');
                        quotedName.textContent = Number(message.reply_to.sender_id) === currentUserId
                            ? 'You'
                            : (message.reply_to.sender_name || 'Student');
                        const quotedText = document.createElement('span');
                        quotedText.textContent = directMessagePreview(message.reply_to);
                        replyQuote.append(quotedName, quotedText);
                        bubble.appendChild(replyQuote);
                    }

                    if (message.message_type !== 'deleted' && message.attachment) {
                        const attachment = message.attachment;
                        const attachmentWrap = document.createElement('div');
                        attachmentWrap.className = 'community-inbox-attachment-wrap';
                        const downloadUrl = new URL(attachment.url, window.location.href);
                        downloadUrl.searchParams.set('download', '1');
                        const downloadLink = document.createElement('a');
                        downloadLink.className = 'community-inbox-attachment-download';
                        downloadLink.href = downloadUrl.toString();
                        downloadLink.download = attachment.name;
                        downloadLink.title = 'Download ' + attachment.name;
                        downloadLink.setAttribute('aria-label', 'Download ' + attachment.name);
                        const downloadIcon = document.createElement('i');
                        downloadIcon.className = 'fa-solid fa-download';
                        downloadIcon.setAttribute('aria-hidden', 'true');
                        downloadLink.appendChild(downloadIcon);

                        if (attachment.mime.startsWith('image/')) {
                            const image = document.createElement('img');
                            image.className = 'community-inbox-attachment';
                            image.src = attachment.url;
                            image.alt = attachment.name;
                            image.loading = 'lazy';
                            attachmentWrap.appendChild(image);
                        } else if (attachment.mime.startsWith('video/')) {
                            const video = document.createElement('video');
                            video.className = 'community-inbox-attachment';
                            video.src = attachment.url;
                            video.controls = true;
                            video.preload = 'metadata';
                            video.setAttribute('aria-label', attachment.name);
                            attachmentWrap.appendChild(video);
                        } else {
                            const fileLink = document.createElement('a');
                            fileLink.className = 'community-inbox-attachment';
                            fileLink.href = attachment.url;
                            fileLink.textContent = attachment.name;
                            fileLink.setAttribute('download', attachment.name);
                            attachmentWrap.appendChild(fileLink);
                        }
                        attachmentWrap.appendChild(downloadLink);
                        bubble.appendChild(attachmentWrap);
                    }

                    if (message.message_type !== 'deleted' && message.message) {
                        const text = document.createElement('div');
                        text.className = 'community-inbox-message-text';
                        text.textContent = message.message;
                        bubble.appendChild(text);
                    }

                    const time = document.createElement('time');
                    time.className = 'community-inbox-message-time';
                    const date = new Date(String(message.created_at).replace(' ', 'T'));
                    time.textContent = Number.isNaN(date.getTime())
                        ? ''
                        : date.toLocaleTimeString([], { hour: 'numeric', minute: '2-digit' });
                    const footer = document.createElement('div');
                    footer.className = 'community-inbox-message-footer';
                    footer.appendChild(time);
                    if (message.edited_at) {
                        const edited = document.createElement('span');
                        edited.className = 'community-message-edited';
                        edited.textContent = 'edited';
                        footer.appendChild(edited);
                    }
                    if (isMine && message.message_type !== 'deleted') {
                        const readStatus = document.createElement('span');
                        readStatus.className = 'community-message-read-status';
                        readStatus.textContent = message.recipient_read_at
                            ? 'Read'
                            : (message.recipient_delivered_at ? 'Delivered' : 'Sent');
                        footer.appendChild(readStatus);
                    }
                    if (message.message_type !== 'deleted') {
                        const messageActions = document.createElement('span');
                        messageActions.className = 'community-inbox-message-actions';
                        const replyButton = document.createElement('button');
                        replyButton.type = 'button';
                        replyButton.className = 'community-inbox-reply';
                        replyButton.dataset.replyDirectMessage = String(message.id);
                        replyButton.title = 'Reply to message';
                        replyButton.setAttribute('aria-label', 'Reply to message');
                        const replyIcon = document.createElement('i');
                        replyIcon.className = 'fa-solid fa-reply';
                        replyIcon.setAttribute('aria-hidden', 'true');
                        replyButton.appendChild(replyIcon);
                        messageActions.appendChild(replyButton);
                        if (isMine) {
                            const editButton = document.createElement('button');
                            editButton.type = 'button';
                            editButton.className = 'community-inbox-delete';
                            editButton.dataset.editDirectMessage = String(message.id);
                            editButton.title = 'Edit message';
                            editButton.setAttribute('aria-label', 'Edit message');
                            editButton.innerHTML = '<i class="fa-regular fa-pen-to-square" aria-hidden="true"></i>';
                            messageActions.appendChild(editButton);
                            const deleteButton = document.createElement('button');
                            deleteButton.type = 'button';
                            deleteButton.className = 'community-inbox-delete';
                            deleteButton.dataset.deleteDirectMessage = String(message.id);
                            deleteButton.title = 'Delete message for everyone';
                            deleteButton.setAttribute('aria-label', 'Delete message for everyone');
                            const deleteIcon = document.createElement('i');
                            deleteIcon.className = 'fa-regular fa-trash-can';
                            deleteIcon.setAttribute('aria-hidden', 'true');
                            deleteButton.appendChild(deleteIcon);
                            messageActions.appendChild(deleteButton);
                        } else {
                            const reportButton = document.createElement('button');
                            reportButton.type = 'button';
                            reportButton.className = 'community-inbox-delete';
                            reportButton.dataset.reportDirectUser = String(message.sender_id);
                            reportButton.title = 'Report this message';
                            reportButton.setAttribute('aria-label', 'Report this message');
                            reportButton.innerHTML = '<i class="fa-regular fa-flag" aria-hidden="true"></i>';
                            messageActions.appendChild(reportButton);
                        }
                        footer.appendChild(messageActions);
                    }
                    bubble.appendChild(footer);
                    if (insertionPoint && insertionPoint.parentNode === inboxMessages) {
                        inboxMessages.insertBefore(bubble, insertionPoint);
                    } else {
                        inboxMessages.appendChild(bubble);
                    }
                    lastDirectMessageId = Math.max(lastDirectMessageId, Number(message.id) || 0);
                });
                applyDirectMessageSearch();
                if (messages.length) inboxMessages.scrollTop = inboxMessages.scrollHeight;
            }

            function applyDirectMessageSearch() {
                const query = inboxSearch.value.trim().toLocaleLowerCase();
                inboxMessages.querySelectorAll('[data-message-id]').forEach(message => {
                    message.hidden = query !== '' && !message.textContent.toLocaleLowerCase().includes(query);
                });
            }

            inboxMessages.addEventListener('click', async event => {
                const replyButton = event.target.closest('[data-reply-direct-message]');
                if (replyButton) {
                    const messageBubble = replyButton.closest('[data-message-id]');
                    const replyMessage = messageBubble &&
                        Array.from(inboxMessages.querySelectorAll('[data-message-id]'))
                            .map(element => element._directMessage)
                            .find(message => Number(message?.id) === Number(replyButton.dataset.replyDirectMessage));
                    if (replyMessage) setDirectReply(replyMessage);
                    return;
                }

                const replyQuote = event.target.closest('[data-scroll-to-message]');
                if (replyQuote) {
                    const targetMessage = inboxMessages.querySelector(
                        '[data-message-id="' + Number(replyQuote.dataset.scrollToMessage) + '"]'
                    );
                    if (targetMessage) {
                        targetMessage.scrollIntoView({ behavior: 'smooth', block: 'center' });
                        targetMessage.classList.add('highlighted');
                        window.setTimeout(() => targetMessage.classList.remove('highlighted'), 1200);
                    }
                    return;
                }

                const editButton = event.target.closest('[data-edit-direct-message]');
                if (editButton && activeConversationId) {
                    const bubble = editButton.closest('[data-message-id]');
                    const current = bubble?._directMessage;
                    if (!current) return;
                    const editedMessage = window.prompt('Edit your message:', current.message || '');
                    if (editedMessage === null || editedMessage.trim() === '' || editedMessage.trim() === current.message) return;
                    editButton.disabled = true;
                    try {
                        const result = await friendsRequest('POST', {
                            action: 'edit_message',
                            target_id: activeConversationId,
                            message_id: current.id,
                            message: editedMessage.trim()
                        });
                        appendDirectMessages([Object.assign({}, current, result.message)]);
                    } catch (error) {
                        inboxFeedback.textContent = error.message;
                        inboxFeedback.classList.add('error');
                    } finally {
                        editButton.disabled = false;
                    }
                    return;
                }

                const reportButton = event.target.closest('[data-report-direct-user]');
                if (reportButton) {
                    const reason = window.prompt('Why are you reporting this message?');
                    if (reason === null) return;
                    try {
                        await friendsRequest('POST', {
                            action: 'report',
                            target_id: reportButton.dataset.reportDirectUser,
                            reason: reason.trim()
                        });
                        inboxFeedback.textContent = 'Report submitted for review.';
                        inboxFeedback.classList.remove('error');
                    } catch (error) {
                        inboxFeedback.textContent = error.message;
                        inboxFeedback.classList.add('error');
                    }
                    return;
                }

                const deleteButton = event.target.closest('[data-delete-direct-message]');
                if (!deleteButton || !activeConversationId) return;
                const messageId = Number(deleteButton.dataset.deleteDirectMessage);
                if (!Number.isInteger(messageId) || !await window.showDashboardToast('Delete this message for everyone?', { confirm: true, type: 'warning', confirmLabel: 'Delete' })) return;

                deleteButton.disabled = true;
                try {
                    const result = await friendsRequest('POST', {
                        action: 'delete_message',
                        target_id: activeConversationId,
                        message_id: messageId
                    });
                    appendDirectMessages([result.message]);
                    await loadInboxThreads();
                } catch (error) {
                    inboxFeedback.textContent = error.message;
                    inboxFeedback.classList.add('error');
                    deleteButton.disabled = false;
                }
            });

            async function loadConversation(targetId, initial) {
                if (loadingConversation || !targetId) return;
                loadingConversation = true;
                try {
                    const result = await friendsRequest('GET', {
                        action: 'conversation',
                        target_id: String(targetId),
                        after_id: initial ? '0' : String(lastDirectMessageId)
                    });
                    if (activeConversationId !== Number(targetId)) return;
                    inboxHeader.textContent = result.student.name;
                    inboxCompose.hidden = false;
                    inboxNotificationMode.hidden = false;
                    inboxBlockButton.hidden = false;
                    inboxReportButton.hidden = false;
                    if (initial) {
                        inboxMessages.replaceChildren();
                        lastDirectMessageId = 0;
                        const preference = await friendsRequest('GET', {
                            action: 'notification_preference',
                            target_id: String(targetId)
                        });
                        if (activeConversationId === Number(targetId)) {
                            inboxNotificationMode.value = preference.notification_mode;
                        }
                    }
                    appendDirectMessages(result.messages);
                    const readStatus = await friendsRequest('GET', {
                        action: 'read_status',
                        target_id: String(targetId)
                    });
                    readStatus.read_status.forEach(status => {
                        const bubble = inboxMessages.querySelector('[data-message-id="' + Number(status.id) + '"]');
                        const indicator = bubble?.querySelector('.community-message-read-status');
                        if (indicator) {
                            indicator.textContent = status.recipient_read_at
                                ? 'Read'
                                : (status.recipient_delivered_at ? 'Delivered' : 'Sent');
                        }
                    });
                    if (initial) await loadInboxThreads();
                    if (initial && result.messages.length === 0) {
                        const empty = document.createElement('div');
                        empty.className = 'community-inbox-empty';
                        empty.textContent = 'No messages yet. Say hello!';
                        inboxMessages.appendChild(empty);
                    }
                    inboxFeedback.textContent = '';
                    inboxFeedback.classList.remove('error');
                } catch (error) {
                    if (activeConversationId !== Number(targetId)) return;
                    inboxFeedback.textContent = error.message;
                    inboxFeedback.classList.add('error');
                    if (error.status === 401 || error.status === 403) {
                        if (directMessagePollTimer) window.clearInterval(directMessagePollTimer);
                        directMessagePollTimer = null;
                        activeConversationId = 0;
                        inboxMessages.replaceChildren();
                        const unavailable = document.createElement('div');
                        unavailable.className = 'community-inbox-empty';
                        unavailable.textContent = 'This conversation is no longer available.';
                        inboxMessages.appendChild(unavailable);
                        inboxCompose.hidden = true;
                        inboxHeader.textContent = 'Conversation unavailable';
                    } else if (initial) {
                        inboxCompose.hidden = true;
                        inboxHeader.textContent = 'Unable to open conversation';
                    }
                } finally {
                    loadingConversation = false;
                }
            }

            function openConversation(targetId) {
                activeConversationId = Number(targetId);
                lastDirectMessageId = 0;
                clearDirectReply();
                inboxSearch.value = '';
                inboxUploadStatus.textContent = '';
                inboxUploadStatus.classList.remove('error');
                if (directMessagePollTimer) window.clearInterval(directMessagePollTimer);
                inboxMessages.replaceChildren();
                const loading = document.createElement('div');
                loading.className = 'community-inbox-placeholder';
                loading.textContent = 'Loading conversation...';
                inboxMessages.appendChild(loading);
                inboxHeader.textContent = 'Loading conversation...';
                inboxNotificationMode.hidden = true;
                inboxBlockButton.hidden = true;
                inboxReportButton.hidden = true;
                inboxCompose.hidden = true;
                inboxThreads.querySelectorAll('[data-inbox-target]').forEach(button => {
                    button.classList.toggle('active', Number(button.dataset.inboxTarget) === activeConversationId);
                });
                loadConversation(activeConversationId, true);
                directMessagePollTimer = window.setInterval(() => {
                    loadConversation(activeConversationId, false);
                }, 4000);
            }

            async function openInboxWithStudent(targetId) {
                const inboxTabButton = document.querySelector('.comm-tab-btn[data-comm-target="comm-inbox"]');
                if (inboxTabButton) inboxTabButton.click();
                if (!await loadInboxThreads()) return;
                if (!inboxThreads.querySelector('[data-inbox-target="' + Number(targetId) + '"]')) {
                    inboxFeedback.textContent = 'This student is no longer connected.';
                    inboxFeedback.classList.add('error');
                    return;
                }
                openConversation(targetId);
                inboxCompose.querySelector('textarea').focus();
            }

            inboxThreads.addEventListener('click', event => {
                const threadButton = event.target.closest('[data-inbox-target]');
                if (threadButton) openConversation(Number(threadButton.dataset.inboxTarget));
            });
            inboxSearch.addEventListener('input', applyDirectMessageSearch);
            inboxNotificationMode.addEventListener('change', async () => {
                if (!activeConversationId) return;
                inboxNotificationMode.disabled = true;
                try {
                    await friendsRequest('POST', {
                        action: 'notification_preference',
                        target_id: activeConversationId,
                        mode: inboxNotificationMode.value
                    });
                    inboxFeedback.textContent = 'Notification setting saved.';
                    inboxFeedback.classList.remove('error');
                } catch (error) {
                    inboxFeedback.textContent = error.message;
                    inboxFeedback.classList.add('error');
                } finally {
                    inboxNotificationMode.disabled = false;
                }
            });
            inboxBlockButton.addEventListener('click', async () => {
                if (!activeConversationId || !await window.showDashboardToast('Block this student? This will stop personal messages and disconnect you.', {
                    confirm: true,
                    type: 'warning',
                    confirmLabel: 'Block student'
                })) return;
                try {
                    await friendsRequest('POST', { action: 'block', target_id: activeConversationId });
                    if (directMessagePollTimer) window.clearInterval(directMessagePollTimer);
                    directMessagePollTimer = null;
                    activeConversationId = 0;
                    inboxMessages.replaceChildren();
                    inboxCompose.hidden = true;
                    inboxNotificationMode.hidden = true;
                    inboxBlockButton.hidden = true;
                    inboxReportButton.hidden = true;
                    inboxHeader.textContent = 'Student blocked';
                    inboxFeedback.textContent = 'This student can no longer message you.';
                    inboxFeedback.classList.remove('error');
                    await loadInboxThreads();
                } catch (error) {
                    inboxFeedback.textContent = error.message;
                    inboxFeedback.classList.add('error');
                }
            });
            inboxReportButton.addEventListener('click', async () => {
                if (!activeConversationId) return;
                const reason = window.prompt('Why are you reporting this student?');
                if (reason === null) return;
                try {
                    await friendsRequest('POST', {
                        action: 'report',
                        target_id: activeConversationId,
                        reason: reason.trim()
                    });
                    inboxFeedback.textContent = 'Report submitted for review.';
                    inboxFeedback.classList.remove('error');
                } catch (error) {
                    inboxFeedback.textContent = error.message;
                    inboxFeedback.classList.add('error');
                }
            });
            inboxAttachmentButton.addEventListener('click', () => inboxAttachment.click());
            inboxAttachment.addEventListener('change', () => {
                const file = inboxAttachment.files[0];
                inboxAttachmentName.textContent = file
                    ? file.name + ' (' + Math.ceil(file.size / 1024) + ' KB)'
                    : '';
                inboxAttachmentName.title = inboxAttachmentName.textContent;
                inboxUploadStatus.textContent = '';
                inboxUploadStatus.classList.remove('error');
            });
            document.querySelector('[data-comm-target="comm-inbox"]').addEventListener('click', loadInboxThreads);
            document.querySelectorAll('.comm-tab-btn').forEach(tabButton => {
                tabButton.addEventListener('click', () => {
                    if (tabButton.dataset.commTarget !== 'comm-inbox' && directMessagePollTimer) {
                        window.clearInterval(directMessagePollTimer);
                        directMessagePollTimer = null;
                    }
                });
            });
            loadInboxThreads();
            window.setInterval(() => {
                if (!document.hidden) loadInboxThreads();
            }, 15000);

            function uploadDirectMessage(payload, file) {
                return new Promise((resolve, reject) => {
                    const xhr = new XMLHttpRequest();
                    xhr.open('POST', 'student-community-friends-api.php');
                    xhr.withCredentials = true;
                    xhr.upload.addEventListener('progress', event => {
                        if (file && event.lengthComputable) {
                            inboxUploadStatus.textContent = 'Uploading attachment: ' +
                                Math.round((event.loaded / event.total) * 100) + '%';
                        }
                    });
                    xhr.addEventListener('load', () => {
                        let result;
                        try {
                            result = JSON.parse(xhr.responseText);
                        } catch (error) {
                            reject(new Error('The server returned an invalid response. Please retry.'));
                            return;
                        }
                        if (xhr.status < 200 || xhr.status >= 300 || !result.success) {
                            const requestError = new Error(result.message || 'Unable to send this personal message.');
                            requestError.status = xhr.status;
                            reject(requestError);
                            return;
                        }
                        resolve(result);
                    });
                    xhr.addEventListener('error', () => reject(new Error('Upload failed. Your message is still here; press Send to retry.')));
                    xhr.addEventListener('abort', () => reject(new Error('Upload cancelled. Press Send to retry.')));
                    xhr.send(payload);
                });
            }

            inboxCompose.addEventListener('submit', async event => {
                event.preventDefault();
                const textarea = inboxCompose.querySelector('textarea');
                const sendButton = inboxCompose.querySelector('[type="submit"]');
                const message = textarea.value.trim();
                const file = inboxAttachment.files[0] || null;
                if ((!message && !file) || !activeConversationId) return;
                sendButton.disabled = true;
                inboxUploadStatus.textContent = file ? 'Uploading attachment: 0%' : 'Sending message...';
                inboxUploadStatus.classList.remove('error');
                try {
                    const payload = new FormData();
                    payload.append('action', 'send_message');
                    payload.append('csrf_token', CSRF_TOKEN);
                    payload.append('target_id', String(activeConversationId));
                    payload.append('message', message);
                    if (replyToMessage) payload.append('reply_to_id', String(replyToMessage.id));
                    if (file) payload.append('attachment', file);
                    const result = await uploadDirectMessage(payload, file);
                    textarea.value = '';
                    inboxAttachment.value = '';
                    inboxAttachmentName.textContent = '';
                    inboxAttachmentName.title = '';
                    clearDirectReply();
                    appendDirectMessages([result.message]);
                    await loadInboxThreads();
                    inboxUploadStatus.textContent = '';
                } catch (error) {
                    inboxFeedback.textContent = error.message;
                    inboxFeedback.classList.add('error');
                    inboxUploadStatus.textContent = 'Not sent. Your message and attachment are kept; press Send to retry.';
                    inboxUploadStatus.classList.add('error');
                } finally {
                    sendButton.disabled = false;
                    textarea.focus();
                }
            });

            const communityParams = new URLSearchParams(window.location.search);
            const requestedCommunityTab = communityParams.get('community_tab');
            const requestedConversationId = Number(communityParams.get('message_with'));
            if (requestedCommunityTab === 'inbox') {
                loadInboxThreads().then(loaded => {
                    if (loaded && requestedConversationId > 0) openConversation(requestedConversationId);
                });
            }
            if (friendsTab.classList.contains('active-sub-view')) loadDirectory();
        })();

        // XSS-safe text helper
        function safeText(str) {
            return (str || '').toString();
        }

        // Dynamic Course Metadata (JavaScript client-side equivalent of get_course_meta)
        function getCourseMetaJS(pc) {
            const courseName = (pc.course_name || '').toString();
            const country = (pc.country_name || 'Global').toString();
            const cLower = courseName.toLowerCase();
            const coLower = country.toLowerCase();

            // 1. Degree Detection
            let degree = "Bachelor's Degree";
            if (/\b(phd|doctorate|doctoral|dphil)\b/i.test(cLower)) {
                degree = "Doctorate / PhD";
            } else if (/\b(master|masters|msc|ma\b|mba|llm|mres|meng|mphil)\b/i.test(cLower)) {
                degree = "Master's Degree";
            } else if (/\b(diploma|pgd|certificate|pgce)\b/i.test(cLower)) {
                degree = "Postgraduate Diploma";
            }

            // 2. Next Intake
            let intake = (pc.intakes || '').toString().trim();
            if (!intake || intake === '03/2027') {
                intake = 'Sep 2026 / Jan 2027';
            }

            // 3. Entry Requirement Score
            let entryScore = 'IELTS 6.5 (PTE 58)';
            if (degree === "Doctorate / PhD") {
                entryScore = 'IELTS 7.0 (PTE 65)';
            } else if (degree === "Master's Degree") {
                if (/\b(law|nursing|medicine|pharmacy|psychology|clinical)\b/i.test(cLower)) {
                    entryScore = 'IELTS 7.0 (PTE 65)';
                } else {
                    entryScore = 'IELTS 6.5 (PTE 58)';
                }
            } else if (degree === "Bachelor's Degree") {
                entryScore = 'IELTS 6.0 (PTE 50)';
            } else if (degree === "Postgraduate Diploma") {
                entryScore = 'IELTS 6.0 (PTE 52)';
            }

            // 4. English Waiver
            const waiverEligible = /united kingdom|uk|ireland|germany|france|dubai/i.test(coLower);

            // 5. Post-Study Work Visa (PSWV)
            let pswv = '2 Years Post-Study Work Visa';
            if (/united kingdom|uk/i.test(coLower)) {
                pswv = '2-Year Graduate Route';
            } else if (/united states|usa/i.test(coLower)) {
                pswv = /\b(science|engineer|tech|data|computer|math|analytics)\b/i.test(cLower) ? '3-Year STEM OPT' : '1-Year OPT';
            } else if (/canada/i.test(coLower)) {
                pswv = 'Up to 3-Year PGWP';
            } else if (/australia/i.test(coLower)) {
                pswv = '2-4 Year Subclass 485 Visa';
            } else if (/germany/i.test(coLower)) {
                pswv = '18-Month Job Seeking Permit';
            } else if (/ireland/i.test(coLower)) {
                pswv = (degree === "Master's Degree") ? '2-Year Stay Back Visa' : '1-Year Scheme';
            } else if (/new zealand/i.test(coLower)) {
                pswv = 'Up to 3-Year PSWV';
            }

            // 6. Living Cost
            let livingCost = '£1,015 - £1,300 / mo';
            if (/united states|usa/i.test(coLower)) livingCost = '$1,100 - $1,500 / mo';
            else if (/canada/i.test(coLower)) livingCost = 'CAD $1,200 - $1,600 / mo';
            else if (/australia/i.test(coLower)) livingCost = 'AUD $1,400 - $1,800 / mo';
            else if (/germany|france|ireland/i.test(coLower)) livingCost = '€850 - €1,200 / mo';

            // 7. Tuition
            let tuition = (pc.tuition_fee || '').toString().trim();
            if (!tuition || tuition.toLowerCase() === 'variable') {
                tuition = '$18,000 - $24,000 / yr';
            }

            return {
                degree,
                intake,
                entryScore,
                waiverEligible,
                pswv,
                livingCost,
                tuition,
                duration: pc.duration || (degree === "Master's Degree" ? '1 - 2 Years' : '3 - 4 Years')
            };
        }

        // Course Comparison State
        window.selectedCompareCourses = JSON.parse(sessionStorage.getItem('compareCourses') || '[]');

        function updateCompareDock() {
            const dock = document.getElementById('course-compare-dock');
            const itemsContainer = document.getElementById('compare-dock-items');
            const dockCount = document.getElementById('compare-dock-count');
            const launchBtn = document.getElementById('btn-compare-dock-launch');
            const badges = document.querySelectorAll('.compare-dock-count-badge');

            const count = window.selectedCompareCourses.length;
            if (dockCount) dockCount.textContent = count;
            badges.forEach(b => b.textContent = count);

            if (!dock || !itemsContainer) return;

            if (count === 0) {
                dock.style.display = 'none';
                return;
            }

            dock.style.display = 'block';
            itemsContainer.innerHTML = '';

            window.selectedCompareCourses.forEach(c => {
                const tag = document.createElement('div');
                tag.className = 'compare-dock-tag';
                tag.innerHTML = '<span>' + safeText(c.course_name) + '</span>';
                
                const rmBtn = document.createElement('button');
                rmBtn.type = 'button';
                rmBtn.className = 'compare-dock-tag-remove';
                rmBtn.innerHTML = '&times;';
                rmBtn.title = 'Remove course';
                rmBtn.addEventListener('click', (e) => {
                    e.stopPropagation();
                    removeCourseFromCompare(c.course_name);
                });
                tag.appendChild(rmBtn);
                itemsContainer.appendChild(tag);
            });

            if (launchBtn) {
                launchBtn.disabled = (count < 2);
                if (count < 2) {
                    launchBtn.title = 'Select at least 2 courses to compare';
                } else {
                    launchBtn.title = 'Compare courses side-by-side';
                }
            }

            // Sync all compare buttons in the UI
            document.querySelectorAll('[data-course-compare-btn]').forEach(btn => {
                const cName = btn.dataset.courseName || '';
                const isSelected = window.selectedCompareCourses.some(sc => sc.course_name === cName);
                if (isSelected) {
                    btn.classList.add('is-comparing');
                    btn.innerHTML = '<i class="fa-solid fa-check"></i> Added';
                } else {
                    btn.classList.remove('is-comparing');
                    btn.innerHTML = '<i class="fa-solid fa-code-compare"></i> Compare';
                }
            });

            sessionStorage.setItem('compareCourses', JSON.stringify(window.selectedCompareCourses));
        }

        function toggleCourseCompare(btn, courseData) {
            if (typeof courseData === 'string') {
                try {
                    courseData = JSON.parse(decodeURIComponent(courseData));
                } catch(e) {
                    console.error('Invalid course JSON', e);
                    return;
                }
            }

            const cName = courseData.course_name;
            const existingIdx = window.selectedCompareCourses.findIndex(sc => sc.course_name === cName);

            if (existingIdx > -1) {
                // Remove
                window.selectedCompareCourses.splice(existingIdx, 1);
                if (window.showDashboardToast) {
                    window.showDashboardToast('Removed "' + cName + '" from comparison.', { duration: 2500 });
                }
            } else {
                // Add
                if (window.selectedCompareCourses.length >= 3) {
                    if (window.showDashboardToast) {
                        window.showDashboardToast('You can compare a maximum of 3 courses at a time. Remove one first.', { type: 'warning', duration: 4000 });
                    }
                    return;
                }
                const meta = getCourseMetaJS(courseData);
                window.selectedCompareCourses.push(Object.assign({}, courseData, meta));
                if (window.showDashboardToast) {
                    window.showDashboardToast('Added "' + cName + '" to comparison (' + window.selectedCompareCourses.length + '/3).', { type: 'success', duration: 2500 });
                }
            }

            updateCompareDock();
        }

        function removeCourseFromCompare(courseName) {
            window.selectedCompareCourses = window.selectedCompareCourses.filter(c => c.course_name !== courseName);
            updateCompareDock();
            if (window.selectedCompareCourses.length >= 2) {
                renderCompareModalTable();
            } else {
                closeCompareModal();
            }
        }

        function clearCourseComparison() {
            window.selectedCompareCourses = [];
            updateCompareDock();
            closeCompareModal();
        }

        function openCompareModal() {
            if (window.selectedCompareCourses.length < 2) {
                if (window.showDashboardToast) {
                    window.showDashboardToast('Please select at least 2 courses to compare (up to 3).', { type: 'info', duration: 3500 });
                }
                return;
            }
            renderCompareModalTable();
            const modal = document.getElementById('course-compare-modal');
            if (modal) modal.style.display = 'flex';
        }

        function closeCompareModal() {
            const modal = document.getElementById('course-compare-modal');
            if (modal) modal.style.display = 'none';
        }

        function renderCompareModalTable() {
            const wrap = document.getElementById('compare-modal-table-wrap');
            if (!wrap) return;

            const courses = window.selectedCompareCourses;
            if (courses.length === 0) {
                wrap.innerHTML = '<p style="text-align:center; padding:40px; color:#64748b;">No courses selected for comparison.</p>';
                return;
            }

            let html = '<div style="overflow-x:auto;"><table class="compare-table">';
            
            // Header Row: University & Course Name
            html += '<thead><tr><th class="compare-row-header">Program Overview</th>';
            courses.forEach(c => {
                html += '<th class="compare-col-course compare-course-header-cell">' +
                    '<div class="compare-uni-name"><i class="fa-solid fa-building-columns"></i> ' + safeText(c.university_name) + ' (' + safeText(c.country_name) + ')</div>' +
                    '<div class="compare-course-name">' + safeText(c.course_name) + '</div>' +
                    '<span class="compare-metric-pill"><i class="fa-solid fa-location-dot"></i> ' + safeText(c.country_name) + '</span>' +
                '</th>';
            });
            html += '</tr></thead><tbody>';

            // Row 1: Degree Level
            html += '<tr><td class="compare-row-header">Degree Level</td>';
            courses.forEach(c => {
                const meta = getCourseMetaJS(c);
                html += '<td class="compare-col-course"><strong>' + meta.degree + '</strong></td>';
            });
            html += '</tr>';

            // Row 2: Annual Tuition Fee
            html += '<tr><td class="compare-row-header">Annual Tuition Fee</td>';
            courses.forEach(c => {
                const meta = getCourseMetaJS(c);
                html += '<td class="compare-col-course"><strong style="color:#0f172a; font-size:15px;">' + meta.tuition + '</strong></td>';
            });
            html += '</tr>';

            // Row 3: Duration
            html += '<tr><td class="compare-row-header">Duration</td>';
            courses.forEach(c => {
                const meta = getCourseMetaJS(c);
                html += '<td class="compare-col-course">' + safeText(meta.duration) + '</td>';
            });
            html += '</tr>';

            // Row 4: Next Intake
            html += '<tr><td class="compare-row-header">Upcoming Intakes</td>';
            courses.forEach(c => {
                const meta = getCourseMetaJS(c);
                html += '<td class="compare-col-course"><span style="color:#2563eb; font-weight:600;"><i class="fa-regular fa-calendar" style="margin-right:4px;"></i> ' + meta.intake + '</span></td>';
            });
            html += '</tr>';

            // Row 5: English & Entry Score
            html += '<tr><td class="compare-row-header">Language & Entry Score</td>';
            courses.forEach(c => {
                const meta = getCourseMetaJS(c);
                let waiverMarkup = meta.waiverEligible ? '<div style="margin-top:6px;"><span class="badge-waiver-pill" style="font-size:10px;"><i class="fa-solid fa-check"></i> MOI / IELTS Waiver Eligible</span></div>' : '';
                html += '<td class="compare-col-course"><strong>' + meta.entryScore + '</strong>' + waiverMarkup + '</td>';
            });
            html += '</tr>';

            // Row 6: Post-Study Work Visa (PSWV)
            html += '<tr><td class="compare-row-header">Post-Study Work Visa</td>';
            courses.forEach(c => {
                const meta = getCourseMetaJS(c);
                html += '<td class="compare-col-course"><span style="color:#059669; font-weight:700;"><i class="fa-solid fa-briefcase" style="margin-right:4px;"></i> ' + meta.pswv + '</span></td>';
            });
            html += '</tr>';

            // Row 7: Estimated Living Expenses
            html += '<tr><td class="compare-row-header">Est. Living Expenses</td>';
            courses.forEach(c => {
                const meta = getCourseMetaJS(c);
                html += '<td class="compare-col-course">' + meta.livingCost + '</td>';
            });
            html += '</tr>';

            // Row 8: Actions
            html += '<tr><td class="compare-row-header">Actions</td>';
            courses.forEach(c => {
                const meta = getCourseMetaJS(c);
                html += '<td class="compare-col-course">' +
                    '<button type="button" class="btn-premium-apply" style="margin-bottom:8px; padding:8px 12px; font-size:13px;" data-course-application ' +
                        'data-course-name="' + safeText(c.course_name) + '" ' +
                        'data-university-name="' + safeText(c.university_name) + '" ' +
                        'data-country-name="' + safeText(c.country_name) + '" ' +
                        'data-intake="' + safeText(meta.intake) + '">' +
                        '<i class="fa-solid fa-paper-plane"></i> Request to Apply' +
                    '</button>' +
                    '<button type="button" style="background:none; border:none; color:#ef4444; font-size:12px; font-weight:600; cursor:pointer; display:inline-flex; align-items:center; gap:4px;" onclick="removeCourseFromCompare(\'' + safeText(c.course_name).replace(/'/g, "\\'") + '\')">' +
                        '<i class="fa-regular fa-trash-can"></i> Remove' +
                    '</button>' +
                '</td>';
            });
            html += '</tr>';

            html += '</tbody></table></div>';
            wrap.innerHTML = html;
        }

        function compareShortlistedCourses() {
            fetch('student-shortlist-api.php', { credentials: 'same-origin' })
                .then(r => r.json())
                .then(data => {
                    if (!data.success || !data.courses || data.courses.length === 0) {
                        if (window.showDashboardToast) {
                            window.showDashboardToast('You have not shortlisted any courses yet. Heart courses in "Explore your courses" first!', { type: 'warning' });
                        }
                        return;
                    }
                    if (data.courses.length < 2) {
                        if (window.showDashboardToast) {
                            window.showDashboardToast('Please shortlist at least 2 courses to compare them side-by-side.', { type: 'info' });
                        }
                        return;
                    }

                    // Pre-fill comparison with top 3 shortlisted courses
                    window.selectedCompareCourses = data.courses.slice(0, 3).map(c => {
                        const meta = getCourseMetaJS(c);
                        return Object.assign({}, c, meta);
                    });

                    updateCompareDock();
                    openCompareModal();
                })
                .catch(err => {
                    console.error('Error fetching shortlist for compare', err);
                });
        }

        // Create a course card using DOM methods (no innerHTML for user data)
        function createCourseCard(pc, isSaved) {
            const card = document.createElement('div');
            card.className = 'course-card premium-course-card';

            const meta = getCourseMetaJS(pc);
            const degree = meta.degree;
            const country = safeText(pc.country_name || 'Global');
            const intake = meta.intake;
            const fee = meta.tuition;

            // Build card structure safely
            const header = document.createElement('div');
            header.className = 'premium-course-header';
            
            const badge = document.createElement('span');
            badge.className = 'course-badge';
            badge.innerHTML = '<i class="fa-solid fa-location-dot"></i> ';
            badge.appendChild(document.createTextNode(country));
            
            const heartBtn = document.createElement('i');
            heartBtn.className = isSaved ? 'fa-solid fa-heart course-card-fav' : 'fa-regular fa-heart course-card-fav';
            heartBtn.setAttribute('style', 'position:static;margin:0;cursor:pointer;font-size:20px;transition:all 0.2s;color:' + (isSaved ? '#d81e76' : '#94a3b8') + ';');
            heartBtn.addEventListener('click', function() {
                toggleShortlist(this, pc);
            });
            
            header.appendChild(badge);
            header.appendChild(heartBtn);
            card.appendChild(header);

            const title = document.createElement('h3');
            title.className = 'premium-course-title';
            title.textContent = safeText(pc.course_name);
            card.appendChild(title);

            const uniWrap = document.createElement('div');
            uniWrap.className = 'premium-course-uni';
            uniWrap.innerHTML = '<div class="uni-icon"><i class="fa-solid fa-building-columns"></i></div>';
            const uniName = document.createElement('span');
            uniName.textContent = safeText(pc.university_name);
            uniWrap.appendChild(uniName);
            card.appendChild(uniWrap);

            if (meta.waiverEligible) {
                const waiverDiv = document.createElement('div');
                waiverDiv.innerHTML = '<span class="badge-waiver-pill"><i class="fa-solid fa-certificate"></i> English Waiver Eligible (MOI)</span>';
                card.appendChild(waiverDiv);
            }

            const metrics = document.createElement('div');
            metrics.className = 'premium-course-metrics';
            const metricData = [
                { icon: 'fa-solid fa-graduation-cap', label: 'Degree', value: degree },
                { icon: 'fa-regular fa-calendar', label: 'Next Intake', value: intake },
                { icon: 'fa-solid fa-money-bill-wave', label: 'Tuition Fee', value: fee },
                { icon: 'fa-regular fa-circle-check', label: 'Entry Score', value: meta.entryScore }
            ];
            metricData.forEach(m => {
                const d = document.createElement('div');
                d.className = 'metric';
                d.innerHTML = '<i class="' + m.icon + '"></i>';
                const inner = document.createElement('div');
                const sm = document.createElement('small');
                sm.textContent = m.label;
                const st = document.createElement('strong');
                st.textContent = m.value;
                inner.appendChild(sm);
                inner.appendChild(st);
                d.appendChild(inner);
                metrics.appendChild(d);
            });
            card.appendChild(metrics);

            const pswvRow = document.createElement('div');
            pswvRow.setAttribute('style', 'font-size:12px; color:#64748b; margin-top:-4px; padding:6px 0; border-top:1px dashed #f1f5f9;');
            pswvRow.innerHTML = '<i class="fa-solid fa-briefcase" style="color:#2563eb; margin-right:4px;"></i> ' + safeText(meta.pswv);
            card.appendChild(pswvRow);

            // Action row with Compare + Request to Apply
            const actionsRow = document.createElement('div');
            actionsRow.className = 'course-card-actions';

            const compareBtn = document.createElement('button');
            compareBtn.type = 'button';
            compareBtn.className = 'btn-card-compare';
            compareBtn.dataset.courseCompareBtn = '';
            compareBtn.dataset.courseName = safeText(pc.course_name);
            const isComparing = window.selectedCompareCourses.some(sc => sc.course_name === pc.course_name);
            if (isComparing) {
                compareBtn.classList.add('is-comparing');
                compareBtn.innerHTML = '<i class="fa-solid fa-check"></i> Added';
            } else {
                compareBtn.innerHTML = '<i class="fa-solid fa-code-compare"></i> Compare';
            }
            compareBtn.addEventListener('click', function() {
                toggleCourseCompare(this, pc);
            });
            actionsRow.appendChild(compareBtn);

            const applyButton = document.createElement('button');
            applyButton.type = 'button';
            applyButton.className = 'btn-premium-apply';
            applyButton.dataset.courseApplication = '';
            applyButton.dataset.courseName = safeText(pc.course_name);
            applyButton.dataset.universityName = safeText(pc.university_name);
            applyButton.dataset.countryName = country;
            applyButton.dataset.intake = intake;
            applyButton.innerHTML = '<i class="fa-solid fa-paper-plane" aria-hidden="true"></i> Request to Apply';
            actionsRow.appendChild(applyButton);

            card.appendChild(actionsRow);

            return card;
        }

        document.addEventListener('click', async event => {
            const button = event.target.closest('[data-course-application]');
            if (!button || button.disabled) return;

            const courseName = button.dataset.courseName || '';
            const universityName = button.dataset.universityName || '';
            if (!await window.showDashboardToast(
                'Send a request to apply for ' + courseName + ' at ' + universityName +
                '? This will be added to your CRM remarks for your counselor. It will not submit an application to the university.',
                { confirm: true, confirmLabel: 'Send request' }
            )) return;
            button.disabled = true;
            button.disabled = true;
            window.showDashboardToast('Sending your request...', { duration: 10000 });
            try {
                const payload = new FormData();
                payload.append('csrf_token', CSRF_TOKEN);
                payload.append('action', 'request_application');
                payload.append('course_name', courseName);
                payload.append('university_name', universityName);
                payload.append('country_name', button.dataset.countryName || '');
                payload.append('intake', button.dataset.intake || '');

                const response = await fetch('student-course-application-api.php', {
                    method: 'POST',
                    credentials: 'same-origin',
                    body: payload
                });
                const result = await response.json();
                if (!response.ok || !result.success) {
                    throw new Error(result.message || 'Unable to send your request.');
                }
                window.showDashboardToast(result.message, { type: 'success' });
                button.innerHTML = '<i class="fa-solid fa-check" aria-hidden="true"></i> Request Sent';
            } catch (error) {
                window.showDashboardToast(error.message || 'Unable to send your request.', { type: 'error' });
                button.disabled = false;
            }
        });

        function toggleShortlist(btn, courseData) {
            if (typeof courseData === 'string') {
                courseData = JSON.parse(decodeURIComponent(courseData));
            }

            btn.classList.toggle('fa-regular');
            btn.classList.toggle('fa-solid');
            let isSaved = btn.classList.contains('fa-solid');
            btn.style.color = isSaved ? '#d81e76' : '#94a3b8';
            btn.style.transform = 'scale(1.3)';
            setTimeout(() => btn.style.transform = 'scale(1)', 200);

            // Server-side API call
            const formData = new FormData();
            formData.append('csrf_token', CSRF_TOKEN);
            formData.append('action', isSaved ? 'add' : 'remove');
            formData.append('course_name', courseData.course_name || '');
            formData.append('university_name', courseData.university_name || '');
            formData.append('country_name', courseData.country_name || '');
            formData.append('duration', courseData.duration || '');
            formData.append('tuition_fee', courseData.tuition_fee || '');
            formData.append('intakes', courseData.intakes || '');

            fetch('student-shortlist-api.php', { method: 'POST', body: formData, credentials: 'same-origin' })
                .then(r => r.json())
                .then(result => {
                    if (!result.success) console.warn('Shortlist API:', result.message);
                    loadShortlistView();
                })
                .catch(err => console.error('Shortlist error:', err));

            // Also keep localStorage in sync for instant UI
            let saved = JSON.parse(localStorage.getItem('savedCourses') || '[]');
            if (isSaved) {
                if (!saved.find(c => c.course_name === courseData.course_name)) saved.push(courseData);
            } else {
                saved = saved.filter(c => c.course_name !== courseData.course_name);
            }
            localStorage.setItem('savedCourses', JSON.stringify(saved));

            const counter = document.getElementById('shortlist-counter');
            if (counter) counter.textContent = saved.length + ' courses';
        }

        function loadShortlistView() {
            fetch('student-shortlist-api.php', { credentials: 'same-origin' })
                .then(r => r.json())
                .then(data => {
                    if (!data.success) return;
                    const grid = document.getElementById('shortlist-grid');
                    const empty = document.getElementById('empty-shortlist');
                    const counter = document.getElementById('shortlist-counter');
                    if (counter) counter.textContent = data.count + ' courses';
                    if (!grid) return;
                    grid.innerHTML = '';
                    if (data.courses.length === 0) {
                        grid.style.display = 'none';
                        if (empty) empty.style.display = 'block';
                    } else {
                        grid.style.display = 'grid';
                        if (empty) empty.style.display = 'none';
                        data.courses.forEach(pc => {
                            grid.appendChild(createCourseCard(pc, true));
                        });
                    }
                })
                .catch(() => {});
        }

        document.addEventListener('DOMContentLoaded', () => {
            loadShortlistView();
            updateCompareDock();

            // One-time sync: migrate localStorage to server
            const localCourses = JSON.parse(localStorage.getItem('savedCourses') || '[]');
            if (localCourses.length > 0 && !localStorage.getItem('shortlistSynced')) {
                const syncData = new FormData();
                syncData.append('csrf_token', CSRF_TOKEN);
                syncData.append('action', 'sync');
                syncData.append('courses', JSON.stringify(localCourses));
                fetch('student-shortlist-api.php', { method: 'POST', body: syncData, credentials: 'same-origin' })
                    .then(r => r.json())
                    .then(result => {
                        if (result.success) {
                            localStorage.setItem('shortlistSynced', '1');
                            loadShortlistView();
                        }
                    })
                    .catch(() => {});
            }

            // Sync explore page heart icons with server data
            fetch('student-shortlist-api.php', { credentials: 'same-origin' })
                .then(r => r.json())
                .then(data => {
                    if (!data.success) return;
                    const savedNames = data.courses.map(c => c.course_name);
                    document.querySelectorAll('.course-card-fav.explore-heart').forEach(btn => {
                        const courseName = btn.getAttribute('data-course');
                        if (savedNames.includes(courseName)) {
                            btn.classList.remove('fa-regular');
                            btn.classList.add('fa-solid');
                            btn.style.color = '#d81e76';
                        }
                    });
                })
                .catch(() => {});

            // Support URL sub-section routing for Application tab (e.g. ?tab=application&sub=offer-hub)
            const urlParams = new URLSearchParams(window.location.search);
            const sub = urlParams.get('sub');
            if (sub && document.getElementById('app-sec-' + sub)) {
                switchAppSubSection(sub);
            }
        });

        // ─── Multi-Application & Offer Letter Sub-section Switching ───
        function switchAppSubSection(sectionId, btn) {
            document.querySelectorAll('.app-sub-section').forEach(sec => sec.style.display = 'none');
            const target = document.getElementById('app-sec-' + sectionId);
            if (target) {
                target.style.display = 'block';
            }
            document.querySelectorAll('.app-sub-nav-btn').forEach(b => b.classList.remove('active'));
            if (btn) {
                btn.classList.add('active');
            } else {
                const matchingBtn = document.querySelector(`.app-sub-nav-btn[onclick*="'${sectionId}'"]`);
                if (matchingBtn) matchingBtn.classList.add('active');
            }
            // Scroll up to application section top if scrolled down
            const appView = document.getElementById('view-application');
            if (appView) {
                const topPos = appView.getBoundingClientRect().top + window.pageYOffset - 80;
                if (window.pageYOffset > topPos) {
                    window.scrollTo({ top: Math.max(0, topPos), behavior: 'smooth' });
                }
            }
        }

        async function handleAcceptOffer(appId) {
            let confirmed = false;
            if (window.showDashboardToast) {
                confirmed = await window.showDashboardToast(
                    'Confirm acceptance of your university offer? This will reserve your seat and notify your counselor to initiate your CAS and visa processing.',
                    { confirm: true, confirmLabel: 'Accept Offer' }
                );
            } else {
                confirmed = confirm('Confirm acceptance of your university offer? This will reserve your seat and notify your counselor.');
            }
            if (!confirmed) return;

            if (window.showDashboardToast) {
                window.showDashboardToast('Recording your offer acceptance...', { duration: 10000 });
            }

            const formData = new FormData();
            formData.append('csrf_token', CSRF_TOKEN);
            formData.append('action', 'accept_offer');
            formData.append('app_id', appId);

            try {
                const res = await fetch('student-offer-api.php', { method: 'POST', body: formData, credentials: 'same-origin' });
                const result = await res.json();
                if (result.success) {
                    if (window.showDashboardToast) {
                        window.showDashboardToast(result.message, { type: 'success', duration: 6000 });
                    } else {
                        alert(result.message);
                    }
                    setTimeout(() => window.location.reload(), 1500);
                } else {
                    if (window.showDashboardToast) {
                        window.showDashboardToast(result.message || 'Unable to accept offer.', { type: 'error' });
                    } else {
                        alert(result.message || 'Unable to accept offer.');
                    }
                }
            } catch (err) {
                if (window.showDashboardToast) {
                    window.showDashboardToast('Network error. Please try again.', { type: 'error' });
                } else {
                    alert('Network error. Please try again.');
                }
            }
        }

        async function handleRequestFeeDetails(appId) {
            if (window.showDashboardToast) {
                window.showDashboardToast('Requesting official university wire & payment details...', { duration: 10000 });
            }

            const formData = new FormData();
            formData.append('csrf_token', CSRF_TOKEN);
            formData.append('action', 'request_fee_details');
            formData.append('app_id', appId);

            try {
                const res = await fetch('student-offer-api.php', { method: 'POST', body: formData, credentials: 'same-origin' });
                const result = await res.json();
                if (result.success) {
                    if (window.showDashboardToast) {
                        window.showDashboardToast(result.message, { type: 'success', duration: 6000 });
                    } else {
                        alert(result.message);
                    }
                    const btn = document.getElementById('btn-fee-request-' + appId);
                    if (btn) {
                        btn.classList.add('requested');
                        btn.innerHTML = '<i class="fa-solid fa-check"></i> Fee Details Requested';
                        btn.disabled = true;
                    }
                } else {
                    if (window.showDashboardToast) {
                        window.showDashboardToast(result.message || 'Unable to request fee details.', { type: 'error' });
                    } else {
                        alert(result.message || 'Unable to request fee details.');
                    }
                }
            } catch (err) {
                if (window.showDashboardToast) {
                    window.showDashboardToast('Network error. Please try again.', { type: 'error' });
                } else {
                    alert('Network error. Please try again.');
                }
            }
        }

        (function () {
            const groupGrid = document.getElementById('community-groups-grid');
            const groupsLayout = document.getElementById('comm-groups');
            const groupList = document.getElementById('community-group-list');
            const chatPanel = document.getElementById('community-group-chat');
            const messagesPanel = document.getElementById('community-group-messages');
            const composeForm = document.getElementById('community-group-compose');
            const groupModal = document.getElementById('community-create-modal');
            const createForm = document.getElementById('community-create-form');
            const groupFeedback = document.getElementById('community-group-feedback');
            const chatFeedback = document.getElementById('community-chat-feedback');
            const groupSearch = document.getElementById('community-group-message-search');
            const groupNotificationMode = document.getElementById('community-group-notification-mode');
            const groupUploadStatus = document.getElementById('community-group-upload-status');
            const attachmentInput = document.getElementById('community-group-attachment');
            const attachmentButton = document.getElementById('community-group-attachment-button');
            const attachmentName = document.getElementById('community-group-attachment-name');
            const replyPreview = document.getElementById('community-group-reply-preview');
            const replyName = document.getElementById('community-group-reply-name');
            const replyText = document.getElementById('community-group-reply-text');
            const replyCancel = document.getElementById('community-group-reply-cancel');
            const createFeedback = document.getElementById('community-create-feedback');
            if (!groupGrid || !groupsLayout || !groupList || !chatPanel || !messagesPanel || !composeForm || !groupModal || !createForm || !chatFeedback || !attachmentInput || !attachmentButton || !attachmentName) return;

            let activeGroup = null;
            let lastMessageId = 0;
            let pollTimer = null;
            let polling = false;
            let replyToMessage = null;

            chatPanel.addEventListener('transitionend', event => {
                if (event.target === chatPanel && event.propertyName === 'flex-basis' && !groupsLayout.classList.contains('is-expanded')) {
                    chatPanel.hidden = true;
                }
            });

            function collapseChat() {
                if (pollTimer) window.clearInterval(pollTimer);
                pollTimer = null;
                clearGroupReply();
                activeGroup = null;
                chatPanel.setAttribute('aria-hidden', 'true');
                if (groupsLayout.classList.contains('is-expanded')) {
                    groupsLayout.classList.remove('is-expanded');
                } else {
                    chatPanel.hidden = true;
                }
            }

            function setFeedback(element, message, isError) {
                element.textContent = message;
                element.classList.toggle('error', Boolean(isError));
                element.classList.toggle('success', Boolean(message) && !isError);
            }

            function updateGroupsUnreadBadge(count) {
                const badge = document.getElementById('community-groups-unread-badge');
                if (!badge) return;
                badge.textContent = count > 99 ? '99+' : String(count);
                badge.hidden = count < 1;
            }

            async function refreshGroupUnreadCounts() {
                try {
                    const response = await fetch('student-group-chat-api.php?action=unread_counts', {
                        credentials: 'same-origin'
                    });
                    const result = await response.json();
                    if (!response.ok || !result.success) {
                        throw new Error(result.message || 'Unable to refresh group unread counts.');
                    }
                    groupGrid.querySelectorAll('[data-community-group]').forEach(card => {
                        const unreadBadge = card.querySelector('[data-group-unread]');
                        if (!unreadBadge) return;
                        const count = Number(result.unread_counts[card.dataset.groupId] || 0);
                        unreadBadge.textContent = count > 99 ? '99+' : String(count);
                        unreadBadge.hidden = count < 1;
                    });
                    updateGroupsUnreadBadge(Number(result.total_unread || 0));
                } catch (error) {
                    setFeedback(groupFeedback, error.message, true);
                    console.error('Group unread count refresh failed:', error);
                }
            }

            function groupMessagePreview(message) {
                if (message.message_type === 'deleted') return 'This message was deleted';
                if (message.message) return message.message;
                if (message.attachment_name) {
                    if (String(message.attachment_mime || '').startsWith('image/')) return 'Photo: ' + message.attachment_name;
                    if (String(message.attachment_mime || '').startsWith('video/')) return 'Video: ' + message.attachment_name;
                    return 'File: ' + message.attachment_name;
                }
                return 'Message';
            }

            function clearGroupReply() {
                replyToMessage = null;
                replyPreview.hidden = true;
                replyName.textContent = '';
                replyText.textContent = '';
            }

            function setGroupReply(message) {
                if (!message || message.message_type === 'deleted') return;
                replyToMessage = message;
                replyName.textContent = Number(message.user_id) === Number(<?php echo (int) $_SESSION['user_id']; ?>)
                    ? 'You'
                    : (message.sender_name || 'Student');
                replyText.textContent = groupMessagePreview(message);
                replyPreview.hidden = false;
                composeForm.querySelector('textarea').focus();
            }

            async function requestGroupApi(action, values, method) {
                const requestMethod = method || 'POST';
                const payload = Object.assign({ action: action }, values || {});
                const options = { method: requestMethod, credentials: 'same-origin' };
                let url = 'student-group-chat-api.php';
                if (requestMethod === 'GET') {
                    url += '?' + new URLSearchParams(payload).toString();
                } else {
                    payload.csrf_token = CSRF_TOKEN;
                    options.headers = { 'Content-Type': 'application/json' };
                    options.body = JSON.stringify(payload);
                }
                const response = await fetch(url, options);
                const result = await response.json();
                if (!response.ok || !result.success) {
                    throw new Error(result.message || 'Unable to complete this group action.');
                }
                return result;
            }

            function updateGroupCard(groupId, group, action) {
                let card = groupGrid.querySelector('[data-group-id="' + groupId + '"]');
                if (!card) {
                    card = document.createElement('article');
                    card.className = 'comm-group-card';
                    card.dataset.communityGroup = '';
                    card.dataset.groupId = String(groupId);
                    const content = document.createElement('div');
                    content.className = 'comm-group-content';
                    const title = document.createElement('h4');
                    title.textContent = group.name;
                    const description = document.createElement('p');
                    description.textContent = group.description || 'A place to connect with fellow students.';
                    const meta = document.createElement('div');
                    meta.className = 'community-group-card-meta';
                    const memberCount = document.createElement('span');
                    memberCount.dataset.memberCount = '';
                    memberCount.textContent = '1 member';
                    const unreadBadge = document.createElement('span');
                    unreadBadge.className = 'community-unread-badge';
                    unreadBadge.dataset.groupUnread = '';
                    unreadBadge.hidden = true;
                    const button = document.createElement('button');
                    button.type = 'button';
                    button.className = 'btn-join';
                    button.dataset.groupAction = action;
                    button.textContent = action === 'open' ? 'Open chat' : 'Join group';
                    meta.append(memberCount, unreadBadge);
                    content.append(title, description, meta, button);
                    card.appendChild(content);
                    groupGrid.prepend(card);
                } else {
                    const button = card.querySelector('[data-group-action]');
                    if (button) {
                        button.dataset.groupAction = action;
                        button.textContent = action === 'open' ? 'Open chat' : 'Join group';
                    }
                    const count = card.querySelector('[data-member-count]');
                    if (count && group.member_count !== undefined) {
                        count.textContent = group.member_count + (Number(group.member_count) === 1 ? ' member' : ' members');
                    }
                    if (!card.querySelector('[data-group-unread]')) {
                        const unreadBadge = document.createElement('span');
                        unreadBadge.className = 'community-unread-badge';
                        unreadBadge.dataset.groupUnread = '';
                        unreadBadge.hidden = true;
                        card.querySelector('.community-group-card-meta')?.appendChild(unreadBadge);
                    }
                }
                card.dataset.groupSearch = (
                    (group.name || card.querySelector('h4')?.textContent || '') + ' ' +
                    (group.description || card.querySelector('.comm-group-content p')?.textContent || '')
                ).toLocaleLowerCase();
                const emptyState = document.getElementById('community-groups-empty');
                if (emptyState) emptyState.remove();
                return card;
            }

            function appendMessages(newMessages) {
                const empty = messagesPanel.querySelector('.community-group-empty');
                if (empty && newMessages.length) empty.remove();
                newMessages.forEach(message => {
                    let insertionPoint = null;
                    const existingMessage = messagesPanel.querySelector(
                        '[data-message-id="' + Number(message.id) + '"]'
                    );
                    if (existingMessage) {
                        if (message.message_type === 'deleted' && !existingMessage.classList.contains('deleted')) {
                            insertionPoint = existingMessage.nextSibling;
                            existingMessage.remove();
                        } else if (message.edited_at && existingMessage.dataset.editedAt !== message.edited_at) {
                            const text = existingMessage.querySelector('.community-chat-message-text');
                            if (text) text.textContent = message.message;
                            existingMessage.dataset.editedAt = message.edited_at;
                            existingMessage._groupMessage = message;
                            let edited = existingMessage.querySelector('.community-message-edited');
                            if (!edited) {
                                edited = document.createElement('span');
                                edited.className = 'community-message-edited';
                                edited.textContent = 'edited';
                                existingMessage.querySelector('.community-chat-message-footer')?.prepend(edited);
                            }
                            return;
                        } else {
                            const readStatus = existingMessage.querySelector('.community-message-read-status');
                            if (readStatus && isCurrentUserMessage(message.user_id)) {
                                readStatus.textContent = Number(message.read_count || 0) > 0
                                    ? 'Read by ' + message.read_count
                                    : 'Sent';
                            }
                            return;
                        }
                    }

                    if (message.message_type === 'member_joined') {
                        const event = document.createElement('div');
                        event.className = 'community-chat-system-event';
                        event.append(document.createTextNode((message.sender_name || 'A member') + ' joined the group'));
                        const eventTime = document.createElement('time');
                        const eventDate = new Date(String(message.created_at).replace(' ', 'T'));
                        eventTime.textContent = Number.isNaN(eventDate.getTime())
                            ? ''
                            : eventDate.toLocaleTimeString([], { hour: 'numeric', minute: '2-digit' });
                        event.appendChild(eventTime);
                        messagesPanel.appendChild(event);
                        lastMessageId = Math.max(lastMessageId, Number(message.id) || 0);
                        return;
                    }

                    const bubble = document.createElement('article');
                    const isMine = Number(message.user_id) === Number(<?php echo (int) $_SESSION['user_id']; ?>);
                    bubble.className = 'community-chat-message' + (isMine ? ' mine' : '') +
                        (message.message_type === 'deleted' ? ' deleted' : '');
                    bubble.dataset.messageId = String(message.id);
                    bubble.dataset.editedAt = message.edited_at || '';
                    bubble._groupMessage = message;

                    if (message.message_type === 'deleted') {
                        const deletedText = document.createElement('div');
                        deletedText.textContent = isMine ? 'You deleted this message' : 'This message was deleted';
                        bubble.appendChild(deletedText);
                    } else if (!isMine) {
                        const sender = document.createElement('strong');
                        sender.className = 'community-chat-sender';
                        sender.textContent = message.sender_name || 'Student';
                        bubble.appendChild(sender);
                    }
                    if (message.message_type !== 'deleted' && message.reply_to) {
                        const replyQuote = document.createElement('button');
                        replyQuote.type = 'button';
                        replyQuote.className = 'community-chat-reply-quote';
                        replyQuote.dataset.scrollToMessage = String(message.reply_to.id);
                        const quotedName = document.createElement('strong');
                        quotedName.textContent = Number(message.reply_to.user_id) === Number(<?php echo (int) $_SESSION['user_id']; ?>)
                            ? 'You'
                            : (message.reply_to.sender_name || 'Student');
                        const quotedText = document.createElement('span');
                        quotedText.textContent = groupMessagePreview(message.reply_to);
                        replyQuote.append(quotedName, quotedText);
                        bubble.appendChild(replyQuote);
                    }
                    if (message.message_type !== 'deleted' && message.attachment) {
                        const attachment = message.attachment;
                        const attachmentWrap = document.createElement('div');
                        attachmentWrap.className = 'community-chat-attachment-wrap';
                        const downloadLink = document.createElement('a');
                        const downloadUrl = new URL(attachment.url, window.location.href);
                        downloadUrl.searchParams.set('download', '1');
                        downloadLink.className = 'community-chat-attachment-download';
                        downloadLink.href = downloadUrl.toString();
                        downloadLink.download = attachment.name;
                        downloadLink.title = 'Download ' + attachment.name;
                        downloadLink.setAttribute('aria-label', 'Download ' + attachment.name);
                        const downloadIcon = document.createElement('i');
                        downloadIcon.className = 'fa-solid fa-download';
                        downloadIcon.setAttribute('aria-hidden', 'true');
                        downloadLink.appendChild(downloadIcon);

                        if (attachment.mime.startsWith('image/')) {
                            const image = document.createElement('img');
                            image.className = 'community-chat-attachment';
                            image.src = attachment.url;
                            image.alt = attachment.name;
                            image.loading = 'lazy';
                            attachmentWrap.appendChild(image);
                        } else if (attachment.mime.startsWith('video/')) {
                            const video = document.createElement('video');
                            video.className = 'community-chat-attachment';
                            video.src = attachment.url;
                            video.controls = true;
                            video.preload = 'metadata';
                            video.setAttribute('aria-label', attachment.name);
                            attachmentWrap.appendChild(video);
                        } else {
                            const fileLink = document.createElement('a');
                            fileLink.className = 'community-chat-attachment';
                            fileLink.href = attachment.url;
                            fileLink.textContent = attachment.name;
                            fileLink.setAttribute('download', attachment.name);
                            attachmentWrap.appendChild(fileLink);
                        }
                        attachmentWrap.appendChild(downloadLink);
                        bubble.appendChild(attachmentWrap);
                    }
                    if (message.message_type !== 'deleted' && message.message) {
                        const text = document.createElement('div');
                        text.className = 'community-chat-message-text';
                        text.textContent = message.message;
                        bubble.appendChild(text);
                    }
                    const time = document.createElement('time');
                    time.className = 'community-chat-time';
                    const date = new Date(String(message.created_at).replace(' ', 'T'));
                    time.dateTime = Number.isNaN(date.getTime()) ? '' : date.toISOString();
                    time.textContent = Number.isNaN(date.getTime()) ? '' : date.toLocaleTimeString([], { hour: 'numeric', minute: '2-digit' });
                    const footer = document.createElement('div');
                    footer.className = 'community-chat-message-footer';
                    footer.appendChild(time);
                    if (message.edited_at) {
                        const edited = document.createElement('span');
                        edited.className = 'community-message-edited';
                        edited.textContent = 'edited';
                        footer.appendChild(edited);
                    }
                    if (isMine && message.message_type !== 'deleted') {
                        const readStatus = document.createElement('span');
                        readStatus.className = 'community-message-read-status';
                        readStatus.textContent = Number(message.read_count || 0) > 0
                            ? 'Read by ' + message.read_count
                            : 'Sent';
                        footer.appendChild(readStatus);
                    }
                    if (message.message_type !== 'deleted') {
                        const messageActions = document.createElement('span');
                        messageActions.className = 'community-chat-message-actions';
                        const replyButton = document.createElement('button');
                        replyButton.type = 'button';
                        replyButton.className = 'community-chat-reply';
                        replyButton.dataset.replyMessage = String(message.id);
                        replyButton.title = 'Reply to message';
                        replyButton.setAttribute('aria-label', 'Reply to message');
                        const replyIcon = document.createElement('i');
                        replyIcon.className = 'fa-solid fa-reply';
                        replyIcon.setAttribute('aria-hidden', 'true');
                        replyButton.appendChild(replyIcon);
                        messageActions.appendChild(replyButton);
                        if (isMine) {
                            const editButton = document.createElement('button');
                            editButton.type = 'button';
                            editButton.className = 'community-chat-delete';
                            editButton.dataset.editMessage = String(message.id);
                            editButton.title = 'Edit message';
                            editButton.setAttribute('aria-label', 'Edit message');
                            editButton.innerHTML = '<i class="fa-regular fa-pen-to-square" aria-hidden="true"></i>';
                            messageActions.appendChild(editButton);
                            const deleteButton = document.createElement('button');
                            deleteButton.type = 'button';
                            deleteButton.className = 'community-chat-delete';
                            deleteButton.dataset.deleteMessage = String(message.id);
                            deleteButton.title = 'Delete message for everyone';
                            deleteButton.setAttribute('aria-label', 'Delete message for everyone');
                            const deleteIcon = document.createElement('i');
                            deleteIcon.className = 'fa-regular fa-trash-can';
                            deleteIcon.setAttribute('aria-hidden', 'true');
                            deleteButton.appendChild(deleteIcon);
                            messageActions.appendChild(deleteButton);
                        } else {
                            const reportButton = document.createElement('button');
                            reportButton.type = 'button';
                            reportButton.className = 'community-chat-delete';
                            reportButton.dataset.reportGroupMessage = String(message.id);
                            reportButton.dataset.reportUserId = String(message.user_id);
                            reportButton.title = 'Report this message';
                            reportButton.setAttribute('aria-label', 'Report this message');
                            reportButton.innerHTML = '<i class="fa-regular fa-flag" aria-hidden="true"></i>';
                            messageActions.appendChild(reportButton);
                        }
                        footer.appendChild(messageActions);
                    }
                    bubble.appendChild(footer);
                    if (insertionPoint && insertionPoint.parentNode === messagesPanel) {
                        messagesPanel.insertBefore(bubble, insertionPoint);
                    } else {
                        messagesPanel.appendChild(bubble);
                    }
                    lastMessageId = Math.max(lastMessageId, Number(message.id) || 0);
                });
                applyGroupMessageSearch();
                if (newMessages.length) messagesPanel.scrollTop = messagesPanel.scrollHeight;
            }

            function applyGroupMessageSearch() {
                const query = groupSearch.value.trim().toLocaleLowerCase();
                messagesPanel.querySelectorAll('[data-message-id]').forEach(message => {
                    message.hidden = query !== '' && !message.textContent.toLocaleLowerCase().includes(query);
                });
            }

            function isCurrentUserMessage(messageUserId) {
                return Number(messageUserId) === Number(<?php echo (int) $_SESSION['user_id']; ?>);
            }

            messagesPanel.addEventListener('click', async event => {
                const replyButton = event.target.closest('[data-reply-message]');
                if (replyButton) {
                    const messageBubble = replyButton.closest('[data-message-id]');
                    if (messageBubble && messageBubble._groupMessage) setGroupReply(messageBubble._groupMessage);
                    return;
                }

                const replyQuote = event.target.closest('[data-scroll-to-message]');
                if (replyQuote) {
                    const targetMessage = messagesPanel.querySelector(
                        '[data-message-id="' + Number(replyQuote.dataset.scrollToMessage) + '"]'
                    );
                    if (targetMessage) {
                        targetMessage.scrollIntoView({ behavior: 'smooth', block: 'center' });
                        targetMessage.classList.add('highlighted');
                        window.setTimeout(() => targetMessage.classList.remove('highlighted'), 1200);
                    }
                    return;
                }

                const editButton = event.target.closest('[data-edit-message]');
                if (editButton && activeGroup) {
                    const bubble = editButton.closest('[data-message-id]');
                    const current = bubble?._groupMessage;
                    if (!current) return;
                    const editedMessage = window.prompt('Edit your message:', current.message || '');
                    if (editedMessage === null || editedMessage.trim() === '' || editedMessage.trim() === current.message) return;
                    editButton.disabled = true;
                    try {
                        const result = await requestGroupApi('edit', {
                            group_id: activeGroup.id,
                            message_id: current.id,
                            message: editedMessage.trim()
                        });
                        appendMessages([Object.assign({}, current, result.message)]);
                    } catch (error) {
                        setFeedback(chatFeedback, error.message, true);
                    } finally {
                        editButton.disabled = false;
                    }
                    return;
                }

                const reportButton = event.target.closest('[data-report-group-message]');
                if (reportButton && activeGroup) {
                    const reason = window.prompt('Why are you reporting this message?');
                    if (reason === null) return;
                    try {
                        const response = await fetch('student-community-friends-api.php', {
                            method: 'POST',
                            credentials: 'same-origin',
                            headers: { 'Content-Type': 'application/json' },
                            body: JSON.stringify({
                                csrf_token: CSRF_TOKEN,
                                action: 'report',
                                target_id: reportButton.dataset.reportUserId,
                                reason: reason.trim()
                            })
                        });
                        const result = await response.json();
                        if (!response.ok || !result.success) throw new Error(result.message || 'Unable to report this message.');
                        setFeedback(chatFeedback, 'Report submitted for review.', false);
                    } catch (error) {
                        setFeedback(chatFeedback, error.message, true);
                    }
                    return;
                }

                const deleteButton = event.target.closest('[data-delete-message]');
                if (!deleteButton || !activeGroup) return;
                const messageId = Number(deleteButton.dataset.deleteMessage);
                if (!Number.isInteger(messageId) || !await window.showDashboardToast('Delete this message for everyone?', { confirm: true, type: 'warning', confirmLabel: 'Delete' })) return;

                deleteButton.disabled = true;
                try {
                    const result = await requestGroupApi('delete', {
                        group_id: activeGroup.id,
                        message_id: messageId
                    });
                    const existingBubble = messagesPanel.querySelector(
                        '[data-message-id="' + messageId + '"]'
                    );
                    if (existingBubble) {
                        appendMessages([result.message]);
                    }
                } catch (error) {
                    setFeedback(chatFeedback, error.message, true);
                    deleteButton.disabled = false;
                }
            });

            async function loadMessages(initial) {
                if (!activeGroup || polling) return;
                polling = true;
                try {
                    const params = new URLSearchParams({
                        action: 'messages',
                        group_id: String(activeGroup.id),
                        after_id: initial ? '0' : String(lastMessageId)
                    });
                    const response = await fetch('student-group-chat-api.php?' + params.toString(), { credentials: 'same-origin' });
                    const result = await response.json();
                    if (!response.ok || !result.success) throw new Error(result.message || 'Unable to load group messages.');
                    if (!activeGroup || Number(result.group.id) !== Number(activeGroup.id)) return;
                    document.getElementById('community-chat-members').textContent =
                        result.group.member_count + (Number(result.group.member_count) === 1 ? ' member' : ' members');
                    if (result.messages.length) appendMessages(result.messages);
                    else if (initial && !messagesPanel.querySelector('.community-chat-message')) {
                        messagesPanel.innerHTML = '';
                        const empty = document.createElement('div');
                        empty.className = 'community-group-empty';
                        empty.textContent = 'No messages yet. Say hello to start the conversation!';
                        messagesPanel.appendChild(empty);
                    }
                    const readStatus = await requestGroupApi('read_status', {
                        group_id: activeGroup.id
                    }, 'GET');
                    readStatus.read_status.forEach(status => {
                        const bubble = messagesPanel.querySelector('[data-message-id="' + Number(status.id) + '"]');
                        const indicator = bubble?.querySelector('.community-message-read-status');
                        if (indicator) {
                            indicator.textContent = Number(status.read_count || 0) > 0
                                ? 'Read by ' + status.read_count
                                : 'Sent';
                        }
                    });
                    if (initial || result.messages.length) await refreshGroupUnreadCounts();
                } catch (error) {
                    if (initial) setFeedback(chatFeedback, error.message, true);
                    else console.error('Group message refresh failed:', error);
                } finally {
                    polling = false;
                }
            }

            function openChat(group, card) {
                clearGroupReply();
                activeGroup = { id: Number(group.id), name: group.name };
                lastMessageId = 0;
                if (pollTimer) window.clearInterval(pollTimer);
                document.getElementById('community-chat-title').textContent = group.name;
                groupSearch.value = '';
                groupNotificationMode.disabled = false;
                requestGroupApi('notification_preference', { group_id: Number(group.id) }, 'GET')
                    .then(result => { groupNotificationMode.value = result.notification_mode; })
                    .catch(error => setFeedback(chatFeedback, error.message, true));
                messagesPanel.innerHTML = '<div class="community-group-empty">Loading conversation...</div>';
                chatPanel.hidden = false;
                chatPanel.setAttribute('aria-hidden', 'false');
                setFeedback(chatFeedback, '', false);
                setFeedback(groupFeedback, '', false);
                loadMessages(true);
                pollTimer = window.setInterval(() => loadMessages(false), 4000);
                window.requestAnimationFrame(() => {
                    if (!activeGroup || activeGroup.id !== Number(group.id)) return;
                    groupsLayout.classList.add('is-expanded');
                    composeForm.querySelector('textarea').focus();
                });
                if (card) card.dataset.groupAction = 'open';
            }

            const requestedGroupId = Number(new URLSearchParams(window.location.search).get('group_id'));
            if (new URLSearchParams(window.location.search).get('community_tab') === 'groups' && requestedGroupId > 0) {
                const requestedGroupCard = groupGrid.querySelector(
                    '[data-group-id="' + requestedGroupId + '"]'
                );
                const requestedGroupAction = requestedGroupCard?.querySelector('[data-group-action]');
                if (requestedGroupAction?.dataset.groupAction === 'open') {
                    openChat({
                        id: requestedGroupId,
                        name: requestedGroupCard.querySelector('h4')?.textContent || 'Group chat'
                    }, requestedGroupCard);
                    window.requestAnimationFrame(() => chatPanel.scrollIntoView({ behavior: 'smooth', block: 'start' }));
                } else {
                    setFeedback(groupFeedback, 'That group is unavailable or you are no longer a member.', true);
                }
            }

            groupGrid.addEventListener('click', async event => {
                const button = event.target.closest('[data-group-action]');
                if (!button) return;
                const card = button.closest('[data-community-group]');
                if (!card) return;
                const groupId = Number(card.dataset.groupId);
                const title = card.querySelector('h4')?.textContent || 'Group chat';
                const description = card.querySelector('.comm-group-content p')?.textContent || '';
                const requestedAction = button.dataset.groupAction;
                button.disabled = true;
                try {
                    if (requestedAction === 'join') {
                        const result = await requestGroupApi('join', { group_id: groupId });
                        updateGroupCard(groupId, { name: title, description, member_count: result.member_count }, 'open');
                        openChat({ id: groupId, name: title }, card);
                    } else {
                        openChat({ id: groupId, name: title }, card);
                    }
                } catch (error) {
                    setFeedback(groupFeedback, error.message, true);
                } finally {
                    button.disabled = false;
                }
            });

            document.getElementById('community-group-back').addEventListener('click', () => {
                collapseChat();
            });

            document.getElementById('community-group-leave').addEventListener('click', async () => {
                if (!activeGroup || !await window.showDashboardToast('Leave this group? You can join again later.', { confirm: true, type: 'warning', confirmLabel: 'Leave group' })) return;
                const groupId = activeGroup.id;
                try {
                    const result = await requestGroupApi('leave', { group_id: groupId });
                    updateGroupCard(groupId, { member_count: result.member_count }, 'join');
                    collapseChat();
                } catch (error) {
                    setFeedback(chatFeedback, error.message, true);
                }
            });

            attachmentButton.addEventListener('click', () => attachmentInput.click());
            groupSearch.addEventListener('input', applyGroupMessageSearch);
            groupNotificationMode.addEventListener('change', async () => {
                if (!activeGroup) return;
                groupNotificationMode.disabled = true;
                try {
                    await requestGroupApi('notification_preference', {
                        group_id: activeGroup.id,
                        mode: groupNotificationMode.value
                    });
                    setFeedback(chatFeedback, 'Notification setting saved.', false);
                } catch (error) {
                    setFeedback(chatFeedback, error.message, true);
                } finally {
                    groupNotificationMode.disabled = false;
                }
            });
            attachmentInput.addEventListener('change', () => {
                const file = attachmentInput.files[0];
                attachmentName.textContent = file
                    ? file.name + ' (' + Math.ceil(file.size / 1024) + ' KB)'
                    : '';
                attachmentName.title = attachmentName.textContent;
                groupUploadStatus.textContent = '';
                groupUploadStatus.classList.remove('error');
            });
            replyCancel.addEventListener('click', clearGroupReply);

            function uploadGroupMessage(payload, file) {
                return new Promise((resolve, reject) => {
                    const xhr = new XMLHttpRequest();
                    xhr.open('POST', 'student-group-chat-api.php');
                    xhr.withCredentials = true;
                    xhr.upload.addEventListener('progress', event => {
                        if (file && event.lengthComputable) {
                            groupUploadStatus.textContent = 'Uploading attachment: ' +
                                Math.round((event.loaded / event.total) * 100) + '%';
                        }
                    });
                    xhr.addEventListener('load', () => {
                        let result;
                        try {
                            result = JSON.parse(xhr.responseText);
                        } catch (error) {
                            reject(new Error('The server returned an invalid response. Please retry.'));
                            return;
                        }
                        if (xhr.status < 200 || xhr.status >= 300 || !result.success) {
                            reject(new Error(result.message || 'Unable to send this group message.'));
                            return;
                        }
                        resolve(result);
                    });
                    xhr.addEventListener('error', () => reject(new Error('Upload failed. Your message is kept; press Send to retry.')));
                    xhr.addEventListener('abort', () => reject(new Error('Upload cancelled. Press Send to retry.')));
                    xhr.send(payload);
                });
            }

            composeForm.addEventListener('submit', async event => {
                event.preventDefault();
                if (!activeGroup) return;
                const textarea = composeForm.querySelector('textarea');
                const sendButton = composeForm.querySelector('[type="submit"]');
                const message = textarea.value.trim();
                const file = attachmentInput.files[0] || null;
                if (!message && !file) return;
                sendButton.disabled = true;
                groupUploadStatus.textContent = file ? 'Uploading attachment: 0%' : 'Sending message...';
                groupUploadStatus.classList.remove('error');
                try {
                    const payload = new FormData();
                    payload.append('action', 'send');
                    payload.append('csrf_token', CSRF_TOKEN);
                    payload.append('group_id', String(activeGroup.id));
                    payload.append('message', message);
                    if (replyToMessage) payload.append('reply_to_id', String(replyToMessage.id));
                    if (file) payload.append('attachment', file);
                    const result = await uploadGroupMessage(payload, file);
                    textarea.value = '';
                    attachmentInput.value = '';
                    attachmentName.textContent = '';
                    attachmentName.title = '';
                    clearGroupReply();
                    appendMessages([result.message]);
                    groupUploadStatus.textContent = '';
                } catch (error) {
                    setFeedback(chatFeedback, error.message, true);
                    groupUploadStatus.textContent = 'Not sent. Your message and attachment are kept; press Send to retry.';
                    groupUploadStatus.classList.add('error');
                } finally {
                    sendButton.disabled = false;
                    textarea.focus();
                }
            });

            document.getElementById('community-create-group').addEventListener('click', () => {
                groupModal.hidden = false;
                document.getElementById('community-create-name').focus();
            });
            document.getElementById('community-create-cancel').addEventListener('click', () => {
                groupModal.hidden = true;
                createForm.reset();
                setFeedback(createFeedback, '', false);
            });
            groupModal.addEventListener('click', event => {
                if (event.target === groupModal) document.getElementById('community-create-cancel').click();
            });
            createForm.addEventListener('submit', async event => {
                event.preventDefault();
                const submitButton = createForm.querySelector('[type="submit"]');
                const name = createForm.elements.name.value.trim();
                const description = createForm.elements.description.value.trim();
                submitButton.disabled = true;
                try {
                    const result = await requestGroupApi('create', { name, description });
                    const card = updateGroupCard(result.group.id, result.group, 'open');
                    groupModal.hidden = true;
                    createForm.reset();
                    openChat(result.group, card);
                } catch (error) {
                    setFeedback(createFeedback, error.message, true);
                } finally {
                    submitButton.disabled = false;
                }
            });

            document.getElementById('community-group-search').addEventListener('input', event => {
                const query = event.target.value.trim().toLocaleLowerCase();
                let visible = 0;
                groupGrid.querySelectorAll('[data-community-group]').forEach(card => {
                    const matches = (card.dataset.groupSearch || '').includes(query);
                    card.hidden = !matches;
                    if (matches) visible++;
                });
                const noResults = document.getElementById('community-groups-no-results');
                if (noResults) noResults.hidden = query === '' || visible > 0;
            });
            document.querySelector('[data-comm-target="comm-groups"]')?.addEventListener('click', refreshGroupUnreadCounts);
            refreshGroupUnreadCounts();
            window.setInterval(() => {
                if (!document.hidden) refreshGroupUnreadCounts();
            }, 15000);
        })();
    </script>

    <!-- Notification JS -->
    <script>
        function toggleNotifPanel() {
            const panel = document.getElementById('notif-panel');
            const isHidden = panel.style.display === 'none';
            panel.style.display = isHidden ? 'block' : 'none';
            if (isHidden) loadNotifications();
        }

        // Close panel on outside click
        document.addEventListener('click', function(e) {
            const wrap = document.getElementById('notif-bell-wrap');
            if (wrap && !wrap.contains(e.target)) {
                document.getElementById('notif-panel').style.display = 'none';
            }
        });

        function loadNotifications() {
            fetch('student-notifications-api.php?limit=15', { credentials: 'same-origin' })
                .then(r => {
                    if (!r.ok) throw new Error('Unable to refresh notifications.');
                    return r.json();
                })
                .then(data => {
                    if (!data.success) return;
                    updateCrmApplicationDetails(data.application, data.remark_history || []);
                    const list = document.getElementById('notif-list');
                    const badge = document.getElementById('notif-badge');

                    if (badge) {
                        if (data.unread_count > 0) {
                            badge.textContent = data.unread_count > 9 ? '9+' : data.unread_count;
                            badge.style.display = 'flex';
                        } else {
                            badge.style.display = 'none';
                        }
                    }

                    list.innerHTML = '';
                    if (data.notification_sync_failed) {
                        const warning = document.createElement('div');
                        warning.style.cssText = 'padding:10px 20px;color:#92400e;background:#fffbeb;font-size:12px;';
                        warning.textContent = 'Application details are refreshed, but CRM update notifications could not be synchronized.';
                        list.appendChild(warning);
                    }

                    if (!data.notifications || data.notifications.length === 0) {
                        const empty = document.createElement('div');
                        empty.style.cssText = 'padding: 30px; text-align: center; color: #94a3b8; font-size: 13px;';
                        empty.textContent = 'No notifications yet';
                        list.appendChild(empty);
                        return;
                    }

                    data.notifications.forEach(n => {
                        const item = document.createElement('a');
                        item.href = n.link || '#';
                        item.className = 'notif-item' + (n.is_read ? '' : ' unread');
                        item.innerHTML = `
                            <div class="notif-title">${n.title || ''}</div>
                            <div class="notif-desc">${n.message || ''}</div>
                            <div class="notif-time">${n.time_ago || n.created_at || ''}</div>
                        `;
                        item.addEventListener('click', () => markNotificationAsRead(n.id));
                        list.appendChild(item);
                    });
                })
                .catch(() => {});
        }

        function markNotificationAsRead(id) {
            const formData = new FormData();
            formData.append('csrf_token', CSRF_TOKEN);
            formData.append('action', 'mark_read');
            formData.append('notification_id', id);

            fetch('student-notifications-api.php', {
                method: 'POST',
                body: formData,
                credentials: 'same-origin'
            })
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    const badge = document.getElementById('notif-badge');
                    if (badge) badge.style.display = 'none';
                    loadNotifications();
                }
            })
            .catch(() => {});
        }

        loadNotifications();
        setInterval(() => {
            if (!document.hidden) loadNotifications();
        }, 30000);
    </script>

    <!-- ═══════════════ Document Management & Verification JS ═══════════════ -->
    <script>
        window.docDashboardData = null;
        window.docFilterStage = 'all';
        window.docFilterStatus = 'all';
        window.docSearchQuery = '';

        function escapeDocHtml(str) {
            if (!str) return '';
            return String(str)
                .replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;')
                .replace(/"/g, '&quot;')
                .replace(/'/g, '&#039;');
        }

        function loadDocuments() {
            fetch('student-documents.php?action=list', { credentials: 'same-origin' })
                .then(r => r.json())
                .then(data => {
                    if (!data.success) {
                        const cont = document.getElementById('doc-stages-container');
                        if (cont) {
                            cont.innerHTML = `
                                <div style="text-align:center; padding:50px 20px; background:white; border-radius:14px; border:1px solid #e2e8f0;">
                                    <i class="fa-solid fa-triangle-exclamation" style="font-size:36px; color:#f59e0b; margin-bottom:12px;"></i>
                                    <p style="color:#64748b; font-size:14px;">${data.message || 'Unable to load documents.'}</p>
                                </div>
                            `;
                        }
                        return;
                    }

                    window.docDashboardData = data;

                    // Update Top Stats & Overview
                    const stats = data.stats || {};
                    const totalUploaded = stats.total_uploaded || stats.total || 0;
                    const totalEl = document.getElementById('kpi-val-total');
                    const verEl = document.getElementById('kpi-val-verified');
                    const revEl = document.getElementById('kpi-val-review');
                    const reuEl = document.getElementById('kpi-val-reupload');
                    const pBar = document.getElementById('doc-progress-bar');
                    const pText = document.getElementById('doc-progress-text');
                    const countBadge = document.getElementById('doc-count-badge');

                    if (totalEl) totalEl.textContent = totalUploaded;
                    if (verEl) verEl.textContent = stats.verified || 0;
                    if (revEl) revEl.textContent = stats.under_review || 0;
                    if (reuEl) reuEl.textContent = stats.needs_reupload || 0;

                    const pct = stats.completion_percentage || 0;
                    if (pBar) pBar.style.width = pct + '%';
                    if (pText) pText.textContent = `${pct}% Certified (${stats.verified || 0} of ${stats.total || 11} Verified)`;

                    if (countBadge) countBadge.textContent = `${stats.verified || 0} / ${totalUploaded}`;

                    // Update sidebar/navbar document counter if present
                    document.querySelectorAll('.doc-badge-live-count').forEach(badge => {
                        badge.textContent = totalUploaded;
                    });

                    // Render Expiry Alerts
                    renderDocExpiryAlerts(data.expiry_alerts || []);

                    // Render Categorized Stages & Slots
                    renderDocStages();
                })
                .catch(err => {
                    console.error('Failed to load documents:', err);
                    const cont = document.getElementById('doc-stages-container');
                    if (cont) {
                        cont.innerHTML = `
                            <div style="text-align:center; padding:50px 20px; background:white; border-radius:14px; border:1px solid #e2e8f0;">
                                <i class="fa-solid fa-triangle-exclamation" style="font-size:36px; color:#ef4444; margin-bottom:12px;"></i>
                                <h3 style="font-size:16px; color:#0f172a; margin-bottom:6px;">Unable to Connect to Document Service</h3>
                                <p style="color:#64748b; font-size:14px; margin-bottom:16px;">Please refresh the page or contact your counselor.</p>
                                <button type="button" class="btn-doc-action btn-doc-upload" onclick="loadDocuments()"><i class="fa-solid fa-rotate"></i> Retry</button>
                            </div>
                        `;
                    }
                });
        }

        // ─── Render Expiry Alerts Banner ───
        function renderDocExpiryAlerts(alerts) {
            const container = document.getElementById('doc-expiry-alerts-container');
            if (!container) return;

            if (!alerts || alerts.length === 0) {
                container.innerHTML = '';
                return;
            }

            let html = '';
            alerts.forEach(alert => {
                const isCrit = alert.type === 'critical';
                const cardClass = isCrit ? 'doc-expiry-card critical' : 'doc-expiry-card warning';
                const iconClass = isCrit ? 'fa-solid fa-triangle-exclamation' : 'fa-solid fa-clock-rotate-left';

                html += `
                    <div class="${cardClass}">
                        <div class="doc-expiry-icon">
                            <i class="${iconClass}"></i>
                        </div>
                        <div class="doc-expiry-content">
                            <div class="doc-expiry-title">
                                <span>${escapeDocHtml(alert.title)}</span>
                                <span style="font-size:11px; padding:2px 8px; border-radius:6px; background:${isCrit ? '#fee2e2' : '#fef3c7'}; color:${isCrit ? '#dc2626' : '#d97706'}; font-weight:700;">
                                    ${isCrit ? 'IMMEDIATE ACTION REQUIRED' : 'VALIDITY NOTICE'}
                                </span>
                            </div>
                            <div class="doc-expiry-desc">
                                ${alert.message}
                            </div>
                            <button type="button" class="btn-expiry-action" onclick="triggerAlertReupload('${escapeDocHtml(alert.action_key)}')">
                                <i class="fa-solid fa-cloud-arrow-up"></i> ${escapeDocHtml(alert.action_label)}
                            </button>
                        </div>
                    </div>
                `;
            });

            container.innerHTML = html;
        }

        function triggerAlertReupload(docKey) {
            if (!window.docDashboardData) return;
            const categories = window.docDashboardData.categories || {};
            for (const catKey in categories) {
                const cat = categories[catKey];
                if (cat.docs && cat.docs[docKey]) {
                    const docDef = cat.docs[docKey];
                    const existing = (window.docDashboardData.documents_by_key || {})[docKey] || {};
                    openDocUploadModal(docKey, docDef.doc_label, cat.title, true, existing.admin_notes || '');
                    return;
                }
            }
        }

        // ─── Render Categorized Stages & Document Cards ───
        function renderDocStages() {
            const container = document.getElementById('doc-stages-container');
            if (!container || !window.docDashboardData) return;

            const categories = window.docDashboardData.categories || {};
            const docsByKey = window.docDashboardData.documents_by_key || {};
            const targetIntake = window.docDashboardData.target_intake || 'Sep 2026';

            let outputHtml = '';
            let totalRenderedSlots = 0;

            for (const catKey in categories) {
                // Filter by stage
                if (window.docFilterStage !== 'all' && window.docFilterStage !== catKey) {
                    continue;
                }

                const cat = categories[catKey];
                const docsList = cat.docs || {};

                // Filter matching docs in this category
                const matchingKeys = [];
                for (const dKey in docsList) {
                    const docDef = docsList[dKey];
                    const docData = docsByKey[dKey] || null;

                    // Status filter
                    if (window.docFilterStatus !== 'all') {
                        const status = docData ? (docData.status || '').toLowerCase() : 'missing';
                        if (window.docFilterStatus === 'reupload' && !['needs re-upload', 'rejected'].includes(status)) continue;
                        if (window.docFilterStatus === 'review' && status !== 'under review') continue;
                        if (window.docFilterStatus === 'verified' && status !== 'verified') continue;
                        if (window.docFilterStatus === 'missing' && docData) continue;
                    }

                    // Search query filter
                    if (window.docSearchQuery) {
                        const q = window.docSearchQuery.toLowerCase();
                        const titleMatch = (docDef.doc_label || '').toLowerCase().includes(q);
                        const descMatch = (docDef.desc || '').toLowerCase().includes(q);
                        const fileMatch = docData ? (docData.name || '').toLowerCase().includes(q) : false;
                        if (!titleMatch && !descMatch && !fileMatch) continue;
                    }

                    matchingKeys.push(dKey);
                }

                if (matchingKeys.length === 0 && (window.docFilterStatus !== 'all' || window.docSearchQuery)) {
                    continue;
                }

                totalRenderedSlots += matchingKeys.length;

                // Category stats
                let catVerified = 0;
                for (const dKey in docsList) {
                    if (docsByKey[dKey] && (docsByKey[dKey].status || '').trim().toLowerCase() === 'verified') {
                        catVerified++;
                    }
                }
                const catTotal = Object.keys(docsList).length;

                outputHtml += `
                    <div class="doc-stage-section" data-stage-id="${catKey}">
                        <div class="doc-stage-header">
                            <div class="doc-stage-title-wrap">
                                <div class="doc-stage-icon" style="background:${cat.color}15; color:${cat.color};">
                                    <i class="fa-solid ${cat.icon}"></i>
                                </div>
                                <div>
                                    <h2 class="doc-stage-title">${escapeDocHtml(cat.title)}</h2>
                                    <p class="doc-stage-desc">${escapeDocHtml(cat.desc)}</p>
                                </div>
                            </div>
                            <div class="doc-stage-meta">
                                <span class="doc-stage-count-badge">
                                    <i class="fa-solid fa-circle-check" style="color:#10b981;"></i> ${catVerified} / ${catTotal} Verified
                                </span>
                            </div>
                        </div>

                        <div class="doc-cards-grid">
                `;

                matchingKeys.forEach(dKey => {
                    const docDef = docsList[dKey];
                    const docData = docsByKey[dKey] || null;

                    outputHtml += renderSingleDocSlotCard(dKey, docDef, docData, cat, targetIntake);
                });

                outputHtml += `
                        </div>
                    </div>
                `;
            }

            if (totalRenderedSlots === 0) {
                container.innerHTML = `
                    <div style="text-align: center; padding: 60px 20px; background: white; border-radius: 16px; border: 1px solid #e2e8f0;">
                        <i class="fa-solid fa-filter-circle-xmark" style="font-size: 38px; color: #cbd5e1; margin-bottom: 12px;"></i>
                        <h3 style="font-size: 16px; color: #475569; margin-bottom: 6px;">No documents match your filter</h3>
                        <p style="color: #94a3b8; font-size: 13.5px; margin-bottom: 16px;">Try clearing your search query or selecting "All Stages".</p>
                        <button type="button" class="btn-doc-action btn-doc-view" onclick="resetDocFilters()">
                            <i class="fa-solid fa-rotate-left"></i> Reset Filters
                        </button>
                    </div>
                `;
            } else {
                container.innerHTML = outputHtml;
            }
        }

        // ─── Render Single Document Slot Card ───
        function renderSingleDocSlotCard(docKey, docDef, docData, cat, targetIntake) {
            const hasFile = docData && docData.file_url && docData.name && docData.name !== 'Not Uploaded';
            const rawStatus = docData ? (docData.status || 'Under Review') : 'Not Uploaded';

            let statusType = 'missing';
            let badgeClass = 'missing';
            let badgeLabel = 'Pending Upload';
            let badgeIcon = 'fa-solid fa-arrow-up-from-bracket';
            let cardStatusClass = 'status-missing';

            if (hasFile) {
                const normStatus = (rawStatus || '').trim().toLowerCase();
                if (normStatus === 'verified') {
                    statusType = 'verified';
                    badgeClass = 'verified';
                    badgeLabel = 'Verified';
                    badgeIcon = 'fa-solid fa-circle-check';
                    cardStatusClass = 'status-verified';
                } else if (normStatus === 'needs re-upload' || normStatus === 'rejected') {
                    statusType = 'reupload';
                    badgeClass = 'rejected';
                    badgeLabel = 'Needs Re-upload';
                    badgeIcon = 'fa-solid fa-circle-exclamation';
                    cardStatusClass = 'status-reupload';
                } else {
                    statusType = 'review';
                    badgeClass = 'pending';
                    badgeLabel = 'Under Review';
                    badgeIcon = 'fa-solid fa-clock';
                    cardStatusClass = 'status-review';
                }
            }

            // Expiry calculation
            let expiryHtml = '';
            if (docDef.has_expiry && docData && docData.expiry_date) {
                const expDate = docData.expiry_date;
                const expTs = new Date(expDate).getTime();
                const intakeTs = new Date(targetIntake.includes(' ') ? targetIntake : 'Sep 2026').getTime();
                const isExpired = expTs < intakeTs;

                if (isExpired) {
                    expiryHtml = `
                        <div class="doc-expiry-pill expired">
                            <i class="fa-solid fa-triangle-exclamation"></i> Expires: ${escapeDocHtml(expDate)} (Expires before intake!)
                        </div>
                    `;
                } else {
                    expiryHtml = `
                        <div class="doc-expiry-pill valid">
                            <i class="fa-solid fa-calendar-check"></i> Valid until: ${escapeDocHtml(expDate)}
                        </div>
                    `;
                }
            }

            // Counselor note callout
            let feedbackHtml = '';
            if (statusType === 'reupload' && docData && docData.admin_notes) {
                feedbackHtml = `
                    <div class="doc-counselor-feedback">
                        <div class="doc-feedback-header">
                            <span><i class="fa-solid fa-comment-dots"></i> Counselor's Feedback</span>
                            <span class="doc-feedback-author">${escapeDocHtml(docData.verified_by || 'Assigned Counselor')}</span>
                        </div>
                        <p class="doc-feedback-text">"${escapeDocHtml(docData.admin_notes)}"</p>
                    </div>
                `;
            }

            // Verified seal
            let verifiedSealHtml = '';
            if (statusType === 'verified') {
                const byWho = docData.verified_by || 'Meghana (Senior Advisor)';
                verifiedSealHtml = `
                    <div class="doc-verified-seal">
                        <i class="fa-solid fa-shield-check"></i> Certified by ${escapeDocHtml(byWho)}
                    </div>
                `;
            }

            // File icon based on extension
            let fileIcon = 'fa-file-pdf';
            const ext = (docData ? (docData.file_extension || '') : '').toLowerCase();
            if (['jpg', 'jpeg', 'png', 'webp'].includes(ext)) fileIcon = 'fa-file-image';
            else if (['doc', 'docx'].includes(ext)) fileIcon = 'fa-file-word';
            else if (['xls', 'xlsx'].includes(ext)) fileIcon = 'fa-file-excel';

            // Actions row
            let actionsHtml = '';
            if (hasFile) {
                const viewBtn = `
                    <button type="button" class="btn-doc-action btn-doc-view" onclick="openDocPreview('${escapeDocHtml(docData.file_url)}', '${escapeDocHtml(docData.name)}', '${escapeDocHtml(ext)}')">
                        <i class="fa-regular fa-eye"></i> View
                    </button>
                `;

                const counselorReviewBtn = `
                    <button type="button" class="btn-doc-action" style="padding:6px 9px; font-size:11.5px; background:#eff6ff; color:#1d4ed8; border:1px solid #bfdbfe;" title="Counselor Decision Simulation" onclick="openCounselorSimulatorModal('${docKey}', '${escapeDocHtml(docDef.doc_label)}')">
                        <i class="fa-solid fa-user-check"></i>
                    </button>
                `;

                if (statusType === 'reupload') {
                    actionsHtml = `
                        ${viewBtn}
                        <button type="button" class="btn-doc-action btn-doc-reupload" onclick="openDocUploadModal('${docKey}', '${escapeDocHtml(docDef.doc_label)}', '${escapeDocHtml(cat.title)}', true, '${escapeDocHtml(docData.admin_notes || '')}')">
                            <i class="fa-solid fa-cloud-arrow-up"></i> Re-upload Document
                        </button>
                        ${counselorReviewBtn}
                    `;
                } else if (statusType === 'verified') {
                    actionsHtml = `
                        ${viewBtn}
                        <span style="font-size:12px; color:#059669; font-weight:600; margin-left:auto; margin-right:8px;">
                            <i class="fa-solid fa-lock" style="font-size:11px;"></i> Locked (Verified)
                        </span>
                        ${counselorReviewBtn}
                    `;
                } else {
                    // Under Review
                    actionsHtml = `
                        ${viewBtn}
                        <button type="button" class="btn-doc-action btn-doc-view" style="flex:1;" onclick="openDocUploadModal('${docKey}', '${escapeDocHtml(docDef.doc_label)}', '${escapeDocHtml(cat.title)}', false)">
                            <i class="fa-solid fa-rotate"></i> Update File
                        </button>
                        ${counselorReviewBtn}
                        <button type="button" class="btn-doc-remove" title="Remove Document" onclick="deleteDocument(${docData.id}, '${docKey}')">
                            <i class="fa-regular fa-trash-can"></i>
                        </button>
                    `;
                }
            } else {
                // Missing
                actionsHtml = `
                    <button type="button" class="btn-doc-action btn-doc-upload" onclick="openDocUploadModal('${docKey}', '${escapeDocHtml(docDef.doc_label)}', '${escapeDocHtml(cat.title)}', false)">
                        <i class="fa-solid fa-cloud-arrow-up"></i> Upload Document
                    </button>
                    <button type="button" class="btn-doc-action" style="padding:7px 11px; font-size:12px; background:#f8fafc; color:#64748b; border:1px solid #e2e8f0;" title="Simulate Counselor Verification" onclick="openCounselorSimulatorModal('${docKey}', '${escapeDocHtml(docDef.doc_label)}')">
                        <i class="fa-solid fa-user-tie"></i>
                    </button>
                `;
            }

            return `
                <div class="doc-slot-card ${cardStatusClass}" id="slot-card-${docKey}">
                    <div>
                        <div class="doc-slot-top">
                            <div>
                                <h3 class="doc-slot-name">${escapeDocHtml(docDef.doc_label)}</h3>
                                <p class="doc-slot-desc">${escapeDocHtml(docDef.desc)}</p>
                            </div>
                            <span class="doc-badge ${badgeClass}">
                                <i class="${badgeIcon}"></i> ${badgeLabel}
                            </span>
                        </div>

                        ${hasFile ? `
                            <div class="doc-file-info">
                                <div class="doc-file-name-wrap">
                                    <i class="fa-solid ${fileIcon} doc-file-icon"></i>
                                    <span class="doc-file-name" title="${escapeDocHtml(docData.name)}">${escapeDocHtml(docData.name)}</span>
                                </div>
                                <span class="doc-file-size">${escapeDocHtml(docData.size || '')}</span>
                            </div>
                            <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:10px; font-size:11px; flex-wrap:wrap; gap:6px;">
                                ${docData.uploaded_by ? (
                                    (docData.uploaded_by.toLowerCase().includes('meghana') || docData.uploaded_by.toLowerCase().includes('counselor'))
                                        ? `<span style="display:inline-flex; align-items:center; gap:4px; padding:2px 8px; background:#f0fdf4; color:#166534; border:1px solid #bbf7d0; border-radius:6px; font-weight:600;"><i class="fa-solid fa-user-tie"></i> ${escapeDocHtml(docData.uploaded_by)}</span>`
                                        : `<span style="display:inline-flex; align-items:center; gap:4px; padding:2px 8px; background:#eff6ff; color:#1e40af; border:1px solid #bfdbfe; border-radius:6px; font-weight:600;"><i class="fa-solid fa-user"></i> Uploaded by Student</span>`
                                ) : `<span style="color:#64748b; font-size:11px;"><i class="fa-solid fa-check"></i> Dossier File</span>`}
                                <span style="color:#64748b; font-size:11px; display:inline-flex; align-items:center; gap:4px;"><i class="fa-solid fa-cloud-arrow-down" style="color:#2563eb;"></i> CRM Synced</span>
                            </div>
                        ` : `
                            <div style="padding:10px 14px; background:#f8fafc; border:1px dashed #cbd5e1; border-radius:10px; font-size:12px; color:#94a3b8; margin:12px 0; display:flex; align-items:center; gap:8px;">
                                <i class="fa-solid fa-arrow-up-from-bracket"></i>
                                <span>No file submitted yet. Formats: ${escapeDocHtml(docDef.accepted)}</span>
                            </div>
                        `}

                        ${expiryHtml}
                        ${feedbackHtml}
                        ${verifiedSealHtml}
                    </div>

                    <div class="doc-slot-actions">
                        ${actionsHtml}
                    </div>
                </div>
            `;
        }

        // ─── Filter & Search Handlers ───
        function filterDocStage(stageId, btn) {
            window.docFilterStage = stageId;
            document.querySelectorAll('.doc-stage-btn').forEach(b => b.classList.remove('active'));
            if (btn) btn.classList.add('active');
            renderDocStages();
        }

        function filterDocStatus(status, btn) {
            window.docFilterStatus = status;
            document.querySelectorAll('.doc-pill-btn').forEach(b => b.classList.remove('active'));
            if (btn) btn.classList.add('active');
            renderDocStages();
        }

        function handleDocSearch(query) {
            window.docSearchQuery = (query || '').trim();
            renderDocStages();
        }

        function resetDocFilters() {
            window.docFilterStage = 'all';
            window.docFilterStatus = 'all';
            window.docSearchQuery = '';
            const searchInput = document.getElementById('doc-search-input');
            if (searchInput) searchInput.value = '';

            document.querySelectorAll('.doc-stage-btn').forEach(b => b.classList.toggle('active', b.dataset.stage === 'all'));
            document.querySelectorAll('.doc-pill-btn').forEach(b => b.classList.toggle('active', b.dataset.status === 'all'));

            renderDocStages();
        }

        // ─── Modal Upload / Re-upload Logic ───
        function openDocUploadModal(docKey, docLabel, category, isReupload, counselorNote) {
            const modal = document.getElementById('doc-upload-modal');
            const titleEl = document.getElementById('modal-doc-title');
            const keyEl = document.getElementById('modal-field-doc-key');
            const catEl = document.getElementById('modal-field-category');
            const labelEl = document.getElementById('modal-field-doc-label');
            const slotCatEl = document.getElementById('modal-slot-category');
            const slotNameEl = document.getElementById('modal-slot-name');
            const slotDescEl = document.getElementById('modal-slot-desc');
            const feedbackAlert = document.getElementById('modal-feedback-alert');
            const feedbackText = document.getElementById('modal-feedback-text');
            const expiryGroup = document.getElementById('modal-expiry-group');
            const expiryInput = document.getElementById('modal-field-expiry');
            const fileSelectedPill = document.getElementById('modal-file-selected-pill');
            const form = document.getElementById('doc-upload-modal-form');
            const uploadFeedback = document.getElementById('doc-modal-upload-feedback');

            if (!modal) return;

            form.reset();
            uploadFeedback.style.display = 'none';
            uploadFeedback.textContent = '';
            fileSelectedPill.style.display = 'none';

            keyEl.value = docKey || '';
            catEl.value = category || '';
            labelEl.value = docLabel || '';

            slotCatEl.textContent = category || 'Application Stage';
            slotNameEl.textContent = docLabel || 'Document';

            // Find description from metadata
            let desc = 'Accepted: PDF, JPG, PNG, DOCX (Max size 7MB)';
            if (window.docDashboardData && window.docDashboardData.categories) {
                for (const ck in window.docDashboardData.categories) {
                    const c = window.docDashboardData.categories[ck];
                    if (c.docs && c.docs[docKey]) {
                        desc = c.docs[docKey].desc || desc;
                        break;
                    }
                }
            }
            slotDescEl.textContent = desc;

            if (isReupload) {
                titleEl.innerHTML = `<i class="fa-solid fa-rotate-right" style="color:#ef4444; margin-right:6px;"></i> Re-upload: ${escapeDocHtml(docLabel)}`;
                if (counselorNote) {
                    feedbackText.textContent = counselorNote;
                    feedbackAlert.style.display = 'block';
                } else {
                    feedbackAlert.style.display = 'none';
                }
            } else {
                titleEl.innerHTML = `<i class="fa-solid fa-cloud-arrow-up" style="color:#2563eb; margin-right:6px;"></i> Upload: ${escapeDocHtml(docLabel)}`;
                feedbackAlert.style.display = 'none';
            }

            // Expiry date input visibility
            if (['id_passport', 'test_english', 'test_standardized'].includes(docKey)) {
                expiryGroup.style.display = 'block';
                // Pre-fill existing expiry if any
                const existing = (window.docDashboardData?.documents_by_key || {})[docKey];
                if (existing && existing.expiry_date) {
                    expiryInput.value = existing.expiry_date;
                } else {
                    expiryInput.value = '';
                }
            } else {
                expiryGroup.style.display = 'none';
                expiryInput.value = '';
            }

            modal.style.display = 'flex';
        }

        function closeDocUploadModal() {
            const modal = document.getElementById('doc-upload-modal');
            if (modal) modal.style.display = 'none';
        }

        function handleModalFileSelect(input) {
            const pill = document.getElementById('modal-file-selected-pill');
            const nameEl = document.getElementById('modal-file-selected-name');
            const sizeEl = document.getElementById('modal-file-selected-size');

            if (input.files && input.files[0]) {
                const file = input.files[0];
                nameEl.textContent = file.name;
                const sizeMb = (file.size / (1024 * 1024)).toFixed(2);
                sizeEl.textContent = `${sizeMb} MB`;
                pill.style.display = 'flex';
            } else {
                pill.style.display = 'none';
            }
        }

        // Modal Form Submit
        const modalUploadForm = document.getElementById('doc-upload-modal-form');
        if (modalUploadForm) {
            modalUploadForm.addEventListener('submit', async function(e) {
                e.preventDefault();

                const submitBtn = document.getElementById('btn-modal-submit');
                const feedbackEl = document.getElementById('doc-modal-upload-feedback');
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Submitting...';
                feedbackEl.style.display = 'none';

                const formData = new FormData(this);

                try {
                    const res = await fetch('student-documents.php', {
                        method: 'POST',
                        body: formData,
                        credentials: 'same-origin'
                    });
                    const result = await res.json();

                    if (result.success) {
                        feedbackEl.style.display = 'block';
                        feedbackEl.style.color = '#059669';
                        feedbackEl.innerHTML = `<i class="fa-solid fa-circle-check"></i> ${escapeDocHtml(result.message)}`;

                        if (window.showDashboardToast) {
                            window.showDashboardToast(result.message, { type: 'success', duration: 4000 });
                        }

                        setTimeout(() => {
                            closeDocUploadModal();
                            loadDocuments();
                        }, 1200);
                    } else {
                        feedbackEl.style.display = 'block';
                        feedbackEl.style.color = '#dc2626';
                        feedbackEl.innerHTML = `<i class="fa-solid fa-circle-exclamation"></i> ${escapeDocHtml(result.message || 'Upload failed.')}`;
                        submitBtn.disabled = false;
                        submitBtn.innerHTML = '<i class="fa-solid fa-cloud-arrow-up"></i> Upload for Verification';
                    }
                } catch (err) {
                    feedbackEl.style.display = 'block';
                    feedbackEl.style.color = '#dc2626';
                    feedbackEl.innerHTML = `<i class="fa-solid fa-triangle-exclamation"></i> Network error. Please try again.`;
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = '<i class="fa-solid fa-cloud-arrow-up"></i> Upload for Verification';
                }
            });
        }

        // ─── Preview Modal ───
        function openDocPreview(url, fileName, ext) {
            const modal = document.getElementById('doc-preview-modal');
            const titleEl = document.getElementById('preview-doc-title');
            const downloadBtn = document.getElementById('preview-doc-download-btn');
            const bodyEl = document.getElementById('preview-doc-body');

            if (!modal) return;

            titleEl.textContent = fileName || 'Document Preview';
            downloadBtn.href = url;
            downloadBtn.setAttribute('download', fileName || 'document');

            const lowerExt = (ext || '').toLowerCase();
            if (['jpg', 'jpeg', 'png', 'webp', 'gif'].includes(lowerExt)) {
                bodyEl.innerHTML = `
                    <div style="text-align:center;">
                        <img src="${url}" alt="${escapeDocHtml(fileName)}" style="max-width:100%; max-height:70vh; border-radius:8px; box-shadow:0 4px 20px rgba(0,0,0,0.15);">
                    </div>
                `;
            } else if (lowerExt === 'pdf') {
                bodyEl.innerHTML = `
                    <iframe src="${url}#toolbar=0" style="width:100%; height:70vh; border:none; border-radius:8px;"></iframe>
                `;
            } else {
                bodyEl.innerHTML = `
                    <div style="text-align:center; padding:40px;">
                        <i class="fa-solid fa-file-lines" style="font-size:48px; color:#2563eb; margin-bottom:16px;"></i>
                        <h4 style="color:#0f172a; margin-bottom:8px;">${escapeDocHtml(fileName)}</h4>
                        <p style="color:#64748b; font-size:13px; margin-bottom:18px;">Inline preview is not available for this file format.</p>
                        <a href="${url}" download="${escapeDocHtml(fileName)}" class="btn-doc-action btn-doc-upload">
                            <i class="fa-solid fa-download"></i> Download to View
                        </a>
                    </div>
                `;
            }

            modal.style.display = 'flex';
        }

        function closeDocPreviewModal() {
            const modal = document.getElementById('doc-preview-modal');
            if (modal) {
                modal.style.display = 'none';
                const bodyEl = document.getElementById('preview-doc-body');
                if (bodyEl) bodyEl.innerHTML = '';
            }
        }

        // ─── Delete Document ───
        async function deleteDocument(docId, docKey) {
            let confirmed = false;
            if (window.showDashboardToast) {
                confirmed = await window.showDashboardToast(
                    'Remove this document file? You can upload a new one at any time.',
                    { confirm: true, type: 'warning', confirmLabel: 'Remove File' }
                );
            } else {
                confirmed = confirm('Remove this document file?');
            }
            if (!confirmed) return;

            const formData = new FormData();
            formData.append('csrf_token', CSRF_TOKEN);
            formData.append('action', 'delete');
            if (docId) formData.append('doc_id', docId);
            if (docKey) formData.append('doc_key', docKey);

            try {
                const res = await fetch('student-documents.php', {
                    method: 'POST',
                    body: formData,
                    credentials: 'same-origin'
                });
                const result = await res.json();
                if (result.success) {
                    if (window.showDashboardToast) {
                        window.showDashboardToast(result.message, { type: 'success', duration: 3000 });
                    }
                    loadDocuments();
                } else {
                    if (window.showDashboardToast) {
                        window.showDashboardToast(result.message || 'Unable to remove document.', { type: 'error' });
                    }
                }
            } catch (err) {
                if (window.showDashboardToast) {
                    window.showDashboardToast('Network error while deleting.', { type: 'error' });
                }
            }
        }

        // ─── Counselor Review Simulator Functions ───
        function openCounselorSimulatorModal(docKey, docLabel) {
            const modal = document.getElementById('counselor-sim-modal');
            if (!modal) return;
            const selectEl = document.getElementById('counselor-doc-select');
            if (selectEl && docKey) {
                let exists = false;
                for (let i = 0; i < selectEl.options.length; i++) {
                    if (selectEl.options[i].value === docKey) {
                        selectEl.selectedIndex = i;
                        exists = true;
                        break;
                    }
                }
                if (!exists && docLabel) {
                    const opt = document.createElement('option');
                    opt.value = docKey;
                    opt.textContent = docLabel;
                    selectEl.appendChild(opt);
                    selectEl.value = docKey;
                }
            }
            const feedbackEl = document.getElementById('counselor-modal-feedback');
            if (feedbackEl) feedbackEl.style.display = 'none';
            modal.style.display = 'flex';
        }

        function closeCounselorSimulatorModal() {
            const modal = document.getElementById('counselor-sim-modal');
            if (modal) modal.style.display = 'none';
        }

        function handleDecisionChange(val) {
            const labelVerified = document.getElementById('decision-label-verified');
            const labelReupload = document.getElementById('decision-label-reupload');
            const notesInput = document.getElementById('counselor-notes-input');

            if (val === 'Verified') {
                if (labelVerified) {
                    labelVerified.style.borderColor = '#10b981';
                    labelVerified.style.background = '#ecfdf5';
                }
                if (labelReupload) {
                    labelReupload.style.borderColor = '#e2e8f0';
                    labelReupload.style.background = '#ffffff';
                }
                if (notesInput && !notesInput.value) {
                    notesInput.placeholder = 'Optional note: Verified & certified authentic.';
                }
            } else {
                if (labelReupload) {
                    labelReupload.style.borderColor = '#ef4444';
                    labelReupload.style.background = '#fef2f2';
                }
                if (labelVerified) {
                    labelVerified.style.borderColor = '#e2e8f0';
                    labelVerified.style.background = '#ffffff';
                }
                if (notesInput && !notesInput.value) {
                    notesInput.placeholder = 'State exact reason for re-upload (e.g. Passport copy blurred, missing seal)...';
                }
            }
        }

        function applyCounselorFeedback(text) {
            const notesInput = document.getElementById('counselor-notes-input');
            if (notesInput) {
                notesInput.value = text;
                if (text.includes('blurred') || text.includes('cropped') || text.includes('missing')) {
                    const radio = document.querySelector('input[name="decision"][value="Needs Re-upload"]');
                    if (radio) {
                        radio.checked = true;
                        handleDecisionChange('Needs Re-upload');
                    }
                } else if (text.includes('Verified')) {
                    const radio = document.querySelector('input[name="decision"][value="Verified"]');
                    if (radio) {
                        radio.checked = true;
                        handleDecisionChange('Verified');
                    }
                }
            }
        }

        async function submitCounselorVerification(e) {
            e.preventDefault();
            const btn = document.getElementById('btn-counselor-sim-submit');
            const feedbackEl = document.getElementById('counselor-modal-feedback');
            const docKey = document.getElementById('counselor-doc-select').value;
            const counselorName = document.getElementById('counselor-name-input').value;
            const decisionRadio = document.querySelector('input[name="decision"]:checked');
            const decision = decisionRadio ? decisionRadio.value : 'Verified';
            const notes = document.getElementById('counselor-notes-input').value;

            btn.disabled = true;
            btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Triggering CRM Flow...';

            try {
                const formData = new FormData();
                formData.append('action', 'verify_decision');
                formData.append('doc_key', docKey);
                formData.append('decision', decision);
                formData.append('counselor_name', counselorName);
                formData.append('counselor_notes', notes);

                const res = await fetch('student-document-verify-api.php', {
                    method: 'POST',
                    body: formData,
                    credentials: 'same-origin'
                });
                const result = await res.json();

                if (result.success) {
                    feedbackEl.style.display = 'block';
                    feedbackEl.style.color = '#059669';
                    feedbackEl.innerHTML = `<i class="fa-solid fa-circle-check"></i> ${escapeDocHtml(result.message)}`;
                    
                    if (window.showDashboardToast) {
                        window.showDashboardToast(result.message, { type: 'success', duration: 4000 });
                    }
                    setTimeout(() => {
                        closeCounselorSimulatorModal();
                        loadDocuments();
                    }, 1000);
                } else {
                    feedbackEl.style.display = 'block';
                    feedbackEl.style.color = '#dc2626';
                    feedbackEl.innerHTML = `<i class="fa-solid fa-circle-exclamation"></i> ${escapeDocHtml(result.message || 'Verification update failed.')}`;
                }
            } catch (err) {
                feedbackEl.style.display = 'block';
                feedbackEl.style.color = '#dc2626';
                feedbackEl.innerHTML = `<i class="fa-solid fa-circle-exclamation"></i> Network error connecting to CRM verification service.`;
            } finally {
                btn.disabled = false;
                btn.innerHTML = '<i class="fa-solid fa-check-double"></i> Save Decision & Trigger Flow';
            }
        }

        // ─── Upload Any Document Handlers ───
        function openUploadAnyDocModal() {
            const modal = document.getElementById('upload-any-doc-modal');
            if (!modal) return;
            const form = document.getElementById('upload-any-doc-form');
            if (form) form.reset();
            const feedbackEl = document.getElementById('any-doc-upload-feedback');
            if (feedbackEl) feedbackEl.style.display = 'none';
            const filePill = document.getElementById('any-doc-file-pill');
            if (filePill) filePill.style.display = 'none';
            const customGroup = document.getElementById('any-doc-custom-title-group');
            if (customGroup) customGroup.style.display = 'none';
            const catGroup = document.getElementById('any-doc-custom-cat-group');
            if (catGroup) catGroup.style.display = 'none';
            modal.style.display = 'flex';
        }

        function closeUploadAnyDocModal() {
            const modal = document.getElementById('upload-any-doc-modal');
            if (modal) modal.style.display = 'none';
        }

        function handleAnyDocTypeChange(val) {
            const customTitleGroup = document.getElementById('any-doc-custom-title-group');
            const customCatGroup = document.getElementById('any-doc-custom-cat-group');
            const fieldKey = document.getElementById('any-doc-field-key');
            const fieldCat = document.getElementById('any-doc-field-category');
            const fieldLabel = document.getElementById('any-doc-field-label');

            if (!val) return;

            if (val.startsWith('custom')) {
                if (customTitleGroup) customTitleGroup.style.display = 'block';
                if (customCatGroup) customCatGroup.style.display = 'block';
                if (fieldKey) fieldKey.value = '';
                if (fieldCat) fieldCat.value = document.getElementById('any-doc-custom-cat-select')?.value || 'Additional & CRM Documents';
                if (fieldLabel) fieldLabel.value = '';
            } else {
                if (customTitleGroup) customTitleGroup.style.display = 'none';
                if (customCatGroup) customCatGroup.style.display = 'none';
                const parts = val.split('|');
                if (parts.length >= 3) {
                    if (fieldKey) fieldKey.value = parts[0];
                    if (fieldCat) fieldCat.value = parts[1];
                    if (fieldLabel) fieldLabel.value = parts[2];
                }
            }
        }

        function handleAnyDocFileSelect(input) {
            const filePill = document.getElementById('any-doc-file-pill');
            const nameEl = document.getElementById('any-doc-file-name');
            const sizeEl = document.getElementById('any-doc-file-size');

            if (input.files && input.files[0]) {
                const file = input.files[0];
                let sz = file.size < 1048576 ? Math.round(file.size / 1024) + ' KB' : (file.size / 1048576).toFixed(1) + ' MB';
                if (nameEl) nameEl.textContent = file.name;
                if (sizeEl) sizeEl.textContent = sz;
                if (filePill) filePill.style.display = 'flex';
            }
        }

        async function submitAnyDocumentUpload(e) {
            e.preventDefault();
            const btn = document.getElementById('btn-any-doc-submit');
            const feedbackEl = document.getElementById('any-doc-upload-feedback');
            const typeSelect = document.getElementById('any-doc-select-type').value;

            if (!typeSelect) {
                feedbackEl.style.display = 'block';
                feedbackEl.style.color = '#dc2626';
                feedbackEl.innerHTML = `<i class="fa-solid fa-circle-exclamation"></i> Please select a document type.`;
                return;
            }

            const fieldKey = document.getElementById('any-doc-field-key');
            const fieldCat = document.getElementById('any-doc-field-category');
            const fieldLabel = document.getElementById('any-doc-field-label');

            if (typeSelect.startsWith('custom')) {
                const customTitle = document.getElementById('any-doc-custom-title-input').value.trim();
                if (!customTitle) {
                    feedbackEl.style.display = 'block';
                    feedbackEl.style.color = '#dc2626';
                    feedbackEl.innerHTML = `<i class="fa-solid fa-circle-exclamation"></i> Please enter a custom document title.`;
                    return;
                }
                const customCat = document.getElementById('any-doc-custom-cat-select').value;
                if (fieldLabel) fieldLabel.value = customTitle;
                if (fieldCat) fieldCat.value = customCat;
                if (fieldKey) fieldKey.value = 'custom_' + customTitle.toLowerCase().replace(/[^a-z0-9_]/g, '_');
            }

            const form = document.getElementById('upload-any-doc-form');
            const formData = new FormData(form);

            btn.disabled = true;
            btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Uploading & Syncing with CRM...';
            feedbackEl.style.display = 'none';

            try {
                const res = await fetch('student-documents.php', {
                    method: 'POST',
                    body: formData,
                    credentials: 'same-origin'
                });
                const result = await res.json();

                if (result.success) {
                    feedbackEl.style.display = 'block';
                    feedbackEl.style.color = '#059669';
                    feedbackEl.innerHTML = `<i class="fa-solid fa-circle-check"></i> ${escapeDocHtml(result.message || 'Document uploaded and synced successfully!')}`;

                    if (window.showDashboardToast) {
                        window.showDashboardToast(result.message || 'Document uploaded & synced with CRM!', { type: 'success', duration: 4000 });
                    }
                    setTimeout(() => {
                        closeUploadAnyDocModal();
                        loadDocuments();
                    }, 1000);
                } else {
                    feedbackEl.style.display = 'block';
                    feedbackEl.style.color = '#dc2626';
                    feedbackEl.innerHTML = `<i class="fa-solid fa-circle-exclamation"></i> ${escapeDocHtml(result.message || 'Upload failed.')}`;
                }
            } catch (err) {
                feedbackEl.style.display = 'block';
                feedbackEl.style.color = '#dc2626';
                feedbackEl.innerHTML = `<i class="fa-solid fa-circle-exclamation"></i> Network error during document upload.`;
            } finally {
                btn.disabled = false;
                btn.innerHTML = '<i class="fa-solid fa-cloud-arrow-up"></i> Upload & Sync with CRM';
            }
        }

        // Initialize Documents on DOM ready
        document.addEventListener('DOMContentLoaded', function() {
            loadDocuments();
        });
    </script>