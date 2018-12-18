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
		$package_id =JRequest::getVar('package_id'); 
		$group_id = JRequest::getVar('group_id'); 		
		
		$data = new stdClass;

		foreach ($post as $key=>$value){
			$data->$key=$value;
		}

		$model = JModelLegacy::getInstance('Se_usergroup', 'SearchengineModel');
		$group_id = $model->insert_criteria($data,$package_id,$group_id,$title);
		
		$this->setRedirect('index.php?option=com_searchengine&controller=se_usergroup&task=create&package_id='.$package_id.'&field='.$post['field'].'&id='.$group_id, 'Saved');	

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
				$this->setRedirect('index.php?option=com_searchengine&controller=se_usergroup&task=create&package_id='.$data->package_id.'&id='.$data->id, $msg);
       		}else if($task =='saveclose'){
       			$this->setRedirect('index.php?option=com_searchengine&view=se_usergroup&task=se_usergroup&package_id='.$data->package_id, $msg);
       		}       		

		}else{
			$msg = 'Error in inserting user group. Please try agina ';
			if($data->id ==0){
       			$this->setRedirect('index.php?option=com_searchengine&controller=se_usergroup&task=create&package_id='.$package_id, $msg,'error');	
       		}else{
				$this->setRedirect('index.php?option=com_searchengine&controller=se_usergroup&task=create&package_id='.$package_id.'&id='.$data->id, $msg,'error');	
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

			//$this->setRedirect('index.php?option=com_searchengine&views=se_usergroup&task=se_usergroup.getlist&package_id='.JRequest::getVar('package_id'), 'User group deleted successfully');		
			
			
			$this->setRedirect("index.php?option=com_searchengine&view=se_usergroup&package_id=".JRequest::getVar('package_id'), 'User group deleted successfully');
		
		}else{
			//$this->setRedirect('index.php?option=com_searchengine&views=se_usergroup&task=se_usergroup.getlist&package_id='.JRequest::getVar('package_id'), 'Error on deleting. Please try again');		
			
			$this->setRedirect("index.php?option=com_searchengine&view=se_usergroup&package_id=".JRequest::getVar('package_id'), 'Error on deleting. Please try again');
			
		}	
	}

	/**
	 * Redirect to homepage.
	 *
	 * @return  void
	 *
	 * @since   12.2
	 */
	public function home()
	{
		$this->setRedirect('index.php?option=com_searchengine&&package_id='.JRequest::getVar('package_id'));	

	}
	
	public function deletecriteria(){

		$app = JFactory::getApplication();	
		$cid = $app->input->get('criteria_id');	
		$field = $app->input->get('field');
		$usergroupid = $app->input->get('usergroupid');		
		$model = JModelLegacy::getInstance('Se_usergroup', 'SearchengineModel');
		if ( $model->deletecriteria($cid) ){
			$this->setRedirect("index.php?option=com_searchengine&controller=se_usergroup&task=create&id=$usergroupid&package_id=".JRequest::getVar('package_id'), 'User group ('.$field.') deleted successfully');		
		}else{			
			$this->setRedirect("index.php?option=com_searchengine&controller=se_usergroup&task=create&id=$usergroupid&package_id=".JRequest::getVar('package_id'), 'Error on deleting. Please try again');			
		}		
	}
	
}
