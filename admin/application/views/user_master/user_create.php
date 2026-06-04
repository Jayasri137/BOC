<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="content-wrapper">
  <div class="container-full">

    <!-- Content Header -->
    <div class="content-header">
      <div class="d-flex align-items-center">
        <div class="me-auto">
          <h3 class="page-title">Add New User</h3>
          <div class="d-inline-block align-items-center">
            <nav>
              <ol class="breadcrumb">
                <li class="breadcrumb-item">
                  <a href="<?= base_url('dashboard'); ?>">
                    <i class="mdi mdi-home-outline"></i> Dashboard
                  </a>
                </li>
                <li class="breadcrumb-item">
                  <a href="<?= base_url('master/user_master'); ?>">User Master</a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">Add User</li>
              </ol>
            </nav>
          </div>
        </div>
      </div>
    </div>

    <!-- Main Form Box -->
    <section class="content">
      <div class="row">
        <div class="col-12">
          <div class="box">
            <div class="box-header with-border d-flex justify-content-between align-items-center">
              <h4 class="box-title mb-0">Create New User</h4>
               <div class="box-controls pull-right">
          <a href="<?= base_url('master/user'); ?>" class="btn btn-primary btn-sm">
            <i class="fa fa-arrow-left"></i> Back
          </a>
        </div>
            </div>

            <div class="box-body">
              <form id="createUserForm" method="post" action="<?= base_url('master/store_user'); ?>" autocomplete="off">

                <div class="row">
                  <!-- First Name -->
                  <div class="col-md-4">
                    <div class="form-group">
                      <label class="required">First Name</label>
                      <input type="text" name="first_name" class="form-control" placeholder="Enter first name" required>
                    </div>
                  </div>

                  <!-- Last Name -->
                  <div class="col-md-4">
                    <div class="form-group">
                      <label>Last Name</label>
                      <input type="text" name="last_name" class="form-control" placeholder="Enter last name">
                    </div>
                  </div>

                  <!-- Mobile -->
                  <div class="col-md-4">
                    <div class="form-group">
                      <label class="required">Mobile Number</label>
                      <input type="text" name="mobile" class="form-control" placeholder="Enter mobile number" maxlength="10" required>
                    </div>
                  </div>

                  <!-- Email -->
                  <div class="col-md-4">
                    <div class="form-group">
                      <label class="required">Email</label>
                      <input type="email" name="email" class="form-control" placeholder="Enter email" required>
                    </div>
                  </div>

                  <!-- Password -->
                  <div class="col-md-4">
                    <div class="form-group">
                      <label class="required">Password</label>
                      <input type="password" name="password" class="form-control" placeholder="Enter password" minlength="6" required>
                    </div>
                  </div>

                  <!-- Role -->
                  <div class="col-md-4">
                    <div class="form-group">
                      <label class="required">Role</label>
                      <select name="role" class="form-control" required>
                        <option value="">Select Role</option>
                        <option value="Admin">Admin</option>
                        <option value="Manager">Manager</option>
                        <option value="Executive">Executive</option>
                        <option value="Staff">Staff</option>
                      </select>
                    </div>
                  </div>

                  <!-- Branch -->
                  <div class="col-md-4">
                    <div class="form-group">
                      <label>Branch</label>
                      <select name="branch" class="form-control">
                        <option value="">Select Branch</option>
                        <option value="Chennai">Chennai</option>
                        <option value="Bangalore">Bangalore</option>
                        <option value="Hyderabad">Hyderabad</option>
                        <option value="Coimbatore">Coimbatore</option>
                      </select>
                    </div>
                  </div>

                  <!-- Status -->
                  <div class="col-md-4">
                    <div class="form-group">
                      <label>Status</label>
                      <select name="status" class="form-control">
                        <option value="Active">Active</option>
                        <option value="Pending">Pending</option>
                        <option value="Inactive">Inactive</option>
                      </select>
                    </div>
                  </div>
                </div>

                <div class="box-footer d-flex justify-content-between mt-3">
                  <a href="<?= base_url('master/user_master'); ?>" class="btn btn-danger">
                    <i class="ti-arrow-left"></i> Cancel
                  </a>
                  <button type="submit" class="btn btn-primary">
                    <i class="ti-save"></i> Save User
                  </button>
                </div>
              </form>
            </div>

          </div>
        </div>
      </div>
    </section>

  </div>
</div>

<!-- Client-side Validation -->
<script>
document.addEventListener("DOMContentLoaded", function () {
  const form = document.getElementById("createUserForm");

  form.addEventListener("submit", function (e) {
    const mobile = form.mobile.value.trim();
    const email = form.email.value.trim();
    let valid = true;

    // Mobile validation
    if (!/^[6-9][0-9]{9}$/.test(mobile)) {
      alert("Please enter a valid 10-digit mobile number starting with 6-9.");
      valid = false;
    }

    // Email validation
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
