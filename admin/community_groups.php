<?php
// admin/community_groups.php - Community Groups Manager
$pageTitle = 'Community Groups Manager';
require_once __DIR__ . '/includes/header.php';

$alertSuccess = '';
$alertError = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = isset($_POST['action']) ? trim($_POST['action']) : '';
    
    if ($action === 'add') {
        $name = isset($_POST['name']) ? trim($_POST['name']) : '';
        $description = isset($_POST['description']) ? trim($_POST['description']) : '';
        $image_url = isset($_POST['image_url']) ? trim($_POST['image_url']) : '';
        $is_active = isset($_POST['is_active']) ? 1 : 0;
        
        if (empty($name)) {
            $alertError = 'Group Name is required.';
        } else {
            try {
                $stmt = $pdo->prepare("INSERT INTO community_groups (name, description, image_url, is_active) VALUES (?, ?, ?, ?)");
                $stmt->execute([$name, $description, $image_url, $is_active]);
                $alertSuccess = 'Group added successfully!';
            } catch (PDOException $e) {
                $alertError = 'Failed to add group: ' . $e->getMessage();
            }
        }
    }
    
    elseif ($action === 'update') {
        $id = isset($_POST['group_id']) ? intval($_POST['group_id']) : 0;
        $name = isset($_POST['name']) ? trim($_POST['name']) : '';
        $description = isset($_POST['description']) ? trim($_POST['description']) : '';
        $image_url = isset($_POST['image_url']) ? trim($_POST['image_url']) : '';
        $is_active = isset($_POST['is_active']) ? 1 : 0;
        
        if ($id <= 0 || empty($name)) {
            $alertError = 'Group Name is required.';
        } else {
            try {
                $stmt = $pdo->prepare("UPDATE community_groups SET name=?, description=?, image_url=?, is_active=? WHERE id=?");
                $stmt->execute([$name, $description, $image_url, $is_active, $id]);
                $alertSuccess = 'Group updated successfully!';
            } catch (PDOException $e) {
                $alertError = 'Failed to update group: ' . $e->getMessage();
            }
        }
    }
    
    elseif ($action === 'delete') {
        $id = isset($_POST['group_id']) ? intval($_POST['group_id']) : 0;
        if ($id > 0) {
            try {
                $pdo->beginTransaction();
                $messageStmt = $pdo->prepare("DELETE FROM community_group_messages WHERE group_id = ?");
                $messageStmt->execute([$id]);
                $memberStmt = $pdo->prepare("DELETE FROM community_group_members WHERE group_id = ?");
                $memberStmt->execute([$id]);
                $stmt = $pdo->prepare("DELETE FROM community_groups WHERE id = ?");
                $stmt->execute([$id]);
                $pdo->commit();
                $alertSuccess = 'Group deleted permanently!';
            } catch (PDOException $e) {
                if ($pdo->inTransaction()) {
                    $pdo->rollBack();
                }
                $alertError = 'Failed to delete group: ' . $e->getMessage();
            }
        }
    }
}

$groups = [];
try {
    $totalCount = intval($pdo->query("SELECT COUNT(*) FROM community_groups")->fetchColumn());
    $pagination = get_pagination_params($totalCount);
    $limit = $pagination['limit'];
    $page = $pagination['page'];
    $totalPages = $pagination['totalPages'];
    $stmt = $pdo->prepare("SELECT * FROM community_groups ORDER BY created_at DESC LIMIT :limit OFFSET :offset");
    $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $pagination['offset'], PDO::PARAM_INT);
    $stmt->execute();
    $groups = $stmt->fetchAll();
} catch (PDOException $e) {
    $alertError = 'Could not fetch groups: ' . $e->getMessage();
    $limit = 10;
    $page = 1;
    $totalPages = 1;
}
?>

<div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 2.5rem;">
    <h1 class="page-title">
        Community Groups Manager
    </h1>
    <button class="btn-pill" onclick="openAddModal()">
        <i class="fa-solid fa-plus"></i>
        <span>Add Group</span>
    </button>
</div>

<?php if (!empty($alertSuccess)): ?>
    <div class="alert alert-success"><i class="fa-solid fa-check"></i> <?php echo htmlspecialchars($alertSuccess); ?></div>
<?php endif; ?>
<?php if (!empty($alertError)): ?>
    <div class="alert alert-danger"><i class="fa-solid fa-triangle-exclamation"></i> <?php echo htmlspecialchars($alertError); ?></div>
<?php endif; ?>

<?php echo render_limit_dropdown($limit); ?>

<div class="crud-grid">
    <?php foreach ($groups as $g): 
        $isActive = intval($g['is_active']) === 1;
    ?>
        <div class="crud-card <?php echo $isActive ? 'crud-card-active' : 'crud-card-inactive'; ?>">
            <?php echo render_admin_active_toggle('community_groups', $g['id'], $isActive, $g['name']); ?>
            <h4 style="margin-bottom: 10px;"><?php echo htmlspecialchars($g['name']); ?></h4>
            <p style="font-size: 0.9rem; margin-bottom: 15px; color: var(--text-muted);"><?php echo htmlspecialchars($g['description']); ?></p>
            <div class="crud-card-footer">
                <span style="font-size:0.75rem;">ID: <?php echo $g['id']; ?></span>
                <div class="crud-card-actions">
                    <button class="btn-action" onclick='openEditModal(<?php echo json_encode($g, JSON_HEX_APOS | JSON_HEX_QUOT); ?>)'><i class="fa-solid fa-pen"></i></button>
                    <button class="btn-action action-delete" onclick="triggerDelete(<?php echo $g['id']; ?>)"><i class="fa-solid fa-trash"></i></button>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>
<?php echo render_pagination_buttons($page, $totalPages, ['limit' => $limit]); ?>

<!-- Modal -->
<div class="modal-overlay" id="groupModal">
    <div class="modal-container" style="max-width: 500px;">
        <div class="modal-header">
            <h3 class="modal-title" id="modalTitle">Add Group</h3>
            <span class="modal-close" onclick="closeModal()">&times;</span>
        </div>
        <form action="community_groups.php" method="POST">
            <div class="modal-body">
                <input type="hidden" name="action" id="modalAction" value="add">
                <input type="hidden" name="group_id" id="edit_group_id">
                
                <div class="form-group">
                    <label class="form-label">Group Name *</label>
                    <input type="text" name="name" id="group_name" class="form-control" required>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Description</label>
                    <textarea name="description" id="group_desc" class="form-control" rows="3"></textarea>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Image URL</label>
                    <input type="text" name="image_url" id="group_img" class="form-control">
                </div>
                
                <div class="form-group" style="display:flex; align-items:center; gap:0.5rem;">
                    <input type="checkbox" name="is_active" id="group_active" checked style="width:18px;height:18px;">
                    <label for="group_active" style="margin:0;">Active</label>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-outline" onclick="closeModal()">Cancel</button>
                <button type="submit" class="btn-pill">Save Group</button>
            </div>
        </form>
    </div>
</div>

<div class="modal-overlay" id="deleteModal">
    <div class="modal-container" style="max-width: 400px;">
        <div class="modal-header">
            <h3 class="modal-title" style="color:red;">Delete Group?</h3>
            <span class="modal-close" onclick="closeDelModal()">&times;</span>
        </div>
        <form action="community_groups.php" method="POST">
            <div class="modal-body">
                <input type="hidden" name="action" value="delete">
                <input type="hidden" name="group_id" id="del_id">
                <p>Are you sure you want to delete this group?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-outline" onclick="closeDelModal()">Cancel</button>
                <button type="submit" class="btn-pill" style="background:var(--danger);">Delete</button>
            </div>
        </form>
    </div>
</div>

<script>
function openAddModal() {
    document.getElementById('modalAction').value = 'add';
    document.getElementById('modalTitle').innerText = 'Add Group';
    document.getElementById('edit_group_id').value = '';
    document.getElementById('group_name').value = '';
    document.getElementById('group_desc').value = '';
    document.getElementById('group_img').value = '';
    document.getElementById('groupModal').classList.add('active');
}
function openEditModal(g) {
    document.getElementById('modalAction').value = 'update';
    document.getElementById('modalTitle').innerText = 'Edit Group';
    document.getElementById('edit_group_id').value = g.id;
    document.getElementById('group_name').value = g.name;
    document.getElementById('group_desc').value = g.description;
    document.getElementById('group_img').value = g.image_url;
    document.getElementById('group_active').checked = parseInt(g.is_active) === 1;
    document.getElementById('groupModal').classList.add('active');
}
function closeModal() { document.getElementById('groupModal').classList.remove('active'); }
function triggerDelete(id) {
    document.getElementById('del_id').value = id;
    document.getElementById('deleteModal').classList.add('active');
}
function closeDelModal() { document.getElementById('deleteModal').classList.remove('active'); }
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
