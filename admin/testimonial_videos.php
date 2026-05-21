<?php
// admin/testimonial_videos.php - Video Reviews CRUD Editor with Local Upload and URL Options
$pageTitle = 'Video Testimonials Manager';
require_once 'includes/header.php';

$alertSuccess = '';
$alertError = '';

// --- HANDLE POST REQUESTS (ADD, UPDATE, DELETE) ---
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = isset($_POST['action']) ? trim($_POST['action']) : '';
    
    // 1. ADD NEW VIDEO
    if ($action === 'add') {
        $student_name = isset($_POST['student_name']) ? trim($_POST['student_name']) : '';
        $details = isset($_POST['details']) ? trim($_POST['details']) : '';
        $youtube_url = isset($_POST['youtube_url']) ? trim($_POST['youtube_url']) : '';
        $is_active = isset($_POST['is_active']) ? 1 : 0;
        
        // Handle local video upload
        $uploaded_path = '';
        if (isset($_FILES['video_file']) && $_FILES['video_file']['error'] === UPLOAD_ERR_OK) {
            $fileTmpPath = $_FILES['video_file']['tmp_name'];
            $fileName = $_FILES['video_file']['name'];
            $fileNameCmps = explode(".", $fileName);
            $fileExtension = strtolower(end($fileNameCmps));
            
            // Sanitize filename
            $newFileName = md5(time() . $fileName) . '.' . $fileExtension;
            $uploadFileDir = '../uploads/videos/';
            if (!is_dir($uploadFileDir)) {
                mkdir($uploadFileDir, 0755, true);
            }
            
            $dest_path = $uploadFileDir . $newFileName;
            if (move_uploaded_file($fileTmpPath, $dest_path)) {
                $uploaded_path = 'uploads/videos/' . $newFileName;
            }
        }

        // Use uploaded path if upload was successful, otherwise use provided URL
        $final_video_source = !empty($uploaded_path) ? $uploaded_path : $youtube_url;

        if (empty($student_name) || empty($details) || empty($final_video_source)) {
            $alertError = 'Student Name, Course/Details, and either a Video URL or a local Video File are required.';
        } else {
            try {
                $stmt = $pdo->prepare("
                    INSERT INTO testimonial_videos (student_name, details, youtube_url, is_active) 
                    VALUES (:student_name, :details, :youtube_url, :is_active)
                ");
                $stmt->execute([
                    'student_name' => $student_name,
                    'details' => $details,
                    'youtube_url' => $final_video_source,
                    'is_active' => $is_active
                ]);
                $alertSuccess = 'Video testimonial added successfully!';
            } catch (PDOException $e) {
                $alertError = 'Failed to add video testimonial: ' . $e->getMessage();
            }
        }
    }
    
    // 2. UPDATE VIDEO
    elseif ($action === 'update') {
        $id = isset($_POST['video_id']) ? intval($_POST['video_id']) : 0;
        $student_name = isset($_POST['student_name']) ? trim($_POST['student_name']) : '';
        $details = isset($_POST['details']) ? trim($_POST['details']) : '';
        $youtube_url = isset($_POST['youtube_url']) ? trim($_POST['youtube_url']) : '';
        $is_active = isset($_POST['is_active']) ? 1 : 0;
        
        // Handle local video upload
        $uploaded_path = '';
        if (isset($_FILES['video_file']) && $_FILES['video_file']['error'] === UPLOAD_ERR_OK) {
            $fileTmpPath = $_FILES['video_file']['tmp_name'];
            $fileName = $_FILES['video_file']['name'];
            $fileNameCmps = explode(".", $fileName);
            $fileExtension = strtolower(end($fileNameCmps));
            
            // Sanitize filename
            $newFileName = md5(time() . $fileName) . '.' . $fileExtension;
            $uploadFileDir = '../uploads/videos/';
            if (!is_dir($uploadFileDir)) {
                mkdir($uploadFileDir, 0755, true);
            }
            
            $dest_path = $uploadFileDir . $newFileName;
            if (move_uploaded_file($fileTmpPath, $dest_path)) {
                $uploaded_path = 'uploads/videos/' . $newFileName;
            }
        }

        // Use uploaded path if upload was successful, otherwise keep the provided URL input
        $final_video_source = !empty($uploaded_path) ? $uploaded_path : $youtube_url;

        if ($id <= 0 || empty($student_name) || empty($details) || empty($final_video_source)) {
            $alertError = 'Invalid parameters. Student Name, Details, and Video Source are required.';
        } else {
            try {
                $stmt = $pdo->prepare("
                    UPDATE testimonial_videos 
                    SET student_name = :student_name, 
                        details = :details, 
                        youtube_url = :youtube_url, 
                        is_active = :is_active 
                    WHERE id = :id
                ");
                $stmt->execute([
                    'student_name' => $student_name,
                    'details' => $details,
                    'youtube_url' => $final_video_source,
                    'is_active' => $is_active,
                    'id' => $id
                ]);
                $alertSuccess = 'Video testimonial updated successfully!';
            } catch (PDOException $e) {
                $alertError = 'Failed to update video: ' . $e->getMessage();
            }
        }
    }
    
    // 3. DELETE VIDEO
    elseif ($action === 'delete') {
        $id = isset($_POST['video_id']) ? intval($_POST['video_id']) : 0;
        if ($id <= 0) {
            $alertError = 'Invalid video ID specified for deletion.';
        } else {
            try {
                // Delete actual local video file if it exists
                $stmtFile = $pdo->prepare("SELECT youtube_url FROM testimonial_videos WHERE id = :id LIMIT 1");
                $stmtFile->execute(['id' => $id]);
                $existingVideo = $stmtFile->fetch();
                if ($existingVideo && strpos($existingVideo['youtube_url'], 'uploads/') === 0) {
                    $local_file = '../' . $existingVideo['youtube_url'];
                    if (file_exists($local_file)) {
                        unlink($local_file);
                    }
                }

                $stmt = $pdo->prepare("DELETE FROM testimonial_videos WHERE id = :id");
                $stmt->execute(['id' => $id]);
                $alertSuccess = 'Video testimonial deleted permanently!';
            } catch (PDOException $e) {
                $alertError = 'Failed to delete video: ' . $e->getMessage();
            }
        }
    }
}

// Fetch all video testimonials
$videos = [];
try {
    $stmt = $pdo->query("SELECT * FROM testimonial_videos ORDER BY id DESC");
    $videos = $stmt->fetchAll();
} catch (PDOException $e) {
    $alertError = 'Could not fetch video testimonials: ' . $e->getMessage();
}
?>

<div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 2.5rem; flex-wrap: wrap; gap: 1rem;">
    <h1 class="page-title">
        Video Testimonials Manager
        <span>Manage successfully admitted student video clips, reviews, and YouTube embed files for the front-end video testimonials board</span>
    </h1>
    <button class="btn-pill" onclick="openAddModal()">
        <i class="fa-solid fa-plus"></i>
        <span>Add Video Review</span>
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

<?php if (empty($videos)): ?>
    <div class="panel-card" style="text-align: center; padding: 5rem 2rem; color: var(--text-secondary);">
        <i class="fa-solid fa-video" style="font-size: 3rem; margin-bottom: 1.5rem; color: var(--text-muted);"></i>
        <h3>No student video reviews exist in the database.</h3>
        <p style="margin-top: 0.5rem; font-size: 0.9rem;">Click "Add Video Review" to embed or upload your first success story clip!</p>
    </div>
<?php else: ?>
    <div class="crud-grid">
        <?php foreach ($videos as $v): 
            $isActive = intval($v['is_active']) === 1;
            $name = clean_output($v['student_name']);
            $details = clean_output($v['details']);
            $yt_url = clean_output($v['youtube_url']);
            $is_local = (strpos($yt_url, 'uploads/') === 0);
        ?>
            <div class="crud-card <?php echo $isActive ? 'crud-card-active' : 'crud-card-inactive'; ?>">
                <div class="crud-card-badge-icon">
                    <i class="fa-solid <?php echo $isActive ? 'fa-check' : 'fa-eye-slash'; ?>"></i>
                </div>
                
                <div style="height: 140px; background: #0f172a; border-radius: 12px; margin-bottom: 1rem; overflow: hidden; position: relative; border: 1px solid var(--border);">
                    <?php if ($is_local): ?>
                        <video src="../<?php echo $yt_url; ?>" controls style="width: 100%; height: 100%; object-fit: cover;"></video>
                    <?php else: ?>
                        <iframe src="<?php echo $yt_url; ?>" style="width: 100%; height: 100%; border: none;" allowfullscreen></iframe>
                    <?php endif; ?>
                </div>

                <div class="crud-card-header" style="display: block;">
                    <h4 class="crud-card-title"><?php echo $name; ?></h4>
                    <span style="font-size: 0.8rem; color: var(--text-secondary); display: block; margin-top: 0.2rem;"><i class="fa-solid fa-user-graduate" style="color: var(--primary); margin-right: 0.4rem;"></i> <?php echo $details; ?></span>
                </div>

                <div class="crud-card-footer" style="margin-top: 1rem;">
                    <span class="crud-card-info" style="font-size: 0.7rem; overflow: hidden; text-overflow: ellipsis; max-width: 150px; white-space: nowrap;">Source: <strong><?php echo $yt_url; ?></strong></span>
                    <div class="crud-card-actions">
                        <button class="btn-action action-edit" title="Edit Video" onclick="openEditModal(<?php echo htmlspecialchars(json_encode($v)); ?>)">
                            <i class="fa-solid fa-pen-to-square"></i>
                        </button>
                        <button class="btn-action action-delete" title="Delete Video" onclick="triggerDeleteVideo(<?php echo $v['id']; ?>, '<?php echo $name; ?>')">
                            <i class="fa-solid fa-trash-can"></i>
                        </button>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<!-- 1. ADD / EDIT DIALOG MODAL -->
<div class="modal-overlay" id="videoModal">
    <div class="modal-container" style="max-width: 500px;">
        <div class="modal-header">
            <h3 class="modal-title" id="modalTitle">Embed / Upload Video Testimonial</h3>
            <span class="modal-close" onclick="closeVideoModal()">&times;</span>
        </div>
        <form action="testimonial_videos.php" method="POST" enctype="multipart/form-data">
            <div class="modal-body">
                <input type="hidden" name="action" id="modalAction" value="add">
                <input type="hidden" name="video_id" id="edit_video_id">
                
                <div class="form-group">
                    <label for="v_name" class="form-label">Student Full Name *</label>
                    <input type="text" name="student_name" id="v_name" class="form-control" placeholder="e.g., Sai Raksha Manoharan" required>
                </div>
                
                <div class="form-group">
                    <label for="v_details" class="form-label">Course / University Destination Details *</label>
                    <input type="text" name="details" id="v_details" class="form-control" placeholder="e.g., MSc in United Kingdom" required>
                </div>

                <div class="form-group">
                    <label for="v_url" class="form-label">YouTube Embedded / Remote URL</label>
                    <input type="text" name="youtube_url" id="v_url" class="form-control" placeholder="e.g., https://www.youtube.com/embed/dQw4w9WgXcQ">
                    <small style="color: var(--text-muted); font-size: 0.75rem; display: block; margin-top: 0.25rem;">Must be the embeddable format URL containing <code>/embed/</code></small>
                </div>

                <div class="form-group">
                    <label for="v_file" class="form-label">Or Upload Local Video File</label>
                    <input type="file" name="video_file" id="v_file" class="form-control" accept="video/mp4,video/webm">
                    <small style="color: var(--text-muted); font-size: 0.75rem; display: block; margin-top: 0.25rem;">Supported formats: MP4, WebM (Recommended size &lt; 20MB)</small>
                </div>

                <div class="form-group" style="margin-bottom: 0; display: flex; align-items: center; gap: 0.5rem;">
                    <input type="checkbox" name="is_active" id="v_active" checked style="width: 18px; height: 18px; accent-color: var(--primary); cursor: pointer;">
                    <label for="v_active" class="form-label" style="margin-bottom: 0; cursor: pointer; user-select: none;">Publish immediately (Active & Visible on testimonials page)</label>
                </div>
            </div>
            
            <div class="modal-footer">
                <button type="button" class="btn-outline" onclick="closeVideoModal()">Cancel</button>
                <button type="submit" class="btn-pill">
                    <i class="fa-solid fa-floppy-disk"></i>
                    <span>Save Video Review</span>
                </button>
            </div>
        </form>
    </div>
</div>

<!-- 2. DELETE CONFIRMATION MODAL -->
<div class="modal-overlay" id="deleteModal">
    <div class="modal-container" style="max-width: 400px;">
        <div class="modal-header" style="border-bottom: none; padding-bottom: 0;">
            <h3 class="modal-title" style="color: var(--danger);"><i class="fa-solid fa-triangle-exclamation"></i> Delete Video Review?</h3>
            <span class="modal-close" onclick="closeDeleteModal()">&times;</span>
        </div>
        <form action="testimonial_videos.php" method="POST">
            <div class="modal-body" style="padding-top: 1rem;">
                <input type="hidden" name="action" value="delete">
                <input type="hidden" name="video_id" id="deleteVideoId">
                <p style="font-size: 0.95rem; line-height: 1.6; color: var(--text-secondary);">
                    Are you sure you want to permanently delete video review for <strong id="deleteVideoName" style="color: var(--text-primary);">Student</strong>?
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
    document.getElementById('modalTitle').innerText = 'Embed / Upload Video Testimonial';
    document.getElementById('edit_video_id').value = '';
    document.getElementById('v_name').value = '';
    document.getElementById('v_details').value = '';
    document.getElementById('v_url').value = 'https://www.youtube.com/embed/';
    document.getElementById('v_file').value = '';
    document.getElementById('v_active').checked = true;
    
    document.getElementById('videoModal').classList.add('active');
}

function openEditModal(v) {
    document.getElementById('modalAction').value = 'update';
    document.getElementById('modalTitle').innerText = 'Edit Video Details';
    document.getElementById('edit_video_id').value = v.id;
    document.getElementById('v_name').value = v.student_name;
    document.getElementById('v_details').value = v.details;
    document.getElementById('v_url').value = v.youtube_url;
    document.getElementById('v_file').value = '';
    document.getElementById('v_active').checked = parseInt(v.is_active) === 1;
    
    document.getElementById('videoModal').classList.add('active');
}

function closeVideoModal() {
    document.getElementById('videoModal').classList.remove('active');
}

function triggerDeleteVideo(id, name) {
    document.getElementById('deleteVideoId').value = id;
    document.getElementById('deleteVideoName').innerText = name;
    document.getElementById('deleteModal').classList.add('active');
}

function closeDeleteModal() {
    document.getElementById('deleteModal').classList.remove('active');
}
</script>

<?php require_once 'includes/footer.php'; ?>
