<?php
/**
 * Student Documents API — Categorized Checklist & Verification Hub
 * 
 * Features:
 *   - Categorized Document Checklist (Academic Records, English & Tests, Application Essentials, Identity & Financials)
 *   - Visual Verification Badges & Counselor Feedback Notes
 *   - Intake-Based Expiry Alerts (Passport & IELTS/English scorecard validation)
 *   - File upload, replace, and deletion
 *   - Synchronization with Central CRM (lead_documents & leads.remarks)
 */
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/crm_db.php';
require_once __DIR__ . '/includes/crm_document_flow.php';

// Auth check
if (empty($_SESSION['user_id']) || ($_SESSION['user_role'] ?? '') !== 'student') {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Please sign in with a student account.']);
    exit;
}

header('Content-Type: application/json; charset=utf-8');
header('X-Content-Type-Options: nosniff');

$userId = (int) $_SESSION['user_id'];

// ─── Master Document Checklist Definition (4 Categories) ───
$masterCategories = [
    'academic' => [
        'id' => 'academic',
        'title' => 'Academic Records',
        'icon' => 'fa-graduation-cap',
        'badge' => 'Education',
        'color' => '#2563eb',
        'desc' => 'High school, intermediate, and undergraduate transcripts required for admissions evaluation.',
        'docs' => [
            'academic_10th' => [
                'doc_key' => 'academic_10th',
                'doc_label' => '10th Standard / Secondary Marksheet',
                'category' => 'Academic Records',
                'mandatory' => true,
                'has_expiry' => false,
                'accepted' => '.pdf,.jpg,.jpeg,.png',
                'desc' => 'Official 10th grade / SSC / CBSE / ICSE marks statement'
            ],
            'academic_12th' => [
                'doc_key' => 'academic_12th',
                'doc_label' => '12th / Higher Secondary Marksheet',
                'category' => 'Academic Records',
                'mandatory' => true,
                'has_expiry' => false,
                'accepted' => '.pdf,.jpg,.jpeg,.png',
                'desc' => '12th grade / HSC / Intermediate / A-Levels certificate'
            ],
            'academic_ug' => [
                'doc_key' => 'academic_ug',
                'doc_label' => 'Undergrad Marksheets (Sem 1-8 / Consolidated)',
                'category' => 'Academic Records',
                'mandatory' => true,
                'has_expiry' => false,
                'accepted' => '.pdf',
                'desc' => 'All individual semester transcripts or official consolidated marksheet'
            ],
            'academic_degree' => [
                'doc_key' => 'academic_degree',
                'doc_label' => 'Degree / Provisional Certificate',
                'category' => 'Academic Records',
                'mandatory' => true,
                'has_expiry' => false,
                'accepted' => '.pdf,.jpg,.jpeg,.png',
                'desc' => 'Convocation degree certificate or provisional passing certificate'
            ]
        ]
    ],
    'tests' => [
        'id' => 'tests',
        'title' => 'English & Standardized Tests',
        'icon' => 'fa-language',
        'badge' => 'Eligibility Tests',
        'color' => '#8b5cf6',
        'desc' => 'Language proficiency and graduate admissions test results.',
        'docs' => [
            'test_english' => [
                'doc_key' => 'test_english',
                'doc_label' => 'English Language Scorecard (IELTS / PTE / TOEFL / Duolingo)',
                'category' => 'English & Standardized Tests',
                'mandatory' => true,
                'has_expiry' => true,
                'expiry_type' => 'ielts',
                'accepted' => '.pdf',
                'desc' => 'Official Test Report Form (TRF) showing overall score & 4 component bands'
            ],
            'test_standardized' => [
                'doc_key' => 'test_standardized',
                'doc_label' => 'GRE / GMAT Scorecard (If applicable)',
                'category' => 'English & Standardized Tests',
                'mandatory' => false,
                'has_expiry' => true,
                'expiry_type' => 'gre',
                'accepted' => '.pdf',
                'desc' => 'Official GRE General or GMAT scorecard for STEM/MBA programs'
            ]
        ]
    ],
    'essentials' => [
        'id' => 'essentials',
        'title' => 'Application Essentials',
        'icon' => 'fa-file-lines',
        'badge' => 'Admission Docs',
        'color' => '#059669',
        'desc' => 'Core application dossier required by university admission committees.',
        'docs' => [
            'app_sop' => [
                'doc_key' => 'app_sop',
                'doc_label' => 'Statement of Purpose (SOP)',
                'category' => 'Application Essentials',
                'mandatory' => true,
                'has_expiry' => false,
                'accepted' => '.pdf,.docx,.doc',
                'desc' => 'Personal statement of purpose tailored to your chosen course'
            ],
            'app_cv' => [
                'doc_key' => 'app_cv',
                'doc_label' => 'Updated CV / Academic Resume',
                'category' => 'Application Essentials',
                'mandatory' => true,
                'has_expiry' => false,
                'accepted' => '.pdf,.docx,.doc',
                'desc' => 'Detailed academic CV conforming to international format'
            ],
            'app_lor_1' => [
                'doc_key' => 'app_lor_1',
                'doc_label' => 'Letter of Recommendation 1 (Academic LOR)',
                'category' => 'Application Essentials',
                'mandatory' => true,
                'has_expiry' => false,
                'accepted' => '.pdf',
                'desc' => 'Reference on university letterhead from professor / HOD'
            ],
            'app_lor_2' => [
                'doc_key' => 'app_lor_2',
                'doc_label' => 'Letter of Recommendation 2 (Professional / Academic LOR)',
                'category' => 'Application Essentials',
                'mandatory' => true,
                'has_expiry' => false,
                'accepted' => '.pdf',
                'desc' => 'Second reference from faculty member or current employer'
            ]
        ]
    ],
    'identity' => [
        'id' => 'identity',
        'title' => 'Identity & Financials',
        'icon' => 'fa-passport',
        'badge' => 'Visa & ID',
        'color' => '#d97706',
        'desc' => 'Government identity and financial evidence for CAS, I-20, and visa filing.',
        'docs' => [
            'id_passport' => [
                'doc_key' => 'id_passport',
                'doc_label' => 'Passport (Front & Back Pages)',
                'category' => 'Identity & Financials',
                'mandatory' => true,
                'has_expiry' => true,
                'expiry_type' => 'passport',
                'accepted' => '.pdf,.jpg,.jpeg,.png',
                'desc' => 'Color scan of photo page, signature page, and address page'
            ],
            'id_bank_statement' => [
                'doc_key' => 'id_bank_statement',
                'doc_label' => 'Bank Statement (Last 6 Months)',
                'category' => 'Identity & Financials',
                'mandatory' => true,
                'has_expiry' => false,
                'accepted' => '.pdf',
                'desc' => 'Official bank statement with branch seal & stamp showing required proof of funds'
            ],
            'id_affidavit' => [
                'doc_key' => 'id_affidavit',
                'doc_label' => 'Affidavit of Support / Financial Undertaking',
                'category' => 'Identity & Financials',
                'mandatory' => false,
                'has_expiry' => false,
                'accepted' => '.pdf',
                'desc' => 'Notarized sponsorship affidavit and relationship proof (if funded by parents/sponsor)'
            ]
        ]
    ]
];

// Helper: Calculate Expiry Alerts against Course Intake
function calculate_doc_expiry_alerts(array $docsByKey, string $targetIntakeStr): array {
    $alerts = [];

    // Parse target intake (e.g. "Sep 2026", "Feb 2027", "2026-09-01")
    $intakeDate = null;
    $targetIntakeClean = trim($targetIntakeStr);
    if (!empty($targetIntakeClean)) {
        $ts = strtotime('1 ' . $targetIntakeClean);
        if (!$ts) {
            $ts = strtotime($targetIntakeClean);
        }
        if ($ts) {
            $intakeDate = date('Y-m-d', $ts);
        }
    }
    if (!$intakeDate) {
        $intakeDate = '2026-09-01'; // Default Fall 2026
    }
    $intakeTs = strtotime($intakeDate);
    $intakeDisplay = date('M Y', $intakeTs);

    // 1. Passport Expiry Check
    if (isset($docsByKey['id_passport']) && !empty($docsByKey['id_passport']['expiry_date'])) {
        $expDate = $docsByKey['id_passport']['expiry_date'];
        $expTs = strtotime($expDate);
        $expDisplay = date('d M Y', $expTs);

        if ($expTs < $intakeTs) {
            $alerts[] = [
                'type' => 'critical',
                'doc_key' => 'id_passport',
                'doc_title' => 'Passport (Front & Back Pages)',
                'expiry_date' => $expDate,
                'expiry_display' => $expDisplay,
                'intake_display' => $intakeDisplay,
                'title' => 'Critical Expiry Alert: Passport Expires Before Course Intake',
                'message' => "Your passport expires on <strong>{$expDisplay}</strong>, which is <strong>BEFORE</strong> your target course intake date (<strong>{$intakeDisplay}</strong>). International immigration authorities (UKVI, Australia DHA, US Embassy) require passports to be valid for at least 6 months past your course commencement date. Please initiate passport renewal at your regional Passport Seva Kendra immediately to prevent CAS or visa rejection.",
                'action_label' => 'Renew Passport & Re-upload',
                'action_key' => 'id_passport'
            ];
        } elseif ($expTs < strtotime('+6 months', $intakeTs)) {
            $alerts[] = [
                'type' => 'warning',
                'doc_key' => 'id_passport',
                'doc_title' => 'Passport (Front & Back Pages)',
                'expiry_date' => $expDate,
                'expiry_display' => $expDisplay,
                'intake_display' => $intakeDisplay,
                'title' => 'Passport Validity Warning: Less than 6 Months from Intake',
                'message' => "Your passport expires on <strong>{$expDisplay}</strong>. Student visas generally require minimum 6 months validity past your program commencement ({$intakeDisplay}). We strongly advise renewing your passport early to avoid biometric appointment delays.",
                'action_label' => 'Upload Renewed Passport',
                'action_key' => 'id_passport'
            ];
        }
    }

    // 2. English Scorecard Expiry Check
    if (isset($docsByKey['test_english']) && !empty($docsByKey['test_english']['expiry_date'])) {
        $expDate = $docsByKey['test_english']['expiry_date'];
        $expTs = strtotime($expDate);
        $expDisplay = date('d M Y', $expTs);

        if ($expTs < $intakeTs) {
            $alerts[] = [
                'type' => 'critical',
                'doc_key' => 'test_english',
                'doc_title' => 'English Language Scorecard (IELTS / PTE / TOEFL)',
                'expiry_date' => $expDate,
                'expiry_display' => $expDisplay,
                'intake_display' => $intakeDisplay,
                'title' => 'Critical Expiry Alert: English Test Expires Before Course Intake',
                'message' => "Your English test scorecard expires on <strong>{$expDisplay}</strong>, which is <strong>BEFORE</strong> your course enrollment (<strong>{$intakeDisplay}</strong>). IELTS & PTE test results are strictly valid for 2 years. Universities cannot issue an unconditional offer or CAS with an expired test score. Please retake the test or request an English Medium of Instruction (MOI) waiver.",
                'action_label' => 'Upload Retake Scorecard / MOI Certificate',
                'action_key' => 'test_english'
            ];
        } elseif ($expTs < strtotime('+3 months', $intakeTs)) {
            $alerts[] = [
                'type' => 'warning',
                'doc_key' => 'test_english',
                'doc_title' => 'English Language Scorecard',
                'expiry_date' => $expDate,
                'expiry_display' => $expDisplay,
                'intake_display' => $intakeDisplay,
                'title' => 'English Test Expiry Warning',
                'message' => "Your English test score expires on <strong>{$expDisplay}</strong>, shortly before your intake date ({$intakeDisplay}). Ensure your university CAS and visa application are submitted before this date.",
                'action_label' => 'Update Test Scorecard',
                'action_key' => 'test_english'
            ];
        }
    }

    return $alerts;
}

// ─── Resolve CRM Lead ID (opportunistic) ───
$crmLeadId = null;
try {
    $linkStmt = $pdo->prepare('SELECT crm_lead_id FROM student_crm_links WHERE student_user_id = ? LIMIT 1');
    $linkStmt->execute([$userId]);
    $crmLeadId = $linkStmt->fetchColumn();
} catch (PDOException $e) {}

// Resolve target intake
$targetIntake = 'Sep 2026';
try {
    $appIntakeStmt = $pdo->prepare("SELECT intake FROM student_applications WHERE user_id = ? AND intake IS NOT NULL AND intake != '' ORDER BY id ASC LIMIT 1");
    $appIntakeStmt->execute([$userId]);
    $foundIntake = $appIntakeStmt->fetchColumn();
    if ($foundIntake) {
        $targetIntake = $foundIntake;
    }
} catch (PDOException $e) {}

$requestMethod = $_SERVER['REQUEST_METHOD'] ?? 'GET';

// ─── GET: List Documents, Categories, Stats, and Expiry Alerts ───
if ($requestMethod === 'GET') {
    $action = $_GET['action'] ?? 'list';

    if ($action === 'list') {
        try {
            $data = sync_and_calculate_crm_candidate_documents($pdo, $userId, $crmLeadId);
            echo json_encode($data);
            exit;
        } catch (Exception $e) {
            error_log('Student documents list error: ' . $e->getMessage());
            echo json_encode(['success' => false, 'message' => 'Unable to load candidate documents: ' . $e->getMessage()]);
            exit;
        }
    }

    echo json_encode(['success' => false, 'message' => 'Invalid action.']);
    exit;
}

// ─── POST: Upload / Re-upload & Delete ───
if ($requestMethod === 'POST') {
    $action = $_POST['action'] ?? '';

    // CSRF verification
    $csrfToken = $_POST['csrf_token'] ?? '';
    if (
        !is_string($csrfToken) ||
        empty($_SESSION['community_csrf_token']) ||
        !hash_equals($_SESSION['community_csrf_token'], $csrfToken)
    ) {
        echo json_encode(['success' => false, 'message' => 'Session expired. Please refresh the page and try again.']);
        exit;
    }

    // ─── ACTION: Upload / Re-upload Document ───
    if ($action === 'upload') {
        if (!isset($_FILES['document']) || $_FILES['document']['error'] !== UPLOAD_ERR_OK) {
            $uploadErrors = [
                UPLOAD_ERR_INI_SIZE   => 'File exceeds server configuration limit.',
                UPLOAD_ERR_FORM_SIZE  => 'File exceeds form upload limit.',
                UPLOAD_ERR_PARTIAL    => 'File was only partially uploaded.',
                UPLOAD_ERR_NO_FILE    => 'No document file was selected.',
                UPLOAD_ERR_NO_TMP_DIR => 'Missing temporary folder.',
                UPLOAD_ERR_CANT_WRITE => 'Failed to write document to disk.',
            ];
            $errCode = $_FILES['document']['error'] ?? UPLOAD_ERR_NO_FILE;
            echo json_encode(['success' => false, 'message' => $uploadErrors[$errCode] ?? 'Upload failed.']);
            exit;
        }

        $file = $_FILES['document'];
        $docKey = trim($_POST['doc_key'] ?? '');
        $category = trim($_POST['category'] ?? 'Academic Records');
        $docLabel = trim($_POST['doc_label'] ?? '');
        $expiryDate = !empty($_POST['expiry_date']) ? trim($_POST['expiry_date']) : null;

        if (empty($docKey)) {
            $mapped = map_crm_doc_to_key($docLabel ?: $file['name']);
            $docKey = $mapped['doc_key'];
            if (empty($category) || $category === 'Academic Records') {
                $category = $mapped['category'];
            }
        }
        if (empty($docLabel)) {
            $docLabel = pathinfo($file['name'], PATHINFO_FILENAME);
        }

        // Validate expiry date format if provided
        if ($expiryDate) {
            $parsedTs = strtotime($expiryDate);
            if (!$parsedTs) {
                $expiryDate = null;
            } else {
                $expiryDate = date('Y-m-d', $parsedTs);
            }
        }

        // File size check: 7MB limit
        $maxSize = 7 * 1024 * 1024;
        if ($file['size'] > $maxSize) {
            echo json_encode(['success' => false, 'message' => 'File too large. Maximum allowed size is 7MB.']);
            exit;
        }

        // Allowed file types
        $allowedExts = ['pdf', 'jpg', 'jpeg', 'png', 'webp', 'doc', 'docx', 'xls', 'xlsx'];
        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

        if (!in_array($ext, $allowedExts)) {
            echo json_encode(['success' => false, 'message' => 'Invalid file format. Allowed formats: PDF, Images (JPG, PNG), Word, and Excel.']);
            exit;
        }

        // Size display string
        $fileSize = $file['size'];
        if ($fileSize < 1024) {
            $sizeDisplay = $fileSize . ' B';
        } elseif ($fileSize < 1048576) {
            $sizeDisplay = round($fileSize / 1024, 1) . ' KB';
        } else {
            $sizeDisplay = round($fileSize / 1048576, 1) . ' MB';
        }

        // Ensure target directory exists
        $uploadDir = __DIR__ . '/uploads/documents';
        if (!is_dir($uploadDir)) {
            @mkdir($uploadDir, 0755, true);
        }

        // Safe filename: doc_USERID_TIMESTAMP_FILENAME
        $safeOriginal = preg_replace('/[^a-zA-Z0-9._-]/', '_', $file['name']);
        $storedName = 'doc_' . $userId . '_' . time() . '_' . $safeOriginal;
        $destPath = $uploadDir . '/' . $storedName;

        if (!move_uploaded_file($file['tmp_name'], $destPath)) {
            echo json_encode(['success' => false, 'message' => 'Failed to save uploaded file.']);
            exit;
        }

        $fileUrl = 'uploads/documents/' . $storedName;

        try {
            // Check if slot already exists in student_documents
            $checkStmt = $pdo->prepare('SELECT id, status, admin_notes FROM student_documents WHERE user_id = ? AND doc_key = ? LIMIT 1');
            $checkStmt->execute([$userId, $docKey]);
            $existing = $checkStmt->fetch(PDO::FETCH_ASSOC);

            if ($existing) {
                // Keep note of re-upload if counselor had left a note
                $noteUpdate = $existing['admin_notes'];
                if ($existing['status'] === 'Needs Re-upload') {
                    $noteUpdate = '[Re-uploaded on ' . date('d M Y') . ' — Pending Counselor Verification] Previous feedback: ' . $existing['admin_notes'];
                }

                $updStmt = $pdo->prepare("
                    UPDATE student_documents 
                    SET name = ?, category = ?, doc_label = ?, status = 'Under Review', 
                        size = ?, file_extension = ?, file_url = ?, expiry_date = ?, 
                        admin_notes = ?, uploaded_by = ?, updated_at = NOW()
                    WHERE id = ? AND user_id = ?
                ");
                $updStmt->execute([
                    $file['name'],
                    $category,
                    $docLabel,
                    $sizeDisplay,
                    $ext,
                    $fileUrl,
                    $expiryDate,
                    $noteUpdate,
                    'Student (' . ($_SESSION['user_name'] ?? 'Candidate') . ')',
                    $existing['id'],
                    $userId
                ]);
            } else {
                $insStmt = $pdo->prepare("
                    INSERT INTO student_documents 
                    (user_id, doc_key, doc_label, category, name, status, size, file_extension, file_url, expiry_date, uploaded_by, created_at, updated_at)
                    VALUES (?, ?, ?, ?, ?, 'Under Review', ?, ?, ?, ?, ?, NOW(), NOW())
                ");
                $insStmt->execute([
                    $userId,
                    $docKey,
                    $docLabel,
                    $category,
                    $file['name'],
                    $sizeDisplay,
                    $ext,
                    $fileUrl,
                    $expiryDate,
                    'Student (' . ($_SESSION['user_name'] ?? 'Candidate') . ')'
                ]);
            }

            // Create in-app student notification
            try {
                $notifStmt = $pdo->prepare("
                    INSERT INTO notifications (user_id, title, message, type, category, link)
                    VALUES (?, ?, ?, 'info', 'document', 'student-dashboard.php?tab=documents')
                ");
                $notifStmt->execute([
                    $userId,
                    'Document Submitted: ' . $docLabel,
                    'Your document "' . $file['name'] . '" was uploaded successfully and is now Under Review by your counseling advisor.'
                ]);
            } catch (Exception $ne) {}

            // Execute Dynamic CRM Document Submission Flow Engine
            $isReupload = ($existing && in_array($existing['status'] ?? '', ['Needs Re-upload', 'Rejected']));
            $flowSummary = execute_crm_document_submission_flow($pdo, $userId, [
                'doc_key' => $docKey,
                'doc_label' => $docLabel,
                'category' => $category,
                'file_name' => $file['name'],
                'file_url' => $fileUrl,
                'file_size' => $sizeDisplay,
                'file_extension' => $ext,
                'expiry_date' => $expiryDate,
                'is_reupload' => $isReupload,
                'previous_note' => $existing['admin_notes'] ?? ''
            ]);

            // Synchronize and re-calculate real dynamic stats
            $syncResult = sync_and_calculate_crm_candidate_documents($pdo, $userId, $crmLeadId);

            $successMsg = 'Document submitted successfully! ';
            if (!empty($flowSummary['counselor_notified'])) {
                $successMsg .= 'Counselor ' . $flowSummary['counselor_name'] . ' has been alerted to verify your file.';
            } else {
                $successMsg .= 'It is now Under Review with your counselor.';
            }

            echo json_encode([
                'success' => true,
                'message' => $successMsg,
                'doc_key' => $docKey,
                'file_name' => $file['name'],
                'file_url' => $fileUrl,
                'status' => 'Under Review',
                'flow' => $flowSummary,
                'stats' => $syncResult['stats'] ?? null
            ]);
            exit;
        } catch (PDOException $e) {
            error_log('Document save error: ' . $e->getMessage());
            echo json_encode(['success' => false, 'message' => 'Database error while saving document.']);
            exit;
        }
    }

    // ─── ACTION: Delete Document ───
    if ($action === 'delete') {
        $docId = (int) ($_POST['doc_id'] ?? 0);
        $docKey = trim($_POST['doc_key'] ?? '');

        if ($docId <= 0 && empty($docKey)) {
            echo json_encode(['success' => false, 'message' => 'Valid document ID or key is required.']);
            exit;
        }

        try {
            $query = $docId > 0
                ? 'SELECT * FROM student_documents WHERE id = ? AND user_id = ? LIMIT 1'
                : 'SELECT * FROM student_documents WHERE doc_key = ? AND user_id = ? LIMIT 1';
            $params = $docId > 0 ? [$docId, $userId] : [$docKey, $userId];

            $checkStmt = $pdo->prepare($query);
            $checkStmt->execute($params);
            $doc = $checkStmt->fetch(PDO::FETCH_ASSOC);

            if (!$doc) {
                echo json_encode(['success' => false, 'message' => 'Document record not found.']);
                exit;
            }

            if (strtolower($doc['status'] ?? '') === 'verified') {
                echo json_encode(['success' => false, 'message' => 'Verified documents cannot be deleted. Contact your counselor if changes are needed.']);
                exit;
            }

            // Remove physical file if exists
            if (!empty($doc['file_url'])) {
                $physicalPath = __DIR__ . '/' . $doc['file_url'];
                if (file_exists($physicalPath)) {
                    @unlink($physicalPath);
                }
            }

            // Reset slot or delete custom record in student_documents
            if (strpos($docKey, 'custom_') === 0 || strpos($docKey, 'doc_') === 0 || strpos($docKey, 'adm_') === 0 || strpos($docKey, 'med_') === 0 || strpos($docKey, 'exp_') === 0 || strpos($docKey, 'fin_') === 0) {
                $delStmt = $pdo->prepare("DELETE FROM student_documents WHERE id = ? AND user_id = ?");
                $delStmt->execute([$doc['id'], $userId]);
            } else {
                $delStmt = $pdo->prepare("
                    UPDATE student_documents 
                    SET file_url = NULL, name = 'Not Uploaded', size = '0 KB', 
                        status = 'Under Review', admin_notes = NULL, updated_at = NOW() 
                    WHERE id = ? AND user_id = ?
                ");
                $delStmt->execute([$doc['id'], $userId]);
            }

            // Sync with CRM after deletion
            $syncResult = sync_and_calculate_crm_candidate_documents($pdo, $userId, $crmLeadId);

            echo json_encode([
                'success' => true,
                'message' => 'Document removed successfully.',
                'stats' => $syncResult['stats'] ?? null
            ]);
            exit;
        } catch (PDOException $e) {
            error_log('Document delete error: ' . $e->getMessage());
            echo json_encode(['success' => false, 'message' => 'Failed to delete document.']);
            exit;
        }
    }

    echo json_encode(['success' => false, 'message' => 'Invalid action specified.']);
    exit;
}

http_response_code(405);
echo json_encode(['success' => false, 'message' => 'Method not allowed.']);
