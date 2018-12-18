
<form action="index.php?option=com_searchengine&task=se_usergroup.save_usergroup" method="post" name="frm_save_name" id="frm_save_name" class="form-validate">

<?php $command = JRequest::getVar('command'); ?>
<table width="100%" cellpadding="1" cellspacing="1" class="table-striped" style="border: 1px solid #cccccc; font-size:10pt;">
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
			    <input type="hidden" name="jform[population]" id="jform_population" value="100"/>
				<input type="text" value="100" 
				class="inputbox" size="5" style="width:40px;" disabled="true">%
			</td>
			<td align="center">
				<input type="text" name="jform[firstname]" id="jform_firstname"  required="true"
				value="<?php echo $this->firstname; ?>" 
				class="inputbox" size="5" style="width:130px;">
			</td>
			<td align="center">
				<input type="text" name="jform[lastname]" id="jform_lastname" required="true" 
				value="<?php echo $this->lastname; ?>" 
				class="inputbox" size="5" style="width:130px;"> 
			</td>
		</tr>
		<tr>
			<td align="left" style="padding-left:15px;">
				<button type="button" value="Save" id="save_name" class="btn-add" onclick="saveForm('frm_save_name')" ><?php echo JText::_('COM_REFUND_SAVE'); ?></button>
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
	$rows = $this->model->filter_field($this->packageId,'name', $this->group->id);
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
			<a href="<?php echo JRoute::_('index.php?option=com_searchengine&controller=se_usergroup&task=deletecriteria&field=name&criteria_id='.$row->criteria_id.'&usergroupid='.$row->usergroup_id); ?>" onclick="return window.confirm('Are you sure?');"><?php echo JText::_('COM_REFUND_DELETE'); ?></a>
            
			</td>
		</tr>
		<?php
		endforeach;
		?>
	</tbody>
</table>


<input type="hidden" value="name" name="jform[field]">
<input type="hidden" name="command" value="<?php echo JRequest::getVar('command') ?>">
<input type="hidden" name="package_id" value="<?php echo $this->packageId; ?>"/>
<input type="hidden" name="criteria_id" value="<?php echo (JRequest::getVar('field') =='name') ? JRequest::getVar('criteria_id'):'';?>"/>
<input type="hidden" name="option" value="com_searchengine" />
<input type="hidden" name="task" id="task" value="se_usergroup.save_usergroup" />
<input type="hidden" name="group_id" id="group_id" value="<?php echo $this->group->id;?>"/>
<input type="hidden" name="group_title" id="group_title" value="<?php echo $this->group->title;?>" class="group_title" />

<?php echo JHtml::_('form.token'); ?>

</form>
