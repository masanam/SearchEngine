<?php
/**
 * @version		$Id: view.html.php 01 2013-01-11 11:37:09Z maverick $
 * @package		CoreJoomla.quizs
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

require_once JPATH_COMPONENT_ADMINISTRATOR.'/helpers/communityquizs.php';
class SearchEngineViewse_quizgroup extends JViewLegacy {
	
    protected $items;
    protected $pagination;
    protected $canDo;
    protected $lists;
    protected $state;
	protected $params;
	
	function __construct($config = array()) {
        
        parent::__construct($config);
        $this->model = JModelLegacy::getInstance('se_quizgroup', 'SearchengineModel');        
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
        else
        {
            $this->addToolBar();
        }
        
        parent::display($tpl);
    }
	
    public function addToolBar()
    {
        JToolBarHelper::title('Quiz group list');	
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
        
        $model = $this->getModel();
        $listModel = JModelLegacy::getInstance('se_quizgroup','SearchEngineModel');
		
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
        JToolbarHelper::back('Cancel', 'index.php?option=com_searchengine&view=se_quizgroup&package_id='.JRequest::getVar('package_id'));
		
		$cid = JRequest::getVar('cid');
		
		if ( $cid != '' ) {
			JToolBarHelper::title(JText::_('Edit quiz group'),'generic.png');
			$is_edit = true;
		  } else {
			JToolBarHelper::title(JText::_('Create quiz group'),'generic.png');
		  }
        
/*
frontend usersz model/controller
		$app = JFactory::getApplication();
		
		
		$appparams = JComponentHelper::getParams(S_APP_NAME);
		$menuParams = new JRegistry;
		$this->params = clone $menuParams;
		$this->params->merge($appparams);
		
		
		$limit = $this->params->get('list_length', $app->getCfg('list_limit', 20));
		$limitstart = $app->input->getInt('start', 0);
		$limitstart = ($limit != 0 ? (floor($limitstart / $limit) * $limit) : 0);
		
		//JModel::addIncludePath (JPATH_ADMINISTRATOR . DS . 'components' . DS . 'com_searchengine' . DS . 'models');
		JModelLegacy::addIncludePath(JPATH_SITE  . DS . 'components' . DS . 'com_searchengine' . DS . 'models');
		$quizs_model =& JModelLegacy::getInstance( 'quiz', 'AwardpackageUsersModel' );
		
		
		$user = JFactory::getUser();		
		
		$options = array('userid'=>20, 'limit'=>$limit, 'limitstart'=>$limitstart, 'order'=>'a.created', 'order_dir'=>'desc');
		$return = $quizs_model->get_quizs(4, $options, $this->params);
		//print_r($return);
*/
		//JModelLegacy::addIncludePath(JPATH_ADMINISTRATOR  . DS . 'components' . DS . 'com_searchengine' . DS . 'models');
		$quizs_model = JModelLegacy::getInstance( 'quizs', 'SearchEngineModel' );
		$result = $quizs_model->get_quizs(array(), 20, 0, -1);
		//print_r($result);
		
		
		//$this->assignRef('quizs', $result);
		$this->assignRef('is_edit', $is_edit);
		
        //To call layout file
        $this->setLayout('create');
    }
	
}
