<?php

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// import Joomla modelform library
jimport('joomla.application.component.model');

/**
 * HelloWorld Model
 */
class AwardPackageModelUserGroupLocation extends JModelLegacy {

    /**
     * Returns a reference to the a Table object, always creating it.
     *
     * @param       type    The table type to instantiate
     * @param       string  A prefix for the table class name. Optional.
     * @param       array   Configuration array for model. Optional.
     * @return      JTable  A database object
     * @since       2.5
     */
    public function getTable($type = 'UserGroupLocation', $prefix = 'AwardpackageTable', $config = array()) {
        return JTable::getInstance($type, $prefix, $config);
    }

    function addItem($data = array()) {

        $row = $this->getTable();

        if (!$row->bind($data)) {
            return JFactory::getApplication()->enqueueMessage($row->getError(), 'warning');
        } else {
            if (!$row->store()) {
                return JFactory::getApplication()->enqueueMessage($row->getError(), 'error');
            } else {
                $db = $row->getDBO();
                $this->id = $db->insertid();
                return true;
            }
        }
        return false;
    }

    function getList($package_id) {
        $db = $this->getDbo();
        $query = $db->getQuery(true);
        $query->select('a.*');
        $query->from($db->quoteName('#__ap_usergroup_location') . ' AS a');
        $query->where('a.package_id=' . $package_id);
        $db->setQuery($query);
        return $db->loadObjectList();
    }
    
    function checkDataExist($package_id){
        $db = $this->getDbo();
        $query = $db->getQuery(true);
        $query->select('a.*');
        $query->from($db->quoteName('#__ap_usergroup_location') . ' AS a');
        $query->where('a.package_id =' . $package_id);
        $db->setQuery($query);
        $db->execute();
        $result = $db->getNumRows();
        return $result;
    }

    function delete($id) {
        $db = $this->getDbo();
        $query = $db->getQuery(true);
        $query->delete();
        $query->from($db->quoteName('#__ap_usergroup_location'));
        $query->where('id=' . $id);
        $db->setQuery($query);
        //var_dump($db->replacePrefix((string) $db->getQuery()));

        if (!$db->query()) {
            return false;
        }
        return true;
    }

}
