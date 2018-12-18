<div id="cj-wrapper">
	<div id="penalty_inner">
		<div id="penalty_wrapper" class="span12">
			<div>
				<h2><?php echo $this->penalty->title;?></h2>
			</div>
			<div>
				<p><?php echo $this->penalty->message;?></p>
			</div>
			<div>
				<p id="countdown_clock">00:00:00:00</p>
			</div>
		</div>
	</div>

</div>
<script type="text/javascript">
	
   var pdays = '<?php echo $this->penalty->days;?>';	
   var start_from =  '<?php echo $this->penalty->created_at;?>';  
   
   function startTimer(pdays,start_from){
    
	   start_from = start_from.replace(/-/g,'/');

	   var currentDate = '<?php echo JDate::getInstance('now')->format('Y/m/d H:i:s');?>';
	   var from_date = moment(start_from);
	   var endDate = from_date.add(parseInt(pdays), 'days');
	   endDate.add(86399,'s');

		$('#countdown_clock').countdowntimer({
			startDate : currentDate,
			dateAndTime : endDate.format('Y/M/D HH:mm:ss'),
			size : "lg",
			regexpMatchFormat: "([0-9]{1,3}):([0-9]{1,2}):([0-9]{1,2}):([0-9]{1,2})",
			regexpReplaceWith: "<span>$1</span><sup class='displayformat'>days</sup>&nbsp;&nbsp;<span>$2</span><sup class='displayformat'>hours</sup>&nbsp;&nbsp;<span>$3</span><sup class='displayformat'>minutes</sup>&nbsp;&nbsp;<span>$4</span><sup class='displayformat'>seconds</sup>"
			});
	}
	
	startTimer(pdays,start_from);

</script>
<style type="text/css">
#cj-wrapper{
 width:100%
}
#penalty_inner{
	width: 50%;
    margin: 0 auto; 
}

#countdown_clock{
	text-align: center;
}
#countdown_clock span {
  display: inline-block;
  font-size: 50px;
  color: rgba(0,0,0,0.25);
  height: 50px;
  line-height: 1;
}
#countdown_clock sup{
	color: rgba(0,0,0,0.25);
	top:-2.5px !important;
	font-size: 30px;
}
#penalty_wrapper{
	padding: 5px;
	margin: 5px;
	background-color: #f5f5f5;
}
#penalty_wrapper h2{
	padding: 5px 5px;	
	margin-right: 5px;
	text-align: center;
	background-color: #ffffff;
	margin-left: 4px;
}
#penalty_wrapper p{
	background-color: #ffffff;
	padding: 20px;
	margin-right: 5px;
	margin-left: 4px;
	
}
</style>
