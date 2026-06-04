<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php
// expects $blog to be an object (from Blog_model::get_blog_by_id)
$blog = isset($blog) ? $blog : null;
$blog_id = $blog ? (int)$blog->id : 0;
$author_value = $blog ? htmlspecialchars($blog->author_name) : '';
$title_value  = $blog ? htmlspecialchars($blog->blog_title) : '';
$status_value = $blog ? (int)$blog->status : 1;
$desc_value   = $blog ? $blog->blog_description : '';
$image_value  = $blog && !empty($blog->blog_image) ? base_url($blog->blog_image) : 'assets/images/blog/default.png';
?>

<div class="content-wrapper">
    <div class="container-full">
        <div class="content-header">
            <div class="d-flex align-items-center">
                <div class="me-auto p-3">
                    <h3 class="page-title">Edit Blog</h3>
                    <nav>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="<?= base_url(); ?>Dashboard">
                                    <i class="mdi mdi-home-outline"></i> Dashboard
                                </a>
                            </li>
                            <li class="breadcrumb-item active">Blog</li>
                        </ol>
                    </nav>
                </div>
                <div class="box-controls pull-right p-3">
                    <a href="<?= base_url('blog') ?>" class="btn btn-primary btn-sm">
                        <i class="fa fa-arrow-left"></i> Back
                    </a>
                </div>
            </div>
        </div>

        <div class="container border p-5 mt-4 mb-5 bg-white rounded shadow-sm">
            <!-- NOTE: action points to update_blog controller -->
            <form method="post" enctype="multipart/form-data" action="<?= base_url('blog/update_blog/'.$blog_id) ?>">

                <div class="row mb-4">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="required">Author Name</label>
                            <input type="text" name="author_name" id="author_name" class="form-control"
                                   required placeholder="Enter Author name" maxlength="50" value="<?= $author_value ?>">
                            <small class="text-muted">Characters: <span id="author_count"><?= strlen($author_value) ?></span>/50</small>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="required">Blog Title</label>
                            <input type="text" name="blog_title" id="blog_title" class="form-control"
                                   required placeholder="Enter blog title" maxlength="120" value="<?= $title_value ?>">
                            <small class="text-muted">Characters: <span id="title_count"><?= strlen($title_value) ?></span></small>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="required">Status</label>
                            <select name="status" class="form-control" required>
                                <option value="1" <?= $status_value===1 ? 'selected' : '' ?>>Active</option>
                                <option value="0" <?= $status_value===0 ? 'selected' : '' ?>>Inactive</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="row mb-4">
                    <!-- Description -->
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label class="required">Product Description</label>
                            <textarea name="product_description" id="product_description" class="form-control" rows="12" required placeholder="Enter description"><?= $desc_value ?></textarea>
                        </div>
                    </div>

                    <!-- Image Upload & Preview -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <div class="border mt-3 p-3 text-center rounded bg-light" style="height: 250px; display:flex; align-items:center; justify-content:center;">
                                <img id="previewImage" src="<?= $image_value ?>" alt="Preview" class="img-thumbnail" style="max-width:100%; max-height:230px; object-fit:contain;" />
                            </div>

                            <div class="border mt-3 p-3 rounded" style="background:#fff;">
                                <label class="required">Product Image (JPG, JPEG, PNG)*</label>
                                <input type="file" class="form-control mt-2" id="product_image" name="product_image" accept="image/png, image/jpeg, image/jpg">
                                <?php if ($blog && !empty($blog->blog_image)): ?>
                                    <small class="text-muted d-block mt-2">Current file: <?= htmlspecialchars(basename($blog->blog_image)) ?></small>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Buttons -->
                <div class="d-flex justify-content-end mt-4 gap-3">
                    <a href="<?= base_url('blog') ?>" class="btn btn-danger">
                        <i class="ti-trash"></i> Cancel
                    </a>
                    <button type="submit" id="submit" name="submit" value="Submit" class="btn btn-success">
                        <i class="ti-save-alt"></i> Save
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- CKEditor 5 -->
<script src="https://cdn.ckeditor.com/ckeditor5/41.2.1/classic/ckeditor.js"></script>

<script>
    ClassicEditor.create(document.querySelector('#product_description')).catch(error => console.error(error));

    // counters
    (function () {
        const author = document.getElementById('author_name');
        const authorCount = document.getElementById('author_count');
        const title = document.getElementById('blog_title');
        const titleCount = document.getElementById('title_count');
        function setCount(el, counterEl, max){ if(!el||!counterEl) return; counterEl.textContent = el.value.length; if(max && el.value.length>max){ el.value = el.value.substring(0,max); counterEl.textContent = el.value.length; } }
        author?.addEventListener('input', ()=> setCount(author, authorCount, 50));
        title?.addEventListener('input', ()=> setCount(title, titleCount, 120));
    })();

    // Image preview
    (function () {
        const fileInput = document.getElementById('product_image');
        const preview = document.getElementById('previewImage');
        if (!fileInput || !preview) return;
        const defaultSrc = preview.src;
        fileInput.addEventListener('change', function (e) {
            const file = e.target.files[0];
            if (!file) { preview.src = defaultSrc; return; }
            if (!file.type.startsWith('image/')) { alert('Please select a valid image.'); e.target.value = ''; preview.src = defaultSrc; return; }
            if (file.size > 10 * 1024 * 1024) { alert('Image too large. Max 10MB.'); e.target.value = ''; return; }
            const reader = new FileReader();
            reader.onload = function (ev) { preview.src = ev.target.result; };
            reader.readAsDataURL(file);
        });
    })();
</script>
