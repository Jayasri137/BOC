<?php
// admin/services.php - Services CRUD Editor
$pageTitle = 'Services Manager';
require_once 'includes/header.php'; // handles session and pdo load

$alertSuccess = '';
$alertError = '';

// --- HANDLE POST REQUESTS (ADD, UPDATE, DELETE, TOGGLE) ---
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = isset($_POST['action']) ? trim($_POST['action']) : '';
    
    // 1. ADD NEW SERVICE
    if ($action === 'add') {
        $icon = isset($_POST['icon']) ? trim($_POST['icon']) : 'fa-user-graduate';
        $title = isset($_POST['title']) ? trim($_POST['title']) : '';
        $description = isset($_POST['description']) ? trim($_POST['description']) : '';
        $link = isset($_POST['link']) ? trim($_POST['link']) : '#';
        $color = isset($_POST['color']) ? trim($_POST['color']) : 'blue';
        $is_active = isset($_POST['is_active']) ? 1 : 0;
        
        if (empty($title) || empty($description)) {
            $alertError = 'Title and Description are required fields.';
        } else {
            try {
                $stmt = $pdo->prepare("
                    INSERT INTO services (icon, title, description, link, color, is_active) 
                    VALUES (:icon, :title, :description, :link, :color, :is_active)
                ");
                $stmt->execute([
                    'icon' => $icon,
                    'title' => $title,
                    'description' => $description,
                    'link' => $link,
                    'color' => $color,
                    'is_active' => $is_active
                ]);
                $alertSuccess = 'Service card added successfully!';
            } catch (PDOException $e) {
                $alertError = 'Failed to add service: ' . $e->getMessage();
            }
        }
    }
    
    // 2. UPDATE SERVICE
    elseif ($action === 'update') {
        $id = isset($_POST['service_id']) ? intval($_POST['service_id']) : 0;
        $icon = isset($_POST['icon']) ? trim($_POST['icon']) : 'fa-user-graduate';
        $title = isset($_POST['title']) ? trim($_POST['title']) : '';
        $description = isset($_POST['description']) ? trim($_POST['description']) : '';
        $link = isset($_POST['link']) ? trim($_POST['link']) : '#';
        $color = isset($_POST['color']) ? trim($_POST['color']) : 'blue';
        $is_active = isset($_POST['is_active']) ? 1 : 0;
        
        if ($id <= 0 || empty($title) || empty($description)) {
            $alertError = 'Invalid parameters. Title and Description are required.';
        } else {
            try {
                $stmt = $pdo->prepare("
                    UPDATE services 
                    SET icon = :icon, 
                        title = :title, 
                        description = :description, 
                        link = :link, 
                        color = :color, 
                        is_active = :is_active 
                    WHERE id = :id
                ");
                $stmt->execute([
                    'icon' => $icon,
                    'title' => $title,
                    'description' => $description,
                    'link' => $link,
                    'color' => $color,
                    'is_active' => $is_active,
                    'id' => $id
                ]);
                $alertSuccess = 'Service card updated successfully!';
            } catch (PDOException $e) {
                $alertError = 'Failed to update service: ' . $e->getMessage();
            }
        }
    }
    
    // 3. DELETE SERVICE
    elseif ($action === 'delete') {
        $id = isset($_POST['service_id']) ? intval($_POST['service_id']) : 0;
        if ($id <= 0) {
            $alertError = 'Invalid service ID specified for deletion.';
        } else {
            try {
                $stmt = $pdo->prepare("DELETE FROM services WHERE id = :id");
                $stmt->execute(['id' => $id]);
                $alertSuccess = 'Service card deleted permanently!';
            } catch (PDOException $e) {
                $alertError = 'Failed to delete service: ' . $e->getMessage();
            }
        }
    }
}

// Fetch all services with pagination
$services = [];
$setupNeeded = false;
try {
    $countQuery = $pdo->query("SELECT COUNT(*) FROM services");
    $totalCount = intval($countQuery->fetchColumn());
    
    $pagination = get_pagination_params($totalCount, 10);
    $limit = $pagination['limit'];
    $offset = $pagination['offset'];
    $page = $pagination['page'];
    $totalPages = $pagination['totalPages'];
    
    $stmt = $pdo->prepare("SELECT * FROM services ORDER BY id ASC LIMIT :limit OFFSET :offset");
    $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();
    $services = $stmt->fetchAll();
} catch (PDOException $e) {
    $setupNeeded = true;
    $alertError = 'Could not fetch services: ' . $e->getMessage() . '. Have you initialized database tables?';
}
?>

<div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 2.5rem; flex-wrap: wrap; gap: 1rem;">
    <h1 class="page-title">
        Services Cards Manager
        <span>Manage, build, and publish premium service description cards on the website homepage</span>
    </h1>
    <?php if (!$setupNeeded): ?>
        <button class="btn-pill" onclick="openAddModal()">
            <i class="fa-solid fa-plus"></i>
            <span>Add New Service Card</span>
        </button>
    <?php endif; ?>
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

<?php if ($setupNeeded): ?>
    <div class="panel-card" style="text-align: center; padding: 4rem 2rem;">
        <div style="font-size: 3.5rem; color: var(--warning); margin-bottom: 1.5rem;"><i class="fa-solid fa-database"></i></div>
        <h2>Database Setup Required</h2>
        <p style="color: var(--text-secondary); max-width: 500px; margin: 1rem auto 2rem; line-height: 1.6;">Please initialize the admin tables first by clicking below to seed default values.</p>
        <a href="setup.php" class="btn-pill" style="padding: 1rem 2rem;"><i class="fa-solid fa-screwdriver-wrench"></i> Run Database Installer</a>
    </div>
<?php else: ?>

    <?php echo render_limit_dropdown($limit); ?>

    <?php if (empty($services)): ?>
        <div class="panel-card" style="text-align: center; padding: 5rem 2rem; color: var(--text-secondary);">
            <i class="fa-solid fa-folder-open" style="font-size: 3rem; margin-bottom: 1.5rem; color: var(--text-muted);"></i>
            <h3>No service cards exist in the database.</h3>
            <p style="margin-top: 0.5rem; font-size: 0.9rem;">Click the "Add New Service Card" button to create your first card!</p>
        </div>
    <?php else: ?>
        <div class="crud-grid">
            <?php foreach ($services as $srv): 
                $isActive = intval($srv['is_active']) === 1;
                $color = clean_output($srv['color']);
                $icon = clean_output($srv['icon']);
            ?>
                <div class="crud-card <?php echo $isActive ? 'crud-card-active' : 'crud-card-inactive'; ?>">
                    <div class="crud-card-badge-icon">
                        <i class="fa-solid <?php echo $isActive ? 'fa-check' : 'fa-eye-slash'; ?>"></i>
                    </div>
                    
                    <div class="crud-card-header">
                        <div class="crud-card-icon icon-<?php echo $color; ?>">
                            <i class="fa-solid <?php echo $icon; ?>"></i>
                        </div>
                        <h4 class="crud-card-title"><?php echo clean_output($srv['title']); ?></h4>
                    </div>
                    
                    <p class="crud-card-desc"><?php echo clean_output($srv['description']); ?></p>
                    
                    <div class="crud-card-footer">
                        <span class="crud-card-info">Color: <strong><?php echo ucfirst($color); ?></strong></span>
                        <div class="crud-card-actions">
                            <button class="btn-action action-edit" title="Edit Service" onclick="openEditModal(<?php echo htmlspecialchars(json_encode($srv)); ?>)">
                                <i class="fa-solid fa-pen-to-square"></i>
                            </button>
                            <button class="btn-action action-delete" title="Delete Service" onclick="triggerDeleteService(<?php echo $srv['id']; ' - ' . clean_output($srv['title']); ?>, '<?php echo clean_output($srv['title']); ?>')">
                                <i class="fa-solid fa-trash-can"></i>
                            </button>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        <?php echo render_pagination_buttons($page, $totalPages); ?>
    <?php endif; ?>

<?php endif; ?>

<!-- 1. ADD / EDIT DIALOG MODAL -->
<div class="modal-overlay" id="serviceModal">
    <div class="modal-container">
        <div class="modal-header">
            <h3 class="modal-title" id="modalTitle">Add Service Card</h3>
            <span class="modal-close" onclick="closeServiceModal()">&times;</span>
        </div>
        <form action="services.php" method="POST">
            <div class="modal-body">
                <input type="hidden" name="action" id="modalAction" value="add">
                <input type="hidden" name="service_id" id="edit_service_id">
                
                <div class="form-group">
                    <label for="srv_title" class="form-label">Service Title *</label>
                    <input type="text" name="title" id="srv_title" class="form-control" placeholder="e.g., Student Counselling" required>
                </div>
                
                <div class="form-group">
                    <label for="srv_desc" class="form-label">Card Description *</label>
                    <textarea name="description" id="srv_desc" class="form-control" rows="3" placeholder="Enter short, engaging overview explaining what this service does..." required></textarea>
                </div>

                <div class="detail-grid">
                    <div class="form-group">
                        <label for="srv_icon" class="form-label">FontAwesome Icon *</label>
                        <input type="text" name="icon" id="srv_icon" class="form-control" placeholder="e.g., fa-user-graduate" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="srv_color" class="form-label">Highlight Accent Theme *</label>
                        <select name="color" id="srv_color" class="form-select" required>
                            <option value="blue">Blue Accent</option>
                            <option value="purple">Purple Accent</option>
                            <option value="orange">Orange Accent</option>
                            <option value="teal">Teal Accent</option>
                            <option value="pink">Pink Accent</option>
                            <option value="gold">Gold Accent</option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label for="srv_link" class="form-label">Redirect Pathway URL (Button Link)</label>
                    <input type="text" name="link" id="srv_link" class="form-control" placeholder="e.g., services.php?s=counselling">
                </div>

                <div class="form-group" style="margin-bottom: 0; display: flex; align-items: center; gap: 0.5rem;">
                    <input type="checkbox" name="is_active" id="srv_active" checked style="width: 18px; height: 18px; accent-color: var(--primary); cursor: pointer;">
                    <label for="srv_active" class="form-label" style="margin-bottom: 0; cursor: pointer; user-select: none;">Publish immediately (Active & Visible on homepage)</label>
                </div>
            </div>
            
            <div class="modal-footer">
                <button type="button" class="btn-outline" onclick="closeServiceModal()">Cancel</button>
                <button type="submit" class="btn-pill">
                    <i class="fa-solid fa-floppy-disk"></i>
                    <span>Save Service</span>
                </button>
            </div>
        </form>
    </div>
</div>

<!-- 2. DELETE CONFIRMATION MODAL -->
<div class="modal-overlay" id="deleteModal">
    <div class="modal-container" style="max-width: 400px;">
        <div class="modal-header" style="border-bottom: none; padding-bottom: 0;">
            <h3 class="modal-title" style="color: var(--danger);"><i class="fa-solid fa-triangle-exclamation"></i> Delete Service?</h3>
            <span class="modal-close" onclick="closeDeleteModal()">&times;</span>
        </div>
        <form action="services.php" method="POST">
            <div class="modal-body" style="padding-top: 1rem;">
                <input type="hidden" name="action" value="delete">
                <input type="hidden" name="service_id" id="deleteServiceId">
                <p style="font-size: 0.95rem; line-height: 1.6; color: var(--text-secondary);">
                    Are you sure you want to permanently delete the service card <strong id="deleteServiceName" style="color: var(--text-primary);">Service Card</strong>?
                </p>
                <p style="font-size: 0.8rem; color: var(--text-muted); margin-top: 0.75rem;">
                    This card will instantly vanish from the front-end. It cannot be recovered.
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
    document.getElementById('modalTitle').innerText = 'Add Service Card';
    document.getElementById('edit_service_id').value = '';
    document.getElementById('srv_title').value = '';
    document.getElementById('srv_desc').value = '';
    document.getElementById('srv_icon').value = 'fa-user-graduate';
    document.getElementById('srv_color').value = 'blue';
    document.getElementById('srv_link').value = '#';
    document.getElementById('srv_active').checked = true;
    
    document.getElementById('serviceModal').classList.add('active');
}

function openEditModal(srv) {
    document.getElementById('modalAction').value = 'update';
    document.getElementById('modalTitle').innerText = 'Edit Service: ' + srv.title;
    document.getElementById('edit_service_id').value = srv.id;
    document.getElementById('srv_title').value = srv.title;
    document.getElementById('srv_desc').value = srv.description;
    document.getElementById('srv_icon').value = srv.icon;
    document.getElementById('srv_color').value = srv.color;
    document.getElementById('srv_link').value = srv.link || '#';
    document.getElementById('srv_active').checked = parseInt(srv.is_active) === 1;
    
    document.getElementById('serviceModal').classList.add('active');
}

function closeServiceModal() {
    document.getElementById('serviceModal').classList.remove('active');
}

function triggerDeleteService(id, name) {
    document.getElementById('deleteServiceId').value = id;
    document.getElementById('deleteServiceName').innerText = name;
    document.getElementById('deleteModal').classList.add('active');
}

function closeDeleteModal() {
    document.getElementById('deleteModal').classList.remove('active');
}
</script>

<?php require_once 'includes/footer.php'; ?>
