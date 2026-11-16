<?php
/**
 * Official University Offer Letter Viewer / PDF Printable
 */
session_start();
require_once __DIR__ . '/includes/config.php';

if (empty($_SESSION['user_id']) || ($_SESSION['user_role'] ?? '') !== 'student') {
    header('Location: sign-in.php');
    exit;
}

$userId = (int) $_SESSION['user_id'];
$appId = (int) ($_GET['id'] ?? 0);

try {
    $stmt = $pdo->prepare("SELECT * FROM student_applications WHERE id = ? AND user_id = ? LIMIT 1");
    $stmt->execute([$appId, $userId]);
    $app = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$app) {
        // Fallback to first offer
        $stmt2 = $pdo->prepare("SELECT * FROM student_applications WHERE user_id = ? AND (offer_type != 'None' OR status_type IN ('offer', 'visa')) LIMIT 1");
        $stmt2->execute([$userId]);
        $app = $stmt2->fetch(PDO::FETCH_ASSOC);
    }
} catch (PDOException $e) {
    die('Database connection error.');
}

if (!$app) {
    die('No offer letter available for this application yet.');
}

$studentName = $_SESSION['user_name'] ?? 'Student Applicant';
$uniName = $app['university_name'];
$courseName = $app['course_name'];
$country = $app['country_name'];
$intake = $app['intake'];
$studentIdRef = $app['student_id_ref'] ?: 'STU-' . rand(100000, 999999);
$depositAmount = $app['deposit_amount'] ?: '£3,000';
$conditions = !empty($app['conditions_json']) ? json_decode($app['conditions_json'], true) : [];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Official Offer Letter - <?= htmlspecialchars($uniName) ?> - <?= htmlspecialchars($studentName) ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@700&family=Inter:wght@400;500;600;700;800&family=Newsreader:ital,opsz,wght@0,6..72,400;0,6..72,600;1,6..72,400&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            background: #f1f5f9;
            font-family: 'Inter', -apple-system, sans-serif;
            color: #1e293b;
            padding: 30px 15px;
        }
        .offer-paper {
            max-width: 820px;
            margin: 0 auto;
            background: #ffffff;
            border-radius: 8px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.12);
            padding: 50px 60px;
            position: relative;
            border-top: 8px solid #1e3a8a;
        }
        .print-toolbar {
            max-width: 820px;
            margin: 0 auto 20px auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .btn-action {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: #1e3a8a;
            color: white;
            padding: 10px 20px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            font-size: 14px;
            cursor: pointer;
            border: none;
            transition: all 0.2s;
        }
        .btn-action:hover { background: #172554; }
        .btn-back { background: #64748b; }
        .btn-back:hover { background: #475569; }

        .letter-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            border-bottom: 2px solid #e2e8f0;
            padding-bottom: 24px;
            margin-bottom: 30px;
        }
        .uni-crest {
            display: flex;
            align-items: center;
            gap: 14px;
        }
        .uni-crest i {
            font-size: 40px;
            color: #1e3a8a;
        }
        .uni-title-box h1 {
            font-family: 'Cinzel', serif;
            font-size: 24px;
            color: #0f172a;
            letter-spacing: 0.02em;
        }
        .uni-title-box p {
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            color: #64748b;
            font-weight: 600;
        }
        .letter-meta {
            text-align: right;
            font-size: 12px;
            color: #64748b;
            line-height: 1.6;
        }
        .letter-meta strong { color: #0f172a; }

        .recipient-box {
            margin-bottom: 24px;
            font-size: 14px;
            line-height: 1.6;
            color: #334155;
        }
        .recipient-box strong {
            font-size: 16px;
            color: #0f172a;
        }

        .offer-title-banner {
            background: #f0fdf4;
            border: 1px solid #bbf7d0;
            padding: 16px 20px;
            border-radius: 8px;
            margin-bottom: 28px;
        }
        .offer-title-banner h2 {
            font-size: 18px;
            font-weight: 700;
            color: #166534;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .offer-title-banner p {
            font-size: 13px;
            color: #15803d;
            margin-top: 4px;
        }

        .details-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 28px;
        }
        .details-table td {
            padding: 10px 14px;
            border: 1px solid #e2e8f0;
            font-size: 13px;
        }
        .details-table td.label-col {
            background: #f8fafc;
            font-weight: 600;
            color: #475569;
            width: 35%;
        }
        .details-table td.val-col {
            color: #0f172a;
            font-weight: 700;
        }

        .conditions-section {
            background: #fefce8;
            border: 1px solid #fef08a;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 30px;
        }
        .conditions-section h3 {
            font-size: 15px;
            font-weight: 700;
            color: #854d0e;
            margin-bottom: 12px;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .conditions-section ol {
            padding-left: 20px;
            font-size: 13px;
            color: #713f12;
            line-height: 1.8;
        }

        .letter-body {
            font-family: 'Newsreader', serif;
            font-size: 16px;
            line-height: 1.7;
            color: #334155;
            margin-bottom: 35px;
        }

        .letter-footer {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            margin-top: 40px;
            padding-top: 24px;
            border-top: 1px solid #e2e8f0;
        }
        .dean-signature {
            font-family: 'Newsreader', serif;
            font-style: italic;
            font-size: 22px;
            color: #1e3a8a;
            margin-bottom: 4px;
        }
        .stamp-box {
            border: 2px dashed #94a3b8;
            padding: 12px 18px;
            border-radius: 8px;
            text-align: center;
            font-size: 11px;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        @media print {
            body { background: white; padding: 0; }
            .print-toolbar { display: none; }
            .offer-paper { box-shadow: none; border-radius: 0; padding: 40px; }
        }
    </style>
</head>
<body>

    <div class="print-toolbar">
        <a href="student-dashboard.php?tab=application" class="btn-action btn-back"><i class="fa-solid fa-arrow-left"></i> Back to Dashboard</a>
        <button onclick="window.print()" class="btn-action"><i class="fa-solid fa-print"></i> Print Official Offer Letter</button>
    </div>

    <div class="offer-paper">
        <div class="letter-header">
            <div class="uni-crest">
                <i class="fa-solid fa-building-columns"></i>
                <div class="uni-title-box">
                    <h1><?= htmlspecialchars($uniName) ?></h1>
                    <p>Office of International Admissions & Global Engagement</p>
                </div>
            </div>
            <div class="letter-meta">
                <div>Date: <strong><?= date('F j, Y') ?></strong></div>
                <div>Application Ref: <strong><?= htmlspecialchars($studentIdRef) ?></strong></div>
                <div>Country: <strong><?= htmlspecialchars($country) ?></strong></div>
            </div>
        </div>

        <div class="recipient-box">
            <p>Dear <strong><?= htmlspecialchars($studentName) ?></strong>,</p>
            <p>We are delighted to offer you admission to <strong><?= htmlspecialchars($uniName) ?></strong>. On behalf of the university faculty and admissions council, we congratulate you on your academic achievements and look forward to welcoming you to our campus.</p>
        </div>

        <div class="offer-title-banner">
            <h2><i class="fa-solid fa-circle-check"></i> Official Letter of Offer (Conditional Admission)</h2>
            <p>Please review your offer details, program specifications, and conditions outlined below.</p>
        </div>

        <table class="details-table">
            <tr>
                <td class="label-col">Program of Study</td>
                <td class="val-col"><?= htmlspecialchars($courseName) ?></td>
            </tr>
            <tr>
                <td class="label-col">Academic Intake</td>
                <td class="val-col"><?= htmlspecialchars($intake) ?></td>
            </tr>
            <tr>
                <td class="label-col">Student Reference ID</td>
                <td class="val-col"><?= htmlspecialchars($studentIdRef) ?></td>
            </tr>
            <tr>
                <td class="label-col">Study Level & Mode</td>
                <td class="val-col">Full-Time Postgraduate / Undergraduate Taught</td>
            </tr>
            <tr>
                <td class="label-col">Tuition Deposit Required</td>
                <td class="val-col"><?= htmlspecialchars($depositAmount) ?> (To reserve your place and release CAS/I-20)</td>
            </tr>
            <tr>
                <td class="label-col">Authorized Education Partner</td>
                <td class="val-col">Bluestone Overseas Consultants (Verified Representative)</td>
            </tr>
        </table>

        <?php if (!empty($conditions)): ?>
        <div class="conditions-section">
            <h3><i class="fa-solid fa-list-check"></i> Academic & Administrative Conditions:</h3>
            <ol>
                <?php foreach ($conditions as $c): ?>
                    <li>
                        <?= htmlspecialchars($c['condition']) ?> 
                        <?php if (!empty($c['satisfied'])): ?>
                            <span style="color:#16a34a; font-weight:700; margin-left:6px;"><i class="fa-solid fa-check"></i> [Satisfied]</span>
                        <?php else: ?>
                            <span style="color:#d97706; font-weight:700; margin-left:6px;"><i class="fa-solid fa-clock"></i> [Pending Fulfillment]</span>
                        <?php endif; ?>
                    </li>
                <?php endforeach; ?>
            </ol>
        </div>
        <?php endif; ?>

        <div class="letter-body">
            <p>Upon accepting this offer and satisfying the remaining academic conditions and deposit requirements, your official Confirmation of Acceptance for Studies (CAS) / Form I-20 will be issued to facilitate your international student visa application.</p>
        </div>

        <div class="letter-footer">
            <div>
                <div class="dean-signature">Prof. Alistair R. Montgomery</div>
                <div style="font-size:13px; font-weight:700; color:#0f172a;">Director of International Admissions</div>
                <div style="font-size:12px; color:#64748b;"><?= htmlspecialchars($uniName) ?></div>
            </div>
            <div class="stamp-box">
                <i class="fa-solid fa-certificate" style="font-size:24px; color:#1e3a8a; margin-bottom:4px; display:block;"></i>
                Admissions Board<br>Verified & Sealed
            </div>
        </div>
    </div>

</body>
</html>

