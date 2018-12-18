<style>
#cssmenu {
  
  list-style: none;
  margin: 0;
  padding: 0;
  width: 12em;
}
#cssmenu li {
  font: 100% "Lucida Sans Unicode", "Bitstream Vera Sans", "Trebuchet Unicode MS", "Lucida Grande", Verdana, Helvetica, sans-serif;
  margin: 0;
  padding: 0;
  list-style: none;
}
#cssmenu a {
  
  display: block;
  margin: 0;
  padding: 8px 12px;
  text-decoration: none;
  font-weight: normal;
}
#cssmenu a:hover {
  padding-bottom: 8px;
}
</style>
<?php
defined('_JEXEC') or die();
$user = JFactory::getUser();
?>

<div id='cssmenu'>
<ul>
	<li
		class="<?php echo $this->page_id == "1" ? ' active' : '' ?>">
	<a
		href="<?php echo JRoute::_('index.php?option='.S_APP_NAME.'&view=uaccount&task=uaccount.getFunds&accountId='.JRequest::getVar("accountId").'&package_id='.JRequest::getVar("package_id").'');?>"><?php echo JText::_('Account')?></a>
	</li>	
	<li
		class="<?php echo $this->page_id == "2" ? ' active' : '' ?>">
	<a
		href="<?php echo JRoute::_('index.php?option='.S_APP_NAME.'&view=ufunding&task=ufunding.getMainPage&accountId='.JRequest::getVar("accountId").'&package_id='.JRequest::getVar("package_id").'');?>"><?php echo JText::_('Funds')?></a>
	</li>
	<li
		class="<?php echo $this->page_id == "3" ? ' active' : '' ?>">
	<a
		href="<?php echo JRoute::_('index.php?option='.S_APP_NAME.'&&view=udonation&task=udonation.getMainPage&accountId='.JRequest::getVar("accountId").'&package_id='.JRequest::getVar("package_id").'');?>"><?php echo JText::_('Donation')?></a>
	</li>	
	<li
		class="<?php echo $this->page_id == "4" ? ' active' : '' ?>">
	<a
		href="<?php echo JRoute::_('index.php?option='.S_APP_NAME.'&view=uaccount&task=uaccount.getFunds&accountId='.JRequest::getVar("accountId").'&package_id='.JRequest::getVar("package_id").'');?>"><?php echo JText::_('Quiz')?></a>
	</li>	
	<li
		class="<?php echo $this->page_id == "5" ? ' active' : '' ?>">
	<a
		href="<?php echo JRoute::_('index.php?option='.S_APP_NAME.'&view=uaccount&task=uaccount.getFunds&accountId='.JRequest::getVar("accountId").'&package_id='.JRequest::getVar("package_id").'');?>"><?php echo JText::_('Survey')?></a>
	</li>
	<li
		class="<?php echo $this->page_id == "6" ? ' active' : '' ?>">
	<a
		href="<?php echo JRoute::_('index.php?option='.S_APP_NAME.'&view=ugiftcode&task=ugiftcode.getMainPage&accountId='.JRequest::getVar("accountId").'&package_id='.JRequest::getVar("package_id").'');?>"><?php echo JText::_('Giftcode')?></a>
	</li>
	<li
		class="<?php echo $this->page_id == "7" ? ' active' : '' ?>">
	<a
		href="<?php echo JRoute::_('index.php?option='.S_APP_NAME.'&view=uprize&task=uprize.getMainPage&accountId='.JRequest::getVar("accountId").'&package_id='.JRequest::getVar("package_id").'');?>"><?php echo JText::_('Prize')?></a>
	</li>
	<li
		class="<?php echo $this->page_id == "8" ? ' active' : '' ?>">
	<a
		href="<?php echo JRoute::_('index.php?option='.S_APP_NAME.'&view=ushoppingcreditplan&task=ushoppingcreditplan.getMainPage&accountId='.JRequest::getVar("accountId").'&package_id='.JRequest::getVar("package_id").'');?>"><?php echo JText::_('Shopping Credit')?></a>
	</li>
					
</ul>
</div>


