<?php
// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.modellist');

class SearchEngineModelse_surveygroup extends JModelLegacy
{
    var $_getListQuery;
	var $_surveygroup_title;
    
    function getListQuery()
    {
        /*
            $user = JFactory::getUser();
            $db = JFactory::getDBO();
            $query = $db->getQuery(true);
            $query->select('*');
            $query->from('#__se_rewardlist')->order('created DESC');
            return $query;
        */    
            if (empty($this->_data)) {
            $this->_data = $this->_getList("SELECT * FROM #__se_surveygrouplist ORDER BY id");
            }
            return $this->_data;
    }
	
	function getListQueryPagi($user_id,$package_id,$limitstart,$limit)
    {
        /*
            $user = JFactory::getUser();
            $db = JFactory::getDBO();
            $query = $db->getQuery(true);
            $query->select('*');
            $query->from('#__se_rewardlist')->order('created DESC');
            return $query;
        */    
        $db     = JFactory::getDBO();
        $query  = $db->getQuery(true);
        $query->select('*');
        $query->from($db->QuoteName('#__se_surveygrouplist'));
        $query->where("package_id='".$package_id."'"); 
        $query->where("created_by='".$user_id."'");         
        $db->setQuery($query,$limitstart,$limit);

        $rows = $db->loadObjectList();
		
        $subQuery = $db->getQuery(true);
        $subQuery->select('COUNT(*)');      
        $subQuery->from($db->QuoteName('#__se_surveygrouplist'));
        $subQuery->where("package_id='".$package_id."'");  
        $subQuery->where("created_by='".$user_id."'");  
        $db->setQuery($subQuery);
        $this->total = (int) $db->loadResult();
        
        return $rows;
    }
	
	function get_surveygroup_title($surveygroup_id) {
		if (empty($this->_surveygroup_title)) {
				$this->_surveygroup_title = $this->_getList("SELECT title
                FROM #__se_surveygrouplist 
                WHERE id = '".$surveygroup_id."'
                ORDER BY id 
                DESC LIMIT 1"); 
		}
		return $this->_surveygroup_title;
	}
	
	
	
	function get_surveygroup_surveys($surveygroup_id) {
		$query = 'SELECT * FROM #__se_surveygrouplist_surveys where surveygroup_id="'.$surveygroup_id.'"';
		$this->_db->setQuery($query);
		$result = $this->_db->loadObjectList();
		return $result;
	}
	
	function save($survey_group_name, $chksurveys, $edit) {
		//echo $setid;
		//print_r($surveytitles);
		$package_id = JRequest::getVar('package_id');
		$id = JRequest::getVar('cid');
		$created = !empty($created_date) ? $created_date : date('Y-m-d G:i:s');
		$modified = date('Y-m-d G:i:s');
		
		$ret = $id;
		
		$users	= JFactory::getUser();
			$created_by=$users->id;
		
        if (empty($id)) {
            $db = JFactory::getDbo();
            $query = $db->getQuery(true);
			
			
			
            $columns = array('title', 'package_id', 'created', 'created_by');
            $values = array($db->quote($survey_group_name), $db->quote($package_id), $db->quote($created),  $db->quote($created_by));
            $query
                    ->insert($db->quoteName('#__se_surveygrouplist'))
                    ->columns($db->quoteName($columns))
                    ->values(implode(',', $values));
            $db->setQuery($query);
            $db->execute();
            $ret = $db->insertid();
        }
	
	else{
		
		$db = JFactory::getDBO();
		
		
		$fields = array(
                    $db->quoteName('title') . ' = ' . $db->quote($survey_group_name),
                    $db->quoteName('modified') . ' = ' . $db->quote(date("Y-m-d G:i:s"))
                );
                
            $conditions = array(
                $db->quoteName('id') . ' = ' . $db->quote($id)
            );
            $query = $db->getQuery(true);
            $query->update($db->quoteName('#__se_surveygrouplist'))->set($fields)->where($conditions);
            $db->setQuery($query);
			$db->execute();
			
	}
		
        
		if (!empty($ret)) {			
			$this->_getList( "DELETE FROM #__se_surveygrouplist_surveys WHERE surveygroup_id = '".$ret."'" );

		
			foreach($chksurveys as $chksurvey){


				$db = JFactory::getDbo();
				$query = $db->getQuery(true);
				$columns = array('survey_id', 'surveygroup_id', 'package_id', 'created', 'modified', 'created_by');
				$values = array($db->quote($chksurvey), $db->quote($ret), $db->quote($package_id), $db->quote($created), $db->quote($modified), $db->quote($created_by));
				$query
						->insert($db->quoteName('#__se_surveygrouplist_surveys'))
						->columns($db->quoteName($columns))
						->values(implode(',', $values));
				$db->setQuery($query);
				$db->execute();	
			
			}
		}





	}
	
	
	function published($id) {
		$db = JFactory::getDBO();
		$query = "UPDATE #__se_surveygrouplist SET published='1' WHERE id=" . $id;
	
		$db->setQuery($query);
		
		if (!$db->query()) {
			echo "<script>alert('" . $db->getErrorMsg() . "'); window.history.go(-1);</script>\n";
			exit();
		}
	}
	
	function unpublished($id) {
		$db = JFactory::getDBO();
		$query = "UPDATE #__se_surveygrouplist SET published='0' WHERE id=" . $id;
	
		$db->setQuery($query);
		
		if (!$db->query()) {
			echo "<script>alert('" . $db->getErrorMsg() . "'); window.history.go(-1);</script>\n";
			exit();
		}
	}
	
	function remove($se_surveygroup_id) {
		$this->_getList( "DELETE FROM #__se_surveygrouplist WHERE id = '".$se_surveygroup_id."'" );
	}
}

?>
