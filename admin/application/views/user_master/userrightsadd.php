<div class="content-wrapper">
    <div class="container-full">
        <div class="content-header">
            <div class="d-flex align-items-center">
                <div class="me-auto">
                    <h3 class="page-title">User Rights Add</h3>
                    <nav>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="<?=base_url(); ?>master/userrights"> <i class="mdi mdi-home-outline"> User Rights</i></a></li>
                            <!-- <li class="breadcrumb-item"></li> -->
                            <li class="breadcrumb-item active">User Rights Add</li>
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
                            <h4 class="box-title">Add User Rights </h4>
                        </div>
                        <form class="form needs-validation" id="submitForm" name="addform" action="<?php echo base_url();?>master/userrights" method="POST" novalidate>
                            <div class="box-body">
                                <div class="row">
                                    <div class="col-md-4 col-sm-6">
                                        <div class="form-group">
                                            <label class="form-label required">User Group</label>
											<select class="form-select" id="usergroup" name="usergroup" required >
												<option value="">Select</option>
												<!-- <?php 
												$userGroup = $this->db->query("select ug.* from user_group as ug where ug.status=1 and ug.id not in (select user_group_id from user_rights where status=1) order by ug.id asc ");
												foreach($userGroup->result() as $row){  
																		?>
												<option value="<?php echo $row->id;?>"><?php echo $row->group_name;?></option>
												<?php } ?> -->
                                            </select>
                                        </div>
                                    </div>
                                </div>
								<div class="row">
									<div class="table-responsive">
										<table id="example" class="table table-bordered table-striped">
											<thead>
												<tr>
													<th>S.No</th>
													<th>Menu Name</th>
													<th>Session</th>
													<th>View</th>
													<th>Add</th>
													<th>Edit</th>
												</tr>
											</thead>
											<tbody>
												<tr class="row1" id="row_1">
													<td align="center">	1</td>
													<td align="left"> 
														<input   type="text" class="form-control" placeholder="Menu Name" required value="Customer"  readonly />
														<input   type="hidden" value="1" />
													</td>
													<td>	
														<input  type="text" class="form-control text-center" value="1000" readonly />
													</td>
													<td class="form-group">
															<input type="checkbox" class="form-control view" placeholder="Change Mode" name="changemode" id="changemode">
															<label for="changemode" class="mb-0 h-15"> </label>
													</td> 									
													<td class="form-group">	
														<input type="checkbox" class="form-control add" placeholder="Change Mode" name="changemode" id="changemode">
														<label for="changemode" class="mb-0 h-15"> </label>
													</td>
													<td class="form-group">
														<input type="checkbox" class="form-control edit" placeholder="Change Mode" name="changemode" id="changemode">
														<label for="changemode" class="mb-0 h-15"> </label>
													</td> 	
												</tr>
											</tbody>
										</table>
									</div>
								</div>
                            </div>
                            <div class="box-footer d-flex justify-content-between">
                                <button type="reset" class="btn btn-warning">
                                    <i class="ti-trash"></i> Cancel
                                </button>
                                <button type="submit" value="Submit"  id="submit" class="btn btn-primary">
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

<div class="content-wrapper">
	<div class="container-full">
		<div class="content-header">
			<div class="d-flex align-items-center">
				<div class="me-auto">
					<h3 class="page-title">Create Customer Category</h3>
					<nav>
						<ol class="breadcrumb">
							<li class="breadcrumb-item"><a href="customer_category.php"> <i class="mdi mdi-home-outline"> Customer Category</i></a></li>
							<!-- <li class="breadcrumb-item"></li> -->
							<li class="breadcrumb-item active">Create Customer Category</li>
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
							<h4 class="box-title">Customer Category Details</h4>
						</div>
						<form class="form needs-validation" novalidate>
							<div class="box-body">
								<div class="row">
									<div class="col-lg-4 col-md-4 col-sm-6">
										<div class="form-group">
											<label class="form-label required">Customer Category Name</label>
											<input type="text" class="form-control" placeholder="Customer_Category Name" required>
										</div>
									</div>
									<div class="col-lg-4 col-md-4 col-sm-6">
									
									</div>
								</div>
							</div>
							<div class="box-footer d-flex justify-content-between">
								<button type="reset" class="btn btn-warning">
									<i class="ti-trash"></i> Cancel
								</button>
								<button type="submit" class="btn btn-primary">
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
	(function() {
		'use strict';
		var forms = document.querySelectorAll('.needs-validation');
		Array.prototype.slice.call(forms).forEach(function(form) {
			form.addEventListener('submit', function(event) {
				if (!form.checkValidity()) {
					event.preventDefault();
					event.stopPropagation();
				}
				form.classList.add('was-validated');
			}, false);
		});
	})();
</script>
<div class="container-xxl flex-grow-1 container-p-y">     
 	<h4 class="py-3 mb-4">
	<span class="text-muted fw-light"><a href="<?=base_url();?>Dashboard">Dashboard</a> /</span> User Rights Create
	</h4>
	<div class="col-md-12">
		<form name="addform" action="<?php echo base_url();?>master/userrightsadd" method="POST">
			<div class="col-md-12 col-sm-12">
				<div class="card mb-3 p-3 pb-5">
					<div class="row py-2 px-3">
						<div class="col-md-6 col-sm-6">
							<div class="form-label-pad">User Group <span class="required-input">*</span>
							</div>
							<div class="form-group-row">
								<select required="" name="usergroup"  id="usergroup" class="form-control">
									<option value="">Select</option>
									<?php 
									$userGroup = $this->db->query("select ug.* from user_group as ug where ug.ug_status=1 and ug.ug_id not in (select ur_user_group_id from user_rights where ur_status=1) order by ug.ug_id asc ");
									foreach($userGroup->result() as $row){  
															?>
									<option value="<?php echo $row->ug_id;?>"><?php echo $row->ug_name;?></option>
									<?php } ?>
								</select>
							</div>
						</div>
						<div class="col-md-6 col-sm-6">
							
						</div> 
					</div>
					<div class="row py-2 px-3">
						<table id="example1" class="table table-striped table-bordered nowrap" cellspacing="0" width="100%" style="padding-top:0px;padding-bottom:0px;">
							<thead>
								<tr style="text-align:center">
									<th>S.No</th>
									<th>Menu Name</th>
									<th>Session</th>
									<th><input type="checkbox" class="form-check-input " onchange="changeValue(this,'view')" style="margin-bottom:5px"/><br/>VIEW</th>
									<th><input type="checkbox" class="form-check-input " onchange="changeValue(this,'add')" style="margin-bottom:5px"/><br/>ADD</th>
									<th><input type="checkbox" class="form-check-input " onchange="changeValue(this,'edit')" style="margin-bottom:5px"/><br/>EDIT</th>
								</tr>
							</thead>
							<tbody class="table-bordered">
								<?php 
								
									
									
									$this->db->select('mm.*');
									$this->db->from("menu_master as mm");
									$this->db->order_by("mm.mm_id","asc");
									$menuMaster= $this->db->get();
							$sno=1;
							foreach($menuMaster->result() as $row)
							{
							?>
								<tr class="row<?=$sno?>" id="row_<?=$sno?>">
									<td align="center"> 
										<?=$sno?>
									</td>
									<td align="left"> 
										<input id="menuName_<?=$sno?>" name="items[<?=$sno?>][menuName]"  type="text" class="form-control" placeholder="Menu Name" required value="<?=$row->mm_name?>"  readonly />
										<input id="menuId_<?=$sno?>" name="items[<?=$sno?>][menuId]"  type="hidden" value="<?=$row->mm_id?>" />
									</td>
									<td>	
										<input id="sessionNumber_<?=$sno?>" name="items[<?=$sno?>][sessionNumber]"  type="text" class="form-control text-center" value="<?=$row->mm_session_number?>" readonly />
									</td>
									<td style="text-align:center">
										<input id="view_<?=$sno?>" name="items[<?=$sno?>][view]" style="margin-left:0px"  type="checkbox" class="form-check-input view"  />
									</td> 									
									<td style="text-align:center">	
										<input id="add_<?=$sno?>" name="items[<?=$sno?>][add]" style="margin-left:0px"  type="checkbox" class="form-check-input add" />
									</td>
									<td style="text-align:center">
										<input id="edit_<?=$sno?>" name="items[<?=$sno?>][edit]" style="margin-left:0px"  type="checkbox" class="form-check-input edit" />
									</td> 	
								</tr>
							<?php $sno++; }?>
								
							</tbody>
						</table>
					</div>
					<div class="row py-2 px-3" style="justify-content: end;">
						<?php if($this->sessionArray[1046]->ur_add==1)  {   ?>
							<button  type="button" id="btnSubmit"  value="Submit" class="btn btn-success btn-below " style="width:auto">Submit</button>
								<!-- <input type="submit" value="Submit" class="mainsub" id="submit">	 -->
						<?php } ?>
					</div>
				</div>
			</div>
		</form>
	</div>
</div>

<script>
function changeValue(element,value)
{
	if(element.checked==true)
		$('.'+value).not(this).prop('checked', true);  
	else
		$('.'+value).not(this).prop('checked', false);  
}
</script>

	