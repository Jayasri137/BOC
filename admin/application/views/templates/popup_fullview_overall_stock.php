<html>
<head>
<title>Ahattrickz</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="viewport" content="width=device-width, initial-scale=1,maximum-scale=1,user-scalable=no">
<link rel="stylesheet" href="<?=base_url();?>assets/css/bootstrap.min.css" />
 <!--   <link rel="stylesheet" href="<?=base_url();?>assets/css/datatables.min.css">-->
  <link rel="stylesheet" href="<?=base_url();?>assets/css/lib/jquery-ui/jquery-ui.css" />
  <link rel="stylesheet" href="<?=base_url();?>assets/css/bootstrap-select.min.css" />
  <link rel="stylesheet" href="<?=base_url();?>assets/css/bootstrap-datetimepicker.min.css" />
  <script src="<?=base_url();?>assets/js/jquery.min.js"></script>
  <script src="<?=base_url();?>assets/js/tether.min.js"></script>
  <!--script src="<?=base_url();?>assets/js/bootstrap.min.js"></script-->
  <script src="<?=base_url();?>assets/js/datatables.min.js"></script>
  <script src="<?=base_url();?>assets/js/bootstrap-datetimepicker.min.js"></script>
  <script src="<?=base_url();?>assets/js/wickedpicker.js"></script>
  <link rel="stylesheet" href="<?=base_url();?>assets/css/pikaday.css" />
  <link rel="stylesheet" href="<?=base_url();?>assets/css/site.css" />
  <link rel="stylesheet" href="<?=base_url();?>assets/css/dataTables.bootstrap.min.css">
 
  <link rel="stylesheet" href="<?=base_url();?>assets/css/respansive.css" />
  <link rel="stylesheet" href="<?=base_url();?>assets/css/wickedpicker.css" />
  <link rel="stylesheet" href="<?=base_url();?>assets/css/bootstrap.min.css">
  
 
  <link rel="stylesheet" href="<?=base_url();?>assets/css/style.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
  <script type="text/javascript" src="<?=base_url();?>assets/js/jquery.scannerdetection.js"></script>
  <script type="text/javascript" src="<?=base_url();?>assets/js/jquery.validate.min.js"></script>
  <script src="<?=base_url();?>assets/js/jquery.form.js"></script>

</head>
<body>
<style>
table.dataTable tr.odd { background-color:  red; }
table.dataTable tr.even { background-color: green;  }
.fottops{
	margin-top: 50px;
    border-top: 1px solid #b87c1d;
}
</style>

<div class="col-sm-12" style="background-color:#27ae60;color: #fbe9e9;margin-top:10px;">
	<h4>
		Branch wise stock report as on date time
	</h4>
</div>

<!--div style="padding:10px;" class="pull-right">
</div-->
<form class="form-horizontal"  method="post" id="chequeStatus" name="chequeStatus" role="form">
<div class="row" style="background: #d0d2d0;color:#806b6b;margin-bottom:200px;padding:20px;">
	<div class="col-sm-12">
		<div style="margin-left:200px;">
			<div class="form-group col-sm-12" style="margin-top:5px;">
				<div class="col-sm-3">
					<label class="form-control-label" style="padding-top:15px;margin-left:100px;">Product Name</label>
				</div>
				<div class="col-sm-5" style="margin-left:-20px;">
					<input  id="productsListID" name="productsListID" type="hidden" class="form-control">
					<input  id="productsList" name="productsList" type="text" class="form-control" autocomplete="off">
				</div>
			</div>
			
			<div class="form-group col-sm-12 " style="margin-top:-30px;">
				<div class="col-sm-3">
					<label class="form-control-label" style="padding-top:15px;margin-left:100px;">Weight From</label>
				</div>
				<div class="col-sm-5" style="margin-left:-20px;">
					<input  id="weightfrom" name="weightfrom" type="text" class="form-control changeProduct threedecimal" autocomplete="off" onkeypress="return isNumber(event)">
				</div>
			</div>
			
			<div class="form-group col-sm-12" style="margin-top:-30px;">
				<div class="col-sm-3">
					<label class="form-control-label" style="padding-top:15px;margin-left:100px;">Weight To</label>
				</div>
				<div class="col-sm-5" style="margin-left:-20px;">
					<input  id="weightto" name="weightto" type="text" class="form-control changeProduct threedecimal" autocomplete="off" onkeypress="return isNumber(event)">
				</div>
			</div>
			<div class="form-group col-sm-12" style="margin-top:-30px;">
				<div class="col-sm-3">
					<label class="form-control-label" style="padding-top:15px;margin-left:100px;">Branch</label>
				</div>
				<div class="col-sm-5" style="margin-left:-20px;">
					<select  class="form-control changeProduct" id="branch" name="branch" >
					<?php $branch=$this->db->select('id,name')->from('branch')->where('status',1)->get()->result();
						foreach($branch as $row){
					?>
						<option value="<?=$row->id?>"><?=$row->name?></option>
						<?php } ?>	
					</select>
				</div>
			</div>
			<div class="form-group col-sm-12" style="margin-top:-30px;">
				<div class="col-sm-3">
					<label class="form-control-label" style="padding-top:15px;margin-left:100px;">Display</label>
				</div>
				<div class="col-sm-5" style="margin-left:-20px;">
					<select class="form-control changeProduct" id="viewtype" name="viewtype" onchange="getStockDetailsByAll()" >
						<option value="1">Total</option>
						<option value="2">Barcode Wise</option>
					</select>
				</div>
			</div>
		</div>
		<div>
			<div class="form-group row">
				<div class="col-sm-12" style="border-top:1px solid #d8e2e7;padding-bottom:10px;padding-top:5px;" id="getStockList" >
			 
				</div>
					
				<div class="col-sm-12 text-center" style="margin-bottom:10px;">
					<button type="submit" class="btn btn-danger" data-dismiss="modal" >Cancel</button>
					
				</div>
			</div>
		</div>
		
</div>
</form>
<script>
$(document).on('keyup','#productsList',function(){
	var weightfrom=$('#weightto').val();
	var weightto=$('#weightto').val();
	
	var id_arr = $(this).attr('id');
	var element_id = id_arr.split("_");
	id1 = element_id[1];
	$(this).autocomplete({
		source: function( request, response ) {
			$.ajax({
				url : '<?=base_url()?>Ajax/getProduct',
				dataType: "json",
				method: 'post',
				data: {
				   name: request.term
				},
				success: function( data ) {
					//$('.ui-autocomplete').addClass('uiCustomWidth');
					//$('.ui-autocomplete').css('z-index', '900');
						if(!data.length){
						  var result = [{label: 'No matches found'}];
						   response(result);
						 }
						 else{
						 response( $.map( data, function( item ) {
						 	var code = item.split("|");
							return {
								label: code[2]+' -  '+code[1],
								value: code[1],
								data : item
							}
						}));
					
					}
				}
			});
		},
		autoFocus: true,
		minLength: 0,
		select: function( event, ui ) {
			if( typeof ui.item.data !== "undefined" ){
				var names = ui.item.data.split("|");
				$('#productsListID').val(names[0]);
				getStockDetailsByAll();
					}else{
				return false;
			}
		}
	});
});	
$(document).on('change','.changeProduct',function(){
	getStockDetailsByAll();
});
</script>
</div>

</html>