<?php

defined('_JEXEC') or die('Restricted access');
jimport('joomla.application.component.view');

class SearchEngineViewse_urlreward extends JViewLegacy {
    
    function __construct($config = array()) {
        
        parent::__construct($config);
        
        $this->model = JModelLegacy::getInstance('Se_urlreward', 'SearchEngineModel');
        
        $this->package_id = JRequest::getVar('package_id');
		
		$user	= JFactory::getUser();
		$this->user_id = $user->id;               
    }

    function display($tpl = null) {
	
		$app = JFactory::getApplication();
		$document= JFactory::getDocument();
		JHtml::_('bootstrap.framework');

		$document->addStyleSheet(JURI::base(true).'/components/com_searchengine/assets/css/bootstrap-datepicker.min.css');
		$document->addStyleSheet(JURI::base(true).'/components/com_searchengine/assets/css/custom.css');
		$document->addScript(JURI::base(true).'/components/com_searchengine/assets/js/bootstrap-datepicker.min.js');

		$task = JRequest::getVar('task');
		$pageLimit = 10;
			
		if ($task =='create') {

			JToolBarHelper::title('Url reward list');
			$this->addToolbarForCreatePage();

			$membertickets = $this->model->getUrlGroupList($this->package_id);			
			$this->membertickets = $membertickets;
			
				
			$model1 = JModelLegacy::getInstance('SearchEngine', 'SearchEngineModel');
			$sedetails = $model1->getSearEnginePackage($this->package_id);
		
			
			$categories = $this->model->get_categories($sedetails->awardpackageid);	
	
			$this->assignRef("categories", $categories);

			$this->setLayout('create');
			
			$this->active ='create';

		}else if ($task =='edit') {
			
			$id = $app->input->getInt('id');	
			
			$this->assignRef('id',$id);		

			JToolBarHelper::title('Url reward list');
			
			$this->addToolbarForCreatePage();
						
			$get_rule_details = $this->model->get_rule_details_by_id($id);

			$this->assignRef('rule_details',$get_rule_details);
			
			$get_rule_settings = $this->model->get_rule_settings_by_rule_id($id);
			
			$this->assignRef('rule_settings',$get_rule_settings);

			$membertickets = $this->model->getUrlGroupList($this->package_id);			
			$this->membertickets = $membertickets;
			
				
			$model1 = JModelLegacy::getInstance('SearchEngine', 'SearchEngineModel');
			$sedetails = $model1->getSearEnginePackage($this->package_id);
					
			$categories = $this->model->get_categories($sedetails->awardpackageid);	
				
			$this->assignRef("categories", $categories);
			$this->active ='edit';

		}else{			
			$this->addToolbarForListPage();
			JToolBarHelper::title('Url reward list');	

			$page = isset($_GET['page']) ? ((int) $_GET['page']) : 1;
			if( $page > 1  ) {				
				$limitstart = ($page-1) * $pageLimit; 
			}else {				
				$limitstart = 0;
			}

			$se_urlrewards =$this->model->getUrlrewardlist($this->user_id,$this->package_id,$limitstart,$pageLimit);
			$this->se_urlrewards = $se_urlrewards;
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
			
			$this->active ='summary';
		}
        parent::display($tpl);
    }


	protected function addToolbarForListPage()
	{
		// assuming you have other toolbar buttons ...
 
		JToolBarHelper::custom('se_urlreward.create', 'new.png', 'new.png', 'New', false,false);
		JToolBarHelper::custom('se_urlreward.delete', 'delete.png', 'delete.png', 'Delete', true);
		JToolbarHelper::back('Back', 'index.php?option=com_searchengine&package_id='.JRequest::getVar('package_id'));
	}
	
	protected function addToolbarForGiftCodePage()
	{
		// assuming you have other toolbar buttons ...
 
		JToolBarHelper::custom('se_urlreward.save', 'save.png', 'save.png', 'Save & Close', false,false);
		JToolBarHelper::custom('se_urlreward.back', 'delete.png', 'delete.png', 'Back', true);
 
	}
	
	protected function addToolbarForCreatePage()
	{		
		//JToolBarHelper::custom('se_urlreward.save', 'new.png', 'new.png', 'Save', false,false);		
		JToolBarHelper::custom('se_urlreward.save', 'save.png', 'save.png', 'Save & Close', false,false);
		JToolBarHelper::custom('se_urlreward.back', 'back.png', 'back.png', 'Back', false); 
	}
	
	protected function addToolbarForUpdatePage()
	{
		// assuming you have other toolbar buttons ...
		JToolBarHelper::custom('se_urlreward.create', 'new.png', 'new.png', 'Add', false,false);
		JToolBarHelper::custom('se_urlreward.delete', 'delete.png', 'delete.png', 'Delete', false,false);
		JToolBarHelper::custom('se_urlreward.save', 'save.png', 'save.png', 'Save & Close', false,false);
		JToolBarHelper::custom('se_urlreward.back', 'delete.png', 'delete.png', 'Back', false);
 
	}

}
