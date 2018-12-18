<?php
// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.modellist');

class SearchEngineModelDashboard extends JModelLegacy
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
            $this->_data = $this->_getList("SELECT * FROM #__se_rewardlist ORDER BY id");
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
        $query->select('*,(select count(*) from  #__se_surveygrouplist_surveys 
LEFT JOIN #__survey s on s.id = #__se_surveygrouplist_surveys.survey_id
where surveygroup_id=(select surveygroup from #__se_rewardlist where id=a.id)
AND s.published=1) as surveycount,
(select count(*) from  #__se_quizgrouplist_quizs
LEFT JOIN #__quiz_quizzes q on q.id = #__se_quizgrouplist_quizs.quiz_id
where quizgroup_id=(select quizgroup from #__se_rewardlist where id=a.id)
AND q.published=1) as quizcount,
(select count(*) from #__se_urlrewardlist_settings_ugs
LEFT JOIN #__se_urlgrouplist ON #__se_urlgrouplist.id=#__se_urlrewardlist_settings_ugs.urllist
WHERE urlrewardid  = (select urlgroup from #__se_rewardlist where id=a.id)
AND #__se_urlgrouplist.published=1) as urlgroupcount');
        $query->from($db->QuoteName('#__se_rewardlist')." as a");
        $query->where("package_id='".$package_id."'");    
		$query->where("created_by='".$user_id."'");
        $db->setQuery($query,$limitstart,$limit);

        $rows = $db->loadObjectList();
		
        $subQuery = $db->getQuery(true);
        $subQuery->select('COUNT(*)');      
        $subQuery->from($db->QuoteName('#__se_rewardlist'));
        $subQuery->where("package_id='".$package_id."'");   
		$subQuery->where("created_by='".$user_id."'");   
        $db->setQuery($subQuery);
        $this->total = (int) $db->loadResult();
        
        return $rows;
    }
	
	public function get_serl_by_id($id=""){
        $query = "SELECT * FROM #__se_rewardlist where id='$id'";
        $this->_db->setQuery($query);
        $result = $this->_db->loadObjectList();
        return $result;         
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
	
	public function save($data){
        $db     = JFactory::getDBO();
        $query = $db->getQuery(true);
		
		
        if($data->id>0){
            
            $fields = array(
				$db->quoteName('title') . ' = ' . $db->quote($data->title),
				$db->quoteName('sedescription') . ' = ' . $db->quote($data->sedescription),
				$db->quoteName('modified') . ' = ' . $db->quote(date("Y-m-d G:i:s")),
				
				$db->quoteName('package_id') . ' = ' . $db->quote($data->package_id),
				$db->quoteName('user_id') . ' = ' . $db->quote($data->user_id),
				$db->quoteName('startpublishdate') . ' = ' . $db->quote($data->startpublishdate),
				$db->quoteName('endpublishdate') . ' = ' . $db->quote($data->endpublishdate),
				$db->quoteName('usergroup') . ' = ' . $db->quote($data->usergroup),
				$db->quoteName('keywordgroup') . ' = ' . $db->quote($data->keywordgroup),
				$db->quoteName('urlgroup') . ' = ' . $db->quote($data->urlgroup),
				$db->quoteName('surveygroup') . ' = ' . $db->quote($data->surveygroup),
				$db->quoteName('quizgroup') . ' = ' . $db->quote($data->quizgroup),
				$db->quoteName('usergroupfull') . ' = ' . $db->quote($data->usergroupfull),
				$db->quoteName('keywordgroupfull') . ' = ' . $db->quote($data->keywordgroupfull),
				$db->quoteName('urlgroupfull') . ' = ' . $db->quote($data->urlgroupfull),
				$db->quoteName('surveygroupfull') . ' = ' . $db->quote($data->surveygroupfull),
				$db->quoteName('quizgroupfull') . ' = ' . $db->quote($data->quizgroupfull),
				$db->quoteName('usergroupdesc') . ' = ' . $db->quote($data->usergroupdesc),
				$db->quoteName('keywordgroupdesc') . ' = ' . $db->quote($data->keywordgroupdesc),
				$db->quoteName('urlgroupdesc') . ' = ' . $db->quote($data->urlgroupdesc),
				$db->quoteName('surveygroupdesc') . ' = ' . $db->quote($data->surveygroupdesc),
				$db->quoteName('quizgroupdesc') . ' = ' . $db->quote($data->quizgroupdesc)
			);
			
			
						
			$conditions = array(
				$db->quoteName('id') . ' = ' . $db->quote($data->id)
			);
			$query = $db->getQuery(true);
			$query->update($db->quoteName('#__se_rewardlist'))->set($fields)->where($conditions);
			$db->setQuery($query);
			$db->execute();
			
		   return true;
        }else{			
           $columns = array('title','sedescription','created','package_id','user_id','startpublishdate','endpublishdate','usergroup','keywordgroup','urlgroup','surveygroup','quizgroup','usergroupfull','keywordgroupfull','urlgroupfull','surveygroupfull','quizgroupfull','usergroupdesc','keywordgroupdesc','urlgroupdesc','surveygroupdesc','quizgroupdesc','created_by');
            $values = array(
                        $db->quote($data->title), 
						$db->quote($data->sedescription), 
                        $db->quote(date("Y-m-d G:i:s")),                        
                        $db->quote($data->package_id),
						$db->quote($data->user_id),
						$db->quote($data->startpublishdate),
						$db->quote($data->endpublishdate),
						$db->quote($data->usergroup),
						$db->quote($data->keywordgroup),
						$db->quote($data->urlgroup),
						$db->quote($data->surveygroup),
						$db->quote($data->quizgroup),
						$db->quote(json_encode($data->usergroupfull)),
						$db->quote(json_encode($data->keywordgroupfull)),
						$db->quote(json_encode($data->urlgroupfull)),
						$db->quote(json_encode($data->surveygroupfull)),
						$db->quote(json_encode($data->quizgroupfull)),
						$db->quote($data->usergroupdesc),
						$db->quote($data->keywordgroupdesc),
						$db->quote($data->urlgroupdesc),
						$db->quote($data->surveygroupdesc),
						$db->quote($data->quizgroupdesc),
						$db->quote($data->user_id)
                   );
			
			$query
            ->insert($db->quoteName('#__se_rewardlist'))
            ->columns($db->quoteName($columns))
            ->values(implode(',', $values));
             $db->setQuery($query);
            if( $db->execute() ){
                $id = $db->insertid();
            }
			
            return true;
        }
        return false;
    }
	
	function remove($se_keywordgroup_id) {
		$this->_getList( "DELETE FROM #__se_rewardlist WHERE id = '".$se_keywordgroup_id."'" );
	}
	
	function get_seuglist($package_id,$user_id) {
        
            if (empty($this->_seuglist)) {
              $this->_seuglist = $this->_getList("SELECT * FROM #__se_usergroups
                                                    WHERE package_id = '$package_id' and  created_by = '$user_id' ORDER BY id ASC");
            }            
            return $this->_seuglist;    
    }
	function get_kglist($package_id,$user_id) {
        
            if (empty($this->_kglist)) {
              $this->_kglist = $this->_getList("SELECT *,(select count(g_l.id) from #__se_keywordgrouplist_keywords g_l where g_l.keywordgroup_id = g.id ) as count1 FROM #__se_keywordgrouplist g WHERE package_id = '$package_id' and  created_by = '$user_id' ORDER BY id ASC");
            }          
            return $this->_kglist;    
    }
	function get_uglist($package_id) {
        
            if (empty($this->_uglist)) {
              $this->_uglist = $this->_getList("SELECT *,(select count(g_l.id) from #__se_urlgrouplist_urls g_l where g_l.urlgroup_id = g.id ) as count1 FROM #__se_urlgrouplist g WHERE package_id = '$package_id' ORDER BY id ASC");
            }         
            return $this->_uglist;    
    }
	function get_sglist($package_id,$user_id) {
			if (empty($this->_sglist)) {
              $this->_sglist = $this->_getList("SELECT *,(select count(g_l.id) from #__se_surveygrouplist_surveys g_l where g_l.surveygroup_id = g.id ) as count1 FROM #__se_surveygrouplist g WHERE package_id = '$package_id' and  created_by = '$user_id' ORDER BY id ASC");
            } 
			
            return $this->_sglist;    
    }
	function get_qglist($package_id,$user_id) {

            if (empty($this->_qglist)) {
              $this->_qglist = $this->_getList("SELECT *,(select count(g_l.id) from #__se_quizgrouplist_quizs g_l where g_l.quizgroup_id = g.id ) as count1 FROM #__se_quizgrouplist g WHERE package_id = '$package_id' and  created_by = '$user_id' ORDER BY id ASC");
            }            
            return $this->_qglist;    
    }
	function get_urlist($package_id,$user_id) {
        
            if (empty($this->_urlist)) {
              $this->_urlist = $this->_getList("SELECT *,(select count(g_l.id) from #__se_urlrewardlist_settings_ugs g_l where g_l.urlrewardid = g.id ) as count1 FROM #__se_urlrewardlist g WHERE package_id = '$package_id' and  created_by = '$user_id' ORDER BY id ASC");
            }         
            return $this->_urlist;    
    }
	
	  
	  
	  
}

?>
