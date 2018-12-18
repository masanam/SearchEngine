<?php
// no direct access
defined('_JEXEC') or die('Restricted access');
jimport('joomla.application.component.controller');
require_once JPATH_COMPONENT_ADMINISTRATOR.'/helpers/communitysurveys.php';
require_once JPATH_COMPONENT . '/helpers/awardpackages.php';


class SearchEngineControllerUserSearch extends JControllerLegacy {
	function __construct(){
		parent::__construct();
	}

	public function searchgorup(){
		$view = $this->getView('usersearch', 'html');				
		$view->assign('criteria', 1);
		$view->display('crietira');	
	}


	function save_usergroup(){
		$criteria_id = 0;
		$post  = JRequest::getVar('jform');
		$model = JModelLegacy::getInstance('usersearch','SearchEngineModel');
		$criteria_id = JRequest::getVar('criteria_id'); 
		$package_id = JRequest::getVar('package_id'); 
		$data = new stdClass;

		foreach ($post as $key=>$value){
			$data->$key=$value;
		}
		
		if( isset($criteria_id) && $criteria_id>0){
			$criteria_id = $model->update_criteria($data,$package_id,$criteria_id);
		}else{
			$criteria_id = $model->insert_criteria($data,$package_id);			
		}

		$this->setRedirect('index.php?option=com_searchengine&view=usersearch&criteria=1&task=usersearch.searchgorup&tmpl=component&package_id='.$package_id.'&field='.$post['field'], 'Saved');


	}


	public function searchgroup(){

		$app = JFactory::getApplication();
		$filter_start  = JRequest::getVar('usergorup_from');
		$filter_end  = JRequest::getVar('usergorup_to');


		$package_id  = JRequest::getVar('package_id');

		if(!$package_id){
				$this->setRedirect('index.php?option=com_searchengine', 'No package is selected.Please select the package.','error');
		}

		$model = JModelLegacy::getInstance('usersearch','SearchEngineModel');
		$model->serach($package_id);	

		$view = $this->getView('searchgroup', 'html');		
		$view->assign('mid_tab', 'search');
		$view->assign('criteria', 1);
		$view->display();			
	}

	public function delete(){
		$criteria_id = JRequest::getVar('criteria_id'); 
		$package_id = JRequest::getVar('package_id'); 
		$model = JModelLegacy::getInstance('usersearch','SearchEngineModel');
		$model->delete($criteria_id,$package_id);
		$this->setRedirect('index.php?option=com_searchengine&view=usersearch&task=usersearch.searchgorup&tmpl=component&package_id='.$package_id, 'Deleted');
	}

	public function searchlist(){	

		$app = JFactory::getApplication();
		
		$filter_start  = JRequest::getVar('usergorup_from');
		$filter_end  = JRequest::getVar('usergorup_to');

		$app->setUserState( "com_searchengine.from", '' );
		$app->setUserState( "com_searchengine.to",'' );
		$app->setUserState( "com_searchengine.filter_by",'' );	


		if($filter_start && $filter_start){
			$app->setUserState( "com_searchengine.filter_start", $filter_start );
			$app->setUserState( "com_searchengine.filter_end", $filter_end );
		}
		

		$view = $this->getView('usersearch', 'html');	
	
		$view->assign('mid_tab', 'search');			
		$view->assign('criteria', 1);
		$view->display('search');	


	}

	public function penaltyform(){
		$view = $this->getView('usersearch', 'html');
		$view->assign('action', 'form');
		$view->display('form');
	}

	public function savepenaltyform(){
		$app = JFactory::getApplication();
		$data = new stdClass();
		$data->title = trim(JRequest::getVar('title'));
		$data->message = JRequest::getVar('message');
		$data->days = trim(JRequest::getVar('days'));
		$data->created_at = date('Y-m-d H:i:s');
		$model = JModelLegacy::getInstance('usersearch','SearchEngineModel');
		$penalty_id = $model->savepenaltyform($data);
		if( $penalty_id  ){
			echo json_encode(array('status' => 1, 'pid' => $penalty_id));
		}else{
			echo json_encode(array('status' => 0));	
		}
		$app->close();

	}

	public function addPenalty(){
		$app = JFactory::getApplication();
		$uid = JRequest::getVar('uid');		
		$penalty_form_id = JRequest::getVar('penalty_form_id');
		$package_id = JRequest::getVar('package_id');
//		$created_at = date('Y-m-d H:i:s');
		$created_at = JDate::getInstance('now')->format('Y-m-d H:i:s');
		$model = JModelLegacy::getInstance('usersearch','SearchEngineModel');
		$model->addPenaltyToUser($uid,$penalty_form_id,$package_id,$created_at );
		$details = $model->getPenaltyDetails($penalty_form_id );
		$details->start_from = str_replace('-', '/', $created_at);
		$details->current_date = JDate::getInstance('now')->format('Y/m/d H:i:s');
		$details->created_at = $created_at;
		echo json_encode($details);

		$app->close();
	}

	public function removePenalty(){
		$app = JFactory::getApplication();
		$uid = JRequest::getVar('uid');				
		$package_id = JRequest::getVar('package_id');		
		$model = JModelLegacy::getInstance('usersearch','SearchEngineModel');
		$model->removePenaltyToUser($uid,$package_id);
		$app->close();
	}

	public function editpenaltyform(){

		$view = $this->getView('usersearch', 'html');
		$view->assign('mid_tab', 'editform');
		$view->display('editform');
	}

	public function updatepenaltyform(){
		$app = JFactory::getApplication();
		$data = new stdClass();
		$data->id = JRequest::getVar('id');
		$data->user_id = JRequest::getVar('user_id');
		$data->title = trim(JRequest::getVar('title'));
		$data->message = JRequest::getVar('message');
		$data->days = trim(JRequest::getVar('days'));
		$data->created_at = date('Y-m-d H:i:s');
		$data->package_id = JRequest::getVar('package_id');
		$model = JModelLegacy::getInstance('usersearch','SearchEngineModel');
		$penalty_id = $model->updatepenaltyform($data);

		$created_at = $model->getCreatedDate($data, $penalty_id);

		if( $penalty_id  ){
			echo json_encode(array('status' => 1, 'pid' => $penalty_id,'created_at'=>$created_at->created_at ) );
		}else{
			echo json_encode(array('status' => 1, 'created_at'=>$created_at->created_at) );	
		}
		$app->close();

	}

	public function penaltyhistory(){		
		$view = $this->getView('usersearch', 'html');
		$view->assign('mid_tab', 'history');
		$view->display('history');

	}

	public function get_presentation(){
		$view = $this->getView('usersearch', 'html');
		$view->assign('mid_tab', 'presentation_of');
		$view->display('presentation_of');
	}

	public function get_symbol_queue_detail(){
		$view = $this->getView('usersearch', 'html');
		$view->assign('mid_tab', 'symbol_queue_detail');
		$view->display('symbol_queue_detail');
	}

	public function get_prize_status(){
		$view = $this->getView('usersearch', 'html');
		$view->assign('mid_tab', 'prize_status');
		$view->display('prize_status');
	}
	

	
	/*From here onward Old Codes*/
	public function user_list(){
		$view = $this->getView('usersearch', 'html');
		$view->assign('action', 'list');
		$view->display();
	}
	
	public function get_profile(){
		$view = $this->getView('usersearch', 'html');
		$view->assign('action', 'get_profile');
		$view->display('profile');
	}
	
	public function save_profile(){	
		$username = trim(JRequest::getVar('username'));
		$password = JRequest::getVar('password1');
		$confirmpasw = trim(JRequest::getVar('confirmpasw'));
		$email = trim(JRequest::getVar('email'));
		$firstname = JRequest::getVar('firstname');
		$lastname = JRequest::getVar('lastname');
		$gender = JRequest::getVar('gender');
		$country = JRequest::getVar('country');
		$paypal_account = JRequest::getVar('paypal_account');
		$package_id = JRequest::getVar('paypal_account');
		$userId = JRequest::getVar('accountId');

 
		$data = array(
			"userId" => $userId,
       		"username" => $username,
        	"pasw" => $confirmpasw,
		 	"email" => $email,			
			"firstname" => $firstname,			
			"lastname" => $lastname,			
			"gender" => $gender,			
			"country" => $country,	
			"paypal_account" => $paypal_account,			
			"activation" => '1'
			);
			$model = & JModelLegacy::getInstance( 'userlist', 'SearchEngineModel' );
			if ($password != $confirmpasw){
				$msg = JText::_('Password is not same');
				if(!isset($_SESSION['useraccount']) || empty($_SESSION['useraccount'])) {
					session_start();
					$_SESSION['useraccount'] = $data;
				}
				$this->setRedirect(JRoute::_('index.php?option=com_searchengine&view=usersearch&task=usersearch.get_profile&accountId='.JRequest::getVar('accountId').'&package_id='.JRequest::getVar('package_id').''),$msg);
			}else{
				$msg = JText::_('Date updated');
				if($model->edit_save($data)){		
		             if(!isset($_SESSION['useraccount']) || empty($_SESSION['useraccount'])) {
						session_start();
						$_SESSION['useraccount'] = $data;
					}
		$view = $this->getView('usersearch', 'html');
		$view->assign('action', 'save_profile');
		$view->display('next_profile');
					//$this->setRedirect('index.php?option=com_searchengine&view=usersearch&task=usersearch.registration_next', $msg);
				}else{
					$msg = JText::_('data already exist');

						if(!isset($_SESSION['useraccount']) || empty($_SESSION['useraccount'])) {
						session_start();
						$_SESSION['useraccount'] = $data;
					}
							$view = $this->getView('usersearch', 'html');
							$view->assign('action', 'get_profile');
							$view->display('profile');
//$this->setRedirect(JRoute::_('index.php?option=com_searchengine&view=usersearch&task=usersearch.get_profile&accountId='.$accountId.'&package_id='.$package_id.''),$msg);				
}
			}

	}
		
	
		function save_next_profile(){
			$model = & JModelLegacy::getInstance( 'userlist', 'SearchEngineModel' );
		$app = JFactory::getApplication();
		/*$userId = trim(JRequest::getVar('userId'));
		$email = trim(JRequest::getVar('email'));
		$firstname = trim(JRequest::getVar('firstname'));
		$lastname = trim(JRequest::getVar('lastname'));*/
		$birthdate = trim(JRequest::getVar('birthdate'));
		/*$gender = JRequest::getVar('gender');*/
		$street = trim(JRequest::getVar('street'));
		$city = trim(JRequest::getVar('city'));
		$state = trim(JRequest::getVar('state'));
		$postCode = trim(JRequest::getVar('postCode'));
		/*$country = JRequest::getVar('country');*/
		$phone = trim(JRequest::getVar('phone'));
		$paypal_account = trim(JRequest::getVar('paypal_account'));
		$userId = JRequest::getVar('accountId');



		$data = array(
			"userId" => $userId,
			"username" => $username,
			"email" => $email,       		
			"firstname" => $firstname,
			"lastname" => $lastname,
			"birthdate" => $birthdate,
		    "gender" => $gender,
			"street" => $street,
		   	"city" => $city,
		    "state" => $state,
		    "postCode" => $postCode,
		    "country" => $country,
		    "phone" => $phone,
		    "paypal_account" => $paypal_account
		);
			$model = & JModelLegacy::getInstance( 'userlist', 'SearchEngineModel' );
		if(!$model->updateInfo($data)) {
			if(!isset($_SESSION['useraccount']) || empty($_SESSION['useraccount'])) {
				session_start();
				$_SESSION['useraccount'] = $data;
			}
$msg = "You account is complete";
							$view = $this->getView('usersearch', 'html');
							$view->assign('action', 'get_profile');
							$view->display('profile');
		} else {
$msg = "You account is complete";
							$view = $this->getView('usersearch', 'html');
							$view->assign('action', 'get_profile');
							$view->display('profile');
					}
	}
	
		public function get_transaction(){
		$view = $this->getView('usersearch', 'html');
		$view->assign('action', 'get_transaction');
		$view->display('transaction');
	}
	
	public function get_all_transactions(){
		$view = $this->getView('usersearch', 'html');
		$view->assign('action', 'all_transactions');
		$view->display('all_transactions');
	}
	
	public function get_all_funds(){
		$view = $this->getView('usersearch', 'html');
		$view->assign('action', 'all_funds');
		$view->display('all_funds');
	}
	
	public function get_all_donation(){
		$view = $this->getView('usersearch', 'html');
		$view->assign('action', 'all_donation');
		$view->display('all_donation');
	}
	
	public function get_symbol_queue(){
		$view = $this->getView('usersearch', 'html');
		$view->assign('action', 'symbol_queue_of');
		$view->display('symbol_queue_of');
	}
	
	
	
	
	
	
	public function get_distribute_prize(){
		$view = $this->getView('usersearch', 'html');
		$view->assign('action', 'distribute_prize');
		$view->display('distribute_prize');
	}
	
	public function get_shopping_credit(){
		$view = $this->getView('usersearch', 'html');
		$view->assign('action', 'shopping_credit');
		$view->display('shopping_credit');
	}
	
	public function get_quizzes(){
		$view = $this->getView('usersearch', 'html');
		$view->assign('action', 'get_quizzes');
		$view->display('get_quizzes');
	}
	
	public function get_surveys(){
		$view = $this->getView('usersearch', 'html');
		$view->assign('action', 'get_surveys');
		$view->display('get_surveys');
	}

	public function get_giftcode(){
		$view = $this->getView('usersearch', 'html');
		$view->assign('action', 'get_giftcode');
		$view->display('get_giftcode');
	}	
	
	public function next_profile(){
			$view = $this->getView('usersearch', 'html');
			$view->assign('action', 'next_profile');
			$view->display('next_profile');
	}

	public function tickethistory(){		
		$view = $this->getView('usersearch', 'html');
		$view->assign('mid_tab', 'ticket_history');
		$view->display('ticket_history');

	}
	
}