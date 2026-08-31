<?php
require 'includes/config.php';

try {
    $stmt = $pdo->query("SELECT id, name FROM countries WHERE is_active = 1");
    $countries = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $insertUni = $pdo->prepare("INSERT INTO universities (country_id, name, qs_ranking, specialization, is_active) VALUES (:cid, :name, :qs, 'General', 1)");
    $insertCourse = $pdo->prepare("INSERT INTO courses (university_id, name, duration, tuition_fee, intakes, is_active) VALUES (:uid, :name, :duration, :fee, :intakes, 1)");

    $generic_courses = [
        'Bachelor of Computer Science',
        'Master of Business Administration (MBA)',
        'Bachelor of Mechanical Engineering',
        'Master of Data Science',
        'Bachelor of Nursing',
        'Master of Public Health',
        'Bachelor of Business Administration',
        'Master of Artificial Intelligence'
    ];

    $totalUnisAdded = 0;
    $totalCoursesAdded = 0;

    foreach ($countries as $c) {
        $cid = $c['id'];
        $cname = $c['name'];

        $check = $pdo->query("SELECT count(*) FROM universities WHERE country_id = $cid")->fetchColumn();
        if ($check == 0) {
            echo "Seeding missing data for: $cname\n";
            
            $uni_names = [
                "University of $cname",
                "National Institute of $cname",
                "$cname Tech University"
            ];

            foreach ($uni_names as $idx => $uname) {
                // Insert Uni
                $insertUni->execute([
                    'cid' => $cid,
                    'name' => $uname,
                    'qs' => rand(100, 800)
                ]);
                $uid = $pdo->lastInsertId();
                $totalUnisAdded++;

                // Insert Courses
                shuffle($generic_courses);
                $num_courses = rand(4, 7);
                for ($i=0; $i<$num_courses; $i++) {
                    $cname_course = $generic_courses[$i];
                    $is_master = (strpos($cname_course, 'Master') !== false);
                    $dur = $is_master ? (rand(1,2) . ' Years') : (rand(3,4) . ' Years');
                    $fee = '$' . rand(15, 35) . ',000';
                    $intakes = (rand(0,1) == 1) ? 'Sep, Jan' : 'Sep';

                    $insertCourse->execute([
                        'uid' => $uid,
                        'name' => $cname_course,
                        'duration' => $dur,
                        'fee' => $fee,
                        'intakes' => $intakes
                    ]);
                    $totalCoursesAdded++;
                }
            }
        }
    }

    echo "\n=== Done ===\n";
    echo "Total Universities Added: $totalUnisAdded\n";
    echo "Total Courses Added: $totalCoursesAdded\n";

} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
