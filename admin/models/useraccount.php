<?php

defined('_JEXEC') or die('Restricted access');
jimport('joomla.application.component.modeladmin');

class AwardpackageModelUseraccount extends JModelAdmin {

    public function __construct($config = array()) {
        parent::__construct($config);
        $this->_id = JRequest::getVar('ap_account_id');
        $this->_package_id = JRequest::getVar('package_id');
        $this->_db = JFactory::getDbo();
    }

    public function getForm($data = array(), $loadData = true) {
        // Initialise variables.
        $app = JFactory::getApplication();
        // Get the form.
        $form = $this->loadForm('com_searchengine.useraccount', 'useraccount', array('control' => 'jform', 'load_data' => $loadData));
        if (empty($form)) {
            return false;
        }
        return $form;
    }

    public function getItemByEmail($email) {
        $query = $this->_db->getQuery(true);
        $query->select('*');
        $query->from($this->_db->quoteName('#__ap_useraccounts'));
        $query->where($this->_db->quoteName('email') . "='" . (string) $email . "'");
        $this->_db->setQuery($query);
        return $this->_db->loadObject();
    }

}