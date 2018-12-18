<?php
/**
 * @version		$Id: helper.php 01 2012-06-30 11:37:09Z maverick $
 * @package		CoreJoomla.Surveys
 * @subpackage	Components
 * @copyright	Copyright (C) 2009 - 2012 corejoomla.com. All rights reserved.
 * @author		Maverick
 * @link		http://www.corejoomla.com/
 * @license		License GNU General Public License version 2 or later
 */

// No direct access to this file
defined('_JEXEC') or die;
defined('S_APP_NAME') or define('S_APP_NAME', 'com_awardpackage');
defined('Q_APP_NAME') or define('Q_APP_NAME', 'com_awardpackage');

abstract class CommunityQuizsHelper{

	public static function addSubmenu($view){

		JSubMenuHelper::addEntry(JText::_('COM_COMMUNITYSURVEYS_DASHBOARD'), 'index.php?option='.S_APP_NAME.'&view=dashboard', $view == 'dashboard');
		JSubMenuHelper::addEntry(JText::_('COM_COMMUNITYSURVEYS_SURVEYS'), 'index.php?option='.S_APP_NAME.'&view=surveys', $view == 'surveys');
		JSubMenuHelper::addEntry(JText::_('COM_COMMUNITYSURVEYS_CATEGORIES'), 'index.php?option=com_categories&view=categories&extension='.S_APP_NAME, $view == 'categories');
	}

	public static function getActions($itemid = 0){
		jimport('joomla.access.access');
		$user   = JFactory::getUser();
		$result = new JObject;
		if (empty($itemid)) {				
			$assetName = S_APP_NAME;
			$level = 'component';
		}else {
			$assetName = S_APP_NAME.'.category.'.(int) $itemid;
			$level = 'category';
		}
		$actions = JAccess::getActions(S_APP_NAME, $level);
		foreach ($actions as $action) {				
			$result->set($action->name, $user->authorise($action->name, $assetName));
		}
		return $result;
	}
	
	public static function initiate(){
		$cjlib = JPATH_ROOT.DIRECTORY_SEPARATOR.'components'.DIRECTORY_SEPARATOR.'com_cjlib'.DIRECTORY_SEPARATOR.'framework.php';
		if(file_exists($cjlib)){
			require_once $cjlib;
		}else{
			die('CJLib (CoreJoomla API Library) component not found. Please <a href="http://www.corejoomla.com/downloads/required-extensions.html" target="_blank">download it here</a> and install it to continue.');
		}
		CJLib::import('corejoomla.framework.core');
		CJLib::import('corejoomla.ui.bootstrap');
		
		$document = JFactory::getDocument();
		CJFunctions::load_jquery(array('libs'=>array('fontawesome')));
		$document->addStyleSheet(CJLIB_URI.'/framework/assets/cj.framework.css');
		$document->addScript(JURI::base(true).'/components/com_awardpackage/assets/js/jquery.countdown.min.js');
		$document->addStyleSheet(JURI::base(true).'/components/com_awardpackage/assets/css/cj.surveys.admin.min.css');		
		$document->addStyleSheet(JURI::base(true).'/components/com_awardpackage/assets/css/jquery.countdown.css');
		$document->addStyleSheet(JURI::base(true).'/components/com_awardpackage/assets/css/cj.quiz.min.css');
		$document->addScript(JURI::base(true).'/components/com_awardpackage/assets/js/cj.surveys.admin.min.js'); 
		$document->addScript(JURI::base(true).'/components/com_awardpackage/assets/js/cj.quiz.min.js');
		$document->addScript(JURI::base(true).'/components/com_awardpackage/assets/js/cj.surveys.min.js');
		$document->addStyleSheet(JURI::base(true).'/components/com_awardpackage/assets/css/aw.style.css');
		
	}
}
