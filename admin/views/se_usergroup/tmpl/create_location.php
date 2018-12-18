<form action="index.php?option=com_searchengine&task=se_usergroup.save_usergroup&package_id=<?php echo $this->package_id; ?>" method="post" name="frm_save_location" id="frm_save_location" class="form-validate">

<table width="100%" cellpadding="1" cellspacing="1"
	class="table-striped"
	style="border: 1px solid #cccccc; font-size: 10pt;">
	<thead>
		<tr style="text-align: center; background-color: #CCCCCC">
			<td colspan="3" align="center" height="50" class="td-group"><strong><?php echo JText::_('COM_REFUND_TAB_LOCATION'); ?></strong></td>
		</tr>
		<tr>
			<th align="left" style="padding-left:15px;"><?php echo $this->form->getLabel('population'); ?></th>
			<th align="center"><label title="Select Country" class="hasTooltip required" for="jform_state" id="jform_state-lbl">
	Country<span class="star">&nbsp;*</span></label></th>
			<th align="center"><?php echo $this->form->getLabel('state'); ?></th>
		</tr>
	</thead>
	<tbody>
		<tr>
			<td align="left" style="padding-left:15px;">
				<input type="hidden" name="jform[population]" id="jform_population" value="100" />
				<input type="text" value="100"
				class="inputbox" size="5" style="width: 50px;" disabled="true">%
			</td>
			<td align="center">
				<select id="country" name="jform[country]" required="true"></select>	
			</td>
			<td align="center">
			  <select name="jform[state]" id="state" required="true" ></select>				
			</td>
		</tr>		
		<tr>
			<td align="left" style="padding-left:15px;">
				<button value="Save" class="btn-add" onclick="saveForm('frm_save_location')" <?php echo (empty($parents) && $command == '1' ? "" : "");  echo ( $this->published == 1 ? 'disabled' : '' ) ;?>><?php echo JText::_('COM_REFUND_SAVE'); ?></button>
			</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
		</tr>
		<tr>
			<td colspan="3">&nbsp;</td>
		</tr>
	</tbody>
</table>
<br />
<br />
<table width="100%" cellpadding="1" cellspacing="1"
	class="table-striped"
	style="border: 1px solid #cccccc; font-size: 10pt;">
	<thead>
		<tr>
			<td colspan="7" align="center" height="50" class="td-group"
				style="text-align: center; background-color: #CCCCCC"><strong><?php echo JText::_('COM_REFUND_TAB_LOCATION'); ?></strong></td>
		</tr>
		<tr>
			<th ><?php echo JText::_('COM_REFUND_LBL_POPULATION'); ?></th>
			<th><?php echo JText::_('Country'); ?></th>
			<th><?php echo JText::_('State/Provience'); ?></th>						
			<th><?php echo JText::_('COM_REFUND_LBL_TO_AC'); ?></th>
		</tr>
	</thead>
	<?php
	$rows = $this->model->filter_field($this->package_id,'location', $this->group->id);
	?>
	<tbody>
	<?php
	foreach ($rows as $row):
	?>
		<tr>
			<td align="center"><?php echo $row->population;?> %</td>
			<td align="center"><?php echo $row->country;?></td>
			<td align="center"><?php echo $row->state;?>&nbsp;</td>	
			
			<td align="center">
            
			<a href="<?php echo JRoute::_('index.php?option=com_searchengine&controller=se_usergroup&task=deletecriteria&field=location&package_id='.$this->package_id.'&criteria_id='.$row->criteria_id.'&usergroupid='.$row->usergroup_id); ?>" onclick="return checkLocation();"><?php echo JText::_('Delete'); ?></a>
			<?php //return window.confirm('Are you sure?'); ?>			
			</td>
			
				
			
		</tr>
		<?php
		endforeach;
		?>
	</tbody>
</table>
<div>
<input type="hidden" value="location" name="jform[field]">
<input type="hidden" name="command" value="<?php echo JRequest::getVar('command') ?>">
<input type="hidden" name="package_id" value="<?php echo  JRequest::getVar("package_id"); ?>"/>
<input type="hidden" name="criteria_id" value="<?php echo (JRequest::getVar('field') =='location') ? JRequest::getVar('criteria_id'):'';?>"/>
<input type="hidden" name="option" value="com_searchengine" />
<input type="hidden" name="task" id="task" value="se_usergroup.save_usergroup" />
<input type="hidden" name="group_id" id="group_id" value="<?php echo $this->group->id;?>"/>
<input type="hidden" name="group_title" id="group_title" value="<?php echo $this->group->title;?>" class="group_title" />
<?php echo JHtml::_('form.token'); ?>
</div>
</form>
<script type="text/javascript">
	<?php if($rows){ ?>
		hasLocation= '<?php echo count($rows);?>';
			
	<?php } ?>
	populateCountries("country", "state");
	function checkLocation(){

		/*if( hasLocation >1 ) {
			return window.confirm('Are you sure?');
		}else{
			alert('At least one country/state shoud be set.');
			return false;
		}*/

		if( genderCount<=0 && ageCount<=0  ) {
			return window.confirm('Are you sure?');
		}else if( hasLocation == 1){
			alert('Can not delete location.Gender or age shoud be empty');
			return false;
		}
	}
</script>
