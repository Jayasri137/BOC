<?php
require 'c:\xampp\htdocs\Bluestone Overseas\includes\config.php';

function getRandomFee() {
    $fees = ['$20,000', '$22,500', '$25,000', '$28,000', '$30,000', '$32,500', '$35,000', '£15,000', '£18,000', '£22,000'];
    return $fees[array_rand($fees)];
}

function getRandomDuration($level) {
    if ($level === 'Master') {
        $durs = ['1 Year', '1.5 Years', '2 Years'];
        return $durs[array_rand($durs)];
    } else {
        $durs = ['3 Years', '4 Years'];
        return $durs[array_rand($durs)];
    }
}

try {
    $stmt = $pdo->query("SELECT id, name FROM universities WHERE country_id = (SELECT id FROM countries WHERE slug = 'uk' LIMIT 1)");
    $unis = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $insertStmt = $pdo->prepare("INSERT INTO courses (university_id, name, duration, tuition_fee, intakes, is_active) VALUES (:uid, :name, :duration, :fee, :intakes, 1)");

    $totalAdded = 0;

    foreach ($unis as $uni) {
        // Check if courses already exist
        $check = $pdo->prepare("SELECT count(*) FROM courses WHERE university_id = ?");
        $check->execute([$uni['id']]);
        if ($check->fetchColumn() > 0) continue; // Skip if already has courses

        $uni_name = $uni['name'];
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
            $lines = explode("\n", $extract);
            foreach ($lines as $line) {
                if (preg_match('/(?:Faculty|School|Department) of ([A-Z][a-z]+(?: [A-Z][a-z]+)*)/', $line, $matches)) {
                    $dept = trim($matches[1]);
                    if (!in_array(strtolower($dept), ['the', 'university', 'research'])) {
                        $departments[] = $dept;
                    }
                }
            }
        }

        $fallback_departments = [
            'Computer Science', 'Business Administration', 'Data Science', 'Nursing', 
            'Mechanical Engineering', 'International Relations', 'Psychology', 
            'Cybersecurity', 'Public Health', 'Law', 'Accounting and Finance',
            'Architecture', 'Civil Engineering', 'Marketing', 'Artificial Intelligence'
        ];

        $departments = array_unique($departments);

        if (count($departments) < 5) {
            shuffle($fallback_departments);
            $needed = 8 - count($departments);
            for ($i=0; $i<$needed; $i++) {
                if (isset($fallback_departments[$i])) {
                    $departments[] = $fallback_departments[$i];
                }
            }
        }

        $departments = array_slice(array_unique($departments), 0, 8);

        $added = 0;
        foreach ($departments as $dept) {
            $isMaster = (rand(0, 1) === 1);
            $prefix = $isMaster ? 'Master of ' : 'Bachelor of ';
            if (stripos($dept, 'Business') !== false && $isMaster) {
                $course_name = "Master of Business Administration (MBA)";
            } else {
                $course_name = $prefix . $dept;
            }

            try {
                $insertStmt->execute([
                    'uid' => $uni['id'],
                    'name' => $course_name,
                    'duration' => getRandomDuration($isMaster ? 'Master' : 'Bachelor'),
                    'fee' => getRandomFee(),
                    'intakes' => (rand(0, 1) === 1) ? 'Sep, Jan' : 'Sep'
                ]);
                $added++;
                $totalAdded++;
            } catch (PDOException $e) {
            }
        }
        echo "Added {$added} courses for {$uni_name}\n";
    }
    echo "Total courses added: {$totalAdded}\n";

} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
