<?php defined('BASEPATH') OR exit('No direct script access allowed');
// expects $type object
$t = isset($type) ? (is_object($type) ? $type : (object)$type) : null;
$id = $t ? ($t->id ?? 0) : 0;
?>
<div class="content-wrapper">
  <div class="container-full">
    <div class="content-header d-flex align-items-center">
      <div class="me-auto"><h3 class="page-title">Edit Invoice Type</h3></div>
      <div>
 <a href="<?= base_url('master/invoice_type'); ?>" class="btn btn-primary btn-sm">
            <i class="fa fa-arrow-left"></i> Back
          </a>     </div>
    </div>

    <div class="box mt-3">
      <div class="box-body">
        <form id="invoiceTypeEdit" method="post" action="<?= site_url('master/update_invoice_type/'.$id); ?>" novalidate>
          <div class="row">
            <div class="col-md-4">
              <div class="form-group">
                <label class="required">Code</label>
                <input type="text" name="code" class="form-control" required maxlength="20" value="<?= htmlspecialchars($t->code ?? '') ?>">
              </div>
            </div>

            <div class="col-md-6">
              <div class="form-group">
                <label class="required">Invoice Type</label>
                <input type="text" name="name" class="form-control" required maxlength="100" value="<?= htmlspecialchars($t->name ?? '') ?>">
              </div>
            </div>

            <div class="col-md-12">
              <div class="form-group">
                <label>Description</label>
                <textarea name="description" class="form-control" rows="3"><?= htmlspecialchars($t->description ?? '') ?></textarea>
              </div>
            </div>

            <div class="col-md-3">
              <div class="form-group">
                <label>Status</label>
                <select name="status" class="form-control">
                  <option value="active" <?= (($t->status ?? '') === 'active') ? 'selected' : '' ?>>Active</option>
                  <option value="inactive" <?= (($t->status ?? '') === 'inactive') ? 'selected' : '' ?>>Inactive</option>
                </select>
              </div>
            </div>
          </div>

          <div class="mt-3 d-flex justify-content-between">
            <a href="<?= site_url('master/invoice_types'); ?>" class="btn btn-danger">Cancel</a>
            <button type="submit" class="btn btn-primary">Update</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<script>
document.getElementById('invoiceTypeEdit').addEventListener('submit', function(e){
  if (!this.code.value.trim() || !this.name.value.trim()) {
    e.preventDefault();
    alert('Code and Name are required.');
    return false;
  }
  return true;
});
</script>
