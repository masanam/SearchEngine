<form
	action="index.php?option=com_searchengine&view=usergroup&package_id=<?php echo $this->package_id; ?>"
	method="post" name="adminForm3" id="adminForm3"
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
//$presentationGroups = $this->ug->getNameUserGroupPresentation($this->package_id, 'gender');
/* Above code line changed to below code by Sushil on 30-11-2015 */
$presentationGroups = $this->ug->getNameUserGroupPresentation($this->package_id, 'gender',JRequest::getVar('criteria_id'));
$presentationGroup = null;
if(!empty($presentationGroups)){
	$presentationGroup = $presentationGroups[0];
}

$parents = $this->ug->getParentUserGroup($this->package_id,'gender');
$parent = null;
if(!empty($parents)){
	$parent = $parents[0];
}
?>

<table width="100%" cellpadding="1" cellspacing="1"
	class="table-striped"
	style="border: 1px solid #cccccc; font-size: 10pt;">
	<thead>
		<tr style="text-align: center; background-color: #CCCCCC">
			<td colspan="2" align="center" height="50" class="td-group"><strong><?php echo JText::_('COM_REFUND_TAB_GENDER'); ?></strong></td>
		</tr>
		<tr>
			<th align="left" style="padding-left:15px;"><?php echo $this->form->getLabel('population'); ?></th>
			<th><?php echo JText::_('COM_REFUND_TAB_GENDER'); ?></th>
		</tr>
	</thead>
	<tbody>
		<tr>
			<td align="left" style="padding-left:15px;">
			<input type="hidden" name="jform[population]" id="jform_population" value="100" />
			<input type="text" value="100"
				class="inputbox" size="5" style="width: 50px;" disabled="true">%</td>
			<td align="center">
				<select name="jform[gender]" style="width: 300px;" <?php //echo ($command == '1' && $this->p_gender != '' ? "disabled=disabled" : ""); ?>>
				<?php if($command == '1'){ ?>
					<?php foreach ($this->genders as $rows){ ?>
					<option value="<?php echo $rows->gender; ?>" <?php echo ($this->p_gender == 'M' ? 'selected=selected' : ''); ?>><?php echo $rows->gender; ?></option>
					<?php } ?>
				<?php } else { ?>
					<option value="M"
					<?php echo (@$this->gender == 'M' ? "selected=selected" : ""); ?><?php echo ($this->p_gender == 'M' ? 'selected=selected' : ''); ?> >Male</option>
					<option value="F"
					<?php echo (@$this->gender == 'F' ? "selected=selected" : ""); ?><?php echo ($this->p_gender == 'F' ? 'selected=selected' : ''); ?>>Female</option>
				<?php } ?>
				</select>
			</td>
		</tr>
		<tr>
			<td align="left" style="padding-left:15px;">
				<button value="Save" class="btn-add" <?php echo (empty($parents) && $command == '1' ? "" : "");  echo ( $this->published == 1 ? 'disabled' : '' ) ;?>><?php echo JText::_('COM_REFUND_SAVE'); ?></button>
			</td>
			<td>&nbsp;</td>
		</tr>
		<tr>
			<td colspan="2">&nbsp;</td>
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
			<td colspan="3" align="center" height="50" class="td-group"
				style="text-align: center; background-color: #CCCCCC"><strong><?php echo JText::_('COM_REFUND_TAB_GENDER'); ?></strong></td>
		</tr>
		<tr>
			<th align="center"><?php echo JText::_('COM_REFUND_LBL_POPULATION'); ?></th>
			<th><?php echo JText::_('COM_REFUND_TAB_GENDER'); ?></th>
			<th><?php echo JText::_('COM_REFUND_LBL_TO_AC'); ?></th>
		</tr>
	</thead>
	<?php
	$rows = $this->ug->filter_field($this->package_id,'gender');
	?>
	<tbody>
	<?php
	foreach ($rows as $row):
	?>
		<tr>
			<td align="center"><?php echo $row->population;?> %</td>
			<td align="center"><?php echo $row->gender;?>&nbsp;</td>
			<td align="center">
            <?php if ($this->published == 1) { ?>
           
			 <a href="javascript: clickA()"><?php echo JText::_('COM_REFUND_DELETE'); ?></a>
			<?php }else { ?>
            
			<a href="<?php
           if(JRequest::getVar('command') == '1'){$deltask = "deletepres";} else {$deltask = "delete";} 
           echo JRoute::_('index.php?option=com_searchengine&controller=usergroup&task='.$deltask.'&field=gender&package_id=' . $this->package_id . '&criteria_id=' . $row->criteria_id  . '&command=' . JRequest::getVar('command')); ?>"
				onclick="return window.confirm('Are you sure?');"><?php echo JText::_('COM_REFUND_DELETE'); ?></a>
                <?php } ?>
			<?php if( JRequest::getVar('command') == '1' ) { ?>
      &nbsp;&nbsp;
			<?php
              if($row->is_presentation!="1"){
                $linkcheckbox = JRoute::_("index.php?option=com_searchengine&task=usergrouplocation.centang&field=gender&package_id=" . $this->package_id . '&id=' . $row->criteria_id);
                $cs="";
              } else {
                $linkcheckbox = JRoute::_("index.php?option=com_searchengine&task=usergrouplocation.uncentang&field=gender&package_id=" . $this->package_id . '&id=' . $row->criteria_id);
                $cs="checked"; 
              };
              echo "&nbsp;&nbsp;<input type=checkbox id='cbl{$row->criteria_id}' name='cbl[]' value='1' onchange='javascript:window.location=\"$linkcheckbox\"' $cs>";
      ?>
      <!--
      <a
				href="<?php echo JRoute::_('index.php?option=com_searchengine&view=apresentationlist&task=apresentationlist.initiate&package_id='.$this->package_id.'&idUserGroupsId='.$row->criteria_id.'&processPresentation='.JRequest::getVar('processPresentation')); ?>"
				style="color:blue;text-decoration:underline;"><?php echo JText::_('Select'); ?></a>
			-->
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
	type="hidden" value="gender" name="jform[field]"> <input type="hidden"
	name="command" value="<?php echo ($command == '1' ? '1' : '0'); ?>"> <?php echo JHtml::_('form.token'); ?>
</div>
</form>
