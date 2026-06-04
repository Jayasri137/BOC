<div class="content-wrapper">
  <div class="container-full">

    <div class="content-header">
      <div class="d-flex align-items-center justify-content-between">
        <div>
          <h3 class="page-title">Add Country</h3>
          <nav>
            <ol class="breadcrumb">
              <li class="breadcrumb-item">
                <a href="<?= base_url('dashboard'); ?>"><i class="mdi mdi-home-outline"></i> Dashboard</a>
              </li>
              <li class="breadcrumb-item active">Add Country</li>
            </ol>
          </nav>
        </div>
        <div>
          <a href="<?= base_url('master/country'); ?>" class="btn btn-primary btn-sm">
            <i class="fa fa-arrow-left"></i> Back
          </a>
        </div>
      </div>
    </div>

    <div class="container border p-5 mt-4 mb-4">
      <form method="POST" action="<?= base_url('master/create_country'); ?>" id="countryForm">
        <div class="row">
          <div class="col-md-4">
            <div class="form-group">
              <label class="required">Country Name</label>
              <input type="text" name="name" class="form-control" placeholder="Enter Country" required>
            </div>
          </div>

          <div class="col-md-4">
            <div class="form-group">
              <label class="required">Country Code</label>
              <input type="text" id="country_code" name="country_code" class="form-control" placeholder="e.g. IN, US, UK" required>
            </div>
          </div>

          <div class="col-md-4">
            <div class="form-group">
              <label>Status</label>
              <select name="status" class="form-control">
                <option value="1">Active</option>
                <option value="0">Inactive</option>
              </select>
            </div>
          </div>
        </div>

        <div class="box-footer d-flex justify-content-between mt-3">
          <button type="reset" class="btn btn-danger">
            <i class="ti-trash"></i> Cancel
          </button>
          <button type="submit" class="btn btn-primary">
            <i class="ti-save-alt"></i> Save
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

<style>
label.required:after { content: " *"; color: red; }
</style>
