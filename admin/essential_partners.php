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

// Fetch all partners
$partners = $pdo->query("SELECT * FROM essential_partners ORDER BY category, country_name")->fetchAll();
$all_countries = $pdo->query("SELECT name FROM countries WHERE is_active = 1 ORDER BY name ASC")->fetchAll();
?>

<div class="content-wrapper">
    <div class="page-header">
        <h2 class="page-title">Essential Service <span>Partners</span></h2>
        <button class="btn-primary" onclick="showModal('addPartnerModal')">
            <i class="fa-solid fa-plus"></i> Add New Partner
        </button>
    </div>

    <?php if ($alertSuccess): ?>
        <div class="alert alert-success"><i class="fa-solid fa-circle-check"></i> <?= $alertSuccess ?></div>
    <?php endif; ?>
    <?php if ($alertError): ?>
        <div class="alert alert-danger"><i class="fa-solid fa-circle-exclamation"></i> <?= $alertError ?></div>
    <?php endif; ?>

    <div class="card">
        <div class="table-responsive">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Category</th>
                        <th>Country</th>
                        <th>Partner Name</th>
                        <th>Features</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($partners as $p): ?>
                        <tr>
                            <td><span class="badge badge--<?= $p['category'] ?>"><?= ucfirst($p['category']) ?></span></td>
                            <td><strong><?= clean_output($p['country_name']) ?></strong></td>
                            <td><?= clean_output($p['partner_name']) ?></td>
                            <td style="max-width: 300px; font-size: 0.85rem; color: #64748b;"><?= clean_output($p['features']) ?></td>
                            <td>
                                <div class="action-buttons">
                                    <button class="btn-icon btn-edit" title="Edit" onclick="editPartner(<?= htmlspecialchars(json_encode($p)) ?>)">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </button>
                                    <form method="POST" style="display:inline;" onsubmit="return confirm('Are you sure you want to delete this partner?')">
                                        <input type="hidden" name="action" value="delete_partner">
                                        <input type="hidden" name="partner_id" value="<?= $p['id'] ?>">
                                        <button class="btn-icon btn-delete" title="Delete">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Add Partner Modal -->
<div id="addPartnerModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Add New Service Partner</h3>
            <span class="close-modal" onclick="closeModal('addPartnerModal')">&times;</span>
        </div>
        <form method="POST">
            <input type="hidden" name="action" value="add_partner">
            <div class="form-group">
                <label>Category</label>
                <select name="category" class="form-control" required>
                    <option value="loan">Education Loan</option>
                    <option value="insurance">Health Insurance</option>
                    <option value="bank">Bank Account</option>
                    <option value="forex">Money Transfer / Forex</option>
                    <option value="sim">SIM Card</option>
                </select>
            </div>
            <div class="form-group">
                <label>Country (Select 'Global' for all)</label>
                <select name="country_name" class="form-control">
                    <option value="Global">Global</option>
                    <?php foreach ($all_countries as $c): ?>
                        <option value="<?= $c['name'] ?>"><?= $c['name'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label>Partner Name</label>
                <input type="text" name="partner_name" class="form-control" required placeholder="e.g. State Bank of India">
            </div>
            <div class="form-group">
                <label>Features (Comma separated)</label>
                <textarea name="features" class="form-control" rows="3" placeholder="Feature 1, Feature 2, Feature 3"></textarea>
            </div>
            <div class="form-group">
                <label>Application Link</label>
                <input type="text" name="link" class="form-control" value="consultation.php">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-outline" onclick="closeModal('addPartnerModal')">Cancel</button>
                <button type="submit" class="btn-primary">Save Partner</button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Partner Modal -->
<div id="editPartnerModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Edit Partner Details</h3>
            <span class="close-modal" onclick="closeModal('editPartnerModal')">&times;</span>
        </div>
        <form method="POST">
            <input type="hidden" name="action" value="update_partner">
            <input type="hidden" name="partner_id" id="edit_partner_id">
            <div class="form-group">
                <label>Category</label>
                <select name="category" id="edit_category" class="form-control" required>
                    <option value="loan">Education Loan</option>
                    <option value="insurance">Health Insurance</option>
                    <option value="bank">Bank Account</option>
                    <option value="forex">Money Transfer / Forex</option>
                    <option value="sim">SIM Card</option>
                </select>
            </div>
            <div class="form-group">
                <label>Country</label>
                <select name="country_name" id="edit_country" class="form-control">
                    <option value="Global">Global</option>
                    <?php foreach ($all_countries as $c): ?>
                        <option value="<?= $c['name'] ?>"><?= $c['name'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label>Partner Name</label>
                <input type="text" name="partner_name" id="edit_name" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Features</label>
                <textarea name="features" id="edit_features" class="form-control" rows="3"></textarea>
            </div>
            <div class="form-group">
                <label>Application Link</label>
                <input type="text" name="link" id="edit_link" class="form-control">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-outline" onclick="closeModal('editPartnerModal')">Cancel</button>
                <button type="submit" class="btn-primary">Update Changes</button>
            </div>
        </form>
    </div>
</div>

<script>
function showModal(id) {
    document.getElementById(id).style.display = 'flex';
}
function closeModal(id) {
    document.getElementById(id).style.display = 'none';
}
function editPartner(data) {
    document.getElementById('edit_partner_id').value = data.id;
    document.getElementById('edit_category').value = data.category;
    document.getElementById('edit_country').value = data.country_name;
    document.getElementById('edit_name').value = data.partner_name;
    document.getElementById('edit_features').value = data.features;
    document.getElementById('edit_link').value = data.link;
    showModal('editPartnerModal');
}
</script>

<style>
.badge {
    padding: 0.25rem 0.6rem;
    border-radius: 50px;
    font-size: 0.75rem;
    font-weight: 700;
    text-transform: uppercase;
}
.badge--loan { background: #dcfce7; color: #166534; }
.badge--insurance { background: #dbeafe; color: #1e40af; }
.badge--bank { background: #fef9c3; color: #854d0e; }
.badge--forex { background: #f3e8ff; color: #6b21a8; }
.badge--sim { background: #fee2e2; color: #991b1b; }
</style>

<?php require_once 'includes/footer.php'; ?>
