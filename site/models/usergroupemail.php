<?php

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// import Joomla modelform library
jimport('joomla.application.component.model');

/**
 * HelloWorld Model
 */
class AwardpackageModelUserGroupEmail extends JModelLegacy {

    /**
     * Returns a reference to the a Table object, always creating it.
     *
     * @param       type    The table type to instantiate
     * @param       string  A prefix for the table class name. Optional.
     * @param       array   Configuration array for model. Optional.
     * @return      JTable  A database object
     * @since       2.5
     */
    public function getTable($type = 'UserGroupEmail', $prefix = 'AwardPackageTable', $config = array()) {
        return JTable::getInstance($type, $prefix, $config);
    }

    function addItem($data) {

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
        $query->from($db->quoteName('#__ap_usergroup_email') . ' AS a');
        $query->where('a.package_id=' . $package_id);
        $db->setQuery($query);
        return $db->loadObjectList();
    }

    function checkDataExist($package_id){
        $db = $this->getDbo();
        $query = $db->getQuery(true);
        $query->select('a.*');
        $query->from($db->quoteName('#__ap_usergroup_email') . ' AS a');
        $query->where('a.package_id =' . $package_id);
        $db->setQuery($query);
        $db->execute();
        $result = $db->getNumRows();
        return $result;
    }

    function checkUserIsRegistered($criteria){
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select('a.*,u.`name`,u.`email`');
        $query->from($db->quoteName('#__ap_useraccounts') . ' AS a');
        $query->innerJoin($db->quoteName('#__users') . ' AS u ON u.id=a.id');
        $query->where('u.email like ' . $db->quote("%$criteria%"));
        $query->where('a.package_id>0');
        $db->setQuery($query);
        $db->execute();
        $result = $db->getNumRows();
        return $result;
    }

    function checkUserIsExist($criteria){
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select('a.*,u.`name`,u.`email`');
        $query->from($db->quoteName('#__ap_useraccounts') . ' AS a');
        $query->innerJoin($db->quoteName('#__users') . ' AS u ON u.id=a.id');
        $query->where('u.email like ' . $db->quote("%$criteria%"));
        $query->where('a.package_id>0');
        $db->setQuery($query);
        $db->execute();
        $result = $db->getNumRows();
        return $result;
    }

    function getItem($package_id) {
        $row = $this->getTable();
        $row->load($package_id);
        return $row;
    }

    function delete($id) {
        $db = $this->getDbo();
        $query = $db->getQuery(true);
        $query->delete();
        $query->from($db->quoteName('#__ap_usergroup_email'));
        $query->where('id=' . $id);
        $db->setQuery($query);
        //var_dump($db->replacePrefix((string) $db->getQuery()));

        if (!$db->query()) {
            return false;
        }
        return true;
    }

    private function updateDataPackageAdd($user_id, $package_id) {
        $db = $this->getDbo();
        $query = $db->getQuery(true);
        $query->update('#__ap_useraccounts');
        $query->set('package_id=' . $package_id);
        $query->set('criteria=' . $db->quote("email"));
        $query->where('package_id=0');
        $query->where('id=' . $user_id);
        $db->setQuery($query);
        if (!$db->query()) {
            return false;
        }
        return true;
    }

    private function updateDataPackageDelete($user_id, $package_id) {
        $db = $this->getDbo();
        $query = $db->getQuery(true);
        $query->update('#__ap_useraccounts');
        $query->set('package_id=0');
        $query->set('criteria=' . $db->quote("nothing"));
        $query->where('package_id=' . $package_id);
        $query->where('id=' . $user_id);
        $query->where('criteria=' . $db->quote("email"));
        $db->setQuery($query);
        if (!$db->query()) {
            return false;
        }
        return true;
    }

    function getEmail($criteria, $package_id, $condition = "add") {
        $db = $this->getDbo();
        $query = $db->getQuery(TRUE);
        $query->select('a.*');
        $query->from($db->quoteName('#__users') . ' AS a');
        $query->where('a.email=' . $db->quote($criteria));
        $db->setQuery($query);
        $row = $db->loadObject();

        if (isset($row)) {
            if ($condition == "add") {
                if ($this->updateDataPackageAdd($row->id, $package_id)) {
                    return true;
                }
            } else {
                if ($this->updateDataPackageDelete($row->id, $package_id)) {
                    return true;
                }
            }
        }

        return false;
    }

}
