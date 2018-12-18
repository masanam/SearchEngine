<?php

defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.view');

class SearchEngineViewArchive extends JViewLegacy {

    function display($tpl = null) {
        
       // AwardpackagesHelper::addSubmenuAward(JRequest::getCmd('view', 'referral'));

        $items = $this->get('Items');

        $pagination = $this->get('Pagination');

        // Check for errors.
        if (count($errors = $this->get('Errors'))) {
            JError::raiseError(500, implode('<br />', $errors));

            return false;
        }
        // Assign data to the view
        $this->items = $items;

        $this->pagination = $pagination;

        $this->state = $this->get('State');

        //add toolbars
        $this->addToolbars();

        $this->model  = $this->getModel('archive');
        
        $this->details  = $this->model->getDetails(JRequest::getVar('id'));

        // Display the template
        parent::display($tpl);
    }

    public function addToolbars() {
        $layout = JRequest::getVar('layout');
		
        if ($layout == "details") {
            JRequest::setVar('hidemainmenu',1);
            JToolBarHelper::title('Archive Details');
            JToolBarHelper::cancel('archive.cancel');
        } else {
            JToolBarHelper::title('Award Package Archive List');
            JToolBarHelper::deleteList('Are you sure ?', 'archive.delete');
            JToolBarHelper::unarchiveList('archive.retrive', 'Retrive Archive', 'Are you sure');
        }
    }

}