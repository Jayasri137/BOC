<?php
/**
 * Counselor Document Verification & Feedback API
 * 
 * Handles:
 *   - Counselor verification approval ("Verified" -> Green badge, certified seal)
 *   - Counselor re-upload request ("Needs Re-upload" -> Red badge, exact counselor note)
 *   - Automatic pipeline advancement and CRM audit logging
 */
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
header('Content-Type: application/json; charset=utf-8');
header('X-Content-Type-Options: nosniff');

require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/crm_db.php';
require_once __DIR__ . '/includes/crm_document_flow.php';

// Auth check: allow logged-in students (for demo / simulation), admins, or CRM counselors
$userRole = $_SESSION['user_role'] ?? '';
$userId = (int) ($_SESSION['user_id'] ?? 0);

if ($userId <= 0) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Please sign in to perform document verification.']);
    exit;
}

$action = $_POST['action'] ?? '';

if ($action === 'verify_decision') {
    $docKey = trim($_POST['doc_key'] ?? '');
    $decision = trim($_POST['decision'] ?? 'Verified'); // 'Verified' or 'Needs Re-upload'
    $counselorName = trim($_POST['counselor_name'] ?? 'Meghana (Senior Advisor)');
    $counselorNotes = trim($_POST['counselor_notes'] ?? '');
    $targetStudentId = (int) ($_POST['student_id'] ?? $userId);

    if (empty($docKey)) {
        echo json_encode(['success' => false, 'message' => 'Document key is required.']);
        exit;
    }

    if (!in_array($decision, ['Verified', 'Needs Re-upload'])) {
        echo json_encode(['success' => false, 'message' => 'Invalid decision. Must be Verified or Needs Re-upload.']);
        exit;
    }

    // Default sample notes if none provided for Needs Re-upload
    if ($decision === 'Needs Re-upload' && empty($counselorNotes)) {
        $defaultNotes = [
            'id_passport' => 'Passport copy is blurred, please upload clear color scan including first and last page with signature.',
            'test_english' => 'IELTS scorecard upload is partially cropped on the right margin. Please upload the complete official TRF showing all 4 component band scores and test centre number.',
            'id_bank_statement' => 'Bank statement copy is missing the official bank branch round seal and manager signature on the final page.',
            'academic_10th' => '10th marksheet copy is low resolution. Please upload a clear 300 DPI color scan.',
            'academic_degree' => 'Provisional certificate is missing the university registrar signature.'
        ];
        $counselorNotes = $defaultNotes[$docKey] ?? 'Document verification failed: Please re-upload a clear, uncropped copy with all official stamps and signatures visible.';
    }

    try {
        // Resolve CRM lead ID for this student
        $linkStmt = $pdo->prepare('SELECT crm_lead_id FROM student_crm_links WHERE student_user_id = ? LIMIT 1');
        $linkStmt->execute([$targetStudentId]);
        $crmLeadId = (int) $linkStmt->fetchColumn();

        if ($crmLeadId <= 0) {
            $crmLeadId = 1; // Fallback demo lead
        }

        $result = execute_crm_document_verification_flow(
            $pdo,
            $crmLeadId,
            $docKey,
            $decision,
            $counselorName,
            $counselorNotes
        );

        echo json_encode([
            'success' => true,
            'message' => "Document updated to {$decision} successfully!",
            'decision' => $decision,
            'doc_key' => $docKey,
            'counselor_name' => $counselorName,
            'counselor_notes' => $counselorNotes,
            'flow_result' => $result
        ]);
        exit;
    } catch (Exception $e) {
        error_log('Verification decision error: ' . $e->getMessage());
        echo json_encode(['success' => false, 'message' => 'Unable to record verification decision: ' . $e->getMessage()]);
        exit;
    }
}

echo json_encode(['success' => false, 'message' => 'Invalid action.']);
