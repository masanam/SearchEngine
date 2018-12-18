<?php
/**
 * @version		$Id: header.php 01 2011-08-13 11:37:09Z maverick $
 * @package		CoreJoomla.Quiz
 * @subpackage	Components
 * @copyright	Copyright (C) 2009 - 2011 corejoomla.com. All rights reserved.
 * @author		Maverick
 * @link		http://www.corejoomla.com/
 * @license		License GNU General Public License version 2 or later
 */
defined('_JEXEC') or die();

$user = JFactory::getUser();
$itemid = CJFunctions::get_active_menu_id();
$user_itemid = CJFunctions::get_active_menu_id(true, 'index.php?option='.Q_APP_NAME.'&view=user');
$quizzes_itemid = CJFunctions::get_active_menu_id(true, 'index.php?option='.Q_APP_NAME.'&view=quiz');
$catparam = !empty($catparam) ? $catparam : '';
?>

<?php if($this->params->get('display_toolbar', 1) == 1 && !$this->print && $page_id >= 0):?>
<div class="navbar">
	<div class="navbar-inner" style="background-color: #fafafa;">	
		<div class="header-container">
			<a class="btn btn-navbar" data-toggle="collapse" data-target=".cq-nav-collapse"> 
				<span class="icon-bar"></span> 
				<span class="icon-bar"></span> 
				<span class="icon-bar"></span>
			</a>
			 
			<a class="brand" href="<?php echo JRoute::_('index.php?option='.Q_APP_NAME.'&view=quiz&task=quiz.get_latest_quizzes'.$catparam.$quizzes_itemid);?>">
				<?php echo $this->escape($this->brand);?>
			</a>

			<div class="cq-nav-collapse nav-collapse collapse">
				<ul class="nav">
					<li class="dropdown<?php echo $page_id > 0 && $page_id < 5 ? ' active' : ''?>">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown"><?php echo JText::_('LBL_DISCOVER');?> <b class="caret"></b></a>
						<ul class="dropdown-menu">
							<li class="nav-header"><?php echo JText::_('LBL_QUIZZES');?></li>
							<li<?php echo $page_id == 1 ? ' class="active"' : '';?>>
								<a href="<?php echo JRoute::_('index.php?option='.Q_APP_NAME.'&view=quiz&task=quiz.get_latest_quizzes'.$catparam.$quizzes_itemid);?>">
									<i class="fa fa-tasks"></i> <?php echo JText::_('TXT_LATEST_QUIZZES');?>
								</a>
							</li>
							<li<?php echo $page_id == 2 ? ' class="active"' : '';?>>
								<a href="<?php echo JRoute::_('index.php?option='.Q_APP_NAME.'&view=quiz&task=quiz.get_popular_quizzes'.$catparam.$quizzes_itemid);?>">
									<i class="fa fa-fire"></i> <?php echo JText::_('TXT_MOST_POPULAR_QUIZZES');?>
								</a>
							</li>
							<li<?php echo $page_id == 3 ? ' class="active"' : '';?>>
								<a href="<?php echo JRoute::_('index.php?option='.Q_APP_NAME.'&view=quiz&task=quiz.get_top_rated_quizzes'.$catparam.$quizzes_itemid);?>">
									<i class="fa fa-bar-chart-o"></i> <?php echo JText::_('TXT_TOP_RATED_QUIZZES');?>
								</a>
							</li>
							<li class="divider"></li>
							<li class="nav-header"><?php echo JText::_('LBL_SEARCH')?></li>
							<li <?php echo $page_id == 4 ? 'class="active"' : ''?>>
								<a href="<?php echo JRoute::_('index.php?option='.Q_APP_NAME.'&view=quiz&task=quiz.redirect_to_search'.$quizzes_itemid);?>">
									<i class="fa fa-search"></i> <?php echo JText::_('LBL_ADVANCED_SEARCH');?>
								</a>
							</li>
						</ul>
					</li>
					<li <?php echo $page_id == 5 ? 'class="active"' : ''?>>
						<a href="<?php echo JRoute::_('index.php?option='.Q_APP_NAME.'&view=quiz&task=quiz.get_tagged_quizzes'.$quizzes_itemid);?>">
							<?php echo JText::_('LBL_TAGS');?>
						</a>
					</li>
				</ul>
				
				<ul class="nav pull-right">
				
					<?php 
						//if(!$user->guest && $user->authorise('quiz.create', Q_APP_NAME)):						
					?>
					<li<?php echo $page_id == 6 ? ' class="active"' : '';?>>
						<a href="<?php echo JRoute::_('index.php?option='.Q_APP_NAME.'&view=form&task=form.add_new_quiz'.$itemid);?>"><?php echo JText::_('LBL_CREATE_QUIZ')?></a>
					</li>
					<?php 
						//endif;
					?>
					
					<?php 
						//if(!$user->guest && !empty($user_itemid)):						
					?>
					<li class="divider-vertical"></li>
					
					<li class="dropdown<?php echo $page_id > 6 && $page_id < 10 ? ' active' : '';?>">
					
						<a href="#" class="dropdown-toggle" data-toggle="dropdown"><?php echo JText::_('LBL_MY_STUFF');?> <b class="caret"></b></a>
						
						<ul class="dropdown-menu">
						
							<?php //if($user->authorise('quiz.create', Q_APP_NAME)): ?>
							<li <?php echo $page_id == 7 ? 'class="active"' : ''?>>
								<a href="<?php echo JRoute::_('index.php?option='.Q_APP_NAME.'&view=userquiz&task=userquiz.get_my_quizzes'.$user_itemid);?>">
									<i class="fa fa-question-circle"></i> <?php echo JText::_('LBL_MY_QUIZZES');?>
								</a>
							</li>							
							<?php //endif; ?>
							
							<?php //if($user->authorise('quiz.respond', Q_APP_NAME)): ?>
							<li <?php echo $page_id == 8 ? 'class="active"' : ''?>>
								<a href="<?php echo JRoute::_('index.php?option='.Q_APP_NAME.'&view=userquiz&task=userquiz.get_my_responses'.$user_itemid);?>">
									<i class="fa fa-reply"></i> <?php echo JText::_('LBL_MY_RESPONSES');?>
								</a>
							</li>
							<?php //endif; ?>
							
							<!-- 
							<li class="divider"></li>
							<li <?php echo $page_id == 9 ? 'class="active"' : ''?>>
								<a href="<?php echo JRoute::_('index.php?option='.Q_APP_NAME.'&view=userquiz&task=userquiz.get_my_subscriptions'.$user_itemid);?>">
									<i class="fa fa-bookmark"></i> <?php echo JText::_('LBL_MY_SUBSCRIPTIONS');?>
								</a>
							</li>
							 -->							
						</ul>
						
					</li>
					<?php //endif; ?>
					
				</ul>
				
			</div>
		</div>
	</div>
</div>
<?php endif;?>