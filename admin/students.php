<?php
// admin/students.php - Students CRUD Editor
$pageTitle = 'Students Manager';
require_once __DIR__ . '/includes/header.php';

$alertSuccess = '';
$alertError = '';
if (empty($_SESSION['student_crm_link_csrf'])) {
    $_SESSION['student_crm_link_csrf'] = bin2hex(random_bytes(32));
}

// --- HANDLE POST REQUESTS (ADD, UPDATE, DELETE) ---
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = isset($_POST['action']) ? trim($_POST['action']) : '';
    
    // 1. ADD NEW STUDENT
    if ($action === 'add') {
        $first_name = isset($_POST['first_name']) ? trim($_POST['first_name']) : '';
        $last_name = isset($_POST['last_name']) ? trim($_POST['last_name']) : '';
        $email = isset($_POST['email']) ? trim($_POST['email']) : '';
        $phone = isset($_POST['phone']) ? trim($_POST['phone']) : '';
        $dob = isset($_POST['dob']) ? trim($_POST['dob']) : null;
        if (empty($dob)) $dob = null;
        $password = isset($_POST['password']) ? $_POST['password'] : '';
        
        if (empty($first_name) || empty($last_name) || empty($email) || empty($password)) {
            $alertError = 'First Name, Last Name, Email, and Password are required.';
        } else {
            try {
                // Check if email exists
                $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
                $stmt->execute([$email]);
                if ($stmt->fetch()) {
                    $alertError = "Email is already registered.";
                } else {
                    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
                    $stmt = $pdo->prepare("
                        INSERT INTO users (first_name, last_name, email, phone, dob, password, role) 
                        VALUES (:first_name, :last_name, :email, :phone, :dob, :password, 'student')
                    ");
                    $stmt->execute([
                        'first_name' => $first_name,
                        'last_name' => $last_name,
                        'email' => $email,
                        'phone' => $phone,
                        'dob' => $dob,
                        'password' => $hashedPassword
                    ]);
                    $alertSuccess = 'Student added successfully!';
                }
            } catch (PDOException $e) {
                $alertError = 'Failed to add student: ' . $e->getMessage();
            }
        }
    }
    
    // 2. UPDATE STUDENT
    elseif ($action === 'update') {
        $id = isset($_POST['user_id']) ? intval($_POST['user_id']) : 0;
        $first_name = isset($_POST['first_name']) ? trim($_POST['first_name']) : '';
        $last_name = isset($_POST['last_name']) ? trim($_POST['last_name']) : '';
        $email = isset($_POST['email']) ? trim($_POST['email']) : '';
        $phone = isset($_POST['phone']) ? trim($_POST['phone']) : '';
        $dob = isset($_POST['dob']) ? trim($_POST['dob']) : null;
        if (empty($dob)) $dob = null;
        
        if ($id <= 0 || empty($first_name) || empty($last_name) || empty($email)) {
            $alertError = 'First Name, Last Name, and Email are required.';
        } else {
            try {
                $stmt = $pdo->prepare("
                    UPDATE users 
                    SET first_name = :first_name, last_name = :last_name, email = :email, phone = :phone, dob = :dob 
                    WHERE id = :id AND role = 'student'
                ");
                $stmt->execute([
                    'first_name' => $first_name,
                    'last_name' => $last_name,
                    'email' => $email,
                    'phone' => $phone,
                    'dob' => $dob,
                    'id' => $id
                ]);
                $alertSuccess = 'Student updated successfully!';
            } catch (PDOException $e) {
                $alertError = 'Failed to update student: ' . $e->getMessage();
            }
        }
    }
    
    // 3. DELETE STUDENT
    elseif ($action === 'delete') {
        $id = isset($_POST['user_id']) ? intval($_POST['user_id']) : 0;
        if ($id <= 0) {
            $alertError = 'Invalid student ID specified for deletion.';
        } else {
            try {
                $pdo->beginTransaction();
                $pdo->prepare('DELETE FROM student_crm_links WHERE student_user_id = :id')->execute(['id' => $id]);
                $stmt = $pdo->prepare("DELETE FROM users WHERE id = :id AND role = 'student'");
                $stmt->execute(['id' => $id]);
                $pdo->commit();
                $alertSuccess = 'Student deleted permanently!';
            } catch (PDOException $e) {
                if ($pdo->inTransaction()) {
                    $pdo->rollBack();
                }
                $alertError = 'Failed to delete student: ' . $e->getMessage();
            }
        }
    }
}

// Fetch all students
$students = [];
try {
    $countQuery = $pdo->query("SELECT COUNT(*) FROM users WHERE role = 'student'");
    $totalCount = intval($countQuery->fetchColumn());
    
    $pagination = get_pagination_params($totalCount, 20);
    $limit = $pagination['limit'];
    $offset = $pagination['offset'];
    $page = $pagination['page'];
    $totalPages = $pagination['totalPages'];
    
    $stmt = $pdo->prepare("
        SELECT u.*, l.crm_lead_id
        FROM users u
        LEFT JOIN student_crm_links l ON l.student_user_id = u.id
        WHERE u.role = 'student'
        ORDER BY u.created_at DESC
        LIMIT :limit OFFSET :offset
    ");
    $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();
    $students = $stmt->fetchAll();
} catch (PDOException $e) {
    $alertError = 'Could not fetch students: ' . $e->getMessage();
}
?>

<div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 2.5rem; flex-wrap: wrap; gap: 1rem;">
    <h1 class="page-title">
        Students Manager
        <span>Manage registered students and their details.</span>
    </h1>
    <button class="btn-pill" onclick="openAddModal()">
        <i class="fa-solid fa-plus"></i>
        <span>Add Student</span>
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

<div class="panel-card" style="margin-top: 1.5rem; overflow-x: auto;">
    <table class="data-table" style="width: 100%; border-collapse: collapse;">
        <thead>
            <tr>
                <th style="padding: 1rem; border-bottom: 1px solid var(--border); text-align: left;">ID</th>
                <th style="padding: 1rem; border-bottom: 1px solid var(--border); text-align: left;">Name</th>
                <th style="padding: 1rem; border-bottom: 1px solid var(--border); text-align: left;">Email</th>
                <th style="padding: 1rem; border-bottom: 1px solid var(--border); text-align: left;">Phone</th>
                <th style="padding: 1rem; border-bottom: 1px solid var(--border); text-align: left;">DOB</th>
                <th style="padding: 1rem; border-bottom: 1px solid var(--border); text-align: left;">Joined</th>
                <th style="padding: 1rem; border-bottom: 1px solid var(--border); text-align: left;">CRM Link</th>
                <th style="padding: 1rem; border-bottom: 1px solid var(--border); text-align: right;">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($students)): ?>
                <tr>
                    <td colspan="8" style="padding: 3rem; text-align: center; color: var(--text-secondary);">
                        <i class="fa-solid fa-user-graduate" style="font-size: 3rem; margin-bottom: 1rem; color: var(--text-muted); display:block;"></i>
                        No students found.
                    </td>
                </tr>
            <?php else: ?>
                <?php foreach ($students as $s): ?>
                    <tr style="border-bottom: 1px solid var(--border);">
                        <td style="padding: 1rem; color: var(--text-muted);">#<?php echo $s['id']; ?></td>
                        <td style="padding: 1rem; font-weight: 600; color: var(--text-primary);"><?php echo clean_output($s['first_name'] . ' ' . $s['last_name']); ?></td>
                        <td style="padding: 1rem; color: var(--text-secondary);"><?php echo clean_output($s['email']); ?></td>
                        <td style="padding: 1rem; color: var(--text-secondary);"><?php echo clean_output($s['phone']); ?></td>
                        <td style="padding: 1rem; color: var(--text-secondary);"><?php echo !empty($s['dob']) ? date('M d, Y', strtotime($s['dob'])) : '-'; ?></td>
                        <td style="padding: 1rem; color: var(--text-secondary);"><?php echo date('M d, Y', strtotime($s['created_at'])); ?></td>
                        <td style="padding: 1rem; color: var(--text-secondary);">
                            <?php if (!empty($s['crm_lead_id'])): ?>
                                <span style="display:block; font-weight:600; color:var(--success);">Central CRM #<?php echo (int) $s['crm_lead_id']; ?></span>
                                <span style="font-size:0.8rem;">Linked record</span>
                            <?php else: ?>
                                <span style="font-size:0.85rem; color:var(--text-muted);">Not linked</span>
                            <?php endif; ?>
                            <button type="button" class="btn-outline" style="margin-top:0.4rem; padding:0.35rem 0.6rem;" onclick="openCrmLinkModal(<?php echo (int) $s['id']; ?>, <?php echo htmlspecialchars(json_encode($s['first_name'] . ' ' . $s['last_name']), ENT_QUOTES, 'UTF-8'); ?>, <?php echo (int) ($s['crm_lead_id'] ?? 0); ?>)">
                                <?php echo !empty($s['crm_lead_id']) ? 'Change link' : 'Link CRM'; ?>
                            </button>
                        </td>
                        <td style="padding: 1rem; text-align: right;">
                            <button class="btn-action action-edit" style="background:#eef2ff; color:var(--primary); padding: 0.5rem; border-radius: 6px; border:none; margin-right: 0.5rem; cursor:pointer;" onclick="openEditModal(<?php echo htmlspecialchars(json_encode($s)); ?>)">
                                <i class="fa-solid fa-pen-to-square"></i>
                            </button>
                            <button class="btn-action action-delete" style="background:#fef2f2; color:var(--danger); padding: 0.5rem; border-radius: 6px; border:none; cursor:pointer;" onclick="triggerDelete(<?php echo $s['id']; ?>, '<?php echo addslashes(clean_output($s['first_name'] . ' ' . $s['last_name'])); ?>')">
                                <i class="fa-solid fa-trash-can"></i>
                            </button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php echo render_pagination_buttons($page, $totalPages); ?>

<!-- 1. ADD / EDIT DIALOG MODAL -->
<div class="modal-overlay" id="studentModal">
    <div class="modal-container" style="max-width: 500px;">
        <div class="modal-header">
            <h3 class="modal-title" id="modalTitle">Add Student</h3>
            <span class="modal-close" onclick="closeStudentModal()">&times;</span>
        </div>
        <form action="students.php" method="POST">
            <div class="modal-body">
                <input type="hidden" name="action" id="modalAction" value="add">
                <input type="hidden" name="user_id" id="edit_user_id">
                
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                    <div class="form-group">
                        <label for="s_first_name" class="form-label">First Name *</label>
                        <input type="text" name="first_name" id="s_first_name" class="form-control" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="s_last_name" class="form-label">Last Name *</label>
                        <input type="text" name="last_name" id="s_last_name" class="form-control" required>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="s_email" class="form-label">Email *</label>
                    <input type="email" name="email" id="s_email" class="form-control" required>
                </div>
                
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                    <div class="form-group">
                        <label for="s_phone" class="form-label">Phone</label>
                        <input type="tel" name="phone" id="s_phone" class="form-control">
                    </div>
                    
                    <div class="form-group">
                        <label for="s_dob" class="form-label">Date of Birth</label>
                        <input type="date" name="dob" id="s_dob" class="form-control">
                    </div>
                </div>
                
                <div class="form-group" id="password_group">
                    <label for="s_password" class="form-label">Password * (Only required for new students)</label>
                    <input type="password" name="password" id="s_password" class="form-control">
                </div>
            </div>
            
            <div class="modal-footer">
                <button type="button" class="btn-outline" onclick="closeStudentModal()">Cancel</button>
                <button type="submit" class="btn-pill">
                    <i class="fa-solid fa-floppy-disk"></i>
                    <span>Save Student</span>
                </button>
            </div>
        </form>
    </div>
</div>

<!-- 2. DELETE CONFIRMATION MODAL -->
<div class="modal-overlay" id="deleteModal">
    <div class="modal-container" style="max-width: 400px;">
        <div class="modal-header" style="border-bottom: none; padding-bottom: 0;">
            <h3 class="modal-title" style="color: var(--danger);"><i class="fa-solid fa-triangle-exclamation"></i> Delete Student?</h3>
            <span class="modal-close" onclick="closeDeleteModal()">&times;</span>
        </div>
        <form action="students.php" method="POST">
            <div class="modal-body" style="padding-top: 1rem;">
                <input type="hidden" name="action" value="delete">
                <input type="hidden" name="user_id" id="deleteUserId">
                <p style="font-size: 0.95rem; line-height: 1.6; color: var(--text-secondary);">
                    Are you sure you want to permanently delete <strong id="deleteUserName" style="color: var(--text-primary);">Name</strong>?
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

<!-- CRM account linking dialog -->
<div class="modal-overlay" id="crmLinkModal">
    <div class="modal-container" style="max-width: 650px;">
        <div class="modal-header">
            <h3 class="modal-title">Link CRM application</h3>
            <span class="modal-close" onclick="closeCrmLinkModal()">&times;</span>
        </div>
        <div class="modal-body">
            <p style="margin-bottom:1rem; color:var(--text-secondary);">
                Select the correct CRM record for <strong id="crmLinkStudentName"></strong>. Confirm the student's name, email, and phone before linking.
            </p>
            <input type="hidden" id="crmLinkUserId">
            <input type="hidden" id="crmLinkCurrentLeadId">
            <input type="search" id="crmLeadSearch" class="form-control" placeholder="Search CRM by name, email, phone, or lead code">
            <div id="crmLinkFeedback" role="status" style="margin-top:0.75rem;"></div>
            <div id="crmLeadResults" style="max-height:320px; overflow:auto; margin-top:0.75rem;"></div>
            <button type="button" class="btn-outline" id="crmUnlinkButton" style="display:none; margin-top:0.75rem;" onclick="unlinkCrmLead()">Unlink current CRM record</button>
        </div>
    </div>
</div>

<script>
const studentCrmLinkCsrf = <?php echo json_encode($_SESSION['student_crm_link_csrf']); ?>;

function openAddModal() {
    document.getElementById('modalAction').value = 'add';
    document.getElementById('modalTitle').innerText = 'Add Student';
    document.getElementById('edit_user_id').value = '';
    document.getElementById('s_first_name').value = '';
    document.getElementById('s_last_name').value = '';
    document.getElementById('s_email').value = '';
    document.getElementById('s_phone').value = '';
    document.getElementById('s_dob').value = '';
    document.getElementById('s_password').required = true;
    document.getElementById('password_group').style.display = 'block';
    
    document.getElementById('studentModal').classList.add('active');
}

function openEditModal(s) {
    document.getElementById('modalAction').value = 'update';
    document.getElementById('modalTitle').innerText = 'Edit Student';
    document.getElementById('edit_user_id').value = s.id;
    document.getElementById('s_first_name').value = s.first_name;
    document.getElementById('s_last_name').value = s.last_name;
    document.getElementById('s_email').value = s.email;
    document.getElementById('s_phone').value = s.phone || '';
    document.getElementById('s_dob').value = s.dob || '';
    document.getElementById('s_password').required = false;
    document.getElementById('password_group').style.display = 'none'; // Don't allow password editing here for simplicity
    
    document.getElementById('studentModal').classList.add('active');
}

function closeStudentModal() {
    document.getElementById('studentModal').classList.remove('active');
}

function triggerDelete(id, name) {
    document.getElementById('deleteUserId').value = id;
    document.getElementById('deleteUserName').innerText = name;
    document.getElementById('deleteModal').classList.add('active');
}

function closeDeleteModal() {
    document.getElementById('deleteModal').classList.remove('active');
}

let crmSearchTimer;

function openCrmLinkModal(userId, studentName, currentLeadId) {
    document.getElementById('crmLinkUserId').value = userId;
    document.getElementById('crmLinkCurrentLeadId').value = currentLeadId || '';
    document.getElementById('crmLinkStudentName').textContent = studentName;
    document.getElementById('crmLeadSearch').value = '';
    document.getElementById('crmLeadResults').replaceChildren();
    document.getElementById('crmLinkFeedback').textContent = 'Search CRM records, then choose the verified record.';
    document.getElementById('crmUnlinkButton').style.display = currentLeadId ? 'inline-block' : 'none';
    document.getElementById('crmLinkModal').classList.add('active');
    document.getElementById('crmLeadSearch').focus();
}

function closeCrmLinkModal() {
    document.getElementById('crmLinkModal').classList.remove('active');
}

function renderCrmLeadResults(leads) {
    const results = document.getElementById('crmLeadResults');
    results.replaceChildren();
    if (!leads.length) {
        results.textContent = 'No matching active CRM records found.';
        return;
    }

    leads.forEach(lead => {
        const row = document.createElement('div');
        row.style.cssText = 'display:flex;justify-content:space-between;align-items:center;gap:1rem;padding:0.75rem;border-bottom:1px solid var(--border);';
        const details = document.createElement('div');
        const title = document.createElement('strong');
        title.textContent = `${lead.student_name || 'Unknown'} (${lead.lead_code || `#${lead.id}`})`;
        const meta = document.createElement('div');
        meta.style.cssText = 'font-size:0.85rem;color:var(--text-secondary);margin-top:0.2rem;';
        meta.textContent = [
            lead.domain,
            lead.email,
            lead.phone,
            lead.interested_in,
            'Application: ' + (lead.primary_status || lead.status || 'New')
        ].filter(Boolean).join(' · ');
        details.append(title, meta);

        const choose = document.createElement('button');
        choose.type = 'button';
        choose.className = 'btn-pill';
        const alreadyLinkedToThisStudent = Number(lead.id) === Number(document.getElementById('crmLinkCurrentLeadId').value);
        const linkedToAnotherStudent = lead.linked_user_id && Number(lead.linked_user_id) !== Number(document.getElementById('crmLinkUserId').value);
        choose.textContent = alreadyLinkedToThisStudent ? 'Linked' : (linkedToAnotherStudent ? 'Linked to another account' : 'Link');
        choose.disabled = alreadyLinkedToThisStudent || linkedToAnotherStudent;
        choose.addEventListener('click', () => linkCrmLead(lead.id));
        row.append(details, choose);
        results.append(row);
    });
}

async function searchCrmLeads() {
    const query = document.getElementById('crmLeadSearch').value.trim();
    const feedback = document.getElementById('crmLinkFeedback');
    const results = document.getElementById('crmLeadResults');
    results.replaceChildren();
    if (query.length < 2) {
        feedback.textContent = 'Enter at least 2 characters to search.';
        return;
    }
    feedback.textContent = 'Searching CRM...';
    try {
        const response = await fetch(`student_crm_link.php?search=${encodeURIComponent(query)}`, { credentials: 'same-origin' });
        const result = await response.json();
        if (!response.ok || !result.success) throw new Error(result.message || 'CRM search failed.');
        renderCrmLeadResults(result.leads || []);
        feedback.textContent = '';
    } catch (error) {
        feedback.textContent = error.message || 'Unable to search CRM records.';
    }
}

async function saveCrmLeadLink(action, leadId) {
    const feedback = document.getElementById('crmLinkFeedback');
    feedback.textContent = action === 'link' ? 'Linking CRM record...' : 'Removing CRM link...';
    const body = new URLSearchParams({
        action,
        user_id: document.getElementById('crmLinkUserId').value,
        lead_id: leadId,
        csrf_token: studentCrmLinkCsrf
    });
    try {
        const response = await fetch('student_crm_link.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body,
            credentials: 'same-origin'
        });
        const result = await response.json();
        if (!response.ok || !result.success) throw new Error(result.message || 'Unable to update CRM link.');
        window.location.reload();
    } catch (error) {
        feedback.textContent = error.message || 'Unable to update CRM link.';
    }
}

function linkCrmLead(leadId) {
    saveCrmLeadLink('link', leadId);
}

function unlinkCrmLead() {
    const leadId = document.getElementById('crmLinkCurrentLeadId').value;
    if (leadId && window.confirm('Remove this CRM record from the student account?')) {
        saveCrmLeadLink('unlink', leadId);
    }
}

document.getElementById('crmLeadSearch').addEventListener('input', () => {
    window.clearTimeout(crmSearchTimer);
    crmSearchTimer = window.setTimeout(searchCrmLeads, 250);
});
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
