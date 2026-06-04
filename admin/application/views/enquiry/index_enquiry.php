<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php
$enquiry = $this->db->order_by('created_at', 'DESC')->get('enquiry');
$enquiry_types = $this->db->get_where('enquiry_type', ['status' => 'Active']);

// permission helper (view-level)
$ci = &get_instance();
$company1 = $ci->session->userdata('company1') ?? [];
$user_group_id = is_array($company1) && isset($company1['user_group_id']) ? (int)$company1['user_group_id'] : 0;
$is_admin_flag = isset($company1['is_admin']) && $company1['is_admin'] == true;

function has_permission_by_menu_name($menuNames, $action = 'view', $user_group_id = 0, $is_admin_flag = false, $ci_obj = null)
{
    if ($is_admin_flag) return true;
    if (!$ci_obj) $ci_obj = &get_instance();
    if (!is_array($menuNames)) $menuNames = [$menuNames];
    if ($user_group_id <= 0) return false;

    $col = 'ur_view';
    if (strtolower($action) === 'add')  $col = 'ur_add';
    if (strtolower($action) === 'edit') $col = 'ur_edit';
    if (strtolower($action) === 'delete') $col = 'ur_delete';

    $ci_obj->db->select("ur.{$col} as perm");
    $ci_obj->db->from('user_rights ur');
    $ci_obj->db->join('menu_master mm', 'ur.ur_menu_master_id = mm.mm_id', 'inner');
    $ci_obj->db->where('ur.ur_user_group_id', $user_group_id);
    $ci_obj->db->where('ur.ur_status', 1);

    $ci_obj->db->group_start();
    $first = true;
    foreach ($menuNames as $m) {
        if ($first) { $ci_obj->db->like('mm.mm_name', $m); $first = false; }
        else { $ci_obj->db->or_like('mm.mm_name', $m); }
    }
    $ci_obj->db->group_end();
    $ci_obj->db->limit(1);

    $row = $ci_obj->db->get()->row();
    if (!$row) return false;
    return ((int)$row->perm) === 1;
}

// Decide permissions used in this view
// Add Enquiry button is controlled by 'Enquiry' (add)
$canAddEnquiry = has_permission_by_menu_name(['Enquiry'], 'add', $user_group_id, $is_admin_flag, $ci);
// Move to Lead requires permission to add a Lead OR edit Enquiry
$canAddLead = has_permission_by_menu_name(['Lead'], 'add', $user_group_id, $is_admin_flag, $ci);
$canEditEnquiry = has_permission_by_menu_name(['Enquiry'], 'edit', $user_group_id, $is_admin_flag, $ci);
// We'll allow MoveToLead if either is true
$canMoveToLead = ($canAddLead || $canEditEnquiry);
?>

<div class="content-wrapper">
  <div class="container-full">
    <!-- Content Header -->
    <div class="content-header">
      <div class="d-flex align-items-center">
        <div class="me-auto">
          <h3 class="page-title">Enquiry</h3>
          <nav>
            <ol class="breadcrumb">
              <li class="breadcrumb-item">
                <a href="<?= base_url('dashboard'); ?>">
                  <i class="mdi mdi-home-outline"></i> Dashboard
                </a>
              </li>
              <li class="breadcrumb-item active">Enquiry</li>
            </ol>
          </nav>
        </div>
      </div>
    </div>

    <!-- Flashdata holder for Toast -->
    <div id="flash-data"
        data-success="<?= htmlspecialchars($this->session->flashdata('success') ?? '') ?>"
        data-error="<?= htmlspecialchars($this->session->flashdata('error') ?? '') ?>"
        data-warning="<?= htmlspecialchars($this->session->flashdata('warning') ?? '') ?>">
    </div>

    <!-- Main Content -->
    <div class="col-12">
      <div class="box">
        <div class="box-header d-flex justify-content-between align-items-center">
          <h4 class="box-title">Enquiry List</h4>

          <!-- Add Enquiry button -->
          <div>
            <?php if ($canAddEnquiry): ?>
              <a href="<?= base_url('enquiry/create_enquiry'); ?>" class="btn btn-primary">
                <i class="fa fa-plus-circle me-1"></i> Add Enquiry
              </a>
            <?php else: ?>
              <button class="btn btn-primary" disabled title="You don't have permission to add enquiry">
                <i class="fa fa-plus-circle me-1"></i> Add Enquiry
              </button>
            <?php endif; ?>


            <?php if ($canEditEnquiry): ?>
               <button type="button" class="btn btn-success ms-2" data-bs-toggle="modal" data-bs-target="#bulkUploadModal">
              <i class="fa fa-upload me-1"></i> Bulk Upload
            </button>
            <?php else: ?>
              <button class="btn btn-success ms-2" disabled title="You don't have permission to add enquiry">
                 <i class="fa fa-upload me-1"></i> Bulk Upload
              </button>
            <?php endif; ?>
          </div>
        </div>

        <div class="box-body">
          <div class="table-wrapper">
            <table id="example1" class="table table-bordered table-striped">
              <thead>
                <tr>
                  <th>S.No</th>
                  <th>Name</th>
                  <th>Mobile Number</th>
                  <th>Email</th>
                  <th>Country</th>
                  <th>Source of Enquiry</th>
                  <th>Status</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody class="text-center">
                <?php if ($enquiry && $enquiry->num_rows() > 0): ?>
                  <?php $i = 1; ?>
                  <?php foreach ($enquiry->result() as $row): ?>
                    <?php 
                      $enquiry_type_data = $this->db->get_where('enquiry_type', ['id' => $row->enquiry_type_id])->row();
                      $enquiry_type_name = $enquiry_type_data->enquiry_type ?? '-';
                      
                      $country_data = $this->db->get_where('country', ['id' => $row->country_id])->row();
                      $country_name = $country_data->name ?? '-';
                    ?>
                    <tr id="enquiry-row-<?= $row->id ?>">
                      <td><?= $i++ ?></td>
                      <td class="text-start"><?= htmlspecialchars($row->first_name ?? '-') ?></td>
                      <td><?= htmlspecialchars($row->mobile ?? '-') ?></td>
                      <td><?= htmlspecialchars($row->email ?? '-') ?></td>
                      <td><?= htmlspecialchars($country_name) ?></td>
                      <td><?= htmlspecialchars($enquiry_type_name) ?></td>
                      <td>
                        <?php if($row->status == 'moved_to_lead'): ?>
                          <span class="badge bg-success">Moved to Lead</span>
                        <?php else: ?>
                          <?php if ($canEditEnquiry): ?>
                            <a href="<?= base_url('enquiry/move_to_lead/'.$row->id); ?>" 
                              class="btn btn-success btn-sm move-to-lead" 
                              onclick="return confirm('Are you sure you want to move this enquiry to Lead?');" 
                              title="Move to Lead">
                              <i class="fa fa-exchange-alt"></i> Move to Lead
                            </a>
                          <?php else: ?>
                            <button class="btn btn-secondary btn-sm" disabled title="No permission to move to lead">
                              <i class="fa fa-exchange-alt"></i> Move to Lead
                            </button>
                          <?php endif; ?>
                        <?php endif; ?>
                      </td>
                      <td>
                         <?php if ($canEditEnquiry): ?>
                        <a href="<?= base_url('enquiry/view_enquiry/'.$row->id); ?>" class="btn btn-warning btn-sm" title="View">
                          <i class="fa fa-eye"></i>
                        </a>
                        <?php else: ?>
                          <button class="btn btn-secondary btn-sm" disabled title="No permission to View"><i class="fa fa-eye"></i></button>
                          <?php endif; ?>


                        <?php if ($canEditEnquiry): ?>
                          <a href="<?= base_url('enquiry/edit_enquiry/'.$row->id); ?>" class="btn btn-info btn-sm" title="Edit">
                            <i class="fa fa-edit"></i>
                          </a>
                        <?php else: ?>
                          <button class="btn btn-secondary btn-sm" disabled title="No permission to edit"><i class="fa fa-edit"></i></button>
                        <?php endif; ?>

                        <?php 
                          // delete action uses edit permission in many of your modules - adjust if you have dedicated 'delete' right 
                        ?>
                        <?php if ($canEditEnquiry): ?>
                          <a href="<?= base_url('enquiry/delete/'.$row->id); ?>" class="btn btn-danger btn-sm"
                            onclick="return confirm('Are you sure you want to delete this enquiry?');" title="Delete">
                            <i class="fa fa-trash"></i>
                          </a>
                        <?php else: ?>
                          <button class="btn btn-secondary btn-sm" disabled title="No permission to delete"><i class="fa fa-trash"></i></button>
                        <?php endif; ?>
                      </td>
                    </tr>
                  <?php endforeach; ?>
                <?php else: ?>
                  <tr>
                    <td colspan="8" class="text-center">No enquiries found.</td>
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

<!-- NEW: Bulk Upload Modal -->
<div class="modal fade" id="bulkUploadModal" tabindex="-1" aria-labelledby="bulkUploadModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form action="<?= base_url('enquiry/import'); ?>" method="post" enctype="multipart/form-data">
        <div class="modal-header">
          <h5 class="modal-title" id="bulkUploadModalLabel">Bulk Upload Enquiries (CSV / XLS / XLSX)</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <?php if ($this->session->flashdata('error')): ?>
            <div class="alert alert-danger"><?= $this->session->flashdata('error') ?></div>
          <?php endif; ?>

          <?php if ($this->session->flashdata('success')): ?>
            <div class="alert alert-success"><?= $this->session->flashdata('success') ?></div>
          <?php endif; ?>

          <div class="mb-3">
            <label for="file" class="form-label">Choose file</label>
            <input type="file" name="file" id="file" accept=".csv,.xls,.xlsx" class="form-control" required>
          </div>

          <p class="small">
            <strong>Note:</strong> First row should contain headers. Allowed file types: <code>.csv</code>, <code>.xls</code>, <code>.xlsx</code>.
            The import will map common headers (e.g. <code>phone</code>→<code>mobile</code>, <code>name</code>→<code>first_name</code>).
          </p>

          <p class="small">Download sample file: 
            <a href="<?= base_url('assets/sample_enquiry_upload.csv'); ?>" target="_blank">sample_enquiry_upload.csv</a>
            &nbsp;|&nbsp; <a href="<?= base_url('assets/sample_enquiry_upload.xlsx'); ?>" target="_blank">sample_enquiry_upload.xlsx</a>
          </p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-success">Upload & Import</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- keep your existing toast, styles and scripts below (unchanged) -->


  <!-- Toast Notification -->
  <div id="global-toast" class="global-toast hidden" role="status" aria-live="polite">
    <div class="gt-body">
      <div class="gt-icon" id="gt-icon"></div>
      <div class="gt-content">
        <div id="gt-title" class="gt-title"></div>
        <div id="gt-progress" class="gt-progress"><div id="gt-progress-bar" class="gt-progress-bar"></div></div>
      </div>
    </div>
  </div>

  <style>
  .table-responsive { display: none; }
  .table-wrapper { width: 100%; overflow: visible !important; }
  #example1 { width: 100% !important; table-layout: auto; }
  .box-body { overflow: visible !important; width: 100%; }
  .box { overflow: visible !important; }
  .container-full { overflow: visible !important; }
  .content-wrapper { overflow: visible !important; }
  body, html { overflow-x: visible !important; }
  .dataTables_wrapper { overflow: visible !important; width: 100% !important; }
  .dataTables_scroll { display: none !important; }
  .dataTables_scrollBody { overflow: visible !important; }
  .table { margin-bottom: 0; }
  .table th, .table td { white-space: nowrap; padding: 8px 12px; }
  .badge { font-size: 0.75em; padding: 0.35em 0.65em; }
  .btn-sm { padding: 0.35rem 0.55rem; font-size: 12px; }
  #example1_wrapper .dataTables_scroll { display: none; }
  #example1_wrapper .dataTables_scrollHead,
  #example1_wrapper .dataTables_scrollBody { overflow: visible !important; }
  </style>

  <script>
  // Toast Notification Script
  (function(){
    var holder = document.getElementById('flash-data');
    if (!holder) return;
    var success = holder.getAttribute('data-success') || '';
    var error = holder.getAttribute('data-error') || '';
    var warning = holder.getAttribute('data-warning') || '';
    var message = '', type = '';
    if (success) { message = success; type = 'success'; }
    else if (error) { message = error; type = 'error'; }
    else if (warning) { message = warning; type = 'warning'; }
    if (!message) return;

    var toast = document.getElementById('global-toast');
    var icon = document.getElementById('gt-icon');
    var title = document.getElementById('gt-title');
    var progress = document.getElementById('gt-progress-bar');

    var icons = {
      success: '<svg width="20" height="20" viewBox="0 0 24 24"><path d="M20 6L9 17l-5-5" stroke="white" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/></svg>',
      error: '<svg width="20" height="20" viewBox="0 0 24 24"><path d="M18 6L6 18M6 6l12 12" stroke="white" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/></svg>',
      warning: '<svg width="20" height="20" viewBox="0 0 24 24"><path d="M12 8v4M12 16h.01" stroke="white" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/></svg>'
    };

    var classes = { success: 'gt-success', error: 'gt-error', warning: 'gt-warning' };

    icon.className = 'gt-icon ' + (classes[type] || '');
    icon.innerHTML = icons[type] || '';
    title.textContent = message;

    toast.classList.remove('hidden');
    void toast.offsetWidth;
    toast.classList.add('show');

    var duration = 3200;
    var start = Date.now();
    function tick() {
      var elapsed = Date.now() - start;
      var pct = Math.max(0, 1 - elapsed / duration);
      progress.style.transform = 'scaleX(' + pct + ')';
      if (elapsed < duration) requestAnimationFrame(tick);
      else { toast.classList.remove('show'); setTimeout(function(){ toast.classList.add('hidden'); }, 260); }
    }
    progress.style.transformOrigin = 'left center';
    tick();

    toast.addEventListener('click', function(){ toast.classList.remove('show'); setTimeout(function(){ toast.classList.add('hidden'); }, 200); });
  })();

  // DataTables Initialization
  $(document).ready(function () {
    $('#example1').DataTable({
      pageLength: 10,
      order: [[0, 'asc']],
      language: { 
        search: "", 
        searchPlaceholder: "Search...",
        paginate: {
          next: 'Next',
          previous: 'Previous'
        }
      },
      responsive: false,
      autoWidth: true,
      scrollX: false,
      scrollY: false,
      dom: '<"row"<"col-sm-6"l><"col-sm-6"f>>tip',
      scrollCollapse: false
    });

    // If server reported an upload error, open the modal so user can correct and re-upload
    <?php if ($this->session->flashdata('error')): ?>
      var uploadErrors = <?= json_encode($this->session->flashdata('error')); ?>;
      // open modal to show the error (Bootstrap 5)
      var bulkModal = new bootstrap.Modal(document.getElementById('bulkUploadModal'));
      bulkModal.show();
    <?php endif; ?>
  });
  </script>
