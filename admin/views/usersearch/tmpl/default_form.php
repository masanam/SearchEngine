<div style="width: 98%;">
<form class="adminForm" name="adminForm" id="adminForm" style="font-size: 15px;">

 <div class="form-group" style="margin-bottom: 5px;">
      <div style="float: left;"><h2>Create penalty</h2></div>  <button class="btn btn-primary search_btn" style="float: right;" id="save-penalty">Save</button>
   		<div style="clear: both;"></div>  
  </div>
   <hr>
  <div class="form-group" style="margin-bottom: 5px;">
      <input type="text" name="title" id="title" class="inputbox" style="width:99%;height:30px;" placeholder="Enter penalty Title" />
  </div>
  <div class="form-group" style="margin-bottom: 5px;">
      <textarea name="message" id="message" class="inputbox" style="height:179px;width:583px;" placeholder="Write your message" /></textarea> 
  </div>

  <div class="form-group">
      <input type="text" name="days" id="days" class="inputbox" style="width:11%;height:30px;" placeholder="Enter days" /> <strong>days</strong> <span style="border:1px solid black;padding:10px;"> <strong>23 hours 59 minutes 59 seconds</strong></span>
  </div>

   <div class="form-group" style="margin-bottom: 5px;">     
  </div>
</form>
</div>
<script type="text/javascript">
	jQuery('document').ready(function($){
		$('#save-penalty').click(function(e){
			e.preventDefault();
		    var title = $.trim($('#title').attr('value') );
		    var message = $.trim($('#message').attr('value'));
		    var days = $.trim($('#days').attr('value'));
		    var url = "<?php echo JRoute::_('index.php?option=com_searchengine&task=usersearch.savepenaltyform&tmpl=component&package_id='.$this->package_id, false); ?>";

		    if(title == '' || title.length ==0){
		    	alert('Penalty title can not be left empty.');

		    }else if (message == '' || message.length ==0){
		    	alert('Message not be left empty.');		    	
		    }else if(days == '' || days.length ==0){
		    	alert('Days can not be left empty.');
		    	
		    }else if( isNaN(parseInt(days)) || (days.indexOf('.') != -1) ){
		    	alert('Days can be only Integer number.');	
		    }else{
		    	alert('Every thign is ok');
		    	var data = $('#adminForm').serialize();
				jQuery.ajax({
					type: "POST",
					dataType: 'json',
					url: url,
					data: data,
					success:function(response){
						if(response.status== 1)	
							$('#penalty_form_id').attr('value',response.pid);					
					},
						error: function (request, status, error) {
					}                  
				}); 
 		    }
		    		
		});
	})
</script>