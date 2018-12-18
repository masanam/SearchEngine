<?php
/**
 * @version		$Id: dashboard.php 01 2011-01-11 11:37:09Z maverick $
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
class SearchEngineControllerDashboard extends SearchEngineController {

	function __construct(){
		parent::__construct();
		
		require_once JPATH_COMPONENT . '/helpers/searchenginepackage.php';
		
		
		$user = JFactory::getUser();
		
		
		
		if($user->get('guest')){
			
			/*JError::raiseNotice( 100, 'Please login or <a href="index.php?option=com_awardpackage&
						view=user&task=user.updateDetailInfo">Register</a>' );*/
			//$this->setRedirect('index.php?option=com_users&view=login&return='.$redirectUrl);
		    $redirectUrl = urlencode(base64_encode('index.php?option=com_awardpackage&view=uaccount'));  
		    $errorMsg = 'Please login or <a href="index.php?option=com_awardpackage&view=user&task=user.updateDetailInfo">Register</a>';
			$app = JFactory::getApplication();
			$app->redirect('index.php?option=com_users&view=login&return='.$redirectUrl,$errorMsg); 
		}
	}
	
    function display() {
        $this->save();
        parent::display();
    }
    
    function add() {	   
        $app = JFactory::getApplication();
        $link = 'index.php?option=com_searchengine&view=dashboard&layout=create';
        $app->redirect($link, $msgType='message');	   
    }
  
	function edit(){
		$view = $this->getView('dashboard', 'html');					
		$view->display('edit');		
	}
    
    function save(){ 
	
		$app = JFactory::getApplication();
		
		$data = new stdClass;
		
		$data->id = $app->input->getInt('cid');
		$data->title = $app->input->get('reward_name');
		$data->sedescription = $app->input->get('sedescription');
		
		
		$data->package_id = $app->input->getInt('package_id');
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

		$model = JModelLegacy::getInstance('Dashboard', 'SearchEngineModel');
       	if( $model->save($data) ){
       		
       		$msg = ' Search engine reward list saved successfully';
			$this->setRedirect('index.php?option=com_searchengine&views=dashboard', $msg);

		}else{

			$msg = 'Error in inserting search engine reward list. Please try agina ';
			$this->setRedirect('index.php?option=com_searchengine&view=dashboard&task=dashboard.create&id='.$data->schedule_id,$msg,'notice');
		}
		
        $this->setRedirect('index.php?option=com_searchengine&view=dashboard', $msg);		
    }
	
	function remove() { 	 
		$se_keywordgroup_id = JRequest::getVar('cid');
		$check_all = JRequest::getVar('checkall-toggle');
		$model =& JModelLegacy::getInstance('dashboard','SearchEngineModel');
		$color = JRequest::getVar('color');
		
		//Remove multiple giftcode
		for ($i = 0; $i <= sizeof($se_keywordgroup_id); $i++) {
		  $delete = $model->remove($se_keywordgroup_id[$i]);            
		}
	  
		//Deciding notification
		$msg = sizeof($se_keywordgroup_id) > 1 ? ($i-1)." search engine reward list groups deleted" : "One search engine reward list group deleted";                                                                       
		
		$this->setRedirect("index.php?option=com_searchengine&view=dashboard", $msg); 		
	}
}
?>
