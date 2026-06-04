<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php
$ci = &get_instance();
$company1 = $ci->session->userdata('company1') ?? [];
$user_group_id = is_array($company1) && isset($company1['user_group_id']) ? (int)$company1['user_group_id'] : 0;
$is_admin_flag = isset($company1['is_admin']) && $company1['is_admin'] == true;

// local helper: check permission by menu name (same logic as controller)
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

$menuNames = ['Web Enquiry','Web Enquiries','WebEnquiry'];
$canView = has_permission_local($menuNames, 'view', $user_group_id, $is_admin_flag, $ci);
$canDelete = has_permission_local($menuNames, 'edit', $user_group_id, $is_admin_flag, $ci);

// If the user somehow reached the view without view permission, show message
if (!$canView) {
    echo '<div class="alert alert-danger p-3">You do not have permission to view this page.</div>';
    return;
}

// Fetch data for server-side or client-side table; prefer server-side JSON, but keep compatibility
$enquiry_q = $ci->db->order_by('created_at', 'DESC')->get('web_enquiry');
?>

<div class="content-wrapper">
    <div class="container-full">
        <div class="content-header">
            <div class="d-flex align-items-center">
                <div class="me-auto">
                    <h3 class="page-title">Web Enquiry</h3>
                    <nav><ol class="breadcrumb"><li class="breadcrumb-item"><a href="<?= base_url(); ?>Dashboard"><i class="mdi mdi-home-outline"></i> Dashboard</a></li></ol></nav>
                </div>
            </div>
        </div>

        <div class="col-12">
            <div class="box">
                <div class="box-header d-flex justify-content-between align-items-center">
                    <h4 class="box-title">Web Enquiry List</h4>
                </div>

                <div class="box-body">
                    <div class="table-responsive">
                        <table id="example1" class="table table-bordered table-striped editable-table">
                            <thead>
                                <tr>
                                    <th>S.No</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Mobile</th>
                                    <th>Created Date</th>
                                    <th>Action</th>
                                </tr>
                            </thead>

                            <tbody class="text-center">
                                <?php
                                $sno = 1;
                                if ($enquiry_q && $enquiry_q->num_rows() > 0):
                                    foreach ($enquiry_q->result() as $row):
                                        $id      = (int)$row->id;
                                        $name    = htmlspecialchars(trim(($row->first_name ?? '') . ' ' . ($row->last_name ?? '')));
                                        $email   = htmlspecialchars($row->email ?? '');
                                        $mobile  = htmlspecialchars($row->mobile ?? '');
                                        $created = !empty($row->created_at) ? date('d-M-Y', strtotime($row->created_at)) : '-';
                                ?>
                                <tr>
                                    <td><?= $sno++; ?></td>
                                    <td class="text-start"><?= $name ?: '-'; ?></td>
                                    <td class="text-start"><?= $email ?: '-'; ?></td>
                                    <td class="text-start"><?= $mobile ?: '-'; ?></td>
                                    <td class="text-center"><?= $created; ?></td>
                                    <td class="text-center">
                                        <?php if ($canDelete): ?>
                                        <a href="<?= base_url("webenquiry/view_enquiry/{$id}"); ?>" class="btn btn-warning btn-sm" title="View"><i class="fa fa-eye"></i></a>
                                         <?php else: ?>
                                                    <button class="btn btn-secondary btn-sm" disabled title="No permission"><i class="fa fa-edit"></i></button>
                                            <?php endif; ?>

                                        <?php if ($canDelete): ?>
                                            <a href="<?= base_url('webenquiry/delete/'.$id); ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this enquiry?');" title="Delete"><i class="fa fa-trash"></i></a>
                                        <?php else: ?>
                                         <button class="btn btn-secondary btn-sm" disabled title="No permission">  <i class="fa fa-trash"></i></button>

                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <?php
                                    endforeach;
                                else:
                                    echo '<tr><td colspan="6" class="text-center">No enquiries found.</td></tr>';
                                endif;
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- DataTables initialization (client-side). If you use server-side, point ajax to enquiryjson() -->
<script>
$(document).ready(function() {
    $('#example1').DataTable({
        pageLength: 10,
        order: [[4, 'desc']], // Created Date column index = 4 (0-based)
        columnDefs: [
            { targets: 0, className: "text-center" },
            { targets: [1,2,3], className: "text-start" },
            { targets: 4, className: "text-center" },
            { targets: 5, className: "text-center" }
        ],
        language: { searchPlaceholder: "Search..." }
    });
});
</script>
