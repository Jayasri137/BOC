<?php
// admin/testimonials.php - Student Testimonials CRUD Editor
$pageTitle = 'Testimonials Manager';
require_once 'includes/header.php'; // handles session and pdo load

$alertSuccess = '';
$alertError = '';

// --- HANDLE POST REQUESTS (ADD, UPDATE, DELETE) ---
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = isset($_POST['action']) ? trim($_POST['action']) : '';
    
    // 1. ADD NEW TESTIMONIAL
    if ($action === 'add') {
        $name = isset($_POST['name']) ? trim($_POST['name']) : '';
        $detail = isset($_POST['detail']) ? trim($_POST['detail']) : '';
        $initials = isset($_POST['initials']) ? strtoupper(trim($_POST['initials'])) : '';
        $text = isset($_POST['text']) ? trim($_POST['text']) : '';
        $stars = isset($_POST['stars']) ? intval($_POST['stars']) : 5;
        $color = isset($_POST['color']) ? trim($_POST['color']) : 'blue';
        $is_active = isset($_POST['is_active']) ? 1 : 0;
        
        // Auto initials generator if empty
        if (empty($initials) && !empty($name)) {
            $parts = explode(' ', $name);
            foreach ($parts as $p) {
                $initials .= substr($p, 0, 1);
            }
            $initials = strtoupper(substr($initials, 0, 2));
        }
        
        if (empty($name) || empty($detail) || empty($text)) {
            $alertError = 'Student Name, Program Detail (e.g., MS in US), and Review Text are required.';
        } else {
            try {
                $stmt = $pdo->prepare("
                    INSERT INTO testimonials (name, detail, initials, text, stars, color, is_active) 
                    VALUES (:name, :detail, :initials, :text, :stars, :color, :is_active)
                ");
                $stmt->execute([
                    'name' => $name,
                    'detail' => $detail,
                    'initials' => $initials,
                    'text' => $text,
                    'stars' => $stars,
                    'color' => $color,
                    'is_active' => $is_active
                ]);
                $alertSuccess = 'Student testimonial added successfully!';
            } catch (PDOException $e) {
                $alertError = 'Failed to add testimonial: ' . $e->getMessage();
            }
        }
    }
    
    // 2. UPDATE TESTIMONIAL
    elseif ($action === 'update') {
        $id = isset($_POST['testimonial_id']) ? intval($_POST['testimonial_id']) : 0;
        $name = isset($_POST['name']) ? trim($_POST['name']) : '';
        $detail = isset($_POST['detail']) ? trim($_POST['detail']) : '';
        $initials = isset($_POST['initials']) ? strtoupper(trim($_POST['initials'])) : '';
        $text = isset($_POST['text']) ? trim($_POST['text']) : '';
        $stars = isset($_POST['stars']) ? intval($_POST['stars']) : 5;
        $color = isset($_POST['color']) ? trim($_POST['color']) : 'blue';
        $is_active = isset($_POST['is_active']) ? 1 : 0;
        
        if (empty($initials) && !empty($name)) {
            $parts = explode(' ', $name);
            foreach ($parts as $p) {
                $initials .= substr($p, 0, 1);
            }
            $initials = strtoupper(substr($initials, 0, 2));
        }
        
        if ($id <= 0 || empty($name) || empty($detail) || empty($text)) {
            $alertError = 'All standard fields are required to update the testimonial.';
        } else {
            try {
                $stmt = $pdo->prepare("
                    UPDATE testimonials 
                    SET name = :name, 
                        detail = :detail, 
                        initials = :initials, 
                        text = :text, 
                        stars = :stars, 
                        color = :color, 
                        is_active = :is_active 
                    WHERE id = :id
                ");
                $stmt->execute([
                    'name' => $name,
                    'detail' => $detail,
                    'initials' => $initials,
                    'text' => $text,
                    'stars' => $stars,
                    'color' => $color,
                    'is_active' => $is_active,
                    'id' => $id
                ]);
                $alertSuccess = 'Student testimonial updated successfully!';
            } catch (PDOException $e) {
                $alertError = 'Failed to update testimonial: ' . $e->getMessage();
            }
        }
    }
    
    // 3. DELETE TESTIMONIAL
    elseif ($action === 'delete') {
        $id = isset($_POST['testimonial_id']) ? intval($_POST['testimonial_id']) : 0;
        if ($id <= 0) {
            $alertError = 'Invalid testimonial ID specified for deletion.';
        } else {
            try {
                $stmt = $pdo->prepare("DELETE FROM testimonials WHERE id = :id");
                $stmt->execute(['id' => $id]);
                $alertSuccess = 'Student testimonial deleted permanently!';
            } catch (PDOException $e) {
                $alertError = 'Failed to delete testimonial: ' . $e->getMessage();
            }
        }
    }
}

// Fetch all testimonials
$testimonials = [];
$setupNeeded = false;
try {
    $stmt = $pdo->query("SELECT * FROM testimonials ORDER BY id ASC");
    $testimonials = $stmt->fetchAll();
} catch (PDOException $e) {
    $setupNeeded = true;
    $alertError = 'Could not fetch testimonials: ' . $e->getMessage() . '. Have you initialized database tables?';
}
?>

<div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 2.5rem; flex-wrap: wrap; gap: 1rem;">
    <h1 class="page-title">
        Student Reviews Manager
        <span>Manage, publish, and edit student testimonials and success stories displayed on the front-page</span>
    </h1>
    <?php if (!$setupNeeded): ?>
        <button class="btn-pill" onclick="openAddModal()">
            <i class="fa-solid fa-plus"></i>
            <span>Add Student Testimonial</span>
        </button>
    <?php endif; ?>
</div>

<?php if (!empty($alertSuccess)): ?>
    <div class="alert alert-success">
        <i class="fa-solid fa-circle-check"></i>
        <span><?php echo clean_output($alertSuccess); ?></span>
    </div>
<?php endif; ?>

<?php if (!empty($alertError)): ?>
    <div class="alert alert-danger">
        <i class="fa-solid fa-triangle-exclamation"></i>
        <span><?php echo clean_output($alertError); ?></span>
    </div>
<?php endif; ?>

<?php if ($setupNeeded): ?>
    <div class="panel-card" style="text-align: center; padding: 4rem 2rem;">
        <div style="font-size: 3.5rem; color: var(--warning); margin-bottom: 1.5rem;"><i class="fa-solid fa-database"></i></div>
        <h2>Database Setup Required</h2>
        <p style="color: var(--text-secondary); max-width: 500px; margin: 1rem auto 2rem; line-height: 1.6;">Please initialize the admin tables first by clicking below to seed default values.</p>
        <a href="setup.php" class="btn-pill" style="padding: 1rem 2rem;"><i class="fa-solid fa-screwdriver-wrench"></i> Run Database Installer</a>
    </div>
<?php else: ?>

    <?php if (empty($testimonials)): ?>
        <div class="panel-card" style="text-align: center; padding: 5rem 2rem; color: var(--text-secondary);">
            <i class="fa-solid fa-folder-open" style="font-size: 3rem; margin-bottom: 1.5rem; color: var(--text-muted);"></i>
            <h3>No testimonials exist in the database.</h3>
            <p style="margin-top: 0.5rem; font-size: 0.9rem;">Click the "Add Student Testimonial" button to write your first student review!</p>
        </div>
    <?php else: ?>
        <div class="crud-grid">
            <?php foreach ($testimonials as $t): 
                $isActive = intval($t['is_active']) === 1;
                $color = clean_output($t['color'] ?: 'blue');
                $initials = clean_output($t['initials'] ?: 'ST');
                $name = clean_output($t['name']);
            ?>
                <div class="crud-card <?php echo $isActive ? 'crud-card-active' : 'crud-card-inactive'; ?>">
                    <div class="crud-card-badge-icon">
                        <i class="fa-solid <?php echo $isActive ? 'fa-check' : 'fa-eye-slash'; ?>"></i>
                    </div>
                    
                    <div class="crud-card-header">
                        <div class="user-avatar user-avatar--<?php echo $color; ?>" style="width: 48px; height: 48px; font-weight: 700;">
                            <?php echo $initials; ?>
                        </div>
                        <div style="flex: 1;">
                            <h4 class="crud-card-title" style="margin-bottom: 0.1rem;"><?php echo $name; ?></h4>
                            <span style="font-size: 0.78rem; color: var(--text-secondary); display: block; line-height: 1.2;"><?php echo clean_output($t['detail']); ?></span>
                        </div>
                    </div>
                    
                    <div style="color: var(--warning); font-size: 0.85rem; margin-bottom: 0.75rem;">
                        <?php echo str_repeat('⭐', intval($t['stars'])); ?>
                    </div>
                    
                    <p class="crud-card-desc" style="-webkit-line-clamp: 4; font-style: italic; font-size: 0.85rem; color: var(--text-secondary);">
                        "<?php echo clean_output($t['text']); ?>"
                    </p>
                    
                    <div class="crud-card-footer">
                        <span class="crud-card-info">Theme: <strong><?php echo ucfirst($color); ?></strong></span>
                        <div class="crud-card-actions">
                            <button class="btn-action action-edit" title="Edit Testimonial" onclick="openEditModal(<?php echo htmlspecialchars(json_encode($t)); ?>)">
                                <i class="fa-solid fa-pen-to-square"></i>
                            </button>
                            <button class="btn-action action-delete" title="Delete Testimonial" onclick="triggerDeleteTestimonial(<?php echo $t['id']; ?>, '<?php echo $name; ?>')">
                                <i class="fa-solid fa-trash-can"></i>
                            </button>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

<?php endif; ?>

<!-- 1. ADD / EDIT DIALOG MODAL -->
<div class="modal-overlay" id="testimonialModal">
    <div class="modal-container">
        <div class="modal-header">
            <h3 class="modal-title" id="modalTitle">Add Student Testimonial</h3>
            <span class="modal-close" onclick="closeTestimonialModal()">&times;</span>
        </div>
        <form action="testimonials.php" method="POST">
            <div class="modal-body">
                <input type="hidden" name="action" id="modalAction" value="add">
                <input type="hidden" name="testimonial_id" id="edit_testimonial_id">
                
                <div class="form-group">
                    <label for="t_name" class="form-label">Student Name *</label>
                    <input type="text" name="name" id="t_name" class="form-control" placeholder="e.g., Sai Raksha Manoharan" oninput="autoGenerateInitials(this.value)" required>
                </div>
                
                <div class="detail-grid">
                    <div class="form-group">
                        <label for="t_detail" class="form-label">Program Detail (Achievement) *</label>
                        <input type="text" name="detail" id="t_detail" class="form-control" placeholder="e.g., MSc in UK" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="t_initials" class="form-label">Avatar Initials</label>
                        <input type="text" name="initials" id="t_initials" class="form-control" maxlength="2" placeholder="e.g., SR">
                    </div>
                </div>

                <div class="detail-grid">
                    <div class="form-group">
                        <label for="t_stars" class="form-label">Rating Stars (1 - 5) *</label>
                        <select name="stars" id="t_stars" class="form-select" required>
                            <option value="5">5 Stars</option>
                            <option value="4">4 Stars</option>
                            <option value="3">3 Stars</option>
                            <option value="2">2 Stars</option>
                            <option value="1">1 Star</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="t_color" class="form-label">Avatar Badge Color *</label>
                        <select name="color" id="t_color" class="form-select" required>
                            <option value="blue">Blue Badge</option>
                            <option value="purple">Purple Badge</option>
                            <option value="orange">Orange Badge</option>
                            <option value="teal">Teal Badge</option>
                            <option value="pink">Pink Badge</option>
                            <option value="gold">Gold Badge</option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label for="t_text" class="form-label">Review Text (Testimonial Quote) *</label>
                    <textarea name="text" id="t_text" class="form-control" rows="4" placeholder="Paste the feedback from the student..." required></textarea>
                </div>

                <div class="form-group" style="margin-bottom: 0; display: flex; align-items: center; gap: 0.5rem;">
                    <input type="checkbox" name="is_active" id="t_active" checked style="width: 18px; height: 18px; accent-color: var(--primary); cursor: pointer;">
                    <label for="t_active" class="form-label" style="margin-bottom: 0; cursor: pointer; user-select: none;">Publish immediately (Active & Visible on homepage)</label>
                </div>
            </div>
            
            <div class="modal-footer">
                <button type="button" class="btn-outline" onclick="closeTestimonialModal()">Cancel</button>
                <button type="submit" class="btn-pill">
                    <i class="fa-solid fa-floppy-disk"></i>
                    <span>Save Testimonial</span>
                </button>
            </div>
        </form>
    </div>
</div>

<!-- 2. DELETE CONFIRMATION MODAL -->
<div class="modal-overlay" id="deleteModal">
    <div class="modal-container" style="max-width: 400px;">
        <div class="modal-header" style="border-bottom: none; padding-bottom: 0;">
            <h3 class="modal-title" style="color: var(--danger);"><i class="fa-solid fa-triangle-exclamation"></i> Delete Testimonial?</h3>
            <span class="modal-close" onclick="closeDeleteModal()">&times;</span>
        </div>
        <form action="testimonials.php" method="POST">
            <div class="modal-body" style="padding-top: 1rem;">
                <input type="hidden" name="action" value="delete">
                <input type="hidden" name="testimonial_id" id="deleteTestimonialId">
                <p style="font-size: 0.95rem; line-height: 1.6; color: var(--text-secondary);">
                    Are you sure you want to permanently delete the testimonial of student <strong id="deleteStudentName" style="color: var(--text-primary);">Student Name</strong>?
                </p>
                <p style="font-size: 0.8rem; color: var(--text-muted); margin-top: 0.75rem;">
                    This testimonial will instantly vanish from the live page reviews. It cannot be recovered.
                </p>
            </div>
            <div class="modal-footer" style="border-top: none; padding-top: 0;">
                <button type="button" class="btn-outline" onclick="closeDeleteModal()">Cancel</button>
                <button type="submit" class="btn-pill" style="background: var(--danger); box-shadow: 0 5px 10px var(--danger-glow);">
                    <i class="fa-solid fa-trash-can"></i>
                    <span>Confirm Delete</span>
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function autoGenerateInitials(text) {
    if (document.getElementById('modalAction').value === 'add') {
        const parts = text.trim().split(' ');
        let initials = '';
        if (parts[0]) initials += parts[0].substring(0, 1);
        if (parts[1]) initials += parts[1].substring(0, 1);
        document.getElementById('t_initials').value = initials.toUpperCase();
    }
}

function openAddModal() {
    document.getElementById('modalAction').value = 'add';
    document.getElementById('modalTitle').innerText = 'Add Student Testimonial';
    document.getElementById('edit_testimonial_id').value = '';
    document.getElementById('t_name').value = '';
    document.getElementById('t_detail').value = '';
    document.getElementById('t_initials').value = '';
    document.getElementById('t_stars').value = '5';
    document.getElementById('t_color').value = 'blue';
    document.getElementById('t_text').value = '';
    document.getElementById('t_active').checked = true;
    
    document.getElementById('testimonialModal').classList.add('active');
}

function openEditModal(t) {
    document.getElementById('modalAction').value = 'update';
    document.getElementById('modalTitle').innerText = 'Edit Testimonial: ' + t.name;
    document.getElementById('edit_testimonial_id').value = t.id;
    document.getElementById('t_name').value = t.name;
    document.getElementById('t_detail').value = t.detail;
    document.getElementById('t_initials').value = t.initials;
    document.getElementById('t_stars').value = t.stars;
    document.getElementById('t_color').value = t.color;
    document.getElementById('t_text').value = t.text;
    document.getElementById('t_active').checked = parseInt(t.is_active) === 1;
    
    document.getElementById('testimonialModal').classList.add('active');
}

function closeTestimonialModal() {
    document.getElementById('testimonialModal').classList.remove('active');
}

function triggerDeleteTestimonial(id, name) {
    document.getElementById('deleteTestimonialId').value = id;
    document.getElementById('deleteStudentName').innerText = name;
    document.getElementById('deleteModal').classList.add('active');
}

function closeDeleteModal() {
    document.getElementById('deleteModal').classList.remove('active');
}
</script>

<?php require_once 'includes/footer.php'; ?>
