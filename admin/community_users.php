<?php
// admin/community_users.php - Community Profiles Manager
$pageTitle = 'Community Profiles Manager';
require_once __DIR__ . '/includes/header.php';

$alertSuccess = '';
$alertError = '';

// --- HANDLE POST REQUESTS (ADD, UPDATE, DELETE) ---
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = isset($_POST['action']) ? trim($_POST['action']) : '';
    
    if ($action === 'add') {
        $name = isset($_POST['name']) ? trim($_POST['name']) : '';
        $subtitle = isset($_POST['subtitle']) ? trim($_POST['subtitle']) : '';
        $destination_tag = isset($_POST['destination_tag']) ? trim($_POST['destination_tag']) : '';
        $avatar_url = isset($_POST['avatar_url']) ? trim($_POST['avatar_url']) : '';
        $is_active = isset($_POST['is_active']) ? 1 : 0;
        
        // Handle local image upload
        $uploaded_path = '';
        if (isset($_FILES['user_file']) && $_FILES['user_file']['error'] === UPLOAD_ERR_OK) {
            $fileTmpPath = $_FILES['user_file']['tmp_name'];
            $fileName = $_FILES['user_file']['name'];
            $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
            
            $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
            if (in_array($fileExtension, $allowedExtensions)) {
                $newFileName = md5(time() . $fileName) . '.' . $fileExtension;
                $uploadFileDir = '../assets/images/community/';
                if (!is_dir($uploadFileDir)) {
                    mkdir($uploadFileDir, 0755, true);
                }
                
                $dest_path = $uploadFileDir . $newFileName;
                if (move_uploaded_file($fileTmpPath, $dest_path)) {
                    $uploaded_path = 'assets/images/community/' . $newFileName;
                }
            }
        }
        
        $final_media = !empty($uploaded_path) ? $uploaded_path : $avatar_url;
        
        if (empty($name)) {
            $alertError = 'Name is required.';
        } else {
            try {
                $stmt = $pdo->prepare("INSERT INTO community_users (name, avatar_url, subtitle, destination_tag, is_active) VALUES (:name, :avatar, :subtitle, :tag, :is_active)");
                $stmt->execute([
                    'name' => $name,
                    'avatar' => $final_media,
                    'subtitle' => $subtitle,
                    'tag' => $destination_tag,
                    'is_active' => $is_active
                ]);
                $alertSuccess = 'Profile added successfully!';
            } catch (PDOException $e) {
                $alertError = 'Failed to add profile: ' . $e->getMessage();
            }
        }
    }
    
    elseif ($action === 'update') {
        $id = isset($_POST['user_id']) ? intval($_POST['user_id']) : 0;
        $name = isset($_POST['name']) ? trim($_POST['name']) : '';
        $subtitle = isset($_POST['subtitle']) ? trim($_POST['subtitle']) : '';
        $destination_tag = isset($_POST['destination_tag']) ? trim($_POST['destination_tag']) : '';
        $avatar_url = isset($_POST['avatar_url']) ? trim($_POST['avatar_url']) : '';
        $is_active = isset($_POST['is_active']) ? 1 : 0;
        
        $uploaded_path = '';
        if (isset($_FILES['user_file']) && $_FILES['user_file']['error'] === UPLOAD_ERR_OK) {
            $fileTmpPath = $_FILES['user_file']['tmp_name'];
            $fileName = $_FILES['user_file']['name'];
            $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
            
            $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
            if (in_array($fileExtension, $allowedExtensions)) {
                $newFileName = md5(time() . $fileName) . '.' . $fileExtension;
                $uploadFileDir = '../assets/images/community/';
                if (!is_dir($uploadFileDir)) {
                    mkdir($uploadFileDir, 0755, true);
                }
                
                $dest_path = $uploadFileDir . $newFileName;
                if (move_uploaded_file($fileTmpPath, $dest_path)) {
                    $uploaded_path = 'assets/images/community/' . $newFileName;
                }
            }
        }
        
        $final_media = !empty($uploaded_path) ? $uploaded_path : $avatar_url;
        
        if ($id <= 0 || empty($name)) {
            $alertError = 'Name is required.';
        } else {
            try {
                $stmt = $pdo->prepare("UPDATE community_users SET name=:name, subtitle=:subtitle, destination_tag=:tag, avatar_url=:avatar, is_active=:is_active WHERE id=:id");
                $stmt->execute([
                    'name' => $name,
                    'subtitle' => $subtitle,
                    'tag' => $destination_tag,
                    'avatar' => $final_media,
                    'is_active' => $is_active,
                    'id' => $id
                ]);
                $alertSuccess = 'Profile updated successfully!';
            } catch (PDOException $e) {
                $alertError = 'Failed to update profile: ' . $e->getMessage();
            }
        }
    }
    
    elseif ($action === 'delete') {
        $id = isset($_POST['user_id']) ? intval($_POST['user_id']) : 0;
        if ($id > 0) {
            try {
                $stmt = $pdo->prepare("DELETE FROM community_users WHERE id = :id");
                $stmt->execute(['id' => $id]);
                $alertSuccess = 'Profile deleted permanently!';
            } catch (PDOException $e) {
                $alertError = 'Failed to delete profile: ' . $e->getMessage();
            }
        }
    }
}

// Fetch users
$users = [];
try {
    $totalCount = intval($pdo->query("SELECT COUNT(*) FROM community_users")->fetchColumn());
    $pagination = get_pagination_params($totalCount);
    $limit = $pagination['limit'];
    $page = $pagination['page'];
    $totalPages = $pagination['totalPages'];
    $stmt = $pdo->prepare("SELECT * FROM community_users ORDER BY created_at DESC LIMIT :limit OFFSET :offset");
    $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $pagination['offset'], PDO::PARAM_INT);
    $stmt->execute();
    $users = $stmt->fetchAll();
} catch (PDOException $e) {
    $alertError = 'Could not fetch profiles: ' . $e->getMessage();
    $limit = 10;
    $page = 1;
    $totalPages = 1;
}
?>

<div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 2.5rem;">
    <h1 class="page-title">
        Community Profiles Manager
        <span>Manage author profiles for the community feed</span>
    </h1>
    <button class="btn-pill" onclick="openAddModal()">
        <i class="fa-solid fa-plus"></i>
        <span>Add Profile</span>
    </button>
</div>

<?php if (!empty($alertSuccess)): ?>
    <div class="alert alert-success">
        <i class="fa-solid fa-circle-check"></i>
        <span><?php echo htmlspecialchars($alertSuccess); ?></span>
    </div>
<?php endif; ?>
<?php if (!empty($alertError)): ?>
    <div class="alert alert-danger">
        <i class="fa-solid fa-triangle-exclamation"></i>
        <span><?php echo htmlspecialchars($alertError); ?></span>
    </div>
<?php endif; ?>

<?php echo render_limit_dropdown($limit); ?>

<div class="crud-grid">
    <?php foreach ($users as $user): 
        $isActive = intval($user['is_active']) === 1;
    ?>
        <div class="crud-card <?php echo $isActive ? 'crud-card-active' : 'crud-card-inactive'; ?>">
            <?php echo render_admin_active_toggle('community_users', $user['id'], $isActive, $user['name']); ?>
            
            <div style="display:flex; align-items:center; gap:15px; margin-bottom:15px;">
                <div style="width: 50px; height: 50px; background: #eee; border-radius: 50%; overflow: hidden;">
                    <img src="../<?php echo !empty($user['avatar_url']) ? htmlspecialchars($user['avatar_url']) : 'assets/images/user-placeholder.png'; ?>" style="width: 100%; height: 100%; object-fit: cover;" onerror="this.src='../assets/images/logo.png'">
                </div>
                <div>
                    <h3 style="font-size: 16px; margin:0;"><?php echo htmlspecialchars($user['name']); ?></h3>
                    <p style="font-size: 12px; margin:0; color:#64748b;"><?php echo htmlspecialchars($user['subtitle']); ?></p>
                </div>
            </div>
            
            <div style="margin-bottom: 10px;">
                <?php if(!empty($user['destination_tag'])): ?>
                    <span class="badge-accent"><?php echo htmlspecialchars($user['destination_tag']); ?></span>
                <?php endif; ?>
            </div>
            
            <div class="crud-card-footer">
                <span style="font-size:0.75rem;">User ID: <?php echo $user['id']; ?></span>
                <div class="crud-card-actions">
                    <button class="btn-action" onclick='openEditModal(<?php echo json_encode($user, JSON_HEX_APOS | JSON_HEX_QUOT); ?>)'><i class="fa-solid fa-pen"></i></button>
                    <button class="btn-action action-delete" onclick="triggerDelete(<?php echo $user['id']; ?>)"><i class="fa-solid fa-trash"></i></button>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>
<?php echo render_pagination_buttons($page, $totalPages, ['limit' => $limit]); ?>

<!-- Modal -->
<div class="modal-overlay" id="userModal">
    <div class="modal-container" style="max-width: 500px;">
        <div class="modal-header">
            <h3 class="modal-title" id="modalTitle">Add Profile</h3>
            <span class="modal-close" onclick="closeModal()">&times;</span>
        </div>
        <form action="community_users.php" method="POST" enctype="multipart/form-data">
            <div class="modal-body">
                <input type="hidden" name="action" id="modalAction" value="add">
                <input type="hidden" name="user_id" id="edit_user_id">
                
                <div class="form-group">
                    <label class="form-label">Name *</label>
                    <input type="text" name="name" id="user_name" class="form-control" required>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Subtitle (e.g. University Name)</label>
                    <input type="text" name="subtitle" id="user_subtitle" class="form-control">
                </div>
                
                <div class="form-group">
                    <label class="form-label">Destination Tag</label>
                    <input type="text" name="destination_tag" id="user_tag" class="form-control" placeholder="e.g. Canada">
                </div>
                
                <div class="form-group">
                    <label class="form-label">Avatar URL or Upload</label>
                    <input type="text" name="avatar_url" id="user_avatar" class="form-control" placeholder="URL or relative path">
                    <input type="file" name="user_file" class="form-control" style="margin-top:5px;" accept="image/*">
                </div>
                
                <div class="form-group" style="display:flex; align-items:center; gap:0.5rem;">
                    <input type="checkbox" name="is_active" id="user_active" checked style="width:18px;height:18px;">
                    <label for="user_active" style="margin:0;">Active Profile</label>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-outline" onclick="closeModal()">Cancel</button>
                <button type="submit" class="btn-pill">Save Profile</button>
            </div>
        </form>
    </div>
</div>

<div class="modal-overlay" id="deleteModal">
    <div class="modal-container" style="max-width: 400px;">
        <div class="modal-header">
            <h3 class="modal-title" style="color:red;">Delete Profile?</h3>
            <span class="modal-close" onclick="closeDelModal()">&times;</span>
        </div>
        <form action="community_users.php" method="POST">
            <div class="modal-body">
                <input type="hidden" name="action" value="delete">
                <input type="hidden" name="user_id" id="del_id">
                <p>Are you sure you want to delete this profile?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-outline" onclick="closeDelModal()">Cancel</button>
                <button type="submit" class="btn-pill" style="background:var(--danger);">Delete</button>
            </div>
        </form>
    </div>
</div>

<script>
function openAddModal() {
    document.getElementById('modalAction').value = 'add';
    document.getElementById('modalTitle').innerText = 'Add Profile';
    document.getElementById('edit_user_id').value = '';
    document.getElementById('user_name').value = '';
    document.getElementById('user_subtitle').value = '';
    document.getElementById('user_tag').value = '';
    document.getElementById('user_avatar').value = '';
    document.getElementById('userModal').classList.add('active');
}
function openEditModal(user) {
    document.getElementById('modalAction').value = 'update';
    document.getElementById('modalTitle').innerText = 'Edit Profile';
    document.getElementById('edit_user_id').value = user.id;
    document.getElementById('user_name').value = user.name;
    document.getElementById('user_subtitle').value = user.subtitle;
    document.getElementById('user_tag').value = user.destination_tag;
    document.getElementById('user_avatar').value = user.avatar_url;
    document.getElementById('user_active').checked = parseInt(user.is_active) === 1;
    document.getElementById('userModal').classList.add('active');
}
function closeModal() { document.getElementById('userModal').classList.remove('active'); }
function triggerDelete(id) {
    document.getElementById('del_id').value = id;
    document.getElementById('deleteModal').classList.add('active');
}
function closeDelModal() { document.getElementById('deleteModal').classList.remove('active'); }
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
