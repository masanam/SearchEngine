<?php
/**
 * @version		$Id: view.html.php 01 2013-01-11 11:37:09Z maverick $
 * @package		CoreJoomla.surveys
 * @subpackage	Components.views
 * @copyright	Copyright (C) 2009 - 2013 corejoomla.com, Inc. All rights reserved.
 * @author		Maverick
 * @link		http://www.corejoomla.com/
 * @license		License GNU General Public License version 2 or later
 */
defined('_JEXEC') or die();

jimport( 'joomla.application.component.view');
require_once JPATH_COMPONENT_ADMINISTRATOR.'/helpers/communitysurveys.php';
class SearchEngineViewSearch extends JViewLegacy {
	
	protected $items;
	protected $pagination;
	protected $canDo;
	protected $lists;
	
    function display($tpl = null) {
		JRequest::setVar('tmpl','component');
		$this->tpl = JRequest::getVar('tpl');
		if(!$this->tpl){
			$this->tpl = 'ip';
		}
                
                $this->searchview = JRequest::getVar('searchview');
		if(!$this->searchview){
			$this->searchview = 'ip';
		}

		$model = JModelLegacy::getInstance('searchengine','SearchEngineModel');
		$this->package_id = JRequest::getVar('package_id');  
		$this->ips = $model->getIpList($this->package_id);
		$this->kewyords = $model->getKeywordsList($this->package_id);
		$this->urls = $model->getUrlList($this->package_id);
    	//add toolbar
		$this->addToolBar(); 		
        parent::display($tpl);
    }
	
	protected function addToolBar()
	{
		$session =& JFactory::getSession();
		$this->loggedin=$session->get('loggedin');
		
		JToolBarHelper::title('Search');
		JToolBarHelper::custom('searchengine.addNew', 'search.png', 'search.png', 'Search', false,false);		
		
	}
	
}
