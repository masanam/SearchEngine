<?php
// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.modellist');

class SearchEngineModelse_keywordgroup extends JModelLegacy
{
    var $_getListQuery;
	var $_keywordgroup_title;
    
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
            $this->_data = $this->_getList("SELECT * FROM #__se_keywordgrouplist ORDER BY id");
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
        $query->from($db->QuoteName('#__se_keywordgrouplist'));
        $query->where("package_id='".$package_id."'");     
		$query->where("created_by='".$user_id."'"); 		   
        $db->setQuery($query,$limitstart,$limit);

        $rows = $db->loadObjectList();
		
        $subQuery = $db->getQuery(true);
        $subQuery->select('COUNT(*)');      
        $subQuery->from($db->QuoteName('#__se_keywordgrouplist'));
        $subQuery->where("package_id='".$package_id."'"); 
		$subQuery->where("created_by='".$user_id."'"); 		
        $db->setQuery($subQuery);
        $this->total = (int) $db->loadResult();
        
        return $rows;
    }
	
	function get_keywordgroup_title($keywordgroup_id) {
		if (empty($this->_keywordgroup_title)) {
				$this->_keywordgroup_title = $this->_getList("SELECT title
                FROM #__se_keywordgrouplist 
                WHERE id = '".$keywordgroup_id."'
                ORDER BY id 
                DESC LIMIT 1"); 
		}
		return $this->_keywordgroup_title;
	}
	
	
	
	function get_keywordgroup_keywords($keywordgroup_id) {
		$query = 'SELECT * FROM #__se_keywordgrouplist_keywords where keywordgroup_id="'.$keywordgroup_id.'"';
		$this->_db->setQuery($query);
		$result = $this->_db->loadObjectList();
		return $result;
	}
	
	function save($keyword_group_name, $keywordtitles, $edit) {
		//echo $setid;
		//print_r($keywordtitles);
		$package_id = JRequest::getVar('package_id');
		$id = JRequest::getVar('cid');
		$created = !empty($created_date) ? $created_date : date('Y-m-d G:i:s');
		$modified = date('Y-m-d G:i:s');
		
		/*
		$db = JFactory::getDBO();
		$row =& $this->getTable('Se_keywordgrouplist');
		$row->title = $keyword_group_name;
		$created_date = JRequest::getVar("created_date");
		$row->created = !empty($created_date) ? $created_date : date('Y-m-d G:i:s');
		$row->modified = date('Y-m-d G:i:s');
		$row->published = 0;		
		$row->package_id = $package_id;
		$row->id = $id;

		if (!$row->check()) {
			$this->setError($this->_db->getErrorMsg());
			return false;
		}

		if (!$row->store()) {
			$this->setError( $row->getErrorMsg() );
			return false;
		}
		*/
		
		
		$ret = $id;
		
		$users	= JFactory::getUser();
			$created_by=$users->id;
		
        if (empty($id)) {
			
			

			
            $db = JFactory::getDbo();
            $query = $db->getQuery(true);
            $columns = array('title', 'package_id', 'created',  'created_by');
            $values = array($db->quote($keyword_group_name), $db->quote($package_id), $db->quote($created),  $db->quote($created_by));
            $query
                    ->insert($db->quoteName('#__se_keywordgrouplist'))
                    ->columns($db->quoteName($columns))
                    ->values(implode(',', $values));
            $db->setQuery($query);
            $db->execute();
            $ret = $db->insertid();
        }
	else{
		
		$db = JFactory::getDBO();
		
		
		$fields = array(
                    $db->quoteName('title') . ' = ' . $db->quote($keyword_group_name),
                    $db->quoteName('modified') . ' = ' . $db->quote(date("Y-m-d G:i:s"))
                );
                
            $conditions = array(
                $db->quoteName('id') . ' = ' . $db->quote($id)
            );
            $query = $db->getQuery(true);
            $query->update($db->quoteName('#__se_keywordgrouplist'))->set($fields)->where($conditions);
            $db->setQuery($query);
			$db->execute();	
		
	}
		
        
		if (!empty($ret)) {			
			$this->_getList( "DELETE FROM #__se_keywordgrouplist_keywords WHERE keywordgroup_id = '".$ret."'" );
			
			foreach($keywordtitles as $keywordtitle){
				if(!empty($keywordtitle)){
				$db = JFactory::getDbo();
				$query = $db->getQuery(true);
				$columns = array('title', 'keywordgroup_id', 'package_id', 'created', 'modified', 'created_by');
				$values = array($db->quote($keywordtitle), $db->quote($ret), $db->quote($package_id), $db->quote($created), $db->quote($modified), $db->quote($created_by));
				$query
						->insert($db->quoteName('#__se_keywordgrouplist_keywords'))
						->columns($db->quoteName($columns))
						->values(implode(',', $values));
				$db->setQuery($query);
				$db->execute();	
				}				
			}
		}
	}
	
	
	function published($id) {
		$db = JFactory::getDBO();
		$query = "UPDATE #__se_keywordgrouplist SET published='1' WHERE id=" . $id;
	
		$db->setQuery($query);
		
		if (!$db->query()) {
			echo "<script>alert('" . $db->getErrorMsg() . "'); window.history.go(-1);</script>\n";
			exit();
		}
	}
	
	function unpublished($id) {
		$db = JFactory::getDBO();
		$query = "UPDATE #__se_keywordgrouplist SET published='0' WHERE id=" . $id;
	
		$db->setQuery($query);
		
		if (!$db->query()) {
			echo "<script>alert('" . $db->getErrorMsg() . "'); window.history.go(-1);</script>\n";
			exit();
		}
	}
	
	function remove($se_keywordgroup_id) {
		$this->_getList( "DELETE FROM #__se_keywordgrouplist WHERE id = '".$se_keywordgroup_id."'" );
	}
}

?>
