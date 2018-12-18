<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
// import the Joomla modellist library
jimport('joomla.application.component.modellist');
/**
 * UserAccount Model
 */
class AwardpackageModelUserAccounts extends JModelList
{
	public function getListQuery(){
		$db			= &JFactory::getDbo();
		$query		= $db->getQuery(TRUE);
		$query->select("*");
		$query->from("#__ap_useraccounts a");
		$query->order("a.firstname ASC");
		$query->innerJoin("#__users AS b ON a.id=b.id");
		$query->where("package_id='".JRequest::getInt('package_id')."'");
		return $query;
	}
}

?>