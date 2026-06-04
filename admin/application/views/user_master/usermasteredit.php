<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// Safe session & customer id
$session = $this->session->userdata('company1') ?? [];
$customerId = $session['customer_id'] ?? ($session['company_id'] ?? 1);
$user_group_id = $session['user_group_id'] ?? null;

// Ensure model loaded and fetch rights (safe)
if (!isset($this->master)) {
    $this->load->model('Master_model', 'master', true);
}
$userRights = $this->master->getUserAuthentication($user_group_id);

// Fetch user safely
$acat = $this->db->query("SELECT * FROM user_master WHERE um_id = ?", [$id])->row();
if (!$acat) {
    echo '<div class="alert alert-danger">User not found.</div>';
    return;
}

// Fetch the user's current group safely
$group = $this->db->query("SELECT * FROM user_group WHERE ug_id = ?", [$acat->um_user_group_id])->row();

// Get available user groups for dropdown (only active for this customer)
$groups_q = $this->db->query("SELECT * FROM user_group WHERE ug_customer_id = ? AND ug_status = 1 ORDER BY ug_name ASC", [$customerId]);
$groups = $groups_q->result();

// Determine selected status
$current_status = isset($acat->um_status) ? intval($acat->um_status) : 1;
?>

<div class="content-wrapper">
  <div class="container-full">
    <div class="content-header">
      <div class="d-flex align-items-center">
        <div class="me-auto">
          <h3 class="page-title">Edit User</h3>
          <nav>
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="<?= base_url('master/usermaster') ?>"><i class="mdi mdi-home-outline"> User</i></a></li>
              <li class="breadcrumb-item active">Edit User</li>
            </ol>
          </nav>
        </div>
      </div>
    </div>

    <section class="content">
      <div class="row">
        <div class="col-lg-12 col-12">
          <div class="box">
            <div class="box-header with-border">
              <h4 class="box-title">User Details</h4>
            </div>

            <?php if (validation_errors()): ?>
              <div class="alert alert-danger"><?php echo validation_errors(); ?></div>
            <?php endif; ?>

            <form class="form needs-validation" id="submitForm" name="editform" action="<?php echo base_url('master/usermasteredit/'.$id); ?>" method="POST" novalidate>
              <div class="box-body">
                <div class="row">

                  <!-- User Group -->
                  <div class="col-lg-4 col-md-4 col-sm-6">
                    <div class="form-group">
                      <label class="form-label required">User Group</label>

                      <?php if ($group && ($group->ug_name === 'Super Admin' || $group->ug_name === 'Admin')): ?>
                        <input type="text" class="form-control" value="<?= html_escape($group->ug_name) ?>" disabled>
                        <input type="hidden" name="usergroup" value="<?= (int)$acat->um_user_group_id ?>">
                      <?php else: ?>
                        <select required name="usergroup" id="usergroup" class="form-select form-control">
                          <option value="">Select</option>
                          <?php foreach ($groups as $g): 
                            $sel = ($acat->um_user_group_id == $g->ug_id) ? 'selected' : '';
                          ?>
                            <option value="<?= (int)$g->ug_id ?>" <?= $sel ?>><?= html_escape($g->ug_name) ?></option>
                          <?php endforeach; ?>
                        </select>
                      <?php endif; ?>

                    </div>
                  </div>

                  <!-- Username -->
                  <div class="col-lg-4 col-md-4 col-sm-6">
                    <div class="form-group">
                      <label class="form-label required">User Name</label>
                      <input type="text" name="username" value="<?= html_escape($acat->um_username) ?>"
                             onblur="CheckUserName(this.value,'<?= (int)$id ?>')" id="username" class="form-control" placeholder="User Name" required>
                    </div>
                  </div>

                  <!-- Password (optional on edit) -->
                  <div class="col-lg-4 col-md-4 col-sm-6">
                    <div class="form-group">
                      <label class="form-label">Password <small class="text-muted">(leave blank to keep current)</small></label>
                      <div class="input-group">
                        <input type="password" name="password1" id="password1" class="form-control" placeholder="Password">
                        <div class="input-group-append">
                          <span class="input-group-text" onclick="togglePasswordWithTimeout('password1', this)" style="cursor:pointer">
                            <i class="fa fa-eye"></i>
                          </span>
                        </div>
                      </div>
                    </div>
                  </div>

                  <!-- Confirm Password -->
                  <div class="col-lg-4 col-md-4 col-sm-6">
                    <div class="form-group">
                      <label class="form-label">Confirm Password</label>
                      <div class="input-group">
                        <input type="password" name="password2" id="password2" class="form-control" placeholder="Confirm Password">
                        <div class="input-group-append">
                          <span class="input-group-text" onclick="togglePasswordWithTimeout('password2', this)" style="cursor:pointer">
                            <i class="fa fa-eye"></i>
                          </span>
                        </div>
                      </div>
                    </div>
                  </div>

                  <!-- Status -->
                  <div class="col-lg-4 col-md-4 col-sm-6">
                    <div class="form-group">
                      <label class="form-label">Status</label>
                      <select class="form-select form-control" name="status" id="status">
                        <option value="1" <?= $current_status === 1 ? 'selected' : '' ?>>Active</option>
                        <option value="0" <?= $current_status === 0 ? 'selected' : '' ?>>In-Active</option>
                      </select>
                    </div>
                  </div>

                </div>
              </div>

              <div class="box-footer d-flex justify-content-end">
                <?php if (isset($userRights[1045]) && isset($userRights[1045]->ur_edit) && $userRights[1045]->ur_edit == 1) : ?>
                  <button type="submit" id="btnSubmit" class="btn btn-success">
                    <i class="ti-save-alt"></i> Save
                  </button>
                <?php else: ?>
                  <div class="alert alert-warning small">You do not have permission to edit users.</div>
                <?php endif; ?>
              </div>
            </form>

          </div>
        </div>
      </div>
    </section>
  </div>
</div>

<!-- password visibility helper -->
<script>
function togglePasswordWithTimeout(inputId, element) {
    const input = document.getElementById(inputId);
    const icon = element.querySelector('i');

    if (input.timeoutId) {
        clearTimeout(input.timeoutId);
    }

    if (input.type === "password") {
        input.type = "text";
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
        input.timeoutId = setTimeout(() => {
            input.type = "password";
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        }, 2000);
    } else {
        input.type = "password";
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
    }
}
</script>

<!-- form validation: only require password fields if one is filled -->
<script>
(function () {
    'use strict';
    var form = document.getElementById('submitForm');

    form.addEventListener('submit', function (event) {
        // if passwords entered then they must match
        var p1 = document.getElementById('password1').value;
        var p2 = document.getElementById('password2').value;
        if ((p1 !== '' || p2 !== '') && p1 !== p2) {
            event.preventDefault();
            event.stopPropagation();
            alert('Passwords do not match');
            return false;
        }

        if (!form.checkValidity()) {
            event.preventDefault();
            event.stopPropagation();
        }
        form.classList.add('was-validated');
    }, false);
})();
</script>

<!-- small helper to clear confirm if mismatch while typing -->
<script>
function CheckPassword() {
    var password1 = document.getElementById('password1').value;
    var password2 = document.getElementById('password2').value;
    if (password1 !== password2) {
        // don't clear automatically to avoid losing user input, but warn visually
        // you could uncomment next line to clear confirm field:
        // document.getElementById('password2').value = '';
    }
}
</script>
