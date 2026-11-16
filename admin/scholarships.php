<?php
// admin/scholarships.php - Global Scholarships Manager
$pageTitle = 'Global Scholarships';
require_once __DIR__ . '/includes/header.php';

$alertSuccess = '';
$alertError = '';

// Handle POST operations
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = trim($_POST['action'] ?? '');
    
    if ($action === 'add') {
        $name = trim($_POST['name'] ?? '');
        $uniId = intval($_POST['university_id'] ?? 0);
        $amount = trim($_POST['amount'] ?? '');
        $eligibility = trim($_POST['eligibility'] ?? '');
        $deadline = trim($_POST['deadline'] ?? '');
        $isActive = isset($_POST['is_active']) ? 1 : 0;
        
        if (empty($name) || $uniId <= 0) {
            $alertError = 'Scholarship name and university are required.';
        } else {
            try {
                $stmt = $pdo->prepare("
                    INSERT INTO scholarships (university_id, name, amount, eligibility, deadline, is_active)
                    VALUES (?, ?, ?, ?, ?, ?)
                ");
                $stmt->execute([$uniId, $name, $amount, $eligibility, $deadline, $isActive]);
                $alertSuccess = "Scholarship '{$name}' added successfully!";
            } catch (PDOException $e) {
                $alertError = "Error adding scholarship: " . $e->getMessage();
            }
        }
    } elseif ($action === 'edit') {
        $id = intval($_POST['scholarship_id'] ?? 0);
        $name = trim($_POST['name'] ?? '');
        $uniId = intval($_POST['university_id'] ?? 0);
        $amount = trim($_POST['amount'] ?? '');
        $eligibility = trim($_POST['eligibility'] ?? '');
        $deadline = trim($_POST['deadline'] ?? '');
        
        if (empty($name) || $uniId <= 0 || $id <= 0) {
            $alertError = 'Scholarship name, university, and ID are required.';
        } else {
            try {
                $stmt = $pdo->prepare("
                    UPDATE scholarships 
                    SET university_id = ?, name = ?, amount = ?, eligibility = ?, deadline = ?
                    WHERE id = ?
                ");
                $stmt->execute([$uniId, $name, $amount, $eligibility, $deadline, $id]);
                $alertSuccess = "Scholarship '{$name}' updated successfully!";
            } catch (PDOException $e) {
                $alertError = "Error updating scholarship: " . $e->getMessage();
            }
        }
    } elseif ($action === 'delete') {
        $id = intval($_POST['scholarship_id'] ?? 0);
        if ($id > 0) {
            try {
                $stmt = $pdo->prepare("DELETE FROM scholarships WHERE id = ?");
                $stmt->execute([$id]);
                $alertSuccess = "Scholarship deleted successfully.";
            } catch (PDOException $e) {
                $alertError = "Error deleting scholarship: " . $e->getMessage();
            }
        }
    }
}

// Fetch Universities for select dropdown
$universities = [];
try {
    $universities = $pdo->query("SELECT id, name FROM universities WHERE is_active = 1 ORDER BY name ASC")->fetchAll();
} catch (PDOException $e) {}

// Filters & Pagination
$search = trim($_GET['q'] ?? '');
$uniFilter = intval($_GET['university_id'] ?? 0);

$page = max(1, intval($_GET['page'] ?? 1));
$limit = 20;
$offset = ($page - 1) * $limit;

$where = [];
$params = [];

if ($uniFilter > 0) {
    $where[] = "s.university_id = ?";
    $params[] = $uniFilter;
}

if (!empty($search)) {
    $where[] = "(s.name LIKE ? OR u.name LIKE ? OR s.amount LIKE ?)";
    $like = "%{$search}%";
    $params[] = $like;
    $params[] = $like;
    $params[] = $like;
}

$whereSql = !empty($where) ? "WHERE " . implode(" AND ", $where) : "";

$countStmt = $pdo->prepare("
    SELECT COUNT(*) 
    FROM scholarships s 
    JOIN universities u ON s.university_id = u.id 
    {$whereSql}
");
$countStmt->execute($params);
$totalSchols = (int) $countStmt->fetchColumn();
$totalPages = ceil($totalSchols / $limit);

$stmt = $pdo->prepare("
    SELECT s.*, u.name as university_name, c.name as country_name 
    FROM scholarships s 
    JOIN universities u ON s.university_id = u.id 
    LEFT JOIN countries c ON u.country_id = c.id 
    {$whereSql} 
    ORDER BY s.id DESC 
    LIMIT {$limit} OFFSET {$offset}
");
$stmt->execute($params);
$scholarshipsList = $stmt->fetchAll(PDO::FETCH_ASSOC);

$totalActive = 0;
try {
    $totalActive = (int) $pdo->query("SELECT COUNT(*) FROM scholarships WHERE is_active = 1")->fetchColumn();
} catch (PDOException $e) {}
?>

<div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 2rem; flex-wrap: wrap; gap: 1rem;">
    <div>
        <h1 class="page-title" style="margin-bottom: 0.25rem;">
            Global Scholarships Manager
        </h1>
        <p style="color: var(--text-secondary); font-size: 0.9rem; margin: 0;">
            Manage tuition waivers, merit grants, and financial awards displayed in the Mobile App & Website.
        </p>
    </div>
    <div style="display: flex; gap: 0.5rem;">
        <a href="app_masters.php?tab=hero" class="btn-outline">
            <i class="fa-solid fa-sliders"></i>
            <span>Banner Config</span>
        </a>
        <button type="button" class="btn-pill" onclick="openAddScholarshipModal()">
            <i class="fa-solid fa-plus"></i>
            <span>Add Scholarship</span>
        </button>
    </div>
</div>

<?php if ($alertSuccess): ?>
    <div class="alert alert-success" style="padding: 1rem; border-radius: var(--radius-sm); margin-bottom: 1.5rem; background: #ecfdf5; border: 1px solid #a7f3d0; color: #065f46;">
        <i class="fa-solid fa-circle-check"></i> <?php echo clean_output($alertSuccess); ?>
    </div>
<?php endif; ?>

<?php if ($alertError): ?>
    <div class="alert alert-danger" style="padding: 1rem; border-radius: var(--radius-sm); margin-bottom: 1.5rem; background: #fef2f2; border: 1px solid #fecaca; color: #991b1b;">
        <i class="fa-solid fa-triangle-exclamation"></i> <?php echo clean_output($alertError); ?>
    </div>
<?php endif; ?>

<!-- Filter & Search Bar -->
<div class="panel-card" style="margin-bottom: 1.5rem; padding: 1rem 1.25rem;">
    <form method="GET" action="scholarships.php" style="display: flex; gap: 0.75rem; flex-wrap: wrap; align-items: center;">
        <div style="flex: 1; min-width: 220px;">
            <input type="text" name="q" value="<?php echo clean_output($search); ?>" placeholder="Search scholarship title, amount, university..." class="form-control" style="font-size: 0.85rem;">
        </div>
        <div style="min-width: 200px;">
            <select name="university_id" class="form-control" style="font-size: 0.85rem;">
                <option value="">All Universities</option>
                <?php foreach ($universities as $u): ?>
                    <option value="<?php echo $u['id']; ?>" <?php echo $uniFilter === (int) $u['id'] ? 'selected' : ''; ?>>
                        <?php echo clean_output($u['name']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <button type="submit" class="btn-pill" style="padding: 0.5rem 1.2rem; font-size: 0.85rem;">
            <i class="fa-solid fa-magnifying-glass"></i> Filter
        </button>
        <?php if (!empty($search) || $uniFilter > 0): ?>
            <a href="scholarships.php" class="btn-outline" style="padding: 0.5rem 1rem; font-size: 0.85rem;">Clear</a>
        <?php endif; ?>
    </form>
</div>

<!-- Scholarships Data Table -->
<div class="panel-card" style="overflow-x: auto;">
    <table class="data-table" style="width: 100%; border-collapse: collapse;">
        <thead>
            <tr style="border-bottom: 1px solid var(--border); text-align: left;">
                <th style="padding: 1rem;">ID</th>
                <th style="padding: 1rem;">Scholarship Name</th>
                <th style="padding: 1rem;">University & Country</th>
                <th style="padding: 1rem;">Award / Amount</th>
                <th style="padding: 1rem;">Eligibility</th>
                <th style="padding: 1rem;">Deadline</th>
                <th style="padding: 1rem;">Status</th>
                <th style="padding: 1rem; text-align: right;">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($scholarshipsList)): ?>
                <tr>
                    <td colspan="8" style="padding: 3rem; text-align: center; color: var(--text-muted);">
                        <i class="fa-solid fa-award" style="font-size: 2.5rem; display: block; margin-bottom: 0.75rem;"></i>
                        No scholarships found.
                    </td>
                </tr>
            <?php else: ?>
                <?php foreach ($scholarshipsList as $s): ?>
                    <tr style="border-bottom: 1px solid var(--border);">
                        <td style="padding: 1rem; color: var(--text-muted);">#<?php echo $s['id']; ?></td>
                        <td style="padding: 1rem; font-weight: 600; color: var(--text-primary);">
                            <?php echo clean_output($s['name']); ?>
                        </td>
                        <td style="padding: 1rem;">
                            <strong style="color: var(--text-primary); display: block;"><?php echo clean_output($s['university_name']); ?></strong>
                            <span style="font-size: 0.8rem; color: var(--text-muted);"><?php echo clean_output($s['country_name'] ?? ''); ?></span>
                        </td>
                        <td style="padding: 1rem;">
                            <span style="display: inline-block; padding: 3px 8px; border-radius: 6px; background: #fef3c7; color: #92400e; font-weight: 600; font-size: 0.85rem;">
                                <?php echo clean_output($s['amount'] ?: 'Full / Partial'); ?>
                            </span>
                        </td>
                        <td style="padding: 1rem; font-size: 0.85rem; color: var(--text-secondary); max-width: 250px;">
                            <?php echo clean_output($s['eligibility'] ?: 'Academic merit'); ?>
                        </td>
                        <td style="padding: 1rem; font-size: 0.85rem; color: var(--text-secondary);">
                            <?php echo clean_output($s['deadline'] ?: 'Rolling / Intake dependent'); ?>
                        </td>
                        <td style="padding: 1rem;">
                            <?php echo render_admin_active_toggle('scholarships', $s['id'], intval($s['is_active']) === 1, $s['name']); ?>
                        </td>
                        <td style="padding: 1rem; text-align: right; white-space: nowrap;">
                            <button type="button" class="btn-action" style="width: 32px; height: 32px; background: #eef2ff; color: var(--primary); border-radius: 6px; border: none; cursor: pointer; margin-right: 4px;" title="Edit" onclick="openEditScholarshipModal(<?php echo htmlspecialchars(json_encode($s)); ?>)">
                                <i class="fa-solid fa-pen-to-square"></i>
                            </button>
                            <form method="POST" action="scholarships.php" style="display: inline;" onsubmit="return confirm('Delete this scholarship permanently?');">
                                <input type="hidden" name="action" value="delete">
                                <input type="hidden" name="scholarship_id" value="<?php echo $s['id']; ?>">
                                <button type="submit" class="btn-action" style="width: 32px; height: 32px; background: #fee2e2; color: #dc2626; border-radius: 6px; border: none; cursor: pointer;" title="Delete">
                                    <i class="fa-solid fa-trash-can"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<!-- ADD / EDIT SCHOLARSHIP MODAL -->
<div class="modal-overlay" id="scholarshipModal">
    <div class="modal-container" style="max-width: 550px;">
        <div class="modal-header">
            <h3 class="modal-title" id="scholModalTitle">Add Scholarship</h3>
            <span class="modal-close" onclick="closeScholarshipModal()">&times;</span>
        </div>
        <form action="scholarships.php" method="POST">
            <div class="modal-body">
                <input type="hidden" name="action" id="scholAction" value="add">
                <input type="hidden" name="scholarship_id" id="editScholId">

                <div class="form-group" style="margin-bottom: 1rem;">
                    <label class="form-label" style="font-weight: 600;">Scholarship Title *</label>
                    <input type="text" name="name" id="scholName" class="form-control" required placeholder="e.g. Vice-Chancellor International Excellence Award">
                </div>

                <div class="form-group" style="margin-bottom: 1rem;">
                    <label class="form-label" style="font-weight: 600;">University *</label>
                    <select name="university_id" id="scholUniId" class="form-control" required>
                        <option value="">Select University</option>
                        <?php foreach ($universities as $u): ?>
                            <option value="<?php echo $u['id']; ?>"><?php echo clean_output($u['name']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1rem;">
                    <div class="form-group">
                        <label class="form-label" style="font-weight: 600;">Award Amount / Waiver</label>
                        <input type="text" name="amount" id="scholAmount" class="form-control" placeholder="e.g. £5,000 or 50% tuition waiver">
                    </div>
                    <div class="form-group">
                        <label class="form-label" style="font-weight: 600;">Application Deadline</label>
                        <input type="text" name="deadline" id="scholDeadline" class="form-control" placeholder="e.g. 30 June 2026 or Rolling">
                    </div>
                </div>

                <div class="form-group" style="margin-bottom: 1rem;">
                    <label class="form-label" style="font-weight: 600;">Eligibility Criteria</label>
                    <textarea name="eligibility" id="scholEligibility" class="form-control" rows="3" placeholder="e.g. Minimum 65% in undergraduate degree; IELTS 6.5 minimum."></textarea>
                </div>

                <div class="form-group" id="activeCheckboxGroup">
                    <label style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer;">
                        <input type="checkbox" name="is_active" id="scholIsActive" value="1" checked>
                        <span>Active (Visible in Mobile App & Website)</span>
                    </label>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn-outline" onclick="closeScholarshipModal()">Cancel</button>
                <button type="submit" class="btn-pill">
                    <i class="fa-solid fa-floppy-disk"></i>
                    <span>Save Scholarship</span>
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function openAddScholarshipModal() {
    document.getElementById('scholAction').value = 'add';
    document.getElementById('scholModalTitle').innerText = 'Add Scholarship';
    document.getElementById('editScholId').value = '';
    document.getElementById('scholName').value = '';
    document.getElementById('scholUniId').value = '';
    document.getElementById('scholAmount').value = '';
    document.getElementById('scholDeadline').value = '';
    document.getElementById('scholEligibility').value = '';
    document.getElementById('activeCheckboxGroup').style.display = 'block';
    document.getElementById('scholIsActive').checked = true;
    document.getElementById('scholarshipModal').classList.add('active');
}

function openEditScholarshipModal(s) {
    document.getElementById('scholAction').value = 'edit';
    document.getElementById('scholModalTitle').innerText = 'Edit Scholarship';
    document.getElementById('editScholId').value = s.id;
    document.getElementById('scholName').value = s.name;
    document.getElementById('scholUniId').value = s.university_id;
    document.getElementById('scholAmount').value = s.amount || '';
    document.getElementById('scholDeadline').value = s.deadline || '';
    document.getElementById('scholEligibility').value = s.eligibility || '';
    document.getElementById('activeCheckboxGroup').style.display = 'none';
    document.getElementById('scholarshipModal').classList.add('active');
}

function closeScholarshipModal() {
    document.getElementById('scholarshipModal').classList.remove('active');
}
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>

