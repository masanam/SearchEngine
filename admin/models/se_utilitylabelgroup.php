<?php
// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.modellist');

class SearchEngineModelse_utilitylabelgroup extends JModelLegacy
{
    var $_getListQuery;
    
    function getListQuery()
    {
        /*
            $user = JFactory::getUser();
            $db = JFactory::getDBO();
            $query = $db->getQuery(true);
            $query->select('*');
            $query->from('#__se_rewardlist')->order('created DESC');
            return $query;
        */    
            if (empty($this->_data)) {
            $this->_data = $this->_getList("SELECT * FROM #__se_utilitylabelgrouplist ORDER BY id");
            }
            return $this->_data;
    }
}

?>
