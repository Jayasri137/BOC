<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php $roles = $this->db->query("select * from roles"); ?>

<div class="content-wrapper">
  <div class="container-full">

    <!-- Content Header -->
    <div class="content-header">
      <div class="d-flex align-items-center">
        <div class="me-auto">
          <h3 class="page-title">Role</h3>
          <nav>
            <ol class="breadcrumb">
              <li class="breadcrumb-item">
                <a href="<?= base_url('dashboard'); ?>">
                  <i class="mdi mdi-home-outline"></i> Dashboard
                </a>
              </li>
              <li class="breadcrumb-item active">Role</li>
            </ol>
          </nav>
        </div>
      </div>
    </div>

    

    <!-- Main Content -->
    <div class="col-12">
      <div class="box">
        <div class="box-header d-flex justify-content-between align-items-center">
          <h4 class="box-title">Role List</h4>

          <div>
            <a href="<?= base_url('master/create_role'); ?>" class="btn btn-primary">
              <i class="fa fa-plus-circle me-1"></i> Add Role
            </a>
          </div>
        </div>

        <div class="box-body">
          <div class="table-responsive">
            <table id="example1" class="table table-bordered table-striped">
              <thead>
                <tr>
                  <th>S.no</th>
                  <th>Role name</th>
                  <th>Status</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody class="text-center">
  <?php if ($roles && $roles->num_rows() > 0): ?>
    <?php $i = 1; foreach ($roles->result() as $row): ?>
      <tr>
        <td><?= $i++; ?></td>
        <td><?= htmlspecialchars($row->role_name ?? '-'); ?></td>
        <td>
          <?php if ($row->status == 1): ?>
            <span class="badge bg-success">Active</span>
          <?php else: ?>
            <span class="badge bg-danger">Inactive</span>
          <?php endif; ?>
        </td>

        <!-- ✅ Actions -->
        <td>
          <a href="<?= base_url('master/edit_role/'.$row->id); ?>" class="btn btn-info btn-sm" title="Edit">
            <i class="fa fa-edit"></i>
          </a>
          <a href="<?= base_url('master/delete_role/'.$row->id); ?>"
             class="btn btn-danger btn-sm"
             onclick="return confirm('Are you sure you want to delete this role?');"
             title="Delete">
            <i class="fa fa-trash"></i>
          </a>
        </td>
      </tr>
    <?php endforeach; ?>
  <?php else: ?>
    <tr>
      <td colspan="4" class="text-center text-muted">No roles found.</td>
    </tr>
  <?php endif; ?>
</tbody>


            </table>
          </div>
        </div>
      </div>
    </div>

  </div>
</div>
<!-- DataTables Initialization -->
<script>
$(document).ready(function () {
  $('#example1').DataTable({
    pageLength: 10,
    order: [[0, 'asc']],
    columnDefs: [
      { targets: [0,2,3], className: 'text-start' },
      { targets: [1,4,5,6,7,8], className: 'text-center' },
      { targets: 8, orderable: false }
    ],
    language: { search: "", searchPlaceholder: "Search..." },
    responsive: true,
    autoWidth: false
  });
});
</script>


