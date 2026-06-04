<div class="content-wrapper">
    <div class="container-full">
        <div class="content-header">
            <div class="d-flex align-items-center">
                <div class="me-auto">
                    <h3 class="page-title">User Rights Edit</h3>
                    <nav>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="<?=base_url(); ?>master/userrights"> <i class="mdi mdi-home-outline"> User Rights</i></a></li>
                            <!-- <li class="breadcrumb-item"></li> -->
                            <li class="breadcrumb-item active">User Rights Edit</li>
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
                            <h4 class="box-title">Edit User Rights </h4>
                        </div>
                        <form class="form needs-validation" id="submitForm" name="addform" action="<?php echo base_url();?>master/userrights" method="POST" novalidate>
                            <div class="box-body">
                                <div class="row">
                                    <div class="col-md-4 col-sm-6">
                                        <div class="form-group">
                                            <label class="form-label required">User Group</label>
											<select class="form-select" id="usergroup" name="usergroup" required >
												<option value="">Select</option>
												
                                            </select>
                                        </div>
                                    </div>
                                </div>
								<div class="row">
									<div class="table-responsive">
										<table id="example1" class="table table-bordered table-striped">
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
														<input id="menuName_1" name="items[1][menuName]"  type="text" class="form-control m-0" placeholder="Menu Name" required value="Customer"  readonly />
														<input id="menuId_1" name="items[1][menuId]"  type="hidden" value="1" />
													</td>
													<td>	
														<input id="sessionNumber_1" name="items[1][sessionNumber]"  type="text" class="form-control text-center m-0" value="1000" readonly />
													</td>
													<td class="form-group">
															<input type="checkbox" class="form-control view" id="view_1" name="items[1][view]" placeholder="Change Mode" name="changemode" id="changemode">
															<label for="changemode" class="mb-0 h-15"> </label>
													</td> 									
													<td class="form-group">	
														<input type="checkbox" class="form-control add" id="add_1" name="items[1][add]" placeholder="Change Mode" name="changemode" id="changemode">
														<label for="changemode" class="mb-0 h-15"> </label>
													</td>
													<td class="form-group">
														<input type="checkbox" class="form-control edit"  id="edit_1" name="items[1][edit]" placeholder="Change Mode" name="changemode" id="changemode">
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