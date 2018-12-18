<?php
defined('_JEXEC') or die('Restricted access');
jimport('joomla.application.component.controller');
require_once JPATH_COMPONENT . '/helpers/awardpackages.php';

class SearchEngineControllerUsergroup extends SearchEngineController
{
	function __construct(){
		parent::__construct();
	}
	function display($cachable = false, $urlparams = array()) {
		JRequest::setVar('view', 'usergroup');
		parent::display();
	}

	function test(){
		$model = JModelLegacy::getInstance('usergroup','AwardpackageModel');
		$query = "t.id = u.id AND email LIKE '%gmail%' ";
		echo $model->get_total($query);
	}

	function categories(){
		$app = JFactory::getApplication();
		$app->redirect('?option=com_searchengine&controller=donation&task=categories&package_id='.JRequest::getVar('package_id').'&var_id='.JRequest::getVar('var_id'));
	}

	function donation(){
		$this->setRedirect('index.php?option=com_searchengine&controller=donation&task=transaction&package_id='.JRequest::getVar('package_id').'&var_id='.JRequest::getVar('var_id'), $msg);
	}

	function delete(){
		$model = JModelLegacy::getInstance('usergroup','SearchEngineModel');
		$model->delete(JRequest::getVar('criteria_id'),JRequest::getVar('package_id'));
		$app = JFactory::getApplication();
		$app->redirect('index.php?option=com_searchengine&view=usergroup&package_id='.JRequest::getVar('package_id').'&field='.JRequest::getVar('field') . '&command=' . JRequest::getVar('command').'&var_id='.JRequest::getVar('var_id'),'Deleted...');
	}
//&view=usergroup&package_id=5&criteria_id=5&command=1&processPresentation=0&usergroup=6&var_id=5
  function get_usergroup(){
		$package_id = JRequest::getVar('package_id');
		$criteria_id = JRequest::getVar('criteria_id');
		$title = JRequest::getVar('title');
		$this->setRedirect('index.php?option=com_searchengine&view=apresentationlist&task=apresentationlist.initiate&package_id='.JRequest::getVar('package_id').'&idProcess='.JRequest::getVar('sname').'&idProcessValue='.JRequest::getVar('value_from').'&command=1'.'&process_id='.$process_id, JText::_('MSG_SUCCESS'));
		
		//$this->setRedirect('index.php?option=com_searchengine&view=apresentationlist&task=apresentationlist.initiate&package_id='.JRequest::getVar('package_id'),JTEXT::_('Success add user group'));
	}
	
	function save_usergroup(){
		$criteria_id = 0;
		$model = JModelLegacy::getInstance('usergroup','SearchEngineModel');
		$data_save  = JRequest::getVar('jform');

		foreach ($data_save as $i=>$d){
			$data->$i=$d;
		}
		
		
		$get_criteria_id = JRequest::getVar('criteria_id'); /* [[CUSTOM]] [[RI]] added variable to use in if condition  */
		if(!empty($get_criteria_id)) {
			$criteria_id = $model->insert_criteria($data);
			if($criteria_id == -1){
				if(JRequest::getVar('command') == '1') {
					$this->setRedirect('index.php?option=com_searchengine&controller=usergroup&package_id='.$data->package_id.'&field='.$data->field.'&command=1&var_id='.JRequest::getVar('var_id'), 'Your group is already registered');
				} else {
					$this->setRedirect('index.php?option=com_searchengine&controller=usergroup&package_id='.JRequest::getVar('package_id').'&field='.$data->field,'Your group is already registered');
				}
			} else {			
					if($criteria_id == -1) {
						$msg = "Your group is already registered";
					} else
					if($criteria_id == -2){
						$msg = "Saved...";
					} else
					if($criteria_id == -3){
						$msg = "Different package";
					} else {
						$msg = "Saved...";
					}
					
				if(JRequest::getVar('command') == '1') {
					//$this->setRedirect('index.php?option=com_searchengine&view=apresentationlist&task=apresentationlist.initiate&package_id='.JRequest::getVar('package_id').'&idUserGroupsId='.$criteria_id,'Successful update user groups');
					$this->setRedirect('index.php?option=com_searchengine&view=usergroup&task=edit&criteria_id='.JRequest::getVar('criteria_id').'&package_id='.$data->package_id.'&field='.$data->field.'&command=1&var_id='.JRequest::getVar('var_id').'&title='.JRequest::getVar('title'),  $msg);
				} else {
					$this->setRedirect('index.php?option=com_searchengine&view=usergroup&task=edit&criteria_id='.JRequest::getVar('criteria_id').'&package_id='.$data->package_id.'&field='.$data->field, $msg);
				}
			}
		} else {
			if(JRequest::getVar('criteria_id') != 0) {
				$criteria_id = $model->update_criteria_2($data, JRequest::getVar('criteria_id'), $data->package_id);
				if(JRequest::getVar('command') == '1') {
					$this->setRedirect('index.php?option=com_searchengine&controller=usergroup&criteria_id='.JRequest::getVar('criteria_id').'&package_id='.$data->package_id.'&field='.$data->field.'&command=1&var_id='.JRequest::getVar('var_id'), $msg);
				} else {
					$this->setRedirect('index.php?option=com_searchengine&controller=usergroup&criteria_id='.JRequest::getVar('criteria_id').'&package_id='.$data->package_id.'&field='.$data->field, $msg);
				}
			} else {
				if($data->criteria_id){
					$criteria_id = $model->update_criteria($data);
				}else{
					$criteria_id = $model->insert_criteria($data);
					
					if($criteria_id == -1) {
						$msg = "Your group is already registered";
					} else
					if($criteria_id == -2){
						$msg = "Only one group for one package";
					} else
					if($criteria_id == -3){
						$msg = "Data not found";
					} else {
						$msg = "Saved...";
					}
					if(JRequest::getVar('command') == '1') {
						$this->setRedirect('index.php?option=com_searchengine&view=usergroup&task=edit&criteria_id='.JRequest::getVar('criteria_id').'&package_id='.$data->package_id.'&field='.$data->field.'&command=1&var_id='.JRequest::getVar('var_id'), $msg);
					} else {
						$this->setRedirect('index.php?option=com_searchengine&controller=usergroup&criteria_id='.JRequest::getVar('criteria_id').'&package_id='.$data->package_id.'&field='.$data->field, $msg);
					}
				}
			}
		}
	}

	function addNewUserEmail(){
		$package_id = JRequest::getVar('package_id');
		$account_id = JRequest::getVar('user_selected');
		$model = JModelLegacy::getInstance('usergroup','AwardpackageModel');
		$model->updatePackageForUserAccounts($package_id, substr($account_id, 0, strlen($account_id)-1));
		$msg = "Successfull update package account";
		$this->setRedirect('index.php?option=com_searchengine&controller=usergroup&criteria_id='.JRequest::getVar('criteria_id').'&package_id='.JRequest::getVar('package_id').'&field=email&command=1&var_id='.JRequest::getVar('var_id'), $msg);
	}

	function addNewUserName(){
		$package_id = JRequest::getVar('package_id');
		$account_id = JRequest::getVar('user_selected');
		$model = JModelLegacy::getInstance('usergroup','AwardpackageModel');
		$model->updatePackageForUserAccounts($package_id, substr($account_id, 0, strlen($account_id)-1));
		$msg = "Successfull update package account";
		$this->setRedirect('index.php?option=com_searchengine&controller=usergroup&criteria_id='.JRequest::getVar('criteria_id').'&package_id='.JRequest::getVar('package_id').'&field=name&command=1&var_id='.JRequest::getVar('var_id'), $msg);
	}
	
	function save_create(){
		$package_id = JRequest::getVar('package_id');
		$title = JRequest::getVar('title');
		$idUserGroupsId= JRequest::getVar('criteria_id');
		$var_id= JRequest::getVar('var_id');				     
			$this->setRedirect('index.php?option=com_searchengine&view=apresentationlist&task=apresentationlist.initiate&package_id=' .$package_id . '&title=' . $title . '&idUserGroupsId=' . $idUserGroupsId . '&var_id=' . $var_id. '&command=1', JText::_('MSG_SUCCESS'));
	}

}
