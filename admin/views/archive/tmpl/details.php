<?php
/*
 * Archive com_awardpackage
 * autohor : kadeyasa@gmail.com
 * joomla components
 */
defined('_JEXEC') or die('Restricted access');
?>
<div id="j-main-container" class="span10">
<form
	action="<?php echo JRoute::_('index.php?option=com_awardpackage&view=archive&layout=details'); ?>"
	method="post" name="adminForm" id="adminForm">
<table class="table table-striped">
	<thead>
		<tr style="text-align:center; background-color:#CCCCCC">
			<th><?php echo JText::_('COM_AWARDPACKAGE'); ?></th>
			<th><?php echo JText::_('COM_AWARD_PACKAGE_DATE_TIME_CREATED'); ?></th>
			<th><?php echo JText::_('Start Date'); ?></th>
			<th><?php echo JText::_('End Date'); ?></th>
			<th><?php echo JText::_('Duration'); ?></th>
			<th><?php echo JText::_('COM_AWARD_PACKAGE_NUMBER_OF_USER'); ?></th>
			<th><?php echo JText::_('COM_AWARD_PACKAGE_NUMBER_OF_PRIZE'); ?></th>
		</tr>
		<?php
		foreach ($this->details as $k => $item) {
			$date1 = explode("-", $item->start_date);
			$date2 = explode("-", $item->end_date);
			$start = mktime(0, 0, 0, $date1[1], $date1[2], $date1[0]);
			$end = mktime(0, 0, 0, $date2[1], $date2[2], $date2[0]);

			?>
		<tr class="row<?php echo $k; ?>">
			<td align="center"><?php echo $item->awardpackage; ?></td>
			<td align="center"><?php echo $item->date_created; ?></td>
			<td align="center"><?php echo $item->start_date; ?></td>
			<td align="center"><?php echo $item->end_date; ?></td>
			<td align="center"><?php echo floor(($end - $start) / 60 / 60 / 24) . ' days';?></td>
			<td align="center"><?php echo count($this->model->getApUser($item->package_id));?></td>
			<td align="center"><?php echo count($this->model->getPrize($item->package_id));?></td>
		</tr>
		<?php
		}
		?>
	</thead>
</table>
<div><input type="hidden" name="task" value="" /></div>
</form>

</div>
