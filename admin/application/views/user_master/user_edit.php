<?php defined('BASEPATH') OR exit('No direct script access allowed'); 
// Expecting $user (object or associative array) from controller
$u = isset($user) ? (is_object($user) ? $user : (object)$user) : null;
$id = $u ? ($u->id ?? $u->user_id ?? 0) : 0;
?>

<div class="content-wrapper">
  <div class="container-full">
    <!-- Content Header -->
    <div class="content-header">
      <div class="d-flex align-items-center">
        <div class="me-auto">
          <h3 class="page-title">Edit User</h3>
          <nav>
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="<?= base_url('dashboard'); ?>"><i class="mdi mdi-home-outline"></i> Dashboard</a></li>
              <li class="breadcrumb-item"><a href="<?= base_url('master/user_master'); ?>">User Master</a></li>
              <li class="breadcrumb-item active" aria-current="page">Edit User</li>
            </ol>
          </nav>
        </div>
      </div>
    </div>

    <!-- Edit Form -->
    <section class="content">
      <div class="row">
        <div class="col-12">
          <div class="box">
            <div class="box-header d-flex justify-content-between align-items-center">
              <h4 class="box-title mb-0">Update User</h4>
 <a href="<?= base_url('master/user'); ?>" class="btn btn-primary btn-sm">
            <i class="fa fa-arrow-left"></i> Back
          </a>              </div>

            <div class="box-body">
              <form id="editUserForm" method="post" action="<?= base_url('master/update_user/' . $id); ?>" autocomplete="off">
                <div class="row">

                  <!-- First Name -->
                  <div class="col-md-4">
                    <div class="form-group">
                      <label class="required">First Name</label>
                      <input type="text" name="first_name" class="form-control" required
                             value="<?= htmlspecialchars($u->first_name ?? '') ?>">
                    </div>
                  </div>

                  <!-- Last Name -->
                  <div class="col-md-4">
                    <div class="form-group">
                      <label>Last Name</label>
                      <input type="text" name="last_name" class="form-control"
                             value="<?= htmlspecialchars($u->last_name ?? '') ?>">
                    </div>
                  </div>

                  <!-- Mobile -->
                  <div class="col-md-4">
                    <div class="form-group">
                      <label class="required">Mobile Number</label>
                      <input type="text" name="mobile" class="form-control" maxlength="10" required
                             value="<?= htmlspecialchars($u->mobile ?? '') ?>">
                    </div>
                  </div>

                  <!-- Email -->
                  <div class="col-md-4">
                    <div class="form-group">
                      <label class="required">Email</label>
                      <input type="email" name="email" class="form-control" required
                             value="<?= htmlspecialchars($u->email ?? '') ?>">
                    </div>
                  </div>

                  <!-- Password (optional) -->
                  <div class="col-md-4">
                    <div class="form-group">
                      <label>New Password <small class="text-muted">(leave blank to keep current)</small></label>
                      <input type="password" name="password" class="form-control" minlength="6" placeholder="Enter new password to change">
                    </div>
                  </div>

                  <!-- Role -->
                  <div class="col-md-4">
                    <div class="form-group">
                      <label class="required">Role</label>
                      <select name="role" class="form-control" required>
                        <option value="">Select Role</option>
                        <?php $roles = ['Admin','Manager','Executive','Staff']; ?>
                        <?php foreach ($roles as $r): ?>
                          <option value="<?= $r ?>" <?= (isset($u->role) && $u->role===$r) ? 'selected' : '' ?>><?= $r ?></option>
                        <?php endforeach; ?>
                      </select>
                    </div>
                  </div>

                  <!-- Branch -->
                  <div class="col-md-4">
                    <div class="form-group">
                      <label>Branch</label>
                      <select name="branch" class="form-control">
                        <option value="">Select Branch</option>
                        <?php $branches = ['Chennai','Bangalore','Hyderabad','Coimbatore']; ?>
                        <?php foreach ($branches as $b): ?>
                          <option value="<?= $b ?>" <?= (isset($u->branch) && $u->branch===$b) ? 'selected' : '' ?>><?= $b ?></option>
                        <?php endforeach; ?>
                      </select>
                    </div>
                  </div>

                  <!-- Status -->
                  <div class="col-md-4">
                    <div class="form-group">
                      <label>Status</label>
                      <select name="status" class="form-control">
                        <option value="Active" <?= (isset($u->status) && $u->status==='Active') ? 'selected' : '' ?>>Active</option>
                        <option value="Pending" <?= (isset($u->status) && $u->status==='Pending') ? 'selected' : '' ?>>Pending</option>
                        <option value="Inactive" <?= (isset($u->status) && $u->status==='Inactive') ? 'selected' : '' ?>>Inactive</option>
                      </select>
                    </div>
                  </div>

                </div>

                <div class="box-footer d-flex justify-content-between mt-3">
                  <a href="<?= base_url('master/user_master'); ?>" class="btn btn-danger">
                    <i class="ti-arrow-left"></i> Cancel
                  </a>

                  <div>
                    <button type="submit" class="btn btn-primary">
                      <i class="ti-save"></i> Update User
                    </button>
                  </div>
                </div>

              </form>
            </div>

          </div>
        </div>
      </div>
    </section>

  </div>
</div>

<!-- Client-side validation (same rules as create) -->
<script>
document.addEventListener("DOMContentLoaded", function () {
  const form = document.getElementById("editUserForm");

  form.addEventListener("submit", function (e) {
    const mobile = form.mobile.value.trim();
    const email = form.email.value.trim();
    let valid = true;

    if (!/^[6-9][0-9]{9}$/.test(mobile)) {
      alert("Please enter a valid 10-digit mobile number starting with 6-9.");
      valid = false;
    }

    if (!/^[^@\s]+@[^@\s]+\.[^@\s]+$/.test(email)) {
      alert("Please enter a valid email address.");
      valid = false;
    }

    if (!valid) {
      e.preventDefault();
    }
  });
});
</script>
