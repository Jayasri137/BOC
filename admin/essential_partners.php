<?php
// admin/essential_partners.php - Manage Bank/Finance/Insurance Partners
$pageTitle = 'Essential Service Partners';
require_once 'includes/header.php';

$alertSuccess = '';
$alertError = '';

// --- HANDLE POST REQUESTS (ADD/EDIT/DELETE) ---
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = isset($_POST['action']) ? trim($_POST['action']) : '';
    
    // 1. ADD PARTNER
    if ($action === 'add_partner') {
        $category = $_POST['category'] ?? '';
        $country = $_POST['country_name'] ?? '';
        $name = $_POST['partner_name'] ?? '';
        $features = $_POST['features'] ?? '';
        $link = $_POST['link'] ?? 'consultation.php';
        
        if (empty($category) || empty($name)) {
            $alertError = 'Category and Partner Name are required.';
        } else {
            try {
                $stmt = $pdo->prepare("INSERT INTO essential_partners (category, country_name, partner_name, features, link) VALUES (?, ?, ?, ?, ?)");
                $stmt->execute([$category, $country, $name, $features, $link]);
                $alertSuccess = 'Partner added successfully!';
            } catch (PDOException $e) {
                $alertError = 'Failed to add partner: ' . $e->getMessage();
            }
        }
    }
    
    // 2. UPDATE PARTNER
    elseif ($action === 'update_partner') {
        $id = intval($_POST['partner_id']);
        $category = $_POST['category'] ?? '';
        $country = $_POST['country_name'] ?? '';
        $name = $_POST['partner_name'] ?? '';
        $features = $_POST['features'] ?? '';
        $link = $_POST['link'] ?? '';
        
        try {
            $stmt = $pdo->prepare("UPDATE essential_partners SET category = ?, country_name = ?, partner_name = ?, features = ?, link = ? WHERE id = ?");
            $stmt->execute([$category, $country, $name, $features, $link, $id]);
            $alertSuccess = 'Partner updated successfully!';
        } catch (PDOException $e) {
            $alertError = 'Failed to update partner: ' . $e->getMessage();
        }
    }
    
    // 3. DELETE PARTNER
    elseif ($action === 'delete_partner') {
        $id = intval($_POST['partner_id']);
        try {
            $stmt = $pdo->prepare("DELETE FROM essential_partners WHERE id = ?");
            $stmt->execute([$id]);
            $alertSuccess = 'Partner removed permanently!';
        } catch (PDOException $e) {
            $alertError = 'Failed to delete partner: ' . $e->getMessage();
        }
    }
}

// Fetch total partners count
$total_count = $pdo->query("SELECT COUNT(*) FROM essential_partners")->fetchColumn();
$pag = get_pagination_params($total_count, 10);
$limit = $pag['limit'];
$page = $pag['page'];
$totalPages = $pag['totalPages'];
$offset = $pag['offset'];

// Fetch paginated partners
if ($limit === 999999) {
    $partners = $pdo->query("SELECT * FROM essential_partners ORDER BY category, country_name")->fetchAll();
} else {
    $stmt = $pdo->prepare("SELECT * FROM essential_partners ORDER BY category, country_name LIMIT :limit OFFSET :offset");
    $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();
    $partners = $stmt->fetchAll();
}
$all_countries = $pdo->query("SELECT name FROM countries WHERE is_active = 1 ORDER BY name ASC")->fetchAll();

$categories = [
    'loan' => 'Education Loan',
    'insurance' => 'Health Insurance',
    'bank' => 'Bank Account',
    'forex' => 'Forex Transfer',
    'sim' => 'SIM Card'
];
?>

<div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 2.5rem; flex-wrap: wrap; gap: 1rem;">
    <h1 class="page-title">
        Essential Service Partners
        <span>Manage Bank Accounts, Education Loans, Health Insurance, Forex, and SIM Card partners by destination country</span>
    </h1>
    <button class="btn-pill" onclick="openAddModal()">
        <i class="fa-solid fa-plus"></i>
        <span>Add New Partner</span>
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

<?php if (empty($partners)): ?>
    <div class="panel-card" style="text-align: center; padding: 5rem 2rem; color: var(--text-secondary);">
        <i class="fa-solid fa-handshake" style="font-size: 3rem; margin-bottom: 1.5rem; color: var(--text-muted);"></i>
        <h3>No service partners exist in the database.</h3>
        <p style="margin-top: 0.5rem; font-size: 0.9rem;">Click "Add New Partner" to register your first partner office or service!</p>
    </div>
<?php else: ?>
    <?php 
    $limitParam = ($limit === 999999) ? 'all' : $limit;
    echo render_limit_dropdown($limit); 
    ?>
    <div class="panel-card">
        <div class="data-table-wrapper">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Category</th>
                        <th>Country</th>
                        <th>Partner Name</th>
                        <th>Key Features</th>
                        <th>Application Link</th>
                        <th style="width: 100px; text-align: center;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($partners as $p): ?>
                        <tr>
                            <td><span class="badge badge--<?= $p['category'] ?>"><?= isset($categories[$p['category']]) ? $categories[$p['category']] : ucfirst($p['category']) ?></span></td>
                            <td><strong style="color: var(--text-primary);"><i class="fa-solid fa-earth-americas" style="font-size: 0.8rem; margin-right: 0.4rem; color: var(--text-muted);"></i> <?= clean_output($p['country_name']) ?></strong></td>
                            <td><span style="font-weight: 600; color: var(--text-primary);"><?= clean_output($p['partner_name']) ?></span></td>
                            <td style="max-width: 320px; font-size: 0.85rem; color: var(--text-secondary);"><?= clean_output($p['features']) ?></td>
                            <td><code style="font-size: 0.8rem; background: #f1f5f9; padding: 0.25rem 0.5rem; border-radius: 6px; color: var(--accent); font-weight: 500;"><?= clean_output($p['link']) ?></code></td>
                            <td>
                                <div style="display: flex; gap: 0.5rem; justify-content: center;">
                                    <button class="btn-action action-edit" title="Edit Partner" onclick="openEditModal(<?= htmlspecialchars(json_encode($p)) ?>)">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </button>
                                    <button class="btn-action action-delete" title="Delete Partner" onclick="triggerDeletePartner(<?= $p['id'] ?>, '<?= htmlspecialchars($p['partner_name'], ENT_QUOTES) ?>')">
                                        <i class="fa-solid fa-trash-can"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php 
    echo render_pagination_buttons($page, $totalPages, ['limit' => $limitParam]); 
    ?>
<?php endif; ?>

<!-- 1. ADD DIALOG MODAL -->
<div class="modal-overlay" id="addPartnerModal">
    <div class="modal-container" style="max-width: 500px;">
        <div class="modal-header">
            <h3 class="modal-title">Add New Service Partner</h3>
            <span class="modal-close" onclick="closeModal('addPartnerModal')">&times;</span>
        </div>
        <form method="POST">
            <div class="modal-body">
                <input type="hidden" name="action" value="add_partner">
                
                <div class="form-group">
                    <label class="form-label">Category</label>
                    <select name="category" class="form-select" required>
                        <option value="loan">Education Loan</option>
                        <option value="insurance">Health Insurance</option>
                        <option value="bank">Bank Account</option>
                        <option value="forex">Money Transfer / Forex</option>
                        <option value="sim">SIM Card</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Country (Select 'Global' for all)</label>
                    <select name="country_name" class="form-select">
                        <option value="Global">Global</option>
                        <?php foreach ($all_countries as $c): ?>
                            <option value="<?= $c['name'] ?>"><?= $c['name'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Partner Name</label>
                    <input type="text" name="partner_name" class="form-control" required placeholder="e.g. State Bank of India">
                </div>
                
                <div class="form-group">
                    <label class="form-label">Features (Comma separated)</label>
                    <textarea name="features" class="form-control" rows="3" placeholder="Feature 1, Feature 2, Feature 3"></textarea>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Application Link / CTA URL</label>
                    <input type="text" name="link" class="form-control" value="consultation.php">
                </div>
            </div>
            
            <div class="modal-footer">
                <button type="button" class="btn-outline" onclick="closeModal('addPartnerModal')">Cancel</button>
                <button type="submit" class="btn-pill">
                    <i class="fa-solid fa-floppy-disk"></i>
                    <span>Save Service Partner</span>
                </button>
            </div>
        </form>
    </div>
</div>

<!-- 2. EDIT DIALOG MODAL -->
<div class="modal-overlay" id="editPartnerModal">
    <div class="modal-container" style="max-width: 500px;">
        <div class="modal-header">
            <h3 class="modal-title" id="editModalTitle">Edit Partner Details</h3>
            <span class="modal-close" onclick="closeModal('editPartnerModal')">&times;</span>
        </div>
        <form method="POST">
            <div class="modal-body">
                <input type="hidden" name="action" value="update_partner">
                <input type="hidden" name="partner_id" id="edit_partner_id">
                
                <div class="form-group">
                    <label class="form-label">Category</label>
                    <select name="category" id="edit_category" class="form-select" required>
                        <option value="loan">Education Loan</option>
                        <option value="insurance">Health Insurance</option>
                        <option value="bank">Bank Account</option>
                        <option value="forex">Money Transfer / Forex</option>
                        <option value="sim">SIM Card</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Country</label>
                    <select name="country_name" id="edit_country" class="form-select">
                        <option value="Global">Global</option>
                        <?php foreach ($all_countries as $c): ?>
                            <option value="<?= $c['name'] ?>"><?= $c['name'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Partner Name</label>
                    <input type="text" name="partner_name" id="edit_name" class="form-control" required>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Features</label>
                    <textarea name="features" id="edit_features" class="form-control" rows="3"></textarea>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Application Link</label>
                    <input type="text" name="link" id="edit_link" class="form-control">
                </div>
            </div>
            
            <div class="modal-footer">
                <button type="button" class="btn-outline" onclick="closeModal('editPartnerModal')">Cancel</button>
                <button type="submit" class="btn-pill">
                    <i class="fa-solid fa-circle-check"></i>
                    <span>Update Changes</span>
                </button>
            </div>
        </form>
    </div>
</div>

<!-- 3. DELETE CONFIRMATION DIALOG MODAL -->
<div class="modal-overlay" id="deleteModal">
    <div class="modal-container" style="max-width: 400px;">
        <div class="modal-header" style="border-bottom: none; padding-bottom: 0;">
            <h3 class="modal-title" style="color: var(--danger);"><i class="fa-solid fa-triangle-exclamation"></i> Delete Service Partner?</h3>
            <span class="modal-close" onclick="closeModal('deleteModal')">&times;</span>
        </div>
        <form method="POST">
            <div class="modal-body" style="padding-top: 1rem;">
                <input type="hidden" name="action" value="delete_partner">
                <input type="hidden" name="partner_id" id="deletePartnerId">
                <p style="font-size: 0.95rem; line-height: 1.6; color: var(--text-secondary);">
                    Are you sure you want to permanently delete the service partner <strong id="deletePartnerName" style="color: var(--text-primary);">Partner</strong>?
                </p>
            </div>
            <div class="modal-footer" style="border-top: none; padding-top: 0;">
                <button type="button" class="btn-outline" onclick="closeModal('deleteModal')">Cancel</button>
                <button type="submit" class="btn-pill" style="background: var(--danger); box-shadow: 0 5px 10px var(--danger-glow);">
                    <i class="fa-solid fa-trash-can"></i>
                    <span>Confirm Delete</span>
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function showModal(id) {
    document.getElementById(id).classList.add('active');
}
function closeModal(id) {
    document.getElementById(id).classList.remove('active');
}
function openAddModal() {
    document.getElementById('addPartnerModal').querySelector('form').reset();
    showModal('addPartnerModal');
}
function openEditModal(data) {
    document.getElementById('edit_partner_id').value = data.id;
    document.getElementById('edit_category').value = data.category;
    document.getElementById('edit_country').value = data.country_name;
    document.getElementById('edit_name').value = data.partner_name;
    document.getElementById('edit_features').value = data.features;
    document.getElementById('edit_link').value = data.link;
    
    document.getElementById('editModalTitle').innerText = 'Edit Partner: ' + data.partner_name;
    showModal('editPartnerModal');
}
function triggerDeletePartner(id, name) {
    document.getElementById('deletePartnerId').value = id;
    document.getElementById('deletePartnerName').innerText = name;
    showModal('deleteModal');
}
</script>

<style>
.badge--loan { background: #f0fdf4; color: #15803d; border: 1px solid #bbf7d0; }
.badge--insurance { background: #eff6ff; color: #1d4ed8; border: 1px solid #bfdbfe; }
.badge--bank { background: #fffbeb; color: #b45309; border: 1px solid #fde68a; }
.badge--forex { background: #f5f3ff; color: #6d28d9; border: 1px solid #ddd6fe; }
.badge--sim { background: #fef2f2; color: #b91c1c; border: 1px solid #fecaca; }
</style>

<?php require_once 'includes/footer.php'; ?>
