<div id="top_table">
	<div class="pull-right">
		<button class="btn btn-primary" onclick="submit_task('searchengine.add_ip');">Add</button>
		<button class="btn btn-danger" onclick="delete_task('searchengine.delete_ip');">Delete</button>
	</div>
	<br/><br/>
	<table align="center" border="0" class="table table-striped" width="70%">
		<thead>
			<tr style="text-align:center; background-color:#CCCCCC">
				<th scope="col" width="1%" class="hidden-phone"><?php echo JHtml::_('grid.checkall'); ?>
				<th><?php echo JText::_('Date From');?></th>
				<th><?php echo JText::_('Date To');?></th>
				<th><?php echo JText::_('IP Address Range From');?></th>
				<th><?php echo JText::_('IP Address Range To');?></th>
			</tr>
		</thead>
		<tbody>
			<tr>
				<td align="center"><?php echo JHTML::calendar('','data[date_from]', 'date_from', '%Y-%m-%d',array('style'=>'width:60%','maxlength'=>'10','class'=>'required'));?></td>
				<td align="center"><?php echo JHTML::calendar('','data[date_to]', 'date_to', '%Y-%m-%d',array('style'=>'width:60%','maxlength'=>'10','class'=>'required'));?></td>
				<td align="center"><input type="text" name="data[ip_from]" class="form" style="width:50%;"></td>
				<td align="center"><input type="text" name="data[ip_to]" class="form" style="width:50%;"></td>
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
				<th><?php echo JText::_('IP Address Range From');?></th>
				<th><?php echo JText::_('IP Address Range To');?></th>
			</tr>
		</thead>
		<tbody>
			<?php foreach($this->ips as $ip):?>
			<tr class="row<?php echo $i % 2; ?>">
                <td width="5%" align="center"><?php echo JHtml::_('grid.id', $i, $ip->id); ?></td>
				<td width="5%" align="center"><?php echo $ip->date_from ?></td>
				<td width="5%" align="center"><?php echo $ip->date_to ?></td>
				<td width="5%" align="center"><?php echo $ip->ip_from ?></td>
				<td width="5%" align="center"><?php echo $ip->ip_to ?></td>
			</tr>
			<?php endforeach;?>
		</tbody>
	</table>
</div>			

