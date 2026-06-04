<?php
// application/views/user_master/userrights.php
$session = $this->session->userdata('company1');
$isSuperAdmin = isset($session['is_admin']) && $session['is_admin'] == true;

// fetch userRights safely, but avoid heavy DB call here — your controller previously used $this->master->getUserAuthentication()
// If you prefer, you may call model here. For minimal change we call model only if needed:
$this->load->model('Master_model', 'master', true);
$userRights = $this->master->getUserAuthentication($session['user_group_id'] ?? null);

// helper: can current logged user edit user-rights?
$canEditUserRights = $isSuperAdmin || (isset($userRights[1050]) && is_object($userRights[1050]) && isset($userRights[1050]->ur_edit) && $userRights[1050]->ur_edit == 1);
$customerId = $session['customer_id'] ?? 0;
?>
<div class="content-wrapper">
    <div class="container-full">
        <div class="content-header">
            <div class="d-flex align-items-center">
                <div class="me-auto">
                    <h3 class="page-title">User Rights Details</h3>
                    <nav>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="<?= base_url(); ?>Dashboard"><i class="mdi mdi-home-outline"> Dashboard</i></a></li>
                            <li class="breadcrumb-item active" aria-current="page">User Rights</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>

        <div class="col-12">
            <div class="box">
                <div class="box-header d-flex justify-content-between align-items-center">
                    <h4 class="box-title"></h4>
                </div>

                <div class="box-body">
                    <div class="table-responsive">
                        <table id="example1" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>S.No</th>
                                    <th>Role Name</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $this->db->select('ug.*');
                                $this->db->from("user_group as ug");
                                $this->db->order_by("ug.ug_id", "asc");
                                $this->db->where("ug.ug_status", 1);
                                if($customerId){
                                    $this->db->where("ug.ug_customer_id", $customerId);
                                }
                                $query = $this->db->get();
                                $sno = 1;
                                foreach ($query->result() as $row) {
                                    ?>
                                    <tr>
                                        <td class="tdtexts"><?php echo $sno; ?></td>
                                        <td class="tdtextsleft"><?php echo $row->ug_name; ?></td>
                                        <td class="text-center">
                                            <?php if ($canEditUserRights) { ?>
                                                <a href="<?= base_url(); ?>master/usersrightsedit/<?php echo $row->ug_id ?>" data-bs-toggle="tooltip" data-bs-placement="top" title="Edit">
                                                    <button type="button" class="waves-effect waves-light btn btn-dark mb-5">
                                                        <i class="fa fa-edit"></i>
                                                    </button>
                                                </a>
                                            <?php } ?>
                                            <!-- <a href="<?= base_url(); ?>master/userrights/<?php echo $row->ug_id ?>" data-bs-toggle="tooltip" data-bs-placement="top" title="View">
                                                <button type="button" class="waves-effect waves-light btn btn-danger mb-5">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            </a> -->
                                        </td>
                                    </tr>
                                    <?php
                                    $sno++;
                                } ?>
                            </tbody>
                            <tfoot></tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    $('#example1').DataTable({
        "processing": true,
        "serverSide": true,
        "ajax": {
            "url": "<?= base_url() ?>master/userrightsjson",
            "type": "POST"
        },
        // define column classes if you want
    });
});
</script>
