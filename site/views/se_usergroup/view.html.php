<?php

defined('_JEXEC') or die('Restricted access');
jimport('joomla.application.component.view');

class SearchEngineViewse_usergroup extends JViewLegacy {

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

        $this->model = JModelLegacy::getInstance('Se_usergroup', 'SearchengineModel');
        $this->ug  = JModelLegacy::getInstance('usergroup', 'SearchengineModel');

        $this->field = JRequest::getVar('field');
        $this->class       	= ' class="ui-state-default ui-corner-top ui-tabs-selected ui-state-active"';

    }

    function display($tpl = null) {

		$app = JFactory::getApplication();
		$document= JFactory::getDocument();

		$document->addStyleSheet(JURI::base(true).'/components/com_searchengine/assets/css/bootstrap-datepicker.min.css');
		$document->addStyleSheet(JURI::base(true).'/components/com_searchengine/assets/css/custom.css');
		$document->addScript(JURI::base(true).'/components/com_searchengine/assets/js/bootstrap-datepicker.min.js');


		$document->addStyleSheet(JUri::base() . 'components/com_searchengine/assets/css/jquery.ui.all.css');
		$document->addStyleSheet(JUri::root() .'media/jui/css/bootstrap.min.css');
		$document->addScript(JUri::base() . 'components/com_searchengine/assets/js/jquery.min.js');
		//$document->addScript('//code.jquery.com/ui/1.11.4/jquery-ui.min.js');
		$document->addScript(JUri::base() . 'components/com_searchengine/assets/js/jquery.ui.js');
		$document->addScript(JUri::base() . 'components/com_searchengine/assets/js/jquery.ui.core.js');
		$document->addScript(JUri::base() . 'components/com_searchengine/assets/js/jquery.ui.widget.js');
		$document->addScript(JUri::base() . 'components/com_searchengine/assets/js/jquery.ui.tabs.js');
		$document->addScript(JUri::base() . 'components/com_searchengine/assets/js/jquery.cookie.js');
		$document->addScript(JUri::base() . 'components/com_searchengine/assets/js/tabs.js');

		$document->addScript(JURI::base() . 'components/com_searchengine/assets/js/newcountry.js');


		$task = JRequest::getVar('task');
		$this->group_id = $app->input->getInt('id');
		$pageLimit = 10;


		if ($task =='create') {

			$this->addToolbarForCreatePage();
			$this->form = $this->ug->getForm();
			$group = $this->model->get_group_by_id($this->group_id);
			if($group){
				if(isset($group->title) && $group->title == ''){
				 	$group->title = 'User Group ' .  $group->id;
				}
			}else{
				$group= new stdClass;
				$group->title ='';
				$group->id = 0;
			}

			$this->assignRef('group',$group);

			$groupCreteria = $this->model->get_group_creteria($this->group_id,$this->packageId);
			if(empty($groupCreteria)){
				$registeredUser = array();
			}
			else{
				$registeredUser = $this->model->registered_users($groupCreteria,$this->group_id);
			}
			$this->assignRef('registeredUser', $registeredUser);
			$this->setLayout('create');

			$this->active ='create';

		}else if ($task =='edit') {

			$id = $app->input->getInt('id');

			$this->assignRef('id',$id);

			JToolBarHelper::title('Search engine user group list');

			$this->addToolbarForCreatePage();

			$get_rule_details = $this->model->get_rule_details_by_id($id);

			$this->assignRef('rule_details',$get_rule_details);

			$get_rule_settings = $this->model->get_rule_settings_by_rule_id($id);

			$this->assignRef('rule_settings',$get_rule_settings);

			$membertickets = $this->model->getMemberTicketList($this->packageId);
			$this->membertickets = $membertickets;

			$this->setLayout('create');

			$this->active ='edit';
		}else{
			$this->addToolbarForListPage();

			$page = isset($_GET['page']) ? ((int) $_GET['page']) : 1;
			if( $page > 1  ) {
				$limitstart = ($page-1) * $pageLimit;
			}else {
				$limitstart = 0;
			}
			$ticketgroups =$this->model->getUserGroupList($this->userId,$this->packageId,$limitstart,$pageLimit);
			$this->ticketgroups = $ticketgroups;
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

	protected function addToolbarForCreatePage()
	{
	}

}
