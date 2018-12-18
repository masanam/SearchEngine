<div id="top_table">
	<div class="pull-right">
		<button class="btn btn-primary" onclick="submit_task('searchengine.add_keyword');">Add</button>
		<button class="btn btn-danger" onclick="delete_task('searchengine.delete_keyword');">Delete</button>
	</div>
	<br/><br/>
	<table align="center" border="0" class="table table-striped" width="70%">
		<thead>
			<tr style="text-align:center; background-color:#CCCCCC">
				<th><?php echo JText::_('Date From');?></th>
				<th><?php echo JText::_('Date To');?></th>
				<th><?php echo JText::_('Keyword List (Txt Only)');?></th>
			</tr>
		</thead>
		<tbody>
			<tr>
				<td align="center"><?php echo JHTML::calendar('','data[date_from1]', 'date_fromk', '%Y-%m-%d',array('style'=>'width:60%','maxlength'=>'10','class'=>'required'));?></td>
				<td align="center"><?php echo JHTML::calendar('','data[date_to1]', 'date_tok', '%Y-%m-%d',array('style'=>'width:60%','maxlength'=>'10','class'=>'required'));?></td>
				<td align="center"><input type="file" name="filek" class="form" style="width:50%;"></td>
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
				<th><?php echo JText::_('Keyword List');?></th>
			</tr>
		</thead>
		<tbody>
			<?php foreach($this->kewyords as $keyword):?>
				<tr class="row<?php echo $i % 2; ?>">
					<td width="5%" align="center"><?php echo JHtml::_('grid.id', $i, $keyword->id); ?></td>
					<td width="5%" align="center"><?php echo $keyword->date_from; ?></td>
					<td width="5%" align="center"><?php echo $keyword->date_to; ?></td>
					<td width="5%" align="center"><?php echo $keyword->keyword_list; ?></td>
				</tr>
			<?php endforeach;?>
		</tbody>
	</table>
</div>			

