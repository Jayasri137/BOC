<?php
$pageTitle = 'Dreamwall Moderation';
require_once __DIR__ . '/includes/header.php';

if (empty($_SESSION['community_dreamwall_admin_csrf'])) {
    $_SESSION['community_dreamwall_admin_csrf'] = bin2hex(random_bytes(32));
}

$csrfToken = $_SESSION['community_dreamwall_admin_csrf'];
$alertSuccess = '';
$alertError = '';
$milestoneTypes = ['Visa Approved', 'Offer Letter', 'Scholarship', 'IELTS Success'];

function dreamwallAdminLength(string $value): int
{
    $length = preg_match_all('/./us', $value);
    return $length === false ? PHP_INT_MAX : $length;
}

function dreamwallAdminStoreProofImage(array $file): ?string
{
    if (!isset($file['error']) || $file['error'] === UPLOAD_ERR_NO_FILE) {
        return null;
    }
    if ($file['error'] !== UPLOAD_ERR_OK || !isset($file['tmp_name'], $file['size']) ||
        !is_string($file['tmp_name']) || !is_int($file['size']) ||
        $file['size'] < 1 || $file['size'] > 5 * 1024 * 1024 ||
        !is_uploaded_file($file['tmp_name'])) {
        throw new RuntimeException('Choose a proof image no larger than 5 MB.');
    }

    $imageInfo = @getimagesize($file['tmp_name']);
    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $mimeType = $finfo->file($file['tmp_name']);
    $extensions = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/webp' => 'webp'];
    if ($imageInfo === false || !isset($extensions[$mimeType]) || $imageInfo['mime'] !== $mimeType) {
        throw new RuntimeException('Choose a valid JPG, PNG, or WebP proof image.');
    }

    $directory = dirname(__DIR__) . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR . 'dreamwall';
    if (!is_dir($directory) && !mkdir($directory, 0755, true) && !is_dir($directory)) {
        throw new RuntimeException('Proof image storage is unavailable right now.');
    }
    $fileName = bin2hex(random_bytes(16)) . '.' . $extensions[$mimeType];
    if (!move_uploaded_file($file['tmp_name'], $directory . DIRECTORY_SEPARATOR . $fileName)) {
        throw new RuntimeException('The proof image could not be saved.');
    }
    return 'uploads/dreamwall/' . $fileName;
}

function dreamwallAdminRemoveProofImage(?string $path): void
{
    $prefix = 'uploads/dreamwall/';
    if (!is_string($path) || strpos($path, $prefix) !== 0) {
        return;
    }
    $fileName = substr($path, strlen($prefix));
    if ($fileName === '' || basename($fileName) !== $fileName) {
        return;
    }
    $filePath = dirname(__DIR__) . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR . 'dreamwall' . DIRECTORY_SEPARATOR . $fileName;
    if (is_file($filePath) && !unlink($filePath)) {
        error_log('Unable to remove Dreamwall proof image: ' . $filePath);
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = isset($_POST['action']) && is_string($_POST['action']) ? $_POST['action'] : '';
    $milestoneId = filter_var($_POST['milestone_id'] ?? null, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
    $postedToken = $_POST['csrf_token'] ?? null;

    if (!is_string($postedToken) || !hash_equals($csrfToken, $postedToken)) {
        $alertError = 'Your session expired. Refresh the page and try again.';
    } elseif ($milestoneId === false) {
        $alertError = 'Select a valid milestone.';
    } else {
        $newProofPath = null;
        try {
            if ($action === 'approve' || $action === 'reject') {
                $status = $action === 'approve' ? 'approved' : 'rejected';
                $stmt = $pdo->prepare("
                    UPDATE community_dreamwall
                    SET review_status = ?, reviewed_by = ?, reviewed_at = CURRENT_TIMESTAMP
                    WHERE id = ?
                ");
                $stmt->execute([$status, $_SESSION['admin_full_name'] ?? 'Administrator', $milestoneId]);
                $alertSuccess = $action === 'approve'
                    ? 'Milestone approved and published to the student community.'
                    : 'Milestone rejected. It will not appear in the student feed.';
            } elseif ($action === 'toggle_visibility') {
                $stmt = $pdo->prepare('UPDATE community_dreamwall SET is_active = IF(is_active = 1, 0, 1) WHERE id = ?');
                $stmt->execute([$milestoneId]);
                $alertSuccess = 'Milestone visibility updated.';
            } elseif ($action === 'delete') {
                $proofStmt = $pdo->prepare('SELECT proof_image_url FROM community_dreamwall WHERE id = ?');
                $proofStmt->execute([$milestoneId]);
                $oldProofPath = $proofStmt->fetchColumn();
                $pdo->beginTransaction();
                $pdo->prepare('DELETE FROM community_dreamwall_cheers WHERE dreamwall_id = ?')->execute([$milestoneId]);
                $stmt = $pdo->prepare('DELETE FROM community_dreamwall WHERE id = ?');
                $stmt->execute([$milestoneId]);
                $pdo->commit();
                dreamwallAdminRemoveProofImage(is_string($oldProofPath) ? $oldProofPath : null);
                $alertSuccess = 'Milestone deleted.';
            } elseif ($action === 'save') {
                $title = trim((string) ($_POST['title'] ?? ''));
                $university = trim((string) ($_POST['university_name'] ?? ''));
                $country = trim((string) ($_POST['country'] ?? ''));
                $countryFlag = trim((string) ($_POST['country_flag'] ?? ''));
                $type = trim((string) ($_POST['milestone_type'] ?? ''));
                $story = trim((string) ($_POST['story'] ?? ''));
                $intake = trim((string) ($_POST['intake'] ?? ''));
                $removeProof = isset($_POST['remove_proof_image']) && $_POST['remove_proof_image'] === '1';
                if ($title === '' || $university === '' || $country === '' || $story === '' || $intake === '') {
                    $alertError = 'Headline, university, country, story, and intake are required.';
                } elseif (!in_array($type, $milestoneTypes, true)) {
                    $alertError = 'Choose a valid milestone type.';
                } elseif (
                    dreamwallAdminLength($title) > 255 || dreamwallAdminLength($university) > 255 ||
                    dreamwallAdminLength($country) > 100 || dreamwallAdminLength($countryFlag) > 20 ||
                    dreamwallAdminLength($story) > 1200 || dreamwallAdminLength($intake) > 100
                ) {
                    $alertError = 'One or more fields exceed the allowed length.';
                } else {
                    $currentProofStmt = $pdo->prepare('SELECT proof_image_url FROM community_dreamwall WHERE id = ?');
                    $currentProofStmt->execute([$milestoneId]);
                    $oldProofPath = $currentProofStmt->fetchColumn();
                    $newProofPath = isset($_FILES['proof_image']) && is_array($_FILES['proof_image'])
                        ? dreamwallAdminStoreProofImage($_FILES['proof_image'])
                        : null;
                    $proofImagePath = $newProofPath !== null
                        ? $newProofPath
                        : ($removeProof ? null : $oldProofPath);
                    $stmt = $pdo->prepare("
                        UPDATE community_dreamwall
                        SET title = ?, university_name = ?, country = ?, country_flag = ?,
                            milestone_type = ?, story = ?, intake = ?, proof_image_url = ?
                        WHERE id = ?
                    ");
                    $stmt->execute([
                        $title, $university, $country, $countryFlag ?: null, $type,
                        $story, $intake, $proofImagePath ?: null, $milestoneId
                    ]);
                    if (($newProofPath !== null || $removeProof) && is_string($oldProofPath)) {
                        dreamwallAdminRemoveProofImage($oldProofPath);
                    }
                    $newProofPath = null;
                    $alertSuccess = 'Milestone details updated.';
                }
            } else {
                $alertError = 'Choose a valid moderation action.';
            }
        } catch (Throwable $e) {
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }
            if ($newProofPath !== null) {
                dreamwallAdminRemoveProofImage($newProofPath);
            }
            if ($e instanceof PDOException) {
                error_log('Admin Dreamwall action failed: ' . $e->getMessage());
                $alertError = 'The milestone could not be updated. Please confirm Dreamwall tables have been created in Database setup.';
            } else {
                $alertError = $e->getMessage();
            }
        }
    }
}

$statusFilter = isset($_GET['status']) && in_array($_GET['status'], ['pending', 'approved', 'rejected', 'all'], true)
    ? $_GET['status']
    : 'pending';
$counts = ['pending' => 0, 'approved' => 0, 'rejected' => 0];
$milestones = [];
try {
    foreach ($pdo->query("SELECT review_status, COUNT(*) AS total FROM community_dreamwall GROUP BY review_status")->fetchAll(PDO::FETCH_ASSOC) as $row) {
        if (isset($counts[$row['review_status']])) {
            $counts[$row['review_status']] = (int) $row['total'];
        }
    }

    $sql = "
        SELECT d.*, u.email AS student_email
        FROM community_dreamwall d
        LEFT JOIN users u ON u.id = d.student_id
    ";
    $params = [];
    if ($statusFilter !== 'all') {
        $sql .= ' WHERE d.review_status = ?';
        $params[] = $statusFilter;
    }
    $sql .= ' ORDER BY (d.review_status = \'pending\') DESC, d.created_at DESC LIMIT 200';
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $milestones = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    error_log('Admin Dreamwall lookup failed: ' . $e->getMessage());
    $alertError = 'Dreamwall records are unavailable. Run Database setup to create the Dreamwall tables.';
}
?>

<div style="margin-bottom: 1.5rem;">
    <h1 class="page-title">Dreamwall Moderation</h1>
    <p style="color: var(--text-muted);">Review student achievements before publishing them to the community feed.</p>
</div>

<?php if ($alertSuccess !== ''): ?>
    <div class="alert alert-success"><i class="fa-solid fa-check"></i> <?php echo htmlspecialchars($alertSuccess, ENT_QUOTES, 'UTF-8'); ?></div>
<?php endif; ?>
<?php if ($alertError !== ''): ?>
    <div class="alert alert-danger"><i class="fa-solid fa-triangle-exclamation"></i> <?php echo htmlspecialchars($alertError, ENT_QUOTES, 'UTF-8'); ?></div>
<?php endif; ?>

<nav style="display:flex;flex-wrap:wrap;gap:10px;margin:18px 0;" aria-label="Filter milestones">
    <?php foreach (['pending' => 'Pending review', 'approved' => 'Approved', 'rejected' => 'Rejected', 'all' => 'All'] as $value => $label): ?>
        <a href="?status=<?php echo urlencode($value); ?>" class="btn-pill" style="text-decoration:none;<?php echo $statusFilter === $value ? '' : 'background:#64748b;'; ?>">
            <?php echo htmlspecialchars($label, ENT_QUOTES, 'UTF-8'); ?>
            <?php if (isset($counts[$value])): ?><span>(<?php echo $counts[$value]; ?>)</span><?php endif; ?>
        </a>
    <?php endforeach; ?>
</nav>

<?php if ($milestones === [] && $alertError === ''): ?>
    <div class="profile-card" style="padding:30px;text-align:center;">
        <i class="fa-solid fa-award" style="font-size:2rem;color:#64748b;"></i>
        <p style="margin-top:10px;">No milestones in this queue.</p>
    </div>
<?php else: ?>
    <div style="display:grid;gap:18px;">
        <?php foreach ($milestones as $milestone): ?>
            <?php
                $milestoneId = (int) $milestone['id'];
                $reviewStatus = (string) $milestone['review_status'];
                $isVisible = (int) $milestone['is_active'] === 1;
                $proofPath = (string) ($milestone['proof_image_url'] ?? '');
                $proofScheme = strtolower((string) parse_url($proofPath, PHP_URL_SCHEME));
                $proofDisplayUrl = in_array($proofScheme, ['http', 'https'], true)
                    ? $proofPath
                    : (strpos($proofPath, 'uploads/dreamwall/') === 0 && basename($proofPath) === substr($proofPath, strlen('uploads/dreamwall/'))
                        ? '../' . $proofPath
                        : '');
            ?>
            <article class="profile-card" style="padding:20px;">
                <div style="display:flex;justify-content:space-between;align-items:flex-start;gap:16px;flex-wrap:wrap;">
                    <div>
                        <strong><?php echo htmlspecialchars($milestone['student_name'], ENT_QUOTES, 'UTF-8'); ?></strong>
                        <span style="color:var(--text-muted);"><?php echo htmlspecialchars($milestone['student_email'] ?? '', ENT_QUOTES, 'UTF-8'); ?></span>
                        <div style="margin-top:5px;color:var(--text-muted);font-size:12px;">
                            Submitted <?php echo htmlspecialchars((string) $milestone['created_at'], ENT_QUOTES, 'UTF-8'); ?>
                            · <?php echo htmlspecialchars($reviewStatus, ENT_QUOTES, 'UTF-8'); ?>
                            <?php if (!$isVisible): ?> · Hidden<?php endif; ?>
                        </div>
                    </div>
                    <div style="display:flex;gap:8px;flex-wrap:wrap;">
                        <?php if ($reviewStatus !== 'approved'): ?>
                            <form method="POST">
                                <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrfToken, ENT_QUOTES, 'UTF-8'); ?>">
                                <input type="hidden" name="milestone_id" value="<?php echo $milestoneId; ?>">
                                <input type="hidden" name="action" value="approve">
                                <button class="btn-pill" type="submit"><i class="fa-solid fa-check"></i> Approve & publish</button>
                            </form>
                        <?php endif; ?>
                        <?php if ($reviewStatus !== 'rejected'): ?>
                            <form method="POST">
                                <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrfToken, ENT_QUOTES, 'UTF-8'); ?>">
                                <input type="hidden" name="milestone_id" value="<?php echo $milestoneId; ?>">
                                <input type="hidden" name="action" value="reject">
                                <button class="btn-pill" type="submit" style="background:#b91c1c;">Reject</button>
                            </form>
                        <?php endif; ?>
                        <?php if ($reviewStatus === 'approved'): ?>
                            <form method="POST">
                                <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrfToken, ENT_QUOTES, 'UTF-8'); ?>">
                                <input type="hidden" name="milestone_id" value="<?php echo $milestoneId; ?>">
                                <input type="hidden" name="action" value="toggle_visibility">
                                <button class="btn-pill" type="submit" style="background:#475569;"><?php echo $isVisible ? 'Hide from feed' : 'Show in feed'; ?></button>
                            </form>
                        <?php endif; ?>
                        <form method="POST" onsubmit="return confirm('Delete this milestone and its cheers permanently?');">
                            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrfToken, ENT_QUOTES, 'UTF-8'); ?>">
                            <input type="hidden" name="milestone_id" value="<?php echo $milestoneId; ?>">
                            <input type="hidden" name="action" value="delete">
                            <button class="btn-pill" type="submit" style="background:#334155;">Delete</button>
                        </form>
                    </div>
                </div>
                <details style="margin-top:16px;">
                    <summary style="cursor:pointer;font-weight:600;">View / edit milestone details</summary>
                    <form method="POST" enctype="multipart/form-data" style="display:grid;grid-template-columns:repeat(auto-fit,minmax(210px,1fr));gap:12px;margin-top:14px;">
                        <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrfToken, ENT_QUOTES, 'UTF-8'); ?>">
                        <input type="hidden" name="milestone_id" value="<?php echo $milestoneId; ?>">
                        <input type="hidden" name="action" value="save">
                        <label style="display:grid;gap:5px;">Headline
                            <input class="form-control" name="title" maxlength="255" required value="<?php echo htmlspecialchars($milestone['title'], ENT_QUOTES, 'UTF-8'); ?>">
                        </label>
                        <label style="display:grid;gap:5px;">Milestone type
                            <select class="form-control" name="milestone_type" required>
                                <?php foreach ($milestoneTypes as $type): ?>
                                    <option value="<?php echo htmlspecialchars($type, ENT_QUOTES, 'UTF-8'); ?>" <?php echo $milestone['milestone_type'] === $type ? 'selected' : ''; ?>><?php echo htmlspecialchars($type, ENT_QUOTES, 'UTF-8'); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </label>
                        <label style="display:grid;gap:5px;">University
                            <input class="form-control" name="university_name" maxlength="255" required value="<?php echo htmlspecialchars($milestone['university_name'], ENT_QUOTES, 'UTF-8'); ?>">
                        </label>
                        <label style="display:grid;gap:5px;">Country
                            <input class="form-control" name="country" maxlength="100" required value="<?php echo htmlspecialchars($milestone['country'], ENT_QUOTES, 'UTF-8'); ?>">
                        </label>
                        <label style="display:grid;gap:5px;">Country flag
                            <input class="form-control" name="country_flag" maxlength="20" value="<?php echo htmlspecialchars($milestone['country_flag'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                        </label>
                        <label style="display:grid;gap:5px;">Intake
                            <input class="form-control" name="intake" maxlength="100" required value="<?php echo htmlspecialchars($milestone['intake'], ENT_QUOTES, 'UTF-8'); ?>">
                        </label>
                        <label style="display:grid;gap:5px;grid-column:1/-1;">Story / preparation advice
                            <textarea class="form-control" name="story" maxlength="1200" rows="4" required><?php echo htmlspecialchars($milestone['story'], ENT_QUOTES, 'UTF-8'); ?></textarea>
                        </label>
                        <div style="display:grid;gap:8px;grid-column:1/-1;">
                            <strong>Proof image (optional)</strong>
                            <?php if ($proofDisplayUrl !== ''): ?>
                                <a href="<?php echo htmlspecialchars($proofDisplayUrl, ENT_QUOTES, 'UTF-8'); ?>" target="_blank" rel="noopener noreferrer">
                                    <img src="<?php echo htmlspecialchars($proofDisplayUrl, ENT_QUOTES, 'UTF-8'); ?>" alt="Proof for <?php echo htmlspecialchars($milestone['title'], ENT_QUOTES, 'UTF-8'); ?>" style="display:block;max-width:min(100%,360px);max-height:240px;border:1px solid #e2e8f0;border-radius:8px;object-fit:contain;">
                                </a>
                            <?php else: ?>
                                <span style="color:var(--text-muted);">No proof image was submitted.</span>
                            <?php endif; ?>
                            <label style="display:grid;gap:5px;">Upload or replace image
                                <input class="form-control" name="proof_image" type="file" accept="image/jpeg,image/png,image/webp">
                                <small style="color:var(--text-muted);">Optional. JPG, PNG, or WebP up to 5 MB.</small>
                            </label>
                            <?php if (!empty($milestone['proof_image_url'])): ?>
                                <label style="display:flex;align-items:center;gap:8px;">
                                    <input type="checkbox" name="remove_proof_image" value="1">
                                    Remove current proof image (unless replaced above)
                                </label>
                            <?php endif; ?>
                        </div>
                        <div><button class="btn-pill" type="submit">Save edits</button></div>
                    </form>
                </details>
            </article>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
