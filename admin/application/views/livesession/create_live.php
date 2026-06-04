<div class="content-wrapper">
  <div class="container-full">
    <div class="content-header">
      <div class="d-flex align-items-center">
        <div class="me-auto p-3">
          <h3 class="page-title">Create Live Session</h3>
          <nav>
            <ol class="breadcrumb">
              <li class="breadcrumb-item">
                <a href="<?= base_url(); ?>Dashboard">
                  <i class="mdi mdi-home-outline"> Dashboard</i>
                </a>
              </li>
              <li class="breadcrumb-item active"> Live Session</li>
            </ol>
          </nav>
        </div>
        <div class="box-controls pull-right">
          <a href="<?= base_url(); ?>livesession/index" class="btn btn-primary btn-sm">
            <i class="fa fa-arrow-left"></i> Back
          </a>
        </div>
      </div>
    </div>

    <div class="container border p-5 mt-5 mb-2">
      <!-- ✅ Updated form -->
      <form id="liveSessionForm" action="<?= base_url('livesession/create'); ?>" method="post">
        <!-- Row 1 -->
        <div class="row mt-3">
          <div class="col-md-4">
            <div class="form-group">
              <label class="required">Session Title</label>
              <input type="text" class="form-control" name="title" placeholder="Enter Session Title" required>
            </div>
          </div>

          <div class="col-md-4">
            <div class="form-group">
              <label class="required">Session URL / Link</label>
              <input type="url" class="form-control" name="url" placeholder="Enter Session URL (https://...)" required>
            </div>
          </div>

          <div class="col-md-4">
            <div class="form-group">
              <label class="required">Status</label>
              <select class="form-control" name="status" required>
                <option value="">Select Status</option>
                <option value="1" selected>Active</option>
                <option value="0">Inactive</option>
              </select>
            </div>
          </div>
        </div>

        <!-- Row 2 -->
        <div class="row mt-3">
          <div class="col-md-12">
            <div class="form-group">
              <label>Description</label>
              <textarea class="form-control" name="description" placeholder="Enter Session Description" rows="4"></textarea>
            </div>
          </div>
        </div>
      </form>
    </div>
  </div>

  <!-- ✅ Footer buttons -->
  <div class="box-footer d-flex justify-content-between">
    <button type="button" id="resetBtn" class="btn btn-danger">
      <i class="ti-trash"></i> Cancel
    </button>
    <button type="button" id="submitBtn" class="btn btn-primary">
      <i class="ti-save-alt"></i> Publish/ Save
    </button>
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
    document.getElementById('liveSessionForm').reset();
  });
</script>
