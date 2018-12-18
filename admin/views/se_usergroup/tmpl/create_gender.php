<form action="index.php?option=com_searchengine&task=se_usergroup.save_usergroup&package_id=<?php echo $this->package_id; ?>" method="post" name="frm_save_gender" id="frm_save_gender" class="form-validate">
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
				<select name="jform[gender]">				
					<option value="M"
					<?php echo (@$this->gender == 'M' ? "selected=selected" : "");?> >Male</option>
					<option value="F"
					<?php echo (@$this->gender == 'F' ? "selected=selected" : "");?> >Female</option>
				
				</select>
			</td>
		</tr>
		<tr>
			<td align="left" style="padding-left:15px;">
				<button value="Save" class="btn-add btn-group2" <?php echo (empty($parents) && $command == '1' ? "" : "");  echo ( $this->published == 1 ? 'disabled' : '' ) ;?>><?php echo JText::_('Save'); ?></button>
				
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
	$rows = $this->model->filter_field($this->package_id,'gender',$this->group->id);
	?>
	<tbody>
	<?php
	foreach ($rows as $row):
	?>
		<tr>
			<td align="center"><?php echo $row->population;?> %</td>
			<td align="center"><?php echo $row->gender;?>&nbsp;</td>
			<td align="center">
           
			<a href="<?php echo JRoute::_('index.php?option=com_searchengine&controller=se_usergroup&task=deletecriteria&field=gender&package_id='.$this->package_id.'&criteria_id='.$row->criteria_id.'&usergroupid='.$row->usergroup_id); ?>" onclick="return window.confirm('Are you sure?');"><?php echo JText::_('COM_REFUND_DELETE'); ?></a>
			</td>
		</tr>
		<?php
		endforeach;
		?>
	</tbody>
</table>
<div><input type="hidden" value="gender" name="jform[field]">
<input type="hidden" name="command" value="<?php echo JRequest::getVar('command') ?>">
<input type="hidden" name="package_id" value="<?php echo  JRequest::getVar("package_id"); ?>"/>
<input type="hidden" name="criteria_id" value="<?php echo (JRequest::getVar('field') =='gender') ? JRequest::getVar('criteria_id'):'';?>"/>
<input type="hidden" name="option" value="com_searchengine" />
<input type="hidden" name="task" id="task" value="se_usergroup.save_usergroup" />
<input type="hidden" name="group_id" id="group_id" value="<?php echo $this->group->id;?>"/>
<input type="hidden" name="group_title" id="group_title" value="<?php echo $this->group->title;?>" class="group_title" />
<?php echo JHtml::_('form.token'); ?>
</div>
</form>
<script>
genderCount = '<?php echo count($rows);?>';
</script>
