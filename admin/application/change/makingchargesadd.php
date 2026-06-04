<?php
if($makingchargesadd['status']==200) 
{
	$headerstatus=200;
	$category=$makingchargesadd['result']->wmc_product_category_id;
	$subcategory=$makingchargesadd['result']->wmc_product_sub_category_id;
	$product=$makingchargesadd['result']->wmc_product_id;
	$weightfrom=$makingchargesadd['result']->wmc_weight_from;
	$weightto=$makingchargesadd['result']->wmc_weight_to;
	
	$mcpcs=$makingchargesadd['result']->wmc_mrate_pcs;
	$mcgram=$makingchargesadd['result']->wmc_mrate_gram;
	$mcpercentage=$makingchargesadd['result']->wmc_mrate_percentage;
	$wgram=$makingchargesadd['result']->wmc_wrate_gram;
	$wpercentage=$makingchargesadd['result']->wmc_wrate_percentage;
	$type="Edit";
}
else
{
	$headerstatus=400;
	$category='';
	$subcategory='';
	$product='';
	$weightfrom='';
	$weightto='';
	
	$mcpcs='';
	$mcgram='';
	$mcpercentage='';
	$wgram='';
	$wpercentage='';
	$type="Add";
	
}

?>
<div class="col-sm-12" style="margin-top:25px;padding-left:30px;">	
		
			<div class="row">
				<div class="btn-group btn-breadcrumb">
					<a href="<?=base_url();?>master/home" class="btn btn-primary"><i class="glyphicon glyphicon-home"></i></a>
					 
					<a href="<?=base_url();?>master/makingcharges" class="btn btn-info">Making Charges</a>
					<a href="#" class="btn btn-success" style="box-shadow: none;"><?=$type?></a>
					<!--a href="#" class="btn btn-warning">Product Sub Category</a-->
				</div>
			</div>

</div><!--end of col-sm-12-->

<!--start tabe-->
<div class="col-sm-12" style="margin-top:25px;padding-left:30px;border: 1px solid #b87c1d;">	
	<!--row 1-->	
			<!--start left side-->
		<div class="row" style="padding-top:10px;">
		<form name="makingcharges" id="makingcharges" action="<?php echo base_url();?>master/makingchargesadd/<?=$id?>" method="POST">
			<div class="row"> 
				<div class="col-sm-12" style="padding-left:100px;padding-right:100px;">
				 	
					<!--start Category-->
						<div class="row">
							<div class="col-sm-12">
								<div class="col-sm-5">
									<p class="addpara">Category <span class="required">*</span></p>
								</div><!--end of col-sm-5-->
								
								<div class="col-sm-7">
                                <select required="" name="category" id="category" onchange="getProductSubCategory(this.value)" class="form-control customvalidblur">
                                <option value="">Select</option>
                                <?php 
                                $cat=$this->db->query("select * from product_category where status='1'");    
                                foreach($cat->result() as $row){  
								$selected='';
									if($category==$row->id)
										$selected='selected';
								                          ?>
                                <option value="<?php echo $row->id;?>" <?=$selected?>><?php echo $row->name;?></option>
                                <?php } ?>
                                </select>
								 
								</div><!--end of col-sm-7-->
							</div><!--end of col-sm-12-->
						</div><!--end of row-->
					<!--end Category-->
                    
                    <!--start Sub Category name-->
						<div class="row">
							<div class="col-sm-12">
								<div class="col-sm-5">
									<p class="addpara">Sub Category</p>
								</div><!--end of col-sm-5-->
								
								<div class="col-sm-7">
                                         <select  name="subcategory" id="subcategory" class="form-control" onchange="getProduct(this.value)">
                                            <option value="">Select</option>
                                            <?php 
											
											if($makingchargesadd['status']==200)  {
											foreach($makingchargesadd['subcategory']->result() as $row){  
												$selected='';
												if($subcategory==$row->id)
													$selected='selected';
											 ?>
											<option value="<?php echo $row->id;?>" <?=$selected?>><?php echo $row->name;?></option>
											<?php   } }  ?>
                                        </select>
								</div><!--end of col-sm-7-->
							</div><!--end of col-sm-12-->
						</div><!--end of row-->
					<!--end Sub Category name-->
                
						<!--start Category-->
						<div class="row">
							<div class="col-sm-12">
								<div class="col-sm-5">
									<p class="addpara">Product </p>
								</div><!--end of col-sm-5-->
								
								<div class="col-sm-7">
                                <select  name="product" id="product" class="form-control product ">
									<option value="">Select</option>
									 <?php 
									 if($makingchargesadd['status']==200)  {
											foreach($makingchargesadd['product']->result() as $row){  
												$selected='';
												if($product==$row->id)
													$selected='selected';
											 ?>
											<option value="<?php echo $row->id;?>" <?=$selected?>><?php echo $row->name;?></option>
											<?php  } }   ?>
                                </select>
								</div><!--end of col-sm-7-->
							</div><!--end of col-sm-12-->
						</div><!--end of row-->
						
						<div class="row">
							<div class="col-sm-12">
								<div class="col-sm-5">
									<p class="addpara">Weight From <span class="required">*</span></p>
								</div><!--end of col-sm-5-->
								
								<div class="col-sm-7">
                                
							     <input type="text" name="weightfrom" id="weightfrom" value="<?=$weightfrom?>" required="" onkeypress="return isNumber(event)" class="form-control customvalidblur" readonly />
								</div><!--end of col-sm-7-->
							</div><!--end of col-sm-12-->
						</div><!--end of row-->
						
						<div class="row">
							<div class="col-sm-12">
								<div class="col-sm-5">
									<p class="addpara">Weight To <span class="required">*</span></p>
								</div><!--end of col-sm-5-->
								
								<div class="col-sm-7">
                                
							     <input type="text" name="weightto" id="weightto" value="<?=$weightto?>" required="" onkeypress="return isNumber(event)" class="form-control customvalidblur"/>
								</div><!--end of col-sm-7-->
							</div><!--end of col-sm-12-->
						</div>
						<!--end of row-->
				</div><!--end of col-sm-6-->
			</div>	
			<div class="row" style="padding-left:85px;padding-right:85px;">
				<div class="col-sm-6">
				 	<!--start Category-->
						<div class="row">
							<div class="col-sm-12">
								<div class="col-sm-5">
									
								</div><!--end of col-sm-5-->
								
								<div class="col-sm-7">
                                <p class="addpara">MAKING CHARGES</p>
								</div><!--end of col-sm-7-->
							</div><!--end of col-sm-12-->
						</div><!--end of row-->
					<!--end Category-->
					
						
						<div class="row">
							<div class="col-sm-12">
								<div class="col-sm-5">
									<p class="addpara">Rate/Pcs </p>
								</div><!--end of col-sm-5-->
								
								<div class="col-sm-7">
                                
							     <input type="text" name="mcpcs" id="mcpcs" value="<?=$mcpcs?>"  onkeypress="return isNumber(event)" class="form-control customvalidblur rate makingcharge"/>
								</div><!--end of col-sm-7-->
							</div><!--end of col-sm-12-->
						</div><!--end of row-->
						
						<div class="row">
							<div class="col-sm-12">
								<div class="col-sm-5">
									<p class="addpara">Rate/Gram </p>
								</div><!--end of col-sm-5-->
								
								<div class="col-sm-7">
                                
							     <input type="text" name="mcgram" id="mcgram" value="<?=$mcgram?>"  onkeypress="return isNumber(event)" class="form-control customvalidblur rate makingcharge"/>
								</div><!--end of col-sm-7-->
							</div><!--end of col-sm-12-->
						</div>
						<!--end of row-->
						
                     	<div class="row">
							<div class="col-sm-12">
								<div class="col-sm-5">
									<p class="addpara">Rate in % </p>
								</div><!--end of col-sm-5-->
								
								<div class="col-sm-7">
                                
							     <input type="text" name="mcpercentage" id="mcpercentage" value="<?=$mcpercentage?>"  onkeypress="return isNumber(event)" class="form-control customvalidblur rate makingcharge"/>
								</div><!--end of col-sm-7-->
							</div><!--end of col-sm-12-->
						</div><!--end of row-->
    		 	</div><!--end of col-sm-6-->
				<div class="col-sm-6">
				 	<!--start Category-->
						<div class="row">
							<div class="col-sm-12">
								<div class="col-sm-5">
									
								</div><!--end of col-sm-5-->
								
								<div class="col-sm-7">
                                <p class="addpara">WASTAGE </p>
								</div><!--end of col-sm-7-->
							</div><!--end of col-sm-12-->
						</div><!--end of row-->
					<!--end Category-->
					
						
						<!--start of row-->
						<div class="row">
							<div class="col-sm-12">
								<div class="col-sm-5">
									<p class="addpara">Wastage/Pcs</p>
								</div><!--end of col-sm-5-->
								
								<div class="col-sm-7">
                                
							     <input type="text" name="wgram" id="wgram" value="<?=$wgram?>" required="" onkeypress="return isNumber(event)" class="form-control customvalidblur rate wastage"/>
								</div><!--end of col-sm-7-->
							</div><!--end of col-sm-12-->
						</div>
						<!--end of row-->
						<!--start of row-->
						<div class="row">
							<div class="col-sm-12">
								<div class="col-sm-5">
									<p class="addpara">Wastage in %</p>
								</div><!--end of col-sm-5-->
								
								<div class="col-sm-7">
                                
							     <input type="text" name="wpercentage" id="wpercentage" value="<?=$wpercentage?>" required="" onkeypress="return isNumber(event)" class="form-control customvalidblur rate wastage"/>
								</div><!--end of col-sm-7-->
							</div><!--end of col-sm-12-->
						</div><!--end of row-->
						<!--end of row-->
						
						
                </div><!--end of col-sm-6-->
			</div>	
			<!--end right side-->
		</div><!--end of row-->
	<!--end row 1-->	
</div><!--end of col-sm-12-->	
<!--end tabe-->	


<div class="col-sm-12" style="border-top:1px solid #333;margin-bottom:10px;">
	<input type="button" id="btnReturn"  value="Return" class="mainsub">
	<input type="button" id="btnReset" value="Reset" class="mainsub">
	<?php if(($headerstatus==200 && $this->sessionArray[1032]->ur_edit==1) || ($headerstatus!=200 && $this->sessionArray[1032]->ur_add==1))  {   ?>
		<input type="button" id="btnSubmit" value="Submit" class="mainsub">
	<?php } ?>
	
</div><!--end of col-sm-12-->
</form>
</div>

</div><!--end of row-->			
</div><!--end of col-sm-12-->
</div><!--end of col-sm-12-->	
<script>
$(document).on('blur','.customvalidblur',function(){
	checkMakingCharges();
	customValidateForm();
	
});
$(document).on('blur','#mcpcs',function(){
		$('#mcgram').val('');
		$('#mcpercentage').val('');
});
$(document).on('blur','#mcgram',function(){
		$('#mcpcs').val('');
		$('#mcpercentage').val('');
});
$(document).on('blur','#mcpercentage',function(){
		$('#mcpcs').val('');
		$('#mcgram').val('');
		
});
$(document).on('blur','#wgram',function(){
		$('#wpercentage').val('');
});
$(document).on('blur','#wpercentage',function(){
		$('#wgram').val('');
});

function validateMakingCharge(val)
{
	var mcpcs=parseFloat($('#mcpcs').val());
	var mcgram=parseFloat($('#mcgram').val());
	var mcpercentage=parseFloat($('#mcpercentage').val());
	if(mcpcs>0)
	{
		
	}

	if(mcgram>0)
	{
		$('#mcgram').val(val);
		$('#mcpcs').val('');
		$('#mcpercentage').val('');
	}
	if(mcpercentage>0)
	{
		$('#mcpercentage').val(val);
		$('#mcpcs').val('');
		$('#mcgram').val('');
	}
}
function customValidateForm()
{
	$('.customvalidblur').css('border','1px solid rgb(187, 126, 24)');
	var category=$('#category').val();
	var weightfrom=parseFloat($('#weightfrom').val());
	var weightto=parseFloat($('#weightto').val());
	var mcpcs=parseFloat($('#mcpcs').val());
	var mcgram=parseFloat($('#mcgram').val());
	var mcpercentage=parseFloat($('#mcpercentage').val());
	var wgram=parseFloat($('#wgram').val());
	var wpercentage=parseFloat($('#wpercentage').val());
	if(!category>0)
	{
		$('#category').css('border','2px solid red');
		return false;
	}
	if(!(weightfrom>0))
	{
		$('#weightfrom').css('border','2px solid red');
		return false;
	}
	if(!(weightto>0 && weightto>=weightfrom))
	{
		$('#weightto').css('border','2px solid red');
		return false;
	}
	if(!(mcpcs>0 || mcgram>0 || mcpercentage>0))
	{
		$('#mcpcs').css('border','2px solid red');
		$('#mcgram').css('border','2px solid red');
		$('#mcpercentage').css('border','2px solid red');
		return false;
	}
	flag=checkMakingCharges();
	if(flag==0)
		return false;	
	return true;
}
$(document).on('change','#subcategory',function(){
	getMCWeightFrom();
});
$(document).on('change','#category',function(){
	getMCWeightFrom();
});
$(document).on('change','#product',function(){
	getMCWeightFrom();
});
$(document).on('blur','#weightto',function(){
	var val=$(this).val();
	if(val>0)
		$(this).val(parseFloat(val).toFixed(3));
	else
		$(this).val('0.000');
});
$(document).on('blur','.rate',function(){
	var val=$(this).val();
	if(val>0)
		$(this).val(parseFloat(val).toFixed(2));
	else
		$(this).val('0.000');
});
function getMCWeightFrom()
{
	var category=$("#category").val();
	var subcategory=$("#subcategory").val();
	var product=$("#product").val();
	 $.ajax
        ({
        type: "POST",
        url: base_url+'MasterAjax/getMCWeightFrom',
        data:{category:category,subcategory:subcategory,product:product},
        success: function(msg)
        {
			console.log(msg);
            try{  
				$('#weightfrom').val(msg);
              }catch (e)
            {
                showMessage('Server Error');
            }
            
        },
		error: function(e){						
			showMessage('Server Error');
		}
    });
}
function checkMakingCharges(id=0)
{
	 $.ajax
        ({
        type: "POST",
        url: base_url+'MasterAjax/checkMakingCharges',
        data:$('#makingcharges').serialize()+"&id="+<?=$id?>,
        success: function(msg)
        {
			console.log(msg);
            try{  
				if(msg==1)	
					return 1;
				else
				{
					showMessage('Weight Already Exists');
					return 0;
				}
					
              }catch (e)
            {
                showMessage('Server Error');
            }
            
        },
		error: function(e){						
			showMessage('Server Error');
		}
    });
}

$(document).on('click','#btnSubmit',function (e){
	
	if(customValidateForm())
	{
		
			$('#btnSubmit').prop('disabled',true);
			var form = $('form')[0]; // You need to use standart javascript object here
			var formData = new FormData(form); 
			var url=form.action;
			$.ajax({
						url:url,
						type:'POST',
						data:formData,
						contentType:false,
						cache:false,
						processData:false,
						dataType:'json',
						success:function(result){
							console.log(result);
							showMessage(result.message);
							if(result.status==200)
							{
								$('#btnSubmit').prop('disabled',true);
								showMessageurlcustom("Save Successfully",'master/makingchargesadd');
							}
							else
							{
								$('#btnSubmit').prop('disabled',false);
								showMessage("Something Wrong Check");
							}
						},
					error:function(e){
						showMessage('Server Error');
						$('#btnSubmit').prop('disabled',false);
						}
					});
		
	}
});
$(document).on('click','#btnReset',function (e){
	location.reload();
});
$(document).on('click','#btnReturn',function (e){
	window.location.href = "<?=base_url()?>master/home";
});
// Get Product Sub Category
</script>

	