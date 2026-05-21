<?php
// admin/news.php - News Articles CRUD Editor with Local Image Upload Support
$pageTitle = 'News & Articles Manager';
require_once 'includes/header.php';

$alertSuccess = '';
$alertError = '';

// --- HANDLE POST REQUESTS (ADD, UPDATE, DELETE) ---
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = isset($_POST['action']) ? trim($_POST['action']) : '';
    
    // 1. ADD NEW ARTICLE
    if ($action === 'add') {
        $title = isset($_POST['title']) ? trim($_POST['title']) : '';
        $date_string = isset($_POST['date_string']) ? trim($_POST['date_string']) : '';
        $tag = isset($_POST['tag']) ? trim($_POST['tag']) : '';
        $excerpt = isset($_POST['excerpt']) ? trim($_POST['excerpt']) : '';
        $emoji = isset($_POST['emoji']) ? trim($_POST['emoji']) : '📚';
        $link = isset($_POST['link']) ? trim($_POST['link']) : '';
        $image_path = isset($_POST['image_path']) ? trim($_POST['image_path']) : '';
        $is_active = isset($_POST['is_active']) ? 1 : 0;
        
        // Handle local image upload
        $uploaded_path = '';
        if (isset($_FILES['news_file']) && $_FILES['news_file']['error'] === UPLOAD_ERR_OK) {
            $fileTmpPath = $_FILES['news_file']['tmp_name'];
            $fileName = $_FILES['news_file']['name'];
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
        
        if (empty($title) || empty($date_string) || empty($tag) || empty($excerpt)) {
            $alertError = 'Title, Date, Category Tag, and Excerpt are required fields.';
        } else {
            try {
                $stmt = $pdo->prepare("
                    INSERT INTO news_articles (title, date_string, tag, excerpt, emoji, link, image_path, is_active) 
                    VALUES (:title, :date_string, :tag, :excerpt, :emoji, :link, :image_path, :is_active)
                ");
                $stmt->execute([
                    'title' => $title,
                    'date_string' => $date_string,
                    'tag' => $tag,
                    'excerpt' => $excerpt,
                    'emoji' => $emoji,
                    'link' => $link,
                    'image_path' => $final_image_source,
                    'is_active' => $is_active
                ]);
                $alertSuccess = 'News article added successfully!';
            } catch (PDOException $e) {
                $alertError = 'Failed to add article: ' . $e->getMessage();
            }
        }
    }
    
    // 2. UPDATE ARTICLE
    elseif ($action === 'update') {
        $id = isset($_POST['article_id']) ? intval($_POST['article_id']) : 0;
        $title = isset($_POST['title']) ? trim($_POST['title']) : '';
        $date_string = isset($_POST['date_string']) ? trim($_POST['date_string']) : '';
        $tag = isset($_POST['tag']) ? trim($_POST['tag']) : '';
        $excerpt = isset($_POST['excerpt']) ? trim($_POST['excerpt']) : '';
        $emoji = isset($_POST['emoji']) ? trim($_POST['emoji']) : '📚';
        $link = isset($_POST['link']) ? trim($_POST['link']) : '';
        $image_path = isset($_POST['image_path']) ? trim($_POST['image_path']) : '';
        $is_active = isset($_POST['is_active']) ? 1 : 0;
        
        // Handle local image upload
        $uploaded_path = '';
        if (isset($_FILES['news_file']) && $_FILES['news_file']['error'] === UPLOAD_ERR_OK) {
            $fileTmpPath = $_FILES['news_file']['tmp_name'];
            $fileName = $_FILES['news_file']['name'];
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
        
        if ($id <= 0 || empty($title) || empty($date_string) || empty($tag) || empty($excerpt)) {
            $alertError = 'Invalid parameters. Title, Date, Tag, and Excerpt are required.';
        } else {
            try {
                $stmt = $pdo->prepare("
                    UPDATE news_articles 
                    SET title = :title, 
                        date_string = :date_string, 
                        tag = :tag, 
                        excerpt = :excerpt, 
                        emoji = :emoji, 
                        link = :link, 
                        image_path = :image_path,
                        is_active = :is_active 
                    WHERE id = :id
                ");
                $stmt->execute([
                    'title' => $title,
                    'date_string' => $date_string,
                    'tag' => $tag,
                    'excerpt' => $excerpt,
                    'emoji' => $emoji,
                    'link' => $link,
                    'image_path' => $final_image_source,
                    'is_active' => $is_active,
                    'id' => $id
                ]);
                $alertSuccess = 'News article updated successfully!';
            } catch (PDOException $e) {
                $alertError = 'Failed to update article: ' . $e->getMessage();
            }
        }
    }
    
    // 3. DELETE ARTICLE
    elseif ($action === 'delete') {
        $id = isset($_POST['article_id']) ? intval($_POST['article_id']) : 0;
        if ($id <= 0) {
            $alertError = 'Invalid article ID specified for deletion.';
        } else {
            try {
                // Delete actual local file if it exists inside uploads
                $stmtFile = $pdo->prepare("SELECT image_path FROM news_articles WHERE id = :id LIMIT 1");
                $stmtFile->execute(['id' => $id]);
                $artFile = $stmtFile->fetch();
                if ($artFile && strpos($artFile['image_path'], 'assets/images/uploads/') === 0) {
                    $local_file = '../' . $artFile['image_path'];
                    if (file_exists($local_file)) {
                        unlink($local_file);
                    }
                }

                $stmt = $pdo->prepare("DELETE FROM news_articles WHERE id = :id");
                $stmt->execute(['id' => $id]);
                $alertSuccess = 'News article deleted permanently!';
            } catch (PDOException $e) {
                $alertError = 'Failed to delete article: ' . $e->getMessage();
            }
        }
    }
}

// Fetch all articles
$articles = [];
try {
    $stmt = $pdo->query("SELECT * FROM news_articles ORDER BY id DESC");
    $articles = $stmt->fetchAll();
} catch (PDOException $e) {
    $alertError = 'Could not fetch articles: ' . $e->getMessage();
}
?>

<div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 2.5rem; flex-wrap: wrap; gap: 1rem;">
    <h1 class="page-title">
        News & Articles Manager
        <span>Manage dynamic blogs, student resource guides, and official study destination news announcements</span>
    </h1>
    <button class="btn-pill" onclick="openAddModal()">
        <i class="fa-solid fa-plus"></i>
        <span>Add New Article</span>
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

<?php if (empty($articles)): ?>
    <div class="panel-card" style="text-align: center; padding: 5rem 2rem; color: var(--text-secondary);">
        <i class="fa-solid fa-newspaper" style="font-size: 3rem; margin-bottom: 1.5rem; color: var(--text-muted);"></i>
        <h3>No articles exist in the database.</h3>
        <p style="margin-top: 0.5rem; font-size: 0.9rem;">Click "Add New Article" to compose your first blog post!</p>
    </div>
<?php else: ?>
    <div class="crud-grid">
        <?php foreach ($articles as $art): 
            $isActive = intval($art['is_active']) === 1;
            $title = clean_output($art['title']);
            $date = clean_output($art['date_string']);
            $tag = clean_output($art['tag']);
            $emoji = clean_output($art['emoji']);
            $image_path = clean_output($art['image_path'] ?? '');
        ?>
            <div class="crud-card <?php echo $isActive ? 'crud-card-active' : 'crud-card-inactive'; ?>">
                <div class="crud-card-badge-icon">
                    <i class="fa-solid <?php echo $isActive ? 'fa-check' : 'fa-eye-slash'; ?>"></i>
                </div>
                
                <div style="height: 120px; background: var(--bg-hover); border-radius: 12px; margin-bottom: 1rem; display: flex; align-items: center; justify-content: center; border: 1px solid var(--border); overflow: hidden; position: relative;">
                    <?php if (!empty($image_path)): ?>
                        <img src="../<?php echo $image_path; ?>" alt="Article Image" style="width: 100%; height: 100%; object-fit: cover;">
                    <?php else: ?>
                        <span style="font-size: 3rem;"><?php echo $emoji; ?></span>
                    <?php endif; ?>
                </div>

                <div class="crud-card-header" style="display: block;">
                    <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 0.3rem;">
                        <span style="font-size: 0.75rem; color: var(--text-muted);"><i class="fa-regular fa-calendar"></i> <?php echo $date; ?></span>
                        <span class="badge-accent" style="font-size: 0.75rem; background: var(--primary-glow); color: var(--primary); padding: 0.15rem 0.5rem; border-radius: 4px; font-weight: 500;"><?php echo $tag; ?></span>
                    </div>
                    <h4 class="crud-card-title"><?php echo $title; ?></h4>
                </div>
                
                <p class="crud-card-desc"><?php echo clean_output($art['excerpt']); ?></p>

                <div class="crud-card-footer">
                    <span class="crud-card-info" style="font-size: 0.75rem; overflow: hidden; text-overflow: ellipsis; max-width: 120px; white-space: nowrap;">Img: <strong><?php echo $image_path ? $image_path : 'None'; ?></strong></span>
                    <div class="crud-card-actions">
                        <button class="btn-action action-edit" title="Edit Article" onclick="openEditModal(<?php echo htmlspecialchars(json_encode($art)); ?>)">
                            <i class="fa-solid fa-pen-to-square"></i>
                        </button>
                        <button class="btn-action action-delete" title="Delete Article" onclick="triggerDeleteArticle(<?php echo $art['id']; ?>, '<?php echo $title; ?>')">
                            <i class="fa-solid fa-trash-can"></i>
                        </button>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<!-- 1. ADD / EDIT DIALOG MODAL -->
<div class="modal-overlay" id="articleModal">
    <div class="modal-container" style="max-width: 500px;">
        <div class="modal-header">
            <h3 class="modal-title" id="modalTitle">Compose News Article</h3>
            <span class="modal-close" onclick="closeArticleModal()">&times;</span>
        </div>
        <form action="news.php" method="POST" enctype="multipart/form-data">
            <div class="modal-body">
                <input type="hidden" name="action" id="modalAction" value="add">
                <input type="hidden" name="article_id" id="edit_article_id">
                
                <div class="form-group">
                    <label for="art_title" class="form-label">Article Heading Title *</label>
                    <input type="text" name="title" id="art_title" class="form-control" placeholder="e.g., Why Malta is the Smart Choice for Students" required>
                </div>
                
                <div class="detail-grid">
                    <div class="form-group">
                        <label for="art_date" class="form-label">Publication Date *</label>
                        <input type="text" name="date_string" id="art_date" class="form-control" placeholder="e.g., April 2025" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="art_tag" class="form-label">Category Tag *</label>
                        <input type="text" name="tag" id="art_tag" class="form-control" placeholder="e.g., Study Abroad" required>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="art_excerpt" class="form-label">Short Summary Excerpt *</label>
                    <textarea name="excerpt" id="art_excerpt" class="form-control" rows="3" placeholder="Enter short, engaging intro excerpt summary of the article..." required></textarea>
                </div>

                <div class="detail-grid">
                    <div class="form-group">
                        <label for="art_emoji" class="form-label">Display Country Emoji *</label>
                        <input type="text" name="emoji" id="art_emoji" class="form-control" placeholder="e.g., 🇲🇹 or 📚" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="art_link" class="form-label">Destination Details Link URL</label>
                        <input type="text" name="link" id="art_link" class="form-control" placeholder="e.g., blog-details.php?id=14">
                    </div>
                </div>

                <div class="detail-grid">
                    <div class="form-group">
                        <label for="art_img" class="form-label">Cover Image Path</label>
                        <input type="text" name="image_path" id="art_img" class="form-control" placeholder="e.g., assets/images/blog1.jpg">
                    </div>
                    
                    <div class="form-group">
                        <label for="art_file" class="form-label">Or Upload Local Image</label>
                        <input type="file" name="news_file" id="art_file" class="form-control" accept="image/*">
                    </div>
                </div>

                <div class="form-group" style="margin-bottom: 0; display: flex; align-items: center; gap: 0.5rem;">
                    <input type="checkbox" name="is_active" id="art_active" checked style="width: 18px; height: 18px; accent-color: var(--primary); cursor: pointer;">
                    <label for="art_active" class="form-label" style="margin-bottom: 0; cursor: pointer; user-select: none;">Publish immediately (Active & Visible on Blog/News listings)</label>
                </div>
            </div>
            
            <div class="modal-footer">
                <button type="button" class="btn-outline" onclick="closeArticleModal()">Cancel</button>
                <button type="submit" class="btn-pill">
                    <i class="fa-solid fa-floppy-disk"></i>
                    <span>Save Article</span>
                </button>
            </div>
        </form>
    </div>
</div>

<!-- 2. DELETE CONFIRMATION MODAL -->
<div class="modal-overlay" id="deleteModal">
    <div class="modal-container" style="max-width: 400px;">
        <div class="modal-header" style="border-bottom: none; padding-bottom: 0;">
            <h3 class="modal-title" style="color: var(--danger);"><i class="fa-solid fa-triangle-exclamation"></i> Delete Article?</h3>
            <span class="modal-close" onclick="closeDeleteModal()">&times;</span>
        </div>
        <form action="news.php" method="POST">
            <div class="modal-body" style="padding-top: 1rem;">
                <input type="hidden" name="action" value="delete">
                <input type="hidden" name="article_id" id="deleteArticleId">
                <p style="font-size: 0.95rem; line-height: 1.6; color: var(--text-secondary);">
                    Are you sure you want to permanently delete article <strong id="deleteArticleName" style="color: var(--text-primary);">Article</strong>?
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
    document.getElementById('modalTitle').innerText = 'Compose News Article';
    document.getElementById('edit_article_id').value = '';
    document.getElementById('art_title').value = '';
    document.getElementById('art_date').value = '';
    document.getElementById('art_tag').value = 'Study Abroad';
    document.getElementById('art_excerpt').value = '';
    document.getElementById('art_emoji').value = '📚';
    document.getElementById('art_link').value = 'blog-details.php?id=';
    document.getElementById('art_img').value = '';
    document.getElementById('art_file').value = '';
    document.getElementById('art_active').checked = true;
    
    document.getElementById('articleModal').classList.add('active');
}

function openEditModal(art) {
    document.getElementById('modalAction').value = 'update';
    document.getElementById('modalTitle').innerText = 'Edit Article Details';
    document.getElementById('edit_article_id').value = art.id;
    document.getElementById('art_title').value = art.title;
    document.getElementById('art_date').value = art.date_string;
    document.getElementById('art_tag').value = art.tag;
    document.getElementById('art_excerpt').value = art.excerpt;
    document.getElementById('art_emoji').value = art.emoji;
    document.getElementById('art_link').value = art.link;
    document.getElementById('art_img').value = art.image_path || '';
    document.getElementById('art_file').value = '';
    document.getElementById('art_active').checked = parseInt(art.is_active) === 1;
    
    document.getElementById('articleModal').classList.add('active');
}

function closeArticleModal() {
    document.getElementById('articleModal').classList.remove('active');
}

function triggerDeleteArticle(id, name) {
    document.getElementById('deleteArticleId').value = id;
    document.getElementById('deleteArticleName').innerText = name;
    document.getElementById('deleteModal').classList.add('active');
}

function closeDeleteModal() {
    document.getElementById('deleteModal').classList.remove('active');
}
</script>

<?php require_once 'includes/footer.php'; ?>
