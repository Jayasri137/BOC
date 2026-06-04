<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php
// Fetch all universities with country names
$ci = &get_instance();
$universities = $ci->db->query("
    SELECT u.*, c.name as country_name 
    FROM university u 
    LEFT JOIN country c ON c.id = u.country_id 
    ORDER BY u.id DESC
");

// session & rights info
$company1 = $ci->session->userdata('company1') ?? [];
$user_group_id = is_array($company1) && isset($company1['user_group_id']) ? (int)$company1['user_group_id'] : 0;
$is_admin_flag = isset($company1['is_admin']) && $company1['is_admin'] == true;

/**
 * has_permission_by_menu_name
 * Lightweight view-level permission helper.
 * Adjust menu names below to match your menu_master.mm_name values if needed.
 */
function has_permission_by_menu_name($menuNames, $action = 'view', $user_group_id = 0, $is_admin_flag = false, $ci_obj = null)
{
    if ($is_admin_flag) return true;
    if (!$ci_obj) $ci_obj = &get_instance();
    if (!is_array($menuNames)) $menuNames = [$menuNames];
    if ($user_group_id <= 0) return false;

    $col = 'ur_view';
    if (strtolower($action) === 'add')    $col = 'ur_add';
    if (strtolower($action) === 'edit')   $col = 'ur_edit';
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

// Adjust these labels if your menu_master names are different
$menuLabels = ['University', 'university', 'Universities'];

$canAdd    = has_permission_by_menu_name($menuLabels, 'add',    $user_group_id, $is_admin_flag, $ci);
$canEdit   = has_permission_by_menu_name($menuLabels, 'edit',   $user_group_id, $is_admin_flag, $ci);
$canDelete = has_permission_by_menu_name($menuLabels, 'delete', $user_group_id, $is_admin_flag, $ci);
?>

<div class="content-wrapper">
  <div class="container-full">

    <!-- Content Header -->
    <div class="content-header">
      <div class="d-flex align-items-center">
        <div class="me-auto">
          <h3 class="page-title">University</h3>
          <nav>
            <ol class="breadcrumb">
              <li class="breadcrumb-item">
                <a href="<?= base_url('dashboard'); ?>">
                  <i class="mdi mdi-home-outline"></i> Dashboard
                </a>
              </li>
              <li class="breadcrumb-item active">University</li>
            </ol>
          </nav>
        </div>
      </div>
    </div>

    <!-- Main Content -->
    <div class="col-12">
      <div class="box">
        <div class="box-header d-flex justify-content-between align-items-center">
          <h4 class="box-title">University List</h4>

          <div>
            <?php if ($canAdd): ?>
              <a href="<?= base_url('master/create_university'); ?>" class="btn btn-primary">
                <i class="fa fa-plus-circle me-1"></i> Add University
              </a>
            <?php else: ?>
              <button class="btn btn-primary" disabled title="No permission to add university">
                <i class="fa fa-plus-circle me-1"></i> Add University
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
                  <th>University Name</th>
                  <th>Country</th>
                  <th>Status</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody class="text-center">
                <?php if ($universities && $universities->num_rows() > 0): ?>
                  <?php $i = 1; foreach ($universities->result() as $row): ?>
                    <tr>
                      <td><?= $i++ ?></td>
                      <td class="text-start"><?= htmlspecialchars($row->name ?? '-'); ?></td>
                      <td class="text-start"><?= htmlspecialchars($row->country_name ?? '-'); ?></td>
                      <td>
                        <?php if (strtolower($row->status) === 'active' || $row->status == 1): ?>
                          <span class="badge bg-success">Active</span>
                        <?php else: ?>
                          <span class="badge bg-danger">Inactive</span>
                        <?php endif; ?>
                      </td>
                      <td>
                        <?php if ($canEdit): ?>
                          <a href="<?= base_url('master/edit_university/'.$row->id); ?>" class="btn btn-dark btn-sm" title="Edit">
                            <i class="fa fa-edit"></i>
                          </a>
                        <?php else: ?>
                          <button class="btn btn-secondary btn-sm" disabled title="No permission to edit">
                            <i class="fa fa-edit"></i>
                          </button>
                        <?php endif; ?>

                        <?php if ($canEdit): ?>
                          <a href="<?= base_url('master/delete_university/'.$row->id); ?>"
                             class="btn btn-danger btn-sm"
                             onclick="return confirm('Are you sure you want to delete this university?');"
                             title="Delete">
                            <i class="fa fa-trash"></i>
                          </a>
                        <?php else: ?>
                          <button class="btn btn-secondary btn-sm" disabled title="No permission to delete">
                            <i class="fa fa-trash"></i>
                          </button>
                        <?php endif; ?>
                      </td>
                    </tr>
                  <?php endforeach; ?>
                <?php else: ?>
                  <tr>
                    <td colspan="5" class="text-center">No universities found.</td>
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
});
</script>

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