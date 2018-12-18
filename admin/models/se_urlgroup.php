<?php
// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.modellist');

class SearchEngineModelse_urlgroup extends JModelLegacy
{
    var $_getListQuery;
	var $_urlgroup_title;
    
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
            $this->_data = $this->_getList("SELECT * FROM #__se_urlgrouplist ORDER BY id");
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
        $query->from($db->QuoteName('#__se_urlgrouplist'));
        $query->where("package_id='".$package_id."'"); 
        $query->where("created_by='".$user_id."'");            
        $db->setQuery($query,$limitstart,$limit);

        $rows = $db->loadObjectList();
		
        $subQuery = $db->getQuery(true);
        $subQuery->select('COUNT(*)');      
        $subQuery->from($db->QuoteName('#__se_urlgrouplist'));
        $subQuery->where("package_id='".$package_id."'");    
        $subQuery->where("created_by='".$user_id."'");      
        $db->setQuery($subQuery);
        $this->total = (int) $db->loadResult();
        
        return $rows;
    }
	
	function get_urlgroup_title($urlgroup_id) {
		if (empty($this->_urlgroup_title)) {
				$this->_urlgroup_title = $this->_getList("SELECT title
                FROM #__se_urlgrouplist 
                WHERE id = '".$urlgroup_id."'
                ORDER BY id 
                DESC LIMIT 1"); 
		}
		return $this->_urlgroup_title;
	}
	
	
	
	function get_urlgroup_urls($urlgroup_id) {
		$query = 'SELECT * FROM #__se_urlgrouplist_urls where urlgroup_id="'.$urlgroup_id.'"';
		$this->_db->setQuery($query);
		$result = $this->_db->loadObjectList();
		return $result;
	}
	
	function save($url_group_name, $urltitles, $edit) {
		//echo $setid;
		//print_r($urltitles);
		$package_id = JRequest::getVar('package_id');
		$id = JRequest::getVar('cid');
		$created = !empty($created_date) ? $created_date : date('Y-m-d G:i:s');
		$modified = date('Y-m-d G:i:s');
		$users	= JFactory::getUser();
			$created_by=$users->id;
				
		$ret = $id;
		
        if (!empty($package_id)  AND empty($id)) {
            $db = JFactory::getDbo();
            $query = $db->getQuery(true);
			
			
				
            $columns = array('title', 'package_id', 'created', 'created_by', 'published');
            $values = array($db->quote($url_group_name), $db->quote($package_id), $db->quote($created),  $db->quote($created_by),1);
            $query
                    ->insert($db->quoteName('#__se_urlgrouplist'))
                    ->columns($db->quoteName($columns))
                    ->values(implode(',', $values));
            $db->setQuery($query);
            $db->execute();
            $ret = $db->insertid();
        }
	else{
		
		$db = JFactory::getDBO();
		
		
		$fields = array(
                    $db->quoteName('title') . ' = ' . $db->quote($url_group_name),
                    $db->quoteName('modified') . ' = ' . $db->quote(date("Y-m-d G:i:s"))
                );
                
            $conditions = array(
                $db->quoteName('id') . ' = ' . $db->quote($id)
            );
            $query = $db->getQuery(true);
            $query->update($db->quoteName('#__se_urlgrouplist'))->set($fields)->where($conditions);
            $db->setQuery($query);
			$db->execute();	
		
	}	
        
		if (!empty($ret)) {			
			$this->_getList( "DELETE FROM #__se_urlgrouplist_urls WHERE urlgroup_id = '".$ret."'" );
			
			foreach($urltitles as $urltitle){
				if(!empty($urltitle)){
				$db = JFactory::getDbo();
				$query = $db->getQuery(true);
				$columns = array('title', 'urlgroup_id', 'package_id', 'created', 'modified', 'created_by');
				$values = array($db->quote($urltitle), $db->quote($ret), $db->quote($package_id), $db->quote($created), $db->quote($modified), $db->quote($created_by));
				$query
						->insert($db->quoteName('#__se_urlgrouplist_urls'))
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
		$query = "UPDATE #__se_urlgrouplist SET published='1' WHERE id=" . $id;
	
		$db->setQuery($query);
		
		if (!$db->query()) {
			echo "<script>alert('" . $db->getErrorMsg() . "'); window.history.go(-1);</script>\n";
			exit();
		}
	}
	
	function unpublished($id) {
		$db = JFactory::getDBO();
		$query = "UPDATE #__se_urlgrouplist SET published='0' WHERE id=" . $id;
	
		$db->setQuery($query);
		
		if (!$db->query()) {
			echo "<script>alert('" . $db->getErrorMsg() . "'); window.history.go(-1);</script>\n";
			exit();
		}
	}
	
	function remove($se_urlgroup_id) {
		$this->_getList( "DELETE FROM #__se_urlgrouplist WHERE id = '".$se_urlgroup_id."'" );
	}
}

?>
