<?php
/**
 * @version		$Id: user.php 01 2011-08-13 11:37:09Z maverick $
 * @package		CoreJoomla.Quiz
 * @subpackage	Components
 * @copyright	Copyright (C) 2009 - 2011 corejoomla.com. All rights reserved.
 * @author		Maverick
 * @link		http://www.corejoomla.com/
 * @license		License GNU General Public License version 2 or later
 */
defined('_JEXEC') or die();

jimport('joomla.application.component.controller');
require_once JPATH_COMPONENT_ADMINISTRATOR . '/helpers/awardpackages.php';

class SearchEngineControllerUser extends JControllerLegacy {

	function __construct(){
		parent::__construct();
		$this->registerTask('registration', 'registration_user');

	}

	public function registration_user(){
		$view = $this->getView('user', 'html');
		$view->assign('action', 'registration_user');
		$view->display('registration_info2');
	}

	public function new_user(){
		$view = $this->getView('user', 'html');
		$view->assign('action', 'new_user');
		$view->display('registration_info');
	}
	
	function noPackageRegistered(){
		$view = $this->getView('user', 'html');
		$view->assign('action', 'no_package');
		$view->display('no_package');
	}
	

	public function updateDetailInfo(){
		$userid = JRequest::getVar('userid');
		$emailRegistered = JRequest::getVar('emailRegistered');
		$view = $this->getView('user', 'html');
		$view->assign('action', 'update_info');
		$view->assignRef('userId', $userid);
		$view->assignRef('email', $emailRegistered);
		$view->display('registration_info');		
	}
	
	public function updateUserInfo(){
		
		$checkSesssion = true;
		$view = $this->getView('user', 'html');
		$view->assign('action', 'update_info');			
		$view->assign('checksession', $checkSesssion);	
		$view->display('registration_info');	

	}

	function save(){
		$username = trim(JRequest::getVar('username'));
		$password = JRequest::getVar('password1');
		$confirmpasw = trim(JRequest::getVar('confirmpasw'));
		$email = trim(JRequest::getVar('email'));
		$firstname = JRequest::getVar('firstname');
		$lastname = JRequest::getVar('lastname');
		$gender = JRequest::getVar('gender');
		$country = JRequest::getVar('country');
		$paypal_account = JRequest::getVar('paypal_account');
		$state = JRequest::getVar('state');
		$userId = JRequest::getVar('id');
		$session = JFactory::getSession();

 
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
			"activation" => '1',
			"state" => $state	
			);
			$model =& JModelLegacy::getInstance('useraccount','SearchEngineUsersModel');
			if(!preg_match('/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/',$email)) { 
				//session_start();
				//$_SESSION['useraccount'] = $data;
				$session->set( 'useraccount', $data );
				$msg = JText::_('Invalid Email Address');
				$this->setRedirect(JRoute::_('index.php?option=com_searchengine&view=user&task=user.new_user'),$msg);
			} else if ($password != $confirmpasw){
				$msg = JText::_('Password is not same');
				/*if(!isset($_SESSION['useraccount']) || empty($_SESSION['useraccount'])) {
					session_start();
					$_SESSION['useraccount'] = $data;
				}*/
				$sessionData = $session->get( 'useraccount', null );
				if(!isset($sessionData) || empty($sessionData) ){
					$session->set( 'useraccount', $data );
				}
				$this->setRedirect(JRoute::_('index.php?option=com_searchengine&view=user&task=user.new_user'),$msg);
			}else{
				$msg = JText::_('Success add new user');
				if($data['userId'] >0){
					if($model->updteUsersave($data) ) {
						$data['userId'] = $model->insertedId;
						$session->set( 'useraccount', $data );			  
						$this->setRedirect('index.php?option=com_searchengine&view=user&task=user.registration_next', $msg);		
					}else{
						$msg = JText::_('data already exist');
						$sessionData = $session->get( 'useraccount', null );
						 if(!isset($sessionData) || empty($sessionData) ){
								$session->set( 'useraccount', $data );
						  }
						$this->setRedirect(JRoute::_('index.php?option=com_searchengine&view=user&task=user.new_user'),$msg);
					}
				}else if($model->save($data)){		
		            /*if(!isset($_SESSION['useraccount']) || empty($_SESSION['useraccount'])) {
						session_start();
						$_SESSION['useraccount'] = $data;
					}*/
					$data['userId'] = $model->insertedId;
					$session->set( 'useraccount', $data );	
					$this->setRedirect('index.php?option=com_searchengine&view=user&task=user.registration_next', $msg);
				}else{
					$msg = JText::_('data already exist');

						/*if(!isset($_SESSION['useraccount']) || empty($_SESSION['useraccount'])) {
						session_start();
						$_SESSION['useraccount'] = $data;
						}*/
					$sessionData = $session->get( 'useraccount', null );
					if(!isset($sessionData) || empty($sessionData) ){
						$session->set( 'useraccount', $data );
					}
					$this->setRedirect(JRoute::_('index.php?option=com_searchengine&view=user&task=user.new_user'),$msg);
				}
			}

	}

	function login() {
		$model = JModelLegacy::getInstance('useraccount','SearchEngineUsersModel');
		$app = JFactory::getApplication();
		// Populate the data array:
		$data = array();
		$data['return'] = base64_decode($app->input->post->get('return', '', 'BASE64'));
		$data['username'] = JRequest::getVar('username', '', 'method', 'username');
		$data['password'] = JRequest::getString('password', '', 'post', JREQUEST_ALLOWRAW);
		$data['secretkey'] = JRequest::getString('secretkey', '');

		// Set the return URL if empty.
		if (empty($data['return'])){
			$data['return'] = 'index.php?option=com_searchengine&view=user&task=user.registration_user';
		}

		$app->setUserState('users.login.form.return', $data['return']);

		// Get the log in options.
		$options = array();
		$options['remember'] = $this->input->getBool('remember', false);
		$options['return'] = $data['return'];

		// Get the log in credentials.
		$credentials = array();
		$credentials['username']  = $data['username'];
		$credentials['password']  = $data['password'];
		$credentials['secretkey'] = $data['secretkey'];

		$result = $model->login($credentials);
		if($result->is_active == '1') {
			if(!isset($_SESSION['login__']) || empty($_SESSION['login__'])) {
				session_start();
				$_SESSION['login__'] = $result;
			}
			$this->setRedirect(JRoute::_('index.php?option=com_searchengine'));
		} else {
			if($result != null){
				if($result->activation != '1') {
					if(!isset($_SESSION['login__']) || empty($_SESSION['login__'])) {
						session_start();
						$_SESSION['login__'] = $result;
					}
					$view = $this->getView('user', 'html');
					$view->assign('action', 'update_info');
					$view->assignRef('userId', $result->id);
					$view->assignRef('email', $result->emailRegistered);
					$view->display('registration_info');
				} else {
					$msg = "You account is not active yet";
					$this->setRedirect(JRoute::_('index.php?option=com_searchengine&view=user&task=user.registration_user'),$msg);
				}
			} else {
				$msg = "You are not registered or maybe wrong password";
				$this->setRedirect(JRoute::_('index.php?option=com_searchengine&view=user&task=user.registration_user'),$msg);
			}
		}

	}

function registration_next(){
            $view = $this->getView('user', 'html');
			$view->assign('action', 'update_info');
			$view->display('registration_info2');
		}
		
	function updateInfo(){
		$model = JModelLegacy::getInstance('useraccount','SearchEngineUsersModel');
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



		$data = array(
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
		$model = JModelLegacy::getInstance('useraccount','SearchEngineUsersModel');
		if(!$model->updateInfo($data)) {
			if(!isset($_SESSION['useraccount']) || empty($_SESSION['useraccount'])) {
				session_start();
				$_SESSION['useraccount'] = $data;
			}
$msg = "You account is complete";
					$this->setRedirect(JRoute::_('index.php?option=com_users&view=login'),$msg);
		} else {
$msg = "You account is complete";
					$this->setRedirect(JRoute::_('index.php?option=com_users&view=login'),$msg);		}
	}

	function logout(){
		if(isset($_SESSION['login__'])) {
			unset($_SESSION['login__']);
			$this->setRedirect(JRoute::_('index.php?option=com_searchengine&view=user&task=user.registration_user'),$msg);
		}
	}
	function info(){		
		$view = $this->getView('user', 'html');
		$view->assign('action', 'user_info');
		$view->display('user_info');
	}
}
