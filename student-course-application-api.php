<?php
session_start();
header('Content-Type: application/json; charset=utf-8');
header('X-Content-Type-Options: nosniff');
header('Cache-Control: no-store, max-age=0');

require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/crm_db.php';

function studentCourseApplicationResponse(int $status, array $payload): void
{
    http_response_code($status);
    echo json_encode($payload, JSON_INVALID_UTF8_SUBSTITUTE);
    exit;
}

if (empty($_SESSION['user_id']) || ($_SESSION['user_role'] ?? '') !== 'student') {
    studentCourseApplicationResponse(401, [
        'success' => false,
        'message' => 'Please sign in with a student account.'
    ]);
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Allow: POST');
    studentCourseApplicationResponse(405, [
        'success' => false,
        'message' => 'This action requires a POST request.'
    ]);
}

$csrfToken = $_POST['csrf_token'] ?? '';
if (
    !is_string($csrfToken) ||
    empty($_SESSION['community_csrf_token']) ||
    !hash_equals($_SESSION['community_csrf_token'], $csrfToken)
) {
    studentCourseApplicationResponse(403, [
        'success' => false,
        'message' => 'Session expired. Refresh the page and try again.'
    ]);
}

if (($_POST['action'] ?? '') !== 'request_application') {
    studentCourseApplicationResponse(422, [
        'success' => false,
        'message' => 'Invalid action.'
    ]);
}

$courseName = trim(is_string($_POST['course_name'] ?? null) ? $_POST['course_name'] : '');
$universityName = trim(is_string($_POST['university_name'] ?? null) ? $_POST['university_name'] : '');
$countryName = trim(is_string($_POST['country_name'] ?? null) ? $_POST['country_name'] : '');
$intake = trim(is_string($_POST['intake'] ?? null) ? $_POST['intake'] : '');

if ($courseName === '' || $universityName === '') {
    studentCourseApplicationResponse(422, [
        'success' => false,
        'message' => 'Course and university are required.'
    ]);
}

$courseName = mb_substr($courseName, 0, 300);
$universityName = mb_substr($universityName, 0, 300);
$countryName = mb_substr($countryName, 0, 100);
$intake = mb_substr($intake, 0, 200);

try {
    $linkStmt = $pdo->prepare(
        'SELECT crm_lead_id FROM student_crm_links WHERE student_user_id = ? LIMIT 1'
    );
    $linkStmt->execute([(int) $_SESSION['user_id']]);
    $crmLeadId = (int) $linkStmt->fetchColumn();

    if ($crmLeadId < 1) {
        studentCourseApplicationResponse(409, [
            'success' => false,
            'message' => 'Your dashboard is not linked to a CRM record yet. Please contact your counselor.'
        ]);
    }

    $details = [
        $universityName . ' - ' . $courseName . ' (student application request, ' . date('Y-m-d H:i:s') . ')',
    ];
    if ($countryName !== '') {
        $details[] = 'Country: ' . $countryName;
    }
    if ($intake !== '') {
        $details[] = 'Preferred intake: ' . $intake;
    }
    $remark = implode("\n", $details);

    $crmPdo = get_central_crm_pdo();
    $updateStmt = $crmPdo->prepare("    
        UPDATE leads
        SET remarks = CONCAT(
            ?,
            CASE
                WHEN COALESCE(remarks, '') = '' THEN ''
                ELSE CONCAT(CHAR(10), CHAR(10), remarks)
            END
        )
        WHERE id = ? AND is_active = 1
    ");
    $updateStmt->execute([$remark, $crmLeadId]);

    if ($updateStmt->rowCount() !== 1) {
        studentCourseApplicationResponse(404, [
            'success' => false,
            'message' => 'Your linked CRM record is unavailable. Please contact your counselor.'
        ]);
    }

    // Also record in student_applications table for multi-application tracking & notify
    try {
        $checkApp = $pdo->prepare("SELECT id FROM student_applications WHERE user_id = ? AND university_name = ? AND course_name = ? LIMIT 1");
        $checkApp->execute([(int) $_SESSION['user_id'], $universityName, $courseName]);
        if (!$checkApp->fetch()) {
            $insApp = $pdo->prepare("
                INSERT INTO student_applications 
                (user_id, university_name, course_name, country_name, intake, app_type, status, status_type, student_id_ref, offer_type)
                VALUES (?, ?, ?, ?, ?, 'Target', 'Counselor Review & Filing', 'review', ?, 'None')
            ");
            $refId = 'APP-' . rand(100000, 999999);
            $insApp->execute([
                (int) $_SESSION['user_id'],
                $universityName,
                $courseName,
                $countryName !== '' ? $countryName : 'Global',
                $intake !== '' ? $intake : 'Sep 2026',
                $refId
            ]);
        }

        // Add local in-app notification
        $notifStmt = $pdo->prepare("
            INSERT INTO notifications (user_id, title, message, type, category, link)
            VALUES (?, ?, ?, 'info', 'application', 'student-dashboard.php?tab=application')
        ");
        $notifStmt->execute([
            (int) $_SESSION['user_id'],
            'Application Requested: ' . $universityName,
            'You requested an application for ' . $courseName . ' at ' . $universityName . '. Your counselor will review your profile and initiate university submission.'
        ]);
    } catch (Exception $appErr) {
        error_log('Error adding student_application entry: ' . $appErr->getMessage());
    }

    studentCourseApplicationResponse(200, [
        'success' => true,
        'message' => 'Your request was sent to your counselor and added to your dashboard!'
    ]);
} catch (PDOException $e) {
    error_log('Student course application request failed: ' . $e->getMessage());
    studentCourseApplicationResponse(500, [
        'success' => false,
        'message' => 'Unable to send your request right now. Please try again later.'
    ]);
}
