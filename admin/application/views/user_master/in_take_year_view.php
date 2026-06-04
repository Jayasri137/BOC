<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<?php
$ci = &get_instance();

/* ------------------------------------
   FETCH INTAKE YEAR LIST
------------------------------------ */
$intake_years = $ci->db->order_by('created_at', 'DESC')->get('intake_year');

/* ------------------------------------
   SESSION + PERMISSIONS
------------------------------------ */
$company1 = $ci->session->userdata('company1') ?? [];

// normalize user_group_id
$user_group_id = isset($company1['user_group_id']) ? (int)$company1['user_group_id'] : 0;

// normalize is_admin
$is_admin_flag = false;
if (isset($company1['is_admin'])) {
    $val = $company1['is_admin'];
    $is_admin_flag = ($val === true || $val === 1 || $val === '1' || strtolower((string)$val) === 'true');
}

// fallback: if super admin is user_group_id = 1
if ($user_group_id === 1) {
    $is_admin_flag = true;
}

/* ------------------------------------
   PERMISSION FUNCTION
------------------------------------ */
function has_permission_by_menu_name($menuNames, $action = 'view', $user_group_id = 0, $is_admin_flag = false, $ci_obj = null)
{
    if ($is_admin_flag) return true; // super admin bypass

    if (!$ci_obj) $ci_obj = &get_instance();
    if (!is_array($menuNames)) $menuNames = [$menuNames];
    if ($user_group_id <= 0) return false;

    $action = strtolower($action);
    $col = 'ur_view';
    if ($action == 'add')    $col = 'ur_add';
    if ($action == 'edit')   $col = 'ur_edit';
    if ($action == 'delete') $col = 'ur_delete';

    $ci_obj->db->select("ur.$col AS perm");
    $ci_obj->db->from('user_rights ur');
    $ci_obj->db->join('menu_master mm', 'ur.ur_menu_master_id = mm.mm_id', 'inner');
    $ci_obj->db->where('ur.ur_user_group_id', $user_group_id);
    $ci_obj->db->where('ur.ur_status', 1);

    $ci_obj->db->group_start();
    $first = true;
    foreach ($menuNames as $m) {
        $m = trim($m);
        if ($m === '') continue;

        if ($first) {
            $ci_obj->db->like('mm.mm_name', $m);
            $first = false;
        } else {
            $ci_obj->db->or_like('mm.mm_name', $m);
        }
    }
    $ci_obj->db->group_end();

    $ci_obj->db->limit(1);
    $row = $ci_obj->db->get()->row();

    if (!$row) return false;
    return ((int)$row->perm) === 1;
}

/* ------------------------------------
   MENU NAME (MATCH YOUR menu_master TABLE)
------------------------------------ */
$menuLabel = ['Intake Year', 'intake year', 'IntakeYear'];

/* ------------------------------------
   PERMISSIONS
------------------------------------ */
$canView   = has_permission_by_menu_name($menuLabel, 'view',   $user_group_id, $is_admin_flag, $ci);
$canAdd    = has_permission_by_menu_name($menuLabel, 'add',    $user_group_id, $is_admin_flag, $ci);
$canEdit   = has_permission_by_menu_name($menuLabel, 'edit',   $user_group_id, $is_admin_flag, $ci);
$canDelete = has_permission_by_menu_name($menuLabel, 'delete', $user_group_id, $is_admin_flag, $ci);

?>

<!-- ====================== PAGE HTML ====================== -->

<div class="content-wrapper">
  <div class="container-full">

    <div class="content-header">
      <div class="d-flex align-items-center">
        <div class="me-auto">
          <h3 class="page-title">Intake Year</h3>
          <nav>
            <ol class="breadcrumb">
              <li class="breadcrumb-item">
                <a href="<?= base_url('dashboard'); ?>">
                  <i class="mdi mdi-home-outline"></i> Dashboard
                </a>
              </li>
              <li class="breadcrumb-item active">Intake Year</li>
            </ol>
          </nav>
        </div>
      </div>
    </div>

    <!-- Main Content -->
    <div class="col-12">
      <div class="box">

        <!-- Header -->
        <div class="box-header d-flex justify-content-between align-items-center">
          <h4 class="box-title">Intake Year List</h4>

          <div>
            <?php if ($canAdd): ?>
              <a href="<?= base_url('master/create_intake_year'); ?>" class="btn btn-primary">
                <i class="fa fa-plus-circle me-1"></i> Add Intake Year
              </a>
            <?php else: ?>
              <button class="btn btn-primary" disabled title="No permission to add Intake Year">
                <i class="fa fa-plus-circle me-1"></i> Add Intake Year
              </button>
            <?php endif; ?>
          </div>
        </div>

        <!-- Table -->
        <div class="box-body">
          <div class="table-responsive">
            <table id="example1" class="table table-bordered table-striped">
              <thead>
                <tr>
                  <th>S.No</th>
                  <th>Intake Year</th>
                  <th>Status</th>
                  <th>Action</th>
                </tr>
              </thead>

              <tbody class="text-center">
                <?php if ($intake_years->num_rows() > 0): ?>
                  <?php $i = 1; foreach ($intake_years->result() as $row): ?>
                    <tr>
                      <td><?= $i++; ?></td>
                      <td class="text-start"><?= htmlspecialchars($row->intake_year); ?></td>

                      <td>
                        <?php if ($row->status == 'Active'): ?>
                          <span class="badge bg-success">Active</span>
                        <?php elseif ($row->status == 'Pending'): ?>
                          <span class="badge bg-warning">Pending</span>
                        <?php else: ?>
                          <span class="badge bg-danger">Inactive</span>
                        <?php endif; ?>
                      </td>

                      <td>
                        <!-- Edit -->
                        <?php if ($canEdit): ?>
                          <a href="<?= base_url('master/edit_intake_year/'.$row->id); ?>" class="btn btn-dark btn-sm" title="Edit">
                            <i class="fa fa-edit"></i>
                          </a>
                        <?php else: ?>
                          <button class="btn btn-secondary btn-sm" disabled title="No permission to edit">
                            <i class="fa fa-edit"></i>
                          </button>
                        <?php endif; ?>

                        <!-- Delete -->
                        <?php if ($canDelete): ?>
                          <a href="<?= base_url('master/delete_intake_year/'.$row->id); ?>"
                             onclick="return confirm('Are you sure you want to delete this intake year?');"
                             class="btn btn-danger btn-sm" title="Delete">
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
                    <td colspan="4" class="text-center text-muted">No Intake Years Found</td>
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

<!-- DataTables -->
<script>
$(document).ready(function () {
  $('#example1').DataTable({
    pageLength: 10,
    order: [[0, 'asc']],
    columnDefs: [
      { targets: [0, 1, 2, 3], className: 'text-center' },
      { targets: 3, orderable: false }  
    ],
    language: { search: "", searchPlaceholder: "Search..." },
    responsive: true,
    autoWidth: false
  });
});
</script>

<style>
.badge { padding: 6px 10px; font-size: 13px; }
.table th, .table td { vertical-align: middle; }
</style>
