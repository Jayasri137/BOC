<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php
$ci = &get_instance();
$company1 = $ci->session->userdata('company1') ?? [];
$user_group_id = is_array($company1) && isset($company1['user_group_id']) ? (int)$company1['user_group_id'] : 0;
$is_admin_flag = isset($company1['is_admin']) && $company1['is_admin'] == true;

/**
 * Local permission helper (same logic as controller)
 */
function has_permission_local($menuNames, $action = 'view', $user_group_id = 0, $is_admin_flag = false, $ci_obj = null)
{
    if ($is_admin_flag) return true;
    if (!$ci_obj) $ci_obj = &get_instance();
    if (!is_array($menuNames)) $menuNames = [$menuNames];
    if ($user_group_id <= 0) return false;

    $col = 'ur_view';
    if (strtolower($action) === 'add') $col = 'ur_add';
    if (strtolower($action) === 'edit') $col = 'ur_edit';

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

$menuNames = ['Lead','Leads','lead'];
$canView = has_permission_local($menuNames, 'view', $user_group_id, $is_admin_flag, $ci);
$canAdd  = has_permission_local($menuNames, 'add', $user_group_id, $is_admin_flag, $ci);
$canEdit = has_permission_local($menuNames, 'edit', $user_group_id, $is_admin_flag, $ci);

// deny if no view
if (!$canView) {
    echo '<div class="alert alert-danger p-3">You do not have permission to view leads.</div>';
    return;
}

// Query with join to get enquiry_type name
$ci->db->select('lead.*, enquiry_type.enquiry_type as enquiry_type_name');
$ci->db->from('lead');
$ci->db->join('enquiry_type', 'lead.enquiry_type_id = enquiry_type.id', 'left');
$ci->db->order_by('lead.created_at', 'DESC');
$leads = $ci->db->get()->result();

// Alternative: use $leads passed by controller if it already has the join
if (isset($leads) && !empty($leads) && property_exists($leads[0], 'enquiry_type_name')) {
    // $leads already has the joined data from controller
} else {
    // Use our query above
}
?>

<div class="content-wrapper">
  <div class="container-full">
    <div class="content-header">
      <div class="d-flex align-items-center">
        <div class="me-auto">
          <h3 class="page-title">Leads</h3>
          <nav>
            <ol class="breadcrumb">
              <li class="breadcrumb-item">
                <a href="<?= base_url('Dashboard'); ?>">
                  <i class="mdi mdi-home-outline"></i> Dashboard
                </a>
              </li>
              <li class="breadcrumb-item active" aria-current="page">Leads</li>
            </ol>
          </nav>
        </div>
      </div>
    </div>

    <div class="col-12">
      <div class="box">
        <div class="box-header d-flex justify-content-between align-items-center">
          <h4 class="box-title">Lead List</h4>
        </div>

        <div class="box-body">
          <div class="table-responsive">
            <table id="example1" class="table table-bordered table-striped editable-table">
              <thead>
                <tr>
                  <th>S.No</th>
                  <th>Name</th>
                  <th>Mobile Number</th>
                  <th>Email</th>
                  <th>Country</th>
                  <th>Source Of Enquiry</th> <!-- Changed column header -->
                  <th>Assigned TO</th>
                  <th>Status</th>
                  <th class="text-center">Actions</th>
                </tr>
              </thead>
              <tbody>
                <?php if (!empty($leads)): $i = 1; ?>
                  <?php foreach ($leads as $lead): ?>
                    <tr>
                      <td class="text-center"><?= $i++ ?></td>
                      <td class="text-start"><?= htmlspecialchars($lead->name ?? '-') ?></td>
                      <td class="text-start"><?= htmlspecialchars($lead->mobile ?? '-') ?></td>
                      <td class="text-start"><?= htmlspecialchars($lead->email ?? '-') ?></td>
                      <td class="text-start"><?= htmlspecialchars($lead->country ?? '-') ?></td>
                      <td class="text-start">
                        <?= htmlspecialchars($lead->enquiry_type_name ?? 
                                           ($lead->source_of_enquiry ?? 
                                           ($lead->enquiry_type_id ?? '-'))) ?>
                      </td>
                      <td class="text-start"><?= htmlspecialchars($lead->role_name ?? ($lead->assigned_to ? 'Assigned' : 'Not Assigned')) ?></td>
                      <td class="text-center">
                        <span class="badge <?= 
                          ($lead->status == 'Open' ? 'bg-warning' : 
                          ($lead->status == 'Hot' ? 'bg-danger' : 
                          ($lead->status == 'Warm' ? 'bg-info' : 
                          ($lead->status == 'Cold' ? 'bg-secondary' : 
                          ($lead->status == 'Registered' ? 'bg-success' : 'bg-dark')))) )
                        ?>">
                          <?= htmlspecialchars($lead->status ?? 'Open') ?>
                        </span>
                      </td>
                      <td>
                        <div class="d-flex flex-nowrap justify-content-center gap-1">
                          <?php if ($canEdit): ?>
                            <a href="<?= base_url('lead/view/'.$lead->id); ?>" class="btn btn-warning btn-sm d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;" title="View">
                              <i class="fa fa-eye fa-xs"></i>
                            </a>
                          <?php else: ?>
                            <button class="btn btn-secondary btn-sm d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;" disabled title="No permission">
                              <i class="fa fa-eye fa-xs"></i>
                            </button>
                          <?php endif; ?>

                          <?php if ($canEdit): ?>
                            <a href="<?= base_url('lead/edit/'.$lead->id); ?>" class="btn btn-info btn-sm d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;" title="Edit">
                              <i class="fa fa-edit fa-xs"></i>
                            </a>
                          <?php else: ?>
                            <button class="btn btn-secondary btn-sm d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;" disabled title="No permission">
                              <i class="fa fa-edit fa-xs"></i>
                            </button>
                          <?php endif; ?>
                          
                          <?php if ($canEdit): ?>
                            <a href="<?= base_url('lead/delete/'.$lead->id); ?>" class="btn btn-danger btn-sm d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;" onclick="return confirm('Are you sure you want to delete this lead?');" title="Delete">
                              <i class="fa fa-trash fa-xs"></i>
                            </a>
                          <?php else: ?>
                            <button class="btn btn-secondary btn-sm d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;" disabled title="No permission">
                              <i class="fa fa-trash fa-xs"></i>
                            </button>
                          <?php endif; ?>
                        </div>
                      </td>
                    </tr>
                  <?php endforeach; ?>
                <?php else: ?>
                  <tr>
                    <td colspan="9" class="text-center text-muted">No leads found.</td>
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

<!-- DataTables Initialization -->
<script>
$(document).ready(function() {
    $('#example1').DataTable({
        pageLength: 10,
        order: [[0, 'asc']], // S.No column
        columnDefs: [
            { targets: 0, className: "text-center" },
            { targets: [1,2,3,4,5,6], className: "text-start" },
            { targets: 7, className: "text-center" },
            { targets: 8, className: "text-center" }
        ],
        language: { 
            searchPlaceholder: "Search...",
            paginate: {
                next: 'Next',
                previous: 'Previous'
            }
        }
    });
});

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
</script>