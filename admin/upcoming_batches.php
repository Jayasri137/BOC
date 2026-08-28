<?php
// admin/upcoming_batches.php - Upcoming Batches CRUD Editor
$pageTitle = 'Upcoming Batches Manager';
require_once 'includes/header.php';

$alertSuccess = '';
$alertError = '';

// --- HANDLE POST REQUESTS (ADD, UPDATE, DELETE) ---
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = isset($_POST['action']) ? trim($_POST['action']) : '';
    
    // 1. ADD NEW BATCH
    if ($action === 'add') {
        $course_slug = isset($_POST['course_slug']) ? trim($_POST['course_slug']) : '';
        $start_date = isset($_POST['start_date']) ? trim($_POST['start_date']) : '';
        $batch_time = isset($_POST['batch_time']) ? trim($_POST['batch_time']) : '';
        $batch_mode = isset($_POST['batch_mode']) ? trim($_POST['batch_mode']) : '';
        $status = isset($_POST['status']) ? trim($_POST['status']) : '';
        $duration = isset($_POST['duration']) ? trim($_POST['duration']) : '';
        $is_active = isset($_POST['is_active']) ? 1 : 0;
        
        if (empty($course_slug) || empty($start_date) || empty($batch_time) || empty($batch_mode) || empty($status)) {
            $alertError = 'All fields are required.';
        } else {
            try {
                $stmt = $pdo->prepare("
                    INSERT INTO upcoming_batches (course_slug, start_date, batch_time, batch_mode, duration, status, is_active) 
                    VALUES (:course_slug, :start_date, :batch_time, :batch_mode, :duration, :status, :is_active)
                ");
                $stmt->execute([
                    'course_slug' => $course_slug,
                    'start_date' => $start_date,
                    'batch_time' => $batch_time,
                    'batch_mode' => $batch_mode,
                    'duration' => $duration,
                    'status' => $status,
                    'is_active' => $is_active
                ]);
                $alertSuccess = 'Batch added successfully!';
            } catch (PDOException $e) {
                $alertError = 'Failed to add batch: ' . $e->getMessage();
            }
        }
    }
    
    // 2. UPDATE BATCH
    elseif ($action === 'update') {
        $id = isset($_POST['batch_id']) ? intval($_POST['batch_id']) : 0;
        $course_slug = isset($_POST['course_slug']) ? trim($_POST['course_slug']) : '';
        $start_date = isset($_POST['start_date']) ? trim($_POST['start_date']) : '';
        $batch_time = isset($_POST['batch_time']) ? trim($_POST['batch_time']) : '';
        $batch_mode = isset($_POST['batch_mode']) ? trim($_POST['batch_mode']) : '';
        $status = isset($_POST['status']) ? trim($_POST['status']) : '';
        $duration = isset($_POST['duration']) ? trim($_POST['duration']) : '';
        $is_active = isset($_POST['is_active']) ? 1 : 0;
        
        if ($id <= 0 || empty($course_slug) || empty($start_date) || empty($batch_time) || empty($batch_mode) || empty($status)) {
            $alertError = 'All fields are required.';
        } else {
            try {
                $stmt = $pdo->prepare("
                    UPDATE upcoming_batches 
                    SET course_slug = :course_slug, start_date = :start_date, batch_time = :batch_time, batch_mode = :batch_mode, duration = :duration, status = :status, is_active = :is_active 
                    WHERE id = :id
                ");
                $stmt->execute([
                    'course_slug' => $course_slug, 'start_date' => $start_date, 'batch_time' => $batch_time, 'batch_mode' => $batch_mode,
                    'duration' => $duration, 'status' => $status, 'is_active' => $is_active, 'id' => $id
                ]);
                $alertSuccess = 'Batch updated successfully!';
            } catch (PDOException $e) {
                $alertError = 'Failed to update batch: ' . $e->getMessage();
            }
        }
    }
    
    // 3. DELETE BATCH
    elseif ($action === 'delete') {
        $id = isset($_POST['batch_id']) ? intval($_POST['batch_id']) : 0;
        if ($id > 0) {
            try {
                $stmt = $pdo->prepare("DELETE FROM upcoming_batches WHERE id = :id");
                $stmt->execute(['id' => $id]);
                $alertSuccess = 'Batch deleted successfully!';
            } catch (PDOException $e) {
                $alertError = 'Failed to delete batch: ' . $e->getMessage();
            }
        }
    }
}

// --- FETCH ALL BATCHES FOR DISPLAY ---
$limit = 50; 
$page = isset($_GET['p']) ? max(1, intval($_GET['p'])) : 1;
$offset = ($page - 1) * $limit;

try {
    $countQuery = $pdo->query("SELECT COUNT(*) FROM upcoming_batches");
    $total_records = $countQuery->fetchColumn();
    $total_pages = ceil($total_records / $limit);

    $stmt = $pdo->prepare("SELECT * FROM upcoming_batches ORDER BY id DESC LIMIT :limit OFFSET :offset");
    $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();
    $batches = $stmt->fetchAll();
} catch (PDOException $e) {
    $batches = [];
    $total_pages = 1;
}

$courses = [
    'pte' => 'PTE',
    'ielts' => 'IELTS',
    'german' => 'German',
    'japanese' => 'Japanese',
    'toefl' => 'TOEFL'
];
?>

<!-- Include SweetAlert2 for Delete Confirmation -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<div class="content-body">
    <!-- Header Area -->
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
        <div>
            <h2 class="page-title">Upcoming Batches Manager</h2>
            <p style="color: var(--text-secondary); font-size: 0.95rem; margin-top: 0.25rem;">Manage upcoming batches displayed on the test preparation pages.</p>
        </div>
        <button class="btn btn-primary" onclick="openAddModal()">
            <i class="fa-solid fa-plus"></i> Add Batch
        </button>
    </div>

    <!-- Alerts -->
    <?php if ($alertSuccess): ?>
        <div class="alert alert-success" style="margin-bottom: 2rem;">
            <i class="fa-solid fa-circle-check"></i> <?php echo $alertSuccess; ?>
        </div>
    <?php endif; ?>
    <?php if ($alertError): ?>
        <div class="alert alert-danger" style="margin-bottom: 2rem;">
            <i class="fa-solid fa-circle-exclamation"></i> <?php echo $alertError; ?>
        </div>
    <?php endif; ?>

    <!-- Data Display Area -->
    <?php if (empty($batches)): ?>
        <div class="panel-card" style="text-align: center; padding: 5rem 2rem; color: var(--text-secondary);">
            <i class="fa-solid fa-calendar-times" style="font-size: 3rem; margin-bottom: 1.5rem; color: var(--text-muted);"></i>
            <h3>No batches exist.</h3>
            <p style="margin-top: 0.5rem; font-size: 0.9rem;">Click "Add Batch" to start adding upcoming batches.</p>
        </div>
    <?php else: ?>
        <div class="table-responsive panel-card" style="padding: 0;">
            <table class="table" style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr>
                        <th style="padding: 1rem; text-align: left; border-bottom: 2px solid var(--border);">Course</th>
                        <th style="padding: 1rem; text-align: left; border-bottom: 2px solid var(--border);">Start Date</th>
                        <th style="padding: 1rem; text-align: left; border-bottom: 2px solid var(--border);">Timing</th>
                        <th style="padding: 1rem; text-align: left; border-bottom: 2px solid var(--border);">Mode</th>
                        <th style="padding: 1rem; text-align: left; border-bottom: 2px solid var(--border);">Duration</th>
                        <th style="padding: 1rem; text-align: left; border-bottom: 2px solid var(--border);">Status</th>
                        <th style="padding: 1rem; text-align: center; border-bottom: 2px solid var(--border);">Active</th>
                        <th style="padding: 1rem; text-align: right; border-bottom: 2px solid var(--border);">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($batches as $b): 
                        $isActive = intval($b['is_active']) === 1;
                        $courseName = isset($courses[$b['course_slug']]) ? $courses[$b['course_slug']] : strtoupper($b['course_slug']);
                    ?>
                        <tr>
                            <td style="padding: 1rem; border-bottom: 1px solid var(--border); font-weight: 600;"><?php echo clean_output($courseName); ?></td>
                            <td style="padding: 1rem; border-bottom: 1px solid var(--border);"><?php echo clean_output($b['start_date']); ?></td>
                            <td style="padding: 1rem; border-bottom: 1px solid var(--border);"><?php echo clean_output($b['batch_time']); ?></td>
                            <td style="padding: 1rem; border-bottom: 1px solid var(--border);"><?php echo clean_output($b['batch_mode']); ?></td>
                            <td style="padding: 1rem; border-bottom: 1px solid var(--border);"><?php echo clean_output($b['duration'] ?? ''); ?></td>
                            <td style="padding: 1rem; border-bottom: 1px solid var(--border);">
                                <?php
                                $statusColor = '#64748b';
                                $s = strtolower($b['status']);
                                if (strpos($s, 'filling') !== false || strpos($s, 'fast') !== false) $statusColor = '#f59e0b'; // orange
                                elseif (strpos($s, 'open') !== false) $statusColor = '#10b981'; // green
                                elseif (strpos($s, 'closed') !== false || strpos($s, 'full') !== false) $statusColor = '#ef4444'; // red
                                ?>
                                <span style="display: inline-block; padding: 0.2rem 0.6rem; border-radius: 50px; background: <?php echo $statusColor; ?>20; color: <?php echo $statusColor; ?>; font-size: 0.8rem; font-weight: 600;">
                                    <?php echo clean_output($b['status']); ?>
                                </span>
                            </td>
                            <td style="padding: 1rem; text-align: center; border-bottom: 1px solid var(--border);">
                                <?php if ($isActive): ?>
                                    <span style="color: #10b981;"><i class="fa-solid fa-check"></i></span>
                                <?php else: ?>
                                    <span style="color: #64748b;"><i class="fa-solid fa-xmark"></i></span>
                                <?php endif; ?>
                            </td>
                            <td style="padding: 1rem; text-align: right; border-bottom: 1px solid var(--border);">
                                <button class="btn-action action-edit" style="width: 32px; height: 32px; border-radius: 6px; background: rgba(59,130,246,0.1); color: #3b82f6; border: none; cursor: pointer; margin-right: 0.5rem;" title="Edit Batch" onclick="openEditModal(<?php echo htmlspecialchars(json_encode($b)); ?>)">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </button>
                                <button class="btn-action action-delete" style="width: 32px; height: 32px; border-radius: 6px; background: rgba(239,68,68,0.1); color: #ef4444; border: none; cursor: pointer;" title="Delete Batch" onclick="triggerDeleteBatch(<?php echo $b['id']; ?>, '<?php echo addslashes(clean_output($courseName . ' - ' . $b['start_date'])); ?>')">
                                    <i class="fa-solid fa-trash-can"></i>
                                </button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <?php if ($total_pages > 1): ?>
        <div class="pagination" style="margin-top: 2rem; display: flex; justify-content: center; gap: 0.5rem;">
            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                <a href="?p=<?php echo $i; ?>" class="btn <?php echo $i === $page ? 'btn-primary' : 'btn-outline'; ?>" style="padding: 0.5rem 1rem;"><?php echo $i; ?></a>
            <?php endfor; ?>
        </div>
        <?php endif; ?>
    <?php endif; ?>

</div>

<!-- Modal Background Overlay -->
<div id="modalOverlay" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(15,23,42,0.6); backdrop-filter:blur(4px); z-index:999; opacity:0; transition:opacity 0.3s;"></div>

<!-- Add/Edit Batch Modal -->
<div id="batchModal" style="display:none; position:fixed; top:50%; left:50%; transform:translate(-50%, -50%) scale(0.95); background:white; width:90%; max-width:600px; border-radius:16px; box-shadow:0 25px 50px rgba(0,0,0,0.2); z-index:1000; opacity:0; transition:all 0.3s; max-height:90vh; overflow-y:auto;">
    
    <div style="padding: 1.5rem 2rem; border-bottom: 1px solid var(--border); display: flex; justify-content: space-between; align-items: center; background: #f8fafc; border-radius: 16px 16px 0 0;">
        <h3 id="modalTitle" style="margin: 0; font-size: 1.25rem; font-weight: 700; color: var(--dark);">Add New Batch</h3>
        <button type="button" onclick="closeModal()" style="background: none; border: none; font-size: 1.5rem; color: var(--text-muted); cursor: pointer; transition: color 0.3s;">&times;</button>
    </div>
    
    <form id="batchForm" action="upcoming_batches.php" method="POST" style="padding: 2rem;">
        <input type="hidden" name="action" id="formAction" value="add">
        <input type="hidden" name="batch_id" id="batchId" value="">
        
        <div class="form-group" style="margin-bottom: 1.5rem;">
            <label style="display: block; font-weight: 600; margin-bottom: 0.5rem; font-size: 0.9rem; color: var(--dark);">Course *</label>
            <select name="course_slug" id="inputCourseSlug" class="form-control" required style="width: 100%; padding: 0.75rem; border: 1px solid var(--border); border-radius: 8px; font-family: inherit; font-size: 0.95rem; outline: none; transition: border-color 0.3s;">
                <option value="">Select Course</option>
                <?php foreach($courses as $slug => $name): ?>
                    <option value="<?php echo $slug; ?>"><?php echo $name; ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
            <div class="form-group" style="margin-bottom: 1.5rem;">
                <label style="display: block; font-weight: 600; margin-bottom: 0.5rem; font-size: 0.9rem; color: var(--dark);">Start Date *</label>
                <input type="text" name="start_date" id="inputStartDate" class="form-control" placeholder="e.g., 15th Sept, 2026" required style="width: 100%; padding: 0.75rem; border: 1px solid var(--border); border-radius: 8px; font-family: inherit; font-size: 0.95rem; outline: none; transition: border-color 0.3s;">
            </div>

            <div class="form-group" style="margin-bottom: 1.5rem;">
                <label style="display: block; font-weight: 600; margin-bottom: 0.5rem; font-size: 0.9rem; color: var(--dark);">Timing *</label>
                <input type="text" name="batch_time" id="inputBatchTime" class="form-control" placeholder="e.g., 10:00 AM - 12:00 PM" required style="width: 100%; padding: 0.75rem; border: 1px solid var(--border); border-radius: 8px; font-family: inherit; font-size: 0.95rem; outline: none; transition: border-color 0.3s;">
            </div>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
            <div class="form-group" style="margin-bottom: 1.5rem;">
                <label style="display: block; font-weight: 600; margin-bottom: 0.5rem; font-size: 0.9rem; color: var(--dark);">Mode *</label>
                <select name="batch_mode" id="inputBatchMode" class="form-control" required style="width: 100%; padding: 0.75rem; border: 1px solid var(--border); border-radius: 8px; font-family: inherit; font-size: 0.95rem; outline: none; transition: border-color 0.3s;">
                    <option value="Online">Online</option>
                    <option value="Offline">Offline</option>
                    <option value="Hybrid">Hybrid</option>
                </select>
            </div>

                        <div class="form-group" style="margin-bottom: 1.5rem;">
                <label style="display: block; font-weight: 600; margin-bottom: 0.5rem; font-size: 0.9rem; color: var(--dark);">Duration</label>
                <input type="text" name="duration" id="inputDuration" class="form-control" placeholder="e.g., 1.5 Months, 11 Days" style="width: 100%; padding: 0.75rem; border: 1px solid var(--border); border-radius: 8px; font-family: inherit; font-size: 0.95rem; outline: none; transition: border-color 0.3s;">
            </div>

            <div class="form-group" style="margin-bottom: 1.5rem;">
                <label style="display: block; font-weight: 600; margin-bottom: 0.5rem; font-size: 0.9rem; color: var(--dark);">Status *</label>
                <input type="text" name="status" id="inputStatus" class="form-control" placeholder="e.g., Filling Fast, Open" required style="width: 100%; padding: 0.75rem; border: 1px solid var(--border); border-radius: 8px; font-family: inherit; font-size: 0.95rem; outline: none; transition: border-color 0.3s;">
            </div>
        </div>

        <div class="form-group" style="margin-bottom: 1.5rem;">
            <label style="display: flex; align-items: center; cursor: pointer; gap: 0.5rem;">
                <input type="checkbox" name="is_active" id="inputIsActive" value="1" checked style="width: 18px; height: 18px; accent-color: var(--primary);">
                <span style="font-weight: 500; font-size: 0.95rem; color: var(--dark);">Batch is Active (visible on website)</span>
            </label>
        </div>

        <div style="display: flex; justify-content: flex-end; gap: 1rem; margin-top: 2rem;">
            <button type="button" class="btn btn-outline" onclick="closeModal()">Cancel</button>
            <button type="submit" class="btn btn-primary" id="btnSubmit">Save Batch</button>
        </div>
    </form>
</div>

<!-- Hidden form for deleting -->
<form id="deleteForm" action="upcoming_batches.php" method="POST" style="display:none;">
    <input type="hidden" name="action" value="delete">
    <input type="hidden" name="batch_id" id="deleteBatchId" value="">
</form>

<script>
    const overlay = document.getElementById('modalOverlay');
    const modal = document.getElementById('batchModal');
    
    function openAddModal() {
        document.getElementById('modalTitle').innerText = 'Add New Batch';
        document.getElementById('formAction').value = 'add';
        document.getElementById('batchId').value = '';
        document.getElementById('inputCourseSlug').value = '';
        document.getElementById('inputStartDate').value = '';
        document.getElementById('inputBatchTime').value = '';
        document.getElementById('inputBatchMode').value = 'Online';
        document.getElementById('inputDuration').value = '';
        document.getElementById('inputStatus').value = 'Open';
        document.getElementById('inputIsActive').checked = true;
        
        showModal();
    }
    
    function openEditModal(b) {
        document.getElementById('modalTitle').innerText = 'Edit Batch';
        document.getElementById('formAction').value = 'update';
        document.getElementById('batchId').value = b.id;
        document.getElementById('inputCourseSlug').value = b.course_slug;
        document.getElementById('inputStartDate').value = b.start_date;
        document.getElementById('inputBatchTime').value = b.batch_time;
        document.getElementById('inputBatchMode').value = b.batch_mode;
        document.getElementById('inputDuration').value = b.duration || '';
        document.getElementById('inputStatus').value = b.status;
        document.getElementById('inputIsActive').checked = parseInt(b.is_active) === 1;
        
        showModal();
    }
    
    function showModal() {
        overlay.style.display = 'block';
        modal.style.display = 'block';
        // trigger reflow
        void modal.offsetWidth;
        overlay.style.opacity = '1';
        modal.style.opacity = '1';
        modal.style.transform = 'translate(-50%, -50%) scale(1)';
    }
    
    function closeModal() {
        overlay.style.opacity = '0';
        modal.style.opacity = '0';
        modal.style.transform = 'translate(-50%, -50%) scale(0.95)';
        setTimeout(() => {
            overlay.style.display = 'none';
            modal.style.display = 'none';
        }, 300);
    }
    
    // Delete Confirmation with SweetAlert2
    function triggerDeleteBatch(id, name) {
        Swal.fire({
            title: 'Are you sure?',
            text: "You are about to delete batch: " + name,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#64748b',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('deleteBatchId').value = id;
                document.getElementById('deleteForm').submit();
            }
        });
    }

    // Input focus effects
    document.querySelectorAll('.form-control').forEach(input => {
        input.addEventListener('focus', function() {
            this.style.borderColor = 'var(--primary)';
            this.style.boxShadow = '0 0 0 3px rgba(37, 99, 235, 0.1)';
        });
        input.addEventListener('blur', function() {
            this.style.borderColor = 'var(--border)';
            this.style.boxShadow = 'none';
        });
    });
</script>

<?php require_once 'includes/footer.php'; ?>
