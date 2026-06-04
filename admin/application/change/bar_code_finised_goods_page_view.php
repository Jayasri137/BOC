<div class="page-content">
	<div class="container-fluid">
		<div class="col-sm-12" style="margin-top:25px;padding-left:30px;">	
				<div class="row">
					<div class="btn-group btn-breadcrumb">
						<a href="<?=base_url();?>master/home" class="btn btn-primary"><i class="glyphicon glyphicon-home"></i></a>
						<a href="#" class="btn btn-info">Barcode</a>
					</div>
				</div>
		</div><!--end of col-sm-12-->
			<br>
			<br>
			<br>
			<?php
				if(isset($_GET['grnno']))
				{
					$grnno=$_GET['grnno'];
				}
				else
				{
					$grnno=0;
				}
				
			?>
			<form target="_self" class="form-horizontal" action="<?= base_url();?>Sales/barCodeFinishedGoodsAjax" target="_blank" method="post" id="enquiryForm" role="form">
				<div class="box-typical box-typical-padding">
					<header class="box-typical-header">
						<div class="tbl-row">
							<div class="tbl-cell tbl-cell-title">
								<h3>BARCODE PRINT</h3>
							</div>
							<div class="tbl-cell tbl-cell-actions">
								<button type="button" class="action-btn action-btn-expand">
									<i class="font-icon font-icon-expand"></i>
								</button>
							</div>
						</div>
					</header>
					<div class="row" style="padding-left: 200px;padding-right: 500px;">
						<div class="col-sm-4">
							<div class="tophead">Print Type<span class="required">*</span></div>
						</div><!--end of col-sm-4-->
				
						<div class="col-sm-6" style="margin-top: -25px;">
							<div class="form-group-row branchtd">
								<select required="" name="type"  id="type"  class="form-control customvalidblur" onchange="updateDataTable()">
									<option value="0">Box Tag</option>
									<option value="1">Tail Tag</option>
								</select>
									<input  name="grnno" id="grnno" value="<?php echo $grnno;?>" type="hidden" class="form-control staff-bx" placeholder=""/>
							</div>
						</div><!--end of col-sm-8-->
						<div class="col-sm-2" style="margin-top: -17px;">
							<div class="form-group-row branchtd">
							<?php if($this->sessionArray[1037]->ur_add==1)  {   ?>
								<button type="submit"  id="go" name="submits" value="2" style="width:50px;height:37px;" class="btn btn-sm btn-primary submit" >Go</button>
							<?php } ?>		
							</div>
						</div><!--end of col-sm-8-->
					</div>
					<div class="row" style="padding-left: 200px;padding-right: 500px;">
						<div class="col-sm-4">
							<div class="tophead">Barcode<span class="required">*</span></div>
						</div><!--end of col-sm-4-->
				
						<div class="col-sm-6" style="margin-top: -25px;">
							<div class="form-group-row branchtd">
								<select required="" name="printtype"  id="printtype"  class="form-control customvalidblur" onchange="updateDataTable()">
									<option value="0">Whole</option>
									<option value="1">Partial</option>
								</select>
							</div>
						</div><!--end of col-sm-8-->
					</div>
					
					<div class="row" style="padding-left: 200px;padding-right: 500px;display:none;" id="checkallDisplay">
						<div class="col-sm-4">
							<div class="tophead">Check All</div>
						</div><!--end of col-sm-4-->
				
						<div class="col-sm-6" style="margin-top: -25px;">
							<div class="form-group-row branchtd">
								<input class="form-control" name="checkall" type="checkbox" id="checkall" value="1" >
							</div>
						</div><!--end of col-sm-8-->
					</div>
					<input type="hidden" name="id" value="2">
					<table id="example" class="table table-striped table-bordereds nowrap" cellspacing="0" width="100%" style="padding-top:0px;padding-bottom:0px;">
						<thead>	
								<tr>
									<th width="5%">#</th>
									<th width="20%" style="text-align:center;" >Product</th>
									<th width="20%" style="text-align:center;" >Bar Code</th>
									<th width="20%" style="text-align:center;" >Weight/Rate</th>
								</tr>
						</thead>
					
					</table>
				</div><!--.container-fluid-->
				<div class="form-group row" style="margin-top:100px;">
					<div class="col-sm-12 text-center">
					<?php if($this->sessionArray[1037]->ur_add==1)  {   ?>
						<button type="submit"  id="print" name="submits" value="2" style="width:100px;" class="btn btn-primary submit" >Print</button>
					<?php } ?>	
					<!--	<button type="submit"  id="email" name="submits" value="3" style="width:100px;" class="btn btn-primary" >Email</button>-->
						<a type="cancel" 	   style="width:100px;"  class="btn btn-danger" onclick="javascript:window.location='<?=base_url();?>Sales/openingProduct?mode=1';">Return</a>
					</div>
				</div>
			</form>	
	</div><!--.page-content-->
</div>	

<script>
$(document).on('click','.submit',function(){
	updateDataTable();
	//submitEnquiry();
});

$(document).on('change','.weight',function(){
	id_attr=$(this).attr('id');
	elementid=id_attr.split('_');   
	id=parseInt(elementid[1]);
	changeWeightSize(id,1);
});

$(document).on('change','.size',function(){
	id_attr=$(this).attr('id');
	elementid=id_attr.split('_');   
	id=parseInt(elementid[1]);
	changeWeightSize(id,2);
});

$(document).on('change','#checkall',function(){
	var val=$(this).is(":checked");
	if(val==true)
	{
		 $('.checkall').attr('Checked','Checked'); 
	}
	else
	{
		 $('.checkall').removeAttr('Checked'); 
	}	
});

function changeWeightSize(id,columntype)
{
	var url='<?=base_url()?>TransactionAjax/updateWeightSize';
	var barcode=$('#barcode_'+id).val();
	var weight=$('#weight_'+id).val();
	var size=$('#size_'+id).val();
	var type=$('#type').val();
	var dataString='barcode='+barcode+'&weight='+weight+'&size='+size+'&type='+type+'&columntype='+columntype;
	$.ajax({
			url:url,
			type:'POST',
			data:dataString,
			success:function(result){
				console.log(result);
				if(result.status==200)
				{
					
				}
			}
		});
}




function updateDataTable()
{
	type=$('#type').val();
	printtype=$('#printtype').val();
	if(type==2)
	{
		$('#size').show();
	}
	else
	{
		$('#size').hide();
	}
	if(printtype==1)
	{
		$('#checkallDisplay').show();
	}
	else
	{
		$('#checkallDisplay').hide();
	}
	$('.dataTable').each(function() {
			dt = $(this).dataTable();
			dt.fnDraw();
		});
}
$(document).ready(function() {
    var dataTable=$('.table').DataTable( {
        "processing": true,
        "serverSide": true,
		"aaSorting": [],
		"lengthMenu": [[10, 25, 50, 100,-1], [10, 25, 50,100,"ALL"]],
		"ajax": {
			"url":"<?=base_url()?>Sales/barCodeFinishedGoodsJson",
			"type":"POST",
			 "data": function ( d ) {
					d.type = $('#type').val(),
					d.grnno=$('#grnno').val(),
					d.printtype=$('#printtype').val(),
					d.checkall=$('#checkall').val()
				}
		},	
		"columnDefs": [
				{ "width": "10%", "targets": 0,className: "tdtexts" },
				{ "width": "30%", "targets": 1,className: "tdtextsleft" },
				{ "width": "30%", "targets": 2,className: "tdtexts"  },
				{ "width": "30%", "targets": 3,className: "tdtextsright"  },
				],
    } );
});

</script>
