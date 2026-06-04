<div class="content-wrapper">
    <div class="container-full">
        <div class="content-header">
            <div class="d-flex align-items-center">
                <div class="me-auto">
                    <h3 class="page-title">View User</h3>
                    <nav>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="<?=base_url();?>master/usermaster"> <i class="mdi mdi-home-outline">
                                        User</i></a></li>
                            <!-- <li class="breadcrumb-item"></li> -->
                            <li class="breadcrumb-item active">View User</li>
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
                            <h4 class="box-title">User Details</h4>
                        </div>
                        <form class="form needs-validation" id="submitForm" name="addform"  method="POST" novalidate>
                            <div class="box-body">
                                <div class="row">

                                    <div class="col-lg-4 col-md-4 col-sm-6">
                                        <div class="form-group">
                                            <label class="form-label     ">User Group</label>
											<select name="usergroup"  id="usergroup" class="form-select" disabled>
												<option value="">Select</option>
												<?php 
												$cat=$this->db->query("select * from user_group where ug_status='1'");    
												foreach($cat->result() as $row){  
																		?>
												<option value="<?php echo $row->ug_id;?>"><?php echo $row->ug_name;?></option>
												<?php } ?>
											</select>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-6">
                                        <div class="form-group">
                                            <label class="form-label ">User Name</label>
                                            <input type="text" class="form-control" name="username"  onblur="CheckUserName(this.value,'<?php echo "0";?>')" id="cat" placeholder="User Name"  readonly   >
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-6">
                                        <div class="form-group">
                                            <label class="form-label  ">Password</label>
                                            <input type="password" name="password1" id="password1" onblur="CheckPassword(this)" class="form-control" placeholder="Password"  readonly   >
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-6">
                                        <div class="form-group">
                                            <label class="form-label     ">Confirm Password</label>
                                            <input type="password" name="password2" id="password2" onblur="CheckPassword(this)"  class="form-control" placeholder="Confirm Password"  readonly   >
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-6">
                                        <div class="form-group">
                                            <label class="form-label   ">Employee</label>
                                            <select  name="employee"  id="employee" class="form-control" disabled>
												<option value="">Select</option>
												<?php 
												$cat=$this->db->query("select * from employee where status='1'");    
												foreach($cat->result() as $row){  
																		?>
												<option value="<?php echo $row->id;?>"><?php echo $row->name;?></option>
												<?php } ?>
											</select>
                                        </div>
                                    </div>
                                    
                                    <div class="col-lg-4 col-md-4 col-sm-6">
                                        <div class="form-group">
                                            <label class="form-label ">Status</label>
                                            <select class="form-select" name="status" id="status" disabled>
												<option value="0">Active</option>
												<option value="1">In-Active</option>
                                            </select>
                                        </div>
                                    </div>

                                </div>
                            </div>
                            <div class="box-footer d-flex justify-content-between">
                                <button type="reset" class="btn btn-warning">
                                    <i class="ti-trash"></i> Cancel
                                </button>
                                <button type="submit" id="btnSubmit"  value="Submit" class="btn btn-primary">
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