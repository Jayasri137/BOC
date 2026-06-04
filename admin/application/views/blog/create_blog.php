<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="content-wrapper">
    <div class="container-full">
        <div class="content-header">
            <div class="d-flex align-items-center">
                <div class="me-auto p-3">
                    <h3 class="page-title">Create Blog</h3>
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
                    <a href="<?= base_url(); ?>blog/index" class="btn btn-primary btn-sm">
                        <i class="fa fa-arrow-left"></i> Back
                    </a>
                </div>
            </div>
        </div>

        

        <!-- Main container (keeps original classes and action) -->
        <div class="container border mt-5 mb-5 bg-white rounded shadow-sm">
            <form method="post" enctype="multipart/form-data" action="<?= base_url('blog/create_blog') ?>" class="p-4" id="createBlogForm" novalidate>

                <!-- Add CSRF token if CI has it enabled -->
                <?php if (property_exists($this->security, 'get_csrf_hash')): ?>
                    <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>" />
                <?php endif; ?>

                <!-- Row: Author, Title, Status -->
                <div class="row mb-4 mt-5">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="required">Author Name</label>
                            <input type="text" name="author_name" id="author_name" class="form-control"
                                   required placeholder="Enter Author name" maxlength="50" value="<?= set_value('author_name'); ?>">
                            <small class="text-muted">Characters: <span id="author_count">0</span>/50</small>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="required">Blog Title</label>
                            <input type="text" name="blog_title" id="blog_title" class="form-control"
                                   required placeholder="Enter blog title" maxlength="120" value="<?= set_value('blog_title'); ?>">
                            <small class="text-muted">Characters: <span id="title_count">0</span></small>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="required">Status</label>
                            <select name="status" class="form-control" required>
                                <option value="1" <?= set_select('status', '1', true); ?>>Active</option>
                                <option value="0" <?= set_select('status', '0'); ?>>Inactive</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Row with Description (left) and Image upload + preview (right) -->
                <div class="row mb-4">
                    <!-- Left Column -->
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label class="required">Product Description</label>
                            <textarea name="product_description" id="product_description"
                                      class="form-control" rows="12" col="12" required
                                      placeholder="Enter description"><?= set_value('product_description'); ?></textarea>
                        </div>
                    </div>

                    <!-- Right Column -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <div class="border mt-3 p-3 text-center rounded bg-light"
                                 style="height: 250px; display: flex; align-items: center; justify-content: center;">
                                <img id="previewImage"
                                     src="https://ias.ahattrickz.com/media/demoimage.png"
                                     alt="Preview" class="img-thumbnail"
                                     style="max-width: 100%; max-height: 230px; object-fit: contain;" />
                            </div>
                            <div class="border mt-3 p-3 rounded" style="background:#fff;">
                                <label class="required">Product Image (JPG, JPEG, PNG)*</label>
                                <input type="file" class="form-control mt-2" id="product_image"
                                       name="product_image" accept="image/png, image/jpeg, image/jpg" required>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Buttons (kept as per your structure) -->
                <div class="box-footer d-flex justify-content-between">
                    <button type="reset" id="resetBtn" class="btn btn-danger">
                        <i class="ti-trash"></i> Cancel
                    </button>
                    <!-- submit must be type="submit" -->
                    <button type="submit" id="submitBtn" class="btn btn-primary">
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
    // Initialize CKEditor
    ClassicEditor.create(document.querySelector('#product_description'), {
        toolbar: [
            'undo', 'redo', '|',
            'heading', '|',
            'bold', 'italic', 'underline', 'strikethrough', '|',
            'link', 'bulletedList', 'numberedList', 'blockQuote', '|',
            'insertTable', 'alignment', 'fontColor', 'fontBackgroundColor'
        ],
        table: {
            contentToolbar: ['tableColumn', 'tableRow', 'mergeTableCells']
        }
    }).catch(error => console.error('CKEditor Error:', error));

    // Author & Title char counters
    (function () {
        const author = document.getElementById('author_name');
        const title = document.getElementById('blog_title');
        const authorCount = document.getElementById('author_count');
        const titleCount = document.getElementById('title_count');

        function updateCount(el, counter, max) {
            counter.textContent = el.value.length;
            if (el.value.length > max) {
                el.value = el.value.substring(0, max);
                counter.textContent = max;
            }
        }

        if (author) {
            author.addEventListener('input', () => updateCount(author, authorCount, 50));
            // init
            authorCount.textContent = author.value.length;
        }
        if (title) {
            title.addEventListener('input', () => updateCount(title, titleCount, 120));
            titleCount.textContent = title.value.length;
        }
    })();

    // Image Preview
    (function () {
        const input = document.getElementById('product_image');
        const preview = document.getElementById('previewImage');
        const defaultSrc = preview.src;

        input.addEventListener('change', e => {
            const file = e.target.files[0];
            if (!file) {
                preview.src = defaultSrc;
                return;
            }
            if (!file.type.startsWith('image/')) {
                alert('Please select a valid image file.');
                e.target.value = '';
                preview.src = defaultSrc;
                return;
            }
            const reader = new FileReader();
            reader.onload = ev => preview.src = ev.target.result;
            reader.readAsDataURL(file);
        });
    })();

    // Optional: simple client-side form check to show immediate message if something missing
    document.getElementById('createBlogForm').addEventListener('submit', function (e) {
        // let HTML5 validation handle it; we only do a quick double-check
        const desc = document.getElementById('product_description').value.trim();
        if (desc === '') {
            e.preventDefault();
            alert('Please provide a description.');
            return false;
        }
        return true;
    });
</script>

<style>
.required::after {
    content: " *";
    color: red;
}

.ck-editor__editable_inline {
    min-height: 250px;
}

</style>
