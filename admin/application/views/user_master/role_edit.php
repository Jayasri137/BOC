<div class="content-wrapper">
    <div class="container-full">
        <div class="content-header">
            <div class="d-flex align-items-center">
                <div class="mr-auto">
                    <h3 class="page-title">Edit Role</h3>
                    <div class="d-inline-block align-items-center">
                        <nav>
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="<?= base_url('dashboard'); ?>"><i class="mdi mdi-home-outline"></i> Dashboard</a></li>
                                <li class="breadcrumb-item"><a href="<?= base_url('master/role'); ?>">Roles</a></li>
                                <li class="breadcrumb-item active">Edit Role</li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </div>

        <section class="content">
            <div class="row">
                <div class="col-12">
                    <div class="box">
                        <div class="box-header with-border">
                            <h4 class="box-title">Edit Role Details</h4>
                        </div>
                        <div class="box-body">
                            <form method="post" action="<?= base_url('master/edit_role/' . $role->id); ?>">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="role_name" class="control-label">Role Name <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" id="role_name" name="role_name" 
                                                   value="<?= htmlspecialchars($role->role_name ?? ''); ?>" required>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="status" class="control-label">Status</label>
                                            <select class="form-control" id="status" name="status">
                                                <option value="1" <?= ($role->status == 1) ? 'selected' : ''; ?>>Active</option>
                                                <option value="0" <?= ($role->status == 0) ? 'selected' : ''; ?>>Inactive</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="box-footer d-flex justify-content-between mt-3">
    <a href="<?= base_url('master/role'); ?>" class="btn btn-danger">
        <i class="ti-trash"></i> Cancel
    </a>
    <button type="submit" class="btn btn-primary" id="submitBtn">
        <i class="ti-save-alt"></i> 
        <span id="submitText">Update Role</span>
        <span id="loadingSpinner" class="spinner-border spinner-border-sm d-none" role="status"></span>
    </button>
</div>


                                               
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('form');
    const submitBtn = document.getElementById('submitBtn');
    const submitText = document.getElementById('submitText');
    const loadingSpinner = document.getElementById('loadingSpinner');
    
    form.addEventListener('submit', function() {
        submitBtn.disabled = true;
        submitText.textContent = 'Updating...';
        loadingSpinner.classList.remove('d-none');
    });
});
</script>