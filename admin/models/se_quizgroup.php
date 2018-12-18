<?php
// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.modellist');

class SearchEngineModelse_quizgroup extends JModelLegacy
{
    var $_getListQuery;
	var $_quizgroup_title;
    
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
            $this->_data = $this->_getList("SELECT * FROM #__se_quizgrouplist ORDER BY id");
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
        $query->from($db->QuoteName('#__se_quizgrouplist'));
        $query->where("package_id='".$package_id."'"); 
        $query->where("created_by='".$user_id."'");           
        $db->setQuery($query,$limitstart,$limit);

        $rows = $db->loadObjectList();
		
        $subQuery = $db->getQuery(true);
        $subQuery->select('COUNT(*)');      
        $subQuery->from($db->QuoteName('#__se_quizgrouplist'));
        $subQuery->where("package_id='".$package_id."'");    
        $subQuery->where("created_by='".$user_id."'");    
        $db->setQuery($subQuery);
        $this->total = (int) $db->loadResult();
        
        return $rows;
    }
	
	function get_quizgroup_title($quizgroup_id) {
		if (empty($this->_quizgroup_title)) {
				$this->_quizgroup_title = $this->_getList("SELECT title
                FROM #__se_quizgrouplist 
                WHERE id = '".$quizgroup_id."'
                ORDER BY id 
                DESC LIMIT 1"); 
		}
		return $this->_quizgroup_title;
	}
	
	
	
	function get_quizgroup_quizs($quizgroup_id) {
		$query = 'SELECT * FROM #__se_quizgrouplist_quizs where quizgroup_id="'.$quizgroup_id.'"';
		$this->_db->setQuery($query);
		$result = $this->_db->loadObjectList();
		return $result;
	}
	
	function save($quiz_group_name, $chkquizs, $edit) {
		//echo $setid;
		//print_r($quiztitles);
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
            $values = array($db->quote($quiz_group_name), $db->quote($package_id), $db->quote($created), $db->quote($created_by));
			
			
            $query
                    ->insert($db->quoteName('#__se_quizgrouplist'))
                    ->columns($db->quoteName($columns))
                    ->values(implode(',', $values));
            $db->setQuery($query);
            $db->execute();
            $ret = $db->insertid();
        }	
	else{
		$db = JFactory::getDBO();
		
		
		$fields = array(
                    $db->quoteName('title') . ' = ' . $db->quote($quiz_group_name),
                    $db->quoteName('modified') . ' = ' . $db->quote(date("Y-m-d G:i:s"))
                );
                
            $conditions = array(
                $db->quoteName('id') . ' = ' . $db->quote($id)
            );
            $query = $db->getQuery(true);
            $query->update($db->quoteName('#__se_quizgrouplist'))->set($fields)->where($conditions);
            $db->setQuery($query);
			$db->execute();
	}
		
        
		if (!empty($ret)) {			
			$this->_getList( "DELETE FROM #__se_quizgrouplist_quizs WHERE quizgroup_id = '".$ret."'" );

		
			foreach($chkquizs as $chkquiz){


				$db = JFactory::getDbo();
				$query = $db->getQuery(true);
				$columns = array('quiz_id', 'quizgroup_id', 'package_id', 'created', 'modified', 'created_by');
				$values = array($db->quote($chkquiz), $db->quote($ret), $db->quote($package_id), $db->quote($created), $db->quote($modified), $db->quote($created_by));
				$query
						->insert($db->quoteName('#__se_quizgrouplist_quizs'))
						->columns($db->quoteName($columns))
						->values(implode(',', $values));
				$db->setQuery($query);
				$db->execute();	
			
			}
		}





	}
	
	
	function published($id) {
		$db = JFactory::getDBO();
		$query = "UPDATE #__se_quizgrouplist SET published='1' WHERE id=" . $id;
	
		$db->setQuery($query);
		
		if (!$db->query()) {
			echo "<script>alert('" . $db->getErrorMsg() . "'); window.history.go(-1);</script>\n";
			exit();
		}
	}
	
	function unpublished($id) {
		$db = JFactory::getDBO();
		$query = "UPDATE #__se_quizgrouplist SET published='0' WHERE id=" . $id;
	
		$db->setQuery($query);
		
		if (!$db->query()) {
			echo "<script>alert('" . $db->getErrorMsg() . "'); window.history.go(-1);</script>\n";
			exit();
		}
	}
	
	function remove($se_quizgroup_id) {
		$this->_getList( "DELETE FROM #__se_quizgrouplist WHERE id = '".$se_quizgroup_id."'" );
	}
}

?>
