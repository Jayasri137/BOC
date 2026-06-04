<?php
$acat=$this->db->query("select * from user_group where ug_id='$id'")->row();
?>
<div class="content-wrapper">
    <div class="container-full">
        <div class="content-header">
            <div class="d-flex align-items-center">
                <div class="me-auto">
                    <h3 class="page-title">Edit User Group</h3>
                    <nav>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="<?=base_url();?>master/usergroup"> <i class="mdi mdi-home-outline">
                                        User Group</i></a></li>
                            <!-- <li class="breadcrumb-item"></li> -->
                            <li class="breadcrumb-item active" aria-current="page">Edit User Group</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>

        <section class="content">
            <div class="row">
                <div class="col-lg-12 col-12">
                    <div class="box">
                        <div class="box-header with-border">
                            <h4 class="box-title">User Group Details</h4>
                        </div>
                        <form class="form needs-validation" id="submitForm" name="addform" action="<?=base_url();?>master/usergroupedit/<?php echo $id;?>" method="POST" novalidate>
                            <div class="box-body">
                                <div class="row">
                                    <div class="col-lg-4 col-md-4 col-sm-6">
                                        <div class="form-group">
                                            <label class="form-label required">User Group Name</label>
                                            <input type="text" class="form-control" name="name" id="cat" onblur="CheckUserGroup(this.value,'<?php echo $id;?>')" value="<?php echo $acat->ug_name;?>" placeholder="User Group Name"
                                                required>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-6">
                                        <div class="form-group">
                                            <label class="form-label">Status</label>
                                            <select class="form-select" name="status" id="status" >
												<option value="1" <?php echo set_select("status",$acat->ug_status,($acat->ug_status=="1"));?>>Active</option>
												<option value="1" <?php echo set_select("status",$acat->ug_status,($acat->ug_status=="0"));?>>In-Active</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="box-footer d-flex justify-content-between">
                                <button type="reset" class="btn btn-warning">
                                    <i class="ti-trash"></i> Cancel
                                </button>
                                <button type="submit" name="status" id="status" class="btn btn-primary">
                                    <i class="ti-save-alt"></i> Save
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>

<script>
    (function () {
        'use strict';
        var forms = document.querySelectorAll('.needs-validation');
        Array.prototype.slice.call(forms).forEach(function (form) {
            form.addEventListener('submit', function (event) {
                if (!form.checkValidity()) {
                    event.preventDefault();
                    event.stopPropagation();
                }
                form.classList.add('was-validated');
            }, false);
        });
    })();
</script>



<script>
		$(document).on('click','#submit',function (){
			$('#submitForm').valid();
		});
	</script>

	