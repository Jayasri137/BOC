<?php
// submit-enquiry.php - Process form submissions asynchronously (AJAX)

header('Content-Type: application/json; charset=utf-8');
require_once 'includes/config.php'; // automatically loads includes/db.php

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'error' => 'Invalid request method.']);
    exit;
}

// Read JSON input or standard POST data
$input = json_decode(file_get_contents('php://input'), true);
if (!$input) {
    $input = $_POST;
}

$formType = isset($input['form_type']) ? trim($input['form_type']) : 'enquiry';

$firstName = isset($input['first_name']) ? trim($input['first_name']) : '';
$lastName = isset($input['last_name']) ? trim($input['last_name']) : '';
$email = isset($input['email']) ? trim($input['email']) : '';
$phone = isset($input['phone']) ? trim($input['phone']) : '';
$destination = isset($input['destination']) ? trim($input['destination']) : '';

if (empty($firstName) || empty($email) || empty($phone)) {
    echo json_encode(['success' => false, 'error' => 'Required fields (First Name, Email, Phone) are missing.']);
    exit;
}

$fullName = trim($firstName . ' ' . $lastName);

try {
    if ($formType === 'contact') {
        // --- CONTACT MESSAGE SUBMISSION ---
        $queryText = isset($input['query']) ? trim($input['query']) : '';
        $message = "Preferred Country: " . $destination . "\nQuery: " . $queryText;
        
        // 1. Insert into contact_inquiries
        $stmt = $pdo->prepare("
            INSERT INTO contact_inquiries (name, email, phone, business_focus, message, created_at)
            VALUES (:name, :email, :phone, :business_focus, :message, NOW())
        ");
        $stmt->execute([
            'name'           => $fullName,
            'email'          => $email,
            'phone'          => $phone,
            'business_focus' => 'Overseas Consulting', 
            'message'        => $message
        ]);
        
        // 2. Generate lead code and insert into leads as Website Contact
        $leadCode = getNextLeadCode($pdo);
        $stmtLeads = $pdo->prepare("
            INSERT INTO leads (student_name, email, phone, domain, source, category, interested_in, remarks, lead_code, status, is_active, created_at)
            VALUES (:name, :email, :phone, 'Overseas Consulting', 'Website Contact', 'Website Contact', :interested_in, :remarks, :lead_code, 'New', 1, NOW())
        ");
        $stmtLeads->execute([
            'name'          => $fullName,
            'email'         => $email,
            'phone'         => $phone,
            'interested_in' => $destination ? "Study in " . $destination : "Overseas Consulting",
            'remarks'       => $queryText,
            'lead_code'     => $leadCode
        ]);

        // Forward lead to central CRM Portal
        $crmPayload = [
            'name'          => $fullName,
            'email'         => $email,
            'phone'         => $phone,
            'message'       => $message,
            'domain'        => 'Overseas',
            'category'      => 'Website Contact',
            'interested_in' => $destination ? "Study in " . $destination : "Overseas Consulting"
        ];
        forwardToCRM($crmPayload);

        echo json_encode(['success' => true, 'message' => 'Thank you! Your message has been sent successfully.', 'lead_code' => $leadCode]);
        exit;
        
    } else {
        // --- STUDY ABROAD ENQUIRY SUBMISSION ---
        $startDate = isset($input['start_date']) ? trim($input['start_date']) : '';
        $studyLevel = isset($input['study_level']) ? trim($input['study_level']) : '';
        $counsellingMode = isset($input['counselling_mode']) ? trim($input['counselling_mode']) : '';
        $fundingMode = isset($input['funding_mode']) ? trim($input['funding_mode']) : '';
        
        $remarks = "Preferred Level: " . $studyLevel . "\nCounselling Mode: " . $counsellingMode . "\nFunding: " . $fundingMode;
        if (!empty($startDate)) {
            $remarks .= "\nPreferred Intake: " . $startDate;
        }

        // 1. Generate unique lead code
        $leadCode = getNextLeadCode($pdo);
        
        // 2. Insert into bgoi_enquiries
        $stmtBgoi = $pdo->prepare("
            INSERT INTO bgoi_enquiries (lead_code, user_id, enquiry_for, candidate_name, full_name, service_type, phone, email, remarks, budget, created_at)
            VALUES (:lead_code, NULL, 'Study Abroad', :candidate_name, :full_name, 'Study Abroad', :phone, :email, :remarks, :budget, NOW())
        ");
        $stmtBgoi->execute([
            'lead_code'      => $leadCode,
            'candidate_name' => $fullName,
            'full_name'      => $fullName,
            'phone'          => $phone,
            'email'          => $email,
            'remarks'        => $remarks,
            'budget'         => $fundingMode
        ]);
        
        // 3. Insert into leads
        $stmtLeads = $pdo->prepare("
            INSERT INTO leads (student_name, email, phone, domain, source, category, interested_in, remarks, lead_code, status, is_active, created_at)
            VALUES (:name, :email, :phone, 'Overseas Consulting', 'Website Enquiry', 'Website Enquiry', :interested_in, :remarks, :lead_code, 'New', 1, NOW())
        ");
        $stmtLeads->execute([
            'name'          => $fullName,
            'email'         => $email,
            'phone'         => $phone,
            'interested_in' => "Study in " . ($destination ? $destination : "Overseas"),
            'remarks'       => $remarks,
            'lead_code'     => $leadCode
        ]);

        // Forward lead to central CRM Portal
        $interestedIn = "Overseas Consulting";
        if ($destination) {
            if (in_array(strtolower($destination), ['ielts', 'toefl', 'pte', 'pte academic', 'coaching'])) {
                $interestedIn = $destination . " Coaching";
            } else {
                $interestedIn = "Study in " . $destination;
            }
        }

        $crmPayload = [
            'name'          => $fullName,
            'email'         => $email,
            'phone'         => $phone,
            'message'       => $remarks,
            'domain'        => 'Overseas',
            'category'      => 'Website Enquiry',
            'interested_in' => $interestedIn
        ];
        forwardToCRM($crmPayload);

        echo json_encode(['success' => true, 'message' => 'Thank you! Your enquiry has been received successfully.', 'lead_code' => $leadCode]);
        exit;
    }
} catch (Exception $e) {
    error_log("Form submission error: " . $e->getMessage());
    echo json_encode(['success' => false, 'error' => 'An internal database error occurred: ' . $e->getMessage()]);
    exit;
}

// Function to generate the next lead code (BGOI-XXXX)
function getNextLeadCode($pdo) {
    $prefix = 'BGOI';
    $stmt = $pdo->prepare("
        SELECT lead_code 
        FROM leads 
        WHERE lead_code LIKE :pattern 
        ORDER BY CAST(SUBSTRING_INDEX(lead_code, '-', -1) AS UNSIGNED) DESC 
        LIMIT 1
    ");
    $stmt->execute(['pattern' => "$prefix-%"]);
    $row = $stmt->fetch();
    if ($row) {
        $lastCode = $row['lead_code'];
        $parts = explode('-', $lastCode);
        $lastNum = isset($parts[1]) ? intval($parts[1]) : 0;
        $nextNum = $lastNum + 1;
    } else {
        $nextNum = 1;
    }
    return $prefix . '-' . str_pad($nextNum, 4, '0', STR_PAD_LEFT);
}

// Function to forward the lead asynchronously/synchronously to the CRM portal via cURL
function forwardToCRM($payload) {
    $url = 'https://bluestoneinternationalpreschool.com/bgoi_portal/api/contact';
    $ch = curl_init($url);
    
    $jsonData = json_encode($payload);
    
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'Content-Length: ' . strlen($jsonData)
    ]);
    curl_setopt($ch, CURLOPT_TIMEOUT, 5); // 5 seconds connection timeout
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // Bypass certificate verification if local/Hostinger issue
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $err = curl_error($ch);
    curl_close($ch);
    
    if ($err) {
        error_log("CRM Forward Error for " . $payload['email'] . ": " . $err);
        return false;
    }
    
    error_log("CRM Forward Response (HTTP $httpCode) for " . $payload['email'] . ": " . $response);
    return true;
}
