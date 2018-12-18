<?php

/*
 * author:kadeyasa@gmail.com
 * and open the template in the editor.
 */

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// import Joomla controller library
jimport('joomla.application.component.controller');

/**
 * General Controller of archive function
 */
class SearchEngineControllerArchive extends JControllerLegacy {

    function __construct() {
        parent::__construct();
		require_once JPATH_COMPONENT . '/helpers/awardpackages.php';
    }

    public function delete() {
        $model = $this->getModel('archive');
        $mainModel = & JModelLegacy::getInstance('main', 'AwardPackageModel');
        $cids = JRequest::getVar('cid');
        foreach ($cids as $cid) {
            $package_id [] = $model->getAwardPackageId($cid)->package_id;
            if ($model->remove($cid)) {
                $mainModel->delete($package_id);
            }
        }
        $link = 'index.php?option=com_searchengine&view=archive';
        $msg = 'Archive has been deleted';
        $this->setRedirect($link, $msg);
    }

    public function retrive() {
        $model = $this->getModel('archive');
        $cids = JRequest::getVar('cid');
        if (count($cids) > 0) {
            foreach ($cids as $cid) {
                $model->retriveArchive($cid);
            }
            $msg = 'Archive has beed retrived';
            $link = 'index.php?option=com_searchengine';
        }
        $this->setRedirect($link, $msg);
    }
    
    public  function cancel(){
        $this->setRedirect('index.php?option=com_searchengine&view=archive');
    }

}

?>
