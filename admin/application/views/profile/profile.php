<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

    <div class="content-wrapper">
        <div class="container-full">
            <div class="content-header">
                <div class="d-flex align-items-center">
                    <div class="me-auto p-3">
                        <h3 class="page-title">Company Profile</h3>
                        <nav>
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item">
                                    <a href="<?= base_url(); ?>Dashboard">
                                        <i class="mdi mdi-home-outline"></i> Dashboard
                                    </a>
                                </li>
                                <li class="breadcrumb-item active">Company Profile</li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>

            <!-- Flash Messages -->
            <div class="container mt-3">
                <?php if ($this->session->flashdata('success')): ?>
                    <div class="alert alert-success">
                        <?= $this->session->flashdata('success'); ?>
                    </div>
                    <?php endif; ?>
                        <?php if ($this->session->flashdata('error')): ?>
                            <div class="alert alert-danger">
                                <?= $this->session->flashdata('error'); ?>
                            </div>
                            <?php endif; ?>
                                <?php if ($this->session->flashdata('warning')): ?>
                                    <div class="alert alert-warning">
                                        <?= $this->session->flashdata('warning'); ?>
                                    </div>
                                    <?php endif; ?>
            </div>

            <!-- Main Container -->
            <div class="container border mt-5 mb-5 bg-white rounded shadow-sm">
                <div class="p-4">

                    <!-- Header -->
                    <div class="box-typical-header mb-4">
                        <div class="tbl-row">
                            <div class="tbl-cell tbl-cell-title">
                                <h4>Company Information</h4>
                            </div>
                        </div>
                    </div>

                    <div class="profile-wrapper">
                        <div class="profile-header mb-4">
                            <div class="profile-avatar " style="width=100% ;height= auto ;">
    <img src="<?= base_url('assets/images/itime_logo.jpg'); ?>" 
         alt="Company Logo" 
         class="avatar-img"/>
</div>

                            <div class="profile-info">
                                <h4><?= htmlspecialchars($profile->name ?? 'ITIME'); ?></h4>
                                <p class="email text-muted">
                                    <?= htmlspecialchars($profile->email1 ?? 'contact@itime.com'); ?>
                                </p>
                            </div>
                        </div>

                        <!-- Profile Form -->
                        <form id="profileForm" method="post" action="<?= base_url('master/update_profile') ?>">

                            <div class="row mb-4 mt-5">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="required">Company Name</label>
                                        <input type="text" class="form-control" name="name" value="<?= htmlspecialchars($profile->name ?? '') ?>" required>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="required">Primary Email</label>
                                        <input type="email" class="form-control" name="email1" value="<?= htmlspecialchars($profile->email1 ?? '') ?>" required>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="required">Primary Mobile</label>
                                        <input type="text" class="form-control" name="mobile1" value="<?= htmlspecialchars($profile->mobile1 ?? '') ?>" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Username</label>
                                        <input type="text" class="form-control" name="um_username" value="<?= htmlspecialchars($profile->um_username ?? '') ?>" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="required">Password</label>
                                        <input type="password" class="form-control" name="password" id="password" placeholder="Enter new password">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="required">Confirm Password</label>
                                        <input type="password" class="form-control" name="confirm_password" id="confirm_password" placeholder="Confirm password">
                                        <small id="passwordError" class="text-danger mt-1" style="display:none;">
                                    Passwords do not match
                                </small>
                                    </div>
                                </div>
                                <!-- <div class="profile-grid">

                            <div class="form-group">
                                <label class="required">Company Name</label>
                                <input type="text" class="form-control" name="name"
                                       value="<?= htmlspecialchars($profile->name ?? '') ?>" required>
                            </div>

                            <div class="form-group">
                                <label class="required">Primary Email</label>
                                <input type="email" class="form-control" name="email1"
                                       value="<?= htmlspecialchars($profile->email1 ?? '') ?>" required>
                            </div>

                            <div class="form-group">
                                <label class="required">Primary Mobile</label>
                                <input type="text" class="form-control" name="mobile1"
                                       value="<?= htmlspecialchars($profile->mobile1 ?? '') ?>" required>
                            </div>

                            <div class="form-group">
                                <label>Username</label>
                                <input type="text" class="form-control" name="um_username"
                                       value="<?= htmlspecialchars($profile->um_username ?? '') ?>" required>
                            </div>

                            <div class="form-group">
                                <label class="required">Password</label>
                                <input type="password" class="form-control" name="password" id="password"
                                       placeholder="Enter new password">
                            </div>

                            <div class="form-group">
                                <label class="required">Confirm Password</label>
                                <input type="password" class="form-control" name="confirm_password" id="confirm_password"
                                       placeholder="Confirm password">
                                <small id="passwordError" class="text-danger mt-1" style="display:none;">
                                    Passwords do not match
                                </small>
                            </div>
                        </div> -->

                                <div class="box-footer d-flex justify-content-between">
                                    <button type="reset" id="resetBtn" class="btn btn-danger">
                                        <i class="ti-trash"></i> Cancel
                                    </button>
                                    <button type="submit" id="submitBtn" class="btn btn-primary">
                                        <i class="ti-save-alt"></i> Save
                                    </button>
                                </div>
                        </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <!-- Minimal CSS & JS (same as earlier) -->
        <style>
            /* ... paste your existing styles here ... */
            .profile-wrapper { background: #fff; }
            /* (rest omitted for brevity) */
        </style>

        <script>
            document.getElementById("profileForm").addEventListener("submit", function(e) {
                const pass = document.getElementById("password").value.trim();
                const confirm = document.getElementById("confirm_password").value.trim();
                const error = document.getElementById("passwordError");
                if (pass !== "" && pass !== confirm) {
                    e.preventDefault();
                    error.style.display = "block";
                } else {
                    error.style.display = "none";
                }
            });
        </script>