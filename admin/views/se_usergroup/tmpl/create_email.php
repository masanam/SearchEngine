<form action="index.php?option=com_searchengine&task=se_usergroup.save_usergroup&package_id=<?php echo $this->package_id; ?>" method="post" name="frm_save_email" id="frm_save_email" class="form-validate">



<table width="100%" cellpadding="1" cellspacing="1"
	class="table-striped"
	style="border: 1px solid #cccccc; font-size: 10pt;">
	<thead>
		<tr style="text-align: center; background-color: #CCCCCC">
			<td colspan="2" align="center" height="50" class="td-group"><strong><?php echo JText::_('COM_REFUND_TAB_EMAIL'); ?></strong></td>
		</tr>
		<tr>
			<th align="left" style="padding-left:15px;" ><?php echo $this->form->getLabel('population'); ?></th>
			<th><?php echo $this->form->getLabel('email'); ?></th>
		</tr>
	</thead>
	<tbody>
		<tr>
			<td align="left" style="padding-left:15px;">
			<input type="hidden" name="jform[population]" id="jform_population" value="100" />
			<input type="text"  value="100"
				class="inputbox" size="5" style="width: 50px;" disabled="true">%</td>
			<td align="center"><input type="email" name="jform[email]"
				class="validate-email inputbox" id="jform_email" value="<?php echo $this->email; ?>" size="20" required="true">
			</td>
		</tr>
		<tr>
			<td align="left" style="padding-left:15px;">
				<button value="Save" class="btn-add" onclick="saveForm('frm_save_email')" <?php echo (empty($parents) && $command == '1' ? "" : "");  echo ( $this->published == 1 ? 'disabled' : '' ) ;?>><?php echo JText::_('COM_REFUND_SAVE'); ?></button>
			</td>
			<td></td>
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
				style="text-align: center; background-color: #CCCCCC"><strong><?php echo JText::_('COM_REFUND_TAB_EMAIL'); ?></strong></td>
		</tr>
		<tr>
			<th align="center"><?php echo JText::_('COM_REFUND_LBL_POPULATION'); ?></th>
			<th><?php echo JText::_('COM_REFUND_TAB_EMAIL'); ?></th>
			<th><?php echo JText::_('COM_REFUND_LBL_TO_AC'); ?></th>
		</tr>
	</thead>
	<?php
	$rows = $this->model->filter_field($this->package_id, 'email',$this->group->id);
	?>
	<tbody>
	<?php
	foreach ($rows as $row):
	?>
		<tr>
			<td align="center"><?php echo $row->population; ?> %</td>
			<td align="center"><?php echo $row->email; ?>&nbsp;</td>
			<td align="center">            
          
			<a href="<?php echo JRoute::_('index.php?option=com_searchengine&controller=se_usergroup&task=deletecriteria&field=email&package_id='.$this->package_id.'&criteria_id='.$row->criteria_id.'&usergroupid='.$row->usergroup_id); ?>" onclick="return window.confirm('Are you sure?');"><?php echo JText::_('COM_REFUND_DELETE'); ?></a>
          
			</td>			
		</tr>
		<?php
		endforeach;
		?>
	</tbody>
</table>
<div id="userEmailModalWindow" class="modal hide fade" style="height:670px; width:800px;padding:10px;">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
		<h3><?php echo JText::_('User List');?></h3>
	</div>
	<div style="overflow:scroll; height:550px; width:100%;">
		<br/>
		&nbsp;&nbsp;&nbsp;<input type="Button" value="Select" onclick="onSelectUserEmail();"/>
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
						<input type="checkbox" name="emailaccountid" id="emailaccountid" 
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
<input type="hidden" value="email" name="jform[field]">
<input type="hidden" name="command" value="<?php echo JRequest::getVar('command') ?>">
<input type="hidden" name="package_id" value="<?php echo  JRequest::getVar("package_id"); ?>"/>
<input type="hidden" name="criteria_id" value="<?php echo (JRequest::getVar('field') =='email') ? JRequest::getVar('criteria_id'):'';?>"/>
<input type="hidden" name="option" value="com_searchengine" />
<input type="hidden" name="task" id="task" value="se_usergroup.save_usergroup" />
<input type="hidden" name="group_id" id="group_id" value="<?php echo $this->group->id;?>"/>
<input type="hidden" name="group_title" id="group_title" value="<?php echo $this->group->title;?>" class="group_title" />
<?php echo JHtml::_('form.token'); ?>
</form>

