<?php
$pageTitle = 'Student Community Reports';
require_once __DIR__ . '/includes/header.php';

if (empty($_SESSION['community_reports_csrf'])) {
    $_SESSION['community_reports_csrf'] = bin2hex(random_bytes(32));
}

$alertSuccess = '';
$alertError = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $reportId = filter_var($_POST['report_id'] ?? null, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
    if (
        !isset($_POST['csrf_token']) ||
        !is_string($_POST['csrf_token']) ||
        !hash_equals($_SESSION['community_reports_csrf'], $_POST['csrf_token'])
    ) {
        $alertError = 'Your session expired. Refresh this page and try again.';
    } elseif ($reportId === false) {
        $alertError = 'Select a valid report.';
    } else {
        try {
            $deleteStmt = $pdo->prepare('DELETE FROM student_community_reports WHERE id = ?');
            $deleteStmt->execute([$reportId]);
            $alertSuccess = $deleteStmt->rowCount() === 1
                ? 'The reviewed report was removed.'
                : 'This report was already removed.';
        } catch (PDOException $e) {
            error_log('Admin community report dismissal failed: ' . $e->getMessage());
            $alertError = 'The report could not be removed. Please try again.';
        }
    }
}

$reports = [];
try {
    $reports = $pdo->query("
        SELECT r.id, r.reporter_id, r.reported_user_id, r.reason, r.created_at,
               reporter.first_name AS reporter_first_name, reporter.last_name AS reporter_last_name,
               reported.first_name AS reported_first_name, reported.last_name AS reported_last_name
        FROM student_community_reports r
        LEFT JOIN users reporter ON reporter.id = r.reporter_id
        LEFT JOIN users reported ON reported.id = r.reported_user_id
        ORDER BY r.created_at DESC
        LIMIT 100
    ")->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    error_log('Admin community reports lookup failed: ' . $e->getMessage());
    $alertError = 'Reports are unavailable. Confirm the student community tables have been installed.';
}
?>

<div style="margin-bottom: 1.5rem;">
    <h1 class="page-title">Student Community Reports</h1>
    <p style="color: var(--text-muted);">Review student safety reports. The latest 100 reports are shown.</p>
</div>

<?php if ($alertSuccess !== ''): ?>
    <div class="alert alert-success"><i class="fa-solid fa-check"></i> <?php echo htmlspecialchars($alertSuccess, ENT_QUOTES, 'UTF-8'); ?></div>
<?php endif; ?>
<?php if ($alertError !== ''): ?>
    <div class="alert alert-danger"><i class="fa-solid fa-triangle-exclamation"></i> <?php echo htmlspecialchars($alertError, ENT_QUOTES, 'UTF-8'); ?></div>
<?php endif; ?>

<?php if ($reports === [] && $alertError === ''): ?>
    <div class="profile-card" style="padding: 30px; text-align: center;">
        <i class="fa-solid fa-shield-heart" style="font-size: 2rem; color: #16a34a;"></i>
        <p style="margin-top: 10px;">There are no community reports to review.</p>
    </div>
<?php else: ?>
    <div style="display: grid; gap: 16px;">
        <?php foreach ($reports as $report): ?>
            <?php
                $reporter = trim(($report['reporter_first_name'] ?? '') . ' ' . ($report['reporter_last_name'] ?? ''));
                $reported = trim(($report['reported_first_name'] ?? '') . ' ' . ($report['reported_last_name'] ?? ''));
            ?>
            <article class="profile-card" style="padding: 20px;">
                <div style="display: flex; justify-content: space-between; align-items: flex-start; gap: 16px; flex-wrap: wrap;">
                    <div>
                        <strong><?php echo htmlspecialchars($reporter !== '' ? $reporter : 'Former student', ENT_QUOTES, 'UTF-8'); ?></strong>
                        <span style="color: var(--text-muted);"> reported </span>
                        <strong><?php echo htmlspecialchars($reported !== '' ? $reported : 'Former student', ENT_QUOTES, 'UTF-8'); ?></strong>
                        <div style="margin-top: 5px; color: var(--text-muted); font-size: 12px;">
                            Report #<?php echo (int) $report['id']; ?> · <?php echo htmlspecialchars((string) $report['created_at'], ENT_QUOTES, 'UTF-8'); ?>
                        </div>
                    </div>
                    <form method="POST" onsubmit="return confirm('Remove this report after review?');">
                        <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['community_reports_csrf'], ENT_QUOTES, 'UTF-8'); ?>">
                        <input type="hidden" name="report_id" value="<?php echo (int) $report['id']; ?>">
                        <button type="submit" class="btn-pill" style="background: #475569;">Remove reviewed report</button>
                    </form>
                </div>
                <p style="margin-top: 16px; white-space: pre-wrap; overflow-wrap: anywhere;"><?php echo htmlspecialchars($report['reason'], ENT_QUOTES, 'UTF-8'); ?></p>
            </article>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
