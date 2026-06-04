<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php $country = $this->db->query("SELECT * FROM country"); ?>

<div class="content-wrapper">
  <div class="container-full">
    <div class="content-header d-flex align-items-center">
      <div class="me-auto"><h3 class="page-title">Create University</h3></div>
      <div>
        <a href="<?= base_url('master/university'); ?>" class="btn btn-primary btn-sm">
          <i class="fa fa-arrow-left"></i> Back
        </a>
      </div>
    </div>

    <div class="container border p-5 mt-4 mb-4">
      <form id="univForm" action="<?= site_url('master/store_university'); ?>" method="post" novalidate>
        <div class="row g-3">
          <!-- Country select -->
          <div class="col-md-3">
            <div class="form-group">
              <label class="required">Country</label>
              <select id="countrySelect" name="country_id" class="form-control" required>
                <option value="">-- Select Country --</option>

                <?php if ($country && $country->num_rows() > 0): ?>
                  <?php foreach ($country->result() as $c): ?>
                    <option name="country_code" value="<?= (int)$c->id ?>"
                            data-code="<?= htmlspecialchars($c->country_code ?? '') ?>">
                      <?= htmlspecialchars(($c->name ?? $c->country_name ?? 'Unnamed') ) ?>
                    </option>
                  <?php endforeach; ?>
                <?php endif; ?>
              </select>
            </div>
          </div>

        

          <!-- University name -->
          <div class="col-md-3">
            <div class="form-group">
              <label class="required">University Name</label>
              <input type="text" name="name" class="form-control" placeholder="Enter University Name" required>
            </div>
          </div>

          <!-- Status -->
          <div class="col-md-3">
            <div class="form-group">
              <label>Status</label>
              <select name="status" class="form-control">
                <option value="active">Active</option>
                <option value="inactive">Inactive</option>
              </select>
            </div>
          </div>
        </div>

        <div class="box-footer d-flex justify-content-between mt-4">
          <a href="<?= site_url('master/university'); ?>" class="btn btn-danger">
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

<script>
document.addEventListener('DOMContentLoaded', function () {
 
  const form = document.getElementById('univForm');
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
      submitBtn.innerHTML = '<i class="ti-save-alt"></i> Saving...';
    }
  });
});
</script>

<style>
label.required:after { content: " *"; color: red; }
</style>
