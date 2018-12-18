<form
	action="index.php?option=com_searchengine&view=usergroup&package_id=<?php echo $this->package_id; ?>"
	method="post" name="adminForm5" id="adminForm5"
	class="form-validate">
<input type="hidden" name="command" value="<?php echo JRequest::getVar('command') ?>">
<input type="hidden" name="package_id" value="<?php echo  JRequest::getVar("package_id"); ?>"/>
<input type="hidden" name="var_id" value="<?php echo JRequest::getVar('var_id');?>"/>
<input type="hidden" name="criteria_id" value="<?php echo JRequest::getVar('criteria_id');?>"/>
<input type="hidden" name="filter_order" value="<?php if($this->lists['order']) echo $this->lists['order']; ?>" />
<input type="hidden" name="filter_order_Dir" value="<?php if($this->lists['order_dir']) echo $this->lists['order_dir']; ?>" />
<input type="hidden" name="task" id="task" value="" /> 
<input type="hidden" name="user_selected" id="user_selected" value=""/>

<?php $command = JRequest::getVar('command'); ?>
<?php
//$presentationGroups = $this->ug->getNameUserGroupPresentation($this->package_id, 'name');
/* Code changed from above to below code by Sushil 30-11-2015 */
$presentationGroups = $this->ug->getNameUserGroupPresentation($this->package_id, 'name',JRequest::getVar('criteria_id'));
$presentationGroup = null;
if(!empty($presentationGroups)){
	$presentationGroup = $presentationGroups[0];
}

$parents = $this->ug->getParentUserGroup($this->package_id,'name');
$par = null;
if(!empty($parents)){
	$par = $parents[0];
	
}
?>

<table width="100%" cellpadding="1" cellspacing="1" class="table-striped"
	style="border: 1px solid #cccccc; font-size:10pt;">
	<thead>
		<tr style="text-align:center; background-color:#CCCCCC">
			<td colspan="3" align="center" height="50" class="td-group"><strong><?php echo JText::_('COM_REFUND_TAB_NAME'); ?></strong></td>
		</tr>
		<tr>
			<th align="left" style="padding-left:15px;"><?php echo $this->form->getLabel('population'); ?></th>
			<th><?php echo $this->form->getLabel('firstname'); ?></th>
			<th><?php echo $this->form->getLabel('lastname'); ?></th>
		</tr>
	</thead>
	<tbody>
		<tr>
			<td align="left" style="padding-left:15px;">
				<input type="hidden" name="jform[population]" id="jform_population" value="100" />
				<input type="text" value="100" 
				class="inputbox" size="5" style="width:40px;" disabled="true">%
			</td>
			<td align="center">
				<input type="text" name="jform[firstname]" id="jform_firstname" 
				value="<?php echo ($this->task == 'edit' ? $this->p_firstname : ''); ?>" 
				class="inputbox" size="5" style="width:130px;" 
				<?php echo ($command == '1' && $par->firstname != '' ? '' : ''); ?>>
			</td>
			<td align="center">
				<input type="text" name="jform[lastname]" id="jform_lastname" 
				value="<?php echo ($this->task == 'edit' ? @$this->lastname : ''); ?>" 
				class="inputbox" size="5" style="width:130px;" <?php echo ($command == '1' && $par->lastname != '' ? '' : ''); ?>> 
			</td>
		</tr>
		<tr>
			<td align="left" style="padding-left:15px;">
				<button value="Save" class="btn-add" onclick="save_edit();" ><?php echo JText::_('COM_REFUND_SAVE'); ?></button>
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
<table width="100%" cellpadding="1" cellspacing="1" class="table-striped"
	style="border: 1px solid #cccccc;font-size:10pt;">
	<thead>
		<tr>
			<td colspan="4" align="center" height="50" class="td-group" style="text-align:center; background-color:#CCCCCC"><strong><?php echo JText::_('COM_REFUND_TAB_NAME'); ?></strong></td>
		</tr>
		<tr>
			<th align="center"><?php echo JText::_('COM_REFUND_LBL_POPULATION'); ?></th>
			<th><?php echo JText::_('Firstname'); ?></th>
			<th><?php echo JText::_('Lastname'); ?></th>
			<th><?php echo JText::_('COM_REFUND_LBL_TO_AC'); ?></th>
		</tr>
	</thead>
	<?php
	$rows = $this->ug->filter_field($this->package_id,'name');
	?>
	<tbody>
	<?php
	foreach ($rows as $row):
	?>
		<tr>
			<td align="center"><?php echo $row->population; ?>%</td>
			<td align="center"><?php echo $row->firstname; ?></td>
			<td align="center"><?php echo $row->lastname; ?></td>
			<td align="center">
            <?php if ($this->published == 1) { ?>
            
			 <a href="javascript: clickA()"><?php echo JText::_('COM_REFUND_DELETE'); ?></a>
			<?php }else { ?>
			<a href="<?php echo JRoute::_('index.php?option=com_searchengine&controller=usergroup&task=delete&field=name&package_id='.$this->package_id.'&criteria_id='.$row->criteria_id . '&command=' . JRequest::getVar('command')); ?>" onclick="return window.confirm('Are you sure?');"><?php echo JText::_('COM_REFUND_DELETE'); ?></a>
            <?php } ?>
			<?php if (JRequest::getVar('command') == '1') {
			        if($row->is_presentation!="1"){
                $linkcheckbox = JRoute::_("index.php?option=com_searchengine&task=usergrouplocation.centang&field=name&package_id=" . $this->package_id . '&id=' . $row->criteria_id);
                $cs="";
              } else {
                $linkcheckbox = JRoute::_("index.php?option=com_searchengine&task=usergrouplocation.uncentang&field=name&package_id=" . $this->package_id . '&id=' . $row->criteria_id);
                $cs="checked"; 
              };
              echo "&nbsp;&nbsp;<input type=checkbox id='cbl{$row->criteria_id}' name='cbl[]' value='1' onchange='javascript:window.location=\"$linkcheckbox\"' $cs>";
          } ?>
			<!--
      <span style="text-decoration:underline;cursor:pointer; color:blue;" onclick="openModalUserName();">
				<?php echo JText::_('Select User'); ?>				
			</span>
			-->
			</td>
		</tr>
		<?php
		endforeach;
		?>
	</tbody>
</table>
<input type="hidden" name="jform[package_id]"
	value="<?php echo $this->package_id; ?>" /> 
    <input type="hidden"
	name="jform[criteria_id]" value="<?php echo $this->criteria_id; ?>" />
	<input type="hidden" name="option" value="com_searchengine" /> 
	<input
	type="hidden" name="controller" value="usergroup" /> <input	
	type="hidden" value="name" name="jform[field]">
	<input type="hidden" name="command" value="<?php echo ($command == '1' ? '1' : '0'); ?>">
	<div id="userNameModalWindow" class="modal hide fade" style="height:670px; width:800px;padding:10px;">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
		<h3><?php echo JText::_('User List');?></h3>
	</div>	
	<div style="overflow:scroll; height:550px; width:100%;">
		<br/>
		&nbsp;&nbsp;&nbsp;<input type="Button" value="Select" onclick="onSelectUserName();"/>
		<br/>
		<br/>
		<table class="table table-striped" id="prizeTable"
			style="border: 1px solid #ccc;">
			<thead>
				<tr style="background-color:#CCCCCC">
					<th width="5%">&nbsp;</th>
					<th><u><?php echo JText::_('Firstname')?></u></th>
					<th><u><?php echo JText::_('Lastname')?></u></th>
					<th><u><?php echo JText::_('Birthday')?></u></th>
					<th><u><?php echo JText::_('Email')?></u></th>
				</tr>
			</thead>
			<tbody>
				<?php
				foreach ($this->users as $user){ ?>
				<tr>
					<td>
						<input type="checkbox" name="nameaccountid" id="nameaccountid" 
						value="<?php echo $user->ap_account_id; ?>" />						
					</td>
					<td>
						<?php echo (empty($user->firstname) ? '' : JText::_($user->firstname)); ?>
					</td>
					<td>
						<?php echo (empty($user->lastname) ? '' : JText::_($user->lastname)); ?>						
					</td>
					<td>					
						<?php echo (empty($user->birthday) ? '' : JText::_($user->birthday)); ?>
					</td>
					<td>					
						<?php echo (empty($user->email) ? '' : JText::_($user->email)); ?>
					</td>
				</tr>		
				<?php				
				} 
				?>
			</tbody>
		</table>
	</div>
</div>
</form>
