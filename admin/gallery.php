<?php
// admin/gallery.php - Gallery CRUD Editor with Local Image Upload Support
$pageTitle = 'Photo Gallery Manager';
require_once 'includes/header.php';

$alertSuccess = '';
$alertError = '';

// --- HANDLE POST REQUESTS (ADD, UPDATE, DELETE) ---
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = isset($_POST['action']) ? trim($_POST['action']) : '';
    
    // 1. ADD NEW GALLERY ITEM
    if ($action === 'add') {
        $title = isset($_POST['title']) ? trim($_POST['title']) : '';
        $category = isset($_POST['category']) ? trim($_POST['category']) : 'Events';
        $image_path = isset($_POST['image_path']) ? trim($_POST['image_path']) : '';
        $is_active = isset($_POST['is_active']) ? 1 : 0;
        
        // Handle local image upload
        $uploaded_path = '';
        if (isset($_FILES['item_file']) && $_FILES['item_file']['error'] === UPLOAD_ERR_OK) {
            $fileTmpPath = $_FILES['item_file']['tmp_name'];
            $fileName = $_FILES['item_file']['name'];
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
                    INSERT INTO gallery_items (title, category, image_path, is_active) 
                    VALUES (:title, :category, :image_path, :is_active)
                ");
                $stmt->execute([
                    'title' => $title,
                    'category' => $category,
                    'image_path' => $final_image_source,
                    'is_active' => $is_active
                ]);
                $alertSuccess = 'Gallery item added successfully!';
            } catch (PDOException $e) {
                $alertError = 'Failed to add gallery item: ' . $e->getMessage();
            }
        }
    }
    
    // 2. UPDATE GALLERY ITEM
    elseif ($action === 'update') {
        $id = isset($_POST['item_id']) ? intval($_POST['item_id']) : 0;
        $title = isset($_POST['title']) ? trim($_POST['title']) : '';
        $category = isset($_POST['category']) ? trim($_POST['category']) : 'Events';
        $image_path = isset($_POST['image_path']) ? trim($_POST['image_path']) : '';
        $is_active = isset($_POST['is_active']) ? 1 : 0;
        
        // Handle local image upload
        $uploaded_path = '';
        if (isset($_FILES['item_file']) && $_FILES['item_file']['error'] === UPLOAD_ERR_OK) {
            $fileTmpPath = $_FILES['item_file']['tmp_name'];
            $fileName = $_FILES['item_file']['name'];
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
                    UPDATE gallery_items 
                    SET title = :title, 
                        category = :category, 
                        image_path = :image_path, 
                        is_active = :is_active 
                    WHERE id = :id
                ");
                $stmt->execute([
                    'title' => $title,
                    'category' => $category,
                    'image_path' => $final_image_source,
                    'is_active' => $is_active,
                    'id' => $id
                ]);
                $alertSuccess = 'Gallery item updated successfully!';
            } catch (PDOException $e) {
                $alertError = 'Failed to update gallery item: ' . $e->getMessage();
            }
        }
    }
    
    // 3. DELETE GALLERY ITEM
    elseif ($action === 'delete') {
        $id = isset($_POST['item_id']) ? intval($_POST['item_id']) : 0;
        if ($id <= 0) {
            $alertError = 'Invalid gallery item ID specified for deletion.';
        } else {
            try {
                // Delete actual local file if it exists inside uploads
                $stmtFile = $pdo->prepare("SELECT image_path FROM gallery_items WHERE id = :id LIMIT 1");
                $stmtFile->execute(['id' => $id]);
                $galleryFile = $stmtFile->fetch();
                if ($galleryFile && strpos($galleryFile['image_path'], 'assets/images/uploads/') === 0) {
                    $local_file = '../' . $galleryFile['image_path'];
                    if (file_exists($local_file)) {
                        unlink($local_file);
                    }
                }

                $stmt = $pdo->prepare("DELETE FROM gallery_items WHERE id = :id");
                $stmt->execute(['id' => $id]);
                $alertSuccess = 'Gallery item deleted permanently!';
            } catch (PDOException $e) {
                $alertError = 'Failed to delete gallery item: ' . $e->getMessage();
            }
        }
    }
}

// Fetch all gallery items with pagination
$gallery_items = [];
try {
    $countQuery = $pdo->query("SELECT COUNT(*) FROM gallery_items");
    $totalCount = intval($countQuery->fetchColumn());
    
    $pagination = get_pagination_params($totalCount, 10);
    $limit = $pagination['limit'];
    $offset = $pagination['offset'];
    $page = $pagination['page'];
    $totalPages = $pagination['totalPages'];
    
    $stmt = $pdo->prepare("SELECT * FROM gallery_items ORDER BY id DESC LIMIT :limit OFFSET :offset");
    $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();
    $gallery_items = $stmt->fetchAll();
} catch (PDOException $e) {
    $alertError = 'Could not fetch gallery items: ' . $e->getMessage();
}
?>

<div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 2.5rem; flex-wrap: wrap; gap: 1rem;">
    <h1 class="page-title">
        Photo Gallery Manager
        <span>Manage successfully hosted events, language coaching activities, and pre-departure briefs photos appearing in front-end gallery pages</span>
    </h1>
    <button class="btn-pill" onclick="openAddModal()">
        <i class="fa-solid fa-plus"></i>
        <span>Add New Photo</span>
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

<?php echo render_limit_dropdown($limit); ?>

<?php if (empty($gallery_items)): ?>
    <div class="panel-card" style="text-align: center; padding: 5rem 2rem; color: var(--text-secondary);">
        <i class="fa-solid fa-image" style="font-size: 3rem; margin-bottom: 1.5rem; color: var(--text-muted);"></i>
        <h3>No gallery items exist in the database.</h3>
        <p style="margin-top: 0.5rem; font-size: 0.9rem;">Click "Add New Photo" to upload or link your first media card!</p>
    </div>
<?php else: ?>
    <div class="crud-grid">
        <?php foreach ($gallery_items as $item): 
            $isActive = intval($item['is_active']) === 1;
            $title = clean_output($item['title']);
            $cat = clean_output($item['category']);
            $image_path = clean_output($item['image_path']);
        ?>
            <div class="crud-card <?php echo $isActive ? 'crud-card-active' : 'crud-card-inactive'; ?>">
                <div class="crud-card-badge-icon">
                    <i class="fa-solid <?php echo $isActive ? 'fa-check' : 'fa-eye-slash'; ?>"></i>
                </div>
                
                <div style="height: 140px; background: #e0f2fe; border-radius: 12px; margin-bottom: 1rem; overflow: hidden; position: relative; display: flex; align-items: center; justify-content: center;">
                    <?php if (!empty($image_path)): ?>
                        <img src="../<?php echo $image_path; ?>" alt="Gallery Image" style="width: 100%; height: 100%; object-fit: cover; background: #f8fafc;">
                    <?php else: ?>
                        <i class="fa-regular fa-image" style="font-size: 3rem; color: var(--text-muted);"></i>
                    <?php endif; ?>
                </div>

                <div class="crud-card-header">
                    <h4 class="crud-card-title"><?php echo $title; ?></h4>
                </div>
                
                <div class="crud-card-footer">
                    <span class="crud-card-info" style="font-size: 0.8rem; background: var(--bg-hover); padding: 0.2rem 0.5rem; border-radius: 6px; font-weight: 500;"><?php echo $cat; ?></span>
                    <div class="crud-card-actions">
                        <button class="btn-action action-edit" title="Edit Photo" onclick="openEditModal(<?php echo htmlspecialchars(json_encode($item)); ?>)">
                            <i class="fa-solid fa-pen-to-square"></i>
                        </button>
                        <button class="btn-action action-delete" title="Delete Photo" onclick="triggerDeleteItem(<?php echo $item['id']; ?>, '<?php echo htmlspecialchars($title, ENT_QUOTES); ?>')">
                            <i class="fa-solid fa-trash-can"></i>
                        </button>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
    <?php echo render_pagination_buttons($page, $totalPages); ?>
<?php endif; ?>

<!-- 1. ADD / EDIT DIALOG MODAL -->
<div class="modal-overlay" id="galleryModal">
    <div class="modal-container" style="max-width: 500px;">
        <div class="modal-header">
            <h3 class="modal-title" id="modalTitle">Add Gallery Photo</h3>
            <span class="modal-close" onclick="closeGalleryModal()">&times;</span>
        </div>
        <form action="gallery.php" method="POST" enctype="multipart/form-data">
            <div class="modal-body">
                <input type="hidden" name="action" id="modalAction" value="add">
                <input type="hidden" name="item_id" id="edit_item_id">
                
                <div class="form-group">
                    <label for="item_title" class="form-label">Photo Description Title *</label>
                    <input type="text" name="title" id="item_title" class="form-control" placeholder="e.g., Pre-departure Briefing Coimbatore" required>
                </div>
                
                <div class="form-group">
                    <label for="item_cat" class="form-label">Category / Album Tag *</label>
                    <input type="text" name="category" id="item_cat" class="form-control" placeholder="e.g., Workshops" required>
                </div>

                <div class="detail-grid">
                    <div class="form-group">
                        <label for="item_img" class="form-label">Image Source Path</label>
                        <input type="text" name="image_path" id="item_img" class="form-control" placeholder="e.g., assets/images/md gallery5.png">
                    </div>
                    
                    <div class="form-group">
                        <label for="item_file" class="form-label">Or Upload Local Image</label>
                        <input type="file" name="item_file" id="item_file" class="form-control" accept="image/*">
                    </div>
                </div>

                <div class="form-group" style="margin-bottom: 0; display: flex; align-items: center; gap: 0.5rem;">
                    <input type="checkbox" name="is_active" id="item_active" checked style="width: 18px; height: 18px; accent-color: var(--primary); cursor: pointer;">
                    <label for="item_active" class="form-label" style="margin-bottom: 0; cursor: pointer; user-select: none;">Publish immediately (Active & Visible on homepage / gallery page)</label>
                </div>
            </div>
            
            <div class="modal-footer">
                <button type="button" class="btn-outline" onclick="closeGalleryModal()">Cancel</button>
                <button type="submit" class="btn-pill">
                    <i class="fa-solid fa-floppy-disk"></i>
                    <span>Save Photo</span>
                </button>
            </div>
        </form>
    </div>
</div>

<!-- 2. DELETE CONFIRMATION MODAL -->
<div class="modal-overlay" id="deleteModal">
    <div class="modal-container" style="max-width: 400px;">
        <div class="modal-header" style="border-bottom: none; padding-bottom: 0;">
            <h3 class="modal-title" style="color: var(--danger);"><i class="fa-solid fa-triangle-exclamation"></i> Delete Gallery Photo?</h3>
            <span class="modal-close" onclick="closeDeleteModal()">&times;</span>
        </div>
        <form action="gallery.php" method="POST">
            <div class="modal-body" style="padding-top: 1rem;">
                <input type="hidden" name="action" value="delete">
                <input type="hidden" name="item_id" id="deleteItemId">
                <p style="font-size: 0.95rem; line-height: 1.6; color: var(--text-secondary);">
                    Are you sure you want to permanently delete the photo <strong id="deleteItemName" style="color: var(--text-primary);">Photo</strong>?
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
    document.getElementById('modalTitle').innerText = 'Add Gallery Photo';
    document.getElementById('edit_item_id').value = '';
    document.getElementById('item_title').value = '';
    document.getElementById('item_cat').value = 'Events';
    document.getElementById('item_img').value = '';
    document.getElementById('item_file').value = '';
    document.getElementById('item_active').checked = true;
    
    document.getElementById('galleryModal').classList.add('active');
}

function openEditModal(item) {
    document.getElementById('modalAction').value = 'update';
    document.getElementById('modalTitle').innerText = 'Edit Photo Details';
    document.getElementById('edit_item_id').value = item.id;
    document.getElementById('item_title').value = item.title;
    document.getElementById('item_cat').value = item.category;
    document.getElementById('item_img').value = item.image_path;
    document.getElementById('item_file').value = '';
    document.getElementById('item_active').checked = parseInt(item.is_active) === 1;
    
    document.getElementById('galleryModal').classList.add('active');
}

function closeGalleryModal() {
    document.getElementById('galleryModal').classList.remove('active');
}

function triggerDeleteItem(id, name) {
    document.getElementById('deleteItemId').value = id;
    document.getElementById('deleteItemName').innerText = name;
    document.getElementById('deleteModal').classList.add('active');
}

function closeDeleteModal() {
    document.getElementById('deleteModal').classList.remove('active');
}
</script>

<?php require_once 'includes/footer.php'; ?>
