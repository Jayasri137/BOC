<?php
// admin/site_popup.php - Site Popup CRUD Manager
$pageTitle = 'Site Popup Manager';
require_once 'includes/header.php';

$alertSuccess = '';
$alertError = '';

// Handle Actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = isset($_POST['action']) ? trim($_POST['action']) : '';
    
    if ($action === 'add' || $action === 'update') {
        $id = isset($_POST['popup_id']) ? intval($_POST['popup_id']) : 0;
        $link_url = isset($_POST['link_url']) ? trim($_POST['link_url']) : '';
        $image_path = isset($_POST['image_path']) ? trim($_POST['image_path']) : '';
        $is_active = isset($_POST['is_active']) ? 1 : 0;
        
        $uploaded_path = '';
        if (isset($_FILES['popup_file']) && $_FILES['popup_file']['error'] === UPLOAD_ERR_OK) {
            $fileTmpPath = $_FILES['popup_file']['tmp_name'];
            $fileName = $_FILES['popup_file']['name'];
            $fileNameCmps = explode(".", $fileName);
            $fileExtension = strtolower(end($fileNameCmps));
            
            $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg'];
            if (in_array($fileExtension, $allowedExtensions)) {
                $newFileName = 'popup_' . md5(time() . $fileName) . '.' . $fileExtension;
                $uploadFileDir = '../assets/images/uploads/';
                if (!is_dir($uploadFileDir)) {
                    mkdir($uploadFileDir, 0755, true);
                }
                
                $dest_path = $uploadFileDir . $newFileName;
                if (move_uploaded_file($fileTmpPath, $dest_path)) {
                    $uploaded_path = 'assets/images/uploads/' . $newFileName;
                }
            } else {
                $alertError = "Invalid file extension.";
            }
        }
        
        $final_image_source = !empty($uploaded_path) ? $uploaded_path : $image_path;
        
        if (empty($final_image_source)) {
            $alertError = 'An Image Path or an Uploaded Image is required.';
        } else {
            try {
                if ($action === 'add') {
                    $stmt = $pdo->prepare("INSERT INTO site_popup (link_url, image_path, is_active) VALUES (:link_url, :image_path, :is_active)");
                    $stmt->execute([
                        'link_url' => $link_url,
                        'image_path' => $final_image_source,
                        'is_active' => $is_active
                    ]);
                    $alertSuccess = 'Popup added successfully!';
                } else {
                    $stmt = $pdo->prepare("UPDATE site_popup SET link_url = :link_url, image_path = :image_path, is_active = :is_active WHERE id = :id");
                    $stmt->execute([
                        'link_url' => $link_url,
                        'image_path' => $final_image_source,
                        'is_active' => $is_active,
                        'id' => $id
                    ]);
                    $alertSuccess = 'Popup updated successfully!';
                }
            } catch (PDOException $e) {
                $alertError = 'Database error: ' . $e->getMessage();
            }
        }
    } elseif ($action === 'delete') {
        $id = isset($_POST['popup_id']) ? intval($_POST['popup_id']) : 0;
        if ($id > 0) {
            try {
                $stmtFile = $pdo->prepare("SELECT image_path FROM site_popup WHERE id = :id LIMIT 1");
                $stmtFile->execute(['id' => $id]);
                $popupFile = $stmtFile->fetch();
                if ($popupFile && strpos($popupFile['image_path'], 'assets/images/uploads/') === 0) {
                    $local_file = '../' . $popupFile['image_path'];
                    if (file_exists($local_file)) {
                        unlink($local_file);
                    }
                }
                $stmt = $pdo->prepare("DELETE FROM site_popup WHERE id = :id");
                $stmt->execute(['id' => $id]);
                $alertSuccess = 'Popup deleted permanently!';
            } catch (PDOException $e) {
                $alertError = 'Failed to delete popup: ' . $e->getMessage();
            }
        }
    }
}

// Fetch all popups
$popups = [];
try {
    $stmt = $pdo->query("SELECT * FROM site_popup ORDER BY id DESC");
    $popups = $stmt->fetchAll();
} catch (PDOException $e) {
    $alertError = 'Could not fetch popups: ' . $e->getMessage();
}
?>

<div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 2.5rem; flex-wrap: wrap; gap: 1rem;">
    <h1 class="page-title">
        Site Popup Manager
        <span>Manage the Social Media Business Posts that appear when visitors open the website</span>
    </h1>
    <button class="btn-pill" onclick="openAddModal()">
        <i class="fa-solid fa-plus"></i>
        <span>Add New Popup</span>
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

<?php if (empty($popups)): ?>
    <div class="panel-card" style="text-align: center; padding: 5rem 2rem; color: var(--text-secondary);">
        <i class="fa-regular fa-image" style="font-size: 3rem; margin-bottom: 1.5rem; color: var(--text-muted);"></i>
        <h3>No popups exist in the database.</h3>
        <p style="margin-top: 0.5rem; font-size: 0.9rem;">Click "Add New Popup" to create one.</p>
    </div>
<?php else: ?>
    <div class="crud-grid">
        <?php foreach ($popups as $popup): 
            $isActive = intval($popup['is_active']) === 1;
            $image_path = clean_output($popup['image_path']);
            $link_url = clean_output($popup['link_url']);
        ?>
            <div class="crud-card <?php echo $isActive ? 'crud-card-active' : 'crud-card-inactive'; ?>">
                <div class="crud-card-badge-icon">
                    <i class="fa-solid <?php echo $isActive ? 'fa-check' : 'fa-eye-slash'; ?>"></i>
                </div>
                
                <div style="height: 250px; background: #e0f2fe; border-radius: 12px; margin-bottom: 1rem; overflow: hidden; position: relative; display: flex; align-items: center; justify-content: center;">
                    <?php if (!empty($image_path)): ?>
                        <img src="../<?php echo $image_path; ?>" alt="Popup Image" style="width: 100%; height: 100%; object-fit: contain; background: #f8fafc;">
                    <?php else: ?>
                        <i class="fa-regular fa-image" style="font-size: 3rem; color: var(--text-muted);"></i>
                    <?php endif; ?>
                </div>

                <div class="crud-card-header" style="display: block;">
                    <h4 class="crud-card-title" style="margin-top: 0.2rem; font-size: 1.05rem; line-height: 1.4;">
                        Popup #<?php echo $popup['id']; ?>
                    </h4>
                </div>
                
                <p class="crud-card-desc" style="-webkit-line-clamp: 2; height: 2.8rem; overflow: hidden;">
                    Link: <?php echo !empty($link_url) ? '<a href="'.$link_url.'" target="_blank" style="color:var(--primary);">'.$link_url.'</a>' : 'None'; ?>
                </p>
                
                <div class="crud-card-footer">
                    <span class="crud-card-info" style="max-width: 150px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">Path: <strong><?php echo $image_path; ?></strong></span>
                    <div class="crud-card-actions">
                        <button class="btn-action action-edit" title="Edit Popup" onclick="openEditModal(<?php echo htmlspecialchars(json_encode($popup)); ?>)">
                            <i class="fa-solid fa-pen-to-square"></i>
                        </button>
                        <button class="btn-action action-delete" title="Delete Popup" onclick="triggerDeletePopup(<?php echo $popup['id']; ?>)">
                            <i class="fa-solid fa-trash-can"></i>
                        </button>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<!-- 1. ADD / EDIT DIALOG MODAL -->
<div class="modal-overlay" id="popupModal">
    <div class="modal-container">
        <div class="modal-header">
            <h3 class="modal-title" id="modalTitle">Add Popup</h3>
            <span class="modal-close" onclick="closePopupModal()">&times;</span>
        </div>
        <form action="site_popup.php" method="POST" enctype="multipart/form-data">
            <div class="modal-body">
                <input type="hidden" name="action" id="modalAction" value="add">
                <input type="hidden" name="popup_id" id="edit_popup_id">
                
                <div class="form-group">
                    <label for="link_url" class="form-label">Redirect Link (When clicked)</label>
                    <input type="text" name="link_url" id="link_url" class="form-control" placeholder="e.g. https://instagram.com/bluestoneoverseas">
                </div>

                <div class="detail-grid">
                    <div class="form-group">
                        <label for="image_path" class="form-label">Image URL Path</label>
                        <input type="text" name="image_path" id="image_path" class="form-control" placeholder="e.g. assets/images/popup.jpg">
                    </div>
                    
                    <div class="form-group">
                        <label for="popup_file" class="form-label">Or Upload Local Image *</label>
                        <input type="file" name="popup_file" id="popup_file" class="form-control" accept="image/*">
                    </div>
                </div>

                <div class="form-group" style="margin-bottom: 0; display: flex; align-items: center; gap: 0.5rem;">
                    <input type="checkbox" name="is_active" id="popup_active" checked style="width: 18px; height: 18px; accent-color: var(--primary); cursor: pointer;">
                    <label for="popup_active" class="form-label" style="margin-bottom: 0; cursor: pointer; user-select: none;">Set as Active (Publish to website)</label>
                </div>
            </div>
            
            <div class="modal-footer">
                <button type="button" class="btn-outline" onclick="closePopupModal()">Cancel</button>
                <button type="submit" class="btn-pill">
                    <i class="fa-solid fa-floppy-disk"></i>
                    <span>Save Popup</span>
                </button>
            </div>
        </form>
    </div>
</div>

<!-- 2. DELETE CONFIRMATION MODAL -->
<div class="modal-overlay" id="deleteModal">
    <div class="modal-container" style="max-width: 400px;">
        <div class="modal-header" style="border-bottom: none; padding-bottom: 0;">
            <h3 class="modal-title" style="color: var(--danger);"><i class="fa-solid fa-triangle-exclamation"></i> Delete Popup?</h3>
            <span class="modal-close" onclick="closeDeleteModal()">&times;</span>
        </div>
        <form action="site_popup.php" method="POST">
            <div class="modal-body" style="padding-top: 1rem;">
                <input type="hidden" name="action" value="delete">
                <input type="hidden" name="popup_id" id="deletePopupId">
                <p style="font-size: 0.95rem; line-height: 1.6; color: var(--text-secondary);">
                    Are you sure you want to permanently delete this popup image?
                </p>
            </div>
            <div class="modal-footer" style="border-top: none; padding-top: 0;">
                <button type="button" class="btn-outline" onclick="closeDeleteModal()">Cancel</button>
                <button type="submit" class="btn-pill" style="background: var(--danger); box-shadow: 0 5px 10px var(--danger-glow);">
                    <i class="fa-solid fa-trash-can"></i>
                    <span>Delete</span>
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function openAddModal() {
    document.getElementById('modalAction').value = 'add';
    document.getElementById('modalTitle').innerText = 'Add Popup';
    document.getElementById('edit_popup_id').value = '';
    document.getElementById('link_url').value = '';
    document.getElementById('image_path').value = '';
    document.getElementById('popup_file').value = '';
    document.getElementById('popup_active').checked = true;
    
    document.getElementById('popupModal').classList.add('active');
}

function openEditModal(popup) {
    document.getElementById('modalAction').value = 'update';
    document.getElementById('modalTitle').innerText = 'Edit Popup';
    document.getElementById('edit_popup_id').value = popup.id;
    document.getElementById('link_url').value = popup.link_url;
    document.getElementById('image_path').value = popup.image_path;
    document.getElementById('popup_file').value = '';
    document.getElementById('popup_active').checked = parseInt(popup.is_active) === 1;
    
    document.getElementById('popupModal').classList.add('active');
}

function closePopupModal() {
    document.getElementById('popupModal').classList.remove('active');
}

function triggerDeletePopup(id) {
    document.getElementById('deletePopupId').value = id;
    document.getElementById('deleteModal').classList.add('active');
}

function closeDeleteModal() {
    document.getElementById('deleteModal').classList.remove('active');
}
</script>

<?php require_once 'includes/footer.php'; ?>
