<?php
/**
 * Student Offer & Decision API
 * Handles:
 *   - Accept Offer & Reserve Seat
 *   - Request University Fee Payment / Wire Details
 *   - Offer Letter generation / download
 */
session_start();
header('Content-Type: application/json; charset=utf-8');
header('X-Content-Type-Options: nosniff');
header('Cache-Control: no-store, max-age=0');

require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/crm_db.php';

function offerApiResponse(int $status, array $payload): void {
    http_response_code($status);
    echo json_encode($payload, JSON_INVALID_UTF8_SUBSTITUTE);
    exit;
}

if (empty($_SESSION['user_id']) || ($_SESSION['user_role'] ?? '') !== 'student') {
    offerApiResponse(401, ['success' => false, 'message' => 'Please sign in with a student account.']);
}

$userId = (int) $_SESSION['user_id'];
$csrfToken = $_POST['csrf_token'] ?? '';

if (
    !is_string($csrfToken) ||
    empty($_SESSION['community_csrf_token']) ||
    !hash_equals($_SESSION['community_csrf_token'], $csrfToken)
) {
    offerApiResponse(403, ['success' => false, 'message' => 'Session expired. Refresh the page and try again.']);
}

$action = trim($_POST['action'] ?? '');
$appId = (int) ($_POST['app_id'] ?? 0);

if ($appId < 1) {
    offerApiResponse(422, ['success' => false, 'message' => 'Valid application ID is required.']);
}

// Fetch the application
try {
    $stmt = $pdo->prepare("SELECT * FROM student_applications WHERE id = ? AND user_id = ? LIMIT 1");
    $stmt->execute([$appId, $userId]);
    $app = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$app) {
        offerApiResponse(404, ['success' => false, 'message' => 'Application record not found.']);
    }
} catch (PDOException $e) {
    offerApiResponse(500, ['success' => false, 'message' => 'Database error.']);
}

// ─── ACTION 1: Accept Offer ───
if ($action === 'accept_offer') {
    try {
        $updateStmt = $pdo->prepare("
            UPDATE student_applications 
            SET offer_type = 'Accepted', status = 'Offer Accepted & Seat Reserved', status_type = 'visa'
            WHERE id = ? AND user_id = ?
        ");
        $updateStmt->execute([$appId, $userId]);

        // Insert student notification
        $uniName = $app['university_name'];
        $notifStmt = $pdo->prepare("
            INSERT INTO notifications (user_id, title, message, type, category, link)
            VALUES (?, ?, ?, 'success', 'application', 'student-dashboard.php?tab=application')
        ");
        $notifStmt->execute([
            $userId,
            'Offer Accepted: ' . $uniName,
            'You have accepted the offer from ' . $uniName . '. Your counselor has been notified to guide you on deposit payment and CAS issuance.'
        ]);

        // Also add note to CRM remarks if linked
        try {
            $linkStmt = $pdo->prepare('SELECT crm_lead_id FROM student_crm_links WHERE student_user_id = ? LIMIT 1');
            $linkStmt->execute([$userId]);
            $crmLeadId = (int) $linkStmt->fetchColumn();
            if ($crmLeadId > 0) {
                $crmPdo = get_central_crm_pdo();
                $remark = "[STUDENT PORTAL ACTION] Accepted Offer for " . $uniName . " (" . $app['course_name'] . ") on " . date('Y-m-d H:i:s') . ". Awaiting deposit remittance instructions.";
                $crmPdo->prepare("
                    UPDATE leads 
                    SET remarks = CONCAT(?, '\n\n', COALESCE(remarks, ''))
                    WHERE id = ? AND is_active = 1
                ")->execute([$remark, $crmLeadId]);
            }
        } catch (Exception $e) {
            error_log('CRM remark sync error: ' . $e->getMessage());
        }

        offerApiResponse(200, [
            'success' => true,
            'message' => 'Congratulations! You have successfully accepted the offer from ' . $uniName . '. Your advisor will guide you on fee deposit and CAS processing.'
        ]);
    } catch (PDOException $e) {
        error_log('Accept offer error: ' . $e->getMessage());
        offerApiResponse(500, ['success' => false, 'message' => 'Unable to record offer acceptance.']);
    }
}

// ─── ACTION 2: Request Fee Payment Details ───
if ($action === 'request_fee_details') {
    try {
        $updateStmt = $pdo->prepare("
            UPDATE student_applications 
            SET fee_details_requested = 1
            WHERE id = ? AND user_id = ?
        ");
        $updateStmt->execute([$appId, $userId]);

        $uniName = $app['university_name'];
        $notifStmt = $pdo->prepare("
            INSERT INTO notifications (user_id, title, message, type, category, link)
            VALUES (?, ?, ?, 'info', 'application', 'student-dashboard.php?tab=application')
        ");
        $notifStmt->execute([
            $userId,
            'Fee Details Requested: ' . $uniName,
            'Your request for international wire & payment remittance details for ' . $uniName . ' has been forwarded to your assigned advisor.'
        ]);

        // Add note to CRM remarks
        try {
            $linkStmt = $pdo->prepare('SELECT crm_lead_id FROM student_crm_links WHERE student_user_id = ? LIMIT 1');
            $linkStmt->execute([$userId]);
            $crmLeadId = (int) $linkStmt->fetchColumn();
            if ($crmLeadId > 0) {
                $crmPdo = get_central_crm_pdo();
                $remark = "[STUDENT PORTAL ACTION] Requested Official Fee Payment / Wire Instructions for " . $uniName . " (" . $app['deposit_amount'] . ") on " . date('Y-m-d H:i:s') . ".";
                $crmPdo->prepare("
                    UPDATE leads 
                    SET remarks = CONCAT(?, '\n\n', COALESCE(remarks, ''))
                    WHERE id = ? AND is_active = 1
                ")->execute([$remark, $crmLeadId]);
            }
        } catch (Exception $e) {}

        offerApiResponse(200, [
            'success' => true,
            'message' => 'Official fee payment & wire instructions requested! Your advisor will contact you with approved bank remittance and Flywire/Convera details.'
        ]);
    } catch (PDOException $e) {
        error_log('Request fee error: ' . $e->getMessage());
        offerApiResponse(500, ['success' => false, 'message' => 'Unable to request fee details.']);
    }
}

offerApiResponse(400, ['success' => false, 'message' => 'Invalid action specified.']);

