<?php
// admin/student_documents.php - Student Document Locker & Verification Manager
$pageTitle = 'Student Document Locker';
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/../includes/crm_db.php';
require_once __DIR__ . '/../includes/crm_document_flow.php';

$alertSuccess = '';
$alertError = '';

// Handle Status & Verification Updates
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = trim($_POST['action'] ?? '');
    
    if ($action === 'update_status') {
        $docId = intval($_POST['doc_id'] ?? 0);
        $newStatus = trim($_POST['status'] ?? 'Verified');
        $adminNotes = trim($_POST['admin_notes'] ?? '');
        $verifierName = !empty($adminName) ? $adminName : 'Bluestone Admissions';
        
        if ($docId > 0 && in_array($newStatus, ['Verified', 'Needs Re-upload', 'Under Review', 'Rejected'])) {
            try {
                // 1. Fetch document info
                $docStmt = $pdo->prepare("SELECT * FROM student_documents WHERE id = ?");
                $docStmt->execute([$docId]);
                $doc = $docStmt->fetch(PDO::FETCH_ASSOC);
                
                if ($doc) {
                    $userId = (int) ($doc['user_id'] ?? 0);
                    
                    // Auto-resolve doc_key, doc_label, and category if missing
                    $docKey = trim($doc['doc_key'] ?? '');
                    $docLabel = trim($doc['doc_label'] ?? '');
                    $category = trim($doc['category'] ?? '');
                    
                    if (empty($docKey)) {
                        $mapped = map_crm_doc_to_key($doc['name'] ?: $docLabel ?: $category);
                        $docKey = $mapped['doc_key'];
                        if (empty($docLabel)) $docLabel = $mapped['doc_label'];
                        if (empty($category)) $category = $mapped['category'];
                    }
                    if (empty($docLabel)) {
                        $docLabel = $doc['name'] ?: 'Document';
                    }
                    if (empty($category)) {
                        $category = 'Academic Records';
                    }
                    
                    // 2. Update status and verification metadata in student_documents
                    $stmt = $pdo->prepare("
                        UPDATE student_documents 
                        SET status = ?, 
                            admin_notes = ?, 
                            verified_by = ?, 
                            doc_key = ?, 
                            doc_label = ?, 
                            category = ?, 
                            updated_at = NOW() 
                        WHERE id = ?
                    ");
                    $stmt->execute([$newStatus, $adminNotes, $verifierName, $docKey, $docLabel, $category, $docId]);
                    
                    // 3. Two-Way Sync with Central CRM if student is linked
                    if ($userId > 0) {
                        $crmLeadId = null;
                        try {
                            $linkStmt = $pdo->prepare('SELECT crm_lead_id FROM student_crm_links WHERE student_user_id = ? LIMIT 1');
                            $linkStmt->execute([$userId]);
                            $crmLeadId = $linkStmt->fetchColumn();
                        } catch (Exception $e) {}
                        
                        if ($crmLeadId) {
                            try {
                                $crmPdo = get_central_crm_pdo();
                                if ($crmPdo) {
                                    $crmDocStatus = ($newStatus === 'Verified') ? 'Verified' : (($newStatus === 'Needs Re-upload' || $newStatus === 'Rejected') ? 'Rejected' : 'Uploaded');
                                    
                                    // Update lead_documents in CRM
                                    $namePattern = '%' . ($doc['name'] ?: $docLabel) . '%';
                                    $keyPattern = '%' . $docKey . '%';
                                    $crmUpd = $crmPdo->prepare("
                                        UPDATE lead_documents 
                                        SET status = ?, verified_by = ?, verified_at = NOW() 
                                        WHERE lead_id = ? AND (document_name LIKE ? OR file_name LIKE ? OR document_name LIKE ? OR file_name LIKE ?)
                                    ");
                                    $crmUpd->execute([$crmDocStatus, $verifierName, $crmLeadId, $namePattern, $namePattern, $keyPattern, $keyPattern]);
                                    
                                    // Append audit note into CRM lead remarks
                                    $auditNote = "[ADMISSIONS VERIFICATION — " . date('d M Y H:i') . "]\n"
                                        . "• Document: " . ($doc['name'] ?: $docLabel) . " [Key: {$docKey}]\n"
                                        . "• Decision: {$newStatus}\n"
                                        . "• Verified By: {$verifierName}\n"
                                        . (!empty($adminNotes) ? "• Feedback Note: \"{$adminNotes}\"\n" : "")
                                        . "• Synchronized across Student Web Portal & Mobile App.";
                                        
                                    $crmPdo->prepare("
                                        UPDATE leads 
                                        SET remarks = CONCAT(?, '\n\n', COALESCE(remarks, '')), updated_at = NOW() 
                                        WHERE id = ? AND is_active = 1
                                    ")->execute([$auditNote, $crmLeadId]);
                                }
                            } catch (Exception $ce) {
                                error_log('CRM Doc verification sync error: ' . $ce->getMessage());
                            }
                        }
                        
                        // 4. Dispatch Notifications
                        $notifTitle = ($newStatus === 'Verified')
                            ? "Document Verified: {$docLabel} ✅"
                            : (($newStatus === 'Needs Re-upload') ? "Re-upload Required: {$docLabel} ⚠️" : "Document Updated: {$docLabel}");
                            
                        $notifBody = ($newStatus === 'Verified')
                            ? "Great news! Your {$category} document ({$docLabel}) has been verified and certified by {$verifierName}."
                            : (($newStatus === 'Needs Re-upload')
                                ? "Action needed: {$adminNotes} (Please re-upload via your Document Locker)."
                                : "Your document status has been updated to {$newStatus}.");
                        
                        // Notification for web dashboard bell
                        try {
                            $webNotifType = ($newStatus === 'Verified') ? 'success' : (($newStatus === 'Needs Re-upload') ? 'warning' : 'info');
                            $webNotifStmt = $pdo->prepare("
                                INSERT INTO notifications (user_id, title, message, type, category, link)
                                VALUES (?, ?, ?, ?, 'document', 'student-dashboard.php?tab=documents')
                            ");
                            $webNotifStmt->execute([$userId, $notifTitle, $notifBody, $webNotifType]);
                        } catch (Exception $e) {}
                        
                        // Notification for mobile app
                        try {
                            $mobileNotifStmt = $pdo->prepare("
                                INSERT INTO student_notifications (user_id, title, body, category, priority, action_label, route_target, is_read, created_at)
                                VALUES (?, ?, ?, 'documents', ?, 'View Vault', '/documents', 0, NOW())
                            ");
                            $priority = ($newStatus === 'Needs Re-upload') ? 'high' : 'medium';
                            $mobileNotifStmt->execute([$userId, $notifTitle, $notifBody, $priority]);
                        } catch (Exception $e) {}
                    }
                    
                    $alertSuccess = "Document status updated to '{$newStatus}' and synchronized across Web & CRM successfully!";
                } else {
                    $alertError = "Document not found.";
                }
            } catch (PDOException $e) {
                $alertError = "Error updating document: " . $e->getMessage();
            }
        } else {
            $alertError = "Invalid document ID or status.";
        }
    } elseif ($action === 'delete') {
        $docId = intval($_POST['doc_id'] ?? 0);
        if ($docId > 0) {
            try {
                $stmt = $pdo->prepare("DELETE FROM student_documents WHERE id = ?");
                $stmt->execute([$docId]);
                $alertSuccess = "Document deleted successfully from vault.";
            } catch (PDOException $e) {
                $alertError = "Error deleting document: " . $e->getMessage();
            }
        }
    }
}

// Filters & Pagination
$statusFilter = trim($_GET['status'] ?? '');
$studentFilter = intval($_GET['student_id'] ?? 0);
$search = trim($_GET['q'] ?? '');

$page = max(1, intval($_GET['page'] ?? 1));
$limit = 20;
$offset = ($page - 1) * $limit;

// Build query
$where = [];
$params = [];

if (!empty($statusFilter)) {
    $where[] = "d.status = ?";
    $params[] = $statusFilter;
}

if ($studentFilter > 0) {
    $where[] = "d.user_id = ?";
    $params[] = $studentFilter;
}

if (!empty($search)) {
    $where[] = "(d.name LIKE ? OR d.category LIKE ? OR u.first_name LIKE ? OR u.last_name LIKE ? OR u.email LIKE ?)";
    $like = "%{$search}%";
    $params[] = $like;
    $params[] = $like;
    $params[] = $like;
    $params[] = $like;
    $params[] = $like;
}

$whereSql = !empty($where) ? "WHERE " . implode(" AND ", $where) : "";

// Count total
$countStmt = $pdo->prepare("
    SELECT COUNT(*) 
    FROM student_documents d 
    LEFT JOIN users u ON d.user_id = u.id 
    {$whereSql}
");
$countStmt->execute($params);
$totalDocs = (int) $countStmt->fetchColumn();
$totalPages = ceil($totalDocs / $limit);

// Fetch documents
$query = "
    SELECT d.*, u.first_name, u.last_name, u.email, u.phone 
    FROM student_documents d 
    LEFT JOIN users u ON d.user_id = u.id 
    {$whereSql} 
    ORDER BY d.id DESC 
    LIMIT {$limit} OFFSET {$offset}
";
$stmt = $pdo->prepare($query);
$stmt->execute($params);
$documents = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Overall Statistics
$stats = [
    'total' => 0,
    'under_review' => 0,
    'verified' => 0,
    'needs_reupload' => 0
];
try {
    $statRows = $pdo->query("SELECT status, COUNT(*) as cnt FROM student_documents GROUP BY status")->fetchAll();
    foreach ($statRows as $row) {
        $st = $row['status'];
        $cnt = (int) $row['cnt'];
        $stats['total'] += $cnt;
        if (in_array($st, ['Under Review', 'pending'])) $stats['under_review'] += $cnt;
        elseif (in_array($st, ['Verified', 'verified'])) $stats['verified'] += $cnt;
        elseif (in_array($st, ['Needs Re-upload', 'rejected', 'Rejected'])) $stats['needs_reupload'] += $cnt;
    }
} catch (PDOException $e) {}
?>

<div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 2rem; flex-wrap: wrap; gap: 1rem;">
    <div>
        <h1 class="page-title" style="margin-bottom: 0.25rem;">
            Student Document Locker & Vault
        </h1>
        <p style="color: var(--text-secondary); font-size: 0.9rem; margin: 0;">
            Unified repository for compliance documents uploaded via the Mobile App & Student Dashboard.
        </p>
    </div>
    <div>
        <a href="students.php" class="btn-outline">
            <i class="fa-solid fa-users"></i>
            <span>Manage Students</span>
        </a>
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

<!-- Statistics Summary -->
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(210px, 1fr)); gap: 1rem; margin-bottom: 1.5rem;">
    <a href="student_documents.php" style="text-decoration: none; color: inherit;">
        <div class="stat-card" style="background: var(--surface); padding: 1.25rem; border-radius: var(--radius-md); border: 1px solid var(--border);">
            <div style="font-size: 0.85rem; color: var(--text-muted); font-weight: 600;">TOTAL DOCUMENTS</div>
            <div style="font-size: 1.75rem; font-weight: 700; color: var(--text-primary); margin-top: 0.25rem;"><?php echo $stats['total']; ?></div>
        </div>
    </a>
    <a href="student_documents.php?status=Under+Review" style="text-decoration: none; color: inherit;">
        <div class="stat-card" style="background: var(--surface); padding: 1.25rem; border-radius: var(--radius-md); border: 1px solid #fef3c7;">
            <div style="font-size: 0.85rem; color: #b45309; font-weight: 600;">UNDER REVIEW</div>
            <div style="font-size: 1.75rem; font-weight: 700; color: #d97706; margin-top: 0.25rem;"><?php echo $stats['under_review']; ?></div>
        </div>
    </a>
    <a href="student_documents.php?status=Verified" style="text-decoration: none; color: inherit;">
        <div class="stat-card" style="background: var(--surface); padding: 1.25rem; border-radius: var(--radius-md); border: 1px solid #d1fae5;">
            <div style="font-size: 0.85rem; color: #047857; font-weight: 600;">VERIFIED</div>
            <div style="font-size: 1.75rem; font-weight: 700; color: #059669; margin-top: 0.25rem;"><?php echo $stats['verified']; ?></div>
        </div>
    </a>
    <a href="student_documents.php?status=Needs+Re-upload" style="text-decoration: none; color: inherit;">
        <div class="stat-card" style="background: var(--surface); padding: 1.25rem; border-radius: var(--radius-md); border: 1px solid #fee2e2;">
            <div style="font-size: 0.85rem; color: #b91c1c; font-weight: 600;">NEEDS RE-UPLOAD</div>
            <div style="font-size: 1.75rem; font-weight: 700; color: #dc2626; margin-top: 0.25rem;"><?php echo $stats['needs_reupload']; ?></div>
        </div>
    </a>
</div>

<!-- Search & Filter Bar -->
<div class="panel-card" style="margin-bottom: 1.5rem; padding: 1rem 1.25rem;">
    <form method="GET" action="student_documents.php" style="display: flex; gap: 0.75rem; flex-wrap: wrap; align-items: center;">
        <div style="flex: 1; min-width: 220px;">
            <input type="text" name="q" value="<?php echo clean_output($search); ?>" placeholder="Search student name, email, or document..." class="form-control" style="font-size: 0.85rem;">
        </div>
        <div>
            <select name="status" class="form-control" style="font-size: 0.85rem;">
                <option value="">All Statuses</option>
                <option value="Under Review" <?php echo $statusFilter === 'Under Review' ? 'selected' : ''; ?>>Under Review</option>
                <option value="Verified" <?php echo $statusFilter === 'Verified' ? 'selected' : ''; ?>>Verified</option>
                <option value="Needs Re-upload" <?php echo $statusFilter === 'Needs Re-upload' ? 'selected' : ''; ?>>Needs Re-upload</option>
            </select>
        </div>
        <button type="submit" class="btn-pill" style="padding: 0.5rem 1.2rem; font-size: 0.85rem;">
            <i class="fa-solid fa-magnifying-glass"></i> Filter
        </button>
        <?php if (!empty($search) || !empty($statusFilter) || $studentFilter > 0): ?>
            <a href="student_documents.php" class="btn-outline" style="padding: 0.5rem 1rem; font-size: 0.85rem;">Clear</a>
        <?php endif; ?>
    </form>
</div>

<!-- Documents Table -->
<div class="panel-card" style="overflow-x: auto;">
    <table class="data-table" style="width: 100%; border-collapse: collapse;">
        <thead>
            <tr style="border-bottom: 1px solid var(--border); text-align: left;">
                <th style="padding: 1rem;">ID</th>
                <th style="padding: 1rem;">Student</th>
                <th style="padding: 1rem;">Document Name & Category</th>
                <th style="padding: 1rem;">File Info</th>
                <th style="padding: 1rem;">Status</th>
                <th style="padding: 1rem;">Uploaded Date</th>
                <th style="padding: 1rem; text-align: right;">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($documents)): ?>
                <tr>
                    <td colspan="7" style="padding: 3rem; text-align: center; color: var(--text-muted);">
                        <i class="fa-solid fa-folder-open" style="font-size: 2.5rem; display: block; margin-bottom: 0.75rem;"></i>
                        No student documents found.
                    </td>
                </tr>
            <?php else: ?>
                <?php foreach ($documents as $doc): ?>
                    <?php
                        $st = $doc['status'] ?? 'Under Review';
                        $statusBadgeClass = 'background: #fef3c7; color: #92400e; border: 1px solid #fde68a;';
                        if ($st === 'Verified' || $st === 'verified') {
                            $statusBadgeClass = 'background: #d1fae5; color: #065f46; border: 1px solid #a7f3d0;';
                        } elseif ($st === 'Needs Re-upload' || $st === 'rejected' || $st === 'Rejected') {
                            $statusBadgeClass = 'background: #fee2e2; color: #991b1b; border: 1px solid #fecaca;';
                        }
                        
                        // Resolve download / preview URL
                        $fileUrl = $doc['file_url'] ?? '';
                        if (empty($fileUrl) && !empty($doc['file_path'])) {
                            $fileUrl = $doc['file_path'];
                        }
                        $cleanUrl = ltrim($fileUrl, '/');
                        $fullFileUrl = '../' . $cleanUrl;
                    ?>
                    <tr style="border-bottom: 1px solid var(--border);">
                        <td style="padding: 1rem; color: var(--text-muted);">#<?php echo $doc['id']; ?></td>
                        <td style="padding: 1rem;">
                            <strong style="color: var(--text-primary); display: block;">
                                <?php echo clean_output(($doc['first_name'] ?? '') . ' ' . ($doc['last_name'] ?? '')); ?>
                            </strong>
                            <span style="font-size: 0.8rem; color: var(--text-muted);"><?php echo clean_output($doc['email'] ?? 'ID: #' . $doc['user_id']); ?></span>
                        </td>
                        <td style="padding: 1rem;">
                            <div style="font-weight: 600; color: var(--text-primary);">
                                <?php echo clean_output($doc['name'] ?? $doc['doc_label'] ?? 'Document'); ?>
                            </div>
                            <span style="display: inline-block; font-size: 0.75rem; padding: 2px 8px; border-radius: 4px; background: rgba(0,0,0,0.04); color: var(--text-secondary); margin-top: 3px;">
                                <i class="fa-solid fa-tag"></i> <?php echo clean_output($doc['category'] ?? $doc['doc_type'] ?? 'General'); ?>
                            </span>
                            <?php if (!empty($doc['admin_notes'])): ?>
                                <div style="font-size: 0.78rem; color: #b91c1c; margin-top: 4px; font-style: italic;">
                                    Note: <?php echo clean_output($doc['admin_notes']); ?>
                                </div>
                            <?php endif; ?>
                        </td>
                        <td style="padding: 1rem;">
                            <span style="text-transform: uppercase; font-weight: 700; font-size: 0.75rem; background: #e0f2fe; color: #0369a1; padding: 2px 6px; border-radius: 4px;">
                                <?php echo clean_output($doc['file_extension'] ?? 'PDF'); ?>
                            </span>
                            <span style="font-size: 0.8rem; color: var(--text-muted); margin-left: 6px;">
                                <?php echo clean_output($doc['size'] ?? ''); ?>
                            </span>
                        </td>
                        <td style="padding: 1rem;">
                            <span style="display: inline-block; padding: 4px 10px; border-radius: 12px; font-size: 0.8rem; font-weight: 600; <?php echo $statusBadgeClass; ?>">
                                <?php echo clean_output($st); ?>
                            </span>
                        </td>
                        <td style="padding: 1rem; color: var(--text-secondary); font-size: 0.85rem;">
                            <?php echo !empty($doc['created_at']) ? date('M d, Y', strtotime($doc['created_at'])) : '-'; ?>
                        </td>
                        <td style="padding: 1rem; text-align: right; white-space: nowrap;">
                            <?php if (!empty($fileUrl)): ?>
                                <a href="<?php echo htmlspecialchars($fullFileUrl); ?>" target="_blank" class="btn-action" style="display: inline-flex; align-items: center; justify-content: center; width: 32px; height: 32px; background: #e0f2fe; color: #0284c7; border-radius: 6px; margin-right: 4px; text-decoration: none;" title="Preview Document">
                                    <i class="fa-solid fa-eye"></i>
                                </a>
                            <?php endif; ?>
                            <button type="button" class="btn-action" style="width: 32px; height: 32px; background: #dbeafe; color: var(--primary); border-radius: 6px; border: none; cursor: pointer; margin-right: 4px;" title="Review & Verify" onclick="openReviewModal(<?php echo htmlspecialchars(json_encode($doc)); ?>)">
                                <i class="fa-solid fa-stamp"></i>
                            </button>
                            <form method="POST" action="student_documents.php" style="display: inline;" onsubmit="return confirm('Delete this document from vault?');">
                                <input type="hidden" name="action" value="delete">
                                <input type="hidden" name="doc_id" value="<?php echo $doc['id']; ?>">
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

<!-- REVIEW & VERIFY MODAL -->
<div class="modal-overlay" id="reviewModal">
    <div class="modal-container" style="max-width: 500px;">
        <div class="modal-header">
            <h3 class="modal-title" id="reviewModalTitle"><i class="fa-solid fa-stamp"></i> Review Document</h3>
            <span class="modal-close" onclick="closeReviewModal()">&times;</span>
        </div>
        <form action="student_documents.php" method="POST">
            <div class="modal-body">
                <input type="hidden" name="action" value="update_status">
                <input type="hidden" name="doc_id" id="revDocId">

                <div style="background: var(--bg-main); padding: 1rem; border-radius: var(--radius-sm); margin-bottom: 1.25rem;">
                    <div style="font-size: 0.8rem; color: var(--text-muted); text-transform: uppercase;">Document</div>
                    <div style="font-weight: 700; color: var(--text-primary); font-size: 1rem;" id="revDocName">-</div>
                    <div style="font-size: 0.85rem; color: var(--text-secondary); margin-top: 3px;" id="revStudentName">-</div>
                </div>

                <div class="form-group" style="margin-bottom: 1rem;">
                    <label class="form-label" style="font-weight: 600;">Verification Decision *</label>
                    <select name="status" id="revStatus" class="form-control" required onchange="toggleNotesRequired(this.value)">
                        <option value="Verified">✅ Verified (Approved & Certified)</option>
                        <option value="Needs Re-upload">⚠️ Needs Re-upload (Feedback Required)</option>
                        <option value="Under Review">⏳ Under Review (Pending Further Verification)</option>
                        <option value="Rejected">❌ Rejected (Invalid / Fraudulent)</option>
                    </select>
                </div>

                <div class="form-group" id="notesGroup">
                    <label class="form-label" style="font-weight: 600;">Counselor Remarks / Feedback Note</label>
                    <textarea name="admin_notes" id="revNotes" class="form-control" rows="3" placeholder="e.g. Please provide a clearer scanned copy of page 1 showing official stamp."></textarea>
                    <small style="color: var(--text-muted); font-size: 0.78rem; display: block; margin-top: 4px;">
                        This feedback note is sent to the student on their mobile app and dashboard.
                    </small>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn-outline" onclick="closeReviewModal()">Cancel</button>
                <button type="submit" class="btn-pill">
                    <i class="fa-solid fa-check"></i>
                    <span>Save Decision</span>
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function openReviewModal(doc) {
    document.getElementById('revDocId').value = doc.id;
    document.getElementById('revDocName').innerText = (doc.name || doc.doc_label || 'Document') + ' (' + (doc.category || 'General') + ')';
    document.getElementById('revStudentName').innerText = (doc.first_name ? (doc.first_name + ' ' + (doc.last_name || '')) : 'Student #' + doc.user_id) + (doc.email ? ' • ' + doc.email : '');
    document.getElementById('revStatus').value = doc.status || 'Verified';
    document.getElementById('revNotes').value = doc.admin_notes || '';
    toggleNotesRequired(doc.status || 'Verified');
    document.getElementById('reviewModal').classList.add('active');
}

function closeReviewModal() {
    document.getElementById('reviewModal').classList.remove('active');
}

function toggleNotesRequired(status) {
    const notesInput = document.getElementById('revNotes');
    if (status === 'Needs Re-upload' || status === 'Rejected') {
        notesInput.required = true;
        notesInput.placeholder = 'Please explain why re-upload is needed so student can correct it...';
    } else {
        notesInput.required = false;
        notesInput.placeholder = 'Optional counselor notes...';
    }
}
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>

