<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="content-wrapper">
  <div class="container-full">

    <!-- Content Header -->
    <div class="content-header">
      <div class="d-flex align-items-center">
        <div class="me-auto"> 
          <h3 class="page-title">User Master</h3>
          <div class="d-inline-block align-items-center">
            <nav>
              <ol class="breadcrumb">
                <li class="breadcrumb-item">
                  <a href="<?= base_url('dashboard'); ?>">
                    <i class="mdi mdi-home-outline"></i> Dashboard
                  </a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">Users</li>
              </ol>
            </nav>
          </div>
        </div>
      </div>
    </div>

    <!-- Flashdata holder -->
    <div id="flash-data"
         data-success=""
         data-error=""
         data-warning="">
    </div>

    <!-- Main Content -->
    <div class="col-12">
       <div class="box">
        <div class="box-header d-flex justify-content-between align-items-center">
          <h4 class="box-title">Users List</h4>

          <!-- Add Users button -->
          <div>
            <a href="<?= base_url('master/create_user'); ?>" class="btn btn-primary">
              <i class="fa fa-plus-circle me-1"></i> Add Users
            </a>
          </div>
        </div>

        <div class="box-body">
          <div class="table-responsive">
            <table id="example1" class="table table-bordered table-striped">
              <thead>
                <tr>
                  <th>S.No</th>
                  <th>First Name</th>
                  <th>Last Name</th>
                  <th>Mobile Number</th>
                  <th>Email</th>
                  <th>Role</th>
                  <th>Branch</th>
                  <th>Status</th>
                  <th>Action</th>
                </tr>
              </thead>

              <tbody>
                <!-- Static Sample Data -->
                <tr>
                  <td class="text-center">1</td>
                  <td>John</td>
                  <td>Doe</td>
                  <td>9876543210</td>
                  <td>john.doe@example.com</td>
                  <td>Manager</td>
                  <td>Chennai</td>
                  <td><span class="badge bg-success">Active</span></td>
                  <td class="text-center">
                    <a href="<?= base_url('master/user/1'); ?>" class="btn btn-info btn-sm" title="View"><i class="fa fa-eye"></i></a>
                    <a href="<?= base_url('master/edit_user/1'); ?>" class="btn btn-warning btn-sm" title="Edit"><i class="fa fa-edit"></i></a>
                    <button type="button" class="btn btn-danger btn-sm" title="Delete"><i class="fa fa-trash"></i></button>
                  </td>
                </tr>

                <tr>
                  <td class="text-center">2</td>
                  <td>Priya</td>
                  <td>Kumar</td>
                  <td>9998887776</td>
                  <td>priya.k@example.com</td>
                  <td>Admin</td>
                  <td>Bangalore</td>
                  <td><span class="badge bg-warning">Pending</span></td>
                  <td class="text-center">
                    <a href="<?= base_url('master/user/2'); ?>" class="btn btn-info btn-sm" title="View"><i class="fa fa-eye"></i></a>
                    <a href="<?= base_url('master/edit_user/2'); ?>" class="btn btn-warning btn-sm" title="Edit"><i class="fa fa-edit"></i></a>
                    <button type="button" class="btn btn-danger btn-sm" title="Delete"><i class="fa fa-trash"></i></button>
                  </td>
                </tr>

                <tr>
                  <td class="text-center">3</td>
                  <td>Ravi</td>
                  <td>Menon</td>
                  <td>8877665544</td>
                  <td>ravi.menon@example.com</td>
                  <td>Executive</td>
                  <td>Hyderabad</td>
                  <td><span class="badge bg-danger">Inactive</span></td>
                  <td class="text-center">
                    <a href="<?= base_url('master/user/3'); ?>" class="btn btn-info btn-sm" title="View"><i class="fa fa-eye"></i></a>
                    <a href="<?= base_url('master/edit_user/3'); ?>" class="btn btn-warning btn-sm" title="Edit"><i class="fa fa-edit"></i></a>
                    <button type="button" class="btn btn-danger btn-sm" title="Delete"><i class="fa fa-trash"></i></button>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>

      </div>
    </div>
  </div>
</div>

<!-- Toast -->
<div id="global-toast" class="global-toast hidden" role="status" aria-live="polite">
  <div class="gt-body">
    <div class="gt-icon" id="gt-icon"></div>
    <div class="gt-content">
      <div id="gt-title" class="gt-title"></div>
      <div id="gt-progress" class="gt-progress">
        <div id="gt-progress-bar" class="gt-progress-bar"></div>
      </div>
    </div>
  </div>
</div>

<!-- Styles -->
<style>
.global-toast {
  position: fixed;
  top: 20px;
  right: 20px;
  min-width: 260px;
  z-index: 20000;
  border-radius: 10px;
  background: #fff;
  box-shadow: 0 8px 24px rgba(10,20,40,0.12);
  overflow: hidden;
  opacity: 0;
  transform: translateY(-8px);
  transition: all 240ms ease;
}
.global-toast.show { opacity: 1; transform: translateY(0); }
.gt-body { display:flex; gap:12px; padding:14px; align-items:center; }
.gt-icon { width:44px; height:44px; border-radius:8px; display:flex; align-items:center; justify-content:center; color:#fff; }
.gt-content { flex:1; }
.gt-title { font-weight:600; color:#0f1724; margin-bottom:6px; }
.gt-progress { height:4px; background:rgba(0,0,0,0.06); border-radius:999px; overflow:hidden; }
.gt-progress-bar { height:100%; transform-origin:left; transform:scaleX(1); transition:transform linear; background:rgba(255,255,255,0.9); }
.gt-success { background: linear-gradient(90deg,#10b981,#06b6d4); }
.gt-error { background: linear-gradient(90deg,#ef4444,#f97316); }
.gt-warning { background: linear-gradient(90deg,#f59e0b,#f97316); }
.hidden { display:none; }
.table-responsive { overflow: visible !important; }
</style>

<!-- DataTable Initialization -->
<script>
$(document).ready(function() {
  $('#example1').DataTable({
    pageLength: 10,
    order: [[0, 'asc']],
    columnDefs: [
      { targets: 0, className: "text-center" },
      { targets: [1,2,3,4,5,6], className: "text-start" },
      { targets: 7, className: "text-center", orderable: false }
    ],
    language: { searchPlaceholder: "Search..." }
  });
});
</script>
