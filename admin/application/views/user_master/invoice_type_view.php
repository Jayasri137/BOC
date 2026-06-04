<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="content-wrapper">
  <div class="container-full">
     <!-- Content Header -->
    <div class="content-header">
      <div class="d-flex align-items-center">
        <div class="me-auto"> 
          <h3 class="page-title">Invoice Type</h3>
          <div class="d-inline-block align-items-center">
            <nav>
              <ol class="breadcrumb">
                <li class="breadcrumb-item">
                  <a href="<?= base_url('dashboard'); ?>">
                    <i class="mdi mdi-home-outline"></i> Dashboard
                  </a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">Invoice Type</li>
              </ol>
            </nav>
          </div>
        </div>
      </div>
    </div>
    <div class="content-header d-flex align-items-center">
      <div class="me-auto"><h3 class="page-title">Invoice Type</h3></div>
      <div>
        <a href="<?= site_url('master/create_invoice_type'); ?>" class="btn btn-primary">
          <i class="fa fa-plus-circle me-1"></i> Add Invoice Type
        </a>
      </div>
    </div>

    <div class="box mt-3">
      <div class="box-body">
        <div class="table-responsive">
          <table id="invoiceTypesTable" class="table table-bordered table-striped">
            <thead>
              <tr>
                <th class="text-center">S.No</th>
                <th>Code</th>
                <th>Invoice type</th>
                <th>Description</th>
                <th class="text-center">Status</th>
                <th class="text-center" style="width:120px">Action</th>
              </tr>
            </thead>
            <tbody>
                <!-- Static Sample Data -->
                <tr>
                  <td class="text-center">1</td>
                  <td>John</td>
                  <!-- <td>Doe</td>
                  <td>9876543210</td> -->
                  <td>john.doe@example.com</td>
                  <!-- <td>Manager</td> -->
                  <td>Chennai</td>
                  <td><span class="badge bg-success">Active</span></td>
                  <td class="text-center">
                    <a href="<?= base_url('master/invoice_type/1'); ?>" class="btn btn-info btn-sm" title="View"><i class="fa fa-eye"></i></a>
                    <a href="<?= base_url('master/edit_invoice_type/1'); ?>" class="btn btn-warning btn-sm" title="Edit"><i class="fa fa-edit"></i></a>
                    <button type="button" class="btn btn-danger btn-sm" title="Delete"><i class="fa fa-trash"></i></button>
                  </td>
                </tr>

                <tr>
                  <td class="text-center">2</td>
                  <td>Priya</td>
                  <!-- <td>Kumar</td>
                  <td>9998887776</td> -->
                  <td>priya.k@example.com</td>
                  <!-- <td>Admin</td> -->
                  <td>Bangalore</td>
                  <td><span class="badge bg-warning">Pending</span></td>
                  <td class="text-center">
                    <a href="<?= base_url('master/invoice_type/2'); ?>" class="btn btn-info btn-sm" title="View"><i class="fa fa-eye"></i></a>
                    <a href="<?= base_url('master/edit_invoice_type/2'); ?>" class="btn btn-warning btn-sm" title="Edit"><i class="fa fa-edit"></i></a>
                    <button type="button" class="btn btn-danger btn-sm" title="Delete"><i class="fa fa-trash"></i></button>
                  </td>
                </tr>

                <tr>
                  <td class="text-center">3</td>
                  <td>Ravi</td>
                  <!-- <td>Menon</td>
                  <td>8877665544</td> -->
                  <td>ravi.menon@example.com</td>
                  <!-- <td>Executive</td> -->
                  <td>Hyderabad</td>
                  <td><span class="badge bg-danger">Inactive</span></td>
                  <td class="text-center">
                    <a href="<?= base_url('master/invoice_type/3'); ?>" class="btn btn-info btn-sm" title="View"><i class="fa fa-eye"></i></a>
                    <a href="<?= base_url('master/edit_invoice_type/3'); ?>" class="btn btn-warning btn-sm" title="Edit"><i class="fa fa-edit"></i></a>
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

<script>
$(document).ready(function(){
  $('#invoiceTypesTable').DataTable({
    pageLength: 10,
    order: [[2,'asc']],
    columnDefs: [{ targets:[0,4,5], className:'text-center', orderable:false }]
  });

  $('.btn-delete-type').on('click', function(){
    const id = $(this).data('id');
    if (!confirm('Delete this invoice type?')) return;
    $.post('<?= site_url("master/delete_invoice_type"); ?>', { id: id }, function(res){
      try {
        const j = (typeof res === 'object') ? res : JSON.parse(res);
        if (j.ok) location.reload();
        else alert(j.error || 'Delete failed');
      } catch(e){ console.error(e); alert('Unexpected server response'); }
    }).fail(function(xhr){ alert('Delete failed (HTTP ' + xhr.status + ')'); });
  });
});
</script>
