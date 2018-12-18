<?php
/**
 * @version		$Id: surveys.php 01 2012-04-21 11:37:09Z maverick $
 * @package		CoreJoomla.Surveys
 * @subpackage	Components.admin
 * @copyright	Copyright (C) 2009 - 2012 corejoomla.com, Inc. All rights reserved.
 * @author		Maverick
 * @link		http://www.corejoomla.com/
 * @license		License GNU General Public License version 2 or later
 */

// no direct access
defined('_JEXEC') or die();

// Import Joomla! libraries
jimport('joomla.application.component.modellist');
jimport('joomla.application.component.helper');
defined('S_APP_NAME') or define('S_APP_NAME', 'com_awardpackage');
require_once JPATH_COMPONENT_ADMINISTRATOR.'/helpers/constants.php';
require_once JPATH_SITE.'/components/com_cjlib/framework/functions.php';
//require_once JPATH_ROOT.DIRECTORY_SEPARATOR.'components'.DIRECTORY_SEPARATOR.'com_cjlib'.DIRECTORY_SEPARATOR.'framework'.DIRECTORY_SEPARATOR.'functions.php';
class SearchEngineModelSurveys extends JModelLegacy {

	function __construct() {
		parent::__construct();
	}	
	
	
	public function get_surveys($ids = array(), $limit = 20, $limitstart = 0, $published = -1){
		$user = JFactory::getUser();
		$app = JFactory::getApplication();

		$filter_order = $app->getUserStateFromRequest( S_APP_NAME.'.surveys.filter_order', 'filter_order', 'a.created', 'cmd' );
		$filter_order_dir = $app->getUserStateFromRequest( S_APP_NAME.'.surveys.filter_order_dir', 'filter_order_Dir', 'DESC', 'word' );
		
			
		$userid = $app->input->post->getInt('uid', 0);

		$wheres = array();
		$return = array();
		
		if($userid){

			$wheres[] = 'a.created_by = '.$userid;
		}
		
		
		//$wheres[] = 'a.package_id = \''.JRequest::getVar('package_id').'\'';
		$wheres[] = "a.is_active='1'";

		$where = ((count($wheres) > 0) ? ' where ('.implode(' ) and ( ', $wheres).')' : '');		
		$order = ' order by ' . $filter_order . ' ' . $filter_order_dir;

		$query = '
        		select
        			a.id, a.title
        		from
        			#__survey a        		
        		left join
        			#__users u ON a.created_by = u.id
    			'.$where.$order;
				
		$this->_db->setQuery($query);
		$return = $this->_db->loadObjectList();

	


		return $return;
	}
	
}
?>
