<?php

defined('_JEXEC') or die('Restricted access');
jimport('joomla.application.component.view');
 
class searchengineViewMain extends JViewLegacy
{
	function display($tpl = null) 
	{
		$user = JFactory::getUser();
		$task = JRequest::getVar('task');
		switch($task){
			case 'entry':
				if(JRequest::getVar('edit')){
					foreach(JRequest::getVar('edit') as $k => $v){
						${$k} = $v;
						$this->assignRef("$k",${$k});		
					}
				}			
			break;
		}

			parent::display($tpl);
	}
}
