<div id="top_table">
	<div class="pull-right">
		<button class="btn btn-primary" onclick="submit_task('searchengine.add_url');">Add</button>
		<button class="btn btn-danger" onclick="delete_task('searchengine.delete_url');">Delete</button>
	</div>
	<br/><br/>
	<table align="center" border="0" class="table table-striped" width="70%">
		<thead>
			<tr style="text-align:center; background-color:#CCCCCC">
				<th><?php echo JText::_('Date From');?></th>
				<th><?php echo JText::_('Date To');?></th>
				<th><?php echo JText::_('Url List (Txt Only)');?></th>
			</tr>
		</thead>
		<tbody>
			<tr>
				<td align="center"><?php echo JHTML::calendar('','data[date_from2]', 'date_from2', '%Y-%m-%d',array('style'=>'width:60%','maxlength'=>'10','class'=>'required'));?></td>
				<td align="center"><?php echo JHTML::calendar('','data[date_to2]', 'date_to2', '%Y-%m-%d',array('style'=>'width:60%','maxlength'=>'10','class'=>'required'));?></td>
				<td align="center"><input type="file" name="file2" class="form" style="width:50%;"></td>
			</tr>
		</tbody>
	</table>
</div>	
<div id="top_table">
	<table align="center" border="0" class="table table-striped" width="70%">
		<thead>
			<tr style="text-align:center; background-color:#CCCCCC">
				<th><?php echo JHtml::_('grid.checkall'); ?>
				<th><?php echo JText::_('Date From');?></th>
				<th><?php echo JText::_('Date To');?></th>
				<th><?php echo JText::_('Url List');?></th>
			</tr>
		</thead>
		<tbody>
			<?php foreach($this->urls as $i=>$item):?>
				<tr class="row<?php echo $i % 2; ?>">
					<td width="5%" align="center"><?php echo JHtml::_('grid.id', $i, $item->id); ?></td>
					<td width="5%" align="center"><?php echo $item->date_from; ?></td>
					<td width="5%" align="center"><?php echo $item->date_to; ?></td>
					<td width="5%" align="center"><?php echo $item->url_list; ?></td>
				</tr>
			<?php endforeach;?>
		</tbody>
	</table>
</div>			

