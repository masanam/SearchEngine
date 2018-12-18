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
class SearchEngineControllerse_keywordgroup extends SearchEngineController {

	function __construct(){		
		parent::__construct();
		
		require_once JPATH_COMPONENT . '/helpers/searchenginepackage.php';
	}
	
    function display() {
  	//tambah
        $this->save();
        parent::display();
    }
    
    function add() {	   
        $app = JFactory::getApplication();
        $link = 'index.php?option=com_searchengine&view=se_keywordgroup&layout=create';
        $app->redirect($link, $msgType='message');	   
    }
	
	function edit() {
    
    JRequest::setVar('view','se_keywordgroup');
    
    JRequest::setVar('layout', 'create');
    
    parent::display();
  }
    
    function save(){ 
	
		$keyword_group_name = JRequest::getVar('keyword_group_name');
		$model =& JModelLegacy::getInstance('se_keywordgroup','SearchEngineModel');
		$edit=false;
		
		$keywordtitles = JRequest::getVar('keywordstitle');
		
		
		$model->save($keyword_group_name,$keywordtitles, $edit);
		
        $this->setRedirect('index.php?option=com_searchengine&view=se_keywordgroup', $msg);		
    }
	
	function publish() {
		$cid = JRequest::getVar("cid");
		
		$i = 0;            
		$ccid = count( $cid);
		
		if($ccid > 0)
		{
			 foreach ($cid as $id) {
				 
			  $model =& JModelLegacy::getInstance('se_keywordgroup','SearchEngineModel');
				$update = $model->published($id); 
				$i++;     
			}
			
			$publish_counter = JRequest::getVar("checkall-toggle") == "on" ? ($i/7) : $i;
			
			$msg = "$publish_counter keyword group(s) Published ";
		} 
		
		$this->setRedirect("index.php?option=com_searchengine&view=se_keywordgroup", $msg);  
	}
	
	function unpublish() {
		$cid = JRequest::getVar("cid");
		
		$i = 0;            
		$ccid = count( $cid);
		
		if($ccid > 0)
		{
			 foreach ($cid as $id) {
				 
			  $model =& JModelLegacy::getInstance('se_keywordgroup','SearchEngineModel');
				$update = $model->unpublished($id); 
				$i++;     
			}
			
			$publish_counter = JRequest::getVar("checkall-toggle") == "on" ? ($i/7) : $i;
			
			$msg = "$publish_counter keyword group(s) Unpublished ";
		} 
		
		$this->setRedirect("index.php?option=com_searchengine&view=se_keywordgroup", $msg);  
	}
	
	function remove() { 	 
		$se_keywordgroup_id = JRequest::getVar('cid');
		$check_all = JRequest::getVar('checkall-toggle');
		$model =& JModelLegacy::getInstance('se_keywordgroup','SearchEngineModel');
		$color = JRequest::getVar('color');
		
		//Remove multiple giftcode
		for ($i = 0; $i <= sizeof($se_keywordgroup_id); $i++) {
		  $delete = $model->remove($se_keywordgroup_id[$i]);            
		}
	  
		//Deciding notification
		$msg = sizeof($se_keywordgroup_id) > 1 ? ($i-1)." keyword groups deleted" : "One keyword group deleted"; 
		
		$this->setRedirect("index.php?option=com_searchengine&view=se_keywordgroup", $msg); 
	}
}
?>
