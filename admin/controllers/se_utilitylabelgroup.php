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
class SearchEngineControllerse_utilitylabelgroup extends SearchEngineController {

    function display() {
  	//tambah
        $this->save();
        parent::display();
    }
    
    function add() {	   
        $app = JFactory::getApplication();
        $link = 'index.php?option=com_searchengine&view=se_utilitylabelgroup&layout=create&package_id='.JRequest::getVar('package_id');
        $app->redirect($link, $msgType='message');	   
    }
    
    function save(){  
        $this->setRedirect('index.php?option=com_searchengine&view=se_utilitylabelgroup&package_id='.JRequest::getVar('package_id'), $msg);		
    }
}
?>
