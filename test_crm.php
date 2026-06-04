<?php
// test_crm.php
require_once 'includes/config.php';

$payload = [
    'name'          => 'Test Student',
    'email'         => 'teststudent@example.com',
    'phone'         => '9876543210',
    'message'       => 'Test Study Abroad Enquiry Remarks',
    'domain'        => 'Overseas',
    'category'      => 'Website Enquiry',
    'interested_in' => 'Study in Germany'
];

echo "Sending test payload to CRM...\n";
echo "Payload: " . json_encode($payload, JSON_PRETTY_PRINT) . "\n\n";

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
curl_setopt($ch, CURLOPT_TIMEOUT, 10);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$err = curl_error($ch);
curl_close($ch);

if ($err) {
    echo "cURL Error: " . $err . "\n";
} else {
    echo "HTTP Status Code: " . $httpCode . "\n";
    echo "Response: " . $response . "\n";
}
