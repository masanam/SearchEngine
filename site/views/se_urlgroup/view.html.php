<?php
/**
 * @version		$Id: view.html.php 01 2013-01-11 11:37:09Z maverick $
 * @package		CoreJoomla.surveys
 * @subpackage          Components.views
 * @copyright           Copyright (C) 2009 - 2013 corejoomla.com, Inc. All rights reserved.
 * @author		Maverick
 * @link		http://www.corejoomla.com/
 * @license		License GNU General Public License version 2 or later
 */

// no direct access
defined('_JEXEC') or die();

// Import Joomla! libraries
jimport( 'joomla.application.component.view');

//require_once JPATH_COMPONENT_ADMINISTRATOR.'/helpers/communitysurveys.php';
class SearchEngineViewse_urlgroup extends JViewLegacy {
	
    protected $items;
    protected $pagination;
    protected $canDo;
    protected $lists;
    protected $state;
	
	function __construct($config = array()) {
        
        parent::__construct($config);
		
		$app 			= JFactory::getApplication();
		$user = JFactory::getUser();
		if($user->get('guest')){
		    $redirectUrl = urlencode(base64_encode('index.php?option=com_searchengine&view=dashboard'));  
		    $errorMsg = 'Please login or <a href="index.php?option=com_awardpackage&view=user&task=user.updateDetailInfo">Register</a>';

			$app->redirect('index.php?option=com_users&view=login&return='.$redirectUrl,$errorMsg); 
		}
		
		$users = SearchenginePackageHelper::getUserData();
		$this->userId = $users->id;
		$this->packageId = $users->package_id; //this is searchengine package id
        
        $this->model = JModelLegacy::getInstance('se_urlgroup', 'SearchengineModel');
    }
    
    function display($tpl = null) 
    {
		JHtml::_('bootstrap.framework');
        $task = JRequest::getVar('task');
        if($task=='')
        {
            $this->active ='summary';
        }
        else
        {
            $this->active ='list';
        }
        
        //add stylesheet
        $this->addStyleSheet();
        
        $act = JRequest::getVar('layout');
        
        //To Create Post Function
        if ($act == 'create') 
        {  
            $this->create();
        }
        else
        {
            $this->addToolBar();
        }
        
        parent::display($tpl);
    }
	
    public function addToolBar()
    {        
        //Blive Code for model start
        
        $pageLimit = 10;
		
		$page = isset($_GET['page']) ? ((int) $_GET['page']) : 1;
			if( $page > 1  ) {				
				$limitstart = ($page-1) * $pageLimit; 
			}else {				
				$limitstart = 0;
			}
        
        		
	
        $listData = $this->model->getListQueryPagi($this->userId,$this->packageId,$limitstart,$pageLimit);   
        //var_dump($categoryData);
        //$model = $this->getModel();
        $this->assignRef('listData', $listData);
		
		$total= $this->model->total;

		$pagination = new SearchenginePaginationHelper;
		$pagination->setCurrent($page);				
		$pagination->setTotal($total);
		$pagination->addClasses('pagination-list');
		$pagination->setKey('page');
		$pagination->setNext('<span class="icon-next"></span>');
		$pagination->setPrevious('<span class="icon-previous"></span>');
		$pagination->setRPP($pageLimit);
		$this->pagination = $pagination->parse();
        
        //Blive Code for model ends
    }
    
    function addStyleSheet(){
        //$document = JFactory::getDocument();	
        //$document->addScript(JURI::base(true).'/components/com_searchengine/asset/js/jquery-1.2.6.js');
        //$document->addScript(JURI::base(true).'/components/com_searchengine/asset/js/jquery.ui.all.js');
        //$document->addScript(JURI::base(true).'/components/com_searchengine/asset/js/eye.js');
        //$document->addScript(JURI::base(true).'/components/com_searchengine/asset/js/layout.js');
    }
    
    
     function create(){
        //To hide main page menu
        JRequest::setVar( 'hidemainmenu', 1 );
        		
		$cid = JRequest::getVar('cid');
		
		if ( $cid != '' ) {
			$is_edit = true;
		  }
		
		$this->assignRef('is_edit', $is_edit);
		
        //To call layout file
        $this->setLayout('create');
    }
	
}