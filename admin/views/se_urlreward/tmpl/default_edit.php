<?php defined('_JEXEC') or die('Restricted access'); 
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
?>
<script type="text/javascript">

var count=undefined;
var count2=undefined;

Joomla.submitbutton = function(pressbutton) {
	if(pressbutton=='se_urlreward.back'){
		window.location.href = "index.php?option=com_searchengine&views=se_urlreward&task=se_urlreward.getlist&package_id=<?php echo $this->package_id; ?>";	
	}else{
		document.adminForm.task.value=pressbutton;
		submitform(pressbutton);	
	}
}
</script>
<?php echo $this->loadTemplate('nav');?>
<div id="j-main-container" class="span10">
<form method="post" action="<?php echo JRoute::_('index.php?option=com_searchengine&task=se_urlreward.save');?>" name="adminForm" id="adminForm">

		<div>
				
				
				<div class="span10">					
					<label class="span1">Title</label>
					<div class="span10">
						<input type="text" name="title" id="title" value="<?php if(!empty($this->rule_details[0]->title)) echo $this->rule_details[0]->title;?>" >
					</div>
				</div>
			<div class="span12" id="gift_code_rule_list_week_days">				
			<?php
			$url_list=[];
			$gift_codes=[];
			
				$i=1;
				foreach($this->rule_settings as $key=>$valuesettings)
				{
					if(!empty($valuesettings->urllist))
					{
						
						$url_list=json_decode($valuesettings->urllist);	
						
					}
					
					if(!empty($valuesettings->giftcodes))
					{
						$gift_codes=json_decode($valuesettings->giftcodes);	
					}	
				}
			?>				
				<div style="float:left;width:100%;border:1px solid #CCCCCC;margin-bottom: 25px;"  id="group<?php echo $i; ?>" class="bigbox">				
				  <div class="span12" style="width:100%;">
				    	
				    	<div style="background-color:#CCCCCC;height: 30px;">
					    	<div class="span4"  style="text-align:center;"><strong><?php echo JText::_('Url clicked (No url needed to be clicked if left blank)');?></strong></div>
					    	<div class="span5"  style="text-align:center;"><strong><?php echo JText::_('Giftcode');?></strong></div>
							<div class="span3"  style="text-align:center;"><strong><?php echo JText::_('Additional Settings');?></strong></div>
				    	</div>
				    </div>
				    <div class="span12">	
<div class="span4 divtableurllist">
						<table align="center" border="0" class="table table-striped" style="width:97%;">
							<thead>
								
								<tr class="actions">
									<input type="hidden" name="group-id[]" value="<?php echo $i; ?>">
									<td colspan="3" align="right"><a href="javascript:void(0)" class="deleteAll">Delete</a><a href="#" data-toggle="modal" data-target="#myModalWeekdays2" data-group-id="<?php echo $i; ?>">Add</a></td>
								</tr>
							</thead>
						</table>
						<table align="center" border="0" class="table table-striped" style="width:97%;">
							<thead>
								<tr style="text-align:center; background-color:#CCCCCC">
									
									<th width="1%" class="hidden-phone"><?php echo JHtml::_('grid.checkall'); ?></th>
									<th><?php echo JText::_('Url List');?></th>
								</tr>
							</thead>

							<tbody>
								<?php
								if(!empty($url_list))
								{
									foreach($url_list as $key=>$valueul)
									{
										
										if(!empty($valueul) && $valueul!='null')
										{
											$url_data=explode(',',$valueul);
								?>
										<tr>
										<td><input type="checkbox" value="<?php echo $valueul; ?>"  name="rulegiftcode[]"><input type="hidden" value="<?php echo $valueul; ?>" name="weekdays[<?php echo $i; ?>][ticket][]"></td>
										<td><?php echo $url_data[1]; ?></td>
										</tr>										
								<?php
										}
									}
								}
								?>
							</tbody>
						</table>
						</div>					
					<div class="span5 divtablecategory" style="border-right: 1px solid #CCCCCC; border-left: 1px solid #CCCCCC; padding:0 15px;">
						<table align="center" border="0" class="table table-striped" width="70%">
							<thead>
								
								<tr class="actions">
									<input type="hidden" name="group-id[]" value="<?php echo $i; ?>">
									<td colspan="3" align="right"><a href="javascript:void(0)" class="deleteAll">Delete</a><a href="#" data-toggle="modal" data-target="#myModalWeekdays" data-group-id="<?php echo $i; ?>">Add</a></td>
								</tr>
							</thead>
							After keyword input and hit the search button, must click on ALL Url on the list
							<table align="center" border="0" class="table table-striped" width="70%">
								<thead>
									<tr style="text-align:center; background-color:#CCCCCC">
										
										<th width="1%" class="hidden-phone"><?php echo JHtml::_('grid.checkall'); ?>
										</th>
										<th><u><?php echo JText::_('Giftcode');?></u></th>
										<th><u><?php echo JText::_('Giftcode Quantity');?></u></th>
										<th><u><?php echo JText::_('Total Giftcode Cost');?></u></th>
									</tr>
								</thead>
								<tbody>
								<?php
									if(!empty($gift_codes))
									{
										
										foreach($gift_codes as $key=>$valuegc)
										{
											if(!empty($valuegc) && $valuegc!='null')
											{
												$gc_data=explode(',',$valuegc);
									?>
										<tr>
										<td><input type="checkbox" value="<?php echo $valuegc; ?>" name="ruleweekday[]"><input type="hidden" value="<?php echo $valuegc; ?>" name="weekdays[<?php echo $i; ?>][day][]"></td>
										<td><?php echo $gc_data[1]; ?></td>	
										<td><?php echo $gc_data[2]; ?></td>	
										<td><?php echo $gc_data[3]; ?></td>	
										</tr>										
								<?php
											}
										}
									}
								?>	
								</tbody>

							</table>
						</table>
					</div>
					
						<div  class="span3">
						Number of times each user can collect giftcodes from the same input keyword and url 
						<input type="textbox" id="additionalsettings" name="additionalsettings"  value="<?php echo $valuesettings->additionalsettings;?>" /> Times per day
						</div>
					</div>
				</div>
		<?php 
				$i++;
				
			
		?>
			</div>
			
			
			  <!-- Modal -->
			  <div class="modal fade" id="myModalWeekdays" role="dialog" style="display:none;">
				<div class="modal-dialog modal-sm">
				  <div class="modal-content">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
		<h3><?php echo JText::_('Search Category Giftcode');?></h3>
	</div>
	<table class="table table-striped" id="surveyCategoryTable"
		style="border: 1px solid #ccc;">
		<thead>
			<tr style="background-color:#CCCCCC">
				<th width="5%">&nbsp;</th>
				<th><u><?php echo JText::_('COM_AWARD_PACKAGE_CATEGORY')?></u></th>
				<th width="19%"><u><?php echo JText::_('COM_AWARD_PACKAGE_CATEGORY_NAME');?></u></th>
				<th><u><?php echo JText::_('Giftcode Quantity');?></u></th>
				<th><u><?php echo JText::_('Total Giftcode Cost');?></u></th>
			</tr>
		</thead>
		<tbody>
		<?php 
		foreach ($this->categories as $i=>$category) { ?>
			<tr class="row<?php echo $i%2;?>">
				<td><input type="radio" class="radioSurveyCategoryClass"
					value="<?php echo $i; ?>" name="surveyCategory"
					"></td>
				<td valign="center">
					<div style="padding-top:14px;width:50px;height:40px;text-align:center;background-color:<?php echo $category->colour_code;?>"><font color="white" size="5"><b><?php echo $category->category_id; ?></b></font></div>
				</td>						
				<td><?php echo $category->category_name; ?></td>				
				<td align="right">
				<input type="text" value="1" name="giftCodeTotal" onkeyup="numbersOnly(this);">
				<input type="hidden" value="<?php echo ($category->search_price*100); ?>">
				</td>
				<td align="right">
				<input type="text" value="<?php echo ($category->search_price*100); ?>" name="giftCodePrice" readonly="readonly">								
				</td>
			</tr>
			<?php } ?>
		</tbody>
	</table>
					</div>
					<div class="modal-footer">
						<input type="hidden" name="box-id" value="" class="box-id">
					   <button type="button" class="btn btn-default" id="save-giftcode-day1" onclick="close_modal_window();">Save</button><button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				  </div>
				</div>
			  </div>
			  
			
			  
			  
			  <!-- Modal -->
			  <div class="modal fade" id="myModalWeekdays2" role="dialog" style="display:none;">
				<div class="modal-dialog modal-sm">
				  <div class="modal-content">
					<div class="modal-header">
					  <button type="button" class="close" data-dismiss="modal">&times;</button>
					  <h4 class="modal-title">Select Url List</h4>
					</div>
					<div class="modal-body" style="overflow-y:scroll">
						<table class="table table-striped" id="giftcodes">
							<thead>
								<tr style="text-align:center; background-color:#CCCCCC">
									
									<th><input type="checkbox" id="checkAllDay" name="toggle" value=""
										onclick="checkAll('checkAllDay','dayChkbox');" /></th>
									</th>					
										<th><?php echo JText::_('Url List');?></th>
										
								</tr>
							</thead>
							<tbody>
							<?php foreach ($this->membertickets as $i=>$memberticket) { ?>
								<tr class="row<?php echo $i%2;?>">
									<td><input type="checkbox" name="ticket_id[]" class="dayChkbox" value="<?php echo $memberticket->id."-".$memberticket->title; ?>"></td>
									<td align="center"><?php echo $memberticket->title;?></td>
																	

								</tr>
								<?php } ?>
							</tbody>
						</table>
					</div>
					<div class="modal-footer">
						<input type="hidden" name="box-id" value="" class="box-id">
					   <button type="button" class="btn btn-default" id="save-giftcode-day">Save</button><button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
					</div>
				  </div>
				</div>
			  </div>
			  
			  
			  
	
	<input type="hidden" id="option" name="option" value="com_searchengine" />
	<input type="hidden" name="boxchecked" value="1" />
	<input type="hidden" name="urlrewardid" value="<?php echo $this->id;?>">
	<input type="hidden" name="task" value="se_urlreward.save" />
	<input type="hidden" name="package_id" value="<?php echo $this->package_id; ?>" />
		
</form>
</div>
<script>

function close_modal_window(){	
			if(jQuery("input[type='radio'].radioSurveyCategoryClass").is(':checked')) {
				var id = parseInt(jQuery("input[type='radio'].radioSurveyCategoryClass:checked").val()) + 1;			    
				var tr = jQuery("#surveyCategoryTable tr:eq("+id+")");
				var name = jQuery(tr).find("td:eq(2)").text();			    
				var quantity = jQuery(tr).find("td:eq(3) input").val();
				var costPerResponse = jQuery(tr).find("td:eq(4) input").val();
				var cpr = costPerResponse;
				
				
				//var boxid = jQuery(this).parent().find("input").val();
				var boxid = 1;
				jQuery("#group"+boxid).find(".divtablecategory table tbody").append("<tr><td><input type='checkbox' name='ruleweekday[]' class='chkWeekDay"+boxid+"' value='"+id+"'><input type='hidden' name='weekdays["+boxid+"][day][]' value='"+id+","+name+","+quantity+","+cpr+"'></td><td>"+name+"</td><td>"+quantity+"</td><td>"+cpr+"</td></tr>");
				jQuery('#myModalWeekdays').modal('hide') 
				
				
			};
			//jQuery('#loadSurveyCategory').modal('toggle');	
		}
		
		
	jQuery(document).ready(function(){
		
		
		
		jQuery(document).on("click", "a[data-toggle=modal]", function () {
			 var groupId = jQuery(this).data('group-id');
			 jQuery(".modal-footer").find(".box-id").val(groupId);
		});
		
		
		jQuery(document).on("click","#save-giftcode-day",function(){
			var me =jQuery(this);
			var selectedVal = "";
			var selected = jQuery("#giftcodes input[type='checkbox']:checked");

			if (selected.length > 0) {
				jQuery(selected).each(function(){
					var elem= jQuery(this);			
					selectedVal = elem.val();
					if( selectedVal!=''){
						selectedVal = selectedVal.split("-");
						var boxid = me.parent().find(".box-id").val();

						jQuery("#group"+boxid).find(".divtableurllist table tbody").append("<tr><td><input type='checkbox' name='ruleticketid[]' class='chkDayMemberTicket"+boxid+"' value='"+selectedVal[0]+"'><input type='hidden' name='weekdays["+boxid+"][ticket][]' value='"+selectedVal[0]+","+selectedVal[1]+"'></td><td>"+selectedVal[1]+"</td></tr>");
					}
				});

				jQuery('#myModalWeekdays2').modal('hide') 
			}
			else{
				alert("Please select any giftcode.")
			}
		});
		
				
		jQuery(".hasTooltip").click(function(){
			
			if(this.checked) { // check select status
				jQuery(this).parents().closest("table").find("tbody tr input[type='checkbox']").each(function(index, element){
					this.checked = true;  //select all checkboxes with class "checkbox1"              
				});
			}else{
				jQuery(this).parents().closest("table").find("tbody tr input[type='checkbox']").each(function(index, element){
					this.checked = false; //deselect all checkboxes with class "checkbox1"                      
				}); 
			}				
		});
	});
	
	jQuery(document).on('click','.deleteAll',function(){
			
			console.log(jQuery(this).parents().closest("table"));
			
			jQuery(this).parents().closest("table").next("table").find("tbody tr input[type='checkbox']").each(function(index, element){
				if(this.checked){
					jQuery(element).parent().parent().remove();
				}            
			});
				
		});


	function checkAll(self, elem){
		jQuery('.'+elem).prop('checked',jQuery('#'+self).prop('checked'));
	}
</script>
