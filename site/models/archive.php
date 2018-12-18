<?php

/*
 * Models Create by kadeyasa
 * kadeyasa@gmail.com
 */
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.modellist');

class SearchEngineModelArchive extends JModelList {

    public function __construct($config = array()) {
        if (empty($config['filter_fields'])) {
            $config['filter_fields'] = array('awardpackage', 'date_created', 'date_archived', 'number_of_user', 'number_of_prize');
        }
        parent::__construct($config);
    }

    protected function getListQuery() {
        // Create a new query object.
        $db = JFactory::getDBO();
        $query = $db->getQuery(true);

        // Select some fields
        $query->select('*');

        // From the hello table
        $query->from('#__se_search_archive');
        $query->order($this->getState('list.ordering', 'id') . ' ' . $this->getState('list.direction', 'ASC'));
        return $query;
    }

    public function save_archive($package_id, $number_of_user, $number_of_prize, $date_now) {
        $db = JFactory::getDbo();
        $query = "SELECT * FROM " . $db->quoteName('#__search_engine') . " WHERE " . $db->quoteName('package_id') . "='" . $package_id . "'";
        $db->setQuery($query);
        $rows = $db->loadObjectList();
        foreach ($rows as $row) {
            $query = "INSERT INTO #__se_search_archive (awardpackage,date_created,date_archived,number_of_user,number_of_prize,package_id) VALUES ('" .
                    $row->package_name . "','" . $row->created . "','" . $date_now . "','" . $number_of_user . "','" . $number_of_prize . "','" . $package_id . "')";
            $db->setQuery($query);
            if ($db->query()) {
                return TRUE;
            }
        }
    }

    public function getApUser($package_id) {
        $db = JFactory::getDbo();
        $query = "SELECT * FROM " . $db->quoteName('#__se_useraccounts') . " WHERE " . $db->quoteName('package_id') . "='" . $package_id . "'";
        $db->setQuery($query);
        $rows = $db->loadObjectList();
        return $rows;
    }

    public function getPrize($package_id) {
        $db = JFactory::getDbo();
        $query = "SELECT * FROM " . $db->quoteName('#__symbol_prize') . " WHERE " . $db->quoteName('package_id') . "='" . $package_id . "'";
        $db->setQuery($query);
        $rows = $db->loadObjectList();
        return $rows;
    }

    public function UpdateAwardPackage($package_id) {
        $db = JFactory::getDbo();
        $query = "UPDATE " . $db->quoteName('#__search_engine') . " SET is_archive='1' WHERE " . $db->quoteName('package_id') . "='" . $package_id . "'";
        $db->setQuery($query);
        if ($db->query()) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    public function remove($id) {
        $row = & $this->getTable();
        if (!$row->delete($id)) {
            $this->setError($row->getErrorMsg());
            return false;
        }  else {
            return true;    
        }
    }
    
    public  function getAwardPackageId($archive_id){
        $db     = JFactory::getDbo();
        $query  = "SELECT * FROM #__se_search_archive WHERE id='".$archive_id."'";
        $db->setQuery($query);
        $row    = $db->loadObject();
        return $row;
    }
    
    public function getDetails($archive_id){
        $db     = JFactory::getDbo();
        $query  = "SELECT * FROM #__se_search_archive a INNER JOIN #__search_engine b ON a.package_id=b.package_id WHERE a.id='".$archive_id."'";
        $db->setQuery($query);
        $rows   = $db->loadObjectList();
        return $rows;
    }

        public function retriveArchive($cid){
        $db         = JFactory::getDbo();
        $package_id = $this->getAwardPackageId($cid)->package_id;
        $query      = "DELETE FROM #__se_search_archive WHERE id='".$cid."'";
        $db->setQuery($query);
        if($db->query()){
            $query = "UPDATE #__search_engine SET is_archive='0' WHERE ".$db->quoteName('package_id')."='".$package_id."'";
            $db->setQuery($query);
            if($db->query()){
                return true;
            }
        }
    }
}

?>
