<?php

defined('_JEXEC') or die('Restricted access');
jimport('joomla.application.component.modeladmin');

class SearchEngineModelUsertranscation extends JModelAdmin {

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
        $form = $this->loadForm('com_awardpackage.usertransaction', 'usertransaction', array('control' => 'jform', 'load_data' => $loadData));
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
        $query->from($this->_db->quoteName('#__ap_usergroup'));
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

    public function insert_criteria($data,$package_id) {
   
        $field = $data->field;

        
        switch($field){
            case 'name':
             $query = "INSERT INTO `#__ut_usergroup` (package_id,population,firstname,lastname,field) 
             VALUES ('$package_id','$data->population','$data->firstname','$data->lastname','$data->field')";
            break;           
            case 'email':
             $query = "INSERT INTO `#__ut_usergroup` (package_id,population,email,field) 
             VALUES ('$package_id','$data->population','$data->email','$data->field')";
            break;
            case 'age':
             $query = "INSERT INTO `#__ut_usergroup` (package_id,population,from_age,to_age,field) 
             VALUES ('$package_id','$data->population','$data->from_age','$data->to_age','$data->field')";
            break;
            case 'gender':
             $query = "INSERT INTO `#__ut_usergroup` (package_id,population,gender,field) 
             VALUES ('$package_id','$data->population','$data->gender','$data->field')";
            break;
            case 'location':
            $query = "INSERT INTO `#__ut_usergroup` (package_id,population,street,city,state,post_code,country,field) 
             VALUES ('$package_id','$data->population','$data->street','$data->city',".$this->_db->Quote($data->state).",'$data->post_code','$data->country','$data->field')";
            break;    
        }
        $this->_db->setQuery($query);
        $this->_db->query();                 
    }

    public function update_criteria($data,$package_id,$criteria_id) {
        
        $field = $data->field;
        switch($field){
            case 'name':
             $query = "  UPDATE `#__ut_usergroup` SET 
                         package_id= '$package_id',
                         population='$data->population',
                         firstname='$data->firstname',
                         lastname= '$data->lastname',
                         field= '$data->field'";             
            break; 

            case 'email':
             $query = "  UPDATE `#__ut_usergroup` SET 
                         package_id= '$package_id',
                         population='$data->population',
                         email='$data->email',                        
                         field= '$data->field'";               
            break;

            case 'age':
              $query = "  UPDATE `#__ut_usergroup` SET 
                         package_id= '$package_id',
                         population='$data->population',
                         from_age='$data->from_age', 
                         to_age='$data->to_age',                        
                         field= '$data->field'";              
            break;

            case 'gender':
                $query = "  UPDATE `#__ut_usergroup` SET 
                         package_id= '$package_id',
                         population='$data->population',
                         gender='$data->gender',                                             
                         field= '$data->field'";     
            
            break;

            case 'location':
             $query = "  UPDATE `#__ut_usergroup` SET 
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
        $query->from($this->_db->quoteName('#__ut_usergroup'));
        $query->where($this->_db->quoteName('package_id') . "='" . $package_id . "'");
        $query->where($this->_db->quoteName('field') . "='" . $field . "'");
        $this->_db->setQuery($query);
        $fields = $this->_db->loadObjectList();
        return $fields;
    }

    public function getCriteriaById($criteria_id){
        $query = $this->_db->getQuery(true);
        $query->select('*');
        $query->from($this->_db->quoteName('#__ut_usergroup'));
        $query->where($this->_db->quoteName('criteria_id') . "='" . $criteria_id . "'");          
        $this->_db->setQuery($query);
        return $this->_db->loadObject();

    }

    public function delete($criteria_id,$package_id){
        $query = "  DELETE FROM  `#__ut_usergroup` WHERE criteria_id= '$criteria_id' AND package_id='$package_id'";  
        $this->_db->setQuery($query); 
        $this->_db->query(); 
        $this->serach($package_id);

    }

    public function serach($package_id){
        $query = $this->_db->getQuery(true);
        $query->select('*');
        $query->from($this->_db->quoteName('#__ut_usergroup'));
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
                       /* if(!empty($criteria->post_code)){
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
        $query = "SELECT id FROM #__ap_useraccounts";

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
                        $_query[]= 'LOWER('.$this->_db->quoteName('state') .")=".strtolower($loc[0]);
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

        if( $result){

            foreach ($result as $key => $value) {
                $users_id[] = (int)  $value->id;
            }

            $session =& JFactory::getSession();
            $session->set( 'serached_user', $users_id );
      
        }


        return $users_id ;

    }

    public function getAdminsWithdarwHistory($criteria, $package_id, $filter_start, $filter_end, $email_name, $limitstart, $limit, $status){
        $criteria = $criteria? $criteria: JRequest::getVar('criteria'); 

        if(isset($criteria)){                        
            $serached_user = $this->serach( $package_id);

            if( $serached_user ){

                $query = "
                SELECT fh.*, fu.*, CONCAT(a.`firstname`, ' ', a.`lastname`) AS NAME, a.`firstname` AS firstname, a.`lastname` AS lastname
                FROM #__funding_user fu 
                INNER JOIN #__funding_history fh ON fh.`funding_id` = fu.`funding_id`
                INNER JOIN `#__ap_useraccounts` a ON a.`id` = fu.`user_id`
                WHERE fu.`user_id` IN(".implode(',',$serached_user).") AND fu.`package_id` = '".$package_id."' ";

                if($filter_start && $filter_end){
                 $query .=" AND fh.`created_date` >= '".$filter_start."' AND fh.`created_date` <='".$filter_end."' ";
                }

                if(!empty($email_name)){
                    $query .= " AND (a.`email` = '$email_name' OR a.`state` = '$email_name')";  
                }
                $query .= " AND fh.`transaction_type` = 'WITHDRAW' AND fh.`status` = '".$status."' ORDER BY fh.created_date DESC";    


                $this->_db->setQuery($query);        
                $results = $this->_db->loadObjectList();
                $this->total = count( $results );

                $query .=" LIMIT ".$limitstart.", ".$limit." ";             
        
   
                //echo str_replace('#__', 'raj_', $query);
                $this->_db->setQuery($query);        
                $results = $this->_db->loadObjectList();
                
                if($results){
                    $results = array_reverse($results);
                    $total=0;
                    foreach ($results  as $key => $result) {
                        $total += $result->amount;                                                 
                        $result->total = $total;
                    }
                    $results = array_reverse($results);
                }

                return $results;

                //var_dump($result);
            }
        }

    }

    public function getAdminsTotalHistory($criteria, $package_id, $filter_start, $filter_end, $status){
        $criteria = $criteria? $criteria: JRequest::getVar('criteria');        
        if(isset($criteria)){           
            $data = array();
            $grandTotal = 0;

            $serached_user = $this->serach( $package_id);
            

            if( $serached_user ){

                // User prize claimed
                $query = "
                    SELECT fh.*, fu.*, CONCAT(a.`firstname`, ' ', a.`lastname`) AS NAME, a.`firstname` AS firstname, a.`lastname` AS lastname
                    FROM #__funding_user fu 
                    INNER JOIN #__funding_history fh ON fh.`funding_id` = fu.`funding_id`
                    INNER JOIN `#__ap_useraccounts` a ON a.`id` = fu.`user_id`
                    WHERE fu.`user_id` IN(".implode(',',$serached_user).") AND fu.`package_id` = '".$package_id."'"; 
                
                if( !empty($filter_start) && !empty($filter_end)){
                    $query .= " AND fh.`created_date` >= '".$filter_start."' AND fh.`created_date` <='".$filter_end."'";
                }    
                
                $query .= " AND fh.`transaction_type` = 'PRIZE' AND fh.`status` = '".$status."' ORDER BY fh.created_date";          
                               
                $this->_db->setQuery($query);        
                $results = $this->_db->loadObjectList();

                if($results){                   
                    $total=0;
                    $values = new stdClass;
                    $values->description ='(+)User prize claimed total';
                    
                    foreach ($results  as $key => $result) {
                       $total += $result->amount; 
                       $values->funding_last_update = $result->funding_last_update;                                                                                              
                    }
                    $values->total = $total ;                   
                    $grandTotal = $total* -1; 
                    $values->grand_total = $grandTotal;
                    $data[] = $values;
                }

                // Award Symbol Purchased
                $query = "
                    SELECT fh.*, fu.*, CONCAT(a.`firstname`, ' ', a.`lastname`) AS NAME, a.`firstname` AS firstname, a.`lastname` AS lastname
                    FROM #__funding_user fu 
                    INNER JOIN #__funding_history fh ON fh.`funding_id` = fu.`funding_id`
                    INNER JOIN `#__ap_useraccounts` a ON a.`id` = fu.`user_id`
                    WHERE fu.`user_id` IN(".implode(',',$serached_user).") AND fu.`package_id` = '".$package_id."' ";
                    if( !empty($filter_start) && !empty($filter_end)){
                        $query .= " AND fh.`created_date` >= '".$filter_start."' AND fh.`created_date` <='".$filter_end."'";
                    }   
                    $query .=" AND fh.`transaction_type` = 'SYMOBL' AND fh.`status` = '".$status."' ORDER BY fh.created_date";          
                               
                $this->_db->setQuery($query);        
                $results = $this->_db->loadObjectList();

                if($results){                   
                    $total=0;
                    $values = new stdClass;
                    $values->description ='(+)User Award symbol purchased total';
                    
                    foreach ($results  as $key => $result) {
                       $total += $result->amount;  
                       $values->funding_last_update = $result->funding_last_update;                                                                                             
                    }
                    $values->total = $total;                   
                    $grandTotal += $total;
                    $values->grand_total = $grandTotal;
                    $data[] = $values;
                }

                // Shopping Credit (Business Only)
                $query = "
                    SELECT fh.*, fu.*, CONCAT(a.`firstname`, ' ', a.`lastname`) AS NAME, a.`firstname` AS firstname, a.`lastname` AS lastname
                    FROM #__funding_user fu 
                    INNER JOIN #__funding_history fh ON fh.`funding_id` = fu.`funding_id`
                    INNER JOIN `#__ap_useraccounts` a ON a.`id` = fu.`user_id`
                    WHERE fu.`user_id` IN(".implode(',',$serached_user).") AND fu.`package_id` = '".$package_id."'";
                    if( !empty($filter_start) && !empty($filter_end)){
                        $query .= " AND fh.`created_date` >= '".$filter_start."' AND fh.`created_date` <='".$filter_end."'";
                    }    
                    $query .=" AND fh.`transaction_type` = 'SYMOBL_SOLD' AND fh.`status` = '".$status."' ORDER BY fh.created_date";          
                               
                $this->_db->setQuery($query);        
                $results = $this->_db->loadObjectList();

                 if($results){                   
                    $total=0;
                    $values = new stdClass;
                    $values->description ='(+)User shopping credits total(Business only)';
                    
                    foreach ($results  as $key => $result) {
                       $total += $result->amount;   
                       $values->funding_last_update = $result->funding_last_update;                                                                                            
                    }
                    $values->total = $total;                   
                    $grandTotal -= $total;
                    $values->grand_total = $grandTotal;
                    $data[] = $values;
                }


                // Shopping Credit (Shopping Only)
                $query = "
                    SELECT fh.*, fu.*, CONCAT(a.`firstname`, ' ', a.`lastname`) AS NAME, a.`firstname` AS firstname, a.`lastname` AS lastname
                    FROM #__funding_user fu 
                    INNER JOIN #__funding_history fh ON fh.`funding_id` = fu.`funding_id`
                    INNER JOIN `#__ap_useraccounts` a ON a.`id` = fu.`user_id`
                    WHERE fu.`user_id` IN(".implode(',',$serached_user).") AND fu.`package_id` = '".$package_id."'";
                    if( !empty($filter_start) && !empty($filter_end)){
                        $query .= " AND fh.`created_date` >= '".$filter_start."' AND fh.`created_date` <='".$filter_end."'";
                    }    
                    $query .=" AND fh.`transaction_type` = 'REFUND' AND fh.`status` = '".$status."' ORDER BY fh.created_date";          
                               
                $this->_db->setQuery($query);        
                $results = $this->_db->loadObjectList();

                if($results){                   
                    $total=0;
                    $values = new stdClass;
                    $values->description ='(+)User shopping credits total(Personal only)';
                   
                    foreach ($results  as $key => $result) {
                       $total += $result->amount;  
                        $values->funding_last_update = $result->funding_last_update;                                                                                             
                    }
                    $values->total = $total;
                    $grandTotal += $total;
                    $values->grand_total = $grandTotal;
                    $data[] = $values;
                }

                // Fee
                 $query = "
                    SELECT fh.*, fu.*, CONCAT(a.`firstname`, ' ', a.`lastname`) AS NAME, a.`firstname` AS firstname, a.`lastname` AS lastname
                    FROM #__funding_user fu 
                    INNER JOIN #__funding_history fh ON fh.`funding_id` = fu.`funding_id`
                    INNER JOIN `#__ap_useraccounts` a ON a.`id` = fu.`user_id`
                    WHERE fu.`user_id` IN(".implode(',',$serached_user).") AND fu.`package_id` = '".$package_id."'";
                    if( !empty($filter_start) && !empty($filter_end)){
                        $query .= " AND fh.`created_date` >= '".$filter_start."' AND fh.`created_date` <='".$filter_end."'";
                    }    
                    $query .=" AND fh.`transaction_type` = 'FEE' AND fh.`status` = '".$status."' ORDER BY fh.created_date";          
                               
                $this->_db->setQuery($query);        
                $results = $this->_db->loadObjectList();

                if($results){                   
                    $total=0;
                    $values = new stdClass;
                    $values->description ='(+)User Fee ';
                    
                    foreach ($results  as $key => $result) {
                       $total += $result->amount;           
                       $values->funding_last_update = $result->funding_last_update;                                                                                    
                    }
                    $values->total = $total;
                    $grandTotal += $total;
                    $values->grand_total = $grandTotal;
                    $data[] = $values;
                }

                // User Net Donation
                 $query = "
                    SELECT fh.*, fu.*, CONCAT(a.`firstname`, ' ', a.`lastname`) AS NAME, a.`firstname` AS firstname, a.`lastname` AS lastname
                    FROM #__funding_user fu 
                    INNER JOIN #__funding_history fh ON fh.`funding_id` = fu.`funding_id`
                    INNER JOIN `#__ap_useraccounts` a ON a.`id` = fu.`user_id`
                    WHERE fu.`user_id` IN(".implode(',',$serached_user).") AND fu.`package_id` = '".$package_id."'";
                    if( !empty($filter_start) && !empty($filter_end)){
                        $query .= " AND fh.`created_date` >= '".$filter_start."' AND fh.`created_date` <='".$filter_end."'";
                    }    
                    $query .=" AND fh.`transaction_type` = 'DONATION' AND fh.`status` = '".$status."' ORDER BY fh.created_date";          
                               
                $this->_db->setQuery($query);        
                $results = $this->_db->loadObjectList();

                if($results){                   
                    $total=0;
                    $values = new stdClass;
                    $values->description ='(+)User net donation total';                
                    foreach ($results  as $key => $result) {
                       $total += $result->amount;                                                                                               
                        $values->funding_last_update = $result->funding_last_update;
                    }
                    $values->total = $total;
                    $grandTotal += $total;
                    $values->grand_total = $grandTotal;
                    $data[] = $values;
                }


                // User Funds
                $query = "
                
                SELECT fh.*, fu.*, CONCAT(a.`firstname`, ' ', a.`lastname`) AS NAME, a.`firstname` AS firstname, a.`lastname` AS lastname
                FROM #__funding_user fu 
                INNER JOIN #__funding_history fh ON fh.`funding_id` = fu.`funding_id`
                INNER JOIN `#__ap_useraccounts` a ON a.`id` = fu.`user_id`
                WHERE fu.`user_id` IN(".implode(',',$serached_user).") AND fu.`package_id` = '".$package_id."' 
                AND (fh.`transaction_type` = 'FUNDING'  OR fh.`transaction_type` = 'WITHDRAW')";
                 if( !empty($filter_start) && !empty($filter_end)){
                    $query .= " AND fh.`created_date` >= '".$filter_start."' AND fh.`created_date` <='".$filter_end."'";
                }    
                $query .=" AND fh.`status` = '".$status."' ORDER BY fh.created_date";          
                
                //echo str_replace('#__', 'raj_', $query);
                $this->_db->setQuery($query);        
                $results = $this->_db->loadObjectList();

                if($results){                   
                    $total=0;
                    $values = new stdClass;
                    $values->description ='(+)User funds total';
                    
                    foreach ($results  as $key => $result) {
                        if( $result->transaction_type == 'FUNDING'){
                           $total += $result->amount;                                                                        
                        }else{
                           $total -= $result->amount;                                                                        
                        } 
                        $values->funding_last_update = $result->funding_last_update;                   
                    }
                    $values->total = $total;
                    $grandTotal += $total;
                    $values->grand_total = $grandTotal;                   
                    $data[] = $values;
                }


                $values = new stdClass;
                $values->description ='(+)Admin funds total';
                $values->funding_last_update = '';                
                $values->total = '-';                
                $values->grand_total = $total;
                $data[] = $values;

                return array_reverse($data);
            }
        }
    }

    public function getuserFundingHistory($criteria, $package_id, $filter_start, $filter_end, $limitstart, $limit, $type, $status){
        $criteria = $criteria? $criteria: JRequest::getVar('criteria');        
        if(isset($criteria)){            
            $data = array();
            $grandTotal = 0;
            $results= null;
            $serached_user = $this->serach( $package_id);

            if( $serached_user ){
                switch ($type) {
                    case 'FUNDS':
                        $query = "
                        SELECT fh.*, fu.*, CONCAT(a.`firstname`, ' ', a.`lastname`) AS NAME, a.`firstname` AS firstname, a.`lastname` AS lastname
                        FROM #__funding_user fu 
                        INNER JOIN #__funding_history fh ON fh.`funding_id` = fu.`funding_id`
                        INNER JOIN `#__ap_useraccounts` a ON a.`id` = fu.`user_id`
                        WHERE fu.`user_id` IN(".implode(',',$serached_user).") AND fu.`package_id` = '".$package_id."'"; 

                        if( !empty($filter_start) && !empty($filter_end)){
                            $query .= " AND fh.`created_date` >= '".$filter_start."' AND fh.`created_date` <='".$filter_end."'";
                        }    

                        $query .= " AND (fh.`transaction_type` = 'FUNDING' OR fh.`transaction_type` = 'WITHDRAW' OR fh.`transaction_type` = 'QUIZ' OR fh.`transaction_type` = 'SURVEY') 
                        AND fh.`status` = '".$status."' ORDER BY fh.created_date DESC";  

                        $this->_db->setQuery($query);        
                        $results = $this->_db->loadObjectList();
                        $this->total = count( $results );

                        $query .=" LIMIT ".$limitstart.", ".$limit." ";            
                        //echo str_replace('#__', 'raj_', $query);
                        $this->_db->setQuery($query);        
                        $results = $this->_db->loadObjectList();

                        if($results){
                            $results = array_reverse($results);
                            $total=0;
                            foreach ($results  as $key => $result) {
                                if($result->transaction_type =='FUNDING'){
                                    $total += $result->amount;    
                                }else{
                                    $total -= $result->amount;    
                                }                                                                            
                                $result->total = $total;
                            }
                            $results = array_reverse($results);
                        }
                        break;                    
                }             
            }
        }

        return $results;
    }

    public function getuserDonationHistory($criteria, $package_id, $filter_start, $filter_end, $limitstart, $limit, $type, $status){
         $criteria = $criteria? $criteria: JRequest::getVar('criteria');        
        if(isset($criteria)){            
            $data = array();
            $grandTotal = 0;
            $results= null;
            $serached_user = $this->serach( $package_id);

            if( $serached_user ){
                switch ($type) {
                    case 'FUNDS':
                        $query = "
                        SELECT fh.*, fu.*, CONCAT(a.`firstname`, ' ', a.`lastname`) AS NAME, a.`firstname` AS firstname, a.`lastname` AS lastname
                        FROM #__funding_user fu 
                        INNER JOIN #__funding_history fh ON fh.`funding_id` = fu.`funding_id`
                        INNER JOIN `#__ap_useraccounts` a ON a.`id` = fu.`user_id`
                        WHERE fu.`user_id` IN(".implode(',',$serached_user).") AND fu.`package_id` = '".$package_id."'"; 

                        if( !empty($filter_start) && !empty($filter_end)){
                            $query .= " AND fh.`created_date` >= '".$filter_start."' AND fh.`created_date` <='".$filter_end."'";
                        }    

                        $query .= " AND fh.`transaction_type` = 'DONATION' 
                        AND fh.`status` = '".$status."' ORDER BY fh.created_date DESC";  

                        $this->_db->setQuery($query);        
                        $results = $this->_db->loadObjectList();
                        $this->total = count( $results );

                        $query .=" LIMIT ".$limitstart.", ".$limit." ";            
                        //echo str_replace('#__', 'raj_', $query);
                        $this->_db->setQuery($query);        
                        $results = $this->_db->loadObjectList();

                        if($results){
                            $results = array_reverse($results);
                            $total=0;
                            foreach ($results  as $key => $result) {
                                $total += $result->amount;    
                                $result->total = $total;
                            }
                            $results = array_reverse($results);
                        }
                        break;                    
                }             
            }
        }

        return $results;
    }

    public function getuserSymbolHistory($criteria, $package_id, $filter_start, $filter_end, $limitstart, $limit, $type, $status){
         $criteria = $criteria? $criteria: JRequest::getVar('criteria');        
        if(isset($criteria)){            
            $data = array();
            $grandTotal = 0;
            $results= null;
            $serached_user = $this->serach( $package_id);

            if( $serached_user ){
                switch ($type) {
                    case 'FUNDS':
                        $query = "
                        SELECT fh.*, fu.*, CONCAT(a.`firstname`, ' ', a.`lastname`) AS NAME, a.`firstname` AS firstname, a.`lastname` AS lastname
                        FROM #__funding_user fu 
                        INNER JOIN #__funding_history fh ON fh.`funding_id` = fu.`funding_id`
                        INNER JOIN `#__ap_useraccounts` a ON a.`id` = fu.`user_id`
                        WHERE fu.`user_id` IN(".implode(',',$serached_user).") AND fu.`package_id` = '".$package_id."'"; 

                        if( !empty($filter_start) && !empty($filter_end)){
                            $query .= " AND fh.`created_date` >= '".$filter_start."' AND fh.`created_date` <='".$filter_end."'";
                        }    

                        $query .= " AND fh.`transaction_type` = 'SYMBOL' 
                        AND fh.`status` = '".$status."' ORDER BY fh.created_date DESC";  

                        $this->_db->setQuery($query);        
                        $results = $this->_db->loadObjectList();
                        $this->total = count( $results );

                        $query .=" LIMIT ".$limitstart.", ".$limit." ";            
                        //echo str_replace('#__', 'raj_', $query);
                        $this->_db->setQuery($query);        
                        $results = $this->_db->loadObjectList();

                        if($results){
                            $results = array_reverse($results);
                            $total=0;
                            foreach ($results  as $key => $result) {
                                $total += $result->amount;    
                                $result->total = $total;
                            }
                            $results = array_reverse($results);
                        }
                        break;                    
                }             
            }
        }

        return $results;
    }

    public function getuserShoppingCreditHistory($criteria, $package_id, $filter_start, $filter_end, $limitstart, $limit, $type, $status){
         $criteria = $criteria? $criteria: JRequest::getVar('criteria');        
        if(isset($criteria)){            
            $data = array();
            $grandTotal = 0;
            $results= null;
            $serached_user = $this->serach( $package_id);

            if( $serached_user ){
               
                        $query = "
                        SELECT fh.*, fu.*, CONCAT(a.`firstname`, ' ', a.`lastname`) AS NAME, a.`firstname` AS firstname, a.`lastname` AS lastname
                        FROM #__funding_user fu 
                        INNER JOIN #__funding_history fh ON fh.`funding_id` = fu.`funding_id`
                        INNER JOIN `#__ap_useraccounts` a ON a.`id` = fu.`user_id`
                        WHERE fu.`user_id` IN(".implode(',',$serached_user).") AND fu.`package_id` = '".$package_id."'"; 

                        if( !empty($filter_start) && !empty($filter_end)){
                            $query .= " AND fh.`created_date` >= '".$filter_start."' AND fh.`created_date` <='".$filter_end."'";
                        }
                        
                        if($type == 1 || $type=='')
                            $query .= " AND (fh.`transaction_type` = 'REFUND' OR fh.`method` = 'SHOPPING_CREDIT') ";
                        else if($type == 2)
                            $query .= " AND ( (fh.`transaction_type` = 'SYMBOL_SOLD'  AND fh.`credit_to`=1 ) OR fh.`method` = 'SHOPPING_CREDIT_BUSINESS') ";


                        $query .= " AND fh.`status` = '".$status."' ORDER BY fh.created_date DESC";  
    
                        $this->_db->setQuery($query);        
                        $results = $this->_db->loadObjectList();
                        $this->total = count( $results );

                        $query .=" LIMIT ".$limitstart.", ".$limit." ";            
                        //echo str_replace('#__', 'raj_', $query);
                        $this->_db->setQuery($query);        
                        $results = $this->_db->loadObjectList();

                        if($results){
                            $results = array_reverse($results);
                            $total=0;
                            foreach ($results  as $key => $result) {
                                /*$total += $result->credit;    
                                $result->total = $total;*/
                                if( $result->transaction_type == 'REFUND'){
                                    $total += $result->amount;                                                 
                                }else{
                                    $total -= $result->amount;                         
                                }
                                $result->total = $total;
                            }
                            $results = array_reverse($results);
                        }
                                   
                             
            }
        }

        return $results;
    }

    public function getuserpPrizeClaimHistory($criteria, $package_id, $filter_start, $filter_end, $limitstart, $limit, $type, $status){
         $criteria = $criteria? $criteria: JRequest::getVar('criteria');        
        if(isset($criteria)){            
            $data = array();
            $grandTotal = 0;
            $results= null;
            $serached_user = $this->serach( $package_id);

            if( $serached_user ){
                switch ($type) {
                    case 'FUNDS':
                        $query = "
                        SELECT fh.*, fu.*, CONCAT(a.`firstname`, ' ', a.`lastname`) AS NAME, a.`firstname` AS firstname, a.`lastname` AS lastname
                        FROM #__funding_user fu 
                        INNER JOIN #__funding_history fh ON fh.`funding_id` = fu.`funding_id`
                        INNER JOIN `#__ap_useraccounts` a ON a.`id` = fu.`user_id`
                        WHERE fu.`user_id` IN(".implode(',',$serached_user).") AND fu.`package_id` = '".$package_id."'"; 

                        if( !empty($filter_start) && !empty($filter_end)){
                            $query .= " AND fh.`created_date` >= '".$filter_start."' AND fh.`created_date` <='".$filter_end."'";
                        }    

                        $query .= " AND fh.`transaction_type` = 'PRIZE' 
                        AND fh.`status` = '".$status."' ORDER BY fh.created_date DESC";  

                        $this->_db->setQuery($query);        
                        $results = $this->_db->loadObjectList();
                        $this->total = count( $results );

                        $query .=" LIMIT ".$limitstart.", ".$limit." ";            
                        //echo str_replace('#__', 'raj_', $query);
                        $this->_db->setQuery($query);        
                        $results = $this->_db->loadObjectList();

                        if($results){
                            $results = array_reverse($results);
                            $total=0;
                            foreach ($results  as $key => $result) {
                                $total += $result->amount;    
                                $result->total = $total;
                            }
                            $results = array_reverse($results);
                        }
                        break;                    
                }             
            }
        }

        return $results;
    }

    public function getuserAuctionHistory($criteria, $package_id, $filter_start, $filter_end, $limitstart, $limit, $type, $status){
         $criteria = $criteria? $criteria: JRequest::getVar('criteria');        
        if(isset($criteria)){            
            $data = array();
            $grandTotal = 0;
            $results= null;
            $serached_user = $this->serach( $package_id);

            if( $serached_user ){
                switch ($type) {
                    case 'FUNDS':
                        $query = "
                        SELECT fh.*, fu.*, CONCAT(a.`firstname`, ' ', a.`lastname`) AS NAME, a.`firstname` AS firstname, a.`lastname` AS lastname
                        FROM #__funding_user fu 
                        INNER JOIN #__funding_history fh ON fh.`funding_id` = fu.`funding_id`
                        INNER JOIN `#__ap_useraccounts` a ON a.`id` = fu.`user_id`
                        WHERE fu.`user_id` IN(".implode(',',$serached_user).") AND fu.`package_id` = '".$package_id."'"; 

                        if( !empty($filter_start) && !empty($filter_end)){
                            $query .= " AND fh.`created_date` >= '".$filter_start."' AND fh.`created_date` <='".$filter_end."'";
                        }    

                        $query .= " AND fh.`txn_via` = '1' 
                        AND fh.`status` = '".$status."' ORDER BY fh.created_date DESC";  

                        $this->_db->setQuery($query);        
                        $results = $this->_db->loadObjectList();
                        $this->total = count( $results );

                        $query .=" LIMIT ".$limitstart.", ".$limit." ";            
                        //echo str_replace('#__', 'raj_', $query);
                        $this->_db->setQuery($query);        
                        $results = $this->_db->loadObjectList();

                        if($results){
                            $results = array_reverse($results);
                            $total=0;
                            foreach ($results  as $key => $result) {
                                $total += $result->amount;    
                                $result->total = $total;
                            }
                            $results = array_reverse($results);
                        }
                        break;                    
                }             
            }
        }

        return $results;
    }

    public function updateTransaction($transactions_id, $status){
        $query = "  UPDATE `#__funding_history` SET  status= '$status'
                    WHERE funding_history_id IN(". implode(',', $transactions_id).")";                    
        $this->_db->setQuery($query);
        return $this->_db->query();

        

    }

    public function getAdminsUserFundsHistory($criteria, $package_id, $filter_start, $filter_end, $limitstart, $limit, $status){
         $criteria = $criteria? $criteria: JRequest::getVar('criteria');        
        if(isset($criteria)){
           /* $session =& JFactory::getSession();
            $serached_user =  $session->get( 'serached_user', array() );   */         
            $user= array();
            $data = array();
            $grandTotal = 0;
            $results= null;
            $serached_user = $this->serach( $package_id);

            if( $serached_user ){
                 $query = "
                        SELECT fh.*, fu.*, CONCAT(a.`firstname`, ' ', a.`lastname`) AS NAME, a.`firstname` AS firstname, a.`lastname` AS lastname, a.id as user
                        FROM #__funding_user fu
                        INNER JOIN #__funding_history fh ON fh.`funding_id` = fu.`funding_id`
                        INNER JOIN `#__ap_useraccounts` a ON a.`id` = fu.`user_id`
                        WHERE fu.`user_id` IN(".implode(',',$serached_user).") AND fu.`package_id` = '".$package_id."'"; 

                        if( !empty($filter_start) && !empty($filter_end)){
                            $query .= " AND fh.`created_date` >= '".$filter_start."' AND fh.`created_date` <='".$filter_end."'";
                        }    

                        $query .= " AND fh.`transaction_type` != 'FEE' 
                        AND fh.`status` = '".$status."' ORDER BY fh.created_date DESC"; 

                        //echo str_replace('#__', 'raj_', $query);

                        $this->_db->setQuery($query);        
                        $results = $this->_db->loadObjectList();
                       

                        if($results){
                            $results = array_reverse($results);
                            $total=0;                                                       
                            foreach ($results  as $key => $result) {

                                if(!in_array($result->user, $user))
                                {
                                    $user[] = $result->user;
                                    $data[$result->user] = array(
                                        'name' =>$result->NAME,
                                        'created' =>$result->funding_last_update,
                                        'funds'=>0,
                                        'spend_funds'=>0,
                                        'shoppig_credit'=>0,
                                        'spend_shopping_crdit'=>0,                                                              
                                        'shopping_credit_business'=>0,
                                        'spend_shopping_crdit_business' =>0,
                                        'withdarw'=>0, 
                                        'total'=>0,                                        
                                        );
                                }
                                switch ($result->transaction_type) {
                                    case 'FUNDING':
                                         if($result->method =='SHOPPING_CREDIT'){
                                            $data[$result->user]['spend_shopping_crdit']= $result->amount;
                                         }else if($result->method =='SHOPPING_CREDIT_BUSINESS'){
                                            $data[$result->user]['spend_shopping_crdit_business']= $result->amount;
                                         }else{
                                            $data[$result->user]['funds'] += $result->amount;
                                         }
                                         //$data[$result->user]['funds'] += $result->amount;
                                        break;

                                    case 'DONATION':
                                         if($result->method =='SHOPPING_CREDIT'){
                                            $data[$result->user]['spend_shopping_crdit']= $result->amount;
                                         }                                        
                                        break;

                                    case 'QUIZ':
                                         $data[$result->user]['spend_funds'] += $result->amount;
                                        break;

                                    case 'SURVERY':
                                         $data[$result->user]['spend_funds'] += $result->amount;
                                        break;
                                    
                                    case 'REFUND':
                                        $data[$result->user]['shoppig_credit'] += $result->amount;
                                        break;

                                    case 'SYMOBL_SOLD':                                        
                                        $data[$result->user]['shopping_credit_business'] += $result->amount;
                                        break;

                                    case 'WITHDRAW':
                                        $data[$result->user]['withdarw'] += $result->amount;
                                        break;

                                     case 'SYMBOL':
                                         if($result->method =='SHOPPING_CREDIT'){
                                            $data[$result->user]['spend_shopping_crdit']= $result->amount;
                                         } 

                                         if($result->method =='SHOPPING_CREDIT_BUSINESS'){
                                            $data[$result->user]['spend_shopping_crdit_business']= $result->amount;
                                         }                                         
                                        break;    
                                }                                                                
                            }
                            
                            $results = array_reverse($data);
                            
                            foreach ($results as $key => &$result) {
                               $result['funds'] =  $result['funds'] - $result['withdarw']-$result['spend_funds'];
                               $result['shoppig_credit'] = $result['shoppig_credit']- $result['spend_shopping_crdit'];
                               $result['shopping_credit_business'] = $result['shopping_credit_business']- $result['spend_shopping_crdit_business']; 
                               $result['total'] =  (float)$result['funds'] +  (float)$result['shoppig_credit']+ (float)$result['shopping_credit_business'] + (float)$result['spend_shopping_crdit']+ (float)$result['spend_shopping_crdit_business'];                           
                            }

                            $results = array_slice($results, $limitstart, $limit);

                        }
                }
            
        }
        $this->total = count( $user );
        return $results;
    }

     public function getAdminsUserDonationHistory($criteria, $package_id, $filter_start, $filter_end, $limitstart, $limit, $status){
         $criteria = $criteria? $criteria: JRequest::getVar('criteria');        
        if(isset($criteria)){
            $user= array();
            $data = array();
            $grandTotal = 0;
            $results= null;
            $serached_user = $this->serach( $package_id);
            if( $serached_user ){
                 $query = "
                SELECT fh.*, fu.*, CONCAT(a.`firstname`, ' ', a.`lastname`) AS NAME, a.`firstname` AS firstname, a.`lastname` AS lastname, a.id as user
                FROM #__funding_user fu
                INNER JOIN #__funding_history fh ON fh.`funding_id` = fu.`funding_id`
                INNER JOIN `#__ap_useraccounts` a ON a.`id` = fu.`user_id`
                WHERE fu.`user_id` IN(".implode(',',$serached_user).") AND fu.`package_id` = '".$package_id."'"; 

                if( !empty($filter_start) && !empty($filter_end)){
                    $query .= " AND fh.`created_date` >= '".$filter_start."' AND fh.`created_date` <='".$filter_end."'";
                }    

                $query .= " AND fh.`transaction_type` = 'DONATION' 
                AND fh.`status` = '".$status."' ORDER BY fh.created_date DESC"; 

                //echo str_replace('#__', 'raj_', $query);


                $this->_db->setQuery($query);        
                $results = $this->_db->loadObjectList();

                if($results){
                    $results = array_reverse($results);
                    $total=0;                                                       
                    foreach ($results  as $key => $result) {

                        if(!in_array($result->user, $user))
                        {
                            $user[] = $result->user;
                            $data[$result->user] = array(
                                'name' =>$result->NAME,
                                'created' =>$result->funding_last_update,
                                'cash_donation'=>0,                                     
                                'shoppig_credit_donation'=>0,
                                'shoppig_credit_business_donation'=>0,                                        
                                'total'=>0,                                        
                                );
                        }
                       
                         if($result->method =='PAYPAL'){
                            $data[$result->user]['cash_donation']= $result->amount;
                         }
                         if($result->method =='SHOPPING_CREDIT'){
                            $data[$result->user]['shoppig_credit_donation']= $result->amount;
                         }
                         if($result->method =='SHOPPING_CREDIT_BUSINESS'){
                            $data[$result->user]['shoppig_credit_business_donation']= $result->amount;
                         }
                              
                    }

                    $results = array_reverse($data); 
                    foreach ($results as $key => &$result) {
                        $result['total'] =  (float)$result['cash_donation'] +  (float)$result['shoppig_credit_donation']+ 
                        (float)$result['shoppig_credit_business_donation'];                         
                    }

                    $results = array_slice($results, $limitstart, $limit);

                }
            }

        }
           
        $this->total = count( $user );
        return $results;
           
    }

    public function getAdminsUserSymbolHistory($criteria, $package_id, $filter_start, $filter_end, $limitstart, $limit, $status){
         $criteria = $criteria? $criteria: JRequest::getVar('criteria');        
        if(isset($criteria)){
            $user= array();
            $data = array();
            $grandTotal = 0;
            $results= null;
            $serached_user = $this->serach( $package_id);
            if( $serached_user ){
                 $query = "
                SELECT fh.*, fu.*, CONCAT(a.`firstname`, ' ', a.`lastname`) AS NAME, a.`firstname` AS firstname, a.`lastname` AS lastname, a.id as user
                FROM #__funding_user fu
                INNER JOIN #__funding_history fh ON fh.`funding_id` = fu.`funding_id`
                INNER JOIN `#__ap_useraccounts` a ON a.`id` = fu.`user_id`
                WHERE fu.`user_id` IN(".implode(',',$serached_user).") AND fu.`package_id` = '".$package_id."'"; 

                if( !empty($filter_start) && !empty($filter_end)){
                    $query .= " AND fh.`created_date` >= '".$filter_start."' AND fh.`created_date` <='".$filter_end."'";
                }    

                $query .= " AND fh.`transaction_type` = 'SYMBOL' 
                AND fh.`status` = '".$status."' ORDER BY fh.created_date DESC"; 

                //echo str_replace('#__', 'raj_', $query);


                $this->_db->setQuery($query);        
                $results = $this->_db->loadObjectList();

                if($results){
                    $results = array_reverse($results);
                    $total=0;                                                       
                    foreach ($results  as $key => $result) {

                        if(!in_array($result->user, $user))
                        {
                            $user[] = $result->user;
                            $data[$result->user] = array(
                                'name' =>$result->NAME,
                                'created' =>$result->funding_last_update,
                                'cash_donation'=>0,                                     
                                'shoppig_credit_donation'=>0,
                                'shoppig_credit_business_donation'=>0,                                        
                                'total'=>0,                                        
                                );
                        }
                       
                         if($result->method =='PAYPAL'){
                            $data[$result->user]['cash_purchase']= $result->amount;
                         }
                          if($result->method =='SHOPPING_CREDIT'){
                            $data[$result->user]['shoppig_credit_purchase']= $result->amount;
                         }
                          if($result->method =='SHOPPING_CREDIT_BUSINESS'){
                            $data[$result->user]['shoppig_credit_business_purchase']= $result->amount;
                         }
                              
                    }

                    $results = array_reverse($data); 
                    foreach ($results as $key => &$result) {
                        $result['total'] =  (float)$result['cash_purchase'] +  (float)$result['shoppig_credit_purchase']+ 
                        (float)$result['shoppig_credit_business_purchase'];                         
                    }

                    $results = array_slice($results, $limitstart, $limit);

                }
            }

        }
           
        $this->total = count( $user );
        return $results;
           
    }

    public function getAdminsUserShoppingCreditHistory($criteria, $package_id, $filter_start, $filter_end, $limitstart, $limit, $status){
         $criteria = $criteria? $criteria: JRequest::getVar('criteria');        
        if(isset($criteria)){
            $user= array();
            $data = array();
            $grandTotal = 0;
            $results= null;
            $serached_user = $this->serach( $package_id);
            if( $serached_user ){
                 $query = "
                SELECT fh.*, fu.*, CONCAT(a.`firstname`, ' ', a.`lastname`) AS NAME, a.`firstname` AS firstname, a.`lastname` AS lastname, a.id as user
                FROM #__funding_user fu
                INNER JOIN #__funding_history fh ON fh.`funding_id` = fu.`funding_id`
                INNER JOIN `#__ap_useraccounts` a ON a.`id` = fu.`user_id`
                WHERE fu.`user_id` IN(".implode(',',$serached_user).") AND fu.`package_id` = '".$package_id."'"; 

                if( !empty($filter_start) && !empty($filter_end)){
                    $query .= " AND fh.`created_date` >= '".$filter_start."' AND fh.`created_date` <='".$filter_end."'";
                }    

                $query .= " AND (fh.`transaction_type` = 'REFUND' OR fh.`transaction_type` = 'SYMOBL_SOLD' )
                AND fh.`status` = '".$status."' ORDER BY fh.created_date DESC"; 

                //echo str_replace('#__', 'raj_', $query);


                $this->_db->setQuery($query);        
                $results = $this->_db->loadObjectList();

                if($results){
                    $results = array_reverse($results);
                    $total=0;                                                       
                    foreach ($results  as $key => $result) {

                        if(!in_array($result->user, $user))
                        {
                            $user[] = $result->user;
                            $data[$result->user] = array(
                                'name' =>$result->NAME,
                                'created' =>$result->funding_last_update,
                                'shopping_credit'=>0,                                     
                                'shoppig_credit_business'=>0,
                                'total'=>0,                                        
                                );
                        }
                       
                         if($result->transaction_type =='REFUND'){
                            $data[$result->user]['shopping_credit']= $result->amount;
                         }
                         
                         if($result->transaction_type =='SYMOBL_SOLD'){
                            $data[$result->user]['shoppig_credit_business']= $result->amount;
                         }
                              
                    }

                    $results = array_reverse($data); 
                    foreach ($results as $key => &$result) {
                        $result['total'] =  (float)$result['shopping_credit'] +  (float)$result['shoppig_credit_business'];                         
                    }

                    $results = array_slice($results, $limitstart, $limit);

                }
            }

        }
           
        $this->total = count( $user );
        return $results;
    }

    public function getAdminsUserPrizeClaimHistory($criteria, $package_id, $filter_start, $filter_end, $limitstart, $limit, $status){
        $criteria = $criteria? $criteria: JRequest::getVar('criteria');        
        if(isset($criteria)){
            $user= array();
            $data = array();
            $grandTotal = 0;
            $results= null;
            $serached_user = $this->serach( $package_id);
            if( $serached_user ){
                 $query = "
                SELECT fh.*, fu.*, CONCAT(a.`firstname`, ' ', a.`lastname`) AS NAME, a.`firstname` AS firstname, a.`lastname` AS lastname, a.id as user
                FROM #__funding_user fu
                INNER JOIN #__funding_history fh ON fh.`funding_id` = fu.`funding_id`
                INNER JOIN `#__ap_useraccounts` a ON a.`id` = fu.`user_id`
                WHERE fu.`user_id` IN(".implode(',',$serached_user).") AND fu.`package_id` = '".$package_id."'"; 

                if( !empty($filter_start) && !empty($filter_end)){
                    $query .= " AND fh.`created_date` >= '".$filter_start."' AND fh.`created_date` <='".$filter_end."'";
                }    

                $query .= " AND fh.`transaction_type` = 'PRIZE' 
                AND fh.`status` = '".$status."' ORDER BY fh.created_date DESC"; 

                //echo str_replace('#__', 'raj_', $query);


                $this->_db->setQuery($query);        
                $results = $this->_db->loadObjectList();

                if($results){
                    $results = array_reverse($results);
                    $total=0;                                                       
                    foreach ($results  as $key => $result) {

                        if(!in_array($result->user, $user))
                        {
                            $user[] = $result->user;
                            $data[$result->user] = array(
                                'name' =>$result->NAME,
                                'created' =>$result->funding_last_update,
                                'prize_claim_amount'=>0                                                                                                    
                                );
                        }
                        
                        $data[$result->user]['prize_claim_amount'] += $result->amount;
                         
                    }

                    $results = array_reverse($data);                     
                    $results = array_slice($results, $limitstart, $limit);

                }
            }

        }
           
        $this->total = count( $user );
        return $results;
    }

    public function getAdminsUserAuction($criteria, $package_id, $filter_start, $filter_end, $limitstart, $limit, $status){
        $criteria = $criteria? $criteria: JRequest::getVar('criteria');        
        if(isset($criteria)){
            $user= array();
            $data = array();
            $grandTotal = 0;
            $results= null;
            $serached_user = $this->serach( $package_id);
            if( $serached_user ){
                 $query = "
                SELECT fh.*, fu.*, CONCAT(a.`firstname`, ' ', a.`lastname`) AS NAME, a.`firstname` AS firstname, a.`lastname` AS lastname, a.id as user
                FROM #__funding_user fu
                INNER JOIN #__funding_history fh ON fh.`funding_id` = fu.`funding_id`
                INNER JOIN `#__ap_useraccounts` a ON a.`id` = fu.`user_id`
                WHERE fu.`user_id` IN(".implode(',',$serached_user).") AND fu.`package_id` = '".$package_id."'"; 

                if( !empty($filter_start) && !empty($filter_end)){
                    $query .= " AND fh.`created_date` >= '".$filter_start."' AND fh.`created_date` <='".$filter_end."'";
                }    

                $query .= " AND fh.`txn_via` = '1' 
                AND fh.`status` = '".$status."' ORDER BY fh.created_date DESC"; 

                //echo str_replace('#__', 'raj_', $query);


                $this->_db->setQuery($query);        
                $results = $this->_db->loadObjectList();

                if($results){
                    $results = array_reverse($results);
                    $total=0;                                                       
                    foreach ($results  as $key => $result) {

                        if(!in_array($result->user, $user))
                        {
                            $user[] = $result->user;
                            $data[$result->user] = array(
                                'name' =>$result->NAME,
                                'created' =>$result->funding_last_update,
                                'auction_purchase'=>0,
                                'auction_sold' =>0
                                );
                        }
                        if($result->transaction_type='SYMBOL'){
                            $data[$result->user]['auction_purchase'] += $result->amount;    
                        }
                        if($result->transaction_type='SYMBOL_SOLD'){
                            $data[$result->user]['auction_sold'] += $result->amount;    
                        }
                        
                         
                    }

                    $results = array_reverse($data); 
                    foreach ($results as $key => &$result) {
                        $result['total'] =  (float)$result['auction_purchase'] +  (float)$result['auction_sold'];                         
                    }                     
                    $results = array_slice($results, $limitstart, $limit);

                }
            }

        }
           
        $this->total = count( $user );
        return $results;
    }


    public function getuserTicketHistory($criteria, $package_id, $filter_start, $filter_end, $limitstart, $limit, $status){
         $criteria = $criteria? $criteria: JRequest::getVar('criteria');        
        if(isset($criteria)){            
            $data = array();
            $grandTotal = 0;
            $results= null;
            $serached_user = $this->serach( $package_id);

            if( $serached_user ){
                switch ($status) {
                    case 'PENDING':                      
                        /* $query = "
                        SELECT a.*, CONCAT(b.`firstname`, ' ', b.`lastname`) AS NAME, b.`firstname` AS firstname, b.`lastname` AS lastname,c.points,d.qty
                        FROM #__ticket_history a                        
                        INNER JOIN `#__ap_useraccounts` b ON b.`id` = a.`user_id`
                        INNER JOIN `#__membertickets` c ON c.`title` = a.`ticket`
                        LEFT JOIN `#__member_assigned_tickets` d ON d.`ticket_id` = c.`id`
                        WHERE b.`id` IN(".implode(',',$serached_user).") AND b.`package_id` = '".$package_id."' AND a.status='PENDING'"; */

                         $query = "
                        SELECT a.*, CONCAT(b.`firstname`, ' ', b.`lastname`) AS NAME, b.`firstname` AS firstname, b.`lastname` AS lastname
                        FROM #__ticket_history a                        
                        INNER JOIN `#__ap_useraccounts` b ON b.`id` = a.`user_id`                        
                        WHERE b.`id` IN(".implode(',',$serached_user).") AND b.`package_id` = '".$package_id."' AND a.status='PENDING'"; 

                        $this->_db->setQuery($query);        
                        $results = $this->_db->loadObjectList();
                        $this->total = count( $results );

                        $query .=" LIMIT ".$limitstart.", ".$limit." ";            
                        //echo str_replace('#__', 'raj_', $query);
                        $this->_db->setQuery($query);        
                        $results = $this->_db->loadObjectList();

                        if($results){
                            $results = array_reverse($results);
                            $total=0;
                            foreach ($results  as $key => $result) {
                                $total += $result->amount;    
                                $result->total = $total;
                            }
                            $results = array_reverse($results);
                        }
                        break; 
                    case 'COMPLETED':

                        /*$query = "
                        SELECT a.*, CONCAT(b.`firstname`, ' ', b.`lastname`) AS NAME, b.`firstname` AS firstname, b.`lastname` AS lastname,c.points,d.qty
                        FROM #__ticket_history a                        
                        INNER JOIN `#__ap_useraccounts` b ON b.`id` = a.`user_id`
                        INNER JOIN `#__membertickets` c ON c.`title` = a.`ticket`
                        LEFT JOIN `#__member_assigned_tickets` d ON d.`ticket_id` = c.`id`
                        WHERE b.`id` IN(".implode(',',$serached_user).") AND b.`package_id` = '".$package_id."'"; */

                        $query = "
                        SELECT a.*, CONCAT(b.`firstname`, ' ', b.`lastname`) AS NAME, b.`firstname` AS firstname, b.`lastname` AS lastname,d.qty
                        FROM #__ticket_history a                        
                        INNER JOIN `#__ap_useraccounts` b ON b.`id` = a.`user_id`                        
                        LEFT JOIN `#__member_assigned_tickets` d ON d.`ticket_id` = a.`ticket_id`
                        WHERE b.`id` IN(".implode(',',$serached_user).") AND b.`package_id` = '".$package_id."'"; 

                        if( !empty($filter_start) && !empty($filter_end)){
                            $query .= " AND a.`created_date` >= '".$filter_start."' AND a.`created_date` <='".$filter_end."'";
                        }    
                        $query .= " AND a.`status` = '".$status."' ORDER BY a.created_date DESC";  

                        $this->_db->setQuery($query);        
                        $results = $this->_db->loadObjectList();
                        $this->total = count( $results );

                        $query .=" LIMIT ".$limitstart.", ".$limit." ";            
                        //echo str_replace('#__', 'raj_', $query);
                        $this->_db->setQuery($query);        
                        $results = $this->_db->loadObjectList();

                        if($results){
                            $results = array_reverse($results);
                            $total=0;
                            foreach ($results  as $key => $result) {
                                if($result->ref_id>0){
                                     $price = $result->amount;
                                     $total += $price;
                                     $total -= ($result->points_minus * 0.01);  
                                
                                }else{
                                    if($result->points_plus>0){
                                        $price = ($result->points_plus * 0.01);
                                    }else{
                                        $price = ($result->points_minus* 0.01);
                                    }
                                    $result->amount = $price;                              
                                    $total += ($result->points_plus * 0.01);
                                    $total -= ($result->points_minus *0.01);

                                }

                                $result->total = $total;                                
                            }
                            $results = array_reverse($results);
                        }
                        break;                    
                }             
            }
        }

        return $results;
    }

}
