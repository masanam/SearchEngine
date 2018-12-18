<?php

// No direct access to this file
defined('_JEXEC') or die('Restricted access');
// import Joomla controller library
jimport('joomla.application.component.controller');

//require files
/*require_once JPATH_SITE . DS . 'components' . DS . 'com_awardpackage' . DS . 'classes' . DS . 'loader.php';*/
require_once JPATH_SITE.'/components/com_cjlib/framework/functions.php';
require_once JPATH_COMPONENT_ADMINISTRATOR.'/helpers/constants.php';
//require_once JPATH_COMPONENT_ADMINISTRATOR.'/helpers/communitysurveys.php';

class SearchEngineController extends JControllerLegacy {
	/*
		Author 	: I Kade Yasa
		Package	: Awardpackage
		*/
	function __construct() {
		parent::__construct();
	}

	function display($cachable = false, $urlparams = false){
		//require helper
		/*
		 require_once JPATH_COMPONENT . '/helpers/general.php';
		 */
		require_once JPATH_COMPONENT . '/helpers/awardpackage.php';
		require_once JPATH_COMPONENT . '/helpers/searchenginepackage.php';

		//award helper
		SearchenginePackageHelper::addStyleSheet();

		//SearchenginePackageHelper::checkUser();

		//set view
		//JRequest::setVar('view', JRequest::getCmd('view', 'Donation'));
		JRequest::setVar('view', JRequest::getCmd('view', 'searchengine'));
		parent::display($cachable);
	}
	function upload(){
		return SearchenginePackageHelper::upload();
	}
}
?>
