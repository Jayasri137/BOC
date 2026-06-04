<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php
// Branch view with permission checks (view-level)
// Fetch branches
$branches = $this->db->query("SELECT * FROM branches");

// get CI instance & session
$ci = &get_instance();
$company1 = $ci->session->userdata('company1') ?? [];
$user_group_id = is_array($company1) && isset($company1['user_group_id']) ? (int)$company1['user_group_id'] : 0;
$is_admin_flag = isset($company1['is_admin']) && $company1['is_admin'] == true;

/**
 * has_permission_by_menu_name
 * - menuNames: string or array of menu labels (will apply like() on mm.mm_name)
 * - action: 'view'|'add'|'edit'|'delete'
 */
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

// decide permissions for Branch menu (adjust menu label if different in your menu_master)
$menuLabel = ['Branch','branch']; // try multiple variants
$canView   = has_permission_by_menu_name($menuLabel, 'view', $user_group_id, $is_admin_flag, $ci);
$canAdd    = has_permission_by_menu_name($menuLabel, 'add',  $user_group_id, $is_admin_flag, $ci);
$canEdit   = has_permission_by_menu_name($menuLabel, 'edit', $user_group_id, $is_admin_flag, $ci);
$canDelete = has_permission_by_menu_name($menuLabel, 'delete',$user_group_id, $is_admin_flag, $ci);
?>

<div class="content-wrapper">
  <div class="container-full">

    <!-- Content Header -->
    <div class="content-header">
      <div class="d-flex align-items-center">
        <div class="me-auto">
          <h3 class="page-title">Branch</h3>
          <nav>
            <ol class="breadcrumb">
              <li class="breadcrumb-item">
                <a href="<?= base_url('dashboard'); ?>">
                  <i class="mdi mdi-home-outline"></i> Dashboard
                </a>
              </li>
              <li class="breadcrumb-item active">Branch</li>
            </ol>
          </nav>
        </div>
      </div>
    </div>

    <!-- Main Content -->
    <div class="col-12">
      <div class="box">
        <div class="box-header d-flex justify-content-between align-items-center">
          <h4 class="box-title">Branch List</h4>

          <div>
            <?php if ($canAdd): ?>
              <a href="<?= base_url('master/create_branch'); ?>" class="btn btn-primary">
                <i class="fa fa-plus-circle me-1"></i> Add Branch
              </a>
            <?php else: ?>
              <button class="btn btn-primary" disabled title="You don't have permission to add branch">
                <i class="fa fa-plus-circle me-1"></i> Add Branch
              </button>
            <?php endif; ?>
          </div>
        </div>

        <div class="box-body">
          <div class="table-responsive">
            <table id="example1" class="table table-bordered table-striped">
              <thead>
                <tr>
                  <th>S.no</th>
                  <th>Branch Name</th>
                  <th>Status</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody class="text-center">
                <?php if ($branches && $branches->num_rows() > 0): ?>
                  <?php $i = 1; foreach ($branches->result() as $row): ?>
                    <tr>
                      <td><?= $i++; ?></td>
                      <td class="text-start"><?= htmlspecialchars($row->name ?? '-'); ?></td>

                      <td>
                        <?php if ($row->status == 1): ?>
                          <span class="badge bg-success">Active</span>
                        <?php else: ?>
                          <span class="badge bg-danger">Inactive</span>
                        <?php endif; ?>
                      </td>

                      <!-- Actions -->
                      <td>
                        <?php if ($canEdit): ?>
                          <a href="<?= base_url('master/edit_branch/'.$row->id); ?>" class="btn btn-dark btn-sm" title="Edit">
                            <i class="fa fa-edit"></i>
                          </a>
                        <?php else: ?>
                          <button class="btn btn-secondary btn-sm" disabled title="No permission to edit"><i class="fa fa-edit"></i></button>
                        <?php endif; ?>

                       <?php if ($canEdit): ?>
                          <a href="<?= base_url('master/delete_branch/'.$row->id); ?>"
                            class="btn btn-danger btn-sm"
                            onclick="return confirm('Are you sure you want to delete this branch?');"
                            title="Delete">
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
                    <td colspan="4" class="text-center text-muted">No branches found.</td>
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
      { targets: 0, className: 'text-center' },
      { targets: 1, className: 'text-start' },
      { targets: 2, className: 'text-center' },
      { targets: 3, className: 'text-center', orderable: false }
    ],
    language: {
      search: "",
      searchPlaceholder: "Search branches...",
      emptyTable: "No branches available",
      info: "Showing _START_ to _END_ of _TOTAL_ branches",
      infoEmpty: "Showing 0 to 0 of 0 branches",
      infoFiltered: "(filtered from _MAX_ total branches)",
      lengthMenu: "Show _MENU_ branches",
      paginate: {
        first: "First",
        last: "Last",
        next: "Next",
        previous: "Previous"
      }
    },
    responsive: true,
    autoWidth: false
  });
});
</script>
