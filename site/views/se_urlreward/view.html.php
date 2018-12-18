<?php

defined('_JEXEC') or die('Restricted access');
jimport('joomla.application.component.view');

class SearchEngineViewse_urlreward extends JViewLegacy {
    
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
        
        $this->model = JModelLegacy::getInstance('Se_urlreward', 'SearchEngineModel');
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
			$this->addToolbarForCreatePage();

			$membertickets = $this->model->getUrlGroupList($this->packageId,$this->userId);			
			$this->membertickets = $membertickets;
				
			$model1 = JModelLegacy::getInstance('SearchEngine', 'SearchEngineModel');
			$sedetails = $model1->getSearEnginePackage($this->packageId);
					
			$categories = $this->model->get_categories($sedetails->awardpackageid);	
			
			$this->assignRef("categories", $categories);

			$this->setLayout('create');
			
			$this->active ='create';

		}else if ($task =='edit') {
			
			$id = $app->input->getInt('id');	
			
			$this->assignRef('id',$id);		

			
			$this->addToolbarForCreatePage();
						
			$get_rule_details = $this->model->get_rule_details_by_id($id);

			$this->assignRef('rule_details',$get_rule_details);
			
			$get_rule_settings = $this->model->get_rule_settings_by_rule_id($id);
			
			$this->assignRef('rule_settings',$get_rule_settings);

			$membertickets = $this->model->getUrlGroupList($this->packageId,$this->userId);			
			$this->membertickets = $membertickets;
			
			$model1 = JModelLegacy::getInstance('SearchEngine', 'SearchEngineModel');
			$sedetails = $model1->getSearEnginePackage($this->packageId);
					
			$categories = $this->model->get_categories($sedetails->awardpackageid);	
				
			$this->assignRef("categories", $categories);
			$this->active ='edit';

		}else{			
			$this->addToolbarForListPage();

			$page = isset($_GET['page']) ? ((int) $_GET['page']) : 1;
			if( $page > 1  ) {				
				$limitstart = ($page-1) * $pageLimit; 
			}else {				
				$limitstart = 0;
			}

			$se_urlrewards =$this->model->getUrlrewardlist($this->userId,$this->packageId,$limitstart,$pageLimit);
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
	}
	
	protected function addToolbarForGiftCodePage()
	{ 
	}
	
	protected function addToolbarForCreatePage()
	{		
	}
	
	protected function addToolbarForUpdatePage()
	{ 
	}

}
