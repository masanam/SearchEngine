<?php
/**
 * @version		$Id: default.php 01 2011-08-13 11:37:09Z maverick $
 * @package		CoreJoomla.Answers
 * @subpackage	Components
 * @copyright	Copyright (C) 2009 - 2011 corejoomla.com. All rights reserved.
 * @author		Maverick
 * @link		http://www.corejoomla.com/
 * @license		License GNU General Public License version 2 or later
 */
defined('_JEXEC') or die();

$page_id = 9;
?>
<div id="cj-wrapper">
	
	<?php include_once JPATH_COMPONENT.DS.'helpers'.DS.'header.php';?>
	
	<div class="container-fluid no-space-left no-space-right quiz-wrapper">
		<div class="row-fluid">
			<div class="span12">
				<?php if(!empty($this->items)):?>
				<table class="table table-striped table-hover">
					<thead>
						<tr>
							<th>#</th>
							<th><?php echo JText::_('LBL_NAME');?></th>
							<th width="20%"><?php echo JText::_('LBL_TYPE');?></th>
							<th width="120"><?php echo JText::_('LBL_UNSUBSCRIBE');?></th>
						</tr>
					</thead>
					<tbody>
						<?php foreach($this->items as $i=>$item):?>
						<tr>
							<td><?php echo $i+1;?></td>
							<?php if($item->subscription_type == 2):?>
							<td>
								<a href="<?php echo JRoute::_('index.php?option='.Q_APP_NAME.'&view=quizzes&id='.$item->catid.':'.$item->cat_alias.$quizzes_itemid)?>">
									<?php echo $this->escape($item->category);?>
								</a>
							</td>
							<td><?php echo JText::_('LBL_CATEGORY');?></td>
							<?php else:?>
							<td><?php echo JText::_('LBL_GLOBAL');?></td>
							<td><?php echo JText::_('LBL_GLOBAL');?></td>
							<?php endif;?>
							<td>
								<?php if($item->subscription_type == 2 || $item->subscription_type == 3):?>
								<a href="<?php echo JRoute::_('index.php?option='.Q_APP_NAME.'&view=quiz&task=unsubscribe&id='.$item->catid.$quizzes_itemid)?>" 
									class="btn btn-mini btn-unsubscribe" onclick="return false;">
									<i class="icon-minus"></i> <?php echo JText::_('LBL_UNSUBSCRIBE')?>
								</a>
								<?php endif;?>
							</td>
						</tr>
						<?php endforeach;?>
					</tbody>
				</table>
				<?php else:?>
				<div class="well"><?php echo JText::_('MSG_NO_RESULTS')?></div>
				<?php endif;?>
			</div>
		</div>
	</div>
</div>