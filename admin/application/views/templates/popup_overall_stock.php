<style>
table.dataTable tr.odd { background-color:  red; }
table.dataTable tr.even { background-color: green;  }
</style>
<div style="padding:10px;" class="pull-right">
</div>
<form class="form-horizontal"  method="post" id="chequeStatus" name="chequeStatus" role="form">
<div class="col-sm-12">
	<div class="form-group row">
		<label class="col-sm-3 form-control-label" style="padding-top:15px;">Product Name</label>
		<div class="col-sm-9">
		<input  id="productsListID" name="productsListID" type="hidden" class="form-control">
		<input  id="productsList" name="productsList" type="text" class="form-control" autocomplete="off">
		</div>
	</div>
</div>
<div class="col-sm-12" style="border-top:1px solid #d8e2e7;padding-bottom:10px;" id="getStockList">
 
</div>
<div class="col-sm-12 text-center">
  	<button type="submit" class="btn btn-danger" data-dismiss="modal" >Cancel</button>
	
</div>
</form>
<script>
$(document).on('keyup','#productsListss',function(){
	var serialNumber=1;
	
	var id_arr = $(this).attr('id');
	var element_id = id_arr.split("_");
	id1 = element_id[1];
	$(this).autocomplete({
		source: function( request, response ) {
					$('.ui-autocomplete').css('z-index', '700');
			$.ajax({
				url : '<?=base_url()?>Ajax/getInvoiceOverAllFinishedProductStock',
				dataType: "json",
				method: 'post',
				data: {
				   name: request.term,serialNumber:serialNumber
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
								label: code[2]+' -  '+code[3],
								value: code[5],
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
				$('#productsListID').val(names[1]);
				getStockDetailsByAll(names[1]);
					}else{
				return false;
			}
		}
	});
});	

</script>