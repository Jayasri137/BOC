<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="content-wrapper">
  <div class="container-full">

    <!-- Header -->
    <div class="content-header d-flex align-items-center justify-content-between">
      <div>
        <h3 class="page-title">Add Intake Year</h3>
        <nav>
          <ol class="breadcrumb">
            <li class="breadcrumb-item">
              <a href="<?= base_url('dashboard'); ?>">
                <i class="mdi mdi-home-outline"></i> Dashboard
              </a>
            </li>
            <li class="breadcrumb-item active">Add Intake Year</li>
          </ol>
        </nav>
      </div>
      <div>
        <a href="<?= base_url('master/intake_year'); ?>" class="btn btn-primary btn-sm">
          <i class="fa fa-arrow-left"></i> Back
        </a>
      </div>
    </div>

    <!-- Form Section -->
    <div class="container border p-5 mt-4 mb-4 bg-white rounded shadow-sm">
      <form id="intakeYearForm" action="<?= site_url('master/create_intake_year'); ?>" method="post" novalidate>
        <div class="row g-3">
          
          <!-- Intake Year -->
          <div class="col-md-4">
            <div class="form-group">
              <label class="required">Intake Year</label>
              <input type="text" name="intake_year" class="form-control" placeholder="e.g. 2025-26" maxlength="20" required>
            </div>
          </div>

          <!-- Status -->
          <div class="col-md-4">
            <div class="form-group">
              <label>Status</label>
              <select name="status" class="form-control">
                <option value="Active" selected>Active</option>
                <option value="Inactive">Inactive</option>
              </select>
            </div>
          </div>
        </div>

        <!-- Buttons -->
        <div class="d-flex justify-content-between mt-4">
          <a href="<?= site_url('master/intake_year'); ?>" class="btn btn-danger">
            <i class="ti-trash"></i> Cancel
          </a>
          <button id="submitBtn" type="submit" class="btn btn-primary">
            <i class="ti-save-alt"></i> Save
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Script -->
<script>
document.addEventListener("DOMContentLoaded", function() {
  const form = document.getElementById('intakeYearForm');
  const submitBtn = document.getElementById('submitBtn');

  form.addEventListener('submit', function(e) {
    if (!form.checkValidity()) {
      e.preventDefault();
      e.stopPropagation();
      form.classList.add('was-validated');
      return false;
    }

    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="ti-save-alt"></i> Saving...';
  });
});
</script>

<style>
label.required:after {
  content: " *";
  color: red;
}
</style>
