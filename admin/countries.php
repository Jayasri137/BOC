<?php
// admin/countries.php - Study Destinations CRUD Editor with 2026 Advanced Metrics
$pageTitle = 'Destinations Manager';
require_once 'includes/header.php'; // handles session and pdo load

$alertSuccess = '';
$alertError = '';

// --- HANDLE POST REQUESTS (ADD, UPDATE, DELETE) ---
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = isset($_POST['action']) ? trim($_POST['action']) : '';
    
    // Read and sanitize new fields
    $travel_hours = isset($_POST['travel_hours']) ? trim($_POST['travel_hours']) : '';
    $study_options = isset($_POST['study_options']) ? trim($_POST['study_options']) : '';
    
    $roi_advantage = isset($_POST['roi_advantage']) ? trim($_POST['roi_advantage']) : '';
    $roi_priority = isset($_POST['roi_priority']) ? trim($_POST['roi_priority']) : '';
    $roi_wage = isset($_POST['roi_wage']) ? trim($_POST['roi_wage']) : '';
    $roi_qs = isset($_POST['roi_qs']) ? trim($_POST['roi_qs']) : '';
    
    $living_cost_local = isset($_POST['living_cost_local']) ? trim($_POST['living_cost_local']) : '';
    $living_cost_inr = isset($_POST['living_cost_inr']) ? trim($_POST['living_cost_inr']) : '';
    $visa_fee_local = isset($_POST['visa_fee_local']) ? trim($_POST['visa_fee_local']) : '';
    $visa_fee_inr = isset($_POST['visa_fee_inr']) ? trim($_POST['visa_fee_inr']) : '';
    $weekly_budget_local = isset($_POST['weekly_budget_local']) ? trim($_POST['weekly_budget_local']) : '';
    $weekly_budget_inr = isset($_POST['weekly_budget_inr']) ? trim($_POST['weekly_budget_inr']) : '';
    $earnings_potential_local = isset($_POST['earnings_potential_local']) ? trim($_POST['earnings_potential_local']) : '';
    $earnings_potential_inr = isset($_POST['earnings_potential_inr']) ? trim($_POST['earnings_potential_inr']) : '';
    
    $stayback_bachelors = isset($_POST['stayback_bachelors']) ? trim($_POST['stayback_bachelors']) : '';
    $stayback_bachelors_stem = isset($_POST['stayback_bachelors_stem']) ? trim($_POST['stayback_bachelors_stem']) : '';
    $stayback_masters = isset($_POST['stayback_masters']) ? trim($_POST['stayback_masters']) : '';
    $stayback_doctoral = isset($_POST['stayback_doctoral']) ? trim($_POST['stayback_doctoral']) : '';
    $stayback_regional = isset($_POST['stayback_regional']) ? trim($_POST['stayback_regional']) : '';
    
    $upcoming_intakes = isset($_POST['upcoming_intakes']) ? trim($_POST['upcoming_intakes']) : '';
    $demand_careers = isset($_POST['demand_careers']) ? trim($_POST['demand_careers']) : '';
    $image_url = isset($_POST['image_url']) ? trim($_POST['image_url']) : '';
    $uploaded_image_url = '';
    if (isset($_FILES['country_image']) && $_FILES['country_image']['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['country_image']['tmp_name'];
        $fileName = basename($_FILES['country_image']['name']);
        $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
        $allowedExtensions = ['jpg', 'jpeg', 'png', 'webp', 'gif', 'svg'];
        if (in_array($fileExt, $allowedExtensions, true)) {
            $uploadDir = '../assets/images/uploads/countries/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }
            $cleanName = preg_replace('/[^a-z0-9._-]+/i', '-', pathinfo($fileName, PATHINFO_FILENAME));
            $newFileName = strtolower($cleanName) . '.' . $fileExt;
            $counter = 0;
            $destination = $uploadDir . $newFileName;
            while (file_exists($destination)) {
                $counter++;
                $destination = $uploadDir . strtolower($cleanName) . '-' . $counter . '.' . $fileExt;
            }
            if (move_uploaded_file($fileTmpPath, $destination)) {
                $uploaded_image_url = 'assets/images/uploads/countries/' . basename($destination);
            }
        }
    }
    $final_image_url = !empty($uploaded_image_url) ? $uploaded_image_url : $image_url;

    // 1. ADD NEW COUNTRY
    if ($action === 'add') {
        $slug = isset($_POST['slug']) ? strtolower(trim($_POST['slug'])) : '';
        $name = isset($_POST['name']) ? trim($_POST['name']) : '';
        $flag = isset($_POST['flag']) ? trim($_POST['flag']) : '';
        $description = isset($_POST['description']) ? trim($_POST['description']) : '';
        $is_active = isset($_POST['is_active']) ? 1 : 0;
        
        // Clean slug (letters, numbers, hyphens only)
        $slug = preg_replace('/[^a-z0-9\-]/', '', $slug);
        
        if (empty($slug) || empty($name) || empty($flag) || empty($description)) {
            $alertError = 'All base fields (Slug, Name, Flag Emoji, and Description) are required.';
        } else {
            try {
                // Check if slug already exists
                $check = $pdo->prepare("SELECT COUNT(*) FROM countries WHERE slug = :slug");
                $check->execute(['slug' => $slug]);
                if ($check->fetchColumn() > 0) {
                    $alertError = 'A study destination with the slug "' . clean_output($slug) . '" already exists. Slugs must be unique!';
                } else {
                    $stmt = $pdo->prepare("
                        INSERT INTO countries (
                            slug, name, flag, description, is_active,
                            travel_hours, study_options,
                            roi_advantage, roi_priority, roi_wage, roi_qs,
                            living_cost_local, living_cost_inr, visa_fee_local, visa_fee_inr,
                            weekly_budget_local, weekly_budget_inr, earnings_potential_local, earnings_potential_inr,
                            stayback_bachelors, stayback_bachelors_stem, stayback_masters, stayback_doctoral, stayback_regional,
                            upcoming_intakes, demand_careers, image_url
                        ) VALUES (
                            :slug, :name, :flag, :description, :is_active,
                            :travel_hours, :study_options,
                            :roi_advantage, :roi_priority, :roi_wage, :roi_qs,
                            :living_cost_local, :living_cost_inr, :visa_fee_local, :visa_fee_inr,
                            :weekly_budget_local, :weekly_budget_inr, :earnings_potential_local, :earnings_potential_inr,
                            :stayback_bachelors, :stayback_bachelors_stem, :stayback_masters, :stayback_doctoral, :stayback_regional,
                            :upcoming_intakes, :demand_careers, :image_url
                        )
                    ");
                    $stmt->execute([
                        'slug' => $slug,
                        'name' => $name,
                        'flag' => $flag,
                        'description' => $description,
                        'is_active' => $is_active,
                        'travel_hours' => $travel_hours,
                        'study_options' => $study_options,
                        'roi_advantage' => $roi_advantage,
                        'roi_priority' => $roi_priority,
                        'roi_wage' => $roi_wage,
                        'roi_qs' => $roi_qs,
                        'living_cost_local' => $living_cost_local,
                        'living_cost_inr' => $living_cost_inr,
                        'visa_fee_local' => $visa_fee_local,
                        'visa_fee_inr' => $visa_fee_inr,
                        'weekly_budget_local' => $weekly_budget_local,
                        'weekly_budget_inr' => $weekly_budget_inr,
                        'earnings_potential_local' => $earnings_potential_local,
                        'earnings_potential_inr' => $earnings_potential_inr,
                        'stayback_bachelors' => $stayback_bachelors,
                        'stayback_bachelors_stem' => $stayback_bachelors_stem,
                        'stayback_masters' => $stayback_masters,
                        'stayback_doctoral' => $stayback_doctoral,
                        'stayback_regional' => $stayback_regional,
                        'upcoming_intakes' => $upcoming_intakes,
                        'demand_careers' => $demand_careers,
                        'image_url' => $final_image_url
                    ]);
                    $alertSuccess = 'Study destination added successfully!';
                }
            } catch (PDOException $e) {
                $alertError = 'Failed to add destination: ' . $e->getMessage();
            }
        }
    }
    
    // 2. UPDATE COUNTRY
    elseif ($action === 'update') {
        $id = isset($_POST['country_id']) ? intval($_POST['country_id']) : 0;
        $slug = isset($_POST['slug']) ? strtolower(trim($_POST['slug'])) : '';
        $name = isset($_POST['name']) ? trim($_POST['name']) : '';
        $flag = isset($_POST['flag']) ? trim($_POST['flag']) : '';
        $description = isset($_POST['description']) ? trim($_POST['description']) : '';
        $is_active = isset($_POST['is_active']) ? 1 : 0;
        
        $slug = preg_replace('/[^a-z0-9\-]/', '', $slug);
        
        if ($id <= 0 || empty($slug) || empty($name) || empty($flag) || empty($description)) {
            $alertError = 'Base fields (Slug, Name, Flag, and Description) are required to update the destination.';
        } else {
            try {
                // Check if slug already exists for other records
                $check = $pdo->prepare("SELECT COUNT(*) FROM countries WHERE slug = :slug AND id != :id");
                $check->execute(['slug' => $slug, 'id' => $id]);
                if ($check->fetchColumn() > 0) {
                    $alertError = 'The slug "' . clean_output($slug) . '" is already in use by another country. Slugs must be unique!';
                } else {
                    $stmt = $pdo->prepare("
                        UPDATE countries 
                        SET slug = :slug, 
                            name = :name, 
                            flag = :flag, 
                            description = :description, 
                            is_active = :is_active,
                            travel_hours = :travel_hours,
                            study_options = :study_options,
                            roi_advantage = :roi_advantage,
                            roi_priority = :roi_priority,
                            roi_wage = :roi_wage,
                            roi_qs = :roi_qs,
                            living_cost_local = :living_cost_local,
                            living_cost_inr = :living_cost_inr,
                            visa_fee_local = :visa_fee_local,
                            visa_fee_inr = :visa_fee_inr,
                            weekly_budget_local = :weekly_budget_local,
                            weekly_budget_inr = :weekly_budget_inr,
                            earnings_potential_local = :earnings_potential_local,
                            earnings_potential_inr = :earnings_potential_inr,
                            stayback_bachelors = :stayback_bachelors,
                            stayback_bachelors_stem = :stayback_bachelors_stem,
                            stayback_masters = :stayback_masters,
                            stayback_doctoral = :stayback_doctoral,
                            stayback_regional = :stayback_regional,
                            upcoming_intakes = :upcoming_intakes,
                            demand_careers = :demand_careers,
                            image_url = :image_url
                        WHERE id = :id
                    ");
                    $stmt->execute([
                        'slug' => $slug,
                        'name' => $name,
                        'flag' => $flag,
                        'description' => $description,
                        'is_active' => $is_active,
                        'travel_hours' => $travel_hours,
                        'study_options' => $study_options,
                        'roi_advantage' => $roi_advantage,
                        'roi_priority' => $roi_priority,
                        'roi_wage' => $roi_wage,
                        'roi_qs' => $roi_qs,
                        'living_cost_local' => $living_cost_local,
                        'living_cost_inr' => $living_cost_inr,
                        'visa_fee_local' => $visa_fee_local,
                        'visa_fee_inr' => $visa_fee_inr,
                        'weekly_budget_local' => $weekly_budget_local,
                        'weekly_budget_inr' => $weekly_budget_inr,
                        'earnings_potential_local' => $earnings_potential_local,
                        'earnings_potential_inr' => $earnings_potential_inr,
                        'stayback_bachelors' => $stayback_bachelors,
                        'stayback_bachelors_stem' => $stayback_bachelors_stem,
                        'stayback_masters' => $stayback_masters,
                        'stayback_doctoral' => $stayback_doctoral,
                        'stayback_regional' => $stayback_regional,
                        'upcoming_intakes' => $upcoming_intakes,
                        'demand_careers' => $demand_careers,
                        'image_url' => $final_image_url,
                        'id' => $id
                    ]);
                    $alertSuccess = 'Study destination updated successfully!';
                }
            } catch (PDOException $e) {
                $alertError = 'Failed to update destination: ' . $e->getMessage();
            }
        }
    }
    
    // 3. DELETE COUNTRY
    elseif ($action === 'delete') {
        $id = isset($_POST['country_id']) ? intval($_POST['country_id']) : 0;
        if ($id <= 0) {
            $alertError = 'Invalid country ID specified for deletion.';
        } else {
            try {
                $stmt = $pdo->prepare("DELETE FROM countries WHERE id = :id");
                $stmt->execute(['id' => $id]);
                $alertSuccess = 'Study destination deleted permanently!';
            } catch (PDOException $e) {
                $alertError = 'Failed to delete destination: ' . $e->getMessage();
            }
        }
    }
}

// Fetch all countries
$countries = [];
$setupNeeded = false;
try {
    $stmt = $pdo->query("SELECT * FROM countries ORDER BY name ASC");
    $countries = $stmt->fetchAll();
} catch (PDOException $e) {
    $setupNeeded = true;
    $alertError = 'Could not fetch countries: ' . $e->getMessage() . '. Have you initialized database tables?';
}
?>

<style>
    /* Expand the modal width for editing many parameters nicely */
    #countryModal .modal-container {
        max-width: 850px !important;
        width: 95%;
    }
    .form-section-title {
        border-bottom: 1px dashed rgba(255, 255, 255, 0.15);
        padding-bottom: 0.5rem;
        margin: 1.5rem 0 1rem;
        color: var(--accent);
        font-weight: 600;
        font-size: 1rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
</style>

<div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 2.5rem; flex-wrap: wrap; gap: 1rem;">
    <h1 class="page-title">
        Study Destinations Manager
        <span>Manage 2026 requirements, stay-back terms, finances, and high-demand careers for each study destination</span>
    </h1>
    <?php if (!$setupNeeded): ?>
        <button class="btn-pill" onclick="openAddModal()">
            <i class="fa-solid fa-plus"></i>
            <span>Add Destination Country</span>
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

<?php if ($setupNeeded): ?>
    <div class="panel-card" style="text-align: center; padding: 4rem 2rem;">
        <div style="font-size: 3.5rem; color: var(--warning); margin-bottom: 1.5rem;"><i class="fa-solid fa-database"></i></div>
        <h2>Database Setup Required</h2>
        <p style="color: var(--text-secondary); max-width: 500px; margin: 1rem auto 2rem; line-height: 1.6;">Please initialize the admin tables first by clicking below to seed default values.</p>
        <a href="setup.php" class="btn-pill" style="padding: 1rem 2rem;"><i class="fa-solid fa-screwdriver-wrench"></i> Run Database Installer</a>
    </div>
<?php else: ?>

    <?php if (empty($countries)): ?>
        <div class="panel-card" style="text-align: center; padding: 5rem 2rem; color: var(--text-secondary);">
            <i class="fa-solid fa-folder-open" style="font-size: 3rem; margin-bottom: 1.5rem; color: var(--text-muted);"></i>
            <h3>No study destination countries exist in the database.</h3>
            <p style="margin-top: 0.5rem; font-size: 0.9rem;">Click the "Add Destination Country" button to create your first country!</p>
        </div>
    <?php else: ?>
        <div class="crud-grid">
            <?php foreach ($countries as $c): 
                $isActive = intval($c['is_active']) === 1;
                $slug = clean_output($c['slug']);
                $flag = clean_output($c['flag']);
                $name = clean_output($c['name']);
            ?>
                <div class="crud-card <?php echo $isActive ? 'crud-card-active' : 'crud-card-inactive'; ?>">
                    <div class="crud-card-badge-icon">
                        <i class="fa-solid <?php echo $isActive ? 'fa-check' : 'fa-eye-slash'; ?>"></i>
                    </div>
                    
                    <div class="crud-card-header">
                        <div class="crud-card-icon icon-blue" style="font-size: 2rem; background: rgba(255,255,255,0.03); border: 1px solid var(--border);">
                            <?php echo $flag; ?>
                        </div>
                        <div style="flex: 1;">
                            <h4 class="crud-card-title" style="margin-bottom: 0.15rem;"><?php echo $name; ?></h4>
                            <span style="font-size: 0.75rem; color: var(--accent); font-family: monospace;">Slug: <?php echo $slug; ?></span>
                        </div>
                    </div>
                    
                    <p class="crud-card-desc"><?php echo clean_output($c['description']); ?></p>
                    
                    <div style="padding: 0 1.5rem; margin-bottom: 1rem; font-size: 0.8rem; color: var(--text-muted); display: grid; grid-template-columns: 1fr 1fr; gap: 0.5rem;">
                        <div><i class="fa-solid fa-plane"></i> <?php echo !empty($c['travel_hours']) ? clean_output($c['travel_hours']) : 'Not set'; ?></div>
                        <div><i class="fa-solid fa-wallet"></i> <?php echo !empty($c['living_cost_local']) ? clean_output($c['living_cost_local']) : 'Not set'; ?></div>
                    </div>
                    
                    <div class="crud-card-footer">
                        <span class="crud-card-info">URL: <code style="color: var(--text-secondary);">study-in-<?php echo $slug; ?>.php</code></span>
                        <div class="crud-card-actions">
                            <button class="btn-action action-edit" title="Edit Country Details & 2026 Metrics" onclick="openEditModal(<?php echo htmlspecialchars(json_encode($c)); ?>)">
                                <i class="fa-solid fa-pen-to-square"></i>
                            </button>
                            <button class="btn-action action-delete" title="Delete Country" onclick="triggerDeleteCountry(<?php echo $c['id']; ?>, '<?php echo $name; ?>')">
                                <i class="fa-solid fa-trash-can"></i>
                            </button>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

<?php endif; ?>

<!-- 1. ADD / EDIT DIALOG MODAL -->
<div class="modal-overlay" id="countryModal">
    <div class="modal-container">
        <div class="modal-header">
            <h3 class="modal-title" id="modalTitle">Add Destination Country</h3>
            <span class="modal-close" onclick="closeCountryModal()">&times;</span>
        </div>
        <form action="countries.php" method="POST" enctype="multipart/form-data">
            <div class="modal-body" style="max-height: 70vh; overflow-y: auto; padding-right: 0.5rem;">
                <input type="hidden" name="action" id="modalAction" value="add">
                <input type="hidden" name="country_id" id="edit_country_id">
                
                <div class="form-section-title" style="margin-top: 0;"><i class="fa-solid fa-circle-info"></i> Base Information</div>
                <div class="detail-grid">
                    <div class="form-group">
                        <label for="c_name" class="form-label">Country Name *</label>
                        <input type="text" name="name" id="c_name" class="form-control" placeholder="e.g., Australia" oninput="generateSlug(this.value)" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="c_flag" class="form-label">Country Flag Emoji *</label>
                        <input type="text" name="flag" id="c_flag" class="form-control" placeholder="e.g., 🇦🇺" required>
                    </div>
                </div>

                <div class="detail-grid">
                    <div class="form-group">
                        <label for="c_slug" class="form-label">Destination URL Slug *</label>
                        <input type="text" name="slug" id="c_slug" class="form-control" placeholder="e.g., australia" required>
                    </div>
                    <div class="form-group">
                        <label for="c_travel_hours" class="form-label">Distance / Flight Duration</label>
                        <input type="text" name="travel_hours" id="c_travel_hours" class="form-control" placeholder="e.g., Approx 12-14 hours (Flight from India)">
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="c_study_options" class="form-label">Study Options / Programs Available</label>
                    <input type="text" name="study_options" id="c_study_options" class="form-control" placeholder="e.g., Bachelor's, Master's, MBA, Doctoral (PhD)">
                </div>

                <div class="form-group">
                    <label for="c_desc" class="form-label">Overview Description *</label>
                    <textarea name="description" id="c_desc" class="form-control" rows="3" placeholder="Enter study abroad overview description..." required></textarea>
                </div>

                <div class="detail-grid">
                    <div class="form-group">
                        <label for="c_image_url" class="form-label">Country Image Path</label>
                        <input type="text" name="image_url" id="c_image_url" class="form-control" placeholder="e.g., assets/images/countries/australia.jpg">
                    </div>
                    <div class="form-group">
                        <label for="c_country_image" class="form-label">Upload Country Image</label>
                        <input type="file" name="country_image" id="c_country_image" class="form-control" accept="image/*">
                    </div>
                </div>

                <!-- ROI MODULE -->
                <div class="form-section-title"><i class="fa-solid fa-chart-line"></i> Module 1: The "ROI" Hero Highlights</div>
                <div class="detail-grid">
                    <div class="form-group">
                        <label for="c_roi_advantage" class="form-label">Indian Advantage Bullet Point</label>
                        <input type="text" name="roi_advantage" id="c_roi_advantage" class="form-control" placeholder="e.g., Indian Advantage: Exclusive 3-Year stay-back for Master’s grads">
                    </div>
                    <div class="form-group">
                        <label for="c_roi_priority" class="form-label">Priority Triage Bullet Point</label>
                        <input type="text" name="roi_priority" id="c_roi_priority" class="form-control" placeholder="e.g., Priority Triage: Fast-track visa processing">
                    </div>
                </div>
                <div class="detail-grid">
                    <div class="form-group">
                        <label for="c_roi_wage" class="form-label">Minimum Wage Bullet Point</label>
                        <input type="text" name="roi_wage" id="c_roi_wage" class="form-control" placeholder="e.g., Minimum Wage: $24.95 AUD/hr">
                    </div>
                    <div class="form-group">
                        <label for="c_roi_qs" class="form-label">QS Power Bullet Point</label>
                        <input type="text" name="roi_qs" id="c_roi_qs" class="form-control" placeholder="e.g., QS Power: 6 Universities in the World’s Top 50">
                    </div>
                </div>

                <!-- FINANCIALS MODULE -->
                <div class="form-section-title"><i class="fa-solid fa-coins"></i> Module 2: 2026 Transparent Financial Requirements</div>
                <div class="detail-grid" style="grid-template-columns: repeat(4, 1fr);">
                    <div class="form-group">
                        <label class="form-label">Annual Living (Local)</label>
                        <input type="text" name="living_cost_local" id="c_living_cost_local" class="form-control" placeholder="e.g., $29,710 AUD">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Annual Living (INR)</label>
                        <input type="text" name="living_cost_inr" id="c_living_cost_inr" class="form-control" placeholder="e.g., ₹16.8 Lakhs">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Student Visa (Local)</label>
                        <input type="text" name="visa_fee_local" id="c_visa_fee_local" class="form-control" placeholder="e.g., $2,000 AUD">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Student Visa (INR)</label>
                        <input type="text" name="visa_fee_inr" id="c_visa_fee_inr" class="form-control" placeholder="e.g., ₹1.1 Lakhs">
                    </div>
                </div>
                <div class="detail-grid" style="grid-template-columns: repeat(4, 1fr);">
                    <div class="form-group">
                        <label class="form-label">Weekly Budget (Local)</label>
                        <input type="text" name="weekly_budget_local" id="c_weekly_budget_local" class="form-control" placeholder="e.g., $450 - $650 AUD">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Weekly Budget (Details)</label>
                        <input type="text" name="weekly_budget_inr" id="c_weekly_budget_inr" class="form-control" placeholder="e.g., Rent, Food & Transit">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Earnings (Local)</label>
                        <input type="text" name="earnings_potential_local" id="c_earnings_potential_local" class="form-control" placeholder="e.g., $48,000+ AUD">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Earnings (Details)</label>
                        <input type="text" name="earnings_potential_inr" id="c_earnings_potential_inr" class="form-control" placeholder="e.g., If working maximum hours">
                    </div>
                </div>

                <!-- STAY BACK MODULE -->
                <div class="form-section-title"><i class="fa-solid fa-clock-rotate-left"></i> Module 5: Stay-back & AI-ECTA Trade Rights</div>
                <div class="detail-grid">
                    <div class="form-group">
                        <label class="form-label">Bachelor's (General) Stay-back</label>
                        <input type="text" name="stayback_bachelors" id="c_stayback_bachelors" class="form-control" placeholder="e.g., 2 Years">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Bachelor's (STEM 1st Class) Stay-back</label>
                        <input type="text" name="stayback_bachelors_stem" id="c_stayback_bachelors_stem" class="form-control" placeholder="e.g., 3 Years (STEM 1st Class)">
                    </div>
                </div>
                <div class="detail-grid" style="grid-template-columns: repeat(3, 1fr);">
                    <div class="form-group">
                        <label class="form-label">Master's Stay-back</label>
                        <input type="text" name="stayback_masters" id="c_stayback_masters" class="form-control" placeholder="e.g., 3 Years (AI-ECTA)">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Doctoral (PhD) Stay-back</label>
                        <input type="text" name="stayback_doctoral" id="c_stayback_doctoral" class="form-control" placeholder="e.g., 4 Years">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Regional Cities Bonus</label>
                        <input type="text" name="stayback_regional" id="c_stayback_regional" class="form-control" placeholder="e.g., +1 Year (Up to 6 Years Total)">
                    </div>
                </div>

                <!-- INTAKES & CAREERS -->
                <div class="form-section-title"><i class="fa-solid fa-calendar-days"></i> Module 6: Upcoming Intakes & Careers</div>
                <div class="detail-grid">
                    <div class="form-group">
                        <label for="c_upcoming_intakes" class="form-label">Upcoming Intakes & Deadlines (One per line)</label>
                        <textarea name="upcoming_intakes" id="c_upcoming_intakes" class="form-control" rows="3" placeholder="e.g., February 2027 (Major Intake) | Deadline: October 2026&#10;July 2026 / 2027 (Secondary Intake) | Deadline: March / May"></textarea>
                    </div>
                    <div class="form-group">
                        <label for="c_demand_careers" class="form-label">High-Demand Careers (One per line)</label>
                        <textarea name="demand_careers" id="c_demand_careers" class="form-control" rows="3" placeholder="e.g., AI Engineers&#10;Renewable Energy Specialists&#10;Registered Nurses&#10;Cyber Security Experts"></textarea>
                    </div>
                </div>

                <div class="form-group" style="margin-bottom: 0; display: flex; align-items: center; gap: 0.5rem; margin-top: 1rem;">
                    <input type="checkbox" name="is_active" id="c_active" checked style="width: 18px; height: 18px; accent-color: var(--primary); cursor: pointer;">
                    <label for="c_active" class="form-label" style="margin-bottom: 0; cursor: pointer; user-select: none;">Publish immediately (Active & Visible in marquee slider)</label>
                </div>
            </div>
            
            <div class="modal-footer">
                <button type="button" class="btn-outline" onclick="closeCountryModal()">Cancel</button>
                <button type="submit" class="btn-pill">
                    <i class="fa-solid fa-floppy-disk"></i>
                    <span>Save Destination</span>
                </button>
            </div>
        </form>
    </div>
</div>

<!-- 2. DELETE CONFIRMATION MODAL -->
<div class="modal-overlay" id="deleteModal">
    <div class="modal-container" style="max-width: 400px;">
        <div class="modal-header" style="border-bottom: none; padding-bottom: 0;">
            <h3 class="modal-title" style="color: var(--danger);"><i class="fa-solid fa-triangle-exclamation"></i> Delete Destination?</h3>
            <span class="modal-close" onclick="closeDeleteModal()">&times;</span>
        </div>
        <form action="countries.php" method="POST">
            <div class="modal-body" style="padding-top: 1rem;">
                <input type="hidden" name="action" value="delete">
                <input type="hidden" name="country_id" id="deleteCountryId">
                <p style="font-size: 0.95rem; line-height: 1.6; color: var(--text-secondary);">
                    Are you sure you want to permanently delete the study destination <strong id="deleteCountryName" style="color: var(--text-primary);">Country Name</strong>?
                </p>
                <p style="font-size: 0.8rem; color: var(--text-muted); margin-top: 0.75rem;">
                    This country marquee card will instantly vanish. It cannot be recovered.
                </p>
            </div>
            <div class="modal-footer" style="border-top: none; padding-top: 0;">
                <button type="button" class="btn-outline" onclick="closeDeleteModal()">Cancel</button>
                <button type="submit" class="btn-pill" style="background: var(--danger); box-shadow: 0 5px 10px var(--danger-glow);">
                    <i class="fa-solid fa-trash-can"></i>
                    <span>Confirm Delete</span>
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function generateSlug(text) {
    if (document.getElementById('modalAction').value === 'add') {
        const slug = text
            .toString()
            .toLowerCase()
            .replace(/\s+/g, '-')           // Replace spaces with -
            .replace(/[^\w\-]+/g, '')       // Remove all non-word chars
            .replace(/\-\-+/g, '-')         // Replace multiple - with single -
            .replace(/^-+/, '')             // Trim - from start
            .replace(/-+$/, '');            // Trim - from end
        document.getElementById('c_slug').value = slug;
    }
}

function openAddModal() {
    document.getElementById('modalAction').value = 'add';
    document.getElementById('modalTitle').innerText = 'Add Destination Country';
    document.getElementById('edit_country_id').value = '';
    document.getElementById('c_name').value = '';
    document.getElementById('c_flag').value = '';
    document.getElementById('c_slug').value = '';
    document.getElementById('c_desc').value = '';
    document.getElementById('c_active').checked = true;
    
    // Clear advanced fields
    document.getElementById('c_travel_hours').value = '';
    document.getElementById('c_study_options').value = '';
    document.getElementById('c_image_url').value = '';
    document.getElementById('c_country_image').value = '';
    document.getElementById('c_roi_advantage').value = '';
    document.getElementById('c_roi_priority').value = '';
    document.getElementById('c_roi_wage').value = '';
    document.getElementById('c_roi_qs').value = '';
    
    document.getElementById('c_living_cost_local').value = '';
    document.getElementById('c_living_cost_inr').value = '';
    document.getElementById('c_visa_fee_local').value = '';
    document.getElementById('c_visa_fee_inr').value = '';
    document.getElementById('c_weekly_budget_local').value = '';
    document.getElementById('c_weekly_budget_inr').value = '';
    document.getElementById('c_earnings_potential_local').value = '';
    document.getElementById('c_earnings_potential_inr').value = '';
    
    document.getElementById('c_stayback_bachelors').value = '';
    document.getElementById('c_stayback_bachelors_stem').value = '';
    document.getElementById('c_stayback_masters').value = '';
    document.getElementById('c_stayback_doctoral').value = '';
    document.getElementById('c_stayback_regional').value = '';
    
    document.getElementById('c_upcoming_intakes').value = '';
    document.getElementById('c_demand_careers').value = '';
    
    document.getElementById('countryModal').classList.add('active');
}

function openEditModal(c) {
    document.getElementById('modalAction').value = 'update';
    document.getElementById('modalTitle').innerText = 'Edit Country: ' + c.name;
    document.getElementById('edit_country_id').value = c.id;
    document.getElementById('c_name').value = c.name;
    document.getElementById('c_flag').value = c.flag;
    document.getElementById('c_slug').value = c.slug;
    document.getElementById('c_desc').value = c.description;
    document.getElementById('c_image_url').value = c.image_url || '';
    document.getElementById('c_country_image').value = '';
    document.getElementById('c_active').checked = parseInt(c.is_active) === 1;
    
    // Populate advanced fields with database defaults or empty strings
    document.getElementById('c_travel_hours').value = c.travel_hours || '';
    document.getElementById('c_study_options').value = c.study_options || '';
    document.getElementById('c_roi_advantage').value = c.roi_advantage || '';
    document.getElementById('c_roi_priority').value = c.roi_priority || '';
    document.getElementById('c_roi_wage').value = c.roi_wage || '';
    document.getElementById('c_roi_qs').value = c.roi_qs || '';
    
    document.getElementById('c_living_cost_local').value = c.living_cost_local || '';
    document.getElementById('c_living_cost_inr').value = c.living_cost_inr || '';
    document.getElementById('c_visa_fee_local').value = c.visa_fee_local || '';
    document.getElementById('c_visa_fee_inr').value = c.visa_fee_inr || '';
    document.getElementById('c_weekly_budget_local').value = c.weekly_budget_local || '';
    document.getElementById('c_weekly_budget_inr').value = c.weekly_budget_inr || '';
    document.getElementById('c_earnings_potential_local').value = c.earnings_potential_local || '';
    document.getElementById('c_earnings_potential_inr').value = c.earnings_potential_inr || '';
    
    document.getElementById('c_stayback_bachelors').value = c.stayback_bachelors || '';
    document.getElementById('c_stayback_bachelors_stem').value = c.stayback_bachelors_stem || '';
    document.getElementById('c_stayback_masters').value = c.stayback_masters || '';
    document.getElementById('c_stayback_doctoral').value = c.stayback_doctoral || '';
    document.getElementById('c_stayback_regional').value = c.stayback_regional || '';
    
    document.getElementById('c_upcoming_intakes').value = c.upcoming_intakes || '';
    document.getElementById('c_demand_careers').value = c.demand_careers || '';
    
    document.getElementById('countryModal').classList.add('active');
}

function closeCountryModal() {
    document.getElementById('countryModal').classList.remove('active');
}

function triggerDeleteCountry(id, name) {
    document.getElementById('deleteCountryId').value = id;
    document.getElementById('deleteCountryName').innerText = name;
    document.getElementById('deleteModal').classList.add('active');
}

function closeDeleteModal() {
    document.getElementById('deleteModal').classList.remove('active');
}
</script>

<?php require_once 'includes/footer.php'; ?>
