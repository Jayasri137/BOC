<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<?php
// Fetch all contact enquiries
$contact_rows = $this->db->order_by('id', 'DESC')->get('contact_enquiry');

// Prepare CSRF if enabled
$ci = &get_instance();
$csrf = null;
if (isset($ci->security) && method_exists($ci->security, 'get_csrf_token_name')) {
    $csrf = [
        'name' => $ci->security->get_csrf_token_name(),
        'hash' => $ci->security->get_csrf_hash()
    ];
}

// Permission helper (view-level)
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

// For contact enquiries, check both 'Contact' and 'Contact Enquiry' menu labels (adjust if your menu name differs)
$menuNames = ['Contact', 'Contact Enquiry'];
$canViewContact = has_permission_by_menu_name($menuNames, 'view', $user_group_id, $is_admin_flag, $ci);
$canEditContact = has_permission_by_menu_name($menuNames, 'edit', $user_group_id, $is_admin_flag, $ci);
// If you have a dedicated add right and want to show an "Add" button for contact, you can check 'add' as well:
// $canAddContact = has_permission_by_menu_name($menuNames, 'add', $user_group_id, $is_admin_flag, $ci);

// If the user somehow reached the view without view permission, show message
if (!$canViewContact) {
    echo '<div class="alert alert-danger p-3">You do not have permission to view this page.</div>';
    return;
}
?>

<!-- Content Wrapper -->
<div class="content-wrapper">
    <div class="container-full">
        <!-- Content Header -->
        <div class="content-header">
            <div class="d-flex align-items-center">
                <div class="me-auto">
                    <h3 class="page-title">Contact Enquiries</h3>
                    <div class="d-inline-block align-items-center">
                        <nav>
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item">
                                    <a href="<?= base_url('Dashboard'); ?>">
                                        <i class="mdi mdi-home-outline"></i> Dashboard
                                    </a>
                                </li>
                                <li class="breadcrumb-item active" aria-current="page">Contact Enquiries</li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main content -->
        <div class="col-12">
            <div class="box">
                <div class="box-header d-flex justify-content-between align-items-center">
                    <h4 class="box-title">Contact Enquiry List</h4>
                </div>

                <div class="box-body">
                    <div class="table-responsive">
                        <table id="example1" class="table table-bordered table-striped editable-table">
                            <thead>
                                <tr>
                                    <th>S.No</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                    <th>Subject</th>
                                    <th>Created Date</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody class="text-center">
                                <?php
                                $sno = 1;
                                if ($contact_rows && $contact_rows->num_rows() > 0):
                                    foreach ($contact_rows->result() as $row):
                                        $id      = (int) $row->id;
                                        $name    = htmlspecialchars(trim($row->name ?? '-'));
                                        $email   = htmlspecialchars($row->email ?? '-');
                                        $phone   = htmlspecialchars($row->phone ?? '-');
                                        $subject = htmlspecialchars($row->subject ?? '-');
                                        $created = !empty($row->created_at) ? date('d-M-Y ', strtotime($row->created_at)) : '-';
                                ?>
                                <tr>
                                    <td><?= $sno++; ?></td>
                                    <td class="text-start"><?= $name; ?></td>
                                    <td class="text-start"><?= $email; ?></td>
                                    <td class="text-start"><?= $phone; ?></td>
                                    <td class="text-start"><?= $subject; ?></td>
                                    <td class="text-center"><?= $created; ?></td>
                                    <td class="text-center">
                                        <?php if ($canEditContact): ?>
                                        <a href="<?= base_url("contact/view/{$id}"); ?>" 
                                           class="btn btn-warning btn-sm" 
                                           title="View">
                                            <i class="fa fa-eye"></i>
                                        </a>
                                         <?php else: ?>
                                            <button class="btn btn-secondary btn-sm" disabled title="No permission to view">
                                                <i class="fa fa-eye"></i>
                                            </button>
                                        <?php endif; ?>

                                        <?php if ($canEditContact): ?>
                                            <form method="post" 
                                                  action="<?= base_url("contact/delete/{$id}"); ?>" 
                                                  style="display:inline-block;" 
                                                  onsubmit="return confirm('Are you sure you want to delete this enquiry?');">
                                                <?php if (!empty($csrf)): ?>
                                                    <input type="hidden" name="<?= $csrf['name']; ?>" value="<?= $csrf['hash']; ?>">
                                                <?php endif; ?>
                                                <button type="submit" 
                                                        class="btn btn-danger btn-sm" 
                                                        title="Delete">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            </form>
                                        <?php else: ?>
                                            <button class="btn btn-secondary btn-sm" disabled title="No permission to delete">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <?php endforeach; else: ?>
                                <tr>
                                    <td colspan="7" class="text-center text-muted">No enquiries found.</td>
                                </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <!-- /.content -->
    </div>
</div>

<!-- DataTable Initialization -->
<script>
$(document).ready(function() {
    $('#example1').DataTable({
        pageLength: 10,
        order: [[5, 'desc']], // Created Date column index = 5 (0-based)
        columnDefs: [
            { targets: 0, className: "text-center" },
            { targets: [1,2,3,4], className: "text-start" },
            { targets: 5, className: "text-center" },
            { targets: 6, className: "text-center" }
        ],
        language: {
            searchPlaceholder: "Search..."
        }
    });
});
</script>