<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/crm_db.php';
require_once __DIR__ . '/includes/auth.php';

header('Content-Type: application/json; charset=utf-8');

function studentCrmLinkResponse(int $status, array $payload): void
{
    http_response_code($status);
    echo json_encode($payload);
    exit;
}

if (empty($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    studentCrmLinkResponse(401, ['success' => false, 'message' => 'Please sign in as an administrator.']);
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $search = trim((string) ($_GET['search'] ?? ''));
    if (strlen($search) < 2) {
        studentCrmLinkResponse(200, ['success' => true, 'leads' => []]);
    }

    try {
        $crmPdo = get_central_crm_pdo();
        $phoneDigits = preg_replace('/\D+/', '', $search);
        $phoneSearchClause = $phoneDigits !== ''
            ? "OR REGEXP_REPLACE(COALESCE(phone, ''), '[^0-9]', '') LIKE ?"
            : '';
        $searchSql = "
            SELECT id, lead_code, student_name, email, phone, domain, interested_in, status, primary_status
            FROM leads
            WHERE is_active = 1
              AND (
                    student_name LIKE ?
                    OR email LIKE ?
                    OR phone LIKE ?
                    OR lead_code LIKE ?
                    $phoneSearchClause
              )
            ORDER BY created_at DESC, id DESC
            LIMIT 25
        ";
        $stmt = $crmPdo->prepare($searchSql);
        $term = '%' . $search . '%';
        $searchParams = [$term, $term, $term, $term];
        if ($phoneDigits !== '') {
            $searchParams[] = '%' . $phoneDigits . '%';
        }
        $stmt->execute($searchParams);
        $leads = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if ($leads) {
            $leadIds = array_column($leads, 'id');
            $placeholders = implode(',', array_fill(0, count($leadIds), '?'));
            $linksStmt = $pdo->prepare("
                SELECT crm_lead_id, student_user_id
                FROM student_crm_links
                WHERE crm_lead_id IN ($placeholders)
            ");
            $linksStmt->execute($leadIds);
            $linkedStudents = [];
            foreach ($linksStmt->fetchAll(PDO::FETCH_ASSOC) as $link) {
                $linkedStudents[(int) $link['crm_lead_id']] = (int) $link['student_user_id'];
            }
            foreach ($leads as &$lead) {
                $lead['linked_user_id'] = $linkedStudents[(int) $lead['id']] ?? null;
            }
            unset($lead);
        }

        studentCrmLinkResponse(200, ['success' => true, 'leads' => $leads]);
    } catch (PDOException $e) {
        error_log('Student CRM link lead search failed: ' . $e->getMessage());
        if ($e->getCode() === '42S22' || $e->getCode() === '42S02') {
            studentCrmLinkResponse(500, ['success' => false, 'message' => 'Run alter_db.php to install the CRM linking table, then retry.']);
        }
        studentCrmLinkResponse(500, ['success' => false, 'message' => 'Unable to search the central CRM records right now.']);
    }
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    studentCrmLinkResponse(405, ['success' => false, 'message' => 'Unsupported request method.']);
}

if (
    !isset($_POST['csrf_token']) ||
    !is_string($_POST['csrf_token']) ||
    empty($_SESSION['student_crm_link_csrf']) ||
    !hash_equals($_SESSION['student_crm_link_csrf'], $_POST['csrf_token'])
) {
    studentCrmLinkResponse(403, ['success' => false, 'message' => 'Your admin session expired. Refresh and try again.']);
}

$action = $_POST['action'] ?? '';
$userId = filter_var($_POST['user_id'] ?? null, FILTER_VALIDATE_INT);
$leadId = filter_var($_POST['lead_id'] ?? null, FILTER_VALIDATE_INT);
if (!in_array($action, ['link', 'unlink'], true) || !$userId || $userId < 1) {
    studentCrmLinkResponse(422, ['success' => false, 'message' => 'Invalid student CRM link request.']);
}

try {
    $studentStmt = $pdo->prepare("SELECT id FROM users WHERE id = ? AND role = 'student' LIMIT 1");
    $studentStmt->execute([$userId]);
    if (!$studentStmt->fetchColumn()) {
        studentCrmLinkResponse(404, ['success' => false, 'message' => 'Student account not found.']);
    }

    if ($action === 'unlink') {
        if (!$leadId || $leadId < 1) {
            studentCrmLinkResponse(422, ['success' => false, 'message' => 'Invalid CRM record.']);
        }
        $unlinkStmt = $pdo->prepare('DELETE FROM student_crm_links WHERE crm_lead_id = ? AND student_user_id = ?');
        $unlinkStmt->execute([$leadId, $userId]);
        studentCrmLinkResponse(200, ['success' => true, 'message' => 'CRM record unlinked from this student.']);
    }

    if (!$leadId || $leadId < 1) {
        studentCrmLinkResponse(422, ['success' => false, 'message' => 'Choose a CRM record to link.']);
    }

    $crmPdo = get_central_crm_pdo();
    $leadStmt = $crmPdo->prepare('SELECT id FROM leads WHERE id = ? AND is_active = 1 LIMIT 1');
    $leadStmt->execute([$leadId]);
    if (!$leadStmt->fetch(PDO::FETCH_ASSOC)) {
        studentCrmLinkResponse(404, ['success' => false, 'message' => 'Active CRM record not found.']);
    }

    $pdo->beginTransaction();
    $existingLeadLink = $pdo->prepare('SELECT student_user_id FROM student_crm_links WHERE crm_lead_id = ? FOR UPDATE');
    $existingLeadLink->execute([$leadId]);
    $existingStudentId = $existingLeadLink->fetchColumn();
    if ($existingStudentId !== false && (int) $existingStudentId !== (int) $userId) {
        $pdo->rollBack();
        studentCrmLinkResponse(409, ['success' => false, 'message' => 'That CRM record is already linked to another student account.']);
    }

    $linkStmt = $pdo->prepare("
        INSERT INTO student_crm_links (student_user_id, crm_lead_id)
        VALUES (?, ?)
        ON DUPLICATE KEY UPDATE crm_lead_id = VALUES(crm_lead_id), linked_at = CURRENT_TIMESTAMP
    ");
    $linkStmt->execute([$userId, $leadId]);
    $verifyStmt = $pdo->prepare('SELECT student_user_id FROM student_crm_links WHERE crm_lead_id = ?');
    $verifyStmt->execute([$leadId]);
    if ((int) $verifyStmt->fetchColumn() !== (int) $userId) {
        $pdo->rollBack();
        studentCrmLinkResponse(409, ['success' => false, 'message' => 'That CRM record is already linked to another student account.']);
    }
    $pdo->commit();
    studentCrmLinkResponse(200, ['success' => true, 'message' => 'CRM record linked to the student account.']);
} catch (PDOException $e) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    error_log('Student CRM link update failed: ' . $e->getMessage());
    if ((string) $e->getCode() === '23000') {
        studentCrmLinkResponse(409, ['success' => false, 'message' => 'That CRM record is already linked to another student account.']);
    }
    if ($e->getCode() === '42S22' || $e->getCode() === '42S02') {
        studentCrmLinkResponse(500, ['success' => false, 'message' => 'The database needs its student-to-CRM linking update. Run alter_db.php and try again.']);
    }
    studentCrmLinkResponse(500, ['success' => false, 'message' => 'Unable to update the CRM link right now.']);
}
