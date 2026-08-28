<?php
// admin/announcements.php - Announcements CRUD Editor
$pageTitle = 'Announcement Banners Manager';
require_once 'includes/header.php';

$alertSuccess = '';
$alertError = '';

// --- HANDLE POST REQUESTS (ADD, UPDATE, DELETE) ---
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = isset($_POST['action']) ? trim($_POST['action']) : '';
    
    // 1. ADD NEW ANNOUNCEMENT
    if ($action === 'add') {
        $text = isset($_POST['text']) ? trim($_POST['text']) : '';
        $link = isset($_POST['link']) ? trim($_POST['link']) : '';
        $is_active = isset($_POST['is_active']) ? 1 : 0;
        
        if (empty($text)) {
            $alertError = 'Announcement Text is required.';
        } else {
            try {
                $stmt = $pdo->prepare("
                    INSERT INTO announcements (text, link, is_active) 
                    VALUES (:text, :link, :is_active)
                ");
                $stmt->execute([
                    'text' => $text,
                    'link' => $link,
                    'is_active' => $is_active
                ]);
                $alertSuccess = 'Announcement added successfully!';
            } catch (PDOException $e) {
                $alertError = 'Failed to add announcement: ' . $e->getMessage();
            }
        }
    }
    
    // 2. UPDATE ANNOUNCEMENT
    elseif ($action === 'update') {
        $id = isset($_POST['announcement_id']) ? intval($_POST['announcement_id']) : 0;
        $text = isset($_POST['text']) ? trim($_POST['text']) : '';
        $link = isset($_POST['link']) ? trim($_POST['link']) : '';
        $is_active = isset($_POST['is_active']) ? 1 : 0;
        
        if ($id <= 0 || empty($text)) {
            $alertError = 'Invalid parameters. Announcement text is required.';
        } else {
            try {
                $stmt = $pdo->prepare("
                    UPDATE announcements 
                    SET text = :text, 
                        link = :link, 
                        is_active = :is_active 
                    WHERE id = :id
                ");
                $stmt->execute([
                    'text' => $text,
                    'link' => $link,
                    'is_active' => $is_active,
                    'id' => $id
                ]);
                $alertSuccess = 'Announcement updated successfully!';
            } catch (PDOException $e) {
                $alertError = 'Failed to update announcement: ' . $e->getMessage();
            }
        }
    }
    
    // 3. DELETE ANNOUNCEMENT
    elseif ($action === 'delete') {
        $id = isset($_POST['announcement_id']) ? intval($_POST['announcement_id']) : 0;
        if ($id <= 0) {
            $alertError = 'Invalid announcement ID specified for deletion.';
        } else {
            try {
                $stmt = $pdo->prepare("DELETE FROM announcements WHERE id = :id");
                $stmt->execute(['id' => $id]);
                $alertSuccess = 'Announcement deleted permanently!';
            } catch (PDOException $e) {
                $alertError = 'Failed to delete announcement: ' . $e->getMessage();
            }
        }
    }
}

// Fetch all announcements
$announcements = [];
try {
    $stmt = $pdo->prepare("SELECT * FROM announcements ORDER BY id DESC");
    $stmt->execute();
    $announcements = $stmt->fetchAll();
} catch (PDOException $e) {
    $alertError = 'Could not fetch announcements: ' . $e->getMessage();
}
?>

<div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 2.5rem; flex-wrap: wrap; gap: 1rem;">
    <h1 class="page-title">
        Announcement Banners Manager
        <span>Manage the dynamic scrolling alerts displayed at the top of the website</span>
    </h1>
    <button class="btn-pill" onclick="openAddModal()">
        <i class="fa-solid fa-plus"></i>
        <span>Add New Announcement</span>
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

<?php if (empty($announcements)): ?>
    <div class="panel-card" style="text-align: center; padding: 5rem 2rem; color: var(--text-secondary);">
        <i class="fa-solid fa-bullhorn" style="font-size: 3rem; margin-bottom: 1.5rem; color: var(--text-muted);"></i>
        <h3>No announcements exist in the database.</h3>
        <p style="margin-top: 0.5rem; font-size: 0.9rem;">Click "Add New Announcement" to publish your first banner!</p>
    </div>
<?php else: ?>
    <div class="crud-grid">
        <?php foreach ($announcements as $ann): 
            $isActive = intval($ann['is_active']) === 1;
            $text = clean_output($ann['text']);
            $link = clean_output($ann['link'] ?? '');
        ?>
            <div class="crud-card <?php echo $isActive ? 'crud-card-active' : 'crud-card-inactive'; ?>">
                <div class="crud-card-badge-icon">
                    <i class="fa-solid <?php echo $isActive ? 'fa-check' : 'fa-eye-slash'; ?>"></i>
                </div>
                
                <div class="crud-card-header" style="display: block; margin-top: 2rem;">
                    <h4 class="crud-card-title">Announcement</h4>
                </div>
                
                <p class="crud-card-desc"><?php echo $text; ?></p>
                
                <div style="font-size: 0.8rem; color: var(--text-secondary); margin-bottom: 1rem;">
                    <i class="fa-solid fa-link" style="color: var(--primary); margin-right: 0.4rem;"></i> 
                    <?php echo !empty($link) ? $link : 'No link provided'; ?>
                </div>

                <div class="crud-card-footer">
                    <span class="crud-card-info">Created: <?php echo date('M d, Y', strtotime($ann['created_at'])); ?></span>
                    <div class="crud-card-actions">
                        <button class="btn-action action-edit" title="Edit Announcement" onclick="openEditModal(<?php echo htmlspecialchars(json_encode($ann)); ?>)">
                            <i class="fa-solid fa-pen-to-square"></i>
                        </button>
                        <button class="btn-action action-delete" title="Delete Announcement" onclick="triggerDeleteAnnouncement(<?php echo $ann['id']; ?>)">
                            <i class="fa-solid fa-trash-can"></i>
                        </button>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<!-- 1. ADD / EDIT DIALOG MODAL -->
<div class="modal-overlay" id="announcementModal">
    <div class="modal-container" style="max-width: 500px;">
        <div class="modal-header">
            <h3 class="modal-title" id="modalTitle">Add Announcement</h3>
            <span class="modal-close" onclick="closeAnnouncementModal()">&times;</span>
        </div>
        <form action="announcements.php" method="POST">
            <div class="modal-body">
                <input type="hidden" name="action" id="modalAction" value="add">
                <input type="hidden" name="announcement_id" id="edit_announcement_id">
                
                <div class="form-group">
                    <label for="ann_text" class="form-label">Announcement Text *</label>
                    <textarea name="text" id="ann_text" class="form-control" rows="3" placeholder="e.g., ⚡ EXAM ALERT: Registrations now open for JLPT!" required></textarea>
                </div>
                
                <div class="form-group">
                    <label for="ann_link" class="form-label">Optional Link URL</label>
                    <input type="text" name="link" id="ann_link" class="form-control" placeholder="e.g., japanese.php#curriculum">
                </div>

                <div class="form-group" style="margin-bottom: 0; display: flex; align-items: center; gap: 0.5rem;">
                    <input type="checkbox" name="is_active" id="ann_active" checked style="width: 18px; height: 18px; accent-color: var(--primary); cursor: pointer;">
                    <label for="ann_active" class="form-label" style="margin-bottom: 0; cursor: pointer; user-select: none;">Publish immediately (Active & Visible on homepage)</label>
                </div>
            </div>
            
            <div class="modal-footer">
                <button type="button" class="btn-outline" onclick="closeAnnouncementModal()">Cancel</button>
                <button type="submit" class="btn-pill">
                    <i class="fa-solid fa-floppy-disk"></i>
                    <span>Save Announcement</span>
                </button>
            </div>
        </form>
    </div>
</div>

<!-- 2. DELETE CONFIRMATION MODAL -->
<div class="modal-overlay" id="deleteModal">
    <div class="modal-container" style="max-width: 400px;">
        <div class="modal-header" style="border-bottom: none; padding-bottom: 0;">
            <h3 class="modal-title" style="color: var(--danger);"><i class="fa-solid fa-triangle-exclamation"></i> Delete Announcement?</h3>
            <span class="modal-close" onclick="closeDeleteModal()">&times;</span>
        </div>
        <form action="announcements.php" method="POST">
            <div class="modal-body" style="padding-top: 1rem;">
                <input type="hidden" name="action" value="delete">
                <input type="hidden" name="announcement_id" id="deleteAnnouncementId">
                <p style="font-size: 0.95rem; line-height: 1.6; color: var(--text-secondary);">
                    Are you sure you want to permanently delete this announcement?
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
    document.getElementById('modalTitle').innerText = 'Add Announcement';
    document.getElementById('edit_announcement_id').value = '';
    document.getElementById('ann_text').value = '';
    document.getElementById('ann_link').value = '';
    document.getElementById('ann_active').checked = true;
    
    document.getElementById('announcementModal').classList.add('active');
}

function openEditModal(ann) {
    document.getElementById('modalAction').value = 'update';
    document.getElementById('modalTitle').innerText = 'Edit Announcement';
    document.getElementById('edit_announcement_id').value = ann.id;
    document.getElementById('ann_text').value = ann.text;
    document.getElementById('ann_link').value = ann.link || '';
    document.getElementById('ann_active').checked = parseInt(ann.is_active) === 1;
    
    document.getElementById('announcementModal').classList.add('active');
}

function closeAnnouncementModal() {
    document.getElementById('announcementModal').classList.remove('active');
}

function triggerDeleteAnnouncement(id) {
    document.getElementById('deleteAnnouncementId').value = id;
    document.getElementById('deleteModal').classList.add('active');
}

function closeDeleteModal() {
    document.getElementById('deleteModal').classList.remove('active');
}
</script>

<?php require_once 'includes/footer.php'; ?>
