<?php
/**
 * Dynamic CRM Document Submission & Verification Workflow Engine
 * 
 * Handles:
 *   1. Automatic Lead Pipeline Progression (leads.overseas_pipeline_status, primary_status, secondary_status)
 *   2. Document Completeness & Milestone Detection (Identity, Academic, English Tests, Essentials)
 *   3. Automated Counselor Assignment & Dispatch of Action Notifications (bgoi_notifications)
 *   4. Risk & Compliance Assessment (Passport / IELTS validity vs course intake)
 *   5. Structured Audit Trail Logging (leads.remarks, remarks_history, lead_remarks_messages)
 *   6. Dynamic Verification Decisions (Counselor approval / rejection with feedback notes)
 */

if (!function_exists('execute_crm_document_submission_flow')) {
    /**
     * Executes the dynamic document submission flow in CRM
     * 
     * @param PDO $pdo Website database connection
     * @param int $userId Student user ID
     * @param array $docParams Document details (doc_key, doc_label, category, file_name, file_url, size, expiry_date, is_reupload, previous_note)
     * @return array Flow execution summary
     */
    function execute_crm_document_submission_flow(PDO $pdo, int $userId, array $docParams): array
    {
        $flowResult = [
            'success' => true,
            'crm_linked' => false,
            'lead_id' => null,
            'counselor_notified' => false,
            'counselor_name' => 'Counseling Desk',
            'pipeline_status' => 'Documentation In Progress',
            'primary_status' => 'Documents Collecting',
            'secondary_status' => 'Under Review',
            'completeness_percentage' => 0,
            'uploaded_count' => 0,
            'mandatory_count' => 10,
            'expiry_conflict' => false,
            'milestones' => []
        ];

        // 1. Resolve CRM Lead Link
        $crmLeadId = null;
        try {
            $linkStmt = $pdo->prepare('SELECT crm_lead_id FROM student_crm_links WHERE student_user_id = ? LIMIT 1');
            $linkStmt->execute([$userId]);
            $crmLeadId = (int) $linkStmt->fetchColumn();
        } catch (PDOException $e) {}

        if ($crmLeadId <= 0) {
            return $flowResult;
        }

        $flowResult['crm_linked'] = true;
        $flowResult['lead_id'] = $crmLeadId;

        // 2. Fetch Lead & Counselor Details from CRM
        try {
            $crmPdo = get_central_crm_pdo();
            $leadStmt = $crmPdo->prepare('SELECT * FROM leads WHERE id = ? AND is_active = 1 LIMIT 1');
            $leadStmt->execute([$crmLeadId]);
            $lead = $leadStmt->fetch(PDO::FETCH_ASSOC);

            if (!$lead) {
                return $flowResult;
            }

            $counselorId = (int) ($lead['assigned_to'] ?? 0);
            $counselorName = !empty($lead['assigned_to_name']) ? $lead['assigned_to_name'] : 'Assigned Advisor';
            $studentName = !empty($lead['student_name']) ? $lead['student_name'] : ($_SESSION['user_name'] ?? 'Student');
            $targetIntake = !empty($lead['intake']) ? $lead['intake'] : 'Sep 2026';
            $flowResult['counselor_name'] = $counselorName;

            // 3. Document Sync to CRM `lead_documents`
            $docLabel = $docParams['doc_label'] ?? 'Document';
            $fileName = $docParams['file_name'] ?? 'document.pdf';
            $fileUrl = $docParams['file_url'] ?? '';
            $docKey = $docParams['doc_key'] ?? '';
            $category = $docParams['category'] ?? 'Academic Records';
            $expiryDate = !empty($docParams['expiry_date']) ? $docParams['expiry_date'] : null;
            $isReupload = !empty($docParams['is_reupload']);
            $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

            $crmDocStmt = $crmPdo->prepare("
                SELECT id FROM lead_documents 
                WHERE lead_id = ? AND document_name = ? 
                LIMIT 1
            ");
            $crmDocStmt->execute([$crmLeadId, $docLabel]);
            $existingCrmDoc = $crmDocStmt->fetch(PDO::FETCH_ASSOC);

            $uploadedBy = "Student Portal ({$studentName})";
            if ($existingCrmDoc) {
                $updCrmDoc = $crmPdo->prepare("
                    UPDATE lead_documents 
                    SET file_name = ?, file_path = ?, file_type = ?, status = 'Uploaded', 
                        uploaded_by = ?, uploaded_at = NOW() 
                    WHERE id = ?
                ");
                $updCrmDoc->execute([
                    $fileName,
                    $fileUrl,
                    'application/' . $fileExt,
                    $uploadedBy,
                    $existingCrmDoc['id']
                ]);
            } else {
                $insCrmDoc = $crmPdo->prepare("
                    INSERT INTO lead_documents 
                    (lead_id, document_name, file_name, file_path, file_type, status, uploaded_by, uploaded_at) 
                    VALUES (?, ?, ?, ?, ?, 'Uploaded', ?, NOW())
                ");
                $insCrmDoc->execute([
                    $crmLeadId,
                    $docLabel,
                    $fileName,
                    $fileUrl,
                    'application/' . $fileExt,
                    $uploadedBy
                ]);
            }

            // 4. Calculate Dynamic Document Completeness & Milestones
            $localDocsStmt = $pdo->prepare('SELECT doc_key, status, expiry_date FROM student_documents WHERE user_id = ? AND file_url IS NOT NULL AND file_url != ""');
            $localDocsStmt->execute([$userId]);
            $userDocs = $localDocsStmt->fetchAll(PDO::FETCH_ASSOC);

            $uploadedKeys = array_column($userDocs, 'doc_key');
            if (!in_array($docKey, $uploadedKeys)) {
                $uploadedKeys[] = $docKey;
            }

            $mandatoryKeys = [
                'academic_10th', 'academic_12th', 'academic_ug', 'academic_degree',
                'test_english', 'app_sop', 'app_cv', 'app_lor_1', 'app_lor_2', 'id_passport', 'id_bank_statement'
            ];
            $uploadedMandatory = array_intersect($mandatoryKeys, $uploadedKeys);
            $uploadedCount = count($uploadedMandatory);
            $mandatoryCount = count($mandatoryKeys);
            $completenessPct = round(($uploadedCount / $mandatoryCount) * 100);

            $flowResult['uploaded_count'] = $uploadedCount;
            $flowResult['mandatory_count'] = $mandatoryCount;
            $flowResult['completeness_percentage'] = $completenessPct;

            // Milestones Detection
            $hasIdentityAndLanguage = in_array('id_passport', $uploadedKeys) && in_array('test_english', $uploadedKeys);
            $hasAcademics = in_array('academic_10th', $uploadedKeys) && in_array('academic_12th', $uploadedKeys) && in_array('academic_ug', $uploadedKeys);
            $hasEssentials = in_array('app_sop', $uploadedKeys) && in_array('app_cv', $uploadedKeys) && in_array('app_lor_1', $uploadedKeys);
            $allMandatoryUploaded = ($uploadedCount >= $mandatoryCount);

            if ($hasIdentityAndLanguage) $flowResult['milestones'][] = 'Identity & Language Eligibility Verified';
            if ($hasAcademics) $flowResult['milestones'][] = 'Academic Transcripts Complete';
            if ($hasEssentials) $flowResult['milestones'][] = 'Application Dossier Complete';
            if ($allMandatoryUploaded) $flowResult['milestones'][] = 'All Mandatory Documents Uploaded';

            // 5. Dynamic Lead Pipeline Progression
            $newPipelineStatus = $lead['overseas_pipeline_status'] ?: 'Documentation In Progress';
            $newPrimaryStatus = $lead['primary_status'] ?: 'Documents Collecting';
            $newSecondaryStatus = 'Under Counselor Review';

            if ($allMandatoryUploaded) {
                $newPipelineStatus = 'Documents Complete - Ready for University Submission';
                $newPrimaryStatus = 'Documentation Completed';
                $newSecondaryStatus = 'Filing Clearance Pending';
            } elseif ($hasAcademics && $hasIdentityAndLanguage) {
                $newPipelineStatus = 'Under Document Verification';
                $newPrimaryStatus = 'Documentation In Progress';
                $newSecondaryStatus = 'Counselor Document Review Pending';
            } else {
                $newPipelineStatus = 'Documentation In Progress';
                $newPrimaryStatus = 'Documents Collecting';
                $newSecondaryStatus = "Partial Documents Uploaded ({$uploadedCount}/{$mandatoryCount})";
            }

            // Sync Passport number to CRM leads table if passport was uploaded
            $passportUpdateSql = "";
            $passportUpdateParams = [];
            if ($docKey === 'id_passport' && !empty($docParams['passport_number'])) {
                $passportUpdateSql = ", passport_no = ?";
                $passportUpdateParams[] = trim($docParams['passport_number']);
            }

            // 6. Dynamic Expiry & Compliance Assessment
            $complianceAlertRemark = "";
            if (in_array($docKey, ['id_passport', 'test_english']) && $expiryDate) {
                $intakeTs = strtotime('1 ' . $targetIntake);
                if (!$intakeTs) $intakeTs = strtotime('2026-09-01');
                $expTs = strtotime($expiryDate);

                if ($expTs < $intakeTs) {
                    $flowResult['expiry_conflict'] = true;
                    $newSecondaryStatus = "ALERT: {$docLabel} Expiry Conflict";
                    $complianceAlertRemark = "\n⚠️ [COMPLIANCE ALERT - ACTION REQUIRED] Student's {$docLabel} expires on " . date('d M Y', $expTs) . ", which is PRIOR to target intake {$targetIntake}. Visa regulations require min 6 months validity. Counselor must advise student on immediate renewal or test retake.";
                }
            }

            $flowResult['pipeline_status'] = $newPipelineStatus;
            $flowResult['primary_status'] = $newPrimaryStatus;
            $flowResult['secondary_status'] = $newSecondaryStatus;

            // 7. Dispatch Action Notification to Counselor (`bgoi_notifications`)
            if ($counselorId > 0) {
                try {
                    $notifTitle = $isReupload
                        ? "Re-uploaded Document: {$docLabel} - {$studentName}"
                        : "New Document: {$docLabel} - {$studentName}";

                    $notifMessage = $isReupload
                        ? "Student {$studentName} has re-uploaded {$docLabel} ({$fileName}) following your feedback note. Please re-verify."
                        : "Student {$studentName} has submitted {$docLabel} ({$fileName}) in [{$category}]. Total documentation progress: {$uploadedCount}/{$mandatoryCount} ({$completenessPct}%). Action: Verify document authenticity.";

                    $notifBadge = $isReupload ? 'Re-uploaded' : ($flowResult['expiry_conflict'] ? 'Action Required' : 'Pending Review');
                    $notifType = $flowResult['expiry_conflict'] ? 'warning' : 'document';

                    $insNotif = $crmPdo->prepare("
                        INSERT INTO bgoi_notifications 
                        (user_id, title, message, type, service_type, badge, is_read, created_at)
                        VALUES (?, ?, ?, ?, 'overseas_documents', ?, 0, NOW())
                    ");
                    $insNotif->execute([
                        $counselorId,
                        $notifTitle,
                        $notifMessage,
                        $notifType,
                        $notifBadge
                    ]);
                    $flowResult['counselor_notified'] = true;
                } catch (Exception $ne) {
                    error_log('CRM counselor notification error: ' . $ne->getMessage());
                }
            }

            // 8. Structured Audit Trail in CRM `leads.remarks` & `remarks_history`
            $oldRemarks = $lead['remarks'] ?? '';
            $flowActionTag = $isReupload ? 'RE-UPLOADED' : 'SUBMITTED';
            $auditLog = "[STUDENT PORTAL DOCUMENT FLOW — " . date('d M Y H:i') . "]\n"
                . "• Action: {$flowActionTag} {$docLabel} ({$fileName})\n"
                . "• Stage: {$category}\n"
                . "• Progress: {$uploadedCount}/{$mandatoryCount} Mandatory Documents ({$completenessPct}%)\n"
                . "• Pipeline Status: {$newPipelineStatus}\n"
                . "• Counselor Assigned: {$counselorName}" . ($flowResult['counselor_notified'] ? " (Alert Dispatched)" : "")
                . $complianceAlertRemark;

            // Update lead record
            $updateLeadSql = "
                UPDATE leads 
                SET overseas_pipeline_status = ?, 
                    primary_status = ?, 
                    secondary_status = ?, 
                    remarks = CONCAT(?, '\n\n', COALESCE(remarks, '')),
                    updated_at = NOW()
                    {$passportUpdateSql}
                WHERE id = ? AND is_active = 1
            ";
            $updateLeadParams = array_merge([
                $newPipelineStatus,
                $newPrimaryStatus,
                $newSecondaryStatus,
                $auditLog
            ], $passportUpdateParams, [$crmLeadId]);

            $crmPdo->prepare($updateLeadSql)->execute($updateLeadParams);

            // Insert into `remarks_history`
            try {
                $insHist = $crmPdo->prepare("
                    INSERT INTO remarks_history 
                    (lead_id, old_remarks, new_remarks, changed_by, changed_at)
                    VALUES (?, ?, ?, 'Student Portal Auto-Flow', NOW())
                ");
                $insHist->execute([
                    $crmLeadId,
                    $oldRemarks,
                    $auditLog
                ]);
            } catch (Exception $he) {}

            // Insert into `lead_remarks_messages`
            try {
                $refId = 'DOC-' . strtoupper(substr(md5($crmLeadId . $docKey . time()), 0, 8));
                $insMsg = $crmPdo->prepare("
                    INSERT INTO lead_remarks_messages 
                    (lead_id, ref_id, subject, description, primary_recipient_id, sent_status, created_by, created_at)
                    VALUES (?, ?, ?, ?, ?, 'SENT', 'Student Portal Flow Engine', NOW())
                ");
                $insMsg->execute([
                    $crmLeadId,
                    $refId,
                    "[DOCUMENT FLOW] {$docLabel} Submitted for Verification",
                    $auditLog,
                    $counselorId
                ]);
            } catch (Exception $me) {}

        } catch (Exception $e) {
            error_log('CRM document submission flow error: ' . $e->getMessage());
            $flowResult['success'] = false;
            $flowResult['error'] = $e->getMessage();
        }

        return $flowResult;
    }
}

if (!function_exists('execute_crm_document_verification_flow')) {
    /**
     * Executes the counselor verification decision flow (Approve or Reject with Feedback)
     * 
     * @param PDO $pdo Website database connection
     * @param int $leadId CRM lead ID
     * @param string $docKey Document key (e.g. id_passport, test_english)
     * @param string $decision 'Verified' or 'Needs Re-upload'
     * @param string $counselorName Name of counselor making the decision
     * @param string $counselorNotes Specific feedback or guidance note
     * @return array Verification result
     */
    function execute_crm_document_verification_flow(
        PDO $pdo,
        int $leadId,
        string $docKey,
        string $decision,
        string $counselorName,
        string $counselorNotes = ''
    ): array {
        $result = ['success' => false, 'message' => ''];

        try {
            // Find student user linked to this CRM lead
            $linkStmt = $pdo->prepare('SELECT student_user_id FROM student_crm_links WHERE crm_lead_id = ? LIMIT 1');
            $linkStmt->execute([$leadId]);
            $userId = (int) $linkStmt->fetchColumn();

            $statusVal = ($decision === 'Verified') ? 'Verified' : 'Needs Re-upload';

            // 1. Update local student_documents
            if ($userId > 0) {
                $updDoc = $pdo->prepare("
                    UPDATE student_documents 
                    SET status = ?, admin_notes = ?, verified_by = ?, updated_at = NOW()
                    WHERE user_id = ? AND doc_key = ?
                ");
                $updDoc->execute([$statusVal, $counselorNotes, $counselorName, $userId, $docKey]);

                // Create student in-app notification
                $notifType = ($decision === 'Verified') ? 'success' : 'warning';
                $notifTitle = ($decision === 'Verified')
                    ? "Document Verified: {$docKey}"
                    : "Action Required: Re-upload {$docKey}";
                $notifMsg = ($decision === 'Verified')
                    ? "Your document ({$docKey}) was reviewed and verified by counselor {$counselorName}."
                    : "Counselor {$counselorName} has requested a re-upload for {$docKey}. Note: \"{$counselorNotes}\"";

                try {
                    $insNotif = $pdo->prepare("
                        INSERT INTO notifications (user_id, title, message, type, category, link)
                        VALUES (?, ?, ?, ?, 'document', 'student-dashboard.php?tab=documents')
                    ");
                    $insNotif->execute([$userId, $notifTitle, $notifMsg, $notifType]);
                } catch (Exception $ne) {}
            }

            // 2. Update CRM `lead_documents`
            $crmPdo = get_central_crm_pdo();
            $crmStatus = ($decision === 'Verified') ? 'Verified' : 'Rejected';
            $updCrm = $crmPdo->prepare("
                UPDATE lead_documents 
                SET status = ?, verified_by = ?, verified_at = NOW()
                WHERE lead_id = ? AND (document_name LIKE ? OR file_name LIKE ?)
            ");
            $updCrm->execute([$crmStatus, $counselorName, $leadId, "%{$docKey}%", "%{$docKey}%"]);

            // 3. Log Audit in CRM `leads.remarks`
            $auditNote = "[COUNSELOR VERIFICATION DECISION — " . date('d M Y H:i') . "]\n"
                . "• Document Key: {$docKey}\n"
                . "• Decision: {$statusVal}\n"
                . "• Verified By: {$counselorName}\n"
                . (!empty($counselorNotes) ? "• Feedback Note: \"{$counselorNotes}\"\n" : "")
                . "• Student Portal Updated: Yes";

            $crmPdo->prepare("
                UPDATE leads 
                SET remarks = CONCAT(?, '\n\n', COALESCE(remarks, '')), updated_at = NOW()
                WHERE id = ? AND is_active = 1
            ")->execute([$auditNote, $leadId]);

            $result['success'] = true;
            $result['message'] = "Document marked as {$statusVal} successfully.";
        } catch (Exception $e) {
            error_log('Counselor verification flow error: ' . $e->getMessage());
            $result['message'] = $e->getMessage();
        }

        return $result;
    }
}

if (!function_exists('calculate_doc_expiry_alerts')) {
    /**
     * Calculates compliance expiry alerts for Passport and English Language Scorecards
     */
    function calculate_doc_expiry_alerts(array $docsByKey, string $targetIntake): array
    {
        $alerts = [];
        $intakeTs = strtotime('1 ' . $targetIntake);
        if (!$intakeTs) $intakeTs = strtotime('2026-09-01');
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
                    'doc_title' => 'International Passport',
                    'expiry_date' => $expDate,
                    'expiry_display' => $expDisplay,
                    'intake_display' => $intakeDisplay,
                    'title' => 'Critical Expiry Alert: Passport Expires Before Target Intake',
                    'message' => "Your passport expires on <strong>{$expDisplay}</strong>, which is <strong>BEFORE</strong> your target course enrollment date (<strong>{$intakeDisplay}</strong>). Embassy and immigration rules mandate that passports must be valid for at least 6 months beyond your course commencement date. Please apply for a passport renewal immediately.",
                    'action_label' => 'Upload Renewed Passport',
                    'action_key' => 'id_passport'
                ];
            } elseif ($expTs < strtotime('+6 months', $intakeTs)) {
                $alerts[] = [
                    'type' => 'warning',
                    'doc_key' => 'id_passport',
                    'doc_title' => 'International Passport',
                    'expiry_date' => $expDate,
                    'expiry_display' => $expDisplay,
                    'intake_display' => $intakeDisplay,
                    'title' => 'Passport Validity Warning',
                    'message' => "Your passport expires on <strong>{$expDisplay}</strong>. While valid for your intake date ({$intakeDisplay}), many embassies require at least 6 months validity at visa issuance. Contact your counselor to confirm if a renewal is advised.",
                    'action_label' => 'Update Passport Details',
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
}

if (!function_exists('map_crm_doc_to_key')) {
    /**
     * Maps Central CRM document names to standard categories and keys
     */
    function map_crm_doc_to_key(string $crmDocName): array {
        $normalized = strtolower(trim($crmDocName));
        
        // Academic Records
        if (strpos($normalized, '10th') !== false || strpos($normalized, 'sslc') !== false || strpos($normalized, 'secondary') !== false) {
            return ['doc_key' => 'academic_10th', 'doc_label' => '10th Standard / Secondary Marksheet', 'category' => 'Academic Records', 'stage' => 'academic', 'mandatory' => true];
        }
        if (strpos($normalized, '12th') !== false || strpos($normalized, 'hsc') !== false || strpos($normalized, 'higher secondary') !== false) {
            return ['doc_key' => 'academic_12th', 'doc_label' => '12th / Higher Secondary Marksheet', 'category' => 'Academic Records', 'stage' => 'academic', 'mandatory' => true];
        }
        if (strpos($normalized, 'ug individual') !== false || strpos($normalized, 'semester') !== false || strpos($normalized, 'transcript') !== false || strpos($normalized, 'mark sheet (educational') !== false) {
            return ['doc_key' => 'academic_ug', 'doc_label' => 'Undergrad Marksheets / Transcripts', 'category' => 'Academic Records', 'stage' => 'academic', 'mandatory' => true];
        }
        if (strpos($normalized, 'degree') !== false || strpos($normalized, 'coursecompletion') !== false) {
            return ['doc_key' => 'academic_degree', 'doc_label' => 'Degree / Course Completion Certificate', 'category' => 'Academic Records', 'stage' => 'academic', 'mandatory' => true];
        }
        if (strpos($normalized, 'provisional') !== false) {
            return ['doc_key' => 'academic_provisional', 'doc_label' => 'Provisional Certificate', 'category' => 'Academic Records', 'stage' => 'academic', 'mandatory' => false];
        }
        if (strpos($normalized, 'consolidated') !== false) {
            return ['doc_key' => 'academic_consolidated', 'doc_label' => 'Consolidated Marksheet', 'category' => 'Academic Records', 'stage' => 'academic', 'mandatory' => false];
        }
        if (strpos($normalized, 'transfer') !== false || strpos($normalized, ' tc') !== false) {
            return ['doc_key' => 'academic_transfer', 'doc_label' => 'Transfer Certificate', 'category' => 'Academic Records', 'stage' => 'academic', 'mandatory' => false];
        }

        // English & Standardized Tests
        if (strpos($normalized, 'ielts') !== false || strpos($normalized, 'pte') !== false || strpos($normalized, 'toefl') !== false || strpos($normalized, 'english') !== false) {
            return ['doc_key' => 'test_english', 'doc_label' => 'English Language Scorecard (IELTS / PTE)', 'category' => 'English & Standardized Tests', 'stage' => 'tests', 'mandatory' => true, 'has_expiry' => true];
        }
        if (strpos($normalized, 'gre') !== false || strpos($normalized, 'gmat') !== false) {
            return ['doc_key' => 'test_standardized', 'doc_label' => 'GRE / GMAT Scorecard', 'category' => 'English & Standardized Tests', 'stage' => 'tests', 'mandatory' => false, 'has_expiry' => true];
        }
        if (strpos($normalized, 'moi') !== false) {
            return ['doc_key' => 'test_moi', 'doc_label' => 'Medium of Instruction (MOI) Certificate', 'category' => 'English & Standardized Tests', 'stage' => 'tests', 'mandatory' => false];
        }

        // Application Essentials
        if (strpos($normalized, 'sop') !== false || strpos($normalized, 'statement of purpose') !== false) {
            return ['doc_key' => 'app_sop', 'doc_label' => 'Statement of Purpose (SOP)', 'category' => 'Application Essentials', 'stage' => 'essentials', 'mandatory' => true];
        }
        if (strpos($normalized, 'resume') !== false || strpos($normalized, 'cv') !== false) {
            return ['doc_key' => 'app_cv', 'doc_label' => 'Updated CV / Academic Resume', 'category' => 'Application Essentials', 'stage' => 'essentials', 'mandatory' => true];
        }
        if (strpos($normalized, 'lor 1') !== false || strpos($normalized, 'lor1') !== false || $normalized === 'lor') {
            return ['doc_key' => 'app_lor_1', 'doc_label' => 'Letter of Recommendation 1 (Academic LOR)', 'category' => 'Application Essentials', 'stage' => 'essentials', 'mandatory' => true];
        }
        if (strpos($normalized, 'lor 2') !== false || strpos($normalized, 'lor2') !== false) {
            return ['doc_key' => 'app_lor_2', 'doc_label' => 'Letter of Recommendation 2 (Professional LOR)', 'category' => 'Application Essentials', 'stage' => 'essentials', 'mandatory' => true];
        }

        // Identity & Financials
        if (strpos($normalized, 'passport') !== false) {
            return ['doc_key' => 'id_passport', 'doc_label' => 'Passport (Front & Back Pages)', 'category' => 'Identity & Financials', 'stage' => 'identity', 'mandatory' => true, 'has_expiry' => true];
        }
        if (strpos($normalized, 'bank statement') !== false || strpos($normalized, 'sanction') !== false) {
            return ['doc_key' => 'id_bank_statement', 'doc_label' => 'Bank Statement / Proof of Funds', 'category' => 'Identity & Financials', 'stage' => 'identity', 'mandatory' => true];
        }
        if (strpos($normalized, 'bank cover') !== false || strpos($normalized, 'disbursement') !== false) {
            return ['doc_key' => 'id_bank_cover', 'doc_label' => 'Bank Cover Letter / Disbursement', 'category' => 'Identity & Financials', 'stage' => 'identity', 'mandatory' => false];
        }
        if (strpos($normalized, 'affidavit') !== false) {
            return ['doc_key' => 'id_affidavit', 'doc_label' => 'Affidavit of Support', 'category' => 'Identity & Financials', 'stage' => 'identity', 'mandatory' => false];
        }
        if (strpos($normalized, 'aadhar') !== false || strpos($normalized, 'national id') !== false) {
            $slug = preg_replace('/[^a-z0-9]/', '', $normalized);
            return ['doc_key' => 'id_' . $slug, 'doc_label' => $crmDocName, 'category' => 'Identity & Financials', 'stage' => 'identity', 'mandatory' => false];
        }

        // Additional / Visa / Offer Documents
        if (strpos($normalized, 'offer') !== false) {
            return ['doc_key' => 'adm_offer_letter', 'doc_label' => 'Official University Offer Letter', 'category' => 'Additional & CRM Documents', 'stage' => 'additional', 'mandatory' => false];
        }
        if (strpos($normalized, 'cas') !== false || strpos($normalized, 'i-20') !== false) {
            return ['doc_key' => 'adm_cas', 'doc_label' => 'CAS / I-20 Document', 'category' => 'Additional & CRM Documents', 'stage' => 'additional', 'mandatory' => false];
        }
        if (strpos($normalized, 'visa') !== false) {
            return ['doc_key' => 'visa_application', 'doc_label' => 'Visa Application / Decision', 'category' => 'Additional & CRM Documents', 'stage' => 'additional', 'mandatory' => false];
        }
        if (strpos($normalized, 'tt') !== false || strpos($normalized, 'wire') !== false) {
            return ['doc_key' => 'fin_tt_copy', 'doc_label' => 'Fee Payment Receipt / TT Copy', 'category' => 'Additional & CRM Documents', 'stage' => 'additional', 'mandatory' => false];
        }
        if (strpos($normalized, 'medical') !== false || strpos($normalized, 'tb') !== false) {
            return ['doc_key' => 'med_certificate', 'doc_label' => 'Medical / TB Test Certificate', 'category' => 'Additional & CRM Documents', 'stage' => 'additional', 'mandatory' => false];
        }
        if (strpos($normalized, 'experience') !== false || strpos($normalized, 'gap') !== false) {
            return ['doc_key' => 'exp_letter', 'doc_label' => 'Experience Letter / Gap Justification', 'category' => 'Additional & CRM Documents', 'stage' => 'additional', 'mandatory' => false];
        }

        $safeSlug = preg_replace('/[^a-z0-9_]/', '_', $normalized);
        return ['doc_key' => 'custom_' . $safeSlug, 'doc_label' => $crmDocName, 'category' => 'Additional & CRM Documents', 'stage' => 'additional', 'mandatory' => false];
    }
}

if (!function_exists('sync_and_calculate_crm_candidate_documents')) {
    /**
     * Bi-directionally syncs documents between Website DB and Central CRM DB,
     * calculates real dynamic statistics, and keeps the CRM lead pipeline updated.
     */
    function sync_and_calculate_crm_candidate_documents(PDO $pdo, int $userId, ?int $crmLeadId = null): array
    {
        $result = [
            'success' => true,
            'crm_linked' => false,
            'crm_lead_id' => null,
            'stats' => [
                'total' => 0,
                'total_uploaded' => 0,
                'verified' => 0,
                'under_review' => 0,
                'needs_reupload' => 0,
                'pending' => 0,
                'completion_percentage' => 0
            ],
            'categories' => [],
            'documents_list' => [],
            'documents_by_key' => [],
            'target_intake' => 'Sep 2026',
            'expiry_alerts' => []
        ];

        if ($userId <= 0) return $result;

        // Resolve CRM Lead ID if not supplied
        if (!$crmLeadId || $crmLeadId <= 0) {
            try {
                $linkStmt = $pdo->prepare('SELECT crm_lead_id FROM student_crm_links WHERE student_user_id = ? LIMIT 1');
                $linkStmt->execute([$userId]);
                $crmLeadId = (int) $linkStmt->fetchColumn();
            } catch (Exception $e) {}
        }

        // Student name
        $studentName = $_SESSION['user_name'] ?? 'Candidate';

        // Connect to CRM
        $crmPdo = null;
        try {
            $crmPdo = get_central_crm_pdo();
        } catch (Exception $e) {
            error_log('CRM connection error in sync_and_calculate_crm_candidate_documents: ' . $e->getMessage());
        }

        // Fetch Local docs
        $locStmt = $pdo->prepare("SELECT * FROM student_documents WHERE user_id = ? ORDER BY id ASC");
        $locStmt->execute([$userId]);
        $localDocs = $locStmt->fetchAll(PDO::FETCH_ASSOC);

        $localByKey = [];
        $localByName = [];
        foreach ($localDocs as $ld) {
            if (!empty($ld['doc_key'])) $localByKey[$ld['doc_key']] = $ld;
            if (!empty($ld['name'])) $localByName[strtolower($ld['name'])] = $ld;
        }

        // If CRM is connected and lead exists:
        if ($crmPdo && $crmLeadId > 0) {
            $result['crm_linked'] = true;
            $result['crm_lead_id'] = $crmLeadId;

            // Fetch CRM documents (Single Source of Truth for candidate's CRM dossier)
            $crmStmt = $crmPdo->prepare("SELECT * FROM lead_documents WHERE lead_id = ? ORDER BY id ASC");
            $crmStmt->execute([$crmLeadId]);
            $crmDocs = $crmStmt->fetchAll(PDO::FETCH_ASSOC);

            // Clean up any stale mock/sample seeds from local DB for this user
            $pdo->prepare("DELETE FROM student_documents WHERE user_id = ? AND (file_url LIKE '%sample_%' OR name LIKE 'sample_%')")->execute([$userId]);

            // Re-fetch local docs after cleaning mock items
            $locStmt->execute([$userId]);
            $localDocs = $locStmt->fetchAll(PDO::FETCH_ASSOC);

            $localByKey = [];
            $localByName = [];
            foreach ($localDocs as $ld) {
                if (!empty($ld['doc_key'])) $localByKey[$ld['doc_key']] = $ld;
                if (!empty($ld['name'])) $localByName[strtolower($ld['name'])] = $ld;
            }

            // Sync CRM Docs -> Local student_documents
            $syncedCrmLocalIds = [];
            foreach ($crmDocs as $cd) {
                $mapped = map_crm_doc_to_key($cd['document_name']);
                $fName = $cd['file_name'] ?: ($mapped['doc_label'] . '.pdf');
                $fLower = strtolower($fName);
                $dLower = strtolower($cd['document_name']);

                $match = $localByName[$fLower] ?? $localByName[$dLower] ?? $localByKey[$mapped['doc_key']] ?? null;

                $rawCrmStatus = $cd['status'] ?? 'Uploaded';
                $localStatus = 'Under Review';
                if ($rawCrmStatus === 'Verified') $localStatus = 'Verified';
                elseif ($rawCrmStatus === 'Rejected') $localStatus = 'Needs Re-upload';

                // Preserve local admin verification if already certified locally
                if ($match && !empty($match['status']) && in_array($match['status'], ['Verified', 'Needs Re-upload'])) {
                    if ($match['status'] === 'Verified' && $rawCrmStatus !== 'Rejected') {
                        $localStatus = 'Verified';
                        // Keep CRM synced with local verification
                        if ($rawCrmStatus !== 'Verified' && !empty($cd['id'])) {
                            try {
                                $crmPdo->prepare("UPDATE lead_documents SET status = 'Verified', verified_at = NOW() WHERE id = ?")->execute([$cd['id']]);
                            } catch (Exception $e) {}
                        }
                    } elseif ($match['status'] === 'Needs Re-upload' && $rawCrmStatus !== 'Verified') {
                        $localStatus = 'Needs Re-upload';
                    }
                }

                $ext = strtolower(pathinfo($fName, PATHINFO_EXTENSION)) ?: 'pdf';
                $uploadedBy = $cd['uploaded_by'] ?: 'Counselor Upload';

                if (!$match) {
                    $ins = $pdo->prepare("
                        INSERT INTO student_documents 
                        (user_id, doc_key, doc_label, category, name, status, size, file_extension, file_url, admin_notes, verified_by, uploaded_by, created_at, updated_at)
                        VALUES (?, ?, ?, ?, ?, ?, '1.5 MB', ?, ?, NULL, ?, ?, ?, NOW())
                    ");
                    $ins->execute([
                        $userId,
                        $mapped['doc_key'],
                        $mapped['doc_label'],
                        $mapped['category'],
                        $fName,
                        $localStatus,
                        $ext,
                        $cd['file_path'],
                        $cd['verified_by'],
                        $uploadedBy,
                        $cd['uploaded_at'] ?: date('Y-m-d H:i:s')
                    ]);
                    $syncedCrmLocalIds[] = (int) $pdo->lastInsertId();
                } else {
                    $filePath = !empty($cd['file_path']) ? $cd['file_path'] : ($match['file_url'] ?? '');
                    $docName = !empty($fName) ? $fName : ($match['name'] ?? '');
                    $upd = $pdo->prepare("
                        UPDATE student_documents 
                        SET doc_key = COALESCE(NULLIF(doc_key, ''), ?), 
                            doc_label = COALESCE(NULLIF(doc_label, ''), ?), 
                            category = COALESCE(NULLIF(category, ''), ?), 
                            name = COALESCE(NULLIF(?, ''), name), 
                            status = ?, 
                            file_url = COALESCE(NULLIF(?, ''), file_url), 
                            verified_by = COALESCE(NULLIF(?, ''), verified_by), 
                            uploaded_by = COALESCE(NULLIF(uploaded_by, ''), ?), 
                            updated_at = NOW()
                        WHERE id = ? AND user_id = ?
                    ");
                    $upd->execute([
                        $mapped['doc_key'],
                        $mapped['doc_label'],
                        $mapped['category'],
                        $docName,
                        $localStatus,
                        $filePath,
                        $cd['verified_by'],
                        $uploadedBy,
                        $match['id'],
                        $userId
                    ]);
                    $syncedCrmLocalIds[] = (int) $match['id'];
                }
            }

            // Remove any orphan local records that do not exist in CRM and are not genuine user uploads
            if (!empty($syncedCrmLocalIds)) {
                $inClause = implode(',', array_map('intval', $syncedCrmLocalIds));
                $pdo->prepare("DELETE FROM student_documents WHERE user_id = ? AND id NOT IN ($inClause) AND (file_url IS NULL OR file_url = '' OR file_url LIKE '%sample_%')")->execute([$userId]);
            }
        }

        // Re-fetch all consolidated local documents
        $locStmt->execute([$userId]);
        $finalDocs = $locStmt->fetchAll(PDO::FETCH_ASSOC);

        $docsByKey = [];
        foreach ($finalDocs as &$d) {
            // Auto-detect and populate doc_key if missing so categorized slot connects properly
            if (empty($d['doc_key'])) {
                $candidateName = (!empty($d['doc_label']) && $d['doc_label'] !== 'Document') ? $d['doc_label'] : ($d['name'] ?? '');
                if (empty($candidateName) || $candidateName === 'Document' || $candidateName === 'Document.pdf') {
                    $candidateName = $d['category'] ?? '';
                }
                $mapped = map_crm_doc_to_key($candidateName);
                if (!empty($mapped['doc_key'])) {
                    $d['doc_key'] = $mapped['doc_key'];
                    $d['doc_label'] = $d['doc_label'] ?: $mapped['doc_label'];
                    $d['category'] = $d['category'] ?: $mapped['category'];
                    try {
                        $pdo->prepare("UPDATE student_documents SET doc_key = ?, doc_label = ?, category = ? WHERE id = ?")
                            ->execute([$mapped['doc_key'], $d['doc_label'], $d['category'], $d['id']]);
                    } catch (Exception $e) {}
                }
            }

            if (!empty($d['doc_key'])) {
                $docsByKey[$d['doc_key']] = $d;
            }
            // Always also index by custom key doc_ID
            $docsByKey['doc_' . $d['id']] = $d;
        }
        unset($d);

        // Dynamic categories builder
        $standardCategories = [
            'academic' => [
                'id' => 'academic',
                'title' => 'Academic Records',
                'icon' => 'fa-graduation-cap',
                'badge' => 'Transcripts',
                'color' => '#2563eb',
                'desc' => 'High school, undergraduate transcripts, marksheets, and degree certificates.',
                'docs' => [
                    'academic_10th' => ['doc_key' => 'academic_10th', 'doc_label' => '10th Standard / Secondary Marksheet', 'category' => 'Academic Records', 'mandatory' => true, 'has_expiry' => false, 'accepted' => '.pdf,.jpg,.jpeg,.png', 'desc' => 'Official 10th board marksheet / pass certificate'],
                    'academic_12th' => ['doc_key' => 'academic_12th', 'doc_label' => '12th / Higher Secondary Marksheet', 'category' => 'Academic Records', 'mandatory' => true, 'has_expiry' => false, 'accepted' => '.pdf,.jpg,.jpeg,.png', 'desc' => 'Higher secondary / +2 examination marksheet'],
                    'academic_ug' => ['doc_key' => 'academic_ug', 'doc_label' => 'Undergrad Marksheets (Sem 1-8 / Consolidated)', 'category' => 'Academic Records', 'mandatory' => true, 'has_expiry' => false, 'accepted' => '.pdf', 'desc' => 'All individual semester marksheets and consolidated transcript'],
                    'academic_degree' => ['doc_key' => 'academic_degree', 'doc_label' => 'Degree / Course Completion Certificate', 'category' => 'Academic Records', 'mandatory' => true, 'has_expiry' => false, 'accepted' => '.pdf', 'desc' => 'Final convocation degree certificate or course completion certificate'],
                    'academic_provisional' => ['doc_key' => 'academic_provisional', 'doc_label' => 'Provisional Degree Certificate', 'category' => 'Academic Records', 'mandatory' => false, 'has_expiry' => false, 'accepted' => '.pdf', 'desc' => 'Provisional certificate issued by university registrar (if final degree pending)'],
                    'academic_consolidated' => ['doc_key' => 'academic_consolidated', 'doc_label' => 'Consolidated Marksheet', 'category' => 'Academic Records', 'mandatory' => false, 'has_expiry' => false, 'accepted' => '.pdf', 'desc' => 'Single consolidated grade sheet issued by university examination branch'],
                    'academic_transfer' => ['doc_key' => 'academic_transfer', 'doc_label' => 'Transfer Certificate (TC)', 'category' => 'Academic Records', 'mandatory' => false, 'has_expiry' => false, 'accepted' => '.pdf', 'desc' => 'Official college transfer certificate']
                ]
            ],
            'tests' => [
                'id' => 'tests',
                'title' => 'English & Standardized Tests',
                'icon' => 'fa-language',
                'badge' => 'Language & Aptitude',
                'color' => '#7c3aed',
                'desc' => 'Official scorecards for IELTS, PTE, TOEFL, Duolingo, GRE, or GMAT.',
                'docs' => [
                    'test_english' => ['doc_key' => 'test_english', 'doc_label' => 'English Language Scorecard (IELTS / PTE / TOEFL)', 'category' => 'English & Standardized Tests', 'mandatory' => true, 'has_expiry' => true, 'expiry_type' => 'ielts', 'accepted' => '.pdf', 'desc' => 'Official Test Report Form (TRF) showing overall score & component bands'],
                    'test_standardized' => ['doc_key' => 'test_standardized', 'doc_label' => 'GRE / GMAT Scorecard (If applicable)', 'category' => 'English & Standardized Tests', 'mandatory' => false, 'has_expiry' => true, 'expiry_type' => 'gre', 'accepted' => '.pdf', 'desc' => 'Official GRE General or GMAT scorecard for STEM/MBA programs'],
                    'test_moi' => ['doc_key' => 'test_moi', 'doc_label' => 'Medium of Instruction (MOI) Certificate', 'category' => 'English & Standardized Tests', 'mandatory' => false, 'has_expiry' => false, 'accepted' => '.pdf', 'desc' => 'Certificate issued by college certifying undergraduate degree was taught in English']
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
                    'app_sop' => ['doc_key' => 'app_sop', 'doc_label' => 'Statement of Purpose (SOP)', 'category' => 'Application Essentials', 'mandatory' => true, 'has_expiry' => false, 'accepted' => '.pdf,.docx,.doc', 'desc' => 'Personal statement of purpose tailored to your chosen course'],
                    'app_cv' => ['doc_key' => 'app_cv', 'doc_label' => 'Updated CV / Academic Resume', 'category' => 'Application Essentials', 'mandatory' => true, 'has_expiry' => false, 'accepted' => '.pdf,.docx,.doc', 'desc' => 'Detailed academic CV conforming to international format'],
                    'app_lor_1' => ['doc_key' => 'app_lor_1', 'doc_label' => 'Letter of Recommendation 1 (Academic LOR)', 'category' => 'Application Essentials', 'mandatory' => true, 'has_expiry' => false, 'accepted' => '.pdf', 'desc' => 'Reference on university letterhead from professor / HOD'],
                    'app_lor_2' => ['doc_key' => 'app_lor_2', 'doc_label' => 'Letter of Recommendation 2 (Professional / Academic LOR)', 'category' => 'Application Essentials', 'mandatory' => true, 'has_expiry' => false, 'accepted' => '.pdf', 'desc' => 'Second reference from faculty member or current employer']
                ]
            ],
            'identity' => [
                'id' => 'identity',
                'title' => 'Identity & Financials',
                'icon' => 'fa-passport',
                'badge' => 'Visa & ID',
                'color' => '#d97706',
                'desc' => 'Government ID, international passport scans, and financial proof of funds.',
                'docs' => [
                    'id_passport' => ['doc_key' => 'id_passport', 'doc_label' => 'Passport (Front & Back Pages)', 'category' => 'Identity & Financials', 'mandatory' => true, 'has_expiry' => true, 'expiry_type' => 'passport', 'accepted' => '.pdf,.jpg,.jpeg,.png', 'desc' => 'Clear color scan of passport bio page and address page'],
                    'id_bank_statement' => ['doc_key' => 'id_bank_statement', 'doc_label' => 'Bank Statement (Last 6 Months) / Proof of Funds', 'category' => 'Identity & Financials', 'mandatory' => true, 'has_expiry' => false, 'accepted' => '.pdf', 'desc' => 'Official savings account bank statement with branch round seal and signature'],
                    'id_bank_cover' => ['doc_key' => 'id_bank_cover', 'doc_label' => 'Bank Cover Letter / Sanction Letter', 'category' => 'Identity & Financials', 'mandatory' => false, 'has_expiry' => false, 'accepted' => '.pdf', 'desc' => 'Education loan sanction letter or bank balance certificate'],
                    'id_affidavit' => ['doc_key' => 'id_affidavit', 'doc_label' => 'Affidavit of Support (Sponsorship)', 'category' => 'Identity & Financials', 'mandatory' => false, 'has_expiry' => false, 'accepted' => '.pdf', 'desc' => 'Notarized sponsorship affidavit on stamp paper from parent/sponsor']
                ]
            ]
        ];

        // Additional / CRM category
        $additionalCategory = [
            'id' => 'additional',
            'title' => 'Additional & CRM Documents',
            'icon' => 'fa-folder-plus',
            'badge' => 'Counselor & Visa Files',
            'color' => '#0284c7',
            'desc' => 'Admissions offer letters, CAS/I-20, medical clearances, and additional files uploaded by your counselor or you.',
            'docs' => []
        ];

        // Check for any documents that are custom or additional
        foreach ($finalDocs as $d) {
            $key = $d['doc_key'] ?: ('doc_' . $d['id']);
            $foundInStandard = false;
            foreach ($standardCategories as $cat) {
                if (isset($cat['docs'][$key])) {
                    $foundInStandard = true;
                    break;
                }
            }

            if (!$foundInStandard) {
                $additionalCategory['docs'][$key] = [
                    'doc_key' => $key,
                    'doc_label' => $d['doc_label'] ?: $d['name'],
                    'category' => $d['category'] ?: 'Additional & CRM Documents',
                    'mandatory' => false,
                    'has_expiry' => !empty($d['expiry_date']),
                    'accepted' => '.pdf,.jpg,.jpeg,.png,.docx,.doc',
                    'desc' => 'Candidate file: ' . $d['name'] . (!empty($d['uploaded_by']) ? ' (' . $d['uploaded_by'] . ')' : '')
                ];
            }
        }

        if (!empty($additionalCategory['docs'])) {
            $standardCategories['additional'] = $additionalCategory;
        }

        // Calculate dynamic stats
        $totalUploaded = count($finalDocs);
        $verifiedCount = 0;
        $underReviewCount = 0;
        $needsReuploadCount = 0;

        foreach ($finalDocs as $d) {
            $st = strtolower($d['status'] ?? 'under review');
            if ($st === 'verified') $verifiedCount++;
            elseif (in_array($st, ['needs re-upload', 'rejected'])) $needsReuploadCount++;
            else $underReviewCount++;
        }

        $mandatoryKeys = [
            'academic_10th', 'academic_12th', 'academic_ug', 'academic_degree',
            'test_english', 'app_sop', 'app_cv', 'app_lor_1', 'app_lor_2', 'id_passport', 'id_bank_statement'
        ];
        $mandatoryTarget = count($mandatoryKeys); // 11
        $completionPct = round(($verifiedCount / max($mandatoryTarget, $totalUploaded, 1)) * 100);
        if ($completionPct > 100) $completionPct = 100;

        // Target intake calculation for expiry
        $targetIntake = 'Sep 2026';
        if ($crmPdo && $crmLeadId > 0) {
            try {
                $intakeStmt = $crmPdo->prepare("SELECT intake FROM leads WHERE id = ? LIMIT 1");
                $intakeStmt->execute([$crmLeadId]);
                $leadIntake = $intakeStmt->fetchColumn();
                if (!empty($leadIntake)) $targetIntake = $leadIntake;
            } catch (Exception $e) {}
        }

        $expiryAlerts = [];
        if (function_exists('calculate_doc_expiry_alerts')) {
            $expiryAlerts = calculate_doc_expiry_alerts($docsByKey, $targetIntake);
        }

        // Sync dynamic status to CRM leads table if connected
        if ($crmPdo && $crmLeadId > 0) {
            try {
                $newPipeline = 'Documentation In Progress';
                $newPrimary = 'Documents Collecting';
                if ($needsReuploadCount > 0) {
                    $newPipeline = 'Under Document Verification';
                    $newPrimary = 'Documentation In Progress';
                    $newSecondary = "Action Required: {$needsReuploadCount} document(s) need re-upload";
                } elseif ($verifiedCount >= $mandatoryTarget) {
                    $newPipeline = 'Documents Complete - Ready for University Submission';
                    $newPrimary = 'Documentation Completed';
                    $newSecondary = "All {$verifiedCount} Documents Verified & Certified";
                } elseif ($totalUploaded >= $mandatoryTarget) {
                    $newPipeline = 'Under Document Verification';
                    $newPrimary = 'Documentation In Progress';
                    $newSecondary = "Verification In Progress ({$verifiedCount}/{$mandatoryTarget} Verified)";
                } else {
                    $newPipeline = 'Documentation In Progress';
                    $newPrimary = 'Documents Collecting';
                    $newSecondary = "Dossier Building ({$totalUploaded} uploaded, {$verifiedCount} verified)";
                }

                $crmPdo->prepare("
                    UPDATE leads 
                    SET overseas_pipeline_status = ?, primary_status = ?, secondary_status = ?, updated_at = NOW()
                    WHERE id = ? AND is_active = 1
                ")->execute([$newPipeline, $newPrimary, $newSecondary, $crmLeadId]);
            } catch (Exception $e) {
                error_log('CRM lead sync status update error: ' . $e->getMessage());
            }
        }

        $result['stats'] = [
            'total' => max($mandatoryTarget, $totalUploaded),
            'total_uploaded' => $totalUploaded,
            'verified' => $verifiedCount,
            'under_review' => $underReviewCount,
            'needs_reupload' => $needsReuploadCount,
            'pending' => max(0, $mandatoryTarget - $totalUploaded),
            'completion_percentage' => $completionPct
        ];
        $result['categories'] = $standardCategories;
        $result['documents_list'] = $finalDocs;
        $result['documents_by_key'] = $docsByKey;
        $result['target_intake'] = $targetIntake;
        $result['expiry_alerts'] = $expiryAlerts;

        return $result;
    }
}


