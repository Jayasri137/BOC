<?php $customerId = $session['customer_id'];
$this->load->model('Master_model', 'master', 1);
$userRights = $this->master->getUserAuthentication();
//     print_r($userRights[1013]);
?>
<div class="content-wrapper">
    <div class="container-full">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="d-flex align-items-center">
                <div class="me-auto">
                    <h3 class="page-title">Role Details</h3>
                    <div class="d-inline-block align-items-center">
                        <nav>
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="<?=base_url();?>Dashboard"><i class="mdi mdi-home-outline"> Dashboard</i></a></li>
                                <li class="breadcrumb-item active" aria-current="page">Role</li>
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
					<?php if($userRights[1015]->ur_add==1) { ?>
					<div>
						<a href="<?= base_url(); ?>master/usergroupadd" class="btn btn-primary">
  <i class="fas fa-user-plus me-2"></i> Create Role
</a>  <!-- Anchor tag styled as a button -->
					</div>
					<?php }?>
				</div>

			<!-- /.box-header -->
				<div class="box-body">
					<div class="table-responsive">
					  <table id="example1" class="table table-bordered table-striped">
						<thead>
							<tr>
								<th>S.No</th>
								<th>Role</th>
								<th>Status</th>
								<th>Action</th>
							</tr>
						</thead>
						<tbody>
							<?php
							$acat=$this->db->query("select  * from user_group where ug_customer_id='$customerId' and  ug_status=1");
							$sno=1;
							foreach($acat->result() as $row)
							{
							?>
								<tr>
									<td class="tdtexts" ><?php echo  $sno;?></td>
									<td class="tdtextsleft" ><?php echo $row->ug_name;?></td>
									<td class="tdtexts" ><?php echo ($row->ug_status==1)?"Active":"In-Active";//;$row->status;?></td>
									<td class="text-center">
										<?php if($userRights[1015]->ur_edit==1) { ?>
										<a href="<?=base_url();?>master/usergroupedit/<?php echo $row->ug_id;?>" data-bs-toggle="tooltip" data-bs-placement="top"
											title="Edit">
											<button type="button" class="waves-effect waves-light btn btn-dark mb-5">
												<i class="fa fa-edit"></i>
											</button>
										</a>
										<?php }?>
										<!-- <a href="<?=base_url();?>master/usergroupview" data-bs-toggle="tooltip" data-bs-placement="top"
											title="View">
											<button type="button" class="waves-effect waves-light btn btn-warning mb-5">
												<i class="fa fa-eye"></i>
											</button>
										</a> -->
										<?php if($userRights[1015]->ur_edit==1) { ?>
										 <a href="<?= base_url(); ?>master/usergroupdelete/<?php echo $row->ug_id; ?>" data-bs-toggle="tooltip" data-bs-placement="top"
                                                title="Delete" onclick="return confirm('Are you sure you want to delete this usergroup?');">
                                                <button type="button" class="waves-effect waves-light btn btn-danger mb-5">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            </a>
										<?php }?>
									</td>
								</tr>
							<?php $sno++; }?>
						</tbody>
						<tfoot>
							
						</tfoot>
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
    var dataTable=$('.table').DataTable( {
        "processing": true,
        "serverSide": true,
		"ajax": {
			"url":"<?=base_url()?>master/usergroupjson",
			"type":"POST",
		},	
		"columnDefs": [
				{   "targets": 0,className: "tdtexts" },
				{  "targets":  1,className: "tdtextsleft" },
				{  "targets": 2,className: "tdtexts"  },
				{  "targets": 3,className: "tdtexts"  },
				{  "targets": 4,className: "tdtexts"  },
				{  "targets": 5,className: "tdtexts"  },
				],
    } );
});

</script>

	