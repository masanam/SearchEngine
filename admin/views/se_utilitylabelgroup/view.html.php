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

require_once JPATH_COMPONENT_ADMINISTRATOR.'/helpers/communitysurveys.php';
class SearchEngineViewse_utilitylabelgroup extends JViewLegacy {
	
    protected $items;
    protected $pagination;
    protected $canDo;
    protected $lists;
    protected $state;
    
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
        JToolBarHelper::title('Utility label group');
        //JToolBarHelper::addNew();
        //JToolBarHelper::deleteList();
        JToolbarHelper::back('Back', 'index.php?option=com_searchengine&package_id='.JRequest::getVar('package_id'));
        
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
        $document = JFactory::getDocument();	
        $document->addScript(JURI::base(true).'/components/com_searchengine/asset/js/jquery-1.2.6.js');
        //$document->addScript(JURI::base(true).'/components/com_searchengine/asset/js/jquery.ui.all.js');
        $document->addScript(JURI::base(true).'/components/com_searchengine/asset/js/eye.js');
        $document->addScript(JURI::base(true).'/components/com_searchengine/asset/js/layout.js');
    }
    
    
    //Create Post Function
    function create(){
        //To hide main page menu
        JRequest::setVar( 'hidemainmenu', 1 );
        
        //To show create page menu
        JToolBarHelper::title('Utility label group');
        JToolBarHelper::save();
        JToolbarHelper::back('Cancel', 'index.php?option=com_searchengine&view=se_utilitylabelgroup&package_id='.JRequest::getVar('package_id'));
        
        //To call layout file
        $this->setLayout('create');
    }
	
}