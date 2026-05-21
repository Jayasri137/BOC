<?php
// admin/hero_slides.php - Hero Sliders CRUD Editor with Local Image Upload Support
$pageTitle = 'Hero Banner Manager';
require_once 'includes/header.php';

$alertSuccess = '';
$alertError = '';

// --- HANDLE POST REQUESTS (ADD, UPDATE, DELETE) ---
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = isset($_POST['action']) ? trim($_POST['action']) : '';
    
    // 1. ADD NEW SLIDE
    if ($action === 'add') {
        $badge = isset($_POST['badge']) ? trim($_POST['badge']) : '';
        $title = isset($_POST['title']) ? trim($_POST['title']) : '';
        $description = isset($_POST['description']) ? trim($_POST['description']) : '';
        $button_text = isset($_POST['button_text']) ? trim($_POST['button_text']) : 'Get Started';
        $image_path = isset($_POST['image_path']) ? trim($_POST['image_path']) : '';
        $is_active = isset($_POST['is_active']) ? 1 : 0;
        
        // Handle local image upload
        $uploaded_path = '';
        if (isset($_FILES['slide_file']) && $_FILES['slide_file']['error'] === UPLOAD_ERR_OK) {
            $fileTmpPath = $_FILES['slide_file']['tmp_name'];
            $fileName = $_FILES['slide_file']['name'];
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
        
        if (empty($title) || empty($final_image_source)) {
            $alertError = 'Title and either an Image Path or an Uploaded Image are required fields.';
        } else {
            try {
                $stmt = $pdo->prepare("
                    INSERT INTO hero_slides (badge, title, description, button_text, image_path, is_active) 
                    VALUES (:badge, :title, :description, :button_text, :image_path, :is_active)
                ");
                $stmt->execute([
                    'badge' => $badge,
                    'title' => $title,
                    'description' => $description,
                    'button_text' => $button_text,
                    'image_path' => $final_image_source,
                    'is_active' => $is_active
                ]);
                $alertSuccess = 'Hero slide banner added successfully!';
            } catch (PDOException $e) {
                $alertError = 'Failed to add hero slide: ' . $e->getMessage();
            }
        }
    }
    
    // 2. UPDATE SLIDE
    elseif ($action === 'update') {
        $id = isset($_POST['slide_id']) ? intval($_POST['slide_id']) : 0;
        $badge = isset($_POST['badge']) ? trim($_POST['badge']) : '';
        $title = isset($_POST['title']) ? trim($_POST['title']) : '';
        $description = isset($_POST['description']) ? trim($_POST['description']) : '';
        $button_text = isset($_POST['button_text']) ? trim($_POST['button_text']) : 'Get Started';
        $image_path = isset($_POST['image_path']) ? trim($_POST['image_path']) : '';
        $is_active = isset($_POST['is_active']) ? 1 : 0;
        
        // Handle local image upload
        $uploaded_path = '';
        if (isset($_FILES['slide_file']) && $_FILES['slide_file']['error'] === UPLOAD_ERR_OK) {
            $fileTmpPath = $_FILES['slide_file']['tmp_name'];
            $fileName = $_FILES['slide_file']['name'];
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
        
        if ($id <= 0 || empty($title) || empty($final_image_source)) {
            $alertError = 'Invalid parameters. Title and valid Image are required.';
        } else {
            try {
                $stmt = $pdo->prepare("
                    UPDATE hero_slides 
                    SET badge = :badge, 
                        title = :title, 
                        description = :description, 
                        button_text = :button_text, 
                        image_path = :image_path, 
                        is_active = :is_active 
                    WHERE id = :id
                ");
                $stmt->execute([
                    'badge' => $badge,
                    'title' => $title,
                    'description' => $description,
                    'button_text' => $button_text,
                    'image_path' => $final_image_source,
                    'is_active' => $is_active,
                    'id' => $id
                ]);
                $alertSuccess = 'Hero slide updated successfully!';
            } catch (PDOException $e) {
                $alertError = 'Failed to update hero slide: ' . $e->getMessage();
            }
        }
    }
    
    // 3. DELETE SLIDE
    elseif ($action === 'delete') {
        $id = isset($_POST['slide_id']) ? intval($_POST['slide_id']) : 0;
        if ($id <= 0) {
            $alertError = 'Invalid slide ID specified for deletion.';
        } else {
            try {
                // Delete actual local file if it exists inside uploads
                $stmtFile = $pdo->prepare("SELECT image_path FROM hero_slides WHERE id = :id LIMIT 1");
                $stmtFile->execute(['id' => $id]);
                $slideFile = $stmtFile->fetch();
                if ($slideFile && strpos($slideFile['image_path'], 'assets/images/uploads/') === 0) {
                    $local_file = '../' . $slideFile['image_path'];
                    if (file_exists($local_file)) {
                        unlink($local_file);
                    }
                }

                $stmt = $pdo->prepare("DELETE FROM hero_slides WHERE id = :id");
                $stmt->execute(['id' => $id]);
                $alertSuccess = 'Hero slide banner deleted permanently!';
            } catch (PDOException $e) {
                $alertError = 'Failed to delete hero slide: ' . $e->getMessage();
            }
        }
    }
}

// Fetch all slides
$slides = [];
try {
    $stmt = $pdo->query("SELECT * FROM hero_slides ORDER BY id ASC");
    $slides = $stmt->fetchAll();
} catch (PDOException $e) {
    $alertError = 'Could not fetch hero slides: ' . $e->getMessage();
}
?>

<div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 2.5rem; flex-wrap: wrap; gap: 1rem;">
    <h1 class="page-title">
        Hero Sliders Manager
        <span>Manage high-engagement promotional banners and slides appearing in the main homepage hero slider</span>
    </h1>
    <button class="btn-pill" onclick="openAddModal()">
        <i class="fa-solid fa-plus"></i>
        <span>Add New Slide Banner</span>
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

<?php if (empty($slides)): ?>
    <div class="panel-card" style="text-align: center; padding: 5rem 2rem; color: var(--text-secondary);">
        <i class="fa-solid fa-images" style="font-size: 3rem; margin-bottom: 1.5rem; color: var(--text-muted);"></i>
        <h3>No slider banners exist in the database.</h3>
        <p style="margin-top: 0.5rem; font-size: 0.9rem;">Click "Add New Slide Banner" to create your first dynamic banner!</p>
    </div>
<?php else: ?>
    <div class="crud-grid">
        <?php foreach ($slides as $slide): 
            $isActive = intval($slide['is_active']) === 1;
            $badge = clean_output($slide['badge']);
            $title = clean_output($slide['title']);
            $image_path = clean_output($slide['image_path']);
        ?>
            <div class="crud-card <?php echo $isActive ? 'crud-card-active' : 'crud-card-inactive'; ?>">
                <div class="crud-card-badge-icon">
                    <i class="fa-solid <?php echo $isActive ? 'fa-check' : 'fa-eye-slash'; ?>"></i>
                </div>
                
                <div style="height: 140px; background: #e0f2fe; border-radius: 12px; margin-bottom: 1rem; overflow: hidden; position: relative; display: flex; align-items: center; justify-content: center;">
                    <?php if (!empty($image_path)): ?>
                        <img src="../<?php echo $image_path; ?>" alt="Slide Image" style="width: 100%; height: 100%; object-fit: contain; background: #f8fafc;">
                    <?php else: ?>
                        <i class="fa-regular fa-image" style="font-size: 3rem; color: var(--text-muted);"></i>
                    <?php endif; ?>
                </div>

                <div class="crud-card-header" style="display: block;">
                    <?php if (!empty($badge)): ?>
                        <span class="badge-accent" style="font-size: 0.75rem; background: var(--primary-glow); color: var(--primary); padding: 0.25rem 0.6rem; border-radius: 20px; font-weight: 600; display: inline-block; margin-bottom: 0.4rem; border: 1px solid rgba(239, 68, 68, 0.15);"><?php echo $badge; ?></span>
                    <?php endif; ?>
                    <h4 class="crud-card-title" style="margin-top: 0.2rem; font-size: 1.05rem; line-height: 1.4;"><?php echo str_replace(['<span>','</span>'], ['<strong>','</strong>'], $title); ?></h4>
                </div>
                
                <p class="crud-card-desc" style="-webkit-line-clamp: 2; height: 2.8rem; overflow: hidden;"><?php echo clean_output($slide['description']); ?></p>
                
                <div class="crud-card-footer">
                    <span class="crud-card-info" style="max-width: 150px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">Path: <strong><?php echo $image_path; ?></strong></span>
                    <div class="crud-card-actions">
                        <button class="btn-action action-edit" title="Edit Slide" onclick="openEditModal(<?php echo htmlspecialchars(json_encode($slide)); ?>)">
                            <i class="fa-solid fa-pen-to-square"></i>
                        </button>
                        <button class="btn-action action-delete" title="Delete Slide" onclick="triggerDeleteSlide(<?php echo $slide['id']; ?>, '<?php echo htmlspecialchars(clean_output($slide['title']), ENT_QUOTES); ?>')">
                            <i class="fa-solid fa-trash-can"></i>
                        </button>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<!-- 1. ADD / EDIT DIALOG MODAL -->
<div class="modal-overlay" id="slideModal">
    <div class="modal-container">
        <div class="modal-header">
            <h3 class="modal-title" id="modalTitle">Add Slide Banner</h3>
            <span class="modal-close" onclick="closeSlideModal()">&times;</span>
        </div>
        <form action="hero_slides.php" method="POST" enctype="multipart/form-data">
            <div class="modal-body">
                <input type="hidden" name="action" id="modalAction" value="add">
                <input type="hidden" name="slide_id" id="edit_slide_id">
                
                <div class="form-group">
                    <label for="slide_badge" class="form-label">Promotional Badge / Tagline</label>
                    <input type="text" name="badge" id="slide_badge" class="form-control" placeholder="e.g., Biggest Education Fair">
                </div>
                
                <div class="form-group">
                    <label for="slide_title" class="form-label">Slide Main Heading *</label>
                    <input type="text" name="title" id="slide_title" class="form-control" placeholder="e.g., Scholarships – Attend <span>Bluestone's</span> Fair" required>
                    <small style="color: var(--text-muted); font-size: 0.75rem; display: block; margin-top: 0.25rem;">Use <code>&lt;span&gt;highlighted text&lt;/span&gt;</code> to highlight parts of heading with standard red gradient accents.</small>
                </div>
                
                <div class="form-group">
                    <label for="slide_desc" class="form-label">Description / Subtitle</label>
                    <textarea name="description" id="slide_desc" class="form-control" rows="3" placeholder="Enter supporting text or list of countries..."></textarea>
                </div>
                
                <div class="form-group">
                    <label for="slide_btn" class="form-label">CTA Button Label *</label>
                    <input type="text" name="button_text" id="slide_btn" class="form-control" placeholder="e.g., Secure your spot" required>
                </div>

                <div class="detail-grid">
                    <div class="form-group">
                        <label for="slide_img" class="form-label">Banner Image Source Path</label>
                        <input type="text" name="image_path" id="slide_img" class="form-control" placeholder="e.g., assets/images/img4.png">
                    </div>
                    
                    <div class="form-group">
                        <label for="slide_file" class="form-label">Or Upload Local Image</label>
                        <input type="file" name="slide_file" id="slide_file" class="form-control" accept="image/*">
                    </div>
                </div>

                <div class="form-group" style="margin-bottom: 0; display: flex; align-items: center; gap: 0.5rem;">
                    <input type="checkbox" name="is_active" id="slide_active" checked style="width: 18px; height: 18px; accent-color: var(--primary); cursor: pointer;">
                    <label for="slide_active" class="form-label" style="margin-bottom: 0; cursor: pointer; user-select: none;">Set banner as Active (Publish in Hero slider)</label>
                </div>
            </div>
            
            <div class="modal-footer">
                <button type="button" class="btn-outline" onclick="closeSlideModal()">Cancel</button>
                <button type="submit" class="btn-pill">
                    <i class="fa-solid fa-floppy-disk"></i>
                    <span>Save Slide Banner</span>
                </button>
            </div>
        </form>
    </div>
</div>

<!-- 2. DELETE CONFIRMATION MODAL -->
<div class="modal-overlay" id="deleteModal">
    <div class="modal-container" style="max-width: 400px;">
        <div class="modal-header" style="border-bottom: none; padding-bottom: 0;">
            <h3 class="modal-title" style="color: var(--danger);"><i class="fa-solid fa-triangle-exclamation"></i> Remove Slide Banner?</h3>
            <span class="modal-close" onclick="closeDeleteModal()">&times;</span>
        </div>
        <form action="hero_slides.php" method="POST">
            <div class="modal-body" style="padding-top: 1rem;">
                <input type="hidden" name="action" value="delete">
                <input type="hidden" name="slide_id" id="deleteSlideId">
                <p style="font-size: 0.95rem; line-height: 1.6; color: var(--text-secondary);">
                    Are you sure you want to permanently delete slide <strong id="deleteSlideName" style="color: var(--text-primary);">Slide</strong>?
                </p>
            </div>
            <div class="modal-footer" style="border-top: none; padding-top: 0;">
                <button type="button" class="btn-outline" onclick="closeDeleteModal()">Cancel</button>
                <button type="submit" class="btn-pill" style="background: var(--danger); box-shadow: 0 5px 10px var(--danger-glow);">
                    <i class="fa-solid fa-trash-can"></i>
                    <span>Delete Slide</span>
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function openAddModal() {
    document.getElementById('modalAction').value = 'add';
    document.getElementById('modalTitle').innerText = 'Add Slide Banner';
    document.getElementById('edit_slide_id').value = '';
    document.getElementById('slide_badge').value = '';
    document.getElementById('slide_title').value = '';
    document.getElementById('slide_desc').value = '';
    document.getElementById('slide_btn').value = 'Get Started';
    document.getElementById('slide_img').value = 'assets/images/img4.png';
    document.getElementById('slide_file').value = '';
    document.getElementById('slide_active').checked = true;
    
    document.getElementById('slideModal').classList.add('active');
}

function openEditModal(slide) {
    document.getElementById('modalAction').value = 'update';
    document.getElementById('modalTitle').innerText = 'Edit Slide Banner';
    document.getElementById('edit_slide_id').value = slide.id;
    document.getElementById('slide_badge').value = slide.badge;
    document.getElementById('slide_title').value = slide.title;
    document.getElementById('slide_desc').value = slide.description;
    document.getElementById('slide_btn').value = slide.button_text;
    document.getElementById('slide_img').value = slide.image_path;
    document.getElementById('slide_file').value = '';
    document.getElementById('slide_active').checked = parseInt(slide.is_active) === 1;
    
    document.getElementById('slideModal').classList.add('active');
}

function closeSlideModal() {
    document.getElementById('slideModal').classList.remove('active');
}

function triggerDeleteSlide(id, name) {
    document.getElementById('deleteSlideId').value = id;
    document.getElementById('deleteSlideName').innerText = name.replace(/<\/?[^>]+(>|$)/g, ""); // strip tag html
    document.getElementById('deleteModal').classList.add('active');
}

function closeDeleteModal() {
    document.getElementById('deleteModal').classList.remove('active');
}
</script>

<?php require_once 'includes/footer.php'; ?>
