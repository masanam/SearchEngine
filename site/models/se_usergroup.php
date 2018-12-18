<?php

defined('_JEXEC') or die('Restricted access');
jimport('joomla.application.component.modellist');

class SearchEngineModelSe_usergroup extends JModelLegacy {

    public $insertid;
    
    public function getUserGroupList($user_id,$package_id,$limitstart,$limit){
        
        $db     = JFactory::getDBO();
        $query  = $db->getQuery(true);
        $query->select('*');
        $query->from($db->QuoteName('#__se_usergroups'));
        $query->where("package_id='".$package_id."'");   
        $query->where("created_by='".$user_id."'");         
        $db->setQuery($query,$limitstart,$limit);

        $rows = $db->loadObjectList();

        $subQuery = $db->getQuery(true);
        $subQuery->select('COUNT(*)');      
        $subQuery->from($db->QuoteName('#__se_usergroups'));
        $subQuery->where("package_id='".$package_id."'");    
        $subQuery->where("created_by='".$user_id."'");    
        $db->setQuery($subQuery);
        $this->total = (int) $db->loadResult();
        
        return $rows;
    }

    public function filter_field($package_id, $field, $group_id) {
        $query = $this->_db->getQuery(true);
        $query->select('*');
        $query->from($this->_db->quoteName('#__se_usergroups_fields'));
        $query->where($this->_db->quoteName('package_id') . "='" . $package_id . "'");
        $query->where($this->_db->quoteName('field') . "='" . $field . "'");
        $query->where($this->_db->quoteName('usergroup_id') . "='" . $group_id . "'");
        $this->_db->setQuery($query);
        $fields = $this->_db->loadObjectList();
        return $fields;
    }

    public function get_group_by_id($id){
        $db     = JFactory::getDBO();
        $query  = $db->getQuery(true);
        $query->select('*');
        $query->from($db->QuoteName('#__se_usergroups'));
        $query->where("id='".$id."'");        
        $db->setQuery($query);
        return $db->loadObject();

    }

   public function insert_criteria($data,$package_id,$group_id,$title) {
   
        $field = $data->field;
        $db     = JFactory::getDBO();
        $created = date('Y-m-d G:i:s');
        if($group_id<=0){           

		$users	= JFactory::getUser();
		$created_by=$users->id;			   
        

            $columns = array('title','package_id','created', 'created_by');
            $values = array(
                        $db->quote($title), 
                        $db->quote($package_id),
                        $db->quote($created),                        
                        $db->quote($created_by)                                                                     
                   );

            $query = $db->getQuery(true);
            $query
            ->insert($db->quoteName('#__se_usergroups'))
            ->columns($db->quoteName($columns))
            ->values(implode(',', $values));
             $db->setQuery($query);
           
            if( $db->execute() ){
                     $group_id = $db->insertid();
            }   

        }else{
             $fields = array(
                    $db->quoteName('title') . ' = ' . $db->quote($title),
                    $db->quoteName('modified') . ' = ' . $db->quote(date("Y-m-d G:i:s"))
                );
                
            $conditions = array(
                $db->quoteName('id') . ' = ' . $db->quote($group_id)
            );
            $query = $db->getQuery(true);
            $query->update($db->quoteName('#__se_usergroups'))->set($fields)->where($conditions);
            $db->setQuery($query);
            $db->execute();
        }
        
        switch($field){
            case 'name':
                $columns = array('package_id','population','firstname','lastname','field','usergroup_id');
                $values = array( $db->quote($package_id), $db->quote($data->population),$db->quote($data->firstname),$db->quote($data->lastname),$db->quote($data->field),$db->quote($group_id) );
                break;           

            case 'email':
                $columns = array('package_id','population','email','field','usergroup_id');
                $values = array( $db->quote($package_id), $db->quote($data->population),$db->quote($data->email),$db->quote($data->field),$db->quote($group_id) );        
                break;

            case 'age':
                $columns = array('package_id','population','from_age','to_age','field','usergroup_id');
                $values = array( $db->quote($package_id), $db->quote($data->population),$db->quote($data->from_age),$db->quote($data->to_age),$db->quote($data->field),$db->quote($group_id) );
                break;

            case 'gender':
                $columns = array('package_id','population','gender','field','usergroup_id');
                $values = array( $db->quote($package_id), $db->quote($data->population),$db->quote($data->gender),$db->quote($data->field),$db->quote($group_id) );            
                break;

            case 'location':
                $columns = array('package_id','population','state','country','field','usergroup_id');   
                 $values = array( $db->quote($package_id), $db->quote($data->population),$db->quote($data->state),$db->quote($data->country),$db->quote($data->field),$db->quote($group_id) );            
                break;    


        }

         $query = $db->getQuery(true);
            $query
            ->insert($db->quoteName('#__se_usergroups_fields'))
            ->columns($db->quoteName($columns))
            ->values(implode(',', $values));
             $db->setQuery($query);

        if( $db->execute() ){
            return $group_id;
        } else{
           return false;
        }
                        
    }

    public function get_group_creteria($gid,$package_id){
        $db     = JFactory::getDBO();
        $query  = $db->getQuery(true);
        $query->select('*');
        $query->from($db->QuoteName('#__se_usergroups_fields'));
        $query->where("usergroup_id='".$gid."'");   
        $query->where("package_id='".$package_id."'");         
        $db->setQuery($query);
        return $db->loadObjectList();
    }

    function registered_users($groupCreteria,$group_id){

        if($group_id == ""){
            $group_id = 0;
        }
        
        $db     = JFactory::getDBO();
        $query  = $db->getQuery(TRUE);
        
        $where = "1!=1 ";
        foreach($groupCreteria as $criteria){
            $where.= ((!empty($criteria->firstname) && $criteria->firstname != '') ? 'OR lower(firstname) like \'%' . strtolower($criteria->firstname) . '%\' ' : '') ;
            $where.= ((!empty($criteria->lastname) && $criteria->lastname != '') ? ' OR lower(lastname) like \'%' . strtolower($criteria->lastname) . '%\' ' : '') ;
            $where.= ((!empty($criteria->email) && $criteria->email != '') ? ' OR lower(email) like \'%' . strtolower($criteria->email) . '%\' ' : '') ;
            $where.= ((!empty($criteria->gender) && $criteria->gender != '') ? ' OR lower(gender) like \'%' . strtolower($criteria->gender) . '%\' ' : '') ;
            //$where.= ((!empty($criteria->street) && $criteria->street != '') ? ' OR lower(street) like \'%' . strtolower($criteria->street) . '%\' ' : '') ;
            //$where.= ((!empty($criteria->city) && $criteria->city != '') ? ' OR lower(city) like \'%' . strtolower($criteria->city) . '%\' ' : '') ;
            //$where.= ((!empty($criteria->state) && $criteria->state != '') ? ' OR lower(state) like \'%' . strtolower($criteria->state) . '%\' ' : '') ;
            $where.= ((!empty($criteria->state) && $criteria->state != '') ? ' OR ( lower(state) like ' . $db->Quote('%'.strtolower($criteria->state).'%') : '') ;
            //$where.= ((!empty($criteria->post_code) && $criteria->post_code != '') ? ' OR lower(post_code) like \'%' . strtolower($criteria->post_code) . '%\' ' : '') ;
            $where.= ((!empty($criteria->country) && $criteria->country != '') ? ' AND lower(country) like \'%' . strtolower($criteria->country) . '%\' )' : '') ;
        }

        $query = "SELECT * from #__ap_useraccounts where (".$where.")";

        $db->setQuery($query);
        $accounts = $db->loadObjectList();
        return $accounts;
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

        $query->delete($db->quoteName('#__se_usergroups'));
        $query->where($conditions);
        $db->setQuery($query);


        if( $db->execute() ){

            $query = $db->getQuery(true);
            $conditions = array(
                $db->quoteName('usergroup_id') . ' IN('.$cid.')'     
            );
            
            $query->delete($db->quoteName('#__se_usergroups_fields'));
            $query->where($conditions);
            $db->setQuery($query);

            $db->execute();

            return true;
        }else{
            return false;
        }

    }

    public function save($data){
        
        $db = JFactory::getDbo();
        
        if($data->id>0){
            $fields = array(
                    $db->quoteName('title') . ' = ' . $db->quote($data->title),
                    $db->quoteName('modified') . ' = ' . $db->quote(date("Y-m-d G:i:s"))
                );
                
            $conditions = array(
                $db->quoteName('id') . ' = ' . $db->quote($data->id)
            );
            $query = $db->getQuery(true);
            $query->update($db->quoteName('#__se_usergroups'))->set($fields)->where($conditions);
            $db->setQuery($query);
            if($db->execute()){
                return true;
            }else{
                return false;
            }

        }else{
			
			$users	= JFactory::getUser();
			$created_by=$users->id;
				   
           $columns = array('title','package_id','created', 'created_by');
            $values = array(
                        $db->quote($data->title), 
                        $db->quote($data->package_id),
                        $db->quote($data->created_at),                        
                        $db->quote($created_by)                                                                    
                   );
            $query = $db->getQuery(true);
            $query
            ->insert($db->quoteName('#__se_usergroups'))
            ->columns($db->quoteName($columns))
            ->values(implode(',', $values));
             $db->setQuery($query);
           
            if( $db->execute() ){
                 $this->insertid = $db->insertid();
                 return true;
            } else{

                return false;
            }
        }
    }



	


    public function deletecriteria($cid){
        
        if(is_array($cid)){
            $cid = implode(',', $cid);
        }

        $db = JFactory::getDbo();
        $query = $db->getQuery(true);

        $conditions = array(
            $db->quoteName('criteria_id') . ' IN('.$cid.')'     
        );

         $query->delete($db->quoteName('#__se_usergroups_fields'));
         $query->where($conditions);
         $db->setQuery($query);
	if( $db->execute() ){
                
                 return true;
            } else{

                return false;
            }


        

    }


}
