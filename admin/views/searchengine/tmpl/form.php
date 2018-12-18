<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted Access');
// load tooltip behavior
JHtml::_('behavior.tooltip');
?>
<form action="<?php echo JRoute::_('index.php?option=com_searchengine&controller=donation&task=save_status'); ?>" method="post" name="adminForm">
<table width="20%">
<tr><td>
<table class="adminlist">
<input type="hidden" name="transaction_id" value="<?php echo $this->transaction_id;?>"/>
<tr><td><b>Donation ID</b></td><td><a href="<?php echo JRoute::_('index.php?option=com_searchengine&controller=donation&task=view&package_id='.JRequest::getVar('package_id').'&transaction_id='.$this->transaction_id);?>"><?php echo $this->transaction_id;?></a></td></tr>
<tr><td><b>Date</b></td><td><?php echo $this->dated;?></td></tr>
<tr><td><b>Amount</b></td><td><?php echo $this->iscent($this->amount,2);?></td></tr>
<tr><td><b>Status</b></td><td>
<input type="text" value="<?php echo $this->status;?>" name="status"></td></tr>
<tr><td colspan="2"><i><font size="1">Completed = You have received the payment on your paypal account</font></i></td></tr>
</table>
	<div>
	
		<input type="hidden" name="transaction_id" value="<?php echo $this->transaction_id;?>" />
		<input type="hidden" name="task" value="save_status" />
		<input type="hidden" name="boxchecked" value="1" />
		<?php echo JHtml::_('form.token'); ?>
	</div>
</td></tr>
</table>
</form>