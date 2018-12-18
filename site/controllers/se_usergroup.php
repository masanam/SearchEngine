<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
// import Joomla controller library
jimport('joomla.application.component.controller');
require_once JPATH_COMPONENT . '/helpers/awardpackages.php';
/**
 * General Controller of Donation component
 */
class SearchEngineControllerSe_usergroup extends JControllerLegacy
{
	function __construct(){		
		parent::__construct();

		$this->registerTask('save', 'save');
		$this->registerTask('saveclose', 'save');
		
		require_once JPATH_COMPONENT . '/helpers/searchenginepackage.php';
	}

	public function getlist() 
	{
		$view = $this->getView('se_usergroup', 'html');					
		$view->display();	
	}

	public function create() 
	{	
		$view = $this->getView('se_usergroup', 'html');					
		$view->display('create');	
	}

	public function save_usergroup(){		
		$app = JFactory::getApplication();
		$post  = JRequest::getVar('jform');		
		$title = JRequest::getVar('group_title'); 
		$criteria_id = JRequest::getVar('criteria_id'); 
		$package_id =$app->input->getInt('package_id');
		$group_id = JRequest::getVar('group_id'); 		
		
		
		$data = new stdClass;

		foreach ($post as $key=>$value){
			$data->$key=$value;
		}

		$model = JModelLegacy::getInstance('Se_usergroup', 'SearchengineModel');
		$group_id = $model->insert_criteria($data,$package_id,$group_id,$title);
		
		$this->setRedirect('index.php?option=com_searchengine&view=se_usergroup&task=create&field='.$post['field'].'&id='.$group_id, 'Saved');	

	}

	

	public function save(){
			
		$app = JFactory::getApplication();
		$data = new stdClass;	
		$data->id = $app->input->get('id');		
		$data->title = $app->input->getString('title');		
		$data->package_id = $app->input->getInt('package_id');
		$data->created_at = date('Y-m-d H:i:s');

		$task = JRequest::getVar('task'); 
		
		$model = JModelLegacy::getInstance('Se_usergroup', 'SearchengineModel');
		

       	if( $model->save($data) ){
       		if($data->id == 0 ){
       			$data->id = $model->insertid;
       		}
       		
       		$msg = 'User group saved successfully';
       		if($task =='save'){
				//$this->setRedirect('index.php?option=com_searchengine&view=se_usergroup&task=create&package_id='.$data->package_id.'&id='.$data->id, $msg);
				$this->setRedirect('index.php?option=com_searchengine&view=se_usergroup', $msg);
       		}else if($task =='saveclose'){
       			$this->setRedirect('index.php?option=com_searchengine&view=se_usergroup', $msg);
       		}       		

		}else{
			$msg = 'Error in inserting user group. Please try agina ';
			if($data->id ==0){
       			$this->setRedirect('index.php?option=com_searchengine&view=se_usergroup&task=create', $msg,'error');	
       		}else{
				$this->setRedirect('index.php?option=com_searchengine&view=se_usergroup&task=create'.'&id='.$data->id, $msg,'error');	
       		}
			
		}

	}
		
	public function edit(){
		$view = $this->getView('se_usergroup', 'html');					
		$view->display('edit');		
	}
	
	public function delete(){

		$app = JFactory::getApplication();	
		$cid = $app->input->get('cid');		
		$model = JModelLegacy::getInstance('Se_usergroup', 'SearchengineModel');
		if ( $model->delete($cid) ){
			$this->setRedirect("index.php?option=com_searchengine&view=se_usergroup", 'User group deleted successfully');		
		}else{			
			$this->setRedirect("index.php?option=com_searchengine&view=se_usergroup", 'Error on deleting. Please try again');			
		}		
	}
	
	public function deletecriteria(){

		$app = JFactory::getApplication();	
		$cid = $app->input->get('criteria_id');	
		$field = $app->input->get('field');
		$usergroupid = $app->input->get('usergroupid');		
		$model = JModelLegacy::getInstance('Se_usergroup', 'SearchengineModel');
		if ( $model->deletecriteria($cid) ){
			$this->setRedirect("index.php?option=com_searchengine&view=se_usergroup&task=create&id=$usergroupid", 'User group ('.$field.') deleted successfully');		
		}else{			
			$this->setRedirect("index.php?option=com_searchengine&view=se_usergroup&task=create&id=$usergroupid", 'Error on deleting. Please try again');			
		}		
	}



	
}
