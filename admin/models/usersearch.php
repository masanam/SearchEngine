<?php

defined('_JEXEC') or die('Restricted access');
jimport('joomla.application.component.modeladmin');

class SearchEngineModelUsersearch extends JModelAdmin {

    public $total; 
    public function __construct($config = array()) {
        parent::__construct($config);
        $this->_id = JRequest::getVar('criteria_id');
        $this->_package_id = JRequest::getVar('package_id');
        $this->_db = JFactory::getDbo();
    }

    public function getForm($data = array(), $loadData = true) {
        // Initialise variables.
        $app = JFactory::getApplication();
        // Get the form.
        $form = $this->loadForm('com_searchengine.usertransaction', 'usertransaction', array('control' => 'jform', 'load_data' => $loadData));
        if (empty($form)) {
            return false;
        }
        return $form;
    }


        
    /**
     * Method to get the data that should be injected in the form.
     *
     * @return  mixed   The data for the form.
     * @since   1.6
     */
    protected function loadFormData() {
        $data = "";
        $db = JFactory::getDbo();
        $query = $this->_db->getQuery(true);
        $query->select('*');
        $query->from($this->_db->quoteName('#__se_usergroup'));
        $query->where($this->_db->quoteName('criteria_id') . "='" . $this->_id . "'");
        $this->_db->setQuery($query);
        $rows = $db->loadObject();
        if ($rows) {
            foreach ($rows as $k => $row) {
                $data[$k] = $row;
            }
        }
        return $data;
    }

    /**
     * Method to get a single record.
     *
     * @param   integer The id of the primary key.
     *
     * @return  mixed   Object on success, false on failure.
     * @since   1.6
     */
    public function getItem($pk = null) {
        if ($item = parent::getItem($pk)) {

            //Do any procesing on fields here if needed
        }

        return $item;
    }

    /**
     * Prepare and sanitise the table prior to saving.
     *
     * @since   1.6
     */
    protected function prepareTable(&$table) {
        jimport('joomla.filter.output');

        if (empty($table->id)) {

            // Set ordering to the last item if not set
            if (@$table->ordering === '') {
                $this->_db->setQuery('SELECT MAX(ordering) FROM #__shopping_package');
                $max = $db->loadResult();
                $table->ordering = $max + 1;
            }
        }
    }

    public function getData() {
        return $this->loadFormData();
    }

    protected function preprocessForm(JForm $form, $data, $group = 'user') {
        parent::preprocessForm($form, $data, $group);
    }

   
    public function savepenaltyform($data){
        $query = "INSERT INTO `#__se_penalties` (title,message,days,created_at) 
             VALUES ('$data->title','$data->message','$data->days','$data->created_at')";
        
        $this->_db->setQuery($query);
        if( $this->_db->query() ){
            return $this->_db->insertid();
        }else{
            return false;
        }
    }

    public function getSPenaltyFormDetails($package_id,$user_id){
        $query = "SELECT a.created_at AS start_date , a.status, a.user_id, p.* FROM #__se_user_penalty AS a
                    LEFT JOIN #__se_penalties p ON p.id= a.penalty_form_id 
                    WHERE a.user_id='$user_id' AND a.package_id='$package_id'"; 
         $this->_db->setQuery($query);
         $result = $this->_db->loadObject();
         return $result;
    }

    public function updatepenaltyform($data){

        $query = "SELECT * FROM `#__se_penalties` WHERE assign_to = '$data->user_id' AND id ='$data->id' ";        
        $this->_db->setQuery($query);
        $result = $this->_db->loadObject();
        $insertid = '';

        if( $result ){
            $query = "UPDATE  `#__se_penalties` 
                    SET title='$data->title',
                    message='$data->message',
                    days='$data->days'
                    WHERE id='$result->id'";
                   
            $this->_db->setQuery($query);
            $this->_db->query(); 
            $insertid = '';


        }else{
             $result = $this->getCreatedDate($data);

             $query = "INSERT INTO `#__se_penalties` (title,message,days,created_at,assign_to,package_id) 
             VALUES ('$data->title','$data->message','$data->days','$result->created_at', '$data->user_id', '$data->package_id')";
             $this->_db->setQuery($query);
             $this->_db->query();
             $insertid = $this->_db->insertid();

             $query = "UPDATE  `#__se_user_penalty` 
                    SET penalty_form_id='$insertid'          
                    WHERE user_id='$data->user_id'
                    AND package_id='$data->package_id'";
             $this->_db->setQuery($query);
             $this->_db->query();

              $query = "UPDATE  `#__se_user_penalty_history` 
                    SET penalty_form_id='$insertid'          
                    WHERE user_id='$data->user_id'
                    AND package_id='$data->package_id'";
             $this->_db->setQuery($query);
             $this->_db->query();
                    

        }

        return  $insertid;


    }

    public function getCreatedDate($data,$insertId=null){
        $ID = $insertId>0? $insertId: $data->id;
        $query = "SELECT created_at FROM `#__se_user_penalty` WHERE penalty_form_id ='$ID' AND user_id= '$data->user_id'";        
        $this->_db->setQuery($query);
        $result = $this->_db->loadObject();
        return $result;

    }

    public function getPenalties($package_id){
        $query = $this->_db->getQuery(true);
        $query->select('*');
        $query->from($this->_db->quoteName('#__se_penalties'));
        $query->where($this->_db->quoteName('package_id') . "='" . $package_id . "'");
        $query->where($this->_db->quoteName('assign_to') . "='0'");
        $query->where($this->_db->quoteName('soft_delete') . "='0'");
        $this->_db->setQuery($query);
        $results = $this->_db->loadObjectList();
        return $results;
    }



    public function addPenaltyToUser($uid, $pid, $package_id, $created_at){
        $values = array();
        $history_values = array();
        foreach ($uid as $key => $user_id) {               
            //$values[] = "('$user_id','$package_id','$created_at','$pid', 1')"; 
            $history_values[] =  "('$user_id','$package_id','$created_at','$pid')";

            $query = $this->_db->getQuery(true);
            $query->select('*');
            $query->from($this->_db->quoteName('#__se_user_penalty'));
            $query->where($this->_db->quoteName('user_id') . "='" . $user_id . "'");          
            $query->where($this->_db->quoteName('package_id') . "='" . $package_id . "'");          
            $this->_db->setQuery($query);
            $result = $this->_db->loadObject();
            if( $result ){
                    $query = "  UPDATE `#__se_user_penalty` SET                        
                         created_at='$created_at',
                         penalty_form_id='$pid',
                         status= '1'"; 

                    $query = $query." WHERE user_id= '$user_id' AND package_id='$package_id'";  
                    $this->_db->setQuery($query);
                    $this->_db->query();             

            } else{

                $values[] = "('$user_id','$package_id','$created_at','$pid','1')"; 
            } 


            $query = "DELETE FROM `#__session` WHERE userid='$user_id'";            
            $db = JFactory::getDBO();
            $db->setQuery($query);
            $db->query(); 

            $query = "UPDATE `#__quiz_quizzes` SET published='2' WHERE created_by='$user_id' AND package_id='$package_id'";            
            $db = JFactory::getDBO();
            $db->setQuery($query);
            $db->query();  

            $query = "UPDATE `#__survey` SET published='2' WHERE created_by='$user_id' AND package_id='$package_id'";            
            $db = JFactory::getDBO();
            $db->setQuery($query);
            $db->query();                    


        }

        $query = "INSERT INTO `#__se_user_penalty_history` (user_id,package_id,created_at,penalty_form_id) VALUES " .implode(',',$history_values);       
        
        $db = JFactory::getDBO();
        $db->setQuery($query);
        $db->query();
        
        if(count($values)>0 ) {
            $query = "INSERT INTO `#__se_user_penalty` (user_id,package_id,created_at,penalty_form_id,status) VALUES " .implode(',',$values);            
            $db = JFactory::getDBO();
            $db->setQuery($query);
            $db->query();
        }
        return true;
    }

    public function removePenaltyToUser($uid, $package_id){

        foreach ($uid as $key => $user_id) {               
        
                $query = "  UPDATE `#__se_user_penalty` SET status= '0'"; 
                $query = $query." WHERE user_id= '$user_id' AND package_id='$package_id'";  
                $this->_db->setQuery($query);
                $this->_db->query();             
        }

    }

    public function getPenaltyDetails($id){
            $query = $this->_db->getQuery(true);
            $query->select('*');
            $query->from($this->_db->quoteName('#__se_penalties'));
            $query->where($this->_db->quoteName('id') . "='" . $id . "'");          
            $this->_db->setQuery($query);
            return $this->_db->loadObject();
    }


   
    public function insert_criteria($data,$package_id) {
   
        $field = $data->field;

        
        switch($field){
            case 'name':
             $query = "INSERT INTO `#__se_usersearch_usergroup` (package_id,population,firstname,lastname,field) 
             VALUES ('$package_id','$data->population','$data->firstname','$data->lastname','$data->field')";
            break;           
            case 'email':
             $query = "INSERT INTO `#__se_usersearch_usergroup` (package_id,population,email,field) 
             VALUES ('$package_id','$data->population','$data->email','$data->field')";
            break;
            case 'age':
             $query = "INSERT INTO `#__se_usersearch_usergroup` (package_id,population,from_age,to_age,field) 
             VALUES ('$package_id','$data->population','$data->from_age','$data->to_age','$data->field')";
            break;
            case 'gender':
             $query = "INSERT INTO `#__se_usersearch_usergroup` (package_id,population,gender,field) 
             VALUES ('$package_id','$data->population','$data->gender','$data->field')";
            break;
            case 'location':
            $query = "INSERT INTO `#__se_usersearch_usergroup` (package_id,population,street,city,state,post_code,country,field) 
             VALUES ('$package_id','$data->population','$data->street','$data->city',".$this->_db->Quote($data->state).",'$data->post_code','$data->country','$data->field')";
            break;    
        }
        $this->_db->setQuery($query);
        $this->_db->query();                 
    }


    public function getUserNameFromId($accountId){
        $query = " SELECT * FROM `#__se_useraccounts` a WHERE a.id = ".$accountId." ";
        $this->_db->setQuery($query);
        $users = $this->_db->loadObjectList();
        if(!empty($users)) {
            return $users;
        } else {
            return null;
        }
    }

    public function update_criteria($data,$package_id,$criteria_id) {
        
        $field = $data->field;
        switch($field){
            case 'name':
             $query = "  UPDATE `#__se_usersearch_usergroup` SET 
                         package_id= '$package_id',
                         population='$data->population',
                         firstname='$data->firstname',
                         lastname= '$data->lastname',
                         field= '$data->field'";             
            break; 

            case 'email':
             $query = "  UPDATE `#__se_usersearch_usergroup` SET 
                         package_id= '$package_id',
                         population='$data->population',
                         email='$data->email',                        
                         field= '$data->field'";               
            break;

            case 'age':
              $query = "  UPDATE `#__se_usersearch_usergroup` SET 
                         package_id= '$package_id',
                         population='$data->population',
                         from_age='$data->from_age', 
                         to_age='$data->to_age',                        
                         field= '$data->field'";              
            break;

            case 'gender':
                $query = "  UPDATE `#__eusersearch_usergroup` SET 
                         package_id= '$package_id',
                         population='$data->population',
                         gender='$data->gender',                                             
                         field= '$data->field'";     
            
            break;

            case 'location':
             $query = "  UPDATE `#__se_usersearch_usergroup` SET 
                         package_id= '$package_id',
                         population='$data->population',
                         street='$data->street', 
                         city='$data->to_age',
                         state=".$this->_db->Quote($data->state).", 
                         post_code='$data->post_code', 
                         country='$data->country',                         
                         field= '$data->field'";             
            break; 

            
        }

        $query = $query." WHERE criteria_id= '$criteria_id'";  
     
        $this->_db->setQuery($query);
        $this->_db->query();   

    }

    public function filter_field($package_id, $field) {
        $query = $this->_db->getQuery(true);
        $query->select('*');
        $query->from($this->_db->quoteName('#__se_usersearch_usergroup'));
        $query->where($this->_db->quoteName('package_id') . "='" . $package_id . "'");
        $query->where($this->_db->quoteName('field') . "='" . $field . "'");
        $this->_db->setQuery($query);
        $fields = $this->_db->loadObjectList();
        return $fields;
    }

    public function getCriteriaById($criteria_id){
        $query = $this->_db->getQuery(true);
        $query->select('*');
        $query->from($this->_db->quoteName('#__se_usersearch_usergroup'));
        $query->where($this->_db->quoteName('criteria_id') . "='" . $criteria_id . "'");          
        $this->_db->setQuery($query);
        return $this->_db->loadObject();

    }

    public function delete($criteria_id,$package_id){
        $query = "  DELETE FROM  `#__se_usersearch_usergroup` WHERE criteria_id= '$criteria_id' AND package_id='$package_id'";  
        $this->_db->setQuery($query); 
        $this->_db->query(); 
        $this->serach($package_id);

    }

    public function serach($package_id, $serachfor = null ){
        $query = $this->_db->getQuery(true);
        $query->select('*');
        $query->from($this->_db->quoteName('#__se_usersearch_usergroup'));
        $query->where($this->_db->quoteName('package_id') . "='" . $package_id . "'");       
        $this->_db->setQuery($query);
        $criterias = $this->_db->loadObjectList();
       
        $email= array();
        $name = array();       
        $street=array();
        $city=array();
        $state=array();
        $post_code=array();
        $country=array();
        $gender=array();
        $age=array();
        
        if(!$criterias)
            return null;


        if($criterias){
            foreach( $criterias as $criteria){
                $fnName= array();
                $ageFromTo= array();
                $alllocation= array();

                switch ($criteria->field) {                    
                    case 'email':
                        if(!empty($criteria->email)){
                            $email[]= "'".strtolower($criteria->email)."'";
                        }
                    break;
                    case 'gender':
                        if(!empty($criteria->gender)){
                            $gender[]= "'".$criteria->gender."'";
                        }
                    break;
                    case 'name':
                        if(!empty($criteria->firstname)){
                            $fnName[] = $criteria->firstname;                    

                        }
                        
                        if(!empty($criteria->lastname)){                
                              $fnName[] = $criteria->lastname;
                        }
                        $name[] = $fnName;
                    break;

                    case 'age':
                        if(!empty($criteria->from_age)){
                            $ageFromTo[] = $criteria->from_age;                   
                        }

                        if(!empty($criteria->to_age)){
                            $ageFromTo[] = $criteria->to_age;
                        }  
                        $age[] = $ageFromTo;
                    break;
                    case 'location':
                       /* if(!empty($criteria->street)){
                            $alllocation[]= "'".$criteria->street."'";
                        }
                        if(!empty($criteria->city)){
                           $alllocation[]= "'".$criteria->city."'";
                        }*/
                        if(!empty($criteria->state)){
                            $alllocation[]= "'".$criteria->state."'";
                        }
                        /*if(!empty($criteria->post_code)){
                            $alllocation[]= "'".$criteria->post_code."'";
                        }*/
                        if(!empty($criteria->country)){
                            $alllocation[]= "'".$criteria->country."'";
                        }
                        $location[] = $alllocation;
                    break;
                }
                            
            }                    
        }

        $conditions1 = array();
        $conditions2 = array();
        $conditions3 = array();
        $query = "SELECT *, CONCAT(`firstname`, ' ', `lastname`) AS NAME FROM #__se_useraccounts";

        if(count($email)>0){
            $email=  array_unique($email);
            $conditions1[]= $this->_db->quoteName('email') . 'IN('. implode(',',$email).')';             
        }

         if(count($gender)>0){
            $gender=  array_unique($gender);
            $conditions1[]= $this->_db->quoteName('gender') . 'IN('. implode(',',$gender).')';             
        }

        if($name){
            foreach($name as $n){
                if(count($n)>0){
                    $_query =array();
                    if(isset($n[0])){
                        $_query[]= 'LOWER('.$this->_db->quoteName('firstname') .")='".strtolower($n[0])."'";                    
                    }
                    if(isset($n[1])){
                        $_query[]= 'LOWER('.$this->_db->quoteName('lastname') .")='".strtolower($n[1])."'";                    
                    }
                    $conditions2[] =  '('. implode( ' AND ',$_query ) .')';                      
                }
            }
        }

        if($location){
            foreach($location as $loc){
                if(count($loc)>0){
                    $_query =array();
                   /* if(isset($loc[0])){
                        $_query[]= 'LOWER('.$this->_db->quoteName('street') .")=".strtolower($loc[0]);
                    }
                    if(isset($loc[1])){
                        $_query[]= 'LOWER('.$this->_db->quoteName('city') .")=".strtolower($loc[1]);
                    }
                    if(isset($loc[2])){
                        $_query[]= 'LOWER('.$this->_db->quoteName('state') .")=".strtolower($loc[2]);
                    }
                    if(isset($loc[3])){
                        $_query[]= $this->_db->quoteName('post_code') ."=".$loc[3];
                    }
                    if(isset($loc[4])){
                        $_query[]= 'LOWER('.$this->_db->quoteName('country') .")=".strtolower($loc[4]);
                    }*/

                     if(isset($loc[0])){
                         $_query[]= 'LOWER('.$this->_db->quoteName('state') .")=".strtolower( $this->_db->Quote( $loc[0] ) );
                    }

                     if(isset($loc[1])){
                       $_query[]= 'LOWER('.$this->_db->quoteName('country') .")=".strtolower($loc[1]);
                    }
                    $conditions3[] =  implode( ' AND ',$_query );                  
                   
                }
            }
        }

         if($age){
            foreach($age as $ag){
                if(count($ag)>0){
                    $_query =array();
                    if(isset($ag[0])){
                        $_query[]= 'TIMESTAMPDIFF(YEAR,'.$this->_db->quoteName('birthday').',CURDATE())' ."<='".$ag[0]."'";       
                    }
                    if(isset($ag[1])){                       
                        $_query[]= 'TIMESTAMPDIFF(YEAR,'.$this->_db->quoteName('birthday').',CURDATE())' .">='".$ag[1]."'";                     
                    }
                    $conditions4[] =  '('. implode( ' AND ',$_query ) .')';                      
                }
            }
        }


    
        $query .= " WHERE package_id='$package_id'";
        $sub_query=array();
        if(count($conditions1)>0){
            $sub_query[] = ' ('.implode(' OR ', $conditions1 ).')';
        }
        if(count($conditions2)>0){
            $sub_query[] .= ' ('.implode(' OR ', $conditions2 ).')';
        }

        if(count($conditions3)>0){
            $sub_query[] .= ' ('.implode(' OR ', $conditions3 ).')';
        }

         if(count($conditions4)>0){
            $sub_query[] .= ' ('.implode(' OR ', $conditions4 ).')';
        }

        if(sizeof($sub_query)>0 ) {
            $query .= ' AND ('. implode(' OR ',  $sub_query) .')';
        }

        ///echo str_replace('#__', 'raj_', $query);
        //exit;

        $this->_db->setQuery($query);        
        $result = $this->_db->loadObjectList(); 
        //$free_usergroup_id = array();
        $users= array();
        if( $result){

            foreach ($result as $key => $value) {
                $users_id[] = (int)  $value->id;               
            }

            $session =& JFactory::getSession();
            $session->set( 'serached_user', $users_id );
      
        }

         return $users_id ;

  
    }
    public function getSeachList($criteria, $package_id, $filter_start, $filter_end, $limitstart, $limit, $filter_by){
        $criteria = $criteria? $criteria: JRequest::getVar('criteria'); 
        $filter_count = count($filter_by);
        $records = array();

        if(isset($criteria)){

             $serached_user = $this->serach( $package_id);

           if( $serached_user ){ 

                $query = "SELECT u.*, b.`registerDate`, b.`username`,up.`status`,p.`days`,up.`created_at`, CONCAT(u.`firstname`, ' ', u.`lastname`) as fullname,
                          (SELECT count(*) FROM #__se_user_penalty_history WHERE user_id=u.id AND package_id=up.package_id) AS hcount,
                          (select count(*) as count from #__se_recieve_user where user_id = u.id) AS symbol_queue,
                          (select count(*) as count from #__member_assigned_tickets where user_id = u.id) AS tcount 
                          FROM #__se_useraccounts u
                          LEFT JOIN #__users b ON b.id= u.id
                          LEFT JOIN #__se_user_penalty up ON up.user_id= u.id 
                          LEFT JOIN #__se_penalties p ON p.id= up.penalty_form_id ";
                                                
                    
                $condtions = array();

                $condtions[] = ' u.id IN('. implode(',', $serached_user).')';

                if( $package_id){                 
                     $condtions[] ="u.`package_id` = '$package_id'";
                }

                $condtions[] = "b.`registerDate` >= '$filter_start' AND b.`registerDate` <='$filter_end'";

                //$condtions[] ='p.soft_delete=0';
                
                $query  .= ' WHERE ' . implode( ' AND ' , $condtions );
                $query .= ' ORDER BY b.registerDate DESC';
                
               // echo str_replace('#__', 'raj_', $query); exit;

                $this->_db->setQuery($query);        
                $results = $this->_db->loadObjectList();
                $this->total = count( $results );

                if($limit >0)
                    $query .=" LIMIT ".$limitstart.", ".$limit." ";             

               
                $this->_db->setQuery($query);        
                $results = $this->_db->loadObjectList();                
                return $results;
            }
        }
    }
    public function getHistory($user_id,$package_id){
        $query = "SELECT u.*, p.days,CONCAT(ua.firstname,' ', ua.lastname) AS fullname  from #__se_user_penalty_history u
                  LEFT JOIN #__se_penalties p ON p.id= u.penalty_form_id
                  LEFT JOIN #__se_useraccounts ua ON ua.id= u.user_id
                  WHERE  u.user_id = '$user_id' AND u.package_id='$package_id' ORDER BY u.created_at DESC
                  ";
        $this->_db->setQuery ( $query );
        $result = $this->_db->loadObjectList();
        return $result;
    }

    public function getTicketHistory($user_id,$package_id){

        $db     = JFactory::getDBO();
        $query  = $db->getQuery(true);
        $query->select("a.*, b.title,b.points,CONCAT(ua.firstname,' ', ua.lastname) AS fullname");
        $query->from( $db->QuoteName('#__member_assigned_tickets','a') );
        $query->join( 'INNER',$db->QuoteName('#__membertickets','b'). ' ON (' . $db->quoteName('a.ticket_id') . ' = ' . $db->quoteName('b.id') . ')' );
        $query->join( 'INNER',$db->QuoteName('#__se_useraccounts','ua'). ' ON (' . $db->quoteName('ua.id') . ' = ' . $db->quoteName('a.user_id') . ')' );
        $query->where("a.user_id='".$user_id."'");
        $query->where("a.package_id='".$package_id."'");
        $query->order('a.id');
        $db->setQuery($query);
        $result= $db->loadObjectList();
        return $result; 
    }


}
