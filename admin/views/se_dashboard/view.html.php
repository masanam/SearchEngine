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
class SearchEngineViewse_dashboard extends JViewLegacy {
	
    protected $items;
    protected $pagination;
    protected $canDo;
    protected $lists;
    protected $state;
	
	function __construct($config = array()) {
        
        parent::__construct($config);
        
        $this->model = JModelLegacy::getInstance('se_dashboard', 'SearchengineModel');
        $this->package_id = JRequest::getVar('package_id');
		
		$user	= JFactory::getUser();
		$this->user_id = $user->id;        
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
		else if ($task =='edit') {
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
        JToolBarHelper::title('Search engine rewards list');
        JToolBarHelper::addNew();
        JToolBarHelper::deleteList();
        JToolbarHelper::back('Back', 'index.php?option=com_searchengine&package_id='.JRequest::getVar('package_id'));
        
        //Blive Code for model start
        
        $pageLimit = 10;
		
		$page = isset($_GET['page']) ? ((int) $_GET['page']) : 1;
			if( $page > 1  ) {				
				$limitstart = ($page-1) * $pageLimit; 
			}else {				
				$limitstart = 0;
			}
        
        		
		//$ticketgroups =$this->model->getTicketGroupList($this->package_id,$limitstart,$pageLimit);
		//$this->package_id,$limitstart,$pageLimit
		//$listData =$this->model->getListQuery();
	
        $listData = $this->model->getListQueryPagi($this->user_id,$this->package_id,$limitstart,$pageLimit);   
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
        $document = JFactory::getDocument();	
        $document->addScript(JURI::base(true).'/components/com_searchengine/asset/js/jquery-1.2.6.js');
        //$document->addScript(JURI::base(true).'/components/com_searchengine/asset/js/jquery.ui.all.js');
        $document->addScript(JURI::base(true).'/components/com_searchengine/asset/js/eye.js');
        $document->addScript(JURI::base(true).'/components/com_searchengine/asset/js/layout.js');
		
		
		
    }
    
    
     function create(){
        //To hide main page menu
        JRequest::setVar( 'hidemainmenu', 1 );
        
        //To show create page menu
        
        JToolBarHelper::save();
        JToolbarHelper::back('Cancel', 'index.php?option=com_searchengine&view=se_dashboard&package_id='.JRequest::getVar('package_id'));
		
		$cid = JRequest::getVar('cid');
		
		if ( $cid != '' ) {
			JToolBarHelper::title(JText::_('Search engine rewards list'),'generic.png');
			$is_edit = true;
		  } else {
			JToolBarHelper::title(JText::_('Search engine rewards list'),'generic.png');
		  }
        
		//
		$this->model = JModelLegacy::getInstance('se_dashboard', 'SearchEngineModel');		
		
		$seuglist = $this->model->get_seuglist($this->package_id,$this->user_id);			
		$this->seuglist = $seuglist;
		
		$qglist = $this->model->get_qglist($this->package_id,$this->user_id);			
		$this->qglist = $qglist;
		
		$sglist = $this->model->get_sglist($this->package_id,$this->user_id);			
		$this->sglist = $sglist;
		
		$urlist = $this->model->get_urlist($this->package_id,$this->user_id);			
		$this->uglist = $urlist;
		
		$kglist = $this->model->get_kglist($this->package_id,$this->user_id);			
		$this->kglist = $kglist;
		
		
		$user	= JFactory::getUser();	
		$userdata = new stdClass();
			
		$userdata->created_by = $user->id;
		$userdata->username = $user->username;
		$userdata->name = $user->name;
		$userdata->email = $user->email;		
		$this->assignRef('item', $userdata);
		
		$this->assignRef('is_edit', $is_edit);
		
        //To call layout file
		
		if ( $cid != '' ) {
		
			$get_serl_details = $this->model->get_serl_by_id($cid);
			$this->assignRef('serl_details',$get_serl_details);
			
			//print_r($get_serl_details);
			
			$this->setLayout('edit');
		  } else {
			$this->setLayout('create');
		  }
    }
	
}
