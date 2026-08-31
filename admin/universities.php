<?php
// admin/universities.php - University & Course Catalog CRUD Editor
$pageTitle = 'Universities & Courses';
require_once 'includes/header.php'; // handles session and pdo load

$alertSuccess = '';
$alertError = '';

// Fetch active countries for dropdown selector
$countries = [];
try {
    $stmt = $pdo->query("SELECT id, name, flag, slug FROM countries WHERE is_active = 1 ORDER BY name ASC");
    $countries = $stmt->fetchAll();
} catch (PDOException $e) {
    $alertError = 'Failed to load countries: ' . $e->getMessage();
}

// Determine currently selected country (default to the first active country)
$selected_country_id = isset($_GET['country_id']) ? intval($_GET['country_id']) : 0;
if ($selected_country_id <= 0 && !empty($countries)) {
    $selected_country_id = intval($countries[0]['id']);
}

// --- HANDLE POST REQUESTS (ADD/EDIT/DELETE FOR UNIVERSITIES AND COURSES) ---
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = isset($_POST['action']) ? trim($_POST['action']) : '';
    
    // 1. ADD UNIVERSITY
    if ($action === 'add_uni') {
        $c_id = isset($_POST['country_id']) ? intval($_POST['country_id']) : 0;
        $name = isset($_POST['name']) ? trim($_POST['name']) : '';
        $qs = isset($_POST['qs_ranking']) ? trim($_POST['qs_ranking']) : '';
        $spec = isset($_POST['specialization']) ? trim($_POST['specialization']) : '';
        
        $image_url = null;
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = '../assets/images/universities/';
            if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);
            $fileName = time() . '_' . basename($_FILES['image']['name']);
            if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadDir . $fileName)) {
                $image_url = 'assets/images/universities/' . $fileName;
            }
        }
        
        if ($c_id <= 0 || empty($name)) {
            $alertError = 'University Name and valid Country are required.';
        } else {
            try {
                $stmt = $pdo->prepare("INSERT INTO universities (country_id, name, qs_ranking, specialization, image_url, is_active) VALUES (:cid, :name, :qs, :spec, :img, 1)");
                $stmt->execute([
                    'cid' => $c_id,
                    'name' => $name,
                    'qs' => $qs,
                    'spec' => $spec,
                    'img' => $image_url
                ]);
                $alertSuccess = 'University added successfully!';
                $selected_country_id = $c_id; // Keep user on the same country
            } catch (PDOException $e) {
                $alertError = 'Failed to add university: ' . $e->getMessage();
            }
        }
    }
    
    // 2. EDIT UNIVERSITY
    elseif ($action === 'update_uni') {
        $uni_id = isset($_POST['uni_id']) ? intval($_POST['uni_id']) : 0;
        $c_id = isset($_POST['country_id']) ? intval($_POST['country_id']) : 0;
        $name = isset($_POST['name']) ? trim($_POST['name']) : '';
        $qs = isset($_POST['qs_ranking']) ? trim($_POST['qs_ranking']) : '';
        $spec = isset($_POST['specialization']) ? trim($_POST['specialization']) : '';
        
        $image_sql = "";
        $params = [
            'name' => $name,
            'qs' => $qs,
            'spec' => $spec,
            'id' => $uni_id
        ];

        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = '../assets/images/universities/';
            if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);
            $fileName = time() . '_' . basename($_FILES['image']['name']);
            if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadDir . $fileName)) {
                $image_sql = ", image_url = :img";
                $params['img'] = 'assets/images/universities/' . $fileName;
            }
        }
        
        if ($uni_id <= 0 || empty($name)) {
            $alertError = 'University Name is required.';
        } else {
            try {
                $stmt = $pdo->prepare("UPDATE universities SET name = :name, qs_ranking = :qs, specialization = :spec{$image_sql} WHERE id = :id");
                $stmt->execute($params);
                $alertSuccess = 'University details updated successfully!';
                if ($c_id > 0) $selected_country_id = $c_id;
            } catch (PDOException $e) {
                $alertError = 'Failed to update university: ' . $e->getMessage();
            }
        }
    }
    
    // 3. DELETE UNIVERSITY
    elseif ($action === 'delete_uni') {
        $uni_id = isset($_POST['uni_id']) ? intval($_POST['uni_id']) : 0;
        $c_id = isset($_POST['country_id']) ? intval($_POST['country_id']) : 0;
        
        if ($uni_id <= 0) {
            $alertError = 'Invalid University ID for deletion.';
        } else {
            try {
                // Cascades courses automatically via database or we can delete manually
                $stmt = $pdo->prepare("DELETE FROM universities WHERE id = :id");
                $stmt->execute(['id' => $uni_id]);
                $alertSuccess = 'University and its courses deleted permanently!';
                if ($c_id > 0) $selected_country_id = $c_id;
            } catch (PDOException $e) {
                $alertError = 'Failed to delete university: ' . $e->getMessage();
            }
        }
    }
    
    // 4. ADD COURSE
    elseif ($action === 'add_course') {
        $uni_id = isset($_POST['uni_id']) ? intval($_POST['uni_id']) : 0;
        $c_id = isset($_POST['country_id']) ? intval($_POST['country_id']) : 0;
        $name = isset($_POST['name']) ? trim($_POST['name']) : '';
        $duration = isset($_POST['duration']) ? trim($_POST['duration']) : '';
        $fee = isset($_POST['tuition_fee']) ? trim($_POST['tuition_fee']) : '';
        $intakes = isset($_POST['intakes']) ? trim($_POST['intakes']) : '';
        
        if ($uni_id <= 0 || empty($name)) {
            $alertError = 'Course Name is required.';
        } else {
            try {
                $stmt = $pdo->prepare("INSERT INTO courses (university_id, name, duration, tuition_fee, intakes, is_active) VALUES (:uid, :name, :duration, :fee, :intakes, 1)");
                $stmt->execute([
                    'uid' => $uni_id,
                    'name' => $name,
                    'duration' => $duration,
                    'fee' => $fee,
                    'intakes' => $intakes
                ]);
                $alertSuccess = 'Course added successfully!';
                if ($c_id > 0) $selected_country_id = $c_id;
            } catch (PDOException $e) {
                $alertError = 'Failed to add course: ' . $e->getMessage();
            }
        }
    }
    
    // 5. EDIT COURSE
    elseif ($action === 'update_course') {
        $course_id = isset($_POST['course_id']) ? intval($_POST['course_id']) : 0;
        $c_id = isset($_POST['country_id']) ? intval($_POST['country_id']) : 0;
        $name = isset($_POST['name']) ? trim($_POST['name']) : '';
        $duration = isset($_POST['duration']) ? trim($_POST['duration']) : '';
        $fee = isset($_POST['tuition_fee']) ? trim($_POST['tuition_fee']) : '';
        $intakes = isset($_POST['intakes']) ? trim($_POST['intakes']) : '';
        
        if ($course_id <= 0 || empty($name)) {
            $alertError = 'Course Name is required.';
        } else {
            try {
                $stmt = $pdo->prepare("UPDATE courses SET name = :name, duration = :duration, tuition_fee = :fee, intakes = :intakes WHERE id = :id");
                $stmt->execute([
                    'name' => $name,
                    'duration' => $duration,
                    'fee' => $fee,
                    'intakes' => $intakes,
                    'id' => $course_id
                ]);
                $alertSuccess = 'Course details updated successfully!';
                if ($c_id > 0) $selected_country_id = $c_id;
            } catch (PDOException $e) {
                $alertError = 'Failed to update course: ' . $e->getMessage();
            }
        }
    }
    
    // 6. DELETE COURSE
    elseif ($action === 'delete_course') {
        $course_id = isset($_POST['course_id']) ? intval($_POST['course_id']) : 0;
        $c_id = isset($_POST['country_id']) ? intval($_POST['country_id']) : 0;
        
        if ($course_id <= 0) {
            $alertError = 'Invalid Course ID for deletion.';
        } else {
            try {
                $stmt = $pdo->prepare("DELETE FROM courses WHERE id = :id");
                $stmt->execute(['id' => $course_id]);
                $alertSuccess = 'Course deleted permanently!';
                if ($c_id > 0) $selected_country_id = $c_id;
            } catch (PDOException $e) {
                $alertError = 'Failed to delete course: ' . $e->getMessage();
            }
        }
    }
    
    // 7. ADD SCHOLARSHIP
    elseif ($action === 'add_scholarship') {
        $uni_id = isset($_POST['uni_id']) ? intval($_POST['uni_id']) : 0;
        $c_id = isset($_POST['country_id']) ? intval($_POST['country_id']) : 0;
        $name = isset($_POST['name']) ? trim($_POST['name']) : '';
        $amount = isset($_POST['amount']) ? trim($_POST['amount']) : '';
        $elig = isset($_POST['eligibility']) ? trim($_POST['eligibility']) : '';
        $deadline = isset($_POST['deadline']) ? trim($_POST['deadline']) : '';
        
        if ($uni_id <= 0 || empty($name)) {
            $alertError = 'Scholarship Name is required.';
        } else {
            try {
                $stmt = $pdo->prepare("INSERT INTO scholarships (university_id, name, amount, eligibility, deadline, is_active) VALUES (:uid, :name, :amount, :elig, :deadline, 1)");
                $stmt->execute([
                    'uid' => $uni_id,
                    'name' => $name,
                    'amount' => $amount,
                    'elig' => $elig,
                    'deadline' => $deadline
                ]);
                $alertSuccess = 'Scholarship added successfully!';
                if ($c_id > 0) $selected_country_id = $c_id;
            } catch (PDOException $e) {
                $alertError = 'Failed to add scholarship: ' . $e->getMessage();
            }
        }
    }
    
    // 8. EDIT SCHOLARSHIP
    elseif ($action === 'update_scholarship') {
        $s_id = isset($_POST['scholarship_id']) ? intval($_POST['scholarship_id']) : 0;
        $c_id = isset($_POST['country_id']) ? intval($_POST['country_id']) : 0;
        $name = isset($_POST['name']) ? trim($_POST['name']) : '';
        $amount = isset($_POST['amount']) ? trim($_POST['amount']) : '';
        $elig = isset($_POST['eligibility']) ? trim($_POST['eligibility']) : '';
        $deadline = isset($_POST['deadline']) ? trim($_POST['deadline']) : '';
        
        if ($s_id <= 0 || empty($name)) {
            $alertError = 'Scholarship Name is required.';
        } else {
            try {
                $stmt = $pdo->prepare("UPDATE scholarships SET name = :name, amount = :amount, eligibility = :elig, deadline = :deadline WHERE id = :id");
                $stmt->execute([
                    'name' => $name,
                    'amount' => $amount,
                    'elig' => $elig,
                    'deadline' => $deadline,
                    'id' => $s_id
                ]);
                $alertSuccess = 'Scholarship updated successfully!';
                if ($c_id > 0) $selected_country_id = $c_id;
            } catch (PDOException $e) {
                $alertError = 'Failed to update scholarship: ' . $e->getMessage();
            }
        }
    }
    
    // 9. DELETE SCHOLARSHIP
    elseif ($action === 'delete_scholarship') {
        $s_id = isset($_POST['scholarship_id']) ? intval($_POST['scholarship_id']) : 0;
        $c_id = isset($_POST['country_id']) ? intval($_POST['country_id']) : 0;
        
        if ($s_id <= 0) {
            $alertError = 'Invalid Scholarship ID.';
        } else {
            try {
                $stmt = $pdo->prepare("DELETE FROM scholarships WHERE id = :id");
                $stmt->execute(['id' => $s_id]);
                $alertSuccess = 'Scholarship deleted successfully!';
                if ($c_id > 0) $selected_country_id = $c_id;
            } catch (PDOException $e) {
                $alertError = 'Failed to delete scholarship: ' . $e->getMessage();
            }
        }
    }
}

// Fetch all universities under the selected country with course counts and pagination
$universities = [];
$totalCount = 0;
$totalPages = 1;
$page = 1;
$limit = 10;
$offset = 0;

if ($selected_country_id > 0) {
    try {
        $countStmt = $pdo->prepare("SELECT COUNT(*) FROM universities WHERE country_id = :cid");
        $countStmt->execute(['cid' => $selected_country_id]);
        $totalCount = intval($countStmt->fetchColumn());

        $pagination = get_pagination_params($totalCount, 10);
        $limit = $pagination['limit'];
        $offset = $pagination['offset'];
        $page = $pagination['page'];
        $totalPages = $pagination['totalPages'];

        if ($limit === 999999) {
            $stmt = $pdo->prepare("
                SELECT u.*, (SELECT COUNT(*) FROM courses WHERE university_id = u.id) as course_count 
                FROM universities u 
                WHERE u.country_id = :cid 
                ORDER BY u.name ASC
            ");
            $stmt->bindValue(':cid', $selected_country_id, PDO::PARAM_INT);
        } else {
            $stmt = $pdo->prepare("
                SELECT u.*, (SELECT COUNT(*) FROM courses WHERE university_id = u.id) as course_count 
                FROM universities u 
                WHERE u.country_id = :cid 
                ORDER BY u.name ASC
                LIMIT :limit OFFSET :offset
            ");
            $stmt->bindValue(':cid', $selected_country_id, PDO::PARAM_INT);
            $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
            $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        }
        $stmt->execute();
        $universities = $stmt->fetchAll();
    } catch (PDOException $e) {
        $alertError = 'Failed to load universities: ' . $e->getMessage();
        $totalCount = 0;
        $totalPages = 1;
        $page = 1;
        $limit = 10;
    }
}
?>

<style>
    .selector-bar {
        background: var(--card-bg);
        border: 1px solid var(--border);
        border-radius: 16px;
        padding: 1.5rem;
        display: flex;
        align-items: center;
        gap: 1.5rem;
        margin-bottom: 2.5rem;
        flex-wrap: wrap;
    }
    .selector-group {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        flex: 1;
        min-width: 250px;
    }
    .accordion-header {
        background: rgba(255, 255, 255, 0.02);
        border: 1px solid var(--border);
        border-radius: 12px;
        padding: 1rem 1.5rem;
        margin-bottom: 1rem;
        transition: all 0.3s ease;
    }
    .accordion-header:hover {
        background: rgba(255, 255, 255, 0.05);
        border-color: rgba(255, 255, 255, 0.2);
    }
    .accordion-content {
        background: rgba(15, 23, 42, 0.2);
        border: 1px solid var(--border);
        border-top: none;
        border-radius: 0 0 12px 12px;
        margin-top: -1.25rem;
        margin-bottom: 1.5rem;
        padding: 1.5rem;
        display: none;
    }
    .accordion-content.active {
        display: block;
    }
    .course-table {
        width: 100%;
        border-collapse: collapse;
        text-align: left;
        margin-top: 1rem;
        font-size: 0.9rem;
    }
    .course-table th, .course-table td {
        padding: 0.75rem 1rem;
        border-bottom: 1px solid var(--border);
    }
    .course-table th {
        background: rgba(255,255,255,0.03);
        font-weight: 600;
        color: var(--accent);
    }
    .course-table tr:hover td {
        background: rgba(255,255,255,0.01);
    }
    .tag-qs {
        background: rgba(245, 158, 11, 0.15);
        color: #f59e0b;
        border: 1px solid rgba(245, 158, 11, 0.3);
        padding: 0.2rem 0.6rem;
        border-radius: 20px;
        font-weight: 600;
        font-size: 0.8rem;
    }
</style>

<div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 2.5rem; flex-wrap: wrap; gap: 1rem;">
    <h1 class="page-title">
        Universities & Courses Manager
        <span>Configure universities, global rankings, specializations, and individual study programs per country</span>
    </h1>
    <?php if ($selected_country_id > 0): ?>
        <button class="btn-pill" onclick="openAddUniModal()">
            <i class="fa-solid fa-plus"></i>
            <span>Add University</span>
        </button>
    <?php endif; ?>
</div>

<?php if (!empty($alertSuccess)): ?>
    <div class="alert alert-success">
        <i class="fa-solid fa-circle-check"></i>
        <span><?php echo clean_output($alertSuccess); ?></span>
    </div>
<?php endif; ?>

<?php if (!empty($alertError)): ?>
    <div class="alert alert-danger">
        <i class="fa-solid fa-triangle-exclamation"></i>
        <span><?php echo clean_output($alertError); ?></span>
    </div>
<?php endif; ?>

<!-- 1. COUNTRY SELECTOR BAR -->
<div class="selector-bar">
    <form method="GET" action="universities.php" style="display: flex; width: 100%; align-items: center; flex-wrap: wrap; gap: 1.5rem;">
        <div class="selector-group">
            <label for="country_select" class="form-label" style="margin-bottom: 0; white-space: nowrap; font-weight: 600; color: var(--text-primary);"><i class="fa-solid fa-earth-asia"></i> Select Country:</label>
            <select name="country_id" id="country_select" class="form-control" onchange="this.form.submit()" style="background: rgba(15,23,42,0.6); border-color: var(--border);">
                <?php if (empty($countries)): ?>
                    <option value="">No active countries found</option>
                <?php else: ?>
                    <?php foreach ($countries as $c): ?>
                        <option value="<?php echo $c['id']; ?>" <?php echo $selected_country_id === intval($c['id']) ? 'selected' : ''; ?>>
                            <?php echo clean_output($c['name']); ?>
                        </option>
                    <?php endforeach; ?>
                <?php endif; ?>
            </select>
        </div>
        <div style="font-size: 0.9rem; color: var(--text-secondary);">
            Active Universities in selected destination: <strong><?php echo $totalCount; ?></strong>
        </div>
        <div class="filter-group" style="flex-direction: row; align-items: center; gap: 0.5rem; margin-bottom: 0; margin-left: auto; display: inline-flex;">
            <label for="limit_select" style="font-size: 0.85rem; font-weight: 600; color: var(--text-secondary); margin-bottom: 0;">Show:</label>
            <select name="limit" id="limit_select" class="filter-control" onchange="this.form.submit()" style="padding: 0.35rem 2rem 0.35rem 0.75rem; font-size: 0.85rem; border-radius: var(--radius-sm); border: 1px solid var(--border); min-width: auto; background-color: rgba(15,23,42,0.6); color: var(--text-primary); border-color: var(--border); cursor: pointer;">
                <option value="10" <?php echo $limit === 10 ? 'selected' : ''; ?>>10 entries</option>
                <option value="20" <?php echo $limit === 20 ? 'selected' : ''; ?>>20 entries</option>
                <option value="50" <?php echo $limit === 50 ? 'selected' : ''; ?>>50 entries</option>
                <option value="all" <?php echo $limit === 999999 ? 'selected' : ''; ?>>Show All</option>
            </select>
        </div>
    </form>
</div>

<!-- 2. UNIVERSITY & COURSES ACCORDION GRID -->
<?php if ($selected_country_id <= 0): ?>
    <div class="panel-card" style="text-align: center; padding: 4rem 2rem; color: var(--text-secondary);">
        <i class="fa-solid fa-circle-question" style="font-size: 3rem; margin-bottom: 1.5rem;"></i>
        <h3>Please configure and publish study destinations first.</h3>
    </div>
<?php else: ?>

    <?php if (empty($universities)): ?>
        <div class="panel-card" style="text-align: center; padding: 5rem 2rem; color: var(--text-secondary);">
            <i class="fa-solid fa-graduation-cap" style="font-size: 3.5rem; margin-bottom: 1.5rem; color: var(--text-muted);"></i>
            <h3>No universities added for this study destination yet.</h3>
            <p style="margin-top: 0.5rem; font-size: 0.9rem;">Click the "Add University" button above to upload the first educational partner institution!</p>
        </div>
    <?php else: ?>
        <div style="display: flex; flex-direction: column;">
            <?php foreach ($universities as $uni): 
                $uniId = intval($uni['id']);
                $uni_json = json_encode($uni);
                
                // Fetch courses for this specific university
                $stmtCourses = $pdo->prepare("SELECT * FROM courses WHERE university_id = :uid ORDER BY name ASC");
                $stmtCourses->execute(['uid' => $uniId]);
                $coursesList = $stmtCourses->fetchAll();
            ?>
                <!-- University Card Wrapper -->
                <div class="accordion-wrapper" id="uni-wrapper-<?php echo $uniId; ?>">
                    <div class="accordion-header" style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 1rem;">
                        <div style="display: flex; align-items: center; gap: 1.25rem; flex: 1;">
                            <div class="icon-colorful icon-colorful--blue" style="font-size: 1.25rem; width: 45px; height: 45px;"><i class="fa-solid fa-building-columns"></i></div>
                            <div>
                                <h3 style="font-size: 1.15rem; margin-bottom: 0.25rem;"><?php echo clean_output($uni['name']); ?></h3>
                                <div style="display: flex; align-items: center; gap: 0.75rem; flex-wrap: wrap; font-size: 0.8rem;">
                                    <?php if (!empty($uni['qs_ranking'])): ?>
                                        <span class="tag-qs"><i class="fa-solid fa-star"></i> QS Ranking: <?php echo clean_output($uni['qs_ranking']); ?></span>
                                    <?php endif; ?>
                                    <span style="color: var(--text-secondary);"><i class="fa-solid fa-atom"></i> Focus: <?php echo !empty($uni['specialization']) ? clean_output($uni['specialization']) : 'General'; ?></span>
                                </div>
                            </div>
                        </div>
                        
                        <div style="display: flex; align-items: center; gap: 1rem;">
                            <!-- Manage Courses trigger button -->
                            <button class="btn-outline" onclick="toggleCoursesAccordion(<?php echo $uniId; ?>)" style="padding: 0.5rem 1rem; border-radius: 20px; font-size: 0.8rem;">
                                <i class="fa-solid fa-book-bookmark"></i>
                                <span>Courses (<?php echo count($coursesList); ?>)</span>
                                <i class="fa-solid fa-chevron-down" id="arrow-<?php echo $uniId; ?>" style="margin-left: 0.5rem; transition: transform 0.3s;"></i>
                            </button>
                            
                            <div class="crud-card-actions" style="margin-top: 0;">
                                <button class="btn-action action-edit" title="Edit University" onclick="openEditUniModal(<?php echo htmlspecialchars($uni_json); ?>)">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </button>
                                <button class="btn-action action-delete" title="Delete University" onclick="triggerDeleteUni(<?php echo $uniId; ?>, '<?php echo clean_output($uni['name']); ?>')">
                                    <i class="fa-solid fa-trash-can"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Courses Expandable Accordion Block -->
                    <div class="accordion-content" id="accordion-<?php echo $uniId; ?>">
                        <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 1rem; flex-wrap: wrap; gap: 0.5rem;">
                            <h4 style="color: var(--accent); font-weight: 600;"><i class="fa-solid fa-graduation-cap"></i> Course Catalog for <?php echo clean_output($uni['name']); ?></h4>
                            <div style="display: flex; gap: 0.5rem;">
                                <button class="btn-pill" onclick="autoFetchCourses(<?php echo $uniId; ?>, '<?php echo addslashes(clean_output($uni['name'])); ?>', this)" style="padding: 0.4rem 1rem; font-size: 0.75rem; background: #8b5cf6; border-color: #8b5cf6; box-shadow: 0 4px 10px rgba(139, 92, 246, 0.3);">
                                    <i class="fa-solid fa-cloud-arrow-down"></i> Auto-Fetch Courses (Web Search)
                                </button>
                                <button class="btn-pill" onclick="openAddCourseModal(<?php echo $uniId; ?>, '<?php echo clean_output($uni['name']); ?>')" style="padding: 0.4rem 1rem; font-size: 0.75rem;">
                                    <i class="fa-solid fa-plus"></i> Add Manual
                                </button>
                            </div>
                        </div>
                        
                        <?php if (empty($coursesList)): ?>
                            <div style="text-align: center; padding: 2.5rem 1rem; color: var(--text-muted); background: rgba(255,255,255,0.01); border-radius: 8px; border: 1px dashed var(--border);">
                                <i class="fa-solid fa-book-open" style="font-size: 1.5rem; margin-bottom: 0.75rem;"></i>
                                <p style="font-size: 0.85rem;">No courses uploaded against this university yet.</p>
                            </div>
                        <?php else: ?>
                            <div style="overflow-x: auto;">
                                <table class="course-table">
                                    <thead>
                                        <tr>
                                            <th>Course Name / Program Title</th>
                                            <th>Duration</th>
                                            <th>Estimated Tuition Fee</th>
                                            <th>Intakes Available</th>
                                            <th style="text-align: right;">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($coursesList as $course): 
                                            $course_json = json_encode($course);
                                        ?>
                                            <tr>
                                                <td style="font-weight: 600; color: var(--text-primary);"><?php echo clean_output($course['name']); ?></td>
                                                <td><?php echo !empty($course['duration']) ? clean_output($course['duration']) : 'N/A'; ?></td>
                                                <td><span style="color: #10b981; font-weight: 500;"><?php echo !empty($course['tuition_fee']) ? clean_output($course['tuition_fee']) : 'Consult Admin'; ?></span></td>
                                                <td><span class="tag-qs" style="background: rgba(16,185,129,0.1); color: #10b981; border-color: rgba(16,185,129,0.2);"><?php echo !empty($course['intakes']) ? clean_output($course['intakes']) : 'General'; ?></span></td>
                                                <td style="text-align: right;">
                                                    <div style="display: flex; gap: 0.5rem; justify-content: flex-end;">
                                                        <button class="btn-action action-edit" style="width: 28px; height: 28px; font-size: 0.75rem;" title="Edit Course" onclick="openEditCourseModal(<?php echo htmlspecialchars($course_json); ?>, '<?php echo clean_output($uni['name']); ?>')">
                                                            <i class="fa-solid fa-pencil"></i>
                                                        </button>
                                                        <button class="btn-action action-delete" style="width: 28px; height: 28px; font-size: 0.75rem;" title="Delete Course" onclick="triggerDeleteCourse(<?php echo $course['id']; ?>, '<?php echo clean_output($course['name']); ?>')">
                                                            <i class="fa-solid fa-trash"></i>
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php endif; ?>

                        <!-- Scholarship Management Section -->
                        <div style="margin-top: 2.5rem; border-top: 1px solid var(--border); padding-top: 2rem;">
                            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 1rem;">
                                <h4 style="color: #f59e0b; font-weight: 600;"><i class="fa-solid fa-award"></i> Scholarships for <?php echo clean_output($uni['name']); ?></h4>
                                <button class="btn-pill" onclick="openAddScholarshipModal(<?php echo $uniId; ?>, '<?php echo clean_output($uni['name']); ?>')" style="padding: 0.4rem 1rem; font-size: 0.75rem; background: #f59e0b; box-shadow: 0 4px 10px rgba(245, 158, 11, 0.2);">
                                    <i class="fa-solid fa-plus"></i> Add Scholarship
                                </button>
                            </div>
                            
                            <?php
                            $stmtSchol = $pdo->prepare("SELECT * FROM scholarships WHERE university_id = :uid ORDER BY name ASC");
                            $stmtSchol->execute(['uid' => $uniId]);
                            $scholList = $stmtSchol->fetchAll();
                            ?>
                            
                            <?php if (empty($scholList)): ?>
                                <div style="text-align: center; padding: 1.5rem 1rem; color: var(--text-muted); background: rgba(255,255,255,0.01); border-radius: 8px; border: 1px dashed var(--border);">
                                    <p style="font-size: 0.85rem;">No scholarships added for this university.</p>
                                </div>
                            <?php else: ?>
                                <div style="overflow-x: auto;">
                                    <table class="course-table">
                                        <thead>
                                            <tr>
                                                <th>Scholarship Name</th>
                                                <th>Amount / Benefit</th>
                                                <th>Eligibility</th>
                                                <th>Deadline</th>
                                                <th style="text-align: right;">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($scholList as $schol): 
                                                $schol_json = json_encode($schol);
                                            ?>
                                                <tr>
                                                    <td style="font-weight: 600; color: var(--text-primary);"><?php echo clean_output($schol['name']); ?></td>
                                                    <td><span style="color: #f59e0b; font-weight: 600;"><?php echo clean_output($schol['amount']); ?></span></td>
                                                    <td style="max-width: 250px; font-size: 0.8rem;"><?php echo clean_output($schol['eligibility']); ?></td>
                                                    <td><span class="tag-qs" style="background: rgba(245, 158, 11, 0.1); color: #f59e0b; border-color: rgba(245, 158, 11, 0.2);"><?php echo clean_output($schol['deadline']); ?></span></td>
                                                    <td style="text-align: right;">
                                                        <div style="display: flex; gap: 0.5rem; justify-content: flex-end;">
                                                            <button class="btn-action action-edit" style="width: 28px; height: 28px; font-size: 0.75rem;" title="Edit Scholarship" onclick="openEditScholarshipModal(<?php echo htmlspecialchars($schol_json); ?>, '<?php echo clean_output($uni['name']); ?>')">
                                                                <i class="fa-solid fa-pencil"></i>
                                                            </button>
                                                            <button class="btn-action action-delete" style="width: 28px; height: 28px; font-size: 0.75rem;" title="Delete Scholarship" onclick="triggerDeleteScholarship(<?php echo $schol['id']; ?>, '<?php echo clean_output($schol['name']); ?>')">
                                                                <i class="fa-solid fa-trash"></i>
                                                            </button>
                                                        </div>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        <?php 
        $limitParam = ($limit === 999999) ? 'all' : $limit;
        echo render_pagination_buttons($page, $totalPages, [
            'country_id' => $selected_country_id,
            'limit' => $limitParam
        ]); 
        ?>
    <?php endif; ?>

<?php endif; ?>

<!-- ==========================================
     MODALS SECTION
     ========================================== -->

<!-- A. ADD/EDIT UNIVERSITY MODAL -->
<div class="modal-overlay" id="uniModal">
    <div class="modal-container" style="max-width: 500px;">
        <div class="modal-header">
            <h3 class="modal-title" id="uniModalTitle">Add Partner University</h3>
            <span class="modal-close" onclick="closeUniModal()">&times;</span>
        </div>
        <form action="universities.php" method="POST" enctype="multipart/form-data">
            <div class="modal-body">
                <input type="hidden" name="action" id="uniModalAction" value="add_uni">
                <input type="hidden" name="country_id" value="<?php echo $selected_country_id; ?>">
                <input type="hidden" name="uni_id" id="edit_uni_id">
                
                <div class="form-group">
                    <label for="u_name" class="form-label">University / Institution Name *</label>
                    <input type="text" name="name" id="u_name" class="form-control" placeholder="e.g., University of Melbourne" required>
                </div>
                
                <div class="form-group">
                    <label for="u_qs" class="form-label">QS World Ranking (Optional)</label>
                    <input type="text" name="qs_ranking" id="u_qs" class="form-control" placeholder="e.g., #19">
                </div>
                
                <div class="form-group">
                    <label for="u_spec" class="form-label">Key Strengths / Specializations</label>
                    <input type="text" name="specialization" id="u_spec" class="form-control" placeholder="e.g., Medicine, Psychology, Law">
                </div>
                
                <div class="form-group">
                    <label for="u_image" class="form-label">University Image (Optional)</label>
                    <input type="file" name="image" id="u_image" class="form-control" accept="image/*">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-outline" onclick="closeUniModal()">Cancel</button>
                <button type="submit" class="btn-pill">
                    <i class="fa-solid fa-floppy-disk"></i>
                    <span>Save University</span>
                </button>
            </div>
        </form>
    </div>
</div>

<!-- B. ADD/EDIT COURSE MODAL -->
<div class="modal-overlay" id="courseModal">
    <div class="modal-container" style="max-width: 550px;">
        <div class="modal-header">
            <h3 class="modal-title" id="courseModalTitle">Add Study Program</h3>
            <span class="modal-close" onclick="closeCourseModal()">&times;</span>
        </div>
        <form action="universities.php" method="POST">
            <div class="modal-body">
                <input type="hidden" name="action" id="courseModalAction" value="add_course">
                <input type="hidden" name="country_id" value="<?php echo $selected_country_id; ?>">
                <input type="hidden" name="uni_id" id="course_uni_id">
                <input type="hidden" name="course_id" id="edit_course_id">
                
                <div class="form-group" style="background: rgba(255,255,255,0.02); padding: 0.75rem 1rem; border-radius: 8px; border: 1px solid var(--border); margin-bottom: 1.25rem;">
                    <span style="font-size: 0.8rem; color: var(--text-muted); display: block;">University Partner:</span>
                    <strong id="course_uni_display" style="color: var(--accent); font-size: 0.95rem;">University Name</strong>
                </div>

                <div class="form-group">
                    <label for="co_name" class="form-label">Course Title / Program Name *</label>
                    <input type="text" name="name" id="co_name" class="form-control" placeholder="e.g., Master of Information Technology" required>
                </div>
                
                <div class="detail-grid">
                    <div class="form-group">
                        <label for="co_duration" class="form-label">Course Duration</label>
                        <input type="text" name="duration" id="co_duration" class="form-control" placeholder="e.g., 2 Years">
                    </div>
                    <div class="form-group">
                        <label for="co_fee" class="form-label">Tuition Fee per year</label>
                        <input type="text" name="tuition_fee" id="co_fee" class="form-control" placeholder="e.g., $45,000 AUD">
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="co_intakes" class="form-label">Intakes Available</label>
                    <input type="text" name="intakes" id="co_intakes" class="form-control" placeholder="e.g., Feb, July">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-outline" onclick="closeCourseModal()">Cancel</button>
                <button type="submit" class="btn-pill">
                    <i class="fa-solid fa-floppy-disk"></i>
                    <span>Save Course</span>
                </button>
            </div>
        </form>
    </div>
</div>

<!-- C. DELETE UNIVERSITY CONFIRMATION MODAL -->
<div class="modal-overlay" id="deleteUniModal">
    <div class="modal-container" style="max-width: 400px;">
        <div class="modal-header" style="border-bottom: none; padding-bottom: 0;">
            <h3 class="modal-title" style="color: var(--danger);"><i class="fa-solid fa-triangle-exclamation"></i> Delete University?</h3>
            <span class="modal-close" onclick="closeDeleteUniModal()">&times;</span>
        </div>
        <form action="universities.php" method="POST">
            <div class="modal-body" style="padding-top: 1rem;">
                <input type="hidden" name="action" value="delete_uni">
                <input type="hidden" name="country_id" value="<?php echo $selected_country_id; ?>">
                <input type="hidden" name="uni_id" id="deleteUniId">
                <p style="font-size: 0.95rem; line-height: 1.6; color: var(--text-secondary);">
                    Are you sure you want to delete university <strong id="deleteUniName" style="color: var(--text-primary);">University Name</strong>?
                </p>
                <p style="font-size: 0.8rem; color: #f43f5e; margin-top: 0.75rem; font-weight: 600;">
                    WARNING: This will instantly and permanently delete ALL courses uploaded under this university!
                </p>
            </div>
            <div class="modal-footer" style="border-top: none; padding-top: 0;">
                <button type="button" class="btn-outline" onclick="closeDeleteUniModal()">Cancel</button>
                <button type="submit" class="btn-pill" style="background: var(--danger); box-shadow: 0 5px 10px var(--danger-glow);">
                    <i class="fa-solid fa-trash-can"></i>
                    <span>Confirm Delete</span>
                </button>
            </div>
        </form>
    </div>
</div>

<!-- D. DELETE COURSE CONFIRMATION MODAL -->
<div class="modal-overlay" id="deleteCourseModal">
    <div class="modal-container" style="max-width: 400px;">
        <div class="modal-header" style="border-bottom: none; padding-bottom: 0;">
            <h3 class="modal-title" style="color: var(--danger);"><i class="fa-solid fa-trash"></i> Delete Program?</h3>
            <span class="modal-close" onclick="closeDeleteCourseModal()">&times;</span>
        </div>
        <form action="universities.php" method="POST">
            <div class="modal-body" style="padding-top: 1rem;">
                <input type="hidden" name="action" value="delete_course">
                <input type="hidden" name="country_id" value="<?php echo $selected_country_id; ?>">
                <input type="hidden" name="course_id" id="deleteCourseId">
                <p style="font-size: 0.95rem; line-height: 1.6; color: var(--text-secondary);">
                    Are you sure you want to permanently delete course program <strong id="deleteCourseName" style="color: var(--text-primary);">Course Name</strong>?
                </p>
            </div>
            <div class="modal-footer" style="border-top: none; padding-top: 0;">
                <button type="button" class="btn-outline" onclick="closeDeleteCourseModal()">Cancel</button>
                <button type="submit" class="btn-pill" style="background: var(--danger); box-shadow: 0 5px 10px var(--danger-glow);">
                    <i class="fa-solid fa-trash-can"></i>
                    <span>Confirm Delete</span>
                </button>
            </div>
        </form>
    </div>
</div>

<!-- E. ADD/EDIT SCHOLARSHIP MODAL -->
<div class="modal-overlay" id="scholarshipModal">
    <div class="modal-container" style="max-width: 550px;">
        <div class="modal-header">
            <h3 class="modal-title" id="scholarshipModalTitle">Add Scholarship</h3>
            <span class="modal-close" onclick="closeScholarshipModal()">&times;</span>
        </div>
        <form action="universities.php" method="POST">
            <div class="modal-body">
                <input type="hidden" name="action" id="scholModalAction" value="add_scholarship">
                <input type="hidden" name="country_id" value="<?php echo $selected_country_id; ?>">
                <input type="hidden" name="uni_id" id="schol_uni_id">
                <input type="hidden" name="scholarship_id" id="edit_scholarship_id">
                
                <div class="form-group" style="background: rgba(255,255,255,0.02); padding: 0.75rem 1rem; border-radius: 8px; border: 1px solid var(--border); margin-bottom: 1.25rem;">
                    <span style="font-size: 0.8rem; color: var(--text-muted); display: block;">University Partner:</span>
                    <strong id="schol_uni_display" style="color: #f59e0b; font-size: 0.95rem;">University Name</strong>
                </div>

                <div class="form-group">
                    <label for="s_name" class="form-label">Scholarship Name *</label>
                    <input type="text" name="name" id="s_name" class="form-control" placeholder="e.g., International Excellence Scholarship" required>
                </div>
                
                <div class="detail-grid">
                    <div class="form-group">
                        <label for="s_amount" class="form-label">Amount / Benefit</label>
                        <input type="text" name="amount" id="s_amount" class="form-control" placeholder="e.g., $10,000 AUD or 50% Tuition">
                    </div>
                    <div class="form-group">
                        <label for="s_deadline" class="form-label">Deadline</label>
                        <input type="text" name="deadline" id="s_deadline" class="form-control" placeholder="e.g., 31 Dec 2026">
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="s_elig" class="form-label">Eligibility Criteria</label>
                    <textarea name="eligibility" id="s_elig" class="form-control" rows="3" placeholder="e.g., Academic merit, Indian nationality, etc."></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-outline" onclick="closeScholarshipModal()">Cancel</button>
                <button type="submit" class="btn-pill" style="background: #f59e0b;">
                    <i class="fa-solid fa-floppy-disk"></i>
                    <span>Save Scholarship</span>
                </button>
            </div>
        </form>
    </div>
</div>

<!-- F. DELETE SCHOLARSHIP CONFIRMATION MODAL -->
<div class="modal-overlay" id="deleteScholModal">
    <div class="modal-container" style="max-width: 400px;">
        <div class="modal-header" style="border-bottom: none; padding-bottom: 0;">
            <h3 class="modal-title" style="color: var(--danger);"><i class="fa-solid fa-trash"></i> Delete Scholarship?</h3>
            <span class="modal-close" onclick="closeDeleteScholModal()">&times;</span>
        </div>
        <form action="universities.php" method="POST">
            <div class="modal-body" style="padding-top: 1rem;">
                <input type="hidden" name="action" value="delete_scholarship">
                <input type="hidden" name="country_id" value="<?php echo $selected_country_id; ?>">
                <input type="hidden" name="scholarship_id" id="deleteScholId">
                <p style="font-size: 0.95rem; line-height: 1.6; color: var(--text-secondary);">
                    Are you sure you want to permanently delete scholarship <strong id="deleteScholName" style="color: var(--text-primary);">Scholarship Name</strong>?
                </p>
            </div>
            <div class="modal-footer" style="border-top: none; padding-top: 0;">
                <button type="button" class="btn-outline" onclick="closeDeleteScholModal()">Cancel</button>
                <button type="submit" class="btn-pill" style="background: var(--danger); box-shadow: 0 5px 10px var(--danger-glow);">
                    <i class="fa-solid fa-trash-can"></i>
                    <span>Confirm Delete</span>
                </button>
            </div>
        </form>
    </div>
</div>

<script>
// Toggle Accordion Panel
function toggleCoursesAccordion(id) {
    const content = document.getElementById('accordion-' + id);
    const arrow = document.getElementById('arrow-' + id);
    
    content.classList.toggle('active');
    
    if(content.classList.contains('active')) {
        arrow.style.transform = 'rotate(180deg)';
    } else {
        arrow.style.transform = 'rotate(0deg)';
    }
}

// University Modals
function openAddUniModal() {
    document.getElementById('uniModalAction').value = 'add_uni';
    document.getElementById('uniModalTitle').innerText = 'Add Partner University';
    document.getElementById('edit_uni_id').value = '';
    document.getElementById('u_name').value = '';
    document.getElementById('u_qs').value = '';
    document.getElementById('u_spec').value = '';
    
    document.getElementById('uniModal').classList.add('active');
}

function openEditUniModal(uni) {
    document.getElementById('uniModalAction').value = 'update_uni';
    document.getElementById('uniModalTitle').innerText = 'Edit University: ' + uni.name;
    document.getElementById('edit_uni_id').value = uni.id;
    document.getElementById('u_name').value = uni.name;
    document.getElementById('u_qs').value = uni.qs_ranking || '';
    document.getElementById('u_spec').value = uni.specialization || '';
    
    document.getElementById('uniModal').classList.add('active');
}

function closeUniModal() {
    document.getElementById('uniModal').classList.remove('active');
}

function triggerDeleteUni(id, name) {
    document.getElementById('deleteUniId').value = id;
    document.getElementById('deleteUniName').innerText = name;
    document.getElementById('deleteUniModal').classList.add('active');
}

function closeDeleteUniModal() {
    document.getElementById('deleteUniModal').classList.remove('active');
}

// Course Modals
function openAddCourseModal(uniId, uniName) {
    document.getElementById('courseModalAction').value = 'add_course';
    document.getElementById('courseModalTitle').innerText = 'Add Study Program';
    document.getElementById('course_uni_id').value = uniId;
    document.getElementById('course_uni_display').innerText = uniName;
    document.getElementById('edit_course_id').value = '';
    
    document.getElementById('co_name').value = '';
    document.getElementById('co_duration').value = '';
    document.getElementById('co_fee').value = '';
    document.getElementById('co_intakes').value = '';
    
    document.getElementById('courseModal').classList.add('active');
}

function openEditCourseModal(course, uniName) {
    document.getElementById('courseModalAction').value = 'update_course';
    document.getElementById('courseModalTitle').innerText = 'Edit Program Details';
    document.getElementById('course_uni_id').value = course.university_id;
    document.getElementById('course_uni_display').innerText = uniName;
    document.getElementById('edit_course_id').value = course.id;
    
    document.getElementById('co_name').value = course.name;
    document.getElementById('co_duration').value = course.duration || '';
    document.getElementById('co_fee').value = course.tuition_fee || '';
    document.getElementById('co_intakes').value = course.intakes || '';
    
    document.getElementById('courseModal').classList.add('active');
}

function closeCourseModal() {
    document.getElementById('courseModal').classList.remove('active');
}

function triggerDeleteCourse(id, name) {
    document.getElementById('deleteCourseId').value = id;
    document.getElementById('deleteCourseName').innerText = name;
    document.getElementById('deleteCourseModal').classList.add('active');
}

function closeDeleteCourseModal() {
    document.getElementById('deleteCourseModal').classList.remove('active');
}

// Scholarship Modals
function openAddScholarshipModal(uniId, uniName) {
    document.getElementById('scholModalAction').value = 'add_scholarship';
    document.getElementById('scholarshipModalTitle').innerText = 'Add Scholarship / Grant';
    document.getElementById('schol_uni_id').value = uniId;
    document.getElementById('schol_uni_display').innerText = uniName;
    document.getElementById('edit_scholarship_id').value = '';
    
    document.getElementById('s_name').value = '';
    document.getElementById('s_amount').value = '';
    document.getElementById('s_deadline').value = '';
    document.getElementById('s_elig').value = '';
    
    document.getElementById('scholarshipModal').classList.add('active');
}

function openEditScholarshipModal(schol, uniName) {
    document.getElementById('scholModalAction').value = 'update_scholarship';
    document.getElementById('scholarshipModalTitle').innerText = 'Edit Scholarship: ' + schol.name;
    document.getElementById('schol_uni_id').value = schol.university_id;
    document.getElementById('schol_uni_display').innerText = uniName;
    document.getElementById('edit_scholarship_id').value = schol.id;
    
    document.getElementById('s_name').value = schol.name;
    document.getElementById('s_amount').value = schol.amount || '';
    document.getElementById('s_deadline').value = schol.deadline || '';
    document.getElementById('s_elig').value = schol.eligibility || '';
    
    document.getElementById('scholarshipModal').classList.add('active');
}

function closeScholarshipModal() {
    document.getElementById('scholarshipModal').classList.remove('active');
}

function triggerDeleteScholarship(id, name) {
    document.getElementById('deleteScholId').value = id;
    document.getElementById('deleteScholName').innerText = name;
    document.getElementById('deleteScholModal').classList.add('active');
}

function closeDeleteScholModal() {
    document.getElementById('deleteScholModal').classList.remove('active');
}

// Auto Fetch Courses via AJAX
function autoFetchCourses(uniId, uniName, btn) {
    if (!confirm('This will search the web and automatically generate courses for ' + uniName + '. This may take a few seconds. Continue?')) {
        return;
    }
    
    const originalText = btn.innerHTML;
    btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Fetching...';
    btn.disabled = true;

    fetch('ajax_fetch_courses.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'uni_id=' + encodeURIComponent(uniId) + '&uni_name=' + encodeURIComponent(uniName)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert(data.message);
            window.location.reload();
        } else {
            alert('Error: ' + data.message);
            btn.innerHTML = originalText;
            btn.disabled = false;
        }
    })
    .catch(err => {
        alert('A network error occurred. Please try again.');
        btn.innerHTML = originalText;
        btn.disabled = false;
    });
}
</script>

<?php require_once 'includes/footer.php'; ?>
