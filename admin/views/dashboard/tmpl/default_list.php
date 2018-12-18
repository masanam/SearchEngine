<br/>
<?php 
$session =& JFactory::getSession();
if($session->get('loggedin')){
	echo '<h3>User keyword input & Url clicked list</h3>';
}else{
	echo '<h3>Not Logged In Keyword & Url clicked list</h3>';
}
?>
<div id="splitter">
	<div class="splitter-panel">
		<div id="top_table">
		
<?php 
if($session->get('loggedin')){
?>
			<table align="center" border="0" class="table table-striped" width="70%">
				<thead>
					<tr style="text-align:center; background-color:#CCCCCC">
						<th><?php echo JText::_('User IP');?></th>
						<th><?php echo JText::_('User email');?></th>						
						<th><?php echo JText::_('Keyword Input date time');?></th>
						<th><?php echo JText::_('Keyword Input');?></th>						
						<th><?php echo JText::_('Url clicked date time');?></th>
						<th><?php echo JText::_('Url clicked');?></th>
					</tr>
				</thead>
				<tbody>
				<?php foreach($this->summarysurlclickedlist as $summary){
					?>
					<tr>
						<td><?php echo $summary->user_ip;?></td>
						<td><?php echo $summary->email;?></td>
						<td><?php echo $summary->date_time;?></td>
						<td><?php echo $summary->keyword;?></td>
						<td><?php echo $summary->clicked_date_time;?></td>
						<td><?php echo $summary->url_clicked;?></td>
					</tr>
				<?php 
				}
				?>
				</tbody>
			</table>
			<table width="100%">
                <tr><td style="text-align:right;">
                <div class="pagination pagination-toolbar">
                  <?php 
                  echo $this->pagination;                  
                ?>
                </div>
                </td></tr>
              </table>
<?php	
}else{ ?>
			<table align="center" border="0" class="table table-striped" width="70%">
				<thead>
					<tr style="text-align:center; background-color:#CCCCCC">
						<th><?php echo JText::_('Last Updated');?></th>
						<th><?php echo JText::_('User IP Address');?></th>
						<th><?php echo JText::_('Total Keyword Input');?></th>
						<th><?php echo JText::_('Total Url Clicked');?></th>
					</tr>
				</thead>
				<tbody>
				
				</tbody>
			</table>
<?php	
}
?>			
		</div>
	</div>
</div>
<?php echo JHtml::_('form.token'); ?>
