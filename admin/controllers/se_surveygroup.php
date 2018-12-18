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
class SearchEngineControllerse_surveygroup extends SearchEngineController {

    function display() {
  	//tambah
        $this->save();
        parent::display();
    }
    
    function add() {	   
        $app = JFactory::getApplication();
        $link = 'index.php?option=com_searchengine&view=se_surveygroup&layout=create&package_id='.JRequest::getVar('package_id');
        $app->redirect($link);	   
    }
	
	function edit() {
    
    JRequest::setVar('view','se_surveygroup');
    
    JRequest::setVar('layout', 'create');
    
    parent::display();
  }
    
    function save(){ 
	
		$survey_group_name = JRequest::getVar('survey_group_name');
		$model =& JModelLegacy::getInstance('se_surveygroup','SearchEngineModel');
		$edit=false;
		
		$chksurveys = JRequest::getVar('chksurveys');
		
		
		$model->save($survey_group_name,$chksurveys, $edit);
		
        $this->setRedirect('index.php?option=com_searchengine&view=se_surveygroup&package_id='.JRequest::getVar('package_id'), $msg);		
    }
	
	function publish() {
		$cid = JRequest::getVar("cid");
		
		$i = 0;            
		$ccid = count( $cid);
		
		if($ccid > 0)
		{
			 foreach ($cid as $id) {
				 
			  $model =& JModelLegacy::getInstance('se_surveygroup','SearchEngineModel');
				$update = $model->published($id); 
				$i++;     
			}
			
			$publish_counter = JRequest::getVar("checkall-toggle") == "on" ? ($i/7) : $i;
			
			$msg = "$publish_counter survey group(s) Published ";
		} 
		
		$this->setRedirect("index.php?option=com_searchengine&view=se_surveygroup&package_id=".JRequest::getVar('package_id'), $msg);  
	}
	
	function unpublish() {
		$cid = JRequest::getVar("cid");
		
		$i = 0;            
		$ccid = count( $cid);
		
		if($ccid > 0)
		{
			 foreach ($cid as $id) {
				 
			  $model =& JModelLegacy::getInstance('se_surveygroup','SearchEngineModel');
				$update = $model->unpublished($id); 
				$i++;     
			}
			
			$publish_counter = JRequest::getVar("checkall-toggle") == "on" ? ($i/7) : $i;
			
			$msg = "$publish_counter survey group(s) Unpublished ";
		} 
		
		$this->setRedirect("index.php?option=com_searchengine&view=se_surveygroup&package_id=".JRequest::getVar('package_id'), $msg);  
	}
	
	function remove() { 	 
		$se_surveygroup_id = JRequest::getVar('cid');
		$check_all = JRequest::getVar('checkall-toggle');
		$model =& JModelLegacy::getInstance('se_surveygroup','SearchEngineModel');
		$color = JRequest::getVar('color');
		
		//Remove multiple giftcode
		for ($i = 0; $i <= sizeof($se_surveygroup_id); $i++) {
		  $delete = $model->remove($se_surveygroup_id[$i]);            
		}
	  
		//Deciding notification
		$msg = sizeof($se_surveygroup_id) > 1 ? ($i-1)." survey groups deleted" : "One survey group deleted";           
		
		$this->setRedirect("index.php?option=com_searchengine&view=se_surveygroup&package_id=".JRequest::getVar('package_id'), $msg); 
	}
}
?>
