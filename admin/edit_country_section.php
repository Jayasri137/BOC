<?php
// admin/edit_country_section.php
$pageTitle = 'Edit Country Section';
require_once 'includes/header.php';

$alertSuccess = '';
$alertError = '';

$country_id = isset($_GET['country_id']) ? intval($_GET['country_id']) : 0;
$section = isset($_GET['section']) ? trim($_GET['section']) : '';

if ($country_id <= 0 || empty($section)) {
    echo "<div class='alert alert-danger'>Invalid parameters.</div>";
    require_once 'includes/footer.php';
    exit;
}

// Map sections to their user-friendly titles and icons
$sectionMeta = [
    'overview' => ['title' => 'Overview & Highlights', 'icon' => 'fa-circle-info'],
    'cost' => ['title' => 'Cost of Studying', 'icon' => 'fa-wallet'],
    'scholarships' => ['title' => 'Scholarships', 'icon' => 'fa-graduation-cap'],
    'intakes' => ['title' => 'Upcoming Intakes', 'icon' => 'fa-calendar-days'],
    'eligibility' => ['title' => 'Eligibility Criteria', 'icon' => 'fa-check-double'],
    'exams' => ['title' => 'Exams Required', 'icon' => 'fa-file-signature'],
    'visa' => ['title' => 'Visa Guide', 'icon' => 'fa-passport'],
    'jobs' => ['title' => 'Jobs & Careers', 'icon' => 'fa-briefcase'],
    'cities' => ['title' => 'Top Cities', 'icon' => 'fa-city'],
    'admits' => ['title' => 'Top Admits', 'icon' => 'fa-award'],
    'news' => ['title' => 'News Articles', 'icon' => 'fa-newspaper']
];

if (!isset($sectionMeta[$section])) {
    echo "<div class='alert alert-danger'>Unknown section.</div>";
    require_once 'includes/footer.php';
    exit;
}

// Fetch the country
try {
    $stmt = $pdo->prepare("SELECT * FROM countries WHERE id = :id");
    $stmt->execute(['id' => $country_id]);
    $country = $stmt->fetch();
    
    if (!$country) {
        echo "<div class='alert alert-danger'>Country not found.</div>";
        require_once 'includes/footer.php';
        exit;
    }
} catch (PDOException $e) {
    echo "<div class='alert alert-danger'>Database error: " . $e->getMessage() . "</div>";
    require_once 'includes/footer.php';
    exit;
}

// Handle Form Submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'update_section') {
    $updateFields = [];
    $params = ['id' => $country_id];
    
    // Process text fields based on section
    if ($section === 'overview') {
        $fields = ['description', 'study_options', 'roi_advantage', 'roi_priority', 'roi_wage', 'roi_qs'];
        foreach($fields as $f) {
            $updateFields[] = "$f = :$f";
            $params[$f] = isset($_POST[$f]) ? trim($_POST[$f]) : '';
        }
    } elseif ($section === 'cost') {
        $fields = ['living_cost_local', 'living_cost_inr', 'weekly_budget_local', 'weekly_budget_inr'];
        foreach($fields as $f) {
            $updateFields[] = "$f = :$f";
            $params[$f] = isset($_POST[$f]) ? trim($_POST[$f]) : '';
        }
    } elseif ($section === 'intakes') {
        $updateFields[] = "upcoming_intakes = :upcoming_intakes";
        $params['upcoming_intakes'] = isset($_POST['upcoming_intakes']) ? trim($_POST['upcoming_intakes']) : '';
    } elseif ($section === 'visa') {
        $fields = ['visa_fee_local', 'visa_fee_inr'];
        foreach($fields as $f) {
            $updateFields[] = "$f = :$f";
            $params[$f] = isset($_POST[$f]) ? trim($_POST[$f]) : '';
        }
    } elseif ($section === 'jobs') {
        $fields = ['stayback_bachelors', 'stayback_bachelors_stem', 'stayback_masters', 'stayback_doctoral', 'stayback_regional', 'earnings_potential_local', 'earnings_potential_inr', 'demand_careers'];
        foreach($fields as $f) {
            $updateFields[] = "$f = :$f";
            $params[$f] = isset($_POST[$f]) ? trim($_POST[$f]) : '';
        }
    }
    
    // Handle specific section image upload
    $imgField = $section . '_image';
    if (in_array($section, ['overview', 'cost', 'scholarships', 'intakes', 'eligibility', 'exams', 'visa', 'jobs', 'cities', 'admits', 'news'])) {
        if (isset($_FILES[$imgField . '_file']) && $_FILES[$imgField . '_file']['error'] === UPLOAD_ERR_OK) {
            $fileTmpPath = $_FILES[$imgField . '_file']['tmp_name'];
            $fileName = basename($_FILES[$imgField . '_file']['name']);
            $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
            if (in_array($fileExt, ['jpg', 'jpeg', 'png', 'webp', 'gif', 'svg'], true)) {
                $uploadDir = '../assets/images/uploads/countries/';
                if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);
                $cleanName = preg_replace('/[^a-z0-9._-]+/i', '-', pathinfo($fileName, PATHINFO_FILENAME));
                $newFileName = strtolower($cleanName) . '-' . $section . '.' . $fileExt;
                $counter = 0;
                $destination = $uploadDir . $newFileName;
                while (file_exists($destination)) {
                    $counter++;
                    $destination = $uploadDir . strtolower($cleanName) . '-' . $section . '-' . $counter . '.' . $fileExt;
                }
                if (move_uploaded_file($fileTmpPath, $destination)) {
                    $updateFields[] = "$imgField = :$imgField";
                    $params[$imgField] = 'assets/images/uploads/countries/' . basename($destination);
                }
            }
        }
    }
    
    if (!empty($updateFields)) {
        try {
            $sql = "UPDATE countries SET " . implode(', ', $updateFields) . " WHERE id = :id";
            $stmt = $pdo->prepare($sql);
            $stmt->execute($params);
            $alertSuccess = "Section updated successfully!";
            // Refresh country data
            $stmt = $pdo->prepare("SELECT * FROM countries WHERE id = :id");
            $stmt->execute(['id' => $country_id]);
            $country = $stmt->fetch();
        } catch (PDOException $e) {
            $alertError = "Error updating section: " . $e->getMessage();
        }
    }
}
?>

<div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 2.5rem; flex-wrap: wrap; gap: 1rem;">
    <h1 class="page-title" style="margin-bottom:0;">
        Editing: <?php echo htmlspecialchars($country['name']); ?>
        <span>Section: <i class="fa-solid <?php echo $sectionMeta[$section]['icon']; ?>" style="margin-inline: 0.5rem; color:var(--primary);"></i> <?php echo $sectionMeta[$section]['title']; ?></span>
    </h1>
    <a href="countries.php" class="btn-outline">
        <i class="fa-solid fa-arrow-left"></i> Back to Destinations
    </a>
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

<div class="panel-card" style="padding: 2.5rem;">
    <form action="" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="action" value="update_section">
        
        <?php if ($section === 'overview'): ?>
            <div class="form-group">
                <label class="form-label">Overview Description</label>
                <textarea name="description" class="form-control" rows="4"><?php echo htmlspecialchars($country['description'] ?? ''); ?></textarea>
            </div>
            <div class="form-group">
                <label class="form-label">Study Options / Programs Available</label>
                <input type="text" name="study_options" class="form-control" value="<?php echo htmlspecialchars($country['study_options'] ?? ''); ?>">
            </div>
            
            <div class="form-section-title" style="margin-top: 2rem; margin-bottom:1rem; font-weight:600; color:var(--primary);"><i class="fa-solid fa-chart-line"></i> ROI Highlights</div>
            <div class="detail-grid" style="display:grid; grid-template-columns:1fr 1fr; gap:1.5rem; margin-bottom:1.5rem;">
                <div class="form-group" style="margin-bottom:0;">
                    <label class="form-label">Indian Advantage</label>
                    <input type="text" name="roi_advantage" class="form-control" value="<?php echo htmlspecialchars($country['roi_advantage'] ?? ''); ?>">
                </div>
                <div class="form-group" style="margin-bottom:0;">
                    <label class="form-label">Priority Triage</label>
                    <input type="text" name="roi_priority" class="form-control" value="<?php echo htmlspecialchars($country['roi_priority'] ?? ''); ?>">
                </div>
                <div class="form-group" style="margin-bottom:0;">
                    <label class="form-label">Minimum Wage</label>
                    <input type="text" name="roi_wage" class="form-control" value="<?php echo htmlspecialchars($country['roi_wage'] ?? ''); ?>">
                </div>
                <div class="form-group" style="margin-bottom:0;">
                    <label class="form-label">QS Power</label>
                    <input type="text" name="roi_qs" class="form-control" value="<?php echo htmlspecialchars($country['roi_qs'] ?? ''); ?>">
                </div>
            </div>
        <?php endif; ?>

        <?php if ($section === 'cost'): ?>
            <div class="detail-grid" style="display:grid; grid-template-columns:1fr 1fr; gap:1.5rem; margin-bottom:1.5rem;">
                <div class="form-group" style="margin-bottom:0;">
                    <label class="form-label">Annual Living (Local Currency)</label>
                    <input type="text" name="living_cost_local" class="form-control" value="<?php echo htmlspecialchars($country['living_cost_local'] ?? ''); ?>">
                </div>
                <div class="form-group" style="margin-bottom:0;">
                    <label class="form-label">Annual Living (INR)</label>
                    <input type="text" name="living_cost_inr" class="form-control" value="<?php echo htmlspecialchars($country['living_cost_inr'] ?? ''); ?>">
                </div>
                <div class="form-group" style="margin-bottom:0;">
                    <label class="form-label">Weekly Budget (Local Currency)</label>
                    <input type="text" name="weekly_budget_local" class="form-control" value="<?php echo htmlspecialchars($country['weekly_budget_local'] ?? ''); ?>">
                </div>
                <div class="form-group" style="margin-bottom:0;">
                    <label class="form-label">Weekly Budget (Details/INR)</label>
                    <input type="text" name="weekly_budget_inr" class="form-control" value="<?php echo htmlspecialchars($country['weekly_budget_inr'] ?? ''); ?>">
                </div>
            </div>
        <?php endif; ?>
        
        <?php if ($section === 'intakes'): ?>
            <div class="form-group">
                <label class="form-label">Upcoming Intakes & Deadlines (One per line)</label>
                <textarea name="upcoming_intakes" class="form-control" rows="6"><?php echo htmlspecialchars($country['upcoming_intakes'] ?? ''); ?></textarea>
                <small style="color:var(--text-muted); display:block; margin-top:0.5rem;">Example: February 2027 (Major Intake) | Deadline: October 2026</small>
            </div>
        <?php endif; ?>

        <?php if ($section === 'visa'): ?>
            <div class="detail-grid" style="display:grid; grid-template-columns:1fr 1fr; gap:1.5rem; margin-bottom:1.5rem;">
                <div class="form-group" style="margin-bottom:0;">
                    <label class="form-label">Student Visa Fee (Local Currency)</label>
                    <input type="text" name="visa_fee_local" class="form-control" value="<?php echo htmlspecialchars($country['visa_fee_local'] ?? ''); ?>">
                </div>
                <div class="form-group" style="margin-bottom:0;">
                    <label class="form-label">Student Visa Fee (INR)</label>
                    <input type="text" name="visa_fee_inr" class="form-control" value="<?php echo htmlspecialchars($country['visa_fee_inr'] ?? ''); ?>">
                </div>
            </div>
        <?php endif; ?>

        <?php if ($section === 'jobs'): ?>
            <div class="form-section-title" style="margin-bottom:1rem; font-weight:600; color:var(--primary);"><i class="fa-solid fa-clock-rotate-left"></i> Stay-back Options</div>
            <div class="detail-grid" style="display:grid; grid-template-columns:1fr 1fr; gap:1.5rem; margin-bottom:1.5rem;">
                <div class="form-group" style="margin-bottom:0;">
                    <label class="form-label">Bachelor's Stay-back</label>
                    <input type="text" name="stayback_bachelors" class="form-control" value="<?php echo htmlspecialchars($country['stayback_bachelors'] ?? ''); ?>">
                </div>
                <div class="form-group" style="margin-bottom:0;">
                    <label class="form-label">Bachelor's (STEM) Stay-back</label>
                    <input type="text" name="stayback_bachelors_stem" class="form-control" value="<?php echo htmlspecialchars($country['stayback_bachelors_stem'] ?? ''); ?>">
                </div>
                <div class="form-group" style="margin-bottom:0;">
                    <label class="form-label">Master's Stay-back</label>
                    <input type="text" name="stayback_masters" class="form-control" value="<?php echo htmlspecialchars($country['stayback_masters'] ?? ''); ?>">
                </div>
                <div class="form-group" style="margin-bottom:0;">
                    <label class="form-label">Doctoral Stay-back</label>
                    <input type="text" name="stayback_doctoral" class="form-control" value="<?php echo htmlspecialchars($country['stayback_doctoral'] ?? ''); ?>">
                </div>
                <div class="form-group" style="margin-bottom:0;">
                    <label class="form-label">Regional Cities Bonus</label>
                    <input type="text" name="stayback_regional" class="form-control" value="<?php echo htmlspecialchars($country['stayback_regional'] ?? ''); ?>">
                </div>
            </div>
            
            <div class="form-section-title" style="margin-top:2rem; margin-bottom:1rem; font-weight:600; color:var(--primary);"><i class="fa-solid fa-money-bill-trend-up"></i> Careers & Earnings</div>
            <div class="detail-grid" style="display:grid; grid-template-columns:1fr 1fr; gap:1.5rem; margin-bottom:1.5rem;">
                <div class="form-group" style="margin-bottom:0;">
                    <label class="form-label">Earnings Potential (Local Currency)</label>
                    <input type="text" name="earnings_potential_local" class="form-control" value="<?php echo htmlspecialchars($country['earnings_potential_local'] ?? ''); ?>">
                </div>
                <div class="form-group" style="margin-bottom:0;">
                    <label class="form-label">Earnings Potential (INR/Details)</label>
                    <input type="text" name="earnings_potential_inr" class="form-control" value="<?php echo htmlspecialchars($country['earnings_potential_inr'] ?? ''); ?>">
                </div>
            </div>
            <div class="form-group">
                <label class="form-label">High-Demand Careers (One per line)</label>
                <textarea name="demand_careers" class="form-control" rows="4"><?php echo htmlspecialchars($country['demand_careers'] ?? ''); ?></textarea>
            </div>
        <?php endif; ?>

        <!-- SECTION IMAGE UPLOAD (FOR ALL SECTIONS) -->
        <div class="form-section-title" style="margin-top:2rem; margin-bottom:1rem; font-weight:600; color:var(--primary);"><i class="fa-solid fa-image"></i> Section Display Image</div>
        <div style="display:flex; gap: 2rem; align-items: flex-start; background: #f8fafc; padding: 1.5rem; border-radius: 12px; border: 1px dashed var(--border);">
            <div style="flex:1;">
                <label class="form-label">Upload New Image for <?php echo $sectionMeta[$section]['title']; ?></label>
                <input type="file" name="<?php echo $section; ?>_image_file" class="form-control" accept="image/*">
                <p style="font-size: 0.85rem; color: var(--text-muted); margin-top:0.5rem; margin-bottom:0;">Recommended size: 1920x600 for banners.</p>
            </div>
            <?php 
            $currentImg = $country[$section . '_image'] ?? '';
            if (!empty($currentImg)): ?>
            <div style="flex-shrink:0; text-align:center;">
                <span style="display:block; font-size:0.8rem; color:var(--text-secondary); margin-bottom:0.5rem; font-weight:500;">Current Image</span>
                <img src="../<?php echo htmlspecialchars($currentImg); ?>" alt="Current Image" style="width: 150px; height: 100px; object-fit: cover; border-radius: 8px; border: 2px solid #fff; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
            </div>
            <?php endif; ?>
        </div>
        
        <div style="margin-top: 3rem; padding-top: 1.5rem; border-top: 1px solid var(--border); display:flex; justify-content:flex-end;">
            <button type="submit" class="btn-pill">
                <i class="fa-solid fa-floppy-disk"></i> Save Section Changes
            </button>
        </div>
    </form>
</div>

<?php require_once 'includes/footer.php'; ?>
