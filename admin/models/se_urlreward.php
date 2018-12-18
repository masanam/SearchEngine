<?php

defined('_JEXEC') or die('Restricted access');
jimport('joomla.application.component.modellist');

class SearchEngineModelSe_urlreward extends JModelLegacy {

    
    public function getUrlrewardlist($user_id,$package_id,$limitstart,$limit){
        
        $db     = JFactory::getDBO();
        $query  = $db->getQuery(true);
        $query->select('*');
        $query->from($db->QuoteName('#__se_urlrewardlist'));
        $query->where("package_id='".$package_id."'");
        $query->where("created_by='".$user_id."'");              
        $db->setQuery($query,$limitstart,$limit);
        $rows = $db->loadObjectList();

        $subQuery = $db->getQuery(true);
        $subQuery->select('COUNT(*)');      
        $subQuery->from($db->QuoteName('#__se_urlrewardlist'));
        $subQuery->where("package_id='".$package_id."'");    
        $subQuery->where("created_by='".$user_id."'");       
        $db->setQuery($subQuery);
        $this->total = (int) $db->loadResult();
        
        return $rows;
    }

     public function getMemberTicketList($package_id){
        
        $db     = JFactory::getDBO();
        $query  = $db->getQuery(true);
        $query->select('*');
        $query->from($db->QuoteName('#__membertickets'));
        $query->where("package_id='".$package_id."'");        
        $db->setQuery($query);
        $rows = $db->loadObjectList();
        return $rows;
    }

    public function save($data){

        $db     = JFactory::getDBO();
        $query = $db->getQuery(true);
        if($data->urlrewardid>0){
            
            $query = $db->getQuery(true);
            $conditions = array(
                $db->quoteName('urlrewardid') . ' = ' . $db->quote($data->urlrewardid)
            );
            $query->delete($db->quoteName('#__se_urlrewardlist_settings'));
            $query->where($conditions);
            $db->setQuery($query);
            $result = $db->execute(); 

            if($result)
            {

                $fields = array(
                    $db->quoteName('title') . ' = ' . $db->quote($data->title),
                    $db->quoteName('additionalsettings') . ' = ' . $db->quote($data->additionalsettings),
                    $db->quoteName('modified') . ' = ' . $db->quote(date("Y-m-d G:i:s"))
                );
                
                $conditions = array(
                    $db->quoteName('id') . ' = ' . $db->quote($data->urlrewardid)
                );
                $query = $db->getQuery(true);
                $query->update($db->quoteName('#__se_urlrewardlist'))->set($fields)->where($conditions);
                $db->setQuery($query);
                $db->execute();

                $ruleday = null;
				$ruleticket = null;
					
                foreach($data->weekdays as $rule){
                    $ruleday = $rule['day'];
					$ruleticket = $rule['ticket'];
                }

	
				
				$query = $db->getQuery(true);
				$columns = array('urlrewardid','giftcodes','urllist','additionalsettings');
				$values = array($data->urlrewardid,$db->quote(json_encode($ruleday)),$db->quote(json_encode($ruleticket)),$db->quote($data->additionalsettings));
				$query
				->insert($db->quoteName('#__se_urlrewardlist_settings'))
				->columns($db->quoteName($columns))
				->values(implode(',', $values));            
				$db->setQuery($query);
				$db->execute();

//print_r($ruleticket);die;	


//print_r($ruleday);die;
//Array ( [0] => 1,RED,11,4 [1] => 2,ORANGE,12,5 ) 

$query = $db->getQuery(true);
$conditions = array(
$db->quoteName('urlrewardid') . ' = ' . $db->quote($data->urlrewardid)
);
$query->delete($db->quoteName('#__se_urlrewardlist_settings_gc'));
$query->where($conditions);
$db->setQuery($query);
$result = $db->execute();

foreach($ruleday as $ruleday1){
$ruleday1id=explode(",",$ruleday1);

$query = $db->getQuery(true);
$columns = array('urlrewardid','gcid','gcname','gcqty','gccost');
$values = array($data->urlrewardid,$ruleday1id[0],$db->quote($ruleday1id[1]),$ruleday1id[2],$ruleday1id[3]);
$query
->insert($db->quoteName('#__se_urlrewardlist_settings_gc'))
->columns($db->quoteName($columns))
->values(implode(',', $values));            
$db->setQuery($query);
$db->execute();


}


				$query = $db->getQuery(true);
			    $conditions = array(
				$db->quoteName('urlrewardid') . ' = ' . $db->quote($data->urlrewardid)
			    );
			    $query->delete($db->quoteName('#__se_urlrewardlist_settings_ugs'));
			    $query->where($conditions);
			    $db->setQuery($query);
			    $result = $db->execute(); 
				
				foreach($ruleticket as $ruleticket1){
					//echo $ruleticket1;
					$ruleticketid=explode(",",$ruleticket1);
				
					$query = $db->getQuery(true);
					$columns = array('urlrewardid','urllist');
					$values = array($data->urlrewardid,$ruleticketid[0]);
					$query
					->insert($db->quoteName('#__se_urlrewardlist_settings_ugs'))
					->columns($db->quoteName($columns))
					->values(implode(',', $values));            
					$db->setQuery($query);
					$db->execute();
				}
				
               return true;
            }

        }else{
						
			$users	= JFactory::getUser();
			$created_by=$users->id;
				
           $columns = array('title','additionalsettings','created','package_id', 'created_by');
            $values = array(
                        $db->quote($data->title),
                        $db->quote($data->additionalsettings),
                        $db->quote($data->created_at),                        
                        $db->quote($data->package_id),                        
                        $db->quote($created_by)                      
                   );
            $query
            ->insert($db->quoteName('#__se_urlrewardlist'))
            ->columns($db->quoteName($columns))
            ->values(implode(',', $values));
             $db->setQuery($query);
            if( $db->execute() ){
                $urlrewardid = $db->insertid();
				
				$ruleday = null;
				$ruleticket = null;
					
                foreach($data->weekdays as $rule){
                    $ruleday = $rule['day'];
					$ruleticket = $rule['ticket'];
                }
				
				
				$query = $db->getQuery(true);
				$columns = array('urlrewardid','giftcodes','urllist','additionalsettings');
				$values = array($urlrewardid,$db->quote(json_encode($ruleday)),$db->quote(json_encode($ruleticket)),$db->quote($data->additionalsettings));
				$query
				->insert($db->quoteName('#__se_urlrewardlist_settings'))
				->columns($db->quoteName($columns))
				->values(implode(',', $values));            
				$db->setQuery($query);
				$db->execute();


				foreach($ruleticket as $ruleticket1){
					//echo $ruleticket1;
					$ruleticketid=explode(",",$ruleticket1);
				
					$query = $db->getQuery(true);
					$columns = array('urlrewardid','urllist');
					$values = array($urlrewardid,$ruleticketid[0]);
					$query
					->insert($db->quoteName('#__se_urlrewardlist_settings_ugs'))
					->columns($db->quoteName($columns))
					->values(implode(',', $values));            
					$db->setQuery($query);
					$db->execute();
				}


				foreach($ruleday as $ruleday1){
					$ruleday1id=explode(",",$ruleday1);

					$query = $db->getQuery(true);
					$columns = array('urlrewardid','gcid','gcname','gcqty','gccost');
					$values = array($urlrewardid,$ruleday1id[0],$db->quote($ruleday1id[1]),$ruleday1id[2],$ruleday1id[3]);
					$query
					->insert($db->quoteName('#__se_urlrewardlist_settings_gc'))
					->columns($db->quoteName($columns))
					->values(implode(',', $values));            
					$db->setQuery($query);
					$db->execute();
				}


					
            }

            return true;
        }

        return false;
    }


    public function get_rule_details_by_id($id=""){
        $query = "SELECT * FROM #__se_urlrewardlist where id='$id'";
        $this->_db->setQuery($query);
        $result = $this->_db->loadObjectList();
        return $result;         
    }
    
    public function get_rule_settings_by_rule_id($id=""){
        $query = "SELECT * FROM #__se_urlrewardlist_settings where urlrewardid='$id'";
        $this->_db->setQuery($query);
        $result = $this->_db->loadObjectList();
        return $result;         
    }





    public function getMemberTicket($id){
        $db     = JFactory::getDBO();
        $query  = $db->getQuery(true);
        $query->select('*');
        $query->from($db->QuoteName('#__membertickets'));
        $query->where("id='".$id."'");
        $db->setQuery($query);
        $row = $db->loadObject();
        return $row;
    }

    public function delete($cid){
        
        if(is_array($cid)){
            $cid = implode(',', $cid);
        }

        $db = JFactory::getDbo();
        $query = $db->getQuery(true);

        $conditions = array(
            $db->quoteName('id') . ' IN('.$cid.')'     
        );

        $query->delete($db->quoteName('#__se_urlrewardlist'));
        $query->where($conditions);
        $db->setQuery($query);

        if( $db->execute() ){

            $query = $db->getQuery(true);
            $conditions = array(
                $db->quoteName('urlrewardid') . ' IN('.$cid.')'     
            );
            $query->delete($db->quoteName('#__se_urlrewardlist_settings'));
            $query->where($conditions);
            $db->setQuery($query);
            $db->execute();

            return true;
        }else{
            return false;
        }

    }
	
	public function getUrlGroupList($package_id){
        
        $db     = JFactory::getDBO();
        $query  = $db->getQuery(true);
        $query->select('*');
        $query->from($db->QuoteName('#__se_urlgrouplist'));
        $query->where("package_id='".$package_id."'");          
        $db->setQuery($query);
        $rows = $db->loadObjectList();
        return $rows;
    }
	
		var $_categories;
    
      function get_categories($package_id) {
        
            if (empty($this->_categories)) {
              $this->_categories = $this->_getList("SELECT * FROM #__ap_categories
                                                    WHERE package_id = '$package_id' ORDER BY category_id ASC");
            }            
            return $this->_categories;    
      }

}
