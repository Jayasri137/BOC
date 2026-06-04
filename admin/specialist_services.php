<?php
// admin/specialist_services.php - Specialist Services CRUD Editor
$pageTitle = 'Specialist Services Manager';
require_once 'includes/header.php';

$alertSuccess = '';
$alertError = '';

// --- HANDLE POST REQUESTS (ADD, UPDATE, DELETE) ---
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = isset($_POST['action']) ? trim($_POST['action']) : '';
    
    // 1. ADD NEW SERVICE
    if ($action === 'add') {
        $title = isset($_POST['title']) ? trim($_POST['title']) : '';
        $category_tag = isset($_POST['category_tag']) ? trim($_POST['category_tag']) : '';
        $icon = isset($_POST['icon']) ? trim($_POST['icon']) : 'fa-briefcase';
        $description = isset($_POST['description']) ? trim($_POST['description']) : '';
        $bullet1 = isset($_POST['bullet1']) ? trim($_POST['bullet1']) : '';
        $bullet2 = isset($_POST['bullet2']) ? trim($_POST['bullet2']) : '';
        $bullet3 = isset($_POST['bullet3']) ? trim($_POST['bullet3']) : '';
        $button_text = isset($_POST['button_text']) ? trim($_POST['button_text']) : 'Explore Details';
        $button_link = isset($_POST['button_link']) ? trim($_POST['button_link']) : 'contact.php';
        $color = isset($_POST['color']) ? trim($_POST['color']) : 'blue';
        $is_active = isset($_POST['is_active']) ? 1 : 0;
        
        if (empty($title) || empty($category_tag) || empty($description)) {
            $alertError = 'Title, Category Tag, and Description are required fields.';
        } else {
            try {
                $stmt = $pdo->prepare("
                    INSERT INTO specialist_services (title, category_tag, icon, description, bullet1, bullet2, bullet3, button_text, button_link, color, is_active) 
                    VALUES (:title, :category_tag, :icon, :description, :bullet1, :bullet2, :bullet3, :button_text, :button_link, :color, :is_active)
                ");
                $stmt->execute([
                    'title' => $title,
                    'category_tag' => $category_tag,
                    'icon' => $icon,
                    'description' => $description,
                    'bullet1' => $bullet1,
                    'bullet2' => $bullet2,
                    'bullet3' => $bullet3,
                    'button_text' => $button_text,
                    'button_link' => $button_link,
                    'color' => $color,
                    'is_active' => $is_active
                ]);
                $alertSuccess = 'Specialist service card added successfully!';
            } catch (PDOException $e) {
                $alertError = 'Failed to add specialist service: ' . $e->getMessage();
            }
        }
    }
    
    // 2. UPDATE SERVICE
    elseif ($action === 'update') {
        $id = isset($_POST['spec_id']) ? intval($_POST['spec_id']) : 0;
        $title = isset($_POST['title']) ? trim($_POST['title']) : '';
        $category_tag = isset($_POST['category_tag']) ? trim($_POST['category_tag']) : '';
        $icon = isset($_POST['icon']) ? trim($_POST['icon']) : 'fa-briefcase';
        $description = isset($_POST['description']) ? trim($_POST['description']) : '';
        $bullet1 = isset($_POST['bullet1']) ? trim($_POST['bullet1']) : '';
        $bullet2 = isset($_POST['bullet2']) ? trim($_POST['bullet2']) : '';
        $bullet3 = isset($_POST['bullet3']) ? trim($_POST['bullet3']) : '';
        $button_text = isset($_POST['button_text']) ? trim($_POST['button_text']) : 'Explore Details';
        $button_link = isset($_POST['button_link']) ? trim($_POST['button_link']) : 'contact.php';
        $color = isset($_POST['color']) ? trim($_POST['color']) : 'blue';
        $is_active = isset($_POST['is_active']) ? 1 : 0;
        
        if ($id <= 0 || empty($title) || empty($category_tag) || empty($description)) {
            $alertError = 'Invalid parameters. Title, Category Tag, and Description are required.';
        } else {
            try {
                $stmt = $pdo->prepare("
                    UPDATE specialist_services 
                    SET title = :title, 
                        category_tag = :category_tag, 
                        icon = :icon, 
                        description = :description, 
                        bullet1 = :bullet1, 
                        bullet2 = :bullet2, 
                        bullet3 = :bullet3, 
                        button_text = :button_text, 
                        button_link = :button_link, 
                        color = :color, 
                        is_active = :is_active 
                    WHERE id = :id
                ");
                $stmt->execute([
                    'title' => $title,
                    'category_tag' => $category_tag,
                    'icon' => $icon,
                    'description' => $description,
                    'bullet1' => $bullet1,
                    'bullet2' => $bullet2,
                    'bullet3' => $bullet3,
                    'button_text' => $button_text,
                    'button_link' => $button_link,
                    'color' => $color,
                    'is_active' => $is_active,
                    'id' => $id
                ]);
                $alertSuccess = 'Specialist service card updated successfully!';
            } catch (PDOException $e) {
                $alertError = 'Failed to update specialist service: ' . $e->getMessage();
            }
        }
    }
    
    // 3. DELETE SERVICE
    elseif ($action === 'delete') {
        $id = isset($_POST['spec_id']) ? intval($_POST['spec_id']) : 0;
        if ($id <= 0) {
            $alertError = 'Invalid specialist service ID specified for deletion.';
        } else {
            try {
                $stmt = $pdo->prepare("DELETE FROM specialist_services WHERE id = :id");
                $stmt->execute(['id' => $id]);
                $alertSuccess = 'Specialist service card deleted permanently!';
            } catch (PDOException $e) {
                $alertError = 'Failed to delete specialist service: ' . $e->getMessage();
            }
        }
    }
}

// Fetch all specialist services with pagination
$specialist_services = [];
try {
    $countQuery = $pdo->query("SELECT COUNT(*) FROM specialist_services");
    $totalCount = intval($countQuery->fetchColumn());
    
    $pagination = get_pagination_params($totalCount, 10);
    $limit = $pagination['limit'];
    $offset = $pagination['offset'];
    $page = $pagination['page'];
    $totalPages = $pagination['totalPages'];
    
    $stmt = $pdo->prepare("SELECT * FROM specialist_services ORDER BY id ASC LIMIT :limit OFFSET :offset");
    $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();
    $specialist_services = $stmt->fetchAll();
} catch (PDOException $e) {
    $alertError = 'Could not fetch specialist services: ' . $e->getMessage();
}
?>

<div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 2.5rem; flex-wrap: wrap; gap: 1rem;">
    <h1 class="page-title">
        Specialist Services Manager
        <span>Manage premium special assistance cards (Work Abroad/Jobs, Australia & Canada PR, Visitor Visas) displaying list benefits</span>
    </h1>
    <button class="btn-pill" onclick="openAddModal()">
        <i class="fa-solid fa-plus"></i>
        <span>Add New Service Card</span>
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

<?php if (empty($specialist_services)): ?>
    <div class="panel-card" style="text-align: center; padding: 5rem 2rem; color: var(--text-secondary);">
        <i class="fa-solid fa-briefcase" style="font-size: 3rem; margin-bottom: 1.5rem; color: var(--text-muted);"></i>
        <h3>No specialist services cards exist in the database.</h3>
        <p style="margin-top: 0.5rem; font-size: 0.9rem;">Click "Add New Service Card" to create your first consulting offer card!</p>
    </div>
<?php else: ?>
    <div class="crud-grid">
        <?php foreach ($specialist_services as $spec): 
            $isActive = intval($spec['is_active']) === 1;
            $color = clean_output($spec['color']);
            $icon = clean_output($spec['icon']);
        ?>
            <div class="crud-card <?php echo $isActive ? 'crud-card-active' : 'crud-card-inactive'; ?>">
                <div class="crud-card-badge-icon">
                    <i class="fa-solid <?php echo $isActive ? 'fa-check' : 'fa-eye-slash'; ?>"></i>
                </div>
                
                <div class="crud-card-header">
                    <div class="crud-card-icon icon-<?php echo $color; ?>">
                        <i class="fa-solid <?php echo $icon; ?>"></i>
                    </div>
                    <h4 class="crud-card-title"><?php echo clean_output($spec['title']); ?> <span style="font-size: 0.75rem; font-weight: normal; background: var(--bg-hover); color: var(--text-secondary); padding: 0.2rem 0.4rem; border-radius: 4px; display: inline-block; margin-left: 0.4rem;"><?php echo clean_output($spec['category_tag']); ?></span></h4>
                </div>
                
                <p class="crud-card-desc"><?php echo clean_output($spec['description']); ?></p>
                
                <div style="background: rgba(15, 23, 42, 0.03); border-radius: 8px; padding: 0.75rem; margin: 0.75rem 0;">
                    <strong style="font-size: 0.75rem; color: var(--text-secondary); text-transform: uppercase; letter-spacing: 0.5px; display: block; margin-bottom: 0.4rem;">Included Features:</strong>
                    <ul style="list-style: none; padding: 0; margin: 0; display: flex; flex-direction: column; gap: 0.25rem;">
                        <?php for($i=1; $i<=3; $i++): $bullet = $spec["bullet$i"]; if(!empty($bullet)): ?>
                            <li style="font-size: 0.8rem; display: flex; align-items: center; gap: 0.4rem; color: var(--text-secondary);"><i class="fa-solid fa-square-check" style="color: var(--primary); font-size: 0.75rem;"></i> <?php echo clean_output($bullet); ?></li>
                        <?php endif; endfor; ?>
                    </ul>
                </div>

                <div class="crud-card-footer">
                    <span class="crud-card-info">Btn: <strong><?php echo clean_output($spec['button_text']); ?></strong></span>
                    <div class="crud-card-actions">
                        <button class="btn-action action-edit" title="Edit Service" onclick="openEditModal(<?php echo htmlspecialchars(json_encode($spec)); ?>)">
                            <i class="fa-solid fa-pen-to-square"></i>
                        </button>
                        <button class="btn-action action-delete" title="Delete Service" onclick="triggerDeleteSpec(<?php echo $spec['id']; ?>, '<?php echo clean_output($spec['title']); ?>')">
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
<div class="modal-overlay" id="specModal">
    <div class="modal-container">
        <div class="modal-header">
            <h3 class="modal-title" id="modalTitle">Add Specialist Service Card</h3>
            <span class="modal-close" onclick="closeSpecModal()">&times;</span>
        </div>
        <form action="specialist_services.php" method="POST">
            <div class="modal-body">
                <input type="hidden" name="action" id="modalAction" value="add">
                <input type="hidden" name="spec_id" id="edit_spec_id">
                
                <div class="detail-grid">
                    <div class="form-group">
                        <label for="spec_title" class="form-label">Service Card Title *</label>
                        <input type="text" name="title" id="spec_title" class="form-control" placeholder="e.g., Global Job Placement" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="spec_tag" class="form-label">Category Subtag *</label>
                        <input type="text" name="category_tag" id="spec_tag" class="form-control" placeholder="e.g., Work Abroad" required>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="spec_desc" class="form-label">Service Description *</label>
                    <textarea name="description" id="spec_desc" class="form-control" rows="2" placeholder="e.g., End-to-end work assistance, job search support, and interview briefing..." required></textarea>
                </div>

                <div class="form-group">
                    <label for="spec_bullet1" class="form-label">Benefit / Feature Bullet Point 1</label>
                    <input type="text" name="bullet1" id="spec_bullet1" class="form-control" placeholder="e.g., Job support for all major countries">
                </div>
                
                <div class="form-group">
                    <label for="spec_bullet2" class="form-label">Benefit / Feature Bullet Point 2</label>
                    <input type="text" name="bullet2" id="spec_bullet2" class="form-control" placeholder="e.g., Resume & LinkedIn optimization">
                </div>

                <div class="form-group">
                    <label for="spec_bullet3" class="form-label">Benefit / Feature Bullet Point 3</label>
                    <input type="text" name="bullet3" id="spec_bullet3" class="form-control" placeholder="e.g., Professional interview coaching">
                </div>

                <div class="detail-grid">
                    <div class="form-group">
                        <label for="spec_btn" class="form-label">Button Call to Action (CTA) *</label>
                        <input type="text" name="button_text" id="spec_btn" class="form-control" placeholder="e.g., Explore Job Services" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="spec_link" class="form-label">Button Redirection URL *</label>
                        <input type="text" name="button_link" id="spec_link" class="form-control" placeholder="e.g., contact.php" required>
                    </div>
                </div>

                <div class="detail-grid">
                    <div class="form-group">
                        <label for="spec_icon" class="form-label">FontAwesome Icon *</label>
                        <input type="text" name="icon" id="spec_icon" class="form-control" placeholder="e.g., fa-briefcase" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="spec_color" class="form-label">Accent Highlight Theme *</label>
                        <select name="color" id="spec_color" class="form-select" required>
                            <option value="blue">Blue Accent</option>
                            <option value="purple">Purple Accent</option>
                            <option value="orange">Orange Accent</option>
                            <option value="teal">Teal Accent</option>
                            <option value="pink">Pink Accent</option>
                            <option value="gold">Gold Accent</option>
                        </select>
                    </div>
                </div>

                <div class="form-group" style="margin-bottom: 0; display: flex; align-items: center; gap: 0.5rem;">
                    <input type="checkbox" name="is_active" id="spec_active" checked style="width: 18px; height: 18px; accent-color: var(--primary); cursor: pointer;">
                    <label for="spec_active" class="form-label" style="margin-bottom: 0; cursor: pointer; user-select: none;">Publish immediately (Active & Visible on homepage)</label>
                </div>
            </div>
            
            <div class="modal-footer">
                <button type="button" class="btn-outline" onclick="closeSpecModal()">Cancel</button>
                <button type="submit" class="btn-pill">
                    <i class="fa-solid fa-floppy-disk"></i>
                    <span>Save Service Card</span>
                </button>
            </div>
        </form>
    </div>
</div>

<!-- 2. DELETE CONFIRMATION MODAL -->
<div class="modal-overlay" id="deleteModal">
    <div class="modal-container" style="max-width: 400px;">
        <div class="modal-header" style="border-bottom: none; padding-bottom: 0;">
            <h3 class="modal-title" style="color: var(--danger);"><i class="fa-solid fa-triangle-exclamation"></i> Delete Specialist Service Card?</h3>
            <span class="modal-close" onclick="closeDeleteModal()">&times;</span>
        </div>
        <form action="specialist_services.php" method="POST">
            <div class="modal-body" style="padding-top: 1rem;">
                <input type="hidden" name="action" value="delete">
                <input type="hidden" name="spec_id" id="deleteSpecId">
                <p style="font-size: 0.95rem; line-height: 1.6; color: var(--text-secondary);">
                    Are you sure you want to permanently delete the specialist service card <strong id="deleteSpecName" style="color: var(--text-primary);">Service</strong>?
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
    document.getElementById('modalTitle').innerText = 'Add Specialist Service Card';
    document.getElementById('edit_spec_id').value = '';
    document.getElementById('spec_title').value = '';
    document.getElementById('spec_tag').value = '';
    document.getElementById('spec_desc').value = '';
    document.getElementById('spec_bullet1').value = '';
    document.getElementById('spec_bullet2').value = '';
    document.getElementById('spec_bullet3').value = '';
    document.getElementById('spec_btn').value = 'Explore Details';
    document.getElementById('spec_link').value = 'contact.php';
    document.getElementById('spec_icon').value = 'fa-briefcase';
    document.getElementById('spec_color').value = 'blue';
    document.getElementById('spec_active').checked = true;
    
    document.getElementById('specModal').classList.add('active');
}

function openEditModal(spec) {
    document.getElementById('modalAction').value = 'update';
    document.getElementById('modalTitle').innerText = 'Edit Specialist Service: ' + spec.title;
    document.getElementById('edit_spec_id').value = spec.id;
    document.getElementById('spec_title').value = spec.title;
    document.getElementById('spec_tag').value = spec.category_tag;
    document.getElementById('spec_desc').value = spec.description;
    document.getElementById('spec_bullet1').value = spec.bullet1;
    document.getElementById('spec_bullet2').value = spec.bullet2;
    document.getElementById('spec_bullet3').value = spec.bullet3;
    document.getElementById('spec_btn').value = spec.button_text;
    document.getElementById('spec_link').value = spec.button_link;
    document.getElementById('spec_icon').value = spec.icon;
    document.getElementById('spec_color').value = spec.color;
    document.getElementById('spec_active').checked = parseInt(spec.is_active) === 1;
    
    document.getElementById('specModal').classList.add('active');
}

function closeSpecModal() {
    document.getElementById('specModal').classList.remove('active');
}

function triggerDeleteSpec(id, name) {
    document.getElementById('deleteSpecId').value = id;
    document.getElementById('deleteSpecName').innerText = name;
    document.getElementById('deleteModal').classList.add('active');
}

function closeDeleteModal() {
    document.getElementById('deleteModal').classList.remove('active');
}
</script>

<?php require_once 'includes/footer.php'; ?>
