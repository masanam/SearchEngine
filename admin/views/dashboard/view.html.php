<?php
/**
 * @version		$Id: view.html.php 01 2013-01-11 11:37:09Z maverick $
 * @package		CoreJoomla.surveys
 * @subpackage	Components.views
 * @copyright	Copyright (C) 2009 - 2013 corejoomla.com, Inc. All rights reserved.
 * @author		Maverick
 * @link		http://www.corejoomla.com/
 * @license		License GNU General Public License version 2 or later
 */
defined('_JEXEC') or die();

jimport( 'joomla.application.component.view');
require_once JPATH_COMPONENT_ADMINISTRATOR.'/helpers/communitysurveys.php';
class SearchEngineViewDashboard extends JViewLegacy {
	
	protected $items;
	protected $pagination;
	protected $canDo;
	protected $lists;
	
    function display($tpl = null) {
		$task = JRequest::getVar('task');
		if($task==''){
			$this->active ='summary';
		}else{
			$this->active ='list';
		}
		$this->package_id = JRequest::getVar('package_id');
	
		
		$searchview = JRequest::getVar('searchview');

		$this->searchview = JRequest::getVar('searchview');
		
		$model =& JModelLegacy::getInstance('searchengine','SearchEngineModel');
		
		$this->summarys = $model->getSummarySearch($this->package_id,$searchview);
		
		if(!empty($this->package_id)){
			$this->summarysurlclickedsummary = $model->getUrlClickedSummary($this->package_id,$searchview);
			
			
			$pageLimit = 50;
		
			$page = isset($_GET['page']) ? ((int) $_GET['page']) : 1;
			if( $page > 1  ) {				
				$limitstart = ($page-1) * $pageLimit; 
			}else {				
				$limitstart = 0;
			}
			
			$this->summarysurlclickedlist = $model->getUrlClickedList($this->package_id,$searchview,$limitstart,$pageLimit);
			
			$total= $model->total;
			
			$pagination = new SearchenginePaginationHelper;
			$pagination->setCurrent($page);				
			$pagination->setTotal($total);
			$pagination->addClasses('pagination-list');
			$pagination->setKey('page');
			$pagination->setNext('<span class="icon-next"></span>');
			$pagination->setPrevious('<span class="icon-previous"></span>');
			$pagination->setRPP($pageLimit);
			$this->pagination = $pagination->parse();
			
			
			
		}

    	//add toolbar
		$this->addToolBar(); 		
		parent::display($tpl);
    }
	
	protected function addToolBar()
	{
		$session =& JFactory::getSession();
		$this->loggedin=$session->get('loggedin');
		if($this->loggedin){
			JToolBarHelper::title('Logged In - Keyword Input & Url Clicked Summary');	
		}else{
			JToolBarHelper::title('Not Logged In - Keyword Input & Url Clicked Summary');	
		}
		
		JToolBarHelper::custom('searchengine.addNew', 'new.png', 'new.png', 'New', false,false);
		JToolBarHelper::custom('searchengine.delete', 'delete.png', 'delete.png', 'Delete', true);
		JToolBarHelper::custom('searchengine.publish', 'publish.png', 'publish.png', 'Publish', true);
		JToolBarHelper::custom('searchengine.unpublish', 'unpublish.png', 'unpublish.png', 'UnPublish', true);
		JToolBarHelper::custom('notloggedin.loggedout', '', 'new.png', 'Not logged in', false,false);
		JToolBarHelper::custom('notLoggedin.loggedin', '', 'new.png', 'Logged in', false,false);
		//JToolBarHelper::custom('searchengine.addNew', 'search.png', 'search.png', 'Search', false,false);
		JToolbarHelper::back('Cancel', 'index.php?option=com_searchengine&package_id='.JRequest::getVar('package_id'));
		
		$document = JFactory::getDocument();
		
		$document->addStyleSheet(JUri::base() . 'components/com_searchengine/assets/css/jquery.ui.all.css');		
		$document->addStyleSheet(JUri::root() .'media/jui/css/bootstrap.min.css');

		$document->addScript(JUri::base() . 'components/com_searchengine/assets/js/jquery.min.js');
		$document->addScript('//code.jquery.com/ui/1.11.4/jquery-ui.min.js');		
		$document->addScript(JUri::base() . 'components/com_searchengine/assets/js/jquery.ui.core.js');
		$document->addScript(JUri::base() . 'components/com_searchengine/assets/js/jquery.ui.widget.js');
		$document->addScript(JUri::base() . 'components/com_searchengine/assets/js/jquery.ui.tabs.js');
		$document->addScript(JUri::base() . 'components/com_searchengine/assets/js/tabs.js');
		$document->addScript('//services.iperfect.net/js/IP_generalLib.js');


 		$document->addStyleSheet(JURI::base() . 'components/com_searchengine/assets/jqwidgets/styles/jqx.base.css');

		$document->addScript(JUri::base() . 'components/com_searchengine/assets/jqwidgets/jqxcore.js');
		$document->addScript(JUri::base() . 'components/com_searchengine/assets/jqwidgets/jqxsplitter.js');
		$document->addScript(JUri::base() . 'components/com_searchengine/assets/jqwidgets/jqxbuttons.js');
		$document->addScript(JUri::base() . 'components/com_searchengine/assets/jqwidgets/jqxscrollbar.js');
		$document->addScript(JUri::base() . 'components/com_searchengine/assets/jqwidgets/jqxpanel.js');
		$document->addScript(JUri::base() . 'components/com_searchengine/assets/js/jquery.countdownTimer.js');
		$document->addScript(JURI::base() . 'components/com_searchengine/assets/js/country.js');
		$document->addScript(JURI::base() . 'components/com_searchengine/assets/js/moment.js');
		$document->addScript(JURI::base() . 'components/com_searchengine/assets/js/moment-timezone-with-data.min.js');


	}
	
}
