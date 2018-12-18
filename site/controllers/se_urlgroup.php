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
class SearchEngineControllerse_urlgroup extends SearchEngineController {

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
        $link = 'index.php?option=com_searchengine&view=se_urlgroup&layout=create';
        $app->redirect($link, $msgType='message');	   
    }
	
	function edit() {
    
    JRequest::setVar('view','se_urlgroup');
    
    JRequest::setVar('layout', 'create');
    
    parent::display();
  }
    
    function save(){ 
	
		$url_group_name = JRequest::getVar('url_group_name');
		$model =& JModelLegacy::getInstance('se_urlgroup','SearchEngineModel');
		$edit=false;
		
		$urltitles = JRequest::getVar('urlstitle');
		
		
		$model->save($url_group_name,$urltitles, $edit);
		
        $this->setRedirect('index.php?option=com_searchengine&view=se_urlgroup', $msg);		
    }
	
	function publish() {
		$cid = JRequest::getVar("cid");
		
		$i = 0;            
		$ccid = count( $cid);
		
		if($ccid > 0)
		{
			 foreach ($cid as $id) {
				 
			  $model =& JModelLegacy::getInstance('se_urlgroup','SearchEngineModel');
				$update = $model->published($id); 
				$i++;     
			}
			
			$publish_counter = JRequest::getVar("checkall-toggle") == "on" ? ($i/7) : $i;
			
			$msg = "$publish_counter url group(s) Published ";
		} 
		
		$this->setRedirect("index.php?option=com_searchengine&view=se_urlgroup", $msg);  
	}
	
	function unpublish() {
		$cid = JRequest::getVar("cid");
		
		$i = 0;            
		$ccid = count( $cid);
		
		if($ccid > 0)
		{
			 foreach ($cid as $id) {
				 
			  $model =& JModelLegacy::getInstance('se_urlgroup','SearchEngineModel');
				$update = $model->unpublished($id); 
				$i++;     
			}
			
			$publish_counter = JRequest::getVar("checkall-toggle") == "on" ? ($i/7) : $i;
			
			$msg = "$publish_counter url group(s) Unpublished ";
		} 
		
		$this->setRedirect("index.php?option=com_searchengine&view=se_urlgroup", $msg);  
	}
	
	function remove() { 	 
		$se_urlgroup_id = JRequest::getVar('cid');
		$check_all = JRequest::getVar('checkall-toggle');
		$model =& JModelLegacy::getInstance('se_urlgroup','SearchEngineModel');
		$color = JRequest::getVar('color');
		
		//Remove multiple giftcode
		for ($i = 0; $i <= sizeof($se_urlgroup_id); $i++) {
		  $delete = $model->remove($se_urlgroup_id[$i]);            
		}
	  
		//Deciding notification
		$msg = sizeof($se_urlgroup_id) > 1 ? ($i-1)." url groups deleted" : "One url group deleted";          
		
		$this->setRedirect("index.php?option=com_searchengine&view=se_urlgroup", $msg); 
	}
}
?>
