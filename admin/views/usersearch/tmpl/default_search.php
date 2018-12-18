 <?php 
JHtml::_('behavior.tooltip');
JHTML::_('behavior.modal');
 ?>
 
<div style="padding:5px;">
 <?php echo $this->loadTemplate('user_tabs');?>
  <div class="span12" style="float: right;margin-bottom: 10px;">
      <div class="span5"></div>
      <div class="span3">
        <div style="border:1px solid #CCCCCC;width: 239px;text-align:center;padding-bottom: 5px;">  
          <fieldset>
          <legend style=" background-color:#EEEEEE;font-size: 15px;font-weight: bold;margin-bottom: 5px;">Penalty to lock user account</legend>
           

  <a href="#" onclick="loadPenatlies();" id="triggerPenaltForm">New</a>

  
            
          </fieldset>    
        </div>  
      </div>
      <div class="span3" style="float: right;">
		   <table>
          <tr>
            <td><button id="ad-penalty"  class="btn btn-success">Add penalty</button></td>
            <td><button id="remove-penalty" class="btn btn-danger">Remove penalty</button></td>
          </tr>
        </table>
        <!--<button id="ad-penalty" style="float:left;width: 130px;" class="btn btn-success">Add penalty</button>
        <button id="remove-penalty" style="float:right;width: 130px;" class="btn btn-danger">Remove penalty</button>-->
      </div>
       <div style="float: right; height: 39px; padding-left: 11px;margin-top:5px;">

        <select size="1" class="inputbox input-mini" name="limit" id="limit">
          <option <?php if($this->pageLimit == 5){ echo 'selected="selected"';}?> value="5">5</option>
          <option <?php if($this->pageLimit == 10){ echo 'selected="selected"';}?> value="10">10</option>
          <option <?php if($this->pageLimit == 15){ echo 'selected="selected"';}?> value="15">15</option>
          <option <?php if($this->pageLimit == 20){ echo 'selected="selected"';}?> value="20">20</option>
          <option <?php if($this->pageLimit == 25){ echo 'selected="selected"';}?> value="25">25</option>
          <option <?php if($this->pageLimit == 30){ echo 'selected="selected"';}?> value="30">30</option>
          <option <?php if($this->pageLimit == 50){ echo 'selected="selected"';}?> value="50">50</option>
          <option <?php if($this->pageLimit == 100){ echo 'selected="selected"';}?> value="100">100</option>
          <option <?php if($this->pageLimit == -1){ echo 'selected="selected"';}?> value="-1">All</option>
        </select>
      </div>
  </div>
  
 <div style="overflow-y: auto; max-height:725px;" id="search_wrapper">
  <form method="post" name="adminForm" id="adminForm1" action="index.php?option=com_searchengine&view=usertransaction&package_id=<?php echo $this->package_id;?>&criteria=1&task=usertransaction">
  <input type="hidden" name="penalty_form_id" id="penalty_form_id" />
	<table class="table table-striped" style="border: 1px solid #ccc;">
		<thead>
		  <tr>
			<th><input type="checkbox" id="all-user-checkbox" value="" onclick="checkAllUsers(this);"></th>
			<th width="10%"><u><?php echo JText::_('User')?></u></th>
			<th width="7%"><u><?php echo JText::_('Registered')?></u></th>    
			<th width="7%"><u><?php echo JText::_('Email')?></u></th>
			<th width="10%"><u><?php echo JText::_('Gender')?></u></th>
			<th width="10%"><u><?php echo JText::_('Country')?></u></th>
			<th width="10%"><u><?php echo JText::_('Birthday')?></u></th>
			<th width="10%"><u><?php echo JText::_('Symbol queue')?></u></th>
			<th width="10%"><u><?php echo JText::_('Presentaion')?></u></th>    
			<th width="8%"><u><?php echo JText::_('User name ')?></u></th>
			<th width="11%"><u><?php echo JText::_('Password')?></u></th>
			<th width="10%"><u><?php echo JText::_('Menu')?></u></th>
			 <th width="10%"><u><?php echo JText::_('Member Ticket History')?></u></th>
			<th width="10%"><u><?php echo JText::_('Penalty')?></u></th>
			<th><u><?php echo JText::_('Penalty history')?></u></th>
		  </tr>
		</thead>
		<tbody>                    
		 <?php 
		 if($this->records) {

		 foreach ($this->records as $key => $item) { 
		  ?>
		  <tr>
		  <td><input type="checkbox" class="user-checkbox" value="<?php echo $item->id;?>" name="uid[]"></td>                      
		  <td><?php echo $item->fullname;?></td>
		  <td><?php echo $item->registerDate;?></td>
		  <td><?php echo $item->email;?></td>
		  <td><?php echo $item->gender;?></td>
		  <td><?php echo $item->country;?></td>
		  <td><?php echo $item->birthday;?></td>
		  <td><?php echo JText::_(empty($item->symbol_queue) ? 'no-data' : '<a href="index.php?option=com_searchengine&view=usersearch&task=usersearch.get_symbol_queue_detail&accountId='.$item->id.'&package_id='.$item->package_id.'" target="_blank">View</a>'); ?></td>

		  <td><?php echo JText::_(empty($item->is_presentation) ? 'no-data' : '<a href="index.php?option=com_searchengine&view=usersearch&task=usersearch.get_presentation&accountId='.$item->id.'&package_id='.$item->package_id.'" target="_blank">View</a>'); ?></td>

		  <td><?php echo $item->username;?></td>
		  <td><?php echo $item->password;?></td>
		  <td><button type="button" class="btn btn-primary" onclick="onLogin(<?php echo $item->id;?>);">Go</button>
		  
		  </td>
		  <td>
		   <?php if( $item->tcount > 0 ){?>  
		  <a  id="ticket_history<?php echo $item->id;?>" href="<?php echo JRoute::_('index.php?option=com_searchengine&task=usersearch.tickethistory&tmpl=component&package_id='.$this->package_id.'&user_id='. $item->id); ?>" 
		  class="modal" rel="{size: {x: 1000, y: 500}}"><?php echo $item->tcount;?></a>
		  <?php }else{ ?>
			<?php echo $item->tcount;?>
		  <?php } ?>

		  </td>
		  <td id="td<?php echo $item->id;?>">
		  <?php if( $item->status ==0 ){?>  
		  <a style="display: none;" href="<?php echo JRoute::_('index.php?option=com_searchengine&task=usersearch.editpenaltyform&tmpl=component&package_id='.$this->package_id.'&user_id='. $item->id); ?>" 
		  class="modal"><span rel="<?php echo $item->id;?>" class="given_date" id="given_date<?php echo $item->id;?>">00:00:00:00</span></a>
		  <input type="hidden" id="given_pdays<?php echo $item->id;?>" value="-1" />
		  <input type="hidden" id="given_startdate<?php echo $item->id;?>" value="<?php echo $item->created_at;//date("Y/m/d H:i:s", strtotime($item->created_at));?>" />
		  <span class="noPenalty">00:00:00:00</span>

		  <?php }else{
		   $endD = date_create($item->created_at);
			date_add($endD,date_interval_create_from_date_string("+$item->days days +23 hours +59 minutes +59 seconds"));
			$end_at = date_format($endD,"Y-m-d H:i:s");

			?>
		  <a href="<?php echo JRoute::_('index.php?option=com_searchengine&task=usersearch.editpenaltyform&tmpl=component&package_id='.$this->package_id.'&user_id='. $item->id); ?>" 
		  class="modal"><span rel="<?php echo $item->id;?>" class="given_date" id="given_date<?php echo $item->id;?>"><?php echo $end_at;?></span></a>
		  <input type="hidden" id="given_pdays<?php echo $item->id;?>" value="<?php echo $item->days;?>" />
		  <input type="hidden" id="given_startdate<?php echo $item->id;?>" value="<?php echo date("Y-m-d H:i:s", strtotime($item->created_at));?>" />
		  <span class="noPenalty" style="display: none;">00:00:00:00</span>
		  <?php } ?>
		  </td>

		  <td> <a  id="given_history<?php echo $item->id;?>" href="<?php echo JRoute::_('index.php?option=com_searchengine&task=usersearch.penaltyhistory&tmpl=component&package_id='.$this->package_id.'&user_id='. $item->id); ?>" 
		  class="modal"><?php echo $item->hcount;?></a></td>                      
		  </tr>                                                             
		  <?php 
			}
		  }else{?>     
			<tr><td colspan="14">No records found</td></tr>
		  <?php 
		  }?>                                                                     
		</tbody>
	</table>
</form>

	<table width="100%">
	<tr><td style="text-align:right;">
		<div class="pagination pagination-toolbar">
		  <?php echo $this->pagination1; ?>
		</div>
	</td></tr>
	</table>
</div>

</div>
<div id="penaltyModalWindow" class="modal hide fade" style="height:670px; width:800px;padding:10px;">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    <h3><?php echo JText::_('Penalty Form List');?></h3>
  </div>
  <div style="overflow:scroll; height:550px; width:100%;">
    <table class="table table-striped table-hover table-bordered" id="prizeTable"
      style="border: 1px solid #ccc;">
        <tr style="background-color:#CCCCCC">
          <th width="5%">&nbsp;</th>
          <th ><?php echo JText::_( 'Created data time' ); ?></th>     
          <th ><?php echo JText::_( 'Title' ); ?></th>                         
          <th class="hidden-phone"><?php echo JText::_( 'Countdown timer' ); ?></th>                
        </tr>
        <?php
        $i = 0; 
        foreach ($this->penalties as $penalties){ 
            $endD = date_create($penalties->created_at);
            date_add($endD,date_interval_create_from_date_string("+$penalties->days days +23 hours +59 minutes +59 seconds"));
            $end_at = date_format($endD,"Y-m-d H:i:s");
            $interval = DateHelper::dateDifference($penalties->created_at,$end_at);
          ?>
        <tr>
          <td>
            <input type="radio" name="radio_filled" class="radioFilledClass" 
           value="<?php echo $penalties->id; ?>" onclick="onClosePenaltyModalWindow(this,<?php echo $penalties->id; ?>);"/>
           <input type="hidden" id="penaltyName<?php echo $penalties->id; ?>" value="<?php echo $penalties->title; ?>">
          </td>
          <td>           
           <?php echo $penalties->created_at;?>
          </td>
          <td>          
            <?php echo $penalties->title; ?>           
          </td>
          <td>          
            <?php echo  $interval; ?>           
          </td>          
        </tr>   
        <?php
        $i++;
        } 
        ?>
    </table>
  </div>    
</div>

<?php
 if($this->records) {
 foreach ($this->records as $key => $item) { 
  ?>
    <form method="post" action="<?php echo  JURI::root() .'index.php?option=com_users'; ?>" name="LoginForm<?php echo $item->id;?>" id="LoginForm<?php echo $item->id;?>" target="_blank">
    <input type="hidden" name="username" value="<?php echo $item->username;?>" />
    <input type="hidden" name="password" value="<?php echo $item->username;;?>" />
    <?php echo JHtml::_('form.token');?>
    </form>
<?php }
}?>



<style type="text/css">
  

  .pagination ul{
    box-shadow: none;
    width: 50%;
  }

  .table-striped th, .table-striped td{
    border: 1px solid #000;
  }
</style>  

<script type="text/javascript">
    <?php
    $timezone = JFactory::getConfig()->get('offset');

    $time = new DateTime('now', new DateTimeZone($timezone));
    $timezoneOffset = $time->format('P');
    ?>
    var timezone = '<?php echo $timezone; ?>';
    var timezoneOffset = '<?php echo $timezoneOffset;?>';

    $(function($) {

      $('#search_wrapper').css('width',$('body').innerWidth()-10);
      var addurl = "<?php echo JRoute::_('index.php?option=com_searchengine&task=usersearch.addPenalty&tmpl=component&package_id='.$this->package_id, false); ?>";
      var removeurl = "<?php echo JRoute::_('index.php?option=com_searchengine&task=usersearch.removePenalty&tmpl=component&package_id='.$this->package_id, false); ?>";

      var loadPage = "<?php echo JRoute::_('index.php?option=com_searchengine&view=usersearch&package_id='.$this->package_id.'&criteria=1&task=usersearch.searchlist&tmpl=component&usergorup_from='.JRequest::getVar('usergorup_from').'&usergorup_to='.JRequest::getVar('usergorup_to').'&limit=',false) ?>";
      

       $(".loading_box_center",parent.document).hide();

       $('.filter_by').click(function(){
         jQuery('#frm_search').submit();
       });
 	$('.given_date').each(function() {
        var me = $(this);
        var id = $(this).attr('id');
        var rel = $(this).attr('rel');
        var datetime = $(this).html();

        var currentDate = '<?php echo JDate::getInstance('now')->format('Y/m/d H:i:s');?>';

        var start_from = $('#given_startdate' + rel).val();
        var pdays = $('#given_pdays' + rel).val();

        var from_date = moment(start_from);
        var endDate = from_date.add(parseInt(pdays), 'days');
        endDate.add(86399,'s');
        
        $('#' + id).countdowntimer({
            startDate: currentDate,
            dateAndTime: endDate.format('Y/M/D HH:mm:ss'),
            size: "lg"
        });
    });
       $('#ad-penalty').click(function(){

//          var newDate= new Date();
          var currentDate =  moment.tz(moment().format(),timezone); // get date time like server timezone



          var penalty_form_id = $('#penalty_form_id').attr('value');
          if(penalty_form_id ==''){
            alert('No penalty is selected.Select a penalty.');
          }else if( $(".user-checkbox:checked").length == 0 ) {
            alert('Select user to add penralty.');
          }else{
              $('#ad-penalty')
              .html('Adding...')
              .attr('disabled', true);
              var data = $('#adminForm1').serialize();

              jQuery.ajax({
                type: "POST",
                dataType: 'json',
                url: addurl,
                data: data,
                success:function(response){
                    var pdays = response.days;
                    var start_from =  response.created_at;
                    var current_date  = response.current_date;
                    var from_date = new moment(start_from);
                    var currentDate = current_date;
                    var endDate = from_date.add(parseInt(pdays),'days');

                        endDate.add(86399,'s');
                    console.log(endDate); 
                    $(".user-checkbox:checked").each(function(){
                        var id= $(this).attr('value');
                        var elem = $('#td'+id).html();                         

                        $('#given_date'+id).detach();
                        $('#td'+id).find('a').html('<span id="given_date'+id+'">00:00:00:00</span>');                                                                      
                        $('#td'+id).find('.noPenalty').css('display','none');
                        $('#td'+id).find('a').css('display','block');

                        var counter = parseInt( $('#given_history'+id).text() );
                        $('#given_history'+id).text(counter+1);

                        $('#given_date'+id).countdowntimer({
                          startDate : currentDate,
                          dateAndTime : endDate.format('Y/M/D HH:mm:ss'),
                          size : "lg"
                        });
                        

                    });

                    $('#ad-penalty')
                    .html('Add penalty')
                    .attr('disabled', false);
                    


                },
                error: function (request, status, error) {
                }                  
              }); 

          }
       });   

        $('#remove-penalty').click(function(){
          if( $(".user-checkbox:checked").length == 0 ) {
            alert('Select user to remove penralty.');
          }else{

             $('#remove-penalty')
              .html('Processing...')
              .attr('disabled', true);
              var data = $('#adminForm1').serialize();

              jQuery.ajax({
                type: "POST",
                dataType: 'json',
                url: removeurl,
                data: data,
                success:function(response){
                    
                    $(".user-checkbox:checked").each(function(){
                        var id= $(this).attr('value');
                        var elem = $('#td'+id).html();                                          
                        $('#given_date'+id).detach();
                        $('#td'+id).find('a').html('<span id="given_date'+id+'">00:00:00:00</span>');                        
                        $('#td'+id).find('.noPenalty').css('display','block');
                        $('#td'+id).find('a').css('display','none');


                    });

                    $('#remove-penalty')
                    .html('Remove penalty')
                    .attr('disabled', false);
                    


                },
                error: function (request, status, error) {
                }                  
              });
          }
        }); 

          $('#limit').change(function(){
            var limit = $(this).val();
            window.location = loadPage+limit;
        });     
        
}); 

function checkAllUsers(self){
  jQuery('.user-checkbox').prop('checked',jQuery('#all-user-checkbox').prop('checked'));
  
}


  function loadPenatlies(){
    jQuery('#penaltyModalWindow').modal('show');  
  }

  function onClosePenaltyModalWindow(elem, pid){
     
    if(jQuery(elem).is(':checked')) {
        jQuery('#penaltyModalWindow').modal('hide');  
        var id = jQuery(elem).val();      
        jQuery("#penalty_form_id").val(id);
        jQuery('#triggerPenaltForm').html( jQuery("#penaltyName"+pid).val());
        
                           
    } 
  }

  function parentFn(id,startDate,days){
      var pdays = days;
      startDate =startDate.replace(/-/g,'/');

      var newDate= new Date(startDate); 
      newDate.setDate(newDate.getDate()+parseInt(pdays));
      newDate.setHours(newDate.getHours()+parseInt(23));
      newDate.setMinutes(newDate.getMinutes()+parseInt(59));
      newDate.setSeconds(newDate.getSeconds()+parseInt(59));
      
      var endDate = newDate.getFullYear();
      endDate += '/'+(newDate.getMonth()+1);
      endDate += '/'+newDate.getDate();
      endDate += ' '+newDate.getHours();
      endDate += ':'+newDate.getMinutes();
      endDate += ':'+newDate.getSeconds();
      console.log(endDate); 


      var newDate= new Date();       
      var currentDate = newDate.getFullYear();
      currentDate += '/'+(newDate.getMonth()+1);
      currentDate += '/'+newDate.getDate();
      currentDate += ' '+newDate.getHours();
      currentDate += ':'+newDate.getMinutes();
      currentDate += ':'+newDate.getSeconds();
      console.log(currentDate); 


      var elem = $('#td'+id).html();
      $('#given_date'+id).detach();
      $('#td'+id).find('.noPenalty').css('display','none');
      $('#td'+id).find('a').html('<span id="given_date'+id+'">00:00:00:00</span>');                        
      $('#td'+id).find('a').css('display','block'); 

      $('#given_date'+id).countdowntimer({
      startDate : currentDate,
      dateAndTime : endDate,
      size : "lg"
      });
  }


  function onLogin(id){    
    jQuery('form#LoginForm'+id).submit();
  }

</script>       