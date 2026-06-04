<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<div class="content-wrapper">
    <div class="container-full">
        <div class="content-header">
            <div class="d-flex align-items-center">
                <div class="me-auto p-3">
                    <h3 class="page-title">Create Role</h3>
                    <nav>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="<?= base_url('Dashboard'); ?>">
                                    <i class="mdi mdi-home-outline"></i> Dashboard
                                </a>
                            </li>
                            <li class="breadcrumb-item active">Create Role</li>
                        </ol>
                    </nav>
                </div>
                <div class="box-controls pull-right">
                    <a href="<?= base_url('master/role'); ?>" class="btn btn-primary btn-sm">
                        <i class="fa fa-arrow-left"></i> Back
                    </a>
                </div>
            </div>
        </div>

        <div class="container border p-5 mt-4 mb-4">


            <!-- Form -->
            <form id="roleForm" action="<?= base_url('master/create_role') ?>" method="post" novalidate>
                <div class="row">
                    <!-- Role Name -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="required">Role</label>
                            <input type="text"
                                   class="form-control"
                                   name="role_name"
                                   placeholder="Enter Role"
                                   value="<?= set_value('role_name'); ?>"
                                   required>
                        </div>
                    </div>

                    <!-- Status -->
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Status</label>
                            <select name="status" class="form-control" required>
                                <option value="">Select</option>
                                <option value="1" <?= set_value('status') === '1' ? 'selected' : '' ?>>Active</option>
                                <option value="0" <?= set_value('status') === '0' ? 'selected' : '' ?>>Inactive</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Buttons -->
                <div class="box-footer d-flex justify-content-between mt-3">
                    <button type="reset" class="btn btn-danger">
                        <i class="ti-trash"></i> Cancel
                    </button>
                    <button id="submitBtn" type="submit" class="btn btn-primary">
                        <i class="ti-save-alt"></i> Save
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Small JS: form validation + disable submit button to prevent double post -->
<script>
document.addEventListener("DOMContentLoaded", function() {
    const form = document.getElementById('roleForm');
    const submitBtn = document.getElementById('submitBtn');

    form.addEventListener('submit', function (e) {
        // Use browser validation
        if (!form.checkValidity()) {
            e.preventDefault();
            e.stopPropagation();
            form.classList.add('was-validated'); // bootstrap
            return;
        }

        // Disable button to prevent double submit
        if (submitBtn) {
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="ti-save-alt"></i> Saving...';
        }
    });
});
</script>

<style>
/* Optional style to show required fields */
label.required:after {
    content: " *";
    color: red;
}
</style>
