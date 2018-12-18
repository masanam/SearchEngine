<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
// import Joomla controller library
jimport('joomla.application.component.controller');
require_once JPATH_COMPONENT . '/helpers/awardpackages.php';
/**
 * General Controller of Donation component
 */
class SearchEngineControllerse_urlreward extends JControllerLegacy
{
	function __construct(){		
		parent::__construct();
		
		require_once JPATH_COMPONENT . '/helpers/searchenginepackage.php';
	}

	public function getlist() 
	{	

		$view = $this->getView('se_urlreward', 'html');					
		$view->display();	
	}

	public function create() 
	{	

		$view = $this->getView('se_urlreward', 'html');					
		$view->display('create');	
	}

	public function save(){
		

		//echo '<pre>';
		//var_dump($_POST);
		

		$app = JFactory::getApplication();
		$data = new stdClass;
		$data->urlrewardid = $app->input->getInt('urlrewardid');
		$data->title = $app->input->get('title');
		$data->group_id = $app->input->get('group-id');
		$data->weekdays = JRequest::getVar('weekdays');
		$data->package_id = $app->input->getInt('package_id');
		$data->created_at = date('Y-m-d H:i:s');
		$data->id = $app->input->getInt('id',0);
		$data->additionalsettings = $app->input->get('additionalsettings');
		
		//echo '<pre>';
		//var_dump($data);
		//exit;

		$model = JModelLegacy::getInstance('Se_urlreward', 'SearchEngineModel');
       	if( $model->save($data) ){
       		
       		$msg = 'Url reward list saved successfully';
			$this->setRedirect('index.php?option=com_searchengine&view=se_urlreward', $msg);

		}else{

			$msg = 'Error in inserting url reward list. Please try agina ';
			$this->setRedirect('index.php?option=com_searchengine&view=se_urlreward', $msg,'notice');
		}

	}
		
	public function edit(){
		$view = $this->getView('se_urlreward', 'html');					
		$view->display('edit');		
	}
	
	public function delete(){

		$app = JFactory::getApplication();	
		$cid = $app->input->get('cid');		
		$model = JModelLegacy::getInstance('Se_urlreward', 'SearchEngineModel');
		if ( $model->delete($cid) ){

			$this->setRedirect('index.php?option=com_searchengine&view=se_urlreward', 'Url reward list deleted successfully');		
		}else{
			$this->setRedirect('index.php?option=com_searchengine&view=se_urlreward', 'Error on deleting. Please try again');		
		}		
	}

	
	
}
