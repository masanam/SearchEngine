<?php
/**
 * @version		$Id: se_dashboard.php 01 2011-01-11 11:37:09Z maverick $
 * @package		CoreJoomla.surveys
 * @subpackage	Components
 * @copyright	Copyright (C) 2009 - 2010 corejoomla.com, Inc. All rights reserved.
 * @author		Maverick
 * @link		http://www.corejoomla.com/
 * @license		License GNU General Public License version 2 or later
 */

defined('_JEXEC') or die();
jimport('joomla.application.component.controlleradmin');
ini_set('memory_limit','256M');
require_once JPATH_COMPONENT . '/helpers/awardpackages.php';
class SearchEngineControllerse_dashboard extends SearchEngineController {

    function display() {
  	//tambah
        $this->save();
        parent::display();
    }
    
    function add() {	   
        $app = JFactory::getApplication();
        $link = 'index.php?option=com_searchengine&view=se_dashboard&layout=create&package_id='.JRequest::getVar('package_id');
        $app->redirect($link);	   
    }
  
	function edit(){
		$view = $this->getView('se_dashboard', 'html');					
		$view->display('edit');		
	}
    
    function save(){ 
	
		$app = JFactory::getApplication();
		
		$data = new stdClass;
		
		$data->id = $app->input->getInt('cid');
		$data->title = $app->input->get('reward_name');
		$data->sedescription = $app->input->get('sedescription');
		
		
		$data->package_id = JRequest::getVar('package_id');
		$data->user_id = $app->input->getInt('user_id');
		
		$data->startpublishdate = "";
		if($app->input->get('publish_start_date') != "")
		{
			$data->startpublishdate = date ("Y-m-d", strtotime($app->input->get('publish_start_date')));
		}
		$data->endpublishdate = "";
		if($app->input->get('publish_start_date') != "")
		{
			$data->endpublishdate = date ("Y-m-d", strtotime($app->input->get('publish_end_date')));
		}
		
		
		
		$data->usergroup = $app->input->getInt('hdnseugid');
		$data->keywordgroup = $app->input->getInt('hdnkgid');
		$data->urlgroup = $app->input->getInt('hdnugid');
		$data->surveygroup = $app->input->getInt('hdnsgid');
		$data->quizgroup = $app->input->getInt('hdnqgid');
		$data->usergroupfull = json_encode(JRequest::getVar('hdnseugfull'));
		$data->keywordgroupfull = json_encode(JRequest::getVar('hdnkgfull'));
		$data->urlgroupfull = json_encode(JRequest::getVar('hdnugfull'));
		$data->surveygroupfull = json_encode(JRequest::getVar('hdnsgfull'));
		$data->quizgroupfull = json_encode(JRequest::getVar('hdnqgfull'));				
		$data->usergroupdesc = $app->input->get('seugdesc');
		$data->keywordgroupdesc = $app->input->get('kgdesc');
		$data->urlgroupdesc = $app->input->get('ugdesc');
		$data->surveygroupdesc = $app->input->get('sgdesc');
		$data->quizgroupdesc = $app->input->get('qgdesc');

		$model = JModelLegacy::getInstance('se_dashboard', 'SearchEngineModel');
       	if( $model->save($data) ){
       		
       		$msg = ' Search engine reward list saved successfully';
			$this->setRedirect('index.php?option=com_searchengine&views=se_dashboard&package_id='.$data->package_id, $msg);

		}else{

			$msg = 'Error in inserting search engine reward list. Please try agina ';
			$this->setRedirect('index.php?option=com_searchengine&view=se_dashboard&task=se_dashboard.create&id='.$data->schedule_id.'&package_id'.$data->package_id,$msg,'notice');
		}
		
        $this->setRedirect('index.php?option=com_searchengine&view=se_dashboard&package_id='.JRequest::getVar('package_id'), $msg);		
    }
	
	function remove() { 	 
		$se_reward_id = JRequest::getVar('cid');		
		$model =& JModelLegacy::getInstance('se_dashboard','SearchEngineModel');		
		
		//Remove multiple giftcode
		for ($i = 0; $i <= sizeof($se_reward_id); $i++) {
		  $delete = $model->remove($se_reward_id[$i]);            
		}
	  
		//Deciding notification
		$msg = sizeof($se_reward_id) > 1 ? ($i-1)." search engine reward list groups deleted" : "One search engine reward list group deleted";           
		
		$this->setRedirect("index.php?option=com_searchengine&view=se_dashboard&package_id=".JRequest::getVar('package_id'), $msg); 		
	}
}
?>
