<?php
// no direct access
defined('_JEXEC') or die('Restricted access');
jimport('joomla.application.component.controller');
jimport('joomla.filesystem.file');
jimport('joomla.filesystem.folder');
require_once JPATH_COMPONENT_ADMINISTRATOR.'/helpers/communitysurveys.php';
require_once JPATH_COMPONENT . '/helpers/awardpackages.php';


class SearchEngineControllerSearch extends JControllerLegacy {
	function __construct(){
		parent::__construct();
	}
	
	function list_list(){
		$view = $this->getView('dashboard', 'html');				
		$view->display('list');	
	}
	
	function summary(){
		$view = $this->getView('dashboard', 'html');				
		$view->display('summary');	
	}
}