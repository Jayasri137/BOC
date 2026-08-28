<?php
// admin/team_members.php - Team Members CRUD Editor
$pageTitle = 'Team Members Manager';
require_once 'includes/header.php';

$alertSuccess = '';
$alertError = '';

// --- HANDLE POST REQUESTS (ADD, UPDATE, DELETE) ---
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = isset($_POST['action']) ? trim($_POST['action']) : '';
    
    // 1. ADD NEW TEAM MEMBER
    if ($action === 'add') {
        $name = isset($_POST['name']) ? trim($_POST['name']) : '';
        $role = isset($_POST['role']) ? trim($_POST['role']) : '';
        $description = isset($_POST['description']) ? trim($_POST['description']) : '';
        $display_order = isset($_POST['display_order']) ? intval($_POST['display_order']) : 0;
        $is_active = isset($_POST['is_active']) ? 1 : 0;
        
        $image_path_input = isset($_POST['image_path']) ? trim($_POST['image_path']) : '';
        $uploaded_path = '';
        
        if (isset($_FILES['image_file']) && $_FILES['image_file']['error'] === UPLOAD_ERR_OK) {
            $fileTmpPath = $_FILES['image_file']['tmp_name'];
            $mime_type = mime_content_type($fileTmpPath);
            $base64 = base64_encode(file_get_contents($fileTmpPath));
            $uploaded_path = 'data:' . $mime_type . ';base64,' . $base64;
        }
        
        $final_image_source = !empty($uploaded_path) ? $uploaded_path : (!empty($image_path_input) ? $image_path_input : 'assets/images/team_placeholder.jpg');
        
        if (empty($name) || empty($role)) {
            $alertError = 'Name and Role are required.';
        } else {
            try {
                $stmt = $pdo->prepare("
                    INSERT INTO team_members (name, role, description, image_path, display_order, is_active) 
                    VALUES (:name, :role, :description, :image_path, :display_order, :is_active)
                ");
                $stmt->execute([
                    'name' => $name,
                    'role' => $role,
                    'description' => $description,
                    'image_path' => $final_image_source,
                    'display_order' => $display_order,
                    'is_active' => $is_active
                ]);
                $alertSuccess = 'Team member added successfully!';
            } catch (PDOException $e) {
                $alertError = 'Failed to add team member: ' . $e->getMessage();
            }
        }
    }
    
    // 2. UPDATE TEAM MEMBER
    elseif ($action === 'update') {
        $id = isset($_POST['member_id']) ? intval($_POST['member_id']) : 0;
        $name = isset($_POST['name']) ? trim($_POST['name']) : '';
        $role = isset($_POST['role']) ? trim($_POST['role']) : '';
        $description = isset($_POST['description']) ? trim($_POST['description']) : '';
        $display_order = isset($_POST['display_order']) ? intval($_POST['display_order']) : 0;
        $is_active = isset($_POST['is_active']) ? 1 : 0;
        
        $image_path_input = isset($_POST['image_path']) ? trim($_POST['image_path']) : '';
        $uploaded_path = '';
        if (isset($_FILES['image_file']) && $_FILES['image_file']['error'] === UPLOAD_ERR_OK) {
            $fileTmpPath = $_FILES['image_file']['tmp_name'];
            $mime_type = mime_content_type($fileTmpPath);
            $base64 = base64_encode(file_get_contents($fileTmpPath));
            $uploaded_path = 'data:' . $mime_type . ';base64,' . $base64;
        }
        
        $final_image_source = !empty($uploaded_path) ? $uploaded_path : $image_path_input;
        
        if ($id <= 0 || empty($name) || empty($role)) {
            $alertError = 'Name and Role are required.';
        } else {
            try {
                if (!empty($final_image_source)) {
                    $stmt = $pdo->prepare("
                        UPDATE team_members 
                        SET name = :name, role = :role, description = :description, image_path = :image_path, display_order = :display_order, is_active = :is_active 
                        WHERE id = :id
                    ");
                    $stmt->execute([
                        'name' => $name, 'role' => $role, 'description' => $description, 'image_path' => $final_image_source, 'display_order' => $display_order, 'is_active' => $is_active, 'id' => $id
                    ]);
                } else {
                    $stmt = $pdo->prepare("
                        UPDATE team_members 
                        SET name = :name, role = :role, description = :description, display_order = :display_order, is_active = :is_active 
                        WHERE id = :id
                    ");
                    $stmt->execute([
                        'name' => $name, 'role' => $role, 'description' => $description, 'display_order' => $display_order, 'is_active' => $is_active, 'id' => $id
                    ]);
                }
                $alertSuccess = 'Team member updated successfully!';
            } catch (PDOException $e) {
                $alertError = 'Failed to update team member: ' . $e->getMessage();
            }
        }
    }
    
    // 3. DELETE TEAM MEMBER
    elseif ($action === 'delete') {
        $id = isset($_POST['member_id']) ? intval($_POST['member_id']) : 0;
        if ($id <= 0) {
            $alertError = 'Invalid member ID specified for deletion.';
        } else {
            try {
                $stmtFile = $pdo->prepare("SELECT image_path FROM team_members WHERE id = :id LIMIT 1");
                $stmtFile->execute(['id' => $id]);
                $existing = $stmtFile->fetch();
                if ($existing && strpos($existing['image_path'], 'uploads/') !== false && strpos($existing['image_path'], 'data:image') === false) {
                    $local_file = '../' . $existing['image_path'];
                    if (file_exists($local_file)) {
                        unlink($local_file);
                    }
                }

                $stmt = $pdo->prepare("DELETE FROM team_members WHERE id = :id");
                $stmt->execute(['id' => $id]);
                $alertSuccess = 'Team member deleted permanently!';
            } catch (PDOException $e) {
                $alertError = 'Failed to delete team member: ' . $e->getMessage();
            }
        }
    }
}

// Fetch all team members
$members = [];
try {
    $countQuery = $pdo->query("SELECT COUNT(*) FROM team_members");
    $totalCount = intval($countQuery->fetchColumn());
    
    $pagination = get_pagination_params($totalCount, 20);
    $limit = $pagination['limit'];
    $offset = $pagination['offset'];
    $page = $pagination['page'];
    $totalPages = $pagination['totalPages'];
    
    $stmt = $pdo->prepare("SELECT * FROM team_members ORDER BY display_order ASC, id ASC LIMIT :limit OFFSET :offset");
    $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();
    $members = $stmt->fetchAll();
} catch (PDOException $e) {
    $alertError = 'Could not fetch team members: ' . $e->getMessage();
}
?>

<div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 2.5rem; flex-wrap: wrap; gap: 1rem;">
    <h1 class="page-title">
        Team Members Manager
        <span>Manage the leadership and core team members displayed on the website.</span>
    </h1>
    <button class="btn-pill" onclick="openAddModal()">
        <i class="fa-solid fa-plus"></i>
        <span>Add Team Member</span>
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

<?php if (empty($members)): ?>
    <div class="panel-card" style="text-align: center; padding: 5rem 2rem; color: var(--text-secondary);">
        <i class="fa-solid fa-users" style="font-size: 3rem; margin-bottom: 1.5rem; color: var(--text-muted);"></i>
        <h3>No team members exist.</h3>
        <p style="margin-top: 0.5rem; font-size: 0.9rem;">Click "Add Team Member" to add leaders to the board.</p>
    </div>
<?php else: ?>
    <div class="crud-grid">
        <?php foreach ($members as $m): 
            $isActive = intval($m['is_active']) === 1;
        ?>
            <div class="crud-card <?php echo $isActive ? 'crud-card-active' : 'crud-card-inactive'; ?>">
                <div class="crud-card-badge-icon">
                    <i class="fa-solid <?php echo $isActive ? 'fa-check' : 'fa-eye-slash'; ?>"></i>
                </div>
                
                <div style="height: 180px; background: #0f172a; border-radius: 12px; margin-bottom: 1rem; overflow: hidden; position: relative; border: 1px solid var(--border);">
                    <?php 
                    $img_src = clean_output($m['image_path']);
                    if (strpos($img_src, 'data:image') !== 0 && strpos($img_src, 'http') !== 0) {
                        $img_src = '../' . $img_src;
                    }
                    ?>
                    <img src="<?php echo $img_src; ?>" style="width: 100%; height: 100%; object-fit: cover;" alt="<?php echo clean_output($m['name']); ?>">
                </div>

                <div class="crud-card-header" style="display: block;">
                    <h4 class="crud-card-title"><?php echo clean_output($m['name']); ?></h4>
                    <span style="font-size: 0.8rem; color: var(--primary); display: block; margin-top: 0.2rem; font-weight: 600;"><?php echo clean_output($m['role']); ?></span>
                </div>
                
                <p class="crud-card-desc" style="-webkit-line-clamp: 3; font-size: 0.85rem; color: var(--text-secondary); margin-top: 0.5rem;">
                    <?php echo nl2br(clean_output($m['description'])); ?>
                </p>

                <div class="crud-card-footer" style="margin-top: 1rem;">
                    <span class="crud-card-info" style="font-size: 0.75rem;">Order: <strong><?php echo intval($m['display_order']); ?></strong></span>
                    <div class="crud-card-actions">
                        <button class="btn-action action-edit" title="Edit Member" onclick="openEditModal(<?php echo htmlspecialchars(json_encode($m)); ?>)">
                            <i class="fa-solid fa-pen-to-square"></i>
                        </button>
                        <button class="btn-action action-delete" title="Delete Member" onclick="triggerDeleteMember(<?php echo $m['id']; ?>, '<?php echo addslashes(clean_output($m['name'])); ?>')">
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
<div class="modal-overlay" id="memberModal">
    <div class="modal-container" style="max-width: 500px;">
        <div class="modal-header">
            <h3 class="modal-title" id="modalTitle">Add Team Member</h3>
            <span class="modal-close" onclick="closeMemberModal()">&times;</span>
        </div>
        <form action="team_members.php" method="POST" enctype="multipart/form-data">
            <div class="modal-body">
                <input type="hidden" name="action" id="modalAction" value="add">
                <input type="hidden" name="member_id" id="edit_member_id">
                
                <div class="form-group">
                    <label for="m_name" class="form-label">Name *</label>
                    <input type="text" name="name" id="m_name" class="form-control" required>
                </div>
                
                <div class="form-group">
                    <label for="m_role" class="form-label">Role / Designation *</label>
                    <input type="text" name="role" id="m_role" class="form-control" required>
                </div>

                <div class="detail-grid" style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                    <div class="form-group">
                        <label for="m_img_path" class="form-label">Image Source Path (URL or Local Path)</label>
                        <input type="text" name="image_path" id="m_img_path" class="form-control" placeholder="e.g., https://site.com/img.jpg">
                    </div>
                    
                    <div class="form-group">
                        <label for="m_file" class="form-label">Or Upload Local Photo</label>
                        <input type="file" name="image_file" id="m_file" class="form-control" accept="image/jpeg,image/png,image/webp">
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="m_order" class="form-label">Display Order (Lower numbers show first)</label>
                    <input type="number" name="display_order" id="m_order" class="form-control" value="0">
                </div>

                <div class="form-group">
                    <label for="m_desc" class="form-label">Description / Bio (Optional)</label>
                    <textarea name="description" id="m_desc" class="form-control" rows="5"></textarea>
                </div>

                <div class="form-group" style="margin-bottom: 0; display: flex; align-items: center; gap: 0.5rem;">
                    <input type="checkbox" name="is_active" id="m_active" checked style="width: 18px; height: 18px; accent-color: var(--primary); cursor: pointer;">
                    <label for="m_active" class="form-label" style="margin-bottom: 0; cursor: pointer; user-select: none;">Publish immediately</label>
                </div>
            </div>
            
            <div class="modal-footer">
                <button type="button" class="btn-outline" onclick="closeMemberModal()">Cancel</button>
                <button type="submit" class="btn-pill">
                    <i class="fa-solid fa-floppy-disk"></i>
                    <span>Save Member</span>
                </button>
            </div>
        </form>
    </div>
</div>

<!-- 2. DELETE CONFIRMATION MODAL -->
<div class="modal-overlay" id="deleteModal">
    <div class="modal-container" style="max-width: 400px;">
        <div class="modal-header" style="border-bottom: none; padding-bottom: 0;">
            <h3 class="modal-title" style="color: var(--danger);"><i class="fa-solid fa-triangle-exclamation"></i> Delete Team Member?</h3>
            <span class="modal-close" onclick="closeDeleteModal()">&times;</span>
        </div>
        <form action="team_members.php" method="POST">
            <div class="modal-body" style="padding-top: 1rem;">
                <input type="hidden" name="action" value="delete">
                <input type="hidden" name="member_id" id="deleteMemberId">
                <p style="font-size: 0.95rem; line-height: 1.6; color: var(--text-secondary);">
                    Are you sure you want to permanently delete <strong id="deleteMemberName" style="color: var(--text-primary);">Name</strong>?
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
    document.getElementById('modalTitle').innerText = 'Add Team Member';
    document.getElementById('edit_member_id').value = '';
    document.getElementById('m_name').value = '';
    document.getElementById('m_role').value = '';
    document.getElementById('m_desc').value = '';
    document.getElementById('m_order').value = '0';
    document.getElementById('m_img_path').value = '';
    document.getElementById('m_file').value = '';
    document.getElementById('m_active').checked = true;
    
    document.getElementById('memberModal').classList.add('active');
}

function openEditModal(m) {
    document.getElementById('modalAction').value = 'update';
    document.getElementById('modalTitle').innerText = 'Edit Team Member';
    document.getElementById('edit_member_id').value = m.id;
    document.getElementById('m_name').value = m.name;
    document.getElementById('m_role').value = m.role;
    document.getElementById('m_desc').value = m.description;
    document.getElementById('m_order').value = m.display_order;
    document.getElementById('m_img_path').value = m.image_path;
    document.getElementById('m_file').value = '';
    document.getElementById('m_active').checked = parseInt(m.is_active) === 1;
    
    document.getElementById('memberModal').classList.add('active');
}

function closeMemberModal() {
    document.getElementById('memberModal').classList.remove('active');
}

function triggerDeleteMember(id, name) {
    document.getElementById('deleteMemberId').value = id;
    document.getElementById('deleteMemberName').innerText = name;
    document.getElementById('deleteModal').classList.add('active');
}

function closeDeleteModal() {
    document.getElementById('deleteModal').classList.remove('active');
}
</script>

<?php require_once 'includes/footer.php'; ?>
