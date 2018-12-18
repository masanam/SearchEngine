<br/>
<?php 
$session =& JFactory::getSession();
if($session->get('loggedin')){
	echo '<h3>User keyword input & Url clicked summary</h3>';
}else{
	echo '<h3>Not Logged In Keyword & Url clicked summary</h3>';
}
?>
<div id="top_table">
<?php 
if($session->get('loggedin')){
?>
			<table align="center" border="0" class="table table-striped" width="70%">
				<thead>
					<tr style="text-align:center; background-color:#CCCCCC">
						<th><?php echo JText::_('User IP Address');?></th>
						<th><?php echo JText::_('Name of user');?></th>
						<th><?php echo JText::_('User email');?></th>
						<th><?php echo JText::_('Gender');?></th>
						<th><?php echo JText::_('Country');?></th>						
						<th><?php echo JText::_('Total keyword input');?></th>
						<th><?php echo JText::_('Total Url clicked');?></th>
					</tr>
				</thead>
				<tbody>
				<?php foreach($this->summarysurlclickedsummary as $summary){
					?>
					<tr>
						<td><?php echo $summary->user_ip;?></td>
						<td><?php echo $summary->firstname." ".$summary->lastname;?></td>
						<td><?php echo $summary->email;?></td>
						<td><?php echo $summary->gender;?></td>
						<td><?php echo $summary->country;?></td>
						<td><?php echo $summary->totalkeywordinput;?></td>
						<td><?php echo $summary->totalurlclicked;?></td>
					</tr>
				<?php 
				}
				?>
				</tbody>
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
				<?php foreach($this->summarys as $summary){
					?>
					<tr>
						<td><?php echo $summary->date_time;?></td>
						<td><?php echo $summary->user_ip;?></td>
						<td>1</td>
						<td>0</td>
					</tr>
				<?php 
				}
				?>
				</tbody>
			</table>
<?php	
}
?>
</div>	
<?php echo JHtml::_('form.token'); ?>
