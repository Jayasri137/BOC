<?php
// admin/events.php - Events CRUD Editor with Local Image Upload Support
$pageTitle = 'Events & Seminars Manager';
require_once 'includes/header.php';

$alertSuccess = '';
$alertError = '';

// --- HANDLE POST REQUESTS (ADD, UPDATE, DELETE) ---
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = isset($_POST['action']) ? trim($_POST['action']) : '';
    
    // 1. ADD NEW EVENT
    if ($action === 'add') {
        $title = isset($_POST['title']) ? trim($_POST['title']) : '';
        $date_string = isset($_POST['date_string']) ? trim($_POST['date_string']) : '';
        $location = isset($_POST['location']) ? trim($_POST['location']) : '';
        $description = isset($_POST['description']) ? trim($_POST['description']) : '';
        $image_path = isset($_POST['image_path']) ? trim($_POST['image_path']) : '';
        $is_active = isset($_POST['is_active']) ? 1 : 0;
        
        // Handle local image upload
        $uploaded_path = '';
        if (isset($_FILES['event_file']) && $_FILES['event_file']['error'] === UPLOAD_ERR_OK) {
            $fileTmpPath = $_FILES['event_file']['tmp_name'];
            $fileName = $_FILES['event_file']['name'];
            $fileNameCmps = explode(".", $fileName);
            $fileExtension = strtolower(end($fileNameCmps));
            
            $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg'];
            if (in_array($fileExtension, $allowedExtensions)) {
                $newFileName = md5(time() . $fileName) . '.' . $fileExtension;
                $uploadFileDir = '../assets/images/uploads/';
                if (!is_dir($uploadFileDir)) {
                    mkdir($uploadFileDir, 0755, true);
                }
                
                $dest_path = $uploadFileDir . $newFileName;
                if (move_uploaded_file($fileTmpPath, $dest_path)) {
                    $uploaded_path = 'assets/images/uploads/' . $newFileName;
                }
            }
        }
        
        $final_image_source = !empty($uploaded_path) ? $uploaded_path : $image_path;
        
        if (empty($title) || empty($date_string) || empty($location) || empty($description)) {
            $alertError = 'Event Title, Date, Location, and Description are required fields.';
        } else {
            try {
                $stmt = $pdo->prepare("
                    INSERT INTO events (title, date_string, location, description, image_path, is_active) 
                    VALUES (:title, :date_string, :location, :description, :image_path, :is_active)
                ");
                $stmt->execute([
                    'title' => $title,
                    'date_string' => $date_string,
                    'location' => $location,
                    'description' => $description,
                    'image_path' => $final_image_source,
                    'is_active' => $is_active
                ]);
                $alertSuccess = 'Event card added successfully!';
            } catch (PDOException $e) {
                $alertError = 'Failed to add event: ' . $e->getMessage();
            }
        }
    }
    
    // 2. UPDATE EVENT
    elseif ($action === 'update') {
        $id = isset($_POST['event_id']) ? intval($_POST['event_id']) : 0;
        $title = isset($_POST['title']) ? trim($_POST['title']) : '';
        $date_string = isset($_POST['date_string']) ? trim($_POST['date_string']) : '';
        $location = isset($_POST['location']) ? trim($_POST['location']) : '';
        $description = isset($_POST['description']) ? trim($_POST['description']) : '';
        $image_path = isset($_POST['image_path']) ? trim($_POST['image_path']) : '';
        $is_active = isset($_POST['is_active']) ? 1 : 0;
        
        // Handle local image upload
        $uploaded_path = '';
        if (isset($_FILES['event_file']) && $_FILES['event_file']['error'] === UPLOAD_ERR_OK) {
            $fileTmpPath = $_FILES['event_file']['tmp_name'];
            $fileName = $_FILES['event_file']['name'];
            $fileNameCmps = explode(".", $fileName);
            $fileExtension = strtolower(end($fileNameCmps));
            
            $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg'];
            if (in_array($fileExtension, $allowedExtensions)) {
                $newFileName = md5(time() . $fileName) . '.' . $fileExtension;
                $uploadFileDir = '../assets/images/uploads/';
                if (!is_dir($uploadFileDir)) {
                    mkdir($uploadFileDir, 0755, true);
                }
                
                $dest_path = $uploadFileDir . $newFileName;
                if (move_uploaded_file($fileTmpPath, $dest_path)) {
                    $uploaded_path = 'assets/images/uploads/' . $newFileName;
                }
            }
        }
        
        $final_image_source = !empty($uploaded_path) ? $uploaded_path : $image_path;
        
        if ($id <= 0 || empty($title) || empty($date_string) || empty($location) || empty($description)) {
            $alertError = 'Invalid parameters. Title, Date, Location, and Description are required.';
        } else {
            try {
                $stmt = $pdo->prepare("
                    UPDATE events 
                    SET title = :title, 
                        date_string = :date_string, 
                        location = :location, 
                        description = :description, 
                        image_path = :image_path,
                        is_active = :is_active 
                    WHERE id = :id
                ");
                $stmt->execute([
                    'title' => $title,
                    'date_string' => $date_string,
                    'location' => $location,
                    'description' => $description,
                    'image_path' => $final_image_source,
                    'is_active' => $is_active,
                    'id' => $id
                ]);
                $alertSuccess = 'Event updated successfully!';
            } catch (PDOException $e) {
                $alertError = 'Failed to update event: ' . $e->getMessage();
            }
        }
    }
    
    // 3. DELETE EVENT
    elseif ($action === 'delete') {
        $id = isset($_POST['event_id']) ? intval($_POST['event_id']) : 0;
        if ($id <= 0) {
            $alertError = 'Invalid event ID specified for deletion.';
        } else {
            try {
                // Delete actual local file if it exists inside uploads
                $stmtFile = $pdo->prepare("SELECT image_path FROM events WHERE id = :id LIMIT 1");
                $stmtFile->execute(['id' => $id]);
                $evFile = $stmtFile->fetch();
                if ($evFile && strpos($evFile['image_path'], 'assets/images/uploads/') === 0) {
                    $local_file = '../' . $evFile['image_path'];
                    if (file_exists($local_file)) {
                        unlink($local_file);
                    }
                }

                $stmt = $pdo->prepare("DELETE FROM events WHERE id = :id");
                $stmt->execute(['id' => $id]);
                $alertSuccess = 'Event deleted permanently!';
            } catch (PDOException $e) {
                $alertError = 'Failed to delete event: ' . $e->getMessage();
            }
        }
    }
}

// Fetch all events
$events = [];
try {
    $stmt = $pdo->query("SELECT * FROM events ORDER BY id DESC");
    $events = $stmt->fetchAll();
} catch (PDOException $e) {
    $alertError = 'Could not fetch events: ' . $e->getMessage();
}
?>

<div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 2.5rem; flex-wrap: wrap; gap: 1rem;">
    <h1 class="page-title">
        Events & Seminars Manager
        <span>Manage upcoming offline education fairs, interactive webinars, and IELTS masterclasses</span>
    </h1>
    <button class="btn-pill" onclick="openAddModal()">
        <i class="fa-solid fa-plus"></i>
        <span>Add New Event</span>
    </button>
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

<?php if (empty($events)): ?>
    <div class="panel-card" style="text-align: center; padding: 5rem 2rem; color: var(--text-secondary);">
        <i class="fa-solid fa-calendar-days" style="font-size: 3rem; margin-bottom: 1.5rem; color: var(--text-muted);"></i>
        <h3>No events exist in the database.</h3>
        <p style="margin-top: 0.5rem; font-size: 0.9rem;">Click "Add New Event" to publish your first study fair or seminar!</p>
    </div>
<?php else: ?>
    <div class="crud-grid">
        <?php foreach ($events as $ev): 
            $isActive = intval($ev['is_active']) === 1;
            $title = clean_output($ev['title']);
            $date = clean_output($ev['date_string']);
            $loc = clean_output($ev['location']);
            $image_path = clean_output($ev['image_path'] ?? '');
        ?>
            <div class="crud-card <?php echo $isActive ? 'crud-card-active' : 'crud-card-inactive'; ?>">
                <div class="crud-card-badge-icon">
                    <i class="fa-solid <?php echo $isActive ? 'fa-check' : 'fa-eye-slash'; ?>"></i>
                </div>
                
                <div style="height: 140px; background: #f1f5f9; border-radius: 12px; margin-bottom: 1rem; overflow: hidden; display: flex; align-items: center; justify-content: center; border: 1px solid var(--border);">
                    <?php if (!empty($image_path)): ?>
                        <img src="../<?php echo $image_path; ?>" alt="Event Image" style="width: 100%; height: 100%; object-fit: cover; background: #f8fafc;">
                    <?php else: ?>
                        <i class="fa-regular fa-calendar-check" style="font-size: 3rem; color: var(--text-muted);"></i>
                    <?php endif; ?>
                </div>

                <div class="crud-card-header" style="display: block;">
                    <div style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.5rem;">
                        <span class="badge-accent" style="font-size: 0.75rem; background: var(--primary-glow); color: var(--primary); padding: 0.2rem 0.6rem; border-radius: 20px; font-weight: 600;"><i class="fa-regular fa-calendar" style="margin-right: 0.25rem;"></i> <?php echo $date; ?></span>
                    </div>
                    <h4 class="crud-card-title"><?php echo $title; ?></h4>
                </div>
                
                <p class="crud-card-desc"><?php echo clean_output($ev['description']); ?></p>
                
                <div style="font-size: 0.8rem; color: var(--text-secondary); margin-bottom: 1rem;"><i class="fa-solid fa-location-dot" style="color: var(--primary); margin-right: 0.4rem;"></i> <?php echo $loc; ?></div>

                <div class="crud-card-footer">
                    <span class="crud-card-info" style="max-width: 150px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">Img: <strong><?php echo $image_path ? $image_path : 'None'; ?></strong></span>
                    <div class="crud-card-actions">
                        <button class="btn-action action-edit" title="Edit Event" onclick="openEditModal(<?php echo htmlspecialchars(json_encode($ev)); ?>)">
                            <i class="fa-solid fa-pen-to-square"></i>
                        </button>
                        <button class="btn-action action-delete" title="Delete Event" onclick="triggerDeleteEvent(<?php echo $ev['id']; ?>, '<?php echo $title; ?>')">
                            <i class="fa-solid fa-trash-can"></i>
                        </button>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<!-- 1. ADD / EDIT DIALOG MODAL -->
<div class="modal-overlay" id="eventModal">
    <div class="modal-container" style="max-width: 500px;">
        <div class="modal-header">
            <h3 class="modal-title" id="modalTitle">Add Upcoming Event</h3>
            <span class="modal-close" onclick="closeEventModal()">&times;</span>
        </div>
        <form action="events.php" method="POST" enctype="multipart/form-data">
            <div class="modal-body">
                <input type="hidden" name="action" id="modalAction" value="add">
                <input type="hidden" name="event_id" id="edit_event_id">
                
                <div class="form-group">
                    <label for="ev_title" class="form-label">Event Name Title *</label>
                    <input type="text" name="title" id="ev_title" class="form-control" placeholder="e.g., UK Education Fair 2025" required>
                </div>
                
                <div class="detail-grid">
                    <div class="form-group">
                        <label for="ev_date" class="form-label">Event Date String *</label>
                        <input type="text" name="date_string" id="ev_date" class="form-control" placeholder="e.g., May 15, 2025" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="ev_loc" class="form-label">Location / Venue *</label>
                        <input type="text" name="location" id="ev_loc" class="form-control" placeholder="e.g., Coimbatore Office" required>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="ev_desc" class="form-label">Detailed Event Description *</label>
                    <textarea name="description" id="ev_desc" class="form-control" rows="3" placeholder="Explain agenda, university listings, or registration guidelines..." required></textarea>
                </div>

                <div class="detail-grid">
                    <div class="form-group">
                        <label for="ev_img" class="form-label">Event Cover Image Path</label>
                        <input type="text" name="image_path" id="ev_img" class="form-control" placeholder="e.g., assets/images/event1.jpg">
                    </div>
                    
                    <div class="form-group">
                        <label for="ev_file" class="form-label">Or Upload Local Image</label>
                        <input type="file" name="event_file" id="ev_file" class="form-control" accept="image/*">
                    </div>
                </div>

                <div class="form-group" style="margin-bottom: 0; display: flex; align-items: center; gap: 0.5rem;">
                    <input type="checkbox" name="is_active" id="ev_active" checked style="width: 18px; height: 18px; accent-color: var(--primary); cursor: pointer;">
                    <label for="ev_active" class="form-label" style="margin-bottom: 0; cursor: pointer; user-select: none;">Publish immediately (Active & Visible on events board)</label>
                </div>
            </div>
            
            <div class="modal-footer">
                <button type="button" class="btn-outline" onclick="closeEventModal()">Cancel</button>
                <button type="submit" class="btn-pill">
                    <i class="fa-solid fa-floppy-disk"></i>
                    <span>Save Event</span>
                </button>
            </div>
        </form>
    </div>
</div>

<!-- 2. DELETE CONFIRMATION MODAL -->
<div class="modal-overlay" id="deleteModal">
    <div class="modal-container" style="max-width: 400px;">
        <div class="modal-header" style="border-bottom: none; padding-bottom: 0;">
            <h3 class="modal-title" style="color: var(--danger);"><i class="fa-solid fa-triangle-exclamation"></i> Delete Event?</h3>
            <span class="modal-close" onclick="closeDeleteModal()">&times;</span>
        </div>
        <form action="events.php" method="POST">
            <div class="modal-body" style="padding-top: 1rem;">
                <input type="hidden" name="action" value="delete">
                <input type="hidden" name="event_id" id="deleteEventId">
                <p style="font-size: 0.95rem; line-height: 1.6; color: var(--text-secondary);">
                    Are you sure you want to permanently delete event <strong id="deleteEventName" style="color: var(--text-primary);">Event</strong>?
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
function openAddModal() {
    document.getElementById('modalAction').value = 'add';
    document.getElementById('modalTitle').innerText = 'Add Upcoming Event';
    document.getElementById('edit_event_id').value = '';
    document.getElementById('ev_title').value = '';
    document.getElementById('ev_date').value = '';
    document.getElementById('ev_loc').value = '';
    document.getElementById('ev_desc').value = '';
    document.getElementById('ev_img').value = '';
    document.getElementById('ev_file').value = '';
    document.getElementById('ev_active').checked = true;
    
    document.getElementById('eventModal').classList.add('active');
}

function openEditModal(ev) {
    document.getElementById('modalAction').value = 'update';
    document.getElementById('modalTitle').innerText = 'Edit Event Details';
    document.getElementById('edit_event_id').value = ev.id;
    document.getElementById('ev_title').value = ev.title;
    document.getElementById('ev_date').value = ev.date_string;
    document.getElementById('ev_loc').value = ev.location;
    document.getElementById('ev_desc').value = ev.description;
    document.getElementById('ev_img').value = ev.image_path || '';
    document.getElementById('ev_file').value = '';
    document.getElementById('ev_active').checked = parseInt(ev.is_active) === 1;
    
    document.getElementById('eventModal').classList.add('active');
}

function closeEventModal() {
    document.getElementById('eventModal').classList.remove('active');
}

// Correct callback triggers
function triggerDeleteEvent(id, name) {
    document.getElementById('deleteEventId').value = id;
    document.getElementById('deleteEventName').innerText = name;
    document.getElementById('deleteModal').classList.add('active');
}

function closeDeleteModal() {
    document.getElementById('deleteModal').classList.remove('active');
}
</script>

<?php require_once 'includes/footer.php'; ?>
