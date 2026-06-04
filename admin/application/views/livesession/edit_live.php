<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<?php
// expecting $live to be passed (single row object)
if (!isset($live) || empty($live)) {
    echo '<div class="alert alert-warning">No record found.</div>';
    return;
}
?>

<div class="content-wrapper">
  <div class="container-full">
    <div class="content-header">
      <div class="d-flex align-items-center">
        <div class="me-auto p-3">
          <h3 class="page-title">Edit Live Session</h3>
          <nav>
            <ol class="breadcrumb">
              <li class="breadcrumb-item">
                <a href="<?= base_url('Dashboard'); ?>">
                  <i class="mdi mdi-home-outline"> Dashboard</i>
                </a>
              </li>
              <li class="breadcrumb-item"><a href="<?= site_url('livesession'); ?>">Live Session</a></li>
              <li class="breadcrumb-item active">Edit</li>
            </ol>
          </nav>
        </div>
        <div class="box-controls pull-right">
          <a href="<?= site_url('livesession'); ?>" class="btn btn-primary btn-sm">
            <i class="fa fa-arrow-left"></i> Back
          </a>
        </div>
      </div>
    </div>

    <div class="container border p-5 mt-5 mb-2">
      <?php if ($this->session->flashdata('error')): ?>
        <div class="alert alert-danger"><?= $this->session->flashdata('error'); ?></div>
      <?php endif; ?>

      <form id="liveSessionForm" action="<?= site_url('livesession/update_live/'.$live->id); ?>" method="post">
        <!-- Row 1 -->
        <div class="row mt-3">
          <div class="col-md-4">
            <div class="form-group">
              <label class="required">Session Title</label>
              <input type="text" class="form-control" name="title" placeholder="Enter Session Title" value="<?= set_value('title', $live->title); ?>" maxlength="255" required>
              <?= form_error('title', '<small class="text-danger">', '</small>'); ?>
            </div>
          </div>

          <div class="col-md-4">
            <div class="form-group">
              <label class="required">Session URL / Link</label>
              <input type="text" class="form-control" name="url" placeholder="Enter Session URL" value="<?= set_value('url', $live->url); ?>" maxlength="255" required>
              <?= form_error('url', '<small class="text-danger">', '</small>'); ?>
            </div>
          </div>

          <div class="col-md-4">
            <div class="form-group">
              <label class="required">Status</label>
              <select class="form-control" name="status" required>
                <option value="1" <?= set_select('status', '1', ($live->status == 1)); ?>>Active</option>
                <option value="0" <?= set_select('status', '0', ($live->status == 0)); ?>>Inactive</option>
              </select>
            </div>
          </div>
        </div>

        <!-- Row 2 -->
        <div class="row mt-3">
          <div class="col-md-12">
            <div class="form-group">
              <label>Description</label>
              <textarea class="form-control" name="description" placeholder="Enter Session Description" rows="4"><?= set_value('description', $live->description); ?></textarea>
              <?= form_error('description', '<small class="text-danger">', '</small>'); ?>
            </div>
          </div>
        </div>
      </form>
    </div>

    <!-- ✅ Footer buttons -->
    <div class="box-footer d-flex justify-content-between">
      <button type="button" id="resetBtn" class="btn btn-danger">
        <i class="ti-trash"></i> Cancel
      </button>
      <button type="button" id="submitBtn" class="btn btn-primary">
        <i class="ti-save-alt"></i> Update
      </button>
    </div>
  </div>
</div>

<!-- ✅ Working JS -->
<script>
  document.getElementById('submitBtn').addEventListener('click', function () {
    const form = document.getElementById('liveSessionForm');
    if (form.checkValidity()) {
      form.submit(); // normal form submission
    } else {
      form.reportValidity(); // show validation messages
    }
  });

  document.getElementById('resetBtn').addEventListener('click', function () {
    window.location.href = '<?= site_url('livesession'); ?>';
  });
</script>