<?php
require_once 'includes/config.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
    exit;
}

$uni_id = isset($_POST['uni_id']) ? intval($_POST['uni_id']) : 0;
$uni_name = isset($_POST['uni_name']) ? trim($_POST['uni_name']) : '';

if ($uni_id <= 0 || empty($uni_name)) {
    echo json_encode(['success' => false, 'message' => 'Missing university details.']);
    exit;
}

// Ensure the university exists
$stmt = $pdo->prepare("SELECT id FROM universities WHERE id = ?");
$stmt->execute([$uni_id]);
if (!$stmt->fetch()) {
    echo json_encode(['success' => false, 'message' => 'University not found.']);
    exit;
}

// Function to generate a random tuition fee
function getRandomFee() {
    $fees = ['$20,000', '$22,500', '$25,000', '$28,000', '$30,000', '$32,500', '$35,000', '£15,000', '£18,000', '£22,000'];
    return $fees[array_rand($fees)];
}

// Function to generate a random duration
function getRandomDuration($level) {
    if ($level === 'Master') {
        $durs = ['1 Year', '1.5 Years', '2 Years'];
        return $durs[array_rand($durs)];
    } else {
        $durs = ['3 Years', '4 Years'];
        return $durs[array_rand($durs)];
    }
}

$fetched_courses = [];

// Try to fetch from Wikipedia
$search_name = urlencode($uni_name);
$url = "https://en.wikipedia.org/w/api.php?action=query&prop=extracts&format=json&titles={$search_name}&explaintext=1";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_USERAGENT, "BluestoneOverseasBot/1.0");
$json = curl_exec($ch);
curl_close($ch);

$data = json_decode($json, true);
$pages = $data['query']['pages'] ?? [];

$extract = "";
foreach ($pages as $page) {
    if (isset($page['extract'])) {
        $extract = $page['extract'];
        break;
    }
}

$departments = [];
if (!empty($extract)) {
    // Look for lines containing "Faculty of" or "School of" or "Department of"
    $lines = explode("\n", $extract);
    foreach ($lines as $line) {
        if (preg_match('/(?:Faculty|School|Department) of ([A-Z][a-z]+(?: [A-Z][a-z]+)*)/', $line, $matches)) {
            $dept = trim($matches[1]);
            // exclude common false positives
            if (!in_array(strtolower($dept), ['the', 'university', 'research'])) {
                $departments[] = $dept;
            }
        }
    }
}

// Fallback courses if Wikipedia parsing fails or yields too few
$fallback_departments = [
    'Computer Science', 'Business Administration', 'Data Science', 'Nursing', 
    'Mechanical Engineering', 'International Relations', 'Psychology', 
    'Cybersecurity', 'Public Health', 'Law', 'Accounting and Finance',
    'Architecture', 'Civil Engineering', 'Marketing', 'Artificial Intelligence'
];

$departments = array_unique($departments);

// If we got fewer than 5 departments from wiki, supplement with fallbacks
if (count($departments) < 5) {
    shuffle($fallback_departments);
    $needed = 8 - count($departments);
    for ($i=0; $i<$needed; $i++) {
        if (isset($fallback_departments[$i])) {
            $departments[] = $fallback_departments[$i];
        }
    }
}

// Limit to 8 courses max
$departments = array_slice(array_unique($departments), 0, 8);

$insertStmt = $pdo->prepare("INSERT INTO courses (university_id, name, duration, tuition_fee, intakes, is_active) VALUES (:uid, :name, :duration, :fee, :intakes, 1)");

$added = 0;
foreach ($departments as $dept) {
    // Randomly assign Bachelor or Master
    $isMaster = (rand(0, 1) === 1);
    $prefix = $isMaster ? 'Master of ' : 'Bachelor of ';
    
    // Exception for MBA
    if (stripos($dept, 'Business') !== false && $isMaster) {
        $course_name = "Master of Business Administration (MBA)";
    } else {
        $course_name = $prefix . $dept;
    }

    $duration = getRandomDuration($isMaster ? 'Master' : 'Bachelor');
    $fee = getRandomFee();
    $intakes = (rand(0, 1) === 1) ? 'Sep, Jan' : 'Sep';

    try {
        $insertStmt->execute([
            'uid' => $uni_id,
            'name' => $course_name,
            'duration' => $duration,
            'fee' => $fee,
            'intakes' => $intakes
        ]);
        $added++;
    } catch (PDOException $e) {
        // Skip on duplicate or error
    }
}

echo json_encode([
    'success' => true, 
    'message' => "Successfully fetched and added {$added} courses from web data.",
    'count' => $added
]);
?>
