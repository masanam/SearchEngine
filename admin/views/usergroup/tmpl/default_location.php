<form
	action="index.php?option=com_searchengine&view=usergroup&package_id=<?php echo $this->package_id; ?>"
	method="post" name="adminForm4" id="adminForm4"
	class="form-validate">
<input type="hidden" name="command" value="<?php echo JRequest::getVar('command') ?>">
<input type="hidden" name="package_id" value="<?php echo  JRequest::getVar("package_id"); ?>"/>
<input type="hidden" name="var_id" value="<?php echo JRequest::getVar('var_id');?>"/>
<input type="hidden" name="criteria_id" value="<?php echo JRequest::getVar('criteria_id');?>"/>
<input type="hidden" name="filter_order" value="<?php if($this->lists['order']) echo $this->lists['order']; ?>" />
<input type="hidden" name="filter_order_Dir" value="<?php if($this->lists['order_dir']) echo $this->lists['order_dir']; ?>" />
<input type="hidden" name="task" id="task" value="save_usergroup" /> 
<input type="hidden" name="user_selected" id="user_selected" value=""/>
<?php
//$presentationGroups = $this->ug->getNameUserGroupPresentation($this->package_id, 'location');
/* Above code changed to below code by Sushil on 30-11-2015 */
$presentationGroups = $this->ug->getNameUserGroupPresentation($this->package_id, 'location',JRequest::getVar('criteria_id'));
$presentationGroup = null;
if(!empty($presentationGroups)){
	$presentationGroup = $presentationGroups[0];
}

$parents = $this->ug->getParentUserGroup($this->package_id,'location');
$par = null;
if(!empty($parents)){
	$par = $parents[0];
}
?>

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
				<button value="Save" class="btn-add" <?php echo (empty($parents) && $command == '1' ? "" : "");  echo ( $this->published == 1 ? 'disabled' : '' ) ;?>><?php echo JText::_('COM_REFUND_SAVE'); ?></button>
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
<table width="100%" cellpadding="1" cellspacing="1" class="table-striped" style="border: 1px solid #cccccc; font-size: 10pt;">
	<thead>
		<tr>
			<td colspan="7" align="center" height="50" class="td-group"
				style="text-align: center; background-color: #CCCCCC"><strong><?php echo JText::_('COM_REFUND_TAB_LOCATION'); ?></strong></td>
		</tr>
		<tr>
			<th align="left" style="padding-left:15px;"><?php echo JText::_('COM_REFUND_LBL_POPULATION'); ?></th>
			<th><?php echo JText::_('Country'); ?></th>
			<th><?php echo JText::_('State/Provience'); ?></th>					
			<th><?php echo JText::_('COM_REFUND_LBL_TO_AC'); ?></th>
		</tr>
	</thead>
	<?php
	$rows = $this->ug->filter_field($this->package_id,'location');
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
            <?php if ($this->published == 1) { ?>
           
			 <a href="javascript: clickA()"><?php echo JText::_('COM_REFUND_DELETE'); ?></a>
			<?php }else { ?>
           
			<a href="<?php echo JRoute::_('index.php?option=com_searchengine&controller=usergroup&task=delete&field=location&package_id=' . $this->package_id . '&criteria_id=' . $row->criteria_id  . '&command=' . JRequest::getVar('command')); ?>"
				onclick="return window.confirm('Are you sure?');"><?php echo JText::_('COM_REFUND_DELETE'); ?></a>
                <?php } ?>
			<?php if( JRequest::getVar('command') == '1' ) { ?>
			&nbsp;&nbsp;
			<a
				href="<?php echo JRoute::_('index.php?option=com_searchengine&view=apresentationlist&task=apresentationlist.initiate&package_id='.$this->package_id.'&idUserGroupsId='.$row->criteria_id.'&processPresentation='.JRequest::getVar('processPresentation')); ?>"
				style="color:blue;text-decoration:underline;"><?php echo JText::_('Select'); ?></a>
			<?php } ?>
			</td>
		</tr>
		<?php
		endforeach;
		?>
	</tbody>
</table>
<div><input type="hidden" name="jform[package_id]"
	value="<?php echo $this->package_id; ?>" /> <input type="hidden"
	name="jform[criteria_id]" value="<?php echo $this->criteria_id; ?>" />
<input type="hidden" name="option" value="com_searchengine" /><input
	type="hidden" name="controller" value="usergroup" /> <input
	type="hidden" value="location" name="jform[field]"> <input
	type="hidden" name="command"
	value="<?php echo ($command == '1' ? '1' : '0'); ?>"> <?php echo JHtml::_('form.token'); ?>
</div>
</form>
<script type="text/javascript">
	populateCountries("country", "state");
</script>
