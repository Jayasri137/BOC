<!--start footer-->
		<div class="col-sm-12 fottop">	
			<div class="col-sm-12 ftpara">
				<p><img src="<?=base_url();?>assets/css/img/footer-logo.png"><a href="http://www.macsinfosolution.com/" target="_blank">A MACS'ian Product</a></p>
			</div><!--end of col-sm-12-->
		</div><!--end of col-sm-12-->
		<div id="modal_ajax" class="modal fade custom-content" role="dialog">
		  <div class="modal-dialog">
			<!-- Modal content-->
			<div class="modal-content">
			  <div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 id="myModalLabel" class="modal-title"></h4>
			  </div>
			  <div class="modal-body"></div>
			  <div class="modal-footer"></div>
			</div>
		  </div>
		</div>
		
		<div id="dialog-confirm" title="Message" class="text-center"  style="display:none;">
			<p><span  class="ui-icon ui-icon-alert" style="float:left; margin:12px 12px 20px 0;"></span></p>
		</div>
		
	<!--end footer-->
</div><!--end of col-sm-12-->
</div><!--end of col-sm-12--><!--over all full cover-->



<!-- Modal -->
<div id="modal_ajax_medium" class="modal fade custom-content" role="dialog">
  <div class="modal-dialog modal-lg" style="width: 90%;">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 id="myModalLabel" class="modal-title"></h4>
      </div>
      <div class="modal-body"></div>
      <div class="modal-footer"></div>
    </div>
  </div>
</div>
 
  <style>
	
  </style>
<script>
var base_url="<?php echo base_url();?>";</script>
<!--responsive table-->

<!--end responsive table-->


<script src="<?=base_url();?>assets/js/bootstrap.min.js"></script>
<script src="<?= base_url();?>assets/js/jquery.dataTables.min.js"></script>
<script src="<?= base_url();?>assets/js/dataTables.buttons.min.js"></script>
<script src="<?= base_url();?>assets/js/dataTables.responsive.min.js"></script>
<script src="<?= base_url();?>assets/js/dataTables.select.min.js"></script>
<script type="text/javascript" src="<?= base_url();?>assets/js/altEditor/dataTables.altEditor.free.js"></script>

 <!--start 02.03.17-->
<script src="<?=base_url();?>assets/js/jquery-ui.js"></script>
<!--script src="<?= base_url();?>assets/js/lib/datatables-net/jquery.dataTables.min.js"></script>
<script src="<?= base_url();?>assets/js/lib/bootstrap-notify/bootstrap-notify.min.js"></script>
<script src="<?= base_url();?>assets/js/lib/colorbox/jquery.colorbox-min.js"></script>
<!--link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.6.1/css/bootstrap-datepicker.min.css"/-->



<link rel="stylesheet" type="text/css" href="<?= base_url();?>assets/css/bootstrap-datepicker.min.css" />
<!--<script src="<?= base_url();?>assets/js/jquery-1.12.3.js"></script>-->
<!--script src="<?= base_url();?>assets/js/bootstrap.min.js"></script-->

<script src="<?= base_url();?>assets/js/dataTables.bootstrap.min.js"></script>

<script src="<?= base_url();?>assets/js/responsive.bootstrap.min.js"></script>
 <script type="text/javascript" src="<?= base_url();?>assets/js/bootstrap-datepicker.min.js"></script> 
 <script type="text/javascript" src="<?= base_url();?>assets/js/bootstrap-datepicker.de.min.js"></script> 
 <script src="<?= base_url();?>assets/js/jquery.validate.min.js"></script>
 
 <!--<script src="<?= base_url();?>assets/js/masterJs.js"></script>-->
  <script src="<?= base_url();?>assets/js/Master1.js"></script>
  <!--<script src="<?= base_url();?>assets/js/Transaction.js"></script>-->
  <script src="<?= base_url();?>assets/js/Transaction.js"></script>
  <script type="text/javascript" src="<?= base_url();?>assets/js/paging.js"></script> 
 <!--start 02.03.17-->
 
    <script type="text/javascript">
        $(document).ready(function() {
			$('#example').DataTable();
		} );
    </script>
	<script>
	$(function () {
       $(document).keyup(function (e) {
           var key = (e.keyCode ? e.keyCode : e.charCode);
           switch (key) {
               
               case 113:
					window.open(
						  '<?= base_url();?>Sales/invoice?mode=1&inv=&enq=&so',
						  '_blank' // <- This is what makes it open in a new window.
						);
                   //navigateUrl($('a[id$=lnk3]'));
                   break;
               case 115:
				   window.open(
						  '<?= base_url();?>Ajax/get_fullview_overall_stock',
						  '_blank' // <- This is what makes it open in a new window.
						);
					//	showAjaxModal('<?= base_url();?>Ajax/get_overall_stock','Stock List');
                    //$('#myModal').modal('toggle');
					//$('#myModal').modal('show');
                   break;
				case 120:
					window.open(
						  '<?= base_url();?>Welcome/exitLogout',
						  '_self' // <- This is what makes it open in a new window.
						);
					//navigateUrl($('a[id$=lnk3]'));
                   break;
                default: ;
           }
       });
});  
function showAjaxModal(url,title)
{
	// SHOWING AJAX PRELOADER IMAGE
		jQuery('#modal_ajax .modal-body').html('<div style="text-align:center;"><img src="<?=base_url()?>assets/images/preloader.gif" /></div>');
		
		// LOADING THE AJAX MODAL
		jQuery('#modal_ajax').modal('show', {backdrop: 'true'});
		
		// SHOW AJAX RESPONSE ON REQUEST SUCCESS
		$.ajax({
			url: url,
			success: function(response)
			{
				jQuery('#modal_ajax .modal-title').html(title);
				jQuery('#modal_ajax .modal-body').html(response);
			}
		});
}
function showAjaxModalMedium(url,title)
{
	// SHOWING AJAX PRELOADER IMAGE
		jQuery('#modal_ajax_medium .modal-body').html('<div style="text-align:center;"><img src="<?=base_url()?>assets/images/preloader.gif" /></div>');
		
		// LOADING THE AJAX MODAL
		jQuery('#modal_ajax_medium').modal('show', {backdrop: 'true'});
		
		// SHOW AJAX RESPONSE ON REQUEST SUCCESS
		$.ajax({
			url: url,
			success: function(response)
			{
				jQuery('#modal_ajax_medium .modal-title').html(title);
				jQuery('#modal_ajax_medium .modal-body').html(response);
			}
		});
}
/*$(document).on('keyup','#productsList',function(){
	var id_arr = $(this).attr('id');
	var element_id = id_arr.split("_");
	id1 = element_id[1];
	$(this).autocomplete({
		source: function( request, response ) {
			$.ajax({
				url : '<?=base_url()?>Ajax/getInvoiceOverAllFinishedProductStock',
				dataType: "json",
				method: 'post',
				data: {
				   name: request.term
				},
				success: function( data ) {
					//$('.ui-autocomplete').addClass('uiCustomWidth');
					//$('.ui-autocomplete').css('z-index', '999');
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
*/
function getStockDetailsByAll(id,weightfrom,weightto)
{	
	  var id=$('#productsListID').val();
	  var weightfrom=$('#weightfrom').val();
	  var weightto=$('#weightto').val();
	  var viewtype=$('#viewtype').val();
	  var dataString = 'product='+id+'&weightfrom='+weightfrom+'&weightto='+weightto+'&viewtype='+viewtype;
	   //alert(dataString);
	   $.ajax({
	   type:'POST',
	   url:'<?php echo base_url();?>Ajax/get_fullview_allStock_Details',
	   data:dataString,
	   dataType:'html',
	   cache:false,
	   success:function(todo)
	   { 
	   
		  if(todo !=0)
				  {
					   $('#getStockList').html(todo);                                     
				  } 
				else{
					$('#getStockList').html('');   
				}	
		}
		});
}
		$(document).ready(function(){
		   $('[data-toggle="offcanvas"]').click(function(){
			   $("#navigation").toggleClass("hidden-xs");
		   });
		});
		 $( ".datepicker" ).datepicker({
		  format:"dd-mm-yyyy",
		  "setDate": new Date(),
		  "autoclose": true,
		  todayHighlight: true
		 });
		 
		 $( ".disablepastdate" ).datepicker({
			 format:"dd-mm-yyyy",
		     "setDate": new Date(),
			 "autoclose": true,
			  todayHighlight: true,
		     startDate: new Date() 
		 });
		 
	
		
		
</script>
	
<!--start calender function-->
<script>

	$(document).ready(function() {
		$('#chg_branch').on('change',function()
		{
			var data=$('option:selected', '#chg_branch').val();
			var name=$('option:selected', this).attr('data');
			$.ajax({
				url : '<?=base_url()?>Sales/chg_branch',
				dataType: "json",
				method: 'post',
				data: {
				   branch: data,
				   name:name
				},
				success: function( data ) {
					
				}
			});
		});
		
	/*
		$('#calendar').fullCalendar({
			header: {
				left: 'prev,next today',
				center: 'title',
				right: 'month,agendaWeek,agendaDay'
			},
			defaultDate: '2016-06-12',
			editable: true,
			selectable: true,
			eventLimit: true, // allow "more" link when too many events
			events: {
				url: 'php/get-events.php',
				error: function() {
					$('#script-warning').show();
				}
			},
			loading: function(bool) {
				$('#loading').toggle(bool);
			},
			eventRender: function(event, el) {
				// render the timezone offset below the event title
				if (event.start.hasZone()) {
					el.find('.fc-title').after(
						$('<div class="tzo"/>').text(event.start.format('Z'))
					);
				}
			},
			dayClick: function(date) {
				console.log('dayClick', date.format());
			},
			select: function(startDate, endDate) {
				console.log('select', startDate.format(), endDate.format());
			}
		});
		 
		// load the list of available timezones, build the <select> options
		$.getJSON('php/get-timezones.php', function(timezones) {
			$.each(timezones, function(i, timezone) {
				if (timezone != 'UTC') { // UTC is already in the list
					$('#timezone-selector').append(
						$("<option/>").text(timezone).attr('value', timezone)
					);
				}
			});
		});

		// when the timezone selector changes, dynamically change the calendar option
		$('#timezone-selector').on('change', function() {
			$('#calendar').fullCalendar('option', 'timezone', this.value || false);
		});
		*/
	});
//ss=RMIcalculateWeightLOTNoCheck(2,2);
//alert(ss);
function showMessage(message)
{
							jQuery("#dialog-confirm").text(message);
							$("#dialog-confirm" ).dialog({
								resizable: false,
								height: 200,
								width: 500,
								modal: true,
								buttons: {
									"OK": {  text: 'OK', 
                               class: 'btn btn-primary btn-sm buttonWidth floatLeft', 
                               click: function () {
                                  $(this).dialog("close");
								  
									}
									},
									"Cancel": {  text: 'Cancel', 
                               class: 'btn btn-danger btn-sm buttonWidth floatLeft', 
                               click: function () {
                                  $(this).dialog("close");
								   window.location.href="<?=base_url()?>Sales";
									} }
							}
							});
	
}
function showMessageurl(message,url)
{
							jQuery("#dialog-confirm").text(message);
							$("#dialog-confirm" ).dialog({
								resizable: false,
								height: 200,
								width: 500,
								modal: true,
								buttons: {
									"OK": {  text: 'OK', 
                               class: 'btn btn-primary btn-sm buttonWidth floatLeft', 
                               click: function () {
                                  $(this).dialog("close");
								  window.location.href="<?=base_url()?>Sales/"+url;
									}
									},
									"Cancel": {  text: 'Cancel', 
                               class: 'btn btn-danger btn-sm buttonWidth floatLeft', 
                               click: function () {
                                  $(this).dialog("close");
								   window.location.href="<?=base_url()?>Sales";
									} }
							}
							});
	
}
function showMessageurlcustom(message,url)
{
							jQuery("#dialog-confirm").text(message);
							$("#dialog-confirm" ).dialog({
								resizable: false,
								height: 200,
								width: 500,
								modal: true,
								buttons: {
									"OK": {  text: 'OK', 
                               class: 'btn btn-primary btn-sm buttonWidth floatLeft', 
                               click: function () {
                                  $(this).dialog("close");
								  window.location.href="<?=base_url()?>"+url;
									}
									},
									"Cancel": {  text: 'Cancel', 
                               class: 'btn btn-danger btn-sm buttonWidth floatLeft', 
                               click: function () {
                                  $(this).dialog("close");
								   window.location.href="<?=base_url()?>Sales";
									} }
							}
							});
	
}
function showMessage1(message)
{
							jQuery("#dialog-confirm").text(message);
							$("#dialog-confirm" ).dialog({
								resizable: false,
								height: 200,
								width: 500,
								modal: true,
								buttons: {
									"OK": {  text: 'OK', 
                               class: 'btn btn-primary btn-sm buttonWidth floatLeft', 
                               click: function () {
                                 
							var sequenceNumber=$('#sequenceNumber').val();
	window.open('<?=base_url()?>Sales/invoicePrint/'+sequenceNumber);	  
							 $(this).dialog("close");
							 window.location.href="<?=base_url()?>Sales/invoice?mode=1&inv=&enq=&so";
									}
									},
									"Cancel": {  text: 'Cancel', 
                               class: 'btn btn-danger btn-sm buttonWidth floatLeft', 
                               click: function () {
                                  $(this).dialog("close");
								  window.location.href="<?=base_url()?>Sales";
									} }
							}
							});
	
}
function showMessageOne(message,message2)
{
							jQuery("#dialog-confirm").text(message);
							$("#dialog-confirm" ).dialog({
								resizable: false,
								height: 200,
								width: 500,
								modal: true,
								buttons: {
									"OK": {  text: 'OK', 
                               class: 'btn btn-primary btn-sm buttonWidth floatLeft', 
                               click: function () {
                                  $(this).dialog("close");
								  showMessageTwo(message2);
									}
									},
									"Cancel": {  text: 'Cancel', 
                               class: 'btn btn-danger btn-sm buttonWidth floatLeft', 
                               click: function () {
                                  $(this).dialog("close");
								   window.location.href="<?=base_url()?>Sales";
									} }
							}
							});
	
}
function showMessageTwo(message)
{
							jQuery("#dialog-confirm").text(message);
							$("#dialog-confirm" ).dialog({
								resizable: false,
								height: 200,
								width: 500,
								modal: true,
								buttons: {
									"OK": {  text: 'OK', 
                               class: 'btn btn-primary btn-sm buttonWidth floatLeft', 
                               click: function () {
                                  $(this).dialog("close");
									}
									},
									"Cancel": {  text: 'Cancel', 
                               class: 'btn btn-danger btn-sm buttonWidth floatLeft', 
                               click: function () {
                                  $(this).dialog("close");
								   window.location.href="<?=base_url()?>Sales";
									} }
							}
							});
	
}
/* $(document).scannerDetection({
	timeBeforeScanTest: 200, // wait for the next character for upto 200ms
	startChar: [120], // Prefix character for the cabled scanner (OPL6845R)
	endChar: [13], // be sure the scan is complete if key 13 (enter) is detected
	avgTimeByChar: 40, // it's not a barcode if a character takes longer than 40ms
	onComplete: function(barcode, qty)
	{ 
		alert(barcode);
		var dataString='barcode='+barcode;
		$.ajax({
				   type:'POST',
				   url : '<?=base_url()?>TransactionAjax/getProductByBarcode',
				   data:dataString,
				   dataType:'json',
				   cache:false,
				   success:function(todo)
					{
						if(todo.status==200)
						{
							console.log(todo.qty);
							console.log(todo.weight);
							calculateBarcodeValue(barcode,todo.product,todo.qty,todo.weight);
						}
					}
				});				
				
	} // main callback function	
}); */

function barcodePrint()
{
	sequenceNumber=$('#sequenceNumber').val();
	window.open('<?=base_url()?>Sales/barCodeFinishedGoods?grnno='+sequenceNumber); 
}


function checkDuplicateAjax(mode=0,type,idcolumn,id,element,description,table,column1,element1,column2='',element2='',column3='',element3='')
{
	console.log(mode);
	console.log(element1);
	var value1=$('#'+element1).val();
	console.log(value1);
		var dataString='type='+type+'&idcolumn='+idcolumn+'&id='+id+'&description='+description+'&table='+table+'&column1='+column1+'&value1='+value1;

	if(type==2)
	{
		var value2=$('#'+element2).val();
		var dataString='type='+type+'&idcolumn='+idcolumn+'&id='+id+'&description='+description+'&table='+table+'&column1='+column1+'&value1='+value1+'&column2='+column2+'&value2='+value2;
	}
	if(type==3)
	{
		var value3=$('#'+element3).val();
		var dataString='type='+type+'&idcolumn='+idcolumn+'&id='+id+'&description='+description+'&table='+table+'&column1='+column1+'&value1='+value1+'&column2='+column2+'&value2='+value2+'&column3='+column3+'&value3='+value3;
	}
	var flag=false;
	var url='<?=base_url() ?>MasterAjax/checkDuplicateAjax';
		$.ajax({
				url:url,
				async: false,
				type:'POST',
				data:dataString,
				dataType:'json',
				success:function(result){
						console.log(result);
						console.log('success');
						if(result.status==200)
						{
							
							$('#'+element).val('');	
							if(mode==0)
								showMessage(result.message);
							else
							{
								$('.form-error-text-block').show();
								showPopupMessage(result.message);
							}
							$('#'+element).addClass('validationError');
							flag=true;	
						}
						else
						{
							if(mode!=0)
							{
								$('.form-error-text-block').hide();
								$('.custom-content').find('.dpnone').addClass('dpnone');
							}
						}
					},
				error:function(){
					if(mode==0)
						showMessage('Server Error');
					else
					{
						showPopupMessage(result.message);
					}
				}				
			 });
			return flag;
}


</script>
<!--end calender function-->
</body>
</html>

<style>	

</style>
	
