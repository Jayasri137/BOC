<?php
// admin/test_prep.php - Test Prep CRUD Editor with Local Image Upload Support
$pageTitle = 'Test Preparation Manager';
require_once 'includes/header.php';

$alertSuccess = '';
$alertError = '';

// --- HANDLE POST REQUESTS (ADD, UPDATE, DELETE) ---
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = isset($_POST['action']) ? trim($_POST['action']) : '';
    
    // 1. ADD NEW TEST
    if ($action === 'add') {
        $slug = isset($_POST['slug']) ? trim($_POST['slug']) : '';
        $name = isset($_POST['name']) ? trim($_POST['name']) : '';
        $icon = isset($_POST['icon']) ? trim($_POST['icon']) : 'fa-pen-to-square';
        $description = isset($_POST['description']) ? trim($_POST['description']) : '';
        $feature1 = isset($_POST['feature1']) ? trim($_POST['feature1']) : '';
        $feature2 = isset($_POST['feature2']) ? trim($_POST['feature2']) : '';
        $feature3 = isset($_POST['feature3']) ? trim($_POST['feature3']) : '';
        $feature4 = isset($_POST['feature4']) ? trim($_POST['feature4']) : '';
        $color = isset($_POST['color']) ? trim($_POST['color']) : 'blue';
        $image_path = isset($_POST['image_path']) ? trim($_POST['image_path']) : '';
        $is_active = isset($_POST['is_active']) ? 1 : 0;
        
        // Handle local image upload
        $uploaded_path = '';
        if (isset($_FILES['prep_file']) && $_FILES['prep_file']['error'] === UPLOAD_ERR_OK) {
            $fileTmpPath = $_FILES['prep_file']['tmp_name'];
            $fileName = $_FILES['prep_file']['name'];
            $fileNameCmps = explode(".", $fileName);
            $fileExtension = strtolower(end($fileNameCmps));
            
            $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg'];
            if (in_array($fileExtension, $allowedExtensions)) {
                $newFileName = md5(time() . $fileName) . '.' . $fileExtension;
                $uploadFileDir = '../assets/images/uploads/';
                if (!is_dir($uploadFileDir)) {
                    mkdir($uploadFileDir, 0755, true);
                }
                
                $dest_path = $uploadFileDir . $newFileName;
                if (move_uploaded_file($fileTmpPath, $dest_path)) {
                    $uploaded_path = 'assets/images/uploads/' . $newFileName;
                }
            }
        }
        
        $final_image_source = !empty($uploaded_path) ? $uploaded_path : $image_path;
        
        if (empty($slug) || empty($name) || empty($description)) {
            $alertError = 'Slug, Exam Name, and Description are required fields.';
        } else {
            try {
                $stmt = $pdo->prepare("
                    INSERT INTO test_preps (slug, name, icon, description, feature1, feature2, feature3, feature4, color, image_path, is_active) 
                    VALUES (:slug, :name, :icon, :description, :feature1, :feature2, :feature3, :feature4, :color, :image_path, :is_active)
                ");
                $stmt->execute([
                    'slug' => $slug,
                    'name' => $name,
                    'icon' => $icon,
                    'description' => $description,
                    'feature1' => $feature1,
                    'feature2' => $feature2,
                    'feature3' => $feature3,
                    'feature4' => $feature4,
                    'color' => $color,
                    'image_path' => $final_image_source,
                    'is_active' => $is_active
                ]);
                $alertSuccess = 'Test preparation exam added successfully!';
            } catch (PDOException $e) {
                $alertError = 'Failed to add exam: ' . $e->getMessage();
            }
        }
    }
    
    // 2. UPDATE TEST
    elseif ($action === 'update') {
        $id = isset($_POST['test_id']) ? intval($_POST['test_id']) : 0;
        $slug = isset($_POST['slug']) ? trim($_POST['slug']) : '';
        $name = isset($_POST['name']) ? trim($_POST['name']) : '';
        $icon = isset($_POST['icon']) ? trim($_POST['icon']) : 'fa-pen-to-square';
        $description = isset($_POST['description']) ? trim($_POST['description']) : '';
        $feature1 = isset($_POST['feature1']) ? trim($_POST['feature1']) : '';
        $feature2 = isset($_POST['feature2']) ? trim($_POST['feature2']) : '';
        $feature3 = isset($_POST['feature3']) ? trim($_POST['feature3']) : '';
        $feature4 = isset($_POST['feature4']) ? trim($_POST['feature4']) : '';
        $color = isset($_POST['color']) ? trim($_POST['color']) : 'blue';
        $image_path = isset($_POST['image_path']) ? trim($_POST['image_path']) : '';
        $is_active = isset($_POST['is_active']) ? 1 : 0;
        
        // Handle local image upload
        $uploaded_path = '';
        if (isset($_FILES['prep_file']) && $_FILES['prep_file']['error'] === UPLOAD_ERR_OK) {
            $fileTmpPath = $_FILES['prep_file']['tmp_name'];
            $fileName = $_FILES['prep_file']['name'];
            $fileNameCmps = explode(".", $fileName);
            $fileExtension = strtolower(end($fileNameCmps));
            
            $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg'];
            if (in_array($fileExtension, $allowedExtensions)) {
                $newFileName = md5(time() . $fileName) . '.' . $fileExtension;
                $uploadFileDir = '../assets/images/uploads/';
                if (!is_dir($uploadFileDir)) {
                    mkdir($uploadFileDir, 0755, true);
                }
                
                $dest_path = $uploadFileDir . $newFileName;
                if (move_uploaded_file($fileTmpPath, $dest_path)) {
                    $uploaded_path = 'assets/images/uploads/' . $newFileName;
                }
            }
        }
        
        $final_image_source = !empty($uploaded_path) ? $uploaded_path : $image_path;
        
        if ($id <= 0 || empty($slug) || empty($name) || empty($description)) {
            $alertError = 'Invalid parameters. Slug, Exam Name, and Description are required.';
        } else {
            try {
                $stmt = $pdo->prepare("
                    UPDATE test_preps 
                    SET slug = :slug, 
                        name = :name, 
                        icon = :icon, 
                        description = :description, 
                        feature1 = :feature1, 
                        feature2 = :feature2, 
                        feature3 = :feature3, 
                        feature4 = :feature4, 
                        color = :color, 
                        image_path = :image_path,
                        is_active = :is_active 
                    WHERE id = :id
                ");
                $stmt->execute([
                    'slug' => $slug,
                    'name' => $name,
                    'icon' => $icon,
                    'description' => $description,
                    'feature1' => $feature1,
                    'feature2' => $feature2,
                    'feature3' => $feature3,
                    'feature4' => $feature4,
                    'color' => $color,
                    'image_path' => $final_image_source,
                    'is_active' => $is_active,
                    'id' => $id
                ]);
                $alertSuccess = 'Test preparation exam updated successfully!';
            } catch (PDOException $e) {
                $alertError = 'Failed to update exam: ' . $e->getMessage();
            }
        }
    }
    
    // 3. DELETE TEST
    elseif ($action === 'delete') {
        $id = isset($_POST['test_id']) ? intval($_POST['test_id']) : 0;
        if ($id <= 0) {
            $alertError = 'Invalid test ID specified for deletion.';
        } else {
            try {
                // Delete actual local file if it exists inside uploads
                $stmtFile = $pdo->prepare("SELECT image_path FROM test_preps WHERE id = :id LIMIT 1");
                $stmtFile->execute(['id' => $id]);
                $prepFile = $stmtFile->fetch();
                if ($prepFile && strpos($prepFile['image_path'], 'assets/images/uploads/') === 0) {
                    $local_file = '../' . $prepFile['image_path'];
                    if (file_exists($local_file)) {
                        unlink($local_file);
                    }
                }

                $stmt = $pdo->prepare("DELETE FROM test_preps WHERE id = :id");
                $stmt->execute(['id' => $id]);
                $alertSuccess = 'Test preparation exam deleted permanently!';
            } catch (PDOException $e) {
                $alertError = 'Failed to delete exam: ' . $e->getMessage();
            }
        }
    }
}

// Fetch all test preps
$test_preps = [];
try {
    $stmt = $pdo->query("SELECT * FROM test_preps ORDER BY id ASC");
    $test_preps = $stmt->fetchAll();
} catch (PDOException $e) {
    $alertError = 'Could not fetch exams: ' . $e->getMessage();
}
?>

<div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 2.5rem; flex-wrap: wrap; gap: 1rem;">
    <h1 class="page-title">
        Test Prep Cards Manager
        <span>Manage exams (IELTS, TOEFL, PTE) showing key scores, target highlights and student training benefits</span>
    </h1>
    <button class="btn-pill" onclick="openAddModal()">
        <i class="fa-solid fa-plus"></i>
        <span>Add New Exam Card</span>
    </button>
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

<?php if (empty($test_preps)): ?>
    <div class="panel-card" style="text-align: center; padding: 5rem 2rem; color: var(--text-secondary);">
        <i class="fa-solid fa-pen-ruler" style="font-size: 3rem; margin-bottom: 1.5rem; color: var(--text-muted);"></i>
        <h3>No exam systems exist in the database.</h3>
        <p style="margin-top: 0.5rem; font-size: 0.9rem;">Click "Add New Exam Card" to create your first coaching course card!</p>
    </div>
<?php else: ?>
    <div class="crud-grid">
        <?php foreach ($test_preps as $prep): 
            $isActive = intval($prep['is_active']) === 1;
            $color = clean_output($prep['color']);
            $icon = clean_output($prep['icon']);
            $image_path = clean_output($prep['image_path'] ?? '');
        ?>
            <div class="crud-card <?php echo $isActive ? 'crud-card-active' : 'crud-card-inactive'; ?>">
                <div class="crud-card-badge-icon">
                    <i class="fa-solid <?php echo $isActive ? 'fa-check' : 'fa-eye-slash'; ?>"></i>
                </div>
                
                <div style="height: 140px; background: #e2e8f0; border-radius: 12px; margin-bottom: 1rem; overflow: hidden; display: flex; align-items: center; justify-content: center; border: 1px solid var(--border);">
                    <?php if (!empty($image_path)): ?>
                        <img src="../<?php echo $image_path; ?>" alt="Exam Banner" style="width: 100%; height: 100%; object-fit: cover; background: #f8fafc;">
                    <?php else: ?>
                        <div style="text-align: center; color: var(--text-muted);">
                            <i class="fa-solid <?php echo $icon; ?>" style="font-size: 2.5rem; color: var(--primary); margin-bottom: 0.5rem; display: block;"></i>
                            <span style="font-size: 0.75rem;">No banner image</span>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="crud-card-header">
                    <div class="crud-card-icon icon-<?php echo $color; ?>">
                        <i class="fa-solid <?php echo $icon; ?>"></i>
                    </div>
                    <h4 class="crud-card-title"><?php echo clean_output($prep['name']); ?> <span>(/<?php echo clean_output($prep['slug']); ?>)</span></h4>
                </div>
                
                <p class="crud-card-desc"><?php echo clean_output($prep['description']); ?></p>
                
                <div style="background: rgba(15, 23, 42, 0.03); border-radius: 8px; padding: 0.75rem; margin: 0.75rem 0;">
                    <strong style="font-size: 0.75rem; color: var(--text-secondary); text-transform: uppercase; letter-spacing: 0.5px; display: block; margin-bottom: 0.4rem;">Feature Benefits:</strong>
                    <ul style="list-style: none; padding: 0; margin: 0; display: flex; flex-direction: column; gap: 0.25rem;">
                        <?php for($i=1; $i<=4; $i++): $feat = $prep["feature$i"]; if(!empty($feat)): ?>
                            <li style="font-size: 0.8rem; display: flex; align-items: center; gap: 0.4rem; color: var(--text-secondary);"><i class="fa-solid fa-circle-check" style="color: var(--success); font-size: 0.75rem;"></i> <?php echo clean_output($feat); ?></li>
                        <?php endif; endfor; ?>
                    </ul>
                </div>

                <div class="crud-card-footer">
                    <span class="crud-card-info" style="max-width: 150px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">Img: <strong><?php echo $image_path ? $image_path : 'None'; ?></strong></span>
                    <div class="crud-card-actions">
                        <button class="btn-action action-edit" title="Edit Exam" onclick="openEditModal(<?php echo htmlspecialchars(json_encode($prep)); ?>)">
                            <i class="fa-solid fa-pen-to-square"></i>
                        </button>
                        <button class="btn-action action-delete" title="Delete Exam" onclick="triggerDeletePrep(<?php echo $prep['id']; ?>, '<?php echo clean_output($prep['name']); ?>')">
                            <i class="fa-solid fa-trash-can"></i>
                        </button>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<!-- 1. ADD / EDIT DIALOG MODAL -->
<div class="modal-overlay" id="prepModal">
    <div class="modal-container">
        <div class="modal-header">
            <h3 class="modal-title" id="modalTitle">Add Exam Card</h3>
            <span class="modal-close" onclick="closePrepModal()">&times;</span>
        </div>
        <form action="test_prep.php" method="POST" enctype="multipart/form-data">
            <div class="modal-body">
                <input type="hidden" name="action" id="modalAction" value="add">
                <input type="hidden" name="test_id" id="edit_test_id">
                
                <div class="detail-grid">
                    <div class="form-group">
                        <label for="prep_name" class="form-label">Exam Name *</label>
                        <input type="text" name="name" id="prep_name" class="form-control" placeholder="e.g., IELTS" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="prep_slug" class="form-label">URL Slug *</label>
                        <input type="text" name="slug" id="prep_slug" class="form-control" placeholder="e.g., ielts" required>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="prep_desc" class="form-label">Exam Intro Description *</label>
                    <textarea name="description" id="prep_desc" class="form-control" rows="2" placeholder="e.g., International English Language Testing System" required></textarea>
                </div>

                <div class="detail-grid">
                    <div class="form-group">
                        <label for="prep_feat1" class="form-label">Feature / Highlight 1</label>
                        <input type="text" name="feature1" id="prep_feat1" class="form-control" placeholder="e.g., Band 7+ Achievers">
                    </div>
                    
                    <div class="form-group">
                        <label for="prep_feat2" class="form-label">Feature / Highlight 2</label>
                        <input type="text" name="feature2" id="prep_feat2" class="form-control" placeholder="e.g., Full Mock Tests">
                    </div>
                </div>

                <div class="detail-grid">
                    <div class="form-group">
                        <label for="prep_feat3" class="form-label">Feature / Highlight 3</label>
                        <input type="text" name="feature3" id="prep_feat3" class="form-control" placeholder="e.g., Expert Trainers">
                    </div>
                    
                    <div class="form-group">
                        <label for="prep_feat4" class="form-label">Feature / Highlight 4</label>
                        <input type="text" name="feature4" id="prep_feat4" class="form-control" placeholder="e.g., Study Material Included">
                    </div>
                </div>

                <div class="detail-grid">
                    <div class="form-group">
                        <label for="prep_icon" class="form-label">FontAwesome Icon *</label>
                        <input type="text" name="icon" id="prep_icon" class="form-control" placeholder="e.g., fa-pen-to-square" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="prep_color" class="form-label">Highlight Accent Theme *</label>
                        <select name="color" id="prep_color" class="form-select" required>
                            <option value="blue">Blue Accent</option>
                            <option value="purple">Purple Accent</option>
                            <option value="orange">Orange Accent</option>
                            <option value="teal">Teal Accent</option>
                            <option value="pink">Pink Accent</option>
                            <option value="gold">Gold Accent</option>
                        </select>
                    </div>
                </div>

                <div class="detail-grid">
                    <div class="form-group">
                        <label for="prep_img" class="form-label">Exam Banner Image Path</label>
                        <input type="text" name="image_path" id="prep_img" class="form-control" placeholder="e.g., assets/images/ielts-banner.jpg">
                    </div>
                    
                    <div class="form-group">
                        <label for="prep_file" class="form-label">Or Upload Local Image</label>
                        <input type="file" name="prep_file" id="prep_file" class="form-control" accept="image/*">
                    </div>
                </div>

                <div class="form-group" style="margin-bottom: 0; display: flex; align-items: center; gap: 0.5rem;">
                    <input type="checkbox" name="is_active" id="prep_active" checked style="width: 18px; height: 18px; accent-color: var(--primary); cursor: pointer;">
                    <label for="prep_active" class="form-label" style="margin-bottom: 0; cursor: pointer; user-select: none;">Publish immediately (Active & Visible in coaching section)</label>
                </div>
            </div>
            
            <div class="modal-footer">
                <button type="button" class="btn-outline" onclick="closePrepModal()">Cancel</button>
                <button type="submit" class="btn-pill">
                    <i class="fa-solid fa-floppy-disk"></i>
                    <span>Save Exam Card</span>
                </button>
            </div>
        </form>
    </div>
</div>

<!-- 2. DELETE CONFIRMATION MODAL -->
<div class="modal-overlay" id="deleteModal">
    <div class="modal-container" style="max-width: 400px;">
        <div class="modal-header" style="border-bottom: none; padding-bottom: 0;">
            <h3 class="modal-title" style="color: var(--danger);"><i class="fa-solid fa-triangle-exclamation"></i> Delete Exam Card?</h3>
            <span class="modal-close" onclick="closeDeleteModal()">&times;</span>
        </div>
        <form action="test_prep.php" method="POST">
            <div class="modal-body" style="padding-top: 1rem;">
                <input type="hidden" name="action" value="delete">
                <input type="hidden" name="test_id" id="deletePrepId">
                <p style="font-size: 0.95rem; line-height: 1.6; color: var(--text-secondary);">
                    Are you sure you want to permanently delete the exam system <strong id="deletePrepName" style="color: var(--text-primary);">Exam</strong>?
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
function openAddModal() {
    document.getElementById('modalAction').value = 'add';
    document.getElementById('modalTitle').innerText = 'Add Exam Card';
    document.getElementById('edit_test_id').value = '';
    document.getElementById('prep_name').value = '';
    document.getElementById('prep_slug').value = '';
    document.getElementById('prep_desc').value = '';
    document.getElementById('prep_feat1').value = '';
    document.getElementById('prep_feat2').value = '';
    document.getElementById('prep_feat3').value = '';
    document.getElementById('prep_feat4').value = '';
    document.getElementById('prep_icon').value = 'fa-pen-to-square';
    document.getElementById('prep_color').value = 'blue';
    document.getElementById('prep_img').value = '';
    document.getElementById('prep_file').value = '';
    document.getElementById('prep_active').checked = true;
    
    document.getElementById('prepModal').classList.add('active');
}

function openEditModal(prep) {
    document.getElementById('modalAction').value = 'update';
    document.getElementById('modalTitle').innerText = 'Edit Exam: ' + prep.name;
    document.getElementById('edit_test_id').value = prep.id;
    document.getElementById('prep_name').value = prep.name;
    document.getElementById('prep_slug').value = prep.slug;
    document.getElementById('prep_desc').value = prep.description;
    document.getElementById('prep_feat1').value = prep.feature1;
    document.getElementById('prep_feat2').value = prep.feature2;
    document.getElementById('prep_feat3').value = prep.feature3;
    document.getElementById('prep_feat4').value = prep.feature4;
    document.getElementById('prep_icon').value = prep.icon;
    document.getElementById('prep_color').value = prep.color;
    document.getElementById('prep_img').value = prep.image_path || '';
    document.getElementById('prep_file').value = '';
    document.getElementById('prep_active').checked = parseInt(prep.is_active) === 1;
    
    document.getElementById('prepModal').classList.add('active');
}

function closePrepModal() {
    document.getElementById('prepModal').classList.remove('active');
}

function triggerDeletePrep(id, name) {
    document.getElementById('deletePrepId').value = id;
    document.getElementById('deletePrepName').innerText = name;
    document.getElementById('deleteModal').classList.add('active');
}

function closeDeleteModal() {
    document.getElementById('deleteModal').classList.remove('active');
}
</script>

<?php require_once 'includes/footer.php'; ?>
