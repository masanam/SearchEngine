<?php
// no direct access
defined('_JEXEC') or die('Restricted access');
jimport('joomla.application.component.controller');
require_once JPATH_COMPONENT_ADMINISTRATOR.'/helpers/communitysurveys.php';
require_once JPATH_COMPONENT . '/helpers/awardpackages.php';


class SearchEngineControllerNotLoggedin extends JControllerLegacy {
	function __construct(){
		parent::__construct();
	}
	
	public function loggedout(){
		$session =& JFactory::getSession();
		$session->set("loggedin","");
		$this->setRedirect('index.php?option=com_searchengine&view=dashboard','Search Logged out');
	}
	
	public function loggedin(){
		$session =& JFactory::getSession();
		$session->set("loggedin","1");
		if(JRequest::getVar('package_id')==0){
			$this->setRedirect('index.php?option=com_searchengine&package_id='.JRequest::getVar('package_id'),'Select one package');
			return false;
		}
		$this->setRedirect('index.php?option=com_searchengine&view=dashboard&package_id='.JRequest::getVar('package_id'),'Search Logged in');
	}
}