<?php
// admin/leads.php - CRM Lead and Enquiry Manager
$pageTitle = 'Leads CRM';
require_once 'includes/header.php'; // automatically validates sessions and loads PDO

$alertSuccess = '';
$alertError = '';

// --- HANDLE POST OPERATIONS (UPDATE OR DELETE) ---
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        $action = $_POST['action'];
        
        // 1. UPDATE LEAD DETAILS
        if ($action === 'update') {
            $leadId = isset($_POST['lead_id']) ? intval($_POST['lead_id']) : 0;
            $status = isset($_POST['status']) ? trim($_POST['status']) : '';
            $payment_status = isset($_POST['payment_status']) ? trim($_POST['payment_status']) : '';
            $total_fees = isset($_POST['total_fees']) ? floatval($_POST['total_fees']) : 0.00;
            $paid_amount = isset($_POST['paid_amount']) ? floatval($_POST['paid_amount']) : 0.00;
            $remarks = isset($_POST['remarks']) ? trim($_POST['remarks']) : '';
            $interested_in = isset($_POST['interested_in']) ? trim($_POST['interested_in']) : '';
            
            if ($leadId <= 0) {
                $alertError = 'Invalid Lead ID specified.';
            } else {
                try {
                    $stmt = $pdo->prepare("
                        UPDATE leads 
                        SET status = :status, 
                            payment_status = :payment_status, 
                            total_fees = :total_fees, 
                            paid_amount = :paid_amount, 
                            remarks = :remarks,
                            interested_in = :interested_in
                        WHERE id = :id
                    ");
                    $stmt->execute([
                        'status' => $status,
                        'payment_status' => $payment_status,
                        'total_fees' => $total_fees,
                        'paid_amount' => $paid_amount,
                        'remarks' => $remarks,
                        'interested_in' => $interested_in,
                        'id' => $leadId
                    ]);
                    $alertSuccess = 'Lead updated successfully!';
                } catch (PDOException $e) {
                    $alertError = 'Failed to update lead: ' . $e->getMessage();
                }
            }
        }
        
        // 2. DELETE LEAD (SOFT OR HARD DELETE)
        elseif ($action === 'delete') {
            $leadId = isset($_POST['lead_id']) ? intval($_POST['lead_id']) : 0;
            if ($leadId <= 0) {
                $alertError = 'Invalid Lead ID specified for deletion.';
            } else {
                try {
                    // Hard delete for simple admin console management
                    $stmt = $pdo->prepare("DELETE FROM leads WHERE id = :id");
                    $stmt->execute(['id' => $leadId]);
                    $alertSuccess = 'Lead deleted successfully!';
                } catch (PDOException $e) {
                    $alertError = 'Failed to delete lead: ' . $e->getMessage();
                }
            }
        }
    }
}

// --- CONFIGURATION FOR SEARCH, FILTER, AND PAGINATION ---
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$statusFilter = isset($_GET['status']) ? trim($_GET['status']) : '';
$sourceFilter = isset($_GET['source']) ? trim($_GET['source']) : '';

// Pagination
$limit = 10;
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
if ($page < 1) $page = 1;
$offset = ($page - 1) * $limit;

// SQL Builder
$whereClauses = ["is_active = 1"];
$params = [];

if (!empty($search)) {
    $whereClauses[] = "(student_name LIKE :search OR email LIKE :search2 OR phone LIKE :search3 OR lead_code LIKE :search4 OR interested_in LIKE :search5)";
    $params['search'] = "%$search%";
    $params['search2'] = "%$search%";
    $params['search3'] = "%$search%";
    $params['search4'] = "%$search%";
    $params['search5'] = "%$search%";
}

if (!empty($statusFilter)) {
    $whereClauses[] = "status = :statusFilter";
    $params['statusFilter'] = $statusFilter;
}

if (!empty($sourceFilter)) {
    $whereClauses[] = "source = :sourceFilter";
    $params['sourceFilter'] = $sourceFilter;
}

$whereSQL = implode(" AND ", $whereClauses);

try {
    // Get total rows count for pagination
    $countStmt = $pdo->prepare("SELECT COUNT(*) FROM leads WHERE $whereSQL");
    $countStmt->execute($params);
    $totalRows = $countStmt->fetchColumn();
    $totalPages = ceil($totalRows / $limit);
    if ($totalPages < 1) $totalPages = 1;
    if ($page > $totalPages) {
        $page = $totalPages;
        $offset = ($page - 1) * $limit;
    }
    
    // Get paginated leads
    $leadsStmt = $pdo->prepare("SELECT * FROM leads WHERE $whereSQL ORDER BY created_at DESC, id DESC LIMIT $limit OFFSET $offset");
    $leadsStmt->execute($params);
    $leads = $leadsStmt->fetchAll();
    
} catch (PDOException $e) {
    $leads = [];
    $totalRows = 0;
    $totalPages = 1;
    $alertError = 'Unable to fetch leads from database: ' . $e->getMessage() . '. Have you initialized tables inside the setup?';
}
?>

<div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 2.5rem;">
    <h1 class="page-title">
        Leads CRM Sheet
        <span>Manage, evaluate, and track student admissions & inquiry leads</span>
    </h1>
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

<!-- Search and Filter Bar -->
<form action="leads.php" method="GET" class="filter-bar">
    <div class="filter-group" style="flex: 1; min-width: 250px;">
        <label for="search">Search</label>
        <input type="text" name="search" id="search" value="<?php echo clean_output($search); ?>" placeholder="Search student name, email, phone, code..." class="filter-control" style="width: 100%;">
    </div>
    
    <div class="filter-group">
        <label for="status">Status</label>
        <select name="status" id="status" class="filter-control" onchange="this.form.submit()">
            <option value="">All Statuses</option>
            <option value="New" <?php echo $statusFilter === 'New' ? 'selected' : ''; ?>>New</option>
            <option value="Follow Up" <?php echo $statusFilter === 'Follow Up' ? 'selected' : ''; ?>>Follow Up</option>
            <option value="Waiting for Confirmation" <?php echo $statusFilter === 'Waiting for Confirmation' ? 'selected' : ''; ?>>Waiting for Confirmation</option>
            <option value="Enrolled" <?php echo $statusFilter === 'Enrolled' ? 'selected' : ''; ?>>Enrolled</option>
            <option value="Closed" <?php echo $statusFilter === 'Closed' ? 'selected' : ''; ?>>Closed</option>
            <option value="Invalid" <?php echo $statusFilter === 'Invalid' ? 'selected' : ''; ?>>Invalid</option>
            <option value="Dropped" <?php echo $statusFilter === 'Dropped' ? 'selected' : ''; ?>>Dropped</option>
        </select>
    </div>

    <div class="filter-group">
        <label for="source">Source</label>
        <select name="source" id="source" class="filter-control" onchange="this.form.submit()">
            <option value="">All Sources</option>
            <option value="Website Enquiry" <?php echo $sourceFilter === 'Website Enquiry' ? 'selected' : ''; ?>>Website Enquiry</option>
            <option value="Website Contact" <?php echo $sourceFilter === 'Website Contact' ? 'selected' : ''; ?>>Website Contact</option>
        </select>
    </div>
    
    <div style="display: flex; gap: 0.5rem; align-self: flex-end; margin-top: auto;">
        <button type="submit" class="btn-pill" style="padding: 0.5rem 1.25rem;">
            <i class="fa-solid fa-filter"></i>
            <span>Apply</span>
        </button>
        <?php if (!empty($search) || !empty($statusFilter) || !empty($sourceFilter)): ?>
            <a href="leads.php" class="btn-outline" style="padding: 0.5rem 1.25rem;">
                <i class="fa-solid fa-rotate"></i>
                <span>Reset</span>
            </a>
        <?php endif; ?>
    </div>
</form>

<!-- Leads Data Table Card -->
<div class="panel-card">
    <div class="data-table-wrapper">
        <?php if (empty($leads)): ?>
            <div style="text-align: center; padding: 4rem 2rem; color: var(--text-secondary);">
                <i class="fa-regular fa-folder-open" style="font-size: 3rem; margin-bottom: 1.5rem; display: block; color: var(--text-muted);"></i>
                <h3>No leads matched your criteria.</h3>
                <p style="margin-top: 0.5rem; font-size: 0.9rem;">Try adjusting your filters or search criteria.</p>
            </div>
        <?php else: ?>
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Code</th>
                        <th>Student Details</th>
                        <th>Interest</th>
                        <th>Financials</th>
                        <th>Status</th>
                        <th>Submitted At</th>
                        <th style="text-align: center;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($leads as $lead): 
                        $statusClass = 'badge-new';
                        $lbl = strtolower($lead['status'] ?? 'new');
                        if ($lbl === 'follow up') $statusClass = 'badge-follow';
                        elseif ($lbl === 'waiting for confirmation') $statusClass = 'badge-waiting';
                        elseif ($lbl === 'enrolled') $statusClass = 'badge-enrolled';
                        elseif ($lbl === 'closed') $statusClass = 'badge-closed';
                        elseif ($lbl === 'invalid') $statusClass = 'badge-invalid';
                        elseif ($lbl === 'dropped') $statusClass = 'badge-dropped';
                    ?>
                        <tr>
                            <td style="font-family: monospace; font-weight: 700; color: var(--accent);">
                                <?php echo clean_output($lead['lead_code']); ?>
                            </td>
                            <td>
                                <strong style="display: block; font-size: 1rem;"><?php echo clean_output($lead['student_name']); ?></strong>
                                <span style="font-size: 0.8rem; color: var(--text-secondary); display: block;"><?php echo clean_output($lead['email']); ?></span>
                                <span style="font-size: 0.78rem; color: var(--text-muted);"><?php echo clean_output($lead['phone']); ?></span>
                            </td>
                            <td>
                                <strong style="display: block; font-size: 0.88rem;"><?php echo clean_output($lead['interested_in'] ?: 'Overseas'); ?></strong>
                                <span style="font-size: 0.75rem; color: var(--text-muted);"><?php echo clean_output($lead['source']); ?></span>
                            </td>
                            <td>
                                <?php if (floatval($lead['total_fees']) > 0): ?>
                                    <div style="font-size: 0.85rem;">Total: <strong>₹<?php echo number_format($lead['total_fees'], 2); ?></strong></div>
                                    <div style="font-size: 0.78rem; color: var(--success);">Paid: <strong>₹<?php echo number_format($lead['paid_amount'], 2); ?></strong></div>
                                    <div style="font-size: 0.75rem; color: var(--text-muted);"><?php echo clean_output($lead['payment_status']); ?></div>
                                <?php else: ?>
                                    <span style="color: var(--text-muted); font-size: 0.85rem;">₹0.00 / Pending</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <span class="badge <?php echo $statusClass; ?>">
                                    <i class="fa-solid fa-circle"></i>
                                    <?php echo clean_output($lead['status']); ?>
                                </span>
                            </td>
                            <td style="font-size: 0.82rem; color: var(--text-secondary);">
                                <?php echo $lead['created_at'] ? date('M d, Y', strtotime($lead['created_at'])) . '<br><span style="font-size:0.75rem;color:var(--text-muted);">' . date('h:i A', strtotime($lead['created_at'])) . '</span>' : 'N/A'; ?>
                            </td>
                            <td style="text-align: center;">
                                <div style="display: inline-flex; gap: 0.35rem;">
                                    <button class="btn-action action-view" title="View & Edit Details" onclick="openLeadModal(<?php echo htmlspecialchars(json_encode($lead)); ?>)">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </button>
                                    <button class="btn-action action-delete" title="Delete Lead" onclick="triggerDeleteLead(<?php echo $lead['id']; ?>, '<?php echo clean_output($lead['student_name']); ?>', '<?php echo clean_output($lead['lead_code']); ?>')">
                                        <i class="fa-solid fa-trash-can"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</div>

<!-- Pagination container -->
<?php if ($totalPages > 1): ?>
    <div class="pagination">
        <!-- Previous Page -->
        <a href="leads.php?page=<?php echo $page - 1; ?>&search=<?php echo urlencode($search); ?>&status=<?php echo urlencode($statusFilter); ?>&source=<?php echo urlencode($sourceFilter); ?>" class="page-btn <?php echo $page === 1 ? 'disabled' : ''; ?>">
            <i class="fa-solid fa-angle-left"></i>
        </a>
        
        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
            <a href="leads.php?page=<?php echo $i; ?>&search=<?php echo urlencode($search); ?>&status=<?php echo urlencode($statusFilter); ?>&source=<?php echo urlencode($sourceFilter); ?>" class="page-btn <?php echo $page === $i ? 'active' : ''; ?>">
                <?php echo $i; ?>
            </a>
        <?php endfor; ?>
        
        <!-- Next Page -->
        <a href="leads.php?page=<?php echo $page + 1; ?>&search=<?php echo urlencode($search); ?>&status=<?php echo urlencode($statusFilter); ?>&source=<?php echo urlencode($sourceFilter); ?>" class="page-btn <?php echo $page === $totalPages ? 'disabled' : ''; ?>">
            <i class="fa-solid fa-angle-right"></i>
        </a>
    </div>
<?php endif; ?>

<!-- 1. VIEW / EDIT DETAILS MODAL -->
<div class="modal-overlay" id="leadModal">
    <div class="modal-container">
        <div class="modal-header">
            <h3 class="modal-title" id="modalLeadTitle">Student Admission File</h3>
            <span class="modal-close" onclick="closeLeadModal()">&times;</span>
        </div>
        <form action="leads.php" method="POST">
            <div class="modal-body">
                <input type="hidden" name="action" value="update">
                <input type="hidden" name="lead_id" id="modalLeadId">
                
                <div class="detail-grid" style="margin-bottom: 1.5rem; background: rgba(255,255,255,0.02); padding: 1.25rem; border-radius: var(--radius-sm); border: 1px solid var(--border);">
                    <div class="detail-item">
                        <div class="detail-label">Lead Code</div>
                        <div class="detail-value" id="modalLeadCode" style="font-family: monospace; font-weight: 700; color: var(--accent);">BGOI-0000</div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-label">Submission Date</div>
                        <div class="detail-value" id="modalLeadDate">Oct 24, 2024</div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-label">Student Name</div>
                        <div class="detail-value" id="modalStudentName" style="font-weight: 600;">Sai Raksha</div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-label">Contact Details</div>
                        <div class="detail-value" id="modalContactDetails">sai@email.com / +91 93428 99904</div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-label">Lead Source</div>
                        <div class="detail-value" id="modalLeadSource">Website Enquiry</div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-label">Lead Category</div>
                        <div class="detail-value" id="modalLeadCategory">Website Enquiry</div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="edit_interested_in" class="form-label">Interested Destination / Program</label>
                    <input type="text" name="interested_in" id="edit_interested_in" class="form-control" placeholder="e.g., Study in Canada">
                </div>

                <div class="form-group">
                    <label for="edit_status" class="form-label">Admissions Status</label>
                    <select name="status" id="edit_status" class="form-select">
                        <option value="New">New</option>
                        <option value="Follow Up">Follow Up</option>
                        <option value="Waiting for Confirmation">Waiting for Confirmation</option>
                        <option value="Enrolled">Enrolled</option>
                        <option value="Closed">Closed</option>
                        <option value="Invalid">Invalid</option>
                        <option value="Dropped">Dropped</option>
                    </select>
                </div>

                <div class="detail-grid" style="margin-bottom: 1.25rem;">
                    <div class="form-group">
                        <label for="edit_total_fees" class="form-label">Total Fees (₹)</label>
                        <input type="number" step="0.01" name="total_fees" id="edit_total_fees" class="form-control" placeholder="0.00">
                    </div>
                    <div class="form-group">
                        <label for="edit_paid_amount" class="form-label">Amount Paid (₹)</label>
                        <input type="number" step="0.01" name="paid_amount" id="edit_paid_amount" class="form-control" placeholder="0.00">
                    </div>
                </div>

                <div class="form-group">
                    <label for="edit_payment_status" class="form-label">Payment Status Title</label>
                    <input type="text" name="payment_status" id="edit_payment_status" class="form-control" placeholder="e.g., Pending payment, Partially paid, Full payment">
                </div>

                <div class="form-group" style="margin-bottom: 0;">
                    <label for="edit_remarks" class="form-label">Remarks & Advisor Notes</label>
                    <textarea name="remarks" id="edit_remarks" class="form-control" rows="4" placeholder="Enter remarks about current calls, counseling, next follow-ups..."></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-outline" onclick="closeLeadModal()">Cancel</button>
                <button type="submit" class="btn-pill">
                    <i class="fa-solid fa-floppy-disk"></i>
                    <span>Save Changes</span>
                </button>
            </div>
        </form>
    </div>
</div>

<!-- 2. DELETE CONFIRMATION MODAL -->
<div class="modal-overlay" id="deleteModal">
    <div class="modal-container" style="max-width: 400px;">
        <div class="modal-header" style="border-bottom: none; padding-bottom: 0;">
            <h3 class="modal-title" style="color: var(--danger);"><i class="fa-solid fa-triangle-exclamation"></i> Delete Record?</h3>
            <span class="modal-close" onclick="closeDeleteModal()">&times;</span>
        </div>
        <form action="leads.php" method="POST">
            <div class="modal-body" style="padding-top: 1rem;">
                <input type="hidden" name="action" value="delete">
                <input type="hidden" name="lead_id" id="deleteLeadId">
                <p style="font-size: 0.95rem; line-height: 1.6; color: var(--text-secondary);">
                    Are you absolutely sure you want to permanently delete the lead record for <strong id="deleteStudentName" style="color: var(--text-primary);">Student</strong> (<span id="deleteLeadCode" style="font-family: monospace; font-weight: 600;">Code</span>)?
                </p>
                <p style="font-size: 0.8rem; color: var(--text-muted); margin-top: 0.75rem;">
                    This action is destructive and cannot be undone. All notes and metrics associated with this student will be destroyed.
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
// Views & Edit Details Modal Handlers
function openLeadModal(lead) {
    document.getElementById('modalLeadId').value = lead.id;
    document.getElementById('modalLeadCode').innerText = lead.lead_code;
    document.getElementById('modalLeadTitle').innerText = 'Student File: ' + lead.student_name;
    document.getElementById('modalStudentName').innerText = lead.student_name;
    document.getElementById('modalContactDetails').innerText = (lead.email || 'No Email') + ' / ' + lead.phone;
    document.getElementById('modalLeadDate').innerText = lead.created_at ? new Date(lead.created_at).toLocaleString() : 'N/A';
    document.getElementById('modalLeadSource').innerText = lead.source || 'Website Enquiry';
    document.getElementById('modalLeadCategory').innerText = lead.category || 'Website Enquiry';
    
    // Inputs
    document.getElementById('edit_interested_in').value = lead.interested_in || '';
    document.getElementById('edit_status').value = lead.status || 'New';
    document.getElementById('edit_total_fees').value = lead.total_fees || 0.00;
    document.getElementById('edit_paid_amount').value = lead.paid_amount || 0.00;
    document.getElementById('edit_payment_status').value = lead.payment_status || 'Pending payment';
    document.getElementById('edit_remarks').value = lead.remarks || '';
    
    document.getElementById('leadModal').classList.add('active');
}

function closeLeadModal() {
    document.getElementById('leadModal').classList.remove('active');
}

// Delete Confirmation Modal Handlers
function triggerDeleteLead(id, name, code) {
    document.getElementById('deleteLeadId').value = id;
    document.getElementById('deleteStudentName').innerText = name;
    document.getElementById('deleteLeadCode').innerText = code;
    document.getElementById('deleteModal').classList.add('active');
}

function closeDeleteModal() {
    document.getElementById('deleteModal').classList.remove('active');
}
</script>

<?php require_once 'includes/footer.php'; ?>
