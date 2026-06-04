<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="content-wrapper">
  <div class="container-full">
    <div class="content-header">
      <div class="d-flex align-items-center">
        <div class="me-auto p-3">
          <h3 class="page-title">Edit University</h3>
          <nav>
            <ol class="breadcrumb">
              <li class="breadcrumb-item">
                <a href="<?= base_url('Dashboard'); ?>">
                  <i class="mdi mdi-home-outline"></i> Dashboard
                </a>
              </li>
              <li class="breadcrumb-item active">Edit University</li>
            </ol>
          </nav>
        </div>
        <div class="box-controls pull-right">
          <a href="<?= base_url('master/university'); ?>" class="btn btn-primary btn-sm">
            <i class="fa fa-arrow-left"></i> Back
          </a>
        </div>
      </div>
    </div>

    <div class="container border p-5 mt-4 mb-4">

      <!-- Edit University Form -->
      <form id="universityForm" action="<?= base_url('master/update_university/' . $university->id); ?>" method="post" novalidate>
        <div class="row">
          <!-- Country -->
          <div class="col-md-4">
            <div class="form-group">
              <label class="required">Country</label>
              <select name="country_id" class="form-control" required>
                <option value="">Select Country</option>
                <?php foreach ($countries as $country): ?>
                  <option value="<?= $country->id; ?>" <?= ($country->id == $university->country_id) ? 'selected' : ''; ?>>
                    <?= htmlspecialchars($country->name); ?>
                  </option>
                <?php endforeach; ?>
              </select>
            </div>
          </div>

          <!-- University Name -->
          <div class="col-md-4">
            <div class="form-group">
              <label class="required">University Name</label>
              <input
                type="text"
                class="form-control"
                name="name"
                placeholder="Enter University Name"
                value="<?= htmlspecialchars($university->name); ?>"
                required
              >
            </div>
          </div>

          <!-- Status -->
          <div class="col-md-4">
            <div class="form-group">
              <label>Status</label>
              <select name="status" class="form-control" required>
                <option value="">Select</option>
                <option value="1" <?= ($university->status == 1) ? 'selected' : ''; ?>>Active</option>
                <option value="0" <?= ($university->status == 0) ? 'selected' : ''; ?>>Inactive</option>
              </select>
            </div>
          </div>

          <!-- Notes -->
          
        </div>

        <!-- Buttons -->
        <div class="box-footer d-flex justify-content-between mt-3">
          <a href="<?= base_url('master/university'); ?>" class="btn btn-danger">
            <i class="ti-arrow-left"></i> Cancel
          </a>
          <button id="submitBtn" type="submit" class="btn btn-primary">
            <i class="ti-save-alt"></i> Update
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- JS Validation -->
<script>
document.addEventListener("DOMContentLoaded", function() {
  const form = document.getElementById('universityForm');
  const submitBtn = document.getElementById('submitBtn');

  form.addEventListener('submit', function (e) {
    if (!form.checkValidity()) {
      e.preventDefault();
      e.stopPropagation();
      form.classList.add('was-validated');
      return;
    }

    if (submitBtn) {
      submitBtn.disabled = true;
      submitBtn.innerHTML = '<i class="ti-save-alt"></i> Updating...';
    }
  });
});
</script>

<style>
label.required:after {
  content: " *";
  color: red;
}
</style>