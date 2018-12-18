<?php
/**
 * @version		$Id: view.html.php 01 2016-05-27
 * @package		Award Package
 * @subpackage	Components
 * @author		Rajesh Tandukar , rtandular@gmail.com
 * @license		License GNU General Public License version 2 or later
 */
defined('_JEXEC') or die();
jimport ( 'joomla.application.component.view' );
jimport('joomla.html.pagination');

require_once JPATH_COMPONENT_ADMINISTRATOR.'/helpers/date.php';		
class SearchEngineViewUsersearch extends JViewLegacy {
	function __construct($config = array()) {

		parent::__construct($config);

		$this->criteria_id = JRequest::getVar('criteria_id');

		$this->processPresentation = JRequest::getVar('processPresentation');

		$this->var_id 		= JRequest::getVar('var_id');

		$this->model       	= JModelLegacy::getInstance('usersearch', 'SearchEngineModel');

		$this->ug          	= JModelLegacy::getInstance('usergroup', 'SearchEngineModel');

		$this->class       	= ' class="ui-state-default ui-corner-top ui-tabs-selected ui-state-active"';

		$this->package_id  	= JRequest::getVar('package_id');

		$this->field       	= JRequest::getVar('field');

		$this->criteria 	= isset($this->criteria)? $this->criteria: JRequest::getVar('criteria');

		$this->type       	= JRequest::getVar('type');

	}

	function display($tpl = null) {
		$app = JFactory::getApplication();
		$package_id = JRequest::getVar('package_id');

		$filter_start = $app->getUserStateFromRequest( 'com_searchengine.filter_start', '' );
		$filter_end = $app->getUserStateFromRequest( 'com_searchengine.filter_end', '' );
		$this->filter_by = $app->getUserStateFromRequest( 'com_searchengine.filter_by', '' );
		
		if( !is_array($this->filter_by) ){
			$this->filter_by= array();
		}


		if(!$package_id){
			$app->redirect('index.php?option=com_searchengine', 'No package is selected.','error');
		}
	
		JToolBarHelper::title(JText::_('User list'), 'logo.png');
		JToolbarHelper::back('Back', 'index.php?option=com_searchengine&package_id=' . $package_id);

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


		


		$this->form = $this->ug->getForm();

		$firstname = '';
		$lastname = '';
		$email = '';
		$from_age = '';
		$to_age = '';
		$gender = '';
		$street = '';
		$city = '';
		$state = '';
		$post_code = '';
		$country = '';
		$id = '';
		$birthday = '';
		
		$this->assignRef('firstname', $firstname);
		$this->assignRef('lastname', $lastname);
		$this->assignRef('email', $email);
		$this->assignRef('from_age', $from_age);
		$this->assignRef('to_age', $to_age);
		$this->assignRef('gender', $gender);
		$this->assignRef('street', $street);
		$this->assignRef('city', $city);
		$this->assignRef('state', $state);
		$this->assignRef('post_code', $post_code);
		$this->assignRef('country', $country);

		if ($this->criteria_id) {
				$rs = $this->model->getCriteriaById($this->criteria_id);				
				if ($rs) {
					foreach ($rs as $k => $v) {
					${$k} = $rs->{$k};
					$this->assignRef($k, ${$k});
					}
				}
			}

		$CountryList = new AwardpackagesHelper;
		$countries = $CountryList->Countries_list();
		$this->assignRef('countries', $countries);
		
		$pLimit = JRequest::getVar('limit')?  JRequest::getVar('limit'): 50; 		
		$pageLimit = $pLimit > 0 ?  $pLimit: 0; 
		
		switch ($this->mid_tab) {

		
			case 'search':

				$this->from = $app->getUserStateFromRequest( 'com_searchengine.from', '' );
				$this->to = $app->getUserStateFromRequest( 'com_searchengine.to', '' );

				if( $this->from && $this->to){
					$filter_start= $this->from;
					$filter_end= $this->to 	;
				}

				$page = isset($_GET['page1']) ? ((int) $_GET['page1']) : 1;
				if( $page > 1  ) {				
					$limitstart = ($page-1) * $pageLimit; 
				}else {				
					$limitstart = 0;
				}
                                
				$criteria = isset($this->criteria)? $this->criteria:''; 
				$records = $this->model->getSeachList($criteria,$package_id,$filter_start,$filter_end,$limitstart,$pageLimit, $this->filter_by);
				
				$total = $this->model->total? $this->model->total: 0;
				if($pageLimit>0){
					$pagination = new SearchenginePaginationHelper;
					$pagination->setCurrent($page);				
					$pagination->setTotal($total);
					$pagination->addClasses('pagination-list');
					$pagination->setKey('page1');
					$pagination->setNext('<span class="icon-next"></span>');
					$pagination->setPrevious('<span class="icon-previous"></span>');
					$pagination->setRPP($pageLimit);
					$this->pagination1 = $pagination->parse();		
				}		

				$this->assignRef('records', $records);


				$penalties = $this->model->getPenalties($package_id);
				$this->assignRef('penalties', $penalties);
				$this->assignRef('pageLimit', $pLimit);
					break;

				case 'form':								
					break;

				case 'editform':
					
					$package_id=  JRequest::getVar('package_id');
					$user_id = JRequest::getVar('user_id');
					$formdata = $this->model->getSPenaltyFormDetails($package_id,$user_id);
					$this->assignRef('formdata', $formdata);
					break;

				case 'history':				
				$user_id=  JRequest::getVar('user_id');
				$package_id = JRequest::getVar('package_id');
				
				
				$records = $this->model->getHistory($user_id,$package_id);				
				$this->assignRef('records', $records);				
				break;

				case 'presentation_of':
				$package_id = JRequest::getVar('package_id');				
				$data['accountId'] = JRequest::getVar('accountId');
				$users = $this->model->getUserNameFromId($data['accountId']);
				foreach ($users as $user){
					$name = $user->firstname.' '.$user->lastname;
					$userid = $user->id;
				}
				$model_presentation = & JModelLegacy::getInstance( 'apresentationlist', 'SearchEngineModel' );

				$symbolprize = $model_presentation->getSymbolSymbolPrize($package_id,$limit, $limitstart);
				$presentations = $model_presentation->getDetailPresentation3(JRequest::getVar('package_id'), '1');

				$total = $model_presentation->_totalCnt;
				$limitstart = $app->getUserStateFromRequest( '', 'limitstart', $limitstart, 'int' );
				$limit = $app->input->getInt('limit', $limit);
				$limit = (!empty($limit) ? $limit : 5);		
				$limitstart = ($limit != 0 ? (floor($limitstart / $limit) * $limit) : 0);				
		    	$this->pager = new JPagination($total, $limitstart, $limit);
		     	$this->pagination = $this->pager;

				$this->assignRef('presentations', $presentations);
				$this->assignRef('countprize', $countprize);
				$this->assignRef('symbolprize', $symbolprize);
								
				$this->assignRef('users', $user);
				$accountId=JRequest::getVar("accountId");
				JToolbarHelper::title('User Presentation ' . (null != $name ? $name: '' ));
				JToolbarHelper::back('Back', 'index.php?option=com_searchengine&view=usersearch&task=usersearch.user_list&package_id='.$package_id);
				break;

				case 'symbol_queue_detail': 
				$model = & JModelLegacy::getInstance( 'userlist', 'SearchEngineModel' );
				$package_id = JRequest::getVar('package_id');
				
				$data['accountId'] = JRequest::getVar('accountId');
				$users = $model->getUserNameFromId($data['accountId']);
				foreach ($users as $user){
					$name = $user->firstname.' '.$user->lastname;
					$userid = $user->id;
				}
				

				$userGroups = $model->getUserGroup($package_id, $user->firstname, $user->lastname, $user->email,
					$user->birthday, $user->gender, $user->street, $user->city, $user->state, $user->post_code, $user->country);
				$userGroup = $userGroups[0];		

				
				$symbolPrizes = $model->getSymbolSymbolQueue($userid ,$limit,$limitstart);
				$total = $model_presentation->_totalCnt;
				$limitstart = $app->getUserStateFromRequest( '', 'limitstart', $limitstart, 'int' );
				$limit = $app->input->getInt('limit', $limit);
				$limit = (!empty($limit) ? $limit : 5);		
				$limitstart = ($limit != 0 ? (floor($limitstart / $limit) * $limit) : 0);				
		    	$this->pager = new JPagination($total, $limitstart, $limit);
		     	$this->pagination = $this->pager;


				$this->assignRef('symbolPrizes', $symbolPrizes);
				$this->assignRef('results', $results);
				
				$this->assignRef('users', $user);
				$total_symbol = $model->getTotalSymbol($userid,1,$limit,$limitstart);
				$this->assignRef('total_symbol', $total_symbol);
				
				$accountId=JRequest::getVar("accountId");
				JToolbarHelper::title('Symbol Queue of ' . (null != $name ? $name: '' ));
				JToolbarHelper::back('Back', 'index.php?option=com_searchengine&view=usersearch&task=usersearch.user_list&accountId='.$accountId.'&package_id='.$package_id);
				break;

				case 'prize_status':
				$model = & JModelLegacy::getInstance( 'userlist', 'SearchEngineModel' );
				$accountId = JRequest::getVar('accountId');
				$package_id = JRequest::getVar('package_id');
				$prize_id = JRequest::getVar('prizeId');

				$limitstart = $app->getUserStateFromRequest( '', 'limitstart', $limitstart, 'int' );
				$limit = $app->input->getInt('limit', $limit);
				$limit = (!empty($limit) ? $limit : 5);		
				$limitstart = ($limit != 0 ? (floor($limitstart / $limit) * $limit) : 0);				
		    	$this->pager = new JPagination($total, $limitstart, $limit);
		     	$this->pagination = $this->pager;
		     	
				
				$Prizes = $model->getSymbolSymbolPrizebyId($prize_id,$limit,$limitstart);
				$this->assignRef('prizes', $Prizes);
				$SymbolDetail = $model->getSymbolQueueView($prize_id,$limit,$limitstart);
				$this->assignRef('symboldetail', $SymbolDetail);

				JToolbarHelper::title('Prize status');
				
				
				JToolbarHelper::back('Back', 'index.php?option=com_searchengine&view=usersearch&task=usersearch.get_symbol_queue_detail&accountId='.$accountId.'&package_id='.$package_id);
				break;

			case 'ticket_history':
				$user_id=  JRequest::getVar('user_id');
				$package_id = JRequest::getVar('package_id');

				$tickets = $this->model->getTicketHistory($user_id,$package_id);				
				$this->assignRef('tickets', $tickets);	
				
				break;

		}

		

		parent::display($tpl);
	}
}
