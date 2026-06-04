<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>Ahattrickz</title>
    <meta name="distributor" content="Global" />
    <meta itemprop="contentRating" content="General" />
    <meta name="robots" content="All" />
    <meta name="revisit-after" content="7 days" />
    <meta name="description" content="The source of truly unique and awesome jquery plugins." />
    <meta name="keywords" content="slider, carousel, responsive, swipe, one to one movement, touch devices, jquery, plugin, bootstrap compatible, html5, css3" />
    <meta name="author" content="w3widgets.com">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href='http://fonts.googleapis.com/css?family=Economica' rel='stylesheet' type='text/css'>
    <!-- Bootstrap -->
    <link rel="stylesheet" href="http://netdna.bootstrapcdn.com/bootstrap/3.0.3/css/bootstrap.min.css">
    <!-- Respomsive slider -->
    <link href="<?=base_url();?>assets/css/responsive-calendar.css" rel="stylesheet">

	
	
	<link rel="stylesheet" type="text/css" href="<?=base_url();?>assets/demo/css/semantic.ui.min.css">
	<link rel="stylesheet" type="text/css" href="<?=base_url();?>assets/demo/css/prism.css" />
	<link rel="stylesheet" type="text/css" href="<?=base_url();?>assets/demo/css/calendar-style.css" />
	<link rel="stylesheet" type="text/css" href="<?=base_url();?>assets/demo/css/style.css" />
	<link rel="stylesheet" type="text/css" href="<?=base_url();?>assets/dist/css/pignose.calendar.css" />
	<style type="text/css">
    .responsive-calendar .controls {
		text-align: center;
		background: #ff86e9;
		height: 48px;
		padding-top: 10px;
		padding-left: 10px;
		padding-right: 10px;
	}
	.responsive-calendar {
  background: #383838;
  color: #fff;
  border-radius: 10px;
  padding: 20px;
  box-shadow: 0px 0px 10px #000;
  max-width: 600px;
  margin: 30px auto;
}
.controls {
  display: flex;
  justify-content: space-between;
  align-items: center;
}
.controls h4 {
  margin: 0;
}
.btn {
  background: #007bff;
  color: #fff;
  padding: 6px 10px;
  border-radius: 5px;
  cursor: pointer;
}
.btn:hover {
  background: #0056b3;
}
.day-headers, .days {
  display: grid;
  grid-template-columns: repeat(7, 1fr);
  text-align: center;
  margin-top: 10px;
}
.day.header {
  font-weight: bold;
  padding: 8px 0;
}
.day {
  background: #444;
  min-height: 70px;
  padding: 6px;
  margin: 4px;
  border-radius: 5px;
  cursor: pointer;
  position: relative;
  overflow: hidden;
}
.day:hover {
  background: #555;
}
.today {
  background: #28a745;
}
.day-number {
  font-weight: bold;
  text-align: left;
}
.day-content {
  margin-top: 5px;
  background: transparent;
  border: none;
  color: #fff;
  width: 100%;
  height: 50px;
  resize: none;
  font-size: 13px;
}
.day-content:focus {
  outline: 1px solid #00b4ff;
  background: #222;
}

  </style>
	
	<script type="text/javascript" src="<?=base_url();?>assets/demo/js/jquery.latest.min.js"></script>
	<script type="text/javascript" src="<?=base_url();?>assets/demo/js/moment.latest.min.js"></script>
	<script type="text/javascript" src="<?=base_url();?>assets/demo/js/semantic.ui.min.js"></script>
	<script type="text/javascript" src="<?=base_url();?>assets/demo/js/prism.min.js"></script>
	<script type="text/javascript" src="<?=base_url();?>assets/dist/js/pignose.calendar.js"></script>
	
	
  </head>
  <body>
	<div class="col-sm-12" style="margin-top:2%;">
		<div class="col-sm-4">
			<div class="">
			<!--  Responsive Editable Calendar - START -->
<div class="responsive-calendar">
  <div class="controls">
    <div class="btn" id="prevMonth"><<</div>
    <h4><span id="monthName"></span> <span id="year"></span></h4>
    <div class="btn" id="nextMonth">>></div>
  </div>
  <hr style="margin:10px 0;">
  <div class="day-headers">
    <div class="day header">Mon</div>
    <div class="day header">Tue</div>
    <div class="day header">Wed</div>
    <div class="day header">Thu</div>
    <div class="day header">Fri</div>
    <div class="day header">Sat</div>
    <div class="day header">Sun</div>
  </div>
  <div class="days" id="calendarDays"></div>
</div>
<!--  Responsive Editable Calendar - END -->


			</div>
		</div><!--end of col-sm-7-->
		
		<div class="col-sm-8">
		</div><!--end of col-sm-5-->
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
	
<!--start footer-->
		<div class="col-sm-12 fottop">	
			<div class="col-sm-12 ftpara">
				<p><img src="<?=base_url();?>assets/css/img/footer-logo.png">A MACS'ian Product</p>
			</div><!--end of col-sm-12-->
		</div><!--end of col-sm-12-->
<!--end footer-->
<!-- <script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js"></script> -->


	<script src="<?=base_url();?>assets/js/jquery.js"></script>
    <script src="<?=base_url();?>assets/js/bootstrap.min.js"></script>
    <script src="<?=base_url();?>assets/js/responsive-calendar.js"></script>
    <script type="text/javascript">
      $(document).ready(function () {
		  
		  
		 var d = new Date();
		 var year = d.getFullYear(); 
		 var month = d.getMonth()+1;
		 var day = d.getDate();
		showPopupLeave(year,month,day);
		 
        $(".responsive-calendar").responsiveCalendar({
          time: year+'-'+month,
          events: {
			<?php foreach($getLeaveList['currentMonthArray'] as $key=>$value) {//echo $key; ?> 
				"<?=$key?>": {"number": <?=$value?>},
			<?php } ?>
          } 
        });
      });
	  
	 
	  
	$(document).on('click','a',function(){
		 var day=$(this).data("day") ;
		 var month=$(this).data("month") ;
		 var year=$(this).data("year") ;
			 showPopupLeave(year,month,day);	
		
	}); 
		
	function showPopupLeave(year,month,day)
	{
			currentDate=year+'-'+month+'-'+day;
		 var url='<?=base_url()?>TransactionAjax/updateLeaveList';
		 var dataString='currentDate='+currentDate;
		 
		 $.ajax({
				url:url,
				type:'POST',
				data:dataString,
				dataType:'html',
				success:function(result){
					if(result==200)
						showAjaxModal('<?=base_url()?>Sales/showAjaxModal?date='+currentDate,'<span style="font-family: Trebuchet MS;">Leave List</span>');
					
				}
		});
	}	
		
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
		
    </script>
	
	
	</body>
<script>
const monthName = document.getElementById('monthName');
const yearDisplay = document.getElementById('year');
const daysContainer = document.getElementById('calendarDays');

let currentDate = new Date();

function renderCalendar() {
  const year = currentDate.getFullYear();
  const month = currentDate.getMonth();

  monthName.textContent = currentDate.toLocaleString('default', { month: 'long' });
  yearDisplay.textContent = year;

  const firstDay = new Date(year, month, 1);
  const startDay = (firstDay.getDay() + 6) % 7; // Monday start
  const daysInMonth = new Date(year, month + 1, 0).getDate();

  daysContainer.innerHTML = '';

  for (let i = 0; i < startDay; i++) {
    const empty = document.createElement('div');
    daysContainer.appendChild(empty);
  }

  for (let day = 1; day <= daysInMonth; day++) {
    const dayDiv = document.createElement('div');
    dayDiv.classList.add('day');

    // Day number
    const dayNumber = document.createElement('div');
    dayNumber.classList.add('day-number');
    dayNumber.textContent = day;

    // Editable text area
    const textArea = document.createElement('textarea');
    textArea.classList.add('day-content');
    textArea.placeholder = "Write here...";

    // Save data in localStorage for persistence
    const key = `${year}-${month}-${day}`;
    textArea.value = localStorage.getItem(key) || "";

    textArea.addEventListener('input', () => {
      localStorage.setItem(key, textArea.value);
    });

    // Highlight today
    const today = new Date();
    if (day === today.getDate() && month === today.getMonth() && year === today.getFullYear()) {
      dayDiv.classList.add('today');
    }

    dayDiv.appendChild(dayNumber);
    dayDiv.appendChild(textArea);
    daysContainer.appendChild(dayDiv);
  }
}

document.getElementById('prevMonth').addEventListener('click', () => {
  currentDate.setMonth(currentDate.getMonth() - 1);
  renderCalendar();
});

document.getElementById('nextMonth').addEventListener('click', () => {
  currentDate.setMonth(currentDate.getMonth() + 1);
  renderCalendar();
});

renderCalendar();
</script>
   <!-- <script>
        function updateDateTime() {
            const now = new Date();
            const optionsDate = { weekday: 'short', day: '2-digit', month: 'short', year: 'numeric' };
            const date = now.toLocaleDateString('en-GB', optionsDate);
            const time = now.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit', second: '2-digit' });
            document.getElementById("liveDateTime").textContent = `${date} | ${time}`;
        }

        setInterval(updateDateTime, 1000);
        updateDateTime(); 
    </script> -->

</html>