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
class SearchEngineViewse_utilitylabelgroup extends JViewLegacy {
	
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
		$userId = $users->id;
		$this->packageId = $users->package_id; //this is searchengine package id
        
        $this->model = JModelLegacy::getInstance('Se_usergroup', 'SearchengineModel');
        $this->ug  = JModelLegacy::getInstance('usergroup', 'SearchengineModel');      
       
        $this->field = JRequest::getVar('field');
        $this->class       	= ' class="ui-state-default ui-corner-top ui-tabs-selected ui-state-active"';
        
    }
    
    function display($tpl = null) 
    {
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
        
        $model = $this->getModel();
        $listModel = JModelLegacy::getInstance('se_utilitylabelgroup','SearchEngineModel');
        $listData = $listModel->getListQuery();   
        //var_dump($categoryData);
        $model = $this->getModel();
        $this->assignRef('listData', $listData);
        
        //Blive Code for model ends
    }
    
    function addStyleSheet(){
        //$document = JFactory::getDocument();	
        //$document->addScript(JURI::base(true).'/components/com_searchengine/asset/js/jquery-1.2.6.js');
        //$document->addScript(JURI::base(true).'/components/com_searchengine/asset/js/jquery.ui.all.js');
        //$document->addScript(JURI::base(true).'/components/com_searchengine/asset/js/eye.js');
        //$document->addScript(JURI::base(true).'/components/com_searchengine/asset/js/layout.js');
    }
    
    
    //Create Post Function
    function create(){
        //To hide main page menu
        JRequest::setVar( 'hidemainmenu', 1 );
        
        //To show create page menu
        JToolBarHelper::title('Utility label group');
        JToolBarHelper::save();
        JToolbarHelper::back('Cancel', 'index.php?option=com_searchengine&view=se_utilitylabelgroup');
        
        //To call layout file
        $this->setLayout('create');
    }
	
}