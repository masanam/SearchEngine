<?php

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// import Joomla modelform library
jimport('joomla.application.component.model');

/**
 * HelloWorld Model
 */
class AwardPackageModelUserGroupGender extends JModelLegacy {

    /**
     * Returns a reference to the a Table object, always creating it.
     *
     * @param       type    The table type to instantiate
     * @param       string  A prefix for the table class name. Optional.
     * @param       array   Configuration array for model. Optional.
     * @return      JTable  A database object
     * @since       2.5
     */
    public function getTable($type = 'UserGroupGender', $prefix = 'AwardpackageTable', $config = array()) {
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
        $query->from($db->quoteName('#__ap_usergroup_gender') . ' AS a');
        $query->where('a.package_id=' . $package_id);
        $db->setQuery($query);
        return $db->loadObjectList();
    }
    
    function checkDataExist($package_id){
        $db = $this->getDbo();
        $query = $db->getQuery(true);
        $query->select('a.*');
        $query->from($db->quoteName('#__ap_usergroup_gender') . ' AS a');
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
        $query->from($db->quoteName('#__ap_usergroup_gender'));
        $query->where('id=' . $id);
        $db->setQuery($query);
        //var_dump($db->replacePrefix((string) $db->getQuery()));

        if (!$db->query()) {
            return false;
        }
        return true;
    }

    private function updateDataPackageAdd($criteria, $package_id, $num) {
        $db = $this->getDbo();
        $query = $db->getQuery(true);
        $query = "UPDATE " . $db->quoteName('#__ap_useraccounts');
        $query .= " SET package_id = " . $package_id;
        $query .= ",criteria = " . $db->quote("gender");
        $query .= " WHERE package_id=0";
        $query .= " AND gender=" . $db->quote($criteria);
        $query .= " LIMIT " . $num;
        $db->setQuery($query);
        //var_dump($db->replacePrefix((string) $db->getQuery()));

        if (!$db->query()) {
            return false;
        }
        return true;
    }

    private function updateDataPackageDelete($criteria, $package_id) {
        $db = $this->getDbo();
        $query = $db->getQuery(true);
        $query = "UPDATE " . $db->quoteName('#__ap_useraccounts');
        $query .= " SET package_id =0";
        $query .= ",criteria = " . $db->quote("nothing");
        $query .= " WHERE package_id=" . $package_id;
        $query .= " AND gender=" . $db->quote($criteria);
        $query .= " AND criteria=" . $db->quote("gender");

        $db->setQuery($query);
        //var_dump($db->replacePrefix((string) $db->getQuery()));

        if (!$db->query()) {
            return false;
        }
        return true;
    }

    function getGender($criteria, $package_id, $num, $condition = "add") {

        if ($condition == "add") {
            if ($this->updateDataPackageAdd($criteria, $package_id, $num)) {
                return true;
            }
        } else {
            if ($this->updateDataPackageDelete($criteria, $package_id)) {
                return true;
            }
        }


        return false;
    }

    function getItem($package_id) {
        $row = $this->getTable();
        $row->load($package_id);
        return $row;
    }

    public function getTotal($criteria) {
        $db = &JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select('*');
        $query->from($db->quoteName('#__ap_useraccounts'));
        $query->where('`gender`=' . $db->quote($criteria));
        $query->where('package_id=0');
        $db->setQuery($query);
        $db->query();
        return $db->getNumRows();
    }

}
