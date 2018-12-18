<input type="hidden" name="package_id" value="<?php echo JRequest::getVar('package_id');?>"><div id="top_table">
	<table align="center" border="0" class="table table-striped" width="70%">
		<thead>
			<tr style="text-align:center; background-color:#CCCCCC">
				<th><?php echo JText::_('Last Updated');?></th>
				<th><?php echo JText::_('User IP Address');?></th>
				<th><?php echo JText::_('Total Keyword Input');?></th>
				<th><?php echo JText::_('Total Url Clicked');?></th>
			</tr>
		</thead>
	</table>
</div>		
<?php echo JHtml::_('form.token'); ?>
