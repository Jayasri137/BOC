<?php
// admin/branches.php - Branches CRUD Editor
$pageTitle = 'Branch Office Manager';
require_once 'includes/header.php';

$alertSuccess = '';
$alertError = '';

// --- HANDLE POST REQUESTS (ADD, UPDATE, DELETE) ---
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = isset($_POST['action']) ? trim($_POST['action']) : '';
    
    // 1. ADD NEW BRANCH
    if ($action === 'add') {
        $city = isset($_POST['city']) ? trim($_POST['city']) : '';
        $icon = isset($_POST['icon']) ? trim($_POST['icon']) : 'fa-location-dot';
        $badge = isset($_POST['badge']) ? trim($_POST['badge']) : '';
        $address = isset($_POST['address']) ? trim($_POST['address']) : '';
        $is_active = isset($_POST['is_active']) ? 1 : 0;
        
        if (empty($city) || empty($address)) {
            $alertError = 'Branch City and Address are required fields.';
        } else {
            try {
                $stmt = $pdo->prepare("
                    INSERT INTO branches (city, icon, badge, address, is_active) 
                    VALUES (:city, :icon, :badge, :address, :is_active)
                ");
                $stmt->execute([
                    'city' => $city,
                    'icon' => $icon,
                    'badge' => $badge,
                    'address' => $address,
                    'is_active' => $is_active
                ]);
                $alertSuccess = 'Branch office added successfully!';
            } catch (PDOException $e) {
                $alertError = 'Failed to add branch: ' . $e->getMessage();
            }
        }
    }
    
    // 2. UPDATE BRANCH
    elseif ($action === 'update') {
        $id = isset($_POST['branch_id']) ? intval($_POST['branch_id']) : 0;
        $city = isset($_POST['city']) ? trim($_POST['city']) : '';
        $icon = isset($_POST['icon']) ? trim($_POST['icon']) : 'fa-location-dot';
        $badge = isset($_POST['badge']) ? trim($_POST['badge']) : '';
        $address = isset($_POST['address']) ? trim($_POST['address']) : '';
        $is_active = isset($_POST['is_active']) ? 1 : 0;
        
        if ($id <= 0 || empty($city) || empty($address)) {
            $alertError = 'Invalid parameters. City and Address are required.';
        } else {
            try {
                $stmt = $pdo->prepare("
                    UPDATE branches 
                    SET city = :city, 
                        icon = :icon, 
                        badge = :badge, 
                        address = :address, 
                        is_active = :is_active 
                    WHERE id = :id
                ");
                $stmt->execute([
                    'city' => $city,
                    'icon' => $icon,
                    'badge' => $badge,
                    'address' => $address,
                    'is_active' => $is_active,
                    'id' => $id
                ]);
                $alertSuccess = 'Branch office updated successfully!';
            } catch (PDOException $e) {
                $alertError = 'Failed to update branch: ' . $e->getMessage();
            }
        }
    }
    
    // 3. DELETE BRANCH
    elseif ($action === 'delete') {
        $id = isset($_POST['branch_id']) ? intval($_POST['branch_id']) : 0;
        if ($id <= 0) {
            $alertError = 'Invalid branch ID specified for deletion.';
        } else {
            try {
                $stmt = $pdo->prepare("DELETE FROM branches WHERE id = :id");
                $stmt->execute(['id' => $id]);
                $alertSuccess = 'Branch office deleted permanently!';
            } catch (PDOException $e) {
                $alertError = 'Failed to delete branch: ' . $e->getMessage();
            }
        }
    }
}

// Fetch all branches
$branches = [];
try {
    $stmt = $pdo->query("SELECT * FROM branches ORDER BY id ASC");
    $branches = $stmt->fetchAll();
} catch (PDOException $e) {
    $alertError = 'Could not fetch branches: ' . $e->getMessage();
}
?>

<div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 2.5rem; flex-wrap: wrap; gap: 1rem;">
    <h1 class="page-title">
        Branch Office Manager
        <span>Manage regional branches (Coimbatore, Chennai, Salem, Erode, Nepal, Canada) with address and badge notes</span>
    </h1>
    <button class="btn-pill" onclick="openAddModal()">
        <i class="fa-solid fa-plus"></i>
        <span>Add New Branch</span>
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

<?php if (empty($branches)): ?>
    <div class="panel-card" style="text-align: center; padding: 5rem 2rem; color: var(--text-secondary);">
        <i class="fa-solid fa-map-location-dot" style="font-size: 3rem; margin-bottom: 1.5rem; color: var(--text-muted);"></i>
        <h3>No branch offices exist in the database.</h3>
        <p style="margin-top: 0.5rem; font-size: 0.9rem;">Click "Add New Branch" to create your first branch office card!</p>
    </div>
<?php else: ?>
    <div class="crud-grid">
        <?php foreach ($branches as $b): 
            $isActive = intval($b['is_active']) === 1;
            $city = clean_output($b['city']);
            $icon = clean_output($b['icon']);
            $badge = clean_output($b['badge']);
        ?>
            <div class="crud-card <?php echo $isActive ? 'crud-card-active' : 'crud-card-inactive'; ?>">
                <div class="crud-card-badge-icon">
                    <i class="fa-solid <?php echo $isActive ? 'fa-check' : 'fa-eye-slash'; ?>"></i>
                </div>
                
                <div class="crud-card-header">
                    <div class="crud-card-icon icon-blue">
                        <i class="fa-solid <?php echo $icon; ?>"></i>
                    </div>
                    <h4 class="crud-card-title"><?php echo $city; ?> <?php if(!empty($badge)): ?><span style="color: var(--primary); font-size: 0.8rem; font-weight: 600;"><?php echo $badge; ?></span><?php endif; ?></h4>
                </div>
                
                <p class="crud-card-desc" style="font-size: 0.85rem; height: auto; min-height: 3rem; -webkit-line-clamp: unset; margin-bottom: 1rem;"><i class="fa-solid fa-location-dot" style="color: var(--text-muted); margin-right: 0.4rem;"></i> <?php echo clean_output($b['address']); ?></p>
                
                <div class="crud-card-footer">
                    <span class="crud-card-info">ID: <strong>#<?php echo $b['id']; ?></strong></span>
                    <div class="crud-card-actions">
                        <button class="btn-action action-edit" title="Edit Branch" onclick="openEditModal(<?php echo htmlspecialchars(json_encode($b)); ?>)">
                            <i class="fa-solid fa-pen-to-square"></i>
                        </button>
                        <button class="btn-action action-delete" title="Delete Branch" onclick="triggerDeleteBranch(<?php echo $b['id']; ?>, '<?php echo $city; ?>')">
                            <i class="fa-solid fa-trash-can"></i>
                        </button>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<!-- 1. ADD / EDIT DIALOG MODAL -->
<div class="modal-overlay" id="branchModal">
    <div class="modal-container" style="max-width: 500px;">
        <div class="modal-header">
            <h3 class="modal-title" id="modalTitle">Add Branch Office</h3>
            <span class="modal-close" onclick="closeBranchModal()">&times;</span>
        </div>
        <form action="branches.php" method="POST">
            <div class="modal-body">
                <input type="hidden" name="action" id="modalAction" value="add">
                <input type="hidden" name="branch_id" id="edit_branch_id">
                
                <div class="detail-grid">
                    <div class="form-group">
                        <label for="b_city" class="form-label">City / Location Name *</label>
                        <input type="text" name="city" id="b_city" class="form-control" placeholder="e.g., Coimbatore" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="b_badge" class="form-label">Location Badge Tag</label>
                        <input type="text" name="badge" id="b_badge" class="form-control" placeholder="e.g., (HQ) or Leave Empty">
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="b_address" class="form-label">Full Postal Office Address *</label>
                    <textarea name="address" id="b_address" class="form-control" rows="3" placeholder="Enter full branch address here..." required></textarea>
                </div>

                <div class="form-group">
                    <label for="b_icon" class="form-label">FontAwesome Icon Class *</label>
                    <input type="text" name="icon" id="b_icon" class="form-control" placeholder="e.g., fa-location-dot" required>
                </div>

                <div class="form-group" style="margin-bottom: 0; display: flex; align-items: center; gap: 0.5rem;">
                    <input type="checkbox" name="is_active" id="b_active" checked style="width: 18px; height: 18px; accent-color: var(--primary); cursor: pointer;">
                    <label for="b_active" class="form-label" style="margin-bottom: 0; cursor: pointer; user-select: none;">Publish immediately (Active & Visible on homepage / branches list)</label>
                </div>
            </div>
            
            <div class="modal-footer">
                <button type="button" class="btn-outline" onclick="closeBranchModal()">Cancel</button>
                <button type="submit" class="btn-pill">
                    <i class="fa-solid fa-floppy-disk"></i>
                    <span>Save Branch Office</span>
                </button>
            </div>
        </form>
    </div>
</div>

<!-- 2. DELETE CONFIRMATION MODAL -->
<div class="modal-overlay" id="deleteModal">
    <div class="modal-container" style="max-width: 400px;">
        <div class="modal-header" style="border-bottom: none; padding-bottom: 0;">
            <h3 class="modal-title" style="color: var(--danger);"><i class="fa-solid fa-triangle-exclamation"></i> Delete Office Branch?</h3>
            <span class="modal-close" onclick="closeDeleteModal()">&times;</span>
        </div>
        <form action="branches.php" method="POST">
            <div class="modal-body" style="padding-top: 1rem;">
                <input type="hidden" name="action" value="delete">
                <input type="hidden" name="branch_id" id="deleteBranchId">
                <p style="font-size: 0.95rem; line-height: 1.6; color: var(--text-secondary);">
                    Are you sure you want to permanently delete the office branch <strong id="deleteBranchName" style="color: var(--text-primary);">Branch</strong>?
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
    document.getElementById('modalTitle').innerText = 'Add Branch Office';
    document.getElementById('edit_branch_id').value = '';
    document.getElementById('b_city').value = '';
    document.getElementById('b_badge').value = '';
    document.getElementById('b_address').value = '';
    document.getElementById('b_icon').value = 'fa-location-dot';
    document.getElementById('b_active').checked = true;
    
    document.getElementById('branchModal').classList.add('active');
}

function openEditModal(b) {
    document.getElementById('modalAction').value = 'update';
    document.getElementById('modalTitle').innerText = 'Edit Branch: ' + b.city;
    document.getElementById('edit_branch_id').value = b.id;
    document.getElementById('b_city').value = b.city;
    document.getElementById('b_badge').value = b.badge;
    document.getElementById('b_address').value = b.address;
    document.getElementById('b_icon').value = b.icon;
    document.getElementById('b_active').checked = parseInt(b.is_active) === 1;
    
    document.getElementById('branchModal').classList.add('active');
}

function closeBranchModal() {
    document.getElementById('branchModal').classList.remove('active');
}

function triggerDeleteBranch(id, name) {
    document.getElementById('deleteBranchId').value = id;
    document.getElementById('deleteBranchName').innerText = name;
    document.getElementById('deleteModal').classList.add('active');
}

function closeDeleteModal() {
    document.getElementById('deleteModal').classList.remove('active');
}
</script>

<?php require_once 'includes/footer.php'; ?>
