<?php
// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.modellist');

class SearchEngineModelSe_awardpackage extends JModelLegacy
{
	
	public function getawardpackages()
    {
       
		if (empty($this->_data)) {
            $this->_data = $this->_getList("SELECT * FROM #__ap_awardpackages");
            }
            return $this->_data;
    }

	
}

?>
