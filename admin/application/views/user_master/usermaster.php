<?php
// safe session and rights setup
$session = $this->session->userdata('company1') ?? [];
$customerId = $session['customer_id'] ?? ($session['company_id'] ?? 1);

// ensure Master_model is loaded (no harm if already loaded)
if (!isset($this->master)) {
    $this->load->model('Master_model', 'master', true);
}

// request rights using optional group id (Master_model handles null safely)
$user_group_id = $session['user_group_id'] ?? null;
$userRights = $this->master->getUserAuthentication($user_group_id);

/**
 * hasRight helper
 * @param int $menuIndex menu session number (eg 1014)
 * @param string $prop ur_add | ur_view | ur_edit | ur_delete | etc
 * @return bool
 */
function hasRight($menuIndex, $prop = 'ur_view') {
    // $userRights is not available in function scope by default, bring it in
    $ci =& get_instance();
    if (!isset($ci->master)) {
        return false;
    }
    // The controller/view set $userRights variable; prefer that
    if (isset($ci->load) && isset($ci->load->get_var)) {
        // If you ever set the view var, but here we rely on local variable
    }

    // Try to use local $userRights if present in view scope
    global $userRights;
    if (isset($userRights) && is_array($userRights) && array_key_exists($menuIndex, $userRights) && is_object($userRights[$menuIndex])) {
        return (isset($userRights[$menuIndex]->{$prop}) && intval($userRights[$menuIndex]->{$prop}) === 1);
    }

    // fallback: attempt to fetch fresh rights from model using session user_group_id
    $session = $ci->session->userdata('company1') ?? [];
    $group = $session['user_group_id'] ?? null;
    if ($group === null) return false;
    $fresh = $ci->master->getUserAuthentication($group);
    if (isset($fresh[$menuIndex]) && is_object($fresh[$menuIndex]) && isset($fresh[$menuIndex]->{$prop})) {
        return intval($fresh[$menuIndex]->{$prop}) === 1;
    }
    return false;
}
?>

<div class="content-wrapper">
    <div class="container-full">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="d-flex align-items-center">
                <div class="me-auto">
                    <h3 class="page-title">User Master Details</h3>
                    <div class="d-inline-block align-items-center">
                        <nav>
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="<?= base_url(); ?>Dashboard"><i class="mdi mdi-home-outline"> Dashboard</i></a></li>
                                <li class="breadcrumb-item active" aria-current="page">User Master</li>
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
					<h4 class="box-title"></h4>

					<?php if (hasRight(1014, 'ur_add')) : ?>
						<div>
							<a href="<?= base_url(); ?>master/usermasteradd" class="btn btn-primary">Create User Master</a>
						</div>
					<?php endif; ?>

				</div>

				<!-- /.box-header -->
				<div class="box-body">
					<div class="table-responsive">
					  <table id="example1" class="table table-bordered table-striped">
						<thead>
							<tr>
								<th>S.No</th>
								<th>User Name</th>
								<th>User Role</th>
								<th>Status</th>
								<th>Action</th>
							</tr>
						</thead>
						<tbody>
							<?php
								$this->db->select('um.*,ug.*');
								$this->db->from("user_master as um");
								$this->db->join("user_group as ug","um.um_user_group_id=ug.ug_id","left");
								$this->db->where('um.um_status',1);
								// use safe customerId variable
								$this->db->where('um.um_customer_id', $customerId);
								$this->db->order_by('um.um_username','asc');
								$psc = $this->db->get();
								$sno = 1;
								foreach ($psc->result() as $row) {
							?>
									<tr>
										<td class="tdtexts"><?php echo $sno; ?></td>
										<td class="tdtexts"><?php echo html_escape($row->um_username); ?></td>
										<td class="tdtextsleft"><?php echo html_escape($row->ug_name); ?></td>
										<td class="tdtexts"><?php echo ($row->um_status==1) ? "Active" : "In-Active"; ?></td>
										<td class="text-center">
											<?php if (hasRight(1014, 'ur_edit')) : ?>
												<a href="<?= base_url(); ?>master/usermasteredit/<?php echo $row->um_id; ?>" data-bs-toggle="tooltip" data-bs-placement="top" title="Edit">
													<button type="button" class="waves-effect waves-light btn btn-dark mb-5">
														<i class="fa fa-edit"></i>
													</button>
												</a>
											<?php endif; ?>

											<!-- <a href="<?= base_url(); ?>master/usermasterview" data-bs-toggle="tooltip" data-bs-placement="top" title="View">
												<button type="button" class="waves-effect waves-light btn btn-warning mb-5">
													<i class="fa fa-eye"></i>
												</button>
											</a> -->

											<?php if (hasRight(1014, 'ur_edit')) : ?>
												<a href="<?= base_url(); ?>master/usermasterdelete/<?php echo $row->um_id; ?>" data-bs-toggle="tooltip" data-bs-placement="top" title="Delete" onclick="return confirm('Are you sure you want to delete this user?');">
													<button type="button" class="waves-effect waves-light btn btn-danger mb-5">
														<i class="fa fa-trash"></i>
													</button>
												</a>
											<?php endif; ?>
										</td>
									</tr>
							<?php
									$sno++;
								} // endforeach
							?>
						</tbody>
					  </table>
					</div>
				</div>
				<!-- /.box-body -->
			  </div>
			  <!-- /.box -->
        </div>
        <!-- /.content -->
    </div>
</div>

<script>
$(document).ready(function() {
    var dataTable = $('.table').DataTable({
        "processing": true,
        "serverSide": true,
        "aaSorting": [],
        "ajax": {
            "url": "<?= base_url() ?>master/usermasterjson",
            "type": "POST",
        },
        "columnDefs": [
            { "targets": 0, "className": "tdtexts" },
            { "targets": 1, "className": "tdtexts" },
            { "targets": 2, "className": "tdtextsleft" },
            { "targets": 3, "className": "tdtextsleft" },
            { "targets": 4, "className": "tdtextsleft" },
            { "targets": 5, "className": "tdtexts" },
            { "targets": 6, "className": "tdtexts" }
        ],
    });
});
</script>
