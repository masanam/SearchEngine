<?php
/*
 * Archive com_searchengine
 * autohor : kadeyasa@gmail.com
 * joomla components
 */
defined('_JEXEC') or die('Restricted access');
$listOrder = $this->state->get('list.ordering');
$listDirn = $this->state->get('list.direction');
?>
<div id="j-main-container" class="span10">
<form
	action="<?php echo JRoute::_('index.php?option=com_searchengine&view=archive'); ?>"
	method="post" name="adminForm" id="adminForm">
<table class="table table-striped" width="100%">
	<thead>
		<tr style="text-align:center; background-color:#CCCCCC">
			<!-- 
			<th width="20"><input type="checkbox" name="toggle" value=""
				onclick="checkAll(<?php echo count($this->items); ?>);" />
			</th>
			 -->
			<th width="1%" class="hidden-phone"><?php echo JHtml::_('grid.checkall'); ?></th>
			<th>
				<?php echo JHtml::_('grid.sort', 'Search Package', 'Search Package', $listDirn, $listOrder); ?>
			</th>
			<th><?php echo JHtml::_('grid.sort', 'COM_AWARD_PACKAGE_DATE_TIME_CREATED', 'date_created', $listDirn, $listOrder); ?>
			</th>
			<th><?php echo JHtml::_('grid.sort', 'COM_AWARD_PACKAGE_DATE_TIME_ARCHIVED', 'date_archived', $listDirn, $listOrder); ?>
			</th>
			<th><?php echo JHtml::_('grid.sort', 'COM_AWARD_PACKAGE_NUMBER_OF_USER', 'number_of_user', $listDirn, $listOrder); ?>
			</th>
			
			</th>
			<th><?php echo JText::_('COM_AWARD_PACKAGE_DETAILS'); ?></th>
		</tr>
	</thead>
	<tbody>
	<?php foreach ($this->items as $i => $item) {
		?>
		<tr class="row<?php echo $i; ?>">
			<td align="center"><?php echo JHtml::_('grid.id', $i, $item->id); ?></td>
			<td align="center"><?php echo $item->awardpackage; ?></td>
			<td align="center"><?php echo $item->date_created; ?></td>
			<td align="center"><?php echo $item->date_archived; ?></td>
			<td align="center"><?php echo $item->number_of_user; ?></td>
			<td align="center"><a
				href="<?php echo JRoute::_('index.php?option=com_searchengine&view=archive&layout=details&id='.$item->id);?>"
				title="View Details">View</a></td>
		</tr>
		<?php
	}
	?>
	</tbody>
	<tfoot>
		<tr>
			<td colspan="10"><?php echo $this->pagination->getListFooter(); ?></td>
		</tr>
	</tfoot>
</table>
<div><input type="hidden" name="package_id"
	value="<?php echo JRequest::getVar('package_id'); ?>"> <input
	type="hidden" name="option" value="<?php echo $_REQUEST['option']; ?>" />
<input type="hidden" name="task" value="" /> <input type="hidden"
	name="view" value="<?php echo $_REQUEST['view']; ?>" /> <input
	type="hidden" name="filter_order" value="<?php echo $listOrder ?>" /> <input
	type="hidden" name="filter_order_Dir" value="<?php echo $listDirn ?>" />
<input type="hidden" name="boxchecked" value="0" /> <?php echo JHtml::_('form.token'); ?>
</div>

</form>
</div>
