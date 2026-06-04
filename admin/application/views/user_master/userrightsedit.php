
<div class="container-xxl flex-grow-1 container-p-y">     
 	<h4 class="py-3 mb-4">
	<span class="text-muted fw-light"><a href="<?=base_url();?>Dashboard">Dashboard</a> /</span> User Rights Edit
	</h4>
	<div class="col-md-12">
		<?php 

			$this->db->select('*');
			$this->db->from("user_group");
			$this->db->where('ug_id',$id);
			$userGroup= $this->db->get()->row();

			$this->db->select('ur.*,mm.*');
			$this->db->from("menu_master as mm");
			$this->db->join("user_rights as ur","ur.ur_menu_master_id=mm.mm_id and ur.ur_user_group_id=$id","left");
			$this->db->where("mm.mm_status",1);
			$this->db->order_by("mm.mm_session_number","asc");
			//$this->db->order_by("mm.mm_name","asc");
			$userRights= $this->db->get();

			?>
		<form name="addform" action="<?php echo base_url();?>master/userrightsedit/<?=$id?>" method="POST">
			<div class="col-md-12 col-sm-12">
				<div class="card mb-3 p-3 pb-5">
					<div class="row py-2 px-3">
						<div class="col-md-6 col-sm-6">
							<div class="form-label-pad">User Group <span class="required-input">*</span>
							</div>
							<div class="form-group-row">
								<select required="" name="usergroup"  id="usergroup" class="form-control">
									<option value="<?=$userGroup->ug_id;?>"><?=$userGroup->ug_name;?></option>
								</select>
							</div>
						</div>
						<div class="col-md-6 col-sm-6">
							
						</div> 
					</div>
					<div class="row py-2 px-3">
						<table id="example" class="table table-striped table-bordered nowrap" cellspacing="0" width="100%" style="padding-top:0px;padding-bottom:0px;">
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
								foreach($userRights->result() as $row)
								{
								?>
								<tr class="row<?=$sno?>" id="row_<?=$sno?>">
									<td align="center"> 
										<?=$sno?>
									</td>
									<td align="left"> 
										<input id="menuName_<?=$sno?>" name="items[<?=$sno?>][menuName]"  type="text" class="form-control" placeholder="Menu Name" required value="<?=$row->mm_name?>"  readonly />
										<input id="menuId_<?=$sno?>" name="items[<?=$sno?>][menuId]"  type="hidden" value="<?=$row->mm_id?>" />
										<input id="userRightsId_<?=$sno?>" name="items[<?=$sno?>][userRightsId]"  type="hidden" value="<?=$row->ur_id?>" />
									</td>
									<td>	
										<input id="sessionNumber_<?=$sno?>" name="items[<?=$sno?>][sessionNumber]"  type="text" class="form-control text-center" value="<?=$row->mm_session_number?>" readonly />
									</td>
									<td style="text-align:center">
										<input id="view_<?=$sno?>" name="items[<?=$sno?>][view]" style="margin-left:0px"  type="checkbox" class="form-check-input view" <?php if($row->ur_view==1) echo 'checked';?> />	
									</td> 									
									<td style="text-align:center">	
										<input id="add_<?=$sno?>" name="items[<?=$sno?>][add]" style="margin-left:0px"  type="checkbox" class="form-check-input add" <?php if($row->ur_add==1) echo 'checked';?> />
									</td>
									<td style="text-align:center">
										<input id="edit_<?=$sno?>" name="items[<?=$sno?>][edit]" style="margin-left:0px"  type="checkbox" class="form-check-input edit" <?php if($row->ur_edit==1) echo 'checked';?> />
									</td> 	
								</tr>
							<?php $sno++; }?>
								
							</tbody>
						</table>
					</div>
					<div class="row py-2 px-3" style="justify-content: end;">
						<?php if($this->sessionArray[1046]->ur_edit==1)  {   ?>
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
$(document).on('change','.view',function(){
	if($(this).prop("checked"))
		checked=1;
	else
		checked=0;
	var id_arr = $(this).attr('id');
	var element_id = id_arr.split("_");
	id1 = element_id[1];
	type='view';
	menuId=$('#menuId_'+id1).val();
	updateUserRights(menuId,type,checked);
});
$(document).on('change','.add',function(){
	if($(this).prop("checked"))
		checked=1;
	else
		checked=0;
	var id_arr = $(this).attr('id');
	var element_id = id_arr.split("_");
	id1 = element_id[1];
	type='add';
	menuId=$('#menuId_'+id1).val();
	updateUserRights(menuId,type,checked);
});
$(document).on('change','.edit',function(){
	if($(this).prop("checked"))
		checked=1;
	else
		checked=0;
	var id_arr = $(this).attr('id');
	var element_id = id_arr.split("_");
	id1 = element_id[1];
	type='edit';
	menuId=$('#menuId_'+id1).val();
	updateUserRights(menuId,type,checked);
});

function updateUserRights(id,type,checked)
{
	var usergroup=$('#usergroup').val();
	$.ajax({
				url : '<?=base_url()?>master/updateUserRights',
				dataType: "json",
				method: 'post',
				data: {
				   usergroup: usergroup,id:id,type:type,checked:checked
				},
				success: function( data ) {
					console.log('success');
				}
	});
}
</script>

	