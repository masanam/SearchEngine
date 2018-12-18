<?php
defined('_JEXEC') or die('Restricted access');
jimport('joomla.filter.input');

class TableSe_keywordgrouplist extends JTable
{
	var $id                    = null;
	var $created_date_time     = null;
	var $modified_date_time    = null;	
	var $published    = null;	    

	function __construct(& $db) {
		parent::__construct('#__se_keywordgrouplist', 'id', $db);
	}
}
?>