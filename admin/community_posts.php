<?php
// admin/community_posts.php - Community Posts Manager
$pageTitle = 'Community Posts Manager';
require_once __DIR__ . '/includes/header.php';

$alertSuccess = '';
$alertError = '';

// --- HANDLE POST REQUESTS (ADD, UPDATE, DELETE) ---
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = isset($_POST['action']) ? trim($_POST['action']) : '';
    
    if ($action === 'add') {
        $user_id = isset($_POST['user_id']) ? intval($_POST['user_id']) : 1; // Default to admin or system user
        $content = isset($_POST['content']) ? trim($_POST['content']) : '';
        $destination_tag = isset($_POST['destination_tag']) ? trim($_POST['destination_tag']) : '';
        $media_path = isset($_POST['media_path']) ? trim($_POST['media_path']) : '';
        $is_active = isset($_POST['is_active']) ? 1 : 0;
        
        // Handle local image upload
        $uploaded_path = '';
        if (isset($_FILES['post_file']) && $_FILES['post_file']['error'] === UPLOAD_ERR_OK) {
            $fileTmpPath = $_FILES['post_file']['tmp_name'];
            $fileName = $_FILES['post_file']['name'];
            $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
            
            $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'mp4'];
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
        
        $final_media = !empty($uploaded_path) ? $uploaded_path : $media_path;
        
        $author_name = isset($_POST['author_name']) ? trim($_POST['author_name']) : '';
        $destination = isset($_POST['destination']) ? trim($_POST['destination']) : '';
        
        $avatar_url = '';
        if (isset($_FILES['avatar_file']) && $_FILES['avatar_file']['error'] === UPLOAD_ERR_OK) {
            $fileTmpPath = $_FILES['avatar_file']['tmp_name'];
            $fileName = $_FILES['avatar_file']['name'];
            $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
            if (in_array($fileExtension, ['jpg', 'jpeg', 'png', 'gif', 'webp'])) {
                $newFileName = 'avatar_' . md5(time() . $fileName) . '.' . $fileExtension;
                $uploadFileDir = '../assets/images/community/';
                if (move_uploaded_file($fileTmpPath, $uploadFileDir . $newFileName)) {
                    $avatar_url = 'assets/images/community/' . $newFileName;
                }
            }
        }
        $final_avatar = !empty($avatar_url) ? $avatar_url : (isset($_POST['avatar_url']) ? trim($_POST['avatar_url']) : '');
        
        if (empty($content)) {
            $alertError = 'Content is required.';
        } else {
            try {
                $stmt = $pdo->prepare("INSERT INTO community_posts (user_id, content, destination_tag, media_path, avatar_url, author_name, destination, is_active) VALUES (:uid, :content, :tag, :media, :avatar, :author, :dest, :is_active)");
                $stmt->execute([
                    'uid' => $user_id,
                    'content' => $content,
                    'tag' => $destination_tag,
                    'media' => $final_media,
                    'avatar' => $final_avatar,
                    'author' => $author_name,
                    'dest' => $destination,
                    'is_active' => $is_active
                ]);
                $alertSuccess = 'Post added successfully!';
            } catch (PDOException $e) {
                $alertError = 'Failed to add post: ' . $e->getMessage();
            }
        }
    }
    
    elseif ($action === 'update') {
        $id = isset($_POST['post_id']) ? intval($_POST['post_id']) : 0;
        $user_id = isset($_POST['user_id']) ? intval($_POST['user_id']) : 1;
        $content = isset($_POST['content']) ? trim($_POST['content']) : '';
        $destination_tag = isset($_POST['destination_tag']) ? trim($_POST['destination_tag']) : '';
        $media_path = isset($_POST['media_path']) ? trim($_POST['media_path']) : '';
        $is_active = isset($_POST['is_active']) ? 1 : 0;
        
        $uploaded_path = '';
        if (isset($_FILES['post_file']) && $_FILES['post_file']['error'] === UPLOAD_ERR_OK) {
            $fileTmpPath = $_FILES['post_file']['tmp_name'];
            $fileName = $_FILES['post_file']['name'];
            $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
            
            $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'mp4'];
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
        
        $final_media = !empty($uploaded_path) ? $uploaded_path : $media_path;
        
        $author_name = isset($_POST['author_name']) ? trim($_POST['author_name']) : '';
        $destination = isset($_POST['destination']) ? trim($_POST['destination']) : '';
        
        $avatar_url = '';
        if (isset($_FILES['avatar_file']) && $_FILES['avatar_file']['error'] === UPLOAD_ERR_OK) {
            $fileTmpPath = $_FILES['avatar_file']['tmp_name'];
            $fileName = $_FILES['avatar_file']['name'];
            $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
            if (in_array($fileExtension, ['jpg', 'jpeg', 'png', 'gif', 'webp'])) {
                $newFileName = 'avatar_' . md5(time() . $fileName) . '.' . $fileExtension;
                $uploadFileDir = '../assets/images/community/';
                if (move_uploaded_file($fileTmpPath, $uploadFileDir . $newFileName)) {
                    $avatar_url = 'assets/images/community/' . $newFileName;
                }
            }
        }
        $final_avatar = !empty($avatar_url) ? $avatar_url : (isset($_POST['avatar_url']) ? trim($_POST['avatar_url']) : '');
        
        if ($id <= 0 || empty($content)) {
            $alertError = 'Content is required.';
        } else {
            try {
                $stmt = $pdo->prepare("UPDATE community_posts SET user_id=:uid, content=:content, destination_tag=:tag, media_path=:media, avatar_url=:avatar, author_name=:author, destination=:dest, is_active=:is_active WHERE id=:id");
                $stmt->execute([
                    'uid' => $user_id,
                    'content' => $content,
                    'tag' => $destination_tag,
                    'media' => $final_media,
                    'avatar' => $final_avatar,
                    'author' => $author_name,
                    'dest' => $destination,
                    'is_active' => $is_active,
                    'id' => $id
                ]);
                $alertSuccess = 'Post updated successfully!';
            } catch (PDOException $e) {
                $alertError = 'Failed to update post: ' . $e->getMessage();
            }
        }
    }
    
    elseif ($action === 'delete') {
        $id = isset($_POST['post_id']) ? intval($_POST['post_id']) : 0;
        if ($id > 0) {
            try {
                $stmt = $pdo->prepare("DELETE FROM community_posts WHERE id = :id");
                $stmt->execute(['id' => $id]);
                $alertSuccess = 'Post deleted permanently!';
            } catch (PDOException $e) {
                $alertError = 'Failed to delete post: ' . $e->getMessage();
            }
        }
    }
}

// Fetch posts
$posts = [];
try {
    $totalCount = intval($pdo->query("SELECT COUNT(*) FROM community_posts")->fetchColumn());
    $pagination = get_pagination_params($totalCount);
    $limit = $pagination['limit'];
    $page = $pagination['page'];
    $totalPages = $pagination['totalPages'];
    $stmt = $pdo->prepare("SELECT * FROM community_posts ORDER BY created_at DESC LIMIT :limit OFFSET :offset");
    $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $pagination['offset'], PDO::PARAM_INT);
    $stmt->execute();
    $posts = $stmt->fetchAll();
} catch (PDOException $e) {
    $alertError = 'Could not fetch posts: ' . $e->getMessage();
    $limit = 10;
    $page = 1;
    $totalPages = 1;
}
?>

<div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 2.5rem;">
    <h1 class="page-title">
        Community Posts Manager
        <span>Manage user feed posts and moderate content</span>
    </h1>
    <button class="btn-pill" onclick="openAddModal()">
        <i class="fa-solid fa-plus"></i>
        <span>Add Post</span>
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
    <?php foreach ($posts as $post): 
        $isActive = intval($post['is_active']) === 1;
        $postMediaPath = $post['media_path'] ?? '';
        $postIsVideo = preg_match('/\.(mp4|webm|mov)$/i', $postMediaPath) === 1;
    ?>
        <div class="crud-card <?php echo $isActive ? 'crud-card-active' : 'crud-card-inactive'; ?>">
            <?php echo render_admin_active_toggle('community_posts', $post['id'], $isActive, 'community post ' . $post['id']); ?>
            <?php if ($postMediaPath !== ''): ?>
            <div style="height: 150px; background: #eee; border-radius: 8px; margin-bottom: 10px; overflow: hidden;">
                <?php if ($postIsVideo): ?>
                <video controls preload="metadata" style="width: 100%; height: 100%; object-fit: contain;">
                    <source src="../<?php echo htmlspecialchars($postMediaPath, ENT_QUOTES, 'UTF-8'); ?>">
                </video>
                <?php else: ?>
                <img src="../<?php echo htmlspecialchars($postMediaPath, ENT_QUOTES, 'UTF-8'); ?>" alt="Community post media" style="width: 100%; height: 100%; object-fit: cover;">
                <?php endif; ?>
            </div>
            <?php endif; ?>
            <div style="margin-bottom: 10px;">
                <span class="badge-accent"><?php echo htmlspecialchars($post['destination_tag']); ?></span>
            </div>
            <p style="font-size: 0.9rem; margin-bottom: 15px;"><?php echo htmlspecialchars(substr($post['content'], 0, 100)); ?>...</p>
            <div class="crud-card-footer">
                <span style="font-size:0.75rem;">Author: <?php echo htmlspecialchars($post['author_name']); ?></span>
                <div class="crud-card-actions">
                    <button class="btn-action" onclick='openEditModal(<?php echo json_encode($post, JSON_HEX_APOS | JSON_HEX_QUOT); ?>)'><i class="fa-solid fa-pen"></i></button>
                    <button class="btn-action action-delete" onclick="triggerDelete(<?php echo $post['id']; ?>)"><i class="fa-solid fa-trash"></i></button>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>
<?php echo render_pagination_buttons($page, $totalPages, ['limit' => $limit]); ?>

<!-- Modal -->
<div class="modal-overlay" id="postModal">
    <div class="modal-container" style="max-width: 500px;">
        <div class="modal-header">
            <h3 class="modal-title" id="modalTitle">Add Post</h3>
            <span class="modal-close" onclick="closeModal()">&times;</span>
        </div>
        <form action="community_posts.php" method="POST" enctype="multipart/form-data">
            <div class="modal-body">
                <input type="hidden" name="action" id="modalAction" value="add">
                <input type="hidden" name="post_id" id="edit_post_id">
                
                <div class="form-group" style="display:none;">
                    <label class="form-label">User ID (Author)</label>
                    <input type="number" name="user_id" id="post_user" class="form-control" value="0">
                </div>
                
                <div class="form-group">
                    <label class="form-label">Author Name *</label>
                    <input type="text" name="author_name" id="post_author" class="form-control" placeholder="e.g. Amandeep" required>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Subtitle / Destination</label>
                    <input type="text" name="destination" id="post_dest" class="form-control" placeholder="e.g. Southern Alberta Institute of Technology">
                </div>
                
                <div class="form-group">
                    <label class="form-label">Avatar URL or Upload</label>
                    <input type="text" name="avatar_url" id="post_avatar" class="form-control" placeholder="URL or relative path">
                    <input type="file" name="avatar_file" class="form-control" style="margin-top:5px;" accept="image/*">
                </div>
                
                <div class="form-group">
                    <label class="form-label">Post Content *</label>
                    <textarea name="content" id="post_content" class="form-control" rows="4" required></textarea>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Post Media URL or Upload</label>
                    <input type="text" name="media_path" id="post_media" class="form-control" placeholder="URL or relative path">
                    <input type="file" name="post_file" class="form-control" style="margin-top:5px;">
                </div>
                
                <div class="form-group">
                    <label class="form-label">Filter Tag</label>
                    <input type="text" name="destination_tag" id="post_tag" class="form-control" placeholder="e.g. Canada">
                </div>
                
                <div class="form-group" style="display:flex; align-items:center; gap:0.5rem;">
                    <input type="checkbox" name="is_active" id="post_active" checked style="width:18px;height:18px;">
                    <label for="post_active" style="margin:0;">Publish to Feed</label>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-outline" onclick="closeModal()">Cancel</button>
                <button type="submit" class="btn-pill">Save Post</button>
            </div>
        </form>
    </div>
</div>

<div class="modal-overlay" id="deleteModal">
    <div class="modal-container" style="max-width: 400px;">
        <div class="modal-header">
            <h3 class="modal-title" style="color:red;">Delete Post?</h3>
            <span class="modal-close" onclick="closeDelModal()">&times;</span>
        </div>
        <form action="community_posts.php" method="POST">
            <div class="modal-body">
                <input type="hidden" name="action" value="delete">
                <input type="hidden" name="post_id" id="del_id">
                <p>Are you sure you want to delete this post?</p>
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
    document.getElementById('modalTitle').innerText = 'Add Post';
    document.getElementById('edit_post_id').value = '';
    document.getElementById('post_author').value = '';
    document.getElementById('post_dest').value = '';
    document.getElementById('post_avatar').value = '';
    document.getElementById('post_content').value = '';
    document.getElementById('post_tag').value = '';
    document.getElementById('post_media').value = '';
    document.getElementById('postModal').classList.add('active');
}
function openEditModal(post) {
    document.getElementById('modalAction').value = 'update';
    document.getElementById('modalTitle').innerText = 'Edit Post';
    document.getElementById('edit_post_id').value = post.id;
    document.getElementById('post_author').value = post.author_name;
    document.getElementById('post_dest').value = post.destination;
    document.getElementById('post_avatar').value = post.avatar_url;
    document.getElementById('post_user').value = post.user_id;
    document.getElementById('post_content').value = post.content;
    document.getElementById('post_tag').value = post.destination_tag;
    document.getElementById('post_media').value = post.media_path;
    document.getElementById('post_active').checked = parseInt(post.is_active) === 1;
    document.getElementById('postModal').classList.add('active');
}
function closeModal() { document.getElementById('postModal').classList.remove('active'); }
function triggerDelete(id) {
    document.getElementById('del_id').value = id;
    document.getElementById('deleteModal').classList.add('active');
}
function closeDelModal() { document.getElementById('deleteModal').classList.remove('active'); }
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
